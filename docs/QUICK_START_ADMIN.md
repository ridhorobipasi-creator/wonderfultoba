# 🚀 Quick Start - Panel Admin Wonderful Toba

## ⚡ 5 Menit Setup

### 1. Akses Panel Admin
```
URL: http://localhost:3000/admin
```

### 2. Login Credentials
```
Email: admin@wonderfultoba.com
Password: admin123
```

⚠️ **PENTING:** Ganti password setelah login pertama!

---

## 📊 Fitur Utama

### Dashboard
- Lihat statistik real-time
- Monitor tren pendapatan
- Cek reservasi terbaru
- Export laporan

### Manajemen Reservasi
```
Menu: Daftar Pesanan
```
- Filter: All / Pending / Confirmed
- Search: Cari nama pelanggan
- Action: Klik "Kelola" untuk detail

### CMS Landing Page
```
Menu: Pengaturan Beranda (Tour/Outbound)
```
- Edit Hero section
- Ubah judul & subtitle
- Upload gambar
- Klik "Simpan Perubahan"

### Manajemen Paket
```
Menu: Paket Wisata
```
- Tambah paket baru
- Edit paket existing
- Set harga & durasi
- Upload multiple images

---

## 🎯 Common Tasks

### Mengubah Hero Homepage
1. Klik **Pengaturan Beranda**
2. Tab **Hero Section**
3. Edit judul & subtitle
4. Masukkan URL gambar
5. Klik **Simpan Perubahan**

### Mengelola Reservasi
1. Klik **Daftar Pesanan**
2. Filter berdasarkan status
3. Klik **Kelola** pada reservasi
4. Update status jika perlu

### Export Laporan
1. Buka **Dashboard**
2. Klik **Ekspor Laporan**
3. File JSON akan terdownload

---

## 🔧 Troubleshooting

### Dashboard tidak muncul data
```bash
# Check database connection
npx prisma studio

# Restart server
npm run dev
```

### Error saat save CMS
```bash
# Check API endpoint
curl http://localhost:3000/api/settings/landing-tour

# Check database
npx prisma studio
```

### Chart tidak muncul
- Pastikan ada data booking confirmed
- Refresh halaman (F5)
- Clear browser cache

---

## 📱 Keyboard Shortcuts

```
Ctrl + R    : Refresh data
Ctrl + S    : Save (di CMS editor)
Esc         : Close modal
```

---

## 🎨 Tips & Tricks

### Upload Gambar
- Format: JPG, PNG
- Max size: 5MB
- Recommended: 1920x1080px
- Path: `/assets/images/...`

### Best Practices
- ✅ Backup data sebelum edit
- ✅ Test di staging dulu
- ✅ Gunakan gambar optimized
- ✅ Isi semua field required

---

## 📞 Butuh Bantuan?

### Dokumentasi Lengkap
```
File: ADMIN_PANEL_GUIDE.md
```

### Support
- Email: support@wonderfultoba.com
- WhatsApp: +62 xxx-xxxx-xxxx

---

**Happy Managing! 🎉**
