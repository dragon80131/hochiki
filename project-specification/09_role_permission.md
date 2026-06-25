# 09. Role & Permission Specification

> Every role, what it can do, which screens it can reach, and what is restricted.

---

## The two authentication systems

This project actually contains **two separate login systems**:

1. **`tUserM` + RegistKey (the live Hochiki system)** — used by every resident, staff, and contractor screen. This is what matters day-to-day.
2. **`tAdministratorM` (legacy SPFW framework admin)** — driven by `SPFW/inc/authorize.inc` + `SPUSAdministrator.cls`, governing the old generic CMS/mailmag back-office tooling. The Hochiki application screens do **not** use it. Treat it as legacy.

Everything below concerns system #1.

---

## Primary roles — `tUserM.UserKbn`

| `UserKbn` | Role | Who | Logs in via |
|---|---|---|---|
| **1** | 幹事企業一般 — Managing-company **general staff** | NESPE office workers | `login_form.php` |
| **2** | 管理者 — Managing-company **administrator** | senior NESPE staff | `login_form.php` |
| **3** | 協力業者 — **Contractor** | outside inspection vendor staff | `login_form.php` |
| **4** | 入居者 — **Resident / occupant** | apartment residents | `login.php` |

### Secondary sub-role — `tUserM.UserType`

- `'1'` = 管理者 (admin within the role) — unlocks **master-data maintenance** (`IfMasterMaintenance = staff AND UserType='1'`).
- `'0'` (or other) = 一般 (general).
- For general staff (`UserKbn=1`, `UserType≠'1'`) the bell scope is further narrowed to their `BrancheCD`.

### Special markers

- **Login ID contains `nespe`** → internal/developer account: skips 2FA, gets `IfNespe`, often sees all-client data.
- **`Skip2faFlg=1`** → bypasses the email 2-factor step.

### Legacy role taxonomy (`common_seko.php::getUserAuth`, via `Extra3`/`Extra4`)

An older parallel scheme still present in some code: `aipadmin` (Aiphone/NESPE admin), `aipuser` (Aiphone general), `ateuser` (ATE contractor general), `ateadmin` (ATE contractor admin). Documented for completeness; the `UserKbn` scheme above is authoritative for the current app.

---

## What each role can access

### Resident (`UserKbn = 4`)

- **Can:** log in to their property/unit; register/verify email; view & edit their customer info; **book / change / decline** an inspection slot; sign on completion; set their "absent" flag; view equipment info & FAQ.
- **Accessible screens:** `login.php`, `mail_regist.php`, `top.php`, `form.php`/`confirm.php`/`finish.php`, `reserve_form_kakutei.php`/`reserve_confirm_kakutei.php`/`reserve_finish_kakutei.php`, `reserve_list.php`, `kakutei.php`, `personalinfo.php`, `s_sign.php`.
- **Restricted:** everything staff-side. Residents are excluded from staff lists (`AND UserKbn < 4`) and from the bell.
- **Data scope:** only their own unit's data (matched by property/room).

### Managing-company general staff (`UserKbn = 1`)

- **Can:** search/list their client's properties; set up & edit properties; configure the slot grid; generate notices/Excel; take phone bookings; view work schedules and completion; submit result reports; trigger contractor requests.
- **Accessible screens:** `s_search.php`, `s_menu.php`, `s_kihon_form.php`/`_finish.php`, `doc/s_make_kanryo2.php`, `s_format.php`, `sh_list.php`, `sh_henko_list.php`, `s_kanryo.php`, `s_Result_Report.php`, upload screens, `d2.php`, `ns_monthly.php`, etc.
- **Restricted:** master-data maintenance create/edit unless `UserType='1'`. Data limited to own `ClientCD` (and `BrancheCD` for the bell).

### Managing-company administrator (`UserKbn = 2`)

- **Can:** everything general staff can, **plus** full master-data maintenance (`UserType='1'`): contractors, contractor staff, sales staff, branches, management companies. Some 担当 lists are unrestricted only for `UserKbn=2`.
- **Accessible screens:** all of the above plus `s_gyosya_*`, `s_gyosyatanto_*`, `s_tanto_*`, `s_branche_*`, `s_kanricompany_*`.
- **Data scope:** own `ClientCD`.

### Contractor (`UserKbn = 3`)

- **Can:** log in; see **only** the properties assigned to their company (`GyosyaCD` or `GyosyaBousaiCD`); enter inspection working dates; view their schedules and completion status; manage their own staff (created with `ClientCD=0`, their own `GyosyaCD`).
- **Accessible screens:** `s_search.php` (filtered), `s_menu.php`, scheduling/grid screens for their properties, `s_tanto_list.php` (own staff).
- **Restricted:** cannot see other contractors' or other clients' data; limited master maintenance.
- **Data scope:** own `GyosyaCD`.

---

## Permission / scope rules summary

| Rule | Where enforced |
|---|---|
| Staff see only own `ClientCD` | list SQL `WHERE ClientCD = …` in every master/`s_search` |
| General staff narrowed to `BrancheCD` (bell) | `getBukkenAlertScopeSql()` |
| Contractors see only own `GyosyaCD`/`GyosyaBousaiCD` | `s_search.php`, `getBukkenAlertScopeSql()` |
| Master maintenance = staff + `UserType='1'` | `$IfMasterMaintenance` block (every master screen) |
| Residents excluded from staff data & bell | `AND UserKbn < 4`; `bukken_alert.php` early-return |
| `nespe` IDs skip 2FA & may see all | `login_finish2.php`, master screens |
| IP gate: VPN passes, 3-strikes block external | `include/common_accesscheck.php` (`tOKIPM`) |

---

## Role → screen access matrix

| Screen group | Resident (4) | General staff (1) | Admin (2) | Contractor (3) |
|---|:--:|:--:|:--:|:--:|
| Resident login & booking (`login.php`, `top.php`, `reserve_*`) | ✅ | — | — | — |
| Staff login & search (`login_form.php`, `s_search.php`) | — | ✅ (own client) | ✅ | ✅ (own gyosya) |
| Property setup (`s_kihon_*`, `doc/s_make_kanryo2`) | — | ✅ | ✅ | ✅ (own) |
| Notices / grid (`s_format*`, `sh_list`, `sh_henko_list`) | — | ✅ | ✅ | ✅ (own) |
| Completion & result (`s_kanryo*`, `s_Result_Report*`) | — | ✅ | ✅ | ✅ (own) |
| Master maintenance (`s_gyosya_*`, `s_tanto_*`, …) | — | view only | ✅ full | own staff only |
| Signature capture (`s_sign.php`) | ✅ | ✅ | ✅ | ✅ |
| Bell APIs (`get_bukken_alerts`) | — | ✅ (client+branch) | ✅ (client) | ✅ (gyosya) |

> ✅ = allowed; — = not applicable/blocked. "Own" = scoped to the user's client/contractor.
