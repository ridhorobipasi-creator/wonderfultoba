# 📊 Panel Admin Wonderful Toba - Panduan Lengkap

## 🎯 Overview

Panel admin Wonderful Toba adalah sistem manajemen konten (CMS) yang lengkap untuk mengelola website tour & travel dan outbound. Dibangun dengan Next.js 16, React 19, TypeScript, Prisma ORM, dan MySQL.

## ✨ Fitur Utama

### 1. **Dashboard Pusat Komando**
- 📈 Statistik real-time (Total Reservasi, Omzet, Paket Aktif)
- 📊 Chart tren pendapatan 7 hari terakhir
- 🔄 Auto-refresh setiap 10 detik
- 📥 Export laporan ke JSON
- 🎨 UI modern dengan Tailwind CSS

### 2. **Manajemen Reservasi (Bookings)**
- ✅ Filter berdasarkan status (All, Pending, Confirmed)
- 🔍 Pencarian berdasarkan nama pelanggan
- 📋 Tabel responsif dengan detail lengkap
- 🎭 Modal detail reservasi
- 🔄 Update status reservasi
- 🎨 Animasi smooth dengan Framer Motion

### 3. **Manajemen Paket Wisata**
- 📦 CRUD paket tour dan outbound
- 🖼️ Upload multiple images
- 💰 Pricing details dengan tier pax
- 📝 Rich text editor untuk deskripsi
- 🗺️ Itinerary builder
- 🏷️ Kategori dan tags
- 🌐 Multi-language support (ID, EN, MS)

### 4. **CMS Landing Page**
- 🎨 **Tour CMS**: Kelola hero, slider, features, testimonials
- 🏢 **Outbound CMS**: Kelola konten corporate outbound
- 💾 Auto-save dengan debounce
- 🖼️ Image preview real-time
- 📱 Responsive editor

### 5. **Manajemen Armada Mobil**
- 🚗 CRUD kendaraan rental
- 📸 Gallery images
- 💵 Pricing dengan/tanpa driver
- 📋 Features & includes list
- 📄 Terms & conditions

### 6. **Blog & Artikel**
- ✍️ Rich text editor
- 🖼️ Featured image
- 📅 Publish scheduling
- 🏷️ Categories & tags
- 🔍 SEO optimization

### 7. **Laporan Keuangan**
- 💰 Revenue tracking
- 📊 Export ke Excel/PDF
- 📈 Grafik pendapatan
- 🧾 Invoice generation

### 8. **Manajemen Pengguna**
- 👥 User roles (admin, user)
- 🔐 Authentication dengan JWT
- 📧 Email verification
- 🔑 Password reset

## 🚀 Teknologi Stack

```
Frontend:
- Next.js 16.2.4 (App Router)
- React 19.2.4
- TypeScript 5.9.3
- Tailwind CSS 4
- Framer Motion 12.38.0
- Recharts 3.8.1 (Charts)
- Lucide React (Icons)
- React Hook Form + Zod (Forms)
- Zustand (State Management)
- Sonner (Toast Notifications)

Backend:
- Next.js API Routes
- Prisma ORM 6.3.0
- MySQL Database
- Jose (JWT)
- Bcrypt.js (Password Hashing)

Tools:
- ESLint
- PostCSS
```

## 📁 Struktur Folder

```
src/
├── app/
│   ├── admin/                    # Admin routes
│   │   ├── page.tsx             # Dashboard
│   │   ├── bookings/            # Manajemen reservasi
│   │   ├── packages/            # Manajemen paket
│   │   ├── cars/                # Manajemen armada
│   │   ├── cms-tour/            # CMS Tour
│   │   ├── cms-outbound/        # CMS Outbound
│   │   ├── blog/                # Blog management
│   │   ├── users/               # User management
│   │   └── finance/             # Laporan keuangan
│   ├── api/                     # API endpoints
│   │   ├── auth/                # Authentication
│   │   ├── bookings/            # Booking API
│   │   ├── packages/            # Package API
│   │   ├── cars/                # Car API
│   │   ├── dashboard/           # Dashboard stats
│   │   └── settings/            # CMS settings
│   └── (public)/                # Public routes
├── components/
│   ├── admin/                   # Admin components
│   │   ├── AdminSidebar.tsx
│   │   └── RevenueTrendChart.tsx
│   └── ui/                      # Reusable UI components
├── pages/                       # Page components
│   ├── AdminDashboard.tsx
│   └── AdminBookings.tsx
├── lib/
│   ├── prisma.ts               # Prisma client
│   └── api.ts                  # API client
├── store/
│   └── useStore.ts             # Zustand store
└── utils/
    └── cn.ts                   # Class name utility
```

## 🔧 Setup & Installation

### 1. Clone & Install Dependencies

```bash
npm install
```

### 2. Setup Database

