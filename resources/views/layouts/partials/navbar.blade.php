<header 
    x-data="{ 
        isMenuOpen: false, 
        isScrolled: false,
        showLoginModal: false,
        isRegister: false,
        formLoading: false,
        contact: { 
            phone: '{{ $siteSettings['general']['contact_whatsapp'] ?? '+62 813-2388-8207' }}', 
            email: '{{ $siteSettings['general']['contact_email'] ?? 'info@sujailaketoba.com' }}', 
            whatsapp: '{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['contact_whatsapp'] ?? '6282277848855') }}' 
        }
    }"
    x-init="
        window.addEventListener('scroll', () => isScrolled = window.scrollY > 20);
        isScrolled = window.scrollY > 20;
    "
    class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300"
>
    <!-- Top Bar -->
    <div 
        x-show="!isScrolled"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-full"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-full"
        class="hidden sm:block bg-slate-50 text-slate-600 py-2 border-b border-slate-200"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-[11px] font-medium tracking-wide uppercase">
            <div class="flex items-center space-x-6">
                <a :href="'tel:' + contact.phone" class="flex items-center hover:text-secondary transition-colors">
                    <svg class="w-3.5 h-3.5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span x-text="contact.phone"></span>
                </a>
                <a :href="'mailto:' + contact.email" class="flex items-center hover:text-secondary transition-colors">
                    <svg class="w-3.5 h-3.5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span x-text="contact.email"></span>
                </a>
            </div>
            <div class="flex items-center space-x-6">
                <!-- Language & Currency Toggle Dropdown -->
                <div x-data="{ open: false }" class="relative z-[110]">
                    <button @click="open = !open" class="flex items-center hover:text-secondary text-slate-600 transition-colors focus:outline-none py-1 text-[11px] font-semibold tracking-wider uppercase">
                        <span class="mr-1.5 flex items-center gap-1.5">
                            @if(session('locale', 'my') === 'my')
                                <span class="text-sm">🇲🇾</span> MYR (Melayu)
                            @elseif(session('locale', 'my') === 'id')
                                <span class="text-sm">🇮🇩</span> IDR (Indonesia)
                            @else
                                <span class="text-sm">🇸🇬</span> SGD (English)
                            @endif
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg py-2 border border-slate-200 text-[11px] z-[200]">
                        <a href="{{ route('change-locale', 'my') }}" class="flex items-center px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors {{ session('locale', 'my') === 'my' ? 'bg-slate-50 text-primary font-semibold' : '' }}">
                            <span class="mr-2 text-sm">🇲🇾</span> MYR (Melayu)
                        </a>
                        <a href="{{ route('change-locale', 'id') }}" class="flex items-center px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors {{ session('locale', 'my') === 'id' ? 'bg-slate-50 text-primary font-semibold' : '' }}">
                            <span class="mr-2 text-sm">🇮🇩</span> IDR (Rupiah - Indonesia)
                        </a>
                        <a href="{{ route('change-locale', 'en') }}" class="flex items-center px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors {{ session('locale', 'my') === 'en' ? 'bg-slate-50 text-primary font-semibold' : '' }}">
                            <span class="mr-2 text-sm">🇸🇬</span> SGD (Dollar - English)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Nav -->
    <nav 
        :class="isScrolled ? 'bg-white/80 backdrop-blur-xl py-3 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-b border-slate-200/50' : 'bg-transparent py-5 border-transparent'"
        class="transition-all duration-500"
    >
        <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center group w-auto shrink-0 mr-4 md:mr-8">
                    @php
                        $logoLight = $siteSettings['general']['logo_light_url'] ?? null;
                        $logoDark = $siteSettings['general']['logo_dark_url'] ?? null;
                        $brandName = $siteSettings['general']['site_name'] ?? 'Sujai Laketoba';
                    @endphp

                    @if($logoLight)
                        <img
                            src="{{ $logoLight }}"
                            alt="{{ $brandName }}"
                            x-show="!isScrolled"
                            fetchpriority="high"
                            class="h-10 w-auto object-contain transition-all"
                        />
                        <img
                            src="{{ $logoDark ?? $logoLight }}"
                            alt="{{ $brandName }}"
                            x-show="isScrolled"
                            loading="lazy"
                            decoding="async"
                            class="h-10 w-auto object-contain transition-all"
                        />
                    @else
                        <div class="flex items-center space-x-2 md:space-x-3">
                            <div class="w-9 h-9 md:w-10 md:h-10 bg-primary rounded-lg flex items-center justify-center text-white font-bold text-lg md:text-xl shrink-0 overflow-hidden">
                                S
                            </div>
                            <div class="flex flex-col whitespace-nowrap">
                                <span :class="isScrolled ? 'text-slate-900' : 'text-white'" class="text-base md:text-lg font-bold leading-none tracking-tight transition-colors uppercase">
                                    {{ $brandName }}
                                </span>
                                <span 
                                    :class="isScrolled ? 'text-slate-400' : 'text-slate-300'"
                                    class="hidden md:block text-[8px] md:text-[9px] font-semibold tracking-[0.25em] uppercase mt-1 transition-colors"
                                >
                                    {{ $siteSettings['general']['site_tagline'] ?? 'Sumatera Utara Travel Experience' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </a>

                <!-- Desktop Nav -->
                <div class="hidden lg:flex items-center space-x-8 shrink-0 w-max">
                    <a href="/" 
                        :class="isScrolled ? ({{ request()->is('/') ? 'true' : 'false' }} ? 'text-primary font-semibold' : 'text-slate-600 hover:text-secondary') : ({{ request()->is('/') ? 'true' : 'false' }} ? 'text-secondary font-semibold' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Beranda') }}</a>

                    <a href="/tour/packages" 
                        :class="isScrolled ? ({{ request()->is('tour/packages*') ? 'true' : 'false' }} ? 'text-primary font-semibold' : 'text-slate-600 hover:text-secondary') : ({{ request()->is('tour/packages*') ? 'true' : 'false' }} ? 'text-secondary font-semibold' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Paket Wisata') }}</a>
                    
                    <a href="/tour/gallery" 
                        :class="isScrolled ? ({{ request()->is('tour/gallery') ? 'true' : 'false' }} ? 'text-primary font-semibold' : 'text-slate-600 hover:text-secondary') : ({{ request()->is('tour/gallery') ? 'true' : 'false' }} ? 'text-secondary font-semibold' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Galeri') }}</a>
                    
                    <a href="/tour/blog" 
                        :class="isScrolled ? ({{ request()->is('tour/blog*') ? 'true' : 'false' }} ? 'text-primary font-semibold' : 'text-slate-600 hover:text-secondary') : ({{ request()->is('tour/blog*') ? 'true' : 'false' }} ? 'text-secondary font-semibold' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Blog') }}</a>
                    
                    <a href="/about"
                        :class="isScrolled ? ({{ request()->is('about') ? 'true' : 'false' }} ? 'text-primary font-semibold' : 'text-slate-600 hover:text-secondary') : ({{ request()->is('about') ? 'true' : 'false' }} ? 'text-secondary font-semibold' : 'text-white/80 hover:text-white')"
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Tentang Kami') }}</a>

                    <a href="{{ route('booking.track.form') }}"
                        :class="isScrolled ? ({{ request()->is('track-booking*') ? 'true' : 'false' }} ? 'text-primary font-semibold' : 'text-slate-600 hover:text-secondary') : ({{ request()->is('track-booking*') ? 'true' : 'false' }} ? 'text-secondary font-semibold' : 'text-white/80 hover:text-white')"
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Lacak Booking') }}</a>
                </div>

                <!-- Desktop Actions -->
                <div class="hidden lg:flex items-center space-x-6 shrink-0 ml-8">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noreferrer" 
                        :class="isScrolled ? 'border-primary text-primary hover:bg-primary hover:text-white' : 'border-white/30 text-white hover:bg-white hover:text-slate-900'"
                        class="px-5 py-2 rounded-lg border font-semibold text-xs tracking-wider transition-all flex items-center space-x-2 whitespace-nowrap uppercase"
                    >
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>{{ __('HUBUNGI KAMI') }}</span>
                    </a>
                </div>

                <!-- Mobile: Language Selector (icon only) + WA Button -->
                <div class="lg:hidden flex items-center gap-2">
                    <!-- Language Picker compact -->
                    <div x-data="{ open: false }" class="relative z-[110]">
                        <button @click="open = !open" aria-label="Pilih Bahasa" :class="isScrolled ? 'text-slate-600 bg-slate-100' : 'text-white bg-white/10'" class="w-8 h-8 rounded-lg flex items-center justify-center text-sm transition-all">
                            @if(session('locale', 'my') === 'my') 🇲🇾
                            @elseif(session('locale', 'my') === 'id') 🇮🇩
                            @else 🇸🇬
                            @endif
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg py-2 border border-slate-200 text-[11px] z-[200]">
                            <a href="{{ route('change-locale', 'my') }}" class="flex items-center px-4 py-2.5 text-slate-700 hover:bg-slate-50 transition-colors {{ session('locale', 'my') === 'my' ? 'bg-slate-50 text-primary font-semibold' : '' }}">🇲🇾 MYR (Melayu)</a>
                            <a href="{{ route('change-locale', 'id') }}" class="flex items-center px-4 py-2.5 text-slate-700 hover:bg-slate-50 transition-colors {{ session('locale', 'my') === 'id' ? 'bg-slate-50 text-primary font-semibold' : '' }}">🇮🇩 IDR (Indonesia)</a>
                            <a href="{{ route('change-locale', 'en') }}" class="flex items-center px-4 py-2.5 text-slate-700 hover:bg-slate-50 transition-colors {{ session('locale', 'my') === 'en' ? 'bg-slate-50 text-primary font-semibold' : '' }}">🇸🇬 SGD (English)</a>
                        </div>
                    </div>
                    <!-- WhatsApp CTA compact -->
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noreferrer"
                        :class="isScrolled ? 'bg-primary text-white' : 'bg-white/15 text-white border border-white/30'"
                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-all"
                        aria-label="Hubungi via WhatsApp">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Premium Full-screen Mobile Nav -->
        <div 
            x-show="isMenuOpen" 
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-110"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-110"
            class="lg:hidden fixed inset-0 bg-white backdrop-blur-3xl z-[150] flex flex-col"
        >
            <!-- Mobile Header inside Menu -->
            <div class="flex justify-between items-center px-6 py-5 border-b border-slate-200">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center text-white font-bold text-lg">S</div>
                    <span class="text-slate-900 font-bold uppercase tracking-tight text-sm">Sujai <span class="text-secondary">Laketoba</span></span>
                </div>
                <button @click="isMenuOpen = false" aria-label="Close navigation menu" class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-8 py-10 space-y-10">
                <!-- Navigation Sections -->
                <div class="space-y-8">
                    <div>
                        <span class="text-secondary font-bold text-[9px] uppercase tracking-[0.3em] mb-4 block">{{ __('Eksplorasi') }}</span>
                        <div class="space-y-4">
                                    <a href="/" @click="isMenuOpen = false" class="block text-3xl font-semibold text-slate-900 tracking-tight hover:text-secondary transition-colors">{{ __('Beranda') }}</a>
                                    <a href="/tour/packages" @click="isMenuOpen = false" class="block text-3xl font-semibold text-slate-900 tracking-tight hover:text-secondary transition-colors">{{ __('Paket Wisata') }}</a>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-500 font-bold text-[9px] uppercase tracking-[0.3em] mb-4 block">{{ __('Informasi') }}</span>
                        <div class="grid grid-cols-1 gap-4">
                            <a href="/tour/gallery" @click="isMenuOpen = false" class="flex items-center gap-4 text-slate-700 font-semibold text-base">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-secondary"><span class="material-symbols-outlined text-[20px]">photo_library</span></div>
                                {{ __('Galeri') }}
                            </a>
                            <a href="/tour/blog" @click="isMenuOpen = false" class="flex items-center gap-4 text-slate-700 font-semibold text-base">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-secondary"><span class="material-symbols-outlined text-[20px]">article</span></div>
                                {{ __('Blog') }}
                            </a>
                            <a href="/about" @click="isMenuOpen = false" class="flex items-center gap-4 text-slate-700 font-semibold text-base">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-secondary"><span class="material-symbols-outlined text-[20px]">info</span></div>
                                {{ __('Tentang Kami') }}
                            </a>
                            <a href="{{ route('booking.track.form') }}" @click="isMenuOpen = false" class="flex items-center gap-4 text-slate-700 font-semibold text-base">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-secondary"><span class="material-symbols-outlined text-[20px]">map</span></div>
                                {{ __('Lacak Booking') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile Language Selection -->
                <div class="pt-6 border-t border-white/5">
                    <span class="text-slate-500 font-bold text-[9px] uppercase tracking-[0.3em] mb-3 block">{{ __('Pilih Bahasa & Mata Uang') }}</span>
                    <div class="grid grid-cols-3 gap-3">
                        <a href="{{ route('change-locale', 'my') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all {{ session('locale', 'my') === 'my' ? 'border-primary bg-primary/5 text-slate-900 font-semibold' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                            <span class="text-xl mb-1">🇲🇾</span>
                            <span class="text-[9px] font-bold">MYR (RM)</span>
                        </a>
                        <a href="{{ route('change-locale', 'id') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all {{ session('locale', 'my') === 'id' ? 'border-primary bg-primary/5 text-slate-900 font-semibold' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                            <span class="text-xl mb-1">🇮🇩</span>
                            <span class="text-[9px] font-bold">IDR (Rp)</span>
                        </a>
                        <a href="{{ route('change-locale', 'en') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all {{ session('locale', 'my') === 'en' ? 'border-primary bg-primary/5 text-slate-900 font-semibold' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                            <span class="text-xl mb-1">🇸🇬</span>
                            <span class="text-[9px] font-bold">SGD (S$)</span>
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-white/5">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" class="w-full py-4 bg-primary text-white rounded-xl font-semibold text-xs uppercase tracking-widest flex items-center justify-center gap-2 shadow-sm">
                        <x-icon name="whatsapp" class="w-5 h-5" />
                        {{ __('Hubungi Kami') }}
                    </a>
                </div>
            </div>

            <!-- Footer Decor -->
            <div class="p-6 text-center">
                <p class="text-slate-400 text-[8px] font-semibold uppercase tracking-[0.4em]">&copy; Sujai Laketoba</p>
            </div>
        </div>
    </nav>

    <!-- Mobile Horizontal Scrollable Sub-Navbar -->
    <div class="lg:hidden border-t border-slate-200/60"
        :class="isScrolled ? 'bg-white/95 backdrop-blur-xl shadow-sm' : 'bg-white/90 backdrop-blur-md'">
        <div class="overflow-x-auto no-scrollbar">
            <ul class="flex items-center whitespace-nowrap px-4 py-0 gap-0">
                <li>
                    <a href="/"
                        class="inline-flex items-center px-3.5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] transition-all border-b-2 {{ request()->is('/') ? 'text-primary border-primary' : 'text-slate-500 border-transparent hover:text-primary hover:border-primary/40' }}"
                    >{{ __('Beranda') }}</a>
                </li>
                <li>
                    <a href="/tour/packages"
                        class="inline-flex items-center px-3.5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] transition-all border-b-2 {{ request()->is('tour/packages*') ? 'text-primary border-primary' : 'text-slate-500 border-transparent hover:text-primary hover:border-primary/40' }}"
                    >{{ __('Paket Wisata') }}</a>
                </li>
                <li>
                    <a href="/tour/gallery"
                        class="inline-flex items-center px-3.5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] transition-all border-b-2 {{ request()->is('tour/gallery*') ? 'text-primary border-primary' : 'text-slate-500 border-transparent hover:text-primary hover:border-primary/40' }}"
                    >{{ __('Galeri') }}</a>
                </li>
                <li>
                    <a href="/tour/blog"
                        class="inline-flex items-center px-3.5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] transition-all border-b-2 {{ request()->is('tour/blog*') ? 'text-primary border-primary' : 'text-slate-500 border-transparent hover:text-primary hover:border-primary/40' }}"
                    >{{ __('Blog') }}</a>
                </li>
                <li>
                    <a href="/about"
                        class="inline-flex items-center px-3.5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] transition-all border-b-2 {{ request()->is('about*') ? 'text-primary border-primary' : 'text-slate-500 border-transparent hover:text-primary hover:border-primary/40' }}"
                    >{{ __('Tentang Kami') }}</a>
                </li>
                <li>
                    <a href="{{ route('booking.track.form') }}"
                        class="inline-flex items-center px-3.5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] transition-all border-b-2 {{ request()->is('track-booking*') ? 'text-primary border-primary' : 'text-slate-500 border-transparent hover:text-primary hover:border-primary/40' }}"
                    >{{ __('Lacak Booking') }}</a>
                </li>
            </ul>
        </div>
    </div>

</header>

