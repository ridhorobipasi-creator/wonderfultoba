<header 
    x-data="{ 
        isMenuOpen: false, 
        isScrolled: false,
        showLoginModal: false,
        isRegister: false,
        formLoading: false,
        contact: { 
            phone: '{{ $siteSettings['general']['wa_number'] ?? '+62 813-2388-8207' }}', 
            email: '{{ $siteSettings['general']['contact_email'] ?? 'info@wonderfultoba.com' }}', 
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
        class="hidden sm:block bg-slate-900 text-white py-2 overflow-hidden"
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
                <a href="https://wa.me/{{ $siteSettings['general']['whatsapp'] ?? preg_replace('/[^0-9]/', '', $siteSettings['general']['wa_number'] ?? '6281323888207') }}" target="_blank" rel="noopener" class="flex items-center hover:text-toba-accent transition-colors">
                    <svg class="w-3 h-3 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.1-1.7-.8-2-.9-.3-.1-.5-.1-.6.2-.2.3-.7.9-.8 1-.1.2-.3.2-.5.1-.3-.1-1.2-.4-2.2-1.4-.8-.7-1.4-1.6-1.5-1.9-.2-.3 0-.4.1-.6l.4-.5c.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5 0-.1-.6-1.5-.8-2-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-1 .9-1 2.3s1 2.7 1.2 2.9c.1.2 2 3 4.7 4.2.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.5-.1 1.7-.7 2-1.4.2-.7.2-1.2.2-1.4-.1-.1-.3-.2-.6-.3z"/></svg>
                    Konsultasi Cepat
                </a>
            </div>
        </div>
    </div>

    <!-- Main Nav -->
    <nav 
        :class="isScrolled ? 'bg-white/90 backdrop-blur-xl py-3 shadow-[0_4px_24px_-14px_rgba(15,23,42,0.25)] border-slate-100' : 'bg-transparent py-5 border-transparent'"
        class="transition-all duration-300 border-b"
    >
        <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center group w-auto shrink-0 mr-4 md:mr-8">
                    @php
                        $logoLight = $siteSettings['general']['logo_light_url'] ?? null;
                        $logoDark = $siteSettings['general']['logo_dark_url'] ?? null;
                        $brandName = $siteSettings['general']['site_name'] ?? 'Wonderful Toba';
                    @endphp

                    @if($logoLight)
                        <img 
                            src="{{ $logoLight }}" 
                            alt="{{ $brandName }}" 
                            x-show="!isScrolled"
                            class="h-10 md:h-12 w-auto object-contain group-hover:scale-105 transition-all"
                        />
                        <img 
                            src="{{ $logoDark ?? $logoLight }}" 
                            alt="{{ $brandName }}" 
                            x-show="isScrolled"
                            class="h-10 md:h-12 w-auto object-contain group-hover:scale-105 transition-all"
                        />
                    @else
                        <div class="flex items-center space-x-2 md:space-x-3">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-toba-green rounded-xl flex items-center justify-center text-white font-black text-xl md:text-2xl shadow-lg shadow-toba-green/20 group-hover:scale-105 transition-transform shrink-0 overflow-hidden">
                                W
                            </div>
                            <div class="flex flex-col whitespace-nowrap">
                                <span 
                                    :class="isScrolled ? 'text-slate-900' : 'text-white'"
                                    class="text-lg md:text-xl font-extrabold leading-none tracking-tight transition-colors uppercase"
                                >
                                    {{ $brandName }}
                                </span>
                                <span 
                                    :class="isScrolled ? 'text-slate-400' : 'text-slate-300'"
                                    class="hidden md:block text-[9px] md:text-[10px] font-bold tracking-[0.25em] uppercase mt-1 transition-colors"
                                >
                                    {{ $siteSettings['general']['site_tagline'] ?? 'Sumatera Utara Travel Experience' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </a>

                <!-- Desktop Nav -->
                @php
                    $navItems = [
                        ['label' => 'Beranda',    'href' => '/',              'active' => request()->is('/')],
                        ['label' => 'Tour',        'href' => '/tour',          'active' => request()->is('tour')],
                        ['label' => 'Outbound',    'href' => '/outbound',      'active' => request()->is('outbound*')],
                        ['label' => 'Paket',       'href' => '/tour/packages', 'active' => request()->is('tour/packages*')],
                        ['label' => 'Galeri',      'href' => '/tour/gallery',  'active' => request()->is('tour/gallery')],
                        ['label' => 'Blog',        'href' => '/tour/blog',     'active' => request()->is('tour/blog*')],
                        ['label' => 'Tentang',     'href' => '/about',         'active' => request()->is('about')],
                    ];
                @endphp
                <div class="hidden lg:flex items-center lg:space-x-4 xl:space-x-6 shrink-0 w-max">
                    @foreach($navItems as $item)
                    <a href="{{ $item['href'] }}"
                        @if($item['active'])
                            :class="isScrolled ? 'text-toba-green' : 'text-toba-accent'"
                            aria-current="page"
                        @else
                            :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white/80 hover:text-white'"
                        @endif
                        class="relative font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm py-1 {{ $item['active'] ? 'after:absolute after:-bottom-0.5 after:left-0 after:h-0.5 after:w-full after:rounded-full after:bg-toba-green' : '' }}">{{ $item['label'] }}</a>
                    @endforeach
                </div>



                <!-- Desktop Actions -->
                <div class="hidden lg:flex items-center space-x-6 shrink-0 ml-8">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noreferrer" 
                        :class="isScrolled ? 'bg-toba-green text-white hover:bg-slate-900' : 'bg-white text-toba-green hover:bg-slate-100'"
                        class="px-5 py-2.5 rounded-full font-bold text-sm tracking-wide transition-all shadow-soft flex items-center space-x-2 whitespace-nowrap"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>HUBUNGI KAMI</span>
                    </a>


                </div>

                <!-- Mobile Menu Toggle -->
                <div class="lg:hidden flex items-center">
                    <button @click="isMenuOpen = !isMenuOpen" :aria-expanded="isMenuOpen" aria-label="Buka menu navigasi" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white bg-black/5 hover:bg-black/10 rounded-xl'" class="p-2 transition-all">
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
                    <div class="w-10 h-10 bg-toba-green rounded-xl flex items-center justify-center text-white font-black text-xl">W</div>
                    <span class="text-white font-black uppercase tracking-tighter">Wonderful <span class="text-toba-green">Toba</span></span>
                </div>
                <button @click="isMenuOpen = false" class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-8 py-12 space-y-12">
                <!-- Navigation Sections -->
                <div class="space-y-10">
                    <div>
                        <span class="text-toba-accent font-black text-[10px] uppercase tracking-[0.4em] mb-6 block">Eksplorasi</span>
                        <div class="space-y-6">
                            <a href="/" @click="isMenuOpen = false" class="block text-4xl font-black text-white tracking-tighter hover:text-toba-green transition-colors">Beranda</a>
                            <a href="/tour" @click="isMenuOpen = false" class="block text-4xl font-black text-white tracking-tighter hover:text-toba-green transition-colors">Tour & Wisata</a>
                            <a href="/tour/packages" @click="isMenuOpen = false" class="block text-4xl font-black text-white tracking-tighter hover:text-toba-green transition-colors">Paket Wisata</a>
                            <a href="/outbound" @click="isMenuOpen = false" class="block text-4xl font-black text-white tracking-tighter hover:text-toba-green transition-colors">Outbound</a>
                        </div>
                    </div>

                    <div>
                        <span class="text-white/40 font-black text-[10px] uppercase tracking-[0.4em] mb-6 block">Informasi</span>
                        <div class="grid grid-cols-1 gap-6">
                            <a href="/tour/gallery" @click="isMenuOpen = false" class="flex items-center gap-4 text-white/80 font-bold text-lg">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-toba-green"><i class="fas fa-images"></i></div>
                                Galeri Foto
                            </a>
                            <a href="/tour/blog" @click="isMenuOpen = false" class="flex items-center gap-4 text-white/80 font-bold text-lg">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-toba-green"><i class="fas fa-newspaper"></i></div>
                                Blog & Artikel
                            </a>
                            <a href="/about" @click="isMenuOpen = false" class="flex items-center gap-4 text-white/80 font-bold text-lg">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-toba-green"><i class="fas fa-info-circle"></i></div>
                                Tentang Kami
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-10 border-t border-white/5 space-y-4">

                    
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" class="w-full py-5 bg-toba-green text-white rounded-2xl font-bold text-sm uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-toba-accent transition-colors">
                        <i class="fab fa-whatsapp text-xl"></i>
                        Hubungi Kami
                    </a>
                </div>
            </div>

            <!-- Footer Decor -->
            <div class="p-8 text-center">
                <p class="text-white/20 text-[9px] font-black uppercase tracking-[0.5em]">&copy; Wonderful Toba Premium Experience</p>
            </div>
        </div>
    </nav>


</header>

