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
include_once _CLS_DIR . "SPUSUser.cls";

include_once _CLS_DIR . "SPUSBukken.cls";
// include_once _CLS_DIR . "SPUSSiten.cls";

// include_once _CLS_DIR . "SPUSIraiRenkei.cls";
// include_once _CLS_DIR . "SPUSIraiFile.cls";
include_once _CLS_DIR . "SPUSBukkenMatrix.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPUSKojiDate.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";
include_once dirname(__DIR__) . "/include/building_period_helpers.php";

// データベースコネクト

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 認証動作
########################################################
$rkey = SPFWParameter::getValues('rkey');
$wKosu = SPFWParameter::getValues("wKosu");

$myUser = new User($myDB);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1) {
	$URL = _MAIN_URL . 'login_form.php';
	header('Location: ' . $URL);
	exit;
}

$wUserCD = $myUser->UserCD;
$MyShozokuCD = $myUser->Extra1;	#所属支店CD
$MyZokusei = $myUser->Extra3;		#管理ユーザ２一般ユーザ１
if ($MyZokusei == 2) $IfNespe = TRUE; #予定案内表示
$MyEigyoshoCD = $myUser->Extra4;	#営業所CD nespeユーザはNULLになってる
$MyGyosyaCD	= $myUser->Extra5; #会社CD
$LoginUserID = $myUser->ID;

########################################################
# BukkenMatrix　部屋構成 登録
########################################################

$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
$work = SPFWParameter::getValues('work');

if ($work == 1) {

	$KojiJun = SPFWParameter::getValues('KojiJun');
	$KaiRoom = SPFWParameter::getValues('KaiRoom'); #配列

	$myBukkenMatrix = new BukkenMatrix($myDB);

	if($editBuildingCD){
		if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = " . $editBuildingCD . " AND MukouFlg = FALSE", "")) {
			$ErrorString = array();
			$ErrorString[] = "tBukkenM情報の抽出に失敗しました。";
			showSorryPage(_ILLEGAL_ACCESS2);
		}
	}else{
		if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD IS NULL AND MukouFlg = FALSE", "")) {
			$ErrorString = array();
			$ErrorString[] = "tBukkenM情報の抽出に失敗しました。";
			showSorryPage(_ILLEGAL_ACCESS2);
		}
	}

	if ($myBukkenMatrix->RecCnt == 0) { #新規
		$myBukkenMatrix->BukkenMatrixCD = -1;
	}

	$myBukkenMatrix->BukkenCD = $editBukkenCD;
	if($editBuildingCD){
		$myBukkenMatrix->BuildingCD = $editBuildingCD;
	}
	$myBukkenMatrix->KaiRoom = SPFWTools::encodePluralValue($KaiRoom);

	if (!$myBukkenMatrix->executeUpdate()) {
		$ErrorString = array();
		$ErrorString[] = "依頼連携情報の更新に失敗しました。";
		showAdminSorryPage($ErrorString);
	} else {
		$IfOK = TRUE;
	}
}

#########################################################
# 削除ボタン押した時
#########################################################
if ($work == 2) {
	if ($editBukkenCD) {
		$db_link = mysqli_connect(_HOST_NAME, _USER_NAME, _PASSWD, _MAIN_DB);
		if($editBuildingCD){
			$sql = " delete FROM `tReservationF` WHERE BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '".$editBuildingCD."'";
		}else{
			$sql = " delete FROM `tReservationF` WHERE BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL";
		}
		$result = mysqli_query($db_link, $sql);
		mysqli_close($db_link);
	}
}

########################################################
# 物件情報抽出
########################################################

