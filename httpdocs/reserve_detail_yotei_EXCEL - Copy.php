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
$TenkenKind  = SPFWParameter::getValues('TenkenKind');

// ログインしたユーザーが所属する会社名、電話番号を取得します。
$ClientName = '';
$ClientTEL = '';
$rKey = SPFWParameter::getValues('rKey');
if ($rKey != NULL) {
	$myUser = new User($myDB);
	if ($myUser->doAuthenticationByRegistKey($rKey)){
		$TargetClientCD = $myUser->ClientCD;	
		$myClient = new Client($myDB);
		if ($TargetClientCD > 0) {
			if (!$myClient->executeSelect("ClientCD = $TargetClientCD and MukouFlg = 0" , "") || $myClient->RecCnt != 1){
				// trigger_error("Getting myClient Failed.", E_USER_ERROR);
			}
			$ClientName = $myClient->ClientName;
			$ClientTEL = $myClient->TEL;
		}
	}
}

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

if(!$WakuPattern){

	echo "<br><br><br><font color=red>　作業の枠構成を「作業日程登録」から設定してください。</font>";
	exit;
}
$Created 		= $myBukken->Created;
$CreatedYear 		= substr($Created, 0, 4); // パスの2019を取得（tSettingMのCreatedの年を取得）
$wPasswd = date('ym',strtotime($Created));

$Company 			= $myBukken->Company;
$wConstTime 		= $myBukken->MinuteTime;#20分

$GyosyaCD 		= $myBukken->GyosyaCD;

$myGyosya = new Gyosya($myDB);
if (!$myGyosya->executeSelect("GyosyaCD = '" . $GyosyaCD . "' AND MukouFlg = FALSE", "")) {
	$ErrorString	= array();
	$ErrorString[]	= "tGyosyaM情報の抽出に失敗しました。";
	showAdminSorryPage($ErrorString);
}
$GyosyaName	= $myGyosya->GyosyaName;
// $Created 				= $myBukken->Created;#作成日

// $SenyuStartDate = $myBukken->SenyuStartDate;
// $SenyuEndDate = $myBukken->SenyuEndDate;
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


########################################################
# 日程情報取得
########################################################

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
// print_r($WakuAMRoom);
// echo "<br> ".__LINE__." ここまでOK :";
// print_r($WakuPM1Room);
// echo "<br> ".__LINE__." ここまでOK :";
// print_r($WakuPM2Room);
// echo "<br> ".__LINE__." ここまでOK :";

########################################################
# Excel表をつくる。
########################################################
/*
$CellX = array('C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH');
#$CellY = array('11','12','13','14','15','16','17','18','19','20');
for($i=0; $i< $SenyuDateCnt * $wHansu; $i++){#11行目から開始
	$CellY[] = 21 + $i;
}

$x = 0;
$y = 0;
$date = new DateTime($SenyuStartDate);

for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$SenyuDate = $date->format('m月d日');
	if ($i != 0)$y++;
	$Cell[$CellX[$x]][$CellY[$y]] =  $SenyuDate ;

// echo "<br> ".__LINE__." x :".$x ;
// echo " CellY :".$CellY ;
// echo " ID :".$SenyuDate ;

	$x++;
	$Cell[$CellX[$x]][$CellY[$y]] = $week[$date->format('w')] ;
	for ($j = 0; $j < $wHansu; $j++) {
		if ($j != 0)
			$y++;
		for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
			if($i==0 and $j==0 )${$WakuName . "index"}=0;
			for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
				$x++;
// echo "<br> ".__LINE__." x :".$x ;
// echo " CellY :".$CellY[$y] ;
// echo " ID添え字 :".${$WakuName . "index"};
// echo " ID :".${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
				$Cell[$CellX[$x]][$CellY[$y]] = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
		
				${$WakuName . "index"}++;
			}
		}
		$x=1;#E列にもどる　部屋を入れるところにもどる
	}
	$x=0; #C列にもどる
	$date->modify('+1 days');
}
// echo "<br> ".__LINE__." ★★★ :";
// print_r($Cell);

// echo "<br> ".__LINE__." ★★End★ :";
*/
########################################################
# Excelファイル生成
########################################################
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

$reader = new XlsxReader();

	$FileFormat = './template/yotei_temp_Aweb.xlsx';
if($TenkenKind == 3){
	$FileFormat = './template/yotei_temp_Aweb_zappai.xlsx';
}
$spreadsheet = $reader->load($FileFormat); //template.xlsx 読込

$sheet = $spreadsheet->getActiveSheet();



// $weekArray	= array("日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日");
$week 		= array("日", "月", "火", "水", "木", "金", "土");

// $denwa_path = "./images/denwa1.png";
// $yoteiA_path = "./images/yoteiA.png";
// $yotei_path = "./images/yotei.png";


// 案内資料をDLした際の月を表示
$sheet->setCellValue('B1', date('Y年 n月 吉日'));

