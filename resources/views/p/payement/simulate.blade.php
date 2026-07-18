@extends('layouts.base')
@section('title', '| Paiement sécurisé ' . $methodName)

@section('content')
<main class="container py-5 d-flex justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card rounded-3xl p-4 p-md-5 border border-white/10 shadow-lg text-center" style="background: #ffffff;">
            
            <h2 class="fw-bold mb-3" style="color:#1e293b; font-size:1.5rem;">Paiement avec {{ $methodName }}</h2>
            <p class="mb-4 text-muted">Montant à régler : <strong style="color:#4f46e5; font-size:1.2rem;">{{ number_format($amount, 0, ',', ' ') }} FCFA</strong></p>

            <form action="{{ route('p.paiement.simulate.process') }}" method="POST" id="simForm">
                @csrf
                <input type="hidden" name="session_id" value="{{ $sessionId }}">
                <input type="hidden" name="billet_id" value="{{ request('billet_id') }}">
                <input type="hidden" name="evenement_id" value="{{ request('evenement_id') }}">
                <input type="hidden" name="quantity" value="{{ request('quantity') }}">
                <input type="hidden" name="email" value="{{ request('email') }}">
                <input type="hidden" name="name" value="{{ request('name') }}">

                <!-- Etape 1: Numéro de téléphone -->
                <div id="step-phone">
                    <label class="form-label small fw-semibold text-start w-100" style="color:#475569;">Saisissez votre numéro de téléphone</label>
                    <input type="tel" name="phone" id="phone_input" class="form-control py-3 rounded-xl mb-4 text-center fw-bold fs-5" required placeholder="ex: +228 99 00 00 00" style="background:rgba(241,245,249,0.5); border:2px solid #cbd5e1;">
                    <button type="button" id="btn-next" class="btn w-100 py-3 rounded-xl fw-bold text-white" style="background:#4f46e5;">Payer</button>
                </div>

                <!-- Etape 2: Mot de passe (cachée par défaut) -->
                <div id="step-password" class="d-none">
                    <label class="form-label small fw-semibold text-start w-100" style="color:#475569;">Entrez le mot de passe de votre compte {{ $methodName }}</label>
                    <input type="password" name="password" id="password_input" class="form-control py-3 rounded-xl mb-4 text-center fw-bold fs-3" style="letter-spacing: 5px; background:rgba(241,245,249,0.5); border:2px solid #cbd5e1;">
                    <div class="d-flex gap-2">
                        <button type="button" id="btn-back" class="btn w-25 py-3 rounded-xl fw-bold" style="background:#e2e8f0; color:#475569;">Retour</button>
                        <button type="submit" id="btn-submit" class="btn w-75 py-3 rounded-xl fw-bold text-white" style="background:#10b981;">Confirmer le paiement</button>
                    </div>
                </div>
            </form>
            
            <p class="text-center mt-4 small" style="color:#94a3b8;">
                <i class="fas fa-lock me-1"></i> Connexion sécurisée
            </p>
        </div>
    </div>
</main>

<script>
    document.getElementById('btn-next').addEventListener('click', function() {
        var phone = document.getElementById('phone_input').value.trim();
        if(phone.length < 8) {
            alert("Veuillez saisir un numéro de téléphone valide.");
            return;
        }
        document.getElementById('step-phone').classList.add('d-none');
        document.getElementById('step-password').classList.remove('d-none');
        document.getElementById('password_input').setAttribute('required', 'required');
        document.getElementById('password_input').focus();
    });

    document.getElementById('btn-back').addEventListener('click', function() {
        document.getElementById('step-password').classList.add('d-none');
        document.getElementById('password_input').removeAttribute('required');
        document.getElementById('step-phone').classList.remove('d-none');
    });

    document.getElementById('simForm').addEventListener('submit', function() {
        document.getElementById('btn-submit').disabled = true;
        document.getElementById('btn-submit').innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Validation en cours...';
    });
</script>
@endsection
