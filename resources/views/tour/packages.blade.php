@extends('layouts.app')

@section('title', 'Paket Wisata Sumatera Utara – Sujai Laketoba')
@section('description', 'Pilihan paket wisata Danau Toba terbaik mulai dari private tour, group gathering, hingga corporate outing dengan layanan premium.')
@section('keywords', 'paket wisata murah danau toba, private tour danau toba, paket gathering medan, harga paket wisata toba')

@push('schema')
@php
    $itemListElements = [];
    foreach ($packages as $idx => $pkg) {
        $pkgImg  = $pkg->resolveImageUrl($pkg->packageImages->first()?->image_path ?? ($pkg->images[0] ?? null));
        $pkgUrl  = route('tour.package.detail', ['slug' => $pkg->slug ?? $pkg->id]);
        $pkgPrice = $pkg->price ?? 0;
        $itemListElements[] = [
            '@type'    => 'ListItem',
            'position' => $idx + 1,
            'item'     => [
                '@type'       => 'TouristTrip',
                '@id'         => $pkgUrl,
                'name'        => $pkg->translated_name,
                'description' => Str::limit(strip_tags($pkg->translated_description ?? ''), 160),
                'url'         => $pkgUrl,
                'image'       => $pkgImg,
                'touristType' => ['Family', 'Couple', 'Group'],
                'offers'      => [
                    '@type'        => 'Offer',
                    'price'        => (string) $pkgPrice,
                    'priceCurrency'=> 'IDR',
                    'availability' => 'https://schema.org/InStock',
                    'seller'       => ['@type' => 'TravelAgency', 'name' => 'Sujai Laketoba'],
                ],
            ],
        ];
    }
    $schemaData = [
        '@context'     => 'https://schema.org',
        '@type'        => 'ItemList',
        'name'         => 'Paket Wisata Sumatera Utara – Sujai Laketoba',
        'description'  => 'Pilihan lengkap paket wisata premium Danau Toba, Samosir, Berastagi, Tangkahan, dan seluruh Sumatera Utara.',
        'url'          => url()->current(),
        'numberOfItems'=> count($packages),
        'itemListElement' => $itemListElements,
    ];
