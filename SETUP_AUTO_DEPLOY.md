# 🤖 Setup Auto Deploy dari GitHub ke cPanel

## 📋 Cara Kerja

Setiap kali kamu **push ke GitHub**, otomatis:
1. ✅ Pull code terbaru
2. ✅ Install dependencies
3. ✅ Build aplikasi
4. ✅ Update database
5. ✅ Restart aplikasi

---

## 🔧 Setup (Lakukan Sekali Saja)

### STEP 1: Setup Awal di cPanel (Manual - Sekali Saja)

#### 1.1 Buat Database
1. Login cPanel → **MySQL® Databases**
2. Buat database: `wonderfultoba` (jadi: `medp7341_wonderfultoba`)
3. Buat user: `wt_user` (jadi: `medp7341_wt_user`)
4. Set password kuat, **CATAT!**
5. Add user to database dengan **ALL PRIVILEGES**

#### 1.2 Clone Repository (Pertama Kali)
Buka **Terminal** di cPanel:

```bash
cd /home/medp7341
mkdir -p nodeapps
cd nodeapps
git clone https://github.com/ridhorobipasi-creator/wonderfultoba.git
cd wonderfultoba
```

#### 1.3 Buat File .env
```bash
nano .env
```

Copy paste ini (GANTI PASSWORD_KAMU):

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

Save: **Ctrl+O**, **Enter**, **Ctrl+X**

#### 1.4 Build Pertama Kali
```bash
npm install
npx prisma generate
npm run build
npm run db:setup
```

#### 1.5 Setup Node.js App
1. cPanel → **Setup Node.js App**
2. **Create Application**:
   - Node.js version: **18.x**
   - Application mode: **Production**
   - Application root: `nodeapps/wonderfultoba`
   - Application URL: `wonderfultoba.com`
   - Application startup file: `server.js`
3. Klik **Create**
4. Klik **Start**

---

### STEP 2: Setup GitHub Secrets (Untuk Auto Deploy)

#### 2.1 Dapatkan Info SSH cPanel

**Host:**
- Biasanya: `wonderfultoba.com` atau IP server
- Cek di cPanel → **Terminal** → lihat hostname

**Username:**
- Username cPanel kamu: `medp7341`

**Password:**
- Password cPanel kamu

**Port:**
- Default SSH: `22`
- Atau cek di cPanel → **SSH Access**

#### 2.2 Tambahkan Secrets di GitHub

1. Buka: https://github.com/ridhorobipasi-creator/wonderfultoba
2. Klik **Settings** (tab paling kanan)
3. Sidebar kiri → **Secrets and variables** → **Actions**
4. Klik **New repository secret**

Tambahkan 4 secrets ini:

**Secret 1:**
- Name: `CPANEL_HOST`
- Value: `wonderfultoba.com` (atau IP server)

**Secret 2:**
- Name: `CPANEL_USERNAME`
- Value: `medp7341`

**Secret 3:**
- Name: `CPANEL_PASSWORD`
- Value: [password cPanel kamu]

**Secret 4:**
- Name: `CPANEL_PORT`
- Value: `22`

---

## ✅ Selesai! Cara Menggunakan

### Auto Deploy (Otomatis)

Setiap kali kamu push ke GitHub:

```bash
git add .
git commit -m "Update feature"
git push origin main
```

GitHub Actions akan otomatis:
1. Deploy ke cPanel
2. Build aplikasi
3. Update database
4. Restart app

**Lihat progress:**
- GitHub → **Actions** tab
- Klik workflow yang sedang running

### Manual Deploy (Jika Perlu)

1. Buka GitHub → **Actions** tab
2. Pilih workflow **"Auto Deploy to cPanel (Full)"**
3. Klik **Run workflow** → **Run workflow**

---

## 🔍 Monitoring

### Cek Status Deployment

1. GitHub → **Actions** tab
2. Lihat status:
   - ✅ Green = Success
   - ❌ Red = Failed
   - 🟡 Yellow = Running

### Cek Website

- Homepage: https://wonderfultoba.com
- Admin: https://wonderfultoba.com/login

### Cek Log di cPanel

1. cPanel → **Setup Node.js App**
2. Klik aplikasi **wonderfultoba**
3. Klik **View Log**

---

## 🆘 Troubleshooting

### Deployment Failed di GitHub Actions

**Error: "Permission denied"**
- Cek CPANEL_USERNAME dan CPANEL_PASSWORD di GitHub Secrets
- Pastikan SSH enabled di cPanel

**Error: "Directory not found"**
- Jalankan STEP 1.2 manual (clone repository)

**Error: "Database connection failed"**
- Cek file .env di server
- Pastikan DATABASE_URL benar

### Website Tidak Update

**Solusi 1: Restart Manual**
1. cPanel → **Setup Node.js App**
2. Klik **Restart**

**Solusi 2: Clear Cache**
```bash
# Via Terminal cPanel
cd /home/medp7341/nodeapps/wonderfultoba
rm -rf .next
npm run build
```

**Solusi 3: Redeploy**
1. GitHub → **Actions**
2. **Run workflow** manual

---

## 📊 Workflow Files

Sudah ada 2 workflow:

1. **deploy-cpanel.yml** - Deploy basic
2. **deploy-cpanel-auto.yml** - Deploy + auto restart (RECOMMENDED)

Keduanya akan jalan otomatis saat push ke `main` branch.

---

## 🔐 Keamanan

✅ **Aman:**
- Password disimpan di GitHub Secrets (encrypted)
- Tidak ada password di code
- SSH connection secure

⚠️ **Jangan:**
- Commit file .env ke GitHub
- Share GitHub Secrets dengan orang lain
- Gunakan password lemah

---

## 📞 Summary

**Setup Sekali:**
1. ✅ Buat database di cPanel
2. ✅ Clone repo + setup .env
3. ✅ Build pertama kali
4. ✅ Setup Node.js App
5. ✅ Tambahkan GitHub Secrets

**Setelah Itu:**
- Push ke GitHub → **Otomatis deploy!** 🚀
- Tidak perlu login cPanel lagi
- Tidak perlu build manual lagi

**Monitoring:**
- GitHub Actions → Lihat progress
- cPanel → Lihat log (jika error)

---

Sekarang deployment sudah **FULL OTOMATIS**! 🎉
