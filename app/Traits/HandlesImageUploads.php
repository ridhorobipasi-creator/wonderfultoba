<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUploads
{
    /**
     * Upload an image, convert to WebP, generate thumbnail, and optionally index to Media Library.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param string|null $category For Media Library
     * @param string|null $altText For Media Library
     * @return string Path to the file relative to storage/public
     */
    protected function uploadAndIndex($file, $directory = 'uploads', $category = null, $altText = null)
    {
        $path = $this->uploadAndConvert($file, $directory);
        
        if ($path) {
            // Automatically index to Media Library if category is provided or if we want global tracking
            \App\Models\Media::updateOrCreate(
                ['path' => $path],
                [
                    'filename' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'category' => $category ?? $directory,
                    'mime_type' => 'image/webp',
                    'size' => Storage::disk('public')->size($path),
                    'alt_text' => $altText
                ]
            );
        }

        return $path;
    }

    protected function uploadAndConvert($file, $directory = 'uploads', $quality = 80)
    {
        // Ensure directory exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Ensure thumbnails directory exists
        if (!Storage::disk('public')->exists($directory . '/thumbnails')) {
            Storage::disk('public')->makeDirectory($directory . '/thumbnails');
        }

        // Fallback if GD extension is not loaded
        if (!extension_loaded('gd')) {
            return $file->store($directory, 'public');
        }

        $baseFilename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time();
        $filename = $baseFilename . '.webp';
        $path = $directory . '/' . $filename;

        $extension = strtolower($file->getClientOriginalExtension());
        $image = null;

        try {
            @ini_set('memory_limit', '256M');
            @set_time_limit(300);

            $size = @getimagesize($file->getRealPath());
            if (!$size || $size[0] > 5000 || $size[1] > 5000) {
                return $file->store($directory, 'public');
            }

            switch ($extension) {
                case 'jpeg':
                case 'jpg': $image = @\imagecreatefromjpeg($file->getRealPath()); break;
                case 'png':
                    $image = @\imagecreatefrompng($file->getRealPath());
                    if ($image) {
                        \imagepalettetotruecolor($image);
                        \imagealphablending($image, true);
                        \imagesavealpha($image, true);
                    }
                    break;
                case 'webp': $image = @\imagecreatefromwebp($file->getRealPath()); break;
            }

            if ($image) {
                ob_start();
                \imagewebp($image, null, $quality);
                $webpData = ob_get_clean();
                Storage::disk('public')->put($path, $webpData);

                // Thumbnail
                $width = imagesx($image);
                $height = imagesy($image);
                $targetWidth = 400;
                $targetHeight = floor($height * ($targetWidth / $width));
                
                $thumbImg = imagecreatetruecolor($targetWidth, $targetHeight);
                imagealphablending($thumbImg, false);
                imagesavealpha($thumbImg, true);
                imagecopyresampled($thumbImg, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
                
                ob_start();
                imagewebp($thumbImg, null, 70);
                $thumbData = ob_get_clean();
                Storage::disk('public')->put($directory . '/thumbnails/' . $filename, $thumbData);
                
                imagedestroy($thumbImg);
                \imagedestroy($image);
                unset($webpData, $thumbData);

                return $path;
            }
        } catch (\Exception $e) {
            \Log::error('Upload Error: ' . $e->getMessage());
            return $file->store($directory, 'public');
        }

        return $file->store($directory, 'public');
    }
}
