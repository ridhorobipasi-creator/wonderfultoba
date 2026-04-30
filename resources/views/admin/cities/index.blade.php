@extends('admin.layout')

@section('title', 'Cities Management')
@section('page-title', 'Cities')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Destinations</h1>
            <p class="text-gray-600 mt-1 text-sm">View and manage destination cities</p>
        </div>
        <a href="{{ route('admin.cities.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> Add City
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($cities as $city)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                <div class="h-40 bg-gray-200 relative">
                    @if($city->image)
                        <img src="{{ $city->image }}" alt="{{ $city->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gradient-to-br from-gray-100 to-gray-200">
                            <i class="fas fa-city text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 flex gap-2">
                        <a href="{{ route('admin.cities.edit', $city) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/90 text-green-600 hover:bg-white transition shadow-sm backdrop-blur-sm">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" onsubmit="return confirm('Delete this city?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/90 text-red-600 hover:bg-white transition shadow-sm backdrop-blur-sm">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-black text-gray-900 mb-1">{{ $city->name }}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $city->description ?? 'No description available' }}</p>
                    <div class="flex items-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                        <i class="fas fa-box mr-2"></i> {{ $city->packages_count ?? 0 }} Packages
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($cities->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-16 text-center">
            <div class="flex flex-col items-center">
                <i class="fas fa-map-marked-alt text-5xl text-gray-200 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-600">No destinations found</h3>
                <p class="text-gray-400 mt-1">Start by adding your first destination city</p>
            </div>
        </div>
    @endif

    <div class="mt-6">
        {{ $cities->links() }}
    </div>
</div>
@endsection
