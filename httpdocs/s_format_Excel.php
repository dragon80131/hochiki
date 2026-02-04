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
// include_once _CLS_DIR . "SPUSKanriCompany.cls";
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

echo "<br> ".__LINE__." FormatType :".$FormatType;
echo "<br> ".__LINE__." FormatType2 :".$FormatType2;
echo "<br> ".__LINE__." TenkenKind :".$TenkenKind;


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

		if ($TenkenKind == "1") {
			$KobetsuTime[$i] = $KikiAMKojiTime;
		} else if ($TenkenKind == "2") {
			$KobetsuTime[$i] = $SougouAMKojiTime;
		} else {
		}
	} else {

		if ($TenkenKind == "1") {
			$KobetsuTime[$i] = $KikiPMKojiTime;
		} else if ($TenkenKind == "2") {
			$KobetsuTime[$i] = $SougouPMKojiTime;
		} else {
		}
	}

	$BuildingNumber[$i]	= $myListObject->GetValue($i, 2);
	$RoomNumber[$i]	= $myListObject->GetValue($i, 3);
	if ($RoomNumber[$i] == "") {
		$RoomNumber[$i] = "";
	}
}
unset($myListObject);

######################################################
# QRコード生成
#####################################################
require './vendor/autoload.php';

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

$writer = new PngWriter();
$qrCode = QrCode::create('https://app5.489501.jp/kotei/s_Taio_Form.php?editBukkenCD=' . $editBukkenCD)
	->setEncoding(new Encoding('UTF-8'))
	->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
	->setSize(300)
	->setMargin(10)
	->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
	->setForegroundColor(new Color(0, 0, 0))
	->setBackgroundColor(new Color(255, 255, 255));

$result = $writer->write($qrCode);
$result->saveToFile(__DIR__ . '/qrcode.png');

#######################################################
# Excelファイル生成 #
//temp01.xlsx→全住戸入室（階指定なし）
// temp02.xlsx→一部入室_1日間
// temp03.xlsx→一部入室_2日間
// temp04.xlsx→一部入室_3日間
// temp05.xlsx→一部入室_4日間
// temp06.xlsx→一部入室_5日間
// temp07.xlsx→一部入室_1日（半日）
// temp08.xlsx→一部入室_2日（半日）
// temp09.xlsx→一部入室_3日（半日）
// temp10.xlsx→一部入室_4日（半日）
// temp11.xlsx→一部入室_5日（半日）
// temp12.xlsx→全住戸入室（階指定あり）_1日間
// temp13.xlsx→全住戸入室（階指定あり）_2日間
// temp14.xlsx→全住戸入室（階指定あり）_3日間
// temp15.xlsx→全住戸入室（階指定あり）_4日間
// temp16.xlsx→全住戸入室（階指定あり）_5日間
// temp17.xlsx→全住戸入室（階指定あり・半日）_1日間
// temp18.xlsx→全住戸入室（階指定あり・半日）_2日間
// temp19.xlsx→全住戸入室（階指定あり・半日）_3日間
// temp20.xlsx→全住戸入室（階指定あり・半日）_4日間
// temp21.xlsx→全住戸入室（階指定あり・半日）_5日間
// temp22.xlsx→一部入室_6日間
// temp23.xlsx→一部入室_6日（半日）
// temp24.xlsx→全住戸入室（階指定あり）_6日間
// temp25.xlsx→全住戸入室（階指定あり・半日）_6日間
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

