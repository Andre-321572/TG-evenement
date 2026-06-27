@extends('layouts.base')

@section('title', '| Découvrez les meilleurs événements')

@section('content')

@php
    $slides = $events->filter(fn($e) => $e->photo)->take(6)->values();
@endphp

{{-- ======================================================
     HERO — Full bleed, hors container, inline styles only
     (Bypass les overrides light-theme de base.blade.php)
     ====================================================== --}}
<section id="heroSection" style="position:relative; width:100%; height:88vh; min-height:560px; overflow:hidden;">

    {{-- Slides --}}
    @if($slides->count() > 0)
        @foreach($slides as $idx => $slide)
        <div class="hero-slide"
             style="position:absolute; inset:0; background-image:url('{{ $slide->photo_url }}'); background-size:cover; background-position:center; opacity:{{ $idx === 0 ? '1' : '0' }}; transition:opacity 1.3s cubic-bezier(0.4,0,0.2,1);">
        </div>
        @endforeach
    @else
        {{-- Fallback gradient slides quand aucun événement --}}
        <div class="hero-slide" style="position:absolute; inset:0; background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#1e3a5f 100%); opacity:1; transition:opacity 1.3s ease;"></div>
        <div class="hero-slide" style="position:absolute; inset:0; background:linear-gradient(135deg,#0f172a 0%,#1a0533 50%,#4c1d95 100%); opacity:0; transition:opacity 1.3s ease;"></div>
        <div class="hero-slide" style="position:absolute; inset:0; background:linear-gradient(135deg,#0a1628 0%,#0d253f 50%,#1e3a5f 100%); opacity:0; transition:opacity 1.3s ease;"></div>
    @endif

    {{-- Dark overlay --}}
    <div style="position:absolute; inset:0; z-index:10; background:linear-gradient(180deg, rgba(0,0,0,0.52) 0%, rgba(0,0,0,0.22) 40%, rgba(0,0,0,0.70) 100%);"></div>

    {{-- Contenu centré --}}
    <div style="position:relative; z-index:20; height:100%; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; padding:2rem 1.5rem;">
        <div style="max-width:820px; width:100%;">

            <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.28em; text-transform:uppercase; color:rgba(167,139,250,0.9); margin-bottom:1.1rem;">
                TGEvent &mdash; Togo
            </p>

            <h1 style="font-size:clamp(2.1rem,5.5vw,4rem); font-weight:900; line-height:1.13; color:#ffffff; text-shadow:0 2px 28px rgba(0,0,0,0.5); margin-bottom:1.25rem; font-family:'Outfit',sans-serif;">
                Trouvez &amp; vivez des<br>
                <span style="color:#a78bfa;">moments exceptionnels</span>
            </h1>

            <p style="font-size:1.05rem; color:rgba(255,255,255,0.7); line-height:1.75; max-width:560px; margin:0 auto 2.5rem; font-family:'Outfit',sans-serif;">
                Concerts, conférences, compétitions sportives et soirées exclusives — tout près de chez vous.
            </p>

            {{-- Barre de recherche --}}
            <style>
                #heroSearch::placeholder { color: rgba(255,255,255,0.45); }
                #heroSearch:focus { outline: none; }
                #heroSearchBtn:hover { background: #4338ca !important; }
                #heroPrev:hover, #heroNext:hover { background: rgba(255,255,255,0.22) !important; }
            </style>
            <form action="{{ route('p.evenement') }}" method="GET" style="max-width:640px; margin:0 auto;">
                <div style="display:flex; align-items:stretch; border-radius:0.875rem; overflow:hidden; background:rgba(255,255,255,0.1); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1.5px solid rgba(255,255,255,0.2); box-shadow:0 12px 48px rgba(0,0,0,0.35);">
                    <span style="display:flex; align-items:center; padding:0 1rem; color:rgba(255,255,255,0.55); flex-shrink:0;">
                        <i class="fas fa-search" style="font-size:0.9rem;"></i>
                    </span>
                    <input id="heroSearch"
                           type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher par artiste, lieu, mot-clé..."
                           style="flex:1; background:transparent; border:none; padding:1.05rem 0.5rem; color:#ffffff; font-size:0.93rem; font-family:'Outfit',sans-serif; min-width:0;">
                    <button id="heroSearchBtn"
                            type="submit"
                            style="background:#4f46e5; color:#ffffff; border:none; padding:0 1.75rem; font-weight:700; font-size:0.93rem; cursor:pointer; flex-shrink:0; font-family:'Outfit',sans-serif; transition:background 0.2s; white-space:nowrap;">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Boutons précédent / suivant --}}
    <button id="heroPrev"
            style="position:absolute; left:1.25rem; top:50%; transform:translateY(-50%); z-index:30; width:2.75rem; height:2.75rem; border-radius:50%; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.22); color:rgba(255,255,255,0.8); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background 0.2s;"
            onclick="heroSlider.nav(-1)">
        <i class="fas fa-chevron-left" style="font-size:0.78rem;"></i>
    </button>
    <button id="heroNext"
            style="position:absolute; right:1.25rem; top:50%; transform:translateY(-50%); z-index:30; width:2.75rem; height:2.75rem; border-radius:50%; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.22); color:rgba(255,255,255,0.8); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background 0.2s;"
            onclick="heroSlider.nav(1)">
        <i class="fas fa-chevron-right" style="font-size:0.78rem;"></i>
    </button>

    {{-- Indicateurs (dots) --}}
    <div id="heroDots" style="position:absolute; bottom:1.5rem; left:0; right:0; z-index:30; display:flex; justify-content:center; align-items:center; gap:0.5rem;"></div>
