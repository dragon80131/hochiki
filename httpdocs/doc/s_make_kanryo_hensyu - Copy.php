<?php

	include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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

	########################################################
	# 設定パラメータ取得取得
	########################################################

	foreach($_POST as $key => $value){
		// echo "<br>key:".$key;
		${"$key"} = SPFWParameter::getValues($key);
		
		//  echo " :".SPFWParameter::getValues($key);
	}

	$ColsBlock = SPFWTools::decodePluralValue($wColsBlock);
	$RowsLoop = SPFWParameter::getValues('RowsLoop');


	$wKojijun = SPFWParameter::getValues('wKojijun');		# 工事順
	$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature');#初日工事数考慮
	$wHoliday1 = SPFWParameter::getValues('wHoliday1');		#休日
	$wHoliday2 = SPFWParameter::getValues('wHoliday2');		
	$wHoliday3 = SPFWParameter::getValues('wHoliday3');
	$wHoliday4 = SPFWParameter::getValues('wHoliday4');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$wWakuAMcol = SPFWParameter::getValues('wWakuAMcol');
	$wWakuPM1col = SPFWParameter::getValues('wWakuPM1col');
	$wWakuPM = SPFWParameter::getValues('wWakuPM'); 

	if($wWakuPM){
		$wHansu = SPFWParameter::getValues('wHansu');
		$wWakuPM1col = ceil($wWakuPM/$wHansu);
	}
	
	$wWakuPM2col = SPFWParameter::getValues('wWakuPM2col');
	$wKoteihyouEX = SPFWParameter::getValues('wKoteihyouEX');
	$holiday = SPFWParameter::getValues('holiday');		#休日
	$wShukujitucolor = SPFWParameter::getValues('wShukujitucolor');		#祝日色
	$wKyukobi = SPFWParameter::getValues('wKyukobi');		#休工日
	$work = SPFWParameter::getValues('work');		#休工日
	$wKaiRoom3 = SPFWParameter::getValues('wKaiRoom3');		#休工日
	$wKoteihyouEX = SPFWTools::decodePluralValue($wKoteihyouEX); #配列
	$wShukujitucolor = SPFWTools::decodePluralValue($wShukujitucolor);
	$wKyukobi = SPFWTools::decodePluralValue($wKyukobi);
	$wKaiRoom3 = SPFWTools::decodePluralValue($wKaiRoom3);

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD, "") ){
		$ErrorString = array();
		$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	// $KyoyoStartDate = $myBukken->KyoyoStartDate;
	// $KyoyoEndDate = $myBukken->KyoyoEndDate;
	$SenyuStartDate = $myBukken->SenyuStartDate;
	$SenyuEndDate = $myBukken->SenyuEndDate;
	$SenyuDateCnt = (( strtotime( $SenyuEndDate ) -  strtotime( $SenyuStartDate )) / 86400) + 1 ;#専有部日数
	// 休工日
	$Holiday1 = $myBukken->Holiday1;
	$wHoliday = SPFWTools::decodePluralValue($Holiday1);
	sort($wHoliday);

	########################################################
	# 空き削除処理
	########################################################
	if($work==2){
		$wKoteihyouEX = SPFWParameter::getValues('wwKoteihyouEX');		#配列
		$Aki = SPFWParameter::getValues('Aki');		#休工日
		$wWakuAMcol=ceil(($wWakuAM)/$wHansu);#入力枠数を班数でわった切り上げた数
		$WakuAMCEL = $wWakuAMcol*$wHansu;#一日のAMのセル数
		if($wWakuPattern > 2) {
			$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
			$wWakuPM2col=ceil(($wWakuPM2)/$wHansu);
			$WakuPM1CEL = $wWakuPM1col*$wHansu;#一日のPM1のセル数
			$WakuPM2CEL = $wWakuPM2col*$wHansu;#一日のPM2のセル数
			$wWakuSum=$wWakuAMcol+$wWakuPM1col+$wWakuPM2col;
		}else{
			//$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
			$wWakuPM1col=ceil(($wWakuPM)/$wHansu);
			$WakuPM1CEL = $wWakuPM1col*$wHansu;#一日のPM1のセル数
			$wWakuSum=$wWakuAMcol+$wWakuPM1col;
		}
		$x=0;
		$y=0;
		for($i=0 ; $i<$SenyuDateCnt ; $i++){
			for($j=0 ; $j<$wHansu ; $j++){
				for($k=0 ; $k< $wWakuAMcol ; $k++){
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if($y==$Aki)
						$n=$x;
					$x++;
					$y++;
				}
				if($wWakuPattern > 2) {
					$y=$y+$wWakuPM1col+$wWakuPM2col;
				}else{
					$y=$y+$wWakuPM1col;
				}
				
			}
			if($wWakuPattern > 2) {
				$x=$x+$WakuPM1CEL+$WakuPM2CEL;
			}else{
				$x=$x+$WakuPM1CEL;
			}
		}
		$x=$WakuAMCEL;
		$y=$wWakuAMcol;
		for($i=0 ; $i<$SenyuDateCnt ; $i++){
			for($j=0 ; $j<$wHansu ; $j++){
				for($k=0 ; $k< $wWakuPM1col ; $k++){
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if($y==$Aki)
						$n=$x;
					$x++;
					$y++;
				}
				if($wWakuPattern > 2) {
					$y=$y+$wWakuAMcol+$wWakuPM2col;
				}else{
					$y=$y+$wWakuAMcol;
				}
				
			}
			if($wWakuPattern > 2) {
				$x=$x+$WakuAMCEL+$WakuPM2CEL;
			}else{
				$x=$x+$WakuAMCEL;
			}
		}
		if($wWakuPattern > 2) {
			$x=$WakuAMCEL+$WakuPM1CEL;
			$y=$wWakuAMcol+$wWakuPM1col;
			for($i=0 ; $i<$SenyuDateCnt ; $i++){
				for($j=0 ; $j<$wHansu ; $j++){
					for($k=0 ; $k< $wWakuPM2col ; $k++){
						$wKoteihyou1[$x] = $wKoteihyouEX[$y];
						if($y==$Aki)
							$n=$x;
						$x++;
						$y++;
					}
					$y=$y+$wWakuPM1col+$wWakuAMcol;
					
				}
				$x=$x+$WakuPM1CEL+$WakuAMCEL;
			}
		}
		$key=0;
		ksort($wKoteihyou1);
		for($i=0;$i<count($wKoteihyou1);$i++){
			if(ctype_digit($wKoteihyou1[$i])&&$wKoteihyou1[$i]!=="空き"){
				$LastRoom = $i;
			}
		}
		$l=0;
		$o=0;
		for($i=0;$i<count($wKoteihyou1);$i++){
			if($n<=$i){
				if(ctype_digit($wKoteihyou1[$i])&&$l==0&&$o==0){
					$wKoteihyou1[$n]=$wKoteihyou1[$i];
					for($j=0;$j<count($wKaiRoom3);$j++){
						if($wKaiRoom3[$j]==$wKoteihyou1[$n]){
							$Room=$j+1;
						}
					}
					if(isset($wKaiRoom3[$Room])){
						$wKoteihyou1[$i]=$wKaiRoom3[$Room];
						$Room++;
					}else{
						$wKoteihyou1[$i]="空き";
					}
					$l++;
				}elseif(ctype_digit($wKoteihyou1[$i])){
					if(isset($wKaiRoom3[$Room])){
						$wKoteihyou1[$i]=$wKaiRoom3[$Room];
						$Room++;
					}else{
						$wKoteihyou1[$i]="空き";
					}
				}elseif($LastRoom<=$i &&isset($wKaiRoom3[$Room])&& $wKoteihyou1[$i]=="空き" && $o==0 &&$n==$i){
					$wKoteihyou1[$i]=$wKaiRoom3[$Room];
					$Room++;
					$o++;
				}
			}else{
				if($i==$LastRoom){
					for($j=0;$j<count($wKaiRoom3);$j++){
						if($wKaiRoom3[$j]==$wKoteihyou1[$i]){
							$Room=$j+1;
						}
					}
				}
			}

		}

		$x=0;
		$y=0;
		for($i=0 ; $i<$SenyuDateCnt ; $i++){
			for($j=0 ; $j<$wHansu ; $j++){
				for($k=0 ; $k< $wWakuAMcol ; $k++){
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if($y==$Aki)
						$n=$x;
					$x++;
					$y++;
				}
				if($wWakuPattern > 2) {
					$y=$y+$wWakuPM1col+$wWakuPM2col;
				}else{
					$y=$y+$wWakuPM1col;
				}
				
			}
			if($wWakuPattern > 2) {
				$x=$x+$WakuPM1CEL+$WakuPM2CEL;
			}else{
				$x=$x+$WakuPM1CEL;
			}
		}
		$x=$WakuAMCEL;
		$y=$wWakuAMcol;
		for($i=0 ; $i<$SenyuDateCnt ; $i++){
			for($j=0 ; $j<$wHansu ; $j++){
				for($k=0 ; $k< $wWakuPM1col ; $k++){
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if($y==$Aki)
						$n=$x;
					$x++;
					$y++;
				}
				if($wWakuPattern > 2) {
					$y=$y+$wWakuAMcol+$wWakuPM2col;
				}else{
					$y=$y+$wWakuAMcol;
				}
				
			}
			if($wWakuPattern > 2) {
				$x=$x+$WakuAMCEL+$WakuPM2CEL;
			}else{
				$x=$x+$WakuAMCEL;
			}
		}
		if($wWakuPattern > 2) {
			$x=$WakuAMCEL+$WakuPM1CEL;
			$y=$wWakuAMcol+$wWakuPM1col;
			for($i=0 ; $i<$SenyuDateCnt ; $i++){
				for($j=0 ; $j<$wHansu ; $j++){
					for($k=0 ; $k< $wWakuPM2col ; $k++){
						$wKoteihyouEX[$y] = $wKoteihyou1[$x];
						if($y==$Aki)
							$n=$x;
						$x++;
						$y++;
					}
					$y=$y+$wWakuPM1col+$wWakuAMcol;
					
				}
				$x=$x+$WakuPM1CEL+$WakuAMCEL;
			}
		}



	}

	########################################################
	# 空き削除処理
	########################################################

	if($work==3){
		$wKoteihyouEX = SPFWParameter::getValues('wwKoteihyouEX');		#配列
		$Aki = SPFWParameter::getValues('Aki');		#休工日
		$wWakuAMcol=ceil(($wWakuAM)/$wHansu);#入力枠数を班数でわった切り上げた数
		$WakuAMCEL = $wWakuAMcol*$wHansu;#一日のAMのセル数
		if($wWakuPattern > 2) {
			$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
			$wWakuPM2col=ceil(($wWakuPM2)/$wHansu);
			$WakuPM1CEL = $wWakuPM1col*$wHansu;#一日のPM1のセル数
			$WakuPM2CEL = $wWakuPM2col*$wHansu;#一日のPM2のセル数
		}else{
			$wWakuPM1col=ceil(($wWakuPM)/$wHansu);
			// $wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
			$WakuPM1CEL = $wWakuPM1col*$wHansu;#一日のPM1のセル数
		}

		$x=0;
		$y=0;
		for($i=0 ; $i<$SenyuDateCnt ; $i++){##工事順にソートするAM
			for($j=0 ; $j<$wHansu ; $j++){
				for($k=0 ; $k< $wWakuAMcol ; $k++){
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if($y==$Aki)
						$n=$x;
					$x++;
					$y++;
				}
				if($wWakuPattern > 2) {
					$y=$y+$wWakuPM1col+$wWakuPM2col;
				}else{
					$y=$y+$wWakuPM1col;
				}
				
			}
			if($wWakuPattern > 2) {
				$x=$x+$WakuPM1CEL+$WakuPM2CEL;
			}else{
				$x=$x+$WakuPM1CEL;
			}
		}
		$x=$WakuAMCEL;
		$y=$wWakuAMcol;
		for($i=0 ; $i<$SenyuDateCnt ; $i++){#工事順にソート（PM1）
			for($j=0 ; $j<$wHansu ; $j++){
				for($k=0 ; $k< $wWakuPM1col ; $k++){
					$wKoteihyou1[$x] = $wKoteihyouEX[$y];
					if($y==$Aki)
						$n=$x;
					$x++;
					$y++;
				}
				if($wWakuPattern > 2) {
					$y=$y+$wWakuAMcol+$wWakuPM2col;
				}else{
					$y=$y+$wWakuAMcol;
				}
				
			}
			if($wWakuPattern > 2) {
				$x=$x+$WakuAMCEL+$WakuPM2CEL;
			}else{
				$x=$x+$WakuAMCEL;
			}
		}
		if($wWakuPattern > 2) {#工事順にソート（PM2）
			$x=$WakuAMCEL+$WakuPM1CEL;
			$y=$wWakuAMcol+$wWakuPM1col;
			for($i=0 ; $i<$SenyuDateCnt ; $i++){
				for($j=0 ; $j<$wHansu ; $j++){
					for($k=0 ; $k< $wWakuPM2col ; $k++){
						$wKoteihyou1[$x] = $wKoteihyouEX[$y];
						if($y==$Aki)
							$n=$x;
						$x++;
						$y++;
					}
					$y=$y+$wWakuPM1col+$wWakuAMcol;
					
				}
				$x=$x+$WakuPM1CEL+$WakuAMCEL;
			}
		}
		ksort($wKoteihyou1);

		for($i=0;$i<count($wKoteihyou1);$i++){
			if(ctype_digit($wKoteihyou1[$i])){
				$LastRoom = $i;
			}
		}
	
		$l=0;
		for($i=0;$i<count($wKoteihyou1);$i++){
			if($n<=$i){
				if(ctype_digit($wKoteihyou1[$i])&&$l==0){
					$wKoteihyou2 = $wKoteihyou1[$n];
					$wKoteihyouEX[$n]="空き";
					for($j=0;$j<count($wKaiRoom3);$j++){
						if($wKaiRoom3[$j]==$wKoteihyou2){
							$Room=$j-1;
						}
					}
					$wKoteihyou1[$i]="空き";
					$Room++;
					$l++;
				}elseif(ctype_digit($wKoteihyou1[$i])){
					if($wKaiRoom3[$Room]){
						$wKoteihyou1[$i]=$wKaiRoom3[$Room];
						$Room++;
					}else{
						$wKoteihyou1[$i]="空き";
						$Room++;
					}
				}elseif($LastRoom<$i &&isset($wKaiRoom3[$Room])&& $wKoteihyou1[$i]=="空き" &&$l==1 ){
					$wKoteihyou1[$i]=$wKaiRoom3[$Room];
					$Room++;
					$l++;
				}
			}
		}



		$x=0;
		$y=0;
		for($i=0 ; $i<$SenyuDateCnt ; $i++){
			for($j=0 ; $j<$wHansu ; $j++){
				for($k=0 ; $k< $wWakuAMcol ; $k++){
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if($y==$Aki)
						$n=$x;
					$x++;
					$y++;
				}
				if($wWakuPattern > 2) {
					$y=$y+$wWakuPM1col+$wWakuPM2col;
				}else{
					$y=$y+$wWakuPM1col;
				}
				
			}
			if($wWakuPattern > 2) {
				$x=$x+$WakuPM1CEL+$WakuPM2CEL;
			}else{
				$x=$x+$WakuPM1CEL;
			}
		}
		$x=$WakuAMCEL;
		$y=$wWakuAMcol;
		for($i=0 ; $i<$SenyuDateCnt ; $i++){
			for($j=0 ; $j<$wHansu ; $j++){
				for($k=0 ; $k< $wWakuPM1col ; $k++){
					$wKoteihyouEX[$y] = $wKoteihyou1[$x];
					if($y==$Aki)
						$n=$x;
					$x++;
					$y++;
				}
				if($wWakuPattern > 2) {
					$y=$y+$wWakuAMcol+$wWakuPM2col;
				}else{
					$y=$y+$wWakuAMcol;
				}
				
			}
			if($wWakuPattern > 2) {
				$x=$x+$WakuAMCEL+$WakuPM2CEL;
			}else{
				$x=$x+$WakuAMCEL;
			}
		}
		if($wWakuPattern > 2) {
			$x=$WakuAMCEL+$WakuPM1CEL;
			$y=$wWakuAMcol+$wWakuPM1col;
			for($i=0 ; $i<$SenyuDateCnt ; $i++){
				for($j=0 ; $j<$wHansu ; $j++){
					for($k=0 ; $k< $wWakuPM2col ; $k++){
						$wKoteihyouEX[$y] = $wKoteihyou1[$x];
						if($y==$Aki)
							$n=$x;
						$x++;
						$y++;
					}
					$y=$y+$wWakuPM1col+$wWakuAMcol;
					
				}
				$x=$x+$WakuPM1CEL+$WakuAMCEL;
			}
		}

// id='".$m."' 　に飛ぶ

	}

	########################################################
	# 詳細工程表イメージ作成
	########################################################
	$wWakuAMcol=ceil(($wWakuAM)/$wHansu);#入力枠数を班数でわった切り上げた数
	// echo "<br> ".__LINE__." ここまでOK :".$wWakuPM1."/".$wHansu;
	if($wWakuPattern > 2) {
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
		$wWakuPM2col=ceil(($wWakuPM2)/$wHansu);
	}else{//2枠のパターンの時
		// $wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
		$wWakuPM1col=ceil(($wWakuPM)/$wHansu);

	}
	$Koteihyou="<table border='1' width='600px' cellspacing='5' style='text-align:center' cellpadding='7' class='ex_table'><tr><td width='130px'>日程</td><td class='ex_table2' width='60px'>曜日</td><td colspan = ".$wWakuAMcol." style='background-color:#ff7f50;' class='ex_table2'>AM</td>";
	if($wWakuPattern <= 2){
		$Koteihyou .="<td colspan = ".$wWakuPM1col." style='background-color:#87cefa'  class='ex_table2'>PM</td></tr>";
		$wWakuPM2col = 0;
	}else{
		$Koteihyou .="<td colspan = ".$wWakuPM1col." style='background-color:#87cefa'  class='ex_table2'>PM1</td><td colspan = ".$wWakuPM2col." style='background-color:#99FF66;' class='ex_table2'>PM2</td></tr>";
	}

	$date = new DateTime($SenyuStartDate);

	$week = ['日','月','火','水','木','金','土'];
	$m = 0 ;
	for($i=0 ; $i< $SenyuDateCnt ; $i++){
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate,$SHUKUJITULIST);
		$YoubiCD =  $date->format('w') ;
		$Youbi = $week[$YoubiCD];
		$week_str = $Youbi;
		if($week_str == '土')
			$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
		else if($week_str == '日')
			$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';


		if($result!==false || $YoubiCD == 0 || $YoubiCD == 6){
			$holidayCD = $i;
		}
		#★１休工日なら
		$KojiHoliday[$i] = false;
		if (count($wHoliday) > 0) { //休工日
			$KojiHoliday[$i] = (array_search($SenyuDate, $wHoliday) === false) ? false : true;
		}
	
		if($KojiHoliday[$i]){
			$Koteihyou .="<tr class='trholiday'><td rowspan =1".$wShukujitucolor[$i]." class='ex_table2'>".$SenyuDate."</td>";
			$Koteihyou .="<td rowspan =1".$wShukujitucolor[$i]." class='ex_table2'>".$week_str."</td>";
			$Koteihyou .="<td colspan=";
			$Koteihyou .=$wWakuAMcol+$wWakuPM1col+$wWakuPM2col;#うまくつかって上部をつくる　あとで♪
			$Koteihyou .=" rowspan = 1 style='text-align:center;' class='ex_table2'> ";
			$Koteihyou .="休工日";
			$Koteihyou .="</td></tr>";

		#★１休工日でない場合
		}else{
			$Koteihyou .="<tr class='trtop'><td rowspan =".$wHansu.$wShukujitucolor[$i]." class='ex_table2'>".$SenyuDate."</td>";
			$Koteihyou .="<td rowspan =".$wHansu.$wShukujitucolor[$i]." class='ex_table2'>".$week_str."</td>";
			
			#★２班数分くりかえす
			for($j=0 ; $j < $wHansu ;$j++ ){
				if($j!==0)#班が１つめなら<tr>つける
					$Koteihyou .="<tr>";
				#★３AM枠数の班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuAMcol ;$k++ ){
					$Koteihyou .="<td class='link_cell' style='background-color:#ffefd5;'>";
					if($wKoteihyouEX[$m]=="空き"){
						$Koteihyou .= "<a href='#' onclick='moveandaki(".$m.")' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
					}else{
						$Koteihyou .= '<font size="4"> <b>';
						$Koteihyou .= "<a href='#' onclick='moveandroom(".$m.")' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .= '</b></font>';
					}
					$m++;
					$Koteihyou .="</td>";
				}#★３AM枠数を班数で割った数分繰り返すEnd

				#★３-２PM１枠数を班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuPM1col ;$k++ ){
					$Koteihyou .="<td class='link_cell' style='background-color:#d2e5ff;'>";
					if($wKoteihyouEX[$m]=="空き"){
						$Koteihyou .= "<a href='#' onclick='moveandaki(".$m.")' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden'  id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
					}else{
						$Koteihyou .= '<font size="4"> <b>';
						$Koteihyou .= "<a href='#' onclick='moveandroom(".$m.")' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .= '</b></font>';
					}

					$m++;
					$Koteihyou .="</td>";
				}
				#★３-２PM１枠数を班数で割った数分繰り返す　End

				#★３-３PM２枠数を班数で割った数分繰り返す
				if($wWakuPattern > 2){
					for($k=0 ; $k < $wWakuPM2col ;$k++){
						$Koteihyou .="<td class='link_cell' style='background-color:#d1f9b7'>";
						if($wKoteihyouEX[$m]=="空き"){
							$Koteihyou .= "<a href='#' onclick='moveandaki(".$m.")' >".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						}else{
							$Koteihyou .= '<font size="4"> <b>';
							$Koteihyou .= "<a href='#' onclick='moveandroom(".$m.")' >".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .= '</b></font>';
						}

						$m++;
						$Koteihyou .="</td>";
					}
				}#★３-３PM２枠数を班数で割った数分繰り返す

				if($j!==0)
					$Koteihyou .="</tr>";
			}#★２班数分繰り返すEnd

		}
		$date->modify('+1 days');
		$Koteihyou .="</tr>";
	}
	$Koteihyou .= "</table>";
	for($i=0;$i<count($wKaiRoom3);$i++){
		if(array_search($wKaiRoom3[$i] ,$wKoteihyouEX )===false){
			if($ShortageRoom==""){
				$ShortageRoom = "　".$wKaiRoom3[$i];
			}else{
				$ShortageRoom .= "、".$wKaiRoom3[$i];
			}
		}
	}
	if(!$ShortageRoom){
		$IfShortage=FALSE;
		$IfNotShortage=TRUE;
		$SakuseiDisabled="";
	}else{
		$IfShortage=TRUE;
		$IfNotShortage=FALSE;
		$SakuseiDisabled='disabled';
	}
	$wShukujitucolor = SPFWTools::encodePluralValue($wShukujitucolor);
	$wKaiRoom3 = SPFWTools::encodePluralValue($wKaiRoom3);
	$wKyukobi = SPFWTools::encodePluralValue($wKyukobi);

	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_kanryo_hensyu.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);

?>
