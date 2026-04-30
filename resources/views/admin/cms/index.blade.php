@extends('admin.layout')

@section('title', 'CMS Halaman Utama')
@section('page-title', 'CMS Halaman Utama')

@section('content')
<div class="space-y-8">
    <form action="{{ route('admin.cms.save', 'cms_landing') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Branding Tengah -->
        <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Branding Tengah</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Logo & Identitas Utama</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Logo Text (Contoh: Wonderful Toba)</label>
                    <input type="text" name="brand_name" value="{{ $settings['brand_name'] ?? 'Wonderful Toba' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tagline (Contoh: SUMATERA UTARA)</label>
                    <input type="text" name="brand_tagline" value="{{ $settings['brand_tagline'] ?? 'SUMATERA UTARA' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Sisi Kiri: Outbound (B2B) -->
            <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm border-l-[8px] border-l-emerald-600">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Sisi Kiri (Outbound)</h3>
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mt-1">Target B2B / Corporate</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul (Gunakan \n untuk baris baru)</label>
                        <textarea name="outbound_title" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-600 font-black text-2xl text-slate-900">{{ $settings['outbound_title'] ?? "Corporate\nOutbound." }}</textarea>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sub-judul (Value Proposition)</label>
                        <textarea name="outbound_subtitle" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-600 font-bold text-slate-600">{{ $settings['outbound_subtitle'] ?? 'Solusi team building & gathering profesional untuk instansi Anda. Tersedia di puluhan hotel premium Sumut.' }}</textarea>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Latar Belakang (URL atau Upload)</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="text" name="outbound_image_url" value="{{ $settings['outbound_image_url'] ?? 'https://images.unsplash.com/photo-1544735049-717bc392183e' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-600 font-bold text-xs text-slate-400">
                            </div>
                            <button type="button" class="px-6 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest">Ganti</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan: Tour & Travel (B2C) -->
            <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm border-l-[8px] border-l-toba-green">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-toba-green flex items-center justify-center">
                            <i class="fas fa-compass"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Sisi Kanan (Tour)</h3>
                            <p class="text-[10px] font-black text-toba-green uppercase tracking-widest mt-1">Target B2C / Retail</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul (Gunakan \n untuk baris baru)</label>
                        <textarea name="tour_title" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-black text-2xl text-slate-900">{{ $settings['tour_title'] ?? "Tour &\nTravel." }}</textarea>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sub-judul (Value Proposition)</label>
                        <textarea name="tour_subtitle" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-600">{{ $settings['tour_subtitle'] ?? 'Eksplorasi keindahan Danau Toba, Berastagi, dan alam liar Bukit Lawang dengan paket liburan eksklusif kami.' }}</textarea>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Latar Belakang (URL atau Upload)</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="text" name="tour_image_url" value="{{ $settings['tour_image_url'] ?? 'https://images.unsplash.com/photo-1568449039662-3582576da56b' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-xs text-slate-400">
                            </div>
                            <button type="button" class="px-6 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest">Ganti</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-12 py-5 bg-slate-900 text-white rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-2xl shadow-slate-200 transition hover:-translate-y-1">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
