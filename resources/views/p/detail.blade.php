@extends('layouts.base')
@section('title', '| ' . $detail_evenement->titre)

@section('content')

<style>
    body {
        background-color: #fafbfc !important;
    }
    .ticket-option {
        border-color: #f1f5f9;
        cursor: pointer;
    }
    .ticket-option:hover {
        border-color: #e2e8f0;
    }
</style>

{{-- ═══════════════════════════════════════════════
     HERO — Full bleed, inline styles (bypass overrides)
     ═══════════════════════════════════════════════ --}}
<div style="position:relative; width:100%; height:450px; overflow:hidden;">
    <img src="{{ $detail_evenement->photo ? asset('storage/evenement/photo/' . $detail_evenement->photo) : asset('images/default-event.jpg') }}"
         alt="{{ $detail_evenement->titre }}"
         style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; object-position:center;">
    
    {{-- Overlay gradient fading to light page background at the very bottom --}}
    <div style="position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.65) 65%, rgba(250,251,252,1) 100%);"></div>

    {{-- Content overlay --}}
    <div style="position:absolute; inset:0; display:flex; flex-direction:column; justify-content:flex-end; padding:2.5rem 2.5rem 3.5rem 2.5rem;">
        <div style="max-width:860px;">
            <span style="display:inline-block; padding:.35rem .9rem; border-radius:999px; font-size:.72rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#ffffff; background:#d9383a; margin-bottom:.9rem;">
                ⭐ Événement Premium
            </span>
            <h1 style="font-size:clamp(1.8rem,5vw,3rem); font-weight:800; color:#ffffff; line-height:1.2; margin-bottom:1.2rem; text-shadow:0 2px 16px rgba(0,0,0,0.4); font-family: 'Outfit', sans-serif;">
                {{ $detail_evenement->titre }}
            </h1>
            <div style="display:flex; flex-wrap:wrap; gap:1.5rem; text-shadow:0 1px 8px rgba(0,0,0,0.35);">
                <span style="display:flex; align-items:center; gap:.5rem; color:rgba(255,255,255,0.9); font-size:.88rem; font-weight: 500;">
                    <i class="far fa-calendar" style="color:#ffffff;"></i>
                    {{ \Carbon\Carbon::parse($detail_evenement->date)->translatedFormat('l, d F Y') }}
                </span>
                <span style="display:flex; align-items:center; gap:.5rem; color:rgba(255,255,255,0.9); font-size:.88rem; font-weight: 500;">
                    <i class="far fa-clock" style="color:#ffffff;"></i>
                    {{ \Carbon\Carbon::parse($detail_evenement->start_heure)->format('H:i') }}
                    @if($detail_evenement->end_heure) — {{ \Carbon\Carbon::parse($detail_evenement->end_heure)->format('H:i') }}@endif
                </span>
                <span style="display:flex; align-items:center; gap:.5rem; color:rgba(255,255,255,0.9); font-size:.88rem; font-weight: 500;">
                    <i class="fas fa-map-marker-alt" style="color:#ffffff;"></i>
                    {{ $detail_evenement->lieu }}
                </span>
            </div>
        </div>
    </div>

    {{-- Back button --}}
    <div style="position:absolute; top:1.25rem; left:1.5rem; z-index: 30;">
        <a href="{{ route('p.evenement') }}" style="display:inline-flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:.75rem; background:rgba(0,0,0,0.35); border:1px solid rgba(255,255,255,0.15); color:#ffffff; font-size:.82rem; font-weight:600; text-decoration:none; backdrop-filter:blur(8px);">
            <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Retour
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     MAIN CONTENT
     ═══════════════════════════════════════════════ --}}
