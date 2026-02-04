<?php
$isAdminMode = TRUE;
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
	include_once _CLS_DIR . "SPUSBukkenMatrix.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSFile.cls";
	include_once _CLS_DIR . "SPUSKojiDate.cls";


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
		showSorryPage(_ILLEGAL_ACCESS2);

	if (!$myUser->doAuthenticationByRegistKey($rKey)) 
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) 
		showSorryPage(_ILLEGAL_ACCESS2);

	$UserCD = $myUser->UserCD;
	$ID = $myUser->ID;
	unset($myUser);


	########################################################
	# 設定パラメータ取得取得
	########################################################



	$wHansu = SPFWParameter::getValues('wHansu');			#班数
	$wMinuteTime = SPFWParameter::getValues('wMinuteTime');	#工事施工時間（リアル）
	$wWakuPattern = SPFWParameter::getValues('wWakuPattern');
	$wWakuAM = SPFWParameter::getValues('wWakuAM');
	$wWakuPM1 = SPFWParameter::getValues('wWakuPM1');
	$wWakuPM2 = SPFWParameter::getValues('wWakuPM2');
	if( !$wWakuPM2){
		$wWakuPM = $wWakuPM1 ;
		$wWakuSu = $wWakuAM."-".$wWakuPM ;
	}else{
		$wWakuSu = $wWakuAM."-".$wWakuPM1."-".$wWakuPM2 ;
	}
	$wKojijun = SPFWParameter::getValues('wKojijun');		# 工事順
	$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature');#初日工事数考慮
	$wHoliday1 = SPFWParameter::getValues('wHoliday1');		#休日
	$wHoliday =SPFWTools::decodePluralValue($wHoliday1);
	

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');


	$wWakuAMcol = SPFWParameter::getValues('wWakuAMcol');
	$wWakuPM1col = SPFWParameter::getValues('wWakuPM1col');
	$wWakuPM2col = SPFWParameter::getValues('wWakuPM2col');
	$hensyu = SPFWParameter::getValues('hensyu');
	if($hensyu==1){
		$wKoteihyouEX = SPFWParameter::getValues('wwKoteihyouEX');
	}else{
		$wKoteihyouEX = SPFWParameter::getValues('wKoteihyouEX');
		$wKoteihyouEX = SPFWTools::decodePluralValue($wKoteihyouEX); #配列
	}


	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		$ErrorString = array();
		$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	$KyoyoStartDate = $myKoji->KyoyoStartDate;
	$KyoyoEndDate = $myKoji->KyoyoEndDate;
	$SenyuStartDate = $myKoji->SenyuStartDate;
	$SenyuEndDate = $myKoji->SenyuEndDate;
	$SenyuDateCnt = (( strtotime( $SenyuEndDate ) -  strtotime( $SenyuStartDate )) / 86400) + 1 ;#専有部日数
	$ReceptionDate = $myKoji->ReceptionDate;
			echo "<br>count(Holiday)".count($wHoliday);
	for($i=1 ; $i<=count($wHoliday) ; $i++){
			$Kyujitu[] = (strtotime( $wHoliday[$i-1] ) - strtotime( $SenyuStartDate ))/86400;

	}
	#$myKoji->WakuSu = $wWakuSu ;

	if (!$myKoji->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "工事情報登録に失敗しました。";
		showAdminSorryPage($ErrorString);
	}else{
		$IfOK = TRUE;
	}
	unset($myKoji);

	########################################################
	# 部屋情報　保存
	########################################################
	$myBukkenMatrix = new BukkenMatrix($myDB);

	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);

	$wKaiRoom = $myBukkenMatrix->KaiRoom;
	$KaiRoom = SPFWTools::decodePluralValue($wKaiRoom); #配列

	unset($myBukkenMatrix);



	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;
	unset($myBukken);


	########################################################
	# 作成準備
	########################################################

	function getExcelAddress($col,$row) {
		$sinsu = 26;
		$col_val="";
		while($col>0) {
			$intval = (($col-1) % $sinsu);
			$col_val=chr($intval+65).$col_val;
			$col=intval(($col-1) / $sinsu);
		}
		return $col_val.$row;
	}


#工事枠を測る
	$wWakuAMPM = $wWakuAMcol+$wWakuPM1col+$wWakuPM2col;
	$week = ['日','月','火','水','木','金','土'];

	########################################################
	# Excelファイル生成
	########################################################
	echo "<br>".__LINE__."行目";

	#AM表記変更
	$aAM = $WAKUPATTERN[$wWakuPattern]['StartTime'][0];
	$eAM = $WAKUPATTERN[$wWakuPattern]['EndTime'][0];
	$DispAM = $aAM."～".$eAM ;

	$aPM1 = $WAKUPATTERN[$wWakuPattern]['StartTime'][1] ;
	$ePM1 = $WAKUPATTERN[$wWakuPattern]['EndTime'][1] ;
	$DispPM1 = $aPM1."～".$ePM1 ;

	$aPM2 = $WAKUPATTERN[$wWakuPattern]['StartTime'][2] ;
	$ePM2 = $WAKUPATTERN[$wWakuPattern]['EndTime'][2];
	$DispPM2 = $aPM2."～".$ePM2 ;


	if($wWakuPM2col > 0){
		$cellPM2c = getExcelAddress(($wWakuAMcol+4+$wWakuPM1col),8);#列　行
		$cellPM2r = getExcelAddress(($wWakuAMcol+3+$wWakuPM1col+$wWakuPM2col),8);#列　行
	}


	$cell_row = 9;
	$k = 0;
	// 枠線


	for($i = 0 ; $i < $SenyuDateCnt*$wHansu ; $i++ ){#1 専有部日数　#日付はまわす 枠がいっぱいになったら次の日にいく。
		$cell_col = 4;
		$y=0;
		for($x=0 ; $x<count($Kyujitu) ;$x++){
			if($i >= $Kyujitu[$x] * $wHansu && $i < ($Kyujitu[$x]+1) * $wHansu)
				$y=1;
		}


		if($y==0){
			for($j = 0 ; $j < $wWakuAMPM ; $j++){
				if($wFirstDateFeature>0 && $j < $wWakuAMcol && $i >= 0 && $i < $wHansu){
						$k++;
				}else{
					if($wKoteihyouEX[$k]=="空き"){
			
					}elseif($wKoteihyouEX[$k]==""){

					}else{
							if($j<$wWakuAMcol){
								$KoteihyouTa[$i]['AM'][]=$wKoteihyouEX[$k];
							}elseif($j>=$wWakuAMcol && $j<($wWakuAMcol+$wWakuPM1col)){
								$KoteihyouTa[$i]['PM1'][]=$wKoteihyouEX[$k];
							}elseif($j>=($wWakuAMcol+$wWakuPM1col) && $j<$wWakuAMPM){
								$KoteihyouTa[$i]['PM2'][]=$wKoteihyouEX[$k];
							}

					}
					$k++;

				}
			}
		}
			$cell_row = $cell_row + 1;
	}
