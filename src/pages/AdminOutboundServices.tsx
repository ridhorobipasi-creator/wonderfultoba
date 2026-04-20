"use client";

import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Save, X, Image as ImageIcon, Users, Smile, Sparkles, Compass, Target, Swords, ArrowUp, ArrowDown } from 'lucide-react';
import { toast } from 'sonner';
import api from '../lib/api';
import ImageUpload from '../components/ImageUpload';

interface Service {
  id: number;
  title: string;
  shortDesc: string;
  detailDesc: string;
  icon: string;
  image: string;
  orderPriority: number;
  isActive: boolean;
}

const iconOptions = [
  { value: 'Users', label: 'Users', Icon: Users },
  { value: 'Smile', label: 'Smile', Icon: Smile },
  { value: 'Sparkles', label: 'Sparkles', Icon: Sparkles },
  { value: 'Compass', label: 'Compass', Icon: Compass },
  { value: 'Target', label: 'Target', Icon: Target },
  { value: 'Swords', label: 'Swords', Icon: Swords },
];

export default function AdminOutboundServices() {
  const [services, setServices] = useState<Service[]>([]);
  const [loading, setLoading] = useState(true);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [formData, setFormData] = useState<Partial<Service>>({
    title: '',
    shortDesc: '',
    detailDesc: '',
    icon: 'Users',
    image: '',
    orderPriority: 0,
    isActive: true
  });

  useEffect(() => {
    fetchServices();
  }, []);

  const fetchServices = async () => {
    try {
      const res = await fetch('/api/outbound/services');
      const data = await res.json();
      setServices(data);
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
        // Update
        await api.put(`/admin/outbound/services/${editingId}`, formData);
        toast.success('Layanan berhasil diupdate');
      } else {
        // Create
        await api.post('/admin/outbound/services', formData);
        toast.success('Layanan berhasil ditambahkan');
      }
      
      fetchServices();
      resetForm();
    } catch (error) {
      toast.error('Gagal menyimpan data');
    }
  };

  const handleEdit = (service: Service) => {
    setEditingId(service.id);
    setFormData(service);
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Yakin ingin menghapus layanan ini?')) return;
    
    try {
      await api.delete(`/admin/outbound/services/${id}`);
      toast.success('Layanan berhasil dihapus');
      fetchServices();
    } catch (error) {
      toast.error('Gagal menghapus data');
    }
  };

  const handleToggleActive = async (id: number, isActive: boolean) => {
    try {
      await api.put(`/admin/outbound/services/${id}`, { isActive: !isActive });
      toast.success('Status berhasil diubah');
      fetchServices();
    } catch (error) {
      toast.error('Gagal mengubah status');
    }
  };

  const resetForm = () => {
    setEditingId(null);
    setFormData({
      title: '',
      shortDesc: '',
      detailDesc: '',
      icon: 'Users',
      image: '',
      orderPriority: 0,
      isActive: true
    });
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin" />
      </div>
    );
  }

  return (
    <div className="p-8 max-w-7xl mx-auto">
      <div className="mb-8">
        <h1 className="text-3xl font-black text-slate-900 mb-2">Layanan Outbound</h1>
        <p className="text-slate-500">Kelola layanan outbound yang ditampilkan di halaman user</p>
      </div>

      {/* Form */}
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8"
      >
        <h2 className="text-xl font-bold text-slate-900 mb-6">
          {editingId ? 'Edit Layanan' : 'Tambah Layanan Baru'}
        </h2>
        
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">
                Judul Layanan *
              </label>
              <input
                type="text"
                value={formData.title}
                onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent"
                placeholder="TEAM BUILDING"
                required
              />
            </div>

            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">
                Icon
              </label>
              <select
                value={formData.icon}
                onChange={(e) => setFormData({ ...formData, icon: e.target.value })}
                className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent"
              >
                {iconOptions.map(opt => (
                  <option key={opt.value} value={opt.value}>{opt.label}</option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">
                Deskripsi Singkat *
              </label>
              <input
                type="text"
                value={formData.shortDesc}
                onChange={(e) => setFormData({ ...formData, shortDesc: e.target.value })}
                className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent"
                placeholder="Sinergi dan kolaborasi personal"
                required
              />
            </div>

            <div className="md:col-span-2">
              <ImageUpload 
                label="Gambar Service *" 
                value={formData.image || ''} 
                onChange={(url) => setFormData({ ...formData, image: url })} 
                aspectRatio="square"
              />
            </div>

            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">
                Urutan
              </label>
              <input
                type="number"
                value={formData.orderPriority}
                onChange={(e) => setFormData({ ...formData, orderPriority: parseInt(e.target.value) })}
                className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent"
              />
            </div>

            <div className="flex items-center">
              <label className="flex items-center cursor-pointer">
                <input
                  type="checkbox"
                  checked={formData.isActive}
                  onChange={(e) => setFormData({ ...formData, isActive: e.target.checked })}
                  className="w-5 h-5 text-toba-green rounded focus:ring-2 focus:ring-toba-green"
                />
                <span className="ml-2 text-sm font-bold text-slate-700">Aktif</span>
              </label>
            </div>
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">
              Deskripsi Detail *
            </label>
            <textarea
              value={formData.detailDesc}
              onChange={(e) => setFormData({ ...formData, detailDesc: e.target.value })}
              className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent"
              rows={4}
              placeholder="Deskripsi lengkap tentang layanan..."
              required
            />
          </div>

          <div className="flex gap-3">
            <button
              type="submit"
              className="flex items-center gap-2 px-6 py-2 bg-toba-green text-white rounded-xl font-bold hover:bg-toba-green/90 transition-colors"
            >
              <Save size={18} />
              {editingId ? 'Update' : 'Simpan'}
            </button>
            
            {editingId && (
              <button
                type="button"
                onClick={resetForm}
                className="flex items-center gap-2 px-6 py-2 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300 transition-colors"
              >
                <X size={18} />
                Batal
              </button>
            )}
          </div>
        </form>
      </motion.div>

      {/* List */}
      <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-slate-50 border-b border-slate-200">
              <tr>
                <th className="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                  Layanan
                </th>
                <th className="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                  Icon
                </th>
                <th className="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                  Deskripsi
                </th>
                <th className="px-6 py-4 text-center text-xs font-black text-slate-700 uppercase tracking-wider">
                  Urutan
                </th>
                <th className="px-6 py-4 text-center text-xs font-black text-slate-700 uppercase tracking-wider">
                  Status
                </th>
                <th className="px-6 py-4 text-center text-xs font-black text-slate-700 uppercase tracking-wider">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-200">
              {services.map((service) => {
                const IconComponent = iconOptions.find(i => i.value === service.icon)?.Icon || Users;
                return (
                  <tr key={service.id} className="hover:bg-slate-50 transition-colors">
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        <img src={service.image} alt={service.title} className="w-12 h-12 rounded-lg object-cover" />
                        <div>
                          <div className="font-bold text-slate-900">{service.title}</div>
                          <div className="text-sm text-slate-500">{service.shortDesc}</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-2">
                        <IconComponent size={20} className="text-toba-green" />
                        <span className="text-sm text-slate-600">{service.icon}</span>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="text-sm text-slate-600 line-clamp-2 max-w-xs">
                        {service.detailDesc}
                      </div>
                    </td>
                    <td className="px-6 py-4 text-center">
                      <span className="inline-flex items-center justify-center w-8 h-8 bg-slate-100 text-slate-700 rounded-lg font-bold text-sm">
                        {service.orderPriority}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-center">
                      <button
                        onClick={() => handleToggleActive(service.id, service.isActive)}
                        className={`px-3 py-1 rounded-full text-xs font-bold ${
                          service.isActive
                            ? 'bg-green-100 text-green-700'
                            : 'bg-slate-100 text-slate-500'
                        }`}
                      >
                        {service.isActive ? 'Aktif' : 'Nonaktif'}
                      </button>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center justify-center gap-2">
                        <button
                          onClick={() => handleEdit(service)}
                          className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                          title="Edit"
                        >
                          <Edit2 size={18} />
                        </button>
                        <button
                          onClick={() => handleDelete(service.id)}
                          className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                          title="Hapus"
                        >
                          <Trash2 size={18} />
                        </button>
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
