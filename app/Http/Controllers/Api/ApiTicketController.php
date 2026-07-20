<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ApiTicketController extends Controller
{
    /**
     * Récupérer la liste des billets achetés par l'utilisateur connecté.
     */
    public function myTickets(Request $request)
    {
        $user = $request->user();

        // Les tickets achetés sont reliés par le user_id de l'acheteur, ou son email (repli de compatibilité)
        $tickets = TicketCode::with(['evenement', 'billet'])
            ->where('user_id', $user->id)
            ->orWhere('buyer_email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        // Formater les données pour inclure les URL absolues de la photo de l'événement
        foreach ($tickets as $ticket) {
            if ($ticket->evenement) {
                $ticket->evenement->photo_url = $ticket->evenement->photo 
                    ? asset('storage/evenement/photo/' . $ticket->evenement->photo) 
                    : asset('images/default-event.jpg');
            }
        }

        return response()->json([
            'status' => 'success',
            'tickets' => $tickets
        ], 200);
    }

    /**
     * Initier un paiement depuis l'application mobile (flux 100% natif).
     * 
     * - Stripe : retourne un client_secret pour la Payment Sheet native
     * - LeekPay (Moov/TMoney) : initie le paiement et retourne un transaction_id pour le polling
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'evenement_id'   => 'required|exists:evenements,id',
            'billet_id'      => 'required|exists:billets,id',
            'quantity'       => 'nullable|integer|min:1|max:100',
            'email'          => 'nullable|email',
            'user_id'        => 'nullable|integer',
            'payment_method' => 'required|in:stripe,moov_money,mix_by_yas',
        ]);

        $evenement = \App\Models\Evenement::findOrFail($request->evenement_id);

        if ($evenement->isPasse()) {
            return response()->json(['status' => 'error', 'message' => 'Cet événement est déjà passé.'], 400);
        }

        $billet    = \App\Models\Billet::findOrFail($request->billet_id);
        $quantity  = (int) $request->input('quantity', 1);

        if ($billet->quantite_disponible < $quantity) {
            return response()->json(['status' => 'error', 'message' => 'Quantité demandée supérieure au stock disponible.'], 400);
        }

        $buyerUserId = $request->input('user_id') ?? Auth::guard('sanctum')->id() ?? 0;
        $customerEmail = $request->input('email') ?? Auth::guard('sanctum')->user()?->email ?? 'client@example.com';
        $buyerName = $request->input('name') ?? (Auth::guard('sanctum')->user() ? Auth::guard('sanctum')->user()->prenom . ' ' . Auth::guard('sanctum')->user()->nom : 'Client Mobile');

        if ($request->payment_method === 'stripe') {
            return $this->handleStripeNativeCheckout($evenement, $billet, $quantity, $buyerUserId, $customerEmail, $buyerName);
        } else {
            return $this->handleLeekpayNativeCheckout($request, $evenement, $billet, $quantity, $buyerUserId, $customerEmail, $buyerName);
        }
    }

    /**
     * Stripe : Crée un PaymentIntent et retourne le client_secret pour la Payment Sheet native.
     * Plus besoin de WebView ou d'URL de redirection.
     */
    private function handleStripeNativeCheckout($evenement, $billet, $quantity, $buyerUserId, $customerEmail, $buyerName)
    {
        $currency = strtolower(config('services.stripe.currency', 'xof'));
        $zeroDecimalCurrencies = ['bif', 'clf', 'djf', 'gnf', 'isk', 'jpy', 'kmf', 'krw', 'mga', 'pyg', 'rwf', 'ugx', 'vnd', 'vuv', 'xaf', 'xof', 'xpf'];
        
        $unitAmount = in_array($currency, $zeroDecimalCurrencies)
            ? (int) $billet->prix
            : (int) ($billet->prix * 100);

        $totalAmount = $unitAmount * $quantity;

        if ($totalAmount <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Prix du billet invalide.'], 400);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Créer ou récupérer un Customer Stripe pour une meilleure UX
            $customer = \Stripe\Customer::create([
                'email' => $customerEmail,
                'name'  => $buyerName,
            ]);

            // Créer un PaymentIntent (pas une Session !)
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount'   => $totalAmount,
                'currency' => $currency,
                'customer' => $customer->id,
                'metadata' => [
                    'evenement_id' => $evenement->id,
                    'billet_id'    => $billet->id,
                    'user_id'      => $buyerUserId,
                    'quantity'     => $quantity,
                    'email'        => $customerEmail,
                    'name'         => $buyerName,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            // Créer un Ephemeral Key pour le Customer (nécessaire pour la Payment Sheet)
            $ephemeralKey = \Stripe\EphemeralKey::create(
                ['customer' => $customer->id],
                ['stripe_version' => '2023-10-16']
            );

            return response()->json([
                'status'         => 'success',
                'payment_type'   => 'stripe_native',
                'client_secret'  => $paymentIntent->client_secret,
                'ephemeral_key'  => $ephemeralKey->secret,
                'customer_id'    => $customer->id,
                'publishable_key' => config('services.stripe.key'),
                // Métadonnées pour le mobile puisse confirmer après paiement
                'transaction_id' => $paymentIntent->id,
                'evenement_id'   => $evenement->id,
                'billet_id'      => $billet->id,
                'quantity'       => $quantity,
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Stripe PaymentIntent error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Erreur Stripe : ' . $e->getMessage()], 500);
        }
    }

    /**
     * LeekPay (Moov Money / TMoney) : Initie le paiement et retourne un transaction_id.
     * L'app mobile affichera un écran d'attente natif et utilisera le polling pour vérifier le statut.
     */
    private function handleLeekpayNativeCheckout($request, $evenement, $billet, $quantity, $buyerUserId, $customerEmail, $buyerName)
    {
        $currency = strtoupper(config('services.leekpay.currency', 'XOF'));
        $amount = (int) ($billet->prix * $quantity);
        $leekpaySecret = config('services.leekpay.secret');

        if (!$leekpaySecret) {
            return response()->json(['status' => 'error', 'message' => 'La clé secrète LeekPay n\'est pas configurée.'], 500);
        }

        try {
            $response = Http::withToken($leekpaySecret)
                ->post('https://leekpay.fr/api/v1/checkout', [
                    'amount'         => $amount,
                    'currency'       => $currency,
                    'description'    => 'Billet(s) pour : ' . $evenement->titre . ' — ' . $billet->type,
                    'return_url'     => route('p.paiement.success') . '?gateway=leekpay&billet_id=' . $billet->id . '&evenement_id=' . $evenement->id . '&quantity=' . $quantity . '&email=' . urlencode($customerEmail) . '&name=' . urlencode($buyerName) . '&hide_layout=1',
                    'cancel_url'     => route('p.paiement.cancel', $evenement->id) . '?hide_layout=1',
                    'webhook_url'    => route('p.paiement.webhook'),
                    'customer_email' => $customerEmail,
                    'customer_name'  => $buyerName,
                    'customer_phone' => $request->input('phone'),
                    'metadata'       => [
                        'evenement_id'   => $evenement->id,
                        'billet_id'      => $billet->id,
                        'user_id'        => $buyerUserId,
                        'quantity'       => $quantity,
                        'email'          => $customerEmail,
                        'name'           => $buyerName,
                        'payment_method' => $request->payment_method
                    ]
                ]);

            $resData = $response->json();

            if ($response->successful() && isset($resData['data'])) {
                $checkoutId = $resData['data']['id'] ?? $resData['data']['checkout_id'] ?? null;

                return response()->json([
                    'status'          => 'success',
                    'payment_type'    => 'mobile_money',
                    'transaction_id'  => $checkoutId,
                    'payment_method'  => $request->payment_method,
                    'evenement_id'    => $evenement->id,
                    'billet_id'       => $billet->id,
                    'quantity'        => $quantity,
                    'amount'          => $amount,
                    'currency'        => $currency,
                    // On fournit quand même le payment_url en fallback au cas où le Push USSD ne se déclenche pas
                    'payment_url'     => $resData['data']['payment_url'] ?? null,
                ], 200);
            } else {
                $errorMsg = $resData['message'] ?? 'Erreur inconnue avec LeekPay.';
                return response()->json(['status' => 'error', 'message' => 'Erreur LeekPay : ' . $errorMsg], 400);
            }
        } catch (\Exception $e) {
            Log::error('API LeekPay Checkout error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Erreur LeekPay : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Vérifier le statut d'un paiement (polling depuis l'app mobile).
     * Utilisé pour Mobile Money (LeekPay) et pour confirmer Stripe après Payment Sheet.
     */
    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
            'payment_type'   => 'required|in:stripe_native,mobile_money',
            'evenement_id'   => 'required|exists:evenements,id',
            'billet_id'      => 'required|exists:billets,id',
            'quantity'       => 'nullable|integer|min:1',
        ]);

        $transactionId = $request->input('transaction_id');
        $paymentType   = $request->input('payment_type');
        $evenementId   = $request->input('evenement_id');
        $billetId      = $request->input('billet_id');
        $quantity      = (int) $request->input('quantity', 1);
        $buyerUserId   = $request->input('user_id') ?? Auth::guard('sanctum')->id() ?? 0;
        $customerEmail = $request->input('email') ?? Auth::guard('sanctum')->user()?->email ?? 'client@example.com';
        $buyerName     = $request->input('name') ?? 'Client Mobile';

        try {
            if ($paymentType === 'stripe_native') {
                return $this->checkStripePaymentStatus($transactionId, $evenementId, $billetId, $quantity, $buyerUserId, $customerEmail, $buyerName);
            } else {
                return $this->checkLeekpayPaymentStatus($transactionId, $evenementId, $billetId, $quantity, $buyerUserId, $customerEmail, $buyerName);
            }
        } catch (\Exception $e) {
            Log::error('Payment status check error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Erreur lors de la vérification du paiement.'], 500);
        }
    }

    /**
     * Vérifier le statut d'un PaymentIntent Stripe et générer les tickets si réussi.
     */
    private function checkStripePaymentStatus($paymentIntentId, $evenementId, $billetId, $quantity, $buyerUserId, $customerEmail, $buyerName)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

        if ($paymentIntent->status === 'succeeded') {
            // Vérifier si les tickets ont déjà été générés (éviter les doublons)
            $existingTickets = TicketCode::where('stripe_session_id', $paymentIntentId)->get();

            if ($existingTickets->isEmpty()) {
                $billet = \App\Models\Billet::findOrFail($billetId);
                $billet->vendre($quantity);

                $codes = [];
                for ($i = 0; $i < $quantity; $i++) {
                    $code = strtoupper('TGE-' . substr(md5($paymentIntentId . $billetId . $i), 0, 8));
                    $codes[] = $code;

                    TicketCode::create([
                        'code'              => $code,
                        'evenement_id'      => $evenementId,
                        'billet_id'         => $billetId,
                        'stripe_session_id' => $paymentIntentId,
                        'buyer_email'       => $customerEmail,
                        'buyer_name'        => $buyerName,
                        'user_id'           => $buyerUserId,
                    ]);
                }

                return response()->json([
                    'status'  => 'paid',
                    'message' => 'Paiement confirmé ! Vos billets ont été générés.',
                    'codes'   => $codes,
                ], 200);
            } else {
                return response()->json([
                    'status'  => 'paid',
                    'message' => 'Billets déjà générés.',
                    'codes'   => $existingTickets->pluck('code')->toArray(),
                ], 200);
            }
        } elseif (in_array($paymentIntent->status, ['requires_payment_method', 'canceled'])) {
            return response()->json([
                'status'  => 'failed',
                'message' => 'Le paiement a échoué ou a été annulé.',
            ], 200);
        } else {
            return response()->json([
                'status'  => 'pending',
                'message' => 'Paiement en cours de traitement...',
            ], 200);
        }
    }

    /**
     * Vérifier le statut d'un paiement LeekPay (Moov/TMoney) et générer les tickets si réussi.
     */
    private function checkLeekpayPaymentStatus($checkoutId, $evenementId, $billetId, $quantity, $buyerUserId, $customerEmail, $buyerName)
    {
        $leekpaySecret = config('services.leekpay.secret');

        if (!$leekpaySecret) {
            return response()->json(['status' => 'error', 'message' => 'Clé LeekPay non configurée.'], 500);
        }

        $response = Http::withToken($leekpaySecret)
            ->get("https://leekpay.fr/api/v1/checkout/{$checkoutId}");

        if (!$response->successful()) {
            return response()->json(['status' => 'pending', 'message' => 'Impossible de vérifier le statut. Réessai en cours...'], 200);
        }

        $resData = $response->json();
        $paymentStatus = $resData['data']['status'] ?? '';

        if ($paymentStatus === 'paid') {
            // Vérifier si les tickets ont déjà été générés (via webhook ou appel précédent)
            $existingTickets = TicketCode::where('stripe_session_id', $checkoutId)->get();

            if ($existingTickets->isEmpty()) {
                $billet = \App\Models\Billet::findOrFail($billetId);
                $billet->vendre($quantity);

                // Mettre à jour le paiement local si existant
                $payment = \App\Models\Paiement::where('reference', $checkoutId)->first();
                if ($payment) {
                    $payment->update(['status' => 'completed']);
                }

                $codes = [];
                for ($i = 0; $i < $quantity; $i++) {
                    $code = strtoupper('TGE-' . substr(md5($checkoutId . $billetId . $i), 0, 8));
                    $codes[] = $code;

                    TicketCode::create([
                        'code'              => $code,
                        'evenement_id'      => $evenementId,
                        'billet_id'         => $billetId,
                        'stripe_session_id' => $checkoutId,
                        'buyer_email'       => $customerEmail,
                        'buyer_name'        => $buyerName,
                        'user_id'           => $buyerUserId,
                    ]);
                }

                return response()->json([
                    'status'  => 'paid',
                    'message' => 'Paiement confirmé ! Vos billets ont été générés.',
                    'codes'   => $codes,
                ], 200);
            } else {
                return response()->json([
                    'status'  => 'paid',
                    'message' => 'Billets déjà générés.',
                    'codes'   => $existingTickets->pluck('code')->toArray(),
                ], 200);
            }
        } elseif (in_array($paymentStatus, ['failed', 'expired', 'cancelled'])) {
            return response()->json([
                'status'  => 'failed',
                'message' => 'Le paiement a échoué ou a expiré.',
            ], 200);
        } else {
            // pending, processing, etc.
            return response()->json([
                'status'  => 'pending',
                'message' => 'En attente de votre validation sur votre téléphone...',
            ], 200);
        }
    }
}
