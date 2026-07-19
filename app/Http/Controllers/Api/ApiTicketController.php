<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Initier un paiement depuis l'application mobile.
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
            $currency = strtolower(config('services.stripe.currency', 'xof'));
            $zeroDecimalCurrencies = ['bif', 'clf', 'djf', 'gnf', 'isk', 'jpy', 'kmf', 'krw', 'mga', 'pyg', 'rwf', 'ugx', 'vnd', 'vuv', 'xaf', 'xof', 'xpf'];
            
            $amount = in_array($currency, $zeroDecimalCurrencies)
                ? (int) $billet->prix
                : (int) ($billet->prix * 100);

            if ($amount <= 0) {
                return response()->json(['status' => 'error', 'message' => 'Prix du billet invalide.'], 400);
            }

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            try {
                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency'     => $currency,
                            'product_data' => [
                                'name'        => $evenement->titre . ' — ' . $billet->type,
                            ],
                            'unit_amount' => $amount,
                        ],
                        'quantity' => $quantity,
                    ]],
                    'mode'        => 'payment',
                    'success_url' => route('p.paiement.success') . '?session_id={CHECKOUT_SESSION_ID}&billet_id=' . $billet->id . '&evenement_id=' . $evenement->id . '&quantity=' . $quantity . '&email=' . urlencode($customerEmail) . '&name=' . urlencode($buyerName) . '&hide_layout=1',
                    'cancel_url'  => route('p.paiement.cancel', $evenement->id) . '?hide_layout=1',
                    'metadata'    => [
                        'evenement_id' => $evenement->id,
                        'billet_id'    => $billet->id,
                        'user_id'      => $buyerUserId,
                        'quantity'     => $quantity,
                        'email'        => $customerEmail,
                        'name'         => $buyerName,
                    ],
                    'customer_email' => $customerEmail,
                ]);

                return response()->json([
                    'status' => 'success',
                    'payment_url' => $session->url
                ], 200);

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('API Stripe Checkout error: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Erreur Stripe : ' . $e->getMessage()], 500);
            }
        } else {
            // Mobile Money via LeekPay (moov_money, mix_by_yas)
            $currency = strtoupper(config('services.leekpay.currency', 'XOF'));
            $amount = (int) ($billet->prix * $quantity);
            $leekpaySecret = config('services.leekpay.secret');

            if (!$leekpaySecret) {
                return response()->json(['status' => 'error', 'message' => 'La clé secrète LeekPay n\'est pas configurée.'], 500);
            }

            try {
                $response = \Illuminate\Support\Facades\Http::withToken($leekpaySecret)
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

                if ($response->successful() && isset($resData['data']['payment_url'])) {
                    return response()->json([
                        'status' => 'success',
                        'payment_url' => $resData['data']['payment_url']
                    ], 200);
                } else {
                    $errorMsg = $resData['message'] ?? 'Erreur inconnue avec LeekPay.';
                    return response()->json(['status' => 'error', 'message' => 'Erreur LeekPay : ' . $errorMsg], 400);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('API LeekPay Checkout error: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Erreur LeekPay : ' . $e->getMessage()], 500);
            }
        }
    }
}
