<?php

namespace App\Observers;

use App\Models\Setting;
use App\Services\TourService;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{
    public function saved(Setting $setting)
    {
        // Clear global site settings cache
        Cache::forget('site_settings_global');

        // Clear tour specific settings cache if relevant
        if (str_starts_with($setting->key, 'cms_') || str_starts_with($setting->key, 'page_')) {
            (new TourService)->clearCache();
        }
    }
}
