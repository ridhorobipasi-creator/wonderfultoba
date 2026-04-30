<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'slug', 'name', 'shortDescription', 'description', 'locationTag',
        'price', 'childPrice', 'priceDisplay', 'duration', 'images',
        'includes', 'excludes', 'pricingDetails', 'itinerary', 'itineraryText',
        'dronePrice', 'droneLocation', 'notes', 'status', 'isFeatured',
        'isOutbound', 'sortOrder', 'metaTitle', 'metaDescription',
        'translations', 'cityId',
    ];

    protected $casts = [
        'images' => 'array',
        'includes' => 'array',
        'excludes' => 'array',
        'pricingDetails' => 'array',
        'itinerary' => 'array',
        'translations' => 'array',
        'isFeatured' => 'boolean',
        'isOutbound' => 'boolean',
        'price' => 'double',
        'childPrice' => 'double',
        'dronePrice' => 'double',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'cityId');
    }
}
