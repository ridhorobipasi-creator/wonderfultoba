<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */

class Client extends Model
{
    use Syncable;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name', 'logo', 'logo_id', 'websiteUrl', 'orderPriority', 'isActive',
    ];

    protected $casts = [
        'isActive' => 'boolean',
    ];

    protected $appends = ['logo_url'];

    public function logoMedia()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function getLogoUrlAttribute(): string
    {
        // Priority: media relation > legacy logo field > fallback
        if ($this->logoMedia) {
            return $this->logoMedia->url;
        }
        
        if ($this->logo) {
            return asset('storage/' . ltrim($this->logo, '/'));
        }
        
        return asset('images/default-logo.png');
    }
}
