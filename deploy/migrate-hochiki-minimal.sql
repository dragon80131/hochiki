-- =============================================================================
-- hochiki 本番 DB — 最小マイグレーション（不足分のみ追加）
--
-- 方針:
--   - 既存テーブル・既存データは維持
--   - 新機能に必要な「テーブル/カラム」だけ追加
--   - Holiday1 / YoyakuEndDate 等は既存カラムをそのまま利用（ALTER 不要）
--
-- Usage:
--   mysql -u USER -p DBNAME < deploy/migrate-hochiki-minimal.sql
--
-- 実行前に必ずバックアップ:
--   mysqldump -u USER -p DBNAME > hochiki_backup_$(date +%Y%m%d).sql
-- =============================================================================

-- -----------------------------------------------------------------------------
-- A. アラート機能（新テーブル）
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS tBukkenWebActivityF (
  BukkenCD int NOT NULL,
  LastWebActivityAt datetime NOT NULL,
  LastActivityType tinyint NOT NULL DEFAULT 1 COMMENT '1:予約 2:更新',
  LastTelActivityAt datetime NULL COMMENT 'TEL受付更新日時（協力会社向け）',
  PRIMARY KEY (BukkenCD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='WEB予約・更新通知用';

CREATE TABLE IF NOT EXISTS tBukkenAlertReadF (
  UserCD int NOT NULL,
  BukkenCD int NOT NULL,
  LastReadAt datetime NOT NULL,
  PRIMARY KEY (UserCD, BukkenCD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='物件更新通知既読';

-- 既に tBukkenWebActivityF がある旧環境向け: カラムが無い場合のみ追加
SET @db := DATABASE();

-- LastActivityType
SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'tBukkenWebActivityF' AND COLUMN_NAME = 'LastActivityType'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE tBukkenWebActivityF ADD COLUMN LastActivityType tinyint NOT NULL DEFAULT 1 COMMENT ''1:予約 2:更新'' AFTER LastWebActivityAt',
  'SELECT ''skip: LastActivityType exists'' AS msg'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- LastTelActivityAt（協力会社 TEL 受付アラート用）
SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'tBukkenWebActivityF' AND COLUMN_NAME = 'LastTelActivityAt'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE tBukkenWebActivityF ADD COLUMN LastTelActivityAt datetime NULL COMMENT ''TEL受付更新日時（協力会社向け）'' AFTER LastActivityType',
  'SELECT ''skip: LastTelActivityAt exists'' AS msg'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- -----------------------------------------------------------------------------
-- B. 工程表一時保存（テーブルが無い環境のみ）
--    ※ 本番に既にある場合は CREATE IF NOT EXISTS でスキップ
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS tReservationTempF (
  ReservationCD int NOT NULL,
  ClientCD int DEFAULT NULL,
  UserCD int DEFAULT NULL,
  BukkenCD int DEFAULT NULL,
  BuildingCD int DEFAULT NULL,
  ReservationInfo mediumtext,
  Created datetime DEFAULT NULL,
  Creator int DEFAULT NULL,
  Updated datetime DEFAULT NULL,
  Updater int DEFAULT NULL,
  PRIMARY KEY (ReservationCD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='工程表案 一時保存';

-- -----------------------------------------------------------------------------
-- 完了確認
-- -----------------------------------------------------------------------------
SELECT 'migration complete' AS status;
SHOW TABLES LIKE 'tBukkenWebActivityF';
SHOW TABLES LIKE 'tBukkenAlertReadF';
SHOW TABLES LIKE 'tReservationTempF';
