FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install sockets \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*



# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

RUN echo "ServerName localhost" >> /etc/apache2/conf-available/servername.conf \
    && a2enconf servername


# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Expose WebSocket port and Apache port
#EXPOSE 8080 80

# Start script to run both Apache and WebSocket server
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]