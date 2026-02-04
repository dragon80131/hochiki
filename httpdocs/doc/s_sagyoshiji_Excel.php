<?php

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

	include_once  "../include/common.php";


	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 認証動作
	########################################################

	$rKey = SPFWParameter::getValues('rKey');

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
	# 物件情報取得
	########################################################
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken = new Bukken($myDB);

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	if ($myBukken->RecCnt != 1) {
		trigger_error("Getting myBukken List Failed.", E_USER_ERROR);#ありえない

	} else {
	#必要な項目　マンション名、工事名称、住所、戸数、管理会社、消防特例、管理会社、管理員関係
#★
		$wBukkenCD = $myBukken->BukkenCD;
		$wBukkenName = $myBukken->BukkenName;
#		$wBukkenKanriNo = $myBukken->BukkenKanriNo;#物件管理番号
		$wTantoCD = $myBukken->TantoCD;
		$wShozokuCD = $myBukken->ShozokuCD;
		$wAddress = $myBukken->Address;
		$wShoboTokurei = $myBukken->ShoboTokurei;#0:特例なし　1:170号　2:220号住戸用　3:220号共住用
#		${"ShoboTokureiChecked".$wShoboTokurei} = " checked ";

		$wKosu = $myBukken->Kosu;
		$wKanriGaisya = $myBukken->KanriGaisya;
		$wKanriGaisyaTanto = $myBukken->KanriGaisyaTanto;
		$wKanriGaisyaTEL = $myBukken->KanriGaisyaTEL;
		$wKanriinName = $myBukken->KanriinName;
		$wKanriKinmu = $myBukken->KanriKinmu;
		$wKanriTEL = $myBukken->KanriTEL;
		$wKanriGaisyaTantoEMail = $myBukken->KanriGaisyaTantoEMail;



		$wOyaKataban = $myBukken->OyaKataban;
		$wKokiKataban = $myBukken->KokiKataban;

	}
	unset($myBukken);

	########################################################
	# 工事情報取得
	########################################################
	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}
	#複写の前に確保
	$wBukkenCD = $editBukkenCD;
	$wKojiCD = $myKoji->KojiCD;
	$wKojiName = $myKoji->KojiName;
	$wKojiTantoName = $myKoji->KojiTantoName;
	$wKojiShozokuName = $myKoji->KojiShozokuName;
	$wOyaPanel = $myKoji->OyaPanel;	#ADD 201903
	$wKoPanel = $myKoji->KoPanel;	#ADD 201903

	if($myKoji->ZentaiStartDate){
		$wZentaiStartDate = $myKoji->ZentaiStartDate;
		$wZentaiEndDate = $myKoji->ZentaiEndDate;
	}

	if($myKoji->KyoyoStartDate){
		$wKyoyoStartDate = $myKoji->KyoyoStartDate;
		$wKyoyoEndDate = $myKoji->KyoyoEndDate;
	}

	if($myKoji->SenyuStartDate){
		$wSenyuStartDate = $myKoji->SenyuStartDate;
		$wSenyuEndDate = $myKoji->SenyuEndDate;
	}

	if($myKoji->YobiStartDate){
		$wYobiStartDate = $myKoji->YobiStartDate;
		$wYobiEndDate = $myKoji->YobiEndDate;
	}
	if($myKoji->SanpaiKaishuDate ){
		$wSanpaiKaishuDate = $myKoji->SanpaiKaishuDate;
	}

	if ($myKoji->RecCnt != 1) {	#もしレコードがなかったら新規工事情報登録
		echo "工事情報登録を行う必要があります。";
		exit;
	}
	#登録済み工事情報表示
#		if($myKoji->KojiCD)$wKojiCD 		= $myKoji->KojiCD;
#		if($myKoji->ClientCD)$wClientCD 	= $myKoji->ClientCD;



		if($myKoji->ShoboTokurei)$wShoboTokurei = $myKoji->ShoboTokurei;
		${"ShoboTokureiChecked".$wShoboTokurei} = " checked ";
		$wSekoShutai = $myKoji->SekoShutai;
		$wGyosyaTantoCD1 = $myKoji->GyosyaTantoCD1;
		$GyosyaTantoCD1Selected[$GyosyaKey[$wGyosyaTantoCD1] ] = " selected ";

		$wGyosyaTantoCD2 = $myKoji->GyosyaTantoCD2;
		$GyosyaTantoCD2Selected[$GyosyaKey[$wGyosyaTantoCD2] ] = " selected ";

		#◆　工事種別・工事概要
		#$wRNstateFlg = $myKoji->RNstateFlg;
		#${"RNstateFlgChecked".$wRNstateFlg} = " checked ";
		$wRNsystem = $myKoji->RNsystem;
		$RNsystemName = $RNSYSTEMNAME[$wRNsystem];#489propertiesに定義
		#$RNsystemSelected[$wRNsystem] =" selected ";

#		$wGasKeihoRNFlg = $myKoji->GasKeihoRNFlg;
#		${"GasKeihoRNFlgChecked".$wGasKeihoRNFlg} = " checked ";

		$wGasBaseFlg = $myKoji->GasBaseFlg;
#		${"GasBaseFlgChecked".$wGasBaseFlg} = " checked ";

#		$wKasaiRNFlg = $myKoji->KasaiRNFlg;
#		${"KasaiRNFlgChecked".$wKasaiRNFlg} = " checked ";

		$wKasaiChukeiRNFlg = $myKoji->KasaiChukeiRNFlg;
