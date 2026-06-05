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
    @if(!empty($siteSettings['general']['seo_google_verification']))
    <meta name="google-site-verification" content="{{ $siteSettings['general']['seo_google_verification'] }}">
    @endif
    <link rel="icon" type="image/x-icon" href="{{ imageUrl($siteSettings['general']['icon_url'] ?? null, asset('favicon.ico')) }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1a6b4a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $siteSettings['general']['site_name'] ?? 'Sujai Laketoba' }}">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <!-- Open Graph / Facebook -->
    @php
        $ogModel = $package ?? $post ?? null;
        $ogDefault = $ogModel
            ? ogBannerUrl($ogModel)
            : (!empty($siteSettings['general']['og_image_url']) ? imageUrl($siteSettings['general']['og_image_url']) : ogBannerUrl(null));
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ strip_tags($__env->yieldContent('title', $siteSettings['general']['seo_meta_title'] ?? 'Sujai Laketoba | Premium Tour Travel')) }}">
    <meta property="og:description" content="{{ strip_tags($__env->yieldContent('description', $siteSettings['general']['seo_meta_desc'] ?? 'Portal utama Sujai Laketoba. Pilih layanan premium Tour Travel Sumatera Utara.')) }}">
    <meta property="og:image" content="{{ $__env->yieldContent('og_image', $ogDefault) }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ strip_tags($__env->yieldContent('title', $siteSettings['general']['seo_meta_title'] ?? 'Sujai Laketoba | Premium Tour Travel')) }}">
    <meta property="twitter:description" content="{{ strip_tags($__env->yieldContent('description', $siteSettings['general']['seo_meta_desc'] ?? 'Portal utama Sujai Laketoba. Pilih layanan premium Tour Travel Sumatera Utara.')) }}">
    <meta property="twitter:image" content="{{ $__env->yieldContent('og_image', $ogDefault) }}">

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

    @if(!empty($siteSettings['general']['seo_pixel_id']))
    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $siteSettings['general']['seo_pixel_id'] }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $siteSettings['general']['seo_pixel_id'] }}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
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
<body class="font-sans text-slate-900 bg-white selection:bg-green-100 selection:text-green-900 overflow-x-hidden pb-24 md:pb-0" x-data="{ isDark: false }">
    
    <!-- Navbar -->
    @include('layouts.partials.navbar')

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    @php
        // Resolve the floating WhatsApp number from saved settings.
        // env() returns null once config is cached, so never read COMPANY_PHONE here.
        $waFloat = preg_replace('/[^0-9]/', '', (string) (
            $siteSettings['general']['contact_whatsapp']
            ?? $siteSettings['general']['contact_whatsapp']
            ?? $siteSettings['general']['contact_whatsapp']
            ?? config('services.whatsapp.number')
            ?? ''
        ));
    @endphp
    <!-- Floating WhatsApp & Top (Desktop Only) -->
    <div class="fixed bottom-8 right-8 z-[90] hidden md:flex flex-col gap-4" x-data="{ showTop: false }" @scroll.window="showTop = window.scrollY > 500">
        <!-- Back to Top -->
        <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
                x-show="showTop"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-8"
                class="w-12 h-12 bg-white/90 backdrop-blur-md text-slate-900 rounded-full flex items-center justify-center shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:bg-slate-50 transition-all duration-300 border border-slate-200">
            <span class="material-symbols-outlined">arrow_upward</span>
        </button>
        <!-- WhatsApp -->
        <a href="https://wa.me/{{ $waFloat }}" target="_blank" class="w-14 h-14 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-all duration-300 shadow-[0_8px_30px_rgb(34,197,94,0.3)] hover:-translate-y-1 group relative">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
            <span class="absolute right-full mr-4 px-3 py-1.5 bg-slate-900/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-widest rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap pointer-events-none translate-x-2 group-hover:translate-x-0">
                {{ __('Tanya Spesialis') }}
            </span>
        </a>
    </div>

    <!-- Mobile Sticky Bottom CTA Bar -->
    <div class="fixed bottom-0 left-0 w-full z-[90] md:hidden bg-white/95 backdrop-blur-md border-t border-slate-100 shadow-[0_-8px_30px_rgb(0,0,0,0.08)] px-4 py-3 flex items-center justify-between gap-3 safe-area-bottom">
        <a href="https://wa.me/{{ $waFloat }}" 
           class="flex-1 bg-green-50 text-green-600 rounded-xl py-3.5 flex items-center justify-center gap-2 font-bold text-[11px] uppercase tracking-widest border border-green-100">
           <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
           WhatsApp
        </a>
        <a href="{{ route('tour.packages') }}" 
           class="flex-[1.5] bg-secondary-fixed text-on-secondary-fixed rounded-xl py-3.5 flex items-center justify-center gap-2 font-bold text-[11px] uppercase tracking-widest shadow-md">
           <span class="material-symbols-outlined text-[16px]">travel_explore</span>
           {{ __('Pesan Sekarang') }}
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

    <!-- PWA: Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator && !window.location.pathname.startsWith('/admin')) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
