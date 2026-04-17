"use client";

import { motion } from 'framer-motion';
import { Search, MapPin, Calendar, Plane, Car, ChevronDown } from 'lucide-react';
import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { cn } from '../utils/cn';

const BG_IMAGE = 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=90&w=2400';

export default function Hero() {
  const [activeTab, setActiveTab] = useState('tour');
  const [destination, setDestination] = useState('');
  const router = useRouter();

  const tabs = [
    { id: 'tour', label: 'Tour Packages', icon: Plane },
    { id: 'car', label: 'Car Rental', icon: Car },
  ];

  const handleSearch = () => {
    if (activeTab === 'tour') {
      router.push(destination ? `/tour/packages?q=${encodeURIComponent(destination)}` : '/tour/packages');
    } else {
      router.push(destination ? `/tour/cars?q=${encodeURIComponent(destination)}` : '/tour/cars');
    }
  };

  const destinations = ['Danau Toba', 'Pulau Samosir', 'Berastagi', 'Tangkahan', 'Bukit Lawang'];

  return (
    <section className="relative min-h-screen flex flex-col justify-center overflow-hidden">
      {/* Full-screen background */}
      <div className="absolute inset-0 z-0">
        <img
          src={BG_IMAGE}
          alt="Danau Toba"
          className="w-full h-full object-cover"
          referrerPolicy="no-referrer"
        />
        {/* Dark overlay gradient */}
        <div className="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-900/60 to-slate-900/20" />
        <div className="absolute inset-0 bg-gradient-to-t from-slate-950/50 via-transparent to-transparent" />
      </div>

      {/* Floating particles / decorative */}
      <div className="absolute top-1/4 right-1/4 w-96 h-96 bg-toba-green/10 rounded-full blur-3xl z-0 pointer-events-none" />
      <div className="absolute bottom-1/3 right-1/3 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl z-0 pointer-events-none" />

      {/* Content */}
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-32 pb-24">
        <div className="max-w-3xl">
          {/* Badge */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="flex items-center space-x-3 mb-8"
          >
            <div className="h-px w-12 bg-toba-accent" />
            <span className="text-toba-accent font-black text-xs uppercase tracking-[0.35em]">
              Explore North Sumatra with Us
            </span>
          </motion.div>

          {/* Headline */}
          <motion.h1
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, delay: 0.1 }}
            className="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 leading-[1.05] tracking-tight"
          >
            Discover the <br />
            <span className="text-toba-accent">Magic of</span>
            <br />
            <span className="text-white">Toba</span>
          </motion.h1>

          {/* Subtitle */}
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, delay: 0.2 }}
            className="text-lg text-white/70 mb-10 max-w-xl font-medium leading-relaxed"
          >
            Rasakan keindahan Danau Toba dan kekayaan budaya Batak bersama kami.
            Paket wisata premium, terpercaya, dan tak terlupakan.
          </motion.p>

          {/* Search Widget */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, delay: 0.3 }}
            className="bg-white/10 backdrop-blur-xl rounded-[2rem] border border-white/20 p-2 shadow-2xl max-w-2xl"
          >
            {/* Tabs */}
            <div className="flex p-1.5 space-x-1 mb-2">
              {tabs.map((tab) => (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={cn(
                    "flex items-center space-x-2 px-5 py-2.5 rounded-xl text-xs font-bold transition-all",
                    activeTab === tab.id
                      ? "bg-toba-green text-white shadow-lg shadow-toba-green/30"
                      : "text-white/60 hover:text-white hover:bg-white/10"
                  )}
                >
                  <tab.icon size={14} />
                  <span className="uppercase tracking-wider">{tab.label}</span>
                </button>
              ))}
            </div>

            {/* Inputs */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-2 p-1.5">
              {/* Destination */}
              <div className="md:col-span-1 relative bg-white/10 hover:bg-white/15 rounded-xl p-4 transition-all border border-white/10 focus-within:border-toba-accent/50">
                <p className="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5">Destinasi</p>
                <div className="flex items-center">
                  <MapPin size={15} className="text-toba-accent mr-2 shrink-0" />
                  <input
                    type="text"
                    value={destination}
                    onChange={e => setDestination(e.target.value)}
                    onKeyDown={e => e.key === 'Enter' && handleSearch()}
                    placeholder="Mau ke mana?"
                    list="destinations"
                    className="bg-transparent border-none focus:ring-0 text-sm font-bold text-white placeholder:text-white/40 w-full"
                  />
                  <datalist id="destinations">
                    {destinations.map(d => <option key={d} value={d} />)}
                  </datalist>
                </div>
              </div>

              {/* Date */}
              <div className="md:col-span-1 relative bg-white/10 hover:bg-white/15 rounded-xl p-4 transition-all border border-white/10 focus-within:border-toba-accent/50">
                <p className="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5">Tanggal</p>
                <div className="flex items-center">
                  <Calendar size={15} className="text-toba-accent mr-2 shrink-0" />
                  <input
                    type="date"
                    min={new Date().toISOString().split('T')[0]}
                    className="bg-transparent border-none focus:ring-0 text-sm font-bold text-white/80 w-full [color-scheme:dark]"
                  />
                </div>
              </div>

              {/* Search Button */}
              <button
                onClick={handleSearch}
                className="bg-toba-green text-white rounded-xl p-4 font-black text-sm uppercase tracking-[0.15em] hover:bg-toba-accent transition-all flex items-center justify-center space-x-2 shadow-xl shadow-toba-green/30 group/btn"
              >
                <Search size={18} className="group-hover/btn:scale-110 transition-transform" />
                <span>Cari</span>
              </button>
            </div>
          </motion.div>

          {/* Quick destination chips */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.45 }}
            className="flex flex-wrap gap-2 mt-6"
          >
            <span className="text-white/40 text-xs font-bold self-center mr-1">Populer:</span>
            {destinations.map(d => (
              <button
                key={d}
                onClick={() => { setDestination(d); setActiveTab('tour'); }}
                className="px-4 py-1.5 bg-white/10 hover:bg-toba-green/30 border border-white/15 hover:border-toba-accent/40 text-white/70 hover:text-white rounded-full text-xs font-bold transition-all backdrop-blur-sm"
              >
                {d}
              </button>
            ))}
          </motion.div>

          {/* Stats */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.55 }}
            className="flex items-center gap-8 mt-12"
          >
            {[
              { value: '150k+', label: 'Happy Travelers' },
              { value: '50+', label: 'Destinasi' },
              { value: '15+', label: 'Tahun Pengalaman' },
            ].map((stat, i) => (
              <div key={i} className="text-center">
                <p className="text-2xl font-black text-white">{stat.value}</p>
                <p className="text-xs text-white/50 font-bold uppercase tracking-wider">{stat.label}</p>
              </div>
            ))}
          </motion.div>
        </div>
      </div>

      {/* Scroll indicator */}
      <motion.div
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ delay: 1.2 }}
        className="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2"
      >
        <span className="text-white/40 text-[10px] font-bold uppercase tracking-widest">Scroll</span>
        <motion.div
          animate={{ y: [0, 8, 0] }}
          transition={{ repeat: Infinity, duration: 1.5 }}
        >
          <ChevronDown size={20} className="text-white/40" />
        </motion.div>
      </motion.div>
    </section>
  );
}
