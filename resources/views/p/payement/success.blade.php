@extends('layouts.base')
@section('title', '| Paiement confirmé !')

@section('content')
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">

            {{-- Bouton flèche retour à l'accueil --}}
            <div class="mb-4 no-print d-flex align-items-center justify-content-between">
                <a href="{{ route('index') }}" class="btn d-inline-flex align-items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition-all text-decoration-none shadow-xs">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            </div>

            {{-- En-tête succès --}}
            <div class="text-center mb-4 no-print" style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;">
                <div style="width:54px; height:54px; background:#dcfce7; border:2px solid #86efac; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-bottom:.75rem; box-shadow:0 4px 12px rgba(34,197,94,0.15);">
                    <i class="fas fa-check" style="font-size:1.3rem; color:#16a34a;"></i>
                </div>
                <h1 style="color:#0f172a; font-size:1.4rem; font-weight:800; margin:0 0 .35rem 0; font-family:'Outfit', sans-serif; display:block;">
                    Paiement confirmé !
                </h1>
                <p style="color:#64748b; font-size:.88rem; margin:0; font-weight:500; display:block; max-width:520px;">
                    Votre billet a été généré avec succès. Conservez-le précieusement pour l'entrée.
                </p>
            </div>

            {{-- ═══════════════════ TICKET PASS COMPACT (Format 10cm x 5cm) ═══════════════════ --}}
            @php
                $date      = \Carbon\Carbon::parse($evenement->date);
                $moisFr    = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'];
                $photoUrl  = !empty($evenement->photo)
                    ? asset('storage/evenement/photo/' . $evenement->photo)
                    : null;
                $codesList = isset($codes) && count($codes) > 0 ? $codes : [$code];
            @endphp

            <div id="ticket-wrapper">
            @foreach($codesList as $idx => $currentCode)
            <div class="ticket-wrapper-item" style="max-width:620px; margin:0 auto 2rem; filter:drop-shadow(0 15px 35px rgba(0,0,0,0.25)); page-break-after: always;">

                <div class="ticket-container" style="display:flex; border-radius:18px; overflow:hidden; background:#1e1154;">

                    {{-- ══ Partie gauche (Infos Événement avec photo en fond fondu) ══ --}}
                    <div class="ticket-left" style="flex:1; background:#1e1154; padding:1.5rem 1.6rem; display:flex; flex-direction:column; justify-content:space-between; min-width:0; position:relative; overflow:hidden;">

                        @if($photoUrl)
                        {{-- Image de l'événement en arrière-plan plus visible --}}
                        <img src="{{ $photoUrl }}"
                             alt=""
                             style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; object-position:center; opacity:0.65; filter:contrast(1.1) saturate(1.15); pointer-events:none; z-index:0;">
                        
                        {{-- Overlay subtil de lisibilité --}}
                        <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(15,23,42,0.55) 0%, rgba(30,17,84,0.72) 100%); z-index:1; pointer-events:none;"></div>
                        @endif

                        {{-- Décos d'arrière plan --}}
                        <div style="position:absolute; bottom:-25px; right:-15px; width:90px; height:90px; background:#7c3aed; border-radius:50%; opacity:.25; pointer-events:none; z-index:1;"></div>
                        <div style="position:absolute; top:-20px; right:40px; width:40px; height:40px; background:#22c55e; border-radius:50%; opacity:.25; pointer-events:none; z-index:1;"></div>

                        <div style="position:relative; z-index:2;">
                            {{-- Badge Catégorie --}}
                            <div style="margin-bottom:.75rem;">
                                <span style="display:inline-block; background:#f59e0b; color:#0f172a;
                                             font-size:.58rem; font-weight:900; letter-spacing:.12em;
                                             text-transform:uppercase; padding:.28rem .8rem; border-radius:.35rem;">
                                    {{ strtoupper($evenement->categorie ?? 'ÉVÉNEMENT') }}
                                </span>
                            </div>

                            {{-- Titre Événement --}}
                            <h2 style="color:#ffffff; font-size:clamp(1.3rem,2.5vw,1.75rem); font-weight:900;
                                       line-height:1.2; margin-bottom:1rem;
                                       text-shadow:0 2px 10px rgba(0,0,0,0.4); font-family: 'Outfit', sans-serif;">
                                {{ strtoupper($evenement->titre) }}
                            </h2>

                            {{-- Date + Heure --}}
                            <div style="display:flex; flex-wrap:wrap; gap:.45rem; margin-bottom:.85rem;">
                                <div style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15);
                                             border-radius:.45rem; padding:.3rem .75rem; text-align:center;">
                                    <span style="color:#ffffff; font-size:1.05rem; font-weight:900; display:block; line-height:1.1;">{{ $date->format('d') }}</span>
                                    <span style="color:rgba(255,255,255,0.6); font-size:.54rem; font-weight:800;
                                                 text-transform:uppercase; letter-spacing:.07em;">
                                        {{ $moisFr[$date->month - 1] }} {{ $date->format('Y') }}
                                    </span>
                                </div>
                                <div style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15);
                                             border-radius:.45rem; padding:.3rem .75rem;
                                             display:flex; align-items:center; gap:.4rem;">
                                    <i class="far fa-clock" style="color:rgba(255,255,255,0.6); font-size:.72rem;"></i>
                                    <span style="color:#ffffff; font-size:.82rem; font-weight:700;">
                                        {{ \Carbon\Carbon::parse($evenement->start_heure)->format('H:i') }}
                                        @if($evenement->end_heure) — {{ \Carbon\Carbon::parse($evenement->end_heure)->format('H:i') }}@endif
                                    </span>
                                </div>
                            </div>

                            {{-- Lieu --}}
                            <div style="display:flex; align-items:center; gap:.4rem; margin-bottom:.9rem;">
                                <i class="fas fa-map-marker-alt" style="color:#f472b6; font-size:.78rem;"></i>
                                <span style="color:#ffffff; font-size:.84rem; font-weight:700;">{{ $evenement->lieu }}</span>
                            </div>
                        </div>

                        <div style="position:relative; z-index:2;">
                            {{-- Séparateur --}}
                            <div style="border-top:1px solid rgba(255,255,255,0.1); margin-bottom:.85rem;"></div>

                            {{-- Footer : Type Billet / Organisateur / Prix --}}
                            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem;">
                                <div>
                                    <span style="color:rgba(255,255,255,0.45); font-size:.52rem; font-weight:800;
                                                 text-transform:uppercase; letter-spacing:.1em; display:block;">Type de billet</span>
                                    <span style="display:inline-block; background:#16a34a; color:#ffffff;
                                                 font-size:.7rem; font-weight:800; padding:.2rem .65rem;
                                                 border-radius:9999px; margin-top:.15rem;">
                                        {{ $billet->type }} ({{ $idx + 1 }}/{{ count($codesList) }})
                                    </span>
                                </div>

                                @if($evenement->nom_proprietaire)
                                <div style="display:flex; align-items:center; gap:.3rem;">
                                    <i class="fas fa-user-circle" style="color:rgba(255,255,255,0.4); font-size:.8rem;"></i>
                                    <span style="color:rgba(255,255,255,0.5); font-size:.65rem;">
                                        <strong style="color:rgba(255,255,255,0.85);">{{ $evenement->nom_proprietaire }}</strong>
                                    </span>
                                </div>
                                @endif

                                <div style="text-align:right;">
                                    <span style="color:rgba(255,255,255,0.45); font-size:.52rem; font-weight:800;
                                                 text-transform:uppercase; letter-spacing:.1em; display:block;">Montant payé</span>
                                    <span style="color:#f59e0b; font-size:1rem; font-weight:900;">
                                        {{ number_format($billet->prix, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ══ Partie séparateur perforé ══ --}}
                    <div class="ticket-separator" style="width:26px; background:#1e1154; position:relative; flex-shrink:0;
                                display:flex; align-items:stretch; justify-content:center;">
                        <div class="perf-top" style="position:absolute; top:-1px; left:1px; width:24px; height:14px;
                                    background:#ffffff; border-radius:0 0 14px 14px; z-index:3;"></div>
                        <div class="perf-bottom" style="position:absolute; bottom:-1px; left:1px; width:24px; height:14px;
                                    background:#ffffff; border-radius:14px 14px 0 0; z-index:3;"></div>
                        <div class="dashed-line" style="width:0; margin:14px 0; border-left:2px dashed rgba(255,255,255,0.2); align-self:stretch;"></div>
                    </div>

                    {{-- ══ Talon QR Code ══ --}}
                    <div class="ticket-right" style="width:190px; background:#1e1154; padding:1.4rem 1rem;
                                display:flex; flex-direction:column; align-items:center;
                                justify-content:center; gap:1rem; flex-shrink:0;
                                position:relative; overflow:hidden;">

                        {{-- Déco --}}
                        <div style="position:absolute; bottom:-30px; right:-30px; width:90px; height:90px;
                                    background:#7c3aed; border-radius:50%; opacity:.3; pointer-events:none;"></div>

                        {{-- Label app --}}
                        <span style="color:rgba(255,255,255,0.3); font-size:.55rem; font-weight:900;
                                     letter-spacing:.18em; text-transform:uppercase;">TGEVENT</span>

                        {{-- QR Code --}}
                        <div style="background:#ffffff; padding:7px; border-radius:12px;
                                    line-height:0; position:relative; z-index:1; flex-shrink:0; box-shadow:0 6px 18px rgba(0,0,0,0.3);">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($currentCode) }}&size=135x135&color=1e1154&bgcolor=ffffff&margin=2&qzone=1"
                                 alt="{{ $currentCode }}"
                                 data-no-cover
                                 width="135"
                                 height="135"
                                 style="display:block !important; width:135px !important; height:135px !important;
                                        position:static !important; inset:auto !important;
                                        object-fit:none !important; max-width:none !important;
                                        border-radius:0 !important;">
                        </div>

                        {{-- Code billet --}}
                        <div style="text-align:center; position:relative; z-index:1;">
                            <span style="color:rgba(255,255,255,0.45); font-size:.52rem; font-weight:800;
                                         text-transform:uppercase; letter-spacing:.12em; display:block;
                                         margin-bottom:.25rem;">Code billet</span>
                            <span style="color:#ffffff; font-size:.85rem; font-weight:900;
                                         letter-spacing:.07em; display:block;">{{ $currentCode }}</span>
                        </div>

                        {{-- Ticket ID vertical --}}
                        <div style="position:absolute; right:.4rem; top:50%; transform:translateY(-50%);
                                    writing-mode:vertical-rl; text-orientation:mixed;">
                            <span style="color:rgba(255,255,255,0.15); font-size:.45rem;
                                         letter-spacing:.04em; white-space:nowrap;">
                                TICKET ID: {{ $currentCode }}-{{ substr(md5($session->id ?? ''), 0, 6) }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
            </div>
            {{-- ═══════════ FIN TICKET PASS COMPACT ═══════════ --}}


            {{-- Actions Téléchargement / Impression / Accueil (Exclus du Ticket) --}}
            <div class="d-flex flex-wrap gap-3 justify-content-center mt-4 no-print">
                <a href="{{ route('index') }}"
                   class="btn d-inline-flex align-items-center gap-2 px-4 py-2.5 shadow-sm"
                   style="border:1.5px solid #e2e8f0; color:#475569; background:#ffffff;
                          border-radius:.75rem; font-weight:700; font-size:.9rem; text-decoration:none;">
                    <i class="fas fa-arrow-left"></i> Page d'accueil
                </a>
                <button onclick="downloadTicket()"
                        class="btn d-inline-flex align-items-center gap-2 px-4 py-2.5 shadow-md"
                        style="background:#16a34a; border:none; color:#ffffff;
                               border-radius:.75rem; font-weight:700; font-size:.9rem;">
                    <i class="fas fa-download"></i> Télécharger mon billet (PNG)
                </button>
                <button onclick="window.print()"
                        class="btn d-inline-flex align-items-center gap-2 px-4 py-2.5 shadow-sm"
                        style="border:1.5px solid #4f46e5; color:#4f46e5; background:transparent;
                               border-radius:.75rem; font-weight:700; font-size:.9rem;">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>

        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadTicket() {
    html2canvas(document.getElementById('ticket-wrapper'), {
        scale: 2.5,
        useCORS: true,
        allowTaint: true,
        backgroundColor: null
    }).then(function(canvas) {
        var link = document.createElement('a');
        link.download = 'billet-{{ $code }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}
</script>

<style>
.no-print {
    display: flex !important;
}

@media print {
    nav, .navbar, header, .btn, footer, .no-print { 
        display: none !important; 
    }
    body { 
        background: #ffffff !important; 
    }
    #ticket-wrapper { 
        filter: none !important; 
        max-width: 100% !important; 
    }
    main { 
        padding: 0 !important; 
    }
    .container { 
        max-width: 100% !important; 
        padding: 0 !important; 
    }
}

@media (max-width: 767px) {
    .ticket-container {
        flex-direction: column !important;
    }
    .ticket-left {
        width: 100% !important;
    }
    .ticket-separator {
        width: 100% !important;
        height: 26px !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .perf-top {
        top: 1px !important;
        left: -1px !important;
        width: 14px !important;
        height: 24px !important;
        border-radius: 0 14px 14px 0 !important;
    }
    .perf-bottom {
        bottom: 1px !important;
        right: -1px !important;
        left: auto !important;
        width: 14px !important;
        height: 24px !important;
        border-radius: 14px 0 0 14px !important;
    }
    .dashed-line {
        width: calc(100% - 26px) !important;
        height: 0 !important;
        border-top: 2px dashed rgba(255,255,255,0.2) !important;
        border-left: none !important;
        margin: 0 13px !important;
        align-self: center !important;
    }
    .ticket-right {
        width: 100% !important;
        padding: 1.2rem !important;
    }
}
</style>
@endsection
