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
// include_once _CLS_DIR . "SPUSShiryo.cls";
// include_once _CLS_DIR . "SPUSIraiRenkei.cls";
include_once _CLS_DIR . "SPUSBukkenMatrix.cls";
// include_once _CLS_DIR . "SPUSKoji.cls";
// include_once _CLS_DIR . "SPUSFile.cls";
// include_once _CLS_DIR . "SPUSKojiDate.cls";
include_once _CLS_DIR . "SPUSGyosya.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";
include_once _CLS_DIR . "SPUSReservationInit.cls";
include_once _CLS_DIR . "SPUSReservationTemp.cls";
include_once dirname(__DIR__) . "/include/building_period_helpers.php";
include_once dirname(__DIR__) . "/include/holiday_helpers.php";


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

$MyUserCD = $myUser->UserCD;
$TargetClientCD = $myUser->ClientCD;#顧客を区別する
#$ID = $myUser->ID;
unset($myUser);


########################################################
# 設定パラメータ取得取得
########################################################

$wHansu = SPFWParameter::getValues('wHansu');			#班数
#$wMinuteTime = SPFWParameter::getValues('wMinuteTime');	#工事施工時間（リアル）
$wWakuPattern = SPFWParameter::getValues('wWakuPattern');

$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');

$wWakuAMcol = SPFWParameter::getValues('wWakuAMcol');
$wWakuPM1col = SPFWParameter::getValues('wWakuPM1col');
$wWakuPM2col = SPFWParameter::getValues('wWakuPM2col');

$wFrameOverflow = SPFWParameter::getValues('wFrameOverflow');			#枠越え

$rowCountforDay = SPFWParameter::getValues('rowCountforDay');
if(!$rowCountforDay)
	$rowCountforDay = $wHansu;

if(empty($wWakuPM1col))$wWakuPM1col = 0;
if(empty($wWakuPM2col))$wWakuPM2col = 0;
$hensyu = SPFWParameter::getValues('hensyu');

$wKoteihyouEX = array();
$wHansuEX = array();

$wKoteihyouEX_count = SPFWParameter::getValues('wKoteihyouEX_count');
$wHansuEX_count = SPFWParameter::getValues('wHansuEX_count');
if(empty($wKoteihyouEX_count) || $wKoteihyouEX_count == ''){
	$wKoteihyouEX_count = 0;
}
if(empty($wHansuEX_count) || $wHansuEX_count == ''){
	$wHansuEX_count = 0;
}
if($wKoteihyouEX_count == 0 || $wHansuEX_count == 0){
	echo ('<script>
		alert("工程表作成中にエラーが発生いたしました。");
		location.href="../s_menu.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
	</script>');
	exit;		
}
for($i=0; $i<$wKoteihyouEX_count; $i++){
	$wKoteihyouEX_temp = SPFWParameter::getValues('wKoteihyouEX_'.$i);
	if(empty($wKoteihyouEX_temp) || $wKoteihyouEX_temp == ''){
		echo ('<script>
			alert("工程表作成中にエラーが発生いたしました。");
			location.href="../s_menu.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
		</script>');
		exit;		
	}
	$wKoteihyouEX = array_merge($wKoteihyouEX, SPFWTools::decodePluralValue($wKoteihyouEX_temp));
}
foreach ($wKoteihyouEX as $idx => $label) {
	if ($label === '余地') {
		$wKoteihyouEX[$idx] = '空き';
	} else if ($label === '時間外') {
		$wKoteihyouEX[$idx] = '枠越';
	}
}
for($i=0; $i<$wHansuEX_count; $i++){
	$wHansuEX_temp = SPFWParameter::getValues('wHansuEX_'.$i);
	if(empty($wHansuEX_temp) || $wHansuEX_temp == ''){
		echo ('<script>
			alert("工程表作成中にエラーが発生いたしました。");
			location.href="../s_menu.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
		</script>');
		exit;		
	}
	$wHansuEX = array_merge($wHansuEX, SPFWTools::decodePluralValue($wHansuEX_temp));
}


// 空き室のない部屋を優先的に再配置します。
$wWakuAMcol = intval($wWakuAMcol);
$wWakuPM1col = intval($wWakuPM1col);
$wWakuPM2col = intval($wWakuPM2col);
$wHansuRows = intval($rowCountforDay / $wHansu);
$wKoteihyouIndex = 0;
$curAMPM = 'AM';
$curAMPMColIndex = 0;
$AM_Blanks = array();
$AM_Rooms = array();
$PM1_Blanks = array();
$PM1_Rooms = array();
$PM2_Blanks = array();
$PM2_Rooms = array();
$val_Blanks = ['空き', '枠越', ''];
$rowIndex = 0;

$wKoteihyouEXTemp = array();
$wHansuEXTemp = array();

while($wKoteihyouIndex < count($wKoteihyouEX)){
	for($i=0; $i<$wWakuAMcol; $i++){
		if($wKoteihyouIndex >= count($wKoteihyouEX))
			break;
		$disRoom = $wKoteihyouEX[$wKoteihyouIndex];
		$disHansu = $wHansuEX[$wKoteihyouIndex]??'';
		if(in_array($disRoom, $val_Blanks)){
			array_push($AM_Blanks, array(
				'room' => $disRoom,
				'hansu' => $disHansu
			));
		}else{
			array_push($AM_Rooms, array(
				'room' => $disRoom,
				'hansu' => $disHansu
			));
		}
		$wKoteihyouIndex ++;
	}
	for($i=0; $i<$wWakuPM1col; $i++){
		if($wKoteihyouIndex >= count($wKoteihyouEX))
			break;
		$disRoom = $wKoteihyouEX[$wKoteihyouIndex];
		$disHansu = $wHansuEX[$wKoteihyouIndex]??'';
		if(in_array($disRoom, $val_Blanks)){
			array_push($PM1_Blanks, array(
				'room' => $disRoom,
				'hansu' => $disHansu
			));
		}else{
			array_push($PM1_Rooms, array(
				'room' => $disRoom,
				'hansu' => $disHansu
			));
		}
		$wKoteihyouIndex ++;
	}
	for($i=0; $i<$wWakuPM2col; $i++){
		if($wKoteihyouIndex >= count($wKoteihyouEX))
			break;
		$disRoom = $wKoteihyouEX[$wKoteihyouIndex];
		$disHansu = $wHansuEX[$wKoteihyouIndex]??'';
		if(in_array($disRoom, $val_Blanks)){
			array_push($PM2_Blanks, array(
				'room' => $disRoom,
				'hansu' => $disHansu
			));
		}else{
			array_push($PM2_Rooms, array(
				'room' => $disRoom,
				'hansu' => $disHansu
			));
		}
		$wKoteihyouIndex ++;
	}

	$rowIndex ++;

	if($rowIndex >= $wHansuRows){
		$AM_Index = 0;
		$AM_IsRoom = true;
		$PM1_Index = 0;
		$PM1_IsRoom = true;
		$PM2_Index = 0;
		$PM2_IsRoom = true;

		for($j=0; $j<$wHansuRows; $j++){
			for($i=0; $i<$wWakuAMcol; $i++){
				if($AM_IsRoom){
					if($AM_Index < count($AM_Rooms)){
						array_push($wKoteihyouEXTemp, $AM_Rooms[$AM_Index]['room']);
						array_push($wHansuEXTemp, $AM_Rooms[$AM_Index]['hansu']);
						$AM_Index ++;
					}else{
						$AM_IsRoom = false;
						$AM_Index = 0;
					}
				}

				if(!$AM_IsRoom){
					if($AM_Index < count($AM_Blanks)){
						array_push($wKoteihyouEXTemp, $AM_Blanks[$AM_Index]['room']);
						array_push($wHansuEXTemp, $AM_Blanks[$AM_Index]['hansu']);
						$AM_Index ++;
					}else{
						break;
					}
				}
			}
			for($i=0; $i<$wWakuPM1col; $i++){
				if($PM1_IsRoom){
					if($PM1_Index < count($PM1_Rooms)){
						array_push($wKoteihyouEXTemp, $PM1_Rooms[$PM1_Index]['room']);
						array_push($wHansuEXTemp, $PM1_Rooms[$PM1_Index]['hansu']);
						$PM1_Index ++;
					}else{
						$PM1_IsRoom = false;
						$PM1_Index = 0;
					}
				}

				if(!$PM1_IsRoom){
					if($PM1_Index < count($PM1_Blanks)){
						array_push($wKoteihyouEXTemp, $PM1_Blanks[$PM1_Index]['room']);
						array_push($wHansuEXTemp, $PM1_Blanks[$PM1_Index]['hansu']);
						$PM1_Index ++;
					}else{
						break;
					}
				}
			}
			for($i=0; $i<$wWakuPM2col; $i++){
				if($PM2_IsRoom){
					if($PM2_Index < count($PM2_Rooms)){
						array_push($wKoteihyouEXTemp, $PM2_Rooms[$PM2_Index]['room']);
						array_push($wHansuEXTemp, $PM2_Rooms[$PM2_Index]['hansu']);
						$PM2_Index ++;
					}else{
						$PM2_IsRoom = false;
						$PM2_Index = 0;
					}
				}

				if(!$PM2_IsRoom){
					if($PM2_Index < count($PM2_Blanks)){
						array_push($wKoteihyouEXTemp, $PM2_Blanks[$PM2_Index]['room']);
						array_push($wHansuEXTemp, $PM2_Blanks[$PM2_Index]['hansu']);
						$PM2_Index ++;
					}else{
						break;
					}
				}
			}			
		}
	
		$AM_Blanks = array();
		$AM_Rooms = array();
		$PM1_Blanks = array();
		$PM1_Rooms = array();
		$PM2_Blanks = array();
		$PM2_Rooms = array();
		$rowIndex = 0;
	}
}

$wKoteihyouEX = $wKoteihyouEXTemp;
$wHansuEX = $wHansuEXTemp;

$wArrangeType = SPFWParameter::getValues('wArrangeType');
$wFloorReserveInfo = SPFWParameter::getValues('FloorReserveInfo');
$arrFloorReserveInfo = [];
$FloorReserveInfo = '';
if($wFloorReserveInfo != ''){
	$arrFloorReserveInfo = json_decode($wFloorReserveInfo, true);
	$FloorReserveInfo = $wFloorReserveInfo;
}

$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
	showAdminSorryPage($ErrorString);
}

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD . " AND MukouFlg = FALSE", "")) {
		$ErrorString = array();
		$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
}


