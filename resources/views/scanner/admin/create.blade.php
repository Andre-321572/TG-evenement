@extends('layouts.Obase')

@section('content')
<div class="p-4 sm:p-6">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('organisateur.scanners') }}"
           style="color:#4f46e5; font-size:.85rem; font-weight:600; text-decoration:none;
                  display:inline-flex; align-items:center; gap:.3rem; margin-bottom:.8rem;">
            <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Retour à la liste
        </a>
        <h2 class="text-xl font-bold" style="color:#0f172a;">Créer un compte scanner</h2>
        <p style="color:#64748b; font-size:.85rem;">Le compte pourra se connecter et scanner les billets via l'interface dédiée.</p>
    </div>

    {{-- Form --}}
    <div class="glass-card rounded-2xl" style="max-width:560px; padding:1.75rem;">

        {{-- Validation errors --}}
        @if($errors->any())
        <div style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); color:#b91c1c;
                    padding:.75rem 1rem; border-radius:8px; margin-bottom:1.2rem; font-size:.88rem;">
            <ul style="margin:0; padding-left:1.2rem;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('organisateur.scanner-store') }}">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                {{-- Nom --}}
                <div>
                    <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                                  letter-spacing:.07em; display:block; margin-bottom:.35rem;">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                           style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                                  border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                                  box-sizing:border-box; outline:none;">
                </div>
                {{-- Prénom --}}
                <div>
                    <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                                  letter-spacing:.07em; display:block; margin-bottom:.35rem;">Prénom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                           style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                                  border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                                  box-sizing:border-box; outline:none;">
                </div>
            </div>

            {{-- Email --}}
            <div style="margin-bottom:1rem;">
                <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                              letter-spacing:.07em; display:block; margin-bottom:.35rem;">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                              border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                              box-sizing:border-box; outline:none;">
            </div>

            {{-- Téléphone --}}
            <div style="margin-bottom:1rem;">
                <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                              letter-spacing:.07em; display:block; margin-bottom:.35rem;">Téléphone *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                       style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                              border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                              box-sizing:border-box; outline:none;">
            </div>

            {{-- Mot de passe --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
                <div>
                    <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                                  letter-spacing:.07em; display:block; margin-bottom:.35rem;">Mot de passe *</label>
                    <input type="password" name="password" required minlength="8"
                           style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                                  border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                                  box-sizing:border-box; outline:none;">
                </div>
                <div>
                    <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                                  letter-spacing:.07em; display:block; margin-bottom:.35rem;">Confirmer *</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                                  border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                                  box-sizing:border-box; outline:none;">
                </div>
            </div>

            <button type="submit"
                    style="background:#4f46e5; color:#ffffff; border:none; border-radius:10px;
                           padding:.75rem 2rem; font-weight:700; font-size:.95rem; cursor:pointer;
                           width:100%; transition:background .2s;">
                Créer le compte scanner
            </button>
        </form>
    </div>

</div>
@endsection
