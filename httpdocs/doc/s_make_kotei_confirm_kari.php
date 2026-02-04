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
#		echo "<br>key:".$key;
		${"$key"} = SPFWParameter::getValues($key);
	}


/*	$wHansu = SPFWParameter::getValues('wHansu');			#班数
	$wMinuteTime = SPFWParameter::getValues('wMinuteTime');	#工事施工時間（リアル）
	$wWakuPattern = SPFWParameter::getValues('wWakuPattern');
	$wWakuAM = SPFWParameter::getValues('wWakuAM');
	$wWakuPM1 = SPFWParameter::getValues('wWakuPM1');
	$wWakuPM2 = SPFWParameter::getValues('wWakuPM2');
	$wColsBlock = SPFWParameter::getValues('wColsBlock');
	$RowsLoop = SPFWParameter::getValues('RowsLoop');
*/	$ColsBlock = SPFWTools::decodePluralValue($wColsBlock);

	if( !$wWakuPM2)$wWakuPM = $wWakuPM1 ;
	$wWakuAM1 = $wWakuAM;
	for($i=0;$i<count($WAKUPATTERN[$wWakuPattern]['AMPM']);$i++){
		if($i!=0)
			$MaxWakuSu .= "-";

		$wWakuAMPM .= $WAKUPATTERN[$wWakuPattern]['AMPM'][$i]."：".${'wWaku'.$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]}."　";
//		echo 'wWaku'.$WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
		$MaxWakuSu .= ${'wWaku'.$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]};
		$MaxWakuSum += ${'wWaku'.$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]};
	}
	if($wWakuPM)
		$wWakuPM1 = $wWakuPM;
//	echo "$wWakuAM = $wWakuAM1";
	

//	echo $MaxWakuSu;

	if($wHansu == ""){
		$IfNULLError = TRUE;
		$HansuNotError = "<br>班数を入力してください";
	}
	if($wWakuPattern == ""){
		$IfNULLError = TRUE;
		$WakuPatternNotError = "<br>工事枠パターンを入力してください";
	}elseif($wWakuPattern <= 2){
		if($wWakuAM == ""||$wWakuPM1 == ""){
//			echo "<br>$wWakuAM == ''||$wWakuPM1 == ''";
			$IfNULLError = TRUE;
			$WakuAMPMNotError = "<br>最大工事枠数を入力してください";
		}
	}else{
		if($wWakuAM == ""||$wWakuPM1 == ""||$wWakuPM2 == ""){
//			echo "<br>$wWakuAM == ''||$wWakuPM1 == ''||$wWakuPM2 == ''";
			$IfNULLError = TRUE;
			$WakuAMPMNotError = "<br>最大工事枠数を入力してください";
		}
	}
	$wKojijun = SPFWParameter::getValues('wKojijun');		# 工事順
	$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature');#初日工事数考慮
	$wHoliday1 = SPFWParameter::getValues('wHoliday1');		#休日
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$wWakuPatternName = $WAKUPATTERN[$wWakuPattern]["Name"];
	if($wKojijun == 1){
		$KojijunDisp="下から横へ";
		$KojijunImg ="../images/kojijun1.png";
	}elseif($wKojijun == 2){
		$KojijunDisp = "上から横へ";
		$KojijunImg ="../images/kojijun2.png";
	}elseif($wKojijun == 3){
		$KojijunDisp = "下から縦へ（2列ずつ）";
		$KojijunImg ="../images/kojijun3.png";
	}elseif($wKojijun == 4){
		$KojijunDisp = "下から縦へ（3列ずつ）";
		$KojijunImg ="../images/kojijun3.png";
	}elseif($wKojijun == 5){
		$KojijunDisp = "上から縦へ（3列ずつ）";
		$KojijunImg ="../images/kojijun4.png";
	}elseif($wKojijun == 6){
		$KojijunDisp = "上から縦へ（3列ずつ）";
		$KojijunImg ="../images/kojijun4.png";
	}else{
		$IfNULLError = TRUE;
		$KojijunNotError = "<br>工事順を選択してください";
	}
	echo  $WakuAMPMNotError;
	if($IfNULLError){
		include_once("s_make_kanryo.php");
		exit;
	}

	if($wFirstDateFeature == 1){
		$FirstDateFeatureDisp = "初日午前NG";
	}elseif($wFirstDateFeature == 2){
		$FirstDateFeatureDisp = "初日15：00以降OK";
	}


	########################################################
	# 設定パラメータ取得取得
	########################################################


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

	$myKoji->Hansu = $wHansu;
	$myKoji->MinuteTime = $wMinuteTime;
	$myKoji->WakuPattern = $wWakuPattern;
	$myKoji->Kojijun = $wKojijun;
	$myKoji->FirstDateFeature = $wFirstDateFeature;

	$myKoji->MaxWakuSu = $MaxWakuSu;

	if (!$myKoji->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "依頼連携情報の更新に失敗しました。";
		showAdminSorryPage($ErrorString);
	}else{
		$IfOK = TRUE;
	}
	unset($myKoji);

	$wHoliday = SPFWTools::decodePluralValue($wHoliday1);

