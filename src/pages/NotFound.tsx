"use client";

import { motion } from 'framer-motion';
import { useRouter, usePathname } from 'next/navigation';
import Link from 'next/link';
import { Home, Compass, ArrowLeft, Search } from 'lucide-react';
import { Helmet } from 'react-helmet-async';

export default function NotFound() {
  const router = useRouter();
  const pathname = usePathname();
  const safePathname = pathname ?? '';
  const isOutbound = safePathname.startsWith('/outbound');

  return (
    <>
      <Helmet>
        <title>404 – Halaman Tidak Ditemukan | Wonderful Toba</title>
      </Helmet>

      <div className="min-h-screen bg-[#0a0f1a] flex flex-col items-center justify-center px-4 relative overflow-hidden">
        {/* Ambient glows */}
        <div className="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-toba-green/10 rounded-full blur-[120px] pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/5 rounded-full blur-[80px] pointer-events-none" />

        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7 }}
          className="relative z-10 text-center max-w-2xl mx-auto"
        >
          {/* 404 Giant Number */}
          <motion.div
            initial={{ scale: 0.8 }}
            animate={{ scale: 1 }}
            transition={{ type: 'spring', stiffness: 200, damping: 15 }}
            className="relative mb-8"
          >
            <div className="text-[10rem] md:text-[14rem] font-black leading-none text-transparent bg-clip-text bg-gradient-to-b from-slate-700 to-slate-900 select-none">
              404
            </div>
            <div className="absolute inset-0 flex items-center justify-center">
              <div className="text-[10rem] md:text-[14rem] font-black leading-none text-transparent bg-clip-text bg-gradient-to-b from-toba-green/30 to-transparent select-none blur-sm">
                404
              </div>
            </div>
            {/* Floating badge */}
            <motion.div
              animate={{ y: [0, -12, 0] }}
              transition={{ duration: 3, repeat: Infinity, ease: 'easeInOut' }}
              className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-toba-green w-20 h-20 md:w-24 md:h-24 rounded-3xl flex items-center justify-center shadow-2xl shadow-toba-green/40 border-4 border-toba-green/20"
            >
              <Search size={36} className="text-white" strokeWidth={2.5} />
            </motion.div>
          </motion.div>

          <h1 className="text-2xl md:text-4xl font-black text-white mb-4 tracking-tight">
            Halaman Tidak Ditemukan
          </h1>
          <p className="text-slate-400 font-medium text-lg mb-12 leading-relaxed">
            Sepertinya Anda tersesat! Halaman yang Anda cari sudah dipindahkan,<br className="hidden md:block" />
            dihapus, atau memang tidak pernah ada.
          </p>

          {/* Action Buttons */}
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button
               onClick={() => router.back()}
              className="flex items-center space-x-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl transition-all border border-white/10 hover:border-white/20 group"
            >
              <ArrowLeft size={20} className="group-hover:-translate-x-1 transition-transform" />
              <span>Kembali</span>
            </button>

            <Link href="/"
              className="flex items-center space-x-2 px-6 py-3.5 bg-toba-green hover:bg-toba-green/90 text-white font-bold rounded-2xl transition-all shadow-lg shadow-toba-green/30 group"
            >
              <Home size={20} />
              <span>Ke Beranda</span>
            </Link>

            <Link href={isOutbound ? '/outbound' : '/tour'}
              className="flex items-center space-x-2 px-6 py-3.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-2xl transition-all border border-slate-700"
            >
              <Compass size={20} className="text-toba-green" />
              <span>{isOutbound ? 'Outbound' : 'Tour & Travel'}</span>
            </Link>
          </div>
        </motion.div>

        {/* Bottom decoration */}
        <div className="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-toba-green/30 to-transparent" />
      </div>
    </>
  );
}
