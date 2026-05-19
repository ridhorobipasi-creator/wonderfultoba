@extends('layouts.app')

@section('title', $settings['seo_title'] ?? $settings['meta_title'] ?? 'Wonderful Toba Outbound - Provider Outbound Terbaik di Sumatera Utara')
@section('description', $settings['seo_description'] ?? $settings['meta_description'] ?? 'Wonderful Toba Outbound: Provider terbaik untuk Team Building, Gathering, Fun Games di Medan & Sumatera Utara.')
@section('keywords', $settings['seo_keywords'] ?? 'outbound danau toba, outbound medan, team building toba')

@section('content')

<div 
    x-data="{ 
        currentSlide: 0, 
        activeVideoTab: 0,
        heroImages: [
            '{{ imageUrl($settings['hero_image_url'] ?? $settings['hero_image'] ?? null, 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=1200') }}',
            '{{ imageUrl($settings['about_image_1_url'] ?? $settings['about_image_1'] ?? null, 'https://images.unsplash.com/photo-1551818255-e6e10975bc17?w=1200') }}',
            '{{ imageUrl($settings['about_image_2_url'] ?? $settings['about_image_2'] ?? null, 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=1200') }}'
        ],
        about: {
            title: '{{ $settings['about_hero_title'] ?? 'Apa itu Outbound?' }}',
            description: '{{ $settings['about_hero_desc'] ?? 'Outbound adalah metode pembelajaran berbasis pengalaman di alam terbuka yang dirancang untuk membangun karakter, kepemimpinan, dan kerjasama tim secara efektif.' }}',
            statsLabel: '{{ $settings['stat_label_0'] ?? 'Instansi Terlayani' }}',
            statsValue: '{{ $settings['stat_value_0'] ?? '500+' }}'
        },
        waNumber: '{{ preg_replace('/[^0-9]/', '', $settings['cta_whatsapp_number'] ?? $siteSettings['general']['whatsapp'] ?? '6281323888207') }}',
        init() {
            setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.heroImages.length;
            }, 5000);
        }
    }"
    class="font-sans antialiased text-slate-800 bg-[#fbf9f8] min-h-screen"
