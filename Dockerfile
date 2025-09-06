FROM php:8.1-cli

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /app

# Copier les fichiers de configuration
COPY composer.json composer.lock* ./

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

# Copier le code source
COPY . .

# Créer le répertoire pour les logs
RUN mkdir -p /app/logs && chmod 755 /app/logs

# Exposer le port (si nécessaire pour des APIs)
EXPOSE 8000

# Commande par défaut
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