```bash
# Copy environment variables
cp .env.example .env

# Edit .env dan isi DATABASE_URL
# DATABASE_URL="mysql://user:password@localhost:3306/wonderfultoba"

# Generate Prisma Client
npx prisma generate

# Run migrations
npx prisma migrate dev

# Seed database (optional)
npx prisma db seed
```

### 3. Run Development Server

```bash
npm run dev
```

Buka http://localhost:3000/admin

### 4. Build for Production

```bash
npm run build
npm start
```

## 🔐 Authentication

### Default Admin Credentials
```
Email: admin@wonderfultoba.com
Password: admin123
```

**⚠️ PENTING: Ganti password default setelah login pertama!**

## 📊 API Endpoints

### Dashboard
```
GET /api/dashboard
Response: {
  totalBookings, pendingBookings, totalRevenue,
  tourPackages, outboundPackages, recentBookings,
  chartData: [{ date, revenue }]
}
```

### Bookings
```
GET    /api/bookings              # List all bookings
GET    /api/bookings/:id          # Get booking detail
POST   /api/bookings              # Create booking
PUT    /api/bookings/:id          # Update booking
DELETE /api/bookings/:id          # Delete booking
```

### Packages
```
GET    /api/packages              # List packages
GET    /api/packages/:slug        # Get package by slug
POST   /api/packages              # Create package
PUT    /api/packages/:id          # Update package
DELETE /api/packages/:id          # Delete package
```

### CMS Settings
```
GET  /api/settings/landing-tour      # Get tour CMS data
POST /api/settings/landing-tour      # Save tour CMS data
GET  /api/settings/landing-outbound  # Get outbound CMS data
POST /api/settings/landing-outbound  # Save outbound CMS data
```

## 🎨 Design System

### Colors
```css
--toba-green: #15803d      /* Primary brand color */
--toba-accent: #22c55e     /* Accent green */
--obaja-blue: #005696      /* Secondary blue */
```

### Typography
```
Font Family: Plus Jakarta Sans
Weights: 300, 400, 500, 600, 700, 800
```

### Border Radius
```
Small: 12px (rounded-xl)
Medium: 16px (rounded-2xl)
Large: 32px (rounded-[2rem])
Extra Large: 40px (rounded-[2.5rem])
```

## 🔥 Fitur Unggulan

### 1. Real-time Dashboard
- Auto-refresh data setiap 10 detik
- Chart interaktif dengan Recharts
- Responsive di semua device

### 2. Smart Filtering
- Filter multi-kriteria
- Search dengan debounce
- Pagination otomatis

### 3. Image Management
- Upload multiple images
- Image preview
- Lazy loading
- Optimized delivery

### 4. Form Validation
- React Hook Form + Zod
- Real-time validation
- Error messages yang jelas

### 5. Toast Notifications
- Success/Error feedback
- Auto-dismiss
- Customizable

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Check MySQL service
sudo systemctl status mysql

# Test connection
mysql -u root -p

# Regenerate Prisma Client
npx prisma generate
```

### Build Errors
```bash
# Clear cache
rm -rf .next node_modules
npm install
npm run build
```

### API Not Working
```bash
# Check environment variables
cat .env

# Restart dev server
npm run dev
```

## 📈 Performance Optimization

1. **Image Optimization**: Gunakan Next.js Image component
2. **Code Splitting**: Lazy load components dengan dynamic import
3. **API Caching**: Implement SWR atau React Query
4. **Database Indexing**: Index pada kolom yang sering di-query
5. **CDN**: Deploy static assets ke CDN

## 🔒 Security Best Practices

1. ✅ JWT authentication dengan httpOnly cookies
2. ✅ Password hashing dengan bcrypt
3. ✅ SQL injection protection dengan Prisma
4. ✅ XSS protection dengan React
5. ✅ CSRF protection
6. ⚠️ Rate limiting (TODO)
7. ⚠️ Input sanitization (TODO)

## 🚀 Deployment

### Vercel (Recommended)
```bash
# Install Vercel CLI
npm i -g vercel

# Deploy
vercel --prod
```

### Docker
```bash
# Build image
docker build -t wonderfultoba-admin .

# Run container
docker run -p 3000:3000 wonderfultoba-admin
```

## 📝 Changelog

### v1.0.0 (Current)
- ✅ Dashboard dengan statistik real-time
- ✅ Manajemen reservasi lengkap
- ✅ CMS Tour & Outbound
- ✅ Chart pendapatan
- ✅ Export laporan
- ✅ Responsive design

### Roadmap v1.1.0
- 🔄 Export ke Excel/PDF
- 🔄 Email notifications
- 🔄 Advanced filtering
- 🔄 Bulk operations
- 🔄 Activity logs
- 🔄 Multi-language admin

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

Copyright © 2026 Wonderful Toba. All rights reserved.

## 📞 Support

Untuk bantuan teknis, hubungi:
- Email: support@wonderfultoba.com
- WhatsApp: +62 xxx-xxxx-xxxx

---

**Made with ❤️ by Wonderful Toba Team**
