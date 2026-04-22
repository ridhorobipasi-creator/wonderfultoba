import axios from 'axios';
import { 
  mockBlogs, mockTours, mockBookings, mockSettings, 
  mockCars, mockUser, mockOutboundServices, mockVideos, 
  mockLocations, mockClients, mockGallery, mockStats,
  mockCities, mockPackageTiers
} from '../data/mockData';

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
    const method = config.method?.toUpperCase() || 'GET';
    let data: any = null;
    
    // For non-GET requests (POST, PUT, DELETE), return success instead of fetching list
    if (method !== 'GET' && !url.includes('/auth/login')) {
      return Promise.resolve({
        ...config,
        data: { success: true, message: 'Simulated success' },
        status: 200, statusText: 'OK', headers: {}, config
      } as any);
    }

    if (url.includes('/blogs')) data = mockBlogs;
    else if (url.includes('/packages')) {
      data = mockTours;
      console.log(`[Static Mock] Returning ${data.length} tours for ${url}`);
    }
    else if (url.includes('/bookings')) data = mockBookings;
    else if (url.includes('/cars')) data = mockCars;
    else if (url.includes('/auth/me')) data = mockUser;
    else if (url.includes('/auth/login')) data = { token: 'mock-token', user: mockUser };
    else if (url.includes('/outbound/services')) data = mockOutboundServices;
    else if (url.includes('/outbound/videos')) data = mockVideos;
    else if (url.includes('/outbound/locations')) data = mockLocations;
    else if (url.includes('/clients')) data = mockClients;
    else if (url.includes('/gallery')) data = mockGallery;
    else if (url.includes('/stats')) data = mockStats;
    else if (url.includes('/cities')) data = mockCities;
    else if (url.includes('/package-tiers')) data = mockPackageTiers;
    else if (url.includes('/api/dashboard')) {
      data = { 
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
      };
    }
    else if (url.includes('/settings')) {
      const key = new URLSearchParams(url.split('?')[1] || '').get('key');
      data = key ? (mockSettings as any)[key] : mockSettings;
    }
    
    if (data) {
      // console.log(`[Static Mock] Intercepted Request: ${url} (Method: ${method})`);
      return Promise.resolve({
        data,
        status: 200,
        statusText: 'OK',
        headers: config.headers || {},
        config: { ...config, adapter: undefined } // Ensure axios doesn't try to send it
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

