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
                'alt' => 'Slider 1',
                'cta_link' => '#rental'
            ],
            [
                'image_url' => asset('images/slider/slider-2-99.png'),
                'image_webp' => asset('images/slider/slider-2-99.png.webp'),
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
        {{-- Slides --}}
        <div class="relative w-full overflow-hidden h-[360px] sm:h-[440px] md:h-[500px] lg:h-[560px] max-h-[600px]">
            @foreach($slides as $index => $slide)
                <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out"
                     x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition-opacity duration-1000"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-1000"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <a href="{{ $slide['cta_link'] }}" class="block w-full h-full">
                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-black/20 z-10"></div>
                        <picture class="block w-full h-full">
                            <source srcset="{{ $slide['image_webp'] }}" type="image/webp">
                            <img src="{{ $slide['image_url'] }}" alt="{{ $slide['alt'] }}"
                                 class="w-full h-full object-cover object-center">
                        </picture>
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
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 lg:left-8 lg:translate-x-0 z-30 flex items-center gap-2">
            @foreach($slides as $index => $slide)
            <button @click="activeSlide = {{ $index }}"
                    :class="activeSlide === {{ $index }} ? 'w-6 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"
                    class="h-2 rounded-full transition-all duration-300 shadow-sm"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        @endif

        {{-- ZazaTour Style Features Overlay Strip --}}
        <div class="hidden lg:flex absolute bottom-4 right-8 z-30 items-center gap-6 bg-black/35 backdrop-blur-md px-6 py-2.5 rounded-full border border-white/20 text-white text-xs font-bold shadow-xl">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-toba-orange" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Booking Mudah</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-toba-orange" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Proses Cepat</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-toba-orange" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Banyak Pilihan</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-toba-orange" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Harga Terjangkau</span>
            </div>
        </div>
    </div>
</section>

