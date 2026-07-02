@extends('layouts.base')

@section('title', '| Inscription')

@section('content')
<style>
    body {
        background-color: #fafbfc !important;
    }
    .form-control:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }
</style>

<main class="container py-5 d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 120px);">
    <div class="row w-100 align-items-center g-5">
        <!-- Left side: Marketing text and badges -->
        <div class="col-lg-6 d-none d-lg-block animate__animated animate__fadeInLeft">
            <div class="pe-xl-5">
                <h1 class="font-extrabold text-slate-900 tracking-tight leading-tight mb-4" style="font-size: clamp(2rem, 4.5vw, 3.2rem); font-family: 'Outfit', sans-serif;">
                    Rejoignez la<br>
                    <span class="text-[#d9383a]">pulsation</span> des<br>
                    événements.
                </h1>
                <p class="text-slate-500 font-medium text-base md:text-lg mb-5 leading-relaxed">
                    Découvrez, créez et gérez des expériences mémorables avec l'outil de logistique événementielle le plus avancé.
                </p>
                
                <div class="d-flex gap-3">
                    <span class="px-4 py-3 rounded-2xl bg-white border border-slate-100 text-slate-800 text-sm font-semibold d-flex align-items-center gap-2 shadow-xs">
                        🎫 Billetterie sécurisée
                    </span>
                    <span class="px-4 py-3 rounded-2xl bg-white border border-slate-100 text-slate-800 text-sm font-semibold d-flex align-items-center gap-2 shadow-xs">
                        📅 Accès exclusif
                    </span>
                </div>
            </div>
        </div>

        <!-- Right side: Register Card -->
        <div class="col-lg-6 col-md-8 mx-auto animate__animated animate__fadeInRight">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-lg p-4 p-md-5">
                <div class="mb-4">
                    <h2 class="fw-bold text-slate-900 fs-3 mb-1" style="font-family: 'Outfit', sans-serif;">Créer un compte</h2>
                    <p class="text-slate-500 font-medium small mb-0">Prêt à vivre des moments d'exception ?</p>
                </div>

                <form method="POST" action="{{ route('register') }}" id="register-form" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden fields for First Name & Last Name (populated by split JS) -->
                    <input type="hidden" name="nom" id="nom">
                    <input type="hidden" name="prenom" id="prenom">

                    <!-- Nom complet -->
                    <div class="mb-3.5">
                        <label for="nom_complet" class="form-label text-slate-500 font-semibold small mb-1">Nom complet</label>
                        <input type="text" id="nom_complet" required placeholder="Jean Dupont"
                               class="form-control rounded-xl py-2.5 px-3 text-sm border-slate-200 text-slate-800 placeholder-slate-400 font-medium bg-slate-50/50">
                    </div>

                    <!-- Adresse email -->
                    <div class="mb-3.5">
                        <label for="email" class="form-label text-slate-500 font-semibold small mb-1">Adresse email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="jean.dupont@example.com"
                               class="form-control rounded-xl py-2.5 px-3 text-sm border-slate-200 text-slate-800 placeholder-slate-400 font-medium bg-slate-50/50 @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback font-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Téléphone (Required by RegisterController backend) -->
                    <div class="mb-3.5">
                        <label for="phone" class="form-label text-slate-500 font-semibold small mb-1">Téléphone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="+228 90 00 00 00"
                               class="form-control rounded-xl py-2.5 px-3 text-sm border-slate-200 text-slate-800 placeholder-slate-400 font-medium bg-slate-50/50 @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback font-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password & Confirmation -->
                    <div class="mb-3.5">
                        <label for="password" class="form-label text-slate-500 font-semibold small mb-1">Mot de passe</label>
                        <div class="position-relative">
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                   class="form-control rounded-xl py-2.5 px-3 pe-5 text-sm border-slate-200 text-slate-800 placeholder-slate-400 font-medium bg-slate-50/50 @error('password') is-invalid @enderror">
                            <button type="button" class="position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent text-slate-400 px-3 hover:text-slate-600" onclick="togglePasswordVisibility('password', this)" style="height: 100%;">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback font-medium mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3.5">
                        <label for="password_confirmation" class="form-label text-slate-500 font-semibold small mb-1">Confirmer le mot de passe</label>
                        <div class="position-relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                                   class="form-control rounded-xl py-2.5 px-3 pe-5 text-sm border-slate-200 text-slate-800 placeholder-slate-400 font-medium bg-slate-50/50">
                            <button type="button" class="position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent text-slate-400 px-3 hover:text-slate-600" onclick="togglePasswordVisibility('password_confirmation', this)" style="height: 100%;">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Profile Type Selection -->
                    <div class="mb-4">
                        <label class="form-label text-slate-500 font-semibold small mb-2">Type de profil</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" id="type-participant" onclick="setProfileType('participant')" class="btn w-100 py-2.5 rounded-xl border border-indigo-600 bg-indigo-50/10 text-indigo-700 font-semibold text-sm d-flex align-items-center justify-content-center gap-2 transition-all">
                                    <i class="far fa-user"></i> Participant
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" id="type-organisateur" onclick="setProfileType('organisateur')" class="btn w-100 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm d-flex align-items-center justify-content-center gap-2 transition-all">
                                    <i class="fas fa-briefcase"></i> Organisateur
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn text-white font-bold rounded-xl py-2.5 border-0 shadow-sm transition-all duration-200" style="background: #d9383a; hover: background: #c22e30;">
                            Créer mon compte
                        </button>
                    </div>

                    <!-- Toggle Login -->
                    <div class="text-center text-slate-500 small mb-4">
                        Vous avez déjà un compte ? 
                        <a href="{{ route('login') }}" class="fw-bold text-indigo-600 hover:text-indigo-800 text-decoration-none ms-1">
                            Se connecter
                        </a>
                    </div>

                    <!-- Social Login Divider -->
                    <div class="text-center text-slate-400 text-xs my-4 uppercase tracking-wider font-semibold">
                        Ou s'inscrire avec
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
        </div>
    </div>
