<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — {{ __('Halaman Tidak Ditemukan') }} | Sujai Laketoba</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    
    <style>
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow-effect {
            box-shadow: 0 0 80px -10px rgba(16, 185, 129, 0.25);
        }
    </style>
</head>
<body class="bg-[#0b0f19] min-h-screen flex items-center justify-center overflow-hidden relative">

    <!-- Premium Animated Background Particles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-toba-green/10 blur-[150px] animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-emerald-500/5 blur-[120px] animate-pulse" style="animation-duration: 12s;"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.015) 1px, transparent 0); background-size: 32px 32px;"></div>
    </div>

    <div class="relative z-10 text-center px-6 max-w-2xl mx-auto py-12">
        
        <!-- Logo -->
        <div class="mb-8 flex justify-center scale-95 md:scale-100">
            @php
                $logoUrl = imageUrl($siteSettings['general']['logo_dark_url'] ?? null, asset('assets/img/logo-white.png'));
            @endphp
            @if(!empty($logoUrl))
                <img src="{{ $logoUrl }}" class="h-14 w-auto object-contain brightness-0 invert opacity-90 transition hover:opacity-100" alt="Sujai Laketoba">
            @else
                <div class="flex items-center gap-3">
                    <span class="text-white font-outfit font-black text-2xl uppercase tracking-widest">Sujai Laketoba</span>
                </div>
            @endif
        </div>

        <!-- 404 Glass Card -->
        <div class="glass-panel glow-effect rounded-[3.5rem] p-8 md:p-14 mb-8 text-center relative overflow-hidden max-w-lg mx-auto">
            <!-- 404 Large Text -->
            <div class="relative mb-6 select-none">
                <span class="text-[8rem] md:text-[10rem] font-outfit font-extrabold text-transparent bg-clip-text bg-gradient-to-b from-white/20 to-white/0 leading-none tracking-tighter">404</span>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-20 h-20 bg-toba-green/20 border border-toba-green/30 rounded-[1.8rem] flex items-center justify-center backdrop-blur-md shadow-inner">
                        <i class="fas fa-map-location-dot text-toba-green text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Message -->
            <h1 class="text-2xl md:text-3xl font-outfit font-extrabold text-white tracking-tight leading-snug mb-3">
                {{ __('Halaman Tidak Ditemukan') }}
            </h1>
            <p class="text-slate-400 font-normal text-xs md:text-sm max-w-sm mx-auto mb-8 leading-relaxed">
                {{ __('Destinasi yang Anda cari belum ditemukan atau sudah dialihkan. Temukan petualangan menarik lainnya bersama Sujai Laketoba.') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
                <a href="/" 
                   class="w-full sm:w-auto px-8 py-4 bg-toba-green text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-500 hover:scale-[1.02] transition duration-300 shadow-lg shadow-toba-green/10 flex items-center justify-center gap-2.5">
                    <i class="fas fa-home text-xs"></i>
                    {{ __('Kembali ke Beranda') }}
                </a>
                <a href="/tour/packages" 
                   class="w-full sm:w-auto px-8 py-4 bg-white/5 border border-white/10 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/10 hover:scale-[1.02] transition duration-300 flex items-center justify-center gap-2.5">
                    <i class="fas fa-compass text-xs"></i>
                    {{ __('Jelajahi Paket') }}
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="flex flex-wrap items-center justify-center gap-5 text-[10px] font-bold uppercase tracking-widest text-slate-500">
            <a href="/tour" class="hover:text-toba-green transition-colors">{{ __('Tour & Wisata') }}</a>
            <span class="text-slate-700/50">•</span>
            <a href="/tour/blog" class="hover:text-toba-green transition-colors">{{ __('Blog & Info') }}</a>
            <span class="text-slate-700/50">•</span>
            <a href="/about" class="hover:text-toba-green transition-colors">{{ __('Tentang Kami') }}</a>
        </div>
    </div>

</body>
</html>
