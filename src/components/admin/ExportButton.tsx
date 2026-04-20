"use client";

import { useState } from 'react';
import { Download, FileSpreadsheet, FileText, File } from 'lucide-react';
import { toast } from 'sonner';
import { exportToExcel, exportToPDF, exportToCSV, formatDataForExport } from '@/utils/exportUtils';
import { cn } from '@/utils/cn';

interface ExportButtonProps {
  data: any[];
  filename: string;
  type: 'bookings' | 'packages' | 'users' | 'finance';
  title?: string;
}

export default function ExportButton({ data, filename, type, title }: ExportButtonProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [exporting, setExporting] = useState(false);

  const handleExport = async (format: 'excel' | 'pdf' | 'csv') => {
    if (data.length === 0) {
      toast.error('Tidak ada data untuk diexport');
      return;
    }

    setExporting(true);
    try {
      const formattedData = formatDataForExport(data, type);
      const timestamp = new Date().toISOString().split('T')[0];
      const fullFilename = `${filename}-${timestamp}`;

      switch (format) {
        case 'excel':
          await exportToExcel(formattedData, fullFilename, title || 'Data');
          toast.success('Excel berhasil didownload');
          break;
        case 'pdf':
          exportToPDF(formattedData, fullFilename, title || 'Report');
          toast.success('PDF berhasil didownload');
          break;
        case 'csv':
          exportToCSV(formattedData, fullFilename);
          toast.success('CSV berhasil didownload');
          break;
      }
    } catch (error) {
      console.error('Export error:', error);
      toast.error('Gagal export data');
    } finally {
      setExporting(false);
      setIsOpen(false);
    }
  };

  return (
    <div className="relative">
      <button
        onClick={() => setIsOpen(!isOpen)}
        disabled={exporting}
        className="flex items-center gap-2 px-6 py-3 bg-toba-green text-white rounded-2xl font-bold text-sm shadow-lg shadow-toba-green/20 hover:bg-toba-green/90 transition-all disabled:opacity-50"
      >
        <Download className="w-4 h-4" />
        Export Data
      </button>

      {isOpen && (
        <>
          <div
            className="fixed inset-0 z-40"
            onClick={() => setIsOpen(false)}
          />
          <div className="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
            <div className="p-2">
              <button
                onClick={() => handleExport('excel')}
                disabled={exporting}
                className="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors text-left disabled:opacity-50"
              >
                <FileSpreadsheet className="w-5 h-5 text-green-600" />
                <div>
                  <p className="font-bold text-sm text-slate-900">Excel (.xlsx)</p>
                  <p className="text-xs text-slate-400">Spreadsheet format</p>
                </div>
              </button>

              <button
                onClick={() => handleExport('pdf')}
                disabled={exporting}
                className="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors text-left disabled:opacity-50"
              >
                <FileText className="w-5 h-5 text-red-600" />
                <div>
                  <p className="font-bold text-sm text-slate-900">PDF (.pdf)</p>
                  <p className="text-xs text-slate-400">Document format</p>
                </div>
              </button>

              <button
                onClick={() => handleExport('csv')}
                disabled={exporting}
                className="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors text-left disabled:opacity-50"
              >
                <File className="w-5 h-5 text-blue-600" />
                <div>
                  <p className="font-bold text-sm text-slate-900">CSV (.csv)</p>
                  <p className="text-xs text-slate-400">Comma separated</p>
                </div>
              </button>
            </div>
          </div>
        </>
      )}
    </div>
  );
}