// 「工程表 作成」ボタンをクリックした場合、保管

$wHoliday 				= SPFWParameter::getValues("wHoliday"); // 配列
$wHolidayPeriod			= SPFWParameter::getValues("wHolidayPeriod"); // 配列
$Holiday = combineHolidayInputs($wHoliday, $wHolidayPeriod);
$wReserveDay 				= SPFWParameter::getValues("wReserveDay"); // 配列
$ReserveDay = array();
if(is_array($wReserveDay) && count($wReserveDay) > 0){
	for ($i = 0; $i < count($wReserveDay); $i++) {
		if ($wReserveDay[$i]) {
			$ReserveDay[] = $wReserveDay[$i];
		}
	}
}
$wKojijun = SPFWParameter::getValues('wKojijun');		# 工事順
$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature'); #初日工事数考慮
$wWakuAM = SPFWParameter::getValues('wWakuAM');
$wWakuAM2 = SPFWParameter::getValues('wWakuAM2');
$wWakuAM3 = SPFWParameter::getValues('wWakuAM3');
$wWakuPM = SPFWParameter::getValues('wWakuPM');
$wWakuPM1 = SPFWParameter::getValues('wWakuPM1');
$wWakuPM2 = SPFWParameter::getValues('wWakuPM2');

for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	if ($i != 0)
		$MaxWakuSu .= "-";

	$MaxWakuSu .= ${'wWaku' . $WAKUPATTERN[$wWakuPattern]['AMPM'][$i]};
}


if($editBuildingCD){
	$myBuilding->Holiday1 = encodeHoliday1($Holiday); #パイプつなぎ
	$myBuilding->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ
	$myBuilding->Hansu = $wHansu;
	$myBuilding->WakuPattern = $wWakuPattern;
	$myBuilding->Kojijun = $wKojijun;
	$myBuilding->FirstDateFeature = $wFirstDateFeature;
	$myBuilding->MaxWakuSu = $MaxWakuSu;
	$myBuilding->FrameOverflow = $wFrameOverflow;
	$myBuilding->ArrangeType = $wArrangeType;
	$myBuilding->FloorReserveInfo = $wFloorReserveInfo;

	if (!$myBuilding->executeUpdate()) {
		trigger_error("executeUpdate(myBuilding) Failed.", E_USER_ERROR);
	}
}else{
	$myBukken->Hansu = $wHansu;
	$myBukken->WakuPattern = $wWakuPattern;
	$myBukken->Kojijun = $wKojijun;
	$myBukken->FirstDateFeature = $wFirstDateFeature;
	$myBukken->MaxWakuSu = $MaxWakuSu;
	$myBukken->FrameOverflow = $wFrameOverflow;
	$myBukken->ArrangeType = $wArrangeType;
	$myBukken->FloorReserveInfo = $wFloorReserveInfo;


	$myBukken->Holiday1 = encodeHoliday1($Holiday); #パイプつなぎ
	$myBukken->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ

	if (!$myBukken->executeUpdate()) {
		trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
	}
}




#GyosyaCDから取得する
$GyosyaCD = $myBukken->GyosyaCD;#GyosyaName , GyosyaTEL

$myGyosya = new Gyosya($myDB);

if (!$myGyosya->executeSelect("GyosyaCD = " . $GyosyaCD, "")) {
	$ErrorString = array();
	$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
	showAdminSorryPage($ErrorString);
}
$SekoShutai = $myGyosya->GyosyaName;
$TelNumber = $myGyosya->GyosyaTEL;

########################################################
# 受付連絡先（クライアント）
########################################################
$ClientTEL = '';
$BusinessHours = '';
$BusinessHoursNote = '';
$BusinessHoursDisplay = '';
$ReceptionHoursCell = '（工事期間中無休　９：００～１７：３０）';

$wClientCD = $myBukken->ClientCD;
if ($wClientCD) {
	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "ClientName, TEL, BusinessHours, BusinessHoursNote ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tClientM";
	$sql .= " WHERE MukouFlg = FALSE AND ClientCD='" . $wClientCD . "'";

	$myListObject->Condition	= $sql;
	$myListObject->Order 		= "";
	$myListObject->Limit 		= "1";

	if ($myListObject->GetList(1)) {
		$ClientTEL = $myListObject->GetValue(0, 1) ?? '';
		$BusinessHours = $myListObject->GetValue(0, 2) ?? '';
		$BusinessHoursNote = $myListObject->GetValue(0, 3) ?? '';
	}
	unset($myListObject);
}

$BusinessHoursDisplay = trim($BusinessHours . $BusinessHoursNote);
if ($BusinessHoursDisplay !== '') {
	$ReceptionHoursCell = (mb_strpos($BusinessHoursDisplay, '（') === 0)
		? $BusinessHoursDisplay
		: '（' . $BusinessHoursDisplay . '）';
}

// $KyoyoStartDate = $myKoji->KyoyoStartDate;
// $KyoyoEndDate = $myKoji->KyoyoEndDate;
// $SenyuStartDate = $myBukken->SenyuStartDate;
// $SenyuEndDate = $myBukken->SenyuEndDate;

$resolvedPeriod = resolveBuildingSenyuAndYoyaku($myBukken, $editBuildingCD ? $myBuilding : null, $editBuildingCD);
$SenyuStartDate = $resolvedPeriod['SenyuStartDate'];
$SenyuEndDate = $resolvedPeriod['SenyuEndDate'];
$ReceptionDate = $resolvedPeriod['YoyakuEndDate'];#予約受付締切日

