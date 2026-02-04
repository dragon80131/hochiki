<?php
	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";

	########################################################
	# パラメータチェック/値加工
	########################################################

	$Mode = SPFWParameter::getValues('m');
	$Keys = SPFWParameter::getValues('key');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	// $wClientCD = SPFWParameter::getValues('wClientCD');

	########################################################
	# 機能チェック
	########################################################

	#	if (!$LoginFlg && !$ChangeSecurityFlg) {
	#		showSorryPage(_ILLEGAL_ACCESS2);
	#		exit;
	#	}

	$pathinfo = pathinfo($_SERVER['HTTP_REFERER']);
	$filename = $pathinfo['basename'];
	if($filename == '219.117.215.118' || $filename == '192.168.98.125'){
		$wID = SPFWParameter::getValues('wID');
		$wPasswd = SPFWParameter::getValues('wPasswd');
		$Address = SPFWParameter::getValues('Address');
		$Units = SPFWParameter::getValues('Units');
		$Kaidaka = SPFWParameter::getValues('Kaidaka');
		$BukkenName = SPFWParameter::getValues('BukkenName');
		$CallistoBukkenCD = SPFWParameter::getValues('CallistoBukkenCD');
		$CallistoBukkenName_Hurigana = SPFWParameter::getValues('BukkenName_Hurigana');
		$ConstructionStartDate = SPFWParameter::getValues('ConstructionStartDate');

	}

	########################################################
	# コンテンツ表示
	########################################################

	SPFWTemplate::dropValue('wID');
	SPFWTemplate::dropValue('wPasswd');

	$CNT_FILE = "login_form.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();
	unset($myTemplate);
	unset($myLog);
?>
