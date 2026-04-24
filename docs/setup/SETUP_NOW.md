# 🚀 Setup Sekarang - Wonderful Toba

## ⚠️ MySQL Belum Running

Saya mendeteksi MySQL belum terinstall atau belum running di sistem Anda.

---

## 📋 Langkah Setup Lengkap

### Step 1: Install & Start MySQL

#### Opsi A: Menggunakan XAMPP (Recommended untuk Windows)

1. **Download XAMPP:**
   - Kunjungi: https://www.apachefriends.org/download.html
   - Download versi terbaru untuk Windows
   - Install XAMPP

2. **Start MySQL:**
   - Buka XAMPP Control Panel
   - Klik **Start** pada Apache
   - Klik **Start** pada MySQL
   - Tunggu hingga status berubah menjadi hijau

3. **Verifikasi:**
   - Buka browser: http://localhost/phpmyadmin
   - Jika terbuka, MySQL sudah running ✅

#### Opsi B: MySQL Standalone

1. **Download MySQL:**
   - Kunjungi: https://dev.mysql.com/downloads/installer/
   - Download MySQL Installer untuk Windows
   - Install dengan default settings

2. **Start MySQL Service:**
   ```powershell
   # Buka PowerShell sebagai Administrator
   Start-Service MySQL80
   ```

3. **Verifikasi:**
   ```powershell
   Get-Service MySQL80
   ```

---

### Step 2: Buat Database

#### Via phpMyAdmin (XAMPP)

1. Buka: http://localhost/phpmyadmin
2. Klik tab **"Databases"**
3. Di "Create database":
   - Database name: `wonderfultoba_dev`
   - Collation: `utf8mb4_unicode_ci`
4. Klik **"Create"**

#### Via MySQL Command Line

```sql
CREATE DATABASE wonderfultoba_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### Step 3: Setup Database dengan NPM

Setelah database dibuat, jalankan:

```bash
npm run db:setup
```

Ini akan:
- ✓ Generate Prisma Client (sudah selesai)
- Push schema ke database (buat tabel)
- Seed data awal (admin, packages, cities, dll)

**Output yang diharapkan:**
```
✔ Generated Prisma Client
✔ Database schema pushed
✔ Seeded 3 admin users
✔ Seeded 3 cities
✔ Seeded 8 packages
✔ Seeded CMS settings
```

---

### Step 4: Jalankan Development Server

```bash
npm run dev
```

**Output yang diharapkan:**
```
▲ Next.js 16.2.4
- Local:        http://localhost:3000
- Ready in 2.5s
```

---

### Step 5: Buka Browser

1. **Homepage:** http://localhost:3000
2. **Admin Panel:** http://localhost:3000/admin

---

### Step 6: Login Admin

**Kredensial Default:**
- Email: `admin@wonderfultoba.com`
- Password: `password123`

⚠️ **PENTING: Ganti password setelah login pertama!**

---

## 🔧 Troubleshooting

### Error: "Can't reach database server"

**Penyebab:** MySQL belum running

**Solusi:**
1. Buka XAMPP Control Panel
2. Start MySQL
3. Coba lagi: `npm run db:setup`

### Error: "Database does not exist"

**Penyebab:** Database belum dibuat

**Solusi:**
1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Buat database: `wonderfultoba_dev`
3. Coba lagi: `npm run db:setup`

### Error: "Access denied for user 'root'"

**Penyebab:** Password MySQL salah

**Solusi:**
1. Buka file `.env`
2. Update `DATABASE_URL`:
   ```env
   # Jika MySQL pakai password
   DATABASE_URL="mysql://root:PASSWORD@127.0.0.1:3306/wonderfultoba_dev"
   
   # Jika MySQL tanpa password (default XAMPP)
   DATABASE_URL="mysql://root:@127.0.0.1:3306/wonderfultoba_dev"
   ```

### Port 3000 sudah digunakan

**Solusi:**
```powershell
# Cari process yang menggunakan port 3000
netstat -ano | findstr :3000

# Kill process (ganti <PID> dengan nomor yang muncul)
taskkill /PID <PID> /F
```

### Error: "Module not found"

**Solusi:**
```bash
npm install
npm run prisma:generate
```

---

## 📊 Verifikasi Setup

Setelah semua langkah selesai, verifikasi:

- [ ] MySQL service running (XAMPP hijau)
- [ ] Database `wonderfultoba_dev` exists
- [ ] `npm run db:setup` berhasil tanpa error
- [ ] `npm run dev` running di port 3000
- [ ] http://localhost:3000 terbuka
- [ ] http://localhost:3000/admin terbuka
- [ ] Login admin berhasil

---

## 🎯 Quick Commands

```bash
# Cek status database
npm run prisma:studio

# Reset database (jika ada masalah)
npm run db:reset

# Generate Prisma Client
npm run prisma:generate

# Push schema
npm run prisma:push

# Seed data
npm run prisma:seed
```

---

## 📚 Dokumentasi Lengkap

Setelah setup berhasil, baca dokumentasi lengkap:

- **[START_HERE_FIRST.md](START_HERE_FIRST.md)** - Quick start guide
- **[QUICK_START.md](QUICK_START.md)** - Quick reference
- **[README.md](README.md)** - Project overview
- **[docs/DATABASE_RESET_GUIDE.md](docs/DATABASE_RESET_GUIDE.md)** - Database guide

---

## 📞 Butuh Bantuan?

- Email: admin@wonderfultoba.com
- WhatsApp: +62 813-2388-8207

---

## ✅ Checklist Setup

```
[ ] Step 1: Install & Start MySQL (XAMPP/MySQL)
[ ] Step 2: Buat database wonderfultoba_dev
[ ] Step 3: Run npm run db:setup
[ ] Step 4: Run npm run dev
[ ] Step 5: Buka http://localhost:3000
[ ] Step 6: Login admin berhasil
[ ] Step 7: Ganti password admin
```

---

**Setelah checklist selesai, Anda siap untuk development! 🎉**

Untuk production deployment, lanjut ke: **[DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)**

---

**Last Updated:** April 24, 2026  
**Status:** Ready for Setup
