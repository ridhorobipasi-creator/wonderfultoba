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
          <h2 className="text-4xl font-bold text-slate-900 tracking-tight mb-2">Manajemen Reservasi</h2>
          <p className="text-slate-500 font-medium">Pantau dan kelola semua pesanan paket wisata dan rental mobil pelanggan.</p>
        </div>
        <div className="flex items-center space-x-3">
          <div className="flex items-center bg-white p-1 rounded-2xl border border-slate-100 shadow-sm">
            <button 
              onClick={() => setFilterStatus('all')}
              className={cn(
                "px-4 py-2 rounded-xl text-xs font-bold transition-all",
                filterStatus === 'all' ? "bg-obaja-blue text-white shadow-lg shadow-blue-100" : "text-slate-400 hover:text-slate-600"
              )}
            >
              Semua
            </button>
            <button 
              onClick={() => setFilterStatus('pending')}
              className={cn(
                "px-4 py-2 rounded-xl text-xs font-bold transition-all",
                filterStatus === 'pending' ? "bg-amber-500 text-white shadow-lg shadow-amber-100" : "text-slate-400 hover:text-slate-600"
              )}
            >
              Pending
            </button>
            <button 
              onClick={() => setFilterStatus('confirmed')}
              className={cn(
                "px-4 py-2 rounded-xl text-xs font-bold transition-all",
                filterStatus === 'confirmed' ? "bg-emerald-500 text-white shadow-lg shadow-emerald-100" : "text-slate-400 hover:text-slate-600"
              )}
            >
              Sukses
            </button>
          </div>
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
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all"
          />
        </div>
        <div className="flex items-center space-x-3 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center space-x-2 px-6 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-100 transition-all border border-slate-100">
            <Filter size={18} />
            <span>Filter Lanjutan</span>
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
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Pelanggan</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Layanan</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Jadwal</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Total Bayar</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Status</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={6} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-12 h-12 border-4 border-blue-100 border-t-obaja-blue rounded-full animate-spin mb-4"></div>
                      <p className="text-slate-400 font-medium">Memuat data reservasi...</p>
                    </div>
                  </td>
                </tr>
              ) : filteredBookings.length > 0 ? filteredBookings.map((booking, index) => (
                <motion.tr 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.05 }}
                  key={booking.id} 
                  className="hover:bg-slate-50/50 transition-colors group"
                >
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-4">
                      <div className="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 group-hover:bg-obaja-blue group-hover:text-white transition-all duration-300">
                        <User size={18} />
                      </div>
                      <div>
                        <p className="font-bold text-slate-900 group-hover:text-obaja-blue transition-colors">{booking.customerDetails?.name || '-'}</p>
                        <p className="text-xs text-slate-400 font-medium">{booking.customerDetails?.email || '-'}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex flex-col gap-1.5">
                      <span className={cn(
                        "w-fit px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border",
                        booking.type === 'package' ? 'bg-blue-50 text-obaja-blue border-blue-100' : 'bg-orange-50 text-obaja-orange border-orange-100'
                      )}>
                        {booking.type === 'package' ? 'Paket Wisata' : 'Rental Mobil'}
                      </span>
                      <p className="text-sm font-black text-slate-700 truncate max-w-[150px]">{booking.itemName}</p>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex flex-col">
                      <div className="flex items-center space-x-1.5 text-slate-600 text-sm font-bold">
                        <Calendar size={14} className="text-obaja-blue" />
                        <span>{booking.startDate}</span>
                      </div>
                      <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 ml-5">s/d {booking.endDate}</p>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-1.5">
                      <CreditCard size={14} className="text-slate-400" />
                      <p className="font-bold text-slate-900">Rp {booking.totalPrice.toLocaleString('id-ID')}</p>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-2">
                      {booking.status === 'confirmed' && <CheckCircle size={16} className="text-emerald-500" />}
                      {booking.status === 'pending' && <Clock size={16} className="text-amber-500" />}
                      {booking.status === 'cancelled' && <XCircle size={16} className="text-rose-500" />}
                      <span className={cn(
                        "text-xs font-bold capitalize",
                        booking.status === 'confirmed' ? 'text-emerald-600' : 
                        booking.status === 'pending' ? 'text-amber-600' : 'text-rose-600'
                      )}>
                        {booking.status === 'confirmed' ? 'Sukses' : booking.status === 'pending' ? 'Menunggu' : 'Dibatalkan'}
                      </span>
                    </div>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      {booking.status === 'pending' && (
                        <>
                          <button 
                            onClick={() => handleStatusUpdate(booking.id, 'confirmed')}
                            className="p-2.5 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all border border-transparent hover:border-emerald-100"
                            title="Konfirmasi Pesanan"
                          >
                            <CheckCircle size={18} />
                          </button>
                          <button 
                            onClick={() => handleStatusUpdate(booking.id, 'cancelled')}
                            className="p-2.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100"
                            title="Batalkan Pesanan"
                          >
                            <XCircle size={18} />
                          </button>
                        </>
                      )}
                      <button
                        onClick={() => setSelectedBooking(booking)}
                        className="p-2.5 text-slate-400 hover:text-obaja-blue hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100"
                      >
                        <Eye size={18} />
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

