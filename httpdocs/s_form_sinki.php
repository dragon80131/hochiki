<?php

/* s_form_sinki.php
 *
 * 物件の新規登録のみを行う
 *
 * 2018.5.15
 */

include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSSiten.cls";
include_once _CLS_DIR . "SPUSGyosya.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSKoji.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

$maxno_op_nondisp = 0; // jquery オプション用
########################################################
# 値取得
########################################################
$rKey = SPFWParameter::getValues('rKey');
########################################################
# 認証動作
########################################################
$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS);

$wUserCD = $myUser->UserCD;
$MyZokusei = $myUser->Extra3; #管理ユーザ２一般ユーザ１
$MyEigyoshoCD = $myUser->Extra4; #営業所CD
$LastName = $myUser->LastName; #名前
$loginID = $myUser->ID; #ログインID
$ClientCD = $myUser->ClientCD;

if (preg_match('/nespe/', $loginID)) {
	$Ifnespe = "true";
}

unset($myUser);
########################################################
# 値受け取り
########################################################

#########################################################
#会社表示
#########################################################
// $myListObject = new SPFWListObject($myDB);

// $sql = "SELECT ";
// $sql .= "GyosyaName, ";
// $sql .= "GyosyaTEL, ";
// $sql .= "GyosyaCD ";
// $myListObject->SelectSQL = $sql;
// $sql = " FROM tGyosyaM";
// $sql .= " WHERE MukouFlg = FALSE";

// $myListObject->Condition = $sql;
// $myListObject->Order = "";
// $myListObject->Limit = "allpage";

// if (!($myListObject->GetList(1))) {
// 	trigger_error("Getting Gyosya Failed.", E_USER_ERROR);
// }
// if ($myListObject->Rows == 1) {
// 	$GyosyaName = $myListObject->GetValue(0, 0);
// 	$GyosyaTEL = $myListObject->GetValue(0, 1);
// 	$GyosyaCD = $myListObject->GetValue(0, 2);
// }
// unset($myListObject);
#########################################################
#ネスぺユーザーの時に動く
#########################################################
// if (preg_match('/nespe/', $loginID)) {

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "GyosyaName, ";
	$sql .= "GyosyaTEL, ";
	$sql .= "GyosyaCD ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tGyosyaM";
	$sql .= " WHERE MukouFlg = FALSE";
	$sql .= " AND ClientCD = ".$ClientCD;
	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1))) {
		trigger_error("Getting Gyosya Failed.", E_USER_ERROR);
	}
	$GyosyaLoop = $myListObject->Rows;
	for ($i = 0; $i < $GyosyaLoop; $i++) {
		$GyosyaName2[$i] = $myListObject->GetValue($i, 0);
		$GyosyaTEL2[$i] = $myListObject->GetValue($i, 1);
		$GyosyaCD2[$i] = $myListObject->GetValue($i, 2);

	}

	unset($myListObject);
// }


########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "s_form_sinki.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
