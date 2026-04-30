<!DOCTYPE html>
<html lang="id" x-data="{ 
    sidebarOpen: window.innerWidth >= 1024,
    isMobile: window.innerWidth < 1024
}" @resize.window="isMobile = window.innerWidth < 1024; if (!isMobile && !sidebarOpen) sidebarOpen = false">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Wonderful Toba Admin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .custom-scrollbar::-webkit-scrollbar { width: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }

        /* --- Core Layout --- */
        #admin-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar is fixed, always off-canvas by default on mobile */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 272px;
            background: white;
            border-right: 1px solid #F1F5F9;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        #sidebar > div {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        #sidebar nav {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
        }

        /* Content area shifts via margin-left on large screens */
        #content-area {
            flex: 1;
            min-width: 0;
            transition: margin-left 0.3s ease;
        }

        @media (min-width: 1024px) {
            #content-area.sidebar-visible {
                margin-left: 272px;
            }
            #content-area.sidebar-hidden {
                margin-left: 0;
            }
        }

        @media (max-width: 1023px) {
            #content-area {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body class="antialiased text-slate-900 overflow-x-hidden">

    <div id="admin-wrapper">

        {{-- ====== SIDEBAR ====== --}}
        <aside id="sidebar"
            :style="sidebarOpen ? 'transform: translateX(0)' : 'transform: translateX(-100%)'"
        >
            <div class="flex flex-col h-full">

                {{-- Branding --}}
                <div class="px-6 pt-8 pb-6 flex flex-col items-center text-center flex-shrink-0 border-b border-slate-50">
                    <div class="w-12 h-12 bg-toba-green rounded-2xl flex items-center justify-center shadow-lg shadow-toba-green/30 mb-3">
                        <span class="text-white font-black text-xl">W</span>
                    </div>
                    <h1 class="text-base font-black text-slate-900 tracking-tight leading-tight mb-3">Wonderful<br>Toba</h1>
                    <a href="{{ route('index') }}" target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-400 hover:text-toba-green rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                        <i class="fas fa-external-link text-[8px]"></i>
                        <span>Lihat Website</span>
                    </a>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 min-h-0 px-3 py-4 overflow-y-auto custom-scrollbar">

                    {{-- UTAMA --}}
                    <div class="mb-5">
                        <p class="px-4 mb-1.5 text-[9px] font-black text-slate-300 uppercase tracking-[0.25em]">Utama</p>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                  {{ request()->routeIs('admin.dashboard') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fas fa-grid-2 w-5 text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-toba-green' }}"></i>
                            Dashboard
                        </a>
                    </div>

                    {{-- MANAJEMEN KONTEN (CMS) --}}
                    <div class="mb-5">
                        <p class="px-4 mb-1.5 text-[9px] font-black text-slate-300 uppercase tracking-[0.25em]">Manajemen Konten (CMS)</p>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.cms.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.cms.index') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-globe w-5 text-sm {{ request()->routeIs('admin.cms.index') ? 'text-white' : 'text-sky-400' }}"></i>
                                CMS Halaman Utama
                            </a>
                            <a href="{{ route('admin.cms.tour') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.cms.tour') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-house-chimney w-5 text-sm {{ request()->routeIs('admin.cms.tour') ? 'text-white' : 'text-amber-500' }}"></i>
                                CMS Beranda Tour
                            </a>
                            <a href="{{ route('admin.blogs.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.blogs.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-file-lines w-5 text-sm {{ request()->routeIs('admin.blogs.*') ? 'text-white' : 'text-emerald-500' }}"></i>
                                Blog / Artikel
                            </a>
                            <a href="{{ route('admin.cities.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.cities.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-location-dot w-5 text-sm {{ request()->routeIs('admin.cities.*') ? 'text-white' : 'text-teal-500' }}"></i>
                                Wilayah & Destinasi
                            </a>
                            <a href="{{ route('admin.media.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.media.index') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-images w-5 text-sm {{ request()->routeIs('admin.media.index') ? 'text-white' : 'text-purple-400' }}"></i>
                                Media Library
                            </a>
                        </div>
                    </div>

                    {{-- PRODUK & LAYANAN --}}
                    <div class="mb-5">
                        <p class="px-4 mb-1.5 text-[9px] font-black text-slate-300 uppercase tracking-[0.25em]">Produk & Layanan</p>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.packages.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.packages.*') && !request()->has('type') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-box-archive w-5 text-sm {{ request()->routeIs('admin.packages.*') && !request()->has('type') ? 'text-white' : 'text-orange-400' }}"></i>
                                Paket Wisata
                            </a>
                            <a href="{{ route('admin.cars.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.cars.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-van-shuttle w-5 text-sm {{ request()->routeIs('admin.cars.*') ? 'text-white' : 'text-blue-400' }}"></i>
                                Armada Mobil
                            </a>
                        </div>
                    </div>

                    {{-- TRANSAKSI --}}
                    <div class="mb-5">
                        <p class="px-4 mb-1.5 text-[9px] font-black text-slate-300 uppercase tracking-[0.25em]">Transaksi</p>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.bookings.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.bookings.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-calendar-check w-5 text-sm {{ request()->routeIs('admin.bookings.*') ? 'text-white' : 'text-toba-green' }}"></i>
                                Daftar Pesanan
                            </a>
                            <a href="{{ route('admin.finance.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.finance.index') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-money-bill-trend-up w-5 text-sm {{ request()->routeIs('admin.finance.index') ? 'text-white' : 'text-emerald-600' }}"></i>
                                Laporan Keuangan
                            </a>
                        </div>
                    </div>

                    {{-- PENGATURAN --}}
                    <div class="mb-5">
                        <p class="px-4 mb-1.5 text-[9px] font-black text-slate-300 uppercase tracking-[0.25em]">Pengaturan</p>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.users.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.users.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-users-gear w-5 text-sm {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-indigo-400' }}"></i>
                                Pengguna
                            </a>
                        </div>
                    </div>

                    {{-- OUTBOUND --}}
                    <div class="mb-5">
                        <p class="px-4 mb-1.5 text-[9px] font-black text-slate-300 uppercase tracking-[0.25em]">Outbound</p>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.outbound.cms') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.outbound.cms') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-building-user w-5 text-sm {{ request()->routeIs('admin.outbound.cms') ? 'text-white' : 'text-slate-400' }}"></i>
                                CMS Beranda Outbound
                            </a>
                            <a href="{{ route('admin.packages.index', ['type' => 'outbound']) }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.packages.*') && request()->get('type') === 'outbound' ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-layer-group w-5 text-sm {{ request()->routeIs('admin.packages.*') && request()->get('type') === 'outbound' ? 'text-white' : 'text-emerald-500' }}"></i>
                                Paket Outbound
                            </a>
                        </div>
                    </div>

                    {{-- KONTEN OUTBOUND --}}
                    <div class="mb-4">
                        <p class="px-4 mb-1.5 text-[9px] font-black text-slate-300 uppercase tracking-[0.25em]">Konten Outbound</p>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.outbound.services.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.outbound.services.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-list-check w-5 text-sm {{ request()->routeIs('admin.outbound.services.*') ? 'text-white' : 'text-cyan-500' }}"></i>
                                Layanan Outbound
                            </a>
                            <a href="{{ route('admin.outbound.videos.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.outbound.videos.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-video w-5 text-sm {{ request()->routeIs('admin.outbound.videos.*') ? 'text-white' : 'text-red-400' }}"></i>
                                Video Highlight
                            </a>
                            <a href="{{ route('admin.outbound.locations.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.outbound.locations.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-location-dot w-5 text-sm {{ request()->routeIs('admin.outbound.locations.*') ? 'text-white' : 'text-teal-400' }}"></i>
                                Lokasi Venue
                            </a>
                            <a href="{{ route('admin.clients.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.clients.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-building-columns w-5 text-sm {{ request()->routeIs('admin.clients.*') ? 'text-white' : 'text-amber-600' }}"></i>
                                Logo Klien
                            </a>
                            <a href="{{ route('admin.gallery.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.gallery.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-images w-5 text-sm {{ request()->routeIs('admin.gallery.*') ? 'text-white' : 'text-pink-400' }}"></i>
                                Galeri Foto
                            </a>
                            <a href="{{ route('admin.outbound.tiers') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-bold text-[13px]
                                      {{ request()->routeIs('admin.outbound.tiers') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <i class="fas fa-ranking-star w-5 text-sm {{ request()->routeIs('admin.outbound.tiers') ? 'text-white' : 'text-yellow-500' }}"></i>
                                Tier Paket
                            </a>
                        </div>
                    </div>

                </nav>

                {{-- Footer / Logout --}}
                <div class="flex-shrink-0 p-4 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all font-bold text-[13px]">
                            <i class="fas fa-right-from-bracket w-5 text-sm"></i>
                            Keluar
                        </button>
                    </form>
                </div>

            </div>
        </aside>

        {{-- ====== MAIN CONTENT ====== --}}
        <div id="content-area"
             :class="sidebarOpen && !isMobile ? 'sidebar-visible' : 'sidebar-hidden'"
             class="flex flex-col min-h-screen min-w-0">

            {{-- Top Header --}}
            <header class="glass sticky top-0 z-50 border-b border-slate-100 px-5 lg:px-8 py-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-4">
                    {{-- Toggle Button --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition focus:outline-none">
                        <i class="fas fa-bars text-xs"></i>
                    </button>
                    <div>
                        <h2 class="text-sm font-black text-slate-900 tracking-tight">@yield('page-title', 'Dashboard')</h2>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-toba-green"></span>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ now()->format('l, d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('index') }}" target="_blank"
                       class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-100 text-[10px] font-black text-slate-400 hover:text-slate-900 hover:border-slate-300 uppercase tracking-widest transition">
                        View Site
                        <i class="fas fa-external-link text-[8px]"></i>
                    </a>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-5 lg:p-10">

                {{-- Notifications --}}
                @if(session('success'))
                    <div x-data="{ show: true }"
                         x-init="setTimeout(() => show = false, 5000)"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="mb-8 bg-white border border-slate-100 border-l-[5px] border-l-toba-green p-5 rounded-2xl flex items-center justify-between shadow-lg shadow-slate-100/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-toba-green/10 text-toba-green flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="font-bold text-sm text-slate-700">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-slate-300 hover:text-slate-500 ml-4 flex-shrink-0">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-transition
                         class="mb-8 bg-white border border-slate-100 border-l-[5px] border-l-rose-500 p-5 rounded-2xl flex items-center justify-between shadow-lg shadow-slate-100/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-xs"></i>
                            </div>
                            <span class="font-bold text-sm text-slate-700">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-slate-300 hover:text-slate-500 ml-4 flex-shrink-0">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                @endif

                {{-- Page Content Yield --}}
                <div class="animate-in fade-in slide-in-from-bottom-3 duration-500">
                    @yield('content')
                </div>

            </main>

            {{-- Footer --}}
            <footer class="px-8 py-5 border-t border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-2 flex-shrink-0">
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em]">
                    Wonderful Toba Engine &bull; Management v3.0
                </p>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">
                    Crafted with <i class="fas fa-heart text-rose-400"></i> for Wonderful Indonesia
                </p>
            </footer>

        </div>

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen && isMobile"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[99] lg:hidden"
             x-cloak>
        </div>

    </div>

    @stack('scripts')
</body>
</html>
