"use client";

import { useState } from 'react';
import { isAxiosError } from 'axios';
import { motion } from 'framer-motion';
import { X, User, Phone, Mail, Calendar, Save, MessageCircle } from 'lucide-react';
import api from '../lib/api';
import { useStore } from '../store/useStore';
import { toast } from 'sonner';

interface ApiErrorResponse {
  message?: string;
  errors?: Record<string, string[]>;
}

interface BookingModalProps {
  type: 'package' | 'car';
  itemId: string;
  itemName: string;
  pricePerUnit: number;
  onClose: () => void;
}

export default function BookingModal({ type, itemId, itemName, pricePerUnit, onClose }: BookingModalProps) {
  const { user } = useStore();
  const [loading, setLoading] = useState(false);
  const [form, setForm] = useState({
    name: user?.name || '',
    email: user?.email || '',
    phone: '',
    startDate: '',
    endDate: '',
    notes: '',
  });

  const days = form.startDate && form.endDate
    ? Math.max(1, Math.ceil((new Date(form.endDate).getTime() - new Date(form.startDate).getTime()) / (1000 * 60 * 60 * 24)))
    : 1;

  const totalPrice = pricePerUnit * days;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.startDate || !form.endDate) {
      toast.error('Tanggal mulai dan selesai wajib diisi');
      return;
    }
    try {
      // Use the newly created guest endpoint
      await api.post('/bookings/guest', {
        type,
        item_id: Number(itemId),
        start_date: form.startDate,
        end_date: form.endDate,
        total_price: totalPrice,
        customer_name: form.name,
        customer_email: form.email,
        customer_phone: form.phone,
        notes: form.notes,
      });
      
      toast.success('Pemesanan berhasil! Tim kami akan segera menghubungi Anda.');
      
      // WhatsApp Automation: Open WA with details after successful DB save
      const message = `*HALO WONDERFUL TOBA!* 🌊\n\nSaya ingin mengonfirmasi pesanan saya:\n\n*LAYANAN:* ${type === 'package' ? 'Paket Wisata' : 'Rental Mobil'}\n*ITEM:* ${itemName}\n*NAMA:* ${form.name}\n*TELEPON:* ${form.phone}\n*TANGGAL:* ${form.startDate} s/d ${form.endDate}\n*TOTAL:* Rp ${totalPrice.toLocaleString('id-ID')}\n${form.notes ? `\n*CATATAN:* ${form.notes}\n` : ''}\nMohon informasi selanjutnya, terima kasih!`;
      const waUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(message)}`;
      
      // Delay slightly so user can see the success toast
      setTimeout(() => {
        window.open(waUrl, '_blank');
        onClose();
      }, 1500);

    } catch (err: unknown) {
      if (isAxiosError<ApiErrorResponse>(err) && err.response?.data?.errors) {
        const errors = err.response.data.errors;
        const firstError = Object.values(errors)[0] as string[];
        toast.error(firstError[0] || 'Validasi gagal.');
      } else {
        const msg = isAxiosError<ApiErrorResponse>(err)
          ? err.response?.data?.message || 'Gagal membuat pemesanan. Coba lagi.'
          : 'Gagal membuat pemesanan. Coba lagi.';
        toast.error(msg);
      }
    } finally {
      setLoading(false);
    }
  };

  const waMessage = encodeURIComponent(
    `*HALO WONDERFUL TOBA!* 🌊\n\nSaya ingin memesan ${type === 'package' ? 'Paket Wisata' : 'Rental Mobil'}:\n\n*ITEM:* ${itemName}\n*NAMA:* ${form.name}\n*TELEPON:* ${form.phone}\n*TANGGAL:* ${form.startDate || '-'} s/d ${form.endDate || '-'}\n${form.notes ? `\n*CATATAN:* ${form.notes}\n` : ''}\nMohon bantuannya untuk ketersediaan jadwal, terima kasih!`
  );

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
      <motion.div
        initial={{ opacity: 0, scale: 0.9, y: 20 }}
        animate={{ opacity: 1, scale: 1, y: 0 }}
        exit={{ opacity: 0, scale: 0.9, y: 20 }}
        className="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl max-h-[90vh] overflow-y-auto"
      >
        {/* Header */}
        <div className="p-8 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
          <div>
            <h3 className="text-2xl font-black text-slate-900 mb-1">
              {type === 'package' ? 'Pesan Paket Wisata' : 'Sewa Kendaraan'}
            </h3>
            <p className="text-sm text-slate-500 font-medium line-clamp-1">{itemName}</p>
          </div>
          <button onClick={onClose} className="p-2 hover:bg-white rounded-xl text-slate-400 hover:text-slate-900 transition-all">
            <X size={22} />
          </button>
        </div>

        <form onSubmit={handleSubmit} className="p-8 space-y-5">
          {/* Name */}
          <div className="space-y-2">
            <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1">Nama Lengkap</label>
            <div className="relative group">
              <User className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition-colors" size={18} />
              <input
                required
                value={form.name}
                onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
                className="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-toba-green/20 focus:bg-white rounded-2xl transition-all font-medium text-slate-900"
                placeholder="Nama sesuai KTP"
              />
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
            {/* Email */}
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1">Email</label>
              <div className="relative group">
                <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition-colors" size={18} />
                <input
                  required type="email"
                  value={form.email}
                  onChange={e => setForm(f => ({ ...f, email: e.target.value }))}
                  className="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-toba-green/20 focus:bg-white rounded-2xl transition-all font-medium text-slate-900"
                  placeholder="email@contoh.com"
                />
              </div>
            </div>
            {/* Phone */}
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1">No. HP / WA</label>
              <div className="relative group">
                <Phone className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition-colors" size={18} />
                <input
                  required
                  value={form.phone}
                  onChange={e => setForm(f => ({ ...f, phone: e.target.value }))}
                  className="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-toba-green/20 focus:bg-white rounded-2xl transition-all font-medium text-slate-900"
                  placeholder="08xxxxxxxxxx"
                />
              </div>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
            {/* Start Date */}
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1">Tanggal Mulai</label>
              <div className="relative group">
                <Calendar className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition-colors" size={18} />
                <input
                  required type="date"
                  min={new Date().toISOString().split('T')[0]}
                  value={form.startDate}
                  onChange={e => setForm(f => ({ ...f, startDate: e.target.value }))}
                  className="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-toba-green/20 focus:bg-white rounded-2xl transition-all font-medium text-slate-900"
                />
              </div>
            </div>
            {/* End Date */}
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1">Tanggal Selesai</label>
              <div className="relative group">
                <Calendar className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-toba-green transition-colors" size={18} />
                <input
                  required type="date"
                  min={form.startDate || new Date().toISOString().split('T')[0]}
                  value={form.endDate}
                  onChange={e => setForm(f => ({ ...f, endDate: e.target.value }))}
                  className="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-toba-green/20 focus:bg-white rounded-2xl transition-all font-medium text-slate-900"
                />
              </div>
            </div>
          </div>

          {/* Notes */}
          <div className="space-y-2">
            <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1">Catatan (opsional)</label>
            <textarea
              rows={2}
              value={form.notes}
              onChange={e => setForm(f => ({ ...f, notes: e.target.value }))}
              className="w-full px-5 py-4 bg-slate-50 border-2 border-transparent focus:border-toba-green/20 focus:bg-white rounded-2xl transition-all font-medium text-slate-900 resize-none"
              placeholder="Permintaan khusus, jumlah orang, dll..."
            />
          </div>

          {/* Price Summary */}
          {form.startDate && form.endDate && (
            <div className="bg-toba-green/5 border border-toba-green/20 rounded-2xl p-4 flex justify-between items-center">
              <div>
                <p className="text-xs font-bold text-slate-500">{days} {type === 'car' ? 'hari' : 'paket'} × Rp {pricePerUnit.toLocaleString('id-ID')}</p>
                <p className="text-lg font-black text-toba-green">Rp {totalPrice.toLocaleString('id-ID')}</p>
              </div>
              <span className="text-xs font-bold text-toba-green bg-toba-green/10 px-3 py-1 rounded-full">Estimasi Total</span>
            </div>
          )}

          {/* Actions */}
          <div className="flex gap-3 pt-2">
            <a
              href={`https://wa.me/6281234567890?text=${waMessage}`}
              target="_blank" rel="noopener noreferrer"
              className="flex-1 flex items-center justify-center gap-2 py-3.5 bg-emerald-500 text-white rounded-2xl font-bold hover:bg-emerald-600 transition-all text-sm"
            >
              <MessageCircle size={18} /> WhatsApp
            </a>
            <button
              type="submit"
              disabled={loading}
              className="flex-1 flex items-center justify-center gap-2 py-3.5 bg-toba-green text-white rounded-2xl font-bold hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20 disabled:opacity-60 text-sm"
            >
              <Save size={18} />
              {loading ? 'Memproses...' : 'Konfirmasi Pesan'}
            </button>
          </div>
        </form>
      </motion.div>
    </div>
  );
}
