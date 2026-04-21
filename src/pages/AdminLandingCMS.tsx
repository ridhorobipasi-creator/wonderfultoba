"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Save, ArrowLeft, Layout, Image as ImageIcon, Type, Loader2 } from 'lucide-react';
import { toast } from 'sonner';
import { useRouter } from 'next/navigation';
import { cn } from '../utils/cn';
import ImageUpload from '../components/ImageUpload';

interface LandingContent {
  outbound: {
    backgroundImage: string;
    title: string;
    subtitle: string;
    ctaText: string;
  };
  tour: {
    backgroundImage: string;
    title: string;
    subtitle: string;
    ctaText: string;
  };
  brand: {
    name: string;
    tagline: string;
  };
}

const defaultContent: LandingContent = {
  outbound: {
    backgroundImage: '/assets/images/2023/10/006.jpg',
    title: 'Corporate\nOutbound.',
    subtitle: 'Solusi team building & gathering profesional untuk instansi Anda. Tersedia di puluhan hotel premium Sumut.',
    ctaText: 'Jelajahi Outbound',
  },
  tour: {
    backgroundImage: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000',
    title: 'Tour &\nTravel.',
    subtitle: 'Eksplorasi keindahan Danau Toba, Berastagi, dan alam liar Bukit Lawang dengan paket liburan eksklusif kami.',
    ctaText: 'Jelajahi Wisata',
  },
  brand: {
    name: 'Wonderful Toba',
    tagline: 'Sumatera Utara',
  },
};

