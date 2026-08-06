# ホーチキ本番反映 — デプロイガイド

**対象サーバー:** `192.168.98.102`  
**アプリパス:** `/var/www/hochiki`  
**ソース:** nespe_dia 開発環境 (`C:\xampp\htdocs\hochiki`) からの差分反映

---

## 反映内容（8機能）

| # | 機能 | 主なファイル |
|---|------|-------------|
| 1 | 大型物件対応（`max_input_vars`） | `s_make_kanryo_hensyu.php` / `.tpl` |
| 2 | 部屋配置 Ctrl+ドラッグ選択 | `s_make_matrix.php` / `.tpl` |
| 3 | 工程表一時保存 | `s_make_kanryo_hensyu.*` + `SPUSReservationTemp.cls` |
| 4 | 日程変更一覧・最終更新日時 | `sh_henko_list.*` |
| 5 | Excel DL ステータス表示 | `s_henko_download.php` |
| 6 | 物件基本情報（棟別専有部・受付締切） | `s_kihon_form.*` + `building_period_helpers.php` |
| 7 | 休工日 全日/午前/午後 | `s_kihon_form.*`, `s_make_kanryo2.*`, `holiday_helpers.php` |
| 8 | アラート（ベル + TEL受付） | `bukken_alert.*`, `sh_list.php`, SQL |

---

## 事前準備

### 1. デプロイ zip の作成（Windows 開発 PC）

```powershell
cd C:\xampp\htdocs\hochiki
.\deploy\create-deploy-package.ps1
```

`deploy\hochiki-deploy-YYYYMMDD-HHMM.zip` が生成されます。

### 2. 本番バックアップ（必須）

```bash
# サーバー上で実行
sudo cp -a /var/www/hochiki /var/www/hochiki.backup.$(date +%Y%m%d)
mysqldump -u USER -p DBNAME > /tmp/hochiki_db_$(date +%Y%m%d).sql
```

---

## デプロイ手順

### Step 1 — ファイル配置

zip を本番に転送し、パス構造を維持して上書き:

```bash
cd /var/www/hochiki
unzip -o /path/to/hochiki-deploy-*.zip
```

対象一覧は `deploy/files-manifest.txt` を参照。

> **注意:** `SPFW/inc/setting.properties` は zip に含まれますが、**DB 接続情報は本番既存値を必ず維持**してください（下記 Step 2）。

### Step 2 — setting.properties のマージ（重要）

`deploy/setting.properties.hochiki.snippet` を参照し、本番の  
`/var/www/hochiki/SPFW/inc/setting.properties` に以下を追加/更新:

```php
define("_LIBRARY_ROOT", "/var/www/hochiki/SPFW/");
define("_DOCUMENT_ROOT", "/var/www/hochiki/httpdocs/");

// hochiki 本番の工程表ラベル（必須）
define('_SLOT_LABEL_AKI', '余地');
define('_SLOT_LABEL_WAKUOVER', '時間外');

// slot label ヘルパー読込（開発版 setting.properties に同梱済み）
if (file_exists(_DOCUMENT_ROOT . 'include/kotei_slot_labels.php')) {
    include_once _DOCUMENT_ROOT . 'include/kotei_slot_labels.php';
}
```

| 環境 | 空き枠 | 枠越 |
|------|--------|------|
| nespe_dia 開発 | `空き` | `枠越` |
| **hochiki 本番** | **`余地`** | **`時間外`** |

`setting.properties` で上記を定義すると、`s_format.php` / `sh_list.php` / `s_make_kanryo_hensyu.php` が自動的に本番表記になります。

### Step 3 — PHP 設定

`deploy/php.ini.snippet` を参照:

```ini
max_input_vars = 5000
```

反映後:

```bash
sudo systemctl restart apache2
# または php-fpm 利用時
sudo systemctl restart php8.2-fpm && sudo systemctl restart apache2
```

### Step 4 — DB マイグレーション（最小・既存データ維持）

**詳細:** `deploy/DB_MIGRATION.md` を参照。

```bash
# 1) 現状確認
mysql -u USER -p DBNAME < deploy/check-db.sql

# 2) バックアップ
mysqldump -u USER -p DBNAME > /tmp/hochiki_backup_$(date +%Y%m%d).sql

# 3) 不足テーブル/カラムのみ追加（既存行は UPDATE しない）
mysql -u USER -p DBNAME < deploy/migrate-hochiki-minimal.sql
```

