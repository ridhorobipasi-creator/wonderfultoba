<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts: preconnect + non-render-blocking link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <title>@yield('title', $siteSettings['general']['seo_meta_title'] ?? 'Sujai Laketoba | Premium Tour Travel')</title>
    <meta name="description" content="{{ strip_tags($__env->yieldContent('description', $siteSettings['general']['seo_meta_desc'] ?? 'Portal utama Sujai Laketoba. Pilih layanan premium Tour Travel Sumatera Utara.')) }}">
    <meta name="keywords" content="{{ strip_tags($__env->yieldContent('keywords', $siteSettings['general']['seo_meta_keywords'] ?? 'tour danau toba, travel sumatera utara')) }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/x-icon" href="{{ imageUrl($siteSettings['general']['icon_url'] ?? null, asset('favicon.ico')) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ strip_tags($__env->yieldContent('title', $siteSettings['general']['seo_meta_title'] ?? 'Sujai Laketoba | Premium Tour Travel')) }}">
    <meta property="og:description" content="{{ strip_tags($__env->yieldContent('description', $siteSettings['general']['seo_meta_desc'] ?? 'Portal utama Sujai Laketoba. Pilih layanan premium Tour Travel Sumatera Utara.')) }}">
    <meta property="og:image" content="{{ $__env->yieldContent('og_image', ogBannerUrl($package ?? $post ?? null)) }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ strip_tags($__env->yieldContent('title', $siteSettings['general']['seo_meta_title'] ?? 'Sujai Laketoba | Premium Tour Travel')) }}">
    <meta property="twitter:description" content="{{ strip_tags($__env->yieldContent('description', $siteSettings['general']['seo_meta_desc'] ?? 'Portal utama Sujai Laketoba. Pilih layanan premium Tour Travel Sumatera Utara.')) }}">
    <meta property="twitter:image" content="{{ $__env->yieldContent('og_image', ogBannerUrl($package ?? $post ?? null)) }}">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- FontAwesome deferred (non-render-blocking) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css"></noscript>
    
    <!-- Google Analytics (Dynamic from Settings) -->
    @if(!empty($siteSettings['general']['seo_ga_id']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings['general']['seo_ga_id'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $siteSettings['general']['seo_ga_id'] }}');
    </script>
    @endif

    @php
        $activeLocale = session('locale', 'my');
        $rate = \App\Helpers\CurrencyHelper::getRate($activeLocale === 'en' ? 'SGD' : ($activeLocale === 'my' ? 'MYR' : 'IDR'));
        $symbol = $activeLocale === 'en' ? 'S$ ' : ($activeLocale === 'my' ? 'RM ' : 'Rp ');
        $decimals = $activeLocale === 'id' ? 0 : 2;
        $thousandsSep = $activeLocale === 'id' ? '.' : ',';
        $decPoint = $activeLocale === 'id' ? ',' : '.';
    @endphp
    <script>
        window.AppCurrency = {
            locale: @json($activeLocale),
            rate: {{ $rate }},
            symbol: @json($symbol),
            decimals: {{ $decimals }},
            thousandsSep: @json($thousandsSep),
            decPoint: @json($decPoint),
            format: function(priceInIdr) {
                if (priceInIdr === null || priceInIdr === undefined || priceInIdr === '') return '-';
                let converted = priceInIdr * this.rate;
                let formatted = parseFloat(converted).toFixed(this.decimals);
                
                // Format thousands separator
                let parts = formatted.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, this.thousandsSep);
                
                return this.symbol + parts.join(this.decPoint);
            }
        };
    </script>

    @stack('styles')
    @stack('head')
    @stack('schema')
</head>
<body class="font-sans text-slate-900 bg-white selection:bg-green-100 selection:text-green-900 overflow-x-hidden" x-data="{ isDark: false }">
    
    <!-- Navbar -->
    @include('layouts.partials.navbar')

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Floating WhatsApp & Top -->
    <div class="fixed bottom-8 right-8 z-[90] flex flex-col gap-4" x-data="{ showTop: false }" @scroll.window="showTop = window.scrollY > 500">
        <!-- Scroll to Top -->
        <button x-show="showTop" aria-label="Scroll to top"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-8 scale-90"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-90"
                @click="window.scrollTo({top: 0, behavior: 'smooth'})"
                class="w-12 h-12 bg-white/90 backdrop-blur-md text-slate-700 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all duration-300 group border border-slate-200/50 hover:scale-110">
            <i class="fas fa-arrow-up text-sm group-hover:-translate-y-1 transition-transform duration-300"></i>
        </button>
        <!-- WhatsApp Button -->
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6281323888207') }}?text={{ urlencode($siteSettings['general']['wa_message'] ?? 'Halo Sujai Laketoba, saya ingin bertanya tentang paket wisata...') }}" 
           target="_blank"
           class="w-14 h-14 bg-gradient-to-tr from-emerald-500 to-emerald-400 text-white rounded-full shadow-[0_8px_30px_rgba(16,185,129,0.3)] flex items-center justify-center hover:shadow-[0_8px_30px_rgba(16,185,129,0.5)] transition-all duration-300 hover:-translate-y-1 hover:scale-110 group relative border border-emerald-300/30">
            <i class="fab fa-whatsapp text-2xl group-hover:animate-pulse"></i>
            <span class="absolute right-full mr-4 px-3 py-1.5 bg-slate-900/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-widest rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap pointer-events-none translate-x-2 group-hover:translate-x-0">
                Tanya Spesialis
            </span>
        </a>
    </div>

    <!-- CMS Realtime Sync (No-Supabase Version) -->
    <script>
        (function() {
            let currentVersion = null;
            const checkInterval = 30000; // 30 seconds — light on battery/data for mobile
            let timer = null;
            
            async function checkCmsVersion() {
                if (document.visibilityState !== 'visible') return;

                try {
                    const response = await fetch('{{ route('api.sync.version') }}');
                    const data = await response.json();
                    
                    if (currentVersion === null) {
                        currentVersion = data.version;
                    } else if (data.version !== currentVersion) {
                        // CMS content updated — auto-reload
                        window.location.reload();
                    }
                } catch (e) {}
            }

            function startPolling() {
                if (!timer) timer = setInterval(checkCmsVersion, checkInterval);
            }

            function stopPolling() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            // Start polling only on non-admin pages
            if (!window.location.pathname.startsWith('/admin')) {
                startPolling();
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible') startPolling();
                    else stopPolling();
                });
            }
        })();
    </script>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
