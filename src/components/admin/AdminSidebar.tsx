"use client";


import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import {
  LayoutDashboard, Home, FileText, MapPin, Package, Car, CalendarCheck, Globe2, Users, LogOut, Settings, Layers, Box, ExternalLink, BookOpen
} from 'lucide-react';
import { cn } from '@/utils/cn';
import { useStore } from '@/store/useStore';
import api from '@/lib/api';
import { toast } from 'sonner';


export default function AdminSidebar() {
  const pathname = usePathname();
  const router = useRouter();
  const { setToken, setUser } = useStore();

  const handleLogout = async () => {
    try {
      await api.post('/auth/logout');
    } catch {
      // ignore
    } finally {
      setToken(null);
      setUser(null);
      toast.success('Berhasil keluar');
      router.push('/');
    }
  };

  // Menu sesuai gambar
  const menuGroups = [
    {
      label: 'UTAMA',
      items: [
        { label: 'Dashboard', href: '/admin', icon: LayoutDashboard, pattern: /^\/admin$/ },
      ],
    },
    {
      label: 'MANAJEMEN KONTEN (CMS)',
      items: [
        { label: 'Pengaturan Beranda', href: '/admin/cms-tour', icon: Home, pattern: /\/cms-tour/ },
        { label: 'Blog / Artikel', href: '/admin/blog', icon: FileText, pattern: /\/blog/ },
        { label: 'Wilayah & Kota', href: '/admin/cities', icon: MapPin, pattern: /\/cities/ },
      ],
    },
    {
      label: 'PRODUK & LAYANAN',
      items: [
        { label: 'Paket Wisata', href: '/admin/packages', icon: Package, pattern: /\/packages/ },
        { label: 'Armada Mobil', href: '/admin/cars', icon: Car, pattern: /\/cars/ },
      ],
    },
    {
      label: 'TRANSAKSI',
      items: [
        { label: 'Daftar Pesanan', href: '/admin/bookings', icon: CalendarCheck, pattern: /\/bookings/ },
        { label: 'Laporan Keuangan', href: '/admin/finance', icon: Globe2, pattern: /\/finance/ },
      ],
    },
    {
      label: 'PENGATURAN',
      items: [
        { label: 'Pengguna', href: '/admin/users', icon: Users, pattern: /\/users/ },
      ],
    },
    {
      label: '🧑‍🤝‍🧑OUTBOUND',
      items: [
        { label: 'Pengaturan Beranda', href: '/admin/cms-outbound', icon: Home, pattern: /\/cms-outbound/ },
        { label: 'Paket Outbound', href: '/admin/outbound', icon: Layers, pattern: /\/outbound/ },
      ],
    },
  ];

  const isActive = (pattern: RegExp) => pattern.test(pathname ?? "");

  return (
    <aside className="w-64 flex flex-col min-h-screen border-r border-slate-100 bg-white">
      {/* Logo */}
      <div className="flex flex-col items-center gap-2 pt-6 pb-4">
        <div className="w-10 h-10 bg-toba-green rounded-xl flex items-center justify-center shadow-lg shadow-toba-green/20">
          <span className="text-white font-black text-lg">W</span>
        </div>
        <div className="text-center">
          <div className="font-black text-slate-900 leading-tight text-base">Wonderful<br />Toba</div>
        </div>
        <Link href="/" className="mt-3 flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-50 text-slate-400 text-xs font-bold hover:bg-slate-100 transition">
          <ExternalLink className="w-4 h-4" />
          Lihat Website
        </Link>
      </div>
      <hr className="my-6 border-slate-100" />

      {/* Menu Groups */}
      <nav className="flex-1 flex flex-col gap-2">
        {menuGroups.map((group, idx) => (
          <div key={group.label} className={cn(idx === 5 && 'mt-6')}> {/* OUTBOUND group extra margin */}
            <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-300 mb-3 px-6">{idx === 5 ? <span className="inline-block mr-1">🧑‍🤝‍🧑</span> : null}{group.label}</p>
            <div className="flex flex-col gap-1 mb-2">
              {group.items.map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  className={cn(
                    "flex items-center gap-3 px-6 py-3 rounded-2xl font-bold transition-all group",
                    isActive(item.pattern)
                      ? "bg-toba-green text-white shadow-lg"
                      : "text-slate-500 hover:text-toba-green hover:bg-toba-green/10"
                  )}
                >
                  <item.icon className={cn("w-5 h-5", isActive(item.pattern) ? "text-white" : "text-toba-green group-hover:text-toba-green")}/>
                  <span>{item.label}</span>
                </Link>
              ))}
            </div>
          </div>
        ))}
      </nav>

      <div className="mt-auto pt-6 px-6 pb-4">
        <button
          onClick={handleLogout}
          className="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold text-slate-400 hover:text-toba-green hover:bg-toba-green/10 transition-all group"
        >
          <LogOut className="w-5 h-5" />
          <span>Keluar</span>
        </button>
      </div>
    </aside>
  );
}