$SenyuDateCnt = ((strtotime($SenyuEndDate) -  strtotime($SenyuStartDate)) / 86400) + 1; #専有部日数
$wMinuteTime = $myBukken->MinuteTime;		#工事施工時間（リアル）

if(!$wMinuteTime){
	echo "基本情報の作業時間が登録されていません。";
	exit;
}

$wHansu = $myBukken->Hansu;			#班数
$wWakuPattern = $myBukken->WakuPattern;	#枠パターン
$wKojijun = $myBukken->Kojijun;		#工事順
$wFirstDateFeature = $myBukken->FirstDateFeature;		#初日工事数考慮
$wWakuSu = "|" . str_replace("-", "|", $myBukken->MaxWakuSu) . "|";		#最大工事枠数
$wHoliday1 = $myBukken->Holiday1;			#休工日[配列]
$wReserveDay = $myBukken->ReserveDay;			#予備日[配列]
$wFrameOverflow = $myBukken->FrameOverflow;			#枠越え

if($editBuildingCD){
	$wHansu = $myBuilding->Hansu;
	$wWakuPattern = $myBuilding->WakuPattern;
	$wKojijun = $myBuilding->Kojijun;
	$wFirstDateFeature = $myBuilding->FirstDateFeature;
	$wWakuSu = "|" . str_replace("-", "|", $myBuilding->MaxWakuSu) . "|";
	$wHoliday1 = $myBuilding->Holiday1;
	$wReserveDay = $myBuilding->ReserveDay;
	$wFrameOverflow = $myBuilding->FrameOverflow;
}

$wWakuSu = SPFWTools::decodePluralValue($wWakuSu);
if ($wWakuPattern > 5) {

	$wWakuAM1 = $wWakuSu[0];
	$wWakuAM2 = $wWakuSu[1];
	$wWakuPM1 = $wWakuSu[2];
	$wWakuPM2 = $wWakuSu[3];
} elseif ($wWakuPattern > 2) {

	$wWakuAM = $wWakuSu[0];
	$wWakuPM1 = $wWakuSu[1];
	$wWakuPM2 = $wWakuSu[2];
} else {
	$wWakuAM = $wWakuSu[0];
	$wWakuPM1 = $wWakuSu[1];
}
$wHolidayItems = parseHoliday1($wHoliday1);
$HolidayLoop  = count($wHolidayItems);
for ($i = 0; $i < $HolidayLoop; $i++) {
	// Excel日単位の休工マークは全日のみ（半日は工程セル側で表現）
	if ($wHolidayItems[$i]['period'] !== 'ALL') {
		continue;
	}
	$Kyujitu[] = (strtotime($wHolidayItems[$i]['date']) - strtotime($SenyuStartDate)) / 86400;
}

$wReserveDay = SPFWTools::decodePluralValue($wReserveDay);
$beforeReserveDay = [];
$afterReserveDay = [];
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
$beforeReserveDayDateCnt = count($beforeReserveDay);
$afterReserveDayDateCnt = count($afterReserveDay);

#$myBukken->WakuSu = $wWakuSu ;

// if (!$myBukken->executeUpdate()) {
// 	$ErrorString = array();
// 	$ErrorString[] = "工事情報登録に失敗しました。";
// 	showAdminSorryPage($ErrorString);
// } else {
// 	$IfOK = TRUE;
// }

$BukkenName = $myBukken->BukkenName;
$wBuildingName = $myBukken->BuildingName;

if($editBuildingCD){
	$wBuildingName = $myBuilding->BuildingName;
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
$BuildingLoop = $myListObject->Rows;

for ($i = 0; $i < $BuildingLoop; $i++) {
	$BuildingCD[$i] = $myListObject->GetValue($i, 0);
	$BuildingName[$i] = $myListObject->GetValue($i, 1);
}
unset($myListObject);

function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}
// 棟名称が空の場合、例外処理
if(!$wBuildingName){
	if($editBuildingCD){
		$wBuildingName = '棟'.numberToCircled(2);
		for ($i = 0; $i < $BuildingLoop; $i++) {
			if($BuildingCD[$i] == $editBuildingCD){
				$wBuildingName = '棟'. numberToCircled($i+2);
			}
		}

	}else{
		if($BuildingLoop > 0){
			$wBuildingName = '棟'.numberToCircled(1);
		}
	}
}
if($BuildingLoop < 1){
	$wBuildingName = '';
}

unset($myBukken);
unset($myBuilding);



########################################################
# 部屋情報　保存
########################################################
$myBukkenMatrix = new BukkenMatrix($myDB);

if($editBuildingCD){
	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD . " AND BuildingCD = " . $editBuildingCD, ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);

}else{
	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD . " AND BuildingCD IS NULL ", ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);

}

$wKaiRoom = $myBukkenMatrix->KaiRoom;
$KaiRoom = SPFWTools::decodePluralValue($wKaiRoom); #配列

unset($myBukkenMatrix);





########################################################
# 作成準備
########################################################

function getExcelAddress($col, $row)
{
	$sinsu = 26;
	$col_val = "";
	while ($col > 0) {
		$intval = (($col - 1) % $sinsu);
		$col_val = chr($intval + 65) . $col_val;
		$col = intval(($col - 1) / $sinsu);
	}
	return $col_val . $row;
}





#工事枠を測る
$wWakuAMPM = $wWakuAMcol + $wWakuPM1col + $wWakuPM2col;
$week = ['日', '月', '火', '水', '木', '金', '土'];

########################################################
# Excelファイル生成
########################################################
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;

$reader = new XlsxReader();
$spreadsheet = $reader->load('./template/kotei_koteihyo.xlsx'); //template.xlsx 読込
#	$sheet = $spreadsheet->getSheetByName('weather'); //weatherシート取得
$sheet = $spreadsheet->getActiveSheet();

$aReceptionDate = date('Y年m月d日', strtotime($ReceptionDate)) . "(" . $week[date('w', strtotime($ReceptionDate))] . ")";
$sheet->setCellValue('B5', "　日程が合わない場合は、" . $aReceptionDate . "までに、下記までに連絡お願いします。");


