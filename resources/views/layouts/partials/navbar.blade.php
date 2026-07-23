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
            phone: @json(\App\Helpers\ContactHelper::whatsappDisplay()),
            email: '{{ $g['contact_email'] ?? 'info@sujailaketoba.com' }}',
            whatsapp: @json(\App\Helpers\ContactHelper::whatsappDigits())
        }
    }"
    x-init="$watch('isMenuOpen', open => document.body.classList.toggle('overflow-hidden', open))"
    @keydown.escape.window="isMenuOpen = false"
    class="relative z-[100] w-full font-sans"
>
    <!-- 1. Topbar (strip identitas — hijau merek) -->
    <div class="hidden sm:block bg-gradient-to-r from-toba-green via-toba-green to-primary text-white">
        <div class="max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center gap-6 py-2 text-[12px] font-bold">
            <!-- Lokasi Kantor -->
            <div class="flex items-center gap-2 min-w-0">
                <svg class="w-4 h-4 shrink-0 text-white/90" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                <span class="truncate tracking-tight text-white/95">{{ $officeAddress }}</span>
            </div>

            <!-- Sosial + Bahasa -->
            <div class="flex items-center gap-4 shrink-0">
                @if(count($socials))
                    <div class="flex items-center gap-1.5">
                        @foreach($socials as $name => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                               aria-label="{{ ucfirst($name) }}"
                               class="w-7 h-7 rounded-full flex items-center justify-center text-white/90 hover:text-white hover:bg-white/20 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">
                                <x-icon :name="$name" class="w-[15px] h-[15px]" />
                            </a>
                        @endforeach
                    </div>
                    <span class="w-px h-4 bg-white/30" aria-hidden="true"></span>
                @endif

                <div x-data="{ open: false }" class="relative z-[110]">
                    <button @click="open = !open" type="button"
                            :aria-expanded="open" aria-haspopup="true"
                            class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide hover:bg-white/20 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">
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
                         class="absolute right-0 mt-2 w-48 bg-white text-slate-700 rounded-xl shadow-xl shadow-slate-900/10 ring-1 ring-slate-900/5 py-1.5 text-xs font-semibold overflow-hidden z-[200]">
                        @foreach($locales as $code => $label)
                            <a href="{{ route('change-locale', $code) }}"
                               class="flex items-center justify-between gap-2 px-4 py-2 transition-colors hover:bg-toba-green/5 hover:text-toba-green {{ $activeLocale === $code ? 'bg-toba-green/5 text-toba-green' : '' }}">
                                <span>{{ $label }}</span>
                                @if($activeLocale === $code)
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Main Nav Putih (sticky) -->
    <nav @scroll.window="scrolled = window.scrollY > 24"
         :class="scrolled ? 'shadow-lg shadow-slate-900/[0.07] py-2.5' : 'shadow-sm py-3.5'"
         class="sticky top-0 bg-white/95 backdrop-blur-md border-b border-slate-100 transition-all duration-300 z-[120]">
        <div class="max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center gap-4">
                <!-- Logo -->
                <a href="/" aria-label="{{ $g['site_name'] ?? 'Sujai Tour' }} — Beranda"
                   class="group flex items-baseline gap-1 shrink-0 mr-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-toba-green/50 focus-visible:ring-offset-4 rounded">
                    <span class="text-3xl md:text-[2.1rem] font-black tracking-tight text-toba-green uppercase leading-none transition-colors duration-300 group-hover:text-primary">SUJAI</span>
                    <span class="text-3xl md:text-[2.1rem] font-bold tracking-tight text-toba-green italic leading-none"
                          style="font-family: 'Brush Script MT', 'Segoe Script', 'Lucida Handwriting', cursive;">Tour</span>
                </a>

                <!-- Nav Links Desktop -->
                <div class="hidden lg:flex items-center gap-7 text-[15px] font-extrabold text-[#2c3e50]">
                    @php $isHome = request()->is('/'); @endphp
                    <a href="/" @if($isHome) aria-current="page" @endif
                       class="group relative py-2 transition-colors duration-200 {{ $isHome ? 'text-toba-green' : 'hover:text-toba-green' }}">
                        {{ __('Home') }}
                        <span class="absolute left-0 -bottom-0.5 h-[3px] rounded-full bg-toba-green transition-all duration-300 {{ $isHome ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>

                    <!-- Dropdown Paket -->
                    @php $isPkg = request()->is('tour/packages*'); @endphp
                    <div x-data="{ openPkg: false }" @mouseenter="openPkg = true" @mouseleave="openPkg = false" class="relative">
                        <a href="/tour/packages" @if($isPkg) aria-current="page" @endif
                           :aria-expanded="openPkg"
                           class="group relative flex items-center gap-1 py-2 transition-colors duration-200 {{ $isPkg ? 'text-toba-green' : 'hover:text-toba-green' }}">
                            <span>{{ __('Paket Wisata Toba') }}</span>
                            <svg class="w-4 h-4 stroke-[3] transition-transform duration-200" :class="openPkg && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 9l-7 7-7-7"/></svg>
                            <span class="absolute left-0 -bottom-0.5 h-[3px] rounded-full bg-toba-green transition-all duration-300 {{ $isPkg ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                        </a>
                        <!-- pt-3 jadi jembatan hover supaya menu tidak tertutup saat kursor turun -->
                        <div x-show="openPkg" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1.5"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute left-0 top-full pt-3 w-64 z-[200]">
                            <div class="bg-white rounded-xl shadow-xl shadow-slate-900/10 ring-1 ring-slate-900/5 py-2 overflow-hidden">
                                <a href="/tour/packages" class="block px-4 py-2.5 text-sm font-bold text-slate-700 border-l-[3px] border-transparent hover:border-toba-green hover:bg-toba-green/5 hover:text-toba-green transition-all duration-200">{{ __('Semua Paket Tour') }}</a>
                                <a href="/tour/packages?type=private" class="block px-4 py-2.5 text-sm font-bold text-slate-700 border-l-[3px] border-transparent hover:border-toba-green hover:bg-toba-green/5 hover:text-toba-green transition-all duration-200">{{ __('Private VIP Tour') }}</a>
                                <a href="/tour/packages?type=family" class="block px-4 py-2.5 text-sm font-bold text-slate-700 border-l-[3px] border-transparent hover:border-toba-green hover:bg-toba-green/5 hover:text-toba-green transition-all duration-200">{{ __('Paket Rombongan Keluarga') }}</a>
                            </div>
                        </div>
                    </div>

                    @foreach($navLinks as $link)
                        <a href="{{ $link['url'] }}" @if($link['active']) aria-current="page" @endif
                           class="group relative py-2 transition-colors duration-200 {{ $link['active'] ? 'text-toba-green' : 'hover:text-toba-green' }}">
                            {{ $link['label'] }}
                            <span class="absolute left-0 -bottom-0.5 h-[3px] rounded-full bg-toba-green transition-all duration-300 {{ $link['active'] ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                        </a>
                    @endforeach
                </div>

                <!-- CTA Desktop -->
                <div class="hidden lg:flex items-center shrink-0">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noopener noreferrer"
                       class="group bg-toba-orange hover:bg-toba-orange-dark text-white pl-6 pr-1.5 py-1.5 rounded-full font-black text-[13px] tracking-wider uppercase flex items-center gap-3 shadow-md shadow-toba-orange/25 hover:shadow-lg hover:shadow-toba-orange/35 hover:-translate-y-0.5 transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-toba-orange/50 focus-visible:ring-offset-2">
                        <span>{{ __('HUBUNGI KAMI!') }}</span>
                        <span class="w-8 h-8 bg-[#0088cc] rounded-full flex items-center justify-center text-white shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <x-icon name="whatsapp" class="w-4 h-4" />
                        </span>
                    </a>
                </div>

                <!-- Aksi Mobile -->
                <div class="lg:hidden flex items-center gap-2">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noopener noreferrer"
                       aria-label="{{ __('HUBUNGI KAMI!') }}"
                       class="w-9 h-9 rounded-full bg-toba-orange text-white flex items-center justify-center shadow-md shadow-toba-orange/25 active:scale-95 transition-transform">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                    </a>
                    <button @click="isMenuOpen = true" type="button"
                            aria-label="{{ __('Buka menu') }}" :aria-expanded="isMenuOpen"
                            class="p-2 -mr-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-toba-green/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
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
                <span class="text-2xl font-black text-toba-green leading-none">SUJAI<span class="text-toba-green italic font-bold ml-1" style="font-family: 'Brush Script MT', 'Segoe Script', 'Lucida Handwriting', cursive;">Tour</span></span>
                <button @click="isMenuOpen = false" type="button" aria-label="{{ __('Tutup menu') }}"
                        class="w-9 h-9 rounded-full text-slate-500 hover:bg-slate-100 hover:text-slate-800 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4">
                @php
                    $mobileLinks = array_merge(
                        [
                            ['label' => __('Home'), 'url' => '/', 'active' => request()->is('/')],
                            ['label' => __('Paket Wisata Toba'), 'url' => '/tour/packages', 'active' => request()->is('tour/packages*')],
                        ],
                        $navLinks
                    );
                @endphp
                @foreach($mobileLinks as $link)
                    <a href="{{ $link['url'] }}" @if($link['active']) aria-current="page" @endif
                       class="flex items-center justify-between px-3 py-3 rounded-xl text-base font-bold transition-colors {{ $link['active'] ? 'bg-toba-green/5 text-toba-green' : 'text-slate-800 hover:bg-slate-50 hover:text-toba-green' }}">
                        <span>{{ $link['label'] }}</span>
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
            </nav>

            <div class="px-6 py-5 border-t border-slate-100 space-y-4" style="padding-bottom: calc(1.25rem + env(safe-area-inset-bottom));">
                @if(count($socials))
                    <div class="flex items-center justify-center gap-3">
                        @foreach($socials as $name => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($name) }}"
                               class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 hover:bg-toba-green hover:text-white flex items-center justify-center transition-colors duration-200">
                                <x-icon :name="$name" class="w-4 h-4" />
                            </a>
                        @endforeach
                    </div>
                @endif
                <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noopener noreferrer"
                   class="w-full py-3.5 bg-toba-orange hover:bg-toba-orange-dark text-white rounded-full font-black text-sm text-center uppercase tracking-wider flex items-center justify-center gap-2.5 shadow-lg shadow-toba-orange/30 transition-colors">
                    <span>{{ __('HUBUNGI KAMI!') }}</span>
                    <x-icon name="whatsapp" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </div>
</header>
