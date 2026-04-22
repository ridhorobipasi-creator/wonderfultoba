"use client";

import { useState, useEffect, useCallback } from 'react';
import { ChevronLeft, ChevronRight, MapPin, Clock, ArrowRight } from 'lucide-react';
import { cn } from '../utils/cn';
import Link from 'next/link';

interface Destination {
  id: number;
  region: string;
  title: string;
  description: string;
  duration: string;
  price: number;
  image: string;
  cardImage: string;
  link?: string;
}

// Data destinasi wisata Sumatera Utara (Tour) - fallback jika tidak ada data dari CMS
const tourDestinations: Destination[] = [
  {
    id: 1,
    region: 'Tobasa, Sumatera Utara',
    title: 'Pesona Danau Toba & Budaya Batak',
    description: 'Jelajahi keajaiban danau vulkanik terbesar di dunia, nikmati keindahan alam dan kekayaan budaya Batak yang memukau.',
    duration: '4 Hari 3 Malam',
    price: 3500000,
    image: '/assets/images/2023/10/001-1.jpg',
    cardImage: '/assets/images/2023/10/001-1.jpg',
  },
  {
    id: 2,
    region: 'Karo, Sumatera Utara',
    title: 'Air Terjun Sipiso-piso yang Megah',
    description: 'Saksikan kemegahan Air Terjun Sipiso-piso setinggi 120m yang langsung mengalir ke Danau Toba.',
    duration: '3 Hari 2 Malam',
    price: 2800000,
    image: '/assets/images/2023/10/002-1.jpg',
    cardImage: '/assets/images/2023/10/002-1.jpg',
  },
  {
    id: 3,
    region: 'Karo, Sumatera Utara',
    title: 'Berastagi & Gunung Sinabung',
    description: 'Nikmati kesegaran udara pegunungan Karo, pasar buah segar, dan pemandangan Gunung Sinabung yang megah.',
    duration: '2 Hari 1 Malam',
    price: 1900000,
    image: '/assets/images/2023/10/003-1.jpg',
    cardImage: '/assets/images/2023/10/003-1.jpg',
  },
  {
    id: 4,
    region: 'Langkat, Sumatera Utara',
    title: 'Tangkahan & Gajah Sumatera',
    description: 'Petualangan seru bersama gajah Sumatera di tepi sungai, mandi lumpur, dan trekking hutan tropis.',
    duration: '3 Hari 2 Malam',
    price: 2500000,
    image: '/assets/images/2023/10/004.jpg',
    cardImage: '/assets/images/2023/10/004.jpg',
  },
  {
    id: 5,
    region: 'Langkat, Sumatera Utara',
    title: 'Bukit Lawang & Orangutan Liar',
    description: 'Trekking di hutan Gunung Leuser, bertemu orangutan liar di habitat aslinya.',
    duration: '4 Hari 3 Malam',
    price: 3200000,
    image: '/assets/images/2023/10/005.jpg',
    cardImage: '/assets/images/2023/10/005.jpg',
  },
];

const outboundDestinations: Destination[] = [
  {
    id: 1,
    region: 'Toba & Samosir',
    title: 'Corporate Team Building',
    description: 'Tingkatkan solidaritas dan komunikasi tim melalui permainan yang dirancang secara profesional di tepi Danau Toba.',
    duration: '2 Hari 1 Malam',
    price: 1500000,
    image: '/assets/images/2023/10/A11-Team-Building.jpg',
    cardImage: '/assets/images/2023/10/A11-Team-Building.jpg',
  },
  {
    id: 2,
    region: 'Bukit Lawang',
    title: 'Jungle Survival Camp',
    description: 'Uji batas kemampuan tim dalam kondisi ekstrim namun aman. Ekspedisi untuk top level management.',
    duration: '3 Hari 2 Malam',
    price: 2100000,
    image: '/assets/images/2023/10/A12-Fun-Games.jpg',
    cardImage: '/assets/images/2023/10/A12-Fun-Games.jpg',
  },
  {
    id: 3,
    region: 'Berastagi',
    title: 'Fun Family Gathering',
    description: 'Permainan edukatif dan outbond ringan bagi karyawan bersama keluarga.',
    duration: '1 Hari',
    price: 450000,
    image: '/assets/images/2023/10/A12-Gathering.jpg',
    cardImage: '/assets/images/2023/10/A12-Gathering.jpg',
  },
];

