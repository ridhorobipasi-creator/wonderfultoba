<div class="relative w-full h-[600px] lg:h-[800px] overflow-hidden group">
    <!-- Swiper Container -->
    <div class="swiper main-slider h-full">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide relative">
                <img src="{{ asset('images/slider/slide1.png') }}" alt="Lake Toba Panorama" class="absolute inset-0 w-full h-full object-cover scale-110 animate-subtle-zoom">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
                <div class="absolute inset-0 flex items-center justify-center text-center px-4">
                    <div class="max-w-4xl space-y-6">
                        <span class="inline-block px-4 py-1.5 bg-toba-green/20 backdrop-blur-md border border-toba-green/30 text-toba-green rounded-full text-xs font-black tracking-widest uppercase animate-fade-in-down">
                            Welcome to Paradise
                        </span>
                        <h2 class="text-4xl lg:text-7xl font-black text-white leading-tight animate-fade-in-up">
                            Discover the Magic of <br/> <span class="text-toba-green">Lake Toba</span>
                        </h2>
                        <p class="text-slate-200 text-sm lg:text-lg max-w-2xl mx-auto animate-fade-in-up delay-200">
                            Experience the serene beauty and rich culture of North Sumatra with our premium tour packages tailored just for you.
                        </p>
                        <div class="pt-8 flex flex-wrap justify-center gap-4 animate-fade-in-up delay-300">
                            <a href="/tour" class="px-8 py-4 bg-toba-green text-white rounded-2xl font-bold hover:bg-white hover:text-toba-green transition-all shadow-xl shadow-toba-green/20">
                                Explore Tours
                            </a>
                            <a href="/about" class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-2xl font-bold hover:bg-white/20 transition-all">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide relative">
                <img src="{{ asset('images/slider/slide2.png') }}" alt="Batak Culture" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="absolute inset-0 flex items-center justify-center text-center px-4">
                    <div class="max-w-4xl space-y-6">
                        <span class="inline-block px-4 py-1.5 bg-amber-500/20 backdrop-blur-md border border-amber-500/30 text-amber-500 rounded-full text-xs font-black tracking-widest uppercase">
                            Cultural Heritage
                        </span>
                        <h2 class="text-4xl lg:text-7xl font-black text-white leading-tight">
                            Heritage & Traditions <br/> <span class="text-amber-500">Batak Bolon</span>
                        </h2>
                        <p class="text-slate-200 text-sm lg:text-lg max-w-2xl mx-auto">
                            Immerse yourself in the authentic Batak experience, from traditional architecture to mystical legends.
                        </p>
                        <div class="pt-8 flex justify-center gap-4">
                            <a href="/culture" class="px-8 py-4 bg-amber-500 text-white rounded-2xl font-bold hover:bg-white hover:text-amber-500 transition-all shadow-xl shadow-amber-500/20">
                                Discover More
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="swiper-slide relative">
                <img src="{{ asset('images/slider/slide3.png') }}" alt="Corporate Outbound" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="absolute inset-0 flex items-center justify-center text-center px-4">
                    <div class="max-w-4xl space-y-6">
                        <span class="inline-block px-4 py-1.5 bg-blue-500/20 backdrop-blur-md border border-blue-500/30 text-blue-500 rounded-full text-xs font-black tracking-widest uppercase">
                            Corporate Solutions
                        </span>
                        <h2 class="text-4xl lg:text-7xl font-black text-white leading-tight">
                            Transform Your Team <br/> <span class="text-blue-500">Excellence Awaits</span>
                        </h2>
                        <p class="text-slate-200 text-sm lg:text-lg max-w-2xl mx-auto">
                            Elevate your corporate bonding with our professional outbound and team building programs.
                        </p>
                        <div class="pt-8 flex justify-center gap-4">
                            <a href="/outbound" class="px-8 py-4 bg-blue-500 text-white rounded-2xl font-bold hover:bg-white hover:text-blue-500 transition-all shadow-xl shadow-blue-500/20">
                                Corporate Inquiries
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="swiper-button-next !text-white !w-12 !h-12 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-2xl !after:text-lg transition-all opacity-0 group-hover:opacity-100"></div>
        <div class="swiper-button-prev !text-white !w-12 !h-12 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-2xl !after:text-lg transition-all opacity-0 group-hover:opacity-100"></div>
        
        <!-- Pagination -->
        <div class="swiper-pagination !bottom-10"></div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: rgba(255, 255, 255, 0.3);
        opacity: 1;
        transition: all 0.3s;
    }
    .swiper-pagination-bullet-active {
        background: #10b981;
        width: 32px;
        border-radius: 6px;
    }
    @keyframes subtle-zoom {
        0% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .animate-subtle-zoom {
        animation: subtle-zoom 10s ease-out forwards;
    }
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fade-in-up 1s ease-out forwards; }
    .animate-fade-in-down { animation: fade-in-down 1s ease-out forwards; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.4s; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.main-slider', {
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>
@endpush
