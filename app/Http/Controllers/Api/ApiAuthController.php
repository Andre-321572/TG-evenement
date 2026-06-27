<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    /**
     * Inscription via API.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|min:8|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Cet e-mail est déjà utilisé.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $imgProfilPath = null;
        if ($request->hasFile('img_profil')) {
            $imgProfilPath = $request->file('img_profil')->store('user/profile_images', 'public');
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'img_profil' => $imgProfilPath,
            'role' => 'utilisateur', // rôle par défaut
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Inscription réussie.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Connexion via API.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string', // peut être email ou téléphone
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $loginInput)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identifiants incorrects.'
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Connexion réussie.',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Déconnexion via API.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Déconnexion réussie.'
        ], 200);
    }

    /**
     * Profil de l'utilisateur connecté.
     */
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user()
        ], 200);
    }
}
