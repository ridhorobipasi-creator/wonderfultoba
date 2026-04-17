"use client";

import { useEffect, useState, lazy, Suspense } from 'react';
import { useRouter } from 'next/navigation';
import { motion } from 'framer-motion';
import {
  TrendingUp, CalendarCheck, Package, Car, FileText, Users,
  ArrowUpRight, RefreshCcw, Download, Clock, CheckCircle
} from 'lucide-react';
import { cn } from '@/utils/cn';

const RevenueTrendChart = lazy(() => import('@/components/admin/RevenueTrendChart'));

interface DashboardStats {
  totalBookings: number;
  pendingBookings: number;
  confirmedBookings: number;
  tourPackages: number;
  outboundPackages: number;
  totalCars: number;
  totalBlogs: number;
  totalRevenue: number;
  recentBookings: RecentBooking[];
}

interface RecentBooking {
  customer_name: string;
  type: 'package' | 'car';
  start_date: string;
  total_price: number;
  status: 'confirmed' | 'pending' | 'cancelled' | 'completed';
}

interface StatCardProps {
  title: string;
  value: string | number;
  icon: React.ComponentType<{ size?: number }>;
  color: string;
  sub?: string;
  href?: string;
  delay: number;
}

const DUMMY: DashboardStats = {
  totalBookings: 0, pendingBookings: 0, confirmedBookings: 0,
  tourPackages: 0, outboundPackages: 0, totalCars: 0, totalBlogs: 0,
  totalRevenue: 0, recentBookings: [],
};

const weekData = [
  { name: 'Sen', value: 450 }, { name: 'Sel', value: 300 },
  { name: 'Rab', value: 620 }, { name: 'Kam', value: 810 },
  { name: 'Jum', value: 540 }, { name: 'Sab', value: 970 },
  { name: 'Min', value: 1100 },
];

