<?php
/**
 * WEB予約の空き枠判定（ホーチキ: 時間外のみ / 点検: 従来どおり時間外のみ）
 */

/**
 * WEB申込で「時間外」枠のみを空きとして扱うか。
 * - 点検 (ArrangeType=1): 従来どおり時間外のみ
 * - 工事 (ArrangeType!=1): FrameOverflow > 0 のとき時間外のみ（余地は対象外）
 */
function webReserveUsesOverflowSlotsOnly($wArrangeType, $wFrameOverflow)
{
	if ($wArrangeType == '1') {
		return true;
	}
	return intval($wFrameOverflow) > 0;
}
