@extends('layouts.app')

@section('title', 'Cara Pembayaran – Sujai Laketoba | Payment Methods for International Guests')
@section('description', 'Panduan lengkap cara pembayaran paket wisata Sujai Laketoba untuk tamu dari Singapura dan Malaysia. Bank transfer, Wise, dan mata uang yang diterima.')
@section('keywords', 'cara pembayaran sujai laketoba, payment methods lake toba tour, bank transfer singapura malaysia, wise payment toba')

@section('content')
<div class="bg-slate-50 min-h-screen pt-32 pb-24">
    <div class="max-w-5xl mx-auto px-6 md:px-8 animate-in fade-in slide-in-from-bottom-8 duration-1000">

        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-semibold uppercase tracking-[0.2em] rounded-full">{{ __('Informasi') }}</span>
            <h1 class="text-3xl md:text-5xl font-semibold text-slate-900 tracking-tight mt-6">
                {{ __('Cara') }} <span class="text-toba-green">{{ __('Pembayaran') }}</span>
            </h1>
            <p class="mt-4 text-slate-600 font-normal max-w-xl mx-auto text-sm leading-relaxed">
                Kami menerima berbagai metode pembayaran untuk memudahkan tamu dari Singapura, Malaysia, dan seluruh dunia.
                <span class="block mt-1 text-slate-400 text-xs">We accept various payment methods for guests from Singapore, Malaysia, and around the world.</span>
            </p>
        </div>

        {{-- Deposit Info --}}
        <div class="card-flat p-6 md:p-8 mb-12 flex gap-5 items-start">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center shrink-0 border border-emerald-100">
                <i class="fas fa-info text-sm"></i>
            </div>
            <div>
                <h3 class="text-slate-900 font-semibold text-base mb-1.5">Sistem Deposit / Booking Confirmation</h3>
                <p class="text-slate-600 font-normal text-sm leading-relaxed">Pemesanan dikonfirmasi setelah DP (uang muka) <strong class="text-slate-900 font-semibold">30–50% dari total harga paket</strong> diterima. Pelunasan dilakukan paling lambat <strong class="text-slate-900 font-semibold">7 hari sebelum keberangkatan</strong>. Untuk grup di bawah 5 orang, pelunasan penuh diminta saat booking.</p>
                <p class="text-slate-400 text-xs mt-2">Booking is confirmed upon receipt of a 30–50% deposit. Full payment is required at least 7 days before departure.</p>
            </div>
        </div>

        {{-- Currency Accepted --}}
        <div class="mb-12">
            <h2 class="text-lg font-bold text-slate-900 mb-6 tracking-tight">Mata Uang yang Diterima / Accepted Currencies</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card-flat p-6 text-center hover:border-emerald-200 transition-all duration-300">
                    <div class="text-3xl mb-3">🇲🇾</div>
                    <p class="font-semibold text-slate-900 text-lg">MYR</p>
                    <p class="text-slate-400 text-xs font-normal mt-0.5">Ringgit Malaysia</p>
                    <p class="text-emerald-700 font-semibold text-xs tracking-wider mt-3">✓ Diterima</p>
                </div>
                <div class="card-flat p-6 text-center hover:border-emerald-200 transition-all duration-300">
                    <div class="text-3xl mb-3">🇸🇬</div>
                    <p class="font-semibold text-slate-900 text-lg">SGD</p>
                    <p class="text-slate-400 text-xs font-normal mt-0.5">Singapore Dollar</p>
                    <p class="text-emerald-700 font-semibold text-xs tracking-wider mt-3">✓ Diterima</p>
                </div>
                <div class="card-flat p-6 text-center hover:border-emerald-200 transition-all duration-300">
                    <div class="text-3xl mb-3">🇮🇩</div>
                    <p class="font-semibold text-slate-900 text-lg">IDR</p>
                    <p class="text-slate-400 text-xs font-normal mt-0.5">Rupiah Indonesia</p>
                    <p class="text-emerald-700 font-semibold text-xs tracking-wider mt-3">✓ Diterima</p>
                </div>
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="mb-12">
            <h2 class="text-lg font-bold text-slate-900 mb-6 tracking-tight">Metode Pembayaran / Payment Methods</h2>
            <div class="space-y-6">

                {{-- Wise --}}
                <div class="card-flat p-6 md:p-8 hover:border-emerald-200 transition-all duration-300">
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                            <span class="font-bold text-base">W</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 text-base mb-1.5">Wise (TransferWise) — <span class="text-emerald-700 font-medium">Rekomendasi untuk Tamu Internasional</span></h3>
                            <p class="text-slate-600 font-normal text-sm leading-relaxed mb-2">Metode paling murah dan tercepat untuk transfer dari Singapura dan Malaysia. Tidak ada biaya tersembunyi dan kurs mendekati nilai pasar.</p>
                            <p class="text-slate-400 text-xs">Best option for international guests. Low fees, transparent exchange rate, supports MYR and SGD.</p>
                            <div class="mt-4 card-flat-soft p-4 text-xs font-normal text-slate-600">
                                <p>📧 Email Wise: <strong>info@sujailaketoba.com</strong></p>
                                <p class="mt-1">Atau hubungi kami via WhatsApp untuk detail rekening Wise terbaru.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bank Transfer --}}
                <div class="card-flat p-6 md:p-8 hover:border-emerald-200 transition-all duration-300">
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-university text-sm"></i>
                        </div>
                        <div class="w-full">
                            <h3 class="font-bold text-slate-900 text-base mb-1.5">Transfer Bank Lokal (Indonesia)</h3>
                            <p class="text-slate-600 font-normal text-sm leading-relaxed mb-4">Untuk tamu yang sudah memiliki akses ke rekening bank Indonesia atau menggunakan agen jasa keuangan.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="card-flat-soft p-4">
                                    <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Bank BCA</p>
                                    <p class="font-bold text-slate-900 text-sm">Hubungi kami untuk no. rekening</p>
                                    <p class="text-xs text-slate-500">a.n. Sujai Laketoba</p>
                                </div>
                                <div class="card-flat-soft p-4">
                                    <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Bank Mandiri</p>
                                    <p class="font-bold text-slate-900 text-sm">Hubungi kami untuk no. rekening</p>
                                    <p class="text-xs text-slate-500">a.n. Sujai Laketoba</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- International SWIFT --}}
                <div class="card-flat p-6 md:p-8 hover:border-emerald-200 transition-all duration-300">
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-globe text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 text-base mb-1.5">Transfer Bank Internasional (SWIFT)</h3>
                            <p class="text-slate-600 font-normal text-sm leading-relaxed mb-2">Untuk transfer dari bank internasional. Harap diperhatikan bahwa biaya SWIFT dan konversi mata uang ditanggung oleh pengirim.</p>
                            <p class="text-slate-400 text-xs">For SWIFT transfers, please note that bank fees and currency conversion charges are borne by the sender.</p>
                            <div class="mt-4 card-flat-soft p-4 text-xs font-normal text-slate-600">
                                <p>Untuk detail SWIFT code dan instruksi transfer internasional lengkap, silakan hubungi kami via WhatsApp: <strong>+62 813-2388-8207</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="mb-12" x-data="{ open: null }">
            <h2 class="text-lg font-bold text-slate-900 mb-6 tracking-tight">Pertanyaan Umum / FAQ</h2>
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
                <div class="card-flat overflow-hidden">
                    <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between p-5 text-left font-semibold text-sm text-slate-900 hover:text-emerald-700 transition-colors outline-none">
                        <span>{{ $faq['q'] }}</span>
                        <svg class="w-4 h-4 shrink-0 ml-4 transition-transform text-slate-400" :class="open === {{ $i }} ? 'rotate-180 text-emerald-700' : ''" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open === {{ $i }}" x-transition class="px-5 pb-5 text-slate-500 font-normal text-xs leading-relaxed border-t border-slate-50/50 pt-3">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CTA --}}
        <div class="card-flat-soft p-8 md:p-12 text-center">
            <h3 class="text-2xl md:text-3xl font-semibold text-slate-900 mb-3">Siap Memesan? / Ready to Book?</h3>
            <p class="text-slate-600 font-normal text-sm mb-8">Hubungi kami via WhatsApp dan kami akan pandu proses pembayaran step-by-step.</p>
            <a href="https://wa.me/6281323888207" target="_blank" class="cta-primary">
                <i class="fab fa-whatsapp text-sm"></i>
                Chat via WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection
