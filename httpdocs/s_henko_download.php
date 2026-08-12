<?php
$isAdminMode = TRUE;
// ini_set('display_errors', "On");

include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";
include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
// include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPFWTools.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
$m 				= SPFWParameter::getValues('m');

########################################################
# 認証動作
########################################################
if ($rKey) {
	$myUser = new User($myDB);
	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS2);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS2);

	$UserCD 		= $myUser->UserCD;
	$ClientCD 		= $myUser->ClientCD; #幹事企業CD
	$UserKbn 		= $myUser->UserKbn;
	unset($myUser);
}

function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}

function obtainWakuTime($WakuPattern, $TimeFrom,$WAKUPATTERN){
	$TimeFrom = substr($TimeFrom,11,5);#09:20
    $sTimeFrom = strtotime($TimeFrom);
    $WakuSu = isset($WAKUPATTERN[$WakuPattern]['AMPM'])?count($WAKUPATTERN[$WakuPattern]['AMPM']):0;

    for($i=0; $i<$WakuSu; $i++){
        $StartTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
        $sStartTime = strtotime($StartTime);
        $EndTime = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
        $sEndTime = strtotime($EndTime);

        // 終了時刻ちょうど（例: AM 12:00）も枠内とする（sh_henko_list と同じ）
        if($sStartTime <= $sTimeFrom && $sTimeFrom <= $sEndTime){
            // $WakuTime['STime'] = $StartTime;
            // $WakuTime['ETime'] = $EndTime;
			$WakuTime = $WAKUPATTERN[$WakuPattern]['AMPM'][$i];

			// echo "<br> ".__LINE__." StartTime :".$StartTime;
			// echo "<br> ".__LINE__." EndTime :".$EndTime;

            return $WakuTime;
        }
    }
    return null;  // どの時間枠にも当てはまらない場合はnullを返す

}


$myBukken = new Bukken($myDB);
if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
	unset($myTemplate);
	exit;
}

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "") || $myBuilding->RecCnt != 1) {
		$ErrorString = array();
		$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}
}

$MansionName = $myBukken->BukkenName;
$wBuildingName = $myBukken->BuildingName;

$WakuPattern = $myBukken->WakuPattern;
if($editBuildingCD){
	$WakuPattern = $myBuilding->WakuPattern;
	$wBuildingName = $myBuilding->BuildingName;
}

// 棟一覧
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "u.BuildingCD, ";
$sql .= "u.BuildingName ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tBuildingM u ";
$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$editBukkenCD."'";

$myListObject->Condition = $sql;
$myListObject->Order = "u.BuildingCD ASC";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting User List Failed.", E_USER_ERROR);

$BuildingCD = [];
$BuildingName = [];
$BuildingLoop = $myListObject->Rows;

for ($i = 0; $i < $BuildingLoop; $i++) {
	$BuildingCD[$i] = $myListObject->GetValue($i, 0);
	$BuildingName[$i] = $myListObject->GetValue($i, 1);
}
unset($myListObject);

// 棟名称が空の場合、例外処理
if(!$wBuildingName){
	if($editBuildingCD){
		$wBuildingName = '棟'.numberToCircled(2);
		for ($i = 0; $i < $BuildingLoop; $i++) {
			if($BuildingCD[$i] == $editBuildingCD){
				$wBuildingName = '棟'. numberToCircled($i+2);
			}
		}

	}else{
		if($BuildingLoop > 0){
			$wBuildingName = '棟'.numberToCircled(1);
		}
	}
}
if($BuildingLoop < 1){
	$wBuildingName = '';
}

$TargetClientCD = $myBukken->ClientCD; #111
$ClientCD = $TargetClientCD;
unset($myBukken);


########################################################
# 担当者リスト表示
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "UserCD, ";
$sql .= "LastName ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tUserM";
// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
$sql .= " WHERE MukouFlg = FALSE AND ClientCD = " . $ClientCD;
$sql .= " AND UserKbn < 4 ";#住人さん以外
$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$UserLoop = $myListObject->Rows;
for ($i = 0; $i < $UserLoop; $i++) {
	$UserCDs[$i] 	= $myListObject->GetValue($i, 0);
	$LastName[$i]	= $myListObject->GetValue($i, 1);
	$LastNameArray[$UserCDs[$i]] = $LastName[$i];
}
unset($myListObject);

