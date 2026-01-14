# Estágio 1: Build dos Assets (Vite)
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Estágio 2: Ambiente PHP
FROM php:8.2-fpm-alpine

# Instalação de extensões necessárias e dependências de sistema
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql gd zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia arquivos do projeto
COPY . .
# Copia assets compilados do estágio anterior
COPY --from=assets /app/public/build ./public/build

# Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões para o Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Configuração do Nginx (precisamos criar este arquivo ou embutir aqui)
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf

EXPOSE 80

# Script de inicialização (migrações e caches)
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && nginx && php-fpm"]