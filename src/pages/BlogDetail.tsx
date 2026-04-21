"use client";

import { useState, useEffect } from 'react';
import { useRouter, usePathname, useParams } from 'next/navigation';
import Link from 'next/link';
import api from '../lib/api';
import { BlogPost } from '../types';
import { motion } from 'framer-motion';
import { ArrowLeft, Calendar, User, Tag, Share2, MessageCircle, Instagram, Clock, BookOpen } from 'lucide-react';
import { Helmet } from 'react-helmet-async';
import { BlogDetailSkeleton } from '../components/Skeleton';

const fallbackPosts: BlogPost[] = [
  { id: 'f1', title: '10 Alasan Mengapa Danau Toba Wajib Masuk Bucket List Anda', content: 'Danau Toba adalah danau vulkanik terbesar di dunia yang terletak di Sumatera Utara. Dengan luas 1.145 km² dan kedalaman hingga 505 meter, danau ini menyimpan keindahan alam yang luar biasa. Di tengahnya terdapat Pulau Samosir yang kaya akan budaya Batak.\n\nSetiap sudut danau ini menawarkan pemandangan yang memukau, dari tebing-tebing curam hingga hamparan air biru yang tenang. Budaya Batak yang kaya juga menjadi daya tarik tersendiri, dengan rumah adat Bolon, tari Tor-Tor, dan musik gondang yang masih lestari hingga kini.\n\nDanau Toba juga memiliki potensi ekowisata yang luar biasa. Banyak resort dan penginapan bergaya alam yang tersedia di sekitar danau, mulai dari yang budget-friendly hingga yang mewah. Aksesibilitas ke Danau Toba juga semakin mudah dengan adanya Bandara Silangit yang kini melayani penerbangan domestik dari berbagai kota besar di Indonesia.', authorId: 'admin', category: 'Destinasi', tags: ['danautoba', 'sumut', 'wisata'], image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=1200', createdAt: '2026-03-01T00:00:00Z' },
  { id: 'f2', title: 'Panduan Lengkap Trekking di Bukit Lawang untuk Pemula', content: 'Bukit Lawang adalah pintu masuk menuju Taman Nasional Gunung Leuser, salah satu hutan hujan tropis tertua di dunia. Di sini Anda bisa bertemu orangutan Sumatera liar dalam habitat aslinya.\n\nPanduan ini akan membantu Anda mempersiapkan perjalanan trekking yang aman dan menyenangkan. Pastikan membawa perlengkapan yang tepat, termasuk sepatu trekking, jas hujan, dan air minum yang cukup.\n\nSelalu ikuti panduan dari guide lokal yang berpengalaman untuk keselamatan Anda. Trek tersedia dari 1 hari hingga 3 hari semalam, dengan tingkat kesulitan yang bervariasi untuk memenuhi berbagai level kebugaran.', authorId: 'admin', category: 'Panduan', tags: ['bukitlawang', 'trekking', 'orangutan'], image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=1200', createdAt: '2026-02-20T00:00:00Z' },
  { id: 'f3', title: 'Mandi Lumpur Bersama Gajah Sumatera di Tangkahan', content: 'Tangkahan adalah surga tersembunyi di Langkat, Sumatera Utara. Kawasan ekowisata ini menawarkan pengalaman unik berinteraksi langsung dengan gajah Sumatera yang jinak.\n\nAnda bisa memandikan gajah di sungai jernih dan menikmati keindahan hutan tropis yang masih alami. Tangkahan juga dikenal sebagai "The Hidden Paradise" karena keindahan alamnya yang belum banyak terjamah wisatawan.\n\nSungai Batang Serangan yang jernih mengalir di tengah hutan lebat menjadi tempat bermain yang sempurna bagi gajah-gajah jinak di sini.', authorId: 'admin', category: 'Petualangan', tags: ['tangkahan', 'gajah', 'ekowisata'], image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=1200', createdAt: '2026-02-10T00:00:00Z' },
  { id: 'f4', title: 'Kuliner Khas Batak yang Harus Dicoba Saat ke Sumut', content: 'Sumatera Utara tidak hanya kaya akan keindahan alam, tetapi juga memiliki kekayaan kuliner yang menggugah selera. Dari arsik ikan mas, saksang, hingga bika ambon yang manis.\n\nSetiap hidangan menceritakan kekayaan budaya Batak yang telah diwariskan turun-temurun. Arsik adalah masakan ikan mas yang dimasak dengan bumbu kuning khas Batak, sementara saksang adalah olahan daging yang kaya rempah.\n\nJangan lupa mencicipi naniura, ikan mas mentah yang dimasak dengan asam jungga, hidangan khas yang unik dan lezat.', authorId: 'admin', category: 'Kuliner', tags: ['kuliner', 'batak', 'sumut'], image: 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?auto=format&fit=crop&q=80&w=1200', createdAt: '2026-01-28T00:00:00Z' },
  { id: 'b1', title: 'Pentingnya Trust Building Dalam Organisasi Modern', content: 'Kepercayaan adalah fondasi dari setiap organisasi yang kuat. Tanpa kepercayaan, tim tidak bisa berkolaborasi dengan efektif, komunikasi terhambat, dan produktivitas menurun drastis.\n\nMelalui kegiatan outbound, kepercayaan interanggota tim dapat dibangun secara natural. Permainan yang dirancang khusus mengharuskan peserta untuk saling bergantung satu sama lain, menciptakan pengalaman berbagi yang mempererat hubungan.\n\nStudi kasus dari berbagai perusahaan multinasional menunjukkan bahwa investasi dalam trust building terbukti meningkatkan retensi karyawan hingga 40% dan produktivitas tim hingga 30%.', authorId: 'admin', category: 'Outbound', tags: ['teambuilding', 'trust', 'organisasi'], image: '/assets/images/2023/10/005.jpg', createdAt: '2026-03-12T00:00:00Z' },
  { id: 'b2', title: 'Membedah Metode Experiential Learning', content: 'Experiential learning atau pembelajaran berbasis pengalaman adalah metode paling efektif dalam pengembangan kompetensi individu. Berbeda dengan metode konvensional di dalam kelas, experiential learning memungkinkan peserta untuk langsung merasakan, mencoba, dan merefleksikan pengalaman mereka.\n\nDalam konteks outbound, setiap permainan dirancang dengan tujuan pembelajaran yang spesifik. Mulai dari komunikasi efektif, kepemimpinan adaptif, hingga manajemen konflik — semua dikemas dalam format yang menyenangkan namun penuh makna.\n\nPenelitian menunjukkan bahwa manusia mengingat 75% dari apa yang mereka lakukan secara aktif, dibandingkan hanya 5% dari ceramah di kelas.', authorId: 'admin', category: 'Outbound', tags: ['learning', 'experiential', 'metodologi'], image: '/assets/images/2023/10/008.jpg', createdAt: '2026-03-08T00:00:00Z' },
  { id: 'b3', title: 'Persiapan Fisik Sebelum Mengikuti Corporate Rafting', content: 'Arung jeram korporat (corporate rafting) adalah salah satu aktivitas team building yang paling mendebarkan sekaligus efektif. Namun, tanpa persiapan fisik yang memadai, aktivitas ini bisa berujung pada cedera yang tidak diinginkan.\n\nPastikan peserta melakukan pemanasan minimal 15 menit sebelum kegiatan dimulai. Fokus pada peregangan otot bahu, lengan, dan punggung yang paling banyak digunakan saat mendayung.\n\nTim Wonderful Toba selalu menyediakan briefing keselamatan lengkap dan perlengkapan standar internasional sebelum setiap sesi arung jeram.', authorId: 'admin', category: 'Outbound', tags: ['rafting', 'safety', 'persiapan'], image: '/assets/images/2023/10/0010.jpg', createdAt: '2026-02-24T00:00:00Z' },
];

export default function BlogDetail() {
  const params = useParams<{ id: string }>();
  const id = params?.id;
  const router = useRouter();
  const pathname = usePathname();
  const safePathname = pathname ?? '';
  const isOutbound = safePathname.startsWith('/outbound');
  const scope = isOutbound ? '/outbound' : '/tour';

  const [post, setPost] = useState<BlogPost | null>(null);
  const [loading, setLoading] = useState(true);
  const [showShareMenu, setShowShareMenu] = useState(false);
  const [relatedPosts, setRelatedPosts] = useState<BlogPost[]>([]);

  useEffect(() => {
    if (!id) return;
    const fallback = fallbackPosts.find(p => p.id === id);
    if (fallback) {
      setPost(fallback);
      setRelatedPosts(fallbackPosts.filter(p => p.id !== id && (isOutbound ? ['Outbound'].includes(p.category) : !['Outbound'].includes(p.category))).slice(0, 3));
      setLoading(false);
      return;
    }
    const fetchPost = async () => {
      try {
        const [postRes, relatedRes] = await Promise.all([
          api.get(`/blogs/${id}`),
          api.get(`/blogs?category=${isOutbound ? 'outbound' : 'tour'}`),
        ]);
        setPost(postRes.data);
        setRelatedPosts((relatedRes.data || []).filter((p: BlogPost) => String(p.id) !== String(id)).slice(0, 3));
      } catch {
        router.push(`${scope}/blog`);
      } finally {
        setLoading(false);
      }
    };
    fetchPost();
  }, [id, router, scope, isOutbound]);

  // Estimate read time
  const readTime = post ? Math.max(1, Math.round(post.content.split(' ').length / 200)) : 0;

  const handleShareWA = () => {
    const text = `Baca artikel ini dari Wonderful Toba:\n*${post?.title}*\n\n${window.location.href}`;
    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
  };

  const handleShareIG = () => {
    navigator.clipboard.writeText(window.location.href);
    alert('Link disalin! Bisa ditempel di Instagram Story kamu.');
  };

  if (loading) return <BlogDetailSkeleton />;
  if (!post) return null;

  return (
    <>
      <Helmet>
        <title>{post.title} – Wonderful Toba Blog</title>
        <meta name="description" content={post.content.slice(0, 160)} />
        <meta property="og:title" content={post.title} />
        <meta property="og:image" content={post.image} />
        <meta property="og:type" content="article" />
      </Helmet>

      <div className="bg-slate-50 min-h-screen pb-24 page-transition">
        {/* Hero Image */}
        <div className="relative h-[55vh] overflow-hidden">
          <img src={post.image} alt={post.title} className="w-full h-full object-cover scale-105" referrerPolicy="no-referrer" />
          <div className="absolute inset-0 bg-gradient-to-b from-slate-900/20 via-slate-900/40 to-slate-900/90" />
          <div className="absolute bottom-0 left-0 right-0 p-8 max-w-4xl mx-auto">
            <div className="flex items-center gap-3 mb-4">
              <span className="inline-block px-4 py-1.5 bg-toba-green text-white text-xs font-black uppercase tracking-widest rounded-full shadow-lg">
                {post.category}
              </span>
            </div>
            <h1 className="text-3xl md:text-5xl font-black text-white leading-tight drop-shadow-lg">{post.title}</h1>
          </div>
        </div>

        <div className="max-w-4xl mx-auto px-4 mt-8">
          {/* Back */}
          <button onClick={() => router.push(`${scope}/blog`)} className="inline-flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-8 transition-colors group">
            <ArrowLeft size={18} className="group-hover:-translate-x-1 transition-transform" /> Kembali ke Blog
          </button>

          <div className="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-8 items-start">
            {/* Main Article */}
            <motion.article initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-slate-100">
              {/* Meta */}
              <div className="flex flex-wrap items-center gap-4 text-slate-400 text-sm mb-8 pb-8 border-b border-slate-100">
                <span className="flex items-center gap-1.5">
                  <Calendar size={14} className="text-toba-green" />
                  {new Date(post.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                </span>
                <span className="flex items-center gap-1.5">
                  <User size={14} className="text-toba-green" /> Tim Wonderful Toba
                </span>
                <span className="flex items-center gap-1.5">
                  <Clock size={14} className="text-toba-green" /> {readTime} menit baca
                </span>
                <div className="flex gap-2 flex-wrap">
                  {post.tags.map(tag => (
                    <span key={tag} className="flex items-center gap-1 text-xs font-bold text-toba-green bg-toba-green/10 px-3 py-1 rounded-full">
                      <Tag size={10} />#{tag}
                    </span>
                  ))}
                </div>
              </div>

              {/* Content */}
              <div className="prose prose-slate max-w-none prose-p:text-slate-600 prose-p:leading-relaxed prose-p:text-lg prose-headings:text-slate-900 prose-headings:font-black">
                {post.content.split('\n').map((para, i) => para.trim() ? (
                  para.startsWith('#') ? (
                    <h2 key={i} className="text-2xl font-black text-slate-900 mt-10 mb-4">{para.replace(/^#+\s/, '')}</h2>
                  ) : (
                    <p key={i} className="text-slate-600 leading-relaxed font-medium mb-6 text-lg">{para}</p>
                  )
                ) : <br key={i} />)}
              </div>

              {/* Share Bar */}
              <div className="mt-12 pt-8 border-t border-slate-100">
                <div className="flex items-center justify-between flex-wrap gap-4">
                  <p className="font-black text-slate-900">Bagikan artikel ini:</p>
                  <div className="flex items-center gap-3">
                    <button
                      onClick={handleShareWA}
                      className="flex items-center gap-2 bg-[#25D366] text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-[#1da851] transition-colors shadow-lg shadow-[#25D366]/20"
                    >
                      <MessageCircle size={18} /> WhatsApp
                    </button>
                    <button
                      onClick={handleShareIG}
                      className="flex items-center gap-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:opacity-90 transition-opacity shadow-lg shadow-purple-500/20"
                    >
                      <Instagram size={18} /> Salin Link
                    </button>
                  </div>
                </div>
              </div>
            </motion.article>

            {/* Sidebar */}
            <div className="lg:sticky lg:top-28 space-y-6">
              {/* Author Box */}
              <div className="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
                <div className="flex items-center gap-4 mb-4">
                  <div className="w-14 h-14 bg-gradient-to-br from-toba-green to-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-xl">W</div>
                  <div>
                    <p className="font-black text-slate-900">Tim Wonderful Toba</p>
                    <p className="text-xs font-bold text-toba-green uppercase tracking-wider">Official Writer</p>
                  </div>
                </div>
                <p className="text-slate-500 text-sm font-medium leading-relaxed">
                  Kami berbagi tips, panduan, dan inspirasi wisata terbaik di Sumatera Utara untuk perjalanan tak terlupakan.
                </p>
              </div>

              {/* CTA Box */}
              <div className="bg-slate-900 rounded-[2rem] p-6 shadow-sm text-white relative overflow-hidden">
                <div className="absolute top-0 right-0 w-32 h-32 bg-toba-green/20 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3" />
                <BookOpen size={28} className="text-toba-green mb-3 relative z-10" />
                <h3 className="font-black text-lg mb-2 relative z-10">{isOutbound ? 'Rencanakan Event?' : 'Siap Berwisata?'}</h3>
                <p className="text-slate-400 text-sm mb-5 relative z-10">
                  {isOutbound ? 'Konsultasikan kebutuhan outbound instansi Anda bersama tim kami.' : 'Temukan paket perjalanan terbaik ke destinasi impian Anda.'}
                </p>
                <a
                  href={`https://wa.me/6281323888207?text=${encodeURIComponent(isOutbound ? 'Halo Kak, saya ingin konsultasi program outbound untuk perusahaan kami.' : 'Halo Kak, saya ingin tanya paket wisata tersedia.')}`}
                  target="_blank"
                  rel="noreferrer"
                  className="block w-full py-3 bg-toba-green text-white font-black text-sm rounded-2xl text-center hover:bg-toba-green/90 transition-colors relative z-10 shadow-lg shadow-toba-green/20"
                >
                  Hubungi Kami
                </a>
              </div>
            </div>
          </div>

          {/* Related Posts */}
          {relatedPosts.length > 0 && (
            <div className="mt-16">
              <h3 className="text-2xl font-black text-slate-900 mb-8">Artikel Terkait</h3>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {relatedPosts.map(p => (
                  <Link key={p.id} href={`${scope}/blog/${p.id}`}
                    className="bg-white rounded-[1.5rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div className="h-44 overflow-hidden">
                      <img src={p.image} alt={p.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" referrerPolicy="no-referrer" />
                    </div>
                    <div className="p-5">
                      <span className="text-[10px] font-black text-toba-green uppercase tracking-wider bg-toba-green/10 px-2.5 py-1 rounded-full">{p.category}</span>
                      <h4 className="font-black text-slate-900 text-sm mt-3 line-clamp-2 group-hover:text-toba-green transition-colors leading-snug">{p.title}</h4>
                      <p className="text-xs font-medium text-slate-400 mt-2">
                        {new Date(p.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                      </p>
                    </div>
                  </Link>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>
    </>
  );
}
