FROM webdevops/php-nginx:8.3-alpine

# Dépendances système et extensions PHP
RUN apk add --no-cache oniguruma-dev libxml2-dev autoconf gcc g++ make \
    && docker-php-ext-install bcmath ctype fileinfo mbstring pdo_mysql xml

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# NodeJS pour assets
RUN apk add --no-cache nodejs npm

# Variables d'environnement
ENV WEB_DOCUMENT_ROOT=/app/public
ENV APP_ENV=production
WORKDIR /app

# Copier le code
COPY . .

# Installer dépendances Laravel
RUN git config --global --add safe.directory /app \
    && rm -rf vendor/* \
    && composer install --no-dev --no-interaction --optimize-autoloader \
    && php artisan key:generate \
    && php artisan config:cache \
    && php artisan view:cache

# Compilation assets
RUN npm install && npm run build

# Permissions
RUN chown -R application:application .

# User pour exécution
USER application

# Exposer le port HTTP
EXPOSE 8000

# 👉 Lancer Laravel au démarrage
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
