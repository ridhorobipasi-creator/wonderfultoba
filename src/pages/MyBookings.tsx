"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Booking } from '../types';
import { useStore } from '../store/useStore';
import { useRouter } from 'next/navigation';
import { motion } from 'framer-motion';
import { Calendar, Package, Car, Clock, CheckCircle, XCircle, ArrowRight } from 'lucide-react';
import { cn } from '../utils/cn';
import { Helmet } from 'react-helmet-async';

interface BookingApiItem {
  id: number;
  type: 'package' | 'car';
  start_date: string;
  end_date: string;
  total_price: number | string;
  status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
  item_details?: {
    name?: string;
    image?: string;
  };
}

export default function MyBookings() {
  const { user } = useStore();
  const router = useRouter();
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState<'all' | 'pending' | 'confirmed' | 'cancelled'>('all');

  useEffect(() => {
    if (!user) { router.push('/'); return; }
    let mounted = true;

    const fetch = async () => {
      try {
        const res = await api.get('/bookings');
        // Map API response (snake_case) to Frontend model (camelCase)
        const mappedData = Array.isArray(res.data) ? (res.data as BookingApiItem[]).map((b) => ({
          ...b,
          startDate: b.start_date,
          endDate: b.end_date,
          totalPrice: Number(b.total_price),
          itemName: b.item_details?.name || (b.type === 'package' ? 'Paket Wisata' : 'Rental Mobil'),
          itemImage: b.item_details?.image
        })) : [];
        if (mounted) {
          setBookings(mappedData);
        }
      } catch (err) {
        console.error(err);
      } finally {
        if (mounted) {
          setLoading(false);
        }
      }
    };

    fetch();

    const intervalId = window.setInterval(fetch, 10000);

    return () => {
      mounted = false;
      window.clearInterval(intervalId);
    };
  }, [user, router]);

  const filtered = filter === 'all' ? bookings : bookings.filter(b => b.status === filter);

  const statusConfig = {
    pending: { icon: Clock, color: 'text-amber-600 bg-amber-50 border-amber-100', label: 'Menunggu' },
    confirmed: { icon: CheckCircle, color: 'text-emerald-600 bg-emerald-50 border-emerald-100', label: 'Dikonfirmasi' },
    cancelled: { icon: XCircle, color: 'text-rose-600 bg-rose-50 border-rose-100', label: 'Dibatalkan' },
    completed: { icon: CheckCircle, color: 'text-blue-600 bg-blue-50 border-blue-100', label: 'Selesai' },
  };

  return (
    <>
      <Helmet>
        <title>Pemesanan Saya – Wonderful Toba</title>
      </Helmet>

      <div className="bg-slate-50 min-h-screen pt-28 pb-24 px-4">
        <div className="max-w-4xl mx-auto">
          <div className="flex items-center justify-between mb-8">
            <div>
              <h1 className="text-3xl font-black text-slate-900 mb-1">Pemesanan Saya</h1>
              <p className="text-slate-500 font-medium">Riwayat dan status semua pemesanan Anda</p>
            </div>
            <button onClick={() => router.push('/profile')} className="flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold transition-colors text-sm">
              <ArrowRight size={16} className="rotate-180" /> Profil
            </button>
          </div>

          {/* Filter tabs */}
          <div className="flex gap-2 mb-6 bg-white p-1.5 rounded-2xl border border-slate-100 shadow-sm w-fit">
            {(['all', 'pending', 'confirmed', 'cancelled'] as const).map(s => (
              <button key={s} onClick={() => setFilter(s)}
                className={cn(
                  "px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all",
                  filter === s ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-400 hover:text-slate-600'
                )}>
                {s === 'all' ? 'Semua' : statusConfig[s].label}
              </button>
            ))}
          </div>

          {loading ? (
            <div className="space-y-4">
              {[1, 2, 3].map(i => <div key={i} className="h-28 bg-white rounded-[1.5rem] animate-pulse border border-slate-100" />)}
            </div>
          ) : filtered.length === 0 ? (
            <div className="text-center py-24 bg-white rounded-[2rem] border border-slate-100">
              <Calendar size={48} className="mx-auto text-slate-200 mb-4" />
              <h3 className="text-xl font-bold text-slate-700 mb-2">Belum ada pemesanan</h3>
              <p className="text-slate-400 mb-6">Mulai jelajahi paket wisata atau rental mobil kami.</p>
              <div className="flex gap-3 justify-center">
                <button onClick={() => router.push('/packages')} className="px-6 py-3 bg-toba-green text-white rounded-2xl font-bold text-sm hover:bg-toba-green/90 transition-all">
                  Lihat Paket Wisata
                </button>
                <button onClick={() => router.push('/cars')} className="px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">
                  Rental Mobil
                </button>
              </div>
            </div>
          ) : (
            <div className="space-y-4">
              {filtered.map((booking, i) => {
                const cfg = statusConfig[booking.status] || statusConfig.pending;
                const StatusIcon = cfg.icon;
                return (
                  <motion.div key={booking.id}
                    initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: i * 0.05 }}
                    className="bg-white rounded-[1.5rem] p-6 shadow-sm border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div className="flex items-center gap-4">
                      <div className={cn("w-12 h-12 rounded-2xl flex items-center justify-center shrink-0", booking.type === 'package' ? 'bg-toba-green/10 text-toba-green' : 'bg-toba-green/10 text-toba-green')}>
                        {booking.type === 'package' ? <Package size={22} /> : <Car size={22} />}
                      </div>
                      <div>
                        <p className="font-black text-slate-900 capitalize leading-tight">{booking.itemName}</p>
                        <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{booking.type === 'package' ? 'Paket Wisata' : 'Rental Mobil'}</p>
                        <div className="flex items-center gap-3 mt-2 text-xs text-slate-400 font-medium">
                          <span className="flex items-center gap-1"><Calendar size={11} /> {booking.startDate}</span>
                          <span>→</span>
                          <span>{booking.endDate}</span>
                        </div>
                      </div>
                    </div>
                    <div className="flex items-center gap-4 sm:flex-col sm:items-end">
                      <p className="font-black text-slate-900">Rp {booking.totalPrice.toLocaleString('id-ID')}</p>
                      <span className={cn("flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border", cfg.color)}>
                        <StatusIcon size={11} /> {cfg.label}
                      </span>
                    </div>
                  </motion.div>
                );
              })}
            </div>
          )}
        </div>
      </div>
    </>
  );
}
