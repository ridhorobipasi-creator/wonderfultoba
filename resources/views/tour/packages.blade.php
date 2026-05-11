@extends('layouts.app')

@section('title', 'Paket Wisata Sumatera Utara – Wonderful Toba')
@section('description', 'Temukan paket wisata terbaik ke Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang. Harga terjangkau, pelayanan premium.')

@section('content')
<div 
    x-data="{ 
        searchQuery: '', 
        filterCity: 'all', 
        filterDuration: 'Semua', 
        sortBy: 'default',
        packages: {{ json_encode($packages) }},
        cities: {{ json_encode($cities) }},
        
        get filteredPackages() {
            let filtered = this.packages.filter(p => {
                const matchCity = this.filterCity === 'all' || String(p.cityId) === this.filterCity;
                const matchSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                   (p.description && p.description.toLowerCase().includes(this.searchQuery.toLowerCase()));
                
                // Duration matching
                let matchDur = true;
                if (this.filterDuration !== 'Semua') {
                    const daysMatch = p.duration ? p.duration.match(/\d+/) : null;
                    const days = daysMatch ? parseInt(daysMatch[0]) : 0;
                    if (this.filterDuration === '1-3 Hari') matchDur = days >= 1 && days <= 3;
                    else if (this.filterDuration === '4-7 Hari') matchDur = days >= 4 && days <= 7;
                    else if (this.filterDuration === '7+ Hari') matchDur = days > 7;
                }
                
                return matchCity && matchSearch && matchDur;
            });
            
            if (this.sortBy === 'price-asc') filtered.sort((a, b) => a.price - b.price);
            else if (this.sortBy === 'price-desc') filtered.sort((a, b) => b.price - a.price);
            
            return filtered;
        }
    }"
    class="min-h-screen flex flex-col bg-slate-50"
