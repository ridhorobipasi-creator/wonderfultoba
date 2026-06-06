@props(['settings' => [], 'packages' => []])

@php
    $slides = $settings['homepage_slides'] ?? [];
    
    // If no explicit slides, but Hero settings exist, use them as the only slide
    if (empty($slides) && !empty($settings['hero_title'])) {
        $slides[] = [
            'title' => $settings['hero_title'],
            'subtitle' => $settings['hero_subtitle'] ?? '',
            'image_url' => $settings['hero_image_url'] ?? null,
            'location' => 'Sujai Laketoba',
            'price' => 0,
            'cta_link' => $settings['hero_cta_link'] ?? '/tour/packages',
            'cta_text' => $settings['hero_cta_text'] ?? 'Lihat Paket'
        ];
    }

    // Fallback added back to prevent slider from disappearing when settings are empty
    if (empty($slides)) {
        $slides[] = [
            'title' => 'Eksplorasi Keindahan',
            'subtitle' => 'Danau Toba',
            'image_url' => null,
            'location' => 'Sujai Laketoba',
            'price' => 0,
            'cta_link' => '/tour/packages',
            'cta_text' => 'Lihat Paket'
        ];
    }

    // We now use the global imageUrl() helper defined in ImageHelper.php
    $preparedSlides = array_values(array_map(function($slide) {
        $slide = (array) $slide;
        $url = $slide['image_url'] ?? null;
        
        // Ensure we don't have double storage/storage/
        if (is_string($url)) {
            $url = preg_replace('/^(\/?storage\/)+/', '', $url);
        }
        
        $slide['image_url'] = imageUrl($url);
        return $slide;
    }, $slides));

    $totalOriginal = count($preparedSlides);
    
    // We need enough clones to fill the 3-card preview container + buffer for fast clicking
    $clonesNeeded = 6;
    
    // Helper to generate repeated array elements to reach needed count
    $generateClones = function($array, $count, $fromEnd = false) {
        if (empty($array)) return [];
        $result = [];
        while(count($result) < $count) {
            $result = array_merge($result, $array);
        }
        if ($fromEnd) {
            return array_slice($result, - $count);
        }
        return array_slice($result, 0, $count);
    };

    $startClones = $generateClones($preparedSlides, $clonesNeeded, true);
    $endClones = $generateClones($preparedSlides, $clonesNeeded, false);
    
    $infiniteSlides = array_merge($startClones, $preparedSlides, $endClones);
    $clonesCount = $clonesNeeded;
    $startIndex = $clonesCount;

    // LCP image = first real slide (shown at $startIndex). Preload it in <head> so the
    // browser fetches it in parallel with app.js instead of waiting for Alpine to render the <img>.
    $lcpImage = $preparedSlides[0]['image_url'] ?? null;
@endphp

@if($lcpImage)
@push('head')
    <link rel="preload" as="image" href="{{ $lcpImage }}" fetchpriority="high">
@endpush
@endif

