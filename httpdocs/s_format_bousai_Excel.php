<?php
// ini_set('error_reporting', E_ALL);
// //出力するPHPのエラーレベルを設定。
// ini_set('display_errors', "On");
// //PHPエラーの表示・非表示を設定。

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
include_once _CLS_DIR . "SPUSKanriCompany.cls";
include_once _CLS_DIR . "SPUSClient.cls";


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
#########################################################
# 値受取
#########################################################
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$FormatType = SPFWParameter::getValues('wFormatType');
$FormatType2 = SPFWParameter::getValues('wFormatType2');
$TenkenKind = SPFWParameter::getValues('TenkenKind');
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
	$BukkenName = $myBukken->BukkenName;
	if ($kind == "1") {
		$myBukken->FormatType = $FormatType;
	} else if ($kind == "2") {
		$myBukken->FormatType2 = $FormatType2;
	}
	if (!$myBukken->executeUpdate()) {
		$ErrorString = array();
		$ErrorString[] = "アップデートに失敗しました。";
		showAdminSorryPage($ErrorString);
	}
}
$BukkenName = $myBukken->BukkenName;
$KanriGaisya = $myBukken->KanriGaisya;
$KanriGaisyaTEL = $myBukken->KanriGaisyaTEL;
$ClientCD = $myBukken->ClientCD;

$week = array("日", "月", "火", "水", "木", "金", "土");
$Date 	= $myBukken->FirstKojiDate;
$DatetoCal = $Date;
$datetime = date_create($Date);
$w = (int)date_format($datetime, 'w');
$Date = date("Y年n月j日", strtotime($Date));

$LastKojiDate 	= $myBukken->LastKojiDate;
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
$UketsukeShimekiriDate = date("Y年n月d日", strtotime($myBukken->UketsukeShimekiriDate));
$w2 = (int)date_format($UketsukeShimekiridatetime, 'w');

$ExistTenkenKikan = $myBukken->ExistTenkenKikan;
$ExistTenkenKikan_Sougou = $myBukken->ExistTenkenKikan_Sougou;

$KikiTenkenKikan = $myBukken->KikiTenkenKikan;
$SougouTenkenKikan = $myBukken->SougouTenkenKikan;

$KanriCompanyCD = $myBukken->KanriCompanyCD;
$myKanriCompany = new KanriCompany($myDB);
if ($KanriCompanyCD) {
	if (!$myKanriCompany->executeSelect("KanriCompanyCD = " . $KanriCompanyCD . " AND MukouFlg = FALSE", "")) {
		trigger_error("Getting KanriCompany Failed.", E_USER_ERROR);
	}
	$KanriCompanyName = $myKanriCompany->KanriCompanyName;
}
unset($myKanriCompany);

$TantoCD1 = $myBukken->TantoCD1;
$TantoCD2 = $myBukken->TantoCD2;

$myUser = new User($myDB);
if (!$myUser->executeSelect(" UserCD = '" . $TantoCD1 . "' AND MukouFlg = FALSE", "")) {
	trigger_error("Getting User Failed.", E_USER_ERROR);
}
$Tanto1Name = $myUser->LastName;
unset($myUser);

$myUser = new User($myDB);
if (!$myUser->executeSelect(" UserCD = '" . $TantoCD2 . "' AND MukouFlg = FALSE", "")) {
	trigger_error("Getting User Failed.", E_USER_ERROR);
}
$Tanto2Name = $myUser->LastName;
unset($myUser);

if ($Tanto1Name and $Tanto2Name) {
	$TantoName = $Tanto1Name . "・" . $Tanto2Name . "";
} else if ($Tanto1Name) {
	$TantoName = $Tanto1Name;
} else if ($Tanto2Name) {
	$TantoName = $Tanto2Name;
} else {
	$TantoName = "";
}

//防災情報
$BousaiKojiTime = $myBukken->BousaiKojiTime;
$BousaiStartDate = $myBukken->BousaiStartDate;
$BousaiEndDate = $myBukken->BousaiEndDate;
$KanriCompanyBousaiCD = $myBukken->KanriCompanyBousaiCD;
$WorkPlace = $myBukken->WorkPlace;

