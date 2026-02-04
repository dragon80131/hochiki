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
	include_once _CLS_DIR . "SPUSKoji.cls";

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

	unset($myUser);


	########################################################
	# その他、日程
	########################################################
	$OtherwiseLoop = count($OTHERWISEDATE); #489.properties
	for($i = 0; $i < $OtherwiseLoop; $i++){
		$OtherwiseCD[$i] = $i;
		if($i == 0){
			$IfDate[$i] = FALSE;
		}else{
			$IfDate[$i] = TRUE;
		}
	}

	########################################################
	# 物件名取得
	########################################################

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = ".$editBukkenCD , "") || $myBukken->RecCnt != 1) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	$BukkenName = $myBukken->BukkenName;
	$AutoLock = $myBukken->AutoLock;#1:あり　2:なし

	unset($myBukken);


	########################################################
	# 工事情報抽出
	########################################################

	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "")){
		trigger_error("Getting myKoji Failed.", E_USER_ERROR);
	}

	if ($myKoji->RecCnt != 1) {

		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	} else {

		// 20190909add 物件基本情報を登録したかどうか 1:登録
		if ($myKoji->BukkenTourokuStatus != 1) {
			#物件基本情報登録がまだ
			$ErrorString = array();
			$ErrorString[] = "物件基本情報登録を完了させてください。";
			$ErrorLoop = count($ErrorString);
			$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
			exit;
		}
		if($myKoji->KyoyoStartDate)
		$wKyoyoStartDate = date('Y-m-d' , strtotime( $myKoji->KyoyoStartDate )) ;#カラム名注意
		$wKyoyoEndDate = $myKoji->KyoyoEndDate;
		$wSenyuStartDate = $myKoji->SenyuStartDate;
		$wSenyuEndDate = $myKoji->SenyuEndDate;

		$Holiday = $myKoji->Holiday1;
		$wHoliday = SPFWTools::decodePluralValue($Holiday);
		sort($wHoliday);
		$wHolidaySuu = count($wHoliday);

		$wAnnaiDate = $myKoji->AnnaiDate;
		$wReceptionDate = $myKoji->ReceptionDate;
		$wKakuteiDate = $myKoji->KakuteiDate;


		$KyukoTable = "<table class='table table-bordered'><tr>";
		for($i=0;$i<count($wHoliday);$i++){
			if($i%5==0)
				$KyukoTable .= "</tr><tr>";
			$KyukoTable .= "<td><b>".$wHoliday[$i]."</b></td>";

		}
		$KyukoTable .= "<tr></table>";

		#初期表示
		if($wAnnaiDate == "0000-00-00" or $wAnnaiDate == NULL) 			$wAnnaiDate = date( 'Y-m-d', strtotime("-25 day", strtotime($wSenyuStartDate) ) ); // 案内配布日
		if($wReceptionDate == "0000-00-00" or $wReceptionDate == NULL) 	$wReceptionDate = date( 'Y-m-d', strtotime("-11 day", strtotime($wSenyuStartDate) ) ); // 受付締切日
		if($wKakuteiDate == "0000-00-00" or $wKakuteiDate == NULL) 		$wKakuteiDate = date( 'Y-m-d', strtotime( "-7 day", strtotime($wSenyuStartDate) ) ); // 確定案内配布日



		// その他、工程表に記載する日程 5項目
		if($myKoji->Otherwise6Flg==1) $wOtherwise6FlgChecked = "checked";
		if($myKoji->Otherwise6) $wOtherwise6 = $myKoji->Otherwise6;

		if($myKoji->DateS6 and $myKoji->DateS6 != "0000-00-00" ) $wDateS6 = $myKoji->DateS6;
		if($myKoji->DateE6 and $myKoji->DateE6 != "0000-00-00" ) $wDateE6 = $myKoji->DateE6;

		if($myKoji->Otherwise7Flg==1) $wOtherwise7FlgChecked = "checked";
		if($myKoji->Otherwise7) $wOtherwise7 = $myKoji->Otherwise7;
		if($myKoji->DateS7 and $myKoji->DateS7 != "0000-00-00" ) $wDateS7 = $myKoji->DateS7;
		if($myKoji->DateE7 and $myKoji->DateE7 != "0000-00-00" ) $wDateE7 = $myKoji->DateE7;

		if($myKoji->Otherwise8Flg==1) $wOtherwise8FlgChecked = "checked";
		if($myKoji->Otherwise8) $wOtherwise8 = $myKoji->Otherwise8;
		if($myKoji->DateS8 and $myKoji->DateS8 != "0000-00-00" ) $wDateS8 = $myKoji->DateS8;
		if($myKoji->DateE8 and $myKoji->DateE8 != "0000-00-00" ) $wDateE8 = $myKoji->DateE8;

		if($myKoji->Otherwise9Flg==1) $wOtherwise9FlgChecked = "checked";
		if($myKoji->Otherwise9) $wOtherwise9 = $myKoji->Otherwise9;
		if($myKoji->DateS9 and $myKoji->DateS9 != "0000-00-00" ) $wDateS9 = $myKoji->DateS9;
		if($myKoji->DateE9 and $myKoji->DateE9 != "0000-00-00" ) $wDateE9 = $myKoji->DateE9;

		if($myKoji->Otherwise10Flg==1) $wOtherwise10FlgChecked = "checked";
		if($myKoji->Otherwise10) $wOtherwise10 = $myKoji->Otherwise10;
		echo $myKoji->Otherwise10;
		if($myKoji->DateS10 and $myKoji->DateS10 != "0000-00-00" ) $wDateS10 = $myKoji->DateS10;
		if($myKoji->DateE10 and $myKoji->DateE10 != "0000-00-00" ) $wDateE10 = $myKoji->DateE10;

#201902 ADD GOE
		$wConstTime = $myKoji->ConstTime;	#案内資料上の作業時間
		if ($wConstTime == NULL){	#未設定の場合は60をセット
			$wConstTime = '60';
		}

		if($myKoji->KojiBiko == NULL){	#備考に入力がない場合は初期値を表示
			$KojiBiko1_Def = "・事前確認作業は、基本的にエントランス・管理室・各住戸玄関先・パイプシャフト・共用廊下です。\n";
			$KojiBiko2_220gou = "・消防特例の場合、消防届出書類作成のため、事前確認作業時に竣工図面または竣工時消防設置届の借用をお願い申し上げます。\n";
			$KojiBiko3_Def = "・工事期間中は管理室の入室が必要となります。管理員様不在期間の管理室入室にあたり、別途ご相談をお願い申し上げます。\n";
			$KojiBikoo4_Def = "・1部屋あたりの工事時間は、".$wConstTime."分程度を予定しております。（状況により前後します）\n";
			$KojiBikoo5_autlockAndNoheiko = "・共用部工事開から専有部工事完了までインターホンの呼出・通話・開錠・警報通報機能が使用できません。\n";
			$KojiBiko6_Def = "・工事期間中、居住者様は従来通りの方法で入館が可能です。\n";
			$KojiBiko7_220gou = "・工事完了後、所轄消防立会いによる消防検査が必要となる場合がございます。入室しての検査となる場合がございますので、ご協力の程、宜しくお願い申し上げます。\n";

			if ($myKoji->ShoboTokurei <= 1){ #消防特例220号以外　文言削除
				$KojiBiko2_220gou = '';
				$KojiBiko7_220gou = '';
			}

			if ($AutoLock != 1 OR $myKoji->Kirikaehoho == 1){ #オートロックあり以外又は並行稼働文言削除
				$KojiBikoo5_autlockAndNoheiko = '';
			}

			$wKojiBiko = $KojiBiko1_Def.$KojiBiko2_220gou.$KojiBiko3_Def.$KojiBikoo4_Def.$KojiBikoo5_autlockAndNoheiko.$KojiBiko6_Def.$KojiBiko7_220gou.$myKoji->KojiBiko;	
		}else{	#入力されて以降はDBの備考をそのまま表示
			$wKojiBiko = $myKoji->KojiBiko;
		}
#ADD END

	}
	unset($myKoji);


	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_zentaikoteihyo_form.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
