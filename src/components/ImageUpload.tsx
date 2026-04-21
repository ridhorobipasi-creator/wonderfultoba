"use client";

import { useState, useRef, useCallback, useEffect } from 'react';
import { Upload, X, Image as ImageIcon, Loader2, Check } from 'lucide-react';
import { toast } from 'sonner';
import { cn } from '../utils/cn';

interface ImageUploadProps {
  value?: string;
  onChange: (url: string) => void;
  label?: string;
  className?: string;
  aspectRatio?: 'square' | 'video' | 'wide' | 'portrait';
}

export default function ImageUpload({ 
  value, 
  onChange, 
  label = 'Upload Gambar',
  className,
  aspectRatio = 'video'
}: ImageUploadProps) {
  const [uploading, setUploading] = useState(false);
  const [dragActive, setDragActive] = useState(false);
  const [preview, setPreview] = useState(value || '');
  const fileInputRef = useRef<HTMLInputElement>(null);

  // Sync preview when value prop changes (e.g. import from package/blog)
  useEffect(() => {
    setPreview(value || '');
  }, [value]);

  const aspectRatioClasses = {
    square: 'aspect-square',
    video: 'aspect-video',
    wide: 'aspect-[21/9]',
    portrait: 'aspect-[3/4]'
  };

  const handleUpload = async (file: File) => {
    if (!file) return;

    // Validate file type
    if (!file.type.startsWith('image/')) {
      toast.error('File harus berupa gambar');
      return;
    }

    // Validate file size (max 10MB)
    if (file.size > 10 * 1024 * 1024) {
      toast.error('Ukuran file maksimal 10MB');
      return;
    }

    setUploading(true);

    try {
      const formData = new FormData();
      formData.append('file', file);

      const response = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Upload gagal');
      }

      const data = await response.json();
      
      setPreview(data.url);
      onChange(data.url);
      
      toast.success(`Gambar berhasil diupload! (${(data.size / 1024).toFixed(0)}KB WebP)`);
    } catch (error: any) {
      console.error('Upload error:', error);
      toast.error(error.message || 'Gagal mengupload gambar');
    } finally {
      setUploading(false);
    }
  };

  const handleDrag = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === 'dragenter' || e.type === 'dragover') {
      setDragActive(true);
    } else if (e.type === 'dragleave') {
      setDragActive(false);
    }
  }, []);

  const handleDrop = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);

    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      handleUpload(e.dataTransfer.files[0]);
    }
  }, []);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    e.preventDefault();
    if (e.target.files && e.target.files[0]) {
      handleUpload(e.target.files[0]);
    }
  };

  const handleRemove = () => {
    setPreview('');
    onChange('');
    if (fileInputRef.current) {
      fileInputRef.current.value = '';
    }
  };

  return (
    <div className={cn('space-y-2', className)}>
      {label && (
        <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">
          {label}
        </label>
      )}

      <div
        onDragEnter={handleDrag}
        onDragLeave={handleDrag}
        onDragOver={handleDrag}
        onDrop={handleDrop}
        className={cn(
          'relative rounded-2xl border-2 border-dashed transition-all overflow-hidden',
          aspectRatioClasses[aspectRatio],
          dragActive 
            ? 'border-toba-green bg-toba-green/5' 
            : 'border-slate-200 bg-slate-50 hover:border-slate-300',
          uploading && 'pointer-events-none opacity-60'
        )}
      >
        {preview ? (
          // Preview Image
          <div className="relative w-full h-full group">
            <img
              src={preview}
              alt="Preview"
              className="w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
              <button
                type="button"
                onClick={() => fileInputRef.current?.click()}
                className="bg-white text-slate-900 px-4 py-2 rounded-xl font-bold text-sm hover:bg-slate-100 transition-colors flex items-center gap-2"
              >
                <Upload size={16} />
                Ganti
              </button>
              <button
                type="button"
                onClick={handleRemove}
                className="bg-rose-500 text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-rose-600 transition-colors flex items-center gap-2"
              >
                <X size={16} />
                Hapus
              </button>
            </div>
          </div>
        ) : (
          // Upload Area
          <button
            type="button"
            onClick={() => fileInputRef.current?.click()}
            className="w-full h-full flex flex-col items-center justify-center gap-3 p-6 cursor-pointer hover:bg-slate-100 transition-colors"
          >
            {uploading ? (
              <>
                <Loader2 className="w-10 h-10 text-toba-green animate-spin" />
                <p className="text-sm font-bold text-slate-600">Mengupload...</p>
                <p className="text-xs text-slate-400">Mengkonversi ke WebP...</p>
              </>
            ) : (
              <>
                <div className="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center">
                  <ImageIcon className="w-8 h-8 text-slate-400" />
                </div>
                <div className="text-center">
                  <p className="text-sm font-bold text-slate-700 mb-1">
                    Klik atau drag & drop gambar
                  </p>
                  <p className="text-xs text-slate-400">
                    JPG, PNG, WebP (max 10MB)
                  </p>
                  <p className="text-xs text-toba-green font-bold mt-2">
                    ✨ Auto-convert ke WebP
                  </p>
                </div>
              </>
            )}
          </button>
        )}

        <input
          ref={fileInputRef}
          type="file"
          accept="image/*"
          onChange={handleChange}
          className="hidden"
        />
      </div>

      {preview && (
        <div className="flex items-center gap-2 text-xs text-slate-500 ml-1">
          <Check size={14} className="text-toba-green" />
          <span className="font-medium">Gambar siap digunakan</span>
        </div>
      )}
    </div>
  );
}
