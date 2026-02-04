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


	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 値取得
	########################################################
	$rKey 			= SPFWParameter::getValues("rKey");
	$FormCD = SPFWParameter::getValues('currentcd');


	if ($rKey) {

		$myUser = new User($myDB);
		if ($rKey == NULL)
			showSorryPage(_ILLEGAL_ACCESS);
	
		if (!$myUser->doAuthenticationByRegistKey($rKey))
			trigger_error("doAuthentication Failed.", E_USER_ERROR);
	
		if ($myUser->UserCD == -1)
			showSorryPage(_ILLEGAL_ACCESS);
	
		$UserCD 		= $myUser->UserCD;
	
		unset($myUser);
	}

	########################################################
	# 物件情報
	########################################################
	$myResidentsForm = new ResidentsForm($myDB);
	if (!$myResidentsForm->executeSelect(" FormCD = '" . $FormCD . "' AND MukouFlg = FALSE", "")) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
	$myResidentsForm->Creator = $UserCD;
	$myResidentsForm->MukouFlg = true;

	if (!$myResidentsForm->executeUpdate()){
		trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
	}
	unset($myResidentsForm);	

?>
