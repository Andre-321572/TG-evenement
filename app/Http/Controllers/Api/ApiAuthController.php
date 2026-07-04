<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    /**
     * Inscription via API.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|min:8|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Cet e-mail est déjà utilisé.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $imgProfilPath = null;
        if ($request->hasFile('img_profil')) {
            $imgProfilPath = $request->file('img_profil')->store('user/profile_images', 'public');
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'img_profil' => $imgProfilPath,
            'role' => 'utilisateur', // rôle par défaut
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Inscription réussie.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Connexion via API.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string', // peut être email ou téléphone
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $loginInput)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identifiants incorrects.'
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Connexion réussie.',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Déconnexion via API.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Déconnexion réussie.'
        ], 200);
    }

    /**
     * Profil de l'utilisateur connecté.
     */
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user()
        ], 200);
    }

    /**
     * Mise à jour du profil via API.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|required|string|min:8|max:15|unique:users,phone,' . $user->id,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Cet e-mail est déjà utilisé.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->nom = $request->input('nom', $user->nom);
        $user->prenom = $request->input('prenom', $user->prenom);
        $user->email = $request->input('email', $user->email);
        $user->phone = $request->input('phone', $user->phone);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profil mis à jour avec succès.',
            'user' => $user
        ], 200);
    }

    /**
     * Obtenir les notifications dynamiques du participant.
     */
    public function getNotifications(Request $request)
    {
        $user = $request->user();
        $notifications = [];

        // 1. Charger les billets de l'utilisateur pour générer des notifications d'achat réelles
        $tickets = \App\Models\TicketCode::where('user_id', $user->id)
            ->orWhere('buyer_email', $user->email)
            ->with('evenement')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($tickets as $ticket) {
            if ($ticket->evenement) {
                // Notification de confirmation d'achat
                $notifications[] = [
                    'id' => 'purchase_' . $ticket->id,
                    'category' => 'ACHAT',
                    'title' => 'Confirmation d\'achat : ' . $ticket->evenement->titre,
                    'message' => 'Votre paiement pour l\'événement ' . $ticket->evenement->titre . ' a été validé. Votre billet est disponible dans l\'onglet Billets.',
                    'time' => $ticket->created_at ? $ticket->created_at->diffForHumans() : 'Récemment',
                    'isNew' => !$ticket->is_scanned,
                    'iconName' => 'receipt',
                    'iconColor' => '#10b981',
                    'iconBg' => '#ecfdf5',
                    'filterType' => 'Achats',
                ];

                // Si l'événement n'est pas encore scanné, ajouter un rappel
                if (!$ticket->is_scanned) {
                    $notifications[] = [
                        'id' => 'reminder_' . $ticket->id,
                        'category' => 'RAPPEL',
                        'title' => 'Votre événement ' . $ticket->evenement->titre . ' commence bientôt !',
                        'message' => 'L\'ouverture des portes est proche à ' . $ticket->evenement->lieu . '. Préparez votre code QR pour fluidifier l\'accès.',
                        'time' => 'Prochainement',
                        'isNew' => true,
                        'iconName' => 'time',
                        'iconColor' => '#3b82f6',
                        'iconBg' => '#eff6ff',
                        'filterType' => 'Événements',
                    ];
                }
            }
        }

        // 2. Récupérer les derniers événements publiés sur le serveur pour des exclusivités réelles
        $recentEvents = \App\Models\Evenement::where('statut', 'publier')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        foreach ($recentEvents as $event) {
            $notifications[] = [
                'id' => 'promo_' . $event->id,
                'category' => 'EXCLUSIVITÉ',
                'title' => 'Nouveau sur TGevent : ' . $event->titre,
                'message' => 'Les billets pour ' . $event->titre . ' sont disponibles dès maintenant à partir de ' . ($event->min_price ?: '0') . ' FCFA.',
                'time' => 'Annonce',
                'isNew' => false,
                'hasBanner' => true,
                'bannerUrl' => $event->photo_url,
                'filterType' => 'Annonces',
            ];
        }

        // Compléter avec une notification de bienvenue si la liste est vide
        if (empty($notifications)) {
            $notifications[] = [
                'id' => 'welcome',
                'category' => 'SYSTÈME',
                'title' => 'Bienvenue sur TGevent !',
                'message' => 'Explorez nos événements recommandés, achetez des billets et restez informé des nouveautés.',
                'time' => 'Maintenant',
                'isNew' => true,
                'iconName' => 'sparkles',
                'iconColor' => '#f59e0b',
                'iconBg' => '#fef3c7',
                'filterType' => 'Annonces',
            ];
        }

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications
        ], 200);
    }
}
