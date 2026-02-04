<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', "On");
?>
<?php
/* 
 * 工事案内出力
 * s_koji_annai_Excel.php
*/
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
	include_once _CLS_DIR . "SPUSFile.cls";

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

	$loginUserCD = $myUser->UserCD;
	$ID = $myUser->ID;
	unset($myUser);

	#$TantoTEL = getTantoData($myDB, $UserCD )['TEL'];


	#必要な項目
/*
物件名、管理会社名、工事名、連絡先所属名、連絡先担当名、連絡先TEL
全体工期、共用部工期、専有部工期、予備日（開始、終了）、作業時間（開始時刻～終了時刻）
自火報交換工事有無、作業所要時間（60分）、
火災感知器入室有無　なしかキッチンか全居室
ガス漏れ警報器有無
防犯センサー有無
漏水センサー有無

*/


	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');


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
		$BukkenName = $myBukken->BukkenName;
		$KanriGaisya = $myBukken->KanriGaisya;
		$KanriGaisyaTEL = $myBukken->KanriGaisyaTEL;
		$wAutoLock = $myBukken->AutoLock;

		if($wAutoLock == 1 ){
			$DispAutoLock = "エントランス・";
			$DispAutoLock2 = "オートロック解錠機能、";
			$DispAutoLock3 = "オートロック解錠・";
		}
		#$ShoboTokurei = $myBukken->ShoboTokurei;#0:なし　1:170　2:220　3:220
	}
	
	#入力値をDB格納
	
	if (!$myBukken->executeUpdate()){
		trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
	}
	unset($myBukken);

	########################################################
	# 工事情報抽出
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")){
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}

	if ($myKoji->RecCnt != 1) {

		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	} else {

		$weekarray = array("(日)","(月)","(火)","(水)","(木)","(金)","(土)");
		$KojiName = $myKoji->KojiName;
		$KojiShozokuName = $myKoji->KojiShozokuName;
		$KojiTantoName = $myKoji->KojiTantoName;
		$TantoTEL = $myKoji->KojiShozokuTEL;

		$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1;

		$ShoboTokurei = $myKoji->ShoboTokurei;#0:なし　1:170　2:220　3:220
		$ShoboSikenhoho = $myKoji->ShoboSikenhoho;	#0:着工前後　1:終了後　2:都度
		$ShoboSikenDate = $myKoji->ShoboSikenDate;	#
		$ShoboSikenJikan = $myKoji->ShoboSikenJikan;	#
		$ShoboSikenJikan2 = $myKoji->ShoboSikenJikan2;	#
		$ShoboSikenDate = date('Y年n月j日',strtotime( $myKoji->ShoboSikenDate ));
		$ShoboSikenDate .= $weekarray[ date('w',strtotime( $myKoji->ShoboSikenDate )) ];


		$Kanrisitu = $myKoji->Kanrisitu;#0:なし　1:あり 201903 ADD GOE

		$SekoShutai = $myKoji->SekoShutai; //201902 ADD GOE

		$GyosyaData = getGyosyaData($myDB, $GyosyaTantoCD1);
		$GyosyaName = $GyosyaData['GyosyaName'];

		$ZentaiStartDate = date('Y年n月j日',strtotime( $myKoji->ZentaiStartDate ));
		$ZentaiStartDate .= $weekarray[ date('w',strtotime( $myKoji->ZentaiStartDate )) ];
		$ZentaiEndDate = date('Y年n月j日',strtotime( $myKoji->ZentaiEndDate ));
		$ZentaiEndDate .= $weekarray[ date('w',strtotime( $myKoji->ZentaiEndDate )) ];

//専有部終了日の登録がない場合の処理追加
		$SenyuStartDate = date('Y年n月j日',strtotime( $myKoji->SenyuStartDate ));
		$SenyuStartDate .= $weekarray[ date('w',strtotime( $myKoji->SenyuStartDate )) ];
		if(empty($myKoji->SenyuEndDate) 
			OR  $myKoji->SenyuStartDate === $myKoji->SenyuEndDate){#専有部が1日だけの場合

			$SenyuEndDate = "";
		}else{
			$SenyuEndDate = date('Y年n月j日',strtotime( $myKoji->SenyuEndDate )); 
			$SenyuEndDate .= $weekarray[ date('w',strtotime( $myKoji->SenyuEndDate )) ];
		}

//共用部終了日の処理追加 201902 ADD GOE
		$KyoyoStartDate 	= $myKoji->KyoyoStartDate; 
		$KyoyoEndDate 	= $myKoji->KyoyoEndDate; 
		
		
		#共用部に値がある場合
		if(!empty($KyoyoStartDate)){
			if(empty($KyoyoEndDate) OR $KyoyoStartDate === $KyoyoEndDate){#共用部が1日だけの場合
				$KyoyoEndDate = "";
			}else{	#共用部終了日に値がある又は開始日と別日の場合のみフォーマットを変換する
				$KyoyoEndDate = date('Y年n月j日',strtotime( $myKoji->KyoyoEndDate )); 
				$KyoyoEndDate .= $weekarray[ date('w',strtotime( $myKoji->KyoyoEndDate )) ];
			}
			$KyoyoStartDate 	= date('Y年n月j日',strtotime( $myKoji->KyoyoStartDate )); 
			$KyoyoStartDate 	.= $weekarray[ date('w',strtotime( $myKoji->KyoyoStartDate )) ];

			if ($myKoji->Holiday1 != "") {
				$Holiday1 		= SPFWTools::decodePluralValue($myKoji->Holiday1);#配列へ変換
				$x=2;
				$y=0;
				for($i=0;$i<count($Holiday1);$i++){
					if($i==0){
						${"Holiday".$x}		= date('Y年n月j日',strtotime( $Holiday1[$i] )); 
						${"Holiday".$x}		.= $weekarray[ date('w',strtotime( $Holiday1[$i] )) ];
					}else{
						if(date('Y年n月',strtotime( $Holiday1[$i-1] ))==date('Y年n月',strtotime( $Holiday1[$i] ))&& $y<3){
							${"Holiday".$x} 	.= ",".date('j日',strtotime( $Holiday1[$i] ));
							${"Holiday".$x}	.= $weekarray[ date('w',strtotime( $Holiday1[$i] )) ];
							$y++;
						}else{
							$x++;
							${"Holiday".$x} 	.= ",".date('Y年n月j日',strtotime( $Holiday1[$i] ));
							${"Holiday".$x}	.= $weekarray[ date('w',strtotime( $Holiday1[$i] )) ];
							$y=0;
						}
					}
				}
			}
		}else{
			$KyoyoStartDate = "";
		}
		
/*		if ($myKoji->Holiday2 != "0000-00-00") {
			$Holiday2 		= date('Y年n月j日',strtotime( $myKoji->Holiday2 )); 
			$Holiday2 		.= $weekarray[ date('w',strtotime( $myKoji->Holiday2 )) ];
			$Holiday[]		= $Holiday2;
		}
		if ($myKoji->Holiday3 != "0000-00-00") {
			$Holiday3 		= date('Y年n月j日',strtotime( $myKoji->Holiday3 )); 
			$Holiday3 		.= $weekarray[ date('w',strtotime( $myKoji->Holiday3 )) ];
			$Holiday[]		= $Holiday3;
		}
		if ($myKoji->Holiday4 != "0000-00-00") {
			$Holiday4 		= date('Y年n月j日',strtotime( $myKoji->Holiday4 )); 
			$Holiday4 		.= $weekarray[ date('w',strtotime( $myKoji->Holiday4 )) ];
			$Holiday[]		= $Holiday4;
		}
*/
		#予備日に登録があれば
		if (!empty($myKoji->YobiStartDate)) {
			$YobiStartDate = date('Y年n月j日',strtotime( $myKoji->YobiStartDate )); 
			$YobiStartDate .= $weekarray[ date('w',strtotime( $myKoji->YobiStartDate ))] ;

			#予備日が一日以上の場合
			if(empty($myKoji->YobiEndDate) 
				OR $myKoji->YobiStartDate === $myKoji->YobiEndDate){#予備日が1日だけの場合 201903 ADD
				$YobiEndDate = ""; #フォーマット変換せず空をセット
			}else{
				$YobiEndDate = date('Y年n月j日',strtotime( $myKoji->YobiEndDate )); 
				$YobiEndDate .= $weekarray[ date('w',strtotime( $myKoji->YobiEndDate )) ];
			}

		}else{
			$YobiStartDate = "";
		}
		
		$AnnaiDate = date('Y年n月j日',strtotime( $myKoji->AnnaiDate ));	#案内配布日
		$AnnaiDate2 = date('Y年n月j日',strtotime( $myKoji->AnnaiDate ));	#案内配布日
		$AnnaiDate .= $weekarray[ date('w',strtotime( $myKoji->AnnaiDate )) ];
		$ReceptionDate = date('Y年n月j日',strtotime( $myKoji->ReceptionDate ));	#受付締切日
		$ReceptionDate .= $weekarray[ date('w',strtotime( $myKoji->ReceptionDate )) ];
		$ReceptionDate2 = date('Y-n-j',strtotime( $myKoji->ReceptionDate ));	#受付締切日

		if ($myKoji->OpEndDate) {
			$OpEndDate = date('Y年n月j日',strtotime( $myKoji->OpEndDate ));	#オプション締切日
			$OpEndDate .= $weekarray[ date('w',strtotime( $myKoji->OpEndDate )) ];
		} else {
			$OpEndDate = $ReceptionDate;
		}

		$WakuPattern = $myKoji->WakuPattern;
		$AnshoNo = $myKoji->AnshoNo;	#//201902 ADD GOE
		$AnshoNoKojichu = $myKoji->AnshoNoKojichu;
		$CurrentNyukan = $myKoji->CurrentNyukan;
		$arrayCurrentNyukan = SPFWTools::decodePluralValue($CurrentNyukan);#配列へ変換
		if (in_array("2",$arrayCurrentNyukan)) { // 従前の入館方法にノンタッチタグ:2 が含まれるかどうか
			$wTagKoji = 1;
		}

		#オプションある場合
		for($i=1;$i<11;$i++){

			if( $myKoji->{"OP".$i."DeviceCD"} || $myKoji->{"OPZiyuu".$i}) { 
				$IfOP = TRUE;
			}
		}	

		if( $IfOP) { 

			#オプションある場合のみ有効な画面の値をDBに登録
			$wShiharai = SPFWTools::decodePluralValue($myKoji->Shiharai); // 配列を文字列に変換
			#オプション支払い方法
			if(in_array("コンビニ",$wShiharai)){
				if($wShiharaiConveni == 1){
					$wShiharai[] = '上限あり';
				}
			}

			#オプション受付方法
			$wOPUketuke = $myKoji->OPUketuke;


			# OPメニュー一覧
			$myListObject = new SPFWListObject($myDB);
			$sql = "SELECT ";
			$sql .= "DeviceCD, ";	#機器CD
			$sql .= "DeviceName, ";	#機器名
			$sql .= "Kataban, ";	#型番
			$sql .= "Category, ";	#カテゴリ
			$sql .= "OPUseKbn, ";	#OP使用区分
			$sql .= "OPUseDisp ";	#OP説明文
			$myListObject->SelectSQL = $sql;
			$sql = " FROM tDeviceM ";
			$sql .= " WHERE  MukouFlg = FALSE ";
			$myListObject->Condition = $sql;
			$myListObject->Order = "";
			$myListObject->Limit = "allpage";

			if (!($myListObject->GetList(1)))
				trigger_error("Getting Device List Failed.", E_USER_ERROR);

			$OPDeviceLoop = $myListObject->Rows;
			for ($i = 0; $i < $OPDeviceLoop; $i++) {
				$listOPDeviceCD[$i] = $myListObject->GetValue($i, 0);
				$listOPDeviceName[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 1);
				$listOPKataban[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 2);
				$listOPCategory[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 3); // 数字 Choice5Nameでカテゴリ名取得可能
				$listOPUseKbn[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 4); // 数字 Choice5Nameでカテゴリ名取得可能
				$listOPUseDisp[$listOPDeviceCD[$i]] = $myListObject->GetValue($i, 5); // 数字 Choice5Nameでカテゴリ名取得可能
			}
			unset($myListObject);



			$opcnt = 0; // 配列カウント
			$OPInfo = array();
			for ($i = 1; $i <= 10; $i++) {
				// https://qiita.com/mpyw/items/41230bec5c02142ae691
				$strcd = "OP".$i."DeviceCD";
				$strpr = "OPPrice".$i;
				$strcdZyuu = "OPZiyuu".$i;	#201905 ADD 自由記入の機器
				
				#自由入力の機器があればそちらが優先
				if ($myKoji->$strcdZyuu) {
					$OPInfo[$opcnt]["OPDeviceName"] = $myKoji->$strcdZyuu;
					$OPInfo[$opcnt]["OPUseKbn"] =  "Z";
					$OPPrice = $myKoji->$strpr; // OPPrice1 ～ OPPrice10
					if ($OPPrice > 0)  $OPPrice = number_format($OPPrice); // カンマ区切り
					$OPInfo[$opcnt]["OPPrice"] = $OPPrice;
					$opcnt += 1; // 配列カウント		
				#通常の機器
				}elseif ($myKoji->$strcd) {
					$OPCD = $myKoji->$strcd; // OP1DeviceCD ～ OP10DeviceCD
					$OPPrice = $myKoji->$strpr; // OPPrice1 ～ OPPrice10
					if ($OPPrice > 0)  $OPPrice = number_format($OPPrice); // カンマ区切り

					$OPInfo[$opcnt]["OPDeviceName"] = $listOPDeviceName[$OPCD];
					$OPInfo[$opcnt]["OPCategory"] = $KIKICATEGORY[ $listOPCategory[$OPCD] ];
					$OPInfo[$opcnt]["OPCategoryNo"] = $listOPCategory[$OPCD];
					$OPInfo[$opcnt]["OPKataban"] = $listOPKataban[$OPCD];
					$OPInfo[$opcnt]["OPPrice"] = $OPPrice;
					$OPInfo[$opcnt]["OPUseKbn"] =  $listOPUseKbn[$OPCD];
					$OPInfo[$opcnt]["OPUseDisp"] = $listOPUseDisp[$OPCD];
					$opcnt += 1; // 配列カウント
				}
			}

		}

		#入館方法を取得
		$wRNNyukan = $myKoji->RNNyukan;	#|0|1|2| 鍵　暗証番号　ノンタッチ　で入っている#
		$wCurrentNyukan = $myKoji->CurrentNyukan;	#RN前
		
		#タグ切替日を取得
		if($wTagKirikae == '1'){	#タグ切替が着工日の場合	共用部開始日を設定
			$TagKirikaeDate = $myKoji->KyoyoStartDate; 
		}else{	#工事終了日の場合	専有部終了日を設定
			if($SenyuEndDate == ""){#終了日がない（専有部工事が一日場合は）開始日をセット
				$TagKirikaeDate = $myKoji->SenyuStartDate;
			}else{
				$TagKirikaeDate = $myKoji->SenyuEndDate;
			}
		}

	}

	$Kanrisitu = $myKoji->Kanrisitu;	#0:無　1:有	管理室親機有無　201903ADDGOE

	#入力値をDB格納
	$wConstTime = $myKoji->ConstTime;
	$wKirikaehoho = $myKoji->Kirikaehoho;
	
	$wKirikaeHeikoEizoriyo = $myKoji->KirikaeHeikoEizoriyo;
	$wJikaho = $myKoji->Jikaho;
	$wKasaiHeya = $myKoji->KasaiHeya;
	$wKansenKoji = $myKoji->KansenKoji;
	$wGasKoji = $myKoji->GasKoji;
	$wBohanKoji = $myKoji->BohanKoji;
	$wRosuiKoji = $myKoji->RosuiKoji;
	$wTakuhai = $myKoji->Takuhai;
	
