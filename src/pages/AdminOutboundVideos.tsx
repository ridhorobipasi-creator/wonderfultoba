"use client";

import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Save, X, Play } from 'lucide-react';
import { toast } from 'sonner';
import api from '../lib/api';

interface Video {
  id: number;
  title: string;
  youtubeUrl: string;
  orderPriority: number;
  isActive: boolean;
}

export default function AdminOutboundVideos() {
  const [videos, setVideos] = useState<Video[]>([]);
  const [loading, setLoading] = useState(true);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [formData, setFormData] = useState<Partial<Video>>({
    title: '',
    youtubeUrl: '',
    orderPriority: 0,
    isActive: true
  });

  useEffect(() => {
    fetchVideos();
  }, []);

  const fetchVideos = async () => {
    try {
      const res = await api.get('/admin/outbound/videos');
      setVideos(res.data);
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
        await api.put(`/admin/outbound/videos/${editingId}`, formData);
        toast.success('Video berhasil diupdate');
      } else {
        await api.post('/admin/outbound/videos', formData);
        toast.success('Video berhasil ditambahkan');
      }
      
      fetchVideos();
      resetForm();
    } catch (error) {
      toast.error('Gagal menyimpan data');
    }
  };

  const handleEdit = (video: Video) => {
    setEditingId(video.id);
    setFormData(video);
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Yakin ingin menghapus video ini?')) return;
    
    try {
      await api.delete(`/admin/outbound/videos/${id}`);
      toast.success('Video berhasil dihapus');
      fetchVideos();
    } catch (error) {
      toast.error('Gagal menghapus data');
    }
  };

  const resetForm = () => {
    setEditingId(null);
    setFormData({
      title: '',
      youtubeUrl: '',
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
        <h1 className="text-3xl font-black text-slate-900 mb-2">Video Highlight</h1>
        <p className="text-slate-500">Kelola video highlight event outbound</p>
      </div>

      {/* Form */}
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8"
      >
        <h2 className="text-xl font-bold text-slate-900 mb-6">
          {editingId ? 'Edit Video' : 'Tambah Video Baru'}
        </h2>
        
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">
                Judul Video *
              </label>
              <input
                type="text"
                value={formData.title}
                onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent"
                placeholder="Highlight"
                required
              />
            </div>

            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">
                YouTube Embed URL *
              </label>
              <input
                type="url"
                value={formData.youtubeUrl}
                onChange={(e) => setFormData({ ...formData, youtubeUrl: e.target.value })}
                className="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent"
                placeholder="https://www.youtube.com/embed/..."
                required
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
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {videos.map((video) => (
          <motion.div
            key={video.id}
            initial={{ opacity: 0, scale: 0.95 }}
            animate={{ opacity: 1, scale: 1 }}
            className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
          >
            <div className="aspect-video bg-slate-900 relative">
              <iframe
                src={video.youtubeUrl}
                className="w-full h-full"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
              />
              <div className="absolute top-3 right-3">
                <span className={`px-3 py-1 rounded-full text-xs font-bold ${
                  video.isActive ? 'bg-green-500 text-white' : 'bg-slate-500 text-white'
                }`}>
                  {video.isActive ? 'Aktif' : 'Nonaktif'}
                </span>
              </div>
            </div>
            
            <div className="p-4">
              <div className="flex items-start justify-between mb-3">
                <div>
                  <h3 className="font-bold text-slate-900 mb-1">{video.title}</h3>
                  <p className="text-sm text-slate-500">Urutan: {video.orderPriority}</p>
                </div>
              </div>
              
              <div className="flex gap-2">
                <button
                  onClick={() => handleEdit(video)}
                  className="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-bold hover:bg-blue-100 transition-colors"
                >
                  <Edit2 size={16} />
                  Edit
                </button>
                <button
                  onClick={() => handleDelete(video.id)}
                  className="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-100 transition-colors"
                >
                  <Trash2 size={16} />
                  Hapus
                </button>
              </div>
            </div>
          </motion.div>
        ))}
      </div>
    </div>
  );
}
