# 🚀 Deployment ke cPanel - Wonderful Toba

## 📋 Persiapan Sebelum Upload

### 1. Build Production
```bash
# Build aplikasi Next.js
npm run build

# Test production build locally
npm start
```

### 2. File yang Perlu Diupload
```
✅ .next/              (hasil build)
✅ public/             (assets)
✅ node_modules/       (dependencies)
✅ prisma/             (database schema)
✅ src/                (source code - optional)
✅ package.json
✅ package-lock.json
✅ next.config.ts
✅ .env.production     (environment variables)
```

### 3. File yang TIDAK Perlu Diupload
```
❌ .git/
❌ .next/cache/
❌ node_modules/.cache/
❌ .env.local
❌ *.md (dokumentasi)
```

---

## 🔧 Konfigurasi cPanel

### 1. Setup Node.js Application

#### A. Login ke cPanel
```
1. Buka cPanel
2. Cari "Setup Node.js App" atau "Node.js Selector"
3. Klik "Create Application"
```

#### B. Konfigurasi Aplikasi
```
Node.js Version:     18.x atau 20.x (pilih yang tersedia)
Application Mode:    Production
Application Root:    public_html/wonderfultoba (atau folder pilihan)
Application URL:     wonderfultoba.com (atau subdomain)
Application Startup: server.js
```

#### C. Environment Variables
Tambahkan di cPanel Node.js App:
```
DATABASE_URL=mysql://username:password@localhost:3306/database_name
JWT_SECRET=your-super-secret-jwt-key-here
NODE_ENV=production
NEXT_PUBLIC_API_URL=https://wonderfultoba.com
```

---

## 📦 Upload Files ke cPanel

### Metode 1: File Manager (Untuk file kecil)
```
1. Login cPanel
2. Buka File Manager
3. Navigate ke folder aplikasi
4. Upload file zip
5. Extract di server
```

### Metode 2: FTP (Recommended)
```
1. Gunakan FileZilla atau WinSCP
2. Connect ke FTP server
3. Upload semua file
4. Pastikan permission correct (755 untuk folder, 644 untuk file)
```

### Metode 3: Git Deploy (Best Practice)
```bash
# Di cPanel Terminal
cd public_html/wonderfultoba
git clone https://github.com/yourusername/wonderfultoba.git .
npm install
npm run build
```

---

## 🗄️ Setup Database MySQL

### 1. Buat Database di cPanel
```
1. Buka "MySQL Databases"
2. Buat database baru: wonderfultoba_db
3. Buat user baru: wonderfultoba_user
4. Set password yang kuat
5. Add user to database dengan ALL PRIVILEGES
```

### 2. Import Database Schema
```bash
# Via cPanel Terminal
cd public_html/wonderfultoba
npx prisma migrate deploy

# Atau via phpMyAdmin
# Import file: prisma/migrations/migration.sql
```

### 3. Seed Database (Optional)
```bash
npx prisma db seed
```

---

## ⚙️ Konfigurasi Server

### 1. Create server.js (Custom Server)
```javascript
// server.js
const { createServer } = require('http');
const { parse } = require('url');
const next = require('next');

const dev = process.env.NODE_ENV !== 'production';
const hostname = 'localhost';
const port = process.env.PORT || 3000;

const app = next({ dev, hostname, port });
const handle = app.getRequestHandler();

app.prepare().then(() => {
  createServer(async (req, res) => {
    try {
      const parsedUrl = parse(req.url, true);
      await handle(req, res, parsedUrl);
    } catch (err) {
      console.error('Error occurred handling', req.url, err);
      res.statusCode = 500;
      res.end('internal server error');
    }
  })
    .once('error', (err) => {
      console.error(err);
      process.exit(1);
    })
    .listen(port, () => {
      console.log(`> Ready on http://${hostname}:${port}`);
    });
});
```

### 2. Update package.json
```json
{
  "scripts": {
    "dev": "next dev",
    "build": "prisma generate && next build",
    "start": "node server.js",
    "postinstall": "prisma generate"
  }
}
```

### 3. Create .htaccess (untuk Apache)
```apache
# .htaccess
RewriteEngine On
RewriteRule ^$ http://127.0.0.1:3000/ [P,L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ http://127.0.0.1:3000/$1 [P,L]
```

---

## 🔐 Environment Variables

### Create .env.production
```env
# Database
DATABASE_URL="mysql://wonderfultoba_user:password@localhost:3306/wonderfultoba_db"

# JWT
JWT_SECRET="your-super-secret-jwt-key-minimum-32-characters-long"

# App
NODE_ENV=production
NEXT_PUBLIC_API_URL=https://wonderfultoba.com

