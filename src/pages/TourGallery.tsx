"use client";

import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Helmet } from 'react-helmet-async';
import { X, ZoomIn, Search, Filter, Image as ImageIcon, ChevronLeft, ChevronRight } from 'lucide-react';
import { cn } from '../utils/cn';

interface GalleryItem {
  id: number;
  imageUrl: string;
  caption?: string;
  category?: string;
  tags?: string[];
}

const CATEGORIES = ['Semua', 'Danau Toba', 'Berastagi', 'Bukit Lawang', 'Tangkahan', 'Samosir', 'Lainnya'];

export default function TourGallery() {
  const [images, setImages] = useState<GalleryItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [activeCategory, setActiveCategory] = useState('Semua');
  const [searchQuery, setSearchQuery] = useState('');
  const [lightbox, setLightbox] = useState<{ open: boolean; index: number }>({ open: false, index: 0 });

  useEffect(() => {
    fetch('/api/gallery?category=tour')
      .then(r => r.json())
      .then(data => {
        if (Array.isArray(data) && data.length > 0) {
          setImages(data);
        } else {
          // Fallback: use assets images
          setImages(generateFallbackImages());
        }
      })
      .catch(() => setImages(generateFallbackImages()))
      .finally(() => setLoading(false));
  }, []);

  function generateFallbackImages(): GalleryItem[] {
    const files = [
      { file: '001-1.jpg', caption: 'Danau Toba dari Ketinggian', category: 'Danau Toba' },
      { file: '002-1.jpg', caption: 'Pemandangan Alam Sumatera Utara', category: 'Danau Toba' },
      { file: '003-1.jpg', caption: 'Aktivitas Wisata Alam', category: 'Lainnya' },
      { file: '004.jpg', caption: 'Keindahan Alam Toba', category: 'Danau Toba' },
      { file: '005.jpg', caption: 'Petualangan di Alam Terbuka', category: 'Bukit Lawang' },
      { file: '006.jpg', caption: 'Sunset di Danau Toba', category: 'Danau Toba' },
      { file: '008.jpg', caption: 'Hutan Tropis Sumatera', category: 'Bukit Lawang' },
      { file: '009-1.jpg', caption: 'Wisata Alam Sumut', category: 'Lainnya' },
      { file: '0010.jpg', caption: 'Keindahan Alam Pegunungan', category: 'Berastagi' },
      { file: 'A11-Team-Building.jpg', caption: 'Aktivitas Bersama', category: 'Lainnya' },
      { file: 'A12-Fun-Games.jpg', caption: 'Fun Games di Alam', category: 'Lainnya' },
      { file: 'A12-Gathering.jpg', caption: 'Gathering Alam Terbuka', category: 'Lainnya' },
      { file: 'A13a-Outbound-Kids.jpg', caption: 'Outbound Anak-anak', category: 'Lainnya' },
      { file: 'A14a-Archery.jpg', caption: 'Memanah di Alam', category: 'Lainnya' },
      { file: '01-The-Hill-Resort-Sibolangit-wonderfultoba_outbound-outbound_medan.jpg', caption: 'The Hill Resort Sibolangit', category: 'Berastagi' },
      { file: '02-Grand-Mutiara-Hotel-Berastagi-wonderfultoba_outbound-outbound_medan.jpg', caption: 'Grand Mutiara Hotel Berastagi', category: 'Berastagi' },
      { file: '06-Taman-Simalem-Resort-Sidikalang-wonderfultoba_outbound-outbound_medan.jpg', caption: 'Taman Simalem Resort', category: 'Berastagi' },
      { file: '07-Hotel-Niagara-Parapat-wonderfultoba_outbound-outbound_medan.jpg', caption: 'Hotel Niagara Parapat', category: 'Danau Toba' },
      { file: '15-Bukit-Lawang-wonderfultoba_outbound-outbound_medan.jpg', caption: 'Bukit Lawang', category: 'Bukit Lawang' },
      { file: '20-Samosir-Villa-Resort-Samosir-wonderfultoba_outbound-outbound_medan.jpg', caption: 'Samosir Villa Resort', category: 'Samosir' },
    ];
    return files.map((f, i) => ({
      id: i + 1,
      imageUrl: `/assets/images/2023/10/${f.file}`,
      caption: f.caption,
      category: f.category,
    }));
  }

  const filtered = images.filter(img => {
    const matchCat = activeCategory === 'Semua' || img.category === activeCategory;
    const matchSearch = !searchQuery ||
      img.caption?.toLowerCase().includes(searchQuery.toLowerCase()) ||
      img.category?.toLowerCase().includes(searchQuery.toLowerCase());
    return matchCat && matchSearch;
  });

  const openLightbox = (index: number) => setLightbox({ open: true, index });
  const closeLightbox = () => setLightbox({ open: false, index: 0 });
  const prevImage = () => setLightbox(prev => ({ ...prev, index: (prev.index - 1 + filtered.length) % filtered.length }));
  const nextImage = () => setLightbox(prev => ({ ...prev, index: (prev.index + 1) % filtered.length }));

  // Keyboard navigation
  useEffect(() => {
    if (!lightbox.open) return;
    const handler = (e: KeyboardEvent) => {
      if (e.key === 'ArrowLeft') prevImage();
      if (e.key === 'ArrowRight') nextImage();
      if (e.key === 'Escape') closeLightbox();
    };
    window.addEventListener('keydown', handler);
    return () => window.removeEventListener('keydown', handler);
  }, [lightbox.open, filtered.length]);

  return (
    <>
      <Helmet>
        <title>Galeri Foto Wisata – Wonderful Toba</title>
        <meta name="description" content="Koleksi foto perjalanan wisata ke Danau Toba, Berastagi, Bukit Lawang, Tangkahan, dan destinasi indah Sumatera Utara lainnya." />
      </Helmet>

      <div className="bg-slate-50 min-h-screen pb-24">
        {/* Hero */}
        <div className="relative bg-slate-900 pt-32 pb-20 px-4 overflow-hidden">
          <div className="absolute inset-0 opacity-20">
            <img src="/assets/images/2023/10/001-1.jpg" alt="" className="w-full h-full object-cover" />
          </div>
          <div className="absolute inset-0 bg-gradient-to-b from-slate-900/60 to-slate-900/90" />
          <div className="relative z-10 max-w-4xl mx-auto text-center">
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
              <span className="inline-flex items-center gap-2 px-4 py-1.5 bg-toba-green/20 text-toba-green text-xs font-bold uppercase tracking-[0.3em] rounded-full mb-5">
                <ImageIcon size={12} /> Galeri Foto
              </span>
              <h1 className="text-4xl md:text-6xl font-black text-white mb-5 tracking-tight">
                Keindahan <span className="text-toba-accent">Sumatera Utara</span>
              </h1>
              <p className="text-slate-300 text-lg max-w-2xl mx-auto font-medium leading-relaxed mb-8">
                Abadikan setiap momen perjalanan Anda bersama Wonderful Toba.
              </p>
              {/* Search */}
              <div className="max-w-md mx-auto relative">
                <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                <input
                  type="text"
                  placeholder="Cari foto..."
                  value={searchQuery}
                  onChange={e => setSearchQuery(e.target.value)}
                  className="w-full pl-14 pr-6 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-toba-green/50 font-medium"
                />
              </div>
            </motion.div>
          </div>
        </div>

        {/* Category Filter */}
        <div className="sticky top-[80px] z-30 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm">
          <div className="max-w-7xl mx-auto px-4 py-4 flex gap-2 overflow-x-auto scrollbar-hide">
            {CATEGORIES.map(cat => (
              <button
                key={cat}
                onClick={() => setActiveCategory(cat)}
                className={cn(
                  "shrink-0 px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all",
                  activeCategory === cat
                    ? "bg-toba-green text-white shadow-lg shadow-toba-green/20"
                    : "bg-slate-100 text-slate-500 hover:bg-slate-200"
                )}
              >
                {cat}
              </button>
            ))}
          </div>
        </div>

        {/* Gallery Grid */}
        <div className="max-w-7xl mx-auto px-4 mt-10">
          {/* Stats */}
          <div className="flex items-center justify-between mb-8">
            <p className="text-slate-500 font-medium text-sm">
              Menampilkan <span className="text-toba-green font-black">{filtered.length}</span> foto
            </p>
          </div>

          {loading ? (
            <div className="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
              {Array.from({ length: 12 }).map((_, i) => (
                <div key={i} className={`break-inside-avoid rounded-2xl bg-slate-200 animate-pulse ${i % 3 === 0 ? 'h-64' : i % 3 === 1 ? 'h-48' : 'h-56'}`} />
              ))}
            </div>
          ) : filtered.length === 0 ? (
            <div className="text-center py-24 bg-white rounded-[2rem] border border-slate-100">
              <ImageIcon size={48} className="mx-auto text-slate-200 mb-4" />
              <h3 className="text-xl font-bold text-slate-700 mb-2">Foto tidak ditemukan</h3>
              <p className="text-slate-400">Coba kategori atau kata kunci lain.</p>
            </div>
          ) : (
            <div className="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
              {filtered.map((img, index) => (
                <motion.div
                  key={img.id}
                  initial={{ opacity: 0, y: 10 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ delay: (index % 8) * 0.05 }}
                  className="break-inside-avoid relative rounded-2xl overflow-hidden group cursor-pointer shadow-sm hover:shadow-xl transition-all duration-300"
                  onClick={() => openLightbox(index)}
                >
                  <img
                    src={img.imageUrl}
                    alt={img.caption || 'Galeri Wonderful Toba'}
                    className="w-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                    loading="lazy"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div className="absolute bottom-0 left-0 right-0 p-4">
                      {img.caption && (
                        <p className="text-white text-xs font-bold leading-tight">{img.caption}</p>
                      )}
                      {img.category && (
                        <span className="text-toba-green text-[10px] font-black uppercase tracking-wider">{img.category}</span>
                      )}
                    </div>
                    <div className="absolute top-3 right-3">
                      <div className="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <ZoomIn size={16} className="text-white" />
                      </div>
                    </div>
                  </div>
                </motion.div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Lightbox */}
      <AnimatePresence>
        {lightbox.open && filtered[lightbox.index] && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-[100] bg-slate-950/95 backdrop-blur-md flex items-center justify-center p-4"
            onClick={closeLightbox}
          >
            {/* Close */}
            <button
              onClick={closeLightbox}
              className="absolute top-5 right-5 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-10"
            >
              <X size={20} />
            </button>

            {/* Counter */}
            <div className="absolute top-5 left-5 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white text-sm font-bold">
              {lightbox.index + 1} / {filtered.length}
            </div>

            {/* Prev */}
            <button
              onClick={(e) => { e.stopPropagation(); prevImage(); }}
              className="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-10"
            >
              <ChevronLeft size={24} />
            </button>

            {/* Image */}
            <motion.div
              key={lightbox.index}
              initial={{ opacity: 0, scale: 0.95 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.95 }}
              className="max-w-5xl max-h-[80vh] relative"
              onClick={e => e.stopPropagation()}
            >
              <img
                src={filtered[lightbox.index].imageUrl}
                alt={filtered[lightbox.index].caption || ''}
                className="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl"
              />
              {filtered[lightbox.index].caption && (
                <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/80 to-transparent p-6 rounded-b-2xl">
                  <p className="text-white font-bold">{filtered[lightbox.index].caption}</p>
                  {filtered[lightbox.index].category && (
                    <p className="text-toba-green text-xs font-black uppercase tracking-wider mt-1">{filtered[lightbox.index].category}</p>
                  )}
                </div>
              )}
            </motion.div>

            {/* Next */}
            <button
              onClick={(e) => { e.stopPropagation(); nextImage(); }}
              className="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-10"
            >
              <ChevronRight size={24} />
            </button>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}
