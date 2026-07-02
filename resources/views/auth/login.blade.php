@extends('layouts.base')

@section('title', '| Connexion')

@section('content')
<style>
    body {
        background-image: url('https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1200&q=80') !important;
        background-size: cover !important;
        background-position: center !important;
        position: relative;
        min-height: 100vh;
    }
    body::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.45); /* dark slate screen overlay */
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        z-index: 1;
    }
    .form-control:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }
</style>

<main class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 100vh; position: relative; z-index: 10;">
    <div class="w-100 max-w-md animate__animated animate__zoomIn">
        
        <!-- Title & Brand -->
        <div class="text-center mb-4">
            <h2 class="fw-bold text-white fs-2 mb-1" style="font-family: 'Outfit', sans-serif; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">TGEvent</h2>
            <p class="text-slate-200 small" style="text-shadow: 0 1px 6px rgba(0,0,0,0.3);">L'excellence logistique au service de vos émotions.</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl p-4 p-md-5">
            <form method="POST" action="{{ route('login') }}" class="needs-validation">
                @csrf
                
                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label text-slate-500 font-semibold small mb-1">Adresse e-mail</label>
                    <div class="position-relative">
                        <i class="far fa-envelope position-absolute start-0 top-50 translate-middle-y text-slate-400 ms-3" style="pointer-events: none;"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nom@exemple.com"
                               class="form-control rounded-xl py-2.5 ps-5 pe-3 text-sm border-slate-200 text-slate-800 placeholder-slate-400 font-medium bg-slate-50/50 @error('email') is-invalid @enderror">
                    </div>
                    @error('email')
                        <div class="invalid-feedback font-medium mt-1"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label text-slate-500 font-semibold small mb-0">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs text-indigo-600 hover:text-indigo-800 text-decoration-none font-semibold" href="{{ route('password.request') }}">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>
                    <div class="position-relative">
                        <i class="fas fa-lock position-absolute start-0 top-50 translate-middle-y text-slate-400 ms-3" style="pointer-events: none;"></i>
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                               class="form-control rounded-xl py-2.5 ps-5 pe-3 text-sm border-slate-200 text-slate-800 placeholder-slate-400 font-medium bg-slate-50/50 @error('password') is-invalid @enderror">
                    </div>
                    @error('password')
                        <div class="invalid-feedback font-medium mt-1"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <!-- Submit button -->
                <div class="d-grid mb-4 pt-1">
                    <button type="submit" class="btn text-white font-bold rounded-xl py-2.5 border-0 shadow-sm transition-all duration-200" style="background: #d9383a; hover: background: #c22e30;">
                        Se connecter
                    </button>
                </div>

                <!-- Social Divider -->
                <div class="text-center text-slate-400 text-xs my-4 uppercase tracking-wider font-semibold">
                    Ou continuer avec
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" class="btn w-100 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-semibold d-flex align-items-center justify-content-center gap-2 shadow-xs hover:bg-slate-50 transition-colors">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width:16px;"> Google
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn w-100 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-semibold d-flex align-items-center justify-content-center gap-2 shadow-xs hover:bg-slate-50 transition-colors">
                            <i class="fab fa-apple text-slate-900 fs-6"></i> Apple
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer Links -->
        <div class="text-center mt-4 position-relative z-10">
            <p class="text-slate-300 small mb-2">
                Nouveau ici ? 
                <a href="{{ route('register') }}" class="fw-bold text-white hover:text-slate-200 text-decoration-none ms-1">
                    Créer un compte
                </a>
            </p>
            <p class="text-slate-400 text-xs mb-3">
                <a href="#" class="text-slate-400 hover:text-slate-300 text-decoration-none">Confidentialité</a>
                <span class="mx-1.5">•</span>
                <a href="#" class="text-slate-400 hover:text-slate-300 text-decoration-none">Support</a>
            </p>
            <p class="text-slate-500 text-xs mb-0">© 2026 TGEvent. Tous droits réservés.</p>
        </div>

    </div>
</main>
@endsection