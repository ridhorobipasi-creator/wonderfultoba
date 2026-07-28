@php
    $g = $siteSettings['general'] ?? [];
    $officeAddress = $g['office_address'] ?? 'Jl. Danau Toba No. 12C Gg Lawu, Medan & Samosir, Sumatera Utara 20111';
    $socials = array_filter([
        'facebook'  => $g['social_facebook'] ?? null,
        'tiktok'    => $g['social_tiktok'] ?? null,
        'youtube'   => $g['social_youtube'] ?? null,
        'instagram' => !empty($g['social_instagram'])
            ? 'https://instagram.com/' . str_replace('@', '', $g['social_instagram'])
            : null,
    ]);
    $activeLocale = session('locale', 'my');
    $locales = [
        'my' => '🇲🇾 MYR (Melayu)',
        'id' => '🇮🇩 IDR (Indonesia)',
        'en' => '🇸🇬 SGD (English)',
    ];
    $localeShort = ['my' => '🇲🇾 MYR', 'id' => '🇮🇩 IDR', 'en' => '🇸🇬 SGD'];

    // Satu sumber kebenaran untuk menu — desktop & drawer mobile membacanya sama.
    $navLinks = [
        ['label' => __('Tentang Kami'),     'url' => '/about',         'active' => request()->is('about')],
        ['label' => __('Blog'),             'url' => '/tour/blog',     'active' => request()->is('tour/blog*')],
        ['label' => __('Kontak'),           'url' => route('booking.track.form'), 'active' => request()->is('track-booking*')],
    ];
@endphp

<header
    x-data="{
        isMenuOpen: false,
        scrolled: false,
        contact: {
            phone: @js(\App\Helpers\ContactHelper::whatsappDisplay()),
            email: '{{ $g['contact_email'] ?? 'info@sujailaketoba.com' }}',
            whatsapp: @js(\App\Helpers\ContactHelper::whatsappDigits())
        }
    }"
    x-init="$watch('isMenuOpen', open => document.body.classList.toggle('overflow-hidden', open))"
    @keydown.escape.window="isMenuOpen = false"
    class="relative w-full font-sans z-[100]"
