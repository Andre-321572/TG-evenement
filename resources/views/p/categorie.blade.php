

  <div class="d-flex flex-column flex-shrink-0    w-75  fixed" style="margin-top:10px">

      <ul class="nav nav-pills flex-column mb-auto">
        <li class="li">
          <a href="{{ route('p.conferences et congres') }}" class="nav-link text-dark" aria-current="page">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
            Conferences et congres
          </a>
        </li>
        <li class="li">
          <a href="{{ route('p.vie nocturne') }}" class="nav-link text-black">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"/></svg>
            Vie nocturne
          </a>
        </li>
        <li class="li">
          <a href="{{ route('p.evenement sportif') }}" class="nav-link text-black">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"/></svg>
            Sportif
          </a>
        </li>
        <li class="li">
          <a href="{{ route('p.fete') }}" class="nav-link text-black">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"/></svg>
            Fêtes
          </a>
        </li>
        <li class="li">
          <a href="{{ route('p.concert et festival de musique') }}" class="nav-link text-black">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
            Concerts et musiques
          </a>
        </li>
        <li class="li">
          <a href="{{ route('p.santé') }}" class="nav-link text-black">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
            Santé
          </a>
        </li>
        <li class="li">
          <a href="{{ route('p.voyage') }}" class="nav-link text-black">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
            Voyage
          </a>
        </li>
      </ul>
    </div>



    <style>
        .li:hover{
            background-color: rgb(9, 175, 28);
            border-radius: 4px;
        }

    </style>
