<header 
    x-data="{ 
        isMenuOpen: false, 
        isScrolled: false,
        showLoginModal: false,
        isRegister: false,
        formLoading: false,
        contact: { phone: '+62 813-2388-8207', email: 'outbound@wonderfultoba.com', whatsapp: '6281323888207' }
    }"
    x-init="
        window.addEventListener('scroll', () => isScrolled = window.scrollY > 20);
        isScrolled = window.scrollY > 20;
    "
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
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
                <span class="flex items-center">
                    <svg class="w-3 h-3 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    ID | EN
                </span>
            </div>
        </div>
    </div>

    <!-- Main Nav -->
    <nav 
        :class="isScrolled ? 'bg-white/95 backdrop-blur-md py-3 shadow-lg border-slate-100' : 'bg-transparent py-5 border-transparent'"
        class="transition-all duration-300 border-b"
    >
        <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3 group w-auto shrink-0 mr-8">
                    <div class="w-12 h-12 bg-toba-green rounded-xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-toba-green/20 group-hover:scale-105 transition-transform shrink-0">
                        W
                    </div>
                    <div class="flex flex-col whitespace-nowrap">
                        <span 
                            :class="isScrolled ? 'text-slate-900' : 'text-white'"
                            class="text-xl font-extrabold leading-none tracking-tight transition-colors"
                        >
                            WONDERFUL <span class="text-toba-green">TOBA</span>
                        </span>
                        <span 
                            :class="isScrolled ? 'text-slate-400' : 'text-slate-300'"
                            class="hidden sm:block text-[10px] font-bold tracking-[0.25em] uppercase mt-1 transition-colors"
                        >
                            Sumatera Utara Travel Experience
                        </span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden lg:flex items-center space-x-8 shrink-0 w-max">
                    <a href="/" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white/80 hover:text-white'" class="font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm">Beranda</a>
                    <a href="/tour" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white/80 hover:text-white'" class="font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm">Tour & Wisata</a>
                    <a href="/outbound" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white/80 hover:text-white'" class="font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm">Outbound</a>
                    <a href="/cars" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white/80 hover:text-white'" class="font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm">Rental Mobil</a>
                    <a href="/tour/packages" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white/80 hover:text-white'" class="font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm">Paket</a>
                    <a href="/tour/gallery" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white/80 hover:text-white'" class="font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm">Galeri</a>
                    <a href="/tour/blog" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white/80 hover:text-white'" class="font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm">Blog</a>
                </div>



                <!-- Desktop Actions -->
                <div class="hidden lg:flex items-center space-x-6 shrink-0 ml-8">
                    <a :href="'https://wa.me/' + contact.whatsapp" target="_blank" rel="noreferrer" 
                        :class="isScrolled ? 'bg-toba-green text-white hover:bg-slate-900' : 'bg-white text-toba-green hover:bg-slate-100'"
                        class="px-5 py-2.5 rounded-full font-bold text-sm tracking-wide transition-all shadow-lg flex items-center space-x-2 whitespace-nowrap"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>HUBUNGI KAMI</span>
                    </a>

                    <div :class="isScrolled ? 'bg-slate-200' : 'bg-white/20'" class="h-6 w-px"></div>

                    @auth
                        <div class="flex items-center space-x-5">
                            @if(auth()->user()->role !== 'user')
                                <a href="/admin" :class="isScrolled ? 'bg-toba-green/5 text-toba-green hover:bg-toba-green/10' : 'bg-white/10 text-white hover:bg-white/20'" class="text-xs font-extrabold px-5 py-2.5 rounded-full transition-all whitespace-nowrap">
                                    DASHBOARD
                                </a>
                            @endif
                            <div class="flex items-center space-x-3">
                                <a href="/profile" class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-transparent hover:ring-toba-green transition-all shadow-sm">
                                    <img src="{{ auth()->user()->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10b981&color=fff' }}" alt="User" class="w-full h-full object-cover">
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" :class="isScrolled ? 'text-slate-600 hover:text-rose-600' : 'text-white/80 hover:text-white'" class="flex flex-col items-start transition-colors">
                                        <span class="text-xs font-bold leading-none mb-0.5">Logout</span>
                                        <span class="text-[9px] font-semibold uppercase opacity-70 leading-none">Keluar akun</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <button @click="showLoginModal = true" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white hover:text-toba-green bg-white/10 hover:bg-white'" class="px-4 py-2 rounded-full text-sm font-bold flex items-center space-x-2 transition-all whitespace-nowrap">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            <span>LOGIN / DAFTAR</span>
                        </button>
                    @endauth
                </div>

                <!-- Mobile Menu Toggle -->
                <div class="lg:hidden flex items-center">
                    <button @click="isMenuOpen = !isMenuOpen" :class="isScrolled ? 'text-slate-600 hover:text-toba-green' : 'text-white hover:text-toba-green'" class="p-2 transition-colors">
                        <svg x-show="!isMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                        <svg x-show="isMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Nav -->
        <div 
            x-show="isMenuOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="lg:hidden fixed inset-x-0 top-[72px] bottom-0 bg-white/95 backdrop-blur-lg border-t border-slate-100 p-8 space-y-2 shadow-2xl z-50 overflow-y-auto"
        >
            <a href="/" class="block text-xl font-black p-4 rounded-2xl text-slate-900 bg-slate-50 border border-slate-100 transition-all mb-2">Beranda</a>
            <div class="grid grid-cols-2 gap-3">
                <a href="/tour" class="flex flex-col items-center justify-center text-center p-4 rounded-2xl border border-slate-100 hover:border-toba-green group transition-all">
                    <div class="w-10 h-10 rounded-xl bg-toba-green/10 text-toba-green flex items-center justify-center mb-2 group-hover:bg-toba-green group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Tour</span>
                </a>
                <a href="/outbound" class="flex flex-col items-center justify-center text-center p-4 rounded-2xl border border-slate-100 hover:border-toba-green group transition-all">
                    <div class="w-10 h-10 rounded-xl bg-toba-green/10 text-toba-green flex items-center justify-center mb-2 group-hover:bg-toba-green group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Outbound</span>
                </a>
                <a href="/cars" class="flex flex-col items-center justify-center text-center p-4 rounded-2xl border border-slate-100 hover:border-toba-green group transition-all">
                    <div class="w-10 h-10 rounded-xl bg-toba-green/10 text-toba-green flex items-center justify-center mb-2 group-hover:bg-toba-green group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Rental</span>
                </a>
                <a href="/tour/packages" class="flex flex-col items-center justify-center text-center p-4 rounded-2xl border border-slate-100 hover:border-toba-green group transition-all">
                    <div class="w-10 h-10 rounded-xl bg-toba-green/10 text-toba-green flex items-center justify-center mb-2 group-hover:bg-toba-green group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Paket</span>
                </a>
            </div>
            <div class="pt-4">
                <a href="/tour/gallery" class="flex items-center space-x-4 p-4 rounded-2xl text-slate-700 hover:bg-slate-50 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center"><i class="fas fa-images"></i></div>
                    <span class="font-bold">Galeri Foto</span>
                </a>
                <a href="/tour/blog" class="flex items-center space-x-4 p-4 rounded-2xl text-slate-700 hover:bg-slate-50 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center"><i class="fas fa-newspaper"></i></div>
                    <span class="font-bold">Blog & Artikel</span>
                </a>
            </div>



            <div class="border-t border-slate-100 pt-4 space-y-3">
                @auth
                    <a href="/profile" class="w-full flex items-center space-x-3 p-3 rounded-xl text-slate-700 hover:bg-slate-50 font-bold transition-all">
                        <svg class="w-5 h-5 text-toba-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Profil Saya</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-rose-50 text-rose-600 p-4 rounded-2xl font-bold border border-rose-100">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            <span>KELUAR</span>
                        </button>
                    </form>
                @else
                    <button @click="showLoginModal = true; isMenuOpen = false" class="w-full flex items-center justify-center space-x-2 bg-toba-green text-white p-4 rounded-2xl font-bold">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        <span>MASUK</span>
                    </button>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Login / Register Modal -->
    <template x-if="showLoginModal">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md">
            <div @click.away="showLoginModal = false" class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900" x-text="isRegister ? 'Buat Akun' : 'Selamat Datang'"></h3>
                        <p class="text-sm text-slate-500 font-medium mt-1" x-text="isRegister ? 'Daftar untuk mulai memesan' : 'Masuk ke akun Wonderful Toba Anda'"></p>
                    </div>
                    <button @click="showLoginModal = false" class="p-2 hover:bg-slate-50 rounded-xl text-slate-400 hover:text-slate-900 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                
                <!-- Simple Login Form for now -->
                <form action="{{ route('login') }}" method="POST" class="p-8 space-y-5">
                    @csrf
                    <div class="space-y-2" x-show="isRegister">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                        <div class="relative">
                            <input type="text" name="name" class="w-full pl-4 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900" placeholder="Nama Anda">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</label>
                        <div class="relative">
                            <input required type="email" name="email" class="w-full pl-4 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900" placeholder="email@contoh.com">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Password</label>
                        <div class="relative">
                            <input required type="password" name="password" class="w-full pl-4 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900" placeholder="••••••••">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 bg-toba-green text-white rounded-2xl font-bold hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20">
                        <span x-text="isRegister ? 'Daftar Sekarang' : 'Masuk'"></span>
                    </button>
                    <p class="text-center text-sm text-slate-500">
                        <span x-text="isRegister ? 'Sudah punya akun?' : 'Belum punya akun?'"></span>
                        <button type="button" @click="isRegister = !isRegister" class="font-bold text-toba-green hover:underline" x-text="isRegister ? 'Masuk' : 'Daftar'"></button>
                    </p>
                </form>
            </div>
        </div>
    </template>
</header>