/*	for($i=1 ;$i<count($wHoliday) ;$i++){
		if(${"wHoliday".$i}){
			$date = new DateTime(${"wHoliday".$i});
				$wHoliday[] = $date->format('Y-m-d');
			unset($date);
		}
	}


*/


	$myBukkenMatrix = new BukkenMatrix($myDB);

	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);

	$wKaiRoom = $myBukkenMatrix->KaiRoom;
	$KaiRoom = SPFWTools::decodePluralValue($wKaiRoom); #配列
	$RoomSuu = count($KaiRoom);
	unset($myBukkenMatrix);


	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting tFileF Failed.", E_USER_ERROR);

	$BukkenName = $myBukken->BukkenName;
	unset($myBukken);

	########################################################
	# 工事順に部屋を並び替え
	########################################################
	unset($wKaiRoom);
	$y = 0;
	for ($x=0; $x < count($KaiRoom); $x++){
		#　前はいくつあるか不定　後ろはゼロサブで2桁固定 大きい部屋から格納されている
		$Room[$x] = substr($KaiRoom[$x],-2);
		#階高がかならずしも建物の部屋の前の文字を表していない 右うしろ2桁以外の文字 空に置き換え
		if(strlen($KaiRoom[$x])==4 && $KaiRoom[$x] == $Room[$x].$Room[$x]){
			$Kai[$x] = $Room[$x];
		}else{
			$Kai[$x] = str_replace( $Room[$x] , "", $KaiRoom[$x]); 
		}
		if($Kai[$x-1]!==$Kai[$x]){
			for($i=$y; $i < $x ;$i++){
				$KaiRoom2[$Kai[$x-1]][] = $KaiRoom[$i];
				$y = $x;
			}
		}
	}
	for($i=$y; $i < $x ; $i++){
		$KaiRoom2[$Kai[$x-1]][] = $KaiRoom[$i];
	}
	$Floor=0;
	for($i=0 ; $i<$Kai[0] ;$i++){
		if($Floor < count($KaiRoom2[$i]))
			$Floor = count($KaiRoom2[$i]);
	}
//	var_dump($KaiRoom2);
	if($wKojijun == 1){
		for($i=1 ; $i <= $Kai[0] ; $i++ ){
			for($j=0 ; $j< count($KaiRoom2[$i]); $j++){
				$KaiRoom3[] = $KaiRoom2[$i][$j];
			}
		}
	}

	if($wKojijun == 2){
		for($i=$Kai[0] ; $i > 0 ; $i--){
			for($j=0 ; $j< count($KaiRoom2[$i]); $j++){
				$KaiRoom3[] = $KaiRoom2[$i][$j];
			}
		}
	}
	$x=0;
	if($wKojijun == 3){
		for($i=1 ; $i <= $Kai[0] ; $i++){
			$y += count($KaiRoom2[$i]);
			for($j=0 ; $j < count($KaiRoom2[$i]) ; $j++ ){
				$room = substr($KaiRoom2[$i][$j],-2);
				$KaiRoom3_kari[$i][$room] = $KaiRoom2[$i][$j];
			}
		}
#	print_r($KaiRoom3_kari);
#	echo "<br>";
		for($j=0; $j<($y/2);$j++){
			for($i=1 ; $i <= $Kai[0] ; $i++){
#				echo $x;
				for($k=$x;$k<$x+2;$k++){
#					echo sprintf('%02d', $k);
					if($KaiRoom3_kari[$i][sprintf('%02d', ($k+1))]){
						$KaiRoom3[] = $KaiRoom3_kari[$i][sprintf('%02d', ($k+1))];
					}
				}
			}
			$x = $k;
		}
	}

	if($wKojijun == 4){
		for($i=1 ; $i <= $Kai[0] ; $i++){
			$y += count($KaiRoom2[$i]);
			for($j=0 ; $j < count($KaiRoom2[$i]) ; $j++ ){
				$room = substr($KaiRoom2[$i][$j],-2);
				$KaiRoom3_kari[$i][$room] = $KaiRoom2[$i][$j];
			}
		}
#	print_r($KaiRoom3_kari);
#	echo "<br>";
		for($j=0; $j<($y/3);$j++){
			for($i=1 ; $i <= $Kai[0] ; $i++){
#				echo $x;
				for($k=$x;$k<$x+3;$k++){
#					echo sprintf('%02d', $k);
					if($KaiRoom3_kari[$i][sprintf('%02d', ($k+1))]){
						$KaiRoom3[] = $KaiRoom3_kari[$i][sprintf('%02d', ($k+1))];
					}
				}
			}
			$x = $k;
		}
	}

	if($wKojijun == 5){
		for($i=$Kai[0] ; $i > 0 ; $i--){
			$y += count($KaiRoom2[$i]);
			for($j=0 ; $j < count($KaiRoom2[$i]) ; $j++ ){
				$room = substr($KaiRoom2[$i][$j],-2);
				$KaiRoom3_kari[$i][$room] = $KaiRoom2[$i][$j];
			}
		}
#	print_r($KaiRoom3_kari);
#	echo "<br>";
		for($j=0; $j<($y/2);$j++){
			for($i=$Kai[0] ; $i > 0 ; $i--){
#				echo $x;
				for($k=$x;$k<$x+2;$k++){
#					echo sprintf('%02d', $k);
					if($KaiRoom3_kari[$i][sprintf('%02d', ($k+1))]){
						$KaiRoom3[] = $KaiRoom3_kari[$i][sprintf('%02d', ($k+1))];
					}
				}
			}
			$x = $k;
		}
	}

	if($wKojijun == 6){
		for($i=$Kai[0] ; $i > 0 ; $i--){
			$y += count($KaiRoom2[$i]);
			for($j=0 ; $j < count($KaiRoom2[$i]) ; $j++ ){
				$room = substr($KaiRoom2[$i][$j],-2);
				$KaiRoom3_kari[$i][$room] = $KaiRoom2[$i][$j];
			}
		}
#	print_r($KaiRoom3_kari);
#	echo "<br>";
		for($j=0; $j<($y/3);$j++){
			for($i=$Kai[0] ; $i > 0 ; $i--){
#				echo $x;
				for($k=$x;$k<$x+3;$k++){
#					echo sprintf('%02d', $k);
					if($KaiRoom3_kari[$i][sprintf('%02d', ($k+1))]){
						$KaiRoom3[] = $KaiRoom3_kari[$i][sprintf('%02d', ($k+1))];
					}
				}
			}
			$x = $k;
		}
#	print_r($KaiRoom3);
	}

