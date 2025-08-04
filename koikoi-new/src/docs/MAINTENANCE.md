# メンテナンスガイド

## プロジェクト構造

### ディレクトリ構成
```
src/
├── app/               # アプリケーションロジック
├── config/            
│   └── theme.php      # テーマ設定（色、レイアウト、ブレークポイント）
├── public/
│   └── css/
│       └── main.css   # メインスタイルシート
├── resources/
│   ├── css/           # ソースCSS
│   │   ├── app.css
│   │   ├── variables.css
│   │   └── components/
│   ├── views/
│   │   ├── components/  # 再利用可能なBladeコンポーネント
│   │   ├── layouts/     # レイアウトテンプレート
│   │   └── partials/    # 部分テンプレート
└── routes/
```

## スタイル管理

### CSS変数の変更方法

1. **グローバルカラーの変更**
   - ファイル: `public/css/main.css`
   - CSS変数セクションで色を更新

```css
:root {
    --anime-primary: #0575E6;    /* アニメコンのメインカラー */
    --machi-primary: #FA709A;    /* 街コンのメインカラー */
}
```

2. **テーマ設定の変更**
   - ファイル: `config/theme.php`
   - PHPの設定配列で管理

```php
'colors' => [
    'anime' => [
        'primary' => '#0575E6',
        'gradient' => 'linear-gradient(135deg, #0575E6 0%, #21D4FD 100%)',
    ],
]
```

### 新しいテーマの追加

1. `config/theme.php`に新しいテーマカラーを追加
2. `public/css/main.css`にCSS変数を追加
3. 必要に応じてグラデーションクラスを追加

## コンポーネント

### 再利用可能なコンポーネント

- **theme-icon**: テーマに応じたアイコン表示
  ```blade
  <x-theme-icon type="anime" class="me-1" />
  ```

- **page-header**: ページヘッダー
  ```blade
  <x-page-header theme="anime" title="タイトル" subtitle="サブタイトル" />
  ```

### 新しいコンポーネントの追加

1. `resources/views/components/`に新しいBladeファイルを作成
2. propsデコレータでパラメータを定義
3. 必要に応じて`config/theme.php`の設定を利用

## デプロイ

### CSS/JSの更新時

1. Viteでビルド（Tailwind CSS使用時）
   ```bash
   npm run build
   ```

2. 静的CSSの変更は即座に反映

### 本番環境へのデプロイ

```bash
git add .
git commit -m "変更内容の説明"
git push origin main
```

GitHub Actionsが自動的にFTPデプロイを実行

## トラブルシューティング

### スタイルが反映されない場合

1. ブラウザキャッシュをクリア
2. `public/css/main.css`が正しく読み込まれているか確認
3. CSS変数名が正しいか確認

### グラデーションが表示されない場合

1. `!important`フラグが付いているか確認
2. 他のCSSが上書きしていないか確認
3. クラス名が正しく適用されているか確認

## ベストプラクティス

1. **CSS変数を活用**: ハードコードされた値を避ける
2. **コンポーネント化**: 繰り返し使用する要素はコンポーネント化
3. **設定の一元管理**: `config/theme.php`で管理
4. **意味のある命名**: 変数名やクラス名は用途が分かるように
5. **コメントを追加**: 複雑なスタイルには説明を付ける

## よく使うコマンド

```bash
# 開発サーバー起動
php artisan serve

# キャッシュクリア
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# ビルド
npm run build

# 監視モード
npm run dev
```