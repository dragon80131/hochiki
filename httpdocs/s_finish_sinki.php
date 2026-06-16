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
include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPUSGyosya.cls";
include_once _CLS_DIR . "SPUSSchedule.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$newBukkenCD = SPFWParameter::getValues("newBukkenCD");
	$editBukkenCD = SPFWParameter::getValues("editBukkenCD");
	$rKey = SPFWParameter::getValues("rKey");

if ($newBukkenCD) {
	$editBukkenCD = $newBukkenCD;
}
########################################################
# 認証動作
########################################################
$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS);

$UserCD = $myUser->UserCD;
$ID = $myUser->ID;
$ClientCD = $myUser->ClientCD;

unset($myUser);

// if (preg_match('/nespe/', $ID)) {
// 	$q = SPFWParameter::getValues("q");
// 	$Extra5 = $q;
// }

// $myGyosya = new Gyosya($myDB);
// if (!$myGyosya->executeSelect(" GyosyaCD =" . $Extra5, ""))
// 	trigger_error("Getting Gyosya Failed.", E_USER_ERROR);

// $GyosyaName = $myGyosya->GyosyaName;
########################################################
# 値受け取り
########################################################
$work = SPFWParameter::getValues('work');
$ins_ShozokuCD = SPFWParameter::getValues('ins_ShozokuCD');
$ins_MitumoriNo = SPFWParameter::getValues('ins_MitumoriNo');
$ins_BukkenName = SPFWParameter::getValues('ins_BukkenName');
$ins_Address = SPFWParameter::getValues('ins_Address');
$ins_Bunjyo = SPFWParameter::getValues('ins_Bunjyo');
$ins_Kosu = SPFWParameter::getValues('ins_Kosu');
$ins_Kosu = SPFWParameter::getValues('ins_Kosu');

// １．物件基本情報
$wTantoCD = SPFWParameter::getValues("wTantoCD");
$wBukkenName = SPFWParameter::getValues("wBukkenName");
$wBukkenName_Hurigana = SPFWParameter::getValues("wBukkenName_Hurigana");

$wKojiName = SPFWParameter::getValues("wKojiName");
$wKosu = SPFWParameter::getValues("wKosu");
$wKaidaka = SPFWParameter::getValues("wKaidaka");
$wKanriGaisya = SPFWParameter::getValues("wKanriGaisya");
$wOwner_name = SPFWParameter::getValues("wOwner_name");
$wBukkenMemo = SPFWParameter::getValues("wBukkenMemo");
$wKanriGaisyaTEL = SPFWParameter::getValues("wKanriGaisyaTEL");
$wKanriGaisyaTanto = SPFWParameter::getValues("wKanriGaisyaTanto");

$wAddress = SPFWParameter::getValues("wAddress");

// ２．工事基本情報
$wKojiShozokuName = SPFWParameter::getValues("wKojiShozokuName");
$wKojiShozokuTEL = SPFWParameter::getValues("wKojiShozokuTEL");
$wKojiTantoName = SPFWParameter::getValues("wKojiTantoName");
// 施工業者情報
$wGyosyaTantoCD1 = SPFWParameter::getValues("wGyosyaTantoCD1");
$wGyosyaTantoCD2 = SPFWParameter::getValues("wGyosyaTantoCD2");

$touroku_btn = SPFWParameter::getValues("touroku_btn"); // 登録ボタン押下
########################################################
# 登録処理
########################################################
if ($work == 1) { #新規
	// 値チェック
	$ins_BukkenName = replaceStr($ins_BukkenName); // 環境依存文字自動変換
	$ins_Kosu = mb_convert_kana($ins_Kosu, "n"); #戸数　半角数字に変換

	$ErrorString = array();

	if (count($ErrorString) > 0) {
		$IfError = true;
		for ($i = 0; $i < count($ErrorString); $i++) {
			$ErrorMessage .= $ErrorString[$i] . "<br>";
		}
		include_once("s_form_sinki.php");
		exit;
	}

	// 住所から緯度経度取得
	if ($ins_Address != "") {
		$LatLngArray = array();
		$LatLngArray = explode(',', strAddrToLatLng($ins_Address));
		$ins_Lat = $LatLngArray[0];
		$ins_Lng = $LatLngArray[1];
	}

	// 登録処理
	$myBukken = new Bukken($myDB);
	$myBukken->BukkenCD = -1;
	$myBukken->BukkenName = $wBukkenName;
	$myBukken->BukkenName_Hurigana = $wBukkenName_Hurigana;
	$myBukken->Address = $ins_Address;
	$myBukken->Lat = $ins_Lat;
	$myBukken->Lng = $ins_Lng;
	$myBukken->Kosu = $wKosu;
	$myBukken->Kaidaka = $wKaidaka;
	$myBukken->KanriGaisya = $wKanriGaisya;
	$myBukken->BukkenMemo = $wBukkenMemo;
	$myBukken->GyosyaCD = $Extra5;
	$myBukken->Address = $wAddress;
	$myBukken->Creator = $UserCD;
	$myBukken->Updater = $UserCD;
	$myBukken->ClientCD = $ClientCD;
	

	if (strpos($ID, 'nespe') === false) { // ネスペアカウントの場合はUpdaterにいれない
		$myBukken->Updater = $UserCD;
	}
	if (!$myBukken->executeUpdate()) {
		trigger_error("executeUpdate(Bukken) Failed.", E_USER_ERROR);
	} else {
		$IfOK = TRUE;
		SPFWTemplate::dropValue('work');
	}
	$newBukkenCD = $myBukken->BukkenCD;
	unset($myBukken);

	$URL = _MAIN_URL . "s_menu.php?rKey=" . $rKey . "&editBukkenCD=" . $newBukkenCD . "&status=end";
	header('Location: ' . $URL);
	exit;
} else {
	// リダイレクトで飛んできた場合
	/*
		$status  = SPFWParameter::getValues("status");
		if ($status == "end") {

			$IfOK = TRUE;

			$myBukken = new Bukken($myDB);

			//if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1){
			if (!$myBukken->executeSelect("BukkenCD = " . $newBukkenCD, "") || $myBukken->RecCnt != 1){

				trigger_error("Getting myBukken Failed.", E_USER_ERROR);
			}

			$BukkenName = $myBukken->BukkenName;

			unset($myBukken);
		}
		*/
}

SPFWTemplate::dropValue('wShozokuCD');
SPFWTemplate::dropValue('wMitumoriNo');
SPFWTemplate::dropValue('wBukkenName');
SPFWTemplate::dropValue('wAddress');
SPFWTemplate::dropValue('wBunjyo');
SPFWTemplate::dropValue('wKosu');

// 新規登録後、リダイレクトされたとき
if ($editBukkenCD) {

	$myBukken = new Bukken($myDB);
	if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD", "")) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	$BukkenName = $myBukken->BukkenName;
	unset($myBukken);

	$URL2 = _MAIN_URL . "s_menu.php?rKey=" . $rKey . "&editBukkenCD=" . $editBukkenCD;
	header('Location: ' . $URL2);
	exit;
}
#######################################################
# 住所 → 緯度/経度変換
#######################################################
function strAddrToLatLng($strAddr){
	$strRes = file_get_contents( #1
		'https://maps.google.com/maps/api/geocode/json'
			. '?address=' . urlencode(mb_convert_encoding($strAddr, 'UTF-8'))
			. '&key=AIzaSyDU2Y5I1vuHmZ5v1X2pWpgAxukSGQg-OUg'
	);
	$aryGeo = json_decode($strRes, TRUE);
	if (!isset($aryGeo['results'][0]))
		return '';

	$strLat = (string)$aryGeo['results'][0]['geometry']['location']['lat'];
	$strLng = (string)$aryGeo['results'][0]['geometry']['location']['lng'];
	return $strLat . ',' . $strLng;
}
#######################################################
# 環境依存文字変換関数
# 　引数　：変換前文字
# 　戻り値：変換後文字
# 　参考URL：http://wataame.sumomo.ne.jp/archives/1586
#######################################################
function replaceStr($str){
	$ret = "";
	$str2 = "";

	$search =  array('Ⅰ', 'Ⅱ', 'Ⅲ', 'Ⅳ', 'Ⅴ', 'Ⅵ', 'Ⅶ', 'Ⅷ', 'Ⅸ', 'Ⅹ', '①', '②', '③', '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '№', '㈲', '㈱');
	$replace = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', '(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)', '(10)', 'No.', '（有）', '（株）');

	// 文字列数ループする
	for ($i = 0; $i < mb_strlen($str); $i++) {
		//1文字ずつ検索し、置き換える
		$bit = mb_substr($str, $i, 1);
		$bit2 = str_replace($search, $replace, $bit);

		// 改行を除去する
		$bit2 = str_replace(array("\r\n", "\r", "\n"), "", $bit2);

		// 1文字ずつ確認しているのでくっつける
		$str2 .= $bit2;
	}
	$ret = $str2;
	return $ret;
}
########################################################
# コンテンツ表示
#######################################################
$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") . ".tpl";
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();
unset($myTemplate);
unset($myLog);