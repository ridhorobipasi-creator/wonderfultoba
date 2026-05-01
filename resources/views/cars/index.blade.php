@extends('layouts.app')

@section('title', 'Sewa Mobil Danau Toba & Medan – Wonderful Toba')
@section('description', 'Layanan sewa mobil profesional di Sumatera Utara. Unit terbaru, driver berpengalaman, dan harga kompetitif.')

@section('content')
<div 
    x-data="{ 
        cars: {{ json_encode($cars) }},
        searchQuery: '',
        activeType: 'Semua',
        
        get filteredCars() {
            return this.cars.filter(c => {
                const matchType = this.activeType === 'Semua' || c.type === this.activeType;
                const matchSearch = c.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchType && matchSearch;
            });
        },

        get carTypes() {
            return ['Semua', ...new Set(this.cars.map(c => c.type))];
        }
    }"
    class="bg-slate-50 min-h-screen pb-24"
>
    <!-- Hero Header -->
    <div class="relative overflow-hidden bg-slate-900 pt-32 pb-20 px-6 md:px-8">
        <div class="absolute inset-0 opacity-20">
            <img src="/storage/2026/04/sumatra-panorama.png" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 to-slate-900/90"></div>
        <div class="relative z-10 max-w-7xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-toba-green/20 text-toba-accent text-xs font-black uppercase tracking-[0.3em] rounded-full mb-5">
                Premium Car Rental
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-5 tracking-tight leading-tight">
                Sewa <span class="text-toba-green">Mobil</span>
            </h1>
            <p class="text-slate-300 text-base md:text-lg max-w-2xl mx-auto font-medium leading-relaxed mb-10 md:mb-12">
                Pilihan armada terbaik untuk perjalanan bisnis atau wisata Anda di Sumatera Utara.
            </p>
            
            <!-- Filters -->
            <div class="max-w-4xl mx-auto bg-white/10 backdrop-blur-md p-3 md:p-4 rounded-[2rem] md:rounded-3xl border border-white/20 shadow-2xl flex flex-col gap-4">
                <div class="relative">
                    <svg class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input
                        type="text"
                        placeholder="Cari armada..."
                        x-model="searchQuery"
                        class="w-full pl-14 pr-6 py-4 bg-white/10 border-none rounded-2xl text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-toba-green/50 font-medium"
                    >
                </div>
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <template x-for="type in carTypes" :key="type">
                        <button 
                            @click="activeType = type"
                            :class="activeType === type ? 'bg-toba-green text-white shadow-lg' : 'bg-white/10 text-white hover:bg-white/20'"
                            class="px-5 md:px-6 py-3 md:py-4 rounded-xl md:rounded-2xl font-bold text-xs md:text-sm transition-all whitespace-nowrap"
                            x-text="type"
                        ></button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-8 mt-12">
        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <template x-for="car in filteredCars" :key="car.id">
                <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl transition-all duration-500 group flex flex-col h-full">
                    <div class="relative h-64 overflow-hidden bg-slate-100">
                        <img :src="car.images[0]" :alt="car.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-5 right-5">
                            <span class="bg-white/90 backdrop-blur-md text-slate-900 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-sm" x-text="car.type"></span>
                        </div>
                    </div>
                    
                    <div class="p-8 flex-1 flex flex-col">
                        <h3 class="text-2xl font-black text-slate-900 mb-4 group-hover:text-toba-green transition-colors leading-tight" x-text="car.name"></h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="flex items-center gap-3 text-slate-500">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-toba-green">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                </div>
                                <div class="flex flex-col leading-none">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Kapasitas</span>
                                    <span class="text-sm font-black text-slate-700" x-text="car.capacity + ' Orang'"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-slate-500">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-toba-green">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                </div>
                                <div class="flex flex-col leading-none">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Transmisi</span>
                                    <span class="text-sm font-black text-slate-700" x-text="car.transmission"></span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 mb-8">
                            <template x-for="feature in (car.features || []).slice(0, 3)" :key="feature">
                                <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
                                    <svg class="w-4 h-4 text-toba-green" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span x-text="feature"></span>
                                </div>
                            </template>
                        </div>

                        <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lepas Kunci</p>
                                <p class="text-xl font-black text-slate-900">
                                    <span class="text-xs font-bold text-slate-400 mr-1">Rp</span>
                                    <span x-text="new Intl.NumberFormat('id-ID').format(car.price)"></span>
                                    <span class="text-xs font-bold text-slate-400">/hari</span>
                                </p>
                            </div>
                            <a :href="'https://wa.me/6281323888207?text=' + encodeURIComponent('Halo Wonderful Toba, saya ingin sewa ' + car.name)" 
                               class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-toba-green transition-all shadow-lg shadow-slate-900/10 whitespace-nowrap">
                                Sewa Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredCars.length === 0" class="text-center py-24 bg-white rounded-[3rem] border border-slate-100">
            <svg class="w-16 h-16 mx-auto text-slate-200 mb-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Armada tidak ditemukan</h3>
            <p class="text-slate-500 font-medium">Coba gunakan kata kunci atau kategori yang berbeda.</p>
        </div>
    </div>
</div>
@endsection
