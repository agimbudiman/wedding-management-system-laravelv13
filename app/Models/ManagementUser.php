<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManagementUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
        'avatar',
        'birth_date',
        'gender',
        'phone_number',
        'address',
        'status',
        'total_events_handled',
        'joined_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'joined_at' => 'date',
        ];
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_crews', 'management_user_id', 'event_id')
            ->withPivot('is_leader')
            ->withTimestamps();
    }

    public function role_relation()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission($permission)
    {
        if (!$this->role_relation) return false;
        return $this->role_relation->permissions()->where('name', $permission)->exists();
    }
}
