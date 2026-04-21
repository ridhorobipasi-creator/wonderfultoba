"use client";

import { motion } from 'framer-motion';
import { Star, MapPin, ArrowRight, Heart } from 'lucide-react';
import { Package, City } from '../types';
import { useRouter } from 'next/navigation';

interface PackageCardProps {
  package: Package;
  locationName?: string;
  locationData?: City;
  key?: string | number;
}

export default function PackageCard({ package: pkg, locationName, locationData }: PackageCardProps) {
  const router = useRouter();

  const displayLocation = locationData
    ? (locationData.type === 'international'
        ? `${locationData.place || locationData.region || ''}, ${locationData.country}`.replace(/^, /, '')
        : locationData.name)
    : (locationName || 'Sumatera Utara');

  const isInternational = locationData?.type === 'international';

  return (
    <motion.div
      whileHover={{ y: -12 }}
      className="bg-white rounded-[2rem] overflow-hidden border border-slate-100 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 group"
    >
      <div className="relative h-72 overflow-hidden">
        <img
          src={pkg.images[0] || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'}
          alt={pkg.name}
          className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
          referrerPolicy="no-referrer"
        />
        
        {/* Badges */}
        <div className="absolute top-5 left-5 flex flex-col space-y-2">
          <div className="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
            <Star size={14} className="text-amber-400 fill-amber-400" />
            <span className="font-black text-slate-800 text-[10px] uppercase tracking-wider">4.8</span>
          </div>
          <div className="bg-blue-600 text-white px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg">
            {pkg.duration}
          </div>
        </div>

        {/* Wishlist */}
        <button
          onClick={(e) => e.stopPropagation()}
          className="absolute top-5 right-5 w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-rose-500 transition-all"
          aria-label="Simpan ke wishlist"
        >
          <Heart size={20} />
        </button>

        {/* Gradient Overlay */}
        <div className="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
      </div>

      <div className="p-8">
        <div className="flex items-center text-blue-600 text-[10px] font-black uppercase tracking-[0.2em] mb-3">
          <MapPin size={12} className="mr-2" />
          <span>{displayLocation}</span>
          {isInternational && <span className="ml-1.5">✈️</span>}
        </div>
        
        <h3 className="text-2xl font-black text-slate-900 mb-4 line-clamp-1 group-hover:text-blue-600 transition-colors tracking-tight">
          {pkg.name}
        </h3>
        
        <p className="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2 font-medium">
          {pkg.description}
        </p>
        
        <div className="flex items-center justify-between pt-6 border-t border-slate-50">
          <div>
            <p className="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Mulai Dari</p>
            <div className="flex items-baseline space-x-1">
              <span className="text-xs font-bold text-slate-400">IDR</span>
              <span className="text-2xl font-black text-slate-900">
                {pkg.price.toLocaleString('id-ID')}
              </span>
            </div>
          </div>
          <button
            onClick={() => {
              const currentPath = typeof window !== 'undefined' ? window.location.pathname : '';
              const scope = currentPath.startsWith('/outbound') ? '/outbound' : '/tour';
              router.push(`${scope}/package/${pkg.slug}`);
            }}
            className="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 transition-all shadow-xl shadow-slate-200 group/btn"
            aria-label={`Lihat detail ${pkg.name}`}
          >
            <ArrowRight size={24} className="group-hover/btn:translate-x-1 transition-transform" />
          </button>
        </div>
      </div>
    </motion.div>
  );
}