>
    <main class="flex-grow">
        <!-- Hero Section -->
        <div class="relative h-[65vh] flex items-end overflow-hidden">
            @php
                $heroImg = (count($packages) > 0 && count($packages[0]->packageImages) > 0) 
                    ? imageUrl($packages[0]->packageImages[0]->image_path) 
                    : 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=2000';
            @endphp
            <img src="{{ $heroImg }}" alt="Packages Hero" class="absolute inset-0 w-full h-full object-cover" fetchpriority="high" decoding="async">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-slate-900/50 to-slate-50"></div>
            <div class="relative z-10 w-full max-w-7xl mx-auto px-4 pb-32">
                <div class="animate-in fade-in slide-in-from-bottom-8 duration-1000">
                    <span class="inline-block px-4 py-1.5 bg-toba-green text-white text-[10px] font-bold uppercase tracking-[0.3em] rounded-full mb-4">Eksplorasi Indonesia & Dunia</span>
                    <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight leading-tight">
                        Paket Wisata <span class="text-toba-accent">Pilihan</span><br>Terbaik
                    </h1>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20">
            <div class="bg-white/90 backdrop-blur-2xl p-6 md:p-8 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white/60 animate-in fade-in zoom-in duration-1000 delay-300">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Box -->
                    <div class="relative group lg:col-span-2">
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-focus-within:bg-toba-green group-focus-within:text-white transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                        </div>
                        <input type="text" placeholder="Cari destinasi atau paket..." x-model="searchQuery"
                            class="w-full pl-16 pr-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 placeholder:text-slate-400">
                    </div>
                    <!-- City Filter -->
                    <div class="relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-toba-green pointer-events-none"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <select x-model="filterCity"
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 appearance-none cursor-pointer">
                            <option value="all">Semua Wilayah</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Sort Filter -->
                    <div class="relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sliders-horizontal absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-toba-green pointer-events-none"><path d="m21 16-4 4-4-4"></path><path d="M17 20V4"></path><path d="m3 8 4-4 4 4"></path><path d="M7 4v16"></path></svg>
                        <select x-model="sortBy"
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 appearance-none cursor-pointer">
                            <option value="default">Urutkan</option>
                            <option value="price-asc">Harga Terendah</option>
                            <option value="price-desc">Harga Tertinggi</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest self-center mr-1">Durasi:</span>
                    @foreach(['Semua', '1-3 Hari', '4-7 Hari', '7+ Hari'] as $d)
                    <button @click="filterDuration = '{{ $d }}'"
                        :class="filterDuration === '{{ $d }}' ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                        {{ $d }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="max-w-7xl mx-auto px-4 mt-14">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-1">Menampilkan Hasil</h2>
                    <p class="text-slate-500 font-medium text-sm">Ditemukan <span class="text-toba-green font-black" x-text="filteredPackages.length"></span> paket wisata</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="(pkg, i) in filteredPackages" :key="pkg.id">
                    <div class="animate-in fade-in slide-in-from-bottom-12 duration-1000" :style="'animation-delay: ' + (i * 100) + 'ms'">
                        <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 group h-full flex flex-col">
                            <div class="relative h-72 overflow-hidden shrink-0">
                                <img :src="pkg.first_image" :alt="pkg.name" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                     loading="lazy" decoding="async">
                                
                                <div class="absolute top-5 left-5 flex flex-col space-y-2">
                                    <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star text-amber-400 fill-amber-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                                        <span class="font-black text-slate-800 text-[10px] uppercase tracking-wider">4.8</span>
                                    </div>
                                    <div class="bg-blue-600 text-white px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg" x-text="pkg.duration"></div>
                                </div>

                                <button class="absolute top-5 right-5 w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-rose-500 transition-all shadow-lg" aria-label="Simpan ke wishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                                </button>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </div>

                            <div class="p-8 flex flex-col flex-grow">
                                <div class="flex items-center text-blue-600 text-[10px] font-black uppercase tracking-[0.2em] mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mr-2"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <span x-text="cities.find(c => String(c.id) === String(pkg.cityId))?.name || 'Sumatera Utara'"></span>
                                </div>
                                <h3 class="text-2xl font-black text-slate-900 mb-4 line-clamp-1 group-hover:text-blue-600 transition-colors tracking-tight" x-text="pkg.name"></h3>
                                <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2 font-medium flex-grow" x-text="pkg.description"></p>
                                
                                <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Mulai Dari</p>
                                        <div class="flex items-baseline space-x-1">
                                            <span class="text-xs font-bold text-slate-400">IDR</span>
                                            <span class="text-2xl font-black text-slate-900" x-text="pkg.price ? new Intl.NumberFormat('id-ID').format(pkg.price) : '-'"></span>
                                        </div>
                                    </div>
                                    <a :href="'/tour/package/' + (pkg.slug || pkg.id)" class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-toba-green transition-all shadow-xl shadow-slate-200 group/btn" aria-label="Lihat detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right group-hover/btn:translate-x-1 transition-transform"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Premium Empty State -->
            <div x-show="filteredPackages.length === 0" class="text-center py-40 bg-slate-50 rounded-[4rem] border-2 border-dashed border-slate-200 animate-in fade-in zoom-in duration-700">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-slate-200 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-x"><path d="m16 16 5 5"></path><circle cx="10" cy="10" r="7"></circle><path d="m7 7 6 6"></path><path d="m13 7-6 6"></path></svg>
                </div>
                <h3 class="text-4xl font-black text-slate-900 mb-6 tracking-tight">Destinasi Belum Ditemukan</h3>
                <p class="text-slate-500 font-medium max-w-md mx-auto mb-12 leading-relaxed">Kami tidak menemukan paket yang sesuai dengan kriteria Anda. Cobalah untuk mereset filter atau mencari dengan kata kunci lain.</p>
                <button @click="searchQuery = ''; filterCity = 'all'; filterDuration = 'Semua'; sortBy = 'default'"
                    class="bg-slate-900 text-white px-12 py-6 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-toba-green transition-all duration-500">
                    Reset Semua Filter
                </button>
            </div>
        </div>

        <!-- Custom CTA Section -->
        <div class="max-w-7xl mx-auto px-4 mt-24 mb-24">
            <div class="bg-gradient-to-r from-toba-green to-emerald-600 rounded-[2.5rem] p-10 md:p-14 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=1200" alt="" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl md:text-4xl font-black text-white mb-4">Tidak Menemukan Paket yang Cocok?</h3>
                    <p class="text-white/80 font-medium mb-8 max-w-xl mx-auto">Kami siap merancang itinerary khusus sesuai kebutuhan dan budget Anda.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="tel:+{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['whatsapp'] ?? '6281323888207') }}" class="flex items-center justify-center gap-2 bg-white text-toba-green px-8 py-4 rounded-2xl font-black hover:bg-slate-50 transition-all shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Konsultasi Gratis
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['whatsapp'] ?? '6281323888207') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 bg-white/20 text-white border border-white/30 px-8 py-4 rounded-2xl font-black hover:bg-white/30 transition-all">
                            WhatsApp Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection

