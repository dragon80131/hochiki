<?php
$isAdminMode = TRUE;
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
include_once _CLS_DIR . "SPUSBuilding.cls";
include_once _CLS_DIR . "SPUSReservationTemp.cls";


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

foreach ($_POST as $key => $value) {
	#echo "<br>key:".$key;
	${"$key"} = SPFWParameter::getValues($key);
}

$wArrangeType = SPFWParameter::getValues('wArrangeType');
$wFloorReserveInfo = SPFWParameter::getValues('FloorReserveInfo');
$wFloorReserveInfo = html_entity_decode($wFloorReserveInfo, ENT_QUOTES, 'UTF-8');

$ColsBlock = SPFWTools::decodePluralValue($wColsBlock);

for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	if ($i != 0)
		$MaxWakuSu .= "-";

	$wWakuAMPM .= $WAKUPATTERN[$wWakuPattern]['AMPM'][$i] . "：" . ${'wWaku' . $WAKUPATTERN[$wWakuPattern]['AMPM'][$i]} . "　";
	$MaxWakuSu .= ${'wWaku' . $WAKUPATTERN[$wWakuPattern]['AMPM'][$i]};
}


if ($wHansu == "") {
	$IfNULLError = TRUE;
	$HansuNotError = "<br>班数を入力してください";
}
if ($wWakuPattern == "") {
	$IfNULLError = TRUE;
	$WakuPatternNotError = "<br>工事枠パターンを入力してください";
} elseif ($wWakuPattern <= 2) {
	if ($wWakuAM == "" || $wWakuPM1 == "") {
		$IfNULLError = TRUE;
		$WakuAMPMNotError = "<br>最大工事枠数を入力してください";
	}
} else {
	if ($wWakuAM == "" || $wWakuPM1 == "" || $wWakuPM2 == "") {
		$IfNULLError = TRUE;
		$WakuAMPMNotError = "<br>最大工事枠数を入力してください";
	}
}
$wKojijun = SPFWParameter::getValues('wKojijun');		# 工事順
$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature'); #初日工事数考慮
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
$wWakuPatternName = $WAKUPATTERN[$wWakuPattern]["Name"];

if($wArrangeType != '1'){
	if ($wKojijun == 1) {
		$KojijunDisp = "下から横へ";
		$KojijunImg = "../images/kojijun1.png";
	} elseif ($wKojijun == 2) {
		$KojijunDisp = "上から横へ(昇順)";
		$KojijunImg = "../images/kojijun2.png";
	} elseif ($wKojijun == 3) {
		$KojijunDisp = "下から縦へ（2列ずつ）";
		$KojijunImg = "../images/kojijun3.png";
	} elseif ($wKojijun == 4) {
		$KojijunDisp = "下から縦へ（3列ずつ）";
		$KojijunImg = "../images/kojijun3.png";
	} elseif ($wKojijun == 5) {
		$KojijunDisp = "上から縦へ（3列ずつ）";
		$KojijunImg = "../images/kojijun4.png";
	} elseif ($wKojijun == 6) {
		$KojijunDisp = "上から縦へ（3列ずつ）";
		$KojijunImg = "../images/kojijun4.png";
	} elseif ($wKojijun == 7) {
		$KojijunDisp = "上から横へ(降順)";
		$KojijunImg = "../images/kojijun2.png";
	} else {
		$IfNULLError = TRUE;
		$KojijunNotError = "<br>工事順を選択してください";
	}

	if ($wFirstDateFeature == 1) {
		$FirstDateFeatureDisp = "初日午前NG";
	} elseif ($wFirstDateFeature == 2) {
		$FirstDateFeatureDisp = "初日15：00以降OK";
	}

	if(!$wKojijun){
		echo "<br><br><br><br><font color='red' >工事順が選択されていません。</font>";
		exit;
	}
}

########################################################
# 設定パラメータ取得取得
########################################################
$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, ""))
	trigger_error("Getting tFileF Failed.", E_USER_ERROR);

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "")) {
		trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
	}
}

// 休工日登録
$wHoliday 				= SPFWParameter::getValues("wHoliday"); // 配列
$Holiday = array();
$wHolidayHTML = '';
// if ($wUseAppOnly != '1') {
	if(is_array($wHoliday) && count($wHoliday) > 0){
		for ($i = 0; $i < count($wHoliday); $i++) {
			if ($wHoliday[$i]) {
				$Holiday[] = $wHoliday[$i];
				$wHolidayHTML .= '<input type="hidden" name="wHoliday[]" value="'.$wHoliday[$i].'">';
			}
		}
	}
	if($editBuildingCD)
		$myBuilding->Holiday1 = SPFWTools::encodePluralValue($Holiday); #パイプつなぎ
	else
		$myBukken->Holiday1 = SPFWTools::encodePluralValue($Holiday); #パイプつなぎ
// }

if($wArrangeType != '1'){
	// 予備日登録
	$wReserveDay 				= SPFWParameter::getValues("wReserveDay"); // 配列
	$ReserveDay = array();
	$wReserveDayHTML = '';
	if(is_array($wReserveDay) && count($wReserveDay) > 0){
		for ($i = 0; $i < count($wReserveDay); $i++) {
			if ($wReserveDay[$i]) {
				$ReserveDay[] = $wReserveDay[$i];
				$wReserveDayHTML .= '<input type="hidden" name="wReserveDay[]" value="'.$wReserveDay[$i].'">';
			}
		}
	}
	if($editBuildingCD)
		$myBuilding->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ
	else
		$myBukken->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ
}


