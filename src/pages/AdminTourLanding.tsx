"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { 
  Users, Sparkles, Video, MapPin, ImageIcon, Save, Plus, Trash2, 
  ArrowLeft, Layout, Type, List, MonitorPlay, Globe, Star, Shield, Clock, Heart, Award, Compass, MessageSquare, Quote
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { toast } from 'sonner';
import { useRouter } from 'next/navigation';
import { cn } from '../utils/cn';
import ImageUpload from '../components/ImageUpload';

interface HeroContent {
  title: string;
  subtitle: string;
  image: string;
}

interface SliderItem {
  title: string;
  region: string;
  description: string;
  image: string;
  cardImage: string;
  duration: string;
  price: number;
}

interface WhyUsItem {
  title: string;
  icon: string;
  desc: string;
}

interface WhyUsContent {
  title: string;
  items: WhyUsItem[];
}

interface TestimonialItem {
  name: string;
  role: string;
  avatar: string;
  rating: number;
  text: string;
}

interface StatItem {
  icon: string;
  value: string;
  label: string;
}

interface TourLandingContent {
  hero: HeroContent;
  slider: SliderItem[];
  whyUs: WhyUsContent;
  testimonials: TestimonialItem[];
  stats: StatItem[];
}

export default function AdminTourLanding() {
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [activeTab, setActiveTab] = useState('hero');
  const [content, setContent] = useState<TourLandingContent>({
    hero: {
      title: '',
      subtitle: '',
      image: ''
    },
    slider: [],
    whyUs: {
      title: '',
      items: []
    },
    testimonials: [],
    stats: []
  });

  const router = useRouter();

  useEffect(() => {
    fetchContent();
  }, []);

  const fetchContent = async () => {
    setLoading(true);
    try {
      const res = await api.get('/settings?key=tour_landing');
      if (res.data) {
        // Ensure structure exists
        const data = res.data as TourLandingContent;
        if (!data.hero) data.hero = { title: '', subtitle: '', image: '' };
        if (!data.slider) data.slider = [];
        if (!data.whyUs) data.whyUs = { title: '', items: [] };
        if (!data.testimonials) data.testimonials = [];
        if (!data.stats) data.stats = [];
        
        // Convert price to number if it's string (backward compatibility)
        if (data.slider && Array.isArray(data.slider)) {
          data.slider = data.slider.map(slide => ({
            ...slide,
            price: typeof slide.price === 'string' ? parseInt(slide.price) || 0 : slide.price
          }));
        }
        
        setContent(data);
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
        key: 'tour_landing',
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

  const updateSection = (section: 'hero' | 'whyUs', field: string, value: unknown) => {
    setContent({ ...content, [section]: { ...content[section], [field]: value } as HeroContent & WhyUsContent });
  };

  const addItem = (type: 'slider' | 'stats' | 'testimonials', template: SliderItem | StatItem | TestimonialItem) => {
    setContent({ ...content, [type]: [...(content[type] || []), template] });
  };

  const handleSaveWithConversion = async () => {
    setSaving(true);
    try {
      // Convert price strings to numbers for slider items
      const processedContent = {
        ...content,
        slider: content.slider.map(slide => ({
          ...slide,
          price: typeof slide.price === 'string' ? parseInt(slide.price) || 0 : slide.price
        }))
      };
      
      await api.post('/settings', {
        key: 'tour_landing',
        value: processedContent
      });
      toast.success('Konten berhasil disimpan');
    } catch (error) {
      console.error('Error saving settings:', error);
      toast.error('Gagal menyimpan konten');
    } finally {
      setSaving(false);
    }
  };

  const removeItem = (type: 'slider' | 'stats' | 'testimonials', index: number) => {
    const newList = [...content[type]];
    newList.splice(index, 1);
    setContent({ ...content, [type]: newList });
  };

  const updateItem = (type: 'slider' | 'stats' | 'testimonials', index: number, field: string, value: unknown) => {
    const newList = [...content[type]];
    newList[index] = { ...newList[index], [field]: value } as SliderItem & StatItem & TestimonialItem;
    setContent({ ...content, [type]: newList });
  };

  const tabs = [
    { id: 'hero', label: 'Hero Section', icon: Layout },
    { id: 'slider', label: 'Slider Beranda', icon: ImageIcon },
    { id: 'whyUs', label: 'Mengapa Kami', icon: Shield },
    { id: 'testimonials', label: 'Testimoni', icon: MessageSquare },
    { id: 'stats', label: 'Statistik Keberhasilan', icon: Award },
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
          <h2 className="text-4xl font-black text-slate-900 tracking-tight">Beranda <span className="text-toba-green">CMS</span></h2>
          <p className="text-slate-500 font-medium">Kelola konten utama website Tour & Travel.</p>
        </div>
        <button
          onClick={handleSaveWithConversion}
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
              {/* HERO SECTION */}
              {activeTab === 'hero' && (
                <div className="space-y-8">
                  <SectionHeader title="Hero Utama" icon={Layout} desc="Ubah tampilan utama saat website pertama kali dibuka." />
                  <div className="space-y-6">
                    <InputGroup label="Judul Hero" value={content.hero.title} onChange={(v: string) => updateSection('hero', 'title', v)} placeholder="Explore the Beauty of Lake Toba" />
                    <InputGroup label="Sub-Judul" value={content.hero.subtitle} onChange={(v: string) => updateSection('hero', 'subtitle', v)} placeholder="Nikmati pengalaman tak terlupakan bersama kami." />
                    <ImageUpload 
                      label="Gambar Hero Background" 
                      value={content.hero.image} 
                      onChange={(v: string) => updateSection('hero', 'image', v)} 
                      aspectRatio="wide"
                    />
                  </div>
                </div>
              )}

              {/* SLIDER BERANDA */}
              {activeTab === 'slider' && (
                <div className="space-y-8">
                  <SectionHeader title="Slider Beranda" icon={ImageIcon} desc="Kelola gambar dan teks slide yang berputar." />
                  <div className="space-y-6">
                    {content.slider.map((slide: SliderItem, idx: number) => (
                      <div key={idx} className="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative group">
                        <button 
                          onClick={() => removeItem('slider', idx)}
                          className="absolute -top-3 -right-3 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center hover:bg-rose-600 transition-colors shadow-lg z-10"
                        >
                          <Trash2 size={14} />
                        </button>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                          <InputGroup label="Judul Slide" value={slide.title} onChange={(v: string) => updateItem('slider', idx, 'title', v)} placeholder="Danau Toba" />
                          <InputGroup label="Wilayah/Region" value={slide.region} onChange={(v: string) => updateItem('slider', idx, 'region', v)} placeholder="Tobasa, Sumatera Utara" />
                        </div>
                        <InputGroup label="Deskripsi" value={slide.description} onChange={(v: string) => updateItem('slider', idx, 'description', v)} placeholder="Jelajahi keajaiban..." />
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <ImageUpload 
                            label="Gambar Background" 
                            value={slide.image} 
                            onChange={(v: string) => updateItem('slider', idx, 'image', v)} 
                            aspectRatio="wide"
                          />
                          <ImageUpload 
                            label="Gambar Kartu" 
                            value={slide.cardImage} 
                            onChange={(v: string) => updateItem('slider', idx, 'cardImage', v)} 
                            aspectRatio="video"
                          />
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <InputGroup label="Durasi" value={slide.duration} onChange={(v: string) => updateItem('slider', idx, 'duration', v)} placeholder="4 Hari 3 Malam" />
                          <InputGroupNumber label="Harga Mulai (Rp)" value={slide.price} onChange={(v: number) => updateItem('slider', idx, 'price', v)} placeholder="3500000" />
                        </div>
                      </div>
                    ))}
                    <button 
                      onClick={() => addItem('slider', { title: '', region: '', description: '', image: '', cardImage: '', duration: '', price: 0 })}
                      className="w-full py-6 border-2 border-dashed border-slate-200 rounded-3xl text-slate-400 font-bold hover:border-toba-green hover:text-toba-green transition-all flex items-center justify-center gap-2"
                    >
                      <Plus size={20} /> Tambah Slide Baru
                    </button>
                  </div>
                </div>
              )}

              {/* MENGAPA KAMI */}
              {activeTab === 'whyUs' && (
                <div className="space-y-8">
                  <SectionHeader title="Mengapa Memilih Kami" icon={Shield} desc="Ubah alasan mengapa pelanggan harus memilih Anda." />
                  <div className="space-y-6">
                    <InputGroup label="Judul Utama Section" value={content.whyUs.title} onChange={(v: string) => updateSection('whyUs', 'title', v)} placeholder="Pengalaman Wisata Terbaik di Sumut" />
                    
                    <div className="space-y-4 pt-4 border-t border-slate-50">
                      <h4 className="text-sm font-black text-slate-900 uppercase tracking-widest px-1">Daftar Poin Keunggulan</h4>
                      {content.whyUs.items.map((item: WhyUsItem, idx: number) => (
                        <div key={idx} className="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex gap-4 items-start relative">
                          <button onClick={() => {
                            const newItems = [...content.whyUs.items];
                            newItems.splice(idx, 1);
                            updateSection('whyUs', 'items', newItems);
                          }} className="absolute -top-2 -right-2 bg-rose-500 text-white p-1.5 rounded-full shadow-md">
                            <Trash2 size={12} />
                          </button>
                          <div className="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <InputGroup label="Judul" value={item.title} onChange={(v: string) => {
                              const newItems = [...content.whyUs.items];
                              newItems[idx].title = v;
                              updateSection('whyUs', 'items', newItems);
                            }} />
                            <InputGroup label="Ikon (Lucide Name)" value={item.icon} onChange={(v: string) => {
                              const newItems = [...content.whyUs.items];
                              newItems[idx].icon = v;
                              updateSection('whyUs', 'items', newItems);
                            }} placeholder="Shield, Clock, Globe" />
                            <div className="md:col-span-2 space-y-2">
                              <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi Singkat</label>
                              <textarea
                                value={item.desc}
                                onChange={(e) => {
                                  const newItems = [...content.whyUs.items];
                                  newItems[idx].desc = e.target.value;
                                  updateSection('whyUs', 'items', newItems);
                                }}
                                className="w-full p-4 bg-white border border-slate-100 rounded-xl text-sm font-medium"
                              />
                            </div>
                          </div>
                        </div>
                      ))}
                      <button 
                        onClick={() => {
                          const newItems = [...(content.whyUs.items || []), { title: '', icon: 'Shield', desc: '' }];
                          updateSection('whyUs', 'items', newItems);
                        }}
                        className="w-full py-4 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 font-bold hover:text-toba-green hover:border-toba-green transition-all"
                      >
                        + Tambah Poin
                      </button>
                    </div>
                  </div>
                </div>
              )}

              
              {/* STATS SECTION */}
              {activeTab === 'stats' && (
                <div className="space-y-8">
                  <SectionHeader title="Statistik Keberhasilan" icon={Award} desc="Tampilkan pencapaian perusahaan (misal: 1000+ Trip Selesai)." />
                  <div className="space-y-6">
                    {(content.stats || []).map((s: StatItem, idx: number) => (
                      <div key={idx} className="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button 
                          onClick={() => removeItem('stats', idx)}
                          className="absolute -top-3 -right-3 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg"
                        >
                          <Trash2 size={14} />
                        </button>
                        <InputGroup label="Ikon (Lucide)" value={s.icon} onChange={(v: string) => updateItem('stats', idx, 'icon', v)} placeholder="Users, Star, MapPin" />
                        <InputGroup label="Nilai (Value)" value={s.value} onChange={(v: string) => updateItem('stats', idx, 'value', v)} placeholder="500+" />
                        <InputGroup label="Label" value={s.label} onChange={(v: string) => updateItem('stats', idx, 'label', v)} placeholder="Wisatawan Puas" />
                      </div>
                    ))}
                    <button 
                      onClick={() => addItem('stats', { icon: 'Users', value: '', label: '' })}
                      className="w-full py-6 border-2 border-dashed border-slate-200 rounded-3xl text-slate-400 font-bold hover:text-toba-green hover:border-toba-green transition-all flex items-center justify-center gap-2"
                    >
                      <Plus size={20} /> Tambah Statistik Baru
                    </button>
                  </div>
                </div>
              )}
              {activeTab === 'testimonials' && (
                <div className="space-y-8">
                  <SectionHeader title="Testimoni Pelanggan" icon={MessageSquare} desc="Testimoni asli dari wisatawan." />
                  <div className="space-y-6">
                    {content.testimonials.map((t: TestimonialItem, idx: number) => (
                      <div key={idx} className="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative">
                        <button 
                          onClick={() => removeItem('testimonials', idx)}
                          className="absolute -top-3 -right-3 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg"
                        >
                          <Trash2 size={14} />
                        </button>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <InputGroup label="Nama Pelanggan" value={t.name} onChange={(v: string) => updateItem('testimonials', idx, 'name', v)} />
                          <InputGroup label="Peran / Role" value={t.role} onChange={(v: string) => updateItem('testimonials', idx, 'role', v)} placeholder="Traveler dari Jakarta" />
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <ImageUpload 
                            label="Foto Avatar" 
                            value={t.avatar} 
                            onChange={(v: string) => updateItem('testimonials', idx, 'avatar', v)} 
                            aspectRatio="square"
                          />
                          <InputGroup label="Rating (1-5)" value={t.rating} onChange={(v: string) => updateItem('testimonials', idx, 'rating', parseInt(v))} />
                        </div>
                        <div className="space-y-2 mt-4">
                          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Isi Testimoni</label>
                          <textarea
                            value={t.text}
                            onChange={(e) => updateItem('testimonials', idx, 'text', e.target.value)}
                            className="w-full p-5 bg-white border border-slate-100 rounded-2xl text-slate-700 font-medium min-h-[100px]"
                          />
                        </div>
                      </div>
                    ))}
                    <button 
                      onClick={() => addItem('testimonials', { name: '', role: '', avatar: '', rating: 5, text: '' })}
                      className="w-full py-6 border-2 border-dashed border-slate-200 rounded-3xl text-slate-400 font-bold hover:text-toba-green hover:border-toba-green transition-all flex items-center justify-center gap-2"
                    >
                      <Plus size={20} /> Tambah Testimoni Baru
                    </button>
                  </div>
                </div>
              )}
            </motion.div>
          </AnimatePresence>
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

function InputGroup({ label, value, onChange, placeholder }: { label: string; value?: string | number; onChange: (value: string) => void; placeholder?: string }) {
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

function InputGroupNumber({ label, value, onChange, placeholder }: { label: string; value?: number; onChange: (value: number) => void; placeholder?: string }) {
  return (
    <div className="space-y-2">
      <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">{label}</label>
      <input
        type="number"
        value={value || ''}
        onChange={(e) => onChange(parseInt(e.target.value) || 0)}
        className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 placeholder:font-medium transition-all"
        placeholder={placeholder}
      />
    </div>
  );
}
