<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index()
    {
        $stats = $this->dashboardService->getStats();
        
        return view('admin.dashboard', compact('stats'));
    }

    public function stats()
    {
        $stats = $this->dashboardService->getStats();
        
        return response()->json([
            'total_reservations' => $stats['bookings']['active'], // includes pending & confirmed
            'estimated_revenue' => number_format($stats['revenue']['total'], 0, ',', '.'),
            'active_packages' => $stats['packages']['active'],
            'total_cars' => $stats['cars']['total'],
            'revenue_7d' => $stats['revenue_7d'],
            'recent_bookings' => $stats['recent_bookings']
        ]);
    }
}
