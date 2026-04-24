# PowerShell Script untuk setup development environment

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Wonderful Toba - Development Setup" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "[1/6] Install dependencies..." -ForegroundColor Green
npm install

Write-Host ""
Write-Host "[2/6] Generate Prisma Client..." -ForegroundColor Green
npx prisma generate

Write-Host ""
Write-Host "[3/6] Setup database..." -ForegroundColor Green
npx prisma db push

Write-Host ""
Write-Host "[4/6] Seed data awal..." -ForegroundColor Green
npx prisma db seed

Write-Host ""
Write-Host "[5/6] Build Next.js..." -ForegroundColor Green
npm run build

Write-Host ""
Write-Host "[6/6] Setup selesai!" -ForegroundColor Green

Write-Host ""
Write-Host "==========================================" -ForegroundColor Green
Write-Host "  Development Setup Berhasil!" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Jalankan development server:"
Write-Host "  npm run dev"
Write-Host ""
Write-Host "Kredensial Admin:"
Write-Host "  Email: admin@wonderfultoba.com"
Write-Host "  Password: password123"
Write-Host ""
