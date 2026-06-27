<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApiEventController extends Controller
{
    /**
     * Liste publique des événements avec recherche et filtrage.
     */
    public function index(Request $request)
    {
        $query = Evenement::with(['user', 'billets', 'sponsors'])
            ->where('statut', 'publier');

        // Filtre de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('lieu', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Filtre par date
        if ($request->filled('date_start')) {
            $query->where('date', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->where('date', '<=', $request->date_end);
        }

        // Tri - les événements futurs en premier
        $today = Carbon::today()->toDateString();
        $query->orderByRaw("CASE WHEN date >= '{$today}' THEN 0 ELSE 1 END")
              ->orderBy('date', 'asc');

        $events = $query->paginate(10);

        // Transformation pour ajouter les URL absolues des médias
        $events->getCollection()->transform(function ($event) {
            $event->photo_url = $event->photo ? asset('storage/evenement/photo/' . $event->photo) : asset('images/default-event.jpg');
            $event->video_url = $event->video ? asset('storage/evenement/videos/' . $event->video) : null;
            $event->min_price = $event->billets->min('prix') ?? 0;
            return $event;
        });

        return response()->json([
            'status' => 'success',
            'data' => $events
        ], 200);
    }

    /**
     * Détails d'un événement.
     */
    public function show($id)
    {
        $event = Evenement::with(['user', 'billets', 'sponsors'])->find($id);

        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Événement introuvable.'
            ], 404);
        }

        $event->photo_url = $event->photo ? asset('storage/evenement/photo/' . $event->photo) : asset('images/default-event.jpg');
        $event->video_url = $event->video ? asset('storage/evenement/videos/' . $event->video) : null;

        // Ajouter l'URL absolue des logos des sponsors
        foreach ($event->sponsors as $sponsor) {
            $sponsor->logo_url = $sponsor->logo ? asset('storage/evenement/sponsors/' . $sponsor->logo) : null;
        }

        return response()->json([
            'status' => 'success',
            'event' => $event
        ], 200);
    }

    /**
     * Liste des catégories d'événements.
     */
    public function categories()
    {
        $categories = Evenement::select('categorie')
            ->distinct()
            ->whereNotNull('categorie')
            ->where('statut', 'publier')
            ->pluck('categorie');

        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ], 200);
    }

    /**
     * Tableau de bord pour l'organisateur connecté.
     */
    public function organizerDashboard(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $user->role !== 'utilisateur') { // organisateurs
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $now = Carbon::now();

        // Récupérer les statistiques des événements créés par l'organisateur
        $eventsQuery = Evenement::where('user_id', $user->id);

        $totalEvents = (clone $eventsQuery)->count();
        $publishedCount = (clone $eventsQuery)->where('statut', 'publier')->count();
        $draftCount = (clone $eventsQuery)->where('statut', 'en organisation')->count();
        $pastCount = (clone $eventsQuery)->where('date', '<', $now->toDateString())->count();

        // Liste complète des événements de l'organisateur
        $myEvents = (clone $eventsQuery)->orderBy('date', 'desc')->get();

        foreach ($myEvents as $event) {
            $event->photo_url = $event->photo ? asset('storage/evenement/photo/' . $event->photo) : asset('images/default-event.jpg');
        }

        return response()->json([
            'status' => 'success',
            'statistics' => [
                'total_events' => $totalEvents,
                'published_events' => $publishedCount,
                'draft_events' => $draftCount,
                'past_events' => $pastCount,
            ],
            'events' => $myEvents
        ], 200);
    }

    /**
     * Création d'un événement par un organisateur.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categorie' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'start_heure' => 'required',
            'end_heure' => 'required',
            'lieu' => 'required|string|max:255',
            'description' => 'nullable|string',
            'nom_proprietaire' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'facebook' => 'nullable|url',
            'whatsapp' => 'nullable|string',
            'twitter' => 'nullable|url',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $evenement = new Evenement();
            $evenement->categorie = $request->categorie;
            $evenement->titre = $request->titre;
            $evenement->date = $request->date;
            $evenement->start_heure = $request->start_heure;
            $evenement->end_heure = $request->end_heure;
            $evenement->lieu = $request->lieu;
            $evenement->description = $request->description;
            $evenement->nom_proprietaire = $request->nom_proprietaire;
            $evenement->telephone = $request->telephone;
            $evenement->email = $request->email;
            $evenement->facebook = $request->facebook;
            $evenement->whatsapp = $request->whatsapp;
            $evenement->twiter = $request->twitter; // Nom du champ dans la base de données
            $evenement->statut = 'en organisation'; // Par défaut en brouillon
            $evenement->user_id = $request->user()->id;

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('evenement/photo', 'public');
                $evenement->photo = basename($photoPath);
            }

            $evenement->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Événement créé avec succès.',
                'event' => $evenement
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Event Store Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de l\'événement : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mise à jour d'un événement par son organisateur.
     */
    public function update(Request $request, $id)
    {
        $evenement = Evenement::findOrFail($id);

        // Vérification de propriété
        if ($evenement->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'categorie' => 'sometimes|required|string|max:255',
            'titre' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'start_heure' => 'sometimes|required',
            'end_heure' => 'sometimes|required',
            'lieu' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'nom_proprietaire' => 'sometimes|required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'facebook' => 'nullable|url',
            'whatsapp' => 'nullable|string',
            'twitter' => 'nullable|url',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $evenement->fill($request->except(['photo', 'video']));

            if ($request->has('twitter')) {
                $evenement->twiter = $request->twitter;
            }

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('evenement/photo', 'public');
                $evenement->photo = basename($photoPath);
            }

            $evenement->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Événement mis à jour avec succès.',
                'event' => $evenement
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Event Update Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la modification de l\'événement : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un événement.
     */
    public function destroy(Request $request, $id)
    {
        $evenement = Evenement::findOrFail($id);

        if ($evenement->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $evenement->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Événement supprimé avec succès.'
        ], 200);
    }

    /**
     * Publier un événement.
     */
    public function publish(Request $request, $id)
    {
        $evenement = Evenement::findOrFail($id);

        if ($evenement->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $evenement->statut = 'publier';
        $evenement->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Événement publié avec succès.',
            'event' => $evenement
        ], 200);
    }

    /**
     * Archiver / remettre en brouillon un événement.
     */
    public function archive(Request $request, $id)
    {
        $evenement = Evenement::findOrFail($id);

        if ($evenement->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $evenement->statut = 'en organisation';
        $evenement->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Événement remis en brouillon avec succès.',
            'event' => $evenement
        ], 200);
    }
}
