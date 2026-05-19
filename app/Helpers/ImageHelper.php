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

        $lower = strtolower($path);

        // Premium Unsplash Override for Legacy Outbound/Team Building Images to Tour & Travel Images
        if (str_contains($lower, '001-1') || str_contains($lower, 'toba') || str_contains($lower, 'samosir')) {
            return 'https://images.unsplash.com/photo-1544735049-717bc392183e?auto=format&fit=crop&w=1200&q=80'; // Lake Toba
        }
        if (str_contains($lower, '002-1')) {
            return 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80'; // Samosir beach
        }
        if (str_contains($lower, '003-1')) {
            return 'https://images.unsplash.com/photo-1582298538104-fe2e74c27f59?auto=format&fit=crop&w=1200&q=80'; // Sipiso-piso Waterfall
        }
        if (str_contains($lower, '004') || str_contains($lower, 'berastagi') || str_contains($lower, 'karo')) {
            return 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=1200&q=80'; // Berastagi Mount Sinabung
        }
        if (str_contains($lower, '005') || str_contains($lower, 'lumbini')) {
            return 'https://images.unsplash.com/photo-1544735049-717bc392183e?auto=format&fit=crop&w=1200&q=80'; // Lumbini golden temple
        }
        if (str_contains($lower, '006') || str_contains($lower, 'simalem')) {
            return 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80'; // Karo Simalem Resort view
        }
        if (str_contains($lower, '008') || str_contains($lower, 'medan')) {
            return 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1200&q=80'; // Maimun Palace / Medan
        }
        if (str_contains($lower, '009-1') || str_contains($lower, 'masjid')) {
            return 'https://images.unsplash.com/photo-1564507592937-25994a9015ba?auto=format&fit=crop&w=1200&q=80'; // Masjid Raya Medan
        }
        if (str_contains($lower, '010') || str_contains($lower, '0010')) {
            return 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80'; // Premium hotel room / travel stay
        }
        if (str_contains($lower, 'car') || str_contains($lower, 'avanza') || str_contains($lower, 'innova') || str_contains($lower, 'hiace')) {
            if (str_contains($lower, 'avanza')) {
                return 'https://images.unsplash.com/photo-1617788138017-80ad40651399?auto=format&fit=crop&w=800&q=80';
            }
            if (str_contains($lower, 'innova')) {
                return 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80';
            }
            return 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';
        }

        $clean = ltrim($path, '/');

        // Dynamic fallback for non-existent local/storage files with premium Unsplash travel images
        $fileInPublic = public_path($clean);
        $cleanStoragePath = str_starts_with($clean, 'storage/') ? substr($clean, 8) : $clean;
        $fileInStorage = storage_path('app/public/' . $cleanStoragePath);

        if (!file_exists($fileInPublic) && !file_exists($fileInStorage)) {
            return 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80';
        }

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
