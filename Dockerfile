FROM php:8.2-apache

# Instala extensões necessárias
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Configura o Apache para ouvir na porta 10000 (padrão do Render para evitar 502)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/

# O Render define a variável PORT automaticamente
CMD ["apache2-foreground"]
