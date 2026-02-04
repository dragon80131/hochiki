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


	#Postデータ取得
	$wConstTime = SPFWParameter::getValues('wConstTime');		#案内資料上の作業時間
	$wKirikaehoho = SPFWParameter::getValues('wKirikaehoho');	#切替方法 0:停止 1:平行稼働
	$wKirikaeHeikoEizoriyo = SPFWParameter::getValues('wKirikaeHeikoEizoriyo');	#並行稼働時既設映像幹線利用 0:なし 1:あり 201903ADDGOE
	$wJikaho = SPFWParameter::getValues('wJikaho');				#自火報連動 0:なし　1:あり(既設流用）　2:あり（交換）
	$wKasaiHeya = SPFWParameter::getValues('wKasaiHeya');		#火災抵抗器交換部屋立入り 0:なし 1:あり
	$wKansenKoji = SPFWParameter::getValues('wKansenKoji');		#0:パイプシャフト渡り 1:玄関子機渡り 2:部屋渡り 3:1:1　←変更幹線構築方法 0:既設流用  1:新規幹線工事 2:部屋渡り
	$wGasKoji = SPFWParameter::getValues('wGasKoji');			#ガス漏れ警報器連動 0:なし 1:あり(既設流用）　2:あり（交換）
	$wBohanKoji = SPFWParameter::getValues('wBohanKoji');		#防犯センサー連動 0:なし 1:１階住戸のみ 2:全住戸 3:設置住戸のみ
	$wRosuiKoji = SPFWParameter::getValues('wRosuiKoji');		#漏水センサー連動 0:なし 1:あり(既設流用）　2:あり（交換）
	$wTakuhai = SPFWParameter::getValues('wTakuhai');		#宅配連動 0:なし 1:あり	201902 ADDGOE
	$wAnswer = SPFWParameter::getValues('wAnswer');		#専有部工事日時変更受付方法	
	$wWEBRecept = SPFWParameter::getValues('wWEBRecept');		#WEB受付
	$wShiharai = SPFWParameter::getValues('wShiharai');			#オプション支払い方法 配列
	$wShiharaiConveni = SPFWParameter::getValues('wShiharaiConveni');	#コンビニ払いの詳細指定　0：標準/1:上限あり	201903 ADDGOE
	$wOyakiPanel = SPFWParameter::getValues('wOyakiPanel');		#親機の化粧パネル 0:有 1:無
	$wKokiPanel = SPFWParameter::getValues('wKokiPanel');		#子機の化粧パネル 0:有 1:無
	$wOPUketuke = SPFWParameter::getValues('wOPUketuke');		#オプション受付　１：アンケート
	$wTagSuu = SPFWParameter::getValues('wTagSuu');				#タグ標準　渡し本数
	$wOwnerTagSuu = SPFWParameter::getValues('wOwnerTagSuu');	#オーナー渡しタグ　渡し本数 201902 ADDGOE
	$wAnnaijyoType = SPFWParameter::getValues('wAnnaijyoType');	#作成する案内状の種類 0:通常版 1:簡易版
	$DispCompanyTop1 = SPFWParameter::getValues('DispCompanyTop1');		#表示する会社名1
	$DispCompanyTop2 = SPFWParameter::getValues('DispCompanyTop2');		#表示する会社名2
	$DispCompanyTop3 = SPFWParameter::getValues('DispCompanyTop3');		#表示する会社名3
	$wDispCompanyBottomChk1 = SPFWParameter::getValues('DispCompanyBottomChk1');	#ページ下部に表示する会社
	$wDispCompanyBottomChk2 = SPFWParameter::getValues('DispCompanyBottomChk2');	#ページ下部に表示する会社
	$wDispCompanyBottomChk3 = SPFWParameter::getValues('DispCompanyBottomChk3');	#ページ下部に表示する会社
	$wDispCompanyBottom3 = SPFWParameter::getValues('DispCompanyBottom3');	#ページ下部に表示する会社　その他
	$wDispCompanyBottom1FLG = SPFWParameter::getValues('DispCompanyBottom1FLG');	#ページ下部に表示する会社　電話番号表示フラグ1　1：する　0：しない
	$wDispCompanyBottom2FLG = SPFWParameter::getValues('DispCompanyBottom2FLG');	#ページ下部に表示する会社　電話番号表示フラグ2
	$wDispCompanyBottom3FLG = SPFWParameter::getValues('DispCompanyBottom3FLG');	#ページ下部に表示する会社　電話番号表示フラグ3
	$wDispCompanyBottomTEL3 = SPFWParameter::getValues('DispCompanyBottomTEL3');	#ページ下部に表示する会社　電話3
	$wSagyoinArr = SPFWParameter::getValues('wSagyoin');	#作業員 ベスト/腕章　配列
	$wShiteiVest = SPFWParameter::getValues('wShiteiVest');	#指定 ベスト
	$wShiteiVestOther = SPFWParameter::getValues('ShiteiVestOther');	#指定 ベストその他会社名

