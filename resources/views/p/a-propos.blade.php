@extends('layouts.base')

@section('title', '| À propos')

@section('content')
{{-- ═══════════════════════════════════════════════
     HERO BANNER
     ═══════════════════════════════════════════════ --}}
<div class="relative w-full overflow-hidden py-24 px-6 md:px-12" style="background: linear-gradient(rgba(255, 255, 255, 0.82), rgba(255, 255, 255, 0.93)), url('{{ asset('images/about_hero.png') }}') center/cover no-repeat; border-bottom: 1px solid #e2e8f0;">
    {{-- Decorative grid background --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px] pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">
        <div>
            <span class="font-bold text-xs px-3.5 py-1.5 rounded-full uppercase tracking-wider shadow-sm" style="background-color: #d97706 !important; color: #ffffff !important;">
                Qui sommes-nous ?
            </span>
            <h1 class="text-4xl md:text-5xl font-black mt-4 mb-6 leading-tight font-sans tracking-tight text-slate-900" style="color: #0f172a !important;">
                HIT-TECHNOLOGY <span class="text-amber-500" style="color: #d97706 !important;">SARL U</span>
            </h1>
            <p class="text-lg md:text-xl font-medium mb-6 leading-relaxed text-slate-600" style="color: #475569 !important;">
                TGEvent est une plateforme de billetterie innovante développée par HIT-TECHNOLOGY, cabinet spécialisé dans les services informatiques, les télécoms et la formation professionnelle au Togo et en Afrique.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('p.contact') }}" class="font-semibold px-6 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg" style="background-color: #d97706 !important; color: #ffffff !important; text-decoration: none;">
                    Nous Contacter
                </a>
                <a href="#story" class="border border-slate-300 bg-white/40 hover:bg-white/60 text-slate-700 font-semibold px-6 py-3 rounded-xl transition-all duration-300" style="color: #334155 !important; border-color: #cbd5e1 !important; text-decoration: none;">
                    Découvrir notre histoire
                </a>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl p-6 text-center shadow-md transition-all duration-300 hover:bg-white/90 hover:border-white">
                <p class="text-4xl font-extrabold text-amber-600" style="color: #d97706 !important; margin-bottom: 0;">10+</p>
                <p class="text-xs font-semibold uppercase tracking-wider mt-2 mb-0" style="color: #475569 !important;">Années d'expérience</p>
            </div>
            <div class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl p-6 text-center shadow-md transition-all duration-300 hover:bg-white/90 hover:border-white">
                <p class="text-4xl font-extrabold text-amber-600" style="color: #d97706 !important; margin-bottom: 0;">500+</p>
                <p class="text-xs font-semibold uppercase tracking-wider mt-2 mb-0" style="color: #475569 !important;">Clients satisfaits</p>
            </div>
            <div class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl p-6 text-center shadow-md transition-all duration-300 hover:bg-white/90 hover:border-white">
                <p class="text-4xl font-extrabold text-amber-600" style="color: #d97706 !important; margin-bottom: 0;">50+</p>
                <p class="text-xs font-semibold uppercase tracking-wider mt-2 mb-0" style="color: #475569 !important;">Formations dispensées</p>
            </div>
            <div class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl p-6 text-center shadow-md transition-all duration-300 hover:bg-white/90 hover:border-white">
                <p class="text-4xl font-extrabold text-amber-600" style="color: #d97706 !important; margin-bottom: 0;">20+</p>
                <p class="text-xs font-semibold uppercase tracking-wider mt-2 mb-0" style="color: #475569 !important;">Partenaires actifs</p>
            </div>
        </div>
    </div>
</div>

