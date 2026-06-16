<?php
$isAdminMode = TRUE;
include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
// include_once _CLS_DIR . "SPFWMobile.cls";
include_once _CLS_DIR . "SPUSStylist.cls";
include_once _CLS_DIR . "SPUSSetting.cls";
include_once _CLS_DIR . "SPUSMenu.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSCalendar.cls";
include_once _CLS_DIR . "SPUSUser.cls";
// include_once _CLS_DIR . "SPUSBukken_sf2.cls";
include_once _CLS_DIR . "SPUSSetting.cls";
include_once _CLS_DIR . "SPUSClient.cls";
// include_once _CLS_DIR . "SPUSDLHistory.cls";

include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSGyosya.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";

include_once "./include/common_489.php";
########################################################
# データベース接続
########################################################
$myDB	= new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);

########################################################
# 設定確認
########################################################

$editBukkenCD  = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD  = SPFWParameter::getValues('editBuildingCD');

$myBukken 	= new Bukken($myDB);

if (!$myBukken->executeSelect("BukkenCD=".$editBukkenCD." AND MukouFlg = FALSE", "")) {
	$ErrorString	= array();
	$ErrorString[] 	= "tSettingM情報の抽出に失敗しました。";
	showAdminSorryPage($ErrorString);
}

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "")) {
		$ErrorString	= array();
		$ErrorString[] 	= "tSettingM情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
}


$wClientCD		= $myBukken->ClientCD; #顧客CD
$YoyakuEndDate 		= $myBukken->YoyakuEndDate; #受付締め切り日


$MansionName 		= str_replace("'", "\'", $myBukken->BukkenName);
$wBuildingName 		= $myBukken->BuildingName;

$SagyoName			= $myBukken->SagyoName;
$WakuPattern 		= $myBukken->WakuPattern;
$wArrangeType 		= $myBukken->ArrangeType;
$wFloorReserveInfo 	= $myBukken->FloorReserveInfo;
$TantoCD1 			= $myBukken->TantoCD1;
$TantoCD2 			= $myBukken->TantoCD2;


if($editBuildingCD){
	$WakuPattern = $myBuilding->WakuPattern;
	$wBuildingName = $myBuilding->BuildingName;
	$wArrangeType = $myBuilding->ArrangeType;
	$wFloorReserveInfo = $myBuilding->FloorReserveInfo;
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

function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}

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

if($WakuPattern === ""){
	echo "<br><br><br><font color=red>　作業の枠構成を「作業日程登録」から設定してください。</font>";
	exit;
}
$Created 		= $myBukken->Created;
$CreatedYear 		= substr($Created, 0, 4); // パスの2019を取得（tSettingMのCreatedの年を取得）
$Company 			= $myBukken->Company;
$wConstTime 		= $myBukken->MinuteTime;#20分
$SenyuStartDateGeneral = $myBukken->SenyuStartDate;
$SenyuEndDateGeneral = $myBukken->SenyuEndDate;

$SenyuStartDate = $myBukken->SenyuStartDate1;
$SenyuEndDate = $myBukken->SenyuEndDate1;
if($editBuildingCD){
	$SenyuStartDate = $myBuilding->SenyuStartDate;
	$SenyuEndDate = $myBuilding->SenyuEndDate;
}
if(!$SenyuStartDate || !$SenyuEndDate){
	$SenyuStartDate = $SenyuStartDateGeneral;
	$SenyuEndDate = $SenyuEndDateGeneral;
}

$SenyuDateCnt = (( strtotime( $SenyuEndDate ) -  strtotime( $SenyuStartDate )) / 86400) + 1 ;#専有部日数

$KyoyobuStartDate = $myBukken->KyoyobuStartDate;#共用部作業開始日
$KyoyobuEndDate = $myBukken->KyoyobuEndDate;#共用部作業終了日


$wHansu = $myBukken->Hansu;
$wWakuPattern = $myBukken->WakuPattern;
$MaxWakuSu = $myBukken->MaxWakuSu;
if($editBuildingCD){
	$wHansu = $myBuilding->Hansu;
	$wWakuPattern = $myBuilding->WakuPattern;
	$MaxWakuSu = $myBuilding->MaxWakuSu;
}

$MaxWaku = explode("-",$MaxWakuSu);
$Maxsum = array_sum($MaxWaku);
$wWakuAM = $MaxWaku[0];
$wWakuPM1 = $MaxWaku[1];
if(count($MaxWaku)>2){
	$wWakuPM2 = $MaxWaku[2];
}
$wKanriCompanyCD = $myBukken->KanriCompanyCD;
$wBrancheCD = $myBukken->BrancheCD;
unset($myBukken);
########################################################
# QR生成
########################################################

// #https://app5.489501.jp/kotei2/ でイメージファイルを作った。
// $DomainQR_path 	= _DOCUMENT_ROOT . 'images/kotei2QR.png';
// $TargetURL = "https://app5.489501.jp/kotei2/";

