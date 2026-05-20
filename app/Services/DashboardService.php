<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats()
    {
        return \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 300, function() {
            return [
                'revenue' => [
                    'total' => Booking::where('status', 'confirmed')->sum('totalPrice'),
                    'monthly' => Booking::where('status', 'confirmed')
                        ->whereMonth('createdAt', now()->month)
                        ->sum('totalPrice'),
                    'growth' => $this->calculateGrowth(),
                ],
                'profit' => [
                    'total' => Booking::where('status', 'confirmed')->sum(DB::raw('totalPrice - COALESCE(total_cost, 0)')),
                    'monthly' => Booking::where('status', 'confirmed')
                        ->whereMonth('createdAt', now()->month)
                        ->sum(DB::raw('totalPrice - COALESCE(total_cost, 0)')),
                ],
                'bookings' => [
                    'active' => Booking::whereIn('status', ['pending', 'confirmed'])->count(),
                    'pending' => Booking::where('status', 'pending')->count(),
                    'confirmed' => Booking::where('status', 'confirmed')->count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
                ],
                'packages' => [
                    'total' => Package::count(),
                    'active' => Package::where('status', 'active')->count(),
                ],
                'media' => [
                    'total_count' => \App\Models\Media::count(),
                    'total_size'  => \App\Models\Media::sum('size'), // in bytes
                    'orphan_count' => $this->calculateOrphanMedia(),
                ],
                'top_views' => [
                    'packages' => Package::orderByDesc('views_count')->limit(5)->get()->toArray(),
                    'blogs' => \App\Models\Blog::orderByDesc('views_count')->limit(5)->get()->toArray(),
                ],
                'recent_bookings' => Booking::with(['package'])
                    ->latest('createdAt')
                    ->limit(10)
                    ->get()
                    ->toArray(),
                'recent_customers' => \App\Models\Customer::latest()->limit(5)->get()->toArray(),
                'top_packages' => $this->getTopPackages()->toArray(),
                'monthly_revenue' => $this->getMonthlyRevenue(),
                'revenue_7d' => $this->get7DayRevenue(),
                'bookings_7d' => $this->get7DayBookings(),
            ];
        });
    }

    private function get7DayBookings()
    {
        $startDate = now()->subDays(6)->startOfDay();
        
        $counts = Booking::where('createdAt', '>=', $startDate)
            ->select(
                DB::raw("date(createdAt) as date"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $days[] = [
                'date' => $date,
                'label' => now()->subDays($i)->format('D'),
                'total' => (int)($counts[$date] ?? 0)
            ];
        }
        return $days;
    }

    private function get7DayRevenue()
    {
        $startDate = now()->subDays(6)->startOfDay();
        
        $revenues = Booking::where('status', 'confirmed')
            ->where('createdAt', '>=', $startDate)
            ->select(
                DB::raw("date(createdAt) as date"),
                DB::raw("SUM(totalPrice) as total")
            )
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $days[] = [
                'date' => $date,
                'label' => now()->subDays($i)->format('D'),
                'total' => (float)($revenues[$date] ?? 0)
            ];
        }
        return $days;
    }

    private function calculateGrowth()
    {
        $currentMonth = Booking::where('status', 'confirmed')
            ->whereMonth('createdAt', now()->month)
            ->sum('totalPrice');

        $lastMonth = Booking::where('status', 'confirmed')
            ->whereMonth('createdAt', now()->subMonth()->month)
            ->sum('totalPrice');

        if ($lastMonth == 0) {
            return 100;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    private function getTopPackages()
    {
        return Package::select('packages.name', DB::raw('COUNT(bookings.id) as booking_count'))
            ->leftJoin('bookings', 'packages.id', '=', 'bookings.packageId')
            ->groupBy('packages.id', 'packages.name')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get()
            ->pluck('booking_count', 'name');
    }

    private function getMonthlyRevenue()
    {
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "CAST(strftime('%m', createdAt) AS INTEGER)" : "MONTH(createdAt)";

        return Booking::select(
            DB::raw("$monthExpr as month"),
            DB::raw('SUM(totalPrice) as total')
        )
            ->where('status', 'confirmed')
            ->whereYear('createdAt', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function calculateOrphanMedia()
    {
        return \App\Models\Media::get()->filter(fn($m) => $m->usage_count === 0)->count();
    }
}
