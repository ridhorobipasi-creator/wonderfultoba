"use client";

import { useState, useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import api from '../lib/api';
import { Car } from '../types';
import { motion } from 'framer-motion';
import { ArrowLeft, Users, Fuel, Gauge, Star, ShieldCheck, MessageCircle } from 'lucide-react';
import BookingModal from '../components/BookingModal';
import { useStore } from '../store/useStore';
import { toast } from 'sonner';
import { Helmet } from 'react-helmet-async';
import { cn } from '../utils/cn';

export default function CarDetail() {
  const params = useParams<{ id: string }>();
  const id = params?.id;
  const router = useRouter();
  const { user } = useStore();
  const [car, setCar] = useState<Car | null>(null);
  const [loading, setLoading] = useState(true);
  const [showBooking, setShowBooking] = useState(false);

  useEffect(() => {
    if (!id) return;
    const fetch = async () => {
      try {
        const res = await api.get(`/cars/${id}`);
        setCar(res.data);
      } catch {
        router.push('/cars');
      } finally {
        setLoading(false);
      }
    };
    fetch();
  }, [id, router]);

  const handleBookClick = () => {
    if (car?.status === 'booked') {
      toast.error('Kendaraan ini sedang tidak tersedia');
      return;
    }
    setShowBooking(true);
  };

  if (loading) return (
    <div className="min-h-screen flex items-center justify-center bg-slate-50">
      <div className="w-12 h-12 border-4 border-slate-200 border-t-toba-green rounded-full animate-spin" />
    </div>
  );

  if (!car) return null;

  const dailyRate = car.pricePerDay ?? car.price ?? 0;
  const mainImage = car.images?.[0] || 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800';

  const specs = [
    { icon: Users, label: 'Kapasitas', value: car.seats ? `${car.seats} Kursi` : '5 Kursi' },
    { icon: Fuel, label: 'Bahan Bakar', value: car.fuel || 'Bensin' },
    { icon: Gauge, label: 'Transmisi', value: car.transmission || 'Manual' },
  ];

  return (
    <>
      <Helmet>
        <title>{car.name} – Wonderful Toba Car Rental</title>
        <meta name="description" content={`Sewa ${car.name} mulai Rp ${dailyRate.toLocaleString('id-ID')}/hari`} />
      </Helmet>

      <div className="bg-slate-50 min-h-screen pb-24 pt-24">
        <div className="max-w-5xl mx-auto px-4">
          <button onClick={() => router.push('/cars')} className="flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-8 transition-colors">
            <ArrowLeft size={18} /> Kembali ke Armada
          </button>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {/* Left */}
            <div className="lg:col-span-2 space-y-6">
              <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}
                className="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100">
                <div className="relative h-72 bg-slate-50 flex items-center justify-center p-8">
                  <img
                    src={mainImage}
                    alt={car.name}
                    className="w-full h-full object-contain"
                    referrerPolicy="no-referrer"
                  />
                  <div className="absolute top-5 left-5">
                    <span className={cn(
                      "px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider",
                      car.status === 'available' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'
                    )}>
                      {car.status === 'available' ? 'Tersedia' : 'Terpesan'}
                    </span>
                  </div>
                  <div className="absolute bottom-5 right-5 bg-white/80 backdrop-blur-md px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                    <Star size={12} className="text-amber-400 fill-amber-400" />
                    <span className="text-xs font-bold text-slate-700">4.9</span>
                  </div>
                </div>

                {/* Specs */}
                <div className="p-8">
                  <h2 className="text-lg font-black text-slate-900 mb-5">Spesifikasi Kendaraan</h2>
                  <div className="grid grid-cols-3 gap-4">
                    {specs.map((spec, i) => (
                      <div key={i} className="flex flex-col items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <spec.icon size={22} className="text-toba-green mb-2" />
                        <p className="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{spec.label}</p>
                        <p className="font-bold text-slate-900 text-sm">{spec.value}</p>
                      </div>
                    ))}
                  </div>
                </div>
              </motion.div>

              {/* Benefits */}
              <div className="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <h2 className="text-lg font-black text-slate-900 mb-5">Keunggulan Layanan</h2>
                <div className="grid grid-cols-2 gap-3">
                  {['Asuransi Perjalanan', 'Sopir Hafal Rute Sumut', 'AC Dingin', 'Bensin Termasuk', 'Siap 24 Jam', 'Antar-Jemput Bandara Kualanamu'].map((b, i) => (
                    <div key={i} className="flex items-center gap-2.5 text-sm text-slate-600 font-medium">
                      <ShieldCheck size={16} className="text-toba-green shrink-0" />
                      {b}
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Right: Booking Card */}
            <div>
              <motion.div initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }}
                className="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 sticky top-28">
                <span className="text-xs font-black text-toba-green uppercase tracking-widest">{car.type}</span>
                <h1 className="text-2xl font-black text-slate-900 mt-1 mb-5 leading-tight">{car.name}</h1>

                <div className="border-t border-slate-100 pt-5 mb-6">
                  <p className="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Harga Sewa</p>
                  <p className="text-3xl font-black text-slate-900">
                    <span className="text-base font-bold text-slate-400 mr-1">Rp</span>
                    {dailyRate.toLocaleString('id-ID')}
                    <span className="text-sm font-bold text-slate-400 ml-1">/hari</span>
                  </p>
                </div>

                <button
                  onClick={handleBookClick}
                  disabled={car.status === 'booked'}
                  className="w-full py-4 bg-toba-green text-white rounded-2xl font-black text-sm hover:bg-toba-green/90 transition-all shadow-xl shadow-toba-green/20 disabled:opacity-50 disabled:cursor-not-allowed mb-3"
                >
                  {car.status === 'available' ? 'Sewa Sekarang' : 'Tidak Tersedia'}
                </button>

                <a
                  href={`https://wa.me/6281323888207?text=${encodeURIComponent(`Halo, saya ingin menyewa: *${car.name}*`)}`}
                  target="_blank" rel="noopener noreferrer"
                  className="w-full flex items-center justify-center gap-2 py-3.5 bg-emerald-50 text-emerald-600 rounded-2xl font-bold text-sm hover:bg-emerald-100 transition-all border border-emerald-100"
                >
                  <MessageCircle size={18} /> Tanya via WhatsApp
                </a>
              </motion.div>
            </div>
          </div>
        </div>
      </div>

      {showBooking && (
        <BookingModal
          type="car"
          itemId={String(car.id)}
          itemName={car.name}
          pricePerUnit={dailyRate}
          onClose={() => setShowBooking(false)}
        />
      )}
    </>
  );
}
