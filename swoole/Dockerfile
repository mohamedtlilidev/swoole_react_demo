FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libbrotli-dev \
    pkg-config \
    git unzip libzip-dev libssl-dev \
    && docker-php-ext-install sockets \
    && pecl install swoole \
    && docker-php-ext-enable swoole


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Expose Apache and WebSocket ports
#EXPOSE 80 8084

# Copy start script and make it executable
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Run Apache and WebSocket server together
CMD ["/start.sh"]
