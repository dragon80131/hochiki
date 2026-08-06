<?php
$isAdminMode = TRUE;
include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";
include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";
include_once _CLS_DIR . "SPUSBukkenMatrix.cls";

include_once "./include/common_489.php";
include_once "./include/building_period_helpers.php";
include_once "./include/holiday_helpers.php";


$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
$menu = SPFWParameter::getValues('menu'); // If this page is navigated using menu click

########################################################
# 物件情報取得（編集）
########################################################

$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}

// 棟一覧
function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}

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

// 基本棟のデータが存在しない場合、データが存在している棟を選択します。
if($menu == '1'){
	$wWakuPattern = $myBukken->WakuPattern;
	if($wWakuPattern == ""){
		for ($i = 0; $i < $BuildingLoop; $i++) {
			$BuildingCD[$i] = $myListObject->GetValue($i, 0);
			$tempBuilding = new Building($myDB);
			if($BuildingCD[$i]){
				if ($tempBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$BuildingCD[$i]." AND MukouFlg = FALSE", "") || $tempBuilding->RecCnt != 1) {
					if($tempBuilding && $tempBuilding->WakuPattern){
						$editBuildingCD = $BuildingCD[$i];
						break;
					}
				}
			}

		}
	}
}


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

$date = date("YmdHis");

########################################################
# 認証動作
########################################################
$myUser = new User($myDB);
if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS2);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS2);

$UserCD 		= $myUser->UserCD;
$wClientCD 		= $myUser->ClientCD; #幹事企業CD
$EigyosyoCD 		= $myUser->EigyosyoCD; #幹事企業支店・営業所CD
$UserKbn 		= $myUser->UserKbn; #1:幹事企業一般 2:管理者 3:協力業者CD
$GyosyaCD	= $myUser->GyosyaCD; #協力業者CD

unset($myUser);


$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "") || $myBuilding->RecCnt != 1) {
		trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
	}
}


// $wKenmeiNo 				= $myBukken->KenmeiNo;
$wBukkenName 			= $myBukken->BukkenName;
$wBuildingName = $myBukken->BuildingName;
if(!$wBuildingName){
	if($BuildingLoop > 0){
		$wBuildingName = '棟'.numberToCircled(1);
	}
}
if($BuildingLoop > 0)
	$IfBuildingExist = true;
else
	$IfBuildingExist = false;

// $wBukkenName_Hurigana	= $myBukken->BukkenName_Hurigana;
$Created 				= $myBukken->Created;#作成日

$resolvedPeriod = resolveBuildingSenyuAndYoyaku($myBukken, $editBuildingCD ? $myBuilding : null, $editBuildingCD);
$SenyuStartDate = $resolvedPeriod['SenyuStartDate'];
$SenyuEndDate = $resolvedPeriod['SenyuEndDate'];
$YoyakuEndDate = $resolvedPeriod['YoyakuEndDate'];


$SenyuDateCnt = (( strtotime( $SenyuEndDate ) -  strtotime( $SenyuStartDate )) / 86400) + 1 ;#専有部日数

$Holiday1 = $myBukken->Holiday1;
$ReserveDay = $myBukken->ReserveDay;
$wFirstDateFeature = $myBukken->FirstDateFeature;
$wHansu = $myBukken->Hansu;
$wWakuPattern = $myBukken->WakuPattern;
$MaxWakuSu = $myBukken->MaxWakuSu;
$wKojijun = $myBukken->Kojijun;
$wFrameOverflow = $myBukken->FrameOverflow;

$wKaidaka = $myBukken->Kaidaka;
$wArrangeType = $myBukken->ArrangeType;
$wFloorReserveInfo = $myBukken->FloorReserveInfo;

if($editBuildingCD){
	$Holiday1 = $myBuilding->Holiday1;
	$ReserveDay = $myBuilding->ReserveDay;
	$wFirstDateFeature = $myBuilding->FirstDateFeature;
	$wHansu = $myBuilding->Hansu;
	$wWakuPattern = $myBuilding->WakuPattern;
	$MaxWakuSu = $myBuilding->MaxWakuSu;
	$wKojijun = $myBuilding->Kojijun;
	$wFrameOverflow = $myBuilding->FrameOverflow;

	$wKaidaka = $myBuilding->Kaidaka;
	$wArrangeType = $myBuilding->ArrangeType;
	$wFloorReserveInfo = $myBuilding->FloorReserveInfo;
}
$wFloorReserveInfo = html_entity_decode($wFloorReserveInfo, ENT_QUOTES, 'UTF-8');

