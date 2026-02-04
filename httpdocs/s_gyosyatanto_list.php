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
	include_once _CLS_DIR . "SPUSGyosya.cls";

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	foreach($_POST as $key => $value){
		${"$key"} = SPFWParameter::getValues($key);
	}
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

	$loginUserCD = $myUser->UserCD;
	$MyShozokuCD = $myUser->Extra1 ;#所属支店CD
	$MyZokusei = $myUser->Extra3 ;#管理ユーザ２一般ユーザ１
	$MyEigyoshoCD = $myUser->Extra4 ;#営業所CD
	$loginID = $myUser->ID;
	$ClientCD = $myUser->ClientCD;
	$wGyosyaCD = SPFWParameter::getValues("wGyosyaCD");
	$UserKbn = $myUser->UserKbn;
	$UserType = $myUser->UserType;

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
	$IfDevelper = $UserKbn != 3;
	$IfWorker = $UserKbn == 3;
	
	// マスターメンテナンス
	$IfMasterMaintenance = false;
	if($IfDevelper && $UserType == '1'){
		$IfMasterMaintenance = true;
	}

	########################################################
	# 登録・更新等各種動作
	########################################################
	if ($work == 1){
		// 必須項目
		$ErrorString = array();
		if ($wGyosyaTantoName == NULL) $ErrorString[] = "担当氏名は必須項目です。";
		if ($wGyosyaTantoNameKana == NULL) $ErrorString[] = "担当氏名ふりがなは必須項目です。";
		if ($wGyosyaCD == NULL) $ErrorString[] = "会社名は必須項目です。";
		if ($wID != "" && !SPFWInputCheck::isAlphaNumeric($wID))
			$ErrorString[] = "IDは半角英数字で入力して下さい";
		if ($wID != "" && strlen($wID) < 6)
			$ErrorString[] = "IDは6文字以上で入力して下さい";

		if ($wPasswd != "" && !SPFWInputCheck::isAlphaNumeric($wPasswd))
			$ErrorString[] = "パスワードは半角英数字で入力して下さい";
		if ($wPasswd != "" && strlen($wPasswd) < 8)
			$ErrorString[] = "パスワードは8文字以上で入力して下さい";
		if ($wGyosyaTantoMail == NULL) $ErrorString[] = "メールアドレスは必須項目です。";


		$myUser = new User($myDB);
		if ($wID != "" && $myUser->isExistUserByID($wID, "", $editGyosyaTantoCD)){
			$ErrorString[] = "ご希望のIDは既に他の方に登録されているようです。";
		}
		unset($myUser);

		if (count($ErrorString) > 0){
			$ErrorLoop = count($ErrorString);
			for ($i = 0; $i < count($ErrorString); $i++)
				$ErrorMessage .= $ErrorString[$i] . "<br>\n";
			$IfError = TRUE;
			include_once("s_gyosyatanto_detail.php");
			exit;
		}

		// 登録処理
		$myUser = new User($myDB);

		if ($editGyosyaTantoCD > 0){
			if (!$myUser->executeSelect("UserCD = " . $editGyosyaTantoCD ." AND MukouFlg = FALSE", "") || $myUser->RecCnt != 1){
				trigger_error("Getting User Failed.", E_USER_ERROR);
			}
		} else {
			$myUser->UserCD = -1;
			$myUser->Creator = $loginUserCD;
			$myUser->RegistKey = $myUser->getNewKey();
			$myUser->IdentifyKey = $myUser->getNewKey('IdentifyKey');
			// $myUser->ClientCD = $ClientCD;
			$myUser->ClientCD = 0;
		}

		$myUser->ID = $wID;
		$myUser->Passwd = $wPasswd;
		$myUser->LastName = $wGyosyaTantoName;
		$myUser->LastNameKana = $wGyosyaTantoNameKana;
		$myUser->TEL = $wGyosyaTantoTEL;#会社TEL
		$myUser->Address1 = $wGyosyaTantoMail;#メール１
		// $myUser->Address2 = $wGyosyaTantoMail2;#メール２
		$myUser->Notes = $wGyosyaTantoNotes;
		$myUser->Extra5 = $wGyosyaCD;#施工業者CD
		$myUser->Updater = $loginUserCD;
		$myUser->GyosyaCD = $wGyosyaCD;
		$myUser->Skip2faFlg = $wSkip2faFlg ? TRUE : FALSE; // 2段階認証スキップフラグ

		$myUser->UserKbn = "3";//協力業者の値

		if (!$myUser->executeUpdate()){
			trigger_error("Updating User Failed.", E_USER_ERROR);
		}
		unset($myUser);

		// リダイレクト
		$URL = _MAIN_URL . "s_gyosyatanto_list.php?rKey=" . $rKey;
		header('Location: ' . $URL);
		exit;

	} else if ($work == 2){

		$myUser = new User($myDB);
		if (!$myUser->executeSelect("UserCD = " . $editGyosyaTantoCD ." AND MukouFlg = FALSE", NULL)){
			trigger_error("Getting User Failed.", E_USER_ERROR);
		}
		if ($myUser->RecCnt == 1) {
			$myUser->MukouFlg = TRUE;
			$myUser->Updater = $loginUserCD;

			if (!$myUser->executeUpdate()){
				trigger_error("Updating User Failed.", E_USER_ERROR);
			}
		}
		unset($myUser);

		$IfDelete = TRUE;
	}

	SPFWTemplate::dropValue('editGyosyaTantoCD');
	SPFWTemplate::dropValue('wID');
	SPFWTemplate::dropValue('wPasswd');
	SPFWTemplate::dropValue('wGyosyaTantoName');
	SPFWTemplate::dropValue('wGyosyaTantoTEL');
	SPFWTemplate::dropValue('wGyosyaTantoKeitai');
	SPFWTemplate::dropValue('wGyosyaTantoMail');
	SPFWTemplate::dropValue('wGyosyaTantoMail2');
	SPFWTemplate::dropValue('wGyosyaTantoNotes');
	SPFWTemplate::dropValue('wGyosyaCD');
	SPFWTemplate::dropValue('wSitenEigyoshoName');
	SPFWTemplate::dropValue('wUserType');
	SPFWTemplate::dropValue('work');
	########################################################
	# 施工会社一覧表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "g.GyosyaCD, ";
	$sql .= "g.GyosyaName, ";
	$sql .= "u.UserCD, ";
	$sql .= "u.LastName, ";
	$sql .= "u.Address1, ";
	$sql .= "g.GyosyaTEL";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tGyosyaM g INNER JOIN tUserM u on g.GyosyaCD = u.GyosyaCD ";
	$sql .= " WHERE g.GyosyaCD > 0 AND g.MukouFlg = FALSE AND u.MukouFlg = FALSE";
	$sql .= " AND g.ClientCD = ".$ClientCD;

	$myListObject->Condition = $sql;
	// $myListObject->Order = "cast( GyosyaNameKana AS BINARY )";
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Gyosya List Failed.", E_USER_ERROR);

	$GyosyaListLoop = $myListObject->Rows;
	for ($j = 0; $j < $GyosyaListLoop; $j++) {
		$GyosyaCD[$j]   = $myListObject->GetValue($j, 0);
		$GyosyaName[$j] = $myListObject->GetValue($j, 1);
		$GyosyaTantoCD[$j] = $myListObject->GetValue($j, 2);
		$GyosyaTantoName[$j] = $myListObject->GetValue($j, 3);
		$GyosyaTantoMail[$j] = $myListObject->GetValue($j, 4);
		$GyosyaTEL[$j] = $myListObject->GetValue($j, 5);

	}

	########################################################
	# 担当者一覧表示
	########################################################
	// $myListObject = new SPFWListObject($myDB);

	// $sql = "SELECT ";
	// $sql .= "u.UserCD, ";
	// $sql .= "u.LastName, ";		#1 2 名前
	// $sql .= "u.EMail, ";		#2 3メール
	// $sql .= "u.Extra2, ";		#3 4表示順
	// $sql .= "u.Notes, ";		#4 5備考
	// $sql .= "u.Extra3, ";		#5 6管理者FLG
	// $sql .= "u.Extra5, ";		#6 7施工会社CD
	// $sql .= "e.GyosyaName, ";	#7 8施工会社名
	// $sql .= "u.Address2, ";		#8 9Address2＝メールアドレス２
	// $sql .= "u.Address3, ";		#9 10Address3＝携帯電話番号
	// $sql .= "u.TEL, ";			#10 11電話番号

	// $sql .= "u.ID, ";			#11
	// $sql .= "u.Passwd ";		#12

	// $myListObject->SelectSQL = $sql;

	// $sql = " FROM tUserM u, tGyosyaM e ";
	// $sql .= " WHERE u.Extra5 = e.GyosyaCD AND u.MukouFlg = FALSE";
	// // $sql .= " u.UserKbn = 3";

	// // ネスペユーザー以外は、自分の情報のみ表示
	// if (!$IfNespe) {
	// 	$sql .= " AND UserCD = '".$loginUserCD."'";
	// }

	// $myListObject->Condition = $sql;
	// $myListObject->Order = "e.GyosyaNameKana";
	// $myListObject->Limit = "allpage";

	// if (!($myListObject->GetList(1)))
	// 	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	// if ($myListObject->Rows != 0) {
	// 	$GyosyaTantoLoop = $myListObject->Rows;
	// 	for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
	// 		$GyosyaTantoCD[$i] = $myListObject->GetValue($i, 0);	#担当CD
	// 		$GyosyaTantoName[$i] = $myListObject->GetValue($i, 1);	#担当者名
	// 		$GyosyaTantoMail[$i] = $myListObject->GetValue($i, 2);	#メールアドレス
	// 		$GyosyaTantoNotes[$i] =  $myListObject->GetValue($i, 4);#備考
	// 		$GyosyaName[$i] =  $myListObject->GetValue($i, 7);#施工会社名
	// 		$GyosyaTantoMail2[$i] = $myListObject->GetValue($i, 8);	#メールアドレス２
	// 		$GyosyaTantoKeitai[$i] = $myListObject->GetValue($i, 9);#携帯TEL
	// 		$GyosyaTantoTEL[$i] = $myListObject->GetValue($i, 10);	#TEL
	// 		$user_ID[$i] = $myListObject->GetValue($i, 11);
	// 		$user_Passwd[$i] = $myListObject->GetValue($i, 12);

	// 		// 削除ボタン用
	// 		if ($IfNespe) {
	// 			$IfNespeArray[$i] = TRUE;
	// 		}
	// 	}
	// }
	// unset($myListObject);


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
