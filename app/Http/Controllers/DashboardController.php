<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketCode;
use App\Models\Evenement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Fetch purchased tickets
        $tickets = TicketCode::with(['evenement', 'billet'])
            ->where('buyer_email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        $now = now();
        $activeTickets = $tickets->filter(function ($ticket) use ($now) {
            return $ticket->evenement && \Carbon\Carbon::parse($ticket->evenement->date)->gte($now);
        });

        $pastTickets = $tickets->filter(function ($ticket) use ($now) {
            return $ticket->evenement && \Carbon\Carbon::parse($ticket->evenement->date)->lt($now);
        });

        // Fetch recommendations (based on interests / upcoming events)
        $recommendations = Evenement::where('date', '>=', now())
            ->where('statut', 'publier')
            ->orderBy('date', 'asc')
            ->take(3)
            ->get();

        return view('dashboard', compact('user', 'activeTickets', 'pastTickets', 'recommendations'));
    }
}
