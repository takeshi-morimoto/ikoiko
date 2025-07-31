# 新サイトへの段階的移行計画

## 概要
現行サイト（koikoi.co.jp/ikoiko/）を運用しながら、新しいサイトを構築し、段階的に移行する計画です。

## 移行戦略

### 1. アーキテクチャ比較

#### 現行サイト
- **技術**: PHP (レガシー)、MySQL、jQuery
- **ホスティング**: ロリポップ（共有ホスティング）
- **URL構造**: PATH_INFO使用（/event.php/tokyo）
- **デプロイ**: FTP（GitHub Actions経由）

#### 新サイト（提案）
- **技術**: 
  - Option A: Laravel（PHP モダンフレームワーク）
  - Option B: Next.js + PHP API（ヘッドレスCMS構成）
  - Option C: WordPress + カスタムプラグイン（管理画面重視）
- **ホスティング**: 
  - AWS/GCP/Azure
  - または、エックスサーバーなどのVPS
- **URL構造**: RESTful（/events/tokyo）
- **デプロイ**: CI/CD（GitHub Actions + Docker）

### 2. 並行運用プラン

```
現行: koikoi.co.jp/ikoiko/
新規: koikoi.co.jp/v2/ または new.koikoi.co.jp/
```

## Phase 1: 準備期間（1-2ヶ月）

### 1.1 新サイトの基盤構築

```bash
# ディレクトリ構造
/koikoi-new/
├── docker/
│   ├── nginx/
│   ├── php/
│   └── mysql/
├── docker-compose.yml
├── src/
│   ├── app/           # アプリケーションコード
│   ├── public/        # 公開ディレクトリ
│   ├── database/      # マイグレーション
│   └── tests/         # テストコード
└── docs/             # ドキュメント
```

### 1.2 データベース設計（改善版）

```sql
-- 新しいデータベース構造（正規化・最適化済み）
CREATE DATABASE koikoi_new CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 新設計のテーブル（例）
CREATE TABLE areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(32) UNIQUE NOT NULL,  -- URLで使用
    name VARCHAR(100) NOT NULL,
    prefecture_id INT NOT NULL,
    venue_info JSON,  -- 会場情報をJSON形式で
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_prefecture (prefecture_id)
);
```

### 1.3 API設計

```yaml
# API仕様（OpenAPI 3.0）
paths:
  /api/events:
    get:
      summary: イベント一覧取得
      parameters:
        - name: area
          in: query
          schema:
            type: string
        - name: date
          in: query
          schema:
            type: string
            format: date
  
  /api/events/{id}:
    get:
      summary: イベント詳細取得
      
  /api/reservations:
    post:
      summary: 予約登録
```

## Phase 2: 開発期間（2-3ヶ月）

### 2.1 コア機能の実装

優先順位：
1. イベント一覧・詳細表示
2. 予約システム
3. 管理画面（基本機能）
4. メール送信
5. 決済連携

### 2.2 データ同期システム

```php
// data_sync.php - 現行DBから新DBへのデータ同期
<?php
class DataSync {
    private $oldDb;
    private $newDb;
    
    public function syncAreas() {
        // 旧area → 新areas
        $oldAreas = $this->oldDb->query("SELECT * FROM area")->fetchAll();
        
        foreach ($oldAreas as $area) {
            $this->newDb->insert('areas', [
                'slug' => $area['area'],
                'name' => $area['area_ja'],
                'prefecture_id' => $this->getPrefectureId($area['ken']),
                'venue_info' => json_encode([
                    'place' => $area['place'],
                    'price_high' => $area['price_h'],
                    'price_low' => $area['price_l'],
                    'age_male' => $area['age_m'],
                    'age_female' => $area['age_w']
                ])
            ]);
        }
    }
    
    public function syncEvents() {
        // リアルタイム同期も可能
        // 旧システムでイベント登録 → Webhook → 新システムに自動登録
    }
}
```

### 2.3 URL リダイレクト設定

```apache
# .htaccess - 段階的な移行
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # 特定の機能から順次移行
    # Step 1: 新規予約は新システムへ
    RewriteRule ^ikoiko/entry_set/(.*)$ https://new.koikoi.co.jp/reservations/$1 [R=302,L]
    
    # Step 2: 特定エリアを新システムへ
    RewriteRule ^ikoiko/event/tokyo/(.*)$ https://new.koikoi.co.jp/events/tokyo/$1 [R=302,L]
    
    # Step 3: 管理画面を新システムへ
    RewriteRule ^ikoiko/admin_settings/(.*)$ https://new.koikoi.co.jp/admin/$1 [R=302,L]
</IfModule>
```