//	print_r($KaiRoom3);
	########################################################
	# 初日・土日祝考慮
	########################################################

	$wWakuSum1 = 0;
	$wWakuSum2 = 0;

	$week = ['日','月','火','水','木','金','土'];

	$date = new DateTime($SenyuStartDate);
	if($wWakuPattern <= 2)
		$wWakuPM2 = 0;

#★1	専有部工事日でまわす
	for($i=0 ; $i< $SenyuDateCnt ; $i++){
		$SenyuDate = $date->format('Y-m-d');
		#祝日判定
		$result = array_search($SenyuDate,$SHUKUJITULIST);

		#曜日取得
		$YoubiCD =  $date->format('w');

		#休工日取得
		if(count($wHoliday)>0)
			$rst = array_search($SenyuDate,$wHoliday);
		else
			$rst = false;

		#★２休工日でなかったら
		if($rst === false){
			#★３祝日、土、日なら
			if($result!==false || $YoubiCD == 0 || $YoubiCD == 6){
				#セット用　背景色
				$Shukujitucolor[$i] = " style='background-color:pink;'";

				#★４初日なら
				if($i==0){


					#$wFirstDateFeature  1：初日午前NG　２：初日15：00以降OK　の考慮無し♪　


					#初日考慮（午前NGか15:00以降Ok）あり　（初日で、土日でもある）	
					if($wFirstDateFeature > 0){
						#午後１と午後２たして、2で割って、きりあげて　２ひいた数値を加算
						$wWakuSum1 += ceil(($wWakuPM1+$wWakuPM2)/2)-2;
					#初日考慮なし
					}else{
						#午前、午後１、午後２を足して、2で割って、切り上げて、３ひいた数値を加算
						#総工事枠数を半分にして、3を引く　６－４－４なら、３＋２＋２－３＝４ってこと
						$wWakuSum1 += ceil(($wWakuAM+$wWakuPM1+$wWakuPM2)/2)-3;

					}


				#★４初日でない
				}else{
					#（各工事枠の半分の数をその日の枠の最大とした　Point
					#午前を２でわって切り上げた数　＋　午後1を２でわって切り上げた数　＋　午後２を２でわって切り上げた数　を加算
					$wWakuSum1 += ceil($wWakuAM/2)+ceil($wWakuPM1/2)+ceil($wWakuPM2/2);#wWakuPM2は０
					#上記と同じ	
					$wWakuSum2 += ceil($wWakuAM/2)+ceil($wWakuPM1/2)+ceil($wWakuPM2/2);
				}#★４ End

			#★３祝日、土、日でなかったら
			}else{
				#背景色をリセット
				$Shukujitucolor[$i] = "";
				#★４初日なら
				if($i==0){

					#$wFirstDateFeature  1：初日午前NG　２：初日15：00以降OK　　
					#初日考慮あり
					if($wFirstDateFeature > 0){
						if($wWakuPattern <= 2){
							#午前、午後の2枠なら午後１から２引いた数値を加算　
							$wWakuSum1 +=$wWakuPM1-2;
						}else{
							if($wFirstDateFeature == 1){
								#３枠なら午後1と午後２から２づつひいた数値を加算　
								$wWakuSum1 += $wWakuPM1-2+$wWakuPM2-2;
							}else{
								#３枠なら午後1と午後２から２づつひいた数値を加算　
								$wWakuSum1 += $wWakuPM2-2;
							}
						}
					#初日考慮なし
					}else{
						#初日考慮なしなら、午前工事枠分から２ひいた数値を加算
						if($wWakuPattern <= 2)
							$wWakuSum1 += $wWakuAM-2+$wWakuPM1-2;
						else
							$wWakuSum1 += $wWakuAM-2+$wWakuPM1-2+$wWakuPM2-2;
					}
				#★４初日でない	
				}else{
					if($wWakuPattern <= 2){
						$wWakuSum1 += $wWakuAM-1+$wWakuPM1-1;
						$wWakuSum2 += $wWakuAM-1+$wWakuPM1-1;
					}else{
						$wWakuSum1 += $wWakuAM-1+$wWakuPM1-1+$wWakuPM2-1;
						$wWakuSum2 += $wWakuAM-1+$wWakuPM1-1+$wWakuPM2-1;
					}
				}#★４ End
			#★３End
			}
			$wKyukobi[$i] = "";
		#★２休工日なら
		}else{
			${"Kyukobi".$i} = $i;
			$wKyukobi[$i] = $i;
		}
		#★２ End
		if($Kyukobi==""){
			$Kyukobi=-1;
		}
	
	$date->modify('+1 days');
	}

	#★1 End
	$wShukujitucolor = SPFWTools::encodePluralValue($Shukujitucolor);
	$wKyukobi = SPFWTools::encodePluralValue($wKyukobi);





	$Syoniti = count($KaiRoom) - $wWakuSum2 ;
	$Syoniti2 = count($KaiRoom) - $wWakuSum2 ;
 	if($Syoniti > $wWakuAM){
		if(array_search($SenyuStartDate,$SHUKUJITULIST)!==false){
			$wWakuMax = ($wWakuAM/2)-1;
		}else{
			$wWakuMax = ($wWakuAM/2);
		}
	}else{
			$wWakuMax = $Syoniti;
	}

