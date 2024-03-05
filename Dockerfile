<<<<<<< HEAD
FROM php:7.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
=======
# Use Node.js version 18 Alpine as base image
FROM node:18-alpine

# Set the working directory inside the container
WORKDIR /app

# Copy package.json and package-lock.json to work directory
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code
COPY . .

# Expose the port that the app runs on (if necessary)
# EXPOSE 8080

# Run the app
CMD ["npm", "run", "serve"]
>>>>>>> origin/master
