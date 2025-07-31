# 新しいイベントURL構造の例

## 1日1イベントの場合
- `/anime/tokyo-anime-2025-08-15`
- `/machi/yokohama-machi-2025-08-20`
- `/nazo/osaka-nazo-2025-08-25`

## 同日複数イベントの場合

### セッション名を使用
- `/anime/tokyo-anime-2025-08-15-hiru-no-bu` （昼の部）
- `/anime/tokyo-anime-2025-08-15-yoru-no-bu` （夜の部）

### 時間帯での自動区別
- `/anime/tokyo-anime-2025-08-15-morning` （10:00開始）
- `/anime/tokyo-anime-2025-08-15-afternoon` （14:00開始）
- `/anime/tokyo-anime-2025-08-15-evening` （18:00開始）

### 重複時の連番
- `/anime/tokyo-anime-2025-08-15-afternoon`
- `/anime/tokyo-anime-2025-08-15-afternoon-2`

## データ構造の例

```php
// 東京アニメコン 8/15 昼の部
[
    'slug' => 'tokyo-anime-2025-08-15-hiru-no-bu',
    'title' => '東京 アニメコン 昼の部 08/15',
    'event_type_id' => 1, // アニメコン
    'area_id' => 1, // 東京
    'event_date' => '2025-08-15',
    'start_time' => '13:00:00',
    'end_time' => '16:00:00',
    'session_name' => '昼の部',
    'session_number' => 1,
    'capacity_male' => 30,
    'capacity_female' => 30,
    // ...
]

// 東京アニメコン 8/15 夜の部
[
    'slug' => 'tokyo-anime-2025-08-15-yoru-no-bu',
    'title' => '東京 アニメコン 夜の部 08/15',
    'event_type_id' => 1, // アニメコン
    'area_id' => 1, // 東京
    'event_date' => '2025-08-15',
    'start_time' => '18:00:00',
    'end_time' => '21:00:00',
    'session_name' => '夜の部',
    'session_number' => 2,
    'capacity_male' => 40,
    'capacity_female' => 40,
    // ...
]
```