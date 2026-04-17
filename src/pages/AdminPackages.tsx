"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Package, City } from '../types';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Search, Package as PackageIcon, Filter, MoreHorizontal, MapPin, Calendar, DollarSign, Star, Zap } from 'lucide-react';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import { useRouter } from 'next/navigation';

interface AdminPackage extends Package {
  city_id?: number;
  is_featured?: boolean;
  price_display?: string;
}

export default function AdminPackages({ category }: { category?: 'tour' | 'outbound' }) {
  const router = useRouter();
  const [packages, setPackages] = useState<AdminPackage[]>([]);
  const [cities, setCities] = useState<City[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    setLoading(true);
    try {
      const [pkgRes, cityRes] = await Promise.all([
        api.get<AdminPackage[]>('/packages', { params: { category } }),
        api.get<City[]>('/cities')
      ]);
      setPackages(pkgRes.data);
      setCities(cityRes.data);
    } catch (error) {
      console.error('Error fetching packages:', error);
      toast.error('Gagal mengambil data');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id: string | number) => {
    if (window.confirm('Apakah Anda yakin ingin menghapus paket ini?')) {
      try {
        await api.delete(`/packages/${id}`);
        toast.success('Paket dihapus');
        fetchData();
      } catch {
        toast.error('Gagal menghapus paket');
      }
    }
  };

  const filteredPackages = packages.filter(pkg => 
    pkg.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    cities.find(c => c.id === pkg.city_id || c.id === pkg.cityId)?.name.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="space-y-10 pb-12 animate-in fade-in duration-500">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h2 className="text-4xl font-bold text-slate-900 tracking-tight mb-2">Manajemen Paket Wisata</h2>
          <p className="text-slate-500 font-medium italic">&ldquo;Berikan pengalaman tak terlupakan bagi wisatawan di setiap detiknya.&rdquo;</p>
        </div>
        <button
          onClick={() => router.push('/admin/create-package')}
          className="bg-obaja-blue text-white px-8 py-4 rounded-2xl font-bold flex items-center space-x-2 hover:bg-obaja-blue/90 transition-all shadow-xl shadow-blue-100 group"
        >
          <Plus size={20} className="transition-transform group-hover:rotate-90" />
          <span>Tambah Paket Baru</span>
        </button>
      </div>

      {/* Stats Quick View */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {[
          { label: 'Total Paket', value: packages.length, icon: PackageIcon, color: 'text-blue-600', bg: 'bg-blue-50' },
          { label: 'Paket Populer', value: packages.filter(p => p.is_featured).length, icon: Star, color: 'text-amber-500', bg: 'bg-amber-50' },
          { label: 'Kota Destinasi', value: cities.length, icon: MapPin, color: 'text-emerald-500', bg: 'bg-emerald-50' },
          { label: 'Update Masif', value: 'Today', icon: Zap, color: 'text-purple-500', bg: 'bg-purple-50' },
        ].map((stat, i) => (
          <div key={i} className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center space-x-4">
            <div className={cn("p-4 rounded-2xl", stat.bg)}>
              <stat.icon size={24} className={stat.color} />
            </div>
            <div>
              <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{stat.label}</p>
              <p className="text-2xl font-black text-slate-900">{stat.value}</p>
            </div>
          </div>
        ))}
      </div>

      {/* Filters & Search */}
      <div className="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div className="relative w-full md:w-96">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input
            type="text"
            placeholder="Cari paket atau wilayah..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all"
          />
        </div>
        <div className="flex items-center space-x-3 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center space-x-2 px-6 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-100 transition-all border border-slate-100">
            <Filter size={18} />
            <span>Filter</span>
          </button>
          <button className="p-3 bg-slate-50 text-slate-400 hover:text-slate-600 rounded-xl border border-slate-100 transition-all">
            <MoreHorizontal size={20} />
          </button> 
        </div>
      </div>

      {/* Table Container */}
      <div className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/50">
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Informasi Paket</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Wilayah</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Harga</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Status</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={5} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-12 h-12 border-4 border-blue-100 border-t-obaja-blue rounded-full animate-spin mb-4"></div>
                      <p className="text-slate-400 font-medium">Sinkronisasi data...</p>
                    </div>
                  </td>
                </tr>
              ) : filteredPackages.length > 0 ? filteredPackages.map((pkg, index) => (
                <motion.tr 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.05 }}
                  key={pkg.id} 
                  className="hover:bg-slate-50/50 transition-colors group"
                >
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-5">
                      <div className="w-20 h-20 rounded-2xl overflow-hidden bg-slate-50 shrink-0 shadow-lg shadow-slate-200 group-hover:scale-105 transition-transform duration-500 relative">
                        <img src={pkg.images?.[0] || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=400'} alt="" className="w-full h-full object-cover" />
                        {pkg.is_featured && (
                          <div className="absolute top-1 right-1 bg-amber-400 text-white p-1 rounded-lg">
                            <Star size={10} fill="currentColor" />
                          </div>
                        )}
                      </div>
                      <div>
                        <p className="font-bold text-slate-900 text-lg group-hover:text-obaja-blue transition-colors line-clamp-1">{pkg.name}</p>
                        <div className="flex items-center space-x-3 mt-1 text-slate-400 font-bold text-[10px] uppercase tracking-widest">
                          <span className="flex items-center gap-1.5"><Calendar size={12} className="text-blue-400" /> {pkg.duration}</span>
                          <span className="w-1 h-1 bg-slate-200 rounded-full" />
                          <span className="text-obaja-blue">Samosir</span>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-2 text-slate-600">
                      <div className="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <MapPin size={14} className="text-emerald-500" />
                      </div>
                      <span className="text-sm font-bold">
                        {cities.find(c => c.id === pkg.city_id || c.id === pkg.cityId)?.name || 'Danau Toba'}
                      </span>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex flex-col">
                      <div className="flex items-center space-x-1">
                        <span className="text-xs font-bold text-slate-400">IDR</span>
                        <p className="font-black text-xl text-slate-900 tracking-tight">{Number(pkg.price).toLocaleString('id-ID')}</p>
                      </div>
                      <p className="text-[10px] font-bold text-slate-400 mt-0.5 whitespace-nowrap">{pkg.price_display || 'PER ORANG'}</p>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <span className={cn(
                      "px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.15em] border",
                      pkg.status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100'
                    )}>
                      {pkg.status}
                    </span>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button 
                        onClick={() => router.push(`/admin/edit-package/${pkg.id}`)} 
                        className="p-3 text-slate-400 hover:text-obaja-blue hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100"
                        title="Detail Edit"
                      >
                        <Edit2 size={18} />
                      </button>
                      <button 
                        onClick={() => handleDelete(pkg.id)} 
                        className="p-3 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100"
                        title="Hapus"
                      >
                        <Trash2 size={18} />
                      </button>
                    </div>
                  </td>
                </motion.tr>
              )) : (
                <tr>
                  <td colSpan={5} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-200">
                        <PackageIcon size={32} />
                      </div>
                      <p className="text-slate-400 font-medium">Belum ada paket wisata.</p>
                      <button onClick={() => router.push('/admin/create-package')} className="mt-4 text-obaja-blue font-bold hover:underline">
                        Buat paket pertama
                      </button>
                    </div>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
