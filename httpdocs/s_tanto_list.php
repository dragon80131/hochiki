<?php
$isAdminMode = TRUE;
	include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
	include_once _CLS_DIR . "SPUSEigyosho.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";

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

	$MyUserCD = $myUser->UserCD;
	$UserKbn = $myUser->UserKbn;
	$GyosyaCD = $myUser->GyosyaCD;
	$MyZokusei = $myUser->Extra3 ;#管理ユーザ２一般ユーザ１
	$ClientCD = $myUser->ClientCD ;#会社別コード

	$IfDevelper = $UserKbn != 3;
	$IfWorker = $UserKbn == 3;
	$loginID = $myUser->ID;

	$UserType = $myUser->UserType;
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
	
	unset($myUser);
	########################################################
	# 登録・更新等各種動作
	########################################################
	if ($work == 1){
		$ErrorString = array();
		if ($wLastName == NULL) $ErrorString[] = "氏名は必須項目です。";
		if ($wLastNameKana == NULL) $ErrorString[] = "氏名ふりがなは必須項目です。";
		if ($wID == NULL) $ErrorString[] = "ログイン名は必須項目です。";
		if ($wPasswd == NULL) $ErrorString[] = "パスワードは必須項目です。";
		if ($wUserType == NULL) $ErrorString[] = "権限は必須項目です。";
		if ($wAddress1 == NULL) $ErrorString[] = "メールアドレスは必須項目です。";

		if ($wID != "" && !SPFWInputCheck::isAlphaNumeric($wID))
			$ErrorString[] = "ログイン名は半角英数字で入力して下さい";
		if ($wID != "" && strlen($wID) < 6)
			$ErrorString[] = "ログイン名は6文字以上で入力して下さい";

		if ($wPasswd != "" && !SPFWInputCheck::isAlphaNumeric($wPasswd))
			$ErrorString[] = "パスワードは半角英数字で入力して下さい";
		if ($wPasswd != "" && strlen($wPasswd) < 4)
			$ErrorString[] = "パスワードは4文字以上で入力して下さい";

		$myUser = new User($myDB);
		if ($wID != "" && $myUser->isExistUserByID($wID, "", $editUserCD)){
			$ErrorString[] = "ご希望のログインIDは既に他の方に登録されているようです。";
		}

		// if ($wUserType == '0' && !$wBrancheCompany) $ErrorString[] = "一般ユーザーの場合は、支店・支社を選択してください。";
		if ($wUserType == '0' && !$wBrancheCompany) $ErrorString[] = "一般ユーザーの場合は、支店・支社は必須項目です。";

		unset($myUser);

		if (count($ErrorString) > 0){
			$ErrorLoop = count($ErrorString);
			for ($i = 0; $i < count($ErrorString); $i++)
				$ErrorMessage .= $ErrorString[$i] . "<br>\n";
			$IfError = TRUE;
			include_once("s_tanto_detail.php");
			exit;
		}

		// 登録処理
		$myUser = new User($myDB);
		if ($editUserCD > 0){
			if (!$myUser->executeSelect("UserCD = " . $editUserCD, "") || $myUser->RecCnt != 1)
				trigger_error("Getting User Failed.", E_USER_ERROR);

		}else {
			$myUser->UserCD = -1;
			$myUser->Creator = $MyUserCD;
			$myUser->RegistKey = $myUser->getNewKey();
			$myUser->IdentifyKey = $myUser->getNewKey('IdentifyKey');
		}

		$myUser->ID = $wID;
		$myUser->Passwd = $wPasswd;
		$myUser->LastName = $wLastName;
		$myUser->LastNameKana = $wLastNameKana;
		$myUser->TEL = $wTEL;
		$myUser->Address1 = $wAddress1;
		$myUser->Notes = $wNotes;
		$Skip2faFlg = $wSkip2faFlg ? TRUE : FALSE; // 2段階認証スキップフラグ
		if (preg_match('/nespe/', $wID)) { // ユーザー名に「nespe」が含まれたユーザーを登録する場合自動的に「2段階認証を無効にする」になるように
			$Skip2faFlg = TRUE;
		}
		$myUser->Skip2faFlg = $Skip2faFlg;
		$myUser->UserType = $wUserType;
		$myUser->BrancheCD = $wBrancheCompany; // 支店・支社

		if($UserKbn == "3"){
			$myUser->UserKbn = "3";
			$myUser->ClientCD = "0";
			$myUser->GyosyaCD = $GyosyaCD; 
		}else {
			$myUser->UserKbn = $UserKbn;
			$myUser->ClientCD = $ClientCD;
		}

		unset($myEigyosho);
		$myUser->Updater = $MyUserCD;
		if (!$myUser->executeUpdate()){
			trigger_error("Updating User Failed.", E_USER_ERROR);
		}
		unset($myUser);
	}else if ($work == 2){
		$myUser = new User($myDB);
		$Condition = "UserCD = " . $editUserCD;
		if (!$myUser->executeSelect($Condition, NULL) || $myUser->RecCnt != 1){
				trigger_error("Getting User Failed.", E_USER_ERROR);
		}

		$myUser->MukouFlg = TRUE;
		$myUser->Updater = $MyUserCD;
		if (!$myUser->executeUpdate()){
			trigger_error("Updating User Failed.", E_USER_ERROR);
		}
		unset($myUser);
	}

	SPFWTemplate::dropValue('wUserCD');
	SPFWTemplate::dropValue('wClientCD');
	SPFWTemplate::dropValue('wLastName');
	SPFWTemplate::dropValue('wLastNameKana');
	SPFWTemplate::dropValue('wExtra1');
	SPFWTemplate::dropValue('wEMail');
	SPFWTemplate::dropValue('wExtra2');
	SPFWTemplate::dropValue('wExtra4');
	SPFWTemplate::dropValue('wTEL');
	SPFWTemplate::dropValue('wAddress1');
	SPFWTemplate::dropValue('wNotes');
	SPFWTemplate::dropValue('work');

	########################################################
	# 作業員一覧表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "u.UserCD, ";
	$sql .= "u.LastName, ";
	$sql .= "u.Address1, ";	#Address2＝メールアドレス２
	$sql .= "u.Notes, ";
	$sql .= "u.TEL, ";		#電話番号
	$sql .= "u.UserType ";		#権限

	$myListObject->SelectSQL = $sql;

	$sql = " FROM tUserM u ";
	$sql .= " WHERE u.MukouFlg = FALSE";

	if($UserKbn == "3"){//業者ログイン時の制御
		$sql .= " AND u.GyosyaCD = '$GyosyaCD'";
	}else{
		$sql .= " AND u.ClientCD = '$ClientCD'";
	}
	$sql .= " AND UserKbn < 4 ";
	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User List Failed.", E_USER_ERROR);

	if ($myListObject->Rows != 0) {
		$UserListLoop = $myListObject->Rows;

		for ($i = 0; $i < $UserListLoop; $i++) {
			$UserCD[$i] = $myListObject->GetValue($i, 0);
			$LastName[$i] = $myListObject->GetValue($i, 1);
			$Address1[$i] = $myListObject->GetValue($i, 2);//アドレス２
			$Notes[$i] = $myListObject->GetValue($i, 3);
			$TEL[$i] = $myListObject->GetValue($i, 4);
			$aUserType = $myListObject->GetValue($i, 5);
			$sUserType[$i] = $aUserType=='1'?'管理者':'一般';
		}
	}
	unset($myListObject);

	// $KanrishaFlg = $MyZokusei;
	// $IfKanrishaFlg = ( $KanrishaFlg == "2" )? "TRUE":"" ;
	// $IfNonKanrishaFlg = ( $KanrishaFlg != "2" )? "TRUE":"" ;
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
?>
