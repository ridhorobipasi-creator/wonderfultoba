"use client";

import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Download, X, Smartphone } from 'lucide-react';

interface BeforeInstallPromptEvent extends Event {
  prompt(): Promise<void>;
  userChoice: Promise<{ outcome: 'accepted' | 'dismissed' }>;
}

export default function PWAInstallPrompt() {
  const [deferredPrompt, setDeferredPrompt] = useState<BeforeInstallPromptEvent | null>(null);
  const [showPrompt, setShowPrompt] = useState(false);
  const [isInstalled, setIsInstalled] = useState(
    () => typeof window !== 'undefined' && window.matchMedia('(display-mode: standalone)').matches
  );

  useEffect(() => {
    // Listen for install availability
    const handleBeforeInstall = (e: Event) => {
      e.preventDefault();
      setDeferredPrompt(e as BeforeInstallPromptEvent);
      
      // Show after 5 seconds delay (don't be aggressive)
      const timer = setTimeout(() => {
        const dismissed = localStorage.getItem('pwa-install-dismissed');
        if (!dismissed) {
          setShowPrompt(true);
        }
      }, 5000);
      
      return () => clearTimeout(timer);
    };

    const handleAppInstalled = () => {
      setIsInstalled(true);
      setShowPrompt(false);
    };

    window.addEventListener('beforeinstallprompt', handleBeforeInstall);
    window.addEventListener('appinstalled', handleAppInstalled);

    return () => {
      window.removeEventListener('beforeinstallprompt', handleBeforeInstall);
      window.removeEventListener('appinstalled', handleAppInstalled);
    };
  }, []);

  const handleInstall = async () => {
    if (!deferredPrompt) return;
    
    await deferredPrompt.prompt();
    const { outcome } = await deferredPrompt.userChoice;
    
    if (outcome === 'accepted') {
      setIsInstalled(true);
    }
    
    setShowPrompt(false);
    setDeferredPrompt(null);
  };

  const handleDismiss = () => {
    setShowPrompt(false);
    localStorage.setItem('pwa-install-dismissed', 'true');
  };

  if (isInstalled || !showPrompt) return null;

  return (
    <AnimatePresence>
      <motion.div
        initial={{ opacity: 0, y: 100, scale: 0.9 }}
        animate={{ opacity: 1, y: 0, scale: 1 }}
        exit={{ opacity: 0, y: 100, scale: 0.9 }}
        transition={{ type: 'spring', stiffness: 300, damping: 25 }}
        className="fixed bottom-6 left-4 right-4 md:left-auto md:right-6 md:w-96 z-[9999]"
      >
        <div className="bg-slate-900 text-white rounded-[2rem] p-6 shadow-2xl shadow-slate-900/40 border border-white/10 overflow-hidden relative">
          {/* Glow effect */}
          <div className="absolute -top-16 -right-16 w-48 h-48 bg-toba-green/20 rounded-full blur-3xl pointer-events-none" />
          
          <div className="relative z-10">
            <div className="flex items-start justify-between mb-5">
              <div className="flex items-center space-x-4">
                <div className="w-14 h-14 bg-gradient-to-br from-toba-green to-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-toba-green/30">
                  W
                </div>
                <div>
                  <h3 className="font-black text-lg leading-none mb-1 tracking-tight">Wonderful Toba</h3>
                  <p className="text-toba-green text-xs font-bold uppercase tracking-widest">App Tersedia!</p>
                </div>
              </div>
              <button
                onClick={handleDismiss}
                className="p-2 rounded-xl text-slate-500 hover:text-white hover:bg-white/10 transition-all"
              >
                <X size={18} />
              </button>
            </div>

            <div className="flex items-center space-x-3 mb-5 bg-white/5 rounded-2xl p-4 border border-white/5">
              <Smartphone size={20} className="text-toba-green shrink-0" />
              <p className="text-sm font-medium text-slate-300 leading-snug">
                Install aplikasi ini ke layar utama HP Anda. Akses lebih cepat, bahkan tanpa internet!
              </p>
            </div>

            <div className="flex items-center gap-3">
              <button
                onClick={handleInstall}
                className="flex-1 bg-gradient-to-r from-toba-green to-emerald-500 text-white py-3.5 rounded-2xl font-black text-sm tracking-wide flex items-center justify-center space-x-2 hover:shadow-lg hover:shadow-toba-green/30 transition-all hover:scale-[1.02] active:scale-[0.98]"
              >
                <Download size={18} />
                <span>Pasang Sekarang</span>
              </button>
              <button
                onClick={handleDismiss}
                className="px-5 py-3.5 rounded-2xl font-bold text-sm text-slate-400 hover:text-white hover:bg-white/10 transition-all"
              >
                Nanti
              </button>
            </div>
          </div>
        </div>
      </motion.div>
    </AnimatePresence>
  );
}
