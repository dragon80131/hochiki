# 03. Screen Specification

> Every significant screen, described in plain language: purpose, buttons, inputs, search conditions, table columns, popups, validation, error messages, and navigation.
>
> **Where templates live:** the PHP controller (e.g. `login.php`) renders a template by bare name (e.g. `login.tpl`). The actual template files are in **`SPFW/contents_pc/`** (desktop) and a few in **`SPFW/contents_smartphone/`**. The folder `httpdocs/template/` holds **Excel** templates, not screens.
>
> **Encoding note:** Japanese strings below are reconstructed from code; the source files are EUC-JP/Shift-JIS.

---

# PART 1 — Resident-facing screens

## 1.1 Resident Login — `login.php` (`login.tpl`)

- **Purpose:** Front door for apartment residents. Title: 消防設備点検予約システム-ログイン.
- **Reached from:** A QR code / link on the paper notice (carries `editBukkenCD` and optionally `editBuildingCD`); also any failed-auth redirect.
- **Inputs:**
  - `editBukkenCD` — 物件管理番号 (property number, half-width digits)
  - `wID` — 部屋番号 (room number)
  - `wPasswd` — パスワード (password)
  - hidden `editBuildingCD`
- **Buttons:** `ログイン` (submits to `login_finish.php`).
- **Validation / errors:** Required-field messages from `login_finish.php`: `物件管理番号は必須項目です。`, `ユーザー名は必須項目です。`, `パスワードは必須項目です。`, and `該当するユーザが見つかりませんでした。` (no matching user — shown for both unknown user and wrong password, deliberately).
- **Navigation:** success → 2-factor screen → `top.php`.

## 1.2 Two-factor code entry — `login_2fa.tpl`

- **Purpose:** Enter the 6-digit code emailed during login (shared by residents and staff).
- **Inputs:** `verificationCode` (`type=tel`, 6 digits, `autocomplete=one-time-code`); hidden `wID`, `wPasswd`, `editBukkenCD`, `editBuildingCD`.
- **Buttons:** `次へ` (Next — JS checks exactly 6 digits), `再送信` (Resend — regenerates and re-emails the code).
- **Validation:** JS message `確認コードは6桁の数字で入力してください。`
- **Errors:** `確認コードが正しくありません。` (wrong), `確認コードの有効期限が切れています。再度ログインをお試しください。` (expired, 30-min limit).

## 1.3 Email registration / verification — `mail_regist.php` (`mail_regist.tpl`, `mail_confirm.tpl`)

- **Purpose:** First-time residents register and verify an email address before they can proceed.
- **Reached from:** `form.php` / `top.php` when `EMail` is empty or `EmailVerified` is false.
- **Inputs:** `wEMail` (email); then `verificationCode`.
- **Buttons:** `次へ` (Next); back link to `top.php`; resend link.
- **Validation:** JS `メールアドレスの形式が不正です。` (bad email format); server-side wrong/expired code messages.
- **Navigation:** on success sets `EmailVerified=true` and goes to `form.php`.

## 1.4 Resident Top / Property Home — `top.php` (`top_kakutei.tpl`)

- **Purpose:** The resident's home screen for their property/unit.
- **Shows:** mansion name, building/room, the work period dates (共用部期間 / 専有部期間 / 予約受付期限), and their current reservation (or "no reservation", or "辞退/declined").
- **Buttons (depend on state):**
  - One schedule button — label & target vary:
    - has reservation + confirmed → `日程変更` → `reserve_list.php`
    - has reservation + not confirmed → `日程予約` → `reserve_list.php`
    - declined → `日程再予約` → `reserve_form_kakutei.php`
    - no reservation → `日程予約` → `reserve_form_kakutei.php`
  - `機器` → `device.php` (equipment info); `FAQ` → `faq.php`
  - `※お客様情報を修正される場合はこちら` → `form.php?...&CustomerEdit=1`
  - 完了確認書 block (when applicable); `ログアウト` → `logout.php`
- **Closed-window message (popup-style banner):** `インターネットからの受付は終了しました。…` when the reception window is closed.
- **Navigation guard:** missing `rKey`/`editBukkenCD` → redirect to `login.php`.

## 1.5 Customer Info Entry — `form.php` (`form.tpl`)