if ($wWakuPattern > 7) {
	//セルの結合
	$cellAM1 = getExcelAddress(($wWakuAM1col + 4), 8); #列　行　
	$sheet->mergeCells('E8:' . $cellAM1);
	$cellAM2c = getExcelAddress(($wWakuAM1col + 5), 8); #列　行　
	$cellAM2r = getExcelAddress(($wWakuAM1col + 4 + $wWakuAM2col), 8); #列　行
	$sheet->mergeCells($cellAM2c . ':' . $cellAM2r);

	#AM表記変更
	$aAM1 = $WAKUPATTERN[$wWakuPattern]['StartTime'][0];
	$eAM1 = $WAKUPATTERN[$wWakuPattern]['EndTime'][0];
	$DispAM1 = $aAM1 . "～" . $eAM1;
	$sheet->setCellValue("E8", $DispAM1);

	$aAM2 = $WAKUPATTERN[$wWakuPattern]['StartTime'][1];
	$eAM2 = $WAKUPATTERN[$wWakuPattern]['EndTime'][1];
	$DispAM2 = $aAM2 . "～" . $eAM2;
	$sheet->setCellValue($cellAM2c, $DispAM2);

	$aPM1 = $WAKUPATTERN[$wWakuPattern]['StartTime'][2];
	$ePM1 = $WAKUPATTERN[$wWakuPattern]['EndTime'][2];
	$DispPM1 = $aPM1 . "～" . $ePM1;

	$aPM2 = $WAKUPATTERN[$wWakuPattern]['StartTime'][3];
	$ePM2 = $WAKUPATTERN[$wWakuPattern]['EndTime'][3];
	$DispPM2 = $aPM2 . "～" . $ePM2;

	$cellPM1c = getExcelAddress(($wWakuAM1col + $wWakuAM2col + 5), 8); #列　行
	$cellPM1r = getExcelAddress(($wWakuAM1col + $wWakuAM2col + 4 + $wWakuPM1col), 8); #列　行
	$sheet->mergeCells($cellPM1c . ':' . $cellPM1r);
	$sheet->setCellValue($cellPM1c, $DispPM1);

	$cellPM2c = getExcelAddress(($wWakuAM1col + $wWakuAM2col + 5 + $wWakuPM1col), 8); #列　行
	$cellPM2r = getExcelAddress(($wWakuAM1col + $wWakuAM2col + 4 + $wWakuPM1col + $wWakuPM2col), 8); #列　行
	$sheet->mergeCells($cellPM2c . ':' . $cellPM2r);
	$sheet->setCellValue($cellPM2c, $DispPM2);
} else {
	//セルの結合
	$cellAM = getExcelAddress(($wWakuAMcol + 4), 8); #列　行　
	$sheet->mergeCells('E8:' . $cellAM);
	$cellPM1c = getExcelAddress(($wWakuAMcol + 5), 8); #列　行　
	$cellPM1r = getExcelAddress(($wWakuAMcol + 4 + $wWakuPM1col), 8); #列　行

	if($wWakuPM1col>0)$sheet->mergeCells($cellPM1c . ':' . $cellPM1r);

	#AM表記変更
	$aAM = $WAKUPATTERN[$wWakuPattern]['StartTime'][0];
	$eAM = $WAKUPATTERN[$wWakuPattern]['EndTime'][0];
	$DispAM = $aAM . "～" . $eAM;
	$sheet->setCellValue("E8", $DispAM);

	$aPM1 = $WAKUPATTERN[$wWakuPattern]['StartTime'][1];
	$ePM1 = $WAKUPATTERN[$wWakuPattern]['EndTime'][1];
	$DispPM1 = $aPM1 . "～" . $ePM1;
	echo "<br> ".__LINE__." Hensu :".$DispPM1;
	echo "<br> ".__LINE__." Hensu :".$cellPM1r;
	echo "<br> ".__LINE__." Hensu :".$wWakuPM1col;

	if($wWakuPM2col>0){
		$aPM2 = $WAKUPATTERN[$wWakuPattern]['StartTime'][2];
		$ePM2 = $WAKUPATTERN[$wWakuPattern]['EndTime'][2];
		$DispPM2 = $aPM2 . "～" . $ePM2;

		$sheet->setCellValue($cellPM1c, $DispPM1);
		$cellPM2c = getExcelAddress(($wWakuAMcol + 5 + $wWakuPM1col), 8); #列　行
		$cellPM2r = getExcelAddress(($wWakuAMcol + 4 + $wWakuPM1col + $wWakuPM2col), 8); #列　行
		$sheet->mergeCells($cellPM2c . ':' . $cellPM2r);
		$sheet->setCellValue($cellPM2c, $DispPM2);
	} else {
		$DispPM2 = "";
		$sheet->setCellValue($cellPM1c, $DispPM1);
	}
}



$cell_row = 9;
$k = 0;
// 枠線
$sharedStyle1 = new Style();
$sharedStyle1->applyFromArray([
	'borders' => [
		'outline' => ['borderStyle' => Border::BORDER_THICK],
		'bottom' => ['borderStyle' => Border::BORDER_THIN],
		'top' => ['borderStyle' => Border::BORDER_THIN],
		'right' => ['borderStyle' => Border::BORDER_THIN],
		'left' => ['borderStyle' => Border::BORDER_THIN],
	],
	'alignment' => [
		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	],
	'font' => [
		'name' => 'メイリオ',
		'size' => '16',
		'bold' => true,
	],
]);
$sharedStyle2 = new Style(); #すこし小さめ
$sharedStyle2->applyFromArray([
	'borders' => [
		'outline' => ['borderStyle' => Border::BORDER_THICK],
		'bottom' => ['borderStyle' => Border::BORDER_THIN],
		'top' => ['borderStyle' => Border::BORDER_THIN],
		'right' => ['borderStyle' => Border::BORDER_THIN],
		'left' => ['borderStyle' => Border::BORDER_THIN],
	],
	'alignment' => [
		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	],
	'font' => [
		'name' => 'メイリオ',
		'size' => '12',
		'bold' => true,
	],
]);

$cell = getExcelAddress(($wWakuAMPM + 4), (($SenyuDateCnt + $beforeReserveDayDateCnt + $afterReserveDayDateCnt) * $rowCountforDay + 8)); #列　行　D9から
#	$sheet->duplicateStyle($sharedStyle1, ('B8:C8'));
#	$sheet->duplicateStyle($sharedStyle1, ('D8:'.$cell));
$sheet->duplicateStyle($sharedStyle1, ('B8:' . $cell));

$cella = getExcelAddress(($wWakuAMPM + 4), 8); #列　行　D9から
$sheet->duplicateStyle($sharedStyle2, ('E8:' . $cella));

unset($sharedStyle1);
unset($sharedStyle2);

$KoteihyouTb = [];
$HansuInfos = [];
$ViewOrders = [];

