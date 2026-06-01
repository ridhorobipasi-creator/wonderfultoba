<?php

namespace App\Models;

use App\Traits\HasImageFallback;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */

class PackageImage extends Model
{
    use HasImageFallback;

    protected $fillable = ['package_id', 'image_path', 'sort_order'];

    protected $appends = ['image_url'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->resolveImageUrl($this->image_path);
    }
}
