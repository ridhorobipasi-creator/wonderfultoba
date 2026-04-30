@props(['package', 'locationName' => 'Sumatera Utara', 'locationData' => null])

@php
    $displayLocation = $locationData 
        ? ($locationData->type === 'international' 
            ? ($locationData->place ?: $locationData->region) . ', ' . $locationData->country
            : $locationData->name)
        : $locationName;
    
    $isInternational = $locationData && $locationData->type === 'international';
    $image = (isset($package->images) && count($package->images) > 0) ? $package->images[0] : (isset($package->image) ? $package->image : 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800');
@endphp

<div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 group flex flex-col h-full">
    <div class="relative h-72 overflow-hidden shrink-0">
        <img
            src="{{ $image }}"
            alt="{{ $package->name }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
        >
        
        <!-- Badges -->
        <div class="absolute top-5 left-5 flex flex-col space-y-2">
            <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
                <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span class="font-black text-slate-800 text-[10px] uppercase tracking-wider">4.8</span>
            </div>
            <div class="bg-blue-600 text-white px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg">
                {{ $package->duration }}
            </div>
        </div>

        <!-- Wishlist -->
        <button
            class="absolute top-5 right-5 w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-rose-500 transition-all"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    </div>

    <div class="p-8 flex flex-col flex-grow">
        <div class="flex items-center text-blue-600 text-[10px] font-black uppercase tracking-[0.2em] mb-3">
            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>{{ $displayLocation }}</span>
            @if($isInternational) <span class="ml-1.5">✈️</span> @endif
        </div>
        
        <h3 class="text-2xl font-black text-slate-900 mb-4 line-clamp-1 group-hover:text-blue-600 transition-colors tracking-tight">
            {{ $package->name }}
        </h3>
        
        <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2 font-medium">
            {{ $package->description }}
        </p>
        
        <div class="flex items-center justify-between pt-6 border-t border-slate-50 mt-auto">
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Mulai Dari</p>
                <div class="flex items-baseline space-x-1">
                    <span class="text-xs font-bold text-slate-400">IDR</span>
                    <span class="text-2xl font-black text-slate-900">
                        {{ number_format($package->price, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <a
                href="/tour/package/{{ $package->slug }}"
                class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 transition-all shadow-xl shadow-slate-200 group/btn"
            >
                <svg class="w-6 h-6 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</div>
