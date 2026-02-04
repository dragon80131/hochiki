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
	$editGyosyaCD = $myUser->Extra5;#業者CD

	$UserKbn = $myUser->UserKbn;
	$loginID = $myUser->ID;
	$UserType = $myUser->UserType;

	$IfKanrishaFlg = ( $myUser->Extra3 == "2" )? "TRUE":"" ;
	unset($myUser);

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
	$wGyosyaKanriNo = SPFWParameter::getValues('wGyosyaKanriNo');
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
		if ( $wGyosyaName == NULL ) $ErrorString[] = "施工業者名は必須項目です。";
		if ( $wGyosyaNameKana == NULL ) $ErrorString[] = "業者名ふりがなは必須項目です。";
		if ( $wGyosyaTEL == NULL ) $ErrorString[] = "電話番号は必須項目です。";
		// if ( $GyosyaMail == NULL ) $ErrorString[] = "メールアドレスは必須項目です。";

		if (count($ErrorString) > 0) {
			$ErrorLoop = count($ErrorString);
			include_once("s_gyosya_detail.php");
			exit;
		}

		$myGyosya = new Gyosya($myDB);

		if ($editGyosyaCD > 0){
			if (!$myGyosya->executeSelect("GyosyaCD = " . $editGyosyaCD, "") || $myGyosya->RecCnt != 1){
				trigger_error("Getting Gyosya Failed.", E_USER_ERROR);
			}
			$myGyosya->GyosyaCD = $editGyosyaCD;

		} else {
			$myGyosya->GyosyaCD = -1;
			$myGyosya->Creator = $UserCD;
		}

		$myGyosya->ClientCD = $ClientCD;
		$myGyosya->GyosyaName = $wGyosyaName;
		$myGyosya->GyosyaNameKana = $wGyosyaNameKana;
		#$myGyosya->ShozokuCD = SPFWTools::encodePluralValue($wShozokuCD);#|3|4|5｜とはいってる
		$myGyosya->GyosyaTEL = $wGyosyaTEL;
		$myGyosya->GyosyaPasswd = $wGyosyaPasswd;
		$myGyosya->GyosyaNotes = $wGyosyaNotes;

		$myGyosya->Updater = $UserCD;

		// $myGyosya->GyosyaMail = $GyosyaMail;
		// $myGyosya->GyosyaMail2 = $GyosyaMail2;

		// $myGyosya->Color = $wColor;

		$myGyosya->IsSyoubou = $IsSyoubou;
		$myGyosya->IsBouka = $IsBouka;

		if (!$myGyosya->executeUpdate()){
			trigger_error("Updating Gyosya Failed.", E_USER_ERROR);
		}
		unset($myGyosya);


		// リダイレクト
		$URL = _MAIN_URL . "s_gyosya_list.php?rKey=" . $rKey;
		// header('Location: ' . $URL);
		// exit;

	}else if ($work == 2){

		$myGyosya = new Gyosya($myDB);

		$Condition = "GyosyaCD = " . $editGyosyaCD;

		if (!$myGyosya->executeSelect($Condition, NULL)){
			trigger_error("Getting Gyosya Failed.", E_USER_ERROR);
		}
		if ($myGyosya->RecCnt == 1) {
			$myGyosya->MukouFlg = TRUE;
			$myGyosya->Updater = $UserCD;

			if ( !$myGyosya->executeUpdate()){
				trigger_error("Updating Gyosya Failed.", E_USER_ERROR);
			}
		}
		$IfDelete = TRUE;
		SPFWTemplate::dropValue("editGyosyaCD");
	}

	SPFWTemplate::dropValue('wGyosyaCD');
	SPFWTemplate::dropValue('wGyosyaName');
	SPFWTemplate::dropValue('wGyosyaNameKana');
	SPFWTemplate::dropValue('wGyosyaNotes');

	########################################################
	# 施工業者一覧表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "GyosyaCD, ";
	$sql .= "GyosyaName, ";
	$sql .= "GyosyaNameKana, ";
	$sql .= "ShozokuCD, ";#|3|4|のようになっている
	$sql .= "GyosyaTEL, ";
	$sql .= "GyosyaPasswd, ";
	$sql .= "GyosyaNotes, ";
	$sql .= "GyosyaKanriNo, ";
	$sql .= "GyosyaMail ";


	$myListObject->SelectSQL = $sql;
	$sql = " FROM tGyosyaM";
	if( $Extra == "2"){
		$sql .= " WHERE GyosyaCD > 0 AND MukouFlg = FALSE";
	}else{
		// $sql .= " WHERE GyosyaCD > 0 AND MukouFlg = FALSE AND GyosyaCD = '".$editGyosyaCD."' ";#自分ところだけ表示
		$sql .= " WHERE GyosyaCD > 0 AND MukouFlg = FALSE AND ClientCD = '".$ClientCD."' ";#自分ところだけ表示
	}
	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Gyosya List Failed.", E_USER_ERROR);

	$GyosyaListLoop = $myListObject->Rows;
	for ($i = 0; $i < $GyosyaListLoop; $i++) {
		$GyosyaCD[$i] = $myListObject->GetValue($i, 0);
		$GyosyaName[$i] = $myListObject->GetValue($i, 1);
		$GyosyaNameKana[$i] = $myListObject->GetValue($i, 2);

		$GyosyaTEL[$i] = $myListObject->GetValue($i, 4);
		$GyosyaPasswd[$i] =  $myListObject->GetValue($i, 5);
		$GyosyaNotes[$i] =  $myListObject->GetValue($i, 6);

		$wGyosyaMail[$i] = $myListObject->GetValue($i, 8);


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
