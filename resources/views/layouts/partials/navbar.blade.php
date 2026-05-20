<header 
    x-data="{ 
        isMenuOpen: false, 
        isScrolled: false,
        showLoginModal: false,
        isRegister: false,
        formLoading: false,
        contact: { 
            phone: '{{ $siteSettings['general']['wa_number'] ?? '+62 813-2388-8207' }}', 
            email: '{{ $siteSettings['general']['contact_email'] ?? 'info@sujailaketoba.com' }}', 
            whatsapp: '{{ preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6281323888207') }}' 
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
        class="hidden sm:block bg-slate-900 text-white py-2"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-[11px] font-semibold tracking-wider uppercase">
            <div class="flex items-center space-x-6">
                <a :href="'tel:' + contact.phone" class="flex items-center hover:text-toba-accent transition-colors">
                    <svg class="w-3 h-3 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span x-text="contact.phone"></span>
                </a>
                <a :href="'mailto:' + contact.email" class="flex items-center hover:text-toba-accent transition-colors">
                    <svg class="w-3 h-3 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span x-text="contact.email"></span>
                </a>
            </div>
            <div class="flex items-center space-x-6">
                <!-- Language & Currency Toggle Dropdown -->
                <div x-data="{ open: false }" class="relative z-[110]">
                    <button @click="open = !open" class="flex items-center hover:text-toba-accent text-white transition-colors focus:outline-none py-1 text-[11px] font-black tracking-wider uppercase">
                        <span class="mr-1.5 flex items-center gap-1.5">
                            @if(session('locale', 'my') === 'my')
                                <span class="text-sm">🇲🇾</span> MYR (Melayu)
                            @elseif(session('locale', 'my') === 'id')
                                <span class="text-sm">🇮🇩</span> IDR (Indonesia)
                            @else
                                <span class="text-sm">🇸🇬</span> SGD (English)
                            @endif
                        </span>
                        <svg class="w-3 h-3 text-white transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-52 bg-slate-800 rounded-xl shadow-xl py-2 border border-slate-700 text-[11px] z-[200]">
                        <a href="{{ route('change-locale', 'my') }}" class="flex items-center px-4 py-2.5 text-white hover:bg-toba-green/20 hover:text-toba-accent transition-colors {{ session('locale', 'my') === 'my' ? 'bg-toba-green/10 text-toba-accent font-bold' : '' }}">
                            <span class="mr-2 text-sm">🇲🇾</span> MYR (Melayu - Malaysia)
                        </a>
                        <a href="{{ route('change-locale', 'id') }}" class="flex items-center px-4 py-2.5 text-white hover:bg-toba-green/20 hover:text-toba-accent transition-colors {{ session('locale', 'my') === 'id' ? 'bg-toba-green/10 text-toba-accent font-bold' : '' }}">
                            <span class="mr-2 text-sm">🇮🇩</span> IDR (Rupiah - Indonesia)
                        </a>
                        <a href="{{ route('change-locale', 'en') }}" class="flex items-center px-4 py-2.5 text-white hover:bg-toba-green/20 hover:text-toba-accent transition-colors {{ session('locale', 'my') === 'en' ? 'bg-toba-green/10 text-toba-accent font-bold' : '' }}">
                            <span class="mr-2 text-sm">🇸🇬</span> SGD (Dollar - English)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Nav -->
    <nav 
        :class="isScrolled ? 'bg-white/95 backdrop-blur-md py-3 shadow-sm border-slate-200/50' : 'bg-transparent py-5 border-transparent'"
        class="transition-all duration-300 border-b"
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
                            class="h-10 w-auto object-contain transition-all"
                        />
                        <img 
                            src="{{ $logoDark ?? $logoLight }}" 
                            alt="{{ $brandName }}" 
                            x-show="isScrolled"
                            class="h-10 w-auto object-contain transition-all"
                        />
                    @else
                        <div class="flex items-center space-x-2 md:space-x-3">
                            <div class="w-9 h-9 md:w-10 md:h-10 bg-toba-green rounded-lg flex items-center justify-center text-white font-bold text-lg md:text-xl shrink-0 overflow-hidden">
                                S
                            </div>
                            <div class="flex flex-col whitespace-nowrap">
                                <span 
                                    :class="isScrolled ? 'text-slate-900' : 'text-white'"
                                    class="text-base md:text-lg font-bold leading-none tracking-tight transition-colors uppercase"
                                >
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
                        :class="isScrolled ? ({{ request()->is('/') ? 'true' : 'false' }} ? 'text-toba-green' : 'text-slate-600 hover:text-toba-green') : ({{ request()->is('/') ? 'true' : 'false' }} ? 'text-toba-accent' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Beranda') }}</a>

                    <a href="/tour/packages" 
                        :class="isScrolled ? ({{ request()->is('tour/packages*') ? 'true' : 'false' }} ? 'text-toba-green' : 'text-slate-600 hover:text-toba-green') : ({{ request()->is('tour/packages*') ? 'true' : 'false' }} ? 'text-toba-accent' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Paket Wisata') }}</a>
                    
                    <a href="/tour/gallery" 
                        :class="isScrolled ? ({{ request()->is('tour/gallery') ? 'true' : 'false' }} ? 'text-toba-green' : 'text-slate-600 hover:text-toba-green') : ({{ request()->is('tour/gallery') ? 'true' : 'false' }} ? 'text-toba-accent' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Galeri') }}</a>
                    
                    <a href="/tour/blog" 
                        :class="isScrolled ? ({{ request()->is('tour/blog*') ? 'true' : 'false' }} ? 'text-toba-green' : 'text-slate-600 hover:text-toba-green') : ({{ request()->is('tour/blog*') ? 'true' : 'false' }} ? 'text-toba-accent' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Blog') }}</a>
                    
                    <a href="/about" 
                        :class="isScrolled ? ({{ request()->is('about') ? 'true' : 'false' }} ? 'text-toba-green' : 'text-slate-600 hover:text-toba-green') : ({{ request()->is('about') ? 'true' : 'false' }} ? 'text-toba-accent' : 'text-white/80 hover:text-white')" 
                        class="font-semibold tracking-wider transition-all duration-200 whitespace-nowrap text-[11px] uppercase">{{ __('Tentang Kami') }}</a>
                </div>

                <!-- Desktop Actions -->
                <div class="hidden lg:flex items-center space-x-6 shrink-0 ml-8">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noreferrer" 
                        :class="isScrolled ? 'border-toba-green text-toba-green hover:bg-toba-green hover:text-white' : 'border-white text-white hover:bg-white hover:text-slate-900'"
                        class="px-5 py-2 rounded-lg border font-semibold text-xs tracking-wider transition-all flex items-center space-x-2 whitespace-nowrap uppercase"
                    >
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>{{ __('HUBUNGI KAMI') }}</span>
                    </a>
                </div>

                <!-- Mobile Menu Toggle -->
                <div class="lg:hidden flex items-center">
                    <button @click="isMenuOpen = !isMenuOpen" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white bg-black/5 hover:bg-black/10 rounded-xl'" class="p-2 transition-all">
                        <svg x-show="!isMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                        <svg x-show="isMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
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
            class="lg:hidden fixed inset-0 bg-slate-900/98 backdrop-blur-3xl z-[150] flex flex-col"
        >
            <!-- Mobile Header inside Menu -->
            <div class="flex justify-between items-center px-6 py-5 border-b border-white/5">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-toba-green rounded-lg flex items-center justify-center text-white font-bold text-lg">S</div>
                    <span class="text-white font-bold uppercase tracking-tight text-sm">Sujai <span class="text-toba-green">Laketoba</span></span>
                </div>
                <button @click="isMenuOpen = false" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-8 py-10 space-y-10">
                <!-- Navigation Sections -->
                <div class="space-y-8">
                    <div>
                        <span class="text-toba-accent font-bold text-[9px] uppercase tracking-[0.3em] mb-4 block">{{ __('Eksplorasi') }}</span>
                        <div class="space-y-4">
                            <a href="/" @click="isMenuOpen = false" class="block text-3xl font-semibold text-white tracking-tight hover:text-toba-green transition-colors">{{ __('Beranda') }}</a>
                            <a href="/tour/packages" @click="isMenuOpen = false" class="block text-3xl font-semibold text-white tracking-tight hover:text-toba-green transition-colors">{{ __('Paket Wisata') }}</a>
                        </div>
                    </div>

                    <div>
                        <span class="text-white/40 font-bold text-[9px] uppercase tracking-[0.3em] mb-4 block">{{ __('Informasi') }}</span>
                        <div class="grid grid-cols-1 gap-4">
                            <a href="/tour/gallery" @click="isMenuOpen = false" class="flex items-center gap-4 text-white/80 font-semibold text-base">
                                <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-toba-green"><i class="fas fa-images"></i></div>
                                {{ __('Galeri') }}
                            </a>
                            <a href="/tour/blog" @click="isMenuOpen = false" class="flex items-center gap-4 text-white/80 font-semibold text-base">
                                <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-toba-green"><i class="fas fa-newspaper"></i></div>
                                {{ __('Blog') }}
                            </a>
                            <a href="/about" @click="isMenuOpen = false" class="flex items-center gap-4 text-white/80 font-semibold text-base">
                                <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-toba-green"><i class="fas fa-info-circle"></i></div>
                                {{ __('Tentang Kami') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile Language Selection -->
                <div class="pt-6 border-t border-white/5">
                    <span class="text-white/40 font-bold text-[9px] uppercase tracking-[0.3em] mb-3 block">{{ __('Pilih Bahasa & Mata Uang') }}</span>
                    <div class="grid grid-cols-3 gap-3">
                        <a href="{{ route('change-locale', 'my') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all {{ session('locale', 'my') === 'my' ? 'border-toba-green bg-toba-green/10 text-white font-bold' : 'border-white/5 text-white/60 hover:bg-white/5' }}">
                            <span class="text-xl mb-1">🇲🇾</span>
                            <span class="text-[9px] font-bold">MYR (RM)</span>
                        </a>
                        <a href="{{ route('change-locale', 'id') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all {{ session('locale', 'my') === 'id' ? 'border-toba-green bg-toba-green/10 text-white font-bold' : 'border-white/5 text-white/60 hover:bg-white/5' }}">
                            <span class="text-xl mb-1">🇮🇩</span>
                            <span class="text-[9px] font-bold">IDR (Rp)</span>
                        </a>
                        <a href="{{ route('change-locale', 'en') }}" class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all {{ session('locale', 'my') === 'en' ? 'border-toba-green bg-toba-green/10 text-white font-bold' : 'border-white/5 text-white/60 hover:bg-white/5' }}">
                            <span class="text-xl mb-1">🇸🇬</span>
                            <span class="text-[9px] font-bold">SGD (S$)</span>
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-white/5">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" class="w-full py-4 bg-white text-slate-900 rounded-xl font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-2 shadow-sm">
                        <i class="fab fa-whatsapp text-lg"></i>
                        {{ __('Hubungi Kami') }}
                    </a>
                </div>
            </div>

            <!-- Footer Decor -->
            <div class="p-6 text-center">
                <p class="text-white/20 text-[8px] font-bold uppercase tracking-[0.4em]">&copy; Sujai Laketoba Premium Experience</p>
            </div>
        </div>
    </nav>


</header>