</section>

<script>
var heroSlider = (function () {
    var slides = document.querySelectorAll('.hero-slide');
    var dotsWrap = document.getElementById('heroDots');
    var current = 0;
    var timer = null;

    // Construire les dots
    slides.forEach(function (_, i) {
        var btn = document.createElement('button');
        btn.style.cssText = 'height:3px; padding:0; border:none; cursor:pointer; border-radius:999px; transition:all 0.35s;';
        btn.style.background = i === 0 ? 'rgba(255,255,255,0.9)' : 'rgba(255,255,255,0.3)';
        btn.style.width = i === 0 ? '2rem' : '0.55rem';
        btn.addEventListener('click', function () { stop(); goTo(i); start(); });
        dotsWrap.appendChild(btn);
    });

    function dots() { return dotsWrap.querySelectorAll('button'); }

    function goTo(n) {
        var d = dots();
        slides[current].style.opacity = '0';
        d[current].style.width = '0.55rem';
        d[current].style.background = 'rgba(255,255,255,0.3)';

        current = ((n % slides.length) + slides.length) % slides.length;

        slides[current].style.opacity = '1';
        d[current].style.width = '2rem';
        d[current].style.background = 'rgba(255,255,255,0.9)';
    }

    function start() {
        if (slides.length > 1) timer = setInterval(function () { goTo(current + 1); }, 5000);
    }
    function stop() { clearInterval(timer); }

    if (slides.length <= 1) {
        document.getElementById('heroPrev').style.display = 'none';
        document.getElementById('heroNext').style.display = 'none';
    } else {
        start();
    }

    return {
        nav: function (dir) { stop(); goTo(current + dir); start(); }
    };
})();
</script>

{{-- ======================================================
     Reste du contenu — dans le container
     ====================================================== --}}
<main class="container py-10 text-white">

    {{-- Catégories populaires --}}
    <section class="mb-12">
        <h3 class="fw-bold mb-4 tracking-wide text-indigo-600 fs-6 uppercase letter-spacing-2">Catégories populaires</h3>
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

    {{-- Liste des Événements --}}
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
                Tout voir
            </a>
        </div>

        @if($events->count() > 0)
            <div class="row g-4">
                @foreach($events as $event)
                    <div class="col-lg-4 col-md-6">
                        <div class="glass-card rounded-2xl overflow-hidden hover-up h-100 flex flex-col justify-between">
                            <div class="card-thumb group" style="height: 220px;">
                                <img src="{{ $event->photo_url }}" alt="{{ $event->titre }}" style="transition: transform 0.7s ease;">
                                <span class="absolute top-3 right-3 px-3 py-1.5 rounded-xl text-xs font-semibold text-white bg-black/60 backdrop-blur-md border border-white/10">
                                    {{ $event->categorie ?? 'Événement' }}
                                </span>
                                @if($event->is_upcoming)
                                    <span class="absolute top-3 left-3 px-3 py-1.5 rounded-xl text-xs font-semibold text-white bg-emerald-500/80 border border-emerald-400/20 backdrop-blur-md">
                                        À venir
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
                                        <a href="{{ route('p.detail', $event->id) }}" class="btn px-3 py-2 rounded-xl text-white font-medium hover:scale-105 transition-all duration-300 border-0" style="background: #4f46e5;">
                                            @if($event->is_upcoming) Réserver @else Détails @endif
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
                <p class="text-gray-500 fw-semibold mb-1">Aucun événement disponible</p>
                <p class="text-gray-400 small">Revenez bientôt, de nouveaux événements arrivent.</p>
            </div>
        @endif
    </section>

    {{-- Événement en vedette --}}
    @if(isset($featuredEvent) && $featuredEvent)
    <section class="mb-12">
        <h3 class="fw-bold mb-4 tracking-wide text-indigo-600 fs-6 uppercase">À ne pas manquer</h3>
        <div class="glass-card rounded-3xl overflow-hidden p-6 md:p-8 lg:p-10">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-indigo-600 bg-indigo-500/10 border border-indigo-500/20 mb-3 d-inline-block uppercase tracking-widest">
                        En vedette
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

                    <a href="{{ route('p.detail', $featuredEvent->id) }}" class="btn btn-lg px-4 py-2.5 rounded-xl text-white font-semibold hover:scale-105 transition-all duration-300 border-0" style="background: #4f46e5;">
                        Voir les billets
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="card-thumb rounded-2xl border border-white/10 shadow-2xl" style="height: 380px;">
                        <img src="{{ $featuredEvent->photo_url }}" alt="{{ $featuredEvent->titre }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Newsletter --}}
    <section class="glass-card rounded-3xl p-6 md:p-8 lg:p-10 text-center">
        <h3 class="fw-bold text-white mb-2 fs-3">Ne manquez aucun événement</h3>
        <p class="text-gray-400 mb-4 max-w-lg mx-auto small">Inscrivez-vous à notre lettre d'information pour recevoir des invitations exclusives et les derniers événements de votre région.</p>

        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <form onsubmit="event.preventDefault(); alert('Merci pour votre inscription !');">
                    <div class="input-group p-1.5 rounded-2xl border border-white/10 bg-black/20 focus-within:border-indigo-500 transition-all duration-300">
                        <input type="email" class="form-control bg-transparent border-0 text-white shadow-none placeholder:text-gray-600 py-2.5" placeholder="Votre adresse email" required>
                        <button class="btn px-4 rounded-xl text-white font-semibold border-0" type="submit" style="background: #4f46e5;">S'abonner</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</main>
@endsection