$BukkenName = $myBukken->BukkenName;
$wBuildingName = $myBukken->BuildingName;

// $myKoji = new Koji($myDB);

// if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD, "")) {
// 	$ErrorString = array();
// 	$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
// 	showAdminSorryPage($ErrorString);
// }
// $KyoyoStartDate = $myBukken->KyoyoStartDate;
// $KyoyoEndDate = $myBukken->KyoyoEndDate;
$SenyuStartDateGeneral = $myBukken->SenyuStartDate;
$SenyuEndDateGeneral = $myBukken->SenyuEndDate;
$SenyuStartDate = $myBukken->SenyuStartDate1;
$SenyuEndDate = $myBukken->SenyuEndDate1;
if($editBuildingCD){
	$SenyuStartDate = $myBuilding->SenyuStartDate;
	$SenyuEndDate = $myBuilding->SenyuEndDate;
}
if(!$SenyuStartDate || !$SenyuEndDate){
	$SenyuStartDate = $SenyuStartDateGeneral;
	$SenyuEndDate = $SenyuEndDateGeneral;
}
$SenyuDateCnt = ((strtotime($SenyuEndDate) -  strtotime($SenyuStartDate)) / 86400) + 1; #専有部日数
#$myBukken->MinuteTime = $wMinuteTime;

$arrFloorReserveInfo = [];
$FloorReserveInfo = '';
$wKaidaka = $myBukken->Kaidaka;
if($editBuildingCD){
	$wKaidaka = $myBuilding->Kaidaka;
}
$wKaidaka = intval($wKaidaka);

if($wArrangeType == '1'){
	if($wFloorReserveInfo != ''){
		$arrFloorReserveInfo = json_decode($wFloorReserveInfo, true);
		$FloorReserveInfo = $wFloorReserveInfo;
	}else{
		for($floor=$wKaidaka; $floor>=1; $floor--){
			$arrFloorReserveInfo[$floor]["wFloor"] = SPFWParameter::getValues('wFloor_'.$floor);
			$arrFloorReserveInfo[$floor]["wFloorDay"] = SPFWParameter::getValues('wFloorDay_'.$floor);
			$arrFloorReserveInfo[$floor]["wFloorWaku"] = SPFWParameter::getValues('wFloorWaku_'.$floor);
			$arrFloorReserveInfo[$floor]["wFloorCols"] = SPFWParameter::getValues('wFloorCols_'.$floor);
		}
		$FloorReserveInfo = json_encode($arrFloorReserveInfo);
	}
}
$FloorReserveInfo = htmlspecialchars($FloorReserveInfo, ENT_QUOTES, 'UTF-8');

if($editBuildingCD){
	$wBuildingName = $myBuilding->BuildingName;

	$Holiday1 = $myBuilding->Holiday1;
	$myBuilding->Hansu = $wHansu;
	$myBuilding->WakuPattern = $wWakuPattern;
	$myBuilding->MaxWakuSu = $MaxWakuSu;
	$myBuilding->FrameOverflow = $wFrameOverflow;

	if($wArrangeType == '1'){
		$myBuilding->FloorReserveInfo = $FloorReserveInfo;

	}else{
		$ReserveDay = $myBuilding->ReserveDay;
		$myBuilding->Kojijun = $wKojijun;
		$myBuilding->FirstDateFeature = $wFirstDateFeature;
	}
}else{
	$Holiday1 = $myBukken->Holiday1;
	$myBukken->Hansu = $wHansu;
	$myBukken->WakuPattern = $wWakuPattern;
	$myBukken->MaxWakuSu = $MaxWakuSu;
	$myBukken->FrameOverflow = $wFrameOverflow;
	if($wArrangeType == '1'){
		$myBukken->FloorReserveInfo = $FloorReserveInfo;
	}else{
		$ReserveDay = $myBukken->ReserveDay;
		$myBukken->Kojijun = $wKojijun;
		$myBukken->FirstDateFeature = $wFirstDateFeature;
	}

}
$IfOK = TRUE;

// 棟名称が空の場合、例外処理
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "u.BuildingCD, ";
$sql .= "u.BuildingName ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tBuildingM u ";
$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$editBukkenCD."'";

$myListObject->Condition = $sql;
$myListObject->Order = "u.BuildingCD ASC";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting User List Failed.", E_USER_ERROR);

$BuildingCD = [];
$BuildingName = [];
$BuildingLoop = $myListObject->Rows;

for ($i = 0; $i < $BuildingLoop; $i++) {
	$BuildingCD[$i] = $myListObject->GetValue($i, 0);
	$BuildingName[$i] = $myListObject->GetValue($i, 1);
}
unset($myListObject);

// 棟名称が空の場合、例外処理
function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}
if(!$wBuildingName){
	if($editBuildingCD){
		$wBuildingName = '棟'.numberToCircled(2);
		for ($i = 0; $i < $BuildingLoop; $i++) {
			if($BuildingCD[$i] == $editBuildingCD){
				$wBuildingName = '棟'.numberToCircled($i+2);
			}
		}

	}else{
		if($BuildingLoop > 0){
			$wBuildingName = '棟'.numberToCircled(1);
		}
	}
}
if($BuildingLoop > 0)
	$IfBuildingExist = true;
else
	$IfBuildingExist = false;

unset($myBukken);
unset($myBuilding);

$wHoliday = SPFWTools::decodePluralValue($Holiday1);
sort($wHoliday);

