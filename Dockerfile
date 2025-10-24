# Stage 1: Node.js build
FROM node:22-alpine as node-build

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# stage 2
FROM php:8.3-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y    libzip-dev     unzip     && docker-php-ext-install zip pdo_mysql

RUN a2enmod rewrite

#install Composer
COPY --from=composer:latest  /usr/bin/composer  /usr/bin/composer

COPY ./ /var/www/html/

# Install composer dependencies without running scripts
RUN composer install --no-scripts --no-autoloader

# Generate the autoloader separately
RUN composer dump-autoload --optimize

# Install requires composer dependencies
RUN composer install


RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html/public
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
