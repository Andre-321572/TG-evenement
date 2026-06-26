@extends('layouts.base')
@section('title','connexion')
@section('content')
<div class="container mt-5">
<div class="mt-5"> <br>
<br>
<br>
<br>
<br>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4b3;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #04883e;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #00b34e;
        }
        .forgot-password {
            text-align: right;
            margin-bottom: 10px;
        }
        .forgot-password a {
            color: #023b1b;
            text-decoration: none;
        }
    </style>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <form action="#" method="POST">

            <input type="text"   placeholder="Adresse email/téléphone" id="emailPhone" name="emailPhone" required>

            <input type="password" placeholder="Mot de passe" id="password" name="password" required>

            <div class="forgot-password">
                <a href="#">Mot de passe oublié ?</a>
            </div>

            <input type="submit" value="Connexion">
        </form>
    </div>
</body>
</html>


</div>
    </div>
    @endsection
