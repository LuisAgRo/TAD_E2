#!/bin/sh

# Variables de entorno por defecto
PORT=${PORT:-80}
APP_ENV=${APP_ENV:-production}
APP_DEBUG=${APP_DEBUG:-false}
APP_KEY=${APP_KEY:-}
APP_URL=${APP_URL:-http://localhost}
DB_CONNECTION=${DB_CONNECTION:-sqlite}

# Generar .env si no existe
if [ ! -f /app/.env ]; then
    echo "Generando .env desde .env.example..."
    cp /app/.env.example /app/.env
    
    # Si se proporciona APP_KEY, añadirlo
    if [ -n "$APP_KEY" ]; then
        sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" /app/.env
    else
        # Generar APP_KEY si es necesario
        cd /app && php artisan key:generate --force 2>/dev/null || true
    fi
fi

# Reemplazar variables de entorno en .env si han sido proporcionadas
if [ -n "$APP_ENV" ]; then
    sed -i "s|^APP_ENV=.*|APP_ENV=$APP_ENV|" /app/.env
fi

if [ -n "$APP_DEBUG" ]; then
    sed -i "s|^APP_DEBUG=.*|APP_DEBUG=$APP_DEBUG|" /app/.env
fi

if [ -n "$APP_URL" ]; then
    sed -i "s|^APP_URL=.*|APP_URL=$APP_URL|" /app/.env
fi

if [ -n "$DB_CONNECTION" ]; then
    sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=$DB_CONNECTION|" /app/.env
fi

# Configurar nginx port
sed -i "s|LISTEN_PORT|$PORT|g" /etc/nginx/nginx.conf

# Generar base de datos si es SQLite
if [ "$DB_CONNECTION" = "sqlite" ] && [ ! -f /app/database/database.sqlite ]; then
    echo "Creando base de datos SQLite..."
    touch /app/database/database.sqlite
    chown www-data: /app/database/database.sqlite
    cd /app && php artisan migrate --force 2>/dev/null || true
fi

# Asegurar permisos correctos
chown -R www-data: /app/storage
chmod -R 755 /app/storage

# Iniciar supervisor
/usr/bin/supervisord -c /app/docker/supervisord.conf
