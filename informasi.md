# Informasi Proyek: Sujai Laketoba

## Deskripsi Singkat
Sujai Laketoba adalah platform digital pariwisata yang fokus pada penyediaan layanan tour travel premium di kawasan Danau Toba dan Sumatera Utara. Website ini dirancang untuk memberikan pengalaman visual yang elegan serta fungsionalitas pemesanan yang mudah bagi wisatawan mancanegara maupun domestik.

---

## Tech Stack
- **Framework**: Laravel 11 (PHP 8.2+)
- **Styling**: Tailwind CSS 3
- **Frontend Logic**: Alpine.js
- **Database**: 
  - Pengembangan: SQLite
  - Produksi (hostinger): MySQL
- **Icons**: FontAwesome 6 (Pro/Free)
- **Image Processing**: GD Extension (Konversi otomatis ke WebP)

---

## Fitur Utama

### 1. Modul Tour & Travel
- **Manajemen Paket**: Pengaturan destinasi, durasi, dan harga paket wisata secara dinamis.
- **Sistem Booking**: Form pemesanan yang responsif dan mudah digunakan dengan konfirmasi otomatis via WhatsApp.
- **Katalog Destinasi**: Penayangan objek wisata unggulan dengan visual premium.

### 2. Media Library & Optimasi
- **WebP Converter**: Unggahan gambar otomatis dikonversi ke format WebP untuk kecepatan loading maksimal.
- **Auto-Thumbnail**: Pembuatan thumbnail otomatis untuk memperingan beban server.
- **Syncable Media**: Sinkronisasi aset media antara database dan penyimpanan fisik.

### 3. Admin Dashboard
- **CMS Settings**: Pengaturan teks, gambar hero, dan data statistik tanpa menyentuh kode.
- **Booking Management**: Pantau semua reservasi yang masuk dalam satu panel dengan status real-time.
- **Blog & Berita**: Sistem manajemen artikel untuk SEO dan wawasan perjalanan.
- **Finance Reports**: Laporan omzet dan performa transaksi bulanan/tahunan.

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

**Dikembangkan oleh**: Ridho Robbi Pasi (Mitralabs Studio) & Sujai Laketoba Team.
**Terakhir Diperbarui**: 14 Mei 2026
