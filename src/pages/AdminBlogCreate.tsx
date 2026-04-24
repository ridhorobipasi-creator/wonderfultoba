"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { useRouter, useParams } from 'next/navigation';
import { 
  FileText, Save, ArrowLeft, Globe, LayoutList, Image as ImageIcon, CheckCircle
} from 'lucide-react';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import ImageUpload from '@/components/ImageUpload';
import RichTextEditor from '@/components/admin/RichTextEditor';

interface AdminBlogForm {
  title: string;
  slug?: string;
  author?: string;
  category: string;
  content: string;
  excerpt?: string;
  tags?: string;
  image?: string;
  is_published: boolean;
  meta_title?: string;
  meta_description?: string;
  translations: {
    en: Record<string, string>;
    ms: Record<string, string>;
  };
  view_count: number;
}

export default function AdminBlogCreate() {
  const router = useRouter();
  const params = useParams<{ id?: string }>();
  const id = params?.id;
  const [activeTab, setActiveTab] = useState('info');
  const { register, handleSubmit, watch, setValue, reset } = useForm<AdminBlogForm>({
    defaultValues: {
      is_published: false,
      view_count: 0,
      translations: { en: {}, ms: {} },
      category: 'tour',
    }
  });

  // Load existing blog data for edit mode
  useEffect(() => {
    if (id) {
      api.get(`/blogs/${id}`).then(res => {
        const blog = res.data;
        reset({
          title: blog.title,
          slug: blog.slug,
          author: blog.author,
          category: blog.category || 'tour',
          content: blog.content,
          excerpt: blog.excerpt,
          image: blog.image,
          is_published: blog.status === 'published',
          meta_title: blog.metaTitle,
          meta_description: blog.metaDescription,
          translations: blog.translations || { en: {}, ms: {} },
          view_count: 0,
        });
      }).catch(() => toast.error('Gagal memuat data artikel'));
    }
  }, [id, reset]);

  const onSubmit = async (data: AdminBlogForm) => {
    try {
      const tagsArray = data.tags ? data.tags.split(',').map((s: string) => s.trim()) : [];

      const payload = {
        title: data.title,
        author: data.author || 'Admin',
        content: data.content,
        category: data.category,
        tags: tagsArray,
        image: data.image || '',
        slug: data.slug || data.title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''),
        excerpt: data.excerpt,
        status: data.is_published ? 'published' : 'draft',
        meta_title: data.meta_title,
        meta_description: data.meta_description,
        translations: data.translations,
      };

      if (id) {
        await api.put(`/blogs/${id}`, payload);
        toast.success('Artikel berhasil diperbarui');
      } else {
        await api.post('/blogs', payload);
        toast.success('Artikel baru berhasil ditambahkan');
      }
      router.push('/admin/blog');
    } catch (error) {
      console.error('Error saving blog post:', error);
      toast.error('Gagal menyimpan artikel');
    }
  };

  const tabs = [
    { id: 'info', label: 'Informasi Dasar', icon: <FileText size={18} /> },
    { id: 'seo', label: 'Status & SEO', icon: <Globe size={18} /> },
    { id: 'bahasa', label: 'Terjemahan', icon: <LayoutList size={18} /> },
  ];

  return (
    <div className="max-w-5xl mx-auto pb-12 animate-in fade-in duration-500">
      <button onClick={() => router.push('/admin/blog')} className="flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-6 transition-colors">
        <ArrowLeft size={18} /> Kembali ke Manajemen Blog
      </button>

      <div className="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-slate-100">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
          <div>
            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-2">
              {id ? 'Edit Artikel' : 'Tulis Artikel Baru'}
            </h2>
            <p className="text-slate-500 font-medium">Buat konten menarik untuk memandu wisatawan.</p>
          </div>
          <button
            onClick={handleSubmit(onSubmit)}
            className="bg-toba-green text-white px-10 py-4 rounded-2xl font-bold hover:bg-toba-green/90 transition-all shadow-xl shadow-blue-100 flex items-center space-x-2"
          >
            <Save size={20} />
            <span>{id ? 'Simpan Perubahan' : 'Simpan Artikel'}</span>
          </button>
        </div>

        {/* Tab Navigation */}
        <div className="flex overflow-x-auto hide-scrollbar border-b border-slate-100 mb-8 space-x-2">
          {tabs.map(tab => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`flex items-center space-x-2 px-6 py-4 border-b-2 font-bold transition-all whitespace-nowrap ${activeTab === tab.id ? 'border-toba-green text-toba-green' : 'border-transparent text-slate-400 hover:text-slate-600'}`}
            >
              {tab.icon}
              <span>{tab.label}</span>
            </button>
          ))}
        </div>

        <form className="space-y-8">
          {/* INFORMASI DASAR */}
          {activeTab === 'info' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Judul Artikel</label>
                  <div className="relative">
                    <FileText className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      {...register('title', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900"
                      placeholder="Contoh: 10 Pesona Danau Toba"
                    />
                  </div>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">URL Slug (Otomatis jika kosong)</label>
                  <div className="relative">
                    <Globe className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      {...register('slug')}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-900"
                      placeholder="contoh: 10-pesona-danau-toba"
                    />
                  </div>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Kategori Utama</label>
                  <select
                    {...register('category')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 appearance-none"
                  >
                    <optgroup label="Halaman Website">
                      <option value="tour">Tour & Travel (tampil di /tour/blog)</option>
                      <option value="outbound">Corporate Outbound (tampil di /outbound/blog)</option>
                    </optgroup>
                    <optgroup label="Kategori Konten">
                      <option value="Berita">Berita / Update</option>
                      <option value="Destinasi">Destinasi Populer</option>
                      <option value="Tips Wisata">Tips Wisata</option>
                      <option value="Kuliner">Kuliner</option>
                      <option value="Promo">Promo & Diskon</option>
                    </optgroup>
                  </select>
                  <p className="text-[10px] text-slate-400 ml-1">Pilih <strong>Tour</strong> atau <strong>Outbound</strong> agar artikel muncul di halaman yang tepat</p>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Penulis (Author)</label>
                  <input
                    {...register('author')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-900"
                    placeholder="Admin Wonderful Toba"
                  />
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Tags (Pisahkan dengan koma)</label>
                  <input
                    {...register('tags')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-900"
                    placeholder="Samosir, Toba, Sunset..."
                  />
                </div>
              </div>

              <div className="space-y-3">
                <ImageUpload
                  label="Gambar Banner Utama (Upload dari PC/HP - Auto WebP)"
                  value={watch('image')}
                  onChange={(url) => setValue('image', url)}
                  aspectRatio="wide"
                />
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Kutipan Singkat (Excerpt)</label>
                <textarea
                  {...register('excerpt')}
                  rows={2}
                  className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                  placeholder="Ringkasan atau paragraf pembuka artikel..."
                />
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Isi Artikel Lengkap</label>
                <RichTextEditor
                  value={watch('content') || ''}
                  onChange={(val) => setValue('content', val, { shouldValidate: true, shouldDirty: true })}
                />
              </div>
            </div>
          )}

          {/* STATUS & SEO */}
          {activeTab === 'seo' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="flex items-center space-x-3 bg-emerald-50 text-emerald-900 p-6 rounded-2xl border border-emerald-100">
                <input
                  type="checkbox"
                  {...register('is_published')}
                  id="is_published"
                  className="w-5 h-5 text-emerald-600 focus:ring-emerald-500 border-emerald-300 rounded"
                />
                <label htmlFor="is_published" className="font-bold flex items-center gap-2">
                  <CheckCircle size={18} className="text-emerald-600" />
                  Terbitkan Artikel Ini Segera Tampil di Website (Publish)
                </label>
              </div>

              <div className="pt-4 border-t border-slate-100 space-y-4">
                <h4 className="font-bold text-slate-900 mb-2">Optimasi Mesin Pencari (SEO Google)</h4>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Meta Title</label>
                  <input
                    {...register('meta_title')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                    placeholder="Akan digunakan sebagai judul di Google..."
                    maxLength={60}
                  />
                  <p className="text-xs text-slate-400 ml-1">Idealnya maksimal 60 karakter</p>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Meta Description</label>
                  <textarea
                    {...register('meta_description')}
                    rows={2}
                    className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                    placeholder="Akan digunakan sebagai deskripsi hasil pencarian di Google..."
                    maxLength={160}
                  />
                  <p className="text-xs text-slate-400 ml-1">Idealnya maksimal 160 karakter</p>
                </div>
              </div>
            </div>
          )}

          {/* TERJEMAHAN */}
          {activeTab === 'bahasa' && (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 animate-in fade-in slide-in-from-bottom-4">
              <div className="space-y-6 bg-slate-50 p-6 rounded-3xl">
                <div className="flex items-center space-x-2 border-b border-slate-200 pb-4">
                  <span className="text-2xl">🇺🇸</span>
                  <h3 className="font-bold text-slate-900">English (EN)</h3>
                </div>
                <div className="space-y-4">
                  <input
                    {...register('translations.en.title')}
                    className="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Article Title (EN)"
                  />
                  <textarea
                    {...register('translations.en.excerpt')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Short Excerpt (EN)"
                  />
                  <textarea
                    {...register('translations.en.content')}
                    rows={6}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Full Content (EN)"
                  />
                </div>
              </div>

              <div className="space-y-6 bg-slate-50 p-6 rounded-3xl">
                <div className="flex items-center space-x-2 border-b border-slate-200 pb-4">
                  <span className="text-2xl">🇲🇾</span>
                  <h3 className="font-bold text-slate-900">Malaysia (MS)</h3>
                </div>
                <div className="space-y-4">
                  <input
                    {...register('translations.ms.title')}
                    className="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Tajuk Artikel (MS)"
                  />
                  <textarea
                    {...register('translations.ms.excerpt')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Kutipan Ringkas (MS)"
                  />
                  <textarea
                    {...register('translations.ms.content')}
                    rows={6}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Isi Artikel (MS)"
                  />
                </div>
              </div>
            </div>
          )}
        </form>
      </div>
    </div>
  );
}
