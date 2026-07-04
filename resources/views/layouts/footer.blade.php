<footer class="sky-footer border-t border-sky-200 py-12 mt-20" style="background-color: #e0f2fe;">
    <div class="container">
        <div class="row g-4">
            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <img src="{{ asset('images/logo.png') }}" alt="TGEvent" style="height: 38px; width: auto; margin-bottom: 1rem;">
                <p class="text-slate-600 leading-relaxed small mb-0" style="max-width: 320px;">
                    La destination numéro un pour vos billets d'événements préférés. Fiable, rapide et sécurisé.
                </p>
                <div class="mt-4">
                    <a href="{{ asset('downloads/tgevent.apk') }}" class="btn d-inline-flex align-items-center gap-2 px-3.5 py-2 shadow-sm border-0 hover:scale-105 transition-all duration-300" style="background: #1e3a8a; color: #ffffff; border-radius: 12px; font-weight: 700; font-size: 0.85rem;" download>
                        <i class="fab fa-android fs-4 text-emerald-400"></i>
                        <div class="text-start text-white">
                            <span style="font-size: 0.62rem; display: block; font-weight: 500; opacity: 0.8; line-height: 1;">Version Mobile Android</span>
                            <span style="line-height: 1.1;">Télécharger l'APK</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Platform Column -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-slate-900 fw-bold mb-3 small uppercase tracking-wider" style="font-size: 0.75rem; letter-spacing: 0.1em; color: #0f172a;">PLATEFORME</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ route('p.a-propos') }}" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 text-sm text-decoration-none">À propos</a></li>
                    <li class="mb-2"><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 text-sm text-decoration-none">Confidentialité</a></li>
                    <li class="mb-2"><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 text-sm text-decoration-none">Conditions</a></li>
                    <li class="mb-2"><a href="{{ route('p.contact') }}" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 text-sm text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Help Column -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-slate-900 fw-bold mb-3 small uppercase tracking-wider" style="font-size: 0.75rem; letter-spacing: 0.1em; color: #0f172a;">AIDE</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 text-sm text-decoration-none">Support technique</a></li>
                    <li class="mb-2"><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 text-sm text-decoration-none">Remboursements</a></li>
                    <li class="mb-2"><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors duration-200 text-sm text-decoration-none">Vendre des billets</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-slate-900 fw-bold mb-3 small uppercase tracking-wider" style="font-size: 0.75rem; letter-spacing: 0.1em; color: #0f172a;">NEWSLETTER</h5>
                <p class="text-slate-600 small mb-3">Ne manquez aucun événement à venir.</p>
                <form onsubmit="event.preventDefault(); alert('Merci pour votre inscription !');">
                    <div class="d-flex rounded-xl overflow-hidden bg-white border border-sky-200 p-1">
                        <input type="email" class="form-control bg-transparent border-0 text-slate-800 shadow-none placeholder:text-slate-400 py-1.5 text-sm" placeholder="Votre adresse email" style="color: #0f172a !important;" required>
                        <button class="btn btn-primary px-3 rounded-lg border-0 bg-[#d9383a] hover:bg-[#c22e30]" type="submit">
                            <i class="fas fa-paper-plane text-white text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row mt-5 pt-4 border-t border-sky-200 align-items-center text-slate-500 small">
            <div class="col-md-12 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} TGEvent. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Spécificités pour surcharger le layout base */
    footer.sky-footer {
        background-color: #e0f2fe !important;
        border-top: 1px solid #bae6fd !important;
    }
    footer.sky-footer .text-white {
        color: #0f172a !important;
    }
    footer.sky-footer .text-gray-200, 
    footer.sky-footer .text-gray-300, 
    footer.sky-footer .text-gray-400,
    footer.sky-footer .text-slate-400 {
        color: #334155 !important;
    }
    footer.sky-footer .text-gray-500 {
        color: #475569 !important;
    }
    footer.sky-footer a {
        color: #334155 !important;
    }
    footer.sky-footer a:hover {
        color: #1e3a8a !important;
    }
</style>