export default function AdminDashboard() {
  const router = useRouter();
  const [stats, setStats] = useState<DashboardStats>(DUMMY);
  const [loading, setLoading] = useState(true);

  const fetchStats = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/dashboard');
      if (res.ok) setStats(await res.json());
    } catch {
      console.warn('Dashboard: API unavailable — using defaults');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => { fetchStats(); }, []);

  const exportJSON = () => {
    const blob = new Blob([JSON.stringify(stats.recentBookings, null, 2)], { type: 'application/json' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'laporan-reservasi.json';
    a.click();
  };

  const StatCard = ({ title, value, icon: Icon, color, sub, href, delay }: StatCardProps) => (
    <motion.div
      initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay }}
      onClick={() => href && router.push(href)}
      className={cn(
        'bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group',
        href && 'cursor-pointer'
      )}
    >
      <div className="flex justify-between items-start mb-5">
        <div className={cn('w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300', color)}>
          <Icon size={22} />
        </div>
        {href && <ArrowUpRight size={14} className="text-slate-300 group-hover:text-toba-green transition-colors" />}
      </div>
      <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">{title}</p>
      <h3 className={cn('text-2xl font-black tracking-tight', loading ? 'text-slate-200 animate-pulse' : 'text-slate-900')}>
        {loading ? '—' : value}
      </h3>
      {sub && <p className="text-xs text-slate-400 mt-1 font-medium">{sub}</p>}
    </motion.div>
  );

  const statusBadge = (status: string) => ({
    confirmed: 'bg-emerald-50 text-emerald-600 border-emerald-100',
    pending: 'bg-amber-50 text-amber-600 border-amber-100',
    cancelled: 'bg-rose-50 text-rose-600 border-rose-100',
  }[status] ?? 'bg-slate-50 text-slate-400 border-slate-100');

  return (
    <div className="space-y-10 pb-12">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight">Pusat Komando</h1>
          <p className="text-slate-400 font-medium mt-1 text-sm">Selamat datang di panel admin Wonderful Toba 👋</p>
        </div>
        <div className="flex gap-3">
          <button
            onClick={fetchStats}
            className="p-3 bg-white border border-slate-100 rounded-2xl hover:border-toba-green hover:text-toba-green text-slate-400 transition-all shadow-sm"
          >
            <RefreshCcw size={18} className={loading ? 'animate-spin' : ''} />
          </button>
          <button
            onClick={exportJSON}
            className="flex items-center gap-2 px-5 py-3 bg-toba-green text-white rounded-2xl font-bold text-sm hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20"
          >
            <Download size={16} />
            Ekspor Laporan
          </button>
        </div>
      </div>

      {/* Tour Stats */}
      <div>
        <h2 className="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-4">🏔️ Tour & Travel</h2>
        <div className="grid grid-cols-2 md:grid-cols-3 gap-5">
          <StatCard title="Total Reservasi" value={stats.totalBookings} icon={CalendarCheck}
            color="bg-toba-green text-white" href="/admin/tour/bookings" delay={0.1}
            sub={`${stats.pendingBookings} pending`} />
          <StatCard title="Estimasi Omzet" value={`Rp ${stats.totalRevenue.toLocaleString('id-ID')}`}
            icon={TrendingUp} color="bg-obaja-blue text-white" href="/admin/tour/bookings" delay={0.15} />
          <StatCard title="Paket Tour Aktif" value={stats.tourPackages} icon={Package}
            color="bg-amber-500 text-white" href="/admin/tour/packages" delay={0.2} />
        </div>
      </div>

      {/* Secondary Stats */}
      <div>
        <h2 className="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-4">📋 Konten & Performa</h2>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-5">
          <StatCard title="Terkonfirmasi" value={stats.confirmedBookings} icon={CheckCircle}
            color="bg-emerald-500 text-white" href="/admin/tour/bookings" delay={0.3} />
          <StatCard title="Paket Outbound" value={stats.outboundPackages} icon={Package}
            color="bg-orange-500 text-white" href="/admin/outbound/packages" delay={0.35} />
          <StatCard title="Artikel Blog" value={stats.totalBlogs} icon={FileText}
            color="bg-purple-500 text-white" href="/admin/tour/blog" delay={0.4} />
          <StatCard title="Armada Mobil" value={stats.totalCars} icon={Car}
            color="bg-blue-600 text-white" href="/admin/tour/cars" delay={0.45} />
        </div>
      </div>

      {/* Top Packages Section */}
      <div>
        <h2 className="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-4">🔥 Paket Terpopuler</h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
          {[
            { name: 'Explore Danau Toba 3D2N', sales: 42, color: 'border-toba-green' },
            { name: 'Bukit Lawang Jungle Tour', sales: 28, color: 'border-amber-500' },
            { name: 'Samosir Cultural Heritage', sales: 15, color: 'border-obaja-blue' },
          ].map((item, idx) => (
            <div key={idx} className={cn("bg-white p-6 rounded-3xl border-l-4 shadow-sm flex justify-between items-center", item.color)}>
              <div>
                <p className="font-black text-slate-900 text-sm">{item.name}</p>
                <p className="text-xs text-slate-400 font-medium">Banyak diminati bulan ini</p>
              </div>
              <div className="text-right">
                <p className="text-xl font-black text-slate-900">{item.sales}</p>
                <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Bookings</p>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Chart + Recent Bookings */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Chart */}
        <motion.div
          initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.45 }}
          className="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8"
        >
          <div className="flex justify-between items-center mb-8">
            <div>
              <h3 className="text-xl font-black text-slate-900">Tren Pendapatan</h3>
              <p className="text-xs text-slate-400 font-medium mt-0.5">7 hari terakhir</p>
            </div>
            <div className="flex gap-2 bg-slate-50 p-1 rounded-xl border border-slate-100">
              <button className="px-4 py-1.5 bg-white rounded-lg text-xs font-black text-slate-900 shadow-sm">7H</button>
              <button className="px-4 py-1.5 text-xs font-black text-slate-400 hover:text-slate-700 transition-colors">30H</button>
            </div>
          </div>
          <div className="h-72">
            <Suspense fallback={<div className="h-full bg-slate-50 rounded-2xl animate-pulse" />}>
              <RevenueTrendChart data={weekData} />
            </Suspense>
          </div>
        </motion.div>

        {/* Recent Bookings */}
        <motion.div
          initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.5 }}
          className="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 flex flex-col"
        >
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-xl font-black text-slate-900">Reservasi Terbaru</h3>
            <button onClick={() => router.push('/admin/bookings')}
              className="text-xs font-black text-toba-green hover:underline">Lihat Semua</button>
          </div>

          <div className="space-y-4 flex-1">
            {stats.recentBookings.length === 0 ? (
              <div className="flex-1 flex flex-col items-center justify-center text-center py-10">
                <Clock size={32} className="text-slate-200 mb-3" />
                <p className="text-slate-400 text-sm font-medium">Belum ada reservasi</p>
              </div>
            ) : stats.recentBookings.map((b, i) => (
              <div key={i} className="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                <div>
                  <p className="font-bold text-slate-900 text-sm">{b.customer_name}</p>
                  <p className="text-xs text-slate-400">{b.type === 'package' ? 'Paket' : 'Mobil'} · {b.start_date}</p>
                </div>
                <div className="text-right">
                  <p className="font-black text-sm text-slate-900">Rp {Number(b.total_price).toLocaleString('id-ID')}</p>
                  <span className={cn('text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-lg border', statusBadge(b.status))}>
                    {b.status}
                  </span>
                </div>
              </div>
            ))}
          </div>
        </motion.div>
      </div>
    </div>
  );
}
