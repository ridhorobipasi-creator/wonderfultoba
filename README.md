# 🌴 Wonderful Toba - Tour & Outbound Website

Website tour dan outbound profesional untuk Wonderful Toba, Sumatera Utara.

## 🚀 Deploy ke cPanel (OTOMATIS)

**👉 MULAI DI SINI:** Baca file **`🚀_MULAI_DARI_SINI.md`**

### 2 Metode Auto-Deploy:

1. **FULL OTOMATIS** (GitHub Actions + SSH)
   - Push → Deploy otomatis dalam 2-3 menit
   - Baca: `SETUP_AUTO_DEPLOY.md`

2. **SEMI-OTOMATIS** (cPanel Git + Cron)
   - Push → Deploy otomatis dalam 5-10 menit
   - Tidak perlu SSH
   - Baca: `DEPLOY_TANPA_SSH.md`

**Pilih metode:** Baca `PILIH_METODE_DEPLOY.md`

---

## 💻 Development (Lokal)

```bash
# Install dependencies
npm install

# Setup database
npm run db:setup

# Run development server
npm run dev
```

Buka [http://localhost:3000](http://localhost:3000)

### Login Admin

- **Email**: `admin@wonderfultoba.com`
- **Password**: `password123`

## 📦 Tech Stack

- **Framework**: Next.js 16 (App Router)
- **Database**: MySQL + Prisma ORM
- **Auth**: JWT (jose)
- **Styling**: Tailwind CSS 4
- **UI**: Custom components + Lucide icons
- **Forms**: React Hook Form + Zod

## 🏗️ Project Structure

```
wonderfultoba/
├── src/
│   ├── app/              # Next.js App Router
│   │   ├── admin/        # Admin panel
│   │   ├── api/          # API routes
│   │   ├── login/        # Login page
│   │   └── ...
│   ├── components/       # React components
│   ├── lib/              # Utilities & helpers
│   └── types/            # TypeScript types
├── prisma/
│   ├── schema.prisma     # Database schema
│   └── seed.ts           # Seed data
├── public/               # Static assets
└── docs/                 # Documentation
```

## 📚 Documentation

- **[Deployment Guide](DEPLOYMENT_GUIDE_CPANEL.md)** - Deploy ke cPanel
- **[Admin Guide](docs/PANDUAN_ADMIN_CMS_LENGKAP.md)** - Panduan admin panel
- **[Database Setup](docs/DATABASE_SETUP.md)** - Setup database

## 🔧 Available Scripts

```bash
npm run dev          # Development server
npm run build        # Build for production
npm run start        # Start production server
npm run db:setup     # Setup database + seed
npm run db:reset     # Reset database
npm run prisma:studio # Open Prisma Studio
```

## 🌐 Deployment

### Auto-Deploy ke cPanel

**📖 Panduan Lengkap:**
1. **Mulai:** `🚀_MULAI_DARI_SINI.md`
2. **Pilih Metode:** `PILIH_METODE_DEPLOY.md`
3. **Setup SSH:** `SETUP_AUTO_DEPLOY.md` (Metode 1)
4. **Setup Tanpa SSH:** `DEPLOY_TANPA_SSH.md` (Metode 2)

**Quick Deploy:**
```bash
# Push ke GitHub
git push origin main

# Otomatis deploy ke cPanel!
# - Metode 1 (SSH): 2-3 menit
# - Metode 2 (Cron): 5-10 menit
```

### Manual Deployment

Lihat: `DEPLOYMENT_GUIDE_CPANEL.md`

## 🔐 Environment Variables

Copy `.env.example` ke `.env` dan sesuaikan:

```env
DATABASE_URL="mysql://user:password@localhost:3306/database"
JWT_SECRET="your-secret-key"
NEXT_PUBLIC_SITE_URL="https://wonderfultoba.com"
```

## 📞 Contact

- **WhatsApp**: +62 813-2388-8207
- **Email Tour**: tour@wonderfultoba.com
- **Email Outbound**: outbound@wonderfultoba.com
- **Website**: https://wonderfultoba.com

## 📄 License

Private - © 2026 Wonderful Toba
