import { create } from 'zustand';
import { UserProfile } from '../types';

interface AppState {
  user: UserProfile | null;
  setUser: (user: UserProfile | null) => void;
  isLoading: boolean;
  setIsLoading: (loading: boolean) => void;
  token: string | null;
  setToken: (token: string | null) => void;
}

const getInitialToken = () => {
  if (typeof window === 'undefined') return null;
  return localStorage.getItem('auth_token');
};

const getInitialUser = () => {
  if (typeof window === 'undefined') return null;
  const raw = localStorage.getItem('auth_user');
  if (!raw) return null;

  try {
    return JSON.parse(raw) as UserProfile;
  } catch {
    localStorage.removeItem('auth_user');
    return null;
  }
};

export const useStore = create<AppState>((set) => ({
  user: getInitialUser(),
  setUser: (user) => set({ user }),
  isLoading: false,
  setIsLoading: (loading) => set({ isLoading: loading }),
  token: getInitialToken(),
  setToken: (token) => {
    if (typeof window !== 'undefined') {
      if (token) {
        localStorage.setItem('auth_token', token);
      } else {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
      }
    }
    set({ token });
  },
}));
