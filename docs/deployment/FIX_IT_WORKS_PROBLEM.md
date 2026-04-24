# 🔥 FIX: "It Works! NodeJS 18.20.8" Problem

## ❌ Masalah
Domain menampilkan "It works! NodeJS 18.20.8" → artinya Node.js hidup tapi app belum ke-load

---

## ✅ SOLUSI LENGKAP

### 1️⃣ Fix Application Startup File

**Di cPanel → Setup Node.js App:**

```
Application startup file: server.js
```

❗ **JANGAN ISI:** `app.js / server.js` (salah!)  
✅ **ISI:** `server.js` (satu file aja!)

Klik **Save** → Klik **Restart**

---

### 2️⃣ Pastikan File Ada di Server

**Cek di File Manager cPanel:**

```
/home/username/nodeapps/wonderfultoba/
```

**Harus ada:**
- ✅ `server.js` (sudah ada di project kamu)
- ✅ `package.json`
- ✅ `next.config.ts`
- ✅ folder `src/`
- ✅ folder `.next/` (hasil build)

---

### 3️⃣ Install Dependencies

**Via Terminal SSH atau Terminal di cPanel:**

```bash
cd ~/nodeapps/wonderfultoba
npm install
```

Tunggu sampai selesai (bisa 2-5 menit)

---

### 4️⃣ Build Next.js App

```bash
npm run build
```

Ini akan generate folder `.next/` yang dibutuhkan production

---

### 5️⃣ Set Environment Variables

**Di cPanel → Setup Node.js App → Environment variables:**

Tambahkan:

```
NODE_ENV=production
PORT=(port yang dikasih cPanel, biasanya auto)
DATABASE_URL=postgresql://user:pass@host:5432/dbname
```

---

### 6️⃣ Restart App

**Di cPanel → Setup Node.js App:**

Klik tombol **Restart**

---

### 7️⃣ Test

Buka: `https://wonderfultoba.com`

**Kalau berhasil:** akan muncul homepage Next.js kamu  
**Kalau masih "It works":** lanjut troubleshooting di bawah

---

## 🔍 TROUBLESHOOTING

### ❌ Error: EBADENGINE / npm install Gagal

**Kalau muncul error:**
```
EBADENGINE Unsupported engine
npm error signal SIGABRT
```

**Artinya:** Node.js version terlalu lama!

**Fix:** Lihat panduan lengkap di `FIX_NODE_VERSION_CPANEL.md`

**Quick fix:**
1. Upgrade Node.js ke 20+ di cPanel
2. Atau downgrade Next.js ke 15.1.6

---

### Cek Log Error

**Via Terminal:**

```bash
cd ~/nodeapps/wonderfultoba
pm2 logs
```

atau

```bash
tail -f ~/nodeapps/wonderfultoba/logs/error.log
```

### Test Manual

**Jalankan manual dulu:**

```bash
cd ~/nodeapps/wonderfultoba
NODE_ENV=production PORT=3000 node server.js
```

Kalau ada error, akan muncul di sini

### Cek Port

**Pastikan PORT di environment variables sama dengan yang di Node.js App setting**

---

## 🎯 CHECKLIST FINAL

- [ ] Application startup file = `server.js` (bukan `app.js / server.js`)
- [ ] File `server.js` ada di folder
- [ ] `npm install` sudah jalan
- [ ] `npm run build` sudah jalan (ada folder `.next/`)
- [ ] Environment variables sudah diset
- [ ] App sudah di-restart
- [ ] Domain sudah propagasi (cek di https://dnschecker.org)

---

## 💡 TIPS

**Kalau masih stuck:**

1. Screenshot isi folder `/home/username/nodeapps/wonderfultoba`
2. Screenshot setting Node.js App di cPanel
3. Copy paste error dari log

Biar bisa fix lebih spesifik! 🚀
