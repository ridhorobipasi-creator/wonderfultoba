@props(['settings' => [], 'packages' => []])

@php
    $slides = $settings['homepage_slides'] ?? [];
    
    // If no explicit slides, but Hero settings exist, use them as the only slide
    if (empty($slides) && !empty($settings['hero_title'])) {
        $slides[] = [
            'title' => $settings['hero_title'],
            'subtitle' => $settings['hero_subtitle'] ?? '',
            'image_url' => $settings['hero_image_url'] ?? null,
            'location' => 'Sujailake Toba',
            'price' => 0,
            'cta_link' => $settings['hero_cta_link'] ?? '/packages',
            'cta_text' => $settings['hero_cta_text'] ?? 'Lihat Paket'
        ];
    }

    // Fallback removed to allow clean reset as requested.
    if (empty($slides)) {
        return; // Don't render anything if there are no slides
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
    
    // For seamless looping, we clone the first few and last few slides
    // [Last 3] + [Original] + [First 3]
    $clonesCount = $totalOriginal >= 3 ? 3 : $totalOriginal;
    $startClones = array_slice($preparedSlides, -$clonesCount);
    $endClones = array_slice($preparedSlides, 0, $clonesCount);
    $infiniteSlides = array_merge($startClones, $preparedSlides, $endClones);
    $startIndex = $clonesCount;
@endphp

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
        slides: {{ json_encode($infiniteSlides) }},
        
        init() {
            if (this.totalOriginal > 1) {
                this.startAutoplay();
                
                // Watcher for seamless infinite jump
                this.$watch('activeIndex', (val) => {
                    // Jump forward: from end clone to real start
                    if (val >= this.totalOriginal + this.clonesCount) {
                        setTimeout(() => {
                            this.isTransitioning = false;
                            this.activeIndex = this.clonesCount;
                            setTimeout(() => { this.isTransitioning = true; }, 50);
                        }, 1000);
                    }
                    // Jump backward: from start clone to real end
                    if (val <= 0) {
                        setTimeout(() => {
                            this.isTransitioning = false;
                            this.activeIndex = this.totalOriginal + this.clonesCount - 1;
                            setTimeout(() => { this.isTransitioning = true; }, 50);
                        }, 1000);
                    }
                });
            }
        },
        
        startAutoplay() {
            this.stopAutoplay();
            this.autoplayInterval = setInterval(() => {
                this.next();
            }, 8000); 
        },
        
        stopAutoplay() {
            if (this.autoplayInterval) clearInterval(this.autoplayInterval);
        },
        
        next() {
            if (this.activeIndex >= this.totalOriginal + this.clonesCount) return;
            this.isTransitioning = true;
            this.activeIndex++;
            this.startAutoplay();
        },
        
        prev() {
            if (this.activeIndex <= 0) return;
            this.isTransitioning = true;
            this.activeIndex--;
            this.startAutoplay();
        },
        
        goTo(index) {
            this.isTransitioning = true;
            this.activeIndex = index + this.clonesCount;
            this.startAutoplay();
        },

        handleTouchStart(e) {
            this.touchStartX = e.touches[0].clientX;
        },

        handleTouchEnd(e) {
            let touchEndX = e.changedTouches[0].clientX;
            if (this.touchStartX - touchEndX > 50) this.next();
            if (this.touchStartX - touchEndX < -50) this.prev();
        }
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
            width: 180px;
            height: 280px;
            border-radius: 24px;
            transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1);
            cursor: pointer;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            border: 2px solid rgba(255,255,255,0.05);
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

    {{-- Main Carousel Strip --}}
    <div class="absolute inset-0 z-10">
        <div class="flex h-full carousel-strip" 
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
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/20 to-transparent"></div>
                    </div>

                    {{-- Content --}}
                    <div class="w-full max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-12 pt-12 md:pt-20">
                        <div class="hero-content w-full lg:w-1/2 text-white">
                            <div :class="activeIndex == i ? 'opacity-100 translate-x-0 scale-100' : 'opacity-0 -translate-x-12 scale-95'" 
                                 class="transition-all duration-1000 delay-300 ease-out">
                                <span class="text-lake-light uppercase tracking-[0.3em] font-black text-[10px] mb-4 block" x-text="slide.location"></span>
                                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-6 leading-none tracking-tighter uppercase" x-text="slide.title"></h1>
                                <p class="text-slate-300 text-lg md:text-xl mb-10 max-w-xl opacity-80 font-medium line-clamp-2 md:line-clamp-3" x-text="slide.subtitle"></p>
                                
                                <div class="flex items-center gap-8 mb-12 lg:justify-start justify-center" x-show="slide.type !== 'blog' && slide.price > 0">
                                    <div class="flex flex-col text-left">
                                        <span class="text-[10px] text-lake-light uppercase tracking-widest mb-1 font-bold">Investasi Wisata</span>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-2xl font-black" x-text="'Rp ' + (slide.price ? new Intl.NumberFormat('id-ID').format(slide.price) : '-')"></span>
                                        </div>
                                    </div>
                                    <a :href="slide.cta_link" class="inline-flex items-center gap-4 bg-lake-blue text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-500 shadow-2xl hover:-translate-y-1">
                                        <span>Explore Now</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                                <div class="flex items-center gap-8 mb-12 lg:justify-start justify-center" x-show="slide.type === 'blog' || !slide.price || slide.price == 0">
                                    <a :href="slide.cta_link" class="inline-flex items-center gap-4 bg-toba-accent text-slate-900 px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-white transition-all duration-500 shadow-2xl hover:-translate-y-1">
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
        <div class="w-full max-w-7xl mx-auto px-6 md:px-16 lg:px-24 pb-20 flex flex-col lg:flex-row items-end justify-between gap-12">
            
            <div class="hidden lg:block pointer-events-auto">
                {{-- Nav Controls --}}
                <div class="flex items-center gap-6 bg-white/5 backdrop-blur-xl px-8 py-4 rounded-full border border-white/10">
                    <button @click="prev()" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="flex gap-2">
                        <template x-for="i in totalOriginal" :key="'dot-' + i">
                            <div 
                                class="w-2 h-2 rounded-full transition-all duration-500 cursor-pointer"
                                @click="goTo(i-1)"
                                :class="((activeIndex - clonesCount) % totalOriginal + totalOriginal) % totalOriginal == (i-1) ? 'bg-toba-accent w-8' : 'bg-white/20'"
                            ></div>
                        </template>
                    </div>
                    <button @click="next()" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            {{-- Card Preview Container Wrapper (Viewport for 3 cards) --}}
            <div class="hidden lg:block w-[588px] overflow-hidden pointer-events-auto py-4">
                <div class="card-container flex items-center gap-6 carousel-strip"
                     :class="isTransitioning ? 'duration-[800ms]' : 'duration-0'"
                     :style="'transform: translateX(-' + (activeIndex * 204) + 'px)'">
                    <template x-for="(slide, i) in slides" :key="'card-' + i">
                        <div 
                            class="card-preview shrink-0 relative group overflow-hidden"
                            :class="activeIndex == i ? 'active-card' : 'opacity-40 scale-95 hover:opacity-70'"
                            @click="isTransitioning = true; activeIndex = i; startAutoplay()"
                        >
                            <img :src="slide.image_url" 
                                 :onerror="`this.onerror=null; this.src='${'{{ asset('images/home/tour.webp') }}'}'`"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                            <div class="absolute bottom-6 left-6 right-6 text-white translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all z-10">
                                <p class="text-[10px] font-black uppercase tracking-widest mb-1" x-text="slide.location"></p>
                                <h3 class="text-xs font-black uppercase leading-tight line-clamp-2" x-text="slide.title"></h3>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>