// $styleArray = [
// 	'font' => [
// 		'name' => 'HG丸ｺﾞｼｯｸM-PRO',
// 		'size' => '9',
// 	],
	
// ];
// セル範囲に罫線を引く
// $styleArray = [
//     'borders' => [
//         'outline' => [
//             'borderStyle' => Border::BORDER_THICK, // 太線
//             'color' => ['argb' => '00000000'], // 黒色
//         ],
//         'inside' => [
//             'borderStyle' => Border::BORDER_THIN, // 内側は細線
//             'color' => ['argb' => '00000000'], // 黒色
//         ],
//     ],
// ];
// $styleArray = [
//     'font' => [
//         'name' => 'HG丸ｺﾞｼｯｸM-PRO',
//         'size' => 9,
//     ],
//     'borders' => [
//         'outline' => [
//             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // 太線
//             'color' => ['argb' => '00000000'], // 黒色
//         ],
//         'inside' => [
//             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // 内側は細線
//             'color' => ['argb' => '00000000'], // 黒色
//         ],
//     ],
// ];




/*
#部屋マトリックスタイトル部分
$sheet->setCellValue('C20', '日程');
$sheet->setCellValue('D20', '曜日');
$x = 2;#E列
for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];

// 	// A1からC1までのセルを連結（マージ）
// echo "<br> ".__LINE__." Hensu :".$CellX[$x];
// echo "<br> ".__LINE__." Hensu :".$CellX[$x+${'wWaku' . $WakuName . 'Col'}-1];
	$sheet->mergeCells($CellX[$x] . "20:" . $CellX[$x+${'wWaku' . $WakuName . 'Col'} - 1] . "20");
	$sheet->setCellValue($CellX[$x]."20", $WakuName);
	$x = $x + ${'wWaku' . $WakuName . 'Col'};
}

// print_r($WakuCell);#Array ( [E] => AM [J] => PM1 [N] => PM2 )
#部屋マトリックス部分
// echo "<br> ".__LINE__." CellX :".$CellX[0] ;
// echo "<br> ".__LINE__." CellY :".$CellY[0] ;
// echo "<br> ".__LINE__." ID :".$Cell[$CellX[5]][$CellY[1]] ;
// print_r($CellY);# ( [0] => 11 [1] => 12 [2] => 13 [3] => 14 [4] => 15 [5] => 16 [6] => 17 [7] => 18 [8] => 19 [9] => 20 )
// echo "<br> ".__LINE__." ID :".$Cell[$CellX[5]][$CellY[1]] ;
// print_r($CellX);# C [1] => D [2] => E [3] => F [4] => G [5] => H [6] => I [7] => J [8] => K [9] => L [10] => M [11] => N [12] => O [13] => P [14] => Q [15]


for($y=0;$y<count($CellY);$y++){

	for($x=0;$x < ($wWakuColSum + 2) ;$x++){#$xは、日程と曜日部分を加えてLoop

		$sheet->setCellValue($CellX[$x].$CellY[$y], $Cell[$CellX[$x]][$CellY[$y]]);
 	// 	// echo "  ID :".$Cell[$CellX[$x]][$CellY[$y]] ;
	}
}

// if($x==0){#日付　班数分　縦にくっつける
// 	// $sheet->mergeCells("'".$CellX[0].$CellY[$y]."':'".$CellX[$x].$CellY[$y+$wHansu]."'");
// echo "<br> ".__LINE__." Hensu :".$CellY[$y];
// 	// $sheet->mergeCells('C'.$CellY[$y].':C'.(21+$wHansu));
// }
// if($x==1){#曜日
// // 		$sheet->mergeCells($CellX[$x] .$CellY[$y].":" .$CellX[$x].$CellY[$y+$wHansu]);
// }
*/

#罫線をひく
// echo "<br> ".__LINE__." ここまでOK :";
// echo "<br> ".__LINE__." Hensu :".$CellX[0];
// echo "<br> ".__LINE__." Hensu :".$CellY[0];
// echo "<br> ".__LINE__." Hensu :".$CellX[$wWakuColSum + 1];
// echo "<br> ".__LINE__." Hensu :".$SenyuDateCnt;
// echo "<br> ".__LINE__." Hensu :".$wHansu;
// echo "<br> ".__LINE__." Hensu :".($CellY[$SenyuDateCnt*$wHansu -1 ] +1);
// print_r( $CellY );
#$sheet->getStyle("'".$CellX[0].$CellY[0].":".$CellX[$wWakuColSum + 1].($CellY[$SenyuDateCnt*$wHansu -1 ] +1)."'")->applyFromArray($styleArray);
// $sheet->getStyle($CellX[0] . ($CellY[0]-1) . ":" . $CellX[$wWakuColSum + 1] . ($CellY[$SenyuDateCnt * $wHansu - 1]))->applyFromArray($styleArray);



