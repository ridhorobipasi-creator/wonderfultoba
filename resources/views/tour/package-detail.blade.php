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

@section('title', ($package->name ?? 'Paket Wisata') . ' – Wonderful Toba')
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
        showBooking: {{ session('success') || session('error') || $errors->any() ? 'true' : 'false' }},
        isSubmitting: false,
        package: {{ json_encode($package) }},
        package_images: {{ json_encode($packageImagesArray) }},
        city: {{ json_encode($city) }},
        contact: {
            whatsapp: '{{ $siteSettings['cms_tour']['contact_wa'] ?? $siteSettings['general']['whatsapp'] ?? '6281323888207' }}',
            email: '{{ $siteSettings['cms_tour']['contact_email'] ?? $siteSettings['general']['contact_email'] ?? 'hello@medantobatravel.id' }}'
        },
        get waNumber() {
            return (this.contact.whatsapp || '6281323888207').replace(/[^0-9]/g, '');
        },
        get locationDisplay() {
            return this.city ? (this.city.type === 'international' ? (this.city.place || this.city.region || '') + ', ' + this.city.country : this.city.name) : (this.package.locationTag || 'Sumatera Utara');
        }
    }"
    class="bg-white min-h-screen pb-32"
>
    <!-- Cinematic Hero Section -->
    <div class="relative h-[75dvh] w-full overflow-hidden bg-slate-900">

        @foreach($heroImages as $i => $imgObj)
            <img 
                src="{{ imageUrl(is_array($imgObj) ? ($imgObj['image_path'] ?? null) : ($imgObj->image_path ?? null)) }}" 
                class="absolute inset-0 w-full h-full object-cover transition-all duration-1000 ease-in-out {{ $i === 0 ? 'opacity-60 scale-100 z-0' : 'opacity-0 scale-110 -z-10 pointer-events-none' }}"
                :class="activeImg === {{ $i }} ? 'opacity-60 scale-100 z-0' : 'opacity-0 scale-110 -z-10 pointer-events-none'"
                fetchpriority="{{ $i === 0 ? 'high' : 'low' }}"
                loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                alt="{{ $package->name }} - Image {{ $i + 1 }}"
                onerror="this.src='{{ asset('images/home/tour.webp') }}'"
            >
        @endforeach

        <!-- Overlays -->
        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-slate-900/40"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 via-transparent to-transparent"></div>

        <!-- Hero Content -->
        <div class="relative z-10 h-full max-w-7xl mx-auto px-6 md:px-8 flex flex-col justify-center">
            <div class="max-w-4xl animate-in fade-in slide-in-from-left-12 duration-1000">
                <a href="{{ $package->isOutbound ? '/outbound/packages' : '/tour/packages' }}" class="inline-flex items-center gap-3 text-white/80 hover:text-white font-black text-[10px] uppercase tracking-[0.4em] mb-10 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 group-hover:bg-toba-green group-hover:border-toba-green transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </div>
                    {{ __('Kembali ke Katalog') }}
                </a>
                
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-toba-green text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-toba-green/30" x-text="package.status === 'active' ? '{{ __('Paket Tersedia') }}' : '{{ __('Coming Soon') }}'"></span>
                    <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 text-toba-accent rounded-full text-[10px] font-black uppercase tracking-widest" x-text="locationDisplay"></span>
                </div>

                <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter leading-[0.9] mb-12 drop-shadow-2xl" x-text="package.name"></h1>
                
                <div class="flex flex-wrap items-center gap-10">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">{{ __('Durasi Wisata') }}</span>
                        <div class="flex items-center gap-2 text-white font-black text-2xl tracking-tight">
                            <svg class="w-6 h-6 text-toba-green" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <span x-text="package.duration"></span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">{{ __('Rating Wisatawan') }}</span>
                        <div class="flex items-center gap-2 text-white font-black text-2xl tracking-tight">
                            <svg class="w-6 h-6 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span>4.9</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">{{ __('Mulai dari') }}</span>
                        <div class="flex items-baseline gap-1 text-white font-black text-3xl tracking-tighter">
                            <span x-text="AppCurrency.format(package.price)"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-6 md:px-8 -mt-20 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
            
            <!-- Left Column: Details -->
            <div class="lg:col-span-8 space-y-20">
                
                <!-- Image Gallery (Thumbnail Strip) -->
                <div x-show="package_images && package_images.length > 1" class="flex flex-wrap gap-4 animate-in fade-in zoom-in duration-1000">
                    <template x-for="(imgObj, i) in package_images" :key="i">
                        <button @click="activeImg = i"
                            :class="activeImg === i ? 'ring-4 ring-toba-green scale-105 shadow-2xl' : 'opacity-70 hover:opacity-100 border-2 border-white'"
                            class="w-24 h-24 md:w-32 md:h-32 rounded-[1.5rem] overflow-hidden transition-all duration-500 bg-slate-200">
                            <img :src="imgObj.url" class="w-full h-full object-cover" loading="lazy" onerror="this.src='{{ asset('images/home/tour.webp') }}'">
                        </button>
                    </template>
                </div>

                <!-- Embedded Booking Form: Premium Redesign -->
                <div id="booking-form" class="relative group bg-white rounded-[3.5rem] shadow-[0_40px_100px_-20px_rgba(15,23,42,0.1)] border border-slate-100 animate-in fade-in slide-in-from-bottom-12 duration-1000 p-8 md:p-14 lg:p-16 overflow-hidden">
                    
                    <!-- Decorative Background -->
                    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-toba-green/5 rounded-full blur-[120px] -mr-[400px] -mt-[400px] pointer-events-none"></div>

                    <div class="relative z-10 mb-14 flex flex-col items-center text-center">
                        <div class="inline-flex items-center gap-3 px-5 py-2.5 bg-toba-green/10 text-toba-green rounded-full mb-8 border border-toba-green/20">
                            <div class="w-2 h-2 bg-toba-green rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black uppercase tracking-[0.3em]">{{ __('Form Reservasi Resmi') }}</span>
                        </div>
                        <h3 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-tight mb-4">
                            {{ __('Lengkapi Data') }} <span class="text-toba-green">{{ __('Perjalanan Anda') }}</span>
                        </h3>
                        <p class="text-slate-500 font-medium text-base max-w-xl leading-relaxed">{{ __('Keamanan data Anda adalah prioritas kami. Semua informasi dienkripsi dengan standar keamanan tertinggi.') }}</p>
                    </div>

                    @if(session('success'))
                        <div class="py-16 px-8 bg-emerald-50/50 rounded-[3rem] border border-emerald-100 text-center animate-in zoom-in duration-1000" x-init="setTimeout(() => { window.location.href = '{{ session('whatsappUrl') }}' }, 3000)">
                            <div class="relative w-32 h-32 mx-auto mb-10">
                                <div class="absolute inset-0 bg-emerald-500/20 rounded-full animate-ping"></div>
                                <div class="relative w-full h-full bg-white text-emerald-500 rounded-full flex items-center justify-center text-5xl shadow-2xl shadow-emerald-500/30 border-4 border-emerald-50">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <h4 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">{{ __('Reservasi Diterima!') }}</h4>
                            <p class="text-slate-500 font-medium mb-12 max-w-sm mx-auto text-base">{{ __('Kami telah menyimpan data pesanan Anda. Anda akan diarahkan ke WhatsApp dalam 3 detik...') }}</p>
                            
                            <div class="inline-flex flex-col items-center px-16 py-10 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 mb-14">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-4">Official Booking ID</p>
                                <p class="text-5xl font-black text-slate-900 tracking-widest">{{ session('bookingCode') }}</p>
                            </div>
                            
                            <div class="max-w-md mx-auto">
                                <a 
                                    href="{{ session('whatsappUrl') }}"
                                    class="w-full py-6 bg-emerald-500 text-white rounded-3xl font-black text-sm uppercase tracking-widest shadow-2xl shadow-emerald-500/30 hover:bg-emerald-600 hover:-translate-y-2 transition-all flex items-center justify-center gap-4 group"
                                >
                                    <i class="fab fa-whatsapp text-3xl group-hover:scale-110 transition-transform"></i>
                                    {{ __('LANJUTKAN KE WHATSAPP SEKARANG') }}
                                </a>
                            </div>
                        </div>
                    @else
                    @if($package->isOutbound)
                    <!-- Premium Outbound / Corporate Quote Form -->
                    <form action="{{ route('outbound.quote.submit') }}" method="POST" class="relative z-10" @submit="isSubmitting = true">
                        @csrf
                        <input type="hidden" name="activity_type" value="Team Building">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 mb-12">
                            <!-- Nama Instansi -->
                            <div class="space-y-4 md:col-span-2">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-building text-slate-300"></i> {{ __('Nama Instansi / Perusahaan / PIC') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="company_name" required value="{{ old('company_name') }}" placeholder="{{ __('Contoh: PT. Maju Bersama / Bapak Heru') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('company_name') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium">
                                </div>
                                @error('company_name') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- WhatsApp PIC -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fab fa-whatsapp text-slate-300"></i> {{ __('No. WhatsApp PIC') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" name="whatsapp" required value="{{ old('whatsapp') }}" placeholder="0812-xxxx-xxxx" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('whatsapp') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium">
                                </div>
                                @error('whatsapp') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- Jumlah Peserta -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-users text-slate-300"></i> {{ __('Estimasi Jumlah Peserta') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="participants" required min="1" value="{{ old('participants') }}" placeholder="{{ __('Jumlah orang') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('participants') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium">
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 px-4 py-2 bg-slate-200/50 rounded-xl text-[10px] font-black text-slate-500 uppercase tracking-widest pointer-events-none">
                                        {{ __('Orang') }}
                                    </div>
                                </div>
                                @error('participants') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- Lokasi Kegiatan -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-slate-300"></i> {{ __('Pilihan Lokasi Kegiatan') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="location" required value="{{ old('location', $city->name ?? $package->locationTag ?? '') }}" placeholder="{{ __('Contoh: Danau Toba / Medan') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('location') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium">
                                </div>
                                @error('location') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-slate-300"></i> {{ __('Estimasi Tanggal') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="estimated_date" required value="{{ old('estimated_date') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('estimated_date') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 uppercase">
                                </div>
                                @error('estimated_date') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="max-w-2xl mx-auto pt-4">
                            <button 
                                type="submit" 
                                :disabled="isSubmitting"
                                class="w-full h-[5.5rem] bg-toba-green text-white rounded-[2.5rem] font-black text-sm md:text-base uppercase tracking-[0.4em] hover:bg-slate-900 hover:-translate-y-2 active:scale-95 transition-all duration-500 shadow-[0_30px_60px_-15px_rgba(16,185,129,0.4)] flex items-center justify-center gap-6 group overflow-hidden relative border border-emerald-500"
                            >
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-shimmer"></div>
                                
                                <div x-show="!isSubmitting" class="relative z-10 flex items-center justify-center gap-6 w-full px-10">
                                    <span class="flex-1 text-center md:pl-16">{{ __('MINTA PENAWARAN HARGA') }}</span>
                                    <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center group-hover:bg-white group-hover:text-toba-green transition-all duration-500 group-hover:translate-x-4 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </div>
                                </div>

                                <span x-show="isSubmitting" class="flex items-center gap-4 relative z-10 text-white bg-slate-900/50 backdrop-blur-sm w-full h-full justify-center">
                                    <svg class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('MENYIMPAN DATA...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                    @else
                    <form action="{{ route('tour.booking.submit') }}" method="POST" class="relative z-10" @submit="isSubmitting = true">
                        @csrf
                        <input type="hidden" name="packageId" :value="package.id">
                        <input type="hidden" name="slug" :value="package.slug">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 mb-12">
                            <!-- Nama Lengkap -->
                            <div class="space-y-4 md:col-span-2">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-user-circle text-slate-300"></i> {{ __('Nama Sesuai Identitas') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="customerName" required value="{{ old('customerName') }}" placeholder="{{ __('Masukkan nama lengkap') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('customerName') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium">
                                </div>
                                @error('customerName') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-envelope text-slate-300"></i> {{ __('Alamat Email') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" name="customerEmail" required value="{{ old('customerEmail') }}" placeholder="{{ __('email@contoh.com') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('customerEmail') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium">
                                </div>
                                @error('customerEmail') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- WhatsApp -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fab fa-whatsapp text-slate-300"></i> {{ __('Nomor WhatsApp') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" name="customerPhone" required value="{{ old('customerPhone') }}" placeholder="{{ __('0812-xxxx-xxxx') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('customerPhone') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium">
                                </div>
                                @error('customerPhone') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- Tanggal -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-slate-300"></i> {{ __('Tanggal Keberangkatan') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="startDate" required value="{{ old('startDate') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('startDate') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 uppercase">
                                </div>
                                @error('startDate') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- Pax -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-users text-slate-300"></i> {{ __('Jumlah Peserta') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="pax" required min="1" value="{{ old('pax') }}" placeholder="{{ __('Jumlah orang') }}" 
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('pax') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium">
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 px-4 py-2 bg-slate-200/50 rounded-xl text-[10px] font-black text-slate-500 uppercase tracking-widest pointer-events-none">
                                        {{ __('Orang') }}
                                    </div>
                                </div>
                                @error('pax') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>

                            <!-- Notes -->
                            <div class="space-y-4 md:col-span-2">
                                <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                                    <i class="fas fa-comment-dots text-slate-300"></i> {{ __('Catatan Tambahan') }} <span class="text-[9px] text-slate-300 ml-1">({{ __('Opsional') }})</span>
                                </label>
                                <div class="relative">
                                    <textarea name="notes" placeholder="{{ __('Misal: Permintaan hotel khusus, alergi makanan, layanan penjemputan bandara, dll.') }}" rows="3"
                                        class="w-full px-8 py-6 bg-slate-50 hover:bg-slate-100 focus:bg-white border-2 {{ $errors->has('notes') ? 'border-red-500' : 'border-slate-100' }} focus:border-toba-green rounded-3xl outline-none font-bold text-slate-900 text-lg transition-all shadow-sm focus:shadow-xl focus:shadow-toba-green/10 placeholder:text-slate-300 placeholder:font-medium resize-none leading-relaxed">{{ old('notes') }}</textarea>
                                </div>
                                @error('notes') <p class="text-[11px] text-red-500 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="max-w-2xl mx-auto pt-4">
                            <button 
                                type="submit" 
                                :disabled="isSubmitting"
                                class="w-full h-[5.5rem] bg-slate-900 text-white rounded-[2.5rem] font-black text-sm md:text-base uppercase tracking-[0.4em] hover:bg-toba-green hover:-translate-y-2 active:scale-95 transition-all duration-500 shadow-[0_30px_60px_-15px_rgba(15,23,42,0.4)] hover:shadow-[0_40px_80px_-20px_rgba(16,185,129,0.5)] flex items-center justify-center gap-6 group overflow-hidden relative border border-slate-700"
                            >
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-shimmer"></div>
                                
                                <div x-show="!isSubmitting" class="relative z-10 flex items-center justify-center gap-6 w-full px-10">
                                    <span class="flex-1 text-center md:pl-16">{{ __('KONFIRMASI RESERVASI') }}</span>
                                    <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center group-hover:bg-white group-hover:text-toba-green transition-all duration-500 group-hover:translate-x-4 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </div>
                                </div>

                                <span x-show="isSubmitting" class="flex items-center gap-4 relative z-10 text-toba-green bg-slate-900/50 backdrop-blur-sm w-full h-full justify-center">
                                    <svg class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('MENYIMPAN DATA...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                    @endif  
                    <!-- Horizontal Trust Bar replacing the old dark side section -->
                    <div class="mt-16 pt-12 border-t border-slate-100">
                        <h4 class="text-center text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-8">{{ __('Eksklusivitas Wonderful Toba') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                            <div class="flex flex-col items-center group">
                                <div class="w-16 h-16 rounded-3xl bg-slate-50 border border-slate-100 flex items-center justify-center text-toba-green mb-5 group-hover:bg-toba-green group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
                                </div>
                                <p class="font-black text-slate-900 text-sm uppercase tracking-widest mb-2">{{ __('Premium Curator') }}</p>
                                <p class="text-xs text-slate-500 font-medium max-w-[200px]">{{ __('Destinasi eksklusif untuk pengalaman bintang lima.') }}</p>
                            </div>
                            <div class="flex flex-col items-center group">
                                <div class="w-16 h-16 rounded-3xl bg-slate-50 border border-slate-100 flex items-center justify-center text-toba-green mb-5 group-hover:bg-toba-green group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="font-black text-slate-900 text-sm uppercase tracking-widest mb-2">{{ __('Local Heritage') }}</p>
                                <p class="text-xs text-slate-500 font-medium max-w-[200px]">{{ __('Dipandu oleh putra daerah berpengalaman.') }}</p>
                            </div>
                            <div class="flex flex-col items-center group">
                                <div class="w-16 h-16 rounded-3xl bg-slate-50 border border-slate-100 flex items-center justify-center text-toba-green mb-5 group-hover:bg-toba-green group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                </div>
                                <p class="font-black text-slate-900 text-sm uppercase tracking-widest mb-2">{{ __('Safe & Secure') }}</p>
                                <p class="text-xs text-slate-500 font-medium max-w-[200px]">{{ __('Transaksi dan privasi Anda terjamin 100%.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>                <!-- Tabs Navigation -->
                <div class="mt-20 border-b border-slate-100 overflow-x-auto no-scrollbar mb-14">
                    <div class="flex gap-12">
                        <button @click="activeTab = 'itinerary'" 
                            :class="activeTab === 'itinerary' ? 'text-toba-green border-b-4 border-toba-green pb-6' : 'text-slate-400 hover:text-slate-600 pb-6'"
                            class="text-[10px] font-black uppercase tracking-[0.4em] transition-all whitespace-nowrap flex items-center gap-3">
                            <i class="fas fa-map-marked-alt text-sm"></i>
                            {{ __('AGENDA PERJALANAN') }}
                        </button>
                        <button @click="activeTab = 'pricing'" 
                            :class="activeTab === 'pricing' ? 'text-toba-green border-b-4 border-toba-green pb-6' : 'text-slate-400 hover:text-slate-600 pb-6'"
                            class="text-[10px] font-black uppercase tracking-[0.4em] transition-all whitespace-nowrap flex items-center gap-3">
                            <i class="fas fa-tags text-sm"></i>
                            {{ __('BIAYA & FASILITAS') }}
                        </button>
                        <button @click="activeTab = 'reviews'" 
                            :class="activeTab === 'reviews' ? 'text-toba-green border-b-4 border-toba-green pb-6' : 'text-slate-400 hover:text-slate-600 pb-6'"
                            class="text-[10px] font-black uppercase tracking-[0.4em] transition-all whitespace-nowrap flex items-center gap-3">
                            <i class="fas fa-star text-sm"></i>
                            {{ __('ULASAN') }}
                        </button>
                    </div>
                </div>

                <!-- Tab: Itinerary & Description -->
                <div x-show="activeTab === 'itinerary'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-20">
                    <!-- About Section -->
                    <div class="bg-white rounded-[3rem] p-10 md:p-14 shadow-[0_40px_80px_-20px_rgba(15,23,42,0.08)] border border-slate-50">
                        <div class="flex items-center space-x-3 mb-8">
                            <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                            <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">{{ __('Tentang Paket') }}</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-8 tracking-tight">{{ __('Ringkasan') }} <span class="text-toba-green">{{ __('Pengalaman') }}</span></h2>
                        <div class="prose prose-lg prose-slate max-w-none text-slate-600 font-medium leading-relaxed" x-html="package.description"></div>
                    </div>

                    <!-- Visual Itinerary Timeline -->
                    <div x-show="package.itinerary || package.itineraryText" class="relative">
                        <div class="flex items-center space-x-3 mb-12">
                            <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                            <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">Agenda Perjalanan</span>
                        </div>
                        
                        <div x-show="package.itineraryText" class="bg-white rounded-[3rem] p-10 md:p-14 shadow-xl border border-slate-50 whitespace-pre-line text-slate-600 font-medium leading-relaxed" x-text="package.itineraryText"></div>
                        
                        <div x-show="!package.itineraryText && package.itinerary" class="space-y-12 relative">
                            <!-- Vertical line -->
                            <div class="absolute left-[31px] top-10 bottom-10 w-1.5 bg-gradient-to-b from-toba-green via-emerald-100 to-transparent rounded-full hidden md:block"></div>
                            
                            <template x-for="(day, i) in package.itinerary" :key="i">
                                <div class="relative pl-0 md:pl-20 group">
                                    <!-- Indicator -->
                                    <div class="absolute left-0 top-0 w-16 h-16 rounded-3xl bg-slate-900 text-white flex flex-col items-center justify-center shadow-2xl z-10 transition-transform duration-500 group-hover:scale-110 group-hover:bg-toba-green hidden md:flex">
                                        <span class="text-[10px] font-black uppercase opacity-60" x-text="AppCurrency.locale === 'en' ? 'Day' : 'Hari'"></span>
                                        <span class="text-2xl font-black" x-text="day.day || (i + 1)"></span>
                                    </div>
                                    
                                    <div class="bg-white rounded-[2.5rem] p-10 md:p-12 shadow-2xl shadow-slate-200/50 border border-slate-50 transition-all duration-500 group-hover:border-toba-green/20 group-hover:shadow-toba-green/10">
                                        <div class="flex items-center gap-4 mb-6 md:hidden">
                                            <div class="w-12 h-12 rounded-2xl bg-toba-green text-white flex items-center justify-center font-black">
                                                <span x-text="day.day || (i + 1)"></span>
                                            </div>
                                            <span class="text-xs font-black text-toba-green uppercase tracking-widest" x-text="AppCurrency.locale === 'en' ? 'Today\'s Agenda' : 'Agenda Hari Ini'"></span>
                                        </div>
                                        <h4 class="text-2xl font-black text-slate-900 mb-6 tracking-tight" x-text="day.title"></h4>
                                        <p x-show="day.description" class="text-slate-600 font-medium leading-relaxed mb-8" x-text="day.description"></p>
                                        
                                        <template x-if="day.activities && day.activities.length > 0">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <template x-for="(act, j) in day.activities" :key="j">
                                                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-toba-green/20 transition-all">
                                                        <div class="w-2 h-2 bg-toba-green rounded-full"></div>
                                                        <span class="text-sm font-bold text-slate-700" x-text="act"></span>
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

                <!-- Tab: Pricing & Facilities -->
                <div x-show="activeTab === 'pricing'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-16">
                    <!-- Pricing Table -->
                    <div x-show="package.pricingDetails && package.pricingDetails.length > 0" class="bg-white rounded-[3rem] p-10 md:p-14 shadow-2xl shadow-slate-200/50 border border-slate-100">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
                            <div>
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="h-1.5 w-12 bg-toba-green rounded-full"></div>
                                    <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">{{ __('Rincian Biaya') }}</span>
                                </div>
                                <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('Investasi') }} <span class="text-toba-green">{{ __('Perjalanan') }}</span></h3>
                            </div>
                            <div class="px-6 py-3 bg-slate-900 rounded-2xl text-white">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">{{ __('Update') }}</span>
                                <p class="text-sm font-black tracking-tight">{{ __('Musim 2026') }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                            <template x-for="price in package.pricingDetails" :key="price.pax">
                                <div class="flex items-center justify-between p-8 bg-slate-50 rounded-[2rem] border border-transparent hover:border-toba-green/20 transition-all group">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-2xl bg-white shadow-xl flex items-center justify-center text-toba-green group-hover:bg-toba-green group-hover:text-white transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('Peserta') }}</p>
                                            <p class="text-lg font-black text-slate-900" x-text="price.label || (price.pax + ' ' + (AppCurrency.locale === 'en' ? 'People' : 'Orang'))"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('Per Orang') }}</p>
                                        <p class="text-xl font-black text-toba-green">
                                            <span x-text="AppCurrency.format(price.price || price.price_per_person || price.pricePerPerson)"></span>
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="p-8 bg-toba-green/5 rounded-3xl border border-dashed border-toba-green/30 flex items-start gap-6">
                            <div class="w-12 h-12 rounded-2xl bg-white shadow-lg flex items-center justify-center text-toba-green shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900 mb-1 uppercase tracking-wider">{{ __('Catatan Penting') }}</p>
                                <p class="text-sm text-slate-600 font-medium leading-relaxed">{{ __('Harga di atas bersifat dinamis dan dapat berubah sewaktu-waktu tergantung musim dan ketersediaan akomodasi. Untuk grup besar (>15 pax), kami memiliki penawaran harga khusus.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Inclusion / Exclusion -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="bg-white rounded-[3rem] p-10 md:p-14 shadow-2xl shadow-slate-200/50 border border-slate-100">
                            <h3 class="text-2xl font-black text-slate-900 mb-10 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                </div>
                                {{ __('Termasuk') }}
                            </h3>
                            <ul class="space-y-5">
                                <template x-for="item in package.package_includes" :key="item.id">
                                    <li class="flex items-start gap-4">
                                        <div class="mt-1.5 w-2 h-2 bg-emerald-500 rounded-full shrink-0 shadow-sm"></div>
                                        <span class="text-slate-600 font-bold leading-tight" x-text="item.name"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        <div class="bg-white rounded-[3rem] p-10 md:p-14 shadow-2xl shadow-slate-200/50 border border-slate-100">
                            <h3 class="text-2xl font-black text-slate-900 mb-10 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-500 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                </div>
                                {{ __('Tidak Termasuk') }}
                            </h3>
                            <ul class="space-y-5">
                                <template x-for="item in package.package_excludes" :key="item.id">
                                    <li class="flex items-start gap-4">
                                        <div class="mt-1.5 w-2 h-2 bg-slate-300 rounded-full shrink-0"></div>
                                        <span class="text-slate-400 font-bold leading-tight" x-text="item.name"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tab: Reviews -->
                <div x-show="activeTab === 'reviews'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-[3rem] p-10 md:p-14 shadow-2xl shadow-slate-200/50 border border-slate-100">
                        @php
                            $testimonials = $siteSettings['cms_tour']['testimonials'] ?? [];
                        @endphp

                        @if(!empty($testimonials))
                            <div class="mb-12">
                                <h3 class="text-3xl font-black text-slate-900 tracking-tight mb-2">{{ __('Ulasan') }} <span class="text-toba-green">{{ __('Pengunjung') }}</span></h3>
                                <p class="text-slate-500 font-medium">{{ __('Apa kata mereka yang telah menjelajahi Danau Toba bersama kami.') }}</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach($testimonials as $t)
                                <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-transparent hover:border-toba-green/20 transition-all">
                                    <div class="flex items-center gap-4 mb-6">
                                        @if(!empty($t['image']))
                                            <img src="{{ imageUrl($t['image']) }}" class="w-12 h-12 rounded-2xl object-cover bg-slate-200" alt="{{ $t['name'] }}" onerror="this.style.display='none'">
                                        @else
                                            <div class="w-12 h-12 rounded-2xl bg-toba-green/10 flex items-center justify-center text-toba-green font-black text-xl">
                                                {{ strtoupper(substr($t['name'] ?? '?', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-black text-slate-900 text-sm">{{ $t['name'] }}</p>
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ __($t['location'] ?? '') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ __($t['text'] ?? '') }}"</p>
                                </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Tidak ada testimonial: tampilkan ajakan review via WA --}}
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-toba-green/5 rounded-full flex items-center justify-center mx-auto mb-8">
                                    <svg class="w-10 h-10 text-toba-green/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-5l-5 5v-5z"/></svg>
                                </div>
                                <h4 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">{{ __('Bagikan Pengalaman Anda') }}</h4>
                                <p class="text-slate-500 font-medium max-w-md mx-auto mb-10 leading-relaxed">{{ __('Sudah pernah berwisata bersama kami? Kami sangat menghargai cerita perjalanan Anda.') }}</p>
                                <a :href="'https://wa.me/' + waNumber + '?text=' + encodeURIComponent('Halo Wonderful Toba, saya ingin berbagi pengalaman wisata bersama kalian 😊')" target="_blank"
                                   class="inline-flex items-center gap-3 bg-toba-green text-white px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all duration-500 shadow-xl shadow-toba-green/20">
                                    <i class="fab fa-whatsapp text-xl"></i>
                                    {{ __('Ceritakan Perjalananmu') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
  </div>

            <!-- Right Column: Sticky Booking -->
            <div class="lg:col-span-4 relative">
                <div class="sticky top-28 space-y-8 animate-in fade-in slide-in-from-right-12 duration-1000">
                    <!-- Booking Card -->
                    <div class="bg-slate-900 rounded-[3.5rem] p-10 md:p-12 text-white shadow-[0_50px_100px_-20px_rgba(15,23,42,0.4)] relative overflow-hidden">
                        <!-- Design elements -->
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-toba-green/20 rounded-full blur-[80px]"></div>
                        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-toba-accent/10 rounded-full blur-[80px]"></div>
                        
                        <div class="relative z-10">
                            <span class="text-toba-accent font-black text-[11px] uppercase tracking-[0.4em] mb-6 block">{{ __('Reservasi Sekarang') }}</span>
                            <h3 class="text-3xl font-black text-white mb-4 tracking-tighter leading-tight" x-text="package.name"></h3>
                            
                            <div class="flex items-center gap-6 mb-10 border-b border-white/10 pb-10">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1.5">{{ __('Durasi') }}</span>
                                    <span class="text-lg font-black tracking-tight" x-text="package.duration"></span>
                                </div>
                                <div class="w-px h-10 bg-white/10"></div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1.5">{{ __('Lokasi') }}</span>
                                    <span class="text-lg font-black tracking-tight" x-text="locationDisplay"></span>
                                </div>
                            </div>

                            <div class="mb-10">
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-3">{{ __('Harga Estimasi Per Orang') }}</p>
                                <p class="text-5xl font-black text-white tracking-tighter">
                                    <span x-text="AppCurrency.format(package.price)"></span>
                                </p>
                            </div>

                            <a
                                href="#booking-form"
                                class="w-full py-6 bg-toba-green text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] hover:bg-white hover:text-slate-900 transition-all duration-500 shadow-2xl shadow-toba-green/20 flex items-center justify-center gap-3 group"
                            >
                                <i class="fas fa-calendar-check text-xl"></i>
                                <span>{{ __('ISI FORM BOOKING') }}</span>
                            </a>

                            <p class="text-center text-[9px] font-black uppercase tracking-widest text-white/30 mt-6">{{ __('Atau hubungi kami langsung via') }}</p>
                            <a
                                :href="'https://wa.me/' + waNumber + '?text=' + encodeURIComponent('Halo Wonderful Toba, saya ingin bertanya tentang paket: *' + package.name + '*') "
                                target="_blank"
                                class="flex items-center justify-center gap-2 mt-4 text-toba-accent font-black text-[10px] uppercase tracking-widest hover:text-white transition-colors"
                            >
                                <i class="fab fa-whatsapp text-lg"></i>
                                WhatsApp Fast Response
                            </a>
                        </div>
                    </div>

                    <!-- PDF & Share -->
                    <div class="flex flex-col gap-4">
                        <a href="{{ route('itinerary.download', $package->slug) }}" class="flex items-center justify-center gap-3 py-6 bg-slate-50 text-slate-900 rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all duration-500 border border-slate-100 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            {{ __('Download Itinerary PDF') }}
                        </a>
                        
                        <!-- Contact Specialist Card -->
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-toba-green/5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-700"></div>
                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-toba-green overflow-hidden border-2 border-white shadow-sm">
                                    <img src="{{ imageUrl($siteSettings['cms_tour']['specialist_image_url'] ?? null) }}" class="w-full h-full object-cover" onerror="this.src='https://i.pravatar.cc/100?u=staff1'">
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __($siteSettings['cms_tour']['specialist_title'] ?? 'Travel Specialist') }}</p>
                                    <p class="text-sm font-black text-slate-900">{{ $siteSettings['cms_tour']['specialist_name'] ?? 'Sarah Anggraini' }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed mb-8 relative z-10">{{ __($siteSettings['cms_tour']['specialist_desc'] ?? 'Punya pertanyaan khusus? Saya siap membantu merencanakan liburan impian Anda.') }}</p>
                            <a :href="'https://wa.me/' + waNumber" class="flex items-center justify-center gap-2 py-4 bg-emerald-50 text-emerald-600 rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all relative z-10">
                                <i class="fab fa-whatsapp text-lg"></i>
                                {{ __('TANYA') }} {{ strtoupper(explode(' ', $siteSettings['cms_tour']['specialist_name'] ?? 'Sarah')[0]) }} {{ __('SEKARANG') }}
                            </a>
                        </div>

                        {{-- Google rating badge dihapus -- tidak ada sumber data valid --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>


</div>
@endsection
