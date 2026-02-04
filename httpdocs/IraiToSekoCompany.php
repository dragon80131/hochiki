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
	include_once _CLS_DIR . "SPUSGyosya.cls";


	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 値取得
	########################################################
	$rKey 			= SPFWParameter::getValues("rKey");
	$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');

	$RoomNo = SPFWParameter::getValues('RoomNo');
	$Name = SPFWParameter::getValues('Name');
	$TEL = SPFWParameter::getValues('TEL');
	$Contents = SPFWParameter::getValues('Contents');

	$TenkenKind = SPFWParameter::getValues('TenkenKind');
	########################################################
	# 物件情報
	########################################################
	$myBukken = new Bukken($myDB);
	if (!$myBukken->executeSelect(" BukkenCD = '" . $editBukkenCD . "' AND MukouFlg = FALSE", "")) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
	// $myBukken->KikiStatus = "1";
	$myBukken->KikiStatus = STATUS["依頼済"];
	$BukkenName = $myBukken->BukkenName;
	$GyosyaCD = $myBukken->GyosyaCD;
	if($TenkenKind == "1") {
		$myBukken->LatestIraiKojiKind = "1";
	}else if($TenkenKind == "2") {
		$myBukken->LatestIraiKojiKind = "2";
	}else{}

	if (!$myBukken->executeUpdate()){
		trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
	}
	unset($myBukken);	

	$myGyosya = new Gyosya($myDB);
	if (!$myGyosya->executeSelect(" GyosyaCD =" . $GyosyaCD, ""))
		trigger_error('Getting Gyosya Failed.', E_USER_ERROR);

	$GyosyaMail = $myGyosya->GyosyaMail;
	
	#######################################################
	# メール送信
	#######################################################
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	if($GyosyaMail){
		$to = $GyosyaMail;	
		$title = $BukkenName."の点検日程登録依頼";
		$content = "日程登録をお願い致します。URL:https://app5.489501.jp/kotei/s_date.php?editBukkenCD=".$editBukkenCD;
		$headers = "From: info@nespe.com";
		mb_send_mail($to, $title, $content,$headers);

	}
	echo "OK";

?>
