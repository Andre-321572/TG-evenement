@extends('layouts.base')

@section('title', '| Rechercher des Événements')

@section('content')

<style>
    body {
        background-color: #fafbfc !important;
    }
    
    /* Custom range slider styling */
    .range-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 5px;
        border-radius: 999px;
        background: #e2e8f0;
        outline: none;
    }
    .range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #d9383a;
        cursor: pointer;
        transition: transform 0.15s;
        border: none;
    }
    .range-slider::-webkit-slider-thumb:hover {
        transform: scale(1.25);
    }
    .range-slider::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #d9383a;
        cursor: pointer;
        transition: transform 0.15s;
        border: none;
    }
    .range-slider::-moz-range-thumb:hover {
        transform: scale(1.25);
    }

    /* Checkbox styling */
    .filter-checkbox {
        width: 1.15rem;
        height: 1.15rem;
        border-radius: 4px !important;
        border-color: #cbd5e1 !important;
        cursor: pointer;
    }
    .filter-checkbox:checked {
        background-color: #d9383a !important;
        border-color: #d9383a !important;
    }

    /* Hearts */
    .btn-heart:hover {
        color: #d9383a !important;
        background-color: rgba(255,255,255,0.9) !important;
    }
</style>

<div class="container py-5 text-slate-800">
    <div class="mb-4">
        <h1 class="fw-bold text-slate-900 fs-3 mb-4" style="font-family: 'Outfit', sans-serif;">Trouver un événement</h1>
    </div>

    <!-- Main search & filter form -->
    <form method="GET" action="{{ route('p.evenement') }}" id="filter-form">
        <!-- Hidden inputs for dates set by buttons -->
        <input type="hidden" name="date_debut" id="date_debut" value="{{ request('date_debut') }}">
        <input type="hidden" name="date_fin" id="date_fin" value="{{ request('date_fin') }}">

        <!-- Top Search Bar Container -->
        <div class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm mb-5">
            <div class="row g-3 align-items-center">
                <!-- Search query -->
                <div class="col-md-5 d-flex align-items-center px-4 border-end border-slate-100 last:border-end-0">
                    <i class="fas fa-search text-slate-400 me-2.5 fs-5"></i>
                    <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Rechercher des concerts, conférences, sports..." class="form-control bg-transparent border-0 shadow-none text-slate-800 text-sm py-1.5 focus:ring-0 focus:outline-none w-100">
                </div>
                <!-- Location -->
                <div class="col-md-4 d-flex align-items-center px-4">
                    <i class="fas fa-map-marker-alt text-slate-400 me-2.5 fs-5"></i>
                    <input type="text" name="lieu" id="lieu-input" value="{{ request('lieu') }}" placeholder="Ville ou région" class="form-control bg-transparent border-0 shadow-none text-slate-800 text-sm py-1.5 focus:ring-0 focus:outline-none w-100">
                </div>
                <!-- Submit button -->
                <div class="col-md-3">
                    <button type="submit" class="btn w-100 py-2.5 text-white font-semibold rounded-xl border-0 shadow-sm transition-all duration-200" style="background: #1c2434; hover: background: #121824;">
                        Rechercher
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <!-- Sidebar - Filters -->
            <div class="col-lg-3 col-md-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sticky-top" style="top: 96px; z-index: 10;">
                    
                    <!-- Categories filter -->
                    <div class="mb-4">
                        <h6 class="text-slate-500 font-bold uppercase tracking-wider mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Catégories</h6>
                        <div class="d-flex flex-column gap-2.5">
                            @foreach($categories as $cat)
                                @php
                                    $isChecked = (is_array(request('categories')) && in_array($cat, request('categories'))) || request('categorie') == $cat;
                                @endphp
                                <label class="d-flex align-items-center gap-2.5 cursor-pointer text-slate-700 text-sm hover:text-indigo-600 mb-0 font-medium">
                                    <input type="checkbox" name="categories[]" value="{{ $cat }}" {{ $isChecked ? 'checked' : '' }} class="form-check-input filter-checkbox">
                                    <span>{{ ucfirst($cat) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <h6 class="text-slate-500 font-bold uppercase tracking-wider mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Prix</h6>
                        @php
                            $currentPrixMax = request('prix_max', 350);
                        @endphp
                        <div class="px-1">
                            <input type="range" name="prix_max" id="prix_max_range" min="0" max="500" step="10" value="{{ $currentPrixMax }}" class="range-slider">
                            <div class="d-flex justify-content-between text-slate-500 font-semibold mt-2" style="font-size: 0.78rem;">
                                <span id="price-display-label">Jusqu'à {{ $currentPrixMax }}F</span>
                                <span>500F+</span>
                            </div>
                        </div>
                    </div>

                    <!-- Date Quick Filters -->
                    <div class="mb-4">
                        <h6 class="text-slate-500 font-bold uppercase tracking-wider mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Date</h6>
                        <div class="d-flex gap-2">
                            <button type="button" id="btn-weekend" onclick="setDateRange('weekend')" class="btn btn-sm flex-fill py-2 px-3 rounded-xl border border-slate-200 text-slate-700 font-semibold bg-slate-50 hover:bg-slate-100 text-xs transition-colors">
                                Ce week-end
                            </button>
                            <button type="button" id="btn-month" onclick="setDateRange('month')" class="btn btn-sm flex-fill py-2 px-3 rounded-xl border border-slate-200 text-slate-700 font-semibold bg-slate-50 hover:bg-slate-100 text-xs transition-colors">
                                Ce mois
                            </button>
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <div class="pt-2">
                        <a href="{{ route('p.evenement') }}" class="btn w-100 py-2 border border-[#d9383a] text-[#d9383a] bg-white hover:bg-red-50 font-bold rounded-xl text-sm transition-colors text-decoration-none d-flex justify-content-center align-items-center border-2">
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main grid list -->
            <div class="col-lg-9 col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                    <span class="text-slate-600 font-medium text-sm">
                        {{ $events->total() }} résultat{{ $events->total() > 1 ? 's' : '' }} trouvé{{ $events->total() > 1 ? 's' : '' }}
                    </span>
                    
                    <!-- Sorting -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-slate-500 text-sm whitespace-nowrap">Trier par:</span>
                        <select name="sort" id="sort-select" class="form-select bg-transparent border-0 text-slate-800 font-bold text-sm py-1 pe-8 focus:ring-0 cursor-pointer shadow-none">
                            <option value="priority" {{ request('sort') == 'priority' || !request('sort') ? 'selected' : '' }}>Pertinence</option>
                            <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Le plus proche</option>
                            <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Le plus lointain</option>
                            <option value="titre_asc" {{ request('sort') == 'titre_asc' ? 'selected' : '' }}>Titre (A-Z)</option>
                        </select>
                    </div>
                </div>

                @if($events->count() > 0)
                    <div class="row g-4" id="events-grid-container">
                        @foreach($events as $event)
                            @php
                                // Category detection
                                $catName = strtolower($event->categorie ?? '');
                                $catDisplay = '📅 ÉVÉNEMENT';
                                if (str_contains($catName, 'concert') || str_contains($catName, 'musique')) {
                                    $catDisplay = '🎵 MUSIQUE';
                                } elseif (str_contains($catName, 'conf') || str_contains($catName, 'atelier') || str_contains($catName, 'formation') || str_contains($catName, 'congr')) {
                                    $catDisplay = '🎤 CONFÉRENCE';
                                } elseif (str_contains($catName, 'sport') || str_contains($catName, 'fitness')) {
                                    $catDisplay = '🏃 SPORT';
                                } elseif (str_contains($catName, 'art') || str_contains($catName, 'cult') || str_contains($catName, 'fete') || str_contains($catName, 'fête')) {
                                    $catDisplay = '🎨 ART & CULTURE';
                                }
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="bg-white rounded-2xl border border-slate-100 hover:shadow-lg transition-all duration-300 h-100 flex flex-col justify-between overflow-hidden shadow-sm">
                                    <!-- Image Container -->
                                    <div class="position-relative overflow-hidden" style="height: 180px;">
                                        <img src="{{ $event->photo_url }}" alt="{{ $event->titre }}" class="w-100 h-100 object-cover" style="transition: transform 0.5s ease;">
                                        
                                        <!-- Date Badge -->
                                        <div class="position-absolute top-3 start-3 bg-white rounded-xl shadow-md d-flex flex-column align-items-center justify-content-center p-2" style="width: 46px; height: 50px; line-height: 1.1;">
                                            <span class="fw-bold text-slate-900 fs-5 mb-0">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                            <span class="text-[#d9383a] font-extrabold uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                                {{ substr(Str::ascii(\Carbon\Carbon::parse($event->date)->isoFormat('MMM')), 0, 3) }}
                                            </span>
                                        </div>

                                        <!-- Heart Icon Button -->
                                        <div class="position-absolute top-3 end-3 w-9 h-9 rounded-full bg-black/40 backdrop-blur-xs d-flex align-items-center justify-content-center text-white cursor-pointer btn-heart transition-all duration-200">
                                            <i class="far fa-heart"></i>
                                        </div>
                                    </div>

                                    <!-- Content Body -->
                                    <div class="p-3 flex-grow-1 flex flex-col justify-between">
                                        <div>
                                            <!-- Category & Time Row -->
                                            <div class="d-flex align-items-center gap-2 mb-2 text-xs">
                                                <span class="text-indigo-600 font-bold uppercase tracking-wider">{{ $event->categorie ?? 'Événement' }}</span>
                                                <span class="text-slate-400">•</span>
                                                <span class="text-slate-500 d-flex align-items-center">
                                                    <i class="far fa-clock me-1 text-slate-400"></i>
                                                    {{ \Carbon\Carbon::parse($event->start_heure)->format('H:i') }}
                                                </span>
                                            </div>
                                            
                                            <!-- Title -->
                                            <h5 class="fw-bold text-slate-900 mb-2 fs-6 line-clamp-1" style="font-family: 'Outfit', sans-serif;">
                                                <a href="{{ route('p.detail', $event->id) }}" class="text-slate-900 text-decoration-none hover:text-indigo-600">{{ $event->titre }}</a>
                                            </h5>
                                            
                                            <!-- Description -->
                                            <p class="text-slate-500 text-xs mb-3 line-clamp-2 leading-relaxed">{{ $event->truncated_description }}</p>
                                        </div>

                                        <!-- Location & Price Row -->
                                        <div class="d-flex justify-content-between align-items-center border-t border-slate-100 pt-3 mt-auto">
                                            <div class="d-flex align-items-center text-slate-500 text-xs text-truncate me-2">
                                                <i class="fas fa-map-marker-alt me-1.5 text-slate-400"></i>
                                                <span class="text-truncate">{{ $event->lieu }}</span>
                                            </div>
                                            <span class="fw-bold text-indigo-900 text-sm shrink-0">
                                                @if($event->min_price > 0)
                                                    {{ number_format($event->min_price, 0, ',', ' ') }} FCFA
                                                @else
                                                    Gratuit
                                                @endif
                                            </span>
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
                    <div class="text-center py-5 bg-white border border-slate-100 rounded-2xl shadow-sm">
                        <p class="text-lg font-semibold text-slate-500 mb-2">Aucun résultat trouvé</p>
                        <p class="text-slate-400 small">Modifiez vos filtres de recherche ou réinitialisez pour afficher tous les événements.</p>
                        <a href="{{ route('p.evenement') }}" class="btn px-4 py-2 mt-2 rounded-xl text-white font-semibold border-0" style="background: #1c2434;">
                            Réinitialiser les filtres
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </form>
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
    .loading-opacity {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    const container = document.getElementById('events-grid-container');
    
    // Submit on checkbox, select or price range change
    const autoSubmitElements = form.querySelectorAll('input[type="checkbox"], select');
    autoSubmitElements.forEach(el => {
        el.addEventListener('change', function() {
            if (container) container.classList.add('loading-opacity');
            form.submit();
        });
    });

    // Handle Price range visual updates and submit on mouseup
    const priceRange = document.getElementById('prix_max_range');
    const priceDisplay = document.getElementById('price-display-label');
    if (priceRange && priceDisplay) {
        priceRange.addEventListener('input', function() {
            priceDisplay.textContent = `Jusqu'à ${priceRange.value}F`;
        });
        priceRange.addEventListener('change', function() {
            if (container) container.classList.add('loading-opacity');
            form.submit();
        });
    }

    // Highlight active date quick filters
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    
    if (dateDebut && dateFin) {
        const today = new Date();
        
        // Check if weekend is selected
        const currentDay = today.getDay();
        const distToSat = currentDay <= 6 ? 6 - currentDay : 6;
        const sat = new Date(today);
        sat.setDate(today.getDate() + distToSat);
        const sun = new Date(sat);
        sun.setDate(sat.getDate() + 1);
        
        const satStr = formatDate(sat);
        const sunStr = formatDate(sun);
        const endOfMonthStr = formatDate(new Date(today.getFullYear(), today.getMonth() + 1, 0));

        if (dateDebut === satStr && dateFin === sunStr) {
            document.getElementById('btn-weekend').classList.replace('bg-slate-50', 'bg-indigo-50');
            document.getElementById('btn-weekend').classList.add('border-indigo-300', 'text-indigo-600');
        } else if (dateDebut === formatDate(today) && dateFin === endOfMonthStr) {
            document.getElementById('btn-month').classList.replace('bg-slate-50', 'bg-indigo-50');
            document.getElementById('btn-month').classList.add('border-indigo-300', 'text-indigo-600');
        }
    }
});

function setDateRange(rangeType) {
    const today = new Date();
    let start, end;
    
    if (rangeType === 'weekend') {
        const currentDay = today.getDay();
        const distToSat = currentDay <= 6 ? 6 - currentDay : 6;
        start = new Date(today);
        start.setDate(today.getDate() + distToSat);
        end = new Date(start);
        end.setDate(start.getDate() + 1);
    } else if (rangeType === 'month') {
        start = new Date(today);
        end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    }
    
    if (start && end) {
        document.getElementById('date_debut').value = formatDate(start);
        document.getElementById('date_fin').value = formatDate(end);
        
        const container = document.getElementById('events-grid-container');
        if (container) container.classList.add('loading-opacity');
        document.getElementById('filter-form').submit();
    }
}

function formatDate(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}
</script>
@endsection