#	if($wFirstDateFeature > 0 || $wFirstDateFeature !== "")
	if($wFirstDateFeature  > 0 ){#初日考慮は、つねにAMは０
		$SyonitiAM = 0;
		$SyonitiPM1 = $Syoniti - $SyonitiAM;
		if($wFirstDateFeature == 2)
			$SyonitiPM1 = 0;
	}else{
		for($i=0;$i<count($WAKUPATTERN[$wWakuPattern]['AMPM']);$i++){

			${"Syoniti".$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]} = ceil(($Syoniti*${"wWaku".$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]})/($MaxWakuSum));

		}
			$SyonitiAM = $SyonitiAM1;
			echo "<br>".__LINE__."行目初日PM1：".$SyonitiPM1;
/*		if($wWakuPattern > 2){#３枠
			$SyonitiAM = ceil(($Syoniti*$wWakuAM)/($wWakuAM+$wWakuPM1+$wWakuPM2));
			$SyonitiPM1 = ceil(($Syoniti*$wWakuPM1)/($wWakuAM+$wWakuPM1+$wWakuPM2));
			$SyonitiPM2 = $Syoniti - ($SyonitiAM + $SyonitiPM1);

		}else{#２枠
			$SyonitiAM = ceil(($Syoniti*$wWakuAM)/($wWakuAM+$wWakuPM1));
			$SyonitiPM1 = $Syoniti - $SyonitiAM;
		}
			echo "<br>".__LINE__."行目初日PM1：".$SyonitiPM1;
*/	}
	unset($date);

/*	echo "最大入力部屋数".$wWakuSum1;
	echo "<br>初日抜き最大入力部屋数".$wWakuSum2;
	echo "<br>初日AM最大入力部屋数".$SyonitiAM;
	echo "<br>初日余り部屋数".$Syoniti1;
	echo "<br>入れれなかった部屋数".$Syoniti2;
	echo "<br>初日部屋数".$Syoniti;
	echo "<br>初日PM1:".$SyonitiPM1;
*/	########################################################
	# 専有部工事の初日考慮
	########################################################
	
	
	
	########################################################
	# 詳細工程表（イメージ）部分
	########################################################
	$wWakuAMcol=ceil(($wWakuAM)/$wHansu);#入力枠数を班数でわった切り上げた数

	for($i=0;$i<count($WAKUPATTERN[$wWakuPattern]['AMPM']);$i++){

		${'wWaku'.$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]."col"} = ceil((${"wWaku".$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]})/$wHansu);
		$wWakucol += ${'wWaku'.$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]."col"};

	}
	
