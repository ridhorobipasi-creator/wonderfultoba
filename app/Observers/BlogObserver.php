<?php

namespace App\Observers;

use App\Models\Blog;
use App\Services\TourService;

class BlogObserver
{
    public function saved(Blog $blog)
    {
        (new TourService)->clearCache($blog->slug);
    }

    public function deleted(Blog $blog)
    {
        (new TourService)->clearCache($blog->slug);
    }
}
