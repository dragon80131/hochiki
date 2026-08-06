# hochiki DB — 最小更新ガイド

本番 DB を**維持**し、今回のデプロイで**必要なテーブル/カラムだけ**追加する手順です。  
既存物件データの一括更新は**不要**です（スタッフが画面から保存するときに新形式が入ります）。

---

## 結論（何を変えるか）

| 区分 | 対象 | 本番での作業 |
|------|------|-------------|
| **追加が必要な場合あり** | `tBukkenWebActivityF` | テーブル新規 or `LastTelActivityAt` カラム追加 |
| **追加が必要な場合あり** | `tBukkenAlertReadF` | テーブル新規 |
| **追加が必要な場合あり** | `tReservationTempF` | テーブルが無ければ新規（一時保存用） |
| **変更不要** | `tBukkenM.Holiday1` | 既存カラム。旧データはそのまま動作 |
| **変更不要** | `tBukkenM.YoyakuEndDate` | 既存カラム |
| **変更不要** | `tBuildingM.Holiday1` | 既存カラム |
| **変更不要** | `tBuildingM.YoyakuEndDate` | 既存カラム |
| **変更不要** | `tUserM` / `tReservationF` | アラートは別テーブルで管理 |
| **変更不要** | 物件・予約の既存レコード一括 UPDATE | 不要 |

---

## Step 1 — 現状確認

```bash
mysql -u USER -p DBNAME < deploy/check-db.sql
```

確認ポイント:

1. `tBukkenWebActivityF` があるか  
2. `LastTelActivityAt` カラムがあるか  
3. `tReservationTempF` があるか  
4. `tBukkenM` / `tBuildingM` に `Holiday1`, `YoyakuEndDate` があるか（通常は既にある）

---

## Step 2 — バックアップ（必須）

```bash
mysqldump -u USER -p DBNAME > /tmp/hochiki_backup_$(date +%Y%m%d).sql
```

---

## Step 3 — 最小マイグレーション実行

```bash
mysql -u USER -p DBNAME < deploy/migrate-hochiki-minimal.sql
```

この SQL は:

- `CREATE TABLE IF NOT EXISTS` のみ（既存テーブルは触らない）
- `ALTER TABLE ADD COLUMN` は **カラムが無いときだけ** 実行
- **既存行の UPDATE は一切しない**

---

## 既存データについて（変更不要な理由）

### 休工日 `Holiday1`

| 旧形式（そのまま有効） | 新形式（保存時に自動） |
|------------------------|------------------------|
| `\|2026-10-03\|`（全日） | `\|2026-10-03\|` または `\|2026-10-05:AM\|` |

- 日付だけの旧データは **全日休工** として扱われます。
- 午前/午後を付けたい物件は、スタッフが **物件基本情報** または **詳細工程表** で保存し直せばよいです。
- **DB 一括変換は不要**です。

### 受付締切 `YoyakuEndDate`

- 棟1: `tBukkenM.YoyakuEndDate`（既存）
- 棟2以降: `tBuildingM.YoyakuEndDate`（既存）
- 未設定の棟は、デプロイ後に **物件基本情報** 画面で入力・保存。

### アラート `tBukkenWebActivityF`

- 新規テーブル。住人予約や TEL 受付が発生すると **自動で行が INSERT** されます。
- 既存物件に対する事前データ投入は不要。

---

## 手動で触る必要がある「データ」がある場合

通常は不要です。次のときだけ画面から更新してください。

| やりたいこと | 操作 |
|--------------|------|
| 棟ごとに受付締切を分けたい | `s_kihon_form` で棟別 `YoyakuEndDate` を保存 |
| 休工を午前/午後にしたい | 同画面または `s_make_kanryo2` で休工日を保存 |
| アラート既読をリセットしたい | 通常不要。`tBukkenAlertReadF` は運用で自然に更新 |

**一括 SQL で既存 `Holiday1` を変換する必要はありません。**

---

## ロールバック

```bash
# ファイルデプロイを戻したうえで
mysql -u USER -p DBNAME < /tmp/hochiki_backup_YYYYMMDD.sql
```

追加したテーブルだけ消す場合（データ損失に注意）:

```sql
-- DROP TABLE IF EXISTS tBukkenAlertReadF;
-- DROP TABLE IF EXISTS tBukkenWebActivityF;
```

---

## 補足: PHP 側の自動マイグレーション

`httpdocs/include/bukken_alert.php` は初回アクセス時にテーブル/カラムを自動作成します。  
本番では **SQL を先に実行** することを推奨します（権限・ログのため）。

---

## 関連ファイル

| ファイル | 用途 |
|----------|------|
| `deploy/check-db.sql` | 事前確認 |
| `deploy/migrate-hochiki-minimal.sql` | 最小追加 |
| `deploy/migrate-alert.sql` | アラートのみ（簡易版） |
| `sql/tBukkenWebActivity.sql` | DDL 参照用 |
