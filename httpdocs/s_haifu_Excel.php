<?php

include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";
include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSBukken.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);
########################################################
# 認証動作
########################################################
$rKey = SPFWParameter::getValues('rKey');

$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS);

$loginUserCD = $myUser->UserCD;
$ID = $myUser->ID;
$GyosyaCD = $myUser->Extra5;

unset($myUser);
#####################################################
# 値受け取り
#####################################################
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$TenkenKind = SPFWParameter::getValues('TenkenKind');

$Kitizitsu = date('Y年n月吉日');
#######################################################
# 物件情報抽出
########################################################
$myBukken = new Bukken($myDB);
if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}
if ($myBukken->RecCnt != 1) {
	trigger_error("Getting myBukken List Failed.", E_USER_ERROR);
} else {

	$Date1 = $myBukken->Date1;
	$Date2 = $myBukken->Date2;
	$Date3 = $myBukken->Date3;
	$Date4 = $myBukken->Date4;
	$Date5 = $myBukken->Date5;
	$Date6 = $myBukken->Date6;

	$Dates = array($Date1, $Date2, $Date3, $Date4, $Date5, $Date6);
	usort($Dates, "date_sort");
	$Dates = array_diff($Dates, array(""));
	$Dates = array_values($Dates);
	$FirstDate = current($Dates);
	$LastDate = end($Dates);

	$BukkenName = $myBukken->BukkenName;
	$KanriGaisya = $myBukken->KanriGaisya;
	$KanriGaisyaTEL = $myBukken->KanriGaisyaTEL;

	$week = array("日", "月", "火", "水", "木", "金", "土");
	$Date 	= $FirstDate;
	$DatetoCal = $Date;
	$datetime = date_create($Date);
	$w = (int)date_format($datetime, 'w');
	$Date = date("Y年n月j日", strtotime($Date));

	$LastKojiDate 	= $LastDate;
	$LastKojiDatetoCal = $LastKojiDate;
	$LastKojiDatetime = date_create($LastKojiDate);
	$w3 = (int)date_format($LastKojiDatetime, 'w');
	$LastKojiDate = date("Y年n月j日", strtotime($LastKojiDate));

	$Time 	= $myBukken->KojiTime;
	$SougouAMKojiTime = $myBukken->SougouAMKojiTime;
	$SougouPMKojiTime = $myBukken->SougouPMKojiTime;
	$KikiAMKojiTime = $myBukken->KikiAMKojiTime;
	$KikiPMKojiTime = $myBukken->KikiPMKojiTime;

	$UketsukeShimekiridatetime = date_create($myBukken->UketsukeShimekiriDate);
	$UketsukeShimekiriDate = date("Y年n月j日", strtotime($myBukken->UketsukeShimekiriDate));
	$w2 = (int)date_format($UketsukeShimekiridatetime, 'w');

	$ExistTenkenKikan = $myBukken->ExistTenkenKikan;
	$ExistTenkenKikan_Sougou = $myBukken->ExistTenkenKikan_Sougou;

	$KikiTenkenKikan = $myBukken->KikiTenkenKikan;
	$SougouTenkenKikan = $myBukken->SougouTenkenKikan;
}

// $myBukken->KikiStatus = 3;
$haifudate = date("Y-n-d");
$myBukken->HaifuDownloadDate = $haifudate;

if (!$myBukken->executeUpdate()) {
	trigger_error("executeUpdate(Bukken) Failed.", E_USER_ERROR);
} else {
}

unset($myBukken);
#######################################################
# 作業日程取得
######################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "Date, ";
$sql .= "Time, ";
$sql .= "BuildingNumber, ";
$sql .= "RoomNumber";

$myListObject->SelectSQL = $sql;

$sql = " FROM tKojiNitteiF";
$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
$sql .= " AND TenkenKind = " . $TenkenKind;
$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$KojiNitteiLoop = $myListObject->Rows;
for ($i = 0; $i < $KojiNitteiLoop; $i++) {

	$KobetsuDate[$i] 	= $myListObject->GetValue($i, 0);

	if ($i % 2 == 0) {
		$KobetsuTime[$i] = $KikiAMKojiTime;
	} else {
		$KobetsuTime[$i] = $KikiPMKojiTime;
	}

	$BuildingNumber[$i]	= $myListObject->GetValue($i, 2);
	$RoomNumber[$i]	= $myListObject->GetValue($i, 3);
}
unset($myListObject);

#######################################################
# Excelファイル生成 #
########################################################
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border as Border;
use PhpOffice\PhpSpreadsheet\Style\Fill as Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment as Align;
use PhpOffice\PhpSpreadsheet\Worksheet\SheetView;

$reader = new XlsxReader();