if(!$editBukkenCD){
	header("Location: ../s_search.php?rKey=" . $rKey);
	exit;
}
if ($editBukkenCD > 0) { #物件情報の修正の場合

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")) {
		trigger_error("Getting myBukken Failed.", E_USER_ERROR);
	}

	$myBuilding = new Building($myDB);
	if($editBuildingCD){
		if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "")) {
			trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
		}
	}

	// 戸数を変更する
	if($wKosu){
		if($editBuildingCD){
			$myBuilding->Kosu = mb_convert_kana($wKosu, "n");
			if (!$myBuilding->executeUpdate()) {
				trigger_error("executeUpdate(myBuilding) Failed.", E_USER_ERROR);
			}
		}else{
			$myBukken->Kosu = mb_convert_kana($wKosu, "n");
			if (!$myBukken->executeUpdate()) {
				trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
			}
		}
	}

	$wBukkenCD = $myBukken->BukkenCD;
	$wBukkenName = $myBukken->BukkenName;
	$wTantoCD = $myBukken->TantoCD;
	$wShozokuCD = $myBukken->ShozokuCD;
	$wTosu = $myBukken->Tosu;
	$wKosu = $myBukken->Kosu;
	$wKaidaka = $myBukken->Kaidaka;
	$BukkenGyosyaCD = $myBukken->GyosyaCD;
	$wBuildingName = $myBukken->BuildingName;

	if($editBuildingCD){
		$wKosu = $myBuilding->Kosu;
		$wKaidaka = $myBuilding->Kaidaka;
	}

	function numberToCircled($number) {
		$map = [
			1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
			6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
			11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
			16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
		];

		return $map[$number] ?? $number;
	}	
	// 棟一覧
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
	$naviClass = [];
	$mainNaviClass = 'active';
	$BuildingLoop = $myListObject->Rows;

	for ($i = 0; $i < $BuildingLoop; $i++) {
		$BuildingCD[$i] = $myListObject->GetValue($i, 0);
		$BuildingName[$i] = $myListObject->GetValue($i, 1);
		if(!$BuildingName[$i])
			$BuildingName[$i] = '棟'.numberToCircled($i+2);

		if($BuildingCD[$i] == $editBuildingCD){
			$naviClass[$i] = 'active';
			$mainNaviClass = '';
		}
		else{
			$naviClass[$i] = '';
		}

	}
	unset($myListObject);

	if(!$wBuildingName){
		if($BuildingLoop > 0){
			$wBuildingName = '棟'.numberToCircled(1);
		}
	}
	if($BuildingLoop > 0)
		$IfBuildingExist = true;
	else
		$IfBuildingExist = false;

/*
	//ユーザーの会社コードと、物件の会社コードが異なる場合は、物件一覧に強制リダイレクトさせる
	//ただしネスペユーザーは除く
	if ($BukkenGyosyaCD != $MyGyosyaCD && strpos($LoginUserID, 'nespe') === false) {
		header("Location: ../s_search.php?rKey=" . $rKey);
		exit;
	}
*/
// 	########################################################
// 	# 部屋構成情報抽出
// 	########################################################

	$myBukkenMatrix = new BukkenMatrix($myDB);
	if($editBuildingCD){
		if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = " . $editBuildingCD . " AND MukouFlg = FALSE", "")) {
			trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);
		}
	}else{
		if (!$myBukkenMatrix->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD IS NULL AND MukouFlg = FALSE", "")) {
			trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);
		}
	}

	$KaiRoom = $myBukkenMatrix->KaiRoom;
	$KaiRoom = SPFWTools::decodePluralValue($KaiRoom);
	$KaiRoomColsCount = [];

	if ($myBukkenMatrix->RecCnt == 0 || count($KaiRoom) == 0) { #新規
		$IfNew = TRUE;
	} else {

		$IfRoomOK = $IfRoomOK2 = TRUE;

		$RoomSuu = count($KaiRoom);
		for ($x = 0; $x < count($KaiRoom); $x++) {
			#　前はいくつあるか不定　後ろはゼロサブで2桁固定 大きい部屋から格納されている
			$Room[$x] = substr($KaiRoom[$x], -2);
			#階高がかならずしも建物の部屋の前の文字を表していない 右うしろ2桁以外の文字 空に置き換え
			$Kai[$x] = str_replace($Room[$x], "", $KaiRoom[$x]);
			#echo "<br>Kai-Room:".$Kai[$x]."-".$Room[$x] ;
			if ($Kai[$x] == "")
				$Kai[$x] = $Room[$x];
		}

		########################################################
		# ２重ループ最小構成 Tate Yoko ( x,y )
		########################################################

		$CNT_FILE = "s_make_kanryo.tpl";
		$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

		// リスト部分(Loopの中身)のエレメント確定
		$LoopString = $myTemplate->getStringBetween('ColsLoop');
		$LoopString = '__ColsLoop__' . $LoopString . '__ColsLoop__';


		#$ColsLoop = count($Yoko );#
		$ColsLoop = max($Room); #
		$RowsLoop = $wKaidaka; #

		#echo "182行目:".$RowsLoop;
		$x = 0;
		for ($i = 0; $i < $RowsLoop; $i++) {
			$wKaiStart = 0;
			$ColsBlock[$i]="";
			$ColsCount = 0;
			for ($j = 0; $j < $ColsLoop; $j++) { #上からのフロアごとに左にすすむ

				if ($wKaiStart == $Kai[$x] or $wKaiStart == 0) { #
					$Pic[$j] = $KaiRoom[$x];
					$wKaiStart = $Kai[$x];
					$x = $x + 1;
					$ColsCount ++;
				} else { #階が異なっていたらーをいれておく。
					$Pic[$j] = "-";
				}
				$ColsBlock[$i] .= "<td>".$Pic[$j]."</td>";
			}
			$KaiRoomColsCount[$wKaiStart] = $ColsCount;
		}
	}
}
$wColsBlock = SPFWTools::encodePluralValue($ColsBlock);

########################################################
# 工事情報抽出  →　tBukkenMに埋め込む
########################################################

// $myKoji = new Koji($myDB);

// if (!$myKoji->executeSelect("BukkenCD = " . $editBukkenCD . "  AND MukouFlg = FALSE", "")) {
// 	trigger_error("Getting Koji Failed.", E_USER_ERROR);
// }

// if ($myKoji->RecCnt != 1) {

// 	#工事情報登録がまだ
// 	$ErrorString = array();
// 	$ErrorString[] = "工事情報を登録してください。";
// 	$ErrorLoop = count($ErrorString);
// 	$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
// 	exit;
// }

$IfKoji = TRUE;
// $IfNotKoji = FALSE;

$resolvedPeriod = resolveBuildingSenyuAndYoyaku($myBukken, $editBuildingCD ? $myBuilding : null, $editBuildingCD);
$SenyuStartDate = $resolvedPeriod['SenyuStartDate'];
$SenyuEndDate = $resolvedPeriod['SenyuEndDate'];
$YoyakuEndDate = $resolvedPeriod['YoyakuEndDate'];

$SenyuDateCnt = ((strtotime($SenyuEndDate) -  strtotime($SenyuStartDate)) / 86400) + 1; #専有部日数
//echo "<br>236行目".$SenyuDateCnt."-".$SenyuEndDate."-".$SenyuStartDate;

$DateStart = date("Y,n,d", strtotime('-1 month', strtotime($SenyuStartDate)));
$DateEnd = date("Y,n,d", strtotime('-1 month', strtotime($SenyuEndDate)));

$wHansu = $myBukken->Hansu;
// $wMinuteTime = $myKoji->MinuteTime;
$wWakuPattern = $myBukken->WakuPattern;
// if(!$wWakuPattern )$wWakuPattern = 0;
$wKojijun = $myBukken->Kojijun;
$wMaxWakuSu = $myBukken->MaxWakuSu;
$wFirstDateFeature = $myBukken->FirstDateFeature;
$Holiday1 = $myBukken->Holiday1;
$ReserveDay = $myBukken->ReserveDay;
$wFrameOverflow = $myBukken->FrameOverflow; // 枠越え枠数（工程表セル表記は「枠越」）
$wArrangeType = $myBukken->ArrangeType;
$wFloorReserveInfo = $myBukken->FloorReserveInfo;

