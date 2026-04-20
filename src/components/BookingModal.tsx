"use client";

import { useState } from 'react';
import { isAxiosError } from 'axios';
import { motion } from 'framer-motion';
import { X, User, Phone, Mail, Calendar, Save, MessageCircle } from 'lucide-react';
import api from '../lib/api';
import { useStore } from '../store/useStore';
import { toast } from 'sonner';
import LoadingButton from './admin/LoadingButton';
import FormField from './admin/FormField';
import { showErrorToast } from '@/lib/errorHandler';
import { useForm } from 'react-hook-form';

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

interface BookingFormValues {
  name: string;
  email: string;
  phone: string;
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
      startDate: '',
      endDate: '',
      notes: '',
    }
  });

  const startDate = watch('startDate');
  const endDate = watch('endDate');

  const days = startDate && endDate
    ? Math.max(1, Math.ceil((new Date(endDate).getTime() - new Date(startDate).getTime()) / (1000 * 60 * 60 * 24)))
    : 1;

  const totalPrice = pricePerUnit * days;

  const onSubmit = async (data: BookingFormValues) => {
    setLoading(true);
    try {
      await api.post('/bookings/guest', {
        type,
        item_id: Number(itemId),
        start_date: data.startDate,
        end_date: data.endDate,
        total_price: totalPrice,
        customer_name: data.name,
        customer_email: data.email,
        customer_phone: data.phone,
        notes: data.notes,
      });
      
      toast.success('Pemesanan berhasil! Tim kami akan segera menghubungi Anda.');
      
      // WhatsApp Automation
      const message = `*HALO WONDERFUL TOBA!* 🌊\n\nSaya ingin mengonfirmasi pesanan saya:\n\n*LAYANAN:* ${type === 'package' ? 'Paket Wisata' : 'Rental Mobil'}\n*ITEM:* ${itemName}\n*NAMA:* ${data.name}\n*TELEPON:* ${data.phone}\n*TANGGAL:* ${data.startDate} s/d ${data.endDate}\n*TOTAL:* Rp ${totalPrice.toLocaleString('id-ID')}\n${data.notes ? `\n*CATATAN:* ${data.notes}\n` : ''}\nMohon informasi selanjutnya, terima kasih!`;
      const waUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(message)}`;
      
      setTimeout(() => {
        window.open(waUrl, '_blank');
        onClose();
      }, 1500);

    } catch (err: unknown) {
      showErrorToast(err, toast);
    } finally {
      setLoading(false);
    }
  };

  const waMessage = encodeURIComponent(
    `*HALO WONDERFUL TOBA!* 🌊\n\nSaya ingin memesan ${type === 'package' ? 'Paket Wisata' : 'Rental Mobil'}:\n\n*ITEM:* ${itemName}\n*NAMA:* ${watch('name')}\n*TELEPON:* ${watch('phone')}\n*TANGGAL:* ${startDate || '-'} s/d ${endDate || '-'}\n${watch('notes') ? `\n*CATATAN:* ${watch('notes')}\n` : ''}\nMohon bantuannya untuk ketersediaan jadwal, terima kasih!`
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

        <form onSubmit={handleSubmit(onSubmit)} className="p-8 space-y-5">
          {/* Name */}
          <FormField
            label="Nama Lengkap"
            error={errors.name}
            icon={<User size={18} />}
            required
          >
            <input
              {...register('name', { 
                required: 'Nama lengkap wajib diisi',
                minLength: { value: 3, message: 'Nama minimal 3 karakter' }
              })}
              className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${
                errors.name ? 'border-rose-500 focus:border-rose-500' : 'border-transparent focus:border-toba-green/20'
              } focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
              placeholder="Nama sesuai KTP"
            />
          </FormField>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
            {/* Email */}
            <FormField
              label="Email"
              error={errors.email}
              icon={<Mail size={18} />}
              required
            >
              <input
                {...register('email', { 
                  required: 'Email wajib diisi',
                  pattern: {
                    value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                    message: 'Format email tidak valid'
                  }
                })}
                type="email"
                className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${
                  errors.email ? 'border-rose-500 focus:border-rose-500' : 'border-transparent focus:border-toba-green/20'
                } focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
                placeholder="email@contoh.com"
              />
            </FormField>

            {/* Phone */}
            <FormField
              label="No. HP / WA"
              error={errors.phone}
              icon={<Phone size={18} />}
              required
            >
              <input
                {...register('phone', { 
                  required: 'Nomor HP wajib diisi',
                  pattern: {
                    value: /^(\+62|62|0)[0-9]{9,12}$/,
                    message: 'Format nomor HP tidak valid'
                  }
                })}
                className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${
                  errors.phone ? 'border-rose-500 focus:border-rose-500' : 'border-transparent focus:border-toba-green/20'
                } focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
                placeholder="08xxxxxxxxxx"
              />
            </FormField>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
            {/* Start Date */}
            <FormField
              label="Tanggal Mulai"
              error={errors.startDate}
              icon={<Calendar size={18} />}
              required
            >
              <input
                {...register('startDate', { required: 'Tanggal mulai wajib diisi' })}
                type="date"
                min={new Date().toISOString().split('T')[0]}
                className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${
                  errors.startDate ? 'border-rose-500 focus:border-rose-500' : 'border-transparent focus:border-toba-green/20'
                } focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
              />
            </FormField>

            {/* End Date */}
            <FormField
              label="Tanggal Selesai"
              error={errors.endDate}
              icon={<Calendar size={18} />}
              required
            >
              <input
                {...register('endDate', { 
                  required: 'Tanggal selesai wajib diisi',
                  validate: (value) => {
                    if (startDate && value < startDate) {
                      return 'Tanggal selesai harus setelah tanggal mulai';
                    }
                    return true;
                  }
                })}
                type="date"
                min={startDate || new Date().toISOString().split('T')[0]}
                className={`w-full pl-11 pr-4 py-4 bg-slate-50 border-2 ${
                  errors.endDate ? 'border-rose-500 focus:border-rose-500' : 'border-transparent focus:border-toba-green/20'
                } focus:bg-white rounded-2xl transition-all font-medium text-slate-900`}
              />
            </FormField>
          </div>

          {/* Notes */}
          <FormField
            label="Catatan (opsional)"
            error={errors.notes}
            maxLength={500}
            currentLength={watch('notes')?.length || 0}
          >
            <textarea
              {...register('notes', {
                maxLength: { value: 500, message: 'Catatan maksimal 500 karakter' }
              })}
              rows={2}
              className={`w-full px-5 py-4 bg-slate-50 border-2 ${
                errors.notes ? 'border-rose-500 focus:border-rose-500' : 'border-transparent focus:border-toba-green/20'
              } focus:bg-white rounded-2xl transition-all font-medium text-slate-900 resize-none`}
              placeholder="Permintaan khusus, jumlah orang, dll..."
            />
          </FormField>

          {/* Price Summary */}
          {startDate && endDate && (
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
        </form>
      </motion.div>
    </div>
  );
}
