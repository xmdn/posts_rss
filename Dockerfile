FROM php:7.4-apache

WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change ownership and permissions for storage/logs directory
# RUN chown -R www-data:www-data /storage/logs \
#     && chmod -R 775 /storage/logs
# ADD . /var/www
# RUN chmod -R 755 storage

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

CMD ["php", "artisan", "schedule:work"]
