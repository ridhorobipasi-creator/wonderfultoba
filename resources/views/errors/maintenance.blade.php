<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Wonderful Toba</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-xl w-full text-center space-y-10">
        <div class="relative inline-block">
            <div class="w-32 h-32 bg-slate-900 rounded-[2.5rem] flex items-center justify-center mx-auto shadow-2xl shadow-slate-200">
                <i class="fas fa-hammer text-4xl text-white"></i>
            </div>
            <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-amber-400 rounded-2xl flex items-center justify-center shadow-lg border-4 border-slate-50">
                <i class="fas fa-cog text-slate-900 animate-spin"></i>
            </div>
        </div>

        <div class="space-y-4">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-tight">Sedang Pembaruan</h1>
            <p class="text-slate-500 font-bold leading-relaxed">Wonderful Toba sedang melakukan pemeliharaan rutin untuk meningkatkan pengalaman perjalanan Anda. Kami akan segera kembali!</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Butuh bantuan mendesak?</p>
            <a href="https://wa.me/628123456789" class="inline-flex items-center gap-3 px-8 py-4 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 transition shadow-xl shadow-emerald-100">
                <i class="fab fa-whatsapp"></i> Hubungi WhatsApp
            </a>
        </div>

        <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em]">Wonderful Toba &bull; Management v3.0</p>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
