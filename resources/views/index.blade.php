@extends('layouts.base')

@section('title', '| Découvrez les meilleurs événements')

@section('content')
<main class="container py-5 text-white">
    <!-- Hero Section -->
    <section class="relative overflow-hidden rounded-3xl mb-12 p-8 md:p-12 lg:p-16 border border-white/10" style="background: rgba(13, 16, 27, 0.65); backdrop-filter: blur(12px);">
        
        <div class="row align-items-center relative z-10">
            <div class="col-lg-8 mb-4 mb-lg-0 text-center text-lg-start">
                <h1 class="display-4 fw-extrabold mb-3 leading-tight">
                    Trouvez & vivez des <br>
                    <span class="text-gradient-primary">moments exceptionnels</span>
                </h1>
                <p class="fs-5 text-gray-400 mb-5 max-w-xl">
                    Découvrez et participez à des concerts, conférences, compétitions sportives et soirées exclusives près de chez vous.
                </p>
                
                <form action="{{ route('p.evenement') }}" method="GET" class="max-w-2xl">
                    <div class="input-group p-1.5 rounded-2xl border border-white/10 bg-black/30 backdrop-blur-md shadow-2xl focus-within:border-indigo-500 transition-all duration-300">
                        <span class="input-group-text bg-transparent border-0 text-gray-400 px-3">
                            <i class="fas fa-search fs-5"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-transparent border-0 text-white shadow-none placeholder:text-gray-500 py-3" placeholder="Rechercher par artiste, lieu, mot-clé..." value="{{ request('search') }}">
                        <button class="btn px-4 rounded-xl text-white font-semibold shadow-lg hover:scale-105 transition-all duration-300 border-0" type="submit" style="background: #6366f1;">
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="col-lg-4 d-none d-lg-block">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=500&q=80" alt="Spectacle TGEvent" class="img-fluid rounded-2xl border border-white/10 shadow-2xl relative z-10 hover:rotate-2 transition-transform duration-500">
                </div>
            </div>
        </div>
    </section>

    <!-- Catégories populaires -->
    <section class="mb-12">
        <h3 class="fw-bold mb-4 tracking-wide text-indigo-300 fs-5 uppercase"><i class="fas fa-th-large me-2"></i>Catégories Populaires</h3>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('p.concert et festival de musique') }}" class="px-4 py-2.5 rounded-full border border-white/10 bg-white/5 text-gray-300 hover:bg-indigo-600/30 hover:border-indigo-500 hover:text-white transition-all duration-300 text-decoration-none d-flex align-items-center">  
                <i class="fas fa-music me-2 text-pink-500"></i> Musique
            </a>
            <a href="{{ route('p.fete') }}" class="px-4 py-2.5 rounded-full border border-white/10 bg-white/5 text-gray-300 hover:bg-indigo-600/30 hover:border-indigo-500 hover:text-white transition-all duration-300 text-decoration-none d-flex align-items-center">
                <i class="fas fa-glass-cheers me-2 text-amber-500"></i> Fêtes
            </a>
            <a href="{{ route('p.evenement sportif') }}" class="px-4 py-2.5 rounded-full border border-white/10 bg-white/5 text-gray-300 hover:bg-indigo-600/30 hover:border-indigo-500 hover:text-white transition-all duration-300 text-decoration-none d-flex align-items-center">
                <i class="fas fa-running me-2 text-emerald-500"></i> Sport
            </a>
            <a href="{{ route('p.voyage') }}" class="px-4 py-2.5 rounded-full border border-white/10 bg-white/5 text-gray-300 hover:bg-indigo-600/30 hover:border-indigo-500 hover:text-white transition-all duration-300 text-decoration-none d-flex align-items-center">
                <i class="fas fa-plane me-2 text-teal-500"></i> Voyages
            </a>
            <a href="{{ route('p.santé') }}" class="px-4 py-2.5 rounded-full border border-white/10 bg-white/5 text-gray-300 hover:bg-indigo-600/30 hover:border-indigo-500 hover:text-white transition-all duration-300 text-decoration-none d-flex align-items-center">
                <i class="fas fa-heartbeat me-2 text-red-500"></i> Santé
            </a>
            <a href="{{ route('p.vie nocturne') }}" class="px-4 py-2.5 rounded-full border border-white/10 bg-white/5 text-gray-300 hover:bg-indigo-600/30 hover:border-indigo-500 hover:text-white transition-all duration-300 text-decoration-none d-flex align-items-center">
                <i class="fas fa-cocktail me-2 text-violet-500"></i> Vie nocturne
            </a>
            <a href="{{ route('p.conferences et congres') }}" class="px-4 py-2.5 rounded-full border border-white/10 bg-white/5 text-gray-300 hover:bg-indigo-600/30 hover:border-indigo-500 hover:text-white transition-all duration-300 text-decoration-none d-flex align-items-center">
                <i class="fas fa-microphone me-2 text-blue-500"></i> Conférences
            </a>
        </div>
    </section>

    <!-- Liste des Événements -->
    <section class="mb-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold tracking-wide">
                @if($events->where('is_upcoming', true)->count() > 0)
                    Événements à venir
                @else
                    Découvertes récentes
                @endif
            </h2>
            <a href="{{ route('p.evenement') }}" class="text-indigo-400 hover:text-indigo-300 text-decoration-none font-semibold d-flex align-items-center transition-colors duration-300">
                Tout voir <i class="fas fa-arrow-right ms-2 small"></i>
            </a>
        </div>
        
        @if($events->count() > 0)
            <div class="row g-4">
                @foreach($events as $event)
                    <div class="col-lg-4 col-md-6">
                        <div class="glass-card rounded-2xl overflow-hidden hover-up h-100 flex flex-col justify-between">
                            <div class="relative overflow-hidden group" style="height: 220px;">
                                <img src="{{ $event->photo_url }}" alt="{{ $event->titre }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                
                                <!-- Category Badge -->
                                <span class="absolute top-3 right-3 px-3 py-1.5 rounded-xl text-xs font-semibold text-white bg-black/60 backdrop-blur-md border border-white/10">
                                    {{ $event->categorie ?? 'Événement' }}
                                </span>
                                
                                <!-- Status Badge -->
                                @if($event->is_upcoming)
                                    <span class="absolute top-3 left-3 px-3 py-1.5 rounded-xl text-xs font-semibold text-white bg-emerald-500/80 border border-emerald-400/20 backdrop-blur-md">
                                        <i class="fas fa-circle me-1 animate-pulse text-xs"></i> À venir
                                    </span>
                                @else
                                    <span class="absolute top-3 left-3 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-300 bg-white/10 border border-white/5 backdrop-blur-md">
                                        Passé
                                    </span>
                                @endif
                            </div>
                            
                            <div class="p-4 flex-grow-1 flex flex-col justify-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="fw-bold text-white leading-snug tracking-wide h6">{{ $event->titre }}</h5>
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-white/5 text-gray-300">
                                            {{ $event->min_price > 0 ? 'Payant' : 'Gratuit' }}
                                        </span>
                                    </div>
                                    <p class="text-gray-400 text-sm mb-4 line-clamp-3 leading-relaxed">{{ $event->truncated_description }}</p>
                                </div>
                                
                                <div class="border-t border-white/5 pt-3">
                                    <div class="d-flex align-items-center text-gray-400 text-sm mb-2">
                                        <i class="fas fa-calendar-day me-2 text-indigo-500"></i>
                                        <span>{{ $event->formatted_date }}</span>
                                    </div>
                                    <div class="d-flex align-items-center text-gray-400 text-sm mb-2">
                                        <i class="fas fa-clock me-2 text-indigo-500"></i>
                                        <span>{{ $event->formatted_start_time }} - {{ $event->formatted_end_time }}</span>
                                    </div>
                                    <div class="d-flex align-items-center text-gray-400 text-sm mb-3">
                                        <i class="fas fa-map-marker-alt me-2 text-indigo-500"></i>
                                        <span class="text-truncate">{{ $event->lieu }}</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-t border-white/5">
                                        <div>
                                            @if($event->min_price > 0)
                                                <span class="text-xs text-gray-500 block">Ticket à partir de</span>
                                                <span class="fw-bold text-indigo-400">{{ number_format($event->min_price, 0, ',', ' ') }} <span class="text-xs">FCFA</span></span>
                                            @else
                                                <span class="fw-bold text-emerald-400">Accès gratuit</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('p.detail', $event->id) }}" class="btn px-3 py-2 rounded-xl text-white font-medium hover:scale-105 transition-all duration-300 border-0" style="background: #6366f1;">
                                            @if($event->is_upcoming)
                                                Réserver
                                            @else
                                                Détails
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 glass-card rounded-2xl">
                <i class="fas fa-calendar-times fa-3x text-gray-600 mb-3"></i>
                <h4 class="text-gray-400">Aucun événement trouvé</h4>
                <p class="text-gray-500 small">Revenez plus tard pour voir les nouveaux événements !</p>
            </div>
        @endif
    </section>

    <!-- Événement en vedette -->
    @if(isset($featuredEvent) && $featuredEvent)
    <section class="mb-12">
        <h3 class="fw-bold mb-4 tracking-wide text-indigo-300 fs-5 uppercase"><i class="fas fa-star me-2"></i>À ne pas manquer</h3>
        <div class="glass-card rounded-3xl overflow-hidden p-6 md:p-8 lg:p-10 relative">
            
            <div class="row align-items-center relative z-10 g-4">
                <div class="col-lg-6">
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 mb-3 d-inline-block">
                        <i class="fas fa-fire me-1"></i> Événement en vedette
                    </span>
                    <h3 class="display-6 fw-extrabold text-white mb-3">{{ $featuredEvent->titre }}</h3>
                    <p class="text-gray-400 mb-4 leading-relaxed line-clamp-4">{{ $featuredEvent->truncated_description }}</p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center text-gray-300 text-sm">
                                <i class="fas fa-calendar-day me-2 text-indigo-500 fs-5"></i>
                                <span>{{ \Carbon\Carbon::parse($featuredEvent->date)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center text-gray-300 text-sm">
                                <i class="fas fa-clock me-2 text-indigo-500 fs-5"></i>
                                <span>{{ \Carbon\Carbon::parse($featuredEvent->start_heure)->format('H:i') }} - {{ \Carbon\Carbon::parse($featuredEvent->end_heure)->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center text-gray-300 text-sm">
                                <i class="fas fa-map-marker-alt me-2 text-indigo-500 fs-5"></i>
                                <span>{{ $featuredEvent->lieu }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('p.detail', $featuredEvent->id) }}" class="btn btn-lg px-4 py-2.5 rounded-xl text-white font-semibold hover:scale-105 transition-all duration-300 border-0" style="background: #6366f1;">
                        Voir les billets
                    </a>
                </div>
                
                <div class="col-lg-6">
                    <div class="relative rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        <img src="{{ $featuredEvent->photo_url }}" alt="{{ $featuredEvent->titre }}" class="w-full object-cover" style="max-height: 380px;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Newsletter -->
    <section class="glass-card rounded-3xl p-6 md:p-8 lg:p-10 text-center relative overflow-hidden">
        
        <h3 class="fw-bold text-white mb-2 fs-3">Ne manquez aucun événement</h3>
        <p class="text-gray-400 mb-4 max-w-lg mx-auto small">Inscrivez-vous à notre lettre d'information pour recevoir des invitations exclusives et les derniers événements de votre région.</p>
        
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <form onsubmit="event.preventDefault(); alert('Merci pour votre inscription !');">
                    <div class="input-group p-1.5 rounded-2xl border border-white/10 bg-black/20 focus-within:border-indigo-500 transition-all duration-300">
                        <input type="email" class="form-control bg-transparent border-0 text-white shadow-none placeholder:text-gray-600 py-2.5" placeholder="Votre adresse email" required>
                        <button class="btn px-4 rounded-xl text-white font-semibold hover:scale-105 transition-all duration-300 border-0" type="submit" style="background: #6366f1;">S'abonner</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection