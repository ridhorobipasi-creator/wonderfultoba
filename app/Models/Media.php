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

    protected static $usedPathsCache = null;

    public static function getUsedPaths()
    {
        if (self::$usedPathsCache !== null) {
            return self::$usedPathsCache;
        }

        $direct = [];
        $substrings = [];

        // 1. Settings
        try {
            $settings = Setting::all();
            foreach ($settings as $setting) {
                $val = $setting->value;
                if (is_array($val)) {
                    $substrings[] = json_encode($val);
                } else if (is_string($val)) {
                    $substrings[] = $val;
                }
            }
        } catch (\Exception $e) {}

        // 2. Blogs
        try {
            $blogImages = Blog::pluck('image')->filter()->toArray();
            foreach ($blogImages as $img) {
                $direct[$img] = ($direct[$img] ?? 0) + 1;
            }
        } catch (\Exception $e) {}

        // 3. Packages
        try {
            $packageImages = Package::pluck('images')->filter()->toArray();
            foreach ($packageImages as $imgs) {
                if (is_array($imgs)) {
                    foreach ($imgs as $img) {
                        $direct[$img] = ($direct[$img] ?? 0) + 1;
                    }
                } else if (is_string($imgs)) {
                    $decoded = json_decode($imgs, true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $img) {
                            $direct[$img] = ($direct[$img] ?? 0) + 1;
                        }
                    } else {
                        $direct[$imgs] = ($direct[$imgs] ?? 0) + 1;
                    }
                }
            }
        } catch (\Exception $e) {}

        // 4. Gallery
        try {
            $galleryImages = GalleryImage::pluck('imageUrl')->filter()->toArray();
            foreach ($galleryImages as $img) {
                $direct[$img] = ($direct[$img] ?? 0) + 1;
            }
        } catch (\Exception $e) {}

        self::$usedPathsCache = [
            'direct' => $direct,
            'substrings' => $substrings
        ];

        return self::$usedPathsCache;
    }

    public function getUsageCountAttribute()
    {
        $rawPath = $this->path;
        $storagePath = '/storage/' . $this->path;
        $count = 0;

        $cache = self::getUsedPaths();

        // Count direct matches
        if (isset($cache['direct'][$rawPath])) {
            $count += $cache['direct'][$rawPath];
        }
        if (isset($cache['direct'][$storagePath])) {
            $count += $cache['direct'][$storagePath];
        }

        // Count substring matches in settings
        foreach ($cache['substrings'] as $str) {
            if (str_contains($str, $rawPath) || str_contains($str, $storagePath)) {
                $count++;
            }
        }

        return $count;
    }

    public function getIsOrphanAttribute()
    {
        return $this->usage_count === 0;
    }
}
