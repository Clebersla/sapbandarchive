FROM php:8.2-apache

# Instala a extensão mysqli necessária para o banco de dados
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copia os arquivos do seu site para o servidor
COPY . /var/www/html/

# Garante as permissões corretas para o servidor Apache
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
