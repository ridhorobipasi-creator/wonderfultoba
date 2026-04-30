# 🏔️ Wonderful Toba - Laravel Monolith Architecture

> **Platform Wisata & Outbound Profesional Sumatera Utara**  
> Migrasi dari Next.js + Prisma ke Laravel 11 Monolith dengan Filament Admin

---

## 📋 Daftar Isi

- [Overview](#-overview)
- [Arsitektur Sistem](#-arsitektur-sistem)
- [Struktur Database](#-struktur-database)
- [Fitur Utama](#-fitur-utama)
- [Teknologi Stack](#-teknologi-stack)
- [Instalasi & Setup](#-instalasi--setup)
- [Struktur Folder](#-struktur-folder)
- [API Endpoints](#-api-endpoints)
- [Routing Web](#-routing-web)
- [Model & Relasi](#-model--relasi)
- [Panduan Development](#-panduan-development)
- [Deployment](#-deployment)

---

## 🎯 Overview

**Wonderful Toba** adalah platform digital untuk layanan wisata dan corporate outbound di Sumatera Utara, khususnya kawasan Danau Toba, Berastagi, dan Bukit Lawang.

### Status Migrasi
✅ **SELESAI** - Migrasi dari Next.js ke Laravel Monolith  
✅ **READY** - Siap untuk integrasi Filament Admin Panel  
✅ **OPTIMIZED** - Model Eloquent dengan casting JSON otomatis  
✅ **VERIFIED** - Routing, Controller, dan View telah diverifikasi

---

## 🏗️ Arsitektur Sistem

### Sebelum (Next.js + Prisma)
```
┌─────────────────┐
│   Next.js App   │
│  (Frontend SSR) │
└────────┬────────┘
         │
    ┌────▼─────┐
    │  Prisma  │
    │   ORM    │
    └────┬─────┘
         │
    ┌────▼─────┐
    │ Database │
    └──────────┘
```

### Sekarang (Laravel Monolith)
```
┌──────────────────────────────────┐
│      Laravel 11 Monolith         │
├──────────────────────────────────┤
│  ┌────────────┐  ┌────────────┐ │
│  │   Blade    │  │ Alpine.js  │ │
│  │   Views    │  │  (State)   │ │
│  └─────┬──────┘  └─────┬──────┘ │
│        │                │        │
│  ┌─────▼────────────────▼─────┐ │
│  │    Controllers (MVC)       │ │
│  │  - PublicController        │ │
│  │  - PublicApiController     │ │
│  │  - PDFController           │ │
│  └─────┬──────────────────────┘ │
│        │                         │
│  ┌─────▼──────────────────────┐ │
│  │   Eloquent Models          │ │
│  │  (Auto JSON Casting)       │ │
│  └─────┬──────────────────────┘ │
│        │                         │
│  ┌─────▼──────────────────────┐ │
│  │   SQLite Database          │ │
│  └────────────────────────────┘ │
└──────────────────────────────────┘
         │
    ┌────▼─────────┐
    │   Filament   │
    │  Admin Panel │
    │  (Optional)  │
    └──────────────┘
```

---

## 🗄️ Struktur Database

### Tabel Utama

| Tabel | Deskripsi | JSON Fields |
|-------|-----------|-------------|
| **users** | Admin & Customer | - |
| **cities** | Destinasi Wisata | - |
| **packages** | Paket Tour & Outbound | `images`, `includes`, `excludes`, `pricingDetails`, `itinerary`, `translations` |
| **cars** | Rental Mobil | `images`, `features`, `includes`, `pricingDetails`, `translations` |
| **bookings** | Pemesanan | `metadata` |
| **blogs** | Artikel & Berita | `tags` |
| **settings** | Konfigurasi Site | `value` (JSON) |
| **outbound_services** | Layanan Outbound | - |
| **outbound_videos** | Video Testimoni | - |
| **outbound_locations** | Lokasi Outbound | - |
| **clients** | Logo Klien | - |
| **gallery_images** | Galeri Foto | `tags` |
| **package_tiers** | Kategori Paket | - |

### Entity Relationship Diagram

```
┌─────────┐         ┌──────────┐
│  Users  │────┐    │  Cities  │
└─────────┘    │    └────┬─────┘
               │         │
               │    ┌────▼────────┐
               │    │  Packages   │
               │    └────┬────────┘
               │         │
          ┌────▼─────────▼───┐
          │    Bookings      │
          └──────────────────┘
               │
          ┌────▼─────┐
          │   Cars   │
          └──────────┘
```

---

## ✨ Fitur Utama

### 🎫 Tour & Travel
- ✅ Paket wisata Danau Toba, Berastagi, Bukit Lawang
- ✅ Filter berdasarkan kota, durasi, harga
- ✅ Detail paket dengan itinerary lengkap
- ✅ Download PDF itinerary
- ✅ Galeri foto destinasi
- ✅ Blog artikel wisata

### 🏢 Corporate Outbound
- ✅ Paket team building & gathering
- ✅ Lokasi hotel premium di Sumut
- ✅ Video testimoni klien
- ✅ Logo klien korporat
- ✅ Layanan outbound custom

### 🚗 Car Rental
- ✅ Rental mobil dengan/tanpa driver
- ✅ Filter berdasarkan kapasitas & transmisi
- ✅ Harga transparan
- ✅ Fitur & fasilitas lengkap

### 📊 Admin Features (Ready for Filament)
- ⏳ CRUD Packages, Cars, Bookings
- ⏳ Blog Management
- ⏳ Gallery Management
- ⏳ Settings Configuration
- ⏳ User Management
- ⏳ Dashboard Analytics

---

## 🛠️ Teknologi Stack

### Backend
- **Laravel 11** - PHP Framework
- **Eloquent ORM** - Database Management
- **Laravel Sanctum** - API Authentication
- **DomPDF** - PDF Generation
- **SQLite** - Development Database

### Frontend
- **Blade Templates** - Server-side Rendering
- **Alpine.js** - Reactive State Management
- **Tailwind CSS** - Utility-first CSS
- **Vite** - Asset Bundling

### Admin Panel (Ready to Install)
- **Filament v3.2** - Admin Panel Framework

### Development Tools
- **Laravel Pint** - Code Style Fixer
- **Laravel Pail** - Log Viewer
- **Concurrently** - Multi-process Runner

---

## 🚀 Instalasi & Setup

### Prerequisites
```bash
- PHP >= 8.3
- Composer
- Node.js >= 18
- NPM/Yarn
```

### Quick Start

```bash
# 1. Clone Repository
git clone <repository-url>
cd wonderfultoba

# 2. Install Dependencies
composer install
npm install

# 4. Setup Environment
cp .env.example .env
php artisan key:generate

# 5. Setup Database
touch database/database.sqlite
php artisan migrate --seed

# 6. Build Assets
npm run build

# 7. Run Development Server
php artisan serve
```

### Development Mode (dengan Hot Reload)

```bash
# Terminal 1: Laravel Server + Queue + Logs + Vite
composer dev

# Atau manual:
# Terminal 1
php artisan serve

# Terminal 2
npm run dev

# Terminal 3
php artisan queue:listen

# Terminal 4
php artisan pail
```

### Install Filament Admin (Optional)

```bash
composer require filament/filament:"^3.2" -W
php artisan filament:install --panels
```

---

## 📁 Struktur Folder

```
wonderfultoba/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Api/
│   │       │   └── PublicApiController.php    # REST API
│   │       ├── PublicController.php           # Web Routes
│   │       ├── PDFController.php              # PDF Generation
│   │       └── WebAuthController.php          # Authentication
│   ├── Models/
│   │   ├── Package.php                        # Tour Packages
│   │   ├── Car.php                            # Car Rental
│   │   ├── Booking.php                        # Bookings
│   │   ├── Blog.php                           # Blog Posts
│   │   ├── City.php                           # Destinations
│   │   ├── GalleryImage.php                   # Gallery
│   │   ├── OutboundService.php                # Outbound Services
│   │   ├── OutboundVideo.php                  # Video Testimonials
│   │   ├── OutboundLocation.php               # Outbound Locations
│   │   ├── Client.php                         # Corporate Clients
│   │   ├── Setting.php                        # Site Settings
│   │   ├── PackageTier.php                    # Package Categories
│   │   └── User.php                           # Users (Filament Ready)
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   └── 2026_04_29_000002_create_wonderfultoba_tables.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── data.json                          # Seed Data
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php                  # Main Layout
│       │   └── partials/                      # Header, Footer, etc.
│       ├── tour/
│       │   ├── index.blade.php                # Tour Landing
│       │   ├── packages.blade.php             # Package List
│       │   ├── package-detail.blade.php       # Package Detail
│       │   ├── gallery.blade.php              # Gallery
│       │   ├── blog.blade.php                 # Blog List
│       │   └── blog-detail.blade.php          # Blog Detail
│       ├── outbound/
│       │   ├── index.blade.php                # Outbound Landing
│       │   ├── packages.blade.php             # Outbound Packages
│       │   └── blog.blade.php                 # Outbound Blog
│       ├── cars/
│       │   └── index.blade.php                # Car Rental
│       ├── pages/
│       │   ├── about.blade.php                # About Us
│       │   ├── terms.blade.php                # Terms & Conditions
│       │   └── privacy.blade.php              # Privacy Policy
│       ├── pdf/
│       │   └── itinerary.blade.php            # PDF Template
│       ├── components/
│       │   └── package-card.blade.php         # Reusable Component
│       └── index.blade.php                    # Homepage
├── routes/
│   ├── web.php                                # Web Routes
│   ├── api.php                                # API Routes
│   └── console.php                            # Artisan Commands
├── public/
│   ├── storage/                               # Symlinked Storage
│   └── assets/                                # Static Assets
├── config/                                    # Configuration Files
├── composer.json                              # PHP Dependencies
├── package.json                               # Node Dependencies
└── .env                                       # Environment Variables
```

---

## 🌐 API Endpoints

### Public API (No Auth Required)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `POST` | `/api/auth/login` | Login & Get Token |
| `GET` | `/api/blogs` | List Blog Posts |
| `GET` | `/api/packages` | List Tour Packages |
| `GET` | `/api/cars` | List Cars |
| `GET` | `/api/bookings` | List Bookings |
| `GET` | `/api/outbound/services` | Outbound Services |
| `GET` | `/api/outbound/videos` | Video Testimonials |
| `GET` | `/api/outbound/locations` | Outbound Locations |
| `GET` | `/api/clients` | Corporate Clients |
| `GET` | `/api/gallery` | Gallery Images |
| `GET` | `/api/cities` | Destinations |
| `GET` | `/api/package-tiers` | Package Categories |
| `GET` | `/api/settings` | Site Settings |
| `GET` | `/api/stats` | Statistics |

### Protected API (Requires Sanctum Token)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/auth/me` | Get Current User |
| `GET` | `/api/dashboard` | Dashboard Data |

### Authentication Example

```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Response
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "role": "ADMIN"
  }
}

# Use Token
curl http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer 1|abc123..."
```

---

## 🗺️ Routing Web

### Public Routes

| Route | Controller Method | View |
|-------|-------------------|------|
| `GET /` | `PublicController@index` | `index.blade.php` |
| `GET /tour` | `PublicController@tour` | `tour/index.blade.php` |
| `GET /tour/packages` | `PublicController@tourPackages` | `tour/packages.blade.php` |
| `GET /tour/package/{slug}` | `PublicController@tourPackageDetail` | `tour/package-detail.blade.php` |
| `GET /tour/gallery` | `PublicController@tourGallery` | `tour/gallery.blade.php` |
| `GET /tour/blog` | `PublicController@tourBlog` | `tour/blog.blade.php` |
| `GET /tour/blog/{id}` | `PublicController@tourBlogDetail` | `tour/blog-detail.blade.php` |
| `GET /outbound` | `PublicController@outbound` | `outbound/index.blade.php` |
| `GET /outbound/packages` | `PublicController@outboundPackages` | `outbound/packages.blade.php` |
| `GET /outbound/blog` | `PublicController@outboundBlog` | `outbound/blog.blade.php` |
| `GET /cars` | `PublicController@carRental` | `cars/index.blade.php` |
| `GET /about` | `PublicController@about` | `pages/about.blade.php` |
| `GET /terms` | `PublicController@terms` | `pages/terms.blade.php` |
| `GET /privacy` | `PublicController@privacy` | `pages/privacy.blade.php` |
| `GET /download/itinerary/{slug}` | `PDFController@downloadItinerary` | PDF Download |

### Auth Routes

| Route | Controller Method | Deskripsi |
|-------|-------------------|-----------|
| `POST /login` | `WebAuthController@login` | Web Login |
| `POST /logout` | `WebAuthController@logout` | Web Logout |
| `POST /register` | `WebAuthController@register` | Web Register |

---

## 🎨 Model & Relasi

### Package Model

```php
class Package extends Model
{
    protected $fillable = [
        'slug', 'name', 'shortDescription', 'description',
        'locationTag', 'price', 'childPrice', 'priceDisplay',
        'duration', 'images', 'includes', 'excludes',
        'pricingDetails', 'itinerary', 'itineraryText',
        'dronePrice', 'droneLocation', 'notes', 'status',
        'isFeatured', 'isOutbound', 'sortOrder',
        'metaTitle', 'metaDescription', 'translations', 'cityId'
    ];

    protected $casts = [
        'images' => 'array',
        'includes' => 'array',
        'excludes' => 'array',
        'pricingDetails' => 'array',
        'itinerary' => 'array',
        'translations' => 'array',
        'isFeatured' => 'boolean',
        'isOutbound' => 'boolean',
        'price' => 'decimal:2',
        'childPrice' => 'decimal:2',
        'dronePrice' => 'decimal:2',
    ];

    // Relationships
    public function city() {
        return $this->belongsTo(City::class, 'cityId');
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'packageId');
    }
}
```

### Car Model

```php
class Car extends Model
{
    protected $fillable = [
        'name', 'type', 'capacity', 'transmission', 'fuel',
        'price', 'priceWithDriver', 'images', 'description',
        'terms', 'features', 'includes', 'status',
        'isFeatured', 'sortOrder', 'metaTitle',
        'metaDescription', 'pricingDetails', 'translations'
    ];

    protected $casts = [
        'images' => 'array',
        'features' => 'array',
        'includes' => 'array',
        'pricingDetails' => 'array',
        'translations' => 'array',
        'isFeatured' => 'boolean',
        'price' => 'decimal:2',
        'priceWithDriver' => 'decimal:2',
    ];

    public function bookings() {
        return $this->hasMany(Booking::class, 'carId');
    }
}
```

### Booking Model

```php
class Booking extends Model
{
    protected $fillable = [
        'userId', 'type', 'packageId', 'carId',
        'startDate', 'endDate', 'totalPrice',
        'customerName', 'customerEmail', 'customerPhone',
        'notes', 'metadata', 'status'
    ];

    protected $casts = [
        'metadata' => 'array',
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'totalPrice' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }

    public function package() {
        return $this->belongsTo(Package::class, 'packageId');
    }

    public function car() {
        return $this->belongsTo(Car::class, 'carId');
    }
}
```

---

## 👨‍💻 Panduan Development

### Menambah Paket Tour Baru

```php
// Via Tinker
php artisan tinker

Package::create([
    'slug' => 'danau-toba-3d2n',
    'name' => 'Danau Toba 3 Hari 2 Malam',
    'shortDescription' => 'Eksplorasi keindahan Danau Toba',
    'description' => 'Paket lengkap...',
    'price' => 1500000,
    'duration' => '3 Hari 2 Malam',
    'images' => ['/storage/toba-1.jpg', '/storage/toba-2.jpg'],
    'includes' => ['Hotel', 'Transportasi', 'Makan'],
    'excludes' => ['Tiket Pesawat'],
    'status' => 'active',
    'isOutbound' => false,
    'cityId' => 1
]);
```

### Menambah View Baru

```bash
# 1. Buat Blade File
touch resources/views/tour/new-page.blade.php

# 2. Tambah Route di routes/web.php
Route::get('/tour/new-page', [PublicController::class, 'newPage']);

# 3. Tambah Method di PublicController
public function newPage() {
    return view('tour.new-page');
}
```

### Custom Seeder

```php
// database/seeders/CustomSeeder.php
php artisan make:seeder CustomSeeder

public function run() {
    Package::factory()->count(10)->create();
}

// Run
php artisan db:seed --class=CustomSeeder
```

### Generate PDF Custom

```php
use Barryvdh\DomPDF\Facade\Pdf;

public function customPDF() {
    $data = ['title' => 'Custom PDF'];
    $pdf = Pdf::loadView('pdf.custom', $data);
    return $pdf->download('custom.pdf');
}
```

---

## 🚢 Deployment

### Production Checklist

```bash
# 1. Environment
cp .env.production.example .env
php artisan key:generate

# 2. Optimize
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Database
php artisan migrate --force

# 4. Storage Link
php artisan storage:link

# 5. Permissions
chmod -R 755 storage bootstrap/cache
```

### Server Requirements

```
- PHP >= 8.3
- BCMath Extension
- Ctype Extension
- Fileinfo Extension
- JSON Extension
- Mbstring Extension
- OpenSSL Extension
- PDO Extension
- Tokenizer Extension
- XML Extension
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name wonderfultoba.com;
    root /var/www/wonderfultoba/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 📝 Catatan Penting

### JSON Field Handling

Semua field JSON di database **otomatis** di-cast ke array/object PHP oleh Eloquent:

```php
// ❌ TIDAK PERLU lagi manual decode
$package->images = json_decode($package->images, true);

// ✅ LANGSUNG akses sebagai array
foreach ($package->images as $image) {
    echo $image;
}
```

### Filament Integration

Model `User` sudah implement `FilamentUser` interface:

```php
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
}
```

### Alpine.js State Management

View menggunakan Alpine.js untuk reactive state:

```blade
<div x-data="{ 
    selectedCity: 'all',
    packages: @json($packages)
}">
    <select x-model="selectedCity">
        <option value="all">Semua Kota</option>
    </select>
    
    <template x-for="pkg in filteredPackages">
        <div x-text="pkg.name"></div>
    </template>
</div>
```

---

## 🎯 Roadmap

### Phase 1: Core Migration ✅
- [x] Migrasi database schema
- [x] Setup Eloquent models
- [x] Implement controllers
- [x] Create Blade views
- [x] Setup routing
- [x] PDF generation

### Phase 2: Admin Panel ⏳
- [ ] Install Filament
- [ ] Create Filament resources
- [ ] Setup dashboard
- [ ] User management
- [ ] Role & permissions

### Phase 3: Enhancement 📋
- [ ] Booking system integration
- [ ] Payment gateway
- [ ] Email notifications
- [ ] WhatsApp integration
- [ ] Multi-language support
- [ ] SEO optimization

### Phase 4: Production 🚀
- [ ] Performance optimization
- [ ] Security hardening
- [ ] Backup automation
- [ ] Monitoring setup
- [ ] CDN integration

---

## 📞 Support & Contact

**Developer:** Wonderful Toba Team  
**Email:** info@wonderfultoba.com  
**Website:** https://wonderfultoba.com

---

## 📄 License

Proprietary - © 2026 Wonderful Toba. All rights reserved.

---

**Last Updated:** April 30, 2026  
**Version:** 2.0.0 (Laravel Monolith)  
**Status:** Production Ready ✅
