"use client";

import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Save, X, Package, CheckCircle } from 'lucide-react';
import { toast } from 'sonner';
import api from '../lib/api';
import ImageUpload from '../components/ImageUpload';

interface PackageTier {
  id: number;
  category: string;
  tierName: string;
  tagline: string | null;
  badge: string | null;
  colorTheme: string | null;
  priceLabel: string | null;
  price: string | null;
  unit: string | null;
  duration: string | null;
  capacity: string | null;
  location: string | null;
  image: string | null;
  features: string[];
  excludes: string[];
  orderPriority: number;
  isActive: boolean;
}

export default function AdminPackageTiers() {
  const [tiers, setTiers] = useState<PackageTier[]>([]);
  const [loading, setLoading] = useState(true);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [formData, setFormData] = useState<Partial<PackageTier>>({
    category: 'outbound',
    tierName: '',
    tagline: '',
    badge: null,
    colorTheme: 'from-slate-700 to-slate-800',
    priceLabel: 'Mulai dari',
    price: '',
    unit: '',
    duration: '',
    capacity: '',
    location: '',
    image: null,
    features: [],
    excludes: [],
    orderPriority: 0,
    isActive: true
  });
  const [newFeature, setNewFeature] = useState('');
  const [newExclude, setNewExclude] = useState('');

  useEffect(() => {
    fetchTiers();
  }, []);

  const fetchTiers = async () => {
    try {
      const res = await api.get('/admin/package-tiers');
      setTiers(res.data);
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
        await api.put(`/admin/package-tiers/${editingId}`, formData);
        toast.success('Tier berhasil diupdate');
      } else {
        await api.post('/admin/package-tiers', formData);
        toast.success('Tier berhasil ditambahkan');
      }
      fetchTiers();
      resetForm();
    } catch (error) {
      toast.error('Gagal menyimpan data');
    }
  };

  const handleEdit = (tier: PackageTier) => {
    setEditingId(tier.id);
    setFormData(tier);
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Yakin ingin menghapus tier ini?')) return;
    try {
      await api.delete(`/admin/package-tiers/${id}`);
      toast.success('Tier berhasil dihapus');
      fetchTiers();
    } catch (error) {
      toast.error('Gagal menghapus data');
    }
  };

  const addFeature = () => {
    if (newFeature.trim()) {
      setFormData({ ...formData, features: [...(formData.features || []), newFeature.trim()] });
      setNewFeature('');
    }
  };

  const removeFeature = (index: number) => {
    setFormData({ ...formData, features: formData.features?.filter((_, i) => i !== index) });
  };

  const addExclude = () => {
    if (newExclude.trim()) {
      setFormData({ ...formData, excludes: [...(formData.excludes || []), newExclude.trim()] });
      setNewExclude('');
    }
  };

  const removeExclude = (index: number) => {
    setFormData({ ...formData, excludes: formData.excludes?.filter((_, i) => i !== index) });
  };

  const resetForm = () => {
    setEditingId(null);
    setFormData({ category: 'outbound', tierName: '', tagline: '', badge: null, colorTheme: 'from-slate-700 to-slate-800', priceLabel: 'Mulai dari', price: '', unit: '', duration: '', capacity: '', location: '', image: null, features: [], excludes: [], orderPriority: 0, isActive: true });
  };

  if (loading) {
    return <div className="flex items-center justify-center min-h-screen"><div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin" /></div>;
  }

  return (
    <div className="p-8 max-w-7xl mx-auto">
      <div className="mb-8">
        <h1 className="text-3xl font-black text-slate-900 mb-2">Tier Paket</h1>
        <p className="text-slate-500">Kelola tier paket outbound (Basic, Standard, Premium)</p>
      </div>

      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <h2 className="text-xl font-bold text-slate-900 mb-6">{editingId ? 'Edit Tier' : 'Tambah Tier Baru'}</h2>
        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
              <select value={formData.category} onChange={(e) => setFormData({ ...formData, category: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green">
                <option value="outbound">Outbound</option>
                <option value="tour">Tour</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Nama Tier *</label>
              <input type="text" value={formData.tierName} onChange={(e) => setFormData({ ...formData, tierName: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Basic / Standard / Premium" required />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Tagline</label>
              <input type="text" value={formData.tagline || ''} onChange={(e) => setFormData({ ...formData, tagline: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Paket Hemat Terbaik" />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Badge</label>
              <input type="text" value={formData.badge || ''} onChange={(e) => setFormData({ ...formData, badge: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="TERPOPULER / ALL-IN" />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Harga</label>
              <input type="text" value={formData.price || ''} onChange={(e) => setFormData({ ...formData, price: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Rp 250.000" />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Unit</label>
              <input type="text" value={formData.unit || ''} onChange={(e) => setFormData({ ...formData, unit: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="/ pax (min. 30 pax)" />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Durasi</label>
              <input type="text" value={formData.duration || ''} onChange={(e) => setFormData({ ...formData, duration: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Setengah Hari (4-5 Jam)" />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Kapasitas</label>
              <input type="text" value={formData.capacity || ''} onChange={(e) => setFormData({ ...formData, capacity: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="30 – 100 Pax" />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Lokasi</label>
              <input type="text" value={formData.location || ''} onChange={(e) => setFormData({ ...formData, location: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Area Jabodetabek & Sumut" />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Urutan</label>
              <input type="number" value={formData.orderPriority} onChange={(e) => setFormData({ ...formData, orderPriority: parseInt(e.target.value) })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" />
            </div>
          </div>

          {/* Image Upload */}
          <ImageUpload
            label="Gambar Tier (opsional - tampil di kartu paket)"
            value={formData.image || ''}
            onChange={(url) => setFormData({ ...formData, image: url })}
            aspectRatio="wide"
          />

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">Features (Yang Termasuk)</label>
            <div className="flex gap-2 mb-3">
              <input type="text" value={newFeature} onChange={(e) => setNewFeature(e.target.value)} onKeyPress={(e) => e.key === 'Enter' && (e.preventDefault(), addFeature())} className="flex-1 px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Tambah feature..." />
              <button type="button" onClick={addFeature} className="px-4 py-2 bg-toba-green text-white rounded-xl font-bold hover:bg-toba-green/90"><Plus size={18} /></button>
            </div>
            <div className="space-y-2">
              {formData.features?.map((feature, index) => (
                <div key={index} className="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-xl">
                  <CheckCircle size={16} className="text-toba-green" />
                  <span className="flex-1 text-sm">{feature}</span>
                  <button type="button" onClick={() => removeFeature(index)} className="text-red-500 hover:text-red-700"><X size={16} /></button>
                </div>
              ))}
            </div>
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">Excludes (Yang Tidak Termasuk)</label>
            <div className="flex gap-2 mb-3">
              <input type="text" value={newExclude} onChange={(e) => setNewExclude(e.target.value)} onKeyPress={(e) => e.key === 'Enter' && (e.preventDefault(), addExclude())} className="flex-1 px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Tambah exclude..." />
              <button type="button" onClick={addExclude} className="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300"><Plus size={18} /></button>
            </div>
            <div className="space-y-2">
              {formData.excludes?.map((exclude, index) => (
                <div key={index} className="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-xl">
                  <div className="w-4 h-4 rounded-full border-2 border-slate-300" />
                  <span className="flex-1 text-sm text-slate-500 line-through">{exclude}</span>
                  <button type="button" onClick={() => removeExclude(index)} className="text-red-500 hover:text-red-700"><X size={16} /></button>
                </div>
              ))}
            </div>
          </div>

          <div className="flex gap-3">
            <button type="submit" className="flex items-center gap-2 px-6 py-2 bg-toba-green text-white rounded-xl font-bold hover:bg-toba-green/90"><Save size={18} />{editingId ? 'Update' : 'Simpan'}</button>
            {editingId && <button type="button" onClick={resetForm} className="flex items-center gap-2 px-6 py-2 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300"><X size={18} />Batal</button>}
          </div>
        </form>
      </motion.div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {tiers.map((tier) => (
          <motion.div key={tier.id} initial={{ opacity: 0, scale: 0.95 }} animate={{ opacity: 1, scale: 1 }} className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div className={`bg-gradient-to-br ${tier.colorTheme} p-6 text-white relative`}>
              {tier.badge && <div className="absolute top-3 right-3 bg-white/20 px-3 py-1 rounded-full text-xs font-bold">{tier.badge}</div>}
              <h3 className="text-2xl font-black mb-1">{tier.tierName}</h3>
              <p className="text-sm text-white/80">{tier.tagline}</p>
              <div className="mt-4">
                <div className="text-3xl font-black">{tier.price}</div>
                <div className="text-xs text-white/70">{tier.unit}</div>
              </div>
            </div>
            <div className="p-6">
              <div className="space-y-2 mb-4 text-sm">
                <p><strong>Durasi:</strong> {tier.duration}</p>
                <p><strong>Kapasitas:</strong> {tier.capacity}</p>
                <p><strong>Lokasi:</strong> {tier.location}</p>
              </div>
              <div className="flex gap-2">
                <button onClick={() => handleEdit(tier)} className="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-bold hover:bg-blue-100"><Edit2 size={16} />Edit</button>
                <button onClick={() => handleDelete(tier.id)} className="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-100"><Trash2 size={16} />Hapus</button>
              </div>
            </div>
          </motion.div>
        ))}
      </div>
    </div>
  );
}
