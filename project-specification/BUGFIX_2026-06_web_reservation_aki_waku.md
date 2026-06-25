# Bug Fix Report — WEB Reservation Availability (空き / 枠越)

**Date:** 2026-06-25
**Reported by:** Client (via NESPE)
**Area:** WEB inspection reservation calendar & time-slot selection
**Affected file:** `httpdocs/reserve_form_kakutei.php`
**Status:** Fixed (code change applied)

---

## 1. The client's report (original Japanese)

> ウェブ申込の際に、また枠外にはいったのでご報告しておきます。点検のWEB申込で気になる点があります。
>
> 写真10/9見てほしいんですが、空き枠１　PMのはず。だけどWEB申込側　△←これは正しい。だけど時間表示が9:00～12:00のみ。確認お願いしたいです。
>
> 物件名：0625テストマンション。ちなみに、進めると枠外にはいります。なお続けると、枠外が満枠になるとWEB側も×になるようです。
>
> 工事と同じ条件がいいので、WEBは枠外を含まない【空き】枠のみで、残数３→△ / 残数0→× としていただけますでしょうか。

### Plain-English summary

For the property **0625テストマンション** on **2026-10-09 (Fri)**:

| What the staff schedule (工程表) shows | What the WEB form showed | Correct? |
|---|---|---|
| AM: 0 free, **PM: 1 free** (`AM:0 PM:1`) | Calendar day marked `△` (a few left) | ✅ correct |
| The 1 genuine free slot is in the **PM** | Time selection offered **only `09:00～12:00` (AM)** | ❌ **wrong** |

Additional symptoms the client observed:

1. **Proceeding pushes the booking "outside the frame" (枠外 / overflow).** The form was guiding residents into overflow slots, not genuine free slots.
2. **When the overflow (枠越) fills up, the WEB shows `×`.** Availability was being driven by overflow capacity, not by genuine free slots.

### What the client wants

Make the WEB behave **the same as construction (工事)**:

- Count **only genuine free slots (空き)** — do **not** include overflow (枠越) in the available count.
- Symbol thresholds:
  - remaining `3` → `△` (few left)
  - remaining `0` → `×` (full)

---

## 2. Where this lives in the system

This is the **"kakutei" (confirmed, single-slot) WEB reservation flow** used by residents:

```
login.php → top.php → reserve_form_kakutei.php (calendar + time picker)
          → reserve_confirm_kakutei.php → reserve_finish_kakutei.php
```

The two pieces the resident sees on `reserve_form_kakutei.php` are:

| Screen element | Driven by function | Purpose |
|---|---|---|
| **1. Calendar** with `○ △ × 休` per day | `getAkiWaku()` | Computes remaining-slot count → caller maps it to a symbol |
| **2. 時間帯選択** dropdown (`09:00～12:00`, `13:00～…`) | `getAkiWakuTime()` | Returns the list of start-times that still have space |

Related documentation:

- `project-specification/03_screen_specification.md` — Reservation calendar & time-select screens
- `project-specification/04_function_specification.md` — Reservation feature
- `project-specification/07_business_rules.md` — Slot-availability & symbol rules
- `project-specification/05_database_specification.md` — `tReservationF`, `tReservationInitF`, `tBukkenM` (`ArrangeType`, `FrameOverflow`, `MaxWakuSu`, `Hansu`, `WakuPattern`)

---

## 3. Background: 工事 vs 点検, and 枠越 (overflow)

`tBukkenM.ArrangeType` decides how a property's slots are calculated:

- `ArrangeType = 0` → **工事 (construction)**
- `ArrangeType = 1` → **点検 (inspection)** ← **0625テストマンション is this type**

```106:106:kawamoto_dia.sql
  `ArrangeType` int DEFAULT '0' COMMENT '点検の場合は1、工事の場合は0',
```

Both `getAkiWaku()` and `getAkiWakuTime()` contain **two code paths**:

- `if ($wArrangeType != '1')` → **construction path**: simple `remaining = (sum of max slots) − (sum of reservations)`. Overflow is never added (frame-overflow is even forced to `0`, see lines 96–98).
- `else` → **inspection path**: a complex per-班 (team) / per-floor grid walk that **adds `$wFrameOverflow` (枠越) cells into the "available" count**.

`$wFrameOverflow` comes from `tBukkenM.FrameOverflow` — the number of extra "overflow" columns drawn beyond the normal team capacity.

