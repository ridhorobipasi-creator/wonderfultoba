"use client";

import { useState } from 'react';
import { 
  TrendingUp, Wallet, ArrowUpRight, ArrowDownRight,
  Download, PieChart,
  CalendarDays, ChevronRight
} from 'lucide-react';
import { motion } from 'framer-motion';
import { cn } from '../utils/cn';

interface RevenueByCategory {
  name: string;
  value: number;
  color: string;
}

interface TransactionItem {
  id: string;
  customer: string;
  item: string;
  amount: number;
  date: string;
  method: string;
  status: string;
}

interface FinanceStats {
  totalOmzet: number;
  monthlyRevenue: { month: string; amount: number }[];
  byCategory: RevenueByCategory[];
  recentTransactions: TransactionItem[];
}

export default function AdminFinance() {
  const [stats] = useState<FinanceStats>({
    totalOmzet: 250000000,
    monthlyRevenue: [
      { month: 'Jan', amount: 45000000 },
      { month: 'Feb', amount: 52000000 },
      { month: 'Mar', amount: 68000000 },
      { month: 'Apr', amount: 85000000 },
    ],
    byCategory: [
      { name: 'Tour & Travel', value: 180000000, color: 'bg-toba-green' },
      { name: 'Corporate Outbound', value: 45000000, color: 'bg-obaja-blue' },
      { name: 'Rental Mobil', value: 25000000, color: 'bg-amber-500' },
    ],
    recentTransactions: [
      { id: 'TRX001', customer: 'Andi Pratama', item: 'Paket Danau Toba 3D2N', amount: 5500000, date: '12 Apr 2026', method: 'Transfer Bank', status: 'Success' },
      { id: 'TRX002', customer: 'Budi Santoso', item: 'Sewa Innova Reborn', amount: 1300000, date: '11 Apr 2026', method: 'Cash', status: 'Success' },
      { id: 'TRX003', customer: 'Citra Kirana', item: 'Outbound Team Building', amount: 15000000, date: '10 Apr 2026', method: 'Transfer Bank', status: 'Success' },
    ]
  });

  return (
    <div className="space-y-10 pb-12">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h2 className="text-4xl font-black text-slate-900 tracking-tight mb-2">Laporan <span className="text-toba-green">Keuangan</span></h2>
          <p className="text-slate-500 font-medium">Analisis pendapatan dan performa bisnis Anda.</p>
        </div>
        <button className="flex items-center gap-2 px-6 py-4 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
          <Download size={18} />
          <span>Ekspor Laporan (PDF/Excel)</span>
        </button>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
          <div className="absolute top-0 right-0 w-32 h-32 bg-toba-green/5 rounded-bl-[5rem] -mr-8 -mt-8 transition-all group-hover:scale-110" />
          <div className="w-12 h-12 bg-toba-green text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-100">
            <TrendingUp size={22} />
          </div>
          <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Total Omzet Bersih</p>
          <h3 className="text-3xl font-black text-slate-900 leading-none">Rp {stats.totalOmzet.toLocaleString('id-ID')}</h3>
          <div className="flex items-center gap-1 mt-4 text-emerald-600 font-bold text-xs">
            <ArrowUpRight size={14} />
            <span>+12.5% dari bulan lalu</span>
          </div>
        </div>

        <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
          <div className="absolute top-0 right-0 w-32 h-32 bg-obaja-blue/5 rounded-bl-[5rem] -mr-8 -mt-8 transition-all group-hover:scale-110" />
          <div className="w-12 h-12 bg-obaja-blue text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-100">
            <Wallet size={22} />
          </div>
          <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Pendapatan Bulan Ini</p>
          <h3 className="text-3xl font-black text-slate-900 leading-none">Rp 85.000.000</h3>
          <div className="flex items-center gap-1 mt-4 text-emerald-600 font-bold text-xs">
            <ArrowUpRight size={14} />
            <span>+5.2% dari target harian</span>
          </div>
        </div>

        <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
          <div className="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-bl-[5rem] -mr-8 -mt-8 transition-all group-hover:scale-110" />
          <div className="w-12 h-12 bg-amber-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-amber-100">
            <CalendarDays size={22} />
          </div>
          <p className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Rata-rata Per Transaksi</p>
          <h3 className="text-3xl font-black text-slate-900 leading-none">Rp 4.250.000</h3>
          <div className="flex items-center gap-1 mt-4 text-rose-500 font-bold text-xs">
            <ArrowDownRight size={14} />
            <span>-2.1% variasi harga</span>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {/* Category Breakdown */}
        <div className="lg:col-span-4 space-y-6">
          <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm h-full">
            <div className="flex items-center justify-between mb-8">
              <h3 className="text-xl font-black text-slate-900 tracking-tight">Per Kategori</h3>
              <PieChart size={20} className="text-slate-300" />
            </div>
            <div className="space-y-6">
              {stats.byCategory.map((cat, idx: number) => (
                <div key={idx} className="space-y-2">
                  <div className="flex justify-between items-center text-sm font-black">
                    <span className="text-slate-600">{cat.name}</span>
                    <span className="text-slate-900">Rp {(cat.value / 1000000).toFixed(1)}jt</span>
                  </div>
                  <div className="w-full h-3 bg-slate-50 rounded-full overflow-hidden">
                    <motion.div 
                      initial={{ width: 0 }}
                      animate={{ width: `${(cat.value / stats.totalOmzet) * 100}%` }}
                      transition={{ duration: 1, delay: idx * 0.1 }}
                      className={cn("h-full rounded-full transition-all", cat.color)}
                    />
                  </div>
                </div>
              ))}
            </div>
            <div className="mt-10 p-5 bg-slate-50 rounded-2xl border border-slate-100">
              <p className="text-xs text-slate-500 font-medium leading-relaxed italic">
                &ldquo;Kategori <b>Tour &amp; Travel</b> masih menjadi penyumbang terbesar omzet, disusul oleh Outbound Korporat.&rdquo;
              </p>
            </div>
          </div>
        </div>

        {/* Transactions List */}
        <div className="lg:col-span-8 flex flex-col">
          <div className="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col h-full">
            <div className="p-8 border-b border-slate-50 flex justify-between items-center">
              <h3 className="text-xl font-black text-slate-900 tracking-tight">Transaksi Terakhir</h3>
              <button className="text-xs font-black text-toba-green flex items-center gap-1 hover:underline">
                Lihat Semua <ChevronRight size={14} />
              </button>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-left">
                <thead>
                  <tr className="bg-slate-50/50">
                    <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">ID / Item</th>
                    <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Pelanggan</th>
                    <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Nominal</th>
                    <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Status</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-50">
                  {stats.recentTransactions.map((trx, idx: number) => (
                    <tr key={idx} className="hover:bg-slate-50/50 transition-colors">
                      <td className="px-8 py-5">
                        <p className="font-black text-slate-900 text-sm">{trx.id}</p>
                        <p className="text-[10px] text-slate-400 font-bold uppercase">{trx.item}</p>
                      </td>
                      <td className="px-8 py-5">
                        <p className="font-bold text-slate-700 text-sm">{trx.customer}</p>
                        <p className="text-[10px] text-slate-400 font-medium">{trx.date}</p>
                      </td>
                      <td className="px-8 py-5">
                        <p className="font-black text-slate-900">Rp {trx.amount.toLocaleString('id-ID')}</p>
                        <p className="text-[10px] text-slate-400 font-medium">{trx.method}</p>
                      </td>
                      <td className="px-8 py-5">
                        <span className="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black tracking-widest uppercase border border-emerald-100">
                          {trx.status}
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
