"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { UserProfile } from '../types';
import { Search, User as UserIcon, Mail, Edit2, MoreHorizontal, Filter, ShieldCheck, ShieldAlert, RefreshCcw } from 'lucide-react';
import { motion } from 'framer-motion';
import { cn } from '../utils/cn';
import { toast } from 'sonner';

interface ApiUser {
  id: string | number;
  name?: string;
  email?: string;
  role?: string;
  photoURL?: string;
}

export default function AdminUsers() {
  const [users, setUsers] = useState<UserProfile[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');

  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    fetchData();
    
    // Auto refresh every 30 seconds
    const intervalId = setInterval(() => {
      fetchData(true);
    }, 30000);

    return () => clearInterval(intervalId);
  }, []);

  const fetchData = async (silent = false) => {
    if (!silent) setLoading(true);
    if (silent) setRefreshing(true);
    try {
      const res = await api.get('/users');
      // Normalize API response to UserProfile shape.
      const mapped: UserProfile[] = (res.data as ApiUser[]).map((u) => ({
        uid: String(u.id),
        id: typeof u.id === 'number' ? u.id : Number.parseInt(String(u.id), 10) || undefined,
        name: u.name,
        email: u.email ?? '',
        role: u.role ?? 'user',
        photoURL: u.photoURL,
      }));
      setUsers(mapped);
    } catch (error) {
      console.error('Error fetching users:', error);
      if (!silent) toast.error('Gagal memuat data pengguna');
    } finally {
      if (!silent) setLoading(false);
      setRefreshing(false);
    }
  };

  const handleRoleUpdate = async (uid: string | number, role: string) => {
    try {
      await api.put(`/users/${uid}`, { role });
      toast.success('Peran pengguna diperbarui');
      fetchData();
    } catch (error) {
      console.error('Error updating role:', error);
      toast.error('Gagal memperbarui peran');
    }
  };

  const filteredUsers = users.filter(user => 
    user.name?.toLowerCase().includes(searchQuery.toLowerCase()) ||
    user.email?.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="space-y-10 pb-12">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <div className="flex items-center gap-3 mb-2">
            <h2 className="text-4xl font-bold text-slate-900 tracking-tight">Manajemen Pengguna</h2>
            <button 
              onClick={() => fetchData(true)} 
              disabled={refreshing}
              className="p-2 text-slate-400 hover:text-toba-green transition-all disabled:opacity-50"
              title="Refresh data"
            >
              <RefreshCcw className={cn("w-6 h-6", refreshing && "animate-spin")} /> 
            </button>
          </div>
          <p className="text-slate-500 font-medium">Kelola peran, hak akses, dan informasi profil pengguna platform Anda.</p>
        </div>
        <div className="flex items-center space-x-3">
          <div className="bg-white px-5 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
            <div className="w-8 h-8 bg-toba-green/10 rounded-lg flex items-center justify-center text-toba-green">
              <ShieldCheck size={18} />
            </div>
            <div>
              <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Admin</p>
              <p className="text-lg font-bold text-slate-900">{users.filter(u => u.role === 'admin').length}</p>
            </div>
          </div>
        </div>
      </div>

      {/* Filters & Search */}
      <div className="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div className="relative w-full md:w-96">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input
            type="text"
            placeholder="Cari nama atau email pengguna..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green font-medium transition-all"
          />
        </div>
        <div className="flex items-center space-x-3 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center space-x-2 px-6 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-100 transition-all border border-slate-100">
            <Filter size={18} />
            <span>Filter Peran</span>
          </button>
          <button className="p-3 bg-slate-50 text-slate-400 hover:text-slate-600 rounded-xl border border-slate-100 transition-all">
            <MoreHorizontal size={20} />
          </button>
        </div>
      </div>
      
      {/* Table Container */}
      <div className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/50">
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Pengguna</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kontak</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Peran</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={4} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-12 h-12 border-4 border-blue-100 border-t-toba-green rounded-full animate-spin mb-4"></div>
                      <p className="text-slate-400 font-medium">Memuat data pengguna...</p>
                    </div>
                  </td>
                </tr>
              ) : filteredUsers.length > 0 ? filteredUsers.map((user, index) => (
                <motion.tr 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.05 }}
                  key={user.uid} 
                  className="hover:bg-slate-50/50 transition-colors group"
                >
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-4">
                      <div className="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-500 border border-white">
                        <img src={user.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name || 'U')}&background=10b981&color=fff&size=100`} alt="" className="w-full h-full object-cover" referrerPolicy="no-referrer" />
                      </div>
                      <div>
                        <p className="font-bold text-slate-900 text-lg group-hover:text-toba-green transition-colors">{user.name || 'Tanpa Nama'}</p>
                        <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">UID: {user.uid.slice(0, 8)}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-2.5 text-slate-600">
                      <div className="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                        <Mail size={14} />
                      </div>
                      <span className="text-sm font-medium">{user.email}</span>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-2">
                      <span className={cn(
                        "px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.15em] border flex items-center space-x-1.5",
                        user.role === 'admin' ? 'bg-rose-50 text-rose-600 border-rose-100' : 
                        user.role === 'staff' ? 'bg-blue-50 text-toba-green border-blue-100' : 'bg-slate-50 text-slate-400 border-slate-100'
                      )}>
                        {user.role === 'admin' ? <ShieldAlert size={10} /> : <UserIcon size={10} />}
                        <span>{user.role}</span>
                      </span>
                    </div>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end items-center space-x-3">
                      <select
                        value={user.role}
                        onChange={(e) => handleRoleUpdate(user.uid, e.target.value)}
                        className="bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold px-4 py-2.5 focus:ring-2 focus:ring-toba-green transition-all appearance-none cursor-pointer hover:bg-white"
                      >
                        <option value="user">User</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                      </select>
                      <button className="p-2.5 text-slate-400 hover:text-toba-green hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100 opacity-0 group-hover:opacity-100">
                        <Edit2 size={16} />
                      </button>
                    </div>
                  </td>
                </motion.tr>
              )) : (
                <tr>
                  <td colSpan={4} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-200">
                        <UserIcon size={32} />
                      </div>
                      <p className="text-slate-400 font-medium">Tidak ada pengguna yang ditemukan.</p>
                    </div>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