```77:98:httpdocs/reserve_form_kakutei.php
$wArrangeType = $myBukken->ArrangeType;
$wFrameOverflow = $myBukken->FrameOverflow;
$wHansu = $myBukken->Hansu;
$wFloorReserveInfo = $myBukken->FloorReserveInfo;
...
// 工事の場合は、ユーザー側で時間指定枠を考慮しません。
if($wArrangeType != '1')
	$wFrameOverflow = 0;
```

---

## 4. Root cause

In the **inspection path** (`else` branch) of both functions, the slot walk counts a slot as "available" **only when it is an empty overflow (枠越) cell**, and it **never counts a genuine empty (空き) cell**.

Tracing the inner loop of `getAkiWaku()` (inspection branch), for each grid cell:

- **Overflow region** (`$ban_rooms > $max_ban && $ban_rooms <= $limit_ban`) and `$Overflows < $wFrameOverflow`: an **empty overflow cell increments `$number01`** (counted as available).
- **Normal region, empty cell**: falls into `elseif (${'wWaku' . $WakuName} > $passed_rooms)` — and **`$passed_rooms` is never defined** (evaluates to `0`), so this branch is always taken and its body is **empty** → the genuine empty slot is **silently discarded**.

```1538:1544:httpdocs/reserve_form_kakutei.php
		}elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
			}else{
				if($Overflows < $wFrameOverflow){
					$number01 ++;
				}else{
				}
			}
```

So `$WakuZanSuu` (= `$number01`) ends up equal to **the number of empty overflow cells**, not the number of genuine free slots. `getAkiWakuTime()` has the identical structure for its per-waku `$ReserveCount[$j]`.

### This exactly reproduces every reported symptom

1. **`△` was "correct" by accident** — the overflow happened to have a couple of empty cells, so `$WakuZanSuu` was small (`≤ 3`) → `△`.
2. **Time list showed only `09:00～12:00` (AM)** — AM's overflow still had an empty cell (`ReserveCount[AM] > 0` → AM listed), while PM's overflow was full (`ReserveCount[PM] = 0` → PM **not** listed) — even though PM had the genuine free slot. Hence "1 free in PM" but "AM time shown".
3. **Proceeding goes 枠外** — the only "available" the form could find were overflow cells, so the booking landed in overflow.
4. **Overflow full → `×`** — once overflow cells are all occupied, `$number01 = 0` → `×`, regardless of genuine slots.

The symbol thresholds in the caller were already what the client asked for (no change needed there):

```929:936:httpdocs/reserve_form_kakutei.php
				if ($WakuZanSuu <= 0) {
					$DayColor[$j] = "#ffffe0";
					$Jokyo[$j] = "×";
					$IfOK1[$j] = false;
				} elseif ($WakuZanSuu <= 3) { // 2枠以下なら
					$DayColor[$j] = "#ffffe0";
					$Jokyo[$j] = "△";
				}
```

`≤ 0 → ×`, `≤ 3 → △`, otherwise `○`. The bug was **only in the count source**, not in the thresholds.

---

## 5. The fix

The client asked for "**工事と同じ条件**" (same as construction) — i.e. count **only genuine empty (空き) slots, per band, excluding overflow (枠越)**. The fix has two parts, both confined to `reserve_form_kakutei.php`:

### Part A — bypass the inspection overflow-grid (both functions)

At the top of **both** `getAkiWaku()` and `getAkiWakuTime()`, the WEB availability calc is forced onto the construction logic. The variables are function-local (passed by value), so the staff grid rendering, the confirm/finish flow, and the real `ArrangeType`/`FrameOverflow` are **not** affected.

```php
// getAkiWaku() / getAkiWakuTime() — top of function body
$wArrangeType = '0'; // 工事と同じ集計ロジックを使用（枠越を含まない実空きのみ）
$wFrameOverflow = 0; // 枠越は空き数に含めない
```

- **`getAkiWakuTime()`** (time-band list) — the construction path already offers a band **only if `booked < max`** for that band, so a full AM is hidden and an open PM is offered. **No further change needed.**

### Part B — per-band clamp on the count (`getAkiWaku()` only)