// 予備日
for ($i = 0; $i < $beforeReserveDayDateCnt * $rowCountforDay; $i++) {
	$cell_col = 5;
	$y = 0;

	if ($y == 0) {
		for ($j = 0; $j < $wWakuAMPM; $j++) {
			$cell = getExcelAddress($cell_col, $cell_row); #列　行　D9から
			if ($wKoteihyouEX[$k] == "空き") {
				#セルに値をセットする
				$sheet->setCellValue($cell, "");
			// } elseif ($wKoteihyouEX[$k] == "") {
			}else if ($wKoteihyouEX[$k] == "枠越"){
				$sheet->setCellValue($cell, "");
				$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
					->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
					->getStartColor()->setARGB('cccccc');
			}else if($wKoteihyouEX[$k] != ''){
				$sheet->setCellValue($cell, $wKoteihyouEX[$k]);
			}else{
				#セルの色をグレーにする
				$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
					->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
					->getStartColor()->setARGB('cccccc');
			}
			# 右横にずらす
			$cell_col = $cell_col + 1;
			$k++;
		}
	}
	$cell_row = $cell_row + 1;
}
for ($i = 0; $i < $SenyuDateCnt * $rowCountforDay; $i++) { #1 専有部日数　#日付はまわす 枠がいっぱいになったら次の日にいく。
	$cell_col = 5;
	$y = 0;
	$KyujituLoop = is_countable($Kyujitu) ? count($Kyujitu) : 0;
	for ($x = 0; $x < $KyujituLoop; $x++) {
		if ($i == $Kyujitu[$x] * $rowCountforDay) {
			$cell = getExcelAddress($cell_col, $cell_row); #列　行　D9から
			$cell2 = getExcelAddress($wWakuAMPM + 4, ($cell_row + $rowCountforDay) - 1);
			$sheet->mergeCells($cell . ':' . $cell2);
			$sheet->setCellValue($cell, "休 工 日");
			$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('cccccc');
		}
		if ($i >= $Kyujitu[$x] * $rowCountforDay && $i < ($Kyujitu[$x] + 1) * $rowCountforDay)
			$y = 1;
	}


	if ($y == 0) {
		for ($j = 0; $j < $wWakuAMPM; $j++) {
			if ($wFirstDateFeature > 0 && $j < $wWakuAMcol && $i >= 0 && $i < $rowCountforDay) {
				$cell = getExcelAddress($cell_col, $cell_row); #列　行　D9から
				#セルに値をセットする
				$sheet->setCellValue($cell, "");
				#セルの色をグレーにする
				$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
					->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
					->getStartColor()->setARGB('cccccc');
				//				$sheet->duplicateStyle($sharedStyle2, ($cell));

				# 右横にずらす
				$cell_col = $cell_col + 1;
				$k++;
				unset($sharedStyle1);
			} else {
				$cell = getExcelAddress($cell_col, $cell_row); #列　行　D9から
				if ($wKoteihyouEX[$k] == "空き") {
					#セルに値をセットする
					$sheet->setCellValue($cell, "");
				} elseif ($wKoteihyouEX[$k] == "" || $wKoteihyouEX[$k] == "枠越") {
					#セルの色をグレーにする
					$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
						->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
						->getStartColor()->setARGB('cccccc');
				} else {
					#セルに値をセットする
					$sheet->setCellValue($cell, $wKoteihyouEX[$k]);

					//班No
					if(isset($wHansuEX[$k])){
						$HansuInfos[$wKoteihyouEX[$k]] = $wHansuEX[$k];
					}
					$dateIndex = floor($i / $rowCountforDay);

					if ($wWakuPattern > 7) {
						if ($j < $wWakuAM1col) {
							$KoteihyouTa[$i]['AM1'][] = $wKoteihyouEX[$k];
							// 表示順No
							$KoteihyouTb[$dateIndex]['AM1'][] = $wKoteihyouEX[$k];
							$ViewOrders[$wKoteihyouEX[$k]] = count($KoteihyouTb[$dateIndex]['AM1']);
						} elseif ($j >= $wWakuAM1col && $j < ($wWakuAM1col + $wWakuAM2col)) {
							$KoteihyouTa[$i]['AM2'][] = $wKoteihyouEX[$k];
							// 表示順No
							$KoteihyouTb[$dateIndex]['AM2'][] = $wKoteihyouEX[$k];
							$ViewOrders[$wKoteihyouEX[$k]] = count($KoteihyouTb[$dateIndex]['AM2']);
						} elseif ($j >= ($wWakuAM1col + $wWakuAM2col) && $j < ($wWakuAM1col + $wWakuAM2col + $wWakuPM1col)) {
							$KoteihyouTa[$i]['PM1'][] = $wKoteihyouEX[$k];
							// 表示順No
							$KoteihyouTb[$dateIndex]['PM1'][] = $wKoteihyouEX[$k];
							$ViewOrders[$wKoteihyouEX[$k]] = count($KoteihyouTb[$dateIndex]['PM1']);
						} elseif ($j >= ($wWakuAM1col + $wWakuAM2col + $wWakuPM1col) && $j < $wWakuAMPM) {
							$KoteihyouTa[$i]['PM2'][] = $wKoteihyouEX[$k];
							// 表示順No
							$KoteihyouTb[$dateIndex]['PM2'][] = $wKoteihyouEX[$k];
							$ViewOrders[$wKoteihyouEX[$k]] = count($KoteihyouTb[$dateIndex]['PM2']);
						}
					} else {
						if ($j < $wWakuAMcol) {
							$KoteihyouTa[$i]['AM'][] = $wKoteihyouEX[$k];
							// 表示順No
							$KoteihyouTb[$dateIndex]['AM'][] = $wKoteihyouEX[$k];
							$ViewOrders[$wKoteihyouEX[$k]] = count($KoteihyouTb[$dateIndex]['AM']);
						} elseif ($j >= $wWakuAMcol && $j < ($wWakuAMcol + $wWakuPM1col)) {
							$KoteihyouTa[$i]['PM1'][] = $wKoteihyouEX[$k];
							// 表示順No
							$KoteihyouTb[$dateIndex]['PM1'][] = $wKoteihyouEX[$k];
							$ViewOrders[$wKoteihyouEX[$k]] = count($KoteihyouTb[$dateIndex]['PM1']);
						} elseif ($j >= ($wWakuAMcol + $wWakuPM1col) && $j < $wWakuAMPM) {
							$KoteihyouTa[$i]['PM2'][] = $wKoteihyouEX[$k];
							// 表示順No
							$KoteihyouTb[$dateIndex]['PM2'][] = $wKoteihyouEX[$k];
							$ViewOrders[$wKoteihyouEX[$k]] = count($KoteihyouTb[$dateIndex]['PM2']);
						}
					}
				}
				# 右横にずらす
				$cell_col = $cell_col + 1;
				$k++;
			}
		}
	}
	$cell_row = $cell_row + 1;
}
// 予備日
for ($i = 0; $i < $afterReserveDayDateCnt * $rowCountforDay; $i++) {
	$cell_col = 5;
	$y = 0;

	if ($y == 0) {
		for ($j = 0; $j < $wWakuAMPM; $j++) {
			$cell = getExcelAddress($cell_col, $cell_row); #列　行　D9から
			if ($wKoteihyouEX[$k] == "空き") {
				#セルに値をセットする
				$sheet->setCellValue($cell, "");
			// } elseif ($wKoteihyouEX[$k] == "") {
			}else if ($wKoteihyouEX[$k] == "枠越"){
				$sheet->setCellValue($cell, "");
				$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
					->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
					->getStartColor()->setARGB('cccccc');
			}else if($wKoteihyouEX[$k] != ""){
				$sheet->setCellValue($cell, $wKoteihyouEX[$k]);
			}else{
				#セルの色をグレーにする
				$spreadsheet->getSheetByName('Sheet1')->getStyle($cell)->getFill()
					->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
					->getStartColor()->setARGB('cccccc');
			}
			# 右横にずらす
			$cell_col = $cell_col + 1;
			$k++;
		}
	}
	$cell_row = $cell_row + 1;
}


#echo "<br>385行目wKoteihyouTa:";
#print_r($KoteihyouTa);
$CellStart = 9;
$wCellStart = $CellStart;
foreach($beforeReserveDay as $key => $aReserveDay){
	$date = new DateTime($aReserveDay);
	$SenyuDate = $date->format('m月d日');
	$YoubiCD =  $date->format('w');
	$Youbi = $week[$YoubiCD];
	#echo "<br>382行目SenyuDate:".$SenyuDate;
	// $wSenyuDate[] = $date->format('Y-m-d');
	$sheet->mergeCells('B' . $wCellStart . ':B' . ($wCellStart + $rowCountforDay - 1));
	$sheet->setCellValue('B' . $wCellStart, $SenyuDate);

	$sheet->mergeCells('C' . $wCellStart . ':C' . ($wCellStart + $rowCountforDay - 1));
	$sheet->setCellValue('C' . $wCellStart, $Youbi);

	// 班
	$banRowspan = floor($rowCountforDay / $wHansu);
	$wBanCellStart = $wCellStart;
	for($j = 0; $j < $wHansu; $j++){
		$sheet->mergeCells('D' . $wBanCellStart . ':D' . ($wBanCellStart + $banRowspan - 1));
		$sheet->setCellValue('D' . $wBanCellStart, $j+1);
		$wBanCellStart += $banRowspan;
	}	
	$wCellStart += $rowCountforDay;
}
$CellStart = $wCellStart;
$date = new DateTime($SenyuStartDate);
for ($i = 0; $i < $SenyuDateCnt; $i++) { #1 専有部日数　#日付はまわす 枠がいっぱいになったら次の日にいく。
	$SenyuDate = $date->format('m月d日');
	$YoubiCD =  $date->format('w');
	$Youbi = $week[$YoubiCD];
	#echo "<br>382行目SenyuDate:".$SenyuDate;
	$wSenyuDate[] = $date->format('Y-m-d');

	$wCellStart = $CellStart + ($i * $rowCountforDay);
	$sheet->mergeCells('B' . $wCellStart . ':B' . ($wCellStart + $rowCountforDay - 1));
	$sheet->setCellValue('B' . $wCellStart, $SenyuDate);

	$sheet->mergeCells('C' . $wCellStart . ':C' . ($wCellStart + $rowCountforDay - 1));
	$sheet->setCellValue('C' . $wCellStart, $Youbi);

	// 班
	$banRowspan = floor($rowCountforDay / $wHansu);
	$wBanCellStart = $wCellStart;
	for($j = 0; $j < $wHansu; $j++){
		$sheet->mergeCells('D' . $wBanCellStart . ':D' . ($wBanCellStart + $banRowspan - 1));
		$sheet->setCellValue('D' . $wBanCellStart, $j+1);
		$wBanCellStart += $banRowspan;
	}

	$date->modify('+1 days');
}
$wCellStart += $rowCountforDay;

