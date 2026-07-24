@props(['settings' => [], 'packages' => []])

@php
    $slides = $settings['homepage_slides'] ?? [];

    if (empty($slides) && !empty($settings['hero_title'])) {
        $slides[] = [
            'title' => $settings['hero_title'],
            'subtitle' => $settings['hero_subtitle'] ?? '',
            'image_url' => $settings['hero_image_url'] ?? null,
            'cta_link' => $settings['hero_cta_link'] ?? '#rental',
        ];
    }

    if (empty($slides)) {
        $slides = [
            [
                'image_url' => asset('images/slider/slider-1-85.png'),
                'image_webp' => asset('images/slider/slider-1-85.png.webp'),
                'title' => 'Liburan Seru Bareng Sujai Tour',
                'subtitle' => 'Buat Momen Liburan Berkesan Bersama Sujai Danau Toba',
                'alt' => 'Slider 1',
                'cta_link' => '#rental'
            ],
            [
                'image_url' => asset('images/slider/slider-2-99.png'),
                'image_webp' => asset('images/slider/slider-2-99.png.webp'),
                'title' => 'Paket Wisata Danau Toba Terlengkap',
                'subtitle' => 'Harga Terbaik dengan Fasilitas Premium dan Pelayanan Maksimal',
                'alt' => 'Slider 2',
                'cta_link' => '#rental'
            ],
        ];
    } else {
        $slides = array_map(function ($slide) {
            $slide = (array) $slide;
            $url = $slide['image_url'] ?? null;
            if (is_string($url)) {
                $url = preg_replace('/^(\/?storage\/)+/', '', $url);
            }
            $imgUrl = imageUrl($url);
            $slide['image_url'] = $imgUrl;
            $slide['image_webp'] = str_replace(['.png', '.jpg', '.jpeg'], '.webp', $imgUrl);
            $slide['alt'] = $slide['title'] ?? 'Slider';
            $slide['cta_link'] = $slide['cta_link'] ?? '#rental';
            return $slide;
        }, $slides);
    }
@endphp

<section class="relative w-full overflow-hidden">
    <div id="carouselExample" class="relative" x-data="{
        activeSlide: 0,
        totalSlides: {{ count($slides) }},
        timer: null,
        init() {
            if (this.totalSlides > 1) {
                this.timer = setInterval(() => { this.next(); }, 4000);
            }
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
        }
    }">
        {{-- Responsive Height Style for Production Without NPM Build --}}
        <style>
            .hero-slider-container {
                height: 50vh;
                min-height: 380px;
                max-height: 800px;
            }
            @media (min-width: 640px) { .hero-slider-container { height: 65vh; min-height: 480px; } }
            @media (min-width: 768px) { .hero-slider-container { height: 75vh; min-height: 600px; } }
            @media (min-width: 1024px) { .hero-slider-container { height: 80vh; min-height: 600px; } }
        </style>
        {{-- Slides --}}
        <div class="relative w-full overflow-hidden hero-slider-container">
            @foreach($slides as $index => $slide)
                <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out"
                     x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition-opacity duration-1000"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-1000"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <a href="{{ $slide['cta_link'] }}" class="block w-full h-full group">
                        {{-- Overlay tipis agar poster tetap terlihat --}}
                        <div class="absolute inset-0 bg-black/20 z-10"></div>
                        <picture class="block w-full h-full">
                            <source srcset="{{ $slide['image_webp'] }}" type="image/webp">
                            <img src="{{ $slide['image_url'] }}" alt="{{ $slide['alt'] }}"
                                 class="w-full h-full object-cover object-top">
                        </picture>
                        {{-- Hanya tombol BOOK NOW --}}
                        <div class="absolute inset-0 z-20 flex items-end justify-center pb-20">
                            <span class="inline-block bg-toba-orange hover:bg-orange-600 text-white font-bold py-3 md:py-4 px-10 md:px-14 rounded-full shadow-[0_4px_20px_rgba(230,126,34,0.7)] transition-all duration-300 hover:scale-105 uppercase tracking-widest text-sm md:text-base border border-orange-400">
                                BOOK NOW!
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Navigation arrows --}}
        @if(count($slides) > 1)
        <button class="absolute top-1/2 left-4 -translate-y-1/2 z-30 w-10 h-10 flex items-center justify-center bg-black/30 hover:bg-black/50 text-white rounded-full transition backdrop-blur-sm"
                type="button" @click="prev()" aria-label="Previous slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button class="absolute top-1/2 right-4 -translate-y-1/2 z-30 w-10 h-10 flex items-center justify-center bg-black/30 hover:bg-black/50 text-white rounded-full transition backdrop-blur-sm"
                type="button" @click="next()" aria-label="Next slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        {{-- Dot indicators --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2">
            @foreach($slides as $index => $slide)
            <button @click="activeSlide = {{ $index }}"
                    :class="activeSlide === {{ $index }} ? 'w-6 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"
                    class="h-2 rounded-full transition-all duration-300 shadow-sm"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        @endif
        {{-- ZazaTour Style Features Overlay Strip (Desktop Only) --}}
        <div class="hidden lg:flex absolute bottom-8 right-12 z-30 bg-black/50 backdrop-blur-md rounded-2xl p-5 gap-8 border border-white/20 shadow-2xl">
            <div class="flex items-center gap-4 text-white">
                <div class="w-12 h-12 rounded-full bg-toba-orange flex items-center justify-center shadow-[0_0_15px_rgba(230,126,34,0.5)]">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-sm tracking-wide uppercase">Booking Mudah</p>
                    <p class="text-gray-300 text-xs">Tanpa Ribet</p>
                </div>
            </div>
            <div class="w-px h-12 bg-white/30"></div>
            <div class="flex items-center gap-4 text-white">
                <div class="w-12 h-12 rounded-full bg-toba-orange flex items-center justify-center shadow-[0_0_15px_rgba(230,126,34,0.5)]">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-sm tracking-wide uppercase">Proses Cepat</p>
                    <p class="text-gray-300 text-xs">CS 24/7 Fast Respon</p>
                </div>
            </div>
            <div class="w-px h-12 bg-white/30"></div>
            <div class="flex items-center gap-4 text-white">
                <div class="w-12 h-12 rounded-full bg-toba-orange flex items-center justify-center shadow-[0_0_15px_rgba(230,126,34,0.5)]">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-sm tracking-wide uppercase">Banyak Pilihan</p>
                    <p class="text-gray-300 text-xs">Paket Fleksibel</p>
                </div>
            </div>
            <div class="w-px h-12 bg-white/30"></div>
            <div class="flex items-center gap-4 text-white">
                <div class="w-12 h-12 rounded-full bg-toba-orange flex items-center justify-center shadow-[0_0_15px_rgba(230,126,34,0.5)]">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-sm tracking-wide uppercase">Harga Terbaik</p>
                    <p class="text-gray-300 text-xs">Terjangkau & Premium</p>
                </div>
            </div>
        </div>
    </div>
</section>

