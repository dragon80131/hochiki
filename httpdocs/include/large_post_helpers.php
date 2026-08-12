<?php
/**
 * Helpers for large POST payloads (max_input_vars mitigation).
 * Batches array fields into KaiRoom_0..N / KaiRoom_count chunks (300 items each).
 */

/**
 * @return array<int, string>|null Chunked values when *_count is present, otherwise null.
 */
function parseChunkedPluralValuesFromRequest($prefix)
{
	$count = SPFWParameter::getValues($prefix . '_count');
	if ($count === null || $count === '' || intval($count) <= 0) {
		return null;
	}

	$values = array();
	for ($i = 0; $i < intval($count); $i++) {
		$chunk = SPFWParameter::getValues($prefix . '_' . $i);
		if ($chunk !== null && $chunk !== '') {
			$values = array_merge($values, SPFWTools::decodePluralValue($chunk));
		}
	}

	return $values;
}

/**
 * @return array<int, string>
 */
function parseKaiRoomFromRequest()
{
	$chunked = parseChunkedPluralValuesFromRequest('KaiRoom');
	if ($chunked !== null) {
		return $chunked;
	}

	$direct = SPFWParameter::getValues('KaiRoom');
	if (is_array($direct)) {
		return $direct;
	}
	if ($direct !== null && $direct !== '') {
		return array($direct);
	}

	return array();
}
