<?php
/**
 * TEL受付（sh_list.php）空き枠配置ヘルパーの単体テスト
 * 実行: php httpdocs/tests/sh_list_slot_test.php
 */
require_once __DIR__ . '/../include/sh_list_slot_helpers.php';

$failed = 0;

function assertSameValue($expected, $actual, $message) {
	global $failed;
	if ($expected != $actual) {
		echo "FAIL: {$message}\n";
		echo "  expected: " . var_export($expected, true) . "\n";
		echo "  actual:   " . var_export($actual, true) . "\n";
		$failed++;
		return;
	}
	echo "PASS: {$message}\n";
}

$reservation = new stdClass();
$reservation->R003 = 'old';
shListApplyBlankSlotTypeToReservation($reservation, SH_LIST_SLOT_AKI);
assertSameValue('aki', $reservation->R003, '空き枠選択時は R003=aki');

shListApplyBlankSlotTypeToReservation($reservation, SH_LIST_SLOT_WAKUOVER);
assertSameValue('wakuover', $reservation->R003, '枠越枠選択時は R003=wakuover');

shListApplyBlankSlotTypeToReservation($reservation, '');
assertSameValue(null, $reservation->R003, '通常登録時は R003 をクリア');

assertSameValue(
	'103',
	shListFormatOverflowRoom('103', array('103' => SH_LIST_SLOT_AKI)),
	'空き指定の部屋は overflow@ にしない'
);
assertSameValue(
	'overflow@103',
	shListFormatOverflowRoom('103', array('103' => SH_LIST_SLOT_WAKUOVER)),
	'枠越指定の部屋は overflow@ のまま'
);

$rooms = array('1101', 'overflow@103', '空き', '枠越');
$slotMeta = array(
	array('viewOrderNo' => 1, 'ban' => 1, 'date' => '2026-08-24', 'slotType' => 'room'),
	array('viewOrderNo' => 2, 'ban' => 1, 'date' => '2026-08-24', 'slotType' => '枠越'),
	array('viewOrderNo' => 3, 'ban' => 1, 'date' => '2026-08-24', 'slotType' => '空き'),
	array('viewOrderNo' => 4, 'ban' => 1, 'date' => '2026-08-24', 'slotType' => '枠越'),
);
$arrViewOrderNo = array('103' => 3);
$arrHanNo = array('103' => 1);
$arrSlotType = array('103' => SH_LIST_SLOT_AKI);
$arrTimeFrom = array('103' => '2026-08-24 13:00:00');
$arrAmpm = array('103' => 'PM');

shListApplyBlankSlotAssignments($rooms, $slotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType, $arrTimeFrom, 'PM', $arrAmpm);

assertSameValue('1101', $rooms[0], '他部屋はそのまま');
assertSameValue('枠越', $rooms[1], '旧 overflow 位置は枠越に戻す');
assertSameValue('103', $rooms[2], '空き枠に部屋103を配置');
assertSameValue('枠越', $rooms[3], '枠越ラベルは維持');

// 同日・同 ViewOrderNo が複数日ある場合は TimeFrom の日付で絞り込む
$rooms = array(
	'空き', '410', '802',
	'空き', 'overflow@410', '901',
);
$slotMeta = array(
	array('viewOrderNo' => 2, 'ban' => 1, 'date' => '2026-09-14', 'slotType' => '空き'),
	array('viewOrderNo' => 2, 'ban' => 1, 'date' => '2026-09-14', 'slotType' => 'room'),
	array('viewOrderNo' => 3, 'ban' => 1, 'date' => '2026-09-14', 'slotType' => 'room'),
	array('viewOrderNo' => 2, 'ban' => 1, 'date' => '2026-09-21', 'slotType' => '空き'),
	array('viewOrderNo' => 2, 'ban' => 1, 'date' => '2026-09-21', 'slotType' => 'room'),
	array('viewOrderNo' => 3, 'ban' => 1, 'date' => '2026-09-21', 'slotType' => 'room'),
);
$arrViewOrderNo = array('410' => 2);
$arrHanNo = array('410' => 1);
$arrSlotType = array('410' => SH_LIST_SLOT_AKI);
$arrTimeFrom = array('410' => '2026-09-14 13:00:00');
$arrAmpm = array('410' => 'PM');

