<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Sponsor;
use App\Models\Billet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource with filters and search.
     */
    public function index(Request $request)
    {
        $query = Evenement::with(['user']);

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtres par catégories
        if ($request->filled('categories')) {
            $query->whereIn('categorie', $request->categories);
        }

        // Filtre par lieu
        if ($request->filled('lieu')) {
            $query->byLieu($request->lieu);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par date
        if ($request->filled('date_start') || $request->filled('date_end')) {
            $query->byDateRange($request->date_start, $request->date_end);
        }

        // Tri
        switch ($request->sort) {
            case 'date-asc':
                $query->orderBy('date', 'asc');
                break;
            case 'date-desc':
                $query->orderBy('date', 'desc');
                break;
            case 'titre-asc':
                $query->orderBy('titre', 'asc');
                break;
            case 'titre-desc':
                $query->orderBy('titre', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('date', 'asc');
        }

        // Pagination
        $events = $query->paginate(12);

        // Événement en vedette (le plus récent publié)
        $featuredEvent = Evenement::publie()
                                 ->orderBy('created_at', 'desc')
                                 ->first();

        return view('events.index', compact('events', 'featuredEvent'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Récupérer les données dynamiques pour les formulaires
        $categories = Evenement::getCategories();
        $lieux = Evenement::getLieux();
        
        return view('organisateur.creer-un-evenement', compact('categories', 'lieux'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log pour débugger
        Log::info('Début de la création d\'événement', ['request' => $request->all()]);

        // Validation des données de l'événement
        $validatedData = $request->validate([
            'categorie' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'date' => 'required|date|after:today',
            'start_heure' => 'required',
            'end_heure' => 'required|after:start_heure',
            'lieu' => 'required|string|max:255',
            'lien_google_map' => 'nullable|url',
            'photo' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:10240', // 10MB max
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:102400', // 100MB max
            'description' => 'nullable|string',
            'nom_proprietaire' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'facebook' => 'nullable|url',
            'whatsapp' => 'nullable|string', 
            'twitter' => 'nullable|url',
            'statut' => 'required|in:en organisation,publier',
        ], [
            'categorie.required' => 'La catégorie est obligatoire.',
            'titre.required' => 'Le titre est obligatoire.',
            'date.required' => 'La date est obligatoire.',
            'date.date' => 'La date doit être une date valide.',
            'date.after' => 'La date doit être ultérieure à aujourd\'hui.',
            'start_heure.required' => 'L\'heure de début est obligatoire.',
            'end_heure.required' => 'L\'heure de fin est obligatoire.',
            'end_heure.after' => 'L\'heure de fin doit être postérieure à l\'heure de début.',
            'lieu.required' => 'Le lieu est obligatoire.',
            'photo.required' => 'L\'image est obligatoire.',
            'photo.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'photo.max' => 'L\'image ne doit pas dépasser 10 Mo.',
            'video.mimes' => 'La vidéo doit être au format mp4, mov, ogg ou qt.',
            'video.max' => 'La vidéo ne doit pas dépasser 100 Mo.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'nom_proprietaire.required' => 'Le nom du propriétaire est obligatoire.',
        ]);

        try {
            // Création de l'événement
            $evenement = new Evenement();
            
            // Assignation des données validées (SANS les champs fichiers)
            $evenement->categorie = $validatedData['categorie'];
            $evenement->titre = $validatedData['titre'];
            $evenement->date = $validatedData['date'];
            $evenement->start_heure = $validatedData['start_heure'];
            $evenement->end_heure = $validatedData['end_heure'];
            $evenement->lieu = $validatedData['lieu'];
            $evenement->lien_google_map = $validatedData['lien_google_map'] ?? null;
            $evenement->description = $validatedData['description'] ?? null;
            $evenement->nom_proprietaire = $validatedData['nom_proprietaire'];
            $evenement->telephone = $validatedData['telephone'] ?? null;
            $evenement->email = $validatedData['email'] ?? null;
            $evenement->facebook = $validatedData['facebook'] ?? null;
            $evenement->whatsapp = $validatedData['whatsapp'] ?? null;
            $evenement->statut = $validatedData['statut'];

            // Enregistrement de la photo
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('evenement/photo', 'public');
                $evenement->photo = basename($photoPath);
                Log::info('Photo uploadée', ['path' => $photoPath]);
            }

            // Enregistrement de la vidéo si elle existe
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('evenement/videos', 'public');
                $evenement->video = basename($videoPath);
                Log::info('Vidéo uploadée', ['path' => $videoPath]);
            }

            // Assigner l'utilisateur connecté
            $evenement->user_id = Auth::id();
            
            // CORRECTION: Utiliser le bon nom de champ pour Twitter
            $evenement->twiter = $validatedData['twitter'] ?? null; // Note: le champ semble s'appeler "twiter" dans la DB

            Log::info('Avant sauvegarde', ['evenement' => $evenement->toArray()]);
            
            // Sauvegarder l'événement
            $saved = $evenement->save();
            
            if ($saved) {
                Log::info('Événement sauvegardé avec succès', ['id' => $evenement->id]);
                
                return redirect()
                    ->route('organisateur.evenement-en-cours')
                    ->with('success', 'Événement créé avec succès !');
            } else {
                Log::error('Échec de la sauvegarde');
                throw new \Exception('Échec de la sauvegarde en base de données');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation', ['errors' => $e->errors()]);
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'événement : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evenement = Evenement::with(['user', 'sponsors', 'billets'])
                              ->findOrFail($id);
        
        return view('organisateur.show-evenement', compact('evenement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evenement = Evenement::findOrFail($id);
        
        // Vérifier que l'utilisateur peut modifier cet événement
        if (Auth::id() !== $evenement->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $categories = Evenement::getCategories();
        $lieux = Evenement::getLieux();
        
        return view('organisateur.modifier_un_evenement', compact('evenement', 'categories', 'lieux'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $evenement = Evenement::findOrFail($id);
        
        // Vérifier que l'utilisateur peut modifier cet événement
        if (Auth::id() !== $evenement->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $validatedData = $request->validate([
            'categorie' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'date' => 'required|date',
            'start_heure' => 'required',
            'end_heure' => 'required|after:start_heure',
            'lieu' => 'required|string|max:255',
            'lien_google_map' => 'nullable|url',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:102400',
            'description' => 'nullable|string',
            'nom_proprietaire' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'facebook' => 'nullable|url',
            'whatsapp' => 'nullable|string',
            'twitter' => 'nullable|url',
            'statut' => 'required|in:en organisation,publier',
        ]);

        try {
            // Mise à jour des champs
            $evenement->fill($validatedData);

            // Gestion de la photo
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo
                if ($evenement->photo && $evenement->photo !== 'null') {
                    Storage::disk('public')->delete('evenement/photo/' . $evenement->photo);
                }
                
                $photoPath = $request->file('photo')->store('evenement/photo', 'public');
                $evenement->photo = basename($photoPath);
            }

            // Gestion de la vidéo
            if ($request->hasFile('video')) {
                // Supprimer l'ancienne vidéo
                if ($evenement->video && $evenement->video !== 'null') {
                    Storage::disk('public')->delete('evenement/videos/' . $evenement->video);
                }
                
                $videoPath = $request->file('video')->store('evenement/videos', 'public');
                $evenement->video = basename($videoPath);
            }

            $evenement->twiter = $validatedData['twitter'] ?? null;
            $evenement->save();

            return redirect()
                ->route('organisateur.evenement-en-cours')
                ->with('success', 'Événement modifié avec succès !');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            
            // Vérifier les permissions
            if (Auth::id() !== $evenement->user_id && !Auth::user()->isAdmin()) {
                abort(403, 'Accès non autorisé');
            }

            // Supprimer les fichiers associés
            if ($evenement->photo && $evenement->photo !== 'null') {
                Storage::disk('public')->delete('evenement/photo/' . $evenement->photo);
            }
            
            if ($evenement->video && $evenement->video !== 'null') {
                Storage::disk('public')->delete('evenement/videos/' . $evenement->video);
            }

            $evenement->delete();

            return redirect()
                ->back()
                ->with('success', 'Événement supprimé avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Méthodes supplémentaires pour l'API ou les vues
     */
    
    public function getEvenementsByLieu($lieu)
    {
        $evenements = Evenement::byLieu($lieu)
                               ->publie()
                               ->orderBy('date', 'asc')
                               ->get();
        
        return response()->json($evenements);
    }

    public function getEvenementsByCategorie($categorie)
    {
        $evenements = Evenement::byCategorie($categorie)
                               ->publie()
                               ->orderBy('date', 'asc')
                               ->get();
        
        return response()->json($evenements);
    }

    /**
     * Récupérer les lieux dynamiquement pour AJAX
     */
    public function getLieux()
    {
        $lieux = Evenement::getLieux();
        return response()->json($lieux);
    }

    /**
     * Récupérer les catégories dynamiquement pour AJAX
     */
    public function getCategories()
    {
        $categories = Evenement::getCategories();
        return response()->json($categories);
    }
}