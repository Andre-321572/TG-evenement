@extends('layouts.scanner')

@section('content')
<style>
    body {
        background-color: #f8fafc !important;
        color: #1e293b !important;
        font-family: 'Outfit', sans-serif !important;
    }
    
    /* Mobile Wrapper Shell */
    #scanner-app {
        min-height: 100vh;
        background: #fafbfc;
        display: flex;
        flex-direction: column;
        max-width: 480px;
        margin: 0 auto;
        padding: 0;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        border-left: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
    }

    /* Tab navigation at the bottom */
    #bottom-nav {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 64px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-around;
        z-index: 40;
    }

    .nav-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        height: 100%;
        color: #94a3b8;
        font-size: 0.65rem;
        font-weight: 700;
        transition: all 0.2s ease;
        border: none;
        background: transparent;
        outline: none;
    }
    .nav-tab-btn i {
        font-size: 1.2rem;
        margin-bottom: 3px;
    }
    .nav-tab-btn.active {
        color: #4f46e5;
    }
    .nav-tab-btn.active-pill {
        background: rgba(79, 70, 229, 0.08);
        color: #4f46e5;
        border-radius: 12px;
        margin: 6px;
        padding: 4px;
    }

    /* Custom Camera / Scan Animation */
    #camera-box {
        position: relative;
        width: 100%;
        aspect-ratio: 1;
        background: #000;
        border-radius: 28px;
        overflow: hidden;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }
    #reader { width: 100% !important; height: 100% !important; }
    #reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block !important;
    }
    #reader canvas, #reader img { display: none !important; }

    #scan-reticle {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 10;
    }

    .corner {
        position: absolute;
        width: 24px;
        height: 24px;
    }
    .corner-tl { top: 20px; left: 20px; border-top: 4px solid #ef4444; border-left: 4px solid #ef4444; border-radius: 8px 0 0 0; }
    .corner-tr { top: 20px; right: 20px; border-top: 4px solid #ef4444; border-right: 4px solid #ef4444; border-radius: 0 8px 0 0; }
    .corner-bl { bottom: 20px; left: 20px; border-bottom: 4px solid #ef4444; border-left: 4px solid #ef4444; border-radius: 0 0 0 8px; }
    .corner-br { bottom: 20px; right: 20px; border-bottom: 4px solid #ef4444; border-right: 4px solid #ef4444; border-radius: 0 0 8px 0; }

    .scan-line {
        position: absolute;
        left: 30px;
        right: 30px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #ef4444, #f87171, #ef4444, transparent);
        top: 30px;
        animation: scan-sweep-action 2.2s linear infinite;
        opacity: 0.8;
    }
    @keyframes scan-sweep-action {
        0% { top: 30px; }
        50% { top: calc(100% - 30px); }
        100% { top: 30px; }
    }

    /* Pulse bg when scanning */
    .scan-glow {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(239, 68, 68, 0.05) 0%, transparent 70%);
        animation: scan-pulse 2s ease-in-out infinite;
    }
    @keyframes scan-pulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.7; }
    }
</style>

<div id="scanner-app">

    <!-- TAB 1: SCAN VIEW (Default) -->
    <div id="view-scan" class="flex-grow flex flex-col pb-20 overflow-y-auto">
        <!-- Top bar Header -->
        <div class="bg-white border-b border-slate-100 py-3 px-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center space-x-2 text-slate-800">
                <i class="fas fa-qrcode text-lg text-indigo-600"></i>
                <span class="font-extrabold tracking-tight text-base">TGScanner</span>
            </div>
            
            <!-- Event Dropdown Select -->
            <div class="flex-grow max-w-[200px] mx-2">
                <select id="event-selector" class="w-full bg-slate-50 border border-slate-200/80 rounded-full px-3.5 py-1.5 text-xs font-bold text-slate-700 focus:outline-none cursor-pointer appearance-none text-center" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2210%22 height=%2210%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23475569%22 stroke-width=%223%22%3E%3Cpath d=%22m6 9 6 6 6-6%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 10px center; padding-right: 22px;">
                    @foreach($evenements as $idx => $ev)
                        <option value="{{ $ev->id }}" {{ $idx == 0 ? 'selected' : '' }}>{{ $ev->titre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Profile Avatar placeholder -->
            <button onclick="switchTab('settings')" class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors focus:outline-none">
                <i class="far fa-user text-xs"></i>
            </button>
        </div>

        <div class="p-4 space-y-5">
            <!-- CHECKED-IN GUESTS Card -->
            <div class="bg-white border border-slate-100/90 rounded-3xl p-5 shadow-sm space-y-3">
                <div class="flex justify-between items-center text-slate-400 text-[10px] font-extrabold uppercase tracking-widest">
                    <span>Checked-in Guests</span>
                </div>
                
                <div class="flex justify-between items-end">
                    <h2 class="text-3xl font-black tracking-tight text-slate-800">
                        <span id="checked-in-count">0</span> <span class="text-slate-400 text-lg font-bold">/ <span id="total-guests-count">0</span></span>
                    </h2>
                    <span id="checked-in-percentage" class="text-xs font-black text-red-500">
                        0.0%
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div id="checked-in-progressbar" class="h-full bg-indigo-950 rounded-full transition-all duration-500" style="width: 0%;"></div>
                </div>
            </div>

            <!-- Status Info Badge -->
            <div class="text-center">
                <span id="status-badge" class="inline-flex items-center space-x-2 px-4 py-2 bg-indigo-950 text-indigo-200 text-xs font-extrabold rounded-full uppercase tracking-wider shadow-sm">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Scanner prêt : Aucun Scan</span>
                </span>
            </div>

            <!-- Scanner Box Frame -->
            <div class="space-y-3 text-center">
                <div id="camera-box" class="cursor-pointer">
                    <!-- Tap to Scan Placeholder -->
                    <div id="camera-placeholder" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-slate-900/95 text-white p-6 space-y-4">
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center shadow-lg border border-white/20 animate-bounce">
                            <i class="fas fa-camera text-2xl text-[#d9383a]"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-extrabold text-base tracking-wide uppercase">Tap to Scan</h3>
                            <p class="text-[10px] text-slate-400 max-w-[240px] mx-auto">Touchez ici pour démarrer l'appareil photo et scanner les codes QR</p>
                        </div>
                    </div>

                    <!-- html5-qrcode video element container -->
                    <div id="reader" style="display:none;"></div>

                    <!-- Overlaid scan HUD -->
                    <div id="scan-reticle" style="display:none;">
                        <div class="scan-glow"></div>
                        <div class="corner corner-tl"></div>
                        <div class="corner corner-tr"></div>
                        <div class="corner corner-bl"></div>
                        <div class="corner corner-br"></div>
                        <div class="scan-line"></div>
                    </div>
                </div>
                
                <p class="text-xs text-slate-500 font-semibold flex items-center justify-center space-x-1.5">
                    <i class="fas fa-info-circle text-slate-400"></i>
                    <span>Position QR code within the frame</span>
                </p>
            </div>

            <!-- Manual Ticket Entry -->
            <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-3">
                <h4 class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Manual Ticket Entry</h4>
                <form id="manual-form" class="flex items-center space-x-2">
                    <input type="text" id="manual-code-input" placeholder="Enter Ticket ID (e.g., TGE-XXXXXXXX)" class="flex-grow bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-800 placeholder-slate-400">
                    <button type="submit" class="bg-[#d9383a] hover:bg-[#c22e30] text-white px-5 py-2.5 rounded-xl text-xs font-black tracking-wider uppercase transition-colors border-0">
                        Validate
                    </button>
                </form>
            </div>

            <!-- Recent Scans List -->
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Recent Scans</h3>
                    <button onclick="switchTab('history')" class="text-xs font-bold text-indigo-600 hover:underline border-0 bg-transparent">View All</button>
                </div>

                <div id="recent-scans-list" class="space-y-2.5">
                    @forelse($recentScans as $scan)
                        <div class="bg-white border border-slate-100 rounded-2xl p-3 flex items-center justify-between shadow-xs">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="min-w-0">
                                    <h5 class="text-xs font-bold text-slate-800 truncate leading-snug">{{ $scan->buyer_name ?? $scan->buyer_email }}</h5>
                                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Ticket #{{ $scan->code }}</p>
                                </div>
                            </div>
                            <div class="text-right space-y-1">
                                <span class="text-[9px] text-slate-400 font-bold block">{{ $scan->scanned_at?->diffForHumans() ?? 'Récemment' }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[8px] font-extrabold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Valid
                                </span>
                            </div>
                        </div>
                    @empty
                        <div id="no-recent-scans" class="bg-white border border-slate-100 rounded-2xl p-4 text-center text-xs text-slate-400 font-semibold">
                            Aucun scan effectué pendant cette session.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: HISTORY VIEW -->
    <div id="view-history" class="flex-grow flex flex-col pb-20 overflow-y-auto hidden">
        <div class="bg-white border-b border-slate-100 py-3.5 px-4 sticky top-0 z-20">
            <h2 class="text-base font-extrabold text-slate-900">Historique des Scans</h2>
            <p class="text-[10px] text-slate-400 font-semibold">Liste complète des billets scannés par votre compte.</p>
        </div>
        <div class="p-4 space-y-3">
            <div id="full-history-list" class="space-y-2.5">
                <!-- Loaded dynamically or static list -->
                @foreach($recentScans as $scan)
                    <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between shadow-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800 leading-snug">{{ $scan->buyer_name ?? $scan->buyer_email }}</h5>
                                <p class="text-[10px] text-slate-400 font-semibold">Ticket #{{ $scan->code }} · {{ $scan->billet?->type }}</p>
                                <p class="text-[9px] text-indigo-500 font-bold leading-none mt-1">{{ $scan->evenement?->titre }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-500 font-bold block">{{ $scan->scanned_at?->format('H:i') }}</span>
                            <span class="text-[9px] text-slate-400 font-semibold block">{{ $scan->scanned_at?->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- TAB 3: STATS VIEW -->
    <div id="view-stats" class="flex-grow flex flex-col pb-20 overflow-y-auto hidden">
        <div class="bg-white border-b border-slate-100 py-3.5 px-4 sticky top-0 z-20">
            <h2 class="text-base font-extrabold text-slate-900">Statistiques de Présence</h2>
            <p class="text-[10px] text-slate-400 font-semibold">Suivi d'avancement des enregistrements.</p>
        </div>
        <div class="p-4 space-y-4">
            <!-- Stats grid -->
            <div id="stats-grid-container" class="grid grid-cols-2 gap-4">
                <!-- Calculated dynamically in JavaScript -->
            </div>
            
            <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-4">
                <h4 class="text-xs font-bold text-slate-800 tracking-tight">Récapitulatif des billets par Événement</h4>
                <div id="stats-events-breakdown" class="space-y-3.5">
                    <!-- Event statistics list -->
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 4: SETTINGS VIEW -->
    <div id="view-settings" class="flex-grow flex flex-col pb-20 overflow-y-auto hidden">
        <div class="bg-white border-b border-slate-100 py-3.5 px-4 sticky top-0 z-20">
            <h2 class="text-base font-extrabold text-slate-900">Paramètres Scanner</h2>
            <p class="text-[10px] text-slate-400 font-semibold">Informations du compte et session.</p>
        </div>
        <div class="p-4 space-y-5">
            <!-- Account card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-5 text-center space-y-3 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-indigo-600 text-white flex items-center justify-center text-2xl font-black mx-auto">
                    {{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 leading-snug">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h3>
                    <p class="text-xs text-slate-400 font-semibold">{{ auth()->user()->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                        Rôle : {{ auth()->user()->role }}
                    </span>
                </div>
            </div>

            <!-- Logout button -->
            <div class="pt-4">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-scanner').submit();" class="w-full text-center bg-[#d9383a] hover:bg-[#c22e30] text-white py-3 rounded-2xl text-xs font-black tracking-wider uppercase transition-colors shadow-md block border-0">
                    Déconnexion
                </a>
                <form id="logout-form-scanner" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- BOTTOM TAB NAVIGATION BAR -->
    <div id="bottom-nav">
        <button onclick="switchTab('scan')" id="tab-btn-scan" class="nav-tab-btn active">
            <i class="fas fa-qrcode"></i>
            <span>Scan</span>
        </button>
        <button onclick="switchTab('history')" id="tab-btn-history" class="nav-tab-btn">
            <i class="fas fa-history"></i>
            <span>History</span>
        </button>
        <button onclick="switchTab('stats')" id="tab-btn-stats" class="nav-tab-btn">
            <i class="fas fa-chart-bar"></i>
            <span>Stats</span>
        </button>
        <button onclick="switchTab('settings')" id="tab-btn-settings" class="nav-tab-btn">
            <i class="fas fa-sliders-h"></i>
            <span>Settings</span>
        </button>
    </div>

</div>

<!-- HTML5 QR Code library CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function() {
    let html5QrCode = null;
    let scanning = false;
    let cooldown = false;
    let activeEventId = null;

    // Database mapped structures
    const events = @json($evenements);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const verifyUrl = '{{ route("scanner.verify") }}';

    // Switch between tabs
    window.switchTab = function(tabName) {
        // Stop camera if leaving scan view
        if (tabName !== 'scan' && scanning) {
            stopCamera();
        }

        // Hide all views
        document.getElementById('view-scan').classList.add('hidden');
        document.getElementById('view-history').classList.add('hidden');
        document.getElementById('view-stats').classList.add('hidden');
        document.getElementById('view-settings').classList.add('hidden');

        // Deactivate all buttons
        document.getElementById('tab-btn-scan').classList.remove('active');
        document.getElementById('tab-btn-history').classList.remove('active');
        document.getElementById('tab-btn-stats').classList.remove('active');
        document.getElementById('tab-btn-settings').classList.remove('active');

        // Activate select tab
        document.getElementById('view-' + tabName).classList.remove('hidden');
        document.getElementById('tab-btn-' + tabName).classList.add('active');

        if (tabName === 'stats') {
            renderStatsTab();
        }
    };

    // Render Stats View
    function renderStatsTab() {
        const selectedId = document.getElementById('event-selector').value;
        const statsUrl = '{{ route("scanner.stats") }}?evenement_id=' + selectedId;

        fetch(statsUrl)
            .then(res => res.json())
            .then(data => {
                if (data.error) return;

                // Live event headers
                document.getElementById('stats-live-title').textContent = data.evenement.titre;
                document.getElementById('stats-live-lieu').innerHTML = `
                    <i class="fas fa-map-marker-alt text-slate-400 mr-1.5"></i>
                    <span>${data.evenement.lieu}</span>
                `;

                // Attendance card
                document.getElementById('stats-attendance-scanned').textContent = data.scanned;
                document.getElementById('stats-attendance-total').textContent = data.total;
                document.getElementById('stats-attendance-percentage').textContent = data.percentage + '%';
                document.getElementById('stats-attendance-bar').style.width = data.percentage + '%';

                // Ticket Breakdown
                const breakdownContainer = document.getElementById('stats-ticket-breakdown-list');
                breakdownContainer.innerHTML = '';
                
                data.breakdown.forEach(item => {
                    const radius = 18;
                    const circumference = 2 * Math.PI * radius;
                    const offset = circumference - (item.percentage / 100) * circumference;

                    let strokeColor = 'stroke-slate-400';
                    let badgeClass = 'text-slate-600 bg-slate-50 border-slate-100';
                    
                    const typeLower = item.type.toLowerCase();
                    if (typeLower.includes('vvip')) {
                        strokeColor = 'stroke-red-500';
                        badgeClass = 'text-red-600 bg-red-50 border-red-100';
                    } else if (typeLower.includes('vip')) {
                        strokeColor = 'stroke-indigo-900';
                        badgeClass = 'text-indigo-600 bg-indigo-50 border-indigo-100';
                    } else if (typeLower.includes('std') || typeLower.includes('standard') || typeLower.includes('simpl')) {
                        strokeColor = 'stroke-indigo-600';
                        badgeClass = 'text-indigo-600 bg-indigo-50 border-indigo-100';
                    }

                    breakdownContainer.innerHTML += `
                        <div class="bg-white border border-slate-100/90 rounded-3xl p-4 flex items-center justify-between shadow-xs">
                            <div class="flex items-center space-x-4">
                                <div class="relative w-12 h-12 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle cx="24" cy="24" r="${radius}" class="stroke-slate-100 fill-none" stroke-width="4" />
                                        <circle cx="24" cy="24" r="${radius}" class="${strokeColor} fill-none transition-all duration-700" stroke-width="4" stroke-dasharray="${circumference}" stroke-dashoffset="${offset}" stroke-linecap="round" />
                                    </svg>
                                    <span class="absolute text-[10px] font-black text-slate-800">${item.percentage}%</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-1.5 flex-wrap">
                                        <h4 class="text-xs font-bold text-slate-800 truncate">${item.type}</h4>
                                        ${item.label ? `<span class="px-2 py-0.5 rounded-md text-[8px] font-extrabold uppercase border ${badgeClass}">${item.label}</span>` : ''}
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-semibold mt-1">${item.scanned} / ${item.total} Validated</p>
                                </div>
                            </div>
                            <button class="text-slate-300 hover:text-slate-400 focus:outline-none border-0 bg-transparent">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    `;
                });

                // Peak Hour Chart
                const chartBars = document.getElementById('stats-chart-bars');
                chartBars.innerHTML = '';
                
                const maxCount = Math.max(...data.hourly.map(h => h.count));
                
                data.hourly.forEach(item => {
                    const pctHeight = maxCount > 0 ? (item.count / maxCount) * 85 : 0;
                    const isPeak = maxCount > 0 && item.count === maxCount;
                    const barBgColor = isPeak ? 'bg-[#d9383a]' : 'bg-indigo-100';

                    chartBars.innerHTML += `
                        <div class="flex flex-col items-center justify-end h-full flex-grow mx-1 group relative">
                            <span class="absolute -top-6 bg-slate-800 text-white text-[9px] font-bold px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                ${item.count} scans
                            </span>
                            <div class="${barBgColor} w-6 rounded-t-lg transition-all duration-700" style="height: ${Math.max(pctHeight, 8)}%;"></div>
                        </div>
                    `;
                });
            })
            .catch(() => {});
    }

    // Dropdown change events
    document.getElementById('event-selector').addEventListener('change', function() {
        activeEventId = this.value;
        updateCheckedInWidget();
    });

    // Update statistics card in header
    function updateCheckedInWidget() {
        const selector = document.getElementById('event-selector');
        const selectedId = selector.value;
        const eventData = events.find(e => e.id == selectedId);

        if (eventData) {
            document.getElementById('checked-in-count').textContent = eventData.scanned_tickets;
            document.getElementById('total-guests-count').textContent = eventData.total_tickets;
            document.getElementById('checked-in-percentage').textContent = eventData.percentage + '%';
            document.getElementById('checked-in-progressbar').style.width = eventData.percentage + '%';
        }
    }
    
    // Run initially
    updateCheckedInWidget();

    // Trigger Camera
    document.getElementById('camera-placeholder').addEventListener('click', startCamera);

    function startCamera() {
        if (scanning) return;
        html5QrCode = new Html5Qrcode('reader');
        document.getElementById('camera-placeholder').classList.add('hidden');
        document.getElementById('reader').style.display = 'block';
        document.getElementById('scan-reticle').style.display = 'flex';

        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 15, qrbox: (width, height) => {
                const size = Math.min(width, height) * 0.65;
                return { width: size, height: size };
            }, aspectRatio: 1.0 },
            onScanSuccess,
            function() {}
        ).then(() => {
            scanning = true;
        }).catch(err => {
            document.getElementById('camera-placeholder').classList.remove('hidden');
            document.getElementById('reader').style.display = 'none';
            document.getElementById('scan-reticle').style.display = 'none';
            updateStatusBadge('error', 'Caméra bloquée');
        });
    }

    function stopCamera() {
        if (!html5QrCode || !scanning) return;
        html5QrCode.stop().then(() => {
            scanning = false;
            document.getElementById('camera-placeholder').classList.remove('hidden');
            document.getElementById('reader').style.display = 'none';
            document.getElementById('scan-reticle').style.display = 'none';
        }).catch(() => {});
    }

    // Success scan hook
    function onScanSuccess(code) {
        if (cooldown) return;
        cooldown = true;
        
        validateTicketCode(code);
    }

    // Validate a scanned or typed code
    function validateTicketCode(code) {
        updateStatusBadge('info', 'Validation en cours...');
        
        const form = new FormData();
        form.append('_token', csrfToken);
        form.append('code', code);
        form.append('evenement_id', document.getElementById('event-selector').value);

        fetch(verifyUrl, { method: 'POST', body: form })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'valid') {
                    vibrate([200]);
                    updateStatusBadge('valid', 'ACCÈS AUTORISÉ : ' + data.ticket.billet_type);
                    
                    // Update stats locally
                    incrementStats();
                    
                    // Prepend to UI lists
                    prependScanToList(data.ticket, 'valid');
                    
                } else if (data.status === 'already_scanned') {
                    vibrate([100, 50, 100]);
                    updateStatusBadge('duplicate', 'DOUBLON : DÉJÀ SCANNÉ');
                    
                    prependScanToList(data.ticket, 'duplicate');
                } else {
                    vibrate([300]);
                    updateStatusBadge('invalid', 'BILLET INVALIDE / ERRONE');
                }
                
                // Allow scanning next code after 2.5s
                setTimeout(() => { cooldown = false; }, 2500);
            })
            .catch(() => {
                vibrate([300]);
                updateStatusBadge('error', 'ERREUR RÉSEAU');
                setTimeout(() => { cooldown = false; }, 2500);
            });
    }

    // UI Updates
    function updateStatusBadge(type, text) {
        const badge = document.getElementById('status-badge');
        let iconClass = 'fa-ticket-alt';
        let bgClass = 'bg-indigo-950 text-indigo-200';

        if (type === 'valid') {
            bgClass = 'bg-emerald-500 text-white';
            iconClass = 'fa-check-circle';
        } else if (type === 'duplicate') {
            bgClass = 'bg-amber-500 text-white';
            iconClass = 'fa-exclamation-triangle';
        } else if (type === 'invalid' || type === 'error') {
            bgClass = 'bg-rose-600 text-white';
            iconClass = 'fa-times-circle';
        } else if (type === 'info') {
            bgClass = 'bg-indigo-600 text-white';
            iconClass = 'fa-sync-alt fa-spin';
        }

        badge.className = `inline-flex items-center space-x-2 px-4 py-2 ${bgClass} text-xs font-extrabold rounded-full uppercase tracking-wider shadow-sm transition-all`;
        badge.innerHTML = `<i class="fas ${iconClass}"></i> <span>${text}</span>`;
    }

    function incrementStats() {
        const activeId = document.getElementById('event-selector').value;
        const evIndex = events.findIndex(e => e.id == activeId);
        
        if (evIndex !== -1) {
            events[evIndex].scanned_tickets += 1;
            events[evIndex].percentage = events[evIndex].total_tickets > 0 
                ? ((events[evIndex].scanned_tickets / events[evIndex].total_tickets) * 100).toFixed(1)
                : 0;
            
            updateCheckedInWidget();
        }
    }

    function prependScanToList(ticket, status) {
        const list = document.getElementById('recent-scans-list');
        const emptyState = document.getElementById('no-recent-scans');
        
        if (emptyState) emptyState.remove();

        const badgeText = status === 'valid' ? 'Valid' : 'Duplicate';
        const badgeColor = status === 'valid' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-600 border-red-100';
        const iconColor = status === 'valid' ? 'text-emerald-600 bg-emerald-50' : 'text-red-500 bg-red-50';
        const icon = status === 'valid' ? 'fa-check-circle' : 'fa-exclamation-circle';

        const row = `
            <div class="bg-white border border-slate-100 rounded-2xl p-3 flex items-center justify-between shadow-xs animate__animated animate__fadeInDown">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-full ${iconColor} flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="min-w-0">
                        <h5 class="text-xs font-bold text-slate-800 truncate leading-snug">${ticket.buyer_name || ticket.buyer_email}</h5>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Ticket #${ticket.code}</p>
                    </div>
                </div>
                <div class="text-right space-y-1">
                    <span class="text-[9px] text-slate-400 font-bold block">Just Now</span>
                    <span class="px-2 py-0.5 rounded-md text-[8px] font-extrabold uppercase ${badgeColor} border">
                        ${badgeText}
                    </span>
                </div>
            </div>
        `;

        // Prepend to recent list
        list.innerHTML = row + list.innerHTML;

        // Prepend to history tab list
        const historyList = document.getElementById('full-history-list');
        const historyRow = `
            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full ${iconColor} flex items-center justify-center text-base">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800 leading-snug">${ticket.buyer_name || ticket.buyer_email}</h5>
                        <p class="text-[10px] text-slate-400 font-semibold">Ticket #${ticket.code} · ${ticket.billet_type}</p>
                        <p class="text-[9px] text-indigo-500 font-bold leading-none mt-1">${ticket.evenement}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-500 font-bold block">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    <span class="text-[9px] text-slate-400 font-semibold block">Aujourd'hui</span>
                </div>
            </div>
        `;
        historyList.innerHTML = historyRow + historyList.innerHTML;
    }

    // Manual Validation Form Hook
    document.getElementById('manual-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('manual-code-input');
        const code = input.value.trim();
        
        if (code) {
            validateTicketCode(code);
            input.value = '';
        }
    });

    // Mobile vibration fallback
    function vibrate(pattern) {
        if (navigator.vibrate) {
            navigator.vibrate(pattern);
        }
    }
})();
</script>
@endsection
