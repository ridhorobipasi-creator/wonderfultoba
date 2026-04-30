@extends('admin.layout')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm transition hover:shadow-xl hover:shadow-slate-100">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Omzet</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($transactions->sum('totalPrice'), 0, ',', '.') }}</h3>
            <div class="mt-4 flex items-center text-emerald-500 space-x-1">
                <i class="fas fa-arrow-trend-up text-[10px]"></i>
                <span class="text-[9px] font-black uppercase">Confirmed Bookings</span>
            </div>
        </div>
        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm transition hover:shadow-xl hover:shadow-slate-100">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Transaksi</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $transactions->total() }}</h3>
            <div class="mt-4 flex items-center text-slate-400 space-x-1">
                <i class="fas fa-receipt text-[10px]"></i>
                <span class="text-[9px] font-black uppercase">Volume Pesanan</span>
            </div>
        </div>
        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm transition hover:shadow-xl hover:shadow-slate-100">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Rata-rata Transaksi</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($transactions->avg('totalPrice'), 0, ',', '.') }}</h3>
            <div class="mt-4 flex items-center text-blue-500 space-x-1">
                <i class="fas fa-chart-line text-[10px]"></i>
                <span class="text-[9px] font-black uppercase">Average Value</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-10 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-900 tracking-tight">Riwayat Transaksi</h3>
            <button class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 transition hover:-translate-y-1">Ekspor Laporan Excel</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Pesanan</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Customer</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Transaksi</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $booking)
                    <tr class="hover:bg-slate-50/50 transition cursor-default">
                        <td class="px-10 py-8 text-xs font-black text-slate-900">#{{ $booking->id }}</td>
                        <td class="px-10 py-8">
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 font-black text-[10px] uppercase">
                                    {{ substr($booking->customerName, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-slate-700">{{ $booking->customerName }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-8 text-xs font-bold text-slate-400">{{ $booking->createdAt->format('d M Y, H:i') }}</td>
                        <td class="px-10 py-8 text-xs font-black text-slate-900 text-right">Rp {{ number_format($booking->totalPrice, 0, ',', '.') }}</td>
                        <td class="px-10 py-8 text-center">
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                Berhasil
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-3xl text-slate-200 mb-4"></i>
                                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Belum ada data transaksi yang tersimpan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="p-10 border-t border-slate-50">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