$beforeReserveDay = [];
$afterReserveDay = [];
if($wArrangeType != '1'){
	$wReserveDay = SPFWTools::decodePluralValue($ReserveDay);
	sort($wReserveDay);

	foreach($wReserveDay as $aReserveDay){
		$dateReserveDay = new DateTime($aReserveDay);
		$dateSenyuStartDate = new DateTime($SenyuStartDate);
		$dateSenyuEndDate = new DateTime($SenyuEndDate);

		if($dateReserveDay < $dateSenyuStartDate){
			array_push($beforeReserveDay, $aReserveDay);
		}else if($dateReserveDay > $dateSenyuEndDate){
			array_push($afterReserveDay, $aReserveDay);
		}
	}
}

$myBukkenMatrix = new BukkenMatrix($myDB);

if($editBuildingCD){
	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD . " AND BuildingCD = " .$editBuildingCD, ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);
}else{
	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD . " AND BuildingCD IS NULL", ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);
}



$wKaiRoom = $myBukkenMatrix->KaiRoom;
$KaiRoom = SPFWTools::decodePluralValue($wKaiRoom); #配列
if (is_array($KaiRoom)) {
	$RoomSuu = count($KaiRoom);
}
unset($myBukkenMatrix);
unset($wKaiRoom);

########################################################
# 工事順に部屋を並び替え
########################################################
$y = 0;
for ($x = 0; $x < count($KaiRoom); $x++) {
	#　前はいくつあるか不定　後ろはゼロサブで2桁固定 大きい部屋から格納されている
	$Room[$x] = substr($KaiRoom[$x], -2);
	$KaiRoomLen[$x] = strlen($KaiRoom[$x]);
	#Room[$x]…02,03,12  Kai[$x]…1,2,11 など
	$Kai[$x] = substr($KaiRoom[$x], 0, ($KaiRoomLen[$x] - 2));
	#階ごとの部屋 配列
	$KaiRoom2[$Kai[$x]][] = $KaiRoom[$x];
}

$Floor = 0;

for ($i = 1; $i <= $Kai[0]; $i++) {
	if (is_array($KaiRoom2[$i])) {
		if ($Floor < count($KaiRoom2[$i]))
			$Floor = count($KaiRoom2[$i]);
	}
}

if($wArrangeType != '1'){
	if ($wKojijun == 1) {
		for ($i = 1; $i <= $Kai[0]; $i++) {
			#KaiRoom3…101,102,103,1010
			if (is_array($KaiRoom2[$i])) {
				for ($j = 0; $j < count($KaiRoom2[$i]); $j++) {
					$KaiRoom3[] = $KaiRoom2[$i][$j];
				}
			}
		}
	}

	if ($wKojijun == 2) {
		for ($i = $Kai[0]; $i > 0; $i--) {
			if (is_array($KaiRoom2[$i])) {
				for ($j = 0; $j < count($KaiRoom2[$i]); $j++) {
					$KaiRoom3[] = $KaiRoom2[$i][$j];
				}
			}
		}
	}
	if ($wKojijun == 7) {
		for ($i = $Kai[0]; $i > 0; $i--) {
			if (is_array($KaiRoom2[$i])) {
				for ($j = (count($KaiRoom2[$i]) - 1); $j >= 0; $j--) {
					$KaiRoom3[] = $KaiRoom2[$i][$j];
				}
			}
		}
	}
	// var_dump($KaiRoom3);

	$x = 0;
	if ($wKojijun == 3) { #下から縦へ(2列ずつ）
		for ($i = 1; $i <= $Kai[0]; $i++) {
			$CntKaiRoom2 = is_countable($KaiRoom2[$i]) ? count($KaiRoom2[$i]) : 0;
			$y += $CntKaiRoom2;
			for ($j = 0; $j < $CntKaiRoom2; $j++) {
				$room = substr($KaiRoom2[$i][$j], -2);
				$KaiRoom3_kari[$i][$room] = $KaiRoom2[$i][$j];
			}
		}
		#	print_r($KaiRoom3_kari);
		#	echo "<br>";
		for ($j = 0; $j < ($y / 2); $j++) {
			for ($i = 1; $i <= $Kai[0]; $i++) {
				#				echo $x;
				for ($k = $x; $k < $x + 2; $k++) {
					#					echo sprintf('%02d', $k);
					if ($KaiRoom3_kari[$i][sprintf('%02d', ($k + 1))]) {
						$KaiRoom3[] = $KaiRoom3_kari[$i][sprintf('%02d', ($k + 1))];
					}
				}
			}
			$x = $k;
		}
	}


	if ($wKojijun == 4) { #下から縦へ(3列ずつ）
		for ($i = 1; $i <= $Kai[0]; $i++) {
			$CntKaiRoom2 = is_countable($KaiRoom2[$i]) ? count($KaiRoom2[$i]) : 0;
			$y += $CntKaiRoom2;
			for ($j = 0; $j < $CntKaiRoom2; $j++) {
				$room = substr($KaiRoom2[$i][$j], -2);
				$KaiRoom3_kari[$i][$room] = $KaiRoom2[$i][$j];
			}
		}
		for ($j = 0; $j < ($y / 3); $j++) {
			for ($i = 1; $i <= $Kai[0]; $i++) {
				for ($k = $x; $k < $x + 3; $k++) {
					if ($KaiRoom3_kari[$i][sprintf('%02d', ($k + 1))]) {
						$KaiRoom3[] = $KaiRoom3_kari[$i][sprintf('%02d', ($k + 1))];
					}
				}
			}
			$x = $k;
		}
	}

	if ($wKojijun == 5) {
		for ($i = $Kai[0]; $i > 0; $i--) {
			$CntKaiRoom2 = is_countable($KaiRoom2[$i]) ? count($KaiRoom2[$i]) : 0;
			$y += $CntKaiRoom2;
			for ($j = 0; $j < $CntKaiRoom2; $j++) {
				$room = substr($KaiRoom2[$i][$j], -2);
				$KaiRoom3_kari[$i][$room] = $KaiRoom2[$i][$j];
			}
		}
		#	print_r($KaiRoom3_kari);
		#	echo "<br>";
		for ($j = 0; $j < ($y / 2); $j++) {
			for ($i = $Kai[0]; $i > 0; $i--) {
				#				echo $x;
				for ($k = $x; $k < $x + 2; $k++) {
					#					echo sprintf('%02d', $k);
					if ($KaiRoom3_kari[$i][sprintf('%02d', ($k + 1))]) {
						$KaiRoom3[] = $KaiRoom3_kari[$i][sprintf('%02d', ($k + 1))];
					}
				}
			}
			$x = $k;
		}
	}

	if ($wKojijun == 6) {
		for ($i = $Kai[0]; $i > 0; $i--) {
			$CntKaiRoom2 = is_countable($KaiRoom2[$i]) ? count($KaiRoom2[$i]) : 0;
			$y += $CntKaiRoom2;

			for ($j = 0; $j < $CntKaiRoom2; $j++) {
				$room = substr($KaiRoom2[$i][$j], -2);
				$KaiRoom3_kari[$i][$room] = $KaiRoom2[$i][$j];
			}
		}
		#	print_r($KaiRoom3_kari);
		#	echo "<br>";
		for ($j = 0; $j < ($y / 3); $j++) {
			for ($i = $Kai[0]; $i > 0; $i--) {
				#				echo $x;
				for ($k = $x; $k < $x + 3; $k++) {
					#					echo sprintf('%02d', $k);
					if ($KaiRoom3_kari[$i][sprintf('%02d', ($k + 1))]) {
						$KaiRoom3[] = $KaiRoom3_kari[$i][sprintf('%02d', ($k + 1))];
					}
				}
			}
			$x = $k;
		}
	}
}

