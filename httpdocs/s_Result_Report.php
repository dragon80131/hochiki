<?php

	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";

	include_once _CLS_DIR . "SPUSResultReport.cls";

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 値取得
	########################################################
	$rKey 			= SPFWParameter::getValues("rKey");
	$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
	########################################################
	# 認証動作
	########################################################
	$myUser = new User($myDB);

	if($rKey){
		$IfrKey = true;
	}

	$myBukken = new Bukken($myDB);
	if (!$myBukken->executeSelect(" BukkenCD = '" . $editBukkenCD . "' AND MukouFlg = FALSE", "")) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}
	$today = date("Y-m-d");

	$myResultReport = new ResultReport($myDB);
	if (!$myResultReport->executeSelect(" BukkenCD = '" . $editBukkenCD . "'", "")) {
		trigger_error("Getting ResultReport Failed.", E_USER_ERROR);
	}

	$ZyushinDengenChecked = Addcheck($myResultReport->ZyushinDengen);
	$RendoubanConfirmChecked = Addcheck($myResultReport->RendoubanConfirm);
	$ZyushinConfirmChecked = Addcheck($myResultReport->ZyushinConfirm);
	$HukuZyushinConfirmChecked = Addcheck($myResultReport->HukuZyushinConfirm);
	$KeibiCompanyChecked = Addcheck($myResultReport->KeibiCompany);
	$SenyouKairoConfirmChecked = Addcheck($myResultReport->SenyouKairoConfirm);
	$Biko = $myResultReport->Biko;
	if($myResultReport->ResultReport == "1"){
		$ResultReportSelected1 = "checked";
	}else if($myResultReport->ResultReport == "2"){
		$ResultReportSelected2 = "checked";
	}

	$ZikahouChecked = JudgeExistKoumoku($myResultReport->ZyushinDengen, $myResultReport->RendoubanConfirm, $myResultReport->ZyushinConfirm, $myResultReport->HukuZyushinConfirm,$myResultReport->KeibiCompany,$myResultReport->SenyouKairoConfirm);

	$PumpConfirmChecked = Addcheck($myResultReport->PumpConfirm);
	$AirPositionConfirmChecked = Addcheck($myResultReport->AirPositionConfirm);
	$WaterKentiConfirmChecked = Addcheck($myResultReport->WaterKentiConfirm);
	$WaterShingouConfirmChecked = Addcheck($myResultReport->WaterShingouConfirm);
	$AirAtsuryokuConfirmChecked = Addcheck($myResultReport->AirAtsuryokuConfirm);
	$HaisuiConfirmChecked = Addcheck($myResultReport->HaisuiConfirm);
	$SeigyoDengenConfirm = Addcheck($myResultReport->SeigyoDengenConfirm);
	$SenyouKairoConfirmForSPSetsubiChecked = Addcheck($myResultReport->SenyouKairoConfirmForSPSetsubi);

	$SPSetsubiChecked = JudgeExistKoumoku($myResultReport->PumpConfirm, $myResultReport->AirPositionConfirm, $myResultReport->WaterKentiConfirm, $myResultReport->WaterShingouConfirm,$myResultReport->AirAtsuryokuConfirm,$myResultReport->HaisuiConfirm,$myResultReport->SeigyoDengenConfirm,$myResultReport->SenyouKairoConfirmForSPSetsubi);
	
	$NozuruConfirmChecked = Addcheck($myResultReport->NozuruConfirm);
	$HousyutsuConfirmChecked = Addcheck($myResultReport->HousyutsuConfirm);
	$CleaningConfirmChecked = Addcheck($myResultReport->CleaningConfirm);

	$IdoushikiChecked = JudgeExistKoumoku($myResultReport->NozuruConfirm, $myResultReport->HousyutsuConfirm, $myResultReport->CleaningConfirm);

	$HontaiDengenConfirmChecked = Addcheck($myResultReport->HontaiDengenConfirm);
	$HizyouDengenForKasaiChecked = Addcheck($myResultReport->HizyouDengenForKasai);
	$TellKaisenConfirmChecked = Addcheck($myResultReport->TellKaisenConfirm);

	$KasaiTuhouChecked = JudgeExistKoumoku($myResultReport->HontaiDengenConfirm, $myResultReport->HizyouDengenForKasai, $myResultReport->TellKaisenConfirm);

	$DoukanSetsuzokuChecked = Addcheck($myResultReport->DoukanSetsuzoku);
	$HizyouDengenForGasuSetsubiChecked = Addcheck($myResultReport->HizyouDengenForGasuSetsubi);
	$KidouSetsuzokuChecked = Addcheck($myResultReport->KidouSetsuzoku);

	$GasuSetsubiChecked = JudgeExistKoumoku($myResultReport->DoukanSetsuzoku, $myResultReport->HizyouDengenForGasuSetsubi, $myResultReport->KidouSetsuzoku);



	// if ($rKey == NULL)
	// 	showSorryPage(_ILLEGAL_ACCESS);
	
	// if (!$myUser->doAuthenticationByRegistKey($rKey))
	// 	trigger_error("doAuthentication Failed.", E_USER_ERROR);
	
	// if ($myUser->UserCD == -1)
	// 	showSorryPage(_ILLEGAL_ACCESS);
	// $UserCD 		= $myUser->UserCD;

	unset($myUser);

	########################################################
	# 担当者リスト表示
	########################################################
	// $myListObject = new SPFWListObject($myDB);

	// $sql = "SELECT ";
	// $sql .= "UserCD, ";
	// $sql .= "LastName ";
	// $myListObject->SelectSQL = $sql;

	// $sql = " FROM tUserM";
	// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
	// 	$sql .= " WHERE MukouFlg = FALSE AND ClientCD = ".$ClientCD ;
	// 	#$sql .= " AND EigyosyoCD = ".$EigyosyoCD; #営業所のみ
	// }
	// $myListObject->Condition	= $sql;
	// $myListObject->Order 		= "LastNameKana,LastName ";
	// $myListObject->Limit 		= "allpage";

	// if (!($myListObject->GetList(1)))
	// 	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	// $TantoLoop = $myListObject->Rows;
	// for($i=0; $i< $TantoLoop; $i++){
	// 	$TantoCD[$i] 	= $myListObject->GetValue($i, 0);
	// 	$TantoName[$i]	= $myListObject->GetValue($i, 1);
	// 	$TantoSoeji[$TantoCD[$i]] = $i;
	// }
	// unset($myListObject);

	########################################################
	# 施工業者担当者
	########################################################
	// $myListObject = new SPFWListObject($myDB);

	// $sql = "SELECT ";
	// $sql .= "gt.UserCD, ";		#0業者担当CD
	// $sql .= "gt.LastName, ";	#1業者担当名
	// $sql .= "gt.GyosyaCD, ";		#2業者CD
	// $sql .= "gt.TEL, ";			#3業者担当TEL
	// $sql .= "gt.Address3, ";	#4業者担当携帯
	// $sql .= "gt.EMail, ";		#5業者担当メールアドレス
	// $sql .= "gt.Address3, ";	#6  2こめのメールアドレス
	// $sql .= "gt.Notes, ";		#7備考
	// $sql .= "g.GyosyaName, ";	#8業者名
	// $sql .= "g.ShozokuCD ";		#9管轄支店　|3|4|5|となっている。
	// $myListObject->SelectSQL = $sql;
	// $sql = " FROM tUserM gt , tGyosyaM g ";
	// $sql .= " WHERE gt.GyosyaCD = g.GyosyaCD AND gt.MukouFlg = FALSE AND UserKbn = 3 "; //

	// #if($MyZokusei == 1) { #一般ユーザは自分の所属のみ
	// #	$sql .= " AND g.ShozokuCD like '%".$Extra1."%'  ";
	// #}

	// $myListObject->Condition	= $sql;
	// $myListObject->Order 		= "CAST( g.GyosyaNameKana as BINARY ) ";#表示順
	// $myListObject->Limit 		= "allpage";

	// if (!($myListObject->GetList(1)))
	// 	trigger_error("Getting User List Failed.", E_USER_ERROR);

	// $GyosyaTantoLoop = $myListObject->Rows;
	// for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
	// 	$GyosyaTantoCD[$i] 		= $myListObject->GetValue($i, 0);
	// 	$GyosyaTantoName[$i]	= $myListObject->GetValue($i, 1);
	// 	$GyosyaName[$i] 		= $myListObject->GetValue($i, 8);
	// 	$GyosyaKey[$GyosyaTantoCD[$i]] = $i;
	// }
	// unset($myListObject);

	########################################################
	# 施工会社情報取得
	########################################################
	// $myListObject = new SPFWListObject($myDB);

	// $sql = "SELECT ";
	// $sql .= "GyosyaCD, ";
	// $sql .= "GyosyaName ";
	// $myListObject->SelectSQL = $sql;

	// $sql = " FROM tGyosyaM ";
	// // if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
	// $sql .= " WHERE MukouFlg = FALSE AND ClientCD = ".$ClientCD ;
	// // }
	// $myListObject->Condition	= $sql;
	// $myListObject->Order 		= "";
	// $myListObject->Limit 		= "allpage";

	// if (!($myListObject->GetList(1)))
	// 	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	// $SekoCompanyLoop = $myListObject->Rows;
	// for($i=0; $i< $SekoCompanyLoop; $i++){
	// 	$GyosyaCD[$i] 	= $myListObject->GetValue($i, 0);
	// 	$GyosyaName[$i]	= $myListObject->GetValue($i, 1);
	// }
	// unset($myListObject);
	########################################################
	# 物件情報取得（編集）
	########################################################

	// $myBukken = new Bukken($myDB);

	// if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1){
	// 	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	// }

	// $wKenmeiNo 				= $myBukken->KenmeiNo;
	// $wBukkenName 			= $myBukken->BukkenName;
	// $wBukkenName_Hurigana	= $myBukken->BukkenName_Hurigana;
	// $wAddress 				= $myBukken->Address;

	// $wBukkenMemo 				= $myBukken->BukkenMemo;

	// if($myBukken->TantoCD){
	// 	$tempTantoCD 			= $myBukken->TantoCD; #入力されていない。
	// 	$TantoCDSelected[$TantoSoeji[$tempTantoCD]] = " selected ";
	// }
	// // 所属支店
	// $ShozokuCD 				= $myBukken->ShozokuCD;
	// //$SitenName = getSitenData($myDB, $ShozokuCD); // include/common.php

	// $wKosu 					= $myBukken->Kosu;
	// $wKaidaka 				= $myBukken->Kaidaka;

	// $wKanriGaisya 			= $myBukken->KanriGaisya;
	// $wOwner_name 			= $myBukken->Owner_name;
	// $wKanriGaisyaTanto 		= $myBukken->KanriGaisyaTanto;
	// $wKanriGaisyaTEL 		= $myBukken->KanriGaisyaTEL;

	// unset($myBukken);

	########################################################
	# 関数
	########################################################
	function Addcheck($val){
		if($val == "on"){
			return "checked";
		}

		return false;
	}
	function JudgeExistKoumoku(...$names){
		foreach($names as $name){
			if($name == "on"){
				return "checked";
			}
		}
		return false;
	}

	########################################################
	# コンテンツ表示
	########################################################
	$CNT_FILE = "s_Result_Report.tpl";

	$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues 	= $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);


?>
