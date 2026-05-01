@extends('layouts.app')

@section('title', $settings['hero_title'] ?? 'Wonderful Toba – Wisata Sumatera Utara')
@section('description', $settings['hero_subtitle'] ?? 'Temukan keindahan Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang bersama Wonderful Toba.')

@section('content')
<div x-data="{ waNumber: '{{ $settings['contact_wa'] ?? '6281323888207' }}' }">
    
    <!-- Premium Hero Slider -->
    <x-home-slider />

    <!-- Featured Packages -->
    <section class="py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div class="max-w-2xl">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="h-px w-8 bg-toba-green"></div>
                        <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Destinasi Unggulan</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">
                        Paket Wisata <span class="text-toba-green">Terpopuler</span>
                    </h2>
                </div>
                <a href="/tour/packages" class="flex items-center space-x-3 text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0">
                    <span>Lihat Semua</span>
                    <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($packages as $pkg)
                <div class="group cursor-pointer">
                    <div class="relative h-[420px] rounded-[2rem] overflow-hidden mb-6 shadow-xl shadow-slate-200/50">
                        <img src="{{ $pkg->image }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                        <div class="absolute top-6 left-6">
                            <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
                                <svg class="w-3 h-3 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <span class="font-black text-slate-900 text-xs">{{ $pkg->rating ?? '4.9' }}</span>
                            </div>
                        </div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="flex items-center text-toba-accent text-[10px] font-black uppercase tracking-[0.2em] mb-2">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $pkg->locationTag ?? 'Sumatera Utara' }}
                            </div>
                            <h3 class="text-2xl font-black text-white mb-2 tracking-tight leading-tight">{{ $pkg->name }}</h3>
                            <p class="text-slate-300 text-xs font-medium mb-4 line-clamp-2">{{ $pkg->shortDescription }}</p>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">Durasi</p>
                                    <p class="text-white font-bold text-sm">{{ $pkg->duration }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">Mulai Dari</p>
                                    <p class="text-xl font-black text-white">
                                        <span class="text-toba-accent text-xs font-bold mr-1">Rp</span>
                                        {{ number_format($pkg->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="/tour/package/{{ $pkg->slug }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-slate-50 hover:bg-toba-green hover:text-white text-slate-700 rounded-2xl font-bold text-sm transition-all duration-300 group-hover:bg-toba-green group-hover:text-white">
                        Lihat Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="py-20 md:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="relative">
                    <div class="grid grid-cols-2 gap-4">
                        <img src="https://images.unsplash.com/photo-1544735049-717bc392183e?w=800" alt="Lake Toba View" class="rounded-[2rem] md:rounded-[2.5rem] shadow-2xl w-full h-[300px] md:h-[450px] object-cover">
                        <div class="space-y-4 pt-6 md:pt-12">
                            <img src="https://images.unsplash.com/photo-1518002054494-3a6f94352e9d?w=800" alt="Bukit Lawang" class="rounded-[1.5rem] shadow-xl w-full h-32 md:h-48 object-cover">
                            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800" alt="Tangkahan" class="rounded-[1.5rem] shadow-xl w-full h-32 md:h-48 object-cover">
                        </div>
                    </div>
                </div>

                <div class="pt-10 lg:pt-0">
                    <div class="flex items-center space-x-2 mb-5">
                        <div class="h-px w-8 bg-toba-green"></div>
                        <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Mengapa Kami</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-8 tracking-tight leading-tight">
                        Pengalaman Wisata <br /><span class="text-toba-green">Terbaik di Sumut</span>
                    </h2>
                    <div class="space-y-6 mb-10">
                        <div class="flex items-start space-x-5">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 bg-toba-green/8 text-toba-green">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 mb-1 tracking-tight">Harga Transparan</h4>
                                <p class="text-slate-500 font-medium leading-relaxed text-sm">Tidak ada biaya tersembunyi. Semua fasilitas sudah termasuk sesuai yang tertera di paket penawaran kami.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-5">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 bg-toba-green/8 text-toba-green">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 mb-1 tracking-tight">Guide Berpengalaman</h4>
                                <p class="text-slate-500 font-medium leading-relaxed text-sm">Dipandu oleh local expert yang memahami betul budaya, rute, dan cerita tersembunyi di balik keindahan Danau Toba.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-5">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 bg-toba-green/8 text-toba-green">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 mb-1 tracking-tight">Layanan Premium</h4>
                                <p class="text-slate-500 font-medium leading-relaxed text-sm">Armada transportasi bersih dan penginapan terbaik yang sudah kami kurasi khusus untuk kenyamanan liburan Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 md:py-24">
        <div class="max-w-7xl mx-auto px-6 md:px-8 text-center">
            <div class="flex items-center justify-center space-x-2 mb-4">
                <div class="h-px w-8 bg-toba-green"></div>
                <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Testimoni</span>
                <div class="h-px w-8 bg-toba-green"></div>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-12 md:mb-16">
                Kata Mereka <span class="text-toba-green">Tentang Kami</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl transition-all duration-300 text-left">
                    <div class="flex gap-1 mb-5">
                        @for($i=0; $i<5; $i++)
                            <svg class="w-4 h-4 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        @endfor
                    </div>
                    <p class="text-slate-600 leading-relaxed mb-6 font-medium italic">&ldquo;Pelayanan sangat memuaskan, guide ramah dan sangat informatif. Danau Toba memang luar biasa indahnya.&rdquo;</p>
                    <div class="flex items-center gap-3 pt-5 border-t border-slate-50">
                        <img src="https://i.pravatar.cc/150?u=1" alt="Budi" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <p class="font-black text-slate-900 text-sm">Budi Santoso</p>
                            <p class="text-xs text-slate-400 font-medium">Keluarga Wisatawan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl transition-all duration-300 text-left">
                    <div class="flex gap-1 mb-5">
                        @for($i=0; $i<5; $i++)
                            <svg class="w-4 h-4 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        @endfor
                    </div>
                    <p class="text-slate-600 leading-relaxed mb-6 font-medium italic">&ldquo;Pengalaman trekking di Bukit Lawang sangat asyik! Ranger berpengalaman dan penginapannya nyaman banget.&rdquo;</p>
                    <div class="flex items-center gap-3 pt-5 border-t border-slate-50">
                        <img src="https://i.pravatar.cc/150?u=2" alt="Siska" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <p class="font-black text-slate-900 text-sm">Siska Amelia</p>
                            <p class="text-xs text-slate-400 font-medium">Solo Traveler</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-20 md:py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div class="max-w-2xl">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="h-px w-8 bg-toba-green"></div>
                        <span class="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Wawasan & Artikel</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">
                        Jurnal <span class="text-toba-green">Perjalanan Kami</span>
                    </h2>
                </div>
                <a href="/tour/blog" class="flex items-center space-x-3 text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0">
                    <span>Semua Artikel</span>
                    <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </div>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                <div class="group">
                    <div class="h-56 rounded-[2rem] overflow-hidden mb-6 shadow-md">
                        <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <p class="text-xs font-bold text-slate-400 mb-3 uppercase tracking-widest">
                        {{ date('d F Y', strtotime($blog->createdAt)) }}
                    </p>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 leading-snug group-hover:text-toba-green transition-colors">{{ $blog->title }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2">{{ $blog->excerpt }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 md:py-24 px-6 md:px-8">
        <div class="max-w-7xl mx-auto bg-slate-900 rounded-[2.5rem] md:rounded-[3rem] p-10 md:p-20 relative overflow-hidden shadow-2xl">
            <div class="absolute inset-0 opacity-20">
                <img src="{{ $settings['cta']['backgroundImage'] ?? 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000' }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 to-slate-900/40"></div>
            <div class="relative z-10 text-center max-w-3xl mx-auto text-white">
                <h2 class="text-3xl md:text-6xl font-black mb-6 tracking-tight leading-tight">
                    {!! nl2br(e($settings['cta_text'] ?? "Mulai Petualangan Anda\nBersama Kami")) !!}
                </h2>
                <p class="text-lg text-slate-300 mb-10 font-medium leading-relaxed">
                    Lebih dari {{ $settings['stat_customers'] ?? '3000+' }} pelanggan telah mempercayakan perjalanan mereka kepada kami untuk mengeksplorasi {{ $settings['stat_trips'] ?? '500+' }} destinasi.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ $settings['cta_link'] ?? '/tour/packages' }}" class="bg-toba-green text-white px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-toba-accent transition-all shadow-2xl shadow-toba-green/20">
                        Lihat Paket Wisata
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating WA -->
    <a :href="'https://wa.me/' + waNumber + '?text=Halo Admin Wonderful Toba, saya tertarik dengan layanan Anda.'" target="_blank" class="fixed bottom-8 right-8 z-[100] bg-emerald-500 text-white p-5 rounded-full shadow-2xl hover:bg-emerald-600 transition-all hover:scale-110 group animate-bounce">
        <div class="absolute -top-12 right-0 bg-white text-slate-800 px-4 py-2 rounded-xl shadow-lg text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap border border-slate-100 pointer-events-none">
            Butuh Bantuan? Chat Kami 👋
        </div>
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    </a>
</div>
@endsection


