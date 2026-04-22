"use client";

import { useStore } from '../store/useStore';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import api from '../lib/api';
import { LogOut, User, Mail, Shield, Package, Car, ArrowRight, Settings } from 'lucide-react';
import { motion } from 'framer-motion';
import { useEffect } from 'react';

const menuItems = [
  { icon: Package, label: 'Pemesanan Saya', desc: 'Lihat riwayat dan status pemesanan', href: '/my-bookings', color: 'text-toba-green bg-toba-green/8' },
  { icon: Car, label: 'Rental Mobil', desc: 'Kelola sewa kendaraan Anda', href: '/cars', color: 'text-obaja-blue bg-blue-50' },
  { icon: Settings, label: 'Pengaturan Akun', desc: 'Ubah preferensi dan notifikasi', href: '#', color: 'text-slate-500 bg-slate-100' },
];

export default function Profile() {
  const { user, setUser, setToken } = useStore();
  const router = useRouter();

  useEffect(() => {
    if (!user) {
      router.push('/');
    }
  }, [user, router]);

  const handleLogout = async () => {
    try { await api.post('/auth/logout'); } catch {}
    setUser(null);
    setToken(null);
    router.push('/');
  };

  if (!user) return null;

  const roleColor = user.role === 'admin'
    ? 'bg-rose-500/20 text-rose-300'
    : user.role === 'staff'
    ? 'bg-obaja-blue/20 text-blue-300'
    : 'bg-toba-green/20 text-toba-accent';

  return (
    <div className="min-h-screen bg-slate-50 pt-28 pb-24 px-4">
      <div className="max-w-3xl mx-auto space-y-6">

        {/* Profile Card */}
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}
          className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
          {/* Banner */}
          <div className="h-32 bg-gradient-to-r from-slate-900 via-slate-800 to-toba-green relative overflow-hidden">
            <div className="absolute inset-0 opacity-20">
              <img src="/assets/images/2023/10/001-1.jpg" alt="" className="w-full h-full object-cover" />
            </div>
          </div>
          {/* Avatar & Info */}
          <div className="px-8 pb-8">
            <div className="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 -mt-12 mb-6">
              <div className="w-24 h-24 rounded-[1.5rem] overflow-hidden ring-4 ring-white shadow-xl shrink-0">
                <img src={user.photoURL || `https://i.pravatar.cc/200?u=${user.uid}`}
                  alt={user.name} className="w-full h-full object-cover" referrerPolicy="no-referrer" />
              </div>
              {user.role !== 'user' && (
                <Link href="/admin" className="flex items-center gap-2 px-5 py-2.5 bg-toba-green text-white rounded-2xl font-bold text-sm hover:bg-toba-accent transition-all shadow-lg shadow-toba-green/20">
                  Dashboard Admin <ArrowRight size={15} />
                </Link>
              )}
            </div>
            <h1 className="text-2xl font-black text-slate-900 mb-1">{user.name || 'Pengguna'}</h1>
            <div className="flex items-center gap-3 flex-wrap">
              <span className={`inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest ${roleColor}`}>
                {user.role}
              </span>
              <span className="text-slate-400 text-sm font-medium">{user.email}</span>
            </div>
          </div>
        </motion.div>

        {/* Info Cards */}
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.1 }}
          className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
          <h2 className="text-sm font-black text-slate-400 uppercase tracking-widest mb-5">Informasi Akun</h2>
          <div className="space-y-4">
            {[
              { icon: User, label: 'Nama Lengkap', value: user.name || '-' },
              { icon: Mail, label: 'Alamat Email', value: user.email },
              { icon: Shield, label: 'Peran', value: user.role.charAt(0).toUpperCase() + user.role.slice(1) },
            ].map((item, i) => (
              <div key={i} className="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
                <div className="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-toba-green shadow-sm border border-slate-100 shrink-0">
                  <item.icon size={18} />
                </div>
                <div className="min-w-0">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">{item.label}</p>
                  <p className="font-bold text-slate-900 truncate">{item.value}</p>
                </div>
              </div>
            ))}
          </div>
        </motion.div>

        {/* Quick Menu */}
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.2 }}
          className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
          <h2 className="text-sm font-black text-slate-400 uppercase tracking-widest mb-5">Menu Cepat</h2>
          <div className="space-y-3">
            {menuItems.map((item, i) => (
              <Link key={i} href={item.href}
                className="flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-all group">
                <div className={`w-11 h-11 rounded-xl flex items-center justify-center shrink-0 ${item.color}`}>
                  <item.icon size={20} />
                </div>
                <div className="flex-1 min-w-0">
                  <p className="font-bold text-slate-900 text-sm group-hover:text-toba-green transition-colors">{item.label}</p>
                  <p className="text-xs text-slate-400 font-medium">{item.desc}</p>
                </div>
                <ArrowRight size={16} className="text-slate-300 group-hover:text-toba-green group-hover:translate-x-1 transition-all" />
              </Link>
            ))}
          </div>
        </motion.div>

        {/* Logout */}
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.3 }}>
          <button onClick={handleLogout}
            className="w-full py-4 bg-white border border-rose-100 text-rose-600 rounded-2xl font-bold hover:bg-rose-50 transition-all flex items-center justify-center gap-2 shadow-sm">
            <LogOut size={18} /> Keluar dari Akun
          </button>
        </motion.div>
      </div>
    </div>
  );
}