# Upload
MAX_FILE_SIZE=5242880
UPLOAD_DIR=/home/username/public_html/wonderfultoba/public/storage

# Email (Optional)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-app-password
```

---

## 📁 Struktur Folder di cPanel

```
/home/username/
├── public_html/
│   └── wonderfultoba/
│       ├── .next/              (build output)
│       ├── node_modules/       (dependencies)
│       ├── public/
│       │   ├── assets/
│       │   └── storage/        (uploaded files)
│       ├── prisma/
│       ├── src/
│       ├── server.js
│       ├── package.json
│       ├── .env.production
│       └── .htaccess
```

---

## 🚀 Deployment Steps

### Step 1: Build Locally
```bash
# Di komputer lokal
npm run build
npm start  # Test dulu

# Jika OK, siap upload
```

### Step 2: Upload ke cPanel
```bash
# Via FTP atau File Manager
# Upload semua file kecuali node_modules
```

### Step 3: Install Dependencies di Server
```bash
# Via cPanel Terminal
cd public_html/wonderfultoba
npm install --production
```

### Step 4: Setup Database
```bash
# Generate Prisma Client
npx prisma generate

# Run migrations
npx prisma migrate deploy

# Seed (optional)
npx prisma db seed
```

### Step 5: Start Application
```bash
# Via cPanel Node.js App
# Klik "Start" atau "Restart"

# Atau via Terminal
npm start
```

### Step 6: Setup Domain
```
1. Buka cPanel DNS Zone Editor
2. Point domain ke aplikasi Node.js
3. Setup SSL (Let's Encrypt)
4. Test: https://wonderfultoba.com
```

---

## 🔍 Troubleshooting

### Error: "Cannot find module"
```bash
# Install ulang dependencies
rm -rf node_modules
npm install --production
```

### Error: "Database connection failed"
```bash
# Check DATABASE_URL di .env.production
# Pastikan MySQL user punya akses
# Test koneksi: npx prisma db pull
```

### Error: "Port already in use"
```bash
# Kill process
pkill -f node

# Atau ganti port di .env
PORT=3001
```

### Error: "Permission denied"
```bash
# Fix permissions
chmod -R 755 public_html/wonderfultoba
chmod 644 .env.production
```

### Error: "Module not found: Can't resolve 'fs'"
```javascript
// next.config.ts
export default {
  webpack: (config, { isServer }) => {
    if (!isServer) {
      config.resolve.fallback = {
        ...config.resolve.fallback,
        fs: false,
        net: false,
        tls: false,
      };
    }
    return config;
  },
};
```

---

## 📊 Performance Optimization

### 1. Enable Compression
```javascript
// server.js
const compression = require('compression');
app.use(compression());
```

### 2. Setup CDN untuk Assets
```
1. Upload public/assets ke CDN
2. Update image URLs
3. Use CDN URL di .env
```

### 3. Enable Caching
```apache
# .htaccess
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpg "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

---

## 🔒 Security Checklist

```
✅ Change default admin password
✅ Use strong JWT_SECRET
✅ Enable HTTPS/SSL
✅ Set secure file permissions
✅ Disable directory listing
✅ Setup firewall rules
✅ Regular backups
✅ Update dependencies
✅ Monitor error logs
```

---

## 📝 Post-Deployment Checklist

```
✅ Test homepage loads
✅ Test admin login
✅ Test database connection
✅ Test file upload
✅ Test booking creation
✅ Test email notifications (if enabled)
✅ Test all admin features
✅ Check error logs
✅ Setup monitoring
✅ Setup backups
```

---

## 🔄 Update/Redeploy

### Quick Update (Code Only)
```bash
# Upload changed files via FTP
# Restart app di cPanel Node.js App
```

### Full Redeploy
```bash
# Build locally
npm run build

# Upload .next folder
# Restart app
```

### Database Migration
```bash
# Upload new migration files
npx prisma migrate deploy
# Restart app
```

---

## 📞 Support

### cPanel Issues:
- Contact hosting provider
- Check cPanel documentation
- Check Node.js version compatibility

### Application Issues:
- Check error logs: `tail -f logs/error.log`
- Check Node.js logs di cPanel
- Enable debug mode temporarily

---

## 🎯 Alternative: Deploy ke VPS

Jika cPanel terbatas, pertimbangkan VPS:
- DigitalOcean ($5/month)
- Vultr ($5/month)
- Linode ($5/month)

Dengan VPS, Anda punya kontrol penuh dan performa lebih baik.

---

**Status:** Ready for cPanel Deployment ✅
**Estimated Time:** 30-60 minutes
**Difficulty:** Medium

**Good luck with deployment! 🚀**