/*	if($wWakuPattern > 2) {
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
		$wWakuPM2col=ceil(($wWakuPM2)/$wHansu);
	}else{
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
	}
*/	$Koteihyou="<table border='1' width='600px' style='text-align:center'><tr><td>日程</td><td>曜日</td>";
	for($i=0;$i<count($WAKUPATTERN[$wWakuPattern]['AMPM']);$i++){
		if($WAKUPATTERN[$wWakuPattern]['AMPM'][$i]=='AM'||$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]=='AM1')
			$BGcolor="#ff7f50";
		elseif($WAKUPATTERN[$wWakuPattern]['AMPM'][$i]=='AM2')
			$BGcolor="#FF5192";
		elseif($WAKUPATTERN[$wWakuPattern]['AMPM'][$i]=='AM3')
			$BGcolor="#FFFF00";
		elseif($WAKUPATTERN[$wWakuPattern]['AMPM'][$i]=='PM'||$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]=='PM1')
			$BGcolor="#87cefa";
		elseif($WAKUPATTERN[$wWakuPattern]['AMPM'][$i]=='PM2')
			$BGcolor="#99FF66";

#FFFF00
		$Koteihyou .="<td colspan = ".${"wWaku".$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]."col"}." style='background-color:".$BGcolor."'>".$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]."</td>";
	}
	$Koteihyou .="</tr>";
	$date = new DateTime($SenyuStartDate);

	$m = 0 ;
	for($i=0 ; $i< $SenyuDateCnt ; $i++){
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate,$SHUKUJITULIST);
		$YoubiCD =  $date->format('w') ;
		$Youbi = $week[$YoubiCD];
		if($result!==false || $YoubiCD == 0 || $YoubiCD == 6){
			$holidayCD = $i;
		}
		#★１休工日なら
		if(${"Kyukobi".$i} === $i){
			$Koteihyou .="<tr><td rowspan =1".$Shukujitucolor[$i].">".$SenyuDate."</td>";
			$Koteihyou .="<td rowspan =1".$Shukujitucolor[$i].">".$Youbi."</td>";
			$Koteihyou .="<td colspan=";
			$Koteihyou .=$wWakucol;#うまくつかって上部をつくる　あとで♪
			$Koteihyou .=" rowspan = 1 style='text-align:center;'>";
			$Koteihyou .="休工日";
			$Koteihyou .="</td></tr>";

		#★１休工日でない場合
		}else{
			$Koteihyou .="<tr><td rowspan =".$wHansu.$Shukujitucolor[$i].">".$SenyuDate."</td>";
			$Koteihyou .="<td rowspan =".$wHansu.$Shukujitucolor[$i].">".$Youbi."</td>";
			
			#★２班数分くりかえす
			for($j=0 ; $j < $wHansu ;$j++ ){
				if($j!==0)#班が１つめなら<tr>つける
					$Koteihyou .="<tr>";
				#★３AM枠数の班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuAMcol ;$k++ ){
					$Koteihyou .="<td style='background-color:#ffefd5;'>";
					//echo "<br>488行目".$m."部屋目：".$KaiRoom3[$m];
					#　　班が１つ目で、AM枠の１つめなら
					if($k==0 && $j==0){
						#$iが０つまり初日　かつ　祝日、土日　かつ　６－２＜２
						if($i == 0 && $i === $holidayCD && ceil($wWakuAM/2)<=$SyonitiAM){
							#AM枠を２で割って切り上げて１ひく　6/2－1＝２	
							$l = $m+ceil($wWakuAM/2)-1;
#							echo "<br>495行目:".$m;
						#初日かつAM枠の半分より初日のセットする戸数がおおきい　かつ　祝日土日
						}elseif($i == 0 && ceil($wWakuAM/2)>$SyonitiAM && $i === $holidayCD && $SyonitiAM >= 0){
							#初日AM戸数
							$l = $m+$SyonitiAM;
#							echo "<br>500行目:".$m;
						#初日　平日
						}elseif($i == 0 && $wWakuAM-2<$SyonitiAM && $SyonitiAM > 0){				##初日平日
							#AM枠から２をひく
							$l = $m+$wWakuAM-2;
#							echo "<br>505行目:".$m;
						#土日祝
						}elseif($i === $holidayCD && $i != 0){
							#AM枠の半分を切り上げて
							$l = $m+ceil($wWakuAM/2);
#							echo "<br>510行目:".$m;
						#初日　AM枠から２＞＝初日のAM戸数　４＞＝２
						}elseif($i == 0 && $wWakuAM-2>=$SyonitiAM && $SyonitiAM >=0){
							#初日AM戸数たす
							$l = $m+$SyonitiAM;
	#						echo "<br>".__LINE__."行目:".$m."----syonitiAM:".$SyonitiAM;

						}elseif($i == 0 &&  $SyonitiAM <0){
							#初日AM戸数たす
							$l = $m;
#							echo "<br>".__LINE__."行目:".$m."----syonitiAM:".$SyonitiAM;

						#その他
						}else{
							#AM枠数から１ひく
							$l = $m+$wWakuAM-1;
#							echo "<br>521行目:".$m;

						}
					}
//				echo "<br>i:".$i."m:".$m;
					#初日　土日祝　初日AM戸数よりちいさいｍ　AM枠の半分切り上げ＞AM枠マス分＋$k＋１
					if( $i==0 && $i==$holidayCD && $m<$SyonitiAM && ceil($wWakuAM/2) > $j*$wWakuAMcol+$k+1 && isset($KaiRoom3[$m])){#初日土日祝なら
						$Koteihyou .= $KaiRoom3[$m];
						$KoteihyouEX[] = $KaiRoom3[$m];
						$m++;


					}elseif(ceil($wWakuAM/2) > $j*$wWakuAMcol+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$m])){#初日以外土日祝
						$Koteihyou .= $KaiRoom3[$m];
						$KoteihyouEX[] = $KaiRoom3[$m];
						$m++;


					}elseif( $i==0 && $i!==$holidayCD && $m<$SyonitiAM && ($wWakuAM-2) > $j*$wWakuAMcol+$k && isset($KaiRoom3[$m])){#初日平日なら
						$Koteihyou .= $KaiRoom3[$m];
						$KoteihyouEX[] = $KaiRoom3[$m];
						$m++;


					}elseif($wWakuAM > $j*$wWakuAMcol+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$m])){#初日以外平日
						$Koteihyou .= $KaiRoom3[$m];
						$KoteihyouEX[] = $KaiRoom3[$m];
						$m++;


					}elseif($wWakuAM > $j*$wWakuAMcol+$k && $wFirstDateFeature > 0 && $i==0){#残り
						$KoteihyouEX[] = "";


					}elseif($wWakuAM > $j*$wWakuAMcol+$k ){#残り
						$Koteihyou .= "<span style='color:blue;'>空き</span>";
						$KoteihyouEX[] = "空き";
#						echo "<br>".$m;
					}else{
						$KoteihyouEX[] = "";
					}
					$Koteihyou .="</td>";
				}#★３AM枠数を班数で割った数分繰り返すEnd

				#★３-２AM2枠数を班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuAM2col ;$k++ ){
					$Koteihyou .="<td style='background-color:#FFDDFF'>";
					if($k==0 && $j==0){
						$o=$l;
						if($i == 0 && $i !== $holidayCD && $wWakuAM2-2<$SyonitiAM2){##初日平日
							$l = $l+$wWakuAM2-2;
						}elseif($i == 0 && ceil($wWakuAM2/2)>$SyonitiAM2 && $i === $holidayCD){				##初日祝日
							$l = $l+$SyonitiAM2;
						}elseif($i == 0 && ceil($wWakuAM2/2)<=$SyonitiAM2 && $i === $holidayCD){
							$l = $l+ceil($wWakuAM2/2)-1;
						}elseif($i == 0 && $wWakuAM2-2<$SyonitiAM2){				##初日平日
							$l = $l+$wWakuAM2-2;
						}elseif($i === $holidayCD){
							$l = $l+ceil($wWakuAM2/2);
						}elseif($i == 0 && $wWakuAM2-2>=$SyonitiAM2 && $SyonitiAM2 >=0){##初日あまりの部屋
							$l = $l+$SyonitiAM2;
							echo "<br>".__LINE__."行目：".$i;
						}elseif($i == 0 && $SyonitiAM2 <0){
							$l = $l;
							echo "<br>".__LINE__."行目：".$i;
						}else{
							$l = $l+$wWakuAM2-1;
							echo "<br>".__LINE__."行目：".$i;
						}
					}
