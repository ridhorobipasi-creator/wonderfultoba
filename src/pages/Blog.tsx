"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { BlogPost } from '../types';
import { Calendar, User, ArrowRight, Tag, Search, BookOpen } from 'lucide-react';
import { motion } from 'framer-motion';
import { useRouter } from 'next/navigation';
import { Helmet } from 'react-helmet-async';

// Artikel fallback jika API kosong atau gagal
const fallbackPosts: BlogPost[] = [
  {
    id: 'f1',
    title: '10 Alasan Mengapa Danau Toba Wajib Masuk Bucket List Anda',
    content: 'Danau Toba adalah danau vulkanik terbesar di dunia yang terletak di Sumatera Utara. Dengan luas 1.145 km² dan kedalaman hingga 505 meter, danau ini menyimpan keindahan alam yang luar biasa. Di tengahnya terdapat Pulau Samosir yang kaya akan budaya Batak.',
    authorId: 'admin',
    category: 'Destinasi',
    tags: ['danautoba', 'sumut', 'wisata'],
    image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800',
    createdAt: '2026-03-01T00:00:00Z',
  },
  {
    id: 'f2',
    title: 'Panduan Lengkap Trekking di Bukit Lawang untuk Pemula',
    content: 'Bukit Lawang adalah pintu masuk menuju Taman Nasional Gunung Leuser, salah satu hutan hujan tropis tertua di dunia. Di sini Anda bisa bertemu orangutan Sumatera liar dalam habitat aslinya. Panduan ini akan membantu Anda mempersiapkan perjalanan trekking yang aman dan menyenangkan.',
    authorId: 'admin',
    category: 'Panduan',
    tags: ['bukitlawang', 'trekking', 'orangutan'],
    image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800',
    createdAt: '2026-02-20T00:00:00Z',
  },
  {
    id: 'f3',
    title: 'Mandi Lumpur Bersama Gajah Sumatera di Tangkahan',
    content: 'Tangkahan adalah surga tersembunyi di Langkat, Sumatera Utara. Kawasan ekowisata ini menawarkan pengalaman unik berinteraksi langsung dengan gajah Sumatera yang jinak. Anda bisa memandikan gajah di sungai jernih dan menikmati keindahan hutan tropis yang masih alami.',
    authorId: 'admin',
    category: 'Petualangan',
    tags: ['tangkahan', 'gajah', 'ekowisata'],
    image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800',
    createdAt: '2026-02-10T00:00:00Z',
  },
  {
    id: 'f4',
    title: 'Kuliner Khas Batak yang Harus Dicoba Saat ke Sumut',
    content: 'Sumatera Utara tidak hanya kaya akan keindahan alam, tetapi juga memiliki kekayaan kuliner yang menggugah selera. Dari arsik ikan mas, saksang, hingga bika ambon yang manis, setiap hidangan menceritakan kekayaan budaya Batak yang telah diwariskan turun-temurun.',
    authorId: 'admin',
    category: 'Kuliner',
    tags: ['kuliner', 'batak', 'sumut'],
    image: 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?auto=format&fit=crop&q=80&w=800',
    createdAt: '2026-01-28T00:00:00Z',
  },
];

const categories = ['Semua', 'Destinasi', 'Panduan', 'Petualangan', 'Kuliner'];

