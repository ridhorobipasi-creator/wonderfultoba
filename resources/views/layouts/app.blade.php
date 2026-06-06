<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $siteSettings['general']['seo_meta_title'] ?? 'Wonderful Toba | Premium Tour & Corporate Outbound')</title>
    <meta name="description" content="@yield('description', $siteSettings['general']['seo_meta_desc'] ?? 'Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building.')">
    <meta name="keywords" content="@yield('keywords', $siteSettings['general']['seo_meta_keywords'] ?? 'tour danau toba, outbound medan, travel sumatera utara')">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/x-icon" href="{{ imageUrl($siteSettings['general']['icon_url'] ?? null, asset('favicon.ico')) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', $siteSettings['general']['seo_meta_title'] ?? 'Wonderful Toba | Premium Tour & Corporate Outbound')">
    <meta property="og:description" content="@yield('description', $siteSettings['general']['seo_meta_desc'] ?? 'Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building.')">
    <meta property="og:image" content="@yield('og_image', imageUrl($siteSettings['general']['logo_light_url'] ?? null, asset('images/og-default.webp'))) ">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', $siteSettings['general']['seo_meta_title'] ?? 'Wonderful Toba | Premium Tour & Corporate Outbound')">
    <meta property="twitter:description" content="@yield('description', $siteSettings['general']['seo_meta_desc'] ?? 'Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building.')">
    <meta property="twitter:image" content="@yield('og_image', imageUrl($siteSettings['general']['logo_light_url'] ?? null, asset('images/og-default.webp'))) ">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome (deferred agar tidak memblokir render) -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
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

    @stack('styles')
    @stack('head')
    @stack('schema')
</head>
<body class="font-sans text-slate-900 bg-white selection:bg-green-100 selection:text-green-900 overflow-x-hidden" x-data="{ isDark: false }">
    
    @include('layouts.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <!-- Floating WhatsApp & Top -->
    <div class="fixed bottom-8 right-8 z-[90] flex flex-col gap-4" x-data="{ showTop: false }" @scroll.window="showTop = window.scrollY > 500">
        <!-- Scroll to Top -->
        <button x-show="showTop" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                @click="window.scrollTo({top: 0, behavior: 'smooth'})"
                aria-label="Kembali ke atas"
                class="w-12 h-12 bg-white text-slate-900 rounded-2xl shadow-2xl flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all group border border-slate-100">
            <i class="fas fa-chevron-up text-sm group-hover:-translate-y-1 transition-transform" aria-hidden="true"></i>
        </button>
        <!-- WhatsApp Button -->
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6281323888207') }}?text={{ urlencode($siteSettings['general']['wa_message'] ?? 'Halo Wonderful Toba, saya ingin bertanya tentang paket wisata...') }}" 
           target="_blank"
           class="w-12 h-12 bg-emerald-500 text-white rounded-2xl shadow-2xl shadow-emerald-500/20 flex items-center justify-center hover:bg-emerald-600 transition-all hover:scale-110 group relative">
            <i class="fab fa-whatsapp text-xl"></i>
            <span class="absolute right-full mr-4 px-3 py-1.5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-lg opacity-0 group-hover:opacity-100 transition-all whitespace-nowrap pointer-events-none">
                Konsultasi WA
            </span>
        </a>

    </div>

    <!-- Notifikasi konten diperbarui (pengganti reload paksa) -->
    <div id="cms-update-toast"
         class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[120] translate-y-24 opacity-0 transition-all duration-500 pointer-events-none">
        <button type="button" onclick="window.location.reload()"
                class="pointer-events-auto flex items-center gap-3 bg-slate-900 text-white pl-5 pr-3 py-3 rounded-2xl shadow-2xl border border-white/10 hover:bg-slate-800 transition-colors">
            <i class="fas fa-arrows-rotate text-toba-accent" aria-hidden="true"></i>
            <span class="text-sm font-bold">Konten diperbarui</span>
            <span class="text-[11px] font-black uppercase tracking-widest bg-toba-green px-3 py-1.5 rounded-xl">Muat ulang</span>
        </button>
    </div>

    <!-- CMS Realtime Sync (No-Supabase Version) -->
    <script>
        (function() {
            // Jangan ganggu pengunjung dengan reload paksa: cukup tampilkan notifikasi.
            let currentVersion = null;
            const checkInterval = 30000; // 30 detik — hemat kuota/baterai
            let timer = null;
            let notified = false;

            function showToast() {
                const t = document.getElementById('cms-update-toast');
                if (!t) return;
                t.classList.remove('translate-y-24', 'opacity-0');
            }

            async function checkCmsVersion() {
                if (document.visibilityState !== 'visible' || notified) return;
                try {
                    const response = await fetch('{{ route('api.sync.version') }}');
                    const data = await response.json();
                    if (currentVersion === null) {
                        currentVersion = data.version;
                    } else if (data.version !== currentVersion) {
                        notified = true;
                        showToast();
                        stopPolling();
                    }
                } catch (e) {}
            }

            function startPolling() {
                if (!timer && !notified) timer = setInterval(checkCmsVersion, checkInterval);
            }
            function stopPolling() {
                if (timer) { clearInterval(timer); timer = null; }
            }

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