$wFrameOverflow = intval($wFrameOverflow);
// if($wArrangeType == '1'){
// 	$wFrameOverflow = 0;
// }

$wHansu = intval($wHansu);
$rowCountforDay = $wHansu;

for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
	$tempRowCountforDay = ceil((${'wWaku' . $WakuName} + $wFrameOverflow * $wHansu ) / 5);
	if($tempRowCountforDay > $rowCountforDay)
		$rowCountforDay = $tempRowCountforDay;

	${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $wHansu) + $wFrameOverflow;
	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};

	${'wWaku' . $WakuName . 'col'} = ceil(${'wWaku' . $WakuName} / $wHansu) + $wFrameOverflow;
	if($WakuName == 'PM')$wWakuPM1col = ${'wWaku' . $WakuName . 'Col'} ;
}

$rowCountforDay = ceil($rowCountforDay / $wHansu) * $wHansu;

for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];

	$tempRowCountforDay = ceil((${'wWaku' . $WakuName} + $wFrameOverflow * $wHansu) / 5);
	if($tempRowCountforDay > $wHansu){
		${'wWaku' . $WakuName . 'Col'} = 5;
	}else{
		${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $rowCountforDay) + $wFrameOverflow;
	}

	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $rowCountforDay;
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};

	${'wWaku' . $WakuName . 'col'} = ceil(${'wWaku' . $WakuName} / $rowCountforDay) + $wFrameOverflow;
	if($WakuName == 'PM')$wWakuPM1col = ${'wWaku' . $WakuName . 'Col'} ;
}


########################################################
# 初日・土日祝考慮
########################################################

$wWakuSum1 = 0;
$wWakuSum2 = 0;

$week = ['日', '月', '火', '水', '木', '金', '土'];

