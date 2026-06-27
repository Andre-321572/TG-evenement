@extends('layouts.scanner')

@section('content')

<style>
/* ── Override page background ── */
body { background: #07071a !important; color: #f1f5f9 !important; }
.navbar, nav, footer { display: none !important; }

/* ── Scanner shell ── */
#scanner-app {
    min-height: 100vh;
    background: #07071a;
    display: flex;
    flex-direction: column;
    max-width: 480px;
    margin: 0 auto;
    padding: 0;
    font-family: 'Outfit', sans-serif;
}

/* ── Top bar ── */
#top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem .8rem;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

/* ── Camera container ── */
#camera-wrap {
    position: relative;
    width: 100%;
    background: #000;
    overflow: hidden;
    flex-shrink: 0;
}
#reader { width: 100% !important; }
#reader video {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
    position: static !important;
    inset: auto !important;
    max-width: none !important;
}
#reader canvas,
#reader img { display: none !important; }

/* ── Scan reticle ── */
#scan-reticle {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    z-index: 10;
}
.reticle-box {
    position: relative;
    width: 200px;
    height: 200px;
}
.reticle-box::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(79,70,229,0.08);
    border-radius: 4px;
    animation: pulse-bg 2s ease-in-out infinite;
}
@keyframes pulse-bg {
    0%,100% { background: rgba(79,70,229,0.08); }
    50%      { background: rgba(79,70,229,0.18); }
}
.corner {
    position: absolute;
    width: 26px;
    height: 26px;
}
.corner-tl { top:0;    left:0;  border-top: 3px solid #4f46e5; border-left: 3px solid #4f46e5;  border-radius: 4px 0 0 0; }
.corner-tr { top:0;    right:0; border-top: 3px solid #4f46e5; border-right: 3px solid #4f46e5; border-radius: 0 4px 0 0; }
.corner-bl { bottom:0; left:0;  border-bottom: 3px solid #4f46e5; border-left: 3px solid #4f46e5;  border-radius: 0 0 0 4px; }
.corner-br { bottom:0; right:0; border-bottom: 3px solid #4f46e5; border-right: 3px solid #4f46e5; border-radius: 0 0 4px 0; }

/* Scan line */
.scan-line {
    position: absolute;
    left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #4f46e5, #818cf8, #4f46e5, transparent);
    top: 0;
    animation: scan-sweep 2s linear infinite;
    opacity: 0;
}
@keyframes scan-sweep {
    0%   { top:0%;   opacity:1; }
    90%  { top:100%; opacity:1; }
    100% { top:100%; opacity:0; }
}
.scan-line.active { opacity: 1; }

/* Reticle corners — active glow */
.scanning .corner { box-shadow: 0 0 12px rgba(79,70,229,.7); }

/* ── Camera placeholder (before start) ── */
#camera-placeholder {
    width: 100%;
    height: 280px;
    background: #0d0d2b;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .75rem;
}

/* ── Notification overlay ── */
#notif-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    backdrop-filter: blur(4px);
}
#notif-overlay.show {
    display: flex;
    animation: fadeIn .25s ease;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
#notif-card {
    width: 100%;
    max-width: 380px;
    border-radius: 20px;
    padding: 2rem 1.75rem;
    text-align: center;
    animation: slideUp .3s cubic-bezier(.22,1,.36,1);
}
@keyframes slideUp {
    from { transform: translateY(40px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}
#notif-card.valid   { background: #052e16; border: 2px solid #16a34a; }
#notif-card.invalid { background: #2d0a0a; border: 2px solid #dc2626; }
#notif-card.used    { background: #1c1108; border: 2px solid #d97706; }

.notif-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.2rem;
    font-size: 2rem;
}
.notif-icon.valid   { background: rgba(22,163,74,.15);  color: #4ade80; border: 2px solid rgba(22,163,74,.3); }
.notif-icon.invalid { background: rgba(220,38,38,.15);  color: #f87171; border: 2px solid rgba(220,38,38,.3); }
.notif-icon.used    { background: rgba(217,119,6,.15);  color: #fbbf24; border: 2px solid rgba(217,119,6,.3);  }

/* ── Bottom panel ── */
#bottom-panel {
    flex: 1;
    padding: 1.2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* ── Buttons ── */
.btn-scan-start {
    width: 100%;
    padding: 1rem;
    border: none;
    border-radius: 14px;
    background: #4f46e5;
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    letter-spacing: .02em;
    transition: background .2s, transform .1s;
}
.btn-scan-start:hover  { background: #4338ca; }
.btn-scan-start:active { transform: scale(.98); }
.btn-scan-stop {
    width: 100%;
    padding: 1rem;
    border: none;
    border-radius: 14px;
    background: rgba(220,38,38,.15);
    color: #f87171;
    border: 1px solid rgba(220,38,38,.25);
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s;
}
.btn-scan-stop:hover { background: rgba(220,38,38,.25); }

/* ── Event select ── */
#evenement-select {
    width: 100%;
    background: rgba(255,255,255,0.05);
    color: #e2e8f0;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: .75rem 1rem;
    font-size: .9rem;
    font-family: 'Outfit', sans-serif;
    outline: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
}
#evenement-select option { background: #1e293b; color: #e2e8f0; }

/* ── Stats chip ── */
.stats-chip {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(79,70,229,0.12);
    border: 1px solid rgba(79,70,229,0.2);
    border-radius: 9999px;
    padding: .4rem .9rem;
    font-size: .82rem;
    color: #a5b4fc;
    font-weight: 600;
}

/* ── Ticket info grid ── */
.ticket-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .6rem;
    margin-top: 1.2rem;
    text-align: left;
}
.ticket-field { }
.ticket-label {
    font-size: .6rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .1em;
    display: block;
    margin-bottom: .2rem;
    opacity: .5;
}
.ticket-value {
    font-size: .85rem;
    font-weight: 700;
    color: #f1f5f9;
}

/* ── Idle state ── */
#idle-state {
    text-align: center;
    padding: 1.5rem 1rem;
    border-radius: 14px;
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.06);
}

/* Progress bar (auto-dismiss) */
#notif-progress {
    height: 3px;
    border-radius: 9999px;
    margin-top: 1.5rem;
    overflow: hidden;
    background: rgba(255,255,255,.1);
}
#notif-bar {
    height: 100%;
    width: 100%;
    border-radius: 9999px;
    transition: none;
}
#notif-bar.valid   { background: #4ade80; }
#notif-bar.invalid { background: #f87171; }
#notif-bar.used    { background: #fbbf24; }
</style>