// 3行目から5行目までを非表示にする
// for ($row = ($CellY[$SenyuDateCnt * $wHansu -1]+2); $row <= 43; $row++) {
//     $sheet->getRowDimension($row)->setVisible(false);
// }



// for ($i = 0; $i < $ReservationLoop; $i++) {
// 	$clonedWorksheet = clone $spreadsheet->getSheetByName('Sheet1');
// 	$clonedWorksheet->setTitle($ID[$i]);
// 	$spreadsheet->addSheet($clonedWorksheet);
// 	$sheet = $spreadsheet->getSheetByName($ID[$i]); //weatherシート取得

if($wBuildingName){
	$sheet->setCellValue('B2', $MansionName."(".$wBuildingName.")にお住いの皆様へ"); #マンション名
}else{
	$sheet->setCellValue('B2', $MansionName."にお住いの皆様へ"); #マンション名
}
$sheet->setCellValue('A4', "～".$SagyoName."のお知らせ～"); #マンション名
// 		$sheet->setCellValue('C3', $ID[$i]);	#部屋番号
// 		$sheet->getStyle('C3')
// 			->getFont()
// 			->setBold(true);
// 		$sheet->getStyle('C3')
// 			->getFont()
// 			->setSize(22);


// 	$sheet->setCellValue('B16', date('n月j日', strtotime($TimeFrom[$i])));
// 	$sheet->setCellValue('F16', $weekArray[date('w', strtotime($TimeFrom[$i]))]);
// 	$sheet->setCellValue('H16', $ListTimeFrom[$i] . "～" . $ListTimeTo[$i]);

	$YoyakuEndWeek = $week[date('w', strtotime($YoyakuEndDate))];
	$Kigen = "ご不在・時間変更等の受付締切日：".date('n月j日', strtotime($YoyakuEndDate)) . "(" . $YoyakuEndWeek . ")"; #"必ず、5月7日";
	$sheet->setCellValue('B14', $Kigen);
	$sheet->setCellValue('D18', date('Y年n月j日', strtotime($SenyuStartDate))."　～　".date('Y年n月j日', strtotime($SenyuEndDate)));

	
	$sheet->setCellValue('C38', $MansionName."管理組合");
	$sheet->setCellValue('C39', "清掃会社：".$ClientName);
	$sheet->setCellValue('C40', "ＴＥＬ　：".$ClientTEL);

	// #▲マーク
	// $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
	// $drawing->setPath("./images/attention.png");
	// $drawing->setHeight(65);
	// $drawing->setOffsetY(5);
	// $drawing->setCoordinates('C6');#C6
	// $drawing->setWorksheet($sheet);
	// unset($drawing);


		// #作業員 画像
		// $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		// $drawing->setPath("./images/sagyoin.png");
		// $drawing->setHeight(120);
		// $drawing->setCoordinates('C38');
		// $drawing->setWorksheet($sheet);
		// unset($drawing);




		#QRコード
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setPath($DomainQR_path);
		$drawing->setHeight(80);
		$drawing->setCoordinates('C29');
		$drawing->setWorksheet($sheet);
		unset($drawing);

		$sheet->setCellValue('B33', "物件管理番号：".$editBukkenCD); #物件管理番号
		$sheet->setCellValue('B32', "URL：".$TargetURL); #www.489501.jp/kotei2/
// 		#IDパス
// 		$sheet->setCellValue('I28', $ID[$i]);
 		$sheet->setCellValue('B34', "初期パスワード：".$wPasswd);

		$sheet->setCellValue('B20', '※1部屋あたりの作業時間は約' . $wConstTime . '分です。');

		// $sheet->setCellValue('C52', $MansionName."管理組合"); #マンション名

		// #電話番号
		// $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		// $drawing->setPath($denwa_path);
		// $drawing->setHeight(39);

		// $drawing->setCoordinates('D34');

		// $drawing->setWorksheet($sheet);
		// unset($drawing);

// }

// $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Sheet1'));
// $spreadsheet->removeSheetByIndex($sheetIndex);

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

// echo "<br> ".__LINE__." ここまでOK END:";


function obtainWakuTime($WakuPattern, $TimeFrom,$WAKUPATTERN){
	$TimeFrom = substr($TimeFrom,11,5);#09:20
    $sTimeFrom = strtotime($TimeFrom);
    $WakuSu = count($WAKUPATTERN[$WakuPattern]['AMPM']);

    for($i=0; $i<$WakuSu; $i++){
        $StartTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
        $sStartTime = strtotime($StartTime);
        $EndTime = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
        $sEndTime = strtotime($EndTime);

        // $sTimeFromが$sStartTime以上、かつ$sEndTime以下の場合に時間枠に含まれる
        if($sStartTime <= $sTimeFrom && $sTimeFrom < $sEndTime){
            $WakuTime['STime'] = $StartTime;
            $WakuTime['ETime'] = $EndTime;
            return $WakuTime;
        }
    }
    return null;  // どの時間枠にも当てはまらない場合はnullを返す

}


