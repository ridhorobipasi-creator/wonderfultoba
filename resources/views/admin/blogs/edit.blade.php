@extends('admin.layout')

@section('title', 'Edit Blog Post')
@section('page-title', 'Edit Blog')

@section('content')
<div class="max-w-4xl">
    <div class="mb-8">
        <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center text-sm font-black text-toba-green uppercase tracking-widest hover:text-emerald-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Blogs
        </a>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden p-8">
        <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Title -->
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Blog Title *</label>
                    <input type="text" name="title" value="{{ old('title', $blog->title) }}" required
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-bold text-gray-900">
                </div>

                <!-- Featured Image -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Featured Image</label>
                        <div class="relative group aspect-video rounded-2xl overflow-hidden border border-gray-100 shadow-sm bg-gray-100">
                            @if($blog->image)
                                <img id="image-preview" src="{{ $blog->image }}" class="w-full h-full object-cover">
                            @else
                                <div id="placeholder" class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-4xl mb-2"></i>
                                    <p class="text-xs font-bold uppercase tracking-widest">No Image</p>
                                </div>
                                <img id="image-preview" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                    </div>
                    <div class="pt-8">
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewImage(event)">
                        <label for="image-input" class="inline-flex items-center justify-center w-full px-6 py-4 bg-white border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-toba-green hover:bg-gray-50 transition group">
                            <i class="fas fa-cloud-upload-alt mr-3 text-gray-400 group-hover:text-toba-green transition"></i>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-toba-green">Change Featured Image</span>
                        </label>
                        <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase text-center">Max size: 2MB (Recommended: 1200x630)</p>
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Content *</label>
                    <textarea name="content" id="editor" rows="15">{{ old('content', $blog->content) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Publication Status</label>
                        <select name="status" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-bold text-gray-900">
                            <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <!-- Author (Optional override) -->
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Author Name</label>
                        <input type="text" name="author" value="{{ old('author', $blog->author) }}"
                            class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-toba-green/10 focus:border-toba-green transition font-bold text-gray-900">
                    </div>
                </div>

                <div class="pt-10 border-t border-gray-50 flex items-center gap-4">
                    <button type="submit" class="flex-1 bg-toba-green text-white py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-emerald-700 transition shadow-xl shadow-toba-green/20">
                        <i class="fas fa-check-circle mr-2"></i> Update Blog Post
                    </button>
                    <a href="{{ route('admin.blogs.index') }}" class="px-10 py-5 bg-gray-100 text-gray-600 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-200 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500,
        content_style: 'body { font-family: "Plus Jakarta Sans", sans-serif; font-size: 14px; }'
    });

    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('placeholder');
                if (placeholder) placeholder.classList.add('hidden');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
