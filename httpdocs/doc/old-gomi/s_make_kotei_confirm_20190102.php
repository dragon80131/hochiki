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



	$wHansu = SPFWParameter::getValues('wHansu');			#班数
	$wMinuteTime = SPFWParameter::getValues('wMinuteTime');	#工事施工時間（リアル）
	$wWakuPattern = SPFWParameter::getValues('wWakuPattern');
	$wWakuAM = SPFWParameter::getValues('wWakuAM');
	$wWakuPM1 = SPFWParameter::getValues('wWakuPM1');
	$wWakuPM2 = SPFWParameter::getValues('wWakuPM2');
	$wColsBlock = SPFWParameter::getValues('wColsBlock');
	$RowsLoop = SPFWParameter::getValues('RowsLoop');
	$ColsBlock = SPFWTools::decodePluralValue($wColsBlock);

	if( !$wWakuPM2)$wWakuPM = $wWakuPM1 ;
	if($wWakuPattern > 2){
		$wWakuAMPM = "AM：".$wWakuAM."　PM1：".$wWakuPM1."　PM2：".$wWakuPM2;
	}else{
		$wWakuAMPM = "AM：".$wWakuAM."　PM：".$wWakuPM1;
	}
	if($wHansu == ""){
		$IfNULLError = TRUE;
		$HansuNotError = "<br>班数を入力してください";
	}
	if($wWakuPattern == ""){
		$IfNULLError = TRUE;
		$WakuPatternNotError = "<br>工事枠パターンを入力してください";
	}elseif($wWakuPattern <= 2){
		if($wWakuAM == ""||$wWakuPM1 == ""){
			$IfNULLError = TRUE;
			$WakuAMPMNotError = "<br>最大工事枠数を入力してください";
		}
	}else{
		if($wWakuAM == ""||$wWakuPM1 == ""||$wWakuPM2 == ""){
			$IfNULLError = TRUE;
			$WakuAMPMNotError = "<br>最大工事枠数を入力してください";
		}
	}
	$wKojijun = SPFWParameter::getValues('wKojijun');		# 工事順
	$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature');#初日工事数考慮
	$wHoliday1 = SPFWParameter::getValues('wHoliday1');		#休日
	$wHoliday2 = SPFWParameter::getValues('wHoliday2');		
	$wHoliday3 = SPFWParameter::getValues('wHoliday3');
	$wHoliday4 = SPFWParameter::getValues('wHoliday4');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$wWakuPatternName = $WAKUPATTERN[$wWakuPattern]["Name"];
	if($wKojijun == 1){
		$KojijunDisp="下から横へ";
		$KojijunImg ="../images/kojijun1.png";
	}elseif($wKojijun == 2){
		$KojijunDisp = "上から横へ";
		$KojijunImg ="../images/kojijun2.png";
	}elseif($wKojijun == 3){
		$KojijunDisp = "下から縦へ（2列づつ）";
		$KojijunImg ="../images/kojijun3.png";
	}elseif($wKojijun == 4){
		$KojijunDisp = "下から縦へ（3列づつ）";
		$KojijunImg ="../images/kojijun4.png";
	}else{
		$IfNULLError = TRUE;
		$KojijunNotError = "<br>工事順を選択してください";
	}
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
	$myKoji->Holiday1 = $wHoliday1;
	$myKoji->Holiday2 = $wHoliday2;
	$myKoji->Holiday3 = $wHoliday3;
	$myKoji->Holiday4 = $wHoliday4;

	
	if (!$myKoji->executeUpdate()){
		$ErrorString = array();
		$ErrorString[] = "依頼連携情報の更新に失敗しました。";
		showAdminSorryPage($ErrorString);
	}else{
		$IfOK = TRUE;
	}
	unset($myKoji);


	for($i=1 ;$i<5 ;$i++){
		if(${"wHoliday".$i}){
			$date = new DateTime(${"wHoliday".$i});
				$wHoliday[] = $date->format('Y-m-d');
			unset($date);
		}
	}


	#print_r($wHoliday);



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
		$Kai[$x] = str_replace( $Room[$x] , "", $KaiRoom[$x]); 
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
	for($i=0 ; $i<count($KaiRoom2) ;$i++){
		if($Floor < count($KaiRoom2[$i]))
			$Floor = count($KaiRoom2[$i]);
	}
	
	if($wKojijun == 1){
		for($i=1 ; $i <= count($KaiRoom2) ; $i++ ){
			for($j=0 ; $j< count($KaiRoom2[$i]); $j++){
				$KaiRoom3[] = $KaiRoom2[$i][$j];
			}
		}
	}

	if($wKojijun == 2){
		for($i=count($KaiRoom2) ; $i > 0 ; $i--){
			for($j=0 ; $j< count($KaiRoom2[$i]); $j++){
				$KaiRoom3[] = $KaiRoom2[$i][$j];
			}
		}
	}
	$x=0;
	if($wKojijun == 3){
		for($k=0 ; $k < ceil($Floor/2) ;$k++){
			for($i=1 ; $i <= count($KaiRoom2) ; $i++ ){
				for($j=$x ; $j< $x+2; $j++){
					if($KaiRoom2[$i][$j])
						$KaiRoom3[] = $KaiRoom2[$i][$j];
				}
			}
			$x = $j;
		}
	}

	if($wKojijun == 4){
		for($k=0 ; $k < ceil($Floor/3) ;$k++){
			for($i=1 ; $i <= count($KaiRoom2) ; $i++ ){
				for($j=$x ; $j< $x+3; $j++){
					if($KaiRoom2[$i][$j])
						$KaiRoom3[] = $KaiRoom2[$i][$j];
				}
			}
			$x = $j;
		}
	}


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
		$YoubiCD =  $date->format('w') ;
	
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
echo "<br>303行目".$wFirstDateFeature;


					#初日考慮（午前NGか15:00以降Ok）あり　（初日で、土日でもある）	
					if($wFirstDateFeature > 0){
						#午前、午後１、午後２を足して、2で割って、切り上げて、３ひいた数値を加算
						#総工事枠数を半分にして、3を引く　６－４－４なら、３＋２＋２－３＝４ってこと
						$wWakuSum1 += ceil(($wWakuAM+$wWakuPM1+$wWakuPM2)/2)-3;
echo "<br>311行目初日考慮あり：".$wWakuSum1;
					#初日考慮なし
					}else{
						#午後１と午後２たして、2で割って、きりあげて　２ひいた数値を加算
						$wWakuSum1 += ceil(($wWakuPM1+$wWakuPM2)/2)-2;
echo "<br>316行目初日考慮なし：".$wWakuSum1;
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
						if($wWakuPattern <= 2)
							#午前、午後の2枠なら午後１から２引いた数値を加算　
							$wWakuSum1 +=$wWakuPM1-2;
						else
							#３枠なら午後1と午後２から２づつひいた数値を加算　
							$wWakuSum1 += $wWakuPM1-2+$wWakuPM2-2;
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

echo "<br>378行目 wWakuSum2--$wWakuSum1:".$wWakuSum2."---".$wWakuSum1;

$wShukujitucolor = SPFWTools::encodePluralValue($Shukujitucolor);
$wKyukobi = SPFWTools::encodePluralValue($wKyukobi);







	$Syoniti = count($KaiRoom) - $wWakuSum2 ;
	$Syoniti2 = count($KaiRoom) - $wWakuSum2 ;
echo "<br>382行目Syoniti：".$Syoniti;#4
 	if($Syoniti > $wWakuAM){
		if(array_search($SenyuStartDate,$SHUKUJITULIST)!==false){
			$wWakuMax = ($wWakuAM/2)-1;
echo "<br>386行目".$wWakuMax;
		}else{
			$wWakuMax = ($wWakuAM/2);
echo "<br>389行目".$wWakuMax;
		}
	}else{
			$wWakuMax = $Syoniti;
	}

#	if($wFirstDateFeature > 0 || $wFirstDateFeature !== "")
	if($wFirstDateFeature > 0 )#初日考慮は、つねにAMは０
		$SyonitiAM = 0;
	else
		$SyonitiAM = ceil(($Syoniti*$wWakuAM)/($wWakuAM+$wWakuPM1+$wWakuPM2));
	if($wWakuPattern > 2){#３枠
		$SyonitiPM1 = ceil(($Syoniti*$wWakuPM1)/($wWakuAM+$wWakuPM1+$wWakuPM2));
		$SyonitiPM2 = $Syoniti - ($SyonitiAM + $SyonitiPM1);

echo "<br>404行目初日PM1－初日PM2：".$SyonitiPM1."---".$SyonitiPM2 ;
	}else{#２枠
		$SyonitiPM1 = $Syoniti - $SyonitiAM;
	}
	unset($date);

	echo "最大入力部屋数".$wWakuSum1;
	echo "<br>初日抜き最大入力部屋数".$wWakuSum2;
	echo "<br>初日AM最大入力部屋数".$SyonitiAM;
	echo "<br>初日余り部屋数".$Syoniti1;
	echo "<br>入れれなかった部屋数".$Syoniti2;
	echo "<br>初日部屋数".$Syoniti;
	echo "<br>初日PM1:".$SyonitiPM1;
	########################################################
	# 専有部工事の初日考慮
	########################################################
	
	
	
	########################################################
	# 詳細工程表（イメージ）部分
	########################################################
	$wWakuAMcol=ceil(($wWakuAM)/$wHansu);#入力枠数を班数でわった切り上げた数
	
	if($wWakuPattern > 2) {
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
		$wWakuPM2col=ceil(($wWakuPM2)/$wHansu);
	}else{
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
	}
	$Koteihyou="<table border='1' width='600px' style='text-align:center'><tr><td>日程</td><td>曜日</td><td colspan = ".$wWakuAMcol." style='background-color:#ff7f50;'>AM</td><td colspan = ".$wWakuPM1col." style='background-color:#87cefa'>PM1</td>";
	if($wWakuPattern <= 2){
		$Koteihyou .="</tr>";
		$wWakuPM2col = 0;
	}else{
		$Koteihyou .="<td colspan = ".$wWakuPM2col." style='background-color:#99FF66;'>PM2</td></tr>";
	}

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
			$Koteihyou .=$wWakuAMcol+$wWakuPM1col+$wWakuPM2col;#うまくつかって上部をつくる　あとで♪
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

					#　　班が１つ目で、AM枠の１つめなら
					if($k==0 && $j==0){
						#$iが０つまり初日　かつ　祝日、土日　かつ　６－２＜２
						if($i == 0 && $i === $holidayCD && $wWakuAM-2<$SyonitiAM){
							#AM枠を２で割って切り上げて１ひく　6/2－1＝２	
							$l = $m+ceil($wWakuAM/2)-1;

						#初日かつAM枠の半分より初日のセットする戸数がおおきい　かつ　祝日土日
						}elseif($i == 0 && ceil($wWakuAM/2)>$SyonitiAM && $i === $holidayCD && $SyonitiAM >= 0){
							#初日AM戸数
							$l = $m+$SyonitiAM;

						#初日　平日
						}elseif($i == 0 && $wWakuAM-2<$SyonitiAM){				##初日平日
							#AM枠から２をひく
							$l = $m+$wWakuAM-2;

						#土日祝
						}elseif($i === $holidayCD){
							#AM枠の半分を切り上げて
							$l = $m+ceil($wWakuAM/2);

						#初日　AM枠から２＞＝初日のAM戸数　４＞＝２
						}elseif($i == 0 && $wWakuAM-2>=$SyonitiAM){
							#初日AM戸数たす
							$l = $m+$SyonitiAM;
						#その他
						}else{
							#AM枠数から１ひく
							$l = $m+$wWakuAM-1;
						}
					}

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

					}else{
						$KoteihyouEX[] = "";
					}
					$Koteihyou .="</td>";
				}#★３AM枠数を班数で割った数分繰り返すEnd

				#★３-２PM１枠数を班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuPM1col ;$k++ ){
					$Koteihyou .="<td style='background-color:#f0f8ff'>";
					if($k==0 && $j==0){
						if($i == 0 && $i !== $holidayCD && $wWakuPM1-2<$SyonitiPM1){##初日土日祝
							$n = $l+$SyonitiPM1;
						}elseif($i == 0 && ceil($wWakuPM1/2)>$SyonitiPM1 && $i === $holidayCD){				##初日祝日
							$n = $l+$SyonitiPM1;
						}elseif($i == 0 && $wWakuPM1-2<$SyonitiPM1){				##初日平日
							$n = $l+$SyonitiPM1;
						}elseif($i === $holidayCD){
							$n = $l+ceil($wWakuPM1/2);
						}elseif($i == 0 && $wWakuPM1-2>=$SyonitiPM1){
							$n = $l+$SyonitiPM1;
						}else{
							$n = $l+$wWakuPM1-1;
						}
					}
