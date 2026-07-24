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
   class="group relative block overflow-hidden rounded-2xl bg-slate-900 shadow-md hover:shadow-xl transition-all duration-500"
   style="aspect-ratio: 3/4;">

    {{-- Gambar --}}
    <img
        src="{{ $image }}"
        alt="{{ $package->name }}"
        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
    >

    {{-- Gradient Overlay — bawah lebih gelap agar teks jelas --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-black/10"></div>

    {{-- Badge Kiri Atas --}}
    @if($package->isFeatured ?? false)
    <div class="absolute top-3.5 left-3.5 z-10">
        <span class="inline-flex items-center gap-1 bg-toba-orange text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-md">
            🔥 {{ __('Terpopuler') }}
        </span>
    </div>
    @endif

    {{-- Badge Durasi Kanan Atas --}}
    <div class="absolute top-3.5 right-3.5 z-10">
        <span class="inline-flex items-center bg-white/15 backdrop-blur-sm text-white text-[10px] font-semibold uppercase tracking-wider border border-white/20 px-2.5 py-1 rounded-full">
            {{ $package->duration }}
        </span>
    </div>

    {{-- Konten bawah --}}
    <div class="absolute inset-x-0 bottom-0 z-10 p-4 pb-5">

        {{-- Lokasi --}}
        <div class="flex items-center gap-1 text-white/70 text-[10.5px] font-medium uppercase tracking-widest mb-1.5">
            <svg class="w-2.5 h-2.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            <span class="truncate">{{ $displayLocation }}</span>
            @if($isInternational) <span>✈️</span> @endif
        </div>

        {{-- Nama Paket --}}
        <h3 class="text-white font-semibold text-[15px] leading-snug line-clamp-2 mb-3 group-hover:text-toba-orange transition-colors duration-300">
            {{ $package->name }}
        </h3>

        {{-- Harga + CTA --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/50 text-[9.5px] font-semibold uppercase tracking-widest mb-0.5">{{ __('Mulai dari') }}</p>
                <span class="text-white text-[17px] font-bold leading-none">
                    {{ \App\Helpers\CurrencyHelper::formatPrice($package->price) }}
                </span>
            </div>
            <span class="w-9 h-9 bg-white/15 backdrop-blur-sm border border-white/25 text-white rounded-full flex items-center justify-center group-hover:bg-toba-orange group-hover:border-toba-orange transition-all duration-300">
                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </span>
        </div>
    </div>
</a>
