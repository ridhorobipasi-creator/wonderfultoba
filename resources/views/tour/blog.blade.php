@extends('layouts.app')

@section('title', 'Blog & Inspirasi Wisata – Wonderful Toba')
@section('description', 'Tips, panduan, dan cerita menarik untuk rencana liburan Anda ke Sumatera Utara.')

@section('content')
<div 
    x-data="{ 
        activeCategory: 'Semua', 
        searchQuery: '', 
        posts: {{ json_encode($posts) }},
        categories: ['Semua', 'Destinasi', 'Panduan', 'Petualangan', 'Kuliner'],
        
        get filteredPosts() {
            return this.posts.filter(p => {
                const matchCat = this.activeCategory === 'Semua' || p.category === this.activeCategory;
                const matchSearch = p.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                   p.content.toLowerCase().includes(this.searchQuery.toLowerCase());
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
    class="bg-slate-50 min-h-screen pb-24"
>
    <!-- Hero Header -->
    <div class="relative overflow-hidden bg-slate-900 pt-32 pb-20 px-4">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 to-slate-900/90"></div>
        <div class="relative z-10 max-w-7xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-toba-green/20 text-toba-accent text-xs font-bold uppercase tracking-[0.3em] rounded-full mb-5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Blog & Inspirasi
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-5 tracking-tight">
                Inspirasi <span class="text-toba-accent">Perjalanan</span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto font-medium leading-relaxed mb-8">
                Tips, panduan, dan cerita menarik untuk rencana liburan Anda ke Sumatera Utara.
            </p>
            <!-- Search -->
            <div class="max-w-lg mx-auto relative">
                <svg class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    placeholder="Cari artikel..."
                    x-model="searchQuery"
                    class="w-full pl-14 pr-6 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-toba-green/50 font-medium"
                >
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="sticky top-[72px] z-30 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex gap-2 overflow-x-auto scrollbar-hide">
            <template x-for="cat in categories" :key="cat">
                <button
                    @click="activeCategory = cat"
                    :class="activeCategory === cat ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                    class="shrink-0 px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all"
                    x-text="cat"
                ></button>
            </template>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 mt-12">
        <div x-show="filteredPosts.length === 0" class="text-center py-24 bg-white rounded-[2rem] border border-slate-100">
            <svg class="w-12 h-12 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <h3 class="text-xl font-bold text-slate-700 mb-2">Artikel tidak ditemukan</h3>
            <p class="text-slate-400">Coba kata kunci atau kategori lain.</p>
        </div>

        <template x-if="featured">
            <article class="group bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl transition-all duration-500 mb-10 md:flex">
                <div class="relative md:w-1/2 h-72 md:h-auto overflow-hidden">
                    <img :src="featured.image || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'" :alt="featured.title"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-5 left-5">
                        <span class="bg-toba-green text-white px-4 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg" x-text="featured.category"></span>
                    </div>
                </div>
                <div class="md:w-1/2 p-10 flex flex-col justify-center">
                    <div class="flex items-center gap-5 text-slate-400 text-sm mb-5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-toba-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <span x-text="new Date(featured.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                        </span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 mb-4 group-hover:text-toba-green transition-colors leading-tight" x-text="featured.title"></h2>
                    <p class="text-slate-500 leading-relaxed mb-6 line-clamp-3 font-medium" x-text="featured.content"></p>
                    <div class="flex items-center justify-between">
                        <a :href="'/tour/blog/' + (featured.slug || featured.id)" class="flex items-center gap-2 text-toba-green font-black text-sm hover:gap-3 transition-all">
                            Baca <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </div>
            </article>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="post in rest" :key="post.id">
                <article class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-500 group">
                    <div class="relative h-52 overflow-hidden">
                        <img :src="post.image || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'" :alt="post.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-4 left-4">
                            <span class="bg-toba-green text-white px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider" x-text="post.category"></span>
                        </div>
                    </div>
                    <div class="p-7">
                        <div class="flex items-center gap-4 text-slate-400 text-xs mb-4">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-toba-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span x-text="new Date(post.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })"></span>
                            </span>
                        </div>
                        <h2 class="text-lg font-black text-slate-900 mb-3 group-hover:text-toba-green transition-colors leading-tight line-clamp-2" x-text="post.title"></h2>
                        <p class="text-slate-500 text-sm leading-relaxed mb-5 line-clamp-2" x-text="post.content"></p>
                        <div class="flex items-center justify-end pt-4 border-t border-slate-50">
                            <a :href="'/tour/blog/' + (post.slug || post.id)" class="flex items-center gap-1.5 text-toba-green font-black text-xs hover:gap-2.5 transition-all">
                                Baca <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            </template>
        </div>
    </div>
</div>
@endsection