echo "<br>565行目".ceil($wWakuPM1/2)." > ".$j*$wWakuPM1col."+".$k."+1 && $i==0 && $i==$holidayCD && (".$SyonitiAM."+".$SyonitiPM1.")>".$l ;
				if(ceil($wWakuPM1/2) > $j*$wWakuPM1col+$k && $i==0 && $i==$holidayCD && ($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])){#初日土日祝なら
						$Koteihyou .= $KaiRoom3[$l];
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif(ceil($wWakuPM1/2) > $j*$wWakuPM1col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$l])){#初日以外土日祝
						$Koteihyou .= $KaiRoom3[$l];
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k+2 && $i==0 && $i!==$holidayCD &&  ($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])){#初日平日なら
						$Koteihyou .= $KaiRoom3[$l];
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$l])){#初日以外平日
						$Koteihyou .= $KaiRoom3[$l];
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k){#残り
						$Koteihyou .= "<span style='color:blue;'>空き</span>";
						$KoteihyouEX[] = "空き";
					}else{
						$KoteihyouEX[] = "";
					}
					$Koteihyou .="</td>";
				}
				#★３-２PM１枠数を班数で割った数分繰り返す　End

				#★３-３PM２枠数を班数で割った数分繰り返す
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
	}

	$wKoteihyouEX = SPFWTools::encodePluralValue($KoteihyouEX);
	$wKaiRoom3 = SPFWTools::encodePluralValue($KaiRoom3);