- **Purpose:** Capture/update the resident's name, phone, email and a chosen password; require privacy consent.
- **Inputs:** `wLastName` (name, required), `wTEL` (phone, required), `wEMail` (read-only, set via verify flow), `wPasswd` (new password, digits, must differ from initial), `kojin` (consent checkbox), optional hearing questions.
- **Buttons:** `次へ` (Next → `confirm.php`).
- **Validation (client-side):**
  - `氏名を入力してください。`
  - `電話番号を入力してください。`
  - `メールアドレスを入力してください。`
  - `ご希望のパスワードを入力してください。`
  - `初期パスワードと異なる、ご希望のパスワードを入力してください。`
  - `個人情報の取り扱いについてはチェックされていません。`
- **Popup link:** `個人情報の取り扱いについてはコチラ` opens `personalinfo.php` in a new tab.

## 1.6 Customer Info Confirm — `confirm.php` (`confirm.tpl`)

- **Purpose:** Read-only review of what was entered in `form.php`, after server-side validation.
- **Shows:** 氏名 / 電話番号 / メールアドレス / 変更後パスワード / 個人情報の取り扱い.
- **Buttons:** `登録する` (Register → `finish.php`), `修正する` (Edit → back to `form.php`).

## 1.7 Customer Info Finish — `finish.php` (`finish.tpl`)

- **Purpose:** Save the customer info to `tUserM` and route onward.
- **Buttons:** `次へ` → `reserve_form_kakutei.php` (if no reservation) or `reserve_list.php` (if one exists).

## 1.8 Reservation — Pick slot — `reserve_form_kakutei.php` (`reserve_form_kakutei.tpl`)

- **Purpose:** The live booking screen: choose **one** date on a calendar and **one** time slot.
- **Calendar legend (popup-style key):** `○…空きあり △…残りわずか ×…空きなし 休…休工日`.
- **Inputs:** `wDate` (set by clicking an available day), `wTime` (dropdown of open slots), optional `wUserMemo`, month navigation (`前の月` / `次の月`).
- **Buttons:** `次へ` (Next → `reserve_confirm_kakutei.php`); `戻る` (Back → `reserve_list.php` or `top.php`).
- **Validation:** JS alert `日程を選択してください。` if no slot is chosen.
- **Guard:** missing name/phone → redirect to `form.php`.

## 1.9 Reservation — Confirm — `reserve_confirm_kakutei.php` (`reserve_confirm_kakutei.tpl`)

- **Purpose:** Review the chosen 予約日 / 時間帯 before committing. Protected against double-submit by a session `ticket`.
- **Buttons:** `予約を確定する` (Confirm new, `flag=1`) or `予約を変更する` (Change, `flag=2`) → `reserve_finish_kakutei.php`; `修正する` (Edit → back to form).

## 1.10 Reservation — Finish — `reserve_finish_kakutei.php` (`reserve_finish_kakutei.tpl`)

- **Purpose:** Commit the reservation, update flags, send the confirmation email.
- **`flag` meanings:** `1`=confirm new, `2`=change, `3`=decline.
- **Result messages:** `日程予約の完了` / `予約変更の完了` / `予約辞退の完了`; declines show `辞退で受付いたしました。次回のご協力よろしくお願いします。`
- **Error:** if no open slot can be resolved, an error + link back to `top.php`.
- **Buttons:** `予約システムTOPへ` → `top.php`; `ログアウト`.

## 1.11 Reservation List / Confirm-Decline — `reserve_list.php` (`reserve_list_kakutei.tpl`)

- **Purpose:** Show the resident's current (tentative) reservation and offer confirm/change/decline.
- **Buttons:** `確定する` → `kakutei.php?flag=1`; `変更する` → `reserve_form_kakutei.php`; `辞退する` → `kakutei.php?flag=3`; `戻る` → `top.php`.

## 1.12 Confirm/Decline handler — `kakutei.php` (`kakutei.tpl`)

- **Purpose:** Apply a confirm (flag 1) or decline (flag 3); update `tUserM.ReplyFlg`/`ConfirmFlg`; send email.
- **Shows:** confirmed date/time and `予約システムTOPへ` button; or, for not-yet-registered users, a prompt to complete their info first.

## 1.13 Privacy Policy — `personalinfo.php` (`personalinfo.tpl`)

- **Purpose:** Static 個人情報保護方針 (privacy policy) page opened from the consent link. Contact: 予約受付センター 0120-489-501. No database use.

## 1.14 Signature capture — `s_sign.php` (`s_sign.tpl`) + `s_sign_regist.php`

- **Purpose:** Capture a resident's signature (drawn on screen) confirming the inspection. `s_sign_regist.php` receives a base64 PNG, saves it as a JPG under `upfile/…`, writes a `tSignF` row, and sets the matching `tReservationF.KanryoFlg=1` (complete).
- **Result message:** `サインが … に保存されました` / `エラー: サインデータが受信されませんでした`.

