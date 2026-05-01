@extends('layouts.app')

@section('title', ($package->name ?? 'Paket Wisata') . ' – Wonderful Toba')
@section('description', $package->description ?? '')

@section('content')
<div 
    x-data="{ 
        activeImg: 0, 
        showBooking: false,
        package: {{ json_encode($package) }},
        city: {{ json_encode($city) }},
        get locationDisplay() {
            return this.city ? (this.city.type === 'international' ? (this.city.place || this.city.region || '') + ', ' + this.city.country : this.city.name) : 'Sumatera Utara';
        },
        get isInternational() {
            return this.city && this.city.type === 'international';
        }
    }"
    class="bg-slate-50 min-h-screen pb-24 pt-24"
>
    <div class="max-w-6xl mx-auto px-6 md:px-8">
        <!-- Back -->
        <a href="/tour/packages" class="flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Daftar Paket
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Left: Images + Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Image -->
                <div class="relative h-[300px] md:h-[420px] rounded-[2rem] overflow-hidden shadow-xl">
                    <template x-for="(img, i) in package.images" :key="i">
                        <img 
                            x-show="activeImg === i"
                            :src="img" 
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                            :class="activeImg === i ? 'opacity-100' : 'opacity-0'"
                        >
                    </template>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" 
                              :class="package.status === 'active' ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white'"
                              x-text="package.status === 'active' ? 'Tersedia' : 'Nonaktif'">
                        </span>
                    </div>
                </div>

                <!-- Thumbnail strip -->
                <div x-show="package.images.length > 1" class="flex gap-3">
                    <template x-for="(img, i) in package.images" :key="i">
                        <button @click="activeImg = i"
                            :class="activeImg === i ? 'border-toba-green' : 'border-transparent'"
                            class="w-20 h-16 rounded-xl overflow-hidden border-2 transition-all">
                            <img :src="img" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-900 mb-4">Tentang Paket Ini</h2>
                    <p class="text-slate-600 leading-relaxed font-medium" x-text="package.description"></p>
                </div>

                <!-- Pricing Details Table -->
                <div x-show="package.pricingDetails && package.pricingDetails.length > 0" class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-toba-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                        Rincian Harga Paket
                    </h2>
                    <div class="overflow-hidden rounded-2xl border border-slate-50">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-400">Jumlah Peserta</th>
                                    <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-400 text-right">Harga / Orang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <template x-for="(p, i) in package.pricingDetails" :key="i">
                                    <tr class="hover:bg-slate-50/30">
                                        <td class="px-6 py-4 font-bold text-slate-700" x-text="p.pax + ' Orang'"></td>
                                        <td class="px-6 py-4 font-black text-slate-900 text-right" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(p.price_per_person)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Itinerary Section -->
                <div x-show="package.itinerary || package.itineraryText" class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-2">
                        <svg class="w-5 h-5 text-toba-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Jadwal Perjalanan
                    </h2>
                    
                    <div x-show="package.itineraryText" class="prose prose-slate max-w-none whitespace-pre-line text-slate-600 font-medium" x-text="package.itineraryText"></div>
                    
                    <div x-show="!package.itineraryText && package.itinerary" class="space-y-8">
                        <template x-for="(day, i) in package.itinerary" :key="i">
                            <div class="relative pl-10 border-l-2 border-slate-100 last:border-0 pb-2">
                                <div class="absolute -left-[11px] top-0 w-5 h-5 bg-white border-4 border-toba-green rounded-full shadow-sm"></div>
                                <div class="bg-slate-50 p-5 md:p-6 rounded-2xl">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-toba-green mb-1 block" x-text="'Hari ' + (day.day || (i + 1))"></span>
                                    <h4 class="text-base md:text-lg font-black text-slate-900 mb-2" x-text="day.title"></h4>
                                    <p class="text-xs md:text-sm text-slate-500 leading-relaxed font-medium" x-text="day.description"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Includes / Excludes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100">
                        <h3 class="font-black text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Sudah Termasuk
                        </h3>
                        <ul class="space-y-2">
                            <template x-for="item in (package.includes || [])" :key="item">
                                <li class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full shrink-0"></span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100">
                        <h3 class="font-black text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Tidak Termasuk
                        </h3>
                        <ul class="space-y-2">
                            <template x-for="item in (package.excludes || [])" :key="item">
                                <li class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full shrink-0"></span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right: Booking Card -->
            <div class="space-y-5">
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-slate-100 sticky top-28">
                    <div class="flex items-center gap-2 text-toba-green text-xs font-black uppercase tracking-widest mb-3">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span x-text="locationDisplay"></span>
                        <span x-show="isInternational">✈️</span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 mb-2 leading-tight" x-text="package.name"></h1>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-1.5 text-sm text-slate-500 font-medium">
                            <svg class="w-3.5 h-3.5 text-toba-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span x-text="package.duration"></span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <span class="text-sm font-bold text-slate-700">4.8</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-5 mb-6">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Harga Per Orang</p>
                        <p class="text-3xl font-black text-slate-900">
                            <span class="text-base font-bold text-slate-400 mr-1">Rp</span>
                            <span x-text="new Intl.NumberFormat('id-ID').format(package.price)"></span>
                        </p>
                    </div>

                    <button
                        @click="showBooking = true"
                        :disabled="package.status !== 'active'"
                        class="w-full py-4 bg-toba-green text-white rounded-2xl font-black text-sm hover:bg-toba-green/90 transition-all shadow-xl shadow-toba-green/20 disabled:opacity-50 disabled:cursor-not-allowed mb-3"
                    >
                        <span x-text="package.status === 'active' ? 'Pesan Sekarang' : 'Paket Tidak Tersedia'"></span>
                    </button>

                    <a
                        :href="'https://wa.me/6281323888207?text=' + encodeURIComponent('Halo, saya tertarik dengan paket: *' + package.name + '*') "
                        target="_blank" rel="noopener noreferrer"
                        class="w-full flex items-center justify-center gap-2 py-3.5 bg-emerald-50 text-emerald-600 rounded-2xl font-bold text-sm hover:bg-emerald-100 transition-all border border-emerald-100"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        Tanya via WhatsApp
                    </a>

                    <!-- Download PDF -->
                    <div class="mt-4 pt-4 border-t border-slate-50">
                        <a href="{{ route('itinerary.download', $package->slug) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-slate-50 text-slate-500 rounded-2xl font-bold text-xs hover:bg-slate-100 hover:text-slate-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download Itinerary (PDF)
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Simple Booking Modal Overlay -->
    <template x-if="showBooking">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/70 backdrop-blur-md" @click="showBooking = false">
            <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden" @click.stop>
                <div class="p-6 md:p-8 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Form Pemesanan</h3>
                        <p class="text-sm text-slate-500 font-medium mt-1" x-text="package.name"></p>
                    </div>
                    <button @click="showBooking = false" class="p-2 hover:bg-slate-50 rounded-xl text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="p-8">
                    <p class="text-slate-600 mb-6">Silakan hubungi tim kami untuk konfirmasi ketersediaan tanggal dan detail pembayaran.</p>
                    <a :href="'https://wa.me/6281323888207?text=' + encodeURIComponent('Halo Wonderful Toba, saya ingin memesan paket: *' + package.name + '*.\n\nMohon informasi ketersediaan untuk tanggal ...')" 
                       class="w-full py-4 bg-toba-green text-white rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-toba-green/90 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        Konfirmasi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
