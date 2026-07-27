<?php
/**
 * Holiday1 half-day (ALL / AM / PM) helpers.
 *
 * Storage (pipe-separated via encodePluralValue):
 *   |2026-10-03|2026-10-05:AM|2026-10-06:PM|
 * Bare YYYY-MM-DD means ALL (backward compatible).
 */

/**
 * @param string|null $holiday1
 * @return array<int, array{date:string, period:string, token:string}>
 */
function parseHoliday1($holiday1)
{
	$items = array();
	if ($holiday1 === null || $holiday1 === '') {
		return $items;
	}
	$tokens = SPFWTools::decodePluralValue($holiday1);
	foreach ($tokens as $token) {
		$token = trim($token);
		if ($token === '') {
			continue;
		}
		$parsed = parseHolidayToken($token);
		if ($parsed['date'] !== '') {
			$items[] = $parsed;
		}
	}
	return $items;
}

/**
 * @param string $token
 * @return array{date:string, period:string, token:string}
 */
function parseHolidayToken($token)
{
	$token = trim((string)$token);
	$period = 'ALL';
	$date = $token;
	if (strpos($token, ':') !== false) {
		$parts = explode(':', $token, 2);
		$date = trim($parts[0]);
		$rawPeriod = strtoupper(trim($parts[1]));
		if ($rawPeriod === 'AM' || $rawPeriod === 'PM' || $rawPeriod === 'ALL') {
			$period = $rawPeriod;
		}
	}
	$normalizedDate = normalizeHolidayDate($date);
	return array(
		'date' => $normalizedDate,
		'period' => $period,
		'token' => formatHolidayToken($normalizedDate, $period),
	);
}

/**
 * @param string $date
 * @return string Y-m-d or original if unparseable
 */
function normalizeHolidayDate($date)
{
	$date = trim((string)$date);
	if ($date === '') {
		return '';
	}
	$ts = strtotime($date);
	if ($ts === false) {
		return $date;
	}
	return date('Y-m-d', $ts);
}

/**
 * @param string $date
 * @param string $period ALL|AM|PM
 * @return string
 */
function formatHolidayToken($date, $period = 'ALL')
{
	$date = normalizeHolidayDate($date);
	$period = strtoupper(trim((string)$period));
	if ($period !== 'AM' && $period !== 'PM') {
		$period = 'ALL';
	}
	if ($date === '') {
		return '';
	}
	if ($period === 'ALL') {
		return $date;
	}
	return $date . ':' . $period;
}

/**
 * Combine parallel date + period form arrays into Holiday1 tokens.
 *
 * @param array|null $dates
 * @param array|null $periods
 * @return array<int, string>
 */
function combineHolidayInputs($dates, $periods = null)
{
	$out = array();
	if (!is_array($dates)) {
		return $out;
	}
	if (!is_array($periods)) {
		$periods = array();
	}
	$count = count($dates);
	for ($i = 0; $i < $count; $i++) {
		$date = isset($dates[$i]) ? trim((string)$dates[$i]) : '';
		if ($date === '') {
			continue;
		}
		// Already a stored token (from hidden fields after confirm)
		if (strpos($date, ':') !== false) {
			$token = parseHolidayToken($date)['token'];
			if ($token !== '') {
				$out[] = $token;
			}
			continue;
		}
		$period = isset($periods[$i]) ? $periods[$i] : 'ALL';
		$token = formatHolidayToken($date, $period);
		if ($token !== '') {
			$out[] = $token;
		}
	}
	return $out;
}

/**
 * Encode token list to Holiday1 string.
 *
 * @param array $tokens
 * @return string
 */
function encodeHoliday1($tokens)
{
	$clean = array();
	foreach ((array)$tokens as $token) {
		$parsed = parseHolidayToken($token);
		if ($parsed['date'] !== '') {
			$clean[] = $parsed['token'];
		}
	}
	return SPFWTools::encodePluralValue($clean);
}

/**
 * @param string|array $holiday1OrItems
 * @param string $date
 * @return bool
 */
function isFullDayHoliday($holiday1OrItems, $date)
{
	$date = normalizeHolidayDate($date);
	foreach (holidayItems($holiday1OrItems) as $item) {
		if ($item['date'] === $date && $item['period'] === 'ALL') {
			return true;
		}
	}
	return false;
}

/**
 * Whether a schedule slot (AM / PM / PM1 / PM2) is closed by holiday.
 *
 * :PM closes PM, PM1, and PM2. :AM closes slots whose name starts with AM.
 *
 * @param string|array $holiday1OrItems
 * @param string $date
 * @param string $slotName
 * @return bool
 */
function isSlotHoliday($holiday1OrItems, $date, $slotName)
{
	$date = normalizeHolidayDate($date);
	$slotName = strtoupper(trim((string)$slotName));
	foreach (holidayItems($holiday1OrItems) as $item) {
		if ($item['date'] !== $date) {
			continue;
		}
		if ($item['period'] === 'ALL') {
			return true;
		}
		if ($item['period'] === 'AM' && strpos($slotName, 'AM') === 0) {
			return true;
		}
		if ($item['period'] === 'PM' && strpos($slotName, 'PM') === 0) {
			return true;
		}
	}
	return false;
}

/**
 * Periods registered for a date (may include ALL, AM, PM).
 *
 * @param string|array $holiday1OrItems
 * @param string $date
 * @return array<int, string>
 */
