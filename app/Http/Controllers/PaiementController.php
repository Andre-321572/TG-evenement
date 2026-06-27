<?php

namespace App\Http\Controllers;

use App\Models\Billet;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaiementController extends Controller
{
    public function showForm(Evenement $evenement)
    {
        $evenement->load(['billets', 'sponsors']);
        return view('p.payement.payement', compact('evenement'));
    }

    /**
     * Créer une Stripe Checkout Session et rediriger l'utilisateur.
     */
    public function createCheckout(Request $request)
    {
        $request->validate([
            'evenement_id' => 'required|exists:evenements,id',
            'billet_id'    => 'required|exists:billets,id',
        ]);

        $evenement = Evenement::with('billets')->findOrFail($request->evenement_id);
        $billet    = Billet::findOrFail($request->billet_id);

        // Le prix Stripe doit être en centimes (CFA → centimes fictifs : 1 CFA = 1 centime)
        $amountCents = (int) ($billet->prix * 100);

        if ($amountCents <= 0) {
            return redirect()->back()->with('error', 'Prix du billet invalide.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'eur',   // Stripe test — en prod remplacer par XOF si disponible
                        'product_data' => [
                            'name'        => $evenement->titre . ' — ' . $billet->type,
                            'description' => 'Billet pour l\'événement du '
                                . \Carbon\Carbon::parse($evenement->date)->format('d M Y')
                                . ' à ' . $evenement->lieu,
                            'images' => $evenement->photo
                                ? [asset('storage/evenement/photo/' . $evenement->photo)]
                                : [],
                        ],
                        'unit_amount' => $amountCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('p.paiement.success') . '?session_id={CHECKOUT_SESSION_ID}&billet_id=' . $billet->id . '&evenement_id=' . $evenement->id,
                'cancel_url'  => route('p.paiement.cancel', $evenement->id),
                'metadata'    => [
                    'evenement_id' => $evenement->id,
                    'billet_id'    => $billet->id,
                    'user_id'      => Auth::id() ?? 0,
                ],
                'customer_email' => Auth::user()?->email,
            ]);

            return redirect($session->url, 303);

        } catch (\Exception $e) {
            Log::error('Stripe Checkout error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur Stripe : ' . $e->getMessage());
        }
    }

    /**
     * Stripe redirige ici après paiement réussi.
     */
    public function success(Request $request)
    {
        $sessionId  = $request->get('session_id');
        $billetId   = $request->get('billet_id');
        $evenementId = $request->get('evenement_id');

        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Session de paiement introuvable.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session   = StripeSession::retrieve($sessionId);
            $evenement = Evenement::with('billets')->findOrFail($evenementId);
            $billet    = Billet::findOrFail($billetId);

            // Générer un code unique de ticket
            $code = strtoupper('TGE-' . substr(md5($sessionId . $billetId), 0, 8));

            // Persister le code en base (idempotent via firstOrCreate)
            \App\Models\TicketCode::firstOrCreate(
                ['code' => $code],
                [
                    'evenement_id'      => $evenementId,
                    'billet_id'         => $billetId,
                    'stripe_session_id' => $sessionId,
                    'buyer_email'       => $session->customer_details?->email ?? '',
                    'buyer_name'        => $session->customer_details?->name ?? '',
                ]
            );

            return view('p.payement.success', compact('evenement', 'billet', 'session', 'code'));

        } catch (\Exception $e) {
            Log::error('Stripe success page error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Impossible de confirmer le paiement.');
        }
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
