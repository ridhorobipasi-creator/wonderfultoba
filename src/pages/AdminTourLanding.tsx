"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { 
  Users, Sparkles, Video, MapPin, ImageIcon, Save, Plus, Trash2, 
  ArrowLeft, Layout, Type, List, MonitorPlay, Globe, Star, Shield, Clock, Heart, Award, Compass, MessageSquare, Quote,
  Package, BookOpen, Image as GalleryIcon, ChevronDown, ChevronUp, Search, X, Check
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
  link?: string;
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

interface GalleryItem {
  image: string;
  caption: string;
  category: string;
}

interface CtaContent {
  title: string;
  subtitle: string;
  backgroundImage: string;
  buttonText: string;
  buttonLink: string;
}

interface ContactContent {
  phone: string;
  whatsapp: string;
  email: string;
  address: string;
  mapEmbed: string;
}

interface TourLandingContent {
  hero: HeroContent;
  slider: SliderItem[];
  whyUs: WhyUsContent;
  whyUsImages: string[];  // 3 gambar untuk section "Mengapa Kami"
  testimonials: TestimonialItem[];
  stats: StatItem[];
  gallery: GalleryItem[];
  cta: CtaContent;
  contact: ContactContent;
}

export default function AdminTourLanding() {
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [activeTab, setActiveTab] = useState('hero');
  const [content, setContent] = useState<TourLandingContent>({
    hero: { title: '', subtitle: '', image: '' },
    slider: [],
    whyUs: { title: '', items: [] },
    whyUsImages: ['', '', ''],
    testimonials: [],
    stats: [],
    gallery: [],
    cta: {
      title: 'Siap Menjelajahi Keindahan Sumut?',
      subtitle: 'Bergabunglah dengan ribuan wisatawan yang telah merasakan keajaiban Sumatera Utara bersama kami.',
      backgroundImage: '',
      buttonText: 'Lihat Paket Wisata',
      buttonLink: '/tour/packages',
    },
    contact: {
      phone: '+62 813-2388-8207',
      whatsapp: '6281323888207',
      email: 'tour@wonderfultoba.com',
      address: 'Medan, Sumatera Utara, Indonesia',
      mapEmbed: '',
    },
  });

  const router = useRouter();

  // Import picker state
  const [showImportPicker, setShowImportPicker] = useState(false);
  const [importSource, setImportSource] = useState<'package' | 'blog' | 'gallery'>('package');
  const [importItems, setImportItems] = useState<any[]>([]);
  const [importLoading, setImportLoading] = useState(false);
  const [importSearch, setImportSearch] = useState('');
  const [importTargetIdx, setImportTargetIdx] = useState<number | null>(null);

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
        if (!data.whyUsImages) data.whyUsImages = ['', '', ''];
        if (!data.testimonials) data.testimonials = [];
        if (!data.stats) data.stats = [];
        if (!data.gallery) data.gallery = [];
        if (!data.cta) data.cta = { title: '', subtitle: '', backgroundImage: '', buttonText: '', buttonLink: '' };
        if (!data.contact) data.contact = { phone: '', whatsapp: '', email: '', address: '', mapEmbed: '' };
        
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

  const fetchImportItems = async (source: 'package' | 'blog' | 'gallery') => {
    setImportLoading(true);
    setImportItems([]);
    try {
      let url = '';
      if (source === 'package') url = '/api/packages?status=all';
      else if (source === 'blog') url = '/api/blogs';
      else if (source === 'gallery') url = '/api/gallery';
      const res = await api.get(url);
      // packages & blogs return array directly, gallery too
      const data = Array.isArray(res.data) ? res.data : (res.data?.data || []);
      setImportItems(data);
    } catch {
      toast.error('Gagal memuat data');
    } finally {
      setImportLoading(false);
    }
  };

  const openImportPicker = (idx: number) => {
    setImportTargetIdx(idx);
    setImportSource('package');
    setImportSearch('');
    fetchImportItems('package');
    setShowImportPicker(true);
  };

  const handleImportItem = (item: any) => {
    if (importTargetIdx === null) return;
    let patch: Partial<SliderItem> = {};

    if (importSource === 'package') {
      const imgs = Array.isArray(item.images) ? item.images : [];
      patch = {
        title: item.name || '',
        region: item.locationTag || item.city?.name || '',
        description: item.shortDescription || '',
        image: imgs[0] || '',
        cardImage: imgs[1] || imgs[0] || '',
        duration: item.duration || '',
        price: typeof item.price === 'number' ? item.price : parseInt(item.price) || 0,
        link: item.slug ? `/tour/package/${item.slug}` : '/tour/packages'
      };
    } else if (importSource === 'blog') {
      patch = {
        title: item.title || '',
        region: 'Blog',
        description: item.excerpt || '',
        image: item.image || '',
        cardImage: item.image || '',
        duration: '',
        price: 0,
        link: item.slug ? `/tour/blog/${item.slug}` : '/tour/blog'
      };
    } else if (importSource === 'gallery') {
      patch = {
        title: item.caption || 'Galeri',
        region: item.category || '',
        description: '',
        image: item.imageUrl || '',
        cardImage: item.imageUrl || '',
        duration: '',
        price: 0,
        link: '/tour/gallery'
      };
    }

    const newSlider = [...content.slider];
    newSlider[importTargetIdx] = { ...newSlider[importTargetIdx], ...patch };
    setContent({ ...content, slider: newSlider });
    setShowImportPicker(false);
    toast.success('Data berhasil diimport ke slide');
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
    { id: 'gallery', label: 'Galeri Foto', icon: GalleryIcon },
    { id: 'testimonials', label: 'Testimoni', icon: MessageSquare },
    { id: 'stats', label: 'Statistik', icon: Award },
    { id: 'cta', label: 'CTA Section', icon: Compass },
    { id: 'contact', label: 'Kontak', icon: Globe },
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
                  <SectionHeader title="Slider Beranda" icon={ImageIcon} desc="Kelola slide yang berputar di halaman utama. Upload gambar dari PC/HP atau import dari Paket, Blog, atau Galeri." />
                  <div className="space-y-6">
                    {content.slider.map((slide: SliderItem, idx: number) => (
                      <div key={idx} className="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative group">
                        {/* Slide number badge */}
                        <div className="flex items-center justify-between mb-4">
                          <span className="text-xs font-black text-slate-400 uppercase tracking-widest">Slide {idx + 1}</span>
                          <div className="flex items-center gap-2">
                            {/* Import button */}
                            <button
                              onClick={() => openImportPicker(idx)}
                              className="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-xl font-bold text-xs hover:bg-blue-100 transition-all"
                            >
                              <Package size={13} />
                              Import dari Paket/Blog/Galeri
                            </button>
                            <button 
                              onClick={() => removeItem('slider', idx)}
                              className="w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center hover:bg-rose-600 transition-colors shadow-lg"
                            >
                              <Trash2 size={14} />
                            </button>
                          </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                          <InputGroup label="Judul Slide" value={slide.title} onChange={(v: string) => updateItem('slider', idx, 'title', v)} placeholder="Danau Toba" />
                          <InputGroup label="Wilayah/Region" value={slide.region} onChange={(v: string) => updateItem('slider', idx, 'region', v)} placeholder="Tobasa, Sumatera Utara" />
                        </div>
                        <InputGroup label="Deskripsi" value={slide.description} onChange={(v: string) => updateItem('slider', idx, 'description', v)} placeholder="Jelajahi keajaiban..." />
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <ImageUpload 
                            label="Gambar Background (Upload dari PC/HP)" 
                            value={slide.image} 
                            onChange={(v: string) => updateItem('slider', idx, 'image', v)} 
                            aspectRatio="wide"
                          />
                          <ImageUpload 
                            label="Gambar Kartu (Upload dari PC/HP)" 
                            value={slide.cardImage} 
                            onChange={(v: string) => updateItem('slider', idx, 'cardImage', v)} 
                            aspectRatio="video"
                          />
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                          <InputGroup label="Durasi" value={slide.duration} onChange={(v: string) => updateItem('slider', idx, 'duration', v)} placeholder="4 Hari 3 Malam" />
                          <InputGroupNumber label="Harga Mulai (Rp)" value={slide.price} onChange={(v: number) => updateItem('slider', idx, 'price', v)} placeholder="3500000" />
                          <InputGroup label="Link" value={slide.link} onChange={(v: string) => updateItem('slider', idx, 'link', v)} placeholder="/tour/packages" />
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

                    {/* Gambar section Why Us */}
                    <div className="space-y-3 pt-4 border-t border-slate-50">
                      <h4 className="text-sm font-black text-slate-900 uppercase tracking-widest px-1">3 Gambar Kolase (Section Mengapa Kami)</h4>
                      <p className="text-xs text-slate-400 px-1">Gambar yang tampil di sebelah kiri section "Mengapa Kami" di beranda</p>
                      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {[0, 1, 2].map(i => (
                          <ImageUpload
                            key={i}
                            label={i === 0 ? 'Gambar Utama (Besar)' : `Gambar ${i + 1}`}
                            value={(content.whyUsImages || [])[i] || ''}
                            onChange={(v) => {
                              const imgs = [...(content.whyUsImages || ['', '', ''])];
                              imgs[i] = v;
                              setContent({ ...content, whyUsImages: imgs });
                            }}
                            aspectRatio={i === 0 ? 'portrait' : 'video'}
                          />
                        ))}
                      </div>
                    </div>
                    
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

              {/* GALERI FOTO */}
              {activeTab === 'gallery' && (
                <div className="space-y-8">
                  <SectionHeader title="Galeri Foto Tour" icon={GalleryIcon} desc="Foto-foto yang tampil di halaman galeri /tour/gallery. Upload dari PC/HP, auto-convert ke WebP." />
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {(content.gallery || []).map((item: GalleryItem, idx: number) => (
                      <div key={idx} className="p-4 bg-slate-50 rounded-2xl border border-slate-100 relative group">
                        <button
                          onClick={() => {
                            const newList = [...content.gallery];
                            newList.splice(idx, 1);
                            setContent({ ...content, gallery: newList });
                          }}
                          className="absolute -top-2 -right-2 w-7 h-7 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-10"
                        >
                          <Trash2 size={12} />
                        </button>
                        <ImageUpload
                          label={`Foto ${idx + 1}`}
                          value={item.image}
                          onChange={(v) => {
                            const newList = [...content.gallery];
                            newList[idx] = { ...newList[idx], image: v };
                            setContent({ ...content, gallery: newList });
                          }}
                          aspectRatio="video"
                        />
                        <div className="mt-3 grid grid-cols-2 gap-2">
                          <InputGroup
                            label="Caption"
                            value={item.caption}
                            onChange={(v) => {
                              const newList = [...content.gallery];
                              newList[idx] = { ...newList[idx], caption: v };
                              setContent({ ...content, gallery: newList });
                            }}
                            placeholder="Danau Toba dari Ketinggian"
                          />
                          <InputGroup
                            label="Kategori"
                            value={item.category}
                            onChange={(v) => {
                              const newList = [...content.gallery];
                              newList[idx] = { ...newList[idx], category: v };
                              setContent({ ...content, gallery: newList });
                            }}
                            placeholder="Danau Toba"
                          />
                        </div>
                      </div>
                    ))}
                  </div>
                  <button
                    onClick={() => setContent({ ...content, gallery: [...(content.gallery || []), { image: '', caption: '', category: '' }] })}
                    className="w-full py-6 border-2 border-dashed border-slate-200 rounded-3xl text-slate-400 font-bold hover:text-toba-green hover:border-toba-green transition-all flex items-center justify-center gap-2"
                  >
                    <Plus size={20} /> Tambah Foto Galeri
                  </button>
                  <div className="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                    <p className="text-sm text-blue-700 font-medium">
                      💡 Foto galeri juga bisa dikelola di menu <strong>Galeri Foto</strong> (KONTEN OUTBOUND). Halaman publik galeri tour ada di <code className="bg-blue-100 px-1.5 py-0.5 rounded">/tour/gallery</code>
                    </p>
                  </div>
                </div>
              )}

              {/* CTA SECTION */}
              {activeTab === 'cta' && (
                <div className="space-y-8">
                  <SectionHeader title="CTA Section" icon={Compass} desc="Bagian ajakan bertindak di bagian bawah halaman beranda tour." />
                  <div className="space-y-6">
                    <InputGroup label="Judul CTA" value={content.cta?.title} onChange={(v) => setContent({ ...content, cta: { ...content.cta, title: v } })} placeholder="Siap Menjelajahi Keindahan Sumut?" />
                    <div className="space-y-2">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Sub-judul</label>
                      <textarea
                        value={content.cta?.subtitle || ''}
                        onChange={(e) => setContent({ ...content, cta: { ...content.cta, subtitle: e.target.value } })}
                        rows={2}
                        className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-700"
                        placeholder="Bergabunglah dengan ribuan wisatawan..."
                      />
                    </div>
                    <ImageUpload
                      label="Gambar Background CTA"
                      value={content.cta?.backgroundImage}
                      onChange={(v) => setContent({ ...content, cta: { ...content.cta, backgroundImage: v } })}
                      aspectRatio="wide"
                    />
                    <div className="grid grid-cols-2 gap-4">
                      <InputGroup label="Teks Tombol" value={content.cta?.buttonText} onChange={(v) => setContent({ ...content, cta: { ...content.cta, buttonText: v } })} placeholder="Lihat Paket Wisata" />
                      <InputGroup label="Link Tombol" value={content.cta?.buttonLink} onChange={(v) => setContent({ ...content, cta: { ...content.cta, buttonLink: v } })} placeholder="/tour/packages" />
                    </div>
                  </div>
                </div>
              )}

              {/* KONTAK */}
              {activeTab === 'contact' && (
                <div className="space-y-8">
                  <SectionHeader title="Informasi Kontak" icon={Globe} desc="Nomor telepon, WhatsApp, email, dan alamat yang tampil di website." />
                  <div className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <InputGroup label="Nomor Telepon" value={content.contact?.phone} onChange={(v) => setContent({ ...content, contact: { ...content.contact, phone: v } })} placeholder="+62 813-2388-8207" />
                      <InputGroup label="Nomor WhatsApp (tanpa +)" value={content.contact?.whatsapp} onChange={(v) => setContent({ ...content, contact: { ...content.contact, whatsapp: v } })} placeholder="6281323888207" />
                      <InputGroup label="Email" value={content.contact?.email} onChange={(v) => setContent({ ...content, contact: { ...content.contact, email: v } })} placeholder="tour@wonderfultoba.com" />
                      <InputGroup label="Alamat" value={content.contact?.address} onChange={(v) => setContent({ ...content, contact: { ...content.contact, address: v } })} placeholder="Medan, Sumatera Utara" />
                    </div>
                    <div className="space-y-2">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Google Maps Embed URL</label>
                      <textarea
                        value={content.contact?.mapEmbed || ''}
                        onChange={(e) => setContent({ ...content, contact: { ...content.contact, mapEmbed: e.target.value } })}
                        rows={3}
                        className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-700 text-sm"
                        placeholder="https://www.google.com/maps/embed?pb=..."
                      />
                      <p className="text-xs text-slate-400 ml-1">Dapatkan dari Google Maps → Share → Embed a map → Copy HTML, ambil URL dari src="..."</p>
                    </div>
                    {content.contact?.mapEmbed && (
                      <div className="rounded-2xl overflow-hidden border border-slate-200">
                        <iframe
                          src={content.contact.mapEmbed}
                          width="100%"
                          height="250"
                          style={{ border: 0 }}
                          allowFullScreen
                          loading="lazy"
                          title="Peta Lokasi"
                        />
                      </div>
                    )}
                  </div>
                </div>
              )}
            </motion.div>
          </AnimatePresence>
        </div>
      </div>

      {/* Import Picker Modal */}
      <AnimatePresence>
        {showImportPicker && (
          <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md">
            <motion.div
              initial={{ opacity: 0, scale: 0.95 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.95 }}
              className="bg-white rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]"
            >
              {/* Modal Header */}
              <div className="p-6 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div>
                  <h3 className="text-xl font-black text-slate-900">Import ke Slide</h3>
                  <p className="text-sm text-slate-400 mt-0.5">Pilih sumber data untuk mengisi slide otomatis</p>
                </div>
                <button onClick={() => setShowImportPicker(false)} className="p-2 hover:bg-slate-100 rounded-xl transition-all">
                  <X size={20} />
                </button>
              </div>

              {/* Source Tabs */}
              <div className="px-6 pt-4 flex gap-2 shrink-0">
                {([
                  { key: 'package', label: 'Paket Wisata', icon: Package },
                  { key: 'blog', label: 'Blog / Artikel', icon: BookOpen },
                  { key: 'gallery', label: 'Galeri Foto', icon: GalleryIcon },
                ] as const).map(({ key, label, icon: Icon }) => (
                  <button
                    key={key}
                    onClick={() => {
                      setImportSource(key);
                      setImportSearch('');
                      fetchImportItems(key);
                    }}
                    className={cn(
                      "flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-all",
                      importSource === key
                        ? "bg-toba-green text-white shadow-lg shadow-toba-green/20"
                        : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                    )}
                  >
                    <Icon size={15} />
                    {label}
                  </button>
                ))}
              </div>

              {/* Search */}
              <div className="px-6 pt-3 shrink-0">
                <div className="relative">
                  <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                  <input
                    type="text"
                    placeholder="Cari..."
                    value={importSearch}
                    onChange={e => setImportSearch(e.target.value)}
                    className="w-full pl-9 pr-4 py-2.5 bg-slate-50 rounded-xl text-sm font-medium border-none focus:ring-2 focus:ring-toba-green"
                  />
                </div>
              </div>

              {/* Items List */}
              <div className="flex-1 overflow-y-auto p-6 space-y-2">
                {importLoading ? (
                  <div className="text-center py-12">
                    <div className="w-8 h-8 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin mx-auto mb-3" />
                    <p className="text-slate-400 text-sm font-medium">Memuat data...</p>
                  </div>
                ) : importItems.length === 0 ? (
                  <div className="text-center py-12 text-slate-400">
                    <p className="font-medium">Tidak ada data ditemukan</p>
                  </div>
                ) : (
                  importItems
                    .filter(item => {
                      const q = importSearch.toLowerCase();
                      if (!q) return true;
                      if (importSource === 'package') return item.name?.toLowerCase().includes(q);
                      if (importSource === 'blog') return item.title?.toLowerCase().includes(q);
                      if (importSource === 'gallery') return item.caption?.toLowerCase().includes(q) || item.category?.toLowerCase().includes(q);
                      return true;
                    })
                    .map((item, i) => {
                      const img = importSource === 'package'
                        ? (Array.isArray(item.images) ? item.images[0] : '')
                        : importSource === 'blog'
                        ? item.image
                        : item.imageUrl;
                      const title = importSource === 'package' ? item.name
                        : importSource === 'blog' ? item.title
                        : item.caption || `Foto ${i + 1}`;
                      const sub = importSource === 'package'
                        ? `${item.duration || ''} • Rp ${(item.price || 0).toLocaleString('id-ID')}`
                        : importSource === 'blog'
                        ? item.excerpt?.substring(0, 60) + '...'
                        : item.category || '';

                      return (
                        <button
                          key={i}
                          onClick={() => handleImportItem(item)}
                          className="w-full flex items-center gap-4 p-3 rounded-2xl hover:bg-toba-green/5 hover:border-toba-green border-2 border-transparent transition-all text-left group"
                        >
                          <div className="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 shrink-0">
                            {img ? (
                              <img src={img} alt={title} className="w-full h-full object-cover" />
                            ) : (
                              <div className="w-full h-full flex items-center justify-center text-slate-300">
                                <ImageIcon size={24} />
                              </div>
                            )}
                          </div>
                          <div className="flex-1 min-w-0">
                            <p className="font-bold text-slate-900 truncate group-hover:text-toba-green transition-colors">{title}</p>
                            <p className="text-xs text-slate-400 truncate mt-0.5">{sub}</p>
                          </div>
                          <Check size={18} className="text-toba-green opacity-0 group-hover:opacity-100 transition-opacity shrink-0" />
                        </button>
                      );
                    })
                )}
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
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