//				echo "<br>syoniti:".$SyonitiAM;;
					if(ceil($wWakuAM2/2) > $j*$wWakuAM2col+$k+1 && $i==0 && $i==$holidayCD && ($SyonitiAM+$SyonitiAM2)>$o && isset($KaiRoom3[$o])){#初日土日祝なら
						$Koteihyou .= $KaiRoom3[$o];
						$KoteihyouEX[] = $KaiRoom3[$o];
						$o++;
//					echo "<br>".__LINE__."行目<br>";
//					echo ceil($wWakuAM2/2).">$j*$wWakuAM2col+$k&&$i==0&&$i==".$holidayCD."&&($SyonitiAM+$SyonitiAM2)>$o && isset($KaiRoom3[$o])";
					}elseif(ceil($wWakuAM2/2) > $j*$wWakuAM2col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$o])){#初日以外土日祝
						$Koteihyou .= $KaiRoom3[$o];
						$KoteihyouEX[] = $KaiRoom3[$o];
						$o++;
//					echo "<br>".__LINE__;
					}elseif($wWakuAM2 > $j*$wWakuAM2col+$k+2 && $i==0 && $i!==$holidayCD &&  ($SyonitiAM+$SyonitiAM2)>$o && isset($KaiRoom3[$o])){#初日平日なら
						$Koteihyou .= $KaiRoom3[$o];
						$KoteihyouEX[] = $KaiRoom3[$o];
						$o++;
					}elseif($wWakuAM2 > $j*$wWakuAM2col+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$o])){#初日以外平日
						$Koteihyou .= $KaiRoom3[$o];
						$KoteihyouEX[] = $KaiRoom3[$o];
						$o++;
//					echo "<br>".__LINE__;
					}elseif($wWakuAM2 > $j*$wWakuAM2col+$k && $i==0 &&$wFirstDateFeature == 2){#残り
						$KoteihyouEX[] = "";
					}elseif($wWakuAM2 > $j*$wWakuAM2col+$k){#残り
						$Koteihyou .= "<span style='color:blue;'>空き</span>";
						$KoteihyouEX[] = "空き";
#							echo "<br>PM2:".$o;
					}else{
						$KoteihyouEX[] = "";
					}
					$Koteihyou .="</td>";
				}
				#★３-２AM2枠数を班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuAM3col ;$k++ ){
					$Koteihyou .="<td style='background-color:#FFDDFF'>";
					if($k==0 && $j==0){
						$p=$l;
						if($i == 0 && $i !== $holidayCD && $wWakuAM3-2<$SyonitiAM3){##初日平日
							$l = $l+$wWakuAM3-2;
						}elseif($i == 0 && ceil($wWakuAM3/2)>$SyonitiAM3 && $i === $holidayCD){				##初日祝日
							$l = $l+$SyonitiAM3;
						}elseif($i == 0 && ceil($wWakuAM3/2)<=$SyonitiAM3 && $i === $holidayCD){
							$l = $l+ceil($wWakuAM3/2)-1;
						}elseif($i == 0 && $wWakuAM3-2<$SyonitiAM3){				##初日平日
							$l = $l+$wWakuAM3-2;
						}elseif($i === $holidayCD){
							$l = $l+ceil($wWakuAM3/2);
						}elseif($i == 0 && $wWakuAM3-2>=$SyonitiAM3 && $SyonitiAM3 >=0){##初日あまりの部屋
							$l = $l+$SyonitiAM3;
							echo "<br>".__LINE__."行目：".$i;
						}elseif($i == 0 && $SyonitiAM3 <0){
							$l = $l;
							echo "<br>".__LINE__."行目：".$i;
						}else{
							$l = $l+$wWakuAM3-1;
							echo "<br>".__LINE__."行目：".$i;
						}
					}
