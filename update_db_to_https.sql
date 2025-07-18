-- データベース内のHTTP URLをHTTPSに更新するSQLクエリ
-- 実行前に必ずバックアップを取得してください

-- eventsテーブルの更新（存在する場合）
-- koikoi.co.jpドメインのみを更新
UPDATE events 
SET event_name = REPLACE(event_name, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    event_name = REPLACE(event_name, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE event_name LIKE '%http://%koikoi.co.jp%';

UPDATE events 
SET place = REPLACE(place, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    place = REPLACE(place, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE place LIKE '%http://%koikoi.co.jp%';

UPDATE events 
SET event_contents = REPLACE(event_contents, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    event_contents = REPLACE(event_contents, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE event_contents LIKE '%http://%koikoi.co.jp%';

UPDATE events 
SET detail = REPLACE(detail, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    detail = REPLACE(detail, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE detail LIKE '%http://%koikoi.co.jp%';

UPDATE events 
SET access = REPLACE(access, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    access = REPLACE(access, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE access LIKE '%http://%koikoi.co.jp%';

UPDATE events 
SET url = REPLACE(url, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    url = REPLACE(url, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE url LIKE '%http://%koikoi.co.jp%';

-- areaテーブルの更新（存在する場合）
UPDATE area 
SET description = REPLACE(description, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    description = REPLACE(description, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE description LIKE '%http://%koikoi.co.jp%';

-- mail_templateテーブルの更新（存在する場合）
UPDATE mail_template 
SET subject = REPLACE(subject, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    subject = REPLACE(subject, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE subject LIKE '%http://%koikoi.co.jp%';

UPDATE mail_template 
SET body = REPLACE(body, 'http://www.koikoi.co.jp', 'https://www.koikoi.co.jp'),
    body = REPLACE(body, 'http://koikoi.co.jp', 'https://koikoi.co.jp')
WHERE body LIKE '%http://%koikoi.co.jp%';

-- 更新件数を確認するクエリ
SELECT 'events' as table_name, COUNT(*) as http_urls_count
FROM events 
WHERE event_name LIKE '%http://%' 
   OR place LIKE '%http://%'
   OR event_contents LIKE '%http://%'
   OR detail LIKE '%http://%'
   OR access LIKE '%http://%'
   OR url LIKE '%http://%'
UNION ALL
SELECT 'area' as table_name, COUNT(*) as http_urls_count
FROM area 
WHERE description LIKE '%http://%'
UNION ALL
SELECT 'mail_template' as table_name, COUNT(*) as http_urls_count
FROM mail_template 
WHERE subject LIKE '%http://%' 
   OR body LIKE '%http://%';