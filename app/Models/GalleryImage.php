<?php

namespace App\Models;

use App\Traits\HasImageFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */

class GalleryImage extends Model
{
    use \App\Traits\Syncable, HasImageFallback, SoftDeletes;

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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->resolveImageUrl($this->attributes['imageUrl'] ?? null);
    }
}
