<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUploads
{
    /**
     * Upload an image and convert it to WebP.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param int $quality
     * @return string|false Path to the uploaded file relative to storage/public
     */
    protected function uploadAndConvert($file, $directory = 'uploads', $quality = 80)
    {
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
            // Get Image Size without loading to memory
            $size = @getimagesize($file->getRealPath());
            if (!$size || $size[0] > 5000 || $size[1] > 5000) {
                // If resolution is too huge (>5000px), just store without conversion to save RAM
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
                // 1. Save Full Size
                ob_start();
                \imagewebp($image, null, $quality);
                $webpData = ob_get_clean();
                Storage::disk('public')->put($path, $webpData);

                // 2. Save Thumbnail (400px width)
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
                unset($webpData, $thumbData); // Force free memory

                return $path;
            }
        } catch (\Exception $e) {
            return $file->store($directory, 'public');
        }

        return $file->store($directory, 'public');
    }
}