| 追加対象 | 内容 |
|----------|------|
| `tBukkenWebActivityF` | アラート用（+ `LastTelActivityAt` が無ければ追加） |
| `tBukkenAlertReadF` | 既読管理 |
| `tReservationTempF` | 無い場合のみ（一時保存） |

**変更不要:** `Holiday1`, `YoyakuEndDate` 等の既存カラム・既存物件データの一括更新は不要。

### Step 5 — system.properties（アラート JS）

`SPFW/inc/system.properties` の `$SHeaderKanri2` に以下が含まれることを確認（**ファイル全体は上書きしない**）:

```php
$SHeaderKanri2 .= '<script src="./js/bukken_alert.js?v=7"></script>';
```

### Step 6 — キャッシュ

`SPFW/inc/system.properties` に `bukken_alert.js?v=7` が含まれていることを確認。  
ブラウザで Ctrl+F5 強制リロード。

---

## 本番テストチェックリスト

### 基本

- [ ] ログイン（幹事・協力会社・住人）
- [ ] メニュー表示

### 物件基本情報 (`s_kihon_form.php`)

- [ ] 棟1 に休工日 select（全日/午前/午後）
- [ ] 棟追加後も休工 select 表示
- [ ] 棟別 受付締切日・専有部期間の保存

### 詳細工程表 (`s_make_kanryo2.php`)

- [ ] 休工 午前のみ → 午前枠が選択不可
- [ ] 階カレンダーで全日休工日は選択不可

### 部屋配置 (`s_make_matrix.php`)

- [ ] Ctrl+ドラッグで矩形選択
- [ ] 通常ドラッグは横一列選択

### 工程表編集 (`s_make_kanryo_hensyu.php`)

- [ ] 一時保存 → 再表示 confirm
- [ ] **大型物件**（1500室超）で保存エラーが出ない
- [ ] セル表記が **`余地` / `時間外`**（`空き` / `枠越` ではない）

### 日程変更 (`sh_henko_list.php` / `s_henko_download.php`)

- [ ] 最終更新日時列
- [ ] Excel DL にステータス列

### TEL受付 (`sh_list.php`)

- [ ] 休工半日が正しく表示
- [ ] セル表記 **`余地` / `時間外`**
- [ ] 協力会社ログインで TEL 更新後ベル通知

### アラート

- [ ] 住人 WEB 予約 → 幹事ベル点灯
- [ ] ベルから物件へ遷移 → 既読
- [ ] 協力会社は TEL 更新も通知対象

### 回帰

- [ ] 工程表 Excel 出力
- [ ] 案内資料生成
- [ ] WEB 予約（休工日ブロック）

---

## トラブルシューティング

| 症状 | 対処 |
|------|------|
| 工程表保存で PHP エラー / 変数欠落 | `max_input_vars` を 5000 に。`s_make_kanryo_hensyu.tpl` の 300件バッチ hidden がデプロイ済みか確認 |
| 画面に `空き`/`枠越` と表示される | 本番 `setting.properties` の `_SLOT_LABEL_*` が `余地`/`時間外` か確認 |
| ベルが出ない | `migrate-alert.sql` 実行済みか、`get_bukken_alerts.php` のエラーログ確認 |
| include エラー | 本番 `setting.properties` の `_LIBRARY_ROOT` / `_DOCUMENT_ROOT` パス確認 |

---

## ファイル構成

```
deploy/
  DEPLOY_GUIDE.md              ← 本ドキュメント
  files-manifest.txt           ← デプロイ対象一覧
  create-deploy-package.ps1    ← zip 作成スクリプト
  migrate-alert.sql            ← アラート用 DDL
  php.ini.snippet              ← サーバー PHP 設定
  setting.properties.hochiki.snippet  ← 本番 setting 差分
  apply_slot_labels.py         ← 開発用（ラベル定数化）
```

---

## 開発環境との違い

| 項目 | 開発 (nespe_dia) | 本番 (hochiki) |
|------|------------------|----------------|
| DB | `nespe_dia` | 本番 DB 名（既存値維持） |
| パス | `C:/xampp/htdocs/hochiki/` | `/var/www/hochiki/` |
| スロットラベル | 空き / 枠越 | **余地 / 時間外** |

開発 PC の `SPFW/inc/setting.properties` は nespe_dia 用のままです。本番では **snippet のみマージ**し、DB 設定は上書きしないでください。