//				echo "<br>syoniti:".$SyonitiAM;;
					if(ceil($wWakuAM3/2) > $j*$wWakuAM3col+$k+1 && $i==0 && $i==$holidayCD && ($SyonitiAM+$SyonitiAM3)>$p && isset($KaiRoom3[$p])){#初日土日祝なら
						$Koteihyou .= $KaiRoom3[$p];
						$KoteihyouEX[] = $KaiRoom3[$p];
						$p++;
//					echo "<br>".__LINE__."行目<br>";
//					echo ceil($wWakuAM3/2).">$j*$wWakuAM3col+$k&&$i==0&&$i==".$holidayCD."&&($SyonitiAM+$SyonitiAM3)>$p && isset($KaiRoom3[$p])";
					}elseif(ceil($wWakuAM3/2) > $j*$wWakuAM3col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$p])){#初日以外土日祝
						$Koteihyou .= $KaiRoom3[$p];
						$KoteihyouEX[] = $KaiRoom3[$p];
						$p++;
//					echo "<br>".__LINE__;
					}elseif($wWakuAM3 > $j*$wWakuAM3col+$k+2 && $i==0 && $i!==$holidayCD &&  ($SyonitiAM+$SyonitiAM3)>$p && isset($KaiRoom3[$p])){#初日平日なら
						$Koteihyou .= $KaiRoom3[$p];
						$KoteihyouEX[] = $KaiRoom3[$p];
						$p++;
					}elseif($wWakuAM3 > $j*$wWakuAM3col+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$p])){#初日以外平日
						$Koteihyou .= $KaiRoom3[$p];
						$KoteihyouEX[] = $KaiRoom3[$p];
						$p++;
//					echo "<br>".__LINE__;
					}elseif($wWakuAM3 > $j*$wWakuAM3col+$k && $i==0 &&$wFirstDateFeature == 2){#残り
						$KoteihyouEX[] = "";
					}elseif($wWakuAM3 > $j*$wWakuAM3col+$k){#残り
						$Koteihyou .= "<span style='color:blue;'>空き</span>";
						$KoteihyouEX[] = "空き";
#							echo "<br>PM2:".$p;
					}else{
						$KoteihyouEX[] = "";
					}
					$Koteihyou .="</td>";
				}
				#★３-２PM１枠数を班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuPM1col ;$k++ ){
					$Koteihyou .="<td style='background-color:#f0f8ff'>";
					if($k==0 && $j==0){
						if($i == 0 && $i !== $holidayCD && $wWakuPM1-2<$SyonitiPM1){##初日平日
							$n = $l+$wWakuPM1-2;
						}elseif($i == 0 && ceil($wWakuPM1/2)>$SyonitiPM1 && $i === $holidayCD){				##初日祝日
							$n = $l+$SyonitiPM1;
						}elseif($i == 0 && ceil($wWakuPM1/2)<=$SyonitiPM1 && $i === $holidayCD){
							$n = $l+ceil($wWakuPM1/2)-1;
						}elseif($i == 0 && $wWakuPM1-2<$SyonitiPM1){				##初日平日
							$n = $l+$wWakuPM1-2;
						}elseif($i === $holidayCD){
							$n = $l+ceil($wWakuPM1/2);
						}elseif($i == 0 && $wWakuPM1-2>=$SyonitiPM1 && $SyonitiPM1 >=0){##初日あまりの部屋
							$n = $l+$SyonitiPM1;
						}elseif($i == 0 && $SyonitiPM1 <0){
							$n = $l;
						}else{
							$n = $l+$wWakuPM1-1;
						}
					}
