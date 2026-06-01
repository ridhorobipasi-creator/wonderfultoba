Deployment checklist and packaging instructions

Prerequisites
- PHP 8.1+ with Composer
- Node.js + npm (for frontend build)
- Write access to server and ability to run migrations

Local build steps
1. Install PHP deps:

   composer install --no-dev --optimize-autoloader

2. Install Node deps and build assets:

   npm install
   npm run build

3. Cache config & routes (production):

   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

Create deploy artifact (Windows PowerShell)
- Run the packaging script included in `scripts/package-deploy.ps1`:

  powershell -NoProfile -ExecutionPolicy Bypass -File scripts\package-deploy.ps1

This will produce a zip under the `deploy/` folder named like `sujailaketoba-YYYYMMDD-HHMMSS.zip`.

What the artifact contains
- Project files (source), built frontend assets under `public/build`, `vendor` (Composer libs).
- Excludes: `node_modules`, `.git`, some caches and logs.

Deploy steps (example)
1. Upload the zip to your server and extract into the webroot.
2. Ensure `.env` is present and correct on the server (do NOT include local `.env` in artifact).
3. On server run:

   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan storage:link

4. Set proper permissions for storage and bootstrap/cache.

Rollback/Notes
- Keep DB backups before running migrations on production.
- If using shared hosting (FTP), upload the `public` folder contents to the public_html and other files to above-root.
