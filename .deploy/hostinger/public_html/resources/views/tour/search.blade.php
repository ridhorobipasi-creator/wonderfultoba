@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . $query . ' – Sujailake Toba')

@section('content')
<div class="min-h-screen bg-slate-50 pt-32 pb-20 px-4 md:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <span class="text-lake-blue text-[11px] font-black uppercase tracking-[0.3em] mb-3 block">Hasil Pencarian</span>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                Mencari: <span class="text-lake-blue">"{{ $query }}"</span>
            </h1>
            <p class="text-slate-500 font-medium mt-4">
                Ditemukan {{ $packages->count() }} paket dan {{ $blogs->count() }} artikel.
            </p>

            <!-- Refine Search Bar -->
            <div class="mt-10 max-w-xl">
                <form action="{{ route('tour.search') }}" method="GET" class="relative group">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Cari destinasi atau paket lainnya..." class="w-full bg-white border-2 border-slate-100 py-5 pl-8 pr-16 rounded-[2rem] font-bold text-slate-900 focus:outline-none focus:border-lake-blue focus:shadow-xl transition-all">
                    <button type="submit" class="absolute right-3 top-3 bottom-3 w-12 bg-lake-blue text-white rounded-[1.2rem] flex items-center justify-center hover:bg-slate-900 transition-all">
                        <i class="fas fa-search text-xs"></i>
                    </button>
                </form>
            </div>
        </div>

        @if($packages->count() > 0)
        <!-- Packages Section -->
        <div class="mb-20">
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Paket Wisata</h2>
                <div class="flex-grow h-px bg-slate-200"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($packages as $pkg)
                <a href="{{ route('tour.package.detail', $pkg->slug) }}" class="group block bg-white rounded-[2rem] overflow-hidden border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ imageUrl($pkg->images[0] ?? null) }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute bottom-4 left-4">
                            <span class="px-3 py-1 bg-lake-blue text-white text-[9px] font-black uppercase tracking-widest rounded-full">{{ $pkg->duration }}</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-lake-blue transition-colors">{{ $pkg->name }}</h3>
                        <p class="text-slate-500 text-sm line-clamp-2 mb-6">{{ $pkg->shortDescription }}</p>
                        <div class="flex items-center justify-between">
                            <p class="text-xl font-black text-lake-blue">Rp {{ number_format($pkg->price, 0, ',', '.') }}</p>
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:bg-lake-blue group-hover:text-white transition-all">
                                <i class="fas fa-arrow-right -rotate-45"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($blogs->count() > 0)
        <!-- Blogs Section -->
        <div>
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Artikel & Berita</h2>
                <div class="flex-grow h-px bg-slate-200"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($blogs as $post)
                <a href="{{ route('tour.blog.detail', $post->slug) }}" class="flex gap-6 bg-white p-6 rounded-3xl border border-slate-100 hover:border-lake-blue/30 transition-all group">
                    <div class="w-32 h-32 rounded-2xl overflow-hidden shrink-0">
                        <img src="{{ imageUrl($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-lake-blue uppercase tracking-widest mb-2 block">{{ $post->category }}</span>
                        <h3 class="text-lg font-black text-slate-900 line-clamp-2 group-hover:text-lake-blue transition-colors">{{ $post->title }}</h3>
                        <p class="text-slate-500 text-sm mt-2 line-clamp-1">{{ $post->excerpt }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($packages->count() === 0 && $blogs->count() === 0)
        <!-- Empty State -->
        <div class="text-center py-40 bg-white rounded-[4rem] border border-slate-100">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-300 text-3xl">
                <i class="fas fa-search"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4">Tidak Ditemukan Hasil</h2>
            <p class="text-slate-500 max-w-md mx-auto mb-10">Maaf, kami tidak menemukan apa pun untuk kata kunci "{{ $query }}". Coba cari dengan kata kunci lain seperti "Toba", "Samosir", atau "Outbound".</p>
            <a href="{{ route('tour.packages') }}" class="inline-block px-10 py-5 bg-lake-blue text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl">
                Kembali ke Paket
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
