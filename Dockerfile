FROM php:8.2-apache

# Copia seus arquivos para a pasta pública do servidor
COPY . /var/www/html/

# Libera a porta 80 para o site funcionar
EXPOSE 80
