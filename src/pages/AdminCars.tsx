"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Car } from '../types';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Search, Car as CarIcon, Filter, MoreHorizontal, Calendar, DollarSign, Tag, Settings, Users } from 'lucide-react';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import { useRouter } from 'next/navigation';

export default function AdminCars() {
  const router = useRouter();
  const [cars, setCars] = useState<Car[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    setLoading(true);
    try {
      const res = await api.get('/cars');
      setCars(res.data);
    } catch (error) {
      console.error('Error fetching cars:', error);
      toast.error('Gagal mengambil data armada');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id: string | number) => {
    if (window.confirm('Apakah Anda yakin ingin menghapus kendaraan ini?')) {
      try {
        await api.delete(`/cars/${id}`);
        toast.success('Armada dihapus');
        fetchData();
      } catch {
        toast.error('Gagal menghapus armada');
      }
    }
  };

  const filteredCars = cars.filter(car => 
    car.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    car.type.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="space-y-10 pb-12 animate-in fade-in duration-500">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h2 className="text-4xl font-bold text-slate-900 tracking-tight mb-2">Manajemen Armada</h2>
          <p className="text-slate-500 font-medium">Pastikan setiap kendaraan dalam kondisi prima untuk perjalanan tamu.</p>
        </div>
        <button
          onClick={() => router.push('/admin/add-cars')}
          className="bg-toba-green text-white px-8 py-4 rounded-2xl font-bold flex items-center space-x-2 hover:bg-toba-green/90 transition-all shadow-xl shadow-blue-100 group"
        >
          <Plus size={20} className="transition-transform group-hover:rotate-90" />
          <span>Tambah Armada Baru</span>
        </button>
      </div>

      {/* Stats Quick View */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {[
          { label: 'Total Armada', value: cars.length, icon: CarIcon, color: 'text-blue-600', bg: 'bg-blue-50' },
          { label: 'Tersedia', value: cars.filter(c => c.status === 'available').length, icon: Tag, color: 'text-emerald-500', bg: 'bg-emerald-50' },
          { label: 'Terbooking', value: cars.filter(c => c.status !== 'available').length, icon: Calendar, color: 'text-amber-500', bg: 'bg-amber-50' },
          { label: 'Pemeliharaan', value: '0', icon: Settings, color: 'text-rose-500', bg: 'bg-rose-50' },
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
            placeholder="Cari kendaraan atau tipe..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green font-medium transition-all"
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
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kendaraan</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kapasitas</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Harga / Hari</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Status</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
              </tr>
            </thead>
          <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={5} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-12 h-12 border-4 border-blue-100 border-t-toba-green rounded-full animate-spin mb-4"></div>
                      <p className="text-slate-400 font-medium"> Sinkronisasi armada...</p>
                    </div>
                  </td>
                </tr>
              ) : filteredCars.length > 0 ? filteredCars.map((car, index) => (
                <motion.tr 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.05 }}
                  key={car.id} 
                  className="hover:bg-slate-50/50 transition-colors group"
                >
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-5">
                      <div className="w-16 h-16 rounded-2xl overflow-hidden bg-slate-50 shrink-0 shadow-lg shadow-slate-200 group-hover:scale-105 transition-transform duration-500 flex items-center justify-center p-2 border border-slate-100">
                        <img src={car.images?.[0] || '/assets/images/2023/10/001-1.jpg'} alt="" className="w-full h-full object-contain" />
                      </div>
                      <div>
                        <p className="font-bold text-slate-900 text-lg group-hover:text-toba-green transition-colors line-clamp-1">{car.name}</p>
                        <div className="flex items-center space-x-2 mt-1">
                          <span className="px-2 py-0.5 bg-slate-100 text-[10px] font-bold text-slate-500 rounded uppercase tracking-wider">{car.type}</span>
                          <span className="text-[10px] text-slate-300 font-bold tracking-widest uppercase">ID: #{car.id}</span>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-2 text-slate-600">
                      <Users size={16} className="text-slate-400" />
                      <span className="text-sm font-bold">{car.capacity || 5} Kursi</span>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-1 text-slate-900">
                      <span className="text-sm font-bold text-toba-green">Rp</span>
                      <p className="font-black text-xl tracking-tight">{Number(car.price).toLocaleString('id-ID')}</p>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <span className={cn(
                      "px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.15em] border",
                      (car.status === 'available' || car.status === 'active') ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'
                    )}>
                      {car.status}
                    </span>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button 
                        onClick={() => router.push(`/admin/edit-car/${car.id}`)} 
                        className="p-3 text-slate-400 hover:text-toba-green hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100"
                        title="Edit Unit"
                      >
                        <Edit2 size={18} />
                      </button>
                      <button 
                        onClick={() => handleDelete(car.id)} 
                        className="p-3 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100"
                        title="Hapus Unit"
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
                        <CarIcon size={32} />
                      </div>
                      <p className="text-slate-400 font-medium">Belum ada armada mobil.</p>
                      <button onClick={() => router.push('/admin/add-cars')} className="mt-4 text-toba-green font-bold hover:underline">
                        Tambah armada pertama
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
