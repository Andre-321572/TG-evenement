@extends('layouts.base')
@section('title', '| Paiement confirmé !')

@section('content')
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

            {{-- En-tête succès --}}
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                     style="width:60px; height:60px; background:#dcfce7; border:2px solid #86efac;">
                    <i class="fas fa-check" style="font-size:1.4rem; color:#16a34a;"></i>
                </div>
                <h1 class="fw-bold mb-1" style="color:#1e293b; font-size:1.4rem;">Paiement confirmé !</h1>
                <p style="color:#64748b; font-size:.9rem;">Votre billet a été généré. Conservez-le précieusement pour l'entrée.</p>
            </div>

            {{-- ═══════════════════ TICKET ═══════════════════ --}}
            @php
                $date    = \Carbon\Carbon::parse($evenement->date);
                $moisFr  = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'];
                $photoUrl = $evenement->photo
                    ? asset('storage/evenement/photo/' . $evenement->photo)
                    : null;
            @endphp

            <div id="ticket-wrapper" style="max-width:780px; margin:0 auto 2.5rem; filter:drop-shadow(0 20px 50px rgba(0,0,0,0.28));">
                <div style="display:flex; border-radius:16px; overflow:hidden;">

                    {{-- ══ Partie gauche ══ --}}
                    <div style="flex:1; background:#1e1154; display:flex; flex-direction:column; min-width:0;">

                        {{-- Photo événement --}}
                        <div style="position:relative; height:190px; overflow:hidden; flex-shrink:0;">
                            @if($photoUrl)
                            <img src="{{ $photoUrl }}"
                                 alt="{{ $evenement->titre }}"
                                 style="width:100%; height:100%; object-fit:cover; object-position:center;
                                        display:block; position:static; inset:auto; max-width:none;
                                        border-radius:0;">
                            @else
                            {{-- Fallback gradient quand pas de photo --}}
                            <div style="width:100%; height:100%; background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 50%,#e84393 100%);"></div>
                            @endif
                            {{-- Gradient de fondu bas --}}
                            <div style="position:absolute; bottom:0; left:0; right:0; height:80px;
                                        background:linear-gradient(to bottom, transparent, #1e1154);"></div>
                        </div>

                        {{-- Infos événement --}}
                        <div style="padding:1.2rem 1.5rem 1.4rem; flex:1; position:relative; overflow:hidden;">

                            {{-- Cercles déco (en arrière-plan) --}}
                            <div style="position:absolute; bottom:-20px; right:-10px; width:80px; height:80px; background:#7c3aed; border-radius:50%; opacity:.35;"></div>
                            <div style="position:absolute; bottom:20px; right:50px; width:35px; height:35px; background:#22c55e; border-radius:50%; opacity:.5;"></div>

                            {{-- Badge catégorie --}}
                            <div style="margin-bottom:.85rem;">
                                <span style="display:inline-block; background:#eab308; color:#1a1100;
                                             font-size:.58rem; font-weight:900; letter-spacing:.12em;
                                             text-transform:uppercase; padding:.28rem .85rem; border-radius:.35rem;">
                                    {{ strtoupper($evenement->categorie ?? 'ÉVÉNEMENT') }}
                                </span>
                            </div>

                            {{-- Nom événement --}}
                            <h2 style="color:#ffffff; font-size:clamp(1.4rem,3vw,2rem); font-weight:900;
                                       line-height:1.1; margin-bottom:1.1rem;
                                       text-shadow:0 2px 10px rgba(0,0,0,0.5);">
                                {{ strtoupper($evenement->titre) }}
                            </h2>

                            {{-- Date + Heure --}}
                            <div style="display:flex; flex-wrap:wrap; gap:.45rem; margin-bottom:.9rem;">
                                <div style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15);
                                            border-radius:.4rem; padding:.3rem .8rem; text-align:center;">
                                    <span style="color:#ffffff; font-size:1.1rem; font-weight:900; display:block; line-height:1.1;">{{ $date->format('d') }}</span>
                                    <span style="color:rgba(255,255,255,0.55); font-size:.56rem; font-weight:800;
                                                 text-transform:uppercase; letter-spacing:.07em;">
                                        {{ $moisFr[$date->month - 1] }} {{ $date->format('Y') }}
                                    </span>
                                </div>
                                <div style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15);
                                            border-radius:.4rem; padding:.3rem .8rem;
                                            display:flex; align-items:center; gap:.4rem;">
                                    <i class="fas fa-clock" style="color:rgba(255,255,255,0.45); font-size:.7rem;"></i>
                                    <span style="color:#ffffff; font-size:.82rem; font-weight:700;">
                                        {{ \Carbon\Carbon::parse($evenement->start_heure)->format('H:i') }}
                                        @if($evenement->end_heure) — {{ \Carbon\Carbon::parse($evenement->end_heure)->format('H:i') }}@endif
                                    </span>
                                </div>
                            </div>

                            {{-- Lieu --}}
                            <div style="display:flex; align-items:center; gap:.4rem; margin-bottom:1rem;">
                                <i class="fas fa-map-marker-alt" style="color:#f472b6; font-size:.78rem;"></i>
                                <span style="color:#ffffff; font-size:.85rem; font-weight:700;">{{ $evenement->lieu }}</span>
                            </div>

                            {{-- Séparateur --}}
                            <div style="border-top:1px solid rgba(255,255,255,0.08); margin-bottom:.9rem;"></div>

                            {{-- Footer : type billet · organisateur · montant --}}
                            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.6rem;">
                                <div>
                                    <span style="color:rgba(255,255,255,0.4); font-size:.55rem; font-weight:800;
                                                 text-transform:uppercase; letter-spacing:.1em; display:block;">Type de billet</span>
                                    <span style="display:inline-block; background:#16a34a; color:#ffffff;
                                                 font-size:.72rem; font-weight:800; padding:.2rem .65rem;
                                                 border-radius:9999px; margin-top:.2rem;">
                                        {{ $billet->type }}
                                    </span>
                                </div>

                                @if($evenement->nom_proprietaire)
                                <div style="display:flex; align-items:center; gap:.35rem;">
                                    <i class="fas fa-user-circle" style="color:rgba(255,255,255,0.3); font-size:.8rem;"></i>
                                    <span style="color:rgba(255,255,255,0.45); font-size:.65rem;">
                                        Organisé par <strong style="color:rgba(255,255,255,0.7);">{{ $evenement->nom_proprietaire }}</strong>
                                    </span>
                                </div>
                                @endif

                                <div style="text-align:right;">
                                    <span style="color:rgba(255,255,255,0.4); font-size:.55rem; font-weight:800;
                                                 text-transform:uppercase; letter-spacing:.1em; display:block;">Montant payé</span>
                                    <span style="color:#eab308; font-size:1rem; font-weight:900;">
                                        {{ number_format($billet->prix, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ══ Séparateur perforé ══ --}}
                    <div style="width:28px; background:#1e1154; position:relative; flex-shrink:0;
                                display:flex; align-items:stretch; justify-content:center;">
                        <div style="position:absolute; top:-1px; left:2px; width:24px; height:14px;
                                    background:#f0f7ff; border-radius:0 0 14px 14px; z-index:3;"></div>
                        <div style="position:absolute; bottom:-1px; left:2px; width:24px; height:14px;
                                    background:#f0f7ff; border-radius:14px 14px 0 0; z-index:3;"></div>
                        <div style="width:0; margin:14px 0; border-left:2px dashed rgba(255,255,255,0.16); align-self:stretch;"></div>
                    </div>

                    {{-- ══ Talon QR ══ --}}
                    <div style="width:210px; background:#1e1154; padding:1.8rem 1.2rem;
                                display:flex; flex-direction:column; align-items:center;
                                justify-content:center; gap:1.1rem; flex-shrink:0;
                                position:relative; overflow:hidden;">

                        {{-- Déco --}}
                        <div style="position:absolute; bottom:-30px; right:-30px; width:100px; height:100px;
                                    background:#7c3aed; border-radius:50%; opacity:.3;"></div>

                        {{-- Label app --}}
                        <span style="color:rgba(255,255,255,0.22); font-size:.55rem; font-weight:900;
                                     letter-spacing:.18em; text-transform:uppercase;">TGEVENT</span>

                        {{-- QR Code --}}
                        <div style="background:#ffffff; padding:8px; border-radius:10px;
                                    line-height:0; position:relative; z-index:1; flex-shrink:0;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($code) }}&size=150x150&color=1e1154&bgcolor=ffffff&margin=3&qzone=1"
                                 alt="{{ $code }}"
                                 data-no-cover
                                 width="150"
                                 height="150"
                                 style="display:block !important; width:150px !important; height:150px !important;
                                        position:static !important; inset:auto !important;
                                        object-fit:none !important; max-width:none !important;
                                        border-radius:0 !important;">
                        </div>

                        {{-- Code billet --}}
                        <div style="text-align:center; position:relative; z-index:1;">
                            <span style="color:rgba(255,255,255,0.38); font-size:.52rem; font-weight:800;
                                         text-transform:uppercase; letter-spacing:.12em; display:block;
                                         margin-bottom:.35rem;">Code billet</span>
                            <span style="color:#ffffff; font-size:.88rem; font-weight:900;
                                         letter-spacing:.07em; display:block;">{{ $code }}</span>
                        </div>

                        {{-- Ticket ID vertical --}}
                        <div style="position:absolute; right:.55rem; top:50%; transform:translateY(-50%);
                                    writing-mode:vertical-rl; text-orientation:mixed;">
                            <span style="color:rgba(255,255,255,0.12); font-size:.48rem;
                                         letter-spacing:.04em; white-space:nowrap;">
                                TICKET ID: {{ $code }}-{{ substr(md5($session->id ?? ''), 0, 6) }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
            {{-- ═══════════ FIN TICKET ═══════════ --}}

            {{-- Boutons --}}
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <button onclick="window.print()"
                        class="btn d-inline-flex align-items-center gap-2 px-4 py-2"
                        style="border:1.5px solid #4f46e5; color:#4f46e5; background:transparent;
                               border-radius:.75rem; font-weight:600; font-size:.9rem;">
                    <i class="fas fa-print"></i> Imprimer
                </button>
                <button onclick="downloadTicket()"
                        class="btn d-inline-flex align-items-center gap-2 px-4 py-2"
                        style="border:1.5px solid #16a34a; color:#16a34a; background:transparent;
                               border-radius:.75rem; font-weight:600; font-size:.9rem;">
                    <i class="fas fa-download"></i> Télécharger (PNG)
                </button>
                <a href="{{ route('p.detail', $evenement->id) }}"
                   class="btn d-inline-flex align-items-center gap-2 px-4 py-2"
                   style="border:1.5px solid #e2e8f0; color:#475569; background:transparent;
                          border-radius:.75rem; font-weight:600; font-size:.9rem;">
                    <i class="fas fa-calendar-alt"></i> Voir l'événement
                </a>
                <a href="{{ route('p.evenement') }}"
                   class="btn d-inline-flex align-items-center gap-2 px-4 py-2"
                   style="background:#4f46e5; border:none; color:#ffffff;
                          border-radius:.75rem; font-weight:600; font-size:.9rem;">
                    <i class="fas fa-search"></i> Autres événements
                </a>
            </div>

        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadTicket() {
    html2canvas(document.getElementById('ticket-wrapper'), {
        scale: 2,
        useCORS: true,
        allowTaint: true
    }).then(function(canvas) {
        var link = document.createElement('a');
        link.download = 'billet-{{ $code }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}
</script>

<style>
@media print {
    nav, .navbar, header, .btn, footer, .d-flex.flex-wrap { display:none !important; }
    body { background:#ffffff !important; }
    #ticket-wrapper { filter:none !important; max-width:100% !important; }
    main { padding:0 !important; }
    .container { max-width:100% !important; padding:0 !important; }
}
</style>
@endsection
