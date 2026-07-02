@extends('layouts.base')

@section('title', '| À propos')

@section('content')
<style>
    body {
        background-color: #fafbfc !important;
    }
</style>

{{-- ═══════════════════════════════════════════════
     HERO BANNER
     ═══════════════════════════════════════════════ --}}
<div style="position:relative; width:100%; height:460px; overflow:hidden;">
    <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1200&q=80"
         alt="About Banner"
         style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; object-position:center;">
    
    {{-- Dark linear gradient overlay --}}
    <div style="position:absolute; inset:0; background:linear-gradient(180deg, rgba(15,23,42,0.6) 0%, rgba(15,23,42,0.8) 100%);"></div>

    {{-- Content --}}
    <div style="position:absolute; inset:0; display:flex; flex-direction:column; justify-content:center; align-items:center; padding:2rem; text-align:center;">
        <div style="max-width:800px;">
            <span style="display:inline-block; padding:.35rem 1rem; border-radius:999px; font-size:.72rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#ffffff; background:#d9383a; margin-bottom:1.2rem;">
                NOTRE MISSION
            </span>
            <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem, 5vw, 3rem); font-family: 'Outfit', sans-serif; line-height: 1.2;">
                Réinventer l'expérience des événements en direct.
            </h1>
            <p class="text-slate-300 font-medium text-sm md:text-base mb-4 leading-relaxed max-w-2xl mx-auto">
                Nous jetons un pont entre l'imagination et la réalité, en fournissant les outils nécessaires à une découverte fluide et à une logistique de qualité.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn text-white font-bold rounded-xl px-4 py-2.5 transition-colors border-0" style="background:#d9383a; hover:background:#c22e30;">
                    Rejoindre le mouvement
                </a>
                <a href="#story" class="btn text-white font-bold rounded-xl px-4 py-2.5 border border-white/30 bg-transparent hover:bg-white/10 transition-colors">
                    Découvrir notre vision
                </a>
            </div>
        </div>
    </div>
</div>

