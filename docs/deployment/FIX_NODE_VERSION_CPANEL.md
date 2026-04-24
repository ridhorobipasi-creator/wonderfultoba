# 🔥 FIX: Node.js Version & npm install Error

## ❌ Masalah

```
EBADENGINE Unsupported engine {
  package: 'next@16.2.4',
  required: { node: '>=20.9.0' },
  current: { node: 'v18.20.8', npm: '10.8.2' }
}

npm error command failed
npm error signal SIGABRT
npm error command sh -c node scripts/postinstall.js
```

**Artinya:**
- Node.js 18.20.8 terlalu lama untuk Next.js 16
- Prisma gagal install karena Node.js version

---

## ✅ SOLUSI 1: Upgrade Node.js di cPanel (RECOMMENDED)

### Step 1: Cek Node.js Version yang Tersedia

**Di cPanel → Setup Node.js App:**

Lihat dropdown **Node.js version** - cari yang ≥20.9.0:
- ✅ Node.js 20.x
- ✅ Node.js 22.x
- ❌ Node.js 18.x (terlalu lama)

### Step 2: Ganti Node.js Version

1. Pilih **Node.js 20** atau **22** dari dropdown
2. Klik **Save**
3. Klik **Restart**

### Step 3: Install Ulang Dependencies

**Via Terminal SSH:**

```bash
cd ~/nodeapps/wonderfultoba

# Hapus node_modules lama
rm -rf node_modules
rm -rf .next

# Install ulang dengan Node.js baru
npm install

# Build app
npm run build
```

### Step 4: Restart App

Di cPanel → Setup Node.js App → Klik **Restart**

---

## ✅ SOLUSI 2: Downgrade Next.js (Kalau Node 20+ Tidak Tersedia)

**Kalau cPanel cuma punya Node.js 18, downgrade Next.js:**

### Step 1: Edit package.json

Ganti versi ini:

```json
{
  "dependencies": {
    "next": "15.1.6",
    "react": "19.0.0",
    "react-dom": "19.0.0"
  },
  "devDependencies": {
    "@types/react": "^18",
    "@types/react-dom": "^18",
    "tailwindcss": "^3.4.17"
  }
}
```

### Step 2: Install Ulang

```bash
cd ~/nodeapps/wonderfultoba

# Hapus lock file
rm -rf node_modules package-lock.json

# Install dengan versi baru
npm install

# Build
npm run build
```

---

## ✅ SOLUSI 3: Fix Prisma Error (Kalau Masih Error)

### Cara 1: Generate Prisma di Local, Upload Hasil

**Di komputer local:**

```bash
# Generate Prisma client
npm run prisma:generate

# Zip folder node_modules/@prisma
zip -r prisma-client.zip node_modules/@prisma
```

**Upload ke server:**
- Upload `prisma-client.zip` ke `/home/username/nodeapps/wonderfultoba/`
- Extract di folder yang sama

### Cara 2: Install Prisma Manual

**Via Terminal SSH:**

```bash
cd ~/nodeapps/wonderfultoba

# Install Prisma binary manual
npx prisma generate --skip-postinstall

# Atau force install
npm install @prisma/client --force
```

---

## 🎯 CHECKLIST

**Pilih salah satu solusi:**

### Solusi 1 (Recommended):
- [ ] Upgrade Node.js ke 20+ di cPanel
- [ ] Hapus `node_modules` dan `.next`
- [ ] `npm install`
- [ ] `npm run build`
- [ ] Restart app

### Solusi 2 (Kalau Node 20+ tidak ada):
- [ ] Edit `package.json` (downgrade Next.js ke 15.1.6)
- [ ] Hapus `node_modules` dan `package-lock.json`
- [ ] `npm install`
- [ ] `npm run build`
- [ ] Restart app

### Solusi 3 (Kalau Prisma masih error):
- [ ] Generate Prisma di local
- [ ] Upload hasil ke server
- [ ] Restart app

---

## 🔍 Cek Node.js Version di cPanel

**Via Terminal SSH:**

```bash
node --version
npm --version
```

**Harus:**
- Node.js ≥20.9.0 untuk Next.js 16
- Node.js ≥18.17.0 untuk Next.js 15

---

## 💡 TIPS

**Kalau hosting tidak support Node.js 20+:**

1. Minta upgrade ke hosting support
2. Atau pindah ke VPS/Cloud (DigitalOcean, AWS, Vercel)
3. Atau downgrade Next.js ke versi 15

**Vercel (FREE & MUDAH):**
- Support Node.js terbaru
- Auto deploy dari GitHub
- Zero config
- Gratis untuk personal project

---

## 🚀 Quick Fix Command

**Kalau sudah upgrade Node.js ke 20+:**

```bash
cd ~/nodeapps/wonderfultoba && \
rm -rf node_modules .next package-lock.json && \
npm install && \
npm run build
```

Setelah selesai → Restart app di cPanel! 🎉
