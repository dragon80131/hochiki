<?php

	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
#	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
#	include_once _CLS_DIR . "SPFWListObject.cls";
#	include_once _CLS_DIR . "SPFWDate.cls";
#	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";



	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


	include_once _CLS_DIR . "SPUSSetting.cls";
	$mySetting = new Setting($myDB);

		if (!$mySetting->executeSelect(" MukouFlg = FALSE", "")){
			$ErrorString = array();
			$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
			$ErrorLoop = count($ErrorString);
			$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
			unset($myTemplate);
			exit;
		}

	unset($mySetting);




	########################################################
	# 認証動作
	########################################################

	$clsUser = new User($myDB);
	if (!$clsUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);




###20110731 Commentout Add  comment out = not display
	$IfModify = !$IfNew;
	$IfLogin = ($IfNew && $LoginFlg) ? TRUE : FALSE;
	$IfLogout = (!$IfNew && $LoginFlg) ? TRUE : FALSE;

	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "top.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();
	$myTemplate->convertTags();
	$myTemplate->outputTemplate();
	unset($myTemplate);
	unset($myLog);

?>
