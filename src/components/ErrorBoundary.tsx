"use client";

import { Component, ReactNode } from 'react';
import { AlertTriangle, RefreshCcw } from 'lucide-react';

interface Props {
  children: ReactNode;
  fallback?: ReactNode;
}

interface State {
  hasError: boolean;
  error?: Error;
}

export default class ErrorBoundary extends Component<Props, State> {
  constructor(props: Props) {
    super(props);
    this.state = { hasError: false };
  }

  static getDerivedStateFromError(error: Error): State {
    return { hasError: true, error };
  }

  componentDidCatch(error: Error, info: { componentStack: string }) {
    console.error('ErrorBoundary caught:', error, info);
  }

  render() {
    if (this.state.hasError) {
      if (this.props.fallback) return this.props.fallback;
      return (
        <div className="min-h-screen flex items-center justify-center bg-slate-50 px-4">
          <div className="text-center max-w-md">
            <div className="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6">
              <AlertTriangle size={36} className="text-rose-500" />
            </div>
            <h2 className="text-2xl font-black text-slate-900 mb-3">Terjadi Kesalahan</h2>
            <p className="text-slate-500 font-medium mb-2">
              Halaman ini mengalami error yang tidak terduga.
            </p>
            {this.state.error && (
              <p className="text-xs text-slate-400 bg-slate-100 rounded-xl px-4 py-2 mb-6 font-mono">
                {this.state.error.message}
              </p>
            )}
            <button
              onClick={() => window.location.reload()}
              className="flex items-center gap-2 mx-auto px-8 py-3.5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-toba-green transition-all"
            >
              <RefreshCcw size={18} /> Muat Ulang Halaman
            </button>
          </div>
        </div>
      );
    }
    return this.props.children;
  }
}
