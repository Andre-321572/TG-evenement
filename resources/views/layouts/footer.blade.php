<footer class="border-t border-slate-800 py-12 mt-20" style="background: #0e131f;">
    <div class="container">
        <div class="row g-4">
            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h4 class="fw-bold text-white tracking-wider mb-3">TGEvent</h4>
                <p class="text-slate-400 leading-relaxed small mb-0" style="max-width: 320px;">
                    La destination numéro un pour vos billets d'événements préférés. Fiable, rapide et sécurisé.
                </p>
            </div>

            <!-- Platform Column -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-white fw-bold mb-3 small uppercase tracking-wider" style="font-size: 0.75rem; letter-spacing: 0.1em;">PLATEFORME</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ route('p.a-propos') }}" class="text-slate-400 hover:text-white transition-colors duration-200 text-sm text-decoration-none">À propos</a></li>
                    <li class="mb-2"><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 text-sm text-decoration-none">Confidentialité</a></li>
                    <li class="mb-2"><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 text-sm text-decoration-none">Conditions</a></li>
                    <li class="mb-2"><a href="{{ route('p.contact') }}" class="text-slate-400 hover:text-white transition-colors duration-200 text-sm text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Help Column -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-white fw-bold mb-3 small uppercase tracking-wider" style="font-size: 0.75rem; letter-spacing: 0.1em;">AIDE</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 text-sm text-decoration-none">Support technique</a></li>
                    <li class="mb-2"><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 text-sm text-decoration-none">Remboursements</a></li>
                    <li class="mb-2"><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 text-sm text-decoration-none">Vendre des billets</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white fw-bold mb-3 small uppercase tracking-wider" style="font-size: 0.75rem; letter-spacing: 0.1em;">NEWSLETTER</h5>
                <p class="text-slate-400 small mb-3">Ne manquez aucun événement à venir.</p>
                <form onsubmit="event.preventDefault(); alert('Merci pour votre inscription !');">
                    <div class="d-flex rounded-xl overflow-hidden bg-white/5 border border-white/10 p-1">
                        <input type="email" class="form-control bg-transparent border-0 text-white shadow-none placeholder:text-slate-600 py-1.5 text-sm" placeholder="Votre adresse email" style="color: #ffffff !important;" required>
                        <button class="btn btn-primary px-3 rounded-lg border-0 bg-[#d9383a] hover:bg-[#c22e30]" type="submit">
                            <i class="fas fa-paper-plane text-white text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row mt-5 pt-4 border-t border-slate-800 align-items-center text-slate-500 small">
            <div class="col-md-12 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} TGEvent. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>
