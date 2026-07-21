<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'content',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
