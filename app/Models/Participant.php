<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;
    protected $fillable = ['nom','prenom','email','numero'];

    public function evenement(){
        return $this->belongsToMany(Evenement::class, 'reservations')
                    ->withPivot('place_reserver', 'status')
                    ->withTimestamps();
    }
    public function participer(){
        return $this->belongsToMany(Evenement::class, 'participer')
                    ->withPivot()
                    ->withTimestamps();
    }
    public function commentaire()
    {
        return $this->hasMany(Commentaire::class);
    }


}
