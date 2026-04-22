"use client";

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { Helmet } from 'react-helmet-async';
import { ArrowRight, Compass, Users } from 'lucide-react';

interface LandingContent {
  outbound: { backgroundImage: string; title: string; subtitle: string; ctaText: string; };
  tour: { backgroundImage: string; title: string; subtitle: string; ctaText: string; };
  brand: { name: string; tagline: string; };
}

const defaultContent: LandingContent = {
  outbound: {
    backgroundImage: '/storage/2026/04/sumatra-panorama.png',
    title: 'Corporate\nOutbound.',
    subtitle: 'Solusi team building & gathering profesional untuk instansi Anda. Tersedia di puluhan hotel premium Sumut.',
    ctaText: 'Jelajahi Outbound',
  },
  tour: {
    backgroundImage: '/storage/2026/04/lake-toba-premium.png',
    title: 'Tour &\nTravel.',
    subtitle: 'Eksplorasi keindahan Danau Toba, Berastagi, dan alam liar Bukit Lawang dengan paket liburan eksklusif kami.',
    ctaText: 'Jelajahi Wisata',
  },
  brand: { name: 'Wonderful Toba', tagline: 'Sumatera Utara' },
};

export default function Landing() {
  const router = useRouter();
  const [isLoaded, setIsLoaded] = useState(false);
  const [content, setContent] = useState<LandingContent>(defaultContent);

  useEffect(() => {
    const frame = window.requestAnimationFrame(() => setIsLoaded(true));
    // Fetch CMS content
    fetch('/api/settings?key=landing_page')
      .then(r => r.json())
      .then(data => { if (data) setContent(prev => ({ ...prev, ...data })); })
      .catch(() => {});
    return () => window.cancelAnimationFrame(frame);
  }, []);

  return (
    <div className="font-sans bg-slate-950 text-white overflow-hidden min-h-screen">
      <Helmet>
        <title>Wonderful Toba | Premium Tour & Corporate Outbound</title>
        <meta name="description" content="Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building." />
      </Helmet>

      <main className="h-[100dvh] flex flex-col md:flex-row relative">
        
        {/* Central Logo & Branding */}
        <div className={`absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 text-center transition-all duration-1000 ${isLoaded ? 'opacity-100 scale-100' : 'opacity-0 scale-90'}`}>
           <div className="bg-slate-900/40 backdrop-blur-xl p-8 rounded-full border border-white/10 shadow-2xl shadow-toba-green/20">
             <div className="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center font-black text-3xl mx-auto shadow-inner mb-4">W</div>
             <h1 className="text-3xl font-black tracking-tight whitespace-nowrap mb-1">
               {content.brand.name.split(' ').map((w, i) => i === 1 ? <span key={i} className="text-toba-green"> {w}</span> : w)}
             </h1>
             <p className="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-300">{content.brand.tagline}</p>
           </div>
        </div>

        {/* Left Side - Corporate Outbound */}
        <button 
          onClick={() => router.push('/outbound')} 
          className="relative w-full md:w-1/2 h-[50vh] md:h-full group cursor-pointer block overflow-hidden border-b-2 gap-4 md:border-b-0 md:border-r-2 border-slate-900"
        >
          <div 
            className="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] ease-out group-hover:scale-110" 
            style={{ backgroundImage: `url('${content.outbound.backgroundImage}')` }}
          />
          <div className="absolute inset-0 bg-slate-900/60 group-hover:bg-slate-900/40 transition-colors duration-500" />
          <div className="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent" />
          
          <div className="absolute inset-0 flex flex-col justify-end p-10 md:p-16 lg:p-24 text-left transition-transform duration-500 group-hover:-translate-y-4">
            <div className="flex items-center gap-3 mb-4 text-toba-green opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 delay-100">
               <Users size={20} />
               <span className="font-bold text-xs uppercase tracking-widest text-white">Event Organizer</span>
            </div>
            <h2 className="text-4xl md:text-5xl lg:text-7xl font-black mb-4 tracking-tighter leading-[1.1] whitespace-pre-line">
              {content.outbound.title.split('\n').map((line, i) => (
                <span key={i}>{i > 0 ? <><br/><span className="text-toba-green">{line}</span></> : line}</span>
              ))}
            </h2>
            <p className="text-slate-300 font-medium max-w-sm mb-8 text-sm md:text-base leading-relaxed hidden md:block">
              {content.outbound.subtitle}
            </p>
            <div className="flex items-center gap-4">
              <div className="bg-toba-green text-white px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all shadow-xl group-hover:shadow-toba-green/30 group-hover:px-10">
                 {content.outbound.ctaText}
              </div>
              <div className="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-slate-900 transition-all">
                <ArrowRight size={20} className="-rotate-45 group-hover:rotate-0 transition-transform duration-300" />
              </div>
            </div>
          </div>
        </button>

        {/* Right Side - Tour & Travel */}
        <button 
          onClick={() => router.push('/tour')} 
          className="relative w-full md:w-1/2 h-[50vh] md:h-full group cursor-pointer block overflow-hidden"
        >
          <div 
            className="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] ease-out group-hover:scale-110" 
            style={{ backgroundImage: `url('${content.tour.backgroundImage}')` }}
          />
          <div className="absolute inset-0 bg-slate-900/60 group-hover:bg-slate-900/40 transition-colors duration-500" />
          <div className="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent" />
          
          <div className="absolute inset-0 flex flex-col justify-end p-10 md:p-16 lg:p-24 text-left md:text-right transition-transform duration-500 group-hover:-translate-y-4">
            <div className="flex items-center md:justify-end gap-3 mb-4 text-toba-accent opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 delay-100">
               <Compass size={20} />
               <span className="font-bold text-xs uppercase tracking-widest text-white">Travel Agency</span>
            </div>
            <h2 className="text-4xl md:text-5xl lg:text-7xl font-black mb-4 tracking-tighter leading-[1.1] whitespace-pre-line">
              {content.tour.title.split('\n').map((line, i) => (
                <span key={i}>{i > 0 ? <><br/><span className="text-toba-accent">{line}</span></> : line}</span>
              ))}
            </h2>
            <p className="text-slate-300 font-medium max-w-sm mb-8 text-sm md:text-base leading-relaxed md:ml-auto hidden md:block">
              {content.tour.subtitle}
            </p>
            <div className="flex items-center md:justify-end gap-4 flex-row-reverse md:flex-row">
              <div className="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-slate-900 transition-all">
                <ArrowRight size={20} className="-rotate-45 group-hover:rotate-0 transition-transform duration-300" />
              </div>
              <div className="bg-toba-accent text-white px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all shadow-xl group-hover:shadow-toba-accent/30 group-hover:px-10">
                 {content.tour.ctaText}
              </div>
            </div>
          </div>
        </button>
        
      </main>
    </div>
  );
}
