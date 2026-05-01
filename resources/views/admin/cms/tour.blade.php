@extends('admin.layout')

@section('title', 'CMS Beranda Tour')
@section('page-title', 'CMS Beranda Tour')

@section('content')
<div x-data="{ activeTab: 'hero' }" class="space-y-8">
    
    <!-- Tab Navigation -->
    <div class="bg-white p-2.5 md:p-3 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center space-x-1.5 md:space-x-2 overflow-x-auto no-scrollbar">
        <button @click="activeTab = 'hero'" :class="activeTab === 'hero' ? 'bg-slate-900 text-white' : 'text-slate-400 hover:bg-slate-50'" class="shrink-0 px-5 md:px-6 py-3 rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition">Hero</button>
        <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'bg-slate-900 text-white' : 'text-slate-400 hover:bg-slate-50'" class="shrink-0 px-5 md:px-6 py-3 rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition whitespace-nowrap">Mengapa Kami</button>
        <button @click="activeTab = 'gallery'" :class="activeTab === 'gallery' ? 'bg-slate-900 text-white' : 'text-slate-400 hover:bg-slate-50'" class="shrink-0 px-5 md:px-6 py-3 rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition">Galeri</button>
        <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-slate-900 text-white' : 'text-slate-400 hover:bg-slate-50'" class="shrink-0 px-5 md:px-6 py-3 rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition">Testimoni</button>
        <button @click="activeTab = 'stats'" :class="activeTab === 'stats' ? 'bg-slate-900 text-white' : 'text-slate-400 hover:bg-slate-50'" class="shrink-0 px-5 md:px-6 py-3 rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition">Statistik</button>
        <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-slate-900 text-white' : 'text-slate-400 hover:bg-slate-50'" class="shrink-0 px-5 md:px-6 py-3 rounded-2xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition">Kontak</button>
    </div>

    <!-- Tab Contents -->
    <div class="bg-white rounded-[2.5rem] md:rounded-[3.5rem] p-8 lg:p-16 border border-slate-100 shadow-sm min-h-[600px]">
        
        <!-- Hero Section -->
        <div x-show="activeTab === 'hero'" class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="flex items-center space-x-5 md:space-x-6">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl md:rounded-3xl bg-toba-green/10 text-toba-green flex items-center justify-center text-lg">
                    <i class="fas fa-image"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">Hero Section</h3>
                    <p class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Halaman Utama /tour</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Utama</label>
                    <input type="text" value="Discover the Magic of Lake Toba" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Subjudul</label>
                    <input type="text" value="Experience the serene beauty and rich culture of North Sumatra" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="md:col-span-2 space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Hero Background URL</label>
                    <input type="text" value="https://images.unsplash.com/photo-1544735049-717bc392183e" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-xs text-slate-400">
                </div>
            </div>
        </div>

        <!-- Mengapa Kami Section -->
        <div x-show="activeTab === 'about'" class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500" x-cloak>
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Mengapa Kami</h3>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Poin Keunggulan & Kolase Gambar</p>
                </div>
            </div>

            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100">
                        <i class="fas fa-shield-check text-toba-green mb-4"></i>
                        <input type="text" value="Harga Transparan" class="w-full bg-transparent border-none p-0 font-black text-slate-900 placeholder-slate-300 focus:ring-0">
                    </div>
                    <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100">
                        <i class="fas fa-user-tie text-toba-green mb-4"></i>
                        <input type="text" value="Guide Berpengalaman" class="w-full bg-transparent border-none p-0 font-black text-slate-900 placeholder-slate-300 focus:ring-0">
                    </div>
                    <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100">
                        <i class="fas fa-gem text-toba-green mb-4"></i>
                        <input type="text" value="Layanan Premium" class="w-full bg-transparent border-none p-0 font-black text-slate-900 placeholder-slate-300 focus:ring-0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div x-show="activeTab === 'stats'" class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500" x-cloak>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Trip Selesai</label>
                    <input type="text" value="1000+" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pelanggan Puas</label>
                    <input type="text" value="5000+" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
            </div>
        </div>

        <!-- Testimoni & CTA -->
        <div x-show="activeTab === 'social'" class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ajakan Booking (CTA Text)</label>
                    <input type="text" name="cta_text" value="{{ $settings['cta_text'] ?? 'Mulai Petualangan Anda Sekarang' }}" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Link CTA</label>
                    <input type="text" name="cta_link" value="{{ $settings['cta_link'] ?? '/tour/packages' }}" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
            </div>
            <p class="text-[10px] font-bold text-slate-400">Review asli pelanggan dan galeri foto dikelola otomatis melalui modul Media Library dan form ulasan pelanggan.</p>
        </div>

        <!-- Kontak -->
        <div x-show="activeTab === 'contact'" class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor WhatsApp (Paling Penting)</label>
                    <input type="text" name="contact_wa" value="{{ $settings['contact_wa'] ?? '+6281234567890' }}" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-emerald-500 font-bold text-slate-900">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Layanan</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'hello@wonderfultoba.id' }}" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="md:col-span-2 space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Kantor</label>
                    <textarea name="contact_address" rows="2" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">{{ $settings['contact_address'] ?? 'Jl. Sisingamangaraja No.1, Parapat, Danau Toba, Sumatera Utara' }}</textarea>
                </div>
                <div class="md:col-span-2 space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Google Maps Embed URL</label>
                    <input type="text" name="contact_maps" value="{{ $settings['contact_maps'] ?? 'https://maps.google.com/...' }}" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-toba-green font-bold text-xs text-slate-400">
                </div>
            </div>
        </div>

        <div x-show="['gallery'].includes(activeTab)" class="flex flex-col items-center justify-center h-full text-center space-y-4 py-20" x-cloak>
            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                <i class="fas fa-layer-group text-3xl"></i>
            </div>
            <p class="text-slate-400 font-bold">Modul detail <span x-text="activeTab" class="uppercase"></span> terhubung secara dinamis dengan Media Library.</p>
        </div>
    </div>

    <div class="flex justify-center md:justify-end">
        <button class="w-full md:w-auto px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] md:rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-2xl shadow-slate-200 transition hover:-translate-y-1">Simpan Semua Konfigurasi</button>
    </div>
</div>
@endsection
