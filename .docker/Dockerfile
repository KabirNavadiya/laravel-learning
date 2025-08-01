FROM php:8.2-fpm

# Install basic system dependencies and GPG first
RUN apt-get update && apt-get install -y \
    gnupg \
    ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js repository and Node.js
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" > /etc/apt/sources.list.d/nodesource.list

# Install all other dependencies, PHP extensions, and tools
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    sqlite3 \
    libsqlite3-dev \
    ffmpeg \
    nodejs \
    && npm install -g npm@latest \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && ffmpeg -version && composer --version

# Set working directory
WORKDIR /var/www/html

# Create user, set permissions, and create directories in one layer
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd -g ${GROUP_ID} appuser \
    && useradd -u ${USER_ID} -g appuser -m -s /bin/bash appuser \
    && usermod -a -G www-data appuser \
    && mkdir -p /var/www/html/storage/logs \
                /var/www/html/storage/framework/cache \
                /var/www/html/storage/framework/sessions \
                /var/www/html/storage/framework/views \
                /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R appuser:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy configuration files and set permissions in one layer
COPY php/php.ini /usr/local/etc/php/conf.d/99-uploads.ini
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Switch to the non-root user
USER appuser

# Expose port 9000 for PHP-FPM
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]