// 【2026/06 Option B】点検物件も工事と同様に「容量（バンド）のみ」で空き/枠越を判定し、
// フロア紐付け（FloorReserveInfo）による枠越判定は行わない。元の挙動に戻す場合は true にする。
if(!defined('RESERVE_USE_FLOOR_BINDING')) define('RESERVE_USE_FLOOR_BINDING', false);


$wHolidayItems = parseHoliday1($Holiday1);
$wHoliday = array();
foreach ($wHolidayItems as $hi) {
	$wHoliday[] = $hi['date'];
}
sort($wHoliday);

$wReserveDay = SPFWTools::decodePluralValue($ReserveDay);
sort($wReserveDay);

$beforeReserveDay = [];
$afterReserveDay = [];
if($wArrangeType != '1'){
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
$MaxWaku = explode("-",$MaxWakuSu);
$wWakuAM = $MaxWaku[0];
$wWakuPM = $MaxWaku[1];
$wWakuPM1 = $MaxWaku[1];
if(count($MaxWaku)>2){
	$wWakuPM2 = $MaxWaku[2];
}

unset($myBukken);
########################################################
# 資料掲載情報表示
########################################################
######################################################
# QRコード生成
#####################################################
$folderPath = './upfile/'.date('Y',strtotime($Created) );
// フォルダが存在するかチェック
if (!is_dir($folderPath)) {
    // フォルダが存在しない場合、作成する
    if (mkdir($folderPath, 0777, true)) {
        // echo "フォルダ '$folderPath' が作成されました。";
    }
}
$QRCD = $folderPath."/".$editBukkenCD.'qrcode.png';
if($editBuildingCD){
	$QRCD = $folderPath."/".$editBukkenCD.'-'.$editBuildingCD.'qrcode.png';
}
// QRコードを生成するデータ
$loginUrl = _ROOT_URL . 'login.php';
if($editBuildingCD){
	$URLdata = $loginUrl . '?editBukkenCD=' . $editBukkenCD . '&editBuildingCD=' . $editBuildingCD;
	$URL = $loginUrl . '?editBuildingCD=' . $editBuildingCD;
}else{
	$URLdata = $loginUrl . '?editBukkenCD=' . $editBukkenCD;
	$URL = $loginUrl;
}
if (!file_exists($QRCD)) {
	include 'phpqrcode/qrlib.php';
	$file = $QRCD;
	QRcode::png($URLdata, $file, QR_ECLEVEL_L, 10);
	// echo 'QRコードが生成されました: ' . $file;
}
########################################################
# 物件初期ユーザパスワード
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "Passwd ";
$myListObject->SelectSQL = $sql;
$sql = " FROM tUserM";
$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
if($editBuildingCD){
	$sql .= " AND BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND BuildingCD IS NULL ";
}

$myListObject->Condition	= $sql;
$myListObject->Order 		= "UserCD";
$myListObject->Limit 		= "1";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);
$wPasswd = $myListObject->GetValue(0, 0);#一番初めのユーザのパスワードをセット

########################################################
# 詳細工程表表示
########################################################

#Koteihyoに、空きか部屋番号をいれていく。
########################################################
# 日程情報取得
########################################################
// SELECT 
//    DATE(TimeFrom) AS Date,
//     CASE 
//         WHEN TIME(TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'
//         WHEN TIME(TimeFrom) BETWEEN '13:00:00' AND '15:00:00' THEN 'PM1'
//         WHEN TIME(TimeFrom) BETWEEN '15:00:01' AND '18:00:00' THEN 'PM2'
//         ELSE 'Other'
//     END AS TimePeriod
// FROM tReservationF
// where BukkenCD = 217


$myBukkenMatrix = new BukkenMatrix($myDB);

if($editBuildingCD){
	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD . " AND BuildingCD = " .$editBuildingCD, ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);
}else{
	if (!$myBukkenMatrix->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD . " AND BuildingCD IS NULL", ""))
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);
}



$myListObject = new SPFWListObject($myDB);
$sql  = "SELECT ";
$sql .= "r.ReservationCD, "; #0
$sql .= "DATE(r.TimeFrom) AS Date, "; #1
if($wWakuPattern == '0' || $wWakuPattern == '1' || $wWakuPattern == '2'){ // 2枠
	$sql .= "CASE ";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '13:00:00' AND '18:00:00' THEN 'PM'";
	$sql .= " ELSE 'Other'";
	$sql .= " END AS AMPM ,"; #2
}else{ // 3枠
	$sql .= "CASE ";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '09:00:00' AND '12:00:00' THEN 'AM'";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '13:00:00' AND '14:59:00' THEN 'PM1'";
	$sql .= " WHEN TIME(r.TimeFrom) BETWEEN '15:00:00' AND '18:00:00' THEN 'PM2'";
	$sql .= " ELSE 'Other'";
	$sql .= " END AS AMPM ,"; #2
}
$sql .= "r.ID, "; #3
$sql .= "r.UserCD, "; #4
$sql .= "r.TimeFrom, "; #5
$sql .= "r.TimeTo, "; #6
$sql .= "r.HanNo, "; #7
$sql .= "r.ViewOrderNo "; #8