if (is_array($WAKUPATTERN[$WakuPattern]['AMPM'])) {
	$WakuKazu = count($WAKUPATTERN[$WakuPattern]['AMPM']);
}
// $WakuKazu = count($WAKUPATTERN[$WakuPattern]['AMPM']);
$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][0];
$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$WakuKazu - 1];

$TimeLoop  = $WakuKazu;
for ($i = 0; $i < $TimeLoop; $i++) { #時間選択し
	$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
	$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
	$wTime[$i] = $sSTime . "～" . $sETime;
}

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "r.ID, ";
$sql .= "r.TimeFrom, ";
$sql .= "r.Memo, ";
$sql .= "u.LastName, ";
$sql .= "u.TEL, ";
$sql .= "u.Updater,";
$sql .= "u.Updated,";
$sql .= "u.ReplyFlg,";
$sql .= "s.FilePath,";
$sql .= "r.TimeExact,";
$sql .= "r.TimeMeaning,";
$sql .= "u.ConfirmFlg";
$myListObject->SelectSQL = $sql;

$sql = " FROM tUserM u left outer join tReservationF r on r.UserCD = u.UserCD ";
$sql .= " left outer join tSignF s on s.UserCD = u.UserCD and s.MukouFlg = FALSE";

// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
$sql .= " WHERE u.MukouFlg = FALSE AND u.BukkenCD = " . $editBukkenCD;
if($editBuildingCD){
	$sql .= " AND u.BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND u.BuildingCD IS NULL ";
}

$sql .= " AND (r.Created  > CURDATE() - INTERVAL 4 MONTH OR r.Created IS NULL)";
$sql .= " AND (r.Status = 1 OR r.Status IS NULL)";
$myListObject->Condition	= $sql;

$OrderBy 	= SPFWParameter::getValues('OrderBy');

$myListObject->Order 		= str_replace("_", " ", $OrderBy); #"Created desc";
if( $OrderBy == "ID"){
	$OrderBy = " CAST(u.ID AS UNSIGNED)";
	$myListObject->Order 		= $OrderBy; #"Created asc";
}elseif( $OrderBy == "ID_Desc"){
	$OrderBy = " CAST(u.ID AS UNSIGNED) desc";
	$myListObject->Order 		= $OrderBy; #"Created desc";
}else if(!$OrderBy){
	$OrderBy = " CAST(u.ID AS UNSIGNED)";
	$myListObject->Order 		= $OrderBy; #"Created asc";
}

$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$ResidentsFormLoop = $myListObject->Rows;
for ($i = 0; $i < $ResidentsFormLoop; $i++) {
	$ID[$i] 	= $myListObject->GetValue($i, 0);
	$TimeFrom[$i]	= $myListObject->GetValue($i, 1);
	$TimeFromDate[$i]	= date("n/j", strtotime($TimeFrom[$i]));
	$TimeFromTime[$i]	= obtainWakuTime($WakuPattern, $TimeFrom[$i],$WAKUPATTERN);
	$Memo[$i]	= $myListObject->GetValue($i, 2);
	$LastName[$i] = $myListObject->GetValue($i, 3);

	$TEL[$i] = $myListObject->GetValue($i, 4);
	$Updater[$i] = $myListObject->GetValue($i, 5);
	$Updated[$i] = $myListObject->GetValue($i, 6);
	$ReplyFlg[$i] = $myListObject->GetValue($i, 7);
	$FilePath[$i] = $myListObject->GetValue($i, 8);
	$TimeExact[$i] = $myListObject->GetValue($i, 9);
	$TimeMeaning[$i] = $myListObject->GetValue($i, 10);
	$ConfirmFlg[$i] = $myListObject->GetValue($i, 11);
	if($ReplyFlg[$i] == '3'){
		$Status[$i] = '辞退';
	}else if($ReplyFlg[$i] == '1' || $ReplyFlg[$i] == '2' || $ConfirmFlg[$i] == '1'){
		$Status[$i] = '確定';
	}else{
		$Status[$i] = '仮日程';
	}

	$RowClass[$i] = '';
	if($FilePath[$i])
		$RowClass[$i] = 'signed';
	if($ReplyFlg[$i] > 0 ){
		$DispReply[$i] = "レ";
		if($Updater[$i] <> $ID[$i]){
			$DispUpdater[$i] = $LastNameArray[$Updater[$i]]; 
		}else{
			$DispUpdater[$i] = "WEB";
		}
	}
}

