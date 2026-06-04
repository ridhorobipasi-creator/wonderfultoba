<?php

use App\Models\Setting;

if (! function_exists('siteRating')) {
    /**
     * Resolve the site-wide rating shown in trust badges.
     *
     * The numbers are entered by the admin (taken from the real Google Maps
     * listing) under Admin → Settings → Kontak. If no rating is set, the badge
     * is hidden — never a fabricated number.
     *
     * @return array{value: float, count: int|null, url: string|null}|null
     */
    function siteRating(): ?array
    {
        try {
            $general = optional(Setting::where('key', 'general')->first())->value ?? [];
        } catch (\Throwable $e) {
            return null;
        }

        $rating = $general['rating_override'] ?? null;
        if (! is_numeric($rating) || (float) $rating <= 0) {
            return null;
        }

        $count = $general['review_count_override'] ?? null;

        return [
            'value' => round((float) $rating, 1),
            'count' => is_numeric($count) ? (int) $count : null,
            'url'   => $general['google_maps_url'] ?? null,
        ];
    }
}
