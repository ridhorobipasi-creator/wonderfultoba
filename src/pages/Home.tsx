"use client";

import { useState, useEffect, type ComponentType } from 'react';
import { motion } from 'framer-motion';
import HeroSlider from '../components/HeroSlider';
import * as LucideIcons from 'lucide-react';
import { MapPin, Star, ArrowRight, Shield, Clock, Globe, Award, CheckCircle, MessageSquare } from 'lucide-react';
import Link from 'next/link';
import { Helmet } from 'react-helmet-async';
import api from '../lib/api';
import { mockTours, mockBlogs, mockSettings, mockStats } from '../data/mockData';
// import { fallbackPackages, fallbackBlogs } from '../utils/fallbackData';

type IconComponent = ComponentType<{ size?: number; className?: string; strokeWidth?: number }>;

interface SliderItem {
  id: number;
  region: string;
  title: string;
  description: string;
  duration: string;
  price: number;
  image: string;
  cardImage: string;
}

interface FeaturedPackage {
  id: string | number;
  slug: string;
  name: string;
  price: number;
  duration: string;
  rating?: number;
  image?: string;
  images?: string[];
  locationTag?: string;
  shortDescription?: string;
  description?: string;
}

interface HomeStats {
  packages: number;
  happyClients: number;
}

interface WhyUsItem {
  icon: string | IconComponent;
  title: string;
  desc: string;
  color?: string;
}

interface Testimonial {
  name: string;
  role: string;
  text: string;
  rating: number;
  avatar?: string;
}

interface LandingData {
  hero?: { title?: string; subtitle?: string; };
  slider?: SliderItem[];
  whyUs?: { title?: string; items?: WhyUsItem[]; };
  whyUsImages?: string[];
  testimonials?: Testimonial[];
  cta?: { title?: string; subtitle?: string; backgroundImage?: string; buttonText?: string; buttonLink?: string; };
  contact?: { phone?: string; whatsapp?: string; email?: string; address?: string; };
}

const lucideIcons: Record<string, IconComponent> = LucideIcons as unknown as Record<string, IconComponent>;

// testimonials dummy dihapus, semua dari API/settings

