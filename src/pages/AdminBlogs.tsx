"use client";

import { useState, useEffect, useCallback } from 'react';
import api from '../lib/api';
import { motion } from 'framer-motion';
import { Plus, Edit2, Trash2, Search, FileText, Globe, Eye, MoreHorizontal, Calendar, RefreshCcw } from 'lucide-react';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import { useRouter } from 'next/navigation';

interface AdminBlog {
  id: number | string;
  title: string;
  category?: string;
  image?: string;
  featured_image?: string[];
  is_published: boolean;
  view_count?: number;
  created_at: string;
  author?: { name?: string };
}

export default function AdminBlogs({ category }: { category?: 'tour' | 'outbound' }) {
  const router = useRouter();
  const [blogs, setBlogs] = useState<AdminBlog[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');

  const [refreshing, setRefreshing] = useState(false);

  const fetchData = useCallback(async (silent = false) => {
    if (!silent) setLoading(true);
    if (silent) setRefreshing(true);
    try {
      const res = await api.get<AdminBlog[]>('/blogs', { params: { category } });
      setBlogs(res.data);
    } catch (error) {
      console.error('Error fetching blogs:', error);
      if (!silent) toast.error('Gagal mengambil data artikel');
    } finally {
      if (!silent) setLoading(false);
      setRefreshing(false);
    }
  }, [category]);

  useEffect(() => {
    fetchData();
    
    // Auto refresh every 30 seconds
    const intervalId = setInterval(() => {
      fetchData(true);
    }, 30000);

    return () => clearInterval(intervalId);
  }, [fetchData]);

  const handleDelete = async (id: string | number) => {
    if (window.confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
      try {
        await api.delete(`/blogs/${id}`);
        toast.success('Artikel dihapus');
        fetchData();
      } catch {
        toast.error('Gagal menghapus artikel');
      }
    }
  };

  const filteredBlogs = blogs.filter(blog => 
    blog.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
    blog.category?.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="space-y-10 pb-12 animate-in fade-in duration-500">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <div className="flex items-center gap-3 mb-2">
            <h2 className="text-4xl font-bold text-slate-900 tracking-tight">Manajemen Konten (Blog)</h2>
            <button 
              onClick={() => fetchData(true)} 
              disabled={refreshing}
              className="p-2 text-slate-400 hover:text-toba-green transition-all disabled:opacity-50"
              title="Refresh data"
            >
              <RefreshCcw className={cn("w-6 h-6", refreshing && "animate-spin")} /> 
            </button>
          </div>
          <p className="text-slate-500 font-medium">Kelola artikel, berita, dan tips wisata untuk pengunjung.</p>
        </div>
        <button
          onClick={() => router.push('/admin/create-blog')}
          className="bg-toba-green text-white px-8 py-4 rounded-2xl font-bold flex items-center space-x-2 hover:bg-toba-green/90 transition-all shadow-xl shadow-blue-100 group"
        >
          <Plus size={20} className="transition-transform group-hover:rotate-90" />
          <span>Tulis Artikel Baru</span>
        </button>
      </div>

      {/* Filters & Search */}
      <div className="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div className="relative w-full md:w-96">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input
            type="text"
            placeholder="Cari judul artikel..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green font-medium transition-all"
          />
        </div>
        <div className="flex items-center space-x-3 w-full md:w-auto">
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
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Artikel</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kategori</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Status</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Views</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={5} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-12 h-12 border-4 border-blue-100 border-t-toba-green rounded-full animate-spin mb-4"></div>
                      <p className="text-slate-400 font-medium">Memuat data artikel...</p>
                    </div>
                  </td>
                </tr>
              ) : filteredBlogs.length > 0 ? filteredBlogs.map((blog, index) => (
                <motion.tr 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.05 }}
                  key={blog.id} 
                  className="hover:bg-slate-50/50 transition-colors group"
                >
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-5">
                      <div className="w-16 h-16 rounded-2xl overflow-hidden bg-slate-50 shrink-0 shadow-lg shadow-slate-200 group-hover:scale-105 transition-transform duration-500 relative flex items-center justify-center p-2">
                        {blog.image || blog.featured_image?.[0] ? (
                          <img src={blog.image ?? blog.featured_image?.[0] ?? undefined} alt="" className="w-full h-full object-cover rounded-xl" />
                        ) : (
                          <FileText size={20} className="text-slate-300" />
                        )}
                      </div>
                      <div>
                        <p className="font-bold text-slate-900 text-lg group-hover:text-toba-green transition-colors line-clamp-1">{blog.title}</p>
                        <div className="flex items-center text-xs text-slate-400 font-medium mt-1 gap-3">
                          <span className="flex items-center gap-1"><Calendar size={12} /> {new Date(blog.created_at).toLocaleDateString()}</span>
                          <span className="flex items-center gap-1"><Globe size={12} /> {blog.author?.name || 'Admin'}</span>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-2 text-slate-600">
                      <span className="text-sm font-bold bg-slate-100 text-slate-600 px-3 py-1 rounded-lg">{blog.category || 'Utama'}</span>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <span className={cn(
                      "px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.15em] border",
                      blog.is_published ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100'
                    )}>
                      {blog.is_published ? 'Published' : 'Draft'}
                    </span>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-2 text-slate-500 font-medium">
                      <Eye size={16} />
                      <span>{blog.view_count || 0}</span>
                    </div>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button 
                        onClick={() => router.push(`/admin/edit-blog/${blog.id}`)} 
                        className="p-3 text-slate-400 hover:text-toba-green hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100"
                        title="Edit Artikel"
                      >
                        <Edit2 size={18} />
                      </button>
                      <button 
                        onClick={() => handleDelete(blog.id)} 
                        className="p-3 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100"
                        title="Hapus Artikel"
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
                        <FileText size={32} />
                      </div>
                      <p className="text-slate-400 font-medium">Belum ada artikel yang ditulis.</p>
                      <button onClick={() => router.push('/admin/create-blog')} className="mt-4 text-toba-green font-bold hover:underline">
                        Buat artikel pertama
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
