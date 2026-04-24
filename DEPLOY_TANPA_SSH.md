# 🚀 Deploy Otomatis TANPA SSH (Via cPanel Git)

Jika hosting tidak support SSH, gunakan cara ini.

---

## 📋 Cara Kerja

1. Setup Git di cPanel (sekali saja)
2. Push ke GitHub
3. Pull manual di cPanel (1 klik)
4. Restart app (1 klik)

**Total: 2 klik setiap deploy** (lebih cepat dari manual!)

---

## 🔧 Setup (Sekali Saja)

### STEP 1: Setup Database

1. cPanel → **MySQL® Databases**
2. Buat database: `wonderfultoba` → `medp7341_wonderfultoba`
3. Buat user: `wt_user` → `medp7341_wt_user`
4. Set password, **CATAT!**
5. Add user to database (ALL PRIVILEGES)

---

### STEP 2: Setup Git di cPanel

1. cPanel → Cari **"Git™ Version Control"**
2. Klik **"Create"**
3. Isi form:

```
Clone URL: https://github.com/ridhorobipasi-creator/wonderfultoba.git
Repository Path: /home/medp7341/nodeapps/wonderfultoba
Repository Name: wonderfultoba
```

4. Klik **"Create"**
5. Tunggu sampai selesai clone

---

### STEP 3: Setup Environment (.env)

1. cPanel → **File Manager**
2. Navigate ke: `/home/medp7341/nodeapps/wonderfultoba`
3. Klik **"+ File"** → Nama: `.env`
4. Klik kanan file `.env` → **Edit**
5. Copy paste ini (GANTI PASSWORD_KAMU):

```env
DATABASE_URL="mysql://medp7341_wt_user:PASSWORD_KAMU@localhost:3306/medp7341_wonderfultoba"
JWT_SECRET="wonderfultoba-production-secret-2026-very-long-and-secure"
NODE_ENV="production"
NEXT_PUBLIC_API_URL="/api"
PRISMA_CLIENT_ENGINE_TYPE="library"
NEXT_PUBLIC_WA_NUMBER="6281323888207"
NEXT_PUBLIC_PHONE="+62 813-2388-8207"
NEXT_PUBLIC_EMAIL_TOUR="tour@wonderfultoba.com"
NEXT_PUBLIC_EMAIL_OUTBOUND="outbound@wonderfultoba.com"
NEXT_PUBLIC_SITE_URL="https://wonderfultoba.com"
NEXT_PUBLIC_SITE_NAME="Wonderful Toba"
NEXT_PUBLIC_MAX_FILE_SIZE="5242880"
NEXT_PUBLIC_UPLOAD_PATH="/storage"
```

6. **Save Changes**

---

### STEP 4: Build Pertama Kali

1. cPanel → **Terminal**
2. Jalankan:

```bash
cd /home/medp7341/nodeapps/wonderfultoba
npm install
npx prisma generate
npm run build
npm run db:setup
```

Tunggu 5-10 menit sampai selesai.

---

### STEP 5: Setup Node.js App

1. cPanel → **Setup Node.js App**
2. **Create Application**:

```
Node.js version: 18.x (pilih yang tertinggi)
Application mode: Production
Application root: nodeapps/wonderfultoba
Application URL: wonderfultoba.com
Application startup file: server.js
```

3. Klik **"Create"**
4. Klik **"Start"**

---

### STEP 6: Test Website

1. Buka: https://wonderfultoba.com
2. Test login: https://wonderfultoba.com/login
   - Email: `admin@wonderfultoba.com`
   - Password: `password123`

✅ **Jika berhasil login → Setup selesai!**

---

## 🔄 Cara Deploy Update (2 Klik)

Setiap kali ada perubahan:

### 1. Push ke GitHub (Dari Komputer)

```bash
git add .
git commit -m "Update feature"
git push origin main
```

### 2. Pull di cPanel (1 Klik)

1. cPanel → **Git™ Version Control**
2. Klik **"Manage"** pada repository `wonderfultoba`
3. Klik **"Pull or Deploy"** tab
4. Klik **"Update from Remote"**
5. Tunggu sampai selesai

### 3. Build & Restart (Via Terminal)

**Opsi A: Via Terminal (Recommended)**

