@extends('layouts.app')

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
        return [
            'url' => imageUrl($path)
        ];
    })->toArray();
    $package->setAttribute('package_images', $packageImagesArray);
@endphp

@section('title', ($package->name ?? 'Paket Wisata') . ' – Sujai Laketoba')
@section('description', $package->description ?? '')

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
  "@@type": "Product",
  "name": "{{ $package->name }}",
  "image": [
    "{{ count($packageImagesArray) > 0 ? $packageImagesArray[0]['url'] : asset('images/og-default.webp') }}"
  ],
  "description": "{{ Str::limit(strip_tags($package->description), 160) }}",
  "sku": "PKG-{{ $package->id }}",
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "IDR",
    "price": "{{ $package->price }}",
    "availability": "https://schema.org/InStock"
  }
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
            whatsapp: '{{ $siteSettings['cms_tour']['contact_wa'] ?? $siteSettings['general']['whatsapp'] ?? '6281323888207' }}',
            email: '{{ $siteSettings['cms_tour']['contact_email'] ?? $siteSettings['general']['contact_email'] ?? 'hello@sujailaketoba.com' }}'
        },
        get waNumber() {
            return (this.contact.whatsapp || '6281323888207').replace(/[^0-9]/g, '');
        },
        get locationDisplay() {
            return this.city ? (this.city.type === 'international' ? (this.city.place || this.city.region || '') + ', ' + this.city.country : this.city.name) : (this.package.locationTag || 'Danau Toba');
        },
        showConcierge: false,

        // Booking form variables
        pax: {{ old('pax', 2) }},
        paxChildren: {{ old('paxChildren', 0) }},
        privateJet: false,
        guide: true,
        isSubmitting: false,
        notesUser: '{{ old('notesUser', '') }}',
        customerName: '{{ old('customerName', '') }}',
        customerEmail: '{{ old('customerEmail', '') }}',
        customerPhone: '{{ old('customerPhone', '') }}',
        startDate: '{{ old('startDate', '') }}',

        get priceDewasa() {
            return this.pax * this.package.price;
        },
        get priceAnak() {
            return this.paxChildren * this.package.price * 0.5;
        },
        get pricePrivateJet() {
            return this.privateJet ? 120000000 : 0;
        },
        get priceGuide() {
            return this.guide ? 5500000 : 0;
        },
        get totalSebelumPajak() {
            return this.priceDewasa + this.priceAnak + this.pricePrivateJet + this.priceGuide;
        },
        get pajakLayanan() {
            return Math.round(this.totalSebelumPajak * 0.11);
        },
        get totalAkhir() {
            return this.totalSebelumPajak + this.pajakLayanan;
        },
        get serializedNotes() {
            let lines = [];
            if (this.paxChildren > 0) {
                lines.push('Anak-anak: ' + this.paxChildren + ' Orang');
            }
            if (this.privateJet) {
                lines.push('Private Jet: Ya');
            }
            if (this.guide) {
                lines.push('Pemandu Ahli Antropologi: Ya');
            }
            if (this.notesUser && this.notesUser.trim()) {
                lines.push('Catatan Tambahan: ' + this.notesUser.trim());
            }
            return lines.join(' | ');
        }
    }"
    @scroll.window="showConcierge = window.scrollY > 300"
    class="bg-background text-on-background font-body-md min-h-screen pb-32 pt-20"
