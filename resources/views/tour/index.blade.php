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
                        <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em]">{{ __('Destinasi Unggulan') }}</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                        {{ __('Paket Wisata') }} <span class="text-toba-green">{{ __('Terpopuler') }}</span>
                    </h2>
                </div>
                <a class="flex items-center space-x-3 text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0 animate-in fade-in slide-in-from-right-8 duration-1000" href="/tour/packages">
                    <span>{{ __('Lihat Semua') }}</span>
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
                                {{ __($pkg->locationTag ?? 'Sumatera Utara') }}
                            </div>
                            <h3 class="text-2xl font-black text-white mb-2 tracking-tight leading-tight group-hover:text-toba-accent transition-colors">{{ $pkg->name }}</h3>
                            <p class="text-slate-300 text-xs font-medium mb-4 line-clamp-2">{{ $pkg->shortDescription }}</p>
                            
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">{{ __('Durasi') }}</p>
                                    <p class="text-white font-bold text-sm">{{ $pkg->duration }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">{{ __('Mulai dari') }}</p>
                                    <p class="text-xl font-black text-white">{{ \App\Helpers\CurrencyHelper::formatPrice($pkg->price) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="flex items-center justify-center gap-2 w-full py-3.5 bg-slate-50 hover:bg-toba-green hover:text-white text-slate-700 rounded-2xl font-bold text-sm transition-all duration-300 group-hover:bg-toba-green group-hover:text-white" href="/tour/package/{{ $pkg->slug ?: $pkg->id }}">
                        {{ __('Lihat Detail') }} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
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
                                    <p class="text-[9px] text-slate-400 font-bold leading-none mb-1">{{ __('Trip Selesai') }}</p>
                                    <span class="text-sm font-black text-white">
                                        @php
                                            $touristsCount = $settings['stat_customers'] ?? '5000+';
                                            if (strpos($touristsCount, '5000') !== false || strpos($touristsCount, '5K') !== false) {
                                                $touristsCount = __('5.000+');
                                            }
                                        @endphp
                                        {{ $touristsCount }} {{ __('Wisatawan Puas') }}
                                    </span>
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
                        <span>{{ __('Standard of Excellence') }}</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl lg:text-[3.25rem] font-black text-white mb-6 tracking-tight leading-[1.05]">
                        {{ __('Eksplorasi dengan') }} <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-toba-accent via-emerald-400 to-teal-300">{{ __('Kemewahan Sejati') }}</span>
                    </h2>
                    
                    <p class="text-slate-400 font-medium leading-relaxed text-sm md:text-base mb-10 max-w-xl opacity-90">
                        {{ __('Kami merancang setiap detail perjalanan Anda dengan presisi tinggi demi menghadirkan kenyamanan mutlak dan kebahagiaan sejati selama menjelajahi pesona legendaris Danau Toba.') }}
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
                                    {{ __($title) }}
                                </h4>
                                <p class="font-medium leading-relaxed text-xs md:text-sm mt-1 transition-colors duration-300"
                                   :class="activeTab === {{ $i }} ? 'text-slate-200' : 'text-slate-500'">
                                    {{ __($desc) }}
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
                <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">{{ __('Testimoni') }}</span>
                <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
            </div>
            <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight mb-20 md:mb-28 leading-tight">
                {{ __('Momen Indah') }} <br /> <span class="text-toba-green">{{ __('Dari Pelanggan Kami') }}</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16">
                @php
                    $testimonials = $settings['testimonials'] ?? [
                        [
                            'name' => 'Farhan Haris',
                            'location' => 'Kuala Lumpur, Malaysia',
                            'text' => 'Wonderful Toba arranged a perfect 4D3N trip for our family. The view from Samosir Island was breathtaking, and the private transport was extremely comfortable. Highly recommended for Malaysian travelers!',
                            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&h=150&q=80'
                        ],
                        [
                            'name' => 'Cheryl Lim',
                            'location' => 'Singapore',
                            'text' => 'Very professional service. The tour guide was very knowledgeable, spoke fluent English, and the lakefront resort in Samosir was stunning. Smooth booking and hassle-free payment via Wise. A 5-star experience!',
                            'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&h=150&q=80'
                        ]
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
                        <p class="text-slate-700 text-lg md:text-xl leading-relaxed mb-10 font-medium italic relative z-10">&ldquo;{{ __($t['text']) }}&rdquo;</p>
                        <div class="flex items-center gap-5 pt-8 border-t border-slate-100">
                            <div class="relative">
                                <img src="{{ !empty($t['image']) ? (Str::startsWith($t['image'], 'http') ? $t['image'] : asset('storage/'.$t['image'])) : 'https://ui-avatars.com/api/?name='.urlencode($t['name']).'&background=10b981&color=fff' }}" alt="{{ $t['name'] }}" class="w-16 h-16 rounded-2xl object-cover shadow-lg" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($t['name']) }}&background=10b981&color=fff'">
                                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-toba-accent rounded-full border-4 border-white flex items-center justify-center">
                                    <svg class="w-3 h-3 text-slate-900" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                </div>
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-lg tracking-tight">{{ $t['name'] }}</p>
                                <p class="text-[10px] text-toba-green font-black uppercase tracking-[0.2em]">{{ __($t['location'] ?? 'Wisatawan Terverifikasi') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- TripAdvisor & Google review badges row (Temuan #14) -->
            <div class="mt-20 pt-10 border-t border-slate-100 flex flex-wrap items-center justify-center gap-8 md:gap-12 animate-in fade-in slide-in-from-bottom-8 duration-1000">
                <!-- Google Badge -->
                <a href="https://google.com" target="_blank" rel="noopener noreferrer" class="flex items-center gap-4 bg-slate-50 hover:bg-slate-100 border border-slate-100 hover:border-slate-200 px-6 py-4 rounded-[2rem] transition-all group shadow-sm">
                    <div class="w-10 h-10 flex items-center justify-center bg-white rounded-2xl shadow-sm">
                        <svg viewBox="0 0 24 24" width="22" height="22" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22c-.24-.63-.37-1.3-.37-2.09z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="flex items-center gap-1">
                            <span class="font-black text-slate-900 text-sm">4.9</span>
                            <div class="flex text-amber-400">
                                <i class="fas fa-star text-[10px]"></i>
                                <i class="fas fa-star text-[10px]"></i>
                                <i class="fas fa-star text-[10px]"></i>
                                <i class="fas fa-star text-[10px]"></i>
                                <i class="fas fa-star text-[10px]"></i>
                            </div>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none group-hover:text-toba-green transition-colors">{{ __('Ulasan Google') }}</span>
                    </div>
                </a>

                <!-- TripAdvisor Badge -->
                <a href="https://tripadvisor.com" target="_blank" rel="noopener noreferrer" class="flex items-center gap-4 bg-slate-50 hover:bg-slate-100 border border-slate-100 hover:border-slate-200 px-6 py-4 rounded-[2rem] transition-all group shadow-sm">
                    <div class="w-10 h-10 flex items-center justify-center bg-white rounded-2xl shadow-sm text-[#00AF87]">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-3.5 13c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm0-4c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5zm7 4c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm0-4c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5zm-3.5-2.5c-.83 0-1.5-.67-1.5-1.5S11.17 6 12 6s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="flex items-center gap-1">
                            <span class="font-black text-slate-900 text-sm">5.0</span>
                            <div class="flex text-[#00AF87] gap-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#00AF87]"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-[#00AF87]"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-[#00AF87]"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-[#00AF87]"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-[#00AF87]"></span>
                            </div>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none group-hover:text-[#00AF87] transition-colors">{{ __('Ulasan TripAdvisor') }}</span>
                    </div>
                </a>
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

    <!-- FAQ Section (Temuan #12) -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 md:px-8">
            <div class="text-center mb-16">
                <div class="flex items-center justify-center space-x-3 mb-4">
                    <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                    <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">{{ __('FAQ Internasional') }}</span>
                    <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                </div>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">
                    {{ __('Ada Pertanyaan?') }} <span class="text-toba-green">{{ __('Kami Punya Jawabannya') }}</span>
                </h2>
                <p class="mt-4 text-slate-500 font-medium max-w-2xl mx-auto text-sm md:text-base">
                    {{ __('Informasi penting dan praktikal bagi pelancong dari Malaysia, Singapura, dan mancanegara sebelum berkunjung ke Danau Toba.') }}
                </p>
            </div>

            <div x-data="{ activeFaq: null }" class="space-y-4">
                @php
                    $faqs = [
                        [
                            'q' => 'Bagaimana cara terbaik menuju Danau Toba dari Bandara Kualanamu (KNO)?',
                            'a' => 'Cara terbaik dan paling nyaman adalah menggunakan layanan transfer private (armada premium dengan supir pribadi) yang disediakan oleh Wonderful Toba. Perjalanan darat memakan waktu sekitar 3.5 hingga 4 jam melalui jalan tol Medan-Tebing Tinggi, lalu dilanjutkan ke Parapat, pintu gerbang utama menuju Pulau Samosir.'
                        ],
                        [
                            'q' => 'Apakah makanan halal mudah ditemukan di sekitar Danau Toba?',
                            'a' => 'Ya, sangat mudah. Di Parapat dan Pulau Samosir (terutama daerah wisata Tuk-tuk dan Tomok), terdapat banyak restoran Muslim lokal yang bersertifikat halal atau menyajikan menu ramah Muslim seperti ikan mas bakar, ayam penyet, dan masakan khas Minang/Padang. Supir dan pemandu Wonderful Toba akan selalu mengarahkan Anda ke tempat makan halal pilihan.'
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

                @foreach($faqs as $index => $faq)
                <div class="border border-slate-100 rounded-3xl bg-white overflow-hidden transition-all duration-300 shadow-sm hover:shadow-md hover:border-slate-200">
                    <button @click="activeFaq = activeFaq === {{ $index }} ? null : {{ $index }}" class="w-full flex items-center justify-between p-6 md:p-8 text-left font-black text-slate-900 text-base md:text-lg hover:text-toba-green transition-colors focus:outline-none">
                        <span>{{ __($faq['q']) }}</span>
                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-300 shrink-0 ml-4" :class="activeFaq === {{ $index }} ? 'rotate-180 text-toba-green' : ''" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === {{ $index }}" x-transition.opacity.duration.300ms x-cloak>
                        <div class="p-6 md:p-8 pt-0 border-t border-slate-50 text-slate-600 font-medium leading-relaxed text-sm md:text-base">
                            {{ __($faq['a']) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

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
                    @php
                        $touristsCount = $settings['stat_customers'] ?? '5000+';
                        if (strpos($touristsCount, '5000') !== false || strpos($touristsCount, '5K') !== false) {
                            $touristsCount = __('5.000+');
                        }
                    @endphp
                    {{ __('Bergabunglah dengan') }} <span class="text-white font-black">{{ $touristsCount }}</span> {{ __('wisatawan lainnya yang telah menemukan keindahan Sumatera Utara bersama kami.') }}
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