#		${"KasaiChukeiRNFlgChecked".$wKasaiChukeiRNFlg} = " checked ";

		$wKasaiKyoyuRNFlg = $myKoji->KasaiKyoyuRNFlg;
		${"KasaiKyoyuRNFlgChecked".$wKasaiKyoyuRNFlg} = " checked ";

		$wSonotaKojiFlg = $myKoji->SonotaKojiFlg;
		${"SonotaKojiFlgChecked".$wSonotaKojiFlg} = " checked ";

		$wSonotaKojiNaiyo = $myKoji->SonotaKojiNaiyo;

		$wSanpaiBiko = $myKoji->SanpaiBiko;
		$wKojiShijiBiko = $myKoji->KojiShijiBiko;

		$wSonotaKoji = $myKoji->SonotaKoji;
		$wCurrentNyukan = $myKoji->CurrentNyukan;
		$wCurrentNyukan = SPFWTools::decodePluralValue($wCurrentNyukan);#配列
		#数字がはいってる　複数はいってる　１　２　０
		for($dd=0; $dd<count( $wCurrentNyukan ); $dd++){
			${"CurrentNyukanChecked".$wCurrentNyukan[$dd]} = " checked ";
		}

		$wCurrentNyukanSonota = $myKoji->CurrentNyukanSonota;
		$wRNNyukan = $myKoji->RNNyukan;
		$wRNNyukan = SPFWTools::decodePluralValue($wRNNyukan);#配列
		for($dd=0; $dd<count( $wRNNyukan ); $dd++){
			${"RNNyukanChecked".$wRNNyukan[$dd]} = " checked ";
		}
		$wRNNyukanSonota = $myKoji->RNNyukanSonota;
		$wJikahoRendoFlg = $myKoji->JikahoRendoFlg;
		$wJikaho = $myKoji->Jikaho;#自火報が２＝火災感知器交換

#◆　設備
		$wShizaiOkiba = $myKoji->ShizaiOkiba;
		$wShizaiOkibaFlg = $myKoji->ShizaiOkibaFlg;
		${"ShizaiOkibaFlgChecked".$wShizaiOkibaFlg} = " checked ";

		$wDoguOkiba = $myKoji->DoguOkiba;
		$wDoguOkibaFlg = $myKoji->DoguOkibaFlg;
		${"DoguOkibaFlgChecked".$wDoguOkibaFlg} = " checked ";

		$wSanpaiOkiba = $myKoji->SanpaiOkiba;
		$wSanpaiOkibaFlg = $myKoji->SanpaiOkibaFlg;
		${"SanpaiOkibaFlgChecked".$wSanpaiOkibaFlg} = " checked ";

		$wKyukei = $myKoji->Kyukei;
		$wKyukeiFlg = $myKoji->KyukeiFlg;
		${"KyukeiFlgChecked".$wKyukeiFlg} = " checked ";


		$wToilet = $myKoji->Toilet;
		$wToiletFlg = $myKoji->ToiletFlg;
		${"ToiletFlgChecked".$wToiletFlg} = " checked ";


		$wSmoking = $myKoji->Smoking;
		$wSmokingFlg = $myKoji->SmokingFlg;
		${"SmokingFlgChecked".$wSmokingFlg} = " checked ";
		$wRentKey = $myKoji->RentKey;
		$wRentKeyFlg = $myKoji->RentKeyFlg;
		${"RentKeyFlgChecked".$wRentKeyFlg} = " checked ";
		$wParking = $myKoji->Parking;
		$wParkingFlg = $myKoji->ParkingFlg;
		${"ParkingFlgChecked".$wParkingFlg} = " checked ";
		$wSetubiBiko = $myKoji->SetubiBiko;

#警報
		$wKeibiCompany = $myKoji->KeibiCompany;
		$wKeibiCompanyTEL = $myKoji->KeibiCompanyTEL;


		$wHijyo = $myKoji->Hijyo;
		${"HijyoChecked".$wHijyo} = " checked ";
		$wHijyoIho = $myKoji->HijyoIho;
		${"HijyoIhoChecked".$wHijyoIho} = " checked ";
		$wHijyoIhoShubetu =  $myKoji->HijyoIhoShubetu;


		$wGas = $myKoji->Gas;
		${"GasChecked".$wGas} = " checked ";
		$wGasIho = $myKoji->GasIho;
		${"GasIhoChecked".$wGasIho} = " checked ";
		$wGasIhoShubetu =  $myKoji->GasIhoShubetu;

		$wKasai = $myKoji->Kasai;
		${"KasaiChecked".$wKasai} = " checked ";
		$wKasaiIho = $myKoji->KasaiIho;
		${"KasaiIhoChecked".$wKasaiIho} = " checked ";
		$wKasaiIhoShubetu =  $myKoji->KasaiIhoShubetu;

		$wTrouble = $myKoji->Trouble;
		${"TroubleChecked".$wTrouble} = " checked ";
		$wTroubleIho = $myKoji->TroubleIho;
		${"TroubleIhoChecked".$wTroubleIho} = " checked ";
		$wTroubleIhoShubetu =  $myKoji->TroubleIhoShubetu;

		$wBohan = $myKoji->Bohan;
		${"BohanChecked".$wBohan} = " checked ";
		$wBohanIho = $myKoji->BohanIho;
		${"BohanIhoChecked".$wBohanIho} = " checked ";
		$wBohanIhoShubetu =  $myKoji->BohanIhoShubetu;

		$wKanki = $myKoji->Kanki;
		${"KankiChecked".$wKanki} = " checked ";
		$wKankiIho = $myKoji->KankiIho;
		${"KankiIhoChecked".$wKankiIho} = " checked ";
		$wKankiIhoShubetu =  $myKoji->KankiIhoShubetu;



		$wSonota1 = $myKoji->Sonota1;
		${"Sonota1Checked".$wSonota1} = " checked ";
		$wSonota1Iho = $myKoji->Sonota1Iho;
		${"Sonota1IhoChecked".$wSonota1Iho} = " checked ";
		$wSonota1IhoShubetu =  $myKoji->Sonota1IhoShubetu;

		$wSonota2 = $myKoji->Sonota2;
		${"Sonota2Checked".$wSonota2} = " checked ";
		$wSonota2Iho = $myKoji->Sonota2Iho;
		${"Sonota2IhoChecked".$wSonota2Iho} = " checked ";
		$wSonota2IhoShubetu =  $myKoji->Sonota2IhoShubetu;

		$wSonota3 = $myKoji->Sonota3;
		${"Sonota3Checked".$wSonota3} = " checked ";
		$wSonota3Iho = $myKoji->Sonota3Iho;
		${"Sonota3IhoChecked".$wSonota3Iho} = " checked ";
		$wSonota3IhoShubetu =  $myKoji->Sonota3IhoShubetu;

		$wKeihoBiko = $myKoji->KeihoBiko;


