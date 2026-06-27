<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Participer extends Pivot
{
    protected $table = 'participer';

    public $timestamps = true;
}