{{-- ══════════════════ NOTIFICATION OVERLAY ══════════════════ --}}
<div id="notif-overlay">
    <div id="notif-overlay-bg" style="position:absolute;inset:0;"></div>
    <div id="notif-card" style="position:relative; z-index:1;">

        <div id="notif-icon" class="notif-icon">
            <span id="notif-icon-char"></span>
        </div>

        <p id="notif-title"   style="font-size:1.3rem; font-weight:900; color:#f1f5f9; margin:0 0 .4rem;"></p>
        <p id="notif-message" style="font-size:.85rem; color:#94a3b8; margin:0;"></p>

        <div id="notif-ticket" class="ticket-grid" style="display:none;">
            <div class="ticket-field" style="grid-column:1/-1;">
                <span class="ticket-label" style="color:inherit;">Événement</span>
                <span class="ticket-value" id="nt-event"></span>
            </div>
            <div class="ticket-field">
                <span class="ticket-label" style="color:inherit;">Type billet</span>
                <span class="ticket-value" id="nt-type"></span>
            </div>
            <div class="ticket-field">
                <span class="ticket-label" style="color:inherit;">Code</span>
                <span class="ticket-value" id="nt-code" style="font-size:.78rem; letter-spacing:.06em;"></span>
            </div>
            <div class="ticket-field">
                <span class="ticket-label" style="color:inherit;">Acheteur</span>
                <span class="ticket-value" id="nt-name"></span>
            </div>
            <div class="ticket-field">
                <span class="ticket-label" style="color:inherit;">Date</span>
                <span class="ticket-value" id="nt-date"></span>
            </div>
        </div>

        <div id="notif-progress"><div id="notif-bar"></div></div>

        <button onclick="closeNotif()"
                style="margin-top:1rem; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
                       color:#94a3b8; border-radius:10px; padding:.5rem 1.5rem; font-size:.82rem;
                       font-weight:600; cursor:pointer; width:100%;">
            Scanner le suivant
        </button>
    </div>
