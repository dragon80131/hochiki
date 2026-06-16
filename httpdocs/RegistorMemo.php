<?php

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
// $editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');

$BukkenCD = SPFWParameter::getValues('BukkenCD');
$memo = SPFWParameter::getValues('memo');

########################################################
# 物件情報
########################################################
$myBukken = new Bukken($myDB);
if (!$myBukken->executeSelect(" BukkenCD = '" . $BukkenCD . "' AND MukouFlg = FALSE", "")) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}
$myBukken->Memo = $memo;

if (!$myBukken->executeUpdate()) {
	trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
}
unset($myBukken);
	########################################################
	# 認証動作
	########################################################
	// $myUser = new User($myDB);

	// if($rKey){

	// 	// if ($rKey == NULL)
	// 	// 	showSorryPage(_ILLEGAL_ACCESS);

	// 	if (!$myUser->doAuthenticationByRegistKey($rKey))
	// 		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	// 	// if ($myUser->UserCD == -1)
	// 	// 	showSorryPage(_ILLEGAL_ACCESS);
	// 	$UserCD 		= $myUser->UserCD;
	// 	// #	$Extra1 		= $myUser->EigyosyoCD ;#幹事企業拠点CD
	// 	// #	$MyZokusei 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
	// 	// #	$MyEigyoshoCD	= $myUser->GyosyaCD ;#協力業者CD

	// 	// $ClientCD 		= $myUser->ClientCD ;#幹事企業CD
	// 	// $EigyosyoCD 		= $myUser->EigyosyoCD ;#幹事企業支店・営業所CD
	// 	// $UserKbn 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
	// 	// $GyosyaCD	= $myUser->GyosyaCD ;#協力業者CD
	// }else{
	// 	$UserCD = "";
	// }
	// unset($myUser);
