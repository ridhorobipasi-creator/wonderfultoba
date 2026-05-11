<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Setting;
use App\Models\Blog;
use App\Models\Package;
use App\Models\GalleryImage;

use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'filename',
        'original_name',
        'path',
        'category',
        'mime_type',
        'size',
        'alt_text',
        'order_priority'
    ];

    protected $appends = ['url', 'thumbnail_url', 'usage_count'];

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        $dir = dirname($this->path);
        $file = basename($this->path);
        $thumbPath = $dir . '/thumbnails/' . $file;
        
        if (Storage::disk('public')->exists($thumbPath)) {
            return asset('storage/' . $thumbPath);
        }
        
        return $this->url; // Fallback to full size
    }

    public function getUsageCountAttribute()
    {
        $rawPath = $this->path;
        $storagePath = '/storage/' . $this->path;
        $count = 0;

        // 1. Check Settings (Slider & Brand)
        $settings = Setting::all();
        foreach ($settings as $setting) {
            if (is_array($setting->value)) {
                $stringified = json_encode($setting->value);
                if (str_contains($stringified, $rawPath) || str_contains($stringified, $storagePath)) {
                    $count++;
                }
            } else if (is_string($setting->value)) {
                if (str_contains($setting->value, $rawPath) || str_contains($setting->value, $storagePath)) {
                    $count++;
                }
            }
        }

        // 2. Check Blogs
        $count += Blog::where('image', $rawPath)
                    ->orWhere('image', $storagePath)
                    ->count();

        // 3. Check Packages
        $count += Package::where('images', 'LIKE', "%$rawPath%")
                    ->orWhere('images', 'LIKE', "%$storagePath%")
                    ->count();

        // 4. Check Gallery
        $count += GalleryImage::where('imageUrl', $rawPath)
                    ->orWhere('imageUrl', $storagePath)
                    ->count();

        return $count;
    }

    public function getIsOrphanAttribute()
    {
        return $this->usage_count === 0;
    }
}
