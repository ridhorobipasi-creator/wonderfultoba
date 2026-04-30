# 🚀 Quick Start - Wonderful Toba

## Cara Menjalankan Aplikasi (100% Laravel, Tanpa Node.js)

### 1️⃣ Verifikasi Sistem

```bash
php verify.php
```

Pastikan semua checks ✅ passed.

### 2️⃣ Jalankan Server

**Windows:**
```bash
start.bat
```

**Linux/Mac:**
```bash
chmod +x start.sh
./start.sh
```

**Manual:**
```bash
php artisan serve
```

### 3️⃣ Akses Aplikasi

Buka browser: **http://127.0.0.1:8000**

---

## 📍 Halaman yang Tersedia

| URL | Deskripsi |
|-----|-----------|
| `/` | Homepage |
| `/tour` | Tour & Travel |
| `/tour/packages` | Daftar Paket Tour |
| `/outbound` | Corporate Outbound |
| `/outbound/packages` | Daftar Paket Outbound |
| `/cars` | Car Rental |
| `/about` | Tentang Kami |

---

## 🔌 API Endpoints

| Endpoint | Method | Deskripsi |
|----------|--------|-----------|
| `/api/packages` | GET | List semua paket |
| `/api/cars` | GET | List semua mobil |
| `/api/blogs` | GET | List semua blog |
| `/api/cities` | GET | List semua kota |
| `/api/gallery` | GET | List galeri foto |
| `/api/stats` | GET | Statistik |

**Test API:**
```bash
curl http://127.0.0.1:8000/api/packages
```

---

## ✅ Sudah Termasuk

- ✅ **Database SQLite** dengan sample data
- ✅ **Vite Assets** sudah di-build
- ✅ **13 Models** dengan JSON auto-casting
- ✅ **12 Tests** passing (18 assertions)
- ✅ **Code Style** PSR-12 compliant
- ✅ **API Endpoints** siap pakai
- ✅ **Blade Views** dengan Alpine.js
- ✅ **PDF Generation** untuk itinerary

---

## 🛠️ Commands Berguna

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run tests
php artisan test

# Fresh database
php artisan migrate:fresh --seed

# Fix code style
./vendor/bin/pint

# Verify system
php verify.php
```

---

## 📦 Tidak Perlu Node.js!

Aplikasi ini **100% berjalan dengan PHP** saja:
- ❌ Tidak perlu `npm install`
- ❌ Tidak perlu `npm run dev`
- ❌ Tidak perlu Next.js
- ✅ Cukup `php artisan serve`

Assets sudah di-build dan siap pakai!

---

## 🎯 Production Ready

Aplikasi sudah siap untuk:
- ✅ Development
- ✅ Testing
- ✅ Staging
- ⏳ Production (tinggal deploy)

---

## 📞 Troubleshooting

**Server tidak jalan?**
```bash
php verify.php
```

**Database kosong?**
```bash
php artisan migrate:fresh --seed
```

**Error 500?**
```bash
php artisan config:clear
php artisan cache:clear
```

**Permission error?**
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 🎉 Selamat!

Aplikasi Wonderful Toba siap digunakan!

**Akses:** http://127.0.0.1:8000
