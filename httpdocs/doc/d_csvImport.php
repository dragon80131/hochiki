<?php

	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
	// include_once _CLS_DIR . "SPUSSiten.cls";

	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSReservation.cls";
	// include_once _CLS_DIR . "SPUSKojiDate.cls";

	include_once _DOCUMENT_ROOT . "include/common_489.php";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);
	########################################################
	# パラメータチェック/値加工/値受け取り
	########################################################

	$rKey = SPFWParameter::getValues('rKey');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$UpFile = SPFWParameter::getValues('Upfile');#file/201808281111ファイル名.xlsx

	########################################################
	# 認証動作
	########################################################
	$rkey = SPFWParameter::getValues('rkey');

	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	$myUserCD = $myUser->UserCD;
	$myClientCD = $myUser->ClientCD;



	if( $UpFile ){#ファイルがアップされていたら



		
	########################################################
	# 枠情報
	########################################################

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	// #### マンション名　をもってくる。
	$myBukken = new Bukken($myDB);
	
	if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD AND MukouFlg = FALSE", "")) {
		$ErrorString = array();
		$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}
	$MansionName = $myBukken->BukkenName;
	// if ($lang <> 'ja') $MansionName = $myBukken->BukkenNameEn; #★Multilingual
	$WakuPattern = $myBukken->WakuPattern;
	$Hansu = $myBukken->Hansu ;
	$MinuteTime = $myBukken->MinuteTime ;
	$today = date("Y-m-d");
	
	unset($myBukken);
	$Waku = explode("-",$WakuPattern);
	for($i=0;$i<count($WAKUPATTERN[$WakuPattern]['AMPM']);$i++){
		$WakuCol[$i] = ceil($Waku[$i]/$Hansu);
	}


	########################################################
	# EXCEL処理
	########################################################

	//テンプレート読み込み
	$template_filepath = $UpFile;


	//ライブラリ読み込み
	require_once './Classes/PHPExcel.php';
	require_once './Classes/PHPExcel/IOFactory.php';

	//PHPExelオブジェクトの作成
	$objPHPExcel = new PHPExcel();

	// キャッシュ方法を一時ファイルに保存する
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;

	// 一時ファイルの場所を指定
	$cacheSettings = array('dir' => '/tmp');
	// 設定を反映
	if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings)) {
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);
		die ('Can not set PHPExcel cache storage setting');
	}

	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	// テンプレート読み込み
	$objPHPExcel = $objReader->load( $template_filepath );


	// 1枚目のシートを選択
	$sheet = $objPHPExcel->getActiveSheet(0);


	########################################################
	# 配列にして、日付時間でソートする
	########################################################
	$ErrorString = array() ;
	$i = 0;
	$TimeCNT = 0;

	$j = 0;//行ごと
	foreach ($sheet->getRowIterator() as $row){#Foreach①

		$k = 0;//列ごと
		foreach ($row->getCellIterator() as $cell){#ここでセルの値を$cellでとってくるみたい
			// 各セルの値を取得
			$CellValue =  $cell->getValue();#とってきたときのセルの値

			if( $k == 0 ){#部屋番号★
				$wUserCD[$j] = $CellValue;
			}elseif( $k == 1 ){	#工事日★
				$wTimeFromDate[$j] = date('Y/m/d', ($CellValue - 25569) * 86400 );#86400秒＝1日
			}elseif( $k == 2 ) { #開始時間★
				$wCellValue = round($CellValue *24,2);#9.5 9:30に
				$CellValueHour	= floor($wCellValue);
				$CellValueMinute	=$wCellValue-$CellValueHour;
				$CellValueHour		=sprintf('%02d',$CellValueHour );
				$CellValueMinute	=sprintf('%02d',$CellValueMinute *60 );
				$wTimeFromTime[$j] = $CellValueHour.":".$CellValueMinute;
			}


			$k++;#★
		}
		$j++;

	}#★Foreach　①

	#並べ替　日付、時間、部屋番号　をセットで
	array_multisort($wTimeFromDate, SORT_ASC, $wTimeFromTime, SORT_ASC, $wUserCD, SORT_ASC);
	// echo "<br> ".__LINE__." ここまでOK :";
	//  print_r($wTimeFromTime);

	########################################################
	# テーブルに書き込む前に無効に
	########################################################

	$dsn = 'mysql:host=localhost;dbname=' . _MAIN_DB . ';charset=utf8';
	$DBNAME = _MAIN_DB;
	$user = _USER_NAME;
	$password = _PASSWD;
	try {
			$dbh = new PDO($dsn, $user, $password);
	} catch (PDOException $e) {
			echo 'データベースにアクセスできません！' . $e->getMessage();
			exit;
	}
	try {
			$options = [
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			];
			$statement = " Update tReservationF set MukouFlg = 1 WHERE BukkenCD = '" . $editBukkenCD . "' ";
			$stmt = $dbh->prepare($statement);
			$stmt->execute();
	} catch (Exception $ex) {
			var_dump($ex);
	}

	try {
		$options = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		];
		$statement = " Update tUserM set MukouFlg = 1 WHERE BukkenCD = '" . $editBukkenCD . "' ";
		$stmt = $dbh->prepare($statement);
		$stmt->execute();
	} catch (Exception $ex) {
			var_dump($ex);
	}

	########################################################
	# テーブルに書き込み
	########################################################
	for($i=0;$i<count($wUserCD);$i++){
		if($i==0){
			$wTime = $wTimeFromTime[0];
			$HikakuSU = $AddUnit = 0;
		}
		// #前のレコードと同じ日にちかつ同じ時刻
		if(($wTimeFromDate[$i] ==  $wTimeFromDate[$i-1]) and ($wTimeFromTime[$i] == $wTimeFromTime[$i-1])){	#前のレコードと同じ日 
			$HikakuSU++;#班数をこえるかどうか（班数チェック）どうかにつかっている
			$AddUnit = floor($HikakuSU / $Hansu);#切り下げ
			$wTime = date('H:i', strtotime("+" . ($AddUnit * $MinuteTime) . " minutes", strtotime($wTimeFromTime[$i])));

		}else{#同じ日　同じ時刻　どちらかがちがうと　初期化
			$wTime = $wTimeFromTime[$i];
			$HikakuSU = $AddUnit = 0;
		}
		#DB更新処理
		$myUser = new User($myDB);

		$myUser->UserCD = -1;
		$myUser->BukkenCD = $editBukkenCD;
		$myUser->ClientCD = $myClientCD;
		$myUser->ID = $wUserCD[$i];
		$myUser->Passwd = random_int(1000, 9999); ;
		$myUser->Creator = $myUserCD ;
		$myUser->Updater = $myUserCD;
		
		if (!$myUser->executeUpdate()) {
			$ErrorString = array();
			$ErrorString[] = "ユーザ登録に失敗しました。";
			showAdminSorryPage($ErrorString);
		} else {
			$IfOK = TRUE;
		}

		$myReservation = new Reservation($myDB);

		$myReservation->ReservationCD = -1;
		$myReservation->BukkenCD = $editBukkenCD;
		$myReservation->ClientCD = $myClientCD;
		$myReservation->UserCD = $myUser->UserCD;
		$myReservation->ID = $wUserCD[$i];
		$myReservation->TimeFrom = $wTimeFromDate[$i]." ".$wTime;
		$wwTime = date('H:i', strtotime("+" . $MinuteTime . " minutes", strtotime($wTime)));
		$myReservation->TimeTo = $wTimeFromDate[$i]." ".$wwTime;
		$myReservation->Status = "1";
		
		$myReservation->StylistCD = "1";#新しいお客さんがいたら変化させる必要あり
		$myReservation->MenuCD = "|1|";
		$myReservation->Creator = $myUserCD;
		$myReservation->Updater = $myUserCD;
		

		if (!$myReservation->executeUpdate()) {
			$ErrorString = array();
			$ErrorString[] = "日程情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		} else {
			$IfOK = TRUE;
		}


	}

