#!/bin/bash

echo "📦 モデルファイルを作成中..."

# モデルを作成
docker-compose exec php php artisan make:model Prefecture
docker-compose exec php php artisan make:model Area
docker-compose exec php php artisan make:model EventType
docker-compose exec php php artisan make:model Event
docker-compose exec php php artisan make:model Customer
docker-compose exec php php artisan make:model Page
docker-compose exec php php artisan make:model ContentBlock

echo "✅ モデルファイルの作成が完了しました！"