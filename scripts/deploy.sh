#!/bin/bash

# Deployment Script untuk cPanel
# Usage: bash deploy.sh

echo "🚀 Starting deployment process..."

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Step 1: Install dependencies
echo -e "${YELLOW}📦 Installing dependencies...${NC}"
npm install --production

# Step 2: Generate Prisma Client
echo -e "${YELLOW}🔧 Generating Prisma Client...${NC}"
npx prisma generate

# Step 3: Build application
echo -e "${YELLOW}🏗️  Building application...${NC}"
npm run build

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Build successful!${NC}"
else
    echo -e "${RED}❌ Build failed!${NC}"
    exit 1
fi

# Step 4: Run database migrations
echo -e "${YELLOW}🗄️  Running database migrations...${NC}"
npx prisma migrate deploy

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Migrations successful!${NC}"
else
    echo -e "${YELLOW}⚠️  Migrations failed or no migrations to run${NC}"
fi

# Step 5: Set permissions
echo -e "${YELLOW}🔐 Setting file permissions...${NC}"
chmod -R 755 .next
chmod -R 755 public
chmod 644 .env.production

echo -e "${GREEN}✅ Deployment completed!${NC}"
echo -e "${YELLOW}📝 Next steps:${NC}"
echo "1. Start the application: npm start"
echo "2. Or restart via cPanel Node.js App"
echo "3. Test: http://localhost:3000"
