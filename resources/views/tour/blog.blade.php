@extends('layouts.app')

@section('title', 'Blog & Inspirasi Wisata – Wonderful Toba')
@section('description', 'Tips, panduan, dan cerita menarik untuk rencana liburan Anda ke Sumatera Utara.')

@section('content')
<div 
    x-data="{ 
        activeCategory: 'Semua', 
        searchQuery: '', 
        posts: {{ json_encode($posts) }},
        get categories() {
            const cats = ['Semua', ...new Set(this.posts.map(p => p.category).filter(Boolean))];
            return cats;
        },
        
        get filteredPosts() {
            return this.posts.filter(p => {
                const matchCat = this.activeCategory === 'Semua' || p.category === this.activeCategory;
                const matchSearch = p.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                   (p.content && p.content.toLowerCase().includes(this.searchQuery.toLowerCase()));
                return matchCat && matchSearch;
            });
        },
        
        get featured() {
            return this.filteredPosts.length > 0 ? this.filteredPosts[0] : null;
        },
        
        get rest() {
            return this.filteredPosts.slice(1);
        }
    }"
    class="bg-white min-h-screen pb-32"
>
    <!-- Cinematic Hero -->
    <div class="relative h-[60dvh] flex items-center overflow-hidden bg-slate-900">
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
        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-8 text-white pt-20">
            <div class="max-w-4xl animate-in fade-in slide-in-from-left-12 duration-1000">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                    <span class="text-toba-accent font-black text-xs uppercase tracking-[0.4em]">Jurnal Perjalanan</span>
                </div>
                <h1 class="text-5xl md:text-8xl font-black tracking-tighter leading-[0.9] mb-10">
                    Inspirasi <br />
                    <span class="text-toba-green">Eksplorasi</span>
                </h1>
                <p class="text-slate-300 text-lg md:text-2xl font-medium max-w-2xl leading-relaxed">
                    Temukan tips, panduan mendalam, dan cerita inspiratif dari setiap sudut Sumatera Utara untuk menemani rencana petualangan Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Search (Sticky Premium) -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 -mt-20 relative z-30">
        <div class="bg-white rounded-[3.5rem] shadow-[0_50px_100px_-20px_rgba(15,23,42,0.1)] p-8 md:p-12 border border-slate-100 animate-in fade-in zoom-in duration-1000 delay-300">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
                <!-- Category Filters -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                    <template x-for="cat in categories" :key="cat">
                        <button
                            @click="activeCategory = cat"
                            :class="activeCategory === cat ? 'bg-slate-900 text-white shadow-2xl scale-105' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-100'"
                            class="px-8 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-500 whitespace-nowrap"
                            x-text="cat"
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
                        placeholder="Cari topik atau artikel..."
                        x-model="searchQuery"
                        class="w-full pl-16 pr-8 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-toba-green/10 font-bold text-slate-900 placeholder:text-slate-400 text-sm transition-all"
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-8 mt-24">
        
        <!-- Featured Post (Cinema Style) -->
        <template x-if="featured">
            <article class="group relative bg-slate-900 rounded-[4rem] overflow-hidden shadow-2xl transition-all duration-700 mb-24 min-h-[500px] md:min-h-[600px] flex flex-col justify-end">
                <img :src="featured.image ? (featured.image.startsWith('http') ? (['assets/', 'images/', 'branding/', 'gallery/'].some(p =\u003e featured.image.includes('/' + p) \u0026\u0026 !featured.image.includes('/storage/' + p)) ? ['assets/', 'images/', 'branding/', 'gallery/'].reduce((url, p) =\u003e url.replace('/' + p, '/storage/' + p), featured.image) : featured.image) : (['assets/', 'images/', 'branding/', 'gallery/'].some(p =\u003e featured.image.startsWith(p)) ? '/storage/' + featured.image.replace(/^\//, '') : '/storage/' + featured.image.replace(/^\/*storage\//, '').replace(/^\//, ''))) : 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1200'" :alt="featured.title"
                    class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-[3s] ease-out">
                
                <!-- Overlays -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 to-transparent"></div>

                <div class="relative z-10 p-10 md:p-20 max-w-4xl animate-in fade-in slide-in-from-bottom-12 duration-1000">
                    <div class="flex items-center gap-6 text-toba-accent text-xs font-black uppercase tracking-[0.4em] mb-8">
                        <span class="px-4 py-1.5 bg-toba-green text-white rounded-full" x-text="featured.category"></span>
                        <span class="text-white/60" x-text="new Date(featured.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                    </div>
                    <h2 class="text-4xl md:text-7xl font-black text-white mb-8 group-hover:text-toba-green transition-colors leading-[1.05] tracking-tighter" x-text="featured.title"></h2>
                    <p class="text-slate-300 text-lg md:text-xl font-medium mb-12 line-clamp-2 leading-relaxed opacity-80" x-text="featured.excerpt || featured.content"></p>
                    
                    <a :href="'/tour/blog/' + (featured.slug || featured.id)" class="inline-flex items-center gap-4 bg-white text-slate-900 px-10 py-5 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-toba-green hover:text-white transition-all duration-500 shadow-2xl group/btn">
                        Baca Cerita Lengkap
                        <svg class="w-5 h-5 group-hover/btn:translate-x-2 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
        </template>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
            <template x-for="(post, i) in rest" :key="post.id">
                <article class="group flex flex-col h-full animate-in fade-in slide-in-from-bottom-8 duration-1000" :style="'animation-delay: ' + (i * 100) + 'ms'">
                    <a :href="'/tour/blog/' + (post.slug || post.id)" class="block mb-8 relative overflow-hidden rounded-[2.5rem] shadow-xl h-72">
                        <img :src="post.image ? (post.image.startsWith('http') ? (['assets/', 'images/', 'branding/', 'gallery/'].some(p =\u003e post.image.includes('/' + p) \u0026\u0026 !post.image.includes('/storage/' + p)) ? ['assets/', 'images/', 'branding/', 'gallery/'].reduce((url, p) =\u003e url.replace('/' + p, '/storage/' + p), post.image) : post.image) : (['assets/', 'images/', 'branding/', 'gallery/'].some(p =\u003e post.image.startsWith(p)) ? '/storage/' + post.image.replace(/^\//, '') : '/storage/' + post.image.replace(/^\/*storage\//, '').replace(/^\//, ''))) : 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'" :alt="post.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s] ease-out">
                        <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                        <div class="absolute top-6 left-6">
                            <span class="bg-white/95 backdrop-blur-md text-slate-900 px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg" x-text="post.category"></span>
                        </div>
                    </a>
                    
                    <div class="flex-1 flex flex-col px-2">
                        <div class="flex items-center gap-3 text-toba-green text-[10px] font-black uppercase tracking-[0.3em] mb-4">
                            <span x-text="new Date(post.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })"></span>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 mb-4 group-hover:text-toba-green transition-colors leading-tight line-clamp-2 tracking-tight" x-text="post.title"></h2>
                        <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-3 font-medium flex-1 opacity-80" x-text="post.excerpt || post.content"></p>
                        
                        <div class="pt-8 border-t border-slate-100">
                            <a :href="'/tour/blog/' + (post.slug || post.id)" class="flex items-center gap-3 text-slate-900 font-black text-[10px] uppercase tracking-widest group/link">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 text-toba-green group-hover/link:translate-x-2 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredPosts.length === 0" class="text-center py-40 bg-slate-50 rounded-[4rem] border-2 border-dashed border-slate-200">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-slate-200 text-slate-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <h3 class="text-4xl font-black text-slate-900 mb-6 tracking-tight">Artikel Belum Tersedia</h3>
            <p class="text-slate-500 font-medium max-w-md mx-auto mb-12 leading-relaxed">Kami sedang menyusun cerita perjalanan menarik untuk Anda. Silakan coba kategori lain atau reset pencarian.</p>
            <button @click="activeCategory = 'Semua'; searchQuery = ''"
                class="bg-slate-900 text-white px-12 py-6 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-toba-green transition-all duration-500">
                Reset Jurnal
            </button>
        </div>
    </div>
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
