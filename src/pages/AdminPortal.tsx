"use client";

import { motion } from 'framer-motion';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { Compass, Users, Package, MapPin, Activity, ArrowRight, LogOut, CheckCircle, Car } from 'lucide-react';
import { useStore } from '../store/useStore';
import { cn } from '../utils/cn';
import api from '../lib/api';
import { toast } from 'sonner';

export default function AdminPortal() {
  const { user, setToken, setUser } = useStore();
  const router = useRouter();

  const handleLogout = async () => {
    try {
      await api.post('/auth/logout');
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      setToken(null);
      setUser(null);
      toast.success('Berhasil keluar dari panel admin');
      router.push('/');
    }
  };

  const portalCards = [
    {
      title: 'Tour & Travel',
      description: 'Manajemen reservasi liburan, penyewaan mobil, dan paket wisata reguler.',
      path: '/admin/tour',
      icon: Compass,
      color: 'blue',
      gradient: 'from-blue-500 to-indigo-600',
      shadow: 'shadow-blue-500/20',
      stats: [
        { label: 'Paket Aktif', value: '12', icon: Package },
        { label: 'Armada', value: '8', icon: Car },
      ]
    },
    {
      title: 'Corporate Outbound',
      description: 'Manajemen vendor venue, reservasi instansi, dan paket experiential learning.',
      path: '/admin/outbound',
      icon: Users,
      color: 'toba-green',
      gradient: 'from-toba-green to-emerald-600',
      shadow: 'shadow-toba-green/20',
      stats: [
        { label: 'Paket Outbound', value: '6', icon: Package },
        { label: 'Venue Partner', value: '25', icon: MapPin },
      ]
    }
  ];

  const recentActivities = [
    { scope: 'outbound', text: 'Universitas Sumatera Utara melakukan reservasi Team Building.', time: '10 menit yang lalu' },
    { scope: 'tour', text: 'Pembayaran Lunas untuk penyewaan Hiace Commuter (INV-0012).', time: '1 jam yang lalu' },
    { scope: 'outbound', text: 'Artikel baru "Manfaat Fun Games" dipublikasikan.', time: '3 jam yang lalu' },
    { scope: 'tour', text: 'Paket "Danau Toba 3H2M" diperbarui harganya.', time: 'Kemarin' },
  ];

  return (
    <div className="min-h-screen bg-[#f8fafc] text-slate-800 font-sans selection:bg-toba-green/20">
      {/* Portal Top Navigation */}
      <nav className="h-20 px-8 bg-white border-b border-slate-200 flex items-center justify-between shadow-sm sticky top-0 z-50">
        <div className="flex items-center space-x-3">
          <div className="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">W</div>
          <div>
            <div className="font-black text-xl tracking-tight leading-none mb-1">Wonderful<span className="text-toba-green">Toba</span></div>
            <div className="text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase leading-none">Super Dashboard</div>
          </div>
        </div>
        
        <div className="flex items-center space-x-6">
          <div className="text-right hidden sm:block">
            <p className="text-sm font-bold text-slate-900 leading-none mb-1">{user?.name || 'Super Admin'}</p>
            <p className="text-[10px] font-bold text-toba-green uppercase tracking-widest">{user?.role || 'Administrator'}</p>
          </div>
          <div className="flex items-center space-x-3 border-l border-slate-200 pl-6">
            <button
              onClick={handleLogout}
              className="flex items-center space-x-2 text-rose-500 hover:bg-rose-50 px-4 py-2 rounded-xl transition-all font-bold text-sm"
            >
              <LogOut size={16} /> <span className="hidden sm:inline">Keluar</span>
            </button>
          </div>
        </div>
      </nav>

      <main className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="text-center mb-16">
          <motion.div initial={{ opacity: 0, y: -20 }} animate={{ opacity: 1, y: 0 }} className="inline-flex items-center space-x-2 px-4 py-2 bg-toba-green/10 text-toba-green rounded-full font-bold uppercase tracking-widest text-xs mb-6 border border-toba-green/20">
            <Activity size={14} /> <span>Pusat Kendali Sistem</span>
          </motion.div>
          <motion.h1 initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.1 }} className="text-4xl md:text-5xl font-black text-slate-900 mb-4 tracking-tight">
            Selamat datang, <span className="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-500">{user?.name?.split(' ')[0] || 'Admin'}</span>
          </motion.h1>
          <motion.p initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 0.2 }} className="text-slate-500 font-medium text-lg max-w-2xl mx-auto">
            Silakan pilih ruang lingkup bisnis yang ingin Anda kelola hari ini. Pengaturan terpisah menjamin keamanan dan kerapian data.
          </motion.p>
        </div>

        {/* The Gateway Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
          {portalCards.map((card, idx) => (
            <motion.div 
              key={card.path}
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.3 + (idx * 0.1) }}
            >
              <Link href={card.path}
                className={cn(
                  "block group relative bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-200 overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl",
                  card.shadow
                )}
              >
                {/* Background Glow */}
                <div className={cn("absolute -top-32 -right-32 w-64 h-64 rounded-full blur-[80px] bg-gradient-to-br opacity-20 group-hover:opacity-40 transition-opacity duration-500", card.gradient)} />
                
                <div className="relative z-10 flex items-start justify-between mb-8">
                  <div className={cn("w-16 h-16 rounded-2xl flex items-center justify-center text-white shadow-lg bg-gradient-to-br", card.gradient)}>
                    <card.icon size={32} />
                  </div>
                  <div className="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                    <ArrowRight size={20} className="group-hover:translate-x-1 transition-transform" />
                  </div>
                </div>

                <div className="relative z-10 mb-8">
                  <h2 className="text-3xl font-black text-slate-900 mb-3 tracking-tight group-hover:text-slate-800 transition-colors">{card.title}</h2>
                  <p className="text-slate-500 font-medium leading-relaxed">{card.description}</p>
                </div>

                <div className="relative z-10 grid grid-cols-2 gap-4">
                  {card.stats.map((stat, i) => (
                    <div key={i} className="bg-slate-50 p-4 rounded-2xl border border-slate-100 group-hover:border-slate-200 transition-colors">
                      <div className="flex items-center space-x-2 mb-2">
                        <stat.icon size={14} className="text-slate-400" />
                        <span className="text-xs font-bold text-slate-500 uppercase tracking-wider">{stat.label}</span>
                      </div>
                      <div className="text-2xl font-black text-slate-900">{stat.value}</div>
                    </div>
                  ))}
                </div>
              </Link>
            </motion.div>
          ))}
        </div>

        {/* Global Analytics Overview */}
        <motion.div 
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.5 }}
          className="bg-white border border-slate-200 rounded-[2.5rem] p-8 lg:p-10 shadow-sm overflow-hidden relative"
        >
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
              <h3 className="text-2xl font-black text-slate-900 tracking-tight mb-2">Aktivitas Terpadu (Global Log)</h3>
              <p className="text-slate-500 font-medium">Gambaran lintas-platform dari reservasi dan operasional terakhir</p>
            </div>
            <div className="px-5 py-2 bg-slate-900 text-white rounded-full text-xs font-bold uppercase tracking-widest flex items-center space-x-2">
              <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
              <span>Sistem Normal</span>
            </div>
          </div>

          <div className="space-y-6">
            {recentActivities.map((act, idx) => (
              <div key={idx} className="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                <div className="mt-1">
                  {act.scope === 'outbound' ? (
                    <div className="w-10 h-10 bg-emerald-50 text-toba-green rounded-xl flex items-center justify-center">
                      <Users size={18} />
                    </div>
                  ) : (
                    <div className="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                      <Compass size={18} />
                    </div>
                  )}
                </div>
                <div className="flex-1">
                  <div className="flex items-center gap-2 mb-1">
                    <span className={cn("text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md", act.scope === 'outbound' ? "bg-emerald-100 text-emerald-700" : "bg-blue-100 text-blue-700")}>
                      {act.scope.toUpperCase()}
                    </span>
                    <span className="text-xs font-bold text-slate-400">{act.time}</span>
                  </div>
                  <p className="font-bold text-slate-700 text-sm md:text-base">{act.text}</p>
                </div>
                <div>
                  <button className="text-slate-400 hover:text-slate-900 transition-colors p-2 rounded-lg hover:bg-white">
                    <ArrowRight size={18} />
                  </button>
                </div>
              </div>
            ))}
          </div>

        </motion.div>
      </main>
    </div>
  );
}
