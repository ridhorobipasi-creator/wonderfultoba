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
        <div class="relative w-full overflow-hidden h-[60vh] md:h-[85vh] min-h-[420px] md:min-h-[650px] max-h-[950px]">
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
                        <div class="absolute inset-0 bg-black/25 z-10"></div>
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
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2">
            @foreach($slides as $index => $slide)
            <button @click="activeSlide = {{ $index }}"
                    :class="activeSlide === {{ $index }} ? 'w-6 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"
                    class="h-2 rounded-full transition-all duration-300"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        @endif
    </div>
</section>

