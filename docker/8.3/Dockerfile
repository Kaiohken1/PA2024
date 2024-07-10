FROM ubuntu:latest

# Prévenir les interactions utilisateur
ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV VENV_PATH=/var/www/venv

# Installer les dépendances système
RUN apt-get update && apt-get install -y --no-install-recommends \
    tzdata \
    bash \
    curl \
    npm \
    nodejs \
    python3 \
    python3-pip \
    python3-venv \
    php \
    php8.3-fpm \
    php8.3-cli \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-curl \
    php8.3-zip \
    php8.3-bcmath \
    php8.3-dom \
    php8.3-xmlwriter \
    php8.3-tokenizer \
    php8.3-mysql \
    php8.3-pdo \
    php8.3-gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configurer le fuseau horaire
RUN ln -fs /usr/share/zoneinfo/Europe/Paris /etc/localtime && dpkg-reconfigure --frontend noninteractive tzdata

# Configurer PHP-FPM pour écouter sur le port 9000
RUN sed -i 's|listen = /run/php/php8.3-fpm.sock|listen = 9000|' /etc/php/8.3/fpm/pool.d/www.conf

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer un répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application
COPY composer.json composer.lock ./
COPY . .

# Installer les dépendances PHP avec Composer
RUN composer install --no-dev --optimize-autoloader

# Générer la clé de l'application
RUN php artisan key:generate --show | sed 's/APP_KEY=//g' >> .env

# Mettre à jour npm et installer pnpm et bun
RUN npm install -g npm@latest \
    && npm install -g pnpm \
    && npm install -g bun
RUN npm install
RUN npm run build

# Changer les permissions du dossier de stockage
RUN chown -R www-data:www-data /var/www/bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage
RUN chmod -R 775 /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage 

# Exposer le port 9000 pour php-fpm
EXPOSE 9000

# Démarrer php-fpm
CMD ["php-fpm8.3", "-F"]
