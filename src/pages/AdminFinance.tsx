"use client";

import { useState, useEffect } from 'react';
import {
  TrendingUp, Wallet, ArrowUpRight, ArrowDownRight,
  Download, PieChart, CalendarDays, ChevronRight, Loader2
} from 'lucide-react';
import { motion } from 'framer-motion';
import { cn } from '../utils/cn';
import api from '../lib/api';
import { toast } from 'sonner';

interface BookingItem {
  id: number;
  customerName: string;
  itemName?: string;
  totalPrice: number;
  createdAt: string;
  type: string;
  status: string;
}

export default function AdminFinance() {
  const [loading, setLoading] = useState(true);
  const [bookings, setBookings] = useState<BookingItem[]>([]);
  const [stats, setStats] = useState({
    totalRevenue: 0,
    thisMonthRevenue: 0,
    avgTransaction: 0,
    tourRevenue: 0,
    outboundRevenue: 0,
    carRevenue: 0,
    growth: 0,
  });

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    setLoading(true);
    try {
      const res = await api.get('/bookings');
      const data: BookingItem[] = res.data || [];
      setBookings(data);

      // Hitung statistik dari data real
      const confirmed = data.filter(b => b.status === 'confirmed' || b.status === 'completed');
      const total = confirmed.reduce((sum, b) => sum + Number(b.totalPrice), 0);

      const now = new Date();
      const thisMonth = confirmed.filter(b => {
        const d = new Date(b.createdAt);
        return d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear();
      });
      const thisMonthRev = thisMonth.reduce((sum, b) => sum + Number(b.totalPrice), 0);

      const lastMonth = confirmed.filter(b => {
        const d = new Date(b.createdAt);
        const lm = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        return d.getMonth() === lm.getMonth() && d.getFullYear() === lm.getFullYear();
      });
      const lastMonthRev = lastMonth.reduce((sum, b) => sum + Number(b.totalPrice), 0);

      const tourRev = confirmed.filter(b => b.type === 'package').reduce((sum, b) => sum + Number(b.totalPrice), 0);
      const carRev = confirmed.filter(b => b.type === 'car').reduce((sum, b) => sum + Number(b.totalPrice), 0);

      setStats({
        totalRevenue: total,
        thisMonthRevenue: thisMonthRev,
        avgTransaction: confirmed.length > 0 ? Math.round(total / confirmed.length) : 0,
        tourRevenue: tourRev,
        outboundRevenue: 0,
        carRevenue: carRev,
        growth: lastMonthRev > 0 ? Math.round(((thisMonthRev - lastMonthRev) / lastMonthRev) * 100) : 0,
      });
    } catch (error) {
      console.error('Error fetching finance data:', error);
      toast.error('Gagal memuat data keuangan');
    } finally {
      setLoading(false);
    }
  };

  const handleExport = () => {
    toast.info('Fitur ekspor akan segera tersedia');
  };

  const recentBookings = bookings
    .filter(b => b.status === 'confirmed' || b.status === 'completed')
    .slice(0, 10);

  const totalRevenue = stats.tourRevenue + stats.carRevenue + stats.outboundRevenue;
  const categories = [
    { name: 'Tour & Travel', value: stats.tourRevenue, color: 'bg-toba-green' },
    { name: 'Rental Mobil', value: stats.carRevenue, color: 'bg-blue-500' },
  ].filter(c => c.value > 0);

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-[400px]">
        <Loader2 className="w-10 h-10 animate-spin text-toba-green" />
      </div>
    );
  }

  return (
    <div className="space-y-8 pb-12">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h2 className="text-3xl font-black text-slate-900 tracking-tight">Laporan <span className="text-toba-green">Keuangan</span></h2>
          <p className="text-slate-500 font-medium mt-1">Data real dari transaksi yang terkonfirmasi</p>
        </div>
        <button onClick={handleExport} className="flex items-center gap-2 px-6 py-3.5 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg">
          <Download size={18} />
          Ekspor Laporan
        </button>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
        {[
          {
            label: 'Total Pendapatan',
            value: `Rp ${stats.totalRevenue.toLocaleString('id-ID')}`,
            icon: TrendingUp,
            color: 'bg-toba-green',
            shadow: 'shadow-emerald-100',
            trend: null,
          },
          {
            label: 'Bulan Ini',
            value: `Rp ${stats.thisMonthRevenue.toLocaleString('id-ID')}`,
            icon: Wallet,
            color: 'bg-blue-600',
            shadow: 'shadow-blue-100',
            trend: stats.growth,
          },
          {
            label: 'Rata-rata Transaksi',
            value: `Rp ${stats.avgTransaction.toLocaleString('id-ID')}`,
            icon: CalendarDays,
            color: 'bg-amber-500',
            shadow: 'shadow-amber-100',
            trend: null,
          },
        ].map((s, i) => (
          <div key={i} className="bg-white p-7 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div className={cn('w-12 h-12 text-white rounded-2xl flex items-center justify-center mb-5 shadow-lg', s.color, s.shadow)}>
              <s.icon size={22} />
            </div>
            <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">{s.label}</p>
            <h3 className="text-2xl font-black text-slate-900 leading-none">{s.value}</h3>
            {s.trend !== null && (
              <div className={cn('flex items-center gap-1 mt-3 font-bold text-xs', s.trend >= 0 ? 'text-emerald-600' : 'text-rose-500')}>
                {s.trend >= 0 ? <ArrowUpRight size={14} /> : <ArrowDownRight size={14} />}
                <span>{Math.abs(s.trend)}% dari bulan lalu</span>
              </div>
            )}
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Category Breakdown */}
        {categories.length > 0 && (
          <div className="lg:col-span-4">
            <div className="bg-white p-7 rounded-[2rem] border border-slate-100 shadow-sm h-full">
              <div className="flex items-center justify-between mb-7">
                <h3 className="text-lg font-black text-slate-900">Per Kategori</h3>
                <PieChart size={18} className="text-slate-300" />
              </div>
              <div className="space-y-5">
                {categories.map((cat, idx) => (
                  <div key={idx} className="space-y-2">
                    <div className="flex justify-between items-center text-sm font-bold">
                      <span className="text-slate-600">{cat.name}</span>
                      <span className="text-slate-900">Rp {(cat.value / 1000000).toFixed(1)}jt</span>
                    </div>
                    <div className="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                      <motion.div
                        initial={{ width: 0 }}
                        animate={{ width: totalRevenue > 0 ? `${(cat.value / totalRevenue) * 100}%` : '0%' }}
                        transition={{ duration: 1, delay: idx * 0.1 }}
                        className={cn('h-full rounded-full', cat.color)}
                      />
                    </div>
                    <p className="text-xs text-slate-400">
                      {totalRevenue > 0 ? Math.round((cat.value / totalRevenue) * 100) : 0}% dari total
                    </p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}

        {/* Recent Transactions */}
        <div className={categories.length > 0 ? 'lg:col-span-8' : 'lg:col-span-12'}>
          <div className="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div className="p-7 border-b border-slate-50 flex justify-between items-center">
              <h3 className="text-lg font-black text-slate-900">Transaksi Terkonfirmasi</h3>
              <span className="text-xs font-bold text-slate-400">{recentBookings.length} transaksi</span>
            </div>
            {recentBookings.length === 0 ? (
              <div className="py-16 text-center">
                <TrendingUp size={40} className="mx-auto text-slate-200 mb-3" />
                <p className="text-slate-400 font-medium">Belum ada transaksi terkonfirmasi</p>
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left">
                  <thead>
                    <tr className="bg-slate-50/50">
                      <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pelanggan</th>
                      <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Item</th>
                      <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                      <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden lg:table-cell">Tanggal</th>
                      <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-50">
                    {recentBookings.map((b, i) => (
                      <tr key={i} className="hover:bg-slate-50/50 transition-colors">
                        <td className="px-6 py-4">
                          <p className="font-bold text-slate-900 text-sm">{b.customerName}</p>
                          <p className="text-[10px] text-slate-400 uppercase font-bold">{b.type}</p>
                        </td>
                        <td className="px-6 py-4 hidden md:table-cell">
                          <p className="text-sm text-slate-600 font-medium truncate max-w-[180px]">{b.itemName || '-'}</p>
                        </td>
                        <td className="px-6 py-4">
                          <p className="font-black text-slate-900 text-sm">Rp {Number(b.totalPrice).toLocaleString('id-ID')}</p>
                        </td>
                        <td className="px-6 py-4 hidden lg:table-cell">
                          <p className="text-sm text-slate-500">{new Date(b.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</p>
                        </td>
                        <td className="px-6 py-4">
                          <span className={cn(
                            'px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider',
                            b.status === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600'
                          )}>
                            {b.status === 'completed' ? 'Selesai' : 'Confirmed'}
                          </span>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
