-- WEB予約・更新通知（マンション単位アラート）
-- nespe_dia / 本番反映時に実行

CREATE TABLE IF NOT EXISTS tBukkenWebActivityF (
  BukkenCD int NOT NULL,
  LastWebActivityAt datetime NOT NULL,
  PRIMARY KEY (BukkenCD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='WEB予約・更新通知用';

CREATE TABLE IF NOT EXISTS tBukkenAlertReadF (
  UserCD int NOT NULL,
  BukkenCD int NOT NULL,
  LastReadAt datetime NOT NULL,
  PRIMARY KEY (UserCD, BukkenCD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='物件更新通知既読';
