<?php
$isAdminMode = TRUE;
header('Content-Type: application/json; charset=UTF-8');

include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";
include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once "./include/bukken_alert.php";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection) {
	echo json_encode(array('ok' => false));
	exit;
}

$rKey = SPFWParameter::getValues('rKey');
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$markAll = SPFWParameter::getValues('markAll');
$myUser = authenticateKanriUserForAlert($myDB, $rKey);
if (!$myUser) {
	echo json_encode(array('ok' => false));
	exit;
}

if ($markAll) {
	markAllBukkenAlertsRead(
		$myDB,
		$myUser->UserCD,
		$myUser->UserKbn,
		$myUser->ClientCD,
		$myUser->UserType,
		$myUser->BrancheCD,
		$myUser->GyosyaCD
	);
} else if ($editBukkenCD) {
	markBukkenAlertRead($myDB, $myUser->UserCD, $editBukkenCD);
}
unset($myUser);

echo json_encode(array('ok' => true));
