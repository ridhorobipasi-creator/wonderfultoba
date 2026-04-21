export interface City {
  id: number | string;
  name: string;
  slug?: string;
  type?: 'domestic' | 'international';
  country?: string;
  region?: string;
  district?: string;
  place?: string;
  description?: string;
}

export interface Package {
  id: number | string;
  slug: string;
  name: string;
  description: string;
  price: number;
  duration: string;
  cityId?: number | string;
  city?: City;
  includes: string[];
  excludes: string[];
  images: string[];
  status: 'active' | 'inactive';
  isOutbound?: boolean;
  category?: 'tour' | 'outbound';
}

export interface Car {
  id: number | string;
  name: string;
  type: string;
  price?: number;
  pricePerDay?: number;
  capacity?: number;
  seats?: number;
  transmission?: string;
  fuel?: string;
  image?: string;
  images?: string[];
  status: 'active' | 'inactive' | 'available' | 'booked';
}

export interface Booking {
  id: number;
  userId?: number;
  type: 'package' | 'car';
  packageId?: number | string;
  carId?: number | string;
  startDate: string;
  endDate: string;
  totalPrice: number;
  customerName?: string;
  customerEmail?: string;
  customerPhone?: string;
  notes?: string;
  status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
  package?: Package;
  car?: Car;
  itemName?: string; // Aliased for easier UI
  itemImage?: string; // Aliased for easier UI
  metadata?: Record<string, unknown> | null;
  customerDetails?: {
    name: string;
    phone: string;
    email: string;
  };
  createdAt?: string;
}

export interface User {
  id: number;
  email: string;
  name?: string;
  phone?: string;
  photoURL?: string;
  role: string;
}

export interface UserProfile {
  uid: string;
  id?: number;
  email: string;
  name?: string;
  role: string;
  phone?: string;
  photoURL?: string;
}

export interface BlogPost {
  id: number | string;
  slug?: string;
  title: string;
  content: string;
  excerpt?: string;
  image?: string;
  author?: string;
  authorId?: number | string;
  category: string;
  tags: string[];
  status?: string;
  createdAt: string;
}
