<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', "On");


#外部オーナー用案内Excel出力

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
	echo $editBukkenCD;

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
	

	$myKikiSettei = new KikiSettei($myDB);
	if(!$myKikiSettei->executeSelect("BukkenCD = ".$editBukkenCD." AND KikiCD = 4",  "")){
	}

	for($i=1 ; $i < 56 ; $i++){
		if($i<22)
			${"wShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};
		if($i<44){
			${"wOyakiSettei".$i} = $myKikiSettei->{"OyakiSettei".$i};
		}elseif($i<52){
			${"wOyakiSettei".$i} = SPFWTools::decodePluralValue($myKikiSettei->{"OyakiSettei".$i});
		}
		if($i<10)
			${"wJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};

		if($i<9){
			${"wJutakuServiceTSettei".$i} = SPFWTools::decodePluralValue($myKikiSettei->{"JutakuServiceTSettei".$i});
		}
		
		
		if($i<56){
			${"wJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};
		}
		if($i<11)
			${"wJutakuSistemSettei".$i} = $myKikiSettei->{"JutakuSistemSettei".$i};

		if($i<6)
			${"wJutakuKeihouSettei".$i} = $myKikiSettei->{"JutakuKeihouSettei".$i};
			
		if($i<23)
			${"wJutakuKanriSettei".$i} = $myKikiSettei->{"JutakuKanriSettei".$i};
			
		if($i<12)
			${"wJutakuBouhanSettei".$i} = $myKikiSettei->{"JutakuBouhanSettei".$i};

		if($i<4)
			${"wGenkanKokiSettei".$i} = $myKikiSettei->{"GenkanKokiSettei".$i};

		if($i<2)
			${"wDenkijoSettei".$i} = $myKikiSettei->{"DenkijoSettei".$i};

		if($i<6)
			${"wSonotaSettei".$i} = $myKikiSettei->{"SonotaSettei".$i};
	}




		for($i=0; $i<count($wOyakiSettei44);$i++ ){
			if($i==0)
				$wOyakiSettei44Name = $IHOUTANSINAME[$wOyakiSettei44[$i]];
			elseif($i%5==0)
				$wOyakiSettei44Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei44[$i]];
			else
				$wOyakiSettei44Name .= ",　".$IHOUTANSINAME[$wOyakiSettei44[$i]];
		}
		for($i=0; $i<count($wOyakiSettei45);$i++ ){
			if($i==0)
				$wOyakiSettei45Name = $IHOUTANSINAME[$wOyakiSettei45[$i]];
			elseif($i%5==0)
				$wOyakiSettei45Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei45[$i]];
			else
				$wOyakiSettei45Name .= ",　".$IHOUTANSINAME[$wOyakiSettei45[$i]];
		}
		for($i=0; $i<count($wOyakiSettei46);$i++ ){
			if($i==0)
				$wOyakiSettei46Name = $IHOUTANSINAME[$wOyakiSettei46[$i]];
			elseif($i%5==0)
				$wOyakiSettei46Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei46[$i]];
			else
				$wOyakiSettei46Name .= ",　".$IHOUTANSINAME[$wOyakiSettei46[$i]];
		}
		for($i=0; $i<count($wOyakiSettei47);$i++ ){
			if($i==0)
				$wOyakiSettei47Name = $IHOUTANSINAME[$wOyakiSettei47[$i]];
			elseif($i%5==0)
				$wOyakiSettei47Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei47[$i]];
			else
				$wOyakiSettei47Name .= ",　".$IHOUTANSINAME[$wOyakiSettei47[$i]];
		}
		for($i=0; $i<count($wOyakiSettei48);$i++ ){
			if($i==0)
				$wOyakiSettei48Name = $IHOUTANSINAME[$wOyakiSettei48[$i]];
			elseif($i%5==0)
				$wOyakiSettei48Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei48[$i]];
			else
				$wOyakiSettei48Name .= ",　".$IHOUTANSINAME[$wOyakiSettei48[$i]];
		}
		for($i=0; $i<count($wOyakiSettei49);$i++ ){
			if($i==0)
				$wOyakiSettei49Name = $IHOUTANSINAME[$wOyakiSettei49[$i]];
			elseif($i%5==0)
				$wOyakiSettei49Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei49[$i]];
			else
				$wOyakiSettei49Name .= ",　".$IHOUTANSINAME[$wOyakiSettei49[$i]];
		}
		for($i=0; $i<count($wOyakiSettei50);$i++ ){
			if($i==0)
				$wOyakiSettei50Name = $IHOUTANSINAME[$wOyakiSettei50[$i]];
			elseif($i%5==0)
				$wOyakiSettei50Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei50[$i]];
			else
				$wOyakiSettei50Name .= ",　".$IHOUTANSINAME[$wOyakiSettei50[$i]];
		}
		for($i=0; $i<count($wOyakiSettei51);$i++ ){
			if($i==0)
				$wOyakiSettei51Name = $IHOUTANSINAME[$wOyakiSettei51[$i]];
			elseif($i%5==0)
				$wOyakiSettei51Name .= "<br>,".$IHOUTANSINAME[$wOyakiSettei51[$i]];
			else
				$wOyakiSettei51Name .= ",　".$IHOUTANSINAME[$wOyakiSettei51[$i]];
		}
		
		for($i=0; $i<count($wJutakuServiceTSettei1);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei1Name = $SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei1[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei1Name .= "<br>,".$SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei1[$i]];
			else
				$wJutakuServiceTSettei1Name .= ",　".$SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei1[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei2);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei2Name = $SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei2[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei2Name .= "<br>,".$SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei2[$i]];
			else
				$wJutakuServiceTSettei2Name .= ",".$SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei2[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei3);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei3Name = $SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei3[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei3Name .= "<br>,".$SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei3[$i]];
			else
				$wJutakuServiceTSettei3Name .= ",".$SERVICENAME_VIXUS1Pr[$wJutakuServiceTSettei3[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei4);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei4Name = $SERVICENAME_VIXUS1Pr_4[$wJutakuServiceTSettei4[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei4Name .= "<br>,".$SERVICENAME_VIXUS1Pr_4[$wJutakuServiceTSettei4[$i]];
			else
				$wJutakuServiceTSettei4Name .= ",".$SERVICENAME_VIXUS1Pr_4[$wJutakuServiceTSettei4[$i]];
		}
		
		#5用にカスタマイズ　定数配列の値をコピーして最後に１つ加える
		$tmpSERVICENAME_VIXUS1Pr1for5 = $SERVICENAME_VIXUS1Pr;
		$tmpSERVICENAME_VIXUS1Pr1for5[] = $SERVICENAME_VIXUS1Pr_ADD5[0];
		for($i=0; $i<count($wJutakuServiceTSettei5);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei5Name = $tmpSERVICENAME_VIXUS1Pr1for5[$wJutakuServiceTSettei5[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei5Name .= "<br>,".$tmpSERVICENAME_VIXUS1Pr1for5[$wJutakuServiceTSettei5[$i]];
			else
				$wJutakuServiceTSettei5Name .= ",　".$tmpSERVICENAME_VIXUS1Pr1for5[$wJutakuServiceTSettei5[$i]];
		}

		#6用にカスタマイズ　定数配列をコピーして最後に一つ加える
		$tmpSERVICENAME_VIXUS1Pr1for6 = $SERVICENAME_VIXUS1Pr;
		$tmpSERVICENAME_VIXUS1Pr1for6[] = $SERVICENAME_VIXUS1Pr_ADD6[0];
		for($i=0; $i<count($wJutakuServiceTSettei6);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei6Name = $tmpSERVICENAME_VIXUS1Pr1for6[$wJutakuServiceTSettei6[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei6Name .= "<br>,".$tmpSERVICENAME_VIXUS1Pr1for6[$wJutakuServiceTSettei6[$i]];
			else
				$wJutakuServiceTSettei6Name .= ",".$tmpSERVICENAME_VIXUS1Pr1for6[$wJutakuServiceTSettei6[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei7);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei7Name = $SERVICENAME_VIXUS1Pr_7_8[$wJutakuServiceTSettei7[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei7Name .= "<br>,".$SERVICENAME_VIXUS1Pr_7_8[$wJutakuServiceTSettei7[$i]];
			else
				$wJutakuServiceTSettei7Name .= ",".$SERVICENAME_VIXUS1Pr_7_8[$wJutakuServiceTSettei7[$i]];
		}
		for($i=0; $i<count($wJutakuServiceTSettei8);$i++ ){
			if($i==0)
				$wJutakuServiceTSettei8Name = $SERVICENAME_VIXUS1Pr_7_8[$wJutakuServiceTSettei8[$i]];
			elseif($i%5==0)
				$wJutakuServiceTSettei8Name .= "<br>,".$SERVICENAME_VIXUS1Pr_7_8[$wJutakuServiceTSettei8[$i]];
			else
				$wJutakuServiceTSettei8Name .= ",".$SERVICENAME_VIXUS1Pr_7_8[$wJutakuServiceTSettei8[$i]];
		}



		for($i=44 ;$i<52 ;$i++ )
			${"wOyakiSettei".$i."Value"} = SPFWTools::encodePluralValue(${"wOyakiSettei".$i});
			
		for($i=1 ;$i<9 ;$i++ )
			${"wJutakuServiceTSettei".$i."Value"} = SPFWTools::encodePluralValue(${"wJutakuServiceTSettei".$i});

	########################################################
	# DB連携
	########################################################


	$myKikiSettei = new KikiSettei($myDB);

	#初期値を取得
	#if(!$myKikiSettei->executeSelect("KikiSetteiCD = 1",""))
	if(!$myKikiSettei->executeSelect("KikiCD = 4 AND BukkenCD is NULL",""))
		trigger_error("executeSelect(myKikiSettei) Failed" , E_USER_ERROR);

	for($i=1 ;$i<56;$i++ ){
		if($i<22)
			${"dShugoSettei".$i} = $myKikiSettei->{"ShugoSettei".$i};
		if($i<52)
			${"dOyakiSettei".$i} = $myKikiSettei->{"OyakiSettei".$i};
		if($i<13)
			${"dJutakuMenuSettei".$i} = $myKikiSettei->{"JutakuMenuSettei".$i};
		if($i<9)
			${"dJutakuServiceTSettei".$i} = $myKikiSettei->{"JutakuServiceTSettei".$i};

		if($i<56)
			${"dJutakuServiceSettei".$i} = $myKikiSettei->{"JutakuServiceSettei".$i};
		if($i<11)
			${"dJutakuSistemSettei".$i} = $myKikiSettei->{"JutakuSistemSettei".$i};
		if($i<6)
			${"dJutakuKeihouSettei".$i} = $myKikiSettei->{"JutakuKeihouSettei".$i};
		if($i<23)
			${"dJutakuKanriSettei".$i} = $myKikiSettei->{"JutakuKanriSettei".$i};
		if($i<12)
			${"dJutakuBouhanSettei".$i} = $myKikiSettei->{"JutakuBouhanSettei".$i};
		if($i<4)
			${"dGenkanKokiSettei".$i} = $myKikiSettei->{"GenkanKokiSettei".$i};
		if($i<2)
			${"dDenkijoSettei".$i} = $myKikiSettei->{"DenkijoSettei".$i};
		if($i<6)
			${"dSonotaSettei".$i} = $myKikiSettei->{"SonotaSettei".$i};
	}

	unset($myKikiSettei);
	



	########################################################
	# 初期設定と比較
	########################################################

//集合玄関機設定
	$SHUGOSETTEI[1]="R17";
	$SHUGOSETTEI[2]="R18";
	$SHUGOSETTEI[3]="R19";
	$SHUGOSETTEI[4]="R20";
	$SHUGOSETTEI[5]="R21";
	$SHUGOSETTEI[6]="R22";
	$SHUGOSETTEI[7]="R23";
	$SHUGOSETTEI[8]="N24";
	$SHUGOSETTEI[9]="N24";
	$SHUGOSETTEI[10]="N25";
	$SHUGOSETTEI[11]="N25";
	$SHUGOSETTEI[12]="R26";
	$SHUGOSETTEI[13]="R27";
	$SHUGOSETTEI[14]="R28";
	$SHUGOSETTEI[15]="R30";
	$SHUGOSETTEI[16]="R31";
	$SHUGOSETTEI[17]="R32";
	$SHUGOSETTEI[18]="R33";
	$SHUGOSETTEI[19]="R35";
	$SHUGOSETTEI[20]="R36";
	$SHUGOSETTEI[21]="R37";
//管理室親機設定
	$OYAKISETTEI[1]="S17";
	$OYAKISETTEI[2]="S18";
	$OYAKISETTEI[3]="S19";
	$OYAKISETTEI[4]="S20";
	$OYAKISETTEI[5]="S21";
	$OYAKISETTEI[6]="S22";
	$OYAKISETTEI[7]="S23";
	$OYAKISETTEI[8]="S24";
	$OYAKISETTEI[9]="S25";
	$OYAKISETTEI[10]="S26";
	$OYAKISETTEI[11]="S27";
	$OYAKISETTEI[12]="S28";
	$OYAKISETTEI[13]="S29";
	$OYAKISETTEI[14]="S30";
	$OYAKISETTEI[15]="S31";
	$OYAKISETTEI[16]="S32";
	$OYAKISETTEI[17]="S33";
	$OYAKISETTEI[18]="S34";
	$OYAKISETTEI[19]="S35";
	$OYAKISETTEI[20]="S36";
	$OYAKISETTEI[21]="S37";
	$OYAKISETTEI[22]="S39";
	$OYAKISETTEI[23]="S40";
	$OYAKISETTEI[24]="S41";
	$OYAKISETTEI[25]="S42";
	$OYAKISETTEI[26]="AT6";
	$OYAKISETTEI[27]="AT7";
	$OYAKISETTEI[28]="AT8";
	$OYAKISETTEI[29]="AT9";
	$OYAKISETTEI[30]="AT10";
	$OYAKISETTEI[31]="AT10";
	$OYAKISETTEI[32]="AT11";
	$OYAKISETTEI[33]="AT11";
	$OYAKISETTEI[34]="AT13";
	$OYAKISETTEI[35]="AT14";
	$OYAKISETTEI[36]="AT15";
	$OYAKISETTEI[37]="AT16";
	$OYAKISETTEI[38]="AT17";
	$OYAKISETTEI[39]="AT18";
	$OYAKISETTEI[40]="AT19";
	$OYAKISETTEI[41]="AT20";
	$OYAKISETTEI[42]="AT21";
	$OYAKISETTEI[43]="AT22";

	$OYAKISETTEI[44]="BN6";
	$OYAKISETTEI[45]="BN11";
	$OYAKISETTEI[46]="BN16";
	$OYAKISETTEI[47]="BN21";
	$OYAKISETTEI[48]="BN26";
	$OYAKISETTEI[49]="BN31";
	$OYAKISETTEI[50]="BN36";
	$OYAKISETTEI[51]="BN41";


//住宅情報盤設定
	//メニュー画面からの設定
	$JUTAKUMENUSETTEI[1]="M7";
	$JUTAKUMENUSETTEI[2]="M8";
	$JUTAKUMENUSETTEI[3]="M9";
	$JUTAKUMENUSETTEI[4]="M10";
	$JUTAKUMENUSETTEI[5]="M12";
	$JUTAKUMENUSETTEI[6]="M13";
	$JUTAKUMENUSETTEI[7]="M15";
	$JUTAKUMENUSETTEI[8]="M17";
	$JUTAKUMENUSETTEI[9]="M19";

	//サービス1
	$JUTAKUSERVICETSETTEI[1]="L25";
	$JUTAKUSERVICETSETTEI[2]="L28";
	$JUTAKUSERVICETSETTEI[3]="L31";
	$JUTAKUSERVICETSETTEI[4]="L34";
	$JUTAKUSERVICETSETTEI[5]="L37";
	$JUTAKUSERVICETSETTEI[6]="L40";
	$JUTAKUSERVICETSETTEI[7]="L43";
	$JUTAKUSERVICETSETTEI[8]="L46";

	//二枚目
	$JUTAKUSERVICESETTEI[5]="AO6";
	$JUTAKUSERVICESETTEI[6]="AZ6";
	$JUTAKUSERVICESETTEI[7]="AO7";
	$JUTAKUSERVICESETTEI[8]="AZ7";
	$JUTAKUSERVICESETTEI[9]="AO8";
	$JUTAKUSERVICESETTEI[10]="AZ8";
	$JUTAKUSERVICESETTEI[11]="AO9";
	$JUTAKUSERVICESETTEI[12]="AZ9";
	$JUTAKUSERVICESETTEI[13]="AO10";
	$JUTAKUSERVICESETTEI[14]="AZ10";
	$JUTAKUSERVICESETTEI[15]="AO11";
	$JUTAKUSERVICESETTEI[16]="AZ11";
	$JUTAKUSERVICESETTEI[17]="AO12";
	$JUTAKUSERVICESETTEI[18]="AZ12";
	$JUTAKUSERVICESETTEI[19]="AO13";
	$JUTAKUSERVICESETTEI[20]="AZ13";
	$JUTAKUSERVICESETTEI[21]="AO14";
	$JUTAKUSERVICESETTEI[22]="AZ14";
	$JUTAKUSERVICESETTEI[23]="AO15";
	$JUTAKUSERVICESETTEI[24]="AZ15";
	$JUTAKUSERVICESETTEI[25]="AO16";
	$JUTAKUSERVICESETTEI[26]="AO17";
	$JUTAKUSERVICESETTEI[27]="AZ17";
	$JUTAKUSERVICESETTEI[28]="AO18";
	$JUTAKUSERVICESETTEI[29]="AO19";
	$JUTAKUSERVICESETTEI[30]="AZ19";
	$JUTAKUSERVICESETTEI[31]="AO20";
	$JUTAKUSERVICESETTEI[32]="AO21";
	$JUTAKUSERVICESETTEI[33]="AZ21";
	$JUTAKUSERVICESETTEI[34]="AO22";
	$JUTAKUSERVICESETTEI[35]="AZ22";
	$JUTAKUSERVICESETTEI[36]="AO23";
	$JUTAKUSERVICESETTEI[37]="AZ23";
	$JUTAKUSERVICESETTEI[38]="AO24";
	$JUTAKUSERVICESETTEI[39]="AO25";
	$JUTAKUSERVICESETTEI[40]="AZ25";
	$JUTAKUSERVICESETTEI[41]="AO26";
	$JUTAKUSERVICESETTEI[42]="AO27";
	$JUTAKUSERVICESETTEI[43]="AZ27";
	$JUTAKUSERVICESETTEI[44]="AO28";
	$JUTAKUSERVICESETTEI[45]="AZ28";
	$JUTAKUSERVICESETTEI[46]="AO29";
	$JUTAKUSERVICESETTEI[47]="AZ29";
	$JUTAKUSERVICESETTEI[48]="AO30";
	$JUTAKUSERVICESETTEI[49]="AZ30";
	$JUTAKUSERVICESETTEI[50]="AO31";
	$JUTAKUSERVICESETTEI[51]="AZ31";
	$JUTAKUSERVICESETTEI[52]="AO32";
	$JUTAKUSERVICESETTEI[53]="AZ32";
	$JUTAKUSERVICESETTEI[54]="AO33";
	$JUTAKUSERVICESETTEI[55]="AZ33";
	

	$JUTAKUSISTEMSETTEI[1]="AP37";
	$JUTAKUSISTEMSETTEI[2]="AP38";
	$JUTAKUSISTEMSETTEI[3]="AP39";
	$JUTAKUSISTEMSETTEI[4]="AP40";
	$JUTAKUSISTEMSETTEI[5]="AP41";
	$JUTAKUSISTEMSETTEI[6]="AP42";
	$JUTAKUSISTEMSETTEI[7]="AP43";
	$JUTAKUSISTEMSETTEI[8]="AP44";
	$JUTAKUSISTEMSETTEI[9]="AP46";
	$JUTAKUSISTEMSETTEI[10]="AP48";
	
	$JUTAKUKEIHOUSETTEI[1]="BR6";
	$JUTAKUKEIHOUSETTEI[2]="BR7";
	$JUTAKUKEIHOUSETTEI[3]="BR8";
	$JUTAKUKEIHOUSETTEI[4]="BR9";
	$JUTAKUKEIHOUSETTEI[5]="BR10";

	$JUTAKUKANRISETTEI[1]="BX14";
	$JUTAKUKANRISETTEI[2]="BX15";
	$JUTAKUKANRISETTEI[3]="BX16";
	$JUTAKUKANRISETTEI[4]="BX17";
	$JUTAKUKANRISETTEI[5]="BX18";
	$JUTAKUKANRISETTEI[6]="BX19";
	$JUTAKUKANRISETTEI[7]="BX20";
	$JUTAKUKANRISETTEI[8]="BX21";
	$JUTAKUKANRISETTEI[9]="BX22";
	$JUTAKUKANRISETTEI[10]="BX23";
	$JUTAKUKANRISETTEI[11]="BX24";
	$JUTAKUKANRISETTEI[12]="BX25";
	$JUTAKUKANRISETTEI[13]="BX26";
	$JUTAKUKANRISETTEI[14]="BX27";
	$JUTAKUKANRISETTEI[15]="BX28";
	$JUTAKUKANRISETTEI[16]="BX29";
	$JUTAKUKANRISETTEI[17]="BX30";
	$JUTAKUKANRISETTEI[18]="BX31";
	$JUTAKUKANRISETTEI[19]="BX32";
	$JUTAKUKANRISETTEI[20]="BX33";
	$JUTAKUKANRISETTEI[21]="BX34";
	$JUTAKUKANRISETTEI[22]="BX35";

	$JUTAKUBOUHANSETTEI[1] = "CV6";
	$JUTAKUBOUHANSETTEI[2] = "CV7";
	$JUTAKUBOUHANSETTEI[3] = "CV8";
	$JUTAKUBOUHANSETTEI[4] = "CV9";
	$JUTAKUBOUHANSETTEI[5] = "CV10";
	$JUTAKUBOUHANSETTEI[6] = "CV11";
	$JUTAKUBOUHANSETTEI[7] = "CV12";
	$JUTAKUBOUHANSETTEI[8] = "CV13";
	$JUTAKUBOUHANSETTEI[9] = "CV14";
	$JUTAKUBOUHANSETTEI[10] = "CV15";
	$JUTAKUBOUHANSETTEI[11] = "CV16";


	$GENKANKOKISETTEI[1] = "CZ19";
	$GENKANKOKISETTEI[2] = "CZ20";
	$GENKANKOKISETTEI[3] = "CZ21";

	$DENKIJOSETTEI[1] = "CV25";


	$SONOTASETTEI[1] = "CV28";
	$SONOTASETTEI[2] = "CV29";
	$SONOTASETTEI[3] = "CV30";
	$SONOTASETTEI[4] = "CV31";
	$SONOTASETTEI[5] = "CV32";

	for($i=1 ;$i<56 ;$i++ ){
		if($i==8 ||$i==10 ){
			if(${"wShugoSettei".$i}!=="" && ${"wShugoSettei".($i+1)}!=""){
				$ShugoSetteiPlace[]=$SHUGOSETTEI[$i];
			}else{
				${"wShugoSettei".$i} = "";
			}
		}elseif($i==14){
			if(${"dShugoSettei".$i} !== ${"wShugoSettei".$i} && ${"wShugoSettei".$i}!=""){
				$ShugoSetteiPlace[]=$SHUGOSETTEI[$i];
			}else{
				${"wShugoSettei".$i} = "";
			}
		}elseif($i<22 && $i!=9 && $i!=11){
			if(${"dShugoSettei".$i} !== ${"wShugoSettei".$i} ||($i>7 && $i<12)){
				$ShugoSetteiPlace[]=$SHUGOSETTEI[$i];
			}else{
				${"wShugoSettei".$i} = "";
			}
		}
		if($i<53&&($i!==31||$i!==33)){ 
			if(${"dOyakiSettei".$i} !== ${"wOyakiSettei".$i} && $i<44){
				echo $i.":".$OYAKISETTEI[$i]."<br>";
				$OyakiSetteiPlace[] = $OYAKISETTEI[$i];
			}elseif($i<44){
				${"wOyakiSettei".$i} = "";
			}elseif(${"dOyakiSettei".$i} !== ${"wOyakiSettei".$i."Value"}){
				echo $i.":".$OYAKISETTEI[$i]."<br>";
				$OyakiSetteiPlace[] = $OYAKISETTEI[$i];
			}else{
				${"wOyakiSettei".$i."Value"} = "";
			}
		}
		if($i<10){
			if(${"dJutakuMenuSettei".$i} !== ${"wJutakuMenuSettei".$i}){
				echo $i.":".$OYAKISETTEI[$i]."<br>";
				$JutakuSetteiPlace[] = $JUTAKUMENUSETTEI[$i];
			}else{
				${"wJutakuMenuSettei".$i} = "";
			}
		}
		
		if($i<9){
			if(${"dJutakuServiceTSettei".$i} !== ${"wJutakuServiceTSettei".$i."Value"}){
				$JutakuSetteiPlace[] = $JUTAKUSERVICETSETTEI[$i];
			}else{
				${"wJutakuServiceTSettei".$i."Value"} = "";
			}
		}		
		
		if($i<56){
			if(${"dJutakuServiceSettei".$i} !== ${"wJutakuServiceSettei".$i} && $i>4){
				$JutakuSetteiPlace[] = $JUTAKUSERVICESETTEI[$i];
			}else{
				${"wJutakuServiceSettei".$i} = "";
			}
		}
		if($i<11){
			if(${"dJutakuSistemSettei".$i} !== ${"wJutakuSistemSettei".$i}){
				$JutakuSetteiPlace[] = $JUTAKUSISTEMSETTEI[$i];
			}else{
				${"wJutakuSistemSettei".$i} = "";
			}
		}
		if($i<6){
			if(${"dJutakuKeihouSettei".$i} !== ${"wJutakuKeihouSettei".$i}){
				$JutakuSetteiPlace[] = $JUTAKUKEIHOUSETTEI[$i];
			}else{
				${"wJutakuKeihouSettei".$i} = "";
			}
		}
		if($i<23){
			if(${"dJutakuKanriSettei".$i} !== ${"wJutakuKanriSettei".$i}){
				$JutakuSetteiPlace[] = $JUTAKUKANRISETTEI[$i];
			}else{
				${"wJutakuKanriSettei".$i} = "";
			}
		}
		if($i<12){
			if(${"dJutakuBouhanSettei".$i} !== ${"wJutakuBouhanSettei".$i}){
				$JutakuSetteiPlace[] = $JUTAKUBOUHANSETTEI[$i];
			}else{
				${"wJutakuBouhanSettei".$i} = "";
			}
		}
		if($i<4){
			if(${"dGenkanKokiSettei".$i} !== ${"wGenkanKokiSettei".$i}){
				$JutakuSetteiPlace[] = $GENKANKOKISETTEI[$i];
			}else{
				${"wGenkanKokiSettei".$i} = "";
			}
		}
		
		if($i<2){
			if(${"dDenkijoSettei".$i} !== ${"wDenkijoSettei".$i}){
				$JutakuSetteiPlace[] = $DENKIJOSETTEI[$i];
			}else{
				${"DenkijoSettei".$i} = "";
			}
		}

		if($i<6){
			if(${"dSonotaSettei".$i} !== ${"wSonotaSettei".$i}){
				$JutakuSetteiPlace[] = $SONOTASETTEI[$i];
			}else{
				${"wSonotaSettei".$i} = "";
			}
		}
		
	}

	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	use PhpOffice\PhpSpreadsheet\Style;
	use PhpOffice\PhpSpreadsheet\Style\Fill;

	$reader = new XlsxReader();
	$spreadsheet = $reader->load('./template/kikisettei_VIXUS1Pr.xlsx'); //template.xlsx 読込

	#集合玄関機設定
	$sheet = $spreadsheet->getSheetByName('集玄設定表'); //weatherシート取得
		#物件名の表示
		$sheet->setCellValue('AC3',$BukkenName);

		$sheet->setCellValue('R17', $wShugoSettei1);
		$sheet->setCellValue('R18', $wShugoSettei2);
	if($wShugoSettei3)
		$sheet->setCellValue('R19', $SETUZOKU[$wShugoSettei3]);
	if($wShugoSettei4)
		$sheet->setCellValue('R20', $SIYOU[$wShugoSettei4]);
		$sheet->setCellValue('R21', $SIYOU[$wShugoSettei5]);
		$sheet->setCellValue('R22', $UMU[$wShugoSettei6]);
		$sheet->setCellValue('R23', $HYOUJI[$wShugoSettei7]);
		$sheet->setCellValue('N24', $SAYU[$wShugoSettei8]."：".$wShugoSettei9);
		$sheet->setCellValue('N25', $JOGE[$wShugoSettei10]."：".$wShugoSettei11);		
		$sheet->setCellValue('R26', $HYOUJI[$wShugoSettei12]);
	if($wShugoSettei13)
		$sheet->setCellValue('R27', $wShugoSettei13."回");
	if($wShugoSettei14)
		$sheet->setCellValue('R28', $wShugoSettei14."秒");
		$sheet->setCellValue('R30', $SETUZOKU[$wShugoSettei15]);
		if($wShugoSettei16=="")
			$sheet->setCellValue('R31', $wShugoSettei16);
		elseif($wShugoSettei16=="0")
			$sheet->setCellValue('R31', "なし");
		else
			$sheet->setCellValue('R31', $wShugoSettei16."回");

		$sheet->setCellValue('R32', $TONRYOU[$wShugoSettei17]);
		$sheet->setCellValue('R33', $MEIDO[$wShugoSettei18]);
		$sheet->setCellValue('R35', $SIYOU[$wShugoSettei19]);
		$sheet->setCellValue('R36', $SIYOU[$wShugoSettei20]);
		$sheet->setCellValue('R37', $SIYOU[$wShugoSettei21]);

		//初期値と値が異なっていたら赤色にする
		for($i=0;$i<count($ShugoSetteiPlace);$i++){
			$spreadsheet->getSheetByName('集玄設定表')->getStyle($ShugoSetteiPlace[$i])->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFBAB3');
		}


	#管理室親機設定
	$sheet = $spreadsheet->getSheetByName('管親設定表'); //weatherシート取得
		$sheet->setCellValue('S17', $SIYOU[$wOyakiSettei1]);
		$sheet->setCellValue('S18', $SIYOU[$wOyakiSettei2]);
		$sheet->setCellValue('S19', $SIYOU[$wOyakiSettei3]);
		$sheet->setCellValue('S20', $SIYOU[$wOyakiSettei4]);
		$sheet->setCellValue('S21', $SIYOU[$wOyakiSettei5]);
		$sheet->setCellValue('S22', $SIYOU[$wOyakiSettei6]);
		$sheet->setCellValue('S23', $SIYOU[$wOyakiSettei7]);
		$sheet->setCellValue('S24', $SIYOU[$wOyakiSettei8]);
		$sheet->setCellValue('S25', $SIYOU[$wOyakiSettei9]);		
		$sheet->setCellValue('S26', $SIYOU[$wOyakiSettei10]);
		$sheet->setCellValue('S27', $SIYOU[$wOyakiSettei11]);
		$sheet->setCellValue('S28', $SIYOU[$wOyakiSettei12]);
		$sheet->setCellValue('S29', $SIYOU[$wOyakiSettei13]);
		$sheet->setCellValue('S30', $SIYOU[$wOyakiSettei14]);
		$sheet->setCellValue('S31', $SIYOU[$wOyakiSettei15]);
		$sheet->setCellValue('S32', $SIYOU[$wOyakiSettei16]);
		$sheet->setCellValue('S33', $SIYOU[$wOyakiSettei17]);
		$sheet->setCellValue('S34', $SIYOU[$wOyakiSettei18]);
		$sheet->setCellValue('S35', $SIYOU[$wOyakiSettei19]);
		$sheet->setCellValue('S36', $SIYOU[$wOyakiSettei20]);
		$sheet->setCellValue('S37', $wOyakiSettei21."回");
		$sheet->setCellValue('S39', $MEIDO[$wOyakiSettei22]);
		$sheet->setCellValue('S40', $SIYOU[$wOyakiSettei23]);
		$sheet->setCellValue('S41', $KEIHOUONSEI[$wOyakiSettei24]);
		$sheet->setCellValue('S42', $SIYOU[$wOyakiSettei25]);

	#管理室親機設定（2枚目）

		$sheet->setCellValue($OYAKISETTEI[26], $SIYOU[$wOyakiSettei26]);
		$sheet->setCellValue($OYAKISETTEI[27], $SIYOU[$wOyakiSettei27]);
		$sheet->setCellValue($OYAKISETTEI[28], $SIYOU[$wOyakiSettei28]);
		$sheet->setCellValue($OYAKISETTEI[29], $SIYOU[$wOyakiSettei29]);
		if($wOyakiSettei30==0)
			$sheet->setCellValue($OYAKISETTEI[31], "");
		else
			$sheet->setCellValue($OYAKISETTEI[31], $wOyakiSettei31."秒");

		if($wOyakiSettei32==0)
			$sheet->setCellValue($OYAKISETTEI[33], "");
		else
			$sheet->setCellValue($OYAKISETTEI[33], $wOyakiSettei33."秒");

		$sheet->setCellValue($OYAKISETTEI[34], $SIYOU[$wOyakiSettei34]);
		$sheet->setCellValue($OYAKISETTEI[35], $SIYOU[$wOyakiSettei35]);
		$sheet->setCellValue($OYAKISETTEI[36], $SIYOU[$wOyakiSettei36]);
		$sheet->setCellValue($OYAKISETTEI[37], $SIYOU[$wOyakiSettei37]);
		$sheet->setCellValue($OYAKISETTEI[38], $SIYOU[$wOyakiSettei38]);
		$sheet->setCellValue($OYAKISETTEI[39], $SIYOU[$wOyakiSettei39]);
		$sheet->setCellValue($OYAKISETTEI[40], $SIYOU[$wOyakiSettei40]);
		$sheet->setCellValue($OYAKISETTEI[41], $SIYOU[$wOyakiSettei41]);
		$sheet->setCellValue($OYAKISETTEI[42], $KANRISITUOYAKINAME[$wOyakiSettei42]);
		$sheet->setCellValue($OYAKISETTEI[43], $SIYOU[$wOyakiSettei43]);




		//初期値と値が異なっていたら赤色にする
		for($i=0;$i<count($OyakiSetteiPlace);$i++){
			$spreadsheet->getSheetByName('管親設定表')->getStyle($OyakiSetteiPlace[$i])->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFBAB3');
		}

		$sheet->setCellValue($OYAKISETTEI[44], str_replace("<br>","\n",$wOyakiSettei44Name));
		$sheet->setCellValue($OYAKISETTEI[45], str_replace("<br>","\n",$wOyakiSettei45Name));
		$sheet->setCellValue($OYAKISETTEI[46], str_replace("<br>","\n",$wOyakiSettei46Name));
		$sheet->setCellValue($OYAKISETTEI[47], str_replace("<br>","\n",$wOyakiSettei47Name));
		$sheet->setCellValue($OYAKISETTEI[48], str_replace("<br>","\n",$wOyakiSettei48Name));
		$sheet->setCellValue($OYAKISETTEI[49], str_replace("<br>","\n",$wOyakiSettei49Name));
		$sheet->setCellValue($OYAKISETTEI[50], str_replace("<br>","\n",$wOyakiSettei50Name));
		$sheet->setCellValue($OYAKISETTEI[51], str_replace("<br>","\n",$wOyakiSettei51Name));


#使用する・しない
$SIYOU_2[0] = "使用する";
$SIYOU_2[1] = "使用しない";

##連動
$RENDOU_2[0] = "連動する";
$RENDOU_2[1] = "連動しない";

	$sheet = $spreadsheet->getSheetByName('親機設定表'); //weatherシート取得
#要調整
		if($wJutakuMenuSettei1 == 1){	#初期値=0 違う場合のみ出力
			$sheet->setCellValue($JUTAKUMENUSETTEI[1], '録音しない');
		}
		$sheet->setCellValue($JUTAKUMENUSETTEI[2], $SATUZOU[$wJutakuMenuSettei2]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[3], $SATUZOU[$wJutakuMenuSettei3]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[4], $SYOUMEI[$wJutakuMenuSettei4]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[5], $SYOUMEI[$wJutakuMenuSettei5]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[6], $SIYOU_2[$wJutakuMenuSettei6]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[7], $RENDOU_2[$wJutakuMenuSettei7]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[8], $TIENJIKAN[$wJutakuMenuSettei8]);
		$sheet->setCellValue($JUTAKUMENUSETTEI[9], $TIENJIKAN[$wJutakuMenuSettei9]);
		
		$sheet->setCellValue($JUTAKUSERVICETSETTEI[1], str_replace("<br>","\n",$wJutakuServiceTSettei1Name));
		$sheet->setCellValue($JUTAKUSERVICETSETTEI[2], str_replace("<br>","\n",$wJutakuServiceTSettei2Name));
		$sheet->setCellValue($JUTAKUSERVICETSETTEI[3], str_replace("<br>","\n",$wJutakuServiceTSettei3Name));
		$sheet->setCellValue($JUTAKUSERVICETSETTEI[4], str_replace("<br>","\n",$wJutakuServiceTSettei4Name));
		$sheet->setCellValue($JUTAKUSERVICETSETTEI[5], str_replace("<br>","\n",$wJutakuServiceTSettei5Name));
		$sheet->setCellValue($JUTAKUSERVICETSETTEI[6], str_replace("<br>","\n",$wJutakuServiceTSettei6Name));
		$sheet->setCellValue($JUTAKUSERVICETSETTEI[7], str_replace("<br>","\n",$wJutakuServiceTSettei7Name));
		$sheet->setCellValue($JUTAKUSERVICETSETTEI[8], str_replace("<br>","\n",$wJutakuServiceTSettei8Name));

		$sheet->setCellValue($JUTAKUSERVICESETTEI[5], $KENSYUTU[$wJutakuServiceSettei5]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[6], $OSIBUTTON[$wJutakuServiceSettei6]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[7], $TIENJIKAN[$wJutakuServiceSettei7]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[8], $SHUTURYOKU[$wJutakuServiceSettei8]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[9], $KENSYUTU[$wJutakuServiceSettei9]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[10], $OSIBUTTON[$wJutakuServiceSettei10]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[11], $TIENJIKAN[$wJutakuServiceSettei11]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[12], $SHUTURYOKU[$wJutakuServiceSettei12]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[13], $KENSYUTU[$wJutakuServiceSettei13]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[14], $OSIBUTTON[$wJutakuServiceSettei14]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[15], $TIENJIKAN[$wJutakuServiceSettei15]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[16], $SHUTURYOKU[$wJutakuServiceSettei16]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[17], $TIENJIKAN[$wJutakuServiceSettei17]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[18], $SHUTURYOKU[$wJutakuServiceSettei18]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[19], $KENSYUTU[$wJutakuServiceSettei19]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[20], $OSIBUTTON[$wJutakuServiceSettei20]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[21], $TIENJIKAN[$wJutakuServiceSettei21]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[22], $SHUTURYOKU[$wJutakuServiceSettei22]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[23], $KENSYUTU[$wJutakuServiceSettei23]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[24], $OSIBUTTON[$wJutakuServiceSettei24]);
//		$sheet->setCellValue($JUTAKUSERVICESETTEI[25], $TIENJIKAN[$wJutakuServiceSettei25]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[26], $KENSYUTU[$wJutakuServiceSettei26]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[27], $OSIBUTTON[$wJutakuServiceSettei27]);
//		$sheet->setCellValue($JUTAKUSERVICESETTEI[28], $TIENJIKAN[$wJutakuServiceSettei28]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[29], $KENSYUTU[$wJutakuServiceSettei29]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[30], $OSIBUTTON[$wJutakuServiceSettei30]);
//		$sheet->setCellValue($JUTAKUSERVICESETTEI[31], $TIENJIKAN[$wJutakuServiceSettei31]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[32], $KENSYUTU[$wJutakuServiceSettei32]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[33], $OSIBUTTON[$wJutakuServiceSettei33]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[34], $TIENJIKAN[$wJutakuServiceSettei34]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[35], $SHUTURYOKU[$wJutakuServiceSettei35]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[36], $KENSYUTU[$wJutakuServiceSettei36]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[37], $OSIBUTTON[$wJutakuServiceSettei37]);
//		$sheet->setCellValue($JUTAKUSERVICESETTEI[38], $TIENJIKAN[$wJutakuServiceSettei38]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[39], $KENSYUTU[$wJutakuServiceSettei39]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[40], $OSIBUTTON[$wJutakuServiceSettei40]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[41], $TIENJIKAN[$wJutakuServiceSettei41]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[42], $OSIBUTTON[$wJutakuServiceSettei42]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[43], $TIENJIKAN[$wJutakuServiceSettei43]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[44], $KENSYUTU[$wJutakuServiceSettei44]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[45], $OSIBUTTON[$wJutakuServiceSettei45]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[46], $TIENJIKAN[$wJutakuServiceSettei46]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[47], $SHUTURYOKU[$wJutakuServiceSettei47]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[48], $KENSYUTU[$wJutakuServiceSettei48]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[49], $OSIBUTTON[$wJutakuServiceSettei49]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[50], $TIENJIKAN[$wJutakuServiceSettei50]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[51], $SHUTURYOKU[$wJutakuServiceSettei51]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[52], $KENSYUTU[$wJutakuServiceSettei52]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[53], $OSIBUTTON[$wJutakuServiceSettei53]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[54], $TIENJIKAN[$wJutakuServiceSettei54]);
		$sheet->setCellValue($JUTAKUSERVICESETTEI[55], $SHUTURYOKU[$wJutakuServiceSettei55]);



		$sheet->setCellValue($JUTAKUSISTEMSETTEI[1], $SIYOU[$wJutakuSistemSettei1]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[2], $SIYOU[$wJutakuSistemSettei2]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[3], $SIYOU[$wJutakuSistemSettei3]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[4], $SIYOU[$wJutakuSistemSettei4]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[5], $JUSIN[$wJutakuSistemSettei5]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[6], $JUSIN[$wJutakuSistemSettei6]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[7], $JUSIN[$wJutakuSistemSettei7]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[8], $SHUTURYOKU[$wJutakuSistemSettei8]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[9], $ONRYOU[$wJutakuSistemSettei9]);
		$sheet->setCellValue($JUTAKUSISTEMSETTEI[10], $SHUTURYOKU[$wJutakuSistemSettei10]);

		$sheet->setCellValue($JUTAKUKEIHOUSETTEI[1], $IHOU[$wJutakuSistemSettei1]);
		$sheet->setCellValue($JUTAKUKEIHOUSETTEI[2], $JIDOUTEISI[$wJutakuSistemSettei2]);
		$sheet->setCellValue($JUTAKUKEIHOUSETTEI[3], $KEIHOUHYOJI[$wJutakuSistemSettei3]);
		$sheet->setCellValue($JUTAKUKEIHOUSETTEI[4], $TIENJIKAN[$wJutakuSistemSettei4]);
		$sheet->setCellValue($JUTAKUKEIHOUSETTEI[5], $SHUTURYOKU[$wJutakuSistemSettei5]);

#管理室呼出ボタン設定

$YOBIDASI[0] = "メモリ時のみ表示";
$YOBIDASI[1] = "表示する";
$YOBIDASI[2] = "表示しない";

#優先呼出管理室

$YUSENKANRISITU[0] = "管理室1";
$YUSENKANRISITU[1] = "管理室2";
$YUSENKANRISITU[2] = "管理室3";
$YUSENKANRISITU[3] = "管理室4";
$YUSENKANRISITU[4] = "マンションコントローラー";

$YUSENKANRISITU2[0] = "管理室C1";
$YUSENKANRISITU2[1] = "管理室C2";
$YUSENKANRISITU2[2] = "管理室C3";
$YUSENKANRISITU2[3] = "管理室C4";
$YUSENKANRISITU2[4] = "管理室C5";
$YUSENKANRISITU2[5] = "管理室C6";
$YUSENKANRISITU2[6] = "管理室C7";
$YUSENKANRISITU2[7] = "管理室C8";
$YUSENKANRISITU2[8] = "管理室1";
$YUSENKANRISITU2[9] = "管理室2";
$YUSENKANRISITU2[10] = "管理室3";
$YUSENKANRISITU2[11] = "管理室4";
$YUSENKANRISITU2[12] = "マンションコントローラー";

$YOBIDASIMEI[0] = "システム名称指定";
$YOBIDASIMEI[1] = "相談";


		$sheet->setCellValue($JUTAKUKANRISETTEI[1], $YOBIDASI[$wJutakuKanriSettei1]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[2], $YOBIDASI[$wJutakuKanriSettei2]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[3], $YOBIDASI[$wJutakuKanriSettei3]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[4], $YOBIDASI[$wJutakuKanriSettei4]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[5], $YOBIDASI[$wJutakuKanriSettei5]);

		$sheet->setCellValue($JUTAKUKANRISETTEI[6], $YUSENKANRISITU[$wJutakuKanriSettei6]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[7], $YOBIDASIMEI[$wJutakuKanriSettei7]);

		$sheet->setCellValue($JUTAKUKANRISETTEI[8], $YOBIDASI[$wJutakuKanriSettei8]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[9], $YOBIDASI[$wJutakuKanriSettei9]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[10], $YOBIDASI[$wJutakuKanriSettei10]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[11], $YOBIDASI[$wJutakuKanriSettei11]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[12], $YOBIDASI[$wJutakuKanriSettei12]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[13], $YOBIDASI[$wJutakuKanriSettei13]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[14], $YOBIDASI[$wJutakuKanriSettei14]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[15], $YOBIDASI[$wJutakuKanriSettei15]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[16], $YOBIDASI[$wJutakuKanriSettei16]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[17], $YOBIDASI[$wJutakuKanriSettei17]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[18], $YOBIDASI[$wJutakuKanriSettei18]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[19], $YOBIDASI[$wJutakuKanriSettei19]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[20], $YOBIDASI[$wJutakuKanriSettei20]);

		$sheet->setCellValue($JUTAKUKANRISETTEI[21], $YUSENKANRISITU2[$wJutakuKanriSettei21]);
		$sheet->setCellValue($JUTAKUKANRISETTEI[22], $YOBIDASIMEI[$wJutakuKanriSettei22]);


#遅延時間2
$TIENJIKAN2[0] = "300秒";
$TIENJIKAN2[1] = "600秒";

#室内解除
$SITUNAIKAIJO[0] = "使用しない";
$SITUNAIKAIJO[1] = "即時解除する";
$SITUNAIKAIJO[2] = "暗証番号解除する";

#管理用暗証番号
$KANRIANSHO[0] = "使用しない";
$KANRIANSHO[1] = "5分後使用";
$KANRIANSHO[2] = "15分後使用";
$KANRIANSHO[3] = "25分後使用";
$KANRIANSHO[4] = "25分後簡易解除";


		$sheet->setCellValue($JUTAKUBOUHANSETTEI[1], $MEIDO[$wJutakuBouhanSettei1]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[2], $MEIDO[$wJutakuBouhanSettei2]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[3], $TIENJIKAN[$wJutakuBouhanSettei3]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[4], $SHUTURYOKU[$wJutakuBouhanSettei4]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[5], $TIENJIKAN2[$wJutakuBouhanSettei5]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[6], $SIYOU[$wJutakuBouhanSettei6]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[7], $SIYOU[$wJutakuBouhanSettei7]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[8], $SITUNAIKAIJO[$wJutakuBouhanSettei8]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[9], $SIYOU[$wJutakuBouhanSettei9]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[10], $KANRIANSHO[$wJutakuBouhanSettei10]);
		$sheet->setCellValue($JUTAKUBOUHANSETTEI[11], $RENDOU[$wJutakuBouhanSettei11]);

#ワイド設定
$WIDE[0] = "ワイド大";
$WIDE[1] = "ワイド中";
$WIDE[2] = "ズーム固定";

#音量
$SONOTAONRYO[0] = "切";
$SONOTAONRYO[1] = "小";
$SONOTAONRYO[2] = "中";
$SONOTAONRYO[3] = "大";
$SONOTAONRYO[4] = "特大";

#増設子機
$ZOUSETUKOKI[0] = "VH/VJ増設機";
$ZOUSETUKOKI[1] = "QEH-1CD";

#玄関子機の追加　台数
$GENKANKOKIDAISU[0] = "1台";
$GENKANKOKIDAISU[1] = "2台";

#玄関子機の追加　カメラの配置
$GENKANKOKICAMERA[0] = "玄関1";
$GENKANKOKICAMERA[1] = "玄関2";


#電気錠の接続
$DENKIJO[0] = "接続しない";
$DENKIJO[1] = "玄関1";
$DENKIJO[2] = "玄関2";
$DENKIJO[3] = "玄関1と2";


#ホーム画面固定の設定
$HOMEGAMEN[0]="ユーザー選択";
$HOMEGAMEN[1]="通常画面固定";
$HOMEGAMEN[2]="防犯重視固定";
$HOMEGAMEN[3]="シニア1固定";
$HOMEGAMEN[4]="シニア2固定";
$HOMEGAMEN[5]="シニア3固定";

		$sheet->setCellValue($GENKANKOKISETTEI[1], $GENKANKOKIDAISU[$wGenkanKokiSettei1]);
		$sheet->setCellValue($GENKANKOKISETTEI[2], $GENKANKOKICAMERA[$wGenkanKokiSettei2]);
		$sheet->setCellValue($GENKANKOKISETTEI[3], $WIDE[$wGenkanKokiSettei3]);
		#$sheet->setCellValue($GENKANKOKISETTEI[4], $WIDE[$wGenkanKokiSettei4]);

		$sheet->setCellValue($DENKIJOSETTEI[1], $DENKIJO[$wDenkijoSettei1]);

		$sheet->setCellValue($SONOTASETTEI[1], $MEIDO[$wSonotaSettei1]);
		$sheet->setCellValue($SONOTASETTEI[2], $SIYOU[$wSonotaSettei2]);
		$sheet->setCellValue($SONOTASETTEI[3], $SONOTAONRYO[$wSonotaSettei3]);
		$sheet->setCellValue($SONOTASETTEI[4], $HYOUJI[$wSonotaSettei4]);
		$sheet->setCellValue($SONOTASETTEI[5], $HOMEGAMEN[$wSonotaSettei5]);

		for($i=0;$i<count($JutakuSetteiPlace);$i++){
#echo "i==".$i;
#echo "value==".$JutakuSetteiPlace[$i];
			$spreadsheet->getSheetByName('親機設定表')->getStyle($JutakuSetteiPlace[$i])->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFBAB3');
		}
#		showSorryPage(_ILLEGAL_ACCESS);




	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "VIXUS1Pr機器設定指示書".date('Ymd').".xlsx" ;
	$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

	header("Content-Disposition: attachment; filename=".$wFileName );
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //バッファ消去

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');

?>
