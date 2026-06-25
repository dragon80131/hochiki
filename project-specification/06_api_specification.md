# 06. API Specification

> The system is a classic server-rendered PHP application: most "endpoints" are full-page controllers that accept GET/POST and render an HTML template. This document focuses on the **AJAX / background endpoints** that return data or perform an action without rendering a page, plus the page-level POST endpoints that act as form handlers.
>
> For each: Method, URL, Purpose, Request, Response, Authentication, Validation, and which screen uses it.

---

## Conventions

- **Base path:** all under `https://<host>/kotei/` (e.g. production `https://app5.489501.jp/kotei/`). Locally `http://localhost/`.
- **Auth token:** most endpoints expect `rKey` (the session token from login). Parameters are read with `SPFWParameter::getValues()` from GET or POST.
- **Encoding:** responses are UTF-8 unless noted.

> ⚠️ **Security note:** several write endpoints below have their authentication commented out or absent, and interpolate request values into SQL/file paths. These are documented honestly and flagged in **15_improvement_suggestions.md**.

---

## AJAX endpoints (return data / perform action)

### GET/POST `get_bukken_alerts.php` — staff bell: fetch alerts
- **Purpose:** return recent per-property web-activity alerts (resident bookings/updates) for the bell in the staff header.
- **Request:** `rKey` (auth), `m` (echoed back).
- **Response:** JSON
  ```json
  { "count": 3, "items": [
      { "BukkenCD": 12, "BukkenName": "…", "LastWebActivityAt": "2026-06-20 10:00:00",
        "ActivityType": 1, "ActivityLabel": "予約", "IsUnread": true } ],
    "m": "" }
  ```
  (latest 8 items; empty JSON on auth failure).
- **Authentication:** `authenticateKanriUserForAlert($myDB, $rKey)` → `User::doAuthenticationByRegistKey`. Residents (`UserKbn=4`) get nothing.
- **Validation:** none beyond auth.
- **Used by:** `js/bukken_alert.js` (header on every `s_*` page).
- **Tables:** `tBukkenWebActivityF` ⨝ `tBukkenM` ⟕ `tBukkenAlertReadF`.

### POST `mark_bukken_alert_read.php` — staff bell: mark read
- **Purpose:** mark one property's alert (or all in scope) as read.
- **Request:** `rKey`, plus either `markAll=1` or `editBukkenCD=<id>`.
- **Response:** JSON `{ "ok": true }`.
- **Authentication:** same as above (`rKey`).
- **Used by:** `js/bukken_alert.js`.
- **Tables:** `tBukkenAlertReadF` (upsert).

### POST `change_refuge_flg.php` — toggle resident "cannot be home" flag
- **Purpose:** set `tUserM.RefugeFlg` for a unit (resident is away / unavailable).
- **Request:** `rKey`, `editBukkenCD`, `editBuildingCD`, `UserID`, `RefugeFlg`.
- **Response:** plain text `success` / `Auth Failed.` / `Getting User Failed.`
- **Authentication:** only runs `if ($rKey)` (then re-authenticates internally).
- **Used by:** resident schedule/grid screens.
- **Tables:** `tUserM`.

### POST `RegistorTaioLog.php` — save a response log on an inquiry
- **Purpose:** attach staff handling notes (`TaioLog`) to a resident inquiry.
- **Request:** `currentcd` (FormCD), `TaioLog`, `UserCD`, `rKey`.
- **Response:** none (silent).
- **Authentication:** ⚠️ **commented out** — effectively unauthenticated.
- **Tables:** `tResidentsFormF`.

### POST `DeleteTaioLog.php` — soft-delete an inquiry
- **Purpose:** mark a resident inquiry invalid (`MukouFlg=1`).
- **Request:** `currentcd` (FormCD), `rKey`.
- **Response:** none.
- **Authentication:** ✅ active (`doAuthenticationByRegistKey`).
- **Tables:** `tResidentsFormF`.

### POST `RegistorMemo.php` — save a property memo
- **Purpose:** set `tBukkenM.Memo` for a property.
- **Request:** `BukkenCD`, `memo`, `rKey`.
- **Response:** none.
- **Authentication:** ⚠️ **commented out**.
- **Tables:** `tBukkenM`.

### POST `IraiToSekoCompany.php` — send a single date-request to the contractor
- **Purpose:** the per-property "send request now" button: sets `KikiStatus=依頼済(1)` and emails the assigned contractor a link to enter dates.
- **Request:** `editBukkenCD`, `TenkenKind` (1=機器/2=総合), `rKey`.
- **Response:** plain text `OK`.
- **Authentication:** via `rKey`.
- **Tables:** `tBukkenM` (update status + `LatestIraiKojiKind`), `tGyosyaM` (lookup email).
- **Notification:** email to `GyosyaMail`, subject `<BukkenName>の点検日程登録依頼`.

---

## File-management endpoints (three different mechanisms)

