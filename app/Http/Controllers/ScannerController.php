<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\TicketCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ScannerController extends Controller
{
    /**
     * Scanner dashboard — shows published events and the camera interface.
     */
    public function dashboard()
    {
        $evenements = Evenement::publie()->orderBy('date', 'desc')->get();
        return view('scanner.dashboard', compact('evenements'));
    }

    /**
     * AJAX: verify a scanned QR code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));

        $ticket = TicketCode::with(['evenement', 'billet'])
            ->where('code', $code)
            ->first();

        if (!$ticket) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Code billet invalide.',
            ]);
        }

        if ($ticket->is_scanned) {
            return response()->json([
                'status'  => 'already_scanned',
                'message' => 'Billet déjà scanné le ' . $ticket->scanned_at?->format('d/m/Y à H:i') . '.',
                'ticket'  => $this->formatTicket($ticket),
            ]);
        }

        // Mark as scanned
        $ticket->update([
            'is_scanned' => true,
            'scanned_at' => now(),
            'scanned_by' => auth()->id(),
        ]);

        return response()->json([
            'status'  => 'valid',
            'message' => 'Accès autorisé !',
            'ticket'  => $this->formatTicket($ticket),
        ]);
    }

    /**
     * Stats: scanned tickets count for an event today.
     */
    public function stats(Request $request)
    {
        $evenementId = $request->evenement_id;

        $query = TicketCode::where('is_scanned', true)
            ->whereDate('scanned_at', today());

        if ($evenementId) {
            $query->where('evenement_id', $evenementId);
        }

        return response()->json([
            'count' => $query->count(),
        ]);
    }

    // ─── Admin methods ───────────────────────────────────────────────────────────

    /**
     * List all scanner accounts.
     */
    public function listScanners()
    {
        $scanners = User::where('role', 'scanner')->orderBy('created_at', 'desc')->get();
        return view('scanner.admin.list', compact('scanners'));
    }

    /**
     * Show create-scanner form.
     */
    public function createScanner()
    {
        return view('scanner.admin.create');
    }

    /**
     * Store a new scanner account.
     */
    public function storeScanner(Request $request)
    {
        $request->validate([
            'nom'                   => 'required|string|max:255',
            'prenom'                => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required|string|unique:users,phone',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'nom'      => $request->nom,
            'prenom'   => $request->prenom,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'scanner',
        ]);

        return redirect()->route('organisateur.scanners')
            ->with('success', 'Compte scanner créé avec succès.');
    }

    /**
     * Revoke scanner role (back to utilisateur).
     */
    public function deleteScanner(User $user)
    {
        $user->update(['role' => 'utilisateur']);
        return redirect()->route('organisateur.scanners')
            ->with('success', 'Accès scanner révoqué pour ' . $user->nom . ' ' . $user->prenom . '.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    private function formatTicket(TicketCode $ticket): array
    {
        return [
            'code'        => $ticket->code,
            'evenement'   => $ticket->evenement?->titre,
            'billet_type' => $ticket->billet?->type,
            'buyer_name'  => $ticket->buyer_name,
            'buyer_email' => $ticket->buyer_email,
            'date'        => $ticket->evenement?->date
                ? \Carbon\Carbon::parse($ticket->evenement->date)->format('d/m/Y')
                : null,
            'lieu'        => $ticket->evenement?->lieu,
        ];
    }
}
