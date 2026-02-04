<?php
#機器設定指示書 WISM7q 登録処理

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
		#施工設定[集合玄関機設定]
		if($i<7)
			${"wShugoSettei".$i} = SPFWParameter::getValues("wShugoSettei".$i);
		#メニュー画面の設定、カメラ付玄関子機[住宅情報盤設定]
		if($i<5)
			${"wJutakuMenuSettei".$i} = SPFWParameter::getValues("wJutakuMenuSettei".$i);
		#施工設定(サービス　防犯、玄関、窓、セット錠、トイレ、バス、部屋、コールなど[住宅情報盤設定]※1～４は未使用
		if($i<41 ||$i>4)
			${"wJutakuServiceSettei".$i} = SPFWParameter::getValues("wJutakuServiceSettei".$i);
		#施工設定（サービス）
		if($i<6)
			${"wJutakuServiceTSettei".$i} = SPFWParameter::getValues("wJutakuServiceTSettei".$i);
			
		#施工設定（住戸玄関の設定、管理室）[住宅情報盤]
		if($i<6)
			${"wJutakuKanriSettei".$i} = SPFWParameter::getValues("wJutakuKanriSettei".$i);

		#施工設定（その他）
		if($i<4)
			${"wSonotaSettei".$i} = SPFWParameter::getValues("wSonotaSettei".$i);
	}
		#施工設定（サービス）の配列の編集1～５
		for($i=0; $i<count($wJutakuServiceTSettei1);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei1Name = $SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei1[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei1Name .= "<br>,".$SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei1[$i]];
			else
				$wJutakuServiceTSettei1Name .= ",".$SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei1[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei2);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei2Name = $SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei2[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei2Name .= "<br>,".$SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei2[$i]];
			else
				$wJutakuServiceTSettei2Name .= ",".$SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei2[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei3);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei3Name = $SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei3[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei3Name .= "<br>,".$SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei3[$i]];
			else
				$wJutakuServiceTSettei3Name .= ",".$SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei3[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei4);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei4Name = $SERVICENAME_VIXUS1Pr3[$wJutakuServiceTSettei4[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei4Name .= "<br>,".$SERVICENAME_VIXUS1Pr3[$wJutakuServiceTSettei4[$i]];
			else
				$wJutakuServiceTSettei4Name .= ",".$SERVICENAME_VIXUS1Pr3[$wJutakuServiceTSettei4[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei5);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei5Name = $SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei5[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei5Name .= "<br>,".$SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei5[$i]];
			else
				$wJutakuServiceTSettei5Name .= ",".$SERVICENAME_VIXUS1Pr1[$wJutakuServiceTSettei5[$i]];
		}

		for($i=1 ;$i<6 ;$i++ )#★
			${"wJutakuServiceTSettei".$i."Value"} = SPFWTools::encodePluralValue(${"wJutakuServiceTSettei".$i});
		
		#echo $wOyakiSettei44Value;
		
	########################################################
	# DB連携
	########################################################

	$myKikiSettei = new KikiSettei($myDB);

	#機器CD=3(らくタッチ)
	if(!$myKikiSettei->executeSelect("KikiCD = 3 AND BukkenCD is NULL","")){
		trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR);
	}

	for($i=1 ;$i<53 ;$i++ ){
		#施工設定[集合玄関機設定]
		if($i<7)
			${"dShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};
		#メニュー画面、カメラ付玄関子機[住宅情報盤]
		if($i<5)
			${"dJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};	
		#施工設定（サービス）[住宅情報盤]
		if($i<6)
			${"dJutakuServiceTSettei".$i} = $myKikiSettei->{"JutakuServiceTSettei".$i};
		#施工設定(防犯、窓、玄関、トイレ、バス、部屋)[住宅情報盤]
		if($i<41)
			${"dJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};
		#施工設定（住戸玄関の設定）[住宅情報盤設定]
		if($i<6)
			${"dJutakuKanriSettei".$i} = $myKikiSettei->{"JutakuKanriSettei".$i};
		#施工設定（その他）[住宅情報盤設定]
		if($i<4)
			${"dSonotaSettei".$i} = $myKikiSettei->{"SonotaSettei".$i};
	}

	unset($myKikiSettei);
	
	########################################################
	# DB格納
	########################################################

	$myKikiSettei = new KikiSettei($myDB);
	#物件CD　機器CD=3:WISM7α　を指定してレコードを検索
	if($myKikiSettei->executeSelect("BukkenCD = ".$editBukkenCD." AND KikiCD = 3","")){
		#機器設定CD（主キー）を取得
		$wKikiSetteiCD = $myKikiSettei->KikiSetteiCD;
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
		$myKikiSettei->KikiCD = 3;#WISM7α
		$myKikiSettei->BukkenCD = $editBukkenCD;
		

	for($i=1 ; $i<53 ;$i++){
		#施工設定[集合玄関機設定]
		if($i<7)
			$myKikiSettei->{"ShugoSettei".$i} = ${"wShugoSettei".$i};

		#メニュー画面からの設定[住宅情報盤設定指示書]
		if($i<5)
			$myKikiSettei->{"JutakuMenuSettei".$i} = ${"wJutakuMenuSettei".$i};

		#施工設定（サービス）[住宅情報盤設定指示書]
		if($i<6)
			$myKikiSettei->{"JutakuServiceTSettei".$i} = ${"wJutakuServiceTSettei".$i."Value"};

		#施工設定（サービス防犯、窓、玄関、トイレ等）[住宅情報盤設定指示書]※１～４は未使用
		if($i<41 ||$i>4)
		$myKikiSettei->{"JutakuServiceSettei".$i} = ${"wJutakuServiceSettei".$i};

		#施工設定（玄関子機、管理室）[住宅情報盤設定]
		if($i<6)
			$myKikiSettei->{"JutakuKanriSettei".$i} = ${"wJutakuKanriSettei".$i};
			
		#施工設定（その他）「住宅情報盤設定]	
		if($i<4)
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

	$CNT_FILE = "s_kikiseteiWISM7α_finish.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);


?>
