# 🚀 Deployment Guide - Wonderful Toba

> Panduan lengkap deployment Laravel Monolith ke production server

---

## 📋 Daftar Isi

- [Server Requirements](#server-requirements)
- [Pre-Deployment Checklist](#pre-deployment-checklist)
- [Deployment Methods](#deployment-methods)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [Web Server Configuration](#web-server-configuration)
- [SSL/HTTPS Setup](#sslhttps-setup)
- [Performance Optimization](#performance-optimization)
- [Monitoring & Logging](#monitoring--logging)
- [Backup Strategy](#backup-strategy)
- [Troubleshooting](#troubleshooting)

---

## Server Requirements

### Minimum Specifications

```
CPU:     2 cores
RAM:     2GB
Storage: 20GB SSD
OS:      Ubuntu 22.04 LTS / Debian 11+
```

### Recommended Specifications

```
CPU:     4 cores
RAM:     4GB
Storage: 50GB SSD
OS:      Ubuntu 22.04 LTS
```

### Software Requirements

```bash
# PHP & Extensions
PHP >= 8.3
- BCMath Extension
- Ctype Extension
- Fileinfo Extension
- JSON Extension
- Mbstring Extension
- OpenSSL Extension
- PDO Extension
- Tokenizer Extension
- XML Extension
- GD Extension
- Zip Extension

# Web Server
Nginx >= 1.18 or Apache >= 2.4

# Database
MySQL >= 8.0 or PostgreSQL >= 13

# Process Manager
Supervisor

# Others
Composer >= 2.5
Node.js >= 18
Git
```

---

## Pre-Deployment Checklist

### Code Preparation

```bash
# 1. Run tests
composer test

# 2. Fix code style
./vendor/bin/pint

# 3. Clear development cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Build assets
npm run build

# 5. Commit changes
git add .
git commit -m "Prepare for production deployment"
git push origin main
```

### Security Audit

- [ ] Update all dependencies
- [ ] Review `.env` for sensitive data
- [ ] Check file permissions
- [ ] Enable HTTPS
- [ ] Configure CORS properly
- [ ] Set up firewall rules
- [ ] Disable debug mode
- [ ] Remove development tools

---

## Deployment Methods

### Method 1: Manual Deployment (Recommended for First Time)

#### Step 1: Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common \
    php8.3-mysql php8.3-pgsql php8.3-xml php8.3-curl php8.3-gd \
    php8.3-mbstring php8.3-zip php8.3-bcmath php8.3-intl

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Nginx
sudo apt install -y nginx

# Install MySQL
sudo apt install -y mysql-server

# Install Supervisor
sudo apt install -y supervisor

# Install Git
sudo apt install -y git
```

#### Step 2: Clone Repository

```bash
# Create directory
sudo mkdir -p /var/www/wonderfultoba
sudo chown -R $USER:$USER /var/www/wonderfultoba

# Clone repository
cd /var/www/wonderfultoba
git clone <repository-url> .
```

#### Step 3: Install Dependencies

```bash
# PHP dependencies
composer install --optimize-autoloader --no-dev

# Node dependencies
npm ci
npm run build
```

#### Step 4: Environment Configuration

```bash
# Copy environment file
cp .env.production.example .env

# Generate application key
php artisan key:generate

# Edit environment variables
nano .env
```

#### Step 5: Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE wonderfultoba;
CREATE USER 'wonderfultoba'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON wonderfultoba.* TO 'wonderfultoba'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force

# Seed data (optional)
php artisan db:seed --force
```

#### Step 6: File Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/wonderfultoba

# Set permissions
sudo chmod -R 755 /var/www/wonderfultoba
sudo chmod -R 775 /var/www/wonderfultoba/storage
sudo chmod -R 775 /var/www/wonderfultoba/bootstrap/cache

# Create storage link
php artisan storage:link
```

#### Step 7: Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

---

### Method 2: Automated Deployment with Script

Create `deploy.sh`:

```bash
#!/bin/bash

# Configuration
APP_DIR="/var/www/wonderfultoba"
BRANCH="main"

echo "🚀 Starting deployment..."

# Navigate to app directory
cd $APP_DIR

# Enable maintenance mode
php artisan down

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin $BRANCH

# Install dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Clear and cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "💾 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔒 Setting permissions..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache

# Restart services
echo "🔄 Restarting services..."
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
sudo supervisorctl restart all

# Disable maintenance mode
php artisan up

echo "✅ Deployment completed successfully!"
```

Make executable and run:

```bash
chmod +x deploy.sh
./deploy.sh
```

---

### Method 3: CI/CD with GitHub Actions

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Checkout code
      uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.3'
    
    - name: Install dependencies
      run: |
        composer install --optimize-autoloader --no-dev
    
    - name: Run tests
      run: |
        php artisan test
    
    - name: Deploy to server
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.SERVER_HOST }}
        username: ${{ secrets.SERVER_USER }}
        key: ${{ secrets.SSH_PRIVATE_KEY }}
        script: |
          cd /var/www/wonderfultoba
          ./deploy.sh
```

---

## Environment Configuration

### Production `.env` Template

```env
# Application
APP_NAME="Wonderful Toba"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://wonderfultoba.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wonderfultoba
DB_USERNAME=wonderfultoba
DB_PASSWORD=strong_password_here

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@wonderfultoba.com
MAIL_FROM_NAME="${APP_NAME}"

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Security
SANCTUM_STATEFUL_DOMAINS=wonderfultoba.com
SESSION_DOMAIN=.wonderfultoba.com
```

---

## Database Setup

### MySQL Configuration

```bash
# Edit MySQL config
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Add/modify these settings
[mysqld]
max_connections = 200
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
query_cache_size = 0
query_cache_type = 0

# Restart MySQL
sudo systemctl restart mysql
```

### Database Backup Script

Create `backup-db.sh`:

```bash
#!/bin/bash

BACKUP_DIR="/var/backups/wonderfultoba"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="wonderfultoba"
DB_USER="wonderfultoba"
DB_PASS="your_password"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Keep only last 7 days
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +7 -delete

echo "Backup completed: db_$DATE.sql.gz"
```

Schedule with cron:

```bash
# Edit crontab
crontab -e

# Add daily backup at 2 AM
0 2 * * * /var/www/wonderfultoba/backup-db.sh
```

---

## Web Server Configuration

### Nginx Configuration

Create `/etc/nginx/sites-available/wonderfultoba`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name wonderfultoba.com www.wonderfultoba.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name wonderfultoba.com www.wonderfultoba.com;
    
    root /var/www/wonderfultoba/public;
    index index.php index.html;
    
    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/wonderfultoba.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/wonderfultoba.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
    
    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml font/truetype font/opentype application/vnd.ms-fontobject image/svg+xml;
    
    # Logging
    access_log /var/log/nginx/wonderfultoba-access.log;
    error_log /var/log/nginx/wonderfultoba-error.log;
    
    # Client upload size
    client_max_body_size 20M;
    
    # Main location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    
    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }
    
    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/wonderfultoba /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## SSL/HTTPS Setup

### Using Let's Encrypt (Free)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d wonderfultoba.com -d www.wonderfultoba.com

# Auto-renewal (already configured by certbot)
sudo certbot renew --dry-run
```

---

## Performance Optimization

### PHP-FPM Tuning

Edit `/etc/php/8.3/fpm/pool.d/www.conf`:

```ini
[www]
user = www-data
group = www-data
listen = /var/run/php/php8.3-fpm.sock

pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500

php_admin_value[memory_limit] = 256M
php_admin_value[upload_max_filesize] = 20M
php_admin_value[post_max_size] = 20M
```

Restart PHP-FPM:

```bash
sudo systemctl restart php8.3-fpm
```

### Redis Setup (Optional but Recommended)

```bash
# Install Redis
sudo apt install -y redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf

# Set maxmemory
maxmemory 256mb
maxmemory-policy allkeys-lru

# Restart Redis
sudo systemctl restart redis-server

# Update .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Queue Worker Setup

Create `/etc/supervisor/conf.d/wonderfultoba-worker.conf`:

```ini
[program:wonderfultoba-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/wonderfultoba/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/wonderfultoba/storage/logs/worker.log
stopwaitsecs=3600
```

Start worker:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start wonderfultoba-worker:*
```

---

## Monitoring & Logging

### Laravel Telescope (Development Only)

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### Log Rotation

Create `/etc/logrotate.d/wonderfultoba`:

```
/var/www/wonderfultoba/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### Server Monitoring

```bash
# Install monitoring tools
sudo apt install -y htop iotop nethogs

# Monitor PHP-FPM
sudo systemctl status php8.3-fpm

# Monitor Nginx
sudo systemctl status nginx

# Monitor MySQL
sudo systemctl status mysql

# Check logs
tail -f /var/log/nginx/wonderfultoba-error.log
tail -f /var/www/wonderfultoba/storage/logs/laravel.log
```

---

## Backup Strategy

### Full Backup Script

Create `full-backup.sh`:

```bash
#!/bin/bash

BACKUP_DIR="/var/backups/wonderfultoba"
DATE=$(date +%Y%m%d_%H%M%S)
APP_DIR="/var/www/wonderfultoba"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u wonderfultoba -p'password' wonderfultoba | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz \
    $APP_DIR/storage/app \
    $APP_DIR/public/storage \
    $APP_DIR/.env

# Keep only last 30 days
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +30 -delete
find $BACKUP_DIR -name "files_*.tar.gz" -mtime +30 -delete

echo "Backup completed: $DATE"
```

Schedule:

```bash
# Daily at 3 AM
0 3 * * * /var/www/wonderfultoba/full-backup.sh
```

---

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check Nginx logs
tail -f /var/log/nginx/wonderfultoba-error.log

# Check PHP-FPM logs
tail -f /var/log/php8.3-fpm.log

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### 2. Permission Denied

```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/wonderfultoba
sudo chmod -R 755 /var/www/wonderfultoba
sudo chmod -R 775 /var/www/wonderfultoba/storage
sudo chmod -R 775 /var/www/wonderfultoba/bootstrap/cache
```

#### 3. Database Connection Failed

```bash
# Test MySQL connection
mysql -u wonderfultoba -p wonderfultoba

# Check .env configuration
cat .env | grep DB_

# Restart MySQL
sudo systemctl restart mysql
```

#### 4. Queue Not Processing

```bash
# Check supervisor status
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart wonderfultoba-worker:*

# Check worker logs
tail -f storage/logs/worker.log
```

---

## Post-Deployment Verification

### Checklist

```bash
# 1. Check website is accessible
curl -I https://wonderfultoba.com

# 2. Check SSL certificate
openssl s_client -connect wonderfultoba.com:443 -servername wonderfultoba.com

# 3. Test API endpoints
curl https://wonderfultoba.com/api/packages

# 4. Check database connection
php artisan tinker
>>> DB::connection()->getPdo();

# 5. Verify queue workers
sudo supervisorctl status

# 6. Check logs for errors
tail -n 100 storage/logs/laravel.log

# 7. Test file uploads
# Upload test image via admin panel

# 8. Performance test
ab -n 1000 -c 10 https://wonderfultoba.com/
```

---

## Rollback Procedure

If deployment fails:

```bash
# 1. Enable maintenance mode
php artisan down

# 2. Revert to previous commit
git reset --hard HEAD~1

# 3. Restore database backup
gunzip < /var/backups/wonderfultoba/db_YYYYMMDD_HHMMSS.sql.gz | mysql -u wonderfultoba -p wonderfultoba

# 4. Clear cache
php artisan config:clear
php artisan cache:clear

# 5. Disable maintenance mode
php artisan up
```

---

## Security Hardening

### Firewall Setup

```bash
# Install UFW
sudo apt install -y ufw

# Allow SSH
sudo ufw allow 22/tcp

# Allow HTTP/HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable
```

### Fail2Ban Setup

```bash
# Install Fail2Ban
sudo apt install -y fail2ban

# Configure
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo nano /etc/fail2ban/jail.local

# Restart
sudo systemctl restart fail2ban
```

---

## Maintenance Mode

```bash
# Enable maintenance mode
php artisan down --message="Scheduled maintenance" --retry=60

# Enable with secret bypass
php artisan down --secret="bypass-token"
# Access: https://wonderfultoba.com/bypass-token

# Disable maintenance mode
php artisan up
```

---

**Deployment Guide Version:** 1.0  
**Last Updated:** April 30, 2026  
**Status:** Production Ready ✅
