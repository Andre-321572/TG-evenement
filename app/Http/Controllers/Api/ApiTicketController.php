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

        // Les tickets achetés sont reliés par l'email de l'acheteur
        $tickets = TicketCode::with(['evenement', 'billet'])
            ->where('buyer_email', $user->email)
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
}
