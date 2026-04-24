# 🚀 BUILD DI WINDOWS & UPLOAD KE CPANEL

## ⚡ CARA TERCEPAT (PowerShell)

### Opsi 1: Pakai Script Otomatis (RECOMMENDED)

**Jalankan di PowerShell:**

```powershell
.\build-and-zip.ps1
```

**Kalau error "cannot be loaded because running scripts is disabled":**

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
.\build-and-zip.ps1
```

---

### Opsi 2: Manual Step by Step

**Jalankan satu per satu di PowerShell:**

```powershell
# 1. Install dependencies
npm install

# 2. Generate Prisma
npx prisma generate

# 3. Build Next.js
npm run build

# 4. Zip file (pakai Compress-Archive)
Compress-Archive -Path .next,node_modules,prisma,public,src,package.json,package-lock.json,next.config.ts,server.js,.env.production -DestinationPath wonderfultoba-production.zip -Force

# 5. Done!
Write-Host "✅ DONE! Upload wonderfultoba-production.zip ke cPanel!"
```

---

### Opsi 3: Pakai 7-Zip atau WinRAR (Manual)

**Kalau gak mau pakai command:**

1. **Build dulu:**
   ```powershell
   npm install
   npx prisma generate
   npm run build
   ```

2. **Zip manual pakai 7-Zip/WinRAR:**
   
   Pilih folder/file ini:
   ```
   ✅ .next/
   ✅ node_modules/
   ✅ prisma/
   ✅ public/
   ✅ src/
   ✅ package.json
   ✅ package-lock.json
   ✅ next.config.ts
   ✅ server.js
   ✅ .env.production
   ```

3. **Klik kanan → Add to archive → Save as:** `wonderfultoba-production.zip`

---

## 📤 UPLOAD KE CPANEL

### Via File Manager (Mudah)

1. **Login cPanel** → **File Manager**
2. **Masuk folder:** `/home/medp7341/nodeapps/wonderfultoba/`
3. **Upload** `wonderfultoba-production.zip`
4. **Klik kanan zip → Extract**
5. **Pilih:** Extract ke folder yang sama
6. **Tunggu** sampai selesai

### Via FTP (Alternatif)

**Pakai FileZilla atau WinSCP:**

```
Host: ftp.wonderfultoba.com (atau IP server)
Username: medp7341
Password: (password cPanel)
Port: 21
```

**Upload ke:** `/home/medp7341/nodeapps/wonderfultoba/`

---

## 🔧 SETUP DI CPANEL

### 1. Set Environment Variables

**Di cPanel → Setup Node.js App → Environment variables:**

```
NODE_ENV=production
DATABASE_URL=postgresql://user:pass@host:5432/dbname
```

### 2. Set Application Startup File

```
Application startup file: server.js
```

❗ **JANGAN:** `app.js / server.js`  
✅ **ISI:** `server.js` aja!

### 3. Restart App

Klik tombol **Restart**

---

## 🧪 TEST

Buka: `https://wonderfultoba.com`

**Kalau berhasil:** Homepage Next.js muncul! 🎉  
**Kalau masih "It works":** Cek Application startup file = `server.js`

---

## 🔄 UPDATE KODE (Kalau Ada Perubahan)

**Setiap kali update kode:**

### Update Cepat (Cuma Kode)

```powershell
# Build
npm run build

# Zip cuma .next
Compress-Archive -Path .next -DestinationPath next-build.zip -Force

# Upload next-build.zip ke cPanel
# Extract di ~/nodeapps/wonderfultoba/
# Restart app
```

### Update Full (Ada Install Package Baru)

```powershell
# Install package baru
npm install

# Build
npm run build

# Zip semua
Compress-Archive -Path .next,node_modules,package.json,package-lock.json -DestinationPath update-full.zip -Force

# Upload & extract
# Restart app
```

---

## 💡 TIPS

### Zip Lebih Kecil (Exclude Cache)

**Kalau mau zip lebih kecil, exclude cache:**

```powershell
# Hapus cache dulu
Remove-Item -Recurse -Force .next\cache -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force node_modules\.cache -ErrorAction SilentlyContinue

# Baru zip
Compress-Archive -Path .next,node_modules,prisma,public,src,package.json,package-lock.json,next.config.ts,server.js -DestinationPath wonderfultoba-production.zip -Force
```

### Cek Size Zip

```powershell
$size = (Get-Item wonderfultoba-production.zip).Length / 1MB
Write-Host "Size: $([math]::Round($size, 2)) MB"
```

---

## 🚨 TROUBLESHOOTING

### Error: "Compress-Archive: The path is not of a legal form"

**Fix:** Pastikan semua file/folder ada

```powershell
# Cek file ada atau tidak
Test-Path .next
Test-Path node_modules
Test-Path server.js
```

### Error: "npm run build failed"

**Cek error message**, biasanya:
- Syntax error di kode
- Missing dependencies
- TypeScript error

**Fix:**

```powershell
npm run lint
npm run build
```

### Zip Terlalu Besar (>500MB)

**Exclude node_modules, install di server:**

```powershell
# Zip tanpa node_modules
Compress-Archive -Path .next,prisma,public,src,package.json,package-lock.json,next.config.ts,server.js -DestinationPath wonderfultoba-slim.zip -Force

# Upload ke server
# Di server, jalankan: npm install --production
```

---

## 🎯 CHECKLIST

- [ ] Build di local: `npm run build`
- [ ] Zip file yang dibutuhkan
- [ ] Upload ke cPanel File Manager
- [ ] Extract di `/home/medp7341/nodeapps/wonderfultoba/`
- [ ] Set environment variables
- [ ] Application startup file = `server.js`
- [ ] Restart app
- [ ] Test `https://wonderfultoba.com`

---

## 📦 File Script

**Yang sudah dibuat:**

- `build-and-zip.ps1` - Script PowerShell otomatis
- `BUILD_WINDOWS.md` - Panduan ini
- `BUILD_DI_LOCAL_UPLOAD.md` - Panduan umum

---

## ⚡ Quick Command (Copy Paste)

**PowerShell (satu per satu):**

```powershell
npm install
npx prisma generate
npm run build
Compress-Archive -Path .next,node_modules,prisma,public,src,package.json,package-lock.json,next.config.ts,server.js,.env.production -DestinationPath wonderfultoba-production.zip -Force
Write-Host "✅ DONE! Upload wonderfultoba-production.zip ke cPanel!" -ForegroundColor Green
```

Setelah upload → Extract → Restart app → Test domain! 🚀