#	print_r($wKoteihyouEX);


/*
###★パターンB 全体つめぎみ　平日PM1つめぎみ
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
		$YoubiCD =  $date->format('w') ;
	
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

					#初日考慮（午前NGか15:00以降Ok）あり　（初日で、土日でもある）	
					if($wFirstDateFeature > 0){
						#午前、午後１、午後２を足して、2で割って、切り上げて、３ひいた数値を加算
						#総工事枠数を半分にして、3を引く　６－４－４なら、３＋２＋２－３＝４ってこと
						$wWakuSum1 += ceil(($wWakuAM+$wWakuPM1+$wWakuPM2)/2)-2;
echo "<br>311行目初日考慮あり：".$wWakuSum1;
					#初日考慮なし
					}else{
						#午後１と午後２たして、2で割って、きりあげて　２ひいた数値を加算
						$wWakuSum1 += ceil(($wWakuPM1+$wWakuPM2)/2)-1;#♪B
echo "<br>316行目初日考慮なし：".$wWakuSum1;
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
						if($wWakuPattern <= 2)
							#午前、午後の2枠なら午後１から２引いた数値を加算　
							$wWakuSum1 +=$wWakuPM1-2;
						else
							#３枠なら午後1と午後２から２づつひいた数値を加算　
							$wWakuSum1 += $wWakuPM1-2+$wWakuPM2-2;
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
		#★２休工日なら
		}else{
			${"Kyukobi".$i} = $i;
		}
		#★２ End
		if($Kyukobi==""){
			$Kyukobi=-1;
		}
	
	$date->modify('+1 days');
	}
#★1 End

echo "<br>378行目 wWakuSum2--$wWakuSum1:".$wWakuSum2."---".$wWakuSum1;

	$Syoniti = count($KaiRoom) - $wWakuSum2 ;
	$Syoniti2 = count($KaiRoom) - $wWakuSum2 ;
echo "<br>382行目Syoniti：".$Syoniti;#4
 	if($Syoniti > $wWakuAM){
		if(array_search($SenyuStartDate,$SHUKUJITULIST)!==false){
			$wWakuMax = ($wWakuAM/2)-1;
echo "<br>386行目".$wWakuMax;
		}else{
			$wWakuMax = ($wWakuAM/2);
echo "<br>389行目".$wWakuMax;
		}
	}else{
			$wWakuMax = $Syoniti;
	}
echo "<br>775行目".$wWakuMax;
#	if($wFirstDateFeature > 0 || $wFirstDateFeature !== "")
	if($wFirstDateFeature > 0 )#初日考慮は、つねにAMは０
		$SyonitiAM = 0;
	else
		$SyonitiAM = ceil(($Syoniti*$wWakuAM)/($wWakuAM+$wWakuPM1+$wWakuPM2))+1;#♪

echo "<br>782行目".$SyonitiAM;#2

	if($wWakuPattern > 2){#３枠
		$SyonitiPM1 = ceil(($Syoniti*$wWakuPM1)/($wWakuAM+$wWakuPM1+$wWakuPM2))+1;
		$SyonitiPM2 = ( ( $Syoniti - ($SyonitiAM + $SyonitiPM1)) > 0 )? ( $Syoniti - ($SyonitiAM + $SyonitiPM1) ):"0" ;#♪

echo "<br>404行目初日PM1－初日PM2：".$SyonitiPM1."---".$SyonitiPM2 ;
	}else{#２枠
		$SyonitiPM1 = $Syoniti - $SyonitiAM;
	}
	unset($date);

	echo "最大入力部屋数".$wWakuSum1;
	echo "<br>初日抜き最大入力部屋数".$wWakuSum2;
	echo "<br>初日AM最大入力部屋数".$SyonitiAM;
	echo "<br>初日余り部屋数".$Syoniti1;
	echo "<br>入れれなかった部屋数".$Syoniti2;
	echo "<br>初日部屋数".$Syoniti;
	########################################################
	# 専有部工事の初日考慮
	########################################################
	
	
	
	########################################################
	# 詳細工程表（イメージ）部分
	########################################################
	$wWakuAMcol=ceil(($wWakuAM)/$wHansu);#入力枠数を班数でわった切り上げた数
echo "<br>810行目wWakuAMcol".$wWakuAMcol;#3
	if($wWakuPattern > 2) {
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
		$wWakuPM2col=ceil(($wWakuPM2)/$wHansu);
	}else{
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
	}
	$Koteihyou="<table border='1' width='600px' style='text-align:center'><tr><td>日程</td><td>曜日</td><td colspan = ".$wWakuAMcol." style='background-color:#ff7f50;'>AM</td><td colspan = ".$wWakuPM1col." style='background-color:#87cefa'>PM1</td>";
	if($wWakuPattern <= 2){
		$Koteihyou .="</tr>";
		$wWakuPM2col = 0;
	}else{
		$Koteihyou .="<td colspan = ".$wWakuPM2col." style='background-color:#99FF66;'>PM2</td></tr>";
	}

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
			$Koteihyou .=$wWakuAMcol+$wWakuPM1col+$wWakuPM2col;#うまくつかって上部をつくる　あとで♪
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

					#　　班が１つ目で、AM枠の１つめなら
					if($k==0 && $j==0){
						#$iが０つまり初日　かつ　祝日、土日　かつ　６－２＜２
						if($i == 0 && $i === $holidayCD && $wWakuAM-2<$SyonitiAM){
							#AM枠を２で割って切り上げて１ひく　6/2－1＝２	
							$l = $m+ceil($wWakuAM/2)-1;
echo "<br>865行目　l：".$l;#3
						#初日かつAM枠の半分より初日のセットする戸数がおおきい　かつ　祝日土日
						}elseif($i == 0 && ceil($wWakuAM/2)>$SyonitiAM && $i === $holidayCD && $SyonitiAM >= 0){
							#初日AM戸数
							$l = $m+$SyonitiAM ;#
echo "<br>870行目　m：".$m." l:".$l;#2
						#初日　平日
						}elseif($i == 0 && $wWakuAM-2<$SyonitiAM){				##初日平日
							#AM枠から２をひく
							$l = $m+$wWakuAM-2;

						#土日祝
						}elseif($i === $holidayCD){
							#AM枠の半分を切り上げて
							$l = $m+ceil($wWakuAM/2);

						#初日　AM枠から２＞＝初日のAM戸数　４＞＝２
						}elseif($i == 0 && $wWakuAM-2>=$SyonitiAM){
							#初日AM戸数たす
							$l = $m+$SyonitiAM;
						#その他
						}else{
							#AM枠数から１ひく
							$l = $m+$wWakuAM-1;
						}
					}

					#初日　土日祝　初日AM戸数よりちいさいｍ　AM枠の半分切り上げ＞AM枠マス分＋$k＋１ ♪きびしめ　土日祝半分を２→1.5
					if( $i==0 && $i==$holidayCD && $m<$SyonitiAM && ceil($wWakuAM/1.5) > $j*$wWakuAMcol+$k+1 && isset($KaiRoom3[$m])){#初日土日祝なら
						$Koteihyou .= $KaiRoom3[$m];
echo "<br>895行目".$m."<".$SyonitiAM." && ".ceil($wWakuAM/1.5)." > ".$j."*".$wWakuAMcol."+".$k."+1" ;
echo "<br>897行目★　AM　$m：".$m." :". $KaiRoom3[$m];#2
						$KoteihyouEX[] = $KaiRoom3[$m];
						$m++;

					}elseif(ceil($wWakuAM/1.5) > $j*$wWakuAMcol+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$m])){#初日以外土日祝
						$Koteihyou .= $KaiRoom3[$m];

echo "<br>904行目　AM　$m：". $m." :". $KaiRoom3[$m];#2
						$KoteihyouEX[] = $KaiRoom3[$m];
						$m++;
					}elseif( $i==0 && $i!==$holidayCD && $m<$SyonitiAM && ($wWakuAM-2) > $j*$wWakuAMcol+$k && isset($KaiRoom3[$m])){#初日平日なら
						$Koteihyou .= $KaiRoom3[$m];

echo "<br>910行目★　AM　$m：". $m." :". $KaiRoom3[$m];#2
						$KoteihyouEX[] = $KaiRoom3[$m];
						$m++;
					}elseif($wWakuAM > $j*$wWakuAMcol+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$m])){#初日以外平日
						$Koteihyou .= $KaiRoom3[$m];

echo "<br>916行目　AM　$m：". $m." :". $KaiRoom3[$m];#2
						$KoteihyouEX[] = $KaiRoom3[$m];
						$m++;
					}elseif($wWakuAM > $j*$wWakuAMcol+$k && $wFirstDateFeature > 0 && $i==0){#残り
						$KoteihyouEX[] = "";

echo "<br>920行目　AM　$m：". $m." :". $KaiRoom3[$m];#2
					}elseif($wWakuAM > $j*$wWakuAMcol+$k ){#残り
						$Koteihyou .= "<span style='color:blue;'>空き</span>";
						$KoteihyouEX[] = "空き";
echo "<br>920行目　AM　$m：". $m;#2
					}else{
						$KoteihyouEX[] = "";
echo "<br>927行目　　$m：". $m;#2
					}
					$Koteihyou .="</td>";
				}#★３AM枠数を班数で割った数分繰り返すEnd

				#★３-２PM１枠数を班数で割った数分繰り返す
echo "<br>934行目wWakuPM1col：".$wWakuPM1col;
				for($k=0 ; $k < $wWakuPM1col ;$k++ ){
					$Koteihyou .="<td style='background-color:#f0f8ff'>";
					if($k==0 && $j==0){
						if($i == 0 && $i !== $holidayCD && $wWakuPM1-2<$SyonitiPM1){##初日土日祝
							$n = $l+$SyonitiPM1;
echo "<br>939行目 l+ SyonitiPM1:".$l."---".$SyonitiPM1;
						}elseif($i == 0 && ceil($wWakuPM1/1.5)>=$SyonitiPM1 && $i === $holidayCD){				##初日祝日
							$n = $l+$SyonitiPM1;
echo "<br>942行目 wWakuPM1+ SyonitiPM1:".$wWakuPM1."---".$SyonitiPM1;
						}elseif($i == 0 && $wWakuPM1-2<$SyonitiPM1){				##初日平日
							$n = $l+$SyonitiPM1;
						}elseif($i === $holidayCD){
							$n = $l+ceil($wWakuPM1/1.5);
echo "<br>948行目　n：".$l."---".$n;
						}elseif($i == 0 && $wWakuPM1-2>=$SyonitiPM1){
							$n = $l+$SyonitiPM1;
						}else{
							$n = $l+$wWakuPM1-1;
echo "<br>951行目 l+ wWakuPM1:".$l."---".$wWakuPM1."調査:".$i."  ".ceil($wWakuPM1/2).">".$SyonitiPM1;
						}
					}

				if(ceil($wWakuPM1/1.5) > $j*$wWakuPM1col+$k+1 && $i==0 && $i==$holidayCD && ($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])){#初日土日祝なら
						$Koteihyou .= $KaiRoom3[$l];
echo "<br>959行目PM1　$l：". $l." :". $KaiRoom3[$l];#2 103
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif(ceil($wWakuPM1/1.5) > $j*$wWakuPM1col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$l])){#初日以外土日祝
						$Koteihyou .= $KaiRoom3[$l];
echo "<br>963行目PM1　$l：". $l." :". $KaiRoom3[$l];#2
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k+2 && $i==0 && $i!==$holidayCD &&  ($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])){#初日平日なら
						$Koteihyou .= $KaiRoom3[$l];
echo "<br>969行目PM1　$l：". $l." :". $KaiRoom3[$l];#2
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$l])){#初日以外平日
						$Koteihyou .= $KaiRoom3[$l];
echo "<br>971行目PM1　$l：". $l." :". $KaiRoom3[$l];#2
						$KoteihyouEX[] = $KaiRoom3[$l];
						$l++;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k){#残り
						$Koteihyou .= "<span style='color:blue;'>空き</span>";
						$KoteihyouEX[] = "空き";
echo "<br>979行目PM1　$l：". $l;#2
					}else{
						$KoteihyouEX[] = "";
					}
					$Koteihyou .="</td>";
				}
				#★３-２PM１枠数を班数で割った数分繰り返す　End

				#★３-３PM２枠数を班数で割った数分繰り返す
				if($wWakuPattern > 2){
echo "<br>993行目PM2　$n：". $n." :". $KaiRoom3[$n];#2
					for($k=0 ; $k < $wWakuPM2col ;$k++){
						$Koteihyou .="<td style='background-color:#E6FFE9'>";
						if(ceil($wWakuPM2/2) > $j*$wWakuPM2col+$k+1 && $i==0 && $i==$holidayCD && $Syoniti>$n && isset($KaiRoom3[$n]) ){#初日土日祝なら
							$Koteihyou .= $KaiRoom3[$n];
echo "<br>994行目PM2　$n：". $n." :". $KaiRoom3[$n];#2
							$KoteihyouEX[] = $KaiRoom3[$n];
							$n++;
						}elseif(ceil($wWakuPM2/2) > $j*$wWakuPM2col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$n])){#初日以外土日祝
							$Koteihyou .= $KaiRoom3[$n];
echo "<br>996行目PM2　$n：". $n." :". $KaiRoom3[$n];#2
							$KoteihyouEX[] = $KaiRoom3[$n];
							$n++;
						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k+2 && $i==0 && $i!==$holidayCD &&  $Syoniti>$n && isset($KaiRoom3[$n])){#初日平日なら
							$Koteihyou .= $KaiRoom3[$n];
							$KoteihyouEX[] = $KaiRoom3[$n];
							$n++;
						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$n])){#初日以外平日
							$Koteihyou .= $KaiRoom3[$n];
echo "<br>1005行目PM2　$n：". $n." :". $KaiRoom3[$n];#2
							$KoteihyouEX[] = $KaiRoom3[$n];
							$n++;
						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k){#残り
							$Koteihyou .= "<span style='color:blue;'>空き</span>";
							$KoteihyouEX[] = "空き";
echo "<br>1011行目PM2　$n：". $n." :". $KaiRoom3[$n];#2
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
	}

	$wKoteihyouEX = SPFWTools::encodePluralValue($KoteihyouEX);

#	print_r($wKoteihyouEX);
*/


