#CHECKS IF NODE IS INSTALLED, IF NOT - INSTALL NODE#
if ! command -v node &> /dev/null; then
    echo "Setting up Node.js..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt-get install -y nodejs
fi
 
cd /home/site/wwwroot
 
# Install dependencies
npm install --no-audit --progress=false
npm run build  #BUILD FOR PRODUCTION, DEV FOR LOCAL#
 
# Run database migrations safely (only new migrations, don't drop tables)
echo "Running database migrations..."
php artisan migrate --force
 
# Clear and cache config for better performance
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
 
echo "Starting server..."
php -S 0.0.0.0:8080 -t public