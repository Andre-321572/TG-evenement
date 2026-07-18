<?php

namespace App\Http\Controllers;

use App\Models\Billet;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaiementController extends Controller
{
    public function showForm(Evenement $evenement)
    {
        if ($evenement->isPasse()) {
            return redirect()->route('p.detail', $evenement->id)->with('error', 'Cet événement est déjà passé, vous ne pouvez plus acheter de tickets.');
        }
        $evenement->load(['billets', 'sponsors']);
        return view('p.payement.payement', compact('evenement'));
    }

    /**
     * Créer une Stripe Checkout Session et rediriger l'utilisateur.
     */
    public function createCheckout(Request $request)
    {
        $request->validate([
            'evenement_id'   => 'required|exists:evenements,id',
            'billet_id'      => 'required|exists:billets,id',
            'quantity'       => 'nullable|integer|min:1|max:100',
            'email'          => 'nullable|email',
            'user_id'        => 'nullable|integer',
            'payment_method' => 'required|in:stripe,moov_money,mix_by_yas,leekpay',
            'phone'          => 'required_if:payment_method,moov_money,mix_by_yas,leekpay|nullable|string',
        ]);

        $evenement = Evenement::with('billets')->findOrFail($request->evenement_id);

        if ($evenement->isPasse()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Cet événement est déjà passé, vous ne pouvez plus acheter de tickets.'], 422);
            }
            return redirect()->route('p.detail', $evenement->id)->with('error', 'Cet événement est déjà passé, vous ne pouvez plus acheter de tickets.');
        }

        $billet    = Billet::findOrFail($request->billet_id);
        $quantity  = (int) $request->input('quantity', 1);

        if ($billet->quantite_disponible < $quantity) {
            return redirect()->back()->with('error', 'Quantité demandée supérieure au stock disponible.');
        }

        $buyerUserId = $request->input('user_id') ?? Auth::id() ?? 0;
        $customerEmail = $request->input('email') ?? Auth::user()?->email ?? 'client@example.com';
        $buyerName = $request->input('name') ?? (Auth::user() ? Auth::user()->prenom . ' ' . Auth::user()->nom : 'Client');

        if ($request->payment_method === 'stripe') {
            $currency = strtolower(config('services.stripe.currency', 'xof'));
            $zeroDecimalCurrencies = ['bif', 'clf', 'djf', 'gnf', 'isk', 'jpy', 'kmf', 'krw', 'mga', 'pyg', 'rwf', 'ugx', 'vnd', 'vuv', 'xaf', 'xof', 'xpf'];
            
            $amount = in_array($currency, $zeroDecimalCurrencies)
                ? (int) $billet->prix
                : (int) ($billet->prix * 100);

            if ($amount <= 0) {
                return redirect()->back()->with('error', 'Prix du billet invalide.');
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            try {
                $session = StripeSession::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency'     => $currency,
                            'product_data' => [
                                'name'        => $evenement->titre . ' — ' . $billet->type,
                                'description' => 'Billet pour l\'événement du '
                                    . \Carbon\Carbon::parse($evenement->date)->format('d M Y')
                                    . ' à ' . $evenement->lieu,
                                'images' => $evenement->photo
                                    ? [asset('storage/evenement/photo/' . $evenement->photo)]
                                    : [],
                            ],
                            'unit_amount' => $amount,
                        ],
                        'quantity' => $quantity,
                    ]],
                    'mode'        => 'payment',
                    'success_url' => route('p.paiement.success') . '?session_id={CHECKOUT_SESSION_ID}&billet_id=' . $billet->id . '&evenement_id=' . $evenement->id . '&hide_layout=1',
                    'cancel_url'  => route('p.paiement.cancel', $evenement->id) . '?hide_layout=1',
                    'metadata'    => [
                        'evenement_id' => $evenement->id,
                        'billet_id'    => $billet->id,
                        'user_id'      => $buyerUserId,
                        'quantity'     => $quantity,
                    ],
                    'customer_email' => $customerEmail,
                ]);

                return redirect($session->url, 303);

            } catch (\Exception $e) {
                Log::error('Stripe Checkout error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Erreur Stripe : ' . $e->getMessage());
            }
        } elseif ($request->payment_method === 'leekpay') {
            $currency = strtoupper(config('services.leekpay.currency', 'XOF'));
            $amount = (int) $billet->prix * $quantity;
            $leekpaySecret = config('services.leekpay.secret');

            if (!$leekpaySecret) {
                return redirect()->back()->with('error', 'La clé secrète LeekPay n\'est pas configurée.');
            }

            try {
                $response = Http::withToken($leekpaySecret)
                    ->post('https://leekpay.fr/api/v1/checkout', [
                        'amount'         => $amount,
                        'currency'       => $currency,
                        'description'    => 'Billet(s) pour : ' . $evenement->titre . ' — ' . $billet->type,
                        'return_url'     => route('p.paiement.success') . '?gateway=leekpay&session_id={checkout_id}&billet_id=' . $billet->id . '&evenement_id=' . $evenement->id . '&quantity=' . $quantity . '&user_id=' . $buyerUserId . '&email=' . urlencode($customerEmail) . '&name=' . urlencode($buyerName) . '&hide_layout=1',
                        'cancel_url'     => route('p.paiement.cancel', $evenement->id) . '?hide_layout=1',
                        'customer_email' => $customerEmail,
                        'customer_name'  => $buyerName,
                        'customer_phone' => $request->input('phone'),
                        'metadata'       => [
                            'evenement_id' => $evenement->id,
                            'billet_id'    => $billet->id,
                            'user_id'      => $buyerUserId,
                            'quantity'     => $quantity,
                            'email'        => $customerEmail,
                            'name'         => $buyerName,
                        ]
                    ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    if (isset($resData['success']) && $resData['success'] && isset($resData['data']['payment_url'])) {
                        $checkoutId = $resData['data']['id'];
                        $paymentUrl = $resData['data']['payment_url'];

                        // Save Payment Record
                        \App\Models\Paiement::create([
                            'user_id'        => $buyerUserId ?: null,
                            'evenement_id'   => $evenement->id,
                            'amount'         => $amount,
                            'status'         => 'pending',
                            'payment_method' => 'leekpay',
                            'reference'      => $checkoutId,
                        ]);

                        // Stocker le checkout ID dans la session
                        session(['pending_leekpay_checkout_id' => $checkoutId]);

                        return redirect($paymentUrl);
                    }
                }

                Log::error('LeekPay checkout creation failed: ' . $response->body());
                return redirect()->back()->with('error', 'Erreur d\'initialisation LeekPay : ' . ($response->json('message') ?? 'Erreur inconnue'));

            } catch (\Exception $e) {
                Log::error('LeekPay checkout exception: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Erreur de connexion LeekPay : ' . $e->getMessage());
            }
        } else {
            // Moov Money or MIX by Yas simulated checkout
            $sessionId = 'LOCAL-' . strtoupper($request->payment_method) . '-' . strtoupper(uniqid());

            // Save Payment Record
            \App\Models\Paiement::create([
                'user_id'        => $buyerUserId ?: null,
                'evenement_id'   => $evenement->id,
                'amount'         => $billet->prix * $quantity,
                'status'         => 'completed',
                'payment_method' => $request->payment_method,
                'reference'      => $sessionId,
            ]);

            return redirect()->route('p.paiement.success', [
                'session_id'   => $sessionId,
                'billet_id'    => $billet->id,
                'evenement_id' => $evenement->id,
                'quantity'     => $quantity,
                'email'        => $customerEmail,
                'name'         => $buyerName,
                'hide_layout'  => 1
            ]);
        }
    }

    /**
     * Stripe redirige ici après paiement réussi.
     */
    public function success(Request $request)
    {
        $sessionId  = $request->get('session_id');
        if ($sessionId === '{checkout_id}') {
            $sessionId = null;
        }
        $billetId   = $request->get('billet_id');
        $evenementId = $request->get('evenement_id');

        // Handle LeekPay redirection and status verification
        if ($request->get('gateway') === 'leekpay') {
            $checkoutId = $sessionId ?? session('pending_leekpay_checkout_id');
            $quantity   = (int)$request->get('quantity', 1);
            $buyerUserId = $request->get('user_id') ?? Auth::id() ?? null;
            $customerEmail = urldecode($request->get('email') ?? 'client@example.com');
            $buyerName = urldecode($request->get('name') ?? 'Client');

            // Fallback to find successful payment in database if session/ID was lost
            if (!$checkoutId) {
                $payment = \App\Models\Paiement::where('user_id', $buyerUserId)
                    ->where('evenement_id', $evenementId)
                    ->where('payment_method', 'leekpay')
                    ->where('status', 'completed')
                    ->orderBy('updated_at', 'desc')
                    ->first();
                if ($payment) {
                    $checkoutId = $payment->reference;
                }
            }

            if (!$checkoutId) {
                return redirect()->route('home')->with('error', 'Session de paiement LeekPay introuvable.');
            }

            try {
                $evenement = Evenement::with('billets')->findOrFail($evenementId);
                $billet    = Billet::findOrFail($billetId);

                // Check if tickets have already been generated (e.g. by Webhook)
                $ticketCodes = \App\Models\TicketCode::where('stripe_session_id', $checkoutId)->get();

                if ($ticketCodes->isEmpty()) {
                    $leekpaySecret = config('services.leekpay.secret');
                    if (!$leekpaySecret) {
                        return redirect()->route('home')->with('error', 'Clé secrète LeekPay non configurée.');
                    }

                    $response = Http::withToken($leekpaySecret)
                        ->get("https://leekpay.fr/api/v1/checkout/{$checkoutId}");

                    if ($response->successful()) {
                        $resData = $response->json();
                        $status = $resData['data']['status'] ?? '';

                        if ($status === 'paid') {
                            // Update local payment record
                            $payment = \App\Models\Paiement::where('reference', $checkoutId)->first();
                            if ($payment) {
                                $payment->update(['status' => 'completed']);
                            }

                            // Decrement ticket quantity
                            $billet->vendre($quantity);

                            $codes = [];
                            for ($i = 0; $i < $quantity; $i++) {
                                $code = strtoupper('TGE-' . substr(md5($checkoutId . $billetId . $i), 0, 8));
                                $codes[] = $code;

                                \App\Models\TicketCode::create([
                                    'code'              => $code,
                                    'evenement_id'      => $evenementId,
                                    'billet_id'         => $billetId,
                                    'stripe_session_id' => $checkoutId,
                                    'buyer_email'       => $customerEmail,
                                    'buyer_name'        => $buyerName,
                                    'user_id'           => $buyerUserId,
                                ]);
                            }
                        } else {
                            return redirect()->route('p.detail', $evenementId)
                                ->with('error', 'Le paiement LeekPay n\'a pas encore été finalisé (statut : ' . $status . ').');
                        }
                    } else {
                        return redirect()->route('p.detail', $evenementId)
                            ->with('error', 'Impossible de vérifier le statut auprès de LeekPay.');
                    }
                } else {
                    $codes = $ticketCodes->pluck('code')->toArray();
                }

                $code = $codes[0] ?? '';

                // Mock session object for blade view
                $session = (object)[
                    'id' => $checkoutId,
                    'customer_details' => (object)[
                        'email' => $customerEmail,
                        'name' => $buyerName
                    ]
                ];

                return view('p.payement.success', compact('evenement', 'billet', 'session', 'code', 'codes', 'quantity'));
            } catch (\Exception $e) {
                Log::error('LeekPay redirect success verification error: ' . $e->getMessage());
                return redirect()->route('home')->with('error', 'Une erreur est survenue lors de la validation du paiement.');
            }
        }

        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Session de paiement introuvable.');
        }

        // Handle local simulated mobile payment (Moov Money / MIX by Yas)
        if (str_starts_with($sessionId, 'LOCAL-')) {
            try {
                $evenement = Evenement::with('billets')->findOrFail($evenementId);
                $billet    = Billet::findOrFail($billetId);
                $quantity  = (int)$request->get('quantity', 1);
                $buyerUserId = $request->get('user_id') ?? Auth::id() ?? null;
                $customerEmail = $request->get('email') ?? 'client@example.com';
                $buyerName = $request->get('name') ?? 'Client';

                // Check if already processed to prevent duplicate decrement on refresh
                $ticketCodes = \App\Models\TicketCode::where('stripe_session_id', $sessionId)->get();
                
                if ($ticketCodes->isEmpty()) {
                    // Decrement stock
                    $billet->vendre($quantity);

                    $codes = [];
                    for ($i = 0; $i < $quantity; $i++) {
                        $code = strtoupper('TGE-' . substr(md5($sessionId . $billetId . $i), 0, 8));
                        $codes[] = $code;

                        \App\Models\TicketCode::create([
                            'code'              => $code,
                            'evenement_id'      => $evenementId,
                            'billet_id'         => $billetId,
                            'stripe_session_id' => $sessionId,
                            'buyer_email'       => $customerEmail,
                            'buyer_name'        => $buyerName,
                            'user_id'           => $buyerUserId,
                        ]);
                    }
                } else {
                    $codes = $ticketCodes->pluck('code')->toArray();
                }

                $code = $codes[0] ?? '';

                // Mock session object for blade view
                $session = (object)[
                    'id' => $sessionId,
                    'customer_details' => (object)[
                        'email' => $customerEmail,
                        'name' => $buyerName
                    ]
                ];

                return view('p.payement.success', compact('evenement', 'billet', 'session', 'code', 'codes', 'quantity'));
            } catch (\Exception $e) {
                Log::error('Local success page error: ' . $e->getMessage());
                return redirect()->route('home')->with('error', 'Impossible de confirmer le paiement.');
            }
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session   = StripeSession::retrieve($sessionId);
            $evenement = Evenement::with('billets')->findOrFail($evenementId);
            $billet    = Billet::findOrFail($billetId);

            // Récupérer le user_id depuis les metadata Stripe
            $buyerUserId = isset($session->metadata->user_id) && (int) $session->metadata->user_id > 0 
                ? (int) $session->metadata->user_id 
                : null;

            $quantity = isset($session->metadata->quantity) ? (int)$session->metadata->quantity : 1;

            // Décrémenter la quantité disponible de billets
            $billet->vendre($quantity);

            $codes = [];
            for ($i = 0; $i < $quantity; $i++) {
                // Générer un code unique de ticket (indexé par $i pour éviter les doublons md5)
                $code = strtoupper('TGE-' . substr(md5($sessionId . $billetId . $i), 0, 8));
                $codes[] = $code;

                // Persister le code en base
                \App\Models\TicketCode::firstOrCreate(
                    ['code' => $code],
                    [
                        'evenement_id'      => $evenementId,
                        'billet_id'         => $billetId,
                        'stripe_session_id' => $sessionId,
                        'buyer_email'       => $session->customer_details?->email ?? '',
                        'buyer_name'        => $session->customer_details?->name ?? '',
                        'user_id'           => $buyerUserId,
                    ]
                );
            }

            // Pour la vue, utiliser le premier code ou la liste des codes
            $code = $codes[0] ?? '';

            return view('p.payement.success', compact('evenement', 'billet', 'session', 'code', 'codes', 'quantity'));

        } catch (\Exception $e) {
            Log::error('Stripe success page error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Impossible de confirmer le paiement.');
        }
    }

    /**
     * Webhook pour LeekPay.
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('X-LeekPay-Signature');
        $payload = $request->getContent();
        $publicKey = config('services.leekpay.key');

        if (!$signature || !$publicKey) {
            Log::warning('LeekPay Webhook : signature ou clé publique manquante.');
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        $expected = hash_hmac('sha256', $payload, $publicKey);

        if (!hash_equals($expected, $signature)) {
            Log::warning('LeekPay Webhook : signature invalide.');
            return response()->json(['message' => 'Signature invalide'], 401);
        }

        $data = json_decode($payload, true);
        if (!$data || !isset($data['event']) || $data['event'] !== 'payment.completed') {
            return response()->json(['message' => 'Événement ignoré'], 200);
        }

        $checkoutData = $data['data'] ?? [];
        $checkoutId = $checkoutData['checkout_id'] ?? null;
        $status = $checkoutData['status'] ?? '';

        if ($checkoutId && $status === 'paid') {
            $payment = \App\Models\Paiement::where('reference', $checkoutId)->first();

            if ($payment) {
                if ($payment->status !== 'completed') {
                    $payment->update(['status' => 'completed']);

                    // Traitement de la génération des tickets
                    $metadata = $checkoutData['metadata'] ?? [];
                    $evenementId = $metadata['evenement_id'] ?? $payment->evenement_id;
                    $billetId = $metadata['billet_id'] ?? null;
                    $quantity = (int)($metadata['quantity'] ?? 1);
                    $buyerUserId = $metadata['user_id'] ?? $payment->user_id;
                    $customerEmail = urldecode($metadata['email'] ?? $checkoutData['customer']['email'] ?? 'client@example.com');
                    $buyerName = urldecode($metadata['name'] ?? $checkoutData['customer']['name'] ?? 'Client');

                    $billet = Billet::find($billetId);
                    if ($billet) {
                        // Décrémenter le stock
                        $billet->vendre($quantity);

                        for ($i = 0; $i < $quantity; $i++) {
                            $code = strtoupper('TGE-' . substr(md5($checkoutId . $billetId . $i), 0, 8));

                            \App\Models\TicketCode::firstOrCreate(
                                ['code' => $code],
                                [
                                    'evenement_id'      => $evenementId,
                                    'billet_id'         => $billetId,
                                    'stripe_session_id' => $checkoutId,
                                    'buyer_email'       => $customerEmail,
                                    'buyer_name'        => $buyerName,
                                    'user_id'           => $buyerUserId,
                                ]
                            );
                        }
                    }
                }
                return response()->json(['message' => 'Paiement traité avec succès'], 200);
            }
        }

        return response()->json(['message' => 'Paiement introuvable ou non payé'], 200);
    }

    /**
     * Stripe redirige ici si l'utilisateur annule.
     */
    public function cancel(Evenement $evenement)
    {
        return redirect()
            ->route('p.detail', $evenement->id)
            ->with('error', 'Paiement annulé. Vous pouvez réessayer à tout moment.');
    }

    // Ancienne méthode conservée pour compatibilité
    public function processPayment(Request $request)
    {
        return $this->createCheckout($request);
    }
}
