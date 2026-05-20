@extends('layouts.app')

@section('title', __('Galeri Foto Wisata – Sujai Laketoba'))
@section('description', __('Koleksi foto perjalanan wisata ke Danau Toba, Berastagi, Bukit Lawang, Tangkahan, dan destinasi indah Sumatera Utara lainnya.'))

@section('content')
<div 
    x-data="{ 
        activeCategory: 'Semua', 
        searchQuery: '', 
        images: @js($images),
        lightbox: { open: false, index: 0 },
        categories: ['Semua', ...new Set(@js($images->pluck('category')->unique()->toArray()))],
        
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
    class="bg-slate-50 min-h-screen pb-32"
    @keydown.escape.window="closeLightbox()"
    @keydown.left.window="if(lightbox.open) prev()"
    @keydown.right.window="if(lightbox.open) next()"
>
    <!-- Cinematic Hero -->
    <div class="relative h-[55dvh] flex items-center overflow-hidden bg-slate-900">
        @php
        $heroImage = imageUrl(
            $siteSettings['cms_tour']['hero_image_url'] ?? $siteSettings['cms_landing']['tour_image_url'] ?? null,
            asset('images/home/tour.webp')
        );
    @endphp

        <img src="{{ $heroImage }}" alt="Gallery Hero" class="absolute inset-0 w-full h-full object-cover opacity-60 animate-subtle-zoom" fetchpriority="high" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-transparent"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-8 text-white pt-20">
            <div class="max-w-4xl animate-in fade-in slide-in-from-left-12 duration-1000">
                <div class="flex items-center space-x-2 mb-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-toba-green/20 backdrop-blur-md border border-white/10 text-white text-[10px] font-semibold uppercase tracking-[0.2em] rounded-full">
                        {{ __('Visual Storytelling') }}
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-light tracking-tight leading-tight mb-6">
                    {{ __('Galeri') }} <span class="text-toba-green">{{ __('Momen Indah') }}</span>
                </h1>
                <p class="text-slate-200 text-sm md:text-base max-w-xl leading-relaxed font-normal">
                    {{ __('Setiap jepretan adalah cerita yang menanti untuk dijelajahi. Lihat keindahan Sumatera Utara melalui mata para petualang kami.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Category Filter & Search Bar -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 -mt-16 relative z-30">
        <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-sm p-6 border border-slate-100 animate-in fade-in zoom-in duration-1000 delay-300">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <!-- Filters -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-2">
                    <template x-for="cat in categories" :key="cat">
                        <button
                            @click="activeCategory = cat"
                            :class="activeCategory === cat ? 'bg-toba-green text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200/50'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap"
                            x-text="cat === 'Semua' ? '{{ __('Semua') }}' : cat"
                        ></button>
                    </template>
                </div>
                
                <!-- Search Box -->
                <div class="relative group w-full lg:w-80">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-toba-green transition-colors pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                    <input
                        type="text"
                        :placeholder="'{{ __('Cari foto atau momen...') }}'"
                        x-model="searchQuery"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50/50 border border-slate-200/50 rounded-2xl focus:ring-1 focus:ring-toba-green font-medium text-slate-700 placeholder:text-slate-400 text-sm outline-none"
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Masonry Gallery Grid -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 mt-16">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ __('Koleksi') }} <span class="text-toba-green">{{ __('Visual') }}</span></h2>
                <p class="text-slate-500 font-normal text-xs mt-1">
                    {{ __('Menampilkan') }} <span class="text-toba-green font-bold" x-text="filteredImages.length"></span> {{ __('mahakarya alam Sumatera Utara') }}
                </p>
            </div>
        </div>

        <div class="columns-1 md:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
            <template x-for="(img, index) in filteredImages" :key="index">
                <div
                    class="break-inside-avoid relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm transition-all duration-300 border border-slate-100 hover:border-slate-200 bg-white"
                    @click="openLightbox(index)"
                    x-transition:enter="transition opacity duration-500"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                >
                    <img
                        :src="img.image_url"
                        :alt="img.caption || 'Galeri Sujai Laketoba'"
                        class="w-full object-cover transform group-hover:scale-105 transition-transform duration-[1.5s] ease-out"
                        loading="lazy"
                        decoding="async"
                    >
                    
                    <!-- Cinematic Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6">
                        <div class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                            <span x-show="img.category" class="inline-block px-2.5 py-1 bg-toba-green text-white text-[9px] font-semibold uppercase tracking-wider rounded-lg mb-3 shadow-sm" x-text="img.category"></span>
                            <p x-show="img.caption" class="text-white text-base font-bold leading-tight tracking-tight mb-2" x-text="img.caption"></p>
                            <div class="flex items-center gap-1.5 text-white/70 text-[9px] font-semibold uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3"/></svg>
                                {{ __('Lihat Detail') }}
                            </div>
                        </div>
                    </div>

                    <!-- Expansion Icon (Floating) -->
                    <div class="absolute top-6 right-6 scale-0 group-hover:scale-100 transition-all duration-300 delay-75">
                        <div class="w-8 h-8 bg-white/20 backdrop-blur-md border border-white/20 rounded-xl flex items-center justify-center text-white shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Premium Empty State -->
        <div x-show="filteredImages.length === 0" class="text-center py-24 bg-white rounded-3xl border border-slate-100 shadow-sm animate-in fade-in zoom-in duration-700">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <h3 class="text-2xl font-light text-slate-900 mb-3 tracking-tight">{{ __('Koleksi Belum Ditemukan') }}</h3>
            <p class="text-slate-500 text-xs font-normal max-w-xs mx-auto mb-8 leading-relaxed">{{ __('Kami belum menemukan foto yang sesuai dengan filter atau kata kunci Anda. Cobalah kategori lain atau kata kunci yang lebih umum.') }}</p>
            <button @click="activeCategory = 'Semua'; searchQuery = ''"
                class="bg-slate-955 text-white px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-toba-green transition-colors duration-300">
                {{ __('Reset Galeri') }}
            </button>
        </div>
    </div>

    <!-- Premium Lightbox Overlay -->
    <template x-if="lightbox.open">
        <div 
            class="fixed inset-0 z-[200] bg-slate-950/95 backdrop-blur-md flex items-center justify-center p-6 md:p-12"
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
                class="absolute top-6 right-6 w-12 h-12 bg-white/5 hover:bg-rose-500 hover:text-white rounded-xl flex items-center justify-center text-white transition-all z-[210] border border-white/10 group"
            >
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>

            <!-- Counter Info -->
            <div class="absolute top-6 left-6 bg-white/5 backdrop-blur-md border border-white/10 px-5 py-2.5 rounded-xl text-white text-[10px] font-semibold uppercase tracking-wider z-[210] hidden md:block">
                {{ __('FOTO') }} <span x-text="lightbox.index + 1" class="text-toba-accent text-base font-bold"></span> {{ __('DARI') }} <span x-text="filteredImages.length" class="text-base font-bold opacity-40"></span>
            </div>

            <!-- Navigation Controls -->
            <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-6 md:px-12 pointer-events-none z-[210]">
                <button
                    @click.stop="prev()"
                    class="w-14 h-14 bg-white/5 hover:bg-toba-green backdrop-blur-md rounded-xl flex items-center justify-center text-white transition-all pointer-events-auto border border-white/10 shadow-sm group"
                >
                    <svg class="w-6 h-6 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button
                    @click.stop="next()"
                    class="w-14 h-14 bg-white/5 hover:bg-toba-green backdrop-blur-md rounded-xl flex items-center justify-center text-white transition-all pointer-events-auto border border-white/10 shadow-sm group"
                >
                    <svg class="w-6 h-6 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <!-- Lightbox Image Content -->
            <div class="relative w-full h-full flex flex-col items-center justify-center" @click.stop>
                <div class="relative max-w-5xl w-full h-full flex flex-col items-center justify-center p-6 md:p-14">
                    <img
                        :src="filteredImages[lightbox.index].image_url"
                        class="max-w-full max-h-[70vh] object-contain rounded-3xl shadow-2xl border border-white/10"
                        x-transition:enter="transition ease-out duration-500 delay-75"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                    >
                    <!-- Caption Overlay (Bottom) -->
                    <div class="mt-8 text-center max-w-2xl animate-in fade-in slide-in-from-bottom-8 duration-500">
                        <div class="flex items-center justify-center gap-3 mb-3">
                            <span x-text="filteredImages[lightbox.index].category" class="px-4 py-1.5 bg-toba-green text-white text-[9px] font-semibold uppercase tracking-wider rounded-lg shadow-sm"></span>
                        </div>
                        <h4 class="text-white text-xl md:text-3xl font-light tracking-tight leading-tight" x-text="filteredImages[lightbox.index].caption"></h4>
                        
                        <!-- Dynamic Link for Package/Blog -->
                        <template x-if="filteredImages[lightbox.index].type === 'package' || filteredImages[lightbox.index].type === 'blog'">
                            <div class="mt-6">
                                <a :href="filteredImages[lightbox.index].type === 'package' ? '/tour/package/' + filteredImages[lightbox.index].slug : '/tour/blog/' + filteredImages[lightbox.index].slug" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-900 rounded-xl font-bold text-xs hover:bg-toba-green hover:text-white transition-all shadow-sm">
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
        to { transform: scale(1.05); }
    }
    .animate-subtle-zoom {
        animation: subtle-zoom 20s infinite alternate ease-in-out;
    }
</style>
@endsection
