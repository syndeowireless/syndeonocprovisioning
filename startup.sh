#!/bin/bash
 
echo "=== STARTING CUSTOM DEPLOYMENT SCRIPT ==="
 
# CHECKS IF NODE IS INSTALLED, IF NOT - INSTALL NODE
if ! command -v node &> /dev/null; then
    echo "Setting up Node.js..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt-get install -y nodejs
fi
 
# Navigate to application directory
cd /home/site/wwwroot
 
echo "=== INSTALLING NPM DEPENDENCIES ==="
# Install dependencies
npm install --no-audit --progress=false
 
echo "=== BUILDING FRONTEND ASSETS ==="
npm run build
 
echo "=== RUNNING SAFE DATABASE MIGRATIONS ==="
# Run database migrations safely (only new migrations, don't drop tables)
# This is the KEY fix - using migrate instead of migrate:fresh
php artisan migrate --force
 
echo "=== OPTIMIZING LARAVEL FOR PRODUCTION ==="
# Clear and cache config for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
 
# Clear any existing caches first (important for production)
php artisan cache:clear
 
echo "=== DEPLOYMENT COMPLETE ==="
echo "Application is ready to serve requests"
 
# Don't start the server here - Azure will handle that
# Azure App Service will automatically start PHP-FPM
exit 0


