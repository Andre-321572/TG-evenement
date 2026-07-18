@extends('layouts.base')
@section('title', '| Réserver — ' . $evenement->titre)

@section('content')
<main class="container py-5">

    <div class="mb-4">
        <a href="{{ route('p.detail', $evenement->id) }}" class="text-indigo-400 hover:text-indigo-300 text-decoration-none font-semibold d-inline-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i> Retour à l'événement
        </a>
    </div>

    @if(session('error'))
    <div class="rounded-2xl p-3 mb-4 d-flex align-items-center gap-2 small"
         style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); color:#dc2626;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row g-4 align-items-start">

        {{-- ── Sélection billet + paiement ── --}}
        <div class="col-lg-7">
            <div class="glass-card rounded-3xl p-4 p-md-5 border border-white/10">

                <h2 class="fw-bold mb-1" style="color:#1e293b; font-size:1.35rem;">Choisir votre billet</h2>
                <p class="small mb-4" style="color:#64748b;">Sélectionnez un type de billet puis payez en toute sécurité via Stripe.</p>

                <form action="{{ route('p.paiement.checkout') }}" method="POST" id="stripeForm">
                    @csrf
                    <input type="hidden" name="evenement_id" value="{{ $evenement->id }}">

                    {{-- Liste des billets --}}
                    <div class="mb-4">
                        <p class="fw-semibold small mb-2" style="color:#475569;">Type de billet</p>
                        <div class="d-flex flex-column gap-2">
                            @foreach($evenement->billets as $billet)
                            @php $dispo = $billet->quantite_disponible ?? 99; @endphp
                            <label class="d-block" for="billet_{{ $billet->id }}" style="cursor:{{ $dispo <= 0 ? 'not-allowed' : 'pointer' }};">
                                <input type="radio" name="billet_id" id="billet_{{ $billet->id }}"
                                       value="{{ $billet->id }}"
                                       class="d-none billet-radio"
                                       {{ $loop->first && $dispo > 0 ? 'checked' : '' }}
                                       {{ $dispo <= 0 ? 'disabled' : '' }}>
                                <div class="billet-card p-3 rounded-2xl d-flex justify-content-between align-items-center"
                                     style="border: 1.5px solid {{ $loop->first && $dispo > 0 ? '#4f46e5' : 'rgba(203,213,225,0.6)' }};
                                            background: {{ $loop->first && $dispo > 0 ? 'rgba(79,70,229,0.04)' : '#fff' }};
                                            opacity: {{ $dispo <= 0 ? '0.5' : '1' }};
                                            transition: all .2s;">
                                    <div>
                                        <span class="fw-bold d-block" style="color:#1e293b; font-size:.95rem;">{{ $billet->type }}</span>
                                        @if($billet->description)
                                            <small style="color:#64748b;">{{ $billet->description }}</small>
                                        @endif
                                    </div>
                                    <div class="text-end ms-3 flex-shrink-0">
                                        <span class="fw-extrabold d-block" style="color:#4f46e5; font-size:1.05rem;">
                                            {{ number_format($billet->prix, 0, ',', ' ') }}&nbsp;<small style="font-size:.7rem;">FCFA</small>
                                        </span>
                                        @if($dispo <= 0)
                                            <span class="small" style="color:#ef4444;">Épuisé</span>
                                        @elseif($dispo <= 5)
                                            <span class="small" style="color:#d97706;">{{ $dispo }} restant(s)</span>
                                        @else
                                            <span class="small" style="color:#16a34a;">Disponible</span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                            {{-- Quantité --}}
                    <div class="mb-4">
                        <label for="quantity" class="fw-semibold small mb-2" style="color:#475569;">Quantité de billets</label>
                        <select name="quantity" id="quantity-select" class="form-select rounded-xl" style="border-color:rgba(203,213,225,0.7); color:#1e293b; max-width:160px; font-weight:700;">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Coordonnées --}}
                    <div class="mb-4 pt-3" style="border-top:1px solid rgba(203,213,225,0.4);">
                        <p class="fw-semibold small mb-3" style="color:#475569;">Vos coordonnées</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:#64748b;">Nom complet</label>
                                <input type="text" name="name" class="form-control rounded-xl"
                                       style="border-color:rgba(203,213,225,0.7); color:#1e293b;"
                                       value="{{ Auth::user() ? Auth::user()->prenom . ' ' . Auth::user()->nom : '' }}"
                                       placeholder="Jean Dupont">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:#64748b;">Email</label>
                                <input type="email" name="email" class="form-control rounded-xl"
                                       style="border-color:rgba(203,213,225,0.7); color:#1e293b;"
                                       value="{{ Auth::user()?->email ?? '' }}"
                                       placeholder="email@exemple.com">
                            </div>
                        </div>
                    </div>

                    {{-- Moyen de paiement --}}
                    <div class="mb-4 pt-3" style="border-top:1px solid rgba(203,213,225,0.4);">
                        <p class="fw-semibold small mb-3" style="color:#475569;">Moyen de paiement</p>
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-3">
                                <label class="d-block w-100" style="cursor:pointer;">
                                    <input type="radio" name="payment_method" value="stripe" class="d-none payment-method-radio" checked>
                                    <div class="payment-method-card p-3 rounded-2xl text-center border" 
                                         style="border: 2px solid #4f46e5; background:rgba(79,70,229,0.04); transition:all .2s; min-height:85px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                        <i class="fas fa-credit-card mb-2" style="font-size:1.2rem; color:#4f46e5;"></i>
                                        <span class="small fw-bold d-block" style="color:#1e293b; font-size:0.75rem;">Carte (Stripe)</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="d-block w-100" style="cursor:pointer;">
                                    <input type="radio" name="payment_method" value="leekpay" class="d-none payment-method-radio">
                                    <div class="payment-method-card p-3 rounded-2xl text-center border" 
                                         style="border: 1.5px solid rgba(203,213,225,0.6); background:#fff; transition:all .2s; min-height:85px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                        <i class="fas fa-mobile-alt mb-2" style="font-size:1.2rem; color:#10b981;"></i>
                                        <span class="small fw-bold d-block" style="color:#1e293b; font-size:0.75rem;">Mobile Money</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="d-block w-100" style="cursor:pointer;">
                                    <input type="radio" name="payment_method" value="moov_money" class="d-none payment-method-radio">
                                    <div class="payment-method-card p-3 rounded-2xl text-center border" 
                                         style="border: 1.5px solid rgba(203,213,225,0.6); background:#fff; transition:all .2s; min-height:85px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                        <div class="mb-2 d-flex align-items-center justify-content-center rounded-circle" style="width:24px; height:24px; background:#0284c7; color:#fff; font-size:.7rem; font-weight:bold; font-family:sans-serif;">Moov</div>
                                        <span class="small fw-bold d-block" style="color:#1e293b; font-size:0.75rem;">Moov Money</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="d-block w-100" style="cursor:pointer;">
                                    <input type="radio" name="payment_method" value="mix_by_yas" class="d-none payment-method-radio">
                                    <div class="payment-method-card p-3 rounded-2xl text-center border" 
                                         style="border: 1.5px solid rgba(203,213,225,0.6); background:#fff; transition:all .2s; min-height:85px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                        <div class="mb-2 d-flex align-items-center justify-content-center rounded-circle" style="width:24px; height:24px; background:#ea580c; color:#fff; font-size:.7rem; font-weight:bold; font-family:sans-serif;">Mix</div>
                                        <span class="small fw-bold d-block" style="color:#1e293b; font-size:0.75rem;">MIX by Yas</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Mobile Money Phone Input (hidden by default) --}}
                        <div id="phoneInputContainer" class="d-none mt-3">
                            <label for="phoneInput" class="form-label small fw-semibold" style="color:#64748b;">Numéro de téléphone de paiement</label>
                            <input type="tel" name="phone" id="phoneInput" class="form-control rounded-xl" 
                                   style="border-color:rgba(203,213,225,0.7); color:#1e293b;"
                                   placeholder="ex: +228 99 12 34 56">
                            <span class="text-muted small mt-1 d-block" style="font-size: 0.75rem;">Saisissez votre numéro (indicatif pays requis, ex: +228 pour le Togo) pour valider le paiement.</span>
                        </div>
                    </div>

                    {{-- Bouton paiement --}}
                    <button type="submit" id="payBtn"
                            class="btn w-100 py-3 rounded-xl fw-bold text-white border-0 d-flex align-items-center justify-content-center gap-2"
                            style="background:#4f46e5; font-size:.97rem; transition:background .2s;"
                            onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
                        <i class="fas fa-credit-card"></i>
                        <span id="payBtnText">Payer avec Stripe</span>
                    </button>

                    <p class="text-center mt-3 small" style="color:#94a3b8;">
                        <i class="fas fa-lock me-1"></i>
                        Paiement sécurisé SSL — Sécurisé par <strong id="providerName">Stripe</strong>
                    </p>
                </form>
            </div>
        </div>

        {{-- ── Récapitulatif ── --}}
        <div class="col-lg-5">
            <div class="glass-card rounded-3xl p-4 border border-white/10">

                <p class="fw-bold small text-uppercase mb-3" style="color:#94a3b8; letter-spacing:.08em;">Récapitulatif</p>

                <div class="card-thumb rounded-2xl mb-3" style="height:150px;">
                    <img src="{{ $evenement->photo ? asset('storage/evenement/photo/' . $evenement->photo) : asset('images/default-event.jpg') }}"
                          alt="{{ $evenement->titre }}">
                </div>

                <h4 class="fw-bold mb-3" style="color:#1e293b; font-size:.97rem;">{{ $evenement->titre }}</h4>

                <div class="d-flex flex-column gap-1 mb-4">
                    <span class="small d-flex align-items-center gap-2" style="color:#64748b;">
                        <i class="fas fa-calendar-day" style="color:#4f46e5; width:14px;"></i>
                        {{ \Carbon\Carbon::parse($evenement->date)->format('d M Y') }}
                    </span>
                    <span class="small d-flex align-items-center gap-2" style="color:#64748b;">
                        <i class="fas fa-clock" style="color:#4f46e5; width:14px;"></i>
                        {{ \Carbon\Carbon::parse($evenement->start_heure)->format('H:i') }}
                    </span>
                    <span class="small d-flex align-items-center gap-2" style="color:#64748b;">
                        <i class="fas fa-map-marker-alt" style="color:#4f46e5; width:14px;"></i>
                        {{ $evenement->lieu }}
                    </span>
                </div>

                <div class="pt-3" style="border-top:1px solid rgba(203,213,225,0.4);">
                    <div class="d-flex justify-content-between small mb-1" style="color:#64748b;">
                        <span>Billet</span>
                        <span id="recapType">—</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-3" style="color:#64748b;">
                        <span>Frais de service</span>
                        <span>0 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold pt-3" style="border-top:1px solid rgba(203,213,225,0.4);">
                        <span style="color:#1e293b;">Total</span>
                        <span style="color:#4f46e5;" id="recapTotal">—</span>
                    </div>

                </div>

                <div class="mt-4 text-center">
                    <small class="d-block mb-2" style="color:#94a3b8;">Cartes acceptées</small>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="px-3 py-1 rounded-lg border small fw-bold" style="color:#1a1a2e; background:#fff; border-color:rgba(203,213,225,.6);">Visa</span>
                        <span class="px-3 py-1 rounded-lg border small fw-bold" style="color:#1a1a2e; background:#fff; border-color:rgba(203,213,225,.6);">Mastercard</span>
                        <span class="px-3 py-1 rounded-lg border small fw-bold" style="color:#4f46e5; background:#fff; border-color:rgba(203,213,225,.6);">Stripe</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
