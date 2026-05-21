<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteSettings['general']['seo_meta_title'] ?? $siteSettings['cms_landing']['meta_title'] ?? 'Sujai Laketoba | Premium Tour Travel' }}</title>
    <meta name="description" content="{{ $siteSettings['general']['seo_meta_desc'] ?? $siteSettings['cms_landing']['meta_description'] ?? 'Portal utama Sujai Laketoba. Pilih layanan premium Tour Travel Sumatera Utara.' }}">
    <meta name="keywords" content="{{ $siteSettings['general']['seo_meta_keywords'] ?? 'paket wisata danau toba, travel medan toba, sujai laketoba, tour danau toba murah' }}">
    <link rel="icon" type="image/x-icon" href="{{ $siteSettings['general']['icon_url'] ?? asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Deferred -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css"></noscript>
    <style>
        .split-overlay {
            background: linear-gradient(to bottom, rgba(255,255,255,0.05) 0%, rgba(15,23,42,0.38) 100%);
        }
        .bg-zoom {
            animation: ken-burns 45s infinite alternate cubic-bezier(0.25, 1, 0.5, 1);
        }
        @keyframes ken-burns {
            from { transform: scale(1); }
            to { transform: scale(1.04); }
        }
    </style>
</head>
<body class="overflow-x-hidden bg-slate-50 text-slate-900 selection:bg-emerald-100 selection:text-emerald-900">
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
                <div class="w-12 h-12 bg-emerald-800 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-md mb-2">S</div>
                <h1 class="text-white font-semibold text-sm tracking-[0.25em] uppercase">{{ $brandName }}</h1>
            </div>
        @endif
    </div>

    <!-- Language Toggle -->
    <div class="fixed top-8 right-8 z-[100]" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl text-white hover:bg-white/20 transition-all focus:outline-none">
            <span class="mr-2 text-xs font-bold uppercase tracking-widest">
                @if(session('locale', 'my') === 'my')
                    🇲🇾 MYR
                @elseif(session('locale', 'my') === 'id')
                    🇮🇩 IDR
                @else
                    🇸🇬 SGD
                @endif
            </span>
            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-3 w-48 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl py-2 overflow-hidden">
            <a href="{{ route('change-locale', 'my') }}" class="flex items-center px-5 py-3 text-white hover:bg-white/10 text-[10px] font-bold uppercase tracking-widest {{ session('locale', 'my') === 'my' ? 'text-emerald-400 bg-white/5' : '' }}">
                <span class="mr-3 text-base">🇲🇾</span> MYR (Melayu)
            </a>
            <a href="{{ route('change-locale', 'id') }}" class="flex items-center px-5 py-3 text-white hover:bg-white/10 text-[10px] font-bold uppercase tracking-widest {{ session('locale', 'my') === 'id' ? 'text-emerald-400 bg-white/5' : '' }}">
                <span class="mr-3 text-base">🇮🇩</span> IDR (Indonesia)
            </a>
            <a href="{{ route('change-locale', 'en') }}" class="flex items-center px-5 py-3 text-white hover:bg-white/10 text-[10px] font-bold uppercase tracking-widest {{ session('locale', 'my') === 'en' ? 'text-emerald-400 bg-white/5' : '' }}">
                <span class="mr-3 text-base">🇸🇬</span> SGD (English)
            </a>
        </div>
    </div>

    <main class="min-h-screen md:min-h-[80vh] flex flex-col md:flex-row relative">
        <!-- Tour & Travel (Full Width) -->
        <div class="relative w-full min-h-[80vh] md:min-h-[80vh] group overflow-hidden flex-grow">
            @php
                $tourUrl = imageUrl($content['tour_image_url'] ?? null);
                if (empty($tourUrl) || str_contains($tourUrl, 'unsplash')) {
                    $tourUrl = asset('images/home/tour.webp');
                }
            @endphp
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[10s] group-hover:scale-105 bg-zoom" 
                 style="background-image: url('{{ $tourUrl }}')"></div>
            <div class="absolute inset-0 split-overlay transition-opacity duration-700"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end items-center p-5 md:p-10 lg:p-14 text-center z-10">
                <div class="max-w-3xl mx-auto bg-white/88 backdrop-blur-md border border-white/50 rounded-3xl p-7 md:p-10 mb-5 md:mb-7 shadow-lg">
                    <span class="inline-block px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[9px] md:text-[10px] font-semibold uppercase tracking-[0.25em] rounded-md mb-3 md:mb-4">{{ __('Tour Travel Mudah') }}</span>
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-semibold text-slate-900 leading-tight tracking-tight">
                        {!! nl2br(e($content['tour_title'] ?? "Tour &\nTravel.")) !!}
                    </h2>
                    <p class="hidden sm:block text-slate-600 text-sm md:text-base font-normal max-w-2xl mx-auto mt-4 leading-relaxed">
                        {{ $content['tour_subtitle'] ?? 'Eksplorasi Danau Toba dan sekitarnya dengan proses pemesanan yang sederhana, jelas, dan nyaman.' }}
                    </p>
                </div>
                <p class="hidden sm:block text-slate-600 text-sm md:text-lg font-normal max-w-2xl mb-5 md:mb-8 leading-relaxed text-center bg-white/80 backdrop-blur-sm px-5 py-3 rounded-full border border-slate-200">
                    {{ $content['tour_subtitle'] ?? 'Eksplorasi keindahan Danau Toba dengan paket yang jelas, nyaman, dan mudah dipilih.' }}
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <a href="/tour" class="px-8 py-4 md:px-10 md:py-4 bg-slate-900 text-white border border-slate-900 rounded-xl font-semibold text-xs md:text-sm uppercase tracking-widest hover:bg-slate-800 transition-all duration-300">
                        Jelajahi Wisata
                    </a>
                    <a href="/tour/packages" class="px-8 py-4 md:px-10 md:py-4 bg-white text-slate-900 border border-slate-200 rounded-xl font-semibold text-xs md:text-sm uppercase tracking-widest hover:bg-slate-50 transition-all duration-300 shadow-sm">
                        Lihat Paket
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
                        // CMS content updated — auto-reload
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