//●●管理会社の位置情報
$KanriCompanyPositionArray["temp01.xlsx"] = "T58";
$KanriCompanyPositionArray["temp02.xlsx"] = "W62";
$KanriCompanyPositionArray["temp03.xlsx"] = "V69";
$KanriCompanyPositionArray["temp04.xlsx"] = "W74";
$KanriCompanyPositionArray["temp05.xlsx"] = "W81";
$KanriCompanyPositionArray["temp06.xlsx"] = "W86";
$KanriCompanyPositionArray["temp07.xlsx"] = "W59";
$KanriCompanyPositionArray["temp08.xlsx"] = "V66";
$KanriCompanyPositionArray["temp09.xlsx"] = "W71";
$KanriCompanyPositionArray["temp10.xlsx"] = "V78";
$KanriCompanyPositionArray["temp11.xlsx"] = "W83";
$KanriCompanyPositionArray["temp12.xlsx"] = "W62";
$KanriCompanyPositionArray["temp13.xlsx"] = "V69";
$KanriCompanyPositionArray["temp14.xlsx"] = "W74";
$KanriCompanyPositionArray["temp15.xlsx"] = "W81";
$KanriCompanyPositionArray["temp16.xlsx"] = "W86";
$KanriCompanyPositionArray["temp17.xlsx"] = "W59";
$KanriCompanyPositionArray["temp18.xlsx"] = "V66";
$KanriCompanyPositionArray["temp19.xlsx"] = "W71";
$KanriCompanyPositionArray["temp20.xlsx"] = "V78";
$KanriCompanyPositionArray["temp21.xlsx"] = "W83";
$KanriCompanyPositionArray["temp22.xlsx"] = "W92";
$KanriCompanyPositionArray["temp23.xlsx"] = "W89";
$KanriCompanyPositionArray["temp24.xlsx"] = "W92";
$KanriCompanyPositionArray["temp25.xlsx"] = "W89";

//受付担当者の位置情報
$TantoPositionArray["temp01.xlsx"] = "X64";
$TantoPositionArray["temp02.xlsx"] = "AA68";
$TantoPositionArray["temp03.xlsx"] = "Z75";
$TantoPositionArray["temp04.xlsx"] = "AA80";
$TantoPositionArray["temp05.xlsx"] = "AA87";
$TantoPositionArray["temp06.xlsx"] = "AA92";
$TantoPositionArray["temp07.xlsx"] = "AA65";
$TantoPositionArray["temp08.xlsx"] = "Z72";
$TantoPositionArray["temp09.xlsx"] = "AA77";
$TantoPositionArray["temp10.xlsx"] = "Z84";
$TantoPositionArray["temp11.xlsx"] = "AA89";
$TantoPositionArray["temp12.xlsx"] = "AA68";
$TantoPositionArray["temp13.xlsx"] = "Z75";
$TantoPositionArray["temp14.xlsx"] = "AA80";
$TantoPositionArray["temp15.xlsx"] = "AA87";
$TantoPositionArray["temp16.xlsx"] = "AA92";
$TantoPositionArray["temp17.xlsx"] = "AA65";
$TantoPositionArray["temp18.xlsx"] = "Z72";
$TantoPositionArray["temp19.xlsx"] = "AA77";
$TantoPositionArray["temp20.xlsx"] = "Z84";
$TantoPositionArray["temp21.xlsx"] = "AA89";
$TantoPositionArray["temp22.xlsx"] = "AA98";
$TantoPositionArray["temp23.xlsx"] = "AA95";
$TantoPositionArray["temp24.xlsx"] = "AA98";
$TantoPositionArray["temp25.xlsx"] = "AA95";

