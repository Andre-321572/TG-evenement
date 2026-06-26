<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
   use RegistersUsers;

   protected $redirectTo = '/';

   public function __construct()
   {
       $this->middleware('guest');
   }

   protected function validator(array $data)
   {
       return Validator::make($data, [
           'nom' => ['required', 'string', 'max:255'],
           'prenom' => ['required', 'string', 'max:255'],
           'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
           'phone' => ['required', 'string', 'min:8', 'max:12', 'unique:users'],
           'password' => ['required', 'string', 'min:8', 'confirmed'],
           'lien_facebook' => ['nullable', 'string'],
           'lien_instagram' => ['nullable', 'string'],
           'lien_web' => ['nullable', 'string', 'url'],
           'img_profil' => ['nullable', 'image', 'max:2048'],
       ]);
   }

   protected function create(array $data)
   {
       $imgProfilPath = null;

       if (isset($data['img_profil']) && $data['img_profil'] instanceof \Illuminate\Http\UploadedFile) {
           $imgProfilPath = $data['img_profil']->store('user/profile_images', 'public');
       }

       return User::create([
           'nom' => $data['nom'],
           'prenom' => $data['prenom'],
           'email' => $data['email'],
           'phone' => $data['phone'],
           'password' => Hash::make($data['password']),
           'lien_facebook' => $data['lien_facebook'] ?? null,
           'lien_instagram' => $data['lien_instagram'] ?? null,
           'lien_web' => $data['lien_web'] ?? null,
           'img_profil' => $imgProfilPath ?? null,
       ]);
   }

   public function register(Request $request)
   {
       try {
           Log::info('Données reçues :', $request->all());

           $validator = $this->validator($request->all());

           if ($validator->fails()) {
               Log::error('Erreurs de validation :', $validator->errors()->toArray());
               return redirect()->back()
                   ->withErrors($validator)
                   ->withInput();
           }

           $data = $request->all();

           if ($request->hasFile('img_profil')) {
               $data['img_profil'] = $request->file('img_profil');
               Log::info('Image de profil reçue');
           }

           Log::info('Données avant création :', $data);

           $user = $this->create($data);

           Log::info('Utilisateur créé :', $user->toArray());

           $this->guard()->login($user);

           return redirect($this->redirectTo);

       } catch (\Exception $e) {
           Log::error('Erreur lors de l\'inscription : ' . $e->getMessage());
           Log::error('Stack trace : ' . $e->getTraceAsString());

           return redirect()->back()
               ->with('error', 'Une erreur est survenue lors de l\'inscription.')
               ->withInput();
       }
   }
}
