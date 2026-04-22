import axios from 'axios';
import { mockBlogs, mockTours, mockBookings, mockSettings, mockCars } from '../data/mockData';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || '/api',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor for static preview
api.interceptors.request.use((config) => {
  const url = config.url || '';
  
  if (process.env.NEXT_PUBLIC_STATIC_PREVIEW === 'true' || true) { // Force true for now as per user request
    let data: any = null;
    
    if (url.includes('/blogs')) data = mockBlogs;
    else if (url.includes('/packages')) data = mockTours;
    else if (url.includes('/bookings')) data = mockBookings;
    else if (url.includes('/cars')) data = mockCars;
    else if (url.includes('/auth/me')) data = mockUser;
    else if (url.includes('/auth/login')) data = { token: 'mock-token', user: mockUser };
    else if (url.includes('/settings')) {
      const key = new URLSearchParams(url.split('?')[1] || '').get('key');
      data = key ? (mockSettings as any)[key] : mockSettings;
    }


    
    if (data) {
      console.log(`[Static Mock] Intercepted ${url}`);
      return Promise.resolve({
        ...config,
        data,
        status: 200,
        statusText: 'OK',
        headers: {},
        config
      } as any);
    }
  }
  
  return config;
});

// Add a request interceptor to include auth token
api.interceptors.request.use(
  (config) => {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('auth_token');
      if (token && config.headers) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    }
    return config;
  },
  (error) => Promise.reject(error)
);

export default api;

