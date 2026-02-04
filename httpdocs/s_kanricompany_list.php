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
	include_once _CLS_DIR . "SPUSKanriCompany.cls";

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
		if ( $wKanriCompanyName == NULL ) $ErrorString[] = "管理会社名は必須項目です。";
		if ( $wKanriCompanyNameKana == NULL ) $ErrorString[] = "管理会社名ふりがなは必須項目です。";
		if ( $wCompanyTEL == NULL ) $ErrorString[] = "電話番号は必須項目です。";

		if (count($ErrorString) > 0) {
			$ErrorLoop = count($ErrorString);
			include_once("s_kanricompany_detail.php");
			exit;
		}

		$myKanriCompany = new KanriCompany($myDB);

		if ($editKanriCompanyCD > 0){
			if (!$myKanriCompany->executeSelect("KanriCompanyCD = " . $editKanriCompanyCD, "") || $myKanriCompany->RecCnt != 1){
				trigger_error("Getting KanriCompany Failed.", E_USER_ERROR);
			}
			$myKanriCompany->KanriCompanyCD = $editKanriCompanyCD;

		} else {
			$myKanriCompany->KanriCompanyCD = -1;
			$myKanriCompany->Creator = $UserCD;
		}

		$myKanriCompany->ClientCD = $ClientCD;
		$myKanriCompany->KanriCompanyName = $wKanriCompanyName;
		$myKanriCompany->KanriCompanyNameKana = $wKanriCompanyNameKana;
		$myKanriCompany->CompanyTEL = $wCompanyTEL;
		$myKanriCompany->Notes = $wNotes;
		$myKanriCompany->Updater = $UserCD;

		if (!$myKanriCompany->executeUpdate()){
			trigger_error("Updating KanriCompany Failed.", E_USER_ERROR);
		}
		unset($myKanriCompany);


		// リダイレクト
		$URL = _MAIN_URL . "s_kanricompany_list.php?rKey=" . $rKey;
		// header('Location: ' . $URL);
		// exit;

	}else if ($work == 2){

		$myKanriCompany = new KanriCompany($myDB);

		$Condition = "KanriCompanyCD = " . $editGyosyaCD;

		if (!$myKanriCompany->executeSelect($Condition, NULL)){
			trigger_error("Getting KanriCompany Failed.", E_USER_ERROR);
		}
		if ($myKanriCompany->RecCnt == 1) {
			$myKanriCompany->MukouFlg = TRUE;
			$myKanriCompany->Updater = $UserCD;

			if ( !$myKanriCompany->executeUpdate()){
				trigger_error("Updating KanriCompany Failed.", E_USER_ERROR);
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
	# 管理会社一覧表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "KanriCompanyCD, ";
	$sql .= "KanriCompanyName, ";
	$sql .= "CompanyTEL, ";
	$sql .= "Notes ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tKanriCompanyM";

	$sql .= " WHERE MukouFlg = FALSE AND ClientCD = '".$ClientCD."' ";#自分ところだけ表示

	$myListObject->Condition = $sql;
	$myListObject->Order = "";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Gyosya List Failed.", E_USER_ERROR);

	$KanriCompanyLoop = $myListObject->Rows;
	for ($i = 0; $i < $KanriCompanyLoop; $i++) {
		$KanriCompanyCD[$i] = $myListObject->GetValue($i, 0);
		$KanriCompanyName[$i] = $myListObject->GetValue($i, 1);
		$CompanyTEL[$i] = $myListObject->GetValue($i, 2);
		$Notes[$i] = $myListObject->GetValue($i, 3);
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
