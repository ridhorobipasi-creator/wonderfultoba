<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (! function_exists('siteRating')) {
    /**
     * Resolve the site-wide rating shown in trust badges.
     *
     * Priority:
     *   1. Admin override (rating_override / review_count_override in general settings)
     *   2. Live Google Places rating (cached 12h) when place_id + api key are set
     *   3. null  → badge is hidden (no fake numbers)
     *
     * @return array{value: float, count: int|null, source: string, url: string|null}|null
     */
    function siteRating(): ?array
    {
        try {
            $general = optional(Setting::where('key', 'general')->first())->value ?? [];
        } catch (\Throwable $e) {
            return null;
        }

        // 1) Manual override wins — admin-edited, intentional & transparent.
        $override = $general['rating_override'] ?? null;
        if (is_numeric($override) && (float) $override > 0) {
            $count = $general['review_count_override'] ?? null;

            return [
                'value'  => round((float) $override, 1),
                'count'  => is_numeric($count) ? (int) $count : null,
                'source' => 'manual',
                'url'    => $general['google_maps_url'] ?? null,
            ];
        }

        // 2) Live from Google Places (cached so we hit the API at most ~twice a day).
        $placeId = $general['google_place_id'] ?? null;
        $apiKey  = $general['google_maps_api_key'] ?? config('services.google_places.key');

        if (! $placeId || ! $apiKey) {
            return null;
        }

        return Cache::remember('site_google_rating', now()->addHours(12), function () use ($placeId, $apiKey) {
            try {
                $result = Http::timeout(8)
                    ->get('https://maps.googleapis.com/maps/api/place/details/json', [
                        'place_id' => $placeId,
                        'fields'   => 'rating,user_ratings_total,url',
                        'key'      => $apiKey,
                    ])
                    ->json('result');

                if (! empty($result['rating'])) {
                    return [
                        'value'  => round((float) $result['rating'], 1),
                        'count'  => (int) ($result['user_ratings_total'] ?? 0),
                        'source' => 'google',
                        'url'    => $result['url'] ?? null,
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('Google rating fetch failed: '.$e->getMessage());
            }

            return null;
        });
    }
}
