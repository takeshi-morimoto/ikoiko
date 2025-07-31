-- データベース改善案
-- 既存のデータを保持しながら段階的に改善できる提案

-- ========================================
-- 1. インデックスの追加（パフォーマンス改善）
-- ========================================

-- events テーブル
ALTER TABLE events ADD INDEX idx_area (area);
ALTER TABLE events ADD INDEX idx_date (date);
ALTER TABLE events ADD INDEX idx_area_date (area, date);
ALTER TABLE events ADD INDEX idx_state (state_m, state_w);

-- customers テーブル
ALTER TABLE customers ADD INDEX idx_event (event);
ALTER TABLE customers ADD INDEX idx_area (area);
ALTER TABLE customers ADD INDEX idx_date (date);
ALTER TABLE customers ADD INDEX idx_mail (mail);
ALTER TABLE customers ADD INDEX idx_state (state);
ALTER TABLE customers ADD INDEX idx_payment_state (state, payment_d);

-- area テーブル
ALTER TABLE area ADD INDEX idx_ken (ken);
ALTER TABLE area ADD INDEX idx_page (page);

-- ========================================
-- 2. 新規テーブルの追加提案
-- ========================================

-- 都道府県マスタ
CREATE TABLE IF NOT EXISTS prefectures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(2) UNIQUE NOT NULL,
    name VARCHAR(10) NOT NULL,
    region VARCHAR(20),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_region (region)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- イベントカテゴリマスタ
CREATE TABLE IF NOT EXISTS event_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    color VARCHAR(7), -- #RRGGBB形式
    icon_path VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 価格設定テーブル（より柔軟な価格管理）
CREATE TABLE IF NOT EXISTS price_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    area_id INT,
    event_category_id INT,
    price_type ENUM('early_bird', 'regular', 'late', 'pair', 'group') DEFAULT 'regular',
    gender ENUM('M', 'F', 'OTHER') NOT NULL,
    price INT NOT NULL,
    start_date DATE,
    end_date DATE,
    conditions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (area_id) REFERENCES area(number),
    FOREIGN KEY (event_category_id) REFERENCES event_categories(id),
    INDEX idx_area_category (area_id, event_category_id),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- メール配信履歴
CREATE TABLE IF NOT EXISTS mail_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    event_id INT,
    mail_type ENUM('confirmation', 'reminder', 'thank_you', 'cancel', 'other') NOT NULL,
    subject VARCHAR(255),
    body TEXT,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    sent_at DATETIME,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(number),
    FOREIGN KEY (event_id) REFERENCES events(number),
    INDEX idx_customer (customer_id),
    INDEX idx_event (event_id),
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- アクセスログ（分析用）
CREATE TABLE IF NOT EXISTS access_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(128),
    user_agent VARCHAR(255),
    ip_address VARCHAR(45),
    page_type VARCHAR(50),
    page_id INT,
    area VARCHAR(32),
    event_id INT,
    referrer TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_created (created_at),
    INDEX idx_page (page_type, page_id),
    INDEX idx_area (area)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 3. 既存テーブルの改良
-- ========================================

-- area テーブルに列追加
ALTER TABLE area 
ADD COLUMN IF NOT EXISTS prefecture_id INT AFTER ken,
ADD COLUMN IF NOT EXISTS capacity INT DEFAULT 0 COMMENT '会場定員',
ADD COLUMN IF NOT EXISTS google_map_url TEXT,
ADD COLUMN IF NOT EXISTS access_info TEXT COMMENT 'アクセス情報',
ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT TRUE,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- events テーブルに列追加
ALTER TABLE events
ADD COLUMN IF NOT EXISTS category_id INT AFTER area,
ADD COLUMN IF NOT EXISTS capacity_m INT DEFAULT 0 COMMENT '男性定員',
ADD COLUMN IF NOT EXISTS capacity_w INT DEFAULT 0 COMMENT '女性定員',
ADD COLUMN IF NOT EXISTS current_m INT DEFAULT 0 COMMENT '男性現在数',
ADD COLUMN IF NOT EXISTS current_w INT DEFAULT 0 COMMENT '女性現在数',
ADD COLUMN IF NOT EXISTS min_participants INT DEFAULT 0 COMMENT '最少催行人数',
ADD COLUMN IF NOT EXISTS cancel_deadline DATETIME COMMENT 'キャンセル期限',
ADD COLUMN IF NOT EXISTS is_published BOOLEAN DEFAULT TRUE,
ADD COLUMN IF NOT EXISTS view_count INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD FOREIGN KEY (category_id) REFERENCES event_categories(id);

