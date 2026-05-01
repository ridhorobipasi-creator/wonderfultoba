@extends('layouts.app')

@section('title', 'Galeri Foto Wisata – Wonderful Toba')
@section('description', 'Koleksi foto perjalanan wisata ke Danau Toba, Berastagi, Bukit Lawang, Tangkahan, dan destinasi indah Sumatera Utara lainnya.')

@section('content')
<div 
    x-data="{ 
        activeCategory: 'Semua', 
        searchQuery: '', 
        images: {{ json_encode($images) }},
        lightbox: { open: false, index: 0 },
        categories: ['Semua', 'Danau Toba', 'Berastagi', 'Bukit Lawang', 'Tangkahan', 'Samosir', 'Lainnya'],
        
        get filteredImages() {
            return this.images.filter(img => {
                const matchCat = this.activeCategory === 'Semua' || img.category === this.activeCategory;
                const matchSearch = !this.searchQuery || 
                                   (img.caption && img.caption.toLowerCase().includes(this.searchQuery.toLowerCase())) || 
                                   (img.category && img.category.toLowerCase().includes(this.searchQuery.toLowerCase()));
                return matchCat && matchSearch;
            });
        },
        
        openLightbox(index) {
            this.lightbox.index = index;
            this.lightbox.open = true;
            document.body.classList.add('overflow-hidden');
        },
        
        closeLightbox() {
            this.lightbox.open = false;
            document.body.classList.remove('overflow-hidden');
        },
        
        prev() {
            this.lightbox.index = (this.lightbox.index - 1 + this.filteredImages.length) % this.filteredImages.length;
        },
        
        next() {
            this.lightbox.index = (this.lightbox.index + 1) % this.filteredImages.length;
        }
    }"
    class="bg-slate-50 min-h-screen pb-24"
    @keydown.escape.window="closeLightbox()"
    @keydown.left.window="if(lightbox.open) prev()"
    @keydown.right.window="if(lightbox.open) next()"
>
    <!-- Hero -->
    <div class="relative bg-slate-900 pt-32 pb-20 px-6 md:px-8 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="/storage/2026/04/lake-toba-premium.png" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 to-slate-900/90"></div>
        <div class="relative z-10 max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-toba-green/20 text-toba-green text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                Galeri Visual
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-5 tracking-tight leading-tight">
                Keindahan <span class="text-toba-accent">Sumatera Utara</span>
            </h1>
            <p class="text-slate-300 text-base md:text-lg max-w-2xl mx-auto font-medium leading-relaxed mb-10">
                Abadikan setiap momen perjalanan Anda bersama Wonderful Toba.
            </p>
            <!-- Search -->
            <div class="max-w-md mx-auto relative">
                <svg class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    placeholder="Cari foto..."
                    x-model="searchQuery"
                    class="w-full pl-14 pr-6 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-toba-green/50 font-medium"
                >
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="sticky top-[72px] z-30 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 md:px-8 py-4 flex gap-2 overflow-x-auto no-scrollbar">
            <template x-for="cat in categories" :key="cat">
                <button
                    @click="activeCategory = cat"
                    :class="activeCategory === cat ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                    class="shrink-0 px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-wider transition-all"
                    x-text="cat"
                ></button>
            </template>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 mt-10">
        <div class="flex items-center justify-between mb-8">
            <p class="text-slate-500 font-medium text-sm">
                Menampilkan <span class="text-toba-green font-black" x-text="filteredImages.length"></span> foto
            </p>
        </div>

        <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
            <template x-for="(img, index) in filteredImages" :key="img.id">
                <div
                    class="break-inside-avoid relative rounded-2xl overflow-hidden group cursor-pointer shadow-sm hover:shadow-xl transition-all duration-300"
                    @click="openLightbox(index)"
                >
                    <img
                        :src="img.imageUrl"
                        :alt="img.caption || 'Galeri Wonderful Toba'"
                        class="w-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p x-show="img.caption" class="text-white text-xs font-bold leading-tight" x-text="img.caption"></p>
                            <span x-show="img.category" class="text-toba-green text-[10px] font-black uppercase tracking-wider" x-text="img.category"></span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredImages.length === 0" class="text-center py-24 bg-white rounded-[2rem] border border-slate-100">
            <svg class="w-12 h-12 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <h3 class="text-xl font-bold text-slate-700 mb-2">Foto tidak ditemukan</h3>
            <p class="text-slate-400">Coba kategori atau kata kunci lain.</p>
        </div>
    </div>

    <!-- Lightbox -->
    <template x-if="lightbox.open">
        <div 
            class="fixed inset-0 z-[100] bg-slate-950/95 backdrop-blur-md flex items-center justify-center p-4"
            @click="closeLightbox()"
        >
            <!-- Close -->
            <button
                @click="closeLightbox()"
                class="absolute top-5 right-5 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-10"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

            <!-- Counter -->
            <div class="absolute top-5 left-5 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white text-sm font-bold">
                <span x-text="lightbox.index + 1"></span> / <span x-text="filteredImages.length"></span>
            </div>

            <!-- Prev -->
            <button
                @click.stop="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-10"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            </button>

            <!-- Image Area -->
            <div class="max-w-5xl max-h-[80vh] relative" @click.stop>
                <img
                    :src="filteredImages[lightbox.index].imageUrl"
                    class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl"
                >
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/80 to-transparent p-6 rounded-b-2xl">
                    <p class="text-white font-bold" x-text="filteredImages[lightbox.index].caption"></p>
                    <p class="text-toba-green text-xs font-black uppercase tracking-wider mt-1" x-text="filteredImages[lightbox.index].category"></p>
                </div>
            </div>

            <!-- Next -->
            <button
                @click.stop="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-10"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </template>
</div>
@endsection
