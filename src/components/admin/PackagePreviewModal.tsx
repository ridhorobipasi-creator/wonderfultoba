import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { X, MapPin, Clock, CheckCircle, XCircle, Star, Users, MessageCircle, DollarSign, Camera, FileText } from 'lucide-react';

interface PricingDetail {
  pax: string | number;
  price_per_person: number | string;
}

interface ItineraryItem {
  day?: string | number;
  title: string;
  description: string;
}

interface PreviewData {
  name: string;
  category: string;
  duration: string;
  price: number | string;
  description: string;
  short_description?: string;
  image: string;
  location_tag?: string;
  includes: string;
  excludes: string;
  notes?: string;
  itinerary: ItineraryItem[];
  pricing_details: PricingDetail[];
  itinerary_text?: string;
  drone_price?: number | string;
  drone_location?: string;
}

interface PackagePreviewModalProps {
  isOpen: boolean;
  onClose: () => void;
  data: PreviewData;
}

export default function PackagePreviewModal({ isOpen, onClose, data }: PackagePreviewModalProps) {
  if (!isOpen) return null;

  const images = data.image ? data.image.split(',').map(s => s.trim()) : [];
  const includes = data.includes ? data.includes.split(',').map(s => s.trim()) : [];
  const excludes = data.excludes ? data.excludes.split(',').map(s => s.trim()) : [];

  return (
    <AnimatePresence>
      <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm overflow-hidden">
        <motion.div
          initial={{ opacity: 0, scale: 0.95, y: 20 }}
          animate={{ opacity: 1, scale: 1, y: 0 }}
          exit={{ opacity: 0, scale: 0.95, y: 20 }}
          className="bg-slate-50 w-full max-w-6xl h-full max-h-[95vh] rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col"
        >
          {/* Header */}
          <div className="bg-white px-8 py-5 border-b border-slate-100 flex justify-between items-center shrink-0">
            <div>
              <h3 className="text-xl font-black text-slate-900">Preview Paket Wisata</h3>
              <p className="text-xs text-slate-400 font-bold uppercase tracking-widest">Tampilan Langsung di Website</p>
            </div>
            <button onClick={onClose} className="p-3 bg-slate-50 text-slate-400 hover:text-slate-900 rounded-2xl transition-all">
              <X size={24} />
            </button>
          </div>

          {/* Content (Scrollable) */}
          <div className="flex-1 overflow-y-auto p-8 lg:p-12 custom-scrollbar">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
              {/* Left Column */}
              <div className="lg:col-span-2 space-y-8">
                {/* Image Section */}
                <div className="relative h-[400px] rounded-[2.5rem] overflow-hidden shadow-lg bg-slate-200">
                  <img
                    src={images[0] || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'}
                    alt="Preview"
                    className="w-full h-full object-cover"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent" />
                  <div className="absolute bottom-6 left-6">
                    <span className="px-4 py-1.5 bg-emerald-500 text-white rounded-full text-xs font-black uppercase tracking-wider">
                      Tersedia
                    </span>
                  </div>
                </div>

                {/* Thumbnails */}
                {images.length > 1 && (
                  <div className="flex gap-3">
                    {images.map((img, i) => (
                      <div key={i} className="w-20 h-16 rounded-2xl overflow-hidden border-2 border-transparent bg-slate-200">
                        <img src={img} alt="" className="w-full h-full object-cover" />
                      </div>
                    ))}
                  </div>
                )}

                {/* Main Info Card */}
                <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                  <h2 className="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <FileText size={24} className="text-toba-green" /> Tentang Paket
                  </h2>
                  <div className="prose prose-slate max-w-none">
                    <div dangerouslySetInnerHTML={{ __html: data.description || 'Deskripsi belum diisi...' }} />
                  </div>
                </div>

                {/* Pricing Details */}
                {data.pricing_details && data.pricing_details.some(p => p.pax) && (
                  <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h2 className="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                      <DollarSign size={24} className="text-toba-green" /> Rincian Harga
                    </h2>
                    <div className="overflow-hidden rounded-3xl border border-slate-50">
                      <table className="w-full text-left">
                        <thead>
                          <tr className="bg-slate-50">
                            <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Jumlah Peserta</th>
                            <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Harga / Orang</th>
                          </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                          {data.pricing_details.map((p, i) => p.pax && (
                            <tr key={i} className="hover:bg-slate-50/30">
                              <td className="px-8 py-5 font-bold text-slate-700">{p.pax} Orang</td>
                              <td className="px-8 py-5 font-black text-slate-900 text-right">Rp {Number(p.price_per_person).toLocaleString('id-ID')}</td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    </div>
                  </div>
                )}

                {/* Itinerary */}
                {(data.itinerary.some(i => i.title) || data.itinerary_text) && (
                  <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h2 className="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
                      <MapPin size={24} className="text-toba-green" /> Jadwal Perjalanan
                    </h2>
                    {data.itinerary_text ? (
                      <div className="prose prose-slate max-w-none" dangerouslySetInnerHTML={{ __html: data.itinerary_text }} />
                    ) : (
                      <div className="space-y-8">
                        {data.itinerary.map((day, i) => day.title && (
                          <div key={i} className="relative pl-12 border-l-2 border-slate-100 last:border-0 pb-4">
                            <div className="absolute -left-[11px] top-0 w-5 h-5 bg-white border-4 border-toba-green rounded-full shadow-sm" />
                            <div className="bg-slate-50 p-6 rounded-3xl">
                              <span className="text-[10px] font-black uppercase tracking-widest text-toba-green mb-1 block">Hari {day.day || (i + 1)}</span>
                              <h4 className="text-xl font-black text-slate-900 mb-2">{day.title}</h4>
                              <p className="text-sm text-slate-500 leading-relaxed font-medium">{day.description}</p>
                            </div>
                          </div>
                        ))}
                      </div>
                    )}
                  </div>
                )}

                {/* Drone Addon */}
                {Number(data.drone_price) > 0 && (
                  <div className="bg-slate-900 rounded-[2.5rem] p-8 shadow-xl text-white relative overflow-hidden">
                    <div className="absolute top-0 right-0 p-10 opacity-10 rotate-12">
                      <Camera size={120} />
                    </div>
                    <div className="relative z-10">
                      <div className="flex items-center gap-2 mb-4">
                        <Camera size={20} className="text-toba-green" />
                        <span className="text-[10px] font-black uppercase tracking-widest text-toba-green">Add-on Drone</span>
                      </div>
                      <h3 className="text-2xl font-black mb-2">Dokumentasi Udara</h3>
                      <p className="text-slate-400 text-sm mb-6 max-w-md">Abadikan momen di {data.drone_location || 'lokasi pilihan'}.</p>
                      <span className="text-2xl font-black text-toba-green">+ Rp {Number(data.drone_price).toLocaleString('id-ID')}</span>
                    </div>
                  </div>
                )}

                {/* Facilities */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h3 className="font-black text-slate-900 mb-5 flex items-center gap-2">
                      <CheckCircle size={20} className="text-emerald-500" /> Termasuk
                    </h3>
                    <ul className="space-y-3">
                      {includes.map((item, i) => (
                        <li key={i} className="flex items-start gap-3 text-sm text-slate-600 font-medium">
                          <span className="w-1.5 h-1.5 bg-emerald-500 rounded-full mt-1.5 shrink-0" />
                          {item}
                        </li>
                      ))}
                    </ul>
                  </div>
                  <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h3 className="font-black text-slate-900 mb-5 flex items-center gap-2">
                      <XCircle size={20} className="text-rose-400" /> Tidak Termasuk
                    </h3>
                    <ul className="space-y-3">
                      {excludes.map((item, i) => (
                        <li key={i} className="flex items-start gap-3 text-sm text-slate-600 font-medium">
                          <span className="w-1.5 h-1.5 bg-rose-400 rounded-full mt-1.5 shrink-0" />
                          {item}
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              </div>

              {/* Right Column (Sidebar) */}
              <div className="space-y-6">
                <div className="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 sticky top-4">
                  <div className="flex items-center gap-2 text-toba-green text-[10px] font-black uppercase tracking-widest mb-3">
                    <MapPin size={14} />
                    {data.location_tag || 'Danau Toba, Sumatera Utara'}
                  </div>
                  <h1 className="text-2xl font-black text-slate-900 mb-4 leading-tight">{data.name || 'Nama Paket Belum Diisi'}</h1>
                  
                  <div className="flex items-center gap-4 mb-8">
                    <div className="flex items-center gap-1.5 text-xs text-slate-500 font-bold uppercase tracking-wider">
                      <Clock size={16} className="text-toba-green" /> {data.duration || '3D2N'}
                    </div>
                    <div className="flex items-center gap-1">
                      <Star size={16} className="text-amber-400 fill-amber-400" />
                      <span className="text-sm font-black text-slate-900">4.9</span>
                    </div>
                  </div>

                  <div className="border-t border-slate-100 pt-6 mb-8">
                    <p className="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Mulai Dari</p>
                    <p className="text-3xl font-black text-slate-900">
                      <span className="text-base font-bold text-slate-400 mr-1">Rp</span>
                      {Number(data.price || 0).toLocaleString('id-ID')}
                    </p>
                  </div>

                  <div className="space-y-3">
                    <div className="w-full py-4 bg-toba-green text-white rounded-2xl font-black text-sm text-center shadow-lg shadow-toba-green/20">
                      Pesan Sekarang
                    </div>
                    <div className="w-full py-4 bg-emerald-50 text-emerald-600 rounded-2xl font-black text-sm text-center border border-emerald-100">
                      Tanya via WhatsApp
                    </div>
                  </div>

                  <p className="mt-6 text-[10px] text-slate-400 font-bold uppercase tracking-widest text-center flex items-center justify-center gap-2">
                    <Users size={12} /> Dipercaya ratusan traveler
                  </p>
                </div>

                <div className="bg-amber-50 border border-amber-100 rounded-3xl p-6">
                  <h4 className="text-sm font-black text-amber-900 mb-2 uppercase tracking-widest">💡 Catatan Preview</h4>
                  <p className="text-xs text-amber-700 font-medium leading-relaxed">
                    Ini adalah simulasi tampilan di website. Pastikan deskripsi, itinerary, dan rincian harga sudah terlihat rapi sebelum menyimpan.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </motion.div>
      </div>
    </AnimatePresence>
  );
}
