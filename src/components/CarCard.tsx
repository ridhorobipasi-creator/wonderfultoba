"use client";

import { motion } from 'framer-motion';
import { Fuel, Users, Gauge, ArrowRight, Star, ShieldCheck } from 'lucide-react';
import { Car } from '../types';
import { useRouter } from 'next/navigation';
import { cn } from '../utils/cn';

interface CarCardProps {
  car: Car;
  key?: string | number;
}

export default function CarCard({ car }: CarCardProps) {
  const router = useRouter();
  const dailyRate = car.price ?? car.pricePerDay ?? 0;
  const mainImage = car.images?.[0] || car.image || 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800';

  const specs = [
    { icon: Users, label: `${car.capacity || 5} Kursi` },
    { icon: Fuel, label: car.fuel || 'Bensin' },
    { icon: Gauge, label: car.transmission || 'Manual' },
  ];

  return (
    <motion.div
      whileHover={{ y: -12 }}
      className="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl transition-all duration-500 group"
    >
      {/* Image Section */}
      <div className="relative h-64 overflow-hidden bg-slate-50 group-hover:bg-slate-100 transition-colors duration-500">
        <img
          src={mainImage}
          alt={car.name}
          className="w-full h-full object-contain p-8 group-hover:scale-110 transition-transform duration-700 ease-out"
          referrerPolicy="no-referrer"
        />
        
        {/* Badges */}
        <div className="absolute top-6 left-6 flex flex-col gap-2">
          <span className={cn(
            "px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-sm backdrop-blur-md",
            (car.status === 'available' || car.status === 'active') ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 border border-rose-500/20'
          )}>
            {(car.status === 'available' || car.status === 'active') ? 'Tersedia' : 'Terpesan'}
          </span>
          {dailyRate < 1000000 && (
            <span className="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-blue-500/10 text-blue-600 border border-blue-500/20 backdrop-blur-md">
              Best Deal
            </span>
          )}
        </div>

        {/* Rating Overlay */}
        <div className="absolute bottom-4 right-6 bg-white/80 backdrop-blur-md px-3 py-1 rounded-full border border-white/20 flex items-center space-x-1 shadow-sm">
          <Star size={12} className="text-amber-400 fill-amber-400" />
          <span className="text-xs font-bold text-slate-700">4.9</span>
        </div>
      </div>

      {/* Content Section */}
      <div className="p-8">
        <div className="flex justify-between items-start mb-6">
          <div className="flex-1">
            <div className="flex items-center space-x-2 mb-2">
              <span className="text-[10px] text-emerald-600 font-bold uppercase tracking-[0.2em]">{car.type}</span>
              <span className="w-1 h-1 bg-slate-300 rounded-full" />
              <div className="flex items-center text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                <ShieldCheck size={12} className="mr-1" />
                Asuransi
              </div>
            </div>
            <h3 className="text-2xl font-bold text-slate-900 group-hover:text-emerald-600 transition-colors leading-tight">
              {car.name}
            </h3>
          </div>
        </div>

        {/* Specs Grid */}
        <div className="grid grid-cols-3 gap-3 mb-8">
          {specs.map((spec, i) => (
            <div key={i} className="flex flex-col items-center justify-center p-3 bg-slate-50 rounded-[1.5rem] border border-slate-100 group-hover:bg-white group-hover:border-emerald-100 transition-all duration-300">
              <spec.icon size={16} className="text-slate-400 group-hover:text-emerald-500 mb-1.5 transition-colors" />
              <span className="text-[9px] font-bold text-slate-500 uppercase tracking-wider">{spec.label}</span>
            </div>
          ))}
        </div>

        {/* Price and Action */}
        <div className="flex items-center justify-between pt-6 border-t border-slate-100">
          <div>
            <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Mulai Dari</p>
            <p className="text-xl font-bold text-slate-900">
              <span className="text-sm font-medium text-slate-400 mr-1">Rp</span>
              {dailyRate.toLocaleString('id-ID')}
              <span className="text-xs font-medium text-slate-400 ml-1">/hari</span>
            </p>
          </div>
          
          <button 
            disabled={car.status === 'booked'}
            onClick={() => (car.status === 'available' || car.status === 'active') && router.push(`/cars/${car.id}`)}
            className={cn(
              "w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-300 shadow-lg",
              (car.status === 'available' || car.status === 'active')
              ? 'bg-slate-900 text-white hover:bg-emerald-600 hover:shadow-emerald-500/20 shadow-slate-200' 
              : 'bg-slate-100 text-slate-300 cursor-not-allowed shadow-none'
            )}
          >
            <ArrowRight size={24} className={cn((car.status === 'available' || car.status === 'active') ? "group-hover:translate-x-1 transition-transform" : "")} />
          </button>
        </div>
      </div>
    </motion.div>
  );
}