<main class="max-w-7xl mx-auto px-6 py-16 text-slate-800">

    {{-- ═══════════════════════════════════════════════
         OUR STORY & VALUES
         ═══════════════════════════════════════════════ --}}
    <section id="story" class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-24">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-3 block">HISTOIRE</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 tracking-tight">Notre Histoire</h2>
            <p class="text-slate-600 text-base leading-relaxed mb-4">
                HIT-TECHNOLOGY (HIT-T) SARL U est un cabinet togolais spécialisé dans les technologies de l'information et de la communication, fondé avec la vision de démocratiser l'accès aux services technologiques en Afrique.
            </p>
            <p class="text-slate-600 text-base leading-relaxed mb-4">
                Nous proposons une gamme complète de services allant du développement web et mobile (comme notre plateforme de billetterie événementielle <strong>TGEvent</strong>), à la vente de matériel informatique, en passant par la formation professionnelle et les solutions télécoms.
            </p>
            <p class="text-slate-600 text-base leading-relaxed">
                Notre mission est d'accompagner les entreprises et les particuliers dans leur transformation numérique avec des solutions innovantes, robustes et parfaitement adaptées au contexte africain.
            </p>
        </div>
        
        <div class="bg-white border border-slate-100 shadow-sm rounded-3xl p-8 md:p-10 relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-36 h-36 bg-indigo-50 rounded-full blur-2xl pointer-events-none"></div>
            <h3 class="font-extrabold text-2xl text-slate-900 mb-8 tracking-tight">Nos Valeurs</h3>
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-lightbulb fs-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Innovation</h4>
                        <p class="text-slate-500 text-sm mt-1">Toujours à la pointe des nouvelles technologies pour concevoir des solutions performantes et avant-gardistes.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-50 border border-amber-100 rounded-2xl flex items-center justify-center text-amber-600 flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-handshake fs-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Partenariat</h4>
                        <p class="text-slate-500 text-sm mt-1">Construire des relations de confiance durables avec nos clients, collaborateurs et partenaires.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-star fs-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Excellence</h4>
                        <p class="text-slate-500 text-sm mt-1">Garantir une qualité de service irréprochable et des standards de sécurité de niveau bancaire.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-purple-50 border border-purple-100 rounded-2xl flex items-center justify-center text-purple-600 flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-graduation-cap fs-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Formation</h4>
                        <p class="text-slate-500 text-sm mt-1">Développer et valoriser les compétences locales pour propulser l'Afrique vers l'excellence numérique.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         WHAT WE DO (SERVICES)
         ═══════════════════════════════════════════════ --}}
    <section class="mb-24">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-3 block">EXPERTISES</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Ce que nous faisons</h2>
            <p class="text-slate-500 font-medium text-sm md:text-base mt-2">
                Découvrez le large éventail de solutions technologiques et de services proposés par notre cabinet.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Service 1 --}}
            <div class="bg-white border border-slate-100 hover:border-indigo-100 p-8 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="w-14 h-14 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-laptop-code text-indigo-600 fs-4"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-900 mb-3">Développement Web & Mobile</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Création de sites internet, applications web (comme TGEvent) et applications mobiles sur mesure adaptées à vos besoins.</p>
            </div>
            {{-- Service 2 --}}
            <div class="bg-white border border-slate-100 hover:border-amber-100 p-8 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="w-14 h-14 bg-amber-50 border border-amber-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-amber-600 fs-4"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-900 mb-3">Formation Professionnelle</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Formations professionnelles spécialisées en informatique, réseaux télécoms et gestion de projets technologiques.</p>
            </div>
            {{-- Service 3 --}}
            <div class="bg-white border border-slate-100 hover:border-emerald-100 p-8 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="w-14 h-14 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-tower-broadcast text-emerald-600 fs-4"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-900 mb-3">Solutions Télécoms</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Étude, installation, maintenance et optimisation de réseaux informatiques et systèmes de communication d'entreprise.</p>
            </div>
            {{-- Service 4 --}}
            <div class="bg-white border border-slate-100 hover:border-purple-100 p-8 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="w-14 h-14 bg-purple-50 border border-purple-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-desktop text-purple-600 fs-4"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-900 mb-3">Vente de Matériel</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Fourniture d'équipements informatiques de qualité : ordinateurs, serveurs, périphériques et consommables de grandes marques.</p>
            </div>
            {{-- Service 5 --}}
            <div class="bg-white border border-slate-100 hover:border-red-100 p-8 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="w-14 h-14 bg-red-50 border border-red-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-book-open text-red-600 fs-4"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-900 mb-3">Ebooks & Supports</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Édition de guides et supports pédagogiques numériques de qualité dans le domaine des sciences et technologies.</p>
            </div>
            {{-- Service 6 --}}
            <div class="bg-white border border-slate-100 hover:border-yellow-100 p-8 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="w-14 h-14 bg-yellow-50 border border-yellow-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-link text-yellow-600 fs-4"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-900 mb-3">Blockchain & Crypto</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Conseil stratégique et intégration technique autour de la blockchain, de la tokenisation et des cryptomonnaies.</p>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         CTA SECTION
         ═══════════════════════════════════════════════ --}}
    <section class="max-w-4xl mx-auto mt-12">
        <div class="rounded-3xl p-8 md:p-12 text-center text-white relative overflow-hidden shadow-xl" style="background: linear-gradient(135deg, #131a38 0%, #1e295d 100%);">
            {{-- Decorative glow circles inside CTA --}}
            <div class="absolute -top-12 -left-12 w-24 h-24 bg-amber-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl pointer-events-none"></div>
            
            <h2 class="text-2xl md:text-3xl font-extrabold mb-3 text-white tracking-tight">Prêt à transformer vos idées en réalité ?</h2>
            <p class="text-slate-300 text-sm md:text-base max-w-xl mx-auto mb-6">
                Notre équipe est à votre disposition pour vous accompagner dans tous vos besoins digitaux et de billetterie.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('p.contact') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-md keep-white">
                    Discuter avec un expert
                </a>
                <a href="{{ route('index') }}" class="border border-white/20 hover:border-white/50 hover:bg-white/10 text-white font-bold px-6 py-3 rounded-xl transition-all duration-300">
                    Découvrir nos événements
                </a>
            </div>
        </div>
    </section>

</main>
@endsection
