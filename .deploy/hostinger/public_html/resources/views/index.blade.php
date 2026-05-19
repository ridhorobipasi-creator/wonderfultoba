<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteSettings['cms_landing']['meta_title'] ?? ($siteSettings['general']['seo_meta_title'] ?? 'Sujailake Toba | Paket Wisata Danau Toba Premium') }}</title>
    <meta name="description" content="{{ $siteSettings['cms_landing']['meta_description'] ?? ($siteSettings['general']['seo_meta_desc'] ?? 'Agen perjalanan wisata terpercaya untuk eksplorasi Danau Toba, Samosir, dan Sumatera Utara.') }}">
    <meta name="keywords" content="{{ $siteSettings['general']['seo_meta_keywords'] ?? 'danau toba, tour samosir, paket wisata toba, travel sumatera utara, sujailake toba' }}">
    <meta property="og:title" content="{{ $siteSettings['cms_landing']['meta_title'] ?? ($siteSettings['general']['seo_meta_title'] ?? 'Sujailake Toba') }}">
    <meta property="og:description" content="{{ $siteSettings['cms_landing']['meta_description'] ?? ($siteSettings['general']['seo_meta_desc'] ?? 'Wisata Danau Toba Premium') }}">
    <meta property="og:image" content="{{ imageUrl($siteSettings['cms_landing']['homepage_slides'][0]['image_url'] ?? null) ?: 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=1200' }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $siteSettings['cms_landing']['meta_title'] ?? ($siteSettings['general']['seo_meta_title'] ?? 'Sujailake Toba') }}">
    <meta property="twitter:description" content="{{ $siteSettings['cms_landing']['meta_description'] ?? '' }}">
    <meta property="twitter:image" content="{{ imageUrl($siteSettings['general']['logo_light_url'] ?? null) }}">

    <!-- Schema.org Organization -->
    <script type="application/ld+json">
    {
      "{{ '@' }}context": "https://schema.org",
      "{{ '@' }}type": "Organization",
      "name": "{{ $siteSettings['general']['site_name'] ?? 'Sujailake Toba' }}",
      "url": "{{ url('/') }}",
      "logo": "{{ imageUrl($siteSettings['general']['logo_light_url'] ?? null) }}",
      "contactPoint": {
        "{{ '@' }}type": "ContactPoint",
        "telephone": "{{ $siteSettings['general']['wa_number'] ?? '' }}",
        "contactType": "customer service",
        "areaServed": "ID",
        "availableLanguage": ["Indonesian", "English"]
      },
      "sameAs": [
        "{{ $siteSettings['general']['social_facebook'] ?? '' }}",
        "https://instagram.com/{{ str_replace('@', '', $siteSettings['general']['social_instagram'] ?? '') }}"
      ]
    }
    </script>

    <link rel="icon" type="image/x-icon" href="{{ $siteSettings['general']['icon_url'] ?? asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        @keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-8px); } }
        .stat-float { animation: float 5s ease-in-out infinite; }
    </style>
