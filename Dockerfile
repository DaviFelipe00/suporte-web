# Estágio 1: Dependências de Backend (Composer)
FROM composer:2.7 as php_builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress

# Estágio 2: Dependências de Frontend (Node/Vite)
FROM node:20-alpine as node_builder
WORKDIR /app
COPY package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm install && npm run build

# Estágio 3: Imagem Final (Produção)
FROM php:8.2-fpm-alpine

# Instalar dependências do sistema e extensões PHP necessárias para o Laravel
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    curl \
    sqlite-dev # Adicionado caso continue usando SQLite

RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring gd zip opcache bcmath

WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .
# Copiar dependências instaladas nos estágios anteriores
COPY --from=php_builder /app/vendor ./vendor
COPY --from=node_builder /app/public/build ./public/build

# Configurar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuração do Nginx
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf

# Configuração do Supervisor (para rodar Nginx e PHP-FPM juntos)
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Otimizações de produção
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]