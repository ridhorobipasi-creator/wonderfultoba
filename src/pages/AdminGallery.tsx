"use client";

import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Save, X, Image as ImageIcon, Filter } from 'lucide-react';
import { toast } from 'sonner';
import api from '../lib/api';
import ImageUpload from '../components/ImageUpload';

interface GalleryImage {
  id: number;
  imageUrl: string;
  caption: string | null;
  category: string | null;
  tags: string[] | null;
  eventDate: string | null;
  orderPriority: number;
  isActive: boolean;
}

export default function AdminGallery() {
  const [images, setImages] = useState<GalleryImage[]>([]);
  const [loading, setLoading] = useState(true);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [filterCategory, setFilterCategory] = useState<string>('all');
  const [formData, setFormData] = useState<Partial<GalleryImage>>({
    imageUrl: '',
    caption: '',
    category: 'outbound',
    tags: [],
    eventDate: null,
    orderPriority: 0,
    isActive: true
  });

  useEffect(() => {
    fetchImages();
  }, []);

  const fetchImages = async () => {
    try {
      const res = await api.get('/admin/gallery');
      setImages(res.data);
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
        await api.put(`/admin/gallery/${editingId}`, formData);
        toast.success('Gambar berhasil diupdate');
      } else {
        await api.post('/admin/gallery', formData);
        toast.success('Gambar berhasil ditambahkan');
      }
      fetchImages();
      resetForm();
    } catch (error) {
      toast.error('Gagal menyimpan data');
    }
  };

  const handleEdit = (image: GalleryImage) => {
    setEditingId(image.id);
    setFormData(image);
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Yakin ingin menghapus gambar ini?')) return;
    try {
      await api.delete(`/admin/gallery/${id}`);
      toast.success('Gambar berhasil dihapus');
      fetchImages();
    } catch (error) {
      toast.error('Gagal menghapus data');
    }
  };

  const resetForm = () => {
    setEditingId(null);
    setFormData({ imageUrl: '', caption: '', category: 'outbound', tags: [], eventDate: null, orderPriority: 0, isActive: true });
  };

  const filteredImages = filterCategory === 'all' ? images : images.filter(img => img.category === filterCategory);

  if (loading) {
    return <div className="flex items-center justify-center min-h-screen"><div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin" /></div>;
  }

  return (
    <div className="p-8 max-w-7xl mx-auto">
      <div className="mb-8">
        <h1 className="text-3xl font-black text-slate-900 mb-2">Galeri Foto</h1>
        <p className="text-slate-500">Kelola foto dokumentasi event dan kegiatan</p>
      </div>

      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <h2 className="text-xl font-bold text-slate-900 mb-6">{editingId ? 'Edit Gambar' : 'Tambah Gambar Baru'}</h2>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="md:col-span-2">
              <ImageUpload 
                label="Gambar Gallery *" 
                value={formData.imageUrl || ''} 
                onChange={(url) => setFormData({ ...formData, imageUrl: url })} 
                aspectRatio="square"
              />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Caption</label>
              <input type="text" value={formData.caption || ''} onChange={(e) => setFormData({ ...formData, caption: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Event Documentation" />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
              <select value={formData.category || 'outbound'} onChange={(e) => setFormData({ ...formData, category: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green">
                <option value="outbound">Outbound</option>
                <option value="tour">Tour</option>
                <option value="general">General</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Urutan</label>
              <input type="number" value={formData.orderPriority} onChange={(e) => setFormData({ ...formData, orderPriority: parseInt(e.target.value) })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" />
            </div>
            <div className="flex items-center">
              <label className="flex items-center cursor-pointer">
                <input type="checkbox" checked={formData.isActive} onChange={(e) => setFormData({ ...formData, isActive: e.target.checked })} className="w-5 h-5 text-toba-green rounded" />
                <span className="ml-2 text-sm font-bold text-slate-700">Aktif</span>
              </label>
            </div>
          </div>
          <div className="flex gap-3">
            <button type="submit" className="flex items-center gap-2 px-6 py-2 bg-toba-green text-white rounded-xl font-bold hover:bg-toba-green/90"><Save size={18} />{editingId ? 'Update' : 'Simpan'}</button>
            {editingId && <button type="button" onClick={resetForm} className="flex items-center gap-2 px-6 py-2 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300"><X size={18} />Batal</button>}
          </div>
        </form>
      </motion.div>

      <div className="mb-6 flex items-center gap-3">
        <Filter size={20} className="text-slate-400" />
        <button onClick={() => setFilterCategory('all')} className={`px-4 py-2 rounded-xl font-bold text-sm ${filterCategory === 'all' ? 'bg-toba-green text-white' : 'bg-slate-100 text-slate-600'}`}>Semua</button>
        <button onClick={() => setFilterCategory('outbound')} className={`px-4 py-2 rounded-xl font-bold text-sm ${filterCategory === 'outbound' ? 'bg-toba-green text-white' : 'bg-slate-100 text-slate-600'}`}>Outbound</button>
        <button onClick={() => setFilterCategory('tour')} className={`px-4 py-2 rounded-xl font-bold text-sm ${filterCategory === 'tour' ? 'bg-toba-green text-white' : 'bg-slate-100 text-slate-600'}`}>Tour</button>
        <button onClick={() => setFilterCategory('general')} className={`px-4 py-2 rounded-xl font-bold text-sm ${filterCategory === 'general' ? 'bg-toba-green text-white' : 'bg-slate-100 text-slate-600'}`}>General</button>
      </div>

      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        {filteredImages.map((image) => (
          <motion.div key={image.id} initial={{ opacity: 0, scale: 0.95 }} animate={{ opacity: 1, scale: 1 }} className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden group">
            <div className="aspect-square relative overflow-hidden bg-slate-100">
              <img src={image.imageUrl} alt={image.caption || ''} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
              {!image.isActive && <div className="absolute inset-0 bg-slate-900/70 flex items-center justify-center"><span className="text-white text-xs font-bold">Nonaktif</span></div>}
              <div className="absolute top-2 right-2 bg-slate-900/70 text-white px-2 py-1 rounded text-xs font-bold">{image.category}</div>
            </div>
            <div className="p-3">
              {image.caption && <p className="text-xs text-slate-600 mb-2 line-clamp-2">{image.caption}</p>}
              <div className="flex gap-2">
                <button onClick={() => handleEdit(image)} className="flex-1 p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 text-xs"><Edit2 size={12} className="mx-auto" /></button>
                <button onClick={() => handleDelete(image.id)} className="flex-1 p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 text-xs"><Trash2 size={12} className="mx-auto" /></button>
              </div>
            </div>
          </motion.div>
        ))}
      </div>
    </div>
  );
}
