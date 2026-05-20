@extends('layouts.app')

@section('title', 'Tentang Kami – Sujai Laketoba')
@section('description', 'Sujai Laketoba adalah provider perjalanan wisata premium di Sumatera Utara yang berdedikasi memberikan pengalaman terbaik.')
@section('keywords', 'tentang sujai laketoba, travel terpercaya danau toba, biro perjalanan medan, profil travel toba')

@section('content')

<div class="bg-white min-h-screen pb-32">
    <!-- Cinematic Hero Section -->
    <div class="relative h-[55dvh] flex items-center overflow-hidden bg-slate-900">
        <img src="{{ imageUrl($content['hero_image'] ?? '2026/04/lake-toba-premium.webp') }}" alt="About Hero" class="absolute inset-0 w-full h-full object-cover opacity-50 animate-subtle-zoom">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-8 pt-20">
            <div class="max-w-4xl animate-in fade-in slide-in-from-left-12 duration-1000">
                <div class="flex items-center space-x-2 mb-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-toba-green/20 backdrop-blur-md border border-white/10 text-white text-[10px] font-semibold uppercase tracking-[0.2em] rounded-full">Our Legacy</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-light text-white tracking-tight leading-tight mb-6">
                    Dedikasi Untuk <br />
                    <span class="text-toba-green">Pariwisata Sumut</span>
                </h1>
                <p class="text-slate-200 text-sm md:text-base font-normal max-w-xl leading-relaxed">
                    Lebih dari sekadar agen perjalanan, kami adalah kurator pengalaman yang menghidupkan setiap sudut keindahan Sumatera Utara.
                </p>
            </div>
        </div>
    </div>

    <!-- Story Section -->
    <section class="py-20 md:py-32 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-center">
                <div class="lg:col-span-6 relative">
                    <div class="relative z-10 aspect-[4/5] rounded-3xl overflow-hidden shadow-sm">
                        <img src="{{ imageUrl($content['image_url'] ?? '2026/04/sumatra-panorama.webp') }}" alt="Sujai Laketoba Story" class="w-full h-full object-cover">
                    </div>
                    <!-- Decorative Element -->
                    <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-toba-green/5 rounded-full blur-3xl -z-10"></div>
                    <div class="absolute -top-12 -left-12 w-48 h-48 bg-toba-accent/5 rounded-full blur-3xl -z-10"></div>
                    
                    <div class="absolute -bottom-8 -left-8 bg-white p-6 rounded-2xl shadow-lg border border-slate-100 hidden md:block max-w-sm">
                        <div class="flex gap-1 mb-3">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                        </div>
                        <p class="text-slate-900 font-bold italic text-base mb-4 leading-tight">"{{ $content['testimonial_quote'] ?? 'Layanan terbaik, armada baru, dan guide yang sangat informatif.' }}"</p>
                        <p class="text-toba-green font-bold text-[10px] uppercase tracking-widest">— {{ $content['testimonial_name'] ?? 'Pelanggan Setia' }}</p>
                    </div>
                </div>

                <div class="lg:col-span-6 space-y-8">
                    <div class="space-y-4">
                        <span class="text-toba-green font-bold text-xs uppercase tracking-wider block">Mengenal Kami</span>
                        <h2 class="text-3xl md:text-5xl font-light text-slate-900 leading-tight tracking-tight">
                            {{ $content['title'] ?? 'Melayani Dengan Sepenuh Hati Sejak 2012' }}
                        </h2>
                        <div class="text-base text-slate-600 font-normal leading-relaxed space-y-6">
                            {!! nl2br(e($content['description'] ?? 'Berawal dari kecintaan terhadap keindahan alam Sumatera Utara, Sujai Laketoba hadir untuk memberikan pengalaman perjalanan yang tak terlupakan bagi setiap wisatawan. Kami percaya bahwa setiap perjalanan memiliki cerita unik yang layak untuk dikenang selamanya.')) !!}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-10 pt-8 border-t border-slate-100">
                        <div>
                            <p class="text-4xl font-light text-toba-green mb-1 tracking-tight">{{ $content['stat_years'] ?? '12+' }}</p>
                            <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Tahun Pengalaman') }}</p>
                        </div>
                        <div>
                            @php
                                $touristsCount = $siteSettings['cms_tour']['stat_customers'] ?? $content['stat_tourists'] ?? '1.500+';
                            @endphp
                            <p class="text-4xl font-light text-toba-green mb-1 tracking-tight">{{ $touristsCount }}</p>
                            <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider">{{ __('Wisatawan Puas') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="py-20 md:py-32 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-slate-100/50 skew-x-12 translate-x-20"></div>
        
        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 md:mb-24">
                <span class="text-toba-green font-bold text-xs uppercase tracking-wider mb-4 block">Our Purpose</span>
                <h2 class="text-3xl md:text-5xl font-light text-slate-900 tracking-tight leading-tight">
                    Visi & Misi <span class="text-toba-green">Masa Depan</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12">
                <!-- Vision Card -->
                <div class="group bg-white p-10 md:p-14 rounded-3xl shadow-sm border border-slate-100 hover:border-toba-green/20 transition-all duration-500">
                    <div class="w-16 h-16 bg-toba-green rounded-xl flex items-center justify-center text-white mb-8 shadow-sm group-hover:scale-105 transition-transform duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4 tracking-tight">Visi Kami</h3>
                    <p class="text-base text-slate-500 font-normal leading-relaxed">
                        {{ $content['vision'] ?? 'Menjadi lokomotif pariwisata Sumatera Utara yang mengedepankan kualitas layanan premium, keberlanjutan alam, serta kebahagiaan dan kepuasan maksimal setiap tamu yang kami layani.' }}
                    </p>
                </div>

                <!-- Mission Card -->
                <div class="group bg-white p-10 md:p-14 rounded-3xl shadow-sm border border-slate-100 hover:border-slate-200 transition-all duration-500">
                    <div class="w-16 h-16 bg-slate-950 rounded-xl flex items-center justify-center text-white mb-8 shadow-sm group-hover:scale-105 transition-transform duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 tracking-tight">Misi Kami</h3>
                    <ul class="space-y-4">
                        @php
                            $missions = explode("\n", $content['mission'] ?? "Menyediakan paket wisata yang edukatif, inspiratif, dan autentik.\nMenjamin standar kenyamanan dan keamanan transportasi serta akomodasi.\nMempromosikan kearifan lokal melalui interaksi budaya yang positif dan berkelanjutan.");
                        @endphp
                        @foreach($missions as $mission)
                            @if(trim($mission))
                            <li class="flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full bg-toba-green/10 text-toba-green flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <span class="text-sm text-slate-600 font-normal leading-relaxed">{{ trim($mission) }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <div class="lg:col-span-5">
                    <span class="text-toba-green font-bold text-xs uppercase tracking-wider mb-4 block">Why Us</span>
                    <h2 class="text-3xl md:text-5xl font-light text-slate-900 tracking-tight leading-tight mb-8">
                        Keunggulan <br /> <span class="text-toba-green">Sujai Laketoba</span>
                    </h2>
                    <p class="text-sm text-slate-500 font-normal leading-relaxed mb-10">
                        Kami tidak hanya menjual tiket, kami merancang pengalaman. Setiap detail kecil dalam perjalanan Anda adalah prioritas utama bagi tim kami.
                    </p>
                    <a href="/tour/packages" class="inline-flex items-center gap-3 py-3.5 px-8 bg-slate-950 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-toba-green transition-colors shadow-sm group">
                        Lihat Layanan Kami
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
                
                <div class="lg:col-span-7">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:border-slate-200 transition-all duration-300 group">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-toba-green mb-6 group-hover:bg-toba-green group-hover:text-white transition-all">
                                <i class="fas fa-gem text-lg"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Layanan Premium</h4>
                            <p class="text-xs text-slate-500 font-normal leading-relaxed">Armada terbaru dan hotel pilihan berkualitas terbaik.</p>
                        </div>
                        <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:border-slate-200 transition-all duration-300 group">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-toba-green mb-6 group-hover:bg-toba-green group-hover:text-white transition-all">
                                <i class="fas fa-user-tie text-lg"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Guide Berlisensi</h4>
                            <p class="text-xs text-slate-500 font-normal leading-relaxed">Didampingi oleh guide lokal profesional yang informatif.</p>
                        </div>
                        <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:border-slate-200 transition-all duration-300 group">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-toba-green mb-6 group-hover:bg-toba-green group-hover:text-white transition-all">
                                <i class="fas fa-shield-alt text-lg"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Aman & Terpercaya</h4>
                            <p class="text-xs text-slate-500 font-normal leading-relaxed">Berizin resmi dan memiliki reputasi terpercaya sejak 2012.</p>
                        </div>
                        <div class="p-8 bg-white rounded-3xl border border-slate-100 shadow-sm hover:border-slate-200 transition-all duration-300 group">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-toba-green mb-6 group-hover:bg-toba-green group-hover:text-white transition-all">
                                <i class="fas fa-headset text-lg"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Support 24/7</h4>
                            <p class="text-xs text-slate-500 font-normal leading-relaxed">Tim support kami siap membantu kapan pun Anda butuhkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Partners/Trusted Clients Section --}}
    <section class="py-20 bg-slate-950 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="text-center mb-12">
                <span class="text-toba-accent font-bold text-[9px] uppercase tracking-wider mb-2 block">Official Partners</span>
                <h3 class="text-2xl md:text-3xl font-light text-white tracking-tight leading-tight">Dipercaya Oleh Institusi Terkemuka</h3>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
                @php
                $svgPartners = [
                    ['name' => 'Bank Mandiri Taspen', 'logo' => '/images/partners/mandiri.svg'],
                    ['name' => 'Universitas Sumatera Utara', 'logo' => '/images/partners/usu.svg'],
                    ['name' => 'Pelindo 1', 'logo' => '/images/partners/pelindo.svg'],
                    ['name' => 'Hyundai', 'logo' => '/images/partners/hyundai.svg'],
                ];
                @endphp
                @foreach($svgPartners as $partner)
                <div class="flex flex-col items-center gap-3 group">
                    <div class="w-full h-12 flex items-center justify-center opacity-40 group-hover:opacity-100 transition-all duration-500">
                        <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['name'] }}" class="max-h-10 w-auto object-contain filter brightness-0 invert group-hover:brightness-100 group-hover:invert-0 transition-all duration-500">
                    </div>
                    <p class="text-slate-500 text-[8px] font-semibold uppercase tracking-wider text-center group-hover:text-slate-300 transition-colors">{{ $partner['name'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Wonderful Indonesia Credential --}}
            <div class="mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-center gap-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Wonderful_Indonesia_logo.svg" alt="Wonderful Indonesia" class="h-8 opacity-60">
                <div class="text-center md:text-left">
                    <p class="text-white font-bold text-xs">Agen Wisata Resmi Program Wonderful Indonesia</p>
                    <p class="text-slate-500 text-[10px] font-normal">Kementerian Pariwisata dan Ekonomi Kreatif Republik Indonesia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 md:py-32 px-6 md:px-8">
        <div class="max-w-7xl mx-auto bg-slate-50 rounded-3xl p-12 md:p-20 relative overflow-hidden text-center border border-slate-100 shadow-sm">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-toba-green/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-toba-accent/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-light text-slate-900 mb-6 tracking-tight leading-tight">
                    Mulai Cerita <br /> <span class="text-toba-green">Anda Bersama Kami</span>
                </h2>
                <p class="text-slate-500 text-sm font-normal leading-relaxed mb-10">
                    Siap untuk menjelajahi Sumatera Utara? Hubungi tim kami sekarang untuk merancang paket perjalanan impian Anda sendiri.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/tour/packages" class="px-8 py-3.5 bg-slate-950 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-toba-green transition-colors shadow-sm">
                        Pilih Paket Wisata
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['whatsapp'] ?? '6281323888207') }}" target="_blank" class="px-8 py-3.5 bg-white text-slate-900 border border-slate-200 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm">
                        Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    @keyframes subtle-zoom {
        from { transform: scale(1); }
        to { transform: scale(1.05); }
    }
    .animate-subtle-zoom {
        animation: subtle-zoom 20s infinite alternate ease-in-out;
    }
</style>
@endsection
