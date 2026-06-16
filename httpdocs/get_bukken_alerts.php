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
	echo json_encode(array('count' => 0, 'items' => array()));
	exit;
}

$rKey = SPFWParameter::getValues('rKey');
$m = SPFWParameter::getValues('m');
$myUser = authenticateKanriUserForAlert($myDB, $rKey);
if (!$myUser) {
	echo json_encode(array('count' => 0, 'items' => array()));
	exit;
}

$items = getRecentBukkenAlerts(
	$myDB,
	$myUser->UserCD,
	$myUser->UserKbn,
	$myUser->ClientCD,
	$myUser->UserType,
	$myUser->BrancheCD,
	$myUser->GyosyaCD,
	5
);
$unreadCount = getUnreadBukkenAlertCount(
	$myDB,
	$myUser->UserCD,
	$myUser->UserKbn,
	$myUser->ClientCD,
	$myUser->UserType,
	$myUser->BrancheCD,
	$myUser->GyosyaCD
);
unset($myUser);

echo json_encode(array(
	'count' => $unreadCount,
	'items' => $items,
	'm' => $m,
), JSON_UNESCAPED_UNICODE);
