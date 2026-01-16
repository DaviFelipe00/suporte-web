# ==========================================
# Estágio 1: Dependências de Backend (PHP 8.4)
# ==========================================
FROM php:8.4-cli-alpine AS php_builder
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
# Instalando dependências de produção
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --ignore-platform-req=php+

# ==========================================
# Estágio 2: Dependências de Frontend (Node 20)
# ==========================================
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm install && npm run build

# ==========================================
# Estágio 3: Imagem Final de Produção
# ==========================================
FROM php:8.4-fpm-alpine

# Instalar extensões e dependências do sistema
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev

RUN docker-php-ext-install pdo_mysql mbstring gd zip opcache bcmath intl

# Configuração do Opcache para Produção
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

WORKDIR /var/www/html

# Copiar o código e dependências
COPY . .
COPY --from=php_builder /app/vendor ./vendor
COPY --from=node_builder /app/public/build ./public/build

# Ajuste de permissões para o usuário www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Logs do Nginx para o stdout/stderr do Docker
RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]