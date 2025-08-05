#!/bin/bash

#CHECKS IF NODE IS INSTALLED, IF NOT - INSTALL NODE#
#if ! command -v node &> /dev/null; then
#    echo "Setting up Node.js..."
#    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - 
#    apt-get install -y nodejs
#fi
#
#
#cd /home/site/wwwroot
#
#echo "=== MIGRATION STATUS ==="
#php artisan migrate:status
#echo "========================"
#
#
#npm install --no-audit --progress=false
#npm run build  #BUILD FOR PRODUCTION, DEV FOR LOCAL#
#
#
#echo "Starting server..."
#php -S 0.0.0.0:8080 -t public

#!/bin/bash

# Fail on first error
set -e

echo "==== Iniciando startup do Laravel ===="

# 1. Instala dependências do Composer
if [ -f composer.json ]; then
    echo "Instalando dependências do Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 2. Instala dependências do NPM/Yarn (se existir package.json)
if [ -f package.json ]; then
    echo "Instalando dependências do NPM..."
    npm install --no-audit --no-fund
    echo "Compilando assets..."
    npm run build || npm run prod || npm run dev
fi

# 3. Gera chave do Laravel
echo "Gerando chave do app Laravel..."
php artisan key:generate --force

# 4. Rodar migrations SEM truncar tabelas!
echo "Rodando migrations..."
php artisan migrate --force

# 5. Rodar seeders (opcional, remova se não quiser popular dados em produção)
echo "Rodando seeders..."
php artisan db:seed --force

# 6. Criar link simbólico do storage
echo "Criando storage link..."
php artisan storage:link

# 7. Permissões das pastas storage e bootstrap/cache
echo "Ajustando permissões..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

echo "==== Startup do Laravel finalizado com sucesso! ===="

# 8. Iniciar servidor (somente se necessário, Azure geralmente gerencia isso)
# php artisan serve --host=0.0.0.0 --port=8000

# Fim do script