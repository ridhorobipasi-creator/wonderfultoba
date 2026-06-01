<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */

class PackageAmenity extends Model
{
    protected $fillable = ['package_id', 'name', 'type'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
