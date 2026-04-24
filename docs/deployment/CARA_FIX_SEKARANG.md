# 🚀 CARA FIX ERROR npm install - SEKARANG!

## ❌ Error yang Kamu Alami

```
EBADENGINE Unsupported engine
npm error signal SIGABRT
npm error command sh -c node scripts/postinstall.js
EAGAIN error (spawn process limit)
```

**Root cause:** 
1. Node.js 18.20.8 terlalu lama untuk Next.js 16.2.4
2. Shared hosting limit process (CloudLinux) → `npm run build` gagal

## 🔥 SOLUSI FINAL: Build di Local, Upload ke Server

**Shared hosting gak bisa build Next.js** karena limit process!

**Panduan lengkap:** Lihat `BUILD_DI_LOCAL_UPLOAD.md`

**Quick steps:**
1. Build di local: `npm run build`
2. Zip hasil build
3. Upload ke cPanel
4. Extract & restart app

---

---

## ✅ SOLUSI TERCEPAT

### Opsi 1: Upgrade Node.js di cPanel (5 menit) ⭐ RECOMMENDED

**Step by step:**

1. **Login cPanel** → Cari **"Setup Node.js App"**

2. **Edit aplikasi wonderfultoba:**
   - Klik icon **Edit** (pensil)

3. **Ganti Node.js version:**
   - Dropdown **Node.js version** → Pilih **20.x** atau **22.x**
   - Klik **Save**

4. **Via Terminal SSH, jalankan:**

```bash
cd ~/nodeapps/wonderfultoba

# Hapus semua file lama
rm -rf node_modules .next package-lock.json

# Hapus Prisma cache yang error
rm -rf ~/nodevenv/nodeapps/wonderfultoba/18/lib/node_modules/@prisma

# Install ulang
npm install

# Build
npm run build
```

5. **Restart app** di cPanel → Setup Node.js App → **Restart**

6. **Test:** Buka `https://wonderfultoba.com`

---

### Opsi 2: Downgrade Next.js (Kalau Node 20+ Tidak Ada)

**Jalankan script otomatis:**

```bash
cd ~/nodeapps/wonderfultoba

# Download & jalankan fix script
chmod +x fix-install.sh
./fix-install.sh
```

**Atau manual:**

```bash
cd ~/nodeapps/wonderfultoba

# Backup
cp package.json package.json.backup

# Copy package.json untuk Node 18
cp package.json.node18 package.json

# Clean install
rm -rf node_modules .next package-lock.json
rm -rf ~/nodevenv/nodeapps/wonderfultoba/18/lib/node_modules/@prisma

# Install dengan flag bypass
npm install --legacy-peer-deps

# Generate Prisma
npx prisma generate

# Build
npm run build
```

**Restart app** di cPanel!

---

## 🎯 Kenapa Error?

| Package | Butuh Node.js | Kamu Pakai |
|---------|---------------|------------|
| Next.js 16.2.4 | ≥20.9.0 | 18.20.8 ❌ |
| Tailwind 4 | ≥20 | 18.20.8 ❌ |
| Chokidar 5 | ≥20.19.0 | 18.20.8 ❌ |

**Solusi:**
- Upgrade Node → Bisa pakai Next.js 16 ✅
- Downgrade Next → Bisa pakai Node 18 ✅

---

## 🔍 Cek Node.js Version di cPanel

**Via Terminal:**

```bash
node --version
# Harus: v20.x.x atau v22.x.x
```

**Kalau masih v18.x.x:**
- Berarti belum berhasil upgrade
- Cek lagi di Setup Node.js App

---

## 💡 TIPS

**Kalau hosting tidak punya Node.js 20+:**

1. **Hubungi support hosting** - minta upgrade Node.js
2. **Pindah ke Vercel** (gratis, support Node.js terbaru)
3. **Pakai VPS** (DigitalOcean, AWS, dll)

**Vercel = Paling Gampang:**
- Connect GitHub repo
- Auto deploy
- Zero config
- Gratis untuk personal project
- Support Node.js 20+

---

## 🚨 Kalau Masih Error

**Kirim info ini:**

```bash
# Cek Node version
node --version

# Cek npm version
npm --version

# Cek error log
cat ~/.npm/_logs/2026-04-24T04_12_20_531Z-debug-0.log
```

Atau screenshot:
- Setup Node.js App di cPanel
- Error message lengkap

---

## ⚡ Quick Command (Copy Paste)

**Kalau sudah upgrade Node.js ke 20+:**

```bash
cd ~/nodeapps/wonderfultoba && \
rm -rf node_modules .next package-lock.json && \
rm -rf ~/nodevenv/nodeapps/wonderfultoba/18/lib/node_modules/@prisma && \
npm install && \
npm run build && \
echo "✅ DONE! Restart app di cPanel sekarang!"
```

**Kalau masih Node.js 18:**

```bash
cd ~/nodeapps/wonderfultoba && \
cp package.json.node18 package.json && \
rm -rf node_modules .next package-lock.json && \
rm -rf ~/nodevenv/nodeapps/wonderfultoba/18/lib/node_modules/@prisma && \
npm install --legacy-peer-deps && \
npx prisma generate && \
npm run build && \
echo "✅ DONE! Restart app di cPanel sekarang!"
```

---

## 🎉 Setelah Berhasil

1. Restart app di cPanel
2. Buka `https://wonderfultoba.com`
3. Kalau muncul homepage Next.js → **SUKSES!** 🚀
4. Kalau masih "It works" → Cek `FIX_IT_WORKS_PROBLEM.md`

---

**File bantuan:**
- `fix-install.sh` - Script otomatis fix
- `package.json.node18` - Package.json untuk Node 18
- `FIX_NODE_VERSION_CPANEL.md` - Panduan lengkap
- `FIX_IT_WORKS_PROBLEM.md` - Fix "It works" problem
