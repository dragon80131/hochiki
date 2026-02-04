<?php
ini_set('error_reporting', E_ALL);
//出力するPHPのエラーレベルを設定。
ini_set('display_errors', "On");
//PHPエラーの表示・非表示を設定。

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
$weekarray = array("(日)", "(月)", "(火)", "(水)", "(木)", "(金)", "(土)");

#工事名称
$KojiName = $myKoji->KojiName;

#案内配布日
$AnnaiDate = $myKoji->AnnaiDate;
$AnnaiDate = new DateTime($AnnaiDate);
$AnnaiDateDisp = $AnnaiDate->format('Y年 n月 吉日');

$SekoShutai = $myKoji->SekoShutai;

$ConstTime = $myKoji->ConstTime;
$KojiShozokuName = $myKoji->KojiShozokuName;
$KojiTantoName = $myKoji->KojiTantoName;
$TantoTEL = $myKoji->KojiShozokuTEL;

$GyosyaData = getGyosyaData($myDB, $GyosyaCD);
$GyosyaName = $GyosyaData['GyosyaName'];
$ReceptionDate = $myKoji->ReceptionDate;

#共用部終了日の処理追加
$KyoyoStartDate = $myKoji->KyoyoStartDate;
$KyoyoEndDate = $myKoji->KyoyoEndDate;
if (!empty($KyoyoStartDate && $KyoyoEndDate)) {
	$KyoyoStartDate = date('Y年n月j日', strtotime($myKoji->KyoyoStartDate));
	$KyoyoStartDate .= $weekarray[date('w', strtotime($myKoji->KyoyoStartDate))];
	$KyoyoEndDate = date('Y年n月j日', strtotime($myKoji->KyoyoEndDate));
	$KyoyoEndDate .= $weekarray[date('w', strtotime($myKoji->KyoyoEndDate))];
} else {
	$KyoyoStartDate = "";
	$KyoyoEndDate = "";
}

#専有部終了日の処理追加
$SenyuStartDate = $myKoji->SenyuStartDate;
$SenyuEndDate = $myKoji->SenyuEndDate;
if (!empty($SenyuStartDate && $SenyuEndDate)) {
	$SenyuStartDate = date('Y年n月j日', strtotime($myKoji->SenyuStartDate));
	$SenyuStartDate .= $weekarray[date('w', strtotime($myKoji->SenyuStartDate))];
	$SenyuEndDate = date('Y年n月j日', strtotime($myKoji->SenyuEndDate));
	$SenyuEndDate .= $weekarray[date('w', strtotime($myKoji->SenyuEndDate))];
} else {
	$SenyuStartDate = "";
	$SenyuEndDate = "";
}

#全体工期の取得
if (empty($KyoyoStartDate)) { //共用部の工事日が登録されていない
	$ZentaiStartDate = date('Y年n月j日', strtotime($myKoji->SenyuStartDate));
	$ZentaiStartDate .= $weekarray[date('w', strtotime($myKoji->SenyuStartDate))];
	$ZentaiEndDate = date('Y年n月j日', strtotime($myKoji->SenyuEndDate));
	$ZentaiEndDate .= $weekarray[date('w', strtotime($myKoji->SenyuEndDate))];
} else { //$KyoyoStartDateに値がある（共用部分の工事がある）
	$ZentaiStartDate = date('Y年n月j日', strtotime($myKoji->KyoyoStartDate));
	$ZentaiStartDate .= $weekarray[date('w', strtotime($myKoji->KyoyoStartDate))];
	$ZentaiEndDate = date('Y年n月j日', strtotime($myKoji->SenyuEndDate));
	$ZentaiEndDate .= $weekarray[date('w', strtotime($myKoji->SenyuEndDate))];
}

#受付締切日
$ReceptionDate = date('Y年n月j日', strtotime($myKoji->ReceptionDate));
$ReceptionDate .= $weekarray[date('w', strtotime($myKoji->ReceptionDate))];

#オプションの取得