if($editBuildingCD){
	$wHansu = $myBuilding->Hansu;
	$wWakuPattern = $myBuilding->WakuPattern;
	$wKojijun = $myBuilding->Kojijun;
	$wMaxWakuSu = $myBuilding->MaxWakuSu;
	$wFirstDateFeature = $myBuilding->FirstDateFeature;
	$Holiday1 = $myBuilding->Holiday1;
	$ReserveDay = $myBuilding->ReserveDay;
	$wFrameOverflow = $myBuilding->FrameOverflow;
	$wArrangeType = $myBuilding->ArrangeType;
	$wFloorReserveInfo = $myBuilding->FloorReserveInfo;
}

$wFloorReserveInfo = html_entity_decode($wFloorReserveInfo, ENT_QUOTES, 'UTF-8');
$arrFloorReserveInfo = json_decode($wFloorReserveInfo, true);

// デフォルトは点検に
if($wHansu == "")
	$wArrangeType = '1';

${"HansuSelect" . $wHansu} = " selected ";
if(!$wWakuPattern)$wWakuPattern=0;#いったん　１にセット
$wMaxWakuSu = "-" . $wMaxWakuSu . "-";
$wMaxWakuSuu = SPFWTools::decodePluralValue($wMaxWakuSu, "-");
${"KojijunChecked" . $wKojijun} = " checked ";
// ${"FrameOverflowSelect" . $wFrameOverflow} = " selected ";
${"ArrangeTypeChecked" . $wArrangeType} = " checked ";
${"ArrangeTypeActived" . $wArrangeType} = " active ";

if($wArrangeType == '1'){
	$IfArrangeType1 = TRUE;
	$IfArrangeType0 = FALSE;
	$clsTableMakeKanryo = "arrangeTypeTable1";
}else{
	$IfArrangeType1 = FALSE;
	$IfArrangeType0 = TRUE;
	$clsTableMakeKanryo = "arrangeTypeTable0";
}


for ($i = 1; $i <= count($wMaxWakuSuu); $i++) {

	${"wWaku" . $WAKUPATTERN[$wWakuPattern]['AMPM'][$i - 1]} = $wMaxWakuSuu[$i];
}
if ($wWakuAM1)
	$wWakuAM = $wWakuAM1;

$WakuAMPMCnt = count($WAKUPATTERN[$wWakuPattern]['AMPM']);

// /*
// $WAKUPATTERN[0]['Name'] = "2枠( AM 9:00-12:00, PM 13:00-17:00)";
// $WAKUPATTERN[0]['StartTime'][0] = "09:00";
// $WAKUPATTERN[0]['EndTime'][0] = "12:00";
// $WAKUPATTERN[0]['AMPM'][0] = "AM";
// $WAKUPATTERN[0]['WD60min'][0] = "3";
// $WAKUPATTERN[0]['WE60min'][0] = "2";
// $WAKUPATTERN[0]['StartTime'][1] = "13:00";
// $WAKUPATTERN[0]['EndTime'][1] = "17:00";
// $WAKUPATTERN[0]['AMPM'][1] = "PM";
// $WAKUPATTERN[0]['WD60min'][1] = "4";
// $WAKUPATTERN[0]['WE60min'][1] = "3";

// 	$wWakuPM1 = $wMaxWakuSuu[2];
// 	$wWakuPM2 = $wMaxWakuSuu[3];
// 	*/
${"FirstDateFeature" . $wFirstDateFeature} = "selected";
for ($i = 0; $i < $WakuAMPMCnt; $i++) {
	$MaxWakuForm .= '<td class="MaxWakuForm">' . $WAKUPATTERN[$wWakuPattern]['AMPM'][$i] . '<input type="number" name="wWaku' . $WAKUPATTERN[$wWakuPattern]['AMPM'][$i] . '" value="' . ${"wWaku" . $WAKUPATTERN[$wWakuPattern]['AMPM'][$i]} . '" style="width:50px;" class="wakuampm" min="1"></td>';
}
$wakupattern_json = json_encode($WAKUPATTERN);
// echo "<table><tr>";
// echo $MaxWakuForm;
// echo "</tr></table>";
// exit();

$Holiday = SPFWTools::decodePluralValue($Holiday1);
sort($Holiday);

