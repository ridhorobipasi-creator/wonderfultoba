@extends('layouts.app')

@section('title', 'Kebijakan Privasi – Wonderful Toba')

@section('content')
<div class="bg-slate-50 min-h-screen pt-32 pb-24">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-sm border border-slate-100">
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-10">Kebijakan <span class="text-toba-green">Privasi</span></h1>
            
            <div class="prose prose-slate max-w-none font-medium text-slate-600 leading-relaxed">
                <p class="mb-8">Terakhir diperbarui: {{ date('d F Y') }}</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">1. Informasi yang Kami Kumpulkan</h3>
                <p class="mb-6">Kami mengumpulkan informasi identitas pribadi (seperti nama, alamat email, dan nomor telepon) saat Anda melakukan pendaftaran akun atau pemesanan layanan di website kami.</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">2. Penggunaan Informasi</h3>
                <p class="mb-6">Informasi yang kami kumpulkan digunakan untuk memproses pesanan Anda, memberikan dukungan pelanggan, dan mengirimkan informasi terkait update layanan atau promosi Wonderful Toba.</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">3. Keamanan Data</h3>
                <p class="mb-6">Kami berkomitmen untuk menjaga keamanan data pribadi Anda. Kami menggunakan teknologi enkripsi dan prosedur keamanan fisik untuk mencegah akses yang tidak sah terhadap database kami.</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">4. Cookies</h3>
                <p class="mb-6">Website kami menggunakan cookies untuk meningkatkan pengalaman browsing Anda. Cookies membantu kami memahami preferensi Anda dan memberikan konten yang lebih relevan.</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">5. Perubahan Kebijakan</h3>
                <p class="mb-6">Wonderful Toba dapat memperbarui Kebijakan Privasi ini sewaktu-waktu. Perubahan akan diberitahukan melalui website ini.</p>
                
                <p class="mt-12 pt-8 border-t border-slate-50">Jika Anda memiliki pertanyaan tentang kebijakan privasi kami, silakan hubungi kami di <span class="text-toba-green font-bold">privacy@wonderfultoba.com</span></p>
            </div>
        </div>
    </div>
</div>
@endsection
