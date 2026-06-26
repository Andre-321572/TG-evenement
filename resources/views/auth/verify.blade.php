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
    <title>{{config('app.name')}} | Vérification de l'email</title>
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
                                <i class="fas fa-envelope-open-text me-2"></i>Vérifiez votre adresse e-mail
                            </h2>
                            <p class="mt-2 mb-0">Une étape de plus pour sécuriser votre compte</p>
                        </div>
                        
                        <div class="card-body p-4">
                            <!-- Logo ou icône -->
                            <div class="text-center mb-4">
                                <div style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(to right, #0d47a1, #1976d2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope-circle-check" style="font-size: 40px; color: white;"></i>
                                </div>
                            </div>

                            @if (session('resent'))
                                <div class="alert alert-success mb-4" role="alert" style="border-radius: 10px; border-left: 4px solid #28a745;">
                                    <i class="fas fa-check-circle me-2"></i>Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                                </div>
                            @endif

                            <div class="text-center mb-4">
                                <p>Avant de continuer, veuillez vérifier votre e-mail pour un lien de vérification.</p>
                                <p>Si vous n'avez pas reçu l'e-mail, vous pouvez en demander un autre en cliquant sur le bouton ci-dessous.</p>
                            </div>

                            <form method="POST" action="{{ route('verification.resend') }}" class="d-grid gap-2 mb-4">
                                @csrf
                                <button type="submit"
                                    class="btn btn-primary btn-lg text-white fw-bold" 
                                    style="border-radius: 10px; background:linear-gradient(to right, #0d47a1, #1976d2); border: none; padding: 12px; transition: all 0.3s;">
                                    <i class="fas fa-paper-plane me-2"></i>Renvoyer le lien de vérification
                                </button>
                            </form>

                            <div class="text-center mb-2">
                                <p class="mb-0">Retour à la 
                                    <a href="{{ route('login') }}" class="fw-bold text-primary">
                                        page de connexion
                                    </a>
                                </p>
                            </div>
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