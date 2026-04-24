# ⚡ START HERE FIRST - Setup Database Baru

## 🎯 Database Baru Sudah Siap!

Database lama (`wonw2577_tobadb`) **TIDAK DIGUNAKAN LAGI**.

Database baru:
- **Development:** `wonderfultoba_dev`
- **Production:** `wonderfultoba_prod`

---

## 📋 Yang Sudah Dikerjakan

✅ File `.env` sudah diupdate dengan database baru  
✅ File `.env.production` sudah dibuat untuk cPanel  
✅ Script setup otomatis sudah dibuat  
✅ Dokumentasi lengkap sudah tersedia  
✅ Prisma Client sudah di-generate  

---

## 🚀 Langkah Selanjutnya

### Untuk Development (Local)

#### 1. Pastikan MySQL Running

**XAMPP:**
- Buka XAMPP Control Panel
- Start **Apache** dan **MySQL**

**Atau MySQL standalone:**
- Pastikan MySQL service running di port 3306

#### 2. Buat Database Baru

Buka phpMyAdmin (http://localhost/phpmyadmin) atau MySQL CLI:

```sql
CREATE DATABASE wonderfultoba_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 3. Setup Database Otomatis

**Windows (PowerShell):**
```powershell
.\scripts\setup-dev.ps1
```

**Atau manual:**
```bash
npm run db:setup
```

Ini akan:
- Generate Prisma Client ✓ (sudah selesai)
- Push schema ke database (buat tabel)
- Seed data awal (admin, packages, cities, dll)

#### 4. Jalankan Development Server

```bash
npm run dev
```

Buka: http://localhost:3000

#### 5. Login Admin

- URL: http://localhost:3000/admin
- Email: `admin@wonderfultoba.com`
- Password: `password123`

---

### Untuk Production (cPanel)

Ikuti panduan lengkap di:
- **[DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)** - Checklist lengkap
- **[DATABASE_SETUP_PRODUCTION.md](DATABASE_SETUP_PRODUCTION.md)** - Setup database production

**Ringkasan:**

1. **Buat database MySQL di cPanel**
2. **Update `.env.production`** dengan kredensial database
3. **Upload files** ke server
4. **Jalankan setup:**
   ```bash
   chmod +x scripts/setup-production.sh
   ./scripts/setup-production.sh
   ```
5. **Start dengan PM2:**
   ```bash
   pm2 start ecosystem.config.js
   ```

---

## 📚 Dokumentasi Lengkap

| File | Deskripsi |
|------|-----------|
| **[QUICK_START.md](QUICK_START.md)** | Quick start guide |
| **[README.md](README.md)** | Project overview |
| **[DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)** | Deployment ke cPanel |
| **[DATABASE_SETUP_PRODUCTION.md](DATABASE_SETUP_PRODUCTION.md)** | Setup database production |
| **[docs/DATABASE_RESET_GUIDE.md](docs/DATABASE_RESET_GUIDE.md)** | Reset & maintenance database |

---

## 🔧 NPM Commands Penting

```bash
# Setup database lengkap (generate + push + seed)
npm run db:setup

# Reset database (drop all + seed ulang)
npm run db:reset

# Generate Prisma Client
npm run prisma:generate

# Push schema ke database
npm run prisma:push

# Seed data
npm run prisma:seed

# Buka database GUI
npm run prisma:studio
```

---

## 🎯 Default Admin Accounts

Setelah seed, tersedia 3 akun admin:

**Admin Umum** (Full Access)
- Email: `admin@wonderfultoba.com`
- Password: `password123`

**Admin Tour**
- Email: `tour@wonderfultoba.com`
- Password: `password123`

**Admin Outbound**
- Email: `outbound@wonderfultoba.com`
- Password: `password123`

⚠️ **PENTING: Ganti semua password setelah login pertama!**

---

## 🐛 Troubleshooting

### Error: "Can't reach database server"

**Solusi:**
1. Pastikan MySQL service running
2. Cek XAMPP/MySQL sudah start
3. Cek database `wonderfultoba_dev` sudah dibuat
4. Cek kredensial di `.env`

### Error: "Database does not exist"

**Solusi:**
```sql
CREATE DATABASE wonderfultoba_dev;
```

### Error: "Module not found"

**Solusi:**
```bash
npm install
npm run prisma:generate
```

### Port 3000 sudah digunakan

**Solusi:**
```bash
# Windows
netstat -ano | findstr :3000
taskkill /PID <PID> /F
```

---

## 📞 Support

Jika ada masalah:
- Email: admin@wonderfultoba.com
- WhatsApp: +62 813-2388-8207

---

## ✅ Checklist Setup

- [ ] MySQL service running
- [ ] Database `wonderfultoba_dev` sudah dibuat
- [ ] Run `npm run db:setup`
- [ ] Run `npm run dev`
- [ ] Buka http://localhost:3000
- [ ] Login admin berhasil
- [ ] Ganti password admin

---

**Selamat! Database baru siap digunakan! 🎉**

Untuk production deployment ke cPanel, lanjut ke [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)
