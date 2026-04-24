# 🚀 BUILD DI LOCAL & UPLOAD KE CPANEL

## ❌ Masalah: Shared Hosting Limit Process

**Error EAGAIN** = Server limit thread/process (CloudLinux)  
**Next.js build** = Spawn banyak process → kena limit → **GAGAL**

**Solusi:** Build di local, upload hasil build ke server

---

## ✅ CARA BUILD DI LOCAL & UPLOAD

### Step 1: Build di Komputer Local

**Jalankan di terminal local (bukan cPanel):**

```bash
# Masuk ke folder project
cd /path/to/wonderfultoba

# Install dependencies
npm install

# Generate Prisma
npx prisma generate

# Build Next.js
npm run build
```

**Tunggu sampai selesai** (bisa 2-5 menit)

---

### Step 2: Zip File yang Dibutuhkan

**Otomatis (pakai script):**

```bash
chmod +x build-and-zip.sh
./build-and-zip.sh
```

**Manual (Windows):**

Zip folder/file ini:
```
✅ .next/              (hasil build - PENTING!)
✅ node_modules/       (dependencies)
✅ prisma/             (schema)
✅ public/             (assets)
✅ src/                (source code)
✅ package.json
✅ package-lock.json
✅ next.config.ts
✅ server.js
✅ .env.production
```

**Jangan zip:**
```
❌ .git/
❌ .next/cache/
❌ node_modules/.cache/
❌ *.md (dokumentasi)
```

**Nama zip:** `wonderfultoba-production.zip`

---

### Step 3: Upload ke cPanel

**Via File Manager:**

1. Login cPanel → **File Manager**
2. Masuk ke folder: `/home/medp7341/nodeapps/wonderfultoba/`
3. **Upload** `wonderfultoba-production.zip`
4. Klik kanan zip → **Extract**
5. Pilih extract ke folder yang sama
6. Tunggu sampai selesai

**Via FTP (Alternatif):**

```
Host: ftp.wonderfultoba.com
Username: medp7341
Password: (password cPanel)
Folder: /home/medp7341/nodeapps/wonderfultoba/
```

Upload & extract zip

---

### Step 4: Set Environment Variables

**Di cPanel → Setup Node.js App → Environment variables:**

Tambahkan:

```
NODE_ENV=production
DATABASE_URL=postgresql://user:pass@host:5432/dbname
```

---

### Step 5: Restart App

**Di cPanel → Setup Node.js App:**

1. Pastikan **Application startup file** = `server.js`
2. Klik **Restart**

---

### Step 6: Test

Buka: `https://wonderfultoba.com`

**Kalau berhasil:** Homepage Next.js muncul! 🎉  
**Kalau masih "It works":** Cek startup file harus `server.js`

---

## 🔄 UPDATE KODE (Kalau Ada Perubahan)

**Setiap kali update kode:**

1. **Di local:**
   ```bash
   npm run build
   ```

2. **Zip folder `.next/` aja** (kalau cuma update kode, gak perlu zip semua)

3. **Upload & extract** ke server

4. **Restart app** di cPanel

---

## 💡 TIPS CEPAT

### Zip Cuma Folder .next (Update Cepat)

**Kalau cuma update kode (gak install package baru):**

```bash
# Build
npm run build

# Zip cuma .next
zip -r next-build.zip .next

# Upload next-build.zip
# Extract di ~/nodeapps/wonderfultoba/
# Restart app
```

Lebih cepat karena file lebih kecil!

---

### Zip Cuma node_modules (Kalau Install Package Baru)

```bash
# Install package baru
npm install

# Zip node_modules
zip -r node-modules.zip node_modules

# Upload & extract
# Restart app
```

---

## 🚨 PENTING!

**Di server cPanel, JANGAN jalankan:**

```bash
❌ npm install
❌ npm run build
```

**Kenapa?** Kena limit process → error EAGAIN

**Boleh jalankan:**

```bash
✅ npx prisma generate (kalau perlu)
✅ npx prisma db push (kalau perlu)
✅ node server.js (test manual)
```

---

## 🎯 CHECKLIST FINAL

- [ ] Build di local: `npm run build`
- [ ] Zip file yang dibutuhkan
- [ ] Upload ke `/home/medp7341/nodeapps/wonderfultoba/`
- [ ] Extract zip
- [ ] Set environment variables
- [ ] Application startup file = `server.js`
- [ ] Restart app
- [ ] Test domain

---

## 🚀 SOLUSI JANGKA PANJANG

**Kalau mau lebih mudah:**

### Opsi 1: Vercel (GRATIS & MUDAH)

```bash
# Install Vercel CLI
npm i -g vercel

# Deploy
vercel
```

**Keuntungan:**
- Auto build di cloud
- Auto deploy dari GitHub
- Zero config
- Gratis untuk personal project
- Support Node.js terbaru

### Opsi 2: VPS (DigitalOcean, AWS, dll)

**Keuntungan:**
- Full control
- Bisa build di server
- Gak ada limit process
- Lebih cepat

**Harga:** Mulai $5/bulan

---

## 📦 Script Bantuan

**File yang sudah dibuat:**

- `build-and-zip.sh` - Script otomatis build & zip
- `BUILD_DI_LOCAL_UPLOAD.md` - Panduan ini
- `CARA_FIX_SEKARANG.md` - Troubleshooting
- `FIX_NODE_VERSION_CPANEL.md` - Fix Node.js version

---

## ⚡ Quick Command (Local)

**Build & zip sekaligus:**

```bash
npm install && \
npx prisma generate && \
npm run build && \
zip -r wonderfultoba-production.zip .next node_modules prisma public src package.json package-lock.json next.config.ts server.js .env.production && \
echo "✅ DONE! Upload wonderfultoba-production.zip ke cPanel!"
```

Setelah upload → Extract → Restart app → Test domain! 🎉