#オプション工事
#★
		########################################################
		# Deviceリスト表示
		########################################################
		$myListObject = new SPFWListObject($myDB);

		$sql = "SELECT ";
		$sql .= "DeviceCD, ";	#機器CD
#		$sql .= "DeviceName, ";	#機器名
		$sql .= "Kataban ";	#型番
#		$sql .= "Category ";	#カテゴリ
		$myListObject->SelectSQL = $sql;
		$sql = " FROM tDeviceM ";
		$sql .= " WHERE  MukouFlg = FALSE ";#全員表示される。
		$myListObject->Condition = $sql;
		$myListObject->Order = "Kataban ";#表示順
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting Menu List Failed.", E_USER_ERROR);

		$OyaDeviceLoop = $myListObject->Rows;
		for ($i = 0; $i < $OyaDeviceLoop; $i++) {
			$DeviceCD[$i] = $myListObject->GetValue($i, 0);
			$Kataban[$DeviceCD[$i]] = $myListObject->GetValue($i, 1);
		}
		unset($myListObject);


		$wTakuhai = $myKoji->Takuhai;
		${"TakuhaiChecked".$wTakuhai} = " checked ";
		$wTakuhaiCompany = $myKoji->TakuhaiCompany;
		$wTakuhaiCompanyTEL = $myKoji->TakuhaiCompanyTEL;
		$wTakuhaiSetuzoku = $myKoji->TakuhaiSetuzoku;


		$wOP1DeviceCD = $myKoji->OP1DeviceCD;
		$wOP1Kataban = $Kataban[$wOP1DeviceCD];
		$wOPBiko1 = $myKoji->OPBiko1;
		$wOPZiyuu1 = $myKoji->OPZiyuu1;

		$wOP2DeviceCD = $myKoji->OP2DeviceCD;
		$wOP2Kataban = $Kataban[$wOP2DeviceCD];
#		$wOPBiko2 = $myKoji->OPBiko2;
		$wOPZiyuu2 = $myKoji->OPZiyuu2;

		$wOP3DeviceCD = $myKoji->OP3DeviceCD;
		$wOP3Kataban = $Kataban[$wOP3DeviceCD];
#		$wOPBiko3 = $myKoji->OPBiko3;
		$wOPZiyuu3 = $myKoji->OPZiyuu3;

		$wOP4DeviceCD = $myKoji->OP4DeviceCD;
		$wOP4Kataban = $Kataban[$wOP4DeviceCD];
#		$wOPBiko4 = $myKoji->OPBiko4;
		$wOPZiyuu4 = $myKoji->OPZiyuu4;


		$wOP5DeviceCD = $myKoji->OP5DeviceCD;
		$wOP5Kataban = $Kataban[$wOP5DeviceCD];
#		$wOPBiko5 = $myKoji->OPBiko5;
		$wOPZiyuu5 = $myKoji->OPZiyuu5;

		$wOP6DeviceCD = $myKoji->OP6DeviceCD;
		$wOP6Kataban = $Kataban[$wOP6DeviceCD];
#		$wOPBiko6 = $myKoji->OPBiko6;
		$wOPZiyuu6 = $myKoji->OPZiyuu6;

		$wOP7DeviceCD = $myKoji->OP7DeviceCD;
		$wOP7Kataban = $Kataban[$wOP7DeviceCD];
#		$wOPBiko7 = $myKoji->OPBiko7;
		$wOPZiyuu7 = $myKoji->OPZiyuu7;

		$wOP8DeviceCD = $myKoji->OP8DeviceCD;
		$wOP8Kataban = $Kataban[$wOP8DeviceCD];
#		$wOPBiko8 = $myKoji->OPBiko8;
		$wOPZiyuu8 = $myKoji->OPZiyuu8;


		$wOP9DeviceCD = $myKoji->OP9DeviceCD;
		$wOP9Kataban = $Kataban[$wOP9DeviceCD];
#		$wOPBiko9 = $myKoji->OPBiko9;
		$wOPZiyuu9 = $myKoji->OPZiyuu9;


		$wOP10DeviceCD = $myKoji->OP10DeviceCD;
		$wOP10Kataban = $Kataban[$wOP10DeviceCD];
