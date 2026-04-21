"use client";

import { useState } from 'react';
import { motion } from 'framer-motion';
import { X, User, Phone, Mail, Calendar, Save, MessageCircle, Users, FileText } from 'lucide-react';
import api from '../lib/api';
import { useStore } from '../store/useStore';
import { toast } from 'sonner';
import LoadingButton from './admin/LoadingButton';
import FormField from './admin/FormField';
import { showErrorToast } from '@/lib/errorHandler';
import { useForm } from 'react-hook-form';

interface BookingModalProps {
  type: 'package' | 'car';
  itemId: string;
  itemName: string;
  pricePerUnit: number;
  onClose: () => void;
}

interface BookingFormValues {
  name: string;
  email: string;       // opsional
  phone: string;       // wajib (WA)
  persons: number;     // jumlah orang
  startDate: string;
  endDate: string;
  notes: string;
}

export default function BookingModal({ type, itemId, itemName, pricePerUnit, onClose }: BookingModalProps) {
  const { user } = useStore();
  const [loading, setLoading] = useState(false);

  const { register, handleSubmit, watch, formState: { errors } } = useForm<BookingFormValues>({
    mode: 'onChange',
    defaultValues: {
      name: user?.name || '',
      email: user?.email || '',
      phone: '',
      persons: 1,
      startDate: '',
      endDate: '',
      notes: '',
    }
  });

  const startDate = watch('startDate');
  const endDate = watch('endDate');
  const persons = watch('persons') || 1;

  const days = startDate && endDate
    ? Math.max(1, Math.ceil((new Date(endDate).getTime() - new Date(startDate).getTime()) / (1000 * 60 * 60 * 24)))
    : 1;

  const totalPrice = type === 'package'
    ? pricePerUnit * Math.max(1, persons)
    : pricePerUnit * days;

  const onSubmit = async (data: BookingFormValues) => {
    setLoading(true);
    try {
      await api.post('/bookings/guest', {
        type,
        item_id: Number(itemId),
        start_date: data.startDate,
        end_date: data.endDate || data.startDate,
        total_price: totalPrice,
        customer_name: data.name,
        customer_email: data.email || '',
        customer_phone: data.phone,
        notes: data.notes,
        custom_fields: {
          persons: data.persons,
          email: data.email,
        },
      });

      toast.success('Pemesanan berhasil! Tim kami akan segera menghubungi Anda via WhatsApp.');

      // Auto-open WhatsApp
      const message = [
        `*PEMESANAN BARU - WONDERFUL TOBA* 🌊`,
        ``,
        `*Layanan:* ${type === 'package' ? 'Paket Wisata' : 'Rental Mobil'}`,
        `*Paket:* ${itemName}`,
        ``,
        `*Nama:* ${data.name}`,
        `*No. WA:* ${data.phone}`,
        data.email ? `*Email:* ${data.email}` : null,
        `*Jumlah Orang:* ${data.persons} orang`,
        `*Tanggal:* ${data.startDate}${data.endDate && data.endDate !== data.startDate ? ` s/d ${data.endDate}` : ''}`,
        `*Total Estimasi:* Rp ${totalPrice.toLocaleString('id-ID')}`,
        data.notes ? `*Catatan:* ${data.notes}` : null,
        ``,
        `Mohon konfirmasi ketersediaan, terima kasih!`,
      ].filter(Boolean).join('\n');

      setTimeout(() => {
        window.open(`https://wa.me/6281323888207?text=${encodeURIComponent(message)}`, '_blank');
        onClose();
      }, 1200);

    } catch (err: unknown) {
      showErrorToast(err, toast);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
      <motion.div
        initial={{ opacity: 0, scale: 0.9, y: 20 }}
        animate={{ opacity: 1, scale: 1, y: 0 }}
        exit={{ opacity: 0, scale: 0.9, y: 20 }}
        className="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl max-h-[92vh] overflow-y-auto"
      >
        {/* Header */}
        <div className="p-7 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
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

        <form onSubmit={handleSubmit(onSubmit)} className="p-7 space-y-5">
          {/* Name */}
          <FormField label="Nama Lengkap" error={errors.name} icon={<User size={18} />} required>
            <input
              {...register('name', {
                required: 'Nama lengkap wajib diisi',
                minLength: { value: 3, message: 'Nama minimal 3 karakter' }
              })}
              className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${errors.name ? 'border-rose-500' : 'border-transparent focus:border-toba-green/30'} focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
              placeholder="Nama sesuai KTP"
            />
          </FormField>

          {/* Phone (WA) - WAJIB */}
          <FormField
            label="No. WhatsApp"
            error={errors.phone}
            icon={<Phone size={18} />}
            required
          >
            <input
              type="tel"
              {...register('phone', {
                required: 'Nomor WhatsApp wajib diisi'
              })}
              className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${errors.phone ? 'border-rose-500' : 'border-transparent focus:border-toba-green/30'} focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
              placeholder="08xxxxxxxxxx"
            />
          </FormField>

          {/* Email - OPSIONAL */}
          <FormField
            label="Email (opsional)"
            error={errors.email}
            icon={<Mail size={18} />}
          >
            <input
              {...register('email', {
                pattern: {
                  value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                  message: 'Format email tidak valid'
                }
              })}
              type="email"
              className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${errors.email ? 'border-rose-500' : 'border-transparent focus:border-toba-green/30'} focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
              placeholder="email@contoh.com (opsional)"
            />
          </FormField>

          {/* Jumlah Orang */}
          <FormField label="Jumlah Orang" error={errors.persons} icon={<Users size={18} />} required>
            <input
              {...register('persons', {
                required: 'Jumlah orang wajib diisi',
                min: { value: 1, message: 'Minimal 1 orang' },
                max: { value: 500, message: 'Maksimal 500 orang' },
                valueAsNumber: true,
              })}
              type="number"
              min={1}
              className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${errors.persons ? 'border-rose-500' : 'border-transparent focus:border-toba-green/30'} focus:bg-white rounded-2xl transition-all font-bold text-slate-900`}
              placeholder="1"
            />
          </FormField>

          {/* Dates */}
          <div className="grid grid-cols-2 gap-4">
            <FormField label="Tanggal Mulai" error={errors.startDate} icon={<Calendar size={18} />} required>
              <input
                {...register('startDate', { required: 'Tanggal mulai wajib diisi' })}
                type="date"
                min={new Date().toISOString().split('T')[0]}
                className={`w-full pl-11 pr-2 py-4 bg-slate-50 border-2 ${errors.startDate ? 'border-rose-500' : 'border-transparent focus:border-toba-green/30'} focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
              />
            </FormField>

            <FormField label="Tanggal Selesai" error={errors.endDate} icon={<Calendar size={18} />}>
              <input
                {...register('endDate', {
                  validate: (value) => {
                    if (value && startDate && value < startDate) return 'Harus setelah tanggal mulai';
                    return true;
                  }
                })}
                type="date"
                min={startDate || new Date().toISOString().split('T')[0]}
                className={`w-full pl-11 pr-2 py-4 bg-slate-50 border-2 ${errors.endDate ? 'border-rose-500' : 'border-transparent focus:border-toba-green/30'} focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
              />
            </FormField>
          </div>

          {/* Notes */}
          <FormField
            label="Catatan / Permintaan Khusus (opsional)"
            error={errors.notes}
            icon={<FileText size={18} />}
            maxLength={500}
            currentLength={watch('notes')?.length || 0}
          >
            <textarea
              {...register('notes', { maxLength: { value: 500, message: 'Maksimal 500 karakter' } })}
              rows={2}
              className="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-toba-green/30 focus:bg-white rounded-2xl transition-all font-medium text-slate-900 resize-none"
              placeholder="Alergi makanan, kebutuhan khusus, dll..."
            />
          </FormField>

          {/* Price Summary */}
          <div className="bg-toba-green/5 border border-toba-green/20 rounded-2xl p-4">
            <div className="flex justify-between items-center">
              <div>
                <p className="text-xs font-bold text-slate-500 mb-0.5">
                  {type === 'package'
                    ? `${persons} orang × Rp ${pricePerUnit.toLocaleString('id-ID')}`
                    : `${days} hari × Rp ${pricePerUnit.toLocaleString('id-ID')}`}
                </p>
                <p className="text-xl font-black text-toba-green">Rp {totalPrice.toLocaleString('id-ID')}</p>
              </div>
              <span className="text-xs font-bold text-toba-green bg-toba-green/10 px-3 py-1.5 rounded-full">Estimasi Total</span>
            </div>
          </div>

          {/* Actions */}
          <div className="flex gap-3 pt-1">
            <a
              href={`https://wa.me/6281323888207?text=${encodeURIComponent(`Halo, saya ingin tanya tentang *${itemName}*`)}`}
              target="_blank" rel="noopener noreferrer"
              className="flex-1 flex items-center justify-center gap-2 py-3.5 bg-emerald-500 text-white rounded-2xl font-bold hover:bg-emerald-600 transition-all text-sm"
            >
              <MessageCircle size={18} /> Tanya WA
            </a>
            <LoadingButton
              type="submit"
              loading={loading}
              loadingText="Memproses..."
              icon={<Save size={18} />}
              className="flex-1 bg-toba-green hover:bg-toba-green/90 text-white shadow-lg shadow-toba-green/20 text-sm"
            >
              Konfirmasi Pesan
            </LoadingButton>
          </div>

          <p className="text-center text-xs text-slate-400">
            Setelah konfirmasi, WhatsApp akan terbuka otomatis untuk koordinasi lebih lanjut
          </p>
        </form>
      </motion.div>
    </div>
  );
}