//協力会社の位置情報
$ClientPositionArray["temp01.xlsx"] = "X59";
$ClientPositionArray["temp02.xlsx"] = "AA63";
$ClientPositionArray["temp03.xlsx"] = "Z70";
$ClientPositionArray["temp04.xlsx"] = "AA75";
$ClientPositionArray["temp05.xlsx"] = "AA82";
$ClientPositionArray["temp06.xlsx"] = "AA87";
$ClientPositionArray["temp07.xlsx"] = "AA60";
$ClientPositionArray["temp08.xlsx"] = "Z67";
$ClientPositionArray["temp09.xlsx"] = "AA72";
$ClientPositionArray["temp10.xlsx"] = "Z79";
$ClientPositionArray["temp11.xlsx"] = "AA84";
$ClientPositionArray["temp12.xlsx"] = "AA63";
$ClientPositionArray["temp13.xlsx"] = "Z70";
$ClientPositionArray["temp14.xlsx"] = "AA75";
$ClientPositionArray["temp15.xlsx"] = "AA82";
$ClientPositionArray["temp16.xlsx"] = "AA87";
$ClientPositionArray["temp17.xlsx"] = "AA60";
$ClientPositionArray["temp18.xlsx"] = "Z67";
$ClientPositionArray["temp19.xlsx"] = "AA72";
$ClientPositionArray["temp20.xlsx"] = "Z79";
$ClientPositionArray["temp21.xlsx"] = "AA84";
$ClientPositionArray["temp22.xlsx"] = "AA93";
$ClientPositionArray["temp23.xlsx"] = "AA90";
$ClientPositionArray["temp24.xlsx"] = "AA93";
$ClientPositionArray["temp25.xlsx"] = "AA90";

//TELの位置情報
$TELPositionArray["temp01.xlsx"] = "X60";
$TELPositionArray["temp02.xlsx"] = "AA64";
$TELPositionArray["temp03.xlsx"] = "Z71";
$TELPositionArray["temp04.xlsx"] = "AA76";
$TELPositionArray["temp05.xlsx"] = "AA83";
$TELPositionArray["temp06.xlsx"] = "AA88";
$TELPositionArray["temp07.xlsx"] = "AA61";
$TELPositionArray["temp08.xlsx"] = "Z68";
$TELPositionArray["temp09.xlsx"] = "AA73";
$TELPositionArray["temp10.xlsx"] = "Z80";
$TELPositionArray["temp11.xlsx"] = "AA85";
$TELPositionArray["temp12.xlsx"] = "AA64";
$TELPositionArray["temp13.xlsx"] = "Z71";
$TELPositionArray["temp14.xlsx"] = "AA76";
$TELPositionArray["temp15.xlsx"] = "AA83";
$TELPositionArray["temp16.xlsx"] = "AA88";
$TELPositionArray["temp17.xlsx"] = "AA61";
$TELPositionArray["temp18.xlsx"] = "Z68";
$TELPositionArray["temp19.xlsx"] = "AA73";
$TELPositionArray["temp20.xlsx"] = "Z80";
$TELPositionArray["temp21.xlsx"] = "AA85";
$TELPositionArray["temp22.xlsx"] = "AA94";
$TELPositionArray["temp23.xlsx"] = "AA91";
$TELPositionArray["temp24.xlsx"] = "AA94";
$TELPositionArray["temp25.xlsx"] = "AA91";

//FAXの位置情報
$FAXPositionArray["temp01.xlsx"] = "X61";
$FAXPositionArray["temp02.xlsx"] = "AA65";
$FAXPositionArray["temp03.xlsx"] = "Z72";
$FAXPositionArray["temp04.xlsx"] = "AA77";
$FAXPositionArray["temp05.xlsx"] = "AA84";
$FAXPositionArray["temp06.xlsx"] = "AA89";
$FAXPositionArray["temp07.xlsx"] = "AA62";
$FAXPositionArray["temp08.xlsx"] = "Z69";
$FAXPositionArray["temp09.xlsx"] = "AA74";
$FAXPositionArray["temp10.xlsx"] = "Z81";
$FAXPositionArray["temp11.xlsx"] = "AA86";
$FAXPositionArray["temp12.xlsx"] = "AA65";
$FAXPositionArray["temp13.xlsx"] = "Z72";
$FAXPositionArray["temp14.xlsx"] = "AA77";
$FAXPositionArray["temp15.xlsx"] = "AA84";
$FAXPositionArray["temp16.xlsx"] = "AA89";
$FAXPositionArray["temp17.xlsx"] = "AA62";
$FAXPositionArray["temp18.xlsx"] = "Z69";
$FAXPositionArray["temp19.xlsx"] = "AA74";
$FAXPositionArray["temp20.xlsx"] = "Z81";
$FAXPositionArray["temp21.xlsx"] = "AA86";
$FAXPositionArray["temp22.xlsx"] = "AA95";
$FAXPositionArray["temp23.xlsx"] = "AA92";
$FAXPositionArray["temp24.xlsx"] = "AA95";
$FAXPositionArray["temp25.xlsx"] = "AA92";



