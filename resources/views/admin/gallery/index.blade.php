@extends('admin.layout')

@section('title', 'Gallery')
@section('page-title', 'Media Library')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Gallery Assets</h1>
        <a href="{{ route('admin.gallery.create') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200">
            <i class="fas fa-plus mr-2"></i> Upload Image
        </a>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
        @forelse($images as $image)
            <div class="group relative bg-white rounded-3xl border border-slate-50 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                <div class="aspect-square bg-slate-50 relative overflow-hidden">
                    <img src="{{ $image->imageUrl }}" alt="{{ $image->caption }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    
                    <!-- Subtle Overlay -->
                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                        <button onclick="copyToClipboard('{{ $image->imageUrl }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/20 text-white hover:bg-white/40 transition backdrop-blur-md" title="Copy URL">
                            <i class="fas fa-link text-xs"></i>
                        </button>
                        <form action="{{ route('admin.gallery.destroy', $image) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-500/80 text-white hover:bg-rose-500 transition backdrop-blur-md" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Minimal Badge -->
                    <div class="absolute top-3 left-3 px-2 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-[8px] font-black uppercase tracking-widest text-slate-900 shadow-sm">
                        {{ $image->category }}
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-[10px] font-black text-slate-900 truncate tracking-tight mb-1">{{ $image->caption }}</p>
                    <div class="flex items-center gap-1 overflow-hidden">
                        @php $tags = is_string($image->tags) ? json_decode($image->tags, true) : $image->tags; @endphp
                        @forelse($tags ?? [] as $tag)
                            <span class="text-[8px] font-bold text-slate-400">#{{ $tag }}</span>
                        @empty
                            <span class="text-[8px] font-bold text-slate-300 italic">No tags</span>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                <i class="fas fa-images text-3xl text-slate-100 mb-4"></i>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">Empty Gallery</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($images->hasPages())
        <div class="pt-10">
            {{ $images->links() }}
        </div>
    @endif
</div>

<script>
    function copyToClipboard(text) {
        const fullUrl = window.location.origin + text;
        navigator.clipboard.writeText(fullUrl).then(() => {
            // Optional: Add a subtle toast instead of alert
            alert('Image URL copied!');
        });
    }
</script>
@endsection