foreach($afterReserveDay as $key => $aReserveDay){
	$date = new DateTime($aReserveDay);
	$SenyuDate = $date->format('m月d日');
	$YoubiCD =  $date->format('w');
	$Youbi = $week[$YoubiCD];
	#echo "<br>382行目SenyuDate:".$SenyuDate;
	// $wSenyuDate[] = $date->format('Y-m-d');

	$sheet->mergeCells('B' . $wCellStart . ':B' . ($wCellStart + $rowCountforDay - 1));
	$sheet->setCellValue('B' . $wCellStart, $SenyuDate);

	$sheet->mergeCells('C' . $wCellStart . ':C' . ($wCellStart + $rowCountforDay - 1));
	$sheet->setCellValue('C' . $wCellStart, $Youbi);

	// 班
	$banRowspan = floor($rowCountforDay / $wHansu);
	$wBanCellStart = $wCellStart;
	for($j = 0; $j < $wHansu; $j++){
		$sheet->mergeCells('D' . $wBanCellStart . ':D' . ($wBanCellStart + $banRowspan - 1));
		$sheet->setCellValue('D' . $wBanCellStart, $j+1);
		$wBanCellStart += $banRowspan;
	}
	if($key < count($afterReserveDay)-1)
		$wCellStart += $rowCountforDay;
}

// $wCellStart += 4;
$wCellStart += $rowCountforDay;


$sharedStyle1 = new Style();
$sharedStyle1->applyFromArray([
	'font' => [
		'name' => 'メイリオ',
		'size' => '14',
		'bold' => true,
	],
]);
$cell = $wCellStart + 6;
$sheet->duplicateStyle($sharedStyle1, ('B' . $wCellStart . ':N' . $cell));
unset($sharedStyle1);

$styleArray = [
	'borders' => [
		'outline' => [
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
		],
	],
];

$sheet->getStyle('B' . $wCellStart . ':L' . $cell)->applyFromArray($styleArray);


$sheet->setCellValue('B' . $wCellStart, "　　<お部屋訪問日時変更のお申込み>　締め切り　" . date('Y年m月d日', strtotime($ReceptionDate)) . "(" . $week[date('w', strtotime($ReceptionDate))] . ")");
$wCellStart++;
$sheet->setCellValue('B' . $wCellStart, "　　　■受付連絡先");
$wCellStart++;
$sheet->setCellValue('B' . $wCellStart, "電話番号:　" . ($ClientTEL !== '' ? $ClientTEL : '※※※※※※※※※※'));
$spreadsheet->getSheetByName('Sheet1')->getStyle('B' . $wCellStart)->getFont()->setSize(18);
$spreadsheet->getSheetByName('Sheet1')->getStyle('B' . $wCellStart)->getFont()->setUnderline(true);
$sheet->mergeCells('B' . $wCellStart . ':L' . $wCellStart);
$spreadsheet->getSheetByName('Sheet1')->getStyle('B' . $wCellStart)
	->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$wCellStart++;
$sheet->setCellValue('F' . $wCellStart, $ReceptionHoursCell);
$wCellStart++;
$sheet->setCellValue('B' . $wCellStart, "　　　■お願い");
$wCellStart++;
$sheet->setCellValue('B' . $wCellStart, "　　　　○電話にてマンション名・号室・お名前をお伝え願います。");
// $wCellStart++;
// $sheet->setCellValue('B' . $wCellStart, "　　　　　委託先オペレーターが対応致します。");

$ROWS = ($SenyuDateCnt + $beforeReserveDayDateCnt + $afterReserveDayDateCnt) * $rowCountforDay + 8;
for ($i = 8; $i <= $ROWS; $i++) {
	$spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(40);
}


########################################################
# 日程情報　保存
########################################################

#登録前に、同一物件は削除　
#同じ物件CDを全部削除してしまう。多棟のときは、別物件でやってもらおう。まちがえたら上書きになる。復活できない
$db_link = mysqli_connect(_HOST_NAME, _USER_NAME, _PASSWD, _MAIN_DB);

#	$sql = " UPDATE tKojiDateF SET MukouFlg = 1, Updated = '".date('Y-m-d H:i:s')."', Updater = '$UserCD' ";
if($editBuildingCD){
	$sql = " update  tReservationF set MukouFlg = 1";
	$sql .= " WHERE BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '" . $editBuildingCD . "' ";
	$result = mysqli_query($db_link, $sql);

	$sql = " update  tReservationInitF set MukouFlg = 1";
	$sql .= " WHERE BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '" . $editBuildingCD . "' ";
	$result = mysqli_query($db_link, $sql);

	$sql = " update tUserM set MukouFlg = 1 ";
	$sql .= " WHERE BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '" . $editBuildingCD . "' ";
	$result = mysqli_query($db_link, $sql);
}else{
	$sql = " update  tReservationF set MukouFlg = 1";
	$sql .= " WHERE BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL ";
	$result = mysqli_query($db_link, $sql);

	$sql = " update  tReservationInitF set MukouFlg = 1";
	$sql .= " WHERE BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL ";
	$result = mysqli_query($db_link, $sql);

	$sql = " update tUserM set MukouFlg = 1 ";
	$sql .= " WHERE BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL ";
	$result = mysqli_query($db_link, $sql);
}



#いつでも新規登録

for ($i = 0; $i < count($wSenyuDate); $i++) {
	#	echo "<br>日付:".date('Y-m-d',strtotime( $wSenyuDate[$i]));

	for ($j = 0; $j < $rowCountforDay; $j++) {
		#echo " ☆:".($i*$rowCountforDay+$j);

		#print_r($KoteihyouTa[$i*$rowCountforDay+$j]['AM']);
		$KoteihyouTa_AM = is_countable($KoteihyouTa[$i * $rowCountforDay + $j]['AM']) ? count($KoteihyouTa[$i * $rowCountforDay + $j]['AM']) : 0;
		for ($k = 0; $k < $KoteihyouTa_AM; $k++) {
			#echo " AM ".$KoteihyouTa[$i*$rowCountforDay+$j]['AM'][$k];
			$KojiDate['RoomID'][] = $KoteihyouTa[$i * $rowCountforDay + $j]['AM'][$k]; #
			$KojiDate['RoomDate'][] = $wSenyuDate[$i] . " " . $aAM; #日付+AMの開始時刻　196行付近に定義あり
		}
		$KoteihyouTa_AM1 = is_countable($KoteihyouTa[$i * $rowCountforDay + $j]['AM1']) ? count($KoteihyouTa[$i * $rowCountforDay + $j]['AM1']) : 0;
		for ($k = 0; $k < $KoteihyouTa_AM1; $k++) {
			#echo " AM ".$KoteihyouTa[$i*$rowCountforDay+$j]['AM'][$k];
			$KojiDate['RoomID'][] = $KoteihyouTa[$i * $rowCountforDay + $j]['AM1'][$k]; #
			$KojiDate['RoomDate'][] = $wSenyuDate[$i] . " " . $aAM; #日付+AMの開始時刻　196行付近に定義あり
		}
		$KoteihyouTa_AM2 = is_countable($KoteihyouTa[$i * $rowCountforDay + $j]['AM2']) ? count($KoteihyouTa[$i * $rowCountforDay + $j]['AM2']) : 0;
		for ($k = 0; $k < $KoteihyouTa_AM2; $k++) {
			#echo " AM ".$KoteihyouTa[$i*$rowCountforDay+$j]['AM'][$k];
			$KojiDate['RoomID'][] = $KoteihyouTa[$i * $rowCountforDay + $j]['AM2'][$k]; #
			$KojiDate['RoomDate'][] = $wSenyuDate[$i] . " " . $aAM; #日付+AMの開始時刻　196行付近に定義あり
		}
		$KoteihyouTa_PM1 = is_countable($KoteihyouTa[$i * $rowCountforDay + $j]['PM1']) ? count($KoteihyouTa[$i * $rowCountforDay + $j]['PM1']) : 0;
		for ($k = 0; $k < $KoteihyouTa_PM1; $k++) {
			#echo " PM1 ".$KoteihyouTa[$i*$rowCountforDay+$j]['PM1'][$k];
			$KojiDate['RoomID'][] = $KoteihyouTa[$i * $rowCountforDay + $j]['PM1'][$k]; #
			$KojiDate['RoomDate'][] = $wSenyuDate[$i] . " " . $aPM1; #日付+PM1の開始時刻　196行付近に定義あり

		}

		$KoteihyouTa_PM2 = is_countable($KoteihyouTa[$i * $rowCountforDay + $j]['PM2']) ? count($KoteihyouTa[$i * $rowCountforDay + $j]['PM2']) : 0;
		for ($k = 0; $k < $KoteihyouTa_PM2; $k++) {
			#echo " PM2 ".$KoteihyouTa[$i*$rowCountforDay+$j]['PM2'][$k];
			$KojiDate['RoomID'][] = $KoteihyouTa[$i * $rowCountforDay + $j]['PM2'][$k]; #
			$KojiDate['RoomDate'][] = $wSenyuDate[$i] . " " . $aPM2; #日付+PM2の開始時刻　196行付近に定義あり

		}
	}
}

