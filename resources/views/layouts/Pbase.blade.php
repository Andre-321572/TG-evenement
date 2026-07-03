<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                container: {
                    center: true,
                    padding: {
                        DEFAULT: '1rem',
                        sm: '1.5rem',
                        md: '2rem',
                        lg: '3rem',
                        xl: '4rem',
                        '2xl': '6rem',
                    },
                },
                extend: {
                    colors: {
                        darkDeep: '#f8fafc',
                        darkCard: 'rgba(255, 255, 255, 0.9)',
                        glassBg: 'rgba(255, 255, 255, 0.95)',
                        glassBorder: 'rgba(59, 130, 246, 0.08)',
                        accentIndigo: '#4f46e5',
                        accentViolet: '#764ba2',
                        accentEmerald: '#10b981',
                        accentRed: '#d9383a',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome & Bootstrap CSS for legacy icons/compatibility -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/bootstrap.min.css')}}">
    
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>{{config('app.name')}} @yield('title')</title>

    <style>
        body {
            font-family: 'Outfit', sans-serif !important;
            background-color: #fafbfc !important;
            color: #0f172a !important;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #fafbfc;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(79, 70, 229, 0.2);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(79, 70, 229, 0.4);
        }

        /* Ambient glows */
        .glow-orb-purple {
            position: fixed;
            top: 20%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: #8b5cf6;
            filter: blur(150px);
            opacity: 0.04;
            pointer-events: none;
            z-index: -1;
        }

        .glow-orb-blue {
            position: fixed;
            bottom: 20%;
            right: 10%;
            width: 400px;
            height: 400px;
            background: #3b82f6;
            filter: blur(180px);
            opacity: 0.04;
            pointer-events: none;
            z-index: -1;
        }

        /* Sidebar responsive behavior */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }

        /* Mobile sidebar hidden by default */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50/50 flex flex-col font-sans">
    
    <!-- Ambient Glow Objects -->
    <div class="glow-orb-purple"></div>
    <div class="glow-orb-blue"></div>

    <!-- Header (Navbar) -->
    <header class="bg-white border-b border-slate-100 py-3 px-4 sm:px-6 lg:px-8 flex justify-between items-center sticky top-0 z-30 shadow-sm">
        <div class="flex items-center space-x-4">
            <!-- Mobile Menu Toggle -->
            <button id="openSidebar" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <a class="flex items-center" href="{{ url('/') }}">
                <span class="text-xl font-extrabold tracking-tight text-slate-900" style="font-family: 'Outfit', sans-serif;">
                    <span class="text-[#d9383a]">Event</span>Pulse
                </span>
            </a>
        </div>

        <!-- Middle area: EMPTY on purpose ("son navbar ne va rien afficher") -->
        <div class="hidden md:flex flex-grow max-w-md mx-8">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </span>
                <input type="text" placeholder="Rechercher des événements..." class="w-full bg-slate-50/80 border border-slate-200/80 rounded-full pl-9 pr-4 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-accentIndigo/20 focus:border-accentIndigo transition-all">
            </div>
        </div>

        <!-- Right actions -->
        <div class="flex items-center space-x-4">
            <button class="relative p-2 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all focus:outline-none">
                <i class="far fa-bell text-lg"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-[#d9383a] rounded-full"></span>
            </button>
            
            <div class="relative">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-2 text-slate-700 hover:text-slate-900" title="Déconnexion">
                    @if(Auth::user()->img_profil)
                        <img class="w-8 h-8 rounded-full object-cover border border-slate-100 shadow-sm" src="{{ asset('storage/' . Auth::user()->img_profil) }}" alt="{{ Auth::user()->nom }}">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                        </div>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <div class="flex-grow flex relative">
        
        <!-- Sidebar Overlay (mobile only) -->
        <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/40 backdrop-blur-sm z-40 opacity-0 pointer-events-none lg:hidden"></div>

        <!-- Sidebar Left -->
        <aside id="sidebar" class="sidebar fixed lg:sticky top-[61px] bottom-0 left-0 w-64 bg-white border-r border-slate-100 flex flex-col justify-between p-5 z-40 h-[calc(100vh-61px)]">
            
            <div class="space-y-6">
                <!-- User profile box -->
                <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 flex items-center space-x-3">
                    @if(Auth::user()->img_profil)
                        <img class="w-10 h-10 rounded-full object-cover border border-slate-200" src="{{ asset('storage/' . Auth::user()->img_profil) }}" alt="{{ Auth::user()->nom }}">
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-extrabold text-base">
                            {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-bold text-slate-900 truncate leading-snug">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</h4>
                        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider mt-0.5">Participant</p>
                    </div>
                </div>

                <!-- Sidebar navigation -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all group {{ request()->routeIs('dashboard') && request('tab') !== 'tickets' ? 'bg-[#d9383a] text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fas fa-th-large {{ request()->routeIs('dashboard') && request('tab') !== 'tickets' ? 'text-white' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                        <span>Tableau de bord</span>
                    </a>
                    
                    <a href="{{ route('dashboard', ['tab' => 'tickets']) }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all group {{ request('tab') === 'tickets' ? 'bg-[#d9383a] text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fas fa-ticket-alt {{ request('tab') === 'tickets' ? 'text-white' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                        <span>Mes Tickets</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all group">
                        <i class="far fa-heart text-slate-400 group-hover:text-slate-600"></i>
                        <span>Favoris</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all group">
                        <i class="fas fa-history text-slate-400 group-hover:text-slate-600"></i>
                        <span>Historique</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all group">
                        <i class="fas fa-sliders-h text-slate-400 group-hover:text-slate-600"></i>
                        <span>Paramètres</span>
                    </a>
                </nav>
            </div>

            <!-- Bottom sidebar content -->
            <div class="space-y-4">
                <a href="{{ route('p.evenement') }}" class="w-full bg-[#d9383a] hover:bg-[#c22e30] text-white text-center py-2.5 rounded-xl text-sm font-bold shadow-md transition-all duration-300 block">
                    Explorer plus
                </a>

                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center justify-center space-x-2 text-xs font-semibold text-slate-400 hover:text-[#d9383a] py-2 transition-all">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Se déconnecter</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-grow min-w-0 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="{{ asset('asset/bootstrap/bootstrap.min.js') }}"></script>

    <script>
        // Sidebar mobile interactions
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openBtn = document.getElementById('openSidebar');

        function toggleSidebar(state) {
            if (state) {
                sidebar.classList.add('open');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
            } else {
                sidebar.classList.remove('open');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
            }
        }

        if (openBtn) {
            openBtn.addEventListener('click', () => toggleSidebar(true));
        }
        if (overlay) {
            overlay.addEventListener('click', () => toggleSidebar(false));
        }

        // Close sidebar on desktop resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                toggleSidebar(false);
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
