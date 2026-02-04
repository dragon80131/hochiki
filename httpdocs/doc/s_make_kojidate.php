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
	include_once _CLS_DIR . "SPUSKojiDate.cls";
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
	# 更新処理
	########################################################

	if ($work == 1) {

		$editKojiDateCD = SPFWParameter::getValues('editKojiDateCD');
		$wTimeFromDate = SPFWParameter::getValues('wTimeFromDate');
		$wTimeFromTime = SPFWParameter::getValues('wTimeFromTime');

		$myKojiDate = new KojiDate($myDB);

		if (!$myKojiDate->executeSelect("KojiDateCD = " . $editKojiDateCD . " AND MukouFlg = FALSE", "")){
			trigger_error("Getting myBukken Failed.", E_USER_ERROR);
		}

		if ($myKojiDate->RecCnt == 1) {
			#$RoomDate = $myKojiDate->RoomDate;

			$RoomDate = $myKojiDate->RoomDate = $wTimeFromDate." ".$wTimeFromTime;

			if (!$myKojiDate->executeUpdate()){
				trigger_error("executeUpdate(myKojiDate) Failed.", E_USER_ERROR);
			}
		}
		unset($myKojiDate);
	}


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
	# 工事情報抽出
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

	$myListObject->Condition = $sql;

	$myListObject->Order ="KojiDateCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1))) 
		trigger_error("Getting KojiDate List Failed.", E_USER_ERROR);

	$KojiDateLoop = $myListObject->Rows;
	for ($i = 0; $i < $KojiDateLoop; $i++) {
		$No[$i] = $i+1;

		$KojiDateCD[$i] = $myListObject->GetValue($i, 0);
		$UserCD[$i] = $myListObject->GetValue($i, 1);
		$TimeFromDate[$i] = substr( $myListObject->GetValue($i, 2),0,10);
		$TimeFromTime[$i] = substr( $myListObject->GetValue($i, 2),11,5);

	}
	unset($myListObject);


	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_kojidate.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