//	echo __LINE__.$wAnswer;
	$wAnswer = $myKoji->Answer;
	$wWEBRecept = $myKoji->WEBRecept;
	$wTagSuu = $myKoji->TagSuu;
	$wOwnerTagSuu = $myKoji->OwnerTagSuu;
	$wTagKirikae = $myKoji->TagKirikae;
	$wOyakiPanel = $myKoji->SiyobuzaiOyaFlg;
	$wKokiPanel = $myKoji->SiyobuzaiKoFlg;
	
	
	###資料上部に表示する会社名###
	#DB1カラムに登録するため下記形状に変換して登録
	#|1番目に表示する会社名|2番目|3番目|
	# 配列を文字列に変換
	$DispCompanyTop1 = SPFWParameter::getValues('DispCompanyTop1');
	$DispCompanyTop2 = SPFWParameter::getValues('DispCompanyTop2');
	$DispCompanyTop3 = SPFWParameter::getValues('DispCompanyTop3');
	$DispCompanyBottomChk1 = SPFWParameter::getValues('DispCompanyBottomChk1');
	$DispCompanyBottomChk2 = SPFWParameter::getValues('DispCompanyBottomChk2');
	$DispCompanyBottomChk3 = SPFWParameter::getValues('DispCompanyBottomChk3');
	$DispCompanyBottom3 = SPFWParameter::getValues('DispCompanyBottom3');
	$DispCompanyBottom1FLG = SPFWParameter::getValues('DispCompanyBottom1FLG');
	$DispCompanyBottom2FLG = SPFWParameter::getValues('DispCompanyBottom2FLG');
	$DispCompanyBottom3FLG = SPFWParameter::getValues('DispCompanyBottom3FLG');
	$DispCompanyBottomTEL3 = SPFWParameter::getValues('DispCompanyBottomTEL3');

	$myKoji->DispCompanyBottomChk1 = $DispCompanyBottomChk1;
	$DispCompanyTopArr = array($DispCompanyTop1,$DispCompanyTop2,$DispCompanyTop3);
	# 配列を文字列に変換
	$myKoji->DispCompanyTop = SPFWTools::encodePluralValue($DispCompanyTopArr); 
	###問合せ先を登録###
	#DB1カラムに登録するため下記形状に変換して登録
	#|管理会社|1:電話番号表示する 0:しない|アイホン|1:電話番号表示する0:しない|その他会社名|1:電話番号表示する0:しない|電話番号|

	$ToiawasewakiDispArr = array();
	
	#管理会社がONの場合
	if($DispCompanyBottomChk1 == '1'){
		$ToiawasewakiDispArr[0] = '管理会社';
	}else{
		$ToiawasewakiDispArr[0] = '';
	}
	$ToiawasewakiDispArr[1] = $DispCompanyBottom1FLG;	#電話番号を出力　1：する　0：しない

	#アイホンさんがONの場合
	if($DispCompanyBottomChk2 == '1'){
		$ToiawasewakiDispArr[2] = 'アイホン';
	}else{
		$ToiawasewakiDispArr[2] = '';
	}
	$ToiawasewakiDispArr[3] = $DispCompanyBottom2FLG;	#電話番号を出力　1：する　0：しない

	#その他がONの場合
	if($DispCompanyBottomChk3 == '1'){
		$ToiawasewakiDispArr[4]= $DispCompanyBottom3;	#その他の会社名
	}else{
		$ToiawasewakiDispArr[4] = '';
	}
	$ToiawasewakiDispArr[5] = $DispCompanyBottom3FLG;	#電話番号を出力　1：する　0：しない
	$ToiawasewakiDispArr[6] = $DispCompanyBottomTEL3;	#その他電話番号
	
	# 配列を文字列に変換
	$myKoji->ToiawasewakiDisp = SPFWTools::encodePluralValue($ToiawasewakiDispArr); 
	$wSagyoinArr = SPFWParameter::getValues('wSagyoin');	#作業員 ベスト/腕章　配列
	$wShiteiVest = SPFWParameter::getValues('wShiteiVest');	#指定 ベスト
	$wShiteiVestOther = SPFWParameter::getValues('ShiteiVestOther');	#指定 ベストその他会社名
	$myKoji->Sagyoin = SPFWTools::encodePluralValue($wSagyoinArr); 
	$myKoji->ShiteiVestCD = $wShiteiVest ;	#指定ベスト
	$myKoji->ShiteiVestOther = $wShiteiVestOther ;	#指定ベストその他


	if (!$myKoji->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "工事情報のアップデートに失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	unset($myKoji);

#	echo $GyosyaTantoCD1;
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "gt.UserCD, ";		#0業者担当CD
	$sql .= "GyosyaName, ";		#0支払方法の指定
	$sql .= "g.G01 ";		#0支払方法の指定
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tUserM gt , tGyosyaM g ";
	$sql .= " WHERE gt.Extra5 = g.GyosyaCD AND gt.MukouFlg = FALSE AND (Extra3= 3 or Extra3= 4) and gt.UserCD = ".$GyosyaTantoCD1; // 546:nespekimura

	$myListObject->Condition = $sql;
	$myListObject->Order = "";#表示順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User List Failed.", E_USER_ERROR);

	$GyosyaTantoCD = $myListObject->GetValue(0, 0);
	$GyosyaName = $myListObject->GetValue(0, 1);
	$ShiharaiSitei = $myListObject->GetValue(0, 2);

	unset($myListObject);

	#ノンタッチシステム導入FlgON
	#オートロックあり 且つ リニューアル後のタグアリ　且つ　リニューアル前のタグなし
	if($wAutoLock == "1" and strstr($wRNNyukan ,'2') and !strstr($wCurrentNyukan ,'2')){
		$nonTouchFLG = true;
	} 


	#######################################################
	# Excelファイル生成#
	########################################################
	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
	$reader = new XlsxReader();


/*	if ($wAutoLock != "1"){	#オートロック無し
		if($wAnnaijyoType == "0"){	#通常版
			$spreadsheet = $reader->load('./template/koji_annai_noAutlock.xlsx'); //template.xlsx 読込
			$fileName_add = "オートロックなし";
		}else{	#簡易版
			$spreadsheet = $reader->load('./template/koji_annai_noAutlock_light.xlsx'); //template.xlsx 読込
			$fileName_add = "オートロックなし_簡易版";
		}
	}else{
		if($wAnnaijyoType == "0"){	#通常版
//			$spreadsheet = $reader->load('./template/koji_annai_full.xlsx'); //template.xlsx 読込
*/			$spreadsheet = $reader->load('./template/s_koji_annai_full.xlsx'); //template.xlsx 読込
			$fileName_add = "通常版";
/*		}else{	#簡易版
			$spreadsheet = $reader->load('./template/koji_annai_light.xlsx'); //template.xlsx 読込
			$fileName_add = "簡易版";
		}
	}
*/
	#シートを指定して各項目をセットしていく
	#1_概要（オートロックあり/無し、通常版/簡易版　共通）
	$sheet = $spreadsheet->getSheetByName('Sheet1');


	$Gaiyougyo = 1;
	
	#◆タイトル部
	$sheet->setCellValue('AN'.$Gaiyougyo, $AnnaiDate2);
	$Gaiyougyo++;#2
	$sheet->setCellValue('A'.$Gaiyougyo, $BukkenName.'にお住まいの皆様へ');

	#管理会社・アイホン問合せ先
	#◆右上に表示する会社名
	$Gaiyougyo++;#3
	$sheet->setCellValue('AN'.$Gaiyougyo, $DispCompanyTop1);
	$Gaiyougyo++;
	$sheet->setCellValue('AN'.$Gaiyougyo, $DispCompanyTop2);
	$Gaiyougyo++;
	$sheet->setCellValue('AN'.$Gaiyougyo, $DispCompanyTop3);
	$Gaiyougyo+=2;

	#if($wAutoLock == "1" and strstr($wRNNyukan ,'2') and !strstr($wCurrentNyukan ,'2') ){#ノンタッチシステム導入あり
	if($nonTouchFLG){
		$addStr ='及びノンタッチシステム導入工事';	#タイトルに文言を追加
		$tmpCell = $sheet->getCell('E'.$Gaiyougyo);	#タイトル ノンタッチシステム導入工事
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("KojiName",$KojiName.PHP_EOL.$addStr,$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);
	}else{
		$addStr = '';
		$tmpCell = $sheet->getCell('E'.$Gaiyougyo);	#タイトル ノンタッチシステム導入工事
		$tmpCellStr = $tmpCell->getValue();	
		$tmpCellStr = str_replace("KojiName",$KojiName,$tmpCellStr);	
		$tmpCell->setValue($tmpCellStr);								
		$spreadsheet->getActiveSheet()->getStyle('E'.$Gaiyougyo)->getFont()->setSize(22);
	}
	$Gaiyougyo++;
	$Gaiyougyo++;
	

	$tmpCell = $sheet->getCell('B'.$Gaiyougyo);	#挨拶文
	$tmpCellStr = $tmpCell->getValue();
	$tmpCellStr = str_replace("KojiName",$KojiName.$addStr,$tmpCellStr);
	$tmpCell->setValue($tmpCellStr);
	$Gaiyougyo+=6;
	#ノンタッチタグ 201903 ADD GOE
	#切替後の1key/2keyで文言を出し分け
	#if( $wAutoLock == "1" and $wTagSuu >= 1) {#オートロックあり　且つ　タグ数1本以上の場合
	if($nonTouchFLG){

		$Gaiyougyo+=4;

		$tmpCell = $sheet->getCell('V'.$Gaiyougyo);
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace("●月●日",date('n月j日',strtotime( $TagKirikaeDate )),$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);
			
		if(strstr ($wRNNyukan ,'0')){	#鍵OK→2key（鍵&タグ）

			$Gaiyougyo+=4;
	
			$sheet->setCellValue('Y'.$Gaiyougyo, "各住戸".$wTagSuu."本");
			$sheet->setCellValue('AK'.$Gaiyougyo, "従");
			$Gaiyougyo++;
			$sheet->setCellValue('C'.$Gaiyougyo, "来の鍵またはノンタッチタグキーでオートロック開錠が可能になります。" );
			$Gaiyougyo++;#27

			for($temp_i = $Gaiyougyo ; $temp_i < ($Gaiyougyo+2) ; $temp_i++){#27
				$sheet->getRowDimension($temp_i)->setVisible(false);#行非表示
			}

		}else{	#1key
			
			$Gaiyougyo+=4;
			$sheet->setCellValue('Y'.$Gaiyougyo, "各住戸".$wTagSuu."本");
			$Gaiyougyo+=2;

		}

	}else{	#オートロックなし　又は　タグ本数0本の場合　対象行を非表示

		for($temp_i = $Gaiyougyo ; $temp_i < ($Gaiyougyo+14) ; $temp_i++){
			$sheet->getRowDimension($temp_i)->setVisible(false);#行非表示
		}
		$Gaiyougyo+=10;
	}
	$Gaiyougyo+=5;

	#◆工事名称、工期
	#全体工期
	$sheet->setCellValue('J'.$Gaiyougyo, $ZentaiStartDate."～".$ZentaiEndDate );
	#共用部工事
	if(substr($KyoyoStartDate,0,4)==substr($KyoyoEndDate,0,4)){#年を跨がない場合は西暦を出さない
		$KyoyoStartDate = substr($KyoyoStartDate,7);
		$KyoyoEndDate = substr($KyoyoEndDate,7);
	}
	$Gaiyougyo++;
	if($KyoyoStartDate != ""){
		if($KyoyoEndDate != ""){	#終了日がある場合 又は開始日と終了日が別日の場合
			$sheet->setCellValue('J'.$Gaiyougyo, $KyoyoStartDate."～".$KyoyoEndDate );
		}else{	#それ以外は開始日のみ出力
			$sheet->setCellValue('J'.$Gaiyougyo, $KyoyoStartDate );
		}
		$Gaiyougyo++;
	}else{
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		$Gaiyougyo++;
		$sheet->setCellValue('C'.$Gaiyougyo, '専有部工事：');	#共用部がない場合ナンバリング②を削除
	}
	
	#専有部工事
	if(substr($SenyuStartDate,0,4)==substr($SenyuEndDate,0,4)){#年を跨がない場合は西暦を出さない
		$SenyuStartDate = substr($SenyuStartDate,7);
		$SenyuEndDate = substr($SenyuEndDate,7);
	}
	if($SenyuEndDate != ""){	#終了日がある場合 又は開始日と終了日が別日の場合
		$sheet->setCellValue('J'.$Gaiyougyo, $SenyuStartDate."～".$SenyuEndDate );
	}else{		#それ以外は開始日のみ出力
		$sheet->setCellValue('J'.$Gaiyougyo, $SenyuStartDate);
	}
	$Gaiyougyo++;
	
	#予備日
//	echo "<br>".__LINE__."行目:".$YobiStartDate ;
	if(substr($YobiStartDate,0,4)==substr($YobiEndDate,0,4)){#年を跨がない場合は西暦を出さない
		$YobiStartDate = substr($YobiStartDate,7);
		$YobiEndDate = substr($YobiEndDate,7);
	}

	if( $YobiStartDate != ""){
		if($YobiEndDate != ""){
			$YobiDate = $YobiStartDate."～".$YobiEndDate ;
		}else{
			$YobiDate = $YobiStartDate ;
		}
		$sheet->setCellValue('J'.$Gaiyougyo, $YobiDate );
	} else{
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		
	}
	$Gaiyougyo++;#29


	if(count($Holiday)>0){
		for($i=0; $i<count($Holiday) ; $i++){
			$j=$i+$Gaiyougyo-5;
			$sheet->setCellValue('J'.$j, $Holiday[$i] );
		}
		$sheet->setCellValue('C'.$Gaiyougyo, "休工日：" );
	}
	for ($i=count($Holiday); $i<5; $i++){	#不要行の削除
		$j=$i+$Gaiyougyo;			
		$sheet->getRowDimension($j)->setVisible(false);
	}


	#作業時間帯
	if( count( $WAKUPATTERN[$WakuPattern]['StartTime'] ) == 2 ){
		$SagyoTime = $WAKUPATTERN[$WakuPattern]['StartTime'][0]."～".$WAKUPATTERN[$WakuPattern]['EndTime'][1];
	}else{
		$SagyoTime = $WAKUPATTERN[$WakuPattern]['StartTime'][0]."～".$WAKUPATTERN[$WakuPattern]['EndTime'][2];
	}
	

	$Gaiyougyo+=4;#33
	
	#作業時間の先頭が0の場合は除去 201902 ADD GOE
	$SagyoTime = ltrim($SagyoTime, '0');

	$sheet->setCellValue('J'.$Gaiyougyo, $SagyoTime);
	$Gaiyougyo+=3;#36

	#◆工事に関する問い合わせ先
	if(!$IfOP){	#オプション無しの場合不要の文言を削除
		$tmpCell = $sheet->getCell('A'.$Gaiyougyo);
		$tmpCellStr = $tmpCell->getValue();
		$tmpCellStr = str_replace('・オプション申込','',$tmpCellStr);
		$tmpCell->setValue($tmpCellStr);
	}
	#受付期間
	//$sheet->setCellValue('I37', $AnnaiDate."～".$ReceptionDate );


	$CompanyBottom = 0;

	$Gaiyougyo+=10;#46

	if($DispCompanyBottomChk1 || $DispCompanyBottomChk2 || $DispCompanyBottomChk3){

		if($CompanyBottom == 3 ){
			$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		}

		#◆下部に表示する会社名連絡先
		#配列で値がうまく取れなかったので別々のフォームに変更した
		if($DispCompanyBottomChk1){#管理会社
			if($DispCompanyBottom1FLG == '1'){
				$sheet->setCellValue('D'.$Gaiyougyo, $KanriGaisya."　電話：".$KanriGaisyaTEL);
			}else{
				$sheet->setCellValue('D'.$Gaiyougyo, $KanriGaisya);
			}
			$Gaiyougyo++;
		}else{
			$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
			$Gaiyougyo++;
			$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
			$CompanyBottom += 1;
		}


		if($DispCompanyBottomChk3){#その他会社
			$sheet->setCellValue('C'.$Gaiyougyo, "◆連絡先会社　");
			$Gaiyougyo ++;
			if($DispCompanyBottom3FLG == '1'){
				$sheet->setCellValue('D'.$Gaiyougyo, $DispCompanyBottom3."　電話：".$DispCompanyBottomTEL3);
			}else{
				$sheet->setCellValue('D'.$Gaiyougyo, $DispCompanyBottom3);
			}
		}else{
			$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
			$Gaiyougyo ++;
			$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		
			$CompanyBottom += 1;
		}
		$Gaiyougyo ++;

		if($DispCompanyBottomChk2){#アイホン
			$sheet->setCellValue('M'.$Gaiyougyo, $KojiShozokuName);	
			$Gaiyougyo++;
			if($DispCompanyBottom2FLG == '1'){
				$sheet->setCellValue('D'.$Gaiyougyo,  "担当：".$KojiTantoName."　電話：".$TantoTEL."（9時～17時30分　土日祝休み）" );
			}else{
				$sheet->setCellValue('D'.$Gaiyougyo, $KojiTantoName);
			}
		}else{
			$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
			$Gaiyougyo++;
			$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
			$CompanyBottom += 1;
		}
	

	}else{
		$Gaiyougyo--;
		$Gaiyougyo--;
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		$Gaiyougyo++;
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		$Gaiyougyo++;
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		$Gaiyougyo++;
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		$Gaiyougyo++;
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		$Gaiyougyo++;
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		$Gaiyougyo++;
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
		$Gaiyougyo++;
		$sheet->getRowDimension($Gaiyougyo)->setVisible(false);
	}
	$Gaiyougyo++;


	######### 2_工事内容 #########


		#行を変数に格納
		$Naiyougyo = 67;
		$sheet->setBreak('A'.($Naiyougyo-10), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

		if($wJikaho == 0 or $wJikaho == 1){	#自火報無し/流用の場合不要の文言を削除
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);	#共用部関連文言すべて
			$Naiyougyo++;#55
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;#56
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
		}else{
			$Naiyougyo++;
			$Naiyougyo++;#56
		}
		$Naiyougyo+=2;

		$rowsForInvisible = array(); //非表示対象の行リスト	空の配列として定義
		$sheet->setCellValue('L'.$Naiyougyo, "「約".$wConstTime."分」");
		
		$Naiyougyo-=9;#54

		if($Kanrisitu == '0'){#管理室親機　無　不要な文言を削除した文章で差し替えておく
			$sheet->setCellValue('D'.$Naiyougyo, "●インターホン制御装置を交換");#58
		}
		if($wKansenKoji == 3){#3:　1:1 ①共用部工事の内容を非表示
			$Naiyougyo-=3;
			for($i=0;$i<11;$i++){
				$rowsForInvisible[] = $Naiyougyo;
				$Naiyougyo++;
			}#61
			$sheet->setCellValue('B'.$Naiyougyo, "");#①共用部工事がないので専有部の②をはずす
			
		}elseif($wKansenKoji == 2){#2:部屋渡り →～の配線工事を実施 文章まるっとなし
			$Naiyougyo++;
			for($i=0;$i<4;$i++){
				$rowsForInvisible[] = $Naiyougyo;
				$Naiyougyo++;
			}#58
			$Naiyougyo+=3;
		}elseif($wKansenKoji == 1 ){#又は1:玄関子機渡り
			$Naiyougyo+=3;
			$rowsForInvisible[] = $Naiyougyo;
			$Naiyougyo++;
			$rowsForInvisible[] = $Naiyougyo;
			$Naiyougyo+=4;

		}elseif($wKansenKoji == 0 ){#0:パイプシャフト渡り
			$Naiyougyo++;
			$rowsForInvisible[] = $Naiyougyo;
			$Naiyougyo++;
			$rowsForInvisible[] = $Naiyougyo;
			$Naiyougyo+=6;
		}else{

			$Naiyougyo+=8;

		}
		$Naiyougyo-=9;

		if($wAutoLock != "1"){#オートロック無しの場合の非表示行を追加	
			$rowsForInvisible[] = $Naiyougyo;#55行目
		}
		#不要行を非表示に
		for($tmp_i=0 ; $tmp_i<count($rowsForInvisible) ; $tmp_i++){
			$sheet->getRowDimension($rowsForInvisible[$tmp_i])->setVisible(false);
		}
		$Naiyougyo+=13;
		if($wOyakiPanel=="1"&&$wKokiPanel=="1"){
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
		}elseif($wOyakiPanel=="0"&&$wKokiPanel=="0"){
			$sheet->setCellValue('D'.$Naiyougyo, "●住戸内インターホン親機と住戸前玄関子機のパネル取付");
		}elseif($wOyakiPanel=="0"&&$wKokiPanel=="1"){
			$sheet->setCellValue('D'.$Naiyougyo, "●住戸内インターホン親機のパネル取付");
		}elseif($wOyakiPanel=="1"&&$wKokiPanel=="0"){
			$sheet->setCellValue('D'.$Naiyougyo, "●住戸前玄関子機のパネル取付");
		}else{
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
		}


		$Naiyougyo+=3;
		#タグの内容
		if( $wTagSuu < 1 or  $wAutoLock != "1") {#タグなし　又は　オートロックなしの場合　文言非表示
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);#行非表示
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);#行非表示
			$Naiyougyo++;
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);#行非表示
		}else{
			$tmpCell = $sheet->getCell('D'.$Naiyougyo); #タグ本数を出力
			$tmpCellStr = $tmpCell->getValue();
			$tmpCellStr = str_replace("▲", $wTagSuu, $tmpCellStr);
			$tmpCell->setValue($tmpCellStr);
			$Naiyougyo++;

			if( $wOwnerTagSuu > 0){	#オーナー渡しある場合
				$tmpCell = $sheet->getCell('E'.$Naiyougyo); #●標準本数-オーナー渡し　▲オーナー渡し
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("●", (intval($wTagSuu) - intval($wOwnerTagSuu)), $tmpCellStr);
				$tmpCellStr = str_replace("▲", $wOwnerTagSuu, $tmpCellStr);
				$tmpCell->setValue($tmpCellStr);
			}else{
				$sheet->getRowDimension($Naiyougyo)->setVisible(false);#行非表示
			}
			$Naiyougyo++;
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);#タグ渡し無しの場合のサイン受領文を非表示
		}

		#工事内容によって表示をコントロールする
		#シート内の図形リストを取得する
		$drawings = $sheet->getDrawingCollection();

		$ryuyoFlg = false; #流用フラグをOFFで初期化 201902 ADD GOE
		
		$Naiyougyo++;
		$Naiyougyo++;

		#火災感知器の文言を非表示に
		if( $wJikaho == 0 ){	
			#不要な画像を削除
			foreach ($drawings as $key=>$drawing){
				if($drawing->getName() ===  '2_工事内容_火災感知器_A'){
					unset($drawings[$key]);
				}elseif($drawing->getName() ===  '2_工事内容_火災感知器_B'){
					unset($drawings[$key]);
				}
			}
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);

		}else{
			if( $wJikaho == 1 ){#既設流用 流用フラグの判定
				$sheet->setCellValue('E'.$Naiyougyo, "・火災感知器の作業：火災感知器の動作試験");
				$ryuyoFlg = true;	#流用フラグをON
				$Naiyougyo++;
				$Naiyougyo++;
				$Naiyougyo++;
			}elseif($wJikaho == 2){
				$Naiyougyo++;
				$Naiyougyo++;
				$Naiyougyo++;
				$sheet->setCellValue('M'.$Naiyougyo, "");#「本工事では～」の文言をけす

			}
			$Naiyougyo-=3;
			if( $wKasaiHeya == 0 ){#火災抵抗器交換部屋立入り無しの場合　部屋立ち入りの文言削除
				$Naiyougyo++;
				$Naiyougyo++;
				$Naiyougyo++;
				$Naiyougyo++;
				$sheet->getRowDimension($Naiyougyo)->setVisible(false);
				$Naiyougyo++;
				$sheet->getRowDimension($Naiyougyo)->setVisible(false);
				$Naiyougyo-=4;
			}
			$Naiyougyo+=4;

		}

		$Naiyougyo++;

		#ガス漏れの文言を非表示に
		if( $wGasKoji == 0 ){	#なしの場合は画像と文言を削除
			#不要な画像を削除
			foreach ($drawings as $key=>$drawing){
				if($drawing->getName() ===  '2_工事内容_ガス漏れ'){
					unset($drawings[$key]);
					break;
				}
			}
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			
		}elseif( $wGasKoji == 2 ){#交換
			$Naiyougyo+=3;
			$sheet->setCellValue('M'.$Naiyougyo, "");#M34の文言をけす
		}elseif( $wGasKoji == 1 ){#流用
			$sheet->setCellValue('E'.$Naiyougyo, "・ガス漏れ検知器の作業：動作試験");
			$ryuyoFlg = true;	#流用フラグをON
			$Naiyougyo+=3;
		}

		$Naiyougyo++;

		#防犯センサーの文言を非表示に
		if( $wBohanKoji == 0 ){
			#不要な画像を削除
			foreach ($drawings as $key=>$drawing){
				if($drawing->getName() ===  '2_工事内容_防犯センサー'){
					unset($drawings[$key]);
					break;
				}
			}
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
		}else{
			$ryuyoFlg = true;	#流用フラグをON

			if( $wBohanKoji == 1 ){	#１F住戸のみの表示を消す
				$sheet->setCellValue('W'.$Naiyougyo, "※1F住戸のみ");
			}elseif($wBohanKoji ==2){
				$sheet->setCellValue('W'.$Naiyougyo, "");
			}elseif($wBohanKoji ==3){
				$sheet->setCellValue('W'.$Naiyougyo, "※設置住戸のみ");
			}
			$Naiyougyo+=3;
			
		}
		$Naiyougyo++;

		#漏水センサーの文言を非表示に　
		if( $wRosuiKoji == 0 ){
			#不要な画像を削除
			foreach ($drawings as $key=>$drawing){
				if($drawing->getName() ===  '2_工事内容_漏水センサー'){
					unset($drawings[$key]);
					break;
				}
			}
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;

		}elseif( $wRosuiKoji == 2 ){#交換
			$Naiyougyo+=3;
			$sheet->setCellValue('M'.$Naiyougyo, "");#M42の文言をけす
			$Naiyougyo++;

		}elseif($wRosuiKoji == 1){#流用
			$sheet->setCellValue('E'.$Naiyougyo, "・漏水センサーの作業：動作試験");
			$ryuyoFlg = true;	#流用フラグをON
			$Naiyougyo+=4;
		}


		if($wJikaho != 0){
			$ryuyoFlg = true;	#流用フラグをON
		}

		if($ryuyoFlg == false){	#流用がない場合は既設配線工事はしません文言を削除
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
			$Naiyougyo++;
			$sheet->getRowDimension($Naiyougyo)->setVisible(false);
		}