#echo "<br>".__LINE__."行目:".$wShiteiVest;

	
	$wTagKirikae = SPFWParameter::getValues('wTagKirikae');	#タグ切替タイミング1：着工日　2：工事終了日
	$wAutoLock = SPFWParameter::getValues('wAutoLock');	#集合玄関機有無　1:有　2:無

	$wTEST = SPFWParameter::getValues('TEST');
	
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

		if($wAutoLock == 1 ){
			$DispAutoLock = "エントランス・";
			$DispAutoLock2 = "オートロック解錠機能、";
			$DispAutoLock3 = "オートロック解錠・";
		}
		#$ShoboTokurei = $myBukken->ShoboTokurei;#0:なし　1:170　2:220　3:220
	}
	
	#入力値をDB格納
	$myBukken->AutoLock = $wAutoLock;
	
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

		$KojiName = $myKoji->KojiName;
		$KojiShozokuName = $myKoji->KojiShozokuName;
		$KojiTantoName = $myKoji->KojiTantoName;
		$TantoTEL = $myKoji->KojiShozokuTEL;

		$GyosyaTantoCD1 = $myKoji->GyosyaTantoCD1;

		$ShoboTokurei = $myKoji->ShoboTokurei;#0:なし　1:170　2:220　3:220
		$ShoboSikenhoho = $myKoji->ShoboSikenhoho;	#0:着工前後　1:終了後　2:都度

		$Kanrisitu = $myKoji->Kanrisitu;#0:なし　1:あり 201903 ADD GOE

		$SekoShutai = $myKoji->SekoShutai; //201902 ADD GOE

		$GyosyaData = getGyosyaData($myDB, $GyosyaTantoCD1);
		$GyosyaName = $GyosyaData['GyosyaName'];

		$weekarray = array("(日)","(月)","(火)","(水)","(木)","(金)","(土)");
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
		$AnnaiDate .= $weekarray[ date('w',strtotime( $myKoji->AnnaiDate )) ];
		$ReceptionDate = date('Y年n月j日',strtotime( $myKoji->ReceptionDate ));	#受付締切日
		$ReceptionDate .= $weekarray[ date('w',strtotime( $myKoji->ReceptionDate )) ];
		$ReceptionDate2 = date('Y-n-j',strtotime( $myKoji->ReceptionDate ));	#受付締切日

		$WakuPattern = $myKoji->WakuPattern;
		$AnshoNo = $myKoji->AnshoNo;	#//201902 ADD GOE
		$AnshoNoKojichu = $myKoji->AnshoNoKojichu;
		$CurrentNyukan = $myKoji->CurrentNyukan;
		$arrayCurrentNyukan = SPFWTools::decodePluralValue($CurrentNyukan);#配列へ変換
		if (in_array("2",$arrayCurrentNyukan)) { // 従前の入館方法にノンタッチタグ:2 が含まれるかどうか
			$wTagKoji = 1;
		}

		#オプションある場合
		if( $myKoji->OP1DeviceCD || $myKoji->OPZiyuu1) { 
			$IfOP = TRUE;

			#オプションある場合のみ有効な画面の値をDBに登録
			#オプション支払い方法
			if(in_array("コンビニ",$wShiharai)){
				if($wShiharaiConveni == 1){
					$wShiharai[] = '上限あり';
				}
			}
			$myKoji->Shiharai = SPFWTools::encodePluralValue($wShiharai); // 配列を文字列に変換

			#オプション受付方法
			$myKoji->OPUketuke = $wOPUketuke;



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
	$myKoji->ConstTime = $wConstTime;
	$myKoji->Kirikaehoho = $wKirikaehoho;
	
	if($wKirikaehoho == '1'){	#並行稼働の時のみ 既設映像幹線利用の情報も更新 201903ADDGOE
		$myKoji->KirikaeHeikoEizoriyo = $wKirikaeHeikoEizoriyo;
	}
	$myKoji->Jikaho = $wJikaho;
	$myKoji->KasaiHeya = $wKasaiHeya;
	$myKoji->KansenKoji = $wKansenKoji;
	$myKoji->GasKoji = $wGasKoji;
	$myKoji->BohanKoji = $wBohanKoji;
	$myKoji->RosuiKoji = $wRosuiKoji;
	$myKoji->Takuhai = $wTakuhai;
//	echo __LINE__.$wAnswer;
	$myKoji->Answer = $wAnswer;
	$myKoji->WEBRecept = $wWEBRecept;
	
	$myKoji->TagSuu = $wTagSuu;
	$myKoji->OwnerTagSuu = $wOwnerTagSuu;
	$myKoji->TagKirikae = $wTagKirikae;
	
	###資料上部に表示する会社名を登録###
	#DB1カラムに登録するため下記形状に変換して登録
	#|1番目に表示する会社名|2番目|3番目|
	$DispCompanyTopArr = array($DispCompanyTop1,$DispCompanyTop2,$DispCompanyTop3);
	# 配列を文字列に変換
	$myKoji->DispCompanyTop = SPFWTools::encodePluralValue($DispCompanyTopArr); 


	###問合せ先を登録###
	#DB1カラムに登録するため下記形状に変換して登録
	#|管理会社|1:電話番号表示する 0:しない|アイホン|1:電話番号表示する0:しない|その他会社名|1:電話番号表示する0:しない|電話番号|

	$ToiawasewakiDispArr = array();
	
	#管理会社がONの場合
	if($wDispCompanyBottomChk1 == '1'){
		$ToiawasewakiDispArr[0] = '管理会社';
	}else{
		$ToiawasewakiDispArr[0] = '';
	}
	$ToiawasewakiDispArr[1] = $wDispCompanyBottom1FLG;	#電話番号を出力　1：する　0：しない

	#アイホンさんがONの場合
	if($wDispCompanyBottomChk2 == '1'){
		$ToiawasewakiDispArr[2] = 'アイホン';
	}else{
		$ToiawasewakiDispArr[2] = '';
	}
	$ToiawasewakiDispArr[3] = $wDispCompanyBottom2FLG;	#電話番号を出力　1：する　0：しない

	#その他がONの場合
	if($wDispCompanyBottomChk3 == '1'){
		$ToiawasewakiDispArr[4]= $wDispCompanyBottom3;	#その他の会社名
	}else{
		$ToiawasewakiDispArr[4] = '';
	}
	$ToiawasewakiDispArr[5] = $wDispCompanyBottom3FLG;	#電話番号を出力　1：する　0：しない
	$ToiawasewakiDispArr[6] = $wDispCompanyBottomTEL3;	#その他電話番号
	
	# 配列を文字列に変換
	$myKoji->ToiawasewakiDisp = SPFWTools::encodePluralValue($ToiawasewakiDispArr); 

	$myKoji->Sagyoin = SPFWTools::encodePluralValue($wSagyoinArr); 
	$myKoji->ShiteiVestCD = $wShiteiVest ;	#指定ベスト
	$myKoji->ShiteiVestOther = $wShiteiVestOther ;	#指定ベストその他

	if (!$myKoji->executeUpdate()){
		trigger_error("executeUpdate(myKoji) Failed.", E_USER_ERROR);
	}
	unset($myKoji);

#	echo $GyosyaTantoCD1;
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "gt.UserCD, ";		#0業者担当CD
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
	$ShiharaiSitei = $myListObject->GetValue(0, 1);

	unset($myListObject);

	#ノンタッチシステム導入FlgON
	#オートロックあり 且つ リニューアル後のタグアリ　且つ　リニューアル前のタグなし
	if($wAutoLock == "1" and strstr($wRNNyukan ,'2') and !strstr($wCurrentNyukan ,'2')){
		$nonTouchFLG = true;
	} 






	//ライブラリ読み込み
	//require_once './PHPWord/PHPWord.php';
	require_once ('./PHPWord/PHPWord.php');



	$PHPWord = new PHPWord();

	//テンプレート読み込み
	$template_filepath = "./template/koji_annai.docx";
	$document = $PHPWord->loadTemplate($template_filepath);

	/*
	###文字化け対処Start
	$DispTaioNotes = $BukkenName ;
	$BK1 =    mb_convert_encoding($DispTaioNotes, 'sjis', 'euc-jp');
	$BK2 =    mb_convert_encoding($DispTaioNotes, 'sjis', 'utf-8');
	$BK3 =    mb_convert_encoding($DispTaioNotes, 'euc-jp', 'sjis');
	$BK4 =    mb_convert_encoding($DispTaioNotes, 'euc-jp', 'utf-8');
	$BK5 =    mb_convert_encoding($DispTaioNotes, 'utf-8', 'sjis');
	$BK6 =    mb_convert_encoding($DispTaioNotes, 'utf-8', 'euc-jp');
	###文字化け対処End
	*/
/*	$document->setValue('PackName', $PackName);//パック名
	$document->setValue('Today', $Today);//日付
	$document->setValue('MitusakiName', $wkname);//契約相手方名
	$document->setValue('Bsyomei', $Bsyomei);//部署名
	$document->setValue('TantoName', $TantoName);//担当者名
*/
	/*
	$document->setValue('Value4', 'Earth');
	$document->setValue('Value5', $BukkenName );
	$document->setValue('Value6', $Today );
	*/
	/*20170721コメントアウト
	$document->setValue('Value1', $BukkenName);
	$document->setValue('Value2', $PackName);
	$document->setValue('Value3', 'システム');
	$document->setValue('Value4', number_format(100000));
	$document->setValue('Value5', number_format(108000));
	*/



	/********ファイル書き出し処理*********/
	//ファイルの名前
	$fname = "工事案内_".$BukkenName."_".date('Ymd').".docx" ;
	$fname = mb_convert_encoding($fname, 'sjis-win', 'UTF-8');

	//ファイルのサーバでの保存先
	$fpath = './template/'.$fname;

	#$document->save($fpath);
	$document->save("./template/".$fname);

	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fname");



	ob_end_clean();
	readfile($fpath);

	//ファイル削除
	unlink($fpath);


	unset($document);
	unset($PHPWord);



?>
