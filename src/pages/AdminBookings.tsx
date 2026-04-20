"use client";

import { useState, useEffect, useCallback } from 'react';
import api from '../lib/api';
import { Booking } from '../types';
import { Search, Filter, CheckCircle, XCircle, Clock, Eye, Calendar, User, CreditCard, MoreHorizontal, X } from 'lucide-react';
import { motion } from 'framer-motion';
import { cn } from '../utils/cn';
import { toast } from 'sonner';

export default function AdminBookings({ category }: { category?: 'tour' | 'outbound' }) {
  interface ApiBooking {
    id: number;
    type: 'package' | 'car';
    itemId: number;
    itemName?: string;
    itemImage?: string;
    start_date: string;
    end_date: string;
    total_price: number;
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
  }

  const [bookings, setBookings] = useState<Booking[]>([]);
  const [loading, setLoading] = useState(true);
  const [filterStatus, setFilterStatus] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null);

  const fetchData = useCallback(async () => {
    setLoading(true);
    try {
      const res = await api.get<ApiBooking[]>('/bookings', { params: { category } });
      // Map API response (snake_case) to Frontend model (camelCase + nested details)
      const mappedData: Booking[] = res.data.map((b: ApiBooking) => ({
        ...b,
        startDate: b.start_date,
        endDate: b.end_date,
        totalPrice: Number(b.total_price),
        customerName: b.customer_name,
        customerEmail: b.customer_email,
        customerPhone: b.customer_phone,
        createdAt: new Date().toISOString(),
        customerDetails: {
          name: b.customer_name,
          email: b.customer_email,
          phone: b.customer_phone
        }
      }));
      setBookings(mappedData);
    } catch (error) {
      console.error('Error fetching bookings:', error);
      toast.error('Gagal memuat reservasi');
    } finally {
      setLoading(false);
    }
  }, [category]);

  useEffect(() => {
    let mounted = true;

    const initialLoad = async () => {
      if (!mounted) return;
      await fetchData();
    };

    initialLoad();
    const intervalId = window.setInterval(fetchData, 10000);

    return () => {
      mounted = false;
      window.clearInterval(intervalId);
    };
  }, [fetchData]);

  const handleStatusUpdate = async (id: string | number, status: string) => {
    try {
      await api.put(`/bookings/${id}`, { status });
      toast.success('Status reservasi diperbarui');
      fetchData();
    } catch (error) {
      console.error('Error updating status:', error);
      toast.error('Gagal memperbarui status');
    }
  };

  const filteredBookings = bookings
    .filter(b => filterStatus === 'all' || b.status === filterStatus)
    .filter(b => {
      const name = b.customerDetails?.name || '';
      return name.toLowerCase().includes(searchQuery.toLowerCase());
    });

  return (
    <div className="space-y-10 pb-12">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight">Daftar Reservasi</h1>
          <p className="text-slate-400 font-medium mt-1">Pantau dan kelola reservasi paket wisata & armada Anda.</p>
        </div>
        <div className="flex gap-2 bg-white p-1 rounded-2xl border border-slate-100 shadow-sm">
          <button 
            onClick={() => setFilterStatus('all')}
            className={cn(
              "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all",
              filterStatus === 'all' ? "bg-toba-green text-white shadow-lg shadow-emerald-100" : "text-slate-400 hover:text-slate-700"
            )}
          >
            Semua
          </button>
          <button 
            onClick={() => setFilterStatus('pending')}
            className={cn(
              "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all",
              filterStatus === 'pending' ? "bg-amber-500 text-white shadow-lg shadow-amber-100" : "text-slate-400 hover:text-slate-700"
            )}
          >
            Pending
          </button>
          <button 
            onClick={() => setFilterStatus('confirmed')}
            className={cn(
              "px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all",
              filterStatus === 'confirmed' ? "bg-emerald-500 text-white shadow-lg shadow-emerald-100" : "text-slate-400 hover:text-slate-700"
            )}
          >
            Sukses
          </button>
        </div>
      </div>

      {/* Filters & Search */}
      <div className="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div className="relative w-full md:w-96">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input
            type="text"
            placeholder="Cari nama pelanggan..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 placeholder:font-medium transition-all"
          />
        </div>
        <div className="flex items-center space-x-3 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center space-x-2 px-6 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-100 transition-all border border-slate-100 uppercase tracking-widest">
            <Filter size={18} />
            <span className="text-[10px] font-black">Filter</span>
          </button>
          <button className="p-3 bg-slate-50 text-slate-400 hover:text-slate-600 rounded-xl border border-slate-100 transition-all">
            <MoreHorizontal size={20} />
          </button>
        </div>
      </div>
      
      {/* Table Container */}
      <div className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/50">
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Customer</th>
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Paket/Layanan</th>
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Total Tagihan</th>
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={5} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-12 h-12 border-4 border-emerald-100 border-t-toba-green rounded-full animate-spin mb-4"></div>
                      <p className="text-slate-400 font-medium font-black uppercase text-[10px] tracking-widest">Memuat data reservasi...</p>
                    </div>
                  </td>
                </tr>
              ) : filteredBookings.length > 0 ? filteredBookings.map((booking, index) => (
                <motion.tr 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.05 }}
                  key={booking.id} 
                  className="hover:bg-slate-50/30 transition-colors group"
                >
                  <td className="px-8 py-6">
                    <p className="font-bold text-slate-900 leading-none mb-1">{booking.customerDetails?.name || '-'}</p>
                    <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">{booking.customerDetails?.phone || '-'}</p>
                  </td>
                  <td className="px-8 py-6">
                    <p className="font-bold text-slate-700 text-sm">{booking.itemName}</p>
                    <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{booking.startDate} - {booking.endDate}</p>
                  </td>
                  <td className="px-8 py-6">
                    <p className="font-black text-slate-900 italic uppercase">Rp {booking.totalPrice.toLocaleString('id-ID')}</p>
                  </td>
                  <td className="px-8 py-6">
                    <span className={cn(
                      "px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg border",
                      booking.status === 'confirmed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                      booking.status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : 
                      'bg-rose-50 text-rose-600 border-rose-100'
                    )}>
                      {booking.status === 'confirmed' ? 'Confirmed' : booking.status === 'pending' ? 'Pending' : 'Cancelled'}
                    </span>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end gap-2">
                      <button
                        onClick={() => setSelectedBooking(booking)}
                        className="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-toba-green transition-all"
                      >
                        Kelola
                      </button>
                    </div>
                  </td>
                </motion.tr>
              )) : (
                <tr>
                  <td colSpan={6} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-200">
                        <Calendar size={32} />
                      </div>
                      <p className="text-slate-400 font-medium">Tidak ada reservasi yang ditemukan.</p>
                    </div>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Detail Modal */}
      {selectedBooking && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
          <motion.div
            initial={{ opacity: 0, scale: 0.9, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            className="bg-white rounded-[3rem] w-full max-w-lg overflow-hidden shadow-2xl"
          >
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
              <h3 className="text-2xl font-bold text-slate-900">Detail Reservasi</h3>
              <button onClick={() => setSelectedBooking(null)} className="p-2 hover:bg-white rounded-xl text-slate-400 hover:text-slate-900 transition-all">
                <X size={22} />
              </button>
            </div>
            <div className="p-8 space-y-5">
              <div className="grid grid-cols-2 gap-4">
                <div className="bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                  <p className="font-bold text-slate-900">{selectedBooking.customerDetails?.name || '-'}</p>
                </div>
                <div className="bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Telepon</p>
                  <p className="font-bold text-slate-900">{selectedBooking.customerDetails?.phone || '-'}</p>
                </div>
                <div className="bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</p>
                  <p className="font-bold text-slate-900 text-sm">{selectedBooking.customerDetails?.email || '-'}</p>
                </div>
                <div className="bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tipe Layanan</p>
                  <p className="font-bold text-slate-900 capitalize">{selectedBooking.type}</p>
                </div>
                <div className="col-span-2 bg-slate-50 rounded-2xl p-4 flex items-center gap-4">
                  {selectedBooking.itemImage && (
                    <img src={selectedBooking.itemImage} className="w-16 h-12 object-cover rounded-xl" alt="" />
                  )}
                  <div>
                    <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Item Dipesan</p>
                    <p className="font-black text-obaja-blue">{selectedBooking.itemName}</p>
                  </div>
                </div>
                <div className="bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Mulai</p>
                  <p className="font-bold text-slate-900">{selectedBooking.startDate}</p>
                </div>
                <div className="bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Selesai</p>
                  <p className="font-bold text-slate-900">{selectedBooking.endDate}</p>
                </div>
              </div>
              <div className="bg-obaja-blue/5 rounded-2xl p-5 flex justify-between items-center">
                <p className="font-bold text-slate-600">Total Pembayaran</p>
                <p className="text-2xl font-black text-obaja-blue">Rp {selectedBooking.totalPrice.toLocaleString('id-ID')}</p>
              </div>
            </div>
          </motion.div>
        </div>
      )}
    </div>
  );
}

