@extends('admin.layout')

@section('title', 'Cars Management')
@section('page-title', 'Cars')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Cars</h1>
            <p class="text-gray-600 mt-1 text-sm">View and manage car rental fleet</p>
        </div>
        <a href="{{ route('admin.cars.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> New Car
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-gray-400"></i>Search
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Name, type..." 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition"
                    >
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-car mr-1 text-gray-400"></i>Type
                    </label>
                    <input 
                        type="text" 
                        name="type" 
                        value="{{ request('type') }}" 
                        placeholder="SUV, MPV, Sedan..." 
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
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Featured Filter -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-star mr-1 text-gray-400"></i>Featured
                    </label>
                    <select name="featured" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        <option value="">All</option>
                        <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured</option>
                        <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Not Featured</option>
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

    <!-- Cars Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Car</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Specs</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($cars as $car)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-sm mr-3 shadow-sm">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $car->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Str::limit($car->description, 40) }}</p>
                                        @if($car->isFeatured)
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                                <i class="fas fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                    <i class="fas fa-car-side mr-1.5"></i>
                                    {{ $car->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm space-y-1">
                                    <p class="text-gray-900">
                                        <i class="fas fa-users text-gray-400 mr-1"></i>
                                        <span class="font-semibold">{{ $car->capacity }}</span> seats
                                    </p>
                                    <p class="text-gray-600">
                                        <i class="fas fa-cog text-gray-400 mr-1"></i>
                                        {{ ucfirst($car->transmission) }}
                                    </p>
                                    <p class="text-gray-600">
                                        <i class="fas fa-gas-pump text-gray-400 mr-1"></i>
                                        {{ $car->fuel }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <p class="font-black text-gray-900">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
                                    @if($car->priceWithDriver)
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-user-tie mr-1"></i>
                                            With driver: Rp {{ number_format($car->priceWithDriver, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $car->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <i class="fas fa-{{ $car->status === 'active' ? 'check-circle' : 'times-circle' }} mr-1.5"></i>
                                    {{ ucfirst($car->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.cars.show', $car) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.cars.edit', $car) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this car?')">
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
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-car text-5xl mb-4"></i>
                                    <p class="text-lg font-bold text-gray-600">No cars found</p>
                                    <p class="text-sm text-gray-500 mt-1">Try adjusting your filters or add a new car</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($cars->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $cars->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
