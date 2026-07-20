@extends('layouts.app')

@section('title', 'Tracking Booking ' . $booking->bookingCode . ' | Sujai Laketoba')
@section('description', 'Lihat status booking wisata Sujai Laketoba dengan kode booking.')

@php
    $statusMap = [
        'pending' => [
            'label' => 'Menunggu Konfirmasi',
            'description' => 'Admin akan menghubungi Anda untuk memastikan ketersediaan paket, harga final, dan instruksi pembayaran.',
            'class' => 'bg-amber-50 text-amber-700 border-amber-200',
        ],
        'confirmed' => [
            'label' => 'Dikonfirmasi',
            'description' => 'Booking sudah dikonfirmasi. Silakan simpan kode booking dan invoice Anda.',
            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        ],
        'completed' => [
            'label' => 'Selesai',
            'description' => 'Perjalanan sudah selesai. Terima kasih telah memilih Sujai Laketoba.',
            'class' => 'bg-slate-50 text-slate-700 border-slate-200',
        ],
        'cancelled' => [
            'label' => 'Dibatalkan',
            'description' => 'Booking ini tercatat dibatalkan. Hubungi admin jika perlu bantuan.',
            'class' => 'bg-rose-50 text-rose-700 border-rose-200',
        ],
    ];

    $status = $statusMap[$booking->status] ?? $statusMap['pending'];
    $pax = (int) ($booking->metadata['pax'] ?? 1);
    $packageUrl = $booking->package ? route('tour.package.detail', $booking->package->slug) : route('tour.packages');
    $invoiceUrl = route('invoice.download', $booking->bookingCode);
    $waSource = $siteSettings['cms_tour']['contact_whatsapp']
        ?? $siteSettings['general']['contact_whatsapp']
        ?? $siteSettings['general']['contact_whatsapp']
        ?? $siteSettings['general']['contact_wa_1']
        ?? $siteSettings['general']['contact_whatsapp']
        ?? config('services.whatsapp.number')
        ?? '';
    $waNumber = preg_replace('/[^0-9]/', '', (string) $waSource);
    $waText = urlencode('Halo Sujai Laketoba, saya ingin bertanya tentang booking ' . $booking->bookingCode . '.');
    $steps = [
        'pending' => ['Booking Diterima', 'Menunggu Konfirmasi', 'Dikonfirmasi', 'Trip Selesai'],
        'confirmed' => ['Booking Diterima', 'Menunggu Konfirmasi', 'Dikonfirmasi', 'Trip Selesai'],
        'completed' => ['Booking Diterima', 'Menunggu Konfirmasi', 'Dikonfirmasi', 'Trip Selesai'],
        'cancelled' => ['Booking Diterima', 'Menunggu Konfirmasi', 'Dibatalkan'],
    ];
    $activeSteps = $steps[$booking->status] ?? $steps['pending'];
    // The furthest step actually reached. Each status lands ON its own label,
    // so the last step of a finished or cancelled booking is filled in rather
    // than left grey — the previous numbers stopped one short every time.
    $currentStep = match ($booking->status) {
        'confirmed' => 3,   // Dikonfirmasi
        'completed' => 4,   // Trip Selesai
        'cancelled' => 3,   // Dibatalkan
        default => 2,       // Menunggu Konfirmasi
    };
    $isCancelled = $booking->status === 'cancelled';
@endphp