</head>
<body class="overflow-x-hidden bg-white font-sans" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    {{-- ══════════════════════════════════════════════════════════════════════
         HERO — Full Viewport Slider
    ══════════════════════════════════════════════════════════════════════ --}}
    <x-home-slider :settings="$settings" :packages="$packages" />

    {{-- ══════════════════════════════════════════════════════════════════════
         STATS COUNTER
    ══════════════════════════════════════════════════════════════════════ --}}
    <section class="bg-white py-12 md:py-20 px-6 md:px-16 border-b border-slate-50">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
            @foreach(range(0, 3) as $i)
                @if(!empty($siteSettings['cms_landing']['stat_value_'.$i]))
                <div class="text-center group">
                    <p class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2 group-hover:text-lake-blue transition-colors">
                        {{ $siteSettings['cms_landing']['stat_value_'.$i] }}
                    </p>
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
                        {{ $siteSettings['cms_landing']['stat_label_'.$i] }}
                    </p>
                </div>
                @endif
            @endforeach
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════════
         FEATURED PACKAGES
    ══════════════════════════════════════════════════════════════════════ --}}
    @if(isset($featuredPackages) && $featuredPackages->count() > 0)
    <section class="bg-slate-50/50 py-24 md:py-32 px-6 md:px-16 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-6 mb-16">
                <div class="max-w-2xl">
                    <span class="text-lake-blue text-[11px] font-black uppercase tracking-[0.4em] mb-4 block">Eksplorasi Danau Toba</span>
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 leading-[0.9] tracking-tighter">
                        Paket Wisata <span class="text-lake-blue">Terpopuler</span>
                    </h2>
                    <p class="text-slate-500 font-medium mt-6 text-lg">Pilih dari berbagai paket wisata yang telah kami kurasi khusus untuk memberikan pengalaman terbaik di Sumatera Utara.</p>
                </div>
                <a href="/packages" class="shrink-0 px-10 py-5 bg-white text-slate-900 border-2 border-slate-900/5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-xl shadow-slate-900/5">
                    Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($featuredPackages->take(6) as $pkg)
                @php
                    $imgs = is_array($pkg->images) ? $pkg->images : json_decode($pkg->images ?? '[]', true);
                    $thumb = imageUrl($imgs[0] ?? null) ?: 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=600';
                @endphp
                <a href="/package/{{ $pkg->slug }}"
                   class="group block bg-white rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-3 transition-all duration-700">
                    <div class="relative h-72 overflow-hidden">
                        <img src="{{ $thumb }}" alt="{{ $pkg->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 flex items-center gap-2">
                            <span class="px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-xl">
                                <i class="fas fa-clock mr-2 opacity-60"></i>{{ $pkg->duration ?? 'Fleksibel' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="flex items-center gap-2 text-lake-blue text-[9px] font-black uppercase tracking-widest mb-3">
                            <i class="fas fa-location-dot"></i>
                            <span>{{ $pkg->locationTag ?? 'Sumatera Utara' }}</span>
                        </div>
                        <h3 class="font-black text-slate-900 text-xl leading-tight mb-4 group-hover:text-lake-blue transition-colors">{{ $pkg->name }}</h3>
                        <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                            <div>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Mulai Dari</p>
                                <p class="text-2xl font-black text-slate-900">
                                    Rp {{ number_format($pkg->price ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-lake-blue text-white flex items-center justify-center shadow-lg shadow-lake-blue/30 group-hover:bg-slate-900 transition-all">
                                <i class="fas fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         LATEST GALLERY GRID
    ══════════════════════════════════════════════════════════════════════ --}}
    <section class="bg-white py-24 md:py-32 px-6 md:px-16 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-end justify-between gap-6 mb-16">
                <div class="max-w-2xl">
                    <span class="text-lake-blue text-[11px] font-black uppercase tracking-[0.4em] mb-4 block">Dokumentasi Perjalanan</span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 leading-[0.9] tracking-tighter">
                        Momen Tak Terlupakan di <br><span class="text-lake-blue">Danau Toba</span>
                    </h2>
                </div>
                <a href="/gallery" class="shrink-0 px-10 py-5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-lake-blue transition-all shadow-xl shadow-slate-900/10">
                    Lihat Galeri Lengkap <i class="fas fa-images ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($gallery as $index => $item)
                    @php
                        // Logic for bento grid sizes
                        $gridClass = 'col-span-1 row-span-1';
                        $height = 'h-48 md:h-64';
                        if($index === 0) { $gridClass = 'col-span-2 row-span-2'; $height = 'h-full min-h-[300px]'; }
                        if($index === 5) { $gridClass = 'md:col-span-2'; }
                    @endphp
                    <div class="{{ $gridClass }} group relative overflow-hidden rounded-[2rem] shadow-2xl shadow-slate-200/50">
                        <img src="{{ imageUrl($item->image_path) }}" 
                             alt="{{ $item->title ?? 'Sujailake Toba' }}"
                             class="w-full {{ $height }} object-cover transition-transform duration-1000 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end p-8">
                            <div>
                                <p class="text-white font-black text-sm mb-1">{{ $item->title ?? 'Eksplorasi Toba' }}</p>
                                <p class="text-lake-light text-[9px] font-black uppercase tracking-widest">{{ $item->category ?? 'Wisata' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($gallery->isEmpty())
                    <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                        <i class="fas fa-images text-slate-300 text-4xl mb-4"></i>
                        <p class="text-slate-400 font-bold tracking-widest uppercase text-xs">Belum ada foto di galeri</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════════
         SPECIALIST SECTION
    ══════════════════════════════════════════════════════════════════════ --}}
    @if(!empty($siteSettings['cms_landing']['specialist_name']))
    <section class="bg-slate-950 py-24 md:py-32 px-6 md:px-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 1px 1px,rgba(255,255,255,0.5) 1px,transparent 0);background-size:60px 60px"></div>
        
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16 relative z-10">
            <div class="w-full md:w-1/3 relative">
                <div class="aspect-[4/5] rounded-[3rem] overflow-hidden shadow-2xl relative z-10">
                    <img src="{{ imageUrl($siteSettings['cms_landing']['specialist_image_url'] ?? null) ?: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600' }}" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-10 -right-10 w-full h-full bg-lake-blue/20 rounded-[3rem] -z-10"></div>
            </div>

            <div class="w-full md:w-2/3 space-y-10">
                <div class="space-y-4">
                    <span class="text-lake-light text-[11px] font-black uppercase tracking-[0.4em] block">Travel Specialist Kami</span>
                    <h2 class="text-4xl md:text-6xl font-black text-white leading-none tracking-tighter">
                        Wujudkan Liburan <br><span class="text-lake-light">Impian Anda</span>
                    </h2>
                </div>
                
                <p class="text-slate-400 text-xl font-medium leading-relaxed italic">
                    "{{ $siteSettings['cms_landing']['specialist_desc'] ?? 'Kami siap membantu merencanakan setiap detail perjalanan Anda agar menjadi momen tak terlupakan.' }}"
                </p>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-8">
                    <div class="space-y-1">
                        <p class="text-white font-black text-xl">{{ $siteSettings['cms_landing']['specialist_name'] }}</p>
                        <p class="text-lake-light text-[10px] font-black uppercase tracking-[0.2em]">{{ $siteSettings['cms_landing']['specialist_title'] }}</p>
                    </div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6281323888207') }}" 
                       class="px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-lake-light transition-all">
                        Konsultasi Gratis <i class="fab fa-whatsapp ml-2 text-sm text-green-500"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         TESTIMONIALS
    ══════════════════════════════════════════════════════════════════════ --}}
    @if(isset($siteSettings['cms_landing']['testimonials']) && count($siteSettings['cms_landing']['testimonials']) > 0)
    <section class="bg-white py-24 md:py-32 px-6 md:px-16">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <span class="text-lake-blue text-[11px] font-black uppercase tracking-[0.4em] mb-4 block">Ulasan Pengunjung</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter">
                    Apa Kata <span class="text-lake-blue">Mereka</span>?
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($siteSettings['cms_landing']['testimonials'] as $t)
                <div class="p-10 bg-slate-50 rounded-[3rem] space-y-8 relative group hover:bg-slate-900 transition-all duration-700">
                    <div class="flex items-center gap-4">
                        <img src="{{ imageUrl($t['image'] ?? null) ?: 'https://i.pravatar.cc/100?u='.urlencode($t['name']) }}" class="w-14 h-14 rounded-2xl object-cover shadow-lg">
                        <div>
                            <p class="font-black text-slate-900 group-hover:text-white transition-colors">{{ $t['name'] }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-lake-light transition-colors">{{ $t['location'] }}</p>
                        </div>
                    </div>
                    <p class="text-slate-500 font-medium leading-relaxed italic group-hover:text-slate-300 transition-colors">
                        "{{ $t['text'] }}"
                    </p>
                    <div class="flex text-amber-400 text-[10px]">
                        @for($i=0; $i<5; $i++) <i class="fas fa-star"></i> @endfor
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         LATEST BLOG
    ══════════════════════════════════════════════════════════════════════ --}}
    @if(isset($blogs) && $blogs->count() > 0)
    <section class="bg-slate-50 py-24 md:py-32 px-6 md:px-16">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-end justify-between gap-6 mb-16">
                <div class="max-w-xl">
                    <span class="text-lake-blue text-[11px] font-black uppercase tracking-[0.4em] mb-4 block">Blog & Artikel</span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter">
                        Cerita Seru <span class="text-lake-blue">Perjalanan</span>
                    </h2>
                </div>
                <a href="/blog" class="shrink-0 text-slate-900 font-black text-sm uppercase tracking-widest border-b-2 border-lake-blue pb-1 hover:text-lake-blue transition">
                    Lihat Semua
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($blogs->take(3) as $post)
                @php
                    $thumb = imageUrl($post->thumbnail ?? null) ?: 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=600';
                @endphp
                <a href="/blog/{{ $post->slug }}" class="group block">
                    <div class="h-64 rounded-[2.5rem] overflow-hidden mb-6 shadow-xl border border-white">
                        <img src="{{ $thumb }}" alt="{{ $post->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="flex items-center gap-3 text-[10px] font-black text-lake-blue uppercase tracking-widest mb-3">
                        <span>{{ $post->createdAt->format('d M Y') }}</span>
                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                        <span>{{ $post->category ?? 'Wisata' }}</span>
                    </div>
                    <h3 class="font-black text-slate-900 text-xl leading-tight mb-3 group-hover:text-lake-blue transition-colors line-clamp-2">{{ $post->title }}</h3>
                    <p class="text-slate-500 text-sm line-clamp-2 leading-relaxed font-medium">{{ $post->shortDescription }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         CTA BANNER
    ══════════════════════════════════════════════════════════════════════ --}}
    <section class="bg-lake-blue py-24 md:py-32 px-6 md:px-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-lake-blue via-lake-blue to-slate-950"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-8 leading-tight">
                Siap Menjelajahi <br>Danau Toba Bersama Kami?
            </h2>
            <p class="text-blue-100 text-xl font-medium mb-12 max-w-2xl mx-auto leading-relaxed">
                Konsultasikan rencana liburan Anda secara GRATIS. Kami akan menyusun itinerary terbaik yang sesuai dengan keinginan dan budget Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6281323888207') }}"
                   target="_blank"
                   class="px-10 py-6 bg-white text-slate-900 rounded-[2rem] font-black text-xs uppercase tracking-widest hover:bg-lake-light hover:text-white transition-all shadow-2xl flex items-center justify-center gap-3">
                    <i class="fab fa-whatsapp text-green-500 text-xl"></i>
                    Chat Specialist Kami
                </a>
                <a href="/packages"
                   class="px-10 py-6 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-[2rem] font-black text-xs uppercase tracking-widest hover:bg-white/20 transition-all flex items-center justify-center gap-3">
                    <i class="fas fa-map"></i>
                    Lihat Paket Wisata
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('layouts.partials.footer')

</body>
</html>
