@extends('admin.layout')

@section('title', 'Upload Image')
@section('page-title', 'Upload to Gallery')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.gallery.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Gallery
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Upload New Image</h2>

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Caption -->
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Image Caption *</label>
                    <input type="text" name="caption" value="{{ old('caption') }}" required placeholder="e.g. Sunrise at Lake Toba"
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-bold text-gray-900">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Category *</label>
                    <select name="category" required class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-bold text-gray-900">
                        <option value="tour">Tour Gallery</option>
                        <option value="outbound">Outbound Gallery</option>
                    </select>
                </div>

                <!-- Image Selection -->
                <x-image-input 
                    name="gallery_image"
                    label="Select Image *"
                    :value="old('image_id')"
                    :required="true"
                    category="gallery"
                    help="Pilih atau upload gambar untuk galeri"
                />

                @error('gallery_image') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                @error('image_id') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror

                <!-- Tags -->
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Tags (Optional)</label>
                    <input type="text" name="tags[]" placeholder="e.g. nature, lake, morning"
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-bold text-gray-900">
                    <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase">Add tags to help categorize your images</p>
                </div>

                <div class="flex items-center gap-4 pt-8 border-t border-gray-100">
                    <button type="submit" class="flex-1 bg-toba-green text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-green-700 transition shadow-xl shadow-toba-green/20">
                        <i class="fas fa-upload mr-2"></i> Start Upload
                    </button>
                    <a href="{{ route('admin.gallery.index') }}" class="px-8 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-200 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
