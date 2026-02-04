<?php
	include_once "setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSBuilding.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


	########################################################
	# 値取得
	########################################################

	$rKey = SPFWParameter::getValues('rKey');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
	$work = SPFWParameter::getValues('work');
	$KojiDateCD = SPFWParameter::getValues('KojiDateCD');


	########################################################
	# 認証動作
	########################################################

	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	$loginUserCD = $myUser->UserCD;


	########################################################
	# 物件情報抽出
	########################################################

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting myBukken Failed.", E_USER_ERROR);
	}

	$myBuilding = new Building($myDB);
	if($editBuildingCD){
		if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "")) {
			trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
		}
	}

	$BukkenName = $myBukken->BukkenName;
	$wBuildingName = $myBukken->BuildingName;
	if($editBuildingCD){
		$wBuildingName = $myBuilding->BuildingName;
	}

	unset($myBukken);

	// 棟名称が空の場合、例外処理
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "u.BuildingCD, ";
	$sql .= "u.BuildingName ";

	$myListObject->SelectSQL = $sql;

	$sql = " FROM tBuildingM u ";
	$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$editBukkenCD."'";

	$myListObject->Condition = $sql;
	$myListObject->Order = "u.BuildingCD ASC";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User List Failed.", E_USER_ERROR);

	$BuildingCD = [];
	$BuildingName = [];
	$BuildingLoop = $myListObject->Rows;

	for ($i = 0; $i < $BuildingLoop; $i++) {
		$BuildingCD[$i] = $myListObject->GetValue($i, 0);
		$BuildingName[$i] = $myListObject->GetValue($i, 1);
	}
	unset($myListObject);

	// 棟名称が空の場合、例外処理
function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}	
	if(!$wBuildingName){
		if($editBuildingCD){
			$wBuildingName = '棟'.numberToCircled(2);
			for ($i = 0; $i < $BuildingLoop; $i++) {
				if($BuildingCD[$i] == $editBuildingCD){
					$wBuildingName = '棟'.numberToCircled($i+2);
				}
			}

		}else{
			if($BuildingLoop > 0){
				$wBuildingName = '棟'.numberToCircled(1);
			}
		}
	}
	if($BuildingLoop < 1){
		$wBuildingName = '';
	}


	########################################################
	# 工事日時情報抽出
	########################################################

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "KojiDateCD, ";
	$sql .= "RoomID, ";
	$sql .= "RoomDate ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tKojiDateF";
	$sql .= " WHERE MukouFlg = FALSE ";
	$sql .= " AND BukkenCD = " . $editBukkenCD;

	if($editBuildingCD){
		$sql .= " AND BuildingCD = " . $editBuildingCD;
	}else{
		$sql .= " AND BuildingCD IS NULL ";
	}

	$sql .= " AND KojiDateCD = " .$KojiDateCD;
	$myListObject->Condition = $sql;

	$myListObject->Order ="KojiDateCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1))) 
		trigger_error("Getting KojiDate Failed.", E_USER_ERROR);

	if ($myListObject->Rows != 1) {

		#エラー
		$ErrorString = array();
		$ErrorString[] = "エラーです。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	} else {

		$wKojiDateCD = $myListObject->GetValue(0, 0);
		$wUserCD = $myListObject->GetValue(0, 1);
		$wTimeFromDate = substr( $myListObject->GetValue(0, 2),0,10);
		$wTimeFromTime = substr( $myListObject->GetValue(0, 2),11,5);

	}
	unset($myListObject);


	########################################################
	# 工事情報抽出
	########################################################

	$myKoji = new Koji($myDB);

	if($editBuildingCD){
		if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE AND BuildingCD = " . $editBuildingCD, "")){
			trigger_error("Getting myKoji Failed.", E_USER_ERROR);
		}
	}else{
		if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE AND BuildingCD IS NULL", "")){
			trigger_error("Getting myKoji Failed.", E_USER_ERROR);
		}
	}


	$SenyuStartDate = $myKoji->SenyuStartDate;
	$SenyuEndDate = $myKoji->SenyuEndDate;
	$WakuPattern = $myKoji->WakuPattern;
	unset($myBukken);

	// セレクトボックス用
	$StartLoop = count($WAKUPATTERN[$WakuPattern]["StartTime"]);
	for ($i = 0; $i < $StartLoop; $i++) {
		$StartTime[$i] = $WAKUPATTERN[$WakuPattern]["StartTime"][$i];

		if ($StartTime[$i] == $wTimeFromTime) {
			$StartTimeSelected[$i] = "selected";
		}
	}



	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_kojidate_confirm.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
