<footer class="border-t border-white/10 py-10 mt-20" style="background: rgba(30, 41, 59, 0.95);">
    <div class="container">
        <div class="row g-4">
            <!-- Logo and description -->
            <div class="col-lg-4 col-md-6 mb-3">
                <h4 class="fw-bold text-gradient-primary tracking-wider mb-3">TGEvent</h4>
                <p class="text-gray-400 leading-relaxed small">Votre partenaire pour des événements inoubliables. Découvrez, réservez et vivez les meilleures expériences culturelles, sportives et festives.</p>
            </div>

            <!-- Quick links -->
            <div class="col-lg-3 col-md-6 mb-3">
                <h5 class="text-white fw-bold mb-3 small uppercase tracking-wider">Liens rapides</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ url('/') }}" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300 small">Accueil</a></li>
                    <li class="mb-2"><a href="{{ route('p.evenement') }}" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300 small">Découvrir les événements</a></li>
                    <li class="mb-2"><a href="{{ route('p.a-propos') }}" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300 small">À propos de nous</a></li>
                    <li class="mb-2"><a href="{{ route('p.faq') }}" class="text-gray-400 hover:text-indigo-400 transition-colors duration-300 small">Centre d'aide & FAQ</a></li>
                </ul>
            </div>

            <!-- Contact information -->
            <div class="col-lg-3 col-md-6 mb-3">
                <h5 class="text-white fw-bold mb-3 small uppercase tracking-wider">Contact</h5>
                <ul class="list-unstyled text-gray-400 small">
                    <li class="mb-2"><i class="fa fa-map-marker-alt me-2 text-indigo-500"></i> Agbalépédo, Lomé, Togo</li>
                    <li class="mb-2"><i class="fa fa-phone me-2 text-indigo-500"></i> +228 98 46 22 88</li>
                    <li class="mb-2"><i class="fa fa-envelope me-2 text-indigo-500"></i> contact@tgevent.com</li>
                </ul>
            </div>

            <!-- Social media links -->
            <div class="col-lg-2 col-md-6 mb-3">
                <h5 class="text-white fw-bold mb-3 small uppercase tracking-wider">Suivez-nous</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-all duration-300">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-all duration-300">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-all duration-300">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-all duration-300">
                        <i class="fab fa-youtube text-sm"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row mt-4 pt-4 border-t border-white/5 align-items-center text-gray-500 small">
            <div class="col-md-6 mb-2 mb-md-0">
                <p class="mb-0">&copy; {{ date('Y') }} TGEvent. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-gray-500 hover:text-gray-300 text-decoration-none me-3">Politique de confidentialité</a>
                <a href="#" class="text-gray-500 hover:text-gray-300 text-decoration-none">Conditions d'utilisation</a>
            </div>
        </div>
    </div>
</footer>
