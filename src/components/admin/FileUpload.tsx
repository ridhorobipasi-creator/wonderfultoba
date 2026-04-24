"use client";

import { useState } from 'react';
import { Upload, X, Check, Image as ImageIcon } from 'lucide-react';
import api from '@/lib/api';
import { toast } from 'sonner';

interface FileUploadProps {
  onUploadSuccess: (url: string) => void;
  label?: string;
  currentValue?: string;
}

export default function FileUpload({ onUploadSuccess, label, currentValue }: FileUploadProps) {
  const [uploading, setUploading] = useState(false);
  const [preview, setPreview] = useState(currentValue || '');

  const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    // Frontend validation
    if (file.size > 5 * 1024 * 1024) {
      toast.error("File terlalu besar (Maks 5MB)");
      return;
    }

    setUploading(true);
    const formData = new FormData();
    formData.append('file', file);

    try {
      const res = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });
      
      const data = await res.json();
      
      if (res.ok) {
        setPreview(data.url);
        onUploadSuccess(data.url);
        toast.success("Foto berhasil diunggah");
      } else {
        throw new Error(data.error);
      }
    } catch (error) {
      console.error('Upload error:', error);
      toast.error("Gagal mengunggah foto");
    } finally {
      setUploading(false);
    }
  };

  return (
    <div className="space-y-3">
      {label && <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">{label}</label>}
      
      <div className="relative group">
        {preview ? (
          <div className="relative w-full h-40 rounded-2xl overflow-hidden border border-slate-100 bg-slate-50">
            <img src={preview} alt="Preview" className="w-full h-full object-cover" />
            <button 
              type="button"
              onClick={() => { setPreview(''); onUploadSuccess(''); }}
              className="absolute top-2 right-2 bg-rose-500 text-white p-1 unit-8 rounded-full shadow-lg hover:bg-rose-600 transition-colors"
            >
              <X size={14} />
            </button>
            <div className="absolute inset-x-0 bottom-0 bg-black/50 p-2 text-center">
              <p className="text-[9px] text-white font-bold uppercase tracking-widest flex items-center justify-center gap-1">
                <Check size={10} /> Foto Aktif
              </p>
            </div>
          </div>
        ) : (
          <label className="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-toba-green hover:bg-blue-50/30 transition-all">
            <div className="flex flex-col items-center justify-center pt-5 pb-6">
              {uploading ? (
                <div className="w-8 h-8 border-2 border-toba-green/20 border-t-toba-green rounded-full animate-spin mb-3" />
              ) : (
                <Upload className="w-8 h-8 text-slate-300 mb-3 group-hover:text-toba-green" />
              )}
              <p className="text-sm font-bold text-slate-400 group-hover:text-toba-green">
                {uploading ? 'Mengunggah...' : 'Klik untuk Upload'}
              </p>
              <p className="text-[10px] text-slate-300 mt-1 uppercase font-bold tracking-widest">
                PNG, JPG atau WEBP (Maks 5MB)
              </p>
            </div>
            <input type="file" className="hidden" accept="image/*" onChange={handleFileChange} disabled={uploading} />
          </label>
        )}
      </div>
    </div>
  );
}
