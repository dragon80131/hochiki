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

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 値取得
	########################################################
	$rKey 			= SPFWParameter::getValues("rKey");
	$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
	########################################################
	# 認証動作
	########################################################
	$myUser = new User($myDB);

	if($rKey){
		$IfrKey = true;
	}

	$myBukken = new Bukken($myDB);
	if (!$myBukken->executeSelect(" BukkenCD = '" . $editBukkenCD . "' AND MukouFlg = FALSE", "")) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	$UketsukeShimekiriDate = $myBukken->UketsukeShimekiriDate;

	$SougouTenkenMonth 	   = $myBukken->SougouTenkenMonth;
	$KikiTenkenMonth 	   = $myBukken->KikiTenkenMonth;

	$SougouAMKojiTime = $myBukken->SougouAMKojiTime;
	$SougouPMKojiTime = $myBukken->SougouPMKojiTime;
	$SougouTenkenKikan = $myBukken->SougouTenkenKikan;

	$KikiAMKojiTime = $myBukken->KikiAMKojiTime;
	$KikiPMKojiTime = $myBukken->KikiPMKojiTime;
	$KikiTenkenKikan = $myBukken->KikiTenkenKikan;

	$Date1 = $myBukken->Date1;
	$Date2 = $myBukken->Date2;
	$Date3 = $myBukken->Date3;
	$Date4 = $myBukken->Date4;
	$Date5 = $myBukken->Date5;
	$Date6 = $myBukken->Date6;

	$today = date("Y-m-d");
	if(!$rKey){

		if(strtotime($today) > strtotime($UketsukeShimekiriDate)){
			$URL = _MAIN_URL . "s_Taio_Exceed_UketsukeDate.php";
			header('Location: ' . $URL);
			exit;
		}else{}

	}

	// if ($rKey == NULL)
	// 	showSorryPage(_ILLEGAL_ACCESS);

	// if (!$myUser->doAuthenticationByRegistKey($rKey))
	// 	trigger_error("doAuthentication Failed.", E_USER_ERROR);

	// if ($myUser->UserCD == -1)
	// 	showSorryPage(_ILLEGAL_ACCESS);
	// $UserCD 		= $myUser->UserCD;
	// #	$Extra1 		= $myUser->EigyosyoCD ;#幹事企業拠点CD
	// #	$MyZokusei 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
	// #	$MyEigyoshoCD	= $myUser->GyosyaCD ;#協力業者CD

	// $ClientCD 		= $myUser->ClientCD ;#幹事企業CD
	// $EigyosyoCD 		= $myUser->EigyosyoCD ;#幹事企業支店・営業所CD
	// $UserKbn 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
	// $GyosyaCD	= $myUser->GyosyaCD ;#協力業者CD

	unset($myUser);

	###########################################################
	# 機器点検か総合点検を判断
	###########################################################
	$CurrentMonth = date('n');
	$date = array($SougouTenkenMonth, $KikiTenkenMonth);
	$num = 0;
	$result_date = "";
	while ($num < 13){
		$check_date = $CurrentMonth + $num;
		if($check_date > 12){
			$check_date = $check_date -12;
		}

		if($check_date == $SougouTenkenMonth){

			$Kikan = $SougouTenkenKikan;

			if($SougouAMKojiTime){

				$IfNextTenkenAM = true;
				$NextTenkenAM = $SougouAMKojiTime;

			}
			if($SougouPMKojiTime){

				$IfNextTenkenPM = true;
				$NextTenkenPM = $SougouPMKojiTime;

			}

			if((!$SougouAMKojiTime or !$SougouPMKojiTime)){

				$IfNextTenkenAM = false;
				$IfNextTenkenPM = false;

				$IfOnlyOne = true;
			}

		}else if($check_date == $KikiTenkenMonth){

			$Kikan = $KikiTenkenKikan;

			if($KikiAMKojiTime){

				$IfNextTenkenAM = true;
				$NextTenkenAM = $KikiAMKojiTime;
			}

			if($KikiPMKojiTime){

				$IfNextTenkenPM = true;
				$NextTenkenPM = $KikiPMKojiTime;

			}

			if(!$KikiAMKojiTime or !$KikiPMKojiTime){

				$IfNextTenkenAM = false;
				$IfNextTenkenPM = false;

				$IfOnlyOne = true;
			}

		}

		$index = array_search($check_date, $date);
		if($index !== FALSE){
			$result_date = $date[$index];
			break;
		}

		$num++;
	}

	if($Kikan){

		for($i = 0; $i < $Kikan; $i++){

			$k = $i + 1;

			if($NextTenkenAM){
				$Dates[] = ${"Date".$k}."　".$NextTenkenAM;
				$AMPM[] = "AM";
				$DatesData[] = ${"Date".$k};
			}

			if($NextTenkenPM){
				$Dates[] = ${"Date".$k}."　".$NextTenkenPM;
				$AMPM[] = "PM";
				$DatesData[] = ${"Date".$k};
			}

		}

		$DatesLoop = count($Dates);

	}


	########################################################
	# コンテンツ表示
	########################################################
	$CNT_FILE = "s_Taio_Form2.tpl";

	$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues 	= $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
	########################################################
	# 関数群
	########################################################
	#品番一致
	function getDeviceData_Hinban($myDB, $Category, $Hinban){

		$DeviceData = array();

		$myListObject = new SPFWListObject($myDB);

		$sql = "SELECT ";
		$sql .= "DeviceName, ";
		$sql .= "Kataban, ";
		$sql .= "D003, ";#機器説明
		$sql .= "ShortName ";
		$myListObject->SelectSQL = $sql;

		$sql = " FROM tDeviceM";
		$sql .= " WHERE MukouFlg = FALSE and Category = '".$Category."'";#1:親機 2:子機
		$sql .= " AND Kataban = '".$Hinban."' ";

		$myListObject->Condition = $sql;
		$myListObject->Order = "Kataban";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting Menu List Failed.", E_USER_ERROR);

		if ($myListObject->Rows == 1) {
			$DeviceData['DeviceName'] 	= $myListObject->GetValue(0, 0);
			$DeviceData['Kataban'] 		= $myListObject->GetValue(0, 1);
			$DeviceData['KikiSetumei'] 	= $myListObject->GetValue(0, 2);
			$DeviceData['ShortName'] 	= $myListObject->GetValue(0, 3);
		}
		unset($myListObject);

		return $DeviceData;
	}