(function () {
    var billets = @json($evenement->billets->map(fn($b) => ['id' => $b->id, 'type' => $b->type, 'prix' => $b->prix]));
    var currentMethod = 'stripe';

    function fmt(n) { return new Intl.NumberFormat('fr-FR').format(n) + ' FCFA'; }

    function getSelectedMethodName() {
        if (currentMethod === 'moov_money') return 'Moov Money';
        if (currentMethod === 'mix_by_yas') return 'MIX by Yas';
        if (currentMethod === 'leekpay') return 'Mobile Money';
        return 'Stripe';
    }

    function setRecap(id) {
        var b = billets.find(function(x){ return String(x.id) === String(id); });
        if (!b) return;
        var qty = parseInt(document.getElementById('quantity-select').value) || 1;
        var total = b.prix * qty;
        document.getElementById('recapType').textContent  = b.type + ' (x' + qty + ')';
        document.getElementById('recapTotal').textContent = fmt(total);
        document.getElementById('payBtnText').textContent = 'Payer ' + fmt(total) + ' avec ' + getSelectedMethodName();
    }

    document.querySelectorAll('.billet-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.billet-card').forEach(function(c) {
                c.style.borderColor = 'rgba(203,213,225,0.6)';
                c.style.background  = '#fff';
            });
            radio.closest('label').querySelector('.billet-card').style.borderColor = '#4f46e5';
            radio.closest('label').querySelector('.billet-card').style.background  = 'rgba(79,70,229,0.04)';
            setRecap(radio.value);
        });
    });

    document.getElementById('quantity-select').addEventListener('change', function() {
        var checked = document.querySelector('.billet-radio:checked');
        if (checked) setRecap(checked.value);
    });

    // Payment method radio logic
    document.querySelectorAll('.payment-method-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method-card').forEach(function(c) {
                c.style.borderColor = 'rgba(203,213,225,0.6)';
                c.style.background  = '#fff';
                c.style.borderWidth = '1.5px';
            });
            var card = radio.closest('label').querySelector('.payment-method-card');
            card.style.borderColor = '#4f46e5';
            card.style.background  = 'rgba(79,70,229,0.04)';
            card.style.borderWidth = '2px';
            
            currentMethod = radio.value;

            // Show/hide phone input
            var phoneContainer = document.getElementById('phoneInputContainer');
            if (currentMethod === 'stripe') {
                phoneContainer.classList.add('d-none');
                document.getElementById('phoneInput').removeAttribute('required');
            } else {
                phoneContainer.classList.remove('d-none');
                document.getElementById('phoneInput').setAttribute('required', 'required');
            }

            // Update provider name label
            var providerName = document.getElementById('providerName');
            if (providerName) {
                if (currentMethod === 'stripe') {
                    providerName.textContent = 'Stripe';
                } else if (currentMethod === 'leekpay') {
                    providerName.textContent = 'LeekPay';
                } else {
                    providerName.textContent = 'Mobile Money';
                }
            }

            // Update submit button text and icon
            var btnIcon = document.querySelector('#payBtn i');
            if (currentMethod === 'stripe') {
                btnIcon.className = 'fas fa-credit-card';
            } else {
                btnIcon.className = 'fas fa-mobile-alt';
            }

            var checkedBillet = document.querySelector('.billet-radio:checked');
            if (checkedBillet) setRecap(checkedBillet.value);
        });
    });

    var checked = document.querySelector('.billet-radio:checked');
    if (checked) setRecap(checked.value);

    document.getElementById('stripeForm').addEventListener('submit', function() {
        var btn = document.getElementById('payBtn');
        btn.disabled = true;
        
        if (currentMethod === 'stripe') {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Redirection vers Stripe…';
        } else if (currentMethod === 'leekpay') {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Redirection vers LeekPay…';
        } else {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Validation du paiement mobile en cours…';
        }
    });
})();
</script>
@endsection

