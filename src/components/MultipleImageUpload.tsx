"use client";

import { useState } from 'react';
import { Plus, Trash2 } from 'lucide-react';
import ImageUpload from './ImageUpload';

interface MultipleImageUploadProps {
  value: string[]; // Array of image URLs
  onChange: (urls: string[]) => void;
  label?: string;
  maxImages?: number;
  aspectRatio?: 'square' | 'video' | 'wide' | 'portrait';
}

export default function MultipleImageUpload({ 
  value = [], 
  onChange, 
  label = 'Gambar Paket',
  maxImages = 10,
  aspectRatio = 'video'
}: MultipleImageUploadProps) {
  
  const handleImageChange = (index: number, url: string) => {
    const newImages = [...value];
    newImages[index] = url;
    onChange(newImages);
  };

  const handleAddImage = () => {
    if (value.length < maxImages) {
      onChange([...value, '']);
    }
  };

  const handleRemoveImage = (index: number) => {
    const newImages = value.filter((_, i) => i !== index);
    onChange(newImages);
  };

  return (
    <div className="space-y-4">
      {label && (
        <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">
          {label} ({value.length}/{maxImages})
        </label>
      )}

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {value.map((img, idx) => (
          <div key={idx} className="relative">
            <ImageUpload 
              label={`Gambar ${idx + 1}`}
              value={img}
              onChange={(url) => handleImageChange(idx, url)}
              aspectRatio={aspectRatio}
            />
            <button 
              type="button"
              onClick={() => handleRemoveImage(idx)}
              className="absolute -top-2 -right-2 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-rose-600 transition-colors z-20"
            >
              <Trash2 size={14} />
            </button>
          </div>
        ))}
      </div>

      {value.length < maxImages && (
        <button 
          type="button"
          onClick={handleAddImage}
          className="w-full py-6 border-2 border-dashed border-slate-200 rounded-3xl text-slate-400 font-bold hover:border-toba-green hover:text-toba-green transition-all flex items-center justify-center gap-2"
        >
          <Plus size={20} /> Tambah Gambar ({value.length}/{maxImages})
        </button>
      )}

      {value.length === 0 && (
        <div className="text-center py-8 text-slate-400 text-sm">
          Belum ada gambar. Klik tombol di atas untuk menambah gambar.
        </div>
      )}
    </div>
  );
}
