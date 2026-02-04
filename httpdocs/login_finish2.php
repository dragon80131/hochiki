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
	$wClientCD = SPFWParameter::getValues('wClientCD');
	$m = SPFWParameter::getValues('m');
	$verificationCode = SPFWParameter::getValues('verificationCode');

	$ipAddress = $_SERVER["REMOTE_ADDR"];#アクセスコントロール用にIPアドレスを取得する

	// $BeforePage = SPFWParameter::getValues('BeforePage');
	// $Address = SPFWParameter::getValues('Address');
	// $Units = SPFWParameter::getValues('Units');
	// $Kaidaka = SPFWParameter::getValues('Kaidaka');
	// $wBukkenName = SPFWParameter::getValues('BukkenName');
	// $CallistoBukkenCD = SPFWParameter::getValues('CallistoBukkenCD');
	// $CallistoBukkenName_Hurigana = SPFWParameter::getValues('CallistoBukkenName_Hurigana');
	// $ConstructionStartDate = SPFWParameter::getValues('ConstructionStartDate');
	
	
	




	#####################################################
	# 必須項目の判定
	########################################################

	if ($wID == "")
		$ErrorString[] = "ユーザー名は必須項目です。";
	if ($wPasswd == "")
		$ErrorString[] = "パスワードは必須項目です。";
	// if ($wClientCD == "")
	// 	$ErrorString[] = "組織コードは必須項目です。";
		
 	$ErrorStringLoop = (is_countable($ErrorString)?	count($ErrorString): 0);

	if ($ErrorStringLoop == 0) {
		########################################################
		# 正規アクセスチェック
		########################################################

		// データベースコネクト
		$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
		if (!$myDB->Connection)
			trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

		########################################################
		# アクティブ会員かどうかの判定
		########################################################

		$clsUser = new User($myDB);
		// if (!$clsUser->executeSelect("ClientCD = $wClientCD AND ID = '" . $myDB->escapeString($wID) . "' AND (Passwd = '" . $myDB->escapeString($wPasswd) . "' OR Passwd2 = '" . $myDB->escapeString($wPasswd) . "') AND MukouFlg = FALSE"))
		if (!$clsUser->executeSelect("ID = '" . $myDB->escapeString($wID) . "' AND (Passwd = '" . $myDB->escapeString($wPasswd) . "' OR Passwd2 = '" . $myDB->escapeString($wPasswd) . "') AND MukouFlg = FALSE AND UserKbn <> '4'"))
			trigger_error("Getting User Failed.", E_USER_ERROR);
		if ($clsUser->RecCnt != 1){
			$ErrorString[] = "該当するユーザは見つかりませんでした。";
			$ErrorStringLoop = 1;

		}else if ($clsUser->UserCD > 0) {

			// if (strpos($wID,'nespe') !== false or $clsUser->UserKbn == 2 ) {//nespeアカウントと幹事企業だけログイン可能

			// if ($KeyChangeFlg)
			// 	$clsUser->RegistKey = $clsUser->getNewKey();
			// $clsUser->LastLogin = "NOW()";
			
			// if (!$clsUser->executeUpdate())
			// 	trigger_error("executeUpdate(clsUser) Failed.", E_USER_ERROR);

			// $rKey = $clsUser->RegistKey;
			// $ROM = $clsUser->Extra1;

		}
	}

	if ($ErrorStringLoop > 0 ){
		$ErrorLoop = count($ErrorString);
		for ($i = 0; $i < count($ErrorString); $i++)
			$ErrorMessage .= $ErrorString[$i] . "<br>\n";
		$IfError = TRUE;
		include_once("login_form.php");
		exit;
	}

	
	if( $clsUser->Skip2faFlg || strpos($clsUser->ID,'nespe') !== false) {
		if ($KeyChangeFlg)
			$clsUser->RegistKey = $clsUser->getNewKey();
		$clsUser->LastLogin = "NOW()";
		
		if (!$clsUser->executeUpdate())
			trigger_error("executeUpdate(clsUser) Failed.", E_USER_ERROR);

		$rKey = $clsUser->RegistKey;
		$ROM = $clsUser->Extra1;

		if ($m == 1 ){
			// $URL = _MAIN_URL . "s_d2_search.php?rKey=" . $rKey;#スマホ画面
			$URL = _MAIN_URL . "s_search.php?rKey=" . $rKey."&m=1";
		}else{
			$URL = _MAIN_URL . "s_search.php?rKey=" . $rKey;
		}

		header('Location: ' . $URL);
		exit;
	}

	########################################################
	# 2段階認証の処理
	########################################################
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
			$rKey = $clsUser->getNewKey();
			$clsUser->EmailVerificationCode = NULL; // 確認コードをクリア
			$clsUser->EmailVerificationExpiry = NULL; // 有効期限もクリア
			$clsUser->RegistKey = $rKey;
			$clsUser->LastLogin = "NOW()";
			$clsUser->LastLoginIP = $ipAddress;

			if (!$clsUser->executeUpdate("", $clsUser->UserCD)) {
				trigger_error("Updating User Failed.", E_USER_ERROR);
			}

			if ($m == 1 ){
				// $URL = _MAIN_URL . "s_d2_search.php?rKey=" . $rKey;#スマホ画面
				$URL = _MAIN_URL . "s_search.php?rKey=" . $rKey."&m=1";
			}else{
				$URL = _MAIN_URL . "s_search.php?rKey=" . $rKey;
			}
	
			header('Location: ' . $URL);
			exit;
		}
		
	}
	else {

		$verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
		$codeExpiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));
		$clsUser->EmailVerificationCode = $verificationCode;
		$clsUser->EmailVerificationExpiry = $codeExpiry;
		$clsUser->RegistKey = ''; // 2段階認証成功までRegistKeyは空にする

		if (!$clsUser->executeUpdate("", $clsUser->UserCD)) {
			trigger_error("Updating User Failed.", E_USER_ERROR);
		}

		$url = _ROOT_URL . "login_form.php"; // メール内に記載のURL
		// 2段階認証メールを送信
		sendTwoFactorAuthMail($clsUser->Address1, $verificationCode, $codeExpiry, $url);
	}

	$IfError = FALSE;;
	if( $ErrorString != '' ) {
		$IfError = TRUE;
	}

	$IfMagager = TRUE;
	$CNT_FILE = $CNT_FILE = "login_2fa.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	$HiddenValues = $myTemplate->getValuesToPass();
	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	$myDB->close();

	unset($myTemplate);
	unset($myLog);

	########################################################
	# コンテンツ表示
	########################################################

	// if ($m == 1 ){
	// 	// $URL = _MAIN_URL . "s_d2_search.php?rKey=" . $rKey;#スマホ画面
	// 	$URL = _MAIN_URL . "s_search.php?rKey=" . $rKey."&m=1";
	// }else{
	// 	$URL = _MAIN_URL . "s_search.php?rKey=" . $rKey;
	// }

	// header('Location: ' . $URL);
	// exit;

?>
