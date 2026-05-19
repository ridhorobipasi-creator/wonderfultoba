<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 | Akses Ditolak</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-2xl text-center">
        <div class="w-24 h-24 mx-auto mb-8 rounded-[2rem] bg-amber-500/10 border border-amber-400/20 flex items-center justify-center text-amber-300">
            <i class="fas fa-shield-halved text-3xl"></i>
        </div>

        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-300 mb-4">403 Forbidden</p>
        <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-6">Akses Ditolak</h1>
        <p class="text-slate-400 text-lg font-medium leading-relaxed max-w-xl mx-auto mb-10">
            {{ $message ?? 'Anda tidak memiliki hak akses untuk halaman ini.' }}
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 transition">
                Kembali ke Admin
            </a>
            <a href="{{ route('index') }}" class="px-8 py-4 bg-lake-blue text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-lake-light transition">
                Ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
