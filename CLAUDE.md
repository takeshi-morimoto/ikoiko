# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## プロジェクト概要

日本の社交イベント管理システム（PHP/MySQL）：
- アニメコン（アニメ系の婚活・交流イベント）
- 街コン（街を舞台にした出会いイベント）
- 謎解きイベント
- その他の交流イベント

ロリポップ（日本のホスティングサービス）でホスティングされている従来型のPHPアプリケーション。PDOベースのデータベース操作と、モジュール化されたウィジェットシステムを採用。

## よく使うコマンド

### データベースセットアップ
```bash
# Webインターフェース経由で初期化
# アクセス先: http://localhost/ikoiko/admin_settings/setup.php
# 必要なテーブル（area、events、customers、content）を作成
```

### パフォーマンス最適化
```bash
# 画像最適化
php optimize_images.php  # 100KB以上の画像を分析
php convert_to_webp.php  # WebP形式に変換
php update_html_webp.php  # HTMLをWebP対応に更新

# CSS/JS最適化
php combine_css.php  # CSSファイルを結合してHTTPリクエストを削減
php combine_css_fixed.php  # 改良版のCSS結合スクリプト
php optimize_js_css.php  # JS/CSSの最適化
php optimize_cache.php  # キャッシュ最適化
```

### サイトマップ生成
```bash
php generate_sitemap.php  # データベースからsitemap.xmlを生成
```

### デプロイ
```bash
# GitHub Actions経由で自動デプロイ（mainブランチへのpush時）
git push origin main  # 本番環境へのFTPデプロイをトリガー

# 手動デプロイも可能（GitHub ActionsのWorkflow Dispatchを使用）
```

### ローカル開発環境
```bash
# PHPビルトインサーバーを使用
php -S localhost:8080

# Docker環境（docker-compose.ymlが存在）
docker-compose up -d
```

## アーキテクチャ概要

### データベース構造
主要な3つのテーブル：
- **area**: 会場・地域情報（area, area_ja, ken, place, price_h, price_l, age_m, age_w, free_text1, free_text2, content）
- **events**: イベント詳細、スケジュール、料金設定
- **customers**: ユーザー登録、支払い追跡
- **content**: 各ページのコンテンツ（num, text形式で保存）

### ファイル構造パターン
- **ルートPHPファイル**: メインページ（index.php、event.php、list_*.php）
  - `index.php`: アニメコンのトップページ
  - `machi.php`: 街コンのトップページ
  - `nazo.php`: 謎解きイベントページ
  - `off.php`: オフ会イベントページ
  - `yoruuuu.php`: 夜のイベントページ
