@extends('layouts.app')

@section('title', 'Cara Pembayaran – Wonderful Toba | Payment Methods for International Guests')
@section('description', 'Panduan lengkap cara pembayaran paket wisata Wonderful Toba untuk tamu dari Singapura dan Malaysia. Bank transfer, Wise, dan mata uang yang diterima.')
@section('keywords', 'cara pembayaran wonderful toba, payment methods lake toba tour, bank transfer singapura malaysia, wise payment toba')

@section('content')
<div class="bg-white min-h-screen pt-32 pb-24">
    <div class="max-w-5xl mx-auto px-6 md:px-8">

        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="text-toba-green font-black text-xs uppercase tracking-[0.4em]">{{ __('Informasi') }}</span>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight mt-4">
                {{ __('Cara') }} <span class="text-toba-green">{{ __('Pembayaran') }}</span>
            </h1>
            <p class="mt-6 text-slate-500 font-medium max-w-2xl mx-auto text-base">
                Kami menerima berbagai metode pembayaran untuk memudahkan tamu dari Singapura, Malaysia, dan seluruh dunia.
                / We accept various payment methods for guests from Singapore, Malaysia, and around the world.
            </p>
        </div>

        {{-- Deposit Info --}}
        <div class="bg-toba-green/5 border border-toba-green/20 rounded-3xl p-8 mb-12 flex gap-6 items-start">
            <div class="w-12 h-12 bg-toba-green rounded-2xl flex items-center justify-center text-white shrink-0">
                <i class="fas fa-info text-xl"></i>
            </div>
            <div>
                <h3 class="text-slate-900 font-black text-lg mb-2">Sistem Deposit / Booking Confirmation</h3>
                <p class="text-slate-600 font-medium">Pemesanan dikonfirmasi setelah DP (uang muka) <strong>30–50% dari total harga paket</strong> diterima. Pelunasan dilakukan paling lambat <strong>7 hari sebelum keberangkatan</strong>. Untuk grup di bawah 5 orang, pelunasan penuh diminta saat booking.</p>
                <p class="text-slate-500 text-sm mt-2">/ Booking is confirmed upon receipt of a <strong>30–50% deposit</strong>. Full payment is required <strong>at least 7 days before departure</strong>.</p>
            </div>
        </div>

        {{-- Currency Accepted --}}
        <div class="mb-12">
            <h2 class="text-2xl font-black text-slate-900 mb-6">Mata Uang yang Diterima / Accepted Currencies</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border border-slate-100 rounded-3xl p-8 text-center hover:border-toba-green/30 hover:shadow-lg transition-all">
                    <div class="text-4xl mb-4">🇲🇾</div>
                    <p class="font-black text-slate-900 text-xl">MYR</p>
                    <p class="text-slate-500 text-sm font-medium mt-1">Ringgit Malaysia</p>
                    <p class="text-toba-green font-black text-xs uppercase tracking-widest mt-3">✓ Diterima</p>
                </div>
                <div class="border border-slate-100 rounded-3xl p-8 text-center hover:border-toba-green/30 hover:shadow-lg transition-all">
                    <div class="text-4xl mb-4">🇸🇬</div>
                    <p class="font-black text-slate-900 text-xl">SGD</p>
                    <p class="text-slate-500 text-sm font-medium mt-1">Singapore Dollar</p>
                    <p class="text-toba-green font-black text-xs uppercase tracking-widest mt-3">✓ Diterima</p>
                </div>
                <div class="border border-slate-100 rounded-3xl p-8 text-center hover:border-toba-green/30 hover:shadow-lg transition-all">
                    <div class="text-4xl mb-4">🇮🇩</div>
                    <p class="font-black text-slate-900 text-xl">IDR</p>
                    <p class="text-slate-500 text-sm font-medium mt-1">Rupiah Indonesia</p>
                    <p class="text-toba-green font-black text-xs uppercase tracking-widest mt-3">✓ Diterima</p>
                </div>
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="mb-12">
            <h2 class="text-2xl font-black text-slate-900 mb-6">Metode Pembayaran / Payment Methods</h2>
            <div class="space-y-6">

                {{-- Wise --}}
                <div class="border border-slate-100 rounded-3xl p-8 hover:border-toba-green/20 hover:shadow-md transition-all">
                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center shrink-0">
                            <span class="text-emerald-600 font-black text-lg">W</span>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-lg mb-2">Wise (TransferWise) — <span class="text-toba-green">Rekomendasi untuk Tamu Internasional</span></h3>
                            <p class="text-slate-600 font-medium mb-3">Metode paling murah dan tercepat untuk transfer dari Singapura dan Malaysia. Tidak ada biaya tersembunyi dan kurs mendekati nilai pasar.</p>
                            <p class="text-slate-500 text-sm">/ Best option for international guests. Low fees, transparent exchange rate, supports MYR and SGD.</p>
                            <div class="mt-4 bg-slate-50 rounded-2xl p-4 text-sm font-medium text-slate-600">
                                <p>📧 Email Wise: <strong>info@sujailaketoba.com</strong></p>
                                <p class="mt-1">Atau hubungi kami via WhatsApp untuk detail rekening Wise terbaru.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bank Transfer --}}
                <div class="border border-slate-100 rounded-3xl p-8 hover:border-toba-green/20 hover:shadow-md transition-all">
                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center shrink-0">
                            <i class="fas fa-university text-blue-600 text-xl"></i>
                        </div>
                        <div class="w-full">
                            <h3 class="font-black text-slate-900 text-lg mb-2">Transfer Bank Lokal (Indonesia)</h3>
                            <p class="text-slate-600 font-medium mb-4">Untuk tamu yang sudah memiliki akses ke rekening bank Indonesia atau menggunakan agen jasa keuangan.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-slate-50 rounded-2xl p-4">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bank BCA</p>
                                    <p class="font-black text-slate-900">Hubungi kami untuk no. rekening</p>
                                    <p class="text-sm text-slate-500">a.n. Wonderful Toba</p>
                                </div>
                                <div class="bg-slate-50 rounded-2xl p-4">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bank Mandiri</p>
                                    <p class="font-black text-slate-900">Hubungi kami untuk no. rekening</p>
                                    <p class="text-sm text-slate-500">a.n. Wonderful Toba</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- International SWIFT --}}
                <div class="border border-slate-100 rounded-3xl p-8 hover:border-toba-green/20 hover:shadow-md transition-all">
                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center shrink-0">
                            <i class="fas fa-globe text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-lg mb-2">Transfer Bank Internasional (SWIFT)</h3>
                            <p class="text-slate-600 font-medium mb-3">Untuk transfer dari bank internasional. Harap diperhatikan bahwa biaya SWIFT dan konversi mata uang ditanggung oleh pengirim.</p>
                            <p class="text-slate-500 text-sm">/ For SWIFT transfers, please note that bank fees and currency conversion charges are borne by the sender.</p>
                            <div class="mt-4 bg-slate-50 rounded-2xl p-4 text-sm font-medium text-slate-600">
                                <p>Untuk detail SWIFT code dan instruksi transfer internasional lengkap, silakan hubungi kami via WhatsApp: <strong>+62 813-2388-8207</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="mb-12" x-data="{ open: null }">
            <h2 class="text-2xl font-black text-slate-900 mb-6">Pertanyaan Umum / FAQ</h2>
            @php
            $faqs = [
                ['q' => 'Apakah saya bisa membayar dengan kartu kredit?', 'a' => 'Saat ini kami belum menerima pembayaran kartu kredit langsung. Namun Anda bisa menggunakan Wise yang mendukung transfer dari rekening kartu. Hubungi kami untuk alternatif lain.'],
                ['q' => 'Berapa lama waktu yang dibutuhkan untuk konfirmasi setelah transfer?', 'a' => 'Konfirmasi diberikan dalam 1x24 jam kerja setelah dana diterima. Untuk transfer via Wise biasanya lebih cepat (1-4 jam). Kirimkan bukti transfer ke WhatsApp kami untuk mempercepat proses.'],
                ['q' => 'Apakah harga paket sudah termasuk semua biaya?', 'a' => 'Setiap paket memiliki daftar "Termasuk" dan "Tidak Termasuk" yang jelas. Biasanya tidak termasuk: tiket pesawat, visa, pengeluaran pribadi, dan makanan di luar itinerary.'],
                ['q' => 'Bisakah saya melihat harga dalam MYR atau SGD?', 'a' => 'Ya! Gunakan toggle bahasa di pojok kanan atas (🇲🇾 / 🇸🇬) untuk melihat harga dikonversi otomatis menggunakan kurs hari ini.'],
            ];
            @endphp
            <div class="space-y-4">
                @foreach($faqs as $i => $faq)
                <div class="border border-slate-100 rounded-3xl overflow-hidden">
                    <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between p-6 text-left font-black text-slate-900 hover:text-toba-green transition-colors">
                        <span>{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 shrink-0 ml-4 transition-transform" :class="open === {{ $i }} ? 'rotate-180 text-toba-green' : ''" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open === {{ $i }}" x-transition class="px-6 pb-6 text-slate-600 font-medium">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CTA --}}
        <div class="bg-slate-900 rounded-[3rem] p-10 md:p-16 text-center">
            <h3 class="text-3xl font-black text-white mb-4">Siap Memesan? / Ready to Book?</h3>
            <p class="text-slate-400 font-medium mb-8">Hubungi kami via WhatsApp dan kami akan panduan proses pembayaran step-by-step.</p>
            <a href="https://wa.me/6281323888207" target="_blank" class="inline-flex items-center gap-3 bg-toba-green text-white px-10 py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-white hover:text-slate-900 transition-all">
                <i class="fab fa-whatsapp text-xl"></i>
                Chat via WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection
