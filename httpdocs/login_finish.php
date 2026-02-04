<?php
	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";
	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSUser.cls";

	// error_reporting(E_ALL);
	// ini_set('display_errors', '1'); // エラーを表示する
	// set_error_handler(null);
	
	include_once "./include/common_489.php";
	
	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

		
	########################################################
	#　多言語化対応(Multilingual support)
	########################################################
	$lang = get_lang();
	$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照
	if ($lang == 'ja') $Ifjp = TRUE; #datapikckerの制御

	#利用ワード
	$form1 = $WORD[$lang]['form.1']; #取付機器情報
	$form2 = $WORD[$lang]['form.2']; #■標準機器
	$form3 = $WORD[$lang]['form.3']; #取付機器
	$form4 = $WORD[$lang]['form.4']; #型番
	$form5 = $WORD[$lang]['form.5']; #仕様書
	$form6 = $WORD[$lang]['form.6']; #取り扱い説明書
	$form7 = $WORD[$lang]['form.7']; #■オプション機器
	$form8 = $WORD[$lang]['form.8']; #取付機器操作方法動画
	$form9 = $WORD[$lang]['form.9']; #
	$form10 = $WORD[$lang]['form.10']; #
	$form11 = $WORD[$lang]['form.11']; #
	$form12 = $WORD[$lang]['form.12']; #
	$form13 = $WORD[$lang]['form.13']; #
	$form14 = $WORD[$lang]['form.14']; #
	$form15 = $WORD[$lang]['form.15']; #
	$form16 = $WORD[$lang]['form.16']; #つぎへ
	$form17 = $WORD[$lang]['form.17']; #もどる
	$form18 = $WORD[$lang]['form.18']; #
	$form19 = $WORD[$lang]['form.19']; #
	$form20 = $WORD[$lang]['form.20']; #
	$form21 = $WORD[$lang]['form.21']; #
	$form22 = $WORD[$lang]['form.22']; #
	$form23 = $WORD[$lang]['form.23']; #
	$form24 = $WORD[$lang]['form.24']; #
	$form25 = $WORD[$lang]['form.25']; #
	$form26 = $WORD[$lang]['form.26']; #
	$form27 = $WORD[$lang]['form.27']; #
	$form28 = $WORD[$lang]['form.28']; #
	$form29 = $WORD[$lang]['form.29']; #
	$form30 = $WORD[$lang]['form.30']; #

	$top1 = $WORD[$lang]['top.1']; #号室
	$logout1 = $WORD[$lang]['logout.1']; #ログアウト
	$finish2 = $WORD[$lang]['finish.2']; #予約システムTOPへ
	$finish3 = $WORD[$lang]['finish.3']; #予約TOP
	#$form16 = $WORD[$lang]['form.16'];#つぎへ
	#$form17 = $WORD[$lang]['form.17'];#もどる
	
