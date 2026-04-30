# ⚡ Quick Reference - Wonderful Toba

> Cheat sheet untuk command dan workflow yang sering digunakan

---

## 🚀 Quick Start

```bash
# Clone & Setup
git clone <repo-url> && cd wonderfultoba
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite && php artisan migrate --seed
php artisan serve
```

---

## 📦 Common Commands

### Development

```bash
# Start dev server (all-in-one)
composer dev

# Or manual:
php artisan serve              # Laravel server
npm run dev                    # Vite hot reload
php artisan queue:listen       # Queue worker
php artisan pail               # Log viewer
```

### Database

```bash
# Migrations
php artisan migrate            # Run migrations
php artisan migrate:fresh      # Fresh migration
php artisan migrate:rollback   # Rollback last
php artisan migrate:reset      # Rollback all

# Seeding
php artisan db:seed            # Run all seeders
php artisan db:seed --class=PackageSeeder  # Specific seeder
php artisan migrate:fresh --seed           # Fresh + seed
```

### Cache

```bash
# Clear cache
php artisan cache:clear        # Application cache
php artisan config:clear       # Config cache
php artisan route:clear        # Route cache
php artisan view:clear         # View cache

# Create cache
php artisan config:cache       # Cache config
php artisan route:cache        # Cache routes
php artisan view:cache         # Cache views
```

### Testing

```bash
# Run tests
composer test                  # All tests
php artisan test              # PHPUnit
php artisan test --filter=PackageTest  # Specific test
php artisan test --coverage   # With coverage
```

### Code Quality

```bash
# Code style
./vendor/bin/pint             # Fix code style
./vendor/bin/pint --test      # Check only
```

---

## 🗄️ Database Quick Reference

### Models

```php
// Query
Package::all()
Package::find(1)
Package::where('status', 'active')->get()
Package::with('city')->get()

// Create
Package::create([...])

// Update
$package->update([...])

// Delete
$package->delete()
```

### Relationships

```php
// One-to-Many
$package->city              // Get city
$city->packages             // Get packages

// Has Many
$package->bookings          // Get bookings
$booking->package           // Get package
```

---

## 🌐 API Quick Reference

### Authentication

```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Use token
curl http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Endpoints

```bash
# Public
GET  /api/packages
GET  /api/cars
GET  /api/blogs
GET  /api/gallery
GET  /api/cities

# Protected
GET  /api/auth/me
GET  /api/dashboard
```

---

## 🎨 Blade Quick Reference

### Syntax

```blade
{{-- Comments --}}

{{ $variable }}              {{-- Escaped output --}}
{!! $html !!}               {{-- Unescaped output --}}

@if ($condition)
    ...
@elseif ($other)
    ...
@else
    ...
@endif

@foreach ($items as $item)
    {{ $item }}
@endforeach

@for ($i = 0; $i < 10; $i++)
    {{ $i }}
@endfor

@while ($condition)
    ...
@endwhile

@include('partial')
@extends('layout')
@section('content')
@yield('content')

<x-component :prop="$value" />
```

---

## 🔧 Alpine.js Quick Reference

### Directives

```html
<!-- Data -->
<div x-data="{ open: false }">

<!-- Show/Hide -->
<div x-show="open">
<div x-if="condition">

<!-- Events -->
<button @click="open = !open">
<input @input="handleInput">

<!-- Binding -->
<input x-model="value">
<div :class="{ 'active': isActive }">

<!-- Loops -->
<template x-for="item in items">
    <div x-text="item.name"></div>
</template>

<!-- Computed -->
<div x-data="{
    items: [],
    get total() {
        return this.items.length;
    }
}">
```

---

## 🔐 Artisan Quick Reference

### Make Commands

```bash
# Models
php artisan make:model Package
php artisan make:model Package -m        # With migration
php artisan make:model Package -mfs      # With migration, factory, seeder

# Controllers
php artisan make:controller PackageController
php artisan make:controller PackageController --resource

# Migrations
php artisan make:migration create_packages_table
php artisan make:migration add_column_to_packages

# Seeders
php artisan make:seeder PackageSeeder

# Factories
php artisan make:factory PackageFactory

# Tests
php artisan make:test PackageTest
php artisan make:test PackageTest --unit

# Middleware
php artisan make:middleware CheckRole

# Requests
php artisan make:request StorePackageRequest
```

### Other Commands

```bash
# Tinker (REPL)
php artisan tinker

# Routes
php artisan route:list

# Storage link
php artisan storage:link

# Queue
php artisan queue:work
php artisan queue:restart

