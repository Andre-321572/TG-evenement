<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;
    protected $fillable = ['nom','logo','lien_web'];

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }
}
