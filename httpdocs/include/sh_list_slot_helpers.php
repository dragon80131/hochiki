<?php
if (!defined('SH_LIST_SLOT_AKI')) {
	define('SH_LIST_SLOT_AKI', 'aki');
}
if (!defined('SH_LIST_SLOT_WAKUOVER')) {
	define('SH_LIST_SLOT_WAKUOVER', 'wakuover');
}

function shListApplyBlankSlotTypeToReservation(&$myReservation, $blankSlotType) {
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

function shListRecordWakuSlotMeta(&$rooms, &$slotMeta, $viewOrderNo, $ban, $senyuDate) {
	$lastIdx = count($rooms) - 1;
	if ($lastIdx < 0) {
		return;
	}
	$slotVal = $rooms[$lastIdx];
	$slotType = 'room';
	if ($slotVal === '空き') {
		$slotType = '空き';
	} else if ($slotVal === '枠越') {
		$slotType = '枠越';
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
	if ($rooms[$index] === $roomId) {
		$rooms[$index] = '空き';
	} else if ($rooms[$index] === 'overflow@' . $roomId) {
		$restore = '枠越';
		if (isset($slotMeta[$index]['slotType']) && $slotMeta[$index]['slotType'] === '空き') {
			$restore = '空き';
		}
		$rooms[$index] = $restore;
	}
}

function shListApplyAkiSlotAssignments(&$rooms, &$slotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType) {
	if (empty($rooms) || empty($slotMeta)) {
		return;
	}

	foreach ($arrSlotType as $roomId => $slotType) {
		if ($slotType !== SH_LIST_SLOT_AKI) {
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

		$targetIdx = -1;
		$targetDate = null;
		for ($i = 0; $i < count($rooms); $i++) {
			if (!isset($slotMeta[$i])) {
				continue;
			}
			$meta = $slotMeta[$i];
			if (intval($meta['viewOrderNo']) !== $targetViewOrderNo) {
				continue;
			}
			if (intval($meta['ban']) !== $targetBan) {
				continue;
			}
			if ($rooms[$i] !== '空き' && $rooms[$i] !== $roomId && $rooms[$i] !== 'overflow@' . $roomId) {
				continue;
			}
			$targetIdx = $i;
			$targetDate = $meta['date'];
			break;
		}
		if ($targetIdx < 0 || $targetDate === null) {
			continue;
		}

		for ($i = 0; $i < count($rooms); $i++) {
			if (!isset($slotMeta[$i]) || $slotMeta[$i]['date'] !== $targetDate) {
				continue;
			}
			if ($rooms[$i] === $roomId || $rooms[$i] === 'overflow@' . $roomId) {
				shListRestoreRemovedRoomSlot($rooms, $slotMeta, $i, $roomId);
			}
		}

		$rooms[$targetIdx] = $roomId;
	}
}
