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
                <!-- Title -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Image Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Sunrise at Lake Toba"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                </div>

                <!-- File -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Select Image *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-toba-green transition group bg-gray-50/50">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(event)" required>
                        <label for="image" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-toba-green transition mb-3"></i>
                            <p class="text-sm font-bold text-gray-700">Click to select an image</p>
                            <p class="text-xs text-gray-500 mt-1">JPEG, PNG, JPG, WEBP (Max 5MB)</p>
                        </label>
                    </div>
                    <div id="image-preview" class="mt-4 hidden">
                        <img src="" class="max-h-64 rounded-xl mx-auto shadow-sm border border-gray-100">
                    </div>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tags (Optional)</label>
                    <input type="text" name="tags[]" placeholder="e.g. nature, lake, morning"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                    <p class="mt-1 text-xs text-gray-400">Add tags to help categorize your images</p>
                </div>

                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
                        <i class="fas fa-upload mr-2"></i> Start Upload
                    </button>
                    <a href="{{ route('admin.gallery.index') }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.querySelector('#image-preview');
        const img = preview.querySelector('img');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
