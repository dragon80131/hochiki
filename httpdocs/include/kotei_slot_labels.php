<?php
/**
 * 工程表スロット表示ラベル（環境別）
 *
 * hochiki 本番: 余地 / 時間外  （SPFW/inc/setting.properties で定義）
 * nespe_dia 開発: 空き / 枠越
 */

if (!defined('_SLOT_LABEL_AKI')) {
	define('_SLOT_LABEL_AKI', '空き');
}
if (!defined('_SLOT_LABEL_WAKUOVER')) {
	define('_SLOT_LABEL_WAKUOVER', '枠越');
}

function slotLabelAki()
{
	return _SLOT_LABEL_AKI;
}

function slotLabelWakuover()
{
	return _SLOT_LABEL_WAKUOVER;
}

/** 空き枠ラベルか（現行・旧 nespe・旧 hochiki 表記を許容） */
function isSlotLabelAki($label)
{
	return $label === _SLOT_LABEL_AKI || $label === '空き' || $label === '余地';
}

/** 枠越/時間外ラベルか */
function isSlotLabelWakuover($label)
{
	return $label === _SLOT_LABEL_WAKUOVER || $label === '枠越' || $label === '時間外';
}

/** 入力値を現在環境の表示ラベルに正規化 */
function normalizeSlotLabelToCurrent($label)
{
	if (isSlotLabelAki($label)) {
		return _SLOT_LABEL_AKI;
	}
	if (isSlotLabelWakuover($label)) {
		return _SLOT_LABEL_WAKUOVER;
	}
	return $label;
}

/** 休工日と同様に「未割当」扱いのスロットラベル一覧 */
function slotBlankLabels()
{
	return array(_SLOT_LABEL_AKI, _SLOT_LABEL_WAKUOVER, '休工', '');
}
