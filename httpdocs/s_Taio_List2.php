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
	include_once _CLS_DIR . "SPFWTools.cls";

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 値取得
	########################################################
	$rKey 			= SPFWParameter::getValues("rKey");
	$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
	$SearchRoomNo = SPFWParameter::getValues('SearchRoomNo');
	########################################################
	# 認証動作
	########################################################
	if($rKey){
		$myUser = new User($myDB);
		if ($rKey == NULL)
			showSorryPage(_ILLEGAL_ACCESS);
	
		if (!$myUser->doAuthenticationByRegistKey($rKey))
			trigger_error("doAuthentication Failed.", E_USER_ERROR);
	
		if ($myUser->UserCD == -1)
			showSorryPage(_ILLEGAL_ACCESS);

		$UserCD 		= $myUser->UserCD;
		$ClientCD 		= $myUser->ClientCD ;#幹事企業CD
		// #	$Extra1 		= $myUser->EigyosyoCD ;#幹事企業拠点CD
		// #	$MyZokusei 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
		// #	$MyEigyoshoCD	= $myUser->GyosyaCD ;#協力業者CD
		// $EigyosyoCD 		= $myUser->EigyosyoCD ;#幹事企業支店・営業所CD
		// $UserKbn 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
		// $GyosyaCD	= $myUser->GyosyaCD ;#協力業者CD
		unset($myUser);
		if($ClientCD){
			$IfClientCD = true;
		}
	}

	########################################################
	# 担当者リスト表示
	########################################################

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "UserCD, ";
	$sql .= "LastName ";

	$myListObject->SelectSQL = $sql;

	$sql = " FROM tUserM";
	// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
	$sql .= " WHERE MukouFlg = FALSE AND ClientCD = ".$ClientCD;
	$myListObject->Condition	= $sql;
	$myListObject->Order 		= "";
	$myListObject->Limit 		= "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$UserLoop = $myListObject->Rows;
	for($i=0; $i< $UserLoop; $i++){
		$UserCDs[$i] 	= $myListObject->GetValue($i, 0);
		$LastName[$i]	= $myListObject->GetValue($i, 1);
		$LastNameArray[$UserCDs[$i]] = $LastName[$i];
	}
	unset($myListObject);

	#######################################################
	# 物件情報
	#######################################################
	$myBukken = new Bukken($myDB);
	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
	if ($myBukken->RecCnt != 1) {

		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);

	} else {

		$Date1 = $myBukken->Date1;
		$Date2 = $myBukken->Date2;
		$Date3 = $myBukken->Date3;
		$Date4 = $myBukken->Date4;
		$Date5 = $myBukken->Date5;

		$AllDates = array($Date1,$Date2,$Date3,$Date4,$Date5);
		// usort($AllDates, "date_sort");
		$AllDates = array_diff($AllDates, array(""));
		$AllDates = array_values($AllDates);
		$FirstDate = current($AllDates);
		$LastDate = end($AllDates);
		if($LastDate){
			if($FirstDate != $LastDate){
				$IfExistLastDate = true;
			}else{
				$IfnotExistLastDate = true;
			}
		}else{
			$IfnotExistLastDate = true;
		}

	}
	unset($myListObject);




	########################################################
	# お客様問い合わせ一覧
	########################################################

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "FormCD, ";
	$sql .= "RoomNo, ";
	$sql .= "Name, ";
	$sql .= "TEL, ";
	$sql .= "Contents, ";
	$sql .= "Creator,";
	$sql .= "Created,";
	$sql .= "TaioLog, ";
	$sql .= "IsInRoom, ";
	$sql .= "DemandDate, ";
	$sql .= "Dates, ";
	$sql .= "AMPM ";

	$myListObject->SelectSQL = $sql;

	$sql = " FROM tResidentsFormF";
	// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
	$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD ;

	if($SearchRoomNo) $sql .= " AND RoomNo LIKE '%".$SearchRoomNo."%'";

	$myListObject->Condition	= $sql;
	// $myListObject->Order 		= "Created desc";
	$myListObject->Order 		= "CASE DemandDate WHEN '不在' THEN 2 ELSE 1 END, Dates asc, AMPM asc, RoomNo asc ";
	$myListObject->Limit 		= "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$ResidentsFormLoop = $myListObject->Rows;
	for($i=0; $i< $ResidentsFormLoop; $i++){
		$FormCD[$i] 	= $myListObject->GetValue($i, 0);
		$RoomNo[$i]	= $myListObject->GetValue($i, 1);
		$Name[$i] = $myListObject->GetValue($i, 2);
		$TEL[$i] = $myListObject->GetValue($i, 3);
		$Contents[$i] = $myListObject->GetValue($i, 4);
		$Creator[$i] = $myListObject->GetValue($i, 5);
		$CreatorName[$i] = $LastNameArray[$Creator[$i]];
		$Created[$i] = $myListObject->GetValue($i,6);
		$TaioLog[$i] = $myListObject->GetValue($i,7);
		$IsInRoom[$i] = $myListObject->GetValue($i, 8);
		if($IsInRoom[$i] == "2"){
			$Contents[$i] = "在宅不可";
		}
		$DemandDate[$i] = $myListObject->GetValue($i,9);
		$DemandDate[$i] = mb_substr($DemandDate[$i],5,20);

		$Dates[$i] = $myListObject->GetValue($i,10);
		$AMPM[$i] = $myListObject->GetValue($i,11);

	}
	
	unset($myListObject);

	########################################################
	# お客様問い合わせ一覧 削除済
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "FormCD, ";
	$sql .= "RoomNo, ";
	$sql .= "Name, ";
	$sql .= "TEL, ";
	$sql .= "Contents, ";
	$sql .= "Creator,";
	$sql .= "Created,";
	$sql .= "TaioLog, ";
	$sql .= "IsInRoom, ";
	$sql .= "DemandDate ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tResidentsFormF";
	// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
	$sql .= " WHERE MukouFlg = TRUE AND BukkenCD = ".$editBukkenCD ;

	if($SearchRoomNo) $sql .= " AND RoomNo LIKE '%".$SearchRoomNo."%'";

	$myListObject->Condition	= $sql;
	// $myListObject->Order 		= "Created desc";
	$myListObject->Order 		= "CASE DemandDate WHEN '不在' THEN 2 ELSE 1 END, Dates asc, AMPM asc, RoomNo asc ";
	$myListObject->Limit 		= "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$ResidentsFormLoop2 = $myListObject->Rows;
	for($i=0; $i< $ResidentsFormLoop2; $i++){

		$FormCD2[$i] 	= $myListObject->GetValue($i, 0);
		$RoomNo2[$i]	= $myListObject->GetValue($i, 1);
		$Name2[$i] = $myListObject->GetValue($i, 2);
		$TEL2[$i] = $myListObject->GetValue($i, 3);
		$Contents2[$i] = $myListObject->GetValue($i, 4);
		$Creator2[$i] = $myListObject->GetValue($i, 5);
		$CreatorName2[$i] = $LastNameArray[$Creator[$i]];
		$Created2[$i] = $myListObject->GetValue($i,6);
		$TaioLog2[$i] = $myListObject->GetValue($i,7);
		$IsInRoom2[$i] = $myListObject->GetValue($i, 8);
		if($IsInRoom2[$i] == "2"){
			$Contents2[$i] = "在宅不可";
		}
		$DemandDate2[$i] = $myListObject->GetValue($i,9);

	}
	
	unset($myListObject);


	########################################################
	# コンテンツ表示
	########################################################
	$CNT_FILE = "s_Taio_List.tpl";

	$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues 	= $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);




?>
