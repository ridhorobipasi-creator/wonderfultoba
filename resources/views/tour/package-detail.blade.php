@extends('layouts.app')

@push('head')
<style>
    .hide-arrows::-webkit-outer-spin-button,
    .hide-arrows::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .hide-arrows {
        -moz-appearance: textfield;
    }
</style>
@endpush

@php
    $heroImages = collect();
    if ($package->packageImages && $package->packageImages->count() > 0) {
        $heroImages = $package->packageImages;
    } elseif (is_array($package->images) && count($package->images) > 0) {
        $heroImages = collect(array_map(function($path) {
            return (object)['image_path' => $path];
        }, $package->images));
    } else {
        $heroImages = collect([(object)['image_path' => null]]);
    }
    
    // Normalize for AlpineJS thumbnails and ensure json_encode includes it BEFORE x-data evaluates
    $packageImagesArray = $heroImages->map(function($img) {
        $path = is_array($img) ? ($img['image_path'] ?? null) : ($img->image_path ?? null);
        $srcset = '';
        $blurHash = '';
        
        if (!empty($path)) {
            $clean = ltrim($path, '/');
            if (str_starts_with($clean, 'storage/')) {
                $clean = substr($clean, 8);
            }
            $media = \App\Models\Media::where('path', $clean)->orWhere('path', $path)->first();
            if ($media) {
                $dir = dirname($media->path);
                $base = basename($media->path);
                $mobilePath = ($dir === '.' || $dir === '/') ? 'mobile/' . $base : $dir . '/mobile/' . $base;
                $mediumPath = ($dir === '.' || $dir === '/') ? 'medium/' . $base : $dir . '/medium/' . $base;
                $largePath = ($dir === '.' || $dir === '/') ? 'large/' . $base : $dir . '/large/' . $base;

                $srcsetParts = [];
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($mobilePath)) {
                    $srcsetParts[] = \Illuminate\Support\Facades\Storage::disk('public')->url($mobilePath) . ' 480w';
                }
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($mediumPath)) {
                    $srcsetParts[] = \Illuminate\Support\Facades\Storage::disk('public')->url($mediumPath) . ' 800w';
                }
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($largePath)) {
                    $srcsetParts[] = \Illuminate\Support\Facades\Storage::disk('public')->url($largePath) . ' 1200w';
                }
                if (!empty($srcsetParts)) {
                    $srcset = implode(', ', $srcsetParts);
                }
                $blurHash = $media->blur_hash;
            }
        }
        
        return [
            'url' => imageUrl($path),
            'srcset' => $srcset,
            'blur_hash' => $blurHash
        ];
    })->toArray();
    $package->setAttribute('package_images', $packageImagesArray);

    $coverExif = null;
    $mainImgPath = null;
    if (is_array($package->images) && count($package->images) > 0) {
        $mainImgPath = $package->images[0];
    } elseif ($package->packageImages && $package->packageImages->count() > 0) {
        $mainImgPath = $package->packageImages->first()->image_path;
    }
    
    if ($mainImgPath) {
        $clean = ltrim($mainImgPath, '/');
        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, 8);
        }
        $media = \App\Models\Media::where('path', $clean)->orWhere('path', $mainImgPath)->first();
        if ($media && $media->exif_data) {
            $coverExif = $media->exif_data;
        }
    }
@endphp

@php
    $originSuffix = isset($originCity) && $originCity ? ' dari ' . $originCity : '';
@endphp
@section('title', ($package->translated_name ?? 'Paket Wisata') . $originSuffix . ' – Sujai Laketoba')
@section('description', (isset($originCity) && $originCity ? 'Paket ' . ($package->translated_name ?? 'Wisata') . ' keberangkatan dari ' . $originCity . '. ' : '') . ($package->translated_description ?? ''))

@section('og_image')
    @php
        $mainImg = count($packageImagesArray) > 0 ? $packageImagesArray[0]['url'] : null;
        echo $mainImg ?: asset('images/og-default.webp');
    @endphp
@endsection

@push('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@graph": [
    {
      "@@type": "Product",
      "name": "{{ $package->translated_name }}",
      "image": [
        "{{ count($packageImagesArray) > 0 ? $packageImagesArray[0]['url'] : asset('images/og-default.webp') }}"
      ],
      "description": "{{ Str::limit(strip_tags($package->translated_description), 160) }}",
      "sku": "PKG-{{ $package->id }}",
      "offers": {
        "@@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "IDR",
        "price": "{{ $package->price }}",
        "availability": "https://schema.org/InStock"
      }
    },
    {
      "@@type": "TouristTrip",
      "name": "{{ $package->translated_name }}",
      "description": "{{ Str::limit(strip_tags($package->translated_description), 160) }}",
      "provider": {
        "@@type": "TravelAgency",
        "name": "Sujai Laketoba",
        "url": "{{ url('/') }}"
      },
      "itinerary": {
        "@@type": "ItemList",
        "itemListElement": [
          @if(isset($package->itinerary) && is_array($package->itinerary))
            @foreach($package->itinerary as $index => $item)
            {
              "@@type": "ListItem",
              "position": {{ $index + 1 }},
              "item": {
                "@@type": "TouristAttraction",
                "name": "{{ $item['title'] ?? 'Day ' . ($index + 1) }}",
                "description": "{{ Str::limit(strip_tags($item['description'] ?? ''), 100) }}"
              }
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
          @endif
        ]
      }
    }
  ]
}
</script>
@endpush

