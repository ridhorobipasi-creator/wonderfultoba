@extends('layouts.app')

@section('title', 'Paket Wisata Sumatera Utara – Wonderful Toba')
@section('description', 'Temukan paket wisata terbaik ke Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang.')

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
                                   p.description.toLowerCase().includes(this.searchQuery.toLowerCase());
                
                // Duration matching
                let matchDur = true;
                if (this.filterDuration !== 'Semua') {
                    const days = parseInt(p.duration);
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
    class="bg-[#f8fafc] min-h-screen pb-24"
>
    <!-- Hero -->
    <div class="relative h-[60vh] flex items-end overflow-hidden">
        <img
            src="{{ count($packages) > 0 && count($packages[0]->images) > 0 ? $packages[0]->images[0] : '/storage/2026/04/lake-toba-premium.png' }}"
            alt="Packages Hero"
            class="absolute inset-0 w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-slate-900/50 to-[#f8fafc]"></div>
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 pb-32 text-white">
            <span class="inline-block px-4 py-1.5 bg-toba-green text-white text-[10px] font-bold uppercase tracking-[0.3em] rounded-full mb-4">
                Eksplorasi Indonesia & Dunia
            </span>
            <h1 class="text-5xl md:text-7xl font-black tracking-tight leading-tight">
                Paket Wisata <span class="text-toba-accent">Pilihan</span><br />Terbaik
            </h1>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20">
        <div class="bg-white/90 backdrop-blur-2xl p-6 md:p-8 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white/60">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative group lg:col-span-2">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-focus-within:bg-toba-green group-focus-within:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <input type="text" placeholder="Cari destinasi atau paket..." x-model="searchQuery"
                        class="w-full pl-16 pr-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 placeholder:text-slate-400">
                </div>
                <!-- City -->
                <div class="relative">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <select x-model="filterCity"
                        class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 appearance-none cursor-pointer">
                        <option value="all">Semua Wilayah</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Sort -->
                <div class="relative">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                    </div>
                    <select x-model="sortBy"
                        class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 appearance-none cursor-pointer">
                        <option value="default">Urutkan</option>
                        <option value="price-asc">Harga Terendah</option>
                        <option value="price-desc">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            <!-- Quick filters row -->
            <div class="flex flex-wrap gap-2 mt-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest self-center mr-1">Durasi:</span>
                @foreach(['Semua', '1-3 Hari', '4-7 Hari', '7+ Hari'] as $d)
                <button @click="filterDuration = '{{ $d }}'"
                    :class="filterDuration === '{{ $d }}' ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                    class="flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $d }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="max-w-7xl mx-auto px-4 mt-14">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 mb-1">Menampilkan Hasil</h2>
                <p class="text-slate-500 font-medium text-sm">
                    Ditemukan <span class="text-toba-green font-black" x-text="filteredPackages.length"></span> paket wisata
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="pkg in filteredPackages" :key="pkg.id">
                <div x-data="{ 
                    locationData: cities.find(c => String(c.id) === String(pkg.cityId)),
                    get displayLocation() {
                        return this.locationData ? this.locationData.name : 'Sumatera Utara';
                    }
                }">
                    <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 group flex flex-col h-full">
                        <div class="relative h-72 overflow-hidden shrink-0">
                            <img :src="pkg.images[0] || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'" :alt="pkg.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            <div class="absolute top-5 left-5 flex flex-col space-y-2">
                                <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
                                    <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <span class="font-black text-slate-800 text-[10px] uppercase tracking-wider">4.8</span>
                                </div>
                                <div class="bg-blue-600 text-white px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg" x-text="pkg.duration"></div>
                            </div>
                        </div>
                        <div class="p-8 flex flex-col flex-grow">
                            <div class="flex items-center text-blue-600 text-[10px] font-black uppercase tracking-[0.2em] mb-3">
                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span x-text="displayLocation"></span>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 mb-4 line-clamp-1 group-hover:text-blue-600 transition-colors tracking-tight" x-text="pkg.name"></h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2 font-medium" x-text="pkg.description"></p>
                            <div class="flex items-center justify-between pt-6 border-t border-slate-50 mt-auto">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Mulai Dari</p>
                                    <div class="flex items-baseline space-x-1">
                                        <span class="text-xs font-bold text-slate-400">IDR</span>
                                        <span class="text-2xl font-black text-slate-900" x-text="new Intl.NumberFormat('id-ID').format(pkg.price)"></span>
                                    </div>
                                </div>
                                <a :href="'/tour/package/' + pkg.slug" class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 transition-all shadow-xl shadow-slate-200 group/btn">
                                    <svg class="w-6 h-6 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- No Results -->
        <div x-show="filteredPackages.length === 0" class="text-center py-28 bg-white rounded-[3rem] border border-slate-100">
            <svg class="w-14 h-14 mx-auto text-slate-200 mb-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
            <h3 class="text-2xl font-black text-slate-900 mb-3">Paket Tidak Ditemukan</h3>
            <p class="text-slate-500 max-w-sm mx-auto mb-8">Coba ubah filter atau kata kunci pencarian Anda.</p>
            <button @click="searchQuery = ''; filterCity = 'all'; filterDuration = 'Semua'; sortBy = 'default'"
                class="px-8 py-3.5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-toba-green transition-all">
                Reset Filter
            </button>
        </div>
    </div>
</div>
@endsection