shListApplyBlankSlotAssignments($rooms, $slotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType, $arrTimeFrom, 'PM', $arrAmpm);

assertSameValue('410', $rooms[0], '9/14 PM の空き枠に 410 を配置');
assertSameValue('空き', $rooms[1], '9/14 の旧 410 位置は空きに戻す');
assertSameValue('802', $rooms[2], '9/14 の他部屋はそのまま');
assertSameValue('空き', $rooms[3], '9/21 の空き枠はそのまま');
assertSameValue('901', $rooms[5], '9/21 の他部屋はそのまま');

// 枠越枠への移動
$rooms = array('1101', '枠越', 'overflow@410');
$slotMeta = array(
	array('viewOrderNo' => 1, 'ban' => 1, 'date' => '2026-09-14', 'slotType' => 'room'),
	array('viewOrderNo' => 2, 'ban' => 1, 'date' => '2026-09-14', 'slotType' => '枠越'),
	array('viewOrderNo' => 3, 'ban' => 1, 'date' => '2026-09-14', 'slotType' => '枠越'),
);
$arrViewOrderNo = array('410' => 2);
$arrHanNo = array('410' => 1);
$arrSlotType = array('410' => SH_LIST_SLOT_WAKUOVER);
$arrTimeFrom = array('410' => '2026-09-14 13:00:00');
$arrAmpm = array('410' => 'PM');

shListApplyBlankSlotAssignments($rooms, $slotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType, $arrTimeFrom, 'PM', $arrAmpm);

assertSameValue('1101', $rooms[0], '枠越移動: 他部屋はそのまま');
assertSameValue('overflow@410', $rooms[1], '枠越枠に overflow@410 を配置');
assertSameValue('枠越', $rooms[2], '旧 overflow 位置は枠越に戻す');

// PM に移動した部屋は AM 配列には反映しない
$amRooms = array('601', '空き', '603');
$amSlotMeta = array(
	array('viewOrderNo' => 1, 'ban' => 1, 'date' => '2026-08-27', 'slotType' => 'room'),
	array('viewOrderNo' => 2, 'ban' => 1, 'date' => '2026-08-27', 'slotType' => '空き'),
	array('viewOrderNo' => 3, 'ban' => 1, 'date' => '2026-08-27', 'slotType' => 'room'),
);
$pmRooms = array('701', '空き', '703');
$pmSlotMeta = array(
	array('viewOrderNo' => 1, 'ban' => 1, 'date' => '2026-08-27', 'slotType' => 'room'),
	array('viewOrderNo' => 2, 'ban' => 1, 'date' => '2026-08-27', 'slotType' => '空き'),
	array('viewOrderNo' => 3, 'ban' => 1, 'date' => '2026-08-27', 'slotType' => 'room'),
);
$arrViewOrderNo = array('209' => 2);
$arrHanNo = array('209' => 1);
$arrSlotType = array('209' => SH_LIST_SLOT_AKI);
$arrTimeFrom = array('209' => '2026-08-27 13:00:00');
$arrAmpm = array('209' => 'PM');

shListApplyBlankSlotAssignments($amRooms, $amSlotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType, $arrTimeFrom, 'AM', $arrAmpm);
shListApplyBlankSlotAssignments($pmRooms, $pmSlotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType, $arrTimeFrom, 'PM', $arrAmpm);

assertSameValue('601', $amRooms[0], 'AM: 他部屋はそのまま');
assertSameValue('空き', $amRooms[1], 'AM: PM移動の部屋209は入れない');
assertSameValue('603', $amRooms[2], 'AM: 他部屋はそのまま');
assertSameValue('209', $pmRooms[1], 'PM: 空き枠に部屋209を配置');

if ($failed > 0) {
	echo "\n{$failed} test(s) failed.\n";
	exit(1);
}

echo "\nAll tests passed.\n";
echo "\n--- 手動確認手順（TEL受付 / sh_list.php）---\n";
echo "1. 確定済み部屋をクリック → 変更前情報が表示されること\n";
echo "2. 青字の「空き」セルをクリック → 変更後日時が反映されること\n";
echo "3. 下部フォームから登録 → 再表示後、部屋が青字の通常枠（overflow@ ではない）で表示されること\n";
echo "4. 赤字の「枠越」セルへ移動した場合は、従来どおり赤字（overflow@）表示になること\n";