unset($myListObject);


########################################################
# Excelファイル生成
########################################################
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Fill;


$reader = new XlsxReader();

$FileFormat = './template/henko_temp.xlsx';
$spreadsheet = $reader->load($FileFormat); //template.xlsx 読込
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', date('Y年 n月 j日'));

if($wBuildingName != ""){
	$sheet->setCellValue("A3", $MansionName."(".$wBuildingName.")　作業工程表");
}else{
	$sheet->setCellValue("A3", $MansionName."　作業工程表");
}

$row_index = 6;
for ($i = 0; $i < $ResidentsFormLoop; $i++) {
	// 部屋番号
	$sheet->setCellValue('B' . $row_index, isset($ID[$i])&&$ID[$i]?$ID[$i]:'');
	// 日程
	$sheet->setCellValue('C' . $row_index, isset($TimeFromDate[$i])&&$TimeFromDate[$i]?$TimeFromDate[$i]:'');
	// 時間帯
	$sheet->setCellValue('D' . $row_index, isset($TimeFromTime[$i])&&$TimeFromTime[$i]?$TimeFromTime[$i]:'');
	// 時間指定
	$sheet->setCellValue('E' . $row_index, (isset($TimeExact[$i])&&$TimeExact[$i]?$TimeExact[$i]:'').(isset($TimeMeaning[$i])?$TimeMeaning[$i]:''));
	// 名前
	$sheet->setCellValue('F' . $row_index, isset($LastName[$i])&&$LastName[$i]?$LastName[$i]:'');
	// 連絡先
	$sheet->setCellValue('G' . $row_index, isset($TEL[$i])&&$TEL[$i]?$TEL[$i]:'');
	// メモ
	$sheet->setCellValue('H' . $row_index, isset($Memo[$i])&&$Memo[$i]?$Memo[$i]:'');
	// 受付有無
	$sheet->setCellValue('I' . $row_index, isset($DispReply[$i])&&$DispReply[$i]?$DispReply[$i]:'');
	// 受付担当
	$sheet->setCellValue('J' . $row_index, isset($DispUpdater[$i])&&$DispUpdater[$i]?$DispUpdater[$i]:'');
	// ステータス
	$sheet->setCellValue('K' . $row_index, isset($Status[$i])&&$Status[$i]?$Status[$i]:'');

	$row_index ++;
}


$centerAlignStyle = new Style();
$centerAlignStyle->applyFromArray([
	'alignment' => [
		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	],
]);

$rightAlignStyle = new Style();
$rightAlignStyle->applyFromArray([
	'alignment' => [
		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
		'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	],
]);

$sheet->duplicateStyle($rightAlignStyle, ('B6:' . 'B'.$row_index));
$sheet->duplicateStyle($centerAlignStyle, ('C6:' . 'K'.$row_index));

for ($i = 0; $i < $ResidentsFormLoop; $i++) {
	if ($Status[$i] == '辞退') {
		$declinedRow = 6 + $i;
		$sheet->getStyle('B' . $declinedRow . ':K' . $declinedRow)->applyFromArray([
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'C1C3C5'],
			],
		]);
	}
}

header("Content-Description: File Transfer");

if($wBuildingName != ""){
	$wFileName = str_replace(",", "", $MansionName)."_" . str_replace(",", "", $wBuildingName) . '_作業工程表_' . date("Ymdhi") . '.xlsx';
}else{
	$wFileName = str_replace(",", "", $MansionName) . '作業工程表_' . date("Ymdhi") . '.xlsx';
}

$ieFileName = mb_convert_encoding($wFileName, 'SJIS-win', 'UTF-8');
$userAgent = $_SERVER['HTTP_USER_AGENT'];

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if (preg_match('/MSIE|Trident/', $userAgent)) {
    // IE の場合
    header('Content-Disposition: attachment; filename="' . $ieFileName . '"');
} else {
    // その他のモダンブラウザ（UTF-8 + RFC5987対応）
    $encodedFilename = rawurlencode($wFileName);
    header("Content-Disposition: attachment; filename*=UTF-8''" . $encodedFilename);
}

header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
ob_end_clean(); //バッファ消去

$writer = new XlsxWriter($spreadsheet);
$writer->save('php://output');
exit;