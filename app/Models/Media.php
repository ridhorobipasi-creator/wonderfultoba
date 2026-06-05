<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin \Eloquent
 */

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
        'order_priority',
        'thumb',
        'dominant_color',
        'blur_hash',
        'exif_data',
    ];

    protected $casts = [
        'exif_data' => 'array',
    ];

    protected $appends = ['url', 'thumbnail_url', 'usage_count'];

    /**
     * Apakah aset ini adalah file statis (dari public/images/)?
     */
    public function getIsStaticAssetAttribute(): bool
    {
        return str_starts_with($this->path, '_static/');
    }

    /**
     * Kembalikan path relatif asli (tanpa prefix _static/).
     */
    public function getRealPublicPathAttribute(): string
    {
        if ($this->is_static_asset) {
            return ltrim(substr($this->path, strlen('_static')), '/');
        }
        return $this->path;
    }

    public function getUrlAttribute()
    {
        if ($this->is_static_asset) {
            // _static/images/sumut/berastagi.webp → /images/sumut/berastagi.webp
            return '/' . ltrim(substr($this->path, strlen('_static')), '/');
        }
        return '/storage/' . ltrim($this->path, '/');
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->is_static_asset) {
            // Static assets tidak punya thumbnail terpisah — gunakan URL asli
            return $this->url;
        }

        $dir = dirname($this->path);
        $file = basename($this->path);
        $thumbPath = $dir.'/thumbnails/'.$file;

        if (Storage::disk('public')->exists($thumbPath)) {
            return '/storage/' . ltrim($thumbPath, '/');
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
                } elseif (is_string($val)) {
                    $substrings[] = $val;
                }
            }
        } catch (\Exception $e) {
        }

        // 2. Blogs
        try {
            $blogImages = Blog::pluck('image')->filter()->toArray();
            foreach ($blogImages as $img) {
                $direct[$img] = ($direct[$img] ?? 0) + 1;
            }
        } catch (\Exception $e) {
        }

        // 3. Packages
        try {
            $packageImages = Package::pluck('images')->filter()->toArray();
            foreach ($packageImages as $imgs) {
                if (is_array($imgs)) {
                    foreach ($imgs as $img) {
                        $direct[$img] = ($direct[$img] ?? 0) + 1;
                    }
                } elseif (is_string($imgs)) {
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
        } catch (\Exception $e) {
        }

        // 4. Gallery
        try {
            $galleryImages = GalleryImage::pluck('imageUrl')->filter()->toArray();
            foreach ($galleryImages as $img) {
                $direct[$img] = ($direct[$img] ?? 0) + 1;
            }
        } catch (\Exception $e) {
        }

        self::$usedPathsCache = [
            'direct' => $direct,
            'substrings' => $substrings,
        ];

        return self::$usedPathsCache;
    }

    public function getUsageCountAttribute()
    {
        $rawPath = $this->path;
        $count = 0;

        $cache = self::getUsedPaths();

        // Daftar semua variasi path yang mungkin digunakan
        $variants = [$rawPath];
        if ($this->is_static_asset) {
            // _static/images/sumut/berastagi.webp → /images/sumut/berastagi.webp
            $variants[] = '/' . ltrim(substr($rawPath, strlen('_static')), '/');
            $variants[] = ltrim(substr($rawPath, strlen('_static')), '/');
        } else {
            $variants[] = '/storage/' . $rawPath;
        }

        // Count direct matches
        foreach ($variants as $v) {
            if (isset($cache['direct'][$v])) {
                $count += $cache['direct'][$v];
            }
        }

        // Count substring matches in settings
        foreach ($cache['substrings'] as $str) {
            foreach ($variants as $v) {
                if (str_contains($str, $v)) {
                    $count++;
                    break; // cukup hitung sekali per setting string
                }
            }
        }

        return $count;
    }

    public function getUsageDetailsAttribute()
    {
        $rawPath = $this->path;
        // Buat semua variant path yang mungkin dipakai di DB lain
        $pathVariants = [$rawPath];
        if ($this->is_static_asset) {
            $pathVariants[] = '/' . ltrim(substr($rawPath, strlen('_static')), '/');
            $pathVariants[] = ltrim(substr($rawPath, strlen('_static')), '/');
        } else {
            $pathVariants[] = '/storage/' . ltrim($rawPath, '/');
        }
        $storagePath = $pathVariants[1] ?? $pathVariants[0];
        $details = [];

        // 1. Check Packages (Tour Packages)
        try {
            $packages = \App\Models\Package::with('packageImages')->get();
            foreach ($packages as $pkg) {
                $used = false;
                // Check packageImages relation
                if ($pkg->packageImages) {
                    foreach ($pkg->packageImages as $img) {
                        if (in_array($img->image_path, $pathVariants)) {
                            $used = true;
                            break;
                        }
                    }
                }
                // Check images field
                if (!$used) {
                    $imgs = $pkg->images;
                    if (is_array($imgs)) {
                        foreach ($pathVariants as $v) {
                            if (in_array($v, $imgs)) { $used = true; break; }
                        }
                    } elseif (is_string($imgs)) {
                        $decoded = json_decode($imgs, true);
                        if (is_array($decoded)) {
                            foreach ($pathVariants as $v) {
                                if (in_array($v, $decoded)) { $used = true; break; }
                            }
                        } else {
                            if (in_array($imgs, $pathVariants)) { $used = true; }
                        }
                    }
                }

                if ($used) {
                    $details[] = [
                        'type' => 'Paket Wisata',
                        'name' => $pkg->translated_name ?? $pkg->name,
                        'edit_url' => route('admin.packages.edit', $pkg->id),
                    ];
                }
            }
        } catch (\Exception $e) {
        }

        // 2. Check Blogs (Blog Posts)
        try {
            $blogs = \App\Models\Blog::all();
            foreach ($blogs as $blog) {
                if (in_array($blog->image, $pathVariants)) {
                    $details[] = [
                        'type' => 'Artikel Blog',
                        'name' => $blog->translated_title ?? $blog->title,
                        'edit_url' => route('admin.blogs.edit', $blog->id),
                    ];
                }
            }
        } catch (\Exception $e) {
        }

        // 3. Check Gallery
        try {
            $galleries = \App\Models\GalleryImage::all();
            foreach ($galleries as $gal) {
                if (in_array($gal->imageUrl, $pathVariants)) {
                    $details[] = [
                        'type' => 'Foto Galeri',
                        'name' => $gal->title ?? ('Galeri #' . $gal->id),
                        'edit_url' => route('admin.gallery.index'),
                    ];
                }
            }
        } catch (\Exception $e) {
        }

        // 4. Check Settings (Global Settings)
        try {
            $settings = \App\Models\Setting::all();
            foreach ($settings as $setting) {
                $val = $setting->value;
                $used = false;
                $encoded = is_array($val) ? json_encode($val) : (string) $val;
                foreach ($pathVariants as $v) {
                    if (str_contains($encoded, $v)) {
                        $used = true;
                        break;
                    }
                }

                if ($used) {
                    $details[] = [
                        'type' => 'Pengaturan Global',
                        'name' => 'Setting: ' . $setting->key,
                        'edit_url' => route('admin.settings.general.index'),
                    ];
                }
            }
        } catch (\Exception $e) {
        }

        return $details;
    }

    public function getIsOrphanAttribute()
    {
        return $this->usage_count === 0;
    }
}
