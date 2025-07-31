# KOIKOI 統合サイト構造

## URL構造

```
koikoi.co.jp/
├── /                       # トップページ（2つのサービスを紹介）
├── /events/               # 全イベント一覧
├── /events/calendar/      # カレンダー表示
│
├── /anime/                # アニメコントップ
├── /anime/about/          # アニメコンについて
├── /anime/{slug}/         # アニメコンイベント詳細
│   └── 例: /anime/tokyo-ikebukuro-20250815-00001
│
├── /machi/                # 街コントップ
├── /machi/about/          # 街コンについて
├── /machi/{slug}/         # 街コンイベント詳細
│   └── 例: /machi/kanagawa-yokohama-20250820-00005
│
├── /nazo/                 # 謎解きイベント（将来拡張）
│
├── /entry/{event}/        # イベント申込フォーム
├── /entry/{event}/confirm # 確認画面
├── /entry/thanks/{id}     # 完了画面
│
├── /mypage/               # マイページ（要ログイン）
├── /mypage/events/        # 参加イベント一覧
├── /mypage/profile/       # プロフィール編集
│
├── /area/tokyo/           # 東京都のイベント一覧
├── /area/tokyo/ikebukuro/ # 池袋のイベント一覧
│
├── /terms/                # 利用規約
├── /privacy/              # プライバシーポリシー
├── /company/              # 会社概要
└── /contact/              # お問い合わせ
```

## トップページの構成

```html
<!-- ヘッダー -->
<header>
  <nav>
    <a href="/">KOIKOI</a>
    <ul>
      <li><a href="/anime/">アニメコン</a></li>
      <li><a href="/machi/">街コン</a></li>
      <li><a href="/events/">全イベント</a></li>
      <li><a href="/mypage/">マイページ</a></li>
    </ul>
  </nav>
</header>

<!-- ヒーローセクション -->
<section class="hero">
  <h1>恋と出会いのイベントサイト KOIKOI</h1>
  <div class="service-cards">
    <div class="anime-card">
      <h2>アニメコン</h2>
      <p>アニメ好きのための婚活イベント</p>
      <a href="/anime/">詳しく見る</a>
    </div>
    <div class="machi-card">
      <h2>街コン</h2>
      <p>地域密着型の出会いイベント</p>
      <a href="/machi/">詳しく見る</a>
    </div>
  </div>
</section>

<!-- 直近のイベント -->
<section class="upcoming-events">
  <h2>開催予定のイベント</h2>
  <div class="event-list">
    <!-- アニメコンと街コンを混在表示 -->
  </div>
</section>
```

## コントローラー構造

### HomeController
- `index()`: トップページ（両サービスの紹介）

### EventController
- `index()`: 全イベント一覧（フィルタ機能付き）
- `calendar()`: カレンダー表示
- `byPrefecture()`: 都道府県別一覧
- `byArea()`: エリア別一覧

### AnimeController
- `index()`: アニメコン一覧
- `about()`: アニメコンについて
- `show()`: アニメコン詳細

### MachiController
- `index()`: 街コン一覧
- `about()`: 街コンについて
- `show()`: 街コン詳細

## デザイン切り替え

```php
// app/Http/Controllers/AnimeController.php
public function index()
{
    return view('anime.index', [
        'theme' => 'anime', // アニメテーマを適用
        'events' => Event::anime()->upcoming()->get()
    ]);
}

// app/Http/Controllers/MachiController.php
public function index()
{
    return view('machi.index', [
        'theme' => 'machi', // 街コンテーマを適用
        'events' => Event::machi()->upcoming()->get()
    ]);
}
```

## ビューの構成

```
resources/views/
├── layouts/
│   ├── app.blade.php      # 共通レイアウト
│   ├── anime.blade.php    # アニメコン用レイアウト
│   └── machi.blade.php    # 街コン用レイアウト
├── home/
│   └── index.blade.php    # トップページ
├── anime/
│   ├── index.blade.php    # アニメコン一覧
│   ├── show.blade.php     # アニメコン詳細
│   └── about.blade.php    # アニメコンについて
├── machi/
│   ├── index.blade.php    # 街コン一覧
│   ├── show.blade.php     # 街コン詳細
│   └── about.blade.php    # 街コンについて
└── events/
    ├── index.blade.php    # 全イベント一覧
    └── calendar.blade.php # カレンダー表示
```

## CSS構成

```scss
// アニメコンテーマ
.theme-anime {
  --primary-color: #FF6B6B;  // ピンク系
  --bg-pattern: url('/img/anime-pattern.png');
  
  .hero {
    background: var(--bg-pattern);
  }
}

// 街コンテーマ
.theme-machi {
  --primary-color: #4ECDC4;  // 青緑系
  --bg-pattern: url('/img/city-pattern.png');
  
  .hero {
    background: var(--bg-pattern);
  }
}
```

## メリット

1. **管理効率**: 1つの管理画面で両方のイベントを管理
2. **ユーザー利便性**: 1回の会員登録で両方に参加可能
3. **SEO効果**: ドメインパワーの集約
4. **開発効率**: コードの再利用が可能
5. **拡張性**: 新しいイベントタイプの追加が容易

## 実装のポイント

1. **明確な区別**: URLとデザインで2つのサービスを明確に区別
2. **共通機能**: 会員登録、決済、マイページは共通化
3. **独立性**: それぞれのサービスが独立して見えるように設計
4. **フィルタリング**: 全イベント一覧では適切なフィルタを提供