$myListObject->SelectSQL = $sql;
$sql  = " FROM tReservationInitF r, tUserM u ";
$sql .= " WHERE r.Status = 1 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
$sql .= " AND r.BukkenCD = " . $editBukkenCD;
if($editBuildingCD){
	$sql .= " AND r.BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND r.BuildingCD IS NULL ";
}

// $sql .= " AND ClientCD = " . $wClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "Date, AMPM, r.HanNo, r.TimeFrom, r.Updated, r.ReservationCD";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

$ReservationLoop = $myListObject->Rows;
$arrHanNo = [];
$arrViewOrderNo = [];

for ($i = 0; $i < $ReservationLoop; $i++) {
	$ReservationCD[$i] = $myListObject->GetValue($i, 0);
	$tDate = $myListObject->GetValue($i, 1);
	$AMPM = $myListObject->GetValue($i, 2);
	$ID[$i] = $myListObject->GetValue($i, 3);
	$Reserve[$tDate][$AMPM][] = $ID[$i];

	$UserCD[$i] = $myListObject->GetValue($i, 4);

	$arrHanNo[$ID[$i]] 	= $myListObject->GetValue($i, 7);
	$arrViewOrderNo[$ID[$i]] 	= $myListObject->GetValue($i, 8);
}

if($wWakuPattern == "" || !$ReservationLoop){
	echo ('<script>
if(confirm("作業日程登録がまだ終わっていないようです。\r\nブラウザで戻り、作業日程登録の各項目の入力をお願いします。\r\n作業日程登録ページへ移動しますか？")){
	location.href="./doc/s_make_kanryo2.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'&editBuildingCD='.$editBuildingCD.'";
}else{
	location.href="./s_menu.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
}
</script>');
}

######################################################################################

$wFrameOverflow = intval($wFrameOverflow);
// if($wArrangeType == '1'){
// 	$wFrameOverflow = 0;
// }

$wHansu = intval($wHansu);
$rowCountforDay = $wHansu;

for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
	$tempRowCountforDay = ceil((${'wWaku' . $WakuName}+$wFrameOverflow * $wHansu) / 5);
	if($tempRowCountforDay > $rowCountforDay)
		$rowCountforDay = $tempRowCountforDay;

	${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $wHansu)+$wFrameOverflow;
	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};

	${'wWaku' . $WakuName . 'col'} = ceil(${'wWaku' . $WakuName} / $wHansu)+$wFrameOverflow;
	if($WakuName == 'PM')$wWakuPM1col = ${'wWaku' . $WakuName . 'Col'} ;
}

$rowCountforDay = ceil($rowCountforDay / $wHansu) * $wHansu;

for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
	$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];

	$tempRowCountforDay = ceil((${'wWaku' . $WakuName}+$wFrameOverflow * $wHansu) / 5);
	if($tempRowCountforDay > $wHansu){
		${'wWaku' . $WakuName . 'Col'} = 5;
	}else{
		${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $rowCountforDay)+$wFrameOverflow;
	}

	${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $rowCountforDay;
	$wWakuColSum += ${'wWaku' . $WakuName . 'Col'};

	${'wWaku' . $WakuName . 'col'} = ceil(${'wWaku' . $WakuName} / $rowCountforDay)+$wFrameOverflow;
	if($WakuName == 'PM')$wWakuPM1col = ${'wWaku' . $WakuName . 'Col'} ;
}

$EmptyFrameCount = 0;
########################################################
# 初日・土日祝考慮
########################################################

$wWakuSum1 = 0;
$wWakuSum2 = 0;

$week = ['日', '月', '火', '水', '木', '金', '土'];

foreach($beforeReserveDay as $key => $aReserveDay){
	$date = new DateTime($aReserveDay);
	$aSyoniti = false;
	$beforeKojiHoliday[$key] = false;
	$beforeHoliday[$key] = false;

	$SenyuDate = $date->format('Y-m-d');
	$result = array_search($SenyuDate, $SHUKUJITULIST);
	$YoubiCD =  $date->format('w');
	if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
		$beforeHoliday[$key] = true;
	}
	if (count($wHolidayItems) > 0) { //休工日
		$beforeKojiHoliday[$key] = isFullDayHoliday($wHolidayItems, $SenyuDate);
	}
	for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
		$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
		$SyonitiKouryo = false;
		if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $aSyoniti) {
			$SyonitiKouryo = true;
		}else if($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $aSyoniti){
			$SyonitiKouryo = true;
		}