/*
###★パターンC 全体つめぎみ　初日無視・日曜・平日PM1つめぎみ
	########################################################
	# 初日・土日祝考慮
	########################################################


	$wWakuSum1 = 0;
	$wWakuSum2 = 0;

	$week = ['日','月','火','水','木','金','土'];

	$date = new DateTime($SenyuStartDate);
	if($wWakuPattern <= 2)
		$wWakuPM2 = 0;

	for($i=0 ; $i< $SenyuDateCnt ; $i++){
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate,$SHUKUJITULIST);
		$YoubiCD =  $date->format('w') ;

		if(count($wHoliday)>0)
			$rst = array_search($SenyuDate,$wHoliday);
		else
			$rst = false;

		if($rst === false){
			if($result!==false || $YoubiCD == 0 || $YoubiCD == 6){#土日、祝の場合
				$Shukujitucolor[$i] = " style='background-color:pink;'";
				if($i==0){
					if($wFirstDateFeature > 0)
						$wWakuSum1 += ceil(($wWakuAM+$wWakuPM1+$wWakuPM2)/1.4)-3;
					else
						$wWakuSum1 += ceil(($wWakuPM1+$wWakuPM2)/1.4)-2;
					
				}else{
					$wWakuSum1 += ceil($wWakuAM/1.4)+ceil($wWakuPM1/1.4)+ceil($wWakuPM2/1.4);
					$wWakuSum2 += ceil($wWakuAM/1.4)+ceil($wWakuPM1/1.4)+ceil($wWakuPM2/1.4);
				}
			}else{
				$Shukujitucolor[$i] = "";
				if($i==0){
					if($wFirstDateFeature > 0){
						if($wWakuPattern <= 2)
							$wWakuSum1 +=$wWakuPM1-2;
						else
							$wWakuSum1 += $wWakuPM1-2+$wWakuPM2-2;
					}else{
						if($wWakuPattern <= 2)
							$wWakuSum1 += $wWakuAM-2+$wWakuPM1-2;
						else
							$wWakuSum1 += $wWakuAM-2+$wWakuPM1-2+$wWakuPM2-2;
					}
				}else{
					if($wWakuPattern <= 2){
						$wWakuSum1 += $wWakuAM-1+$wWakuPM1-1;
						$wWakuSum2 += $wWakuAM-1+$wWakuPM1-1;
					}else{
						$wWakuSum1 += $wWakuAM-1+$wWakuPM1-1+$wWakuPM2-1;
						$wWakuSum2 += $wWakuAM-1+$wWakuPM1-1+$wWakuPM2-1;
					}
				}
			}
		}else{
			${"Kyukobi".$i} = $i;
		}
	if($Kyukobi==""){
		$Kyukobi=-1;
	}
	
	$date->modify('+1 days');
	}

	$Syoniti = count($KaiRoom) - $wWakuSum2 ;
	$Syoniti2 = count($KaiRoom) - $wWakuSum1 ;

	if($Syoniti > $wWakuAM){
		if(array_search($SenyuStartDate,$SHUKUJITULIST)!==false){
			$wWakuMax = ($wWakuAM/1.4)-1;
		}else{
			$wWakuMax = ($wWakuAM/1.4);

		}
	}else{
			$wWakuMax = $Syoniti;
	}

	if($wFirstDateFeature > 0 || $wFirstDateFeature !== "")
		$SyonitiAM = 0;
	else
		$SyonitiAM = ceil(($Syoniti*$wWakuAM)/($wWakuAM+$wWakuPM1+$wWakuPM2));
	if($wWakuPattern > 2){
		$SyonitiPM1 = ceil(($Syoniti*$wWakuPM1)/($wWakuAM+$wWakuPM1+$wWakuPM2));
		$SyonitiPM2 = $Syoniti - ($SyonitiAM + $SyonitiPM1);
	}else{
		$SyonitiPM1 = $Syoniti - $SyonitiAM;
		
	}
	unset($date);

	echo "<br>パターンC最大入力部屋数".$wWakuSum1;
	echo "<br>初日抜き最大入力部屋数".$wWakuSum2;
	echo "<br>初日AM最大入力部屋数".$SyonitiAM;
	echo "<br>初日余り部屋数".$Syoniti1;
	echo "<br>入れれなかった部屋数".$Syoniti2;
	echo "<br>初日部屋数".$Syoniti;
	########################################################
	# 専有部工事の初日考慮
	########################################################
	
	
	
	########################################################
	# 詳細工程表（イメージ）部分
	########################################################
	$wWakuAMcol=ceil(($wWakuAM)/$wHansu);
	
	if($wWakuPattern > 2) {
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
		$wWakuPM2col=ceil(($wWakuPM2)/$wHansu);
	}else{
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
	}
	$KoteihyouC="<table border='1' width='600px' style='text-align:center'><tr><td>日程</td><td>曜日</td><td colspan = ".$wWakuAMcol." style='background-color:#ff7f50;'>AM</td><td colspan = ".$wWakuPM1col." style='background-color:#87cefa'>PM1</td>";
	if($wWakuPattern <= 2){
		$KoteihyouC .="</tr>";
		$wWakuPM2col = 0;
	}else{
		$KoteihyouC .="<td colspan = ".$wWakuPM2col." style='background-color:#99FF66;'>PM2</td></tr>";
	}

	$date = new DateTime($SenyuStartDate);

	$mC = 0 ;
	for($i=0 ; $i< $SenyuDateCnt ; $i++){
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate,$SHUKUJITULIST);
		$YoubiCD =  $date->format('w') ;
		$Youbi = $week[$YoubiCD];
		if($result!==false || $YoubiCD == 0 || $YoubiCD == 6){
			$holidayCD = $i;
		}

		if(${"Kyukobi".$i} === $i){
			$KoteihyouC .="<tr><td rowspan =1".$Shukujitucolor[$i].">".$SenyuDate."</td>";
			$KoteihyouC .="<td rowspan =1".$Shukujitucolor[$i].">".$Youbi."</td>";
			$KoteihyouC .="<td colspan=";
			$KoteihyouC .=$wWakuAMcol+$wWakuPM1col+$wWakuPM2col;
			$KoteihyouC .=" rowspan = 1 style='text-align:center;'>";
			$KoteihyouC .="休工日";
			$KoteihyouC .="</td></tr>";
		}else{
			$KoteihyouC .="<tr><td rowspan =".$wHansu.$Shukujitucolor[$i].">".$SenyuDate."</td>";
			$KoteihyouC .="<td rowspan =".$wHansu.$Shukujitucolor[$i].">".$Youbi."</td>";

			for($j=0 ; $j < $wHansu ;$j++ ){
				if($j!==0)
					$KoteihyouC .="<tr>";
				for($k=0 ; $k < $wWakuAMcol ;$k++ ){
					$KoteihyouC .="<td style='background-color:#ffefd5;'>";

					if($k==0 && $j==0){
						if($i == 0 && $i === $holidayCD && $wWakuAM-2<$SyonitiAM){##初日土日祝
							$l = $mC+ceil($wWakuAM/1.4)-1;
						}elseif($i == 0 && ceil($wWakuAM/1.4)>$SyonitiAM && $i === $holidayCD && $SyonitiAM >= 0){				##初日祝日
							$l = $mC+$SyonitiAM;
						}elseif($i == 0 && $wWakuAM-2<$SyonitiAM){				##初日平日
							$l = $mC+$wWakuAM-2;
						}elseif($i === $holidayCD){
							$l = $mC+ceil($wWakuAM/1.4);
						}elseif($i == 0 && $wWakuAM-2>=$SyonitiAM){
							$l = $mC+$SyonitiAM;
						}else{
							$l = $mC+$wWakuAM-1;
						}
					}


					if( $i==0 && $i==$holidayCD && $mC<$SyonitiAM && ceil($wWakuAM/1.4) > $j*$wWakuAMcol+$k+1 && isset($KaiRoom3[$mC])){#初日土日祝なら
						$KoteihyouC .= $KaiRoom3[$mC];
						$KoteihyouCEX[] = $KaiRoom3[$mC];
						$mC++;
					}elseif(ceil($wWakuAM/1.4) > $j*$wWakuAMcol+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$mC])){#初日以外土日祝
						$KoteihyouC .= $KaiRoom3[$mC];
						$KoteihyouCEX[] = $KaiRoom3[$mC];
						$mC++;
					}elseif( $i==0 && $i!==$holidayCD && $mC<$SyonitiAM && ($wWakuAM-2) > $j*$wWakuAMcol+$k && isset($KaiRoom3[$mC])){#初日平日なら
						$KoteihyouC .= $KaiRoom3[$mC];
						$KoteihyouCEX[] = $KaiRoom3[$mC];
						$mC++;
					}elseif($wWakuAM > $j*$wWakuAMcol+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$mC])){#初日以外平日
						$KoteihyouC .= $KaiRoom3[$mC];
						$KoteihyouCEX[] = $KaiRoom3[$mC];
						$mC++;
					}elseif($wWakuAM > $j*$wWakuAMcol+$k && $wFirstDateFeature > 0 && $i==0){#残り
						$KoteihyouCEX[] = "";
					}elseif($wWakuAM > $j*$wWakuAMcol+$k ){#残り
						$KoteihyouC .= "<span style='color:blue;'>空き</span>";
						$KoteihyouCEX[] = "空き";
					}else{
						$KoteihyouCEX[] = "";
					}
					$KoteihyouC .="</td>";
				}
				for($k=0 ; $k < $wWakuPM1col ;$k++ ){
					$KoteihyouC .="<td style='background-color:#f0f8ff'>";
					if($k==0 && $j==0){
						if($i == 0 && $i !== $holidayCD && $wWakuPM1-2<$SyonitiPM1){##初日土日祝
							$n = $l+$SyonitiPM1;
						}elseif($i == 0 && ceil($wWakuPM1/1.4)>$SyonitiPM1 && $i === $holidayCD){				##初日祝日
							$n = $l+$SyonitiPM1;
						}elseif($i == 0 && $wWakuPM1-2<$SyonitiPM1){				##初日平日
							$n = $l+$SyonitiPM1;
						}elseif($i === $holidayCD){
							$n = $l+ceil($wWakuPM1/1.4);
						}elseif($i == 0 && $wWakuPM1-2>=$SyonitiPM1){
							$n = $l+$SyonitiPM1;
						}else{
							$n = $l+$wWakuPM1-1;
						}
					}

					#初日土日祝なら
					if(ceil($wWakuPM1/1.4) > $j*$wWakuPM1col+$k+1 && $i==0 && $i==$holidayCD && ($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])){#初日土日祝なら
						$KoteihyouC .= $KaiRoom3[$l];
						$KoteihyouCEX[] = $KaiRoom3[$l];
						$l++;
					#初日以外土日祝
					}elseif(ceil($wWakuPM1/1.4) > $j*$wWakuPM1col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$l])){#初日以外土日祝
						$KoteihyouC .= $KaiRoom3[$l];
						$KoteihyouCEX[] = $KaiRoom3[$l];
						$l++;
					#初日平日なら
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k+1 && $i==0 && $i!==$holidayCD &&  ($SyonitiAM+$SyonitiPM1)>$l && isset($KaiRoom3[$l])){#初日平日なら
						$KoteihyouC .= $KaiRoom3[$l];
						$KoteihyouCEX[] = $KaiRoom3[$l];
						$l++;
					#初日以外平日
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$l])){#初日以外平日
						$KoteihyouC .= $KaiRoom3[$l];
						$KoteihyouCEX[] = $KaiRoom3[$l];
						$l++;
					}elseif($wWakuPM1 > $j*$wWakuPM1col+$k){#残り
						$KoteihyouC .= "<span style='color:blue;'>空き</span>";
						$KoteihyouCEX[] = "空き";
					}else{
						$KoteihyouCEX[] = "";
					}
					$KoteihyouC .="</td>";
#echo "<br>1123行目".$l;
				}
				if($wWakuPattern > 2){
					for($k=0 ; $k < $wWakuPM2col ;$k++){
						$KoteihyouC .="<td style='background-color:#E6FFE9'>";
						if(ceil($wWakuPM2/1.4) > $j*$wWakuPM2col+$k+1 && $i==0 && $i==$holidayCD && $Syoniti>$n && isset($KaiRoom3[$n]) ){#初日土日祝なら
							$KoteihyouC .= $KaiRoom3[$n];
							$KoteihyouCEX[] = $KaiRoom3[$n];
							$n++;
						}elseif(ceil($wWakuPM2/1.4) > $j*$wWakuPM2col+$k && $i!==0 && $i==$holidayCD && isset($KaiRoom3[$n])){#初日以外土日祝
							$KoteihyouC .= $KaiRoom3[$n];
							$KoteihyouCEX[] = $KaiRoom3[$n];
							$n++;

#echo "<br>1138行目".$n."-".$KaiRoom3[$n]."--".$wWakuPM2."---".$SenyuDate;

						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k+2 && $i==0 && $i!==$holidayCD &&  $Syoniti>$n && isset($KaiRoom3[$n])){#初日平日なら
							$KoteihyouC .= $KaiRoom3[$n];
							$KoteihyouCEX[] = $KaiRoom3[$n];
							$n++;
						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k+1 && $i!==0 && $i!==$holidayCD && isset($KaiRoom3[$n])){#初日以外平日
							$KoteihyouC .= $KaiRoom3[$n];
							$KoteihyouCEX[] = $KaiRoom3[$n];
							$n++;
						}elseif($wWakuPM2 > $j*$wWakuPM2col+$k){#残り
							$KoteihyouC .= "<span style='color:blue;'>空き</span>";
							$KoteihyouCEX[] = "空き";
						}else{
							$KoteihyouCEX[] = "";
						}
						$KoteihyouC .="</td>";
					}

				}
				if($j!==0)
					$KoteihyouC .="</tr>";
			}
			if($wWakuPattern > 2){
				$mC = $n;
			}else{
				$mC = $l;
			}
		}
		$date->modify('+1 days');
		$KoteihyouC .="</tr>";
	}
	$KoteihyouC .= "</table>";

	if(count($KaiRoom3) > $mC){
		$IfErrorC = TRUE;
	}

	$wKoteihyouCEX = SPFWTools::encodePluralValue($KoteihyouCEX);

	#print_r($wKoteihyouCEX);

###パターンC　End
*/
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
