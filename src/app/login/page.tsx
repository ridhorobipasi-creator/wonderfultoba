"use client";

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { Loader2 } from 'lucide-react';

export default function LoginPage() {
  const router = useRouter();

  useEffect(() => {
    // Automatically set mock session
    localStorage.setItem('auth_token', 'mock-preview-token');
    localStorage.setItem('auth_user', JSON.stringify({ name: 'Preview Admin', email: 'admin@preview.com', role: 'ADMIN' }));
    
    // Immediate redirect to admin for preview
    setTimeout(() => {
      window.location.href = '/admin';
    }, 500);
  }, []);

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-4">
      <div className="w-16 h-16 border-4 border-emerald-100 border-t-toba-green rounded-full animate-spin mb-6"></div>
      <h1 className="text-xl font-black text-slate-900 tracking-tight mb-2">Wonderful Preview</h1>
      <p className="text-slate-400 font-medium animate-pulse">Mengalihkan ke Dashboard Admin...</p>
    </div>
  );
}
