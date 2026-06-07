<?php

namespace App\Models;

use App\Support\Lqip;
use App\Traits\HasImageFallback;
use Illuminate\Database\Eloquent\Model;

class PackageImage extends Model
{
    use HasImageFallback;

    protected $fillable = ['package_id', 'image_path', 'sort_order', 'placeholder'];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        // Generate LQIP otomatis bila belum ada (mencakup semua jalur upload)
        static::saving(function (PackageImage $img) {
            if (empty($img->placeholder) && ! empty($img->image_path)) {
                $img->placeholder = Lqip::fromStoredPath($img->image_path);
            }
        });
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->resolveImageUrl($this->image_path);
    }
}
