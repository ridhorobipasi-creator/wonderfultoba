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
        'imageUrl', 'image_id', 'caption', 'category', 'tags', 'eventDate', 'orderPriority', 'isActive',
    ];

    protected $casts = [
        'tags' => 'array',
        'eventDate' => 'datetime',
        'isActive' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function imageMedia()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function getImageUrlAttribute()
    {
        // Priority: media relation > legacy imageUrl field > fallback
        if ($this->imageMedia) {
            return $this->imageMedia->url;
        }
        
        return $this->resolveImageUrl($this->attributes['imageUrl'] ?? null);
    }
}
