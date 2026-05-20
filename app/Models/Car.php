<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['formatted_price'];

    protected $casts = [
        'images' => 'array',
        'features' => 'array',
        'includes' => 'array',
        'pricingDetails' => 'array',
        'translations' => 'array',
        'isFeatured' => 'boolean',
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'carId');
    }

    public function resolveImageUrl($path)
    {
        if (empty($path)) {
            return asset('images/placeholder-car.webp');
        }
        
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Standard storage path
        return asset('storage/' . $path);
    }

    public function getFormattedPriceAttribute()
    {
        return \App\Helpers\CurrencyHelper::formatPrice($this->price);
    }
}
