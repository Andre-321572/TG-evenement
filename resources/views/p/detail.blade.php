@extends('layouts.base')
@section('title', '| ' . $detail_evenement->titre)

@section('content')

{{-- ═══════════════════════════════════════════════
     HERO — Full bleed, inline styles (bypass overrides)
     ═══════════════════════════════════════════════ --}}
<div style="position:relative; width:100%; height:480px; overflow:hidden;">
    <img src="{{ $detail_evenement->photo ? asset('storage/evenement/photo/' . $detail_evenement->photo) : asset('images/default-event.jpg') }}"
         alt="{{ $detail_evenement->titre }}"
         style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; object-position:center;">
    {{-- Overlay gradient --}}
    <div style="position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.55) 60%, rgba(0,0,0,0.85) 100%);"></div>

    {{-- Contenu sur le hero --}}
    <div style="position:absolute; inset:0; display:flex; flex-direction:column; justify-content:flex-end; padding:2.5rem;">
        <div style="max-width:860px;">
            <span style="display:inline-block; padding:.35rem .9rem; border-radius:.6rem; font-size:.72rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#a5b4fc; background:rgba(99,102,241,0.2); border:1px solid rgba(99,102,241,0.35); margin-bottom:.9rem;">
                {{ ucfirst($detail_evenement->categorie ?? 'Événement') }}
            </span>
            <h1 style="font-size:clamp(1.6rem,4vw,2.6rem); font-weight:900; color:#ffffff; line-height:1.2; margin-bottom:1.2rem; text-shadow:0 2px 16px rgba(0,0,0,0.5);">
                {{ $detail_evenement->titre }}
            </h1>
            <div style="display:flex; flex-wrap:wrap; gap:1.5rem;">
                <span style="display:flex; align-items:center; gap:.5rem; color:rgba(255,255,255,0.8); font-size:.88rem;">
                    <i class="fas fa-calendar-alt" style="color:#818cf8;"></i>
                    {{ \Carbon\Carbon::parse($detail_evenement->date)->format('d M Y') }}
                </span>
                <span style="display:flex; align-items:center; gap:.5rem; color:rgba(255,255,255,0.8); font-size:.88rem;">
                    <i class="fas fa-clock" style="color:#818cf8;"></i>
                    {{ \Carbon\Carbon::parse($detail_evenement->start_heure)->format('H:i') }}
                    @if($detail_evenement->end_heure) — {{ \Carbon\Carbon::parse($detail_evenement->end_heure)->format('H:i') }}@endif
                </span>
                <span style="display:flex; align-items:center; gap:.5rem; color:rgba(255,255,255,0.8); font-size:.88rem;">
                    <i class="fas fa-map-marker-alt" style="color:#818cf8;"></i>
                    {{ $detail_evenement->lieu }}
                </span>
            </div>
        </div>
    </div>

    {{-- Bouton retour --}}
    <div style="position:absolute; top:1.25rem; left:1.5rem;">
        <a href="{{ route('p.evenement') }}" style="display:inline-flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:.75rem; background:rgba(0,0,0,0.35); border:1px solid rgba(255,255,255,0.15); color:#ffffff; font-size:.82rem; font-weight:600; text-decoration:none; backdrop-filter:blur(8px);">
            <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Retour
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     CONTENU PRINCIPAL
     ═══════════════════════════════════════════════ --}}
