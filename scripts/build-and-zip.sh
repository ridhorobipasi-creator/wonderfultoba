#!/bin/bash

# ============================================
# BUILD DI LOCAL & ZIP UNTUK UPLOAD KE CPANEL
# ============================================

echo "🚀 Building Next.js app di local..."
echo ""

# Install dependencies
echo "📦 Installing dependencies..."
npm install
echo ""

# Generate Prisma
echo "🔧 Generating Prisma client..."
npx prisma generate
echo ""

# Build Next.js
echo "🏗️ Building Next.js app..."
npm run build
echo ""

# Create zip file
echo "📦 Creating zip file..."
echo ""

# Hapus zip lama kalau ada
rm -f wonderfultoba-production.zip

# Zip file yang dibutuhkan
zip -r wonderfultoba-production.zip \
  .next \
  node_modules \
  prisma \
  public \
  src \
  package.json \
  package-lock.json \
  next.config.ts \
  server.js \
  .env.production \
  -x "node_modules/.cache/*" \
  -x ".next/cache/*" \
  -x "*.md" \
  -x ".git/*"

echo ""
echo "✅ DONE!"
echo ""
echo "📦 File: wonderfultoba-production.zip"
echo "📊 Size: $(du -h wonderfultoba-production.zip | cut -f1)"
echo ""
echo "🚀 NEXT STEPS:"
echo "1. Upload wonderfultoba-production.zip ke cPanel File Manager"
echo "2. Extract di folder: ~/nodeapps/wonderfultoba/"
echo "3. Restart app di Setup Node.js App"
echo "4. Test: https://wonderfultoba.com"
echo ""
