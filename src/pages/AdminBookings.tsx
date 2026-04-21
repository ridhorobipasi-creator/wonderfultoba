"use client";

import { useState, useEffect, useCallback, useMemo, useRef } from 'react';
import api from '../lib/api';
import { Booking } from '../types';
import {
  Search, CheckCircle, XCircle, Clock, Eye, Calendar, User,
  Download, FileText, X, MapPin, Phone, Mail, MessageSquare,
  TrendingUp, AlertCircle, Package, CheckSquare, Users,
  ChevronDown, RefreshCw, Printer, Hash,
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import { generateInvoice } from '../lib/generateInvoice';
import { exportBookingsToExcel } from '../lib/exportUtils';

const STATUS_CONFIG = {
  pending:   { label: 'Menunggu', color: 'bg-amber-50 text-amber-700 border-amber-200',   dot: 'bg-amber-500',   icon: Clock },
  confirmed: { label: 'Dikonfirmasi', color: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-500', icon: CheckCircle },
  completed: { label: 'Selesai', color: 'bg-blue-50 text-blue-700 border-blue-200',       dot: 'bg-blue-500',    icon: CheckSquare },
  cancelled: { label: 'Dibatalkan', color: 'bg-rose-50 text-rose-700 border-rose-200',   dot: 'bg-rose-500',    icon: XCircle },
} as const;

function StatusBadge({ status }: { status: string }) {
  const cfg = STATUS_CONFIG[status as keyof typeof STATUS_CONFIG] || STATUS_CONFIG.pending;
  const Icon = cfg.icon;
  return (
    <span className={cn('inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border', cfg.color)}>
      <Icon size={10} />
      {cfg.label}
    </span>
  );
}

function StatCard({ label, value, sub, color, icon: Icon }: { label: string; value: string | number; sub?: string; color: string; icon: any }) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      className="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5"
    >
      <div className={cn('w-14 h-14 rounded-2xl flex items-center justify-center shrink-0', color)}>
        <Icon size={24} />
      </div>
      <div>
        <p className="text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">{label}</p>
        <p className="text-3xl font-black text-slate-900 leading-none">{value}</p>
        {sub && <p className="text-[10px] font-medium text-slate-400 mt-1">{sub}</p>}
      </div>
    </motion.div>
  );
}