if($wArrangeType == '1'){
	$KaiRoom4 = [];
	for($floor=$wKaidaka; $floor>=1; $floor--){
		if(isset($arrFloorReserveInfo[$floor]["wFloorDay"]) && $arrFloorReserveInfo[$floor]["wFloorDay"] != '' && isset($arrFloorReserveInfo[$floor]["wFloorWaku"]) && $arrFloorReserveInfo[$floor]["wFloorWaku"] != ''){
			if(!isset($KaiRoom4[$arrFloorReserveInfo[$floor]["wFloorDay"]]))
				$KaiRoom4[$arrFloorReserveInfo[$floor]["wFloorDay"]] = [];
			if(!isset($KaiRoom4[$arrFloorReserveInfo[$floor]["wFloorDay"]][$arrFloorReserveInfo[$floor]["wFloorWaku"]]))
				$KaiRoom4[$arrFloorReserveInfo[$floor]["wFloorDay"]][$arrFloorReserveInfo[$floor]["wFloorWaku"]] = [];

			if(isset($KaiRoom2[$arrFloorReserveInfo[$floor]["wFloor"]]) && is_array($KaiRoom2[$arrFloorReserveInfo[$floor]["wFloor"]]))
				$KaiRoom4[$arrFloorReserveInfo[$floor]["wFloorDay"]][$arrFloorReserveInfo[$floor]["wFloorWaku"]] = array_merge($KaiRoom4[$arrFloorReserveInfo[$floor]["wFloorDay"]][$arrFloorReserveInfo[$floor]["wFloorWaku"]], $KaiRoom2[$arrFloorReserveInfo[$floor]["wFloor"]]);

		}

	}

	$date = new DateTime($SenyuStartDate);
	$holiday = array();
	for ($i = 0; $i < $SenyuDateCnt; $i++) {
		$Syoniti[$i] = false;
		$KojiHoliday[$i] = false;
		$holiday[$i] = false;
		
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate, $SHUKUJITULIST);
		$YoubiCD =  $date->format('w');
		if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
			$holiday[$i] = true;
		}
		if (count($wHoliday) > 0) { //休工日
			$KojiHoliday[$i] = (array_search($SenyuDate, $wHoliday) === false) ? false : true;
		}

		for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
			//空きを考慮した枠数を取得
			${'Waku' . $WakuName . 'Su'} = ${'wWaku' . $WakuName};
			$ban_rooms = 1;
			$Overflows = 0;
			$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
			$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
			$passed_rooms = 0;
			$x = 0;
			for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {
				if (!$KojiHoliday[$i]) { //休工日以外
					if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
						if($Overflows < $wFrameOverflow){
							${'Waku' . $WakuName . 'Room'}[] = "枠越";
							$Overflows ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = "";
						}
					} elseif (${'Waku' . $WakuName . 'Su'} > $passed_rooms && isset($KaiRoom4[$SenyuDate][$WakuName][$x])) { //空きを考慮した枠数分　部屋を入れる
						${'Waku' . $WakuName . 'Room'}[] = $KaiRoom4[$SenyuDate][$WakuName][$x];
						$x++;
						$passed_rooms ++;
					} elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
						${'Waku' . $WakuName . 'Room'}[] = "空き";
						$passed_rooms ++;
					} else {
						if($Overflows < $wFrameOverflow){
							${'Waku' . $WakuName . 'Room'}[] = "枠越";
							$Overflows ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = "";
						}
					}
					$ban_rooms ++;
					if($ban_rooms > $limit_ban){
						$ban_rooms = 1;
						$Overflows = 0;
					}
				}
			}
		}
		$date->modify('+1 days');
	}	

}else{
	$x = 0;
	// 予備日
	foreach($beforeReserveDay as $key => $aReserveDay){
		$aSyoniti = false;
		$beforeKojiHoliday[$key] = false;
		$beforeHoliday[$key] = false;

		$date = new DateTime($aReserveDay);

		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate, $SHUKUJITULIST);
		$YoubiCD =  $date->format('w');
		if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
			$beforeHoliday[$key] = true;
		}
		if (count($wHoliday) > 0) { //休工日
			$beforeKojiHoliday[$key] = (array_search($SenyuDate, $wHoliday) === false) ? false : true;
		}
		for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
			//空きを考慮した枠数を取得
			${'Waku' . $WakuName . 'Su'} = getWakuRoomSu($WakuName, $aSyoniti, $$beforeHoliday[$key], ${'wWaku' . $WakuName}, $wFirstDateFeature);
			//初日考慮
			$SyonitiKouryo = false;
			if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $aSyoniti) {
				$SyonitiKouryo = true;
			}else if($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $aSyoniti){
				$SyonitiKouryo = true;
			}
			$ban_rooms = 1;
			$Overflows = 0;
			$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
			$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
			$passed_rooms = 0;
			for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {
				if (!$beforeKojiHoliday[$key]) { //休工日以外
					if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
						${'Waku' . $WakuName . 'Room'}[] = "";
					} else if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
						if($Overflows < $wFrameOverflow){
							${'Waku' . $WakuName . 'Room'}[] = "枠越";
							$Overflows ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = "";
						}
					// } elseif (${'Waku' . $WakuName . 'Su'} > $passed_rooms && isset($KaiRoom3[$x])) { //空きを考慮した枠数分　部屋を入れる
					// 	${'Waku' . $WakuName . 'Room'}[] = $KaiRoom3[$x];
					// 	$x++;
					// 	$passed_rooms ++;
					} elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
						${'Waku' . $WakuName . 'Room'}[] = "空き";
						$passed_rooms ++;
					} else {
						if($Overflows < $wFrameOverflow){
							${'Waku' . $WakuName . 'Room'}[] = "枠越";
							$Overflows ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = "";
						}
					}
					$ban_rooms ++;
					if($ban_rooms > $limit_ban){
						$ban_rooms = 1;
						$Overflows = 0;
					}
				}
			}
		}
	}
	$date = new DateTime($SenyuStartDate);
	$holiday = array();
	for ($i = 0; $i < $SenyuDateCnt; $i++) {
		$Syoniti[$i] = false;
		$KojiHoliday[$i] = false;
		$holiday[$i] = false;


		if ($i == 0) {
			$Syoniti[$i] = true;
		}
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate, $SHUKUJITULIST);
		$YoubiCD =  $date->format('w');
		if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
			$holiday[$i] = true;
		}
		if (count($wHoliday) > 0) { //休工日
			$KojiHoliday[$i] = (array_search($SenyuDate, $wHoliday) === false) ? false : true;
		}
		for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
			//空きを考慮した枠数を取得
			${'Waku' . $WakuName . 'Su'} = getWakuRoomSu($WakuName, $Syoniti[$i], $holiday[$i], ${'wWaku' . $WakuName}, $wFirstDateFeature);
			//初日考慮
			$SyonitiKouryo = false;
			if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $Syoniti[$i]) {
				$SyonitiKouryo = true;
			}else if($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $Syoniti[$i]){
				$SyonitiKouryo = true;
			}
			$ban_rooms = 1;
			$Overflows = 0;
			$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
			$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
			$passed_rooms = 0;
			for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {
				if (!$KojiHoliday[$i]) { //休工日以外
					if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
						${'Waku' . $WakuName . 'Room'}[] = "";
					} else if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
						if($Overflows < $wFrameOverflow){
							${'Waku' . $WakuName . 'Room'}[] = "枠越";
							$Overflows ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = "";
						}
					} elseif (${'Waku' . $WakuName . 'Su'} > $passed_rooms && isset($KaiRoom3[$x])) { //空きを考慮した枠数分　部屋を入れる
						${'Waku' . $WakuName . 'Room'}[] = $KaiRoom3[$x];
						$x++;
						$passed_rooms ++;
					} elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
						${'Waku' . $WakuName . 'Room'}[] = "空き";
						$passed_rooms ++;
					} else {
						if($Overflows < $wFrameOverflow){
							${'Waku' . $WakuName . 'Room'}[] = "枠越";
							$Overflows ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = "";
						}
					}
					$ban_rooms ++;
					if($ban_rooms > $limit_ban){
						$ban_rooms = 1;
						$Overflows = 0;
					}
				}
			}
		}
		$date->modify('+1 days');
	}
	// 予備日
	foreach($afterReserveDay as $key => $aReserveDay){
		$aSyoniti = false;
		$afterKojiHoliday[$key] = false;
		$afterHoliday[$key] = false;

		$date = new DateTime($aReserveDay);

		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate, $SHUKUJITULIST);
		$YoubiCD =  $date->format('w');
		if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
			$afterHoliday[$key] = true;
		}
		if (count($wHoliday) > 0) { //休工日
			$afterKojiHoliday[$key] = (array_search($SenyuDate, $wHoliday) === false) ? false : true;
		}
		for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
			//空きを考慮した枠数を取得
			${'Waku' . $WakuName . 'Su'} = getWakuRoomSu($WakuName, $aSyoniti, $$afterHoliday[$key], ${'wWaku' . $WakuName}, $wFirstDateFeature);
			//初日考慮
			$SyonitiKouryo = false;
			if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $aSyoniti) {
				$SyonitiKouryo = true;
			}else if($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $aSyoniti){
				$SyonitiKouryo = true;
			}
			$ban_rooms = 1;
			$Overflows = 0;
			$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
			$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
			$passed_rooms = 0;
			for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {
				if (!$afterKojiHoliday[$key]) { //休工日以外
					if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
						${'Waku' . $WakuName . 'Room'}[] = "";
					} else if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
						if($Overflows < $wFrameOverflow){
							${'Waku' . $WakuName . 'Room'}[] = "枠越";
							$Overflows ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = "";
						}
					// } elseif (${'Waku' . $WakuName . 'Su'} > $passed_rooms && isset($KaiRoom3[$x])) { //空きを考慮した枠数分　部屋を入れる
					// 	${'Waku' . $WakuName . 'Room'}[] = $KaiRoom3[$x];
					// 	$x++;
					// 	$passed_rooms ++;
					} elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
						${'Waku' . $WakuName . 'Room'}[] = "空き";
						$passed_rooms ++;
					} else {
						if($Overflows < $wFrameOverflow){
							${'Waku' . $WakuName . 'Room'}[] = "枠越";
							$Overflows ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = "";
						}
					}
					$ban_rooms ++;
					if($ban_rooms > $limit_ban){
						$ban_rooms = 1;
						$Overflows = 0;
					}
				}
			}
		}
	}

	//組み込んだ部屋の数
	$RoomCnt = $x;

	if (count($KaiRoom3) > $RoomCnt) {
		$IfError = TRUE;
		$SakuseiDisabled = 'disabled';
	}
}

