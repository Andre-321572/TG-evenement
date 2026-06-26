<nav class="navbar navbar-expand-lg fixed-top z-50 py-3 border-b border-white/10" style="background: rgba(5, 6, 11, 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-gradient-primary fs-4 tracking-wider" href="{{ url('/') }}">
            <i class="fas fa-calendar-alt me-2 text-indigo-500"></i>TGEvent
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas"
            aria-controls="navbarOffcanvas" aria-expanded="false" aria-label="Toggle navigation">
             <span class="fas fa-bars text-white fs-4"></span>
        </button>
        
        <div class="offcanvas offcanvas-start bg-[#0a0b10] border-e border-white/10" tabindex="1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
            <div class="offcanvas-header border-b border-white/10">
                <h5 class="offcanvas-title fw-bold text-gradient-primary" id="navbarOffcanvasLabel">TGEvent</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto mb-lg-0 align-items-lg-center">
                    <li class="nav-item px-2">
                        <a class="nav-link text-gray-300 hover:text-indigo-400 font-semibold transition-all duration-300 {{ Route::currentRouteName() == 'p.evenement' ? 'text-indigo-400' : '' }}" href="{{ route('p.evenement') }}">
                            <i class="fas fa-search me-1"></i> Trouver un événement
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown px-2">
                        <a class="nav-link dropdown-toggle text-gray-300 hover:text-indigo-400 font-semibold transition-all duration-300" href="#" id="navbarDropdownCategories" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-th-large me-1"></i> Catégories
                        </a>
                        <ul class="dropdown-menu border border-white/10 shadow-2xl p-2 bg-[#0d0e16]" aria-labelledby="navbarDropdownCategories" style="backdrop-filter: blur(16px);">
                            <li><a class="dropdown-item text-gray-300 hover:bg-indigo-600 hover:text-white rounded py-2" href="{{route('p.concert et festival de musique')}}"><i class="fas fa-music me-2 text-pink-500"></i> Concerts & Musique</a></li>
                            <li><a class="dropdown-item text-gray-300 hover:bg-indigo-600 hover:text-white rounded py-2" href="{{route('p.conferences et congres')}}"><i class="fas fa-microphone me-2 text-blue-500"></i> Conférences</a></li>
                            <li><a class="dropdown-item text-gray-300 hover:bg-indigo-600 hover:text-white rounded py-2" href="{{route('p.evenement sportif')}}"><i class="fas fa-running me-2 text-emerald-500"></i> Sports</a></li>
                            <li><a class="dropdown-item text-gray-300 hover:bg-indigo-600 hover:text-white rounded py-2" href="{{route('p.santé')}}"><i class="fas fa-heartbeat me-2 text-red-500"></i> Santé</a></li>
                            <li><a class="dropdown-item text-gray-300 hover:bg-indigo-600 hover:text-white rounded py-2" href="{{route('p.vie nocturne')}}"><i class="fas fa-cocktail me-2 text-violet-500"></i> Vie nocturne</a></li>
                            <li><a class="dropdown-item text-gray-300 hover:bg-indigo-600 hover:text-white rounded py-2" href="{{route('p.voyage')}}"><i class="fas fa-plane me-2 text-teal-500"></i> Voyages</a></li>
                            <li><a class="dropdown-item text-gray-300 hover:bg-indigo-600 hover:text-white rounded py-2" href="{{route('p.fete')}}"><i class="fas fa-glass-cheers me-2 text-amber-500"></i> Fêtes & Sorties</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item px-2">
                        <a class="nav-link text-gray-300 hover:text-indigo-400 font-semibold transition-all duration-300 {{ Route::currentRouteName() == 'p.a-propos' ? 'text-indigo-400' : '' }}" href="{{ route('p.a-propos') }}">
                            À propos
                        </a>
                    </li>
                    
                    <li class="nav-item px-2">
                        <a class="nav-link text-gray-300 hover:text-indigo-400 font-semibold transition-all duration-300 {{ Route::currentRouteName() == 'p.faq' ? 'text-indigo-400' : '' }}" href="{{ route('p.faq') }}">
                            FAQ
                        </a>
                    </li>
                    
                    @guest
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <a href="{{ route('login') }}" class="btn px-4 py-2 text-white font-semibold rounded-lg hover:scale-105 transition-all duration-300 shadow-md border-0" style="background: #6366f1;">
                                <i class="fas fa-sign-in-alt me-1"></i> Connexion
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white bg-white/5 border border-white/10 rounded-full px-3 py-1.5 hover:bg-white/10 transition-all duration-300" href="#" id="userDropdown" role="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold me-2 shadow-inner">
                                    {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                </div>
                                <span class="font-medium">{{ Auth::user()->nom }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border border-white/10 shadow-2xl p-2 bg-[#0d0e16]" aria-labelledby="userDropdown" style="backdrop-filter: blur(16px);">
                                @if(Auth::user()->role == 'organisateur' || Auth::user()->role == 'admin')
                                    <li>
                                        <a class="dropdown-item text-gray-300 hover:bg-indigo-600 hover:text-white rounded py-2" href="{{ route('organisateur.dashboard') }}">
                                            <i class="fa fa-dashboard me-2 text-indigo-500"></i>Tableau de bord
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider border-white/10"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item text-gray-300 hover:bg-red-600 hover:text-white rounded py-2" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out me-2 text-red-500"></i>{{ __('Déconnexion') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>