---

# PART 2 — Staff / Contractor screens

## 2.1 Staff Login — `login_form.php` (`login_form.tpl`)

- **Purpose:** Front door for NESPE staff and contractors. Header image `images/489kanri.png`.
- **Inputs:** `wID` (ユーザー名), `wPasswd` (password).
- **Buttons:** `ログイン` → `login_finish2.php`.
- **Special rule:** IDs containing `nespe`, or users with `Skip2faFlg`, skip the email 2-factor step and go straight to `s_search.php`.
- **Errors:** `ユーザー名は必須項目です。`, `パスワードは必須項目です。`, `該当するユーザは見つかりませんでした。`.

## 2.2 Property Search & List — `s_search.php` (`s_search.tpl`)

- **Purpose:** Staff landing page. Search and list properties; entry to each property's menu.
- **Search conditions (inputs):** `wBukkenCD`, `wKenmeiNo`, `wBukkenName`, `wBukkenNameKana`, `wAddress`, `wKanriGaisya` (management company), `wGyosyaCompany` (contractor), `wBrancheCompany` (branch), `wStartKosu`/`wEndKosu` (unit-count range), `wStartMonth`/`wEndMonth`, `wTenkenMonth` (inspection month), `wSenyuStartDate`/`wSenyuEndDate`/`wSenyuMonth`, `BukkenStatus`, `Tenken_Category` (1=消防 / 2=防火), `wBukkenMemo`, `deleteFlg`.
- **Buttons:** `検索` (Search, sets `KensakuDisp=1`); `削除` (Delete checked rows → soft delete `tBukkenM.MukouFlg='1'`).
- **Table columns:** Property CD, Property name, Contractor (GyosyaName), Next inspection month, Construction date range, Status badges (per `KikiStatus`), Report-submitted flag.
- **Role scoping:** `UserKbn=1` → only own `ClientCD`; `UserKbn=3` → only own `GyosyaCD`/`GyosyaBousaiCD`.
- **Other:** generates a QR to the mobile login; 20 rows per page.
- **Navigation:** row → `s_menu.php?editBukkenCD=…`; new property → `s_form_sinki.php`.

## 2.3 Property Hub — `s_menu.php` (`s_menu.tpl`)

- **Purpose:** Per-property control panel. Header shows property name, total units/blocks, work name, address, client, the 5 assigned staff (`TantoCD1–5`), and a memo.
- **Live menu links:**
  - **作業準備:** 物件基本情報 → `s_kihon_form.php`; 作業日程登録 → `doc/s_make_kanryo2.php`
  - **入居者様対応:** 案内資料生成 → `s_format.php?menu=1`; 日程変更（TEL受付）→ `sh_list.php?menu=1`
  - **状況確認:** 作業工程表 → `sh_henko_list.php`; 完了報告 → `s_kanryo.php`
- **Indicators:** a red dot (`__IfTempSave__`) if a temporary save exists; status text from `KikiStatus` (日程入力依頼済 / 日程登録済); a 防火 block appears when `BousaiFlg` is on.
- **Note:** menu blocks for `s_haihu_list.php`, `s_Taio_List.php`, `s_date_bousai.php` are HTML-commented (legacy).

## 2.4 Basic Info Form — `s_kihon_form.php` (`s_kihon_form.tpl`) + `s_kihon_finish.php`

- **Purpose:** Edit/register the property's master data, blocks (棟), holidays, and assigned staff.
- **Inputs (selection):** `wBukkenName`(+Kana), `wSagyoName`, `wAddress`, `wBukkenMemo`, `wKosu` (units), `wKaidaka` (floors), `GyosyaCompany`, `TantoCD1–5`, `KanriCompany`, `BrancheCompany`, `MinuteTime`, occupancy/term dates (Senyu/Kyoyobu start/end), `YoyakuEndDate`, `wHoliday[]` (no-work days), `wReserveDay[]` (reserve days), building arrays (`newBuilding[]`, `newKosu[]`, `newKaidaka[]`, …), per-floor settings (`kobetsu[]`).
- **Buttons:** save (`work=1`) → `s_kihon_finish.php` → back to `s_menu.php`.
- **Writes:** `tBukkenM`, `tBuildingM` (create/update/delete blocks), `tKojiNitteiF` (inspection schedule rows for 機器 and 総合).

## 2.5 Copy Property — `s_kihon_copy.php` (`s_kihon_copy.tpl`)

- **Purpose:** Copy an existing property as a starting template for a new one.
- **Search:** `wBukkenName` (filtered to properties updated in the last 24 months).
- **Columns:** Property CD, Name, Contractor, Senyu start/end, Koji date.

