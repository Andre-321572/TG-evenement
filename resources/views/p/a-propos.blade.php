@extends('layouts.base')

@section('title', '| À propos')

@section('content')
<main class="container py-5 text-white animate__animated animate__fadeIn">
    <!-- Header Banner -->
    <section class="glass-card rounded-3xl p-6 md:p-8 lg:p-10 mb-12 relative overflow-hidden text-center border border-white/10">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-indigo-500/10 rounded-full filter blur-[80px] pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-purple-500/10 rounded-full filter blur-[80px] pointer-events-none"></div>
        
        <div class="relative z-10 max-w-2xl mx-auto py-4">
            <span class="w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white fs-3 mx-auto mb-4 shadow-lg">
                <i class="fas fa-users"></i>
            </span>
            <h1 class="fw-extrabold text-white mb-2 fs-2 leading-tight">À propos de TGEvent</h1>
            <p class="text-indigo-200 fs-5 mb-4 font-medium">Découvrez notre histoire, notre vision et notre équipe</p>
        </div>
    </section>

    <!-- History Grid -->
    <section class="mb-12">
        <div class="glass-card rounded-3xl p-6 md:p-8 border border-white/10 relative overflow-hidden">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h3 class="fw-bold text-white mb-4 fs-4 border-b border-white/5 pb-2 text-indigo-400">Notre histoire</h3>
                    <p class="text-gray-400 small leading-relaxed mb-3">TGEvent est né en 2022 d'une idée simple : concevoir une passerelle interactive et sécurisée entre les organisateurs d'événements culturels et sportifs au Togo et leur public.</p>
                    <p class="text-gray-400 small leading-relaxed mb-3">Après plusieurs mois de conception et d'essais pilotes menés avec des promoteurs d'événements locaux, nous avons officiellement déployé la plateforme. TGEvent permet aujourd'hui de gérer de façon entièrement numérisée la vente de billets et le suivi des transactions.</p>
                    <p class="text-gray-400 small leading-relaxed mb-0">De Lomé à Kara, nous accompagnons les événements qui font vibrer les communautés et rassemblent les passionnés.</p>
                </div>
                <div class="col-lg-5">
                    <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80" alt="L'équipe TGEvent" class="w-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values -->
    <section class="mb-12">
        <h3 class="fw-bold text-white text-center mb-5 fs-4 tracking-wide text-gradient-primary">Notre mission & valeurs</h3>
        <div class="row g-4">
            <!-- Value 1 -->
            <div class="col-md-4">
                <div class="glass-card rounded-2xl p-4 text-center h-100 flex flex-col justify-between border border-white/5 shadow-inner">
                    <div class="w-12 h-12 rounded-full bg-indigo-500/10 text-indigo-400 d-flex justify-content-center align-items-center mx-auto mb-3 fs-5">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4 class="fw-bold text-white fs-6 mb-2">Accessibilité</h4>
                    <p class="text-gray-400 text-xs mb-0 leading-relaxed">Nous mettons en place des outils simples pour permettre à n'importe quel organisateur d'ajouter ses événements et à tout utilisateur de réserver en quelques clics.</p>
                </div>
            </div>
            
            <!-- Value 2 -->
            <div class="col-md-4">
                <div class="glass-card rounded-2xl p-4 text-center h-100 flex flex-col justify-between border border-white/5 shadow-inner">
                    <div class="w-12 h-12 rounded-full bg-indigo-500/10 text-indigo-400 d-flex justify-content-center align-items-center mx-auto mb-3 fs-5">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h4 class="fw-bold text-white fs-6 mb-2">Innovation</h4>
                    <p class="text-gray-400 text-xs mb-0 leading-relaxed">Nous cherchons constamment à améliorer le processus de vente de billets avec des tickets virtuels sécurisés équipés de codes QR uniques et infalsifiables.</p>
                </div>
            </div>
            
            <!-- Value 3 -->
            <div class="col-md-4">
                <div class="glass-card rounded-2xl p-4 text-center h-100 flex flex-col justify-between border border-white/5 shadow-inner">
                    <div class="w-12 h-12 rounded-full bg-indigo-500/10 text-indigo-400 d-flex justify-content-center align-items-center mx-auto mb-3 fs-5">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="fw-bold text-white fs-6 mb-2">Communauté</h4>
                    <p class="text-gray-400 text-xs mb-0 leading-relaxed">Nous favorisons les échanges en développant des espaces collaboratifs de discussion et de chat en direct entre organisateurs et participants.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Grid -->
    <section class="mb-12">
        <div class="glass-card rounded-3xl p-5 text-center border border-white/10 relative overflow-hidden">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <h4 class="display-6 fw-extrabold text-indigo-400 mb-2">10K+</h4>
                    <p class="text-gray-400 small mb-0 font-medium">Utilisateurs actifs</p>
                </div>
                <div class="col-6 col-md-3">
                    <h4 class="display-6 fw-extrabold text-indigo-400 mb-2">500+</h4>
                    <p class="text-gray-400 small mb-0 font-medium">Événements publiés</p>
                </div>
                <div class="col-6 col-md-3">
                    <h4 class="display-6 fw-extrabold text-indigo-400 mb-2">100+</h4>
                    <p class="text-gray-400 small mb-0 font-medium">Organisateurs inscrits</p>
                </div>
                <div class="col-6 col-md-3">
                    <h4 class="display-6 fw-extrabold text-indigo-400 mb-2">99%</h4>
                    <p class="text-gray-400 small mb-0 font-medium">Paiements sécurisés</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Journey -->
    <section class="mb-12 max-w-2xl mx-auto">
        <h3 class="fw-bold text-white text-center mb-5 fs-4 tracking-wide text-gradient-primary">Notre parcours</h3>
        
        <div class="position-relative ps-4" style="border-left: 2px solid rgba(59, 130, 246, 0.3);">
            <!-- Milestone 1 -->
            <div class="mb-4 position-relative">
                <div class="position-absolute rounded-full bg-blue-500 border-4 border-[#0b1329]" style="width: 16px; height: 16px; left: -33px; top: 4px;"></div>
                <span class="text-xs text-blue-400 font-bold block mb-1">Janvier 2022</span>
                <h4 class="fw-bold text-white fs-6 mb-1">Lancement du projet</h4>
                <p class="text-gray-400 text-xs leading-relaxed">Création de la startup TGEvent et début du développement de l'architecture Laravel de l'application.</p>
            </div>

            <!-- Milestone 2 -->
            <div class="mb-4 position-relative">
                <div class="position-absolute rounded-full bg-blue-500 border-4 border-[#0b1329]" style="width: 16px; height: 16px; left: -33px; top: 4px;"></div>
                <span class="text-xs text-blue-400 font-bold block mb-1">Août 2023</span>
                <h4 class="fw-bold text-white fs-6 mb-1">Première version test</h4>
                <p class="text-gray-400 text-xs leading-relaxed">Lancement d'une phase pilote privée avec 10 organisateurs événementiels à Lomé.</p>
            </div>

            <!-- Milestone 3 -->
            <div class="position-relative">
                <div class="position-absolute rounded-full bg-blue-500 border-4 border-[#0b1329]" style="width: 16px; height: 16px; left: -33px; top: 4px;"></div>
                <span class="text-xs text-blue-400 font-bold block mb-1">Janvier 2026</span>
                <h4 class="fw-bold text-white fs-6 mb-1">Déploiement public global</h4>
                <p class="text-gray-400 text-xs leading-relaxed">Ouverture officielle de la plateforme au grand public avec intégration des paiements Mobile Money.</p>
            </div>
        </div>
    </section>

    <!-- Join CTA Block -->
    <section class="glass-card rounded-3xl p-6 md:p-8 text-center border border-white/10 relative overflow-hidden">
        <h3 class="fw-bold text-white mb-2 fs-4">Rejoignez l'aventure TGEvent</h3>
        <p class="text-gray-400 mb-4 small max-w-lg mx-auto">Créez votre compte dès maintenant pour réserver vos billets ou commencer à organiser vos propres rendez-vous.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn text-white font-semibold rounded-xl px-4 py-2.5 hover:scale-105 transition-all duration-300 border-0" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); text-decoration: none;">
                Créer mon compte
            </a>
            <a href="{{ route('p.evenement') }}" class="btn btn-outline-light border-white/10 hover:bg-white/5 rounded-xl px-4 py-2 text-sm text-gray-300 text-decoration-none d-flex align-items-center">
                Voir les événements
            </a>
        </div>
    </section>
</main>
@endsection
