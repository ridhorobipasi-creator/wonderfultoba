# Panduan Deployment Wonderful Toba ke cPanel 🚀

Dokumen ini berisi langkah-langkah final untuk memindahkan aplikasi dari **Localhost** ke **Produksi (cPanel)**.

## 1. Persiapan di Localhost
Sebelum mengunggah, pastikan semua aset sudah di-build dan cache sudah dibersihkan.
1. Jalankan file `optimize.bat` yang sudah saya buat di root folder.
2. Script tersebut akan otomatis menjalankan:
   - `npm run build` (Mengompilasi CSS/JS Tailwind)
   - `php artisan optimize` (Caching konfigurasi dan route)
   - `php artisan storage:link` (Memastikan link gambar aktif)

## 2. Membuat Arsip (Zip)
Buat file ZIP dari seluruh isi folder project, **TETAPI JANGAN masukkan folder berikut**:
- `node_modules/` (Akan diinstall di server)
- `tests/`
- `.git/`

## 3. Konfigurasi di cPanel
1. **Upload Zip**: Unggah file zip ke folder project Anda di cPanel (misal: `/home/user/wonderfultoba`).
2. **Ekstrak**: Ekstrak file tersebut.
3. **Setup Node.js App**:
   - Jika menggunakan fitur "Setup Node.js App" di cPanel, arahkan ke folder project.
   - Klik "Run JS Install" untuk menginstal dependency frontend.
4. **Setup Database**:
   - Buat database MySQL baru via cPanel.
   - Buat user database dan hubungkan ke database tersebut (beri hak akses FULL).
5. **Edit file `.env`**:
   Ubah bagian berikut di file `.env` server:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://wonderfultoba.id  # Ganti dengan domain asli

   DB_DATABASE=nama_db_cpanel
   DB_USERNAME=user_db_cpanel
   DB_PASSWORD=password_db_cpanel
   ```

## 4. Perintah Final di Terminal cPanel (SSH/Terminal)
Jalankan perintah ini satu per satu di folder project server:
```bash
# Install dependencies PHP
composer install --optimize-autoloader --no-dev

# Migrasi Database (Hanya jika database masih kosong)
php artisan migrate --force

# Pastikan Symlink Media Aktif
php artisan storage:link
```

## 5. Keamanan Tambahan
Pastikan folder `storage` dan `bootstrap/cache` memiliki izin akses (*Permission*) **775** atau **755** agar Laravel bisa menulis file log dan cache.

---
**Wonderful Toba - Premium Tourism Agency System**
