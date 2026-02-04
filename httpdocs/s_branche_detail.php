<?php
$isAdminMode = TRUE;
include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSBranche.cls";

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 入力チェック
	########################################################
	foreach($_POST as $key => $value){
		${"$key"} = SPFWParameter::getValues($key);
	}
	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}
	
	########################################################
	# 認証動作
	########################################################
	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}
	$ClientCD = $myUser->ClientCD;
	$UserKbn = $myUser->UserKbn;
	$loginID = $myUser->ID;
	$UserType = $myUser->UserType;

	unset($myUser);

	if (preg_match('/nespe/', $loginID)) {
		// $IfNespe = true;
		$IfBranche = false;
	} else {
		$IfNespe = false;
		$IfBranche = true;
	}
	if ($UserKbn != "3") { //幹事企業だった場合
		$IfNespe = true;
	}
	$IfDevelper = $UserKbn != 3;
	$IfWorker = $UserKbn == 3;

	// マスターメンテナンス
	$IfMasterMaintenance = false;
	if($IfDevelper && $UserType == '1'){
		$IfMasterMaintenance = true;
	}

	########################################################
	# 新規でない場合:URL抽出処理
	########################################################
	$IfRegist = false;
	$IfUpdate = true;
	if ($editBrancheCD > 0){
		$wTitle = "編集";

		$myBranche = new Branche($myDB);
		if (!$myBranche->executeSelect("BrancheCD = " . $editBrancheCD, "") || $myBranche->RecCnt == 0){
			trigger_error("Getting Branche Failed.", E_USER_ERROR);
		}

		$wBrancheCD = $myBranche->BrancheCD;
		$wBrancheName = $myBranche->BrancheName;
		$wBrancheNameKana = $myBranche->BrancheNameKana;
		$wBrancheTEL = $myBranche->BrancheTEL;

	} else {
		$wTitle = "新規登録";
		$IfRegist = true;
		$IfUpdate = false;
	}
	########################################################
	# コンテンツ表示
	########################################################

	SPFWTemplate::setValue("work");


	#$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") .".tpl";
	$CNT_FILE = "s_branche_detail.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
