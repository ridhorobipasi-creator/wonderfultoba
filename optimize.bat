@echo off
echo ==============================================
echo WONDERFUL TOBA - PRODUCTION OPTIMIZATION SCRIPT
echo ==============================================
echo.

echo [1/5] Clearing all cached files...
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
echo.

echo [2/5] Creating optimal cache for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo.

echo [3/5] Linking storage if not linked...
php artisan storage:link
echo.

echo [4/5] Building frontend assets...
npm run build
echo.

echo [5/5] Checking environment...
php artisan env
echo.

echo ==============================================
echo DONE! 
echo Aplikasi siap di-zip dan diunggah ke cPanel.
echo ==============================================
pause
