#!/bin/bash

#CHECKS IF NODE IS INSTALLED, IF NOT - INSTALL NODE#
if ! command -v node &> /dev/null; then
    echo "Setting up Node.js..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - 
    apt-get install -y nodejs
fi


cd /home/site/wwwroot

echo "=== MIGRATION STATUS ==="
php artisan migrate:status
echo "========================"


npm install --no-audit --progress=false
npm run build  #BUILD FOR PRODUCTION, DEV FOR LOCAL#


echo "Starting server..."
php -S 0.0.0.0:8080 -t public
