# Use uma imagem oficial do PHP FPM (ideal para Nginx)
FROM php:7.4-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql bcmath zip

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos do projeto para o container
COPY . /var/www/html

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependências do PHP
RUN composer install --no-interaction --no-plugins --no-scripts --optimize-autoloader

# Dar permissões para as pastas de storage e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expor a porta 9000 (padrão do PHP-FPM)
EXPOSE 9000

# O FPM inicia automaticamente
CMD ["php-fpm"]
