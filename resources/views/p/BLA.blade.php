@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-lg-11">

            <h2>Tutoriel Laravel 9 CRUD</h2>

        </div>

        <div class="col-lg-1">
            <a class="btn btn-success" href="{{ url('personnage/create') }}">Ajouter</a>
        </div>

    </div>



    @if ($message = Session::get('success'))

        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif



    <table class="table table-bordered">

        <tr>

            <th>No</th>
            <th>Nom</th>
            <th>Détail</th>
            <th>Company</th>
            <th>fortune</th>
            <th>Actions</th>

        </tr>

        @foreach ($personnages as $index => $personnage)

            <tr>
                <td>{{ $index }}</td>
                <td>{{ $personnage->nom }}</td>
                <td>{{ $personnage->detail }}</td>
                <td>{{ $personnage->company }}</td>
                <td>{{ $personnage->fortune }}</td>
                <td>

                    <form action="{{ url('personnage/'. $personnage->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <a class="btn btn-info" href="{{ url('personnage/'. $personnage->id) }}">Voir</a>
                        <a class="btn btn-primary" href="{{ url('personnage/'. $personnage->id .'/edit') }}">Modifier</a>

                        <button type="submit" class="btn btn-danger">Supprimer</button>

                    </form>
                </td>

            </tr>

        @endforeach
    </table>

@endsection


personnage/create.blade.php

@extends('layouts.app')


@section('content')

    <h1>Ajouter un personnage</h1>


    @if ($errors->any())

        <div class="alert alert-danger">

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ url('personnage') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="nom">Nom :</label>
            <input type="text" class="form-control" id="nom" placeholder="Entrez un nom" name="nom">
        </div>

        <div class="form-group mb-3">

            <label for="company">Company:</label>
            <input type="text" class="form-control" id="company" placeholder="Company" name="company">

        </div>

        <div class="form-group mb-3">
            <label for="fortune">Fortune ($):</label>
            <input type="number" class="form-control" id="fortune" placeholder="fortune" name="fortune">
        </div>

        <div class="form-group mb-3">
            <label for="detail">Détail:</label>
            <textarea class="form-control" id="detail" name="detail" rows="10" placeholder="Détail"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enregister</button>

    </form>

@endsection
personnage/show.blade.php

@extends('layouts.app')


@section('content')

    <h1>Tutoriel Laravel 9 CRUD</h1>


    <table class="table table-bordered">

        <tr>
            <th>Nom:</th>
            <td>{{ $personnage->nom }}</td>
        </tr>

        <tr>

            <th>Company:</th>
            <td>{{ $personnage->company }}</td>

        </tr>

        <tr>

            <th>détail:</th>
            <td>{{ $personnage->detail }}</td>

        </tr>

        <tr>

            <th>Fortune:</th>
            <td>$ {{ $personnage->fortune }}</td>

        </tr>

    </table>

@endsection
personnage/edit.blade.php

@extends('layouts.app')


@section('content')


    <h1>Modifier Personnage</h1>


    @if ($errors->any())

        <div class="alert alert-danger">

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    <form method="post" action="{{ url('personnage/'. $personnage->id) }}" >
        @method('PATCH')
        @csrf


        <div class="form-group mb-3">

            <label for="nom">Nom:</label>
            <input type="text" class="form-control" id="nom" placeholder="Entrer Nom" name="nom" value="{{ $personnage->nom }}">

        </div>

        <div class="form-group mb-3">

            <label for="company">Company:</label>
            <input type="text" class="form-control" id="company" placeholder="Entrer Company" name="company" value="{{ $personnage->company }}">

        </div>

        <div class="form-group mb-3">

            <label for="fortune">Fortune ($):</label>
            <input type="number" class="form-control" id="fortune" placeholder="fortune" name="fortune" value="{{ $personnage->fortune }}">

        </div>

        <div class="form-group mb-3">

            <label for="detail">Détail:</label>
            <textarea class="form-control" id="detail" name="detail" rows="10" placeholder="Détail">{{ $personnage->detail }}</textarea>

        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>

    </form>

@endsection
