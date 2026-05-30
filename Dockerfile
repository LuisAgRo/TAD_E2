FROM php:8.4-rc-fpm-alpine3.22

RUN apk add --no-cache nginx supervisor wget sqlite-dev nodejs npm

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_sqlite

RUN mkdir -p /run/nginx
RUN mkdir -p /app/database

COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY . /app

# Instalar Composer
RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"

# Instalar dependencias PHP
RUN cd /app && /usr/local/bin/composer install --no-dev

# Instalar dependencias de Node y compilar assets
RUN cd /app && npm install && npm run build

# Limpiar cache de npm para reducir tamaño
RUN cd /app && npm cache clean --force && rm -rf node_modules

# Configurar permisos
RUN chown -R www-data: /app
RUN chown -R www-data: /app/database
RUN chmod -R 755 /app/storage /app/database

CMD sh /app/docker/startup.sh