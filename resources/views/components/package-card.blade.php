@props(['package', 'locationName' => 'Sumatera Utara', 'locationData' => null])

@php
    $displayLocation = $locationData 
        ? ($locationData->type === 'international' 
            ? ($locationData->place ?: $locationData->region) . ', ' . $locationData->country
            : $locationData->name)
        : $locationName;
    
    $isInternational = $locationData && $locationData->type === 'international';
    $rawImage = $package->packageImages->first()?->image_path 
        ?? ((isset($package->images) && count($package->images) > 0) ? $package->images[0] : (isset($package->image) ? $package->image : 'tour'));
    $image = imageUrl($rawImage);
@endphp

<div class="card-flat overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col h-full rounded-2xl">

    {{-- Gambar --}}
    <div class="relative h-52 overflow-hidden shrink-0">
        <img
            src="{{ $image }}"
            alt="{{ $package->name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
        >

        {{-- Badge Popular --}}
        @if($package->isFeatured ?? false)
        <div class="absolute top-3 left-3">
            <span class="bg-toba-orange text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow">
                🔥 {{ __('Terpopuler') }}
            </span>
        </div>
        @endif

        {{-- Badge Durasi --}}
        <div class="absolute top-3 right-3">
            <span class="bg-slate-900/75 backdrop-blur-sm text-white text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full">
                {{ $package->duration }}
            </span>
        </div>

        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
    </div>

    {{-- Konten --}}
    <div class="p-5 flex flex-col flex-grow">
        {{-- Lokasi --}}
        <div class="flex items-center text-slate-400 text-[11px] font-medium uppercase tracking-widest mb-2 gap-1.5">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span class="truncate">{{ $displayLocation }}</span>
            @if($isInternational) <span>✈️</span> @endif
        </div>

        {{-- Nama Paket --}}
        <h3 class="text-[15px] font-semibold text-slate-900 mb-2 line-clamp-2 leading-snug group-hover:text-toba-green transition-colors">
            {{ $package->name }}
        </h3>

        {{-- Deskripsi --}}
        <p class="text-slate-500 text-[13px] leading-relaxed line-clamp-2 mb-4 flex-grow">
            {{ $package->description }}
        </p>

        {{-- Harga + CTA --}}
        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
            <div>
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-widest mb-0.5">{{ __('Mulai dari') }}</p>
                <span class="text-lg font-bold text-slate-900">
                    {{ \App\Helpers\CurrencyHelper::formatPrice($package->price) }}
                </span>
            </div>
            <a href="/tour/package/{{ $package->slug }}"
               class="w-10 h-10 bg-slate-900 hover:bg-toba-green text-white rounded-xl flex items-center justify-center transition-colors duration-300 group/btn">
                <svg class="w-4 h-4 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</div>