# Schedule
php artisan schedule:run
```

---

## 📁 File Locations

```
wonderfultoba/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/               # Models
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # Migrations
│   └── seeders/              # Seeders
├── resources/
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Web routes
│   └── api.php               # API routes
├── public/                   # Public assets
├── storage/
│   ├── app/                  # File storage
│   └── logs/                 # Log files
└── tests/                    # Tests
```

---

## 🐛 Debugging

### Logs

```bash
# View logs
tail -f storage/logs/laravel.log

# Clear logs
> storage/logs/laravel.log

# Laravel Pail (live logs)
php artisan pail
```

### Debug Functions

```php
// Dump and die
dd($variable);

// Dump
dump($variable);

// Log
Log::info('Message', ['data' => $data]);
Log::error('Error', ['error' => $error]);

// Query log
DB::enableQueryLog();
// ... queries ...
dd(DB::getQueryLog());
```

---

## 🔍 Common Issues

### Permission Denied

```bash
sudo chown -R $USER:$USER .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
```

### Cache Issues

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

### Database Connection

```bash
# Check .env
cat .env | grep DB_

# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### 500 Error

```bash
# Check logs
tail -f storage/logs/laravel.log

# Check permissions
ls -la storage bootstrap/cache

# Clear cache
php artisan config:clear
```

---

## 🚀 Deployment

### Quick Deploy

```bash
# Pull changes
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Migrate
php artisan migrate --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

---

## 📊 Git Workflow

### Common Commands

```bash
# Update
git pull origin main

# Create branch
git checkout -b feature/name

# Commit
git add .
git commit -m "feat: description"

# Push
git push origin feature/name

# Merge
git checkout main
git merge feature/name

# Delete branch
git branch -d feature/name
```

### Commit Types

```
feat:     New feature
fix:      Bug fix
docs:     Documentation
style:    Code style
refactor: Code refactoring
test:     Tests
chore:    Maintenance
```

---

## 🔧 Environment Variables

### Essential .env

```env
APP_NAME="Wonderful Toba"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wonderfultoba
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## 📞 Quick Links

### Documentation
- [README.md](README.md) - Quick start
- [PROJECT.md](PROJECT.md) - Full docs
- [DEPLOYMENT.md](DEPLOYMENT.md) - Deploy guide

### External
- [Laravel Docs](https://laravel.com/docs)
- [Alpine.js Docs](https://alpinejs.dev)
- [Tailwind CSS](https://tailwindcss.com/docs)

---

## 💡 Tips & Tricks

### Performance

```bash
# Optimize autoloader
composer dump-autoload --optimize

# Cache everything
php artisan optimize

# Clear everything
php artisan optimize:clear
```

### Development

```bash
# Watch for changes
npm run dev

# Run in background
php artisan serve > /dev/null 2>&1 &

# Kill process
kill $(lsof -t -i:8000)
```

### Database

```php
// Eager loading (avoid N+1)
Package::with('city')->get();

// Chunk large datasets
Package::chunk(100, function($packages) {
    // Process
});

// Raw queries
DB::select('SELECT * FROM packages WHERE status = ?', ['active']);
```

---

## 🎯 Keyboard Shortcuts

### Artisan

```bash
# Alias for quick access
alias art='php artisan'
alias tinker='php artisan tinker'
alias migrate='php artisan migrate'
alias seed='php artisan db:seed'
```

### Git

```bash
alias gs='git status'
alias ga='git add'
alias gc='git commit'
alias gp='git push'
alias gl='git pull'
alias gco='git checkout'
```

---

## 📝 Quick Notes

### Remember

- Always backup before major changes
- Test locally before deploying
- Clear cache after config changes
- Use migrations for database changes
- Follow PSR-12 coding standards
- Write tests for new features
- Document your code
- Keep dependencies updated

### Don't

- ❌ Edit files in vendor/
- ❌ Commit .env file
- ❌ Push directly to main
- ❌ Skip migrations
- ❌ Ignore test failures
- ❌ Deploy without testing
- ❌ Forget to backup

---

## 🆘 Emergency Commands

### Site Down

```bash
# Enable maintenance mode
php artisan down

# Disable maintenance mode
php artisan up
```

### Rollback

```bash
# Rollback migration
php artisan migrate:rollback

# Rollback git
git reset --hard HEAD~1

# Restore database
mysql -u root -p wonderfultoba < backup.sql
```

### Clear Everything

```bash
# Nuclear option
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
php artisan optimize:clear
```

---

## 📞 Support

**Email:** dev@wonderfultoba.com  
**Docs:** See [DOCS_INDEX.md](DOCS_INDEX.md)  
**Issues:** GitHub Issues

---

**Quick Reference Version:** 1.0  
**Last Updated:** April 30, 2026

---

**⚡ Keep this handy for quick reference! ⚡**
