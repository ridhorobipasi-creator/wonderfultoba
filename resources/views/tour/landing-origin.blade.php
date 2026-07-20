@extends('layouts.app')

@section('title', 'Paket Wisata Danau Toba dari ' . $originName . ' – Harga Terbaik 2026')
@section('description', 'Pilihan paket liburan premium ke Danau Toba, Samosir, dan sekitarnya keberangkatan dari ' . $originName . ' bersama Sujai Laketoba.')
@section('keywords', __('paket wisata danau toba dari ' . strtolower($originName) . ', travel danau toba dari ' . strtolower($originName) . ', tour samosir ' . strtolower($originName)))


@push('schema')
@php
    $sameAsLinks = [];
    if (!empty($siteSettings['general']['social_instagram'])) {
        $sameAsLinks[] = 'https://www.instagram.com/' . ltrim($siteSettings['general']['social_instagram'], '@');
    }
    if (!empty($siteSettings['general']['social_facebook'])) {
        $sameAsLinks[] = 'https://www.facebook.com/' . $siteSettings['general']['social_facebook'];
    }
    if (!empty($siteSettings['general']['social_tiktok'])) {
        $sameAsLinks[] = 'https://www.tiktok.com/@' . ltrim($siteSettings['general']['social_tiktok'], '@');
    }
    if (!empty($siteSettings['general']['social_youtube'])) {
        $sameAsLinks[] = 'https://www.youtube.com/' . $siteSettings['general']['social_youtube'];
    }
    $schemaLogoUrl = imageUrl($siteSettings['general']['logo_light_url'] ?? null, asset('assets/img/logo.png'));
    $schemaPhone   = '+' . \App\Helpers\ContactHelper::whatsappDigits();
    $schemaEmail   = $siteSettings['general']['contact_email'] ?? 'hello@sujailaketoba.com';
    $schemaDesc    = $settings['meta_description'] ?? 'Agen perjalanan wisata Danau Toba terpercaya';

    $homepageSchema = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'       => 'TravelAgency',
                '@id'         => url('/') . '/#organization',
                'name'        => 'Sujai Laketoba',
                'url'         => url('/'),
                'logo'        => [
                    '@type' => 'ImageObject',
                    'url'   => $schemaLogoUrl,
                ],
                'image'       => $schemaLogoUrl,
                'description' => 'Agen perjalanan wisata premium untuk Danau Toba, Samosir, Berastagi, Tangkahan, dan seluruh destinasi Sumatera Utara.',
                'telephone'   => $schemaPhone,
                'email'       => $schemaEmail,
                'address'     => [
                    '@type'           => 'PostalAddress',
                    'addressLocality' => 'Balige',
                    'addressRegion'   => 'Sumatera Utara',
                    'addressCountry'  => 'ID',
                ],
                'areaServed'    => ['@type' => 'State', 'name' => 'Sumatera Utara'],
                'sameAs'        => $sameAsLinks,
                'priceRange'    => '$$',
                'openingHours'  => 'Mo-Su 08:00-20:00',
            ],
            [
                '@type'       => 'WebSite',
                '@id'         => url('/') . '/#website',
                'url'         => url('/'),
                'name'        => 'Sujai Laketoba',
                'description' => $schemaDesc,
                'publisher'   => ['@id' => url('/') . '/#organization'],
                'potentialAction' => [
                    '@type'       => 'SearchAction',
                    'target'      => [
                        '@type'       => 'EntryPoint',
                        'urlTemplate' => url('/tour/packages') . '?search={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
                'inLanguage' => ['id', 'en', 'ms'],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($homepageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@section('content')
<div x-data="{ waNumber: @json(\App\Helpers\ContactHelper::whatsappDigits()) }">
    
    <!-- Programmatic SEO Hero Banner -->
    <section class="relative pt-32 pb-16 md:pt-40 md:pb-24 overflow-hidden bg-primary px-5 md:px-8">
        <div class="absolute inset-0 opacity-40">
            <img src="{{ imageUrl($settings['hero_image_1_url'] ?? null, 'sumatra-panorama') }}" alt="Danau Toba" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/80 to-transparent"></div>
        <div class="max-w-5xl mx-auto relative z-10 text-center">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-secondary/20 border border-secondary text-secondary font-bold text-xs uppercase tracking-widest mb-6 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                Keberangkatan dari {{ $originName }}
            </span>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white tracking-tight leading-[1.1] mb-6">
                Paket Wisata Danau Toba dari <span class="text-secondary">{{ $originName }}</span>
            </h1>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                Penerbangan dan perjalanan Anda dari {{ $originName }} kini lebih mudah. Nikmati penjemputan VIP dari bandara Kualanamu / Silangit, rute terkurasi, dan pengalaman premium di Danau Toba tanpa ribet.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://wa.me/{{ \App\Helpers\ContactHelper::whatsappDigits() }}?text={{ urlencode('Halo Sujai Laketoba, saya tertarik paket wisata Danau Toba dari ' . $originName) }}" 
                   class="w-full sm:w-auto bg-green-600 hover:bg-green-500 text-white px-8 py-4 rounded-full font-bold text-sm uppercase tracking-widest transition shadow-xl flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                    Konsultasi Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Packages -->
    @if($settings['show_featured'] ?? true)
    <section class="py-16 md:py-24 bg-surface overflow-hidden"
             x-data="{
                 isDragging: false, startX: 0, scrollLeft: 0,
                 scrollPercent: 0,
                 get el() { return this.$refs.pkgStrip },
                 onDown(e) { this.isDragging = true; this.startX = e.pageX - this.el.offsetLeft; this.scrollLeft = this.el.scrollLeft; },
                 onMove(e) { if (!this.isDragging) return; e.preventDefault(); this.el.scrollLeft = this.scrollLeft - ((e.pageX - this.el.offsetLeft) - this.startX); },
                 onUp() { this.isDragging = false; },
                 scrollPrev() { this.el.scrollBy({ left: -380, behavior: 'smooth' }); },
                 scrollNext() { this.el.scrollBy({ left: 380, behavior: 'smooth' }); },
             }">
        <div class="max-w-7xl mx-auto px-5 md:px-8">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-10 md:mb-14 gap-6">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.25em] text-secondary mb-3">
                        <span class="w-6 h-px bg-secondary"></span>{{ __('Paket Pilihan') }}
                    </span>
                    <h2 class="text-3xl md:text-5xl font-bold text-primary tracking-tight leading-[1.1]">{{ __('Pilihan Liburan Terbaik') }}</h2>
                    <p class="text-on-surface-variant text-sm md:text-base mt-3 leading-relaxed">{{ __('Geser untuk menjelajahi destinasi terkurasi di seluruh Sumatera Utara.') }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button @click="scrollPrev()"
                            class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-on-primary hover:border-primary transition">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    <button @click="scrollNext()"
                            class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-on-primary hover:border-primary transition">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <div x-ref="pkgStrip"
             @mousedown="onDown($event)" @mousemove="onMove($event)" @mouseup="onUp()" @mouseleave="onUp()"
             @scroll="const max = el.scrollWidth - el.clientWidth; scrollPercent = max > 0 ? (el.scrollLeft / max) * 100 : 0"
             class="flex gap-6 overflow-x-auto scroll-smooth px-6 md:px-[max(1.5rem,calc((100vw-80rem)/2+1.5rem))] pb-4 no-scrollbar select-none snap-x snap-mandatory overscroll-x-contain"
             :class="isDragging ? 'cursor-grabbing' : 'cursor-grab'">

            @foreach($packages as $index => $pkg)
            @php
                $pkgImage = $pkg->resolveImageUrl($pkg->packageImages->first()?->image_path ?? ($pkg->images[0] ?? null));
            @endphp
            <div class="flex-shrink-0 snap-start w-[80vw] sm:w-[45vw] md:w-[31vw] lg:w-[28vw] xl:w-[25rem] group">
                <div class="relative aspect-[3/4] overflow-hidden rounded-2xl cursor-pointer"
                     onclick="window.location.href='/tour/package/{{ $pkg->slug ?: $pkg->id }}'">
                    <img alt="{{ $pkg->translated_name }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                         src="{{ $pkgImage }}" loading="lazy"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    
                    @if($loop->first || ($pkg->isFeatured ?? false))
                    <div class="absolute top-5 left-5 bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1.5 rounded-full text-[10px] font-bold tracking-widest uppercase shadow-lg flex items-center gap-1.5 z-10">
                        <span class="text-xs">🔥</span> {{ __('Terpopuler') }}
                    </div>
                    @endif

                    @php $__r = siteRating(); @endphp
                    @if($__r)
                    <div class="absolute top-5 right-5 bg-black/60 backdrop-blur-md px-3 py-1 border border-white/10 rounded-full flex items-center gap-1.5 shadow-lg">
                        <span class="material-symbols-outlined text-secondary-fixed text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="text-white font-label-caps text-[11px] font-bold">{{ number_format($__r['value'], 1) }}</span>
                    </div>
                    @endif

                    <div class="absolute bottom-0 left-0 w-full p-6 text-white">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                            <span class="font-label-caps text-[10px] tracking-wider">{{ strtoupper(__($pkg->locationTag ?? 'Sumatera Utara')) }}</span>
                        </div>
                        <h3 class="font-headline-md text-[22px] md:text-[26px] mb-3 line-clamp-2 leading-tight">{{ $pkg->translated_name }}</h3>
                        <div class="flex justify-between items-center">
                            <div class="bg-secondary-fixed px-4 py-2.5 rounded-2xl border border-white/25 shadow-lg flex flex-col justify-center">
                                <p class="font-label-caps text-[9px] text-on-secondary-fixed-variant font-bold uppercase tracking-widest leading-none mb-1.5">{{ __('Mulai dari') }}</p>
                                <p class="font-headline-md text-[18px] md:text-[20px] text-on-secondary-fixed font-black leading-none tracking-tight">
                                    {{ \App\Helpers\CurrencyHelper::formatPrice($pkg->price) }}
                                </p>
                            </div>
                            <div class="w-10 h-10 bg-white/10 backdrop-blur-md border border-white/20 rounded-full flex items-center justify-center group-hover:bg-secondary-fixed group-hover:border-secondary-fixed group-hover:text-on-secondary-fixed transition duration-300">
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        <!-- Modern Scroll Progress Bar for Packages -->
        <div class="max-w-7xl mx-auto px-6 md:px-8 mt-6">
            <div class="h-[3px] w-full bg-slate-100 rounded-full overflow-hidden relative">
                <div class="h-full bg-secondary rounded-full absolute left-0 top-0 transition duration-150"
                     :style="'width: ' + scrollPercent + '%'"></div>
            </div>
        </div>
    </section>
    @endif

    @php
        $ctaImg = imageUrl($settings['cta_image_url'] ?? null, 'sumatra-panorama');
    @endphp

    <!-- Gallery Showcase -->
    @if($settings['show_about'] ?? true)
    @php
        // Use pre-fetched gallery slides from controller (cached)
        $slides = $gallerySlides ?? [];

        if (empty($slides)) {
            $fallbackImg = asset('images/home/tour.webp');
            $slides = [
                ['url' => imageUrl($settings['why_image_1_url'] ?? null, $fallbackImg), 'caption' => '', 'category' => ''],
                ['url' => imageUrl($settings['why_image_2_url'] ?? null, $fallbackImg), 'caption' => '', 'category' => ''],
                ['url' => imageUrl($settings['why_image_3_url'] ?? null, $fallbackImg), 'caption' => '', 'category' => ''],
                ['url' => $fallbackImg, 'caption' => '', 'category' => ''],
            ];
        }
    @endphp

    <section class="bg-primary py-16 md:py-24 overflow-hidden"
             x-data="{
                 slides: @js($slides),
                 scrollContainer: null,
                 isDragging: false,
                 startX: 0,
                 scrollLeft: 0,
                 autoTimer: null,
                 scrollPercent: 0,

                 init() {
                     this.scrollContainer = this.$refs.strip;
                     this.startAutoScroll();
                 },

                 startAutoScroll() {
                     this.stopAutoScroll();
                     this.autoTimer = setInterval(() => {
                         if (!this.scrollContainer) return;
                         const maxScroll = this.scrollContainer.scrollWidth - this.scrollContainer.clientWidth;
                         if (this.scrollContainer.scrollLeft >= maxScroll - 10) {
                             this.scrollContainer.scrollTo({ left: 0, behavior: 'smooth' });
                         } else {
                             this.scrollContainer.scrollBy({ left: 340, behavior: 'smooth' });
                         }
                     }, 4000);
                 },

                 stopAutoScroll() {
                     if (this.autoTimer) clearInterval(this.autoTimer);
                 },

                 scrollPrev() {
                     this.scrollContainer.scrollBy({ left: -340, behavior: 'smooth' });
                     this.stopAutoScroll(); this.startAutoScroll();
                 },

                 scrollNext() {
                     this.scrollContainer.scrollBy({ left: 340, behavior: 'smooth' });
                     this.stopAutoScroll(); this.startAutoScroll();
                 },

                 onMouseDown(e) {
                     this.isDragging = true;
                     this.startX = e.pageX - this.scrollContainer.offsetLeft;
                     this.scrollLeft = this.scrollContainer.scrollLeft;
                     this.stopAutoScroll();
                 },

                 onMouseMove(e) {
                     if (!this.isDragging) return;
                     e.preventDefault();
                     const x = e.pageX - this.scrollContainer.offsetLeft;
                     this.scrollContainer.scrollLeft = this.scrollLeft - (x - this.startX);
                 },

                 onMouseUp() {
                     this.isDragging = false;
                     this.startAutoScroll();
                 }
             }">

        {{-- Header --}}
        <div class="max-w-7xl mx-auto px-5 md:px-8 mb-8 md:mb-12 flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
            <div class="max-w-xl">
                <span class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.25em] text-secondary-fixed mb-3">
                    <span class="w-6 h-px bg-secondary-fixed"></span>{{ __('Galeri Destinasi') }}
                </span>
                <h2 class="text-3xl md:text-5xl font-bold text-white leading-[1.1] tracking-tight">
                    {{ __('Kenangan Nyata dari Toba') }}
                </h2>
            </div>
            <a href="{{ route('tour.gallery') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white hover:bg-secondary hover:border-secondary transition duration-300 group">
                <span class="material-symbols-outlined text-[16px]">photo_library</span>
                <span class="font-label-caps text-[10px] uppercase tracking-wider">{{ __('Lihat Semua') }}</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>

        {{-- Scrollable strip --}}
        <div class="relative group/strip">

            {{-- Prev / Next arrows --}}
            <button @click="scrollPrev()"
                    class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-md border border-white/20 rounded-full items-center justify-center text-white hover:bg-secondary hover:border-secondary transition duration-300 opacity-60 hover:opacity-100">
                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
            </button>
            <button @click="scrollNext()"
                    class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-md border border-white/20 rounded-full items-center justify-center text-white hover:bg-secondary hover:border-secondary transition duration-300 opacity-60 hover:opacity-100">
                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
            </button>

            {{-- Photo strip --}}
            <div x-ref="strip"
                 @mousedown="onMouseDown($event)"
                 @mousemove="onMouseMove($event)"
                 @mouseup="onMouseUp()"
                 @mouseleave="onMouseUp()"
                 @scroll="const max = scrollContainer.scrollWidth - scrollContainer.clientWidth; scrollPercent = max > 0 ? (scrollContainer.scrollLeft / max) * 100 : 0"
                 class="flex gap-5 overflow-x-auto scroll-smooth px-6 md:px-8 pb-4 no-scrollbar select-none snap-x snap-mandatory overscroll-x-contain"
                 :class="isDragging ? 'cursor-grabbing' : 'cursor-grab'">

                <template x-for="(slide, i) in slides" :key="i">
                    <div class="flex-shrink-0 snap-start w-[75vw] sm:w-[45vw] md:w-[30vw] lg:w-[23vw] group/card">
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-xl border border-white/10 transition duration-500 hover:border-secondary/40 hover:-translate-y-1">
                            <img :src="slide.url"
                                 :alt="slide.caption || 'Sujai Laketoba'"
                                 class="w-full h-full object-cover transition-transform duration-[2s] group-hover/card:scale-105"
                                 loading="lazy"
                                 onerror="this.src='{{ asset('images/home/tour.webp') }}'">

                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/20 to-transparent opacity-0 group-hover/card:opacity-100 transition duration-500 flex flex-col justify-end p-5">
                                <span x-show="slide.category"
                                      class="inline-block px-3 py-1 bg-secondary text-on-secondary text-[9px] font-bold uppercase tracking-widest rounded-lg mb-2 w-fit shadow-sm"
                                      x-text="slide.category"></span>
                                <p x-show="slide.caption"
                                   class="text-white text-sm font-bold leading-tight mb-1"
                                   x-text="slide.caption"></p>
                            </div>

                            {{-- Index badge --}}
                            <div class="absolute top-3 left-3 z-10 w-8 h-8 bg-black/30 backdrop-blur-md rounded-full border border-white/10 flex items-center justify-center">
                                <span class="text-white text-[10px] font-bold" x-text="String(i + 1).padStart(2, '0')"></span>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
        </div>

        <!-- Modern Scroll Progress Bar for Gallery -->
        <div class="max-w-7xl mx-auto px-6 md:px-8 mt-6">
            <div class="h-[3px] w-full bg-white/10 rounded-full overflow-hidden relative">
                <div class="h-full bg-secondary rounded-full absolute left-0 top-0 transition duration-150"
                     :style="'width: ' + scrollPercent + '%'"></div>
            </div>
        </div>

    </section>
    @endif


    <!-- Testimonials — minimal -->
    @if($settings['show_testimonials'] ?? true)
    @php
        $dynamicTestimonial = [
            'name' => 'Wisatawan dari ' . $originName,
            'location' => $originName,
            'text' => 'Sangat praktis liburan keluarga dari ' . $originName . ' berkat Sujai Laketoba. Mulai dari penjemputan di bandara hingga hotel, semuanya diurus dengan sangat profesional dan ramah.',
            'image' => 'user3'
        ];

        $testimonials = $settings['testimonials'] ?? [
            [
                'name' => 'Julian Thorne',
                'location' => 'London, UK',
                'text' => 'Perhatian terhadap detail sangat menakjubkan. Kami menjelajahi jantung Sumatera tanpa repot mengatur jadwal. Mulai dari penjemputan di Kualanamu hingga makan malam di tepi Samosir, semuanya dirancang sempurna.',
                'image' => 'user1'
            ],
            [
                'name' => 'Isabella Chen',
                'location' => 'Singapura',
                'text' => 'Sangat puas dengan pilihan hotel dan restorannya. Sujai Laketoba mengkurasi tempat-tempat otentik yang jarang diketahui turis biasa. Sangat direkomendasikan untuk liburan keluarga.',
                'image' => 'user2'
            ]
        ];

        // Insert the dynamic testimonial at the top
        array_unshift($testimonials, $dynamicTestimonial);
    @endphp
    <section class="py-16 md:py-24 bg-surface">
        <div class="max-w-5xl mx-auto px-5 md:px-8">
            <div class="flex items-center gap-3 mb-10 md:mb-12">
                <span class="w-6 h-px bg-secondary"></span>
                <span class="text-[10px] font-bold text-secondary uppercase tracking-[0.25em]">{{ __('Testimoni') }}</span>
            </div>

            <div class="space-y-8">
                @foreach($testimonials as $t)
                <div class="flex flex-col md:flex-row gap-6 md:gap-10 items-start">
                    {{-- Quote --}}
                    <div class="flex-1">
                        <p class="font-headline-md text-[18px] md:text-[22px] text-primary leading-relaxed italic">
                            "{{ __($t['text']) }}"
                        </p>
                    </div>
                    {{-- Author --}}
                    <div class="flex items-center gap-3 md:w-48 shrink-0">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-surface-container-low shrink-0">
                            <img alt="{{ $t['name'] }}"
                                 src="{{ imageUrl($t['image'] ?? null, 'user' . ($loop->iteration ?? 1)) }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-on-surface font-body-md">{{ $t['name'] }}</p>
                            <p class="text-[10px] text-on-surface-variant font-body-md">{{ __($t['location'] ?? 'Wisatawan Terverifikasi') }}</p>
                        </div>
                    </div>
                </div>
                @if(!$loop->last)
                    <div class="h-px bg-outline-variant/40"></div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Specialist — minimal inline -->
    @if($settings['show_specialist'] ?? true)
    <section class="bg-surface pb-16 md:pb-20 px-5 md:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-primary rounded-3xl px-6 py-7 md:px-10 md:py-8 flex flex-col sm:flex-row items-center gap-5 sm:gap-8 text-center sm:text-left">
                <img alt="{{ $settings['specialist_name'] ?? 'Sarah Anggraini' }}"
                     class="w-14 h-14 rounded-full object-cover border-2 border-white/10 shrink-0"
                     src="{{ imageUrl($settings['specialist_image_url'] ?? '', 'staff1') }}"/>
                <div class="flex-1 text-center sm:text-left">
                    <p class="text-white font-bold font-body-md text-sm">{{ $settings['specialist_name'] ?? 'Sarah Anggraini' }}</p>
                    <p class="text-white/50 font-body-md text-xs">{{ __('Punya pertanyaan? Saya siap membantu merencanakan liburan impian Anda.') }}</p>
                </div>
                <a target="_blank" rel="noopener"
                   href="https://wa.me/{{ \App\Helpers\ContactHelper::specialistDigits() }}?text={{ urlencode('Halo ' . ($settings['specialist_name'] ?? 'Sarah') . ', saya ingin tanya paket tour...') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-500 text-white rounded-xl font-label-caps text-[10px] uppercase tracking-widest transition shrink-0">
                    <span class="material-symbols-outlined text-[16px]">chat</span>
                    {{ __('WhatsApp') }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Journal/Blog -->
    @if($settings['show_blogs'] ?? true)
    <section class="py-16 md:py-24 max-w-7xl mx-auto px-5 md:px-8 bg-surface">
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-10 md:mb-14 gap-4">
            <div class="max-w-xl">
                <span class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.25em] text-secondary mb-3">
                    <span class="w-6 h-px bg-secondary"></span>{{ __('Cerita') }}
                </span>
                <h2 class="text-3xl md:text-5xl font-bold text-primary tracking-tight leading-[1.1]">{{ __('Jurnal Perjalanan') }}</h2>
            </div>
            <a class="text-[11px] font-bold uppercase tracking-widest text-secondary underline underline-offset-8 shrink-0" href="/tour/blog">{{ __('Lihat Semua Cerita') }}</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
            @foreach($blogs as $blog)
            <div class="group cursor-pointer" onclick="window.location.href='{{ route('tour.blog.detail', $blog->slug) }}'">
                <div class="aspect-[16/10] overflow-hidden rounded-lg mb-4 md:mb-6 shadow-md border border-slate-100 bg-slate-100">
                    <img alt="{{ $blog->translated_title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $blog->image_url }}"/>
                </div>
                <span class="font-label-caps text-[10px] text-secondary border border-secondary px-2 py-0.5 rounded-full uppercase tracking-wider mb-3 md:mb-4 inline-block">{{ strtoupper($blog->category ?? 'EKSPEDISI') }}</span>
                <h3 class="font-headline-md text-[20px] md:text-[22px] group-hover:text-secondary transition-colors duration-300 font-bold leading-tight">{{ $blog->translated_title }}</h3>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- FAQ -->
    <section class="py-16 md:py-24 bg-surface-container-low">
        <div class="max-w-3xl mx-auto px-5">
            <div class="text-center mb-10 md:mb-14">
                <h2 class="text-3xl md:text-5xl font-bold text-primary tracking-tight mb-4">{{ __('Pertanyaan Umum') }}</h2>
                <div class="w-12 h-0.5 bg-secondary mx-auto"></div>
            </div>
            @php
                $faqs = $settings['faqs'] ?? [
                    [
                        'q' => 'Bagaimana cara terbaik menuju Danau Toba dari Bandara Kualanamu (KNO)?',
                        'a' => 'Cara terbaik dan paling nyaman adalah menggunakan layanan transfer private (armada premium dengan supir pribadi) yang disediakan oleh Sujai Laketoba. Perjalanan darat memakan waktu sekitar 3.5 hingga 4 jam melalui jalan tol Medan-Tebing Tinggi, lalu dilanjutkan ke Parapat, pintu gerbang utama menuju Pulau Samosir.'
                    ],
                    [
                        'q' => 'Apakah makanan halal mudah ditemukan di sekitar Danau Toba?',
                        'a' => 'Ya, sangat mudah. Di Parapat dan Pulau Samosir (terutama daerah wisata Tuk-tuk dan Tomok), terdapat banyak restoran Muslim lokal yang bersertifikat halal atau menyajikan menu ramah Muslim seperti ikan mas bakar, ayam penyet, dan masakan khas Minang/Padang. Supir dan pemandu Sujai Laketoba akan selalu mengarahkan Anda ke tempat makan halal pilihan.'
                    ],
                    [
                        'q' => 'Mata uang apa yang digunakan, dan apakah kartu kredit diterima?',
                        'a' => 'Mata uang resmi yang digunakan adalah Rupiah Indonesia (IDR). Di kota besar seperti Medan, kartu kredit/debit internasional diterima secara luas. Namun, di sekitar Danau Toba, disarankan membawa uang tunai Rupiah untuk transaksi kecil di warung makan atau toko suvenir. Anda juga dapat melakukan pembayaran transfer bank internasional via Wise.'
                    ],
                    [
                        'q' => 'Kapan waktu terbaik untuk berkunjung ke Danau Toba?',
                        'a' => 'Danau Toba indah sepanjang tahun karena iklimnya yang sejuk di dataran tinggi. Waktu terbaik adalah antara bulan Mei hingga September saat curah hujan cenderung lebih rendah, memberikan pemandangan langit yang cerah dan danau yang biru. Hindari musim liburan nasional jika Anda menyukai suasana yang tenang.'
                    ],
                    [
                        'q' => 'Apakah tersedia paket kustom (private tour) untuk rombongan keluarga?',
                        'a' => 'Tentu saja! Semua paket wisata kami bersifat private dan dapat disesuaikan (customized) sepenuhnya sesuai keinginan Anda. Mulai dari pemilihan hotel premium, penyesuaian rute perjalanan, hingga akomodasi kebutuhan khusus untuk lansia atau anak-anak.'
                    ]
                ];
            @endphp
            <div class="space-y-2 md:space-y-4" x-data="{ selected: 1 }">
                @foreach($faqs as $index => $faq)
                <div class="bg-white px-5 md:px-6 rounded-2xl border border-slate-100 shadow-xs transition-shadow hover:shadow-sm">
                    <button @click="selected !== {{ $index + 1 }} ? selected = {{ $index + 1 }} : selected = null" class="w-full py-5 md:py-6 flex justify-between items-center gap-4 text-left focus:outline-none">
                        <span class="text-[15px] md:text-[18px] text-primary font-bold leading-snug">{{ __($faq['q']) }}</span>
                        <span :class="selected === {{ $index + 1 }} ? 'rotate-180 text-secondary' : ''" class="material-symbols-outlined transition-transform duration-300">expand_more</span>
                    </button>
                    <div x-show="selected === {{ $index + 1 }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="overflow-hidden">
                        <p class="pb-5 md:pb-6 font-body-md text-[14px] md:text-[16px] text-on-surface-variant leading-relaxed">
                            {{ __($faq['a']) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Cinema CTA -->
    <section class="py-16 md:py-32 px-5 md:px-8 bg-surface">
        <div class="max-w-7xl mx-auto bg-primary rounded-[2rem] md:rounded-[4rem] p-8 md:p-24 relative overflow-hidden shadow-[0_50px_100px_-20px_rgba(0,37,19,0.3)]">
            <div class="absolute inset-0 opacity-40">
                <img src="{{ $ctaImg }}" alt="{{ $ctaAlt ?? 'Call to action image' }}" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary/60 to-transparent"></div>
            
            <!-- Animated Circles Overlay -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-secondary/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/10 rounded-full blur-[120px]"></div>

            <div class="relative z-10 text-center lg:text-left max-w-4xl">
                <h2 class="text-3xl sm:text-4xl md:text-7xl font-bold text-white mb-6 md:mb-8 tracking-tight leading-[1.05] md:leading-[0.95]">
                    {{ __('Siap Untuk') }} <br/> <span class="text-white">{{ __('Petualangan Nyata?') }}</span>
                </h2>
                <p class="text-base md:text-xl text-slate-300 mb-8 md:mb-12 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    @php
                        $touristsCount = $settings['stat_customers'] ?? '1.500+';
                    @endphp
                    {{ __('Bergabunglah dengan') }} <span class="text-white font-bold">{{ $touristsCount }}</span> {{ __('wisatawan lainnya yang telah menemukan keindahan Sumatera Utara bersama kami.') }}
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-6 justify-center lg:justify-start">
                    <a href="/tour/packages" class="bg-white text-primary px-8 py-4 md:px-12 md:py-6 rounded-2xl md:rounded-[2rem] font-bold text-sm uppercase tracking-[0.2em] hover:bg-secondary hover:text-white transition duration-500 shadow-2xl flex items-center gap-3 group">
                        <span>{{ __('Pesan Paket Sekarang') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <div class="flex -space-x-4">
                        @php
                        $avatarPhotos = [
                            imageUrl('avatar_user_1'),
                            imageUrl('avatar_user_2'),
                            imageUrl('avatar_user_3'),
                            imageUrl('avatar_user_4'),
                        ];
                        @endphp
                        @foreach($avatarPhotos as $avatarUrl)
                            <img src="{{ $avatarUrl }}" class="w-14 h-14 rounded-full border-4 border-primary shadow-xl object-cover" alt="Pelanggan Sujai Laketoba">
                        @endforeach
                        <div class="w-14 h-14 rounded-full border-4 border-primary bg-secondary flex items-center justify-center text-white text-[10px] font-bold">
                            {{ $touristsCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
