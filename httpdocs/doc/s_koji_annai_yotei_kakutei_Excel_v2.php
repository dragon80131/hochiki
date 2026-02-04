<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', "On");

/*
 * 予定案内・確定案内サンプル出力
 * s_koji_annai_yotei_kakutei_Excel_v2.php
 *
 * @created 2020.04.17
 * @updated 2021.03.24　齋藤
 *
*/

include_once "setting.properties";
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
include_once _CLS_DIR . "SPUSShiryo.cls";
include_once _CLS_DIR . "SPUSIraiRenkei.cls";
include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPUSFile.cls";
include_once  "../include/common.php";


// データベースコネクト
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
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

########################################################
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
	$KanriGaisya = $myBukken->KanriGaisya;
	$KanriGaisyaTEL = $myBukken->KanriGaisyaTEL;
}
unset($myBukken);

########################################################
# 工事情報抽出
########################################################

$myKoji = new Koji($myDB);

if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")) {
	trigger_error("Getting Koji Failed.", E_USER_ERROR);
}

if ($myKoji->RecCnt != 1) {
	#工事情報登録がまだ
	$ErrorString = array();
	$ErrorString[] = "工事情報を登録してください。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
	exit;
}

$KojiName = $myKoji->KojiName;
$wAnswer = $myKoji->Answer; // 専有部工事日時変更受付方法
$wWEBRecept = $myKoji->WEBRecept; // WEB受付
$SekoShutai = $myKoji->SekoShutai; //施工会社の主体？これでいい？



###資料上部に表示する会社名###
#DB1カラムに登録するため下記形状に変換して登録
#|1番目に表示する会社名|2番目|3番目|
$DispCompanyTop1 = SPFWParameter::getValues('DispCompanyTop1');
$DispCompanyTop2 = SPFWParameter::getValues('DispCompanyTop2');
$DispCompanyTop3 = SPFWParameter::getValues('DispCompanyTop3');

#入力値をDB格納
$DispCompanyTopArr = array($DispCompanyTop1, $DispCompanyTop2, $DispCompanyTop3);
$myKoji->DispCompanyTop = SPFWTools::encodePluralValue($DispCompanyTopArr); # 配列を文字列に変換

if (!$myKoji->executeUpdate()) {
	$ErrorString = array();
	$ErrorString[] = "工事情報のアップデートに失敗しました。";
	showAdminSorryPage($ErrorString);
}
unset($myKoji);


#######################################################
# Excelファイル生成#
########################################################
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Style\Style;

$reader = new XlsxReader();

$spreadsheet = $reader->load('./template/s_koji_annai_yotei_kakutei_Excel_v2.xlsx');

	$sheet = $spreadsheet->getActiveSheet();

######### 予定案内 #########
if ($wAnswer == "A.日時変更住戸のみ返答") {

	#シートを削除（removeSheetByIndexだと画像が消えてしまう）
	$sheet = $spreadsheet->getSheetByName('予定②_全住戸');
	$sheet->setSheetState('veryHidden');
	$sheet = $spreadsheet->getSheetByName('予定WEB②_全住戸');
	$sheet->setSheetState('veryHidden');

	if ($wWEBRecept == "1") { #WEB受付 有

		#シートを削除
		$sheet = $spreadsheet->getSheetByName('予定①_日変');
		$sheet->setSheetState('veryHidden');

		#シートを指定
		$sheet = $spreadsheet->getSheetByName('予定WEB①_日変');
	} else { #WEB受付 無

		#シートを削除
		$sheet = $spreadsheet->getSheetByName('予定WEB①_日変');
		$sheet->setSheetState('veryHidden');

		#シートを指定
		$sheet = $spreadsheet->getSheetByName('予定①_日変');
	}
	$sheet->setCellValue('A5', $KojiName . ' ご訪問予定日のお知らせ');
	$sheet->getStyle('A5')->getAlignment()->setShrinkToFit(true); //縮小して全体を表示
	$sheet->setCellValue('C2', $BukkenName);
	$sheet->setCellValue('L1', date('Y年') . "　〇月　〇日");
	$sheet->setCellValue('L2', '管理会社：'.$KanriGaisya);
	$sheet->setCellValue('L3', '施工会社：'.$SekoShutai);
	$sheet->setCellValue('L4', $DispCompanyTop3);
} else { #全住戸回答

	#シートを削除（removeSheetByIndexだと画像が消えてしまう）
	$sheet = $spreadsheet->getSheetByName('予定①_日変');
	$sheet->setSheetState('veryHidden');
	$sheet = $spreadsheet->getSheetByName('予定WEB①_日変');
	$sheet->setSheetState('veryHidden');

	if ($wWEBRecept == "1") { #WEB受付 有

		#シートを削除
		$sheet = $spreadsheet->getSheetByName('予定②_全住戸');
		$sheet->setSheetState('veryHidden');

		#シートを指定
		$sheet = $spreadsheet->getSheetByName('予定WEB②_全住戸');
	} else { #WEB受付 無

		#シートを削除
		$sheet = $spreadsheet->getSheetByName('予定WEB②_全住戸');
		$sheet->setSheetState('veryHidden');

		#シートを指定
		$sheet = $spreadsheet->getSheetByName('予定②_全住戸');
	}
	$sheet->setCellValue('A5', $KojiName . ' ご訪問予定日のお知らせ');
	$sheet->getStyle('A5')->getAlignment()->setShrinkToFit(true); //縮小して全体を表示
	$sheet->setCellValue('E2', $BukkenName);
	$sheet->setCellValue('M1', date('Y年') . "　〇月　〇日");
	$sheet->setCellValue('M2', '管理会社：'.$KanriGaisya);
	$sheet->setCellValue('M3', '施工会社：'.$SekoShutai);
	$sheet->setCellValue('M4', $DispCompanyTop3);
}
# セルの選択をA1に設定
$sheet->setSelectedCells('A1');

######### 予定案内 #########


######### 確定案内 #########

$sheet = $spreadsheet->getSheetByName('確定');

$sheet->setCellValue('A2', $BukkenName);
$sheet->setCellValue('A5', $KojiName . ' ご訪問予定日のお知らせ');
$sheet->getStyle('A5')->getAlignment()->setShrinkToFit(true); //縮小して全体を表示
$sheet->setCellValue('J1', date('Y年') . "　〇月　〇日");
$sheet->setCellValue('J2', '管理会社：'.$KanriGaisya);
$sheet->setCellValue('J3', '施工会社：'.$SekoShutai);
$sheet->setCellValue('J4', $DispCompanyTop3);
# セルの選択をA1に設定
$sheet->setSelectedCells('A1');

######### 確定案内 #########

$spreadsheet->setActiveSheetIndex(0);

//ダウンロード用
//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
header("Content-Description: File Transfer");
$wFileName = "予定・確定サンプル_" . $BukkenName . "_" . date('YmdHis') . ".xlsx";
$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

header("Content-Disposition: attachment; filename=" . $wFileName);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
ob_end_clean(); //バッファ消去

$writer = new XlsxWriter($spreadsheet);
$writer->save('php://output');

?>
