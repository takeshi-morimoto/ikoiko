#!/bin/bash

echo "🚀 KOIKOI Laravel プロジェクトセットアップ（修正版）"
echo "=========================================="

# srcディレクトリのクリーンアップ
echo "🧹 srcディレクトリをクリーンアップ中..."
sudo rm -rf src/*
sudo rm -rf src/.*

# Dockerコンテナの起動
echo "📦 Dockerコンテナを起動中..."
docker-compose up -d

# コンテナの起動を待つ
echo "⏳ コンテナの起動を待っています..."
sleep 10

# Laravelプロジェクトを直接作成（権限問題を回避）
echo "🔧 Laravelプロジェクトを作成中..."
docker run --rm -v $(pwd)/src:/app -w /app composer:latest create-project laravel/laravel . --prefer-dist

# 権限の修正
echo "🔒 権限を設定中..."
sudo chown -R 1000:1000 src/
sudo chmod -R 755 src/

# 環境設定ファイルの準備
echo "⚙️ 環境設定中..."
cp src/.env.example src/.env

# .envファイルの更新
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/g' src/.env
sed -i 's/DB_DATABASE=laravel/DB_DATABASE=koikoi_new/g' src/.env
sed -i 's/DB_USERNAME=root/DB_USERNAME=koikoi_user/g' src/.env
sed -i 's/DB_PASSWORD=/DB_PASSWORD=koikoi_pass/g' src/.env
sed -i 's/CACHE_DRIVER=file/CACHE_DRIVER=redis/g' src/.env
sed -i 's/SESSION_DRIVER=file/SESSION_DRIVER=redis/g' src/.env
sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/g' src/.env

# アプリケーションキーの生成
echo "🔑 アプリケーションキーを生成中..."
docker-compose exec php php artisan key:generate

# ストレージリンクの作成
echo "🔗 ストレージリンクを作成中..."
docker-compose exec php php artisan storage:link

# データベースの準備を待つ
echo "⏳ データベースの準備を待っています..."
until docker-compose exec mysql mysql -ukoikoi_user -pkoikoi_pass -e "SELECT 1" &> /dev/null
do
    printf "."
    sleep 1
done
echo ""

# マイグレーションの実行
echo "🗄️ データベースマイグレーションを実行中..."
docker-compose exec php php artisan migrate

echo ""
echo "✅ セットアップが完了しました！"
echo ""
echo "🌐 アクセスURL:"
echo "   - フロントエンド: http://localhost:8080"
echo "   - 管理画面: http://localhost:8080/admin"
echo "   - メールテスト（MailHog）: http://localhost:8025"
echo ""
echo "📝 次のステップ:"
echo "   1. マイグレーションファイルを作成"
echo "   2. モデルとコントローラーを実装"
echo "   3. ビューを作成"
echo "   4. 旧システムからデータを移行"
echo ""
echo "🛠️ 便利なコマンド:"
echo "   - Tinker: docker-compose exec php php artisan tinker"
echo "   - ログ確認: docker-compose logs -f php"
echo "   - キャッシュクリア: docker-compose exec php php artisan cache:clear"