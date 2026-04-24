#!/bin/bash

# ============================================
# DEPLOYMENT SCRIPT FOR CPANEL
# ============================================
# This script will be run on cPanel after git pull
# It handles: install dependencies, build, and restart

echo "🚀 Starting deployment..."

# 1. Install dependencies
echo "📦 Installing dependencies..."
npm install --production

# 2. Generate Prisma Client
echo "🔧 Generating Prisma Client..."
npx prisma generate

# 3. Build Next.js
echo "🏗️  Building Next.js application..."
npm run build

# 4. Database migration (production)
echo "🗄️  Running database migrations..."
npx prisma db push --accept-data-loss

# 5. Restart application
echo "🔄 Restarting application..."
# cPanel will handle this automatically via Node.js app manager

echo "✅ Deployment completed successfully!"
echo "📍 Application should be running at: https://wonderfultoba.com"
