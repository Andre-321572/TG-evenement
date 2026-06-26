<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class OrganisateurController extends Controller
{
    /**
     * Display a listing of the resource with dashboard statistics.
     */
    public function index()
    {
        $dateActuelle = Carbon::now('GMT');
        
        // Statistiques générales
        $statistiques = [
            'total_evenements' => Evenement::count(),
            'evenements_publies' => Evenement::where('statut', 'publier')->count(),
            'evenements_en_organisation' => Evenement::where('statut', 'en organisation')->count(),
            'evenements_passes' => Evenement::where('date', '<', $dateActuelle)->count(),
            'evenements_a_venir' => Evenement::where('date', '>', $dateActuelle)->count()
        ];
        
        // Événements récents (7 derniers publiés)
        $evenementsRecents = Evenement::where('statut', 'publier')
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();
            
        // Événements à venir
        $evenementsAVenir = Evenement::where('date', '>', $dateActuelle)
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get();
            
        // Événements en ligne (publiés)
        $evenementsEnLigne = Evenement::where('statut', 'publier')
            ->orderBy('date', 'desc')
            ->get();

        return view('organisateur.index', compact(
            'statistiques',
            'evenementsRecents', 
            'evenementsAVenir',
            'evenementsEnLigne'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Evenement::getCategories();
        $lieux = Evenement::getLieux();
        
        return view('organisateur.creer_evenement', compact('categories', 'lieux'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'categorie' => 'required|string|max:100',
            'titre' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'start_heure' => 'required|date_format:H:i',
            'end_heure' => 'required|date_format:H:i|after:start_heure',
            'lieu' => 'required|string|max:255',
            'lien_google_map' => 'nullable|url|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt,avi|max:20480',
            'description' => 'nullable|string',
            'nom_proprietaire' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'facebook' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'twitter' => 'nullable|url|max:255',
            'statut' => 'required|in:en organisation,publier,passé',
            'user_id' => 'required|exists:users,id',
        ], [
            'categorie.required' => 'La catégorie est obligatoire.',
            'titre.required' => 'Le titre est obligatoire.',
            'date.required' => 'La date est obligatoire.',
            'date.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
            'start_heure.required' => 'L\'heure de début est obligatoire.',
            'end_heure.required' => 'L\'heure de fin est obligatoire.',
            'end_heure.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'lieu.required' => 'Le lieu est obligatoire.',
            'photo.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'photo.max' => 'L\'image ne doit pas dépasser 2MB.',
            'video.mimes' => 'La vidéo doit être au format mp4, mov, ogg, qt ou avi.',
            'video.max' => 'La vidéo ne doit pas dépasser 20MB.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'user_id.required' => 'L\'organisateur est obligatoire.',
            'user_id.exists' => 'L\'organisateur sélectionné n\'existe pas.',
        ]);

        try {
            // Gestion de l'upload de photo
            if ($request->hasFile('photo')) {
                $photoFile = $request->file('photo');
                $photoName = time() . '_' . uniqid() . '.' . $photoFile->getClientOriginalExtension();
                $photoFile->move(public_path('storage/evenement/photo'), $photoName);
                $validatedData['photo'] = $photoName;
            }

            // Gestion de l'upload de vidéo
            if ($request->hasFile('video')) {
                $videoFile = $request->file('video');
                $videoName = time() . '_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
                $videoFile->move(public_path('storage/evenement/videos'), $videoName);
                $validatedData['video'] = $videoName;
            }

            // Créer l'événement
            $evenement = Evenement::create($validatedData);

            return redirect()
                ->route('organisateur.evenement-en-cours')
                ->with('success', 'Événement créé avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de l\'événement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $evenement = Evenement::with(['user', 'commentaires', 'sponsors'])->findOrFail($id);
        
        return view('organisateur.detail_evenement', compact('evenement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $evenement = Evenement::findOrFail($id);
        $categories = Evenement::getCategories();
        $lieux = Evenement::getLieux();
        
        return view('organisateur.modifier_un_evenement', compact('evenement', 'categories', 'lieux'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'categorie' => 'required|string|max:100',
            'titre' => 'required|string|max:255',
            'date' => 'required|date',
            'start_heure' => 'required|date_format:H:i',
            'end_heure' => 'required|date_format:H:i|after:start_heure',
            'lieu' => 'required|string|max:255',
            'lien_google_map' => 'nullable|url|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt,avi|max:20480',
            'description' => 'nullable|string',
            'nom_proprietaire' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'facebook' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'twitter' => 'nullable|url|max:255',
            'statut' => 'required|in:en organisation,publier,passé',
            'user_id' => 'required|exists:users,id',
        ], [
            'categorie.required' => 'La catégorie est obligatoire.',
            'titre.required' => 'Le titre est obligatoire.',
            'date.required' => 'La date est obligatoire.',
            'start_heure.required' => 'L\'heure de début est obligatoire.',
            'end_heure.required' => 'L\'heure de fin est obligatoire.',
            'end_heure.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'lieu.required' => 'Le lieu est obligatoire.',
            'photo.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'photo.max' => 'L\'image ne doit pas dépasser 2MB.',
            'video.mimes' => 'La vidéo doit être au format mp4, mov, ogg, qt ou avi.',
            'video.max' => 'La vidéo ne doit pas dépasser 20MB.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'user_id.required' => 'L\'organisateur est obligatoire.',
            'user_id.exists' => 'L\'organisateur sélectionné n\'existe pas.',
        ]);

        try {
            $evenement = Evenement::findOrFail($id);

            // Gestion de l'upload de photo
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo
                if ($evenement->photo) {
                    $oldPhotoPath = public_path('storage/evenement/photo/' . $evenement->photo);
                    if (File::exists($oldPhotoPath)) {
                        File::delete($oldPhotoPath);
                    }
                }
                
                $photoFile = $request->file('photo');
                $photoName = time() . '_' . uniqid() . '.' . $photoFile->getClientOriginalExtension();
                $photoFile->move(public_path('storage/evenement/photo'), $photoName);
                $validatedData['photo'] = $photoName;
            }

            // Gestion de l'upload de vidéo
            if ($request->hasFile('video')) {
                // Supprimer l'ancienne vidéo
                if ($evenement->video) {
                    $oldVideoPath = public_path('storage/evenement/videos/' . $evenement->video);
                    if (File::exists($oldVideoPath)) {
                        File::delete($oldVideoPath);
                    }
                }
                
                $videoFile = $request->file('video');
                $videoName = time() . '_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
                $videoFile->move(public_path('storage/evenement/videos'), $videoName);
                $validatedData['video'] = $videoName;
            }

            // Supprimer les champs de fichiers s'ils ne sont pas uploadés
            if (!$request->hasFile('photo')) {
                unset($validatedData['photo']);
            }
            if (!$request->hasFile('video')) {
                unset($validatedData['video']);
            }

            // Mettre à jour l'événement
            $evenement->update($validatedData);

            return redirect()
                ->route('organisateur.evenement-en-cours')
                ->with('success', 'Événement mis à jour avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification de l\'événement: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            
            // Supprimer les fichiers associés
            if ($evenement->photo) {
                $photoPath = public_path('storage/evenement/photo/' . $evenement->photo);
                if (File::exists($photoPath)) {
                    File::delete($photoPath);
                }
            }
            
            if ($evenement->video) {
                $videoPath = public_path('storage/evenement/videos/' . $evenement->video);
                if (File::exists($videoPath)) {
                    File::delete($videoPath);
                }
            }
            
            $evenement->delete();
            
            return redirect()
                ->route('organisateur.evenement-en-cours')
                ->with('success', 'Événement supprimé avec succès !');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les événements en cours (tous les événements)
     */
    public function evenementencours()
    {
        $evenements = Evenement::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('organisateur.event-en-cours', compact('evenements'));
    }

    /**
     * Afficher l'historique de tous les événements
     */
    public function historique()
    {
        $historique = Evenement::with('user')
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('organisateur.historique', compact('historique'));
    }

    /**
     * Afficher les événements à venir
     */
    public function futurevenement()
    {
        $dateActuelle = Carbon::now('GMT');
        $evenementdateavenir = Evenement::where('date', '>', $dateActuelle)
            ->with('user')
            ->orderBy('date', 'asc')
            ->get();
            
        $countfuture = $evenementdateavenir->count();
        
        return view('organisateur.future.evenementenattent', compact('evenementdateavenir', 'countfuture'));
    }

    /**
     * Afficher les événements passés
     */
    public function evenement_passer()
    {
        $dateActuelle = Carbon::now('GMT');
        $evenementdatepasser = Evenement::where('date', '<', $dateActuelle)
            ->with('user')
            ->orderBy('date', 'desc')
            ->get();
            
        $countpasser = $evenementdatepasser->count();
        
        return view('organisateur.evenement-passé', compact('evenementdatepasser', 'countpasser'));
    }

    /**
     * Afficher les événements en attente d'organisation
     */
    public function organiserenattente()
    {
        $evenementsEnAttente = Evenement::where('statut', 'en organisation')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('organisateur.future.organiser-en-attent', compact('evenementsEnAttente'));
    }

    /**
     * Afficher le détail d'un événement
     */
    public function detail_evenement(string $id)
    {
        $evenement = Evenement::with(['user', 'commentaires.participant', 'sponsors'])
            ->findOrFail($id);
            
        return view('organisateur.detail-evenement', compact('evenement'));
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function inscription()
    {
        return view('p.Inscription');
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function connexion()
    {
        return view('p.connexion');
    }

    /**
     * Publier un événement
     */
    public function publier(string $id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            $evenement->update(['statut' => 'publier']);
            
            return redirect()
                ->back()
                ->with('success', 'Événement publié avec succès !');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la publication: ' . $e->getMessage());
        }
    }

    /**
     * Archiver un événement
     */
    public function archiver(string $id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            $evenement->update(['statut' => 'passé']);
            
            return redirect()
                ->back()
                ->with('success', 'Événement archivé avec succès !');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l\'archivage: ' . $e->getMessage());
        }
    }

    /**
     * Rechercher des événements
     */
    public function rechercher(Request $request)
    {
        $query = Evenement::query()->with('user');
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('categorie')) {
            $query->byCategorie($request->categorie);
        }
        
        if ($request->filled('lieu')) {
            $query->byLieu($request->lieu);
        }
        
        if ($request->filled('date_debut') || $request->filled('date_fin')) {
            $query->byDateRange($request->date_debut, $request->date_fin);
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $evenements = $query->orderBy('date', 'desc')->paginate(15);
        $categories = Evenement::getCategories();
        $lieux = Evenement::getLieux();
        
        return view('organisateur.recherche', compact('evenements', 'categories', 'lieux'));
    }
}