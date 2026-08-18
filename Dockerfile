# Define a imagem base - PHP 8.3 FPM (otimizada para nginx)
FROM php:8.3-fpm

# Argumentos definidos no docker-compose.yml para personalizar o usuário
ARG user
ARG uid

# Define o diretório de trabalho da aplicação
WORKDIR /var/www

# Instala dependências do sistema necessárias para o Laravel
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
    ca-certificates \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ============================================================
# Node.js + npm
# ============================================================

# Adiciona o repositório do Node.js 22 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Verifica versões instaladas
RUN node --version && npm --version

# ============================================================
# PHP Extensions
# ============================================================

# Adiciona script para instalar extensões PHP facilmente
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/install-php-extensions

# Instala Xdebug
RUN pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug

# Instala extensões PHP essenciais
RUN install-php-extensions gd zip oci8

# Copia configuração personalizada do Xdebug
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Instala extensões PHP adicionais
RUN docker-php-ext-install mbstring exif pcntl bcmath

# ============================================================
# Composer
# ============================================================

# Obtém a versão mais recente do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ============================================================
# Usuário
# ============================================================

# Cria usuário do sistema para executar Composer e comandos Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Configura permissões
RUN chown -R $user:$user /var/www \
    && chmod -R 775 /var/www \
    && usermod -aG www-data $user

# Expõe porta 9000 para PHP-FPM
EXPOSE 9000

# Inicia PHP-FPM
CMD ["php-fpm"]