if ($TenkenKind == "1") { //機器

	$spreadsheet = $reader->load('./template/kiki/' . $editBukkenCD . '.xlsx'); //template.xlsx 読込
	$sheet = $spreadsheet->getActiveSheet();

	//複数行 一部住戸
	if ($ExistTenkenKikan == "1") {

		$DatetoCal2 = strtotime($DatetoCal);
		$StartPosition = 22;
		$k = 0;

		if (is_countable($Dates)) {

			for ($j = 0; $j < count($Dates); $j++) {

				// for($j=0; $j< $KojiNitteiLoop; $j++){
				if ($j % 2 == 0 and $j > 0) {

					$k++;

					$KobetsuDate[$j] = strtotime($Dates[$j]);
					// $KobetsuDate[$j] = strtotime($Dates[$k]);
					$DayofWeek[$j] = date('w', $KobetsuDate[$j]);
					$KobetsuDate2[$j] = date('n月j日', $KobetsuDate[$j]);
				} else {

					//追加
					$k++;

					$KobetsuDate[$j] = strtotime($Dates[$j]);
					// $KobetsuDate[$j] = strtotime($Dates[$k]);
					$DayofWeek[$j] = date('w', $KobetsuDate[$j]);
					$KobetsuDate2[$j] = date('n月j日', $KobetsuDate[$j]);
				}

				if ($KikiTenkenKikan == 1) {
				} else {

					if ($RoomNumber[$j]) {

						$sheet->setCellValue('C' . $StartPosition, $KobetsuDate2[$j] . "(" . $week[$DayofWeek[$j]] . ")");
						// $StartPosition = $StartPosition + 3;
						//追加
						$StartPosition = $StartPosition + 6;
					}
				}
			}
		}

		if ($FirstDate == $LastDate) {
			$sheet->setCellValue('C17', $Date . "(" . $week[$w] . ")"); //工事資料配布日
		} else {
			$sheet->setCellValue('C17', $Date . "(" . $week[$w] . ")～" . $LastKojiDate . "(" . $week[$w3] . ")"); //工事資料配布日
		}

		$sheet->setCellValue('T18', $UketsukeShimekiriDate . "(" . $week[$w2] . ")"); //受付締切日
		$sheet->setCellValue("AI1", $Kitizitsu);
	} else if ($ExistTenkenKikan == "3") { //全住戸階指定

		$DatetoCal2 = strtotime($DatetoCal);
		$StartPosition = 22;
		$k = 0;

		for ($j = 0; $j < count($Dates); $j++) {
			// for($j=0; $j< $KojiNitteiLoop; $j++){

			if ($j % 2 == 0 and $j > 0) {

				$k++;

				$KobetsuDate[$j] = strtotime($Dates[$j]);
				// $KobetsuDate[$j] = strtotime($Dates[$k]);
				$DayofWeek[$j] = date('w', $KobetsuDate[$j]);
				$KobetsuDate2[$j] = date('n月j日', $KobetsuDate[$j]);
			} else {

				//追加
				$k++;


				$KobetsuDate[$j] = strtotime($Dates[$j]);
				// $KobetsuDate[$j] = strtotime($Dates[$k]);
				$DayofWeek[$j] = date('w', $KobetsuDate[$j]);
				$KobetsuDate2[$j] = date('n月j日', $KobetsuDate[$j]);
			}

			if ($KikiTenkenKikan == 1) {
			} else {
				if ($RoomNumber[$j]) {

					$sheet->setCellValue('C' . $StartPosition, $KobetsuDate2[$j] . "(" . $week[$DayofWeek[$j]] . ")");
					// $StartPosition = $StartPosition + 3;

					//追加
					$StartPosition = $StartPosition + 6;
				}
			}
		}

		if ($FirstDate == $LastDate) {
			$sheet->setCellValue('C17', $Date . "(" . $week[$w] . ")"); //工事資料配布日
		} else {
			$sheet->setCellValue('C17', $Date . "(" . $week[$w] . ")～" . $LastKojiDate . "(" . $week[$w3] . ")"); //工事資料配布日
		}

		$sheet->setCellValue('U18', $UketsukeShimekiriDate . "(" . $week[$w2] . ")"); //受付締切日
		$sheet->setCellValue("AI1", $Kitizitsu);
	} else { //全住戸

		if ($FirstDate == $LastDate) {

			$sheet->setCellValue('C19', $Date . "(" . $week[$w] . ")"); //工事資料配布日
		} else {
			$sheet->setCellValue('C19', $Date . "(" . $week[$w] . ")～" . $LastKojiDate . "(" . $week[$w3] . ")"); //工事資料配布日
		}
		$sheet->setCellValue('S24', $UketsukeShimekiriDate . "(" . $week[$w2] . ")");
		$sheet->setCellValue("AG1", $Kitizitsu);
	}
} else if ($TenkenKind == "2") { //総合

	$spreadsheet = $reader->load('./template/sougou/' . $editBukkenCD . '.xlsx'); //template.xlsx 読込
	$sheet = $spreadsheet->getActiveSheet();

	if ($ExistTenkenKikan_Sougou == "1") { //複数行

		$DatetoCal2 = strtotime($DatetoCal);
		$StartPosition = 22;
		$k = 0;


		if (is_countable($Dates)) {

			for ($j = 0; $j < count($Dates); $j++) {

				// for($j=0; $j< $KojiNitteiLoop; $j++){
				if ($j % 2 == 0 and $j > 0) {

					$k++;

					$KobetsuDate[$j] = strtotime($Dates[$j]);
					// $KobetsuDate[$j] = strtotime($Dates[$k]);
					$DayofWeek[$j] = date('w', $KobetsuDate[$j]);
					$KobetsuDate[$j] = date('n月j日', $KobetsuDate[$j]);
				} else {

					$k++;

					$KobetsuDate[$j] = strtotime($Dates[$j]);
					// $KobetsuDate[$j] = strtotime($Dates[$k]);
					$DayofWeek[$j] = date('w', $KobetsuDate[$j]);
					$KobetsuDate[$j] = date('n月j日', $KobetsuDate[$j]);
				}

				if ($SougouTenkenKikan == 1) {
				} else {
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('C' . $StartPosition, $KobetsuDate[$j] . "(" . $week[$DayofWeek[$j]] . ")");
					}
				}
				$StartPosition = $StartPosition + 6;

				// $StartPosition = $StartPosition + 3;

			}
		}
		if ($FirstDate == $LastDate) {
			$sheet->setCellValue('C17', $Date . "(" . $week[$w] . ")"); //工事資料配布日
		} else {
			$sheet->setCellValue('C17', $Date . "(" . $week[$w] . ")～" . $LastKojiDate . "(" . $week[$w3] . ")"); //工事資料配布日
		}
		$sheet->setCellValue('T18', $UketsukeShimekiriDate . "(" . $week[$w2] . ")");
		$sheet->setCellValue("AI1", $Kitizitsu);
	} else if ($ExistTenkenKikan_Sougou == "3") { //全住戸階指定

		$DatetoCal2 = strtotime($DatetoCal);
		$StartPosition = 22;
		// $StartPosition = 23;
		$k = 0;


		for ($j = 0; $j < count($Dates); $j++) {

			// for($j=0; $j< $KojiNitteiLoop; $j++){

			if ($j % 2 == 0 and $j > 0) {

				$k++;

				$KobetsuDate[$j] = strtotime($Dates[$j]);
				// $KobetsuDate[$j] = strtotime($Dates[$k]);
				$DayofWeek[$j] = date('w', $KobetsuDate[$j]);
				$KobetsuDate[$j] = date('n月j日', $KobetsuDate[$j]);
			} else {

				$k++;
				$KobetsuDate[$j] = strtotime($Dates[$j]);

				// $KobetsuDate[$j] = strtotime($Dates[$k]);
				$DayofWeek[$j] = date('w', $KobetsuDate[$j]);
				$KobetsuDate[$j] = date('n月j日', $KobetsuDate[$j]);
			}

			if ($SougouTenkenKikan == 1) {
			} else {
				if ($RoomNumber[$j]) {
					$sheet->setCellValue('C' . $StartPosition, $KobetsuDate[$j] . "(" . $week[$DayofWeek[$j]] . ")");
				}
			}
			// $StartPosition = $StartPosition + 3;
			$StartPosition = $StartPosition + 6;
		}
		if ($FirstDate == $LastDate) {
			$sheet->setCellValue('C17', $Date . "(" . $week[$w] . ")"); //工事資料配布日
		} else {
			$sheet->setCellValue('C17', $Date . "(" . $week[$w] . ")～" . $LastKojiDate . "(" . $week[$w3] . ")"); //工事資料配布日
		}
		$sheet->setCellValue('U18', $UketsukeShimekiriDate . "(" . $week[$w2] . ")");
		$sheet->setCellValue("AI1", $Kitizitsu);
	} else { //全住戸

		if ($FirstDate == $LastDate) {

			$sheet->setCellValue('C19', $Date . "(" . $week[$w] . ")"); //工事資料配布日
		} else {
			$sheet->setCellValue('C19', $Date . "(" . $week[$w] . ")～" . $LastKojiDate . "(" . $week[$w3] . ")"); //工事資料配布日
		}
		$sheet->setCellValue('S24', $UketsukeShimekiriDate . "(" . $week[$w2] . ")");
		$sheet->setCellValue("AG1", $Kitizitsu);
	}
} else {
}

# 余白調整
$sheet->getPageMargins()->setTop(0);
$sheet->getPageMargins()->setBottom(0);
$sheet->getPageMargins()->setRight(0);
$sheet->getPageMargins()->setLeft(0);

#シート名変更
$sheet = $spreadsheet->getSheetByName('案内資料');

//ダウンロード用
header("Content-Description: File Transfer");
$wFileName = "お知らせ_" . $BukkenName . "_" . date('YmdHis') . ".xlsx";
$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

header("Content-Disposition: attachment; filename=" . $wFileName);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
ob_end_clean(); //バッファ消去

$writer = new XlsxWriter($spreadsheet);
$writer->save('php://output');

########################################################
# 関数群
########################################################
function date_sort($a, $b)
{
	return strtotime($a) - strtotime($b);
}
