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
	include_once _CLS_DIR . "SPUSFile.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";#工事クラス
	include_once _CLS_DIR . "SPUSDevice.cls";#デバイスクラス
	include_once _CLS_DIR . "SPUSGyosya.cls";


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
	$GyosyaCD = $myUser->Extra5;
	$TantoTEL = getTantoData($myDB, $UserCD )['TEL'];

	unset($myUser);
	########################################################
	# 会社情報表示(業者情報）
	########################################################
	$myGyosya = new Gyosya($myDB);

	if (!$myGyosya->executeSelect("GyosyaCD > 0 AND MukouFlg = FALSE AND GyosyaCD = ".$GyosyaCD , ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);

	#会社名
	$GyosyaName = $myGyosya->GyosyaName;

	########################################################
	# 物件情報表示
	########################################################
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);

	#タイトル部
	#マンション名
	$wBukkenName = $myBukken->BukkenName;
	#管理会社
	$KanriGaisya = $myBukken->KanriGaisya;


	#集合玄関機有無
#	$wAutoLock = $myBukken->AutoLock;#1:あり　2:なし
#	$AutoLockChecked1 = ( $wAutoLock == "1" ) ? "Checked" : "" ;
#	$AutoLockChecked2 = ( $wAutoLock == "2" ) ? "Checked" : "" ;

	#件名No 物件機器取得のため
	$KenmeiNo = $myBukken->KenmeiNo;

#	if($myBukken->TantoCD)
#		$TantoTEL = getTantoData($myDB, $myBukken->TantoCD )['TEL'];

	unset($myBukken);


	########################################################
	# 工事情報抽出
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect(" BukkenCD = ".$editBukkenCD ." AND MukouFlg=FALSE", ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);

	if ($myKoji->RecCnt != 1) {
		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	#工事情報に登録がある場合　表示表の値を取得する
	} else {#★工事情報利用処理

		#オプション オプション関連の表示ON/OFF制御
		if( $myKoji->OP1DeviceCD ||  $myKoji->OPZiyuu1) $IfOP = TRUE;
		if ($IfOP) { #オプションあり
			$opcnt = 0; // オプション数
			for ($i = 1; $i <= 10; $i++) {
				$strcd = "OP".$i."DeviceCD";
				$strcdZyuu = "OPZiyuu".$i;
				if ($myKoji->$strcdZyuu) { #自由入力の機器
					$opcnt += 1;
				}elseif ($myKoji->$strcd) {#通常の機器
					$opcnt += 1;
				}
			}
			if ($opcnt > 6) {
				$op_msg = "※オプションが　".$opcnt."　個登録されています。<br>";
				$op_msg .= "工事案内資料[オプション機器のご案内]シートは、6個までしか対応しておりません。<br>";
				$op_msg .= "恐れ入りますが、工事案内資料をダウンロード後、7個目以降は手修正で対応をお願いいたします。<br>";
			}
		}

		$wSekoShutai = $myKoji->SekoShutai;
#		$SekoShutaiAIHON = "アイホン株式会社";GyosyaName
		$SekoShutaiAIHON = $GyosyaName;

		if ($myKoji->DispCompanyTop == "") { // DispCompanyTopが空（工事案内を作ったことがないと判断する）
			// 元請けか下請けかで、資料上部の会社名を変更する
			if ($wSekoShutai == $SekoShutaiAIHON){ #施工主体がアイホンさん（元請け）の場合
				$DispCompanyTop1 = $wBukkenName."管理組合";	#マンション名+管理組合
			}else{
				$DispCompanyTop1 = $KanriGaisya; #管理会社
			}
			$DispCompanyTop2 = $SekoShutaiAIHON; #会社名固定
		} else {
			// 工事案内を1度でも登録済の場合
			$DispCompanyTop = SPFWTools::decodePluralValue($myKoji->DispCompanyTop);
			$DispCompanyTop1 = $DispCompanyTop[0];
			$DispCompanyTop2 = $DispCompanyTop[1];
			$DispCompanyTop3 = $DispCompanyTop[2];
		}

		#問合せ先の値を取得
		if(!empty($myKoji->ToiawasewakiDisp)){

			$ToiawasewakiDispArr = SPFWTools::decodePluralValue($myKoji->ToiawasewakiDisp);#パイプつなぎを配列に変換
			if(count($ToiawasewakiDispArr) == 7){

				if($ToiawasewakiDispArr[0] == '管理会社'){
					$DispCompanyChecked1 = 'checked';
				}
				if($ToiawasewakiDispArr[1] == 1){
					$DispCompanyBottom1FLGchecked1 = 'checked';
				}else{
					$DispCompanyBottom1FLGchecked0 = 'checked';
				}

#				if($ToiawasewakiDispArr[2] == 'アイホン'){
				if($ToiawasewakiDispArr[2] == $GyosyaName){
					$DispCompanyChecked2 = 'checked';
				}

				if($ToiawasewakiDispArr[3] == 1){
					$DispCompanyBottom2FLGchecked1 = 'checked';
				}else{
					$DispCompanyBottom2FLGchecked0 = 'checked';
				}

				if(!empty($ToiawasewakiDispArr[4])){
					$DispCompanyBottom3=$ToiawasewakiDispArr[4];

					$DispCompanyChecked3 = 'checked';
				}

				if($ToiawasewakiDispArr[5] == 1){
					$DispCompanyBottomTEL3 = $ToiawasewakiDispArr[6];
					$DispCompanyBottom3FLGchecked1 = 'checked';
				}else{
					$DispCompanyBottom3FLGchecked0 = 'checked';
				}
			}
		}

		#作業員着用するもの
		if (strpos($myKoji->Sagyoin,"ベスト")) $wSagyoinChecked1 = "checked";
		if (strpos($myKoji->Sagyoin,"腕章")) $wSagyoinChecked2 = "checked";

		#指定ベスト
		$wShiteiVestCD = $myKoji->ShiteiVestCD;

		$ShiteiVestLoop = count( $SHITEIVEST );

		for( $i=0 ; $i< $ShiteiVestLoop ; $i++){
			$ShiteiVestName[$i] = $SHITEIVEST[$i];
			if($i==0) $ShiteiVestName[0] = $GyosyaName;#アイホン→お客さん名に上書き
			$ShiteiVestCD[$i] = $i;
			if ( $wShiteiVestCD == $ShiteiVestCD[$i] ) $SelectedShiteiVest[$i] = "selected";
		}
		$ShiteiVestOther = $myKoji->ShiteiVestOther;


		#親機・玄関子機のパネル有無
		$SiyobuzaiOyaFlg = $myKoji->SiyobuzaiOyaFlg;
		if($SiyobuzaiOyaFlg === "0" OR $SiyobuzaiOyaFlg === "1"){
			${"SiyobuzaiOyaFlgChecked".$SiyobuzaiOyaFlg}="checked";
		}

		$SiyobuzaiKoFlg = $myKoji->SiyobuzaiKoFlg;
		if($SiyobuzaiKoFlg === "0" OR $SiyobuzaiKoFlg === "1"){
			${"SiyobuzaiKoFlgChecked".$SiyobuzaiKoFlg}="checked";
		}

	}#★工事情報利用処理 END
	unset($myKoji);



	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_koji_annai_test.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
