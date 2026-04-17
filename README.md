# Wonderful Toba - Full Stack Project

Proyek ini telah dimigrasi ke arsitektur Full Stack modern menggunakan Next.js dan Laravel.

## Struktur Proyek

- **`/nextjs-app`**: Frontend aplikasi menggunakan Next.js (App Router), Tailwind CSS, dan Prisma ORM.
- **`/laravel-api`**: Backend API menggunakan Laravel 11.
- **`/.github/workflows`**: Otomatisasi deployment ke cPanel menggunakan GitHub Actions (SFTP).

## Cara Menjalankan Lokal

### 1. Frontend (Next.js)
```bash
cd nextjs-app
npm install
npm run dev
```
Akses di: `http://localhost:3000`

### 2. Backend (Laravel)
```bash
cd laravel-api
composer install
php artisan serve
```
Akses di: `http://127.0.0.1:8000`

## Deployment
Setiap perubahan yang di-push ke branch `main` akan otomatis di-deploy ke cPanel melalui GitHub Actions. Pastikan untuk mengatur **Secrets** di GitHub untuk host, username, dan password SFTP (Port 22).

---
© 2026 Wonderful Toba
