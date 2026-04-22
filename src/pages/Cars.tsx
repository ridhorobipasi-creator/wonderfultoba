"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Car } from '../types';
import CarCard from '../components/CarCard';
import { Search, SlidersHorizontal, Car as CarIcon, ArrowRight, CheckCircle } from 'lucide-react';
import { motion } from 'framer-motion';
import { Helmet } from 'react-helmet-async';
import { fallbackCars } from '../utils/fallbackData';

export default function Cars() {
  const [cars, setCars] = useState<Car[]>([]);
  const [loading, setLoading] = useState(true);
  const [filterType, setFilterType] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await api.get('/cars');
        setCars(Array.isArray(res.data) && res.data.length > 0 ? res.data : fallbackCars);
      } catch (error) {
        console.error('Error fetching data:', error);
        setCars(fallbackCars);
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, []);

  const filteredCars = cars.filter(c => {
    const matchesType = filterType === 'all' || c.type === filterType;
    const matchesSearch = c.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      c.type.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesType && matchesSearch;
  });

  const carTypes = Array.from(new Set(cars.map(c => c.type)));

  return (
    <div className="bg-[#f8fafc] min-h-screen pb-24">
      <Helmet>
        <title>Rental Mobil Sumatera Utara – Wonderful Toba</title>
        <meta name="description" content="Sewa mobil untuk keliling Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang. Armada terawat, sopir berpengalaman, harga terjangkau." />
      </Helmet>

      {/* Hero Header */}
      <div className="relative h-[60vh] flex items-center justify-center overflow-hidden">
        <img
          src={cars[0]?.images?.[0] || '/assets/images/2023/10/001-1.jpg'}
          alt="Rental Mobil Sumatera Utara"
          className="absolute inset-0 w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-b from-slate-900/70 via-slate-900/50 to-[#f8fafc]" />

        <div className="relative z-10 max-w-7xl mx-auto px-4 text-center">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.6 }}>
            <span className="inline-block px-4 py-1.5 bg-toba-green text-white text-[10px] font-bold uppercase tracking-[0.3em] rounded-full mb-6">
              Rental Mobil Sumatera Utara
            </span>
            <h1 className="text-5xl md:text-7xl font-bold text-white mb-6 tracking-tight">
              Jelajahi <span className="text-toba-accent">Sumut</span> Bebas
            </h1>
            <p className="text-slate-200 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
              Dari Medan ke Danau Toba, Samosir, Berastagi, hingga Bukit Lawang — armada kami siap menemani setiap perjalanan Anda.
            </p>
          </motion.div>
        </div>
      </div>

      {/* Search & Filter Section */}
      <div className="max-w-7xl mx-auto px-4 -mt-24 relative z-20">
        <div className="bg-white/80 backdrop-blur-2xl p-4 md:p-8 rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-white/50">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            {/* Search Input */}
            <div className="lg:col-span-6 relative group">
              <div className="absolute left-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-focus-within:bg-toba-green group-focus-within:text-white transition-all duration-300">
                <Search size={18} />
              </div>
              <input
                type="text"
                placeholder="Cari merk atau tipe mobil..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-20 pr-6 py-5 bg-slate-50 border-none rounded-[2rem] focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 placeholder:text-slate-400 transition-all"
              />
            </div>

            {/* Type Filter */}
            <div className="lg:col-span-4 relative group">
              <div className="absolute left-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-focus-within:bg-toba-green group-focus-within:text-white transition-all duration-300">
                <CarIcon size={18} />
              </div>
              <select
                value={filterType}
                onChange={(e) => setFilterType(e.target.value)}
                className="w-full pl-20 pr-10 py-5 bg-slate-50 border-none rounded-[2rem] focus:ring-2 focus:ring-toba-green/20 font-medium text-slate-700 appearance-none transition-all cursor-pointer"
              >
                <option value="all">Semua Tipe Armada</option>
                {carTypes.map(type => (
                  <option key={type} value={type}>{type}</option>
                ))}
              </select>
            </div>

            {/* Filter Button */}
            <div className="lg:col-span-2">
              <button className="w-full h-16 bg-slate-900 text-white rounded-[2rem] hover:bg-toba-green transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-2 font-bold text-sm">
                <SlidersHorizontal size={18} /> Filter
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Results Section */}
      <div className="max-w-7xl mx-auto px-4 mt-20">
        <div className="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
          <div>
            <h2 className="text-3xl font-bold text-slate-900 mb-2">Pilihan Armada</h2>
            <p className="text-slate-500 font-medium">
              Tersedia <span className="text-toba-green font-bold">{filteredCars.length}</span> unit kendaraan siap menemani perjalanan Anda.
            </p>
          </div>
          <div className="flex items-center bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest px-4">Urutkan:</span>
            <select className="bg-slate-50 border-none rounded-xl py-2 pl-4 pr-8 text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer">
              <option>Terpopuler</option>
              <option>Harga Terendah</option>
              <option>Harga Tertinggi</option>
            </select>
          </div>
        </div>

        {loading ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            {[1, 2, 3, 4, 5, 6].map(i => (
              <div key={i} className="h-[550px] bg-white rounded-[3rem] animate-pulse border border-slate-100 shadow-sm" />
            ))}
          </div>
        ) : filteredCars.length > 0 ? (
          <motion.div layout className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            {filteredCars.map((car, index) => (
              <motion.div key={car.id} initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: index * 0.1 }}>
                <CarCard car={car} />
              </motion.div>
            ))}
          </motion.div>
        ) : (
          <div className="text-center py-32 bg-white rounded-[4rem] shadow-sm border border-slate-100">
            <div className="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-200">
              <CarIcon size={64} />
            </div>
            <h3 className="text-3xl font-bold text-slate-900 mb-4">Armada Tidak Ditemukan</h3>
            <p className="text-slate-500 text-lg max-w-md mx-auto leading-relaxed">
              Maaf, kami tidak dapat menemukan kendaraan yang sesuai. Coba gunakan kata kunci lain.
            </p>
            <button
              onClick={() => { setSearchQuery(''); setFilterType('all'); }}
              className="mt-10 px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-toba-green transition-all shadow-xl shadow-slate-200"
            >
              Reset Semua Filter
            </button>
          </div>
        )}

        {!loading && filteredCars.length > 0 && (
          <div className="mt-24 flex justify-center items-center space-x-3">
            <button className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-all">
              <ArrowRight size={20} className="rotate-180" />
            </button>
            {[1, 2, 3].map(i => (
              <button key={i} className={`w-12 h-12 rounded-2xl font-bold transition-all ${i === 1 ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'}`}>
                {i}
              </button>
            ))}
            <button className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-all">
              <ArrowRight size={20} />
            </button>
          </div>
        )}
      </div>

      {/* Benefits Banner */}
      <div className="max-w-7xl mx-auto px-4 mt-20">
        <div className="bg-slate-900 rounded-[2.5rem] p-10 md:p-14 relative overflow-hidden">
          <div className="absolute inset-0 opacity-10">
            <img src="/assets/images/2023/10/001-1.jpg" alt="" className="w-full h-full object-cover" />
          </div>
          <div className="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
              <h3 className="text-2xl md:text-3xl font-black text-white mb-2">Kenapa Sewa Mobil di Wonderful Toba?</h3>
              <p className="text-white/80 font-medium mb-6">Kepercayaan ribuan wisatawan sejak 2010</p>
              <div className="grid grid-cols-2 gap-3">
                {['Armada Terawat', 'Sopir Hafal Rute Sumut', 'Asuransi Perjalanan', 'Harga Transparan', 'Tersedia 24 Jam', 'Antar-Jemput Bandara Kualanamu'].map((b, i) => (
                  <div key={i} className="flex items-center gap-2 text-white/90 text-sm font-medium">
                    <CheckCircle size={14} className="text-toba-accent shrink-0" /> {b}
                  </div>
                ))}
              </div>
            </div>
            <a href="https://wa.me/6281323888207" target="_blank" rel="noopener noreferrer"
              className="shrink-0 px-10 py-4 bg-toba-green text-white rounded-2xl font-black hover:bg-toba-accent transition-all shadow-xl shadow-toba-green/20 whitespace-nowrap">
              Pesan via WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  );
}
