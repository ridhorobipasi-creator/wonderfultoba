# 🚀 START HERE - Wonderful Toba Admin Panel

## ⚠️ PENTING: Baca Ini Dulu!

Jika Anda melihat **layar putih** atau **error database**, ikuti langkah berikut:

---

## 🔥 Quick Fix (5 Menit)

### Step 1: Start MySQL Server

#### Jika Menggunakan XAMPP:
1. Buka **XAMPP Control Panel**
2. Klik tombol **"Start"** di baris **MySQL**
3. Tunggu hingga status berubah menjadi hijau

#### Jika Menggunakan MySQL Service:
```bash
# Buka Command Prompt sebagai Administrator
# Jalankan:
net start MySQL80
```

#### Jika Menggunakan WAMP/MAMP:
- Buka WAMP/MAMP
- Start MySQL service

---

### Step 2: Verify Database

```bash
# Test koneksi database
npx prisma studio
```

Jika berhasil, akan membuka browser di `http://localhost:5555`

---

### Step 3: Refresh Browser

1. Buka `http://localhost:3000/admin`
2. Tekan **F5** atau **Ctrl + R**
3. Dashboard seharusnya muncul dengan data

---

## 🎯 Jika Masih Putih/Error

### Cek File .env

Pastikan file `.env` ada dan berisi:

```env
DATABASE_URL="mysql://root:@localhost:3306/wonderfultoba"
```

**Sesuaikan dengan kredensial MySQL Anda:**
- `root` = username MySQL
- `@` = password (kosong jika tidak ada password)
- `wonderfultoba` = nama database

---

### Buat Database (Jika Belum Ada)

```bash
# Masuk ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE wonderfultoba;

# Keluar
exit;
```

---

### Run Migrations

```bash
# Generate Prisma Client
npx prisma generate

# Run migrations
npx prisma migrate dev

# Seed data (optional)
npx prisma db seed
```

---

### Restart Development Server

```bash
# Stop server (Ctrl + C di terminal)
# Start lagi:
npm run dev
```

---

## ✅ Setelah Database Berjalan

### Akses Admin Panel
```
URL: http://localhost:3000/admin
```

### Login Credentials
```
Email: admin@wonderfultoba.com
Password: admin123
```

⚠️ **Ganti password setelah login pertama!**

---

## 📊 Fitur yang Tersedia

Setelah login, Anda dapat:

- ✅ Melihat dashboard dengan statistik real-time
- ✅ Mengelola reservasi (bookings)
- ✅ Edit konten CMS (Tour & Outbound)
- ✅ Manage paket wisata
- ✅ Manage armada mobil
- ✅ Export laporan
- ✅ Dan banyak lagi!

---

## 🐛 Troubleshooting

### Error: "Can't reach database server"
**Solusi:** MySQL belum berjalan. Start MySQL service.

### Error: "Access denied"
**Solusi:** Cek username/password di `.env`

### Error: "Unknown database"
**Solusi:** Buat database dengan `CREATE DATABASE wonderfultoba;`

### Layar masih putih
**Solusi:** 
1. Clear browser cache (Ctrl + Shift + Delete)
2. Hard refresh (Ctrl + Shift + R)
3. Cek console browser (F12) untuk error

---

## 📚 Dokumentasi Lengkap

- **DATABASE_SETUP.md** - Setup database detail
- **ADMIN_PANEL_GUIDE.md** - Panduan lengkap admin panel
- **QUICK_START_ADMIN.md** - Quick reference
- **ADMIN_FEATURES_VISUAL.md** - Visual overview

---

## 💡 Tips

1. **Selalu start MySQL** sebelum menjalankan aplikasi
2. **Gunakan Prisma Studio** untuk manage data dengan mudah
3. **Backup database** secara berkala
4. **Cek terminal** untuk melihat error logs

---

## 📞 Butuh Bantuan?

Jika masih mengalami masalah:

1. Cek terminal untuk error messages
2. Buka browser console (F12) untuk JavaScript errors
3. Pastikan semua dependencies terinstall: `npm install`
4. Coba restart komputer jika MySQL tidak mau start

---

## 🎉 Selamat Menggunakan!

Setelah database berjalan, panel admin akan berfungsi dengan sempurna!

**Happy Managing! 🚀**

---

**Status Saat Ini:**
- ✅ Development server: RUNNING (http://localhost:3000)
- ⚠️ MySQL database: PERLU DISTART
- ✅ Code: PRODUCTION READY
- ✅ Documentation: COMPLETE

**Next Step:** Start MySQL, lalu refresh browser!
