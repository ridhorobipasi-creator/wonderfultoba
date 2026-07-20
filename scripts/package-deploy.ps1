<#
.SYNOPSIS
    Membuat artefak deploy (.zip) yang siap diunggah ke server.

.DESCRIPTION
    Menjalankan langkah build produksi lalu mengemas berkas yang perlu naik:
      1. composer install --no-dev --optimize-autoloader
      2. npm ci && npm run build
      3. Mengemas ke deploy/sujailaketoba-<timestamp>.zip

    Yang TIDAK ikut: .env, node_modules, .git, storage/logs, database lokal,
    dan berkas kredensial. Daftar lengkapnya ada di $excluded di bawah.

    Catatan: setelah skrip ini selesai, vendor/ tidak lagi berisi dev
    dependencies, jadi `php artisan test` tidak akan jalan sampai Anda
    menjalankan `composer install` lagi.

.EXAMPLE
    powershell -NoProfile -ExecutionPolicy Bypass -File scripts\package-deploy.ps1

.EXAMPLE
    # Lewati build ulang bila aset sudah dibangun
    .\scripts\package-deploy.ps1 -SkipBuild
#>

[CmdletBinding()]
param(
    [switch]$SkipBuild,
    [switch]$SkipComposer
)

$ErrorActionPreference = "Stop"
$projectRoot = Split-Path -Parent $PSScriptRoot
$deployDir = Join-Path $projectRoot "deploy"

function Write-Step($text) { Write-Host "`n=== $text ===" -ForegroundColor Cyan }
function Write-Ok($text)   { Write-Host "  $text" -ForegroundColor Green }
function Write-Warn($text) { Write-Host "  $text" -ForegroundColor Yellow }

Push-Location $projectRoot
try {

    # --- Pemeriksaan awal --------------------------------------------------

    Write-Step "Memeriksa kondisi repo"

    $dirty = & git status --porcelain
    if ($dirty) {
        Write-Warn "Masih ada perubahan yang belum di-commit:"
        $dirty | ForEach-Object { Write-Host "    $_" -ForegroundColor DarkGray }
        Write-Warn "Artefak akan dibuat dari kondisi saat ini, termasuk perubahan itu."
    } else {
        Write-Ok "Working tree bersih pada commit $(& git rev-parse --short HEAD)"
    }

    # --- Dependensi PHP ----------------------------------------------------

    if (-not $SkipComposer) {
        Write-Step "composer install --no-dev --optimize-autoloader"
        & composer install --no-dev --optimize-autoloader --no-interaction
        if ($LASTEXITCODE -ne 0) { throw "composer install gagal." }
        Write-Ok "Dependensi produksi terpasang"
        Write-Warn "phpunit kini tidak ada. Jalankan 'composer install' bila ingin menjalankan test lagi."
    }

    # --- Aset frontend -----------------------------------------------------

    if (-not $SkipBuild) {
        Write-Step "Membangun aset frontend"
        if (Test-Path (Join-Path $projectRoot "package-lock.json")) {
            & npm ci
        } else {
            & npm install
        }
        if ($LASTEXITCODE -ne 0) { throw "npm install gagal." }

        & npm run build
        if ($LASTEXITCODE -ne 0) { throw "npm run build gagal." }
        Write-Ok "Aset terbangun ke public/build"
    }

    if (-not (Test-Path (Join-Path $projectRoot "public/build/manifest.json"))) {
        throw "public/build/manifest.json tidak ada. Aset belum terbangun — jangan deploy tanpa ini."
    }

    # --- Kemas -------------------------------------------------------------

    Write-Step "Mengemas artefak"

    if (-not (Test-Path $deployDir)) {
        New-Item -ItemType Directory -Path $deployDir | Out-Null
    }

    $stamp = Get-Date -Format "yyyyMMdd-HHmmss"
    $zipPath = Join-Path $deployDir "sujailaketoba-$stamp.zip"
    $staging = Join-Path $env:TEMP "sujai-deploy-$stamp"

    # Jangan pernah ikut ke server: kredensial, berkas lokal, dan apa pun yang
    # akan menimpa konfigurasi produksi.
    $excluded = @(
        ".git", ".github", "node_modules", "deploy",
        ".env", ".env.example", ".env.backup",
        "ssh.md", "informasi.md", "ROTASI-RAHASIA.md",
        "storage\logs", "database\database.sqlite",
        "tests", "phpunit.xml",
        ".vscode", ".idea", "image.png"
    )

    New-Item -ItemType Directory -Path $staging | Out-Null

    Get-ChildItem -Path $projectRoot -Force | ForEach-Object {
        $name = $_.Name
        if ($excluded -contains $name) { return }
        Copy-Item -Path $_.FullName -Destination (Join-Path $staging $name) -Recurse -Force
    }

    # Buang sub-path yang dikecualikan dan mungkin ikut terbawa induknya.
    foreach ($sub in @("storage\logs", "database\database.sqlite")) {
        $p = Join-Path $staging $sub
        if (Test-Path $p) { Remove-Item $p -Recurse -Force }
    }
    # Pastikan storage/logs tetap ada sebagai folder kosong.
    New-Item -ItemType Directory -Path (Join-Path $staging "storage\logs") -Force | Out-Null

    # Jaring pengaman: kalau .env sampai lolos, hentikan sebelum dikemas.
    if (Test-Path (Join-Path $staging ".env")) {
        Remove-Item $staging -Recurse -Force
        throw "BERHENTI: .env ikut masuk staging. Artefak dibatalkan agar kredensial tidak terkirim."
    }

    Compress-Archive -Path (Join-Path $staging "*") -DestinationPath $zipPath -CompressionLevel Optimal
    Remove-Item $staging -Recurse -Force

    $sizeMb = [math]::Round((Get-Item $zipPath).Length / 1MB, 2)
    Write-Ok "Artefak dibuat: $zipPath ($sizeMb MB)"

    # --- Pengingat ---------------------------------------------------------

    Write-Step "Langkah berikutnya di server"
    Write-Host @"
  1. Unggah dan ekstrak zip ke webroot
  2. Pastikan .env di server sudah benar (artefak ini TIDAK membawanya)
  3. Untuk rilis mata uang MYR, tambahkan dulu ke .env server:
       PRICE_MIGRATION_MYR_IDR=4400
     Tanpa itu migrasi berhenti sendiri dan tidak mengubah apa pun.
  4. AMBIL DUMP DATABASE sebelum menjalankan migrasi.
  5. php artisan migrate --force
  6. php artisan config:cache; php artisan route:cache; php artisan view:cache
  7. Periksa satu pesanan lama: nominalnya harus sama persis seperti sebelum deploy.
"@ -ForegroundColor Gray

}
finally {
    Pop-Location
}
