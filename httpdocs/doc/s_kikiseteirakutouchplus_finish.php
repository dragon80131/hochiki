<?php
#らくタッチ　集合玄関機設定指示書　住宅情報盤設定指示書　出力

	include_once "setting.properties";
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
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSShiryo.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSKikiSettei.cls";


	include_once  "../include/common.php";



	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$rKey = SPFWParameter::getValues('rKey');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	########################################################
	# 認証動作
	########################################################
	#echo $editBukkenCD;

	$myUser = new User($myDB);

	if ($rKey == NULL) 
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey)) 
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) 
		showSorryPage(_ILLEGAL_ACCESS);

	$UserCD = $myUser->UserCD;
	$ID = $myUser->ID;
	unset($myUser);
	$TantoTEL = getTantoData($myDB, $UserCD )['TEL'];


	########################################################
	# 物件情報抽出
	########################################################
	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);

	} else {
	#必要な項目　マンション名、工事名称、住所、戸数、管理会社、消防特例、管理会社、管理員関係

		$KanriGaisya = $myBukken->KanriGaisya;
		$BukkenName = $myBukken->BukkenName;


	}
	
	########################################################
	# パラメータ取得
	########################################################
	
	for($i=1 ; $i < 53 ; $i++){
		if($i<22)
			${"wShugoSettei".$i} = SPFWParameter::getValues("wShugoSettei".$i);
		if($i<52)
			${"wOyakiSettei".$i} = SPFWParameter::getValues("wOyakiSettei".$i);
		if($i<13)
			${"wJutakuMenuSettei".$i} = SPFWParameter::getValues("wJutakuMenuSettei".$i);
		if($i<53)
			${"wJutakuServiceSettei".$i} = SPFWParameter::getValues("wJutakuServiceSettei".$i);
		if($i<10)
			${"wJutakuSistemSettei".$i} = SPFWParameter::getValues("wJutakuSistemSettei".$i);
		if($i<6)
			${"wJutakuKeihouSettei".$i} = SPFWParameter::getValues("wJutakuKeihouSettei".$i);
		if($i<23)
			${"wJutakuKanriSettei".$i} = SPFWParameter::getValues("wJutakuKanriSettei".$i);
		if($i<11)
			${"wJutakuBouhanSettei".$i} = SPFWParameter::getValues("wJutakuBouhanSettei".$i);
		if($i<2)
			${"wGenkanKokiSettei".$i} = SPFWParameter::getValues("wGenkanKokiSettei".$i);
		if($i<5)
			${"wSonotaSettei".$i} = SPFWParameter::getValues("wSonotaSettei".$i);
	}
		for($i=0; $i<count($wOyakiSettei44);$i++ ){
			if($i==0)
				$wOyakiSettei44Name = $IHOUTANSINAME[$wOyakiSettei44[$i]];
			elseif($i%5==0)
				$wOyakiSettei44Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei44[$i]];
			else
				$wOyakiSettei44Name .= ",".$IHOUTANSINAME[$wOyakiSettei44[$i]];
		}
		for($i=0; $i<count($wOyakiSettei45);$i++ ){
			if($i==0)
				$wOyakiSettei45Name = $IHOUTANSINAME[$wOyakiSettei45[$i]];
			elseif($i%5==0)
				$wOyakiSettei45Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei45[$i]];
			else
				$wOyakiSettei45Name .= ",".$IHOUTANSINAME[$wOyakiSettei45[$i]];
		}
		for($i=0; $i<count($wOyakiSettei46);$i++ ){
			if($i==0)
				$wOyakiSettei46Name = $IHOUTANSINAME[$wOyakiSettei46[$i]];
			elseif($i%5==0)
				$wOyakiSettei46Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei46[$i]];
			else
				$wOyakiSettei46Name .= ",".$IHOUTANSINAME[$wOyakiSettei46[$i]];
		}
		for($i=0; $i<count($wOyakiSettei47);$i++ ){
			if($i==0)
				$wOyakiSettei47Name = $IHOUTANSINAME[$wOyakiSettei47[$i]];
			elseif($i%5==0)
				$wOyakiSettei47Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei47[$i]];
			else
				$wOyakiSettei47Name .= ",".$IHOUTANSINAME[$wOyakiSettei47[$i]];
		}
		for($i=0; $i<count($wOyakiSettei48);$i++ ){
			if($i==0)
				$wOyakiSettei48Name = $IHOUTANSINAME[$wOyakiSettei48[$i]];
			elseif($i%5==0)
				$wOyakiSettei48Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei48[$i]];
			else
				$wOyakiSettei48Name .= ",".$IHOUTANSINAME[$wOyakiSettei48[$i]];
		}
		for($i=0; $i<count($wOyakiSettei49);$i++ ){
			if($i==0)
				$wOyakiSettei49Name = $IHOUTANSINAME[$wOyakiSettei49[$i]];
			elseif($i%5==0)
				$wOyakiSettei49Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei49[$i]];
			else
				$wOyakiSettei49Name .= ",".$IHOUTANSINAME[$wOyakiSettei49[$i]];
		}
		for($i=0; $i<count($wOyakiSettei50);$i++ ){
			if($i==0)
				$wOyakiSettei50Name = $IHOUTANSINAME[$wOyakiSettei50[$i]];
			elseif($i%5==0)
				$wOyakiSettei50Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei50[$i]];
			else
				$wOyakiSettei50Name .= ",".$IHOUTANSINAME[$wOyakiSettei50[$i]];
		}
		for($i=0; $i<count($wOyakiSettei51);$i++ ){
			if($i==0)
				$wOyakiSettei51Name = $IHOUTANSINAME[$wOyakiSettei51[$i]];
			elseif($i%5==0)
				$wOyakiSettei51Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei51[$i]];
			else
				$wOyakiSettei51Name .= ",".$IHOUTANSINAME[$wOyakiSettei51[$i]];
		}
		for($i=0; $i<count($wJutakuServiceSettei1);$i++ ){
			if($i==0)
				$wJutakuServiceSettei1Name = $SERVICENAME[$wJutakuServiceSettei1[$i]];
			elseif($i%5==0)
				$wJutakuServiceSettei1Name .= "<br>,".$SERVICENAME[$wJutakuServiceSettei1[$i]];
			else
				$wJutakuServiceSettei1Name .= ",".$SERVICENAME[$wJutakuServiceSettei1[$i]];
		}
		for($i=0; $i<count($wJutakuServiceSettei2);$i++ ){
			if($i==0)
				$wJutakuServiceSettei2Name = $SERVICENAME[$wJutakuServiceSettei2[$i]];
			elseif($i%5==0)
				$wJutakuServiceSettei2Name .= "<br>,".$SERVICENAME[$wJutakuServiceSettei2[$i]];
			else
				$wJutakuServiceSettei2Name .= ",".$SERVICENAME[$wJutakuServiceSettei2[$i]];
		}
		for($i=0; $i<count($wJutakuServiceSettei3);$i++ ){
			if($i==0)
				$wJutakuServiceSettei3Name = $SERVICENAME[$wJutakuServiceSettei3[$i]];
			elseif($i%5==0)
				$wJutakuServiceSettei3Name .= "<br>,".$SERVICENAME[$wJutakuServiceSettei3[$i]];
			else
				$wJutakuServiceSettei3Name .= ",".$SERVICENAME[$wJutakuServiceSettei3[$i]];
		}
		for($i=0; $i<count($wJutakuServiceSettei4);$i++ ){
			if($i==0)
				$wJutakuServiceSettei4Name = $SERVICENAME[$wJutakuServiceSettei4[$i]];
			elseif($i%5==0)
				$wJutakuServiceSettei4Name .= "<br>,".$SERVICENAME[$wJutakuServiceSettei4[$i]];
			else
				$wJutakuServiceSettei4Name .= ",".$SERVICENAME[$wJutakuServiceSettei4[$i]];
		}

		for($i=44 ;$i<52 ;$i++ )
			${"wOyakiSettei".$i."Value"} = SPFWTools::encodePluralValue(${"wOyakiSettei".$i});
		for($i=1 ;$i<5 ;$i++ )
			${"wJutakuServiceSettei".$i."Value"} = SPFWTools::encodePluralValue(${"wJutakuServiceSettei".$i});

		#echo $wOyakiSettei44Value;
	########################################################
	# DB連携
	########################################################

	$myKikiSettei = new KikiSettei($myDB);

	#初期値を取得
	if(!$myKikiSettei->executeSelect("KikiSetteiCD = 1",""))
		trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR);

	for($i=1 ;$i<53 ;$i++ ){
		if($i<22)
			${"dShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};
		if($i<52)
			${"dOyakiSettei".$i} = $myKikiSettei->{"OyakiSettei".$i};
		if($i<13)
			${"dJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};
		if($i<53)
			${"dJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};
		if($i<10)
			${"dJutakuSistemSettei".$i} = $myKikiSettei->{"JutakuSistemSettei".$i};
		if($i<6)
			${"dJutakuKeihouSettei".$i} = $myKikiSettei->{"JutakuKeihouSettei".$i};
		if($i<23)
			${"dJutakuKanriSettei".$i} = $myKikiSettei->{"JutakuKanriSettei".$i};
		if($i<11)
			${"dJutakuBouhanSettei".$i} = $myKikiSettei->{"JutakuBouhanSettei".$i};
		if($i<2)
			${"dGenkanKokiSettei".$i} = $myKikiSettei->{"GenkanKokiSettei".$i};
		if($i<5)
			${"dSonotaSettei".$i} = $myKikiSettei->{"SonotaSettei".$i};
	}

	unset($myKikiSettei);
	
	########################################################
	# DB格納
	########################################################

	$myKikiSettei = new KikiSettei($myDB);
	
	#物件CDと機器CD（らくタッチPlus=1）を指定してデータを取得
	if($myKikiSettei->executeSelect("BukkenCD = ".$editBukkenCD." AND KikiCD=1","")){
		$wKikiSetteiCD = $myKikiSettei->KikiSetteiCD;
	}

	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting KikiSetteiCD Failed.", E_USER_ERROR);
	}

	unset($myKikiSettei);

	$myKikiSettei = new KikiSettei($myDB);
	
	if($wKikiSetteiCD > 0){
		if (!$myKikiSettei->executeSelect("KikiSetteiCD = " . $wKikiSetteiCD, "") || $myKikiSettei->RecCnt != 1)
			trigger_error("Getting KikiSettei Failed.", E_USER_ERROR);
	}else{
		$myKikiSettei->KikiSetteiCD = -1;
		$myKikiSettei->Creator = $UserCD;
		
	}
		$myKikiSettei->KikiCD = 1;#らくタッチPlus
		$myKikiSettei->BukkenCD = $editBukkenCD;
		

	for($i=1 ; $i<53 ;$i++){
		if($i<22)
			$myKikiSettei->{"ShugoSettei".$i} = ${"wShugoSettei".$i};
		if($i > 43 && $i < 52)
			$myKikiSettei->{"OyakiSettei".$i} = ${"wOyakiSettei".$i."Value"};
		elseif($i<52)
			$myKikiSettei->{"OyakiSettei".$i} = ${"wOyakiSettei".$i};
		if($i<13)
			$myKikiSettei->{"JutakuMenuSettei".$i} = ${"wJutakuMenuSettei".$i};
		if($i<5)
			$myKikiSettei->{"JutakuServiceSettei".$i} = ${"wJutakuServiceSettei".$i."Value"};
		else
			$myKikiSettei->{"JutakuServiceSettei".$i} = ${"wJutakuServiceSettei".$i};
		if($i<10)
			$myKikiSettei->{"JutakuSistemSettei".$i} = ${"wJutakuSistemSettei".$i};
		if($i<6)
			$myKikiSettei->{"JutakuKeihouSettei".$i} = ${"wJutakuKeihouSettei".$i};
		if($i<23)
			$myKikiSettei->{"JutakuKanriSettei".$i} = ${"wJutakuKanriSettei".$i};
		if($i<11)
			$myKikiSettei->{"JutakuBouhanSettei".$i} = ${"wJutakuBouhanSettei".$i};
		if($i<2)
			$myKikiSettei->{"GenkanKokiSettei".$i} = ${"wGenkanKokiSettei".$i};
		if($i<5)
			$myKikiSettei->{"SonotaSettei".$i} = ${"wSonotaSettei".$i};

	}
		$myKikiSettei->Updater = $UserCD;

	if(!$myKikiSettei->executeUpdate()){
		trigger_error("executeUpdate(myKikiSettei) Failed" , E_USER_ERROR);
	}



	unset($myKikiSettei);

	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_kikiseteirakutouchplus_finish.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);


?>