<main class="container py-4 text-slate-800">
    <div class="row g-4 align-items-start">

        {{-- ── Left Column (8/12) ── --}}
        <div class="col-lg-8">

            <!-- À propos -->
            <div class="card border-0 shadow-sm rounded-2xl p-4 p-md-5 mb-4" style="background:#fff;">
                <h4 class="fw-bold mb-3 text-slate-900" style="font-family: 'Outfit', sans-serif;">
                    À propos de l'événement
                </h4>
                <div style="color:#475569; line-height:1.85; font-size:.95rem; white-space:pre-line;">
                    {{ $detail_evenement->description ?? 'Aucune description disponible.' }}
                </div>

                <!-- Checked features highlights -->
                <div class="row g-3 border-t border-slate-100 pt-4 mt-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 text-slate-700 text-sm font-medium">
                            <i class="far fa-check-circle text-indigo-600 fs-5"></i>
                            <span>Bar à cocktails premium</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 text-slate-700 text-sm font-medium">
                            <i class="far fa-check-circle text-indigo-600 fs-5"></i>
                            <span>Service à table VIP</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 text-slate-700 text-sm font-medium">
                            <i class="far fa-check-circle text-indigo-600 fs-5"></i>
                            <span>Rencontre avec les artistes</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 text-slate-700 text-sm font-medium">
                            <i class="far fa-check-circle text-indigo-600 fs-5"></i>
                            <span>Acoustique haute fidélité</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address and map side-by-side -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-2xl p-4 h-100 flex flex-col justify-between" style="background:#fff;">
                        <div>
                            <h5 class="fw-bold text-slate-900 mb-3" style="font-family: 'Outfit', sans-serif;">Lieu de l'événement</h5>
                            <p class="text-slate-800 font-semibold mb-1 text-sm">{{ $detail_evenement->lieu }}</p>
                            <p class="text-slate-500 text-xs mb-4">Consultez l'itinéraire pour vous rendre facilement sur place le jour de l'événement.</p>
                        </div>
                        <div>
                            <a href="https://maps.google.com/maps?q={{ urlencode($detail_evenement->lieu) }}" target="_blank"
                               class="btn d-inline-flex align-items-center gap-2 rounded-xl py-2 px-3 border border-slate-200 text-slate-700 bg-slate-50 hover:bg-slate-100 text-xs font-bold transition-colors shadow-xs">
                                <i class="fas fa-compass text-slate-400"></i> Itinéraire
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100" style="min-height:200px; background:#fff;">
                        <iframe src="https://maps.google.com/maps?q={{ urlencode($detail_evenement->lieu) }}&output=embed"
                                style="border:0; width:100%; height:100%; min-height: 200px;"
                                allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <!-- Video presentation (if available) -->
            @if($detail_evenement->video_url)
            <div class="card border-0 shadow-sm rounded-2xl mb-4 p-4" style="background:#fff;">
                <h5 class="fw-bold text-slate-900 mb-3" style="font-family: 'Outfit', sans-serif;">Vidéo de présentation</h5>
                <div class="ratio ratio-16x9 rounded-xl overflow-hidden">
                    <video src="{{ $detail_evenement->video_url }}" controls class="w-full"></video>
                </div>
            </div>
            @endif

            <!-- Sponsors (if available) -->
            @if($detail_evenement->sponsors && $detail_evenement->sponsors->count() > 0)
            <div class="card border-0 shadow-sm rounded-2xl p-4 p-md-5" style="background:#fff;">
                <h5 class="fw-bold text-slate-900 mb-4" style="font-family: 'Outfit', sans-serif;">Sponsors officiels</h5>
                <div class="row g-3 align-items-center">
                    @foreach($detail_evenement->sponsors as $sponsor)
                    <div class="col-6 col-sm-4 col-md-3">
                        <div class="p-3 rounded-xl text-center d-flex flex-column align-items-center justify-content-center"
                             style="background:#f8fafc; border:1px solid #f1f5f9; min-height:80px;">
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

        {{-- ── Right Column (4/12) ── --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top:88px; z-index:10;">

                <!-- Tickets Selection Card -->
                <div class="card border-0 shadow-sm rounded-2xl p-4 mb-3" style="background:#fff;">
                    <h5 class="fw-bold text-slate-900 mb-1" style="font-family: 'Outfit', sans-serif;">Sélection de billets</h5>
                    <p class="text-slate-400 small mb-4">Taxes incluses à la finalisation</p>

                    @if($detail_evenement->billets && $detail_evenement->billets->count() > 0)
                        <form action="{{ route('p.paiement.checkout') }}" method="POST" id="checkout-form">
                            @csrf
                            <input type="hidden" name="evenement_id" value="{{ $detail_evenement->id }}">
                            <input type="hidden" name="billet_id" id="selected_billet_id" value="">

                            <div class="d-flex flex-column gap-3 mb-4">
                                @foreach($detail_evenement->billets as $idx => $billet)
                                    @php 
                                        $dispo = $billet->quantite_disponible ?? 99; 
                                        $isSoldOut = ($dispo <= 0);
                                    @endphp
                                    
                                    @if($isSoldOut)
                                        <!-- Sold Out Option -->
                                        <div class="p-3 rounded-2xl d-flex justify-content-between align-items-center bg-slate-50 border border-slate-100 opacity-60">
                                            <div>
                                                <span class="fw-bold d-block text-slate-400 text-sm">{{ $billet->type }}</span>
                                                <span class="text-slate-400 text-xs font-semibold">{{ number_format($billet->prix, 0, ',', ' ') }} FCFA</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-slate-400 text-xs font-semibold me-2">Vendu</span>
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 d-flex align-items-center justify-content-center text-slate-400">
                                                    <i class="fas fa-lock text-xs"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Active Option -->
                                        <div class="ticket-option p-3 rounded-2xl d-flex justify-content-between align-items-center border border-slate-100 transition-all duration-200 cursor-pointer" 
                                             data-billet-id="{{ $billet->id }}" 
                                             data-price="{{ $billet->prix }}"
                                             onclick="selectTicket('{{ $billet->id }}')"
                                             style="background:#f8fafc;">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-0.5">
                                                    <span class="fw-bold d-block text-slate-800 text-sm leading-none">{{ $billet->type }}</span>
                                                    @if($idx == 1)
                                                        <span class="px-2 py-0.5 rounded text-[8px] font-bold text-white bg-indigo-600 uppercase tracking-wide leading-none">Populaire</span>
                                                    @endif
                                                </div>
                                                <span class="text-indigo-600 text-xs font-bold">{{ number_format($billet->prix, 0, ',', ' ') }} FCFA</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2" onclick="event.stopPropagation();">
                                                <button type="button" class="btn btn-sm btn-light w-8 h-8 rounded-lg d-flex align-items-center justify-content-center border-0 p-0 fs-5 font-bold text-slate-500 shadow-sm" onclick="decrementTicket('{{ $billet->id }}')">-</button>
                                                <span class="fw-bold text-slate-800 px-2 text-sm ticket-qty" id="qty-{{ $billet->id }}">0</span>
                                                <button type="button" class="btn btn-sm btn-light w-8 h-8 rounded-lg d-flex align-items-center justify-content-center border-0 p-0 fs-5 font-bold text-slate-500 shadow-sm" onclick="incrementTicket('{{ $billet->id }}')">+</button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-t border-slate-100 pt-3 mb-4">
                                <span class="text-slate-600 font-semibold text-sm">Total</span>
                                <span class="fw-bold text-slate-900 fs-4" id="total-price-display">0 FCFA</span>
                            </div>

                            <button type="submit" id="btn-submit-checkout" class="btn w-100 py-3 bg-[#d9383a] hover:bg-[#c22e30] text-white font-bold rounded-xl text-sm transition-all duration-200 border-0 shadow-sm d-flex align-items-center justify-content-center gap-2" disabled>
                                <i class="fas fa-ticket-alt"></i> Acheter maintenant
                            </button>
                        </form>
                    @else
                        <div class="text-center py-4 bg-slate-50 rounded-xl">
                            <p class="small text-slate-400 mb-0">Aucun billet disponible pour cet événement.</p>
                        </div>
                    @endif
                </div>

                <!-- Organizer Profile -->
                <div class="card border-0 shadow-sm rounded-2xl p-4" style="background:#fff;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-extrabold text-white flex-shrink-0 bg-indigo-600 shadow-inner" style="width:40px; height:40px; font-size:1.1rem;">
                                {{ strtoupper(substr($detail_evenement->nom_proprietaire ?? ($detail_evenement->user->nom ?? 'O'), 0, 1)) }}
                            </div>
                            <div>
                                <small class="text-slate-400 d-block text-xs">Organisé par</small>
                                <span class="fw-bold text-slate-800 text-sm">{{ $detail_evenement->nom_proprietaire ?? ($detail_evenement->user->nom ?? 'Organisateur') }}</span>
                            </div>
                        </div>
                        @if($detail_evenement->email)
                            <a href="mailto:{{ $detail_evenement->email }}" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-100 d-flex align-items-center justify-content-center text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition-all duration-200 text-decoration-none">
                                <i class="far fa-envelope"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

<script>
let selectedBilletId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Pre-select the first available ticket option on page load
    const firstOption = document.querySelector('.ticket-option');
    if (firstOption) {
        const billetId = firstOption.dataset.billetId;
        selectTicket(billetId);
    }
});

