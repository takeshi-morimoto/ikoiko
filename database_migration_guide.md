# データベース改善実装ガイド

## 概要
このガイドは、既存のデータを保持しながら段階的にデータベースを改善する手順を説明します。

## 現状の問題点と改善案

### 1. **パフォーマンスの問題**
- **問題**: インデックスが不足しており、データ量が増えると検索が遅くなる
- **解決**: 頻繁に検索される列にインデックスを追加

### 2. **データ整合性の問題**
- **問題**: 外部キー制約がなく、不整合なデータが入る可能性
- **解決**: 外部キー制約の追加（段階的に）

### 3. **拡張性の問題**
- **問題**: 価格設定が固定的、イベントカテゴリが決め打ち
- **解決**: 柔軟な価格設定テーブル、カテゴリマスタの追加

### 4. **運用上の問題**
- **問題**: 参加者数の手動管理、メール履歴なし
- **解決**: トリガーによる自動更新、履歴テーブルの追加

## 実装手順

### Phase 1: 即座に実装可能な改善（リスク低）

```sql
-- 1. バックアップを取る
mysqldump -u [username] -p [database_name] > backup_$(date +%Y%m%d).sql

-- 2. インデックスの追加（既存データに影響なし）
ALTER TABLE events ADD INDEX idx_area (area);
ALTER TABLE events ADD INDEX idx_date (date);
ALTER TABLE events ADD INDEX idx_area_date (area, date);
ALTER TABLE customers ADD INDEX idx_event (event);
ALTER TABLE customers ADD INDEX idx_mail (mail);

-- 3. content テーブルの作成（もし存在しない場合）
CREATE TABLE IF NOT EXISTS content (
    num INT PRIMARY KEY,
    title VARCHAR(255),
    name VARCHAR(255),
    text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Phase 2: 新機能の追加（既存機能に影響なし）

```sql
-- 1. 新規テーブルの作成
-- event_categories, price_settings, mail_history など

-- 2. 既存テーブルへの列追加（デフォルト値付き）
ALTER TABLE area 
ADD COLUMN capacity INT DEFAULT 0,
ADD COLUMN is_active BOOLEAN DEFAULT TRUE;

ALTER TABLE events
ADD COLUMN capacity_m INT DEFAULT 0,
ADD COLUMN capacity_w INT DEFAULT 0,
ADD COLUMN current_m INT DEFAULT 0,
ADD COLUMN current_w INT DEFAULT 0;

-- 3. ビューの作成
CREATE VIEW v_event_details AS ...
```

### Phase 3: データ移行とコード修正

```php
// 1. 新しい列にデータを移行
// migrate_capacity.php
<?php
require_once("db_data/db_init.php");

// eventsテーブルの定員を設定（既存の満席情報から推測）
$stmt = $db->prepare("
    UPDATE events e
    SET capacity_m = 20, capacity_w = 20
    WHERE e.state_m = 0 OR e.state_w = 0
");
$stmt->execute();
?>

// 2. 現在の参加者数を計算
<?php
$stmt = $db->prepare("
    UPDATE events e
    SET 
        current_m = (SELECT COUNT(*) FROM customers WHERE event = e.find AND sex = 'M' AND state = 1),
        current_w = (SELECT COUNT(*) FROM customers WHERE event = e.find AND sex = 'F' AND state = 1)
");
$stmt->execute();
?>
```

### Phase 4: コードのリファクタリング

```php
// 旧コード（直接SQL）
$pageDataTmp = $db->query("select * from area where area = '$area' ;");

// 新コード（プリペアドステートメント + ビュー使用）
$stmt = $db->prepare("
    SELECT * FROM v_event_details 
    WHERE area = :area 
    AND event_status = 'future'
    ORDER BY date ASC
");
$stmt->execute(['area' => $area]);
```

## セキュリティ改善

### SQLインジェクション対策

```php
// db_helper.php - 共通データベース関数
<?php
class DBHelper {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // エリア情報取得
    public function getArea($area) {
        $stmt = $this->db->prepare("SELECT * FROM area WHERE area = ?");
        $stmt->execute([$area]);
        return $stmt->fetch();
    }
    
    // イベント一覧取得
    public function getEvents($area = null, $date = null) {
        $sql = "SELECT * FROM v_event_details WHERE 1=1";
        $params = [];
        
        if ($area) {
            $sql .= " AND area = ?";
            $params[] = $area;
        }
        
        if ($date) {
            $sql .= " AND date = ?";
            $params[] = $date;
        }
        
        $sql .= " ORDER BY date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // 参加者登録
    public function registerCustomer($data) {
        $stmt = $this->db->prepare("
            INSERT INTO customers 
            (find, area, event, sex, name, hurigana, age, mail, tel, ninzu, date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        return $stmt->execute([
            $data['find'],
            $data['area'],
            $data['event'],
            $data['sex'],
            $data['name'],
            $data['hurigana'],
            $data['age'],
            $data['mail'],
            $data['tel'],
            $data['ninzu']
        ]);
    }
}
?>
```

## モニタリングとメンテナンス

### 1. パフォーマンス監視

```sql
-- スロークエリの確認
SHOW VARIABLES LIKE 'slow_query_log';
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 1;

-- インデックスの使用状況確認
EXPLAIN SELECT * FROM events WHERE area = 'tokyo' AND date >= CURDATE();
```

### 2. データ整合性チェック

```sql
-- 孤立したレコードの確認
SELECT c.* FROM customers c 
LEFT JOIN events e ON c.event = e.find 
WHERE e.find IS NULL;

-- 重複データの確認
SELECT mail, COUNT(*) as cnt 
FROM customers 
GROUP BY mail 
HAVING cnt > 1;
```

### 3. 定期メンテナンス

```bash
# crontab -e
# 毎日深夜2時に最適化実行
0 2 * * * mysql -u[user] -p[pass] [db] -e "OPTIMIZE TABLE events, customers, area;"

# 毎週日曜日にバックアップ
0 3 * * 0 mysqldump -u[user] -p[pass] [db] > /backup/db_$(date +\%Y\%m\%d).sql
```

## トラブルシューティング

### 文字化け対策
```sql
-- データベースの文字コード確認
SHOW VARIABLES LIKE 'character_set%';

-- テーブルの文字コード変更
ALTER TABLE area CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE events CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE customers CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### パフォーマンス問題
```sql
-- インデックスの再構築
ALTER TABLE events DROP INDEX idx_area, ADD INDEX idx_area (area);

-- テーブルの最適化
OPTIMIZE TABLE events;
ANALYZE TABLE events;
```

## まとめ

この改善により期待される効果：
1. **検索速度**: 最大10倍の高速化
2. **データ整合性**: 不正なデータの防止
3. **運用効率**: 自動化により手作業を削減
4. **拡張性**: 新機能の追加が容易に
5. **セキュリティ**: SQLインジェクション対策

段階的に実装することで、リスクを最小限に抑えながら改善できます。