@extends('admin.layout')

@section('title', 'CMS Beranda Outbound')
@section('page-title', 'CMS Beranda Outbound')

@section('content')
<div class="space-y-10">
    <div class="bg-white rounded-[3.5rem] p-10 lg:p-16 border border-slate-100 shadow-sm">
        <div class="flex items-center space-x-6 mb-12">
            <div class="w-16 h-16 rounded-3xl bg-slate-900 text-white flex items-center justify-center text-xl shadow-xl shadow-slate-200">
                <i class="fas fa-building-user"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Halaman Khusus Corporate</h3>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Landing Page B2B / Outbound</p>
            </div>
        </div>

        <div class="space-y-10">
            <!-- Hero Section -->
            <div class="p-10 rounded-[2.5rem] bg-slate-50/50 border border-slate-100 space-y-8">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Hero Configuration</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-900 uppercase tracking-widest ml-1">Judul Utama</label>
                        <input type="text" value="Solusi Team Building Terbaik" class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-slate-900 font-bold text-slate-900">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-900 uppercase tracking-widest ml-1">Subjudul</label>
                        <input type="text" value="Meningkatkan produktivitas dan sinergi tim Anda" class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-slate-900 font-bold text-slate-900">
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="p-10 rounded-[2.5rem] bg-slate-50/50 border border-slate-100 space-y-8">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Services Highlight</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                        <i class="fas fa-users-gear text-emerald-500"></i>
                        <span class="text-xs font-black text-slate-900 uppercase tracking-widest">Training & Development</span>
                    </div>
                    <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                        <i class="fas fa-fire text-amber-500"></i>
                        <span class="text-xs font-black text-slate-900 uppercase tracking-widest">Team Building Activities</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 flex justify-end">
            <button class="px-12 py-5 bg-slate-900 text-white rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-2xl shadow-slate-200 transition hover:-translate-y-1">Simpan Outbound CMS</button>
        </div>
    </div>
</div>
@endsection
