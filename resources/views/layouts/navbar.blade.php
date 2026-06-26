<nav class="navbar navbar-expand-lg bg-body-tertiary position-fixed navbar-light sticky-top py-0 w-100">
    <div class="">
        <a class="navbar-brand px-3 text-bold logo" href="{{--route('index')--}}">EvenAf</a>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas"
        aria-controls="navbarOffcanvas" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-hand-pointer"></i> <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-start droite mx-5" tabindex="1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="navbarOffcanvasLabel"><a href="{{--route('index')--}}" class="nav-link">EvenAf</a></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ">

                    <li class="nav-item p-0 text-bold">
                        <a class="nav-link active" href="{{route('p.evenement')}}" aria-expanded="false">
                            Trouver les événements
                        </a>
                    </li>
                    <li class="nav-item text-bold">
                        <div class="w-15 text-bold">
                            <ul class="list-unstyled text-bold">
                                <li class="nav-item dropdown text-bold">
                                    <a class="nav-link text-bold" href="#" aria-expanded="false">
                                        Créer un événement
                                    </a>
                                    <ul class="dropdown-menu text-bold">
                                        <li><a class="dropdown-item text-bold" href="{{--route('dashboard')--}}">Ouvrir un compte</a></li>
                                        <li><a class="dropdown-item text-bold" href="">Connexion</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item text-bold">
                        <a class="nav-link" aria-current="page" href="{{route('p.a-propos')}}">À propos</a>
                    </li>
                    <li class="nav-item text-bold">
                        <a class="nav-link" aria-current="page" href="{{route('p.faq')}}">FAQ</a>
                    </li>
                    <li class="nav-item ">

                            <ul class="list-unstyled">
                                <li class="nav-item dropdown text-bold">
                                    <a class="nav-link text-bold" href="" aria-expanded="false">
                                      <img src="{{asset('asset/svgs/solid/user.svg')}}" alt="" class="" style="width: 25px">
                                    </a>
                                    <ul class="dropdown-menu text-bold">
                                        <li><a class="dropdown-item text-bold" href="#"><i class="fa fa-sign-out "></i> Deconnexion</a></li>
                                        <li><a class="dropdown-item text-bold" href="{{route('organisateur.dashboard')}}"><i class="fa fa-sign-in "></i> organisateur</a></li>
                                    </ul>
                                </li>
                            </ul>

                    </li>


            </ul>

        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

