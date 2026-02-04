<?php
	include_once "/var/www/hochiki/SPFW/inc/setting.properties";
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
	include_once _CLS_DIR . "SPUSSchedule.cls";
	include_once _CLS_DIR . "SPUSSagyoin.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 入力チェック
	########################################################

	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	// 物件カラー
	$ColorArray = array ("cyan","lime","yellow","orange","deeppink",
				"violet","darkviolet","magenta","darkcyan","turquoise",
				"darkturquoise","yellowgreen","limegreen","forestgreen","midnightblue",
				"moccasin","brown","lightsalmon","red","blue");

	########################################################
	# 日付取得
	########################################################


$yearlist = $editYear ;
$monthlist = $editMonth ;

	//現在の年月
	$now = time();
	$yearnow = date("Y", $now);
	$monthnow = date("n", $now);

	$YearLoop = 3; // とりあえず3年分選択可能

	for ($i = 0; $i < $YearLoop; $i++) {

		if ($i == 0) {
			$year[$i] = $yearnow;
		}else {
			$year[$i] = $yearnow + $i;
		}

		if ($yearlist <> "") {
			// 選択された年を表示
			$yearselected[$i] = ($year[$i] == $yearlist) ? "selected" : NULL;
		} else {
			// 現在の年を表示
			$yearselected[$i] = ($year[$i] == $yearnow) ? "selected" : NULL;
		}

		if($yearselected[$i] == "selected"){
			$tYear = $year[$i];	// 表示したい年
		}
	}


	$MonthLoop = 12; // 固定 12か月

	for ($i = 0; $i < 12; $i++) {
		$month[$i] = $i+1;

		if ($monthlist <> "") {
			// 選択された月を表示
			$monthselected[$i] = ($i+1 == $monthlist) ? "selected" : NULL;
		} else {
			// 現在の月を表示
			$monthselected[$i] = ($i+1 == $monthnow) ? "selected" : NULL;
		}

		if($monthselected[$i] == "selected"){
			$tMonth = $i+1;	// 表示したい月
		}
	}

	SPFWTemplate::setValue('edityear', "");
	SPFWTemplate::setValue('editmonth', "");


	// 表示する月の最終日を取得
	$d = new DateTime('last day of' .$tYear .'-' .$tMonth);
	$lastday = substr($d->format('Y-m-d'),-2);

	$DayLoop = $lastday;
	$daycount = $lastday;

	$WeekLoop = $lastday;

	for ($i = 0; $i < $WeekLoop; $i++){

		$Day = $i + 1;

		// 日付の表示
		$dd[$i] = sprintf("%02d",$Day);

		// 曜日の表示
		$datetime = new DateTime();
		$datetime->setDate($tYear, $tMonth, $Day);
		$weekarray = array("日", "月", "火", "水", "木", "金", "土");
		$w = $datetime->format('w');
		$dw[$i] = $weekarray[$w];

		if ($dw[$i] == "日"){
			$dc[$i] = "lightpink";
		}
	}

	//28日までは必ず存在するので、それ以降の表示・・・
	if ($lastday == "31"){
		$Ifday29 = true;
		$Ifday30 = true;
		$Ifday31 = true;
	} else if ($lastday == "30") {
		$Ifday29 = true;
		$Ifday30 = true;
	} else if ($lastday == "29") {
		$Ifday29 = true;
	}


	########################################################
	# 物件一覧表示
	########################################################

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";

		$sql .= "BukkenCD , ";
		$sql .= "ClientCD, ";
		$sql .= "BukkenName, ";
		$sql .= "BukkenColor , ";
		$sql .= "Kosu , ";
		$sql .= "TantoCD , ";
		$sql .= "BukkenNotes  ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tBukkenM";
	$sql .= " WHERE BukkenCD > 0 AND MukouFlg = FALSE";

	$myListObject->Condition = $sql;
	$myListObject->Order = "BukkenCD";
	$myListObject->Limit = "allpage";
	
	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	$BukkenLoop = $myListObject->Rows;

	for ($i = 0; $i < $BukkenLoop  ; $i++) {# 1 物件For

	$BukkenCD[$i] = $myListObject->GetValue($i, 0);

	$wBukkenCD[$i] = $BukkenCD[$i] ;

	$ClientCD[$i] = $myListObject->GetValue($i, 1);
	$BukkenName[$i] = $myListObject->GetValue($i, 2);
	$BukkenColor[$i] = $myListObject->GetValue($i, 3);

	$wBukkenColor[$i] = $ColorArray[$BukkenColor[$i]];

	$Kosu[$i] = $myListObject->GetValue($i, 4);
	$TantoCD[$i] = $myListObject->GetValue($i, 5);
	$BukkenNotes[$i] = $myListObject->GetValue($i, 6);

#####物件情報がループしている中につっこんでみた。
		$myListObject2 = new SPFWListObject($myDB);

		$sql = "SELECT ";
		$sql .= "SagyoDate, ";
		$sql .= "SagyoinCD, ";
		$sql .= "BukkenCD , ";
		$sql .= "ScheduleNotes , ";
		$sql .= "BukkenCD  ";

		$myListObject2->SelectSQL = $sql;
		$sql = " FROM tScheduleM";
		$sql .= " WHERE ScheduleCD > 0 AND MukouFlg = FALSE and SagyoinCD = ".$editSagyoinCD." and BukkenCD = ".$BukkenCD[$i];

		$myListObject2->Condition = $sql;
		$myListObject2->Order = "BukkenCD,SagyoinCD ";#物件CD,作業員順に並ぶ
		$myListObject2->Limit = "allpage";


		if (!($myListObject2->GetList(1)))
			trigger_error("Getting Menu List Failed.", E_USER_ERROR);

		$ScheduleLoop = $myListObject2->Rows;

		for ($j = 0; $j < $ScheduleLoop ; $j++) {

			$SagyoDate[$j] = $myListObject2->GetValue($j, 0);

			$SagyoYear[$j] = substr($SagyoDate[$j],0,4);
			$SagyoMonth[$j] = substr($SagyoDate[$j],5,2);
			$SagyoDay[$j] = substr($SagyoDate[$j],-2) ; #"日にち部分
			if ( $SagyoYear[$j] == $tYear And $SagyoMonth[$j] == $tMonth ){ #選択された年月と同じなら
			#1〜31のチェックを組み立てる。

				${"Bcolor".$SagyoDay[$j]}[$i] = $ColorArray[$BukkenColor[$i]];
## SSChecked.物件CD.日付
				${"SSChecked".$SagyoDay[$j]}[$i] = "checked" ;


			}#ifのEnd
		}# FOrのEnd


####物件情報がループしている中につっこんでみた。End


	}#1 For End
	SPFWTemplate::setValue("tMonth", $tMonth);
	SPFWTemplate::setValue("editMonth", $tMonth);





	########################################################
	# 作業員名を取得
	########################################################

	$myListObject = new SPFWListObject($myDB);

	// Select SQL を設定
	$sql = "SELECT ";
	$sql .= "SagyoinCD, ";
	$sql .= "SagyoinName ";

	$myListObject->SelectSQL = $sql;

	// WHERE Condition を設定
	$sql = " FROM tSagyoinM";
	$sql .= " WHERE SagyoinCD > 0 AND MukouFlg = FALSE";
	for ($i = 0; $i < count($whereSQL); $i++){
		$sql .= " AND " . $whereSQL[$i];
	}

	$myListObject->Condition = $sql;
	$myListObject->Order = $SagyoinCD;
	$myListObject->Limit = "allpage";

	// 検索実行
	if (!($myListObject->GetList(1))) {
		$ErrorString = array();
		$ErrorString[] = "メニューマスタリストの抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}

	// データ表示
	$SagyoinListLoop = $myListObject->Rows;

	for ($i = 0; $i < $SagyoinListLoop; $i++) {
		$SagyoinCD2[$i] = $myListObject->GetValue($i, 0);
		$SagyoinName2[$i] = $myListObject->GetValue($i, 1);
	}


	// 作業員CDで検索して作業員名を取得
	$sagyoinNo = array_search("$editSagyoinCD" , $SagyoinCD2);
	$editSagyoinName = $SagyoinName2[$sagyoinNo];



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



	$Nickname = $myUser->Nickname;
	$EMail = $myUser->EMail;

	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") .".tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