#★★★ここから
		$ban_rooms = 1;
		$Overflows = 0;
		$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
		$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
		$passed_rooms = 0;
		$x = 0;

		for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
			$dis_ban = floor($k / $limit_ban) + 1;
			if (!$beforeKojiHoliday[$key]) { //休工日以外
				if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
					${'Waku' . $WakuName . 'Room'}[] = "";
				} else if (isSlotHoliday($wHolidayItems, $SenyuDate, $WakuName)) {
					${'Waku' . $WakuName . 'Room'}[] = "休工";
				} else if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
					if($Overflows < $wFrameOverflow){
						if(isset($Reserve[$SenyuDate][$WakuName][$x]) 
							&& (!$arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] || $arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] == $dis_ban)){
							${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
							$passed_rooms ++;
							$x ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_WAKUOVER;

						}
						$EmptyFrameCount ++;
						$Overflows ++;
					}else{
						${'Waku' . $WakuName . 'Room'}[] = "";
					}
				}elseif(isset($Reserve[$SenyuDate][$WakuName][$x]) 
					&& (!$arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] || $arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] == $dis_ban)){
					if($wArrangeType == '1' && RESERVE_USE_FLOOR_BINDING){
						$bReservedRooms = 0;
						foreach($arrFloorReserveInfo as $floor => $FloorReserveInfo){
							if(date("Y-m-d", strtotime($FloorReserveInfo["wFloorDay"])) == date("Y-m-d", strtotime($SenyuDate)) && $FloorReserveInfo["wFloorWaku"] == $WakuName){
								$bReservedRooms += intval($FloorReserveInfo["wFloorCols"]);
							}
						}
						if(($dis_ban - 1) * $max_ban + $ban_rooms > $bReservedRooms){
							${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_AKI;
							$passed_rooms ++;
							$EmptyFrameCount ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
							$passed_rooms ++;
							$x ++;
						}
					}else{
						${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
					}
					$passed_rooms ++;
					$x ++;
				}elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
					${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_AKI;

					$passed_rooms ++;
					$EmptyFrameCount ++;
				}else{
					if($Overflows < $wFrameOverflow){
						${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_WAKUOVER;

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
	if (count($wHolidayItems) > 0) { //休工日
		$KojiHoliday[$i] = isFullDayHoliday($wHolidayItems, $SenyuDate);
	}
	for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
		$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
		//空きを考慮した枠数を取得
		// ${'Waku' . $WakuName . 'Su'} = getWakuRoomSu($WakuName, $Syoniti[$i], $holiday[$i], ${'wWaku' . $WakuName}, $wFirstDateFeature);
		//初日考慮
		$SyonitiKouryo = false;
		if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $Syoniti[$i]) {
			$SyonitiKouryo = true;
		}else if($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $Syoniti[$i]){
			$SyonitiKouryo = true;
		}
#★★★ここから
		$ban_rooms = 1;
		$Overflows = 0;
		$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
		$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
		$passed_rooms = 0;
		$x = 0;

		for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
			$dis_ban = floor($k / $limit_ban) + 1;
			if (!$KojiHoliday[$i]) { //休工日以外
				if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
					${'Waku' . $WakuName . 'Room'}[] = "";
				} else if (isSlotHoliday($wHolidayItems, $SenyuDate, $WakuName)) {
					${'Waku' . $WakuName . 'Room'}[] = "休工";
				} else if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
					if($Overflows < $wFrameOverflow){
						if(isset($Reserve[$SenyuDate][$WakuName][$x]) 
							&& (!$arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] || $arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] == $dis_ban)){
							${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
							$passed_rooms ++;
							$x ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_WAKUOVER;
						}
						$EmptyFrameCount ++;
						$Overflows ++;
					}else{
						${'Waku' . $WakuName . 'Room'}[] = "";
					}
				}elseif(isset($Reserve[$SenyuDate][$WakuName][$x]) 
					&& (!$arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] || $arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] == $dis_ban)){
					if($wArrangeType == '1'){
						// 仮日程の場合は、チェックを行わずに表示します。
						${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
						$passed_rooms ++;
						$x ++;
					}else{
						${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
						$passed_rooms ++;
						$x ++;
					}
				}elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
					${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_AKI;

					$passed_rooms ++;
					$EmptyFrameCount ++;
				}else{
					if($Overflows < $wFrameOverflow){
						${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_WAKUOVER;

						$Overflows ++;
						$EmptyFrameCount ++;
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
			// if (!$KojiHoliday[$i]) { //休工日以外
				// if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
				// 	${'Waku' . $WakuName . 'Room'}[] = "";
				// } elseif (${'Waku' . $WakuName . 'Su'} > $k && isset($KaiRoom3[$x])) { //空きを考慮した枠数分　部屋を入れる
				// 	${'Waku' . $WakuName . 'Room'}[] = $KaiRoom3[$x];
				// 	$x++;
				// } elseif (${'wWaku' . $WakuName} > $k) { //残った最大工事枠数分は空き
				// 	${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_AKI;
				// } else {
				// 	${'Waku' . $WakuName . 'Room'}[] = "";
				// }
			// }
		}
	}
	$date->modify('+1 days');
}

foreach($afterReserveDay as $key => $aReserveDay){
	$date = new DateTime($aReserveDay);
	$aSyoniti = false;
	$afterKojiHoliday[$key] = false;
	$afterHoliday[$key] = false;

	$SenyuDate = $date->format('Y-m-d');
	$result = array_search($SenyuDate, $SHUKUJITULIST);
	$YoubiCD =  $date->format('w');
	if ($result !== false || $YoubiCD == 0 || $YoubiCD == 6) {
		$afterHoliday[$key] = true;
	}
	if (count($wHolidayItems) > 0) { //休工日
		$afterKojiHoliday[$key] = isFullDayHoliday($wHolidayItems, $SenyuDate);
	}
	for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
		$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
		$SyonitiKouryo = false;
		if ($wFirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE && $aSyoniti) {
			$SyonitiKouryo = true;
		}else if($wFirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE) && $aSyoniti){
			$SyonitiKouryo = true;
		}
#★★★ここから
		$ban_rooms = 1;
		$Overflows = 0;
		$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
		$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
		$passed_rooms = 0;
		$x = 0;

		for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
			$dis_ban = floor($k / $limit_ban) + 1;
			if (!$afterKojiHoliday[$key]) { //休工日以外
				if ($SyonitiKouryo) { //初日考慮でAMなら　空を入れる
					${'Waku' . $WakuName . 'Room'}[] = "";
				} else if (isSlotHoliday($wHolidayItems, $SenyuDate, $WakuName)) {
					${'Waku' . $WakuName . 'Room'}[] = "休工";
				} else if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
					if($Overflows < $wFrameOverflow){
						if(isset($Reserve[$SenyuDate][$WakuName][$x]) 
							&& (!$arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] || $arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] == $dis_ban)){
							${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
							$passed_rooms ++;
							$x ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_WAKUOVER;
						}
						$EmptyFrameCount ++;
						$Overflows ++;
					}else{
						${'Waku' . $WakuName . 'Room'}[] = "";
					}
				}elseif(isset($Reserve[$SenyuDate][$WakuName][$x]) 
					&& (!$arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] || $arrHanNo[$Reserve[$SenyuDate][$WakuName][$x]] == $dis_ban)){
					if($wArrangeType == '1' && RESERVE_USE_FLOOR_BINDING){
						$bReservedRooms = 0;
						foreach($arrFloorReserveInfo as $floor => $FloorReserveInfo){
							if(date("Y-m-d", strtotime($FloorReserveInfo["wFloorDay"])) == date("Y-m-d", strtotime($SenyuDate)) && $FloorReserveInfo["wFloorWaku"] == $WakuName){
								$bReservedRooms += intval($FloorReserveInfo["wFloorCols"]);
							}
						}
						if(($dis_ban - 1) * $max_ban + $ban_rooms > $bReservedRooms){
							${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_AKI;
							$passed_rooms ++;
							$EmptyFrameCount ++;
						}else{
							${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
							$passed_rooms ++;
							$x ++;
						}
					}else{
						${'Waku' . $WakuName . 'Room'}[] = $Reserve[$SenyuDate][$WakuName][$x];
					}
					$passed_rooms ++;
					$x ++;
				}elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
					${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_AKI;

					$passed_rooms ++;
					$EmptyFrameCount ++;
				}else{
					if($Overflows < $wFrameOverflow){
						${'Waku' . $WakuName . 'Room'}[] = _SLOT_LABEL_WAKUOVER;

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
			$Koteihyou .= "<tr class='trtop trreserveday' id='row".$SenyuDate."'><td class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td  class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td  class='ex_table2'></td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trreserveday' id='row".$SenyuDate."'><td  class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td class='ex_table2'></td>";
		}
		$Koteihyou .= "<td colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($beforeHoliday[$key]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trreserveday' id='row".$SenyuDate."'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trreserveday' id='row".$SenyuDate."'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		}
		$banNo = 1;
		$abanNo = $banNo;
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
				$abanNo = $banNo;
				$banNo ++;
			}
	
			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuEndtime   = date('H:i', strtotime($WAKUPATTERN[$wWakuPattern]['EndTime'][$k]));

				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];

				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					if ($k % 2 == 0) {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
					} else {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
					}
					if (!${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]) {
						$Koteihyou_temp = "<td class='link_cell link_cell_none no-action' style='background-color:#d3d3d3; cursor:default'>";
					}

					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "休工") {
						$Koteihyou_temp = "<td class='link_cell link_cell_none no-action' style='background-color:#d3d3d3; cursor:default'>休工";
					} else if (isSlotLabelAki(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}])) {
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}

						$Koteihyou_temp .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
						
					}else if (isSlotLabelWakuover(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}])) {
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}

						$Koteihyou_temp .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					}else if(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]) {
						$tID = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];

						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}

						$Koteihyou_temp .= '<font style="font-size:20px"> <b>' . $tID . '</b>';
						$Koteihyou_temp .= '</font>';
					}
					// $KoteihyouEX[] = $tID;

					$Koteihyou .= $Koteihyou_temp;

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
			$Koteihyou .= "<tr class='trtop trholiday' id='row".$SenyuDate."'><td class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td  class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
			$Koteihyou .= "<td  class='ex_table2' style='background-color:pink;'></td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trholiday' id='row".$SenyuDate."'><td class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td class='ex_table2'></td>";
		}
		$Koteihyou .= "<td colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($holiday[$i]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop' id='row".$SenyuDate."'><td rowspan=" . $rowCountforDay . " class='ex_table2' style='background-color:pink;'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2' style='background-color:pink;'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop' id='row".$SenyuDate."'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "</td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		}
		$banNo = 1;
		$abanNo = $banNo;
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
				$abanNo = $banNo;
				$banNo ++;
			}
	
			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					if ($k % 2 == 0) {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
					} else {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
					}
					if (!${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]) {
						$Koteihyou_temp = "<td class='link_cell link_cell_none no-action' style='background-color:#d3d3d3; cursor:default'>";
					}

					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "休工") {
						$Koteihyou_temp = "<td class='link_cell link_cell_none no-action' style='background-color:#d3d3d3; cursor:default'>休工";
					} else if (isSlotLabelAki(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}])) {
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}

						$Koteihyou_temp .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					}else if (isSlotLabelWakuover(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}])) {
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}

						$Koteihyou_temp .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];

					}else if(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]) {
						$tID = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];

						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}
						$Koteihyou_temp .= '<font style="font-size:20px"> <b>' . $tID . '</b>';
						$Koteihyou_temp .= '</font>';
					}
					// $KoteihyouEX[] = $tID;

					// echo "<br>" . $WakuName . ":" . ${$WakuName . "index"};
					$Koteihyou .= $Koteihyou_temp;

					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;
				}
			}
			$Koteihyou .= "</tr>";
		}

	}#休工日

	$date->modify('+1 days');
}

