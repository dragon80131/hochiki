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
	include_once _CLS_DIR . "SPUSAccessDevice.cls";

	########################################################
	# データベース接続
	########################################################

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 入力チェック
	########################################################
	foreach($_POST as $key => $value){
#		echo "<br>key:".$key;
		${"$key"} = SPFWParameter::getValues($key);
	}

	// rKey必要
	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	$myUser = new User($myDB);
	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	$loginUserCD = $myUser->UserCD;
	$ID = $myUser->ID;
	$Extra3 = $myUser->Extra3;		#管理ユーザ２一般ユーザ１
#	$MyEigyoshoCD = $myUser->Extra4 ;	#営業所CD nespeユーザはNULLになってる
	unset($myUser);

	// ユーザ制御（nespeアカウントのみ）
	if ( (strpos($ID,"nespe") === false) and ($Extra3 == 2) ) {
		#nespeでない、かつ管理者でない
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}


	########################################################
	# 登録・更新等各種動作
	########################################################
	if ($work == 1){
		$ErrorString = array();

		// 必須項目
		if ($wDeviceID == NULL or $wDeviceID == "") $ErrorString[] = "wDeviceIDは必須項目です。";
		if ($wLastName == NULL or $wLastName == "") $ErrorString[] = "wLastNameは必須項目です。";

		if (count($ErrorString) > 0){
			showAdminSorryPage($ErrorString);
		}

		$myAccessDevice = new AccessDevice($myDB);

		if ($editAccessDeviceIDCD > 0){
			if (!$myAccessDevice->executeSelect("AccessDeviceIDCD = " . $editAccessDeviceIDCD, "") || $myAccessDevice->RecCnt != 1){
				$ErrorString = array();
				$ErrorString[] = "tAccessDeviceM情報の抽出に失敗しました。";
				showAdminSorryPage($ErrorString);
			}
			$myAccessDevice->AccessDeviceIDCD = $editAccessDeviceIDCD;

		} else {
			$myAccessDevice->AccessDeviceIDCD = -1;
			$myAccessDevice->Creator = $loginUserCD;

		}

		$myAccessDevice->DeviceID = $wDeviceID; // 端末ID(monaca)
		$myAccessDevice->LastName = $wLastName;
		$myAccessDevice->UserCD = $wUserCD;
		$myAccessDevice->ShortName = $wShortName;
		$myAccessDevice->Notes = $wNotes;
		$myAccessDevice->AppVer = $wAppVer;

		$myAccessDevice->Updater = $loginUserCD;

		if (!$myAccessDevice->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

		SPFWTemplate::dropValue("editAccessDeviceIDCD");
		SPFWTemplate::dropValue("work");

		// リロード対策のため画面更新
		header('Location: ' . "s_accessdevice_list.php?rKey=".$rKey);
		exit;
	}

	SPFWTemplate::dropValue("editAccessDeviceIDCD");
	SPFWTemplate::dropValue("work");


	########################################################
	# 担当者名　配列準備
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "UserCD, ";
	$sql .= "LastName ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tUserM";
	$sql .= " WHERE UserCD > 0 AND MukouFlg = FALSE";
	$myListObject->Condition = $sql;
	$myListObject->Order = "UserCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$UserListLoop = $myListObject->Rows;
	for ($i = 0; $i < $UserListLoop; $i++) {
		$tmpUserCD[$i] = $myListObject->GetValue($i, 0);
		$tmpLastName[$i] = $myListObject->GetValue($i, 1);
		$tmpUserLastName[$tmpUserCD[$i]] = $tmpLastName[$i];
	}
 	unset($myListObject);


	########################################################
	# 一覧取得
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "AccessDeviceIDCD,";
	$sql .= "DeviceID,";
	$sql .= "LastName,";
	$sql .= "UserCD,";
	$sql .= "ShortName,";
	$sql .= "Notes,";
	$sql .= "AppVer,";
	$sql .= "Created";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tAccessDeviceM ";
	$sql .= " WHERE MukouFlg = 0";

	$myListObject->Condition = $sql;
	$myListObject->Order = 1;
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$DeviceLoop = $myListObject->Rows;
	for ($i = 0; $i < $DeviceLoop; $i++) {
		$AccessDeviceIDCD[$i] = $myListObject->GetValue($i, 0);
		$DeviceID[$i] = $myListObject->GetValue($i, 1);
		$LastName[$i] = $myListObject->GetValue($i, 2);
		$UserCD[$i] = $myListObject->GetValue($i, 3);
		if ($UserCD[$i] == 0) {
			$UserCD[$i] = "";
			$UserName[$i] = "-";
		} else {
			$UserName[$i] = $tmpUserLastName[$UserCD[$i]];
		}

		$ShortName[$i] = $myListObject->GetValue($i, 4);
		$Notes[$i] = $myListObject->GetValue($i, 5);
		$AppVer[$i] = $myListObject->GetValue($i, 6);
		$Created[$i] = $myListObject->GetValue($i, 7);
		$Created[$i] = substr($Created[$i],0,10);

		if ($i % 2 == 0) $bc[$i] = "#DFEEFF";

	}


	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") . ".tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, TRUE);

	unset($myTemplate);
	unset($myLog);
	unset($myDB);
?>