/*	#印刷設定
	$sheet->getPageSetup()->setPrintArea('A1:AO44');

	$sheet->getPageSetup()->setFitToWidth(1);
//	$sheet->getPageSetup()->setFitToHeight(0);
*/

		######### 2_工事内容#########




		######### 3_注意事項#########

		$Tyuigyo = 103;
		$sheet->setBreak('A'.($Tyuigyo-1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

		if ($wAutoLock == "1"){####★1 AutoLockありなら####
			if ($wKirikaehoho == '0'){	#切替方法→停止 
				#並行稼働のシートを削除    
				$Tyuigyo += 30;
				for($tmp_i=$Tyuigyo ; $tmp_i<($Tyuigyo+30) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false);
				}
				$Tyuigyo -= 30;
				foreach ($drawings as $key=>$drawing){
					if($drawing->getName() ===  '図 16'){
						unset($drawings[$key]);
					}
				}
				foreach ($drawings as $key=>$drawing){
					if($drawing->getName() ===  '図 17'){
						unset($drawings[$key]);
					}
				}
				foreach ($drawings as $key=>$drawing){
					if($drawing->getName() ===  '図18'){
						unset($drawings[$key]);
					}
				}
				if ($wKansenKoji != "3") { #幹線ルート1:1以外 1:1は3注意事項の出力なし
					$Tyuigyo += 4;
					#使用するシートに値を埋め込み
//					$sheet = $spreadsheet->getSheetByName('3_注意事項');
					$sheet->setCellValue('D'.$Tyuigyo,"共用部工事開始（".$KyoyoStartDate."）よりインターホン設備の下記の機能がご使用いただけなくなります。");
					
					if($Kanrisitu == '0'){#管理室親機　無の場合
						$Tyuigyo++;
						$Tyuigyo++;
						$sheet->getRowDimension($Tyuigyo)->setVisible(false);
						$Tyuigyo++;
						$Tyuigyo++;
						$sheet->getRowDimension($Tyuigyo)->setVisible(false);
					}else{#アリの場合
						$Tyuigyo++;
						$Tyuigyo++;
						$Tyuigyo++;
						$sheet->getRowDimension($Tyuigyo)->setVisible(false);
						$Tyuigyo++;
					
					}
					$Tyuigyo += 8;
					if ($wTagKoji == "1") {#従前入館方法にタグの指定があれば文言修正
						$sheet->setCellValue('D'.$Tyuigyo,"居住者様の入館につきましては、従来通り鍵・タグでのオートロック解錠・入館が可能です。お出かけの際は、必ず鍵をお持ちいただきますようお願い申し上げます。");
					}

					if ($wKansenKoji == "2") { #幹線ルート部屋渡りの場合

						#シート内の図形リストを取得する
						$drawings = $sheet->getDrawingCollection();

						#不要な画像を削除
						foreach ($drawings as $key=>$drawing){
							if($drawing->getName() === '図 24' ){
								unset($drawings[$key]);
							}
							if($drawing->getName() === '図 6' ){
								unset($drawings[$key]);
							}
							if($drawing->getName() === '図 30' ){
								unset($drawings[$key]);
							}
						}
						foreach ($drawings as $key=>$drawing){
							if($drawing->getName() === '丸右矢印2' ){
								unset($drawings[$key]);
							}
						}
						foreach ($drawings as $key=>$drawing){
							if($drawing->getName() === '図 49' ){
								unset($drawings[$key]);
							}
						}
						$Tyuigyo += 3;
						$sheet->unmergeCells('E'.$Tyuigyo.':AM'.($Tyuigyo+1));
						$sheet->getStyle('C'.$Tyuigyo)->getFont()->setSize(14);
						$sheet->mergeCells('C'.$Tyuigyo.':G'.$Tyuigyo);
						$sheet->mergeCells('H'.$Tyuigyo.':AM'.$Tyuigyo);
						$sheet->setCellValue('C'.$Tyuigyo,"注意事項");
						$sheet->setCellValue('H'.$Tyuigyo,"全世帯の工事が完了するまでエントランス集合玄関機からの『呼出・通話・");
						$Tyuigyo ++;
						$sheet->mergeCells('H'.$Tyuigyo.':AM'.$Tyuigyo);
						$sheet->setCellValue('H'.$Tyuigyo,"解錠』が出来ません。（工事完了していないお部屋が1部屋でもありますと、");
						$Tyuigyo ++;
						$sheet->mergeCells('H'.$Tyuigyo.':AM'.$Tyuigyo);
						$sheet->setCellValue('H'.$Tyuigyo,"マンション全体でインターホンが使用できません）必ず工事をしていただきま");
						$Tyuigyo ++;
						$sheet->mergeCells('H'.$Tyuigyo.':AM'.$Tyuigyo);
						$sheet->setCellValue('H'.$Tyuigyo,"すよう、お願いいたします。");
						#不要な行を非表示

						$Tyuigyo ++;
						for($tmp_i=$Tyuigyo ; $tmp_i<($Tyuigyo+7) ; $tmp_i++){
							$sheet->getRowDimension($tmp_i)->setVisible(false);
						}
					
					}
					
				} else { #幹線ルート1:1
					#3注意事項のシートは出力不要
					for($tmp_i=$Tyuigyo ; $tmp_i<($Tyuigyo+60); $tmp_i++){
						$sheet->getRowDimension($tmp_i)->setVisible(false);
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 16'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 17'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図18'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '3_注意事項_注意事項２イラスト'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '旧親機'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '丸右矢印'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '丸右矢印2'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 14'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 5'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 24'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 49'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 6'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 9'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 30'){
							unset($drawings[$key]);
						}
					}
				}

			}else{	#並行稼働の場合
				#3_注意事項_並行稼働を出力　ほかは非表示

					for($tmp_i=$Tyuigyo ; $tmp_i<($Tyuigyo+30) ; $tmp_i++){
						$sheet->getRowDimension($tmp_i)->setVisible(false);
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '3_注意事項_注意事項２イラスト'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '旧親機'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '丸右矢印'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '丸右矢印2'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 14'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 5'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 24'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 49'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 6'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 9'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 30'){
							unset($drawings[$key]);
						}
					}
				if($wKirikaeHeikoEizoriyo != '1'){
					#不要な行を非表示
/*					$rowsForInvisible = [
					for($tmp_i=0 ; $tmp_i<count($rowsForInvisible) ; $tmp_i++){
						$sheet->getRowDimension($rowsForInvisible[$tmp_i])->setVisible(false);
					}
*/				}
			}
		}else{#★1    autolockなしなら
			#使用するシートに値を埋め込み
			if($wKansenKoji != "3"){

					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '3_注意事項_注意事項２イラスト'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '旧親機'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '丸右矢印'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '丸右矢印2'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 14'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 5'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 24'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 49'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 6'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 9'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 30'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 16'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 17'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図18'){
							unset($drawings[$key]);
						}
					}

				for($tmp_i=$Tyuigyo ; $tmp_i<($Tyuigyo+30) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false);
				}
				$sheet->getStyle('B'.$Tyuigyo.':AM'.($Tyuigyo+20))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				$Tyuigyo += 32;
				for($tmp_i=$Tyuigyo ; $tmp_i<($Tyuigyo+20) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setRowHeight(21.6);
				}
				$sheet->getStyle('A'.$Tyuigyo.':AM'.($Tyuigyo+20))->getFont()->setSize(14);
				$sheet->setCellValue('B'.$Tyuigyo,"工事期間中のインターホンの使用について");
				$Tyuigyo ++;
				$Tyuigyo ++;
				$sheet->unmergeCells('C'.$Tyuigyo.':AM'.($Tyuigyo+2));
				$Tyuigyo --;
				$sheet->mergeCells('C'.$Tyuigyo.':I'.$Tyuigyo);
				$sheet->mergeCells('J'.$Tyuigyo.':AD'.$Tyuigyo);
				$sheet->mergeCells('AE'.$Tyuigyo.':AM'.$Tyuigyo);
				$sheet->getStyle('J'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('J'.$Tyuigyo)->getFont()->setBold(true);
				$sheet->getStyle('J'.$Tyuigyo)
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
				$sheet->setCellValue('J'.$Tyuigyo,"設備の");
				$sheet->setCellValue('B'.$Tyuigyo,"");
				$sheet->setCellValue('C'.$Tyuigyo,"注意事項（1）");
				$sheet->setCellValue('J'.$Tyuigyo,"共用部工事開始（".$KyoyoStartDate."）");
				$sheet->setCellValue('AE'.$Tyuigyo,"より、インターホ");



				$Tyuigyo ++;
				$sheet->getStyle('O'.$Tyuigyo)
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
				$sheet->setCellValue('J'.$Tyuigyo,"設備の");
				$sheet->setCellValue('C'.$Tyuigyo,"");
				$sheet->getStyle('O'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('O'.$Tyuigyo)->getFont()->setBold(true);
				$sheet->getStyle('O'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->mergeCells('J'.$Tyuigyo.':N'.$Tyuigyo);
				$sheet->mergeCells('O'.$Tyuigyo.':AM'.$Tyuigyo);
				$sheet->setCellValue('J'.$Tyuigyo,"ン設備の");
				$sheet->setCellValue('O'.$Tyuigyo,"一部の機能がご使用いただけなくなります。");
				$Tyuigyo ++;
				$sheet->getStyle('D'.$Tyuigyo.':I'.($Tyuigyo+2))->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->getStyle('J'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('J'.$Tyuigyo)->getFont()->setBold(true);
				$sheet->getStyle('AE'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('AE'.$Tyuigyo)->getFont()->setBold(true);
				$sheet->mergeCells('D'.$Tyuigyo.':I'.$Tyuigyo);
				$sheet->mergeCells('J'.$Tyuigyo.':M'.$Tyuigyo);
				$sheet->mergeCells('N'.$Tyuigyo.':AD'.$Tyuigyo);
				$sheet->mergeCells('AE'.$Tyuigyo.':AJ'.$Tyuigyo);
				$sheet->mergeCells('AK'.$Tyuigyo.':AM'.$Tyuigyo);
				$sheet->setCellValue('D'.$Tyuigyo,"【機能】●");
				$sheet->setCellValue('J'.$Tyuigyo,"管理室");
				$sheet->setCellValue('N'.$Tyuigyo,"から住戸内インターホン親機への");
				$sheet->setCellValue('AE'.$Tyuigyo,"呼出・通話");
				$sheet->setCellValue('AK'.$Tyuigyo,"機能");
				if($Kanrisitu == '0')
					$sheet->getRowDimension($Tyuigyo)->setVisible(false);
				$Tyuigyo ++;
				$sheet->getStyle('J'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('J'.$Tyuigyo)->getFont()->setBold(true);
				$sheet->getStyle('Z'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('AG'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->mergeCells('D'.$Tyuigyo.':I'.$Tyuigyo);
				$sheet->mergeCells('J'.$Tyuigyo.':V'.$Tyuigyo);
				$sheet->mergeCells('W'.$Tyuigyo.':Y'.$Tyuigyo);
				$sheet->mergeCells('Z'.$Tyuigyo.':AC'.$Tyuigyo);
				$sheet->mergeCells('AD'.$Tyuigyo.':AF'.$Tyuigyo);
				$sheet->mergeCells('AG'.$Tyuigyo.':AI'.$Tyuigyo);
				$sheet->mergeCells('AJ'.$Tyuigyo.':AM'.$Tyuigyo);
				$sheet->setCellValue('D'.$Tyuigyo,"【機能】●");
				$sheet->setCellValue('J'.$Tyuigyo,"住戸内インターホン親機");
				$sheet->setCellValue('W'.$Tyuigyo,"から");
				$sheet->setCellValue('Z'.$Tyuigyo,"管理室");
				$sheet->setCellValue('AD'.$Tyuigyo,"への");
				$sheet->setCellValue('AG'.$Tyuigyo,"呼出");
				$sheet->setCellValue('AJ'.$Tyuigyo,"機能");
				if($Kanrisitu == '0')
					$sheet->getRowDimension($Tyuigyo)->setVisible(false);

				$Tyuigyo ++;
				$sheet->mergeCells('J'.$Tyuigyo.':U'.$Tyuigyo);
				$sheet->setCellValue('I'.$Tyuigyo,"●");
				$sheet->setCellValue('J'.$Tyuigyo,"インターホンと連動した");
				$sheet->setCellValue('V'.$Tyuigyo,"警報通報");
				$sheet->setCellValue('AA'.$Tyuigyo,"機能");
				$sheet->getStyle('J'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('V'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('V'.$Tyuigyo)->getFont()->setBold(true);
		

				$Tyuigyo ++;
				$sheet->getStyle('AA'.$Tyuigyo)->getFont()->setUnderline(true);
				$sheet->getStyle('AA'.$Tyuigyo)->getFont()->setBold(true);
				$sheet->getStyle('D'.$Tyuigyo)->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->unmergeCells('D'.$Tyuigyo.':V'.($Tyuigyo+1));
				$sheet->mergeCells('E'.$Tyuigyo.':Z'.$Tyuigyo);
				$sheet->mergeCells('AA'.$Tyuigyo.':AF'.$Tyuigyo);
				$sheet->mergeCells('AG'.$Tyuigyo.':AM'.$Tyuigyo);
				$sheet->setCellValue('C'.$Tyuigyo,"");
				$sheet->setCellValue('D'.$Tyuigyo,"・");
				$sheet->setCellValue('E'.$Tyuigyo,"共用部工事期間中も、住戸前玄関子機からの");
				$sheet->setCellValue('AA'.$Tyuigyo,"呼出・通話");
				$sheet->setCellValue('AG'.$Tyuigyo,"機能はご使");
				$Tyuigyo ++;
				$sheet->mergeCells('E'.$Tyuigyo.':AM'.$Tyuigyo);
				$sheet->setCellValue('E'.$Tyuigyo,"用いただけます。");
				$Tyuigyo ++;
				$sheet->unmergeCells('D'.$Tyuigyo.':V'.($Tyuigyo+1));
				$sheet->mergeCells('C'.$Tyuigyo.':I'.$Tyuigyo);
				$sheet->mergeCells('J'.$Tyuigyo.':W'.$Tyuigyo);
				$sheet->mergeCells('X'.$Tyuigyo.':AM'.$Tyuigyo);
				$sheet->setCellValue('C'.$Tyuigyo,"注意事項（2）");
				$sheet->setCellValue('J'.$Tyuigyo,"専有部工事が完了したお部屋");
				$sheet->setCellValue('X'.$Tyuigyo,"から、新しいインターホンで");
				$Tyuigyo ++;
				$sheet->mergeCells('J'.$Tyuigyo.':AM'.$Tyuigyo);
				$sheet->setCellValue('J'.$Tyuigyo,"警報通報機能などがご使用いただけます。");
				$Tyuigyo ++;
				$sheet->getStyle('C'.$Tyuigyo)->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->getStyle('D'.$Tyuigyo)->getAlignment()
				  ->setWrapText('true');
				$sheet->mergeCells('D'.$Tyuigyo.':AM'.($Tyuigyo+2));
				$sheet->getStyle('C'.$Tyuigyo.':AM'.($Tyuigyo+2))
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
				$sheet->setCellValue('J'.$Tyuigyo,"設備の");
				$sheet->setCellValue('C'.$Tyuigyo,"※");
				$sheet->setCellValue('D'.$Tyuigyo,"工事期間中に改修工事ができなかったお部屋に関してはインターホンの機能がご使用いただけなくなりますので、必ず工事期間中の工事実施をお願いします。");
				$Tyuigyo +=2;
				$sheet->setCellValue('B'.$Tyuigyo,"");
				$Tyuigyo ++;
				for($tmp_i=$Tyuigyo ; $tmp_i<($Tyuigyo+15) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false);
				}
				if($Kanrisitu == '0'){#管理室親機　無の場合
	//				$sheet->getRowDimension('10')->setVisible(false);
	//				$sheet->getRowDimension('11')->setVisible(false);
				}else{
	//				$sheet->setCellValue('C12',"");#管理室がある場合不要なタイトルを削除
				}

			} else { #幹線ルート1:1
						#3注意事項のシートは出力不要
					for($tmp_i=$Tyuigyo ; $tmp_i<($Tyuigyo+60); $tmp_i++){
						$sheet->getRowDimension($tmp_i)->setVisible(false);
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 16'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 17'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図18'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '3_注意事項_注意事項２イラスト'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '旧親機'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '丸右矢印'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '丸右矢印2'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 14'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 5'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 24'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 49'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 6'){
							unset($drawings[$key]);
						}
					}
					foreach ($drawings as $key=>$drawing){
						if($drawing->getName() ===  '図 9'){
							unset($drawings[$key]);
						}
						if($drawing->getName() ===  '図 30'){
							unset($drawings[$key]);
						}
					}
				}
		}#★1

		$tmpSheetName = "";
		$Tyuigyo ++;


		$AnshoNogyo = 163;
		if($wKansenKoji != "3"){
			$sheet->setBreak('A'.($AnshoNogyo-1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
		}
		#4_仮暗証番号操作方法
		if ($wAutoLock == "1"){#★1 AutoLockありなら
			$rowsForInvisible = array(); //非表示対象の行リスト	いったん空に初期化
			#シート内の画像を取得する
			$drawings = $sheet->getDrawingCollection();
			
			#切替方法→停止 かつ 暗証番号あり 且つ 幹線ルート1:1 以外
			if ($wKirikaehoho == '0' and $AnshoNo == '0' and $wKansenKoji != "3"){

				$AnshoNogyo++;

				#呼出ボタン→[1]→[1]→[1]→[1]
				$a1 = substr($AnshoNoKojichu,0,1);
				$a2 = substr($AnshoNoKojichu,1,1);
				$a3 = substr($AnshoNoKojichu,2,1);
				$a4 = substr($AnshoNoKojichu,3,1);
				$AnshoNoKojichu = "[呼出ボタン]→[".$a1."]→[".$a2."]→[".$a3."]→[".$a4."]";

				$sheet->setCellValue('O'.$AnshoNogyo, $AnshoNoKojichu);

				$AnshoNogyo += 2;
				if($KyoyoStartDate)
					$sheet->setCellValue('G'.$AnshoNogyo, $KyoyoStartDate."～".$SenyuEndDate ."工事完了まで");
				else
					$sheet->setCellValue('G'.$AnshoNogyo, $SenyuStartDate."～".$SenyuEndDate ."工事完了まで");


				$AnshoNogyo -= 3;
				

			}else{#切替方法並行稼働 or 暗証番号なしの場合
				#仮暗証番号操作方法を削除
				#画像を削除
				foreach ($drawings as $key=>$drawing){
					if($drawing->getName() ===  '4_仮暗証番号操作方法_仮暗証番号イラスト'){
						unset($drawings[$key]);
					}elseif($drawing->getName() ===  '4_仮暗証番号操作方法_直線コネクタ'){
						unset($drawings[$key]);
					}
				}
				
				#不要な行を削除
				for($tmp_i=$AnshoNogyo ; $tmp_i<($AnshoNogyo+6) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false);
				}
			}
			$AnshoNogyo += 14;
			if($wOyakiPanel!=="0"){
				$sheet->setCellValue('B'.$AnshoNogyo, '●');
				$sheet->setCellValue('C'.$AnshoNogyo, '住戸内インターホン親機の設置位置は既存と同位置となります。');
				$AnshoNogyo += 1;
				$sheet->getRowDimension($AnshoNogyo)->setVisible(false);#行非表示
				$AnshoNogyo--;
			}
			if($wKokiPanel!=="0"){ 
				$AnshoNogyo += 2;
				$sheet->getRowDimension($AnshoNogyo)->setVisible(false);#行非表示
				$AnshoNogyo -= 2;
			}
			if(!$IfOP){	#オプション無し
				$AnshoNogyo += 4;
				$sheet->getRowDimension($AnshoNogyo)->setVisible(false);#行非表示
				$AnshoNogyo -= 4;
			}
/*			if($wTakuhai=="0"){	#宅配連動　なし
				$sheet->getRowDimension('85')->setVisible(false);#行非表示
				$sheet->getRowDimension('86')->setVisible(false);#行非表示
				$sheet->getRowDimension('87')->setVisible(false);#行非表示
			}
*/
		}else{# オートロック無しの場合　シートを選択しておく
				foreach ($drawings as $key=>$drawing){
					if($drawing->getName() ===  '4_仮暗証番号操作方法_仮暗証番号イラスト'){
						unset($drawings[$key]);
					}elseif($drawing->getName() ===  '4_仮暗証番号操作方法_直線コネクタ'){
						unset($drawings[$key]);
					}
				}
				
				#不要な行を削除
				for($tmp_i=$AnshoNogyo ; $tmp_i<($AnshoNogyo+6) ; $tmp_i++){
					$sheet->getRowDimension($tmp_i)->setVisible(false);
				}
		}#★１autolockあり
/*	
		#工事の際のお知らせ 施工会社によって表示を決定 201902 ADD GOE
		#☆★施工主体に入る値によっては、条件を部分一致に変更する必要あり☆★
		$rowsForInvisible = array(); //非表示対象の行リスト	空の配列として定義

		#ベスト腕章表示処理

			$tmpActiveCell = '';

		if(count($wSagyoinArr) == 1){	#ベストor腕章
			$tmpSagyoin = $wSagyoinArr[0];
		}else{
			$tmpSagyoin = $wSagyoinArr[0].'又は'.$wSagyoinArr[1];
		}

		#シート内の画像を取得する
		$drawings = $sheet->getDrawingCollection();
		#フラグをfalseで初期化
		$flg =false;
#		if ( $SekoShutai != '株式会社アセットライフ'){	//該当しない場合は画像と行を非表示に設定
#		if ( strpos(   $SekoShutai,'アセットライフ' ) === FALSE ){	//該当しない場合は画像と行を非表示に設定

		
		
		#ベスト腕章表示処理END



*/
		$AnshoNogyo++;



		$Optiongyo = 215;
		$sheet->setBreak('A'.($Optiongyo-33), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
		$sheet->setBreak('A'.($Optiongyo-1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

		#6_オプション機器のご案内 ★★
//		$sheet = $spreadsheet->getSheetByName('6_オプション機器のご案内');
		if(!$IfOP){
			for($i=$Optiongyo ;$i<($Optiongyo+75) ;$i++){
				$sheet->getRowDimension($i)->setVisible(false);

			}
		}else{
			$rowsForInvisible = array(); //非表示対象の行リスト	空の配列として定義
			$Optiongyo+=31;
			if( $wOPUketuke == "1"){ // アンケート
				$sheet->setCellValue('K'.$Optiongyo,"アンケート用紙を1階アンケート回収ボックスへ投函ください。");
				$rowsForInvisibleS = 0;
				$rowsForInvisibleE = 0;

			}elseif($wOPUketuke == "2"){#アンケート用紙ではなく、フリーダイヤル&WEB受付
				$sheet->setCellValue('K'.$Optiongyo,"予約受付センター（0120-489-501）にお電話、またはWebポータルサイト（別紙）でご注文ください。");
				$Optiongyo+=23;
				$rowsForInvisibleS = $Optiongyo;
				$Optiongyo+=21;
				$rowsForInvisibleE = $Optiongyo;
				$Optiongyo-=44;

			}else{#日程変更と同じフリーダイヤル　デフォルト
				$sheet->setCellValue('K'.$Optiongyo,"予約受付センター（0120-489-501）にお電話でご注文ください。");
				$Optiongyo+=23;
				$rowsForInvisibleS = $Optiongyo;
				$Optiongyo+=21;
				$rowsForInvisibleE = $Optiongyo;
				$Optiongyo-=44;

			}
			
			for($tmp_i=$rowsForInvisibleS ; $tmp_i<$rowsForInvisibleE ; $tmp_i++){
				$sheet->getRowDimension($tmp_i)->setVisible(false);
			}

			$Optiongyo+=5;
			$sheet->setCellValue('K'.$Optiongyo,$OpEndDate);
			$Optiongyo+=2;
			$sheet->setCellValue('K'.$Optiongyo,$GyosyaName);

			$wShiharai_str = "";

			if (in_array("現金",$wShiharai)) {
				$wShiharai_str .= "専有部工事当日の現金払い（事前にご用意ください）";
				$Optiongyo+=4;
				$sheet->setCellValue('F'.$Optiongyo,'協力会社（'.$GyosyaName.')名義にて領収書を発行いたします。');
				$Optiongyo-=4;
				if (in_array("振込",$wShiharai)) {
					$wShiharai_str .= ",銀行振込";#テンプレに移動よてい

					if (in_array("NP",$wShiharai)) {
						$wShiharai_str .= ",NP後払い";#テンプレに移動よてい
						#$sheet->setCellValue('E18',$wShiharai_str);
						if (in_array("コンビニ",$wShiharai)) {
							$wShiharai_str .= ",コンビニ振込票支払い";
							if ($wShiharaiConveni == 1){	#上限有の場合
								$wShiharai_str .= '又は銀行振込';
							}else{#無しの場合
								$Optiongyo+=10;
								$sheet->getRowDimension($Optiongyo)->setVisible(false);
								$Optiongyo-=10;
							}
						}else{
							$Optiongyo+=7;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo-=10;
						}
					}else{

						$Optiongyo+=6;
						$sheet->getRowDimension($Optiongyo)->setVisible(false);
						$Optiongyo-=6;

						if (in_array("コンビニ",$wShiharai)) {
							$wShiharai_str .= ",コンビニ振込票支払い";
							if ($wShiharaiConveni == 1){	#上限有の場合
								$wShiharai_str .= '又は銀行振込';
							}else{#無しの場合
								$Optiongyo+=10;
								$sheet->getRowDimension($Optiongyo)->setVisible(false);
								$Optiongyo-=10;
							}
						}else{
							$Optiongyo+=7;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo-=10;
						
						}
					}
				}else{
					$Optiongyo+=5;
					$sheet->getRowDimension($Optiongyo)->setVisible(false);
					$Optiongyo-=5;

					if (in_array("NP",$wShiharai)) {
						$wShiharai_str .= "NP後払い";#テンプレに移動よてい
						#$sheet->setCellValue('E18',$wShiharai_str);

						if (in_array("コンビニ",$wShiharai)) {
							$wShiharai_str .= ",コンビニ振込票支払い";
							if ($wShiharaiConveni == 1){	#上限有の場合
								$wShiharai_str .= '又は銀行振込';
							}else{#無しの場合
								$Optiongyo+=10;
								$sheet->getRowDimension($Optiongyo)->setVisible(false);
								$Optiongyo-=10;
							}
						}else{
							$Optiongyo+=7;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo-=10;
						
						}
					}else{

						$Optiongyo+=6;
						$sheet->getRowDimension($Optiongyo)->setVisible(false);
						$Optiongyo-=6;
						if (in_array("コンビニ",$wShiharai)) {
							$wShiharai_str .= ",コンビニ振込票支払い";
							if ($wShiharaiConveni == 1){	#上限有の場合
								$wShiharai_str .= '又は銀行振込';
							}else{#無しの場合
								$Optiongyo+=10;
								$sheet->getRowDimension($Optiongyo)->setVisible(false);
								$Optiongyo-=10;
							}
						}else{
							$Optiongyo+=7;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo-=10;
						
						}
					}
				}
			} else {

					$Optiongyo+=4;
					$sheet->getRowDimension($Optiongyo)->setVisible(false);
					$Optiongyo-=4;

				if (in_array("振込",$wShiharai)) {
					$wShiharai_str .= "銀行振込";#テンプレに移動よてい

					if (in_array("NP",$wShiharai)) {
						$wShiharai_str .= ",NP後払い";#テンプレに移動よてい
						#$sheet->setCellValue('E18',$wShiharai_str);

						if (in_array("コンビニ",$wShiharai)) {
							$wShiharai_str .= "コンビニ振込票支払い";
							if ($wShiharaiConveni == 1){	#上限有の場合
								$wShiharai_str .= '又は銀行振込';
							}else{#無しの場合
								$Optiongyo+=10;
								$sheet->getRowDimension($Optiongyo)->setVisible(false);
								$Optiongyo-=10;
							}
						}else{
							$Optiongyo+=7;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo-=10;
						
						}
					}else{
						$Optiongyo+=6;
						$sheet->getRowDimension($Optiongyo)->setVisible(false);
						$Optiongyo-=6;
						if (in_array("コンビニ",$wShiharai)) {
							$wShiharai_str .= ",コンビニ振込票支払い";
							if ($wShiharaiConveni == 1){	#上限有の場合
								$wShiharai_str .= '又は銀行振込';
							}else{#無しの場合
								$Optiongyo+=10;
								$sheet->getRowDimension($Optiongyo)->setVisible(false);
								$Optiongyo-=10;
							}
						}else{
							$Optiongyo+=7;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo-=10;
						
						}
					}
				}else{
					$Optiongyo+=5;
					$sheet->getRowDimension($Optiongyo)->setVisible(false);
					$Optiongyo-=5;
					if (in_array("NP",$wShiharai)) {
						$wShiharai_str .= ",NP後払い";#テンプレに移動よてい
						#$sheet->setCellValue('E18',$wShiharai_str);

						if (in_array("コンビニ",$wShiharai)) {
							$wShiharai_str .= ",コンビニ振込票支払い";
							if ($wShiharaiConveni == 1){	#上限有の場合
								$wShiharai_str .= '又は銀行振込';
							}else{#無しの場合
								$Optiongyo+=10;
								$sheet->getRowDimension($Optiongyo)->setVisible(false);
								$Optiongyo-=10;
							}
						}else{
							$Optiongyo+=7;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo-=10;
						
						}
					}else{
						$Optiongyo+=6;
						$sheet->getRowDimension($Optiongyo)->setVisible(false);
						$Optiongyo-=6;
						if (in_array("コンビニ",$wShiharai)) {
							$wShiharai_str .= "コンビニ振込票支払い";
							if ($wShiharaiConveni == 1){	#上限有の場合
								$wShiharai_str .= '又は銀行振込';
							}else{#無しの場合
								$Optiongyo+=10;
								$sheet->getRowDimension($Optiongyo)->setVisible(false);
								$Optiongyo-=10;
							}
						}else{
							$Optiongyo+=7;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo++;
							$sheet->getRowDimension($Optiongyo)->setVisible(false);
							$Optiongyo-=10;
						
						}
					}
				}
			}
			$Optiongyo+=2;
			$sheet->setCellValue('K'.$Optiongyo,$wShiharai_str);
			$Optiongyo-=37;
			$gyoDEF = $Optiongyo;	#機器のご案内　先頭行を定義
			$Optiongyo+=53;
			$gyoAnqDEF = $Optiongyo;	#アンケート先頭行を定義

			$gyo = $gyoDEF;
			$gyoAnq = $gyoAnqDEF;	
			$No = 0;
			$cell[1] = "B";
			$cell[2] = "O";
			$cell[3] = "AB";
			$OPSuu=0;
			for($i=0;$i<count($OPInfo);$i++){
				if( $OPInfo[$i]["OPUseKbn"] != "")
					$OPSuu+=1;
			
			}
				$Optiongyo -= 56;

			if($OPSuu==0){
				for($i=$Optiongyo ;$i<($Optiongyo+61) ;$i++){
					$sheet->getRowDimension($i)->setVisible(false);

				}

			}elseif($OPSuu==1){
				$Optiongyo++;
				$Optiongyo++;
				$Optiongyo++;
				
				$sheet->mergeCells('B'.$Optiongyo.':AM'.($Optiongyo+1));##bbdcfc
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$Optiongyo++;
				$Optiongyo++;
				$sheet->mergeCells('B'.$Optiongyo.':AM'.($Optiongyo+5));
				$Optiongyo--;
				$Optiongyo--;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Optiongyo++;
				$Optiongyo++;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Optiongyo+=6;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+4))->getFont()->setSize(9);#フォントサイズをセット

				$sheet->mergeCells('B'.$Optiongyo.':AM'.($Optiongyo+2));
				$Optiongyo+=3;
				$sheet->mergeCells('B'.$Optiongyo.':AM'.($Optiongyo+1));
				
				$Optiongyo-=11;
				echo $Optiongyo;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+1))->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('B'.($Optiongyo+8).':AM'.($Optiongyo+5))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				$sheet->getStyle('B'.$Optiongyo)->getFont()->setSize(14);#フォントサイズをセット
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+1))->getFont()->setBold(true);#太字はsetBold(true)
				$Optiongyo+=8;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+5))->getFont()->setSize(12);#フォントサイズをセット
				$Optiongyo+=3;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+1))->getFont()->setBold(true);#太字はsetBold(true)
				$Optiongyo--;

			}elseif($OPSuu==2){

				$Optiongyo++;
				$Optiongyo++;
				$Optiongyo++;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+1));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo++;
				$Optiongyo++;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+5));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+5));
				$Optiongyo+=6;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+2));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+2));
				$Optiongyo+=3;

				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+1));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+1));

				$Optiongyo-=9;
				$sheet->getStyle('B'.$Optiongyo.':T'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('U'.$Optiongyo.':AM'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Optiongyo+=6;
				$sheet->getStyle('B'.$Optiongyo.':T'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('U'.$Optiongyo.':AM'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

				$Optiongyo-=8;
				$sheet->getStyle('B'.$Optiongyo.':T'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
				$sheet->getStyle('U'.$Optiongyo.':AM'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);


				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getFont()->setSize(9);#フォントサイズをセット

				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->setCellValue('B'.$Optiongyo,"オプション①\n");
				$sheet->setCellValue('U'.$Optiongyo,"オプション②\n");

				$Optiongyo+=8;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+1))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				$cell[1] = "B";
				$cell[2] = "U";

				$Optiongyo+=3;
				$sheet->getStyle('B'.$Optiongyo)->getFont()->setSize(11);#フォントサイズをセット
				$sheet->getStyle('U'.$Optiongyo)->getFont()->setSize(11);#フォントサイズをセット
				$sheet->getStyle('B'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('U'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)

			}elseif($OPSuu==3){

				$Optiongyo++;
				$Optiongyo++;
				$Optiongyo++;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+1));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+1));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo+=2;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+5));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+5));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+5));
				$Optiongyo+=6;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+2));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+2));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+2));
				$Optiongyo+=3;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+1));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+1));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo-=11;

				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=2;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=6;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo-=8;

				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getFont()->setSize(9);#フォントサイズをセット
				$sheet->setCellValue('B'.$Optiongyo,"オプション①\n");
				$sheet->setCellValue('O'.$Optiongyo,"オプション②\n");
				$sheet->setCellValue('AB'.$Optiongyo,"オプション③\n");
				$cell[1] = "B";
				$cell[2] = "O";
				$cell[3] = "AB";
				$cell_f[1] = "M";
				$cell_f[2] = "Z";
				$cell_f[3] = "AM";
				$Optiongyo+=11;

				$sheet->getStyle('B'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('O'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('AB'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('B'.$Optiongyo.':AM'.$Optiongyo)->getFont()->setSize(10);#フォントサイズをセット

			}elseif($OPSuu==4){

				$Optiongyo++;
				$Optiongyo++;
				$Optiongyo++;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+26))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+1));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+1));
				$sheet->setCellValue('B'.$Optiongyo,"オプション①\n");
				$sheet->setCellValue('U'.$Optiongyo,"オプション②\n");

				$Optiongyo+=2;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+5));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+5));

				$Optiongyo+=6;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+2));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+2));

				$Optiongyo+=3;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+1));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+1));

				$Optiongyo+=2;
				$sheet->getRowDimension($Optiongyo)->setVisible(false);

				$Optiongyo++;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+1));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+1));

				$Optiongyo+=2;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+5));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+5));

				$Optiongyo+=6;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+2));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+2));

				$Optiongyo+=3;
				$sheet->mergeCells('B'.$Optiongyo.':T'.($Optiongyo+1));
				$sheet->mergeCells('U'.$Optiongyo.':AM'.($Optiongyo+1));

				$Optiongyo-=25;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+26))->getBorders()
				    ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('B'.$Optiongyo.':T'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
				$sheet->getStyle('U'.$Optiongyo.':AM'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
				
				$Optiongyo+=8;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+5))->getFont()->setSize(9);#フォントサイズをセット

				$Optiongyo+=6;
				$sheet->setCellValue('B'.$Optiongyo,"オプション③\n");
				$sheet->setCellValue('U'.$Optiongyo,"オプション④\n");
				$sheet->getStyle('B'.$Optiongyo.':T'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
				$sheet->getStyle('U'.$Optiongyo.':AM'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

				$Optiongyo+=2;

				$Optiongyo+=6;
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getFont()->setSize(9);#フォントサイズをセット
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

				$Optiongyo+=3;
				$Optiongyo+=3;

				$cell[1] = "B";
				$cell[2] = "U";
				$cell[3] = "B";
				$cell[4] = "U";

			}elseif($OPSuu==5){

				$Optiongyo++;
				$Optiongyo++;
				$Optiongyo++;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+1));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+1));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo+=2;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+5));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+5));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+5));
				$Optiongyo+=6;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+2));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+2));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+2));
				$Optiongyo+=3;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+1));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+1));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo-=11;

				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=2;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=6;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo-=8;

				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getFont()->setSize(9);#フォントサイズをセット
				$sheet->setCellValue('B'.$Optiongyo,"オプション①\n");
				$sheet->setCellValue('O'.$Optiongyo,"オプション②\n");
				$sheet->setCellValue('AB'.$Optiongyo,"オプション③\n");
				$Optiongyo+=11;

				$sheet->getStyle('B'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('O'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('AB'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('B'.$Optiongyo.':AM'.$Optiongyo)->getFont()->setSize(10);#フォントサイズをセット

				$Optiongyo+=3;

				$sheet->getStyle('H'.$Optiongyo.':S'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('U'.$Optiongyo.':AF'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->mergeCells('H'.$Optiongyo.':S'.($Optiongyo+1));
				$sheet->mergeCells('U'.$Optiongyo.':AF'.($Optiongyo+1));
				$Optiongyo+=2;
				$sheet->mergeCells('H'.$Optiongyo.':S'.($Optiongyo+5));
				$sheet->mergeCells('U'.$Optiongyo.':AF'.($Optiongyo+5));
				$Optiongyo+=6;
				$sheet->mergeCells('H'.$Optiongyo.':S'.($Optiongyo+2));
				$sheet->mergeCells('U'.$Optiongyo.':AF'.($Optiongyo+2));
				$Optiongyo+=3;
				$sheet->mergeCells('H'.$Optiongyo.':S'.($Optiongyo+1));
				$sheet->mergeCells('U'.$Optiongyo.':AF'.($Optiongyo+1));
				$Optiongyo-=11;

				$sheet->getStyle('H'.$Optiongyo.':S'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('U'.$Optiongyo.':AF'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=2;
				$sheet->getStyle('H'.$Optiongyo.':S'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('U'.$Optiongyo.':AF'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=6;
				$sheet->getStyle('H'.$Optiongyo.':S'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('U'.$Optiongyo.':AF'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo-=8;

				$sheet->getStyle('H'.$Optiongyo.':AM'.($Optiongyo+2))->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('H'.$Optiongyo.':AM'.($Optiongyo+12))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				
				$sheet->getStyle('H'.$Optiongyo.':AM'.($Optiongyo+12))->getFont()->setSize(9);#フォントサイズをセット
				$sheet->setCellValue('H'.$Optiongyo,"オプション④\n");
				$sheet->setCellValue('U'.$Optiongyo,"オプション⑤\n");
				$Optiongyo+=11;

				$sheet->getStyle('H'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('U'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('H'.$Optiongyo.':AM'.$Optiongyo)->getFont()->setSize(10);#フォントサイズをセット

				$cell[1] = "B";
				$cell[2] = "O";
				$cell[3] = "AB";
				$cell[4] = "H";
				$cell[5] = "U";
				$cell_f[1] = "M";
				$cell_f[2] = "Z";
				$cell_f[3] = "AM";
				$cell_f[1] = "S";
				$cell_f[2] = "Z";


			}elseif($OPSuu==6){

				$Optiongyo++;
				$Optiongyo++;
				$Optiongyo++;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+1));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+1));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo+=2;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+5));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+5));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+5));
				$Optiongyo+=6;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+2));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+2));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+2));
				$Optiongyo+=3;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+1));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+1));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo-=11;

				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=2;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=6;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo-=8;

				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getFont()->setSize(9);#フォントサイズをセット
				$sheet->setCellValue('B'.$Optiongyo,"オプション①\n");
				$sheet->setCellValue('O'.$Optiongyo,"オプション②\n");
				$sheet->setCellValue('AB'.$Optiongyo,"オプション③\n");
				$Optiongyo+=11;

				$sheet->getStyle('B'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('O'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('AB'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('B'.$Optiongyo.':AM'.$Optiongyo)->getFont()->setSize(10);#フォントサイズをセット

				$Optiongyo+=3;

				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+12))->getFill()->setFillType('solid')->getStartColor()->setARGB('BBDCFC');
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+1));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+1));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo+=2;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+5));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+5));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+5));
				$Optiongyo+=6;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+2));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+2));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+2));
				$Optiongyo+=3;
				$sheet->mergeCells('B'.$Optiongyo.':M'.($Optiongyo+1));
				$sheet->mergeCells('O'.$Optiongyo.':Z'.($Optiongyo+1));
				$sheet->mergeCells('AB'.$Optiongyo.':AM'.($Optiongyo+1));
				$Optiongyo-=11;

				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+12))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=2;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+5))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo+=6;
				$sheet->getStyle('B'.$Optiongyo.':M'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('O'.$Optiongyo.':Z'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->getStyle('AB'.$Optiongyo.':AM'.($Optiongyo+2))->getBorders()
				    ->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				
				$Optiongyo-=8;

				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+2))->getAlignment()
				    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+10))->getAlignment()
				    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
				
				$sheet->getStyle('B'.$Optiongyo.':AM'.($Optiongyo+12))->getFont()->setSize(9);#フォントサイズをセット
				$sheet->setCellValue('B'.$Optiongyo,"オプション④\n");
				$sheet->setCellValue('O'.$Optiongyo,"オプション⑤\n");
				$sheet->setCellValue('AB'.$Optiongyo,"オプション⑥\n");
				$Optiongyo+=11;

				$sheet->getStyle('B'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('O'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('AB'.$Optiongyo)->getFont()->setBold(true);#太字はsetBold(true)
				$sheet->getStyle('B'.$Optiongyo.':AM'.$Optiongyo)->getFont()->setSize(10);#フォントサイズをセット

				$cell[1] = "B";
				$cell[2] = "O";
				$cell[3] = "AB";
				$cell[4] = "B";
				$cell[5] = "O";
				$cell[6] = "AB";
				$cell_f[1] = "M";
				$cell_f[2] = "Z";
				$cell_f[3] = "AM";





			}
			$gyo = $gyoDEF;
			if($OPSuu>1){
				$gyo += 1;
			}
			if($OPSuu < 4){
				$Optiongyo++;
				$Optiongyo++;
				$Optiongyo++;
				for($i=$Optiongyo;$i<($Optiongyo+14);$i++){
					$sheet->getRowDimension($i)->setVisible(false);
				}
				$Optiongyo+=14;
			}
/*			if($OPSuu < 4){
				for($i=246;$i<262;$i++){
					$sheet->getRowDimension($i)->setVisible(false);
				}
			}

*/

			$sheet->getStyle('B218:AM242')->getAlignment()
			  ->setWrapText('true');


			for ($i = 0; $i < count($OPInfo); $i++) {
			
			#$sheet->setCellValue('C'.$gyo, "カテゴリ：".$OPInfo[$i]["OPCategory"]);
			#$gyo += 1;

				switch ( $OPInfo[$i]["OPUseKbn"] ){
					case "A": #そっくり入れ替える　ワイヤレス、受話器、スマホ連動のとき
						$No += 1;
						if($OPSuu < 4){
							$gyo = $gyoDEF;
						}elseif($OPSuu == 4){
							if($No < 3){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}

						}else{
							if($No < 4){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}
						}

						$sheet->setCellValue("E".$gyoAnq, "□　".$No.". ".$OPInfo[$i]["OPDeviceName"]."（品番：".$OPInfo[$i]["OPKataban"]."）");
						$gyoAnq += 1;

//						$sheet->setCellValue($cell[$No].$gyo, $OPInfo[$i]["OPDeviceName"]."（品番：".$OPInfo[$i]["OPKataban"]."）");
						$value = $sheet->getCell($cell[$No].$gyo)->getValue();
						if($OPSuu > 1){
							$sheet->setCellValue($cell[$No].$gyo, $value.$OPInfo[$i]["OPDeviceName"]);
						}else{
							$sheet->setCellValue($cell[$No].$gyo, $OPInfo[$i]["OPDeviceName"]);
						}
						$sheet->getStyle($cell[$No].$gyo)->getFont()->setBold(true);#太字はsetBold(true)
						$gyo += 2;

						#画像はる
						$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

						if($OPInfo[$i]["OPCategoryNo"] == "4" ){ #ワイヤレスの場合
							if($OPSuu== 1){
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/wirelessset_1.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(150);
								$drawing->setCoordinates("J".$gyo);
								$drawing->setOffsetX(4);
								$drawing->setOffsetY(1);
								$drawing->setWorksheet($sheet);
								$sheet->getStyle('B'.($gyo+6).':AM'.($gyo+6))->getFont()->setSize(10);#フォントサイズをセット
								$gyo += 6;
							}elseif($OPSuu== 2){
								$cell1= "E220";
								$cell2= "X220";
	
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/wirelessset.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(155);
								$drawing->setCoordinates(${"cell".$No});
								$drawing->setOffsetX(2);
								$drawing->setOffsetY(1);
								$drawing->setWorksheet($sheet);
								$gyo += 6;
								$sheet->getStyle('B220:AM221')->getFont()->setSize(8);#フォントサイズをセット
							}elseif($OPSuu== 3 || $OPSuu ==6){
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/wirelessset_3.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(153);
								$drawing->setCoordinates($cell[$No].$gyo);
								$drawing->setOffsetY(2);
								$drawing->setOffsetX(2);
								$drawing->setWorksheet($sheet);
								$gyo += 6;
							}elseif($OPSuu== 4){
								$cell1= "E";
								$cell2= "X";
								$cell3= "E";
								$cell4= "X";
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/wirelessset.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(155);
								$drawing->setCoordinates(${"cell".$No}.$gyo);
								$drawing->setOffsetY(2);
								$drawing->setOffsetX(2);
								$drawing->setWorksheet($sheet);
								$gyo += 6;
							}elseif($OPSuu== 5){

								$picpath = _DOCUMENT_ROOT."images/kikipicdata/wirelessset_3.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(153);
								$drawing->setCoordinates($cell[$No].$gyo);
								$drawing->setOffsetY(2);
								$drawing->setOffsetX(2);
								$drawing->setWorksheet($sheet);
								$gyo += 6;


							}
							$s=0;
							$temp_str = "";
							if (is_array($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
								if($OPSuu == 3 || $OPSuu==6){
									$temp_str = "別のお部屋にいても来客対応ができます。ワイヤレスなので配線工事は不要です。（インターホン親機から基地アンテナまでは有線になります。）";
								}else{
									foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {
										if($s==1){
											$temp_str .= "\n".$val;
										}else{
											$temp_str .= $val;
										}
										$s=1;
									}
								}
								$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
								$gyo +=3;
								$sheet->setCellValue($cell[$No].$gyo, "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");
								$sheet->getStyle($cell[$No].$gyo)->getAlignment()
								  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
								$sheet->getStyle($cell[$No].$gyo)->getAlignment()
								    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

							}
						}elseif($OPInfo[$i]["OPCategoryNo"] == "6" ){ #受話器の場合
							if($OPSuu==2 ){
								$cell1= "F";
								$cell2= "Y";
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/juwaki.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(153);
								$drawing->setOffsetY(2);
								$drawing->setCoordinates(${"cell".$No}.$gyo);
								$drawing->setWorksheet($sheet);
								$gyo += 6;
								$s=0;
								$temp_str = "";
								if (is_array($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
									foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {
										if($s==1){
											$temp_str .= "\n".$val;
										}else{
											$temp_str .= $val;
										}
										$s=1;
									}
									$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
									$gyo +=3;
									$sheet->setCellValue($cell[$No].$gyo, "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
								}
							}elseif( $OPSuu==3 || $OPSuu==6){
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/juwaki.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(153);
								$drawing->setOffsetY(1);
								$drawing->setOffsetX(1);
								$drawing->setCoordinates($cell[$No].$gyo);
								$drawing->setWorksheet($sheet);
								$gyo += 6;
								$s=0;
								$temp_str = "";
								if (is_array($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
									foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {

										if($s==1){
											$temp_str .= "\n".$val;
										}else{
											$temp_str .= $val;
										}
										$s=1;
									}
									$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
									$gyo +=3;
									$sheet->setCellValue($cell[$No].$gyo, "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


								}
							}elseif($OPSuu==4 ){
								$cell1= "F";
								$cell2= "Y";
								$cell3= "F";
								$cell4= "Y";
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/juwaki.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(153);
								$drawing->setOffsetY(2);
								$drawing->setCoordinates(${"cell".$No}.$gyo);
								$drawing->setWorksheet($sheet);
								$gyo += 6;
								$s=0;
								$temp_str = "";
								if (is_array($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
									foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {
										if($s==1){
											$temp_str .= "\n".$val;
										}else{
											$temp_str .= $val;
										}
										$s=1;
									}
									$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
									$gyo +=3;
									$sheet->setCellValue($cell[$No].$gyo, "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
								}
							}elseif($OPSuu==5 ){

								$picpath = _DOCUMENT_ROOT."images/kikipicdata/juwaki.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(153);
								$drawing->setOffsetY(1);
								$drawing->setOffsetX(1);
								$drawing->setCoordinates($cell[$No].$gyo);
								$drawing->setWorksheet($sheet);
								$gyo += 6;
								$s=0;
								$temp_str = "";
								if (is_array($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
									foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {

										if($s==1){
											$temp_str .= "\n".$val;
										}else{
											$temp_str .= $val;
										}
										$s=1;
									}
									$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
									$gyo +=3;
									$sheet->setCellValue($cell[$No].$gyo, "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


								}

							}else{
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/jyuwaki.png";
								$drawing->setPath($picpath);
								$drawing->setHeight(154);
								$drawing->setOffsetY(1);
								if($OPSuu==1 ){
									$drawing->setCoordinates("G".$gyo);
								}else{
									$drawing->setCoordinates($cell[$No].$gyo);
								}
								$drawing->setWorksheet($sheet);
								$gyo += 6;
								$s=0;
								$temp_str = "";
								if (is_array($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
									foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {
										if($s==1){
											$temp_str .= "\n".$val;
										}else{
											$temp_str .= $val;
										}
										$s=1;
									}
									$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
									$gyo +=3;
									$sheet->setCellValue($cell[$No].$gyo, "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
									$sheet->getStyle($cell[$No].$gyo)->getAlignment()
									    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

								}
							}
						}elseif($OPInfo[$i]["OPCategoryNo"] == "8" ){ #スマホ連動の場合
							if( $OPSuu==1 ){
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/sumaho.jpg";
								$drawing->setPath($picpath);
								$drawing->setHeight(154);
								$drawing->setOffsetX(10);
								$drawing->setOffsetY(1);
								$drawing->setCoordinates("H".$gyo);
							}elseif( $OPSuu==2 ){
								$cell1= "C219";
								$cell2= "V219";
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/sumaho_2.jpg";
								$drawing->setPath($picpath);
								$drawing->setHeight(153);
								$drawing->setOffsetY(2);
								$drawing->setCoordinates(${"cell".$No});
							}elseif( $OPSuu==3 || $OPSuu == 6){
								$gyo++;
								$sheet->getStyle('B220:AM221')->getFont()->setSize(8);#フォントサイズをセット
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/sumaho_2.jpg";
								$drawing->setPath($picpath);
								$drawing->setHeight(107);
								$drawing->setOffsetY(2);
								$drawing->setOffsetX(1);
								$drawing->setCoordinates($cell[$No].$gyo);
								$sheet->getRowDimension($gyo+5)->setRowHeight(37.2);
								$gyo--;

							}elseif( $OPSuu==4 ){
								$sheet->getStyle('B220:AM221')->getFont()->setSize(8);#フォントサイズをセット
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/sumaho_2.jpg";
								$drawing->setPath($picpath);
								$drawing->setHeight(153);
								$drawing->setOffsetY(2);
								$drawing->setOffsetX(1);
								$drawing->setCoordinates($cell[$No].$gyo);

							}elseif( $OPSuu==5 ){
								$gyo++;
								$sheet->getStyle('B220:AM221')->getFont()->setSize(8);#フォントサイズをセット
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/sumaho_2.jpg";
								$drawing->setPath($picpath);
								$drawing->setHeight(107);
								$drawing->setOffsetY(2);
								$drawing->setOffsetX(1);
								$drawing->setCoordinates($cell[$No].$gyo);
								$sheet->getRowDimension($gyo+5)->setRowHeight(37.2);
								$gyo--;
							}
							$drawing->setWorksheet($sheet);
							$gyo += 6;
							$s=0;
							$temp_str = "";
							if (is_array($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]])) {
								foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {
									$temp_str .= $val;
								}
								$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
								$gyo +=3;
								$sheet->setCellValue($cell[$No].$gyo, "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");
								$sheet->getStyle($cell[$No].$gyo)->getAlignment()
								  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
								$sheet->getStyle($cell[$No].$gyo)->getAlignment()
								    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

							}
						}
						break;

					case "B": #写真付き説明　カメラ付き玄関子機　ワイヤレスチャイム
						$No += 1;
						if($OPSuu < 4){
							$gyo = $gyoDEF;
						}elseif($OPSuu == 4){
							if($No < 3){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}

						}else{
							if($No < 4){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}
						}

						$value = $sheet->getCell($cell[$No].$gyo)->getValue();
						$sheet->setCellValue("E".$gyoAnq, "□　".$No.". ".$OPInfo[$i]["OPDeviceName"]."（品番：".$OPInfo[$i]["OPKataban"]."）");
						$gyoAnq += 1;

						$sheet->setCellValue($cell[$No].$gyo, $value.$OPInfo[$i]["OPDeviceName"]);
						$sheet->getStyle($cell[$No].$gyo)->getFont()->setBold(true);#太字はsetBold(true)
						$gyo += 2;
						#$gyoAnq += 1;
						$picpath = _DOCUMENT_ROOT."images/kikipicdata/".$OPInfo[$i]["OPKataban"].".jpg";

						if (file_exists($picpath)) {

							
							if($OPSuu== 1){
								$cellpic[1] = 'Q';
							}elseif($OPSuu== 2){
								$cellpic[1] = 'G';
								$cellpic[2] = 'Z';
							}elseif($OPSuu== 3){
								$cellpic[1] = 'C';
								$cellpic[2] = 'P';
								$cellpic[3] = 'AC';
							}elseif($OPSuu== 4){
								$cellpic[1] = 'G';
								$cellpic[2] = 'Z';
								$cellpic[3] = 'G';
								$cellpic[4] = 'Z';
							}elseif($OPSuu== 5){
								$cellpic[1] = 'C';
								$cellpic[2] = 'P';
								$cellpic[3] = 'AC';
								$cellpic[4] = 'I';
								$cellpic[5] = 'V';
							}elseif($OPSuu== 6){
								$cellpic[1] = 'C';
								$cellpic[2] = 'P';
								$cellpic[3] = 'AC';
								$cellpic[4] = 'C';
								$cellpic[5] = 'P';
								$cellpic[6] = 'AC';
							}else{

		$ErrorString = array();
		$ErrorString[] = "オプション4つ目以上が対応していません。将来的には対応します。ご迷惑をおかけしますが、3つで登録して手修正お願いします。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

							}
							#画像はる
							$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
							$drawing->setPath($picpath);
							$drawing->setHeight(150);
							$drawing->setCoordinates($cellpic[$No].$gyo);
							$drawing->setOffsetX(10);
							$drawing->setOffsetY(1);
							$drawing->setWorksheet($sheet);

							$gyo += 6;#写真があれば6行ずらす
						}else{
							$gyo += 6;	#写真なくても6行づらす
						}

						if($OPInfo[$i]["OPCategoryNo"] == "5" ){ #ワイヤレスチャイムの場合
							$temp_str = "";
							if(strpos($OPInfo[$i]["OPDeviceName"],'追加受信機')){	#ワイヤレスチャイム追加受信機の場合
								$sheet->setCellValue($cell[$No].$gyo, $KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]][0]);	#機器の説明だけ出力
							}else{
								$s=0;
								$temp_str = "";
								if($OPSuu == 3 ||$OPSuu == 5 ||$OPSuu == 6 ){
									$temp_str = "別のお部屋にいてもインターホンの呼出音が聞こえます。ワイヤレスなので配線工事は不要です。（居室親機からアンテナまでは有線になります。）";
								}else{
									foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {
										if($s==1){
											$temp_str .= "\n".$val;
										}else{
											$temp_str .= $val;
										}
										$s=1;
									}
								}
								if($OPSuu == 4  || $OPSuu == 2){
									$sheet->getStyle('B226:AM228')->getFont()->setSize(8);#フォントサイズをセット
									$sheet->getStyle('B240:AM242')->getFont()->setSize(8);#フォントサイズをセット
								}
								$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
							}
						}else{

							$s=0;
							$temp_str = "";
							foreach ($KIKICATEGORY_INFO[$OPInfo[$i]["OPCategoryNo"]] as $val) {
								if($s==1){
									$temp_str .= "\n".$val;
								}else{
									$temp_str .= $val;
								}
								$s=1;
							}
							$sheet->setCellValue($cell[$No].$gyo, " ".$temp_str);
						}
						
						$gyo += 3;
//						$sheet->setCellValue('A'.$gyo, $gyo);
						$sheet->setCellValue($cell[$No].$gyo, "販売価格:".$OPInfo[$i]["OPPrice"]."円（税込）");
						$sheet->getStyle($cell[$No].$gyo)->getAlignment()
						  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						$sheet->getStyle($cell[$No].$gyo)->getAlignment()
						  ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

						break;

					case "C":#タグ　wTagSuu
						$No += 1;
						if($OPSuu < 4){
							$gyo = $gyoDEF;
						}elseif($OPSuu == 4){
							if($No < 3){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}

						}else{
							if($No < 4){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}
						}
						$sheet->setCellValue("E".$gyoAnq, "□　".$No.". ".$OPInfo[$i]["OPDeviceName"]."（品番：".$OPInfo[$i]["OPKataban"]."）");

						$gyoAnq += 1;

						$value = $sheet->getCell($cell[$No].$gyo)->getValue();
						$sheet->setCellValue($cell[$No].$gyo, $value.$OPInfo[$i]["OPDeviceName"]);
						$sheet->getStyle($cell[$No].$gyo)->getFont()->setBold(true);#太字はsetBold(true)
						$gyo += 2;


						

						#画像はる　キーヘッドのときは画像をかえたい
						#ノンタッチキーヘッド
						if(strstr($OPInfo[$i]["OPDeviceName"],'ノンタッチキーヘッド')){	
							if($OPSuu==1){
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/nontouchkeyhead.png";
							}elseif($OPSuu==3 || $OPSuu==5 || $OPSuu==6 ){
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/nontouchkeyhead_3.png";
							}else{
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/nontouchkeyhead_2.png";
							}
						}else{	#ノンタッチタグ
							if($OPSuu==1){
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/nontouchtag.jpg";
							}elseif($OPSuu==3 || $OPSuu==5 || $OPSuu==6 ){
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/nontouchtag_3.png";
							}else{
								$picpath = _DOCUMENT_ROOT."images/kikipicdata/nontouchtag_2.png";
							}
						}
						$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
						$drawing->setPath($picpath);
						if($OPSuu==2){
							$cell1= "B220";
							$cell2= "U220";
							$drawing->setHeight(153);
							$drawing->setOffsetX(5);
							$drawing->setOffsetY(2);
							$drawing->setCoordinates($cell[$No].$gyo);
							$drawing->setWorksheet($sheet);
							$gyo += 6;
						}elseif($OPSuu==4){
							$cell1= "B";
							$cell2= "U";
							$cell1= "B";
							$cell2= "U";
							$drawing->setHeight(153);
							$drawing->setOffsetX(5);
							$drawing->setOffsetY(2);
							$drawing->setCoordinates($cell[$No].$gyo);
							$drawing->setWorksheet($sheet);
							$gyo += 6;
						}elseif($OPSuu==3 || $OPSuu==5 || $OPSuu==6){
							$cell1= "C";
							$cell2= "P";
							$cell3= "AC";
							if($OPSuu==5){
								$cell4= "I";
								$cell5= "V";
							}elseif($OPSuu==6){
								$cell4= "C";
								$cell5= "P";
								$cell6= "AC";
							}
							$drawing->setHeight(153);
							$drawing->setOffsetX(1);
							$drawing->setOffsetY(1);
							$drawing->setCoordinates(${"cell".$No}.$gyo);
							$drawing->setWorksheet($sheet);
							$gyo ++;
							$gyo += 5;
							$sheet->getStyle('B'.$gyo.':AM'.$gyo)->getFont()->setSize(8);#フォントサイズをセット
						}elseif($OPSuu==1){
							$drawing->setHeight(154);
							$drawing->setOffsetY(1);
							$drawing->setCoordinates("H".$gyo);
							$drawing->setWorksheet($sheet);
							$gyo += 6;
						}

						$val = "エントランスの集合玄関機受信部にかざすだけで解錠できます。\n";
						$val .= "※各住戸には工事の時に標準数（".$wTagSuu."本）をお渡しします。\n";
						$val .= "標準本数で不足の場合は、追加本数を販売価格にて承ります。";
						$sheet->setCellValue($cell[$No].$gyo, $val);
						$gyo += 3;

						$sheet->setCellValue($cell[$No].$gyo, "販売価格:1本 ".$OPInfo[$i]["OPPrice"]."円（税込）");
						$sheet->getStyle($cell[$No].$gyo)->getAlignment()
						  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						$sheet->getStyle($cell[$No].$gyo)->getAlignment()
						    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

						break;

					case "D":#写真なし　品番、価格のみ（説明文を付ける場合は、ｔDeviceMにOPUseDispを利用してもよい）
						$No += 1;
						if($OPSuu < 4){
							$gyo = $gyoDEF;
						}elseif($OPSuu == 4){
							if($No < 3){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}

						}else{
							if($No < 4){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}
						}

						$sheet->setCellValue("E".$gyoAnq, "□　".$No.". ".$OPInfo[$i]["OPDeviceName"]."（品番：".$OPInfo[$i]["OPKataban"]."）");
						$gyoAnq += 1;

						$sheet->setCellValue($cell[$No].$gyo, $OPInfo[$i]["OPDeviceName"]);
						$sheet->getStyle($cell[$No].$gyo)->getFont()->setBold(true);#太字はsetBold(true)
						$gyo += 11;
						$sheet->setCellValue($cell[$No].$gyo, "販売価格：￥".$OPInfo[$i]["OPPrice"]."（税込）");
						$sheet->getStyle($cell[$No].$gyo)->getAlignment()
						  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						$sheet->getStyle($cell[$No].$gyo)->getAlignment()
						    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
						$gyo += 1;
						$gyo += 1;	#もう一行ずらす
						
						break;

					case "Z":#自由記入の機器　写真なし記入内容＋金額のみ
						$No += 1;
						if($OPSuu < 4){
							$gyo = $gyoDEF;
						}elseif($OPSuu == 4){
							if($No < 3){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}

						}else{
							if($No < 4){
								$gyo = $gyoDEF;
							}else{
								$gyo = $gyoDEF+14;
							}
						}

						$sheet->setCellValue("E".$gyoAnq, "□　".$No.". ".$OPInfo[$i]["OPDeviceName"]);
						$gyoAnq += 1;

						$sheet->setCellValue($cell[$No].$gyo, $OPInfo[$i]["OPDeviceName"]);
						$sheet->getStyle($cell[$No].$gyo)->getFont()->setBold(true);#太字はsetBold(true)
						$gyo += 11;
						$sheet->setCellValue($cell[$No].$gyo, "販売価格：￥".$OPInfo[$i]["OPPrice"]."（税込）");
						$sheet->getStyle($cell[$No].$gyo)->getAlignment()
						  ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						$sheet->getStyle($cell[$No].$gyo)->getAlignment()
						    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
						$gyo += 1;
						$gyo += 1;	#もう一行ずらす
						
						break;


					default:#オプション表記不要

				}#END Switch 

				if($gyo>=90&&!isset($k)){
					$gyo1 = $gyo;
					$k=1;
				}
			}#END for
			
			if($No == 1){	#1件だけの場合　振っている番号を削除
				$tmpCell = $sheet->getCell('E'.$gyoAnqDEF); #アンケート1行目を取得
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("1.", "", $tmpCellStr);
				$tmpCell->setValue($tmpCellStr);

				$tmpCell = $sheet->getCell($cell[1].$gyoDEF); #オプションのご案内1行目を取得
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("1.", "", $tmpCellStr);
				$tmpCell->setValue($tmpCellStr);
			}

			// 印刷範囲
/*			$sheet->getPageSetup()->setPrintArea('A1:AP'.$gyo);
			if($gyo1){
				$sheet->setBreak('O'.($gyo1-1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

			}
*/		}

		$Jikahogyo = 290;

		if($IfOP && $OPSuu > 0)
			$sheet->setBreak('A'.($Jikahogyo-1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

		#火災警報_220号
		#$sheet = $spreadsheet->getSheetByName('火災警報_220号');
		if( $ShoboTokurei != 3) {#220号共同住宅用　以外の場合
				for($i=$Jikahogyo; $i<($Jikahogyo+39) ; $i++){
					$sheet->getRowDimension($i)->setVisible(false);
				
				}
		} else {
			$tmpKasai = "「火災」";
			$tmpGus = "「ガス漏れ」"; 
			if($wJikaho == 0){
				$tmpKasai = "";
			}
			if( $wGasKoji == 0 ){
				$tmpGus = ""; 
			}

//			$sheet = $spreadsheet->getSheetByName('火災警報_220号 (開始前、終了後一斉確認)');
			if($ShoboSikenhoho != 0){#0:着工前後







			}else{
				$Jikahogyo+=2;
				$tmpCell = $sheet->getCell('C'.$Jikahogyo); // マンション名を置き換え
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("○○様", $BukkenName.'様', $tmpCellStr);
				$tmpCell->setValue($tmpCellStr);

				$Jikahogyo+=14;
		//		$tmpCellStr = str_replace("●月●日", $KyoyoStartDate, $tmpCellStr);
				$sheet->setCellValue('C'.$Jikahogyo, "共用部工事開始前の".$KyoyoStartDate."に「近隣火災」警報の試験を行います。");
				$Jikahogyo++;
				$sheet->setCellValue('C'.$Jikahogyo, "「近隣火災」警報の試験の際は、試験中の住戸とその近隣住戸でも「近隣火災」警報が鳴動します。");
				$Jikahogyo++;
				$Jikahogyo++;
				$sheet->setCellValue('C'.$Jikahogyo, "専有部工事完了の都度、「非常」「ガス漏れ」「火災」警報の試験を行います。");
				$Jikahogyo++;
				$sheet->setCellValue('C'.$Jikahogyo, "「非常」「ガス漏れ」「火災」警報は管理室と試験中の住戸のみ警報が鳴動します。");
				$Jikahogyo++;
				$Jikahogyo++;
				$sheet->setCellValue('C'.$Jikahogyo, "③");
				$sheet->setCellValue('C'.$Jikahogyo, "専有部工事完了後の".$SenyuEndDate."に「近隣火災」警報の試験を行います。");
				$Jikahogyo++;
				$sheet->setCellValue('B'.$Jikahogyo, "※");
				$sheet->setCellValue('C'.$Jikahogyo, "試験方法は①と同様です。");

				$Jikahogyo+=5;

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/kinrinkasai.png");
				$drawing->setHeight(240);
				$drawing->setCoordinates('C'.$Jikahogyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);



			}

	//		$sheet = $spreadsheet->getSheetByName('火災警報_220号 (終了後一斉確認)');
			if($ShoboSikenhoho != 1){#1:終了後
	//			$sheet = $spreadsheet->getSheetByName('火災警報_220号 (終了後一斉確認)');
//				$sheet->setSheetState('veryHidden');	#非表示に設定
			}else{
				$Jikahogyo+=2;
				$tmpCell = $sheet->getCell('C'.$Jikahogyo); // マンション名を置き換え
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("○○様", $BukkenName.'様', $tmpCellStr);
				$tmpCell->setValue($tmpCellStr);
				$Jikahogyo+=17;
				if($ShoboSikenDate=="" || $ShoboSikenJikan==""){
					$sheet->setCellValue('C'.$Jikahogyo, "専有部工事完了後の".$SenyuEndDate."に「近隣火災」警報の試験を行います。");
				}else{
					if($ShoboSikenDate!="0000-00-00"){
						$sheet->setCellValue('C'.$Jikahogyo, "専有部工事完了後の".$ShoboSikenDate."に「近隣火災」警報の試験を行います。");
						if($ShoboSikenJikan){
							$sheet->setCellValue('C'.$Jikahogyo, "専有部工事完了後の".$ShoboSikenDate." ".$ShoboSikenJikan."に「近隣火災」警報の試験を行います。");
							if($ShoboSikenJikan2){
								$sheet->setCellValue('C'.$Jikahogyo, "専有部工事完了後の".$ShoboSikenDate." ".$ShoboSikenJikan."～".$ShoboSikenJikan2."に「近隣火災」警報の試験を行います。");
							}
						}
					}
				}
				$Jikahogyo+=9;
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/kinrinkasai.png");
				$drawing->setHeight(240);
				$drawing->setCoordinates('C'.$Jikahogyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);


			}

//			$sheet = $spreadsheet->getSheetByName('火災警報_220号（都度確認）');
			if($ShoboSikenhoho != 2){#2:都度
//				$sheet = $spreadsheet->getSheetByName('火災警報_220号（都度確認）');
//				$sheet->setSheetState('veryHidden');	#非表示に設定
			
			}else{

				$Jikahogyo+=2;
				$tmpCell = $sheet->getCell('C'.$Jikahogyo); // マンション名を置き換え
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("○○様", $BukkenName.'様', $tmpCellStr);
				$tmpCell->setValue($tmpCellStr);
				
				$Jikahogyo+=14;
				$tmpCell = $sheet->getCell('B'.$Jikahogyo); // 火災防犯置き換え
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("「ガス漏れ」", $tmpGus, $tmpCellStr);
				$tmpCellStr = str_replace("「火災」", $tmpKasai, $tmpCellStr);	
				$tmpCell->setValue($tmpCellStr);
				
				$Jikahogyo++;
				$tmpCell = $sheet->getCell('B'.$Jikahogyo); // 火災防犯置き換え
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("「ガス漏れ」", $tmpGus, $tmpCellStr);
				$tmpCellStr = str_replace("「火災」", $tmpKasai, $tmpCellStr);	
				$tmpCell->setValue($tmpCellStr);
				
				$Jikahogyo+=11;
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/kinrinkasai.png");
				$drawing->setHeight(240);
				$drawing->setCoordinates('C'.$Jikahogyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);


			
			}
		}








		$Taggyo = 328;
		if( $ShoboTokurei == 3) {#220号共同住宅用　以外の場合
			$sheet->setBreak('A'.($Taggyo-1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
		}
#		
		######ノンタッチシステムの導入シート#####
		#オートロックありのみ
		if( $wAutoLock == "1" ){
//			$sheet = $spreadsheet->getSheetByName('ノンタッチシステムの導入について');
			#1keyの場合
			#if(!strstr ($wRNNyukan ,'0') and $wTagSuu > 0){#鍵×かつタグ数0以上（ノンタッチシステム）
			if($nonTouchFLG and !strstr($wRNNyukan ,'0')){#ノンタッチシステム導入 & 鍵× &タグ数0以上
				// タグ切替日を設定
				
				$Taggyo++;
				$tmpCell = $sheet->getCell('B'.$Taggyo);
				$tmpCellStr = $tmpCell->getValue();
				$tmpCellStr = str_replace("●月●日",date('n月j日',strtotime( $TagKirikaeDate )),$tmpCellStr);
				$tmpCell->setValue($tmpCellStr);

				if($wTagSuu > 0){	#標準配布がある場合
					if($wOwnerTagSuu <= 0){#オーナータグ0本の場合
						// タグ本数
						$Taggyo+=10;
						$tmpCell = $sheet->getCell('C'.$Taggyo);
						$tmpCellStr = $tmpCell->getValue();
						$tmpCellStr = str_replace("【標準本数】",$wTagSuu,$tmpCellStr);
						$tmpCell->setValue($tmpCellStr);
						$Taggyo++;

						$sheet->getRowDimension($Taggyo)->setVisible(false);
						$Taggyo++;
						$sheet->getRowDimension($Taggyo)->setVisible(false);
						$Taggyo++;
						$sheet->getRowDimension($Taggyo)->setVisible(false);
					}else{
						$Taggyo+=11;
						// タグ本数
						$tmpCell = $sheet->getCell('C'.$Taggyo); 
						$tmpCellStr = $tmpCell->getValue();
						$tmpCellStr = str_replace("【標準本数】",$wTagSuu,$tmpCellStr);
						$tmpCell->setValue($tmpCellStr);

						$Taggyo++;
						// タグ本数　区分所有者
						$tmpCell = $sheet->getCell('C'.$Taggyo); // タグ切替日を設定
						$tmpCellStr = $tmpCell->getValue();
						$tmpCellStr = str_replace("【標準本数‐外部オーナー本数】",(intval($wTagSuu) - intval($wOwnerTagSuu)),$tmpCellStr);
						$tmpCell->setValue($tmpCellStr);

						$Taggyo++;
						// タグ本数　オーナー
						$tmpCell = $sheet->getCell('C'.$Taggyo); // タグ切替日を設定
						$tmpCellStr = $tmpCell->getValue();
						$tmpCellStr = str_replace("【外部オーナー本数】",$wOwnerTagSuu,$tmpCellStr);
						$tmpCell->setValue($tmpCellStr);

						$sheet->getRowDimension($Taggyo-3)->setVisible(false);
					}
				}else{#標準配布なし
					$Taggyo--;
					for($i=$Taggyo;$i<$Taggyo+15;$i++){
						$sheet->getRowDimension($i)->setVisible(false);
					}
					
				}
			#それ以外
			}else{
//				$sheet->setSheetState('veryHidden');	#非表示に設定
				for($i=$Taggyo;$i<$Taggyo+17;$i++){
					$sheet->getRowDimension($i)->setVisible(false);
				}
			
			}
		}else{
//			$sheet->setSheetState('veryHidden');	#非表示に設定
			for($i=$Taggyo;$i<$Taggyo+17;$i++){
				$sheet->getRowDimension($i)->setVisible(false);
			}
		
		}


##################################################################################################################
###########予定案内
##################################################################################################################

		$Yoteigyo=345;#予定案内のスタートセル位置（行）

		if( $wAutoLock == "1" ){
			if($nonTouchFLG and !strstr($wRNNyukan ,'0')){#ノンタッチシステム導入 & 鍵× &タグ数0以上
				if($wTagSuu > 0){	#標準配布がある場合
					$sheet->setBreak('A'.($Yoteigyo-1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
				}
			}
		}

		if($wAnswer=="A.日時変更住戸のみ返答"){
			if($wWEBRecept=="1"){				#予定WEB①_日変

				#サンプルの文字を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/sample.png");
				$drawing->setHeight(80);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates('O'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->setCellValue('AN'.$Yoteigyo , date('Y年')."　〇月　〇日");
				$Yoteigyo++;
				$sheet->setCellValue('C'.$Yoteigyo, $BukkenName);
				$sheet->getStyle('B'.$Yoteigyo.':M'.($Yoteigyo+1))
				    ->getFont()->setSize(16);
				$sheet->getStyle('C'.$Yoteigyo.':M'.($Yoteigyo+1))
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop1);
				$sheet->setCellValue('J'.$Yoteigyo, "号室");



				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop2);
				$Yoteigyo++;
				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop3);
				$Yoteigyo++;
				$sheet->getStyle('A'.$Yoteigyo)
				    ->getFont()->setBold('true');
				$sheet->setCellValue('A'.$Yoteigyo, "インターホン設備更新工事のお知らせ");

				#境界線の設定
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getFont()->setSize(24);
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getTop()->getColor(18)->setARGB('FFFF0000');
 				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('FFFF0000');

				#セル内の位置の設定
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$sheet->getRowDimension($Yoteigyo)->setRowHeight(53.4);
				#セルの結合
				$sheet->mergeCells('A'.$Yoteigyo.':AN'.$Yoteigyo);
				$Yoteigyo++;#335
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(35.4);
				$sheet->setCellValue('B'.$Yoteigyo, "下記の通り訪問日を予定させていただきました。工事の際には居室インターホンの取替がございますので、ご在宅をお願いします。");
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':AM'.($Yoteigyo+1));
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getAlignment()->setWrapText(true);

				$Yoteigyo++;
				$Yoteigyo++;
				$Yoteigyo++;

				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('ff0000');

				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getFont()->setBold('true');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getFont()->setSize(18);
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getFont()->setUnderline('double');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getBorders()->getOutline()->setBorderStyle('thick');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getBorders()->getOutline()->getColor(18)->setARGB('FFFF0000');
				$sheet->setCellValue('H'.$Yoteigyo, "下記日程についてご都合が合わない場合は");

				#日程変更方法の画像を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/attention.png");
				$drawing->setHeight(60);
				$drawing->setOffsetX(5);
				$drawing->setOffsetY(15);
				$drawing->setCoordinates('C'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30.6);
				$sheet->mergeCells('H'.$Yoteigyo.':AM'.$Yoteigyo);
				$Yoteigyo++;
				$sheet->mergeCells('H'.$Yoteigyo.':AM'.$Yoteigyo);
				$sheet->getStyle('B'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->setCellValue('H'.$Yoteigyo, "○月○日までにご連絡お願いします");
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(35.4);
				$Yoteigyo++;
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':P'.$Yoteigyo);
				$sheet->mergeCells('Q'.$Yoteigyo.':V'.$Yoteigyo);
				$sheet->mergeCells('W'.$Yoteigyo.':AM'.$Yoteigyo);
				#セル内の位置の設定
				$sheet->getStyle('B'.$Yoteigyo.':P'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(52.2);

				$sheet->getStyle('Q'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->setCellValue('B'.$Yoteigyo, "○月×日");
				$sheet->setCellValue('Q'.$Yoteigyo, "△曜日");
				$sheet->setCellValue('W'.$Yoteigyo, "X:XX～X:XX");
				$Yoteigyo++;
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+16))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('ffe4d5');
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':AM'.$Yoteigyo);
				#セル内の位置の設定
				$sheet->getStyle('A'.$Yoteigyo.':AM'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->setCellValue('B'.$Yoteigyo, "【日程ご予約方法】");
				$Yoteigyo++;
				$sheet->mergeCells('D'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('A'.$Yoteigyo.':AM'.$Yoteigyo)
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
				$sheet->setCellValue('D'.$Yoteigyo, "●インターネットで日程の変更・確定をする場合（24時間受付）");
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('000000');
				$Yoteigyo++;
				$sheet->getStyle('D'.$Yoteigyo.':AK'.($Yoteigyo+8))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('FFFFFF');
				$sheet->getStyle('D'.$Yoteigyo.':AN'.($Yoteigyo+8))
				    ->getAlignment()->setWrapText(true);
				$sheet->mergeCells('D'.$Yoteigyo.':R'.($Yoteigyo+1));
				$sheet->mergeCells('S'.$Yoteigyo.':AK'.($Yoteigyo+1));
				$sheet->setCellValue('D'.$Yoteigyo, "①アイホンHPにアクセス");

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/aiphoneHP.png");
				$drawing->setHeight(50);
				$drawing->setOffsetX(2);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates('X'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/aiphoneQR.png");
				$drawing->setHeight(60);
				$drawing->setOffsetX(2);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates('AH'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30.0);

				$Yoteigyo++;
				$Yoteigyo++;

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/interphonebanner.png");
				$drawing->setHeight(30);
				$drawing->setOffsetX(2);
				$drawing->setOffsetY(5);
				$drawing->setCoordinates('X'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->mergeCells('D'.$Yoteigyo.':R'.($Yoteigyo+1));
				$sheet->mergeCells('S'.$Yoteigyo.':AK'.($Yoteigyo+1));
				$sheet->setCellValue('D'.$Yoteigyo, "②「インターホン工事予約システム」のバナーをクリック");
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$Yoteigyo++;
				$sheet->mergeCells('D'.$Yoteigyo.':R'.($Yoteigyo+1));
				$sheet->mergeCells('S'.$Yoteigyo.':W'.$Yoteigyo);
				$sheet->mergeCells('X'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('d3d3d3');
				$sheet->setCellValue('D'.$Yoteigyo, "③物件管理番号の入力");
				$sheet->setCellValue('S'.$Yoteigyo, "(物件管理番号)");
				$sheet->setCellValue('X'.$Yoteigyo, $editBukkenCD);
				$sheet->getStyle('S'.$Yoteigyo.':W'.($Yoteigyo+4))->getFont()->setSize(9);#フォントサイズをセット
				$sheet->getStyle('X'.$Yoteigyo.':AK'.($Yoteigyo+4))->getFont()->setSize(16);#フォントサイズをセット
				$sheet->getStyle('X'.$Yoteigyo.':AK'.($Yoteigyo+4))
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('X'.$Yoteigyo.':AK'.($Yoteigyo+4))
				    ->getFont()->getColor()->setARGB('0000CD');
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(10);
				$Yoteigyo++;
				$sheet->mergeCells('D'.$Yoteigyo.':R'.($Yoteigyo+2));
				$sheet->mergeCells('S'.$Yoteigyo.':W'.$Yoteigyo);
				$sheet->mergeCells('X'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('d3d3d3');
				$sheet->setCellValue('D'.$Yoteigyo, "④ユーザー名とパスワードの入力");
				$sheet->setCellValue('S'.$Yoteigyo, "(ユーザー名)");
				$sheet->setCellValue('X'.$Yoteigyo, '1234');
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$sheet->mergeCells('S'.$Yoteigyo.':W'.$Yoteigyo);
				$sheet->mergeCells('X'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('d3d3d3');
				$sheet->setCellValue('S'.$Yoteigyo, "(パスワード)");
				$sheet->setCellValue('X'.$Yoteigyo, '5678');
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$Yoteigyo+=1;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(10);
				$Yoteigyo+=2;
				#セルの結合s
				$sheet->mergeCells('D'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('A'.$Yoteigyo.':AM'.$Yoteigyo)
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('000000');
				$sheet->setCellValue('D'.$Yoteigyo, "●お電話で日程の変更・確定する場合（午前9時～午後5時30分）");
				$Yoteigyo++;

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/denwa1.png");
				$drawing->setHeight(40);
				$drawing->setOffsetX(20);
				$drawing->setOffsetY(20);
				$drawing->setCoordinates('D'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->mergeCells('D'.$Yoteigyo.':AK'.($Yoteigyo+2));
				$sheet->getStyle('D'.$Yoteigyo.':AK'.($Yoteigyo+2))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('FFFFFF');
				$Yoteigyo++;

				$Yoteigyo+=3;

				#作業員のイラストを貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/sagyoin.png");
				$drawing->setHeight(130);
				$drawing->setCoordinates('B'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$Yoteigyo++;

				$sheet->setCellValue('H'.$Yoteigyo, "・日程調整後、訪問日時確定のご案内を配布させていただきます。");
				$Yoteigyo++;
				$sheet->setCellValue('H'.$Yoteigyo, "・お預かりした個人情報は、当社の定めた個人情報保護方針に基づき厳重に管理し、");
				$Yoteigyo++;
				$sheet->setCellValue('H'.$Yoteigyo, "　今回のインターホン工事以外での目的では使用いたしません。");

			
			}else{
				
				#サンプルの文字を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/sample.png");
				$drawing->setHeight(80);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates('O'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->setCellValue('AN'.$Yoteigyo , date('Y年')."　〇月　〇日");
				$Yoteigyo++;
				$sheet->setCellValue('C'.$Yoteigyo, $BukkenName);
				$sheet->getStyle('B'.$Yoteigyo.':M'.($Yoteigyo+1))
				    ->getFont()->setSize(16);
				$sheet->getStyle('C'.$Yoteigyo.':M'.($Yoteigyo+1))
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop1);
				$sheet->setCellValue('J'.$Yoteigyo, "号室");



				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop2);
				$Yoteigyo++;
				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop3);
				$Yoteigyo++;
				$sheet->getStyle('A'.$Yoteigyo)
				    ->getFont()->setBold('true');
				$sheet->setCellValue('A'.$Yoteigyo, "インターホン設備更新工事のお知らせ");

				#境界線の設定
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getFont()->setSize(24);
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getTop()->getColor(18)->setARGB('FFFF0000');
 				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('FFFF0000');

				#セル内の位置の設定
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$sheet->getRowDimension($Yoteigyo)->setRowHeight(53.4);
				#セルの結合
				$sheet->mergeCells('A'.$Yoteigyo.':AN'.$Yoteigyo);
				$Yoteigyo++;#335
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(35.4);
				$sheet->setCellValue('B'.$Yoteigyo, "下記の通り工事を予定させていただきました。工事の際には居室インターホンの取替がございますので、ご在宅をお願いします。");
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':AM'.($Yoteigyo+1));
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getAlignment()->setWrapText(true);

				$Yoteigyo++;
				$Yoteigyo++;
				$Yoteigyo++;

				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('ff0000');

				#日程変更方法の画像を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/attention.png");
				$drawing->setHeight(60);
				$drawing->setOffsetX(5);
				$drawing->setOffsetY(15);
				$drawing->setCoordinates('C'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getFont()->setBold('true');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getFont()->setSize(18);
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getFont()->setUnderline('double');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getBorders()->getOutline()->setBorderStyle('thick');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getBorders()->getOutline()->getColor(18)->setARGB('FFFF0000');
				$sheet->setCellValue('H'.$Yoteigyo, "下記日程についてご都合が合わない場合は");
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30.6);
				$sheet->mergeCells('H'.$Yoteigyo.':AM'.$Yoteigyo);
				$Yoteigyo++;
				$sheet->mergeCells('H'.$Yoteigyo.':AM'.$Yoteigyo);
				$sheet->getStyle('B'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->setCellValue('H'.$Yoteigyo, "○月○日までにご連絡お願いします");
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(35.4);
				$Yoteigyo++;
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':P'.$Yoteigyo);
				$sheet->mergeCells('Q'.$Yoteigyo.':V'.$Yoteigyo);
				$sheet->mergeCells('W'.$Yoteigyo.':AM'.$Yoteigyo);
				#セル内の位置の設定
				$sheet->getStyle('B'.$Yoteigyo.':P'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(52.2);

				$sheet->getStyle('Q'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->setCellValue('B'.$Yoteigyo, "○月×日");
				$sheet->setCellValue('Q'.$Yoteigyo, "△曜日");
				$sheet->setCellValue('W'.$Yoteigyo, "X:XX～X:XX");
				$Yoteigyo++;
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+16))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('ffe4d5');
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':AM'.$Yoteigyo);
				#セル内の位置の設定
				$sheet->getStyle('A'.$Yoteigyo.':AM'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$Yoteigyo++;
				#日程変更方法の画像を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/toiawase_nitihen.png");
				$drawing->setHeight(350);
				$drawing->setCoordinates('F'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);
				$sheet->mergeCells('D'.$Yoteigyo.':AK'.$Yoteigyo);
				$Yoteigyo++;
				$Yoteigyo++;
				$Yoteigyo++;

				$Yoteigyo++;
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(10);
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$Yoteigyo+=1;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(10);
				$Yoteigyo+=2;
				$Yoteigyo++;


				$Yoteigyo++;

				$Yoteigyo+=3;

				#作業員のイラストを貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/sagyoin.png");
				$drawing->setHeight(130);
				$drawing->setCoordinates('B'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$Yoteigyo++;

				$sheet->setCellValue('H'.$Yoteigyo, "・日程調整後、工事日時確定のご案内を各住戸様へ配布いたします。");
				$Yoteigyo++;
				$sheet->setCellValue('H'.$Yoteigyo, "・お預かりした個人情報は、当社の定めた個人情報保護方針に基づき厳重に管理し、");
				$Yoteigyo++;
				$sheet->setCellValue('H'.$Yoteigyo, "　今回のインターホン工事以外での目的では使用いたしません。");

			}
		}else{#全住戸回答
			if($wWEBRecept=="1"){

				#サンプルの文字を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/sample.png");
				$drawing->setHeight(80);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates('O'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);
				#左上の必ず電話ください赤い印		
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/logo.png");
				$drawing->setHeight(94);
				$drawing->setCoordinates('A'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);



				$sheet->setCellValue('AN'.$Yoteigyo , date('Y年')."　〇月　〇日");
				$Yoteigyo++;
				$sheet->setCellValue('C'.$Yoteigyo, $BukkenName);
				$sheet->getStyle('B'.$Yoteigyo.':M'.($Yoteigyo+1))
				    ->getFont()->setSize(16);
				$sheet->getStyle('C'.$Yoteigyo.':M'.($Yoteigyo+1))
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop1);
				$Yoteigyo++;
				$sheet->setCellValue('J'.$Yoteigyo, "号室");

				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop2);
				$Yoteigyo++;
				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop3);
				$Yoteigyo++;
				$sheet->getStyle('A'.$Yoteigyo)
				    ->getFont()->setBold('true');
				$sheet->setCellValue('A'.$Yoteigyo, "インターホン設備更新工事　ご訪問予定日のお知らせ");

				#境界線の設定
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getFont()->setSize(24);
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getTop()->getColor(18)->setARGB('FFFF0000');
 				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('FFFF0000');

				#セル内の位置の設定
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$sheet->getRowDimension($Yoteigyo)->setRowHeight(53.4);
				#セルの結合
				$sheet->mergeCells('A'.$Yoteigyo.':AN'.$Yoteigyo);
				$Yoteigyo++;#335
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(35.4);
				$sheet->setCellValue('B'.$Yoteigyo, "下記の通り訪問日を予定させていただきました。工事の際には居室インターホンの取替がございますので、ご在宅をお願いします。");
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':AM'.($Yoteigyo+1));
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getAlignment()->setWrapText(true);

				$Yoteigyo++;
				$Yoteigyo++;
				$Yoteigyo++;

				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('ff0000');

				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getFont()->setBold('true');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getFont()->setSize(18);
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getFont()->setUnderline('double');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getBorders()->getOutline()->setBorderStyle('thick');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getBorders()->getOutline()->getColor(18)->setARGB('FFFF0000');
				$sheet->setCellValue('H'.$Yoteigyo, "下記日程(弊社希望日時)についてご了承の有無を");

				#日程変更方法の画像を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/attention.png");
				$drawing->setHeight(60);
				$drawing->setOffsetX(5);
				$drawing->setOffsetY(15);
				$drawing->setCoordinates('C'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30.6);
				$sheet->mergeCells('H'.$Yoteigyo.':AM'.$Yoteigyo);
				$Yoteigyo++;
				$sheet->mergeCells('H'.$Yoteigyo.':AM'.$Yoteigyo);
				$sheet->getStyle('B'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->setCellValue('H'.$Yoteigyo, "○月○日までにご連絡お願いします");
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(35.4);
				$Yoteigyo++;
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':P'.$Yoteigyo);
				$sheet->mergeCells('Q'.$Yoteigyo.':V'.$Yoteigyo);
				$sheet->mergeCells('W'.$Yoteigyo.':AM'.$Yoteigyo);
				#セル内の位置の設定
				$sheet->getStyle('B'.$Yoteigyo.':P'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(52.2);

				$sheet->getStyle('Q'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->setCellValue('B'.$Yoteigyo, "○月×日");
				$sheet->setCellValue('Q'.$Yoteigyo, "△曜日");
				$sheet->setCellValue('W'.$Yoteigyo, "X:XX～X:XX");
				$Yoteigyo++;
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+16))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('ffe4d5');
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':AM'.$Yoteigyo);
				#セル内の位置の設定
				$sheet->getStyle('A'.$Yoteigyo.':AM'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->setCellValue('B'.$Yoteigyo, "【日程ご予約方法】");
				$Yoteigyo++;
				$sheet->mergeCells('D'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('A'.$Yoteigyo.':AM'.$Yoteigyo)
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
				$sheet->setCellValue('D'.$Yoteigyo, "●インターネットで日程の変更・確定をする場合（24時間受付）");
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('000000');
				$Yoteigyo++;
				$sheet->getStyle('D'.$Yoteigyo.':AK'.($Yoteigyo+8))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('FFFFFF');
				$sheet->getStyle('D'.$Yoteigyo.':AN'.($Yoteigyo+8))
				    ->getAlignment()->setWrapText(true);
				$sheet->mergeCells('D'.$Yoteigyo.':R'.($Yoteigyo+1));
				$sheet->mergeCells('S'.$Yoteigyo.':AK'.($Yoteigyo+1));
				$sheet->setCellValue('D'.$Yoteigyo, "①アイホンHPにアクセス");

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/aiphoneHP.png");
				$drawing->setHeight(50);
				$drawing->setOffsetX(2);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates('X'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/aiphoneQR.png");
				$drawing->setHeight(60);
				$drawing->setOffsetX(2);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates('AH'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30.0);

				$Yoteigyo++;
				$Yoteigyo++;

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/interphonebanner.png");
				$drawing->setHeight(30);
				$drawing->setOffsetX(2);
				$drawing->setOffsetY(5);
				$drawing->setCoordinates('X'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->mergeCells('D'.$Yoteigyo.':R'.($Yoteigyo+1));
				$sheet->mergeCells('S'.$Yoteigyo.':AK'.($Yoteigyo+1));
				$sheet->setCellValue('D'.$Yoteigyo, "②「インターホン工事予約システム」のバナーをクリック");
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$Yoteigyo++;
				$sheet->mergeCells('D'.$Yoteigyo.':R'.($Yoteigyo+1));
				$sheet->mergeCells('S'.$Yoteigyo.':W'.$Yoteigyo);
				$sheet->mergeCells('X'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('d3d3d3');
				$sheet->setCellValue('D'.$Yoteigyo, "③物件管理番号の入力");
				$sheet->setCellValue('S'.$Yoteigyo, "(物件管理番号)");
				$sheet->setCellValue('X'.$Yoteigyo, $editBukkenCD);
				$sheet->getStyle('S'.$Yoteigyo.':W'.($Yoteigyo+4))->getFont()->setSize(9);#フォントサイズをセット
				$sheet->getStyle('X'.$Yoteigyo.':AK'.($Yoteigyo+4))->getFont()->setSize(16);#フォントサイズをセット
				$sheet->getStyle('X'.$Yoteigyo.':AK'.($Yoteigyo+4))
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('X'.$Yoteigyo.':AK'.($Yoteigyo+4))
				    ->getFont()->getColor()->setARGB('0000CD');
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(10);
				$Yoteigyo++;
				$sheet->mergeCells('D'.$Yoteigyo.':R'.($Yoteigyo+2));
				$sheet->mergeCells('S'.$Yoteigyo.':W'.$Yoteigyo);
				$sheet->mergeCells('X'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('d3d3d3');
				$sheet->setCellValue('D'.$Yoteigyo, "④ユーザー名とパスワードの入力");
				$sheet->setCellValue('S'.$Yoteigyo, "(ユーザー名)");
				$sheet->setCellValue('X'.$Yoteigyo, '1234');
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$sheet->mergeCells('S'.$Yoteigyo.':W'.$Yoteigyo);
				$sheet->mergeCells('X'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('S'.$Yoteigyo.':AK'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('d3d3d3');
				$sheet->setCellValue('S'.$Yoteigyo, "(パスワード)");
				$sheet->setCellValue('X'.$Yoteigyo, '5678');
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$Yoteigyo+=1;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(10);
				$Yoteigyo+=2;
				#セルの結合s
				$sheet->mergeCells('D'.$Yoteigyo.':AK'.$Yoteigyo);
				$sheet->getStyle('A'.$Yoteigyo.':AM'.$Yoteigyo)
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
				$sheet->getStyle('D'.$Yoteigyo.':AK'.$Yoteigyo)->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('000000');
				$sheet->setCellValue('D'.$Yoteigyo, "●お電話で日程の変更・確定する場合（午前9時～午後5時30分）");
				$Yoteigyo++;

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/denwa1.png");
				$drawing->setHeight(40);
				$drawing->setOffsetX(20);
				$drawing->setOffsetY(20);
				$drawing->setCoordinates('D'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->mergeCells('D'.$Yoteigyo.':AK'.($Yoteigyo+2));
				$sheet->getStyle('D'.$Yoteigyo.':AK'.($Yoteigyo+2))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('FFFFFF');
				$Yoteigyo++;

				$Yoteigyo+=3;

				#作業員のイラストを貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/sagyoin.png");
				$drawing->setHeight(130);
				$drawing->setCoordinates('B'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$Yoteigyo++;

				$sheet->setCellValue('H'.$Yoteigyo, "・日程調整後、訪問日時確定のご案内を配布させていただきます。");
				$Yoteigyo++;
				$sheet->setCellValue('H'.$Yoteigyo, "・お預かりした個人情報は、当社の定めた個人情報保護方針に基づき厳重に管理し、");
				$Yoteigyo++;
				$sheet->setCellValue('H'.$Yoteigyo, "　今回のインターホン工事以外での目的では使用いたしません。");





			}else{
				#予定②_全住戸
	//			$sheet = $spreadsheet->getSheetByName('予定②_全住戸');
				#サンプルの文字を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/sample.png");
				$drawing->setHeight(80);
				$drawing->setOffsetY(2);
				$drawing->setCoordinates('O'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->setCellValue('AN'.$Yoteigyo , date('Y年')."　〇月　〇日");
				$Yoteigyo++;
				$sheet->setCellValue('C'.$Yoteigyo, $BukkenName);
				$sheet->getStyle('B'.$Yoteigyo.':M'.($Yoteigyo+1))
				    ->getFont()->setSize(16);
				$sheet->getStyle('C'.$Yoteigyo.':M'.($Yoteigyo+1))
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$Yoteigyo++;
				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop1);
				$sheet->setCellValue('J'.$Yoteigyo, "号室");



				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop2);
				$Yoteigyo++;
				$sheet->setCellValue('AN'.$Yoteigyo, $DispCompanyTop3);
				$Yoteigyo++;
				$sheet->getStyle('A'.$Yoteigyo)
				    ->getFont()->setBold('true');
				$sheet->setCellValue('A'.$Yoteigyo, "インターホン設備更新工事　ご訪問予定日のお知らせ");

				#境界線の設定
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getFont()->setSize(24);
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED);
 				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getTop()->getColor(18)->setARGB('FFFF0000');
 				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getBorders()->getBottom()->getColor(18)->setARGB('FFFF0000');

				#セル内の位置の設定
				$sheet->getStyle('A'.$Yoteigyo.':AN'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$sheet->getRowDimension($Yoteigyo)->setRowHeight(53.4);
				#セルの結合
				$sheet->mergeCells('A'.$Yoteigyo.':AN'.$Yoteigyo);
				$Yoteigyo++;#335
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(35.4);
				$sheet->setCellValue('B'.$Yoteigyo, "下記の通り訪問日を予定させていただきました。工事の際には居室インターホンの取替がございますので、ご在宅をお願いします。");
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':AM'.($Yoteigyo+1));
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getAlignment()->setWrapText(true);

				$Yoteigyo++;
				$Yoteigyo++;
				$Yoteigyo++;

				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('ff0000');

				#日程変更方法の画像を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/attention.png");
				$drawing->setHeight(60);
				$drawing->setOffsetX(5);
				$drawing->setOffsetY(15);
				$drawing->setCoordinates('C'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getFont()->setBold('true');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getFont()->setSize(18);
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+1))
				    ->getFont()->setUnderline('double');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getBorders()->getOutline()->setBorderStyle('thick');
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+2))
				    ->getBorders()->getOutline()->getColor(18)->setARGB('FFFF0000');
				$sheet->setCellValue('H'.$Yoteigyo, "下記日程(弊社希望日時)についてご了承の有無を");
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30.6);
				$sheet->mergeCells('H'.$Yoteigyo.':AM'.$Yoteigyo);
				$Yoteigyo++;
				$sheet->mergeCells('H'.$Yoteigyo.':AM'.$Yoteigyo);
				$sheet->getStyle('B'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->setCellValue('H'.$Yoteigyo, "○月○日までにご連絡お願いします");
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(35.4);
				$Yoteigyo++;
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':P'.$Yoteigyo);
				$sheet->mergeCells('Q'.$Yoteigyo.':V'.$Yoteigyo);
				$sheet->mergeCells('W'.$Yoteigyo.':AM'.$Yoteigyo);
				#セル内の位置の設定
				$sheet->getStyle('B'.$Yoteigyo.':P'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(52.2);

				$sheet->getStyle('Q'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->setCellValue('B'.$Yoteigyo, "○月×日");
				$sheet->setCellValue('Q'.$Yoteigyo, "△曜日");
				$sheet->setCellValue('W'.$Yoteigyo, "X:XX～X:XX");
				$Yoteigyo++;
				$sheet->getStyle('B'.$Yoteigyo.':AM'.($Yoteigyo+16))->getFill()
				    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB('ffe4d5');
				#セルの結合
				$sheet->mergeCells('B'.$Yoteigyo.':AM'.$Yoteigyo);
				#セル内の位置の設定
				$sheet->getStyle('A'.$Yoteigyo.':AM'.$Yoteigyo)
				    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$Yoteigyo++;
				#日程変更方法の画像を貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/toiawase_zenjuuko.png");
				$drawing->setHeight(350);
				$drawing->setCoordinates('C'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);
				$sheet->mergeCells('D'.$Yoteigyo.':AK'.$Yoteigyo);
				$Yoteigyo++;
				$Yoteigyo++;
				$Yoteigyo++;

				$Yoteigyo++;
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(10);
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$Yoteigyo++;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(30);
				$Yoteigyo+=1;
				$sheet->getRowDimension($Yoteigyo)->setRowHeight(10);
				$Yoteigyo+=2;
				$Yoteigyo++;


				$Yoteigyo++;
				$Yoteigyo+=3;

				#作業員のイラストを貼付
				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath("./images/sagyoin.png");
				$drawing->setHeight(130);
				$drawing->setCoordinates('B'.$Yoteigyo);
				$drawing->setWorksheet($sheet);
				unset($drawing);

				$Yoteigyo++;

				$sheet->setCellValue('H'.$Yoteigyo, "・日程調整後、訪問日時確定のご案内を配布させていただきます。");
				$Yoteigyo++;
				$sheet->setCellValue('H'.$Yoteigyo, "・お預かりした個人情報は、当社の定めた個人情報保護方針に基づき厳重に管理し、");
				$Yoteigyo++;
				$sheet->setCellValue('H'.$Yoteigyo, "　今回のインターホン工事以外での目的では使用いたしません。");

			}
		}




		$Kakuteigyo="380";
		$sheet->setBreak('A'.($Kakuteigyo-1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

##################################################################################################################
###########確定案内
##################################################################################################################

		#専有部工事日時変更資料の出力
		#確定①_日変
//		$sheet = $spreadsheet->getSheetByName('確定');	fce4dc
		$sheet->setCellValue('AN'.$Kakuteigyo, date('Y年')."　〇月　○日");
		$Kakuteigyo++;
		$sheet->setCellValue('A'.$Kakuteigyo, $BukkenName);
		$sheet->setCellValue('AN'.$Kakuteigyo, $DispCompanyTop1);
		$sheet->getStyle('A'.$Kakuteigyo.':I'.$Kakuteigyo)
		    ->getFont()->setSize(16);
		$Kakuteigyo++;
		$sheet->setCellValue('AN'.$Kakuteigyo, $DispCompanyTop2);
		$sheet->setCellValue('F'.$Kakuteigyo, "号室");
		#境界線を下にひく
		$sheet->getStyle('A'.$Kakuteigyo.':K'.$Kakuteigyo)
		    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		#フォントのサイズ変更
		$Kakuteigyo++;
		$sheet->setCellValue('AN'.$Kakuteigyo, $DispCompanyTop3);

		#サンプルの文字を貼付
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setPath("./images/sample.png");
		$drawing->setHeight(80);
		$drawing->setOffsetY(2);
		$drawing->setCoordinates('O'.$Kakuteigyo);
		$drawing->setWorksheet($sheet);
		unset($drawing);

		$Kakuteigyo++;
		$sheet->getRowDimension($Kakuteigyo)->setRowHeight(45);
		$Kakuteigyo++;
		$sheet->getRowDimension($Kakuteigyo)->setRowHeight(60);

		$sheet->setCellValue('A'.$Kakuteigyo, "インターホン設備更新工事のご訪問日のお知らせ");
		$sheet->mergeCells('A'.$Kakuteigyo.':AN'.$Kakuteigyo);
		$sheet->getStyle('A'.$Kakuteigyo)
		    ->getFont()->setSize(22);
		$sheet->getStyle('A'.$Kakuteigyo)->getFill()
		    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		    ->getStartColor()->setARGB('fce4dc');
		$sheet->getStyle('A'.$Kakuteigyo)
		    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A'.$Kakuteigyo)
		    ->getFont()->setBold('true');
		$Kakuteigyo++;
		$Kakuteigyo++;
		$sheet->getStyle('G'.$Kakuteigyo)
		   ->getAlignment()->setWrapText(true);
		$sheet->mergeCells('G'.$Kakuteigyo.':AH'.($Kakuteigyo+2));
		$temp_str = "この度、インターホン取替工事日を決めさせていただきましたので、ご連絡させていただきます。";
		$temp_str .= "下記の日時にお伺いいたしますので、当日はご在宅をお願いいたします。";
		$sheet->setCellValue('G'.$Kakuteigyo, $temp_str);

		$Kakuteigyo++;
		$Kakuteigyo++;
		$Kakuteigyo++;
		$Kakuteigyo++;

		#サンプルの文字を貼付
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setPath("./images/waku_kakutei.png");
		$drawing->setHeight(370);
		$drawing->setCoordinates('F'.$Kakuteigyo);
		$drawing->setWorksheet($sheet);
		unset($drawing);

		$Kakuteigyo++;

		$sheet->getStyle('G'.$Kakuteigyo.':AH'.$Kakuteigyo)
		    ->getFont()->setSize(16);
		$sheet->mergeCells('G'.$Kakuteigyo.':AH'.$Kakuteigyo);
		$sheet->setCellValue('G'.$Kakuteigyo , "【工事日程】");
		$sheet->getStyle('G'.$Kakuteigyo)
		    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$Kakuteigyo++;
		$Kakuteigyo++;
		
		$sheet->getRowDimension($Kakuteigyo)->setRowHeight(58);
		$sheet->mergeCells('G'.$Kakuteigyo.':V'.$Kakuteigyo);
		$sheet->mergeCells('W'.$Kakuteigyo.':AH'.$Kakuteigyo);
		$sheet->getStyle('G'.$Kakuteigyo.':AH'.$Kakuteigyo)
		    ->getFont()->setSize(30);
		$sheet->getStyle('G'.$Kakuteigyo.':AH'.($Kakuteigyo+3))
		    ->getFont()->setBold('true');
		$sheet->getStyle('G'.$Kakuteigyo)
		    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('G'.$Kakuteigyo.':AH'.($Kakuteigyo+1))
		    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
		$sheet->setCellValue('G'.$Kakuteigyo, "○月×日");
		$sheet->setCellValue('W'.$Kakuteigyo, "△曜日");
		$Kakuteigyo++;
		$sheet->getStyle('G'.$Kakuteigyo.':AH'.$Kakuteigyo)
		    ->getFont()->setSize(30);
		$sheet->mergeCells('G'.$Kakuteigyo.':AH'.$Kakuteigyo);
		$sheet->getStyle('G'.$Kakuteigyo)
		    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->setCellValue('G'.$Kakuteigyo, "XX:XX～XX:XX");
		$sheet->getRowDimension($Kakuteigyo)->setRowHeight(58);
		$Kakuteigyo++;
		$Kakuteigyo++;
		$sheet->getStyle('G'.$Kakuteigyo.':AH'.$Kakuteigyo)
		    ->getFont()->setSize(14);
		$sheet->mergeCells('G'.$Kakuteigyo.':AH'.$Kakuteigyo);
		$sheet->getStyle('G'.$Kakuteigyo)
		    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getRowDimension($Kakuteigyo)->setRowHeight(50);
		$sheet->setCellValue('G'.$Kakuteigyo, "工事期間中はご不便をお掛けいたしますが、\nご協力の程、よろしくお願い申し上げます。");
		$sheet->getStyle('G'.$Kakuteigyo)
		   ->getAlignment()->setWrapText(true);

		$Kakuteigyo++;
		$Kakuteigyo++;
		$Kakuteigyo++;

		$sheet->mergeCells('G'.$Kakuteigyo.':AH'.($Kakuteigyo+1));
		$sheet->getStyle('G'.$Kakuteigyo)
		   ->getAlignment()->setWrapText(true);
		$sheet->setCellValue('G'.$Kakuteigyo, "※お預かりした個人情報は、当社の定めた個人情報保護方針に基づき厳重に管理し、今回のインターホン工事以外での目的では使用しません。");

		$Kakuteigyo++;
		$Kakuteigyo++;

		$sheet->getStyle('A'.$Kakuteigyo.':AN'.$Kakuteigyo)
		   ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

		$Kakuteigyo++;

		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setPath("./images/kakutei_toiawase.png");
		$drawing->setHeight(150);
		$drawing->setCoordinates('G'.$Kakuteigyo);
		$drawing->setWorksheet($sheet);
		unset($drawing);

	//}

	###★ Excel(.xlsx)としてtFileFに登録する
	$writer = new XlsxWriter($spreadsheet);
	$Day = date('YmdHis');
	$writer->save( './tmp/a'.$Day.'.xlsx');

	// 画像の取得
	$image_path = './tmp/a'.$Day.'.xlsx' ;
	$img_file = file_get_contents( $image_path );

	//画像を保存するSQL文の実行
	$myFile = new File($myDB);

	$myFile->FileCD = -1;
	$myFile->BukkenCD = $editBukkenCD;
	$myFile->SekoStatus = 5; #5作成ファイル
	$myFile->P001 = '工事案内_'.date('Ymd_His').'.xlsx'; #ファイル名
	$myFile->P002 = 'xlsx';  #拡張子

	$myFile->File = $img_file;
	$myFile->Creator = $loginUserCD;
	$myFile->Updater = $loginUserCD;

	if (!$myFile->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	#テンポラリーファイルを削除する。
	$Command = "rm -f ".$image_path;
	shell_exec($Command);
	###★ Excel(.xlsx)としてtFileFに登録する End


	//ダウンロード用
	//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
	header("Content-Description: File Transfer");
	$wFileName = "工事案内_".$fileName_add."_".$BukkenName."_".date('Ymd').".xlsx" ;
	$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

	header("Content-Disposition: attachment; filename=".$wFileName);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	ob_end_clean(); //バッファ消去

	$writer = new XlsxWriter($spreadsheet);
	$writer->save('php://output');

?>