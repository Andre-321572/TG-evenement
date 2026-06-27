<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;  // Ajoutez cet import
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable  // Changez Model par Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  // Enlevez le doublon de HasFactory

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'phone',
        'password',
        'role',
        'lien_facebook',
        'lien_instagram',
        'lien_web',
        'img_profil'
    ];

    // Le reste de votre code reste identique
    public function Evenement()
    {
        return $this->hasMany(Evenement::class);
    }

    // ... autres méthodes ...

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUtilisateur()
    {
        return $this->role === 'utilisateur';
    }

    public function isScanner()
    {
        return $this->role === 'scanner';
    }

    public function ticketCodes()
    {
        return $this->hasMany(\App\Models\TicketCode::class, 'scanned_by');
    }
}