$myKanriCompanyBousai = new KanriCompany($myDB);
if ($KanriCompanyBousaiCD) {
	if (!$myKanriCompanyBousai->executeSelect("KanriCompanyCD = " . $KanriCompanyBousaiCD . " AND MukouFlg = FALSE", "")) {
		trigger_error("Getting KanriCompany Failed.", E_USER_ERROR);
	}
	$KanriCompanyNameBousai = $myKanriCompanyBousai->KanriCompanyName;
}
unset($myKanriCompanyBousai);





unset($myBukken);

$myClient = new Client($myDB);
if (!$myClient->executeSelect(" ClientCD = '" . $ClientCD . "' AND MukouFlg = FALSE", "")) {
	trigger_error("Getting client Failed.", E_USER_ERROR);
}

$TEL = $myClient->TEL;
$FAX = $myClient->FAX;
$ClientName = $myClient->ClientName;

#######################################################
# 作業日程取得
######################################################
// $myListObject = new SPFWListObject($myDB);

// $sql = "SELECT ";
// $sql .= "Date, ";
// $sql .= "Time, ";
// $sql .= "BuildingNumber, ";
// $sql .= "RoomNumber";

// $myListObject->SelectSQL = $sql;

// $sql = " FROM tKojiNitteiF";
// $sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
// $sql .= " AND TenkenKind = " . $TenkenKind;
// $myListObject->Condition	= $sql;
// $myListObject->Order 		= "";
// $myListObject->Limit 		= "allpage";

// if (!($myListObject->GetList(1)))
// 	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

// $KojiNitteiLoop = $myListObject->Rows;
// for ($i = 0; $i < $KojiNitteiLoop; $i++) {

// 	$KobetsuDate[$i] 	= $myListObject->GetValue($i, 0);
// 	if ($i % 2 == 0) {

// 		if ($TenkenKind == "1") {
// 			$KobetsuTime[$i] = $KikiAMKojiTime;
// 		} else if ($TenkenKind == "2") {
// 			$KobetsuTime[$i] = $SougouAMKojiTime;
// 		} else {
// 		}
// 	} else {

// 		if ($TenkenKind == "1") {
// 			$KobetsuTime[$i] = $KikiPMKojiTime;
// 		} else if ($TenkenKind == "2") {
// 			$KobetsuTime[$i] = $SougouPMKojiTime;
// 		} else {
// 		}
// 	}

// 	$BuildingNumber[$i]	= $myListObject->GetValue($i, 2);
// 	$RoomNumber[$i]	= $myListObject->GetValue($i, 3);
// 	if ($RoomNumber[$i] == "") {
// 		$RoomNumber[$i] = "";
// 	}
// }
// unset($myListObject);


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

// $KanriCompanyPositionArray["temp01.xlsx"] = "T58";
// $TantoPositionArray["temp01.xlsx"] = "X64";
// $ClientPositionArray["temp01.xlsx"] = "X59";
// $TELPositionArray["temp01.xlsx"] = "X60";
// $FAXPositionArray["temp01.xlsx"] = "X61";
$tempfile = "temp_bousai.xlsx";

$reader = new XlsxReader();
$spreadsheet = $reader->load('./template/' . $tempfile); //template.xlsx 読込
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue("C6", $BukkenName);
$sheet->setCellValue("C9", $BukkenName . "　管理組合");
// $sheet->setCellValue("J32", $BousaiKojiTime);
$sheet->setCellValue("T48", $KanriCompanyNameBousai);
$sheet->setCellValue("J35", $WorkPlace);

// $sheet->setCellValue("I30", $BousaiStartDate);
// $sheet->setCellValue("M30", $BousaiStartDate);
// if ($BousaiEndDate) {
// 	$sheet->setCellValue("V30", "～");
// }
// $sheet->setCellValue("X30", $BousaiEndDate);

# 余白調整
$sheet->getPageMargins()->setTop(0);
$sheet->getPageMargins()->setBottom(0);
$sheet->getPageMargins()->setRight(0);
$sheet->getPageMargins()->setLeft(0);

#シート名変更
$sheet = $spreadsheet->getSheetByName('案内資料');

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
