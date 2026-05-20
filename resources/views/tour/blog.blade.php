@extends('layouts.app')

@section('title', __('Blog & Inspirasi Wisata – Sujai Laketoba'))
@section('description', __('Tips, panduan, dan cerita menarik untuk rencana liburan Anda ke Sumatera Utara.'))
@section('keywords', 'blog wisata toba, artikel travel sumatera utara, tips liburan danau toba, cerita perjalanan sujai')

@section('content')
<div 
    x-data="{ 
        activeCategory: '{{ __('Semua') }}', 
        searchQuery: '', 
        posts: @js($posts),
        get categories() {
            const cats = ['{{ __('Semua') }}', ...new Set(this.posts.map(p => p.category).filter(Boolean))];
            return cats;
        },
        
        get filteredPosts() {
            return this.posts.filter(p => {
                const matchCat = this.activeCategory === '{{ __('Semua') }}' || p.category === this.activeCategory;
                const searchLower = (this.searchQuery || '').toLowerCase();
                const titleLower = (p.title || '').toLowerCase();
                const contentLower = (p.content || '').toLowerCase();
                const matchSearch = titleLower.includes(searchLower) || contentLower.includes(searchLower);
                return matchCat && matchSearch;
            });
        },
        
        get featured() {
            return this.filteredPosts.length > 0 ? this.filteredPosts[0] : null;
        },
        
        get rest() {
            return this.filteredPosts.slice(1);
        },
        locale: '{{ session('locale', 'my') === 'my' ? 'ms-MY' : (session('locale') === 'en' ? 'en-SG' : 'id-ID') }}'
    }"
    class="bg-slate-50 min-h-screen pb-32"