#######################################3

		#専有部工期期間内か
		if( $IfError ){
			for( $i=0; $i<count($ErrorString); $i++){
				$ErrorStringAll = $ErrorStringAll.$ErrorString[$i]."<br>";
			}
			include_once('d_csvUpload.php');
			exit;
		}


		// ファイル削除
		unlink($UpFile);

	}#ファイルがアップされていたら　End

	########################################################
	# コンテンツ表示
	########################################################
	SPFWTemplate::setValue("work");
	SPFWTemplate::setValue("editClientCD", $TargetClientCD);
	$HiddenValues = SPFWTemplate::getValuesToPass();

	$CNT_FILE = "d_csvImport.tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, TRUE);

	unset($myTemplate);
	unset($myLog);
	unset($myDB);




	// function getAkiWakuAMPMTime($myDB, $TargetClientCD, $MyMinuteType, $TargetDate, $TargetTime, $WakuSuu, $STimeList, $ETimeList, $MaxWakuSu,$loginUserCD = "")
	// {
	
	
	// 	global $MINUTEUNIT;
	
	
	// 	$TargetWeekdayNo = date('w', strtotime($TargetDate)); // 選択した日の曜日番号
	
	// 	########################################################
	// 	# 基本設定呼び出し
	// 	########################################################
	// 	// $mySetting = new Setting($myDB);
	// 	// if (!$mySetting->executeSelect("ClientCD = " . $TargetClientCD . " AND MukouFlg = FALSE", "")) {
	// 	// 	trigger_error("Getting Stylist List Failed.", E_USER_ERROR);
	// 	// }
	// 	// $OpenTime = SPFWTools::decodePluralValue($mySetting->OpenTime);
	// 	// $CloseTime = SPFWTools::decodePluralValue($mySetting->CloseTime);
	// 	// $LunchTimeFrom = SPFWTools::decodePluralValue($mySetting->LunchTimeFrom);
	// 	// $LunchTimeTo = SPFWTools::decodePluralValue($mySetting->LunchTimeTo);
	
	// 	$OpenTime = ['09:00','09:00','09:00','09:00','09:00','09:00','09:00'];
	// 	$CloseTime = ['18:00','18:00','18:00','18:00','18:00','18:00','18:00'];
	// 	$LunchTimeFrom = ['12:00','12:00','12:00','12:00','12:00','12:00','12:00'];
	// 	$LunchTimeTo = ['13:00','13:00','13:00','13:00','13:00','13:00','13:00'];
	
	
	
	// 	$MyOpenTime = $OpenTime[$TargetWeekdayNo];
	// 	$MyCloseTime = $CloseTime[$TargetWeekdayNo];
	// 	$MyLunchTimeFrom = $LunchTimeFrom[$TargetWeekdayNo];
	// 	$MyLunchTimeTo = $LunchTimeTo[$TargetWeekdayNo];
	
	// 	$MyOpenTimeHour = intval(substr($MyOpenTime, 0, 2));
	// 	$MyOpenTimeMinute = intval(substr($MyOpenTime, 3, 2));
	// 	$MyCloseTimeHour = intval(substr($MyCloseTime, 0, 2));
	// 	$MyCloseTimeMinute = intval(substr($MyCloseTime, 3, 2));
	
	// 	$MyLunchTimeFromHour = substr($MyLunchTimeFrom, 0, 2);
	// 	$MyLunchTimeFromMinute = substr($MyLunchTimeFrom, 3, 2);
	// 	$MyLunchTimeToHour = substr($MyLunchTimeTo, 0, 2);
	// 	$MyLunchTimeToMinute = substr($MyLunchTimeTo, 3, 2);
	
	// 	// $WakuRange = $mySetting->WakuRange;
	// 	$WakuRange = $MaxWakuSu; #6-4-4
	// 	$WakuRangeArray = explode("-",$WakuRange);
	// 	// echo "<br> ".__LINE__." WakuRange :".$WakuRange;
	// 	// print_r($WakuRangeArray);
	
	
	// 	unset($mySetting);
	
	// 	########################################################
	// 	# スタイリストリストを取得
	// 	########################################################
	
	// 	$myListObject = new SPFWListObject($myDB);
	// 	$sql = "SELECT ";
	// 	$sql .= "StylistCD, ";
	// 	$sql .= "NumberOfLines ";
	// 	$myListObject->SelectSQL = $sql;
	// 	$sql = " FROM tStylistM";
	// 	$sql .= " WHERE StylistCD > 0 AND MukouFlg = FALSE ";
	// 	// $sql .= " AND (WakugoeFlg is NULL OR WakugoeFlg = 0)"; // 枠越は除く
	// 	$sql .= " AND ClientCD = " . $TargetClientCD;
	
	// 	$myListObject->Condition = $sql;
	// 	$myListObject->Order = "StylistCD";
	// 	$myListObject->Limit = "allpage";
	
	// 	if (!($myListObject->GetList(1)))
	// 		trigger_error("Getting Stylist List Failed.", E_USER_ERROR);
	
	// 	$StylistLoop = $myListObject->Rows;
	// 	for ($i = 0; $i < $StylistLoop; $i++) {
	// 		$StylistCD[$i] = $myListObject->GetValue($i, 0);
	// 		$NumberOfLines[$i] = $myListObject->GetValue($i, 1);
	
	
	// 		$LinesLoop += $NumberOfLines[$i];
	
	// 		for ($j = 0; $j < $NumberOfLines[$i]; $j++)
	// 			$StylistCDs[] = $StylistCD[$i];
	// 	}
	// 	unset($myListObject);
	
	// 	########################################################
	// 	# 時刻LOOP
	// 	########################################################
	// 	for ($i = $MyOpenTimeHour; $i <= $MyCloseTimeHour; $i++) {
	// 		for ($j = 0; $j < 60; $j += $MINUTEUNIT) {
	// 			if (($i == $MyCloseTimeHour && $MyCloseTimeMinute <= $j) || $i > $MyCloseTimeHour)
	// 				break;
	
	// 			// 昼休みは除外する
	// 			$tmptime = sprintf("%02d%02d", $i, $j);
	// 			if (($MyLunchTimeFromHour . $MyLunchTimeFromMinute <= $tmptime) and ($tmptime < $MyLunchTimeToHour . $MyLunchTimeToMinute))
	// 				break;
	
	// 			$TimesHour[] = $i;
	// 			$TimesMinute[] = $j;
	// 			$Times[] = sprintf("%02d:%02d", $i, $j);
	// 		}
	// 	}
	
	// 	// 予約いれたい時間がAMかPM1かPM2か...を判断
	// 	for ($i = 0; $i < $WakuSuu; $i++) {
	// 		if ($STimeList[$i] <= $TargetTime and $TargetTime < $ETimeList[$i]) {
	// 			#echo "<br>★".$STimeList[$i]."～".$ETimeList[$i]." ".$WakuRangeArray[$i]; // 09:00～12:00 7
	// 			$YoyakuStart = $STimeList[$i]; // 予約いれたい時間枠の開始時間
	// 			$YoyakuEnd = $ETimeList[$i]; // 予約いれたい時間枠の終了時間
	// 			$YoyakuWakuMax = $WakuRangeArray[$i]; // 予約いれたい時間枠の最大枠数
	// 		}
	// 	}
	
	// 	// echo "<br> ".__LINE__." Hensu :".$YoyakuStart;
	// 	// echo "<br> ".__LINE__." Hensu :".$YoyakuEnd;
	// 	// echo "<br> ".__LINE__." Hensu :".$YoyakuWakuMax;
	// 	// echo "<br> ".__LINE__." ここ :";
	
	
	// 	########################################################
	// 	# 予約リストを取得
	// 	########################################################
	
	// 	// 予約いれたい日、同じ時間枠の予約を取得する
	// 	$myListObject = new SPFWListObject($myDB);
	// 	$sql = "SELECT ";
	// 	$sql .= "ReservationCD, ";	#0
	// 	$sql .= "StylistCD, ";		#1
	// 	$sql .= "UserCD, ";			#2
	// 	$sql .= "TimeFrom, ";		#3
	// 	$sql .= "TimeTo ";			#4
	
	// 	$myListObject->SelectSQL = $sql;
	// 	$sql = " FROM tReservationF ";
	// 	$sql .= " WHERE ReservationCD > 0 AND MukouFlg = FALSE";
	// 	$sql .= " AND date_format(TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
	// 	$sql .= " AND date_format(TimeFrom, '%H:%i') >= '" . $YoyakuStart . "' AND date_format(TimeFrom, '%H:%i') < '" . $YoyakuEnd . "' ";
	// 	$sql .= " AND Status = 1";
	// 	$sql .= " AND ClientCD = " . $TargetClientCD;
	
	// 	if ($loginUserCD) // 自分の予約は除く（同じ時間に修正できる）
	// 		$sql .= " AND UserCD != '$loginUserCD'";
	
	// 	$myListObject->Condition = $sql;
	// 	$myListObject->Order = "StylistCD,TimeFrom";
	// 	$myListObject->Limit = "allpage";
	
	// 	if (!($myListObject->GetList(1)))
	// 		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);
	
	// 	$ReservationLoop = $myListObject->Rows;
	// 	for ($i = 0; $i < $ReservationLoop; $i++) {
	// 		$ReservationCD = $myListObject->GetValue($i, 0);
	// 		$StylistCD = $myListObject->GetValue($i, 1);
	// 		#$UserCD = $myListObject->GetValue($i, 2);
	// 		#$wTimeFrom = substr($myListObject->GetValue($i, 3), 11, 5); // 09:00, 11:00...
	// 		$TimeFrom = $myListObject->GetValue($i, 3);
	// 		$TimeTo = $myListObject->GetValue($i, 4);
	
	// 		$TimeFromYear = substr($TimeFrom, 0, 4);
	// 		$TimeFromMonth = substr($TimeFrom, 5, 2);
	// 		$TimeFromDay = substr($TimeFrom, 8, 2);
	// 		$TimeFromHour = substr($TimeFrom, 11, 2);
	// 		$TimeFromMinute = substr($TimeFrom, 14, 2);
	// 		$TimeToYear = substr($TimeTo, 0, 4);
	// 		$TimeToMonth = substr($TimeTo, 5, 2);
	// 		$TimeToDay = substr($TimeTo, 8, 2);
	// 		$TimeToHour = substr($TimeTo, 11, 2);
	// 		$TimeToMinute = substr($TimeTo, 14, 2);
	
	// 		#if ($ReserveTime > 0)
	// 		#	$TimeRequired = $ReserveTime;
	// 		#else
	// 		$TimeRequired = (mktime($TimeToHour, $TimeToMinute, 0, $TimeToMonth, $TimeToDay, $TimeToYear) - mktime($TimeFromHour, $TimeFromMinute, 0, $TimeFromMonth, $TimeFromDay, $TimeFromYear)) / 60;
	// 		$TimeUnits = $TimeRequired / $MINUTEUNIT;
	// 		#echo "<br>TimeRequired".$TimeRequired." TimeUnits:".$TimeUnits;
	
	// 		for ($j = 0; $j < $TimeUnits; $j++) {
	// 			$MyTime = date('H:i', mktime($TimeFromHour, $TimeFromMinute + $j * $MINUTEUNIT, 0, $TimeFromMonth, $TimeFromDay, $TimeFromYear));
	// 			$Reservation[$MyTime][$StylistCD]["ReservationCD"][] = $ReservationCD;
	// 		}
	
	// 		#$Reservation[$TimeFrom][$StylistCD]["ReservationCD"][] = $ReservationCD;
	// 	}
	// 	unset($myListObject);
	
	// 	#echo "<pre>";
	// 	#var_dump($Reservation);
	// 	#echo "</pre>";
	
	// 	$ReturnData = array();
	
	// 	$YoyakuCntCD = array();
	// 	#$YoyakuCnt = 0; // 予約済カウント用
	// 	$TimesLoop = count($Times);
	// 	for ($i = 0; $i < $TimesLoop; $i++) {
	// 		if ($YoyakuStart <= $Times[$i] and $Times[$i] < $YoyakuEnd) { // 予約したい時間帯のみ
	
	// 			for ($j = 0; $j < $LinesLoop; $j++) {
	// 				#echo "<br>".$Times[$i]."_".$j;
	
	// 				if (isset($Reservation[$Times[$i]][$StylistCDs[$j]]['ReservationCD'][$j])) {
	
	// 					// 予約数を数えるために予約CDをセット
	// 					if (!in_array($Reservation[$Times[$i]][$StylistCDs[$j]]['ReservationCD'][$j], $YoyakuCntCD)) {
	// 						$YoyakuCntCD[] = $Reservation[$Times[$i]][$StylistCDs[$j]]['ReservationCD'][$j];
	// 					}
	// 				} else {
	// 					// 予約CDをカウント
	// 					if (count($YoyakuCntCD) >= $YoyakuWakuMax) {
	// 						#echo  "予約済が枠を超えているため、ここは枠超になる。ここに予約を入れてはだめ";
	// 					} else {
	// 						#echo "空いてる";
	// 						$YoyakuOKTimeFrom = $TargetDate . " " . $Times[$i];
	// 						$YoyakuOKStylistCD = $StylistCDs[$j];
	
	// 						$TimeRequired = $MINUTEUNIT * $MyMinuteType;
	// 						$YoyakuOKTimeTo = date("Y-m-d H:i", strtotime($YoyakuOKTimeFrom . "+" . $TimeRequired . " minute"));
	
	// 						// echo "<br> ".__LINE__." MINUTEUNIT :".$MINUTEUNIT;
	// 						// echo "<br> ".__LINE__." MyMinuteType :".$MyMinuteType;
	// 						// echo "<br> ".__LINE__." TimeRequired :".$TimeRequired;
	
	// 						$ReturnData["akiTimeFrom"] = $YoyakuOKTimeFrom;
	// 						$ReturnData["akiTimeTo"] = $YoyakuOKTimeTo;
	// 						$ReturnData["akiStylistCD"] = $YoyakuOKStylistCD;
	
	
	// 						break 2; // ループを抜ける
	// 					}
	// 				}
	// 				#echo " ".count($YoyakuCntCD);
	// 			}
	// 		} else {
	// 			#			echo __LINE__."$YoyakuStart <= $Times[$i] AND $Times[$i] < $YoyakuEnd <br>";
	
	// 		}
	// 	}
	
	// 	#echo "<pre>";
	// 	#var_dump($ReturnData);
	// 	#echo "</pre>";
	
	// 	return $ReturnData;
	// }
	
?>
