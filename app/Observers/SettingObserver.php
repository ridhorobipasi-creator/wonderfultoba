<?php

namespace App\Observers;

use App\Models\Setting;
use App\Services\TourService;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{
    public function saved(Setting $setting)
    {
        // Clear global site settings caches
        Cache::forget('site_settings_global');
        Cache::forget('site_settings_all');
        Cache::forget('tour_homepage_data');

        // Clear all structured settings cache variants
        $patterns = [
            'site_settings_structured_cms_tour_general',
            'site_settings_structured_cms_landing_cms_tour_general',
            'site_settings_structured_cms_landing_general',
            'site_settings_structured_general',
        ];
        foreach ($patterns as $key) {
            Cache::forget($key);
        }

        // Clear tour specific settings cache if relevant
        if (str_starts_with($setting->key, 'cms_') || str_starts_with($setting->key, 'page_')) {
            (new TourService)->clearCache();
        }
    }
}
