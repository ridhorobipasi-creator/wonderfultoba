<?php

namespace App\Models;

use App\Traits\HasImageFallback;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */

class City extends Model
{
    use HasImageFallback;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $appends = ['image_url'];

    protected $fillable = [
        'regency_id', 'name', 'slug', 'type', 'country', 'region', 'district', 'place', 'description', 'image', 'image_id',
    ];

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'cityId');
    }

    public function imageMedia()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function getImageUrlAttribute(): string
    {
        // Priority: media relation > legacy image field > fallback
        if ($this->imageMedia) {
            return $this->imageMedia->url;
        }
        
        return $this->resolveImageUrl($this->image ?? null);
    }
}