export default function Blog({ category }: { category?: 'tour' | 'outbound' }) {
  const [posts, setPosts] = useState<BlogPost[]>([]);
  const [loading, setLoading] = useState(true);
  const [activeCategory, setActiveCategory] = useState('Semua');
  const [searchQuery, setSearchQuery] = useState('');
  const router = useRouter();

  useEffect(() => {
    const fetchPosts = async () => {
      try {
        // Filter by category: 'tour' or 'outbound' based on which page we're on
        const params = category ? `?category=${category}` : '';
        const res = await api.get(`/blogs${params}`);
        setPosts(res.data.length > 0 ? res.data : fallbackPosts);
      } catch {
        setPosts(fallbackPosts);
      } finally {
        setLoading(false);
      }
    };
    fetchPosts();
  }, [category]);

  const filtered = posts.filter(p => {
    const matchCat = activeCategory === 'Semua' || p.category === activeCategory;
    const matchSearch = p.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
      p.content.toLowerCase().includes(searchQuery.toLowerCase());
    return matchCat && matchSearch;
  });

  const featured = filtered[0];
  const rest = filtered.slice(1);

  return (
    <div className="bg-slate-50 min-h-screen pb-24">
      <Helmet>
        <title>Blog & Inspirasi Wisata – Wonderful Toba</title>
        <meta name="description" content="Tips, panduan, dan cerita menarik untuk rencana liburan Anda ke Sumatera Utara. Danau Toba, Bukit Lawang, Tangkahan, dan lebih banyak lagi." />
      </Helmet>
      {/* Hero Header */}
      <div className="relative overflow-hidden bg-slate-900 pt-32 pb-20 px-4">
        <div className="absolute inset-0 opacity-20">
          <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000" alt="" className="w-full h-full object-cover" />
        </div>
        <div className="absolute inset-0 bg-gradient-to-b from-slate-900/60 to-slate-900/90" />
        <div className="relative z-10 max-w-7xl mx-auto text-center">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <span className="inline-flex items-center gap-2 px-4 py-1.5 bg-toba-green/20 text-toba-accent text-xs font-bold uppercase tracking-[0.3em] rounded-full mb-5">
              <BookOpen size={12} /> Blog & Inspirasi
            </span>
            <h1 className="text-4xl md:text-6xl font-black text-white mb-5 tracking-tight">
              Inspirasi <span className="text-toba-accent">Perjalanan</span>
            </h1>
            <p className="text-slate-300 text-lg max-w-2xl mx-auto font-medium leading-relaxed mb-8">
              Tips, panduan, dan cerita menarik untuk rencana liburan Anda ke Sumatera Utara.
            </p>
            {/* Search */}
            <div className="max-w-lg mx-auto relative">
              <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
              <input
                type="text"
                placeholder="Cari artikel..."
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
          {categories.map(cat => (
            <button
              key={cat}
              onClick={() => setActiveCategory(cat)}
              className={`shrink-0 px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all ${
                activeCategory === cat
                  ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20'
                  : 'bg-slate-100 text-slate-500 hover:bg-slate-200'
              }`}
            >
              {cat}
            </button>
          ))}
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 mt-12">
        {loading ? (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {[1, 2, 3, 4].map(i => (
              <div key={i} className="h-[500px] bg-white rounded-[2rem] animate-pulse border border-slate-100" />
            ))}
          </div>
        ) : filtered.length === 0 ? (
          <div className="text-center py-24 bg-white rounded-[2rem] border border-slate-100">
            <BookOpen size={48} className="mx-auto text-slate-200 mb-4" />
            <h3 className="text-xl font-bold text-slate-700 mb-2">Artikel tidak ditemukan</h3>
            <p className="text-slate-400">Coba kata kunci atau kategori lain.</p>
          </div>
        ) : (
          <>
            {/* Featured Post */}
            {featured && (
              <motion.article
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                className="group bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl transition-all duration-500 mb-10 md:flex"
              >
                <div className="relative md:w-1/2 h-72 md:h-auto overflow-hidden">
                  <img src={featured.image || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'} alt={featured.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" referrerPolicy="no-referrer" />
                  <div className="absolute top-5 left-5">
                    <span className="bg-toba-green text-white px-4 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg">
                      {featured.category}
                    </span>
                  </div>
                  <div className="absolute top-5 right-5 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-bold text-toba-green">
                    Artikel Pilihan
                  </div>
                </div>
                <div className="md:w-1/2 p-10 flex flex-col justify-center">
                  <div className="flex items-center gap-5 text-slate-400 text-sm mb-5">
                    <span className="flex items-center gap-1.5"><Calendar size={14} className="text-toba-green" />{new Date(featured.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</span>
                    <span className="flex items-center gap-1.5"><User size={14} className="text-toba-green" />Admin</span>
                  </div>
                  <h2 className="text-2xl md:text-3xl font-black text-slate-900 mb-4 group-hover:text-toba-green transition-colors leading-tight">
                    {featured.title}
                  </h2>
                  <p className="text-slate-500 leading-relaxed mb-6 line-clamp-3 font-medium">{featured.content}</p>
                  <div className="flex items-center justify-between">
                    <div className="flex gap-2 flex-wrap">
                      {featured.tags.slice(0, 3).map(tag => (
                        <span key={tag} className="text-xs font-bold text-toba-green bg-toba-green/8 px-3 py-1 rounded-full">#{tag}</span>
                      ))}
                    </div>
                    <button
                      onClick={() => {
                        const scope = window.location.pathname.startsWith('/outbound') ? '/outbound' : '/tour';
                        router.push(`${scope}/blog/${featured.id}`);
                      }}
                      className="flex items-center gap-2 text-toba-green font-black text-sm hover:gap-3 transition-all">
                      Baca <ArrowRight size={16} />
                    </button>
                  </div>
                </div>
              </motion.article>
            )}

            {/* Rest of posts */}
            {rest.length > 0 && (
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {rest.map((post, i) => (
                  <motion.article
                    key={post.id}
                    initial={{ opacity: 0, y: 20 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ delay: i * 0.08 }}
                    className="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-500 group"
                  >
                    <div className="relative h-52 overflow-hidden">
                      <img src={post.image || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'} alt={post.title}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" referrerPolicy="no-referrer" />
                      <div className="absolute top-4 left-4">
                        <span className="bg-toba-green text-white px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                          {post.category}
                        </span>
                      </div>
                    </div>
                    <div className="p-7">
                      <div className="flex items-center gap-4 text-slate-400 text-xs mb-4">
                        <span className="flex items-center gap-1"><Calendar size={12} className="text-toba-green" />{new Date(post.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })}</span>
                        <span className="flex items-center gap-1"><User size={12} className="text-toba-green" />Admin</span>
                      </div>
                      <h2 className="text-lg font-black text-slate-900 mb-3 group-hover:text-toba-green transition-colors leading-tight line-clamp-2">
                        {post.title}
                      </h2>
                      <p className="text-slate-500 text-sm leading-relaxed mb-5 line-clamp-2">{post.content}</p>
                      <div className="flex items-center justify-between pt-4 border-t border-slate-50">
                        <div className="flex gap-1.5 flex-wrap">
                          {post.tags.slice(0, 2).map(tag => (
                            <span key={tag} className="text-[10px] font-bold text-slate-400">#{tag}</span>
                          ))}
                        </div>
                        <button
                          onClick={() => {
                            const scope = window.location.pathname.startsWith('/outbound') ? '/outbound' : '/tour';
                            router.push(`${scope}/blog/${post.id}`);
                          }}
                          className="flex items-center gap-1.5 text-toba-green font-black text-xs hover:gap-2.5 transition-all">
                          Baca <ArrowRight size={14} />
                        </button>
                      </div>
                    </div>
                  </motion.article>
                ))}
              </div>
            )}
          </>
        )}
      </div>
    </div>
  );
}
