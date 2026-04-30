<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Wonderful Toba | Premium Tour & Corporate Outbound')</title>
    <meta name="description" content="@yield('description', 'Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building.')">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans text-slate-900 bg-white selection:bg-green-100 selection:text-green-900 overflow-x-hidden" x-data="{ isDark: false }">
    
    <!-- Navbar Placeholder (Will be converted soon) -->
    @include('layouts.partials.navbar')

    <main>
        @yield('content')
    </main>

    <!-- Footer Placeholder (Will be converted soon) -->
    @include('layouts.partials.footer')

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