>
    <!-- 1. Topbar (Minimalist Dark) -->
    <div class="hidden sm:block bg-slate-900 text-slate-300">
        <div class="max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center gap-6 py-2.5 text-[11.5px] tracking-wide">
            <!-- Lokasi Kantor -->
            <div class="flex items-center gap-2 min-w-0 opacity-90 hover:opacity-100 hover:text-white transition-all">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="truncate">{{ $officeAddress }}</span>
            </div>

            <!-- Sosial + Bahasa -->
            <div class="flex items-center gap-4 shrink-0">
                @if(count($socials))
                    <div class="flex items-center gap-3">
                        @foreach($socials as $name => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                               aria-label="{{ ucfirst($name) }}"
                               class="text-slate-400 hover:text-white transition-colors duration-200">
                                <x-icon :name="$name" class="w-3.5 h-3.5" />
                            </a>
                        @endforeach
                    </div>
                    <span class="w-px h-3 bg-slate-700" aria-hidden="true"></span>
                @endif

                <div x-data="{ open: false }" class="relative z-[110]">
                    <button @click="open = !open" type="button"
                            :aria-expanded="open" aria-haspopup="true"
                            class="flex items-center gap-1.5 text-slate-400 hover:text-white transition-colors duration-200 font-semibold uppercase tracking-wider">
                        <span>{{ $localeShort[$activeLocale] ?? $localeShort['my'] }}</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute right-0 mt-2 w-48 bg-white text-slate-700 rounded-lg shadow-xl shadow-slate-900/10 ring-1 ring-slate-200 py-1 text-xs font-medium overflow-hidden z-[200]">
                        @foreach($locales as $code => $label)
                            <a href="{{ route('change-locale', $code) }}"
                               class="flex items-center justify-between gap-2 px-4 py-2.5 transition-colors hover:bg-slate-50 {{ $activeLocale === $code ? 'text-toba-green font-semibold bg-slate-50' : 'text-slate-600' }}">
                                <span>{{ $label }}</span>
                                @if($activeLocale === $code)
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Main Nav Putih (sticky, clean) -->
    <nav @scroll.window="scrolled = window.scrollY > 24"
         :class="scrolled ? 'shadow-md py-2.5' : 'py-3 md:py-3.5'"
         class="sticky top-0 bg-white/95 backdrop-blur-md border-b border-slate-100 py-3 md:py-3.5 transition-all duration-300 z-[120]">
        <div class="max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center gap-4">
                <!-- Logo -->
                <a href="/" aria-label="{{ $g['site_name'] ?? 'Sujai Tour' }} — Beranda"
                   class="group flex items-baseline gap-1 shrink-0 mr-4 focus-visible:outline-none rounded">
                    <span class="text-2xl md:text-[1.7rem] font-extrabold tracking-tight text-slate-900 uppercase leading-none transition-colors duration-300 group-hover:text-toba-green">SUJAI</span>
                    <span class="text-2xl md:text-[1.7rem] font-medium tracking-tight text-toba-green italic leading-none"
                          style="font-family: 'Brush Script MT', 'Segoe Script', 'Lucida Handwriting', cursive;">Tour</span>
                </a>

                <!-- Nav Links Desktop (Minimalist) -->
                <div class="hidden lg:flex items-center gap-8 text-[14px] font-medium text-slate-500">
                    @php $isHome = request()->is('/'); @endphp
                    <a href="/" @if($isHome) aria-current="page" @endif
                       class="group relative py-1 transition-colors duration-300 {{ $isHome ? 'text-slate-900 font-semibold' : 'hover:text-slate-900' }}">
                        {{ __('Home') }}
                        <span class="absolute left-1/2 -bottom-1 h-[2px] rounded-full bg-slate-900 transition-all duration-300 ease-out -translate-x-1/2 {{ $isHome ? 'w-4' : 'w-0 group-hover:w-4' }}"></span>
                    </a>

                    <!-- Dropdown Paket -->
                    @php $isPkg = request()->is('tour/packages*'); @endphp
                    <div x-data="{ openPkg: false }" @mouseenter="openPkg = true" @mouseleave="openPkg = false" class="relative">
                        <a href="/tour/packages" @if($isPkg) aria-current="page" @endif
                           :aria-expanded="openPkg"
                           class="group relative flex items-center gap-1.5 py-1 transition-colors duration-300 {{ $isPkg ? 'text-slate-900 font-semibold' : 'hover:text-slate-900' }}">
                            <span>{{ __('Paket Wisata Toba') }}</span>
                            <svg class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400 group-hover:text-slate-900" :class="openPkg && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            <span class="absolute left-1/2 -bottom-1 h-[2px] rounded-full bg-slate-900 transition-all duration-300 ease-out -translate-x-1/2 {{ $isPkg ? 'w-4' : 'w-0 group-hover:w-4' }}"></span>
                        </a>
                        <!-- pt-4 for hover bridge -->
                        <div x-show="openPkg" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute left-0 top-full pt-4 w-72 z-[200]">
                            <div class="bg-white rounded-xl shadow-xl shadow-slate-900/10 ring-1 ring-slate-100 p-2 overflow-hidden">
                                <a href="/tour/packages" class="block px-4 py-2.5 text-[13px] font-semibold text-slate-800 rounded-lg hover:bg-slate-50 transition-colors">{{ __('Semua Paket Tour') }}</a>
                                @if(count($navPackages ?? []))
                                    <div class="my-2 border-t border-slate-100"></div>
                                    <div class="max-h-[60vh] overflow-y-auto space-y-0.5">
                                        @foreach($navPackages as $navPkg)
                                            <a href="/tour/package/{{ $navPkg->slug }}"
                                               class="block px-4 py-2.5 rounded-lg hover:bg-slate-50 transition-colors group/item">
                                                <span class="block text-[13px] font-medium text-slate-700 group-hover/item:text-slate-900">{{ $navPkg->translated_name }}</span>
                                                @if($navPkg->duration)
                                                    <span class="block mt-0.5 text-[11px] text-slate-400">{{ $navPkg->duration }}</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @foreach($navLinks as $link)
                        <a href="{{ $link['url'] }}" @if($link['active']) aria-current="page" @endif
                           class="group relative py-1 transition-colors duration-300 {{ $link['active'] ? 'text-slate-900 font-semibold' : 'hover:text-slate-900' }}">
                            {{ $link['label'] }}
                            <span class="absolute left-1/2 -bottom-1 h-[2px] rounded-full bg-slate-900 transition-all duration-300 ease-out -translate-x-1/2 {{ $link['active'] ? 'w-4' : 'w-0 group-hover:w-4' }}"></span>
                        </a>
                    @endforeach
                </div>

                <div class="hidden lg:flex items-center shrink-0 ml-6">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noopener noreferrer"
                       class="group inline-flex items-center gap-2.5 border border-toba-green text-toba-green hover:bg-toba-green hover:text-white px-6 py-2.5 rounded-full font-medium text-[13.5px] tracking-wide transition-all duration-300">
                        <span>{{ __('Hubungi Kami') }}</span>
                        <x-icon name="whatsapp" class="w-3 h-3 transition-transform duration-300 group-hover:scale-110" />
                    </a>
                </div>

                <!-- Aksi Mobile -->
                <div class="lg:hidden flex items-center gap-2">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noopener noreferrer"
                       aria-label="{{ __('HUBUNGI KAMI!') }}"
                       class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center active:scale-95 transition-transform">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                    </a>
                    <button @click="isMenuOpen = true" type="button"
                            aria-label="{{ __('Buka menu') }}" :aria-expanded="isMenuOpen"
                            class="p-2 -mr-2 text-slate-800 rounded-lg hover:bg-slate-100 transition-colors focus-visible:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Drawer Mobile -->
    <div x-show="isMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-[150]" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="isMenuOpen" @click="isMenuOpen = false"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

        <!-- Panel -->
        <div x-show="isMenuOpen"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="absolute inset-y-0 right-0 w-[85%] max-w-sm bg-white shadow-2xl flex flex-col">
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
                <span class="text-xl font-extrabold text-slate-900 leading-none uppercase">SUJAI<span class="text-toba-green italic font-medium ml-1 normal-case" style="font-family: 'Brush Script MT', 'Segoe Script', 'Lucida Handwriting', cursive;">Tour</span></span>
                <button @click="isMenuOpen = false" type="button" aria-label="{{ __('Tutup menu') }}"
                        class="w-9 h-9 rounded-full text-slate-500 hover:bg-slate-100 hover:text-slate-800 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                @php
                    $isPkgMobile = request()->is('tour/packages*') || request()->is('tour/package/*');
                    $mobileLinks = $navLinks;
                @endphp

                <a href="/" @if(request()->is('/')) aria-current="page" @endif
                   class="flex items-center justify-between px-4 py-3.5 rounded-xl text-[15px] font-medium transition-colors {{ request()->is('/') ? 'bg-slate-50 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span>{{ __('Home') }}</span>
                </a>

                <!-- Paket -->
                <div x-data="{ openPkgMobile: {{ $isPkgMobile ? 'true' : 'false' }} }">
                    <div class="flex items-center rounded-xl {{ $isPkgMobile ? 'bg-slate-50' : '' }}">
                        <a href="/tour/packages" @if($isPkgMobile) aria-current="page" @endif
                           class="flex-1 px-4 py-3.5 rounded-xl text-[15px] font-medium transition-colors {{ $isPkgMobile ? 'text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            {{ __('Paket Wisata Toba') }}
                        </a>
                        @if(count($navPackages ?? []))
                            <button @click="openPkgMobile = !openPkgMobile" type="button"
                                    :aria-expanded="openPkgMobile" aria-label="{{ __('Lihat daftar paket') }}"
                                    class="w-12 h-12 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 transition-transform duration-200" :class="openPkgMobile && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        @endif
                    </div>
                    @if(count($navPackages ?? []))
                        <div x-show="openPkgMobile" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mt-1 ml-4 pl-4 border-l-2 border-slate-100 space-y-1">
                            @foreach($navPackages as $navPkg)
                                <a href="/tour/package/{{ $navPkg->slug }}"
                                   class="block px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                    <span class="block text-sm font-medium">{{ $navPkg->translated_name }}</span>
                                    @if($navPkg->duration)
                                        <span class="block mt-0.5 text-xs text-slate-400">{{ $navPkg->duration }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @foreach($mobileLinks as $link)
                    <a href="{{ $link['url'] }}" @if($link['active']) aria-current="page" @endif
                       class="flex items-center justify-between px-4 py-3.5 rounded-xl text-[15px] font-medium transition-colors {{ $link['active'] ? 'bg-slate-50 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="px-6 py-5 border-t border-slate-100 space-y-4" style="padding-bottom: calc(1.25rem + env(safe-area-inset-bottom));">
                @if(count($socials))
                    <div class="flex items-center justify-center gap-4">
                        @foreach($socials as $name => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($name) }}"
                               class="text-slate-400 hover:text-slate-900 transition-colors duration-200">
                                <x-icon :name="$name" class="w-4 h-4" />
                            </a>
                        @endforeach
                    </div>
                @endif
                <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noopener noreferrer"
                   class="w-full py-3.5 bg-slate-900 hover:bg-toba-green text-white rounded-full font-medium text-[15px] text-center flex items-center justify-center gap-2 transition-colors">
                    <span>{{ __('Hubungi Kami') }}</span>
                    <x-icon name="whatsapp" class="w-4 h-4 opacity-90" />
                </a>
            </div>
        </div>
    </div>
</header>