if ($TenkenKind == 1) { //機器の時

	if ($ExistTenkenKikan == 1) { //一部入室時

		if ($KikiTenkenKikan == "1") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp02.xlsx";
			} else {
				$tempfile = "temp07.xlsx";
			}
		} else if ($KikiTenkenKikan == "2") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp03.xlsx";
			} else {
				$tempfile = "temp08.xlsx";
			}
		} else if ($KikiTenkenKikan == "3") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp04.xlsx";
			} else {
				$tempfile = "temp09.xlsx";
			}
		} else if ($KikiTenkenKikan == "4") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp05.xlsx";
			} else {
				$tempfile = "temp10.xlsx";
			}
		} else if ($KikiTenkenKikan == "5") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp06.xlsx";
			} else {
				$tempfile = "temp11.xlsx";
			}
		} else if ($KikiTenkenKikan == "6") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp22.xlsx";
			} else {
				$tempfile = "temp23.xlsx";
			}
		} else {
			$tempfile = "temp01.xlsx";
		}
	} else if ($ExistTenkenKikan == 2 || $ExistTenkenKikan == 4) { //全住戸と入室なし

		$tempfile = "temp01.xlsx";
	} else if ($ExistTenkenKikan == 3) { //全住戸(階数指定)

		if ($KikiTenkenKikan == "1") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp12.xlsx";
			} else {
				$tempfile = "temp17.xlsx";
			}
		} else if ($KikiTenkenKikan == "2") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp13.xlsx";
			} else {
				$tempfile = "temp18.xlsx";
			}
		} else if ($KikiTenkenKikan == "3") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp14.xlsx";
			} else {
				$tempfile = "temp19.xlsx";
			}
		} else if ($KikiTenkenKikan == "4") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp15.xlsx";
			} else {
				$tempfile = "temp20.xlsx";
			}
		} else if ($KikiTenkenKikan == "5") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp16.xlsx";
			} else {
				$tempfile = "temp21.xlsx";
			}
		} else if ($KikiTenkenKikan == "6") {
			if ($KikiAMKojiTime && $KikiPMKojiTime) {
				$tempfile = "temp24.xlsx";
			} else {
				$tempfile = "temp25.xlsx";
			}
		} else {
			$tempfile = "temp01.xlsx";
		}
	}
} else if ($TenkenKind == "2") { //総合の時

	if ($ExistTenkenKikan_Sougou == 1) { //一部入室時

		if ($SougouTenkenKikan == "1") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp02.xlsx";
			} else {
				$tempfile = "temp07.xlsx";
			}
		} else if ($SougouTenkenKikan == "2") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp03.xlsx";
			} else {
				$tempfile = "temp08.xlsx";
			}
		} else if ($SougouTenkenKikan == "3") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp04.xlsx";
			} else {
				$tempfile = "temp09.xlsx";
			}
		} else if ($SougouTenkenKikan == "4") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp05.xlsx";
			} else {
				$tempfile = "temp10.xlsx";
			}
		} else if ($SougouTenkenKikan == "5") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp06.xlsx";
			} else {
				$tempfile = "temp11.xlsx";
			}
		} else if ($SougouTenkenKikan == "6") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp22.xlsx";
			} else {
				$tempfile = "temp23.xlsx";
			}
		} else {
			$tempfile = "temp01.xlsx";
		}
	} else if ($ExistTenkenKikan_Sougou == 2 || $ExistTenkenKikan_Sougou == 4) { //全住戸と入室なし
		$tempfile = "temp01.xlsx";
	} else if ($ExistTenkenKikan_Sougou == 3) { //全住戸(階数指定)

		if ($SougouTenkenKikan == "1") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp12.xlsx";
			} else {
				$tempfile = "temp17.xlsx";
			}
		} else if ($SougouTenkenKikan == "2") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp13.xlsx";
			} else {
				$tempfile = "temp18.xlsx";
			}
		} else if ($SougouTenkenKikan == "3") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp14.xlsx";
			} else {
				$tempfile = "temp19.xlsx";
			}
		} else if ($SougouTenkenKikan == "4") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp15.xlsx";
			} else {
				$tempfile = "temp20.xlsx";
			}
		} else if ($SougouTenkenKikan == "5") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp16.xlsx";
			} else {
				$tempfile = "temp21.xlsx";
			}
		} else if ($SougouTenkenKikan == "6") {
			if ($SougouAMKojiTime && $SougouPMKojiTime) {
				$tempfile = "temp24.xlsx";
			} else {
				$tempfile = "temp25.xlsx";
			}
		} else {
			$tempfile = "temp01.xlsx";
		}
	}
} else {
	$tempfile = "temp01.xlsx";
}

