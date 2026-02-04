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
include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPUSResidentsForm.cls";
include_once _CLS_DIR . "SPUSResultReport.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
if ($editBukkenCD) {
	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
	$TantoCD1 = $myBukken->TantoCD1;
	$TantoCD2 = $myBukken->TantoCD2;
	$BukkenName = $myBukken->BukkenName;

	$myBukken->Biko_forReport = SPFWParameter::getValues('Biko');

	$myBukken->ReportSubmitted = TRUE;

	if (!$myBukken->executeUpdate()) {
		trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
	}


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
}

foreach ($_POST as $key => $value) {
	${"$key"} = SPFWParameter::getValues($key);
}
########################################################
# 認証動作
########################################################
$myUser = new User($myDB);

if ($rKey) {

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	$UserCD 		= $myUser->UserCD;
} else {
	$UserCD = "";
}

unset($myUser);
########################################################
# お問い合わせ登録
########################################################
$myResultReport = new ResultReport($myDB);

if ($editBukkenCD) {
	if (!$myResultReport->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1) {
		// trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
} else {
	$id = -1;
}

$myResultReport->ZyushinDengen 	= $ZyushinDengen;
$myResultReport->RendoubanConfirm = $RendoubanConfirm;
$myResultReport->ZyushinConfirm = $ZyushinConfirm;
$myResultReport->HukuZyushinConfirm = $HukuZyushinConfirm;
$myResultReport->KeibiCompany = $KeibiCompany;
$myResultReport->SenyouKairoConfirm = $SenyouKairoConfirm;
$myResultReport->ResultReport = $ResultReport;
$myResultReport->Biko = $Biko;

$myResultReport->PumpConfirm = $PumpConfirm;
$myResultReport->AirPositionConfirm = $AirPositionConfirm;
$myResultReport->WaterKentiConfirm = $WaterKentiConfirm;
$myResultReport->WaterShingouConfirm = $WaterShingouConfirm;
$myResultReport->AirAtsuryokuConfirm = $AirAtsuryokuConfirm;
$myResultReport->HaisuiConfirm = $HaisuiConfirm;
$myResultReport->SeigyoDengenConfirm = $SeigyoDengenConfirm;
$myResultReport->SenyouKairoConfirmForSPSetsubi = $SenyouKairoConfirmForSPSetsubi;
$myResultReport->NozuruConfirm = $NozuruConfirm;
$myResultReport->HousyutsuConfirm = $HousyutsuConfirm;
$myResultReport->CleaningConfirm = $CleaningConfirm;
$myResultReport->HontaiDengenConfirm = $HontaiDengenConfirm;
$myResultReport->HizyouDengenForKasai = $HizyouDengenForKasai;
$myResultReport->TellKaisenConfirm = $TellKaisenConfirm;
$myResultReport->DoukanSetsuzoku = $DoukanSetsuzoku;
$myResultReport->HizyouDengenForGasuSetsubi = $HizyouDengenForGasuSetsubi;
$myResultReport->KidouSetsuzoku = $KidouSetsuzoku;

$myResultReport->BukkenCD = $editBukkenCD;
$myResultReport->Creator = $UserCD;

$myResultReport->Sagyousya = $Sagyousya;

if (!$myResultReport->executeUpdate()) {
	trigger_error("executeUpdate(myResultReport) Failed.", E_USER_ERROR);
}
unset($myResultReport);

#######################################################
# メール送信
#######################################################
mb_language("Japanese");
mb_internal_encoding("UTF-8");

if ($TantoCD1Address1 or $TantoCD2Address1) {

	if ($TantoCD1Address1) {
		$to .= $TantoCD1Address1 . ",";
	}
	if ($TantoCD2Address1) {
		$to .= $TantoCD2Address1;
	}

	$title = "【点検進捗管理】" . $BukkenName . "点検完了報告";
	$content = "\r\n";
	$content .= $BukkenName . "\r\n";
	$content .= "点検完了報告が登録されました。\r\n";
	$content .= "システムにログインし、物件情報をご確認ください。\r\n";
	$content .= "-------------------------------------------\r\n";
	$content .= "ダイア-点検進捗管理システム-\r\n";
	$content .= "-------------------------------------------\r\n";
	$content .= "URL:https://app5.489501.jp/kotei/login_form.php";
	$headers = "From: info@nespe.com";
	mb_send_mail($to, $title, $content, $headers);
}
########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_Result_Report_Finish.tpl";
$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();
unset($myTemplate);
unset($myLog);