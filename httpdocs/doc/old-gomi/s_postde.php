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
	include_once _CLS_DIR . "SPUSUser.cls";

	include_once _CLS_DIR . "SPUSBukken.cls";
#	include_once _CLS_DIR . "SPUSTanto.cls";
	include_once _CLS_DIR . "SPUSSiten.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSIraiFile.cls";


	include_once _CLS_DIR . "SPFWParameter.cls";
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$rKey = SPFWParameter::getValues('rKey');


	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


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

	$wUserCD = $myUser->UserCD;
	$MyShozokuCD = $myUser->Extra1 ;	#所属支店CD
	$MyZokusei = $myUser->Extra3 ;		#管理ユーザ２一般ユーザ１
	$MyEigyoshoCD = $myUser->Extra4 ;	#営業所CD nespeユーザはNULLになってる


foreach(glob('upload/*') as $file){
    if(is_file($file)){
        $editfile[]=$file;
    }
}
	for($i=0;$i<count($editfile);$i++){
		unlink($editfile[$i]);
	}
	########################################################
	# 依頼ファイルの削除
	########################################################
/*
	if ($work == "2" and $editIraiFileCD > 0 ) {

		$myIraiFile = new IraiFile($myDB);
		if (!$myIraiFile->executeSelect("IraiFileCD = $editIraiFileCD" )){
			trigger_error("Getting IraiFile Failed.", E_USER_ERROR);
		}

		$myIraiFile->MukouFlg = 1 ;
		if (!$myIraiFile->executeUpdate()){
			trigger_error("executeUpdate(IraiFile) Failed.", E_USER_ERROR);
		}
		SPFWTemplate::dropValue('work');

		unset($myIraiFile);
	}


	########################################################
	# アイホン担当者
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "UserCD, ";		#担当CD
	$sql .= "LastName, ";	#名前
	$sql .= "EMail, ";		#メールアドレス
	$sql .= "Address2, ";	#メールアドレス２
	$sql .= "TEL, ";		#電話番号
	$sql .= "Address3, ";	#携帯電話
	$sql .= "Extra4 ";		#営業所
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tUserM";
	$sql .= " WHERE MukouFlg = FALSE AND (Extra3 = 1 or Extra3 = 2) AND ID not like 'nespe%'";

	if($MyZokusei == 1) { #一般ユーザは自分の所属のみ
		#$sql .= " WHERE ( Extra4 = ".$MyEigyoshoCD." OR Extra4 = 10 )  AND MukouFlg = FALSE ";
		#$sql .= " WHERE ( Extra4 = ".$MyEigyoshoCD." )  AND MukouFlg = FALSE ";
		$sql .= " AND ( Extra1 = ".$MyShozokuCD." ) ";
	}

	$myListObject->Condition = $sql;
	$myListObject->Order = "Extra4";#表示順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$TantoLoop = $myListObject->Rows;
	for ($i = 0; $i < $TantoLoop; $i++) {
		$TantoCD[$i] = $myListObject->GetValue($i, 0);
		$TantoName[$i] = $myListObject->GetValue($i, 1);
		$EMail[$i] = $myListObject->GetValue($i, 2);
		$Address2[$i] = $myListObject->GetValue($i, 3);
		$TEL[$i] = $myListObject->GetValue($i, 4);
		$Address3[$i] = $myListObject->GetValue($i, 5);
		$Extra4[$i] = $myListObject->GetValue($i, 6);


		// 営業所名取得
		$myListObject2 = new SPFWListObject($myDB);
		$sql2 = "SELECT ";
		$sql2 .= "EigyoshoName ";
		$myListObject2->SelectSQL = $sql2;
		$sql2 = " FROM tEigyoshoM";
		$sql2 .= " WHERE EigyoshoCD = '{$Extra4[$i]}' AND MukouFlg = FALSE ";
		$myListObject2->Condition = $sql2;
		$myListObject2->Order = "1";
		$myListObject2->Limit = "allpage";

		if (!($myListObject2->GetList(1)))
			trigger_error("Getting Eigyosho List Failed.", E_USER_ERROR);

		if ($myListObject2->Rows == 1) {
			$EigyoshoName[$i] = $myListObject2->GetValue(0, 0);
		}
		unset($myListObject2);
	}
	unset($myListObject);


	########################################################
	# 施工業者担当者
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "gt.UserCD, ";		#0業者担当CD
	$sql .= "gt.LastName, ";	#1業者担当名
	$sql .= "gt.Extra5, ";		#2業者CD
	$sql .= "gt.TEL, ";			#3業者担当TEL
	$sql .= "gt.Address3, ";	#4業者担当携帯
	$sql .= "gt.EMail, ";		#5業者担当メールアドレス
	$sql .= "gt.Address3, ";	#6  2こめのメールアドレス
	$sql .= "gt.Notes, ";		#7備考
	$sql .= "g.GyosyaName, ";	#8業者名
	$sql .= "g.ShozokuCD ";		#9管轄支店　|3|4|5|となっている。
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tUserM gt , tGyosyaM g ";
	$sql .= " WHERE gt.Extra5 = g.GyosyaCD AND gt.MukouFlg = FALSE AND (Extra3= 3 or Extra3= 4) and UserCD != '546'"; // 546:nespekimura

	if($MyZokusei == 1) { #一般ユーザは自分の所属のみ
		$sql .= " AND g.ShozokuCD like '%".$MyShozokuCD."%'  ";
	}

	$myListObject->Condition = $sql;
	$myListObject->Order = "CAST( g.GyosyaNameKana as BINARY ) ";#表示順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User List Failed.", E_USER_ERROR);

	$GyosyaTantoLoop = $myListObject->Rows;
	for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
		$GyosyaTantoCD[$i] = $myListObject->GetValue($i, 0);
		$GyosyaTantoName[$i] = $myListObject->GetValue($i, 1);

		$GyosyaTantoTEL[$i] = $myListObject->GetValue($i, 3);
		$GyosyaTantoKeitai[$i] = $myListObject->GetValue($i, 4);
		$GyosyaTantoMail[$i] = $myListObject->GetValue($i, 5);
		$GyosyaTantoMail2[$i] = $myListObject->GetValue($i, 6);
		$GyosyaTantoNotes[$i] = $myListObject->GetValue($i, 7);
		$GyosyaName[$i] = $myListObject->GetValue($i, 8);
		$ShozokuCD[$i] = $myListObject->GetValue($i, 9);
	}
	unset($myListObject);


	########################################################
	# 親機メニュー表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "DeviceCD, ";	#機器CD
	$sql .= "DeviceName, ";	#機器名
	$sql .= "Kataban, ";	#型番
	$sql .= "Category ";	#カテゴリ
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tDeviceM ";
	$sql .= " WHERE Category = 1 AND MukouFlg = FALSE ";#全員表示される。
	$myListObject->Condition = $sql;
	$myListObject->Order = "Kataban ";#表示順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$OyaDeviceLoop = $myListObject->Rows;
	for ($i = 0; $i < $OyaDeviceLoop; $i++) {
		$OyaDeviceCD[$i] = $myListObject->GetValue($i, 0);
		$OyaDeviceName[$i] = $myListObject->GetValue($i, 1);
		$OyaKataban[$i] = $myListObject->GetValue($i, 2);
		$OyaCategory[$i] = $myListObject->GetValue($i, 3);
	}
	unset($myListObject);


	########################################################
	# 子機メニュー表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "DeviceCD, ";	#機器CD
	$sql .= "DeviceName, ";	#機器名
	$sql .= "Kataban, ";	#型番
	$sql .= "Category ";	#カテゴリ
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tDeviceM ";
	$sql .= " WHERE Category = 2 AND MukouFlg = FALSE ";#全員表示される。
	$myListObject->Condition = $sql;
	$myListObject->Order = "Kataban ";#表示順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$KokiDeviceLoop = $myListObject->Rows;
	for ($i = 0; $i < $KokiDeviceLoop; $i++) {
		$KokiDeviceCD[$i] = $myListObject->GetValue($i, 0);
		$KokiDeviceName[$i] = $myListObject->GetValue($i, 1);
		$KokiKataban[$i] = $myListObject->GetValue($i, 2);
		$KokiCategory[$i] = $myListObject->GetValue($i, 3);
	}
	unset($myListObject);


	########################################################
	# OPメニュー表示
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "DeviceCD, ";	#機器CD
	$sql .= "DeviceName, ";	#機器名
	$sql .= "Kataban, ";	#型番
	$sql .= "Category ";	#カテゴリ
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tDeviceM ";
	$sql .= " WHERE  MukouFlg = FALSE ";#全員表示される。
	$myListObject->Condition = $sql;
	$myListObject->Order = "Kataban ";#表示順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$OPDeviceLoop = $myListObject->Rows;
	for ($i = 0; $i < $OPDeviceLoop; $i++) {
		$OPDeviceCD[$i] = $myListObject->GetValue($i, 0);
		$OPDeviceName[$i] = $myListObject->GetValue($i, 1);
		$OPKataban[$i] = $myListObject->GetValue($i, 2);
		$OPCategory[$i] = $myListObject->GetValue($i, 3);
	}
	unset($myListObject);


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

		$wBukkenCD = $myBukken->BukkenCD;
		$wBukkenName = $myBukken->BukkenName;
		$wTantoCD = $myBukken->TantoCD;
		$wShozokuCD = $myBukken->ShozokuCD;
		$wAddress = $myBukken->Address;

		#20170711add
		$wMitumoriNo = $myBukken->MitumoriNo;
		$wMitumoriNo = trim($wMitumoriNo); // 空白除去
		if (preg_match("/^[0-9]+$/", $wMitumoriNo)) { // 全て半角数字かどうか
			if (strlen($wMitumoriNo) == 10) $resultMitumoriNo = "OK"; // javascript判定のため、OK or NGの値を入れる
			else $resultMitumoriNo = "NG";
		} else {
			$resultMitumoriNo = "NG";
		}

		$wKanriKinmu = $myBukken->KanriKinmu;

		$wBunjyo = $myBukken->Bunjyo;
		if ( $wBunjyo == 1 )
			$wwBunjyo = "分譲";
		else if ( $wBunjyo == 2 )
			$wwBunjyo = "賃貸";
		$wKosu = $myBukken->Kosu;
	}
	unset($myBukken);


	#支店情報
	$mySiten = new Siten($myDB);
	if (!$mySiten->executeSelect("SitenCD = '" .$wShozokuCD."'", "")){
		trigger_error("Getting Siten Failed.", E_USER_ERROR);
	}

	$wSitenName = $mySiten->SitenName;
	unset($mySiten);

	// デフォルトは本日は受付依頼日
    $Today = date('Y/m/d');
	$wIraiDate = $Today;

	########################################################
	# 連携依頼情報抽出　依頼連携テーブルに物件CDがあったら
	########################################################
if( $Modota <> 1 ){
	$myIraiRenkei = new IraiRenkei($myDB);

	if (!$myIraiRenkei->executeSelect("BukkenCD = ".$editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting IraiRenkei Failed.", E_USER_ERROR);
	}

	if ($myIraiRenkei->RecCnt > 0) {#①依頼連携テーブルにレコードがある＝確認・再連携

		$editIraiRenkeiCD = $myIraiRenkei->IraiRenkeiCD;

//		$wIraiRenkeiStatus = $myIraiRenkei->IraiRenkeiStatus;
		$wIraiRenkeiStatus = 1;
		$wwIraiRenkeiStatus = "依頼済み";

		$wBukkenCD = $myIraiRenkei->BukkenCD;
		$wIraiDate = $myIraiRenkei->IraiDate;
		$wSekoShutai = $myIraiRenkei->SekoShutai;

		$wAnswer = $myIraiRenkei->Answer;
		$Answer0Selected = ( $wAnswer == "-" ) ? "selected" : "" ;
		$Answer1Selected = ( $wAnswer == "A.日時変更住戸のみ返答" ) ? "selected" : "" ;
		$Answer2Selected = ( $wAnswer == "B.全住戸返答" ) ? "selected" : "" ;
		$Answer3Selected = ( $wAnswer == "C.全住戸返答+確定時未返事シート" ) ? "selected" : "" ;

		$wYoshikiYotei = $myIraiRenkei->YoshikiYotei;
		$YoshikiYotei0Selected = ( $wYoshikiYotei == "利用しない" ) ? "selected" : "" ;
		$YoshikiYotei1Selected = ( $wYoshikiYotei == "予定１-A" ) ? "selected" : "" ;
		$YoshikiYotei2Selected = ( $wYoshikiYotei == "予定１-B" ) ? "selected" : "" ;
		$YoshikiYotei3Selected = ( $wYoshikiYotei == "予定２-A" ) ? "selected" : "" ;
		$YoshikiYotei4Selected = ( $wYoshikiYotei == "予定２-B" ) ? "selected" : "" ;
		$YoshikiYotei5Selected = ( $wYoshikiYotei == "予定３-A" ) ? "selected" : "" ;
		$YoshikiYotei6Selected = ( $wYoshikiYotei == "予定３-B" ) ? "selected" : "" ;
		$YoshikiYotei7Selected = ( $wYoshikiYotei == "独自書式" ) ? "selected" : "" ;

		$wYoshikiKakutei = $myIraiRenkei->YoshikiKakutei;
		$YoshikiKakutei0Selected = ( $wYoshikiKakutei == "利用しない" ) ? "selected" : "" ;
		$YoshikiKakutei1Selected = ( $wYoshikiKakutei == "確定１" ) ? "selected" : "" ;
		$YoshikiKakutei2Selected = ( $wYoshikiKakutei == "確定２" ) ? "selected" : "" ;
		$YoshikiKakutei3Selected = ( $wYoshikiKakutei == "独自書式" ) ? "selected" : "" ;

		$wYoshikiOP = $myIraiRenkei->YoshikiOP;
		$YoshikiOPi1Selected = ( $wYoshikiOP == "必要" ) ? "selected" : "" ;
		$YoshikiOPi2Selected = ( $wYoshikiOP == "不要" ) ? "selected" : "" ;

		$wKakuteiCompanyName = $myIraiRenkei->KakuteiCompanyName;
		$KakuteiCompany0Selected = ( $wKakuteiCompanyName == "-" ) ? "selected" : "" ;
		$KakuteiCompany1Selected = ( $wKakuteiCompanyName == "アイホン株式会社" ) ? "selected" : "" ;
		$KakuteiCompany2Selected = ( $wKakuteiCompanyName == "アイホン株式会社+管理会社" ) ? "selected" : "" ;
		$KakuteiCompany3Selected = ( $wKakuteiCompanyName == "資料記載に合わせる" ) ? "selected" : "" ;
		$KakuteiCompany4Selected = ( $wKakuteiCompanyName == "その他" ) ? "selected" : "" ;

		$wPicStatus = $myIraiRenkei->PicStatus;
		$PicStatus0Selected = ( $wPicStatus == "0" ) ? "selected" : "" ;
		$PicStatus1Selected = ( $wPicStatus == "1" ) ? "selected" : "" ;

		$wPicAppSekoName = $myIraiRenkei->PicAppSekoName;

		$wPostingFlg = $myIraiRenkei->PostingFlg;
		$PostingFlg0Selected = ( $wPostingFlg == "0" ) ? "selected" : "" ;
		$PostingFlg1Selected = ( $wPostingFlg == "1" ) ? "selected" : "" ;
		$PostingFlg2Selected = ( $wPostingFlg == "2" ) ? "selected" : "" ;
		$PostingFlg3Selected = ( $wPostingFlg == "3" ) ? "selected" : "" ; //予定案内のみ 20160901

		$wPostingName = $myIraiRenkei->PostingName;
		$PostingName0Selected = ( $wPostingName == "-" ) ? "selected" : "" ;
		$PostingName1Selected = ( $wPostingName == "アイホン株式会社" ) ? "selected" : "" ;
		$PostingName2Selected = ( $wPostingName == "アイホン株式会社+管理会社" ) ? "selected" : "" ;
		$PostingName3Selected = ( $wPostingName == "資料記載に合わせる" ) ? "selected" : "" ;
		$PostingName4Selected = ( $wPostingName == "その他" ) ? "selected" : "" ;

		$wKakuninFlg = $myIraiRenkei->KakuninFlg;
		#20180518 確認書アプリは「利用する」しか選べないように変更
		#$KakuninFlg0Selected = ( $wKakuninFlg == "0" ) ? "selected" : "" ;
		#$KakuninFlg1Selected = ( $wKakuninFlg == "1" ) ? "selected" : "" ;
		$KakuninFlg1Selected = "selected";
		$wKakuninTitle = $myIraiRenkei->KakuninTitle;
		$wKakuninFooter = $myIraiRenkei->KakuninFooter;

		$wSmartFlg = $myIraiRenkei->SmartFlg;
		$SmartFlg0Selected = ( $wSmartFlg == "0" ) ? "selected" : "" ;
		$SmartFlg1Selected = ( $wSmartFlg == "1" ) ? "selected" : "" ;

		$wYoteTekyoDate = $myIraiRenkei->YoteTekyoDate;
		$wYoteDate = $myIraiRenkei->YoteDate;
		$wYoyakuEnd = $myIraiRenkei->YoyakuEnd;
		$wTekyoDate = $myIraiRenkei->TekyoDate;
		$wKeteiDate = $myIraiRenkei->KeteiDate;

		$wTantoCD1 = $myIraiRenkei->TantoCD1;
		$wTantoCD2 = $myIraiRenkei->TantoCD2;
		for ($i = 0; $i < $TantoLoop; $i++) {
			$TantoCD1Selected[$i] = ( $wTantoCD1 == $TantoCD[$i] ) ? "selected" : "" ;
			$TantoCD2Selected[$i] = ( $wTantoCD2 == $TantoCD[$i] ) ? "selected" : "" ;
		}

		$wGyosyaTantoCD1 = $myIraiRenkei->GyosyaTantoCD1;
		$wGyosyaTantoCD2 = $myIraiRenkei->GyosyaTantoCD2;
		for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
			$GyosyaTantoCD1Selected[$i] = ( $wGyosyaTantoCD1 == $GyosyaTantoCD[$i] ) ? "selected" : "" ;
			$GyosyaTantoCD2Selected[$i] = ( $wGyosyaTantoCD2 == $GyosyaTantoCD[$i] ) ? "selected" : "" ;
		}

//		$wWakuRange = $myIraiRenkei->WakuRange;
		$wTimeAStart = $myIraiRenkei->TimeAStart;
		$wTimeBStart = $myIraiRenkei->TimeBStart;
		$wTimeCStart = $myIraiRenkei->TimeCStart;
		$wTimeAEnd = $myIraiRenkei->TimeAEnd;
		$wTimeBEnd = $myIraiRenkei->TimeBEnd;
		$wTimeCEnd = $myIraiRenkei->TimeCEnd;
		$wTimeASu = $myIraiRenkei->TimeASu;
		$wTimeBSu = $myIraiRenkei->TimeBSu;
		$wTimeCSu = $myIraiRenkei->TimeCSu;

		$wOyaDeviceCD = $myIraiRenkei->OyaDeviceCD;
		for ($i = 0; $i < $OyaDeviceLoop; $i++) {
			$OyaDeviceSelected[$i] = ( $wOyaDeviceCD == $OyaDeviceCD[$i] ) ? "selected" : "" ;
		}

		$wKokiDeviceCD = $myIraiRenkei->KokiDeviceCD;
		for ($i = 0; $i < $KokiDeviceLoop; $i++) {
			$KokiDeviceSelected[$i] = ( $wKokiDeviceCD == $KokiDeviceCD[$i] ) ? "selected" : "" ;
		}

		$wKokiPanelKataban = $myIraiRenkei->KokiPanelKataban;

		$wOP1DeviceCD = $myIraiRenkei->OP1DeviceCD;
		$wOP2DeviceCD = $myIraiRenkei->OP2DeviceCD;
		$wOP3DeviceCD = $myIraiRenkei->OP3DeviceCD;
		$wOP4DeviceCD = $myIraiRenkei->OP4DeviceCD;
		for ($i = 0; $i < $OPDeviceLoop; $i++) {
			$OP1DeviceSelected[$i] = ( $wOP1DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
			$OP2DeviceSelected[$i] = ( $wOP2DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
			$OP3DeviceSelected[$i] = ( $wOP3DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
			$OP4DeviceSelected[$i] = ( $wOP4DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
		}

		$wOPPrice1 = $myIraiRenkei->OPPrice1;
		$wOPPrice2 = $myIraiRenkei->OPPrice2;
		$wOPPrice3 = $myIraiRenkei->OPPrice3;
		$wOPPrice4 = $myIraiRenkei->OPPrice4;

		$wShiharai = $myIraiRenkei->Shiharai;
		$Shiharai1Selected = ( $wShiharai == "現金" ) ? "selected" : "" ;
		$Shiharai2Selected = ( $wShiharai == "振込" ) ? "selected" : "" ;
		$Shiharai3Selected = ( $wShiharai == "現金 or 振込選択(ヒアリング依頼)" ) ? "selected" : "" ;

		$wHansu = $myIraiRenkei->Hansu;
		$wConstTime = $myIraiRenkei->ConstTime;
		$wKyukoDate = $myIraiRenkei->KyukoDate;



		$wKaijyo = $myIraiRenkei->Kaijyo;

		if(strpos( $wKaijyo , "鍵" ) !== false){
			$Kaijyo1Checked = "Checked";
		}
		if(strpos( $wKaijyo , "仮" ) !== false){
			$Kaijyo2Checked = "Checked";
		}
		if(strpos( $wKaijyo , "|暗証番号" ) !== false){
			$Kaijyo3Checked = "Checked";
		}
		if(strpos( $wKaijyo , "工事期間中は終日開放" ) !== false){
			$Kaijyo4Checked = "Checked";
		}

		$wIsetu = $myIraiRenkei->Isetu;
		$Isetu1Selected = ( $wIsetu == "しない" ) ? "selected" : "" ;
		$Isetu2Selected = ( $wIsetu == "有料にて対応" ) ? "selected" : "" ;
		$Isetu3Selected = ( $wIsetu == "無料対応" ) ? "selected" : "" ;
		$Isetu4Selected = ( $wIsetu == "アイホン担当者に確認" ) ? "selected" : "" ;

		$wPhotoTekyoDate = $myIraiRenkei->PhotoTekyoDate;

		$wPhotoPattern = $myIraiRenkei->PhotoPattern;
		$PhotoPattern1Selected = ( $wPhotoPattern == "a-1" ) ? "selected" : "" ;
		$PhotoPattern2Selected = ( $wPhotoPattern == "a-2" ) ? "selected" : "" ;
		$PhotoPattern3Selected = ( $wPhotoPattern == "b-1" ) ? "selected" : "" ;
		$PhotoPattern4Selected = ( $wPhotoPattern == "b-2" ) ? "selected" : "" ;

		$wPhotoSenyu1 = $myIraiRenkei->PhotoSenyu1;
		$PhotoSenyu11Selected = ( $wPhotoSenyu1 == "1" ) ? "selected" : "" ;
		$PhotoSenyu12Selected = ( $wPhotoSenyu1 == "2" ) ? "selected" : "" ;
		$PhotoSenyu13Selected = ( $wPhotoSenyu1 == "3" ) ? "selected" : "" ;

		$wPSScene1 = $myIraiRenkei->PSScene1;
		$PSScene11Selected = ( $wPSScene1 == "1" ) ? "selected" : "" ;
		$PSScene12Selected = ( $wPSScene1 == "2" ) ? "selected" : "" ;
		$PSScene13Selected = ( $wPSScene1 == "3" ) ? "selected" : "" ;

		$wPhotoSenyu2 = $myIraiRenkei->PhotoSenyu2;
		$PhotoSenyu21Selected = ( $wPhotoSenyu2 == "1" ) ? "selected" : "" ;
		$PhotoSenyu22Selected = ( $wPhotoSenyu2 == "2" ) ? "selected" : "" ;
		$PhotoSenyu23Selected = ( $wPhotoSenyu2 == "3" ) ? "selected" : "" ;

		$wPSScene2 = $myIraiRenkei->PSScene2;
		$PSScene21Selected = ( $wPSScene2 == "1" ) ? "selected" : "" ;
		$PSScene22Selected = ( $wPSScene2 == "2" ) ? "selected" : "" ;
		$PSScene23Selected = ( $wPSScene2 == "3" ) ? "selected" : "" ;

		$wPhotoSenyu3 = $myIraiRenkei->PhotoSenyu3;
		$PhotoSenyu31Selected = ( $wPhotoSenyu3 == "1" ) ? "selected" : "" ;
		$PhotoSenyu32Selected = ( $wPhotoSenyu3 == "2" ) ? "selected" : "" ;
		$PhotoSenyu33Selected = ( $wPhotoSenyu3 == "3" ) ? "selected" : "" ;

		$wPSScene3 = $myIraiRenkei->PSScene3;
		$PSScene31Selected = ( $wPSScene3 == "1" ) ? "selected" : "" ;
		$PSScene32Selected = ( $wPSScene3 == "2" ) ? "selected" : "" ;
		$PSScene33Selected = ( $wPSScene3 == "3" ) ? "selected" : "" ;

		$wPhotoSenyu4 = $myIraiRenkei->PhotoSenyu4;
		$PhotoSenyu41Selected = ( $wPhotoSenyu4 == "1" ) ? "selected" : "" ;
		$PhotoSenyu42Selected = ( $wPhotoSenyu4 == "2" ) ? "selected" : "" ;
		$PhotoSenyu43Selected = ( $wPhotoSenyu4 == "3" ) ? "selected" : "" ;

		$wPSScene4 = $myIraiRenkei->PSScene4;
		$PSScene41Selected = ( $wPSScene4 == "1" ) ? "selected" : "" ;
		$PSScene42Selected = ( $wPSScene4 == "2" ) ? "selected" : "" ;
		$PSScene43Selected = ( $wPSScene4 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo1 = $myIraiRenkei->PhotoKyoyo1;
		$PhotoKyoyo11Selected = ( $wPhotoKyoyo1 == "1" ) ? "selected" : "" ;
		$PhotoKyoyo12Selected = ( $wPhotoKyoyo1 == "2" ) ? "selected" : "" ;
		$PhotoKyoyo13Selected = ( $wPhotoKyoyo1 == "3" ) ? "selected" : "" ;
		$PhotoKyoyo14Selected = ( $wPhotoKyoyo1 == "4" ) ? "selected" : "" ;

		$wPKScene1 = $myIraiRenkei->PKScene1;
		$PKScene11Selected = ( $wPKScene1 == "1" ) ? "selected" : "" ;
		$PKScene12Selected = ( $wPKScene1 == "2" ) ? "selected" : "" ;
		$PKScene13Selected = ( $wPKScene1 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo2 = $myIraiRenkei->PhotoKyoyo2;
		$PhotoKyoyo21Selected = ( $wPhotoKyoyo2 == "1" ) ? "selected" : "" ;
		$PhotoKyoyo22Selected = ( $wPhotoKyoyo2 == "2" ) ? "selected" : "" ;
		$PhotoKyoyo23Selected = ( $wPhotoKyoyo2 == "3" ) ? "selected" : "" ;
		$PhotoKyoyo24Selected = ( $wPhotoKyoyo2 == "4" ) ? "selected" : "" ;

		$wPKScene2 = $myIraiRenkei->PKScene2;
		$PKScene21Selected = ( $wPKScene2 == "1" ) ? "selected" : "" ;
		$PKScene22Selected = ( $wPKScene2 == "2" ) ? "selected" : "" ;
		$PKScene23Selected = ( $wPKScene2 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo3 = $myIraiRenkei->PhotoKyoyo3;
		$PhotoKyoyo31Selected = ( $wPhotoKyoyo3 == "1" ) ? "selected" : "" ;
		$PhotoKyoyo32Selected = ( $wPhotoKyoyo3 == "2" ) ? "selected" : "" ;
		$PhotoKyoyo33Selected = ( $wPhotoKyoyo3 == "3" ) ? "selected" : "" ;
		$PhotoKyoyo34Selected = ( $wPhotoKyoyo3 == "4" ) ? "selected" : "" ;

		$wPKScene3 = $myIraiRenkei->PKScene3;
		$PKScene31Selected = ( $wPKScene3 == "1" ) ? "selected" : "" ;
		$PKScene32Selected = ( $wPKScene3 == "2" ) ? "selected" : "" ;
		$PKScene33Selected = ( $wPKScene3 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo4 = $myIraiRenkei->PhotoKyoyo4;
		$PhotoKyoyo41Selected = ( $wPhotoKyoyo4 == "1" ) ? "selected" : "" ;
		$PhotoKyoyo42Selected = ( $wPhotoKyoyo4 == "2" ) ? "selected" : "" ;
		$PhotoKyoyo43Selected = ( $wPhotoKyoyo4 == "3" ) ? "selected" : "" ;
		$PhotoKyoyo44Selected = ( $wPhotoKyoyo4 == "4" ) ? "selected" : "" ;

		$wPKScene4 = $myIraiRenkei->PKScene4;
		$PKScene41Selected = ( $wPKScene4 == "1" ) ? "selected" : "" ;
		$PKScene42Selected = ( $wPKScene4 == "2" ) ? "selected" : "" ;
		$PKScene43Selected = ( $wPKScene4 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo5 = $myIraiRenkei->PhotoKyoyo5;
		$wPKScene5 = $myIraiRenkei->PKScene5;
		$PKScene51Selected = ( $wPKScene5 == "1" ) ? "selected" : "" ;
		$PKScene52Selected = ( $wPKScene5 == "2" ) ? "selected" : "" ;
		$PKScene53Selected = ( $wPKScene5 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo6 = $myIraiRenkei->PhotoKyoyo6;
		$wPKScene6 = $myIraiRenkei->PKScene6;
		$PKScene61Selected = ( $wPKScene6 == "1" ) ? "selected" : "" ;
		$PKScene62Selected = ( $wPKScene6 == "2" ) ? "selected" : "" ;
		$PKScene63Selected = ( $wPKScene6 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo7 = $myIraiRenkei->PhotoKyoyo7;
		$wPKScene7 = $myIraiRenkei->PKScene7;
		$PKScene71Selected = ( $wPKScene7 == "1" ) ? "selected" : "" ;
		$PKScene72Selected = ( $wPKScene7 == "2" ) ? "selected" : "" ;
		$PKScene73Selected = ( $wPKScene7 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo8 = $myIraiRenkei->PhotoKyoyo8;
		$wPKScene8 = $myIraiRenkei->PKScene8;
		$PKScene81Selected = ( $wPKScene8 == "1" ) ? "selected" : "" ;
		$PKScene82Selected = ( $wPKScene8 == "2" ) ? "selected" : "" ;
		$PKScene83Selected = ( $wPKScene8 == "3" ) ? "selected" : "" ;

		$wPhotoKyoyo9 = $myIraiRenkei->PhotoKyoyo9;
		$wPKScene9 = $myIraiRenkei->PKScene9;
		$PKScene91Selected = ( $wPKScene9 == "1" ) ? "selected" : "" ;
		$PKScene92Selected = ( $wPKScene9 == "2" ) ? "selected" : "" ;
		$PKScene93Selected = ( $wPKScene9 == "3" ) ? "selected" : "" ;
		$wNotes = $myIraiRenkei->Notes;

		$wRenewalType = $myIraiRenkei->RenewalType;
		${"RenewalTypeChecked".$wRenewalType} = "checked";

		$wHaisenType = $myIraiRenkei->HaisenType;
		${"HaisenTypeChecked".$wHaisenType} = "checked";

		$wKanrishituCall = $myIraiRenkei->KanrishituCall;
		${"KanrishituCallChecked".$wKanrishituCall} = "checked";

		$wPicKSu = $myIraiRenkei->PicKSu;
		${"PicKSuChecked".$wPicKSu} = "checked";

		$wPicKType = $myIraiRenkei->PicKType;
		${"PicKTypeChecked".$wPicKType} = "checked";

		$wPicSu = $myIraiRenkei->PicSu;
		${"PicSuChecked".$wPicSu} = "checked";

		$wPicType = $myIraiRenkei->PicType;
		${"PicTypeChecked".$wPicType} = "checked";

		$wShobo = $myIraiRenkei->Shobo;
		${"ShoboChecked".$wShobo} = "checked";

		$wTeishutuDoc = $myIraiRenkei->TeishutuDoc;
		$wTeishutuDoc = SPFWTools::decodePluralValue($wTeishutuDoc);
		if($wTeishutuDoc[0] == 1 ){
			$TeishutuDocChecked1 = "checked";
		}
		if($wTeishutuDoc[1] == 2 ){
			$TeishutuDocChecked2 = "checked";
		}
		if($wTeishutuDoc[2] == 3 ){
			$TeishutuDocChecked3 = "checked";
		}

		$wSonotaShiji = $myIraiRenkei->SonotaShiji;
		$wIR01 = $myIraiRenkei->IR01;#元請けチェック20160821
		$IR01Checked = ($wIR01[0] == 1 )? "checked":"";

		$wIR02 = $myIraiRenkei->IR02;#腕章ありチェック20160902 (ポスティングあり時のみ)
		$IR02Checked = ($wIR02[0] == 1 )? "checked":"";

		$wIR03 = $myIraiRenkei->IR03;
		$wIR04 = $myIraiRenkei->IR04;
		$wIR05 = $myIraiRenkei->IR05;
		$wIR06 = $myIraiRenkei->IR06;
		$wIR07 = $myIraiRenkei->IR07;
		$wIR08 = $myIraiRenkei->IR08;
		$wIR09 = $myIraiRenkei->IR09;
		$wIR10 = $myIraiRenkei->IR10;

	}else{ #新規依頼の場合
		$wIraiRenkeiStatus = 0;
		$wwIraiRenkeiStatus = "新規依頼";

		// デフォルト入力
		$wKakuninTitle = "インターホン工事完了確認書";
		$wKakuninFooter = "アイホン株式会社";

		$IR02Checked = "checked"; #腕章ありチェック20160902 (ポスティングあり時のみ)

		$wTimeAStart = "9:00";
		$wTimeBStart = "13:00";
		$wTimeCStart = "15:00";
		$wTimeAEnd = "12:00";
		$wTimeBEnd = "15:00";
		$wTimeCEnd = "18:00";

		$PhotoSenyu11Selected = "selected"; // 専有部１　機器名
		$PhotoSenyu23Selected = "selected";
		$PhotoKyoyo11Selected = "selected";
		$PhotoKyoyo22Selected = "selected";
		$PhotoKyoyo33Selected = "selected";

	}#新規依頼　①依頼連携テーブルにレコードがある＝確認・再連携　END

	unset($myIraiRenkei);

}else{#　confirmからもどったら20160821

		$IR01Checked = ($wIR01[0] == 1 )? "checked":""; //元請け
		$IR02Checked = ($wIR02[0] == 1 )? "checked":""; //腕章

		$Answer0Selected = ( $wAnswer == "-" ) ? "selected" : "" ;
		$Answer1Selected = ( $wAnswer == "A.日時変更住戸のみ返答" ) ? "selected" : "" ;
		$Answer2Selected = ( $wAnswer == "B.全住戸返答" ) ? "selected" : "" ;
		$Answer3Selected = ( $wAnswer == "C.全住戸返答+確定時未返事シート" ) ? "selected" : "" ;

		$YoshikiYotei0Selected = ( $wYoshikiYotei == "利用しない" ) ? "selected" : "" ;
		$YoshikiYotei1Selected = ( $wYoshikiYotei == "予定１-A" ) ? "selected" : "" ;
		$YoshikiYotei2Selected = ( $wYoshikiYotei == "予定１-B" ) ? "selected" : "" ;
		$YoshikiYotei3Selected = ( $wYoshikiYotei == "予定２-A" ) ? "selected" : "" ;
		$YoshikiYotei4Selected = ( $wYoshikiYotei == "予定２-B" ) ? "selected" : "" ;
		$YoshikiYotei5Selected = ( $wYoshikiYotei == "予定３-A" ) ? "selected" : "" ;
		$YoshikiYotei6Selected = ( $wYoshikiYotei == "予定３-B" ) ? "selected" : "" ;
		$YoshikiYotei7Selected = ( $wYoshikiYotei == "独自書式" ) ? "selected" : "" ;

		$YoshikiKakutei0Selected = ( $wYoshikiKakutei == "利用しない" ) ? "selected" : "" ;
		$YoshikiKakutei1Selected = ( $wYoshikiKakutei == "確定１" ) ? "selected" : "" ;
		$YoshikiKakutei2Selected = ( $wYoshikiKakutei == "確定２" ) ? "selected" : "" ;
		$YoshikiKakutei3Selected = ( $wYoshikiKakutei == "独自書式" ) ? "selected" : "" ;

		$YoshikiOPi1Selected = ( $wYoshikiOP == "必要" ) ? "selected" : "" ;
		$YoshikiOPi2Selected = ( $wYoshikiOP == "不要" ) ? "selected" : "" ;

		$PicStatus0Selected = ( $wPicStatus == "0" ) ? "selected" : "" ;
		$PicStatus1Selected = ( $wPicStatus == "1" ) ? "selected" : "" ;
		$PostingFlg0Selected = ( $wPostingFlg == "0" ) ? "selected" : "" ;
		$PostingFlg1Selected = ( $wPostingFlg == "1" ) ? "selected" : "" ;
		$PostingFlg2Selected = ( $wPostingFlg == "2" ) ? "selected" : "" ;
		$PostingFlg3Selected = ( $wPostingFlg == "3" ) ? "selected" : "" ; // 予定案内のみ 20160901

		#20180518 確認書アプリは「利用する」しか選べないように変更
		#$KakuninFlg0Selected = ( $wKakuninFlg == "0" ) ? "selected" : "" ;
		#$KakuninFlg1Selected = ( $wKakuninFlg == "1" ) ? "selected" : "" ;
		$KakuninFlg1Selected = "selected";

		$SmartFlg0Selected = ( $wSmartFlg == "0" ) ? "selected" : "" ;
		$SmartFlg1Selected = ( $wSmartFlg == "1" ) ? "selected" : "" ;

		$KakuteiCompany0Selected = ( $wKakuteiCompanyName == "-" ) ? "selected" : "" ;
		$KakuteiCompany1Selected = ( $wKakuteiCompanyName == "アイホン株式会社" ) ? "selected" : "" ;
		$KakuteiCompany2Selected = ( $wKakuteiCompanyName == "アイホン株式会社+管理会社" ) ? "selected" : "" ;
		$KakuteiCompany3Selected = ( $wKakuteiCompanyName == "資料記載に合わせる" ) ? "selected" : "" ;
		$KakuteiCompany4Selected = ( $wKakuteiCompanyName == "その他" ) ? "selected" : "" ;

		$PostingName0Selected = ( $wPostingName == "-" ) ? "selected" : "" ;
		$PostingName1Selected = ( $wPostingName == "アイホン株式会社" ) ? "selected" : "" ;
		$PostingName2Selected = ( $wPostingName == "アイホン株式会社+管理会社" ) ? "selected" : "" ;
		$PostingName3Selected = ( $wPostingName == "資料記載に合わせる" ) ? "selected" : "" ;
		$PostingName4Selected = ( $wPostingName == "その他" ) ? "selected" : "" ;

		for ($i = 0; $i < $TantoLoop; $i++) {
			$TantoCD1Selected[$i] = ( $wTantoCD1 == $TantoCD[$i] ) ? "selected" : "" ;
			$TantoCD2Selected[$i] = ( $wTantoCD2 == $TantoCD[$i] ) ? "selected" : "" ;
		}
		for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
			$GyosyaTantoCD1Selected[$i] = ( $wGyosyaTantoCD1 == $GyosyaTantoCD[$i] ) ? "selected" : "" ;
			$GyosyaTantoCD2Selected[$i] = ( $wGyosyaTantoCD2 == $GyosyaTantoCD[$i] ) ? "selected" : "" ;
		}
		for ($i = 0; $i < $OyaDeviceLoop; $i++) {
			$OyaDeviceSelected[$i] = ( $wOyaDeviceCD == $OyaDeviceCD[$i] ) ? "selected" : "" ;
		}
		for ($i = 0; $i < $KokiDeviceLoop; $i++) {
			$KokiDeviceSelected[$i] = ( $wKokiDeviceCD == $KokiDeviceCD[$i] ) ? "selected" : "" ;
		}
		for ($i = 0; $i < $OPDeviceLoop; $i++) {
			$OP1DeviceSelected[$i] = ( $wOP1DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
			$OP2DeviceSelected[$i] = ( $wOP2DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
			$OP3DeviceSelected[$i] = ( $wOP3DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
			$OP4DeviceSelected[$i] = ( $wOP4DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
		}

		$Shiharai1Selected = ( $wShiharai == "現金" ) ? "selected" : "" ;
		$Shiharai2Selected = ( $wShiharai == "振込" ) ? "selected" : "" ;
		$Shiharai3Selected = ( $wShiharai == "現金 or 振込選択(ヒアリング依頼)" ) ? "selected" : "" ;

		if(strpos( $wKaijyo , "鍵" ) !== false){
			$Kaijyo1Checked = "Checked";
		}
		if(strpos( $wKaijyo , "仮" ) !== false){
			$Kaijyo2Checked = "Checked";
		}
		if(strpos( $wKaijyo , "|暗証番号" ) !== false){
			$Kaijyo3Checked = "Checked";
		}
		if(strpos( $wKaijyo , "工事期間中は終日開放" ) !== false){
			$Kaijyo4Checked = "Checked";
		}

		$Isetu1Selected = ( $wIsetu == "しない" ) ? "selected" : "" ;
		$Isetu2Selected = ( $wIsetu == "有料にて対応" ) ? "selected" : "" ;
		$Isetu3Selected = ( $wIsetu == "無料対応" ) ? "selected" : "" ;
		$Isetu4Selected = ( $wIsetu == "アイホン担当者に確認" ) ? "selected" : "" ;

		$PhotoPattern1Selected = ( $wPhotoPattern == "a-1" ) ? "selected" : "" ;
		$PhotoPattern2Selected = ( $wPhotoPattern == "a-2" ) ? "selected" : "" ;
		$PhotoPattern3Selected = ( $wPhotoPattern == "b-1" ) ? "selected" : "" ;
		$PhotoPattern4Selected = ( $wPhotoPattern == "b-2" ) ? "selected" : "" ;

		$PhotoSenyu11Selected = ( $wPhotoSenyu1 == "1" ) ? "selected" : "" ;
		$PhotoSenyu12Selected = ( $wPhotoSenyu1 == "2" ) ? "selected" : "" ;
		$PhotoSenyu13Selected = ( $wPhotoSenyu1 == "3" ) ? "selected" : "" ;

		$PSScene11Selected = ( $wPSScene1 == "1" ) ? "selected" : "" ;
		$PSScene12Selected = ( $wPSScene1 == "2" ) ? "selected" : "" ;
		$PSScene13Selected = ( $wPSScene1 == "3" ) ? "selected" : "" ;

		$PhotoSenyu21Selected = ( $wPhotoSenyu2 == "1" ) ? "selected" : "" ;
		$PhotoSenyu22Selected = ( $wPhotoSenyu2 == "2" ) ? "selected" : "" ;
		$PhotoSenyu23Selected = ( $wPhotoSenyu2 == "3" ) ? "selected" : "" ;

		$PSScene21Selected = ( $wPSScene2 == "1" ) ? "selected" : "" ;
		$PSScene22Selected = ( $wPSScene2 == "2" ) ? "selected" : "" ;
		$PSScene23Selected = ( $wPSScene2 == "3" ) ? "selected" : "" ;

		$PhotoSenyu31Selected = ( $wPhotoSenyu3 == "1" ) ? "selected" : "" ;
		$PhotoSenyu32Selected = ( $wPhotoSenyu3 == "2" ) ? "selected" : "" ;
		$PhotoSenyu33Selected = ( $wPhotoSenyu3 == "3" ) ? "selected" : "" ;

		$PSScene31Selected = ( $wPSScene3 == "1" ) ? "selected" : "" ;
		$PSScene32Selected = ( $wPSScene3 == "2" ) ? "selected" : "" ;
		$PSScene33Selected = ( $wPSScene3 == "3" ) ? "selected" : "" ;

		$PhotoSenyu41Selected = ( $wPhotoSenyu4 == "1" ) ? "selected" : "" ;
		$PhotoSenyu42Selected = ( $wPhotoSenyu4 == "2" ) ? "selected" : "" ;
		$PhotoSenyu43Selected = ( $wPhotoSenyu4 == "3" ) ? "selected" : "" ;

		$PSScene41Selected = ( $wPSScene4 == "1" ) ? "selected" : "" ;
		$PSScene42Selected = ( $wPSScene4 == "2" ) ? "selected" : "" ;
		$PSScene43Selected = ( $wPSScene4 == "3" ) ? "selected" : "" ;

		$PhotoKyoyo11Selected = ( $wPhotoKyoyo1 == "1" ) ? "selected" : "" ;
		$PhotoKyoyo12Selected = ( $wPhotoKyoyo1 == "2" ) ? "selected" : "" ;
		$PhotoKyoyo13Selected = ( $wPhotoKyoyo1 == "3" ) ? "selected" : "" ;
		$PhotoKyoyo14Selected = ( $wPhotoKyoyo1 == "4" ) ? "selected" : "" ;

		$PKScene11Selected = ( $wPKScene1 == "1" ) ? "selected" : "" ;
		$PKScene12Selected = ( $wPKScene1 == "2" ) ? "selected" : "" ;
		$PKScene13Selected = ( $wPKScene1 == "3" ) ? "selected" : "" ;

		$PhotoKyoyo21Selected = ( $wPhotoKyoyo2 == "1" ) ? "selected" : "" ;
		$PhotoKyoyo22Selected = ( $wPhotoKyoyo2 == "2" ) ? "selected" : "" ;
		$PhotoKyoyo23Selected = ( $wPhotoKyoyo2 == "3" ) ? "selected" : "" ;
		$PhotoKyoyo24Selected = ( $wPhotoKyoyo2 == "4" ) ? "selected" : "" ;

		$PKScene21Selected = ( $wPKScene2 == "1" ) ? "selected" : "" ;
		$PKScene22Selected = ( $wPKScene2 == "2" ) ? "selected" : "" ;
		$PKScene23Selected = ( $wPKScene2 == "3" ) ? "selected" : "" ;

		$PhotoKyoyo31Selected = ( $wPhotoKyoyo3 == "1" ) ? "selected" : "" ;
		$PhotoKyoyo32Selected = ( $wPhotoKyoyo3 == "2" ) ? "selected" : "" ;
		$PhotoKyoyo33Selected = ( $wPhotoKyoyo3 == "3" ) ? "selected" : "" ;
		$PhotoKyoyo34Selected = ( $wPhotoKyoyo3 == "4" ) ? "selected" : "" ;

		$PKScene31Selected = ( $wPKScene3 == "1" ) ? "selected" : "" ;
		$PKScene32Selected = ( $wPKScene3 == "2" ) ? "selected" : "" ;
		$PKScene33Selected = ( $wPKScene3 == "3" ) ? "selected" : "" ;

		$PhotoKyoyo41Selected = ( $wPhotoKyoyo4 == "1" ) ? "selected" : "" ;
		$PhotoKyoyo42Selected = ( $wPhotoKyoyo4 == "2" ) ? "selected" : "" ;
		$PhotoKyoyo43Selected = ( $wPhotoKyoyo4 == "3" ) ? "selected" : "" ;
		$PhotoKyoyo44Selected = ( $wPhotoKyoyo4 == "4" ) ? "selected" : "" ;

		$PKScene41Selected = ( $wPKScene4 == "1" ) ? "selected" : "" ;
		$PKScene42Selected = ( $wPKScene4 == "2" ) ? "selected" : "" ;
		$PKScene43Selected = ( $wPKScene4 == "3" ) ? "selected" : "" ;

		$PKScene51Selected = ( $wPKScene5 == "1" ) ? "selected" : "" ;
		$PKScene52Selected = ( $wPKScene5 == "2" ) ? "selected" : "" ;
		$PKScene53Selected = ( $wPKScene5 == "3" ) ? "selected" : "" ;
		$PKScene61Selected = ( $wPKScene6 == "1" ) ? "selected" : "" ;
		$PKScene62Selected = ( $wPKScene6 == "2" ) ? "selected" : "" ;
		$PKScene63Selected = ( $wPKScene6 == "3" ) ? "selected" : "" ;
		$PKScene71Selected = ( $wPKScene7 == "1" ) ? "selected" : "" ;
		$PKScene72Selected = ( $wPKScene7 == "2" ) ? "selected" : "" ;
		$PKScene73Selected = ( $wPKScene7 == "3" ) ? "selected" : "" ;
		$PKScene81Selected = ( $wPKScene8 == "1" ) ? "selected" : "" ;
		$PKScene82Selected = ( $wPKScene8 == "2" ) ? "selected" : "" ;
		$PKScene83Selected = ( $wPKScene8 == "3" ) ? "selected" : "" ;
		$PKScene91Selected = ( $wPKScene9 == "1" ) ? "selected" : "" ;
		$PKScene92Selected = ( $wPKScene9 == "2" ) ? "selected" : "" ;
		$PKScene93Selected = ( $wPKScene9 == "3" ) ? "selected" : "" ;
		${"RenewalTypeChecked".$wRenewalType} = "checked";
		${"HaisenTypeChecked".$wHaisenType} = "checked";
		${"KanrishituCallChecked".$wKanrishituCall} = "checked";
		#写真撮影件数　共有部
		${"PicKSuChecked".$wPicKSu} = "checked";
		#写真撮影　枚数　共有部
		${"PicKTypeChecked".$wPicKType} = "checked";
		#写真撮影件数　専有部
		${"PicSuChecked".$wPicSu} = "checked";
		#写真撮影　枚数　専有部
		${"PicTypeChecked".$wPicType} = "checked";
		#消防申請
		${"ShoboChecked".$wShobo} = "checked";
		#工事完了後の提出書類
		if($wTeishutuDoc[0] == 1 ){
			$TeishutuDocChecked1 = "checked";
		}
		if($wTeishutuDoc[1] == 2 ){
			$TeishutuDocChecked2 = "checked";
		}
		if($wTeishutuDoc[2] == 3 ){
			$TeishutuDocChecked3 = "checked";
		}

}
//echo "<br>checkcopy:".$checkcopy;
	if ($checkcopy > 0) {
		//echo " 過去の物件からデータ複写";

		$myIraiRenkei = new IraiRenkei($myDB);
		if (!$myIraiRenkei->executeSelect("BukkenCD = ".$checkcopy . " AND MukouFlg = FALSE", "")){
			trigger_error("Getting IraiRenkei Failed.", E_USER_ERROR);
		}

		if ($myIraiRenkei->RecCnt == 1) {#①

			$wSekoShutai = $myIraiRenkei->SekoShutai;
			$wAnswer = $myIraiRenkei->Answer;
			$Answer1Selected = ( $wAnswer == "A.日時変更住戸のみ返答" ) ? "selected" : "" ;
			$Answer2Selected = ( $wAnswer == "B.全住戸返答" ) ? "selected" : "" ;
			$Answer3Selected = ( $wAnswer == "C.全住戸返答+確定時未返事シート" ) ? "selected" : "" ;

			$wYoshikiYotei = $myIraiRenkei->YoshikiYotei;
			$YoshikiYotei0Selected = ( $wYoshikiYotei == "利用しない" ) ? "selected" : "" ;
			$YoshikiYotei1Selected = ( $wYoshikiYotei == "予定１-A" ) ? "selected" : "" ;
			$YoshikiYotei2Selected = ( $wYoshikiYotei == "予定１-B" ) ? "selected" : "" ;
			$YoshikiYotei3Selected = ( $wYoshikiYotei == "予定２-A" ) ? "selected" : "" ;
			$YoshikiYotei4Selected = ( $wYoshikiYotei == "予定２-B" ) ? "selected" : "" ;
			$YoshikiYotei5Selected = ( $wYoshikiYotei == "予定３-A" ) ? "selected" : "" ;
			$YoshikiYotei6Selected = ( $wYoshikiYotei == "予定３-B" ) ? "selected" : "" ;
			$YoshikiYotei7Selected = ( $wYoshikiYotei == "独自書式" ) ? "selected" : "" ;

			$wYoshikiKakutei = $myIraiRenkei->YoshikiKakutei;
			$YoshikiKakutei0Selected = ( $wYoshikiKakutei == "利用しない" ) ? "selected" : "" ;
			$YoshikiKakutei1Selected = ( $wYoshikiKakutei == "確定１" ) ? "selected" : "" ;
			$YoshikiKakutei2Selected = ( $wYoshikiKakutei == "確定２" ) ? "selected" : "" ;
			$YoshikiKakutei3Selected = ( $wYoshikiKakutei == "独自書式" ) ? "selected" : "" ;

			$wYoshikiOP = $myIraiRenkei->YoshikiOP;
			$YoshikiOPi1Selected = ( $wYoshikiOP == "必要" ) ? "selected" : "" ;
			$YoshikiOPi2Selected = ( $wYoshikiOP == "不要" ) ? "selected" : "" ;

			$wKakuteiCompanyName = $myIraiRenkei->KakuteiCompanyName;
			$KakuteiCompany0Selected = ( $wKakuteiCompanyName == "-" ) ? "selected" : "" ;
			$KakuteiCompany1Selected = ( $wKakuteiCompanyName == "アイホン株式会社" ) ? "selected" : "" ;
			$KakuteiCompany2Selected = ( $wKakuteiCompanyName == "アイホン株式会社+管理会社" ) ? "selected" : "" ;
			$KakuteiCompany3Selected = ( $wKakuteiCompanyName == "資料記載に合わせる" ) ? "selected" : "" ;
			$KakuteiCompany4Selected = ( $wKakuteiCompanyName == "その他" ) ? "selected" : "" ;

			$wPicStatus = $myIraiRenkei->PicStatus;
			$PicStatus0Selected = ( $wPicStatus == "0" ) ? "selected" : "" ;
			$PicStatus1Selected = ( $wPicStatus == "1" ) ? "selected" : "" ;

			$wPostingFlg = $myIraiRenkei->PostingFlg;
			$PostingFlg0Selected = ( $wPostingFlg == "0" ) ? "selected" : "" ;
			$PostingFlg1Selected = ( $wPostingFlg == "1" ) ? "selected" : "" ;
			$PostingFlg2Selected = ( $wPostingFlg == "2" ) ? "selected" : "" ;
			$PostingFlg3Selected = ( $wPostingFlg == "3" ) ? "selected" : "" ; // 予定案内のみ 20160901

			$wPostingName = $myIraiRenkei->PostingName;
			$PostingName0Selected = ( $wPostingName == "-" ) ? "selected" : "" ;
			$PostingName1Selected = ( $wPostingName == "アイホン株式会社" ) ? "selected" : "" ;
			$PostingName2Selected = ( $wPostingName == "アイホン株式会社+管理会社" ) ? "selected" : "" ;
			$PostingName3Selected = ( $wPostingName == "資料記載に合わせる" ) ? "selected" : "" ;
			$PostingName4Selected = ( $wPostingName == "その他" ) ? "selected" : "" ;

			#20180518 確認書アプリは「利用する」しか選べないように変更⇒過去の物件からデータ複写しない
			#$wKakuninFlg = $myIraiRenkei->KakuninFlg;
			#$KakuninFlg0Selected = ( $wKakuninFlg == "0" ) ? "selected" : "" ;
			#$KakuninFlg1Selected = ( $wKakuninFlg == "1" ) ? "selected" : "" ;

			$wSmartFlg = $myIraiRenkei->SmartFlg;
			$SmartFlg0Selected = ( $wSmartFlg == "0" ) ? "selected" : "" ;
			$SmartFlg1Selected = ( $wSmartFlg == "1" ) ? "selected" : "" ;

			$wTantoCD1 = $myIraiRenkei->TantoCD1;
			$wTantoCD2 = $myIraiRenkei->TantoCD2;
			for ($i = 0; $i < $TantoLoop; $i++) {
				$TantoCD1Selected[$i] = ( $wTantoCD1 == $TantoCD[$i] ) ? "selected" : "" ;
				$TantoCD2Selected[$i] = ( $wTantoCD2 == $TantoCD[$i] ) ? "selected" : "" ;
			}

//			$wGyosyaTantoCD1 = $myIraiRenkei->GyosyaTantoCD1;
//			$wGyosyaTantoCD2 = $myIraiRenkei->GyosyaTantoCD2;
//			for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
//				$GyosyaTantoCD1Selected[$i] = ( $wGyosyaTantoCD1 == $GyosyaTantoCD[$i] ) ? "selected" : "" ;
//				$GyosyaTantoCD2Selected[$i] = ( $wGyosyaTantoCD2 == $GyosyaTantoCD[$i] ) ? "selected" : "" ;
//			}

			$wTimeAStart = $myIraiRenkei->TimeAStart;
			$wTimeBStart = $myIraiRenkei->TimeBStart;
			$wTimeCStart = $myIraiRenkei->TimeCStart;
			$wTimeAEnd = $myIraiRenkei->TimeAEnd;
			$wTimeBEnd = $myIraiRenkei->TimeBEnd;
			$wTimeCEnd = $myIraiRenkei->TimeCEnd;
			$wTimeASu = $myIraiRenkei->TimeASu;
			$wTimeBSu = $myIraiRenkei->TimeBSu;
			$wTimeCSu = $myIraiRenkei->TimeCSu;

			$wOyaDeviceCD = $myIraiRenkei->OyaDeviceCD;
			for ($i = 0; $i < $OyaDeviceLoop; $i++) {
				$OyaDeviceSelected[$i] = ( $wOyaDeviceCD == $OyaDeviceCD[$i] ) ? "selected" : "" ;
			}

			$wKokiDeviceCD = $myIraiRenkei->KokiDeviceCD;
			for ($i = 0; $i < $KokiDeviceLoop; $i++) {
				$KokiDeviceSelected[$i] = ( $wKokiDeviceCD == $KokiDeviceCD[$i] ) ? "selected" : "" ;
			}

			$wKokiPanelKataban = $myIraiRenkei->KokiPanelKataban;

			$wOP1DeviceCD = $myIraiRenkei->OP1DeviceCD;
			$wOP2DeviceCD = $myIraiRenkei->OP2DeviceCD;
			$wOP3DeviceCD = $myIraiRenkei->OP3DeviceCD;
			$wOP4DeviceCD = $myIraiRenkei->OP4DeviceCD;
			for ($i = 0; $i < $OPDeviceLoop; $i++) {
				$OP1DeviceSelected[$i] = ( $wOP1DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
				$OP2DeviceSelected[$i] = ( $wOP2DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
				$OP3DeviceSelected[$i] = ( $wOP3DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
				$OP4DeviceSelected[$i] = ( $wOP4DeviceCD == $OPDeviceCD[$i] ) ? "selected" : "" ;
			}

			$wOPPrice1 = $myIraiRenkei->OPPrice1;
			$wOPPrice2 = $myIraiRenkei->OPPrice2;
			$wOPPrice3 = $myIraiRenkei->OPPrice3;
			$wOPPrice4 = $myIraiRenkei->OPPrice4;

			$wShiharai = $myIraiRenkei->Shiharai;
			$Shiharai1Selected = ( $wShiharai == "現金" ) ? "selected" : "" ;
			$Shiharai2Selected = ( $wShiharai == "振込" ) ? "selected" : "" ;
			$Shiharai3Selected = ( $wShiharai == "現金 or 振込選択(ヒアリング依頼)" ) ? "selected" : "" ;

			$wHansu = $myIraiRenkei->Hansu;
			$wConstTime = $myIraiRenkei->ConstTime;
			$wKyukoDate = $myIraiRenkei->KyukoDate;


			$wKaijyo = $myIraiRenkei->Kaijyo;
			if(strpos( $wKaijyo , "鍵" ) !== false){
				$Kaijyo1Checked = "Checked";
			}
			if(strpos( $wKaijyo , "仮" ) !== false){
				$Kaijyo2Checked = "Checked";
			}
			if(strpos( $wKaijyo , "|暗証番号" ) !== false){
				$Kaijyo3Checked = "Checked";
			}
			if(strpos( $wKaijyo , "工事期間中は終日開放" ) !== false){
				$Kaijyo4Checked = "Checked";
			}

			$wIsetu = $myIraiRenkei->Isetu;
			$Isetu1Selected = ( $wIsetu == "しない" ) ? "selected" : "" ;
			$Isetu2Selected = ( $wIsetu == "有料にて対応" ) ? "selected" : "" ;
			$Isetu3Selected = ( $wIsetu == "無料対応" ) ? "selected" : "" ;
			$Isetu4Selected = ( $wIsetu == "アイホン担当者に確認" ) ? "selected" : "" ;


			$wPhotoPattern = $myIraiRenkei->PhotoPattern;
			$PhotoPattern1Selected = ( $wPhotoPattern == "a-1" ) ? "selected" : "" ;
			$PhotoPattern2Selected = ( $wPhotoPattern == "a-2" ) ? "selected" : "" ;
			$PhotoPattern3Selected = ( $wPhotoPattern == "b-1" ) ? "selected" : "" ;
			$PhotoPattern4Selected = ( $wPhotoPattern == "b-2" ) ? "selected" : "" ;

			$wPhotoSenyu1 = $myIraiRenkei->PhotoSenyu1;
			$PhotoSenyu11Selected = ( $wPhotoSenyu1 == "1" ) ? "selected" : "" ;
			$PhotoSenyu12Selected = ( $wPhotoSenyu1 == "2" ) ? "selected" : "" ;
			$PhotoSenyu13Selected = ( $wPhotoSenyu1 == "3" ) ? "selected" : "" ;

			$wPSScene1 = $myIraiRenkei->PSScene1;
			$PSScene11Selected = ( $wPSScene1 == "1" ) ? "selected" : "" ;
			$PSScene12Selected = ( $wPSScene1 == "2" ) ? "selected" : "" ;
			$PSScene13Selected = ( $wPSScene1 == "3" ) ? "selected" : "" ;

			$wPhotoSenyu2 = $myIraiRenkei->PhotoSenyu2;
			$PhotoSenyu21Selected = ( $wPhotoSenyu2 == "1" ) ? "selected" : "" ;
			$PhotoSenyu22Selected = ( $wPhotoSenyu2 == "2" ) ? "selected" : "" ;
			$PhotoSenyu23Selected = ( $wPhotoSenyu2 == "3" ) ? "selected" : "" ;

			$wPSScene2 = $myIraiRenkei->PSScene2;
			$PSScene21Selected = ( $wPSScene2 == "1" ) ? "selected" : "" ;
			$PSScene22Selected = ( $wPSScene2 == "2" ) ? "selected" : "" ;
			$PSScene23Selected = ( $wPSScene2 == "3" ) ? "selected" : "" ;

			$wPhotoSenyu3 = $myIraiRenkei->PhotoSenyu3;
			$PhotoSenyu31Selected = ( $wPhotoSenyu3 == "1" ) ? "selected" : "" ;
			$PhotoSenyu32Selected = ( $wPhotoSenyu3 == "2" ) ? "selected" : "" ;
			$PhotoSenyu33Selected = ( $wPhotoSenyu3 == "3" ) ? "selected" : "" ;

			$wPSScene3 = $myIraiRenkei->PSScene3;
			$PSScene31Selected = ( $wPSScene3 == "1" ) ? "selected" : "" ;
			$PSScene32Selected = ( $wPSScene3 == "2" ) ? "selected" : "" ;
			$PSScene33Selected = ( $wPSScene3 == "3" ) ? "selected" : "" ;

			$wPhotoSenyu4 = $myIraiRenkei->PhotoSenyu4;
			$PhotoSenyu41Selected = ( $wPhotoSenyu4 == "1" ) ? "selected" : "" ;
			$PhotoSenyu42Selected = ( $wPhotoSenyu4 == "2" ) ? "selected" : "" ;
			$PhotoSenyu43Selected = ( $wPhotoSenyu4 == "3" ) ? "selected" : "" ;

			$wPSScene4 = $myIraiRenkei->PSScene4;
			$PSScene41Selected = ( $wPSScene4 == "1" ) ? "selected" : "" ;
			$PSScene42Selected = ( $wPSScene4 == "2" ) ? "selected" : "" ;
			$PSScene43Selected = ( $wPSScene4 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo1 = $myIraiRenkei->PhotoKyoyo1;
			$PhotoKyoyo11Selected = ( $wPhotoKyoyo1 == "1" ) ? "selected" : "" ;
			$PhotoKyoyo12Selected = ( $wPhotoKyoyo1 == "2" ) ? "selected" : "" ;
			$PhotoKyoyo13Selected = ( $wPhotoKyoyo1 == "3" ) ? "selected" : "" ;
			$PhotoKyoyo14Selected = ( $wPhotoKyoyo1 == "4" ) ? "selected" : "" ;

			$wPKScene1 = $myIraiRenkei->PKScene1;
			$PKScene11Selected = ( $wPKScene1 == "1" ) ? "selected" : "" ;
			$PKScene12Selected = ( $wPKScene1 == "2" ) ? "selected" : "" ;
			$PKScene13Selected = ( $wPKScene1 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo2 = $myIraiRenkei->PhotoKyoyo2;
			$PhotoKyoyo21Selected = ( $wPhotoKyoyo2 == "1" ) ? "selected" : "" ;
			$PhotoKyoyo22Selected = ( $wPhotoKyoyo2 == "2" ) ? "selected" : "" ;
			$PhotoKyoyo23Selected = ( $wPhotoKyoyo2 == "3" ) ? "selected" : "" ;
			$PhotoKyoyo24Selected = ( $wPhotoKyoyo2 == "4" ) ? "selected" : "" ;

			$wPKScene2 = $myIraiRenkei->PKScene2;
			$PKScene21Selected = ( $wPKScene2 == "1" ) ? "selected" : "" ;
			$PKScene22Selected = ( $wPKScene2 == "2" ) ? "selected" : "" ;
			$PKScene23Selected = ( $wPKScene2 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo3 = $myIraiRenkei->PhotoKyoyo3;
			$PhotoKyoyo31Selected = ( $wPhotoKyoyo3 == "1" ) ? "selected" : "" ;
			$PhotoKyoyo32Selected = ( $wPhotoKyoyo3 == "2" ) ? "selected" : "" ;
			$PhotoKyoyo33Selected = ( $wPhotoKyoyo3 == "3" ) ? "selected" : "" ;
			$PhotoKyoyo34Selected = ( $wPhotoKyoyo3 == "4" ) ? "selected" : "" ;

			$wPKScene3 = $myIraiRenkei->PKScene3;
			$PKScene31Selected = ( $wPKScene3 == "1" ) ? "selected" : "" ;
			$PKScene32Selected = ( $wPKScene3 == "2" ) ? "selected" : "" ;
			$PKScene33Selected = ( $wPKScene3 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo4 = $myIraiRenkei->PhotoKyoyo4;
			$PhotoKyoyo41Selected = ( $wPhotoKyoyo4 == "1" ) ? "selected" : "" ;
			$PhotoKyoyo42Selected = ( $wPhotoKyoyo4 == "2" ) ? "selected" : "" ;
			$PhotoKyoyo43Selected = ( $wPhotoKyoyo4 == "3" ) ? "selected" : "" ;
			$PhotoKyoyo44Selected = ( $wPhotoKyoyo4 == "4" ) ? "selected" : "" ;

			$wPKScene4 = $myIraiRenkei->PKScene4;
			$PKScene41Selected = ( $wPKScene4 == "1" ) ? "selected" : "" ;
			$PKScene42Selected = ( $wPKScene4 == "2" ) ? "selected" : "" ;
			$PKScene43Selected = ( $wPKScene4 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo5 = $myIraiRenkei->PhotoKyoyo5;
			$wPKScene5 = $myIraiRenkei->PKScene5;
			$PKScene51Selected = ( $wPKScene5 == "1" ) ? "selected" : "" ;
			$PKScene52Selected = ( $wPKScene5 == "2" ) ? "selected" : "" ;
			$PKScene53Selected = ( $wPKScene5 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo6 = $myIraiRenkei->PhotoKyoyo6;
			$wPKScene6 = $myIraiRenkei->PKScene6;
			$PKScene61Selected = ( $wPKScene6 == "1" ) ? "selected" : "" ;
			$PKScene62Selected = ( $wPKScene6 == "2" ) ? "selected" : "" ;
			$PKScene63Selected = ( $wPKScene6 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo7 = $myIraiRenkei->PhotoKyoyo7;
			$wPKScene7 = $myIraiRenkei->PKScene7;
			$PKScene71Selected = ( $wPKScene7 == "1" ) ? "selected" : "" ;
			$PKScene72Selected = ( $wPKScene7 == "2" ) ? "selected" : "" ;
			$PKScene73Selected = ( $wPKScene7 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo8 = $myIraiRenkei->PhotoKyoyo8;
			$wPKScene8 = $myIraiRenkei->PKScene8;
			$PKScene81Selected = ( $wPKScene8 == "1" ) ? "selected" : "" ;
			$PKScene82Selected = ( $wPKScene8 == "2" ) ? "selected" : "" ;
			$PKScene83Selected = ( $wPKScene8 == "3" ) ? "selected" : "" ;

			$wPhotoKyoyo9 = $myIraiRenkei->PhotoKyoyo9;
			$wPKScene9 = $myIraiRenkei->PKScene9;
			$PKScene91Selected = ( $wPKScene9 == "1" ) ? "selected" : "" ;
			$PKScene92Selected = ( $wPKScene9 == "2" ) ? "selected" : "" ;
			$PKScene93Selected = ( $wPKScene9 == "3" ) ? "selected" : "" ;

			$wIR01 = $myIraiRenkei->IR01;#元請けチェック20160821
			$IR01Checked = ($wIR01[0] == 1 )? "checked":"";

			$wIR02 = $myIraiRenkei->IR02;#腕章ありチェック20160902 (ポスティングあり時のみ)
			$IR02Checked = ($wIR02[0] == 1 )? "checked":"";

			$wNotes = $myIraiRenkei->Notes;
		}
		unset($myIraiRenkei);
	}


	########################################################
	# 関連資料表示
	########################################################

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "IraiFileCD, ";
	$sql .= "P001, ";#ファイル名
	$sql .= "P002, ";#ファイル拡張子
	$sql .= "File, ";#ファイルそのもの
	$sql .= "Created ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tIraiFileF";
	$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD;

	$myListObject->Condition = $sql;
	$myListObject->Order = "IraiFileCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting IraiFile List Failed.", E_USER_ERROR);

	$IraiFileLoop = $myListObject->Rows;
	for ($i = 0; $i < $IraiFileLoop; $i++) {
		$IraiFileCD[$i] = $myListObject->GetValue($i, 0);
		$IraiFileName[$i] = $myListObject->GetValue($i, 1);
		$IraiFileType[$i] = $myListObject->GetValue($i, 2);
		$IraiFile[$i] = $myListObject->GetValue($i, 3);
		$Created[$i] = $myListObject->GetValue($i, 4);

		$IraiFile[$i] =  "<a href=./viewiraifile.php?IraiFileCD=".$IraiFileCD[$i]." TARGET=_blank >".$IraiFileName[$i]."</a>";
//		$IraiFile[$i] .= "<br><input type=checkbox name=chk[] value=$IraiFileCD[$i]>".$IraiFileName[$i];
		$FileNo[$i] = $i+1;
	}



*/
	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_postde.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
