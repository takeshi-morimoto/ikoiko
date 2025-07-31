#!/bin/bash
# 新サイト構築スターター

echo "🚀 KOIKOI新サイト構築スクリプト"
echo "================================"

# プロジェクトディレクトリ作成
PROJECT_NAME="koikoi-new"
echo "📁 プロジェクトディレクトリを作成: $PROJECT_NAME"
mkdir -p $PROJECT_NAME
cd $PROJECT_NAME

# Option A: Laravel プロジェクトの初期設定
setup_laravel() {
    echo "🔧 Laravel プロジェクトをセットアップ中..."
    
    # Dockerファイル作成
    mkdir -p docker/{nginx,php,mysql}
    
    # docker-compose.yml
    cat > docker-compose.yml << 'EOF'
version: '3.8'
services:
  nginx:
    build: ./docker/nginx
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
    depends_on:
      - php
    networks:
      - koikoi-network

  php:
    build: ./docker/php
    volumes:
      - ./src:/var/www/html
    networks:
      - koikoi-network

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: koikoi_new
      MYSQL_USER: koikoi_user
      MYSQL_PASSWORD: koikoi_pass
    ports:
      - "3307:3306"
    volumes:
      - mysql_data:/var/lib/mysql
      - ./docker/mysql/init:/docker-entrypoint-initdb.d
    networks:
      - koikoi-network

  redis:
    image: redis:alpine
    ports:
      - "6380:6379"
    networks:
      - koikoi-network

volumes:
  mysql_data:

networks:
  koikoi-network:
    driver: bridge
EOF

    # Nginx設定
    cat > docker/nginx/Dockerfile << 'EOF'
FROM nginx:alpine
COPY nginx.conf /etc/nginx/conf.d/default.conf
EOF

    cat > docker/nginx/nginx.conf << 'EOF'
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF

    # PHP設定
    cat > docker/php/Dockerfile << 'EOF'
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
EOF

    echo "✅ Docker環境の設定完了"
}

# Option B: Next.js + API プロジェクトの初期設定
setup_nextjs() {
    echo "🔧 Next.js + API プロジェクトをセットアップ中..."
    
    # フロントエンドとバックエンドのディレクトリ作成
    mkdir -p {frontend,backend}
    
    # Frontend package.json
    cat > frontend/package.json << 'EOF'
{
  "name": "koikoi-frontend",
  "version": "1.0.0",
  "scripts": {
    "dev": "next dev",
    "build": "next build",
    "start": "next start"
  }
}
EOF

    # Backend composer.json
    cat > backend/composer.json << 'EOF'
{
  "name": "koikoi/api",
  "description": "KOIKOI API",
  "require": {
    "php": "^8.0",
    "slim/slim": "^4.0",
    "slim/psr7": "^1.0"
  }
}
EOF

    echo "✅ Next.js + API環境の設定完了"
}

# データ移行スクリプトの準備
create_migration_scripts() {
    echo "📝 データ移行スクリプトを作成中..."
    
    mkdir -p scripts/migration
    
    cat > scripts/migration/sync_data.php << 'EOF'
<?php
/**
 * データ同期スクリプト
 * 旧システムから新システムへのデータ移行
 */

class DataMigration {
    private $oldDb;
    private $newDb;
    
    public function __construct($oldConfig, $newConfig) {
        $this->oldDb = new PDO(
            "mysql:host={$oldConfig['host']};dbname={$oldConfig['database']}",
            $oldConfig['username'],
            $oldConfig['password']
        );
        
        $this->newDb = new PDO(
            "mysql:host={$newConfig['host']};dbname={$newConfig['database']}",
            $newConfig['username'],
            $newConfig['password']
        );
    }
    
    public function migrateAll() {
        $this->migrateAreas();
        $this->migrateEvents();
        $this->migrateCustomers();
        echo "✅ データ移行完了\n";
    }
    
    private function migrateAreas() {
        echo "📍 エリアデータを移行中...\n";
        // 移行ロジック
    }
    
    private function migrateEvents() {
        echo "📅 イベントデータを移行中...\n";
        // 移行ロジック
    }
    
    private function migrateCustomers() {
        echo "👥 顧客データを移行中...\n";
        // 移行ロジック
    }
}
EOF

    echo "✅ 移行スクリプトの作成完了"
}

# 開発ドキュメントの作成
create_documentation() {
    echo "📚 ドキュメントを作成中..."
    
    mkdir -p docs
    
    cat > docs/README.md << 'EOF'
# KOIKOI 新システム

## 概要
KOIKOIイベント管理システムの新バージョンです。

## 技術スタック
- Backend: Laravel 10 / PHP 8.2
- Frontend: Blade Templates / Vue.js 3
- Database: MySQL 8.0
- Cache: Redis
- Server: Nginx

## セットアップ
1. Dockerを起動
   ```bash
   docker-compose up -d
   ```

2. Laravelをインストール
   ```bash
   docker-compose exec php composer create-project laravel/laravel .
   ```

3. 環境設定
   ```bash
   cp .env.example .env
   docker-compose exec php php artisan key:generate
   ```

4. データベースマイグレーション
   ```bash
   docker-compose exec php php artisan migrate
   ```

## 開発方針
- テスト駆動開発（TDD）
- APIファースト
- レスポンシブデザイン
- SEO最適化

## ディレクトリ構造
```
koikoi-new/
├── docker/          # Docker設定
├── src/            # Laravelアプリケーション
├── scripts/        # 移行・管理スクリプト
└── docs/           # ドキュメント
```
EOF

    echo "✅ ドキュメントの作成完了"
}

# メインメニュー
echo ""
echo "どのアーキテクチャで新サイトを構築しますか？"
echo "1) Laravel (PHP モダンフレームワーク) - 推奨"
echo "2) Next.js + PHP API (ヘッドレス構成)"
echo "3) カスタム設定"
echo ""
read -p "選択してください (1-3): " choice

case $choice in
    1)
        setup_laravel
        create_migration_scripts
        create_documentation
        echo ""
        echo "✨ Laravel プロジェクトの準備が完了しました！"
        echo "次のステップ:"
        echo "1. cd $PROJECT_NAME"
        echo "2. docker-compose up -d"
        echo "3. docker-compose exec php composer create-project laravel/laravel src"
        ;;
    2)
        setup_nextjs
        create_migration_scripts
        create_documentation
        echo ""
        echo "✨ Next.js + API プロジェクトの準備が完了しました！"
        ;;
    3)
        echo "カスタム設定を選択しました。手動でセットアップしてください。"
        ;;
    *)
        echo "無効な選択です。"
        exit 1
        ;;
esac

echo ""
echo "🎉 セットアップ完了！"
echo "詳細は docs/README.md を参照してください。"