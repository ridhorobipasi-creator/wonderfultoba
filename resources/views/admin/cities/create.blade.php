@extends('admin.layout')

@section('title', 'Add New City')
@section('page-title', 'Add City')

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <a href="{{ route('admin.cities.index') }}" class="inline-flex items-center text-sm font-black text-toba-green uppercase tracking-widest hover:text-emerald-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Cities
        </a>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden p-8">
        <form action="{{ route('admin.cities.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- City Name -->
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">City Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Medan, Parapat, Berastagi"
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-bold text-gray-900">
                    @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Region -->
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Region / Province</label>
                    <input type="text" name="region" value="{{ old('region', 'Sumatera Utara') }}" placeholder="e.g. Sumatera Utara"
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-bold text-gray-900">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="description" rows="5" placeholder="Tell something about this city..."
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-medium text-gray-700">{{ old('description') }}</textarea>
                </div>

                <div class="pt-6 border-t border-gray-50 flex items-center gap-4">
                    <button type="submit" class="flex-1 bg-toba-green text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-emerald-700 transition shadow-xl shadow-toba-green/20">
                        <i class="fas fa-save mr-2"></i> Save City
                    </button>
                    <a href="{{ route('admin.cities.index') }}" class="px-8 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-200 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