#echo "<br>385行目wKoteihyouTa:";
#print_r($KoteihyouTa);
	$date = new DateTime($SenyuStartDate);
	$CellStart = 9;
	for($i = 0 ; $i < $SenyuDateCnt ; $i++ ){#1 専有部日数　#日付はまわす 枠がいっぱいになったら次の日にいく。
		$SenyuDate = $date->format('m月d日');
		$YoubiCD =  $date->format('w') ;
		$Youbi = $week[$YoubiCD];
#echo "<br>382行目SenyuDate:".$SenyuDate;
		$wSenyuDate[] = $date->format('Y-m-d');	
	
		$wCellStart = $CellStart + ( $i * $wHansu);
		
		$date->modify('+1 days');
	}

	########################################################
	# 日程情報　保存
	########################################################

	#登録前に、同一物件は削除　
	#同じ物件CDを全部削除してしまう。多棟のときは、別物件でやってもらおう。まちがえたら上書きになる。復活できない
	$db_link = mysqli_connect(_HOST_NAME, _USER_NAME, _PASSWD, _MAIN_DB);

#	$sql = " UPDATE tKojiDateF SET MukouFlg = 1, Updated = '".date('Y-m-d H:i:s')."', Updater = '$UserCD' ";
	$sql = " delete from  tKojiDateF ";
	$sql .= " WHERE BukkenCD = '".$editBukkenCD."' ";
	$result = mysqli_query( $db_link,$sql);	

	#いつでも新規登録
	if(is_array($wSenyuDate)){
	for( $i=0; $i< count($wSenyuDate); $i++){
	#	echo "<br>日付:".date('Y-m-d',strtotime( $wSenyuDate[$i]));
		echo "<br>日付:".$wSenyuDate[$i] ;
		for( $j=0; $j< $wHansu; $j++){
			#echo " ☆:".($i*$wHansu+$j);

			#print_r($KoteihyouTa[$i*$wHansu+$j]['AM']);
			if($KoteihyouTa[$i*$wHansu+$j]['AM']){
			for($k=0; $k<count($KoteihyouTa[$i*$wHansu+$j]['AM']);$k++){
				#echo " AM ".$KoteihyouTa[$i*$wHansu+$j]['AM'][$k];
				$KojiDate['RoomID'][] = $KoteihyouTa[$i*$wHansu+$j]['AM'][$k];#
				$KojiDate['RoomDate'][] = $wSenyuDate[$i]." ".$aAM;#日付+AMの開始時刻　196行付近に定義あり
			}
			}
			if(is_array($KoteihyouTa[$i*$wHansu+$j]['PM1'])){
			for($k=0; $k<count($KoteihyouTa[$i*$wHansu+$j]['PM1']);$k++){
				#echo " PM1 ".$KoteihyouTa[$i*$wHansu+$j]['PM1'][$k];
				$KojiDate['RoomID'][] = $KoteihyouTa[$i*$wHansu+$j]['PM1'][$k];#
				$KojiDate['RoomDate'][] = $wSenyuDate[$i]." ".$aPM1;#日付+PM1の開始時刻　196行付近に定義あり

			}
			}
			if(is_array($KoteihyouTa[$i*$wHansu+$j]['PM2'])){
			for($k=0; $k<count($KoteihyouTa[$i*$wHansu+$j]['PM2']);$k++){
				#echo " PM2 ".$KoteihyouTa[$i*$wHansu+$j]['PM2'][$k];
				$KojiDate['RoomID'][] = $KoteihyouTa[$i*$wHansu+$j]['PM2'][$k];#
				$KojiDate['RoomDate'][] = $wSenyuDate[$i]." ".$aPM2;#日付+PM2の開始時刻　196行付近に定義あり

			}
			}
		}

	}
	}
	##テーブル登録
	for( $i=0; $i<count( $KojiDate['RoomID'] ); $i++){
		$myKojiDate = new KojiDate($myDB);

		$myKojiDate->KojiDateCD = "-1";
		$myKojiDate->BukkenCD = $editBukkenCD;
		$myKojiDate->RoomID = $KojiDate['RoomID'][$i];
		$myKojiDate->RoomDate = $KojiDate['RoomDate'][$i];
		$myKojiDate->Creator = $UserCD;
		$myKojiDate->Updater = $UserCD;
		
		if (!$myKojiDate->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "部屋日程登録に失敗しました。";
			showAdminSorryPage($ErrorString);
		}
		unset($myKojiDate);
	}
	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_kotei_finish.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);




?>