@section('content')
<div 
    x-data="{ 
        activeImg: 0, 
        activeTab: 'itinerary',
        package: @js($package),
        package_images: @js($packageImagesArray),
        city: @js($city),
        contact: {
            whatsapp: '{{ $siteSettings['cms_tour']['contact_whatsapp'] ?? $siteSettings['general']['contact_whatsapp'] ?? '6282277848855' }}',
            email: '{{ $siteSettings['cms_tour']['contact_email'] ?? $siteSettings['general']['contact_email'] ?? 'hello@sujailaketoba.com' }}'
        },
        get waNumber() {
            return (this.contact.whatsapp || '6282277848855').replace(/[^0-9]/g, '');
        },
        get locationDisplay() {
            return this.city ? (this.city.type === 'international' ? (this.city.place || this.city.region || '') + ', ' + this.city.country : this.city.name) : (this.package.locationTag || 'Danau Toba');
        },
        showConcierge: false,
        totalChanged: false,

        // Booking form variables
        pax: {{ old('pax', 1) }},
        paxChildren: {{ old('paxChildren', 0) }},
        pkgTiers: @js($package->pricingDetails['tiers'] ?? []),
        services: (@js($package->pricingDetails['additional_services'] ?? [
            ['name' => 'Private Jet Charter', 'icon' => 'flight_takeoff', 'price' => 120000000],
            ['name' => 'Pemandu Antropologi', 'icon' => 'person_pin', 'price' => 5500000]
        ])).map(s => ({
            ...s,
            selected: s.name === 'Pemandu Antropologi'
        })),
        isSubmitting: false,
        notesUser: '{{ old('notesUser', '') }}',
        customerName: '{{ old('customerName', '') }}',
        customerEmail: '{{ old('customerEmail', '') }}',
        customerPhone: '{{ old('customerPhone', '') }}',
        startDate: '{{ old('startDate', '') }}',

        get currentUnitPrice() {
            if (this.pkgTiers && this.pkgTiers.length > 0) {
                // Find matching tier
                const matchingTier = this.pkgTiers.find(t => this.pax >= t.min_pax && this.pax <= t.max_pax);
                if (matchingTier) {
                    return matchingTier.price;
                }
                // Check if pax exceeds max tier, use the highest tier or default
                const maxTier = [...this.pkgTiers].sort((a, b) => b.max_pax - a.max_pax)[0];
                if (maxTier && this.pax > maxTier.max_pax) {
                    return maxTier.price; // Optional logic: use max tier price for beyond
                }
            }
            return this.package.price;
        },

        get priceDewasa() {
            return this.pax * this.currentUnitPrice;
        },
        get priceAnak() {
            return this.paxChildren * (this.package.childPrice ? this.package.childPrice : (this.package.price * 0.5));
        },
        get additionalServicesPrice() {
            return (this.services || [])
                .filter(s => s.selected)
                .reduce((total, s) => total + parseFloat(s.price || 0), 0);
        },
        get totalSebelumPajak() {
            return this.priceDewasa + this.priceAnak + this.additionalServicesPrice;
        },
        taxPercentage: {{ isset($taxPercentage) ? $taxPercentage : 11 }},
        get pajakLayanan() {
            return Math.round(this.totalSebelumPajak * (this.taxPercentage / 100));
        },
        get totalAkhir() {
            return this.totalSebelumPajak + this.pajakLayanan;
        },
        get serializedNotes() {
            let lines = [];
            if (this.paxChildren > 0) {
                lines.push('Anak-anak: ' + this.paxChildren + ' Orang');
            }
            if (this.services) {
                this.services.forEach(s => {
                    if (s.selected) {
                        lines.push(s.name + ': Ya');
                    }
                });
            }
            if (this.notesUser && this.notesUser.trim()) {
                lines.push('Catatan Tambahan: ' + this.notesUser.trim());
            }
            return lines.join(' | ');
        }
    }"
    x-init="$watch('totalAkhir', value => { totalChanged = true; setTimeout(() => totalChanged = false, 500); })"
    @scroll.window="showConcierge = window.scrollY > 300"
    class="bg-background text-on-background font-body-md min-h-screen pb-32 pt-20"
