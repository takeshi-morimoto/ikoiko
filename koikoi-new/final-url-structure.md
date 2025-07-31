# 最終的なURL構造（都道府県＋エリア＋日付＋通し番号）

## URL構造

### パターン
`/{イベントタイプ}/{都道府県}-{エリア}-{日付}-{通し番号}`

### 具体例

#### 東京都内の複数イベント（アニメコン）
- `/anime/tokyo-ikebukuro-20250815-00001` （池袋 8/15 13:00）
- `/anime/tokyo-akihabara-20250815-00002` （秋葉原 8/15 13:00）
- `/anime/tokyo-shibuya-20250815-00003` （渋谷 8/15 13:00）
- `/anime/tokyo-ikebukuro-20250815-00004` （池袋 8/15 18:00 夜の部）

#### 神奈川県（街コン）
- `/machi/kanagawa-yokohama-20250820-00005` （横浜街コン）
- `/machi/kanagawa-kawasaki-20250820-00006` （川崎街コン）

#### 大阪府（各種イベント）
- `/anime/osaka-umeda-20250822-00007` （梅田アニメコン）
- `/nazo/osaka-namba-20250825-00008` （難波謎解き）

## メリット

1. **SEO効果**
   - 都道府県名とエリア名の両方を含む
   - 「東京 池袋 アニメコン」のような検索に強い
   - 日付も含むため「8月15日 アニメコン」にも対応

2. **ユーザビリティ**
   - URLから場所と日付が一目でわかる
   - SNSシェア時に情報が伝わりやすい

3. **管理性**
   - 通し番号で完全にユニーク
   - 体系的で予測可能

## 都道府県コードの例

```php
// 主要な都道府県
[
    ['code' => '13', 'code_en' => 'tokyo', 'name' => '東京都'],
    ['code' => '14', 'code_en' => 'kanagawa', 'name' => '神奈川県'],
    ['code' => '27', 'code_en' => 'osaka', 'name' => '大阪府'],
    ['code' => '23', 'code_en' => 'aichi', 'name' => '愛知県'],
    ['code' => '40', 'code_en' => 'fukuoka', 'name' => '福岡県'],
    ['code' => '01', 'code_en' => 'hokkaido', 'name' => '北海道'],
    ['code' => '26', 'code_en' => 'kyoto', 'name' => '京都府'],
    ['code' => '28', 'code_en' => 'hyogo', 'name' => '兵庫県'],
]
```

## データ構造の例

```php
// 池袋アニメコン
[
    'event_code' => 'EV-2025-00001',
    'slug' => 'tokyo-ikebukuro-20250815-00001',
    'title' => '池袋 アニメコン 8/15(土) 昼の部',
    'event_type_id' => 1, // アニメコン
    'area_id' => 1, // 池袋
    'event_date' => '2025-08-15',
    'start_time' => '13:00:00',
    'end_time' => '16:00:00',
    'meta_title' => '池袋アニメコン 8/15(土) 13:00〜 | 東京都豊島区',
    'meta_description' => '東京都豊島区池袋で開催されるアニメ好きのための婚活イベント。8月15日(土)13:00〜16:00。',
]
```

## ページ内でのSEO対策

```html
<title>池袋アニメコン 8/15(土) 13:00〜 | 東京都豊島区 | KOIKOI</title>

<h1>池袋アニメコン - 東京都豊島区</h1>
<h2>8月15日(土) 13:00〜16:00 昼の部</h2>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "池袋アニメコン",
  "startDate": "2025-08-15T13:00:00+09:00",
  "endDate": "2025-08-15T16:00:00+09:00",
  "location": {
    "@type": "Place",
    "name": "池袋○○会館",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "豊島区",
      "addressRegion": "東京都",
      "addressCountry": "JP"
    }
  }
}
</script>
```

## まとめ

この構造により：
- **SEO**: 地域検索に強い（都道府県＋エリア）
- **ユーザー**: 場所と日時が明確
- **管理**: 通し番号で重複なし
- **拡張性**: 新しいエリアの追加が容易