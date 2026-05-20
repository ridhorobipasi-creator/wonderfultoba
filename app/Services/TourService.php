<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesImageUploads;
use Illuminate\Support\Str;

class TourService
{
    use HandlesImageUploads;

    /**
     * Create or update a tour package with comprehensive image handling.
     */
    public function savePackage(array $data, Package $package = null)
    {
        $package = $package ?? new Package();
        
        // 1. Prepare Data
        if (!$package->exists) {
            $data['slug'] = Str::slug($data['name']);
        } elseif (isset($data['name']) && $data['name'] !== $package->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        // 2. Sanitize Includes/Excludes
        $data['includes'] = array_values(array_filter($data['includes'] ?? [], fn($v) => !empty(trim((string)$v))));
        $data['excludes'] = array_values(array_filter($data['excludes'] ?? [], fn($v) => !empty(trim((string)$v))));

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
                'sort_order' => $index
            ]);
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
     * Helper to index file into Media Library.
     */
    private function indexToMedia($path, $originalName, $packageName)
    {
        return Media::create([
            'filename' => basename($path),
            'original_name' => $originalName,
            'path' => $path,
            'category' => 'packages',
            'mime_type' => 'image/webp',
            'size' => Storage::disk('public')->size($path),
            'alt_text' => $packageName
        ]);
    }

    /**
     * Clear tour related cache.
     */
    public function clearCache($slug = null)
    {
        \Illuminate\Support\Facades\Cache::forget('tour_packages_all');
        \Illuminate\Support\Facades\Cache::forget('featured_packages');
        \Illuminate\Support\Facades\Cache::forget('tour_blogs_all');
        
        if ($slug) {
            \Illuminate\Support\Facades\Cache::forget("package_detail_{$slug}");
        }

        \Illuminate\Support\Facades\Log::info("Cache cleared for " . ($slug ?? 'all packages'));
        return true;
    }

    /**
     * Get CMS Tour Settings.
     */
    public function getTourSettings()
    {
        return \App\Models\Setting::where('key', 'cms_tour')->first()?->value ?? [];
    }

    /**
     * Get featured active tour packages.
     */
    public function getFeaturedPackages()
    {
        return Package::where('status', 'active')
            ->where('isFeatured', true)
            ->orderBy('sortOrder')
            ->get();
    }

    /**
     * Get tour blog posts. Does NOT filter by category so all published posts appear.
     */
    public function getBlogs($limit = null)
    {
        $query = \App\Models\Blog::where('status', 'published')
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
            ->with(['packageImages'])
            ->orderBy('sortOrder')
            ->get();
    }

    /**
     * Get all cities.
     */
    public function getCities()
    {
        return \App\Models\City::orderBy('name')->get();
    }

    /**
     * Get active gallery images for Tour.
     */
    public function getGallery()
    {
        return \App\Models\GalleryImage::where('isActive', true)
            ->where(function($query) {
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
            ->first();
    }

    /**
     * Get active blog post by slug.
     */
    public function getBlogPost($slug)
    {
        return \App\Models\Blog::where('slug', $slug)
            ->where('status', 'published')
            ->first();
    }

    /**
     * Get related blog posts (any category).
     */
    public function getRelatedBlogs($currentId, $limit = 3)
    {
        return \App\Models\Blog::where('status', 'published')
            ->where('id', '!=', $currentId)
            ->latest('createdAt')
            ->limit($limit)
            ->get();
    }
}

