-- ホーチキ本番反映: WEB予約・更新通知（ベルアイコン）
-- 本番 DB で実行（テーブルが無い場合のみ / カラム追加は下記コメント参照）

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

-- 既存環境で tBukkenWebActivityF があるが LastTelActivityAt が無い場合:
-- ALTER TABLE tBukkenWebActivityF ADD COLUMN LastTelActivityAt datetime NULL COMMENT 'TEL受付更新日時（協力会社向け）' AFTER LastActivityType;
