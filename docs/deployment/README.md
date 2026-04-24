# 📚 Panduan Deployment - Wonderful Toba

## 🎯 Mulai Dari Sini

Website sudah **LIVE** di: `https://www.wonderfultoba.com` ✅

---

## 📖 Dokumentasi Deployment

### 1. Panduan Utama
- **[BUILD_WINDOWS.md](./BUILD_WINDOWS.md)** - Panduan build di Windows & upload ke cPanel
- **[BUILD_DI_LOCAL_UPLOAD.md](./BUILD_DI_LOCAL_UPLOAD.md)** - Panduan umum build & upload

### 2. Script Deployment
- **[build-and-zip.ps1](../../scripts/build-and-zip.ps1)** - Script PowerShell build & zip otomatis
- **[zip-for-upload.ps1](../../scripts/zip-for-upload.ps1)** - Script zip file untuk upload
- **[build-and-zip.sh](../../scripts/build-and-zip.sh)** - Script Bash untuk Linux/Mac

### 3. Troubleshooting
- **[FIX_IT_WORKS_PROBLEM.md](./FIX_IT_WORKS_PROBLEM.md)** - Fix "It works! NodeJS" problem
- **[FIX_NODE_VERSION_CPANEL.md](./FIX_NODE_VERSION_CPANEL.md)** - Fix Node.js version error
- **[CARA_FIX_SEKARANG.md](./CARA_FIX_SEKARANG.md)** - Quick fix untuk error umum

---

## 🚀 Quick Start - Update Kode

**Setiap kali ada perubahan kode:**

### Windows (PowerShell):
```powershell
npm run build
.\scripts\zip-for-upload.ps1
```

### Linux/Mac (Bash):
```bash
npm run build
./scripts/build-and-zip.sh
```

**Setelah zip selesai:**
1. Upload ke cPanel File Manager
2. Extract di `/home/medp7341/nodeapps/wonderfultoba/`
3. Restart app di Setup Node.js App
4. Test `https://www.wonderfultoba.com`

---

## ⚠️ PENTING!

**JANGAN jalankan di server cPanel:**
- ❌ `npm install`
- ❌ `npm run build`

**Kenapa?** Shared hosting limit process → error EAGAIN

**Solusi:** Build di local, upload hasil build

---

## 📁 Struktur File

```
wonderfultoba/
├── docs/
│   └── deployment/          ← Dokumentasi deployment
│       ├── README.md        ← File ini
│       ├── BUILD_WINDOWS.md
│       ├── BUILD_DI_LOCAL_UPLOAD.md
│       ├── FIX_IT_WORKS_PROBLEM.md
│       └── ...
├── scripts/                 ← Script deployment
│   ├── build-and-zip.ps1   ← PowerShell (Windows)
│   ├── zip-for-upload.ps1
│   └── build-and-zip.sh    ← Bash (Linux/Mac)
├── .next/                   ← Hasil build (jangan commit)
├── src/                     ← Source code
├── public/                  ← Static assets
├── prisma/                  ← Database schema
├── server.js                ← Production server
└── package.json
```

---

## 🔗 Link Terkait

- **Website:** https://www.wonderfultoba.com
- **cPanel:** (login dengan kredensial hosting)
- **Database:** PostgreSQL (lihat `.env.production`)

---

## 💡 Tips

### Update Cepat (Cuma Kode)
Zip cuma folder `.next/` → Upload → Extract → Restart

### Update Full (Ada Package Baru)
Zip semua (`.next/`, `node_modules/`, dll) → Upload → Extract → Restart

### Backup Database
```bash
pg_dump -h host -U user -d dbname > backup.sql
```

---

## 📞 Support

Kalau ada masalah, cek file troubleshooting di folder ini atau hubungi support hosting.