########################################################
# 日程情報保存 tReservationF　へ保存 ★★★
########################################################
$No = 1;
$k = array();  // このように初期化しておく
$l = array();  // このように初期化しておく
$wConstTime = $wMinuteTime;#20分

for ($j = 0; $j < count($wWakuSu); ++$j) {
	$starttime = strtotime($WAKUPATTERN[$wWakuPattern]['StartTime'][$j]);
	$endtime   = strtotime($WAKUPATTERN[$wWakuPattern]['EndTime'][$j]);
	$minutes = ($endtime - $starttime) / 60;
	$wConstTime = intval($wConstTime);
	$countForTimeZoneOfDate = ceil($minutes / $wConstTime);
	$countMax = intval($wWakuSu[$j]);
	$countRepeat = ceil($countMax / $countForTimeZoneOfDate);
	if($countRepeat < 1)
		$countRepeat = 1;
	$arrCountRepeat[$j] = $countRepeat;
}
$Passwd = date('ym').rand(1, 100);# 202408

for ($i = 0; $i < count($KojiDate['RoomID']); $i++) {
		$No = $i + 1;
	$i = $No - 1;

	#$wUserCD[$i] = SPFWParameter::getValues('wUserCD' . $No);
	$wID[$i] = $KojiDate['RoomID'][$i];
	
// 	// //既に登録されている部屋番号の時は登録しない →いったん無効に。
// 	// if (in_array($wUserCD[$i], $UserCD489)) {
// 	// 	$No++;
// 	// 	continue;
// 	// }


// 	// $wTimeFromDate[$i] = SPFWParameter::getValues('wTimeFromDate' . $No);
// 	// $wTimeFromTime[$i] = SPFWParameter::getValues('wTimeFromTime' . $No);
	#$KojiDate['RoomDate'][$i];
	$twTimeFromDate[$i] = explode(" ",$KojiDate['RoomDate'][$i]);
	$wTimeFromDate[$i] = $twTimeFromDate[$i][0];#2024-08-21 
	$wTimeFromTime[$i] = $twTimeFromDate[$i][1]; #09:00






	//$Passwd = substr(str_shuffle('123456789'), 0, 4);
	$RegistKey = substr(str_shuffle('1234567890ABCdefghijklmnopqrstuvwxyz'), 0, 12);
	$IdentifyKey = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 12);

	$myUser = new User($myDB);

	$myUser->ClientCD = $TargetClientCD;
	$myUser->BukkenCD = $editBukkenCD;
	if($editBuildingCD){
		$myUser->BuildingCD = $editBuildingCD;
	// }else{
	// 	$myUser->BuildingCD = null;
	}
	$myUser->UserCD = "-1";#$wUserCD[$i];
	$myUser->ID = $wID[$i];
	$myUser->Passwd = $Passwd;
	$myUser->UserKbn = "4";#ユーザ区分　4:入居者
	#$myUser->MenuCD = '|1|';#'|' . $MenuCD . '|'; いったん　雑排は　MenuCD＝１の固定としてみる。
	$myUser->RegistKey = $RegistKey;
	$myUser->IdentifyKey = $IdentifyKey;

	// if (!empty(SPFWParameter::getValues('ToName' . $No))) {#多棟はあとでかんがえるか　tTatoMがないと区別できるかなー
	// 	$ToName[$i] = SPFWParameter::getValues('ToName' . $No);
	// 	$myUser->Address3 = $ToName[$i];
	// }
	$myUser->Creator = $MyUserCD;
	$myUser->Updater = $MyUserCD;
	if (!$myUser->executeUpdate()) { //引数もたせるとInsertはやめる。
		$ErrorString = [];
		$ErrorString[] = 'ユーザ登録時にエラーがおこりました。';
		showAdminSorryPage($ErrorString);
	}
	// $myLog->debug("Insert User : ".$myUser->ID);
	$tUserCD  = $myUser->UserCD; #登録されたUserCD
	$ReservationCD = '-1';
