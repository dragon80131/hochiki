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
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	#include_once _CLS_DIR . "SPUSGyosyaTanto.cls";

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 値取得
	########################################################

	$editGyosyaTantoCD = SPFWParameter::getValues('editGyosyaTantoCD');

	########################################################
	# 入力チェック
	########################################################
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
	$ClientCD = $myUser->ClientCD;
	$UserKbn = $myUser->UserKbn;
	$loginID = $myUser->ID;
	$UserType = $myUser->UserType;
	
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
	# 新規でない場合:URL抽出処理
	########################################################
	$IfRegist = false;
	$IfUpdate = true;
	if ($editGyosyaTantoCD > 0){

		$myUser = new User($myDB);

		if (!$myUser->executeSelect("UserCD = " . $editGyosyaTantoCD." AND MukouFlg = FALSE", "") || $myUser->RecCnt == 0){
			trigger_error("Getting User Failed.", E_USER_ERROR);
		}

		$wID = $myUser->ID;
		$wPasswd = $myUser->Passwd;
		$wUserCD = $myUser->UserCD;
		$wGyosyaTantoName = $myUser->LastName;
		$wGyosyaTantoNameKana = $myUser->LastNameKana;
		$wGyosyaCD = $myUser->Extra5;
		$wSitenEigyoshoName = $myUser->Extra6;
		$wUserType = $myUser->Extra7;
		$wGyosyaTantoTEL = $myUser->TEL;
		$wGyosyaTantoKeitai = $myUser->Address3;
		$wGyosyaTantoMail = $myUser->Address1;
		$wGyosyaTantoMail2 = $myUser->Address2;
		$wGyosyaTantoNotes = $myUser->Notes;

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

		unset($myUser);

	} else {
		$editGyosyaTantoCD = "-1";
		$IfRegist = true;
		$IfUpdate = false;
	}
	########################################################
	# 施工業者名の一覧表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "GyosyaCD, ";#営業所CD
	$sql .= "GyosyaName ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tGyosyaM";
	$sql .= " WHERE MukouFlg = FALSE";
	$sql .= " AND ClientCD = ".$ClientCD;
	$myListObject->Condition = $sql;
	$myListObject->Order = "CAST( GyosyaNameKana as BINARY ) ";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1))) {
		trigger_error("Getting Gyosya List Failed.", E_USER_ERROR);
	}

	$GyosyaLoop = $myListObject->Rows;
	for ($i = 0; $i < $GyosyaLoop; $i++) {
		$GyosyaCD[$i] = $myListObject->GetValue($i, 0);
		$GyosyaName[$i] = $myListObject->GetValue($i, 1);
		if(  $GyosyaCD[$i] == $wGyosyaCD ){
			$GyosyaSelected[$i] = ' selected' ;#業者selected
		}
	}
	########################################################
	# コンテンツ表示
	########################################################
	SPFWTemplate::setValue("work");
	#$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") .".tpl";
	$CNT_FILE = "s_gyosyatanto_detail.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
