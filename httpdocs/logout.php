<?php
	include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";

	########################################################
	# 機能チェック
	########################################################

	if (!$LoginFlg) {
		showSorryPage(_ILLEGAL_ACCESS);
		exit;
	}

	########################################################
	# 正規アクセスチェック
	########################################################

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$editBuildingCD = SPFWParameter::getValues('editBuildingCD');

	########################################################
	# アクティブ会員かどうかの判定
	########################################################

	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login.php';
		header('Location: ' . $URL);
		exit;
	}

	$myUser->RegistKey = $myUser->getNewKey();

	if (!$myUser->executeUpdate())
		trigger_error("executeUpdate(myUser) Failed.", E_USER_ERROR);

	########################################################
	# コンテンツ表示
	########################################################

	$IfLogin = "TRUE";

	$CNT_FILE = "logout.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
