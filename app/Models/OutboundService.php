<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundService extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'title', 'shortDesc', 'detailDesc', 'icon', 'image', 'orderPriority', 'isActive',
    ];

    protected $casts = [
        'isActive' => 'boolean',
    ];
}
