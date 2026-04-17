"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { City } from '../types';
import { motion, AnimatePresence } from 'framer-motion';
import { Plus, Edit2, Trash2, Search, MapPin, X, Save, Map as MapIcon, Globe, Navigation } from 'lucide-react';
import { useForm } from 'react-hook-form';
import { cn } from '../utils/cn';
import { toast } from 'sonner';

interface AdminCityForm {
  name: string;
  description?: string;
}

export default function AdminCities() {
  const [cities, setCities] = useState<City[]>([]);
  const [loading, setLoading] = useState(true);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingCity, setEditingCity] = useState<City | null>(null);
  const [searchQuery, setSearchQuery] = useState('');

  const { register, handleSubmit, reset, setValue } = useForm<AdminCityForm>();

  useEffect(() => {
    fetchCities();
  }, []);

  const fetchCities = async () => {
    setLoading(true);
    try {
      const res = await api.get('/cities');
      setCities(res.data);
    } catch (error) {
      console.error('Error fetching cities:', error);
      toast.error('Gagal mengambil data wilayah');
    } finally {
      setLoading(false);
    }
  };

  const onSubmit = async (data: AdminCityForm) => {
    try {
      const payload = {
        name: data.name,
        description: data.description || '',
        province_id: 1, // Default focus: North Sumatra
      };

      if (editingCity) {
        await api.put(`/cities/${editingCity.id}`, payload);
        toast.success('Wilayah diperbarui');
      } else {
        await api.post('/cities', payload);
        toast.success('Wilayah baru berhasil ditambahkan');
      }
      setIsModalOpen(false);
      setEditingCity(null);
      reset();
      fetchCities();
    } catch (error) {
      console.error('Error saving city:', error);
      toast.error('Gagal menyimpan wilayah');
    }
  };

  const handleEdit = (city: City) => {
    setEditingCity(city);
    setValue('name', city.name);
    setValue('description', city.description || '');
    setIsModalOpen(true);
  };

  const handleDelete = async (id: string | number) => {
    if (window.confirm('Hapus wilayah ini? Semua paket yang terkait mungkin akan terpengaruh.')) {
      try {
        await api.delete(`/cities/${id}`);
        toast.success('Wilayah dihapus');
        fetchCities();
      } catch {
        toast.error('Gagal menghapus wilayah. Pastikan tidak ada paket yang bergantung pada wilayah ini.');
      }
    }
  };

  const filteredCities = cities.filter(city => 
    city.name.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="space-y-10 pb-12 animate-in fade-in duration-500">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h2 className="text-4xl font-bold text-slate-900 tracking-tight mb-2">Manajemen Wilayah</h2>
          <p className="text-slate-500 font-medium italic">&ldquo;Petakan keindahan Toba melalui manajemen destinasi yang terorganisir.&rdquo;</p>
        </div>
        <button
          onClick={() => {
            setEditingCity(null);
            reset();
            setIsModalOpen(true);
          }}
          className="bg-obaja-blue text-white px-8 py-4 rounded-2xl font-bold flex items-center space-x-2 hover:bg-obaja-blue/90 transition-all shadow-xl shadow-blue-100 group"
        >
          <Plus size={20} className="transition-transform group-hover:rotate-90" />
          <span>Wilayah Baru</span>
        </button>
      </div>

      {/* Stats Quick View */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {[
          { label: 'Total Wilayah', value: cities.length, icon: MapIcon, color: 'text-blue-600', bg: 'bg-blue-50' },
          { label: 'Provinsi Fokus', value: '1', icon: Globe, color: 'text-emerald-500', bg: 'bg-emerald-50' },
          { label: 'Titik Destinasi', value: 'Active', icon: Navigation, color: 'text-amber-500', bg: 'bg-amber-50' },
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

      {/* Search */}
      <div className="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
        <div className="relative w-full md:w-96">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input
            type="text"
            placeholder="Cari wilayah atau kota..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-obaja-blue font-medium transition-all"
          />
        </div>
      </div>

      {/* Table Container */}
      <div className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/50">
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Wilayah & Kategori</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={2} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-12 h-12 border-4 border-blue-100 border-t-obaja-blue rounded-full animate-spin mb-4"></div>
                      <p className="text-slate-400 font-medium">Memuat data wilayah...</p>
                    </div>
                  </td>
                </tr>
              ) : filteredCities.length > 0 ? filteredCities.map((city, index) => (
                <motion.tr 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.05 }}
                  key={city.id} 
                  className="hover:bg-slate-50/50 transition-colors group"
                >
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-5">
                      <div className="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 group-hover:text-obaja-blue group-hover:bg-blue-50 transition-all duration-300 border border-slate-100 shadow-sm shadow-slate-200/50">
                        <MapPin size={24} />
                      </div>
                      <div>
                        <p className="font-bold text-slate-900 text-lg group-hover:text-obaja-blue transition-colors line-clamp-1">{city.name}</p>
                        <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Sumatera Utara, Indonesia</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button 
                        onClick={() => handleEdit(city)} 
                        className="p-3 text-slate-400 hover:text-obaja-blue hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100"
                        title="Edit Wilayah"
                      >
                        <Edit2 size={18} />
                      </button>
                      <button 
                        onClick={() => handleDelete(city.id)} 
                        className="p-3 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100"
                        title="Hapus Wilayah"
                      >
                        <Trash2 size={18} />
                      </button>
                    </div>
                  </td>
                </motion.tr>
              )) : (
                <tr>
                  <td colSpan={2} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-200">
                        <MapPin size={32} />
                      </div>
                      <p className="text-slate-400 font-medium">Belum ada wilayah yang terdaftar.</p>
                      <button onClick={() => setIsModalOpen(true)} className="mt-4 text-obaja-blue font-bold hover:underline">
                        Tambah wilayah pertama
                      </button>
                    </div>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal */}
      <AnimatePresence>
        {isModalOpen && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
            <motion.div
              initial={{ opacity: 0, scale: 0.9, y: 20 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.9, y: 20 }}
              className="bg-white rounded-[3rem] w-full max-w-md overflow-hidden shadow-2xl border border-white/20"
            >
              <div className="p-10 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <div>
                  <h3 className="text-3xl font-bold text-slate-900 tracking-tight">
                    {editingCity ? 'Edit Wilayah' : 'Wilayah Baru'}
                  </h3>
                  <p className="text-slate-500 font-medium mt-1">Masukkan informasi wilayah secara akurat.</p>
                </div>
                <button onClick={() => setIsModalOpen(false)} className="p-3 hover:bg-white rounded-2xl transition-all border border-transparent hover:border-slate-100 text-slate-400 hover:text-slate-900">
                  <X size={24} />
                </button>
              </div>
              
              <form onSubmit={handleSubmit(onSubmit)} className="p-10 space-y-8">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Wilayah / Kota</label>
                  <div className="relative">
                    <MapPin className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      {...register('name', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 transition-all shadow-inner"
                      placeholder="Contoh: Pulau Samosir"
                    />
                  </div>
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Keterangan Singkat</label>
                  <textarea
                    {...register('description')}
                    rows={3}
                    className="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium text-slate-900 transition-all resize-none shadow-inner"
                    placeholder="Contoh: Wilayah dikelilingi perairan jernih..."
                  />
                </div>

                <div className="flex justify-end space-x-4 pt-6">
                  <button
                    type="button"
                    onClick={() => setIsModalOpen(false)}
                    className="px-8 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all border border-transparent border-slate-100"
                  >
                    Batal
                  </button>
                  <button
                    type="submit"
                    className="bg-obaja-blue text-white px-12 py-4 rounded-2xl font-bold hover:bg-obaja-blue/90 transition-all shadow-xl shadow-blue-100 flex items-center space-x-2"
                  >
                    <Save size={20} />
                    <span>{editingCity ? 'Simpan' : 'Buat Wilayah'}</span>
                  </button>
                </div>
              </form>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}
