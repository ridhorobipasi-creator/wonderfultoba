<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\City;
use App\Models\GalleryImage;
use App\Models\Media;
use App\Models\Package;
use App\Models\Setting;
use App\Traits\HandlesImageUploads;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourService
{
    use HandlesImageUploads;

    /**
     * Create or update a tour package with comprehensive image handling.
     */
    public function savePackage(array $data, ?Package $package = null)
    {
        $package = $package ?? new Package;

        // 1. Prepare Data
        if (! $package->exists) {
            $data['slug'] = Str::slug($data['name']);
        } elseif (isset($data['name']) && $data['name'] !== $package->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        // 2. Sanitize Includes/Excludes
        $data['includes'] = array_values(array_filter($data['includes'] ?? [], fn ($v) => ! empty(trim((string) $v))));
        $data['excludes'] = array_values(array_filter($data['excludes'] ?? [], fn ($v) => ! empty(trim((string) $v))));

        // 3. Handle Image Removals (for update)
        $currentImages = $package->images ?? [];
        if (isset($data['remove_images']) && is_array($data['remove_images'])) {
            foreach ($data['remove_images'] as $imgToRemove) {
                if (($key = array_search($imgToRemove, $currentImages)) !== false) {
                    unset($currentImages[$key]);
                    Storage::disk('public')->delete($imgToRemove);
                }
            }
        }

        // 4. Handle New File Uploads
        if (isset($data['image_files']) && is_array($data['image_files'])) {
            foreach ($data['image_files'] as $file) {
                $path = $this->uploadAndIndex($file, 'packages', 'packages', $data['name']);
                if ($path) {
                    $currentImages[] = $path;
                }
            }
        }

        // 5. Handle Media Library IDs
        if (isset($data['media_ids']) && is_array($data['media_ids'])) {
            $mediaPaths = Media::whereIn('id', $data['media_ids'])->pluck('path')->toArray();
            $currentImages = array_merge($currentImages, $mediaPaths);
        }

        $data['images'] = array_values(array_unique($currentImages));

        // 6. Save Package
        $package->fill($data);
        $package->save();

        // 7. Sync Relational PackageImage table
        $package->packageImages()->delete();
        foreach ($data['images'] as $index => $imgPath) {
            $package->packageImages()->create([
                'image_path' => $imgPath,
                'sort_order' => $index,
            ]);
        }

        // 8. Sync Cities
        if (isset($data['cityIds']) && is_array($data['cityIds'])) {
            $package->cities()->sync($data['cityIds']);
        } elseif (isset($data['cityIds']) && empty($data['cityIds'])) {
            $package->cities()->detach();
        }

        return $package;
    }

    /**
     * Delete package assets.
     */
    public function deletePackage(Package $package)
    {
        if ($package->images) {
            foreach ($package->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        return $package->delete();
    }



    /**
     * Clear tour related cache.
     */
    public function clearCache($slug = null)
    {
        Cache::forget('tour_packages_all');
        Cache::forget('featured_packages');
        Cache::forget('tour_blogs_all');
        Cache::forget('tour_homepage_data');
        Cache::forget('site_settings_structured_cms_tour_general');

        if ($slug) {
            Cache::forget("package_detail_{$slug}");
        }

        Log::info('Cache cleared for '.($slug ?? 'all packages'));

        return true;
    }

    /**
     * Get CMS Tour Settings.
     */
    public function getTourSettings()
    {
        return Setting::where('key', 'cms_tour')->first()?->value ?? [];
    }

    /**
     * Get featured active tour packages.
     */
    public function getFeaturedPackages()
    {
        $featured = Package::where('status', 'active')
            ->where('isFeatured', true)
            ->with(['packageImages', 'city'])
            ->orderBy('sortOrder')
            ->get();

        // Fallback: if no featured packages, show all active ones
        if ($featured->isEmpty()) {
            return Package::where('status', 'active')
                ->with(['packageImages', 'city'])
                ->orderBy('sortOrder')
                ->get();
        }

        return $featured;
    }

    /**
     * Get tour blog posts. Does NOT filter by category so all published posts appear.
     */
    public function getBlogs($limit = null)
    {
        $query = Blog::where('status', 'published')
            ->latest('createdAt');

        if ($limit) {
            return $query->limit($limit)->get();
        }

        return $query->get();
    }

    /**
     * Get all active tour packages with eager loaded images and city.
     */
    public function getAllPackages()
    {
        return Package::where('status', 'active')
            ->with(['packageImages', 'city', 'cities'])
            ->orderBy('sortOrder')
            ->get();
    }

    /**
     * Get all cities.
     */
    public function getCities()
    {
        return City::orderBy('name')->get();
    }

    /**
     * Get active gallery images for Tour.
     */
    public function getGallery()
    {
        return GalleryImage::where('isActive', true)
            ->where(function ($query) {
                $query->where('category', 'tour')
                    ->orWhere('category', 'Tour')
                    ->orWhereNull('category')
                    ->orWhere('category', '');
            })
            ->orderBy('orderPriority')
            ->get();
    }

    /**
     * Get active package by slug.
     */
    public function getPackageBySlug($slug)
    {
        return Package::where('slug', $slug)
            ->where('status', 'active')
            ->with(['packageImages', 'city'])
            ->first();
    }

    /**
     * Get active blog post by slug.
     */
    public function getBlogPost($slug)
    {
        return Blog::where('slug', $slug)
            ->where('status', 'published')
            ->first();
    }

    /**
     * Get related blog posts (any category).
     */
    public function getRelatedBlogs($currentId, $limit = 3)
    {
        return Blog::where('status', 'published')
            ->where('id', '!=', $currentId)
            ->latest('createdAt')
            ->limit($limit)
            ->get();
    }
}
