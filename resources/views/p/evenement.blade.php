@extends('layouts.base')

@section('title', '| Rechercher des Événements')

@section('content')

<style>
    /* Styling overrides for premium feel */
    body {
        background-color: #f8fafc !important;
    }
    
    /* Range slider customize */
    .range-slider-premium {
        -webkit-appearance: none;
        width: 100%;
        height: 6px;
        border-radius: 999px;
        background: #e2e8f0;
        outline: none;
    }
    .range-slider-premium::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #4f46e5;
        cursor: pointer;
        transition: transform 0.15s ease, background-color 0.15s ease;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.4);
    }
    .range-slider-premium::-webkit-slider-thumb:hover {
        transform: scale(1.2);
        background: #4338ca;
    }
    .range-slider-premium::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #4f46e5;
        cursor: pointer;
        transition: transform 0.15s ease, background-color 0.15s ease;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.4);
    }
    .range-slider-premium::-moz-range-thumb:hover {
        transform: scale(1.2);
        background: #4338ca;
    }

    /* Premium Checkbox style */
    .custom-cb {
        width: 18px;
        height: 18px;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s ease;
        accent-color: #4f46e5;
    }

    /* Card visual enhancements */
    .premium-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .premium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }
    .premium-card:hover img {
        transform: scale(1.06);
    }
    .heart-btn {
        transition: all 0.2s ease;
    }
    .heart-btn:hover {
        background: #ffffff !important;
        color: #ef4444 !important;
        transform: scale(1.1);
    }
</style>

{{-- Hero Section --}}
<div class="w-full bg-gradient-to-r from-blue-50 to-indigo-100/50 py-10 md:py-14 border-b border-indigo-100/30 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        
        {{-- Hero Text & Search Box --}}
        <div class="lg:col-span-8 z-10 space-y-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight">
                    Trouvez l'événement<br>
                    <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">qui vous inspire</span>
                </h1>
                <p class="text-slate-500 text-base md:text-lg mt-3 font-medium">
                    Conférences, ateliers, concerts et plus encore près de chez vous.
                </p>
            </div>

            {{-- Overlapping Search overlay --}}
            <form method="GET" action="{{ route('p.evenement') }}" class="bg-white p-3 rounded-2xl md:rounded-full shadow-lg border border-slate-100 grid grid-cols-1 md:grid-cols-12 gap-2 items-center max-w-3xl">
                <input type="hidden" name="date_debut" id="date_debut" value="{{ request('date_debut') }}">
                <input type="hidden" name="date_fin" id="date_fin" value="{{ request('date_fin') }}">
                
                {{-- Search query input --}}
                <div class="md:col-span-5 flex items-center px-4 border-b md:border-b-0 md:border-r border-slate-100 pb-2 md:pb-0">
                    <i class="fas fa-search text-slate-400 mr-3 text-lg"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher des concerts, conférences, workshops..." 
                           class="w-full bg-transparent border-0 focus:outline-none focus:ring-0 text-slate-800 placeholder-slate-400 text-sm">
                </div>

                {{-- Location input --}}
                <div class="md:col-span-4 flex items-center px-4 pb-2 md:pb-0">
                    <i class="fas fa-map-marker-alt text-slate-400 mr-3 text-lg"></i>
                    <input type="text" name="lieu" value="{{ request('lieu') }}" placeholder="Ville ou région" 
                           class="w-full bg-transparent border-0 focus:outline-none focus:ring-0 text-slate-800 placeholder-slate-400 text-sm">
                </div>

                {{-- Submit button --}}
                <div class="md:col-span-3">
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl md:rounded-full transition-colors shadow-md hover:shadow-indigo-500/20">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>

        {{-- Hero Illustration/Image --}}
        <div class="hidden lg:block lg:col-span-4 relative">
            <div class="w-72 h-72 bg-gradient-to-tr from-indigo-500/20 to-violet-500/20 rounded-full absolute -top-4 -left-4 blur-2xl"></div>
            <img src="{{ asset('asset/image/hero-img.png') }}" alt="TGEvent illustration" class="relative z-10 w-full max-h-72 object-contain mx-auto">
        </div>

    </div>
</div>