$reader = new XlsxReader();
$spreadsheet = $reader->load('./template/' . $tempfile); //template.xlsx 読込
$sheet = $spreadsheet->getActiveSheet();


$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setPath('qrcode.png');		//元の画像パス
//QRコードの位置
if ($KikiTenkenKikan == 1 || $SougouTenkenKikan == 1) { //1日
	$drawing->setCoordinates('E61');		//追加するセル位置
} elseif ($KikiTenkenKikan == 2 || $SougouTenkenKikan == 2) { //2日
	$drawing->setCoordinates('E70');		//追加するセル位置
} elseif ($KikiTenkenKikan == 3 || $SougouTenkenKikan == 3) { //3日
	$drawing->setCoordinates('E75');		//追加するセル位置
} elseif ($KikiTenkenKikan == 4 || $SougouTenkenKikan == 4) { //4日
	$drawing->setCoordinates('E82');		//追加するセル位置
} elseif ($KikiTenkenKikan == 5 || $SougouTenkenKikan == 5) { //5日
	$drawing->setCoordinates('E87');		//追加するセル位置
} elseif ($KikiTenkenKikan == 6 || $SougouTenkenKikan == 6) { //6日
	$drawing->setCoordinates('E93');		//追加するセル位置
}
$drawing->setWidth(100);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

