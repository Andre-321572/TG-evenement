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
        <h2 class="text-xl font-bold" style="color:#0f172a;">Modifier le compte scanner</h2>
        <p style="color:#64748b; font-size:.85rem;">Modifiez les détails de l'agent scanner ou son affectation à un événement.</p>
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

        <form method="POST" action="{{ route('organisateur.scanner-update', $user->id) }}">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                {{-- Nom --}}
                <div>
                    <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                                  letter-spacing:.07em; display:block; margin-bottom:.35rem;">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required
                           style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                                  border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                                  box-sizing:border-box; outline:none;">
                </div>
                {{-- Prénom --}}
                <div>
                    <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                                  letter-spacing:.07em; display:block; margin-bottom:.35rem;">Prénom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required
                           style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                                  border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                                  box-sizing:border-box; outline:none;">
                </div>
            </div>

            {{-- Email --}}
            <div style="margin-bottom:1rem;">
                <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                              letter-spacing:.07em; display:block; margin-bottom:.35rem;">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                              border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                              box-sizing:border-box; outline:none;">
            </div>

            {{-- Téléphone --}}
            <div style="margin-bottom:1rem;">
                <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                              letter-spacing:.07em; display:block; margin-bottom:.35rem;">Téléphone *</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                       style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                              border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                              box-sizing:border-box; outline:none;">
            </div>

            {{-- Événement à scanner --}}
            <div style="margin-bottom:1.5rem;">
                <label style="color:#475569; font-size:.78rem; font-weight:700; text-transform:uppercase;
                              letter-spacing:.07em; display:block; margin-bottom:.35rem;">Événement attribué</label>
                <select name="evenement_id"
                        style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                               border-radius:8px; padding:.65rem .85rem; color:#0f172a; font-size:.9rem;
                               box-sizing:border-box; outline:none;">
                    <option value="">-- Aucun (Peut scanner tous les événements) --</option>
                    @foreach($evenements as $ev)
                        <option value="{{ $ev->id }}" {{ old('evenement_id', $user->evenement_id) == $ev->id ? 'selected' : '' }}>
                            {{ $ev->titre }} ({{ $ev->date ? \Carbon\Carbon::parse($ev->date)->format('d/m/Y') : '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Mot de passe (optionnel) --}}
            <div style="border-top:1px dashed rgba(59,130,246,0.1); padding-top:1rem; margin-bottom:1.5rem;">
                <h4 style="font-size:.85rem; font-weight:700; color:#1e3a8a; margin:0 0 .5rem 0;">Changer le mot de passe (optionnel)</h4>
                <p style="color:#64748b; font-size:.75rem; margin:0 0 1rem 0;">Laissez ces champs vides si vous ne souhaitez pas modifier le mot de passe actuel.</p>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div>
                        <label style="color:#475569; font-size:.75rem; font-weight:700; text-transform:uppercase;
                                      letter-spacing:.07em; display:block; margin-bottom:.35rem;">Nouveau mot de passe</label>
                        <input type="password" name="password" minlength="8"
                               style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                                      border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                                      box-sizing:border-box; outline:none;">
                    </div>
                    <div>
                        <label style="color:#475569; font-size:.75rem; font-weight:700; text-transform:uppercase;
                                      letter-spacing:.07em; display:block; margin-bottom:.35rem;">Confirmer</label>
                        <input type="password" name="password_confirmation" minlength="8"
                               style="width:100%; background:#ffffff; border:1px solid rgba(59,130,246,0.15);
                                      border-radius:8px; padding:.6rem .85rem; color:#0f172a; font-size:.9rem;
                                      box-sizing:border-box; outline:none;">
                    </div>
                </div>
            </div>

            <button type="submit"
                    style="background:#4f46e5; color:#ffffff; border:none; border-radius:10px;
                           padding:.75rem 2rem; font-weight:700; font-size:.95rem; cursor:pointer;
                           width:100%; transition:background .2s;">
                Enregistrer les modifications
            </button>
        </form>
    </div>

</div>
@endsection
