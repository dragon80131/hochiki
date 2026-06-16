<?php

	// ini_set('display_errors', "On");

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
	include_once _CLS_DIR . "SPFWTools.cls";

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
	// if($rKey){
	// 	$myUser = new User($myDB);
	// 	if ($rKey == NULL)
	// 		showSorryPage(_ILLEGAL_ACCESS);
	
	// 	if (!$myUser->doAuthenticationByRegistKey($rKey))
	// 		trigger_error("doAuthentication Failed.", E_USER_ERROR);
	
	// 	if ($myUser->UserCD == -1)
	// 		showSorryPage(_ILLEGAL_ACCESS);

	// 	$UserCD 		= $myUser->UserCD;
	// 	$ClientCD 		= $myUser->ClientCD ;#幹事企業CD
	// 	// #	$Extra1 		= $myUser->EigyosyoCD ;#幹事企業拠点CD
	// 	// #	$MyZokusei 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
	// 	// #	$MyEigyoshoCD	= $myUser->GyosyaCD ;#協力業者CD
	// 	// $EigyosyoCD 		= $myUser->EigyosyoCD ;#幹事企業支店・営業所CD
	// 	// $UserKbn 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
	// 	// $GyosyaCD	= $myUser->GyosyaCD ;#協力業者CD
	// 	unset($myUser);
	// }

	########################################################
	# 担当者リスト表示
	########################################################
	// $myListObject = new SPFWListObject($myDB);

	// $sql = "SELECT ";
	// $sql .= "UserCD, ";
	// $sql .= "LastName ";

	// $myListObject->SelectSQL = $sql;

	// $sql = " FROM tUserM";
	// // if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
	// $sql .= " WHERE MukouFlg = FALSE AND ClientCD = ".$ClientCD;
	// $myListObject->Condition	= $sql;
	// $myListObject->Order 		= "";
	// $myListObject->Limit 		= "allpage";

	// if (!($myListObject->GetList(1)))
	// 	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	// $UserLoop = $myListObject->Rows;
	// for($i=0; $i< $UserLoop; $i++){
	// 	$UserCDs[$i] 	= $myListObject->GetValue($i, 0);
	// 	$LastName[$i]	= $myListObject->GetValue($i, 1);
	// 	$LastNameArray[$UserCDs[$i]] = $LastName[$i];
	// }
	// unset($myListObject);
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
	$sql .= "IsInRoom ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tResidentsFormF";
	$editBukkenCD = 24;
	// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
	$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD ;
		#$sql .= " AND EigyosyoCD = ".$EigyosyoCD; #営業所のみ
	$myListObject->Condition	= $sql;
	$myListObject->Order 		= "Created desc";
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

	}
	
	unset($myListObject);

	########################################################
	# 物件情報取得（編集）
	########################################################

	// $myBukken = new Bukken($myDB);

	// if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1){
	// 	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	// }

	// $wKenmeiNo 				= $myBukken->KenmeiNo;
	// $wBukkenName 			= $myBukken->BukkenName;
	// $wBukkenName_Hurigana	= $myBukken->BukkenName_Hurigana;
	// $wAddress 				= $myBukken->Address;

	// $wBukkenMemo 				= $myBukken->BukkenMemo;

	// if($myBukken->TantoCD){
	// 	$tempTantoCD 			= $myBukken->TantoCD; #入力されていない。
	// 	$TantoCDSelected[$TantoSoeji[$tempTantoCD]] = " selected ";
	// }
	// // 所属支店
	// $ShozokuCD 				= $myBukken->ShozokuCD;
	// //$SitenName = getSitenData($myDB, $ShozokuCD); // include/common.php

	// $wKosu 					= $myBukken->Kosu;
	// $wKaidaka 				= $myBukken->Kaidaka;

	// $wKanriGaisya 			= $myBukken->KanriGaisya;
	// $wOwner_name 			= $myBukken->Owner_name;
	// $wKanriGaisyaTanto 		= $myBukken->KanriGaisyaTanto;
	// $wKanriGaisyaTEL 		= $myBukken->KanriGaisyaTEL;

	// unset($myBukken);
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

	// function ChangeToMongon(int $value){
	// 	if($value == "1"){
	// 		$ChangedValue = "可";
	// 	}else if($value == "2"){
	// 		$ChangedValue = "不可";
	// 	}else{
	// 		$ChangedValue = "";
	// 	}

	// 	return $ChangedValue;
	// }

?>
