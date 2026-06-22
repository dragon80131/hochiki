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

shListApplyAkiSlotAssignments($rooms, $slotMeta, $arrViewOrderNo, $arrHanNo, $arrSlotType);

assertSameValue('1101', $rooms[0], '他部屋はそのまま');
assertSameValue('枠越', $rooms[1], '旧 overflow 位置は枠越に戻す');
assertSameValue('103', $rooms[2], '空き枠に部屋103を配置');
assertSameValue('枠越', $rooms[3], '枠越ラベルは維持');

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
