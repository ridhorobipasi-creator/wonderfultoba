"use client";

import { useRouter, usePathname } from 'next/navigation';
import Link from 'next/link';
import { LogIn, Menu, X, Globe, Phone, Mail, LogOut, User, Lock, Eye, EyeOff, Moon, Sun } from 'lucide-react';
import { useState, useEffect } from 'react';
import { useStore } from '../store/useStore';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import api from '../lib/api';

export default function Navbar() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [isRegister, setIsRegister] = useState(false);
  const [formLoading, setFormLoading] = useState(false);
  const [showPass, setShowPass] = useState(false);
  const [form, setForm] = useState({ name: '', email: '', password: '' });
  const [isDark, setIsDark] = useState(false);
  const [mounted, setMounted] = useState(false);
  const [contact, setContact] = useState({ phone: '+62 813-2388-8207', email: 'outbound@wonderfultoba.com', whatsapp: '6281323888207' });
  const { user, setUser, setToken } = useStore();

  useEffect(() => {
    setMounted(true);
    setIsDark(document.documentElement.classList.contains('dark'));
    // Load contact from settings
    fetch('/api/settings?key=tour_landing')
      .then(r => r.json())
      .then(d => {
        if (d?.contact) {
          setContact(prev => ({
            phone: d.contact.phone || prev.phone,
            email: d.contact.email || prev.email,
            whatsapp: d.contact.whatsapp || prev.whatsapp,
          }));
        }
      })
      .catch(() => {});
  }, []);
  const router = useRouter();
  const pathname = usePathname();
  const safePathname = pathname ?? '';

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 20);
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    setIsMenuOpen(false);
  }, [safePathname]);

  const toggleDark = () => {
    const html = document.documentElement;
    const newVal = !isDark;
    html.classList.toggle('dark', newVal);
    setIsDark(newVal);
    localStorage.setItem('wt-dark-mode', String(newVal));
  };

  const handleAuthSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setFormLoading(true);
    try {
      const endpoint = isRegister ? '/auth/register' : '/auth/login';
      const payload = isRegister
        ? { name: form.name, email: form.email, password: form.password }
        : { email: form.email, password: form.password };
      const res = await api.post(endpoint, payload);
      setToken(res.data.token);
      setUser(res.data.user);
      if (typeof window !== 'undefined' && res.data.user) {
        localStorage.setItem('auth_user', JSON.stringify(res.data.user));
      }
      setShowLoginModal(false);
      setForm({ name: '', email: '', password: '' });
      toast.success(isRegister ? 'Akun berhasil dibuat!' : 'Selamat datang kembali!');
    } catch (err: unknown) {
      const axiosErr = err as { response?: { data?: { errors?: Record<string, string[]>; message?: string } } };
      if (axiosErr.response?.data?.errors) {
        const errors = axiosErr.response.data.errors;
        const firstError = Object.values(errors)[0] as string[];
        toast.error(firstError[0]);
      } else {
        const msg = axiosErr.response?.data?.message || 'Terjadi kesalahan. Coba lagi.';
        toast.error(msg);
      }
    } finally {
      setFormLoading(false);
    }
  };

  const handleLogout = async () => {
    try { await api.post('/auth/logout'); } catch {}
    setUser(null);
    setToken(null);
    toast.success('Berhasil keluar.');
    router.push('/');
    setIsMenuOpen(false);
  };

  type NavLink = { name: string; path: string; isExternal?: boolean };

  const isOutbound = safePathname.startsWith('/outbound');
  const isDarkHeroPage = safePathname === '/tour' || safePathname === '/outbound' || safePathname === '/tour/packages' || safePathname === '/outbound/packages';
  
  const navLinks: NavLink[] = isOutbound ? [
    { name: 'Tentang Kami', path: '/outbound#tentangkami' },
    { name: 'Layanan', path: '/outbound#layanan' },
    { name: 'Klien', path: '/outbound#klien' },
    { name: 'Pricelist', path: '/outbound/packages' },
    { name: 'Blog', path: '/outbound/blog' },
  ] : [
    { name: 'Beranda', path: '/tour' },
    { name: 'Paket Wisata', path: '/tour/packages' },
    { name: 'Galeri', path: '/tour/gallery' },
    { name: 'Blog', path: '/tour/blog' },
  ];

  return (
    <header className="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
      {/* Top Bar */}
      <div className={cn(
        "hidden sm:block bg-slate-900 text-white py-2 transition-all duration-300 overflow-hidden",
        isScrolled ? "h-0 opacity-0" : "h-auto opacity-100"
      )}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-[11px] font-semibold tracking-wider uppercase">
          <div className="flex items-center space-x-6">
            <a href={`tel:${contact.phone}`} className="flex items-center hover:text-toba-accent transition-colors">
              <Phone size={12} className="mr-2" />
              {contact.phone}
            </a>
            <a href={`mailto:${contact.email}`} className="flex items-center hover:text-toba-accent transition-colors">
              <Mail size={12} className="mr-2" />
              {contact.email}
            </a>
          </div>
          <div className="flex items-center space-x-6">
            <span className="flex items-center">
              <Globe size={12} className="mr-2" />
              ID | EN
            </span>
          </div>
        </div>
      </div>

      {/* Main Nav */}
      <nav className={cn(
        "transition-all duration-300 border-b",
        isScrolled 
          ? "bg-white/95 backdrop-blur-md py-3 shadow-lg border-slate-100" 
          : isDarkHeroPage 
            ? "bg-transparent py-5 border-transparent" 
            : "bg-white py-5 border-slate-100"
      )}>
        <div className="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8">
          <div className="flex justify-between items-center">
            {/* Logo */}
            <Link href="/" className="flex items-center space-x-3 group w-auto shrink-0 mr-8">
              <div className="w-12 h-12 bg-toba-green rounded-xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-toba-green/20 group-hover:scale-105 transition-transform shrink-0">
                W
              </div>
              <div className="flex flex-col whitespace-nowrap">
                <span className={cn(
                  "text-xl font-extrabold leading-none tracking-tight transition-colors",
                  (!isScrolled && isDarkHeroPage) ? "text-white" : "text-slate-900"
                )}>
                  WONDERFUL <span className="text-toba-green">TOBA</span> {isOutbound && <span className={(!isScrolled && isDarkHeroPage) ? "text-white" : "text-slate-900"}>OUTBOUND</span>}
                </span>
                <span className={cn(
                  "hidden sm:block text-[10px] font-bold tracking-[0.25em] uppercase mt-1 transition-colors",
                  (!isScrolled && isDarkHeroPage) ? "text-slate-300" : "text-slate-400"
                )}>
                  {isOutbound ? 'Provider Outbound Terbaik Sumut' : 'Sumatera Utara Travel Experience'}
                </span>
              </div>
            </Link>

            {/* Desktop Nav */}
            <div className="hidden lg:flex items-center space-x-8 shrink-0 w-max">
              {navLinks.map((link) => (
                link.isExternal ? null : (
                  <a
                    key={link.path}
                    href={link.path}
                    className={cn(
                      "font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm",
                      pathname === link.path 
                        ? "text-toba-green" 
                        : (!isScrolled && isDarkHeroPage) ? "text-white/80 hover:text-white drop-shadow-sm" : "text-slate-600 hover:text-toba-green"
                    )}
                  >
                    {link.name}
                  </a>
                )
              ))}
            </div>

            {/* Desktop Actions */}
            <div className="hidden lg:flex items-center space-x-6 shrink-0 ml-8">
              <a href={`https://wa.me/${contact.whatsapp}`} target="_blank" rel="noreferrer" className={cn(
                "px-5 py-2.5 rounded-full font-bold text-sm tracking-wide transition-all shadow-lg flex items-center space-x-2 whitespace-nowrap",
                (!isScrolled && isDarkHeroPage) ? "bg-white text-toba-green hover:bg-slate-100" : "bg-toba-green text-white hover:bg-slate-900"
              )}>
                <Phone size={16} />
                <span>HUBUNGI KAMI</span>
              </a>

              <div className={cn("h-6 w-px", (!isScrolled && isDarkHeroPage) ? "bg-white/20" : "bg-slate-200")} />

              {/* Dark Mode Toggle - only render after mount to avoid hydration mismatch */}
              {mounted && (
                <button
                  onClick={toggleDark}
                  className={cn(
                    "p-2.5 rounded-xl transition-all",
                    isDark ? "bg-slate-700 text-yellow-400 hover:bg-slate-600" :
                    (!isScrolled && isDarkHeroPage) ? "bg-white/10 text-white hover:bg-white/20" :
                    "bg-slate-100 text-slate-600 hover:bg-slate-200"
                  )}
                  aria-label={isDark ? 'Mode Terang' : 'Mode Gelap'}
                  title={isDark ? 'Aktifkan Mode Terang' : 'Aktifkan Mode Gelap'}
                >
                  {isDark ? <Sun size={18} /> : <Moon size={18} />}
                </button>
              )}

              {/* User section - only render after mount to avoid hydration mismatch */}
              {mounted && (user ? (
                <div className="flex items-center space-x-5">
                  {user.role !== 'user' && (
                    <Link href="/admin"
                      className={cn(
                        "text-xs font-extrabold px-5 py-2.5 rounded-full transition-all whitespace-nowrap",
                        (!isScrolled && isDarkHeroPage) ? "bg-white/10 text-white hover:bg-white/20" : "bg-toba-green/5 text-toba-green hover:bg-toba-green/10"
                      )}
                    >
                      DASHBOARD
                    </Link>
                  )}
                  <div className="flex items-center space-x-3">
                    <button
                      onClick={() => router.push('/profile')}
                      className="w-10 h-10 rounded-full overflow-hidden ring-2 ring-transparent hover:ring-toba-green transition-all shadow-sm"
                      aria-label="Profil saya"
                    >
                      <img src={user.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name || 'U')}&background=10b981&color=fff&size=100`} alt="User" referrerPolicy="no-referrer" className="w-full h-full object-cover" />
                    </button>
                    <button onClick={handleLogout} className={cn("flex flex-col items-start transition-colors", (!isScrolled && isDarkHeroPage) ? "text-white/80 hover:text-white" : "text-slate-600 hover:text-rose-600")} aria-label="Keluar">
                      <span className="text-xs font-bold leading-none mb-0.5">Logout</span>
                      <span className="text-[9px] font-semibold uppercase opacity-70 leading-none">Keluar akun</span>
                    </button>
                  </div>
                </div>
              ) : (
                <button
                  onClick={() => setShowLoginModal(true)}
                  className={cn(
                    "px-4 py-2 rounded-full text-sm font-bold flex items-center space-x-2 transition-all whitespace-nowrap",
                    (!isScrolled && isDarkHeroPage) ? "text-white hover:text-toba-green bg-white/10 hover:bg-white" : "text-slate-600 hover:text-toba-green"
                  )}
                >
                  <LogIn size={18} />
                  <span>LOGIN / DAFTAR</span>
                </button>
              ))}
            </div>

            {/* Mobile Menu Toggle */}
            <div className="lg:hidden flex items-center">
              <button
                onClick={() => setIsMenuOpen(!isMenuOpen)}
                className={cn("p-2 transition-colors", (!isScrolled && isDarkHeroPage) ? "text-white hover:text-toba-green" : "text-slate-600 hover:text-toba-green")}
                aria-label={isMenuOpen ? 'Tutup menu' : 'Buka menu'}
              >
                {isMenuOpen ? <X size={28} /> : <Menu size={28} />}
              </button>
            </div>
          </div>
        </div>

        {/* Mobile Nav */}
        {isMenuOpen && (
          <div className="lg:hidden fixed inset-x-0 top-full bg-white border-t border-slate-100 p-6 space-y-4 shadow-2xl z-50">
            {navLinks.map((link) => (
              link.isExternal ? (
                <a
                  key={link.path}
                  href={link.path}
                  target="_blank"
                  rel="noreferrer"
                  className="block text-lg font-bold p-3 rounded-xl transition-all text-toba-green bg-toba-green/10 text-center"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {link.name}
                </a>
              ) : (
                <a
                  key={link.path}
                  href={link.path}
                  onClick={() => setIsMenuOpen(false)}
                  className={cn(
                    "block text-lg font-bold p-3 rounded-xl transition-all",
                    pathname === link.path 
                      ? "text-toba-green bg-toba-green/5" 
                      : "text-slate-600 hover:bg-slate-50"
                  )}
                >
                  {link.name}
                </a>
              )
            ))}

            <div className="border-t border-slate-100 pt-4 space-y-3">
              {user ? (
                <>
                  <button
                    onClick={() => { router.push('/profile'); setIsMenuOpen(false); }}
                    className="w-full flex items-center space-x-3 p-3 rounded-xl text-slate-700 hover:bg-slate-50 font-bold transition-all"
                  >
                    <User size={20} className="text-toba-green" />
                    <span>Profil Saya</span>
                  </button>
                  {user.role !== 'user' && (
                    <Link href="/admin"
                      className="flex items-center space-x-3 p-3 rounded-xl text-toba-green bg-toba-green/5 font-bold transition-all"
                    >
                      <span>Dashboard Admin</span>
                    </Link>
                  )}
                  <button
                    onClick={handleLogout}
                    className="w-full flex items-center justify-center space-x-2 bg-rose-50 text-rose-600 p-4 rounded-2xl font-bold border border-rose-100"
                  >
                    <LogOut size={20} />
                    <span>KELUAR</span>
                  </button>
                </>
              ) : (
                <button
                  onClick={() => { setShowLoginModal(true); setIsMenuOpen(false); }}
                  className="w-full flex items-center justify-center space-x-2 bg-toba-green text-white p-4 rounded-2xl font-bold"
                >
                  <LogIn size={20} />
                  <span>MASUK</span>
                </button>
              )}
            </div>
          </div>
        )}
      </nav>

      {/* Login / Register Modal */}
      {showLoginModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md">
          <div className="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center">
              <div>
                <h3 className="text-2xl font-black text-slate-900">{isRegister ? 'Buat Akun' : 'Selamat Datang'}</h3>
                <p className="text-sm text-slate-500 font-medium mt-1">{isRegister ? 'Daftar untuk mulai memesan' : 'Masuk ke akun Wonderful Toba Anda'}</p>
              </div>
              <button onClick={() => setShowLoginModal(false)} className="p-2 hover:bg-slate-50 rounded-xl text-slate-400 hover:text-slate-900 transition-all">
                <X size={22} />
              </button>
            </div>
            <form onSubmit={handleAuthSubmit} className="p-8 space-y-5">
              {isRegister && (
                <div className="space-y-2">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                  <div className="relative">
                    <User className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={18} />
                    <input required value={form.name} onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
                      className="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900"
                      placeholder="Nama Anda" />
                  </div>
                </div>
              )}
              <div className="space-y-2">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</label>
                <div className="relative">
                  <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={18} />
                  <input required type="email" value={form.email} onChange={e => setForm(f => ({ ...f, email: e.target.value }))}
                    className="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900"
                    placeholder="email@contoh.com" />
                </div>
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Password</label>
                <div className="relative">
                  <Lock className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={18} />
                  <input required type={showPass ? 'text' : 'password'} value={form.password} onChange={e => setForm(f => ({ ...f, password: e.target.value }))}
                    className="w-full pl-11 pr-12 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900"
                    placeholder="••••••••" />
                  <button type="button" onClick={() => setShowPass(v => !v)} className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    {showPass ? <EyeOff size={18} /> : <Eye size={18} />}
                  </button>
                </div>
              </div>
              <button type="submit" disabled={formLoading}
                className="w-full py-4 bg-toba-green text-white rounded-2xl font-bold hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20 disabled:opacity-60">
                {formLoading ? 'Memproses...' : (isRegister ? 'Daftar Sekarang' : 'Masuk')}
              </button>
              <p className="text-center text-sm text-slate-500">
                {isRegister ? 'Sudah punya akun?' : 'Belum punya akun?'}{' '}
                <button type="button" onClick={() => setIsRegister(v => !v)} className="font-bold text-toba-green hover:underline">
                  {isRegister ? 'Masuk' : 'Daftar'}
                </button>
              </p>
            </form>
          </div>
        </div>
      )}
    </header>
  );
}
