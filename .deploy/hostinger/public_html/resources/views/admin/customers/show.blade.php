@extends('admin.layout')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Profil Pelanggan')

@section('content')
<div class="space-y-8 pb-20">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition">Edit Profil</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-50 shadow-sm text-center">
                <div class="w-24 h-24 rounded-[2.5rem] bg-indigo-50 text-indigo-500 flex items-center justify-center font-black text-3xl uppercase mx-auto mb-6 shadow-xl shadow-indigo-50">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $customer->name }}</h2>
                <p class="text-xs font-bold text-slate-400 mt-1">{{ $customer->email }}</p>
                
                <div class="grid grid-cols-2 gap-4 mt-10">
                    <div class="p-5 rounded-3xl bg-slate-50 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Trip</p>
                        <p class="text-xl font-black text-slate-900">{{ $customer->total_bookings }}</p>
                    </div>
                    <div class="p-5 rounded-3xl bg-slate-50 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Lifetime Value</p>
                        <p class="text-xl font-black text-emerald-600">Rp {{ number_format($customer->total_spent / 1000, 0) }}K</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-50 shadow-sm space-y-6">
                <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest border-b border-slate-50 pb-4">Informasi Kontak</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 text-xs">
                            <i class="fas fa-phone"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-700">{{ $customer->phone ?? 'Tidak ada telepon' }}</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 text-xs shrink-0">
                            <i class="fas fa-location-dot"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-700 leading-relaxed">{{ $customer->address ?? 'Alamat belum diatur' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking History -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-50 overflow-hidden shadow-sm">
                <div class="px-10 py-8 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Riwayat Perjalanan</h3>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $customer->bookings->count() }} Transaksi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Trip / Layanan</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Tanggal</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($customer->bookings as $booking)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                                <i class="fas {{ $booking->type === 'package' ? 'fa-box-archive' : 'fa-car-side' }} text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">{{ $booking->bookingCode }}</p>
                                                <h4 class="text-xs font-bold text-slate-900">{{ $booking->package->name ?? $booking->car->name ?? 'N/A' }}</h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-center">
                                        <p class="text-xs font-bold text-slate-600">{{ $booking->startDate ? $booking->startDate->format('d M Y') : '-' }}</p>
                                    </td>
                                    <td class="px-10 py-6 text-center">
                                        @php
                                            $colors = [
                                                'pending' => 'bg-amber-50 text-amber-600',
                                                'confirmed' => 'bg-emerald-50 text-emerald-600',
                                                'completed' => 'bg-blue-50 text-blue-600',
                                                'cancelled' => 'bg-rose-50 text-rose-600',
                                            ];
                                            $color = $colors[$booking->status] ?? 'bg-slate-50 text-slate-500';
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $color }}">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <span class="text-xs font-black text-slate-900">Rp {{ number_format($booking->totalPrice / 1000, 0) }}K</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-50 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Catatan Internal Admin</h3>
                    <i class="fas fa-sticky-note text-slate-200"></i>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl min-h-[100px]">
                    <p class="text-xs font-bold text-slate-500 italic leading-relaxed">{{ $customer->notes ?? 'Belum ada catatan khusus untuk pelanggan ini.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
