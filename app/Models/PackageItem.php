<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    protected $fillable = ['package_id', 'name'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
