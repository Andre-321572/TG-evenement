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
        
        foreach ($evenements as $ev) {
            $total = \App\Models\TicketCode::where('evenement_id', $ev->id)->count();
            $scanned = \App\Models\TicketCode::where('evenement_id', $ev->id)->where('is_scanned', true)->count();
            $ev->total_tickets = $total;
            $ev->scanned_tickets = $scanned;
            $ev->percentage = $total > 0 ? round(($scanned / $total) * 100, 1) : 0;
        }

        // Fetch the 5 most recent scans of this scanner
        $recentScans = \App\Models\TicketCode::with(['evenement', 'billet'])
            ->where('is_scanned', true)
            ->where('scanned_by', auth()->id())
            ->orderBy('scanned_at', 'desc')
            ->take(5)
            ->get();

        return view('scanner.dashboard', compact('evenements', 'recentScans'));
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
     * Stats: complete statistics for an event (attendance, ticket breakdown, hourly check-ins).
     */
    public function stats(Request $request)
    {
        $evenementId = $request->evenement_id;

        if (!$evenementId) {
            $firstEvent = Evenement::publie()->first();
            $evenementId = $firstEvent ? $firstEvent->id : null;
        }

        if (!$evenementId) {
            return response()->json(['error' => 'Aucun événement disponible.'], 404);
        }

        $evenement = Evenement::findOrFail($evenementId);

        $total = \App\Models\TicketCode::where('evenement_id', $evenementId)->count();
        $scanned = \App\Models\TicketCode::where('evenement_id', $evenementId)->where('is_scanned', true)->count();
        $percentage = $total > 0 ? round(($scanned / $total) * 100, 1) : 0;

        // Breakdown by billet types
        $billets = \App\Models\Billet::where('evenement_id', $evenementId)->get();
        $breakdown = [];
        foreach ($billets as $b) {
            $bTotal = \App\Models\TicketCode::where('billet_id', $b->id)->count();
            $bScanned = \App\Models\TicketCode::where('billet_id', $b->id)->where('is_scanned', true)->count();
            $bPercentage = $bTotal > 0 ? round(($bScanned / $bTotal) * 100) : 0;

            // Flow label algorithm
            $label = 'Steady';
            if ($bPercentage >= 85) {
                $label = 'High Flow';
            } elseif ($bPercentage > 0 && $bPercentage < 30) {
                $label = 'Queue Peak';
            }

            $breakdown[] = [
                'type' => $b->type,
                'total' => $bTotal,
                'scanned' => $bScanned,
                'percentage' => $bPercentage,
                'label' => $label
            ];
        }

        // Peak hour check-ins (16:00 to 20:00)
        $hours = ['16:00', '17:00', '18:00', '19:00', '20:00'];
        $hourlyData = [];
        foreach ($hours as $h) {
            $hourInt = (int) substr($h, 0, 2);
            $count = \App\Models\TicketCode::where('evenement_id', $evenementId)
                ->where('is_scanned', true)
                ->whereHour('scanned_at', $hourInt)
                ->count();
            $hourlyData[] = [
                'hour' => $h,
                'count' => $count
            ];
        }

        return response()->json([
            'evenement' => [
                'id' => $evenement->id,
                'titre' => $evenement->titre,
                'lieu' => $evenement->lieu,
            ],
            'total' => $total,
            'scanned' => $scanned,
            'percentage' => $percentage,
            'breakdown' => $breakdown,
            'hourly' => $hourlyData
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