$folderPath = './upfile/'.date('Y',strtotime($Created) );
$DomainQR_path = $folderPath."/".$editBukkenCD.'qrcode.png';
$TargetURL = "https://app5.489501.jp/hochiki/login.php";
if($editBuildingCD){
	$DomainQR_path = $folderPath."/".$editBukkenCD.'-'.$editBuildingCD.'qrcode.png';
	$TargetURL = "https://app5.489501.jp/hochiki/login.php?editBuildingCD=".$editBuildingCD;
}


########################################################
# 物件初期ユーザパスワード
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "Passwd ";
$myListObject->SelectSQL = $sql;
$sql = " FROM tUserM";
$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
if($editBuildingCD){
	$sql .= " AND BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND BuildingCD IS NULL ";
}
$myListObject->Condition	= $sql;
$myListObject->Order 		= "UserCD";
$myListObject->Limit 		= "1";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);
$wPasswd = $myListObject->GetValue(0, 0);#一番初めのユーザのパスワードをセット
unset($myListObject);

########################################################
# 日程情報取得
########################################################
/*
$myListObject = new SPFWListObject($myDB);

$sql  = "SELECT ";
$sql .= "ReservationCD, ";
$sql .= "DATE(TimeFrom) AS Date, ";
$sql .= "CASE ";
$sql .= " WHEN TIME(TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'";
$sql .= " WHEN TIME(TimeFrom) BETWEEN '13:00:00' AND '14:59:00' THEN 'PM1'";
$sql .= " WHEN TIME(TimeFrom) BETWEEN '15:00:00' AND '18:00:00' THEN 'PM2'";
$sql .= " ELSE 'Other'";
$sql .= " END AS AMPM ,";
$sql .= "ID ";
$myListObject->SelectSQL = $sql;
$sql  = " FROM tReservationF";
$sql .= " WHERE Status = 1 AND MukouFlg = FALSE";
$sql .= " AND BukkenCD = " . $editBukkenCD;
if($editBuildingCD){
	$sql .= " AND BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND BuildingCD IS NULL ";
}
$sql .= " AND ClientCD = " . $wClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "TimeFrom,ReservationCD ";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

$ReservationLoop = $myListObject->Rows;
for ($i = 0; $i < $ReservationLoop; $i++) {
	$ReservationCD[$i] = $myListObject->GetValue($i, 0);
	$tDate = $myListObject->GetValue($i, 1);
	$AMPM = $myListObject->GetValue($i, 2);
	$ID[$i] = $myListObject->GetValue($i, 3);
	$Reserve[$tDate][$AMPM][] = $ID[$i];
}
// print_r($Reserve);
// echo "<br> ".__LINE__." ここまでOK :";
//枠名取得
for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {# 3枠なら　3
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
	${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $wHansu);# 5,4,4
	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;#10,8,8
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};#横の合計数　colspan 5+4+4 = 13
}
unset($myListObject);
########################################################
# 初日・土日祝考慮
########################################################

$wWakuSum1 = 0;
$wWakuSum2 = 0;

$week = ['日', '月', '火', '水', '木', '金', '土'];

$date = new DateTime($SenyuStartDate);
$x = 0;
for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$Syoniti[$i] = false;
	$KojiHoliday[$i] = false;
	$holiday[$i] = false;


	if ($i == 0) {
		$Syoniti[$i] = true;
	}
	$SenyuDate = $date->format('Y-m-d');
	$result = array_search($SenyuDate, $SHUKUJITULIST);
	$YoubiCD =  $date->format('w');
	if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
		$holiday[$i] = true;
	}
	// if (count($wHoliday) > 0) { //休工日
	// 	$KojiHoliday[$i] = (array_search($SenyuDate, $wHoliday) === false) ? false : true;
	// }
	for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
		$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];

		for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
			if(isset($Reserve[$SenyuDate][$WakuName][$k])){
				${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$k];
			}elseif (${'wWaku' . $WakuName} > $k) { //残った最大工事枠数分は空き
				${'Waku' . $WakuName . 'Room'}[] = "空き";
			}else{
				${'Waku' . $WakuName . 'Room'}[] = "";
			}	
		}
	}
	$date->modify('+1 days');
}
*/
########################################################
# Excelファイル生成
########################################################
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

$reader = new XlsxReader();

$FileFormat = './template/yotei_temp_Aweb2025.xlsx';
$spreadsheet = $reader->load($FileFormat); //template.xlsx 読込

$sheet = $spreadsheet->getActiveSheet();

// 案内資料をDLした際の月を表示
$sheet->setCellValue('P1', date('Y年 n月 吉日'));


if($wBuildingName){
	$sheet->setCellValue('A2', $MansionName."(".$wBuildingName.")にお住いの皆様へ"); #マンション名
}else{
	$sheet->setCellValue('A2', $MansionName."にお住いの皆様へ"); #マンション名
}
$sheet->setCellValue('B3', "～　".$SagyoName."のご案内　～"); #マンション名

// 専有部点検日
$disSenyuStartDate = "";
if($SenyuStartDate)
	$disSenyuStartDate = date('Y年n月j日', strtotime($SenyuStartDate));
