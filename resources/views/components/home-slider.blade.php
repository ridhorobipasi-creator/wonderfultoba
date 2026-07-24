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
/* ===== HERO WRAPPER ===== */
.sujai-hero {
    position: relative;
    width: 100%;
    height: 88vh;
    min-height: 520px;
    max-height: 900px;
    overflow: hidden;
    touch-action: pan-y;
}
@media (max-width: 768px) {
    .sujai-hero { height: 70vh; min-height: 400px; }
}

/* ===== BOOK NOW BUTTON ===== */
.hero-book-btn {
    position: absolute;
    bottom: 90px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 30;
    display: inline-block;
    background: #E67E22;
    color: #fff;
    font-weight: 800;
    padding: 14px 52px;
    border-radius: 9999px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    font-size: 15px;
    border: 2px solid rgba(255,255,255,0.35);
    box-shadow: 0 6px 28px rgba(230,126,34,0.65);
    transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
    white-space: nowrap;
    text-decoration: none;
}
.hero-book-btn:hover {
    background: #D35400;
    transform: translateX(-50%) scale(1.05);
    box-shadow: 0 8px 36px rgba(230,126,34,0.85);
}
@media (max-width: 640px) {
    .hero-book-btn { bottom: 76px; padding: 12px 36px; font-size: 13px; }
}

/* ===== FEATURES STRIP — overlay di dalam hero ===== */
.hero-features-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 25;
    background: rgba(20, 20, 20, 0.88);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: stretch;
    justify-content: center;
    flex-wrap: nowrap;
}
.hero-feat-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    color: white;
    flex: 1;
    justify-content: center;
    border-right: 1px solid rgba(255,255,255,0.12);
}
.hero-feat-item:last-child { border-right: none; }
.hero-feat-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #E67E22;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.hero-feat-title { font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; line-height: 1.2; }
.hero-feat-sub   { font-size: 10px; color: #aaa; line-height: 1.2; }

@media (max-width: 640px) {
    .hero-features-bar { flex-wrap: wrap; }
    .hero-feat-item { min-width: 50%; flex: none; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.1); padding: 10px 16px; }
    .hero-feat-item:nth-child(odd) { border-right: 1px solid rgba(255,255,255,0.12); }
    .hero-feat-item:nth-last-child(-n+2) { border-bottom: none; }
    .hero-feat-title { font-size: 10px; }
    .hero-feat-sub { font-size: 9px; }
}

/* ===== DOT INDICATORS ===== */
.hero-dots {
    position: absolute;
    bottom: 58px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 35;
    display: flex;
    gap: 7px;
    align-items: center;
}
@media (max-width: 640px) {
    .hero-dots { bottom: 48px; }
}
.hero-dot {
    height: 6px;
    border-radius: 9999px;
    border: none;
    cursor: pointer;
    transition: all 0.35s;
    background: rgba(255,255,255,0.45);
    width: 6px;
    padding: 0;
}
.hero-dot.active { width: 22px; background: white; }
</style>

{{-- ===== HERO SLIDER ===== --}}
<section class="sujai-hero" 
    x-data="{
        activeSlide: 0,
        totalSlides: {{ count($slides) }},
        touchStartX: 0,
        touchEndX: 0,
        autoTimer: null,
        init() {
            if (this.totalSlides > 1) {
                this.autoTimer = setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                }, 5000);
            }
        },
        goTo(i) { this.activeSlide = i; },
        prev() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
        next() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
        onTouchStart(e) { this.touchStartX = e.changedTouches[0].clientX; },
        onTouchEnd(e) {
            this.touchEndX = e.changedTouches[0].clientX;
            const diff = this.touchStartX - this.touchEndX;
            if (Math.abs(diff) > 50) { diff > 0 ? this.next() : this.prev(); }
        }
    }"
    x-init="init()"
    @touchstart.passive="onTouchStart($event)"
    @touchend.passive="onTouchEnd($event)">

    {{-- ===== SLIDES ===== --}}
    @foreach($slides as $index => $slide)
    <div class="absolute inset-0 w-full h-full"
         style="transition: opacity 0.9s ease-in-out;"
         x-bind:style="activeSlide === {{ $index }} ? 'opacity:1;z-index:10;' : 'opacity:0;z-index:5;'">
        <a href="{{ $slide['cta_link'] }}" class="block w-full h-full" style="text-decoration:none;" tabindex="-1">
            <picture class="block w-full h-full">
                <source srcset="{{ $slide['image_webp'] ?? $slide['image_url'] }}" type="image/webp">
                <img src="{{ $slide['image_url'] }}"
                     alt="{{ $slide['alt'] }}"
                     class="w-full h-full"
                     style="object-fit:cover; object-position:center;"
                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
            </picture>
            <div style="position:absolute;inset:0;background:rgba(0,0,0,0.18);z-index:1;"></div>
        </a>
    </div>
    @endforeach

    {{-- ===== BOOK NOW ===== --}}
    <a href="{{ $slides[0]['cta_link'] ?? '#rental' }}" class="hero-book-btn">
        Book Now!
    </a>

    {{-- ===== DOT INDICATORS ===== --}}
    @if(count($slides) > 1)
    <div class="hero-dots">
        @foreach($slides as $index => $slide)
        <button class="hero-dot"
                :class="activeSlide === {{ $index }} ? 'active' : ''"
                @click="goTo({{ $index }})"
                aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>

    {{-- ===== ARROW BUTTONS ===== --}}
    <button type="button" @click="prev()"
            style="position:absolute;top:50%;left:16px;transform:translateY(-50%);z-index:40;width:40px;height:40px;border-radius:50%;background:rgba(0,0,0,0.38);color:white;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;backdrop-filter:blur(4px);margin-top:-30px;"
            aria-label="Slide sebelumnya">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" @click="next()"
            style="position:absolute;top:50%;right:16px;transform:translateY(-50%);z-index:40;width:40px;height:40px;border-radius:50%;background:rgba(0,0,0,0.38);color:white;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;backdrop-filter:blur(4px);margin-top:-30px;"
            aria-label="Slide berikutnya">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>
    @endif

    {{-- ===== FEATURES BAR (OVERLAY di dalam hero, bawah) ===== --}}
    <div class="hero-features-bar">
        <div class="hero-feat-item">
            <div class="hero-feat-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <p class="hero-feat-title">Booking Mudah</p>
                <p class="hero-feat-sub">Tanpa Ribet</p>
            </div>
        </div>
        <div class="hero-feat-item">
            <div class="hero-feat-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <p class="hero-feat-title">Proses Cepat</p>
                <p class="hero-feat-sub">CS 24/7 Fast Respon</p>
            </div>
        </div>
        <div class="hero-feat-item">
            <div class="hero-feat-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="hero-feat-title">Banyak Pilihan</p>
                <p class="hero-feat-sub">Paket Fleksibel</p>
            </div>
        </div>
        <div class="hero-feat-item">
            <div class="hero-feat-icon">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="hero-feat-title">Harga Terbaik</p>
                <p class="hero-feat-sub">Terjangkau &amp; Premium</p>
            </div>
        </div>
    </div>

</section>
