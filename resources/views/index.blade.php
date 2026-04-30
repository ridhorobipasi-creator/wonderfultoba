<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wonderful Toba | Pilih Layanan Kami</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #000; }
        .central-logo {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="overflow-hidden">

    <main class="h-[100dvh] flex flex-col md:flex-row relative">
        
        <!-- Center Logo -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 pointer-events-none">
            <div class="central-logo p-10 rounded-[3rem] text-center shadow-2xl">
                <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center text-white font-black text-3xl mx-auto mb-4 shadow-xl">W</div>
                <h1 class="text-3xl font-black text-white tracking-tighter leading-tight">
                    @php
                        $nameParts = explode(' ', $content['brand_name'] ?? 'Wonderful Toba');
                    @endphp
                    @foreach($nameParts as $index => $part)
                        @if($index === 1)
                            <span class="text-emerald-500">{{ $part }}</span>
                        @else
                            {{ $part }}
                        @endif
                    @endforeach
                </h1>
                <p class="text-[9px] font-black tracking-[0.4em] text-slate-300 uppercase mt-2">{{ $content['brand_tagline'] ?? 'Sumatera Utara' }}</p>
            </div>
        </div>

        <!-- Left: Corporate Outbound -->
        <div class="relative w-full md:w-1/2 h-[50vh] md:h-full group overflow-hidden border-b-2 md:border-b-0 md:border-r border-white/5">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[3s] group-hover:scale-110" 
                 style="background-image: url('{{ $content['outbound_image_url'] ?? 'https://images.unsplash.com/photo-1544735049-717bc392183e' }}')"></div>
            <div class="absolute inset-0 bg-slate-950/60 group-hover:bg-slate-950/40 transition-colors"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end p-12 md:p-20 lg:p-24">
                <h2 class="text-5xl lg:text-7xl font-black text-white leading-none mb-6">
                    {!! nl2br(e($content['outbound_title'] ?? "Corporate\nOutbound.")) !!}
                </h2>
                <p class="text-slate-200 text-sm md:text-base font-bold max-w-sm mb-10 leading-relaxed">
                    {{ $content['outbound_subtitle'] ?? 'Solusi team building & gathering profesional untuk instansi Anda.' }}
                </p>
                <div class="flex items-center space-x-4">
                    <a href="/outbound" class="px-8 py-4 bg-emerald-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-900/20 transition hover:bg-white hover:text-emerald-700">
                        Jelajahi Outbound
                    </a>
                    <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white">
                        <i class="fas fa-arrow-right -rotate-45"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Tour & Travel -->
        <div class="relative w-full md:w-1/2 h-[50vh] md:h-full group overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[3s] group-hover:scale-110" 
                 style="background-image: url('{{ $content['tour_image_url'] ?? 'https://images.unsplash.com/photo-1568449039662-3582576da56b' }}')"></div>
            <div class="absolute inset-0 bg-slate-950/60 group-hover:bg-slate-950/40 transition-colors"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end items-end p-12 md:p-20 lg:p-24 text-right">
                <h2 class="text-5xl lg:text-7xl font-black text-white leading-none mb-6">
                    {!! nl2br(e($content['tour_title'] ?? "Tour &\nTravel.")) !!}
                </h2>
                <p class="text-slate-200 text-sm md:text-base font-bold max-w-sm mb-10 leading-relaxed">
                    {{ $content['tour_subtitle'] ?? 'Eksplorasi keindahan Danau Toba dengan paket liburan eksklusif kami.' }}
                </p>
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white">
                        <i class="fas fa-arrow-right -rotate-45"></i>
                    </div>
                    <a href="/tour" class="px-8 py-4 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-500/20 transition hover:bg-white hover:text-emerald-500">
                        Jelajahi Wisata
                    </a>
                </div>
            </div>
        </div>

    </main>

    <!-- Floating UI -->
    <div class="absolute bottom-10 right-10 z-50 flex flex-col space-y-4">
        <button class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center">
            <i class="fas fa-grid-2"></i>
        </button>
        <button class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center">
            <i class="fas fa-robot"></i>
        </button>
    </div>

</body>
</html>
