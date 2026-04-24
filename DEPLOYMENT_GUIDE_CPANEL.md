# 🚀 Panduan Deploy ke cPanel

## 📋 Persiapan

### 1. Database di cPanel
1. Login ke cPanel
2. Buka **MySQL® Databases**
3. Buat database baru: `medp7341_wonderfultoba`
4. Buat user: `medp7341_wt_user`
5. Set password yang kuat
6. Tambahkan user ke database dengan **ALL PRIVILEGES**
7. Catat informasi:
   - Database: `medp7341_wonderfultoba`
   - Username: `medp7341_wt_user`
   - Password: [password yang kamu buat]
   - Host: `localhost`

### 2. Setup Node.js App di cPanel

1. Buka **Setup Node.js App**
2. Klik **Create Application**
3. Isi form:
   - **Node.js version**: 18.x atau lebih tinggi
   - **Application mode**: Production
   - **Application root**: `nodeapps/wonderfultoba`
   - **Application URL**: `wonderfultoba.com`
   - **Application startup file**: `server.js`
   - **Passenger log file**: (biarkan default)

4. Klik **Create**

### 3. Setup Git Deployment

#### A. Di cPanel - Git Version Control

1. Buka **Git™ Version Control**
2. Klik **Create**
3. Isi form:
   - **Clone URL**: `https://github.com/ridhorobipasi-creator/wonderfultoba.git`
   - **Repository Path**: `/home/medp7341/nodeapps/wonderfultoba`
   - **Repository Name**: `wonderfultoba`
4. Klik **Create**

#### B. Setup Environment Variables

1. Masuk ke folder aplikasi via **Terminal** atau **File Manager**
2. Buat file `.env` di `/home/medp7341/nodeapps/wonderfultoba/`:

```bash
cd /home/medp7341/nodeapps/wonderfultoba
nano .env
```

3. Isi dengan:

```env
# Production Database
DATABASE_URL="mysql://medp7341_wt_user:PASSWORD_KAMU@localhost:3306/medp7341_wonderfultoba"

# JWT Secret (ganti dengan random string)
JWT_SECRET="ganti-dengan-random-string-panjang-dan-aman"

# API Configuration
NEXT_PUBLIC_API_URL="/api"

# Prisma Configuration
PRISMA_CLIENT_ENGINE_TYPE="library"

# Contact Information
NEXT_PUBLIC_WA_NUMBER="6281323888207"
NEXT_PUBLIC_PHONE="+62 813-2388-8207"
NEXT_PUBLIC_EMAIL_TOUR="tour@wonderfultoba.com"
NEXT_PUBLIC_EMAIL_OUTBOUND="outbound@wonderfultoba.com"

# Site Configuration (Production)
NEXT_PUBLIC_SITE_URL="https://wonderfultoba.com"
NEXT_PUBLIC_SITE_NAME="Wonderful Toba"

# Upload Configuration
NEXT_PUBLIC_MAX_FILE_SIZE="5242880"
NEXT_PUBLIC_UPLOAD_PATH="/storage"

# Node Environment
NODE_ENV="production"
```

4. Save (Ctrl+O, Enter, Ctrl+X)

#### C. Install Dependencies & Build

Di Terminal cPanel:

```bash
cd /home/medp7341/nodeapps/wonderfultoba

# Install dependencies
npm install

# Generate Prisma Client
npx prisma generate

# Build aplikasi
npm run build

# Setup database (push schema + seed)
npm run db:setup
```

#### D. Start Application

1. Kembali ke **Setup Node.js App**
2. Cari aplikasi `wonderfultoba`
3. Klik **Start** atau **Restart**

### 4. Verifikasi

1. Buka browser: `https://wonderfultoba.com`
2. Cek homepage muncul
3. Test login: `https://wonderfultoba.com/login`
   - Email: `admin@wonderfultoba.com`
   - Password: `password123`

---

## 🔄 Update Aplikasi (Deploy Ulang)

Setiap kali ada perubahan di GitHub:

### Via cPanel Terminal:

```bash
cd /home/medp7341/nodeapps/wonderfultoba

# Pull perubahan terbaru
git pull origin main

# Install dependencies baru (jika ada)
npm install

# Generate Prisma Client
npx prisma generate

# Build ulang
npm run build

# Update database schema (jika ada perubahan)
npx prisma db push

# Restart aplikasi
# Buka Setup Node.js App → Restart
```

### Atau gunakan script otomatis:

```bash
cd /home/medp7341/nodeapps/wonderfultoba
bash deploy-cpanel.sh
```

Lalu restart aplikasi dari **Setup Node.js App**.

---

## 🔧 Troubleshooting

### 1. Error "Can't reach database server"
- Cek DATABASE_URL di `.env`
- Pastikan database sudah dibuat
- Pastikan user punya akses ke database

### 2. Error "Module not found"
```bash
cd /home/medp7341/nodeapps/wonderfultoba
rm -rf node_modules package-lock.json
npm install
npm run build
```

### 3. Error "Prisma Client not generated"
```bash
npx prisma generate
npm run build
```

### 4. Website tidak muncul
- Cek Node.js App status (harus Running)
- Cek error log di cPanel → Setup Node.js App → View Log
- Restart aplikasi

### 5. Login tidak berfungsi
```bash
# Reset password admin
cd /home/medp7341/nodeapps/wonderfultoba
npx tsx prisma/seed.ts
```

---

## 📁 Struktur Folder Final

```
/home/medp7341/
├── nodeapps/
│   └── wonderfultoba/          ← Source code (aman, tidak publik)
│       ├── .env                ← Environment variables
│       ├── .next/              ← Build output
│       ├── node_modules/
│       ├── prisma/
│       ├── public/
│       ├── src/
│       ├── server.js           ← Entry point
│       ├── package.json
│       └── ...
│
└── public_html/
    └── wonderfultoba.com/      ← Handled by Node.js (bisa kosong)
```

---

## 🔐 Keamanan

1. **Jangan commit `.env`** ke GitHub
2. **Ganti JWT_SECRET** dengan random string
3. **Ganti password database** yang kuat
4. **Ganti password admin** setelah login pertama
5. **Backup database** secara berkala

---

## 📞 Support

Jika ada masalah:
1. Cek error log di cPanel
2. Cek terminal output saat build
3. Pastikan semua environment variables sudah benar
