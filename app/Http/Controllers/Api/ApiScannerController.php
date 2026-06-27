<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\TicketCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiScannerController extends Controller
{
    /**
     * Valider et marquer un ticket scanné comme utilisé.
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'evenement_id' => 'nullable|exists:evenements,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $code = strtoupper(trim($request->code));
        $evenementId = $request->evenement_id;

        $ticket = TicketCode::with(['evenement', 'billet'])
            ->where('code', $code)
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Code de billet invalide.'
            ], 404);
        }

        // Si un ID d'événement est fourni, s'assurer que le billet appartient à cet événement
        if ($evenementId && $ticket->evenement_id != $evenementId) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Ce billet ne correspond pas à l\'événement sélectionné.'
            ], 400);
        }

        if ($ticket->is_scanned) {
            return response()->json([
                'status' => 'already_scanned',
                'message' => 'Billet déjà scanné.',
                'scanned_at' => $ticket->scanned_at ? $ticket->scanned_at->format('d/m/Y à H:i') : null,
                'ticket' => $this->formatTicket($ticket)
            ], 200);
        }

        // Marquer comme scanné
        $ticket->update([
            'is_scanned' => true,
            'scanned_at' => now(),
            'scanned_by' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'valid',
            'message' => 'Accès autorisé !',
            'ticket' => $this->formatTicket($ticket)
        ], 200);
    }

    /**
     * Statistiques des billets scannés pour la journée.
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
            'status' => 'success',
            'count' => $query->count()
        ], 200);
    }

    /**
     * Obtenir la liste de tous les comptes scanners (rôle 'scanner').
     */
    public function listScanners(Request $request)
    {
        // Seuls les organisateurs/admins peuvent voir la liste
        if ($request->user()->role !== 'admin' && $request->user()->role !== 'utilisateur') {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $scanners = User::where('role', 'scanner')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'scanners' => $scanners
        ], 200);
    }

    /**
     * Créer un nouveau compte scanner.
     */
    public function storeScanner(Request $request)
    {
        if ($request->user()->role !== 'admin' && $request->user()->role !== 'utilisateur') {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'scanner',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Compte scanner créé avec succès.',
            'scanner' => $user
        ], 201);
    }

    /**
     * Révoquer le rôle scanner d'un utilisateur.
     */
    public function deleteScanner(Request $request, User $user)
    {
        if ($request->user()->role !== 'admin' && $request->user()->role !== 'utilisateur') {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $user->update(['role' => 'utilisateur']);

        return response()->json([
            'status' => 'success',
            'message' => 'Accès scanner révoqué avec succès.'
        ], 200);
    }

    /**
     * Formater le ticket pour la réponse JSON.
     */
    private function formatTicket(TicketCode $ticket): array
    {
        return [
            'code' => $ticket->code,
            'evenement' => $ticket->evenement?->titre,
            'billet_type' => $ticket->billet?->type,
            'buyer_name' => $ticket->buyer_name,
            'buyer_email' => $ticket->buyer_email,
            'date' => $ticket->evenement?->date
                ? \Carbon\Carbon::parse($ticket->evenement->date)->format('d/m/Y')
                : null,
            'lieu' => $ticket->evenement?->lieu,
        ];
    }
}