foreach($afterReserveDay as $key => $aReserveDay){
	$date = new DateTime($aReserveDay);
	$SenyuDate = $date->format('Y-m-d');
	$NextBlankTime = [];
	$NextBlankContinusCount = [];

	$week_str = $week[$date->format('w')];
	if($week_str == '土')
		$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
	else if($week_str == '日')
		$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

	if ($afterKojiHoliday[$key]) { #★１休工日なら
		if ($afterHoliday[$key]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trreserveday' id='row".$SenyuDate."'><td class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td  class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td  class='ex_table2'></td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trreserveday' id='row".$SenyuDate."'><td class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td class='ex_table2'>" . $week_str . "</td>";
			$Koteihyou .= "<td class='ex_table2'></td>";
		}
		$Koteihyou .= "<td colspan=" . $wWakuColSum . " class='ex_table2'>";
		$Koteihyou .= "休工日</td>";
	} else { #★１休工日でない場合
		if ($afterHoliday[$key]) { //土日祝なら
			$Koteihyou .= "<tr class='trtop trreserveday' id='row".$SenyuDate."'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		} else { //平日なら
			$Koteihyou .= "<tr class='trtop trreserveday' id='row".$SenyuDate."'><td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $SenyuDate . "<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .= "<td rowspan=" . $rowCountforDay . " class='ex_table2'>" . $week_str . "</td>";
		}
		$banNo = 1;
		$abanNo = $banNo;
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
				$abanNo = $banNo;
				$banNo ++;
			}
	
			for ($k = 0; $k < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $k++) {
				$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$k];
				if(!isset($arrBlankCount[$WakuName]))
					$arrBlankCount[$WakuName] = 0;
				for ($l = 0; $l < ${'wWaku' . $WakuName . 'Col'}; $l++) {
					if ($k % 2 == 0) {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
					} else {
						$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
					}
					if (!${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]) {
						$Koteihyou_temp = "<td class='link_cell link_cell_none no-action' style='background-color:#d3d3d3; cursor:default'>";
					}

					if (${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}] == "休工") {
						$Koteihyou_temp = "<td class='link_cell link_cell_none no-action' style='background-color:#d3d3d3; cursor:default'>休工";
					} else if (isSlotLabelAki(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}])) {
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}
						$Koteihyou_temp .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					}else if (isSlotLabelWakuover(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}])) {
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}
						$Koteihyou_temp .= ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
					}else if(${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}]) {
						$tID = ${'Waku' . $WakuName . 'Room'}[${$WakuName . "index"}];
						if ($k % 2 == 0) {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffff9e;'>";
						} else {
							$Koteihyou_temp = "<td class='link_cell' style='background-color:#ffffcf;'>";
						}
						$Koteihyou_temp .= '<font style="font-size:20px"> <b>' . $tID . '</b>';
						$Koteihyou_temp .= '</font>';
					}
					// $KoteihyouEX[] = $tID;

					// echo "<br>" . $WakuName . ":" . ${$WakuName . "index"};
					$Koteihyou .= $Koteihyou_temp;

					$Koteihyou .= "</td>";
					${$WakuName . "index"}++;
				}
			}
			$Koteihyou .= "</tr>";
		}
	}
}