</main>

<script>
function togglePasswordVisibility(fieldId, btnEl) {
    const input = document.getElementById(fieldId);
    const icon = btnEl.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'far fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'far fa-eye';
    }
}

function setProfileType(type) {
    const participantBtn = document.getElementById('type-participant');
    const organisateurBtn = document.getElementById('type-organisateur');
    
    if (type === 'participant') {
        participantBtn.className = 'btn w-100 py-2.5 rounded-xl border border-indigo-600 bg-indigo-50/10 text-indigo-700 font-semibold text-sm d-flex align-items-center justify-content-center gap-2 transition-all';
        organisateurBtn.className = 'btn w-100 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm d-flex align-items-center justify-content-center gap-2 transition-all';
    } else {
        organisateurBtn.className = 'btn w-100 py-2.5 rounded-xl border border-indigo-600 bg-indigo-50/10 text-indigo-700 font-semibold text-sm d-flex align-items-center justify-content-center gap-2 transition-all';
        participantBtn.className = 'btn w-100 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm d-flex align-items-center justify-content-center gap-2 transition-all';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    const nomCompletInput = document.getElementById('nom_complet');

    function splitNameAndPopulate() {
        const fullname = nomCompletInput.value.trim();
        const parts = fullname.split(/\s+/);
        const prenom = parts[0] || '';
        const nom = parts.slice(1).join(' ') || ' ';
        document.getElementById('prenom').value = prenom;
        document.getElementById('nom').value = nom;
    }

    if (nomCompletInput) {
        nomCompletInput.addEventListener('input', splitNameAndPopulate);
        nomCompletInput.addEventListener('change', splitNameAndPopulate);
    }

    if (registerForm) {
        registerForm.addEventListener('submit', splitNameAndPopulate);
    }
});
</script>
@endsection
