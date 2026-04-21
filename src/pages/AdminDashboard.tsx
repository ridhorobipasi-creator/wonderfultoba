
"use client";

import React, { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { RefreshCcw, Download, CalendarCheck, ArrowUpRight, TrendingUp, Package, DollarSign } from 'lucide-react';
import RevenueTrendChart from '../components/admin/RevenueTrendChart';
import { cn } from '../utils/cn';
import { toast } from 'sonner';

type Booking = {
  customer_name: string;
  start_date: string;
  total_price: number;
  status: 'confirmed' | 'pending' | 'cancelled';
  type: string;
};

export default function AdminDashboard() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);
  const [refreshing, setRefreshing] = useState(false);
  const [hasError, setHasError] = useState(false);
  const [stats, setStats] = useState<{
    totalBookings: number;
    pendingBookings: number;
    totalRevenue: number;
    tourPackages: number;
    outboundPackages: number;
    recentBookings: Booking[];
    chartData?: Array<{ date: string; revenue: number }>;
  }>({
    totalBookings: 0,
    pendingBookings: 0,
    totalRevenue: 0,
    tourPackages: 0,
    outboundPackages: 0,
    recentBookings: [],
    chartData: [],
  });

  useEffect(() => {
    fetchStats();
    
    // Auto refresh every 10 seconds
    const intervalId = setInterval(() => {
      fetchStats(true); // pass true for silent refresh
    }, 10000);

    return () => clearInterval(intervalId);
  }, []);

  const fetchStats = async (silent = false) => {
    if (!silent) setLoading(true);
    if (silent) setRefreshing(true);
    setHasError(false);
    try {
      const res = await fetch('/api/dashboard');
      if (res.ok) {
        const data = await res.json();
        setStats(data);
        setHasError(false);
      } else {
        const errorText = await res.text();
        console.error('Dashboard API error:', errorText);
        if (!silent) toast.error('Gagal memuat data dashboard. Periksa koneksi database.');
        setHasError(true);
      }
    } catch (error) {
      console.error('Dashboard error:', error);
      if (!silent) toast.error('Database tidak terhubung. Silakan start MySQL server.');
      setHasError(true);
      setStats({
        totalBookings: 0,
        pendingBookings: 0,
        totalRevenue: 0,
        tourPackages: 0,
        outboundPackages: 0,
        recentBookings: [],
        chartData: [],
      });
    } finally {
      if (!silent) setLoading(false);
      setRefreshing(false);
    }
  };

  const handleRefresh = async () => {
    setRefreshing(true);
    await fetchStats();
    setRefreshing(false);
    toast.success('Data berhasil diperbarui');
  };

  const exportReport = () => {
    const reportData = {
      generated_at: new Date().toISOString(),
      summary: {
        total_bookings: stats.totalBookings,
        pending_bookings: stats.pendingBookings,
        total_revenue: stats.totalRevenue,
        tour_packages: stats.tourPackages,
        outbound_packages: stats.outboundPackages,
      },
      recent_bookings: stats.recentBookings,
    };

    const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = `laporan-wonderful-toba-${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    toast.success('Laporan berhasil diunduh');
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(amount);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
    });
  };

  if (loading && !stats.totalBookings) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="text-center">
          <div className="w-16 h-16 border-4 border-emerald-100 border-t-toba-green rounded-full animate-spin mb-4 mx-auto"></div>
          <p className="text-slate-400 font-bold uppercase text-xs tracking-widest">Memuat Dashboard...</p>
        </div>
      </div>
    );
  }

  // Check if database is not connected (only show error if fetch actually failed)
  const isDatabaseError = hasError;

  if (isDatabaseError) {
    return (
      <div className="p-6 lg:p-10">
        <div className="max-w-2xl mx-auto mt-20">
          <div className="bg-white rounded-[2.5rem] border border-red-100 shadow-lg p-12 text-center">
            <div className="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg className="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <h2 className="text-2xl font-black text-slate-900 mb-4">Database Tidak Terhubung</h2>
            <p className="text-slate-600 mb-6 leading-relaxed">
              Panel admin tidak dapat terhubung ke database MySQL. Pastikan MySQL server berjalan di <code className="px-2 py-1 bg-slate-100 rounded text-sm font-mono">localhost:3306</code>
            </p>
            
            <div className="bg-slate-50 rounded-2xl p-6 mb-6 text-left">
              <p className="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Solusi Cepat:</p>
              <ol className="space-y-2 text-sm text-slate-700">
                <li className="flex items-start gap-2">
                  <span className="font-black text-toba-green">1.</span>
                  <span>Buka XAMPP Control Panel dan start MySQL</span>
                </li>
                <li className="flex items-start gap-2">
                  <span className="font-black text-toba-green">2.</span>
                  <span>Atau jalankan: <code className="px-2 py-0.5 bg-white rounded text-xs font-mono">net start MySQL80</code></span>
                </li>
                <li className="flex items-start gap-2">
                  <span className="font-black text-toba-green">3.</span>
                  <span>Refresh halaman ini setelah MySQL berjalan</span>
                </li>
              </ol>
            </div>

            <div className="flex gap-3 justify-center">
              <button
                onClick={handleRefresh}
                className="px-6 py-3 bg-toba-green text-white rounded-2xl font-bold text-sm hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20"
              >
                🔄 Coba Lagi
              </button>
              <a
                href="/DATABASE_SETUP.md"
                target="_blank"
                className="px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all"
              >
                📚 Panduan Setup
              </a>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="p-4 lg:p-6 xl:p-10">
      <div className="space-y-6 lg:space-y-10 pb-12">
        {/* Header */}
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div>
            <div className="flex items-center gap-3">
              <h1 className="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Pusat Komando</h1>
              <div className="bg-emerald-500/10 text-emerald-500 px-2 py-1 rounded-lg flex items-center gap-1.5 border border-emerald-500/20 shadow-sm animate-pulse">
                <div className="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                <span className="text-[10px] font-black uppercase tracking-widest">Live</span>
              </div>
            </div>
            <p className="text-slate-400 font-medium mt-1 text-sm">Selamat datang di panel admin Wonderful Toba 👋</p>
          </div>
          <div className="flex gap-2 lg:gap-3 w-full md:w-auto">
            <button
              onClick={handleRefresh}
              disabled={refreshing}
              className="p-2.5 lg:p-3 bg-white border border-slate-100 rounded-xl lg:rounded-2xl text-slate-400 hover:text-toba-green shadow-sm transition-all disabled:opacity-50"
            >
              <RefreshCcw className={cn("w-4 h-4 lg:w-[18px] lg:h-[18px]", refreshing && "animate-spin")} />
            </button>
            <button
              onClick={exportReport}
              className="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 lg:px-6 py-2.5 lg:py-3 bg-toba-green text-white rounded-xl lg:rounded-2xl font-bold text-xs lg:text-sm shadow-lg shadow-toba-green/20 hover:bg-toba-green/90 transition-all"
            >
              <Download className="w-3.5 h-3.5 lg:w-4 lg:h-4" />
              <span className="hidden sm:inline">Ekspor Laporan</span>
              <span className="sm:hidden">Ekspor</span>
            </button>
          </div>
        </div>

        {/* Stats Cards */}
        <div>
          <h2 className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 lg:mb-6">🏔️ Tour & Travel</h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
            {/* Total Reservasi */}
            <div className="bg-white p-5 lg:p-6 rounded-2xl lg:rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
              <div className="flex justify-between mb-6 lg:mb-8">
                <div className="w-10 h-10 lg:w-12 lg:h-12 bg-toba-green text-white rounded-xl lg:rounded-2xl flex items-center justify-center shadow-lg shadow-toba-green/20">
                  <CalendarCheck className="w-5 h-5 lg:w-6 lg:h-6" />
                </div>
                <ArrowUpRight className="text-slate-200 w-5 h-5" />
              </div>
              <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Reservasi</p>
              <h3 className="text-2xl lg:text-3xl font-black text-slate-900">{stats.totalBookings.toLocaleString('id-ID')}</h3>
              <p className="text-xs text-slate-400 mt-2 font-medium">
                {stats.pendingBookings} pending bulan ini
              </p>
            </div>

            {/* Estimasi Omzet */}
            <div className="bg-white p-5 lg:p-6 rounded-2xl lg:rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
              <div className="flex justify-between mb-6 lg:mb-8">
                <div className="w-10 h-10 lg:w-12 lg:h-12 bg-obaja-blue text-white rounded-xl lg:rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                  <TrendingUp className="w-5 h-5 lg:w-6 lg:h-6" />
                </div>
                <ArrowUpRight className="text-slate-200 w-5 h-5" />
              </div>
              <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Estimasi Omzet</p>
              <h3 className="text-xl lg:text-3xl font-black text-slate-900">{formatCurrency(stats.totalRevenue)}</h3>
              <p className="text-xs text-slate-400 mt-2 font-medium">Berdasarkan total confirmed</p>
            </div>

            {/* Paket Tour Aktif */}
            <div className="bg-white p-5 lg:p-6 rounded-2xl lg:rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow sm:col-span-2 lg:col-span-1">
              <div className="flex justify-between mb-6 lg:mb-8">
                <div className="w-10 h-10 lg:w-12 lg:h-12 bg-amber-500 text-white rounded-xl lg:rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                  <Package className="w-5 h-5 lg:w-6 lg:h-6" />
                </div>
                <ArrowUpRight className="text-slate-200 w-5 h-5" />
              </div>
              <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Paket Aktif</p>
              <h3 className="text-2xl lg:text-3xl font-black text-slate-900">
                {stats.tourPackages + stats.outboundPackages} Paket
              </h3>
              <p className="text-xs text-slate-400 mt-2 font-medium">
                {stats.tourPackages} Tour · {stats.outboundPackages} Outbound
              </p>
            </div>
          </div>
        </div>

        {/* Chart & Recent Bookings */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
          {/* Revenue Chart */}
          <div className="lg:col-span-2 bg-white rounded-2xl lg:rounded-[2.5rem] border border-slate-100 shadow-sm p-5 lg:p-8">
            <div className="flex justify-between items-center mb-6 lg:mb-10">
              <div>
                <h3 className="text-lg lg:text-xl font-black text-slate-900">Tren Pendapatan</h3>
                <p className="text-xs text-slate-400 font-medium mt-1">7 hari terakhir</p>
              </div>
            </div>
            <RevenueTrendChart data={stats.chartData || []} />
          </div>

          {/* Recent Bookings */}
          <div className="bg-white rounded-2xl lg:rounded-[2.5rem] border border-slate-100 shadow-sm p-5 lg:p-8">
            <h3 className="text-lg lg:text-xl font-black text-slate-900 mb-6 lg:mb-8">Reservasi Terbaru</h3>
            <div className="space-y-4 lg:space-y-6">
              {stats.recentBookings.length > 0 ? (
                stats.recentBookings.slice(0, 5).map((booking, idx) => (
                  <div key={idx} className="flex items-center justify-between border-b border-slate-50 pb-3 lg:pb-4 last:border-0">
                    <div className="flex-1 min-w-0 pr-3">
                      <p className="font-bold text-slate-900 text-sm truncate">{booking.customer_name}</p>
                      <p className="text-[10px] text-slate-400 italic truncate">
                        {booking.type} · {formatDate(booking.start_date)}
                      </p>
                    </div>
                    <div className="text-right flex-shrink-0">
                      <p className="text-xs lg:text-sm font-black whitespace-nowrap">{formatCurrency(booking.total_price)}</p>
                      <span
                        className={cn(
                          "inline-block px-2 py-0.5 text-[9px] font-black uppercase tracking-widest rounded-lg border mt-1",
                          booking.status === 'confirmed'
                            ? 'bg-emerald-50 text-emerald-600 border-emerald-100'
                            : booking.status === 'pending'
                            ? 'bg-amber-50 text-amber-600 border-amber-100'
                            : 'bg-rose-50 text-rose-600 border-rose-100'
                        )}
                      >
                        {booking.status}
                      </span>
                    </div>
                  </div>
                ))
              ) : (
                <p className="text-center text-slate-400 py-8">Belum ada reservasi</p>
              )}
            </div>
            <button
              onClick={() => router.push('/admin/bookings')}
              className="w-full mt-6 lg:mt-10 py-3 lg:py-4 bg-slate-50 text-slate-400 font-black text-[10px] uppercase tracking-widest rounded-xl lg:rounded-2xl hover:bg-toba-green hover:text-white transition-all"
            >
              Lihat Semua Reservasi
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
