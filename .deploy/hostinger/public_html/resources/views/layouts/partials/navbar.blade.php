<header 
    x-data="{ 
        isMenuOpen: false, 
        isScrolled: false,
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
    :class="isScrolled ? 'bg-white/95 backdrop-blur-xl shadow-xl py-3' : 'bg-transparent py-5'"
>
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                @php
                    $logoUrl = $siteSettings['general']['logo_light_url'] ?? null;
                    $brandName = $siteSettings['general']['site_name'] ?? 'Sujailake Toba';
                @endphp
                
                <div x-show="!isScrolled" x-cloak>
                    @if($logoUrl)
                        <img src="{{ imageUrl($logoUrl) }}" alt="{{ $brandName }}" class="h-10 w-auto object-contain">
                    @else
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-lake-blue rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg">S</div>
                            <span class="text-white font-black text-lg uppercase tracking-tight hidden sm:block">{{ $brandName }}</span>
                        </div>
                    @endif
                </div>

                <div x-show="isScrolled" x-cloak>
                    @if($logoUrl)
                        <img src="{{ imageUrl($logoUrl) }}" alt="{{ $brandName }}" class="h-9 w-auto object-contain brightness-0">
                    @else
                        <div class="flex items-center gap-2">
                            <div class="w-9 h-9 bg-lake-blue rounded-xl flex items-center justify-center text-white font-black text-lg">S</div>
                            <span class="text-slate-900 font-black text-lg uppercase tracking-tight hidden sm:block">{{ $brandName }}</span>
                        </div>
                    @endif
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-6 text-[11px] font-black uppercase tracking-widest">
                <a href="/" class="transition-colors" :class="isScrolled ? 'text-slate-600 hover:text-lake-blue' : 'text-white/80 hover:text-white'">Beranda</a>
                <a href="/packages" class="transition-colors" :class="isScrolled ? 'text-slate-600 hover:text-lake-blue' : 'text-white/80 hover:text-white'">Paket</a>
                <a href="/gallery" class="transition-colors" :class="isScrolled ? 'text-slate-600 hover:text-lake-blue' : 'text-white/80 hover:text-white'">Galeri</a>
                <a href="/blog" class="transition-colors" :class="isScrolled ? 'text-slate-600 hover:text-lake-blue' : 'text-white/80 hover:text-white'">Blog</a>
                <a href="/about" class="transition-colors" :class="isScrolled ? 'text-slate-600 hover:text-lake-blue' : 'text-white/80 hover:text-white'">Tentang</a>
                <a href="/contact" class="transition-colors" :class="isScrolled ? 'text-slate-600 hover:text-lake-blue' : 'text-white/80 hover:text-white'">Kontak</a>
                
                <!-- Search Toggle -->
                <div x-data="{ open: false }">
                    <button @click="open = true" class="w-10 h-10 rounded-full flex items-center justify-center transition-all" :class="isScrolled ? 'bg-slate-100 text-slate-600 hover:bg-lake-blue hover:text-white' : 'bg-white/10 text-white hover:bg-white hover:text-slate-900 backdrop-blur-md'">
                        <i class="fas fa-search text-xs"></i>
                    </button>
                    <template x-teleport="body">
                        <div x-show="open" x-cloak x-transition class="fixed inset-0 z-[200] bg-slate-950/95 backdrop-blur-xl flex items-center justify-center p-6">
                            <button @click="open = false" class="absolute top-10 right-10 text-white text-3xl"><i class="fas fa-times"></i></button>
                            <div class="w-full max-w-2xl text-center">
                                <form action="{{ route('tour.search') }}" method="GET" class="relative group">
                                    <input type="text" name="q" placeholder="Cari destinasi..." class="w-full bg-transparent border-b-2 border-white/20 py-6 text-4xl text-white font-bold focus:outline-none focus:border-lake-light">
                                    <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 text-white text-2xl"><i class="fas fa-arrow-right"></i></button>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Lang Switch -->
                <div class="flex items-center gap-1 bg-white/10 backdrop-blur-md rounded-full p-1 border border-white/20" :class="isScrolled ? 'bg-slate-100 border-slate-200' : ''">
                    <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 rounded-full text-[8px] font-black {{ app()->getLocale() == 'id' ? 'bg-white text-slate-900 shadow-sm' : 'text-white/40 hover:text-white' }}">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 rounded-full text-[8px] font-black {{ app()->getLocale() == 'en' ? 'bg-white text-slate-900 shadow-sm' : 'text-white/40 hover:text-white' }}">EN</a>
                </div>

                <a :href="'https://wa.me/' + contact.whatsapp" 
                   target="_blank"
                   class="px-5 py-2.5 rounded-full font-black text-[9px] uppercase tracking-widest transition-all shadow-lg"
                   :class="isScrolled ? 'bg-lake-blue text-white shadow-lake-blue/20' : 'bg-white text-slate-900 hover:bg-lake-blue hover:text-white'">
                    <i class="fab fa-whatsapp mr-1.5"></i> Hubungi
                </a>
            </nav>

            <!-- Mobile Toggle -->
            <button @click="isMenuOpen = true" class="lg:hidden w-10 h-10 rounded-xl flex items-center justify-center transition-all" :class="isScrolled ? 'bg-slate-100 text-slate-900' : 'bg-white/10 text-white backdrop-blur-md'">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="isMenuOpen" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" class="fixed inset-0 z-[110] bg-slate-950 flex flex-col p-10 lg:hidden">
        <div class="flex justify-between items-center mb-16">
            <span class="text-white/20 font-black text-[10px] uppercase tracking-widest">Menu</span>
            <button @click="isMenuOpen = false" class="text-white text-2xl"><i class="fas fa-times"></i></button>
        </div>
        <nav class="flex flex-col gap-8">
            <a href="/" class="text-4xl font-black text-white">Beranda</a>
            <a href="/packages" class="text-4xl font-black text-white">Paket</a>
            <a href="/gallery" class="text-4xl font-black text-white">Galeri</a>
            <a href="/blog" class="text-4xl font-black text-white">Blog</a>
            <a href="/about" class="text-4xl font-black text-white">Tentang</a>
            <a href="/contact" class="text-4xl font-black text-white">Kontak</a>
            
            <div class="mt-10 pt-10 border-t border-white/5 space-y-8">
                <div class="flex items-center justify-between text-white/40 font-black text-[10px] uppercase">
                    <span>Currency</span>
                    <div class="flex gap-2">
                        <button @click="switchCurrency('IDR')" :class="currency==='IDR' ? 'text-white' : ''">IDR</button>
                        <button @click="switchCurrency('USD')" :class="currency==='USD' ? 'text-white' : ''">USD</button>
                    </div>
                </div>
                <a :href="'https://wa.me/' + contact.whatsapp" class="block w-full py-5 bg-lake-blue text-white text-center rounded-2xl font-black text-xs tracking-widest uppercase">
                    WhatsApp Kami
                </a>
            </div>
        </nav>
    </div>
</header>
