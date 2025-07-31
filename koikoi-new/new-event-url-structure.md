# 新しいイベントURL構造（通し番号ベース）

## 通し番号方式の利点

1. **完全にユニーク**：同日・同時刻・同地域でも重複しない
2. **管理が簡単**：イベントコードで一意に特定可能
3. **SEOフレンドリー**：地域名とイベントタイプを含む
4. **拡張性が高い**：新しい地域やイベントタイプの追加が容易

## URL構造

### 基本パターン
`/{イベントタイプ}/{エリア}-{イベントタイプ}-{年}-{通し番号}`

### 具体例

#### 東京都内の複数エリアで同日開催
- `/anime/ikebukuro-anime-2025-00001` （池袋 8/15 13:00）
- `/anime/akihabara-anime-2025-00002` （秋葉原 8/15 13:00）
- `/anime/shibuya-anime-2025-00003` （渋谷 8/15 13:00）
- `/anime/ikebukuro-anime-2025-00004` （池袋 8/15 18:00 夜の部）

#### 異なるイベントタイプ
- `/machi/yokohama-machi-2025-00005` （横浜街コン）
- `/nazo/osaka-nazo-2025-00006` （大阪謎解き）

## データ構造

```php
// 池袋アニメコン（昼の部）
[
    'event_code' => 'EV-2025-00001',
    'slug' => 'ikebukuro-anime-2025-00001',
    'title' => '池袋 アニメコン 08/15',
    'event_type_id' => 1, // アニメコン
    'area_id' => 1, // 池袋（東京都豊島区）
    'event_date' => '2025-08-15',
    'start_time' => '13:00:00',
    'end_time' => '16:00:00',
    'session_name' => '昼の部',
    'venue_name' => '池袋○○会館',
    // ...
]

// 秋葉原アニメコン（同日同時刻）
[
    'event_code' => 'EV-2025-00002',
    'slug' => 'akihabara-anime-2025-00002',
    'title' => '秋葉原 アニメコン 08/15',
    'event_type_id' => 1, // アニメコン
    'area_id' => 2, // 秋葉原（東京都千代田区）
    'event_date' => '2025-08-15',
    'start_time' => '13:00:00',
    'end_time' => '16:00:00',
    'venue_name' => '秋葉原○○ビル',
    // ...
]
```

## エリアマスタの例

```php
// 東京都内の詳細エリア
[
    ['slug' => 'ikebukuro', 'name' => '池袋', 'prefecture_id' => 13, 'district' => '豊島区', 'station' => '池袋駅'],
    ['slug' => 'akihabara', 'name' => '秋葉原', 'prefecture_id' => 13, 'district' => '千代田区', 'station' => '秋葉原駅'],
    ['slug' => 'shibuya', 'name' => '渋谷', 'prefecture_id' => 13, 'district' => '渋谷区', 'station' => '渋谷駅'],
    ['slug' => 'shinjuku', 'name' => '新宿', 'prefecture_id' => 13, 'district' => '新宿区', 'station' => '新宿駅'],
    ['slug' => 'roppongi', 'name' => '六本木', 'prefecture_id' => 13, 'district' => '港区', 'station' => '六本木駅'],
]

// 神奈川県
[
    ['slug' => 'yokohama', 'name' => '横浜', 'prefecture_id' => 14, 'district' => '横浜市', 'station' => '横浜駅'],
    ['slug' => 'kawasaki', 'name' => '川崎', 'prefecture_id' => 14, 'district' => '川崎市', 'station' => '川崎駅'],
]

// 大阪府
[
    ['slug' => 'umeda', 'name' => '梅田', 'prefecture_id' => 27, 'district' => '大阪市北区', 'station' => '梅田駅'],
    ['slug' => 'namba', 'name' => '難波', 'prefecture_id' => 27, 'district' => '大阪市中央区', 'station' => '難波駅'],
]
```

## 管理画面での運用

1. **イベント作成時**
   - エリアを選択（池袋、秋葉原など）
   - イベントタイプを選択
   - 日時を設定
   - システムが自動的にイベントコードとスラッグを生成

2. **イベント検索**
   - イベントコードで一意に検索
   - エリア、日付、イベントタイプで絞り込み

3. **URL管理**
   - 通し番号により重複の心配なし
   - 地域名が含まれるのでSEOにも有利