function getHolidayPeriodsForDate($holiday1OrItems, $date)
{
	$date = normalizeHolidayDate($date);
	$periods = array();
	foreach (holidayItems($holiday1OrItems) as $item) {
		if ($item['date'] === $date) {
			$periods[] = $item['period'];
		}
	}
	return $periods;
}

/**
 * Zero out MaxWakuSu bands that are holiday for the day.
 *
 * @param string $wakuRange e.g. "15-15" or "10-10-10"
 * @param array $ampmList e.g. ['AM','PM']
 * @param array $periods periods for that date
 * @return string
 */
function applyHolidayToWakuRange($wakuRange, $ampmList, $periods)
{
	$parts = explode('-', (string)$wakuRange);
	if (!is_array($periods) || count($periods) === 0) {
		return $wakuRange;
	}
	if (in_array('ALL', $periods, true)) {
		for ($i = 0; $i < count($parts); $i++) {
			$parts[$i] = '0';
		}
		return implode('-', $parts);
	}
	foreach ((array)$ampmList as $i => $slotName) {
		if (!isset($parts[$i])) {
			continue;
		}
		if (slotMatchesHolidayPeriods($slotName, $periods)) {
			$parts[$i] = '0';
		}
	}
	return implode('-', $parts);
}

/**
 * @param string $slotName
 * @param array $periods
 * @return bool
 */
function slotMatchesHolidayPeriods($slotName, $periods)
{
	$slotName = strtoupper(trim((string)$slotName));
	foreach ((array)$periods as $period) {
		$period = strtoupper(trim((string)$period));
		if ($period === 'ALL') {
			return true;
		}
		if ($period === 'AM' && strpos($slotName, 'AM') === 0) {
			return true;
		}
		if ($period === 'PM' && strpos($slotName, 'PM') === 0) {
			return true;
		}
	}
	return false;
}

/**
 * HTML for one holiday date input + period select.
 *
 * @param string $dateName
 * @param string $periodName
 * @param string $dateValue
 * @param string $periodValue
 * @param string $dateClass
 * @return string
 */
/**
 * HTML select for ALL/AM/PM next to a holiday date input.
 *
 * @param string $name
 * @param string $selected
 * @param string $extraClass
 * @return string
 */
function holidayPeriodSelectHtml($name, $selected = 'ALL', $extraClass = '')
{
	$selected = strtoupper(trim((string)$selected));
	if ($selected !== 'AM' && $selected !== 'PM') {
		$selected = 'ALL';
	}
	$classAttr = trim('holiday-period ' . $extraClass);
	$html = "<select name=\"" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "\" class=\"" . htmlspecialchars($classAttr, ENT_QUOTES, 'UTF-8') . "\" style=\"width:70px;margin-left:2px;\">";
	foreach (array('ALL' => '全日', 'AM' => '午前', 'PM' => '午後') as $value => $label) {
		$sel = ($selected === $value) ? " selected" : "";
		$html .= "<option value=\"{$value}\"{$sel}>{$label}</option>";
	}
	$html .= "</select>";
	return $html;
}

/**
 * HTML for one holiday date input + period select.
 *
 * @param string $dateName
 * @param string $periodName
 * @param string $dateValue
 * @param string $periodValue
 * @param string $dateClass
 * @return string
 */
function holidayDatePeriodInputHtml($dateName, $periodName, $dateValue = '', $periodValue = 'ALL', $dateClass = 'wHoliday')
{
	$dateValue = htmlspecialchars((string)$dateValue, ENT_QUOTES, 'UTF-8');
	$dateName = htmlspecialchars($dateName, ENT_QUOTES, 'UTF-8');
	$dateClass = htmlspecialchars($dateClass, ENT_QUOTES, 'UTF-8');
	$html = "<input type=\"text\" name=\"{$dateName}\" value=\"{$dateValue}\" class=\"{$dateClass}\" style=\"width:120px\" autocomplete=\"off\">";
	$html .= holidayPeriodSelectHtml($periodName, $periodValue);
	return $html;
}

/**
 * Display label for a holiday token.
 *
 * @param string $token
 * @return string
 */
function formatHolidayDisplay($token)
{
	$parsed = parseHolidayToken($token);
	if ($parsed['date'] === '') {
		return '';
	}
	if ($parsed['period'] === 'ALL') {
		return $parsed['date'];
	}
	if ($parsed['period'] === 'AM') {
		return $parsed['date'] . '(午前)';
	}
	if ($parsed['period'] === 'PM') {
		return $parsed['date'] . '(午後)';
	}
	return $parsed['token'];
}

/**
 * @param string|array $holiday1OrItems
 * @return array<int, array{date:string, period:string, token:string}>
 */
function holidayItems($holiday1OrItems)
{
	if (is_array($holiday1OrItems)) {
		$items = array();
		foreach ($holiday1OrItems as $item) {
			if (is_array($item) && isset($item['date'])) {
				$period = isset($item['period']) ? $item['period'] : 'ALL';
				$items[] = array(
					'date' => normalizeHolidayDate($item['date']),
					'period' => strtoupper($period),
					'token' => formatHolidayToken($item['date'], $period),
				);
			} else {
				$parsed = parseHolidayToken((string)$item);
				if ($parsed['date'] !== '') {
					$items[] = $parsed;
				}
			}
		}
		return $items;
	}
	return parseHoliday1($holiday1OrItems);
}
