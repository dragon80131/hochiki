<?php
require_once __DIR__ . '/kotei_slot_labels.php';

if (!defined('SH_LIST_SLOT_AKI')) {
	define('SH_LIST_SLOT_AKI', 'aki');
}
if (!defined('SH_LIST_SLOT_WAKUOVER')) {
	define('SH_LIST_SLOT_WAKUOVER', 'wakuover');
}

/**
 * TEL受付で「余地」枠への新規登録・変更を許可するか。
 * ホーチキ仕様: 余地は表示用バッファのみ。登録は時間外のみ。
 */
function shListAllowsAkiSlotBooking()
{
	return false;
}

function shListRejectAkiSlotBooking($blankSlotType)
{
	return !shListAllowsAkiSlotBooking() && $blankSlotType === SH_LIST_SLOT_AKI;
}

function shListApplyBlankSlotTypeToReservation(&$myReservation, $blankSlotType) {
	if (shListRejectAkiSlotBooking($blankSlotType)) {
		$myReservation->R003 = NULL;
		return;
	}
	if ($blankSlotType === SH_LIST_SLOT_AKI) {
		$myReservation->R003 = SH_LIST_SLOT_AKI;
	} else if ($blankSlotType === SH_LIST_SLOT_WAKUOVER) {
		$myReservation->R003 = SH_LIST_SLOT_WAKUOVER;
	} else {
		$myReservation->R003 = NULL;
	}
}

function shListFormatOverflowRoom($roomId, $arrSlotType) {
	if (isset($arrSlotType[$roomId]) && $arrSlotType[$roomId] === SH_LIST_SLOT_AKI) {
		return $roomId;
	}
	return 'overflow@' . $roomId;
}

function shListRecordWakuSlotMeta(&$rooms, &$slotMeta, $viewOrderNo, $ban, $senyuDate, $forceSlotType = null) {
	$lastIdx = count($rooms) - 1;
	if ($lastIdx < 0) {
		return;
	}
	$slotVal = $rooms[$lastIdx];
	$slotType = 'room';
	if ($forceSlotType !== null && $forceSlotType !== '') {
		$slotType = $forceSlotType;
	} else if ($slotVal === _SLOT_LABEL_AKI || isSlotLabelAki($slotVal)) {
		$slotType = _SLOT_LABEL_AKI;
	} else if ($slotVal === _SLOT_LABEL_WAKUOVER || isSlotLabelWakuover($slotVal)) {
		$slotType = _SLOT_LABEL_WAKUOVER;
	}
	$slotMeta[] = array(
		'viewOrderNo' => intval($viewOrderNo),
		'ban' => intval($ban),
		'date' => $senyuDate,
		'slotType' => $slotType,
	);
}

function shListRestoreRemovedRoomSlot(&$rooms, &$slotMeta, $index, $roomId) {
	if (!isset($rooms[$index])) {
		return;
	}
	$roomId = strval($roomId);
	$metaSlotType = isset($slotMeta[$index]['slotType']) ? $slotMeta[$index]['slotType'] : null;
	if ($rooms[$index] === $roomId || $rooms[$index] === 'overflow@' . $roomId) {
		$restore = _SLOT_LABEL_AKI;
		if ($metaSlotType === _SLOT_LABEL_WAKUOVER || isSlotLabelWakuover($metaSlotType)) {
			$restore = _SLOT_LABEL_WAKUOVER;
		} else if ($rooms[$index] === 'overflow@' . $roomId && ($metaSlotType === _SLOT_LABEL_AKI || isSlotLabelAki($metaSlotType))) {
			$restore = _SLOT_LABEL_AKI;
		}
		$rooms[$index] = $restore;
	}
}

function shListNormalizeReservationDate($timeFrom) {
	if ($timeFrom === null || $timeFrom === '') {
		return null;
	}
	return date('Y-m-d', strtotime($timeFrom));
}

function shListApplyBlankSlotAssignments(&$rooms, &$slotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType, $arrTimeFrom = array(), $currentWakuName = null, $arrAmpm = array()) {
	if (empty($rooms) || empty($slotMeta)) {
		return;
	}

	foreach ($arrSlotType as $roomId => $slotType) {
		if ($slotType !== SH_LIST_SLOT_AKI && $slotType !== SH_LIST_SLOT_WAKUOVER) {
			continue;
		}
		$roomId = strval($roomId);

		if ($currentWakuName !== null && isset($arrAmpm[$roomId]) && $arrAmpm[$roomId] !== $currentWakuName) {
			continue;
		}

		$targetViewOrderNo = intval($arrViewOrderNo[$roomId] ?? 0);
		$targetBan = intval($arrHanNo[$roomId] ?? 0);
		if ($targetViewOrderNo <= 0) {
			continue;
		}
		if ($targetBan <= 0) {
			$targetBan = 1;
		}

		$targetDate = isset($arrTimeFrom[$roomId]) ? shListNormalizeReservationDate($arrTimeFrom[$roomId]) : null;

		$targetIdx = -1;
		for ($i = 0; $i < count($rooms); $i++) {
			if (!isset($slotMeta[$i])) {
				continue;
			}
			$meta = $slotMeta[$i];
			if ($targetDate !== null && $meta['date'] !== $targetDate) {
				continue;
			}
			if (intval($meta['viewOrderNo']) !== $targetViewOrderNo) {
				continue;
			}
			if (intval($meta['ban']) !== $targetBan) {
				continue;
			}
			if ($slotType === SH_LIST_SLOT_AKI) {
				if (!isSlotLabelAki($rooms[$i]) && $rooms[$i] !== $roomId && $rooms[$i] !== 'overflow@' . $roomId) {
					continue;
				}
			} else if (!isSlotLabelWakuover($rooms[$i]) && $rooms[$i] !== $roomId && $rooms[$i] !== 'overflow@' . $roomId) {
				continue;
			}
			$targetIdx = $i;
			break;
		}
		if ($targetIdx < 0) {
			continue;
		}

		for ($i = 0; $i < count($rooms); $i++) {
			if ($i === $targetIdx) {
				continue;
			}
			if ($rooms[$i] === $roomId || $rooms[$i] === 'overflow@' . $roomId) {
				shListRestoreRemovedRoomSlot($rooms, $slotMeta, $i, $roomId);
			}
		}

		if ($slotType === SH_LIST_SLOT_AKI) {
			$rooms[$targetIdx] = $roomId;
		} else {
			$rooms[$targetIdx] = 'overflow@' . $roomId;
		}
	}
}

function shListApplyAkiSlotAssignments(&$rooms, &$slotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType, $arrTimeFrom = array(), $currentWakuName = null, $arrAmpm = array()) {
	shListApplyBlankSlotAssignments($rooms, $slotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType, $arrTimeFrom, $currentWakuName, $arrAmpm);
}
