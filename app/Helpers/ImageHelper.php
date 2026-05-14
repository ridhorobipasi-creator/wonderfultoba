<?php

/**
 * Global Image Path Resolution Helper
 *
 * Single source of truth for resolving storage/asset paths to full URLs.
 * Replaces all inline path resolution logic scattered across Blade views.
 *
 * Usage in Blade:
 *   {{ imageUrl($path) }}
 *   {{ imageUrl($path, asset('images/custom-fallback.webp')) }}
 *
 * Usage in PHP (Controller/Service):
 *   imageUrl($path)
 */

if (! function_exists('imageUrl')) {
    function imageUrl(?string $path, ?string $fallback = null): string
    {
        $localFallback = $fallback ?? asset('images/home/tour.webp');

        if (empty($path) || $path === 'null') {
            return $localFallback;
        }

        // Already a full external URL — return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        // Already a data URI or blob — return as-is
        if (str_starts_with($path, 'data:') || str_starts_with($path, 'blob:')) {
            return $path;
        }

        $clean = ltrim($path, '/');

        // Check if it's a static asset in the public folder (no storage/ prefix needed)
        // If file exists in public/assets or public/images, return asset() directly
        if (str_starts_with($clean, 'assets/') || str_starts_with($clean, 'images/')) {
            if (file_exists(public_path($clean))) {
                return asset($clean);
            }
        }

        // Strip redundant 'storage/' prefix before re-adding it
        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, strlen('storage/'));
        }

        // --- NEW: Smart WebP Prioritization ---
        // If it's a PNG/JPG/JPEG, check if a .webp version exists
        $extension = strtolower(pathinfo($clean, PATHINFO_EXTENSION));
        if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
            $webpPath = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $clean);
            if (Storage::disk('public')->exists($webpPath)) {
                return asset('storage/' . $webpPath);
            }
        }
        // --------------------------------------

        // Paths that start with known media prefixes (from Media Library)
        foreach (['branding/', 'gallery/', 'cms/', 'packages/', 'blogs/', 'cities/'] as $prefix) {
            if (str_starts_with($clean, $prefix)) {
                return asset('storage/' . $clean);
            }
        }

        // Final Fallback: Check if file exists in public/ directly, else assume storage
        if (file_exists(public_path($clean)) && !is_dir(public_path($clean))) {
            return asset($clean);
        }

        return asset('storage/' . $clean);
    }
}

if (! function_exists('imageFallback')) {
    /**
     * Returns a local asset URL for onerror fallback attributes in img tags.
     * Safe to use inline in HTML onerror= attributes.
     */
    function imageFallback(?string $fallback = null): string
    {
        return $fallback ?? asset('images/home/tour.webp');
    }
}
