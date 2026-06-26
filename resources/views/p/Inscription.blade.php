@extends('layouts.base')
@section('title','creer un compte')
@section('content')
<div class="container mt-5">
<div class="mt-5"> <br>
<br>
<br>
<br>
<br>


<style>

</style>

<div class="inscription-container">
    <h2>Créez votre compte</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom(s)" required>
        <input type="email" name="email" placeholder="Adresse email" required>

        <div class="phone-input">
            <input type="tel" id="phone" name="phone" placeholder="Numéro de téléphone" required>
            <label for="phone"></label> <!-- Remplacez par l'indicatif du pays souhaité -->
        </div>

        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>

        <button type="submit">S'inscrire</button>
    </form>


</div>
</div>
@endsection

