<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;
    protected $fillable = ['email','commentaire', 'note'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }


    protected $timestamp = true; // pour éviter les erreurs de timestamp
}
