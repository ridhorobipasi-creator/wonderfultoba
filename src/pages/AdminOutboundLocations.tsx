"use client";

import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Save, X, MapPin, Star } from 'lucide-react';
import { toast } from 'sonner';
import api from '../lib/api';
import ImageUpload from '../components/ImageUpload';

interface Location {
  id: number;
  name: string;
  image: string;
  region: string;
  isFeatured: boolean;
  orderPriority: number;
}

export default function AdminOutboundLocations() {
  const [locations, setLocations] = useState<Location[]>([]);
  const [loading, setLoading] = useState(true);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [formData, setFormData] = useState<Partial<Location>>({
    name: '',
    image: '',
    region: '',
    isFeatured: false,
    orderPriority: 0
  });

  useEffect(() => {
    fetchLocations();
  }, []);

  const fetchLocations = async () => {
    try {
      const res = await api.get('/admin/outbound/locations');
      setLocations(res.data);
    } catch (error) {
      toast.error('Gagal memuat data');
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (editingId) {
        await api.put(`/admin/outbound/locations/${editingId}`, formData);
        toast.success('Lokasi berhasil diupdate');
      } else {
        await api.post('/admin/outbound/locations', formData);
        toast.success('Lokasi berhasil ditambahkan');
      }
      fetchLocations();
      resetForm();
    } catch (error) {
      toast.error('Gagal menyimpan data');
    }
  };

  const handleEdit = (location: Location) => {
    setEditingId(location.id);
    setFormData(location);
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Yakin ingin menghapus lokasi ini?')) return;
    try {
      await api.delete(`/admin/outbound/locations/${id}`);
      toast.success('Lokasi berhasil dihapus');
      fetchLocations();
    } catch (error) {
      toast.error('Gagal menghapus data');
    }
  };

  const resetForm = () => {
    setEditingId(null);
    setFormData({ name: '', image: '', region: '', isFeatured: false, orderPriority: 0 });
  };

  if (loading) {
    return <div className="flex items-center justify-center min-h-screen"><div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin" /></div>;
  }

  return (
    <div className="p-8 max-w-7xl mx-auto">
      <div className="mb-8">
        <h1 className="text-3xl font-black text-slate-900 mb-2">Lokasi Venue Outbound</h1>
        <p className="text-slate-500">Kelola lokasi venue untuk kegiatan outbound</p>
      </div>

      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <h2 className="text-xl font-bold text-slate-900 mb-6">{editingId ? 'Edit Lokasi' : 'Tambah Lokasi Baru'}</h2>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Nama Lokasi *</label>
              <input type="text" value={formData.name} onChange={(e) => setFormData({ ...formData, name: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Marianna Resort, Samosir" required />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Region</label>
              <input type="text" value={formData.region} onChange={(e) => setFormData({ ...formData, region: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Samosir" />
            </div>
            <div className="md:col-span-2">
              <ImageUpload 
                label="Gambar Lokasi *" 
                value={formData.image || ''} 
                onChange={(url) => setFormData({ ...formData, image: url })} 
                aspectRatio="video"
              />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Urutan</label>
              <input type="number" value={formData.orderPriority} onChange={(e) => setFormData({ ...formData, orderPriority: parseInt(e.target.value) })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" />
            </div>
            <div className="flex items-center">
              <label className="flex items-center cursor-pointer">
                <input type="checkbox" checked={formData.isFeatured} onChange={(e) => setFormData({ ...formData, isFeatured: e.target.checked })} className="w-5 h-5 text-toba-green rounded" />
                <span className="ml-2 text-sm font-bold text-slate-700">Featured</span>
              </label>
            </div>
          </div>
          <div className="flex gap-3">
            <button type="submit" className="flex items-center gap-2 px-6 py-2 bg-toba-green text-white rounded-xl font-bold hover:bg-toba-green/90"><Save size={18} />{editingId ? 'Update' : 'Simpan'}</button>
            {editingId && <button type="button" onClick={resetForm} className="flex items-center gap-2 px-6 py-2 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300"><X size={18} />Batal</button>}
          </div>
        </form>
      </motion.div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {locations.map((location) => (
          <motion.div key={location.id} initial={{ opacity: 0, scale: 0.95 }} animate={{ opacity: 1, scale: 1 }} className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden group">
            <div className="relative h-48">
              <img src={location.image} alt={location.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
              {location.isFeatured && (
                <div className="absolute top-3 right-3 bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                  <Star size={12} fill="white" />Featured
                </div>
              )}
            </div>
            <div className="p-4">
              <div className="flex items-start gap-2 mb-2">
                <MapPin size={16} className="text-toba-green mt-1 shrink-0" />
                <div>
                  <h3 className="font-bold text-slate-900 text-sm leading-tight">{location.name}</h3>
                  {location.region && <p className="text-xs text-slate-500 mt-1">{location.region}</p>}
                </div>
              </div>
              <p className="text-xs text-slate-400 mb-3">Urutan: {location.orderPriority}</p>
              <div className="flex gap-2">
                <button onClick={() => handleEdit(location)} className="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-bold hover:bg-blue-100"><Edit2 size={14} />Edit</button>
                <button onClick={() => handleDelete(location.id)} className="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-red-50 text-red-600 rounded-xl text-xs font-bold hover:bg-red-100"><Trash2 size={14} />Hapus</button>
              </div>
            </div>
          </motion.div>
        ))}
      </div>
    </div>
  );
}
