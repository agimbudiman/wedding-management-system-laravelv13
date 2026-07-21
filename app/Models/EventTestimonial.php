<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTestimonial extends Model
{
    protected $fillable = ['event_id', 'rating', 'testimony'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
