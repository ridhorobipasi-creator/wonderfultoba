# ============================================
# BUILD DI LOCAL & ZIP UNTUK UPLOAD KE CPANEL
# PowerShell Script untuk Windows
# ============================================

Write-Host "🚀 Building Next.js app di local..." -ForegroundColor Green
Write-Host ""

# Install dependencies
Write-Host "📦 Installing dependencies..." -ForegroundColor Yellow
npm install
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ npm install failed!" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Generate Prisma
Write-Host "🔧 Generating Prisma client..." -ForegroundColor Yellow
npx prisma generate
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Prisma generate failed!" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Build Next.js
Write-Host "🏗️ Building Next.js app..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Build failed!" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Create zip file
Write-Host "📦 Creating zip file..." -ForegroundColor Yellow
Write-Host ""

# Hapus zip lama kalau ada
if (Test-Path "wonderfultoba-production.zip") {
    Remove-Item "wonderfultoba-production.zip" -Force
}

# Compress file yang dibutuhkan
$files = @(
    ".next",
    "node_modules",
    "prisma",
    "public",
    "src",
    "package.json",
    "package-lock.json",
    "next.config.ts",
    "server.js"
)

# Tambahkan .env.production kalau ada
if (Test-Path ".env.production") {
    $files += ".env.production"
}

Compress-Archive -Path $files -DestinationPath "wonderfultoba-production.zip" -Force

Write-Host ""
Write-Host "✅ DONE!" -ForegroundColor Green
Write-Host ""
Write-Host "📦 File: wonderfultoba-production.zip" -ForegroundColor Cyan
$size = (Get-Item "wonderfultoba-production.zip").Length / 1MB
Write-Host "📊 Size: $([math]::Round($size, 2)) MB" -ForegroundColor Cyan
Write-Host ""
Write-Host "🚀 NEXT STEPS:" -ForegroundColor Yellow
Write-Host "1. Upload wonderfultoba-production.zip ke cPanel File Manager"
Write-Host "2. Extract di folder: ~/nodeapps/wonderfultoba/"
Write-Host "3. Restart app di Setup Node.js App"
Write-Host "4. Test: https://wonderfultoba.com"
Write-Host ""
