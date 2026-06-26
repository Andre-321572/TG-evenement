<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,700,800" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/build-bootstrap.js') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/organisateur/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/organisateur/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/organisateur/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/organisateur/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/organisateur/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/organisateur/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/organisateur/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/organisateur/styles.css') }}">
    <link rel="stylesheet" href="{{asset('asset/bootstrap/organisateur/tooplate-artxibition.css')}}">


    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>{{ config('app.name') }} @yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @vite('rsources/css/app.css')

</head>

<body>
    <div>
        <div id="contenu" class="container-scroller bg-white">
            @include('organisateur.include.navbar')
            <div class="main-panel text-black controlers w-100 " style="margin-left: 20vh;" style="
                    max-width: 300%;

                "
            >
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('asset/bootstrap/bootstrap.min.js') }}"></script>

    <!-- Font Awesome JS -->
    <script src="{{ asset('asset/bootstrap/all.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/js.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/jquery.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/build-bootstrap.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/main.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/aos.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/script.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/Chart.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/dashbord.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/hoverable-collaps.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/jquery-jvectormap-word-mill-en.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/misc.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/off-canvas.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/popovers.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/progressbar.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/todolist.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/jquery-3.3.1.slim.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/organisateur/misc.js') }}"></script>


    <style>
        a {
            text-decoration: none;
        }


        @media (max-width: 992px) {

            .page-body-wrapper{
                margin-left: 0vh;margin-right: 0%;
            }
        }
        @media (max-width: 700px) {
            .controlers{
                margin-left: 0vh;margin-right: 0%;
            }
        }
        
    </style>
</body>

</html>
