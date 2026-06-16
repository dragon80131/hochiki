<?php

	include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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

	// if ($rKey == NULL)
	// 	showSorryPage(_ILLEGAL_ACCESS);

	// if (!$myUser->doAuthenticationByRegistKey($rKey))
	// 	trigger_error("doAuthentication Failed.", E_USER_ERROR);

	// if ($myUser->UserCD == -1)
	// 	showSorryPage(_ILLEGAL_ACCESS);
	// $UserCD 		= $myUser->UserCD;
	// #	$Extra1 		= $myUser->EigyosyoCD ;#幹事企業拠点CD
	// #	$MyZokusei 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
	// #	$MyEigyoshoCD	= $myUser->GyosyaCD ;#協力業者CD

	// $ClientCD 		= $myUser->ClientCD ;#幹事企業CD
	// $EigyosyoCD 		= $myUser->EigyosyoCD ;#幹事企業支店・営業所CD
	// $UserKbn 		= $myUser->UserKbn ;#1:幹事企業一般 2:管理者 3:協力業者CD
	// $GyosyaCD	= $myUser->GyosyaCD ;#協力業者CD


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
	# コンテンツ表示
	########################################################
	$CNT_FILE = "s_Taio_Exceed_UketsukeDate.tpl";

	$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues 	= $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);


?>
