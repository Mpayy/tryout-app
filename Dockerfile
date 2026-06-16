# ==========================================
# 1. BASE IMAGE
# ==========================================
# Kita pakai PHP 8.2 yang sudah include Apache di dalamnya
FROM php:8.2-apache

# ==========================================
# 2. SYSTEM DEPENDENCIES & PHP EXTENSIONS
# ==========================================
# Install tools standar Linux dan extension PHP yang wajib untuk Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

# ==========================================
# 3. KONFIGURASI APACHE (WEB SERVER)
# ==========================================
# Aktifkan mod_rewrite Apache supaya route Laravel (web.php) tidak 404
RUN a2enmod rewrite

# FIX ERROR MPM: Matikan mpm_event/worker, pastikan hanya mpm_prefork yang aktif
RUN a2dismod mpm_event mpm_worker || true && a2enmod mpm_prefork

# Ubah Apache Document Root dari default (/var/www/html) ke folder (/var/www/html/public)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# ==========================================
# 4. COPY CODE & SET PERMISSIONS
# ==========================================
# Tentukan working directory di dalam container
WORKDIR /var/www/html

# Copy seluruh source code project Laravel kamu dari lokal ke dalam container
COPY . /var/www/html

# Berikan hak akses (permission) ke folder storage & cache supaya Laravel bisa nulis log/session
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ==========================================
# 5. RUN SERVER
# ==========================================
# Buka port 80 (Railway akan otomatis mendeteksi port ini)
EXPOSE 80

# Jalankan Apache di foreground agar container tetap hidup
CMD ["apache2-foreground"]