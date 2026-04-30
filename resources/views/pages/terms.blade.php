@extends('layouts.app')

@section('title', 'Syarat & Ketentuan – Wonderful Toba')

@section('content')
<div class="bg-slate-50 min-h-screen pt-32 pb-24">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-sm border border-slate-100">
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-10">Syarat & <span class="text-toba-green">Ketentuan</span></h1>
            
            <div class="prose prose-slate max-w-none font-medium text-slate-600 leading-relaxed">
                <p class="mb-8">Terakhir diperbarui: {{ date('d F Y') }}</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">1. Pendaftaran & Pemesanan</h3>
                <p class="mb-6">Setiap pemesanan dianggap sah apabila dilakukan melalui website resmi kami atau jalur komunikasi resmi (WhatsApp/Email). Kami berhak meminta uang muka (DP) sebesar 30-50% sebagai tanda jadi pemesanan paket wisata atau rental mobil.</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">2. Pembayaran</h3>
                <p class="mb-6">Pelunasan wajib dilakukan paling lambat 7 hari sebelum tanggal keberangkatan (untuk paket wisata) atau saat serah terima unit (untuk rental mobil). Pembayaran dilakukan melalui transfer bank ke rekening resmi PT Wonderful Toba Experience.</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">3. Pembatalan</h3>
                <ul class="list-disc pl-5 mb-6">
                    <li>Pembatalan > 14 hari sebelum keberangkatan: Pengembalian dana 100% (potong biaya admin).</li>
                    <li>Pembatalan 7-14 hari sebelum keberangkatan: Pengembalian dana 50%.</li>
                    <li>Pembatalan < 7 hari sebelum keberangkatan: Dana tidak dapat dikembalikan.</li>
                </ul>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">4. Kebijakan Rental Mobil</h3>
                <p class="mb-6">Penyewa wajib memiliki SIM A yang masih berlaku. Penggunaan unit terbatas untuk wilayah Sumatera Utara kecuali ada kesepakatan tertulis sebelumnya. Kerusakan akibat kelalaian penyewa sepenuhnya menjadi tanggung jawab penyewa.</p>
                
                <h3 class="text-slate-900 font-black text-xl mb-4">5. Perubahan Jadwal</h3>
                <p class="mb-6">Wonderful Toba berhak mengubah itinerary atau jadwal perjalanan apabila terjadi kondisi force majeure (bencana alam, penutupan akses oleh pemerintah, dll) demi keselamatan dan kenyamanan tamu.</p>
            </div>
        </div>
    </div>
</div>
@endsection
