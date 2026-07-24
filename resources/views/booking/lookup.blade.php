@extends('layouts.app')

@section('title', 'Cek Booking | Sujai Laketoba')
@section('description', 'Masukkan kode booking untuk melihat status pesanan wisata Sujai Laketoba.')

@section('content')
<section class="bg-slate-950 text-white">
    <div class="mx-auto max-w-3xl px-5 py-20 text-center md:px-8 md:py-28">
        <p class="text-xs font-bold uppercase tracking-[0.28em] text-green-300">Cek Booking</p>
        <h1 class="mt-4 text-3xl font-extrabold leading-tight md:text-5xl">Lihat Status Booking</h1>
        <p class="mx-auto mt-5 max-w-xl text-sm leading-7 text-slate-200 md:text-base">
            Masukkan kode booking yang Anda dapat setelah mengisi form pemesanan.
        </p>
    </div>
</section>

<section class="bg-slate-50 py-10 md:py-16">
    <div class="mx-auto max-w-xl px-5 md:px-8">
        <form method="POST" action="{{ route('booking.track.lookup') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            @csrf
            <label for="booking_code" class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                Kode Booking
            </label>
            <input
                id="booking_code"
                name="booking_code"
                value="{{ old('booking_code') }}"
                placeholder="Contoh: WT-ABC123"
                class="mt-3 w-full rounded-xl border border-slate-200 px-4 py-4 text-base font-bold uppercase tracking-wide text-slate-950 outline-none transition focus:border-toba-green focus:ring-4 focus:ring-toba-green/10"
                required
            >
            @error('booking_code')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror

            <button type="submit" class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-toba-green px-5 py-4 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-primary-container">
                <span class="material-symbols-outlined text-base">search</span>
                Lihat Track
            </button>
        </form>
    </div>
</section>
@endsection
