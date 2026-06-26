<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas bg-body-secondary position-fixed h-100" id="sidebar">

    <ul class="nav h-100">
        <li class="nav-item profile mt-5">
            <div class="sidebar-brand-wrapper wrap d-none d-lg-flex align-items-center justify-content-center fixed-top bg-white"
                style="height: 100px">
                <a class="sidebar-brand brand-logo h-100" href="{{ route('organisateur.dashboard') }}"><img
                        class="w-100 h-100" src="{{ asset('asset/image/EvenAf.png') }}" alt="logo" /></a>
                <a class="sidebar-brand brand-logo-mini h-50" href="{{ route('organisateur.dashboard') }}"><img
                        class="w-100 h-100" src="{{ asset('asset/image/EvenAf.png') }}" alt="logo" /></a>
            </div>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('organisateur.dashboard') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">dashbord</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#gestion-des-evenement" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-laptop"></i>
                </span>
                <span class="menu-title">Gestion des evenements</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="gestion-des-evenement">
                <ul class="nav flex-column sub-menu">

                    <li class="nav-item"> <a class="nav-link"
                            href="{{ route('organisateur.ajouter-un-evenement') }}">Ajouter un évenement</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('organisateur.sponsor-form') }}">Gerer les
                            sponsors</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('organisateur.billet-form') }}">Gerer les
                            billets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('organisateur.historique') }}">Historique des évènements</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link"
                            href="{{ route('organisateur.evenement-en-cours') }}">Gerer les evenemnt en cour</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('organisateur.future.future') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-playlist-play"></i>
                </span>
                <span class="menu-title">Evenements avenir</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('organisateur.evenement-passe') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-table-large"></i>
                </span>
                <span class="menu-title">Evenements passer</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('organisateur.chat') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-chart-bar"></i>
                </span>
                <span class="menu-title">Discusion site</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="#">
                <span class="menu-icon">
                    <i class="mdi mdi-contacts"></i>
                </span>
                <span class="menu-title">Les commentaire</span>
            </a>
        </li>

    </ul>
</nav>
<!-- partial -->
<div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar p-0 fixed-top d-flex flex-row " style="background-color: rgb(97, 94, 94)">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand bg-body-secondary brand-logo-mini w-100 h-100 text-white"
                style="border-radius: 100px solid" href="{{ route('organisateur.dashboard') }}"><img
                    class="w-100 h-100 bg-white " style="border-radius: 100%"
                    src="{{ asset('asset/image/EvenAf.png') }}" alt="logo" />EventAf</a>
        </div>
        <div class="navbar-menu-wrapper flex-grow  d-flex align-items-stretch">

            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-menu text-white"></span>
            </button>
            <ul class="navbar-nav w-100">
                <li class="nav-item w-100">

                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
               
                <li class="nav-item nav-settings d-none d-lg-block">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-view-grid"></i>
                    </a>
                </li>
                <li class="nav-item dropdown border-left">
                    <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#"
                        data-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-email"></i>
                        <span class="count bg-success"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                        aria-labelledby="messageDropdown">
                        <h6 class="p-3 mb-0">Messages</h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img src="assets/images/faces/face4.jpg" alt="image"
                                    class="rounded-circle profile-pic">
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject ellipsis mb-1">Mark send you a message</p>
                                <p class="text-muted mb-0"> 1 Minutes ago </p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img src="assets/images/faces/face2.jpg" alt="image"
                                    class="rounded-circle profile-pic">
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject ellipsis mb-1">Cregh send you a message</p>
                                <p class="text-muted mb-0"> 15 Minutes ago </p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img src="assets/images/faces/face3.jpg" alt="image"
                                    class="rounded-circle profile-pic">
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject ellipsis mb-1">Profile picture updated</p>
                                <p class="text-muted mb-0"> 18 Minutes ago </p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <p class="p-3 mb-0 text-center">4 new messages</p>
                    </div>
                </li>
                <li class="nav-item dropdown border-left">
                    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                        data-toggle="dropdown">
                        <i class="mdi mdi-bell"></i>
                        <span class="count bg-danger"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                        aria-labelledby="notificationDropdown">
                        <h6 class="p-3 mb-0">Notifications</h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-calendar text-success"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">Event today</p>
                                <p class="text-muted ellipsis mb-0"> Just a reminder that you have an event today </p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-settings text-danger"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">Settings</p>
                                <p class="text-muted ellipsis mb-0"> Update dashboard </p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-link-variant text-warning"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">Launch Admin</p>
                                <p class="text-muted ellipsis mb-0"> New admin wow! </p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <p class="p-3 mb-0 text-center">See all notifications</p>
                    </div>
                </li>
                <li class="nav-item dropdown text-bg-light rounded-5">
                    <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                        <div class="navbar-profile">
                            <!--img class="img-xs rounded-circle" src="assets/images/faces/face15.jpg" alt=""-->
                            <i class="fa fa-user-circle img-xs rounded-circle"></i>
                            <p class="mb-0 d-none d-sm-block navbar-profile-name"> {{ Auth::user()->nom }}
                                {{ Auth::user()->prenom }} </p>
                            <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list">
                        <a href='' class="dropdown-item preview-item ">
                            <div class="preview-thumbnail">
                                <i class="fa fa-box-open text-success"></i>profil
                            </div>
                        </a>

                        <a class="dropdown-item preview-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout text-danger"></i>{{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>



                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
                <span class="mdi mdi-format-line-spacing text-white"></span>
            </button>
        </div>
    </nav>
