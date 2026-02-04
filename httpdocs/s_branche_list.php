<?php
$isAdminMode = TRUE;
	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
	include_once _CLS_DIR . "SPUSBranche.cls";


	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 認証動作
	########################################################
	$myUser = new User($myDB);

	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS2);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS2);

	$UserCD = $myUser->UserCD;
	$Extra3 = $myUser->Extra3;
	$ClientCD = $myUser->ClientCD;
	$editBrancheCD = $myUser->Extra5;#業者CD

	$UserKbn = $myUser->UserKbn;
	$loginID = $myUser->ID;
	$UserType = $myUser->UserType;

	$IfKanrishaFlg = ( $myUser->Extra3 == "2" )? "TRUE":"" ;
	unset($myUser);

	if (preg_match('/nespe/', $loginID)) {
		// $IfNespe = true;
		$IfBranche = false;
	} else {
		$IfNespe = false;
		$IfBranche = true;
	}
	if ($UserKbn != "3") { //幹事企業だった場合
		$IfNespe = true;
	}
	$IfDevelper = $UserKbn != 3;
	$IfWorker = $UserKbn == 3;

	// マスターメンテナンス
	$IfMasterMaintenance = false;
	if($IfDevelper && $UserType == '1'){
		$IfMasterMaintenance = true;
	}
	
	########################################################
	# パラメータ取得
	########################################################
	$wBrancheKanriNo = SPFWParameter::getValues('wBrancheKanriNo');
	########################################################
	# パラメータ取得
	########################################################
	foreach($_POST as $key => $value){
		${"$key"} = SPFWParameter::getValues($key);
	}
	########################################################
	# 登録・更新等各種動作
	########################################################

	if ($work == 1){
		// 必須項目
		$ErrorString = array();
		if ( $wBrancheName == NULL ) $ErrorString[] = "支店・支社名は必須項目です。";
		// if ( $wBrancheNameKana == NULL ) $ErrorString[] = "支店・支社名ふりがなは必須項目です。";
		// if ( $wBrancheTEL == NULL ) $ErrorString[] = "電話番号は必須項目です。";

		if (count($ErrorString) > 0) {
			$ErrorLoop = count($ErrorString);
			include_once("s_branche_detail.php");
			exit;
		}

		$myBranche = new Branche($myDB);

		if ($editBrancheCD > 0){
			if (!$myBranche->executeSelect("BrancheCD = " . $editBrancheCD, "") || $myBranche->RecCnt != 1){
				trigger_error("Getting Branche Failed.", E_USER_ERROR);
			}
			$myBranche->BrancheCD = $editBrancheCD;

		} else {
			$myBranche->BrancheCD = -1;
			$myBranche->Creator = $UserCD;
		}

		$myBranche->ClientCD = $ClientCD;
		$myBranche->BrancheName = $wBrancheName;
		$myBranche->BrancheNameKana = $wBrancheNameKana;
		$myBranche->BrancheTEL = $wBrancheTEL;

		$myBranche->Updater = $UserCD;

		if (!$myBranche->executeUpdate()){
			trigger_error("Updating Branche Failed.", E_USER_ERROR);
		}
		unset($myBranche);


		// リダイレクト
		$URL = _MAIN_URL . "s_branche_list.php?rKey=" . $rKey;
		// header('Location: ' . $URL);
		// exit;

	}else if ($work == 2){

		$myBranche = new Branche($myDB);

		$Condition = "BrancheCD = " . $editBrancheCD;

		if (!$myBranche->executeSelect($Condition, NULL)){
			trigger_error("Getting Branche Failed.", E_USER_ERROR);
		}
		if ($myBranche->RecCnt == 1) {
			$myBranche->MukouFlg = TRUE;
			$myBranche->Updater = $UserCD;

			if ( !$myBranche->executeUpdate()){
				trigger_error("Updating Branche Failed.", E_USER_ERROR);
			}
		}
		$IfDelete = TRUE;
		SPFWTemplate::dropValue("editBrancheCD");
	}

	SPFWTemplate::dropValue('wBrancheCD');
	SPFWTemplate::dropValue('wBrancheName');
	SPFWTemplate::dropValue('wBrancheNameKana');

	########################################################
	# 施工業者一覧表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "BrancheCD, ";
	$sql .= "BrancheName, ";
	$sql .= "BrancheNameKana, ";
	$sql .= "BrancheTEL ";


	$myListObject->SelectSQL = $sql;
	$sql = " FROM tBrancheM";
	if( $Extra == "2"){
		$sql .= " WHERE BrancheCD > 0 AND MukouFlg = FALSE";
	}else{
		// $sql .= " WHERE BrancheCD > 0 AND MukouFlg = FALSE AND BrancheCD = '".$editBrancheCD."' ";#自分ところだけ表示
		$sql .= " WHERE BrancheCD > 0 AND MukouFlg = FALSE AND ClientCD = '".$ClientCD."' ";#自分ところだけ表示
	}
	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Branche List Failed.", E_USER_ERROR);

	$BrancheListLoop = $myListObject->Rows;
	for ($i = 0; $i < $BrancheListLoop; $i++) {
		$BrancheCD[$i] = $myListObject->GetValue($i, 0);
		$BrancheName[$i] = $myListObject->GetValue($i, 1);
		$BrancheNameKana[$i] = $myListObject->GetValue($i, 2);
		$BrancheTEL[$i] = $myListObject->GetValue($i, 3);

	}
	unset($myListObject);


	########################################################
	# コンテンツ表示
	########################################################
	SPFWTemplate::dropValue("work");
	SPFWTemplate::setValue("work", "");
	$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") .".tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();
	$myTemplate->convertTags();
	$myTemplate->outputTemplate();
	unset($myTemplate);
	unset($myLog);
	unset($myDB);
?>
