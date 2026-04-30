<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'imageUrl', 'caption', 'category', 'tags', 'eventDate', 'orderPriority', 'isActive',
    ];

    protected $casts = [
        'tags' => 'array',
        'eventDate' => 'datetime',
        'isActive' => 'boolean',
    ];
}