{{-- Main Section: Filters and Events list --}}
<div class="max-w-7xl mx-auto px-6 py-10">
    <form method="GET" action="{{ route('p.evenement') }}" id="filter-form">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="lieu" value="{{ request('lieu') }}">
        <input type="hidden" name="date_debut" id="hidden_date_debut" value="{{ request('date_debut') }}">
        <input type="hidden" name="date_fin" id="hidden_date_fin" value="{{ request('date_fin') }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- 1. LEFT SIDEBAR FILTERS --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sticky top-24 space-y-6">
                    
                    {{-- Header filter --}}
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="font-extrabold text-slate-900 text-base">Filtres</span>
                        <a href="{{ route('p.evenement') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">
                            Réinitialiser
                        </a>
                    </div>

                    {{-- Categories --}}
                    <div>
                        <h6 class="text-slate-500 font-extrabold text-xs uppercase tracking-wider mb-3">Catégories</h6>
                        <div class="flex flex-col gap-2.5">
                            @foreach($categories as $cat)
                                @php
                                    $isChecked = (is_array(request('categories')) && in_array($cat, request('categories'))) || request('categorie') == $cat;
                                @endphp
                                <label class="flex items-center gap-3 cursor-pointer text-slate-700 text-sm font-semibold hover:text-indigo-600 transition-colors">
                                    <input type="checkbox" name="categories[]" value="{{ $cat }}" {{ $isChecked ? 'checked' : '' }} class="custom-cb">
                                    <span>{{ ucfirst($cat) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Slider --}}
                    <div>
                        <h6 class="text-slate-500 font-extrabold text-xs uppercase tracking-wider mb-3">Prix</h6>
                        @php
                            $currentPrixMax = request('prix_max', 5000);
                        @endphp
                        <input type="range" name="prix_max" id="prix_max_range" min="0" max="5000" step="100" value="{{ $currentPrixMax }}" class="range-slider-premium">
                        <div class="flex justify-between text-slate-500 font-bold text-xs mt-2">
                            <span id="price-display-label">Jusqu'à {{ number_format($currentPrixMax, 0, ',', ' ') }} FCFA</span>
                            <span>5000+ FCFA</span>
                        </div>
                    </div>

                    {{-- Custom Dates Inputs --}}
                    <div>
                        <h6 class="text-slate-500 font-extrabold text-xs uppercase tracking-wider mb-3">Date</h6>
                        <div class="space-y-2">
                            <div>
                                <label class="text-[11px] font-bold text-slate-400 block mb-1">Date début</label>
                                <input type="date" name="custom_date_debut" id="custom_date_debut" value="{{ request('date_debut') }}"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-slate-400 block mb-1">Date fin</label>
                                <input type="date" name="custom_date_fin" id="custom_date_fin" value="{{ request('date_fin') }}"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- Location Filter input box --}}
                    <div>
                        <h6 class="text-slate-500 font-extrabold text-xs uppercase tracking-wider mb-2">Lieu</h6>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="filter_lieu" value="{{ request('lieu') }}" placeholder="Lieu ou région"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- Apply button --}}
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm">
                        Appliquer les filtres
                    </button>
                </div>
            </div>

            {{-- 2. EVENTS LIST AREA --}}
            <div class="lg:col-span-9">
                
                {{-- Header count and sorting --}}
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-slate-900">Événements à ne pas manquer</h2>
                        <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 text-[11px] font-bold px-2 py-0.5 rounded-full">
                            {{ $events->total() }} événement{{ $events->total() > 1 ? 's' : '' }}
                        </span>
                    </div>

                    {{-- Sorting select --}}
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 text-xs font-semibold">Trier par:</span>
                        <select name="sort" id="sort-select" onchange="this.form.submit()" class="bg-transparent border-0 text-slate-800 font-bold text-xs py-1 cursor-pointer focus:ring-0">
                            <option value="priority" {{ request('sort') == 'priority' || !request('sort') ? 'selected' : '' }}>Pertinence</option>
                            <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Le plus proche</option>
                            <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Le plus lointain</option>
                            <option value="titre_asc" {{ request('sort') == 'titre_asc' ? 'selected' : '' }}>Titre (A-Z)</option>
                        </select>
                    </div>
                </div>

                @if($events->count() > 0)
                    {{-- Cards Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="events-grid-container">
                        @foreach($events as $event)
                            @php
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
                                $isFeatured = $loop->first || $event->is_upcoming;
                            @endphp

                            <div class="bg-white rounded-2xl border border-slate-100 premium-card overflow-hidden flex flex-col justify-between shadow-sm relative">
                                
                                {{-- Card Image wrapper --}}
                                <div class="relative overflow-hidden h-44">
                                    <img src="{{ $event->photo_url }}" alt="{{ $event->titre }}" class="w-full h-full object-cover transition-transform duration-500">
                                    
                                    {{-- Featured Badge --}}
                                    @if($isFeatured)
                                        <span class="absolute top-3.5 left-3.5 bg-blue-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md tracking-wider uppercase">
                                            Featured
                                        </span>
                                    @endif

                                    {{-- Heart Action Button --}}
                                    <button type="button" class="absolute top-3.5 right-3.5 w-9 h-9 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white heart-btn border-0">
                                        <i class="far fa-heart text-sm"></i>
                                    </button>

                                    {{-- Date Badge (Floating top-left style) --}}
                                    @if(!$isFeatured)
                                        <div class="absolute top-3.5 left-3.5 bg-white/95 backdrop-blur-xs rounded-xl shadow-md flex flex-col items-center justify-center p-1.5" style="width: 44px; height: 46px; line-height: 1.1;">
                                            <span class="font-extrabold text-slate-900 text-base">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                            <span class="text-indigo-600 font-extrabold uppercase text-[9px] tracking-wider">
                                                {{ substr(Str::ascii(\Carbon\Carbon::parse($event->date)->isoFormat('MMM')), 0, 3) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Card body --}}
                                <div class="p-4 flex-grow flex flex-col justify-between">
                                    <div>
                                        {{-- Category --}}
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-indigo-600 font-extrabold text-[10px] tracking-wider uppercase">
                                                {{ $catDisplay }}
                                            </span>
                                            <span class="text-slate-300 text-xs">•</span>
                                            <span class="text-slate-500 text-[11px] font-semibold flex items-center">
                                                <i class="far fa-clock mr-1 text-slate-400"></i>
                                                {{ \Carbon\Carbon::parse($event->start_heure)->format('H:i') }}
                                            </span>
                                        </div>

                                        {{-- Title --}}
                                        <h3 class="font-extrabold text-slate-900 text-sm mb-2 hover:text-indigo-600 transition-colors line-clamp-1">
                                            <a href="{{ route('p.detail', $event->id) }}" class="text-slate-900 no-underline">{{ $event->titre }}</a>
                                        </h3>

                                        {{-- Excerpt description --}}
                                        <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed mb-4">
                                            {{ $event->truncated_description }}
                                        </p>
                                    </div>

                                    {{-- Footer row (Location & Price) --}}
                                    <div class="flex justify-between items-center border-t border-slate-100 pt-3 mt-auto">
                                        <div class="flex items-center text-slate-500 text-xs min-w-0 mr-3">
                                            <i class="fas fa-map-marker-alt mr-1.5 text-slate-400 text-[11px]"></i>
                                            <span class="truncate font-semibold">{{ $event->lieu }}</span>
                                        </div>
                                        <span class="font-extrabold text-emerald-600 text-xs bg-emerald-50 px-2.5 py-1 rounded-full whitespace-nowrap">
                                            @if($event->min_price > 0)
                                                {{ number_format($event->min_price, 0, ',', ' ') }} FCFA
                                            @else
                                                Gratuit
                                            @endif
                                        </span>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="flex justify-center mt-8">
                        {{ $events->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16 bg-white border border-slate-100 rounded-2xl shadow-sm space-y-3">
                        <div class="text-4xl">🔍</div>
                        <p class="text-base font-bold text-slate-700">Aucun résultat trouvé</p>
                        <p class="text-slate-400 text-xs max-w-sm mx-auto">
                            Modifiez vos filtres de recherche ou réinitialisez pour afficher tous les événements.
                        </p>
                        <a href="{{ route('p.evenement') }}" class="inline-block px-5 py-2 mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                            Réinitialiser les filtres
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </form>

    {{-- 3. BANNIERE CTA --}}
    <div class="mt-16 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100/50 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="p-3.5 bg-indigo-600/10 text-indigo-600 rounded-2xl text-2xl hidden md:block">⚡</div>
            <div>
                <h4 class="font-extrabold text-slate-900 text-base md:text-lg">Organisez votre événement ?</h4>
                <p class="text-slate-500 text-xs md:text-sm mt-0.5">Touchez des milliers de participants et développez votre communauté.</p>
            </div>
        </div>
        <a href="{{ route('organisateur.ajouter-un-evenement') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl text-sm transition-all shadow-md shadow-indigo-600/15 flex items-center gap-2 whitespace-nowrap">
            <span>Publier un événement</span>
            <i class="fas fa-arrow-right text-xs opacity-80"></i>
        </a>
    </div>

    {{-- 4. TRUST BADGES SECTION --}}
    <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-10 border-t border-slate-200/60">
        @php
            $badges = [
                ['title' => 'Événements vérifiés', 'desc' => 'Des événements de qualité validés par notre équipe.', 'icon' => '🛡️'],
                ['title' => 'Paiement sécurisé', 'desc' => 'Réservez en toute sécurité avec plusieurs options.', 'icon' => '💳'],
                ['title' => 'Rappels & Notifications', 'desc' => 'Ne manquez aucun événement avec nos rappels.', 'icon' => '🔔'],
                ['title' => 'Communauté active', 'desc' => 'Rejoignez une communauté passionnée.', 'icon' => '👥']
            ];
        @endphp
        @foreach($badges as $b)
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-lg">
                    {{ $b['icon'] }}
                </div>
                <div>
                    <h5 class="font-bold text-slate-800 text-sm">{{ $b['title'] }}</h5>
                    <p class="text-slate-500 text-xs mt-1 leading-relaxed">{{ $b['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    
    // Custom date synchronizer to hidden fields
    const customStart = document.getElementById('custom_date_debut');
    const customEnd = document.getElementById('custom_date_fin');
    const hiddenStart = document.getElementById('hidden_date_debut');
    const hiddenEnd = document.getElementById('hidden_date_fin');
    
    if (customStart && hiddenStart) {
        customStart.addEventListener('change', function() {
            hiddenStart.value = this.value;
        });
    }
    if (customEnd && hiddenEnd) {
        customEnd.addEventListener('change', function() {
            hiddenEnd.value = this.value;
        });
    }
});
</script>

@endsection