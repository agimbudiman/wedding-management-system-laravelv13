<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventGuestBook extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'address'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
