FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Use non-root user
RUN useradd -m -s /bin/bash developer \
    && chown -R developer:developer /app
USER developer

# Command to run when container starts
CMD ["php", "-v"] 