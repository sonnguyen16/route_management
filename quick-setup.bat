@echo off
echo ============================================
echo   LOCATION TRACKING - QUICK SETUP
echo ============================================
echo.

echo [1/4] Installing Pusher package...
composer require pusher/pusher-php-server
if %errorlevel% neq 0 (
    echo ERROR: Failed to install Pusher package
    pause
    exit /b 1
)

echo.
echo [2/4] Running migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo ERROR: Migration failed
    pause
    exit /b 1
)

echo.
echo [3/4] Clearing cache...
php artisan config:clear
php artisan cache:clear

echo.
echo [4/4] Building frontend assets...
npm run build
if %errorlevel% neq 0 (
    echo WARNING: npm build failed, try manually: npm run dev
)

echo.
echo ============================================
echo   SETUP COMPLETED!
echo ============================================
echo.
echo NEXT STEPS:
echo 1. Add environment variables to .env (see ENV_CONFIG_REQUIRED.txt)
echo 2. Open 3 terminals and run:
echo    - Terminal 1: php artisan reverb:start
echo    - Terminal 2: php artisan queue:work
echo    - Terminal 3: php artisan serve
echo.
echo 3. Test with demo accounts:
echo    - Leader: leader@example.com / password
echo    - Captain: captain1@example.com / password
echo.
pause
