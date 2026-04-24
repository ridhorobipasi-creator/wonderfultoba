"use client";

import { Loader2 } from 'lucide-react';
import { ButtonHTMLAttributes, ReactNode } from 'react';

interface LoadingButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  loading?: boolean;
  loadingText?: string;
  icon?: ReactNode;
  children: ReactNode;
  variant?: 'primary' | 'secondary' | 'danger';
}

export default function LoadingButton({
  loading = false,
  loadingText = 'Memproses...',
  icon,
  children,
  variant = 'primary',
  className = '',
  disabled,
  ...props
}: LoadingButtonProps) {
  const variantStyles = {
    primary: 'bg-toba-green hover:bg-toba-green/90 text-white shadow-xl shadow-blue-100',
    secondary: 'bg-slate-100 hover:bg-slate-200 text-slate-700',
    danger: 'bg-rose-500 hover:bg-rose-600 text-white shadow-xl shadow-rose-100',
  };

  return (
    <button
      disabled={loading || disabled}
      className={`relative px-10 py-4 rounded-2xl font-bold transition-all flex items-center justify-center space-x-2 disabled:opacity-60 disabled:cursor-not-allowed ${variantStyles[variant]} ${className}`}
      {...props}
    >
      {loading ? (
        <>
          <Loader2 className="w-5 h-5 animate-spin" />
          <span>{loadingText}</span>
        </>
      ) : (
        <>
          {icon && <span>{icon}</span>}
          <span>{children}</span>
        </>
      )}
    </button>
  );
}
