# Informasi Proyek: Wonderful Toba

## Deskripsi Singkat
Wonderful Toba adalah platform digital pariwisata yang fokus pada penyediaan layanan tour dan outbound di kawasan Danau Toba dan Sumatera Utara. Website ini dirancang untuk memberikan pengalaman visual yang premium serta fungsionalitas pemesanan yang mudah bagi klien korporat maupun perorangan.

---

## Tech Stack
- **Framework**: Laravel 11 (PHP 8.2+)
- **Styling**: Tailwind CSS 3
- **Frontend Logic**: Alpine.js
- **Database**: 
  - Pengembangan: SQLite
  - Produksi (cPanel): MySQL
- **Icons**: FontAwesome 6 (Pro/Free)
- **Image Processing**: GD Extension (Konversi otomatis ke WebP)

---

## Fitur Utama

### 1. Modul Outbound (Update Terbaru)
- **Landing Page Dinamis**: Menampilkan keunggulan layanan dengan animasi premium.
- **Lokasi Kegiatan**: Daftar destinasi outbound pilihan dengan grid foto.
- **Client Portfolio**: Logo cloud perusahaan yang pernah menggunakan jasa Wonderful Toba.
- **Gallery Momen**: Galeri dokumentasi kegiatan menggunakan layout masonry.
- **Form Permintaan Penawaran**: Form khusus untuk korporat yang terintegrasi langsung dengan notifikasi WhatsApp Admin.

### 2. Modul Tour & Travel
- **Manajemen Paket**: Pengaturan destinasi, durasi, dan harga paket wisata.
- **Sistem Booking**: Form pemesanan yang responsif dan mudah digunakan.

### 3. Media Library & Optimasi
- **WebP Converter**: Unggahan gambar otomatis dikonversi ke format WebP untuk kecepatan loading.
- **Auto-Thumbnail**: Pembuatan thumbnail otomatis untuk memperingan beban server.
- **Syncable Media**: Sinkronisasi aset media antara database dan penyimpanan fisik.

### 4. Admin Dashboard
- **CMS Settings**: Pengaturan teks, gambar hero, dan data statistik tanpa menyentuh kode.
- **Booking Management**: Pantau semua reservasi yang masuk dalam satu panel.
- **Blog & Berita**: Sistem manajemen artikel untuk SEO.

---

## Konfigurasi Server (cPanel Shared Hosting)

Proyek ini telah dikonfigurasi khusus agar bisa berjalan di hosting cPanel tanpa akses SSH yang mendalam:

1. **Struktur Root**: Menggunakan `.htaccess` di root folder untuk mengarahkan trafik ke `/public` secara aman.
2. **Auto-Symlink**: `AppServiceProvider` dilengkapi kode untuk mendeteksi dan membuat symlink `storage` secara otomatis saat website diakses.
3. **Optimasi Aset**: Aset Vite (CSS/JS) sudah di-*build* dan disertakan dalam repositori untuk memudahkan deployment langsung lewat Git Version Control cPanel.
4. **Environment**: File `.env` dikonfigurasi menggunakan disk `public` untuk memastikan file yang diunggah dapat diakses publik di folder `public_html`.

---

## Perintah Penting (Terminal cPanel)
- **Update Kode**: `git pull origin main`
- **Reset Perubahan Server**: `git reset --hard HEAD`
- **Bersihkan Cache**: `php artisan optimize`
- **Migrasi Database**: `php artisan migrate`

---

**Dikembangkan oleh**: Ridho Robbi Pasi (Mitralabs Studio) & Wonderful Toba Team.
**Terakhir Diperbarui**: 14 Mei 2026
