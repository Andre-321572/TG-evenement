<nav class="navbar navbar-expand-lg fixed-top py-3 border-bottom shadow-sm" id="main-navbar" style="background: rgba(255,255,255,0.97); z-index: 1050;">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="height: 52px;">
            <img src="{{ asset('images/logo.png') }}" alt="TGEvent" style="height: 100%; max-height: 52px; width: auto;">
        </a>

        {{-- Hamburger button --}}
        <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavCollapse"
                aria-controls="mainNavCollapse"
                aria-expanded="false"
                aria-label="Toggle navigation"
                style="outline:none;">
            <span style="font-size:1.4rem; color:#1e293b;">&#9776;</span>
        </button>

        {{-- Collapsible menu --}}
        <div class="collapse navbar-collapse" id="mainNavCollapse">

            {{-- Spacer to push links to the right --}}
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 pt-3 pt-lg-0">

                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 py-2 rounded-3 {{ Route::currentRouteName() == 'p.evenement' ? 'text-indigo-600' : 'text-slate-700' }}"
                       href="{{ route('p.evenement') }}"
                       style="{{ Route::currentRouteName() == 'p.evenement' ? 'color:#4f46e5!important;' : '' }}">
                        Trouver un événement
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold px-3 py-2 rounded-3 text-slate-700" href="#"
                       id="dropdownCategories" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Catégories
                    </a>
                    <ul class="dropdown-menu shadow border-0 p-2 mt-1" aria-labelledby="dropdownCategories"
                        style="border-radius:12px; min-width:200px;">
                        <li><a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('p.concert et festival de musique') }}"><i class="fas fa-music me-2 text-pink-500"></i>Concerts &amp; Musique</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('p.conferences et congres') }}"><i class="fas fa-microphone me-2 text-blue-500"></i>Conférences</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('p.evenement sportif') }}"><i class="fas fa-running me-2 text-emerald-500"></i>Sports</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('p.santé') }}"><i class="fas fa-heartbeat me-2 text-red-500"></i>Santé</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('p.vie nocturne') }}"><i class="fas fa-cocktail me-2 text-violet-500"></i>Vie nocturne</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('p.voyage') }}"><i class="fas fa-plane me-2 text-teal-500"></i>Voyages</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('p.fete') }}"><i class="fas fa-glass-cheers me-2 text-amber-500"></i>Fêtes &amp; Sorties</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 py-2 rounded-3 {{ Route::currentRouteName() == 'p.a-propos' ? 'text-indigo-600' : 'text-slate-700' }}"
                       href="{{ route('p.a-propos') }}">
                        À propos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 py-2 rounded-3 {{ Route::currentRouteName() == 'p.faq' ? 'text-indigo-600' : 'text-slate-700' }}"
                       href="{{ route('p.faq') }}">
                        FAQ
                    </a>
                </li>

                @guest
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0 mb-3 mb-lg-0">
                        <a href="{{ route('login') }}"
                           class="btn fw-bold px-4 py-2 text-white border-0 shadow-sm"
                           style="background:#4f46e5; border-radius:10px;">
                            Connexion
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown ms-lg-2 mt-2 mt-lg-0 mb-3 mb-lg-0">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                           href="#" id="userDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false"
                           style="background:#f8fafc; border:1px solid #e2e8f0;">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-indigo-600 text-white fw-bold"
                                  style="width:32px;height:32px;font-size:.9rem;background:#4f46e5;">
                                {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                            </span>
                            <span class="fw-semibold text-slate-700">{{ Auth::user()->nom }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-1"
                            aria-labelledby="userDropdown"
                            style="border-radius:12px; min-width:200px;">
                            @if(Auth::user()->role == 'organisateur' || Auth::user()->role == 'admin')
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('organisateur.dashboard') }}">
                                        <i class="fa fa-dashboard me-2 text-indigo-500"></i>Tableau de bord
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                            @elseif(Auth::user()->role == 'scanner')
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('scanner.dashboard') }}">
                                        <i class="fa fa-qrcode me-2 text-indigo-500"></i>Scanner
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                            @else
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('dashboard', ['tab' => 'tickets']) }}">
                                        <i class="fas fa-ticket-alt me-2" style="color:#d9383a;"></i>Mes Tickets
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('dashboard', ['tab' => 'favoris']) }}">
                                        <i class="far fa-heart me-2 text-pink-500"></i>Favoris
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 px-3 text-slate-700" href="{{ route('dashboard', ['tab' => 'historique']) }}">
                                        <i class="fas fa-history me-2 text-indigo-500"></i>Historique
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                            @endif
                            <li>
                                <a class="dropdown-item rounded-2 py-2 px-3 text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out me-2"></i>{{ __('Déconnexion') }}
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

    {{-- Mobile menu smooth open style --}}
    <style>
        #mainNavCollapse {
            background: #fff;
        }
        @media (max-width: 991.98px) {
            #mainNavCollapse {
                border-top: 1px solid #e2e8f0;
                padding: 0.5rem 1rem 1rem;
                box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            }
            #mainNavCollapse .nav-link {
                font-size: 1rem;
                padding: 0.6rem 0.75rem !important;
                border-radius: 8px;
                color: #1e293b !important;
            }
            #mainNavCollapse .nav-link:hover,
            #mainNavCollapse .nav-link:focus {
                background: #f1f5f9;
                color: #4f46e5 !important;
            }
            #mainNavCollapse .dropdown-menu {
                position: static !important;
                box-shadow: none !important;
                background: #f8fafc;
                border: 1px solid #e2e8f0 !important;
                margin-top: 4px;
                margin-bottom: 4px;
            }
        }
    </style>
</nav>