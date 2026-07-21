<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'category',
        'due_date',
        'management_user_id',
        'is_completed',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(ManagementUser::class, 'management_user_id');
    }
}