>
    <!-- AI Context & Screen Reader Only Data -->
    <section class="sr-only" id="ai-context" aria-hidden="true">
        <h2>AI Context: {{ $package->translated_name }}</h2>
        <p>{{ $package->translated_description }}</p>
        <p>Price: IDR {{ number_format($package->price, 0, ',', '.') }}</p>
        @if(!empty($package->pricingDetails['includes']))
        <h3>Includes</h3>
        <ul>
            @foreach($package->pricingDetails['includes'] as $inc)
                <li>{{ is_array($inc) ? ($inc['text'] ?? '') : $inc }}</li>
            @endforeach
        </ul>
        @endif
        @if(!empty($package->pricingDetails['excludes']))
        <h3>Excludes</h3>
        <ul>
            @foreach($package->pricingDetails['excludes'] as $exc)
                <li>{{ is_array($exc) ? ($exc['text'] ?? '') : $exc }}</li>
            @endforeach
        </ul>
        @endif
    </section>

    <!-- Gallery & Hero Section -->
    <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-10 grid grid-cols-1 md:grid-cols-12 gap-8">
        
        <!-- LEFT COLUMN WRAPPER -->
        <div class="contents md:block md:col-span-8">

        <!-- Hero/Gallery Part -->
        <div class="space-y-8 animate-in fade-in slide-in-from-left-8 duration-1000 order-1 mb-8 md:mb-12">
            <!-- Main Gallery -->
            <div class="relative h-[420px] md:h-[550px] overflow-hidden rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.12)] group">
                <img class="w-full h-full object-cover ken-burns group-hover:scale-110 transition-transform duration-[10s]"
                     fetchpriority="high" decoding="async"
                     :src="package_images[activeImg] ? package_images[activeImg].url : '{{ imageUrl($package->images[0] ?? null) }}'"
                     :srcset="package_images[activeImg] ? package_images[activeImg].srcset : ''"
                     sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                     :style="package_images[activeImg] && package_images[activeImg].blur_hash ? 'background-image: url(' + package_images[activeImg].blur_hash + '); background-size: cover; background-position: center; filter: blur(8px); transition: filter 0.5s ease-in-out, background-image 0.5s ease-in-out;' : ''"
                     onload="this.style.filter='none'; this.style.backgroundImage='none';"
                     onerror="this.src='{{ asset('images/home/tour.webp') }}'"/>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 md:bottom-10 md:left-10 bg-white/10 backdrop-blur-md border border-white/20 p-6 md:p-8 rounded-[1.5rem] max-w-[92%] md:max-w-[75%] shadow-glass">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="font-label-caps text-[10px] md:text-xs text-emerald-100 uppercase tracking-[0.2em]" x-text="locationDisplay"></span>
                        @if(isset($originCity) && $originCity)
                            <span class="ml-2 bg-emerald-500/80 text-white text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full">
                                Dari {{ $originCity }}
                            </span>
                        @endif
                    </div>
                    <h1 class="font-headline-lg text-2xl md:text-4xl text-white font-bold leading-tight drop-shadow-sm" x-text="package.translated_name + '{{ isset($originCity) && $originCity ? ' dari ' . $originCity : '' }}'"></h1>
                </div>
            </div>
            
            <!-- Thumbnails/Secondary Gallery -->
            <div x-show="package_images && package_images.length > 1" class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                <template x-for="(imgObj, i) in package_images" :key="i">
                    <div @click="activeImg = i" 
                         :class="activeImg === i ? 'ring-2 ring-emerald-500 ring-offset-2' : 'border border-slate-200/50'"
                         class="min-w-[140px] md:min-w-[180px] h-24 md:h-32 rounded-xl overflow-hidden flex-shrink-0 cursor-pointer hover:opacity-90 transition duration-300 shadow-sm">
                        <img class="w-full h-full object-cover" loading="lazy" decoding="async" :src="imgObj.url" :style="imgObj.blur_hash ? 'background-image: url(' + imgObj.blur_hash + '); background-size: cover; background-position: center; filter: blur(4px);' : ''" onload="this.style.filter='none'; this.style.backgroundImage='none';" onerror="this.src='{{ asset('images/home/tour.webp') }}'"/>
                    </div>
                </template>
            </div>
        </div>

        
    <style>
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>

    </style>

        <!-- Content Part -->
        <div class="space-y-16 animate-in fade-in slide-in-from-left-8 duration-1000 order-3 mt-8 md:mt-0">
            
            <!-- Section: Itinerary -->
            <div class="space-y-8" id="section-itinerary">
                <!-- Header Section Itinerary -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-[24px]">map</span>
                    </div>
                    <h2 class="font-headline-md text-2xl text-primary font-bold">{{ __('AGENDA PERJALANAN') }}</h2>
                </div>

                <!-- Ringkasan Pengalaman -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200">
                    <h2 class="font-headline-md text-headline-md text-primary mb-4 md:mb-6">{{ __('Ringkasan Pengalaman') }}</h2>
                    <div class="prose prose-slate max-w-none text-slate-600 font-body-md text-body-md leading-relaxed" x-html="package.translated_description"></div>

                    @if($coverExif)
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap gap-4 items-center justify-between text-xs text-slate-500 bg-slate-50 p-4 rounded-xl">
                        <div class="flex items-center gap-3">
                            <span class="text-lg">📸</span>
                            <div>
                                <p class="font-semibold text-slate-700">Metadata Foto Wisata</p>
                                <p class="text-slate-500">
                                    @if(!empty($coverExif['camera_brand']) || !empty($coverExif['camera_model']))
                                        {{ $coverExif['camera_brand'] ?? '' }} {{ $coverExif['camera_model'] ?? '' }}
                                    @endif
                                    @if(!empty($coverExif['aperture'])) • {{ $coverExif['aperture'] }} @endif
                                    @if(!empty($coverExif['iso'])) • ISO {{ $coverExif['iso'] }} @endif
                                    @if(!empty($coverExif['shutter_speed'])) • {{ $coverExif['shutter_speed'] }} @endif
                                </p>
                            </div>
                        </div>
                        @if(!empty($coverExif['gps']['lat']) && !empty($coverExif['gps']['lng']))
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $coverExif['gps']['lat'] }},{{ $coverExif['gps']['lng'] }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg font-semibold tracking-wide transition-colors">
                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                            {{ __('Lihat Lokasi Persis') }}
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- pSEO: Internal Linking Block — "Paket ini tersedia dari kota berikut" --}}
                @php
                    $originsString = $siteSettings['general']['seo_pseo_origins'] ?? 'Jakarta, Surabaya, Bandung, Bali, Batam, Palembang, Makassar, Semarang, Yogyakarta, Kuala Lumpur, Singapore, Penang, Pekanbaru, Padang, Malaysia';
                    $pSEOCities    = array_filter(array_map('trim', explode(',', $originsString)));
                @endphp
                @if(count($pSEOCities) > 0)
                <div class="bg-indigo-50/60 border border-indigo-100 rounded-2xl p-6 md:p-8">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4">
                        <span class="material-symbols-outlined text-[14px] align-middle mr-1">flight_takeoff</span>
                        Paket ini tersedia keberangkatan dari:
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($pSEOCities as $cityLink)
                            @php
                                $citySlug = \Illuminate\Support\Str::slug($cityLink);
                                $isActive = (isset($originCity) && strtolower($originCity) === strtolower(trim($cityLink)));
                            @endphp
                            <a href="{{ url('/tour/package/' . $package->slug . '-dari-' . $citySlug) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold transition
                                      {{ $isActive ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-indigo-700 border border-indigo-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600' }}">
                                @if($isActive)<span class="material-symbols-outlined text-[13px]">check_circle</span>@endif
                                {{ ucwords(trim($cityLink)) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif


                <div x-show="package.itinerary || package.translated_itinerary_text" class="space-y-6 py-8 border-t border-outline-variant">
                    <h2 class="font-headline-md text-headline-md text-primary">{{ __('Rencana Perjalanan') }}</h2>
                    
                    <div x-show="package.translated_itinerary_text" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-sm whitespace-pre-line text-slate-600 font-body-md text-body-md leading-relaxed" x-text="package.translated_itinerary_text"></div>
                    
                    <div x-show="!package.translated_itinerary_text && package.itinerary" class="space-y-8 relative">
                        <template x-for="(day, i) in package.itinerary" :key="i">
                            <div class="flex gap-5 group">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full border border-secondary flex items-center justify-center text-secondary font-semibold group-hover:bg-secondary group-hover:text-on-secondary transition-colors" x-text="String(day.day || (i + 1)).padStart(2, '0')"></div>
                                    <div class="w-px h-full bg-outline-variant my-2"></div>
                                </div>
                                <div class="pb-6 flex-1">
                                    <h3 class="font-headline-md text-body-lg font-semibold text-slate-900" x-text="day.title"></h3>
                                    <p class="font-body-md text-slate-600 leading-relaxed mt-2" x-text="day.description"></p>
                                    
                                    <template x-if="day.activities && day.activities.length > 0">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                            <template x-for="(act, j) in day.activities" :key="j">
                                                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-slate-200 transition">
                                                    <div class="w-1.5 h-1.5 bg-secondary rounded-full"></div>
                                                    <span class="text-xs font-medium text-slate-700" x-text="act"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Section: Pricing & Facilities -->
            <div class="space-y-8 pt-8 border-t border-slate-200" id="section-pricing">
                <!-- Header Section Pricing -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-[24px]">payments</span>
                    </div>
                    <h2 class="font-headline-md text-2xl text-primary font-bold">{{ __('BIAYA & FASILITAS') }}</h2>
                </div>
                <!-- Rincian Biaya -->
                <div x-show="package.pricingDetails && package.pricingDetails.length > 0" class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <span class="font-label-caps text-label-caps text-secondary block mb-1">{{ __('RINCIAN BIAYA') }}</span>
                            <h3 class="font-headline-md text-headline-md text-primary">{{ __('Investasi Perjalanan') }}</h3>
                        </div>
                        <div class="px-4 py-2 bg-slate-900 rounded-lg text-on-primary">
                            <span class="font-label-caps text-[10px] uppercase tracking-wider opacity-60 block">{{ __('Musim') }}</span>
                            <p class="text-xs font-bold font-body-md">2026/2027</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <template x-for="price in package.pricingDetails" :key="price.pax">
                            <div class="flex items-center justify-between p-5 bg-slate-50 rounded-xl border border-slate-200 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-secondary transition">
                                        <span class="material-symbols-outlined text-[20px]">group</span>
                                    </div>
                                    <div>
                                        <p class="font-label-caps text-[10px] text-on-surface-variant uppercase tracking-wider mb-0.5">{{ __('Peserta') }}</p>
                                        <p class="text-sm font-semibold text-slate-900 font-body-md" x-text="price.label || (price.pax + ' ' + (AppCurrency.locale === 'en' ? 'People' : 'Orang'))"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-label-caps text-[10px] text-on-surface-variant uppercase tracking-wider mb-0.5">{{ __('Per Orang') }}</p>
                                    <p class="text-base font-semibold text-primary font-body-md">
                                        <span x-text="AppCurrency.format(price.price || price.price_per_person || price.pricePerPerson)"></span>
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined text-[20px]">info</span>
                        </div>
                        <div>
                            <p class="font-label-caps text-xs font-semibold text-primary mb-1 uppercase tracking-wider">{{ __('Catatan Penting') }}</p>
                            <p class="text-xs text-slate-600 font-body-md font-normal leading-relaxed">{{ __('Harga bisa berubah sesuai musim dan ketersediaan. Untuk grup besar, kami bisa bantu buat penawaran khusus.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Inclusion / Exclusion -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900 mb-6 flex items-center gap-3 font-headline-md">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                            </div>
                            {{ __('Termasuk') }}
                        </h3>
                        <ul class="space-y-4">
                            <template x-for="item in package.package_includes" :key="item.id">
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 bg-primary rounded-full shrink-0 shadow-sm"></div>
                                    <span class="text-slate-700 font-medium text-xs leading-tight font-body-md" x-text="item.name"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900 mb-6 flex items-center gap-3 font-headline-md">
                            <div class="w-9 h-9 rounded-lg bg-red-100 text-error flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">cancel</span>
                            </div>
                            {{ __('Tidak Termasuk') }}
                        </h3>
                        <ul class="space-y-4">
                            <template x-for="item in package.package_excludes" :key="item.id">
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 bg-error rounded-full shrink-0"></div>
                                    <span class="text-slate-700 font-medium text-xs leading-tight font-body-md" x-text="item.name"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Section: Reviews -->
            <div class="space-y-8 pt-8 border-t border-slate-200" id="section-reviews">
                <!-- Header Section Reviews -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-[24px]">grade</span>
                    </div>
                    <h2 class="font-headline-md text-2xl text-primary font-bold">{{ __('ULASAN') }}</h2>
                </div>
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-200">
                    @php
                        $testimonials = $siteSettings['cms_tour']['testimonials'] ?? [];
                    @endphp

                    @if(!empty($testimonials))
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold font-headline-md text-primary mb-2">{{ __('Ulasan Pengunjung') }}</h3>
                            <p class="text-slate-600 font-body-md text-sm">{{ __('Cerita dari mereka yang sudah bepergian bersama kami.') }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($testimonials as $t)
                            <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 transition">
                                <div class="flex items-center gap-3 mb-4">
                                    @if(!empty($t['image']))
                                        <img src="{{ imageUrl($t['image']) }}" loading="lazy" decoding="async" class="w-10 h-10 rounded-lg object-cover bg-slate-200" alt="{{ $t['name'] }}" onerror="this.style.display='none'">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-base">
                                            {{ strtoupper(substr($t['name'] ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-slate-900 text-xs font-body-md">{{ $t['name'] }}</p>
                                        <p class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider font-label-caps">{{ __($t['location'] ?? '') }}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 font-body-md font-normal leading-relaxed italic">"{{ __($t['text'] ?? '') }}"</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="material-symbols-outlined text-[40px] text-primary/40">chat_bubble</span>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 mb-2 font-headline-md">{{ __('Bagikan Pengalaman Anda') }}</h4>
                            <p class="text-slate-600 font-body-md max-w-sm mx-auto mb-8 text-sm leading-relaxed">{{ __('Sudah pernah bepergian bersama kami? Ceritamu akan sangat membantu orang lain memilih.') }}</p>
                            <a :href="'https://wa.me/' + waNumber + '?text=' + encodeURIComponent('Halo Sujai Laketoba, saya ingin berbagi pengalaman wisata bersama kalian 😊')" target="_blank"
                               class="inline-flex items-center gap-2 bg-primary text-on-primary px-8 py-3.5 rounded-lg font-semibold text-xs uppercase tracking-wider hover:bg-primary-container transition shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">chat</span>
                                {{ __('Ceritakan Perjalananmu') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Travel Specialist & PDF CTA Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8">
                <!-- PDF Download -->
                <a href="{{ route('itinerary.download', $package->slug) }}" class="flex flex-col items-center justify-center p-8 bg-white border border-outline-variant rounded-xl shadow-lg hover:border-secondary hover:shadow-xl transition duration-300 group text-center h-full">
                    <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[24px]">download</span>
                    </div>
                    <h3 class="font-headline-md text-body-lg font-semibold text-primary mb-1">{{ __('Unduh Brosur PDF') }}</h3>
                    <p class="text-xs text-on-surface-variant font-body-md mb-4">{{ __('Dapatkan detail jadwal & informasi lengkap offline.') }}</p>
                    <span class="text-xs font-bold text-secondary font-label-caps tracking-wider underline">{{ __('DOWNLOAD SEKARANG') }}</span>
                </a>

                <!-- Contact Specialist Card -->
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-primary/5 rounded-full -mr-10 -mt-10 group-hover:scale-125 transition-transform duration-500"></div>
                    <div class="flex items-center gap-4 mb-4 relative z-10">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-primary overflow-hidden border-2 border-white shadow-md">
                            <img src="{{ imageUrl($siteSettings['cms_tour']['specialist_image_url'] ?? null, 'staff1') }}" loading="lazy" decoding="async" class="w-full h-full object-cover" onerror="this.src='{{ imageUrl('staff1') }}'">
                        </div>
                        <div>
                            <p class="font-label-caps text-[9px] font-bold text-on-surface-variant uppercase tracking-wider">{{ __($siteSettings['cms_tour']['specialist_title'] ?? 'Travel Specialist') }}</p>
                            <p class="text-sm font-bold text-primary font-body-md">{{ $siteSettings['cms_tour']['specialist_name'] ?? 'Sarah Anggraini' }}</p>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-600 font-body-md font-normal leading-relaxed mb-4 relative z-10">{{ __($siteSettings['cms_tour']['specialist_desc'] ?? 'Punya pertanyaan khusus? Kami siap bantu pilih paket yang paling pas.') }}</p>
                    <a :href="'https://wa.me/' + waNumber + '?text=' + encodeURIComponent('Halo ' + ('{{ $siteSettings['cms_tour']['specialist_name'] ?? 'Sarah' }}').split(' ')[0] + ', saya tertarik bertanya tentang paket: ' + package.translated_name)" 
                       target="_blank"
                       class="flex items-center justify-center gap-1.5 py-2.5 bg-primary/5 text-primary rounded-lg font-semibold text-[10px] uppercase tracking-wider hover:bg-primary hover:text-on-primary transition relative z-10 border border-primary/20">
                        <span class="material-symbols-outlined text-[16px]">chat</span>
                        {{ __('Tanya Sekarang') }}
                    </a>
                </div>
            </div>
        </div>

        </div> <!-- END LEFT COLUMN WRAPPER -->

        <!-- Booking Form Sidebar (Sticky) -->
        <div id="booking-form-sidebar" class="md:col-span-4 relative order-2 h-full">
            <div class="sticky top-28 bg-white p-6 md:p-8 rounded-2xl shadow-md border border-slate-200 space-y-6 max-h-[85vh] overflow-y-auto custom-scroll">
                @if(session('success'))
                    <div 
                        x-data="{ 
                            countdown: 2, 
                            redirectCancelled: false,
                            timer: null,
                            hasUrl: {{ session('whatsappUrl') ? 'true' : 'false' }},
                            init() {
                                if (this.hasUrl) {
                                    this.timer = setInterval(() => {
                                        if (this.countdown > 0 && !this.redirectCancelled) {
                                            this.countdown--;
                                        } else {
                                            clearInterval(this.timer);
                                            if (!this.redirectCancelled) {
                                                window.location.href = '{!! session('whatsappUrl') !!}';
                                            }
                                        }
                                    }, 1000);
                                }
                            },
                            cancelRedirect() {
                                this.redirectCancelled = true;
                                if (this.timer) clearInterval(this.timer);
                            }
                        }"
                        class="py-6 px-4 bg-primary/5 rounded-2xl border border-primary/10 text-center animate-in zoom-in duration-500"
                    >
                        <div class="w-14 h-14 bg-white text-secondary rounded-full flex items-center justify-center text-2xl shadow-sm border border-secondary/20 mx-auto mb-4">
                            <span class="material-symbols-outlined text-[32px]">check_circle</span>
                        </div>
                        <h4 class="text-xl font-semibold font-headline-md text-primary mb-2">{{ __('Reservasi Terkirim') }}</h4>
                        
                        @if(session('warning'))
                            <div class="p-3 bg-yellow-50 text-yellow-800 rounded-lg text-xs font-body-md mb-4 border border-yellow-200">
                                {{ session('warning') }}
                            </div>
                        @else
                            <p class="text-slate-600 font-body-md mb-6 text-sm leading-relaxed">{{ __('Pesanan Anda berhasil kami catat. Silakan lanjutkan konfirmasi via WhatsApp.') }}</p>
                        @endif
                        
                        <div class="inline-flex flex-col items-center px-6 py-4 bg-white rounded-lg border border-outline-variant mb-6 w-full">
                            <p class="font-label-caps text-[9px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Booking ID</p>
                            <p class="text-2xl font-semibold font-body-md text-primary tracking-wider">{{ session('bookingCode') }}</p>
                        </div>
                        
                        <!-- Redirection Countdown Status -->
                        <template x-if="hasUrl">
                            <div class="mb-6 p-3 bg-white/50 backdrop-blur-sm rounded-xl border border-slate-200/50 text-[11px] text-slate-600">
                                <template x-if="!redirectCancelled && countdown > 0">
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-3.5 w-3.5 text-secondary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span>Mengalihkan ke WhatsApp otomatis dalam <span class="font-bold text-secondary text-sm" x-text="countdown"></span> detik...</span>
                                    </div>
                                </template>
                                <template x-if="!redirectCancelled && countdown === 0">
                                    <span>Menghubungkan ke WhatsApp...</span>
                                </template>
                                <template x-if="redirectCancelled">
                                    <span class="text-slate-500 font-medium">Pengalihan otomatis dibatalkan. Silakan lakukan konfirmasi manual.</span>
                                </template>
                            </div>
                        </template>
                        
                        <!-- Action Buttons -->
                        <template x-if="hasUrl">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <template x-if="!redirectCancelled && countdown > 0">
                                <button 
                                    @click="cancelRedirect()"
                                    type="button"
                                    class="flex-1 py-3 border border-slate-300 text-slate-700 rounded-lg font-semibold text-[11px] uppercase tracking-wider hover:bg-slate-50 transition focus:outline-none"
                                >
                                    {{ __('Batal Alihkan') }}
                                </button>
                            </template>
                            <a 
                                href="{{ session('whatsappUrl') }}"
                                target="_blank"
                                class="flex-1 py-3 bg-secondary text-on-secondary rounded-lg font-semibold text-[11px] uppercase tracking-wider shadow-sm hover:bg-secondary/90 transition flex items-center justify-center gap-2 group"
                            >
                                <span class="material-symbols-outlined text-[18px]">chat</span>
                                {{ __('KONFIRMASI SEKARANG') }}
                            </a>
                        </div>
                        </template>

                        @if(session('bookingCode'))
                        <a href="{{ route('booking.track', session('bookingCode')) }}"
                           class="mt-4 inline-flex items-center justify-center gap-1.5 text-[11px] font-semibold text-primary hover:text-secondary transition-colors">
                            <span class="material-symbols-outlined text-[16px]">travel_explore</span>
                            {{ __('Lacak status pesanan Anda') }}
                        </a>
                        @endif
                    </div>
                @else
                        <div class="flex justify-between items-end border-b border-slate-200 pb-4">
                        <div>
                            <span class="font-label-caps text-[10px] text-slate-500 uppercase tracking-wider">{{ __('Mulai dari') }}</span>
                            <div class="font-headline-md text-headline-md text-primary" x-text="AppCurrency.format(package.price)"></div>
                        </div>
                        @php $__rating = siteRating(); @endphp
                        @if($__rating)
                        <div class="text-right">
                            <span class="text-secondary font-semibold font-body-md">★ {{ number_format($__rating['value'], 1) }}</span>
                            @if($__rating['count'])
                            <span class="text-slate-500 text-[11px] font-body-md block">
                                @if($__rating['url'])
                                    <a href="{{ $__rating['url'] }}" target="_blank" rel="noopener" class="hover:text-secondary transition-colors">{{ number_format($__rating['count']) }} {{ __('ulasan Google') }}</a>
                                @else
                                    {{ number_format($__rating['count']) }} {{ __('ulasan') }}
                                @endif
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>

                    @if(session('error'))
                        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs font-body-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form id="booking-form" action="{{ route('tour.booking.submit') }}" method="POST" class="space-y-5" @submit="isSubmitting = true">
                        @csrf
                        <input type="hidden" name="packageId" :value="package.id">
                        <input type="hidden" name="slug" :value="package.slug">
                        <input type="hidden" name="notes" :value="serializedNotes">
                        <input type="hidden" name="paxChildren" :value="paxChildren">
                        <template x-for="(service, idx) in services.filter(s => s.selected)" :key="idx">
                            <input type="hidden" name="selected_services[]" :value="service.name">
                        </template>
                        
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Nama lengkap') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="customerName" x-model="customerName" required placeholder="{{ __('Nama sesuai identitas') }}" 
                                class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition">
                            @error('customerName') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email & WhatsApp -->
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Email') }} <span class="text-red-500">*</span></label>
                                <input type="email" name="customerEmail" x-model="customerEmail" required placeholder="{{ __('email@contoh.com') }}" 
                                    class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition">
                                @error('customerEmail') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Nomor WhatsApp') }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="customerPhone" x-model="customerPhone" required placeholder="{{ __('0812-xxxx-xxxx') }}" 
                                    class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition">
                                @error('customerPhone') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Tanggal Keberangkatan -->
                        <div>
                            <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Pilih tanggal') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="date" name="startDate" x-model="startDate" required
                                    min="{{ now()->format('Y-m-d') }}"
                                    class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition uppercase">
                            </div>
                            @error('startDate') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Pax Dewasa & Anak -->
                        <!-- Input Pax Dewasa & Anak -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Tamu dewasa') }} <span class="text-red-500">*</span></label>
                                <div class="relative flex items-center">
                                    <button type="button" @click="if(pax > 1) pax--" class="absolute left-0 top-0 bottom-0 px-4 text-gray-500 hover:bg-gray-100 rounded-l-lg transition focus:outline-none"><span class="material-symbols-outlined text-[16px]">remove</span></button>
                                    <input type="number" name="pax" x-model.number="pax" required min="1" class="w-full text-center border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition hide-arrows">
                                    <button type="button" @click="pax++" class="absolute right-0 top-0 bottom-0 px-4 text-gray-500 hover:bg-gray-100 rounded-r-lg transition focus:outline-none"><span class="material-symbols-outlined text-[16px]">add</span></button>
                                </div>
                                <template x-if="pkgTiers && pkgTiers.length > 0">
                                    <p class="text-[10px] text-primary mt-1" x-text="`Rp ${AppCurrency.format(currentUnitPrice)} / pax`"></p>
                                </template>
                                @error('pax') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Anak-anak') }}</label>
                                <div class="relative flex items-center">
                                    <button type="button" @click="if(paxChildren > 0) paxChildren--" class="absolute left-0 top-0 bottom-0 px-4 text-gray-500 hover:bg-gray-100 rounded-l-lg transition focus:outline-none"><span class="material-symbols-outlined text-[16px]">remove</span></button>
                                    <input type="number" name="paxChildren" x-model.number="paxChildren" min="0" class="w-full text-center border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition hide-arrows">
                                    <button type="button" @click="paxChildren++" class="absolute right-0 top-0 bottom-0 px-4 text-gray-500 hover:bg-gray-100 rounded-r-lg transition focus:outline-none"><span class="material-symbols-outlined text-[16px]">add</span></button>
                                </div>
                            </div>
                        </div>

                        <!-- Layanan Tambahan -->
                        <div class="space-y-3" x-show="services && services.length > 0">
                            <label class="font-label-caps text-label-caps text-slate-700 mb-1 block uppercase tracking-wider">{{ __('Layanan tambahan') }}</label>
                            
                            <template x-for="(service, idx) in services" :key="idx">
                                <label class="flex items-center justify-between p-3 border border-outline-variant rounded-lg cursor-pointer hover:border-secondary transition" :class="service.selected ? 'border-secondary bg-secondary/5' : ''">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-secondary text-[22px]" x-text="service.icon || 'help'"></span>
                                        <div>
                                            <div class="font-body-md font-semibold text-slate-900 text-xs" x-text="service.name"></div>
                                            <div class="text-[10px] text-on-surface-variant font-body-md">+ <span x-text="AppCurrency.format(service.price)"></span></div>
                                        </div>
                                    </div>
                                    <input type="checkbox" x-model="service.selected" class="w-4 h-4 text-secondary border-outline-variant focus:ring-0 rounded"/>
                                </label>
                            </template>
                        </div>

                        <!-- Catatan User -->
                        <div>
                            <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Catatan tambahan') }} <span class="text-[9px] text-slate-500">({{ __('Opsional') }})</span></label>
                            <textarea x-model="notesUser" placeholder="{{ __('Permintaan khusus, hotel, alergi, penjemputan, dll.') }}" rows="2"
                                class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition resize-none"></textarea>
                        </div>

                        <!-- Real-time Pricing Summary Card -->
                        <div class="bg-slate-50 p-4 rounded-lg space-y-2 border border-slate-200">
                            <div class="flex justify-between text-xs text-slate-600 font-body-md">
                                <span>{{ __('Ekspedisi Dewasa') }} (<span x-text="pax"></span>x)</span>
                                <span x-text="AppCurrency.format(priceDewasa)"></span>
                            </div>
                            <div x-show="paxChildren > 0" class="flex justify-between text-xs text-slate-600 font-body-md">
                                <span>{{ __('Ekspedisi Anak-Anak') }} (<span x-text="paxChildren"></span>x)</span>
                                <span x-text="AppCurrency.format(priceAnak)"></span>
                            </div>
                            <template x-for="(service, idx) in services" :key="idx">
                                <div x-show="service.selected" class="flex justify-between text-xs text-slate-600 font-body-md">
                                    <span x-text="service.name"></span>
                                    <span x-text="AppCurrency.format(service.price)"></span>
                                </div>
                            </template>
                            <div class="flex justify-between text-xs text-slate-600 font-body-md">
                                <span>{{ __('Pajak & Layanan') }} (<span x-text="taxPercentage"></span>%)</span>
                                <span x-text="AppCurrency.format(pajakLayanan)"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-200 flex justify-between font-semibold text-primary text-base font-body-md transition duration-300 origin-right"
                                 :class="totalChanged ? 'scale-[1.03] text-secondary font-bold' : ''">
                                <span>Total Ringkasan</span>
                                <span x-text="AppCurrency.format(totalAkhir)"></span>
                            </div>
                        </div>

                        <!-- Honeypot Field -->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true">
                            <label for="website_url">Tinggalkan kolom ini kosong jika Anda manusia</label>
                            <input type="text" name="website_url" id="website_url" value="" autocomplete="off" tabindex="-1">
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            :disabled="isSubmitting"
                            class="w-full bg-primary text-on-primary py-4 rounded-lg font-semibold text-xs uppercase tracking-wider hover:bg-primary-container transition duration-300 shadow-sm flex items-center justify-center gap-2"
                        >
                            <span x-show="!isSubmitting" class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                                {{ __('Pesan Sekarang') }}
                            </span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                {{ __('Mengirim...') }}
                            </span>
                        </button>
                        <p class="text-center text-[11px] text-slate-500 font-body-md">{{ __('Konfirmasi cepat tersedia untuk tanggal terpilih.') }}</p>
                        <p class="text-center text-[9px] text-slate-400 font-body-md mt-1 leading-normal">
                            * {{ __('Data Anda akan disimpan di sistem kami. Anda akan diarahkan ke WhatsApp untuk melakukan konfirmasi cepat.') }}
                        </p>
                    </form>
                @endif
            </div>
        </div>


    </section>

    <!-- Floating Concierge Bar -->
    <div 
        x-show="showConcierge" 
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-12 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-12 scale-95"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] md:w-auto glass-card border border-slate-200 rounded-2xl px-5 md:px-8 py-3 z-50 hidden md:flex items-center justify-between gap-4 md:gap-12 shadow-lg transition duration-500 transform"
        style="display: none;"
    >
        <div class="hidden md:flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">support_agent</span>
            <span class="text-on-surface text-body-md font-semibold font-body-md">{{ __('Butuh bantuan? Hubungi kami.') }}</span>
        </div>
        <div class="flex items-center gap-4 w-full md:w-auto justify-between">
            <a 
                :href="'https://wa.me/' + waNumber + '?text=' + encodeURIComponent('Halo Sujai Laketoba, saya ingin bertanya tentang paket: *' + package.name + '*') "
                target="_blank"
                class="bg-white text-secondary border border-slate-200 px-4 md:px-6 py-2 rounded-full font-semibold text-xs hover:bg-slate-50 transition-colors"
            >
                Chat
            </a>
            <button 
                @click="document.getElementById('booking-form-sidebar').scrollIntoView({ behavior: 'smooth' })"
                class="bg-primary text-on-primary px-6 md:px-8 py-2 rounded-full font-semibold text-xs hover:bg-primary-container transition-colors"
            >
                Pesan
            </button>
        </div>
    </div>
</div>

@push('scripts')
@if($errors->any() || session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const form = document.getElementById('booking-form');
            if (form) {
                form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 500);
    });
</script>
@endif
@endpush

<style>
    .glass-card {
        background: rgba(252, 249, 248, 0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .ken-burns {
        animation: kenburns 25s infinite alternate ease-in-out;
    }
    @keyframes kenburns {
        0% { transform: scale(1); }
        100% { transform: scale(1.08); }
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
