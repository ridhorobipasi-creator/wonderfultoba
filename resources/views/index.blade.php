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
    {{-- Fonts self-hosted & bundled via app.css (Plus Jakarta Sans + subset Material Symbols) --}}

    <style>
        .split-overlay {
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.05) 0%, rgba(15, 23, 42, 0.45) 100%);
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
    <link rel="preload" as="image" href="{{ imageUrl($siteSettings['cms_landing']['outbound_image_url'] ?? null, 'outbound') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ imageUrl($siteSettings['cms_landing']['tour_image_url'] ?? null, 'tour') }}" fetchpriority="high">
    
    <!-- Beautiful Floating Header -->
    <header class="fixed top-6 left-0 right-0 z-[100] px-4 md:px-8" x-data="{ mobileMenuOpen: false, langOpen: false }">
        <nav class="max-w-7xl mx-auto bg-white/75 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-3.5 flex items-center justify-between shadow-lg transition duration-300">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 focus-visible:ring-2 focus-visible:ring-emerald-500 rounded-lg outline-none">
                @php
                    $logoDarkUrl = $siteSettings['general']['logo_dark_url'] ?? $siteSettings['general']['logo_light_url'] ?? null;
                    $brandName = $siteSettings['general']['site_name'] ?? 'Sujai Laketoba';
                @endphp

                @if($logoDarkUrl)
                    <img src="{{ imageUrl($logoDarkUrl) }}" class="h-8 md:h-10 w-auto object-contain" alt="{{ $brandName }}">
                @else
                    <div class="flex items-center space-x-2">
                        <div class="w-9 h-9 bg-emerald-800 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm">S</div>
                        <span class="font-bold text-slate-800 text-sm tracking-wider uppercase hidden sm:block">{{ $brandName }}</span>
                    </div>
                @endif
            </a>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="/" class="text-xs font-semibold text-slate-800 hover:text-emerald-700 tracking-wider uppercase transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded px-2.5 py-1 outline-none">Beranda</a>
                <a href="/tour/packages" class="text-xs font-semibold text-slate-800 hover:text-emerald-700 tracking-wider uppercase transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded px-2.5 py-1 outline-none">Paket Wisata</a>
                <a href="/tour/gallery" class="text-xs font-semibold text-slate-800 hover:text-emerald-700 tracking-wider uppercase transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded px-2.5 py-1 outline-none">Galeri</a>
                <a href="/tour/blog" class="text-xs font-semibold text-slate-800 hover:text-emerald-700 tracking-wider uppercase transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded px-2.5 py-1 outline-none">Blog</a>
                <a href="/about" class="text-xs font-semibold text-slate-800 hover:text-emerald-700 tracking-wider uppercase transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded px-2.5 py-1 outline-none">Tentang Kami</a>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Improved Language & Currency Selector -->
                <div class="relative">
                    <button @click="langOpen = !langOpen" 
                            class="flex items-center gap-2 bg-slate-900/5 hover:bg-slate-900/10 border border-slate-900/10 px-3 py-2 rounded-xl text-slate-800 hover:text-emerald-800 transition focus-visible:ring-2 focus-visible:ring-emerald-500 outline-none"
                            aria-label="Pilih Bahasa dan Mata Uang">
                        <span class="text-xs font-bold tracking-wide uppercase flex items-center gap-1.5">
                            @if(session('locale', 'my') === 'my')
                                🇲🇾 <span class="text-[10px] font-semibold text-slate-600">MYR</span>
                            @elseif(session('locale', 'my') === 'id')
                                🇮🇩 <span class="text-[10px] font-semibold text-slate-600">IDR</span>
                            @else
                                🇸🇬 <span class="text-[10px] font-semibold text-slate-600">SGD</span>
                            @endif
                        </span>
                        <svg class="w-3.5 h-3.5 transition-transform text-slate-500" :class="langOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="langOpen" 
                         @click.away="langOpen = false" 
                         x-transition 
                         class="absolute right-0 mt-3 w-56 bg-white border border-slate-100 rounded-2xl shadow-xl py-2 overflow-hidden z-[110]"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Pilih Bahasa & Kurs
                        </div>
                        <a href="{{ route('change-locale', 'my') }}" class="flex items-center justify-between px-4 py-3 text-slate-700 hover:bg-slate-50 text-xs font-semibold {{ session('locale', 'my') === 'my' ? 'text-emerald-700 bg-emerald-50/50' : '' }}">
                            <span class="flex items-center gap-2"><span class="text-base">🇲🇾</span> Melayu</span>
                            <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono font-bold">MYR</span>
                        </a>
                        <a href="{{ route('change-locale', 'id') }}" class="flex items-center justify-between px-4 py-3 text-slate-700 hover:bg-slate-50 text-xs font-semibold {{ session('locale', 'my') === 'id' ? 'text-emerald-700 bg-emerald-50/50' : '' }}">
                            <span class="flex items-center gap-2"><span class="text-base">🇮🇩</span> Indonesia</span>
                            <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono font-bold">IDR</span>
                        </a>
                        <a href="{{ route('change-locale', 'en') }}" class="flex items-center justify-between px-4 py-3 text-slate-700 hover:bg-slate-50 text-xs font-semibold {{ session('locale', 'my') === 'en' ? 'text-emerald-700 bg-emerald-50/50' : '' }}">
                            <span class="flex items-center gap-2"><span class="text-base">🇸🇬</span> English</span>
                            <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono font-bold">SGD</span>
                        </a>
                    </div>
                </div>

                <!-- Hamburger Button (Mobile) -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="lg:hidden p-2 text-slate-800 hover:text-emerald-800 transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded-xl outline-none"
                        aria-label="Menu Utama">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition 
             class="lg:hidden absolute top-20 left-4 right-4 bg-white/95 backdrop-blur-lg border border-slate-100 rounded-2xl shadow-xl p-5 flex flex-col gap-4 z-[105]"
             style="display: none;">
            <a href="/" class="text-sm font-semibold text-slate-800 hover:text-emerald-700 py-1 transition-colors">Beranda</a>
            <a href="/tour/packages" class="text-sm font-semibold text-slate-800 hover:text-emerald-700 py-1 transition-colors">Paket Wisata</a>
            <a href="/tour/gallery" class="text-sm font-semibold text-slate-800 hover:text-emerald-700 py-1 transition-colors">Galeri</a>
            <a href="/tour/blog" class="text-sm font-semibold text-slate-800 hover:text-emerald-700 py-1 transition-colors">Blog</a>
            <a href="/about" class="text-sm font-semibold text-slate-800 hover:text-emerald-700 py-1 transition-colors">Tentang Kami</a>
        </div>
    </header>

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
                <div class="max-w-3xl mx-auto bg-white/70 backdrop-blur-xl border border-white/40 rounded-3xl p-7 md:p-10 mb-8 shadow-xl">
                    <span class="inline-block px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[9px] md:text-[10px] font-semibold uppercase tracking-[0.25em] rounded-md mb-3 md:mb-4">{{ __('Eksplorasi Tanpa Repot') }}</span>
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-semibold text-slate-900 leading-tight tracking-tight">
                        {!! nl2br(e($content['tour_title'] ?? "Jelajahi Sumatera\nUtara Tanpa Repot.")) !!}
                    </h2>
                    <p class="text-slate-600 text-xs sm:text-sm md:text-base font-normal max-w-2xl mx-auto mt-4 leading-relaxed">
                        {{ $content['tour_subtitle'] ?? 'Serahkan urusan rute, hotel, dan transportasi pada kami. Anda cukup duduk santai dan nikmati keindahan Danau Toba hingga sejuknya Berastagi.' }}
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="/tour" class="px-8 py-4 md:px-10 bg-slate-900 text-white border border-slate-900 rounded-xl font-bold text-sm tracking-wider hover:bg-emerald-800 hover:border-emerald-800 transition duration-300 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 outline-none">
                        Mulai Liburan Anda
                    </a>
                    <a href="/tour/packages" class="px-8 py-4 md:px-10 bg-white text-slate-900 border border-slate-200 rounded-xl font-bold text-sm tracking-wider hover:bg-slate-50 transition duration-300 shadow-sm focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 outline-none">
                        Pilih Destinasi
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Update Notification Toast -->
    <div id="update-toast" class="fixed bottom-6 right-6 z-[200] bg-slate-900 text-white px-5 py-4 rounded-2xl shadow-2xl border border-slate-800 flex items-center gap-4 transition duration-500 translate-y-24 opacity-0 pointer-events-none">
        <div class="flex flex-col gap-0.5">
            <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Konten Diperbarui</span>
            <span class="text-xs text-slate-300">Pembaruan tampilan halaman tersedia.</span>
        </div>
        <button onclick="window.location.reload()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold transition focus-visible:ring-2 focus-visible:ring-emerald-400 outline-none">
            Segarkan
        </button>
    </div>

    <!-- CMS Realtime Sync (No-Supabase Version) -->
    <script>
        (function() {
            let currentVersion = null;
            const checkInterval = 30000; // 30 seconds — light on battery/data for mobile

            function showUpdateToast() {
                const toast = document.getElementById('update-toast');
                if (toast) {
                    toast.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
                    toast.classList.add('translate-y-0', 'opacity-100');
                }
            }

            async function checkCmsVersion() {
                if (document.visibilityState !== 'visible') return;
                try {
                    const response = await fetch('{{ route('api.sync.version') }}');
                    const data = await response.json();
                    
                    if (currentVersion === null) {
                        currentVersion = data.version;
                    } else if (data.version !== currentVersion) {
                        if (document.visibilityState === 'visible') {
                            showUpdateToast();
                        } else {
                            window.location.reload();
                        }
                    }
                } catch (e) {
                }
            }

            setInterval(checkCmsVersion, checkInterval);
        })();
    </script>
</body>
</html>
