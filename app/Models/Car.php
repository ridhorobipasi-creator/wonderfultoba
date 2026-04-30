<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name', 'type', 'capacity', 'transmission', 'fuel', 'price',
        'priceWithDriver', 'images', 'description', 'terms', 'features',
        'includes', 'status', 'isFeatured', 'sortOrder', 'metaTitle',
        'metaDescription', 'pricingDetails', 'translations',
    ];

    protected $casts = [
        'images' => 'array',
        'features' => 'array',
        'includes' => 'array',
        'pricingDetails' => 'array',
        'translations' => 'array',
        'isFeatured' => 'boolean',
        'price' => 'double',
        'priceWithDriver' => 'double',
    ];
}