$ReserveDay = SPFWTools::decodePluralValue($ReserveDay);
sort($ReserveDay);

$MINWaku = ($RoomSuu / $SenyuDateCnt) + 4;
/*
$HolidayDisp = "<table class='table table-bordered table-sm'><tr>";
for ($i = 1; $i <= count($Holiday); $i++) {
	if ($i % 3 == 1) {
		$HolidayDisp .= "<tr>";
	}
	${"wHoliday" . $i} = $Holiday[$i - 1];
	$HolidayDisp .= "<td>" . ${"wHoliday" . $i} . "</td>";
	if ($i % 3 == 0) {
		$HolidayDisp .= "</tr>";
	}
	$HolidayFlg = true;
}
$HolidayDisp .= "</table>";
*/

// 休工日
$maxNo = 0; // デフォルト
$KyukoTable = "";
if ($Holiday1) {
	$Holiday = SPFWTools::decodePluralValue($Holiday1);
	for ($i = 1; $i <= count($Holiday); $i++) {
		${"wHoliday" . $i} = $Holiday[$i - 1];
	}
	$maxNo 	= count($Holiday);
	$maxNo1 = ceil($maxNo / 3);
	$maxNo 	= $maxNo1 * 3 + 1;
	$j = 4;
	for ($i = 1; $i < $maxNo1; $i++) {
		$KyukoTable .= "<tr><td><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='../images/icon_delete.png'></a></td>";
		$KyukoTable .= "<td><input type='text' name='wHoliday[]' value='__wHoliday" . $j . "__' class='wHoliday' style='width:120px' >";
		$KyukoTable .= "　<input type='text' name='wHoliday[]' value='__wHoliday" . ($j + 1) . "__' class='wHoliday' style='width:120px' >";
		$KyukoTable .= "　<input type='text' name='wHoliday[]' value='__wHoliday" . ($j + 2) . "__' class='wHoliday' style='width:120px' ></td></tr>";
		$j += 3;
	}
}

// 予備日
$maxNo = 0; // デフォルト
$ReserveDayTable = "";
if ($ReserveDay) {
	for ($i = 1; $i <= count($ReserveDay); $i++) {
		${"wReserveDay" . $i} = $ReserveDay[$i - 1];
	}
	$maxNo 	= count($ReserveDay);
	$maxNo1 = ceil($maxNo / 3);
	$maxNo 	= $maxNo1 * 3 + 1;
	$j = 4;
	for ($i = 1; $i < $maxNo1; $i++) {
		$ReserveDayTable .= "<tr><td><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='../images/icon_delete.png'></a></td>";
		$ReserveDayTable .= "<td><input type='text' name='wReserveDay[]' value='__wReserveDay" . $j . "__' class='wReserveDay' style='width:120px' >";
		$ReserveDayTable .= "　<input type='text' name='wReserveDay[]' value='__wReserveDay" . ($j + 1) . "__' class='wReserveDay' style='width:120px' >";
		$ReserveDayTable .= "　<input type='text' name='wReserveDay[]' value='__wReserveDay" . ($j + 2) . "__' class='wReserveDay' style='width:120px' ></td></tr>";
		$j += 3;
	}
}

########################################################
# 枠パターン一覧表示、
########################################################
$WakuPatternLoop = count($WAKUPATTERN);
for ($x = 0; $x < count($WAKUPATTERN); $x++) {
	$WakuPattern[$x] = $x;
	$WakuPatternName[$x] = $WAKUPATTERN[$x]['Name']; # = "3枠(9:00-12:00,13:00-15:00,15:00-18:00)";
}
$SelectedWakuPattern[$wWakuPattern] = " selected ";

########################################################
# 詳細工程表　作成用
########################################################

$date = new DateTime($SenyuStartDate);

$HoliDay = '0';
$WeekDay = '0';
$FHoliDay = '0';
$FWeekDay = '0';


