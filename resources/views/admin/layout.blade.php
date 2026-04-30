<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: window.innerWidth > 1024, darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Wonderful Toba Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-gray-50 antialiased font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside 
            x-show="sidebarOpen" 
            x-transition:enter="sidebar-transition"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="sidebar-transition"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 shadow-2xl lg:shadow-none"
            x-cloak
        >
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-toba-green to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-toba-green/20">
                            <i class="fas fa-mountain text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-black text-gray-900 leading-tight">Wonderful Toba</h1>
                            <p class="text-[10px] text-toba-green font-black uppercase tracking-widest">Admin Central</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
                    <div class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">General</div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-chart-pie w-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-receipt w-5 {{ request()->routeIs('admin.bookings.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Bookings</span>
                    </a>
                    
                    <a href="{{ route('admin.packages.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.packages.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-map w-5 {{ request()->routeIs('admin.packages.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Packages</span>
                    </a>
                    
                    <a href="{{ route('admin.cars.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.cars.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-car w-5 {{ request()->routeIs('admin.cars.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Cars</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-user-shield w-5 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Users</span>
                    </a>

                    <div class="px-4 py-2 mt-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Content CMS</div>

                    <a href="{{ route('admin.blogs.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.blogs.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-newspaper w-5 {{ request()->routeIs('admin.blogs.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Blogs</span>
                    </a>

                    <a href="{{ route('admin.cities.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.cities.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-city w-5 {{ request()->routeIs('admin.cities.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Cities</span>
                    </a>

                    <a href="{{ route('admin.gallery.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.gallery.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-images w-5 {{ request()->routeIs('admin.gallery.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Gallery</span>
                    </a>

                    <a href="{{ route('admin.clients.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.clients.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-handshake w-5 {{ request()->routeIs('admin.clients.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Clients</span>
                    </a>

                    <div class="px-4 py-2 mt-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Outbound</div>

                    <a href="{{ route('admin.outbound.services.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.outbound.services.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-concierge-bell w-5 {{ request()->routeIs('admin.outbound.services.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Services</span>
                    </a>

                    <a href="{{ route('admin.outbound.videos.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.outbound.videos.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-video w-5 {{ request()->routeIs('admin.outbound.videos.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Videos</span>
                    </a>

                    <a href="{{ route('admin.outbound.locations.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.outbound.locations.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-map-marked-alt w-5 {{ request()->routeIs('admin.outbound.locations.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Locations</span>
                    </a>

                    <div class="px-4 py-2 mt-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">System</div>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-toba-green text-white shadow-lg shadow-toba-green/30' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-cog w-5 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Settings</span>
                    </a>

                    <div class="pt-6 mt-6 border-t border-gray-100">
                        <a href="{{ route('index') }}" target="_blank" class="flex items-center px-4 py-3 text-sm font-bold text-gray-500 rounded-xl hover:bg-gray-50 transition-all">
                            <i class="fas fa-external-link-alt w-5"></i>
                            <span class="ml-3">View Website</span>
                        </a>
                    </div>
                </nav>

                <!-- User Info -->
                <div class="p-6 bg-gray-50/50 border-t border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-toba-green flex items-center justify-center text-white font-black shadow-lg shadow-toba-green/20">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Logout">
                                <i class="fas fa-power-off text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col transition-all duration-300" :class="{ 'lg:ml-72': sidebarOpen }">
            <!-- Top Header -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-gray-100">
                <div class="flex items-center justify-between px-8 py-4">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-600 hover:bg-gray-100 transition focus:outline-none">
                            <i class="fas fa-bars-staggered"></i>
                        </button>
                        <div>
                            <h2 class="text-lg font-black text-gray-900">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ now()->format('l, d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="hidden sm:flex items-center px-4 py-2 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse mr-2"></div>
                            <span class="text-[10px] font-black text-gray-600 uppercase tracking-wider">System Live</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-8">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white mr-4 shadow-lg shadow-emerald-500/20">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <span class="font-bold text-sm">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 bg-red-50 border border-red-100 text-red-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-red-500 flex items-center justify-center text-white mr-4 shadow-lg shadow-red-500/20">
                                <i class="fas fa-exclamation-triangle text-sm"></i>
                            </div>
                            <span class="font-bold text-sm">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="px-8 py-6 border-t border-gray-100 text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    &copy; {{ date('Y') }} Wonderful Toba Management System &bull; Crafted with <i class="fas fa-heart text-red-500"></i>
                </p>
            </footer>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div 
        x-show="sidebarOpen && window.innerWidth < 1024" 
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 lg:hidden"
        x-cloak
    ></div>

    @stack('scripts')
</body>
</html>