//複数行
if ($TenkenKind == 1) { //機器の時

	if ($ExistTenkenKikan == "1" or $ExistTenkenKikan == "3") {
		$DatetoCal2 = strtotime($DatetoCal);
		$StartPosition = 23;
		$StartPositionforKobetsuTime = 22;

		$k = 0;
		for ($j = 0; $j < $KojiNitteiLoop; $j++) {
			if ($KikiTenkenKikan == 1) { //点検期間1日
				if (!empty($KikiAMKojiTime) && !empty($KikiPMKojiTime)) { //終日
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('C' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('L' . $StartPosition, $RoomNumber[$j]);
						// $StartPosition = $StartPosition + 3;
						// $StartPositionforKobetsuTime = $StartPositionforKobetsuTime + 3;
					}
				} elseif (!empty($KikiAMKojiTime) && empty($KikiPMKojiTime)) {
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('C' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('L' . $StartPosition, $RoomNumber[$j]);
					}
					$j++;
				} elseif (empty($KikiAMKojiTime) && !empty($KikiPMKojiTime)) {
					$j++;
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('C' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('L' . $StartPosition, $RoomNumber[$j]);
					}
				}
			} else { //点検期間2日以上
				if (!empty($KikiAMKojiTime) && !empty($KikiPMKojiTime)) { //終日
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('I' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('P' . $StartPosition, $RoomNumber[$j]);
					}
				} elseif (!empty($KikiAMKojiTime) && empty($KikiPMKojiTime)) { //半日AM
					//偶数
					if ($j % 2 == 0) {
						if ($RoomNumber[$j]) {
							$sheet->setCellValue('I' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
							$sheet->setCellValue('P' . $StartPosition, $RoomNumber[$j]);
						}
					}
				} elseif (empty($KikiAMKojiTime) && !empty($KikiPMKojiTime)) { //半日PM
					if ($j == 0) {
						//最初のAMスキップさせる
						$j++;
					}
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('I' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('P' . $StartPosition, $RoomNumber[$j]);
					}
				}
			}
			$StartPosition = $StartPosition + 3;
			$StartPositionforKobetsuTime = $StartPositionforKobetsuTime + 3;
		}
	} else {

		$sheet->setCellValue('C22', $KikiAMKojiTime); //作業時間
		$sheet->setCellValue('R22', $KikiPMKojiTime); //作業時間
	}
} else if ($TenkenKind == "2") { //総合の時

	if ($ExistTenkenKikan_Sougou == "1" or $ExistTenkenKikan_Sougou == "3") { //複数行

		$DatetoCal2 = strtotime($DatetoCal);
		$StartPosition = 23;
		$StartPositionforKobetsuTime = 22;
		$k = 0;
		for ($j = 0; $j < $KojiNitteiLoop; $j++) {

			if ($SougouTenkenKikan == 1) { //点検期間1日
				if (!empty($SougouAMKojiTime) && !empty($SougouPMKojiTime)) { //終日
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('C' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('L' . $StartPosition, $RoomNumber[$j]);
					}
				} elseif (!empty($SougouAMKojiTime) && empty($SougouPMKojiTime)) { //半日AM
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('C' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('L' . $StartPosition, $RoomNumber[$j]);
					}
					$j++;
				} elseif (empty($SougouAMKojiTime) && !empty($SougouPMKojiTime)) { //半日PM
					$j++;
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('C' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('L' . $StartPosition, $RoomNumber[$j]);
					}
				}
			} else { //点検期間2日以上
				if (!empty($SougouAMKojiTime) && !empty($SougouPMKojiTime)) { //終日
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('I' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('P' . $StartPosition, $RoomNumber[$j]);
					}
				} elseif (!empty($SougouAMKojiTime) && empty($SougouPMKojiTime)) { //半日AM
					//偶数
					if ($j % 2 == 0) {
						if ($RoomNumber[$j]) {
							$sheet->setCellValue('I' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
							$sheet->setCellValue('P' . $StartPosition, $RoomNumber[$j]);
						}
					}
				} elseif (empty($SougouAMKojiTime) && !empty($SougouPMKojiTime)) { //半日PM
					if ($j == 0) {
						//最初のAMスキップさせる
						$j++;
					}
					if ($RoomNumber[$j]) {
						$sheet->setCellValue('I' . $StartPositionforKobetsuTime, $KobetsuTime[$j]);
						$sheet->setCellValue('P' . $StartPosition, $RoomNumber[$j]);
					}
				}
			}
			$StartPosition = $StartPosition + 3;
			$StartPositionforKobetsuTime = $StartPositionforKobetsuTime + 3;
		}
	} else {
		$sheet->setCellValue('C22', $SougouAMKojiTime); //作業時間
		$sheet->setCellValue('R22', $SougouPMKojiTime); //作業時間
	}
}

$sheet->setCellValue('A2', $BukkenName);

if ($KanriCompanyName) {
	if ($KanriCompanyPositionArray[$tempfile]) {
		$sheet->setCellValue($KanriCompanyPositionArray[$tempfile], $KanriCompanyName);
	}
}

if ($TantoPositionArray[$tempfile]) {

	$sheet->setCellValue($TantoPositionArray[$tempfile], $TantoName);
}

$sheet->setCellValue($TELPositionArray[$tempfile], $TEL);

// FAX削除
// $sheet->setCellValue($FAXPositionArray[$tempfile], $FAX);

$sheet->setCellValue($ClientPositionArray[$tempfile], $ClientName);



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
