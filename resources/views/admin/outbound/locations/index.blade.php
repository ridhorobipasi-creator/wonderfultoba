@extends('admin.layout')

@section('title', 'Outbound Locations')
@section('page-title', 'Outbound Locations')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Locations</h1>
            <p class="text-gray-600 mt-1 text-sm">Destinations for outbound activities</p>
        </div>
        <button class="inline-flex items-center justify-center bg-toba-green text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> Add Location
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($locations as $location)
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden group">
                <div class="aspect-square bg-gray-100">
                    <img src="{{ $location->image }}" alt="{{ $location->name }}" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="font-bold text-sm text-gray-900">{{ $location->name }}</span>
                    <form action="{{ route('admin.outbound.locations.destroy', $location) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-gray-400 font-bold">No locations found</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
