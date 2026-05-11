<?php

namespace App\Observers;

use App\Models\Package;
use App\Services\TourService;

class PackageObserver
{
    public function saved(Package $package)
    {
        (new TourService)->clearCache($package->slug);
    }

    public function deleted(Package $package)
    {
        (new TourService)->clearCache($package->slug);
    }

    public function restored(Package $package)
    {
        (new TourService)->clearCache($package->slug);
    }
}