interface HeroSliderProps {
  category?: 'tour' | 'outbound';
  destinations?: Destination[];
}

export default function HeroSlider({ category = 'tour', destinations: customDestinations }: HeroSliderProps) {
  const defaultDestinations = category === 'outbound' ? outboundDestinations : tourDestinations;
  const destinations = customDestinations && customDestinations.length > 0 ? customDestinations : defaultDestinations;
  const [activeIndex, setActiveIndex] = useState(0);
  const [isTransitioning, setIsTransitioning] = useState(false);
  const [visibleStart, setVisibleStart] = useState(0);

  const CARDS_VISIBLE = 4;
  const active = destinations[activeIndex];

  // Ganti slide dengan efek fade
  const goTo = useCallback((index: number) => {
    if (isTransitioning || index === activeIndex) return;
    setIsTransitioning(true);
    setTimeout(() => {
      setActiveIndex(index);
      setIsTransitioning(false);
    }, 400);
  }, [isTransitioning, activeIndex]);

  // Auto-play setiap 5 detik
  useEffect(() => {
    const timer = setInterval(() => {
      const next = (activeIndex + 1) % destinations.length;
      goTo(next);
      // Geser carousel jika kartu aktif keluar dari view
      if (next >= visibleStart + CARDS_VISIBLE) {
        setVisibleStart(next - CARDS_VISIBLE + 1);
      } else if (next < visibleStart) {
        setVisibleStart(next);
      }
    }, 5000);
    return () => clearInterval(timer);
  }, [activeIndex, goTo, visibleStart, destinations.length]);

  const handlePrev = () => {
    const prev = (activeIndex - 1 + destinations.length) % destinations.length;
    goTo(prev);
    if (prev < visibleStart) setVisibleStart(Math.max(0, visibleStart - 1));
  };

  const handleNext = () => {
    const next = (activeIndex + 1) % destinations.length;
    goTo(next);
    if (next >= visibleStart + CARDS_VISIBLE) {
      setVisibleStart(Math.min(destinations.length - CARDS_VISIBLE, visibleStart + 1));
    }
  };

  const handleCardClick = (index: number) => {
    goTo(index);
  };

  const visibleCards = destinations.slice(visibleStart, visibleStart + CARDS_VISIBLE);

  return (
    <section className="relative w-full h-screen min-h-[600px] overflow-hidden">
      {/* ── Background Images dengan Fade Transition ── */}
      {destinations.map((dest, i) => (
        <div
          key={dest.id}
          className="absolute inset-0 transition-opacity duration-700 ease-in-out"
          style={{ opacity: i === activeIndex && !isTransitioning ? 1 : 0 }}
        >
          <img
            src={dest.image}
            alt={dest.title}
            className="w-full h-full object-cover"
            referrerPolicy="no-referrer"
          />
        </div>
      ))}

      {/* ── Overlay Gradien ── */}
      <div className="absolute inset-0 bg-gradient-to-r from-slate-900/85 via-slate-900/50 to-transparent" />
      <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent" />

      {/* ── Konten Utama ── */}
      <div className="relative z-10 h-full flex items-center">
        <div className="w-full max-w-7xl mx-auto px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-8 lg:gap-0">

          {/* ── Sisi Kiri: Teks Hero ── */}
          <div className="w-full lg:w-5/12 text-white">
            {/* Label Region */}
            <div
              key={`region-${activeIndex}`}
              className="animate-fadeInUp"
            >
              <span className="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-[0.25em] text-toba-accent mb-4">
                <MapPin size={12} />
                {active.region}
              </span>

              {/* Judul */}
              <h1 className="text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight mb-5 drop-shadow-lg">
                {active.title}
              </h1>

              {/* Deskripsi */}
              <p className="text-slate-300 text-base leading-relaxed mb-6 max-w-md font-medium">
                {active.description}
              </p>

              {/* Durasi */}
              <div className="flex items-center gap-2 text-slate-300 text-sm font-bold mb-3">
                <Clock size={16} className="text-toba-accent" />
                <span>{active.duration}</span>
              </div>

              {/* Harga */}
              <p className="text-sm text-slate-400 font-bold uppercase tracking-widest mb-1">Mulai Dari</p>
              <p className="text-3xl font-black text-white mb-8">
                <span className="text-toba-accent text-lg mr-1">Rp</span>
                {active.price.toLocaleString('id-ID')}
              </p>

              {/* Tombol CTA */}
              <Link href={active.link || "/tour/packages"}
                className="inline-flex items-center gap-3 bg-toba-green text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-[0.15em] hover:bg-toba-accent transition-all duration-300 shadow-2xl shadow-toba-green/30 group"
              >
                <span>Pesan Sekarang</span>
                <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
              </Link>
            </div>
          </div>

          {/* ── Sisi Kanan: Carousel Kartu ── */}
          <div className="w-full lg:w-7/12 flex flex-col items-start lg:items-end gap-4 mt-8 lg:mt-0">
            {/* Kartu-kartu */}
            <div className="flex gap-3 w-full justify-start lg:justify-end overflow-x-auto snap-x snap-mandatory pb-4 hide-scrollbar md:pb-0" style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}>
              {visibleCards.map((dest) => {
                const realIndex = destinations.findIndex(d => d.id === dest.id);
                const isActive = realIndex === activeIndex;
                return (
                  <button
                    key={dest.id}
                    onClick={() => handleCardClick(realIndex)}
                    className={cn(
                      "relative shrink-0 w-[240px] md:w-auto md:flex-1 min-w-0 rounded-[1.5rem] overflow-hidden cursor-pointer transition-all duration-500 group snap-center",
                      "hover:scale-105 hover:shadow-2xl hover:shadow-black/40",
                      isActive
                        ? "ring-2 ring-toba-accent ring-offset-2 ring-offset-transparent shadow-2xl shadow-black/40"
                        : "opacity-80 hover:opacity-100"
                    )}
                    style={{ height: '260px' }}
                  >
                    {/* Gambar Kartu */}
                    <img
                      src={dest.cardImage}
                      alt={dest.title}
                      className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                      referrerPolicy="no-referrer"
                    />

                    {/* Overlay Kartu */}
                    <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent" />

                    {/* Teks Kartu */}
                    <div className="absolute bottom-0 left-0 right-0 p-4 text-left">
                      <span className="text-[9px] font-black uppercase tracking-[0.2em] text-toba-accent block mb-1">
                        {dest.region}
                      </span>
                      <h3 className="text-white font-black text-sm leading-tight mb-2 line-clamp-2">
                        {dest.title}
                      </h3>
                      <p className="text-[10px] text-slate-300 font-bold">
                        Mulai Dari{' '}
                        <span className="text-white">
                          Rp {dest.price.toLocaleString('id-ID')}
                        </span>
                      </p>
                    </div>

                    {/* Indikator Aktif */}
                    {isActive && (
                      <div className="absolute top-3 right-3 w-2.5 h-2.5 bg-toba-accent rounded-full shadow-lg shadow-toba-accent/50" />
                    )}
                  </button>
                );
              })}
            </div>

            {/* ── Tombol Navigasi Prev / Next ── */}
            <div className="flex items-center gap-3 mr-1">
              {/* Dot indicators */}
              <div className="flex gap-1.5 mr-2">
                {destinations.map((_, i) => (
                  <button
                    key={i}
                    onClick={() => goTo(i)}
                    className={cn(
                      "rounded-full transition-all duration-300",
                      i === activeIndex
                        ? "w-6 h-2 bg-toba-accent"
                        : "w-2 h-2 bg-white/40 hover:bg-white/70"
                    )}
                  />
                ))}
              </div>

              <button
                onClick={handlePrev}
                className="w-11 h-11 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white hover:bg-toba-green hover:border-toba-green transition-all duration-300 shadow-lg"
                aria-label="Sebelumnya"
              >
                <ChevronLeft size={20} />
              </button>
              <button
                onClick={handleNext}
                className="w-11 h-11 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white hover:bg-toba-green hover:border-toba-green transition-all duration-300 shadow-lg"
                aria-label="Berikutnya"
              >
                <ChevronRight size={20} />
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
