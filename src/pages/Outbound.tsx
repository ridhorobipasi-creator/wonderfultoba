"use client";

import { useState, useEffect, type ComponentType } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Helmet } from 'react-helmet-async';
import * as LucideIcons from 'lucide-react';
import { ArrowRight, MapPin, CheckCircle, Target, Users, Sparkles, Smile, Compass, Navigation, Swords, Play, Image as ImageIcon, Shield } from 'lucide-react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { mockOutboundServices, mockVideos, mockLocations, mockClients, mockGallery, mockSettings } from '../data/mockData';

type IconComponent = ComponentType<{ size?: number; className?: string; strokeWidth?: number }>;

interface OutboundService {
  title: string;
  desc: string;
  icon: IconComponent;
  image: string;
  detail: string;
}

interface OutboundVideo {
  title: string;
  url: string;
}

interface OutboundLocation {
  title: string;
  img: string;
}

interface OutboundLandingData {
  about: {
    title: string;
    description: string;
    vision: string;
    mission: string;
    statsLabel: string;
    statsValue: string;
  };
  services: OutboundService[];
  videos: OutboundVideo[];
  locations: OutboundLocation[];
  gallery: string[];
}

export default function Outbound() {
  const router = useRouter();
  const [currentSlide, setCurrentSlide] = useState(0);
  const [activeVideoTab, setActiveVideoTab] = useState(0);
  const [loading, setLoading] = useState(true);
  const [services, setServices] = useState<OutboundService[]>([]);
  const [videos, setVideos] = useState<OutboundVideo[]>([]);
  const [locations, setLocations] = useState<OutboundLocation[]>([]);
  const [clients, setClients] = useState<string[]>([]);
  const [gallery, setGallery] = useState<string[]>([]);
  const [heroImages, setHeroImages] = useState([
    '/assets/images/2023/10/001-1.jpg',
    '/assets/images/2023/10/002-1.jpg',
    '/assets/images/2023/10/003-1.jpg',
    '/assets/images/2023/10/004.jpg',
    '/assets/images/2023/10/006.jpg',
    '/assets/images/2023/10/009-1.jpg',
  ]);
  const [about, setAbout] = useState({
    title: 'Apa itu Outbound?',
    description: 'Outbound adalah metode pembelajaran berbasis pengalaman di alam terbuka yang dirancang untuk membangun karakter, kepemimpinan, dan kerjasama tim secara efektif.',
    vision: 'Menjadi provider outbound terpercaya dan terdepan di Sumatera Utara yang menghadirkan pengalaman belajar bermakna.',
    mission: 'Memberikan layanan outbound berkualitas tinggi dengan trainer tersertifikasi dan venue premium untuk memaksimalkan potensi SDM.',
    statsLabel: 'Tahun',
    statsValue: '12+',
  });

  useEffect(() => {
    async function fetchAllData() {
      try {
        const [servicesRes, videosRes, locationsRes, clientsRes, galleryRes, settingsRes] = await Promise.all([
          fetch('/api/outbound/services'),
          fetch('/api/outbound/videos'),
          fetch('/api/outbound/locations'),
          fetch('/api/clients'),
          fetch('/api/gallery?category=outbound'),
          fetch('/api/settings?key=outbound_landing'),
        ]);

        const servicesData = await servicesRes.json().catch(() => []);
        const videosData = await videosRes.json().catch(() => []);
        const locationsData = await locationsRes.json().catch(() => []);
        const clientsData = await clientsRes.json().catch(() => []);
        const galleryData = await galleryRes.json().catch(() => []);
        const settingsData = await settingsRes.json().catch(() => ({}));

        // Load about section
        if (settingsData?.about) {
          setAbout(prev => ({ ...prev, ...settingsData.about }));
        } else if (mockSettings.outbound_landing?.about) {
          setAbout(prev => ({ ...prev, ...mockSettings.outbound_landing.about }));
        }

        // Load hero images from settings if available
        if (settingsData?.heroImages && Array.isArray(settingsData.heroImages) && settingsData.heroImages.filter(Boolean).length > 0) {
          setHeroImages(settingsData.heroImages.filter(Boolean));
        }

        // Services with Icons
        const finalServices = Array.isArray(servicesData) && servicesData.length > 0 ? servicesData : mockOutboundServices;
        const processedServices = finalServices.map((s: any) => ({
          title: s.name || s.title,
          icon: (LucideIcons as any)[s.icon] || Users,
          image: s.image,
          detail: s.detailDesc || s.detail,
          desc: s.shortDesc || s.desc
        }));
        setServices(processedServices);

        // Others
        const finalVideos = Array.isArray(videosData) && videosData.length > 0 ? videosData : mockVideos;
        setVideos(finalVideos.map((v: any) => ({ title: v.title, url: v.youtubeUrl || v.url })));

        const finalLocations = Array.isArray(locationsData) && locationsData.length > 0 ? locationsData : mockLocations;
        setLocations(finalLocations.map((l: any) => ({ title: l.name || l.title, img: l.image || l.img })));

        const finalClients = Array.isArray(clientsData) && clientsData.length > 0 ? clientsData : mockClients;
        setClients(finalClients.map((c: any) => c.logo));

        const finalGallery = Array.isArray(galleryData) && galleryData.length > 0 ? galleryData : mockGallery;
        setGallery(finalGallery.map((g: any) => g.imageUrl || g.image));
      } catch (e) {
        console.error("Failed to fetch outbound data, using local mock fallbacks", e);
        setServices(mockOutboundServices.map(s => ({
          title: s.title,
          icon: (LucideIcons as any)[s.icon] || Users,
          image: s.image,
          detail: s.detailDesc,
          desc: s.shortDesc
        })));
        setVideos(mockVideos.map(v => ({ title: v.title, url: v.youtubeUrl })));
        setLocations(mockLocations.map(l => ({ title: l.name, img: l.image })));
        setClients(mockClients.map(c => c.logo));
        setGallery(mockGallery.map(g => g.imageUrl));
      } finally {
        setLoading(false);
      }
    }
    fetchAllData();
  }, []);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % heroImages.length);
    }, 5000);
    return () => clearInterval(timer);
  }, [heroImages.length]);

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-screen bg-slate-50">
        <div className="w-16 h-16 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin mb-4" />
        <p className="text-slate-600 font-medium">Memuat konten...</p>
      </div>
    );
  }

  const fallbackBlogs = [
    {
      id: 'b1',
      title: 'Pentingnya Trust Building Dalam Organisasi Modern',
      excerpt: 'Studi kasus bagaimana permainan sederhana di alam bisa membongkar sekat hierarki korporasi.',
      date: '12 Nov 2026',
      category: 'outbound',
      image: '/assets/images/2023/10/005.jpg'
    },
    {
      id: 'b2',
      title: 'Membedah Metode Experiential Learning',
      excerpt: 'Mengapa belajar dari pengalaman fisik terbukti 3x lebih mempan dibanding materi kelas.',
      date: '08 Nov 2026',
      category: 'outbound',
      image: '/assets/images/2023/10/008.jpg'
    },
    {
      id: 'b3',
      title: 'Persiapan Fisik Sebelum Mengikuti Corporate Rafting',
      excerpt: 'Tips dan trik kesehatan esensial agar sesi team building air Anda tidak berujung cedera.',
      date: '24 Okt 2026',
      category: 'outbound',
      image: '/assets/images/2023/10/0010.jpg'
    }
  ];

  return (
    <div className="font-sans antialiased text-slate-800 bg-[#fbf9f8] min-h-screen">
      <Helmet>
        <title>Wonderful Toba Outbound - Provider Outbound Terbaik di Sumatera Utara</title>
        <meta name="description" content="Wonderful Toba Outbound: Provider terbaik untuk Team Building, Gathering, Fun Games di Medan & Sumatera Utara." />
      </Helmet>

      {/* Hero Section */}
      <section className="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
        <div className="absolute inset-0">
          <AnimatePresence mode="wait">
            <motion.img
              key={currentSlide}
              src={heroImages[currentSlide]}
              initial={{ opacity: 0, scale: 1.05 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0 }}
              transition={{ duration: 1.5, ease: "easeInOut" }}
              alt="Outbound Activity"
              className="w-full h-full object-cover"
            />
          </AnimatePresence>
          <div className="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-900/40 to-slate-900/90 mix-blend-multiply" />
        </div>
        
        <div className="relative z-10 max-w-5xl mx-auto px-4 text-center mt-10">
          <motion.div initial={{ opacity: 0, y: 30 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.8 }} className="flex flex-col items-center">
            <img 
               src="/assets/images/2023/09/Logo-Wonderful-Toba-Outbound-White-1.png" 
               alt="Wonderful Toba Outbound Logo" 
               className="w-auto h-28 md:h-36 mb-6 drop-shadow-[0_0_15px_rgba(255,255,255,0.4)]" 
            />
            
            <p className="text-xl md:text-2xl text-white font-medium max-w-3xl mx-auto mb-12 leading-relaxed drop-shadow-xl">
              Pembelajaran ilmu terapan atraktif dan seru di alam terbuka untuk menggabungkan <span className="font-black text-white">intelegensia, fisik, dan mental</span>.
            </p>
            
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
              <Link href="/outbound/packages" className="w-full sm:w-auto bg-toba-green text-white px-10 py-4 rounded-full font-black tracking-widest text-sm uppercase hover:bg-emerald-600 transition-colors shadow-2xl flex items-center justify-center gap-2">
                Pricelist & Paket
              </Link>
              <a href="https://wa.me/6281323888207" className="w-full sm:w-auto bg-white/20 backdrop-blur-md border border-white/50 text-white px-10 py-4 rounded-full font-black tracking-widest text-sm uppercase hover:bg-white hover:text-slate-900 transition-colors shadow-2xl flex items-center justify-center">
                Konsultasi Event
              </a>
            </div>
            
            {/* Slider Dots */}
            <div className="flex justify-center mt-16 gap-2">
              {heroImages.map((_, idx) => (
                <button 
                   key={idx} 
                   onClick={() => setCurrentSlide(idx)} 
                   className={`w-3 h-3 rounded-full transition-all duration-300 ${currentSlide === idx ? 'bg-toba-green w-10 shadow-[0_0_10px_#10B981]' : 'bg-white/40 hover:bg-white/70'}`} 
                   aria-label={`Slide ${idx + 1}`}
                />
              ))}
            </div>
          </motion.div>
        </div>
      </section>

      {/* Apa itu Outbound & Kenapa Kami - Ultra Premium Editorial Layout */}
      <section id="tentangkami" className="py-32 bg-[#fdfdfd] relative top-0 z-20 -mt-10 rounded-t-[3rem] shadow-[0_-20px_40px_-15px_rgba(0,0,0,0.05)] overflow-hidden">
        {/* Subtle background glow */}
        <div className="absolute top-0 right-0 w-[800px] h-[800px] bg-toba-green/[0.03] rounded-full blur-[100px] -translate-y-1/2 translate-x-1/3 pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-[600px] h-[600px] bg-emerald-600/[0.03] rounded-full blur-[80px] translate-y-1/3 -translate-x-1/3 pointer-events-none" />

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-8 items-center">
            
            {/* Left Content Column (7 cols) */}
            <motion.div initial={{ opacity: 0, x: -30 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true, margin: "-100px" }} className="lg:col-span-7 pr-0 lg:pr-12">
              <div className="inline-flex items-center space-x-3 mb-8 bg-white px-5 py-2.5 rounded-full border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)]">
                <span className="relative flex h-3.5 w-3.5">
                  <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-toba-green opacity-75"></span>
                  <span className="relative inline-flex rounded-full h-3.5 w-3.5 bg-toba-green"></span>
                </span>
                <span className="text-toba-green font-black text-xs uppercase tracking-[0.25em]">Experiential Learning</span>
              </div>
              
              <h2 className="text-5xl md:text-6xl lg:text-[4rem] font-black text-slate-900 mb-10 leading-[1.1] tracking-tight">
                {about.title || 'Apa itu Outbound?'} <span className="text-toba-green relative inline-block">
                  Outbound?
                  <svg className="absolute w-[110%] h-4 -bottom-1 left-[-5%] text-toba-green/20" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0 15 Q 50 0 100 15" stroke="currentColor" strokeWidth="8" fill="none" strokeLinecap="round" /></svg>
                </span>
              </h2>
              
              <div className="relative mb-12">
                <div className="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-toba-green to-emerald-200 rounded-full" />
                <div className="pl-8 text-xl leading-relaxed text-slate-600 font-medium">
                  {about.description}
                </div>
              </div>

              <div className="bg-white border border-slate-100 rounded-[2.5rem] p-8 md:p-10 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] relative hover:shadow-[0_20px_60px_-15px_rgba(16,185,129,0.1)] transition-all duration-500">
                <h3 className="text-xl md:text-2xl font-black mb-8 text-slate-900 flex items-center gap-4">
                  <span className="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-lg"><Sparkles size={20} /></span>
                  Kenapa Harus Kami?
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                  {[
                    { text: 'Trainer tersertifikasi pakar', Icon: Users },
                    { text: 'Vendor venue resort premium', Icon: MapPin },
                    { text: 'Games interaktif & adaptif', Icon: Target },
                    { text: 'Konsep event kreatif berdampak', Icon: Compass }
                  ].map(({ text, Icon }, i) => (
                    <div key={i} className="flex items-start gap-4 group cursor-default">
                      <div className="mt-1 bg-slate-50 p-2.5 rounded-xl group-hover:bg-toba-green group-hover:text-white group-hover:scale-110 transition-all duration-300 text-toba-green shadow-sm">
                        <Icon size={18} strokeWidth={2.5} />
                      </div>
                      <span className="font-bold text-slate-700 text-sm md:text-base leading-snug tracking-wide group-hover:text-slate-900 transition-colors">{text}</span>
                    </div>
                  ))}
                </div>
              </div>
            </motion.div>

            {/* Right Images Column (5 cols) - Overlapping Editorial Look */}
            <motion.div initial={{ opacity: 0, scale: 0.95 }} whileInView={{ opacity: 1, scale: 1 }} viewport={{ once: true, margin: "-100px" }} transition={{ duration: 0.7 }} className="lg:col-span-5 relative mt-16 lg:mt-0">
              {/* Image 1 (Back/Top Right) */}
              <div className="relative w-4/5 ml-auto -right-4 top-0 z-10 rounded-[2.5rem] overflow-hidden shadow-2xl hover:-translate-y-2 transition-transform duration-500">
                <img src="/assets/images/2023/10/A11-Team-Building.jpg" alt="Team Building" className="w-full aspect-[4/5] object-cover" />
                <div className="absolute inset-0 bg-gradient-to-tr from-slate-900/40 to-transparent" />
              </div>
              
              {/* Image 2 (Front/Bottom Left) */}
              <div className="absolute w-3/4 -bottom-12 -left-4 z-20 rounded-[2.5rem] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] border-[6px] border-white hover:scale-105 transition-transform duration-500">
                <img src="/assets/images/2023/10/003-1.jpg" alt="Corporate Activity" className="w-full aspect-square object-cover" />
              </div>

              {/* Floating Badge */}
              <motion.div 
                animate={{ y: [0, -10, 0] }} 
                transition={{ duration: 4, repeat: Infinity, ease: "easeInOut" }}
                className="absolute top-1/2 -left-8 lg:-left-12 z-30 bg-white/90 backdrop-blur-xl p-5 md:p-6 rounded-3xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.2)] border border-white flex items-center gap-4"
              >
                <div className="bg-gradient-to-br from-toba-green to-emerald-600 text-white w-14 h-14 md:w-16 md:h-16 rounded-2xl flex items-center justify-center font-black text-2xl shadow-inner shadow-white/20">
                  {about.statsValue || '12+'}
                </div>
                <div>
                  <div className="font-black text-slate-900 text-sm md:text-base uppercase tracking-wider leading-none mb-1">{about.statsLabel || 'Tahun'}</div>
                  <div className="text-slate-500 font-bold text-xs md:text-sm">Pengalaman Eksekusi</div>
                </div>
              </motion.div>
            </motion.div>
            
          </div>
        </div>
      </section>

      {/* Corporate Identity */}
      <section className="py-24 bg-slate-50 border-y border-slate-200 relative overflow-hidden">
        <div className="absolute -left-40 top-20 w-96 h-96 bg-toba-green/5 rounded-full blur-3xl pointer-events-none" />
        <div className="absolute -right-40 bottom-20 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl pointer-events-none" />
        
        <div className="max-w-7xl mx-auto px-4 relative z-10">
          <div className="text-center max-w-4xl mx-auto mb-20">
            <h2 className="text-4xl md:text-5xl font-black text-slate-900 mb-8 tracking-tight">Kredibilitas <span className="text-toba-green">Perusahaan</span></h2>
            <p className="text-slate-600 font-medium leading-relaxed text-lg mb-6 max-w-3xl mx-auto">
              Berbekal keahlian sejak tahun 2012, <strong>Wonderful Toba Outbound</strong> dibentuk untuk mengakomodir kebutuhan pembelajaran sebagai metode membangun rasa percaya diri, kepemimpinan dan kerjasama tim.
            </p>
            <p className="text-slate-600 font-medium leading-relaxed text-lg max-w-3xl mx-auto">
              Telah banyak dipercaya melaksanakan program baik untuk komunitas, sekolah, instansi pemerintahan hingga <strong>perusahaan multinasional maupun internasional.</strong>
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} className="bg-white p-12 rounded-[3rem] shadow-[0_10px_40px_-5px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-2xl transition-all duration-300">
              <div className="w-20 h-20 bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 rounded-3xl flex items-center justify-center mb-8 shadow-sm">
                <Target size={40} strokeWidth={2.5} />
              </div>
              <h3 className="text-3xl font-black text-slate-900 mb-6">VISI KAMI</h3>
              <p className="text-slate-600 font-medium leading-relaxed text-lg">
                {about.vision}
              </p>
            </motion.div>
            
            <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: 0.1 }} className="bg-white p-12 rounded-[3rem] shadow-[0_10px_40px_-5px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-2xl transition-all duration-300">
              <div className="w-20 h-20 bg-gradient-to-br from-emerald-50 to-emerald-100 text-toba-green rounded-3xl flex items-center justify-center mb-8 shadow-sm">
                <Navigation size={40} strokeWidth={2.5} />
              </div>
              <h3 className="text-3xl font-black text-slate-900 mb-6">MISI KAMI</h3>
              <p className="text-slate-600 font-medium leading-relaxed text-lg mb-4">
                {about.mission}
              </p>
              <p className="text-slate-500 font-medium leading-relaxed text-sm bg-slate-50 p-4 rounded-xl border border-slate-100">
                Kami juga mengintrodusir pelatihan khusus: <em>Motivation Building</em>, <em>Assessment Center</em>, dan <em>Counselling</em> merespon keluhan kualitas SDM & Etos Kerja.
              </p>
            </motion.div>
          </div>
        </div>
      </section>

      {/* Layanan Kami - Premium Bento-inspired Cards */}
      <section id="layanan" className="py-32 bg-[#090e17] relative overflow-hidden">
        {/* Abstract Backgrounds */}
        <div className="absolute top-0 right-0 w-[500px] h-[500px] bg-toba-green/10 rounded-full blur-[120px] pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-[120px] pointer-events-none" />
        <div className="absolute inset-0 bg-[url('/assets/images/2023/10/outbound-hadena-indonesia-experience-1024x723-1-1024x430.jpg')] bg-cover bg-center opacity-[0.03] mix-blend-overlay grayscale pointer-events-none" />
        
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div className="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
            <div className="max-w-2xl">
              <span className="inline-flex items-center space-x-2 text-toba-green font-black uppercase tracking-[0.3em] text-xs mb-6 bg-toba-green/10 px-4 py-2 rounded-full border border-toba-green/20">
                <Sparkles size={14} /> <span>Program Unggulan</span>
              </span>
              <h2 className="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight">Kapasitas <span className="text-transparent bg-clip-text bg-gradient-to-r from-toba-green to-emerald-400">Layanan Kami</span></h2>
            </div>
            <p className="text-slate-400 font-medium text-lg max-w-md leading-relaxed md:text-right">
              Dirancang spesifik untuk membangun kebersamaan dan mencetak kapabilitas SDM perusahan Anda di level maksimal.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            {services.map((svc, i: number) => (
              <motion.div key={i} initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, margin: "-50px" }} transition={{ duration: 0.5, delay: i * 0.1 }}
                className="group relative rounded-[2.5rem] p-1 overflow-hidden bg-gradient-to-b from-white/10 to-white/5 hover:from-toba-green/40 hover:to-toba-green/5 transition-colors duration-500">
                
                {/* Glow Effect Background on Hover */}
                <div className="absolute inset-0 bg-toba-green/0 group-hover:bg-toba-green/10 blur-xl transition-all duration-700" />
                
                {/* Inner Card Card */}
                <div className="relative w-full h-full bg-[#0d131f] rounded-[2.3rem] overflow-hidden flex flex-col items-start border border-white/5">
                  
                  {/* Top Image Split */}
                  <div className="w-full h-56 relative overflow-hidden">
                    <img src={svc.image} alt={svc.title} className="w-full h-full object-cover scale-100 group-hover:scale-110 transition-transform duration-700 ease-out opacity-80 group-hover:opacity-100" />
                    <div className="absolute inset-0 bg-gradient-to-b from-black/0 via-[#0d131f]/10 to-[#0d131f] translate-y-2" />
                    
                    {/* Floating Icon */}
                    <div className="absolute top-5 right-5 bg-black/40 backdrop-blur-md p-3.5 rounded-2xl border border-white/10 shadow-2xl group-hover:bg-toba-green group-hover:border-toba-green transition-colors duration-300">
                      <svc.icon size={22} className="text-toba-green group-hover:text-white transition-colors" />
                    </div>
                  </div>
                  
                  {/* Text Content */}
                  <div className="p-8 pt-4 flex-1 flex flex-col z-10 w-full relative">
                    {/* Stylized Number Watermark */}
                    <div className="absolute right-6 bottom-4 text-7xl font-black text-white/[0.03] group-hover:text-toba-green/[0.05] transition-colors pointer-events-none select-none z-0">
                      {String(i + 1).padStart(2, '0')}
                    </div>
                    
                    <h3 className="text-2xl font-black text-white mb-2 tracking-tight group-hover:text-toba-green transition-colors leading-tight relative z-10">{svc.title}</h3>
                    <p className="text-toba-green font-bold text-xs mb-5 uppercase tracking-widest bg-toba-green/10 inline-block px-3 py-1 rounded-lg w-max relative z-10">{svc.desc}</p>
                    <p className="text-slate-400 font-medium leading-relaxed text-sm group-hover:text-slate-300 transition-colors relative z-10 line-clamp-3">
                      {svc.detail}
                    </p>
                  </div>

                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
      
      {/* Video Highlights (Refined Tabs) */}
      <section className="py-24 bg-slate-50 border-y border-slate-200">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">Highlight <span className="text-toba-green">Video Event</span></h2>
            <p className="text-slate-500 font-medium text-lg max-w-2xl mx-auto">Kami mengabadikan setiap momen kebersamaan dan kekompakan event secara profesional.</p>
          </div>
          
          <div className="flex flex-wrap justify-center gap-3 mb-12">
            {videos.length > 0 && videos.map((vid, i: number) => (
              <button 
                key={i} 
                onClick={() => setActiveVideoTab(i)}
                className={`px-6 py-3.5 rounded-full font-bold text-sm transition-all flex items-center gap-3 relative overflow-hidden group ${activeVideoTab === i ? 'bg-slate-900 text-white shadow-xl scale-105' : 'bg-white text-slate-600 border mx-1 border-slate-200 hover:border-slate-300 hover:bg-slate-50'}`}
              >
                {activeVideoTab === i && <div className="absolute inset-0 bg-toba-green/20 scale-150 animate-pulse" />}
                <div className={`p-1.5 rounded-full ${activeVideoTab === i ? 'bg-toba-green' : 'bg-slate-100 group-hover:bg-slate-200'}`}>
                  <Play size={12} className={activeVideoTab === i ? 'text-white' : 'text-slate-700'} fill="currentColor" />
                </div>
                <span className="relative z-10">{vid.title}</span>
              </button>
            ))}
          </div>
          
          <div className="aspect-video w-full max-w-5xl mx-auto rounded-[2.5rem] overflow-hidden shadow-2xl bg-slate-900 relative ring-8 ring-white">
             <div className="absolute inset-0 flex items-center justify-center"><div className="w-16 h-16 border-4 border-toba-green/30 border-t-toba-green rounded-full animate-spin" /></div>
             <AnimatePresence mode="wait">
               <motion.iframe
                  key={activeVideoTab}
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  exit={{ opacity: 0 }}
                  transition={{ duration: 0.5 }}
                  src={videos[activeVideoTab].url}
                  title={videos[activeVideoTab].title}
                  className="w-full h-full absolute inset-0 z-10"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowFullScreen
               />
             </AnimatePresence>
          </div>
        </div>
      </section>

      {/* Lokasi Outbound (Visual Grid) */}
      <section id="lokasioutbound" className="py-28 bg-white overflow-hidden">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div className="max-w-3xl">
              <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em] mb-4 block">Venue Partners</span>
              <h2 className="text-4xl md:text-5xl font-black text-slate-900 mb-6">Lokasi <span className="text-toba-green relative">
                  Outbound Terbaik
                  <svg className="absolute w-full h-3 -bottom-2 left-0 text-toba-green" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0 20 L 100 0" stroke="currentColor" strokeWidth="6" fill="none" /></svg>
                </span>
              </h2>
              <p className="text-slate-500 font-medium text-lg leading-relaxed">
                Kami siap menyelenggarakan event Anda di puluhan spot premium terpilih di Sumatera Utara. Dari view pegunungan Berastagi hingga megahnya Danau Toba.
              </p>
            </div>
            <Link href="/outbound/packages" className="shrink-0 bg-slate-900 text-white px-8 py-4 rounded-full font-bold text-sm hover:bg-toba-green transition-colors flex items-center gap-2">
              Pricelist Resort <ArrowRight size={16} />
            </Link>
          </div>
          
          <div className="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
            {locations.map((lokasi, idx: number) => (
              <motion.div key={idx} initial={{ opacity: 0, y: 10 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: (idx % 4) * 0.1 }} 
                className="break-inside-avoid relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm hover:shadow-2xl transition-all">
                 <img src={lokasi.img} alt={lokasi.title} className="w-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-in-out" />
                 <div className="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity" />
                 <div className="absolute inset-x-0 bottom-0 p-5 translate-y-2 group-hover:translate-y-0 transition-transform">
                   <div className="flex items-start gap-3">
                     <span className="bg-toba-green text-white p-1.5 rounded-full mt-0.5"><MapPin size={14} /></span>
                     <h3 className="font-bold text-white text-sm leading-tight drop-shadow-md">{lokasi.title}</h3>
                   </div>
                 </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Klien Kami (Marquee Style & Grid) */}
      <section id="klien" className="py-24 bg-slate-50 border-y border-slate-200 rounded-b-[4rem] relative z-10 shadow-lg">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-16">
            <span className="text-slate-400 font-black text-xs uppercase tracking-[0.3em] mb-4 block">Trusted By</span>
            <h2 className="text-4xl font-black text-slate-900">Dipercaya Oleh Berbagai <span className="text-toba-green">Instansi</span></h2>
          </div>
          
          {/* Logo Grid */}
          <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7 gap-x-8 gap-y-12 items-center justify-items-center opacity-70 hover:opacity-100 transition-opacity duration-500">
            {clients.map((clientUrl, i) => (
              <div key={i} className="flex items-center justify-center w-full h-16 p-2 filter grayscale hover:grayscale-0 hover:scale-110 transition-all duration-300">
                <img src={clientUrl} alt={`Client ${i}`} className="max-w-full max-h-full object-contain drop-shadow-sm" />
              </div>
            ))}
          </div>
        </div>
      </section>
      
      {/* Mega Gallery Grid */}
      <section className="py-32 bg-slate-900 -mt-8 pt-40 relative z-0">
         <div className="max-w-7xl mx-auto px-4">
           <div className="flex flex-col items-center text-center mb-16">
             <div className="bg-slate-800 text-slate-300 px-6 py-2 rounded-full font-bold uppercase tracking-widest text-xs flex items-center gap-2 mb-6 border border-slate-700">
               <ImageIcon size={14} /> Dokumentasi Event
             </div>
             <h2 className="text-4xl md:text-5xl font-black text-white">Galeri <span className="text-toba-green">Wonderful Toba</span></h2>
           </div>
           
           <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
             {gallery.map((img: string, i: number) => (
               <motion.div key={i} initial={{ opacity: 0, scale: 0.8 }} whileInView={{ opacity: 1, scale: 1 }} viewport={{ once: true }} transition={{ delay: (i % 6) * 0.05 }} 
                 className="aspect-square rounded-2xl overflow-hidden shadow-lg hover:shadow-toba-green/20 relative group bg-slate-800">
                 <img src={img} loading="lazy" alt={`Gallery ${i}`} className="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-125 transition-all duration-700" />
                 <div className="absolute inset-0 border-2 border-transparent group-hover:border-toba-green/50 rounded-2xl transition-colors pointer-events-none" />
               </motion.div>
             ))}
           </div>
           
           <div className="text-center mt-12">
             <Link href="/outbound/blog" className="inline-flex items-center gap-2 text-slate-400 hover:text-white font-bold transition-colors">
               Lihat Dokumentasi di Blog <ArrowRight size={16} />
             </Link>
           </div>
         </div>
      </section>

    </div>
  );
}

