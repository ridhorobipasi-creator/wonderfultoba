# 🏝️ Wonderful Toba - Tourism Website

Website pariwisata untuk Danau Toba yang menampilkan destinasi wisata, kuliner, penginapan, dan informasi lengkap tentang Danau Toba.

**Live Website:** https://www.wonderfultoba.com ✅

---

## 🚀 Tech Stack

- **Framework:** Next.js 16 (App Router + Pages Router)
- **Language:** TypeScript
- **Styling:** Tailwind CSS 4
- **Database:** PostgreSQL + Prisma ORM
- **Authentication:** JWT (Jose)
- **Deployment:** cPanel (Node.js App)

---

## 📁 Project Structure

```
wonderfultoba/
├── src/
│   ├── app/              # Next.js 13+ App Router
│   ├── pages/            # Next.js Pages Router (hybrid)
│   ├── components/       # React components
│   ├── lib/              # Utilities & helpers
│   ├── hooks/            # Custom React hooks
│   └── store/            # State management (Zustand)
├── public/               # Static assets
├── prisma/               # Database schema & migrations
├── docs/                 # Documentation
│   ├── deployment/       # Deployment guides
│   ├── setup/            # Setup guides
│   └── ...
├── scripts/              # Build & deployment scripts
├── server.js             # Production server
└── package.json
```

---

## 🛠️ Development

### Prerequisites

- Node.js ≥20.9.0
- PostgreSQL database
- npm or yarn

### Setup Local

```bash
# Clone repository
git clone <repository-url>
cd wonderfultoba

# Install dependencies
npm install

# Setup database
cp .env.example .env
# Edit .env dengan database credentials

# Generate Prisma client
npx prisma generate

# Push database schema
npx prisma db push

# Seed database (optional)
npm run seed

# Run development server
npm run dev
```

Open http://localhost:3000

---

## 📦 Build & Deployment

### Build Production

```bash
npm run build
```

### Deployment ke cPanel

**Panduan lengkap:** [docs/deployment/README.md](./docs/deployment/README.md)

**Quick steps:**

1. Build di local:
   ```bash
   npm run build
   ```

2. Zip file untuk upload:
   ```powershell
   # Windows
   .\scripts\zip-for-upload.ps1
   
   # Linux/Mac
   ./scripts/build-and-zip.sh
   ```

3. Upload ke cPanel File Manager
4. Extract di `/home/medp7341/nodeapps/wonderfultoba/`
5. Restart app di Setup Node.js App
6. Test website

---

## 📚 Documentation

### Deployment
- [Deployment Guide](./docs/deployment/README.md) - Panduan deployment lengkap
- [Build Windows](./docs/deployment/BUILD_WINDOWS.md) - Build di Windows
- [Troubleshooting](./docs/deployment/FIX_IT_WORKS_PROBLEM.md) - Fix common errors

### Setup
- [Database Setup](./docs/setup/DATABASE_SETUP_PRODUCTION.md) - Setup database production
- [Auto Deploy](./docs/setup/SETUP_AUTO_DEPLOY.md) - Setup auto deployment

### Features
- [Admin Panel Guide](./docs/ADMIN_PANEL_GUIDE.md) - Panduan admin panel
- [CMS Homepage](./docs/CMS_HOMEPAGE_GUIDE.md) - Manage homepage content
- [Media Library](./docs/MEDIA_LIBRARY_GUIDE.md) - Upload & manage media

**Index lengkap:** [docs/INDEX.md](./docs/INDEX.md)

---

## 🎯 Features

### Public Features
- 🏝️ Destinasi wisata dengan detail lengkap
- 🍽️ Kuliner khas Danau Toba
- 🏨 Penginapan & hotel
- 📸 Galeri foto & video
- 🗺️ Peta interaktif
- 📱 Responsive design (mobile-friendly)

### Admin Features
- 📝 CMS untuk manage konten
- 🖼️ Media library dengan upload gambar
- 👥 User management
- 📊 Dashboard analytics
- 🎨 Homepage slider management
- ✏️ Rich text editor (TipTap)

---

## 🔧 Scripts

```bash
# Development
npm run dev              # Start dev server
npm run build            # Build production
npm start                # Start production server

# Database
npm run prisma:generate  # Generate Prisma client
npm run prisma:push      # Push schema to database
npm run prisma:studio    # Open Prisma Studio
npm run seed             # Seed database
npm run db:reset         # Reset & seed database

# Deployment
npm run deploy           # Install, build, push DB
```

---

## 🌐 Environment Variables

```env
# Database
DATABASE_URL=postgresql://user:pass@host:5432/dbname

# App
NODE_ENV=production
NEXT_PUBLIC_API_URL=https://www.wonderfultoba.com

# JWT
JWT_SECRET=your-secret-key
```

**Template:** `.env.example`

---

## 📝 License

Private project - All rights reserved

---

## 👨‍💻 Developer

Developed by Ridho Pasi

---

## 🆘 Support

Kalau ada masalah:
1. Cek [Troubleshooting Guide](./docs/deployment/FIX_IT_WORKS_PROBLEM.md)
2. Lihat dokumentasi di folder `docs/`
3. Hubungi developer

---

## 🎉 Status

✅ **LIVE & RUNNING** - https://www.wonderfultoba.com

Last updated: April 2026
