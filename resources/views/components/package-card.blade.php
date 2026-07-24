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

<a href="/tour/package/{{ $package->slug }}"
   class="group relative block overflow-hidden rounded-xl shadow-sm hover:shadow-md transition-all duration-400 bg-slate-900"
   style="height: 280px;">

    {{-- Gambar --}}
    <img
        src="{{ $image }}"
        alt="{{ $package->name }}"
        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
    >

    {{-- Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>

    {{-- Badge Popular --}}
    @if($package->isFeatured ?? false)
    <div class="absolute top-3 left-3 z-10">
        <span class="inline-flex items-center gap-1 bg-toba-orange text-white text-[9px] font-bold uppercase tracking-wider px-2 py-1 rounded-full">
            🔥 {{ __('Terpopuler') }}
        </span>
    </div>
    @endif

    {{-- Badge Durasi --}}
    <div class="absolute top-3 right-3 z-10">
        <span class="inline-flex items-center bg-black/40 backdrop-blur-sm text-white text-[9px] font-medium px-2 py-1 rounded-full border border-white/15">
            {{ $package->duration }}
        </span>
    </div>

    {{-- Konten Bawah --}}
    <div class="absolute inset-x-0 bottom-0 z-10 p-4">

        {{-- Lokasi --}}
        <p class="text-white/60 text-[10px] font-medium uppercase tracking-widest mb-1 truncate">
            <svg class="inline w-2.5 h-2.5 mr-0.5 -mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            {{ $displayLocation }}@if($isInternational) ✈️ @endif
        </p>

        {{-- Nama Paket --}}
        <h3 class="text-white font-semibold text-[14px] leading-snug line-clamp-2 mb-3">
            {{ $package->name }}
        </h3>

        {{-- Harga + Arrow --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/50 text-[9px] uppercase tracking-widest mb-0.5">{{ __('Mulai dari') }}</p>
                <span class="text-white text-[15px] font-bold">
                    {{ \App\Helpers\CurrencyHelper::formatPrice($package->price) }}
                </span>
            </div>
            <span class="w-8 h-8 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center group-hover:bg-toba-orange group-hover:border-toba-orange transition-all duration-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </span>
        </div>
    </div>
</a>
