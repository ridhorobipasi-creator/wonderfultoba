#!/bin/bash

# Quick Deploy Script untuk cPanel
# Script ini untuk update aplikasi yang sudah running

echo "=========================================="
echo "  Wonderful Toba - Quick Deploy"
echo "=========================================="
echo ""

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Deploying updates...${NC}"
echo ""

echo -e "${GREEN}[1/5] Pull latest code...${NC}"
git pull origin main

echo -e "${GREEN}[2/5] Install dependencies...${NC}"
npm install --production

echo -e "${GREEN}[3/5] Update Prisma Client...${NC}"
npx prisma generate

echo -e "${GREEN}[4/5] Migrate database (if needed)...${NC}"
npx prisma db push

echo -e "${GREEN}[5/5] Rebuild application...${NC}"
npm run build

echo ""
echo -e "${GREEN}Deploy selesai!${NC}"
echo ""
echo "Restart aplikasi dengan:"
echo "  pm2 restart wonderfultoba"
echo ""
