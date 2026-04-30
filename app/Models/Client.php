<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name', 'logo', 'websiteUrl', 'orderPriority', 'isActive',
    ];

    protected $casts = [
        'isActive' => 'boolean',
    ];
}
