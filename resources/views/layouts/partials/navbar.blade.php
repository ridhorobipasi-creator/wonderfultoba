<header 
    x-data="{ 
        isMenuOpen: false, 
        isScrolled: false,
        showLoginModal: false,
        isRegister: false,
        formLoading: false,
        contact: { 
            phone: @json(\App\Helpers\ContactHelper::whatsappDisplay()), 
            email: '{{ $siteSettings['general']['contact_email'] ?? 'info@sujailaketoba.com' }}', 
            whatsapp: @json(\App\Helpers\ContactHelper::whatsappDigits()) 
        }
    }"
    x-init="
        window.addEventListener('scroll', () => isScrolled = window.scrollY > 20);
        isScrolled = window.scrollY > 20;
    "
    class="relative z-[100] w-full"
>
    <!-- Top Bar (Orange Zaza Style) -->
    <div class="hidden sm:block bg-[#ea8c1e] text-white py-2 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-xs font-semibold">
            <!-- Left Info / Address -->
            <div class="flex items-center space-x-6">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Jl. Danau Toba No. 12C, Medan & Samosir, Sumatera Utara</span>
                </span>
            </div>
            
            <!-- Right Contacts & Language -->
            <div class="flex items-center space-x-6">
                <a :href="'tel:' + contact.phone" class="flex items-center hover:opacity-80 transition-opacity">
                    <svg class="w-3.5 h-3.5 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span x-text="contact.phone"></span>
                </a>
                
                <!-- Social Icons -->
                <div class="flex items-center space-x-3 text-sm">
                    <a href="#" aria-label="Facebook" class="hover:opacity-80 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="TikTok" class="hover:opacity-80 transition"><i class="fab fa-tiktok"></i></a>
                    <a href="#" aria-label="YouTube" class="hover:opacity-80 transition"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="Instagram" class="hover:opacity-80 transition"><i class="fab fa-instagram"></i></a>
                </div>

                <!-- Language Selector -->
                <div x-data="{ open: false }" class="relative z-[110]">
                    <button @click="open = !open" class="flex items-center hover:opacity-80 transition-opacity focus:outline-none py-0.5 text-xs font-bold uppercase">
                        <span class="mr-1 flex items-center gap-1">
                            @if(session('locale', 'my') === 'my')
                                🇲🇾 MYR
                            @elseif(session('locale', 'my') === 'id')
                                🇮🇩 IDR
                            @else
                                🇸🇬 SGD
                            @endif
                        </span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 border border-slate-100 text-xs font-semibold z-[200]">
                        <a href="{{ route('change-locale', 'my') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 hover:text-[#ea8c1e] transition-colors {{ session('locale', 'my') === 'my' ? 'bg-orange-50 text-[#ea8c1e]' : '' }}">🇲🇾 MYR (Melayu)</a>
                        <a href="{{ route('change-locale', 'id') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 hover:text-[#ea8c1e] transition-colors {{ session('locale', 'my') === 'id' ? 'bg-orange-50 text-[#ea8c1e]' : '' }}">🇮🇩 IDR (Indonesia)</a>
                        <a href="{{ route('change-locale', 'en') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 hover:text-[#ea8c1e] transition-colors {{ session('locale', 'my') === 'en' ? 'bg-orange-50 text-[#ea8c1e]' : '' }}">🇸🇬 SGD (English)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Nav (Clean White Background) -->
    <nav class="bg-white py-3 md:py-4 shadow-sm border-b border-slate-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo Zaza Style -->
                <a href="/" class="flex items-center group w-auto shrink-0 mr-4 md:mr-8">
                    @php
                        $brandName = $siteSettings['general']['site_name'] ?? 'Sujai Laketoba';
                    @endphp
                    <div class="flex items-center gap-2">
                        <span class="text-2xl md:text-3xl font-black tracking-tight text-[#ea8c1e] uppercase">SUJAI<span class="text-slate-800 font-extrabold italic lowercase">toba</span></span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden lg:flex items-center space-x-7 shrink-0 text-sm font-bold text-slate-700">
                    <a href="/" class="hover:text-[#ea8c1e] transition duration-200 {{ request()->is('/') ? 'text-[#ea8c1e]' : '' }}">{{ __('Beranda') }}</a>
                    <a href="/tour/packages" class="hover:text-[#ea8c1e] transition duration-200 {{ request()->is('tour/packages*') ? 'text-[#ea8c1e]' : '' }}">{{ __('Paket Wisata') }}</a>
                    <a href="/tour/gallery" class="hover:text-[#ea8c1e] transition duration-200 {{ request()->is('tour/gallery') ? 'text-[#ea8c1e]' : '' }}">{{ __('Galeri') }}</a>
                    <a href="/tour/blog" class="hover:text-[#ea8c1e] transition duration-200 {{ request()->is('tour/blog*') ? 'text-[#ea8c1e]' : '' }}">{{ __('Blog') }}</a>
                    <a href="/about" class="hover:text-[#ea8c1e] transition duration-200 {{ request()->is('about') ? 'text-[#ea8c1e]' : '' }}">{{ __('Tentang Kami') }}</a>
                    <a href="{{ route('booking.track.form') }}" class="hover:text-[#ea8c1e] transition duration-200 {{ request()->is('track-booking*') ? 'text-[#ea8c1e]' : '' }}">{{ __('Lacak Booking') }}</a>
                </div>

                <!-- Desktop Action CTA (Orange Pill Button with WA icon) -->
                <div class="hidden lg:flex items-center shrink-0 ml-6">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noreferrer" 
                        class="bg-[#ea8c1e] hover:bg-[#d87c14] text-white px-6 py-2.5 rounded-full font-bold text-xs tracking-wider transition duration-300 shadow-md hover:shadow-lg flex items-center gap-2 uppercase"
                    >
                        <span>{{ __('HUBUNGI KAMI!') }}</span>
                        <div class="w-6 h-6 bg-[#00a884] rounded-full flex items-center justify-center text-white">
                            <x-icon name="whatsapp" class="w-3.5 h-3.5" />
                        </div>
                    </a>
                </div>

                <!-- Mobile: Language Selector + WA Button + Menu Toggle -->
                <div class="lg:hidden flex items-center gap-2">
                    <div x-data="{ open: false }" class="relative z-[110]">
                        <button @click="open = !open" aria-label="Pilih Bahasa" class="w-10 h-10 rounded-lg flex items-center justify-center text-sm bg-orange-50 text-[#ea8c1e] font-bold border border-orange-200 transition">
                            @if(session('locale', 'my') === 'my') 🇲🇾
                            @elseif(session('locale', 'my') === 'id') 🇮🇩
                            @else 🇸🇬
                            @endif
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-44 bg-white text-slate-800 rounded-xl shadow-lg py-2 border border-slate-100 text-xs font-semibold z-[200]">
                            <a href="{{ route('change-locale', 'my') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 transition-colors {{ session('locale', 'my') === 'my' ? 'bg-orange-50 text-[#ea8c1e]' : '' }}">🇲🇾 MYR (Melayu)</a>
                            <a href="{{ route('change-locale', 'id') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 transition-colors {{ session('locale', 'my') === 'id' ? 'bg-orange-50 text-[#ea8c1e]' : '' }}">🇮🇩 IDR (Indonesia)</a>
                            <a href="{{ route('change-locale', 'en') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 transition-colors {{ session('locale', 'my') === 'en' ? 'bg-orange-50 text-[#ea8c1e]' : '' }}">🇸🇬 SGD (English)</a>
                        </div>
                    </div>
                    
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noreferrer"
                        class="w-10 h-10 rounded-lg bg-[#ea8c1e] text-white flex items-center justify-center transition shadow-sm"
                        aria-label="Hubungi via WhatsApp">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                    </a>
                    
                    <button @click="isMenuOpen = true" aria-label="Buka menu navigasi"
                        class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Premium Mobile Nav Drawer -->
        <div 
            x-show="isMenuOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="lg:hidden fixed inset-0 bg-white z-[150] flex flex-col"
        >
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50">
                <span class="text-xl font-black text-[#ea8c1e] uppercase">SUJAI<span class="text-slate-800 font-extrabold lowercase italic">toba</span></span>
                <button @click="isMenuOpen = false" aria-label="Close navigation menu" class="w-9 h-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-700 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-8 space-y-6">
                <div class="space-y-4 font-bold text-slate-800 text-lg">
                    <a href="/" @click="isMenuOpen = false" class="block py-2 hover:text-[#ea8c1e] transition-colors border-b border-slate-100">{{ __('Beranda') }}</a>
                    <a href="/tour/packages" @click="isMenuOpen = false" class="block py-2 hover:text-[#ea8c1e] transition-colors border-b border-slate-100">{{ __('Paket Wisata') }}</a>
                    <a href="/tour/gallery" @click="isMenuOpen = false" class="block py-2 hover:text-[#ea8c1e] transition-colors border-b border-slate-100">{{ __('Galeri') }}</a>
                    <a href="/tour/blog" @click="isMenuOpen = false" class="block py-2 hover:text-[#ea8c1e] transition-colors border-b border-slate-100">{{ __('Blog') }}</a>
                    <a href="/about" @click="isMenuOpen = false" class="block py-2 hover:text-[#ea8c1e] transition-colors border-b border-slate-100">{{ __('Tentang Kami') }}</a>
                    <a href="{{ route('booking.track.form') }}" @click="isMenuOpen = false" class="block py-2 hover:text-[#ea8c1e] transition-colors border-b border-slate-100">{{ __('Lacak Booking') }}</a>
                </div>

                <div class="pt-4">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" class="w-full py-3.5 bg-[#ea8c1e] text-white rounded-full font-bold text-sm uppercase tracking-wider flex items-center justify-center gap-2 shadow-md">
                        <x-icon name="whatsapp" class="w-5 h-5" />
                        {{ __('Hubungi Kami!') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Horizontal Scrollable Sub-Navbar -->
        <div class="lg:hidden bg-slate-50 border-t border-slate-200">
            <div class="overflow-x-auto no-scrollbar snap-x overscroll-x-contain">
                <ul class="flex items-center whitespace-nowrap px-3 py-1 gap-1 text-xs font-bold text-slate-700">
                    <li><a href="/" class="inline-block px-3 py-2 text-[#ea8c1e]">{{ __('Beranda') }}</a></li>
                    <li><a href="/tour/packages" class="inline-block px-3 py-2 hover:text-[#ea8c1e]">{{ __('Paket Wisata') }}</a></li>
                    <li><a href="/tour/gallery" class="inline-block px-3 py-2 hover:text-[#ea8c1e]">{{ __('Galeri') }}</a></li>
                    <li><a href="/tour/blog" class="inline-block px-3 py-2 hover:text-[#ea8c1e]">{{ __('Blog') }}</a></li>
                    <li><a href="/about" class="inline-block px-3 py-2 hover:text-[#ea8c1e]">{{ __('Tentang Kami') }}</a></li>
                    <li><a href="{{ route('booking.track.form') }}" class="inline-block px-3 py-2 hover:text-[#ea8c1e]">{{ __('Lacak Booking') }}</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