#★1 End
$wShukujitucolor = SPFWTools::encodePluralValue($Shukujitucolor);
$wKyukobi = SPFWTools::encodePluralValue($wKyukobi);


########################################################
# 詳細工程表（イメージ）部分
########################################################

$Koteihyou = "<table border='1' width='600px' cellspacing='5' style='text-align:center' cellpadding='7' class='ex_table no-action'>";
$Koteihyou .= "<tr><td class='ex_table2' width='130px'>日程</td>";
$Koteihyou .= "<td class='ex_table2' width='60px'>曜日</td>";
$Koteihyou .= "<td class='ex_table2' width='50px'>班</td>";
for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];

	$disWakuName = $WakuName;
	$WakuPatternNames = $WAKUPATTERN[$wWakuPattern]['Name'];
	if (preg_match('/\((.*?)\)/', $WakuPatternNames, $matches)) {
		$WakuPatternNamesStr = $matches[1];
		$WakuPatternNamesArr = explode(",", $WakuPatternNamesStr);
		if(isset($WakuPatternNamesArr[$i]) && $WakuPatternNamesArr[$i] != '')
			$disWakuName = trim($WakuPatternNamesArr[$i]);
	}


	$Koteihyou .= "<td colspan = " . ${'wWaku' . $WakuName . 'Col'};
	if ($i % 2 == 0) {
		$Koteihyou .= " style='background-color:#add8e6;' ";
	} else {
		$Koteihyou .= " style='background-color:#e0ffff;' ";
	}
	$Koteihyou .= "class='ex_table2'>" . $disWakuName."</td>";

	${$WakuName . "index"} = 0;
	// echo "<pre>";
	// var_dump(${'Waku' . $WakuName . 'Room'});
	// echo "</pre>";

	${"wWaku".$WakuName."col"} = ${'wWaku' . $WakuName . 'Col'} ;#大文字、小文字がちがう！
	#echo "<br> ".__LINE__." Hensu :".${"wWaku".$WakuName."col"};
	

}
$Koteihyou .= "</tr>";
$holiday = array();

