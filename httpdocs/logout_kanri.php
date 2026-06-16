<?php
$isAdminMode = TRUE;
	include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";

	########################################################
	# 機能チェック
	########################################################

	if (!$LoginFlg) {
		showSorryPage(_ILLEGAL_ACCESS2);
		exit;
	}
	########################################################
	# 正規アクセスチェック
	########################################################

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$m = isset($_GET["m"])?$_GET["m"]:'';

	########################################################
	# アクティブ会員かどうかの判定
	########################################################

	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		if($m != '')
			$URL = _MAIN_URL . 'login_form.php?m=1';
		else
			$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	$myUser->RegistKey = $myUser->getNewKey();
	$wClientCD = $myUser->ClientCD;
	if (!$myUser->executeUpdate())
		trigger_error("executeUpdate(myUser) Failed.", E_USER_ERROR);

	$login_form_url = "login_form.php";
	if($m != '')
		$login_form_url = "login_form.php?m=1";

	########################################################
	# コンテンツ表示
	########################################################

	$IfLogin = "TRUE";

	$CNT_FILE = "logout_kanri.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
