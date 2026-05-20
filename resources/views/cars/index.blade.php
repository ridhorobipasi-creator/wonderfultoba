@extends('layouts.app')

@section('title', 'Sewa Mobil Danau Toba & Medan – Sujai Laketoba')
@section('description', 'Layanan sewa mobil profesional di Sumatera Utara. Unit terbaru, driver berpengalaman, dan harga kompetitif.')

@section('content')
<div 
    x-data="{ 
        cars: @js($cars),
        searchQuery: '',
        activeType: 'Semua',
        
        get filteredCars() {
            return this.cars.filter(c => {
                const matchType = this.activeType === 'Semua' || c.type === this.activeType;
                const matchSearch = c.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchType && matchSearch;
            });
        },
        showModal: false,
        selectedCar: null,
        openBookingModal(car) {
            this.selectedCar = car;
            this.showModal = true;
        },
        closeBookingModal() {
            this.showModal = false;
            this.selectedCar = null;
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
            <img src="/storage/2026/04/sumatra-panorama.webp" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 to-slate-900/90"></div>
        <div class="relative z-10 max-w-7xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-toba-green/10 text-toba-accent text-[10px] font-semibold uppercase tracking-[0.2em] rounded-full mb-5">
                Premium Car Rental
            </span>
            <h1 class="text-4xl md:text-5xl font-light text-white mb-5 tracking-tight leading-tight">
                Sewa <span class="text-toba-green">Mobil</span>
            </h1>
            <p class="text-slate-300 text-sm md:text-base max-w-2xl mx-auto font-normal leading-relaxed mb-10 md:mb-12">
                Pilihan armada terbaik untuk perjalanan bisnis atau wisata Anda di Sumatera Utara.
            </p>
            
            <!-- Filters -->
            <div class="max-w-4xl mx-auto bg-white/5 backdrop-blur-md p-4 rounded-3xl border border-white/10 shadow-lg flex flex-col gap-4 animate-fade-in-up">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input
                        type="text"
                        placeholder="Cari armada..."
                        x-model="searchQuery"
                        class="w-full pl-12 pr-5 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-toba-green font-medium text-sm outline-none"
                    >
                </div>
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <template x-for="type in carTypes" :key="type">
                        <button 
                            @click="activeType = type"
                            :class="activeType === type ? 'bg-toba-green text-white shadow-sm' : 'bg-white/5 text-white hover:bg-white/10 border border-white/5'"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all whitespace-nowrap"
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
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 hover:border-slate-200 transition-colors duration-300 group flex flex-col h-full">
                    <div class="relative h-60 overflow-hidden bg-slate-50">
                        <img :src="car.images && car.images[0] ? (car.images[0].startsWith('http') ? car.images[0] : '/storage/' + car.images[0].replace('/storage/', '')) : '/images/placeholder-car.webp'" :alt="car.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[1.5s]">
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/95 backdrop-blur-md text-slate-900 px-3 py-1 rounded-lg text-[9px] font-semibold uppercase tracking-wider border border-slate-100 shadow-sm" x-text="car.type"></span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-toba-green transition-colors leading-tight" x-text="car.name"></h3>
                        
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div class="flex items-center gap-2 text-slate-500">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-toba-green">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                </div>
                                <div class="flex flex-col leading-none">
                                    <span class="text-[9px] font-semibold text-slate-400 uppercase">Kapasitas</span>
                                    <span class="text-xs font-bold text-slate-700 mt-0.5" x-text="car.capacity + ' Orang'"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-slate-500">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-toba-green">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                </div>
                                <div class="flex flex-col leading-none">
                                    <span class="text-[9px] font-semibold text-slate-400 uppercase">Transmisi</span>
                                    <span class="text-xs font-bold text-slate-700 mt-0.5" x-text="car.transmission"></span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 mb-6">
                            <template x-for="feature in (car.features || []).slice(0, 3)" :key="feature">
                                <div class="flex items-center gap-2 text-xs font-normal text-slate-500">
                                    <svg class="w-3.5 h-3.5 text-toba-green" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span x-text="feature"></span>
                                </div>
                            </template>
                        </div>

                        <div class="mt-auto pt-5 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Lepas Kunci</p>
                                <p class="text-lg font-bold text-slate-900">
                                    <span x-text="car.formatted_price"></span>
                                    <span class="text-[10px] font-normal text-slate-400">/{{ __('hari') }}</span>
                                </p>
                            </div>
                            <button @click="openBookingModal(car)" 
                               class="bg-slate-955 text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-toba-green transition-all duration-300 whitespace-nowrap">
                                Sewa Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredCars.length === 0" class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
            <svg class="w-12 h-12 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <h3 class="text-xl font-light text-slate-900 mb-2">Armada tidak ditemukan</h3>
            <p class="text-slate-500 text-sm font-normal">Coba gunakan kata kunci atau kategori yang berbeda.</p>
        </div>
    </div>

    <!-- Booking Modal -->
    <div x-show="showModal" 
        style="display: none;" 
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-slate-950/40 backdrop-blur-sm p-4 animate-fade-in">
        
        <div x-show="showModal" 
            @click.away="closeBookingModal"
            class="bg-white w-full max-w-xl rounded-3xl overflow-hidden shadow-2xl relative border border-slate-100"
            x-transition>
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">
                    Form Pemesanan Mobil
                </h3>
                <button @click="closeBookingModal" class="p-1.5 bg-white text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors border border-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="
                    const phone = '{{ $siteSettings['general']['whatsapp'] ?? '6281323888207' }}'.replace(/[^0-9]/g,'');
                    const msg = 'Halo Sujai Laketoba, saya ingin memesan *' + (selectedCar?.name ?? 'mobil') + '* dari tanggal ' + $el.startDate.value + ' s/d ' + $el.endDate.value + '. Nama: ' + $el.customerName.value;
                    window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(msg), '_blank');
                " class="p-6 space-y-4">

                <input type="hidden" name="carId" :value="selectedCar?.id" />

                <!-- Detail Mobil -->
                <div class="bg-blue-50/20 p-4 border border-blue-100/50 rounded-2xl flex gap-3 items-center">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-2xl shadow-sm border border-blue-50/50">
                        🚗
                    </div>
                    <div>
                        <p class="text-[9px] font-semibold text-blue-500 uppercase tracking-wider mb-0.5">Unit Dipilih</p>
                        <p class="font-bold text-slate-900 text-sm" x-text="selectedCar?.name"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="customerName" required class="w-full bg-slate-50/50 border border-slate-200/50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-toba-green text-sm" placeholder="Nama Anda" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                        <input type="email" name="customerEmail" required class="w-full bg-slate-50/50 border border-slate-200/50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-toba-green text-sm" placeholder="Email aktif" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor WhatsApp</label>
                        <input type="text" name="customerPhone" required class="w-full bg-slate-50/50 border border-slate-200/50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-toba-green text-sm" placeholder="08..." />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Tanggal Mulai</label>
                        <input type="date" name="startDate" required class="w-full bg-slate-50/50 border border-slate-200/50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-toba-green text-sm text-slate-700" />
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Tanggal Selesai</label>
                        <input type="date" name="endDate" required class="w-full bg-slate-50/50 border border-slate-200/50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-toba-green text-sm text-slate-700" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kota Penjemputan</label>
                        <input type="text" name="pickupLocation" required class="w-full bg-slate-50/50 border border-slate-200/50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-toba-green text-sm" placeholder="Contoh: Kualanamu, Medan" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full bg-slate-50/50 border border-slate-200/50 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-toba-green text-sm" placeholder="Informasi tambahan..."></textarea>
                </div>

                <button type="submit" class="w-full bg-slate-950 hover:bg-toba-green text-white font-bold py-3 rounded-xl transition-colors duration-300 text-sm mt-2 shadow-md shadow-slate-200">
                    Kirim Pemesanan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
