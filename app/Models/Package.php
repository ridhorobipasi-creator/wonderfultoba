<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use App\Traits\HasImageFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */

class Package extends Model
{
    use \App\Traits\Syncable, HasImageFallback, SoftDeletes;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'slug', 'name', 'shortDescription', 'description', 'locationTag',
        'price', 'childPrice', 'cost_price', 'priceDisplay', 'duration', 'images',
        'includes', 'excludes', 'pricingDetails', 'itinerary', 'itineraryText',
        'dronePrice', 'droneLocation', 'notes', 'status', 'isFeatured',
        'sortOrder', 'metaTitle', 'metaDescription',
        'translations', 'cityId',
    ];

    protected $appends = ['first_image', 'image_url', 'formatted_price'];

    protected $casts = [
        'images' => 'array',
        'includes' => 'array',
        'excludes' => 'array',
        'pricingDetails' => 'array',
        'itinerary' => 'array',
        'translations' => 'array',
        'isFeatured' => 'boolean',
        'price' => 'double',
        'childPrice' => 'double',
        'dronePrice' => 'double',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'cityId');
    }

    public function packageImages()
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort_order');
    }

    public function amenities()
    {
        return $this->hasMany(PackageAmenity::class);
    }

    public function packageIncludes()
    {
        return $this->hasMany(PackageAmenity::class)->where('type', 'include');
    }

    public function packageExcludes()
    {
        return $this->hasMany(PackageAmenity::class)->where('type', 'exclude');
    }

    public function getFirstImageAttribute()
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        return $this->resolveImageUrl($images[0] ?? null);
    }

    public function getImageUrlAttribute()
    {
        return $this->first_image;
    }

    public function getFormattedPriceAttribute()
    {
        return CurrencyHelper::formatPrice($this->price);
    }
}
