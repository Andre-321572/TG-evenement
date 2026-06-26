<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item navbar-brand-mini-wrapper">
        <a class="nav-link navbar-brand brand-logo-mini" href="{{ route('organisateur.dashboard') }}"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
      </li>
      <li class="nav-item nav-category">
        <span class="nav-link"></span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('organisateur.dashboard') }}">
          <span class="menu-title">Dashboard</span>
          <i class="icon-screen-desktop menu-icon"></i>
        </a>
      </li>
      <li class="nav-item nav-category"><span class="nav-link"></span></li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-title">Gestion des evenements</span>
          <i class="icon-layers menu-icon "></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">

            <li class="nav-item"> <a class="nav-link text-white"
                href="{{ route('organisateur.ajouter-un-evenement') }}">Ajouter un évenement</a>
            </li>
            <li class="nav-item"> <a class="nav-link text-white" href="{{ route('organisateur.sponsor-form') }}">Gerer les
                    sponsors</a>
            </li>
            <li class="nav-item"> <a class="nav-link text-white" href="{{ route('organisateur.billet') }}">Gerer les
                    billets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('organisateur.historique') }}">Historique des évènements</a>
            </li>
            <li class="nav-item"> <a class="nav-link text-white"
                    href="{{ route('organisateur.evenement-en-cours') }}">Gerer les evenemnt en cour</a>
            </li>

          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
          <span class="menu-title">Avenir</span>
          <i class="icon-globe menu-icon"></i>
        </a>
        <div class="collapse" id="icons">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('organisateur.future.future') }}">Evenements avenir</a>
            </li>

          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#forms" aria-expanded="false" aria-controls="forms">
          <span class="menu-title">Evenements passer</span>
          <i class="icon-book-open menu-icon"></i>
        </a>
        <div class="collapse" id="forms">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{ route('organisateur.evenement-passe') }}">Evenements passer</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
          <span class="menu-title">Discussion</span>
          <i class="icon-chart menu-icon"></i>
        </a>
        <div class="collapse" id="charts">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{-- route('organisateur.chat') --}}">Discusion site</a></li>
            <li class="nav-item"> <a class="nav-link" href="">Les commentaire</a></li>
          </ul>
        </div>
      </li>
      {{--<li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
          <span class="menu-title">Tables</span>
          <i class="icon-grid menu-icon"></i>
        </a>
        <div class="collapse" id="tables">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="l">Basic Table</a></li>
          </ul>
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic Table</a></li>
          </ul>
        </div>
      </li>--}}
    </ul>
  </nav>
