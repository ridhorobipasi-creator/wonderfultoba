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
                    'priceCurrency'=> \App\Helpers\CurrencyHelper::PRICE_BASE,
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
        packages: @js($packages),
        cities: @js($cities)
    }"
    class="min-h-screen flex flex-col bg-slate-50"
>
    <main class="flex-grow">
        <!-- Hero Section -->
        <div class="relative h-[55dvh] min-h-[320px] flex items-end overflow-hidden">
            @php
                $heroImg = (count($packages) > 0 && count($packages[0]->packageImages) > 0) 
                    ? imageUrl($packages[0]->packageImages[0]->image_path) 
                    : imageUrl('sumatra-panorama');
            @endphp
            <img src="{{ $heroImg }}" alt="Packages Hero" class="absolute inset-0 w-full h-full object-cover" fetchpriority="high" decoding="async">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-slate-900/50 to-slate-50"></div>
            <div class="relative z-10 w-full max-w-7xl mx-auto px-5 md:px-8 pb-12 md:pb-16">
                <div class="animate-in fade-in slide-in-from-bottom-8 duration-1000">
                    <x-breadcrumb :dark="true" class="mb-4" :items="[
                        ['label' => __('Paket Wisata')],
                    ]" />
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-toba-green/20 backdrop-blur-md border border-white/10 text-white text-[10px] font-semibold uppercase tracking-[0.2em] rounded-full mb-4">Eksplorasi Indonesia & Dunia</span>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white tracking-tight leading-[1.1]">
                        Paket Wisata <span class="text-toba-green">Pilihan Terbaik</span>
                    </h1>
                </div>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="max-w-7xl mx-auto px-5 md:px-8 mt-8 md:mt-14">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-0.5">Menampilkan Hasil</h2>
                    <p class="text-slate-500 font-normal text-xs">Ditemukan <span class="text-toba-green font-bold" x-text="packages.length"></span> paket wisata</p>
                </div>
            </div>

            {{-- items-start: tiap kartu setinggi isinya sendiri. Dengan
                 align-items: stretch bawaan, seluruh kartu dipaksa setinggi
                 yang tertinggi -- begitu satu akordeon dibuka, kartu itu jadi
                 yang tertinggi dan SEMUA tetangganya ikut melar mengikuti,
                 isinya menggantung di ruang kosong. Tinggi kartu tertutup
                 tetap seragam karena judul dan deskripsinya sudah dibatasi
                 line-clamp. --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 items-start">
                <template x-for="(pkg, i) in packages" :key="pkg.id">
                    <div class="animate-in fade-in slide-in-from-bottom-12 duration-1000" :style="'animation-delay: ' + (i * 100) + 'ms'">
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 hover:border-slate-200 transition-colors duration-300 group h-full flex flex-col shadow-sm">
                            <div class="relative h-64 overflow-hidden shrink-0" x-data="{ loaded: false }">
                                <!-- Blur placeholder -->
                                <div class="absolute inset-0 bg-gradient-to-br from-toba-green/20 via-slate-200/50 to-toba-green/20 animate-pulse"
                                     x-show="!loaded"></div>
                                <a :href="'/tour/detail/' + (pkg.slug || pkg.id)" class="block w-full h-full"
                                   :aria-label="pkg.translated_name">
                                <img :src="pkg.first_image" :alt="pkg.name"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-[1.5s]"
                                     :class="loaded ? 'opacity-100 scale-100' : 'opacity-0 scale-105'"
                                     style="transition: opacity 0.6s ease, transform 1.5s ease"
                                     loading="lazy" decoding="async"
                                     x-on:load="loaded = true"
                                     x-on:error="loaded = true">
                                </a>
                                
                                @php $__pkgRating = siteRating(); @endphp
                                <div class="absolute top-4 left-4 flex flex-col space-y-1.5">
                                    @if($__pkgRating)
                                    <div class="bg-white/95 backdrop-blur-md px-2.5 py-1 rounded-lg flex items-center space-x-1 border border-slate-100 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star text-amber-400 fill-amber-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                                        <span class="font-bold text-slate-800 text-[10px] tracking-wider">{{ number_format($__pkgRating['value'], 1) }}</span>
                                    </div>
                                    @endif
                                    <div class="bg-slate-950 text-white px-2.5 py-1 rounded-lg text-[9px] font-semibold uppercase tracking-wider" x-text="pkg.duration"></div>
                                </div>

                                {{-- Tombol wishlist dihapus: tidak pernah tersambung ke
                                     apa pun. Ia menerima klik, memberi efek hover, lalu
                                     tidak menyimpan apa-apa — dan pengunjung baru sadar
                                     saat mencari daftar simpanannya yang tidak ada. --}}
                            </div>

                            <div class="px-6 pt-6 pb-4 flex flex-col flex-grow">
                                <div class="flex items-center text-toba-green text-[9px] font-semibold uppercase tracking-wider mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mr-1.5"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <span x-text="(pkg.cities && pkg.cities.length > 0) ? pkg.cities.map(c => c.name).join(', ') : (cities.find(c => String(c.id) === String(pkg.cityId))?.name || 'Sumatera Utara')"></span>
                                </div>
                                {{-- Kartu di grid ini sebelumnya tidak bisa diklik sama
                                     sekali: satu-satunya jalan masuk cuma tombol Booking,
                                     jadi tamu yang cuma ingin membaca detail tidak punya
                                     pintu. Judulnya kini menuju halaman detail tanpa form. --}}
                                <a :href="'/tour/detail/' + (pkg.slug || pkg.id)" class="block">
                                    <h3 class="text-lg font-bold text-slate-900 mb-3 line-clamp-1 group-hover:text-toba-green transition-colors tracking-tight" x-text="pkg.translated_name"></h3>
                                </a>
                                <p class="text-slate-500 text-xs leading-relaxed mb-4 line-clamp-2 font-normal flex-grow" x-text="pkg.translated_description"></p>
                            </div>
                            @include('partials.package-details', [
                                'xdata' => 'pkgDetails(pkg.includes || [], pkg.excludes || [], pkg.itinerary || [])',
                                'uid' => '\'pkg-detail-grid-\' + pkg.id',
                            ])
                            @include('partials.pax-calc', ['xdata' => 'paxCalc(pkg.price, pkg.childPrice, pkg.slug || pkg.id, (pkg.pricingDetails && pkg.pricingDetails.tiers) || [], pkg.translated_name || pkg.name)'])
                        </div>
                    </div>
                </template>
            </div>

            {{-- Tanpa filter, daftar kosong hanya berarti satu hal: memang belum
                 ada paket aktif. Tombol "Reset Semua Filter" di sini dulu
                 merujuk state yang sudah tidak ada -- sekali diklik, Alpine
                 melempar ReferenceError dan seluruh komponen berhenti bekerja. --}}
            <div x-show="packages.length === 0" class="text-center py-24 bg-white rounded-3xl border border-slate-100 shadow-sm animate-in fade-in zoom-in duration-700">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-x"><path d="m16 16 5 5"></path><circle cx="10" cy="10" r="7"></circle><path d="m7 7 6 6"></path><path d="m13 7-6 6"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">{{ __('Paket Belum Tersedia') }}</h3>
                <p class="text-slate-500 text-xs font-normal max-w-xs mx-auto mb-8 leading-relaxed">{{ __('Daftar paket sedang kami perbarui. Ceritakan rencana Anda dan kami susunkan penawarannya.') }}</p>
                @php
                    $kosongPesan = __('Halo, saya ingin bertanya tentang paket wisata Danau Toba.');
                @endphp
                <a href="https://wa.me/{{ \App\Helpers\ContactHelper::whatsappDigits() }}?text={{ rawurlencode($kosongPesan) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-slate-950 text-white px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-toba-green transition-colors duration-300">
                    <x-icon name="whatsapp" class="w-3.5 h-3.5" />
                    {{ __('Tanya lewat WhatsApp') }}
                </a>
            </div>
        </div>

        <!-- Custom CTA Section -->
        <div class="max-w-7xl mx-auto px-5 md:px-8 mt-16 md:mt-24 mb-16 md:mb-24">
            <div class="bg-gradient-to-r from-toba-green to-primary-container rounded-3xl p-8 md:p-12 text-center relative overflow-hidden shadow-sm">
                <div class="absolute inset-0 opacity-10">
                    <img src="{{ imageUrl('sumatra-panorama') }}" alt="Paket wisata - destinasi" loading="lazy" decoding="async" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-3 tracking-tight">Tidak Menemukan Paket yang Cocok?</h3>
                    <p class="text-white/80 text-sm font-normal mb-8 max-w-lg mx-auto">Kami siap merancang itinerary khusus sesuai kebutuhan dan budget Anda.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ \App\Helpers\ContactHelper::whatsappLink() }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 bg-white text-toba-green px-6 py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Konsultasi Gratis
                        </a>
                        <a href="{{ \App\Helpers\ContactHelper::whatsappLink() }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 bg-white/10 text-white border border-white/20 px-6 py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-white/20 transition">
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