>
    <!-- Cinematic Hero -->
    <div class="relative h-[55dvh] flex items-center overflow-hidden bg-slate-900">
        @php
            $heroImage = $siteSettings['cms_tour']['hero_image_url'] ?? $siteSettings['cms_landing']['tour_image_url'] ?? 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000';
                if (Str::startsWith($heroImage, ['http', '//', 'data:', 'blob:'])) {
                    foreach (['assets/', 'images/', 'branding/', 'gallery/'] as $prefix) {
                        if (str_contains($heroImage, '/' . $prefix) && !str_contains($heroImage, '/storage/' . $prefix)) {
                            $heroImage = str_replace('/' . $prefix, '/storage/' . $prefix, $heroImage);
                        }
                    }
                } else {
                    $cleanPath = ltrim($heroImage, '/');
                    $matched = false;
                    foreach (['assets/', 'images/', 'branding/', 'gallery/'] as $prefix) {
                        if (Str::startsWith($cleanPath, $prefix)) {
                            $heroImage = asset('storage/' . $cleanPath);
                            $matched = true;
                            break;
                        }
                    }
                    if (!$matched) {
                        $heroImage = asset('storage/' . ltrim(str_replace('storage/', '', $cleanPath), '/'));
                    }
                }
        @endphp
        <img src="{{ $heroImage }}" alt="Blog Hero" class="absolute inset-0 w-full h-full object-cover opacity-60 animate-subtle-zoom">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-transparent"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-8 text-white pt-20">
            <div class="max-w-4xl animate-in fade-in slide-in-from-left-12 duration-1000">
                <div class="flex items-center space-x-2 mb-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-toba-green/25 backdrop-blur-md border border-white/10 text-white text-[10px] font-semibold uppercase tracking-[0.2em] rounded-full">
                        {{ __('Jurnal Perjalanan') }}
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-light tracking-tight leading-tight mb-6 text-white">
                    {{ __('Inspirasi') }} <span class="text-toba-green">{{ __('Eksplorasi') }}</span>
                </h1>
                <p class="text-slate-200 text-sm md:text-base max-w-xl leading-relaxed font-normal">
                    {{ __('Temukan tips, panduan mendalam, dan cerita inspiratif dari setiap sudut Sumatera Utara untuk menemani rencana petualangan Anda.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 -mt-16 relative z-30">
        <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-sm p-6 border border-slate-100 animate-in fade-in zoom-in duration-1000 delay-300">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <!-- Category Filters -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-2">
                    <template x-for="cat in categories" :key="cat">
                        <button
                            @click="activeCategory = cat"
                            :class="activeCategory === cat ? 'bg-toba-green text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200/50'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap"
                            x-text="cat"
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
                        placeholder="{{ __('Cari topik atau artikel...') }}"
                        x-model="searchQuery"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50/50 border border-slate-200/50 rounded-2xl focus:ring-1 focus:ring-toba-green font-medium text-slate-700 placeholder:text-slate-400 text-sm outline-none"
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-8 mt-16">
        
        <!-- Featured Post -->
        <template x-if="featured">
            <article class="group relative bg-slate-900 rounded-3xl overflow-hidden shadow-sm transition-all duration-700 mb-16 min-h-[400px] md:min-h-[500px] flex flex-col justify-end">
                <img :src="featured.image ? (featured.image.startsWith('http') ? (['assets/', 'images/', 'branding/', 'gallery/'].some(p =\u003e featured.image.includes('/' + p) \u0026\u0026 !featured.image.includes('/storage/' + p)) ? ['assets/', 'images/', 'branding/', 'gallery/'].reduce((url, p) =\u003e url.replace('/' + p, '/storage/' + p), featured.image) : featured.image) : (['assets/', 'images/', 'branding/', 'gallery/'].some(p =\u003e featured.image.startsWith(p)) ? '/storage/' + featured.image.replace(/^\//, '') : '/storage/' + featured.image.replace(/^\/*storage\//, '').replace(/^\//, ''))) : 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1200'" :alt="featured.title"
                    class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-[2s] ease-out">
                
                <!-- Overlays -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/60 to-transparent"></div>

                <div class="relative z-10 p-8 md:p-14 max-w-3xl animate-in fade-in slide-in-from-bottom-12 duration-1000">
                    <div class="flex items-center gap-4 text-xs font-semibold uppercase tracking-wider text-white mb-5">
                        <span class="px-3 py-1 bg-toba-green text-white rounded-lg text-[9px] uppercase tracking-wider" x-text="featured.category"></span>
                        <span class="text-white/60 text-[10px]" x-text="new Date(featured.createdAt).toLocaleDateString(locale, { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-light text-white mb-5 group-hover:text-toba-green transition-colors leading-tight tracking-tight" x-text="featured.title"></h2>
                    <p class="text-slate-300 text-sm md:text-base font-normal mb-8 line-clamp-2 leading-relaxed opacity-90" x-text="featured.excerpt || featured.content"></p>
                    
                    <a :href="'/tour/blog/' + (featured.slug || featured.id)" class="inline-flex items-center gap-3 bg-white text-slate-900 px-6 py-3.5 rounded-xl font-bold text-xs hover:bg-toba-green hover:text-white transition-all duration-300 shadow-sm">
                        {{ __('Baca Cerita Lengkap') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
        </template>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="(post, i) in rest" :key="post.id">
                <article class="group flex flex-col h-full bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 hover:border-slate-200 transition-all duration-300 animate-in fade-in slide-in-from-bottom-8 duration-1000" :style="'animation-delay: ' + (i * 100) + 'ms'">
                    <a :href="'/tour/blog/' + (post.slug || post.id)" class="block relative overflow-hidden h-60">
                        <img :src="post.image ? (post.image.startsWith('http') ? (['assets/', 'images/', 'branding/', 'gallery/'].some(p =\u003e post.image.includes('/' + p) \u0026\u0026 !post.image.includes('/storage/' + p)) ? ['assets/', 'images/', 'branding/', 'gallery/'].reduce((url, p) =\u003e url.replace('/' + p, '/storage/' + p), post.image) : post.image) : (['assets/', 'images/', 'branding/', 'gallery/'].some(p =\u003e post.image.startsWith(p)) ? '/storage/' + post.image.replace(/^\//, '') : '/storage/' + post.image.replace(/^\/*storage\//, '').replace(/^\//, ''))) : 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'" :alt="post.title"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[1.5s] ease-out">
                        <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/95 backdrop-blur-md text-slate-900 px-3 py-1 rounded-lg text-[9px] font-semibold uppercase tracking-wider shadow-sm" x-text="post.category"></span>
                        </div>
                    </a>
                    
                    <div class="flex-1 flex flex-col p-6">
                        <div class="flex items-center gap-3 text-toba-green text-[9px] font-semibold uppercase tracking-wider mb-2">
                            <span x-text="new Date(post.createdAt).toLocaleDateString(locale, { day: 'numeric', month: 'short', year: 'numeric' })"></span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900 mb-3 group-hover:text-toba-green transition-colors leading-tight line-clamp-2 tracking-tight" x-text="post.title"></h2>
                        <p class="text-slate-500 text-xs leading-relaxed mb-6 line-clamp-2 font-normal flex-grow" x-text="post.excerpt || post.content"></p>
                        
                        <div class="pt-5 border-t border-slate-100">
                            <a :href="'/tour/blog/' + (post.slug || post.id)" class="inline-flex items-center gap-2 text-slate-900 font-bold text-xs group/link hover:text-toba-green transition-colors">
                                {{ __('Baca Selengkapnya') }}
                                <svg class="w-3.5 h-3.5 text-toba-green group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredPosts.length === 0" class="text-center py-24 bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <h3 class="text-2xl font-light text-slate-900 mb-3 tracking-tight">{{ __('Artikel Belum Tersedia') }}</h3>
            <p class="text-slate-500 text-xs font-normal max-w-xs mx-auto mb-8 leading-relaxed">{{ __('Kami sedang menyusun cerita perjalanan menarik untuk Anda. Silakan coba kategori lain atau reset pencarian.') }}</p>
            <button @click="activeCategory = '{{ __('Semua') }}'; searchQuery = ''"
                class="bg-slate-950 text-white px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-toba-green transition-colors duration-300">
                {{ __('Reset Jurnal') }}
            </button>
        </div>
    </div>
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
