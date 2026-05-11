<?php

namespace App\Traits;

trait HasImageFallback
{
    /**
     * Resolve image URL with storage/assets detection and placeholder fallback.
     * Delegates to the global imageUrl() helper for consistency.
     *
     * @param string|null $path
     * @param string|null $fallback
     * @return string
     */
    public function resolveImageUrl($path, $fallback = null): string
    {
        return imageUrl($path, $fallback);
    }

    /**
     * Standard accessor for image_url property.
     * Expects the model to have an 'image' attribute.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->image ?? null);
    }
}
