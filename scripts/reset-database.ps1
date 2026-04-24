# PowerShell Script untuk reset database development
# Gunakan dengan hati-hati! Script ini akan menghapus semua data

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Wonderful Toba - Database Reset Script" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Konfirmasi
Write-Host "PERINGATAN: Script ini akan menghapus semua data di database!" -ForegroundColor Yellow
Write-Host "Database: wonderfultoba_dev" -ForegroundColor Yellow
Write-Host ""
$confirm = Read-Host "Apakah Anda yakin ingin melanjutkan? (yes/no)"

if ($confirm -ne "yes") {
    Write-Host "Reset database dibatalkan." -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "[1/5] Menghapus Prisma Client lama..." -ForegroundColor Green
Remove-Item -Path "node_modules/.prisma" -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item -Path "node_modules/@prisma/client" -Recurse -Force -ErrorAction SilentlyContinue

Write-Host "[2/5] Generate Prisma Client baru..." -ForegroundColor Green
npx prisma generate

Write-Host "[3/5] Reset database (drop & recreate)..." -ForegroundColor Green
npx prisma db push --force-reset

Write-Host "[4/5] Seed data awal..." -ForegroundColor Green
npx prisma db seed

Write-Host "[5/5] Verifikasi database..." -ForegroundColor Green
npx prisma db pull

Write-Host ""
Write-Host "==========================================" -ForegroundColor Green
Write-Host "  Database berhasil di-reset!" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Kredensial Admin:"
Write-Host "  - Admin Umum:"
Write-Host "    Email: admin@wonderfultoba.com"
Write-Host "    Password: password123"
Write-Host ""
Write-Host "  - Admin Tour:"
Write-Host "    Email: tour@wonderfultoba.com"
Write-Host "    Password: password123"
Write-Host ""
Write-Host "  - Admin Outbound:"
Write-Host "    Email: outbound@wonderfultoba.com"
Write-Host "    Password: password123"
Write-Host ""
Write-Host "PENTING: Ganti password setelah login pertama!" -ForegroundColor Yellow
Write-Host ""