foreach($beforeReserveDay as $key => $aReserveDay){
	$date = new DateTime($aReserveDay);
	$SenyuDate = $date->format('Y-m-d');
	$week_str = $week[$date->format('w')];
	if($week_str == '土')
		$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
	else if($week_str == '日')
		$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

	if ($beforeKojiHoliday[$key]) { #★１休工日なら
		if ($beforeHoliday[$key]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trreserveday'><td class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td  class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td  class='ex_table2'></td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trreserveday'><td  class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td class='ex_table2'></td>";
		}
		$Koteihyou .= "<td colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($beforeHoliday[$key]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trreserveday'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trreserveday'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		}
		$banNo = 1;
		$curHansu = $banNo;
		$banRowspan = floor($rowCountforDay / $wHansu);
		for ($j = 0; $j < $rowCountforDay; $j++) {
			if ($j != 0)
				$Koteihyou .= "<tr class='trreserveday'>";

			if($j % $banRowspan == 0){
				if ($beforeHoliday[$key]) { //土日祝なら
					$Koteihyou .= "<td rowspan=" . $banRowspan . " class='ex_table2'>" . $banNo . "</td>";
				} else { //平日なら
					$Koteihyou .= "<td rowspan=" . $banRowspan . " class='ex_table2'>" . $banNo . "</td>";
				}
				$curHansu = $banNo;
				$banNo ++;
			}


			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					if(!${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]){
						$Koteihyou .= "<td class='link_cell' style='background-color:#d3d3d3;'>";
					}else{
						if ($k % 2 == 0) {
							$Koteihyou .= "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou .= "<td class='link_cell' style='background-color:#ffffcf;'>";
						}
					}
					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "空き" || ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "枠越") {
						$Koteihyou .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					} else {
						$Koteihyou .= '<font style="font-size:20px"> <b>' . ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] . '</b></font>';
					}
					$KoteihyouEX[] = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					$HansuEX[] = $curHansu;

					// echo "<br>" . $WakuName . ":" . ${$WakuName . "index"};
					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;
				}
			}
			$Koteihyou .= "</tr>";
		}
	}	

}


$date = new DateTime($SenyuStartDate);

for ($i = 0; $i < $SenyuDateCnt; $i++) {
	$SenyuDate = $date->format('Y-m-d');
	$week_str = $week[$date->format('w')];
	if($week_str == '土')
		$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
	else if($week_str == '日')
		$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

	if ($KojiHoliday[$i]) { #★１休工日なら
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trholiday'><td class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td  class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
			$Koteihyou .= "<td  class='ex_table2' style='background-color:pink;'></td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trholiday'><td  class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td class='ex_table2'></td>";
		}
		$Koteihyou .= "<td colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop'><td rowspan=" . $rowCountforDay . " class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		}
		$banNo = 1;
		$curHansu = $banNo;
		$banRowspan = floor($rowCountforDay / $wHansu);
		for ($j = 0; $j < $rowCountforDay; $j++) {
			if ($j != 0)
				$Koteihyou .= "<tr>";

			if($j % $banRowspan == 0){
				if ($holiday[$i]) { //土日祝なら
					$Koteihyou .= "<td rowspan=" . $banRowspan . " class='ex_table2' style='background-color:pink;'>" . $banNo . "</td>";
				} else { //平日なら
					$Koteihyou .= "<td rowspan=" . $banRowspan . " class='ex_table2'>" . $banNo . "</td>";
				}
				$curHansu = $banNo;
				$banNo ++;
			}


			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					if(!${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]){
						$Koteihyou .= "<td class='link_cell' style='background-color:#d3d3d3;'>";
					}else{
						if ($k % 2 == 0) {
							$Koteihyou .= "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou .= "<td class='link_cell' style='background-color:#ffffcf;'>";
						}
					}

					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "空き" || ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "枠越") {
						$Koteihyou .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					} else {
						$Koteihyou .= '<font style="font-size:20px"> <b>' . ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] . '</b></font>';
					}
					$KoteihyouEX[] = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					$HansuEX[] = $curHansu;

					// echo "<br>" . $WakuName . ":" . ${$WakuName . "index"};
					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;
				}
			}
			$Koteihyou .= "</tr>";
		}
	}

	$date->modify('+1 days');
}

