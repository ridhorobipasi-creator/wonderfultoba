"use client";

import { useRouter, usePathname } from 'next/navigation';
import Link from 'next/link';
import { LayoutDashboard, Layout, Package, Car, BookOpen, Users, LogOut, Menu, X, Bell, Search, User, MapPin } from 'lucide-react';
import { useState } from 'react';
import api from '../lib/api';
import { useStore } from '../store/useStore';
import { cn } from '../utils/cn';
import { toast } from 'sonner';

export default function AdminLayout({ children, category = 'tour' }: { children: React.ReactNode, category?: 'tour' | 'outbound' }) {
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);
  const [isMobileOpen, setIsMobileOpen] = useState(false);
  const router = useRouter();
  const pathname = usePathname();
  const { user, setToken, setUser } = useStore();

  const handleLogout = async () => {
    try {
      await api.post('/auth/logout');
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      setToken(null);
      setUser(null);
      toast.success('Berhasil keluar dari panel admin');
      router.push('/');
    }
  };

  const basePath = category === 'outbound' ? '/admin/outbound' : '/admin/tour';
  
  const navItems = category === 'outbound' ? [
    { icon: LayoutDashboard, label: 'Dashboard Outbound', path: `${basePath}` },
    { icon: Layout, label: 'Landing Page', path: `${basePath}/landing-page` },
    { icon: Package, label: 'Paket Outbound', path: `${basePath}/packages` },
    { icon: MapPin, label: 'Lokasi Venue', path: `${basePath}/cities` },
    { icon: BookOpen, label: 'Artikel Outbound', path: `${basePath}/blog` },
    { icon: BookOpen, label: 'Pemesanan', path: `${basePath}/bookings` },
    { icon: Users, label: 'Pengguna', path: `${basePath}/users` },
  ] : [
    { icon: LayoutDashboard, label: 'Dashboard Tour', path: `${basePath}` },
    { icon: Layout, label: 'Landing Page', path: `${basePath}/landing-page` },
    { icon: Package, label: 'Paket Wisata', path: `${basePath}/packages` },
    { icon: MapPin, label: 'Wilayah Tour', path: `${basePath}/cities` },
    { icon: Car, label: 'Armada Mobil', path: `${basePath}/cars` },
    { icon: BookOpen, label: 'Artikel Blog', path: `${basePath}/blog` },
    { icon: BookOpen, label: 'Pemesanan', path: `${basePath}/bookings` },
    { icon: Users, label: 'Pengguna', path: `${basePath}/users` },
  ];

  const NavLinks = ({ collapsed }: { collapsed: boolean }) => (
    <>
      <nav className="flex-grow py-8 px-4 space-y-2 overflow-y-auto">
        {navItems.map((item) => {
          const isActive = pathname === item.path;
          return (
            <Link key={item.path}
              href={item.path}
              onClick={() => setIsMobileOpen(false)}
              className={cn(
                "flex items-center h-14 px-4 rounded-2xl transition-all duration-300 group relative",
                isActive
                  ? "bg-toba-green text-white shadow-lg shadow-toba-green/20"
                  : "text-slate-400 hover:bg-white/5 hover:text-white",
                collapsed && "justify-center px-0"
              )}
            >
              <item.icon size={22} className={cn(isActive ? "text-white" : "group-hover:text-toba-accent transition-colors", !collapsed && "ml-1")} />
              {!collapsed && <span className="ml-4 font-bold text-sm tracking-wide">{item.label}</span>}
              {collapsed && (
                <div className="absolute left-full ml-4 px-3 py-2 bg-slate-800 text-white text-xs font-bold rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-50">
                  {item.label}
                </div>
              )}
            </Link>
          );
        })}
      </nav>
      <div className="p-4 border-t border-white/5">
        <button
          onClick={handleLogout}
          className={cn(
            "flex items-center h-14 px-4 rounded-2xl text-slate-400 hover:bg-rose-500/10 hover:text-rose-500 transition-all duration-300 w-full group relative",
            collapsed && "justify-center px-0"
          )}
        >
          <LogOut size={22} />
          {!collapsed && <span className="ml-4 font-bold text-sm tracking-wide">Keluar</span>}
          {collapsed && (
            <div className="absolute left-full ml-4 px-3 py-2 bg-rose-500 text-white text-xs font-bold rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-50">
              Keluar
            </div>
          )}
        </button>
      </div>
    </>
  );

  return (
    <div className="min-h-screen flex bg-[#f8fafc]">
      {/* Mobile Backdrop */}
      {isMobileOpen && (
        <div
          className="fixed inset-0 bg-slate-900/50 z-40 lg:hidden"
          onClick={() => setIsMobileOpen(false)}
        />
      )}

      {/* Desktop Sidebar */}
      <aside className={cn(
        "bg-slate-900 text-white transition-all duration-500 flex-col fixed inset-y-0 left-0 z-50 shadow-2xl hidden lg:flex",
        isSidebarOpen ? "w-72" : "w-24"
      )}>
        <div className="h-20 flex items-center justify-between px-6 border-b border-white/5 shrink-0">
          {isSidebarOpen ? (
            <Link href={basePath} className="flex items-center space-x-3">
              <div className="w-10 h-10 bg-toba-green rounded-xl flex items-center justify-center text-white font-bold text-xl">W</div>
              <span className="font-bold text-xl tracking-tight">Wonderful<span className="text-toba-accent">Toba</span></span>
            </Link>
          ) : (
            <div className="w-10 h-10 bg-toba-green rounded-xl flex items-center justify-center text-white font-bold text-xl mx-auto">W</div>
          )}
          <button onClick={() => setIsSidebarOpen(!isSidebarOpen)} className="p-2 hover:bg-white/10 rounded-xl text-slate-400 hover:text-white transition-colors">
            {isSidebarOpen ? <X size={20} /> : <Menu size={20} />}
          </button>
        </div>

        {/* Scope Switcher */}
        {isSidebarOpen && (
          <div className="px-4 py-4 border-b border-white/5 shrink-0">
            <div className="flex rounded-xl bg-slate-800 p-1">
              <Link href="/admin/tour" className={cn("flex-1 text-center py-2 text-[11px] uppercase tracking-widest font-bold rounded-lg transition-all", category === 'tour' ? "bg-toba-green text-white shadow-md shadow-toba-green/20" : "text-slate-400 hover:text-white")}>Tour</Link>
              <Link href="/admin/outbound" className={cn("flex-1 text-center py-2 text-[11px] uppercase tracking-widest font-bold rounded-lg transition-all", category === 'outbound' ? "bg-toba-green text-white shadow-md shadow-toba-green/20" : "text-slate-400 hover:text-white")}>Outbound</Link>
            </div>
          </div>
        )}
        <NavLinks collapsed={!isSidebarOpen} />
      </aside>

      {/* Mobile Sidebar */}
      <aside className={cn(
        "bg-slate-900 text-white flex flex-col fixed inset-y-0 left-0 z-50 w-72 shadow-2xl transition-transform duration-300 lg:hidden",
        isMobileOpen ? "translate-x-0" : "-translate-x-full"
      )}>
        <div className="h-20 flex items-center justify-between px-6 border-b border-white/5 shrink-0">
          <Link href={basePath} onClick={() => setIsMobileOpen(false)} className="flex items-center space-x-3">
            <div className="w-10 h-10 bg-toba-green rounded-xl flex items-center justify-center text-white font-bold text-xl">W</div>
            <span className="font-bold text-xl tracking-tight">Wonderful<span className="text-toba-accent">Toba</span></span>
          </Link>
          <button onClick={() => setIsMobileOpen(false)} className="p-2 hover:bg-white/10 rounded-xl text-slate-400 hover:text-white">
            <X size={20} />
          </button>
        </div>
        
        {/* Mobile Scope Switcher */}
        <div className="px-4 py-4 border-b border-white/5 shrink-0">
          <div className="flex rounded-xl bg-slate-800 p-1">
            <Link href="/admin/tour" onClick={() => setIsMobileOpen(false)} className={cn("flex-1 text-center py-2 text-[11px] uppercase tracking-widest font-bold rounded-lg transition-all", category === 'tour' ? "bg-toba-green text-white shadow-md shadow-toba-green/20" : "text-slate-400 hover:text-white")}>Tour</Link>
            <Link href="/admin/outbound" onClick={() => setIsMobileOpen(false)} className={cn("flex-1 text-center py-2 text-[11px] uppercase tracking-widest font-bold rounded-lg transition-all", category === 'outbound' ? "bg-toba-green text-white shadow-md shadow-toba-green/20" : "text-slate-400 hover:text-white")}>Outbound</Link>
          </div>
        </div>

        <NavLinks collapsed={false} />
      </aside>

      {/* Main Content */}
      <div className={cn(
        "flex-grow flex flex-col min-h-screen overflow-hidden transition-all duration-500 lg:ml-24",
        isSidebarOpen && "lg:ml-72"
      )}>
        {/* Top Header */}
        <header className="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-40">
          <div className="flex items-center flex-1">
            <button onClick={() => setIsMobileOpen(true)} className="lg:hidden p-2 mr-4 hover:bg-slate-100 rounded-xl text-slate-500">
              <Menu size={24} />
            </button>
            <div className="hidden md:flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2 w-full max-w-md focus-within:ring-2 focus-within:ring-toba-green/20 focus-within:border-toba-green/50 transition-all">
              <Search size={18} className="text-slate-400 mr-3" />
              <input
                type="text"
                placeholder="Cari data, laporan, atau pengguna..."
                className="bg-transparent border-none focus:ring-0 text-sm font-medium text-slate-600 placeholder:text-slate-400 w-full"
              />
            </div>
          </div>
          <div className="flex items-center space-x-6">
            <button className="relative p-2.5 text-slate-400 hover:text-toba-green hover:bg-toba-green/5 rounded-xl transition-all">
              <Bell size={22} />
              <span className="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white" />
            </button>
            <div className="h-10 w-px bg-slate-200" />
            <div className="flex items-center space-x-4">
              <div className="text-right hidden sm:block">
                <p className="text-sm font-bold text-slate-900 leading-none mb-1">{user?.name || 'Admin User'}</p>
                <p className="text-[10px] font-bold text-toba-green uppercase tracking-widest">{user?.role || 'Staff'}</p>
              </div>
              <div className="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-200 shadow-sm overflow-hidden cursor-pointer hover:border-toba-green transition-colors">
                {user?.photoURL ? (
                  <img src={user.photoURL} alt="Profile" className="w-full h-full object-cover" />
                ) : (
                  <User size={24} />
                )}
              </div>
            </div>
          </div>
        </header>

        {/* Page Content */}
        <main className="flex-grow overflow-y-auto p-8">
          <div className="max-w-7xl mx-auto">
            {children}
          </div>
        </main>
      </div>
    </div>
  );
}
