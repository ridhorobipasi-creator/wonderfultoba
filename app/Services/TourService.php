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
                $path = $this->uploadAndConvert($file, 'packages');
                if ($path) {
                    $this->indexToMedia($path, $file->getClientOriginalName(), $data['name']);
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
}
