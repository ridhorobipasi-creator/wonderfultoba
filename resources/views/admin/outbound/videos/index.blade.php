@extends('admin.layout')

@section('title', 'Video Outbound')
@section('page-title', 'Video Outbound')

@section('content')
<div x-data="{ addOpen: false }" class="space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $videos->count() }} Video Terdaftar</p>
        <button @click="addOpen = true" class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 transition hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Tambah Video
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($videos as $video)
            @php
                // Parse standard youtube URL to embed format if needed
                $embedUrl = $video->youtubeUrl;
                if(str_contains($embedUrl, 'watch?v=')) {
                    $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                    $embedUrl = explode('&', $embedUrl)[0];
                }
            @endphp
            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden group hover:shadow-xl transition-all">
                <div class="aspect-video bg-slate-900 relative">
                    <iframe class="w-full h-full" src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="p-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="font-black text-slate-900 tracking-tight text-lg line-clamp-2">{{ $video->title }}</h3>
                    <form action="{{ route('admin.outbound.videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Hapus video ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 shadow-sm hover:bg-rose-100 transition flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-slate-100">
                <p class="text-slate-400 font-black uppercase tracking-widest text-sm">Belum ada video terdaftar. Tambahkan video dokumentasi.</p>
            </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div x-show="addOpen" x-cloak class="fixed inset-0 z-[150] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-6" @click.self="addOpen = false">
        <div class="bg-white rounded-[3rem] p-12 w-full max-w-lg shadow-2xl">
            <h3 class="text-xl font-black text-slate-900 mb-8">Tambah Video Baru</h3>
            <form action="{{ route('admin.outbound.videos.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul Video</label>
                    <input type="text" name="title" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">URL YouTube</label>
                    <input type="url" name="youtubeUrl" required placeholder="https://www.youtube.com/watch?v=..." class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" @click="addOpen = false" class="flex-1 py-4 rounded-2xl border border-slate-200 text-slate-500 font-black text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
