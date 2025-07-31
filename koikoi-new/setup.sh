#!/bin/bash

echo "🚀 KOIKOI Laravel プロジェクトセットアップ"
echo "========================================"

# Dockerコンテナの起動
echo "📦 Dockerコンテナを起動中..."
docker-compose up -d

# コンテナの起動を待つ
echo "⏳ コンテナの起動を待っています..."
sleep 10

# Laravelプロジェクトの作成
echo "🔧 Laravelプロジェクトを作成中..."
docker-compose exec php composer create-project laravel/laravel . --prefer-dist

# 環境設定ファイルの準備
echo "⚙️ 環境設定中..."
docker-compose exec php cp .env.example .env

# .envファイルの更新
docker-compose exec php sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/g' .env
docker-compose exec php sed -i 's/DB_DATABASE=laravel/DB_DATABASE=koikoi_new/g' .env
docker-compose exec php sed -i 's/DB_USERNAME=root/DB_USERNAME=koikoi_user/g' .env
docker-compose exec php sed -i 's/DB_PASSWORD=/DB_PASSWORD=koikoi_pass/g' .env
docker-compose exec php sed -i 's/CACHE_DRIVER=file/CACHE_DRIVER=redis/g' .env
docker-compose exec php sed -i 's/SESSION_DRIVER=file/SESSION_DRIVER=redis/g' .env

# アプリケーションキーの生成
echo "🔑 アプリケーションキーを生成中..."
docker-compose exec php php artisan key:generate

# 必要なパッケージのインストール
echo "📚 追加パッケージをインストール中..."
docker-compose exec php composer require laravel/breeze --dev
docker-compose exec php composer require filament/filament
docker-compose exec php composer require spatie/laravel-permission
docker-compose exec php composer require barryvdh/laravel-debugbar --dev

# Breezeのインストール（認証機能）
echo "🔐 認証機能をセットアップ中..."
docker-compose exec php php artisan breeze:install blade
docker-compose exec php npm install
docker-compose exec php npm run build

# ストレージリンクの作成
echo "🔗 ストレージリンクを作成中..."
docker-compose exec php php artisan storage:link

# 権限の設定
echo "🔒 権限を設定中..."
docker-compose exec php chmod -R 777 storage bootstrap/cache

# データベースのマイグレーション待ち
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

# Filamentの設定
echo "🎨 管理画面（Filament）をセットアップ中..."
docker-compose exec php php artisan filament:install --panels

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