#		$wOPBiko10 = $myKoji->OPBiko10;
		$wOPZiyuu10 = $myKoji->OPZiyuu10;


		if( $myKoji->OPPrice1 )$wOPPrice1 = number_format( $myKoji->OPPrice1 )."(税込)";
		if( $myKoji->OPPrice2 )$wOPPrice2 = number_format( $myKoji->OPPrice2 )."(税込)";
		if( $myKoji->OPPrice3 )$wOPPrice3 = number_format( $myKoji->OPPrice3 )."(税込)";
		if( $myKoji->OPPrice4 )$wOPPrice4 = number_format( $myKoji->OPPrice4 )."(税込)";
		if( $myKoji->OPPrice5 )$wOPPrice5 = number_format( $myKoji->OPPrice5 )."(税込)";
		if( $myKoji->OPPrice6 )$wOPPrice6 = number_format( $myKoji->OPPrice6 )."(税込)";
		if( $myKoji->OPPrice7 )$wOPPrice7 = number_format( $myKoji->OPPrice7 )."(税込)";
		if( $myKoji->OPPrice8 )$wOPPrice8 = number_format( $myKoji->OPPrice8 )."(税込)";
		if( $myKoji->OPPrice9 )$wOPPrice9 = number_format( $myKoji->OPPrice9 )."(税込)";
		if( $myKoji->OPPrice10 )$wOPPrice10 = number_format( $myKoji->OPPrice10 )."(税込)";

		$wOPNotes = $myKoji->OPNotes;#未使用
		$wSekoKyoyoKikiPic = $myKoji->SekoKyoyoKikiPic;
		$wSekoSenyuKikiPic = $myKoji->SekoSenyuKikiPic;
		$wSekoHaisenPic = $myKoji->SekoHaisenPic;
		$wSekoKyoyoKikiPicFlg = $myKoji->SekoKyoyoKikiPicFlg;
		$wSekoSenyuKikiPicFlg = $myKoji->SekoSenyuKikiPicFlg;
		$wSekoHaisenPicFlg = $myKoji ->SekoHaisenPicFlg;

#インターホン設備更新工事　使用部材
		$wSiyobuzaiOyaFlg = $myKoji->SiyobuzaiOyaFlg;
		${"SiyobuzaiOyaFlgChecked".$wSiyobuzaiOyaFlg} = " checked ";
		$wSiyobuzaiOyaBiko = $myKoji->SiyobuzaiOyaBiko;
		$wSiyobuzaiOyaKataban = ($wOyaPanel) ? $Kataban[$wOyaPanel]."　" : ""; 	#ADD 201903

		$wSiyobuzaiKoFlg = $myKoji->SiyobuzaiKoFlg;
		${"SiyobuzaiKoFlgChecked".$wSiyobuzaiKoFlg} = " checked ";
		$wSiyobuzaiKoBiko = $myKoji->SiyobuzaiKoBiko;
		$wSiyobuzaiKoKataban = ($wKoPanel) ? $Kataban[$wKoPanel]."　" : ""; 	#ADD 201903


		$wSiyobuzaiSyugenFlg = $myKoji->SiyobuzaiSyugenFlg;
		${"SiyobuzaiSyugenFlgChecked".$wSiyobuzaiSyugenFlg} = " checked ";
		$wSiyobuzaiSyugenBiko = $myKoji->SiyobuzaiSyugenBiko;


		$wSiyobuzaiKanOyaFlg = $myKoji->SiyobuzaiKanOyaFlg;
		${"SiyobuzaiKanOyaFlgChecked".$wSiyobuzaiKanOyaFlg} = " checked ";
		$wSiyobuzaiKanOyaBiko = $myKoji->SiyobuzaiKanOyaBiko;

#施工写真
		$wSekoKyoyoKikiPic = $myKoji->SekoKyoyoKikiPic;#0:前後　1:前中後
		${"SekoKyoyoKikiPicChecked".$wSekoKyoyoKikiPic} = " checked ";
		$wSekoKyoyoKikiPicBiko = $myKoji->SekoKyoyoKikiPicBiko;

		$wSekoSenyuKikiPic = $myKoji->SekoSenyuKikiPic;#0:前後　1:前中後
		${"SekoSenyuKikiPicChecked".$wSekoSenyuKikiPic} = " checked ";
		$wSekoSenyuKikiPicBiko = $myKoji->SekoSenyuKikiPicBiko;


		$wSekoHaisenPic = $myKoji->SekoHaisenPic;#0:前後　1:前中後
		${"SekoHaisenPicChecked".$wSekoHaisenPic} = " checked ";
		$wSekoHaisenPicBiko = $myKoji->SekoHaisenPicBiko;

#暗証番号


		$wAnshoNo = $myKoji->AnshoNo;
		${"AnshoNoChecked".$wAnshoNo} = " checked ";#0:有　1:無
		$wAnshoNoBiko = $myKoji->AnshoNoBiko;
		$wAnshoNoKojichu = $myKoji->AnshoNoKojichu;

#パネル
		#$wPanelFlg = $myKoji->PanelFlg;
		#${"PanelFlgChecked".$wPanelFlg} = " checked ";#0:有　1:無
		$wOyaPanel = $myKoji->OyaPanel;
#		${"OyaPanelChecked".$wOyaPanel} = " selected ";#0:有　1:無
		$OyaPanelDeviceCDSelected[$PanelOPSelectNo[$wOyaPanel]] = " selected ";

		$wKoPanel = $myKoji->KoPanel;
#		${"KoPanelChecked".$wKoPanel} = " selected ";#0:有　1:無

		$KoPanelDeviceCDSelected[$PanelOPSelectNo[$wKoPanel]] = " selected ";
