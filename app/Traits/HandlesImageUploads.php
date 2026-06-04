<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUploads
{
    protected $lastDominantColor = null;

    protected $lastBlurHash = null;

    protected $lastExifData = null;

    /**
     * Extract the dominant color of an image resource using GD pixel averaging.
     *
     * @param  resource  $image  GD image resource
     * @return string Hex color code (e.g. #a1b2c3)
     */
    protected function extractDominantColor($image)
    {
        if (! $image) {
            return '#e2e8f0';
        }

        try {
            $width = imagesx($image);
            $height = imagesy($image);

            $tmp = imagecreatetruecolor(1, 1);
            imagecopyresampled($tmp, $image, 0, 0, 0, 0, 1, 1, $width, $height);
            $rgb = imagecolorat($tmp, 0, 0);

            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            imagedestroy($tmp);

            return sprintf('#%02x%02x%02x', $r, $g, $b);
        } catch (\Exception $e) {
            return '#e2e8f0';
        }
    }

    /**
     * Apply an elegant Brand Watermark on the image.
     *
     * @param  resource  $image  GD image resource
     * @return void
     */
    protected function applyWatermark($image)
    {
        if (! $image) {
            return;
        }

        try {
            $width = imagesx($image);
            $height = imagesy($image);

            $text = 'sujailaketoba.com';

            $font = 5; // Built-in font size (1-5)
            $fontWidth = imagefontwidth($font);
            $fontHeight = imagefontheight($font);

            $textWidth = strlen($text) * $fontWidth;
            $textHeight = $fontHeight;

            $padding = 24;
            $x = $width - $textWidth - $padding;
            $y = $height - $textHeight - $padding;

            if ($x < 0 || $y < 0) {
                return;
            } // Too small

            $boxPaddingX = 14;
            $boxPaddingY = 10;

            $bx1 = $x - $boxPaddingX;
            $by1 = $y - $boxPaddingY;
            $bx2 = $x + $textWidth + $boxPaddingX;
            $by2 = $y + $textHeight + $boxPaddingY;

            // Premium semi-transparent dark pill background
            $bgColor = imagecolorallocatealpha($image, 15, 23, 42, 60); // Slate-900 with transparency
            $textColor = imagecolorallocate($image, 255, 255, 255);
            $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 100);

            // Draw background rectangle/pill
            imagefilledrectangle($image, $bx1, $by1, $bx2, $by2, $bgColor);

            // Draw text shadow
            imagestring($image, $font, $x + 1, $y + 1, $text, $shadowColor);
            // Draw text
            imagestring($image, $font, $x, $y, $text, $textColor);
        } catch (\Exception $e) {
            \Log::error('Watermark Error: '.$e->getMessage());
        }
    }

    /**
     * Generate SEO optimized Alt Text from filename and category.
     *
     * @param  string  $originalName
     * @param  string|null  $category
     * @return string
     */
    protected function generateAutoAltText($originalName, $category = null)
    {
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $name = str_replace(['-', '_', '.'], ' ', $name);
        $name = ucwords(trim($name));

        if ($category && $category !== 'uncategorized' && $category !== 'uploads') {
            $catFormatted = ucwords(str_replace(['-', '_'], ' ', $category));

            return "Foto {$name} - Kategori {$catFormatted} | Wisata Danau Toba Sujailaketoba";
        }

        return "Pesona Keindahan {$name} - Wonderful Lake Toba Tour";
    }

    /**
     * Upload an image, convert to WebP, generate thumbnail, and optionally index to Media Library.
     *
     * @param  UploadedFile  $file
     * @param  string  $directory
     * @param  string|null  $category  For Media Library
     * @param  string|null  $altText  For Media Library
     * @param  bool  $watermark  Whether to apply branding watermark
     * @return string Path to the file relative to storage/public
     */
    protected function uploadAndIndex($file, $directory = 'uploads', $category = null, $altText = null, $watermark = false)
    {
        // Auto Alt-Text Generation if empty
        if (empty($altText)) {
            $altText = $this->generateAutoAltText($file->getClientOriginalName(), $category);
        }

        $path = $this->uploadAndConvert($file, $directory, 80, $watermark);

        if ($path) {
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $isWebp = $extension === 'webp';

            $mimeType = match ($extension) {
                'webp' => 'image/webp',
                'png' => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                default => 'application/octet-stream',
            };

            // Automatically index to Media Library
            Media::updateOrCreate(
                ['path' => $path],
                [
                    'filename' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'category' => $category ?? $directory,
                    'mime_type' => $mimeType,
                    'size' => Storage::disk('uploads')->size($path),
                    'thumb' => $isWebp ? ($directory.'/thumbnails/'.basename($path)) : null,
                    'alt_text' => $altText,
                    'dominant_color' => $this->lastDominantColor,
                    'blur_hash' => $this->lastBlurHash,
                    'exif_data' => $this->lastExifData,
                ]
            );
        }

        return $path;
    }


    /**
     * Downscale a GD image so its largest side fits within $maxDimension.
     * Returns the (possibly new) image resource; the original is freed when resized.
     *
     * @param  resource  $image
     * @return resource
     */
    protected function downscaleToMax($image, int $maxDimension = 6000)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxDimension && $height <= $maxDimension) {
            return $image;
        }

        $ratio = min($maxDimension / $width, $maxDimension / $height);
        $newWidth = max(1, (int) floor($width * $ratio));
        $newHeight = max(1, (int) floor($height * $ratio));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        imagedestroy($image);

        return $resized;
    }

    /**
     * Core upload and conversion engine.
     */
    protected function uploadAndConvert($file, $directory = 'uploads', $quality = 80, $watermark = false)
    {
        // Ensure directory exists
        if (! Storage::disk('uploads')->exists($directory)) {
            Storage::disk('uploads')->makeDirectory($directory);
        }

        // Ensure thumbnails directory exists
        if (! Storage::disk('uploads')->exists($directory.'/thumbnails')) {
            Storage::disk('uploads')->makeDirectory($directory.'/thumbnails');
        }

        // Fallback if GD extension is not loaded
        if (! extension_loaded('gd')) {
            return $file->store($directory, 'uploads');
        }

        $baseFilename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'-'.time();
        $filename = $baseFilename.'.webp';
        $path = $directory.'/'.$filename;

        $image = null;

        try {
            // INCREASE LIMITS FOR LARGE IMAGES
            @ini_set('memory_limit', '512M');
            @set_time_limit(600);

            $imgData = @file_get_contents($file->getRealPath());
            if ($imgData) {
                $image = @imagecreatefromstring($imgData);
            }

            if ($image) {
                \imagepalettetotruecolor($image);
                \imagealphablending($image, true);
                \imagesavealpha($image, true);

                // Downscale oversized images instead of storing the heavy original
                $image = $this->downscaleToMax($image, 6000);
                $width = imagesx($image);
                $height = imagesy($image);

                // Apply watermark if requested
                if ($watermark) {
                    $this->applyWatermark($image);
                }

                // Extract dominant color
                $this->lastDominantColor = $this->extractDominantColor($image);

                // Generate Blur Hash
                $this->lastBlurHash = $this->generateBlurHash($image);

                // Extract EXIF data safely
                $this->lastExifData = $this->extractExifData($file->getRealPath());

                // Generate Responsive Variants
                $this->generateResponsiveVariants($image, $directory, $filename);

                ob_start();
                \imagewebp($image, null, $quality);
                $webpData = ob_get_clean();
                Storage::disk('uploads')->put($path, $webpData);

                // Thumbnail (400px width)
                $targetWidth = 400;
                $targetHeight = floor($height * ($targetWidth / $width));

                $thumbImg = imagecreatetruecolor($targetWidth, $targetHeight);
                imagealphablending($thumbImg, false);
                imagesavealpha($thumbImg, true);
                imagecopyresampled($thumbImg, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

                ob_start();
                imagewebp($thumbImg, null, 70);
                $thumbData = ob_get_clean();
                Storage::disk('uploads')->put($directory.'/thumbnails/'.$filename, $thumbData);

                imagedestroy($thumbImg);
                \imagedestroy($image);
                unset($webpData, $thumbData, $image, $thumbImg);

                return $path;
            }
        } catch (\Throwable $e) {
            try {
                \Log::error('Upload Error: '.$e->getMessage());
            } catch (\Throwable $logException) {
                // Ignore log errors if storage/logs is not writable
                throw new \Exception('Upload Error: ' . $e->getMessage() . ' | Log Error: ' . $logException->getMessage());
            }

            throw $e;
        }

        return $file->store($directory, 'uploads');
    }

    /**
     * Convert an existing image path in storage to WebP format.
     *
     * @param  string  $storagePath  Relative path on 'public' disk
     * @param  int  $quality  WebP compression quality
     * @return string|false New WebP storage path, or false on failure
     */
    protected function convertPathToWebp($storagePath, $quality = 80)
    {
        if (! extension_loaded('gd')) {
            return false;
        }

        if (! Storage::disk('uploads')->exists($storagePath)) {
            return false;
        }

        $extension = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
        if ($extension === 'webp') {
            // Even if already webp, extract dominant color for DB consistency
            try {
                $absolutePath = Storage::disk('uploads')->path($storagePath);
                $imgData = @file_get_contents($absolutePath);
                if ($imgData) {
                    $image = @imagecreatefromstring($imgData);
                    if ($image) {
                        $this->lastDominantColor = $this->extractDominantColor($image);
                        imagedestroy($image);
                    }
                }
            } catch (\Exception $e) {
            }

            return $storagePath;
        }

        $absolutePath = Storage::disk('uploads')->path($storagePath);
        $directory = dirname($storagePath);
        $baseFilename = pathinfo($storagePath, PATHINFO_FILENAME);
        $newFilename = $baseFilename.'.webp';
        $newStoragePath = ($directory === '.' || $directory === '/') ? $newFilename : $directory.'/'.$newFilename;

        $image = null;
        try {
            @ini_set('memory_limit', '512M');
            @set_time_limit(600);

            $imgData = @file_get_contents($absolutePath);
            if ($imgData) {
                $image = @imagecreatefromstring($imgData);
            }

            if ($image) {
                \imagepalettetotruecolor($image);
                \imagealphablending($image, true);
                \imagesavealpha($image, true);

                // Downscale oversized images instead of skipping the conversion
                $image = $this->downscaleToMax($image, 6000);
                $width = imagesx($image);
                $height = imagesy($image);

                // Extract dominant color
                $this->lastDominantColor = $this->extractDominantColor($image);

                // Extract EXIF data safely
                $this->lastExifData = $this->extractExifData($absolutePath);

                // Generate Blur Hash
                $this->lastBlurHash = $this->generateBlurHash($image);

                // Generate Responsive Variants
                $this->generateResponsiveVariants($image, $directory, $newFilename);

                // Ensure directory and thumbnails directory exist
                if (! Storage::disk('uploads')->exists($directory.'/thumbnails')) {
                    Storage::disk('uploads')->makeDirectory($directory.'/thumbnails');
                }

                // Convert to WebP
                ob_start();
                \imagewebp($image, null, $quality);
                $webpData = ob_get_clean();
                Storage::disk('uploads')->put($newStoragePath, $webpData);

                // Thumbnail (400px width)
                $targetWidth = 400;
                $targetHeight = floor($height * ($targetWidth / $width));

                $thumbImg = imagecreatetruecolor($targetWidth, $targetHeight);
                imagealphablending($thumbImg, false);
                imagesavealpha($thumbImg, true);
                imagecopyresampled($thumbImg, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

                ob_start();
                imagewebp($thumbImg, null, 70);
                $thumbData = ob_get_clean();
                $thumbPath = ($directory === '.' || $directory === '/') ? 'thumbnails/'.$newFilename : $directory.'/thumbnails/'.$newFilename;
                Storage::disk('uploads')->put($thumbPath, $thumbData);

                imagedestroy($thumbImg);
                \imagedestroy($image);
                unset($webpData, $thumbData, $image, $thumbImg);

                // Delete the old non-webp file
                Storage::disk('uploads')->delete($storagePath);

                // Delete old thumbnail if it exists
                $oldThumbPath = ($directory === '.' || $directory === '/') ? 'thumbnails/'.basename($storagePath) : $directory.'/thumbnails/'.basename($storagePath);
                if (Storage::disk('uploads')->exists($oldThumbPath)) {
                    Storage::disk('uploads')->delete($oldThumbPath);
                }

                return $newStoragePath;
            }
        } catch (\Throwable $e) {
            \Log::error('WebP Conversion Error for '.$storagePath.': '.$e->getMessage());
        }

        return false;
    }

    /**
     * Download an image from an external URL, convert to WebP, and index to Media Library.
     *
     * @param  string  $url
     * @param  string  $directory
     * @param  string|null  $category
     * @param  string|null  $altText
     * @param  bool  $watermark  Whether to apply branding watermark
     * @return string|false Relative WebP path or false
     */
    protected function uploadFromUrl($url, $directory = 'uploads', $category = null, $altText = null, $watermark = false)
    {
        try {
            if (! filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }

            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
                    'timeout' => 30,
                ],
            ];
            $context = stream_context_create($opts);
            $imgData = @file_get_contents($url, false, $context);

            if (! $imgData) {
                return false;
            }

            $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
            $originalName = ! empty($pathInfo['basename']) ? $pathInfo['basename'] : 'url-image-'.time();

            // Auto Alt-Text Generation if empty
            if (empty($altText)) {
                $altText = $this->generateAutoAltText($originalName, $category);
            }

            $baseFilename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)).'-'.time();
            if (empty($baseFilename)) {
                $baseFilename = 'url-image-'.time();
            }
            $filename = $baseFilename.'.webp';
            $path = $directory.'/'.$filename;

            // Ensure directories exist
            if (! Storage::disk('uploads')->exists($directory)) {
                Storage::disk('uploads')->makeDirectory($directory);
            }
            if (! Storage::disk('uploads')->exists($directory.'/thumbnails')) {
                Storage::disk('uploads')->makeDirectory($directory.'/thumbnails');
            }

            // Fallback if GD is not loaded
            if (! extension_loaded('gd')) {
                $originalPath = $directory.'/'.$baseFilename.'.jpg';
                Storage::disk('uploads')->put($originalPath, $imgData);

                Media::updateOrCreate(
                    ['path' => $originalPath],
                    [
                        'filename' => basename($originalPath),
                        'original_name' => $originalName,
                        'category' => $category ?? $directory,
                        'mime_type' => 'image/jpeg',
                        'size' => strlen($imgData),
                        'thumb' => $originalPath,
                        'alt_text' => $altText,
                    ]
                );

                return $originalPath;
            }

            // GD conversion
            @ini_set('memory_limit', '512M');
            @set_time_limit(600);

            $image = @imagecreatefromstring($imgData);

            if ($image) {
                \imagepalettetotruecolor($image);
                \imagealphablending($image, true);
                \imagesavealpha($image, true);

                // Downscale oversized images instead of skipping the conversion
                $image = $this->downscaleToMax($image, 6000);
                $width = imagesx($image);
                $height = imagesy($image);

                // Apply watermark if requested
                if ($watermark) {
                    $this->applyWatermark($image);
                }

                // Extract dominant color
                $dominantColor = $this->extractDominantColor($image);

                ob_start();
                \imagewebp($image, null, 80);
                $webpData = ob_get_clean();
                Storage::disk('uploads')->put($path, $webpData);

                // Thumbnail (400px width)
                $targetWidth = 400;
                $targetHeight = floor($height * ($targetWidth / $width));

                $thumbImg = imagecreatetruecolor($targetWidth, $targetHeight);
                imagealphablending($thumbImg, false);
                imagesavealpha($thumbImg, true);
                imagecopyresampled($thumbImg, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

                ob_start();
                imagewebp($thumbImg, null, 70);
                $thumbData = ob_get_clean();
                Storage::disk('uploads')->put($directory.'/thumbnails/'.$filename, $thumbData);

                imagedestroy($thumbImg);
                \imagedestroy($image);
                unset($webpData, $thumbData, $image, $thumbImg);

                // Generate Blur Hash & Responsive Variants
                $this->lastBlurHash = $this->generateBlurHash($image);
                $this->generateResponsiveVariants($image, $directory, $filename);

                Media::updateOrCreate(
                    ['path' => $path],
                    [
                        'filename' => $filename,
                        'original_name' => $originalName,
                        'category' => $category ?? $directory,
                        'mime_type' => 'image/webp',
                        'size' => Storage::disk('uploads')->size($path),
                        'thumb' => $directory.'/thumbnails/'.$filename,
                        'alt_text' => $altText,
                        'dominant_color' => $dominantColor,
                        'blur_hash' => $this->lastBlurHash,
                        'exif_data' => null,
                    ]
                );

                return $path;
            }
        } catch (\Exception $e) {
            \Log::error('URL Upload Error: '.$e->getMessage());
        }

        return false;
    }

    /**
     * Helper to convert fractional degrees/minutes/seconds GPS arrays to decimal.
     */
    protected function extractGpsCoordinate($coordinate, $ref)
    {
        if (! is_array($coordinate) || count($coordinate) < 3) {
            return null;
        }

        $parts = [];
        foreach ($coordinate as $part) {
            $division = explode('/', $part);
            if (count($division) === 2 && $division[1] > 0) {
                $parts[] = (float) $division[0] / (float) $division[1];
            } else {
                $parts[] = (float) $part;
            }
        }

        if (count($parts) < 3) {
            return null;
        }

        $degrees = $parts[0];
        $minutes = $parts[1];
        $seconds = $parts[2];

        $value = $degrees + ($minutes / 60) + ($seconds / 3600);

        if ($ref === 'S' || $ref === 'W') {
            $value = -$value;
        }

        return round($value, 6);
    }

    /**
     * Extract camera and GPS metadata from JPEG.
     */
    protected function extractExifData($filePath)
    {
        if (! function_exists('exif_read_data')) {
            return null;
        }

        try {
            $mime = @mime_content_type($filePath);
            if (! in_array($mime, ['image/jpeg', 'image/tiff'])) {
                return null;
            }

            $exif = @exif_read_data($filePath);
            if (! $exif) {
                return null;
            }

            $data = [];

            if (! empty($exif['Make'])) {
                $data['camera_brand'] = trim($exif['Make']);
            }
            if (! empty($exif['Model'])) {
                $data['camera_model'] = trim($exif['Model']);
            }

            if (! empty($exif['FNumber'])) {
                $fParts = explode('/', $exif['FNumber']);
                if (count($fParts) === 2 && $fParts[1] > 0) {
                    $data['aperture'] = 'f/'.round($fParts[0] / $fParts[1], 1);
                } else {
                    $data['aperture'] = 'f/'.$exif['FNumber'];
                }
            }
            if (! empty($exif['ISOSpeedRatings'])) {
                $data['iso'] = is_array($exif['ISOSpeedRatings']) ? $exif['ISOSpeedRatings'][0] : $exif['ISOSpeedRatings'];
            }
            if (! empty($exif['ExposureTime'])) {
                $data['shutter_speed'] = $exif['ExposureTime'].'s';
            }

            if (! empty($exif['GPSLatitude']) && ! empty($exif['GPSLatitudeRef']) && ! empty($exif['GPSLongitude']) && ! empty($exif['GPSLongitudeRef'])) {
                $lat = $this->extractGpsCoordinate($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
                $lng = $this->extractGpsCoordinate($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
                if ($lat !== null && $lng !== null) {
                    $data['gps'] = [
                        'lat' => $lat,
                        'lng' => $lng,
                    ];
                }
            }

            return empty($data) ? null : $data;
        } catch (\Exception $e) {
            \Log::error('EXIF Extraction Error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Generate micro 16x16 blurred WebP placeholder as base64 string.
     */
    protected function generateBlurHash($image)
    {
        if (! $image) {
            return null;
        }

        try {
            $microWidth = 16;
            $microHeight = 16;

            $microImg = imagecreatetruecolor($microWidth, $microHeight);
            imagealphablending($microImg, false);
            imagesavealpha($microImg, true);

            $width = imagesx($image);
            $height = imagesy($image);

            imagecopyresampled($microImg, $image, 0, 0, 0, 0, $microWidth, $microHeight, $width, $height);

            ob_start();
            imagewebp($microImg, null, 20);
            $microData = ob_get_clean();

            imagedestroy($microImg);

            return 'data:image/webp;base64,'.base64_encode($microData);
        } catch (\Exception $e) {
            \Log::error('Blur Hash Generation Error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Generate large, medium, and mobile responsive variants.
     */
    protected function generateResponsiveVariants($image, $directory, $filename)
    {
        if (! $image) {
            return;
        }

        $sizes = [
            'large' => 1200,
            'medium' => 800,
            'mobile' => 480,
        ];

        $width = imagesx($image);
        $height = imagesy($image);

        foreach ($sizes as $name => $targetWidth) {
            $subDir = $directory.'/'.$name;
            if (! Storage::disk('uploads')->exists($subDir)) {
                Storage::disk('uploads')->makeDirectory($subDir);
            }

            if ($width > $targetWidth) {
                $targetHeight = floor($height * ($targetWidth / $width));
            } else {
                $targetWidth = $width;
                $targetHeight = $height;
            }

            try {
                $variantImg = imagecreatetruecolor($targetWidth, $targetHeight);
                imagealphablending($variantImg, false);
                imagesavealpha($variantImg, true);
                imagecopyresampled($variantImg, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

                ob_start();
                imagewebp($variantImg, null, 80);
                $variantData = ob_get_clean();

                Storage::disk('uploads')->put($subDir.'/'.$filename, $variantData);
                imagedestroy($variantImg);
            } catch (\Exception $e) {
                \Log::error("Responsive Variant Generation Error for {$name}: ".$e->getMessage());
            }
        }
    }
}
