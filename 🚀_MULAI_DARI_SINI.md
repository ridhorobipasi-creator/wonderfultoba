# 🚀 MULAI DARI SINI

## ✅ Website Sudah LIVE!

**URL:** https://www.wonderfultoba.com

---

## 📚 Dokumentasi Lengkap

### 🎯 Quick Links

1. **[README.md](./README.md)** - Overview project & tech stack
2. **[docs/deployment/README.md](./docs/deployment/README.md)** - Panduan deployment
3. **[docs/INDEX.md](./docs/INDEX.md)** - Index semua dokumentasi

### 📖 Panduan Deployment

- **[BUILD_WINDOWS.md](./docs/deployment/BUILD_WINDOWS.md)** - Build di Windows & upload
- **[FIX_IT_WORKS_PROBLEM.md](./docs/deployment/FIX_IT_WORKS_PROBLEM.md)** - Troubleshooting

### 🛠️ Scripts

- **[scripts/zip-for-upload.ps1](./scripts/zip-for-upload.ps1)** - Zip file untuk upload (Windows)
- **[scripts/build-and-zip.sh](./scripts/build-and-zip.sh)** - Build & zip (Linux/Mac)

---

## 🔄 Update Kode

**Setiap kali ada perubahan:**

### Windows:
```powershell
npm run build
.\scripts\zip-for-upload.ps1
```

### Linux/Mac:
```bash
npm run build
./scripts/build-and-zip.sh
```

**Upload → Extract → Restart app di cPanel**

---

## 📁 Struktur Folder

```
wonderfultoba/
├── 🚀_MULAI_DARI_SINI.md    ← File ini
├── README.md                 ← Overview project
├── docs/
│   ├── deployment/           ← Panduan deployment
│   ├── setup/                ← Panduan setup
│   └── INDEX.md              ← Index dokumentasi
├── scripts/                  ← Build & deployment scripts
├── src/                      ← Source code
├── public/                   ← Static assets
├── prisma/                   ← Database schema
└── server.js                 ← Production server
```

---

## 🎯 Development

```bash
# Install dependencies
npm install

# Setup database
cp .env.example .env
npx prisma generate
npx prisma db push

# Run dev server
npm run dev
```

Open http://localhost:3000

---

## 📞 Support

Kalau ada masalah, cek:
1. [Troubleshooting](./docs/deployment/FIX_IT_WORKS_PROBLEM.md)
2. [Dokumentasi lengkap](./docs/INDEX.md)

---

**Status:** ✅ LIVE & RUNNING  
**Last updated:** April 2026
