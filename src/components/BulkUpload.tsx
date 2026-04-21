"use client";

import { useState, useCallback, useRef } from 'react';
import { Upload, X, CheckCircle, AlertCircle, Image as ImageIcon, Loader2 } from 'lucide-react';
import { cn } from '../utils/cn';
import { toast } from 'sonner';

interface UploadFile {
  id: string;
  file: File;
  preview: string;
  status: 'pending' | 'uploading' | 'done' | 'error';
  progress: number;
  url?: string;
  error?: string;
}

interface BulkUploadProps {
  onUploadComplete?: (urls: string[]) => void;
  maxFiles?: number;
  className?: string;
}

export default function BulkUpload({ onUploadComplete, maxFiles = 20, className }: BulkUploadProps) {
  const [files, setFiles] = useState<UploadFile[]>([]);
  const [dragActive, setDragActive] = useState(false);
  const [isUploading, setIsUploading] = useState(false);
  const inputRef = useRef<HTMLInputElement>(null);

  const addFiles = useCallback((newFiles: File[]) => {
    const imageFiles = newFiles.filter(f => f.type.startsWith('image/'));
    
    if (imageFiles.length === 0) {
      toast.error('Hanya file gambar yang diizinkan');
      return;
    }

    const remaining = maxFiles - files.length;
    const toAdd = imageFiles.slice(0, remaining);

    if (toAdd.length < imageFiles.length) {
      toast.warning(`Maksimal ${maxFiles} file. ${imageFiles.length - toAdd.length} file diabaikan.`);
    }

    const uploadFiles: UploadFile[] = toAdd.map(file => ({
      id: `${Date.now()}-${Math.random().toString(36).substr(2, 9)}`,
      file,
      preview: URL.createObjectURL(file),
      status: 'pending',
      progress: 0,
    }));

    setFiles(prev => [...prev, ...uploadFiles]);
  }, [files.length, maxFiles]);

  const handleDrag = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === 'dragenter' || e.type === 'dragover') setDragActive(true);
    else if (e.type === 'dragleave') setDragActive(false);
  }, []);

  const handleDrop = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);
    const droppedFiles = Array.from(e.dataTransfer.files);
    addFiles(droppedFiles);
  }, [addFiles]);

  const handleFileInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      addFiles(Array.from(e.target.files));
      e.target.value = '';
    }
  };

  const removeFile = (id: string) => {
    setFiles(prev => {
      const file = prev.find(f => f.id === id);
      if (file) URL.revokeObjectURL(file.preview);
      return prev.filter(f => f.id !== id);
    });
  };

  const uploadSingleFile = async (uploadFile: UploadFile): Promise<string | null> => {
    const formData = new FormData();
    formData.append('file', uploadFile.file);

    try {
      // Simulate progress
      setFiles(prev => prev.map(f => 
        f.id === uploadFile.id ? { ...f, status: 'uploading', progress: 30 } : f
      ));

      const res = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });

      setFiles(prev => prev.map(f => 
        f.id === uploadFile.id ? { ...f, progress: 80 } : f
      ));

      if (!res.ok) {
        const err = await res.json();
        throw new Error(err.error || 'Upload gagal');
      }

      const data = await res.json();

      setFiles(prev => prev.map(f => 
        f.id === uploadFile.id ? { ...f, status: 'done', progress: 100, url: data.url } : f
      ));

      return data.url;
    } catch (error: any) {
      setFiles(prev => prev.map(f => 
        f.id === uploadFile.id ? { ...f, status: 'error', progress: 0, error: error.message } : f
      ));
      return null;
    }
  };

  const handleUploadAll = async () => {
    const pendingFiles = files.filter(f => f.status === 'pending');
    if (pendingFiles.length === 0) {
      toast.info('Tidak ada file yang perlu diupload');
      return;
    }

    setIsUploading(true);

    // Upload in parallel (max 3 concurrent)
    const BATCH_SIZE = 3;
    const uploadedUrls: string[] = [];

    for (let i = 0; i < pendingFiles.length; i += BATCH_SIZE) {
      const batch = pendingFiles.slice(i, i + BATCH_SIZE);
      const results = await Promise.all(batch.map(f => uploadSingleFile(f)));
      uploadedUrls.push(...results.filter((url): url is string => url !== null));
    }

    setIsUploading(false);

    const successCount = uploadedUrls.length;
    const failCount = pendingFiles.length - successCount;

    if (successCount > 0) {
      toast.success(`${successCount} file berhasil diupload${failCount > 0 ? `, ${failCount} gagal` : ''}`);
      onUploadComplete?.(uploadedUrls);
    } else {
      toast.error('Semua upload gagal');
    }
  };

  const clearAll = () => {
    files.forEach(f => URL.revokeObjectURL(f.preview));
    setFiles([]);
  };

  const pendingCount = files.filter(f => f.status === 'pending').length;
  const doneCount = files.filter(f => f.status === 'done').length;
  const errorCount = files.filter(f => f.status === 'error').length;
  const uploadingCount = files.filter(f => f.status === 'uploading').length;

  return (
    <div className={cn("space-y-4", className)}>
      {/* Drop Zone */}
      <div
        onDragEnter={handleDrag}
        onDragLeave={handleDrag}
        onDragOver={handleDrag}
        onDrop={handleDrop}
        onClick={() => inputRef.current?.click()}
        className={cn(
          "border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all",
          dragActive
            ? "border-toba-green bg-toba-green/5 scale-[1.01]"
            : "border-slate-200 hover:border-toba-green/50 hover:bg-slate-50"
        )}
      >
        <input
          ref={inputRef}
          type="file"
          multiple
          accept="image/*"
          onChange={handleFileInput}
          className="hidden"
        />
        <Upload className={cn("w-10 h-10 mx-auto mb-3 transition-colors", dragActive ? "text-toba-green" : "text-slate-300")} />
        <p className="font-bold text-slate-700 mb-1">
          {dragActive ? 'Lepaskan file di sini' : 'Drag & drop atau klik untuk pilih'}
        </p>
        <p className="text-xs text-slate-400">
          JPG, PNG, WebP, GIF • Maks {maxFiles} file • Auto-convert ke WebP
        </p>
      </div>

      {/* File List */}
      {files.length > 0 && (
        <div className="space-y-3">
          {/* Summary Bar */}
          <div className="flex items-center justify-between bg-slate-50 rounded-xl px-4 py-3">
            <div className="flex items-center gap-4 text-sm">
              <span className="font-bold text-slate-700">{files.length} file</span>
              {doneCount > 0 && (
                <span className="flex items-center gap-1 text-emerald-600 font-bold">
                  <CheckCircle className="w-4 h-4" /> {doneCount} selesai
                </span>
              )}
              {errorCount > 0 && (
                <span className="flex items-center gap-1 text-rose-500 font-bold">
                  <AlertCircle className="w-4 h-4" /> {errorCount} gagal
                </span>
              )}
              {uploadingCount > 0 && (
                <span className="flex items-center gap-1 text-blue-500 font-bold">
                  <Loader2 className="w-4 h-4 animate-spin" /> {uploadingCount} uploading
                </span>
              )}
            </div>
            <button
              onClick={clearAll}
              disabled={isUploading}
              className="text-xs text-slate-400 hover:text-rose-500 font-bold transition-colors disabled:opacity-50"
            >
              Hapus Semua
            </button>
          </div>

          {/* File Grid */}
          <div className="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
            {files.map(f => (
              <div key={f.id} className="relative group">
                <div className={cn(
                  "aspect-square rounded-xl overflow-hidden border-2 transition-all",
                  f.status === 'done' ? "border-emerald-400" :
                  f.status === 'error' ? "border-rose-400" :
                  f.status === 'uploading' ? "border-blue-400" :
                  "border-slate-200"
                )}>
                  <img
                    src={f.preview}
                    alt={f.file.name}
                    className="w-full h-full object-cover"
                  />

                  {/* Overlay for uploading */}
                  {f.status === 'uploading' && (
                    <div className="absolute inset-0 bg-blue-500/30 flex items-center justify-center">
                      <div className="w-8 h-8 border-3 border-white border-t-transparent rounded-full animate-spin" />
                    </div>
                  )}

                  {/* Overlay for done */}
                  {f.status === 'done' && (
                    <div className="absolute inset-0 bg-emerald-500/20 flex items-center justify-center">
                      <CheckCircle className="w-8 h-8 text-emerald-500" />
                    </div>
                  )}

                  {/* Overlay for error */}
                  {f.status === 'error' && (
                    <div className="absolute inset-0 bg-rose-500/20 flex items-center justify-center">
                      <AlertCircle className="w-8 h-8 text-rose-500" />
                    </div>
                  )}

                  {/* Progress bar */}
                  {f.status === 'uploading' && (
                    <div className="absolute bottom-0 left-0 right-0 h-1 bg-white/30">
                      <div
                        className="h-full bg-blue-500 transition-all duration-300"
                        style={{ width: `${f.progress}%` }}
                      />
                    </div>
                  )}
                </div>

                {/* Remove button */}
                {f.status !== 'uploading' && (
                  <button
                    onClick={() => removeFile(f.id)}
                    className="absolute -top-1.5 -right-1.5 w-5 h-5 bg-rose-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm"
                  >
                    <X className="w-3 h-3" />
                  </button>
                )}

                {/* Filename tooltip */}
                <p className="text-[10px] text-slate-500 truncate mt-1 text-center">{f.file.name}</p>
              </div>
            ))}
          </div>

          {/* Upload Button */}
          {pendingCount > 0 && (
            <button
              onClick={handleUploadAll}
              disabled={isUploading}
              className="w-full py-3.5 bg-toba-green text-white rounded-2xl font-bold text-sm hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20 disabled:opacity-60 flex items-center justify-center gap-2"
            >
              {isUploading ? (
                <>
                  <Loader2 className="w-4 h-4 animate-spin" />
                  Mengupload {uploadingCount} file...
                </>
              ) : (
                <>
                  <Upload className="w-4 h-4" />
                  Upload {pendingCount} File Sekarang
                </>
              )}
            </button>
          )}
        </div>
      )}
    </div>
  );
}
