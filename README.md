# 🌴 Wonderful Toba - Tour & Outbound Website

Website tour dan outbound profesional untuk Wonderful Toba, Sumatera Utara.

## 🚀 Quick Start

### Development

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

### cPanel Deployment

Lihat panduan lengkap di [DEPLOYMENT_GUIDE_CPANEL.md](DEPLOYMENT_GUIDE_CPANEL.md)

**Quick steps:**

1. Setup database di cPanel
2. Clone repo ke `nodeapps/wonderfultoba`
3. Setup environment variables
4. Run `npm run deploy`
5. Start Node.js app

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
