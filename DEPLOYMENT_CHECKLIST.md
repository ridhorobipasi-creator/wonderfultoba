# 🚀 Deployment Checklist - Wonderful Toba ke cPanel

## Pre-Deployment Checklist

### 1. Persiapan File

- [ ] Semua file sudah di-commit ke Git
- [ ] File `.env.production` sudah dikonfigurasi
- [ ] File `.htaccess` sudah ada di root
- [ ] Folder `public/storage` sudah ada dengan `.gitignore`
- [ ] Dependencies sudah up-to-date (`npm update`)

### 2. Database Setup di cPanel

- [ ] Database MySQL sudah dibuat
- [ ] User database sudah dibuat dengan password kuat
- [ ] User sudah di-assign ke database dengan ALL PRIVILEGES
- [ ] Kredensial database sudah dicatat

### 3. Environment Variables

Update file `.env.production`:

```env
# Database - UPDATE INI!
DATABASE_URL="mysql://cpanel_user:password@localhost:3306/cpanel_database"

# JWT Secret - GENERATE BARU!
JWT_SECRET="your-super-secret-random-string-here"

# Site URL - UPDATE INI!
NEXT_PUBLIC_SITE_URL="https://wonderfultoba.com"
NEXT_PUBLIC_SITE_NAME="Wonderful Toba"

# Contact Info
NEXT_PUBLIC_WA_NUMBER="6281323888207"
NEXT_PUBLIC_PHONE="+62 813-2388-8207"
NEXT_PUBLIC_EMAIL_TOUR="tour@wonderfultoba.com"
NEXT_PUBLIC_EMAIL_OUTBOUND="outbound@wonderfultoba.com"
```

**Generate JWT Secret:**
```bash
node -e "console.log(require('crypto').randomBytes(64).toString('hex'))"
```

---

## Deployment Steps

### Step 1: Upload Files ke cPanel

**Opsi A: Via Git (Recommended)**

```bash
# Di cPanel, buka Terminal dan clone repository
cd ~/public_html
git clone https://github.com/yourusername/wonderfultoba.git .

# Atau jika sudah ada, pull latest
git pull origin main
```

**Opsi B: Via FTP/File Manager**

Upload semua file kecuali:
- `node_modules/`
- `.next/`
- `.git/`
- `.env` (gunakan `.env.production`)

### Step 2: Setup Node.js di cPanel

1. Login ke cPanel
2. Cari **Setup Node.js App**
3. Klik **Create Application**

**Konfigurasi:**
```
Node.js version: 18.17.0 (atau lebih baru)
Application mode: Production
Application root: public_html (atau folder Anda)
Application URL: https://wonderfultoba.com
Application startup file: server.js
```

4. Klik **Create**

### Step 3: Install Dependencies

Di Terminal cPanel:

```bash
cd ~/public_html
npm install --production
```

**Jika error, coba:**
```bash
rm -rf node_modules package-lock.json
npm cache clean --force
npm install --production
```

### Step 4: Setup Database

```bash
# Copy .env.production ke .env
cp .env.production .env

# Generate Prisma Client
npx prisma generate

# Push schema ke database (buat tabel)
npx prisma db push

# Seed data awal
npx prisma db seed
```

**Verifikasi:**
```bash
# Cek apakah tabel sudah dibuat
npx prisma studio
```

### Step 5: Build Aplikasi

```bash
npm run build
```

**Jika error memory:**
```bash
NODE_OPTIONS="--max-old-space-size=4096" npm run build
```

### Step 6: Setup PM2 (Process Manager)

```bash
# Install PM2 globally
npm install -g pm2

# Start aplikasi
pm2 start npm --name "wonderfultoba" -- start

# Save PM2 configuration
pm2 save

# Setup auto-start on server reboot
pm2 startup
# Copy dan jalankan command yang muncul

# Cek status
pm2 status
pm2 logs wonderfultoba
```

### Step 7: Setup .htaccess

Pastikan file `.htaccess` di root berisi:

```apache
# Enable Rewrite Engine
RewriteEngine On
RewriteBase /

# Redirect HTTP to HTTPS (optional, tapi recommended)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Proxy semua request ke Next.js server
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ http://localhost:3000/$1 [P,L]

# Enable CORS (jika diperlukan)
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
</IfModule>
```

### Step 8: Setup SSL Certificate

1. Di cPanel, buka **SSL/TLS Status**
2. Klik **Run AutoSSL** untuk domain Anda
3. Tunggu hingga certificate ter-install

Atau gunakan Let's Encrypt manual:
```bash
certbot --apache -d wonderfultoba.com -d www.wonderfultoba.com
```

### Step 9: Verifikasi Deployment

