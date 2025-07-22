# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## プロジェクト概要

日本の社交イベント管理システム（PHP/MySQL）：
- アニメコン（アニメ系の婚活・交流イベント）
- 街コン（街を舞台にした出会いイベント）
- 謎解きイベント
- その他の交流イベント

ロリポップ（日本のホスティングサービス）でホスティングされている従来型のPHPアプリケーション。

## よく使うコマンド

### データベースセットアップ
```bash
# Webインターフェース経由で初期化
# アクセス先: http://localhost/admin_settings/setup.php
# 必要なテーブル（area、events、customers）を作成
```

### 画像最適化
```bash
# ブラウザ経由で実行
php optimize_images.php  # 100KB以上の画像を分析
php convert_to_webp.php  # WebP形式に変換
php update_html_webp.php  # HTMLをWebP対応に更新
```

### サイトマップ生成
```bash
php generate_sitemap.php  # データベースからsitemap.xmlを生成
```

### CSS最適化
```bash
php combine_css.php  # CSSファイルを結合してHTTPリクエストを削減
php combine_css_fixed.php  # 改良版のCSS結合スクリプト
```

### デプロイ
```bash
# GitHub Actions経由で自動デプロイ
git push origin main  # 本番環境へのFTPデプロイをトリガー
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
- **/admin_settings/**: イベント・顧客管理用の管理画面
  - IP制限による保護（.htaccess）
  - CSV/Excel インポート・エクスポート機能
  - メール送信機能
- **/widgets/**: 再利用可能なPHPコンポーネント
  - `/ani/`: アニメコン用ウィジェット
  - `/machi/`: 街コン用ウィジェット
  - 共通: pageHeader_ultimate.php、footer.php
- **/entry_set/**: ユーザー登録フロー
- **/db_data/**: データベース設定と接続
- **/css/**: スタイルシート（レガシーとモダンの混在）
  - header-supreme.css: 新しいヘッダーデザイン
  - modern-*.css: モダンUIコンポーネント
- **/js/**: JavaScript（jQuery 3.1.0ベース）
- **/img/**: 画像アセット

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

1. **パスの更新**: ローカル開発時は本番環境のパス（`/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/`）をローカルパスに変更

2. **データベース認証情報**: `/db_data/db_info/db_info.php`に記載 - ローカル環境用に更新が必要

3. **文字エンコーディング**: 常にUTF-8を使用。データベース接続時に明示的にUTF-8を設定

4. **ファイル命名規則**: 
   - `_m.php`で終わるファイル: 街コン（machi）セクション用
   - 接尾辞なしのファイル: アニメコン（ani）セクション用
   - 例: `event.php` = アニメコンイベント、`event_m.php` = 街コンイベント

5. **イベントタイプ**: 異なる料金体系と登録フローを持つ複数のイベントタイプをサポート

6. **Mixed Content対策**: HTTPSページでHTTPリソースを読み込まないよう注意
   ```php
   // データベースから取得したコンテンツのHTTPをHTTPSに置換
   $content = str_replace('http://koikoi.co.jp', 'https://koikoi.co.jp', $content);
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

### 主要関数（function.php）

- **h($str)**: XSS防止用のHTMLエスケープ関数
- **データベースクエリ**: ORMなし、直接PDO使用
- **セッション管理**: 登録フローの追跡に使用

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

### テスト
自動テストは存在しない。以下の手動テストが必要：
- イベントの作成と編集
- ユーザー登録フロー
- 支払い確認
- メール通知

### セキュリティ注意事項
- 管理画面はIPホワイトリストを使用
- SQLインジェクション対策が不完全（プリペアドステートメントの使用を推奨）
- .htaccess経由でHTTPS強制
- 入力検証はモジュールにより異なる
- データベース認証情報のハードコーディングに注意