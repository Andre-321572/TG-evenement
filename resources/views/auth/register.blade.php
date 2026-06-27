@extends('layouts.base')

@section('title', '| Inscription')

@section('content')
<main class="container py-5 text-white d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 200px);">
    <div class="w-100 max-w-xl">
        <div class="glass-card rounded-3xl overflow-hidden p-4 p-md-5 border border-white/10 relative">
            
            <div class="mb-5">
                <p class="text-indigo-600 fw-bold text-xs uppercase tracking-widest mb-1">TGEvent</p>
                <h2 class="fw-bold text-slate-800 fs-4 mb-1">Créer un compte</h2>
                <p class="text-gray-400 small">Rejoignez-nous pour gérer et découvrir des événements</p>
            </div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Nom & Prénom -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="nom" class="form-label text-gray-400 small fw-semibold">Nom</label>
                        <input type="text" id="nom" name="nom" 
                               value="{{ old('nom') }}" required placeholder="Ex: Dupont"
                               class="form-control glass-input rounded-xl py-2.5 px-3 @error('nom') is-invalid @enderror">
                        @error('nom')
                            <div class="invalid-feedback font-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="prenom" class="form-label text-gray-400 small fw-semibold">Prénom(s)</label>
                        <input type="text" id="prenom" name="prenom" 
                               value="{{ old('prenom') }}" required placeholder="Ex: Jean"
                               class="form-control glass-input rounded-xl py-2.5 px-3 @error('prenom') is-invalid @enderror">
                        @error('prenom')
                            <div class="invalid-feedback font-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email & Téléphone -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="email" class="form-label text-gray-400 small fw-semibold">Adresse email</label>
                        <input type="email" id="email" name="email" 
                               value="{{ old('email') }}" required placeholder="Ex: jean@exemple.com"
                               class="form-control glass-input rounded-xl py-2.5 px-3 @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback font-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label text-gray-400 small fw-semibold">Téléphone</label>
                        <input type="tel" id="phone" name="phone" 
                               value="{{ old('phone') }}" required placeholder="Ex: +228 90 00 00 00"
                               class="form-control glass-input rounded-xl py-2.5 px-3 @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback font-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password & Confirmation -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label text-gray-400 small fw-semibold">Mot de passe</label>
                        <input type="password" id="password" name="password" 
                               required placeholder="••••••••"
                               class="form-control glass-input rounded-xl py-2.5 px-3 @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback font-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label text-gray-400 small fw-semibold">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               required placeholder="••••••••"
                               class="form-control glass-input rounded-xl py-2.5 px-3">
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="form-check mb-4">
                    <input class="form-check-input bg-transparent border-white/20 focus:border-indigo-500 focus:ring-0 rounded" type="checkbox" id="terms" required>
                    <label class="form-check-label text-gray-400 small" for="terms">
                        J'accepte les <a href="#" class="text-indigo-400 hover:text-indigo-300 text-decoration-none"><u>conditions d'utilisation</u></a>
                    </label>
                </div>

                <!-- Action button -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn text-white font-semibold rounded-xl py-3 hover:scale-105 transition-all duration-300 border-0" style="background: #4f46e5;">
                        Créer mon compte
                    </button>
                </div>

                <!-- Footer link -->
                <div class="text-center text-gray-400 small">
                    Déjà un compte ? 
                    <a href="{{ route('login') }}" class="fw-bold text-indigo-400 hover:text-indigo-300 text-decoration-none ms-1">
                        Connectez-vous
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