- [ ] Buka https://wonderfultoba.com
- [ ] Test halaman utama
- [ ] Test halaman tour
- [ ] Test halaman outbound
- [ ] Test admin login: https://wonderfultoba.com/admin
- [ ] Test upload gambar di admin
- [ ] Test booking form
- [ ] Test responsive mobile

**Login Admin:**
- Email: `admin@wonderfultoba.com`
- Password: `password123`

**⚠️ SEGERA GANTI PASSWORD!**

---

## Post-Deployment

### 1. Ganti Password Admin

Login ke admin panel dan ganti semua password default:
- Admin Umum
- Admin Tour
- Admin Outbound

### 2. Setup Backup Otomatis

**Backup Database (Cron Job):**

Di cPanel > Cron Jobs, tambahkan:

```bash
# Backup database setiap hari jam 2 pagi
0 2 * * * mysqldump -u username -p'password' database_name > ~/backups/db_$(date +\%Y\%m\%d).sql
```

**Backup Files:**
```bash
# Backup files setiap minggu
0 3 * * 0 tar -czf ~/backups/files_$(date +\%Y\%m\%d).tar.gz ~/public_html
```

### 3. Setup Monitoring

**PM2 Monitoring:**
```bash
pm2 install pm2-logrotate
pm2 set pm2-logrotate:max_size 10M
pm2 set pm2-logrotate:retain 7
```

**Error Monitoring:**
```bash
# Cek logs
pm2 logs wonderfultoba --lines 100

# Monitor real-time
pm2 monit
```

### 4. Performance Optimization

**Enable Gzip Compression:**

Tambahkan di `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

**Enable Browser Caching:**
```apache
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

### 5. Security Hardening

- [ ] Ganti semua password default
- [ ] Enable firewall di cPanel
- [ ] Setup fail2ban untuk brute force protection
- [ ] Regular update dependencies: `npm update`
- [ ] Monitor error logs: `pm2 logs`
- [ ] Setup rate limiting di API routes

---

## Update Aplikasi (Future Deployments)

### Quick Update Script

```bash
cd ~/public_html

# Pull latest code
git pull origin main

# Install dependencies
npm install --production

# Update Prisma
npx prisma generate
npx prisma db push

# Rebuild
npm run build

# Restart
pm2 restart wonderfultoba

# Verify
pm2 logs wonderfultoba --lines 50
```

Atau gunakan script otomatis:
```bash
chmod +x scripts/quick-deploy.sh
./scripts/quick-deploy.sh
```

---

## Troubleshooting

### Aplikasi tidak bisa diakses

1. Cek PM2 status:
```bash
pm2 status
pm2 logs wonderfultoba
```

2. Cek port 3000:
```bash
netstat -tulpn | grep 3000
```

3. Restart aplikasi:
```bash
pm2 restart wonderfultoba
```

### Error 502 Bad Gateway

- Cek apakah Node.js app running
- Cek .htaccess proxy configuration
- Cek error logs di cPanel

### Database connection error

1. Verifikasi kredensial di `.env`
2. Test koneksi:
```bash
npx prisma db pull
```

3. Cek MySQL service:
```bash
systemctl status mysql
```

### Build error (out of memory)

```bash
# Increase Node.js memory
NODE_OPTIONS="--max-old-space-size=4096" npm run build
```

### Permission denied errors

```bash
# Fix permissions
chmod -R 755 ~/public_html
chown -R username:username ~/public_html
```

---

## Rollback Plan

Jika deployment gagal:

```bash
# Rollback ke commit sebelumnya
git log --oneline
git reset --hard <commit-hash>

# Rebuild
npm install --production
npm run build
pm2 restart wonderfultoba
```

---

## Support & Documentation

- **Database Setup:** `docs/DATABASE_RESET_GUIDE.md`
- **Production Setup:** `DATABASE_SETUP_PRODUCTION.md`
- **Admin Guide:** `docs/PANDUAN_ADMIN_CMS_LENGKAP.md`
- **API Docs:** `docs/API_DOCUMENTATION.md`

**Contact:**
- Email: admin@wonderfultoba.com
- WhatsApp: +62 813-2388-8207

---

## Deployment Success Criteria

- [ ] Website accessible via HTTPS
- [ ] All pages load correctly
- [ ] Admin panel accessible and functional
- [ ] Database connected and seeded
- [ ] Image uploads working
- [ ] Forms submitting correctly
- [ ] Mobile responsive
- [ ] SSL certificate active
- [ ] PM2 running and monitoring
- [ ] Backups configured
- [ ] All passwords changed from default

---

**Deployment Date:** _________________

**Deployed By:** _________________

**Production URL:** https://wonderfultoba.com

**Status:** ⬜ Success  ⬜ Failed  ⬜ Partial

**Notes:**
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