## 2.6 Work-schedule configuration — `doc/s_make_kanryo2.php` (`s_make_kanryo2.tpl`)

- **Purpose:** Configure the parameters from which the booking grid is built: teams (`Hansu`/班), slot pattern (`WakuPattern`), max slots (`MaxWakuSu`), no-work days (`Holiday1`), reserve days (`ReserveDay`), first-day handling (`FirstDateFeature`), inspection-vs-construction mode (`ArrangeType`), and the **room matrix** (floor × room layout, stored in `tBukkenMatrixM.KaiRoom`).
- **Buttons:** save room matrix (`work=1`); **delete schedule** (`work=2` — wipes `tReservationF` for that property/block).
- **On-screen guidance:** computes recommended min/max slot counts over the work period and warns if the configuration cannot fit all units (`$OverErrorStrings`).
- **Editable sub-tables:** 休工日 (no-work days), 予備日 (reserve days), per-floor reservation table.

## 2.7 Notice generation / grid preview — `s_format.php` (`s_format.tpl`)

- **Purpose:** Render the detailed schedule grid (dates × teams × time slots), filled with room numbers / 空き(empty) / 枠越(overflow) / 休工日 / 予備日. Lists uploaded files; shows the initial resident password and a QR code.
- **Guard:** if no slot pattern or no reservations → JS confirm redirect to `doc/s_make_kanryo2.php` or back to `s_menu.php`.
- **Reads:** `tReservationInitF` (the generated initial grid).
- **Related downloads:** `s_format_Excel.php` (案内資料 Excel, 機器/総合, picks one of 25 templates `temp01–25.xlsx`), `s_haifu_bousai_Excel.php` (fire-prevention notice from a per-property template).

## 2.8 Telephone reception / booking editor — `sh_list.php` (`sh_list.tpl`)

- **Purpose:** The interactive grid where staff register/confirm/change/decline/restore a resident's reservation taken over the phone.
- **`work` actions:** `1`=register info, `2`=confirm, `3`=change, `4`=decline, `5`=restore.
- **Inputs:** `HenkoDate`, `TimeFromTime`, `HanNo`, `ViewOrderNo`, `BlankSlotType`, `RoomNo`, `Name`, `TEL`, `EMail`, `Memo`, `TimeExactHour`/`Minutes`/`Meaning`, `Biko`.
- **Writes:** `tReservationF` (time, memo, team, order, status), `tUserM` (ReplyFlg/ConfirmFlg/name/TEL/email), `tBukkenM` (Biko).

## 2.9 Work schedule / change list — `sh_henko_list.php` (`sh_henko_list.tpl`)

- **Purpose:** A table of all reservations and their status for a property.
- **Search conditions:** `wKaidaka` (room/floor prefix), `srchDate` (month/day), `OrderBy`.
- **Table columns:** Room (ID), Date, Time slot (AM/PM/PM1/PM2), Memo, Name, TEL, Updater, Updated, Reply flag, Signature-exists, Exact time, Time meaning, Confirm flag, Status.
- **Status rule:** `ReplyFlg=3`→辞退 (declined); `ReplyFlg 1/2`+`ConfirmFlg=1`→確定 (confirmed); else 仮日程 (tentative).
- **Download:** `s_henko_download.php` (Excel, template `henko_temp.xlsx`).

## 2.10 Completion report — `s_kanryo.php` (`s_kanryo.tpl`)

- **Purpose:** List of units with completion/signature status; view/delete a signature or start signing.
- **Buttons:** `削除` (delete signature, `work=3`); `未` (unsigned → `s_sign.php`); 完了書 image link when signed.
- **Download:** `s_kanryo_download.php` (印取表 / completion-signature Excel, template `complete_report.xlsx`; embeds the signature images; sets `KanryoFlg=1`).

## 2.11 Inspection Result Report — `s_Result_Report.php` (`s_Result_Report.tpl`) + `s_Result_Report_Finish.php`

- **Purpose:** A checklist form for the inspection result: items like `ZyushinDengen` (受信機電源), `RendoubanConfirm` (連動盤), `PumpConfirm` (ポンプ), `NozuruConfirm` (ノズル), `HontaiDengenConfirm`, `DoukanSetsuzoku`, plus a memo (`Biko`), `ResultReport`, `Sagyousya` (worker).
- **Writes:** `tResultReportF`, and sets `tBukkenM.ReportSubmitted`; emails staff that the report is complete.

## 2.12 New property registration — `s_form_sinki.php` (`s_form_sinki.tpl`) + `s_finish_sinki.php`