export default function Home() {
  const [featuredPackages, setFeaturedPackages] = useState<FeaturedPackage[]>([]);
  const [loading, setLoading] = useState(true);
  const [landingData, setLandingData] = useState<LandingData | null>(null);
  const [stats, setStats] = useState<HomeStats | null>(null);
  const [blogs, setBlogs] = useState<any[]>([]);
  const [error, setError] = useState<string | null>(null);
  const [waNumber, setWaNumber] = useState('6281323888207');

// ...
  useEffect(() => {
    const fetchAll = async () => {
      try {
        const [pkgRes, landingRes, statsRes, blogRes] = await Promise.all([
          api.get('/packages?status=active'),
          api.get('/settings?key=tour_landing'),
          api.get('/stats'),
          api.get('/blogs?category=tour'),
        ]);
        
        const pkgData = Array.isArray(pkgRes.data) ? pkgRes.data.filter((p: any) => !p.isOutbound).slice(0, 3) : [];
        setFeaturedPackages(pkgData.length > 0 ? pkgData : mockTours.filter(p => !p.isOutbound).slice(0, 3));
        
        setLandingData(landingRes.data || mockSettings.tour_landing);
        setStats(statsRes.data || mockStats);
        
        const blogData = Array.isArray(blogRes.data) ? blogRes.data.slice(0, 3) : [];
        setBlogs(blogData.length > 0 ? blogData : mockBlogs.slice(0, 3));

        if (landingRes.data?.contact?.whatsapp) {
          setWaNumber(landingRes.data.contact.whatsapp);
        }
      } catch (err: any) {
        console.error('Home Page fetch failed, using fallbacks:', err);
        setFeaturedPackages(mockTours.filter(p => !p.isOutbound).slice(0, 3));
        setLandingData(mockSettings.tour_landing as any);
        setStats(mockStats);
        setBlogs(mockBlogs.slice(0, 3));
        // setError('Gagal memuat data utama.');
      } finally {
        setLoading(false);
      }
    };
    fetchAll();
  }, []);
  if (loading) {
    return <div className="flex flex-col items-center justify-center min-h-[400px] py-32"><div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin mb-6" /><p className="text-slate-400 font-medium">Menyiapkan perjalanan premium...</p></div>;
  }
  if (error) {
    return <div className="flex flex-col items-center justify-center min-h-[400px] py-32"><p className="text-red-500 font-bold text-lg">{error}</p></div>;
  }
  return (
    <div className="bg-white">
      <Helmet>
        <title>{landingData?.hero?.title || 'Wonderful Toba – Wisata Sumatera Utara'}</title>
        <meta name="description" content={landingData?.hero?.subtitle || 'Temukan keindahan Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang bersama Wonderful Toba. Paket wisata terpercaya di Sumatera Utara.'} />
      </Helmet>
      <HeroSlider destinations={landingData?.slider} />

      {/* Featured Packages */}
      <section className="py-24">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div className="max-w-2xl">
              <div className="flex items-center space-x-2 mb-4">
                <div className="h-px w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Destinasi Unggulan</span>
              </div>
              <h2 className="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                Paket Wisata <span className="text-toba-green">Terpopuler</span>
              </h2>
            </div>
            <Link href="/tour/packages" className="flex items-center space-x-3 text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0">
              <span>Lihat Semua</span>
              <div className="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all">
                <ArrowRight size={18} />
              </div>
            </Link>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {featuredPackages.map((pkg, i) => (
              <motion.div key={pkg.id} initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="group cursor-pointer">
                <div className="relative h-[420px] rounded-[2rem] overflow-hidden mb-6 premium-shadow">
                  <img src={pkg.images?.[0] || pkg.image} alt={pkg.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" referrerPolicy="no-referrer" />
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent" />
                  <div className="absolute top-6 left-6">
                    <div className="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
                      <Star size={13} className="text-amber-400 fill-amber-400" />
                      <span className="font-black text-slate-900 text-xs">{pkg.rating}</span>
                    </div>
                  </div>
                  <div className="absolute bottom-6 left-6 right-6">
                    <div className="flex items-center text-toba-accent text-[10px] font-black uppercase tracking-[0.2em] mb-2">
                      <MapPin size={11} className="mr-1.5" />{pkg.locationTag || 'Sumatera Utara'}
                    </div>
                    <h3 className="text-2xl font-black text-white mb-2 tracking-tight leading-tight">{pkg.name}</h3>
                    <p className="text-slate-300 text-xs font-medium mb-4 line-clamp-2">{pkg.shortDescription || pkg.description?.slice(0, 100)}</p>
                    <div className="flex justify-between items-end">
                      <div>
                        <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">Durasi</p>
                        <p className="text-white font-bold text-sm">{pkg.duration}</p>
                      </div>
                      <div className="text-right">
                        <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">Mulai Dari</p>
                        <p className="text-xl font-black text-white">
                          <span className="text-xs font-bold text-toba-accent mr-1">Rp</span>
                          {pkg.price.toLocaleString('id-ID')}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <Link href={`/tour/package/${pkg.slug}`} className="flex items-center justify-center gap-2 w-full py-3.5 bg-slate-50 hover:bg-toba-green hover:text-white text-slate-700 rounded-2xl font-bold text-sm transition-all duration-300 group-hover:bg-toba-green group-hover:text-white">
                  Lihat Detail <ArrowRight size={16} />
                </Link>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Why Us Section */}
      <section className="py-24 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div className="relative">
              <div className="grid grid-cols-2 gap-4">
                <img
                  src={(landingData as any)?.whyUsImages?.[0] || '/assets/images/2023/10/001-1.jpg'}
                  alt="Lake Toba View"
                  className="rounded-[2.5rem] shadow-2xl w-full h-[450px] object-cover"
                />
                <div className="space-y-4 pt-12">
                  <img
                    src={(landingData as any)?.whyUsImages?.[1] || '/assets/images/2023/10/15-Bukit-Lawang-wonderfultoba_outbound-outbound_medan.jpg'}
                    alt="Bukit Lawang"
                    className="rounded-[1.5rem] shadow-xl w-full h-48 object-cover"
                  />
                  <img
                    src={(landingData as any)?.whyUsImages?.[2] || '/assets/images/2023/10/A11-Team-Building.jpg'}
                    alt="Tangkahan"
                    className="rounded-[1.5rem] shadow-xl w-full h-48 object-cover"
                  />
                </div>
              </div>
              
              {/* Dynamic Stats Row */}
              <div className="absolute -bottom-8 left-0 right-0 px-4 flex justify-center gap-4">
                {(stats ? [
                  { icon: 'Award', value: '15+', label: 'Tahun Melayani' },
                  { icon: 'Compass', value: `${stats.packages}+`, label: 'Paket Wisata' },
                  { icon: 'Users', value: `${stats.happyClients}+`, label: 'Wisatawan Puas' }
                ] : [
                  { icon: 'Award', value: '15+', label: 'Tahun Melayani' },
                  { icon: 'Users', value: '500+', label: 'Wisatawan Puas' }
                ]).slice(0, 3).map((stat: { icon: string; value: string; label: string }, idx: number) => {
                  const StatIcon = lucideIcons[stat.icon] || Award;
                  return (
                    <motion.div 
                      key={idx}
                      initial={{ opacity: 0, y: 20 }}
                      whileInView={{ opacity: 1, y: 0 }}
                      transition={{ delay: idx * 0.1 }}
                      className="bg-white p-5 md:p-7 rounded-[2rem] shadow-2xl border border-slate-50 flex items-center gap-4 min-w-[160px]"
                    >
                      <div className="w-12 h-12 bg-toba-green/10 text-toba-green rounded-2xl flex items-center justify-center shrink-0">
                        <StatIcon size={24} />
                      </div>
                      <div>
                        <p className="text-2xl md:text-3xl font-black text-slate-900 leading-none mb-1">{stat.value}</p>
                        <p className="text-[10px] font-bold uppercase tracking-widest text-slate-400">{stat.label}</p>
                      </div>
                    </motion.div>
                  );
                })}
              </div>
            </div>

            <div className="pt-10 lg:pt-0">
              <div className="flex items-center space-x-2 mb-5">
                <div className="h-px w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Mengapa Kami</span>
              </div>
              <h2 className="text-4xl md:text-5xl font-black text-slate-900 mb-8 tracking-tight leading-tight">
                {landingData?.whyUs?.title || (
                  <>Pengalaman Wisata <br /><span className="text-toba-green">Terbaik di Sumut</span></>
                )}
              </h2>
              <div className="space-y-6 mb-10">
                {(landingData?.whyUs?.items || [
                  { icon: Shield, title: 'Aman & Terpercaya', desc: 'Keselamatan Anda prioritas kami. Asuransi perjalanan lengkap dan pembayaran aman.', color: 'text-toba-green bg-toba-green/8' },
                  { icon: Clock, title: 'Dukungan 24/7', desc: 'Tim kami siap membantu kapan saja selama perjalanan Anda berlangsung.', color: 'text-emerald-600 bg-emerald-50' },
                  { icon: Globe, title: 'Jaringan Luas', desc: 'Akses ke destinasi eksklusif dan pengalaman lokal melalui jaringan mitra kami.', color: 'text-blue-600 bg-blue-50' },
                ]).map((item: WhyUsItem, i: number) => {
                  const IconComponent = typeof item.icon === 'string' ? (lucideIcons[item.icon] || Shield) : item.icon;
                  return (
                    <motion.div key={i} initial={{ opacity: 0, x: 20 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="flex items-start space-x-5">
                      <div className={`w-13 h-13 w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 ${item.color || 'text-toba-green bg-toba-green/8'}`}>
                        <IconComponent size={24} />
                      </div>
                      <div>
                        <h4 className="text-lg font-black text-slate-900 mb-1 tracking-tight">{item.title}</h4>
                        <p className="text-slate-500 font-medium leading-relaxed text-sm">{item.desc}</p>
                      </div>
                    </motion.div>
                  );
                })}
              </div>
              <div className="flex flex-wrap gap-3">
                {['Guide Berpengalaman', 'Harga Transparan', 'Itinerary Fleksibel', 'Grup Kecil'].map(tag => (
                  <span key={tag} className="flex items-center gap-1.5 px-4 py-2 bg-toba-green/8 text-toba-green rounded-full text-xs font-bold">
                    <CheckCircle size={13} /> {tag}
                  </span>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Blog & News Section */}
      <section className="py-24 bg-white border-t border-slate-100">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div className="max-w-2xl">
              <div className="flex items-center space-x-2 mb-4">
                <div className="h-px w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Wawasan & Artikel</span>
              </div>
              <h2 className="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                Jurnal <span className="text-toba-green">Perjalanan Kami</span>
              </h2>
            </div>
            <Link href="/tour/blog" className="flex items-center space-x-3 text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0">
              <span>Semua Artikel</span>
              <div className="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all">
                <ArrowRight size={18} />
              </div>
            </Link>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {blogs.map((blog, i) => (
              <motion.div key={i} initial={{ opacity: 0, scale: 0.95 }} whileInView={{ opacity: 1, scale: 1 }} viewport={{ once: true }} className="group">
                <div className="h-56 rounded-[2rem] overflow-hidden mb-6 shadow-md">
                  <img src={blog.image || '/assets/images/2023/10/001-1.jpg'} alt={blog.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                </div>
                <p className="text-xs font-bold text-slate-400 mb-3 uppercase tracking-widest">
                  {new Date(blog.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                </p>
                <h3 className="text-xl font-bold text-slate-900 mb-3 leading-snug group-hover:text-toba-green transition-colors">{blog.title}</h3>
                <p className="text-sm text-slate-500 line-clamp-2">{blog.excerpt || blog.content?.slice(0, 120)}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="py-24">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-16">
            <div className="flex items-center justify-center space-x-2 mb-4">
              <div className="h-px w-8 bg-toba-green" />
              <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Testimoni</span>
              <div className="h-px w-8 bg-toba-green" />
            </div>
            <h2 className="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
              Kata Mereka <span className="text-toba-green">Tentang Kami</span>
            </h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {(landingData?.testimonials || []).map((t: Testimonial, i: number) => (
              <motion.div key={i} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }}
                className="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                <div className="flex gap-1 mb-5">
                  {Array.from({ length: t.rating }).map((_, j) => (
                    <Star key={j} size={16} className="text-amber-400 fill-amber-400" />
                  ))}
                </div>
                <p className="text-slate-600 leading-relaxed mb-6 font-medium italic">&ldquo;{t.text}&rdquo;</p>
                <div className="flex items-center gap-3 pt-5 border-t border-slate-50">
                  <img src={t.avatar} alt={t.name} className="w-12 h-12 rounded-full object-cover" referrerPolicy="no-referrer" />
                  <div>
                    <p className="font-black text-slate-900 text-sm">{t.name}</p>
                    <p className="text-xs text-slate-400 font-medium">{t.role}</p>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-24 px-4">
        <div className="max-w-7xl mx-auto bg-slate-900 rounded-[3rem] p-12 md:p-20 relative overflow-hidden shadow-2xl">
          <div className="absolute inset-0 opacity-20 pointer-events-none">
            {landingData?.cta?.backgroundImage ? (
              <img src={landingData.cta.backgroundImage} alt="" className="w-full h-full object-cover" />
            ) : (
              <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000" alt="Danau Toba" className="w-full h-full object-cover" />
            )}
          </div>
          <div className="absolute inset-0 bg-gradient-to-r from-slate-900/80 to-slate-900/40" />
          <div className="relative z-10 text-center max-w-3xl mx-auto">
            <h2 className="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight">
              {landingData?.cta?.title || <>Siap Menjelajahi <br /><span className="text-toba-accent">Keindahan Sumut?</span></>}
            </h2>
            <p className="text-lg text-slate-300 mb-10 font-medium leading-relaxed">
              {landingData?.cta?.subtitle || 'Bergabunglah dengan ribuan wisatawan yang telah merasakan keajaiban Sumatera Utara bersama kami.'}
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <Link href={(landingData?.cta?.buttonLink as string) || '/tour/packages'} className="bg-toba-green text-white px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-toba-accent transition-all shadow-2xl shadow-toba-green/20">
                {(landingData?.cta?.buttonText as string) || 'Lihat Paket Wisata'}
              </Link>
            </div>
          </div>
        </div>
      </section>
      {/* Floating WhatsApp Widget */}
      <a 
        href={`https://wa.me/${waNumber}?text=${encodeURIComponent('Halo Admin Wonderful Toba, saya tertarik dengan layanan Anda.')}`}
        target="_blank" 
        rel="noopener noreferrer"
        className="fixed bottom-8 right-8 z-[100] bg-emerald-500 text-white p-5 rounded-full shadow-2xl hover:bg-emerald-600 transition-all hover:scale-110 group animate-bounce"
      >
        <div className="absolute -top-12 right-0 bg-white text-slate-800 px-4 py-2 rounded-xl shadow-lg text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap border border-slate-100 pointer-events-none">
          Butuh Bantuan? Chat Kami 👋
        </div>
        <MessageSquare size={28} />
      </a>
    </div>
  );
}
