"use client";

import { useState } from 'react';
import { Shield, User, Users, Key, X } from 'lucide-react';
import { toast } from 'sonner';
import { cn } from '@/utils/cn';

interface UserRoleManagerProps {
  user: {
    id: number;
    name: string;
    email: string;
    role: string;
  };
  onUpdate: () => void;
  onClose: () => void;
}

export default function UserRoleManager({ user, onUpdate, onClose }: UserRoleManagerProps) {
  const [selectedRole, setSelectedRole] = useState(user.role);
  const [updating, setUpdating] = useState(false);
  const [resettingPassword, setResettingPassword] = useState(false);
  const [newPassword, setNewPassword] = useState('');

  const roles = [
    {
      value: 'user',
      label: 'User',
      icon: User,
      description: 'Akses terbatas, hanya bisa booking',
      color: 'text-blue-600 bg-blue-50 border-blue-100',
    },
    {
      value: 'staff',
      label: 'Staff',
      icon: Users,
      description: 'Akses menengah, bisa manage bookings',
      color: 'text-purple-600 bg-purple-50 border-purple-100',
    },
    {
      value: 'admin',
      label: 'Admin',
      icon: Shield,
      description: 'Akses penuh ke semua fitur',
      color: 'text-red-600 bg-red-50 border-red-100',
    },
  ];

  const handleUpdateRole = async () => {
    if (selectedRole === user.role) {
      toast.info('Role tidak berubah');
      return;
    }

    setUpdating(true);
    try {
      const response = await fetch(`/api/users/${user.id}/role`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ role: selectedRole }),
      });

      if (!response.ok) throw new Error('Failed to update role');

      toast.success('Role berhasil diupdate');
      onUpdate();
      onClose();
    } catch (error) {
      console.error('Update role error:', error);
      toast.error('Gagal update role');
    } finally {
      setUpdating(false);
    }
  };

  const handleResetPassword = async () => {
    if (!newPassword || newPassword.length < 6) {
      toast.error('Password minimal 6 karakter');
      return;
    }

    setResettingPassword(true);
    try {
      const response = await fetch(`/api/users/${user.id}/password-reset`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ newPassword }),
      });

      if (!response.ok) throw new Error('Failed to reset password');

      toast.success('Password berhasil direset');
      setNewPassword('');
    } catch (error) {
      console.error('Password reset error:', error);
      toast.error('Gagal reset password');
    } finally {
      setResettingPassword(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
      <div className="bg-white rounded-[3rem] w-full max-w-2xl overflow-hidden shadow-2xl">
        {/* Header */}
        <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
          <div>
            <h3 className="text-2xl font-bold text-slate-900">Manage User</h3>
            <p className="text-sm text-slate-500 mt-1">{user.name} • {user.email}</p>
          </div>
          <button
            onClick={onClose}
            className="p-2 hover:bg-white rounded-xl text-slate-400 hover:text-slate-900 transition-all"
          >
            <X size={22} />
          </button>
        </div>

        <div className="p-8 space-y-8">
          {/* Role Selection */}
          <div>
            <label className="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 block">
              User Role
            </label>
            <div className="space-y-3">
              {roles.map((role) => (
                <button
                  key={role.value}
                  onClick={() => setSelectedRole(role.value)}
                  className={cn(
                    "w-full flex items-center gap-4 p-4 rounded-2xl border-2 transition-all text-left",
                    selectedRole === role.value
                      ? role.color + " shadow-lg"
                      : "border-slate-100 hover:border-slate-200"
                  )}
                >
                  <div className={cn(
                    "w-12 h-12 rounded-xl flex items-center justify-center",
                    selectedRole === role.value ? role.color : "bg-slate-50"
                  )}>
                    <role.icon className="w-6 h-6" />
                  </div>
                  <div className="flex-1">
                    <p className="font-black text-slate-900">{role.label}</p>
                    <p className="text-xs text-slate-500">{role.description}</p>
                  </div>
                  {selectedRole === role.value && (
                    <div className="w-6 h-6 bg-toba-green rounded-full flex items-center justify-center">
                      <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                      </svg>
                    </div>
                  )}
                </button>
              ))}
            </div>
          </div>

          {/* Password Reset */}
          <div className="pt-6 border-t border-slate-100">
            <label className="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 block">
              Reset Password
            </label>
            <div className="flex gap-3">
              <div className="flex-1">
                <input
                  type="password"
                  value={newPassword}
                  onChange={(e) => setNewPassword(e.target.value)}
                  placeholder="Password baru (min 6 karakter)"
                  className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold"
                />
              </div>
              <button
                onClick={handleResetPassword}
                disabled={resettingPassword || !newPassword}
                className="px-6 py-4 bg-amber-500 text-white rounded-2xl font-bold hover:bg-amber-600 transition-all disabled:opacity-50 flex items-center gap-2"
              >
                <Key className="w-5 h-5" />
                Reset
              </button>
            </div>
          </div>

          {/* Actions */}
          <div className="flex gap-3 pt-4">
            <button
              onClick={onClose}
              className="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl font-bold hover:bg-slate-200 transition-all"
            >
              Batal
            </button>
            <button
              onClick={handleUpdateRole}
              disabled={updating || selectedRole === user.role}
              className="flex-1 px-6 py-4 bg-toba-green text-white rounded-2xl font-bold hover:bg-toba-green/90 transition-all disabled:opacity-50"
            >
              {updating ? 'Menyimpan...' : 'Simpan Role'}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
