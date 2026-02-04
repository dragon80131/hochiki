<?php

include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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

$week = array("日", "月", "火", "水", "木", "金", "土");

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

	$Dates = array($Date1, $Date2, $Date3, $Date4, $Date5);
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

	//防災情報
	$BousaiFlg = $myBukken->BousaiFlg;
	$BousaiTenkenMonth = $myBukken->BousaiTenkenMonth;
	$BousaiKojiTime = $myBukken->BousaiKojiTime;
	$GyosyaBousaiCD = $myBukken->GyosyaBousaiCD;
	$KanriCompanyBousaiCD = $myBukken->KanriCompanyBousaiCD;
	$BousaiStartDate = $myBukken->BousaiStartDate;
	$BousaiEndDate = $myBukken->BousaiEndDate;

	$BousaiStartDate2 = date_create($BousaiStartDate);
	$w2 = (int)date_format($BousaiStartDate2, 'w');
	$StartYear = date("Y年", strtotime($BousaiStartDate));
	$BousaiStartDate = date("n月j日", strtotime($BousaiStartDate));

	$BousaiEndDate2 = date_create($BousaiEndDate);
	$w3 = (int)date_format($BousaiEndDate2, 'w');
	$BousaiEndDate = date("n月j日", strtotime($BousaiEndDate));
}

$haifudate = date("Y-n-d");
$myBukken->HaifuDownloadDate = $haifudate;

if (!$myBukken->executeUpdate()) {
	trigger_error("executeUpdate(Bukken) Failed.", E_USER_ERROR);
} else {
}

unset($myBukken);


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

$spreadsheet = $reader->load('./template/bousai/' . $editBukkenCD . '.xlsx'); //template.xlsx 読込
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue("X5", $Kitizitsu);

$sheet->setCellValue('M29', $BousaiStartDate . "(" . $week[$w2] . ")");
$sheet->setCellValue("J29", $StartYear);

$sheet->setCellValue("J32", $BousaiKojiTime);

if ($BousaiEndDate && $BousaiStartDate != $BousaiEndDate) {
	$sheet->setCellValue("V29", "～");
	$sheet->setCellValue("X29", $BousaiEndDate . "(" . $week[$w3] . ")");
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
