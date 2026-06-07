@extends('layouts.app')

@section('title', 'Paket Outbound & Gathering – Wonderful Toba')
@section('description', 'Pilihan paket outbound, team building, dan gathering terbaik di Sumatera Utara.')

@section('content')
<div 
    x-data="{ 
        activeCity: 'Semua', 
        searchQuery: '', 
        sortBy: 'default',
        packages: {{ json_encode($packages) }},
        cities: {{ json_encode($cities) }},
        
        get filteredPackages() {
            let result = this.packages.filter(p => {
                const matchCity = this.activeCity === 'Semua' || p.cityId == this.activeCity;
                const matchSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                   p.description.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchCity && matchSearch;
            });

            if (this.sortBy === 'price-asc') {
                result.sort((a, b) => a.price - b.price);
            } else if (this.sortBy === 'price-desc') {
                result.sort((a, b) => b.price - a.price);
            }
            return result;
        }
    }"
    class="bg-slate-50 min-h-screen pb-24 pt-32"
>
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="text-center mb-16 md:mb-20">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-500/10 backdrop-blur-md border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-6 animate-fade-in-down">
                Corporate & Community
            </span>
            <h1 class="text-5xl md:text-8xl font-black text-slate-900 mb-8 tracking-tighter leading-[0.9] animate-fade-in-up">
                Paket <span class="text-gradient">Outbound.</span>
            </h1>
            <p class="text-slate-500 text-lg md:text-xl max-w-2xl mx-auto font-medium animate-fade-in-up delay-200">
                Solusi kegiatan luar ruang profesional untuk instansi, perusahaan, dan komunitas Anda.
            </p>
        </div>

        <!-- Filters -->
        <div class="glass-card p-8 md:p-10 rounded-3xl shadow-2xl border-white/40 mb-16 sticky top-[80px] z-30 animate-fade-in-up delay-300">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Search -->
                <div class="relative group lg:col-span-2">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-focus-within:bg-toba-green group-focus-within:text-white transition-all duration-500">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input type="text" placeholder="Cari paket outbound..." x-model="searchQuery"
                        class="w-full pl-20 pr-6 py-5 bg-slate-50/50 border-none rounded-[1.5rem] focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 placeholder:text-slate-400 text-sm">
                </div>
                <!-- City -->
                <div class="relative group">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-toba-green transition-colors">
                        <i class="fas fa-location-dot text-xs"></i>
                    </div>
                    <select x-model="activeCity"
                        class="w-full pl-14 pr-6 py-5 bg-slate-50/50 border-none rounded-[1.5rem] focus:ring-2 focus:ring-toba-green/20 font-black uppercase tracking-widest text-[10px] text-slate-700 appearance-none cursor-pointer">
                        <option value="Semua">Semua Lokasi</option>
                        <template x-for="city in cities" :key="city.id">
                            <option :value="city.id" x-text="city.name"></option>
                        </template>
                    </select>
                </div>
                <!-- Sort -->
                <div class="relative group">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-toba-green transition-colors">
                        <i class="fas fa-sort-amount-down text-xs"></i>
                    </div>
                    <select x-model="sortBy"
                        class="w-full pl-14 pr-6 py-5 bg-slate-50/50 border-none rounded-[1.5rem] focus:ring-2 focus:ring-toba-green/20 font-black uppercase tracking-widest text-[10px] text-slate-700 appearance-none cursor-pointer">
                        <option value="default">Urutan Default</option>
                        <option value="price-asc">Harga Terendah</option>
                        <option value="price-desc">Harga Tertinggi</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <template x-for="pkg in filteredPackages" :key="pkg.id">
                <div class="card-premium h-full group flex flex-col overflow-hidden">
                    <div class="relative h-72 overflow-hidden shrink-0">
                        <img :src="pkg.first_image" :alt="pkg.name" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s] ease-out">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        <div class="absolute top-5 left-5">
                            <span class="bg-white/95 backdrop-blur-md text-slate-900 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl border border-white/20 flex items-center gap-2">
                                <i class="fas fa-location-dot text-toba-green text-[9px]"></i>
                                <span x-text="cities.find(c => c.id == pkg.cityId)?.name || 'Sumatera Utara'"></span>
                            </span>
                        </div>
                    </div>
                    <div class="p-8 flex flex-col flex-grow">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="flex items-center gap-2 text-[10px] font-black text-toba-green uppercase tracking-[0.2em]">
                                <i class="far fa-clock"></i>
                                <span x-text="pkg.duration || '-'"></span>
                            </span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-4 line-clamp-1 group-hover:text-toba-green transition-colors tracking-tighter" x-text="pkg.name"></h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-10 line-clamp-2 font-medium" x-text="pkg.description || '-'"></p>
                        
                        <div class="mt-auto pt-8 border-t border-slate-50 flex items-center justify-between">
                            <div>
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1">Mulai Dari</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xs font-black text-slate-900">Rp</span>
                                    <span class="text-2xl font-black text-slate-900 tracking-tighter" x-text="pkg.price ? new Intl.NumberFormat('id-ID').format(pkg.price) : '-'"></span>
                                </div>
                            </div>
                            <a :href="'/tour/package/' + (pkg.slug || pkg.id)" class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-toba-green hover:scale-110 transition-all duration-300 shadow-xl shadow-slate-200 group/btn">
                                <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredPackages.length === 0" class="text-center py-40 glass-card rounded-3xl animate-fade-in-up">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-200">
                <i class="fas fa-search-minus text-4xl"></i>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Paket tidak ditemukan</h3>
            <p class="text-slate-500 font-medium max-w-sm mx-auto mb-12">Coba gunakan kata kunci atau lokasi yang berbeda untuk menemukan paket yang sesuai.</p>
        </div>

        <!-- Tiers Section -->
        @if(isset($tiers) && count($tiers) > 0)
        <div class="mt-32 pb-16">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-toba-green/10 text-toba-green text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-6">
                    Kategori Layanan
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-8 tracking-tighter">
                    Pilihan <span class="text-gradient">Tingkat Layanan</span>
                </h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto font-medium leading-relaxed">
                    Sesuaikan paket outbound Anda dengan kebutuhan dan anggaran perusahaan untuk hasil yang maksimal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($tiers as $tier)
                <div class="card-premium p-10 group flex flex-col h-full">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-900 flex items-center justify-center mb-8 group-hover:bg-toba-green group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-layer-group text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">{{ $tier->name }}</h3>
                    <p class="text-slate-500 font-medium leading-relaxed mb-10 flex-1">
                        {{ $tier->description }}
                    </p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['whatsapp'] ?? '6281323888207') }}?text=Halo,%20saya%20tertarik%20dengan%20layanan%20outbound%20tier%20{{ $tier->name }}" class="btn-premium w-full py-4 rounded-2xl bg-slate-900 text-white font-black text-[10px] uppercase tracking-widest text-center">
                        Konsultasi Tier Ini
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    </div>
</div>
@endsection