#オプションある場合
for ($i = 1; $i < 11; $i++) {
	if ($myKoji->{"OP" . $i . "DeviceCD"} || $myKoji->{"OPZiyuu" . $i}) {
		$IfOP = TRUE;
	}
}

if ($IfOP) { #OPがある場合
	# OPメニュー一覧
	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "DeviceCD, ";	#機器CD
	$sql .= "DeviceName, ";	#機器名
	$sql .= "Kataban, ";	#型番
	$sql .= "Category, ";	#カテゴリ
	$sql .= "OPUseKbn, ";	#OP使用区分
	$sql .= "OPUseDisp ";	#OP説明文
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tDeviceM ";
	$sql .= " WHERE MukouFlg = FALSE ";
	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Device List Failed.", E_USER_ERROR);

	$OPDeviceLoop = $myListObject->Rows;
	for ($i = 0; $i < $OPDeviceLoop; $i++) {
		$listOPDeviceCD[$i] = $myListObject->GetValue($i, 0);
		$listOPDeviceName[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 1);
		$listOPKataban[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 2);
		$listOPCategory[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 3); // 数字 Choice5Nameでカテゴリ名取得可能
		$listOPUseKbn[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 4); // 数字 Choice5Nameでカテゴリ名取得可能
		$listOPUseDisp[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 5); // 数字 Choice5Nameでカテゴリ名取得可能
	}
	unset($myListObject);
	$opcnt = 0; // 配列カウント
	$OPInfo = array();
	for ($i = 1; $i <= 10; $i++) {
		// https://qiita.com/mpyw/items/41230bec5c02142ae691
		$strcd = "OP" . $i . "DeviceCD";
		$strpr = "OPPrice" . $i;
		$strcdZyuu = "OPZiyuu" . $i;	#201905 ADD 自由記入の機器

		#自由入力の機器があればそちらが優先
		if ($myKoji->$strcdZyuu) {
			$OPInfo[$opcnt]["OPDeviceName"] = $myKoji->$strcdZyuu;
			$OPInfo[$opcnt]["OPUseKbn"] =  "Z";
			$OPPrice = $myKoji->$strpr; // OPPrice1 ～ OPPrice10
			if ($OPPrice > 0)  $OPPrice = number_format($OPPrice); // カンマ区切り
			$OPInfo[$opcnt]["OPPrice"] = $OPPrice;
			$opcnt += 1; // 配列カウント
			#通常の機器
		} elseif ($myKoji->$strcd) {
			$OPCD = $myKoji->$strcd; // OP1DeviceCD ～ OP10DeviceCD
			$OPPrice = $myKoji->$strpr; // OPPrice1 ～ OPPrice10
			if ($OPPrice > 0)  $OPPrice = number_format($OPPrice); // カンマ区切り

			$OPInfo[$opcnt]["OPDeviceName"] = $listOPDeviceName[$OPCD];
			$OPInfo[$opcnt]["OPCategory"] = $KIKICATEGORY[$listOPCategory[$OPCD]];
			$OPInfo[$opcnt]["OPCategoryNo"] = $listOPCategory[$OPCD];
			$OPInfo[$opcnt]["OPKataban"] = $listOPKataban[$OPCD];
			$OPInfo[$opcnt]["OPPrice"] = $OPPrice;
			$OPInfo[$opcnt]["OPUseKbn"] =  $listOPUseKbn[$OPCD];
			$OPInfo[$opcnt]["OPUseDisp"] = $listOPUseDisp[$OPCD];
			$opcnt += 1; // 配列カウント
		}
	}
}
unset($myKoji);

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

$spreadsheet = $reader->load('./template/s_koji_annai_Excel_Aihon.xlsx'); //template.xlsx 読込

######### 1_概要 #########

$sheet = $spreadsheet->getSheetByName('1_概要');

#行を変数に格納
$gyo_sheet1 = 0;

