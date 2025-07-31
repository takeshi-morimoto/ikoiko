#!/bin/bash

echo "Docker環境を完全にクリーンアップして再起動します..."

# 1. コンテナを停止
echo "1. 既存のコンテナを停止しています..."
docker-compose down

# 2. 古いコンテナを削除
echo "2. 古いコンテナを削除しています..."
docker container prune -f

# 3. 古いイメージを削除（koikoi関連のみ）
echo "3. 古いイメージを削除しています..."
docker images | grep koikoi | awk '{print $3}' | xargs -r docker rmi -f

# 4. ボリュームを削除（オプション - データベースデータも削除される）
echo "4. ボリュームを削除しますか？（データベースのデータも削除されます）"
read -p "削除する場合は 'yes' と入力してください: " answer
if [ "$answer" = "yes" ]; then
    docker volume rm koikoi-new_mysql_data 2>/dev/null || true
    echo "ボリュームを削除しました。"
fi

# 5. ネットワークを削除
echo "5. ネットワークを削除しています..."
docker network rm koikoi-new_koikoi-network 2>/dev/null || true

# 6. ビルドキャッシュをクリア
echo "6. ビルドキャッシュをクリアしています..."
docker builder prune -f

# 7. 新しいイメージをビルド
echo "7. 新しいイメージをビルドしています..."
docker-compose build --no-cache

# 8. コンテナを起動
echo "8. コンテナを起動しています..."
docker-compose up -d

# 9. 少し待ってから権限を設定
echo "9. 権限を設定しています..."
sleep 10

docker-compose exec -T php bash << 'EOF'
# ディレクトリ作成
mkdir -p storage/{app,framework,logs}
mkdir -p storage/framework/{cache,sessions,views,testing}
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# 権限設定
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# .envファイルの設定
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Composerの依存関係をインストール
composer install --no-interaction --prefer-dist --optimize-autoloader

# npmパッケージをインストール
npm install
npm run build

# マイグレーション実行の確認
echo ""
echo "データベースマイグレーションを実行しますか？"
echo "（新規インストールの場合は実行してください）"
EOF

echo ""
echo "============================================"
echo "クリーンアップと再起動が完了しました！"
echo "============================================"
echo ""
echo "次のステップ:"
echo "1. ブラウザで http://localhost:8080 にアクセス"
echo "2. マイグレーションを実行する場合:"
echo "   docker-compose exec php php artisan migrate"
echo ""
echo "現在のコンテナ状態:"
docker-compose ps