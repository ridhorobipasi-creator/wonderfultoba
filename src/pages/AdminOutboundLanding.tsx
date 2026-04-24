"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { 
  Users, Sparkles, Video, MapPin, ImageIcon, Save, Plus, Trash2, 
  ArrowLeft, Layout, Type, List, MonitorPlay, Globe, Image as HeroIcon
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { toast } from 'sonner';
import { useRouter } from 'next/navigation';
import { cn } from '../utils/cn';
import ImageUpload from '../components/ImageUpload';

interface AboutSection {
  title: string;
  description: string;
  vision: string;
  mission: string;
  statsLabel: string;
  statsValue: string;
}

interface HeroSlide {
  image: string;
}

interface ServiceItem {
  title: string;
  desc: string;
  image: string;
  detail: string;
  icon: string;
}

interface VideoItem {
  title: string;
  url: string;
}

interface LocationItem {
  title: string;
  img: string;
}

interface OutboundLandingContent {
  about: AboutSection;
  heroImages: string[];
  services: ServiceItem[];
  videos: VideoItem[];
  locations: LocationItem[];
  gallery: string[];
}

export default function AdminOutboundLanding() {
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [activeTab, setActiveTab] = useState('about');
  const [content, setContent] = useState<OutboundLandingContent>({
    about: { title: '', description: '', vision: '', mission: '', statsLabel: '', statsValue: '' },
    heroImages: [],
    services: [],
    videos: [],
    locations: [],
    gallery: []
  });

  const router = useRouter();

  useEffect(() => {
    fetchContent();
  }, []);

  const fetchContent = async () => {
    setLoading(true);
    try {
      const res = await api.get('/settings?key=outbound_landing');
      if (res.data) {
        // Merge with defaults to ensure all fields exist
        setContent(prev => ({
          ...prev,
          ...res.data,
          heroImages: res.data.heroImages || prev.heroImages || [],
          gallery: res.data.gallery || prev.gallery || [],
        }));
      }
    } catch (error) {
      console.error('Error fetching settings:', error);
      toast.error('Gagal mengambil data konten');
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    setSaving(true);
    try {
      await api.post('/settings', {
        key: 'outbound_landing',
        value: content
      });
      toast.success('Konten berhasil disimpan');
    } catch (error) {
      console.error('Error saving settings:', error);
      toast.error('Gagal menyimpan konten');
    } finally {
      setSaving(false);
    }
  };

  const updateAbout = (field: keyof AboutSection, value: string) => {
    setContent({ ...content, about: { ...content.about, [field]: value } });
  };

  const addItem = (type: 'services' | 'videos' | 'locations', template: ServiceItem | VideoItem | LocationItem) => {
    setContent({ ...content, [type]: [...content[type], template] });
  };

  const removeItem = (type: 'services' | 'videos' | 'locations', index: number) => {
    const newList = [...content[type]];
    newList.splice(index, 1);
    setContent({ ...content, [type]: newList });
  };

  const updateItem = (type: 'services' | 'videos' | 'locations', index: number, field: string, value: unknown) => {
    const newList = [...content[type]];
    newList[index] = { ...newList[index], [field]: value } as ServiceItem & VideoItem & LocationItem;
    setContent({ ...content, [type]: newList });
  };

  const tabs = [
    { id: 'about', label: 'Tentang Kami', icon: Users },
    { id: 'hero', label: 'Hero Slider', icon: HeroIcon },
    { id: 'services', label: 'Layanan', icon: Sparkles },
    { id: 'videos', label: 'Video Highlight', icon: Video },
    { id: 'locations', label: 'Lokasi', icon: MapPin },
    { id: 'gallery', label: 'Galeri', icon: ImageIcon },
  ];

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[400px]">
        <div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin" />
        <p className="mt-4 text-slate-400 font-medium">Memuat editor konten...</p>
      </div>
    );
  }

  return (
    <div className="max-w-6xl mx-auto pb-20">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
        <div>
          <button onClick={() => router.back()} className="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors font-bold mb-4 group">
            <ArrowLeft size={18} className="group-hover:-translate-x-1 transition-transform" /> Kembali
          </button>
          <h2 className="text-4xl font-black text-slate-900 tracking-tight">Outbound <span className="text-toba-green">CMS</span></h2>
          <p className="text-slate-500 font-medium">Kelola konten landing page Corporate Outbound secara real-time.</p>
        </div>
        <button
          onClick={handleSave}
          disabled={saving}
          className="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 flex items-center space-x-2 disabled:opacity-50"
        >
          {saving ? <div className="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin" /> : <Save size={18} />}
          <span>{saving ? 'Menyimpan...' : 'Simpan Perubahan'}</span>
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {/* Tab Sidebar */}
        <div className="lg:col-span-3">
          <div className="bg-white rounded-[2rem] p-3 shadow-sm border border-slate-100 sticky top-24">
            {tabs.map((tab) => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={cn(
                  "w-full flex items-center gap-4 px-5 py-4 rounded-xl font-bold transition-all mb-1",
                  activeTab === tab.id 
                    ? "bg-toba-green text-white shadow-lg shadow-emerald-100 scale-[1.02]" 
                    : "text-slate-400 hover:text-slate-600 hover:bg-slate-50"
                )}
              >
                <tab.icon size={20} />
                <span>{tab.label}</span>
              </button>
            ))}
          </div>
        </div>

        {/* Editor Area */}
        <div className="lg:col-span-9 bg-white rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-slate-100 min-h-[600px]">
          <AnimatePresence mode="wait">
            <motion.div
              key={activeTab}
              initial={{ opacity: 0, x: 10 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -10 }}
              transition={{ duration: 0.2 }}
            >
              {/* HERO SLIDER */}
              {activeTab === 'hero' && (
                <div className="space-y-8">
                  <SectionHeader title="Hero Slider" icon={HeroIcon} desc="Gambar yang berputar di bagian atas halaman Outbound. Upload dari PC/HP, otomatis convert ke WebP." />
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {(content.heroImages || []).map((img: string, idx: number) => (
                      <div key={idx} className="relative">
                        <ImageUpload
                          label={`Slide ${idx + 1}`}
                          value={img}
                          onChange={(v) => {
                            const newList = [...(content.heroImages || [])];
                            newList[idx] = v;
                            setContent({ ...content, heroImages: newList });
                          }}
                          aspectRatio="wide"
                        />
                        <button
                          onClick={() => {
                            const newList = [...(content.heroImages || [])];
                            newList.splice(idx, 1);
                            setContent({ ...content, heroImages: newList });
                          }}
                          className="absolute -top-2 -right-2 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-rose-600 transition-colors z-20"
                        >
                          <Trash2 size={14} />
                        </button>
                      </div>
                    ))}
                  </div>
                  <button
                    onClick={() => setContent({ ...content, heroImages: [...(content.heroImages || []), ''] })}
                    className="w-full py-6 border-2 border-dashed border-slate-200 rounded-3xl text-slate-400 font-bold hover:text-toba-green hover:border-toba-green transition-all flex items-center justify-center gap-2"
                  >
                    <Plus size={20} /> Tambah Slide Hero
                  </button>
                  <p className="text-xs text-slate-400 text-center">Jika kosong, akan menggunakan 6 gambar default dari folder assets</p>
                </div>
              )}

              {/* TENTANG KAMI */}
              {activeTab === 'about' && (
                <div className="space-y-8">
                  <SectionHeader title="Tentang Kami" icon={Users} desc="Ubah teks narasi utama di section awal." />
                  <div className="space-y-6">
                    <InputGroup label="Judul Utama" value={content.about.title} onChange={(v) => updateAbout('title', v)} placeholder="Apa itu Outbound?" />
                    <div className="space-y-3">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi</label>
                      <textarea
                        value={content.about.description}
                        onChange={(e) => updateAbout('description', e.target.value)}
                        className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-700 min-h-[120px]"
                        placeholder="Masukkan penjelasan outbound..."
                      />
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div className="space-y-3">
                        <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Visi</label>
                        <textarea
                          value={content.about.vision}
                          onChange={(e) => updateAbout('vision', e.target.value)}
                          className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-700 min-h-[100px]"
                        />
                      </div>
                      <div className="space-y-3">
                        <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Misi</label>
                        <textarea
                          value={content.about.mission}
                          onChange={(e) => updateAbout('mission', e.target.value)}
                          className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-700 min-h-[100px]"
                        />
                      </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-3xl">
                      <InputGroup label="Label Statistik" value={content.about.statsLabel} onChange={(v) => updateAbout('statsLabel', v)} placeholder="Tahun Pengalaman" />
                      <InputGroup label="Value Statistik" value={content.about.statsValue} onChange={(v) => updateAbout('statsValue', v)} placeholder="12+" />
                    </div>
                  </div>
                </div>
              )}

              {/* LAYANAN */}
              {activeTab === 'services' && (
                <div className="space-y-8">
                  <SectionHeader title="Manajemen Layanan" icon={Sparkles} desc="Kelola kartu layanan (Team Building, Fun Games, dll)." />
                  <div className="space-y-4">
                    {content.services.map((svc: ServiceItem, idx: number) => (
                      <div key={idx} className="p-6 bg-slate-50 rounded-3xl border border-slate-100 flex flex-col gap-4 relative">
                        <button 
                          onClick={() => removeItem('services', idx)}
                          className="absolute -top-3 -right-3 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center hover:bg-rose-600 transition-colors shadow-lg"
                        >
                          <Trash2 size={14} />
                        </button>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <InputGroup label="Judul Layanan" value={svc.title} onChange={(v) => updateItem('services', idx, 'title', v)} placeholder="Team Building" />
                          <InputGroup label="Tagline" value={svc.desc} onChange={(v) => updateItem('services', idx, 'desc', v)} placeholder="Sinergi Personil" />
                        </div>
                        <ImageUpload 
                          label="Gambar Service" 
                          value={svc.image} 
                          onChange={(v) => updateItem('services', idx, 'image', v)} 
                          aspectRatio="square"
                        />
                        <div className="space-y-3">
                          <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan Detail</label>
                          <textarea
                            value={svc.detail}
                            onChange={(e) => updateItem('services', idx, 'detail', e.target.value)}
                            className="w-full p-4 bg-white border border-slate-100 rounded-xl focus:ring-2 focus:ring-toba-green font-medium text-slate-700 min-h-[80px]"
                          />
                        </div>
                      </div>
                    ))}
                    <button 
                      onClick={() => addItem('services', { title: '', desc: '', image: '', detail: '', icon: 'Users' })}
                      className="w-full py-6 border-2 border-dashed border-slate-200 rounded-3xl text-slate-400 font-bold hover:border-toba-green hover:text-toba-green transition-all flex items-center justify-center gap-2"
                    >
                      <Plus size={20} /> Tambah Layanan Baru
                    </button>
                  </div>
                </div>
              )}

              {/* VIDEO */}
              {activeTab === 'videos' && (
                <div className="space-y-8">
                  <SectionHeader title="Video Highlights" icon={Video} desc="URL YouTube Embed (embed/ID)." />
                  <div className="space-y-4">
                    {content.videos.map((vid: VideoItem, idx: number) => (
                      <div key={idx} className="flex gap-4 p-4 bg-slate-50 rounded-2xl items-center">
                        <div className="flex-1 grid grid-cols-2 gap-4">
                          <input
                            value={vid.title}
                            onChange={(e) => updateItem('videos', idx, 'title', e.target.value)}
                            placeholder="Judul Video"
                            className="px-4 py-3 bg-white border border-slate-100 rounded-xl"
                          />
                          <input
                            value={vid.url}
                            onChange={(e) => updateItem('videos', idx, 'url', e.target.value)}
                            placeholder="YouTube Embed URL"
                            className="px-4 py-3 bg-white border border-slate-100 rounded-xl"
                          />
                        </div>
                        <button onClick={() => removeItem('videos', idx)} className="p-3 text-rose-500 hover:bg-rose-50 rounded-xl">
                          <Trash2 size={18} />
                        </button>
                      </div>
                    ))}
                    <button 
                      onClick={() => addItem('videos', { title: '', url: '' })}
                      className="w-full py-4 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 font-bold hover:border-toba-green hover:text-toba-green transition-all"
                    >
                      + Tambah Video
                    </button>
                  </div>
                </div>
              )}

              {/* LOCATIONS */}
              {activeTab === 'locations' && (
                <div className="space-y-8">
                  <SectionHeader title="Lokasi Outbound" icon={MapPin} desc="Daftar resort/venue partner." />
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {content.locations.map((loc: LocationItem, idx: number) => (
                      <div key={idx} className="p-5 bg-slate-50 rounded-3xl border border-slate-200 group relative">
                        <button onClick={() => removeItem('locations', idx)} className="absolute -top-2 -right-2 w-6 h-6 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-10">
                          <Trash2 size={12} />
                        </button>
                        <div className="space-y-3">
                          <input
                            value={loc.title}
                            onChange={(e) => updateItem('locations', idx, 'title', e.target.value)}
                            placeholder="Nama Resort"
                            className="w-full px-4 py-2 bg-white border border-slate-100 rounded-lg text-sm font-bold"
                          />
                          <ImageUpload 
                            label="Gambar Lokasi" 
                            value={loc.img} 
                            onChange={(v) => updateItem('locations', idx, 'img', v)} 
                            aspectRatio="video"
                          />
                        </div>
                      </div>
                    ))}
                  </div>
                  <button 
                    onClick={() => addItem('locations', { title: '', img: '' })}
                    className="w-full py-4 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 font-bold hover:border-toba-green hover:text-toba-green transition-all"
                  >
                    + Tambah Lokasi
                  </button>
                </div>
              )}

              {/* GALLERY */}
              {activeTab === 'gallery' && (
                <div className="space-y-8">
                  <SectionHeader title="Dokumentasi Galeri" icon={ImageIcon} desc="Kumpulan foto dokumentasi kegiatan outbound." />
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {content.gallery.map((img: string, idx: number) => (
                      <div key={idx} className="relative">
                        <ImageUpload 
                          label={`Foto ${idx + 1}`}
                          value={img} 
                          onChange={(v) => {
                            const newList = [...content.gallery];
                            newList[idx] = v;
                            setContent({ ...content, gallery: newList });
                          }} 
                          aspectRatio="square"
                        />
                        <button 
                          onClick={() => {
                            const newList = [...content.gallery];
                            newList.splice(idx, 1);
                            setContent({ ...content, gallery: newList });
                          }}
                          className="absolute -top-2 -right-2 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-rose-600 transition-colors z-20"
                        >
                          <Trash2 size={14} />
                        </button>
                      </div>
                    ))}
                  </div>
                  <button 
                    onClick={() => setContent({ ...content, gallery: [...content.gallery, ''] })}
                    className="w-full py-6 border-2 border-dashed border-slate-200 rounded-3xl text-slate-400 font-bold hover:text-toba-green hover:border-toba-green transition-all flex items-center justify-center gap-2"
                  >
                    <Plus size={20} /> Tambah Foto Galeri
                  </button>
                </div>
              )}
            </motion.div>
          </AnimatePresence>SectionHeader
        </div>
      </div>
    </div>
  );
}

function SectionHeader({ title, icon: Icon, desc }: { title: string; icon: React.ComponentType<{ size?: number }>; desc: string }) {
  return (
    <div className="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
      <div className="w-14 h-14 bg-slate-900 text-white rounded-[1.25rem] flex items-center justify-center shadow-xl shadow-slate-200">
        <Icon size={24} />
      </div>
      <div>
        <h3 className="text-2xl font-black text-slate-900 tracking-tight leading-none mb-2">{title}</h3>
        <p className="text-slate-400 font-medium text-sm">{desc}</p>
      </div>
    </div>
  );
}

function InputGroup({ label, value, onChange, placeholder }: { label: string; value?: string; onChange: (value: string) => void; placeholder?: string }) {
  return (
    <div className="space-y-2">
      <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">{label}</label>
      <input
        value={value || ''}
        onChange={(e) => onChange(e.target.value)}
        className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 placeholder:font-medium transition-all"
        placeholder={placeholder}
      />
    </div>
  );
}
