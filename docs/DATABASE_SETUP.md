# 🗄️ Database Setup - Wonderful Toba

## ⚠️ MASALAH: Database Tidak Terhubung

Error yang muncul:
```
Can't reach database server at `127.0.0.1:3306`
```

---

## 🔧 Solusi Cepat

### Opsi 1: Start MySQL Server (Recommended)

#### Windows (XAMPP)
```bash
# Buka XAMPP Control Panel
# Klik "Start" pada MySQL
```

#### Windows (MySQL Service)
```bash
# Buka Services (Win + R, ketik: services.msc)
# Cari "MySQL" atau "MySQL80"
# Klik kanan > Start
```

#### Command Line
```bash
# Start MySQL service
net start MySQL80

# Atau jika menggunakan XAMPP
cd C:\xampp
mysql_start.bat
```

---

### Opsi 2: Setup Database Baru

#### 1. Buat Database
```sql
CREATE DATABASE wonderfultoba CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 2. Update .env
```env
DATABASE_URL="mysql://root:@localhost:3306/wonderfultoba"
```

Ganti sesuai kredensial Anda:
- `root` = username MySQL
- `@` = password (kosong jika tidak ada password)
- `localhost:3306` = host dan port
- `wonderfultoba` = nama database

#### 3. Run Migrations
```bash
# Generate Prisma Client
npx prisma generate

# Run migrations
npx prisma migrate dev

# Seed database (optional)
npx prisma db seed
```

---

### Opsi 3: Gunakan SQLite untuk Development (Quick Fix)

Jika ingin cepat tanpa setup MySQL:

#### 1. Update schema.prisma
```prisma
datasource db {
  provider = "sqlite"
  url      = "file:./dev.db"
}
```

#### 2. Run migrations
```bash
npx prisma migrate dev --name init
npx prisma generate
```

#### 3. Restart server
```bash
npm run dev
```

---

## ✅ Verifikasi Koneksi

### Test Database Connection
```bash
# Buka Prisma Studio
npx prisma studio

# Jika berhasil, akan membuka http://localhost:5555
```

### Check MySQL Status
```bash
# Windows
sc query MySQL80

# Atau
netstat -an | findstr 3306
```

---

## 🚀 Setelah Database Berjalan

1. **Restart development server**
   ```bash
   # Stop server (Ctrl + C)
   npm run dev
   ```

2. **Akses admin panel**
   ```
   http://localhost:3000/admin
   ```

3. **Login dengan credentials**
   ```
   Email: admin@wonderfultoba.com
   Password: admin123
   ```

---

## 📊 Database Schema

Database memiliki 8 tabel:
- ✅ User (Authentication)
- ✅ Package (Tour & Outbound)
- ✅ City (Locations)
- ✅ Car (Rental)
- ✅ Booking (Reservations)
- ✅ Blog (Articles)
- ✅ Setting (CMS Data)

---

## 🔍 Troubleshooting

### Error: "Access denied for user"
```env
# Check username & password di .env
DATABASE_URL="mysql://USERNAME:PASSWORD@localhost:3306/wonderfultoba"
```

### Error: "Unknown database"
```bash
# Buat database dulu
mysql -u root -p
CREATE DATABASE wonderfultoba;
exit;
```

### Error: "Port 3306 already in use"
```bash
# Check apa yang menggunakan port 3306
netstat -ano | findstr :3306

# Kill process jika perlu
taskkill /PID [PID_NUMBER] /F
```

---

## 💡 Tips

1. **Gunakan XAMPP** untuk kemudahan (includes MySQL, phpMyAdmin)
2. **Backup database** sebelum migration
3. **Gunakan Prisma Studio** untuk manage data
4. **Check .env** pastikan DATABASE_URL benar

---

## 📞 Butuh Bantuan?

Jika masih error, cek:
1. MySQL service berjalan
2. Port 3306 tidak diblok firewall
3. Credentials di .env benar
4. Database sudah dibuat

---

**Setelah database berjalan, panel admin akan berfungsi normal!** ✅