function selectTicket(billetId) {
    // Reset other options style and quantities
    document.querySelectorAll('.ticket-option').forEach(opt => {
        opt.classList.remove('border-indigo-600');
        opt.style.background = '#f8fafc';
        const bId = opt.dataset.billetId;
        const qtyEl = document.getElementById('qty-' + bId);
        if (qtyEl) qtyEl.textContent = '0';
    });

    selectedBilletId = billetId;
    document.getElementById('selected_billet_id').value = billetId;

    const activeOpt = document.querySelector(`.ticket-option[data-billet-id="${billetId}"]`);
    if (activeOpt) {
        activeOpt.classList.add('border-indigo-600');
        activeOpt.style.background = 'rgba(99, 102, 241, 0.05)';
        const price = parseFloat(activeOpt.dataset.price);
        
        const qtyEl = document.getElementById('qty-' + billetId);
        if (qtyEl) qtyEl.textContent = '1';
        
        document.getElementById('total-price-display').textContent = formatPrice(price) + ' FCFA';
        document.getElementById('btn-submit-checkout').disabled = false;
    }
}

function incrementTicket(billetId) {
    selectTicket(billetId);
}

function decrementTicket(billetId) {
    if (selectedBilletId === billetId) {
        // If we decrement the active one, clear quantity and disable checkout
        const qtyEl = document.getElementById('qty-' + billetId);
        if (qtyEl) qtyEl.textContent = '0';
        
        const activeOpt = document.querySelector(`.ticket-option[data-billet-id="${billetId}"]`);
        if (activeOpt) {
            activeOpt.classList.remove('border-indigo-600');
            activeOpt.style.background = '#f8fafc';
        }
        
        selectedBilletId = null;
        document.getElementById('selected_billet_id').value = '';
        document.getElementById('total-price-display').textContent = '0 FCFA';
        document.getElementById('btn-submit-checkout').disabled = true;
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR').format(price);
}
</script>

@endsection
