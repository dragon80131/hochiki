-- =============================================================================
-- hochiki DB 事前確認（本番で実行して不足を確認）
-- Usage: mysql -u USER -p DBNAME < deploy/check-db.sql
-- =============================================================================

SELECT '=== 1. アラート用テーブル ===' AS section;
SHOW TABLES LIKE 'tBukkenWebActivityF';
SHOW TABLES LIKE 'tBukkenAlertReadF';

SELECT '=== 2. tBukkenWebActivityF カラム ===' AS section;
SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_COMMENT
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'tBukkenWebActivityF'
ORDER BY ORDINAL_POSITION;

SELECT '=== 3. 工程表一時保存テーブル ===' AS section;
SHOW TABLES LIKE 'tReservationTempF';

SELECT '=== 4. 休工日・受付締切（既存カラム確認）===' AS section;
SELECT COLUMN_NAME, COLUMN_TYPE
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('tBukkenM', 'tBuildingM')
  AND COLUMN_NAME IN ('Holiday1', 'YoyakuEndDate', 'SenyuStartDate', 'SenyuEndDate', 'SenyuStartDate1', 'SenyuEndDate1')
ORDER BY TABLE_NAME, COLUMN_NAME;

SELECT '=== 5. 休工日データ形式サンプル（先頭5件）===' AS section;
SELECT BukkenCD, BukkenName,
       LEFT(Holiday1, 80) AS Holiday1_sample
FROM tBukkenM
WHERE MukouFlg = 0 AND Holiday1 IS NOT NULL AND Holiday1 != ''
LIMIT 5;

SELECT '=== 6. 棟マスタの受付締切 ===' AS section;
SELECT COUNT(*) AS building_rows,
       SUM(CASE WHEN YoyakuEndDate IS NOT NULL AND YoyakuEndDate != '' AND YoyakuEndDate != '0000-00-00' THEN 1 ELSE 0 END) AS with_yoyaku_end
FROM tBuildingM
WHERE MukouFlg = 0;