$Koteihyou .= "</table>";


########################################################
// # 担当者リスト表示
// ########################################################
// $myListObject = new SPFWListObject($myDB);

// $sql = "SELECT ";
// $sql .= "UserCD, ";
// $sql .= "LastName ";
// $myListObject->SelectSQL = $sql;

// $sql = " FROM tUserM";
// if ($UserKbn != 2) { #管理者でなければ
// 	$sql .= " WHERE MukouFlg = FALSE AND ClientCD = " . $ClientCD;
// 	#		$sql .= " AND EigyosyoCD = ".$EigyosyoCD; #幹事企業CD
// }
// $myListObject->Condition	= $sql;
// $myListObject->Order 		= "LastNameKana,LastName ";
// $myListObject->Limit 		= "allpage";

// if (!($myListObject->GetList(1)))
// 	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

// $TantoLoop = $myListObject->Rows;
// for ($i = 0; $i < $TantoLoop; $i++) {
// 	$TantoCD[$i] 	= $myListObject->GetValue($i, 0);
// 	$TantoName[$i]	= $myListObject->GetValue($i, 1);
// 	$TantoSoeji[$TantoCD[$i]] = $i;
// }
// unset($myListObject);

// ########################################################
// # 施工業者担当者
// ########################################################
// $myListObject = new SPFWListObject($myDB);

