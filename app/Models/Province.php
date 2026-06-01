<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */

class Province extends Model
{
    protected $fillable = ['name'];

    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }
}
