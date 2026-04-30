<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageTier extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = ['tierName', 'category', 'tagline'];
}