// $sql = "SELECT ";
// $sql .= "gt.UserCD, ";		#0業者担当CD
// $sql .= "gt.LastName, ";	#1業者担当名
// $sql .= "gt.GyosyaCD, ";		#2業者CD
// $sql .= "gt.TEL, ";			#3業者担当TEL
// $sql .= "gt.Address3, ";	#4業者担当携帯
// $sql .= "gt.EMail, ";		#5業者担当メールアドレス
// $sql .= "gt.Address3, ";	#6  2こめのメールアドレス
// $sql .= "gt.Notes, ";		#7備考
// $sql .= "g.GyosyaName, ";	#8業者名
// $sql .= "g.ShozokuCD ";		#9管轄支店　|3|4|5|となっている。
// $myListObject->SelectSQL = $sql;
// $sql = " FROM tUserM gt , tGyosyaM g ";
// $sql .= " WHERE gt.GyosyaCD = g.GyosyaCD AND gt.MukouFlg = FALSE AND UserKbn = 3 "; //

// $myListObject->Condition	= $sql;
// $myListObject->Order 		= "CAST( g.GyosyaNameKana as BINARY ) "; #表示順
// $myListObject->Limit 		= "allpage";

// if (!($myListObject->GetList(1)))
// 	trigger_error("Getting User List Failed.", E_USER_ERROR);