$disSenyuEndDate = "";
if($SenyuEndDate)
	$disSenyuEndDate = date('Y年n月j日', strtotime($SenyuEndDate));
if($disSenyuStartDate != "" || $disSenyuEndDate != "")
	$sheet->setCellValue('H12', $disSenyuStartDate."～".$disSenyuEndDate);
else
	$sheet->setCellValue('H12', "");

// 共用部点検日
$disKyoyobuStartDate = "";
if($KyoyobuStartDate)
	$disKyoyobuStartDate = date('Y年n月j日', strtotime($KyoyobuStartDate));
$disKyoyobuEndDate = "";
if($KyoyobuEndDate)
	$disKyoyobuEndDate = date('Y年n月j日', strtotime($KyoyobuEndDate));
if($disKyoyobuStartDate != "" || $disKyoyobuEndDate != "")
	$sheet->setCellValue('H13', $disKyoyobuStartDate."～".$disKyoyobuEndDate);
else
	$sheet->setCellValue('H13', "");

// 受付締切日
$week	= array("日", "月", "火", "水", "木", "金", "土");
if($YoyakuEndDate){
	$YoyakuEndWeek = $week[date('w', strtotime($YoyakuEndDate))];
	$sheet->setCellValue('E19', "ご不在・時間変更等の受付締切日：".date("n月j日", strtotime($YoyakuEndDate))."(".$YoyakuEndWeek.")");
}else{
	$sheet->setCellValue('E19', "ご不在・時間変更等の受付締切日：");
}

// 点検時間
$WakuKazu = 0;
if (is_array($WAKUPATTERN[$wWakuPattern]['AMPM'])) {
	$WakuKazu = count($WAKUPATTERN[$wWakuPattern]['AMPM']);
}
$sAMPMInfo = '';
for ($i = 0; $i < $WakuKazu; $i++) { #時間選択し
	$sSTime = $WAKUPATTERN[$wWakuPattern]['StartTime'][$i];
	$sETime = $WAKUPATTERN[$wWakuPattern]['EndTime'][$i];
	$wTime[$i] = $sSTime . "～" . $sETime;
	if($sAMPMInfo != '')
		$sAMPMInfo .= '　';
	if($wTime[$i])
		$sAMPMInfo .= $wTime[$i];
}

$sheet->setCellValue('H14', $sAMPMInfo);
// 物件管理番号
$sheet->setCellValue('L21', $editBukkenCD);
// ワンタイムパスワード
$sheet->setCellValue('L22', $wPasswd);
// URL
$sheet->setCellValue('I20', $TargetURL);

#QRコード
$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setPath($DomainQR_path);
$drawing->setHeight(80);
$drawing->setCoordinates('B19');
$drawing->setWorksheet($sheet);
unset($drawing);

// 管理会社
$myListObject = new SPFWListObject($myDB);
$sql = "SELECT ";
$sql .= "KanriCompanyName ";
$myListObject->SelectSQL = $sql;
$sql = " FROM tKanriCompanyM";
$sql .= " WHERE MukouFlg = FALSE AND ClientCD = '" . $wClientCD."' AND KanriCompanyCD='".$wKanriCompanyCD."'";

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "1";

if (!($myListObject->GetList(1))){
}
$KanriCompanyName = $myListObject->GetValue(0, 0);
unset($myListObject);
$sheet->setCellValue('E40', $KanriCompanyName);

// 点検会社
$myListObject = new SPFWListObject($myDB);
$sql = "SELECT ";
$sql .= "BrancheName, BrancheTEL";
$myListObject->SelectSQL = $sql;
$sql = " FROM tBrancheM";
$sql .= " WHERE MukouFlg = FALSE AND BrancheCD='".$wBrancheCD."'";

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "1";

if (!($myListObject->GetList(1))){
}
$BrancheName = $myListObject->GetValue(0, 0);
$BrancheTEL = $myListObject->GetValue(0, 1);
unset($myListObject);

$sheet->setCellValue('E41', "ホーチキ株式会社".$BrancheName."メンテナンスセンター");
$sheet->setCellValue('E42', $BrancheTEL);


// //ダウンロード用
// //MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
header("Content-Description: File Transfer");
if($wBuildingName){
	$wFileName = str_replace(",", "", $MansionName).'_' . str_replace(",", "", $wBuildingName) . '_予定案内_' . date("Ymdhi") . '.xlsx';
}else{
	$wFileName = str_replace(",", "", $MansionName) . '予定案内_' . date("Ymdhi") . '.xlsx';
}

$ieFileName = mb_convert_encoding($wFileName, 'SJIS-win', 'UTF-8');
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/MSIE|Trident/', $userAgent)) {
    // IE の場合
    header('Content-Disposition: attachment; filename="' . $ieFileName . '"');
} else {
    // その他のモダンブラウザ（UTF-8 + RFC5987対応）
    $encodedFilename = rawurlencode($wFileName);
    header("Content-Disposition: attachment; filename*=UTF-8''" . $encodedFilename);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
ob_end_clean(); //バッファ消去

$writer = new XlsxWriter($spreadsheet);
$writer->save('php://output');
exit;