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
   class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-slate-100 hover:border-slate-200 hover:shadow-lg transition-all duration-300">

    {{-- Gambar --}}
    <div class="relative h-48 overflow-hidden shrink-0">
        <img
            src="{{ $image }}"
            alt="{{ $package->name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
        >
        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if($package->isFeatured ?? false)
            <span class="inline-flex items-center gap-1 bg-toba-orange text-white text-[9px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full shadow-sm">
                🔥 Terpopuler
            </span>
            @endif
        </div>
        <div class="absolute top-3 right-3">
            <span class="bg-slate-900/60 backdrop-blur-sm text-white text-[9px] font-medium px-2 py-0.5 rounded-full">
                {{ $package->duration }}
            </span>
        </div>
    </div>

    {{-- Info --}}
    <div class="flex flex-col flex-grow p-4">
        {{-- Lokasi --}}
        <p class="flex items-center gap-1 text-slate-400 text-[10px] font-medium uppercase tracking-widest mb-1.5 truncate">
            <svg class="w-2.5 h-2.5 shrink-0 text-toba-green" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            {{ $displayLocation }}@if($isInternational) ✈️ @endif
        </p>

        {{-- Judul --}}
        <h3 class="text-slate-900 font-semibold text-[14px] leading-snug line-clamp-2 mb-3 flex-grow group-hover:text-toba-green transition-colors duration-200">
            {{ $package->name }}
        </h3>

        {{-- Harga + CTA --}}
        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
            <div>
                <p class="text-slate-400 text-[9px] uppercase tracking-widest mb-0.5">Mulai dari</p>
                <span class="text-slate-900 text-[15px] font-bold">
                    {{ \App\Helpers\CurrencyHelper::formatPrice($package->price) }}
                </span>
            </div>
            <span class="w-8 h-8 rounded-full bg-toba-green/10 text-toba-green flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all duration-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </span>
        </div>
    </div>
</a>
