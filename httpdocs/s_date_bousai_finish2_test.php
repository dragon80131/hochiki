<?php
include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
include_once _CLS_DIR . "SPUSKojiNittei.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 認証動作
########################################################
if ($rKey) {
	$rKey  = SPFWParameter::getValues("rKey");
	$myUser = new User($myDB);

	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS);

	$UserCD 		= $myUser->UserCD;
	$ID 			= $myUser->ID;
	$MyShozokuCD	= $myUser->Extra1; #所属支店CD

	unset($myUser);
}
########################################################
# 値取得
########################################################

$editBukkenCD  	= SPFWParameter::getValues("editBukkenCD");
$work  			= SPFWParameter::getValues("work");

$wSagyoDate     = SPFWParameter::getValues("wSagyoDate");
$wSagyoDate2 = SPFWParameter::getValues("wSagyoDate2");
$wSagyoTime     = SPFWParameter::getValues("wSagyoTime");

$wNumberBuilding = SPFWParameter::getValues("wNumberBuilding");
$wRoomNo        = SPFWParameter::getValues("wRoomNo");
$wSagyoDateKobetsu 	   = SPFWParameter::getValues("wSagyoDateKobetsu");
$wSagyoTimeKobetsu 	   = SPFWParameter::getValues("wSagyoTimeKobetsu");
$wSagyoDates     = SPFWParameter::getValues("wSagyoDates");

$BousaiStartDate = SPFWParameter::getValues("BousaiStartDate");
$BousaiEndDate  = SPFWParameter::getValues("BousaiEndDate");

$BousaiKojiTime = SPFWParameter::getValues("BousaiKojiTime");
########################################################
# 登録処理
########################################################
if ($work == 1) { #新規、修正

	########################################################
	# 物件情報
	########################################################

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect(" BukkenCD = '" . $editBukkenCD . "' AND MukouFlg = FALSE", "")) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	$BukkenName = $myBukken->BukkenName;
	$TantoCD1 = $myBukken->TantoCD1;
	$TantoCD2 = $myBukken->TantoCD2;
	if ($TantoCD1) {
		$myUser = new User($myDB);
		if (!$myUser->executeSelect("UserCD = " . $TantoCD1 . " AND MukouFlg = FALSE", "") || $myUser->RecCnt != 1) {
			trigger_error("Getting Bukken Failed.", E_USER_ERROR);
		}
		$TantoCD1Address1 = $myUser->Address1;
		unset($myUser);
	}
	if ($TantoCD2) {
		$myUser = new User($myDB);
		if (!$myUser->executeSelect("UserCD = " . $TantoCD2 . " AND MukouFlg = FALSE", "") || $myUser->RecCnt != 1) {
			trigger_error("Getting Bukken Failed.", E_USER_ERROR);
		}
		$TantoCD2Address1 = $myUser->Address1;
		unset($myUser);
	}

	//防火日程登録済みステータス
	$myBukken->BousaiStatus = "2";


	$myBukken->BousaiStartDate = $BousaiStartDate;
	$myBukken->BousaiEndDate = $BousaiEndDate;

	$myBukken->BousaiKojiTime = $BousaiKojiTime;

	$myBukken->BousaiLastUpdater = $UserCD;
	$myBukken->BousaiLastUpdated = date("Y/m/d H:i:s");

	if (!$myBukken->executeUpdate()) {
		trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
	}
	unset($myBukken);

	SPFWTemplate::dropValue('work');
	// $URL = _MAIN_URL . "s_date_finish.php?rKey=" . $rKey . "&editBukkenCD=" . $editBukkenCD . "&status=end";

	mb_language("Japanese");
	mb_internal_encoding("UTF-8");

	// $to .= "matsugami-c@nespe.com,";
	if ($TantoCD1Address1) {
		$to .= $TantoCD1Address1 . ",";
	}
	if ($TantoCD2Address1) {
		$to .= $TantoCD2Address1;
	}

	$title = "【点検システム】" . $BukkenName . "防火実施日程登録通知";
	$content = $BukkenName . "の防火実施日程が登録されました\r\nシステムにログインし、内容をご確認ください。\r\n";
	$content .= "\r\nまた、お知らせ資料・QRコードのダウンロードが可能です。\r\n";
	$content .= "用途に応じてご活用ください。\r\n";
	$content .= "-------------------------------------------\r\n";
	$content .= "ダイア-点検進捗管理システム-\r\n";
	$content .= "-------------------------------------------\r\n";
	$content .= "https://app5.489501.jp/kotei/login_form.php";
	$headers = "From: info@nespe.com";

	mb_send_mail($to, $title, $content, $headers);
} else {
	// リダイレクトで飛んできた場合
	$status  = SPFWParameter::getValues("status");
	if ($status == "end") {

		$IfOK = TRUE;

		$myBukken = new Bukken($myDB);

		if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1) {
			trigger_error("Getting myBukken Failed.", E_USER_ERROR);
		}

		$BukkenName = $myBukken->BukkenName;
		unset($myBukken);
	}
}

########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_date_bousai_finish2.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
