@extends('layouts.base')

@section('title', '| Foire Aux Questions')

@section('content')
<main class="container py-5 text-white animate__animated animate__fadeIn">
    <!-- Header Banner -->
    <section class="glass-card rounded-3xl p-6 md:p-8 lg:p-10 mb-12 relative overflow-hidden text-center border border-white/10">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-indigo-500/10 rounded-full filter blur-[80px] pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-purple-500/10 rounded-full filter blur-[80px] pointer-events-none"></div>
        
        <div class="relative z-10 max-w-2xl mx-auto py-4">
            <span class="w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white fs-3 mx-auto mb-4 shadow-lg">
                <i class="fas fa-question-circle"></i>
            </span>
            <h1 class="fw-extrabold text-white mb-2 fs-2 leading-tight">Foire Aux Questions</h1>
            <p class="text-indigo-200 fs-5 mb-4 font-medium">Tout ce que vous devez savoir sur TGEvent</p>
            <p class="text-gray-400 small leading-relaxed">Trouvez des réponses rapides à vos questions sur l'achat de billets, la création de vos propres événements en tant qu'organisateur, ou les méthodes de paiement sécurisées.</p>
        </div>
    </section>

    <!-- FAQ Accordions -->
    <section class="max-w-3xl mx-auto mb-12">
        <!-- Section: General -->
        <div class="glass-card rounded-3xl p-4 mb-4 border border-white/10">
            <h3 class="fw-bold text-white mb-4 fs-5 border-b border-white/5 pb-2 text-indigo-400">
                <i class="fas fa-info-circle me-2"></i>Questions Générales
            </h3>
            
            <div class="d-flex flex-col gap-3">
                <div class="border-b border-white/5 pb-3">
                    <button class="w-full d-flex justify-content-between align-items-center bg-transparent border-0 text-white text-start fw-bold fs-6 py-2 faq-toggle focus:outline-none">
                        <span>Qu'est-ce que TGEvent ?</span>
                        <i class="fas fa-plus text-xs text-indigo-400 transition-transform duration-300 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-gray-400 small leading-relaxed mt-2 transition-all duration-300">
                        TGEvent est une plateforme en ligne haut de gamme permettant de découvrir et de réserver des billets pour des événements de tous genres (concerts, festivals, compétitions sportives, séminaires professionnels, etc.). C'est également un outil puissant pour les organisateurs qui souhaitent créer, gérer, et promouvoir leurs événements.
                    </div>
                </div>

                <div class="border-b border-white/5 pb-3">
                    <button class="w-full d-flex justify-content-between align-items-center bg-transparent border-0 text-white text-start fw-bold fs-6 py-2 faq-toggle focus:outline-none">
                        <span>Comment puis-je créer un compte ?</span>
                        <i class="fas fa-plus text-xs text-indigo-400 transition-transform duration-300 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-gray-400 small leading-relaxed mt-2 transition-all duration-300">
                        Pour créer un compte, cliquez sur le bouton "Connexion" dans le menu principal, puis sélectionnez "S'inscrire". Remplissez les champs requis (nom, prénom, e-mail, téléphone et mot de passe) et validez le formulaire. Votre compte sera instantanément disponible.
                    </div>
                </div>

                <div class="pb-2">
                    <button class="w-full d-flex justify-content-between align-items-center bg-transparent border-0 text-white text-start fw-bold fs-6 py-2 faq-toggle focus:outline-none">
                        <span>Est-ce que je peux utiliser la plateforme sans créer de compte ?</span>
                        <i class="fas fa-plus text-xs text-indigo-400 transition-transform duration-300 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-gray-400 small leading-relaxed mt-2 transition-all duration-300">
                        Vous pouvez parcourir les événements librement et consulter leurs détails sans compte. Cependant, pour acheter un billet ou réserver une place pour un événement gratuit, vous devez être connecté afin de garantir la sécurité de votre billet électronique.
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Tickets -->
        <div class="glass-card rounded-3xl p-4 mb-4 border border-white/10">
            <h3 class="fw-bold text-white mb-4 fs-5 border-b border-white/5 pb-2 text-indigo-400">
                <i class="fas fa-ticket-alt me-2"></i>Billetterie & Inscriptions
            </h3>
            
            <div class="d-flex flex-col gap-3">
                <div class="border-b border-white/5 pb-3">
                    <button class="w-full d-flex justify-content-between align-items-center bg-transparent border-0 text-white text-start fw-bold fs-6 py-2 faq-toggle focus:outline-none">
                        <span>Comment acheter des billets pour un événement ?</span>
                        <i class="fas fa-plus text-xs text-indigo-400 transition-transform duration-300 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-gray-400 small leading-relaxed mt-2 transition-all duration-300">
                        Accédez aux détails de l'événement de votre choix. Dans la section "Billetterie" à droite, cliquez sur "Réserver mon billet". Vous serez redirigé vers le formulaire de facturation pour choisir votre mode de paiement (TMoney, Flooz ou Carte bancaire) et valider votre transaction.
                    </div>
                </div>

                <div class="border-b border-white/5 pb-3">
                    <button class="w-full d-flex justify-content-between align-items-center bg-transparent border-0 text-white text-start fw-bold fs-6 py-2 faq-toggle focus:outline-none">
                        <span>Où puis-je retrouver mon billet après l'achat ?</span>
                        <i class="fas fa-plus text-xs text-indigo-400 transition-transform duration-300 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-gray-400 small leading-relaxed mt-2 transition-all duration-300">
                        Une fois le paiement validé, votre billet virtuel s'affiche directement à l'écran sous la forme d'un pass style Apple Wallet avec un QR Code. Vous pouvez également le retrouver et le télécharger à tout moment dans votre compte utilisateur dans la section "Mes réservations".
                    </div>
                </div>

                <div class="pb-2">
                    <button class="w-full d-flex justify-content-between align-items-center bg-transparent border-0 text-white text-start fw-bold fs-6 py-2 faq-toggle focus:outline-none">
                        <span>Puis-je organiser mon propre événement ?</span>
                        <i class="fas fa-plus text-xs text-indigo-400 transition-transform duration-300 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-gray-400 small leading-relaxed mt-2 transition-all duration-300">
                        Absolument ! TGEvent est conçu pour tous. Créez simplement un compte organisateur pour débloquer votre tableau de bord exclusif, d'où vous pourrez configurer vos événements, éditer des catégories de billets (VIP, Standard), et suivre vos ventes en temps réel.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Support Help Block -->
    <section class="glass-card rounded-3xl p-6 md:p-8 text-center relative overflow-hidden max-w-3xl mx-auto border border-white/10">
        <h3 class="fw-bold text-white mb-2 fs-4">Vous n'avez pas trouvé la réponse à votre question ?</h3>
        <p class="text-gray-400 mb-4 small max-w-lg mx-auto">Notre équipe d'assistance technique est disponible du lundi au vendredi pour répondre à toutes vos interrogations.</p>
        <a href="{{ route('p.contact') }}" class="btn text-white font-semibold rounded-xl px-4 py-2.5 hover:scale-105 transition-all duration-300 border-0" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); text-decoration: none;">
            Contactez le support <i class="fas fa-headset ms-2"></i>
        </a>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.faq-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('.toggle-icon');
            
            // Toggle expansion
            if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                content.style.maxHeight = '0px';
                icon.style.transform = 'rotate(0deg)';
                icon.className = 'fas fa-plus text-xs text-indigo-400 transition-transform duration-300 toggle-icon';
            } else {
                // Close other opened FAQs first
                document.querySelectorAll('.faq-content').forEach(c => {
                    c.style.maxHeight = '0px';
                    const otherIcon = c.previousElementSibling.querySelector('.toggle-icon');
                    if (otherIcon) {
                        otherIcon.style.transform = 'rotate(0deg)';
                        otherIcon.className = 'fas fa-plus text-xs text-indigo-400 transition-transform duration-300 toggle-icon';
                    }
                });
                
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.style.transform = 'rotate(135deg)';
                icon.className = 'fas fa-times text-xs text-indigo-400 transition-transform duration-300 toggle-icon';
            }
        });
    });
});
</script>
@endsection
