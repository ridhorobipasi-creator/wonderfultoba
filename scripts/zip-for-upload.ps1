# ============================================
# ZIP FILE UNTUK UPLOAD KE CPANEL
# PowerShell Script untuk Windows
# ============================================

Write-Host "📦 Creating zip file for upload..." -ForegroundColor Yellow
Write-Host ""

# Hapus zip lama kalau ada
if (Test-Path "wonderfultoba-upload.zip") {
    Remove-Item "wonderfultoba-upload.zip" -Force
    Write-Host "🗑️ Removed old zip file" -ForegroundColor Gray
}

# List file/folder yang akan di-zip
$items = @()

# Cek dan tambahkan file/folder yang ada
if (Test-Path ".next") {
    $items += ".next"
    Write-Host "✅ .next/ found" -ForegroundColor Green
} else {
    Write-Host "❌ .next/ not found - run 'npm run build' first!" -ForegroundColor Red
}

if (Test-Path "src") {
    $items += "src"
    Write-Host "✅ src/ found (Next.js 13+ app directory)" -ForegroundColor Green
} elseif (Test-Path "app") {
    $items += "app"
    Write-Host "✅ app/ found" -ForegroundColor Green
} elseif (Test-Path "pages") {
    $items += "pages"
    Write-Host "✅ pages/ found" -ForegroundColor Green
}

if (Test-Path "public") {
    $items += "public"
    Write-Host "✅ public/ found" -ForegroundColor Green
}

if (Test-Path "prisma") {
    $items += "prisma"
    Write-Host "✅ prisma/ found" -ForegroundColor Green
}

if (Test-Path "package.json") {
    $items += "package.json"
    Write-Host "✅ package.json found" -ForegroundColor Green
}

if (Test-Path "package-lock.json") {
    $items += "package-lock.json"
    Write-Host "✅ package-lock.json found" -ForegroundColor Green
}

if (Test-Path "server.js") {
    $items += "server.js"
    Write-Host "✅ server.js found" -ForegroundColor Green
}

if (Test-Path "next.config.ts") {
    $items += "next.config.ts"
    Write-Host "✅ next.config.ts found" -ForegroundColor Green
} elseif (Test-Path "next.config.js") {
    $items += "next.config.js"
    Write-Host "✅ next.config.js found" -ForegroundColor Green
}

if (Test-Path ".env.production") {
    $items += ".env.production"
    Write-Host "✅ .env.production found" -ForegroundColor Green
} elseif (Test-Path ".env") {
    $items += ".env"
    Write-Host "✅ .env found" -ForegroundColor Green
}

Write-Host ""

# Cek apakah ada file yang akan di-zip
if ($items.Count -eq 0) {
    Write-Host "❌ No files to zip!" -ForegroundColor Red
    exit 1
}

# Compress file
Write-Host "🗜️ Compressing files..." -ForegroundColor Yellow
Compress-Archive -Path $items -DestinationPath "wonderfultoba-upload.zip" -Force

Write-Host ""
Write-Host "✅ DONE!" -ForegroundColor Green
Write-Host ""
Write-Host "📦 File: wonderfultoba-upload.zip" -ForegroundColor Cyan
$size = (Get-Item "wonderfultoba-upload.zip").Length / 1MB
Write-Host "📊 Size: $([math]::Round($size, 2)) MB" -ForegroundColor Cyan
Write-Host ""
Write-Host "🚀 NEXT STEPS:" -ForegroundColor Yellow
Write-Host "1. Upload wonderfultoba-upload.zip ke cPanel File Manager"
Write-Host "2. Extract di folder: /home/medp7341/nodeapps/wonderfultoba/"
Write-Host "3. Di cPanel → Setup Node.js App:"
Write-Host "   - Application startup file: server.js"
Write-Host "   - Environment variables: NODE_ENV=production, DATABASE_URL=..."
Write-Host "4. Restart app"
Write-Host "5. Test: https://wonderfultoba.com"
Write-Host ""
