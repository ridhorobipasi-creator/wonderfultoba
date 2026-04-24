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
    if (window.fetch.name === 'patchedFetch') return;

    const originalFetch = window.fetch;

    window.fetch = async function patchedFetch(...args) {
      const request = args[0];
      if (!request) return originalFetch.apply(this, args);

      let url = '';
      try {
        url = typeof request === 'string' ? request : (request as Request).url;
      } catch (e) {
        return originalFetch.apply(this, args);
      }

      if (!url || typeof url !== 'string' || url.includes('_rsc=')) {
        return originalFetch.apply(this, args);
      }
      
      console.log(`[Fetch Debug] ${url}`);

      try {
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
        if (url.includes('/api/dashboard')) {
          return new Response(JSON.stringify({ 
            totalBookings: 124,
            pendingBookings: 8,
            totalRevenue: 450000000,
            tourPackages: mockTours.filter(p => !p.isOutbound).length,
            outboundPackages: mockTours.filter(p => !!p.isOutbound).length,
            recentBookings: mockBookings.map(b => ({
              customer_name: b.customer_name,
              start_date: b.booking_date,
              total_price: b.total_price,
              status: b.status.toLowerCase() as any,
              type: b.tour_name || 'Tour'
            })),
            chartData: [
              { date: '2026-04-15', revenue: 15000000 },
              { date: '2026-04-16', revenue: 25000000 },
              { date: '2026-04-17', revenue: 10000000 },
              { date: '2026-04-18', revenue: 35000000 },
              { date: '2026-04-19', revenue: 45000000 },
              { date: '2026-04-20', revenue: 30000000 },
              { date: '2026-04-21', revenue: 55000000 },
            ]
          }), { status: 200, headers: { 'Content-Type': 'application/json' } });
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
      } catch (e) {
        console.warn('[Fetch Mock Error]', e);
      }
      return originalFetch.apply(this, args);
    };

    console.log('[Static Mock] Global fetch patched for static preview');
  }, []);

  return null;
}