#		$wPanelGyosya = $myKoji->PanelGyosya;

#工事前準備
		$wGetHeyaNoFlg = $myKoji->GetHeyaNoFlg;
		${"GetHeyaNoFlgChecked".$wGetHeyaNoFlg} = " checked ";#0:有　1:無
		$wOrderKanriFutoFlg = $myKoji->OrderKanriFutoFlg;
		${"OrderKanriFutoFlgChecked".$wOrderKanriFutoFlg} = " checked ";#0:有　1:無





	$template_file = './template/sagyoshiji.xlsx';
	$filename = "作業連絡書";
	$weekArray = array("(日)","(月)","(火)","(水)","(木)","(金)","(土)");
	########################################################
	# Excelファイル生成
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();

	$spreadsheet = $reader->load( $template_file ); //template.xlsx 読込

	$sheet = $spreadsheet->getActiveSheet();

	#基本情報
	$sheet->setCellValue('F6', $wBukkenName);
	$sheet->setCellValue('F7', $wKojiName);
	$sheet->setCellValue('F8', $wAddress);
	$sheet->setCellValue('W8', $wKosu);
	$sheet->setCellValue('F9', $wKanriGaisya);
	$sheet->setCellValue('W9', $wKanriGaisyaTanto);
	switch( $wShoboTokurei ){
	case 0:#特例なし
		$RangeShoboTokurei = "F10";
		break;
	case 1:#170号
		$RangeShoboTokurei = "K10";
		break;
	case 2:#220号住戸用
		$RangeShoboTokurei = "P10";
		break;
	case 3:#220号共住用
		$RangeShoboTokurei = "U10";
		break;
	}
	$sheet->setCellValue( $RangeShoboTokurei, "■");
	$sheet->setCellValue('F11', $wKanriinName);
	$sheet->setCellValue('N11', $wKanriTEL);
	$sheet->setCellValue('F12', $wKanriKinmu);

	#工事種別・工事概要
	$sheet->setCellValue( "H14", "■");#インターホン設備更新はありで。
	$sheet->setCellValue( "O14", $RNsystemName); //基本システム

	$sss = ( $wGasKoji > 1)?"H15":"J15";#あり：なし
	$sheet->setCellValue( $sss, "■");

	$sss = ( $wGasBaseFlg == "0")?"Q15":"S15";#あり：なし
	$sheet->setCellValue( $sss, "■");

	$sss = ( $wJikaho == "2")?"H16":"J16";#あり：なし　専有部感知器交換　２があり
	$sheet->setCellValue( $sss, "■");

	$sss = ( $wKasaiChukeiRNFlg == "0")?"Q16":"S16";#あり：なし　中継器
	$sheet->setCellValue( $sss, "■");

	$sss = ( $wKasaiKyoyoRNFlg == "0")?"X16":"Z16";#あり：なし 受信機交換
	$sheet->setCellValue( $sss, "■");

#	$sss = ( $wSonotaKojiFlg == "0")?"X17":"Z17";#あり：なし その他工事
#	$sheet->setCellValue( $sss, "■");

	$sheet->setCellValue( "H17", $wSonotaKojiNaiyo);


#日付
	$yobi = $weekArray[ date('w',strtotime( $wZentaiStartDate )) ];
	$sheet->setCellValue( "F18", date('Y年n月j日',strtotime( $wZentaiStartDate) ).$yobi );

	$yobi = $weekArray[ date('w',strtotime( $wZentaiEndDate )) ];
	$sheet->setCellValue( "M18", date('Y年n月j日',strtotime( $wZentaiEndDate) ).$yobi );

	$yobi = $weekArray[ date('w',strtotime( $wKyoyoStartDate )) ];
	$sheet->setCellValue( "F19", date('Y年n月j日',strtotime( $wKyoyoStartDate) ).$yobi );

	$yobi = $weekArray[ date('w',strtotime( $wKyoyoEndDate )) ];
	$sheet->setCellValue( "M19",  date('Y年n月j日',strtotime( $wKyoyoEndDate) ).$yobi );

	$yobi = $weekArray[ date('w',strtotime( $wSenyuStartDate )) ];
	$sheet->setCellValue( "F20", date('Y年n月j日',strtotime( $wSenyuStartDate) ).$yobi );

	$yobi = $weekArray[ date('w',strtotime( $wSenyuEndDate )) ];
	$sheet->setCellValue( "M20", date('Y年n月j日',strtotime( $wSenyuEndDate) ).$yobi );

	if($wYobiStartDate){
		$yobi = $weekArray[ date('w',strtotime( $wYobiStartDate )) ];
		$sheet->setCellValue( "F21", date('Y年n月j日',strtotime( $wYobiStartDate) ).$yobi );

		$yobi = $weekArray[ date('w',strtotime( $wYobiEndDate )) ];
		$sheet->setCellValue( "M21", date('Y年n月j日',strtotime( $wYobiEndDate) ).$yobi );
	}

	if($wSanpaiKaishuDate){
		$yobi = $weekArray[ date('w',strtotime( $wSanpaiKaishuDate )) ];
		$sheet->setCellValue( "F22", date('Y年n月j日',strtotime( $wSanpaiKaishuDate) ).$yobi );
	}
	#作業時間：9時～17時を厳守。作業時間外の作業となる場合は、事前に連絡をお願いします。
	#最終日の作業後、インターホン工事に関する掲示は撤去して下さい。
 	if($wKojiShijiBiko){
		$sheet->setCellValue( "F23", $wKojiShijiBiko );
	}