export default function AdminLandingCMS() {
  const router = useRouter();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [activeTab, setActiveTab] = useState<'outbound' | 'tour' | 'brand'>('outbound');
  const [content, setContent] = useState<LandingContent>(defaultContent);

  useEffect(() => {
    fetchContent();
  }, []);

  const fetchContent = async () => {
    setLoading(true);
    try {
      const res = await api.get('/settings?key=landing_page');
      if (res.data) {
        setContent({ ...defaultContent, ...res.data });
      }
    } catch {
      // use defaults
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    setSaving(true);
    try {
      await api.post('/settings', { key: 'landing_page', value: content });
      toast.success('Halaman utama berhasil disimpan');
    } catch {
      toast.error('Gagal menyimpan');
    } finally {
      setSaving(false);
    }
  };

  const update = (side: 'outbound' | 'tour', field: string, value: string) => {
    setContent(prev => ({ ...prev, [side]: { ...prev[side], [field]: value } }));
  };

  const tabs = [
    { id: 'outbound' as const, label: 'Sisi Outbound (Kiri)', icon: Layout },
    { id: 'tour' as const, label: 'Sisi Tour (Kanan)', icon: ImageIcon },
    { id: 'brand' as const, label: 'Branding Tengah', icon: Type },
  ];

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-[400px]">
        <Loader2 className="w-10 h-10 animate-spin text-toba-green" />
      </div>
    );
  }

  return (
    <div className="max-w-5xl mx-auto pb-20">
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
        <div>
          <button onClick={() => router.back()} className="flex items-center gap-2 text-slate-400 hover:text-slate-900 font-bold mb-4 group">
            <ArrowLeft size={18} className="group-hover:-translate-x-1 transition-transform" /> Kembali
          </button>
          <h2 className="text-4xl font-black text-slate-900 tracking-tight">Halaman <span className="text-toba-green">Utama</span> CMS</h2>
          <p className="text-slate-500 font-medium">Kelola tampilan halaman pemilihan Tour / Outbound di <code className="bg-slate-100 px-2 py-0.5 rounded text-sm">/</code></p>
        </div>
        <button
          onClick={handleSave}
          disabled={saving}
          className="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black hover:bg-slate-800 transition-all shadow-xl flex items-center gap-2 disabled:opacity-50"
        >
          {saving ? <Loader2 size={18} className="animate-spin" /> : <Save size={18} />}
          {saving ? 'Menyimpan...' : 'Simpan Perubahan'}
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {/* Tabs */}
        <div className="lg:col-span-3">
          <div className="bg-white rounded-[2rem] p-3 shadow-sm border border-slate-100 sticky top-24">
            {tabs.map(tab => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={cn(
                  "w-full flex items-center gap-4 px-5 py-4 rounded-xl font-bold transition-all mb-1",
                  activeTab === tab.id
                    ? "bg-toba-green text-white shadow-lg"
                    : "text-slate-400 hover:text-slate-600 hover:bg-slate-50"
                )}
              >
                <tab.icon size={20} />
                <span className="text-sm">{tab.label}</span>
              </button>
            ))}
          </div>
        </div>

        {/* Editor */}
        <div className="lg:col-span-9 bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
          {(activeTab === 'outbound' || activeTab === 'tour') && (
            <div className="space-y-6">
              <div className="flex items-center gap-3 pb-6 border-b border-slate-50">
                <div className="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center">
                  <Layout size={22} />
                </div>
                <div>
                  <h3 className="text-xl font-black text-slate-900">
                    {activeTab === 'outbound' ? 'Sisi Kiri - Corporate Outbound' : 'Sisi Kanan - Tour & Travel'}
                  </h3>
                  <p className="text-sm text-slate-400">Konten yang tampil di bagian {activeTab === 'outbound' ? 'kiri' : 'kanan'} halaman utama</p>
                </div>
              </div>

              <ImageUpload
                label="Gambar Background"
                value={content[activeTab].backgroundImage}
                onChange={v => update(activeTab, 'backgroundImage', v)}
                aspectRatio="wide"
              />

              <div className="space-y-2">
                <label className="text-xs font-black text-slate-400 uppercase tracking-widest">Judul (gunakan \n untuk baris baru)</label>
                <textarea
                  value={content[activeTab].title}
                  onChange={e => update(activeTab, 'title', e.target.value)}
                  rows={2}
                  className="w-full px-5 py-4 bg-slate-50 rounded-2xl font-bold text-slate-900 border-none focus:ring-2 focus:ring-toba-green"
                  placeholder="Corporate\nOutbound."
                />
              </div>

              <div className="space-y-2">
                <label className="text-xs font-black text-slate-400 uppercase tracking-widest">Sub-judul</label>
                <textarea
                  value={content[activeTab].subtitle}
                  onChange={e => update(activeTab, 'subtitle', e.target.value)}
                  rows={3}
                  className="w-full px-5 py-4 bg-slate-50 rounded-2xl font-medium text-slate-700 border-none focus:ring-2 focus:ring-toba-green"
                />
              </div>

              <div className="space-y-2">
                <label className="text-xs font-black text-slate-400 uppercase tracking-widest">Teks Tombol CTA</label>
                <input
                  value={content[activeTab].ctaText}
                  onChange={e => update(activeTab, 'ctaText', e.target.value)}
                  className="w-full px-5 py-4 bg-slate-50 rounded-2xl font-bold text-slate-900 border-none focus:ring-2 focus:ring-toba-green"
                  placeholder="Jelajahi Outbound"
                />
              </div>

              {/* Preview */}
              <div className="mt-6 p-4 bg-slate-900 rounded-2xl relative overflow-hidden" style={{ minHeight: 160 }}>
                {content[activeTab].backgroundImage && (
                  <img
                    src={content[activeTab].backgroundImage}
                    alt="preview"
                    className="absolute inset-0 w-full h-full object-cover opacity-40"
                  />
                )}
                <div className="relative z-10 text-white">
                  <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Preview</p>
                  <h3 className="text-2xl font-black leading-tight whitespace-pre-line">{content[activeTab].title}</h3>
                  <p className="text-slate-300 text-sm mt-2 line-clamp-2">{content[activeTab].subtitle}</p>
                  <div className="mt-3 inline-block bg-toba-green text-white px-5 py-2 rounded-full text-xs font-bold">
                    {content[activeTab].ctaText}
                  </div>
                </div>
              </div>
            </div>
          )}

          {activeTab === 'brand' && (
            <div className="space-y-6">
              <div className="flex items-center gap-3 pb-6 border-b border-slate-50">
                <div className="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center">
                  <Type size={22} />
                </div>
                <div>
                  <h3 className="text-xl font-black text-slate-900">Branding Tengah</h3>
                  <p className="text-sm text-slate-400">Logo dan tagline yang tampil di tengah halaman</p>
                </div>
              </div>

              <div className="space-y-2">
                <label className="text-xs font-black text-slate-400 uppercase tracking-widest">Nama Brand</label>
                <input
                  value={content.brand.name}
                  onChange={e => setContent(prev => ({ ...prev, brand: { ...prev.brand, name: e.target.value } }))}
                  className="w-full px-5 py-4 bg-slate-50 rounded-2xl font-bold text-slate-900 border-none focus:ring-2 focus:ring-toba-green"
                  placeholder="Wonderful Toba"
                />
              </div>

              <div className="space-y-2">
                <label className="text-xs font-black text-slate-400 uppercase tracking-widest">Tagline</label>
                <input
                  value={content.brand.tagline}
                  onChange={e => setContent(prev => ({ ...prev, brand: { ...prev.brand, tagline: e.target.value } }))}
                  className="w-full px-5 py-4 bg-slate-50 rounded-2xl font-bold text-slate-900 border-none focus:ring-2 focus:ring-toba-green"
                  placeholder="Sumatera Utara"
                />
              </div>

              {/* Preview */}
              <div className="mt-4 flex justify-center">
                <div className="bg-slate-900/80 backdrop-blur-xl p-8 rounded-full border border-white/10 text-center">
                  <div className="w-16 h-16 bg-toba-green rounded-full flex items-center justify-center font-black text-2xl mx-auto mb-3 text-white">W</div>
                  <h3 className="text-xl font-black text-white">{content.brand.name || 'Wonderful Toba'}</h3>
                  <p className="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-300 mt-1">{content.brand.tagline || 'Sumatera Utara'}</p>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
