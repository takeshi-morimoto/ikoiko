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

# エントリーポイントスクリプトをコピー
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# www-dataユーザーのUIDとGIDを1000に変更（ホストユーザーと一致させる）
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

EXPOSE 9000

# rootユーザーでエントリーポイントを実行（権限設定のため）
USER root

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]