foreach($afterReserveDay as $key => $aReserveDay){
	$date = new DateTime($aReserveDay);
	$SenyuDate = $date->format('Y-m-d');
	$week_str = $week[$date->format('w')];
	if($week_str == '土')
		$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
	else if($week_str == '日')
		$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

	if ($afterKojiHoliday[$key]) { #★１休工日なら
		if ($afterHoliday[$key]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trreserveday'><td class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td  class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td  class='ex_table2'></td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trreserveday'><td  class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td class='ex_table2'></td>";
		}
		$Koteihyou .= "<td colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($afterHoliday[$key]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trreserveday'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trreserveday'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		}
		$banNo = 1;
		$curHansu = $banNo;
		$banRowspan = floor($rowCountforDay / $wHansu);
		for ($j = 0; $j < $rowCountforDay; $j++) {
			if ($j != 0)
				$Koteihyou .= "<tr class='trreserveday'>";

			if($j % $banRowspan == 0){
				if ($afterHoliday[$key]) { //土日祝なら
					$Koteihyou .= "<td rowspan=" . $banRowspan . " class='ex_table2'>" . $banNo . "</td>";
				} else { //平日なら
					$Koteihyou .= "<td rowspan=" . $banRowspan . " class='ex_table2'>" . $banNo . "</td>";
				}
				$curHansu = $banNo;
				$banNo ++;
			}


			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					if(!${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]){
						$Koteihyou .= "<td class='link_cell' style='background-color:#d3d3d3;'>";
					}else{
						if ($k % 2 == 0) {
							$Koteihyou .= "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou .= "<td class='link_cell' style='background-color:#ffffcf;'>";
						}
					}

					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "空き" || ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "枠越") {
						$Koteihyou .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					} else {
						$Koteihyou .= '<font style="font-size:20px"> <b>' . ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] . '</b></font>';
					}
					$KoteihyouEX[] = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					$HansuEX[] = $curHansu;

					// echo "<br>" . $WakuName . ":" . ${$WakuName . "index"};
					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;
				}
			}
			$Koteihyou .= "</tr>";
		}
	}	

}

$Koteihyou .= "</table>";
//$wKoteihyouEX = SPFWTools::encodePluralValue($KoteihyouEX);
$wKoteihyouEXHTML = "";
$i = 0;
foreach (array_chunk($KoteihyouEX, 300) as $KoteihyouEXChunk) {
	$wKoteihyouEXHTML .= '<input type="hidden" name="wKoteihyouEX_'.$i.'" value="' . SPFWTools::encodePluralValue($KoteihyouEXChunk) . '">';
	$i++;
}
$wKoteihyouEXHTML .= '<input type="hidden" name="wKoteihyouEX_count" value="' . $i . '">';

$wKaiRoom3 = SPFWTools::encodePluralValue($KaiRoom3);
//$wHansuEX = SPFWTools::encodePluralValue($HansuEX);
$wHansuEXHTML = "";
$i = 0;
foreach (array_chunk($HansuEX, 300) as $HansuEXChunk) {
	$wHansuEXHTML .= '<input type="hidden" name="wHansuEX_'.$i.'" value="' . SPFWTools::encodePluralValue($HansuEXChunk) . '">';
	$i++;
}
$wHansuEXHTML .= '<input type="hidden" name="wHansuEX_count" value="' . $i . '">';

$wFrameOverflow = SPFWParameter::getValues('wFrameOverflow');

//最大工事枠数から空きの数を引いた枠数を取得
function getWakuRoomSu($WakuName, $Syoniti, $holiday, $MaxWakuSu, $FirstDateFeature)
{
	// 第一引数：$WakuName string
	//  AM・PMなど

	// 第二引数： $Syoniti boolean
	//  初日かどうか

	// 第三引数：$holiday boolean
	//  土日祝かどうか

	// 第四引数：$MaxWakuSu int
	//  枠ごとの最大工事枠数

	// 第五引数：$FirstDateFeature int
	//  初日考慮
	//  1：午前中NG
	//  2：15時までNG


	//最大工事枠数から空きの数を引く計算をする
	if ($Syoniti == true && $holiday) { //初日・土日祝　(最大工事枠数/2)-1

		$WakuRoomSu = ceil($MaxWakuSu / 2) - 1;

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE)) { //初日15：00以降OK
			$WakuRoomSu = 0;
		}
	} elseif ($Syoniti == true) { //初日・平日　最大工事枠数-2

		$WakuRoomSu = $MaxWakuSu - 2;

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE)) {
			$WakuRoomSu = 0;
		}
	} elseif ($holiday) { //土日祝 最大工事枠数/2
		$WakuRoomSu = ceil($MaxWakuSu / 2);
	} else { //平日 最大工事枠数-1
		$WakuRoomSu = $MaxWakuSu - 1;
	}
	if ($WakuRoomSu < 0) {
		$WakuRoomSu = 0;
	}
	return $WakuRoomSu;
}

$myListObject = new SPFWListObject($myDB);
$myListObject->SelectSQL = 'SELECT ID';
$sql  = " FROM tReservationF WHERE MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD;
if($editBuildingCD){
	$sql .= " AND BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND BuildingCD IS NULL ";
}
$myListObject->Condition = $sql;
$myListObject->Order = "ID";
$myListObject->Limit = "allpage";
if (!($myListObject->GetList(1)))
	trigger_error("Getting Reservation List Failed.", E_USER_ERROR);
$ReservationCount = $myListObject->Rows;

// 一時保存されたデータがあるか確認します。
$myReservationTemp = new ReservationTemp($myDB);
if($editBuildingCD){
	if (!$myReservationTemp->executeSelect("  BukkenCD = '".$editBukkenCD."' AND BuildingCD = '".$editBuildingCD."'", "")) 
		trigger_error("Getting Temp Reservation Failed.", E_USER_ERROR);
}else{
	if (!$myReservationTemp->executeSelect("  BukkenCD = '".$editBukkenCD."' AND BuildingCD IS NULL", "")) 
		trigger_error("Getting Temp Reservation Failed.", E_USER_ERROR);
}
$IfExistTempReservation = false;
if($myReservationTemp->RecCnt > 0)
	$IfExistTempReservation = true;

########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "s_make_kotei_confirm2.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);