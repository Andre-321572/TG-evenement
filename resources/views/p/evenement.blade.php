@extends('layouts.base')

@section('title', '| Rechercher des Événements')

@section('content')
<div class="container py-5 text-white">
    <div class="row g-4 mt-2">
        <!-- Sidebar - Formulaire de recherche et filtres -->
        <div class="col-lg-4 col-xl-3">
            <div class="sticky-top" style="top: 96px; z-index: 10;">
                <div class="glass-card rounded-3xl overflow-hidden p-4">
                    <div class="border-b border-white/10 pb-3 mb-4">
                        <h5 class="mb-0 fw-bold text-gradient-primary">Filtrer les événements</h5>
                    </div>
                    
                    <form method="GET" action="{{ route('p.evenement') }}" id="filter-form">
                        <!-- Recherche -->
                        <div class="mb-4">
                            <label for="search" class="form-label text-gray-400 small fw-semibold">Rechercher</label>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Artistes, lieu, mot-clé..."
                                   class="form-control glass-input rounded-xl py-2 px-3 text-sm">
                        </div>

                        <!-- Filtre par catégorie -->
                        <div class="mb-4">
                            <label for="categorie" class="form-label text-gray-400 small fw-semibold">Catégorie</label>
                            <select name="categorie" id="categorie" class="form-select glass-input rounded-xl py-2 px-3 text-sm">
                                <option value="" class="bg-[#0f111a] text-white">Toutes les catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('categorie') == $cat ? 'selected' : '' }} class="bg-[#0f111a] text-white">
                                        {{ ucfirst($cat) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtre par lieu -->
                        <div class="mb-4">
                            <label for="lieu" class="form-label text-gray-400 small fw-semibold">Lieu</label>
                            <select name="lieu" id="lieu" class="form-select glass-input rounded-xl py-2 px-3 text-sm">
                                <option value="" class="bg-[#0f111a] text-white">Tous les lieux</option>
                                @foreach($lieux as $l)
                                    <option value="{{ $l }}" {{ request('lieu') == $l ? 'selected' : '' }} class="bg-[#0f111a] text-white">
                                        {{ ucfirst($l) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tri -->
                        <div class="mb-4">
                            <label for="sort" class="form-label text-gray-400 small fw-semibold">Trier par</label>
                            <select name="sort" id="sort" class="form-select glass-input rounded-xl py-2 px-3 text-sm">
                                <option value="priority" {{ request('sort') == 'priority' || !request('sort') ? 'selected' : '' }} class="bg-[#0f111a] text-white">Date (Pertinence)</option>
                                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }} class="bg-[#0f111a] text-white">Date (Le plus proche)</option>
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }} class="bg-[#0f111a] text-white">Date (Le plus lointain)</option>
                                <option value="titre_asc" {{ request('sort') == 'titre_asc' ? 'selected' : '' }} class="bg-[#0f111a] text-white">Titre (A-Z)</option>
                                <option value="titre_desc" {{ request('sort') == 'titre_desc' ? 'selected' : '' }} class="bg-[#0f111a] text-white">Titre (Z-A)</option>
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }} class="bg-[#0f111a] text-white">Création (Plus récent)</option>
                            </select>
                        </div>

                        <!-- Filtres par date -->
                        <div class="mb-4">
                            <label class="form-label text-gray-400 small fw-semibold">Période</label>
                            <div class="row g-2">
                                <div class="col-12">
                                    <input type="date" 
                                           name="date_debut" 
                                           id="date_debut" 
                                           value="{{ request('date_debut') }}"
                                           class="form-control glass-input rounded-xl py-2 px-3 text-sm">
                                </div>
                                <div class="col-12">
                                    <input type="date" 
                                           name="date_fin" 
                                           id="date_fin" 
                                           value="{{ request('date_fin') }}"
                                           class="form-control glass-input rounded-xl py-2 px-3 text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-white font-semibold rounded-xl py-2.5 hover:scale-102 transition-all duration-300 border-0" style="background: #4f46e5;">
                                Filtrer
                            </button>
                            <a href="{{ route('p.evenement') }}" class="btn btn-outline-light border-white/10 hover:bg-white/5 rounded-xl py-2 text-sm text-gray-300">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contenu principal - Événements -->
        <div class="col-lg-8 col-xl-9">
            <!-- Événement en vedette (si applicable et s'il s'agit de la première page sans recherche particulière) -->
            @if(isset($featuredEvent) && $featuredEvent && !request('search') && !request('categorie'))
            <div class="mb-5">
                <h4 class="fw-bold mb-4 tracking-wide text-indigo-300 fs-5 uppercase">
                    Événement en vedette
                </h4>
                <div class="glass-card rounded-3xl overflow-hidden p-0 border border-white/10 relative">
                    <div class="row g-0">
                        <div class="col-md-6 order-2 order-md-1">
                            <div class="p-4 md:p-5 flex flex-col justify-between h-full" style="min-height: 280px;">
                                <div>
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-semibold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 mb-3 d-inline-block">
                                        {{ ucfirst($featuredEvent->categorie) }}
                                    </span>
                                    <h3 class="fw-extrabold text-white mb-2 fs-4 leading-tight">{{ $featuredEvent->titre }}</h3>
                                    <p class="text-gray-400 small mb-4 line-clamp-3 leading-relaxed">{{ $featuredEvent->truncated_description }}</p>
                                </div>
                                
                                <div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center text-gray-400 text-xs">
                                                <i class="fas fa-calendar-day me-2 text-indigo-500"></i>
                                                <span>{{ \Carbon\Carbon::parse($featuredEvent->date)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center text-gray-400 text-xs">
                                                <i class="fas fa-clock me-2 text-indigo-500"></i>
                                                <span>{{ \Carbon\Carbon::parse($featuredEvent->start_heure)->format('H:i') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center text-gray-400 text-xs">
                                                <i class="fas fa-map-marker-alt me-2 text-indigo-500"></i>
                                                <span class="text-truncate">{{ $featuredEvent->lieu }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('p.detail', $featuredEvent->id) }}" class="btn btn-sm px-4 rounded-xl text-white font-semibold hover:scale-105 transition-all duration-300 border-0" style="background: #4f46e5;">
                                        S'inscrire
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 order-1 order-md-2" style="height: 300px;">
                            <img src="{{ $featuredEvent->photo_url }}" 
                                 alt="{{ $featuredEvent->titre }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Liste des événements -->
            <div class="mb-5" id="events-container">
                <div class="d-flex justify-content-between align-items-center mb-4 border-b border-white/5 pb-3">
                    <h2 class="fw-bold tracking-wide h5 mb-0">
                        Tous les événements
                        <span class="badge bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-xl ms-2 text-sm">{{ $events->total() }} résultat{{ $events->total() > 1 ? 's' : '' }}</span>
                    </h2>
                </div>

                @if($events->count() > 0)
                    <div class="row g-4">
                        @foreach($events as $event)
                            <div class="col-md-6 col-xl-4">
                                <div class="glass-card rounded-2xl overflow-hidden hover-up h-100 flex flex-col justify-between">
                                    <div class="card-thumb group" style="height: 180px;">
                                        <img src="{{ $event->photo_url }}" alt="{{ $event->titre }}" style="transition: transform 0.7s ease;">
                                        
                                        <!-- Category -->
                                        <span class="absolute top-2 right-2 px-2.5 py-1 rounded-xl text-[10px] font-semibold text-white bg-black/60 backdrop-blur-md border border-white/10">
                                            {{ $event->categorie ?? 'Événement' }}
                                        </span>
                                        
                                        <!-- Status Badge -->
                                        @if($event->is_upcoming)
                                            <span class="absolute top-2 left-2 px-2.5 py-1 rounded-xl text-[10px] font-semibold text-white bg-emerald-500/80 border border-emerald-400/20 backdrop-blur-md">
                                                À venir
                                            </span>
                                        @else
                                            <span class="absolute top-2 left-2 px-2.5 py-1 rounded-xl text-[10px] font-semibold text-gray-300 bg-white/10 border border-white/5 backdrop-blur-md">
                                                Passé
                                            </span>
                                        @endif
                                    </div>

                                    <div class="p-3 flex-grow-1 flex flex-col justify-between">
                                        <div>
                                            <h5 class="fw-bold text-white mb-2 line-clamp-1 h6">{{ $event->titre }}</h5>
                                            <p class="text-gray-400 text-xs mb-3 line-clamp-2 leading-relaxed">{{ $event->truncated_description }}</p>
                                        </div>
                                        
                                        <div class="border-t border-white/5 pt-2">
                                            <div class="d-flex align-items-center text-gray-400 text-xs mb-1.5">
                                                <i class="fas fa-calendar-day me-2 text-indigo-500"></i>
                                                <span>{{ $event->formatted_date }}</span>
                                            </div>
                                            <div class="d-flex align-items-center text-gray-400 text-xs mb-1.5">
                                                <i class="fas fa-clock me-2 text-indigo-500"></i>
                                                <span>{{ $event->formatted_start_time }} - {{ $event->formatted_end_time }}</span>
                                            </div>
                                            <div class="d-flex align-items-center text-gray-400 text-xs mb-3">
                                                <i class="fas fa-map-marker-alt me-2 text-indigo-500"></i>
                                                <span class="text-truncate">{{ $event->lieu }}</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-2 border-t border-white/5">
                                                <span class="fw-bold text-indigo-400 text-sm">
                                                    @if($event->min_price > 0)
                                                        {{ number_format($event->min_price, 0, ',', ' ') }} FCFA
                                                    @else
                                                        Gratuit
                                                    @endif
                                                </span>
                                                <a href="{{ route('p.detail', $event->id) }}" class="btn btn-sm px-3 rounded-lg text-white font-medium hover:scale-105 transition-all duration-300 border-0" style="background: #4f46e5;">
                                                    Plus d'info
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-5">
                        {{ $events->links() }}
                    </div>
                @else
                    <div class="text-center py-5 glass-card rounded-3xl">
                        <p class="text-lg font-semibold text-gray-400 mb-2">Aucun résultat trouvé</p>
                        <p class="text-gray-500 small">Modifiez vos filtres de recherche ou réinitialisez pour afficher tous les événements.</p>
                        <a href="{{ route('p.evenement') }}" class="btn px-4 py-2 mt-2 rounded-xl text-white font-semibold hover:scale-105 transition-all duration-300 border-0" style="background: #4f46e5;">
                            Tous les événements
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .loading-opacity {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit du formulaire lors du changement des selects de filtre
    const form = document.getElementById('filter-form');
    const selects = form.querySelectorAll('select');
    const container = document.getElementById('events-container');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            if (container) {
                container.classList.add('loading-opacity');
            }
            form.submit();
        });
    });
});
</script>
@endsection