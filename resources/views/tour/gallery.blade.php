@extends('layouts.app')

@section('title', __('Galeri Foto Wisata – Wonderful Toba'))
@section('description', __('Koleksi foto perjalanan wisata ke Danau Toba, Berastagi, Bukit Lawang, Tangkahan, dan destinasi indah Sumatera Utara lainnya.'))

@section('content')
<div 
    x-data="{ 
        activeCategory: 'Semua', 
        searchQuery: '', 
        images: {{ json_encode($images) }},
        lightbox: { open: false, index: 0 },
        categories: ['Semua', ...new Set({{ json_encode($images->pluck('category')->unique()->toArray()) }})],
        
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
    class="bg-white min-h-screen pb-32"
    @keydown.escape.window="closeLightbox()"
    @keydown.left.window="if(lightbox.open) prev()"
    @keydown.right.window="if(lightbox.open) next()"
>
    <!-- Cinematic Hero -->
    <div class="relative h-[60dvh] flex items-center overflow-hidden bg-slate-900">
        @php
        $heroImage = imageUrl(
            $siteSettings['cms_tour']['hero_image_url'] ?? $siteSettings['cms_landing']['tour_image_url'] ?? null,
            asset('images/home/tour.webp')
        );
    @endphp

        <img src="{{ $heroImage }}" alt="Gallery Hero" class="absolute inset-0 w-full h-full object-cover opacity-60 animate-subtle-zoom" fetchpriority="high" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-8 text-white pt-20">
            <div class="max-w-4xl animate-in fade-in slide-in-from-left-12 duration-1000">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                    <span class="text-toba-accent font-black text-xs uppercase tracking-[0.4em]">{{ __('Visual Storytelling') }}</span>
                </div>
                <h1 class="text-5xl md:text-8xl font-black tracking-tighter leading-[0.9] mb-10">
                    {{ __('Galeri') }} <br />
                    <span class="text-toba-green">{{ __('Momen Indah') }}</span>
                </h1>
                <p class="text-slate-300 text-lg md:text-2xl font-medium max-w-2xl leading-relaxed">
                    {{ __('Setiap jepretan adalah cerita yang menanti untuk dijelajahi. Lihat keindahan Sumatera Utara melalui mata para petualang kami.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Category Filter & Search Bar (Sticky Premium) -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 -mt-20 relative z-30">
        <div class="bg-white rounded-[3.5rem] shadow-[0_50px_100px_-20px_rgba(15,23,42,0.1)] p-8 md:p-12 border border-slate-100 animate-in fade-in zoom-in duration-1000 delay-300">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
                <!-- Filters -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                    <template x-for="cat in categories" :key="cat">
                        <button
                            @click="activeCategory = cat"
                            :class="activeCategory === cat ? 'bg-slate-900 text-white shadow-2xl scale-105' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-100'"
                            class="px-8 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-500 whitespace-nowrap"
                            x-text="cat === 'Semua' ? '{{ __('Semua') }}' : cat"
                        ></button>
                    </template>
                </div>
                
                <!-- Search Box -->
                <div class="relative group w-full lg:w-96">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition-colors pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                    <input
                        type="text"
                        :placeholder="'{{ __('Cari foto atau momen...') }}'"
                        x-model="searchQuery"
                        class="w-full pl-16 pr-8 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-toba-green/10 font-bold text-slate-900 placeholder:text-slate-400 text-sm transition-all"
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Masonry Gallery Grid -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 mt-24">
        <div class="flex items-center justify-between mb-16">
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter">{{ __('Koleksi') }} <span class="text-toba-green">{{ __('Visual') }}</span></h2>
                <p class="text-slate-500 font-medium text-sm mt-2">
                    {{ __('Menampilkan') }} <span class="text-toba-green font-black" x-text="filteredImages.length"></span> {{ __('mahakarya alam Sumatera Utara') }}
                </p>
            </div>
        </div>

        <div class="columns-1 md:columns-2 lg:columns-3 xl:columns-4 gap-8 space-y-8">
            <template x-for="(img, index) in filteredImages" :key="index">
                <div
                    class="break-inside-avoid relative rounded-[2.5rem] overflow-hidden group cursor-pointer shadow-xl transition-all duration-700 hover:-translate-y-2 hover:shadow-toba-green/20 border border-slate-50"
                    @click="openLightbox(index)"
                    x-transition:enter="transition opacity duration-500"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                >
                    <img
                        :src="img.image_url"
                        :alt="img.caption || 'Galeri Wonderful Toba'"
                        class="w-full object-cover transform group-hover:scale-110 transition-transform duration-[2s] ease-out"
                        loading="lazy"
                        decoding="async"
                    >
                    
                    <!-- Cinematic Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-700 flex flex-col justify-end p-8">
                        <div class="transform translate-y-8 group-hover:translate-y-0 transition-all duration-700">
                            <span x-show="img.category" class="inline-block px-4 py-1.5 bg-toba-green text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl mb-4 shadow-lg shadow-toba-green/30" x-text="img.category"></span>
                            <p x-show="img.caption" class="text-white text-lg font-black leading-tight tracking-tight mb-2" x-text="img.caption"></p>
                            <div class="flex items-center gap-2 text-white/60 text-[9px] font-black uppercase tracking-[0.2em]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3"/></svg>
                                {{ __('Lihat Detail') }}
                            </div>
                        </div>
                    </div>

                    <!-- Expansion Icon (Floating) -->
                    <div class="absolute top-8 right-8 scale-0 group-hover:scale-100 transition-all duration-500 delay-100">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-xl border border-white/20 rounded-2xl flex items-center justify-center text-white shadow-2xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Premium Empty State -->
        <div x-show="filteredImages.length === 0" class="text-center py-40 bg-slate-50 rounded-[4rem] border-2 border-dashed border-slate-200 animate-in fade-in zoom-in duration-700">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-slate-200 text-slate-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <h3 class="text-4xl font-black text-slate-900 mb-6 tracking-tight">{{ __('Koleksi Belum Ditemukan') }}</h3>
            <p class="text-slate-500 font-medium max-w-md mx-auto mb-12 leading-relaxed">{{ __('Kami belum menemukan foto yang sesuai dengan filter atau kata kunci Anda. Cobalah kategori lain atau kata kunci yang lebih umum.') }}</p>
            <button @click="activeCategory = 'Semua'; searchQuery = ''"
                class="bg-slate-900 text-white px-12 py-6 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-toba-green transition-all duration-500">
                {{ __('Reset Galeri') }}
            </button>
        </div>
    </div>

    <!-- Premium Lightbox Overlay -->
    <template x-if="lightbox.open">
        <div 
            class="fixed inset-0 z-[200] bg-slate-950/98 backdrop-blur-3xl flex items-center justify-center p-6 md:p-12"
            @click="closeLightbox()"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <!-- Close Button -->
            <button
                @click="closeLightbox()"
                class="absolute top-10 right-10 w-16 h-16 bg-white/5 hover:bg-rose-500 hover:text-white rounded-[1.5rem] flex items-center justify-center text-white transition-all z-[210] border border-white/10 group"
            >
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>

            <!-- Counter Info -->
            <div class="absolute top-10 left-10 bg-white/5 backdrop-blur-xl border border-white/10 px-8 py-4 rounded-[1.5rem] text-white text-[10px] font-black uppercase tracking-[0.4em] z-[210] hidden md:block">
                {{ __('FOTO') }} <span x-text="lightbox.index + 1" class="text-toba-accent text-lg"></span> {{ __('DARI') }} <span x-text="filteredImages.length" class="text-lg opacity-40"></span>
            </div>

            <!-- Navigation Controls -->
            <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-8 md:px-12 pointer-events-none z-[210]">
                <button
                    @click.stop="prev()"
                    class="w-20 h-20 bg-white/5 hover:bg-toba-green backdrop-blur-xl rounded-[2rem] flex items-center justify-center text-white transition-all pointer-events-auto border border-white/10 shadow-2xl group"
                >
                    <svg class="w-8 h-8 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button
                    @click.stop="next()"
                    class="w-20 h-20 bg-white/5 hover:bg-toba-green backdrop-blur-xl rounded-[2rem] flex items-center justify-center text-white transition-all pointer-events-auto border border-white/10 shadow-2xl group"
                >
                    <svg class="w-8 h-8 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <!-- Lightbox Image Content -->
            <div class="relative w-full h-full flex flex-col items-center justify-center" @click.stop>
                <div class="relative max-w-6xl w-full h-full flex flex-col items-center justify-center p-8 md:p-20">
                    <img
                        :src="filteredImages[lightbox.index].image_url"
                        class="max-w-full max-h-[75vh] object-contain rounded-[3rem] shadow-[0_50px_150px_-30px_rgba(0,0,0,1)] border border-white/10"
                        x-transition:enter="transition ease-out duration-700 delay-100"
                        x-transition:enter-start="opacity-0 scale-90 translate-y-10"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    >
                    <!-- Caption Overlay (Bottom) -->
                    <div class="mt-12 text-center max-w-3xl animate-in fade-in slide-in-from-bottom-8 duration-700">
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <span x-text="filteredImages[lightbox.index].category" class="px-5 py-2 bg-toba-green text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-full shadow-lg shadow-toba-green/20"></span>
                        </div>
                        <h4 class="text-white text-3xl md:text-5xl font-black tracking-tighter leading-tight" x-text="filteredImages[lightbox.index].caption"></h4>
                        
                        <!-- Dynamic Link for Package/Blog -->
                        <template x-if="filteredImages[lightbox.index].type === 'package' || filteredImages[lightbox.index].type === 'blog'">
                            <div class="mt-8">
                                <a :href="filteredImages[lightbox.index].type === 'package' ? '/tour/package/' + filteredImages[lightbox.index].slug : '/tour/blog/' + filteredImages[lightbox.index].slug" 
                                   class="inline-flex items-center gap-3 px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-toba-green hover:text-white transition-all shadow-2xl">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span x-text="filteredImages[lightbox.index].type === 'package' ? '{{ __('Lihat Paket Wisata') }}' : '{{ __('Baca Artikel Selengkapnya') }}'"></span>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    @keyframes subtle-zoom {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }
    .animate-subtle-zoom {
        animation: subtle-zoom 20s infinite alternate ease-in-out;
    }
</style>
@endsection