// $GyosyaTantoLoop = $myListObject->Rows;
// for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
// 	$GyosyaTantoCD[$i] 		= $myListObject->GetValue($i, 0);
// 	$GyosyaTantoName[$i]	= $myListObject->GetValue($i, 1);
// 	$GyosyaName[$i] 		= $myListObject->GetValue($i, 8);
// 	$GyosyaKey[$GyosyaTantoCD[$i]] = $i;
// }
// unset($myListObject);

########################################################
# OPメニュー表示
########################################################

########################################################
# 設定情報
########################################################


########################################################
# フォーマットファイルがアップされているか確認
########################################################


// ディレクトリのパス
/*
$directory = 'C:/xampp/htdocs/hochiki/httpdocs/kojifile/'.$editBukkenCD."/";
if (file_exists($directory)) {

	// ディレクトリを開く
	if ($handle = opendir($directory)) {
		#echo "ディレクトリ内のファイル一覧:<br>";

		// ディレクトリ内のアイテムを1つずつ読み込む
		$FileLoop = 0;
		while (($file = readdir($handle)) !== false) {
			if ($file != "." && $file != "..") {
				#echo $file . "<br>";
				$FileName[] = $file ;
				$FileLoop++;
			}
		}

		// ディレクトリハンドルを閉じる
		closedir($handle);
	} else {
		echo "エラー ディレクトリを開けませんでした。";
	}
}
*/
// 
$myListObject = new SPFWListObject($myDB);

$sql  = "SELECT ";
$sql .= "UploadFileID, ";
$sql .= "FileName, ";
$sql .= "FilePath, ";
$sql .= "tUserM.LastName, ";
$sql .= "tUploadFileF.Created ";
$myListObject->SelectSQL = $sql;
$sql  = " FROM tUploadFileF";
$sql .= " LEFT JOIN tUserM ON tUserM.UserCD = tUploadFileF.UserCD";
$sql .= " WHERE tUploadFileF.BukkenCD = " . $editBukkenCD;
if($editBuildingCD){
	$sql .= " AND tUploadFileF.BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND tUploadFileF.BuildingCD IS NULL ";
}


$myListObject->Condition = $sql;
$myListObject->Order = "tUploadFileF.UploadFileID ASC ";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

$UploadFileLoop = $myListObject->Rows;
$UploadFileID = $FileName = $FilePath = $LastName = $Created = [];
for ($i = 0; $i < $UploadFileLoop; $i++) {
	$UploadFileID[$i] 		= $myListObject->GetValue($i, 0);
	$FileName[$i]	= $myListObject->GetValue($i, 1);
	$FilePath[$i] 		= str_replace('C:/xampp/htdocs/hochiki/httpdocs/kojifile/', './kojifile/', $myListObject->GetValue($i, 2));
	$LastName[$i] 		= $myListObject->GetValue($i, 3);
	$Created[$i] 		= date("Y-m-d H:i", strtotime($myListObject->GetValue($i, 4)));
}

########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "s_format.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);


########################################################
# 関数群
########################################################
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

		$WakuRoomSu = ceil($MaxWakuSu / 2) - 1;#切り上げて1引く。　

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE)) { //初日15：00以降OK
			$WakuRoomSu = 0;
		}
	} elseif ($Syoniti == true) { //初日・平日　最大工事枠数-2

		$WakuRoomSu = $MaxWakuSu - 2;

		if ($FirstDateFeature == 1 && strpos($WakuName, 'AM') !== FALSE) { //初日午前NG
			$WakuRoomSu = 0;
		} elseif ($FirstDateFeature == 2 && (strpos($WakuName, 'AM') !== FALSE || strpos($WakuName, 'PM1') !== FALSE)) { //初日15：00以降OK
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


#品番一致
function getDeviceData_Hinban($myDB, $Category, $Hinban)
{

	$DeviceData = array();

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "DeviceName, ";
	$sql .= "Kataban, ";
	$sql .= "D003, "; #機器説明
	$sql .= "ShortName ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tDeviceM";
	$sql .= " WHERE MukouFlg = FALSE and Category = '" . $Category . "'"; #1:親機 2:子機
	$sql .= " AND Kataban = '" . $Hinban . "' ";

	$myListObject->Condition = $sql;
	$myListObject->Order = "Kataban";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$DeviceData['DeviceName'] 	= $myListObject->GetValue(0, 0);
		$DeviceData['Kataban'] 		= $myListObject->GetValue(0, 1);
		$DeviceData['KikiSetumei'] 	= $myListObject->GetValue(0, 2);
		$DeviceData['ShortName'] 	= $myListObject->GetValue(0, 3);
	}
	unset($myListObject);

	return $DeviceData;
}