<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/stylese.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/build-bootstrap.js') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/responsive.css') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>{{config('app.name')}} | Inscription</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<section class="vh-100 m-0">
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="card shadow-lg" style="border-radius: 20px; border: none; overflow: hidden;">
                        <!-- Header -->
                        <div class="text-center text-black" style="padding: px 0;">
                            <h2 class="text-uppercase fw-bold mb-0">
                                <i class="fas fa-user-plus me-2"></i>Créer un compte
                            </h2>
                            <p class="mt-2 mb-0">Rejoignez-nous pour découvrir des événements exceptionnels</p>
                        </div>
                        
                        <div class="card-body p-4">
                            <!-- Logo ou icône -->
                            <div class="text-center mb-4">
                                <div style="width: 80px; height: 80px; margin: 0 auto; background-color: #4CAF50; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-edit" style="font-size: 40px; color: white;"></i>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('register') }}" class="needs-validation">
                                @csrf

                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="text" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}" placeholder="Nom" required autocomplete="name" autofocus
                                            style="border-radius: 10px; border: 1px solid #ccc;">
                                        <label for="name" style="color: #666;">Nom</label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" placeholder="E-mail" required autocomplete="email"
                                            style="border-radius: 10px; border: 1px solid #ccc;">
                                        <label for="email" style="color: #666;">Adresse e-mail</label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                            name="password" placeholder="Mot de passe" required autocomplete="new-password"
                                            style="border-radius: 10px; border: 1px solid #ccc;">
                                        <label for="password" style="color: #666;">Mot de passe</label>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-floating">
                                        <input type="password" id="password-confirm" class="form-control form-control-lg"
                                            name="password_confirmation" placeholder="Confirmer le mot de passe" required autocomplete="new-password"
                                            style="border-radius: 10px; border: 1px solid #ccc;">
                                        <label for="password-confirm" style="color: #666;">Confirmer le mot de passe</label>
                                    </div>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms" style="color: #666;">
                                        J'accepte les <a href="#" class="text-success"><u>conditions d'utilisation</u></a>
                                    </label>
                                </div>

                                <div class="d-grid gap-2 mb-4">
                                    <button type="submit"
                                        class="btn btn-success btn-lg text-white fw-bold" 
                                        style="border-radius: 10px; background: linear-gradient(to right, #388E3C, #4CAF50); border: none; padding: 12px; transition: all 0.3s;">
                                        <i class="fas fa-user-plus me-2"></i>S'inscrire
                                    </button>
                                </div>

                                <div class="text-center mb-2">
                                    <p class="mb-0">Vous avez déjà un compte? 
                                        <a href="{{ route('login') }}" class="fw-bold text-success">
                                            Connectez-vous
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Animations et effets supplémentaires */
    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        background: linear-gradient(to right, #2E7D32, #388E3C) !important;
    }
    
    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: none;
    }
    
    /* Animation d'apparition du formulaire */
    .card {
        animation: fadeIn 0.8s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Style pour le fond */
    section.vh-100 {
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
    
    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
</style>
</body>
</html>