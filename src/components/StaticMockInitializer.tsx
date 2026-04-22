"use client";

import { useEffect } from 'react';
import { 
  mockBlogs, mockTours, mockBookings, mockSettings, 
  mockCars, mockUser, mockOutboundServices, mockVideos, 
  mockLocations, mockClients, mockGallery, mockStats, 
  mockCities, mockPackageTiers
} from '../data/mockData';

export default function StaticMockInitializer() {
  useEffect(() => {
    if (typeof window === 'undefined') return;

    const originalFetch = window.fetch;

    window.fetch = async (...args) => {
      const url = typeof args[0] === 'string' ? args[0] : (args[0] as Request).url;
      
      console.log(`[Fetch Debug] ${url}`);

      if (url.includes('/api/blogs')) {
        return new Response(JSON.stringify(mockBlogs), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/packages')) {
        console.log(`[Fetch Mock] Returning ${mockTours.length} tours for ${url}`);
        return new Response(JSON.stringify(mockTours), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      const init = args[1] as RequestInit;
      const method = init?.method?.toUpperCase() || 'GET';

      if (url.includes('/api/bookings')) {
        if (method === 'POST') {
          return new Response(JSON.stringify({ success: true, message: 'Booking simulated successfully' }), { status: 200, headers: { 'Content-Type': 'application/json' } });
        }
        return new Response(JSON.stringify(mockBookings), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }

      if (url.includes('/api/cars')) {
        return new Response(JSON.stringify(mockCars), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/auth/me')) {
        return new Response(JSON.stringify(mockUser), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/auth/login')) {
        return new Response(JSON.stringify({ token: 'mock-token', user: mockUser }), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/outbound/services')) {
        return new Response(JSON.stringify(mockOutboundServices), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/outbound/videos')) {
        return new Response(JSON.stringify(mockVideos), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/outbound/locations')) {
        return new Response(JSON.stringify(mockLocations), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/clients')) {
        return new Response(JSON.stringify(mockClients), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/gallery')) {
        return new Response(JSON.stringify(mockGallery), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/settings')) {



        const key = new URL(url, window.location.origin).searchParams.get('key');
        const data = key ? (mockSettings as any)[key] : mockSettings;
        return new Response(JSON.stringify(data), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }

      if (url.includes('/api/stats')) {
        return new Response(JSON.stringify(mockStats), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/cities')) {
        return new Response(JSON.stringify(mockCities), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      if (url.includes('/api/package-tiers')) {
        return new Response(JSON.stringify(mockPackageTiers), { status: 200, headers: { 'Content-Type': 'application/json' } });
      }
      return originalFetch(...args);
    };

    console.log('[Static Mock] Global fetch patched for static preview');
  }, []);

  return null;
}
