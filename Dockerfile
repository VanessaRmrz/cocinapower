# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Instala dependencias del sistema necesarias antes de compilar extensiones
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copia todos los archivos del proyecto al servidor
COPY . /var/www/html/

# Da permisos adecuados a los archivos
RUN chown -R www-data:www-data /var/www/html

# Habilita mod_rewrite (útil si usas URLs amigables)
RUN a2enmod rewrite

# Expone el puerto 80
EXPOSE 80
