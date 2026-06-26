<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <div id="chargement" class="d-flex justify-content-center align-items-center ">
            <section id="hero" class="d-flex align-items-center justify-center">
                <div class="col-lg-12 order-1 order-lg-2 hero-img d-flex justify-content-center align-items-center"
                    data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('asset/svgs/solid/globe.svg') }}" class="img-fluid animated" alt=""
                        style="height: 100px;width:100px">
                </div>
            </section>
        </div>

        <div id="contenu" class="d-none">
            @include('layouts.navbar')
            <div class="" style="padding-left: 30px;" style="padding-right: 30px">
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
    <style>
        a {
            text-decoration: none;
        }
    </style>
</body>

</html>
