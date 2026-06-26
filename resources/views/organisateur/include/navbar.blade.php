<!-- Sidebar Overlay for Mobile -->
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-60 z-30 opacity-0 pointer-events-none lg:hidden backdrop-blur-sm"></div>

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar w-64 fixed h-full z-40 lg:relative lg:translate-x-0 bg-[#1e293b]/90 backdrop-blur-xl border-r border-white/5 flex flex-col justify-between">
        <div class="flex-1 overflow-y-auto scrollbar">
            <!-- Sidebar Header -->
            <div class="p-5 flex items-center justify-between border-b border-white/5">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-gradient-to-tr from-accentIndigo to-accentViolet rounded-xl text-white shadow-lg shadow-indigo-500/25">
                        <i data-feather="calendar" class="w-6 h-6"></i>
                    </div>
                    <span class="text-lg font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">TGEvent Pro</span>
                </div>
                <!-- Close button for mobile -->
                <button id="closeSidebar" class="lg:hidden p-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-6 px-4 space-y-1.5">
                <a href="{{ route('organisateur.dashboard') }}" class="flex items-center space-x-3 {{ request()->routeIs('organisateur.dashboard') ? 'text-white bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }} rounded-xl px-4 py-3 transition-all duration-200">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('organisateur.ajouter-un-evenement') }}" class="flex items-center space-x-3 {{ request()->routeIs('organisateur.ajouter-un-evenement') ? 'text-white bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }} rounded-xl px-4 py-3 transition-all duration-200">
                    <i data-feather="plus-circle" class="w-5 h-5"></i>
                    <span>Ajouter un Événement</span>
                </a>
                
                <a href="{{ route('organisateur.sponsor-form') }}" class="flex items-center space-x-3 {{ request()->routeIs('organisateur.sponsor-form') ? 'text-white bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }} rounded-xl px-4 py-3 transition-all duration-200">
                    <i data-feather="gift" class="w-5 h-5"></i>
                    <span>Sponsors</span>
                </a>
                
                <a href="{{ route('organisateur.billet') }}" class="flex items-center space-x-3 {{ request()->routeIs('organisateur.billet') || request()->routeIs('organisateur.billet-all') || request()->routeIs('organisateur.billet-form') ? 'text-white bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }} rounded-xl px-4 py-3 transition-all duration-200">
                    <i data-feather="tag" class="w-5 h-5"></i>
                    <span>Billetterie</span>
                </a>
                
                <div class="pt-4 pb-2 px-4">
                    <span class="text-xxs font-bold text-gray-500 uppercase tracking-wider">Gestion Événements</span>
                </div>
                
                <a href="{{ route('organisateur.evenement-en-cours') }}" class="flex items-center space-x-3 {{ request()->routeIs('organisateur.evenement-en-cours') ? 'text-white bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }} rounded-xl px-4 py-3 transition-all duration-200">
                    <i data-feather="play-circle" class="w-5 h-5"></i>
                    <span>Gestion des en cours</span>
                </a>
                
                <a href="{{ route('organisateur.future.future') }}" class="flex items-center space-x-3 {{ request()->routeIs('organisateur.future.future') ? 'text-white bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }} rounded-xl px-4 py-3 transition-all duration-200">
                    <i data-feather="trending-up" class="w-5 h-5"></i>
                    <span>Événements futurs</span>
                </a>
                
                <a href="{{ route('organisateur.evenement-passe') }}" class="flex items-center space-x-3 {{ request()->routeIs('organisateur.evenement-passe') ? 'text-white bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }} rounded-xl px-4 py-3 transition-all duration-200">
                    <i data-feather="archive" class="w-5 h-5"></i>
                    <span>Événements passés</span>
                </a>
                
                <a href="{{ route('organisateur.historique') }}" class="flex items-center space-x-3 {{ request()->routeIs('organisateur.historique') ? 'text-white bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }} rounded-xl px-4 py-3 transition-all duration-200">
                    <i data-feather="clock" class="w-5 h-5"></i>
                    <span>Historique complet</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer / User Profile info -->
        <div class="p-4 border-t border-white/5 bg-black/20">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img src="{{ asset('asset/image/hero-img.png') }}" alt="Profile" class="w-10 h-10 rounded-xl object-cover border border-white/10">
                    <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-[#1e293b]"></div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                <!-- Logout Button -->
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="p-1.5 rounded-lg hover:bg-white/5 text-gray-400 hover:text-red-400 transition-colors" title="Se déconnecter">
                    <i data-feather="log-out" class="w-4 h-4"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
