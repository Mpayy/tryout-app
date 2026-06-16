FROM php:8.2-apache

WORKDIR /var/www/html

# 1. Install alat-alat yang dibutuhkan (Ekstensi PHP & Node.js)
RUN apt-get update && apt-get install -y libzip-dev libpng-dev zip unzip nodejs npm \
    && docker-php-ext-install pdo_mysql zip gd \
    && a2enmod rewrite \
    && a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# 2. Atur folder /public Laravel & port dinamis untuk Railway
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV PORT=80
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
    && sed -i "s/80/\${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 3. Copy seluruh file project Anda ke dalam Docker
COPY . .

# 4. Install Composer dan semua Dependency (PHP & Node.js)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-interaction
RUN npm install && npm run build

# 5. Berikan izin tulis (permission) pada folder penyimpanan
RUN chown -R www-data:www-data storage bootstrap/cache