<section id="home-hero-slider" class="relative w-full h-screen min-h-[600px] bg-slate-950 overflow-hidden" 
    @touchstart="handleTouchStart($event)"
    @touchend="handleTouchEnd($event)"
    x-data="{ 
        activeIndex: {{ $startIndex }},
        totalOriginal: {{ $totalOriginal }},
        clonesCount: {{ $clonesCount }},
        isTransitioning: true,
        autoplayInterval: null,
        touchStartX: 0,
        isDraggingCards: false,
        dragStartX: 0,
        dragOffsetX: 0,
        dragPointerId: null,
        previewStep: 204,
        slides: @js($infiniteSlides),
        
        init() {
            this.$nextTick(() => {
                this.computePreviewStep();
                window.addEventListener('resize', () => this.computePreviewStep());
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) this.stopAutoplay();
                    else if (this.totalOriginal > 1) this.startAutoplay();
                });
                window.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') return this.prev();
                    if (e.key === 'ArrowRight') return this.next();
                    if (e.key === ' ' || e.key === 'Spacebar') {
                        e.preventDefault();
                        if (this.autoplayInterval) this.stopAutoplay(); else if (this.totalOriginal > 1) this.startAutoplay();
                    }
                });
            });

            if (this.totalOriginal > 1) {
                this.startAutoplay();
            }
        },

        computePreviewStep() {
            this.$nextTick(() => {
                const preview = document.querySelector('#home-hero-slider .card-preview');
                const container = document.querySelector('#home-hero-slider .card-container');
                if (!preview) return;
                const rect = preview.getBoundingClientRect();
                let gap = 0;
                if (container) {
                    const style = getComputedStyle(container);
                    gap = parseFloat(style.columnGap) || parseFloat(style.gap) || 0;
                }
                this.previewStep = Math.round(rect.width + gap);
            });
        },
        
        startAutoplay() {
            this.stopAutoplay();
            this.autoplayInterval = setInterval(() => {
                this.next();
            }, 8000); 
        },
        
        stopAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
                this.autoplayInterval = null;
            }
        },
        
        next() {
            this.isTransitioning = true;
            this.activeIndex++;
            this.startAutoplay();
        },
        
        prev() {
            this.isTransitioning = true;
            this.activeIndex--;
            this.startAutoplay();
        },
        
        goTo(index) {
            this.isTransitioning = true;
            this.activeIndex = index + this.clonesCount;
            this.startAutoplay();
        },

        beginCardDrag(e) {
            if (e.pointerType === 'mouse' && e.button !== 0) return;
            try { e.target.setPointerCapture(e.pointerId); } catch (err) {}
            this.isDraggingCards = true;
            this.dragPointerId = e.pointerId;
            this.dragStartX = e.clientX;
            this.dragOffsetX = 0;
            this.isTransitioning = false;
            this.stopAutoplay();
        },

        moveCardDrag(e) {
            if (!this.isDraggingCards) return;
            this.dragOffsetX = e.clientX - this.dragStartX;
        },

        endCardDrag(e) {
            if (!this.isDraggingCards) return;
            try { if (this.dragPointerId != null) e.target.releasePointerCapture(this.dragPointerId); } catch (err) {}

            const threshold = 60;
            const delta = this.dragOffsetX;

            this.isDraggingCards = false;
            this.dragOffsetX = 0;
            this.dragPointerId = null;

            if (Math.abs(delta) > threshold) {
                if (delta < 0) this.next(); else this.prev();
                return;
            }

            // restore transition and resume autoplay
            this.isTransitioning = true;
            this.startAutoplay();
        },

        handleTouchStart(e) {
            this.touchStartX = e.touches[0].clientX;
        },

        handleTouchEnd(e) {
            let touchEndX = e.changedTouches[0].clientX;
            if (this.touchStartX - touchEndX > 50) this.next();
            if (this.touchStartX - touchEndX < -50) this.prev();
        },

        handleTransitionEnd() {
            if (this.activeIndex >= this.totalOriginal + this.clonesCount || this.activeIndex < this.clonesCount) {
                this.isTransitioning = false;
                let offset = this.activeIndex - this.clonesCount;
                let realIndex = ((offset % this.totalOriginal) + this.totalOriginal) % this.totalOriginal + this.clonesCount;
                this.activeIndex = realIndex;
                this.$nextTick(() => {
                    // Force reflow to apply the transform without transition
                    void document.querySelector('#home-hero-slider .carousel-strip').offsetHeight;
                    this.isTransitioning = true;
                });
            }
        },
    }">
    
    <style>
        .hero-slide-bg {
            position: absolute;
            inset: 0;
            z-index: 10;
        }
        
        .hero-slide-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.5s cubic-bezier(0.23, 1, 0.32, 1);
        }
        
        .active-slide img {
            transform: scale(1.1) translateX(20px);
        }

        .card-preview {
            width: 140px;
            height: 220px;
            border-radius: 16px;
            transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1);
            cursor: pointer;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.1);
            background: #0f172a;
        }

        .card-preview:hover {
            transform: translateY(-15px) scale(1.05);
            border-color: rgba(255,255,255,0.4);
            z-index: 50;
        }

        .active-card {
            border-color: #fbbf24 !important;
            box-shadow: 0 0 40px rgba(251, 191, 36, 0.4);
            transform: translateY(-10px);
        }

        @media (max-width: 768px) {
            .card-container { display: none !important; }
            .hero-content { padding-top: 60px; text-align: center; left: 0 !important; width: 100% !important; padding-left: 20px; padding-right: 20px; }
            .hero-content h1 { font-size: 2.5rem !important; }
        }

    /* Smooth Carousel Transition */
        .carousel-strip {
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.23, 1, 0.32, 1);
        }
    </style>

    {{-- Initial LCP Fallback (Removes itself once Alpine loads) --}}
    @if(count($preparedSlides) > 0)
    <div class="absolute inset-0 z-20" x-init="$el.remove()">
        <img src="{{ $preparedSlides[0]['image_url'] }}" alt="{{ $preparedSlides[0]['title'] }}"
             fetchpriority="high" loading="eager" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/20 to-transparent"></div>
        
        <div class="absolute inset-0 z-30">
            <div class="w-full h-full max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-12 pt-12 md:pt-20 px-6 md:px-16 lg:px-24">
                <div class="w-full lg:w-1/2 text-white">
                    <span class="text-toba-accent uppercase tracking-[0.3em] font-black text-[10px] mb-4 block">{{ $preparedSlides[0]['location'] }}</span>
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-6 leading-none tracking-tighter uppercase">{{ $preparedSlides[0]['title'] }}</h1>
                    <p class="text-slate-300 text-lg md:text-xl mb-10 max-w-xl opacity-80 font-medium line-clamp-2 md:line-clamp-3">{{ $preparedSlides[0]['subtitle'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Carousel Strip --}}
    <div class="absolute inset-0 z-10">
        <div class="flex h-full carousel-strip" 
             @transitionend="handleTransitionEnd()"
             :class="isTransitioning ? 'duration-[1000ms]' : 'duration-0'"
             :style="'width: ' + (slides.length * 100) + '%; transform: translateX(-' + (activeIndex * (100 / slides.length)) + '%)'">
            
            <template x-for="(slide, i) in slides" :key="'slide-' + i">
                <div class="h-full relative shrink-0 flex items-center px-6 md:px-16 lg:px-24 overflow-hidden" 
                     :class="activeIndex == i ? 'active-slide' : ''"
                     :style="'width: ' + (100 / slides.length) + '%'">
                    {{-- Background --}}
                      <div class="absolute inset-0 -z-10">
                          <img :src="slide.image_url" :alt="slide.title"
                               :onerror="`this.onerror=null; this.src='${'{{ asset('images/home/tour.webp') }}'}'`"
                               :fetchpriority="(activeIndex == i || slide.image_url == '{{ $lcpImage }}') ? 'high' : 'auto'"
                               :loading="(activeIndex == i || slide.image_url == '{{ $lcpImage }}') ? 'eager' : 'lazy'"
                               class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/20 to-transparent"></div>
                    </div>

                    {{-- Content --}}
                    <div class="w-full max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-12 pt-12 md:pt-20">
                        <div class="hero-content w-full lg:w-1/2 text-white">
                            <div :class="activeIndex == i ? 'opacity-100 translate-x-0 scale-100' : 'opacity-0 -translate-x-12 scale-95'" 
                                 class="transition-all duration-1000 delay-300 ease-out">
                                <span class="text-toba-accent uppercase tracking-[0.3em] font-black text-[10px] mb-4 block" x-text="slide.location"></span>
                                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-6 leading-none tracking-tighter uppercase" x-text="slide.title"></h1>
                                <p class="text-slate-300 text-lg md:text-xl mb-10 max-w-xl opacity-80 font-medium line-clamp-2 md:line-clamp-3" x-text="slide.subtitle"></p>
                                
                                <div class="flex items-center gap-8 mb-12 lg:justify-start justify-center" x-show="slide.type !== 'blog' && slide.price > 0">
                                    <div class="flex flex-col text-left">
                                        <span class="text-[10px] text-toba-accent uppercase tracking-widest mb-1 font-bold">{{ __('Investasi Wisata') }}</span>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-2xl font-black" x-text="AppCurrency.format(slide.price)"></span>
                                        </div>
                                    </div>
                                    <a :href="slide.cta_link" class="cta-primary px-8 py-4 md:px-10 md:py-5">
                                        <span>Explore Now</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                                <div class="flex items-center gap-8 mb-12 lg:justify-start justify-center" x-show="slide.type === 'blog' || !slide.price || slide.price == 0">
                                    <a :href="slide.cta_link" class="cta-secondary px-8 py-4 md:px-10 md:py-5">
                                        <span x-text="slide.cta_text || 'Baca Selengkapnya'"></span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- Placeholder for layout balance on large screens --}}
                        <div class="hidden lg:block w-1/2"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Overlay Controls & Indicators --}}
    <div class="absolute inset-0 z-30 pointer-events-none flex flex-col justify-end">
        <div class="w-full max-w-7xl mx-auto px-6 md:px-16 lg:px-24 pb-10 flex flex-col lg:flex-row items-end justify-between gap-8">

            {{-- Left: Minimal Dot Indicators + Counter --}}
            <div class="hidden lg:flex flex-col gap-4 pointer-events-auto">

                {{-- Slide Counter --}}
                <div class="flex items-baseline gap-1">
                    <span class="text-white text-2xl font-black leading-none tabular-nums" 
                          x-text="String(((activeIndex - clonesCount) % totalOriginal + totalOriginal) % totalOriginal + 1).padStart(2,'0')"></span>
                    <span class="text-white/30 text-sm font-medium">/</span>
                    <span class="text-white/40 text-sm font-medium" x-text="String(totalOriginal).padStart(2,'0')"></span>
                </div>

                {{-- Thin progress line dots --}}
                <div class="flex items-center gap-2">
                    <template x-for="i in totalOriginal" :key="'dot-' + i">
                        <div 
                            class="h-[3px] rounded-full transition-all duration-500 cursor-pointer"
                            @click="goTo(i-1)"
                            :class="((activeIndex - clonesCount) % totalOriginal + totalOriginal) % totalOriginal == (i-1) 
                                ? 'w-8 bg-white' 
                                : 'w-4 bg-white/30 hover:bg-white/60'"
                        ></div>
                    </template>
                </div>

                {{-- Prev / Next ghost buttons --}}
                <div class="flex items-center gap-3">
                    <button @click="prev()" aria-label="Previous slide"
                        class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white hover:text-slate-900 hover:border-white transition-all duration-300 backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button @click="next()" aria-label="Next slide"
                        class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white hover:text-slate-900 hover:border-white transition-all duration-300 backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Card Preview Container Wrapper (Viewport for 3 cards) --}}
            <div class="hidden lg:block w-[468px] overflow-hidden pointer-events-auto py-4">
                <div class="card-container flex items-center gap-6 carousel-strip"
                     @pointerdown="beginCardDrag($event)"
                     @pointermove="moveCardDrag($event)"
                     @pointerup="endCardDrag()"
                     @pointerleave="endCardDrag()"
                     @pointercancel="endCardDrag()"
                     style="touch-action: pan-y; user-select: none;"
                     :class="isTransitioning ? 'duration-[800ms]' : 'duration-0'"
                     :style="'transform: translateX(calc(-' + (activeIndex * previewStep) + 'px + ' + dragOffsetX + 'px))'">
                    <template x-for="(slide, i) in slides" :key="'card-' + i">
                        <div 
                            class="card-preview shrink-0 relative group overflow-hidden"
                            :class="activeIndex == i ? 'active-card' : 'opacity-40 scale-95 hover:opacity-70'"
                            @click="isTransitioning = true; activeIndex = i; startAutoplay()"
                        >
                               <img :src="slide.image_url" :alt="slide.title || 'Slide image'"
                                   :onerror="`this.onerror=null; this.src='${'{{ asset('images/home/tour.webp') }}'}'`"
                                   loading="lazy"
                                   class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white translate-y-2 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all z-10">
                                <p class="text-[9px] font-black uppercase tracking-widest mb-0.5 text-white/60" x-text="slide.location"></p>
                                <h3 class="text-[11px] font-black uppercase leading-tight line-clamp-2" x-text="slide.title"></h3>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>
