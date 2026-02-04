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
	$TantoTEL = getTantoData($myDB, $UserCD )['TEL'];

	unset($myUser);

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
	$wAutoLock = $myBukken->AutoLock;#1:あり　2:なし
	$AutoLockChecked1 = ( $wAutoLock == "1" ) ? "Checked" : "" ; 
	$AutoLockChecked2 = ( $wAutoLock == "2" ) ? "Checked" : "" ; 

	#件名No 物件機器取得のため
	$KenmeiNo = $myBukken->KenmeiNo;

	if($myBukken->TantoCD)
		$TantoTEL = getTantoData($myDB, $myBukken->TantoCD )['TEL'];
	
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
	
		#タイトル部
		#消防特例	#Bukkenにも同じカラムがあるため混同注意！
		$ShoboTokurei = $myKoji->ShoboTokurei;#0:なし　1:170　2:220　3:220
		switch($ShoboTokurei){
			case 0:
				$ShoboTokureiDisp = '特例なし';
				break;
			case 1:
				$ShoboTokureiDisp = '170号';
				break;
			case 2:
				$ShoboTokureiDisp = '220号住戸用';
				break;
			case 3:
				$ShoboTokureiDisp = '220号共同住宅用';
				break;
		}

		#オプション オプション関連の表示ON/OFF制御
		if( $myKoji->OP1DeviceCD ||  $myKoji->OPZiyuu1) $IfOP = TRUE;
		if($IfOP){
			$EXECINIT = "true";
			$OPDisp = "オプションあり";
		}else{
			#オプション関連の表示を無効化するようタグをセット
			$noOPSetFontColor = "<span style=\"color:lightgray;\">";
			$noOPspanEnd = "</span>";
			$noOPSetDisabled = "disabled";
			$EXECINIT = "false";	
		}

		$KojiShozokuName = $myKoji->KojiShozokuName;	#営業所
		$KojiTantoName = $myKoji->KojiTantoName;	#担当

		$wSekoShutai = $myKoji->SekoShutai;
		$SekoShutaiAIHON = "アイホン株式会社";

		#施工主体がアイホンさん（元請け）の場合
		if ($wSekoShutai == $SekoShutaiAIHON){
			$DispCompanyTop1=$wBukkenName."管理組合";	#マンション名+管理組合
		}else{
			$DispCompanyTop1=$KanriGaisya;	#管理会社
		}
		$DispCompanyTop2=$SekoShutaiAIHON;#アイホン固定
		

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

				if($ToiawasewakiDispArr[2] == 'アイホン'){
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
/*	不使用
		$ZentaiStartDate = $myKoji->ZentaiStartDate;
		$ZentaiEndDate = $myKoji->ZentaiEndDate;
		$SenyuStartDate = $myKoji->SenyuStartDate;
		$SenyuEndDate = $myKoji->SenyuEndDate;
		$KyoyoStartDate = $myKoji->KyoyoStartDate;
		$KyoyoEndDate = $myKoji->KyoyoEndDate;
		$YobiStartDate = $myKoji->YobiStartDate;
		$YobiEndDate = $myKoji->YobiEndDate;
*/

	
#不使用 201903
		#$KojiName = $myKoji->KojiName;
		
		$SiyobuzaiOyaFlg = $myKoji->SiyobuzaiOyaFlg;
		if(empty($SiyobuzaiOyaFlg)){
			$SiyobuzaiOyaFlg = 1; #デフォルトを1：なしにセット
		}
		${"OyakiPanelChecked".$SiyobuzaiOyaFlg}="checked";

		$SiyobuzaiKoFlg = $myKoji->SiyobuzaiKoFlg;
		if(empty($SiyobuzaiKoFlg)){
			$SiyobuzaiKoFlg = 1; #デフォルトを1：なしにセット
		}
		${"KokiPanelChecked".$SiyobuzaiKoFlg}="checked";



/* 不使用 201903
		$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1;
		$GyosyaData = getGyosyaData($myDB, $GyosyaTantoCD1 );
		$GyosyaName = $GyosyaData['GyosyaName'];
*/
		#既に登録済み
		$wConstTime = $myKoji->ConstTime;
		if( !$wConstTime )$wConstTime = "60";
		
		##リニューアルパネルの有無
		#親機
		#子機
		
		#切替方法
		#切替方法 0:停止 1:並行稼働
		$wKirikaehoho = $myKoji->Kirikaehoho;
		${"KirikaehohoChecked".$wKirikaehoho} = " checked";
		
		#切替並行稼働時映像幹線利用 0:しない 1:する←デフォルト
		$wKirikaeHeikoEizoriyo = $myKoji->KirikaeHeikoEizoriyo;
		${"KirikaeHeikoEizoriyoChecked".$wKirikaeHeikoEizoriyo} = " checked";

		#自火報連動
		$wJikaho = $myKoji->Jikaho;
		${"JikahoChecked".$wJikaho} = " checked";

		#火災抵抗器交換部屋立入り
		$wKasaiHeya = $myKoji->KasaiHeya;
		${"KasaiHeyaChecked".$wKasaiHeya} = " checked";

		#幹線ルート
		$wKansenKoji = $myKoji->KansenKoji;
		${"KansenKojiChecked".$wKansenKoji} = " checked";
		
		#ガス漏れ警報器連動
		$wGasKoji = $myKoji->GasKoji;
		${"GasKojiChecked".$wGasKoji} = " checked";

		#防犯センサー連動
		$wBohanKoji = $myKoji->BohanKoji;
		${"BohanKojiChecked".$wBohanKoji} = " checked";

		#漏水センサー連動
		$wRosuiKoji = $myKoji->RosuiKoji;
		${"RosuiKojiChecked".$wRosuiKoji} = " checked";

		#宅配連動 ADD GOE 201902
		$wTakuhai = $myKoji->Takuhai;
		if($wTakuhai == NULL){
			$TakuhaiChecked0 = " checked";
		}else{
			${"TakuhaiChecked".$wTakuhai} = " checked";
		}

		$wAnswer = $myKoji->Answer;
		$wWEBRecept = $myKoji->WEBRecept;
#		echo $wAnswer;

		if($wAnswer == "A.日時変更住戸のみ返答"){
			$Answer1Selected = "selected";
		}elseif($wAnswer == "B.全住戸返答"){
			$Answer2Selected = "selected";
		}elseif($wAnswer == "C.全住戸返答+確定時未返事シート"){
			$Answer3Selected = "selected";
		}else{
			
		}

		if($wWEBRecept == "1"){
			$WEBRecept1Checked = "checked";
		}elseif($wWEBRecept == "0"){
			$WEBRecept0Checked = "checked";
		}
		#オプションありの場合
		#オプション支払い方法
		#値がある場合のみ
		if(!empty($myKoji->Shiharai)){
			$wShiharai = SPFWTools::decodePluralValue($myKoji->Shiharai); // 文字列を配列に変換
			if (in_array("現金",$wShiharai)) $ShiharaiSelected1 = "checked";
			if (in_array("振込",$wShiharai)) $ShiharaiSelected2 = "checked";
			if (in_array("NP",$wShiharai)) $ShiharaiSelected3 = "checked";
			if (in_array("コンビニ",$wShiharai)){
				$ShiharaiSelected4 = "checked";
				if (in_array("上限あり",$wShiharai)){	//コンビニ払いの場合　上限あり/なしを指定
					$ShiharaiConveni1 = "checked";
				}else{
					$ShiharaiConveni0 = "checked";
				}
			}else{
				$ShiharaiConveni0 = "checked";	//デフォルトで通常を選択
			}
		}
		
		#オプション受付方法
		${"OPUketukeChecked".$myKoji->OPUketuke} = " checked"; // 0:日程受付と同じフリーダイヤル 1:アンケート


		#タグ関連の表示有効/無効化
		$wCurrentNyukan = $myKoji->CurrentNyukan;
		$wCurrentNyukan = SPFWTools::decodePluralValue($wCurrentNyukan);#配列

		$wRNNyukan = $myKoji->RNNyukan;
		$wRNNyukan = SPFWTools::decodePluralValue($wRNNyukan);#パイプつなぎを配列に変換

		if(in_array('2',$wRNNyukan) && !in_array('2',$wCurrentNyukan)){	#RN前になくてリニューアル後の入館方法にタグがある場合　→有効
			$noTagSetFontColor = "";
			$spanEnd = "";
			$noTagSetDisabled = "";
			$EXCITETag = "true";

			#タグ　について（ノンタッチ導入の場合有効）
			#タグ本数
			$wTagSuu = $myKoji->TagSuu;
			if( $myKoji->TagSuu == "0" )$wTagSuu = "0";#初期値
			$wOwnerTagSuu = $myKoji->OwnerTagSuu;	#外部オーナー渡しタグ数 201902 ADD GOE
			#切替タイミング
			$wTagKirikae = $myKoji->TagKirikae;	#タグ切替タイミング 201903 ADD GOE
			if($wTagKirikae == NULL){
				$TagKirikaeChecked1 = " checked";
			}else{
				${"TagKirikaeChecked".$wTagKirikae} = " checked";
			}

		}else{
			$noTagSetFontColor = "<span style=\"color:lightgray;\">";
			$noTaSpanEnd = "</span>";
			$noTagSetDisabled = "disabled";
			$EXCITETag = "false";
			
		}


		

		#作業員着用するもの
		if (strpos($myKoji->Sagyoin,"ベスト")) $wSagyoinChecked1 = "checked";
		if (strpos($myKoji->Sagyoin,"腕章")) $wSagyoinChecked2 = "checked";


		#指定ベスト
		$wShiteiVestCD = $myKoji->ShiteiVestCD;


		$ShiteiVestLoop = count( $SHITEIVEST );
		for( $i=0; $i< $ShiteiVestLoop ; $i++){
			$ShiteiVest[$i] = $SHITEIVEST[$i];
			$ShiteiVestCD[$i] = $i;
			if ( $wShiteiVestCD == $ShiteiVestCD[$i] ) $SelectedShiteiVest[$i] = "selected";
		}

		$ShiteiVestOther = $myKoji->ShiteiVestOther;
		
	}#★工事情報利用処理 END
	unset($myKoji);




	########################################
	# tBukkenKikiM の取得
	########################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "MEISAI_KIKI_KBN, ";
	$sql .= "SYHN_CD, ";
	$sql .= "HINBAN, ";
	$sql .= "HINMEI_SIYO, ";
	$sql .= "JUC_SURYO ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tBukkenKikiM";
	$sql .= " WHERE KenmeiNo = '".$KenmeiNo."' AND MukouFlg = FALSE";

	$myListObject->Condition = $sql;
	$myListObject->Order = "JUC_GYO_NO";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	if ($myListObject->Rows != 0) {
		$MeisaiLoop = $myListObject->Rows;
		for ($i = 0; $i < $MeisaiLoop; $i++) {
			#明細機器区分　0：標準品／1：モジュール品／2：受注品／A：工事費／B：他社品／C：コメント／D：220号他社品／+：合計／/：値引き
			$HINMEI_SIYO[$i] = $myListObject->GetValue($i, 3);

			if(strpos($HINMEI_SIYO[$i],"集合玄関機")!== false ){
				$AutoLockChecked1 = " checked ";
				$AutoLockChecked2 = "";
			}
		}
	}
	unset($myListObject);


	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_koji_annai_01sapporo.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