#◆　設備

	$sss = ( $wShizaiOkibaFlg == "0")?"F27":"H27";#あり：なし　資材置き場0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L27", $wShizaiOkiba);

	$sss = ( $wDoguOkibaFlg == "0")?"F28":"H28";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L28", $wDoguOkiba);

	$sss = ( $wSanpaiOkibaFlg == "0")?"F29":"H29";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L29", $wSanpaiOkiba);

	$sss = ( $wKyukeiFlg == "0")?"F30":"H30";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L30", $wKyukei);


	$sss = ( $wToiletFlg == "0")?"F31":"H31";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L31", $wToilet);


	$sss = ( $wSmokingFlg == "0")?"F32":"H32";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L32", $wSmoking);


	$sss = ( $wSmokingFlg == "0")?"F32":"H32";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L32", $wSmoking);


	$sss = ( $wRentKeyFlg == "0")?"F33":"H33";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L33", $wRentKey);

	$sss = ( $wParkingFlg == "0")?"F34":"H34";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");
	$sheet->setCellValue( "L34", $wParking);
	$sheet->setCellValue( "F35", $wSetubiBiko);


#警報
	$sheet->setCellValue( "F38", $wKeibiCompany);
	$sheet->setCellValue( "W38", $wKeibiCompanyTEL);

	$ddd = ( $wHijyo == "0")?"F39":"H39";#あり：なし　0:有
	$sheet->setCellValue( $ddd, "■");
	$eee = ( $wHijyoIho == "0")?"M39":"O39";#あり：なし　0:有
	$sheet->setCellValue( $eee, "■");
	if( strpos($wHijyoIhoShubetu,'0')!== false )$sheet->setCellValue( "T39", "■");
	if( strpos($wHijyoIhoShubetu,'1')!== false )$sheet->setCellValue( "V39", "■");
	if( strpos($wHijyoIhoShubetu,'2')!== false )$sheet->setCellValue( "X39", "■");

	$ddd = ( $wGas == "0")?"F40":"H40";#あり：なし　0:有
	$sheet->setCellValue( $ddd, "■");
	$eee = ( $wGasIho == "0")?"M40":"O40";#あり：なし　0:有
	$sheet->setCellValue( $eee, "■");
	if( strpos($wGasIhoShubetu,'0')!== false )$sheet->setCellValue( "T40", "■");
	if( strpos($wGasIhoShubetu,'1')!== false )$sheet->setCellValue( "V40", "■");
	if( strpos($wGasIhoShubetu,'2')!== false )$sheet->setCellValue( "X40", "■");


	$ddd = ( $wKasai == "0")?"F41":"H41";#あり：なし　0:有
	$sheet->setCellValue( $ddd, "■");
	$eee = ( $wKasaiIho == "0")?"M41":"O41";#あり：なし　0:有
	$sheet->setCellValue( $eee, "■");
	if( strpos($wKasaiIhoShubetu,'0')!== false )$sheet->setCellValue( "T41", "■");
	if( strpos($wKasaiIhoShubetu,'1')!== false )$sheet->setCellValue( "V41", "■");
	if( strpos($wKasaiIhoShubetu,'2')!== false )$sheet->setCellValue( "X41", "■");



	$ddd = ( $wTrouble == "0")?"F42":"H42";#あり：なし　0:有
	$sheet->setCellValue( $ddd, "■");
	$eee = ( $wTroubleIho == "0")?"M42":"O42";#あり：なし　0:有
	$sheet->setCellValue( $eee, "■");
	if( strpos($wTroubleIhoShubetu,'0')!== false )$sheet->setCellValue( "T42", "■");
	if( strpos($wTroubleIhoShubetu,'1')!== false )$sheet->setCellValue( "V42", "■");
	if( strpos($wTroubleIhoShubetu,'2')!== false )$sheet->setCellValue( "X42", "■");


	$ddd = ( $wBohan == "0")?"F43":"H43";#あり：なし　0:有
	$sheet->setCellValue( $ddd, "■");
	$eee = ( $wBohanIho == "0")?"M43":"O43";#あり：なし　0:有
	$sheet->setCellValue( $eee, "■");
	if( strpos($wBohanIhoShubetu,'0')!== false )$sheet->setCellValue( "T43", "■");
	if( strpos($wBohanIhoShubetu,'1')!== false )$sheet->setCellValue( "V43", "■");
	if( strpos($wBohanIhoShubetu,'2')!== false )$sheet->setCellValue( "X43", "■");



	$ddd = ( $wKanki == "0")?"F44":"H44";#あり：なし　0:有
	$sheet->setCellValue( $ddd, "■");
	$eee = ( $wKankiIho == "0")?"M44":"O44";#あり：なし　0:有
	$sheet->setCellValue( $eee, "■");
	if( strpos($wKankiIhoShubetu,'0')!== false )$sheet->setCellValue( "T44", "■");
	if( strpos($wKankiIhoShubetu,'1')!== false )$sheet->setCellValue( "V44", "■");
	if( strpos($wKankiIhoShubetu,'2')!== false )$sheet->setCellValue( "X44", "■");



	$fff = ( $wSonota1 == "0")?"F45":"H45";#あり：なし　0:有
	$sheet->setCellValue( $fff, "■");
	$ggg = ( $wSonota1Iho == "0")?"M45":"O45";#あり：なし　0:有
	$sheet->setCellValue( $ggg, "■");
	if( strpos($wSonota1IhoShubetu,'0')!== false )$sheet->setCellValue( "T45", "■");
	if( strpos($wSonota1IhoShubetu,'1')!== false )$sheet->setCellValue( "V45", "■");
	if( strpos($wSonota1IhoShubetu,'2')!== false )$sheet->setCellValue( "X45", "■");


	$fff = ( $wSonota2 == "0")?"F46":"H46";#あり：なし　0:有
	$sheet->setCellValue( $fff, "■");
	$ggg = ( $wSonota2Iho == "0")?"M46":"O46";#あり：なし　0:有
	$sheet->setCellValue( $ggg, "■");
	if( strpos($wSonota2IhoShubetu,'0')!== false )$sheet->setCellValue( "T46", "■");
	if( strpos($wSonota2IhoShubetu,'1')!== false )$sheet->setCellValue( "V46", "■");
	if( strpos($wSonota2IhoShubetu,'2')!== false )$sheet->setCellValue( "X46", "■");

	$sheet->setCellValue( "F47", $wKeihoBiko);



