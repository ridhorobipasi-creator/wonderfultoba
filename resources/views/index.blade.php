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
            backdrop-filter: blur(40px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .split-overlay {
            background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.2) 60%, rgba(15, 23, 42, 0) 100%);
        }
        .bg-zoom {
            animation: zoom-slow 20s infinite alternate;
        }
        @keyframes zoom-slow {
            from { transform: scale(1); }
            to { transform: scale(1.15); }
        }
        .btn-premium {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-premium:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="overflow-hidden">

    <main class="h-[100dvh] flex flex-col md:flex-row relative">
        
        
        <!-- Left: Corporate Outbound -->
        <div class="relative w-full md:w-1/2 h-[50dvh] md:h-full group overflow-hidden border-b md:border-b-0 md:border-r border-white/10">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[5s] group-hover:scale-110 bg-zoom" 
                 style="background-image: url('{{ !empty($content['outbound_image_url']) ? $content['outbound_image_url'] : asset('images/home/outbound.png') }}')"></div>
            <div class="absolute inset-0 split-overlay transition-opacity duration-500 group-hover:opacity-80"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-20 lg:p-24 z-10">
                <div class="overflow-hidden mb-4">
                    <span class="inline-block px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase tracking-widest rounded-full mb-3 md:mb-4">B2B & Corporate</span>
                    <h2 class="text-4xl md:text-6xl lg:text-8xl font-black text-white leading-none">
                        {!! nl2br(e($content['outbound_title'] ?? "Corporate\nOutbound.")) !!}
                    </h2>
                </div>
                <p class="hidden sm:block text-slate-300 text-sm md:text-base font-medium max-w-sm mb-10 leading-relaxed">
                    {{ $content['outbound_subtitle'] ?? 'Solusi team building & gathering profesional untuk instansi Anda.' }}
                </p>
                <div class="flex items-center space-x-4 md:space-x-6">
                    <a href="/outbound" class="btn-premium flex-1 sm:flex-none text-center px-6 md:px-10 py-4 md:py-5 bg-emerald-600 text-white rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-2xl shadow-emerald-950/40 border border-emerald-500/20">
                        Jelajahi Outbound
                    </a>
                    <div class="group/btn relative w-12 h-12 md:w-14 md:h-14 rounded-full border border-white/20 flex items-center justify-center text-white transition-all hover:bg-white hover:text-emerald-900 overflow-hidden cursor-pointer shrink-0">
                        <i class="fas fa-arrow-right -rotate-45 relative z-10"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Tour & Travel -->
        <div class="relative w-full md:w-1/2 h-[50dvh] md:h-full group overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[5s] group-hover:scale-110 bg-zoom" 
                 style="background-image: url('{{ !empty($content['tour_image_url']) ? $content['tour_image_url'] : asset('images/home/tour.png') }}')"></div>
            <div class="absolute inset-0 split-overlay transition-opacity duration-500 group-hover:opacity-80"></div>
            
            <div class="absolute inset-0 flex flex-col justify-end items-end p-8 md:p-20 lg:p-24 text-right z-10">
                <div class="overflow-hidden mb-4">
                    <span class="inline-block px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase tracking-widest rounded-full mb-3 md:mb-4">Premium Leisure</span>
                    <h2 class="text-4xl md:text-6xl lg:text-8xl font-black text-white leading-none">
                        {!! nl2br(e($content['tour_title'] ?? "Tour &\nTravel.")) !!}
                    </h2>
                </div>
                <p class="hidden sm:block text-slate-300 text-sm md:text-base font-medium max-w-sm mb-10 leading-relaxed">
                    {{ $content['tour_subtitle'] ?? 'Eksplorasi keindahan Danau Toba dengan paket liburan eksklusif kami.' }}
                </p>
                <div class="flex items-center space-x-4 md:space-x-6">
                    <div class="group/btn relative w-12 h-12 md:w-14 md:h-14 rounded-full border border-white/20 flex items-center justify-center text-white transition-all hover:bg-white hover:text-emerald-900 overflow-hidden cursor-pointer shrink-0">
                        <i class="fas fa-arrow-right -rotate-45 relative z-10"></i>
                    </div>
                    <a href="/tour" class="btn-premium flex-1 sm:flex-none text-center px-6 md:px-10 py-4 md:py-5 bg-emerald-500 text-white rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-2xl shadow-emerald-500/40 border border-emerald-400/20">
                        Jelajahi Wisata
                    </a>
                </div>
            </div>
        </div>

    </main>


</body>
</html>
