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
    <title>{{config('app.name')}} | Confirmation du mot de passe</title>
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
                            <p class="mt-2 mb-0">Veuillez confirmer votre mot de passe avant de continuer</p>
                        </div>
                        
                        <div class="card-body p-4">
                            <!-- Logo ou icône -->
                            <div class="text-center mb-4">
                                <div style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(to right, #0d47a1, #1976d2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shield-alt" style="font-size: 40px; color: white;"></i>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('password.confirm') }}" class="needs-validation">
                                @csrf

                                <div class="mb-4">
                                    <div class="form-floating">
                                        <input type="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                            name="password" placeholder="Mot de passe" required autocomplete="current-password"
                                            style="border-radius: 10px; border: 1px solid #ccc;">
                                        <label for="password" style="color: #666;">Mot de passe</label>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mb-3">
                                    <button type="submit"
                                        class="btn btn-primary btn-lg text-white fw-bold" 
                                        style="border-radius: 10px; background:linear-gradient(to right, #0d47a1, #1976d2); border: none; padding: 12px; transition: all 0.3s;">
                                        <i class="fas fa-check-circle me-2"></i>Confirmer le mot de passe
                                    </button>
                                </div>

                                @if (Route::has('password.request'))
                                    <div class="text-center mb-3">
                                        <a class="text-primary" href="{{ route('password.request') }}">
                                            <i class="fas fa-question-circle me-1"></i>Mot de passe oublié?
                                        </a>
                                    </div>
                                @endif
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
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        background: linear-gradient(to right, #1565c0, #1e88e5) !important;
    }
    
    .form-control:focus {
        border-color: #1976d2;
        box-shadow: none;
    }
    
    .btn-outline-primary {
        color: #1976d2;
        border-color: #1976d2;
    }
    
    .btn-outline-primary:hover {
        background-color: #1976d2;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
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