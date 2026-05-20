<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteSettings['general']['seo_meta_title'] ?? $siteSettings['cms_landing']['meta_title'] ?? 'Sujai Laketoba | Premium Tour Travel' }}</title>
    <meta name="description" content="{{ $siteSettings['general']['seo_meta_desc'] ?? $siteSettings['cms_landing']['meta_description'] ?? 'Portal utama Sujai Laketoba. Pilih layanan premium Tour Travel Sumatera Utara.' }}">
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
            $brandName = $siteSettings['general']['site_name'] ?? 'Sujai Laketoba';
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
        <!-- Tour & Travel (Full Width) -->
        <div class="relative w-full h-full group overflow-hidden flex-grow">
            @php
                $tourUrl = imageUrl($content['tour_image_url'] ?? null);
                if (empty($tourUrl) || str_contains($tourUrl, 'unsplash')) {
                    $tourUrl = asset('images/home/tour.webp');
                }
            @endphp
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[10s] group-hover:scale-105 bg-zoom" 
                 style="background-image: url('{{ $tourUrl }}')"></div>
            <div class="absolute inset-0 split-overlay transition-opacity duration-700"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end items-center p-8 md:p-16 lg:p-20 text-center z-10">
                <div class="overflow-hidden mb-4 md:mb-6">
                    <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md border border-white/10 text-white text-[9px] md:text-[10px] font-semibold uppercase tracking-[0.25em] rounded-md mb-3 md:mb-4">Premium Leisure</span>
                    <h2 class="text-4xl md:text-6xl lg:text-7xl font-light text-white leading-[1.1] tracking-tight">
                        {!! nl2br(e($content['tour_title'] ?? "Tour &\nTravel.")) !!}
                    </h2>
                </div>
                <p class="hidden sm:block text-slate-300 text-sm md:text-lg font-normal max-w-lg mb-6 md:mb-10 leading-relaxed opacity-90 text-center">
                    {{ $content['tour_subtitle'] ?? 'Eksplorasi keindahan Danau Toba dengan paket liburan eksklusif kami.' }}
                </p>
                <div class="flex items-center">
                    <a href="/tour" class="px-8 py-4 md:px-12 md:py-5 bg-white/10 text-white border border-white/30 rounded-xl font-bold text-xs md:text-sm uppercase tracking-widest hover:bg-white hover:text-slate-900 hover:border-white transition-all duration-300">
                        Jelajahi Wisata
                    </a>
                </div>
            </div>
        </div>
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