@section('content')
<section class="relative overflow-hidden bg-slate-950 text-white" x-data="{ copied: false, copyCode() { navigator.clipboard.writeText('{{ $booking->bookingCode }}'); this.copied = true; setTimeout(() => this.copied = false, 1800); } }">
    <div class="absolute inset-0 opacity-30">
        <div class="h-full w-full bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.35),_transparent_38%),linear-gradient(135deg,_#020617,_#0f172a_46%,_#064e3b)]"></div>
    </div>

    <div class="relative mx-auto max-w-5xl px-5 py-20 md:px-8 md:py-28">
        <p class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-300">Tracking Booking</p>
        <h1 class="mt-4 text-3xl font-extrabold leading-tight md:text-5xl">
            {{ $booking->bookingCode }}
        </h1>
        <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-200 md:text-base">
            Simpan kode booking ini saat berkomunikasi dengan admin Sujai Laketoba.
        </p>
        <div class="mt-7 flex flex-col gap-3 sm:flex-row">
            <button type="button" @click="copyCode()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-emerald-50">
                <span class="material-symbols-outlined text-base">content_copy</span>
                <span x-text="copied ? 'Kode Tersalin' : 'Copy Kode Booking'"></span>
            </button>
            <a href="{{ route('booking.track.form') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                <span class="material-symbols-outlined text-base">search</span>
                Cek Kode Lain
            </a>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-10 md:py-16">
    <div class="mx-auto grid max-w-5xl gap-6 px-5 md:grid-cols-[1.1fr_0.9fr] md:px-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Status Saat Ini</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-slate-950">{{ $status['label'] }}</h2>
                </div>
                <span class="inline-flex w-fit items-center rounded-full border px-4 py-2 text-xs font-bold {{ $status['class'] }}">
                    {{ strtoupper($booking->status) }}
                </span>
            </div>

            <p class="mt-6 text-sm leading-7 text-slate-600">
                {{ $status['description'] }}
            </p>

            <div class="mt-8 rounded-xl border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Timeline</p>
                <div class="mt-5 relative pl-2">
                    <!-- Connecting Vertical Line -->
                    <div class="absolute left-[1.4rem] top-4 bottom-4 w-px bg-slate-200"></div>
                    
                    <div class="space-y-6 relative">
                        @foreach($activeSteps as $index => $step)
                            @php
                                $stepNumber = $index + 1;
                                $isDone = $stepNumber <= $currentStep;
                                // A cancellation is the one outcome that must not
                                // read as a completed green step.
                                $isCancelStep = $isCancelled && $stepNumber === $currentStep;
                            @endphp
                            <div class="flex items-start gap-4">
                                <div class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 {{ $isCancelStep ? 'bg-rose-600 border-rose-600 text-white' : ($isDone ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200') }} shadow-sm transition-all duration-300">
                                    @if($isCancelStep)
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @elseif($isDone)
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <span class="h-2.5 w-2.5 rounded-full bg-slate-200"></span>
                                    @endif
                                </div>
                                <div class="pt-1.5">
                                    <p class="text-sm font-bold {{ $isDone ? 'text-slate-900' : 'text-slate-400' }}">{{ $step }}</p>
                                    @if($stepNumber === $currentStep)
                                        <p class="mt-1 text-xs font-semibold {{ $isCancelStep ? 'text-rose-600 bg-rose-50' : 'text-emerald-600 bg-emerald-50' }} px-2 py-0.5 rounded w-fit">Status saat ini</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Paket</p>
                    <p class="mt-2 font-bold text-slate-950">{{ $booking->package->name ?? 'Paket Wisata' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Tanggal Berangkat</p>
                    <p class="mt-2 font-bold text-slate-950">{{ optional($booking->startDate)->translatedFormat('d F Y') ?? '-' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Peserta</p>
                    <p class="mt-2 font-bold text-slate-950">{{ $pax }} Orang</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Estimasi Total</p>
                    <p class="mt-2 font-bold text-slate-950">{{ \App\Helpers\CurrencyHelper::formatIn($booking->totalPrice, $booking->currency) }}</p>
                    @if($booking->currency !== 'IDR')
                    {{-- The amount agreed is above; this is only a reference for
                         transfers from an Indonesian bank, at the rate frozen
                         when the booking was made. --}}
                    <p class="mt-1 text-xs text-slate-400">≈ {{ \App\Helpers\CurrencyHelper::formatIn($booking->totalPrice_idr, 'IDR') }} <span class="whitespace-nowrap">(kurs {{ number_format((float) $booking->exchange_rate_idr, 0, ',', '.') }})</span></p>
                    @endif
                </div>
            </div>

            @if(isset($booking->metadata['price_breakdown']))
            @php
                $pb = $booking->metadata['price_breakdown'];
                // Every figure in this breakdown was recorded in the booking's
                // own currency. Render it as stored — never re-convert, or the
                // customer sees different numbers each time the rate changes.
                $cur = $booking->currency;
            @endphp
            <div class="mt-6 rounded-xl border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Rincian Biaya</p>
                <div class="space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Ekspedisi Dewasa ({{ $pb['pax_dewasa'] }}x)</span>
                        <span>{{ \App\Helpers\CurrencyHelper::formatIn($pb['price_dewasa_total'], $cur) }}</span>
                    </div>
                    @if(isset($pb['pax_anak']) && $pb['pax_anak'] > 0)
                    <div class="flex justify-between">
                        <span>Ekspedisi Anak-Anak ({{ $pb['pax_anak'] }}x)</span>
                        <span>{{ \App\Helpers\CurrencyHelper::formatIn($pb['price_anak_total'], $cur) }}</span>
                    </div>
                    @endif
                    @if(isset($pb['additional_services']))
                        @foreach($pb['additional_services'] as $srv)
                        <div class="flex justify-between">
                            <span>{{ $srv['name'] }}</span>
                            <span>{{ \App\Helpers\CurrencyHelper::formatIn($srv['price'], $cur) }}</span>
                        </div>
                        @endforeach
                    @endif
                    <div class="flex justify-between">
                        <span>Pajak & Layanan ({{ $pb['tax_percentage'] ?? 11 }}%)</span>
                        <span>{{ \App\Helpers\CurrencyHelper::formatIn($pb['tax'] ?? 0, $cur) }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex justify-between font-bold text-slate-950 mt-2">
                        <span>Total Ringkasan</span>
                        <span>{{ \App\Helpers\CurrencyHelper::formatIn($pb['total'] ?? $booking->totalPrice, $cur) }}</span>
                    </div>
                </div>
            </div>
            @endif

            @if($booking->notes)
                <div class="mt-6 rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Catatan</p>
                    <p class="mt-2 text-sm leading-7 text-slate-600">{{ $booking->notes }}</p>
                </div>
            @endif
        </div>

        <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Link Booking</p>
            <div class="mt-5 space-y-3">
                <a href="{{ $invoiceUrl }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-4 text-sm font-bold text-slate-800 transition hover:border-emerald-300 hover:bg-emerald-50">
                    <span>Invoice</span>
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                </a>
                <a href="{{ $packageUrl }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-4 text-sm font-bold text-slate-800 transition hover:border-emerald-300 hover:bg-emerald-50">
                    <span>Lihat Paket</span>
                    <span class="material-symbols-outlined text-base">travel_explore</span>
                </a>
                @if($waNumber)
                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener" class="flex items-center justify-between rounded-xl bg-emerald-600 px-4 py-4 text-sm font-bold text-white transition hover:bg-emerald-700">
                        <span>Hubungi Admin</span>
                        <span class="material-symbols-outlined text-base">chat</span>
                    </a>
                @endif
            </div>

            <div class="mt-6 rounded-xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                Jika ada perubahan tanggal, jumlah peserta, atau titik penjemputan, kirim kode booking ini ke admin.
            </div>
        </aside>
    </div>
</section>
@endsection
