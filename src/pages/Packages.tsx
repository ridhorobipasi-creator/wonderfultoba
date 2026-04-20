"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Package, City } from '../types';
// import { fallbackPackages, fallbackCities } from '../utils/fallbackData';
import PackageCard from '../components/PackageCard';
import { Search, SlidersHorizontal, MapPin, Calendar, Users, ArrowRight, Compass } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { Helmet } from 'react-helmet-async';

const durationOptions = ['Semua', '1-3 Hari', '4-7 Hari', '7+ Hari'];

function matchDuration(duration: string, filter: string) {
  if (filter === 'Semua') return true;
  const days = parseInt(duration);
  if (filter === '1-3 Hari') return days >= 1 && days <= 3;
  if (filter === '4-7 Hari') return days >= 4 && days <= 7;
  if (filter === '7+ Hari') return days > 7;
  return true;
}

export default function Packages({ category }: { category?: 'tour' | 'outbound' }) {
  const [packages, setPackages] = useState<Package[]>([]);
  const [cities, setCities] = useState<City[]>([]);
  const [loading, setLoading] = useState(true);
  const [filterCity, setFilterCity] = useState('all');
  const [filterDuration, setFilterDuration] = useState('Semua');
  const [filterStatus, setFilterStatus] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [sortBy, setSortBy] = useState('default');

  const [error, setError] = useState<string | null>(null);
  useEffect(() => {
    const fetchData = async () => {
      try {
        const [pkgRes, cityRes] = await Promise.all([
          api.get('/packages'),
          api.get('/cities'),
        ]);
        setPackages(Array.isArray(pkgRes.data) ? pkgRes.data : []);
        setCities(Array.isArray(cityRes.data) ? cityRes.data : []);
      } catch (error) {
        setError('Gagal memuat data paket atau kota. Silakan refresh halaman.');
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, []);

  const filteredPackages = packages
    .filter(p => {
      // If we are in the Outbound scope, strictly show outbound category
      // If we are in the Tour scope, rigorously show ONLY tour category or things without categories
      const matchCategory = category === 'outbound' 
        ? p.category === 'outbound'
        : (p.category === 'tour' || !p.category);

      const matchCity = filterCity === 'all' || String(p.cityId) === filterCity;
      const matchSearch = p.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
        p.description.toLowerCase().includes(searchQuery.toLowerCase());
      const matchDur = matchDuration(p.duration, filterDuration);
      const matchStatus = filterStatus === 'all' || p.status === filterStatus;
      return matchCategory && matchCity && matchSearch && matchDur && matchStatus;
    })
    .sort((a, b) => {
      if (sortBy === 'price-asc') return a.price - b.price;
      if (sortBy === 'price-desc') return b.price - a.price;
      return 0;
    });

  if (loading) {
    return <div className="flex flex-col items-center justify-center min-h-[400px] py-32"><div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin mb-6" /><p className="text-slate-400 font-medium">Memuat data paket...</p></div>;
  }
  if (error) {
    return <div className="flex flex-col items-center justify-center min-h-[400px] py-32"><p className="text-red-500 font-bold text-lg">{error}</p></div>;
  }
  return (
    <div className="bg-[#f8fafc] min-h-screen pb-24">
      <Helmet>
        <title>Paket Wisata Sumatera Utara – Wonderful Toba</title>
        <meta name="description" content="Temukan paket wisata terbaik ke Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang. Harga terjangkau, pelayanan premium." />
      </Helmet>
      {/* Hero */}
      <div className="relative h-[65vh] flex items-end overflow-hidden">
        <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000"
          alt="Packages Hero" className="absolute inset-0 w-full h-full object-cover" />
        <div className="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-slate-900/50 to-[#f8fafc]" />
        <div className="relative z-10 w-full max-w-7xl mx-auto px-4 pb-32">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.6 }}>
            <span className="inline-block px-4 py-1.5 bg-toba-green text-white text-[10px] font-bold uppercase tracking-[0.3em] rounded-full mb-4">
              Eksplorasi Sumatera Utara
            </span>
            <h1 className="text-5xl md:text-7xl font-black text-white tracking-tight leading-tight">
              Paket Wisata <span className="text-toba-accent">Pilihan</span><br />Terbaik Sumut
            </h1>
          </motion.div>
        </div>
      </div>

      {/* Search & Filter Card */}
      <div className="max-w-7xl mx-auto px-4 -mt-20 relative z-20">
        <div className="bg-white/90 backdrop-blur-2xl p-6 md:p-8 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white/60">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {/* Search */}
            <div className="relative group lg:col-span-2">
              <div className="absolute left-5 top-1/2 -translate-y-1/2 w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-focus-within:bg-toba-green group-focus-within:text-white transition-all">
                <Search size={16} />
              </div>
              <input type="text" placeholder="Cari destinasi atau paket..." value={searchQuery}
                onChange={e => setSearchQuery(e.target.value)}
                className="w-full pl-16 pr-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 placeholder:text-slate-400" />
            </div>
            {/* City */}
            <div className="relative">
              <MapPin className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
              <select value={filterCity} onChange={e => setFilterCity(e.target.value)}
                className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 appearance-none cursor-pointer">
                <option value="all">Semua Wilayah</option>
                {cities.map(c => <option key={c.id} value={String(c.id)}>{c.name}</option>)}
              </select>
            </div>
            {/* Sort */}
            <div className="relative">
              <SlidersHorizontal className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
              <select value={sortBy} onChange={e => setSortBy(e.target.value)}
                className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 appearance-none cursor-pointer">
                <option value="default">Urutkan</option>
                <option value="price-asc">Harga Terendah</option>
                <option value="price-desc">Harga Tertinggi</option>
              </select>
            </div>
          </div>

          {/* Quick filters row */}
          <div className="flex flex-wrap gap-2 mt-4">
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest self-center mr-1">Durasi:</span>
            {durationOptions.map(d => (
              <button key={d} onClick={() => setFilterDuration(d)}
                className={`flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold transition-all ${filterDuration === d ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'}`}>
                <Calendar size={11} /> {d}
              </button>
            ))}
            <div className="w-px bg-slate-200 mx-1 self-stretch" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest self-center mr-1">Status:</span>
            {[{ val: 'all', label: 'Semua' }, { val: 'active', label: '✅ Aktif' }, { val: 'inactive', label: '⏸ Nonaktif' }].map(opt => (
              <button key={opt.val} onClick={() => setFilterStatus(opt.val)}
                className={`px-4 py-2 rounded-full text-xs font-bold transition-all ${filterStatus === opt.val ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'}`}>
                {opt.label}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Results */}
      <div className="max-w-7xl mx-auto px-4 mt-14">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
          <div>
            <h2 className="text-2xl font-black text-slate-900 mb-1">Menampilkan Hasil</h2>
            <p className="text-slate-500 font-medium text-sm">
              Ditemukan <span className="text-toba-green font-black">{filteredPackages.length}</span> paket wisata
            </p>
          </div>
          {/* City quick chips */}
          <div className="flex gap-2 flex-wrap">
            {cities.slice(0, 5).map(c => (
              <button key={c.id} onClick={() => setFilterCity(filterCity === String(c.id) ? 'all' : String(c.id))}
                className={`flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold transition-all border ${filterCity === String(c.id) ? 'bg-toba-green text-white border-toba-green' : 'bg-white text-slate-600 border-slate-200 hover:border-toba-green'}`}>
                <MapPin size={11} /> {c.name}
              </button>
            ))}
          </div>
        </div>

        {loading ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {[1, 2, 3, 4, 5, 6].map(i => <div key={i} className="h-[520px] bg-white rounded-[2.5rem] animate-pulse border border-slate-100" />)}
          </div>
        ) : filteredPackages.length > 0 ? (
          <AnimatePresence mode="popLayout">
            <motion.div layout className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {filteredPackages.map((pkg, index) => (
                <motion.div key={pkg.id} layout initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0, scale: 0.95 }} transition={{ delay: index * 0.06 }}>
                  <PackageCard package={pkg} locationName={cities.find(c => String(c.id) === String(pkg.cityId))?.name} />
                </motion.div>
              ))}
            </motion.div>
          </AnimatePresence>
        ) : (
          <div className="text-center py-28 bg-white rounded-[3rem] border border-slate-100">
            <Compass size={56} className="mx-auto text-slate-200 mb-5" />
            <h3 className="text-2xl font-black text-slate-900 mb-3">Paket Tidak Ditemukan</h3>
            <p className="text-slate-500 max-w-sm mx-auto mb-8">Coba ubah filter atau kata kunci pencarian Anda.</p>
            <button onClick={() => { setSearchQuery(''); setFilterCity('all'); setFilterDuration('Semua'); setFilterStatus('all'); }}
              className="px-8 py-3.5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-toba-green transition-all">
              Reset Filter
            </button>
          </div>
        )}

        {!loading && filteredPackages.length > 0 && (
          <div className="mt-16 flex justify-center items-center gap-2">
            <button className="w-11 h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50">
              <ArrowRight size={18} className="rotate-180" />
            </button>
            {[1, 2, 3].map(i => (
              <button key={i} className={`w-11 h-11 rounded-2xl font-bold transition-all ${i === 1 ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'}`}>{i}</button>
            ))}
            <button className="w-11 h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50">
              <ArrowRight size={18} />
            </button>
          </div>
        )}
      </div>

      {/* CTA Banner */}
      <div className="max-w-7xl mx-auto px-4 mt-20">
        <div className="bg-gradient-to-r from-toba-green to-emerald-600 rounded-[2.5rem] p-10 md:p-14 text-center relative overflow-hidden">
          <div className="absolute inset-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000" alt="" className="w-full h-full object-cover" />
          </div>
          <div className="relative z-10">
            <h3 className="text-2xl md:text-4xl font-black text-white mb-4">Tidak Menemukan Paket yang Cocok?</h3>
            <p className="text-white/80 font-medium mb-8 max-w-xl mx-auto">Kami siap merancang itinerary khusus sesuai kebutuhan dan budget Anda.</p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <a href="tel:+6281234567890" className="flex items-center justify-center gap-2 bg-white text-toba-green px-8 py-4 rounded-2xl font-black hover:bg-slate-50 transition-all shadow-xl">
                <Users size={18} /> Konsultasi Gratis
              </a>
              <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                className="flex items-center justify-center gap-2 bg-white/20 text-white border border-white/30 px-8 py-4 rounded-2xl font-black hover:bg-white/30 transition-all">
                WhatsApp Kami
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
