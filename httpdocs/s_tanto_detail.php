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
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSTanto.cls";
	
	// error_reporting(E_ALL);
	// ini_set('display_errors', '1'); // エラーを表示する
	// set_error_handler(null);
	
	include_once "./include/common_489.php";


	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 入力チェック
	########################################################
	foreach($_POST as $key => $value){
		${"$key"} = SPFWParameter::getValues($key);
	}
	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

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
	$MyZokusei = $myUser->Extra3 ;#管理ユーザ２一般ユーザ１

	If($MyZokusei == 2 ){
		$IfKanriUser = TRUE;
	}
	$loginID = $myUser->ID;
	$UserType = $myUser->UserType;

	########################################################
	# 新規時の処理
	########################################################
	$IfRegist = false;
	$IfUpdate = true;
	########################################################
	# 新規でない場合:URL抽出処理
	########################################################
	if ($editUserCD > 0){

		$myUser = new User($myDB);

		if (!$myUser->executeSelect("UserCD = " . $editUserCD, "") || $myUser->RecCnt == 0){
			$ErrorString = array();
			$ErrorString[] = "メニューマスタ情報の抽出に失敗しました。";
			$ErrorLoop = count($ErrorString);
			$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
			unset($myTemplate);
			exit;
		}

		$wUserCD = $myUser->UserCD;
		$wID = $myUser->ID;
		$wPasswd = $myUser->Passwd;
		$wClientCD = $myUser->ClientCD;
		$wLastName = $myUser->LastName;
		$wLastNameKana = $myUser->LastNameKana;
		$UserKbn = $myUser->UserKbn;
		$UserType = $myUser->UserType;
		$wBrancheCD = $myUser->BrancheCD;

		if($UserType == 1){
			$UserType0 = "";
			$UserType1 = "selected";
		}else{
			$UserType0 = "selected";
			$UserType1 = "";
		}

		$Skip2faFlg = $myUser->Skip2faFlg; #2段階認証スキップフラグ
		if($Skip2faFlg == 1) {
			//$Skip2faFlgCecked = "checked";
			$Skip2faFlgSelected0 = "";
			$Skip2faFlgSelected1 = "selected";
		} else {
			//$Skip2faFlgCecked = "";
			$Skip2faFlgSelected0 = "selected";
			$Skip2faFlgSelected1 = "";
		}

		$wAddress1 = $myUser->Address1; #メールアドレス
		$wNotes = $myUser->Notes; #営業担当備考

		$wTEL = $myUser->TEL; #電話
		unset($myUser);
	}else{
		$wTitle = "新規登録";
		$IfRegist = true;
		$IfUpdate = false;
	}

	$IfDevelper = $UserKbn != 3;
	$IfWorker = $UserKbn == 3;
	// マスターメンテナンス
	$IfMasterMaintenance = false;
	if($IfDevelper && $UserType == '1'){
		$IfMasterMaintenance = true;
	}

	if (preg_match('/nespe/', $loginID)) {
		// $IfNespe = true;
		$IfGyosya = false;
	} else {
		$IfNespe = false;
		$IfGyosya = true;
	}
	if ($UserKbn != "3") { //幹事企業だった場合
		$IfNespe = true;
	}

	########################################################
	# 支店・支社情報取得
	########################################################

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "BrancheCD, ";
	$sql .= "BrancheName ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tBrancheM ";
	$sql .= " WHERE MukouFlg = FALSE";

	$myListObject->Condition	= $sql;
	$myListObject->Order 		= "";
	$myListObject->Limit 		= "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$BrancheCompanyLoop = $myListObject->Rows;
	for ($i = 0; $i < $BrancheCompanyLoop; $i++) {
		$BrancheCD[$i] 	= $myListObject->GetValue($i, 0);
		$BrancheName[$i]	= $myListObject->GetValue($i, 1);
		if($wBrancheCD == $BrancheCD[$i]){
			$BrancheSelected[$i] = " selected";
		}
	}
	unset($myListObject);
	
	########################################################
	# コンテンツ表示
	########################################################
	SPFWTemplate::setValue("work");
	$CNT_FILE = "s_tanto_detail.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();
	$myTemplate->convertTags();
	$myTemplate->outputTemplate();
	unset($myTemplate);
	unset($myLog);
?>