#オプションwOP1DeviceCD
	if($wOPZiyuu1){
		$sheet->setCellValue( "AE6", $wOPZiyuu1 );
		$sheet->setCellValue( "AH6", "■");
		$sheet->setCellValue( "AN6", $wOPPrice1 );
	}elseif($wOP1Kataban){
		$sheet->setCellValue( "AE6", $wOP1Kataban );
		$hhh = ( $wOP1Kataban )?"AH6":"AJ6";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN6", $wOPPrice1 );
	}

	if($wOPZiyuu2){
		$sheet->setCellValue( "AE7", $wOPZiyuu2 );
		$sheet->setCellValue( "AH7", "■");
		$sheet->setCellValue( "AN7", $wOPPrice2 );
	}elseif($wOP2Kataban){
		$sheet->setCellValue( "AE7", $wOP2Kataban );
		$hhh = ( $wOP2Kataban )?"AH7":"AJ7";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN7", $wOPPrice2 );
	}

	if($wOPZiyuu3){
		$sheet->setCellValue( "AE8", $wOPZiyuu3 );
		$sheet->setCellValue( "AH8", "■");
		$sheet->setCellValue( "AN8", $wOPPrice3 );
	}elseif($wOP3Kataban){
		$sheet->setCellValue( "AE8", $wOP3Kataban );
		$hhh = ( $wOP3Kataban )?"AH8":"AJ8";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN8", $wOPPrice3 );
	}


	if($wOPZiyuu4){
		$sheet->setCellValue( "AE9", $wOPZiyuu4 );
		$sheet->setCellValue( "AH9", "■");
		$sheet->setCellValue( "AN9", $wOPPrice4 );
	}elseif($wOP4Kataban){
		$sheet->setCellValue( "AE9", $wOP4Kataban );
		$hhh = ( $wOP4Kataban )?"AH9":"AJ9";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN9", $wOPPrice4 );
	}


	if($wOPZiyuu5){
		$sheet->setCellValue( "AE10", $wOPZiyuu5 );
		$sheet->setCellValue( "AH10", "■");
		$sheet->setCellValue( "AN10", $wOPPrice5 );
	}elseif($wOP5Kataban){
		$sheet->setCellValue( "AE10", $wOP5Kataban );
		$hhh = ( $wOP5Kataban )?"AH10":"AJ10";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN10", $wOPPrice5 );
	}

	if($wOPZiyuu6){
		$sheet->setCellValue( "AE11", $wOPZiyuu6 );
		$sheet->setCellValue( "AH11", "■");
		$sheet->setCellValue( "AN11", $wOPPrice6 );
	}elseif($wOP6Kataban){
		$sheet->setCellValue( "AE11", $wOP6Kataban );
		$hhh = ( $wOP6Kataban )?"AH11":"AJ11";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN11", $wOPPrice6);
	}

	if($wOPZiyuu7){
		$sheet->setCellValue( "AE12", $wOPZiyuu7 );
		$sheet->setCellValue( "AH12", "■");
		$sheet->setCellValue( "AN12", $wOPPrice7 );
	}elseif($wOP7Kataban){
		$sheet->setCellValue( "AE12", $wOP7Kataban );
		$hhh = ( $wOP7Kataban )?"AH12":"AJ12";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN12", $wOPPrice7 );
	}


	if($wOPZiyuu8){
		$sheet->setCellValue( "AE13", $wOPZiyuu8 );
		$sheet->setCellValue( "AH13", "■");
		$sheet->setCellValue( "AN13", $wOPPrice8 );
	}elseif($wOP8Kataban){
		$sheet->setCellValue( "AE13", $wOP8Kataban );
		$hhh = ( $wOP8Kataban )?"AH13":"AJ13";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN13", $wOPPrice8 );
	}


	if($wOPZiyuu9){
		$sheet->setCellValue( "AE14", $wOPZiyuu9 );
		$sheet->setCellValue( "AH14", "■");
		$sheet->setCellValue( "AN14", $wOPPrice9 );
	}elseif($wOP9Kataban){
		$sheet->setCellValue( "AE14", $wOP9Kataban );
		$hhh = ( $wOP9Kataban )?"AH14":"AJ14";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN14", $wOPPrice9 );
	}


	if($wOPZiyuu10){
		$sheet->setCellValue( "AE15", $wOPZiyuu10 );
		$sheet->setCellValue( "AH15", "■");
		$sheet->setCellValue( "AN15", $wOPPrice10 );
	}elseif($wOP10Kataban){
		$sheet->setCellValue( "AE15", $wOP10Kataban );
		$hhh = ( $wOP10Kataban )?"AH15":"AJ15";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
		$sheet->setCellValue( "AN15", $wOPPrice10);
	}

	if($wOPBiko1){  #オプション備考
		$sheet->setCellValue( "AH12", $wOPBiko1 );
	}

	if($wSiyobuzaiOyaFlg!==""){
		$hhh = ( $wSiyobuzaiOyaFlg==0 )?"AH15":"AJ15";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
	}
	if($wSiyobuzaiOyaKataban　!=="" OR $wSiyobuzaiOyaBiko!==""){
		$sheet->setCellValue( "AN15", $wSiyobuzaiOyaKataban.$wSiyobuzaiOyaBiko);
	}
	if($wSiyobuzaiKoFlg!==""){
		$hhh = ( $wSiyobuzaiKoFlg==0 )?"AH16":"AJ16";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
	}
	if($wSiyobuzaiKoKataban !=="" OR $wSiyobuzaiKoBiko!==""){
		$sheet->setCellValue( "AN16", $wSiyobuzaiKoKataban.$wSiyobuzaiKoBiko);
	}
	if($wSiyobuzaiSyugenFlg!==""){
		$hhh = ( $wSiyobuzaiSyugenFlg==0 )?"AH17":"AJ17";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
	}
	if($wSiyobuzaiSyugenBiko!==""){
		$sheet->setCellValue( "AN17", $wSiyobuzaiSyugenBiko);
	}
	if($wSiyobuzaiKanOyaFlg!==""){
		$hhh = ( $wSiyobuzaiKanOyaFlg==0 )?"AH18":"AJ18";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
	}
	if($wSiyobuzaiKanOyaBiko!==""){
		$sheet->setCellValue( "AN18", $wSiyobuzaiKanOyaBiko);
	}


	if($wSekoKyoyoKikiPicFlg ==1 && $wSekoKyoyoKikiPic ==1){
		$sheet->setCellValue( "AH20","■");
		$sheet->setCellValue( "AJ20","■");
		$sheet->setCellValue( "AL20","■");
	}elseif($wSekoKyoyoKikiPicFlg ==1 && $wSekoKyoyoKikiPic ==2){
		$sheet->setCellValue( "AH20","■");
		$sheet->setCellValue( "AL20","■");
/*	}elseif ($wSekoKyoyoKikiPicFlg ==2 && $wSekoKyoyoKikiPic ==1) {
		$sheet->setCellValue( "AH20","■");
		$sheet->setCellValue( "AJ20","■");
		$sheet->setCellValue( "AL20","■");
		$sheet->setCellValue( "AP20","抜粋");
	}elseif ($wSekoKyoyoKikiPicFlg ==2 && $wSekoKyoyoKikiPic ==2) {
		$sheet->setCellValue( "AH20","■");
		$sheet->setCellValue( "AL20","■");
		$sheet->setCellValue( "AP20","抜粋");*/
	}else {
		$sheet->setCellValue( "AP20","不要");
	}


	if($wSekoSenyuKikiPicFlg ==1 && $wSekoSenyuKikiPic ==1){
		$sheet->setCellValue( "AH21","■");
		$sheet->setCellValue( "AJ21","■");
		$sheet->setCellValue( "AL21","■");
		$sheet->setCellValue( "AP21","専有部工事では、撮影前に必ず居住者様の了承を頂いて下さい。");
	}elseif($wSekoSenyuKikiPicFlg ==1 && $wSekoSenyuKikiPic ==2){
		$sheet->setCellValue( "AH21","■");
		$sheet->setCellValue( "AL21","■");
		$sheet->setCellValue( "AP21","専有部工事では、撮影前に必ず居住者様の了承を頂いて下さい。");
	}elseif ($wSekoSenyuKikiPicFlg ==2 && $wSekoSenyuKikiPic ==1) {
		$sheet->setCellValue( "AH21","■");
		$sheet->setCellValue( "AJ21","■");
		$sheet->setCellValue( "AL21","■");
		$sheet->setCellValue( "AP21","抜粋  専有部工事では、撮影前に必ず居住者様の了承を頂いて下さい。");
	}elseif ($wSekoSenyuKikiPicFlg ==2 && $wSekoSenyuKikiPic ==2) {
		$sheet->setCellValue( "AH21","■");
		$sheet->setCellValue( "AL21","■");
		$sheet->setCellValue( "AP21","抜粋  専有部工事では、撮影前に必ず居住者様の了承を頂いて下さい。");
	}else {
		$sheet->setCellValue( "AP21","不要");
	}


	if($wSekoHaisenPicFlg == 0 && $wSekoHaisenPic == 1){
		$sheet->setCellValue( "AH22", "■");
		$sheet->setCellValue( "AJ22", "■");
		$sheet->setCellValue( "AL22", "■");
	}elseif($wSekoHaisenPicFlg == 0 && $wSekoHaisenPic == 0){
		$sheet->setCellValue( "AH22", "■");
		$sheet->setCellValue( "AL22", "■");
	}else {
		$sheet->setCellValue( "AP22","不要");
	}


	if($wAnshoNo !== ""){
		$hhh = ( $wAnshoNo==0 )?"AK24":"AM24";#あり：なし　0:有
		$sheet->setCellValue( $hhh, "■");
	}
	$sheet->setCellValue( "AQ24", $wAnshoNoBiko);

/*
#★　おきば TVX
	$sss = ( $wRentKeyFlg == "0")?"F33":"H33";#あり：なし　0:有
	$sheet->setCellValue( $sss, "■");

*/



	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = $filename."_".$BukkenName.".xlsx" ;
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
