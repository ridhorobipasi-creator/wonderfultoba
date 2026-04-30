<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name', 'slug', 'type', 'country', 'region', 'district', 'place', 'description',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class, 'cityId');
    }
}
