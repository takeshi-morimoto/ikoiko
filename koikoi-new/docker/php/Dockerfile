FROM php:8.2-fpm

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libicu-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Redis拡張のインストール
RUN pecl install redis && docker-php-ext-enable redis

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.jsとnpmのインストール（フロントエンドビルド用）
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# PHP設定ファイルのコピー
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# 作業ディレクトリの設定
WORKDIR /var/www/html

# ユーザーとグループの作成
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# 権限の設定
RUN chown -R www:www /var/www/html

USER www

EXPOSE 9000

CMD ["php-fpm"]