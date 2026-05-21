@extends('layouts.app')

@section('title', $settings['meta_title'] ?? $settings['hero_title'] ?? 'Sujai Laketoba – Wisata Sumatera Utara')
@section('description', $settings['meta_description'] ?? $settings['hero_subtitle'] ?? 'Temukan keindahan Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang bersama Sujai Laketoba.')
@section('keywords', __('paket wisata danau toba, layanan premium danau toba, private tour samosir, travel vip medan, wisata sumatera utara, sujai laketoba'))
@section('content')
<div x-data="{ waNumber: '{{ preg_replace('/[^0-9]/', '', $settings['contact_wa_1'] ?? '6281323888207') }}' }">
    
    <!-- Premium Hero Slider -->
    @if($settings['show_slider'] ?? true)
    <x-home-slider :settings="$settings" :packages="$packages" />
    @endif

    <!-- Featured Packages -->
    @if($settings['show_featured'] ?? true)
    <section class="py-24 bg-surface overflow-hidden"
             x-data="{
                 isDragging: false, startX: 0, scrollLeft: 0,
                 get el() { return this.$refs.pkgStrip },
                 onDown(e) { this.isDragging = true; this.startX = e.pageX - this.el.offsetLeft; this.scrollLeft = this.el.scrollLeft; },
                 onMove(e) { if (!this.isDragging) return; e.preventDefault(); this.el.scrollLeft = this.scrollLeft - ((e.pageX - this.el.offsetLeft) - this.startX); },
                 onUp() { this.isDragging = false; },
                 scrollPrev() { this.el.scrollBy({ left: -380, behavior: 'smooth' }); },
                 scrollNext() { this.el.scrollBy({ left: 380, behavior: 'smooth' }); },
             }">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-14 gap-6">
                <div>
                    <div class="w-16 h-0.5 bg-secondary mb-4"></div>
                    <h2 class="font-headline-lg text-headline-lg text-primary">{{ __('Ekspedisi Terkurasi') }}</h2>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="scrollPrev()"
                            class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-on-primary hover:border-primary transition-all">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    <button @click="scrollNext()"
                            class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-on-primary hover:border-primary transition-all">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <div x-ref="pkgStrip"
             @mousedown="onDown($event)" @mousemove="onMove($event)" @mouseup="onUp()" @mouseleave="onUp()"
             class="flex gap-6 overflow-x-auto scroll-smooth px-6 md:px-[max(1.5rem,calc((100vw-80rem)/2+1.5rem))] pb-4 no-scrollbar select-none"
             :class="isDragging ? 'cursor-grabbing' : 'cursor-grab'">

            @foreach($packages as $index => $pkg)
            @php
                $pkgImage = $pkg->resolveImageUrl($pkg->packageImages->first()?->image_path ?? ($pkg->images[0] ?? null));
            @endphp
            <div class="flex-shrink-0 w-[80vw] sm:w-[45vw] md:w-[31vw] lg:w-[28vw] xl:w-[25rem] group">
                <div class="relative aspect-[3/4] overflow-hidden rounded-2xl cursor-pointer"
                     onclick="window.location.href='/tour/package/{{ $pkg->slug ?: $pkg->id }}'">
                    <img alt="{{ $pkg->name }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                         src="{{ $pkgImage }}" loading="lazy"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                    
                    <div class="absolute top-5 right-5 glass-card px-3 py-1 rounded-full flex items-center gap-1">
                        <span class="material-symbols-outlined text-secondary text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="text-white font-label-caps text-[11px]">{{ $pkg->rating ?? '4.9' }}</span>
                    </div>

                    <div class="absolute bottom-0 left-0 w-full p-6 text-white">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                            <span class="font-label-caps text-[10px] tracking-wider">{{ strtoupper(__($pkg->locationTag ?? 'Sumatera Utara')) }}</span>
                        </div>
                        <h3 class="font-headline-md text-[22px] md:text-[26px] mb-3 line-clamp-2 leading-tight">{{ $pkg->name }}</h3>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-label-caps text-[9px] opacity-60">{{ __('Mulai dari') }}</p>
                                <p class="font-headline-md text-[20px] text-secondary">{{ \App\Helpers\CurrencyHelper::formatPrice($pkg->price) }}</p>
                            </div>
                            <div class="w-10 h-10 bg-white/10 backdrop-blur-md border border-white/20 rounded-full flex items-center justify-center group-hover:bg-secondary group-hover:border-secondary transition-all">
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </section>
    @endif

    @php
        $ctaImg = imageUrl($settings['cta_image_url'] ?? null, 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000');
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

    <section class="bg-primary py-20 md:py-28 overflow-hidden"
             x-data="{
                 slides: @js($slides),
                 scrollContainer: null,
                 isDragging: false,
                 startX: 0,
                 scrollLeft: 0,
                 autoTimer: null,

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
        <div class="max-w-7xl mx-auto px-6 md:px-8 mb-10 md:mb-14 flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
            <div>
                <span class="font-label-caps text-[10px] text-white tracking-widest uppercase block mb-3">{{ __('Galeri Destinasi') }}</span>
                <h2 class="font-headline-lg text-[32px] md:text-headline-lg text-white leading-tight">
                    {{ __('Momen') }} <span class="text-white">{{ __('Tak Terlupakan') }}</span>
                </h2>
            </div>
            <a href="{{ route('tour.gallery') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white hover:bg-secondary hover:border-secondary transition-all duration-300 group">
                <span class="material-symbols-outlined text-[16px]">photo_library</span>
                <span class="font-label-caps text-[10px] uppercase tracking-wider">{{ __('Lihat Semua') }}</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>

        {{-- Scrollable strip --}}
        <div class="relative group/strip">

            {{-- Prev / Next arrows --}}
            <button @click="scrollPrev()"
                    class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-md border border-white/20 rounded-full items-center justify-center text-white hover:bg-secondary hover:border-secondary transition-all duration-300 opacity-0 group-hover/strip:opacity-100">
                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
            </button>
            <button @click="scrollNext()"
                    class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-md border border-white/20 rounded-full items-center justify-center text-white hover:bg-secondary hover:border-secondary transition-all duration-300 opacity-0 group-hover/strip:opacity-100">
                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
            </button>

            {{-- Photo strip --}}
            <div x-ref="strip"
                 @mousedown="onMouseDown($event)"
                 @mousemove="onMouseMove($event)"
                 @mouseup="onMouseUp()"
                 @mouseleave="onMouseUp()"
                 class="flex gap-5 overflow-x-auto scroll-smooth px-6 md:px-8 pb-4 no-scrollbar select-none"
                 :class="isDragging ? 'cursor-grabbing' : 'cursor-grab'">

                <template x-for="(slide, i) in slides" :key="i">
                    <div class="flex-shrink-0 w-[75vw] sm:w-[45vw] md:w-[30vw] lg:w-[23vw] group/card">
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-xl border border-white/10 transition-all duration-500 hover:border-secondary/40 hover:-translate-y-1">
                            <img :src="slide.url"
                                 :alt="slide.caption || 'Sujai Laketoba'"
                                 class="w-full h-full object-cover transition-transform duration-[2s] group-hover/card:scale-105"
                                 loading="lazy"
                                 onerror="this.src='{{ asset('images/home/tour.webp') }}'">

                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/20 to-transparent opacity-0 group-hover/card:opacity-100 transition-all duration-500 flex flex-col justify-end p-5">
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

    </section>
    @endif


    <!-- Testimonials — minimal -->
    @if($settings['show_testimonials'] ?? true)
    @php
        $testimonials = $settings['testimonials'] ?? [
            [
                'name' => 'Julian Thorne',
                'location' => 'London, UK',
                'text' => 'Perhatian terhadap detail ekologis sangat menakjubkan. Kami menjelajahi jantung Sumatera tanpa merasa mengganggu alam. Benar-benar kemewahan yang memiliki jiwa.',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBC2hFgaSrsa7A85bf12SiUK30lkhhCDaRhbOnbQUSBDrVAhUc-Gn-kMxthYsqdKkltnsLX5uDn04g_wUEJDw7KExIuUEuT2cOCkJzUuAvUoirw4J8-btZ7EU65QsdsiKicugbr5fKfKaiv1dI7zoNYP3tIBWv-WQaRo54c35tZdiY3pUz3Id2QsoJ1s8g4PhVBn9mEPONWbu5kdqHJ3Az707ev_vjB69P126_JW9q9SKGuhiqoqVWEFZ36G19k5tdDV9HRU5qbZr3v'
            ],
            [
                'name' => 'Isabella Chen',
                'location' => 'Singapura',
                'text' => 'Semuanya, mulai dari penerbangan pribadi hingga lokasi makan malam rahasia di Raja Ampat, dirancang dengan sempurna. Sujai Laketoba berada di kelasnya tersendiri.',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAFaWOA9YaZv80gupI35EV08bFke39OBT9N8CQ45hUM6dKKz11RKUVgEOqjPB0spF_ja_gJ6-2wUxWYwZLSmBQV9h7pTqQ1AwkBLNqZm2Heokgv4sEbwFHjwf44zyhRu1psOnTm-x6WFxR7PtU9RLW5My0DCfk85AEvcuqn4mMERnRR084UCi-8jPaSPcohMu3PBqalduBH8ykcewFiLBro5njrtDwIEOFCZZBhts_Yjkf-BBORMZS97L63Xj6opHAW4qD4A01FGfXk'
            ]
        ];
    @endphp
    <section class="py-20 bg-surface">
        <div class="max-w-5xl mx-auto px-6 md:px-8">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-10 h-0.5 bg-secondary"></div>
                <span class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">{{ __('Testimoni') }}</span>
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
                                 src="{{ !empty($t['image']) ? (Str::startsWith($t['image'], 'http') ? $t['image'] : asset('storage/'.$t['image'])) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&h=150&q=80' }}"
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
    <section class="bg-surface pb-20 px-6 md:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-primary rounded-2xl px-8 py-6 md:px-10 md:py-7 flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
                <img alt="{{ $settings['specialist_name'] ?? 'Sarah Anggraini' }}"
                     class="w-14 h-14 rounded-full object-cover border-2 border-white/10 shrink-0"
                     src="{{ imageUrl($settings['specialist_image_url'] ?? '', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=200&q=80') }}"/>
                <div class="flex-1 text-center sm:text-left">
                    <p class="text-white font-bold font-body-md text-sm">{{ $settings['specialist_name'] ?? 'Sarah Anggraini' }}</p>
                    <p class="text-white/50 font-body-md text-xs">{{ __('Punya pertanyaan? Saya siap membantu merencanakan liburan impian Anda.') }}</p>
                </div>
                <a class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-6 py-3 rounded-xl font-label-caps text-[10px] uppercase tracking-widest transition-all shrink-0"
                   href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['contact_wa_1'] ?? '6281323888207') }}?text={{ urlencode('Halo ' . ($settings['specialist_name'] ?? 'Sarah') . ', saya ingin tanya paket tour...') }}">
                    <span class="material-symbols-outlined text-[16px]">chat</span>
                    {{ __('WhatsApp') }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Journal/Blog -->
    @if($settings['show_blogs'] ?? true)
    <section class="py-24 max-w-7xl mx-auto px-6 md:px-8 bg-surface">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 md:mb-16 gap-4">
            <h2 class="font-headline-lg text-[32px] md:text-headline-lg text-primary">{{ __('Jurnal Perjalanan') }}</h2>
            <a class="font-label-caps text-label-caps text-secondary underline underline-offset-8" href="/tour/blog">{{ __('Lihat Semua Cerita') }}</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($blogs as $blog)
            <div class="group cursor-pointer" onclick="window.location.href='{{ route('tour.blog.detail', $blog->slug) }}'">
                <div class="aspect-[16/10] overflow-hidden rounded-lg mb-4 md:mb-6 shadow-md border border-slate-100 bg-slate-100">
                    <img alt="{{ $blog->translated_title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $blog->image }}"/>
                </div>
                <span class="font-label-caps text-[10px] text-secondary border border-secondary px-2 py-0.5 rounded-full uppercase tracking-wider mb-3 md:mb-4 inline-block">{{ strtoupper($blog->category ?? 'EKSPEDISI') }}</span>
                <h3 class="font-headline-md text-[20px] md:text-[22px] group-hover:text-secondary transition-colors duration-300 font-bold leading-tight">{{ $blog->translated_title }}</h3>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- FAQ -->
    <section class="py-24 bg-surface-container-low">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="font-headline-lg text-[32px] md:text-headline-lg text-primary mb-4">{{ __('Pertanyaan Umum') }}</h2>
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
                <div class="border-b border-outline-variant bg-white px-6 rounded-2xl shadow-xs border border-slate-100">
                    <button @click="selected !== {{ $index + 1 }} ? selected = {{ $index + 1 }} : selected = null" class="w-full py-5 md:py-6 flex justify-between items-center text-left focus:outline-none">
                        <span class="font-headline-md text-[16px] md:text-[18px] text-primary font-bold">{{ __($faq['q']) }}</span>
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
    <section class="py-24 md:py-32 px-6 md:px-8 bg-surface">
        <div class="max-w-7xl mx-auto bg-primary rounded-[4rem] p-12 md:p-24 relative overflow-hidden shadow-[0_50px_100px_-20px_rgba(0,37,19,0.3)]">
            <div class="absolute inset-0 opacity-40">
                <img src="{{ $ctaImg }}" alt="{{ $ctaAlt ?? 'Call to action image' }}" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary/60 to-transparent"></div>
            
            <!-- Animated Circles Overlay -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-secondary/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/10 rounded-full blur-[120px]"></div>

            <div class="relative z-10 text-center lg:text-left max-w-4xl">
                <h2 class="text-4xl md:text-7xl font-headline-lg text-white mb-8 tracking-tight leading-[0.95]">
                    {{ __('Siap Untuk') }} <br/> <span class="text-white">{{ __('Petualangan Nyata?') }}</span>
                </h2>
                <p class="text-xl text-slate-300 mb-12 font-body-lg leading-relaxed max-w-2xl">
                    @php
                        $touristsCount = $settings['stat_customers'] ?? '1.500+';
                    @endphp
                    {{ __('Bergabunglah dengan') }} <span class="text-white font-bold">{{ $touristsCount }}</span> {{ __('wisatawan lainnya yang telah menemukan keindahan Sumatera Utara bersama kami.') }}
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-6 justify-center lg:justify-start">
                    <a href="/tour/packages" class="bg-white text-primary px-12 py-6 rounded-[2rem] font-bold text-sm uppercase tracking-[0.2em] hover:bg-secondary hover:text-white transition-all duration-500 shadow-2xl flex items-center gap-3 group">
                        <span>{{ __('Pesan Paket Sekarang') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <div class="flex -space-x-4">
                        @php
                        $avatarPhotos = [
                            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&h=100&q=80',
                            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&h=100&q=80',
                            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=100&h=100&q=80',
                            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&h=100&q=80',
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
