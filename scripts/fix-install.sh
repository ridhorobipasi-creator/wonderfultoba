#!/bin/bash

# ============================================
# FIX npm install Error - Node.js 18
# ============================================

echo "🔥 Fixing npm install error..."
echo ""

# Check Node version
NODE_VERSION=$(node --version)
echo "Current Node.js: $NODE_VERSION"
echo ""

# Jika Node.js 18, harus downgrade Next.js
if [[ "$NODE_VERSION" == v18* ]]; then
    echo "❌ Node.js 18 detected - Next.js 16 tidak support!"
    echo "✅ Downgrading to Next.js 15.1.6..."
    echo ""
    
    # Backup package.json
    cp package.json package.json.backup
    
    # Replace Next.js version
    sed -i 's/"next": "16.2.4"/"next": "15.1.6"/g' package.json
    sed -i 's/"react": "19.2.4"/"react": "19.0.0"/g' package.json
    sed -i 's/"react-dom": "19.2.4"/"react-dom": "19.0.0"/g' package.json
    sed -i 's/"@types\/react": "\^19"/"@types\/react": "^18"/g' package.json
    sed -i 's/"@types\/react-dom": "\^19"/"@types\/react-dom": "^18"/g' package.json
    sed -i 's/"@tailwindcss\/postcss": "\^4"/"@tailwindcss\/postcss": "^3"/g' package.json
    sed -i 's/"tailwindcss": "\^4"/"tailwindcss": "^3.4.17"/g' package.json
    sed -i 's/"eslint-config-next": "16.2.4"/"eslint-config-next": "15.1.6"/g' package.json
    
    echo "✅ package.json updated!"
    echo ""
fi

# Clean install
echo "🧹 Cleaning old files..."
rm -rf node_modules
rm -rf .next
rm -rf package-lock.json
rm -rf /home/medp7341/nodevenv/nodeapps/wonderfultoba/18/lib/node_modules/@prisma
echo "✅ Cleaned!"
echo ""

# Install with legacy peer deps (bypass warnings)
echo "📦 Installing dependencies..."
npm install --legacy-peer-deps
echo ""

# Generate Prisma
echo "🔧 Generating Prisma client..."
npx prisma generate
echo ""

# Build app
echo "🏗️ Building Next.js app..."
npm run build
echo ""

echo "✅ DONE! Sekarang restart app di cPanel!"
echo ""
echo "Kalau masih error, upgrade Node.js ke 20+ di cPanel"