// if ($IfKoji) {
	//echo "<br>253行目　SenyuDateCnt".$SenyuDateCnt;
	for ($i = 0; $i < $SenyuDateCnt; $i++) {
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate, $SHUKUJITULIST);
		$result2 = array_search($SenyuDate, $Holiday);
		$YoubiCD =  $date->format('w');
		if ($result2 === false) {
			if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
				if ($i == 0) {
					$FHoliDay += 1;
				} else {
					$HoliDay += 1;
				}
			} else {
				if ($i == 0) {
					$FWeekDay += 1;
				} else {
					$WeekDay += 1;
					//echo "<br>269行目".$WeekDay;
				}
			}
		}
		$date->modify('+1 days');

	}
	$DateSum = $FHoliDay + $FWeekDay + $HoliDay + $WeekDay;
	if ($DateSum > 1) {


		#2枠選択時（初日考慮無）のベスト案
		if($FHoliDay + (2 * $FWeekDay) + $HoliDay + (2 * $WeekDay)){
			$MinWakuSum = ((2 * (count($KaiRoom))) + (4 * $FHoliDay) + (8 * $FWeekDay) + (4 * $WeekDay)) / ($FHoliDay + (2 * $FWeekDay) + $HoliDay + (2 * $WeekDay));
			$MinWakuSum = ceil($MinWakuSum);
		}

		//echo $MinWakuSum;
		#2枠選択時の初日がすべて空きになってしまう時
		if($HoliDay + (2 * $WeekDay)){
			$MaxWakuSum = ((2 * count($KaiRoom)) + (4 * $WeekDay)) / ($HoliDay + (2 * $WeekDay));
			$MaxWakuSum = ceil($MaxWakuSum);
		}

		#3枠選択時（初日考慮無）のベスト案
		if($FHoliDay + (2 * $FWeekDay) + $HoliDay + (2 * $WeekDay)){
			$MinWakuSum2 = ((2 * count($KaiRoom)) + (6 * $FHoliDay) + (12 * $FWeekDay) + (6 * $WeekDay)) / ($FHoliDay + (2 * $FWeekDay) + $HoliDay + (2 * $WeekDay));
			$MinWakuSum2 = ceil($MinWakuSum2);
		}

		#3枠選択時の初日がすべて空きになってしまう時
		if($HoliDay + (2 * $WeekDay)){
			$MaxWakuSum2 = ((2 * count($KaiRoom)) + (6 * $WeekDay)) / ($HoliDay + (2 * $WeekDay));
			$MaxWakuSum2 = ceil($MaxWakuSum2);
		}
	} elseif ($DateSum == 1) {
		#初日のみの場合
		if($FHoliDay + (2 * $FWeekDay)){
			$MinWakuSum3 = (2 * count($KaiRoom) + (4 * $FHoliDay) + (8 * $FWeekDay)) / ($FHoliDay + (2 * $FWeekDay));
			$MinWakuSum3 = ceil($MinWakuSum3);
		}
	}


	//3枠のとき
	if(2 * $WeekDay + $HoliDay + 1){
		$MinWakuSum = ((6 * $WeekDay) + 6 + (2 * count($KaiRoom))) / (2 * $WeekDay + $HoliDay + 1);
		$MinWakuSum = floor($MinWakuSum);
	}
	//echo "<br>274行目 WeekDay:".$WeekDay." KaiRoom:".count($KaiRoom)." HoliDay:".$HoliDay;

	//echo "<br>278行目 MinWakuSum:".$MinWakuSum;
	$MaxWakuSum = (6 * $WeekDay) + (6 * $FHoliDay) + (12 * $FWeekDay) + (2 * (count($KaiRoom)));
	if((2 * $WeekDay) + $HoliDay + $FHoliDay + (2 * $FWeekDay)){
		$MaxWakuSum = $MaxWakuSum / ((2 * $WeekDay) + $HoliDay + $FHoliDay + (2 * $FWeekDay));
		//echo "<br>280行目".$MaxWakuSum."/((2*".$WeekDay.")+".$HoliDay."+".$FHoliDay."+(2*".$FWeekDay."));";
		$MaxWakuSum = floor($MaxWakuSum) - 1;
	}

	//echo "<br>282行目 MaxWakuSum:".$MaxWakuSum;
	//2枠のとき
	if(2 * $WeekDay + $HoliDay + 1){
		$MinWakuSum2 = ((4 * $WeekDay) + 4 + (2 * count($KaiRoom))) / (2 * $WeekDay + $HoliDay + 1);
		$MinWakuSum2 = floor($MinWakuSum2);
	}
	$MaxWakuSum2 = (4 * $WeekDay) + (4 * $FHoliDay) + (8 * $FWeekDay) + (2 * (count($KaiRoom)));
	if((2 * $WeekDay) + $HoliDay + $FHoliDay + (2 * $FWeekDay)){
		$MaxWakuSum2 = $MaxWakuSum2 / ((2 * $WeekDay) + $HoliDay + $FHoliDay + (2 * $FWeekDay));
		$MaxWakuSum2 = floor($MaxWakuSum2);
	}

	//午前NGのとき
	if ($WeekDay !== 0) {
		$MinWakuSum3 = (6 * $WeekDay) + (2 * (count($KaiRoom)));
		#echo $MinWakuSum3."<br>".$WeekDay;
		//$MinWakuSum3 = $MinWakuSum3/((2*$WeekDay)+$HoliDay);

		$MinWakuSum3 = floor($MinWakuSum3) - 1;
	}


