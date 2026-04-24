# Setup Database Production untuk cPanel

## Langkah 1: Buat Database di cPanel

1. Login ke cPanel Anda
2. Buka **MySQL® Databases**
3. Buat database baru:
   - Nama: `wonderfultoba_prod` (atau sesuai keinginan)
   - Catat nama lengkap database (biasanya: `username_wonderfultoba_prod`)

4. Buat user database baru:
   - Username: `wonderfultoba_user`
   - Password: Generate password yang kuat
   - **CATAT username dan password ini!**

5. Tambahkan user ke database:
   - Pilih user yang baru dibuat
   - Pilih database yang baru dibuat
   - Berikan **ALL PRIVILEGES**

## Langkah 2: Update File .env.production

Edit file `.env.production` dan update baris berikut:

```env
DATABASE_URL="mysql://username_wonderfultoba_user:PASSWORD_ANDA@localhost:3306/username_wonderfultoba_prod"
```

Ganti:
- `username_wonderfultoba_user` dengan username database lengkap Anda
- `PASSWORD_ANDA` dengan password yang Anda buat
- `username_wonderfultoba_prod` dengan nama database lengkap Anda

**Contoh:**
```env
DATABASE_URL="mysql://wonw2577_wtuser:MyStr0ngP@ss!@localhost:3306/wonw2577_wonderfultoba"
```

## Langkah 3: Generate JWT Secret yang Kuat

Ganti nilai `JWT_SECRET` dengan string random yang kuat. Anda bisa generate dengan:

```bash
node -e "console.log(require('crypto').randomBytes(64).toString('hex'))"
```

Atau gunakan generator online: https://randomkeygen.com/

## Langkah 4: Deploy ke cPanel

### A. Upload Files

Upload semua file project ke cPanel via:
- File Manager cPanel, atau
- FTP/SFTP, atau
- Git deployment

### B. Install Dependencies

SSH ke server dan jalankan:

```bash
cd ~/public_html  # atau folder project Anda
npm install --production
```

### C. Setup Database

```bash
# Generate Prisma Client
npx prisma generate

# Push schema ke database (membuat tabel)
npx prisma db push

# Seed data awal
npx prisma db seed
```

### D. Build Next.js

```bash
npm run build
```

### E. Setup .htaccess untuk Next.js

File `.htaccess` sudah disediakan di root project. Pastikan berisi:

```apache
RewriteEngine On
RewriteBase /

# Redirect semua request ke Next.js server
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ http://localhost:3000/$1 [P,L]
```

### F. Setup Node.js Application di cPanel

1. Buka **Setup Node.js App** di cPanel
2. Klik **Create Application**
3. Isi form:
   - **Node.js version**: 18.x atau lebih baru
   - **Application mode**: Production
   - **Application root**: public_html (atau folder Anda)
   - **Application URL**: domain Anda
   - **Application startup file**: server.js atau next start
   - **Environment variables**: Copy dari .env.production

4. Klik **Create**

### G. Start Application

```bash
npm start
```

Atau gunakan PM2 untuk auto-restart:

```bash
npm install -g pm2
pm2 start npm --name "wonderfultoba" -- start
pm2 save
pm2 startup
```

## Langkah 5: Verifikasi

1. Buka website Anda di browser
2. Test halaman utama: `https://yourdomain.com`
3. Test admin login: `https://yourdomain.com/admin`
   - Email: `admin@wonderfultoba.com`
   - Password: `password123`

4. **PENTING**: Segera ganti password admin setelah login pertama!

## Troubleshooting

### Error: "Can't reach database server"

- Cek kredensial database di `.env.production`
- Pastikan user database memiliki privileges yang cukup
- Cek apakah MySQL service berjalan

### Error: "Module not found"

```bash
rm -rf node_modules package-lock.json
npm install --production
```

### Error: "Prisma Client not generated"

```bash
npx prisma generate
```

### Website tidak muncul

- Cek apakah Node.js app sudah running di cPanel
- Cek error log di cPanel
- Pastikan port 3000 tidak diblokir firewall

## Security Checklist

- ✅ Ganti JWT_SECRET dengan nilai random yang kuat
- ✅ Ganti password admin default
- ✅ Pastikan .env.production tidak ter-commit ke Git
- ✅ Enable HTTPS/SSL di cPanel
- ✅ Setup firewall rules
- ✅ Regular backup database
- ✅ Update dependencies secara berkala

## Maintenance

### Backup Database

```bash
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

### Update Application

```bash
git pull origin main
npm install --production
npx prisma generate
npx prisma db push
npm run build
pm2 restart wonderfultoba
```

### Monitor Logs

```bash
pm2 logs wonderfultoba
```

## Support

Jika ada masalah, hubungi:
- Email: admin@wonderfultoba.com
- WhatsApp: +62 813-2388-8207