function formatDate(dateStr?: string) {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

function formatCurrency(n: number) {
  return `Rp ${n.toLocaleString('id-ID')}`;
}

export default function AdminBookings({ category }: { category?: 'tour' | 'outbound' }) {
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [openDropdown, setOpenDropdown] = useState<string | number | null>(null);
  const dropdownRef = useRef<HTMLDivElement>(null);
  const [filterStatus, setFilterStatus] = useState('all');
  const [filterType, setFilterType] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedBooking, setSelectedBooking] = useState<any | null>(null);
  const [updatingStatus, setUpdatingStatus] = useState(false);
  const [sortBy, setSortBy] = useState<'newest' | 'oldest' | 'price_high' | 'price_low'>('newest');

  const fetchData = useCallback(async (silent = false) => {
    if (!silent) setLoading(true);
    else setRefreshing(true);
    try {
      const res = await api.get('/bookings', { params: { category } });
      const mapped = res.data.map((b: any) => ({
        ...b,
        startDate: b.startDate,
        endDate: b.endDate,
        totalPrice: Number(b.totalPrice),
        createdAt: b.createdAt || new Date().toISOString(),
        persons: b.metadata?.persons,
        customerDetails: {
          name: b.customerName,
          email: b.customerEmail || b.metadata?.email || '',
          phone: b.customerPhone,
        },
      }));
      setBookings(mapped);
    } catch {
      toast.error('Gagal memuat data reservasi');
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, [category]);

  useEffect(() => {
    fetchData();
    const id = window.setInterval(() => fetchData(true), 10000);
    return () => window.clearInterval(id);
  }, [fetchData]);

  // Close dropdown on outside click
  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(e.target as Node)) {
        setOpenDropdown(null);
      }
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, []);

  const handleStatusUpdate = async (id: string | number, status: string) => {
    setUpdatingStatus(true);
    try {
      await api.put(`/bookings/${id}`, { status });
      toast.success(`Status berhasil diubah ke "${STATUS_CONFIG[status as keyof typeof STATUS_CONFIG]?.label || status}"`);
      fetchData(true);
      setSelectedBooking((prev: any) => prev ? { ...prev, status } : null);
    } catch {
      toast.error('Gagal memperbarui status');
    } finally {
      setUpdatingStatus(false);
    }
  };

  // Stats
  const stats = useMemo(() => ({
    total: bookings.length,
    pending: bookings.filter(b => b.status === 'pending').length,
    confirmed: bookings.filter(b => b.status === 'confirmed').length,
    revenue: bookings.filter(b => b.status !== 'cancelled').reduce((s, b) => s + b.totalPrice, 0),
  }), [bookings]);

  // Filtered + sorted
  const filtered = useMemo(() => {
    let list = [...bookings];
    if (filterStatus !== 'all') list = list.filter(b => b.status === filterStatus);
    if (filterType !== 'all') list = list.filter(b => b.type === filterType);
    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      list = list.filter(b =>
        (b.customerDetails?.name || '').toLowerCase().includes(q) ||
        (b.customerDetails?.phone || '').includes(q) ||
        (b.customerDetails?.email || '').toLowerCase().includes(q) ||
        (b.itemName || '').toLowerCase().includes(q)
      );
    }
    switch (sortBy) {
      case 'oldest': list.sort((a, b) => new Date(a.createdAt).getTime() - new Date(b.createdAt).getTime()); break;
      case 'price_high': list.sort((a, b) => b.totalPrice - a.totalPrice); break;
      case 'price_low': list.sort((a, b) => a.totalPrice - b.totalPrice); break;
      default: list.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());
    }
    return list;
  }, [bookings, filterStatus, filterType, searchQuery, sortBy]);

  const STATUS_FLOW = ['pending', 'confirmed', 'completed'];

  return (
    <div className="space-y-8 pb-16">

      {/* ─── Header ─── */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight">Daftar Reservasi</h1>
          <p className="text-slate-400 font-medium mt-1 text-sm">
            Pantau &amp; kelola semua pemesanan paket wisata secara real-time.
          </p>
        </div>
        <div className="flex items-center gap-3 flex-wrap">
          <button
            onClick={() => fetchData(true)}
            disabled={refreshing}
            className="p-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl transition-all"
            title="Refresh"
          >
            <RefreshCw size={18} className={refreshing ? 'animate-spin' : ''} />
          </button>
          <button
            onClick={() => exportBookingsToExcel(filtered)}
            className="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-2xl font-bold text-xs shadow-lg shadow-emerald-500/20 transition-all uppercase tracking-widest"
          >
            <Download size={16} /> Export Excel
          </button>
        </div>
      </div>

      {/* ─── Stat Cards ─── */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard label="Total Reservasi" value={stats.total} sub="semua waktu" color="bg-slate-100 text-slate-600" icon={Package} />
        <StatCard label="Menunggu" value={stats.pending} sub="butuh tindakan" color="bg-amber-50 text-amber-600" icon={AlertCircle} />
        <StatCard label="Dikonfirmasi" value={stats.confirmed} sub="trip berjalan" color="bg-emerald-50 text-emerald-600" icon={CheckCircle} />
        <StatCard label="Total Pendapatan" value={formatCurrency(stats.revenue)} sub="diluar yang dibatalkan" color="bg-blue-50 text-blue-600" icon={TrendingUp} />
      </div>

      {/* ─── Filter Bar ─── */}
      <div className="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-stretch md:items-center">
        {/* Search */}
        <div className="relative flex-1 min-w-0">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input
            type="text"
            placeholder="Cari nama, nomor HP, email, atau paket..."
            value={searchQuery}
            onChange={e => setSearchQuery(e.target.value)}
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green font-medium text-slate-900 placeholder:text-slate-400 transition-all"
          />
        </div>

        <div className="flex items-center gap-3 flex-wrap">
          {/* Status filter */}
          <div className="flex gap-1.5 bg-slate-50 p-1 rounded-2xl border border-slate-100">
            {['all', 'pending', 'confirmed', 'completed', 'cancelled'].map(s => (
              <button key={s}
                onClick={() => setFilterStatus(s)}
                className={cn(
                  'px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all',
                  filterStatus === s
                    ? s === 'all' ? 'bg-slate-900 text-white'
                      : s === 'pending' ? 'bg-amber-500 text-white'
                      : s === 'confirmed' ? 'bg-emerald-500 text-white'
                      : s === 'completed' ? 'bg-blue-500 text-white'
                      : 'bg-rose-500 text-white'
                    : 'text-slate-500 hover:text-slate-900'
                )}
              >
                {s === 'all' ? 'Semua' : STATUS_CONFIG[s as keyof typeof STATUS_CONFIG]?.label || s}
              </button>
            ))}
          </div>

          {/* Type filter */}
          <select
            value={filterType}
            onChange={e => setFilterType(e.target.value)}
            className="pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-black text-slate-600 uppercase tracking-widest focus:ring-2 focus:ring-toba-green transition-all cursor-pointer"
          >
            <option value="all">Semua Tipe</option>
            <option value="package">Paket Wisata</option>
            <option value="car">Armada</option>
          </select>

          {/* Sort */}
          <select
            value={sortBy}
            onChange={e => setSortBy(e.target.value as any)}
            className="pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-black text-slate-600 uppercase tracking-widest focus:ring-2 focus:ring-toba-green transition-all cursor-pointer"
          >
            <option value="newest">Terbaru</option>
            <option value="oldest">Terlama</option>
            <option value="price_high">Harga ↓</option>
            <option value="price_low">Harga ↑</option>
          </select>
        </div>
      </div>

      {/* ─── Result count ─── */}
      <div className="flex items-center justify-between px-1">
        <p className="text-sm font-bold text-slate-400">
          Menampilkan <span className="text-slate-900">{filtered.length}</span> dari <span className="text-slate-900">{bookings.length}</span> reservasi
        </p>
        {searchQuery && (
          <button onClick={() => setSearchQuery('')} className="text-xs text-rose-500 font-bold flex items-center gap-1 hover:underline">
            <X size={12} /> Hapus filter
          </button>
        )}
      </div>

      {/* ─── Table ─── */}
      <div className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/70 border-b border-slate-100">
                <th className="px-7 py-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 w-8"><Hash size={12} /></th>
                <th className="px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Pelanggan</th>
                <th className="px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Paket / Layanan</th>
                <th className="px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Periode</th>
                <th className="px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Tagihan</th>
                <th className="px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                <th className="px-5 py-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Aksi Cepat</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={7} className="py-24 text-center">
                    <div className="flex flex-col items-center gap-4">
                      <div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin" />
                      <p className="text-slate-400 font-medium text-sm">Memuat data reservasi...</p>
                    </div>
                  </td>
                </tr>
              ) : filtered.length === 0 ? (
                <tr>
                  <td colSpan={7} className="py-24 text-center">
                    <div className="flex flex-col items-center gap-4">
                      <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                        <Calendar size={36} />
                      </div>
                      <p className="text-slate-400 font-medium">Tidak ada reservasi ditemukan.</p>
                    </div>
                  </td>
                </tr>
              ) : filtered.map((booking, idx) => (
                <motion.tr
                  key={booking.id}
                  initial={{ opacity: 0, y: 8 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: idx * 0.03 }}
                  className="hover:bg-slate-50/30 transition-colors group"
                >
                  {/* # */}
                  <td className="px-7 py-5">
                    <span className="text-[10px] font-black text-slate-300">#{idx + 1}</span>
                  </td>
                  {/* Customer */}
                  <td className="px-4 py-5">
                    <div className="flex items-center gap-3">
                      <div className="w-9 h-9 rounded-full bg-gradient-to-br from-toba-green to-emerald-400 flex items-center justify-center text-white font-black text-sm shrink-0">
                        {(booking.customerDetails?.name?.[0] || '?').toUpperCase()}
                      </div>
                      <div>
                        <p className="font-bold text-slate-900 text-sm leading-none mb-1">
                          {booking.customerDetails?.name || '-'}
                        </p>
                        <p className="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                          <Phone size={9} />{booking.customerDetails?.phone || '-'}
                        </p>
                        {booking.customerDetails?.email && (
                          <p className="text-[10px] text-slate-400 font-medium flex items-center gap-1 mt-0.5">
                            <Mail size={9} />{booking.customerDetails.email}
                          </p>
                        )}
                      </div>
                    </div>
                  </td>
                  {/* Item */}
                  <td className="px-4 py-5">
                    <div className="flex items-center gap-3">
                      {(booking as any).itemImage ? (
                        <img src={(booking as any).itemImage} alt="" className="w-11 h-11 rounded-xl object-cover shrink-0" />
                      ) : (
                        <div className="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center text-slate-300 shrink-0">
                          <MapPin size={16} />
                        </div>
                      )}
                      <div>
                        <p className="font-bold text-slate-800 text-sm leading-snug max-w-[180px] line-clamp-1">
                          {booking.itemName || '-'}
                        </p>
                        <span className="text-[9px] font-black uppercase tracking-widest text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
                          {booking.type === 'package' ? 'Paket Wisata' : 'Armada'}
                        </span>
                      </div>
                    </div>
                  </td>
                  {/* Period */}
                  <td className="px-4 py-5">
                    <div className="text-sm font-bold text-slate-700">
                      {formatDate(booking.startDate)}
                    </div>
                    <div className="text-[10px] text-slate-400 font-medium flex items-center gap-1 mt-0.5">
                      <ChevronDown size={10} className="-rotate-90" />
                      {formatDate(booking.endDate)}
                    </div>
                    {(booking as any).persons && (
                      <div className="text-[9px] text-emerald-600 font-black flex items-center gap-1 mt-1 bg-emerald-50 px-2 py-0.5 rounded-md w-fit">
                        <Users size={9} /> {(booking as any).persons} orang
                      </div>
                    )}
                  </td>
                  {/* Price */}
                  <td className="px-4 py-5">
                    <p className="font-black text-slate-900 text-sm">{formatCurrency(booking.totalPrice)}</p>
                    <p className="text-[9px] text-slate-400 font-medium mt-0.5">
                      {formatDate(booking.createdAt)}
                    </p>
                  </td>
                  {/* Status – inline dropdown */}
                  <td className="px-4 py-5">
                    <div className="relative" ref={openDropdown === booking.id ? dropdownRef : undefined}>
                      <button
                        onClick={e => { e.stopPropagation(); setOpenDropdown(openDropdown === booking.id ? null : booking.id); }}
                        className={cn(
                          'flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border transition-all hover:opacity-80',
                          STATUS_CONFIG[booking.status as keyof typeof STATUS_CONFIG]?.color || STATUS_CONFIG.pending.color
                        )}
                      >
                        {(() => { const cfg = STATUS_CONFIG[booking.status as keyof typeof STATUS_CONFIG] || STATUS_CONFIG.pending; const Icon = cfg.icon; return <Icon size={10} />; })()}
                        {STATUS_CONFIG[booking.status as keyof typeof STATUS_CONFIG]?.label || booking.status}
                        <ChevronDown size={9} className={cn('transition-transform', openDropdown === booking.id ? 'rotate-180' : '')} />
                      </button>

                      <AnimatePresence>
                        {openDropdown === booking.id && (
                          <motion.div
                            initial={{ opacity: 0, y: -6, scale: 0.97 }}
                            animate={{ opacity: 1, y: 0, scale: 1 }}
                            exit={{ opacity: 0, y: -4, scale: 0.97 }}
                            className="absolute left-0 top-full mt-1.5 z-40 bg-white rounded-2xl shadow-2xl border border-slate-100 p-1.5 min-w-[160px]"
                          >
                            {[
                              { s: 'pending',   label: 'Menunggu',    icon: Clock,        cls: 'text-amber-700 hover:bg-amber-50' },
                              { s: 'confirmed', label: 'Konfirmasi',   icon: CheckCircle,  cls: 'text-emerald-700 hover:bg-emerald-50' },
                              { s: 'completed', label: 'Selesai',      icon: CheckSquare,  cls: 'text-blue-700 hover:bg-blue-50' },
                              { s: 'cancelled', label: 'Batalkan',     icon: XCircle,      cls: 'text-rose-600 hover:bg-rose-50' },
                            ].map(({ s, label, icon: Icon, cls }) => (
                              <button
                                key={s}
                                disabled={booking.status === s || updatingStatus}
                                onClick={e => { e.stopPropagation(); handleStatusUpdate(booking.id, s); setOpenDropdown(null); }}
                                className={cn(
                                  'flex items-center gap-2 w-full px-3 py-2 rounded-xl text-[11px] font-bold transition-all disabled:opacity-30 disabled:cursor-not-allowed',
                                  cls
                                )}
                              >
                                <Icon size={13} /> {label}
                                {booking.status === s && <span className="ml-auto text-[9px] opacity-50">saat ini</span>}
                              </button>
                            ))}
                          </motion.div>
                        )}
                      </AnimatePresence>
                    </div>
                  </td>

                  {/* Quick Actions */}
                  <td className="px-5 py-5">
                    <div className="flex items-center gap-2 justify-end">
                      {/* WA */}
                      {booking.customerDetails?.phone && (
                        <a
                          href={`https://wa.me/${(booking.customerDetails.phone).replace(/\D/g, '')}?text=${encodeURIComponent(`Halo ${booking.customerDetails?.name || ''}, terkait pemesanan *${booking.itemName}* Anda di Wonderful Toba.`)}`}
                          target="_blank"
                          rel="noopener noreferrer"
                          onClick={e => e.stopPropagation()}
                          title="Chat WhatsApp"
                          className="w-8 h-8 rounded-xl bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white flex items-center justify-center transition-all"
                        >
                          <MessageSquare size={14} />
                        </a>
                      )}
                      {/* Invoice download */}
                      <button
                        onClick={e => { e.stopPropagation(); generateInvoice(booking, false); }}
                        title="Unduh Invoice"
                        className="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-800 hover:text-white flex items-center justify-center transition-all"
                      >
                        <Download size={14} />
                      </button>
                      {/* Detail */}
                      <button
                        onClick={e => { e.stopPropagation(); setSelectedBooking(booking); }}
                        title="Lihat Detail"
                        className="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 group-hover:bg-toba-green group-hover:text-white hover:bg-toba-green hover:text-white flex items-center justify-center transition-all"
                      >
                        <Eye size={14} />
                      </button>
                    </div>
                  </td>
                </motion.tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* ─── Detail Modal ─── */}
      <AnimatePresence>
        {selectedBooking && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md">
            <motion.div
              initial={{ opacity: 0, scale: 0.92, y: 24 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.95, y: 12 }}
              className="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl max-h-[90vh] flex flex-col"
            >
              {/* Modal Header */}
              <div className="p-7 border-b border-slate-100 flex justify-between items-start bg-slate-50/50 shrink-0">
                <div>
                  <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">
                    Reservasi #{selectedBooking.id}
                  </p>
                  <h3 className="text-2xl font-black text-slate-900">{selectedBooking.itemName || 'Detail Pemesanan'}</h3>
                  <div className="mt-2">
                    <StatusBadge status={selectedBooking.status} />
                  </div>
                </div>
                <div className="flex items-center gap-2 shrink-0">
                  <button
                    onClick={() => generateInvoice(selectedBooking, true)}
                    className="flex items-center gap-1.5 px-3 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold text-xs hover:bg-slate-200 transition-all"
                    title="Preview Invoice"
                  >
                    <Printer size={14} />
                  </button>
                  <button
                    onClick={() => generateInvoice(selectedBooking, false)}
                    className="flex items-center gap-1.5 px-3 py-2 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-slate-800 transition-all"
                  >
                    <Download size={14} /> Invoice
                  </button>
                  <button
                    onClick={() => setSelectedBooking(null)}
                    className="p-2 ml-1 hover:bg-slate-100 rounded-xl text-slate-400 hover:text-slate-900 transition-all"
                  >
                    <X size={20} />
                  </button>
                </div>
              </div>

              {/* Modal Body */}
              <div className="overflow-y-auto flex-1 p-7 space-y-6">

                {/* Item Preview */}
                <div className="flex items-center gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                  {(selectedBooking as any).itemImage ? (
                    <img src={(selectedBooking as any).itemImage} alt="" className="w-16 h-16 rounded-2xl object-cover shrink-0" />
                  ) : (
                    <div className="w-16 h-16 bg-slate-200 rounded-2xl flex items-center justify-center text-slate-400 shrink-0">
                      <Package size={24} />
                    </div>
                  )}
                  <div>
                    <p className="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Item Dipesan</p>
                    <p className="font-black text-slate-900 text-lg leading-tight">{selectedBooking.itemName}</p>
                    <p className="text-xs text-slate-400 font-medium mt-0.5 capitalize">
                      {selectedBooking.type === 'package' ? 'Paket Wisata' : 'Armada / Mobil'}
                    </p>
                  </div>
                </div>

                {/* Customer Info */}
                <div>
                  <p className="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-3">Informasi Pelanggan</p>
                  <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div className="bg-slate-50 rounded-2xl p-4 flex items-start gap-3">
                      <User size={16} className="text-slate-400 mt-0.5 shrink-0" />
                      <div>
                        <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama</p>
                        <p className="font-bold text-slate-900 text-sm mt-0.5">{selectedBooking.customerDetails?.name || '-'}</p>
                      </div>
                    </div>
                    <div className="bg-slate-50 rounded-2xl p-4 flex items-start gap-3">
                      <Phone size={16} className="text-slate-400 mt-0.5 shrink-0" />
                      <div>
                        <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest">WhatsApp</p>
                        <p className="font-bold text-slate-900 text-sm mt-0.5">{selectedBooking.customerDetails?.phone || '-'}</p>
                      </div>
                    </div>
                    <div className="bg-slate-50 rounded-2xl p-4 flex items-start gap-3">
                      <Mail size={16} className="text-slate-400 mt-0.5 shrink-0" />
                      <div>
                        <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email</p>
                        <p className="font-bold text-slate-900 text-sm mt-0.5 break-all">{selectedBooking.customerDetails?.email || '-'}</p>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Booking Details */}
                <div>
                  <p className="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-3">Detail Pemesanan</p>
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div className="bg-slate-50 rounded-2xl p-4">
                      <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Mulai</p>
                      <p className="font-bold text-slate-900 text-sm">{formatDate(selectedBooking.startDate)}</p>
                    </div>
                    <div className="bg-slate-50 rounded-2xl p-4">
                      <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Selesai</p>
                      <p className="font-bold text-slate-900 text-sm">{formatDate(selectedBooking.endDate)}</p>
                    </div>
                    {(selectedBooking as any).persons && (
                      <div className="bg-emerald-50 rounded-2xl p-4 border border-emerald-100">
                        <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Peserta</p>
                        <p className="font-black text-emerald-700 text-lg">{(selectedBooking as any).persons} <span className="text-xs font-bold">orang</span></p>
                      </div>
                    )}
                    <div className="bg-slate-50 rounded-2xl p-4">
                      <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tgl Pesan</p>
                      <p className="font-bold text-slate-900 text-sm">{formatDate(selectedBooking.createdAt)}</p>
                    </div>
                  </div>
                </div>

                {/* Total */}
                <div className="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-5 flex justify-between items-center">
                  <p className="text-slate-400 font-bold text-sm">Total Pembayaran</p>
                  <p className="text-2xl font-black text-white">{formatCurrency(selectedBooking.totalPrice)}</p>
                </div>

                {/* Notes */}
                {(selectedBooking as any).notes && (
                  <div className="bg-amber-50 rounded-2xl p-5 border border-amber-100">
                    <p className="text-[9px] font-black uppercase tracking-widest text-amber-600 mb-2 flex items-center gap-1.5">
                      <AlertCircle size={12} /> Catatan Pelanggan
                    </p>
                    <p className="text-sm font-medium text-slate-700 leading-relaxed">{(selectedBooking as any).notes}</p>
                  </div>
                )}

                {/* Status Flow */}
                <div>
                  <p className="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-3">Alur Status</p>
                  <div className="flex items-center gap-2 mb-5">
                    {STATUS_FLOW.map((s, i) => {
                      const isActive = selectedBooking.status === s;
                      const isDone = STATUS_FLOW.indexOf(selectedBooking.status) > i;
                      return (
                        <div key={s} className="flex items-center gap-2 flex-1">
                          <div className={cn(
                            'flex-1 flex flex-col items-center text-center p-3 rounded-2xl transition-all text-[10px] font-black uppercase tracking-wider border',
                            isActive ? 'bg-toba-green text-white border-toba-green shadow-lg shadow-emerald-100' :
                            isDone ? 'bg-emerald-50 text-emerald-600 border-emerald-100' :
                            'bg-slate-50 text-slate-400 border-slate-100'
                          )}>
                            {STATUS_CONFIG[s as keyof typeof STATUS_CONFIG]?.label || s}
                          </div>
                          {i < STATUS_FLOW.length - 1 && (
                            <div className={cn('w-4 h-0.5 shrink-0', isDone ? 'bg-emerald-400' : 'bg-slate-200')} />
                          )}
                        </div>
                      );
                    })}
                  </div>

                  {/* Action Buttons */}
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-2">
                    {[
                      { status: 'confirmed', label: 'Konfirmasi', icon: CheckCircle, cls: 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:bg-emerald-100' },
                      { status: 'completed', label: 'Selesai', icon: CheckSquare, cls: 'bg-blue-50 border-blue-100 text-blue-700 hover:bg-blue-100' },
                      { status: 'pending', label: 'Pending', icon: Clock, cls: 'bg-amber-50 border-amber-100 text-amber-700 hover:bg-amber-100' },
                      { status: 'cancelled', label: 'Batalkan', icon: XCircle, cls: 'bg-rose-50 border-rose-100 text-rose-600 hover:bg-rose-100' },
                    ].map(({ status, label, icon: Icon, cls }) => (
                      <button
                        key={status}
                        onClick={() => handleStatusUpdate(selectedBooking.id, status)}
                        disabled={selectedBooking.status === status || updatingStatus}
                        className={cn(
                          'flex flex-col items-center gap-1.5 py-3 px-2 rounded-2xl font-bold text-xs transition-all border disabled:opacity-40 disabled:cursor-not-allowed',
                          cls
                        )}
                      >
                        <Icon size={16} />
                        {label}
                      </button>
                    ))}
                  </div>
                </div>

                {/* WhatsApp Quick Reply */}
                {selectedBooking.customerDetails?.phone && (
                  <a
                    href={`https://wa.me/${(selectedBooking.customerDetails.phone).replace(/\D/g, '')}?text=${encodeURIComponent(`Halo ${selectedBooking.customerDetails?.name || ''}, terkait pemesanan *${selectedBooking.itemName}* Anda di Wonderful Toba.`)}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex items-center justify-center gap-2 w-full py-3.5 bg-[#25D366] hover:bg-[#20ba5a] text-white rounded-2xl font-black text-sm transition-all shadow-md shadow-green-200"
                  >
                    <MessageSquare size={18} /> Chat via WhatsApp
                  </a>
                )}
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}
