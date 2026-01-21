# Use a imagem oficial do PHP 8.2 com Apache
FROM php:8.2-apache

# Instala as dependências necessárias e a extensão pdo_sqlite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite

# Define o diretório de trabalho
WORKDIR /var/www/html

# Opcional: Altera a propriedade do diretório para o usuário do Apache para garantir a escrita (ex: SQLite)
# O mapeamento de volume no compose pode sobrescrever isso, mas é uma boa prática.
RUN chown -R www-data:www-data /var/www/html
