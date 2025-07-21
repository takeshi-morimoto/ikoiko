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
```

### サイトマップ生成
```bash
php generate_sitemap.php  # データベースからsitemap.xmlを生成
```

### CSS最適化
```bash
php combine_css.php  # CSSファイルを結合してHTTPリクエストを削減
```

### デプロイ
```bash
# GitHub Actions経由で自動デプロイ
git push origin main  # 本番環境へのFTPデプロイをトリガー
```

## アーキテクチャ概要

### データベース構造
主要な3つのテーブル：
- **area**: 会場・地域情報
- **events**: イベント詳細、スケジュール、料金設定
- **customers**: ユーザー登録、支払い追跡

### ファイル構造パターン
- **ルートPHPファイル**: メインページ（index.php、event.php、list_*.php）
- **/admin_settings/**: イベント・顧客管理用の管理画面
- **/widgets/**: 再利用可能なPHPコンポーネント（ヘッダー、フッター、検索）
- **/entry_set/**: ユーザー登録フロー
- **/db_data/**: データベース設定と接続

### URLルーティング
PATH_INFOを使用した疑似ルーティング：
```php
// 例: /event.php/tokyo
$path_info = $_SERVER['PATH_INFO'] ?? '';
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

### 主要関数（function.php）

- **h($str)**: XSS防止用のHTMLエスケープ関数
- **データベースクエリ**: ORMなし、直接PDO使用
- **セッション管理**: 登録フローの追跡に使用

### テスト
自動テストは存在しない。以下の手動テストが必要：
- イベントの作成と編集
- ユーザー登録フロー
- 支払い確認
- メール通知

### セキュリティ注意事項
- 管理画面はIPホワイトリストを使用
- プリペアドステートメントを実装箇所で使用
- .htaccess経由でHTTPS強制
- 入力検証はモジュールにより異なる