$sheet->setCellValue('A' . ($gyo_sheet1 + 2), $BukkenName . "にお住まいの皆様へ");
$sheet->setCellValue('AN' . ($gyo_sheet1 + 1), $AnnaiDateDisp); //工事資料配布日
$sheet->setCellValue('AN3', '施工会社：' . $SekoShutai); //施工会社の主体？

#【全体工期】
if ($ZentaiStartDate && $ZentaiEndDate) {
	$sheet->setCellValue('J' . ($gyo_sheet1 + 15), $ZentaiStartDate . '〜' . $ZentaiEndDate);
} else {
	$sheet->setCellValue('J' . ($gyo_sheet1 + 15), '全体工期の日程が登録されていません。');
}

#【共用部工事日程】
if ($KyoyoStartDate && $KyoyoEndDate) {
	$sheet->setCellValue('J' . ($gyo_sheet1 + 16), $KyoyoStartDate . '〜' . $KyoyoEndDate);
} else {
	$sheet->getRowDimension($gyo_sheet1 + 16)->setVisible(false); // 共用部がセットされていない場合は16行目を非表示に
	$sheet->setCellValue('C' . ($gyo_sheet1 + 16), '　共用部工事');
	$sheet->setCellValue('C' . ($gyo_sheet1 + 17), '　専有部工事');
}

#【部屋内工事工程】専有部工事
if ($SenyuStartDate && $SenyuEndDate) {
	$sheet->setCellValue('J' . ($gyo_sheet1 + 17), $SenyuStartDate . '〜' . $SenyuEndDate);
} else {
	$sheet->setCellValue('J' . ($gyo_sheet1 + 17), '専有部部工事の日程が登録されていません。');
}


#【工事お問い合わせ】
$sheet->setCellValue('C' . ($gyo_sheet1 + 30), '◆施工会社：' . $SekoShutai . '  担当：' . $KojiTantoName);

$sheet->getStyle('N' . ($gyo_sheet1 + 30))->getAlignment()->setShrinkToFit(true);

$sheet->setCellValue('D' . ($gyo_sheet1 + 31), '電話：' . $TantoTEL . '（9時〜17時30分　土日祝休み）');

// $sheet->getPageSetup()->setPrintArea('A:AN32'); //印刷範囲の設定
$sheet->setBreak(
	'A' . ($gyo_sheet1 + 32),
	\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW
); //改ページ



######### 2_工事内容 #########
$sheet = $spreadsheet->getSheetByName('2_工事内容');

#行を変数に格納
$gyo_sheet2 = 0;
if ($ConstTime) {
	$sheet->setCellValue('L' . ($gyo_sheet2 + 15), "「約" . $ConstTime . "分」"); // 14行目
}


######### 3_注意事項 #########
$sheet = $spreadsheet->getSheetByName('3_注意事項');

#行を変数に格納
$gyo_sheet3 = 0;

$sheet->setCellValue('D' . ($gyo_sheet3 + 5), "共用部工事開始（" . $KyoyoStartDate . "）よりインターホン設備の下記の機能がご使用いただけなくなります。");


######### 4_仮暗証番号操作方法 #########
$sheet = $spreadsheet->getSheetByName('4_仮暗証番号操作方法');

#行を変数に格納
$gyo_sheet4 = 0;

$sheet->setCellValue('G' . ($gyo_sheet4 + 5),  $ZentaiStartDate . '〜' . $ZentaiEndDate . " 工事完了まで");

######### 6_オプション機器のご案内 #########

$sheet = $spreadsheet->getSheetByName('6_オプション機器のご案内');

#行を変数に格納
$gyo_sheet6 = 0;

