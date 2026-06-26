<?php

namespace App\Http\Controllers;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Faq;
use Carbon\Carbon;

class PublicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer d'abord les événements futurs
        $upcomingEvents = Evenement::with(['user', 'billets', 'sponsors'])
                                  ->where('statut', 'publier')
                                  ->where('date', '>=', now()->toDateString())
                                  ->orderBy('date', 'asc')
                                  ->limit(6)
                                  ->get();

        // Si pas assez d'événements futurs, compléter avec les événements passés récents
        $eventsToShow = $upcomingEvents;
        if ($upcomingEvents->count() < 6) {
            $remainingSlots = 6 - $upcomingEvents->count();
            
            $pastEvents = Evenement::with(['user', 'billets', 'sponsors'])
                                  ->where('statut', 'publier')
                                  ->where('date', '<', now()->toDateString())
                                  ->orderBy('date', 'desc')
                                  ->limit($remainingSlots)
                                  ->get();
            
            $eventsToShow = $upcomingEvents->merge($pastEvents);
        }

        // Transformation des données pour l'affichage
        $events = $eventsToShow->transform(function ($event) {
            $event->truncated_description = Str::limit($event->description, 150);
            $event->formatted_date = Carbon::parse($event->date)->format('d M Y');
            $event->formatted_start_time = Carbon::parse($event->start_heure)->format('H:i');
            $event->formatted_end_time = Carbon::parse($event->end_heure)->format('H:i');
            $event->photo_url = $event->photo ? asset('storage/evenement/photo/' . $event->photo) : asset('images/default-event.jpg');
            $event->is_upcoming = Carbon::parse($event->date)->isFuture();
            
            // Prix minimum des billets pour cet événement
            $event->min_price = $event->billets->min('prix') ?? 0;
            
            return $event;
        });

        // Récupérer les catégories pour la section catégories
        $categories = [
            [
                'name' => 'Concerts & Musique',
                'description' => 'Découvrez les meilleurs concerts et festivals',
                'image' => 'https://th.bing.com/th/id/OIP.UqehV_VtqVVYuN8GJvYaqQHaEK?w=295&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7',
                'slug' => 'concert'
            ],
            [
                'name' => 'Sports & Fitness',
                'description' => 'Événements sportifs et activités fitness',
                'image' => 'https://th.bing.com/th/id/OIP.g3L8Cs_9cEGghmtmZqAA7gHaE8?w=277&h=184&c=7&r=0&o=5&dpr=1.3&pid=1.7',
                'slug' => 'sport'
            ],
            [
                'name' => 'Ateliers & Formations',
                'description' => 'Développez vos compétences et passions',
                'image' => 'https://via.placeholder.com/300x200/6366f1/ffffff?text=Formation',
                'slug' => 'formation'
            ],
            [
                'name' => 'Arts & Culture',
                'description' => 'Expositions, théâtre et performances',
                'image' => 'https://th.bing.com/th/id/OIP.Th_AX048TT1Qnf8gvOJPDgHaE8?w=248&h=181&c=7&r=0&o=5&dpr=1.3&pid=1.7',
                'slug' => 'art'
            ]
        ];

        // Statistiques pour la page d'accueil
        $stats = [
            'total_events' => Evenement::where('statut', 'publier')->count(),
            'upcoming_events' => Evenement::where('statut', 'publier')
                                        ->where('date', '>=', now())
                                        ->count(),
            'total_categories' => Evenement::where('statut', 'publier')
                                         ->distinct('categorie')
                                         ->count('categorie')
        ];

        // Événement en vedette (priorité aux événements futurs, sinon le plus récent)
        $featuredEvent = Evenement::where('statut', 'publier')
                                 ->where('date', '>=', now()->toDateString())
                                 ->orderBy('date', 'asc')
                                 ->first();

        if (!$featuredEvent) {
            $featuredEvent = Evenement::where('statut', 'publier')
                                    ->orderBy('date', 'desc')
                                    ->first();
        }

        if ($featuredEvent) {
            $featuredEvent->truncated_description = Str::limit($featuredEvent->description, 300);
            $featuredEvent->photo_url = $featuredEvent->photo ? asset('storage/evenement/photo/' . $featuredEvent->photo) : asset('images/default-event.jpg');
            $featuredEvent->is_upcoming = Carbon::parse($featuredEvent->date)->isFuture();
        }

        return view('index', compact('events', 'categories', 'stats', 'featuredEvent'));
    }

    // ... autres méthodes restent inchangées
    
    public function show(string $id)
    {
        $detail_evenement = Evenement::FindOrFail($id);
        return view('p.detail', compact('detail_evenement'));
    }

    public function a_propos()
    {
        return view('p.a-propos');
    }
    
    public function contact()
    {
        return view('p.contact');
    }

    public function concert_et_festival_de_musique()
    {
        $events = Evenement::with(['user', 'billets', 'sponsors'])
                           ->where('statut', 'publier')
                           ->where(function($q) {
                               $q->where('categorie', 'Concert')
                                 ->orWhere('categorie', 'Festival')
                                 ->orWhere('categorie', 'like', '%concert%')
                                 ->orWhere('categorie', 'like', '%musique%')
                                 ->orWhere('categorie', 'like', '%festival%');
                           })
                           ->latest()
                           ->paginate(12);

        return view('p.concerts-et-festival-de-musique', compact('events'));
    }

    public function conference_et_congres()
    {
        $events = Evenement::with(['user', 'billets', 'sponsors'])
                           ->where('statut', 'publier')
                           ->where(function($q) {
                               $q->where('categorie', 'Conférence')
                                 ->orWhere('categorie', 'Formation')
                                 ->orWhere('categorie', 'Networking')
                                 ->orWhere('categorie', 'like', '%conf%')
                                 ->orWhere('categorie', 'like', '%congres%')
                                 ->orWhere('categorie', 'like', '%atelier%');
                           })
                           ->latest()
                           ->paginate(12);

        return view('p.conferences-et-congres', compact('events'));
    }

    public function evenement_sportif()
    {
        $events = Evenement::with(['user', 'billets', 'sponsors'])
                           ->where('statut', 'publier')
                           ->where(function($q) {
                               $q->where('categorie', 'Sport')
                                 ->orWhere('categorie', 'like', '%sport%')
                                 ->orWhere('categorie', 'like', '%fitness%')
                                 ->orWhere('categorie', 'like', '%course%');
                           })
                           ->latest()
                           ->paginate(12);

        return view('p.evenement-sportif', compact('events'));
    }

    /**
     * Afficher tous les événements pour le public
     */
    public function evenement(Request $request)
    {
        $query = Evenement::with(['user', 'billets', 'sponsors'])
                          ->where('statut', 'publier'); // Seulement les événements publiés

        // Recherche par titre ou description
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('titre', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('lieu', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Filtre par lieu
        if ($request->filled('lieu')) {
            $query->where('lieu', 'LIKE', "%{$request->lieu}%");
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->where('date', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->where('date', '<=', $request->date_fin);
        }

        // Tri - priorité aux événements futurs
        $sortBy = $request->get('sort', 'priority');
        switch ($sortBy) {
            case 'date_desc':
                $query->orderBy('date', 'desc');
                break;
            case 'titre_asc':
                $query->orderBy('titre', 'asc');
                break;
            case 'titre_desc':
                $query->orderBy('titre', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'priority':
            default:
                // Événements futurs en premier, puis les passés
                $query->orderByRaw('CASE WHEN date >= CURDATE() THEN 0 ELSE 1 END')
                      ->orderBy('date', 'asc');
        }

        // Pagination avec conservation des paramètres de recherche
        $events = $query->paginate(12)->appends($request->query());

        // Transformation des données pour l'affichage
        $events->getCollection()->transform(function ($event) {
            $event->truncated_description = Str::limit($event->description, 200);
            $event->formatted_date = Carbon::parse($event->date)->format('d/m/Y');
            $event->formatted_start_time = Carbon::parse($event->start_heure)->format('H:i');
            $event->formatted_end_time = Carbon::parse($event->end_heure)->format('H:i');
            $event->photo_url = $event->photo ? asset('storage/evenement/photo/' . $event->photo) : asset('images/default-event.jpg');
            $event->is_upcoming = Carbon::parse($event->date)->isFuture();
            $event->min_price = $event->billets->min('prix') ?? 0;
            return $event;
        });

        // Récupérer les catégories et lieux pour les filtres
        $categories = Evenement::select('categorie')
                               ->distinct()
                               ->whereNotNull('categorie')
                               ->where('statut', 'publier')
                               ->pluck('categorie');

        $lieux = Evenement::select('lieu')
                          ->distinct()
                          ->whereNotNull('lieu')
                          ->where('statut', 'publier')
                          ->pluck('lieu');

        // Événement en vedette (le plus récent publié)
        $featuredEvent = Evenement::where('statut', 'publier')
                                  ->where('date', '>=', now()->toDateString())
                                  ->orderBy('date', 'asc')
                                  ->first();

        if (!$featuredEvent) {
            $featuredEvent = Evenement::where('statut', 'publier')
                                    ->orderBy('date', 'desc')
                                    ->first();
        }

        if ($featuredEvent) {
            $featuredEvent->truncated_description = Str::limit($featuredEvent->description, 300);
            $featuredEvent->photo_url = $featuredEvent->photo ? asset('storage/evenement/photo/' . $featuredEvent->photo) : asset('images/default-event.jpg');
            $featuredEvent->is_upcoming = Carbon::parse($featuredEvent->date)->isFuture();
        }

        return view('p.evenement', compact('events', 'categories', 'lieux', 'featuredEvent'));
    }

    public function faq($parameter = null)
    {
        $faqs = Faq::all();
        return view('P.faq', compact('faqs'));
    }

    public function fete()
    {
        $events = Evenement::with(['user', 'billets', 'sponsors'])
                           ->where('statut', 'publier')
                           ->where(function($q) {
                               $q->where('categorie', 'Fête')
                                 ->orWhere('categorie', 'Fete')
                                 ->orWhere('categorie', 'Théâtre')
                                 ->orWhere('categorie', 'Exposition')
                                 ->orWhere('categorie', 'like', '%fete%')
                                 ->orWhere('categorie', 'like', '%fête%')
                                 ->orWhere('categorie', 'like', '%gastronomie%');
                           })
                           ->latest()
                           ->paginate(12);

        return view('p.fete', compact('events'));
    }

    public function sante()
    {
        $events = Evenement::with(['user', 'billets', 'sponsors'])
                           ->where('statut', 'publier')
                           ->where(function($q) {
                               $q->where('categorie', 'Santé')
                                 ->orWhere('categorie', 'like', '%sante%')
                                 ->orWhere('categorie', 'like', '%santé%')
                                 ->orWhere('categorie', 'like', '%medical%');
                           })
                           ->latest()
                           ->paginate(12);

        return view('p.santé', compact('events'));
    }

    public function vie_nocturne()
    {
        $events = Evenement::with(['user', 'billets', 'sponsors'])
                           ->where('statut', 'publier')
                           ->where(function($q) {
                               $q->where('categorie', 'Vie nocturne')
                                 ->orWhere('categorie', 'like', '%nocturne%')
                                 ->orWhere('categorie', 'like', '%soiree%')
                                 ->orWhere('categorie', 'like', '%club%');
                           })
                           ->latest()
                           ->paginate(12);

        return view('p.vie-nocturne', compact('events'));
    }

    public function voyage()
    {
        $events = Evenement::with(['user', 'billets', 'sponsors'])
                           ->where('statut', 'publier')
                           ->where(function($q) {
                               $q->where('categorie', 'Voyage')
                                 ->orWhere('categorie', 'like', '%voyage%')
                                 ->orWhere('categorie', 'like', '%tourisme%')
                                 ->orWhere('categorie', 'like', '%excursion%');
                           })
                           ->latest()
                           ->paginate(12);

        return view('p.voyage', compact('events'));
    }
}