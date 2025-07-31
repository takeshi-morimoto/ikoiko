#!/bin/bash

echo "Laravelの権限問題を修正しています..."

# 1. コンテナを再ビルド
echo "1. Dockerコンテナを再ビルドしています..."
docker-compose down
docker-compose build --no-cache php
docker-compose up -d

# 少し待つ
sleep 5

# 2. コンテナ内で権限を修正
echo "2. コンテナ内で権限を修正しています..."
docker-compose exec -T php bash << 'EOF'
# storageディレクトリの作成と権限設定
mkdir -p storage/{app,framework,logs}
mkdir -p storage/framework/{cache,sessions,views,testing}
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# 所有者をwww-dataに変更
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# 権限を設定
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Laravelのキャッシュをクリア
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# .envファイルが存在しない場合はコピー
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

echo "権限の修正が完了しました。"
EOF

echo "3. ブラウザでアクセスしてください: http://localhost:8080"