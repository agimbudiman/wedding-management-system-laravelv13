<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_no',
        'event_id',
        'package_id',
        'custom_package_name',
        'custom_package_price',
        'payment_type',
        'amount',
        'payment_date',
        'notes',
        'proof_document',
        'status',
        'snap_token',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