</div>

{{-- ══════════════════ SCANNER APP ══════════════════ --}}
<div id="scanner-app">

    {{-- Top bar --}}
    <div id="top-bar">
        <div>
            <span style="color:rgba(255,255,255,.3); font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.15em; display:block;">TGEvent</span>
            <span style="color:#f1f5f9; font-size:.95rem; font-weight:700;">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
        </div>
        <div class="stats-chip">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
            <span id="scan-count">0</span> scannés
        </div>
    </div>

    {{-- Camera zone --}}
    <div id="camera-wrap">
        <div id="camera-placeholder">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(79,70,229,.4)" stroke-width="1.5">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
            <span style="color:rgba(255,255,255,.2); font-size:.82rem;">Appuyez sur "Démarrer" pour activer la caméra</span>
        </div>
        <div id="reader" style="display:none;"></div>

        {{-- Reticle (visible when scanning) --}}
        <div id="scan-reticle" style="display:none;">
            <div class="reticle-box" id="reticle-box">
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>
                <div class="scan-line" id="scan-line"></div>
            </div>
        </div>
    </div>

    {{-- Bottom panel --}}
    <div id="bottom-panel">

        {{-- Event select --}}
        <div>
            <label style="color:rgba(255,255,255,.35); font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.12em; display:block; margin-bottom:.5rem;">
                Événement
            </label>
            <select id="evenement-select">
                <option value="">— Tous les événements —</option>
                @foreach($evenements as $ev)
                <option value="{{ $ev->id }}">{{ $ev->titre }} — {{ \Carbon\Carbon::parse($ev->date)->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>

        {{-- Button --}}
        <div id="btn-container">
            <button class="btn-scan-start" id="btn-start">
                Démarrer la caméra
            </button>
        </div>

        {{-- Idle status --}}
        <div id="idle-state">
            <p style="color:rgba(255,255,255,.25); font-size:.85rem; margin:0;">En attente de scan...</p>
        </div>

    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    var html5QrCode = null;
    var scanning    = false;
    var cooldown    = false;
    var dismissTimer = null;

    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    var verifyUrl = '{{ route("scanner.verify") }}';
    var statsUrl  = '{{ route("scanner.stats") }}';

    /* ── Stats ── */
    function fetchStats() {
        var evId = document.getElementById('evenement-select').value;
        fetch(statsUrl + (evId ? '?evenement_id=' + evId : ''))
            .then(function(r){ return r.json(); })
            .then(function(d){ document.getElementById('scan-count').textContent = d.count; })
            .catch(function(){});
    }
    fetchStats();
    document.getElementById('evenement-select').addEventListener('change', fetchStats);

    /* ── Start camera ── */
    document.getElementById('btn-start').addEventListener('click', startCamera);

    function startCamera() {
        if (scanning) return;
        html5QrCode = new Html5Qrcode('reader');
        document.getElementById('camera-placeholder').style.display = 'none';
        document.getElementById('reader').style.display = 'block';
        document.getElementById('scan-reticle').style.display = 'flex';
        document.getElementById('scan-line').classList.add('active');

        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 200, height: 200 }, aspectRatio: 1.2 },
            onScanSuccess,
            function() {}
        ).then(function() {
            scanning = true;
            document.getElementById('reticle-box').classList.add('scanning');
            document.getElementById('btn-container').innerHTML =
                '<button class="btn-scan-stop" id="btn-stop">Arrêter la caméra</button>';
            document.getElementById('btn-stop').addEventListener('click', stopCamera);
        }).catch(function(err) {
            document.getElementById('camera-placeholder').style.display = 'flex';
            document.getElementById('reader').style.display = 'none';
            document.getElementById('scan-reticle').style.display = 'none';
            showNotif('invalid', 'Caméra inaccessible', 'Vérifiez les permissions de votre navigateur.', null);
        });
    }

    function stopCamera() {
        if (!html5QrCode || !scanning) return;
        html5QrCode.stop().then(function() {
            scanning = false;
            document.getElementById('camera-placeholder').style.display = 'flex';
            document.getElementById('reader').style.display = 'none';
            document.getElementById('scan-reticle').style.display = 'none';
            document.getElementById('btn-container').innerHTML =
                '<button class="btn-scan-start" id="btn-start">Démarrer la caméra</button>';
            document.getElementById('btn-start').addEventListener('click', startCamera);
        }).catch(function(){});
    }

    /* ── Scan callback ── */
    function onScanSuccess(code) {
        if (cooldown) return;
        cooldown = true;

        var evId = document.getElementById('evenement-select').value;
        var body = new FormData();
        body.append('_token', csrfToken);
        body.append('code', code);
        if (evId) body.append('evenement_id', evId);

        fetch(verifyUrl, { method: 'POST', body: body })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                if (data.status === 'valid') {
                    vibrate([200]);
                    showNotif('valid', 'Accès autorisé !', 'Billet valide — bienvenue !', data.ticket);
                    fetchStats();
                } else if (data.status === 'already_scanned') {
                    vibrate([100, 60, 100]);
                    showNotif('used', 'Déjà scanné', data.message, data.ticket);
                } else {
                    vibrate([80, 40, 80, 40, 80]);
                    showNotif('invalid', 'Billet invalide', data.message || 'Ce code n\'est pas reconnu.', null);
                }
            })
            .catch(function() {
                vibrate([100, 40, 100]);
                showNotif('invalid', 'Erreur réseau', 'Vérifiez votre connexion et réessayez.', null);
            });
    }

    /* ── Notification ── */
    function showNotif(type, title, message, ticket) {
        if (dismissTimer) clearTimeout(dismissTimer);

        var overlay  = document.getElementById('notif-overlay');
        var card     = document.getElementById('notif-card');
        var bg       = document.getElementById('notif-overlay-bg');
        var icon     = document.getElementById('notif-icon');
        var iconChar = document.getElementById('notif-icon-char');
        var bar      = document.getElementById('notif-bar');

        /* Colors */
        var bgColor = type === 'valid'   ? 'rgba(5,46,22,.92)'
                    : type === 'used'    ? 'rgba(28,17,8,.92)'
                    : 'rgba(45,10,10,.92)';

        bg.style.background = bgColor;
        card.className = 'notif-card ' + type;
        card.setAttribute('id','notif-card');

        /* Icon */
        icon.className = 'notif-icon ' + type;
        iconChar.innerHTML = type === 'valid'
            ? '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>'
            : type === 'used'
            ? '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
            : '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

        document.getElementById('notif-title').textContent   = title;
        document.getElementById('notif-message').textContent = message;

        /* Ticket details */
        var ticketDiv = document.getElementById('notif-ticket');
        if (ticket) {
            ticketDiv.style.display = 'grid';
            document.getElementById('nt-event').textContent = ticket.evenement || '';
            document.getElementById('nt-type').textContent  = ticket.billet_type || '';
            document.getElementById('nt-code').textContent  = ticket.code || '';
            document.getElementById('nt-name').textContent  = ticket.buyer_name || ticket.buyer_email || '';
            document.getElementById('nt-date').textContent  = ticket.date || '';
        } else {
            ticketDiv.style.display = 'none';
        }

        /* Progress bar */
        bar.className = 'notif-bar ' + type;
        bar.setAttribute('id','notif-bar');
        bar.style.transition = 'none';
        bar.style.width = '100%';

        /* Show */
        overlay.classList.add('show');

        /* Animate progress bar */
        setTimeout(function() {
            bar.style.transition = 'width 3.5s linear';
            bar.style.width = '0%';
        }, 50);

        /* Auto-dismiss after 3.5s */
        dismissTimer = setTimeout(function() {
            closeNotif();
        }, 3550);
    }

    window.closeNotif = function() {
        if (dismissTimer) clearTimeout(dismissTimer);
        document.getElementById('notif-overlay').classList.remove('show');
        setTimeout(function() { cooldown = false; }, 500);
    };

    /* ── Vibration ── */
    function vibrate(pattern) {
        if (navigator.vibrate) navigator.vibrate(pattern);
    }
})();
</script>
@endsection
