@extends('layouts.app')

@section('title', 'Tentang Kami – Wonderful Toba')
@section('description', 'Wonderful Toba adalah provider perjalanan wisata dan outbound premium di Sumatera Utara.')

@section('content')
<div class="bg-white min-h-screen pt-32 pb-24">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-block px-4 py-1.5 bg-toba-green/10 text-toba-green text-xs font-black uppercase tracking-[0.3em] rounded-full mb-6">
                    Our Story
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-8 leading-[1.1] md:leading-tight">
                    Mengenal Lebih Dekat <span class="text-toba-green">Wonderful Toba</span>
                </h1>
                <p class="text-lg text-slate-600 font-medium leading-relaxed mb-6">
                    Berawal dari kecintaan terhadap keindahan alam Sumatera Utara, Wonderful Toba hadir untuk memberikan pengalaman perjalanan yang tak terlupakan bagi setiap wisatawan.
                </p>
                <p class="text-lg text-slate-600 font-medium leading-relaxed mb-10">
                    Kami tidak hanya sekadar agen perjalanan, kami adalah mitra eksplorasi Anda yang memahami setiap sudut keajaiban Danau Toba dan sekitarnya. Dengan tim profesional dan layanan premium, kami memastikan setiap momen liburan Anda menjadi cerita yang indah.
                </p>
                
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-4xl font-black text-toba-green mb-2">12+</p>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tahun Pengalaman</p>
                    </div>
                    <div>
                        <p class="text-4xl font-black text-toba-green mb-2">5k+</p>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Wisatawan Puas</p>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-[4/5] rounded-[3rem] overflow-hidden shadow-2xl">
                    <img src="/assets/images/2023/10/003-1.jpg" alt="Wonderful Toba Team" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-8 -left-8 bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100 hidden md:block">
                    <p class="text-slate-900 font-black italic text-xl mb-2">"Layanan terbaik, armada baru, dan guide yang sangat informatif."</p>
                    <p class="text-toba-green font-bold text-sm">— Bpk. Ridho, Corporate Client</p>
                </div>
            </div>
        </div>

        <div class="mt-24 md:mt-32">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6">Visi & Misi Kami</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                <div class="bg-slate-50 p-8 md:p-12 rounded-[2.5rem] md:rounded-[3rem] border border-slate-100">
                    <div class="w-16 h-16 bg-toba-green rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg shadow-toba-green/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Visi</h3>
                    <p class="text-slate-600 font-medium leading-relaxed">Menjadi lokomotif pariwisata Sumatera Utara yang mengedepankan kualitas layanan, keberlanjutan alam, dan kebahagiaan setiap tamu.</p>
                </div>
                <div class="bg-slate-50 p-8 md:p-12 rounded-[2.5rem] md:rounded-[3rem] border border-slate-100">
                    <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Misi</h3>
                    <ul class="space-y-4 text-slate-600 font-medium">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-toba-green mt-1 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Menyediakan paket wisata yang edukatif dan inspiratif.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-toba-green mt-1 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Menjamin kenyamanan dan keamanan transportasi tamu.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-toba-green mt-1 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Mempromosikan kearifan lokal melalui interaksi budaya yang positif.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