<main class="container py-5 text-slate-800">

    {{-- ═══════════════════════════════════════════════
         STATS ROW
         ═══════════════════════════════════════════════ --}}
    <section class="row g-4 mb-5 text-center mt-[-4rem] position-relative z-10">
        <div class="col-md-4">
            <div class="card border border-slate-100 shadow-sm rounded-2xl p-4 bg-white">
                <div class="fs-2 mb-2">🎫</div>
                <h3 class="fw-bold text-slate-900 mb-1" style="font-family: 'Outfit', sans-serif;">1M+</h3>
                <p class="text-slate-400 text-xs font-semibold mb-0">Tickets vendus</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border border-slate-100 shadow-sm rounded-2xl p-4 bg-white">
                <div class="fs-2 mb-2">👥</div>
                <h3 class="fw-bold text-slate-900 mb-1" style="font-family: 'Outfit', sans-serif;">500+</h3>
                <p class="text-slate-400 text-xs font-semibold mb-0">Organisateurs actifs</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border border-slate-100 shadow-sm rounded-2xl p-4 bg-white">
                <div class="fs-2 mb-2">📅</div>
                <h3 class="fw-bold text-slate-900 mb-1" style="font-family: 'Outfit', sans-serif;">10k+</h3>
                <p class="text-slate-400 text-xs font-semibold mb-0">Événements gérés</p>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         OUR STORY & VISION
         ═══════════════════════════════════════════════ --}}
    <section id="story" class="row g-5 align-items-center mb-5 py-3">
        <div class="col-lg-6">
            <div class="rounded-3xl overflow-hidden shadow-md">
                <img src="https://images.unsplash.com/photo-1531535934200-873499974982?auto=format&fit=crop&w=600&q=80" 
                     alt="Our Vision Team" 
                     class="w-100 object-cover" 
                     style="height: 380px;">
            </div>
        </div>
        <div class="col-lg-6">
            <h2 class="fw-bold text-slate-900 mb-4" style="font-family: 'Outfit', sans-serif;">Notre Histoire & Vision</h2>
            <p class="text-slate-600 text-sm leading-relaxed mb-3">
                Fondée à l'intersection de la technologie et de la culture, TGEvent est née d'un constat simple : la magie des événements en direct était étouffée par des systèmes obsolètes. Nous avons entrepris de construire une plateforme qui honore la passion des créateurs tout en offrant la fiabilité que les participants méritent.
            </p>
            <p class="text-slate-600 text-sm leading-relaxed mb-4">
                Notre vision est celle d'un monde où chaque connexion établie lors d'un événement en direct est amplifiée par une technologie fluide. Nous ne faisons pas que vendre des billets ; nous concevons l'infrastructure des relations humaines.
            </p>
            
            <div class="d-flex align-items-center gap-3 border-t border-slate-100 pt-4">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=100&h=100&q=80" 
                     alt="Marcus Kweo" 
                     class="rounded-circle shadow-inner" 
                     style="width:48px; height:48px;">
                <div>
                    <h6 class="fw-bold text-slate-800 mb-0">Marcus Kweo</h6>
                    <small class="text-slate-400">CEO & Fondateur, TGEvent</small>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         OUR CORE VALUES
         ═══════════════════════════════════════════════ --}}
    <section class="text-center mb-5 py-3">
        <h2 class="fw-bold text-slate-900 mb-2" style="font-family: 'Outfit', sans-serif;">Nos valeurs fondamentales</h2>
        <p class="text-slate-500 font-medium text-sm mb-5 max-w-lg mx-auto">
            Les principes qui guident chaque fonctionnalité que nous développons et chaque partenariat que nous tissons.
        </p>

        <div class="row g-4 text-start">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-2xl p-4 h-100 bg-white">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 d-flex justify-content-center align-items-center fs-5 mb-4 shadow-sm" style="width: 40px; height: 40px;">
                        <i class="far fa-lightbulb"></i>
                    </div>
                    <h5 class="fw-bold text-slate-800 mb-2" style="font-family: 'Outfit', sans-serif;">Innovation</h5>
                    <p class="text-slate-500 text-xs leading-relaxed mb-0">
                        Nous ne suivons pas les tendances, nous les créons. Notre équipe d'ingénieurs repousse constamment les limites du possible en matière de billetterie en temps réel et de gestion des accès.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-2xl p-4 h-100 bg-white">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-[#d9383a] d-flex justify-content-center align-items-center fs-5 mb-4 shadow-sm" style="width: 40px; height: 40px;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="fw-bold text-slate-800 mb-2" style="font-family: 'Outfit', sans-serif;">Sécurité</h5>
                    <p class="text-slate-500 text-xs leading-relaxed mb-0">
                        La confiance est notre monnaie d'échange. Nous utilisons un chiffrement de niveau bancaire et des mesures de prévention des fraudes pour garantir la protection absolue des données de chaque participant.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-2xl p-4 h-100 bg-white">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 d-flex justify-content-center align-items-center fs-5 mb-4 shadow-sm" style="width: 40px; height: 40px;">
                        <i class="far fa-heart"></i>
                    </div>
                    <h5 class="fw-bold text-slate-800 mb-2" style="font-family: 'Outfit', sans-serif;">Passion</h5>
                    <p class="text-slate-500 text-xs leading-relaxed mb-0">
                        Nous sommes des fans, des festivaliers et des amateurs de conférences. Nous construisons TGEvent parce que nous croyons profondément au pouvoir des expériences partagées.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         CTA BANNER
         ═══════════════════════════════════════════════ --}}
    <section class="max-w-4xl mx-auto my-5">
        <div class="rounded-3xl p-5 text-center text-white relative overflow-hidden" style="background: linear-gradient(135deg, #131a38 0%, #1e295d 100%);">
            <h2 class="fw-bold mb-2 text-white" style="font-family: 'Outfit', sans-serif;">Prêt à transformer votre prochain événement ?</h2>
            <p class="text-slate-300 small max-w-xl mx-auto mb-4">
                Rejoignez des centaines d'organisateurs qui font confiance à TGEvent pour propulser leurs expériences les plus ambitieuses.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn text-white font-bold rounded-xl px-4 py-2.5 transition-colors border-0" style="background:#d9383a; hover:background:#c22e30;">
                    Commencer gratuitement
                </a>
                <a href="{{ route('p.contact') }}" class="btn text-white font-bold rounded-xl px-4 py-2.5 border border-white/30 bg-transparent hover:bg-white/10 transition-colors">
                    Contacter l'équipe
                </a>
            </div>
        </div>
    </section>

</main>
@endsection
