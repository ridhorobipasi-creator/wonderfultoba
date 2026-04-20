"use client";

import { useState, useCallback } from 'react';
import { Upload, X, Image as ImageIcon, File, Loader2 } from 'lucide-react';
import { toast } from 'sonner';
import { cn } from '@/utils/cn';

interface MediaUploadProps {
  onUpload: (urls: string[]) => void;
  maxFiles?: number;
  accept?: string;
  multiple?: boolean;
}

export default function MediaUpload({ 
  onUpload, 
  maxFiles = 5, 
  accept = "image/*",
  multiple = true 
}: MediaUploadProps) {
  const [uploading, setUploading] = useState(false);
  const [dragActive, setDragActive] = useState(false);
  const [previews, setPreviews] = useState<string[]>([]);

  const handleDrag = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === "dragenter" || e.type === "dragover") {
      setDragActive(true);
    } else if (e.type === "dragleave") {
      setDragActive(false);
    }
  }, []);

  const handleDrop = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);

    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      handleFiles(e.dataTransfer.files);
    }
  }, []);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    e.preventDefault();
    if (e.target.files && e.target.files[0]) {
      handleFiles(e.target.files);
    }
  };

  const handleFiles = async (files: FileList) => {
    const fileArray = Array.from(files);
    
    if (fileArray.length > maxFiles) {
      toast.error(`Maksimal ${maxFiles} file`);
      return;
    }

    // Validate file types
    const validFiles = fileArray.filter(file => {
      if (accept === "image/*") {
        return file.type.startsWith('image/');
      }
      return true;
    });

    if (validFiles.length !== fileArray.length) {
      toast.error('Beberapa file tidak valid');
    }

    // Create previews
    const previewUrls = validFiles.map(file => URL.createObjectURL(file));
    setPreviews(previewUrls);

    // Upload files
    setUploading(true);
    try {
      const formData = new FormData();
      validFiles.forEach(file => {
        formData.append('files', file);
      });

      const response = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) throw new Error('Upload failed');

      const data = await response.json();
      onUpload(data.urls);
      toast.success(`${validFiles.length} file berhasil diupload`);
    } catch (error) {
      console.error('Upload error:', error);
      toast.error('Gagal upload file');
    } finally {
      setUploading(false);
    }
  };

  const removePreview = (index: number) => {
    setPreviews(prev => prev.filter((_, i) => i !== index));
  };

  return (
    <div className="space-y-4">
      {/* Upload Area */}
      <div
        className={cn(
          "relative border-2 border-dashed rounded-2xl p-8 transition-all",
          dragActive ? "border-toba-green bg-toba-green/5" : "border-slate-200 hover:border-slate-300",
          uploading && "opacity-50 pointer-events-none"
        )}
        onDragEnter={handleDrag}
        onDragLeave={handleDrag}
        onDragOver={handleDrag}
        onDrop={handleDrop}
      >
        <input
          type="file"
          id="file-upload"
          className="hidden"
          multiple={multiple}
          accept={accept}
          onChange={handleChange}
          disabled={uploading}
        />
        
        <label
          htmlFor="file-upload"
          className="flex flex-col items-center justify-center cursor-pointer"
        >
          {uploading ? (
            <>
              <Loader2 className="w-12 h-12 text-toba-green animate-spin mb-4" />
              <p className="text-sm font-bold text-slate-600">Uploading...</p>
            </>
          ) : (
            <>
              <Upload className="w-12 h-12 text-slate-400 mb-4" />
              <p className="text-sm font-bold text-slate-900 mb-1">
                Klik untuk upload atau drag & drop
              </p>
              <p className="text-xs text-slate-400">
                {accept === "image/*" ? "PNG, JPG, WEBP" : "Semua file"} (Max {maxFiles} files)
              </p>
            </>
          )}
        </label>
      </div>

      {/* Preview Grid */}
      {previews.length > 0 && (
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {previews.map((preview, index) => (
            <div key={index} className="relative group">
              <div className="aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                <img
                  src={preview}
                  alt={`Preview ${index + 1}`}
                  className="w-full h-full object-cover"
                />
              </div>
              <button
                onClick={() => removePreview(index)}
                className="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <X className="w-4 h-4" />
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
