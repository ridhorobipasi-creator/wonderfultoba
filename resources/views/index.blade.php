<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wonderful Toba | Premium Tour & Corporate Outbound</title>
    <meta name="description" content="Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-slate-950 text-white overflow-hidden min-h-screen" x-data="{ isLoaded: false }" x-init="setTimeout(() => isLoaded = true, 100)">

    <main class="h-[100dvh] flex flex-col md:flex-row relative">
        
        <!-- Central Logo & Branding -->
        <div 
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 text-center transition-all duration-1000"
            :class="isLoaded ? 'opacity-100 scale-100' : 'opacity-0 scale-90'"
        >
           <div class="bg-slate-900/40 backdrop-blur-xl p-8 rounded-full border border-white/10 shadow-2xl shadow-toba-green/20">
             <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center font-black text-3xl mx-auto shadow-inner mb-4 text-white">W</div>
             <h1 class="text-3xl font-black tracking-tight whitespace-nowrap mb-1">
                @php
                    $nameParts = explode(' ', $content['brand']['name']);
                @endphp
                @foreach($nameParts as $index => $part)
                    @if($index === 1)
                        <span class="text-toba-green"> {{ $part }}</span>
                    @else
                        {{ $part }}
                    @endif
                @endforeach
             </h1>
             <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-300">{{ $content['brand']['tagline'] }}</p>
           </div>
        </div>

        <!-- Left Side - Corporate Outbound -->
        <a 
            href="/outbound" 
            class="relative w-full md:w-1/2 h-[50vh] md:h-full group cursor-pointer block overflow-hidden border-b-2 gap-4 md:border-b-0 md:border-r-2 border-slate-900"
        >
          <div 
            class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] ease-out group-hover:scale-110" 
            style="background-image: url('{{ $content['outbound']['backgroundImage'] }}')"
          ></div>
          <div class="absolute inset-0 bg-slate-900/60 group-hover:bg-slate-900/40 transition-colors duration-500"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent"></div>
          
          <div class="absolute inset-0 flex flex-col justify-end p-10 md:p-16 lg:p-24 text-left transition-transform duration-500 group-hover:-translate-y-4">
            <div class="flex items-center gap-3 mb-4 text-toba-green opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 delay-100">
               <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
               <span class="font-bold text-xs uppercase tracking-widest text-white">Event Organizer</span>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-7xl font-black mb-4 tracking-tighter leading-[1.1] whitespace-pre-line">
                @php
                    $titleLines = explode("\n", $content['outbound']['title']);
                @endphp
                @foreach($titleLines as $index => $line)
                    @if($index > 0)
                        <br><span class="text-toba-green">{{ $line }}</span>
                    @else
                        {{ $line }}
                    @endif
                @endforeach
            </h2>
            <p class="text-slate-300 font-medium max-w-sm mb-8 text-sm md:text-base leading-relaxed hidden md:block">
              {{ $content['outbound']['subtitle'] }}
            </p>
            <div class="flex items-center gap-4">
              <div class="bg-toba-green text-white px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all shadow-xl group-hover:shadow-toba-green/30 group-hover:px-10">
                 {{ $content['outbound']['ctaText'] }}
              </div>
              <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-slate-900 transition-all">
                <svg class="w-5 h-5 -rotate-45 group-hover:rotate-0 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </div>
            </div>
          </div>
        </a>

        <!-- Right Side - Tour & Travel -->
        <a 
            href="/tour" 
            class="relative w-full md:w-1/2 h-[50vh] md:h-full group cursor-pointer block overflow-hidden"
        >
          <div 
            class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] ease-out group-hover:scale-110" 
            style="background-image: url('{{ $content['tour']['backgroundImage'] }}')"
          ></div>
          <div class="absolute inset-0 bg-slate-900/60 group-hover:bg-slate-900/40 transition-colors duration-500"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent"></div>
          
          <div class="absolute inset-0 flex flex-col justify-end p-10 md:p-16 lg:p-24 text-left md:text-right transition-transform duration-500 group-hover:-translate-y-4">
            <div class="flex items-center md:justify-end gap-3 mb-4 text-toba-accent opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 delay-100">
               <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
               <span class="font-bold text-xs uppercase tracking-widest text-white">Travel Agency</span>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-7xl font-black mb-4 tracking-tighter leading-[1.1] whitespace-pre-line">
                @php
                    $titleLines = explode("\n", $content['tour']['title']);
                @endphp
                @foreach($titleLines as $index => $line)
                    @if($index > 0)
                        <br><span class="text-toba-accent">{{ $line }}</span>
                    @else
                        {{ $line }}
                    @endif
                @endforeach
            </h2>
            <p class="text-slate-300 font-medium max-w-sm mb-8 text-sm md:text-base leading-relaxed md:ml-auto hidden md:block">
              {{ $content['tour']['subtitle'] }}
            </p>
            <div class="flex items-center md:justify-end gap-4 flex-row-reverse md:flex-row">
              <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-slate-900 transition-all">
                <svg class="w-5 h-5 -rotate-45 group-hover:rotate-0 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </div>
              <div class="bg-toba-accent text-white px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all shadow-xl group-hover:shadow-toba-accent/30 group-hover:px-10">
                 {{ $content['tour']['ctaText'] }}
              </div>
            </div>
          </div>
        </a>
        
    </main>

</body>
</html>