>
    <!-- Hero Section -->
    @if($settings['show_hero'] ?? true)
    <section class="relative h-[100dvh] min-h-[600px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <template x-for="(img, i) in heroImages" :key="i">
                <img 
                    x-show="currentSlide === i"
                    :src="img" 
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
                    :class="currentSlide === i ? 'opacity-100 scale-100' : 'opacity-0 scale-105'"
                    :fetchpriority="i === 0 ? 'high' : 'low'"
                    :loading="i === 0 ? 'eager' : 'lazy'"
                    decoding="async"
                >
            </template>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/30 to-slate-900/80"></div>
        </div>
        
        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center mt-10">
            <div class="flex flex-col items-center">
                <div class="mb-6 flex flex-col items-center">
                    <div class="w-20 h-20 bg-white/10 backdrop-blur-xl rounded-3xl flex items-center justify-center text-white font-black text-4xl border border-white/20 shadow-2xl mb-4">
                        W
                    </div>
                    <div class="text-white font-black tracking-[0.4em] uppercase text-xs opacity-80">{{ $siteSettings['cms_landing']['brand_name'] ?? 'WONDERFUL TOBA' }}</div>
                    <div class="text-emerald-400 font-black tracking-[0.2em] uppercase text-[10px] mt-1">{{ $settings['hero_label'] ?? 'OUTBOUND & EVENT' }}</div>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-7xl text-white font-black max-w-4xl mx-auto mb-6 leading-[1.1] md:leading-tight drop-shadow-2xl">
                    {{ $settings['hero_title'] ?? 'Solusi Team Building Terbaik di Sumatera' }}
                </h1>
                <p class="text-lg md:text-2xl text-white font-medium max-w-3xl mx-auto mb-10 md:mb-12 leading-relaxed drop-shadow-xl">
                    {{ $settings['hero_subtitle'] ?? 'Tingkatkan sinergi dan produktivitas tim Anda dengan program outbound profesional.' }}
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
                    <a href="/outbound/packages" class="w-full sm:w-auto bg-toba-green text-white px-10 py-4 rounded-full font-black tracking-widest text-sm uppercase hover:bg-emerald-600 transition-colors shadow-2xl flex items-center justify-center gap-2">
                        {{ $settings['hero_cta_text'] ?? 'Pricelist & Paket' }}
                    </a>
                    <a :href="'https://wa.me/' + waNumber" class="w-full sm:w-auto bg-white/20 backdrop-blur-md border border-white/50 text-white px-10 py-4 rounded-full font-black tracking-widest text-sm uppercase hover:bg-white hover:text-slate-900 transition-colors shadow-2xl flex items-center justify-center">
                        Konsultasi Event
                    </a>
                </div>
                
                <!-- Slider Dots -->
                <div class="flex justify-center mt-16 gap-2">
                    <template x-for="(_, idx) in heroImages" :key="idx">
                        <button 
                           @click="currentSlide = idx" 
                           class="w-3 h-3 rounded-full transition-all duration-300"
                           :class="currentSlide === idx ? 'bg-toba-green w-10 shadow-[0_0_10px_#10B981]' : 'bg-white/40 hover:bg-white/70'"
                        ></button>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Apa itu Outbound & Kenapa Kami -->
    <section id="tentangkami" class="py-20 md:py-32 bg-[#fdfdfd] relative z-20 -mt-6 md:-mt-10 rounded-t-[3rem] shadow-[0_-20px_40px_-15px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 md:gap-16 lg:gap-8 items-center">
                <div class="lg:col-span-7 pr-0 lg:pr-12">
                    <div class="inline-flex items-center space-x-3 mb-8 bg-white px-5 py-2.5 rounded-full border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)]">
                        <span class="relative flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-toba-green opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-toba-green"></span>
                        </span>
                        <span class="text-toba-green font-black text-xs uppercase tracking-[0.25em]">Experiential Learning</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-6xl lg:text-[4rem] font-black text-slate-900 mb-8 md:mb-10 leading-[1.1] tracking-tight">
                        <template x-if="about.title.toLowerCase().includes('outbound')">
                            <span>
                                <span x-text="about.title.replace(/outbound\??/i, '').trim()"></span>
                                <span class="text-toba-green relative inline-block">
                                    <span x-text="about.title.match(/outbound\??/i)?.[0] || 'Outbound?'"></span>
                                    <svg class="absolute w-[110%] h-4 -bottom-1 left-[-5%] text-toba-green/20" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0 15 Q 50 0 100 15" stroke="currentColor" stroke-width="8" fill="none" stroke-linecap="round" /></svg>
                                </span>
                            </span>
                        </template>
                        <template x-if="!about.title.toLowerCase().includes('outbound')">
                            <span x-text="about.title"></span>
                        </template>
                    </h2>
                    
                    <div class="relative mb-12">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-toba-green to-emerald-200 rounded-full"></div>
                        <div class="pl-8 text-xl leading-relaxed text-slate-600 font-medium" x-text="about.description"></div>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-[2.5rem] p-8 md:p-10 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] relative hover:shadow-[0_20px_60px_-15px_rgba(16,185,129,0.1)] transition-all duration-500">
                        <h3 class="text-xl md:text-2xl font-black mb-8 text-slate-900 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-lg">
                                <i class="fas fa-star"></i>
                            </span>
                            Kenapa Harus Kami?
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            @php
                                $defaults = [
                                    1 => [
                                        'title' => 'Trainer Bersertifikasi',
                                        'icon' => 'fas fa-award',
                                        'desc' => 'Tim trainer profesional bersertifikasi resmi BNSP dan AELI dengan pengalaman puluhan tahun.'
                                    ],
                                    2 => [
                                        'title' => 'Program Custom',
                                        'icon' => 'fas fa-sliders',
                                        'desc' => 'Program dirancang khusus berbasis Experiential Learning yang disesuaikan dengan corporate goals.'
                                    ],
                                    3 => [
                                        'title' => 'Keamanan Terjamin',
                                        'icon' => 'fas fa-shield-halved',
                                        'desc' => 'Peralatan berstandar internasional, asuransi, serta instruktur K3 tersertifikasi.'
                                    ],
                                    4 => [
                                        'title' => 'Kemitraan Resort',
                                        'icon' => 'fas fa-hotel',
                                        'desc' => 'Koneksi luas dengan resort premium di Samosir, Sibolangit, Simalem, & Bukit Lawang.'
                                    ]
                                ];
                            @endphp
                            @for($i=1; $i<=4; $i++)
                                @php
                                    $title = $settings['about_title_'.$i] ?? $defaults[$i]['title'];
                                    $icon = $settings['about_icon_'.$i] ?? $defaults[$i]['icon'];
                                    $desc = $settings['about_desc_'.$i] ?? $defaults[$i]['desc'];
                                    if ($icon && !str_starts_with($icon, 'fa')) {
                                        $icon = 'fas fa-' . $icon;
                                    } elseif ($icon && str_starts_with($icon, 'fa-')) {
                                        $icon = 'fas ' . $icon;
                                    }
                                @endphp
                                <div class="flex items-start gap-4 group cursor-default">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 text-toba-green flex items-center justify-center shrink-0 group-hover:bg-toba-green group-hover:text-white transition-colors duration-300">
                                        <i class="{{ $icon }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-900 mb-1 text-sm">{{ $title }}</h4>
                                        <p class="text-slate-500 text-[10px] leading-relaxed line-clamp-2">{{ $desc }}</p>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>


                <div class="lg:col-span-5 relative mt-32 lg:mt-0">
                    <div class="relative w-full md:w-4/5 ml-auto md:-right-4 top-0 z-10 rounded-[2.5rem] overflow-hidden shadow-2xl hover:-translate-y-2 transition-transform duration-500">
                        <img src="{{ imageUrl($settings['about_image_1_url'] ?? $settings['about_image_1'] ?? null, 'https://images.unsplash.com/photo-1551818255-e6e10975bc17?w=800') }}" alt="Team Building" class="w-full aspect-[4/5] object-cover">
                    </div>
                    <div class="absolute w-3/4 -bottom-12 -left-4 z-20 rounded-[2.5rem] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] border-[6px] border-white hover:scale-105 transition-transform duration-500">
                        <img src="{{ imageUrl($settings['about_image_2_url'] ?? $settings['about_image_2'] ?? null, 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800') }}" alt="Corporate Activity" class="w-full aspect-square object-cover">
                    </div>
                    <div class="absolute top-1/2 -left-8 lg:-left-12 z-30 bg-white/90 backdrop-blur-xl p-5 md:p-6 rounded-3xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.2)] border border-white flex items-center gap-4">
                        <div class="bg-gradient-to-br from-toba-green to-emerald-600 text-white w-14 h-14 md:w-16 md:h-16 rounded-2xl flex items-center justify-center font-black text-2xl shadow-inner shadow-white/20" x-text="about.statsValue"></div>
                        <div>
                            <div class="font-black text-slate-900 text-sm md:text-base uppercase tracking-wider leading-none mb-1" x-text="about.statsLabel"></div>
                            <div class="text-slate-500 font-bold text-xs md:text-sm">Pengalaman Eksekusi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Client Portfolio -->
    @if(count($clients) > 0)
    <section class="py-20 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="text-center mb-12">
                <span class="text-toba-green font-black uppercase tracking-[0.3em] text-[10px] mb-4 block">Trusted By</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Klien Portofolio Kami</h2>
            </div>
            
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-60 grayscale hover:grayscale-0 transition-all duration-700">
                @foreach($clients as $client)
                <div class="w-32 md:w-40 h-16 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:scale-110">
                    <img src="{{ imageUrl($client->logo) }}" alt="{{ $client->name }}" class="max-w-full max-h-full object-contain">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($settings['show_services'] ?? true)
    <!-- Layanan Kami -->
    <section id="layanan" class="py-32 bg-[#090e17] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-toba-green/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute inset-0 bg-cover bg-center opacity-[0.03] mix-blend-overlay grayscale pointer-events-none"
             style="background-image: url('{{ imageUrl($settings['services_bg_url'] ?? null, asset('assets/images/2023/10/outbound-hadena-indonesia-experience-1024x723-1-1024x430.webp')) }}')"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center space-x-2 text-toba-green font-black uppercase tracking-[0.3em] text-xs mb-6 bg-toba-green/10 px-4 py-2 rounded-full border border-toba-green/20">
                        <i class="fas fa-sparkles text-[10px]"></i>
                        <span>Program Unggulan</span>
                    </span>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight">Kapasitas <span class="text-transparent bg-clip-text bg-gradient-to-r from-toba-green to-emerald-400">Layanan Kami</span></h2>
                </div>
                <p class="text-slate-400 font-medium text-lg max-w-md leading-relaxed md:text-right">Dirancang spesifik untuk membangun kebersamaan dan mencetak kapabilitas SDM perusahan Anda di level maksimal.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($services as $i => $svc)
                    @php
                        $iconMap = [
                            'Users' => 'fas fa-users',
                            'Smile' => 'fas fa-smile',
                            'Sparkles' => 'fas fa-sparkles',
                            'Compass' => 'fas fa-compass',
                            'Award' => 'fas fa-award',
                            'Shield' => 'fas fa-shield-halved',
                            'Heart' => 'fas fa-heart',
                            'Map' => 'fas fa-map',
                            'Trophy' => 'fas fa-trophy'
                        ];
                        $iconClass = $iconMap[$svc->icon] ?? $svc->icon ?? 'fas fa-users';
                        if ($iconClass && !str_starts_with($iconClass, 'fa')) {
                            $iconClass = 'fas fa-' . $iconClass;
                        }
                    @endphp
                    <div class="group relative rounded-[2.5rem] p-1 overflow-hidden bg-gradient-to-b from-white/10 to-white/5 hover:from-toba-green/40 hover:to-toba-green/5 transition-colors duration-500">
                        <div class="absolute inset-0 bg-toba-green/0 group-hover:bg-toba-green/10 blur-xl transition-all duration-700"></div>
                        <div class="relative w-full h-full bg-[#0d131f] rounded-[2.3rem] overflow-hidden flex flex-col items-start border border-white/5">
                            <div class="w-full h-56 relative overflow-hidden">
                                <img src="{{ $svc->image }}" alt="{{ $svc->title }}" class="w-full h-full object-cover scale-100 group-hover:scale-110 transition-transform duration-700 ease-out opacity-80 group-hover:opacity-100">
                                <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-[#0d131f]/10 to-[#0d131f] translate-y-2"></div>
                                <div class="absolute top-5 right-5 bg-black/40 backdrop-blur-md p-3.5 rounded-2xl border border-white/10 shadow-2xl group-hover:bg-toba-green group-hover:border-toba-green transition-colors duration-300">
                                    <i class="{{ $iconClass }} text-toba-green group-hover:text-white transition-colors text-lg"></i>
                                </div>
                            </div>
                            <div class="p-8 pt-4 flex-1 flex flex-col z-10 w-full relative">
                                <div class="absolute right-6 bottom-4 text-7xl font-black text-white/[0.03] group-hover:text-toba-green/[0.05] transition-colors pointer-events-none select-none z-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                <h3 class="text-2xl font-black text-white mb-2 tracking-tight group-hover:text-toba-green transition-colors leading-tight relative z-10 uppercase">{{ $svc->title }}</h3>
                                <p class="text-toba-green font-bold text-xs mb-5 uppercase tracking-widest bg-toba-green/10 inline-block px-3 py-1 rounded-lg w-max relative z-10">{{ $svc->shortDesc }}</p>
                                <p class="text-slate-400 font-medium leading-relaxed text-sm group-hover:text-slate-300 transition-colors relative z-10 line-clamp-3">{{ $svc->detailDesc }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    
    @if($settings['show_featured'] ?? true)
    @if(count($featuredPackages) > 0)
    <!-- Featured Packages -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center space-x-2 text-amber-500 font-black uppercase tracking-[0.3em] text-xs mb-6">
                        <i class="fas fa-star text-[10px]"></i>
                        <span>Best Selling</span>
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">Paket <span class="text-amber-500">Pilihan Terbaik</span></h2>
                </div>
                <a href="/outbound/packages" class="text-slate-900 font-black text-xs uppercase tracking-widest border-b-2 border-amber-500 pb-1 hover:text-amber-600 transition-colors">Lihat Semua Paket</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredPackages as $pkg)
                <a href="/outbound/packages" class="group block">
                    <div class="relative h-[450px] rounded-[3rem] overflow-hidden mb-6 shadow-2xl shadow-slate-200/50">
                        @php
                            $pImg = $pkg->packageImages->first()?->image_path ?? ($pkg->images[0] ?? 'https://images.unsplash.com/photo-1522071823991-b580970ad00d?w=800');
                        @endphp
                        <img src="{{ asset($pImg) }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 right-8">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 bg-amber-500 text-white text-[9px] font-black uppercase tracking-widest rounded-lg">{{ $pkg->duration }}</span>
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest rounded-lg border border-white/20">Corporate</span>
                            </div>
                            <h3 class="text-2xl font-black text-white leading-tight mb-2 group-hover:text-amber-400 transition-colors">{{ $pkg->name }}</h3>
                            <p class="text-slate-300 font-bold text-sm">Mulai Rp {{ number_format($pkg->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endif

    <!-- Lokasi Outbound -->
    @if(count($locations) > 0)
    <section id="lokasi" class="py-32 bg-[#f8fafc] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center space-x-2 text-toba-green font-black uppercase tracking-[0.3em] text-xs mb-6">
                        <i class="fas fa-map-marker-alt text-[10px]"></i>
                        <span>Destinasi Pilihan</span>
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">Lokasi <span class="text-toba-green">Kegiatan</span></h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($locations as $loc)
                <div class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-xl hover:-translate-y-2 transition-all duration-500">
                    <img src="{{ imageUrl($loc->image) }}" alt="{{ $loc->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 right-8">
                        <h3 class="text-xl font-black text-white mb-2">{{ $loc->name }}</h3>
                        <div class="w-8 h-1 bg-toba-green rounded-full group-hover:w-full transition-all duration-500"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Video Highlights -->
    <section class="py-24 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">Highlight <span class="text-toba-green">Video Event</span></h2>
                <p class="text-slate-500 font-medium text-lg max-w-2xl mx-auto">Kami mengabadikan setiap momen kebersamaan dan kekompakan event secara profesional.</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                @foreach($videos as $i => $vid)
                    <button 
                        @click="activeVideoTab = {{ $i }}"
                        :class="activeVideoTab === {{ $i }} ? 'bg-slate-900 text-white shadow-xl scale-105' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300 hover:bg-slate-50'"
                        class="px-6 py-3.5 rounded-full font-bold text-sm transition-all flex items-center gap-3 relative overflow-hidden group"
                    >
                        <div x-show="activeVideoTab === {{ $i }}" class="absolute inset-0 bg-toba-green/20 scale-150 animate-pulse"></div>
                        <div class="p-1.5 rounded-full transition-colors" :class="activeVideoTab === {{ $i }} ? 'bg-toba-green' : 'bg-slate-100 group-hover:bg-slate-200'">
                            <i class="fas fa-play text-[10px]" :class="activeVideoTab === {{ $i }} ? 'text-white' : 'text-slate-700'"></i>
                        </div>
                        <span class="relative z-10">{{ $vid->title }}</span>
                    </button>
                @endforeach
            </div>
            
            <div class="aspect-video w-full max-w-5xl mx-auto rounded-[2.5rem] overflow-hidden shadow-2xl bg-slate-900 relative ring-8 ring-white">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 border-4 border-toba-green/30 border-t-toba-green rounded-full animate-spin"></div>
                </div>
                @foreach($videos as $i => $vid)
                    @php
                        $embedUrl = $vid->youtubeUrl;
                        if(str_contains($embedUrl, 'watch?v=')) {
                            $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                            $embedUrl = explode('&', $embedUrl)[0];
                        } elseif(str_contains($embedUrl, 'youtu.be/')) {
                            $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $embedUrl);
                        }
                    @endphp
                    <iframe
                        x-show="activeVideoTab === {{ $i }}"
                        src="{{ $embedUrl }}"
                        class="w-full h-full absolute inset-0 z-10"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Gallery Foto -->
    @if(count($gallery) > 0)
    <section id="gallery" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="text-center mb-20">
                <span class="inline-flex items-center space-x-2 text-toba-green font-black uppercase tracking-[0.3em] text-xs mb-6 bg-toba-green/10 px-4 py-2 rounded-full">
                    <i class="fas fa-camera text-[10px]"></i>
                    <span>Moment Gallery</span>
                </span>
                <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">Dokumentasi <span class="text-toba-green">Kegiatan</span></h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($gallery as $i => $img)
                <div class="group relative overflow-hidden rounded-[2rem] shadow-lg {{ $i % 5 == 0 ? 'md:col-span-2 md:row-span-2 aspect-square' : 'aspect-square' }}">
                    <img src="{{ imageUrl($img->imageUrl) }}" alt="{{ $img->caption }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center p-6 text-center">
                        <p class="text-white font-bold text-sm">{{ $img->caption }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($settings['show_testimonials'] ?? true)
    <!-- Testimonials -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center space-x-2 text-toba-green font-black uppercase tracking-[0.3em] text-xs mb-6">
                        <i class="fas fa-quote-left text-[10px]"></i>
                        <span>Client Stories</span>
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">Apa Kata <span class="text-toba-green">Klien Kami?</span></h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @php
                    $testimonials = $settings['testimonials'] ?? [
                        ['name' => 'Bambang Heru', 'location' => 'HRD PT. Maju Bersama', 'text' => 'Program outbound yang luar biasa! Tim kami jadi lebih solid dan semangat bekerja meningkat drastis.', 'image' => 'https://i.pravatar.cc/100?u=user3'],
                        ['name' => 'Siska Amelia', 'location' => 'Manager Bank Mandiri', 'text' => 'Instruktur sangat profesional dan materi team building dikemas dengan sangat menarik.', 'image' => 'https://i.pravatar.cc/100?u=user4']
                    ];
                @endphp
                @foreach($testimonials as $t)
                <div class="p-10 rounded-[3rem] bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                    <div class="flex gap-1 mb-8 text-amber-400">
                        @for($j=0; $j<5; $j++) <i class="fas fa-star text-xs"></i> @endfor
                    </div>
                    <p class="text-xl font-medium text-slate-600 leading-relaxed mb-10 italic">
                        &ldquo;{{ $t['text'] }}&rdquo;
                    </p>
                    <div class="flex items-center gap-5 pt-8 border-t border-slate-200/60">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm overflow-hidden border border-slate-100">
                            <img src="{{ imageUrl($t['image'] ?? null) }}" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($t['name']) }}&background=10b981&color=fff'">
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-lg leading-none mb-2">{{ $t['name'] }}</h4>
                            <p class="text-toba-green font-bold text-xs uppercase tracking-widest">{{ $t['location'] ?? 'Corporate Client' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    @endif

    <!-- Form Minta Penawaran -->
    <section id="penawaran" class="py-32 bg-slate-50 border-y border-slate-100">
        <div class="max-w-4xl mx-auto px-6">
            @if(session('success'))
                <div class="mb-8 p-8 bg-emerald-500 text-white rounded-[2.5rem] shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="font-black uppercase tracking-widest text-xs opacity-80 mb-1">Berhasil!</p>
                            <p class="font-bold text-lg leading-tight">{{ session('success') }}</p>
                        </div>
                    </div>
                    @if(session('whatsappUrl'))
                        <a href="{{ session('whatsappUrl') }}" target="_blank" class="px-8 py-4 bg-white text-emerald-600 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-emerald-50 transition-colors shadow-lg">
                            Konfirmasi WhatsApp
                        </a>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-8 p-6 bg-rose-500 text-white rounded-[2.5rem] shadow-xl">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center shrink-0 mt-1">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <p class="font-black uppercase tracking-widest text-xs opacity-90 mb-2">Mohon Periksa Kembali Form Anda:</p>
                            <ul class="list-disc list-inside text-sm font-medium space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100">
                <div class="bg-toba-green p-12 text-white text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-black mb-4">Minta Penawaran</h2>
                        <p class="text-emerald-100 font-medium">Dapatkan penawaran harga terbaik yang disesuaikan dengan kebutuhan instansi Anda.</p>
                    </div>
                </div>
                
                <form action="{{ route('outbound.quote.submit') }}" method="POST" class="p-12 space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-2">Nama Instansi/Perusahaan/PIC</label>
                            <input type="text" name="company_name" required value="{{ old('company_name') }}" placeholder="Contoh: PT. Maju Bersama / Bapak Heru" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-toba-green focus:border-toba-green transition-all outline-none font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-2">Jumlah Peserta</label>
                            <input type="number" name="participants" required value="{{ old('participants') }}" placeholder="Contoh: 50" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-toba-green focus:border-toba-green transition-all outline-none font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-2">Lokasi Kegiatan</label>
                            <input type="text" name="location" required value="{{ old('location') }}" placeholder="Contoh: Danau Toba / Medan" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-toba-green focus:border-toba-green transition-all outline-none font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-2">Jenis Kegiatan</label>
                            <select name="activity_type" required 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-toba-green focus:border-toba-green transition-all outline-none font-medium appearance-none">
                                <option value="" disabled selected>Pilih Jenis Kegiatan</option>
                                <option value="Team Building" {{ old('activity_type') == 'Team Building' ? 'selected' : '' }}>Team Building</option>
                                <option value="Fun Games" {{ old('activity_type') == 'Fun Games' ? 'selected' : '' }}>Fun Games</option>
                                <option value="Outbound Kids" {{ old('activity_type') == 'Outbound Kids' ? 'selected' : '' }}>Outbound Kids</option>
                                <option value="Family Gathering" {{ old('activity_type') == 'Family Gathering' ? 'selected' : '' }}>Family Gathering</option>
                                <option value="Lainnya" {{ old('activity_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-2">Estimasi Tanggal</label>
                            <input type="date" name="estimated_date" required value="{{ old('estimated_date') }}" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-toba-green focus:border-toba-green transition-all outline-none font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-2">No. Whatsapp</label>
                            <input type="tel" name="whatsapp" required value="{{ old('whatsapp') }}" placeholder="Contoh: 081234567890" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-toba-green focus:border-toba-green transition-all outline-none font-medium">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-5 bg-toba-green text-white rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-toba-green/20 hover:bg-emerald-600 transition-all transform hover:-translate-y-1">
                        Kirim Permintaan Penawaran
                    </button>
                </form>
            </div>
        </div>
    </section>

    @if($settings['show_stats'] ?? true)
    <!-- Stats Section -->
    <section class="py-24 bg-white overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 text-center">
                @php
                    $statsDefaults = [
                        0 => ['value' => '500+', 'label' => 'Instansi Terlayani'],
                        1 => ['value' => '10k+', 'label' => 'Peserta Outbound'],
                        2 => ['value' => '99%', 'label' => 'Tingkat Kepuasan'],
                        3 => ['value' => '15+', 'label' => 'Tahun Pengalaman']
                    ];
                @endphp
                @for($i=0; $i<4; $i++)
                    @php
                        $val = $settings['stat_value_'.$i] ?? $statsDefaults[$i]['value'];
                        $lbl = $settings['stat_label_'.$i] ?? $statsDefaults[$i]['label'];
                    @endphp
                    <div class="space-y-4">
                        <div class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter">{{ $val }}</div>
                        <div class="text-xs font-black text-slate-400 uppercase tracking-[0.3em]">{{ $lbl }}</div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    @endif

    @if($settings['show_cta'] ?? true)
    <!-- CTA & Address Section -->
    <section class="py-24 bg-[#0d131f] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-toba-green/5 rounded-full blur-[120px]"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 md:gap-24 items-center">
                <div class="space-y-10">
                    <h2 class="text-4xl md:text-6xl font-black text-white leading-tight tracking-tight">
                        {{ $settings['cta_footer_title'] ?? "Siap Memberikan Pengalaman Terbaik Untuk Tim Anda?" }}
                    </h2>
                    <div class="flex flex-col sm:flex-row gap-5">
                        <a :href="'https://wa.me/' + waNumber" class="px-10 py-5 bg-toba-green text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-2xl shadow-toba-green/20 text-center">
                            {{ $settings['cta_footer_btn'] ?? 'Konsultasi Sekarang' }}
                        </a>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-[3rem] p-10 md:p-12 backdrop-blur-xl space-y-10">
                    <!-- Specialist Card (Dynamic) -->
                    <div class="bg-white/10 p-6 rounded-[2rem] border border-white/20">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-full border-2 border-toba-green overflow-hidden shrink-0">
                                <img src="{{ imageUrl($settings['specialist_image_url'] ?? null) }}" class="w-full h-full object-cover" onerror="this.src='https://i.pravatar.cc/100?u=staff1'">
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-toba-green uppercase tracking-[0.2em]">{{ $settings['specialist_title'] ?? 'Corporate Expert' }}</p>
                                <p class="text-lg font-black text-white">{{ $settings['specialist_name'] ?? 'Sarah Anggraini' }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed italic mb-0">"{{ $settings['specialist_desc'] ?? 'Siap membantu merancang program outbound terbaik untuk perusahaan Anda.' }}"</p>
                    </div>

                    <div>
                        <h3 class="text-white font-black text-xl mb-8 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-xl bg-toba-green text-white flex items-center justify-center shadow-lg"><i class="fas fa-location-dot"></i></span>
                            Head Office
                        </h3>
                        <p class="text-slate-400 font-medium text-lg leading-relaxed mb-10">
                            {{ $settings['office_address'] ?? "Gedung Wonderful Toba Lt. 2, Jl. Ringroad No. 123, Medan, Sumatera Utara." }}
                        </p>
                        <div class="flex items-center gap-6">
                            <a href="https://instagram.com/{{ str_replace('@', '', $settings['social_instagram'] ?? 'wonderful.outbound') }}" class="text-slate-500 hover:text-white transition-colors text-2xl" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="{{ $settings['social_facebook'] ?? '#' }}" class="text-slate-500 hover:text-white transition-colors text-2xl" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="mailto:{{ $settings['contact_email'] ?? 'hello@wonderfultoba.id' }}" class="text-slate-500 hover:text-white transition-colors text-2xl"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif


</div>
@endsection
