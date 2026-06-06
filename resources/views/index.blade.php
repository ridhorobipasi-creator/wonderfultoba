<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteSettings['general']['seo_meta_title'] ?? $siteSettings['cms_landing']['meta_title'] ?? 'Wonderful Toba | Premium Tour & Corporate Outbound' }}</title>
    <meta name="description" content="{{ $siteSettings['general']['seo_meta_desc'] ?? $siteSettings['cms_landing']['meta_description'] ?? 'Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building.' }}">
    <link rel="icon" type="image/x-icon" href="{{ $siteSettings['general']['icon_url'] ?? asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- FontAwesome Deferred -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css"></noscript>
    <style>
        .split-overlay {
            background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.7));
        }
        .bg-zoom {
            animation: ken-burns 20s infinite alternate;
        }
        @keyframes ken-burns {
            from { transform: scale(1) translate(0,0); }
            to { transform: scale(1.15) translate(-2%, -2%); }
        }
    </style>
</head>
<body class="font-sans overflow-x-hidden bg-black">
    <!-- Preload Hero Images for LCP -->
    <link rel="preload" as="image" href="{{ imageUrl($siteSettings['cms_landing']['outbound_image_url'] ?? null) ?: 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=1600' }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ imageUrl($siteSettings['cms_landing']['tour_image_url'] ?? null) ?: 'https://images.unsplash.com/photo-1544735049-717bc392183e?w=1600' }}" fetchpriority="high">
    
    <!-- Floating Logo -->
    <div class="fixed top-8 left-1/2 -translate-x-1/2 z-[100] pointer-events-none">
        @php
            $logoUrl = $siteSettings['general']['logo_light_url'] ?? null;
            $brandName = $siteSettings['general']['site_name'] ?? 'Wonderful Toba';
        @endphp

        @if($logoUrl)
            <img src="{{ imageUrl($logoUrl) }}" class="h-12 md:h-16 w-auto object-contain drop-shadow-2xl">
        @else
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-emerald-600 rounded-[2rem] flex items-center justify-center text-white font-black text-3xl shadow-2xl mb-4">W</div>
                <h1 class="text-white font-black text-xl tracking-[0.2em] uppercase">{{ $brandName }}</h1>
            </div>
        @endif
    </div>

    <!-- Escape Nav: link sekunder agar pengunjung tidak terjebak hanya 2 pilihan -->
    @php
        $landingWa = $siteSettings['general']['whatsapp'] ?? preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6281323888207');
    @endphp
    <nav class="fixed top-6 right-6 z-[110] flex items-center gap-0.5 sm:gap-1">
        <a href="/about" class="px-3 py-2 text-white/80 hover:text-white text-[11px] font-bold uppercase tracking-widest transition-colors drop-shadow">Tentang</a>
        <a href="/tour/gallery" class="px-3 py-2 text-white/80 hover:text-white text-[11px] font-bold uppercase tracking-widest transition-colors drop-shadow">Galeri</a>
        <a href="/tour/blog" class="hidden sm:inline-block px-3 py-2 text-white/80 hover:text-white text-[11px] font-bold uppercase tracking-widest transition-colors drop-shadow">Blog</a>
        <a href="https://wa.me/{{ $landingWa }}" target="_blank" rel="noopener" class="ml-1 px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-white hover:text-slate-900 transition-all">Kontak</a>
    </nav>

    <main class="min-h-screen h-screen md:h-[100dvh] flex flex-col md:flex-row relative">
        @php
            $showOutbound = $siteSettings['cms_landing']['show_outbound'] ?? true;
            $showTour = $siteSettings['cms_landing']['show_tour'] ?? true;
            $outboundWidth = !$showTour ? 'w-full' : 'w-full md:w-1/2';
            $tourWidth = !$showOutbound ? 'w-full' : 'w-full md:w-1/2';
        @endphp
        
        @if($showOutbound)
        <!-- Left: Corporate Outbound -->
        <div class="relative {{ $outboundWidth }} h-1/2 md:h-full group overflow-hidden border-b md:border-b-0 md:border-r border-white/5 flex-grow">
            @php
                $outboundUrl = imageUrl($content['outbound_image_url'] ?? null);
                if (empty($outboundUrl) || str_contains($outboundUrl, 'unsplash')) {
                    $outboundUrl = asset('images/home/outbound.webp');
                }
            @endphp
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[8s] group-hover:scale-110 bg-zoom" 
                 style="background-image: url('{{ $outboundUrl }}')"></div>
            <div class="absolute inset-0 split-overlay transition-opacity duration-700 group-hover:opacity-90"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-16 lg:p-24 z-10">
                <div class="overflow-hidden mb-4 md:mb-6">
                    <span class="inline-block px-4 py-1.5 bg-emerald-500/10 backdrop-blur-md border border-emerald-500/20 text-emerald-400 text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-4 md:mb-6">B2B & Corporate</span>
                    <h2 class="text-4xl md:text-7xl lg:text-[6.5rem] font-black text-white leading-[0.85] tracking-tighter">
                        {!! nl2br(e($content['outbound_title'] ?? "Corporate\nOutbound.")) !!}
                    </h2>
                </div>
                <p class="hidden sm:block text-slate-200 text-sm md:text-lg font-medium max-w-sm mb-8 md:mb-12 leading-relaxed opacity-80">
                    {{ $content['outbound_subtitle'] ?? 'Solusi team building & gathering profesional untuk instansi Anda.' }}
                </p>
                <div class="flex items-center space-x-4 md:space-x-6">
                    <a href="/outbound" class="group/btn flex-1 sm:flex-none text-center px-8 py-4 md:px-10 md:py-5 bg-emerald-700 text-white rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-2xl border border-emerald-500/20 hover:bg-white hover:text-emerald-900 transition-all duration-500">
                        Jelajahi Outbound
                    </a>
                    <a href="/outbound" aria-label="Jelajahi Outbound" class="group/arrow relative w-12 h-12 md:w-14 md:h-14 rounded-full border border-white/20 flex items-center justify-center text-white transition-all hover:bg-white hover:text-emerald-900 overflow-hidden cursor-pointer shrink-0">
                        <i class="fas fa-arrow-right -rotate-45 relative z-10 group-hover/arrow:rotate-0 transition-transform duration-500" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if($showTour)
        <!-- Right: Tour & Travel -->
        <div class="relative {{ $tourWidth }} h-1/2 md:h-full group overflow-hidden flex-grow">
            @php
                $tourUrl = imageUrl($content['tour_image_url'] ?? null);
                if (empty($tourUrl) || str_contains($tourUrl, 'unsplash')) {
                    $tourUrl = asset('images/home/tour.webp');
                }
            @endphp
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[8s] group-hover:scale-110 bg-zoom" 
                 style="background-image: url('{{ $tourUrl }}')"></div>
            <div class="absolute inset-0 split-overlay transition-opacity duration-700 group-hover:opacity-90"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end items-end p-8 md:p-16 lg:p-24 text-right z-10">
                <div class="overflow-hidden mb-4 md:mb-6">
                    <span class="inline-block px-4 py-1.5 bg-emerald-500/10 backdrop-blur-md border border-emerald-500/20 text-emerald-400 text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-4 md:mb-6">Premium Leisure</span>
                    <h2 class="text-4xl md:text-7xl lg:text-[6.5rem] font-black text-white leading-[0.85] tracking-tighter">
                        {!! nl2br(e($content['tour_title'] ?? "Tour &\nTravel.")) !!}
                    </h2>
                </div>
                <p class="hidden sm:block text-slate-200 text-sm md:text-lg font-medium max-w-sm mb-8 md:mb-12 leading-relaxed opacity-80 text-right">
                    {{ $content['tour_subtitle'] ?? 'Eksplorasi keindahan Danau Toba dengan paket liburan eksklusif kami.' }}
                </p>
                <div class="flex items-center space-x-4 md:space-x-6">
                    <a href="/tour" aria-label="Jelajahi Wisata" class="group/arrow relative w-12 h-12 md:w-14 md:h-14 rounded-full border border-white/20 flex items-center justify-center text-white transition-all hover:bg-white hover:text-emerald-900 overflow-hidden cursor-pointer shrink-0">
                        <i class="fas fa-arrow-left rotate-45 relative z-10 group-hover/arrow:rotate-0 transition-transform duration-500" aria-hidden="true"></i>
                    </a>
                    <a href="/tour" class="group/btn flex-1 sm:flex-none text-center px-8 py-4 md:px-10 md:py-5 bg-emerald-800 text-white rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-2xl border border-emerald-400/20 hover:bg-white hover:text-emerald-900 transition-all duration-500">
                        Jelajahi Wisata
                    </a>
                </div>
            </div>
        </div>
        @endif

    </main>


    <!-- Notifikasi konten diperbarui (tanpa reload paksa) -->
    <button id="cms-update-toast" type="button" onclick="window.location.reload()"
            class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[120] translate-y-24 opacity-0 transition-all duration-500 flex items-center gap-3 bg-white text-slate-900 pl-5 pr-3 py-3 rounded-2xl shadow-2xl">
        <i class="fas fa-arrows-rotate text-emerald-600" aria-hidden="true"></i>
        <span class="text-sm font-bold">Konten diperbarui</span>
        <span class="text-[11px] font-black uppercase tracking-widest bg-emerald-600 text-white px-3 py-1.5 rounded-xl">Muat ulang</span>
    </button>

    <!-- CMS Realtime Sync (No-Supabase Version) -->
    <script>
        (function() {
            let currentVersion = null;
            const checkInterval = 30000; // 30 detik
            let notified = false;

            async function checkCmsVersion() {
                if (document.visibilityState !== 'visible' || notified) return;
                try {
                    const response = await fetch('{{ route('api.sync.version') }}');
                    const data = await response.json();
                    if (currentVersion === null) {
                        currentVersion = data.version;
                    } else if (data.version !== currentVersion) {
                        notified = true;
                        const t = document.getElementById('cms-update-toast');
                        if (t) t.classList.remove('translate-y-24', 'opacity-0');
                    }
                } catch (e) {}
            }

            setInterval(checkCmsVersion, checkInterval);
        })();
    </script>
</body>
</html>
