@extends('layouts.base')

@section('title', '| Découvrez les meilleurs événements')

@section('content')

<style>
    body {
        background-color: #fafbfc !important;
    }
</style>

{{-- ======================================================
     HERO & SEARCH BAR — Mockup Layout
     ====================================================== --}}
<section class="relative overflow-hidden py-5 pt-12 md:py-16 md:pt-20 px-4 text-center" style="background: radial-gradient(circle at top, rgba(217, 56, 58, 0.05) 0%, rgba(255, 255, 255, 0) 60%), #fafbfc;">
    <div class="max-w-3xl mx-auto mb-10 mt-6 animate__animated animate__fadeIn">
        <h1 class="font-extrabold text-slate-900 tracking-tight leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.5rem); font-family: 'Outfit', sans-serif;">
            Vivez l'instant présent.<br>
            <span class="text-[#d9383a]">Trouvez votre prochain événement.</span>
        </h1>
        <p class="text-slate-500 font-medium text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
            De la musique live aux conférences technologiques, explorez des milliers d'expériences uniques à travers le monde.
        </p>
    </div>

    <!-- Floating Search Bar -->
    <form action="{{ route('p.evenement') }}" method="GET" class="max-w-4xl mx-auto mb-16 px-2">
        <div class="bg-white rounded-2xl md:rounded-full shadow-lg border border-slate-100 p-2 md:p-3 flex flex-col md:flex-row align-items-center gap-3">
            <!-- Input 1: Event Name -->
            <div class="flex-1 w-full flex items-center px-4 py-2 border-b md:border-b-0 md:border-r border-slate-100 last:border-r-0">
                <i class="fas fa-search text-slate-400 me-3 fs-5"></i>
                <input type="text" name="search" placeholder="Nom de l'événement" class="w-full bg-transparent border-0 focus:outline-none focus:ring-0 text-slate-800 placeholder-slate-400 text-sm font-medium" value="{{ request('search') }}">
            </div>
            <!-- Input 2: Category -->
            <div class="w-full md:w-60 flex items-center px-4 py-2 border-b md:border-b-0 md:border-r border-slate-100 last:border-r-0">
                <i class="fas fa-th text-slate-400 me-3 fs-5"></i>
                <select name="categorie" class="w-full bg-transparent border-0 focus:outline-none focus:ring-0 text-slate-700 text-sm font-medium cursor-pointer appearance-none">
                    <option value="">Toutes les catégories</option>
                    <option value="Concert">Concerts & Musique</option>
                    <option value="Conférence">Conférences</option>
                    <option value="Sport">Sports</option>
                    <option value="Fête">Arts & Culture</option>
                    <option value="Santé">Santé</option>
                    <option value="Voyage">Voyages</option>
                    <option value="Vie nocturne">Vie nocturne</option>
                </select>
            </div>
            <!-- Input 3: Date -->
            <div class="w-full md:w-52 flex items-center px-4 py-2">
                <i class="fas fa-calendar-alt text-slate-400 me-3 fs-5"></i>
                <input type="date" name="date_debut" class="w-full bg-transparent border-0 focus:outline-none focus:ring-0 text-slate-700 text-sm font-medium cursor-pointer" value="{{ request('date_debut') }}">
            </div>
            <!-- Button -->
            <button type="submit" class="w-full md:w-auto px-8 py-3 bg-[#d9383a] hover:bg-[#c22e30] text-white font-bold rounded-xl md:rounded-full text-sm transition-all duration-200 shadow-md whitespace-nowrap border-0">
                Rechercher
            </button>
        </div>
    </form>
</section>

{{-- ======================================================
     MAIN CONTENT
     ====================================================== --}}
