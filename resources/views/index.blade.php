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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Deferred -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css"></noscript>
    <style>
        .split-overlay {
            background: linear-gradient(to bottom, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.6) 100%);
        }
        .bg-zoom {
            animation: ken-burns 30s infinite alternate cubic-bezier(0.25, 1, 0.5, 1);
        }
        @keyframes ken-burns {
            from { transform: scale(1); }
            to { transform: scale(1.08); }
        }
    </style>
</head>
<body class="overflow-x-hidden bg-black text-slate-100 selection:bg-emerald-950 selection:text-emerald-300">
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
            <img src="{{ imageUrl($logoUrl) }}" class="h-10 md:h-12 w-auto object-contain">
        @else
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-emerald-800 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-md mb-2">W</div>
                <h1 class="text-white font-semibold text-sm tracking-[0.25em] uppercase">{{ $brandName }}</h1>
            </div>
        @endif
    </div>

    <main class="min-h-screen h-screen md:h-[100dvh] flex flex-col md:flex-row relative">
        @php
            $showOutbound = $siteSettings['cms_landing']['show_outbound'] ?? true;
            $showTour = $siteSettings['cms_landing']['show_tour'] ?? true;
            $outboundWidth = !$showTour ? 'w-full' : 'w-full md:w-1/2';
            $tourWidth = !$showOutbound ? 'w-full' : 'w-full md:w-1/2';
        @endphp
        
        @if($showOutbound)
        <!-- Left: Corporate Outbound -->
        <div class="relative {{ $outboundWidth }} h-1/2 md:h-full group overflow-hidden border-b md:border-b-0 md:border-r border-white/10 flex-grow">
            @php
                $outboundUrl = imageUrl($content['outbound_image_url'] ?? null);
                if (empty($outboundUrl) || str_contains($outboundUrl, 'unsplash')) {
                    $outboundUrl = asset('images/home/outbound.webp');
                }
            @endphp
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[10s] group-hover:scale-105 bg-zoom" 
                 style="background-image: url('{{ $outboundUrl }}')"></div>
            <div class="absolute inset-0 split-overlay transition-opacity duration-700"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-16 lg:p-20 z-10">
                <div class="overflow-hidden mb-4 md:mb-6">
                    <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md border border-white/10 text-white text-[9px] md:text-[10px] font-semibold uppercase tracking-[0.25em] rounded-md mb-3 md:mb-4">B2B & Corporate</span>
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-light text-white leading-[1.1] tracking-tight">
                        {!! nl2br(e($content['outbound_title'] ?? "Corporate\nOutbound.")) !!}
                    </h2>
                </div>
                <p class="hidden sm:block text-slate-300 text-sm md:text-base font-normal max-w-sm mb-6 md:mb-8 leading-relaxed opacity-90">
                    {{ $content['outbound_subtitle'] ?? 'Solusi team building & gathering profesional untuk instansi Anda.' }}
                </p>
                <div class="flex items-center space-x-4 md:space-x-6">
                    <a href="/outbound" class="px-6 py-3 md:px-8 md:py-4 bg-white/10 text-white border border-white/30 rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-widest hover:bg-white hover:text-slate-900 hover:border-white transition-all duration-300">
                        Jelajahi Outbound
                    </a>
                    <div class="group/arrow relative w-10 h-10 md:w-12 md:h-12 rounded-xl border border-white/20 flex items-center justify-center text-white transition-all hover:bg-white hover:text-slate-900 overflow-hidden cursor-pointer shrink-0">
                        <i class="fas fa-arrow-right -rotate-45 relative z-10 group-hover/arrow:rotate-0 transition-transform duration-300"></i>
                    </div>
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
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[10s] group-hover:scale-105 bg-zoom" 
                 style="background-image: url('{{ $tourUrl }}')"></div>
            <div class="absolute inset-0 split-overlay transition-opacity duration-700"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end items-end p-8 md:p-16 lg:p-20 text-right z-10">
                <div class="overflow-hidden mb-4 md:mb-6">
                    <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md border border-white/10 text-white text-[9px] md:text-[10px] font-semibold uppercase tracking-[0.25em] rounded-md mb-3 md:mb-4">Premium Leisure</span>
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-light text-white leading-[1.1] tracking-tight">
                        {!! nl2br(e($content['tour_title'] ?? "Tour &\nTravel.")) !!}
                    </h2>
                </div>
                <p class="hidden sm:block text-slate-300 text-sm md:text-base font-normal max-w-sm mb-6 md:mb-8 leading-relaxed opacity-90 text-right">
                    {{ $content['tour_subtitle'] ?? 'Eksplorasi keindahan Danau Toba dengan paket liburan eksklusif kami.' }}
                </p>
                <div class="flex items-center space-x-4 md:space-x-6">
                    <div class="group/arrow relative w-10 h-10 md:w-12 md:h-12 rounded-xl border border-white/20 flex items-center justify-center text-white transition-all hover:bg-white hover:text-slate-900 overflow-hidden cursor-pointer shrink-0">
                        <i class="fas fa-arrow-left rotate-45 relative z-10 group-hover/arrow:rotate-0 transition-transform duration-300"></i>
                    </div>
                    <a href="/tour" class="px-6 py-3 md:px-8 md:py-4 bg-white/10 text-white border border-white/30 rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-widest hover:bg-white hover:text-slate-900 hover:border-white transition-all duration-300">
                        Jelajahi Wisata
                    </a>
                </div>
            </div>
        </div>
        @endif

    </main>


    <!-- CMS Realtime Sync (No-Supabase Version) -->
    <script>
        (function() {
            let currentVersion = null;
            const checkInterval = 5000; // 5 seconds
            
            async function checkCmsVersion() {
                try {
                    const response = await fetch('{{ route('api.sync.version') }}');
                    const data = await response.json();
                    
                    if (currentVersion === null) {
                        currentVersion = data.version;
                    } else if (data.version !== currentVersion) {
                        console.log('CMS Update Detected! Syncing content...');
                        window.location.reload();
                    }
                } catch (e) {
                }
            }

            setInterval(checkCmsVersion, checkInterval);
        })();
    </script>
</body>
</html>
