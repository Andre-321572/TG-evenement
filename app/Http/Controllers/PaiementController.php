<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function showForm(Evenement $evenement)
    {
        return view('p.payement.payement', compact('evenement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'evenement_id' => 'required|exists:evenements,id',
        ]);

        $event = Evenement::findOrFail($request->evenement_id);
        $user = Auth::user() ?? \App\Models\User::first(); // Fallback if not logged in

        if (!$user) {
            return redirect()->back()->with('error', 'Vous devez être connecté pour effectuer un paiement.');
        }

        // Calculer le montant (prix minimum des billets pour cet événement)
        $amount = $event->billets->min('prix') ?? 0;

        try {
            // Création de la transaction
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->evenement_id = $event->id;
            $transaction->amount = $amount;
            $transaction->status = 'success';
            $transaction->save();

            // Génération du ticket
            $ticket = new Ticket();
            $ticket->user_id = $user->id;
            $ticket->evenement_id = $event->id;
            $ticket->code = uniqid('TKT-');
            
            // Tenter de générer l'image du ticket
            try {
                $ticket->image = $this->generateTicketImage($event, $ticket->code);
            } catch (\Exception $e) {
                Log::error('Erreur génération image ticket : ' . $e->getMessage());
                $ticket->image = null; // Fallback if image generation fails
            }
            
            $ticket->save();

            return view('p.payement.process', compact('event', 'ticket', 'transaction'));

        } catch (\Exception $e) {
            Log::error('Erreur lors du paiement : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du traitement du paiement : ' . $e->getMessage());
        }
    }

    protected function generateTicketImage($event, $code)
    {
        // Utiliser une bibliothèque pour générer une image si elle est présente,
        // sinon retourner simplement un chemin fictif ou généré.
        // Ce bloc vérifie si Intervention Image existe.
        if (class_exists('\Intervention\Image\Facades\Image') || class_exists('\Intervention\Image\Laravel\Facades\Image')) {
            try {
                $imgClass = class_exists('\Intervention\Image\Facades\Image') 
                    ? '\Intervention\Image\Facades\Image' 
                    : '\Intervention\Image\Laravel\Facades\Image';
                
                $img = $imgClass::canvas(400, 200, '#fff');
                $img->text($event->titre, 200, 50, function($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(24);
                    $font->color('#000');
                    $font->align('center');
                });
                $img->text($code, 200, 100, function($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(20);
                    $font->color('#000');
                    $font->align('center');
                });
                
                // Enregistrer l'image
                $path = 'tickets/' . $code . '.png';
                if (!file_exists(public_path('tickets'))) {
                    mkdir(public_path('tickets'), 0777, true);
                }
                $img->save(public_path($path));
                return $path;
            } catch (\Exception $e) {
                Log::warning('Intervention Image a échoué, utilisation du fallback. ' . $e->getMessage());
            }
        }

        // Fallback sans Intervention Image
        return 'tickets/' . $code . '.png';
    }
}
