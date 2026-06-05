<?php

namespace App\Observers;

use App\Models\Blog;
use App\Services\TourService;
use Illuminate\Support\Facades\Cache;

class BlogObserver
{
    public function saved(Blog $blog)
    {
        (new TourService)->clearCache($blog->slug);
        Cache::forget('admin_dashboard_stats');
    }

    public function deleted(Blog $blog)
    {
        (new TourService)->clearCache($blog->slug);
        Cache::forget('admin_dashboard_stats');
    }

    public function restored(Blog $blog)
    {
        (new TourService)->clearCache($blog->slug);
        Cache::forget('admin_dashboard_stats');
    }
}