1. cPanel → **Terminal**
2. Jalankan:

```bash
cd /home/medp7341/nodeapps/wonderfultoba
npm install
npx prisma generate
npm run build
npx prisma db push
```

**Opsi B: Via Script Otomatis**

1. cPanel → **Terminal**
2. Jalankan:

```bash
cd /home/medp7341/nodeapps/wonderfultoba
bash deploy-cpanel.sh
```

### 4. Restart App (1 Klik)

1. cPanel → **Setup Node.js App**
2. Klik **"Restart"** pada aplikasi wonderfultoba

✅ **Website sudah update!**

---

## 🤖 Semi-Otomatis dengan Cron Job

Buat cron job untuk auto-pull setiap 5 menit:

### Setup Cron Job:

1. cPanel → **Cron Jobs**
2. **Add New Cron Job**:

```
Minute: */5
Hour: *
Day: *
Month: *
Weekday: *

Command:
cd /home/medp7341/nodeapps/wonderfultoba && git pull origin main && npm install && npx prisma generate && npm run build && npx prisma db push && touch tmp/restart.txt
```

3. Klik **"Add New Cron Job"**

**Cara Kerja:**
- Setiap 5 menit, cron akan cek update dari GitHub
- Jika ada update, otomatis pull, build, dan restart
- Kamu tinggal push ke GitHub, tunggu 5 menit → otomatis deploy!

---

## 📊 Monitoring

### Cek Status Git

1. cPanel → **Git™ Version Control**
2. Klik **"Manage"**
3. Lihat:
   - Current branch: `main`
   - Last commit
   - Status

### Cek Log Aplikasi

1. cPanel → **Setup Node.js App**
2. Klik aplikasi **wonderfultoba**
3. Klik **"View Log"**

### Cek Cron Job Log

1. cPanel → **Cron Jobs**
2. Scroll ke bawah → **"Cron Email"**
3. Lihat email notifikasi hasil cron

---

## 🆘 Troubleshooting

### Git Pull Failed

**Error: "local changes would be overwritten"**

Solusi via Terminal:
```bash
cd /home/medp7341/nodeapps/wonderfultoba
git reset --hard origin/main
git pull origin main
```

### Build Failed

**Error: "Module not found"**

Solusi:
```bash
cd /home/medp7341/nodeapps/wonderfultoba
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Website Tidak Update

**Solusi:**
1. Clear build cache:
```bash
cd /home/medp7341/nodeapps/wonderfultoba
rm -rf .next
npm run build
```

2. Restart app:
   - cPanel → Setup Node.js App → Restart

---

## 📊 Perbandingan Metode

| Metode | Kecepatan | Kemudahan | Perlu SSH |
|--------|-----------|-----------|-----------|
| **GitHub Actions** | ⚡⚡⚡ Otomatis penuh | ⭐⭐⭐ Mudah | ✅ Ya |
| **cPanel Git + Cron** | ⚡⚡ Semi-otomatis | ⭐⭐ Sedang | ❌ Tidak |
| **cPanel Git Manual** | ⚡ Manual (2 klik) | ⭐⭐⭐ Mudah | ❌ Tidak |
| **Manual FTP** | 🐌 Sangat lambat | ⭐ Sulit | ❌ Tidak |

---

## ✅ Kesimpulan

**Tanpa SSH, kamu punya 2 opsi:**

### Opsi 1: Manual (2 Klik)
- Push ke GitHub
- Pull di cPanel (1 klik)
- Restart app (1 klik)
- **Total: 2 klik per deploy**

### Opsi 2: Semi-Otomatis (Cron Job)
- Push ke GitHub
- Tunggu 5 menit
- Otomatis deploy!
- **Total: 0 klik per deploy**

**Recommended: Gunakan Opsi 2 (Cron Job)** untuk deployment paling mudah tanpa SSH! 🚀

---

## 📞 Next Steps

1. ✅ Setup Git di cPanel (STEP 1-6)
2. ✅ Test deploy manual (2 klik)
3. ✅ Setup Cron Job (opsional, untuk auto-deploy)
4. ✅ Push ke GitHub → Otomatis deploy!

Selamat! Deployment sudah otomatis tanpa perlu SSH! 🎉
