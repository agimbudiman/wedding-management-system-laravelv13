<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'groom_name',
        'bride_name',
        'client_name',
        'client_address',
        'client_phone',
        'client_email',
        'date',
        'venue',
        'google_maps_link',
        'type',
        'status',
        'client_qr_token',
        'is_client_qr_active',
        'guest_qr_token',
        'is_guest_qr_active',
        'slug',
        'personalization',
    ];

    protected $casts = [
        'date' => 'date',
        'personalization' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function crews()
    {
        return $this->belongsToMany(ManagementUser::class, 'event_crews', 'event_id', 'management_user_id')
                    ->withPivot('is_leader')
                    ->withTimestamps();
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'event_vendors', 'event_id', 'vendor_id')
                    ->withTimestamps();
    }

    public function todos()
    {
        return $this->hasMany(EventTodo::class);
    }

    public function rundowns()
    {
        return $this->hasMany(EventRundown::class);
    }

    public function notes()
    {
        return $this->hasOne(EventNote::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function guestBooks()
    {
        return $this->hasMany(EventGuestBook::class);
    }

    public function testimonial()
    {
        return $this->hasOne(EventTestimonial::class);
    }
}
