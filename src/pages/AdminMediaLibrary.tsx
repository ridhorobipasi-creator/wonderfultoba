"use client";

import { useState, useEffect, useCallback } from 'react';
import { Image as ImageIcon, Trash2, Search, Grid3x3, List, Download, Eye, X, Check, Folder, RefreshCw, Copy, AlertCircle } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import BulkUpload from '../components/BulkUpload';

interface MediaFile {
  id: string;
  filename: string;
  url: string;
  size: number;
  type: string;
  folder: string;
  uploadedAt: string;
  dimensions?: { width: number; height: number };
}

interface FolderInfo {
  name: string;
  count: number;
}

export default function AdminMediaLibrary() {
  const [files, setFiles] = useState<MediaFile[]>([]);
  const [folders, setFolders] = useState<FolderInfo[]>([]);
  const [selectedFolder, setSelectedFolder] = useState('all');
  const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedFiles, setSelectedFiles] = useState<string[]>([]);
  const [previewFile, setPreviewFile] = useState<MediaFile | null>(null);
  const [loading, setLoading] = useState(true);
  const [totalFiles, setTotalFiles] = useState(0);
  const [totalSize, setTotalSize] = useState(0);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [showUpload, setShowUpload] = useState(false);
  const [deleting, setDeleting] = useState(false);

  useEffect(() => {
    fetchMedia();
  }, [selectedFolder, searchQuery, page]);

  const fetchMedia = async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams({
        folder: selectedFolder,
        page: String(page),
        limit: '48',
      });
      if (searchQuery) params.set('search', searchQuery);

      const res = await fetch(`/api/media?${params}`);
      if (!res.ok) throw new Error('Gagal memuat media');
      
      const data = await res.json();
      setFiles(data.files || []);
      setTotalFiles(data.total || 0);
      setTotalPages(data.totalPages || 1);
      setTotalSize(data.totalSize || 0);
      
      // Build folders list
      const allFolders: FolderInfo[] = [
        { name: 'all', count: data.total || 0 },
        ...(data.folders || []),
      ];
      setFolders(allFolders);
    } catch (error) {
      console.error('Error fetching media:', error);
      toast.error('Gagal memuat media library');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (fileUrls: string[]) => {
    if (!window.confirm(`Hapus ${fileUrls.length} file? Tindakan ini tidak bisa dibatalkan.`)) return;

    setDeleting(true);
    try {
      const res = await fetch('/api/media', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ urls: fileUrls }),
      });

      if (!res.ok) throw new Error('Gagal menghapus file');
      
      const data = await res.json();
      toast.success(data.message || 'File berhasil dihapus');
      setSelectedFiles([]);
      fetchMedia();
    } catch (error) {
      console.error('Delete error:', error);
      toast.error('Gagal menghapus file');
    } finally {
      setDeleting(false);
    }
  };

  const handleSelectFile = (fileId: string) => {
    setSelectedFiles(prev =>
      prev.includes(fileId)
        ? prev.filter(id => id !== fileId)
        : [...prev, fileId]
    );
  };

  const handleSelectAll = () => {
    if (selectedFiles.length === files.length) {
      setSelectedFiles([]);
    } else {
      setSelectedFiles(files.map(f => f.id));
    }
  };

  const copyToClipboard = (url: string) => {
    navigator.clipboard.writeText(window.location.origin + url);
    toast.success('URL disalin ke clipboard');
  };

  const formatFileSize = (bytes: number) => {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
  };

  const getSelectedUrls = () => {
    return files
      .filter(f => selectedFiles.includes(f.id))
      .map(f => f.url);
  };

  const handleUploadComplete = (urls: string[]) => {
    toast.success(`${urls.length} gambar berhasil diupload`);
    setShowUpload(false);
    setPage(1);
    fetchMedia();
  };

  return (
    <div className="space-y-6 pb-12">
      {/* Header */}
      <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
          <h1 className="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Media Library</h1>
          <p className="text-sm text-slate-400 font-medium mt-1">Kelola semua file gambar di satu tempat</p>
        </div>
        <div className="flex gap-2 w-full lg:w-auto">
          <button
            onClick={() => setShowUpload(!showUpload)}
            className="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 lg:px-6 py-3 bg-toba-green text-white rounded-2xl font-bold text-sm shadow-lg shadow-toba-green/20 hover:bg-toba-green/90 transition-all"
          >
            <ImageIcon className="w-4 h-4" />
            {showUpload ? 'Tutup Upload' : 'Upload Gambar'}
          </button>
          <button
            onClick={fetchMedia}
            className="p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-all"
            title="Refresh"
          >
            <RefreshCw className="w-4 h-4" />
          </button>
        </div>
      </div>

      {/* Bulk Upload Panel */}
      <AnimatePresence>
        {showUpload && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            className="overflow-hidden"
          >
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
              <h3 className="text-base font-black text-slate-900 mb-4">Upload Gambar Baru</h3>
              <BulkUpload onUploadComplete={handleUploadComplete} maxFiles={20} />
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Stats */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
        {[
          { label: 'Total File', value: totalFiles, icon: ImageIcon, color: 'toba-green' },
          { label: 'Folder', value: folders.length > 1 ? folders.length - 1 : 0, icon: Folder, color: 'blue-500' },
          { label: 'Storage', value: formatFileSize(totalSize), icon: Download, color: 'amber-500' },
          { label: 'Dipilih', value: selectedFiles.length, icon: Check, color: 'purple-500' },
        ].map(stat => (
          <div key={stat.label} className="bg-white p-4 lg:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{stat.label}</p>
            <p className="text-xl lg:text-2xl font-black text-slate-900">{stat.value}</p>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Sidebar - Folders */}
        <div className="lg:col-span-3">
          <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 lg:p-5 sticky top-4">
            <h3 className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Folder</h3>
            <div className="space-y-1">
              {folders.map(folder => (
                <button
                  key={folder.name}
                  onClick={() => { setSelectedFolder(folder.name); setPage(1); }}
                  className={cn(
                    "w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-bold text-sm transition-all",
                    selectedFolder === folder.name
                      ? "bg-toba-green text-white shadow-lg shadow-toba-green/20"
                      : "text-slate-600 hover:bg-slate-50"
                  )}
                >
                  <span className="flex items-center gap-2 truncate">
                    <Folder className="w-4 h-4 shrink-0" />
                    <span className="truncate">{folder.name === 'all' ? 'Semua Media' : folder.name}</span>
                  </span>
                  <span className={cn(
                    "text-xs font-black px-2 py-0.5 rounded-lg shrink-0 ml-1",
                    selectedFolder === folder.name ? "bg-white/20" : "bg-slate-100 text-slate-400"
                  )}>
                    {folder.count}
                  </span>
                </button>
              ))}
            </div>
          </div>
        </div>

        {/* Main Content */}
        <div className="lg:col-span-9 space-y-4">
          {/* Toolbar */}
          <div className="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <div className="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
              <div className="relative flex-1">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                <input
                  type="text"
                  placeholder="Cari file..."
                  value={searchQuery}
                  onChange={(e) => { setSearchQuery(e.target.value); setPage(1); }}
                  className="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green font-medium text-sm"
                />
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => setViewMode('grid')}
                  className={cn("p-2.5 rounded-xl transition-all", viewMode === 'grid' ? "bg-toba-green text-white" : "bg-slate-50 text-slate-400 hover:text-slate-600")}
                >
                  <Grid3x3 className="w-4 h-4" />
                </button>
                <button
                  onClick={() => setViewMode('list')}
                  className={cn("p-2.5 rounded-xl transition-all", viewMode === 'list' ? "bg-toba-green text-white" : "bg-slate-50 text-slate-400 hover:text-slate-600")}
                >
                  <List className="w-4 h-4" />
                </button>
                {selectedFiles.length > 0 && (
                  <button
                    onClick={() => handleDelete(getSelectedUrls())}
                    disabled={deleting}
                    className="px-4 py-2.5 bg-rose-50 text-rose-600 rounded-xl font-bold text-sm hover:bg-rose-100 transition-all flex items-center gap-2 disabled:opacity-60"
                  >
                    <Trash2 className="w-4 h-4" />
                    <span className="hidden sm:inline">Hapus ({selectedFiles.length})</span>
                  </button>
                )}
              </div>
            </div>
          </div>

          {/* Files */}
          {loading ? (
            <div className="bg-white p-20 rounded-2xl border border-slate-100 shadow-sm text-center">
              <div className="w-12 h-12 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin mx-auto mb-4" />
              <p className="text-slate-400 font-medium">Memuat media...</p>
            </div>
          ) : files.length === 0 ? (
            <div className="bg-white p-20 rounded-2xl border border-slate-100 shadow-sm text-center">
              <ImageIcon className="w-16 h-16 text-slate-200 mx-auto mb-4" />
              <p className="text-slate-500 font-bold mb-1">Tidak ada file</p>
              <p className="text-slate-400 text-sm">Upload gambar untuk mulai menggunakan media library</p>
            </div>
          ) : viewMode === 'grid' ? (
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
              {files.map((file, index) => (
                <motion.div
                  key={file.id}
                  initial={{ opacity: 0, scale: 0.9 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ delay: index * 0.03 }}
                  className={cn(
                    "bg-white rounded-2xl border-2 overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer group relative",
                    selectedFiles.includes(file.id) ? "border-toba-green ring-2 ring-toba-green/20" : "border-slate-100"
                  )}
                >
                  <div className="aspect-square relative overflow-hidden bg-slate-50">
                    <img
                      src={file.url}
                      alt={file.filename}
                      className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                      loading="lazy"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                      <div className="absolute bottom-2 left-2 right-2 flex gap-1">
                        <button
                          onClick={(e) => { e.stopPropagation(); setPreviewFile(file); }}
                          className="flex-1 p-2 bg-white/90 backdrop-blur-sm rounded-lg hover:bg-white transition-all"
                          title="Preview"
                        >
                          <Eye className="w-4 h-4 mx-auto text-slate-700" />
                        </button>
                        <button
                          onClick={(e) => { e.stopPropagation(); copyToClipboard(file.url); }}
                          className="flex-1 p-2 bg-white/90 backdrop-blur-sm rounded-lg hover:bg-white transition-all"
                          title="Copy URL"
                        >
                          <Copy className="w-4 h-4 mx-auto text-slate-700" />
                        </button>
                        <button
                          onClick={(e) => { e.stopPropagation(); handleDelete([file.url]); }}
                          className="flex-1 p-2 bg-rose-500/90 backdrop-blur-sm rounded-lg hover:bg-rose-500 transition-all"
                          title="Hapus"
                        >
                          <Trash2 className="w-4 h-4 mx-auto text-white" />
                        </button>
                      </div>
                    </div>
                    <button
                      onClick={() => handleSelectFile(file.id)}
                      className={cn(
                        "absolute top-2 right-2 w-6 h-6 rounded-lg flex items-center justify-center transition-all border-2",
                        selectedFiles.includes(file.id)
                          ? "bg-toba-green border-toba-green text-white"
                          : "bg-white/90 border-white/50 text-transparent hover:border-toba-green"
                      )}
                    >
                      {selectedFiles.includes(file.id) && <Check className="w-3.5 h-3.5" />}
                    </button>
                  </div>
                  <div className="p-2.5">
                    <p className="text-xs font-bold text-slate-900 truncate">{file.filename}</p>
                    <p className="text-[10px] text-slate-400 font-medium">{formatFileSize(file.size)}</p>
                  </div>
                </motion.div>
              ))}
            </div>
          ) : (
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
              <table className="w-full">
                <thead>
                  <tr className="bg-slate-50/50 border-b border-slate-100">
                    <th className="px-4 py-3 text-left w-10">
                      <input
                        type="checkbox"
                        checked={selectedFiles.length === files.length && files.length > 0}
                        onChange={handleSelectAll}
                        className="rounded"
                      />
                    </th>
                    <th className="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">File</th>
                    <th className="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden lg:table-cell">Ukuran</th>
                    <th className="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden lg:table-cell">Tanggal</th>
                    <th className="px-4 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-50">
                  {files.map(file => (
                    <tr key={file.id} className="hover:bg-slate-50/50 transition-colors">
                      <td className="px-4 py-3">
                        <input
                          type="checkbox"
                          checked={selectedFiles.includes(file.id)}
                          onChange={() => handleSelectFile(file.id)}
                          className="rounded"
                        />
                      </td>
                      <td className="px-4 py-3">
                        <div className="flex items-center gap-3">
                          <img src={file.url} alt="" className="w-10 h-10 rounded-lg object-cover bg-slate-100" loading="lazy" />
                          <div>
                            <p className="text-sm font-bold text-slate-900">{file.filename}</p>
                            <p className="text-xs text-slate-400">{file.folder}</p>
                          </div>
                        </div>
                      </td>
                      <td className="px-4 py-3 text-sm text-slate-600 hidden lg:table-cell">{formatFileSize(file.size)}</td>
                      <td className="px-4 py-3 text-sm text-slate-600 hidden lg:table-cell">
                        {new Date(file.uploadedAt).toLocaleDateString('id-ID')}
                      </td>
                      <td className="px-4 py-3 text-right">
                        <div className="flex justify-end gap-1">
                          <button onClick={() => setPreviewFile(file)} className="p-2 text-slate-400 hover:text-toba-green transition-all" title="Preview">
                            <Eye className="w-4 h-4" />
                          </button>
                          <button onClick={() => copyToClipboard(file.url)} className="p-2 text-slate-400 hover:text-blue-500 transition-all" title="Copy URL">
                            <Copy className="w-4 h-4" />
                          </button>
                          <button onClick={() => handleDelete([file.url])} className="p-2 text-slate-400 hover:text-rose-500 transition-all" title="Hapus">
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}

          {/* Pagination */}
          {totalPages > 1 && (
            <div className="flex items-center justify-center gap-2">
              <button
                onClick={() => setPage(p => Math.max(1, p - 1))}
                disabled={page === 1}
                className="px-4 py-2 rounded-xl font-bold text-sm bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 transition-all"
              >
                ← Prev
              </button>
              <span className="text-sm font-bold text-slate-600">
                {page} / {totalPages}
              </span>
              <button
                onClick={() => setPage(p => Math.min(totalPages, p + 1))}
                disabled={page === totalPages}
                className="px-4 py-2 rounded-xl font-bold text-sm bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 transition-all"
              >
                Next →
              </button>
            </div>
          )}
        </div>
      </div>

      {/* Preview Modal */}
      <AnimatePresence>
        {previewFile && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" onClick={() => setPreviewFile(null)}>
            <motion.div
              initial={{ opacity: 0, scale: 0.9 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.9 }}
              className="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl"
              onClick={e => e.stopPropagation()}
            >
              <div className="p-5 border-b border-slate-100 flex justify-between items-center">
                <h3 className="text-base font-black text-slate-900 truncate pr-4">{previewFile.filename}</h3>
                <button onClick={() => setPreviewFile(null)} className="p-2 hover:bg-slate-100 rounded-xl transition-all shrink-0">
                  <X className="w-5 h-5" />
                </button>
              </div>
              <div className="p-5">
                <img
                  src={previewFile.url}
                  alt={previewFile.filename}
                  className="w-full rounded-2xl max-h-96 object-contain bg-slate-50"
                />
                <div className="mt-4 grid grid-cols-3 gap-3">
                  <div className="bg-slate-50 rounded-xl p-3">
                    <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Ukuran</p>
                    <p className="text-sm font-bold text-slate-900">{formatFileSize(previewFile.size)}</p>
                  </div>
                  <div className="bg-slate-50 rounded-xl p-3">
                    <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Dimensi</p>
                    <p className="text-sm font-bold text-slate-900">
                      {previewFile.dimensions ? `${previewFile.dimensions.width}×${previewFile.dimensions.height}` : '-'}
                    </p>
                  </div>
                  <div className="bg-slate-50 rounded-xl p-3">
                    <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Format</p>
                    <p className="text-sm font-bold text-slate-900 uppercase">{previewFile.type.split('/')[1]}</p>
                  </div>
                </div>
                <div className="mt-3 bg-slate-50 rounded-xl p-3">
                  <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2">URL</p>
                  <div className="flex gap-2">
                    <input
                      type="text"
                      value={window.location.origin + previewFile.url}
                      readOnly
                      className="flex-1 px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-mono text-slate-600"
                    />
                    <button
                      onClick={() => copyToClipboard(previewFile.url)}
                      className="px-4 py-2 bg-toba-green text-white rounded-lg font-bold text-sm hover:bg-toba-green/90 transition-all flex items-center gap-1.5"
                    >
                      <Copy className="w-3.5 h-3.5" />
                      Copy
                    </button>
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}