//				echo "<br>syoniti:".$SyonitiAM;;
					if(ceil($wWakuPM1/2) > $j*$wWakuPM1col+$k+1 && $i==0 && $i==$holidayCD && ($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])){#初日土日祝なら
						$Koteihyou .= $KaiRoom3[$l];
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
//					echo "<br>".__LINE__."行目<br>";
//					echo ceil($wWakuPM1/2).">$j*$wWakuPM1col+$k&&$i==0&&$i==".$holidayCD."&&($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])";
					}elseif(ceil($wWakuPM1/2) > $j*$wWakuPM1col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$l])){#初日以外土日祝
						$Koteihyou .= $KaiRoom3[$l];
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
//					echo "<br>".__LINE__;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k+2 && $i==0 && $i!==$holidayCD &&  ($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])){#初日平日なら
						$Koteihyou .= $KaiRoom3[$l];
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$l])){#初日以外平日
						$Koteihyou .= $KaiRoom3[$l];
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
//					echo "<br>".__LINE__;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k && $i==0 &&$wFirstDateFeature == 2){#残り
						$KoteihyouEX[] = "";
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k){#残り
						$Koteihyou .= "<span style='color:blue;'>空き</span>";
						$KoteihyouEX[] = "空き";
#							echo "<br>PM2:".$l;
					}else{
						$KoteihyouEX[] = "";
					}
					$Koteihyou .="</td>";
				}
					#★３-２PM１枠数を班数で割った数分繰り返す　End
//				echo "<br>i:".$i."n:".$n;
				#★３-３PM２枠数を班数で割った数分繰り返す
				if($l==5)
					echo $SyonitiPM1;
				if($wWakuPattern > 2){
					for($k=0 ; $k < $wWakuPM2col ;$k++){
						$Koteihyou .="<td style='background-color:#E6FFE9'>";
						if(ceil($wWakuPM2/2) > $j*$wWakuPM2col+$k+1 && $i==0 && $i==$holidayCD && $Syoniti>$n && isset($KaiRoom3[$n]) ){#初日土日祝なら
							$Koteihyou .= $KaiRoom3[$n];
							$KoteihyouEX[] = $KaiRoom3[$n];
							$n++;
						}elseif(ceil($wWakuPM2/2) > $j*$wWakuPM2col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$n])){#初日以外土日祝
							$Koteihyou .= $KaiRoom3[$n];
							$KoteihyouEX[] = $KaiRoom3[$n];
							$n++;
						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k+2 && $i==0 && $i!==$holidayCD &&  $Syoniti>$n && isset($KaiRoom3[$n])){#初日平日なら
							$Koteihyou .= $KaiRoom3[$n];
							$KoteihyouEX[] = $KaiRoom3[$n];
							$n++;
						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$n])){#初日以外平日
							$Koteihyou .= $KaiRoom3[$n];
							$KoteihyouEX[] = $KaiRoom3[$n];
							$n++;
						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k){#残り
							$Koteihyou .= "<span style='color:blue;'>空き</span>";
							$KoteihyouEX[] = "空き";
#							echo "<br>PM2:".$n;
						}else{
							$KoteihyouEX[] = "";
						}
						$Koteihyou .="</td>";
					}
				}#★３-３PM２枠数を班数で割った数分繰り返す

				if($j!==0)
					$Koteihyou .="</tr>";
			}#★２班数分繰り返すEnd

			if($wWakuPattern > 2){
				$m = $n;
			}else{
				$m = $l;
			}
		}
		$date->modify('+1 days');
		$Koteihyou .="</tr>";
	}
	$Koteihyou .= "</table>";

	if(count($KaiRoom3) > $m){
		$IfError = TRUE;
		$SakuseiDisabled = disabled;
	}

	$wKoteihyouEX = SPFWTools::encodePluralValue($KoteihyouEX);
	$wKaiRoom3 = SPFWTools::encodePluralValue($KaiRoom3);



	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_kanryo_confirm.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);


?>
