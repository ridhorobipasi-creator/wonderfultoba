"use client";

import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Helmet } from 'react-helmet-async';
import { MessageCircle, CheckCircle, Users, Target, Sparkles, ArrowRight, Phone, Star, Clock, MapPin } from 'lucide-react';
import Link from 'next/link';

interface PackageTier {
  id: number;
  tier: string;
  tagline: string;
  badge: string | null;
  color: string;
  priceLabel: string;
  price: string;
  unit: string;
  duration: string;
  capacity: string;
  location: string;
  features: string[];
  excludes: string[];
}

export default function OutboundPackages() {
  const [packages, setPackages] = useState<PackageTier[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchPackages() {
      try {
        const res = await fetch('/api/package-tiers?category=outbound');
        const data = await res.json();
        
        // Map database fields to component fields
        const mappedPackages = data.map((tier: any) => ({
          id: tier.id,
          tier: tier.tierName,
          tagline: tier.tagline,
          badge: tier.badge,
          color: tier.colorTheme,
          priceLabel: tier.priceLabel,
          price: tier.price,
          unit: tier.unit,
          duration: tier.duration,
          capacity: tier.capacity,
          location: tier.location,
          features: tier.features || [],
          excludes: tier.excludes || []
        }));
        
        setPackages(mappedPackages);
      } catch (error) {
        console.error('Failed to fetch package tiers:', error);
      } finally {
        setLoading(false);
      }
    }
    fetchPackages();
  }, []);

  const waMessage = (tier: string) =>
    encodeURIComponent(`Halo Kak Wonderful Toba, saya tertarik dengan *Paket Outbound ${tier}* untuk kegiatan perusahaan kami. Bisa minta penawaran harga dan proposal kegiatannya?`);

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-screen bg-slate-50">
        <div className="w-16 h-16 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin mb-4" />
        <p className="text-slate-600 font-medium">Memuat paket...</p>
      </div>
    );
  }

  return (
    <>
      <Helmet>
        <title>Pricelist & Paket Outbound | Wonderful Toba Outbound</title>
        <meta name="description" content="Pilih paket outbound terbaik untuk perusahaan Anda — Basic, Standard, atau Premium. Trainer berpengalaman, venue premium, konsep kreatif." />
      </Helmet>

      <div className="bg-[#fdfdfd] min-h-screen page-transition">
        {/* Hero Banner */}
        <section className="relative bg-[#090e17] py-32 overflow-hidden">
          <div className="absolute inset-0 bg-[url('/assets/images/2023/10/outbound-hadena-indonesia-experience-1024x723-1-1024x430.jpg')] bg-cover bg-center opacity-10 grayscale" />
          <div className="absolute top-0 right-0 w-96 h-96 bg-toba-green/15 rounded-full blur-[100px]" />
          <div className="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[80px]" />

          <div className="max-w-4xl mx-auto px-4 text-center relative z-10">
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
              <span className="inline-flex items-center space-x-2 text-toba-green font-black uppercase tracking-[0.3em] text-xs mb-8 bg-toba-green/10 px-5 py-2.5 rounded-full border border-toba-green/20">
                <Sparkles size={14} /> <span>Pricelist Resmi 2026</span>
              </span>
              <h1 className="text-4xl md:text-6xl font-black text-white mb-6 leading-tight tracking-tight">
                Paket <span className="text-transparent bg-clip-text bg-gradient-to-r from-toba-green to-emerald-400">Outbound</span> Kami
              </h1>
              <p className="text-slate-400 font-medium text-lg max-w-2xl mx-auto leading-relaxed mb-10">
                Kami menyediakan tiga tingkatan paket yang dapat disesuaikan dengan kebutuhan dan anggaran perusahaan Anda.
              </p>
              <div className="flex flex-wrap justify-center gap-6 text-sm font-bold text-slate-400">
                {[
                  { icon: Star, text: '12+ Tahun Pengalaman' },
                  { icon: Users, text: '500+ Event Sukses' },
                  { icon: MapPin, text: '25+ Venue Partner' },
                ].map(({ icon: Icon, text }) => (
                  <div key={text} className="flex items-center gap-2">
                    <Icon size={16} className="text-toba-green" /> <span>{text}</span>
                  </div>
                ))}
              </div>
            </motion.div>
          </div>
        </section>

        {/* Pricing Tiers */}
        <section className="py-20 px-4">
          <div className="max-w-6xl mx-auto">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
              {packages.map((pkg, idx) => (
                <motion.div
                  key={pkg.tier}
                  initial={{ opacity: 0, y: 40 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: idx * 0.15 }}
                  className={`relative rounded-[2.5rem] overflow-hidden flex flex-col ${pkg.badge === 'TERPOPULER' ? 'ring-4 ring-toba-green/40 scale-[1.02] shadow-2xl shadow-toba-green/10' : 'border border-slate-200 shadow-sm'}`}
                >
                  {/* Badge */}
                  {pkg.badge && (
                    <div className="absolute top-5 right-5 z-20 bg-toba-green text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-lg">
                      {pkg.badge}
                    </div>
                  )}

                  {/* Header */}
                  <div className={`bg-gradient-to-br ${pkg.color} p-8 text-white relative overflow-hidden`}>
                    <div className="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full" />
                    <div className="absolute -left-4 -bottom-4 w-24 h-24 bg-white/5 rounded-full" />
                    <p className="text-xs font-black uppercase tracking-[0.25em] text-white/70 mb-2">{pkg.tier}</p>
                    <h2 className="text-3xl font-black mb-1 relative z-10">{pkg.tagline}</h2>
                    <div className="mt-6 relative z-10">
                      <span className="text-sm font-bold text-white/70">{pkg.priceLabel}</span>
                      <div className="text-4xl font-black leading-none mt-1">{pkg.price}</div>
                      <div className="text-sm text-white/60 font-medium mt-1">{pkg.unit}</div>
                    </div>
                  </div>

                  {/* Details */}
                  <div className="bg-white flex-1 flex flex-col p-8">
                    {/* Info Pills */}
                    <div className="flex flex-col gap-2 mb-6 pb-6 border-b border-slate-100">
                      <div className="flex items-center gap-2 text-sm font-bold text-slate-600">
                        <Clock size={15} className="text-toba-green shrink-0" /> {pkg.duration}
                      </div>
                      <div className="flex items-center gap-2 text-sm font-bold text-slate-600">
                        <Users size={15} className="text-toba-green shrink-0" /> {pkg.capacity}
                      </div>
                      <div className="flex items-center gap-2 text-sm font-bold text-slate-600">
                        <MapPin size={15} className="text-toba-green shrink-0" /> {pkg.location}
                      </div>
                    </div>

                    {/* Features */}
                    <ul className="space-y-3 flex-1">
                      {pkg.features.map((f, i) => (
                        <li key={i} className="flex items-start gap-3 text-sm font-medium text-slate-700">
                          <CheckCircle size={16} className="text-toba-green shrink-0 mt-0.5" strokeWidth={2.5} />
                          {f}
                        </li>
                      ))}
                      {pkg.excludes.map((f, i) => (
                        <li key={`ex-${i}`} className="flex items-start gap-3 text-sm font-medium text-slate-400 line-through">
                          <span className="w-4 h-4 rounded-full border border-slate-200 shrink-0 mt-0.5" />
                          {f}
                        </li>
                      ))}
                    </ul>

                    {/* CTA */}
                    <a
                      href={`https://wa.me/6281323888207?text=${waMessage(pkg.tier)}`}
                      target="_blank"
                      rel="noreferrer"
                      className={`mt-8 flex items-center justify-center gap-3 w-full py-4 rounded-2xl font-black text-sm tracking-wide transition-all ${
                        pkg.badge === 'TERPOPULER'
                          ? 'bg-toba-green text-white hover:bg-toba-green/90 shadow-lg shadow-toba-green/30'
                          : 'bg-slate-900 text-white hover:bg-toba-green border border-slate-800 hover:border-toba-green'
                      }`}
                    >
                      <MessageCircle size={18} />
                      {pkg.price === 'Custom Quote' ? 'Minta Proposal' : 'Konsultasi Gratis'}
                    </a>
                  </div>
                </motion.div>
              ))}
            </div>
          </div>
        </section>

        {/* Add-on Services */}
        <section className="py-16 bg-slate-50 border-y border-slate-200">
          <div className="max-w-5xl mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-black text-slate-900 mb-3">Layanan Tambahan</h2>
              <p className="text-slate-500 font-medium">Tersedia sebagai add-on untuk semua tier paket</p>
            </div>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              {[
                { label: 'Leadership Talk / Seminar Motivasi', icon: Target },
                { label: 'Paintball & Archery Game', icon: Target },
                { label: 'Gathering Malam / Gala Dinner', icon: Sparkles },
                { label: 'Sewa Bus Pariwisata', icon: MapPin },
              ].map(({ label, icon: Icon }) => (
                <div key={label} className="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm text-center hover:shadow-md hover:border-toba-green/30 transition-all">
                  <div className="w-12 h-12 bg-toba-green/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <Icon size={22} className="text-toba-green" />
                  </div>
                  <p className="text-sm font-bold text-slate-700 leading-snug">{label}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Bottom CTA */}
        <section className="py-20 bg-slate-900 text-white text-center px-4 relative overflow-hidden">
          <div className="absolute inset-0 opacity-5" style={{ backgroundImage: 'radial-gradient(circle, #10B981 1px, transparent 1px)', backgroundSize: '30px 30px' }} />
          <div className="relative z-10 max-w-2xl mx-auto">
            <Phone size={40} className="text-toba-green mx-auto mb-6" />
            <h2 className="text-3xl md:text-4xl font-black mb-4">Bingung Pilih Paket?</h2>
            <p className="text-slate-400 font-medium mb-8 text-lg leading-relaxed">
              Tim kami siap membantu menyusun proposal dan penawaran harga yang paling sesuai dengan kebutuhan spesifik perusahaan Anda. <strong className="text-white">Konsultasi 100% gratis!</strong>
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <a
                href={`https://wa.me/6281323888207?text=${encodeURIComponent('Halo Kak, saya ingin konsultasi gratis untuk program outbound perusahaan kami. Tolong bantu rekomendasi paket yang sesuai.')}`}
                target="_blank"
                rel="noreferrer"
                className="inline-flex items-center justify-center gap-3 bg-toba-green text-white px-8 py-4 rounded-2xl font-black hover:bg-toba-green/90 transition-all shadow-xl shadow-toba-green/30"
              >
                <MessageCircle size={22} /> WhatsApp Sekarang
              </a>
              <Link href="/outbound" className="inline-flex items-center justify-center gap-3 bg-white/10 text-white px-8 py-4 rounded-2xl font-black hover:bg-white/20 transition-all border border-white/10">
                Kembali ke Beranda <ArrowRight size={20} />
              </Link>
            </div>
          </div>
        </section>
      </div>
    </>
  );
}
