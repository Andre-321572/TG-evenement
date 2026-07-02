@extends('layouts.base')

@section('title', '| Foire Aux Questions')

@section('content')
<style>
    body {
        background-color: #fafbfc !important;
    }
    .faq-toggle:focus {
        outline: none !important;
    }
    .category-line {
        position: relative;
        padding-left: 1.25rem;
    }
    .category-line::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.25rem;
        bottom: 0.25rem;
        width: 4px;
        border-radius: 4px;
    }
    .line-red::before { background-color: #d9383a; }
    .line-blue::before { background-color: #4f46e5; }
    .line-grey::before { background-color: #64748b; }
    .faq-toggle-btn {
        transition: background-color 0.2s;
    }
    .faq-toggle-btn:hover {
        background-color: #f8fafc;
    }
</style>

<main class="container py-5 text-slate-800 animate__animated animate__fadeIn">
    
    <!-- Header Block -->
    <div class="text-center max-w-2xl mx-auto mb-5">
        <h1 class="fw-bold text-slate-900 fs-1 mb-2" style="font-family: 'Outfit', sans-serif;">Comment pouvons-nous vous aider ?</h1>
        <p class="text-slate-500 font-medium text-sm mb-4">
            Trouvez les réponses aux questions fréquemment posées sur les billets, l'organisation d'événements et la sécurité.
        </p>
        
        <!-- Search bar -->
        <div class="position-relative max-w-lg mx-auto shadow-sm rounded-xl overflow-hidden border border-slate-200">
            <i class="fas fa-search position-absolute start-0 top-50 translate-middle-y text-slate-400 ms-3"></i>
            <input type="text" id="faq-search" placeholder="Rechercher une question (ex: 'remboursement', 'code QR')..."
                   class="form-control rounded-xl py-2.5 ps-5 pe-3 text-sm border-0 bg-white text-slate-800 placeholder-slate-400 font-medium focus:ring-0">
        </div>
    </div>

    <!-- FAQ Categories Grid -->
    <section class="max-w-3xl mx-auto my-5">
        
        <!-- 1. Participants -->
        <div class="faq-category-section mb-5">
            <h5 class="category-line line-red fw-bold text-slate-900 mb-3 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                <i class="far fa-user text-[#d9383a]"></i> Participants
            </h5>
            
            <div class="d-flex flex-column gap-3">
                <!-- Q1 -->
                <div class="faq-item card border border-slate-100 shadow-xs rounded-2xl overflow-hidden bg-white">
                    <button class="w-100 d-flex justify-content-between align-items-center bg-transparent border-0 text-slate-800 text-start fw-bold text-sm p-3.5 faq-toggle-btn focus:outline-none" onclick="toggleFaq(this)">
                        <span>Comment acheter un billet ?</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-slate-500 text-xs px-3.5 transition-all duration-300" style="max-height: 0px;">
                        <p class="pb-3.5 mb-0">Accédez aux détails de l'événement de votre choix. Dans la section « Sélection de billets » à droite, choisissez votre billet, ajustez la quantité et cliquez sur « Acheter maintenant ». Vous serez redirigé vers notre formulaire de paiement Stripe sécurisé.</p>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="faq-item card border border-slate-100 shadow-xs rounded-2xl overflow-hidden bg-white">
                    <button class="w-100 d-flex justify-content-between align-items-center bg-transparent border-0 text-slate-800 text-start fw-bold text-sm p-3.5 faq-toggle-btn focus:outline-none" onclick="toggleFaq(this)">
                        <span>Où puis-je accéder à mon code QR ?</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-slate-500 text-xs px-3.5 transition-all duration-300" style="max-height: 0px;">
                        <p class="pb-3.5 mb-0">Une fois le paiement validé, votre billet virtuel avec un QR code s'affiche immédiatement. Vous recevrez également un e-mail de confirmation et vous pourrez le retrouver à tout moment dans votre espace personnel, dans l'onglet « Mes Réservations ».</p>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="faq-item card border border-slate-100 shadow-xs rounded-2xl overflow-hidden bg-white">
                    <button class="w-100 d-flex justify-content-between align-items-center bg-transparent border-0 text-slate-800 text-start fw-bold text-sm p-3.5 faq-toggle-btn focus:outline-none" onclick="toggleFaq(this)">
                        <span>Puis-je obtenir un remboursement ?</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-slate-500 text-xs px-3.5 transition-all duration-300" style="max-height: 0px;">
                        <p class="pb-3.5 mb-0">Les remboursements dépendent de la politique d'annulation définie par l'organisateur de l'événement. Veuillez le contacter directement depuis les coordonnées présentes sur la page de détails de l'événement pour formuler votre demande.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Organizers -->
        <div class="faq-category-section mb-5">
            <h5 class="category-line line-blue fw-bold text-slate-900 mb-3 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                <i class="fas fa-briefcase text-indigo-600"></i> Organisateurs
            </h5>
            
            <div class="d-flex flex-column gap-3">
                <!-- Q1 -->
                <div class="faq-item card border border-slate-100 shadow-xs rounded-2xl overflow-hidden bg-white">
                    <button class="w-100 d-flex justify-content-between align-items-center bg-transparent border-0 text-slate-800 text-start fw-bold text-sm p-3.5 faq-toggle-btn focus:outline-none" onclick="toggleFaq(this)">
                        <span>Comment créer un événement ?</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-slate-500 text-xs px-3.5 transition-all duration-300" style="max-height: 0px;">
                        <p class="pb-3.5 mb-0">Connectez-vous à votre compte et rendez-vous dans votre Espace Organisateur. Cliquez sur « Créer un événement », puis renseignez le titre, la date, le lieu, la description, les catégories de billets et téléchargez l'affiche de l'événement.</p>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="faq-item card border border-slate-100 shadow-xs rounded-2xl overflow-hidden bg-white">
                    <button class="w-100 d-flex justify-content-between align-items-center bg-transparent border-0 text-slate-800 text-start fw-bold text-sm p-3.5 faq-toggle-btn focus:outline-none" onclick="toggleFaq(this)">
                        <span>Quels sont les frais de la plateforme ?</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-slate-500 text-xs px-3.5 transition-all duration-300" style="max-height: 0px;">
                        <p class="pb-3.5 mb-0">L'inscription et la publication d'événements sont totalement gratuites. Une commission minime est prélevée uniquement sur les ventes de billets payants afin de couvrir les frais de transaction Stripe et le support client.</p>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="faq-item card border border-slate-100 shadow-xs rounded-2xl overflow-hidden bg-white">
                    <button class="w-100 d-flex justify-content-between align-items-center bg-transparent border-0 text-slate-800 text-start fw-bold text-sm p-3.5 faq-toggle-btn focus:outline-none" onclick="toggleFaq(this)">
                        <span>Quel est le calendrier des paiements ?</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-slate-500 text-xs px-3.5 transition-all duration-300" style="max-height: 0px;">
                        <p class="pb-3.5 mb-0">Les fonds collectés lors des ventes de billets sont transférés directement sur votre compte bancaire ou compte Mobile Money à la fin de l'événement ou selon un calendrier périodique convenu avec notre équipe financière.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Security -->
        <div class="faq-category-section mb-5">
            <h5 class="category-line line-grey fw-bold text-slate-900 mb-3 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                <i class="fas fa-shield-alt text-slate-500"></i> Sécurité & Compte
            </h5>
            
            <div class="d-flex flex-column gap-3">
                <!-- Q1 -->
                <div class="faq-item card border border-slate-100 shadow-xs rounded-2xl overflow-hidden bg-white">
                    <button class="w-100 d-flex justify-content-between align-items-center bg-transparent border-0 text-slate-800 text-start fw-bold text-sm p-3.5 faq-toggle-btn focus:outline-none" onclick="toggleFaq(this)">
                        <span>Comment réinitialiser mon mot de passe ?</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-slate-500 text-xs px-3.5 transition-all duration-300" style="max-height: 0px;">
                        <p class="pb-3.5 mb-0">Cliquez sur « Se connecter », puis sur le lien « Mot de passe oublié ? » au-dessus du champ. Renseignez votre adresse e-mail et nous vous enverrons instantanément un lien sécurisé pour choisir un nouveau mot de passe.</p>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="faq-item card border border-slate-100 shadow-xs rounded-2xl overflow-hidden bg-white">
                    <button class="w-100 d-flex justify-content-between align-items-center bg-transparent border-0 text-slate-800 text-start fw-bold text-sm p-3.5 faq-toggle-btn focus:outline-none" onclick="toggleFaq(this)">
                        <span>Comment mes données sont-elles protégées ?</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200 toggle-icon"></i>
                    </button>
                    <div class="faq-content overflow-hidden max-h-0 text-slate-500 text-xs px-3.5 transition-all duration-300" style="max-height: 0px;">
                        <p class="pb-3.5 mb-0">Toutes vos données de compte et de transactions sont cryptées et hébergées sur des serveurs hautement sécurisés. Les paiements par carte bancaire sont traités directement par Stripe avec chiffrement de bout en bout conforme PCI-DSS.</p>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Still have questions Card -->
    <section class="max-w-3xl mx-auto my-5">
        <div class="rounded-3xl p-5 text-center text-white relative overflow-hidden" style="background: linear-gradient(135deg, #131a38 0%, #1e295d 100%);">
            <h4 class="fw-bold mb-2 text-white" style="font-family: 'Outfit', sans-serif;">Vous avez encore des questions ?</h4>
            <p class="text-slate-300 small max-w-lg mx-auto mb-4">Notre équipe de support est à votre disposition pour s'assurer que votre expérience avec TGEvent reste parfaite.</p>
            <a href="{{ route('p.contact') }}" class="btn text-white font-bold rounded-xl px-4 py-2.5 transition-colors border-0" style="background: #d9383a; hover: background: #c22e30;">
                Contacter le support
            </a>
        </div>
    </section>

</main>

<script>
function toggleFaq(btnEl) {
    const content = btnEl.nextElementSibling;
    const icon = btnEl.querySelector('.toggle-icon');
    
    if (content.style.maxHeight && content.style.maxHeight !== '0px') {
        content.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
    } else {
        // Close other open ones in the same category or overall
        document.querySelectorAll('.faq-content').forEach(c => {
            c.style.maxHeight = '0px';
            const otherIcon = c.previousElementSibling.querySelector('.toggle-icon');
            if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
        });
        
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    }
}

// Live search filtering
document.getElementById('faq-search').addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase().trim();
    
    document.querySelectorAll('.faq-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(query)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });

    // Hide or show categories based on visible children
    document.querySelectorAll('.faq-category-section').forEach(sect => {
        const hasVisibleItem = Array.from(sect.querySelectorAll('.faq-item')).some(item => item.style.display !== 'none');
        if (hasVisibleItem) {
            sect.style.display = 'block';
        } else {
            sect.style.display = 'none';
        }
    });
});
</script>
@endsection