if (!$IfOP) { #もしオプションがなかったら
	for ($i = ($gyo_sheet6 + 1); $i < ($gyo_sheet6 + 104); $i++) {
		$sheet->getRowDimension($i)->setVisible(false); //セルを非表示
	}
	$sheet->setSheetState('veryHidden'); #シートを削除
} else {
	// オプションがある場合
	$sheet->setCellValue('K' . ($gyo_sheet6 + 85), $ReceptionDate); // 85行目　締め切り入力がない
	$sheet->setCellValue('K' . ($gyo_sheet6 + 88), $SekoShutai);
	$sheet->setCellValue('F' . ($gyo_sheet6 + 92),   $SekoShutai . '名義にて領収書を発行いたします。'); // 92行目


	// オプション機器情報
	$OP_COUNT = count($OPInfo);

	if ($OP_COUNT > 6) {
		$OP_COUNT = 6;

		#$ErrorString = array();
		#$ErrorString[] = "現在、オプション7つ以上が対応していません。<br>ご迷惑をおかけしますが、6つで登録して手修正お願いします。";
		#$ErrorLoop = count($ErrorString);
		#$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		#exit;
	}

	// オプション数によって、表示場所が変わるため、先に定義しておく
	if ($OP_COUNT == 1) {
		$op_posi[0]["title"] = "B5";
		$op_posi[0]["image"] = "B7";
		$op_posi[0]["body"]  = "B13";
		$op_posi[0]["price"] = "B16";
		$op_posi[0]["size"]  = "big";

		for ($tmp_i = ($gyo_sheet6 + 19); $tmp_i < ($gyo_sheet6 + 78); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
	} else if ($OP_COUNT == 2) {
		$sheet->setCellValue("B19", "オプション①");
		$op_posi[0]["title"] = "B20";
		$op_posi[0]["image"] = "B21";
		$op_posi[0]["body"]  = "B27";
		$op_posi[0]["price"] = "B30";
		$op_posi[0]["size"]  = "mid";
		$sheet->setCellValue("U19", "オプション②");
		$op_posi[1]["title"] = "U20";
		$op_posi[1]["image"] = "U21";
		$op_posi[1]["body"]  = "U27";
		$op_posi[1]["price"] = "U30";
		$op_posi[1]["size"]  = "mid";

		for ($tmp_i = ($gyo_sheet6 + 5); $tmp_i < ($gyo_sheet6 + 19); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
		for ($tmp_i = ($gyo_sheet6 + 33); $tmp_i < ($gyo_sheet6 + 78); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
	} else if ($OP_COUNT == 3) {
		$sheet->setCellValue("B33", "オプション①");
		$op_posi[0]["title"] = "B34";
		$op_posi[0]["image"] = "B35";
		$op_posi[0]["body"]  = "B41";
		$op_posi[0]["price"] = "B44";
		$op_posi[0]["size"]  = "small";
		$sheet->setCellValue("O33", "オプション②");
		$op_posi[1]["title"] = "O34";
		$op_posi[1]["image"] = "O35";
		$op_posi[1]["body"]  = "O41";
		$op_posi[1]["price"] = "O44";
		$op_posi[1]["size"]  = "small";
		$sheet->setCellValue("AB33", "オプション③");
		$op_posi[2]["title"] = "AB34";
		$op_posi[2]["image"] = "AB35";
		$op_posi[2]["body"]  = "AB41";
		$op_posi[2]["price"] = "AB44";
		$op_posi[2]["size"]  = "small";

		for ($tmp_i = ($gyo_sheet6 + 5); $tmp_i < ($gyo_sheet6 + 33); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
		for ($tmp_i = ($gyo_sheet6 + 47); $tmp_i < ($gyo_sheet6 + 78); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
	} else if ($OP_COUNT == 4) {
		$sheet->setCellValue("B19", "オプション①");
		$op_posi[0]["title"] = "B20";
		$op_posi[0]["image"] = "B21";
		$op_posi[0]["body"]  = "B27";
		$op_posi[0]["price"] = "B30";
		$op_posi[0]["size"]  = "mid";
		$sheet->setCellValue("U19", "オプション②");
		$op_posi[1]["title"] = "U20";
		$op_posi[1]["image"] = "U21";
		$op_posi[1]["body"]  = "U27";
		$op_posi[1]["price"] = "U30";
		$op_posi[1]["size"]  = "mid";
		$sheet->setCellValue("B47", "オプション③");
		$op_posi[2]["title"] = "B48";
		$op_posi[2]["image"] = "B49";
		$op_posi[2]["body"]  = "B55";
		$op_posi[2]["price"] = "B58";
		$op_posi[2]["size"]  = "mid";
		$sheet->setCellValue("U47", "オプション④");
		$op_posi[3]["title"] = "U48";
		$op_posi[3]["image"] = "U49";
		$op_posi[3]["body"]  = "U55";
		$op_posi[3]["price"] = "U58";
		$op_posi[3]["size"]  = "mid";

		for ($tmp_i = ($gyo_sheet6 + 5); $tmp_i < ($gyo_sheet6 + 19); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
		for ($tmp_i = ($gyo_sheet6 + 33); $tmp_i < ($gyo_sheet6 + 47); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
		for ($tmp_i = ($gyo_sheet6 + 61); $tmp_i < ($gyo_sheet6 + 78); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
	} else if ($OP_COUNT == 5) {
		$sheet->setCellValue("B33", "オプション①");
		$op_posi[0]["title"] = "B34";
		$op_posi[0]["image"] = "B35";
		$op_posi[0]["body"]  = "B41";
		$op_posi[0]["price"] = "B44";
		$op_posi[0]["size"]  = "small";
		$sheet->setCellValue("O33", "オプション②");
		$op_posi[1]["title"] = "O34";
		$op_posi[1]["image"] = "O35";
		$op_posi[1]["body"]  = "O41";
		$op_posi[1]["price"] = "O44";
		$op_posi[1]["size"]  = "small";
		$sheet->setCellValue("AB33", "オプション③");
		$op_posi[2]["title"] = "AB34";
		$op_posi[2]["image"] = "AB35";
		$op_posi[2]["body"]  = "AB41";
		$op_posi[2]["price"] = "AB44";
		$op_posi[2]["size"]  = "small";
		$sheet->setCellValue("B47", "オプション④");
		$op_posi[3]["title"] = "B48";
		$op_posi[3]["image"] = "B49";
		$op_posi[3]["body"]  = "B55";
		$op_posi[3]["price"] = "B58";
		$op_posi[3]["size"]  = "mid";
		$sheet->setCellValue("U47", "オプション⑤");
		$op_posi[4]["title"] = "U48";
		$op_posi[4]["image"] = "U49";
		$op_posi[4]["body"]  = "U55";
		$op_posi[4]["price"] = "U58";
		$op_posi[4]["size"]  = "mid";

		for ($tmp_i = ($gyo_sheet6 + 5); $tmp_i < ($gyo_sheet6 + 33); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
		for ($tmp_i = ($gyo_sheet6 + 61); $tmp_i < ($gyo_sheet6 + 78); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
	} else if ($OP_COUNT == 6) {
		$sheet->setCellValue("B33", "オプション①");
		$op_posi[0]["title"] = "B34";
		$op_posi[0]["image"] = "B35";
		$op_posi[0]["body"]  = "B41";
		$op_posi[0]["price"] = "B44";
		$op_posi[0]["size"]  = "small";
		$sheet->setCellValue("O33", "オプション②");
		$op_posi[1]["title"] = "O34";
		$op_posi[1]["image"] = "O35";
		$op_posi[1]["body"]  = "O41";
		$op_posi[1]["price"] = "O44";
		$op_posi[1]["size"]  = "small";
		$sheet->setCellValue("AB33", "オプション③");
		$op_posi[2]["title"] = "AB34";
		$op_posi[2]["image"] = "AB35";
		$op_posi[2]["body"]  = "AB41";
		$op_posi[2]["price"] = "AB44";
		$op_posi[2]["size"]  = "small";
		$sheet->setCellValue("B61", "オプション④");
		$op_posi[3]["title"] = "B62";
		$op_posi[3]["image"] = "B63";
		$op_posi[3]["body"]  = "B69";
		$op_posi[3]["price"] = "B72";
		$op_posi[3]["size"]  = "small";
		$sheet->setCellValue("O61", "オプション⑤");
		$op_posi[4]["title"] = "O62";
		$op_posi[4]["image"] = "O63";
		$op_posi[4]["body"]  = "O69";
		$op_posi[4]["price"] = "O72";
		$op_posi[4]["size"]  = "small";
		$sheet->setCellValue("AB61", "オプション⑥");
		$op_posi[5]["title"] = "AB62";
		$op_posi[5]["image"] = "AB63";
		$op_posi[5]["body"]  = "AB69";
		$op_posi[5]["price"] = "AB72";
		$op_posi[5]["size"]  = "small";

		for ($tmp_i = ($gyo_sheet6 + 5); $tmp_i < ($gyo_sheet6 + 33); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
		for ($tmp_i = ($gyo_sheet6 + 47); $tmp_i < ($gyo_sheet6 + 61); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
		for ($tmp_i = ($gyo_sheet6 + 75); $tmp_i < ($gyo_sheet6 + 78); $tmp_i++) {
			$sheet->getRowDimension($tmp_i)->setVisible(false);
		}
	}


	// オプション表示部分
	$i = 0;
	foreach ($OPInfo as $key => $val) {

		if ($i == 6) break; // オプション数は6こまでしか対応していない

		#オプション名・価格
		$sheet->setCellValue($op_posi[$i]["title"], $OPInfo[$i]["OPDeviceName"]);
		$sheet->setCellValue($op_posi[$i]["price"], "販売価格:" . $OPInfo[$i]["OPPrice"] . "円（税込）");

		#説明文
		$tmp_body = "";
		if (isset($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
			$tmp_body = implode("\n", $KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]]);
		}

		#カテゴリ・表示サイズごとの画像・（変更がある場合は説明文）
		$picfile = "";
		$offsetX = 0; // 玄関子機画像で使用
		if ($OPInfo[$i]["OPCategoryNo"] == "1" or $OPInfo[$i]["OPCategoryNo"] == "2") { // 1:居室親機 2:玄関子機（通話）（標準機器なのでOPで選ばれることはないはず）
			$picfile = ""; // 画像なし

		} else if ($OPInfo[$i]["OPCategoryNo"] == "3") { // 3:カメラ付玄関子機
			$picfile = $OPInfo[$i]["OPKataban"] . ".jpg"; // 型番のファイル名

			#玄関子機の画像が小さいため、セル位置を変更する
			$offsetX = 100; // offsetもセットしておく
			if ($op_posi[$i]["image"] == "B7") 		 $op_posi[$i]["image"] = "Q7";
			else if ($op_posi[$i]["image"] == "B21") $op_posi[$i]["image"] = "G21";
			else if ($op_posi[$i]["image"] == "U21") $op_posi[$i]["image"] = "Z21";
			else if ($op_posi[$i]["image"] == "B49") $op_posi[$i]["image"] = "G49";
			else if ($op_posi[$i]["image"] == "U49") $op_posi[$i]["image"] = "Z49";
		} else if ($OPInfo[$i]["OPCategoryNo"] == "4") { // 4:ワイヤレス増設親機

			if ($op_posi[$i]["size"] == "big") 		  $picfile = "4_wirelessset_1.png"; // サイズ大
			else if ($op_posi[$i]["size"] == "mid")   $picfile = "4_wirelessset_2.png"; // サイズ中
			else if ($op_posi[$i]["size"] == "small") $picfile = "4_wirelessset_3.png"; // サイズ小

			if ($op_posi[$i]["size"] == "small") {
				// 文字数が多いため、へらす
				$tmp_body = "別のお部屋にいても来客対応ができます。ワイヤレスなので配線工事は不要です。";
				$tmp_body .= "（インターホン親機から基地アンテナまでは有線になります。）";
			}
		} else if ($OPInfo[$i]["OPCategoryNo"] == "5") { // 5:ワイヤレスチャイム
			$picfile = ""; // 画像なし

			if (strpos($OPInfo[$i]["OPDeviceName"], '追加受信機')) { // ワイヤレスチャイム追加受信機の場合
				$tmp_body = $KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]][0]; #機器の説明だけ出力
			}
		} else if ($OPInfo[$i]["OPCategoryNo"] == "6") { // 6:受話器

			if ($op_posi[$i]["size"] == "big")	 $picfile = "6_jyuwaki_1.png"; // サイズ大
			if ($op_posi[$i]["size"] == "mid")	 $picfile = "6_jyuwaki_2.png"; // サイズ中
			if ($op_posi[$i]["size"] == "small") $picfile = "6_jyuwaki_3.png"; // サイズ小

		} else if ($OPInfo[$i]["OPCategoryNo"] == "7") { // 7:増設親機
			$picfile = ""; // 画像なし

		} else if ($OPInfo[$i]["OPCategoryNo"] == "8") { // 8:スマホ連動

			if ($op_posi[$i]["size"] == "big")	 $picfile = "8_sumaho_1.png"; // サイズ大
			if ($op_posi[$i]["size"] == "mid")	 $picfile = "8_sumaho_2.png"; // サイズ中
			if ($op_posi[$i]["size"] == "small") $picfile = "8_sumaho_3.png"; // サイズ小

		} else if ($OPInfo[$i]["OPCategoryNo"] == "9") { // 9:タグ

			$tmp_body = str_replace("標準数をお渡しします", "標準数（" . $wTagSuu . "本）をお渡しします", $tmp_body);

			if (strstr($OPInfo[$i]["OPDeviceName"], 'ノンタッチキーヘッド')) { // ノンタッチキーヘッド
				if ($op_posi[$i]["size"] == "big") 			$picfile = "9_nontouchkeyhead_1.png"; // サイズ大
				else if ($op_posi[$i]["size"] == "mid") 	$picfile = "9_nontouchkeyhead_2.png"; // サイズ中
				else if ($op_posi[$i]["size"] == "small") 	$picfile = "9_nontouchkeyhead_3.png"; // サイズ小
			} else { // ノンタッチタグ
				if ($op_posi[$i]["size"] == "big") 			$picfile = "9_nontouchtag_1.png"; // サイズ大
				else if ($op_posi[$i]["size"] == "mid") 	$picfile = "9_nontouchtag_2.png"; // サイズ中
				else if ($op_posi[$i]["size"] == "small") 	$picfile = "9_nontouchtag_3.png"; // サイズ小
			}
		}

		#画像
		$picpath = _DOCUMENT_ROOT . "images/kikipicdataAihon/" . $picfile;
		if (($picfile == "" or !file_exists($picpath))) { // 画像ファイルがない場合
			$picpath = _DOCUMENT_ROOT . "images/kikipicdataAihon/" . "0_NoImage.png";
		}
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setPath($picpath);
		$drawing->setHeight(153);
		$drawing->setOffsetX(2 + $offsetX);
		$drawing->setOffsetY(2);
		$drawing->setCoordinates($op_posi[$i]["image"]);
		$drawing->setWorksheet($sheet);

		#説明文
		$sheet->setCellValue($op_posi[$i]["body"], $tmp_body);

		$i += 1;
	}
}



$sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4); //印刷設定をA4にする
$sheet->getPageSetup()->setHorizontalCentered(true); //印刷の中央配置
$sheet->setSelectedCells('A1'); //セルの選択をA1に設定

# 余白調整
$sheet->getPageMargins()->setTop(0);
$sheet->getPageMargins()->setBottom(0);
$sheet->getPageMargins()->setRight(0);
$sheet->getPageMargins()->setLeft(0);

#シート名変更
// $sheet = $spreadsheet->getSheetByName('工事案内資料');
// $sheet = $spreadsheet->removeSheetByName('Sheet1');

//ダウンロード用
//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
header("Content-Description: File Transfer");
$wFileName = "工事案内_" . $BukkenName . "_" . date('YmdHis') . ".xlsx";
$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

header("Content-Disposition: attachment; filename=" . $wFileName);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
ob_end_clean(); //バッファ消去

$writer = new XlsxWriter($spreadsheet);
$writer->save('php://output');