- **Purpose:** Create a brand-new `tBukkenM` record. Geocodes the address via Google Maps (`strAddrToLatLng`) to store latitude/longitude.
- **Inputs:** `wBukkenName`(+furigana), `wKojiName`, `wKosu`, `wKaidaka`, `wKanriGaisya`(+TEL/contact), `wOwner_name`, `wBukkenMemo`, `wAddress`, contractor staff CDs, etc.

## 2.13 Master-data screens (admin)

Each is a **list + detail** pair. Lists support search, show a table, and have `登録/更新` (save, `work=1`) and `削除` (soft delete, `work=2`) actions. Only staff-admins (`IfMasterMaintenance` = staff with `UserType='1'`) get full maintenance rights; contractors are limited to their own data.

| Screen | Files | Manages | Key fields | Validation messages |
|---|---|---|---|---|
| Contractor companies (業者) | `s_gyosya_list.php` / `_detail.php` (`tGyosyaM`) | Inspection vendor companies | name, kana, TEL, password, notes, IsSyoubou/IsBouka | `施工業者名は必須項目です。` / `業者名ふりがなは必須項目です。` / `電話番号は必須項目です。` |
| Contractor staff (業者担当) | `s_gyosyatanto_list.php` / `_detail.php` (rows in `tUserM`, UserKbn=3) | People at a contractor | ID, password, name, kana, TEL, mail, company, Skip2fa | name/kana/company required; ID alnum ≥6; password ≥8; duplicate-ID `ご希望のIDは既に他の方に登録されているようです。` |
| Sales / staff (担当) | `s_tanto_list.php` / `_detail.php` (`tUserM`, UserKbn 1/2) | NESPE staff | name, kana, ID, password, role (UserType), mail, TEL, branch, Skip2fa | required name/role/mail; password ≥4; general users must have a branch: `一般ユーザーの場合は、支店・支社は必須項目です。` |
| Branch offices (支店) | `s_branche_list.php` / `_detail.php` (`tBrancheM`) | Branch offices | name, kana, TEL | `支店・支社名は必須項目です。` |
| Management companies (管理会社) | `s_kanricompany_list.php` / `_detail.php` (`tKanriCompanyM`) | Building management firms | name, kana, TEL, notes | `管理会社名は必須項目です。` / `管理会社名ふりがなは必須項目です。` / `電話番号は必須項目です。` |

## 2.14 File upload screens

- `s_upload_file.php` (`s_upload_file.tpl`) — per-property file management: drawings (`FileKind=1`) and notices (`FileKind=3`) in `tFileF`.
- `s_upload_file_for_report.php` — adds inspection report files (`FileKind=2`) and the result-report checklist.
- `upload_sf_pics.php` — receives an uploaded Excel and stores it as the per-property notice template (`…/template/{kiki|sougou}/{BukkenCD}.xlsx`).

## 2.15 Schedule dashboards

- `d2.php` (`d2.tpl`) — per-property work schedule grid for the crew, with completion checkboxes, memos, emergency-contact toggle, and several popups (残工事住戸, 未返事住戸, 受付締切後の日程調整履歴). AJAX to `get_rireki.php`, `a2.php`.
- `ns_monthly.php` (`ns_monthly.tpl`) — a month calendar of all properties/inspection dates, colored by contractor.
- `s_d2.php` / `s_d2_search.php` — a completion/signature dashboard variant.

---

# PART 3 — Legacy / secondary screens

These exist in the codebase and still function but are **not linked from the current live menu**:

| Screen | Purpose |
|---|---|
| `s_Taio_Form.php` / `_Form2.php` / `_Finish.php` | Older resident inquiry/date-request form (QR-linked); writes `tResidentsFormF` |
| `s_Taio_List.php` / `_List2.php` / `_List_before.php` | Staff view of resident inquiries |
| `s_Taio_Exceed_UketsukeDate.php` | "Reception deadline passed" page |
| `s_date*.php`, `s_date_bousai*.php`, `s_regist_date.php` | Older date-entry screens (set `KikiStatus`/`BousaiStatus=2`) |
| `s_Set_Dates.php` / `_Finish.php` | Availability master (`tSettingDatesM`) |
| `s_haihu_list.php`, `s_haifu_Excel.php` | Older distribution list / Excel |
| `reserve_form.php` / `reserve_confirm.php` / `reserve_finish.php` | The older 3-preference reservation flow (replaced by the "kakutei" single-slot flow) |

> See **14_unknown_logic.md** for files whose live-vs-legacy status is uncertain, and **15_improvement_suggestions.md** for cleanup recommendations.