// 	/*	//午前NGのとき
// 			$MinWakuSum3 = (6*$WeekDay)+(2*(count($KaiRoom)));
// 			$MinWakuSum3 = $MinWakuSum3/((2*$WeekDay)+$HoliDay);
// 			$MinWakuSum3 = floor($MinWakuSum3)-1;
// 		*/

	########################################################
	# 戻るボタン
	########################################################
	$backw = SPFWParameter::getValues('backw');
	if ($backw == 1) {
		$wHansu = SPFWParameter::getValues('wHansu');			#班数
		$wMinuteTime = SPFWParameter::getValues('wMinuteTime');	#工事施工時間（リアル）
		$wWakuPattern = SPFWParameter::getValues('wWakuPattern');
		$wWakuAM = SPFWParameter::getValues('wWakuAM');
		$wWakuPM1 = SPFWParameter::getValues('wWakuPM1');
		$wWakuPM2 = SPFWParameter::getValues('wWakuPM2');
		$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature'); #初日工事数考慮
		$wKojijun = SPFWParameter::getValues('wKojijun');		# 工事順
		$wHoliday1 = SPFWParameter::getValues('wHoliday1');		#休日
		$wHoliday2 = SPFWParameter::getValues('wHoliday2');
		$wHoliday3 = SPFWParameter::getValues('wHoliday3');
		$wHoliday4 = SPFWParameter::getValues('wHoliday4');

		$wReserveDay1 = SPFWParameter::getValues('wReserveDay1');		#休日
		$wReserveDay2 = SPFWParameter::getValues('wReserveDay2');
		$wReserveDay3 = SPFWParameter::getValues('wReserveDay3');
		$wReserveDay4 = SPFWParameter::getValues('wReserveDay4');
		$wFrameOverflow = SPFWParameter::getValues('wFrameOverflow');			#班数

		${"HansuSelect" . $wHansu} = "selected";
		$SelectedWakuPattern[$wWakuPattern] = "selected";
		// ${"FrameOverflowSelect" . $wFrameOverflow} = "selected";
		if ($wWakuPattern <= 2) {
			$p2style = "style=\"display: none\"";
		} else {
			$p2style = "style=\"display: block\"";
		}
		${"FirstDateFeature" . $wFirstDateFeature} = "selected";
		${"KojijunChecked" . $wKojijun} = "checked";
	}
	if ($wWakuPattern > 2) { #3枠なら
		$OverErrorStrings = "※最大工事枠数の合計を" . $MinWakuSum . "以下に設定してください。";
		$OverErrorStrings .= "<br>　ただし、休工日・初日考慮設定時は少し多めに設定してください。";
	} elseif ($wWakuPattern < 3) { #2枠なら
		$OverErrorStrings = "※最大工事枠数の合計を" . $MinWakuSum2 . "以下に設定してください。";
		$OverErrorStrings .= "<br>　ただし、休工日・初日考慮設定時は少し多めに設定してください。";
	}
