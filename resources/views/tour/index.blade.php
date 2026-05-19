@extends('layouts.app')

@section('title', $settings['meta_title'] ?? $settings['hero_title'] ?? 'Wonderful Toba – Wisata Sumatera Utara')
@section('description', $settings['meta_description'] ?? $settings['hero_subtitle'] ?? 'Temukan keindahan Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang bersama Wonderful Toba.')

@section('content')
<div x-data="{ waNumber: '{{ preg_replace('/[^0-9]/', '', $settings['contact_wa_1'] ?? '6281323888207') }}' }">
    
    <!-- Premium Hero Slider -->
    @if($settings['show_slider'] ?? true)
    <x-home-slider :settings="$settings" :packages="$packages" />
    @endif

    <!-- Featured Packages (Updated Style) -->
    @if($settings['show_featured'] ?? true)
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div class="max-w-2xl animate-in fade-in slide-in-from-left-8 duration-1000">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="h-px w-8 bg-toba-green"></div>
                        <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Destinasi Unggulan</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                        Paket Wisata <span class="text-toba-green">Terpopuler</span>
                    </h2>
                </div>
                <a class="flex items-center space-x-3 text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0 animate-in fade-in slide-in-from-right-8 duration-1000" href="/tour/packages">
                    <span>Lihat Semua</span>
                    <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($packages->take(3) as $index => $pkg)
                @php
                    $pkgImage = $pkg->resolveImageUrl($pkg->packageImages->first()?->image_path ?? ($pkg->images[0] ?? null));
                @endphp
                <div class="group cursor-pointer animate-in fade-in slide-in-from-bottom-12 duration-1000" style="animation-delay: {{ $index * 150 }}ms">
                    <div class="relative h-[420px] rounded-[2rem] overflow-hidden mb-6 premium-shadow transition-all duration-700 hover:-translate-y-2 hover:shadow-toba-green/10">
                        <img src="{{ $pkgImage }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s] ease-out">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                        
                        <!-- Floating Rating -->
                        <div class="absolute top-6 left-6">
                            <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star text-amber-400 fill-amber-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                                <span class="font-black text-slate-900 text-xs">{{ $pkg->rating ?? '4.9' }}</span>
                            </div>
                        </div>

                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="flex items-center text-toba-accent text-[10px] font-black uppercase tracking-[0.2em] mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin mr-1.5"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                {{ $pkg->locationTag ?? 'Sumatera Utara' }}
                            </div>
                            <h3 class="text-2xl font-black text-white mb-2 tracking-tight leading-tight group-hover:text-toba-accent transition-colors">{{ $pkg->name }}</h3>
                            <p class="text-slate-300 text-xs font-medium mb-4 line-clamp-2">{{ $pkg->shortDescription }}</p>
                            
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">Durasi</p>
                                    <p class="text-white font-bold text-sm">{{ $pkg->duration }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">Mulai Dari</p>
                                    <p class="text-xl font-black text-white"><span class="text-xs font-bold text-toba-accent mr-1">Rp</span>{{ number_format($pkg->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="flex items-center justify-center gap-2 w-full py-3.5 bg-slate-50 hover:bg-toba-green hover:text-white text-slate-700 rounded-2xl font-bold text-sm transition-all duration-300 group-hover:bg-toba-green group-hover:text-white" href="/tour/package/{{ $pkg->slug ?: $pkg->id }}">
                        Lihat Detail <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Why Us Section (Interactive Dark Mode Canvas Explorer) -->
    @if($settings['show_about'] ?? true)
    @php
        $whyImg1 = imageUrl($settings['why_image_1_url'] ?? null, asset('images/home/tour.webp'));
        $whyImg2 = imageUrl($settings['why_image_2_url'] ?? null, asset('images/home/outbound.webp'));
        $whyImg3 = imageUrl($settings['why_image_3_url'] ?? null, asset('images/home/tour.webp'));
        
        $ctaImg = imageUrl($settings['cta_image_url'] ?? null, 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000');
    @endphp
    <section x-data="{ activeTab: 1 }" class="py-24 md:py-40 bg-gradient-to-br from-slate-900 via-slate-950 to-emerald-950 text-white relative overflow-hidden">
        <!-- Luxury Glowing Atmosphere & Cosmic Grid -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 32px 32px;"></div>
        <div class="absolute -top-48 -right-48 w-[600px] h-[600px] bg-toba-green/20 rounded-full blur-[150px] pointer-events-none animate-pulse duration-[8s]"></div>
        <div class="absolute -bottom-48 -left-48 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[130px] pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/3 w-[300px] h-[300px] bg-sky-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-center lg:items-stretch">
                
                <!-- Left Column: Dynamic Visual Canvas (Morphing Showcase Stack) -->
                <div class="lg:col-span-7 relative flex items-center justify-center min-h-[460px] md:min-h-[640px] px-8 md:px-12 select-none">
                    
                    <!-- Ambient Dynamic Blurred Reflection Backdrop -->
                    <div class="absolute inset-0 -z-10 opacity-35 blur-[120px] scale-110 pointer-events-none transition-all duration-[1.2s] ease-out">
                        <div x-show="activeTab === 1" x-transition:enter="transition opacity duration-1000" x-transition:leave="transition opacity duration-500 absolute" class="absolute inset-0 bg-gradient-to-tr from-toba-green to-emerald-500 rounded-full"></div>
                        <div x-show="activeTab === 2" x-transition:enter="transition opacity duration-1000" x-transition:leave="transition opacity duration-500 absolute" class="absolute inset-0 bg-gradient-to-tr from-emerald-500 to-sky-500 rounded-full"></div>
                        <div x-show="activeTab === 3" x-transition:enter="transition opacity duration-1000" x-transition:leave="transition opacity duration-500 absolute" class="absolute inset-0 bg-gradient-to-tr from-amber-500 to-toba-accent rounded-full"></div>
                    </div>

                    <div class="relative w-full max-w-[480px] lg:max-w-none mx-auto aspect-[4/3] sm:aspect-[1.2] md:aspect-[1.25] lg:aspect-[0.95] xl:aspect-[1.05]">
                        
                        <!-- Visual Card 1 (Layanan Exclusive) -->
                        <div class="absolute inset-0 w-full h-full rounded-[3.5rem] overflow-hidden border border-white/10 group/canvas1 transition-all duration-700 ease-out"
                             :class="{
                                 'z-30 opacity-100 scale-100 translate-x-0 translate-y-0 rotate-0 pointer-events-auto shadow-[0_30px_80px_rgba(0,0,0,0.6)]': activeTab === 1,
                                 'z-20 opacity-50 scale-95 translate-x-6 md:translate-x-10 translate-y-4 rotate-3 blur-[0.5px] pointer-events-none shadow-[0_15px_30px_rgba(0,0,0,0.3)]': activeTab === 2,
                                 'z-10 opacity-30 scale-90 -translate-x-6 md:-translate-x-10 -translate-y-4 -rotate-3 blur-[1.5px] pointer-events-none shadow-[0_10px_20px_rgba(0,0,0,0.2)]': activeTab === 3
                             }">
                            <img src="{{ $whyImg1 }}" alt="Layanan Exclusive" class="w-full h-full object-cover transition-transform duration-[4s] group-hover/canvas1:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                            
                            <!-- Floating Trust Banner inside image -->
                            <div class="absolute bottom-6 left-6 right-6 bg-slate-950/70 backdrop-blur-xl p-5 rounded-[2rem] border border-white/10 flex items-center justify-between shadow-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-toba-green/20 flex items-center justify-center text-toba-accent">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Standardisasi</p>
                                        <h4 class="text-sm font-black text-white leading-tight">Hotel & Armada Premium</h4>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] text-slate-400 font-bold leading-none mb-1">Trip Selesai</p>
                                    <span class="text-sm font-black text-white">1.5K+ Klien</span>
                                </div>
                            </div>
                        </div>

                        <!-- Visual Card 2 (Keamanan Tanpa Cela) -->
                        <div class="absolute inset-0 w-full h-full rounded-[3.5rem] overflow-hidden border border-white/10 group/canvas2 transition-all duration-700 ease-out"
                             :class="{
                                 'z-30 opacity-100 scale-100 translate-x-0 translate-y-0 rotate-0 pointer-events-auto shadow-[0_30px_80px_rgba(0,0,0,0.6)]': activeTab === 2,
                                 'z-20 opacity-50 scale-95 translate-x-6 md:translate-x-10 translate-y-4 rotate-3 blur-[0.5px] pointer-events-none shadow-[0_15px_30px_rgba(0,0,0,0.3)]': activeTab === 3,
                                 'z-10 opacity-30 scale-90 -translate-x-6 md:-translate-x-10 -translate-y-4 -rotate-3 blur-[1.5px] pointer-events-none shadow-[0_10px_20px_rgba(0,0,0,0.2)]': activeTab === 1
                             }">
                            <img src="{{ $whyImg2 }}" alt="Keamanan Terjamin" class="w-full h-full object-cover transition-transform duration-[4s] group-hover/canvas2:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                            
                            <div class="absolute bottom-6 left-6 right-6 bg-slate-950/70 backdrop-blur-xl p-5 rounded-[2rem] border border-white/10 flex items-center justify-between shadow-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                        <i class="fas fa-shield-halved"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Proteksi</p>
                                        <h4 class="text-sm font-black text-white leading-tight">Asuransi & Pemandu Resmi</h4>
                                    </div>
                                </div>
                                <div class="bg-toba-green/20 border border-toba-green/30 px-3 py-1 rounded-lg text-toba-accent font-black text-[10px] uppercase tracking-wider">
                                    Aman & Legal
                                </div>
                            </div>
                        </div>

                        <!-- Visual Card 3 (Kearifan Lokal) -->
                        <div class="absolute inset-0 w-full h-full rounded-[3.5rem] overflow-hidden border border-white/10 group/canvas3 transition-all duration-700 ease-out"
                             :class="{
                                 'z-30 opacity-100 scale-100 translate-x-0 translate-y-0 rotate-0 pointer-events-auto shadow-[0_30px_80px_rgba(0,0,0,0.6)]': activeTab === 3,
                                 'z-20 opacity-50 scale-95 translate-x-6 md:translate-x-10 translate-y-4 rotate-3 blur-[0.5px] pointer-events-none shadow-[0_15px_30px_rgba(0,0,0,0.3)]': activeTab === 1,
                                 'z-10 opacity-30 scale-90 -translate-x-6 md:-translate-x-10 -translate-y-4 -rotate-3 blur-[1.5px] pointer-events-none shadow-[0_10px_20px_rgba(0,0,0,0.2)]': activeTab === 2
                             }">
                            <img src="{{ $whyImg3 }}" alt="Budaya Danau Toba" class="w-full h-full object-cover transition-transform duration-[4s] group-hover/canvas3:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                            
                            <div class="absolute bottom-6 left-6 right-6 bg-slate-950/70 backdrop-blur-xl p-5 rounded-[2rem] border border-white/10 flex items-center justify-between shadow-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400">
                                        <i class="fas fa-heart-circle-check"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Autentisitas</p>
                                        <h4 class="text-sm font-black text-white leading-tight">Interaksi Budaya Asli</h4>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] text-slate-400 font-bold leading-none mb-1">Kepuasan</p>
                                    <span class="text-sm font-black text-amber-400"><i class="fas fa-star mr-1"></i>4.9 / 5.0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Outer Floating Glass Accent Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white/5 backdrop-blur-xl p-4.5 rounded-[2rem] border border-white/10 flex items-center gap-3 shadow-2xl animate-float z-40">
                            <div class="w-10 h-10 rounded-xl bg-toba-green/20 flex items-center justify-center text-toba-accent shadow-inner">
                                <i class="fas fa-compass text-lg animate-spin" style="animation-duration: 15s;"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-0.5">{{ $settings['stat_label_0'] ?? 'Wisatawan' }}</p>
                                <p class="text-xl font-black text-white leading-none">{{ $settings['stat_value_0'] ?? '10k+' }}</p>
                            </div>
                        </div>

                        <!-- Outer Glowing Accent Ring -->
                        <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full border-2 border-white/10 flex items-center justify-center opacity-30 animate-pulse z-40">
                            <div class="w-16 h-16 rounded-full border border-dashed border-white/20"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive Deck Trigger & Typography -->
                <div class="lg:col-span-5 flex flex-col justify-center py-4">
                    <div class="inline-flex items-center gap-2.5 px-4.5 py-2 bg-white/5 border border-white/10 text-toba-accent rounded-full font-black text-xs uppercase tracking-[0.2em] mb-6">
                        <span class="w-2 h-2 rounded-full bg-toba-accent animate-ping"></span>
                        <span>Standard of Excellence</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl lg:text-[3.25rem] font-black text-white mb-6 tracking-tight leading-[1.05]">
                        Eksplorasi dengan <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-toba-accent via-emerald-400 to-teal-300">Kemewahan Sejati</span>
                    </h2>
                    
                    <p class="text-slate-400 font-medium leading-relaxed text-sm md:text-base mb-10 max-w-xl opacity-90">
                        Kami merancang setiap detail perjalanan Anda dengan presisi tinggi demi menghadirkan kenyamanan mutlak dan kebahagiaan sejati selama menjelajahi pesona legendaris Danau Toba.
                    </p>
                    
                    <!-- Dynamic Interactive Tab Space -->
                    <div class="space-y-4">
                        @for($i=1; $i<=3; $i++)
                        @php
                            $icon = $i == 1 ? 'fa-award' : ($i == 2 ? 'fa-shield-halved' : 'fa-heart-circle-check');
                            $titles = [
                                1 => 'Layanan Exclusive',
                                2 => 'Keamanan Tanpa Cela',
                                3 => 'Kearifan Lokal Autentik'
                            ];
                            $descriptions = [
                                1 => 'Standar kenyamanan kelas satu dengan akomodasi hotel premium serta armada transportasi terawat luar dalam.',
                                2 => 'Perjalanan terproteksi penuh bersama pemandu lokal berlisensi resmi dan asuransi perjalanan terpercaya.',
                                3 => 'Menghubungkan Anda langsung ke jantung kebudayaan Batak melalui aktivitas kultural dan kuliner asli.'
                            ];
                            $title = $settings['about_title_'.$i] ?? $titles[$i];
                            $desc = $settings['about_desc_'.$i] ?? $descriptions[$i];
                        @endphp
                        
                        <div 
                            @mouseenter="activeTab = {{ $i }}" 
                            @click="activeTab = {{ $i }}"
                            class="group cursor-pointer relative rounded-[2rem] p-6.5 border transition-all duration-500 flex items-start gap-5 select-none"
                            :class="activeTab === {{ $i }} ? 'bg-white/10 border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.3)] translate-x-3.5' : 'bg-transparent border-transparent opacity-40 hover:opacity-85 hover:translate-x-1.5'"
                        >
                            <!-- Elegant Left Colored Bar for Active tab -->
                            <div x-show="activeTab === {{ $i }}" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-y-0"
                                 x-transition:enter-end="opacity-100 scale-y-100"
                                 class="absolute left-0 top-6 bottom-6 w-1.5 bg-gradient-to-b from-toba-accent to-emerald-400 rounded-full">
                            </div>

                            <!-- Animated Glowing Icon Container -->
                            <div class="w-13 h-13 rounded-xl flex items-center justify-center shrink-0 transition-all duration-500 relative z-10"
                                 :class="activeTab === {{ $i }} ? 'bg-toba-accent text-white shadow-[0_0_25px_rgba(21,128,61,0.5)] ring-2 ring-white/10 scale-105' : 'bg-white/5 text-slate-400 group-hover:bg-white/10 group-hover:text-white'">
                                <i class="fas {{ $icon }} text-xl transition-transform duration-500 group-hover:rotate-[10deg]"></i>
                            </div>
                            
                            <!-- Tab Body Content -->
                            <div class="relative z-10 flex-1">
                                <h4 class="text-lg font-black tracking-tight transition-colors duration-300"
                                    :class="activeTab === {{ $i }} ? 'text-white' : 'text-slate-300 group-hover:text-white'">
                                    {{ $title }}
                                </h4>
                                <p class="font-medium leading-relaxed text-xs md:text-sm mt-1 transition-colors duration-300"
                                   :class="activeTab === {{ $i }} ? 'text-slate-200' : 'text-slate-500'">
                                    {{ $desc }}
                                </p>
                            </div>

                            <!-- Tiny Chevron indicator -->
                            <div class="self-center text-slate-600 transition-all duration-300"
                                 :class="activeTab === {{ $i }} ? 'translate-x-0 opacity-100 text-toba-accent' : 'opacity-0 -translate-x-2'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

            </div>
        </div>
    </section>
    @endif

    <!-- Premium Testimonials -->
    @if($settings['show_testimonials'] ?? true)
    <section class="py-24 md:py-40 relative overflow-hidden bg-white">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full opacity-[0.03] pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 0 L100 0 L100 100 L0 100 Z" fill="url(#grid)"/></svg>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-8 text-center relative z-10">
            <div class="flex items-center justify-center space-x-3 mb-6">
                <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">Testimoni</span>
                <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
            </div>
            <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight mb-20 md:mb-28 leading-tight">
                Momen Indah <br /> <span class="text-toba-green">Dari Pelanggan Kami</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16">
                @php
                    $testimonials = $settings['testimonials'] ?? [
                        ['name' => 'Budi Santoso', 'text' => 'Pelayanan sangat memuaskan, hotel bintang 4 sesuai janji. Tour guide sangat ramah dan sabar.', 'image' => null],
                        ['name' => 'Ani Wijaya', 'text' => 'Paket Danau Toba 3D2N sangat berkesan. Anak-anak senang sekali dengan kegiatannya.', 'image' => null]
                    ];
                @endphp
                @foreach($testimonials as $t)
                <div class="relative group">
                    <div class="absolute -inset-4 bg-slate-50 rounded-[3rem] opacity-0 group-hover:opacity-100 transition-all duration-700 -z-10 scale-95 group-hover:scale-100"></div>
                    <div class="bg-white border border-slate-100 rounded-[3rem] p-10 md:p-14 shadow-2xl shadow-slate-200/50 text-left relative overflow-hidden transition-all duration-500 group-hover:border-toba-green/20">
                        <i class="fas fa-quote-right absolute top-10 right-10 text-6xl text-slate-50"></i>
                        <div class="flex gap-1.5 mb-8">
                            @for($j=0; $j<5; $j++)
                                <svg class="w-4 h-4 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                        </div>
                        <p class="text-slate-700 text-lg md:text-xl leading-relaxed mb-10 font-medium italic relative z-10">&ldquo;{{ $t['text'] }}&rdquo;</p>
                        <div class="flex items-center gap-5 pt-8 border-t border-slate-100">
                            <div class="relative">
                                <img src="{{ !empty($t['image']) ? (Str::startsWith($t['image'], 'http') ? $t['image'] : asset('storage/'.$t['image'])) : 'https://ui-avatars.com/api/?name='.urlencode($t['name']).'&background=10b981&color=fff' }}" alt="{{ $t['name'] }}" class="w-16 h-16 rounded-2xl object-cover shadow-lg" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($t['name']) }}&background=10b981&color=fff'">
                                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-toba-accent rounded-full border-4 border-white flex items-center justify-center">
                                    <svg class="w-3 h-3 text-slate-900" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                </div>
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-lg tracking-tight">{{ $t['name'] }}</p>
                                <p class="text-[10px] text-toba-green font-black uppercase tracking-[0.2em]">{{ $t['location'] ?? 'Wisatawan Terverifikasi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Specialist Section -->
    @if($settings['show_specialist'] ?? true)
    <section class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="bg-white/5 backdrop-blur-xl rounded-[4rem] p-10 md:p-20 border border-white/10 relative overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="relative">
                        <div class="aspect-square rounded-[3rem] overflow-hidden relative z-10 ring-8 ring-white/5">
                            <img src="{{ imageUrl($settings['specialist_image_url'] ?? '', 'https://i.pravatar.cc/600?u=staff1') }}" alt="Specialist" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-toba-green/20 rounded-full blur-[80px]"></div>
                    </div>
                    <div class="animate-in fade-in slide-in-from-right-12 duration-1000">
                        <div class="flex items-center space-x-3 mb-8">
                            <div class="h-1.5 w-12 bg-toba-accent rounded-full"></div>
                            <span class="text-toba-accent font-black text-xs uppercase tracking-[0.4em]">Personal Specialist</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight leading-tight">
                            Punya Pertanyaan <br /><span class="text-toba-accent">Seputar Perjalanan?</span>
                        </h2>
                        <p class="text-slate-400 text-lg font-medium leading-relaxed mb-10">
                            {{ $settings['specialist_desc'] ?? 'Halo! Saya ' . ($settings['specialist_name'] ?? 'Sarah') . '. Saya siap membantu merencanakan liburan impian Anda di Sumatera Utara dengan layanan personal dan ramah.' }}
                        </p>
                        
                        <div class="flex items-center gap-6 mb-12">
                            <div class="bg-white/10 w-px h-16"></div>
                            <div>
                                <p class="text-white font-black text-xl tracking-tight">{{ $settings['specialist_name'] ?? 'Sarah Anggraini' }}</p>
                                <p class="text-toba-accent text-xs font-black uppercase tracking-widest">{{ $settings['specialist_title'] ?? 'Travel Specialist' }}</p>
                            </div>
                        </div>

                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['contact_wa_1'] ?? '6281323888207') }}?text=Halo%20{{ $settings['specialist_name'] ?? 'Sarah' }},%20saya%20ingin%20tanya%20paket%20tour..." class="inline-flex items-center gap-4 bg-white text-slate-900 px-10 py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-toba-accent transition-all group">
                            <i class="fab fa-whatsapp text-xl"></i>
                            Chat via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Blog Section (Magazine Style) -->
    @if($settings['show_blogs'] ?? true)
    <section class="py-24 md:py-40 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
                <div class="max-w-2xl">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                        <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">Wawasan & Artikel</span>
                    </div>
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                        Jurnal <span class="text-toba-green">Eksplorasi</span>
                    </h2>
                </div>
                <a href="/tour/blog" class="group flex items-center gap-4 hover:text-toba-green transition-all">
                    <span class="text-sm font-black text-slate-900 uppercase tracking-widest">Semua Artikel</span>
                    <div class="w-12 h-12 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all group-hover:border-toba-green group-hover:bg-toba-green group-hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                @foreach($blogs as $blog)
                <article class="group">
                    <a href="{{ route('tour.blog.detail', $blog->slug) }}" class="block">
                        <div class="relative h-80 rounded-[2.5rem] overflow-hidden mb-8 shadow-xl">
                            <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                            <div class="absolute top-6 left-6">
                                <span class="bg-white/90 backdrop-blur px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-900">
                                    {{ $blog->category ?? 'Travel' }}
                                </span>
                            </div>
                        </div>
                        <div class="px-2">
                            <time class="text-[10px] font-black text-toba-green uppercase tracking-[0.3em] mb-4 block">
                                {{ date('d F Y', strtotime($blog->createdAt)) }}
                            </time>
                            <h3 class="text-2xl font-black text-slate-900 mb-4 leading-tight group-hover:text-toba-green transition-colors tracking-tight">
                                {{ $blog->title }}
                            </h3>
                            <p class="text-slate-500 text-sm font-medium line-clamp-3 leading-relaxed mb-6">{{ $blog->excerpt }}</p>
                            <div class="flex items-center gap-2 text-slate-900 font-black text-[10px] uppercase tracking-widest group-hover:gap-4 transition-all">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 text-toba-green" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Cinema CTA -->
    <section class="py-24 md:py-32 px-6 md:px-8 bg-white">
        <div class="max-w-7xl mx-auto bg-slate-900 rounded-[4rem] p-12 md:p-24 relative overflow-hidden shadow-[0_50px_100px_-20px_rgba(15,23,42,0.3)]">
            <div class="absolute inset-0 opacity-40">
                <img src="{{ $ctaImg }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900/60 to-transparent"></div>
            
            <!-- Animated Circles Overlay -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-toba-green/20 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-toba-accent/10 rounded-full blur-[120px]"></div>

            <div class="relative z-10 text-center lg:text-left max-w-4xl">
                <h2 class="text-4xl md:text-7xl font-black text-white mb-8 tracking-tighter leading-[0.95]">
                    Siap Untuk <br/> <span class="text-toba-green">Petualangan Nyata?</span>
                </h2>
                <p class="text-xl text-slate-300 mb-12 font-medium leading-relaxed max-w-2xl">
                    Bergabunglah dengan <span class="text-white font-black">{{ $settings['stat_value_1'] ?? '10K+' }}</span> petualang lainnya yang telah menemukan keindahan Sumatera Utara bersama kami.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <a href="/tour/packages" class="bg-toba-green text-white px-12 py-6 rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] hover:bg-toba-accent hover:text-slate-900 transition-all duration-500 shadow-2xl shadow-toba-green/30 group flex items-center gap-3">
                        <span>Pesan Paket Sekarang</span>
                        <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <div class="flex -space-x-4">
                        @for($i=1; $i<=4; $i++)
                            <img src="https://i.pravatar.cc/100?u={{ $i }}" class="w-14 h-14 rounded-full border-4 border-slate-900 shadow-xl" alt="User avatar">
                        @endfor
                        <div class="w-14 h-14 rounded-full border-4 border-slate-900 bg-slate-800 flex items-center justify-center text-white text-[10px] font-black">
                            +99
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>

<style>
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-subtle {
        animation: bounce-subtle 3s infinite ease-in-out;
    }
</style>
@endsection