> ⚠️ **Important correction found while verifying against the real production data.**
> The construction path's original count was the *aggregate* `remaining = Σ(max) − Σ(booked)`. That is correct for true construction (which never overflows), **but wrong for an inspection property that already has overflow bookings**, because one band's over-capacity cancels another band's genuine free slots.
>
> Real example (0625テストマンション, max `15-15`, **2026-10-09**): AM booked **18** (3 in overflow), PM booked **14** (1 genuine free).
> - Aggregate: `30 − 32 = −2` → **× and the day becomes unclickable** ❌ (resident can't book the real PM slot).
> - Per-band clamp: `max(0,15−18) + max(0,15−14) = 0 + 1 = 1` → **△, PM bookable** ✅ (exactly what the client expects).

So the count in `getAkiWaku()` was changed from the aggregate to a **per-band, floor-at-zero sum**, aligned to bands via the `orderTimeFrom` column:

```php
$WakuZanSuu = 0;
for ($i = 0; $i < count($WakuRangeArray); $i++) {
    $band = $i + 1;
    if ($band < $startBand) continue;          // FirstDateFeature exclusion
    $max_i    = (int)$WakuRangeArray[$i];
    $booked_i = isset($bookedByBand[$band]) ? $bookedByBand[$band] : 0;
    $free_i   = $max_i - $booked_i;
    if ($free_i > 0) $WakuZanSuu += $free_i;    // count genuine empties only
}
```

This also fixes the `FirstDateFeature`/`ExcludePattern` handling (the excluded leading bands are skipped explicitly via `$startBand`, instead of the old `array_shift` that mis-aligned the count). For a genuine construction property (no band ever exceeds its max), the per-band clamp equals the old aggregate, so **construction behavior is unchanged**.

---

## 6. Verified against the real server data (kawamoto_dia dump)

The production dump was imported and the fixed logic was reproduced for **0625テストマンション** (BukkenCD 10, `ArrangeType=1`, WakuPattern 0 = AM 09:00–12:00 / PM 13:00–17:00, `MaxWakuSu=15-15`, `FrameOverflow=3`):

| Date | AM booked | PM booked | Remaining (genuine, clamped) | Symbol | Times offered |
|---|---|---|---|---|---|
| 10/05 | 1 | 4 | 25 | ○ | AM + PM |
| 10/06 | 8 | 10 | 12 | ○ | AM + PM |
| 10/07 | 10 | 8 | 12 | ○ | AM + PM |
| 10/08 | 13 | 2 | 15 | ○ | AM + PM |
| **10/09** | **18** (overflow) | **14** | **1** | **△** | **PM only** |

10/09 now matches the client's report exactly: `△` and **PM only** (no more `09:00～12:00`).

Threshold behavior (unchanged, already matches the request `残数3→△ / 残数0→×`):

- remaining `≥ 4` → `○`
- remaining `1–3` → `△`
- remaining `0` (or negative) → `×`

> Note: BukkenCD 11 = "0625**工事**テストマンション" is `ArrangeType=0` (construction) — the comparison property the client referenced ("工事と同じ条件").

---

## 7. How to test

1. Log in as a resident for **0625テストマンション** and open the inspection booking calendar.
2. Navigate to **2026-10**. Confirm **10/9** is `△`.
3. Click **10/9** → the **時間帯選択** dropdown must now offer the **PM** slot (e.g. `13:00～…`), not `09:00～12:00`.
4. Book genuine slots until the PM genuine capacity is exhausted → the day must flip to `×` and offer **no** time slots (it must **not** allow an overflow/枠外 booking).
5. Cross-check against the staff schedule (工程表) header `AM:n PM:m`: the WEB-offered times must correspond only to the AM/PM that still has `n`/`m > 0` genuine free.
6. Regression: verify a genuine **工事 (construction)** property still behaves exactly as before (its path is unchanged in effect).

> Tip: `php -l httpdocs/reserve_form_kakutei.php` confirms there are no syntax errors (verified after this change).

---

## 8. Follow-ups / things to confirm with the client

1. **Downstream booking validation.** This fix corrects what the **form** offers. The pages that actually write the reservation (`reserve_confirm_kakutei.php` / `reserve_finish_kakutei.php`) should be reviewed to ensure they re-validate against genuine capacity and never silently accept an overflow booking that the form no longer offers. (Out of scope of this change; recommended next.)
2. **`FirstDateFeature` (first-day) properties.** `getAkiWaku()` now skips the excluded leading band(s) via `$startBand` (count side fixed), but the construction path of `getAkiWakuTime()` still does **not** apply `ExcludePattern`, so on the special first day the *time dropdown* could still offer the excluded first band. 0625テストマンション has `FirstDateFeature = 0`, so this has no effect here; flag for a separate fix if a first-day-feature property is used on the WEB.
3. **Other reservation entry points.** The legacy 3-preference flow (`reserve_form.php`) has a different/older `getAkiWakuTime()` and is not used by this kakutei flow. Confirm with the client whether any inspection property still uses that legacy flow on the WEB.
4. **Scope.** This change makes **all** inspection properties' WEB booking use the genuine-空き (construction) logic. The client's wording ("工事と同じ条件がいい") indicates this is the intended global behavior; please confirm there is no inspection property that intentionally relies on offering overflow slots on the WEB.
