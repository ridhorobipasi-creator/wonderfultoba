@extends('layouts.app')

@section('title', 'Tentang Kami – Wonderful Toba')
@section('description', 'Wonderful Toba adalah provider perjalanan wisata dan outbound premium di Sumatera Utara yang berdedikasi memberikan pengalaman terbaik.')

@section('content')

<div class="bg-white min-h-screen pb-32">
    <!-- Cinematic Hero Section -->
    <div class="relative h-[70dvh] flex items-center overflow-hidden bg-slate-900">
        <img src="{{ imageUrl($content['hero_image'] ?? '2026/04/lake-toba-premium.webp') }}" alt="About Hero" class="absolute inset-0 w-full h-full object-cover opacity-50 animate-subtle-zoom">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-8 pt-20">
            <div class="max-w-4xl animate-in fade-in slide-in-from-left-12 duration-1000">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                    <span class="text-toba-accent font-black text-xs uppercase tracking-[0.4em]">Our Legacy</span>
                </div>
                <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter leading-[0.9] mb-10">
                    Dedikasi Untuk <br />
                    <span class="text-toba-green">Pariwisata Sumut</span>
                </h1>
                <p class="text-slate-300 text-lg md:text-2xl font-medium max-w-2xl leading-relaxed">
                    Lebih dari sekadar agen perjalanan, kami adalah kurator pengalaman yang menghidupkan setiap sudut keindahan Sumatera Utara.
                </p>
            </div>
        </div>
    </div>

    <!-- Story Section -->
    <section class="py-24 md:py-40 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-center">
                <div class="lg:col-span-6 relative">
                    <div class="relative z-10 aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl">
                        <img src="{{ imageUrl($content['image_url'] ?? '2026/04/sumatra-panorama.webp') }}" alt="Wonderful Toba Story" class="w-full h-full object-cover">
                    </div>
                    <!-- Decorative Element -->
                    <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-toba-green/10 rounded-full blur-3xl -z-10"></div>
                    <div class="absolute -top-12 -left-12 w-48 h-48 bg-toba-accent/10 rounded-full blur-3xl -z-10"></div>
                    
                    <div class="absolute -bottom-8 -left-8 bg-white p-8 rounded-3xl shadow-2xl border border-slate-50 hidden md:block max-w-sm">
                        <div class="flex gap-1 mb-4">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-4 h-4 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                        </div>
                        <p class="text-slate-900 font-black italic text-lg mb-4 leading-tight">"{{ $content['testimonial_quote'] ?? 'Layanan terbaik, armada baru, dan guide yang sangat informatif.' }}"</p>
                        <p class="text-toba-green font-bold text-xs uppercase tracking-widest">— {{ $content['testimonial_name'] ?? 'Pelanggan Setia' }}</p>
                    </div>
                </div>

                <div class="lg:col-span-6 space-y-10">
                    <div class="space-y-6">
                        <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Mengenal Kami</span>
                        <h2 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                            {{ $content['title'] ?? 'Melayani Dengan Sepenuh Hati Sejak 2012' }}
                        </h2>
                        <div class="text-lg text-slate-600 font-medium leading-relaxed space-y-6">
                            {!! nl2br(e($content['description'] ?? 'Berawal dari kecintaan terhadap keindahan alam Sumatera Utara, Wonderful Toba hadir untuk memberikan pengalaman perjalanan yang tak terlupakan bagi setiap wisatawan. Kami percaya bahwa setiap perjalanan memiliki cerita unik yang layak untuk dikenang selamanya.')) !!}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-10 pt-10 border-t border-slate-100">
                        <div>
                            <p class="text-5xl font-black text-toba-green mb-2 tracking-tighter">{{ $content['stat_years'] ?? '12+' }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $content['stat_years_label'] ?? 'Tahun Pengalaman' }}</p>
                        </div>
                        <div>
                            <p class="text-5xl font-black text-toba-green mb-2 tracking-tighter">{{ $content['stat_tourists'] ?? '5K+' }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $content['stat_tourists_label'] ?? 'Wisatawan Puas' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission (Premium Grid) -->
    <section class="py-24 md:py-40 bg-slate-50 relative overflow-hidden">
        <!-- Decor -->
        <div class="absolute top-0 right-0 w-1/3 h-full bg-slate-100/50 skew-x-12 translate-x-20"></div>
        
        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20 md:mb-32">
                <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em] mb-6 block">Our Purpose</span>
                <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                    Visi & Misi <span class="text-toba-green">Masa Depan</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16">
                <!-- Vision Card -->
                <div class="group bg-white p-12 md:p-16 rounded-3xl shadow-2xl shadow-slate-200/50 border border-transparent hover:border-toba-green/20 transition-all duration-700">
                    <div class="w-20 h-20 bg-toba-green rounded-[1.5rem] flex items-center justify-center text-white mb-10 shadow-xl shadow-toba-green/30 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">Visi Kami</h3>
                    <p class="text-lg text-slate-600 font-medium leading-relaxed">
                        {{ $content['vision'] ?? 'Menjadi lokomotif pariwisata Sumatera Utara yang mengedepankan kualitas layanan premium, keberlanjutan alam, serta kebahagiaan dan kepuasan maksimal setiap tamu yang kami layani.' }}
                    </p>
                </div>

                <!-- Mission Card -->
                <div class="group bg-white p-12 md:p-16 rounded-3xl shadow-2xl shadow-slate-200/50 border border-transparent hover:border-slate-900/10 transition-all duration-700">
                    <div class="w-20 h-20 bg-slate-900 rounded-[1.5rem] flex items-center justify-center text-white mb-10 shadow-xl group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 mb-6 tracking-tight">Misi Kami</h3>
                    <ul class="space-y-6">
                        @php
                            $missions = explode("\n", $content['mission'] ?? "Menyediakan paket wisata yang edukatif, inspiratif, dan autentik.\nMenjamin standar kenyamanan dan keamanan transportasi serta akomodasi.\nMempromosikan kearifan lokal melalui interaksi budaya yang positif dan berkelanjutan.");
                        @endphp
                        @foreach($missions as $mission)
                            @if(trim($mission))
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 rounded-full bg-toba-green/10 text-toba-green flex items-center justify-center shrink-0 mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <span class="text-lg text-slate-600 font-medium leading-tight">{{ trim($mission) }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-24 md:py-40">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <div class="lg:col-span-5">
                    <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em] mb-6 block">Why Us</span>
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight mb-10">
                        Keunggulan <br /> <span class="text-toba-green">Wonderful Toba</span>
                    </h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed mb-12">
                        Kami tidak hanya menjual tiket, kami merancang pengalaman. Setiap detail kecil dalam perjalanan Anda adalah prioritas utama bagi tim kami.
                    </p>
                    <a href="/tour/packages" class="inline-flex items-center gap-4 py-5 px-10 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-toba-green transition-all shadow-xl group">
                        Lihat Layanan Kami
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
                
                <div class="lg:col-span-7">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="p-10 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/30 hover:shadow-toba-green/10 transition-all group">
                            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-toba-green mb-8 group-hover:bg-toba-green group-hover:text-white transition-all">
                                <i class="fas fa-gem text-xl"></i>
                            </div>
                            <h4 class="text-xl font-black text-slate-900 mb-3">Layanan Premium</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Armada terbaru dan hotel pilihan berkualitas terbaik.</p>
                        </div>
                        <div class="p-10 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/30 hover:shadow-toba-green/10 transition-all group">
                            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-toba-green mb-8 group-hover:bg-toba-green group-hover:text-white transition-all">
                                <i class="fas fa-user-tie text-xl"></i>
                            </div>
                            <h4 class="text-xl font-black text-slate-900 mb-3">Guide Berlisensi</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Didampingi oleh guide lokal profesional yang informatif.</p>
                        </div>
                        <div class="p-10 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/30 hover:shadow-toba-green/10 transition-all group">
                            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-toba-green mb-8 group-hover:bg-toba-green group-hover:text-white transition-all">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <h4 class="text-xl font-black text-slate-900 mb-3">Aman & Terpercaya</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Berizin resmi dan memiliki reputasi terpercaya sejak 2012.</p>
                        </div>
                        <div class="p-10 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/30 hover:shadow-toba-green/10 transition-all group">
                            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-toba-green mb-8 group-hover:bg-toba-green group-hover:text-white transition-all">
                                <i class="fas fa-headset text-xl"></i>
                            </div>
                            <h4 class="text-xl font-black text-slate-900 mb-3">Support 24/7</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Tim support kami siap membantu kapan pun Anda butuhkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted Clients (Slider Style) -->
    @if(count($clients) > 0)
    <section class="py-24 bg-slate-900 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="text-center mb-16">
                <span class="text-toba-accent font-black text-[10px] uppercase tracking-[0.4em] mb-4 block">Official Partners</span>
                <h3 class="text-3xl font-black text-white tracking-tight leading-tight">Dipercaya Oleh Perusahaan Besar</h3>
            </div>
            
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-40 grayscale hover:grayscale-0 transition-all duration-1000">
                @foreach($clients as $client)
                    <div class="w-32 md:w-40 h-16 relative group">
                        <img src="{{ imageUrl($client->logo) }}" alt="{{ $client->name }}" class="w-full h-full object-contain filter brightness-0 invert group-hover:invert-0 group-hover:brightness-100 transition-all">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Call to Action -->
    <section class="py-24 md:py-32 px-6 md:px-8">
        <div class="max-w-7xl mx-auto bg-slate-100 rounded-3xl p-12 md:p-24 relative overflow-hidden text-center">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-toba-green/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-toba-accent/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-4xl md:text-7xl font-black text-slate-900 mb-8 tracking-tighter leading-tight">
                    Mulai Cerita <br /> <span class="text-toba-green">Anda Bersama Kami</span>
                </h2>
                <p class="text-lg text-slate-500 font-medium leading-relaxed mb-12">
                    Siap untuk menjelajahi Sumatera Utara? Hubungi tim kami sekarang untuk merancang paket perjalanan impian Anda sendiri.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="/tour/packages" class="px-12 py-6 bg-toba-green text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-slate-900 transition-all shadow-2xl shadow-toba-green/30">
                        Pilih Paket Wisata
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['whatsapp'] ?? '6281323888207') }}" target="_blank" class="px-12 py-6 bg-white text-slate-900 border border-slate-200 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-slate-50 transition-all">
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
        to { transform: scale(1.1); }
    }
    .animate-subtle-zoom {
        animation: subtle-zoom 20s infinite alternate ease-in-out;
    }
</style>
@endsection