-- customers テーブルに列追加
ALTER TABLE customers
ADD COLUMN IF NOT EXISTS customer_code VARCHAR(20) UNIQUE AFTER number,
ADD COLUMN IF NOT EXISTS birth_date DATE AFTER age,
ADD COLUMN IF NOT EXISTS postal_code VARCHAR(8),
ADD COLUMN IF NOT EXISTS address TEXT,
ADD COLUMN IF NOT EXISTS occupation VARCHAR(100),
ADD COLUMN IF NOT EXISTS referral_source VARCHAR(50),
ADD COLUMN IF NOT EXISTS is_member BOOLEAN DEFAULT FALSE,
ADD COLUMN IF NOT EXISTS member_since DATE,
ADD COLUMN IF NOT EXISTS total_participations INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS last_participation_date DATE,
ADD COLUMN IF NOT EXISTS notes TEXT,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ========================================
-- 4. データ整合性の改善
-- ========================================

-- 外部キー制約の追加（既存データに問題がない場合のみ）
-- ALTER TABLE events ADD FOREIGN KEY (area) REFERENCES area(area) ON DELETE RESTRICT ON UPDATE CASCADE;
-- ALTER TABLE customers ADD FOREIGN KEY (area) REFERENCES area(area) ON DELETE RESTRICT ON UPDATE CASCADE;

-- ========================================
-- 5. ビューの作成（よく使うクエリの簡略化）
-- ========================================

-- イベント詳細ビュー
CREATE OR REPLACE VIEW v_event_details AS
SELECT 
    e.*,
    a.area_ja,
    a.ken,
    a.place,
    a.price_h,
    a.price_l,
    a.age_m,
    a.age_w,
    (e.capacity_m - e.current_m) as available_m,
    (e.capacity_w - e.current_w) as available_w,
    CASE 
        WHEN e.date < CURDATE() THEN 'past'
        WHEN e.date = CURDATE() THEN 'today'
        ELSE 'future'
    END as event_status
FROM events e
JOIN area a ON e.area = a.area;

-- 顧客統計ビュー
CREATE OR REPLACE VIEW v_customer_stats AS
SELECT 
    c.customer_code,
    c.name,
    c.mail,
    COUNT(DISTINCT c.event) as total_events,
    MAX(c.date) as last_registration,
    SUM(CASE WHEN c.state = 1 THEN 1 ELSE 0 END) as confirmed_participations,
    GROUP_CONCAT(DISTINCT a.ken) as participated_prefectures
FROM customers c
LEFT JOIN area a ON c.area = a.area
GROUP BY c.customer_code, c.name, c.mail;

-- ========================================
-- 6. ストアドプロシージャ（複雑な処理の簡略化）
-- ========================================

DELIMITER //

-- イベント参加者数を更新
CREATE PROCEDURE update_event_participants(IN event_find VARCHAR(32))
BEGIN
    UPDATE events e
    SET 
        current_m = (SELECT COUNT(*) FROM customers WHERE event = event_find AND sex = 'M' AND state = 1),
        current_w = (SELECT COUNT(*) FROM customers WHERE event = event_find AND sex = 'F' AND state = 1)
    WHERE e.find = event_find;
END//

-- 満席チェック
CREATE FUNCTION is_event_full(event_find VARCHAR(32), gender CHAR(1)) 
RETURNS BOOLEAN
DETERMINISTIC
BEGIN
    DECLARE capacity INT;
    DECLARE current INT;
    
    IF gender = 'M' THEN
        SELECT capacity_m, current_m INTO capacity, current 
        FROM events WHERE find = event_find;
    ELSE
        SELECT capacity_w, current_w INTO capacity, current 
        FROM events WHERE find = event_find;
    END IF;
    
    RETURN current >= capacity;
END//

DELIMITER ;

-- ========================================
-- 7. トリガー（自動処理）
-- ========================================

DELIMITER //

-- 顧客登録時に参加者数を自動更新
CREATE TRIGGER after_customer_insert
AFTER INSERT ON customers
FOR EACH ROW
BEGIN
    IF NEW.state = 1 THEN
        CALL update_event_participants(NEW.event);
    END IF;
END//

-- 顧客情報更新時に参加者数を自動更新
CREATE TRIGGER after_customer_update
AFTER UPDATE ON customers
FOR EACH ROW
BEGIN
    IF OLD.state != NEW.state OR OLD.event != NEW.event THEN
        CALL update_event_participants(OLD.event);
        IF OLD.event != NEW.event THEN
            CALL update_event_participants(NEW.event);
        END IF;
    END IF;
END//

DELIMITER ;

-- ========================================
-- 8. 初期データの投入（マスタデータ）
-- ========================================

-- 都道府県データ
INSERT INTO prefectures (code, name, region, display_order) VALUES
('01', '北海道', '北海道', 1),
('02', '青森県', '東北', 2),
('03', '岩手県', '東北', 3),
-- ... 他の都道府県も同様に

-- イベントカテゴリ
INSERT INTO event_categories (code, name, description, color) VALUES
('anime', 'アニメコン', 'アニメ・マンガ好きのための婚活イベント', '#FF6B6B'),
('machi', '街コン', '地域密着型の出会いイベント', '#4ECDC4'),
('nazo', '謎解き', '謎解きを楽しみながらの交流イベント', '#45B7D1'),
('online', 'オンライン', 'オンラインでの交流イベント', '#96CEB4');