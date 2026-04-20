"use client";

import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Save, X, Building2, ExternalLink } from 'lucide-react';
import { toast } from 'sonner';
import api from '../lib/api';
import ImageUpload from '../components/ImageUpload';

interface Client {
  id: number;
  name: string;
  logo: string;
  websiteUrl: string | null;
  orderPriority: number;
  isActive: boolean;
}

export default function AdminClients() {
  const [clients, setClients] = useState<Client[]>([]);
  const [loading, setLoading] = useState(true);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [formData, setFormData] = useState<Partial<Client>>({
    name: '',
    logo: '',
    websiteUrl: '',
    orderPriority: 0,
    isActive: true
  });

  useEffect(() => {
    fetchClients();
  }, []);

  const fetchClients = async () => {
    try {
      const res = await api.get('/admin/clients');
      setClients(res.data);
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
        await api.put(`/admin/clients/${editingId}`, formData);
        toast.success('Klien berhasil diupdate');
      } else {
        await api.post('/admin/clients', formData);
        toast.success('Klien berhasil ditambahkan');
      }
      fetchClients();
      resetForm();
    } catch (error) {
      toast.error('Gagal menyimpan data');
    }
  };

  const handleEdit = (client: Client) => {
    setEditingId(client.id);
    setFormData(client);
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Yakin ingin menghapus klien ini?')) return;
    try {
      await api.delete(`/admin/clients/${id}`);
      toast.success('Klien berhasil dihapus');
      fetchClients();
    } catch (error) {
      toast.error('Gagal menghapus data');
    }
  };

  const resetForm = () => {
    setEditingId(null);
    setFormData({ name: '', logo: '', websiteUrl: '', orderPriority: 0, isActive: true });
  };

  if (loading) {
    return <div className="flex items-center justify-center min-h-screen"><div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin" /></div>;
  }

  return (
    <div className="p-8 max-w-7xl mx-auto">
      <div className="mb-8">
        <h1 className="text-3xl font-black text-slate-900 mb-2">Logo Klien</h1>
        <p className="text-slate-500">Kelola logo perusahaan klien yang pernah bekerja sama</p>
      </div>

      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <h2 className="text-xl font-bold text-slate-900 mb-6">{editingId ? 'Edit Klien' : 'Tambah Klien Baru'}</h2>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Nama Klien *</label>
              <input type="text" value={formData.name} onChange={(e) => setFormData({ ...formData, name: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="Mandiri Taspen" required />
            </div>
            <div className="md:col-span-2">
              <ImageUpload 
                label="Logo Client *" 
                value={formData.logo || ''} 
                onChange={(url) => setFormData({ ...formData, logo: url })} 
                aspectRatio="square"
              />
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">Website URL</label>
              <input type="url" value={formData.websiteUrl || ''} onChange={(e) => setFormData({ ...formData, websiteUrl: e.target.value })} className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green" placeholder="https://..." />
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

      <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        {clients.map((client) => (
          <motion.div key={client.id} initial={{ opacity: 0, scale: 0.95 }} animate={{ opacity: 1, scale: 1 }} className="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 group hover:shadow-lg transition-all">
            <div className="aspect-square bg-slate-50 rounded-xl mb-3 flex items-center justify-center p-4 relative overflow-hidden">
              <img src={client.logo} alt={client.name} className="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition-all" />
              {!client.isActive && <div className="absolute inset-0 bg-slate-900/50 flex items-center justify-center"><span className="text-white text-xs font-bold">Nonaktif</span></div>}
            </div>
            <h3 className="font-bold text-slate-900 text-xs text-center mb-1 line-clamp-2">{client.name}</h3>
            {client.websiteUrl && (
              <a href={client.websiteUrl} target="_blank" rel="noopener noreferrer" className="flex items-center justify-center gap-1 text-xs text-toba-green hover:underline mb-2">
                <ExternalLink size={10} />Website
              </a>
            )}
            <p className="text-xs text-slate-400 text-center mb-3">Urutan: {client.orderPriority}</p>
            <div className="flex gap-2">
              <button onClick={() => handleEdit(client)} className="flex-1 p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100"><Edit2 size={14} className="mx-auto" /></button>
              <button onClick={() => handleDelete(client.id)} className="flex-1 p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100"><Trash2 size={14} className="mx-auto" /></button>
            </div>
          </motion.div>
        ))}
      </div>
    </div>
  );
}
