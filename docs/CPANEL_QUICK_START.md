# ⚡ cPanel Quick Start Guide

## 🎯 Langkah Cepat Deploy ke cPanel

### 1️⃣ Persiapan (Di Komputer Lokal)

```bash
# Build production
npm run build

# Test build
npm start

# Jika OK, lanjut upload
```

### 2️⃣ Upload ke cPanel

**Via FTP (FileZilla/WinSCP):**
```
1. Connect ke FTP server
2. Upload semua file ke: public_html/wonderfultoba/
3. Tunggu upload selesai (bisa 10-30 menit)
```

**File yang WAJIB diupload:**
```
✅ .next/              (hasil build)
✅ public/             (assets & storage)
✅ prisma/             (schema & migrations)
✅ src/                (source code)
✅ node_modules/       (atau install di server)
✅ server.js           (custom server)
✅ .htaccess           (Apache config)
✅ package.json
✅ package-lock.json
✅ next.config.ts
✅ .env.production     (rename dari .env.production.example)
```

### 3️⃣ Setup di cPanel

#### A. Buat Database MySQL
```
1. Login cPanel
2. MySQL Databases
3. Create Database: wonderfultoba_db
4. Create User: wonderfultoba_user
5. Set Password (simpan!)
6. Add User to Database (ALL PRIVILEGES)
```

#### B. Setup Node.js App
```
1. Cari "Setup Node.js App"
2. Create Application:
   - Node.js Version: 18.x atau 20.x
   - Application Mode: Production
   - Application Root: public_html/wonderfultoba
   - Application URL: yourdomain.com
   - Application Startup File: server.js
   - Port: 3000 (atau yang tersedia)
```

#### C. Environment Variables (di Node.js App)
```
DATABASE_URL=mysql://wonderfultoba_user:PASSWORD@localhost:3306/wonderfultoba_db
JWT_SECRET=generate-random-32-chars-here
NODE_ENV=production
NEXT_PUBLIC_API_URL=https://yourdomain.com
```

### 4️⃣ Install & Deploy (Via cPanel Terminal)

```bash
# Masuk ke folder aplikasi
cd public_html/wonderfultoba

# Install dependencies (jika belum upload node_modules)
npm install --production

# Generate Prisma Client
npx prisma generate

# Run migrations
npx prisma migrate deploy

# Seed database (optional)
npx prisma db seed

# Set permissions
chmod -R 755 .next
chmod -R 755 public
chmod 644 .env.production
```

### 5️⃣ Start Application

**Via cPanel Node.js App:**
```
1. Kembali ke "Setup Node.js App"
2. Klik aplikasi Anda
3. Klik "Start" atau "Restart"
4. Tunggu status jadi "Running"
```

**Via Terminal:**
```bash
npm start
```

### 6️⃣ Test & Verify

```
✅ Buka: https://yourdomain.com
✅ Test homepage load
✅ Test admin: https://yourdomain.com/admin
✅ Login dengan: admin@wonderfultoba.com / admin123
✅ Test semua fitur
```

---

## 🔧 Troubleshooting Cepat

### Error: "Cannot find module"
```bash
cd public_html/wonderfultoba
rm -rf node_modules
npm install --production
```

### Error: "Database connection failed"
```bash
# Check .env.production
# Pastikan DATABASE_URL benar
# Format: mysql://USER:PASS@localhost:3306/DB_NAME
```

### Error: "Port already in use"
```bash
# Kill process
pkill -f node

# Atau ganti port di Node.js App settings
```

### Error: "Permission denied"
```bash
chmod -R 755 public_html/wonderfultoba
chmod 644 .env.production
```

### App tidak start
```bash
# Check logs
tail -f logs/error.log

# Atau check di cPanel Node.js App logs
```

---

## 📊 Checklist Deployment

```
✅ Database created
✅ Database user created
✅ Files uploaded
✅ Node.js App configured
✅ Environment variables set
✅ Dependencies installed
✅ Prisma generated
✅ Migrations run
✅ Permissions set
✅ App started
✅ Domain tested
✅ Admin login tested
✅ SSL enabled (Let's Encrypt)
```

---

## 🎯 Post-Deployment

### Setup SSL (HTTPS)
```
1. cPanel > SSL/TLS Status
2. Run AutoSSL (Let's Encrypt)
3. Tunggu proses selesai
4. Test: https://yourdomain.com
```

### Setup Cron Job (Optional)
```
# Backup database daily
0 2 * * * cd /home/username/public_html/wonderfultoba && npx prisma db pull > backup.sql
```

### Monitor Logs
```bash
# Error logs
tail -f logs/error.log

# Access logs
tail -f logs/access.log
```

---

## 💡 Tips

1. **Upload node_modules?**
   - Jika koneksi lambat: Install di server
   - Jika koneksi cepat: Upload langsung

2. **Build di server atau lokal?**
   - Lokal: Lebih cepat, upload .next folder
   - Server: Lebih aman, tapi butuh waktu

3. **Backup sebelum deploy!**
   ```bash
   # Backup database
   mysqldump -u user -p database > backup.sql
   
   # Backup files
   tar -czf backup.tar.gz public_html/wonderfultoba
   ```

4. **Test di subdomain dulu**
   - Setup di: test.yourdomain.com
   - Test semua fitur
   - Baru pindah ke domain utama

---

## 📞 Butuh Bantuan?

### Hosting Provider
- Contact support jika ada masalah cPanel
- Tanyakan Node.js version yang tersedia
- Tanyakan resource limits (RAM, CPU)

### Application Issues
- Check error logs
- Enable debug mode
- Test di local dulu

---

## ⏱️ Estimasi Waktu

```
Upload files:        10-30 menit (tergantung koneksi)
Setup database:      5 menit
Setup Node.js App:   5 menit
Install deps:        5-10 menit
Run migrations:      2 menit
Testing:            10 menit

Total:              ~40-60 menit
```

---

## 🎉 Selesai!

Jika semua langkah diikuti dengan benar, aplikasi Anda seharusnya sudah running di:

**https://yourdomain.com** ✅

**Admin Panel:**
**https://yourdomain.com/admin** ✅

**Login:**
- Email: admin@wonderfultoba.com
- Password: admin123

⚠️ **PENTING:** Ganti password admin setelah login pertama!

---

**Good luck! 🚀**