### POST `delete_file.php` — delete a file by name
- **Request:** `fileName`, `editBukkenCD`.
- **Action:** deletes `./kojifile/<editBukkenCD>/<fileName>` after a path-traversal guard (`preg_match('/\.\.|\/|\^/', …)`).
- **Response:** Japanese plain-text success/error.
- **Authentication:** ⚠️ none, no DB.

### POST `delete_file_fromid.php` — delete by upload record id
- **Request:** `UploadFileID`.
- **Action:** look up `FilePath` in `tUploadFileF`, delete the DB row, `unlink` the file.
- **Authentication:** ⚠️ none.
- **Tables:** `tUploadFileF`.

### POST `s_delete_file_API.php` — delete by FileCD (raw PDO)
- **Request:** `TargetFileCD`.
- **Action:** `SELECT ServerFileName FROM tFileF WHERE FileCD=…`, `DELETE … LIMIT 1`, then `unlink` (note: hardcoded `E:/xampp8.2.4/kotei/…` path — stale source-server path).
- **Authentication:** ⚠️ none. Opens its own PDO connection.
- **Tables:** `tFileF`.

### POST `s_sign_regist.php` — save a signature image
- **Request:** `imgData` (base64 PNG), `wID`, `editBukkenCD`, `editBuildingCD`.
- **Action:** decode → save JPG under `upfile/<year>/<BukkenCD>[-<BuildingCD>]/<wID>_<time>.jpg`; insert `tSignF`; set matching `tReservationF.KanryoFlg=1`.
- **Response:** plain text success/error.
- **Tables:** `tSignF`, `tReservationF`.

### POST `upload_sf_pics.php` — upload a per-property Excel template
- **Request:** `$_FILES` upload, `editBukkenCD`, `TenkenKind` (1=機器/2=総合).
- **Action:** moves the uploaded `.xlsx` to `…/template/{kiki|sougou}/<editBukkenCD>.xlsx`.

---

## Page-level form handlers (POST controllers that redirect)

These are not JSON APIs but are the action endpoints behind the main forms. They validate, write to the DB, and redirect.

| Endpoint | Purpose | Key request fields | Writes |
|---|---|---|---|
| `login_finish.php` | resident login + 2FA | `editBukkenCD`, `wID`, `wPasswd`, `verificationCode` | `tUserM` |
| `login_finish2.php` | staff login + 2FA | `wID`, `wPasswd`, `verificationCode`, `m` | `tUserM` |
| `finish.php` | save customer info | `wLastName`, `wTEL`, `wEMail`, `wPasswd`, `kojin` | `tUserM` |
| `reserve_finish_kakutei.php` | commit reservation | `wDate`, `wTime`, `flag`, `wUserMemo`, `ticket` | `tReservationF`, `tUserM` |
| `kakutei.php` | confirm/decline | `flag` (1/3) | `tUserM`, `tReservationF` |
| `s_kihon_finish.php` | save property | full basic-info set, `work=1` | `tBukkenM`, `tBuildingM`, `tKojiNitteiF` |
| `s_finish_sinki.php` | create property | new-property fields | `tBukkenM` |
| `sh_list.php` | phone booking | `work` 1–5 + booking fields | `tReservationF`, `tUserM`, `tBukkenM` |
| `doc/s_make_kanryo2.php` | grid config | `work` 1/2 + config fields | `tBukkenMatrixM`, `tReservationF` |
| `s_Result_Report_Finish.php` | submit result | checkbox set + `Biko` | `tResultReportF`, `tBukkenM` |
| master `s_*_list.php` | CRUD masters | `work=1` save / `work=2` delete | respective master table |

---

## Download endpoints (stream an Excel/Word file)

| Endpoint | Output | Template |
|---|---|---|
| `s_format_Excel.php` | 案内資料 (resident notice) | `template/temp01–25.xlsx` |
| `s_haifu_bousai_Excel.php` | 防災 notice | `template/bousai/<BukkenCD>.xlsx` |
| `s_kanryo_download.php` | 印取表 (completion + signatures) | `template/complete_report.xlsx` |
| `s_henko_download.php` | 作業工程表 (work schedule) | `template/henko_temp.xlsx` |
| `doc/*.php` (many) | schedules, device sheets, contracts, estimates | various `template/*.xlsx` / `.docx` |

---

## External APIs the system calls (outbound)

| Service | Where | Purpose | Auth |
|---|---|---|---|
| Google Maps Geocoding | `s_finish_sinki.php` (`strAddrToLatLng`) | address → lat/lng on new-property registration | API key hardcoded inline |
| Aiphone "件名/Milkey" (Oracle) | `include/kenmei_connect.php` | pull project/order master data (test mode locally) | Oracle creds in file; `HOSYU_DEBUG=1` → MySQL test mode |
| SMTP / `mb_send_mail` | everywhere | all outbound email | local mail server |

(See **11_external_services.md** for full detail.)
