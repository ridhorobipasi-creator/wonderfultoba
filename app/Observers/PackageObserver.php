<?php

namespace App\Observers;

use App\Models\Package;
use App\Services\TourService;
use Illuminate\Support\Facades\Cache;

class PackageObserver
{
    public function saved(Package $package)
    {
        (new TourService)->clearCache($package->slug);
        Cache::forget('admin_dashboard_stats');
    }

    public function deleted(Package $package)
    {
        (new TourService)->clearCache($package->slug);
        Cache::forget('admin_dashboard_stats');
    }

    public function restored(Package $package)
    {
        (new TourService)->clearCache($package->slug);
        Cache::forget('admin_dashboard_stats');
    }
}
