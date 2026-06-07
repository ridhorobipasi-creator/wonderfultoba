<?php

namespace App\Models;

use App\Support\Lqip;
use App\Traits\HasImageFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryImage extends Model
{
    use \App\Traits\Syncable, HasImageFallback, SoftDeletes;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'imageUrl', 'caption', 'category', 'tags', 'eventDate', 'orderPriority', 'isActive', 'placeholder',
    ];

    protected static function booted(): void
    {
        static::saving(function (GalleryImage $img) {
            $src = $img->attributes['imageUrl'] ?? null;
            if (empty($img->placeholder) && ! empty($src)) {
                $img->placeholder = Lqip::fromStoredPath($src);
            }
        });
    }

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