- **/admin_settings/**: イベント・顧客管理用の管理画面
  - IP制限による保護（.htaccess）
  - `setup.php`: データベース初期設定
  - `admin.php`: 管理画面メイン
  - `participant.php`: 参加者管理
  - `io-management.php`: CSV/Excel インポート・エクスポート
  - メール送信機能（confirmationMail.php）
- **/widgets/**: 再利用可能なPHPコンポーネント
  - `/ani/`: アニメコン用ウィジェット（globalMenu.php、search.php、dateSearch.php）
  - `/machi/`: 街コン用ウィジェット（同上のファイル構成）
  - 共通: pageHeader_ultimate.php、footer.php、sideContent.php
- **/entry_set/**: ユーザー登録フロー（entry.php → entry_receive.php → finish.php）
- **/db_data/**: データベース設定と接続
  - `db_info/db_info.php`: 接続情報
  - `db_init.php`: PDO初期化
- **/css/**: スタイルシート（レガシーとモダンの混在）
  - `header-supreme.css`: 新しいヘッダーデザイン
  - `modern-*.css`: モダンUIコンポーネント
  - `combined.css`: 結合されたCSS
- **/js/**: JavaScript（jQuery 3.7.1ベース）
- **/img/**: 画像アセット
- **/manga/**: 漫画コンテンツ（manga_1.php〜manga_14.php）
- **/.github/workflows/**: GitHub Actions（deploy.yml）

### URLルーティング
PATH_INFOを使用した疑似ルーティング：
```php
// 例: /event.php/tokyo
$path_info = $_SERVER['PATH_INFO'] ?? '';
$area = strtok($path_info, "/");
```

### データベース接続パターン
```php
require_once '/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_info/db_info.php';
$db->exec("SET NAMES utf8");
```

### ウィジェットシステム
コンポーネントのインクルード方法：
```php
include 'widgets/ani/globalMenu.php';  // アニメセクション用
include 'widgets/machi/globalMenu.php';  // 街コンセクション用
include 'widgets/pageHeader_ultimate.php';  // 共通ヘッダー
```

### 管理画面アクセス
管理画面は`.htaccess`でIP制限。`/admin_settings/`でアクセス。

### 開発時の注意点

1. **パスの更新**: 
   - 本番環境パス: `/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/`
   - ローカル開発時はプロジェクトルートからの相対パスまたは絶対パスに変更
   - ウィジェットのinclude時は `/ikoiko/` プレフィックスに注意

2. **データベース設定**:
   - 認証情報: `/db_data/db_info/db_info.php`
   - CSVファイル: `/db_data/db_info/db_info.csv` も参照される
   - ローカル環境用に両ファイルの更新が必要
   - 初期設定: http://localhost/ikoiko/admin_settings/setup.php

3. **文字エンコーディング**: 
   - 常にUTF-8を使用
   - データベース接続: `$db->exec("SET NAMES utf8");`
   - ファイル保存時もUTF-8（BOMなし）

4. **ファイル命名規則**: 
   - `_m.php`で終わるファイル: 街コン（machi）セクション用
   - `_mb.php`: モバイル専用ページ
   - 接尾辞なしのファイル: アニメコン（ani）セクション用
   - 例: `event.php` = アニメコンイベント、`event_m.php` = 街コンイベント

5. **イベントタイプとルーティング**:
   - PATH_INFOベースのルーティング（例: `/event.php/tokyo`）
   - イベントタイプにより異なる料金体系と登録フロー
   - list_1.php〜list_4.php: 異なるリスト表示形式

6. **Mixed Content対策**: 
   ```php
   // データベースから取得したコンテンツのHTTPをHTTPSに置換
   $content = str_replace('http://koikoi.co.jp', 'https://koikoi.co.jp', $content);
   $content = str_replace('http://www.koikoi.co.jp', 'https://www.koikoi.co.jp', $content);
   ```

### CSSアーキテクチャ

1. **コンテナシステム**: 
   - `#topContainer`: max-width: 1200px のメインコンテナ
   - `.container`: ヘッダー用コンテナ（header-container クラスを推奨）
   - 競合を避けるため、ヘッダー内では`.header-container`を使用

2. **レスポンシブ対応**:
   - モバイル専用ファイル（_mb.php）
   - CSS変数によるブレークポイント管理
   - modern-*.css によるモダンUI実装

### 主要関数とパターン

#### function.php の主要関数
- **h($str)**: XSS防止用のHTMLエスケープ関数
- **データベースクエリ**: ORMなし、直接PDO使用
- **セッション管理**: 登録フローの追跡に使用

#### 共通パターン
```php
// データベース初期化
require_once("db_data/db_init.php");
$db->exec("SET NAMES utf8");

// HTMLエスケープ
echo h($user_input);

// PATH_INFOからエリア取得
$path_info = $_SERVER['PATH_INFO'] ?? '';
$area = strtok($path_info, "/");
```

### よくある問題と解決方法

1. **ヘッダーの重複表示**: 
   - `pageHeader_ultimate.php`の閉じタグ位置を確認
   - コンテナクラスの競合を確認

2. **Mixed Content警告**:
   - データベース内のHTTP URLをHTTPSに置換
   - event.phpとevent_m.phpにstr_replace処理を追加

3. **文字化け**:
   - ファイルエンコーディングをUTF-8に統一
   - データベース接続時に`SET NAMES utf8`を実行

### テストとデバッグ

#### 手動テスト項目
自動テストは存在しないため、以下の手動テストが必要：
- イベントの作成と編集（admin_settings/event_set.php）
- ユーザー登録フロー（entry_set/entry.php → finish.php）
- 支払い確認（admin_settings/participant.php）
- メール通知（confirmationMail.php）
- PATH_INFOルーティング（/event.php/tokyo など）

#### デバッグのヒント
- エラーログの確認
- `var_dump()` や `print_r()` でのデバッグ
- PDOエラーモードの設定: `$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);`

### セキュリティ注意事項

1. **アクセス制御**:
   - 管理画面はIPホワイトリスト（.htaccess）
   - セッションベースの認証チェック

2. **SQLインジェクション対策**:
   - 現状: 一部で直接クエリ結合あり
   - 推奨: プリペアドステートメントの使用
   ```php
   $stmt = $db->prepare("SELECT * FROM area WHERE area = ?");
   $stmt->execute([$area]);
   ```

3. **XSS対策**:
   - `h()` 関数で出力をエスケープ
   - データベースからのコンテンツ表示時は特に注意

4. **HTTPS強制**:
   - .htaccess でHTTPSリダイレクト
   - Mixed Content の防止

5. **設定ファイル**:
   - データベース認証情報のハードコーディングに注意
   - 本番環境では環境変数の使用を推奨

### GitHub Actionsデプロイ設定

FTPデプロイ（.github/workflows/deploy.yml）:
- トリガー: mainブランチへのpush
- 除外ファイル: .git*, node_modules/, CLAUDE.md, README.md
- 必要なシークレット:
  - `FTP_SERVER`: FTPサーバーアドレス
  - `FTP_USERNAME`: FTPユーザー名
  - `FTP_PASSWORD`: FTPパスワード