>
    <!-- Gallery & Hero Section -->
    <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-10 grid grid-cols-12 gap-gutter">
        <div class="col-span-12 md:col-span-8 space-y-8 animate-in fade-in slide-in-from-left-8 duration-1000">
            <!-- Main Gallery -->
            <div class="relative h-[420px] md:h-[500px] overflow-hidden rounded-2xl shadow-lg">
                <img class="w-full h-full object-cover ken-burns" :src="package_images[activeImg] ? package_images[activeImg].url : '{{ asset('images/home/tour.webp') }}'" onerror="this.src='{{ asset('images/home/tour.webp') }}'"/>
                <div class="absolute bottom-5 left-5 glass-card p-5 rounded-2xl max-w-[92%] md:max-w-[70%]">
                    <span class="font-label-caps text-[10px] text-secondary block mb-1 uppercase tracking-wider" x-text="locationDisplay"></span>
                    <h1 class="font-headline-lg text-[22px] md:text-headline-lg text-primary leading-tight" x-text="package.name"></h1>
                </div>
            </div>
            
            <!-- Thumbnails/Secondary Gallery -->
            <div x-show="package_images && package_images.length > 1" class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                <template x-for="(imgObj, i) in package_images" :key="i">
                    <div @click="activeImg = i" 
                         :class="activeImg === i ? 'border-2 border-secondary' : 'border border-outline-variant'"
                         class="min-w-[200px] h-32 rounded-lg overflow-hidden flex-shrink-0 cursor-pointer hover:opacity-80 transition-all duration-300">
                        <img class="w-full h-full object-cover" :src="imgObj.url" onerror="this.src='{{ asset('images/home/tour.webp') }}'"/>
                    </div>
                </template>
            </div>

            <!-- Tabs Navigation -->
            <div class="border-b border-slate-200 overflow-x-auto no-scrollbar mb-8 pt-4">
                <div class="flex gap-6">
                    <button @click="activeTab = 'itinerary'" 
                        :class="activeTab === 'itinerary' ? 'text-secondary border-b-2 border-secondary pb-4 font-semibold' : 'text-on-surface-variant hover:text-on-surface pb-4'"
                        class="text-xs font-semibold font-label-caps uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">map</span>
                        {{ __('AGENDA PERJALANAN') }}
                    </button>
                    <button @click="activeTab = 'pricing'" 
                        :class="activeTab === 'pricing' ? 'text-secondary border-b-2 border-secondary pb-4 font-semibold' : 'text-on-surface-variant hover:text-on-surface pb-4'"
                        class="text-xs font-semibold font-label-caps uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        {{ __('BIAYA & FASILITAS') }}
                    </button>
                    <button @click="activeTab = 'reviews'" 
                        :class="activeTab === 'reviews' ? 'text-secondary border-b-2 border-secondary pb-4 font-semibold' : 'text-on-surface-variant hover:text-on-surface pb-4'"
                        class="text-xs font-semibold font-label-caps uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">grade</span>
                        {{ __('ULASAN') }}
                    </button>
                </div>
            </div>

            <!-- Tab Content: Itinerary -->
            <div x-show="activeTab === 'itinerary'" class="space-y-8">
                <!-- Ringkasan Pengalaman -->
                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200">
                    <h2 class="font-headline-md text-headline-md text-primary mb-4 md:mb-6">{{ __('Ringkasan Pengalaman') }}</h2>
                    <div class="prose prose-slate max-w-none text-slate-600 font-body-md text-body-md leading-relaxed" x-html="package.description"></div>
                </div>

                <!-- Timeline Rencana Perjalanan -->
                <div x-show="package.itinerary || package.itineraryText" class="space-y-6 py-8 border-t border-outline-variant">
                    <h2 class="font-headline-md text-headline-md text-primary">{{ __('Rencana Perjalanan') }}</h2>
                    
                    <div x-show="package.itineraryText" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-sm whitespace-pre-line text-slate-600 font-body-md text-body-md leading-relaxed" x-text="package.itineraryText"></div>
                    
                    <div x-show="!package.itineraryText && package.itinerary" class="space-y-8 relative">
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
                                                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-slate-200 transition-all">
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

            <!-- Tab Content: Pricing & Facilities -->
            <div x-show="activeTab === 'pricing'" class="space-y-8">
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
                                    <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-secondary transition-all">
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

            <!-- Tab Content: Reviews -->
            <div x-show="activeTab === 'reviews'">
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
                            <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 transition-all">
                                <div class="flex items-center gap-3 mb-4">
                                    @if(!empty($t['image']))
                                        <img src="{{ imageUrl($t['image']) }}" class="w-10 h-10 rounded-lg object-cover bg-slate-200" alt="{{ $t['name'] }}" onerror="this.style.display='none'">
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
                               class="inline-flex items-center gap-2 bg-primary text-on-primary px-8 py-3.5 rounded-lg font-semibold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm">
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
                <a href="{{ route('itinerary.download', $package->slug) }}" class="flex flex-col items-center justify-center p-8 bg-white border border-outline-variant rounded-xl shadow-lg hover:border-secondary hover:shadow-xl transition-all duration-300 group text-center h-full">
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
                            <img src="{{ imageUrl($siteSettings['cms_tour']['specialist_image_url'] ?? null) }}" class="w-full h-full object-cover" onerror="this.src='https://i.pravatar.cc/100?u=staff1'">
                        </div>
                        <div>
                            <p class="font-label-caps text-[9px] font-bold text-on-surface-variant uppercase tracking-wider">{{ __($siteSettings['cms_tour']['specialist_title'] ?? 'Travel Specialist') }}</p>
                            <p class="text-sm font-bold text-primary font-body-md">{{ $siteSettings['cms_tour']['specialist_name'] ?? 'Sarah Anggraini' }}</p>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-600 font-body-md font-normal leading-relaxed mb-4 relative z-10">{{ __($siteSettings['cms_tour']['specialist_desc'] ?? 'Punya pertanyaan khusus? Kami siap bantu pilih paket yang paling pas.') }}</p>
                    <a :href="'https://wa.me/' + waNumber + '?text=' + encodeURIComponent('Halo ' + ('{{ $siteSettings['cms_tour']['specialist_name'] ?? 'Sarah' }}').split(' ')[0] + ', saya tertarik bertanya tentang paket: ' + package.name)" 
                       target="_blank"
                       class="flex items-center justify-center gap-1.5 py-2.5 bg-primary/5 text-primary rounded-lg font-semibold text-[10px] uppercase tracking-wider hover:bg-primary hover:text-on-primary transition-all relative z-10 border border-primary/20">
                        <span class="material-symbols-outlined text-[16px]">chat</span>
                        {{ __('Tanya Sekarang') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Booking Form Sidebar (Sticky) -->
        <div id="booking-form-sidebar" class="col-span-12 md:col-span-4 relative">
            <div class="sticky top-28 bg-white p-6 md:p-8 rounded-2xl shadow-md border border-slate-200 space-y-6">
                @if(session('success'))
                    <div class="py-6 px-4 bg-primary/5 rounded-2xl border border-primary/10 text-center animate-in zoom-in duration-500" x-init="setTimeout(() => { if ('{{ session('whatsappUrl') }}') { window.location.href = '{{ session('whatsappUrl') }}' } }, 3000)">
                        <div class="w-14 h-14 bg-white text-secondary rounded-full flex items-center justify-center text-2xl shadow-sm border border-secondary/20 mx-auto mb-4">
                            <span class="material-symbols-outlined text-[32px]">check_circle</span>
                        </div>
                        <h4 class="text-xl font-semibold font-headline-md text-primary mb-2">{{ __('Reservasi Terkirim') }}</h4>
                        <p class="text-slate-600 font-body-md mb-6 text-sm leading-relaxed">{{ __('Pesanan Anda berhasil kami catat. Silakan lanjutkan konfirmasi via WhatsApp.') }}</p>
                        
                        <div class="inline-flex flex-col items-center px-6 py-4 bg-white rounded-lg border border-outline-variant mb-6 w-full">
                            <p class="font-label-caps text-[9px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Booking ID</p>
                            <p class="text-2xl font-semibold font-body-md text-primary tracking-wider">{{ session('bookingCode') }}</p>
                        </div>
                        
                        <a 
                            href="{{ session('whatsappUrl') }}"
                            target="_blank"
                            class="w-full py-3 bg-secondary text-on-secondary rounded-lg font-semibold text-xs uppercase tracking-wider shadow-sm hover:bg-secondary/90 transition-all flex items-center justify-center gap-2 group"
                        >
                            <span class="material-symbols-outlined text-[18px]">chat</span>
                            {{ __('KONFIRMASI SEKARANG') }}
                        </a>
                    </div>
                @else
                        <div class="flex justify-between items-end border-b border-slate-200 pb-4">
                        <div>
                            <span class="font-label-caps text-[10px] text-slate-500 uppercase tracking-wider">{{ __('Mulai dari') }}</span>
                            <div class="font-headline-md text-headline-md text-primary" x-text="AppCurrency.format(package.price)"></div>
                        </div>
                        <div class="text-right">
                            <span class="text-secondary font-semibold font-body-md">★ 4.9</span>
                            <span class="text-slate-500 text-[11px] font-body-md block">124 ulasan</span>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs font-body-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('tour.booking.submit') }}" method="POST" class="space-y-5" @submit="isSubmitting = true">
                        @csrf
                        <input type="hidden" name="packageId" :value="package.id">
                        <input type="hidden" name="slug" :value="package.slug">
                        <input type="hidden" name="notes" :value="serializedNotes">
                        
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Nama lengkap') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="customerName" x-model="customerName" required placeholder="{{ __('Nama sesuai identitas') }}" 
                                class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition-all">
                            @error('customerName') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email & WhatsApp -->
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Email') }} <span class="text-red-500">*</span></label>
                                <input type="email" name="customerEmail" x-model="customerEmail" required placeholder="{{ __('email@contoh.com') }}" 
                                    class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition-all">
                                @error('customerEmail') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Nomor WhatsApp') }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="customerPhone" x-model="customerPhone" required placeholder="{{ __('0812-xxxx-xxxx') }}" 
                                    class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition-all">
                                @error('customerPhone') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Tanggal Keberangkatan -->
                        <div>
                            <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Pilih tanggal') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="date" name="startDate" x-model="startDate" required
                                    class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition-all uppercase">
                            </div>
                            @error('startDate') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Pax Dewasa & Anak -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Tamu dewasa') }} <span class="text-red-500">*</span></label>
                                <select name="pax" x-model="pax" required class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition-all">
                                    <option :value="1">1 {{ __('Orang') }}</option>
                                    <option :value="2">2 {{ __('Orang') }}</option>
                                    <option :value="3">3 {{ __('Orang') }}</option>
                                    <option :value="4">4 {{ __('Orang') }}</option>
                                    <option :value="5">5 {{ __('Orang') }}</option>
                                    <option :value="6">6 {{ __('Orang') }}</option>
                                    <option :value="7">7 {{ __('Orang') }}</option>
                                    <option :value="8">8 {{ __('Orang') }}</option>
                                    <option :value="9">9 {{ __('Orang') }}</option>
                                    <option :value="10">10+ {{ __('Orang') }}</option>
                                </select>
                                @error('pax') <span class="text-xs text-error font-body-md mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Anak-anak') }}</label>
                                <select x-model="paxChildren" class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition-all">
                                    <option :value="0">0 {{ __('Orang') }}</option>
                                    <option :value="1">1 {{ __('Orang') }}</option>
                                    <option :value="2">2 {{ __('Orang') }}</option>
                                    <option :value="3">3 {{ __('Orang') }}</option>
                                    <option :value="4">4 {{ __('Orang') }}</option>
                                    <option :value="5">5 {{ __('Orang') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Layanan Tambahan -->
                        <div class="space-y-3">
                            <label class="font-label-caps text-label-caps text-slate-700 mb-1 block uppercase tracking-wider">{{ __('Layanan tambahan') }}</label>
                            
                            <label class="flex items-center justify-between p-3 border border-outline-variant rounded-lg cursor-pointer hover:border-secondary transition-all" :class="privateJet ? 'border-secondary bg-secondary/5' : ''">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-secondary text-[22px]">flight_takeoff</span>
                                    <div>
                                        <div class="font-body-md font-semibold text-slate-900 text-xs">Private Jet Charter</div>
                                        <div class="text-[10px] text-on-surface-variant font-body-md">+ Rp 120.000.000</div>
                                    </div>
                                </div>
                                <input type="checkbox" x-model="privateJet" class="w-4 h-4 text-secondary border-outline-variant focus:ring-0 rounded"/>
                            </label>

                            <label class="flex items-center justify-between p-3 border border-outline-variant rounded-lg cursor-pointer hover:border-secondary transition-all" :class="guide ? 'border-secondary bg-secondary/5' : ''">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-secondary text-[22px]">person_pin</span>
                                    <div>
                                        <div class="font-body-md font-semibold text-slate-900 text-xs">Pemandu Antropologi</div>
                                        <div class="text-[10px] text-on-surface-variant font-body-md">+ Rp 5.500.000</div>
                                    </div>
                                </div>
                                <input type="checkbox" x-model="guide" class="w-4 h-4 text-secondary border-outline-variant focus:ring-0 rounded"/>
                            </label>
                        </div>

                        <!-- Catatan User -->
                        <div>
                            <label class="font-label-caps text-label-caps text-slate-700 mb-2 block uppercase tracking-wider">{{ __('Catatan tambahan') }} <span class="text-[9px] text-slate-500">({{ __('Opsional') }})</span></label>
                            <textarea x-model="notesUser" placeholder="{{ __('Permintaan khusus, hotel, alergi, penjemputan, dll.') }}" rows="2"
                                class="w-full border border-outline-variant rounded-lg p-3 text-sm text-on-surface bg-background focus:ring-1 focus:ring-secondary focus:border-secondary outline-none font-body-md transition-all resize-none"></textarea>
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
                            <div x-show="privateJet" class="flex justify-between text-xs text-slate-600 font-body-md">
                                <span>Private Jet Charter</span>
                                <span x-text="AppCurrency.format(pricePrivateJet)"></span>
                            </div>
                            <div x-show="guide" class="flex justify-between text-xs text-slate-600 font-body-md">
                                <span>{{ __('Pemandu Antropologi') }}</span>
                                <span x-text="AppCurrency.format(priceGuide)"></span>
                            </div>
                            <div class="flex justify-between text-xs text-slate-600 font-body-md">
                                <span>{{ __('Pajak & Layanan') }} (11%)</span>
                                <span x-text="AppCurrency.format(pajakLayanan)"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-200 flex justify-between font-semibold text-primary text-base font-body-md">
                                <span>Total Ringkasan</span>
                                <span x-text="AppCurrency.format(totalAkhir)"></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            :disabled="isSubmitting"
                            class="w-full bg-primary text-on-primary py-4 rounded-lg font-semibold text-xs uppercase tracking-wider hover:bg-primary-container transition-all duration-300 shadow-sm flex items-center justify-center gap-2"
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
                    </form>
                @endif
            </div>
        </div>
    </section>

    <!-- Floating Concierge Bar -->
    <div 
        class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] md:w-auto glass-card border border-slate-200 rounded-2xl px-5 md:px-8 py-3 z-50 flex items-center justify-between gap-4 md:gap-12 shadow-lg transition-all duration-500 transform"
        :class="showConcierge ? 'translate-y-0 opacity-100 pointer-events-auto' : 'translate-y-20 opacity-0 pointer-events-none'"
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