#echo "<br><h1>システムメンテナンス中</h1>" ;
	########################################################
	# 機能チェック
	########################################################
	/*
	if (!$LoginFlg && !$ChangeSecurityFlg) {
		showSorryPage(_ILLEGAL_ACCESS);
		exit;
	}
	*/
	########################################################
	# パラメータチェック/値加工
	########################################################

	$Keys = SPFWParameter::getValues('key');

	$wID = SPFWParameter::getValues('wID');
	$wPasswd = SPFWParameter::getValues('wPasswd');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
	$verificationCode = SPFWParameter::getValues('verificationCode');

	$ipAddress = $_SERVER["REMOTE_ADDR"];#アクセスコントロール用にIPアドレスを取得する

	$BeforePage = SPFWParameter::getValues('BeforePage');
	$Address = SPFWParameter::getValues('Address');
	$Units = SPFWParameter::getValues('Units');
	$Kaidaka = SPFWParameter::getValues('Kaidaka');
	$wBukkenName = SPFWParameter::getValues('BukkenName');
	$CallistoBukkenCD = SPFWParameter::getValues('CallistoBukkenCD');
	$CallistoBukkenName_Hurigana = SPFWParameter::getValues('CallistoBukkenName_Hurigana');
	$ConstructionStartDate = SPFWParameter::getValues('ConstructionStartDate');
	
	
	




	#####################################################
	# 必須項目の判定
	########################################################

	if ($editBukkenCD == "")
		$ErrorString[] = "物件管理番号は必須項目です。";
		if ($wID == "")
			$ErrorString[] = "ユーザー名は必須項目です。";
	if ($wPasswd == "")
		$ErrorString[] = "パスワードは必須項目です。";

 	$ErrorStringLoop = (is_countable($ErrorString)?	count($ErrorString): 0);

	if ($ErrorStringLoop == 0) {
		########################################################
		# 正規アクセスチェック
		########################################################

		// // データベースコネクト
		// $myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
		// if (!$myDB->Connection)
		// 	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

		########################################################
		# アクティブ会員かどうかの判定
		########################################################

		$clsUser = new User($myDB);
		// if (!$clsUser->executeSelect("BukkenCD = '" .$editBukkenCD."' AND ID = '" . $myDB->escapeString($wID) . "' AND ((Passwd = '" . $myDB->escapeString($wPasswd) . "' AND Passwd2 IS NULL) OR (Passwd2 = '" . $myDB->escapeString($wPasswd) . "') ) AND MukouFlg = FALSE"))
		if($editBuildingCD){
			// if (!$clsUser->executeSelect("BukkenCD = '" .$editBukkenCD."' AND BuildingCD = '" .$editBuildingCD."' AND ID = '" . $myDB->escapeString($wID) . "' AND (Passwd = '" . $myDB->escapeString($wPasswd) . "' OR Passwd2 = '" . $myDB->escapeString($wPasswd) . "') AND MukouFlg = FALSE AND UserKbn = '4'"))
			if (!$clsUser->executeSelect("BukkenCD = '" .$editBukkenCD."' AND BuildingCD = '" .$editBuildingCD."' AND ID = '" . $myDB->escapeString($wID) . "' AND MukouFlg = FALSE AND UserKbn = '4'"))
				trigger_error("Getting User Failed.", E_USER_ERROR);
		}else{
			//if (!$clsUser->executeSelect("BukkenCD = '" .$editBukkenCD."' AND BuildingCD IS NULL AND ID = '" . $myDB->escapeString($wID) . "' AND (Passwd = '" . $myDB->escapeString($wPasswd) . "' OR Passwd2 = '" . $myDB->escapeString($wPasswd) . "') AND MukouFlg = FALSE AND UserKbn = '4'"))
			if (!$clsUser->executeSelect("BukkenCD = '" .$editBukkenCD."' AND BuildingCD IS NULL AND ID = '" . $myDB->escapeString($wID) . "' AND  MukouFlg = FALSE AND UserKbn = '4'"))
				trigger_error("Getting User Failed.", E_USER_ERROR);
		}

		if ($clsUser->RecCnt != 1){
			$ErrorString[] = "該当するユーザが見つかりませんでした。";
			$ErrorStringLoop = 1;

		}else if ($clsUser->UserCD > 0) {

			//var_dump($clsUser);
			if($wPasswd != '' && ($clsUser->Passwd2 == $wPasswd || ($clsUser->Passwd2 == '' && $clsUser->Passwd == $wPasswd))){
				// if ($KeyChangeFlg)
				// 	$clsUser->RegistKey = $clsUser->getNewKey();
				// $clsUser->LastLogin = "NOW()";
				$clsUser->RegistKey = ''; // 2段階認証成功まで空に
				$clsUser->LastLoginIP = $ipAddress;

				if (!$clsUser->executeUpdate())
					trigger_error("executeUpdate(clsUser) Failed.", E_USER_ERROR);

				$rKey = $clsUser->RegistKey;
				$wClientCD = $clsUser->ClientCD;
				$ROM = $clsUser->Extra1;
			}else{
				$ErrorString[] = "該当するユーザが見つかりませんでした。"; // パスワード違いというメッセージは出さない。「は」と「が」の違いでわかる人には区別できるように
				$ErrorStringLoop = 1;
			}

		}
	}

	if ($ErrorStringLoop > 0  ){

		// SPFWParameter::dropValues('editBukkenCD');
		// SPFWParameter::dropValues('wID');
		// SPFWParameter::dropValues('wPasswd');
		$ErrorLoop = count($ErrorString);
		for ($i = 0; $i < count($ErrorString); $i++)
			$ErrorMessage .= $ErrorString[$i] . "<br>\n";
		$IfError = TRUE;
		include_once("login.php");
		// SPFWParameter::dropValues('editBukkenCD');
		exit;
	}

	########################################################
	# コンテンツ表示
	########################################################

	if ($editBukkenCD > 0 ){#直接　物件メニューに飛ぶ
		//$URL = _MAIN_URL . "top.php?rKey=" . $rKey."&editBukkenCD=".$editBukkenCD."&editBuildingCD=".$editBuildingCD."&wClientCD=".$wClientCD ;
	}else{
		$URL = _MAIN_URL . "login.php";
		header('Location: ' . $URL);
		exit;
	}

	if( !$clsUser->EmailVerified ) {
		// メールが確認されていない場合は
		// rKey発行してメニューに
		$clsUser->RegistKey = $clsUser->getNewKey();
		$clsUser->LastLogin = "NOW()";
		$clsUser->LastLoginIP = $ipAddress;

		if (!$clsUser->executeUpdate("", $clsUser->UserCD)) {
			trigger_error("Updating User Failed.", E_USER_ERROR);
		}

		$URL = _MAIN_URL . "top.php?rKey=" . $clsUser->RegistKey."&editBukkenCD=".$editBukkenCD."&editBuildingCD=".$editBuildingCD."&wClientCD=".$wClientCD ;
		header('Location: ' . $URL);
		exit;
	}

	$ErrorString = '';
	// 2段階認証
	if ($verificationCode != NULL) {
		// 確認コードが正しいかチェック
		if ($clsUser->EmailVerificationCode != $verificationCode) {
			$ErrorString = "確認コードが正しくありません。";
		}
		else if(strtotime($clsUser->EmailVerificationExpiry) < time()){
			$ErrorString = "確認コードの有効期限が切れています。再度ログインをお試しください。";
		}
		else {
			// 2段階認証成功
			$clsUser->EmailVerificationCode = NULL; // 確認コードをクリア
			$clsUser->EmailVerificationExpiry = NULL; // 有効期限もクリア
			$clsUser->RegistKey = $clsUser->getNewKey();
			$clsUser->LastLogin = "NOW()";
			$clsUser->LastLoginIP = $ipAddress;

			if (!$clsUser->executeUpdate("", $clsUser->UserCD)) {
				trigger_error("Updating User Failed.", E_USER_ERROR);
			}

			$URL = _MAIN_URL . "top.php?rKey=" . $clsUser->RegistKey."&editBukkenCD=".$editBukkenCD."&editBuildingCD=".$editBuildingCD."&wClientCD=".$wClientCD ;
			header('Location: ' . $URL);
			exit;
		}
		
	}
	else {
		// 2段階認証が必要な場合は確認コードを生成してメールを送信
		$verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
		$codeExpiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));
		$clsUser->EmailVerificationCode = $verificationCode;
		$clsUser->EmailVerificationExpiry = $codeExpiry;

		if (!$clsUser->executeUpdate("", $clsUser->UserCD)) {
			trigger_error("Updating User Failed.", E_USER_ERROR);
		}
		$URL = _ROOT_URL . "login.php";
		if($editBuildingCD)
			$URL = _ROOT_URL . "login.php?editBuildingCD=".$editBuildingCD;
		sendTwoFactorAuthMail($clsUser->EMail, $verificationCode, $codeExpiry, $URL);
	}

	$IfError = FALSE;;
	if( $ErrorString != '' ) {
		$IfError = TRUE;
	}

	$IfMagager = FALSE;
	$CNT_FILE = $CNT_FILE = "login_2fa.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	$HiddenValues = $myTemplate->getValuesToPass();
	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	$myDB->close();

	unset($myTemplate);
	unset($myLog);

?>
