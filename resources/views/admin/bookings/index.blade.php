@extends('admin.layout')

@section('title', 'Bookings Management')
@section('page-title', 'Bookings')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Bookings</h1>
            <p class="text-gray-600 mt-1 text-sm">View and manage all customer reservations</p>
        </div>
        <a href="{{ route('admin.bookings.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> New Booking
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-gray-400"></i>Search
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Code, name, email..." 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-flag mr-1 text-gray-400"></i>Status
                    </label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-filter mr-1 text-gray-400"></i>Type
                    </label>
                    <select name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        <option value="">All Types</option>
                        <option value="package" {{ request('type') == 'package' ? 'selected' : '' }}>Package</option>
                        <option value="car" {{ request('type') == 'car' ? 'selected' : '' }}>Car Rental</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
                        <i class="fas fa-search mr-2"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
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
                                        <p class="text-xs text-gray-500">{{ $booking->customerPhone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $booking->type == 'package' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    <i class="fas {{ $booking->type == 'package' ? 'fa-box' : 'fa-car' }} mr-1.5"></i>
                                    {{ ucfirst($booking->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900 font-medium">
                                    {{ $booking->package->name ?? $booking->car->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="far fa-calendar mr-2 text-gray-400"></i>{{ $booking->startDate?->format('d M Y') }}
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-inbox text-5xl mb-4"></i>
                                    <p class="text-lg font-bold text-gray-600">No bookings found</p>
                                    <p class="text-sm text-gray-500 mt-1">Try adjusting your filters or create a new booking</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
