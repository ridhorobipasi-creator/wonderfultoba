"use client";

import { useState, useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import api from '../lib/api';
import { Package, City } from '../types';
import { motion } from 'framer-motion';
import { ArrowLeft, MapPin, Clock, CheckCircle, XCircle, Star, Users, MessageCircle, DollarSign, Camera } from 'lucide-react';
import BookingModal from '../components/BookingModal';
import { useStore } from '../store/useStore';
import { toast } from 'sonner';
import { Helmet } from 'react-helmet-async';

interface PricingDetail {
  pax: string | number;
  price_per_person: number | string;
}

interface ItineraryItem {
  day?: string | number;
  title: string;
  description: string;
}

interface PackageDetailModel extends Package {
  pricingDetails?: PricingDetail[];
  itinerary?: ItineraryItem[];
  itineraryText?: string;
  dronePrice: number;
  droneLocation?: string;
}

export default function PackageDetail() {
  const params = useParams<{ slug: string }>();
  const slug = params?.slug;
  const router = useRouter();
  const { user } = useStore();
  const [pkg, setPkg] = useState<PackageDetailModel | null>(null);
  const [city, setCity] = useState<City | null>(null);
  const [loading, setLoading] = useState(true);
  const [showBooking, setShowBooking] = useState(false);
  const [activeImg, setActiveImg] = useState(0);

  useEffect(() => {
    if (!slug) return;
    const fetch = async () => {
      try {
        const res = await api.get(`/packages/${slug}`);
        const data = res.data as PackageDetailModel;
        setPkg(data);
        if (data.city) {
          setCity(data.city);
        }
      } catch {
        const scope = window.location.pathname.startsWith('/outbound') ? '/outbound' : '/tour';
        router.push(`${scope}/packages`);
      } finally {
        setLoading(false);
      }
    };
    fetch();
  }, [slug, router]);

  // Build display location from city data
  const locationDisplay = city
    ? ((city as any).type === 'international'
        ? `${(city as any).place || (city as any).region || ''}, ${(city as any).country}`.replace(/^, /, '')
        : city.name)
    : 'Sumatera Utara';
  const isInternational = (city as any)?.type === 'international';

  const handleBookClick = () => {
    setShowBooking(true);
  };

  if (loading) return (
    <div className="min-h-screen flex items-center justify-center bg-slate-50">
      <div className="w-12 h-12 border-4 border-slate-200 border-t-toba-green rounded-full animate-spin" />
    </div>
  );

  if (!pkg) return null;

  return (
    <>
      <Helmet>
        <title>{pkg.name} – Wonderful Toba</title>
        <meta name="description" content={pkg.description} />
      </Helmet>

      <div className="bg-slate-50 min-h-screen pb-24 pt-24">
        <div className="max-w-6xl mx-auto px-4">
          {/* Back */}
          <button onClick={() => {
            const scope = window.location.pathname.startsWith('/outbound') ? '/outbound' : '/tour';
            router.push(`${scope}/packages`);
          }} className="flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-8 transition-colors">
            <ArrowLeft size={18} /> Kembali ke Daftar Paket
          </button>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {/* Left: Images + Info */}
            <div className="lg:col-span-2 space-y-6">
              {/* Main Image */}
              <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}
                className="relative h-[420px] rounded-[2rem] overflow-hidden shadow-xl">
                <img
                  src={pkg.images[activeImg] || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'}
                  alt={pkg.name}
                  className="w-full h-full object-cover"
                  referrerPolicy="no-referrer"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent" />
                <div className="absolute bottom-5 left-5 flex items-center gap-2">
                  <span className={`px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider ${pkg.status === 'active' ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white'}`}>
                    {pkg.status === 'active' ? 'Tersedia' : 'Nonaktif'}
                  </span>
                </div>
              </motion.div>

              {/* Thumbnail strip */}
              {pkg.images.length > 1 && (
                <div className="flex gap-3">
                  {pkg.images.map((img, i) => (
                    <button key={i} onClick={() => setActiveImg(i)}
                      className={`w-20 h-16 rounded-xl overflow-hidden border-2 transition-all ${activeImg === i ? 'border-toba-green' : 'border-transparent'}`}>
                      <img src={img} alt="" className="w-full h-full object-cover" referrerPolicy="no-referrer" />
                    </button>
                  ))}
                </div>
              )}

              {/* Description */}
              <div className="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <h2 className="text-xl font-black text-slate-900 mb-4">Tentang Paket Ini</h2>
                <p className="text-slate-600 leading-relaxed font-medium">{pkg.description}</p>
              </div>

              {/* Pricing Details Table */}
              {pkg.pricingDetails && pkg.pricingDetails.length > 0 && (
                <div className="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                  <h2 className="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                    <DollarSign size={22} className="text-toba-green" /> Rincian Harga Paket
                  </h2>
                  <div className="overflow-hidden rounded-2xl border border-slate-50">
                    <table className="w-full text-left">
                      <thead>
                        <tr className="bg-slate-50">
                          <th className="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-400">Jumlah Peserta</th>
                          <th className="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-400 text-right">Harga / Orang</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-50">
                        {pkg.pricingDetails.map((p, i) => (
                          <tr key={i} className="hover:bg-slate-50/30">
                            <td className="px-6 py-4 font-bold text-slate-700">{p.pax} Orang</td>
                            <td className="px-6 py-4 font-black text-slate-900 text-right">Rp {Number(p.price_per_person).toLocaleString('id-ID')}</td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              )}

              {/* Itinerary Section */}
              {((pkg.itinerary?.length || 0) > 0 || pkg.itineraryText) && (
                <div className="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                  <h2 className="text-xl font-black text-slate-900 mb-8 flex items-center gap-2">
                    <MapPin size={22} className="text-toba-green" /> Jadwal Perjalanan
                  </h2>
                  
                  {pkg.itineraryText ? (
                    <div className="prose prose-slate max-w-none whitespace-pre-line text-slate-600 font-medium">
                      {pkg.itineraryText}
                    </div>
                  ) : (
                    <div className="space-y-8">
                      {pkg.itinerary?.map((day, i) => (
                        <div key={i} className="relative pl-10 border-l-2 border-slate-100 last:border-0 pb-2">
                          <div className="absolute -left-[11px] top-0 w-5 h-5 bg-white border-4 border-toba-green rounded-full shadow-sm" />
                          <div className="bg-slate-50 p-6 rounded-2xl">
                            <span className="text-[10px] font-black uppercase tracking-widest text-toba-green mb-1 block">Hari {day.day || (i + 1)}</span>
                            <h4 className="text-lg font-black text-slate-900 mb-2">{day.title}</h4>
                            <p className="text-sm text-slate-500 leading-relaxed font-medium">{day.description}</p>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              )}

              {/* Drone Info */}
              {pkg.dronePrice > 0 && (
                <div className="bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2rem] p-8 shadow-xl text-white relative overflow-hidden">
                  <div className="absolute top-0 right-0 p-10 opacity-10 rotate-12">
                    <Camera size={120} />
                  </div>
                  <div className="relative z-10">
                    <div className="flex items-center gap-2 mb-4">
                      <div className="bg-toba-green/20 p-2 rounded-xl">
                        <Camera size={20} className="text-toba-green" />
                      </div>
                      <span className="text-[10px] font-black uppercase tracking-widest text-toba-green">Add-on Layanan Drone</span>
                    </div>
                    <h3 className="text-2xl font-black mb-2">Dokumentasi Udara (Drone)</h3>
                    <p className="text-slate-400 text-sm mb-6 max-w-md">Abadikan momen terbaik Anda dari udara di {pkg.droneLocation || 'lokasi pilihan'}.</p>
                    <div className="flex items-baseline gap-2">
                      <span className="text-2xl font-black text-toba-green">+ Rp {pkg.dronePrice.toLocaleString('id-ID')}</span>
                      <span className="text-xs text-slate-500">/ paket</span>
                    </div>
                  </div>
                </div>
              )}

              {/* Includes / Excludes */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100">
                  <h3 className="font-black text-slate-900 mb-4 flex items-center gap-2">
                    <CheckCircle size={18} className="text-emerald-500" /> Sudah Termasuk
                  </h3>
                  <ul className="space-y-2">
                    {(pkg.includes || []).map((item, i) => (
                      <li key={i} className="flex items-center gap-2 text-sm text-slate-600 font-medium">
                        <span className="w-1.5 h-1.5 bg-emerald-500 rounded-full shrink-0" />
                        {item}
                      </li>
                    ))}
                    {(!pkg.includes || pkg.includes.length === 0) && <li className="text-sm text-slate-400">-</li>}
                  </ul>
                </div>
                <div className="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100">
                  <h3 className="font-black text-slate-900 mb-4 flex items-center gap-2">
                    <XCircle size={18} className="text-rose-400" /> Tidak Termasuk
                  </h3>
                  <ul className="space-y-2">
                    {(pkg.excludes || []).map((item, i) => (
                      <li key={i} className="flex items-center gap-2 text-sm text-slate-600 font-medium">
                        <span className="w-1.5 h-1.5 bg-rose-400 rounded-full shrink-0" />
                        {item}
                      </li>
                    ))}
                    {(!pkg.excludes || pkg.excludes.length === 0) && <li className="text-sm text-slate-400">-</li>}
                  </ul>
                </div>
              </div>
            </div>

            {/* Right: Booking Card */}
            <div className="space-y-5">
              <motion.div initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }}
                className="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 sticky top-28">
                <div className="flex items-center gap-2 text-toba-green text-xs font-black uppercase tracking-widest mb-3">
                  <MapPin size={14} />
                  {locationDisplay}
                  {isInternational && <span>✈️</span>}
                </div>
                <h1 className="text-2xl font-black text-slate-900 mb-2 leading-tight">{pkg.name}</h1>

                <div className="flex items-center gap-4 mb-6">
                  <div className="flex items-center gap-1.5 text-sm text-slate-500 font-medium">
                    <Clock size={14} className="text-toba-green" /> {pkg.duration}
                  </div>
                  <div className="flex items-center gap-1">
                    <Star size={14} className="text-amber-400 fill-amber-400" />
                    <span className="text-sm font-bold text-slate-700">4.8</span>
                    <span className="text-xs text-slate-400">(24 ulasan)</span>
                  </div>
                </div>

                <div className="border-t border-slate-100 pt-5 mb-6">
                  <p className="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Harga Per Orang</p>
                  <p className="text-3xl font-black text-slate-900">
                    <span className="text-base font-bold text-slate-400 mr-1">Rp</span>
                    {pkg.price.toLocaleString('id-ID')}
                  </p>
                </div>

                {pkg.isOutbound ? (
                  <a
                    href={`https://wa.me/6281323888207?text=${encodeURIComponent(`Halo Wonderful Toba, saya tertarik memesan paket Outbound: *${pkg.name}*. Mohon informasi lebih lanjut.`)}`}
                    target="_blank" rel="noopener noreferrer"
                    className="w-full flex items-center justify-center gap-2 py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/20 mb-3"
                  >
                    <MessageCircle size={18} /> Pesan via WhatsApp
                  </a>
                ) : (
                  <>
                    <button
                      onClick={handleBookClick}
                      disabled={pkg.status !== 'active'}
                      className="w-full py-4 bg-toba-green text-white rounded-2xl font-black text-sm hover:bg-toba-green/90 transition-all shadow-xl shadow-toba-green/20 disabled:opacity-50 disabled:cursor-not-allowed mb-3"
                    >
                      {pkg.status === 'active' ? 'Pesan Sekarang' : 'Paket Tidak Tersedia'}
                    </button>

                    <a
                      href={`https://wa.me/6281323888207?text=${encodeURIComponent(`Halo, saya tertarik dengan paket: *${pkg.name}*`)}`}
                      target="_blank" rel="noopener noreferrer"
                      className="w-full flex items-center justify-center gap-2 py-3.5 bg-emerald-50 text-emerald-600 rounded-2xl font-bold text-sm hover:bg-emerald-100 transition-all border border-emerald-100"
                    >
                      <MessageCircle size={18} /> Tanya via WhatsApp
                    </a>
                  </>
                )}

                <div className="mt-5 flex items-center gap-2 text-xs text-slate-400 font-medium">
                  <Users size={14} /> Sudah dipesan 24+ kali bulan ini
                </div>
              </motion.div>
            </div>
          </div>
        </div>
      </div>

      {showBooking && (
        <BookingModal
          type="package"
          itemId={String(pkg.id)}
          itemName={pkg.name}
          pricePerUnit={pkg.price}
          onClose={() => setShowBooking(false)}
        />
      )}
    </>
  );
}
