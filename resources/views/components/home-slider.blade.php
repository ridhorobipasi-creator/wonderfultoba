@props(['settings' => [], 'packages' => []])

@php
    $slides = $settings['homepage_slides'] ?? [];

    if (empty($slides) && !empty($settings['hero_title'])) {
        $slides[] = [
            'title'    => $settings['hero_title'],
            'subtitle' => $settings['hero_subtitle'] ?? '',
            'image_url'=> $settings['hero_image_url'] ?? null,
            'cta_link' => $settings['hero_cta_link'] ?? '#rental',
        ];
    }

    if (empty($slides)) {
        $slides = [
            [
                'image_url'  => asset('images/slider/slider-1-85.png'),
                'image_webp' => asset('images/slider/slider-1-85.png.webp'),
                'alt'        => 'Sujai Tour Slider 1',
                'cta_link'   => '#rental',
            ],
            [
                'image_url'  => asset('images/slider/slider-2-99.png'),
                'image_webp' => asset('images/slider/slider-2-99.png.webp'),
                'alt'        => 'Sujai Tour Slider 2',
                'cta_link'   => '#rental',
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
            $slide['image_url']  = $imgUrl;
            $slide['image_webp'] = str_replace(['.png', '.jpg', '.jpeg'], '.webp', $imgUrl);
            $slide['alt']        = $slide['title'] ?? 'Slider';
            $slide['cta_link']   = $slide['cta_link'] ?? '#rental';
            return $slide;
        }, $slides);
    }
@endphp

<style>
    .hero-wrap { position: relative; width: 100%; overflow: hidden; height: 80vh; min-height: 500px; max-height: 850px; }
    @media (max-width: 768px) { .hero-wrap { height: 60vh; min-height: 380px; } }
    .hero-book-btn {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 30;
        display: inline-block;
        background: #E67E22;
        color: #fff;
        font-weight: 800;
        padding: 14px 48px;
        border-radius: 9999px;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        font-size: 15px;
        border: 2px solid rgba(255,255,255,0.3);
        box-shadow: 0 6px 24px rgba(230,126,34,0.6);
        transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
        white-space: nowrap;
        text-decoration: none;
    }
    .hero-book-btn:hover {
        background: #D35400;
        transform: translateX(-50%) scale(1.05);
        box-shadow: 0 8px 32px rgba(230,126,34,0.8);
    }
    .hero-features {
        background: #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0;
        padding: 0;
    }
    .hero-feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 28px;
        color: white;
        flex: 1;
        min-width: 160px;
        justify-content: center;
        border-right: 1px solid rgba(255,255,255,0.1);
    }
    .hero-feature-item:last-child { border-right: none; }
    .hero-feature-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #E67E22;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .hero-feature-title { font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; }
    .hero-feature-sub   { font-size: 11px; color: #aaa; }
    @media (max-width: 640px) {
        .hero-feature-item { min-width: 50%; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .hero-feature-item:nth-child(odd) { border-right: 1px solid rgba(255,255,255,0.1); }
        .hero-feature-item:nth-last-child(-n+2) { border-bottom: none; }
    }
</style>

{{-- ===== HERO SLIDER ===== --}}
<section class="relative w-full" x-data="{
    activeSlide: 0,
    totalSlides: {{ count($slides) }},
    touchStartX: 0,
    touchEndX: 0,
    init() {
        if (this.totalSlides > 1) {
            setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; }, 5000);
        }
    },
    onTouchStart(e) {
        this.touchStartX = e.changedTouches[0].screenX;
    },
    onTouchEnd(e) {
        this.touchEndX = e.changedTouches[0].screenX;
        const diff = this.touchStartX - this.touchEndX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
            } else {
                this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
            }
        }
    }
}" x-init="init()">

    <div class="hero-wrap"
         @touchstart="onTouchStart($event)"
         @touchend="onTouchEnd($event)">
        @foreach($slides as $index => $slide)
        <div class="absolute inset-0 w-full h-full"
             style="transition: opacity 0.8s ease-in-out;"
             x-bind:style="activeSlide === {{ $index }} ? 'opacity:1; z-index:10;' : 'opacity:0; z-index:5;'">
            <picture class="block w-full h-full">
                <source srcset="{{ $slide['image_webp'] }}" type="image/webp">
                <img src="{{ $slide['image_url'] }}"
                     alt="{{ $slide['alt'] }}"
                     class="w-full h-full"
                     style="object-fit: cover; object-position: center;">
            </picture>
            {{-- Overlay ringan --}}
            <div style="position:absolute;inset:0;background:rgba(0,0,0,0.15);z-index:1;"></div>
        </div>
        @endforeach

        {{-- BOOK NOW button — tengah bawah --}}
        <a href="{{ $slides[0]['cta_link'] ?? '#rental' }}" class="hero-book-btn">
            Book Now!
        </a>

        {{-- Arrow Prev / Next --}}
        @if(count($slides) > 1)
        <button type="button" @click="activeSlide = (activeSlide - 1 + totalSlides) % totalSlides"
                style="position:absolute;top:50%;left:16px;transform:translateY(-50%);z-index:40;width:40px;height:40px;border-radius:50%;background:rgba(0,0,0,0.35);color:white;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;backdrop-filter:blur(4px);"
                aria-label="Previous slide">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" @click="activeSlide = (activeSlide + 1) % totalSlides"
                style="position:absolute;top:50%;right:16px;transform:translateY(-50%);z-index:40;width:40px;height:40px;border-radius:50%;background:rgba(0,0,0,0.35);color:white;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;backdrop-filter:blur(4px);"
                aria-label="Next slide">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        {{-- Dot indicators --}}
        <div style="position:absolute;bottom:12px;left:50%;transform:translateX(-50%);z-index:40;display:flex;gap:8px;align-items:center;">
            @foreach($slides as $index => $slide)
            <button @click="activeSlide = {{ $index }}"
                    style="height:6px;border-radius:9999px;border:none;cursor:pointer;transition:all 0.3s;background:rgba(255,255,255,0.5);"
                    :style="activeSlide === {{ $index }} ? 'width:24px;background:white;' : 'width:6px;'"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ===== FEATURES STRIP — di bawah slider ===== --}}
    <div class="hero-features">
        <div class="hero-feature-item">
            <div class="hero-feature-icon">
                <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <p class="hero-feature-title">Booking Mudah</p>
                <p class="hero-feature-sub">Tanpa Ribet</p>
            </div>
        </div>
        <div class="hero-feature-item">
            <div class="hero-feature-icon">
                <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <p class="hero-feature-title">Proses Cepat</p>
                <p class="hero-feature-sub">CS 24/7 Fast Respon</p>
            </div>
        </div>
        <div class="hero-feature-item">
            <div class="hero-feature-icon">
                <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="hero-feature-title">Banyak Pilihan</p>
                <p class="hero-feature-sub">Paket Fleksibel</p>
            </div>
        </div>
        <div class="hero-feature-item">
            <div class="hero-feature-icon">
                <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="hero-feature-title">Harga Terbaik</p>
                <p class="hero-feature-sub">Terjangkau &amp; Premium</p>
            </div>
        </div>
    </div>

</section>
