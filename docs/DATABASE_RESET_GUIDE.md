# Panduan Reset Database - Wonderful Toba

## Overview

Dokumen ini menjelaskan cara reset database untuk development dan production. Database baru menggunakan nama `wonderfultoba_dev` untuk development dan `wonderfultoba_prod` untuk production.

**Database lama (`wonw2577_tobadb`) tidak digunakan lagi.**

---

## 🔧 Development Setup

### Metode 1: Menggunakan Script Otomatis (Recommended)

#### Windows (PowerShell)

```powershell
# Setup awal (pertama kali)
.\scripts\setup-dev.ps1

# Reset database (hapus semua data dan seed ulang)
.\scripts\reset-database.ps1
```

#### Linux/Mac (Bash)

```bash
# Setup awal (pertama kali)
chmod +x scripts/reset-database.sh
./scripts/reset-database.sh
```

### Metode 2: Manual dengan NPM Commands

```bash
# 1. Install dependencies
npm install

# 2. Generate Prisma Client
npm run prisma:generate

# 3. Push schema ke database (buat tabel)
npm run prisma:push

# 4. Seed data awal
npm run prisma:seed

# 5. Build aplikasi
npm run build

# 6. Jalankan development server
npm run dev
```

### Metode 3: Reset Database Cepat

```bash
# Reset database (drop semua tabel dan seed ulang)
npm run db:reset

# Atau setup lengkap dari awal
npm run db:setup
```

---

## 🚀 Production Setup (cPanel)

### Persiapan

1. **Buat Database di cPanel**
   - Login ke cPanel
   - Buka **MySQL® Databases**
   - Buat database baru: `wonderfultoba_prod`
   - Buat user baru dengan password kuat
   - Assign user ke database dengan **ALL PRIVILEGES**

2. **Update .env.production**

```env
DATABASE_URL="mysql://cpanel_user:password@localhost:3306/cpanel_wonderfultoba_prod"
JWT_SECRET="generate-random-string-here"
NEXT_PUBLIC_SITE_URL="https://yourdomain.com"
```

### Deploy ke cPanel

#### Metode 1: Script Otomatis

```bash
# Upload semua file ke server via FTP/Git
# SSH ke server, lalu jalankan:

chmod +x scripts/setup-production.sh
./scripts/setup-production.sh
```

#### Metode 2: Manual

```bash
# 1. Install dependencies
npm install --production

# 2. Generate Prisma Client
NODE_ENV=production npx prisma generate

# 3. Push schema ke database
NODE_ENV=production npx prisma db push

# 4. Seed data awal
NODE_ENV=production npx prisma db seed

# 5. Build aplikasi
npm run build

# 6. Start aplikasi
npm start
```

### Setup Node.js App di cPanel

1. Buka **Setup Node.js App**
2. Create Application:
   - Node.js version: 18.x atau lebih baru
   - Application mode: **Production**
   - Application root: `public_html` (atau folder Anda)
   - Application URL: domain Anda
   - Application startup file: `server.js`

3. Environment Variables (copy dari .env.production)

4. Start aplikasi

### Menggunakan PM2 (Recommended)

```bash
# Install PM2
npm install -g pm2

# Start aplikasi
pm2 start npm --name "wonderfultoba" -- start

# Save PM2 configuration
pm2 save

# Setup auto-start on reboot
pm2 startup
```

---

## 📊 Database Schema

### Models Utama

- **User** - Admin dan user management
- **Package** - Paket tour dan outbound
- **City** - Destinasi wisata
- **Car** - Rental mobil
- **Booking** - Pemesanan
- **Blog** - Artikel blog
- **Setting** - CMS settings (hero, slider, testimonials)
- **OutboundService** - Layanan outbound
- **OutboundVideo** - Video gallery
- **OutboundLocation** - Lokasi outbound
- **Client** - Logo klien
- **GalleryImage** - Gallery foto
- **PackageTier** - Tier paket (Basic, Standard, Premium)

### Default Admin Accounts

Setelah seed, tersedia 3 akun admin:

1. **Admin Umum** (Full Access)
   - Email: `admin@wonderfultoba.com`
   - Password: `password123`
   - Role: `admin_umum`

2. **Admin Tour**
   - Email: `tour@wonderfultoba.com`
   - Password: `password123`
   - Role: `admin_tour`

3. **Admin Outbound**
   - Email: `outbound@wonderfultoba.com`
   - Password: `password123`
   - Role: `admin_outbound`

**⚠️ PENTING: Ganti semua password setelah login pertama!**

---

## 🔄 Update & Maintenance

### Update Aplikasi (Production)

```bash
# Quick deploy script
chmod +x scripts/quick-deploy.sh
./scripts/quick-deploy.sh

# Restart aplikasi
pm2 restart wonderfultoba
```

### Backup Database

```bash
# Backup database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Restore database
mysql -u username -p database_name < backup_20260424.sql
```

### Prisma Studio (Database GUI)

```bash
# Buka Prisma Studio untuk manage data via GUI
npm run prisma:studio
```

Akses di: http://localhost:5555

---

## 🐛 Troubleshooting

### Error: "Can't reach database server"

**Solusi:**
1. Cek kredensial di `.env` atau `.env.production`
2. Pastikan MySQL service running
3. Cek user database memiliki privileges yang cukup

```bash
# Test koneksi database
npx prisma db pull
```

### Error: "Prisma Client not generated"

**Solusi:**
```bash
rm -rf node_modules/.prisma
rm -rf node_modules/@prisma/client
npm run prisma:generate
```

### Error: "Module not found"

**Solusi:**
```bash
rm -rf node_modules package-lock.json
npm install
npm run prisma:generate
```

### Database schema out of sync

**Solusi:**
```bash
# Push schema terbaru
npm run prisma:push

# Atau reset database (HATI-HATI: hapus semua data)
npm run db:reset
```

### Port 3000 sudah digunakan

**Solusi:**
```bash
# Windows
netstat -ano | findstr :3000
taskkill /PID <PID> /F

# Linux/Mac
lsof -ti:3000 | xargs kill -9
```

---

## 📝 NPM Scripts Reference

| Command | Deskripsi |
|---------|-----------|
| `npm run dev` | Start development server |
| `npm run build` | Build production |
| `npm start` | Start production server |
| `npm run prisma:generate` | Generate Prisma Client |
| `npm run prisma:push` | Push schema ke database |
| `npm run prisma:seed` | Seed data awal |
| `npm run prisma:studio` | Buka Prisma Studio GUI |
| `npm run db:reset` | Reset database (drop & seed) |
| `npm run db:setup` | Setup database lengkap |

---

## 🔐 Security Checklist

- [ ] Ganti `JWT_SECRET` dengan string random yang kuat
- [ ] Ganti semua password admin default
- [ ] Pastikan `.env` dan `.env.production` tidak ter-commit ke Git
- [ ] Enable HTTPS/SSL di production
- [ ] Setup firewall rules
- [ ] Regular backup database
- [ ] Update dependencies secara berkala
- [ ] Monitor error logs

---

## 📞 Support

Jika ada masalah:
- Email: admin@wonderfultoba.com
- WhatsApp: +62 813-2388-8207

---

**Last Updated:** April 24, 2026
