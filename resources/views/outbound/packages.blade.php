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
        <div class="text-center mb-12 md:mb-16">
            <span class="inline-block px-4 py-1.5 bg-toba-green/20 text-toba-green text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-5">
                Corporate & Community
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                Paket <span class="text-toba-green">Outbound</span>
            </h1>
            <p class="text-slate-500 text-base md:text-lg max-w-2xl mx-auto font-medium">
                Solusi kegiatan luar ruang profesional untuk instansi, perusahaan, dan komunitas Anda.
            </p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 mb-12 sticky top-[80px] z-30">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Search -->
                <div class="flex-1 relative">
                    <svg class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input
                        type="text"
                        placeholder="Cari paket outbound..."
                        x-model="searchQuery"
                        class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-toba-green/50 font-medium"
                    >
                </div>

                <!-- City -->
                <div class="lg:w-64">
                    <select 
                        x-model="activeCity"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-slate-900 font-bold appearance-none cursor-pointer focus:ring-2 focus:ring-toba-green/50"
                    >
                        <option value="Semua">Semua Lokasi</option>
                        <template x-for="city in cities" :key="city.id">
                            <option :value="city.id" x-text="city.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Sort -->
                <div class="lg:w-64">
                    <select 
                        x-model="sortBy"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-slate-900 font-bold appearance-none cursor-pointer focus:ring-2 focus:ring-toba-green/50"
                    >
                        <option value="default">Urutkan</option>
                        <option value="price-asc">Harga Terendah</option>
                        <option value="price-desc">Harga Tertinggi</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="pkg in filteredPackages" :key="pkg.id">
                <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl transition-all duration-500 group flex flex-col h-full">
                    <div class="relative h-64 overflow-hidden">
                        <img :src="pkg.images[0]" :alt="pkg.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-5 left-5">
                            <span class="bg-white/90 backdrop-blur-md text-slate-900 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-sm flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-toba-green" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span x-text="cities.find(c => c.id == pkg.cityId)?.name || 'Sumatera Utara'"></span>
                            </span>
                        </div>
                    </div>
                    <div class="p-6 md:p-8 flex-1 flex flex-col">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400">
                                <svg class="w-3.5 h-3.5 text-toba-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span x-text="pkg.duration"></span>
                            </span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-toba-green transition-colors leading-tight" x-text="pkg.name"></h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-3 font-medium" x-text="pkg.description"></p>
                        
                        <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Mulai Dari</p>
                                <p class="text-xl font-black text-slate-900">
                                    <span class="text-xs font-bold text-slate-400 mr-1">Rp</span>
                                    <span x-text="new Intl.NumberFormat('id-ID').format(pkg.price)"></span>
                                </p>
                            </div>
                            <a :href="'/tour/package/' + pkg.slug" class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-toba-green transition-all shadow-lg shadow-slate-900/10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredPackages.length === 0" class="text-center py-24 bg-white rounded-[3rem] border border-slate-100">
            <svg class="w-16 h-16 mx-auto text-slate-200 mb-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Paket tidak ditemukan</h3>
            <p class="text-slate-500 font-medium">Coba gunakan kata kunci atau lokasi yang berbeda.</p>
        </div>

        <!-- Tiers Section -->
        @if(isset($tiers) && count($tiers) > 0)
        <div class="mt-32">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-emerald-50 text-toba-green text-xs font-black uppercase tracking-[0.3em] rounded-full mb-5">
                    Kategori Layanan
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                    Pilihan <span class="text-toba-green">Tingkat Layanan</span>
                </h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto font-medium">
                    Sesuaikan paket outbound Anda dengan kebutuhan dan anggaran perusahaan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($tiers as $tier)
                <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-slate-50 rounded-full group-hover:bg-toba-green/10 transition-colors"></div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 relative z-10">{{ $tier->name }}</h3>
                    <p class="text-slate-500 font-medium leading-relaxed mb-8 relative z-10">
                        {{ $tier->description }}
                    </p>
                    <a href="https://wa.me/6281323888207?text=Halo,%20saya%20tertarik%20dengan%20layanan%20outbound%20tier%20{{ $tier->name }}" class="inline-flex items-center justify-center w-full py-4 rounded-2xl bg-slate-50 text-slate-900 font-black text-[10px] uppercase tracking-widest hover:bg-toba-green hover:text-white transition-colors">
                        Konsultasi Tier Ini
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
