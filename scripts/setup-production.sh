#!/bin/bash

# Script untuk setup database production di cPanel
# Jalankan script ini setelah upload ke server

echo "=========================================="
echo "  Wonderful Toba - Production Setup"
echo "=========================================="
echo ""

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Cek apakah .env.production ada
if [ ! -f ".env.production" ]; then
    echo -e "${RED}Error: File .env.production tidak ditemukan!${NC}"
    echo "Silakan buat file .env.production terlebih dahulu."
    exit 1
fi

echo -e "${BLUE}Menggunakan konfigurasi dari .env.production${NC}"
echo ""

# Konfirmasi
echo -e "${YELLOW}Script ini akan:${NC}"
echo "  1. Install dependencies production"
echo "  2. Generate Prisma Client"
echo "  3. Push schema ke database"
echo "  4. Seed data awal"
echo "  5. Build Next.js application"
echo ""
read -p "Lanjutkan? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo -e "${RED}Setup dibatalkan.${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}[1/6] Install dependencies...${NC}"
npm install --production

echo ""
echo -e "${GREEN}[2/6] Generate Prisma Client...${NC}"
NODE_ENV=production npx prisma generate

echo ""
echo -e "${GREEN}[3/6] Push database schema...${NC}"
NODE_ENV=production npx prisma db push

echo ""
echo -e "${GREEN}[4/6] Seed data awal...${NC}"
NODE_ENV=production npx prisma db seed

echo ""
echo -e "${GREEN}[5/6] Build Next.js application...${NC}"
npm run build

echo ""
echo -e "${GREEN}[6/6] Setup selesai!${NC}"

echo ""
echo -e "${GREEN}=========================================="
echo "  Production Setup Berhasil!"
echo "==========================================${NC}"
echo ""
echo "Langkah selanjutnya:"
echo "  1. Setup Node.js App di cPanel"
echo "  2. Start aplikasi: npm start"
echo "  3. Atau gunakan PM2: pm2 start npm --name wonderfultoba -- start"
echo ""
echo "Kredensial Admin Default:"
echo "  Email: admin@wonderfultoba.com"
echo "  Password: password123"
echo ""
echo -e "${YELLOW}PENTING: Segera ganti password admin setelah login!${NC}"
echo ""