@endphp
<script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@section('content')
<div 
    x-data="{ 
        searchQuery: '', 
        filterCity: 'all', 
        filterDuration: 'Semua', 
        sortBy: 'default',
        packages: @js($packages),
        cities: @js($cities),
        
        get filteredPackages() {
            let filtered = this.packages.filter(p => {
                const matchCity = this.filterCity === 'all' || String(p.cityId) === this.filterCity;
                const matchSearch = p.translated_name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                   (p.translated_description && p.translated_description.toLowerCase().includes(this.searchQuery.toLowerCase()));
                
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
        <div class="relative h-[55vh] flex items-end overflow-hidden">
            @php
                $heroImg = (count($packages) > 0 && count($packages[0]->packageImages) > 0) 
                    ? imageUrl($packages[0]->packageImages[0]->image_path) 
                    : imageUrl('sumatra-panorama');
            @endphp
            <img src="{{ $heroImg }}" alt="Packages Hero" class="absolute inset-0 w-full h-full object-cover" fetchpriority="high" decoding="async">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-slate-900/50 to-slate-50"></div>
            <div class="relative z-10 w-full max-w-7xl mx-auto px-5 md:px-8 pb-28">
                <div class="animate-in fade-in slide-in-from-bottom-8 duration-1000">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-toba-green/20 backdrop-blur-md border border-white/10 text-white text-[10px] font-semibold uppercase tracking-[0.2em] rounded-full mb-4">Eksplorasi Indonesia & Dunia</span>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white tracking-tight leading-[1.1]">
                        Paket Wisata <span class="text-toba-accent">Pilihan Terbaik</span>
                    </h1>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="max-w-7xl mx-auto px-5 md:px-8 -mt-16 relative z-20">
            <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-slate-100 animate-in fade-in zoom-in duration-1000 delay-300">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Box -->
                    <div class="relative group lg:col-span-2">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-focus-within:bg-toba-green group-focus-within:text-white transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                        </div>
                        <input type="text" placeholder="Cari destinasi atau paket..." x-model="searchQuery"
                            class="w-full pl-14 pr-4 py-3 bg-slate-50/50 border border-slate-200/50 rounded-2xl focus:ring-1 focus:ring-toba-green font-medium text-slate-700 placeholder:text-slate-400 text-sm outline-none">
                    </div>
                    <!-- City Filter -->
                    <div class="relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-toba-green pointer-events-none"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <select x-model="filterCity"
                            class="w-full pl-12 pr-4 py-3 bg-slate-50/50 border border-slate-200/50 rounded-2xl focus:ring-1 focus:ring-toba-green font-medium text-slate-700 appearance-none cursor-pointer outline-none text-sm">
                            <option value="all">Semua Wilayah</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Sort Filter -->
                    <div class="relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sliders-horizontal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-toba-green pointer-events-none"><path d="m21 16-4 4-4-4"></path><path d="M17 20V4"></path><path d="m3 8 4-4 4 4"></path><path d="M7 4v16"></path></svg>
                        <select x-model="sortBy"
                            class="w-full pl-12 pr-4 py-3 bg-slate-50/50 border border-slate-200/50 rounded-2xl focus:ring-1 focus:ring-toba-green font-medium text-slate-700 appearance-none cursor-pointer outline-none text-sm">
                            <option value="default">Urutkan</option>
                            <option value="price-asc">Harga Terendah</option>
                            <option value="price-desc">Harga Tertinggi</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mt-4 items-center">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mr-1">Durasi:</span>
                    @foreach(['Semua', '1-3 Hari', '4-7 Hari', '7+ Hari'] as $d)
                    <button @click="filterDuration = '{{ $d }}'"
                        :class="filterDuration === '{{ $d }}' ? 'bg-toba-green text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200/50'"
                        class="flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-semibold transition-all whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                        {{ $d }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="max-w-7xl mx-auto px-5 md:px-8 mt-14">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-0.5">Menampilkan Hasil</h2>
                    <p class="text-slate-500 font-normal text-xs">Ditemukan <span class="text-toba-green font-bold" x-text="filteredPackages.length"></span> paket wisata</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <template x-for="(pkg, i) in filteredPackages" :key="pkg.id">
                    <div class="animate-in fade-in slide-in-from-bottom-12 duration-1000" :style="'animation-delay: ' + (i * 100) + 'ms'">
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 hover:border-slate-200 transition-colors duration-300 group h-full flex flex-col shadow-sm">
                            <div class="relative h-64 overflow-hidden shrink-0" x-data="{ loaded: false }">
                                <!-- Blur placeholder -->
                                <div class="absolute inset-0 bg-gradient-to-br from-toba-green/20 via-slate-200/50 to-toba-accent/20 animate-pulse"
                                     x-show="!loaded"></div>
                                <img :src="pkg.first_image" :alt="pkg.name" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-all duration-[1.5s]"
                                     :class="loaded ? 'opacity-100 scale-100' : 'opacity-0 scale-105'"
                                     style="transition: opacity 0.6s ease, transform 1.5s ease"
                                     loading="lazy" decoding="async"
                                     x-on:load="loaded = true"
                                     x-on:error="loaded = true">
                                
                                <div class="absolute top-4 left-4 flex flex-col space-y-1.5">
                                    <div class="bg-white/95 backdrop-blur-md px-2.5 py-1 rounded-lg flex items-center space-x-1 border border-slate-100 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star text-amber-400 fill-amber-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                                        <span class="font-bold text-slate-800 text-[10px] tracking-wider">4.8</span>
                                    </div>
                                    <div class="bg-slate-950 text-white px-2.5 py-1 rounded-lg text-[9px] font-semibold uppercase tracking-wider" x-text="pkg.duration"></div>
                                </div>

                                <button class="absolute top-4 right-4 w-8 h-8 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-rose-500 transition-all shadow-sm" aria-label="Simpan ke wishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                                </button>
                            </div>

                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex items-center text-toba-green text-[9px] font-semibold uppercase tracking-wider mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mr-1.5"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <span x-text="cities.find(c => String(c.id) === String(pkg.cityId))?.name || 'Sumatera Utara'"></span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-3 line-clamp-1 group-hover:text-toba-green transition-colors tracking-tight" x-text="pkg.translated_name"></h3>
                                <p class="text-slate-500 text-xs leading-relaxed mb-6 line-clamp-2 font-normal flex-grow" x-text="pkg.translated_description"></p>
                                
                                <div class="flex items-center justify-between pt-5 border-t border-slate-100">
                                    <div>
                                        <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider mb-0.5">{{ __('Mulai dari') }}</p>
                                        <div class="flex items-baseline space-x-1">
                                            <span class="text-lg font-bold text-slate-900" x-text="pkg.formatted_price || '-'"></span>
                                        </div>
                                    </div>
                                    <a :href="'/tour/package/' + (pkg.slug || pkg.id)" class="w-10 h-10 bg-slate-950 text-white rounded-xl flex items-center justify-center hover:bg-toba-green transition-all group/btn" aria-label="Lihat detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right group-hover/btn:translate-x-0.5 transition-transform"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Premium Empty State -->
            <div x-show="filteredPackages.length === 0" class="text-center py-24 bg-white rounded-3xl border border-slate-100 shadow-sm animate-in fade-in zoom-in duration-700">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-x"><path d="m16 16 5 5"></path><circle cx="10" cy="10" r="7"></circle><path d="m7 7 6 6"></path><path d="m13 7-6 6"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Destinasi Belum Ditemukan</h3>
                <p class="text-slate-500 text-xs font-normal max-w-xs mx-auto mb-8 leading-relaxed">Kami tidak menemukan paket yang sesuai dengan kriteria Anda. Cobalah untuk mereset filter atau mencari dengan kata kunci lain.</p>
                <button @click="searchQuery = ''; filterCity = 'all'; filterDuration = 'Semua'; sortBy = 'default'"
                    class="bg-slate-950 text-white px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-toba-green transition-colors duration-300">
                    Reset Semua Filter
                </button>
            </div>
        </div>

        <!-- Custom CTA Section -->
        <div class="max-w-7xl mx-auto px-5 md:px-8 mt-16 md:mt-24 mb-16 md:mb-24">
            <div class="bg-gradient-to-r from-toba-green to-emerald-600 rounded-3xl p-8 md:p-12 text-center relative overflow-hidden shadow-sm">
                <div class="absolute inset-0 opacity-10">
                    <img src="{{ imageUrl('sumatra-panorama') }}" alt="Paket wisata - destinasi" loading="lazy" decoding="async" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-3 tracking-tight">Tidak Menemukan Paket yang Cocok?</h3>
                    <p class="text-white/80 text-sm font-normal mb-8 max-w-lg mx-auto">Kami siap merancang itinerary khusus sesuai kebutuhan dan budget Anda.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="tel:+{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6282277848855') }}" class="flex items-center justify-center gap-2 bg-white text-toba-green px-6 py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Konsultasi Gratis
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6282277848855') }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 bg-white/10 text-white border border-white/20 px-6 py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-white/20 transition-all">
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
