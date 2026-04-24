#!/bin/bash

# Script untuk reset database development
# Gunakan dengan hati-hati! Script ini akan menghapus semua data

echo "=========================================="
echo "  Wonderful Toba - Database Reset Script"
echo "=========================================="
echo ""

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Konfirmasi
echo -e "${YELLOW}PERINGATAN: Script ini akan menghapus semua data di database!${NC}"
echo -e "${YELLOW}Database: wonderfultoba_dev${NC}"
echo ""
read -p "Apakah Anda yakin ingin melanjutkan? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo -e "${RED}Reset database dibatalkan.${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}[1/5] Menghapus Prisma Client lama...${NC}"
rm -rf node_modules/.prisma
rm -rf node_modules/@prisma/client

echo -e "${GREEN}[2/5] Generate Prisma Client baru...${NC}"
npx prisma generate

echo -e "${GREEN}[3/5] Reset database (drop & recreate)...${NC}"
npx prisma db push --force-reset

echo -e "${GREEN}[4/5] Seed data awal...${NC}"
npx prisma db seed

echo -e "${GREEN}[5/5] Verifikasi database...${NC}"
npx prisma db pull

echo ""
echo -e "${GREEN}=========================================="
echo "  Database berhasil di-reset!"
echo "==========================================${NC}"
echo ""
echo "Kredensial Admin:"
echo "  - Admin Umum:"
echo "    Email: admin@wonderfultoba.com"
echo "    Password: password123"
echo ""
echo "  - Admin Tour:"
echo "    Email: tour@wonderfultoba.com"
echo "    Password: password123"
echo ""
echo "  - Admin Outbound:"
echo "    Email: outbound@wonderfultoba.com"
echo "    Password: password123"
echo ""
echo -e "${YELLOW}PENTING: Ganti password setelah login pertama!${NC}"
echo ""
