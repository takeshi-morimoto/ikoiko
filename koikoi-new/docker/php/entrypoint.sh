#!/bin/bash

# Laravel用のディレクトリ権限を設定
echo "Setting up Laravel permissions..."

# storageディレクトリが存在しない場合は作成
mkdir -p /var/www/html/storage/{app,framework,logs}
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/bootstrap/cache

# 権限を設定（www-dataユーザーで実行）
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# 書き込み権限を付与
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# PHP-FPMを起動
exec php-fpm