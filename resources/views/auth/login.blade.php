@extends('layouts.base')

@section('title', '| Connexion')

@section('content')
<main class="container py-5 text-white d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 200px);">
    <div class="w-100 max-w-md">
        <div class="glass-card rounded-3xl overflow-hidden p-4 p-md-5 border border-white/10 relative">
            
            <div class="mb-5">
                <p class="text-indigo-600 fw-bold text-xs uppercase tracking-widest mb-1">TGEvent</p>
                <h2 class="fw-bold text-slate-800 fs-4 mb-1">Connexion</h2>
                <p class="text-gray-400 small">Accédez à votre compte organisateur</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="needs-validation">
                @csrf
                
                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label text-gray-400 small fw-semibold">Adresse email</label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="nom@exemple.com"
                           class="form-control glass-input rounded-xl py-2.5 px-3 @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback font-medium mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label text-gray-400 small fw-semibold mb-0">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs text-indigo-400 hover:text-indigo-300 text-decoration-none" href="{{ route('password.request') }}">
                                Oublié ?
                            </a>
                        @endif
                    </div>
                    <input type="password" id="password" name="password" 
                           required autocomplete="current-password"
                           placeholder="••••••••"
                           class="form-control glass-input rounded-xl py-2.5 px-3 @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback font-medium mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-4">
                    <input class="form-check-input bg-transparent border-white/20 focus:border-indigo-500 focus:ring-0 rounded" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-gray-400 small" for="remember">
                        Se souvenir de moi
                    </label>
                </div>

                <!-- Action button -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn text-white font-semibold rounded-xl py-3 hover:scale-105 transition-all duration-300 border-0" style="background: #4f46e5;">
                        Se connecter
                    </button>
                </div>

                <!-- Footer link -->
                <div class="text-center text-gray-400 small">
                    Nouveau participant ? 
                    <a href="{{ route('register') }}" class="fw-bold text-indigo-400 hover:text-indigo-300 text-decoration-none ms-1">
                        S'inscrire
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection