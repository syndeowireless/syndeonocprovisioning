# name this file as "startup.sh" and call it from "startup command" as "/home/startup.sh"
# check out my YouTube video "https://youtu.be/-PGhVFsOnGA"
cp /home/default /etc/nginx/sites-enabled/default

cp /home/php.ini /usr/local/etc/php/conf.d/php.ini


# install support for webp file conversion
apt-get update --allow-releaseinfo-change && apt-get install -y libfreetype6-dev \
                libjpeg62-turbo-dev \
                libpng-dev \
                libwebp-dev \
        && docker-php-ext-configure gd --with-freetype --with-webp  --with-jpeg
docker-php-ext-install gd

# install support for queue
apt-get install -y supervisor 

cp /home/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# restart nginx
service nginx restart
service supervisor restart

php /home/site/wwwroot/artisan down --refresh=15 --secret="1630542a-246b-4b66-afa1-dd72a4c43515"

php /home/site/wwwroot/artisan migrate --force

# Clear caches
php /home/site/wwwroot/artisan cache:clear

# Clear expired password reset tokens
#php /home/site/wwwroot/artisan auth:clear-resets

# Clear and cache routes
php /home/site/wwwroot/artisan route:cache

# Clear and cache config
php /home/site/wwwroot/artisan config:cache

# Clear and cache views
php /home/site/wwwroot/artisan view:cache

# Install node modules
# npm ci

# Build assets using Laravel Mix
# npm run production --silent

# uncomment next line if you dont have S3 or Blob storage
#php /home/site/wwwroot/artisan storage:link

# Turn off maintenance mode
php /home/site/wwwroot/artisan up

# run worker
nohup php /home/site/wwwroot/artisan queue:work &