## Phase 3: テスト期間（1ヶ月）

### 3.1 A/Bテスト

```javascript
// A/Bテスト実装
function redirectToNewSite() {
    const testGroup = localStorage.getItem('test_group') || Math.random() > 0.5 ? 'A' : 'B';
    localStorage.setItem('test_group', testGroup);
    
    if (testGroup === 'B' && window.location.pathname.includes('/event/')) {
        // 50%のユーザーを新サイトへ
        window.location.href = window.location.href.replace('koikoi.co.jp/ikoiko', 'new.koikoi.co.jp');
    }
}
```

### 3.2 モニタリング

```php
// 両サイトのアクセス・エラー監視
class Monitor {
    public function trackAccess($site, $page, $user) {
        // Google Analytics
        // エラーログ
        // パフォーマンス測定
    }
}
```

## Phase 4: 移行期間（1-2ヶ月）

### 4.1 段階的切り替え

1. **Week 1-2**: 新規登録を新システムへ
2. **Week 3-4**: イベント表示を新システムへ
3. **Week 5-6**: 管理機能を新システムへ
4. **Week 7-8**: 完全移行

### 4.2 ロールバック計画

```bash
#!/bin/bash
# rollback.sh - 問題発生時の切り戻し

# DNSを旧サイトに戻す
# CloudflareやRoute53のAPIを使用

# データベースの逆同期
php reverse_sync.php

# キャッシュクリア
redis-cli FLUSHALL

# 通知
curl -X POST https://slack.com/api/chat.postMessage \
  -H "Authorization: Bearer $SLACK_TOKEN" \
  -d "channel=#alerts&text=Rollback completed"
```

## Phase 5: 完了後の対応

### 5.1 旧サイトの処理

```apache
# 全リクエストを新サイトへ301リダイレクト
RewriteRule ^ikoiko/(.*)$ https://koikoi.co.jp/$1 [R=301,L]
```

### 5.2 SEO対策

```xml
<!-- sitemap.xml更新 -->
<url>
    <loc>https://koikoi.co.jp/events/tokyo</loc>
    <lastmod>2025-08-01</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
</url>
```

## 技術スタック比較

### Option A: Laravel（推奨）

**メリット**:
- PHPなので既存知識を活用可能
- 豊富なエコシステム
- 優れたORMとマイグレーション

**構成例**:
```bash
composer create-project laravel/laravel koikoi-new
cd koikoi-new
composer require laravel/breeze  # 認証
composer require spatie/laravel-permission  # 権限管理
composer require barryvdh/laravel-debugbar  # デバッグ
```

### Option B: Next.js + PHP API

**メリット**:
- 高速なフロントエンド
- SEO対応（SSR/SSG）
- モダンな開発体験

**構成例**:
```bash
# フロントエンド
npx create-next-app@latest koikoi-frontend --typescript --tailwind

# バックエンド（Lumen）
composer create-project laravel/lumen koikoi-api
```

### Option C: WordPress

**メリット**:
- 管理画面が充実
- プラグインが豊富
- 非エンジニアでも更新可能

**構成例**:
```php
// カスタムプラグイン作成
// wp-content/plugins/koikoi-events/
```

## リスク管理

### 想定されるリスクと対策

1. **データ不整合**
   - 対策: リアルタイム同期、定期バックアップ

2. **SEOランキング低下**
   - 対策: 301リダイレクト、canonical設定

3. **ユーザー混乱**
   - 対策: 事前告知、UIの統一

4. **システム障害**
   - 対策: ロードバランサー、自動フェイルオーバー

## コスト見積もり

### 開発コスト
- 新サイト開発: 3-6ヶ月
- データ移行: 1ヶ月
- テスト・調整: 1ヶ月

### ランニングコスト（月額）
- 現行: ロリポップ 約1,000円
- 新規: 
  - クラウド: 10,000-30,000円
  - VPS: 3,000-10,000円

## 推奨アプローチ

1. **Laravel + MySQL + Redis**を採用
2. **サブドメイン**（new.koikoi.co.jp）で並行運用
3. **機能単位**で段階的に移行
4. **3ヶ月**かけて慎重に移行

この計画でいかがでしょうか？