<main class="container py-4">

    {{-- Événements à la une --}}
    <section class="mb-16">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <h2 class="fw-extrabold text-slate-900 fs-3 position-relative pb-2 mb-0" style="font-family: 'Outfit', sans-serif;">
                Événements à la une
                <span class="position-absolute bottom-0 start-0 h-[3px] bg-[#d9383a]" style="width: 80px;"></span>
            </h2>
            <a href="{{ route('p.evenement') }}" class="text-slate-500 hover:text-indigo-600 text-decoration-none font-semibold d-flex align-items-center transition-colors duration-300 text-sm">
                Voir tout <i class="fas fa-arrow-right ms-2 text-xs"></i>
            </a>
        </div>

        @if($events->count() > 0)
            <div class="row g-4">
                @foreach($events->take(3) as $idx => $event)
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

                        // Top Left Badge
                        $leftBadge = null;
                        if ($idx == 0) {
                            $leftBadge = 'Le plus populaire';
                        } elseif ($idx == 2) {
                            $leftBadge = 'Dernières places';
                        }
                        
                        // Button style
                        $isRedButton = ($idx == 2); // Third card has red button in mockup
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="bg-white rounded-2xl border border-slate-100 hover:shadow-lg transition-all duration-300 h-100 flex flex-col justify-between overflow-hidden shadow-sm">
                            <!-- Card Image Container -->
                            <div class="position-relative overflow-hidden" style="height: 220px;">
                                <img src="{{ $event->photo_url }}" alt="{{ $event->titre }}" class="w-100 h-100 object-cover" style="transition: transform 0.5s ease;">
                                
                                <!-- Badges -->
                                @if($leftBadge)
                                    <span class="position-absolute top-3 start-3 px-3 py-1 bg-[#d9383a] text-white text-xs font-bold rounded-full shadow-sm">
                                        {{ $leftBadge }}
                                    </span>
                                @endif
                                
                                @if($event->min_price == 0)
                                    <span class="position-absolute top-3 end-3 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full shadow-sm">
                                        Gratuit
                                    </span>
                                @else
                                    <span class="position-absolute top-3 end-3 px-3 py-1 bg-white/80 backdrop-blur-md text-slate-800 text-xs font-bold rounded-full shadow-sm border border-white/20">
                                        À partir de {{ number_format($event->min_price, 0, ',', ' ') }} FCFA
                                    </span>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 flex-grow-1 flex flex-col justify-between">
                                <div>
                                    <!-- Category -->
                                    <div class="text-[#d9383a] text-xs font-bold uppercase tracking-wider mb-2">
                                        {{ $catDisplay }}
                                    </div>
                                    
                                    <!-- Title -->
                                    <h5 class="fw-bold text-slate-900 mb-3 fs-5" style="font-family: 'Outfit', sans-serif;">{{ $event->titre }}</h5>
                                    
                                    <!-- Date & Time -->
                                    <div class="d-flex align-items-center text-slate-500 text-sm mb-2">
                                        <i class="far fa-calendar-alt me-2 text-slate-400"></i>
                                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d F, H:i') }}</span>
                                    </div>
                                    
                                    <!-- Location -->
                                    <div class="d-flex align-items-center text-slate-500 text-sm mb-4">
                                        <i class="fas fa-map-marker-alt me-2 text-slate-400"></i>
                                        <span class="text-truncate">{{ $event->lieu }}</span>
                                    </div>
                                </div>

                                <!-- Card Action Button -->
                                <div>
                                    @if($isRedButton)
                                        <a href="{{ route('p.detail', $event->id) }}" class="btn w-100 py-2.5 bg-[#d9383a] hover:bg-[#c22e30] text-white font-bold rounded-lg text-sm transition-all duration-200 border-0 shadow-sm text-decoration-none d-flex justify-content-center align-items-center">
                                            Acheter maintenant
                                        </a>
                                    @else
                                        <a href="{{ route('p.detail', $event->id) }}" class="btn w-100 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold rounded-lg text-sm transition-all duration-200 shadow-xs text-decoration-none d-flex justify-content-center align-items-center">
                                            {{ $event->min_price == 0 ? 'Réserver ma place' : 'Détails du billet' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 bg-white border border-slate-100 rounded-2xl shadow-sm">
                <p class="text-slate-500 fw-semibold mb-1">Aucun événement disponible</p>
                <p class="text-slate-400 small">Revenez bientôt, de nouveaux événements arrivent.</p>
            </div>
        @endif
    </section>

    {{-- Parcourir par catégorie --}}
    <section class="mb-16">
        <h3 class="fw-bold mb-5 text-center text-slate-900 fs-3" style="font-family: 'Outfit', sans-serif;">Parcourir par catégorie</h3>
        <div class="row g-4 justify-content-center">
            <!-- Musique -->
            <div class="col-6 col-md-3">
                <a href="{{ route('p.concert et festival de musique') }}" class="block p-6 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 text-center text-decoration-none group">
                    <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-music text-indigo-600 fs-5"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-sm md:text-base group-hover:text-indigo-600 transition-colors">Musique</span>
                </a>
            </div>
            <!-- Conférences -->
            <div class="col-6 col-md-3">
                <a href="{{ route('p.conferences et congres') }}" class="block p-6 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 text-center text-decoration-none group">
                    <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-microphone text-indigo-600 fs-5"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-sm md:text-base group-hover:text-indigo-600 transition-colors">Conférences</span>
                </a>
            </div>
            <!-- Sport -->
            <div class="col-6 col-md-3">
                <a href="{{ route('p.evenement sportif') }}" class="block p-6 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 text-center text-decoration-none group">
                    <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-running text-indigo-600 fs-5"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-sm md:text-base group-hover:text-indigo-600 transition-colors">Sport</span>
                </a>
            </div>
            <!-- Arts & Culture -->
            <div class="col-6 col-md-3">
                <a href="{{ route('p.fete') }}" class="block p-6 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 text-center text-decoration-none group">
                    <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-paint-brush text-indigo-600 fs-5"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-sm md:text-base group-hover:text-indigo-600 transition-colors">Arts & Culture</span>
                </a>
            </div>
        </div>
    </section>

</main>
@endsection
