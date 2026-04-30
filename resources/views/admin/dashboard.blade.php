@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-toba-green to-emerald-600 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-emerald-100">Here's what's happening with your business today.</p>
            </div>
            <div class="hidden lg:block">
                <i class="fas fa-chart-line text-6xl text-white opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs font-bold bg-green-100 text-green-700 px-3 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i>{{ $stats['revenue']['growth'] }}%
                </span>
            </div>
            <p class="text-gray-600 text-sm font-semibold mb-1">Total Revenue</p>
            <h3 class="text-2xl font-black text-gray-900">Rp {{ number_format($stats['revenue']['total'], 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-500 mt-2">
                <i class="far fa-calendar mr-1"></i>This month: Rp {{ number_format($stats['revenue']['monthly'], 0, ',', '.') }}
            </p>
        </div>

        <!-- Active Bookings -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <span class="text-xs font-bold bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                    {{ $stats['bookings']['pending'] }} pending
                </span>
            </div>
            <p class="text-gray-600 text-sm font-semibold mb-1">Active Bookings</p>
            <h3 class="text-2xl font-black text-gray-900">{{ $stats['bookings']['active'] }}</h3>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-check-circle mr-1"></i>{{ $stats['bookings']['confirmed'] }} confirmed
            </p>
        </div>

        <!-- Total Packages -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
                <span class="text-xs font-bold bg-purple-100 text-purple-700 px-3 py-1 rounded-full">
                    {{ $stats['packages']['active'] }} active
                </span>
            </div>
            <p class="text-gray-600 text-sm font-semibold mb-1">Total Packages</p>
            <h3 class="text-2xl font-black text-gray-900">{{ $stats['packages']['total'] }}</h3>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-hiking mr-1"></i>{{ $stats['packages']['tour'] }} tour, {{ $stats['packages']['outbound'] }} outbound
            </p>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-orange-600 text-xl"></i>
                </div>
                <span class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                    +{{ $stats['users']['new_this_month'] }} new
                </span>
            </div>
            <p class="text-gray-600 text-sm font-semibold mb-1">Total Users</p>
            <h3 class="text-2xl font-black text-gray-900">{{ $stats['users']['total'] }}</h3>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-user-plus mr-1"></i>Registered members
            </p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Monthly Revenue</h3>
                    <p class="text-sm text-gray-500">Revenue overview for {{ now()->year }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Total</p>
                    <p class="text-lg font-black text-toba-green">Rp {{ number_format($stats['monthly_revenue']->sum('total'), 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="h-64 flex items-end justify-between gap-2">
                @php
                    $maxRevenue = $stats['monthly_revenue']->max('total') ?: 1;
                @endphp
                @for($i = 1; $i <= 12; $i++)
                    @php
                        $monthData = $stats['monthly_revenue']->firstWhere('month', $i);
                        $revenue = $monthData->total ?? 0;
                        $height = $revenue > 0 ? ($revenue / $maxRevenue * 100) : 2;
                    @endphp
                    <div class="flex-1 bg-gradient-to-t from-toba-green to-emerald-400 rounded-t-xl hover:from-toba-green/80 hover:to-emerald-400/80 transition relative group cursor-pointer" 
                         style="height: {{ $height }}%">
                        <div class="absolute -top-16 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none z-10 shadow-xl">
                            <p class="font-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="grid grid-cols-12 gap-2 mt-4 text-xs text-gray-500 text-center font-semibold">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
                <span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
            </div>
        </div>

        <!-- Top Packages -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Top Packages</h3>
                    <p class="text-sm text-gray-500">Most booked packages</p>
                </div>
                <i class="fas fa-trophy text-yellow-500 text-xl"></i>
            </div>
            <div class="space-y-3">
                @forelse($stats['top_packages'] as $index => $package)
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition group">
                        <div class="w-8 h-8 bg-gradient-to-br from-toba-green to-emerald-600 rounded-lg flex items-center justify-center text-white font-black text-sm mr-3 shadow-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 truncate text-sm">{{ $package->name }}</p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-calendar-check mr-1"></i>{{ $package->booking_count }} bookings
                            </p>
                        </div>
                        <span class="text-toba-green font-black text-sm ml-3 whitespace-nowrap">Rp {{ number_format($package->price / 1000, 0) }}K</span>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-3"></i>
                        <p class="text-sm font-medium">No packages yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-gray-900">Recent Bookings</h3>
                <p class="text-sm text-gray-500">Latest booking activities</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center text-toba-green hover:text-toba-green/80 text-sm font-bold transition group">
                View All 
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Package/Car</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($stats['recent_bookings'] as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-lg">{{ $booking->bookingCode ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3 shadow-sm">
                                        {{ strtoupper(substr($booking->customerName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $booking->customerName }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->customerEmail }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900 font-medium">
                                    {{ $booking->package->name ?? $booking->car->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="far fa-calendar mr-2"></i>{{ $booking->startDate?->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-black text-gray-900">Rp {{ number_format($booking->totalPrice, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => 'fa-clock'],
                                        'confirmed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'fa-check-circle'],
                                        'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'fa-flag-checkered'],
                                        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'fa-times-circle'],
                                    ];
                                    $config = $statusConfig[$booking->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'fa-question'];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $config['bg'] }} {{ $config['text'] }}">
                                    <i class="fas {{ $config['icon'] }} mr-1.5"></i>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-inbox text-5xl mb-4"></i>
                                    <p class="text-lg font-bold text-gray-600">No bookings yet</p>
                                    <p class="text-sm text-gray-500 mt-1">Bookings will appear here once customers make reservations</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
