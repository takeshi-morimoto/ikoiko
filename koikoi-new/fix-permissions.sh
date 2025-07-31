#!/bin/bash

# Dockerコンテナ内でLaravelの権限を修正するスクリプト

echo "Laravelの権限を修正しています..."

# Dockerコンテナに入って権限を修正
docker-compose exec php bash -c "
    # storageとbootstrap/cacheディレクトリの権限を修正
    chown -R www-data:www-data /var/www/html/storage
    chown -R www-data:www-data /var/www/html/bootstrap/cache
    
    # 書き込み権限を付与
    chmod -R 775 /var/www/html/storage
    chmod -R 775 /var/www/html/bootstrap/cache
    
    # キャッシュをクリア
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    # 設定を再キャッシュ（オプション）
    # php artisan config:cache
    # php artisan route:cache
"

echo "権限の修正が完了しました。"