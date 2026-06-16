<?php
session_start();
include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPUSResidentsForm.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');

$RoomNo = SPFWParameter::getValues('RoomNo');
$Name = SPFWParameter::getValues('Name');
$TEL = SPFWParameter::getValues('TEL');
$Contents = SPFWParameter::getValues('Contents');
$IsInRoom = SPFWParameter::getValues('IsInRoom');
$DemandDate = SPFWParameter::getValues('DemandDate');
$AMPM = SPFWParameter::getValues('ampm');
$Dates = SPFWParameter::getValues('dates');
$MailAddress = SPFWParameter::getValues('MailAddress');

$ToName = SPFWParameter::getValues('ToName');

$token = SPFWParameter::getValues('token');

//リロード対策フラグセッションから取得
$reload = $_SESSION['reload'];

$session_token = $_SESSION['token'];
########################################################
# 認証動作
########################################################

$myUser = new User($myDB);

if ($rKey) {

	// if ($rKey == NULL)
	// 	showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	// if ($myUser->UserCD == -1)
	// 	showSorryPage(_ILLEGAL_ACCESS);
	$UserCD 		= $myUser->UserCD;
} else {
	$UserCD = "";
}

unset($myUser);

########################################################
# お問い合わせ登録
########################################################
if(isset($token) && $token == $session_token){
	$tokenOK = true;
	if (!$reload) {
		$myResidentsForm = new ResidentsForm($myDB);

		if ($ToName) {
			$RoomNo = $ToName . "-" . $RoomNo;
		}

		$myResidentsForm->RoomNo 			= $RoomNo;
		$myResidentsForm->Name 				= $Name;
		$myResidentsForm->TEL 				= $TEL;
		$myResidentsForm->Contents 			= $Contents;
		$myResidentsForm->BukkenCD 			= $editBukkenCD;
		$myResidentsForm->Creator           = $UserCD;
		$myResidentsForm->IsInRoom           = $IsInRoom;
		$myResidentsForm->AMPM 				= $AMPM;
		$myResidentsForm->Dates = $Dates;
		$myResidentsForm->TaioLog = $Contents;
		$myResidentsForm->MailAddress = $MailAddress;

		$myResidentsForm->DemandDate = $DemandDate;
		if ($DemandDate) {
			$IfDemandDate = true;
		}


		if (!$myResidentsForm->executeUpdate()) {
			trigger_error("executeUpdate(myResidentsForm) Failed.", E_USER_ERROR);
		}
		$_SESSION['reload'] = true;
		unset($_SESSION['token']);
		unset($session_token);
		unset($myResidentsForm);
	}
}
########################################################
# お問い合わせ登録
########################################################
$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}

$TantoCD1 = $myBukken->TantoCD1;
$TantoCD2 = $myBukken->TantoCD2;
$BukkenName = $myBukken->BukkenName;
unset($myBukken);

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

#######################################################
# メール送信
#######################################################
if (!$reload && $tokenOK) {
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	// $to = "matsugami-c@nespe.com,";

	if ($TantoCD1Address1 or $TantoCD2Address1) {

		if ($TantoCD1Address1) {
			$to .= $TantoCD1Address1 . ",";
		}
		if ($TantoCD2Address1) {
			$to .= $TantoCD2Address1;
		}

		$title = "【点検システム】" . $BukkenName . "日程お問い合わせ";
		$content = "物件名：" . $BukkenName . "\r\n" . "部屋番号：" . $RoomNo . "\r\n" . "名前：" . $Name . "\r\n" . "連絡先:" . $TEL . "\r\n" . "メールアドレス:" . $MailAddress . "\r\n" . "日程について:" . $DemandDate . "\r\n" . "対応ログ:" . $Contents;

		if ($IsInRoom == 2) {
			$content .= "\r\n在宅不可";
			$IfnotZaitaku = true;
		}
		$headers = "From: info@nespe.com";

		mb_send_mail($to, $title, $content, $headers);
	}

	if ($MailAddress) {

		$toResident = $MailAddress;

		$title = "【点検システム】" . $BukkenName . "日程お問い合わせ";
		$content = "以下の内容を受付けました。\r\n" . "物件名：" . $BukkenName . "\r\n" . "部屋番号：" . $RoomNo . "\r\n" . "名前：" . $Name . "\r\n" . "連絡先:" . $TEL . "\r\n" . "メールアドレス:" . $MailAddress . "\r\n" . "日程について:" . $DemandDate . "\r\n" . "対応ログ:" . $Contents;

		if ($IsInRoom == 2) {
			$content .= "\r\n在宅不可";
			$IfnotZaitaku = true;
		}

		// ---------------------------------------
		// ※本メールは送信専用のメールアドレスです。ご返信いただいても回答いたしかねますので、あらかじめご了承ください。
		// お問い合わせは下記お問い合わせ先にて承っております。

		// R. K. I 設備保全株式会社
		// 9時30分～17時(土日祝日を除く)
		// TEL：03-6809-4100
		// ---------------------------------------

		// add above content to mail
		// $content .= "\r\n" . "---------------------------------------" . "\r\n" . "※本メールは送信専用のメールアドレスです。ご返信いただいても回答いたしかねますので、あらかじめご了承ください。" . "\r\n" . "お問い合わせは下記お問い合わせ先にて承っております。" . "\r\n" . "R. K. I 設備保全株式会社" . "\r\n" . "9時30分～17時(土日祝日を除く)" . "\r\n" . "TEL：03-6809-4100" . "\r\n" . "---------------------------------------";
		// $content .= "\r\n" . "---------------------------------------" . "\r\n" . "※本メールは送信専用のメールアドレスです。ご返信いただいても回答いたしかねますので、あらかじめご了承ください。" . "\r\n" . "お問い合わせは下記お問い合わせ先にて承っております。" . "\r\n" . "R. K. I 設備保全株式会社" . "\r\n" . "9時30分～17時(土日祝日を除く)" . "\r\n" . "TEL：03-6409-5581" . "\r\n" . "---------------------------------------";
		$content .= "\r\n" . "---------------------------------------" . "\r\n" . "※本メールは送信専用のメールアドレスです。ご返信いただいても回答いたしかねますので、あらかじめご了承ください。" . "\r\n" . "お問い合わせは下記お問い合わせ先にて承っております。" . "\r\n" . "R. K. I 設備保全株式会社" . "\r\n" . "9時30分～17時(土日祝日を除く)" . "\r\n" . "TEL：03-6809-5581" . "\r\n" . "---------------------------------------";

		$headers = "From: info@nespe.com";

		mb_send_mail($toResident, $title, $content, $headers);
	}
}
########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_Taio_Finish.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