<main class="container py-5">
    <div class="row g-4 align-items-start">

        {{-- ── Colonne gauche (8/12) ── --}}
        <div class="col-lg-8">

            {{-- Description --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 p-4 p-md-5" style="background:#fff;">
                <h2 class="fw-bold mb-3" style="color:#1e293b; font-size:1.15rem; border-bottom:2px solid #f1f5f9; padding-bottom:.75rem;">
                    À propos de l'événement
                </h2>
                <div style="color:#475569; line-height:1.85; font-size:.95rem; white-space:pre-line;">
                    {{ $detail_evenement->description ?? 'Aucune description disponible.' }}
                </div>
            </div>

            {{-- Vidéo --}}
            @if($detail_evenement->video_url)
            <div class="card border-0 shadow-sm rounded-3 mb-4 p-4 p-md-5" style="background:#fff;">
                <h2 class="fw-bold mb-3" style="color:#1e293b; font-size:1.15rem; border-bottom:2px solid #f1f5f9; padding-bottom:.75rem;">
                    Vidéo de présentation
                </h2>
                <div class="ratio ratio-16x9 rounded-2xl overflow-hidden">
                    <video src="{{ $detail_evenement->video_url }}" controls class="w-full"></video>
                </div>
            </div>
            @endif

            {{-- Localisation --}}
            @if($detail_evenement->lien_google_map)
            <div class="card border-0 shadow-sm rounded-3 mb-4 p-4 p-md-5" style="background:#fff;">
                <h2 class="fw-bold mb-3" style="color:#1e293b; font-size:1.15rem; border-bottom:2px solid #f1f5f9; padding-bottom:.75rem;">
                    Localisation
                </h2>
                <div class="ratio" style="--bs-aspect-ratio:45%;">
                    <iframe src="https://maps.google.com/maps?q={{ urlencode($detail_evenement->lieu) }}&output=embed"
                            style="border:0; border-radius:.75rem; width:100%; height:100%;"
                            allowfullscreen loading="lazy"></iframe>
                </div>
                <a href="{{ $detail_evenement->lien_google_map }}" target="_blank"
                   class="btn mt-3 d-inline-flex align-items-center gap-2 rounded-xl"
                   style="background:#f1f5f9; color:#4f46e5; font-weight:600; font-size:.88rem; border:none;">
                    <i class="fas fa-external-link-alt"></i> Ouvrir dans Google Maps
                </a>
            </div>
            @endif

            {{-- Organisateur --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 p-4 p-md-5" style="background:#fff;">
                <h2 class="fw-bold mb-4" style="color:#1e293b; font-size:1.15rem; border-bottom:2px solid #f1f5f9; padding-bottom:.75rem;">
                    Organisateur
                </h2>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-extrabold text-white flex-shrink-0"
                         style="width:56px; height:56px; background:#4f46e5; font-size:1.4rem;">
                        {{ strtoupper(substr($detail_evenement->nom_proprietaire ?? ($detail_evenement->user->nom ?? 'O'), 0, 1)) }}
                    </div>
                    <div>
                        <p class="fw-bold mb-0" style="color:#1e293b; font-size:1rem;">
                            {{ $detail_evenement->nom_proprietaire ?? ($detail_evenement->user->nom ?? 'Organisateur') }}
                        </p>
                        <small style="color:#94a3b8;">Organisateur sur TGEvent</small>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    @if($detail_evenement->telephone)
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-2xl" style="background:#f8fafc; border:1px solid #f1f5f9;">
                            <i class="fas fa-phone" style="color:#4f46e5; font-size:.9rem;"></i>
                            <span style="color:#475569; font-size:.88rem;">{{ $detail_evenement->telephone }}</span>
                        </div>
                    </div>
                    @endif
                    @if($detail_evenement->email)
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-2xl" style="background:#f8fafc; border:1px solid #f1f5f9;">
                            <i class="fas fa-envelope" style="color:#4f46e5; font-size:.9rem;"></i>
                            <span style="color:#475569; font-size:.88rem;">{{ $detail_evenement->email }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                @if($detail_evenement->facebook || $detail_evenement->twiter || $detail_evenement->whatsapp)
                <div class="d-flex gap-2">
                    @if($detail_evenement->facebook)
                    <a href="{{ $detail_evenement->facebook }}" target="_blank"
                       class="d-flex align-items-center justify-content-center rounded-circle text-decoration-none"
                       style="width:38px; height:38px; background:#e8f0fe; color:#1d6ef5; font-size:.9rem; transition:all .2s;"
                       onmouseover="this.style.background='#1d6ef5';this.style.color='#fff'"
                       onmouseout="this.style.background='#e8f0fe';this.style.color='#1d6ef5'">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if($detail_evenement->twiter)
                    <a href="{{ $detail_evenement->twiter }}" target="_blank"
                       class="d-flex align-items-center justify-content-center rounded-circle text-decoration-none"
                       style="width:38px; height:38px; background:#e7f3ff; color:#1da1f2; font-size:.9rem; transition:all .2s;"
                       onmouseover="this.style.background='#1da1f2';this.style.color='#fff'"
                       onmouseout="this.style.background='#e7f3ff';this.style.color='#1da1f2'">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @endif
                    @if($detail_evenement->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $detail_evenement->whatsapp) }}" target="_blank"
                       class="d-flex align-items-center justify-content-center rounded-circle text-decoration-none"
                       style="width:38px; height:38px; background:#e8f9f0; color:#25d366; font-size:.9rem; transition:all .2s;"
                       onmouseover="this.style.background='#25d366';this.style.color='#fff'"
                       onmouseout="this.style.background='#e8f9f0';this.style.color='#25d366'">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Sponsors --}}
            @if($detail_evenement->sponsors && $detail_evenement->sponsors->count() > 0)
            <div class="card border-0 shadow-sm rounded-3 p-4 p-md-5" style="background:#fff;">
                <h2 class="fw-bold mb-4" style="color:#1e293b; font-size:1.15rem; border-bottom:2px solid #f1f5f9; padding-bottom:.75rem;">
                    Sponsors officiels
                </h2>
                <div class="row g-3 align-items-center">
                    @foreach($detail_evenement->sponsors as $sponsor)
                    <div class="col-6 col-sm-4 col-md-3">
                        <div class="p-3 rounded-2xl text-center d-flex flex-column align-items-center justify-content-center"
                             style="background:#f8fafc; border:1px solid #f1f5f9; min-height:80px; transition:box-shadow .2s;"
                             onmouseover="this.style.boxShadow='0 4px 16px rgba(79,70,229,0.1)'"
                             onmouseout="this.style.boxShadow='none'">
                            @if($sponsor->logo)
                            <img src="{{ asset('storage/evenement/sponsors/' . $sponsor->logo) }}"
                                 alt="{{ $sponsor->nom }}"
                                 data-no-cover
                                 style="max-height:40px; max-width:100%; object-fit:contain; display:block; margin:0 auto .5rem;">
                            @endif
                            <span style="font-size:.72rem; font-weight:600; color:#64748b;">{{ $sponsor->nom }}</span>
                            @if($sponsor->lien_web)
                            <a href="{{ $sponsor->lien_web }}" target="_blank"
                               style="font-size:.65rem; color:#4f46e5; text-decoration:none;">Visiter</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ── Colonne droite (4/12) — Billetterie sticky ── --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top:88px; z-index:10;">

                {{-- Card billetterie --}}
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3" style="background:#fff;">

                    {{-- Mini photo header --}}
                    <div class="card-thumb" style="height:160px;">
                        <img src="{{ $detail_evenement->photo ? asset('storage/evenement/photo/' . $detail_evenement->photo) : asset('images/default-event.jpg') }}"
                             alt="{{ $detail_evenement->titre }}">
                    </div>

                    <div class="p-4">
                        <p class="fw-bold text-uppercase small mb-3" style="color:#94a3b8; letter-spacing:.07em;">Billetterie</p>

                        @if($detail_evenement->billets && $detail_evenement->billets->count() > 0)
                            <div class="d-flex flex-column gap-2 mb-4">
                                @foreach($detail_evenement->billets as $billet)
                                @php $dispo = $billet->quantite_disponible ?? 99; @endphp
                                <div class="p-3 rounded-2xl d-flex justify-content-between align-items-center"
                                     style="background:#f8fafc; border:1px solid #f1f5f9;">
                                    <div>
                                        <span class="fw-bold d-block" style="color:#1e293b; font-size:.9rem;">{{ $billet->type }}</span>
                                        @if($billet->description)
                                            <small style="color:#94a3b8; font-size:.72rem;">{{ $billet->description }}</small>
                                        @endif
                                    </div>
                                    <div class="text-end ms-2 flex-shrink-0">
                                        <span class="fw-extrabold d-block" style="color:#4f46e5; font-size:.95rem; white-space:nowrap;">
                                            {{ number_format($billet->prix, 0, ',', ' ') }} <small style="font-size:.65rem;">FCFA</small>
                                        </span>
                                        @if($dispo <= 0)
                                            <span style="font-size:.68rem; color:#ef4444; font-weight:600;">Épuisé</span>
                                        @elseif($dispo <= 5)
                                            <span style="font-size:.68rem; color:#d97706; font-weight:600;">{{ $dispo }} restant(s)</span>
                                        @else
                                            <span style="font-size:.68rem; color:#16a34a; font-weight:600;">Disponible</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if($detail_evenement->billets->where('prix', '>', 0)->count() > 0)
                            <p class="small mb-3 text-center" style="color:#94a3b8;">
                                À partir de
                                <strong style="color:#4f46e5; font-size:1.05rem;">
                                    {{ number_format($detail_evenement->billets->min('prix'), 0, ',', ' ') }} FCFA
                                </strong>
                            </p>
                            @endif

                            @if($detail_evenement->billets->sum('quantite_disponible') > 0 || $detail_evenement->billets->whereNull('quantite_disponible')->count() > 0)
                                <a href="{{ route('p.paiement.form', $detail_evenement->id) }}"
                                   class="btn w-100 py-3 rounded-xl fw-bold text-white border-0"
                                   style="background:#4f46e5; font-size:.95rem; transition:background .2s;"
                                   onmouseover="this.style.background='#4338ca'"
                                   onmouseout="this.style.background='#4f46e5'">
                                    Réserver mon billet
                                </a>
                            @else
                                <button class="btn w-100 py-3 rounded-xl fw-bold border-0" disabled
                                        style="background:#f1f5f9; color:#94a3b8; cursor:not-allowed;">
                                    Complet / Épuisé
                                </button>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <p class="small mb-0" style="color:#94a3b8;">Aucun billet disponible pour cet événement.</p>
                            </div>
                        @endif

                        <p class="text-center mt-3 mb-0 small" style="color:#cbd5e1;">
                            <i class="fas fa-lock me-1"></i> Paiement sécurisé via Stripe
                        </p>
                    </div>
                </div>

                {{-- Infos rapides --}}
                <div class="card border-0 shadow-sm rounded-3 p-4" style="background:#fff;">
                    <p class="fw-bold text-uppercase small mb-3" style="color:#94a3b8; letter-spacing:.07em;">Infos pratiques</p>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-xl flex-shrink-0"
                                 style="width:36px; height:36px; background:#eef2ff;">
                                <i class="fas fa-calendar-alt" style="color:#4f46e5; font-size:.85rem;"></i>
                            </div>
                            <div>
                                <small class="fw-bold text-uppercase d-block" style="color:#94a3b8; font-size:.65rem; letter-spacing:.07em;">Date</small>
                                <span style="color:#1e293b; font-size:.88rem; font-weight:600;">
                                    {{ \Carbon\Carbon::parse($detail_evenement->date)->translatedFormat('l d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-xl flex-shrink-0"
                                 style="width:36px; height:36px; background:#eef2ff;">
                                <i class="fas fa-clock" style="color:#4f46e5; font-size:.85rem;"></i>
                            </div>
                            <div>
                                <small class="fw-bold text-uppercase d-block" style="color:#94a3b8; font-size:.65rem; letter-spacing:.07em;">Horaire</small>
                                <span style="color:#1e293b; font-size:.88rem; font-weight:600;">
                                    {{ \Carbon\Carbon::parse($detail_evenement->start_heure)->format('H:i') }}
                                    @if($detail_evenement->end_heure) — {{ \Carbon\Carbon::parse($detail_evenement->end_heure)->format('H:i') }}@endif
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-xl flex-shrink-0"
                                 style="width:36px; height:36px; background:#eef2ff;">
                                <i class="fas fa-map-marker-alt" style="color:#4f46e5; font-size:.85rem;"></i>
                            </div>
                            <div>
                                <small class="fw-bold text-uppercase d-block" style="color:#94a3b8; font-size:.65rem; letter-spacing:.07em;">Lieu</small>
                                <span style="color:#1e293b; font-size:.88rem; font-weight:600;">{{ $detail_evenement->lieu }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

@endsection
