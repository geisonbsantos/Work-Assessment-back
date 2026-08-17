# Define a imagem base - PHP 8.3 FPM (otimizada para nginx)
FROM php:8.3-fpm

# Argumentos definidos no docker-compose.yml para personalizar o usuário
ARG user
ARG uid

# Define o diretório de trabalho da aplicação
WORKDIR /var/www

# Instala dependências do sistema necessárias para o Laravel
# git: controle de versão, curl: cliente HTTP, libpng-dev: biblioteca PNG
# libonig-dev: expressões regulares, libxml2-dev: biblioteca XML
# zip/unzip: compactação, autoconf: configuração automática
# g++: compilador C++, make: ferramenta de build
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    autoconf \
    g++ \
    make \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Adiciona script para instalar extensões PHP facilmente
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/install-php-extensions

# Instala Xdebug para debugging
RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug

# Instala extensões PHP essenciais
# gd: manipulação de imagens, zip: compactação, oci8: Oracle database
RUN install-php-extensions gd zip oci8

# Copia configuração personalizada do Xdebug
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Instala extensões PHP adicionais
# RUN docker-php-ext-install mbstring exif pcntl bcmath pdo pdo_mysql
RUN docker-php-ext-install mbstring exif pcntl bcmath

# Obtém a versão mais recente do Composer (gerenciador de dependências PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ----------------------------------------------------
# INSTALAÇÃO DO NODE.JS E NPM
# Copia os binários do Node.js da imagem oficial lts (ou mude para 20, 22, etc.)
# ----------------------------------------------------
COPY --from=node:lts /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node:lts /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-merge-driver /usr/local/bin/npm-merge-driver \
    && ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

# Cria usuário do sistema para executar Composer e comandos Artisan (PHP-FPM)
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Configura permissões para que tanto www-data quanto usuário personalizado trabalhem
RUN chown -R $user:$user /var/www && \
    chmod -R 775 /var/www && \
    usermod -aG www-data $user

# Expõe porta 9000 para PHP-FPM
EXPOSE 9000

# Inicia PHP-FPM
CMD ["php-fpm"]
