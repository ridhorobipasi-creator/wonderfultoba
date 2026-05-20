@extends('layouts.app')

@section('title', 'Syarat & Ketentuan – Wonderful Toba | Lake Toba Tour Operator')
@section('description', 'Syarat dan ketentuan layanan paket wisata Wonderful Toba. Terms & conditions for Lake Toba tour packages including payment, cancellation, and liability policies.')
@section('keywords', 'syarat ketentuan wonderful toba, tour terms conditions lake toba, kebijakan pemesanan wisata')

@section('content')
<div class="bg-slate-50 min-h-screen pt-32 pb-24">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-sm border border-slate-100">
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-4">Syarat &amp; <span class="text-toba-green">Ketentuan</span></h1>
            
            {{-- Identitas Perusahaan --}}
            <div class="mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-100 text-sm text-slate-500 font-medium">
                <p class="font-black text-slate-700 mb-1">Wonderful Toba</p>
                <p>Nama Domain: <strong>sujailaketoba.com</strong> — Brand resmi: <strong>Wonderful Toba</strong></p>
                <p>Dioperasikan oleh CV/UD Wonderful Toba, berdomisili di Parapat, Sumatera Utara.</p>
                <p class="mt-2 text-xs text-slate-400">Terakhir diperbarui: Juni 2025</p>
            </div>
            
            <div class="prose prose-slate max-w-none font-medium text-slate-600 leading-relaxed">
                @if(isset($content['content']))
                    {!! $content['content'] !!}
                @else
                    <h3 class="text-slate-900 font-black text-xl mb-4">1. Pendaftaran &amp; Pemesanan</h3>
                    <p class="mb-6">Setiap pemesanan dianggap sah apabila dilakukan melalui website resmi <strong>sujailaketoba.com</strong> atau melalui jalur komunikasi resmi (WhatsApp/Email). Kami berhak meminta uang muka (DP) sebesar 30–50% sebagai tanda jadi pemesanan paket wisata sebelum proses konfirmasi akomodasi dilakukan.</p>
                    
                    <h3 class="text-slate-900 font-black text-xl mb-4">2. Pembayaran</h3>
                    <p class="mb-6">Pelunasan wajib dilakukan paling lambat 7 hari sebelum tanggal keberangkatan. Pembayaran dapat dilakukan melalui:</p>
                    <ul class="list-disc pl-5 mb-6">
                        <li>Transfer bank rekening resmi Wonderful Toba (BCA / BNI / Mandiri)</li>
                        <li>Transfer internasional via Wise (Transferwise) untuk tamu dari Singapura dan Malaysia</li>
                        <li>Mata uang yang diterima: IDR (Rupiah), MYR (Ringgit Malaysia), SGD (Singapore Dollar)</li>
                    </ul>
                    <p class="mb-6">Untuk informasi rekening dan detail pembayaran internasional, silakan kunjungi halaman <a href="/payment" class="text-toba-green font-bold hover:underline">Cara Pembayaran</a>.</p>
                    
                    <h3 class="text-slate-900 font-black text-xl mb-4">3. Pembatalan</h3>
                    <ul class="list-disc pl-5 mb-6">
                        <li>Pembatalan &gt; 14 hari sebelum keberangkatan: Pengembalian dana 100% (potong biaya admin Rp 50.000).</li>
                        <li>Pembatalan 7–14 hari sebelum keberangkatan: Pengembalian dana 50%.</li>
                        <li>Pembatalan &lt; 7 hari sebelum keberangkatan: Dana tidak dapat dikembalikan.</li>
                        <li>Pembatalan karena bencana alam atau force majeure: Pengembalian dana penuh atau penjadwalan ulang tanpa biaya.</li>
                    </ul>
                    
                    <h3 class="text-slate-900 font-black text-xl mb-4">4. Tanggung Jawab &amp; Asuransi</h3>
                    <p class="mb-6">Wonderful Toba bertanggung jawab atas keselamatan dan kenyamanan tamu selama program berlangsung sesuai itinerary yang disepakati. Kami menyediakan asuransi perjalanan dasar untuk setiap tamu. Tamu disarankan untuk memiliki asuransi perjalanan pribadi yang mencakup evakuasi medis, terutama untuk tamu internasional.</p>
                    
                    <h3 class="text-slate-900 font-black text-xl mb-4">5. Perubahan Jadwal</h3>
                    <p class="mb-6">Wonderful Toba berhak mengubah itinerary atau jadwal perjalanan apabila terjadi kondisi force majeure (bencana alam, penutupan akses oleh pemerintah, cuaca ekstrem, dll) demi keselamatan dan kenyamanan tamu. Perubahan akan diberitahukan paling lambat 24 jam sebelum keberangkatan.</p>
                    
                    <h3 class="text-slate-900 font-black text-xl mb-4">6. Hak &amp; Kewajiban Tamu</h3>
                    <ul class="list-disc pl-5 mb-6">
                        <li>Tamu wajib mematuhi peraturan dan norma lokal di setiap destinasi yang dikunjungi.</li>
                        <li>Tamu wajib memiliki dokumen perjalanan yang sah (paspor, visa jika diperlukan).</li>
                        <li>Tamu bertanggung jawab atas barang bawaan pribadi masing-masing.</li>
                    </ul>
                    
                    <h3 class="text-slate-900 font-black text-xl mb-4">7. Kontak &amp; Penyelesaian Sengketa</h3>
                    <p class="mb-6">Untuk pertanyaan, keluhan, atau penyelesaian sengketa, tamu dapat menghubungi kami melalui:</p>
                    <ul class="list-none pl-0 mb-6">
                        <li>📧 Email: <a href="mailto:info@sujailaketoba.com" class="text-toba-green font-bold">info@sujailaketoba.com</a></li>
                        <li>📱 WhatsApp: <a href="https://wa.me/6281323888207" class="text-toba-green font-bold">+62 813-2388-8207</a></li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
