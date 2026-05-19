<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\City;
use App\Models\GalleryImage;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TourService
{
    /**
     * Get tour-specific landing page content
     */
    public function getTourSettings(): array
    {
        return Cache::remember('tour_settings', 3600, function() {
            return Setting::where('key', 'cms_tour')->first()?->value ?? [];
        });
    }

    /**
     * Get featured tour packages for homepage/tour page.
     * Priority: 1) Admin-pinned IDs from CMS, 2) isFeatured flag, 3) latest active packages.
     */
    public function getFeaturedPackages(int $limit = 3): Collection
    {
        return Cache::remember("featured_packages_{$limit}", 3600, function() use ($limit) {
            // Check if admin has manually pinned specific package IDs via CMS
            $cmsSettings = $this->getTourSettings();
            $pinnedIds = $cmsSettings['featured_package_ids'] ?? [];

            if (!empty($pinnedIds) && is_array($pinnedIds)) {
                // Load pinned packages in the exact order the admin set
                $packages = Package::with(['packageImages', 'city'])
                    ->where('status', 'active')
                    ->where('isOutbound', false)
                    ->whereIn('id', $pinnedIds)
                    ->get()
                    ->sortBy(fn($pkg) => array_search($pkg->id, $pinnedIds))
                    ->values();

                if ($packages->count() > 0) {
                    return $packages->take($limit);
                }
            }

            // Fallback: use isFeatured flag
            $featured = Package::with(['packageImages', 'city'])
                ->where('status', 'active')
                ->where('isOutbound', false)
                ->where('isFeatured', true)
                ->take($limit)
                ->get();

            if ($featured->count() > 0) {
                return $featured;
            }

            // Last resort: take any active tour packages
            return Package::with(['packageImages', 'city'])
                ->where('status', 'active')
                ->where('isOutbound', false)
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get all active tour packages
     */
    public function getAllPackages(): Collection
    {
        return Cache::remember('all_tour_packages', 3600, function() {
            return Package::with(['packageImages', 'city'])
                ->where('status', 'active')
                ->where('isOutbound', false)
                ->get();
        });
    }

    /**
     * Get a specific tour package by slug
     */
    public function getPackageBySlug(string $slug): ?Package
    {
        return Cache::remember("package_detail_{$slug}", 3600, function() use ($slug) {
            return Package::with(['packageImages', 'packageIncludes', 'packageExcludes', 'city'])
                ->where('slug', $slug)
                ->first();
        });
    }

    /**
     * Get tour gallery images
     */
    /**
     * Get unified gallery images from multiple sources
     */
    public function getGallery(): Collection
    {
        return Cache::remember('unified_gallery', 3600, function() {
            // 1. Get explicit gallery images
            $galleryImages = GalleryImage::where('isActive', true)
                ->latest('orderPriority')
                ->get()
                ->map(function($img) {
                    return [
                        'image_url' => $img->image_url,
                        'caption' => $img->caption,
                        'category' => $img->category === 'tour' ? 'Wisata' : ($img->category === 'outbound' ? 'Outbound' : $img->category),
                        'type' => 'gallery'
                    ];
                });

            // 2. Get images from active packages (featured only to keep it clean)
            $packageImages = Package::where('status', 'active')
                ->with('packageImages')
                ->latest()
                ->get()
                ->map(function($pkg) {
                    $img = $pkg->packageImages->first()?->image_url ?? $pkg->resolveImageUrl($pkg->images[0] ?? null);
                    if (!$img || str_contains($img, 'unsplash')) return null;
                    return [
                        'image_url' => $img,
                        'caption' => $pkg->name,
                        'category' => 'Paket Wisata',
                        'type' => 'package',
                        'slug' => $pkg->slug
                    ];
                })->filter();

            // 3. Get images from latest blogs
            $blogImages = Blog::where('status', 'published')
                ->latest()
                ->limit(20)
                ->get()
                ->map(function($post) {
                    $img = $post->image_url;
                    if (!$img || str_contains($img, 'unsplash')) return null;
                    return [
                        'image_url' => $img,
                        'caption' => $post->title,
                        'category' => 'Berita & Tips',
                        'type' => 'blog',
                        'slug' => $post->slug
                    ];
                })->filter();

            return $galleryImages->merge($packageImages)->merge($blogImages)->shuffle();
        });
    }

    /**
     * Get published blogs
     */
    public function getBlogs(int $limit = null): Collection
    {
        $cacheKey = $limit ? "published_blogs_{$limit}" : "published_blogs_all";
        return Cache::remember($cacheKey, 3600, function() use ($limit) {
            $query = Blog::where('status', 'published')
                ->orderBy('createdAt', 'desc');

            return $limit ? $query->take($limit)->get() : $query->get();
        });
    }

    /**
     * Get blog post by slug
     */
    public function getBlogPost(string $slug): ?Blog
    {
        return Cache::remember("blog_detail_{$slug}", 3600, function() use ($slug) {
            return Blog::where('slug', $slug)
                ->orWhere('id', $slug)
                ->first();
        });
    }

    /**
     * Get related blog posts
     */
    public function getRelatedBlogs(int $excludeId, int $limit = 3): Collection
    {
        return Blog::where('id', '!=', $excludeId)
            ->where('status', 'published')
            ->take($limit)
            ->get();
    }

    /**
     * Get all cities for filtering
     */
    public function getCities(): Collection
    {
        return City::all();
    }

    /**
     * Search packages and blogs
     */
    public function search(string $query): array
    {
        $packages = Package::with(['packageImages', 'city'])
            ->where('status', 'active')
            ->where('isOutbound', false)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('shortDescription', 'like', "%{$query}%");
            })
            ->get();

        $blogs = Blog::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return [
            'packages' => $packages,
            'blogs' => $blogs,
        ];
    }

    /**
     * Clear all tour related cache
     */
    public function clearCache($slug = null)
    {
        Cache::forget('tour_settings');
        Cache::forget('outbound_settings');
        Cache::forget('all_tour_packages');
        Cache::forget('unified_gallery');
        Cache::forget('published_blogs_all');
        Cache::forget('published_blogs_3');
        Cache::forget('featured_packages_3');
        Cache::forget('featured_packages_10');
        Cache::forget('cars_active');
        Cache::forget('admin_dashboard_stats');
        Cache::forget('site_settings_global');

        if ($slug) {
            Cache::forget("package_detail_{$slug}");
            Cache::forget("blog_detail_{$slug}");
        }
    }
}
