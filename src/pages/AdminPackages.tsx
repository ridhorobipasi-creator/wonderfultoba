"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Package, City } from '../types';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Search, Package as PackageIcon, Filter, MoreHorizontal, MapPin, Calendar, DollarSign, Star, Zap, RefreshCcw } from 'lucide-react';
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
  const [editingPriceId, setEditingPriceId] = useState<number | string | null>(null);
  const [editPriceValue, setEditPriceValue] = useState<string>('');
  const [selectedIds, setSelectedIds] = useState<(number | string)[]>([]);

  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchData();
    
    // Auto refresh every 30 seconds
    const intervalId = setInterval(() => {
      fetchData(true);
    }, 30000);

    return () => clearInterval(intervalId);
  }, [category]);

  const fetchData = async (silent = false) => {
    if (!silent) setLoading(true);
    if (silent) setRefreshing(true);
    try {
      const [pkgRes, cityRes] = await Promise.all([
        api.get<AdminPackage[]>('/packages', { params: { category } }),
        api.get<City[]>('/cities')
      ]);
      setPackages(pkgRes.data);
      setCities(cityRes.data);
    } catch (error) {
      console.error('Error fetching packages:', error);
      if (!silent) toast.error('Gagal mengambil data');
    } finally {
      if (!silent) setLoading(false);
      setRefreshing(false);
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

  const handleBulkDelete = async () => {
    if (!selectedIds.length) return;
    if (window.confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} paket terpilih secara massal?`)) {
      try {
        await Promise.all(selectedIds.map(id => api.delete(`/packages/${id}`)));
        toast.success(`Berhasil menghapus ${selectedIds.length} paket`);
        setSelectedIds([]);
        fetchData();
      } catch {
        toast.error('Gagal menghapus beberapa paket. Mungkin sedang digunakan.');
        fetchData();
      }
    }
  };

  const handleSelectAll = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.checked) {
      setSelectedIds(filteredPackages.map(p => p.id));
    } else {
      setSelectedIds([]);
    }
  };

  const toggleSelect = (id: string | number) => {
    setSelectedIds(prev => prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]);
  };

  const savePrice = async (pkg: AdminPackage) => {
    try {
      await api.put(`/packages/${pkg.id}`, { ...pkg, price: Number(editPriceValue) });
      toast.success('Harga berhasil diperbarui');
      setPackages(packages.map(p => p.id === pkg.id ? { ...p, price: Number(editPriceValue) } : p));
    } catch {
      toast.error('Gagal memperbarui harga');
    }
    setEditingPriceId(null);
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
          <div className="flex items-center gap-3">
            <h1 className="text-3xl font-black text-slate-900 tracking-tight">Daftar Paket</h1>
            <button 
              onClick={() => fetchData(true)} 
              disabled={refreshing}
              className="p-2 text-slate-400 hover:text-toba-green transition-all disabled:opacity-50"
              title="Refresh data"
            >
              <RefreshCcw className={cn("w-5 h-5", refreshing && "animate-spin")} /> 
            </button>
          </div>
          <p className="text-slate-400 font-medium mt-1">Kelola paket wisata dan outbound Anda secara real-time.</p>
        </div>
        <button
          onClick={() => router.push('/admin/create-package')}
          className="bg-toba-green text-white px-8 py-4 rounded-2xl font-black text-sm shadow-lg shadow-toba-green/20 flex items-center gap-3 hover:bg-toba-green/90 transition-all group"
        >
          <Plus size={20} className="transition-transform group-hover:rotate-90" />
          <span>Tambah Paket Baru</span>
        </button>
      </div>

      {/* Stats Quick View */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {[
          { label: 'Total Paket', value: packages.length, icon: PackageIcon, color: 'text-toba-green', bg: 'bg-emerald-50' },
          { label: 'Paket Populer', value: packages.filter(p => p.is_featured).length, icon: Star, color: 'text-amber-500', bg: 'bg-amber-50' },
          { label: 'Kota Destinasi', value: cities.length, icon: MapPin, color: 'text-blue-500', bg: 'bg-blue-50' },
          { label: 'Update Masif', value: 'Today', icon: Zap, color: 'text-purple-500', bg: 'bg-purple-50' },
        ].map((stat, i) => (
          <div key={i} className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center space-x-4">
            <div className={cn("p-4 rounded-2xl", stat.bg)}>
              <stat.icon size={24} className={stat.color} />
            </div>
            <div>
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">{stat.label}</p>
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
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 placeholder:font-medium transition-all"
          />
        </div>
        <div className="flex items-center space-x-3 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center space-x-2 px-6 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-100 transition-all border border-slate-100 uppercase tracking-widest">
            <Filter size={18} />
            <span className="text-[10px] font-black">Filter</span>
          </button>
          <button className="p-3 bg-slate-50 text-slate-400 hover:text-slate-600 rounded-xl border border-slate-100 transition-all">
            <MoreHorizontal size={20} />
          </button> 
        </div>
      </div>

      {/* Table Container */}
      <div className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        {selectedIds.length > 0 && (
          <div className="bg-emerald-50/60 border-b border-emerald-100 px-8 py-4 flex items-center justify-between animate-in slide-in-from-top-2">
            <span className="text-sm font-bold text-emerald-800">{selectedIds.length} item terpilih</span>
            <button 
              onClick={handleBulkDelete}
              className="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm shadow-rose-200 flex items-center gap-2"
            >
              <Trash2 size={14} /> Hapus Terpilih
            </button>
          </div>
        )}
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/50">
                <th className="px-6 py-5 w-12 text-center">
                  <input 
                    type="checkbox"
                    className="w-4 h-4 rounded text-toba-green focus:ring-toba-green border-slate-300"
                    onChange={handleSelectAll}
                    checked={filteredPackages.length > 0 && selectedIds.length === filteredPackages.length}
                  />
                </th>
                <th className="px-2 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Informasi Paket</th>
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Kategori</th>
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Harga</th>
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                <th className="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={6} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-12 h-12 border-4 border-emerald-100 border-t-toba-green rounded-full animate-spin mb-4"></div>
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
                  className={cn(
                    "hover:bg-slate-50/30 transition-colors group",
                    selectedIds.includes(pkg.id) ? "bg-slate-50/50" : ""
                  )}
                >
                  <td className="px-6 py-6 text-center">
                    <input 
                      type="checkbox"
                      className="w-4 h-4 rounded text-toba-green focus:ring-toba-green border-slate-300"
                      checked={selectedIds.includes(pkg.id)}
                      onChange={() => toggleSelect(pkg.id)}
                    />
                  </td>
                  <td className="px-2 py-6">
                    <div className="flex items-center space-x-5">
                      <div className="w-16 h-12 rounded-xl overflow-hidden bg-slate-50 shrink-0 shadow-sm relative group-hover:scale-105 transition-transform duration-500">
                        <img src={pkg.images?.[0] || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=400'} alt="" className="w-full h-full object-cover" />
                        {pkg.is_featured && (
                          <div className="absolute top-0.5 right-0.5 bg-amber-400 text-white p-0.5 rounded-md shadow-sm">
                            <Star size={8} fill="currentColor" />
                          </div>
                        )}
                      </div>
                      <div>
                        <p className="font-black text-slate-900 group-hover:text-toba-green transition-colors leading-none mb-1">{pkg.name}</p>
                        <p className="text-[10px] text-slate-400 font-black uppercase tracking-widest leading-none">{pkg.duration}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <span className={cn(
                      "px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg border",
                      pkg.category === 'outbound' 
                        ? "bg-indigo-50 text-indigo-600 border-indigo-100" 
                        : "bg-blue-50 text-blue-600 border-blue-100"
                    )}>
                      {pkg.category || 'Tour'}
                    </span>
                  </td>
                  <td className="px-8 py-6">
                    {editingPriceId === pkg.id ? (
                      <div className="flex flex-col gap-2">
                        <input
                          type="number"
                          value={editPriceValue}
                          onChange={(e) => setEditPriceValue(e.target.value)}
                          className="w-28 px-2 py-1 text-sm font-bold bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-toba-green"
                          autoFocus
                          onKeyDown={(e) => {
                            if (e.key === 'Enter') savePrice(pkg);
                            if (e.key === 'Escape') setEditingPriceId(null);
                          }}
                        />
                        <div className="flex gap-1">
                          <button onClick={() => savePrice(pkg)} className="text-[9px] px-2 py-1 bg-toba-green text-white rounded font-bold">Simpan</button>
                          <button onClick={() => setEditingPriceId(null)} className="text-[9px] px-2 py-1 bg-slate-200 text-slate-600 rounded font-bold">Batal</button>
                        </div>
                      </div>
                    ) : (
                      <div 
                        className="flex flex-col cursor-pointer hover:bg-slate-50 p-2 -ml-2 rounded-lg transition-colors group relative"
                        onClick={() => {
                          setEditingPriceId(pkg.id);
                          setEditPriceValue(pkg.price.toString());
                        }}
                        title="Klik untuk ubah harga (Inline Edit)"
                      >
                        <p className="font-bold text-slate-700 group-hover:text-toba-green flex items-center gap-1.5">
                          Rp {(Number(pkg.price)/1000).toLocaleString('id-ID')}k 
                          <Edit2 size={12} className="opacity-0 group-hover:opacity-100 transition-opacity" />
                        </p>
                        <p className="text-[9px] font-black text-slate-400 uppercase tracking-tighter">{pkg.price_display || 'PER ORANG'}</p>
                      </div>
                    )}
                  </td>
                  <td className="px-8 py-6">
                    <span className={cn(
                      "px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border",
                      pkg.status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100'
                    )}>
                      Aktif
                    </span>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end space-x-2">
                      <button 
                        onClick={() => router.push(`/admin/edit-package/${pkg.id}`)} 
                        className="p-2 text-slate-400 hover:text-toba-green transition-all"
                        title="Edit"
                      >
                        <Edit2 size={16} />
                      </button>
                      <button 
                        onClick={() => handleDelete(pkg.id)} 
                        className="p-2 text-slate-400 hover:text-rose-500 transition-all"
                        title="Hapus"
                      >
                        <Trash2 size={16} />
                      </button>
                    </div>
                  </td>
                </motion.tr>
              )) : (
                <tr>
                  <td colSpan={6} className="px-8 py-20 text-center">
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
