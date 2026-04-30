@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Pusat Kontrol')

@section('content')
<div x-data="{ 
    data: {{ json_encode($stats) }},
    isRefreshing: false,
    async refreshData() {
        this.isRefreshing = true;
        try {
            const response = await fetch('{{ route('admin.dashboard.stats') }}');
            this.data = await response.json();
        } catch (error) {
            console.error('Failed to refresh stats:', error);
        } finally {
            this.isRefreshing = false;
        }
    },
    exportData() {
        const blob = new Blob([JSON.stringify(this.data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `report-${new Date().toISOString().split('T')[0]}.json`;
        a.click();
    }
}" x-init="setInterval(() => refreshData(), 10000)" class="space-y-12">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Kondisi Bisnis</h2>
            <div class="flex items-center space-x-3 mt-2">
                <div class="flex items-center space-x-1.5">
                    <div class="w-2 h-2 rounded-full bg-toba-green animate-pulse"></div>
                    <span class="text-[10px] font-black text-toba-green uppercase tracking-widest">Live Monitoring</span>
                </div>
                <span class="text-slate-300">|</span>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="'Terakhir update: ' + new Date().toLocaleTimeString()">{{ now()->format('H:i:s') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <button @click="exportData()" class="px-8 py-4 bg-white border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 hover:border-slate-300 transition shadow-sm">
                Ekspor Laporan
            </button>
            <button @click="refreshData()" class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-xl shadow-slate-200 transition hover:rotate-180" :class="isRefreshing ? 'animate-spin' : ''">
                <i class="fas fa-sync-alt text-xs"></i>
            </button>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Reservasi -->
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-100 group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-toba-green/10 text-toba-green flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check text-lg"></i>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Reservasi</p>
            <div class="flex items-baseline space-x-2">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" x-text="data.total_reservations || data.bookings.active">{{ $stats['bookings']['active'] }}</h3>
                <span class="text-[10px] font-bold text-slate-400">Unit</span>
            </div>
        </div>

        <!-- Estimasi Omzet -->
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-100 group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-money-bill-trend-up text-lg"></i>
                </div>
                <span class="px-2 py-1 bg-emerald-50 text-emerald-500 rounded-lg text-[9px] font-black uppercase">Confirmed Only</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Estimasi Omzet</p>
            <div class="flex items-baseline space-x-1">
                <span class="text-sm font-black text-slate-900">Rp</span>
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" x-text="data.estimated_revenue || (data.revenue.total / 1000).toLocaleString() + 'K'">{{ number_format($stats['revenue']['total'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Paket Aktif -->
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-100 group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-box-archive text-lg"></i>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Paket Aktif</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight" x-text="data.active_packages || data.packages.active">{{ $stats['packages']['active'] }}</h3>
        </div>

        <!-- Armada Mobil -->
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm transition-all hover:shadow-xl hover:shadow-slate-100 group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-van-shuttle text-lg"></i>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Armada Mobil</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight" x-text="data.total_cars || data.cars.total">{{ $stats['cars']['total'] }}</h3>
        </div>
    </div>

    <!-- Main Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 7-Day Revenue Trend -->
        <div class="lg:col-span-2 bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Tren Pendapatan</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">7 Hari Terakhir</p>
                </div>
            </div>
            
            <div class="h-80 flex items-end space-x-4">
                <template x-for="day in data.revenue_7d" :key="day.date">
                    <div class="flex-1 flex flex-col items-center group h-full justify-end">
                        <div class="w-full bg-slate-50 rounded-2xl relative overflow-hidden transition-all group-hover:bg-toba-green/10"
                             :style="`height: ${(day.total / Math.max(...data.revenue_7d.map(d => d.total || 1))) * 100}%`"
                             :title="`Rp ${day.total.toLocaleString()}`">
                            <div class="absolute inset-x-0 bottom-0 bg-toba-green rounded-2xl transition-all duration-1000" style="height: 0%" 
                                 x-init="setTimeout(() => $el.style.height = '100%', 500)"></div>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase mt-4" x-text="day.label"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Reservasi Terbaru -->
        <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Reservasi Terbaru</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-[10px] font-black text-toba-green uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            
            <div class="flex-1 space-y-6 overflow-y-auto custom-scrollbar max-h-[350px] pr-2">
                <template x-for="booking in (data.recent_bookings || data.stats.recent_bookings)" :key="booking.id">
                    <div class="flex items-center justify-between group p-3 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs uppercase" x-text="booking.customerName.charAt(0)"></div>
                            <div>
                                <p class="text-xs font-black text-slate-900 tracking-tight" x-text="booking.customerName"></p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest" x-text="new Date(booking.createdAt).toLocaleDateString('id-ID', {day:'numeric', month:'short'})"></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-slate-900" x-text="'Rp ' + (booking.totalPrice/1000).toFixed(0) + 'K'"></p>
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-tighter" 
                                  :class="booking.status === 'confirmed' ? 'bg-emerald-50 text-emerald-500' : 'bg-amber-50 text-amber-500'"
                                  x-text="booking.status"></span>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="mt-8">
                <a href="{{ route('admin.bookings.index') }}" class="block w-full py-5 bg-slate-900 text-white rounded-[1.5rem] text-center text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 transition hover:-translate-y-1 hover:shadow-2xl">
                    Kelola Semua Pesanan
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
