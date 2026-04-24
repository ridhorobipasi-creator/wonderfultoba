# ✅ Checklist Deploy ke cPanel

## 📋 Persiapan (Lakukan Sekali)

### 1️⃣ Database cPanel
- [ ] Buat database: `medp7341_wonderfultoba`
- [ ] Buat user: `medp7341_wt_user` dengan password kuat
- [ ] Tambahkan user ke database (ALL PRIVILEGES)

### 2️⃣ Clone dari GitHub
Di Terminal cPanel:
```bash
cd /home/medp7341
mkdir -p nodeapps
cd nodeapps
git clone https://github.com/ridhorobipasi-creator/wonderfultoba.git
cd wonderfultoba
```

### 3️⃣ Setup Environment
```bash
cd /home/medp7341/nodeapps/wonderfultoba
nano .env
```

Copy dari `.env.production.example` dan isi:
- `DATABASE_URL` dengan info database kamu
- `JWT_SECRET` dengan random string

### 4️⃣ Install & Build
```bash
npm install
npx prisma generate
npm run build
npm run db:setup
```

### 5️⃣ Setup Node.js App di cPanel
- [ ] Buka **Setup Node.js App**
- [ ] Create Application:
  - Node.js version: **18.x**
  - Application mode: **Production**
  - Application root: `nodeapps/wonderfultoba`
  - Application URL: `wonderfultoba.com`
  - Application startup file: `server.js`
- [ ] Klik **Create**
- [ ] Klik **Start**

### 6️⃣ Test
- [ ] Buka: https://wonderfultoba.com
- [ ] Login: https://wonderfultoba.com/login
  - Email: `admin@wonderfultoba.com`
  - Password: `password123`

---

## 🔄 Update Aplikasi (Setiap Ada Perubahan)

Di Terminal cPanel:
```bash
cd /home/medp7341/nodeapps/wonderfultoba
git pull origin main
npm install
npx prisma generate
npm run build
npx prisma db push
```

Lalu restart dari **Setup Node.js App** → **Restart**

---

## 🆘 Troubleshooting Cepat

**Website tidak muncul?**
```bash
# Cek status di Setup Node.js App
# Lihat error log
# Restart aplikasi
```

**Database error?**
```bash
# Cek .env
cat /home/medp7341/nodeapps/wonderfultoba/.env
# Pastikan DATABASE_URL benar
```

**Login tidak bisa?**
```bash
cd /home/medp7341/nodeapps/wonderfultoba
npx tsx prisma/seed.ts
```

---

## 📞 Info Penting

- **Folder aplikasi**: `/home/medp7341/nodeapps/wonderfultoba`
- **Domain**: `wonderfultoba.com`
- **Entry point**: `server.js`
- **Admin email**: `admin@wonderfultoba.com`
- **Admin password default**: `password123` (ganti setelah login!)
