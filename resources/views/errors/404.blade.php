<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Halaman Tidak Ditemukan | Wonderful Toba</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center overflow-hidden relative">

    {{-- Decorative Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -left-48 w-[600px] h-[600px] bg-toba-green/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 -right-48 w-[600px] h-[600px] bg-emerald-900/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.03) 1px, transparent 0); background-size: 48px 48px;"></div>
    </div>

    <div class="relative z-10 text-center px-6 max-w-2xl mx-auto">
        
        {{-- Logo --}}
        <div class="mb-12 flex justify-center">
            @php
                $logoUrl = null;
                try {
                    $setting = \App\Models\Setting::where('key', 'general')->first();
                    $logoUrl = $setting?->value['logo_url'] ?? null;
                    if ($logoUrl && !\Illuminate\Support\Str::startsWith($logoUrl, ['http', '//', 'data:'])) {
                        $logoUrl = asset('storage/' . ltrim(str_replace('storage/', '', $logoUrl), '/'));
                    }
                } catch (\Exception $e) { $logoUrl = null; }
            @endphp

            @if(!empty($logoUrl))
                <img src="{{ $logoUrl }}" class="h-12 w-auto object-contain brightness-0 invert opacity-70" alt="Wonderful Toba">
            @else
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-toba-green rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-2xl shadow-toba-green/30">W</div>
                    <span class="text-white font-black text-xl uppercase tracking-widest opacity-80">Wonderful Toba</span>
                </div>
            @endif
        </div>

        {{-- 404 Number --}}
        <div class="relative mb-8">
            <p class="text-[12rem] md:text-[16rem] font-black text-white/5 leading-none select-none tracking-tighter">404</p>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 bg-toba-green/10 border border-toba-green/20 rounded-[2rem] flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-map-location-dot text-toba-green text-3xl"></i>
                </div>
            </div>
        </div>

        {{-- Message --}}
        <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter mb-4">
            Destinasi Tidak<br><span class="text-toba-green">Ditemukan</span>
        </h1>
        <p class="text-slate-400 font-medium text-lg max-w-md mx-auto mb-12 leading-relaxed">
            Sepertinya halaman yang Anda cari sudah berpindah atau tidak pernah ada. Mari kembali dan temukan petualangan Toba yang sesungguhnya.
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/" 
               class="px-10 py-5 bg-toba-green text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest hover:bg-emerald-400 transition-all duration-500 shadow-2xl shadow-toba-green/20 flex items-center gap-3">
                <i class="fas fa-home"></i>
                Kembali ke Beranda
            </a>
            <a href="/tour/packages" 
               class="px-10 py-5 bg-white/5 border border-white/10 text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest hover:bg-white/10 transition-all duration-500 flex items-center gap-3 backdrop-blur-sm">
                <i class="fas fa-compass"></i>
                Jelajahi Paket Wisata
            </a>
        </div>

        {{-- Quick Links --}}
        <div class="mt-16 flex flex-wrap items-center justify-center gap-6">
            <a href="/tour" class="text-slate-500 hover:text-toba-green text-xs font-bold uppercase tracking-widest transition-colors">Tour & Wisata</a>
            <span class="text-slate-700 text-xs">•</span>
            <a href="/outbound" class="text-slate-500 hover:text-toba-green text-xs font-bold uppercase tracking-widest transition-colors">Corporate Outbound</a>
            <span class="text-slate-700 text-xs">•</span>
            <a href="/tour/blog" class="text-slate-500 hover:text-toba-green text-xs font-bold uppercase tracking-widest transition-colors">Blog & Artikel</a>
            <span class="text-slate-700 text-xs">•</span>
            <a href="/about" class="text-slate-500 hover:text-toba-green text-xs font-bold uppercase tracking-widest transition-colors">Tentang Kami</a>
        </div>
    </div>

</body>
</html>
