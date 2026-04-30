@extends('admin.layout')

@section('title', 'Gallery Management')
@section('page-title', 'Gallery')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Media Gallery</h1>
            <p class="text-gray-600 mt-1 text-sm">Upload and organize your project images</p>
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> Upload Image
        </a>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($images as $image)
            <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="aspect-square bg-gray-100 relative">
                    <img src="{{ $image->url }}" alt="{{ $image->title }}" class="w-full h-full object-cover">
                    
                    <!-- Overlay Actions -->
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                        <button onclick="copyToClipboard('{{ $image->url }}')" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/20 text-white hover:bg-white/40 transition backdrop-blur-md" title="Copy URL">
                            <i class="fas fa-link"></i>
                        </button>
                        <form action="{{ route('admin.gallery.destroy', $image) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/80 text-white hover:bg-red-500 transition backdrop-blur-md" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-3">
                    <p class="text-xs font-bold text-gray-900 truncate">{{ $image->title }}</p>
                    <div class="flex items-center mt-1">
                        @foreach($image->tags ?? [] as $tag)
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded mr-1">#{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-dashed border-gray-300 p-16 text-center">
                <div class="flex flex-col items-center">
                    <i class="fas fa-images text-5xl text-gray-200 mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-600">No images found</h3>
                    <p class="text-gray-400 mt-1">Start by uploading your first gallery image</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $images->links() }}
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Image URL copied to clipboard!');
        });
    }
</script>
@endsection
