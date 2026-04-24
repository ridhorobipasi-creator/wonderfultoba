# 🚀 COMMAND FIX MANUAL - Copy Paste Aja!

## ⚡ Quick Fix untuk Node.js 18

**Copy paste command ini satu per satu:**

### 1. Masuk ke folder project
```bash
cd ~/nodeapps/wonderfultoba
```

### 2. Backup package.json
```bash
cp package.json package.json.backup
```

### 3. Edit package.json (downgrade Next.js)
```bash
sed -i 's/"next": "16.2.4"/"next": "15.1.6"/g' package.json
sed -i 's/"react": "19.2.4"/"react": "19.0.0"/g' package.json
sed -i 's/"react-dom": "19.2.4"/"react-dom": "19.0.0"/g' package.json
sed -i 's/"@types\/react": "\^19"/"@types\/react": "^18"/g' package.json
sed -i 's/"@types\/react-dom": "\^19"/"@types\/react-dom": "^18"/g' package.json
sed -i 's/"@tailwindcss\/postcss": "\^4"/"@tailwindcss\/postcss": "^3"/g' package.json
sed -i 's/"tailwindcss": "\^4"/"tailwindcss": "^3.4.17"/g' package.json
sed -i 's/"eslint-config-next": "16.2.4"/"eslint-config-next": "15.1.6"/g' package.json
```

### 4. Hapus file lama
```bash
rm -rf node_modules .next package-lock.json
rm -rf ~/nodevenv/nodeapps/wonderfultoba/18/lib/node_modules/@prisma
```

### 5. Install dependencies
```bash
npm install --legacy-peer-deps
```

### 6. Generate Prisma
```bash
npx prisma generate
```

### 7. Build app
```bash
npm run build
```

### 8. Cek hasil
```bash
echo "✅ DONE! Sekarang restart app di cPanel!"
```

---

## 🎯 ATAU Copy Paste Sekaligus (All-in-One)

```bash
cd ~/nodeapps/wonderfultoba && \
cp package.json package.json.backup && \
sed -i 's/"next": "16.2.4"/"next": "15.1.6"/g' package.json && \
sed -i 's/"react": "19.2.4"/"react": "19.0.0"/g' package.json && \
sed -i 's/"react-dom": "19.2.4"/"react-dom": "19.0.0"/g' package.json && \
sed -i 's/"@types\/react": "\^19"/"@types\/react": "^18"/g' package.json && \
sed -i 's/"@types\/react-dom": "\^19"/"@types\/react-dom": "^18"/g' package.json && \
sed -i 's/"@tailwindcss\/postcss": "\^4"/"@tailwindcss\/postcss": "^3"/g' package.json && \
sed -i 's/"tailwindcss": "\^4"/"tailwindcss": "^3.4.17"/g' package.json && \
sed -i 's/"eslint-config-next": "16.2.4"/"eslint-config-next": "15.1.6"/g' package.json && \
rm -rf node_modules .next package-lock.json && \
rm -rf ~/nodevenv/nodeapps/wonderfultoba/18/lib/node_modules/@prisma && \
npm install --legacy-peer-deps && \
npx prisma generate && \
npm run build && \
echo "✅ DONE! Restart app di cPanel sekarang!"
```

---

## 🔥 Setelah Selesai

1. **Restart app di cPanel:**
   - Setup Node.js App → Klik **Restart**

2. **Test:**
   - Buka `https://wonderfultoba.com`

3. **Kalau masih "It works":**
   - Cek Application startup file = `server.js` (bukan `app.js / server.js`)

---

## 💡 Kalau Mau Lebih Cepat

**Upgrade Node.js ke 20+ di cPanel:**
1. Setup Node.js App → Node.js version → Pilih **20.x**
2. Save → Restart
3. Jalankan:
```bash
cd ~/nodeapps/wonderfultoba && \
rm -rf node_modules .next package-lock.json && \
npm install && \
npm run build
```

Lebih simple & bisa pakai Next.js 16! 🚀
