<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Facades\Cache;

class BookingObserver
{
    public function saved(Booking $booking)
    {
        Cache::forget('admin_dashboard_stats');
    }

    public function deleted(Booking $booking)
    {
        Cache::forget('admin_dashboard_stats');
    }

    public function restored(Booking $booking)
    {
        Cache::forget('admin_dashboard_stats');
    }
}
