<header 
    x-data="{ 
        isMenuOpen: false, 
        contact: { 
            phone: @json(\App\Helpers\ContactHelper::whatsappDisplay()), 
            email: '{{ $siteSettings['general']['contact_email'] ?? 'info@sujailaketoba.com' }}', 
            whatsapp: @json(\App\Helpers\ContactHelper::whatsappDigits()) 
        }
    }"
    class="relative z-[100] w-full font-sans"
>
    <!-- 1. Topbar Oranye Presisi Zaza Tour -->
    <div class="hidden sm:block bg-[#e67e22] text-white py-1.5">
        <div class="max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-[12px] font-bold">
            <!-- Left Info Location -->
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                <span>Jl. Danau Toba No. 12C Gg Lawu, Medan & Samosir, Sumatera Utara 20111</span>
            </div>
            
            <!-- Right Social Icons & Language Dropdown -->
            <div class="flex items-center space-x-5">
                <div class="flex items-center space-x-3.5 text-[13px]">
                    <a href="#" aria-label="Facebook" class="hover:opacity-80 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="TikTok" class="hover:opacity-80 transition"><i class="fab fa-tiktok"></i></a>
                    <a href="#" aria-label="YouTube" class="hover:opacity-80 transition"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="Instagram" class="hover:opacity-80 transition"><i class="fab fa-instagram"></i></a>
                </div>

                <div x-data="{ open: false }" class="relative z-[110]">
                    <button @click="open = !open" class="flex items-center hover:opacity-80 transition-opacity focus:outline-none text-[11px] font-bold uppercase">
                        <span class="mr-1">
                            @if(session('locale', 'my') === 'my') 🇲🇾 MYR
                            @elseif(session('locale', 'my') === 'id') 🇮🇩 IDR
                            @else 🇸🇬 SGD
                            @endif
                        </span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-44 bg-white text-slate-800 rounded-md shadow-xl py-1 border border-slate-200 text-xs font-semibold z-[200]">
                        <a href="{{ route('change-locale', 'my') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 hover:text-[#e67e22] {{ session('locale', 'my') === 'my' ? 'bg-orange-50 text-[#e67e22]' : '' }}">🇲🇾 MYR (Melayu)</a>
                        <a href="{{ route('change-locale', 'id') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 hover:text-[#e67e22] {{ session('locale', 'my') === 'id' ? 'bg-orange-50 text-[#e67e22]' : '' }}">🇮🇩 IDR (Indonesia)</a>
                        <a href="{{ route('change-locale', 'en') }}" class="flex items-center px-4 py-2 hover:bg-orange-50 hover:text-[#e67e22] {{ session('locale', 'my') === 'en' ? 'bg-orange-50 text-[#e67e22]' : '' }}">🇸🇬 SGD (English)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Main Nav Putih Zaza Tour Presisi -->
    <nav class="bg-white py-3.5 shadow-sm border-b border-slate-100">
        <div class="max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo Type Zaza Tour style -->
                <a href="/" class="flex items-center shrink-0 mr-6">
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl md:text-4xl font-black tracking-tight text-[#e67e22] uppercase font-sans">SUJAI</span>
                        <span class="text-3xl md:text-4xl font-bold tracking-tight text-[#1b4372] italic font-serif" style="font-family: 'Brush Script MT', cursive, sans-serif;">Tour</span>
                    </div>
                </a>

                <!-- Nav Links Zaza Style -->
                <div class="hidden lg:flex items-center space-x-8 text-[15px] font-extrabold text-[#2c3e50]">
                    <a href="/" class="transition duration-200 {{ request()->is('/') ? 'text-[#e67e22]' : 'hover:text-[#e67e22]' }}">{{ __('Home') }}</a>
                    
                    <!-- Dropdown Paket -->
                    <div x-data="{ openPkg: false }" class="relative" @mouseleave="openPkg = false">
                        <a href="/tour/packages" @mouseenter="openPkg = true" class="flex items-center gap-1 transition duration-200 {{ request()->is('tour/packages*') ? 'text-[#e67e22]' : 'hover:text-[#e67e22]' }}">
                            <span>{{ __('Paket Wisata Toba') }}</span>
                            <svg class="w-4 h-4 text-[#e67e22] font-bold stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div x-show="openPkg" x-transition class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 border border-slate-100 z-[200]">
                            <a href="/tour/packages" class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-[#e67e22]">{{ __('Semua Paket Tour') }}</a>
                            <a href="/tour/packages?type=private" class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-[#e67e22]">{{ __('Private VIP Tour') }}</a>
                            <a href="/tour/packages?type=family" class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-[#e67e22]">{{ __('Paket Rombongan Keluarga') }}</a>
                        </div>
                    </div>

                    <a href="/tour/packages" class="transition duration-200 hover:text-[#e67e22]">{{ __('Sewa Mobil & Bus') }}</a>
                    <a href="/about" class="transition duration-200 {{ request()->is('about') ? 'text-[#e67e22]' : 'hover:text-[#e67e22]' }}">{{ __('Tentang Kami') }}</a>
                    <a href="/tour/blog" class="transition duration-200 {{ request()->is('tour/blog*') ? 'text-[#e67e22]' : 'hover:text-[#e67e22]' }}">{{ __('Blog') }}</a>
                    <a href="{{ route('booking.track.form') }}" class="transition duration-200 {{ request()->is('track-booking*') ? 'text-[#e67e22]' : 'hover:text-[#e67e22]' }}">{{ __('Kontak') }}</a>
                </div>

                <!-- CTA Button Zaza Style (Orange Pill + Blue Circle WA Icon) -->
                <div class="hidden lg:flex items-center shrink-0 ml-4">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noreferrer" 
                        class="bg-[#e67e22] hover:bg-[#d35400] text-white pl-7 pr-1.5 py-1.5 rounded-full font-black text-[13px] tracking-wider transition duration-300 shadow-sm flex items-center gap-3 uppercase"
                    >
                        <span>{{ __('HUBUNGI KAMI!') }}</span>
                        <div class="w-8 h-8 bg-[#0088cc] rounded-full flex items-center justify-center text-white shrink-0 shadow-inner">
                            <x-icon name="whatsapp" class="w-4 h-4" />
                        </div>
                    </a>
                </div>

                <!-- Mobile Navigation Toggle -->
                <div class="lg:hidden flex items-center gap-2">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" class="w-9 h-9 rounded-full bg-[#e67e22] text-white flex items-center justify-center">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                    </a>
                    <button @click="isMenuOpen = true" class="p-2 text-slate-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="isMenuOpen" x-transition class="lg:hidden fixed inset-0 bg-white z-[150] p-6 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <span class="text-2xl font-black text-[#e67e22]">SUJAI<span class="text-[#1b4372] italic font-serif">Tour</span></span>
                    <button @click="isMenuOpen = false" class="text-slate-500 text-2xl font-bold">&times;</button>
                </div>
                <div class="mt-6 space-y-4 text-base font-bold text-slate-800">
                    <a href="/" class="block hover:text-[#e67e22]">{{ __('Home') }}</a>
                    <a href="/tour/packages" class="block hover:text-[#e67e22]">{{ __('Paket Wisata Toba') }}</a>
                    <a href="/tour/packages" class="block hover:text-[#e67e22]">{{ __('Sewa Mobil & Bus') }}</a>
                    <a href="/about" class="block hover:text-[#e67e22]">{{ __('Tentang Kami') }}</a>
                    <a href="/tour/blog" class="block hover:text-[#e67e22]">{{ __('Blog') }}</a>
                    <a href="{{ route('booking.track.form') }}" class="block hover:text-[#e67e22]">{{ __('Kontak') }}</a>
                </div>
            </div>
            <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" class="w-full py-3 bg-[#e67e22] text-white rounded-full font-bold text-center uppercase tracking-wider flex items-center justify-center gap-2">
                <span>HUBUNGI KAMI!</span>
                <x-icon name="whatsapp" class="w-4 h-4" />
            </a>
        </div>
    </nav>
</header>

