<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats()
    {
        return [
            'revenue' => [
                'total' => Booking::where('status', 'confirmed')->sum('totalPrice'),
                'monthly' => Booking::where('status', 'confirmed')
                    ->whereMonth('createdAt', now()->month)
                    ->sum('totalPrice'),
                'growth' => $this->calculateGrowth(),
            ],
            'bookings' => [
                'active' => Booking::whereIn('status', ['pending', 'confirmed'])->count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'completed' => Booking::where('status', 'completed')->count(),
            ],
            'users' => [
                'total' => User::count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ],
            'packages' => [
                'total' => Package::count(),
                'active' => Package::where('status', 'active')->count(),
                'tour' => Package::where('isOutbound', false)->count(),
                'outbound' => Package::where('isOutbound', true)->count(),
            ],
            'cars' => [
                'total' => Car::count(),
                'available' => Car::where('status', 'available')->count(),
            ],
            'recent_bookings' => Booking::with(['package', 'car'])
                ->latest('createdAt')
                ->limit(10)
                ->get(),
            'top_packages' => $this->getTopPackages(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
        ];
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
        return Package::select('packages.*', DB::raw('COUNT(bookings.id) as booking_count'))
            ->leftJoin('bookings', 'packages.id', '=', 'bookings.packageId')
            ->groupBy('packages.id')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();
    }

    private function getMonthlyRevenue()
    {
        return Booking::select(
            DB::raw("CAST(strftime('%m', createdAt) AS INTEGER) as month"),
            DB::raw('SUM(totalPrice) as total')
        )
            ->where('status', 'confirmed')
            ->whereYear('createdAt', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
