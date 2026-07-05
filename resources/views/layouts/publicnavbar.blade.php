<nav id="main-navbar" style="
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 1050;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 1px 16px rgba(0,0,0,0.07);
    padding: 0;
    font-family: 'Outfit', sans-serif;
">
    <div style="max-width:1200px; margin:0 auto; padding:0 1.25rem; display:flex; align-items:center; justify-content:space-between; height:76px;">

        {{-- Logo --}}
        <a href="{{ url('/') }}" style="display:flex; align-items:center; text-decoration:none; height:72px;">
            <img src="{{ asset('images/logo.png') }}" alt="TGEvent" style="height:100%; max-height:72px; width:auto;">
        </a>

        {{-- Desktop Nav links --}}
        <div id="nav-links" style="display:flex; align-items:center; gap:0.25rem;">

            <a href="{{ route('p.evenement') }}"
               style="text-decoration:none; font-weight:600; font-size:.93rem; padding:.5rem .85rem; border-radius:8px; color:{{ Route::currentRouteName() == 'p.evenement' ? '#4f46e5' : '#334155' }};"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                Trouver un événement
            </a>

            {{-- Catégories dropdown --}}
            <div style="position:relative;" id="cat-dropdown-wrap">
                <button onclick="toggleDropdown('cat-menu')"
                        style="background:transparent; border:none; font-weight:600; font-size:.93rem; padding:.5rem .85rem; border-radius:8px; color:#334155; cursor:pointer; display:flex; align-items:center; gap:.3rem; font-family:'Outfit',sans-serif;"
                        onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    Catégories <span style="font-size:.7rem;">&#9660;</span>
                </button>
                <div id="cat-menu" style="display:none; position:absolute; top:calc(100% + 8px); left:0; background:#fff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.12); min-width:210px; padding:.5rem; z-index:9999;">
                    <a href="{{ route('p.concert et festival de musique') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">&#127925; Concerts &amp; Musique</a>
                    <a href="{{ route('p.conferences et congres') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">&#127908; Conférences</a>
                    <a href="{{ route('p.evenement sportif') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">&#127939; Sports</a>
                    <a href="{{ route('p.santé') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">&#10084; Santé</a>
                    <a href="{{ route('p.vie nocturne') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">&#127865; Vie nocturne</a>
                    <a href="{{ route('p.voyage') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">&#9992; Voyages</a>
                    <a href="{{ route('p.fete') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">&#127870; Fêtes &amp; Sorties</a>
                </div>
            </div>

            <a href="{{ route('p.a-propos') }}"
               style="text-decoration:none; font-weight:600; font-size:.93rem; padding:.5rem .85rem; border-radius:8px; color:{{ Route::currentRouteName() == 'p.a-propos' ? '#4f46e5' : '#334155' }};"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                À propos
            </a>

            <a href="{{ route('p.faq') }}"
               style="text-decoration:none; font-weight:600; font-size:.93rem; padding:.5rem .85rem; border-radius:8px; color:{{ Route::currentRouteName() == 'p.faq' ? '#4f46e5' : '#334155' }};"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                FAQ
            </a>

            @guest
                <a href="{{ route('login') }}"
                   style="text-decoration:none; font-weight:700; font-size:.9rem; padding:.55rem 1.25rem; border-radius:10px; color:#fff; background:#4f46e5; margin-left:.5rem;"
                   onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
                    Connexion
                </a>
            @else
                <div style="position:relative; margin-left:.5rem;" id="user-dropdown-wrap">
                    <button onclick="toggleDropdown('user-menu')"
                            style="background:#f8fafc; border:1px solid #e2e8f0; font-weight:600; font-size:.9rem; padding:.45rem .9rem; border-radius:10px; color:#334155; cursor:pointer; display:flex; align-items:center; gap:.5rem; font-family:'Outfit',sans-serif;">
                        <span style="width:30px;height:30px;border-radius:50%;background:#4f46e5;color:#fff;font-weight:700;font-size:.85rem;display:flex;align-items:center;justify-content:center;">
                            {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                        </span>
                        {{ Auth::user()->nom }}
                        <span style="font-size:.65rem;">&#9660;</span>
                    </button>
                    <div id="user-menu" style="display:none; position:absolute; top:calc(100% + 8px); right:0; background:#fff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.12); min-width:200px; padding:.5rem; z-index:9999;">
                        @if(Auth::user()->role == 'organisateur' || Auth::user()->role == 'admin')
                            <a href="{{ route('organisateur.dashboard') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Tableau de bord</a>
                            <hr style="margin:.4rem 0;border-color:#f1f5f9;">
                        @elseif(Auth::user()->role == 'scanner')
                            <a href="{{ route('scanner.dashboard') }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Scanner</a>
                            <hr style="margin:.4rem 0;border-color:#f1f5f9;">
                        @else
                            <a href="{{ route('dashboard', ['tab' => 'tickets']) }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Mes Tickets</a>
                            <a href="{{ route('dashboard', ['tab' => 'favoris']) }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Favoris</a>
                            <a href="{{ route('dashboard', ['tab' => 'historique']) }}" style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Historique</a>
                            <hr style="margin:.4rem 0;border-color:#f1f5f9;">
                        @endif
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                           style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border-radius:8px;text-decoration:none;color:#ef4444;font-size:.9rem;font-weight:500;" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='transparent'">
                           Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                    </div>
                </div>
            @endguest

        </div>

        {{-- Hamburger (mobile only, shown via JS) --}}
        <button id="hamburger-btn" onclick="toggleMobileMenu()"
                style="display:none; background:transparent; border:none; padding:.5rem; cursor:pointer; font-size:1.7rem; color:#1e293b; line-height:1; align-items:center; justify-content:center;">
            <span id="hamburger-icon">&#9776;</span>
        </button>
    </div>

    {{-- Mobile dropdown menu --}}
    <div id="mobile-menu" style="display:none; background:#fff; border-top:1px solid #e2e8f0; padding:1rem 1.25rem 1.5rem; box-shadow:0 8px 24px rgba(0,0,0,0.1);">
        <a href="{{ route('p.evenement') }}" style="display:block;padding:.7rem .85rem;border-radius:8px;text-decoration:none;font-weight:600;color:#334155;font-size:1rem;margin-bottom:.2rem;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Trouver un événement</a>

        <button onclick="toggleMobileCat()" style="width:100%;text-align:left;background:transparent;border:none;padding:.7rem .85rem;border-radius:8px;font-weight:600;color:#334155;font-size:1rem;cursor:pointer;font-family:'Outfit',sans-serif;margin-bottom:.2rem;display:flex;justify-content:space-between;align-items:center;">
            Catégories <span id="cat-arrow" style="font-size:.75rem;">&#9654;</span>
        </button>
        <div id="mobile-cat-list" style="display:none;padding-left:.75rem;margin-bottom:.2rem;">
            <a href="{{ route('p.concert et festival de musique') }}" style="display:block;padding:.5rem .85rem;border-radius:8px;text-decoration:none;color:#475569;font-size:.93rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Concerts &amp; Musique</a>
            <a href="{{ route('p.conferences et congres') }}" style="display:block;padding:.5rem .85rem;border-radius:8px;text-decoration:none;color:#475569;font-size:.93rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Conférences</a>
            <a href="{{ route('p.evenement sportif') }}" style="display:block;padding:.5rem .85rem;border-radius:8px;text-decoration:none;color:#475569;font-size:.93rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Sports</a>
            <a href="{{ route('p.santé') }}" style="display:block;padding:.5rem .85rem;border-radius:8px;text-decoration:none;color:#475569;font-size:.93rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Santé</a>
            <a href="{{ route('p.vie nocturne') }}" style="display:block;padding:.5rem .85rem;border-radius:8px;text-decoration:none;color:#475569;font-size:.93rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Vie nocturne</a>
            <a href="{{ route('p.voyage') }}" style="display:block;padding:.5rem .85rem;border-radius:8px;text-decoration:none;color:#475569;font-size:.93rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Voyages</a>
            <a href="{{ route('p.fete') }}" style="display:block;padding:.5rem .85rem;border-radius:8px;text-decoration:none;color:#475569;font-size:.93rem;font-weight:500;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">Fêtes &amp; Sorties</a>
        </div>

        <a href="{{ route('p.a-propos') }}" style="display:block;padding:.7rem .85rem;border-radius:8px;text-decoration:none;font-weight:600;color:#334155;font-size:1rem;margin-bottom:.2rem;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">À propos</a>
        <a href="{{ route('p.faq') }}" style="display:block;padding:.7rem .85rem;border-radius:8px;text-decoration:none;font-weight:600;color:#334155;font-size:1rem;margin-bottom:.75rem;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">FAQ</a>

        @guest
            <a href="{{ route('login') }}" style="display:block;text-align:center;padding:.75rem 1.5rem;border-radius:10px;background:#4f46e5;color:#fff;font-weight:700;font-size:1rem;text-decoration:none;">Connexion</a>
        @else
            <div style="border-top:1px solid #f1f5f9;padding-top:.75rem;margin-top:.25rem;">
                @if(Auth::user()->role == 'organisateur' || Auth::user()->role == 'admin')
                    <a href="{{ route('organisateur.dashboard') }}" style="display:block;padding:.6rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-weight:600;font-size:.95rem;">Tableau de bord</a>
                @elseif(Auth::user()->role == 'scanner')
                    <a href="{{ route('scanner.dashboard') }}" style="display:block;padding:.6rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-weight:600;font-size:.95rem;">Scanner</a>
                @else
                    <a href="{{ route('dashboard', ['tab' => 'tickets']) }}" style="display:block;padding:.6rem .85rem;border-radius:8px;text-decoration:none;color:#334155;font-weight:600;font-size:.95rem;">Mes Tickets</a>
                @endif
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form-mobile').submit();"
                   style="display:block;padding:.6rem .85rem;border-radius:8px;text-decoration:none;color:#ef4444;font-weight:600;font-size:.95rem;margin-top:.25rem;">Déconnexion</a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            </div>
        @endguest
    </div>
</nav>

<script>
(function() {
    function handleResize() {
        var hamburger = document.getElementById('hamburger-btn');
        var navLinks  = document.getElementById('nav-links');
        if (!hamburger || !navLinks) return;
        if (window.innerWidth < 992) {
            hamburger.style.display = 'flex';
            navLinks.style.display  = 'none';
        } else {
            hamburger.style.display = 'none';
            navLinks.style.display  = 'flex';
            var mm = document.getElementById('mobile-menu');
            if (mm) mm.style.display = 'none';
        }
    }

    window.toggleMobileMenu = function() {
        var menu = document.getElementById('mobile-menu');
        var icon = document.getElementById('hamburger-icon');
        if (!menu) return;
        var isOpen = menu.style.display === 'block';
        menu.style.display = isOpen ? 'none' : 'block';
        if (icon) icon.innerHTML = isOpen ? '&#9776;' : '&#10005;';
    };

    window.toggleMobileCat = function() {
        var list  = document.getElementById('mobile-cat-list');
        var arrow = document.getElementById('cat-arrow');
        if (!list) return;
        var isOpen = list.style.display === 'block';
        list.style.display = isOpen ? 'none' : 'block';
        if (arrow) arrow.innerHTML = isOpen ? '&#9654;' : '&#9660;';
    };

    window.toggleDropdown = function(id) {
        var menu = document.getElementById(id);
        if (!menu) return;
        var isOpen = menu.style.display === 'block';
        ['cat-menu','user-menu'].forEach(function(m) {
            var el = document.getElementById(m);
            if (el) el.style.display = 'none';
        });
        menu.style.display = isOpen ? 'none' : 'block';
    };

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#cat-dropdown-wrap') && !e.target.closest('#user-dropdown-wrap')) {
            ['cat-menu','user-menu'].forEach(function(m) {
                var el = document.getElementById(m);
                if (el) el.style.display = 'none';
            });
        }
    });

    handleResize();
    window.addEventListener('resize', handleResize);
})();
</script>