// }


########################################################
# 工事日程登録済みを表示
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "r.ID, ";
$sql .= "r.TimeFrom, ";
$sql .= "r.Updated, ";
$sql .= "u.Passwd ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tReservationF r, tUserM u ";
$sql .= " WHERE r.MukouFlg = FALSE  and r.UserCD = u.UserCD";
$sql .= " AND r.BukkenCD = " . $editBukkenCD;
if($editBuildingCD){
	$sql .= " AND r.BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND r.BuildingCD IS NULL ";
}

$myListObject->Condition = $sql;

$myListObject->Order = "CAST(r.ID AS UNSIGNED) ASC";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1))) {
	$ErrorString = array();
	$ErrorString[] = "メニューマスタリストの抽出に失敗しました。";
	showAdminSorryPage($ErrorString);
}

// データ表示

$CellLoop = $myListObject->Rows;

if ($CellLoop) {
	$IfKojiDateOK = TRUE;
}
for ($i = 0; $i < $CellLoop; $i++) {
	$No[$i] = $i + 1;

	$RoomID[$i] = $myListObject->GetValue($i, 0);

	$wTimeFromDate[$i] = substr($myListObject->GetValue($i, 1), 0, 10);
	$wTimeFromTime[$i] = substr($myListObject->GetValue($i, 1), 11, 5);

	if($wArrangeType == '1'){
		#　前はいくつあるか不定　後ろはゼロサブで2桁固定 大きい部屋から格納されている
		$KaiRoomLen = strlen($RoomID[$i]);
		#Room[$x]…02,03,12  Kai[$x]…1,2,11 など
		$Floor = substr($RoomID[$i], 0, ($KaiRoomLen - 2));
		if(isset($arrFloorReserveInfo[$Floor]["wFloorWaku"]))
			$wTimeFromTime[$i] = $arrFloorReserveInfo[$Floor]["wFloorWaku"];
	}

	$Passwd[$i] = $myListObject->GetValue($i, 3);
}

$FloorTableInfo = '';
$wKaidaka = intval($wKaidaka);

for($floor=$wKaidaka; $floor>=1; $floor--){
	$ColsCount = isset($KaiRoomColsCount[$floor])?$KaiRoomColsCount[$floor]:0;
	$FloorTableInfo .= "<tr>";
		$FloorTableInfo .= "<td>".$floor."F<input type='hidden' name='wFloor_".$floor."' value='".$floor."'></td>";
		$wFloorDayVal = "";
		if(isset($arrFloorReserveInfo[$floor]["wFloorDay"]))
			$wFloorDayVal = $arrFloorReserveInfo[$floor]["wFloorDay"];
		$FloorTableInfo .= "<td align='center'><input type='text' name='wFloorDay_".$floor."' value='".$wFloorDayVal."' class='wFloorDay'></td>";

		$optionsWaku = '<option value=""></option>';
		for ($i = 0; $i < $WakuAMPMCnt; $i++) {
			$selected = '';
			if(isset($arrFloorReserveInfo[$floor]["wFloorWaku"]) && $arrFloorReserveInfo[$floor]["wFloorWaku"] == $WAKUPATTERN[$wWakuPattern]['AMPM'][$i])
				$selected = 'selected';

			$optionsWaku .= "<option value='".$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]."' ".$selected.">".$WAKUPATTERN[$wWakuPattern]['AMPM'][$i]."</option>";
		}
		$FloorTableInfo .= "<td align='center'><select class='sFloorWakuSelect' name='wFloorWaku_".$floor."'>".$optionsWaku."</select></td>";
		$FloorTableInfo .= "<td align='center'>".$ColsCount."<input type='hidden' name='wFloorCols_".$floor."' value='".$ColsCount."'></td>";
	$FloorTableInfo .= "</tr>";
}

$ReserveTableTimeTitle = '開始時間';
if($wArrangeType == '1')
	$ReserveTableTimeTitle = '時間帯';

########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "s_make_kanryo2.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
