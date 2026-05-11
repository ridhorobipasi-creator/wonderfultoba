@extends('layouts.app')

@section('title', 'Kebijakan Privasi – Wonderful Toba')

@section('content')
<div class="bg-slate-50 min-h-screen pt-32 pb-24">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-sm border border-slate-100">
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-10">Kebijakan <span class="text-toba-green">Privasi</span></h1>
            
            <div class="prose prose-slate max-w-none font-medium text-slate-600 leading-relaxed">
                <p class="mb-8 text-xs font-bold text-slate-400 uppercase tracking-widest">Terakhir diperbarui: {{ date('d F Y') }}</p>
                
                @if(isset($content['content']))
                    {!! $content['content'] !!}
                @else
                    <h3 class="text-slate-900 font-black text-xl mb-4">Perlindungan Data</h3>
                    <p class="mb-6">Wonderful Toba berkomitmen untuk melindungi privasi pelanggan kami. Kami hanya mengumpulkan informasi yang diperlukan untuk memproses pemesanan Anda dan meningkatkan layanan kami.</p>
                    
                    <h3 class="text-slate-900 font-black text-xl mb-4">Informasi yang Kami Kumpulkan</h3>
                    <p class="mb-6">Informasi yang kami kumpulkan meliputi nama, alamat email, nomor telepon, dan detail perjalanan yang diperlukan untuk koordinasi tour atau kegiatan outbound.</p>
                    
                    <h3 class="text-slate-900 font-black text-xl mb-4">Penggunaan Informasi</h3>
                    <p class="mb-6">Data Anda tidak akan pernah dijual atau dibagikan kepada pihak ketiga untuk tujuan pemasaran tanpa persetujuan eksplisit dari Anda.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
