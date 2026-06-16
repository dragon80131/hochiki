<?php
$isAdminMode = TRUE;
// ini_set('display_errors', "On");

include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";
include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPFWTools.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
$UserID 		= SPFWParameter::getValues('UserID');
$RefugeFlg 		= SPFWParameter::getValues('RefugeFlg');

########################################################
# 認証動作
########################################################
if ($rKey) {
	$myUser = new User($myDB);
	if ($rKey == NULL){
		echo 'Auth Failed.'; exit;
	}

	if (!$myUser->doAuthenticationByRegistKey($rKey)){
		echo 'Auth Failed.'; exit;
	}

	if ($myUser->UserCD == -1){
		echo 'Auth Failed.'; exit;
	}
}

$reserveUser = new User($myDB);
if($editBuildingCD){
	if (!$reserveUser->executeSelect(" MukouFlg = FALSE AND BukkenCD  = '".$editBukkenCD."' AND BuildingCD = '".$editBuildingCD."' AND ID = '".$UserID."'", "")) {
		return 'Getting User Failed.';
	}
}else{
	if (!$reserveUser->executeSelect(" MukouFlg = FALSE AND BukkenCD  = '".$editBukkenCD."' AND BuildingCD IS NULL AND ID = '".$UserID."'", "")) {
		return 'Getting User Failed.';
	}
}

$reserveUser->RefugeFlg 			= $RefugeFlg;
if (!$reserveUser->executeUpdate()) {
	trigger_error("Update Failed.", E_USER_ERROR);
}
echo 'success'; exit;