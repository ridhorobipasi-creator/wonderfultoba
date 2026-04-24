# 🚀 Quick Start - Wonderful Toba

## Development (Local)

### 1. Clone & Install

```bash
# Clone repository
git clone <repository-url>
cd wonderfultoba

# Install dependencies
npm install
```

### 2. Setup Database

**Windows:**
```powershell
.\scripts\setup-dev.ps1
```

**Linux/Mac:**
```bash
chmod +x scripts/reset-database.sh
./scripts/reset-database.sh
```

**Atau manual:**
```bash
npm run db:setup
```

### 3. Run Development Server

```bash
npm run dev
```

Buka: http://localhost:3000

### 4. Login Admin

- URL: http://localhost:3000/admin
- Email: `admin@wonderfultoba.com`
- Password: `password123`

---

## Production (cPanel)

### 1. Persiapan

1. Buat database MySQL di cPanel
2. Update `.env.production`:
   ```env
   DATABASE_URL="mysql://user:pass@localhost:3306/dbname"
   JWT_SECRET="generate-random-string"
   NEXT_PUBLIC_SITE_URL="https://yourdomain.com"
   ```

### 2. Upload & Deploy

```bash
# Upload files ke server (via Git/FTP)
# SSH ke server, lalu:

cd ~/public_html
npm install --production
npx prisma generate
npx prisma db push
npx prisma db seed
npm run build
```

### 3. Start dengan PM2

```bash
npm install -g pm2
pm2 start npm --name "wonderfultoba" -- start
pm2 save
pm2 startup
```

### 4. Setup Node.js App di cPanel

- Node.js version: 18.x
- Application mode: Production
- Application root: public_html
- Startup file: server.js

---

## NPM Commands

| Command | Description |
|---------|-------------|
| `npm run dev` | Start development server |
| `npm run build` | Build for production |
| `npm start` | Start production server |
| `npm run db:setup` | Setup database (generate + push + seed) |
| `npm run db:reset` | Reset database (drop all + seed) |
| `npm run prisma:studio` | Open Prisma Studio GUI |

---

## Default Accounts

**Admin Umum:**
- Email: admin@wonderfultoba.com
- Password: password123

**Admin Tour:**
- Email: tour@wonderfultoba.com
- Password: password123

**Admin Outbound:**
- Email: outbound@wonderfultoba.com
- Password: password123

⚠️ **Ganti password setelah login pertama!**

---

## Troubleshooting

**Database error?**
```bash
npm run prisma:generate
npm run prisma:push
```

**Module not found?**
```bash
rm -rf node_modules package-lock.json
npm install
```

**Port 3000 in use?**
```bash
# Windows
netstat -ano | findstr :3000
taskkill /PID <PID> /F

# Linux/Mac
lsof -ti:3000 | xargs kill -9
```

---

## Documentation

- 📖 [Database Setup Guide](docs/DATABASE_RESET_GUIDE.md)
- 🚀 [Deployment Checklist](DEPLOYMENT_CHECKLIST.md)
- 📚 [Admin Panel Guide](docs/PANDUAN_ADMIN_CMS_LENGKAP.md)
- 🔧 [Production Setup](DATABASE_SETUP_PRODUCTION.md)

---

## Support

- Email: admin@wonderfultoba.com
- WhatsApp: +62 813-2388-8207
