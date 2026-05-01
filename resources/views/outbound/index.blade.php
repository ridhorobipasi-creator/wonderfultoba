@extends('layouts.app')

@section('title', 'Wonderful Toba Outbound - Provider Outbound Terbaik di Sumatera Utara')
@section('description', 'Wonderful Toba Outbound: Provider terbaik untuk Team Building, Gathering, Fun Games di Medan & Sumatera Utara.')

@section('content')
<div 
    x-data="{ 
        currentSlide: 0, 
        activeVideoTab: 0,
        heroImages: [
            '{{ $settings['hero_image'] ?? 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=2000' }}',
            'https://images.unsplash.com/photo-1552664730-d307ca884978?w=2000',
            'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=2000'
        ],
        about: {
            title: 'Apa itu Outbound?',
            description: 'Outbound adalah metode pembelajaran berbasis pengalaman di alam terbuka yang dirancang untuk membangun karakter, kepemimpinan, dan kerjasama tim secara efektif.',
            statsLabel: 'Tahun',
            statsValue: '12+'
        },
        init() {
            setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.heroImages.length;
            }, 5000);
        }
    }"
    class="font-sans antialiased text-slate-800 bg-[#fbf9f8] min-h-screen"
>
    <!-- Hero Section -->
    <section class="relative h-[100dvh] min-h-[600px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <template x-for="(img, i) in heroImages" :key="i">
                <img 
                    x-show="currentSlide === i"
                    :src="img" 
                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
                    :class="currentSlide === i ? 'opacity-100 scale-100' : 'opacity-0 scale-105'"
                >
            </template>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-900/40 to-slate-900/90 mix-blend-multiply"></div>
        </div>
        
        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center mt-10">
            <div class="flex flex-col items-center">
                <img 
                   src="/assets/images/2023/09/Logo-Wonderful-Toba-Outbound-White-1.png" 
                   alt="Wonderful Toba Outbound Logo" 
                   class="w-auto h-28 md:h-36 mb-6 drop-shadow-[0_0_15px_rgba(255,255,255,0.4)]" 
                >
                
                <h1 class="text-4xl md:text-5xl lg:text-7xl text-white font-black max-w-4xl mx-auto mb-6 leading-[1.1] md:leading-tight drop-shadow-2xl">
                    {{ $settings['hero_title'] ?? 'Solusi Team Building Terbaik di Sumatera' }}
                </h1>
                <p class="text-lg md:text-2xl text-white font-medium max-w-3xl mx-auto mb-10 md:mb-12 leading-relaxed drop-shadow-xl">
                    {{ $settings['hero_subtitle'] ?? 'Tingkatkan sinergi dan produktivitas tim Anda dengan program outbound profesional.' }}
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
                    <a href="/outbound/packages" class="w-full sm:w-auto bg-toba-green text-white px-10 py-4 rounded-full font-black tracking-widest text-sm uppercase hover:bg-emerald-600 transition-colors shadow-2xl flex items-center justify-center gap-2">
                        Pricelist & Paket
                    </a>
                    <a href="https://wa.me/6281323888207" class="w-full sm:w-auto bg-white/20 backdrop-blur-md border border-white/50 text-white px-10 py-4 rounded-full font-black tracking-widest text-sm uppercase hover:bg-white hover:text-slate-900 transition-colors shadow-2xl flex items-center justify-center">
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
    <section id="tentangkami" class="py-20 md:py-32 bg-[#fdfdfd] relative top-0 z-20 -mt-10 rounded-t-[3rem] shadow-[0_-20px_40px_-15px_rgba(0,0,0,0.05)] overflow-hidden">
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
                        <span x-text="about.title"></span>
                        <span class="text-toba-green relative inline-block">
                            Outbound?
                            <svg class="absolute w-[110%] h-4 -bottom-1 left-[-5%] text-toba-green/20" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0 15 Q 50 0 100 15" stroke="currentColor" stroke-width="8" fill="none" stroke-linecap="round" /></svg>
                        </span>
                    </h2>
                    
                    <div class="relative mb-12">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-toba-green to-emerald-200 rounded-full"></div>
                        <div class="pl-8 text-xl leading-relaxed text-slate-600 font-medium" x-text="about.description"></div>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-[2.5rem] p-8 md:p-10 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] relative hover:shadow-[0_20px_60px_-15px_rgba(16,185,129,0.1)] transition-all duration-500">
                        <h3 class="text-xl md:text-2xl font-black mb-8 text-slate-900 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </span>
                            Kenapa Harus Kami?
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            @php
                                $reasons = [
                                    ['text' => 'Trainer tersertifikasi pakar', 'icon' => 'users'],
                                    ['text' => 'Vendor venue resort premium', 'icon' => 'map-pin'],
                                    ['text' => 'Games interaktif & adaptif', 'icon' => 'target'],
                                    ['text' => 'Konsep event kreatif berdampak', 'icon' => 'compass']
                                ];
                            @endphp
                            @foreach($reasons as $r)
                                <div class="flex items-start gap-4 group cursor-default">
                                    <div class="mt-1 bg-slate-50 p-2.5 rounded-xl group-hover:bg-toba-green group-hover:text-white group-hover:scale-110 transition-all duration-300 text-toba-green shadow-sm">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            @if($r['icon'] == 'users') <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            @elseif($r['icon'] == 'map-pin') <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                            @elseif($r['icon'] == 'target') <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                                            @elseif($r['icon'] == 'compass') <circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                                            @endif
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-700 text-sm md:text-base leading-snug tracking-wide group-hover:text-slate-900 transition-colors">{{ $r['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 relative mt-24 lg:mt-0">
                    <div class="relative w-4/5 ml-auto -right-4 top-0 z-10 rounded-[2.5rem] overflow-hidden shadow-2xl hover:-translate-y-2 transition-transform duration-500">
                        <img src="/assets/images/2023/10/A11-Team-Building.jpg" alt="Team Building" class="w-full aspect-[4/5] object-cover">
                    </div>
                    <div class="absolute w-3/4 -bottom-12 -left-4 z-20 rounded-[2.5rem] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] border-[6px] border-white hover:scale-105 transition-transform duration-500">
                        <img src="/assets/images/2023/10/003-1.jpg" alt="Corporate Activity" class="w-full aspect-square object-cover">
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

    <!-- Layanan Kami -->
    <section id="layanan" class="py-20 md:py-32 bg-[#090e17] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 md:mb-20 gap-8">
                <div class="max-w-2xl">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight">Kapasitas <span class="text-transparent bg-clip-text bg-gradient-to-r from-toba-green to-emerald-400">Layanan Kami</span></h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($services as $i => $svc)
                    <div class="group relative rounded-[2.5rem] p-1 overflow-hidden bg-gradient-to-b from-white/10 to-white/5 hover:from-toba-green/40 hover:to-toba-green/5 transition-colors duration-500">
                        <div class="relative w-full h-full bg-[#0d131f] rounded-[2.3rem] overflow-hidden flex flex-col items-start border border-white/5">
                            <div class="w-full h-56 relative overflow-hidden">
                                <img src="{{ $svc->image }}" alt="{{ $svc->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                            </div>
                            <div class="p-8 pt-4 flex-1 flex flex-col z-10 w-full relative">
                                <h3 class="text-2xl font-black text-white mb-2 tracking-tight group-hover:text-toba-green transition-colors leading-tight">{{ $svc->title }}</h3>
                                <p class="text-toba-green font-bold text-xs mb-5 uppercase tracking-widest bg-toba-green/10 inline-block px-3 py-1 rounded-lg w-max">{{ $svc->shortDesc }}</p>
                                <p class="text-slate-400 font-medium leading-relaxed text-sm line-clamp-3">{{ $svc->detailDesc }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Video Highlights -->
    <section class="py-24 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">Highlight <span class="text-toba-green">Video Event</span></h2>
            </div>
            
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                @foreach($videos as $i => $vid)
                    <button 
                        @click="activeVideoTab = {{ $i }}"
                        :class="activeVideoTab === {{ $i }} ? 'bg-slate-900 text-white shadow-xl scale-105' : 'bg-white text-slate-600 border border-slate-200'"
                        class="px-6 py-3.5 rounded-full font-bold text-sm transition-all flex items-center gap-3"
                    >
                        {{ $vid->title }}
                    </button>
                @endforeach
            </div>
            
            <div class="aspect-video w-full max-w-5xl mx-auto rounded-[2.5rem] overflow-hidden shadow-2xl bg-slate-900 relative ring-8 ring-white">
                @foreach($videos as $i => $vid)
                    @php
                        $embedUrl = $vid->youtubeUrl;
                        if(str_contains($embedUrl, 'watch?v=')) {
                            $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                            $embedUrl = explode('&', $embedUrl)[0];
                        }
                    @endphp
                    <iframe
                        x-show="activeVideoTab === {{ $i }}"
                        src="{{ $embedUrl }}"
                        class="w-full h-full absolute inset-0 z-10"
                        allowfullscreen
                    ></iframe>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Klien Kami -->
    <section id="klien" class="py-24 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-black text-slate-900 mb-16">Dipercaya Oleh Berbagai <span class="text-toba-green">Instansi</span></h2>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-8 items-center justify-items-center opacity-70">
                @foreach($clients as $c)
                    <img src="{{ $c->logo }}" class="h-12 object-contain filter grayscale hover:grayscale-0 transition-all">
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