/*
	$tempTimeFromDate = $wTimeFromDate[$i]; //2018-08-14部分
	$tempTimeFromTime = $wTimeFromTime[$i]; //09:00 部分
	if (!isset($k[$tempTimeFromDate][$tempTimeFromTime])) {
		$k[$tempTimeFromDate][$tempTimeFromTime] = 0;
	}
	if (!isset($l[$tempTimeFromDate][$tempTimeFromTime])) {
		$l[$tempTimeFromDate][$tempTimeFromTime] = 0;
	}

	for ($j = 0; $j < count($wWakuSu); ++$j) {
		if ($WAKUPATTERN[$wWakuPattern]['StartTime'][$j] == $tempTimeFromTime) {


			// echo "<br> ".__LINE__." Hensu :" ;
			// echo "<br>  Hensu :".$tempTimeFromDate . $tempTimeFromTime ;
			// echo "<br>  wConstTime :".$wConstTime ;
			// echo "<br>  k :".$k[$tempTimeFromDate][$tempTimeFromTime];
			$exTimeFrom = strtotime($tempTimeFromDate . $tempTimeFromTime) + ($wConstTime * 60 * $k[$tempTimeFromDate][$tempTimeFromTime]);

			$wTimeFrom = date('Y-m-d H:i', strtotime('+' . ($wConstTime * $k[$tempTimeFromDate][$tempTimeFromTime]) . 'minute', strtotime($tempTimeFromDate . ' ' . $tempTimeFromTime)));

			$wTimeTo = date('Y-m-d H:i', strtotime($wTimeFrom) + ($wConstTime * 60));


			++$l[$tempTimeFromDate][$tempTimeFromTime];
			//echo "<br>".__LINE__."行目：".$wTimeFrom."～".$wTimeTo."EndTime:".$WAKUPATTERN[$wWakuPattern]['EndTime'][$j];

			if ($l[$tempTimeFromDate][$tempTimeFromTime] == $rowCountforDay) {#班数にたっしたら　＄ｌを初期化
				++$k[$tempTimeFromDate][$tempTimeFromTime];
				$l[$tempTimeFromDate][$tempTimeFromTime] = 0;
			}
		}
	}

*/
	if ($wTimeFromDate[$i] && $wTimeFromDate[$i] != '0000-00-00') {
		for ($j = 0; $j < count($wWakuSu); ++$j) {
			if ($WAKUPATTERN[$wWakuPattern]['StartTime'][$j] == $wTimeFromTime[$i]) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
				$WakuStart = $WAKUPATTERN[$wWakuPattern]['StartTime'][$j];
				if(!isset($NextBlankTime[$wTimeFromDate[$i]][$WakuName])){
					$NextBlankTime[$wTimeFromDate[$i]][$WakuName] = date('H:i', strtotime($WakuStart));
					$NextBlankCount[$wTimeFromDate[$i]][$WakuName] = 0;
				}

				$NextBlankTime[$wTimeFromDate[$i]][$WakuName] = date('H:i', strtotime("+".(floor($NextBlankCount[$wTimeFromDate[$i]][$WakuName] / $arrCountRepeat[$j]) * $wConstTime)." minutes", strtotime($WakuStart)));

				$wTimeFrom = date('Y-m-d H:i', strtotime($wTimeFromDate[$i] . ' ' . $NextBlankTime[$wTimeFromDate[$i]][$WakuName]));
				$wTimeTo = date('Y-m-d H:i', strtotime($wTimeFrom) + ($wConstTime * 60));
				$NextBlankCount[$wTimeFromDate[$i]][$WakuName] ++;
			}
		}

		$myReservation = new Reservation($myDB);

		$myReservation->ReservationCD = $ReservationCD;
		$myReservation->ClientCD = $TargetClientCD;
		$myReservation->StylistCD = "1"; #$shokiStylistCD; まずは、３Lineをもつ班を指定した
		$myReservation->UserCD = $tUserCD;#　50行ほど前に登録したUserCD
		$myReservation->BukkenCD = $editBukkenCD;
		if($editBuildingCD){
			$myReservation->BuildingCD = $editBuildingCD;
		// }else{
		// 	$myReservation->BuildingCD = null;
		}
		$myReservation->ID = $wID[$i];#部屋番号
		if(isset($HansuInfos[$wID[$i]])){
			$myReservation->HanNo = $HansuInfos[$wID[$i]];
		}
		if(isset($ViewOrders[$wID[$i]])){
			$myReservation->ViewOrderNo = $ViewOrders[$wID[$i]];
		}
		$myReservation->TimeFrom = $wTimeFrom;
		$myReservation->TimeTo = $wTimeTo;
		$myReservation->MenuCD = "|1|"; #　'|' . $MenuCD . '|';　まずは、20分作業のMenuを固定にした
		$myReservation->Status = '1';
		$myReservation->Creator = $MyUserCD;
		$myReservation->Updater = $MyUserCD;

		if (!$myReservation->executeUpdate()) { //★引数もたせるとInsert
			$ErrorString = [];
			$ErrorString[] = '予約情報登録時にエラーがおこりました。';
			showAdminSorryPage($ErrorString);
		}
		// $myLog->debug("Insert Reservation : ".$myReservation->ID);


		$myReservationInit = new ReservationInit($myDB);

		$myReservationInit->ReservationCD = $ReservationCD;
		$myReservationInit->ClientCD = $TargetClientCD;
		$myReservationInit->StylistCD = "1"; #$shokiStylistCD; まずは、３Lineをもつ班を指定した
		$myReservationInit->UserCD = $tUserCD;#　50行ほど前に登録したUserCD
		$myReservationInit->BukkenCD = $editBukkenCD;
		if($editBuildingCD){
			$myReservationInit->BuildingCD = $editBuildingCD;
		// }else{
		// 	$myReservationInit->BuildingCD = null;
		}
		$myReservationInit->ID = $wID[$i];#部屋番号
		if(isset($HansuInfos[$wID[$i]])){
			$myReservationInit->HanNo = $HansuInfos[$wID[$i]];
		}
		if(isset($ViewOrders[$wID[$i]])){
			$myReservationInit->ViewOrderNo = $ViewOrders[$wID[$i]];
		}
		$myReservationInit->TimeFrom = $wTimeFrom;
		$myReservationInit->TimeTo = $wTimeTo;
		$myReservationInit->MenuCD = "|1|"; #　'|' . $MenuCD . '|';　まずは、20分作業のMenuを固定にした
		$myReservationInit->Status = '1';
		$myReservationInit->Creator = $MyUserCD;
		$myReservationInit->Updater = $MyUserCD;

		if (!$myReservationInit->executeUpdate()) { //★引数もたせるとInsert
			$ErrorString = [];
			$ErrorString[] = '予約情報登録時にエラーがおこりました。';
			showAdminSorryPage($ErrorString);
		}		
		// $myLog->debug("Insert reservationInit : ".$myReservationInit->ID);
	}
	$No++;
} //ForのEnd
######################################################################################
















// ###★ Excel(.xlsx)としてtFileFに登録する
// $writer = new XlsxWriter($spreadsheet);
// $writer->save('./tmp/s' . date('Ymdhis') . '.xlsx');

// // 画像の取得
// #		$image_path = $dir.$image_name;
// $image_path = './tmp/s' . date('Ymdhis') . '.xlsx';
// $img_file = file_get_contents($image_path);

// //画像を保存するSQL文の実行
// $myFile = new File($myDB);

// $myFile->FileCD = -1;
// $myFile->BukkenCD = $editBukkenCD;
// $myFile->SekoStatus = 5;                     #5作成ファイル
// $myFile->P001 = '詳細工程表' . date('Ymdihs') . '.xlsx';             #ファイル名
// $myFile->P002 = 'xlsx';  #拡張子

// $myFile->File = $img_file;
// #		$myFile->Memo = $Memo;
// $myFile->Creator = $wUserCD;
// $myFile->Updater = $wUserCD;

// if (!$myFile->executeUpdate()) {
// 	$ErrorString = array();
// 	$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
// 	showAdminSorryPage($ErrorString);
// }
// #テンポラリーファイルを削除する。
// $Command = "rm -f " . $image_path;
// shell_exec($Command);
// ###★ Excel(.xlsx)としてtFileFに登録する End




// 一時保存されたデータを削除します。
$myReservationTemp = new ReservationTemp($myDB);
if($editBuildingCD){
	if (!$myReservationTemp->executeSelect("  BukkenCD = '".$editBukkenCD."' AND BuildingCD = '".$editBuildingCD."'", "")) 
		trigger_error("Getting Temp Reservation Failed.", E_USER_ERROR);
}else{
	if (!$myReservationTemp->executeSelect("  BukkenCD = '".$editBukkenCD."' AND BuildingCD IS NULL", "")) 
		trigger_error("Getting Temp Reservation Failed.", E_USER_ERROR);
}
if($myReservationTemp->RecCnt > 0)
	$myReservationTemp->executeDelete();



//ダウンロード用
//MIMEタイプ：https://technet.microsoft.com/ja-jp/ee309278.aspx
header("Content-Description: File Transfer");

if($wBuildingName != ""){
	$wFileName = str_replace(",", "", $BukkenName) . "_" . str_replace(",", "", $wBuildingName) . "_詳細工程表.xlsx";
}else{
	$wFileName = str_replace(",", "", $BukkenName) . "_詳細工程表.xlsx";
}

#	$wFileName = mb_convert_encoding($wFileName, "SJIS", "UTF-8"); // 20181217 IEでの文字化け対応

#	header("Content-Disposition: attachment; filename=".$wFileName );

$ieFileName = mb_convert_encoding($wFileName, 'SJIS-win', 'UTF-8');
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/MSIE|Trident/', $userAgent)) {
    // IE の場合
    header('Content-Disposition: attachment; filename="' . $ieFileName . '"');
} else {
    // その他のモダンブラウザ（UTF-8 + RFC5987対応）
    $encodedFilename = rawurlencode($wFileName);
    header("Content-Disposition: attachment; filename*=UTF-8''" . $encodedFilename);
}

setcookie('downloadComplete', '1', time() + 60, '/');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
ob_end_clean(); //バッファ消去

$writer = new XlsxWriter($spreadsheet);
$writer->save('php://output');




// echo "<br> ".__LINE__." ここまでOK :";
?>