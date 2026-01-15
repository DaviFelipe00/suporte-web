# ==========================================
# Estágio 1: Dependências de Backend (PHP 8.4)
# ==========================================
FROM php:8.4-cli-alpine AS php_builder

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar apenas arquivos de dependências primeiro para aproveitar o cache das camadas
COPY composer.json composer.lock ./

# Instalar dependências sem scripts (scripts rodam no estágio final)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --ignore-platform-req=php+

# ==========================================
# Estágio 2: Dependências de Frontend (Node/Vite)
# ==========================================
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copiar arquivos de configuração de frontend
COPY package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm install && npm run build

# ==========================================
# Estágio 3: Imagem Final de Produção
# ==========================================
FROM php:8.4-fpm-alpine

# Instalar dependências do sistema e extensões PHP essenciais
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
    icu-dev \
    linux-headers

# Instalar extensões PHP (MySQL é o foco aqui conforme sua imagem de credenciais)
RUN docker-php-ext-install pdo_mysql mbstring gd zip opcache bcmath intl

WORKDIR /var/www/html

# Copiar o código do projeto
COPY . .

# Copiar dependências prontas dos estágios anteriores
COPY --from=php_builder /app/vendor ./vendor
COPY --from=node_builder /app/public/build ./public/build

# Configurar permissões para o Laravel (essencial para evitar erro 500)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar configurações de infra (Nginx e Supervisor)
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expor a porta do Nginx
EXPOSE 80

# O Supervisor gerencia o PHP-FPM e o Nginx simultaneamente
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]