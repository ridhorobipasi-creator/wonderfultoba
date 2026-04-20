"use client";

import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import {
  LayoutDashboard, Package, Car, FileText, CalendarCheck,
  Users, MapPin, LogOut, ChevronDown, Menu,
  Globe, Layers, Layout
} from 'lucide-react';
import { cn } from '@/utils/cn';
import { useStore } from '@/store/useStore';

type NavItem = {
  label: string;
  href: string;
  icon: React.ComponentType<{ size?: number }>;
  exact?: boolean;
};

type NavSection = {
  title: string;
  items: NavItem[];
};

const NAV_SECTIONS: NavSection[] = [
  {
    title: 'Utama',
    items: [
      { label: 'Dashboard', href: '/admin', icon: LayoutDashboard, exact: true },
    ]
  },
  {
    title: 'Manajemen Konten (CMS)',
    items: [
      { label: 'CMS Tour', href: '/admin/cms-tour', icon: Layout },
      { label: 'CMS Outbound', href: '/admin/cms-outbound', icon: Layout },
      { label: 'Blog / Artikel', href: '/admin/blog', icon: FileText },
      { label: 'Wilayah & Kota', href: '/admin/cities', icon: MapPin },
    ]
  },
  {
    title: 'Produk & Layanan',
    items: [
      { label: 'Paket Wisata & Outbound', href: '/admin/packages', icon: Package },
      { label: 'Armada Mobil', href: '/admin/cars', icon: Car },
    ]
  },
  {
    title: 'Transaksi',
    items: [
      { label: 'Daftar Pesanan', href: '/admin/bookings', icon: CalendarCheck },
      { label: 'Laporan Keuangan', href: '/admin/finance', icon: Globe },
    ]
  },
  {
    title: 'Pengaturan',
    items: [
      { label: 'Pengguna', href: '/admin/users', icon: Users },
    ]
  }
];

function NavItemLink({
  item,
  isActive,
  onClick,
}: {
  item: NavItem;
  isActive: (href: string, exact?: boolean) => boolean;
  onClick: () => void;
}) {
  return (
    <Link
      href={item.href}
      onClick={onClick}
      className={cn(
        'flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-sm transition-all duration-200',
        isActive(item.href, item.exact)
          ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20'
          : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900'
      )}
    >
      <item.icon size={18} />
      {item.label}
    </Link>
  );
}

function SidebarContent({
  isActive,
  closeSidebar,
  handleLogout,
}: {
  isActive: (href: string, exact?: boolean) => boolean;
  closeSidebar: () => void;
  handleLogout: () => void;
}) {
  return (
    <div className="flex flex-col h-full bg-white border-r border-slate-100">
      {/* Logo */}
      <div className="p-6 border-b border-slate-100">
        <Link href="/" className="flex items-center gap-3">
          <div className="w-10 h-10 bg-toba-green rounded-xl flex items-center justify-center font-black text-white text-lg shadow-md">W</div>
          <div>
            <p className="font-black text-slate-900 leading-none">Wonderful</p>
            <p className="font-black text-toba-green leading-none">Toba</p>
          </div>
        </Link>
        <div className="mt-4 px-2 py-1.5 bg-slate-50 rounded-xl flex items-center gap-2 text-xs font-bold text-slate-400">
          <Globe size={12} />
          <Link href="/" className="hover:text-toba-green transition-colors">Lihat Website</Link>
        </div>
      </div>

      {/* Navigation */}
      <nav className="flex-1 p-4 overflow-y-auto space-y-6">
        {NAV_SECTIONS.map((section, sidx) => (
          <div key={sidx} className="space-y-1">
            <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-300 px-4 py-2 mt-2">
              {section.title}
            </p>
            {section.items.map(item => (
              <NavItemLink key={item.href} item={item} isActive={isActive} onClick={closeSidebar} />
            ))}
          </div>
        ))}
      </nav>

      {/* Footer */}
      <div className="p-4 border-t border-slate-100">
        <button
          onClick={handleLogout}
          className="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 font-bold text-sm transition-all"
        >
          <LogOut size={18} />
          Keluar
        </button>
      </div>
    </div>
  );
}

export default function AdminShell({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const safePathname = pathname ?? '';
  const router = useRouter();
  const { setUser } = useStore();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const isActive = (href: string, exact?: boolean) => {
    if (exact) return safePathname === href;
    return safePathname.startsWith(href);
  };

  const handleLogout = () => {
    setUser(null);
    router.push('/');
  };

  const closeSidebar = () => setSidebarOpen(false);

  return (
    <div className="min-h-screen bg-slate-50 flex">
      {/* Desktop Sidebar */}
      <aside className="hidden lg:flex flex-col w-72 shrink-0 sticky top-0 h-screen">
        <SidebarContent
          isActive={isActive}
          closeSidebar={closeSidebar}
          handleLogout={handleLogout}
        />
      </aside>

      {/* Mobile Sidebar Overlay */}
      <AnimatePresence>
        {sidebarOpen && (
          <>
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              className="fixed inset-0 bg-black/40 z-40 lg:hidden"
              onClick={() => setSidebarOpen(false)}
            />
            <motion.aside
              initial={{ x: -300 }}
              animate={{ x: 0 }}
              exit={{ x: -300 }}
              transition={{ type: 'spring', stiffness: 300, damping: 30 }}
              className="fixed left-0 top-0 bottom-0 w-72 z-50 lg:hidden"
            >
              <SidebarContent
                isActive={isActive}
                closeSidebar={closeSidebar}
                handleLogout={handleLogout}
              />
            </motion.aside>
          </>
        )}
      </AnimatePresence>

      {/* Main Content */}
      <div className="flex-1 flex flex-col min-w-0">
        {/* Mobile Top Bar */}
        <header className="lg:hidden bg-white border-b border-slate-100 px-4 py-3 flex items-center justify-between sticky top-0 z-30">
          <button onClick={() => setSidebarOpen(true)} className="p-2 rounded-xl hover:bg-slate-100 transition-colors">
            <Menu size={22} className="text-slate-700" />
          </button>
          <div className="font-black text-slate-900">
            Wonderful <span className="text-toba-green">Toba</span>
          </div>
          <div className="w-9" />
        </header>

        {/* Page Content */}
        <main className="flex-1 p-6 lg:p-10">
          {children}
        </main>
      </div>
    </div>
  );
}
