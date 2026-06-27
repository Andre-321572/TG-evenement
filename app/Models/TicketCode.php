<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCode extends Model
{
    protected $table = 'ticket_codes';

    protected $fillable = [
        'code',
        'evenement_id',
        'billet_id',
        'stripe_session_id',
        'buyer_email',
        'buyer_name',
        'is_scanned',
        'scanned_at',
        'scanned_by',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'is_scanned'  => 'boolean',
    ];

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    public function billet()
    {
        return $this->belongsTo(Billet::class);
    }

    public function scannedByUser()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
