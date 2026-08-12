<?php
// include_once "/var/www/kawamoto_dia/SPFW/inc/setting.properties";
include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";

include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWListObjectNoCount.cls"; #GroupBYを無効にしているクラス　星☆彡ここから
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
// include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSStylist.cls";
include_once _CLS_DIR . "SPUSSetting.cls";
include_once _CLS_DIR . "SPUSMenu.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSCalendar.cls";
include_once _CLS_DIR . "SPFWParameter.cls";

include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";
include_once "./include/common_489.php";
include_once "./include/building_period_helpers.php";
include_once "./include/holiday_helpers.php";
include_once "./include/web_reserve_slot_helpers.php";


$wLang = SPFWParameter::getValues('wLang');
$rKey = SPFWParameter::getValues('rKey');

// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


########################################################
#　多言語化対応(Multilingual support)
########################################################
$language = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照



########################################################
# マンション名取得
########################################################
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');

$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
	trigger_error("Getting Setting Failed.", E_USER_ERROR);
}

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "") || $myBuilding->RecCnt != 1) {
		trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
	}
}


$wWEBReceptType = $myBukken->WEBReceptType;
$MansionName 	= $myBukken->BukkenName;

$wBuildingName 	= $myBukken->BuildingName;
$WakuPattern 	= $myBukken->WakuPattern;
$WakuRange = $myBukken->MaxWakuSu; #6-4-4

$Holiday = $myBukken->Holiday1;
$ReserveDay = $myBukken->ReserveDay;
$FirstDateFeature = $myBukken->FirstDateFeature;

$wArrangeType = $myBukken->ArrangeType;
$wFrameOverflow = $myBukken->FrameOverflow;
$wHansu = $myBukken->Hansu;
$wFloorReserveInfo = $myBukken->FloorReserveInfo;

if($editBuildingCD){
	$wBuildingName 	= $myBuilding->BuildingName;
	$WakuPattern 	= $myBuilding->WakuPattern;
	$WakuRange = $myBuilding->MaxWakuSu; #6-4-4
	$Holiday = $myBuilding->Holiday1; #6-4-4
	$ReserveDay = $myBuilding->ReserveDay; #6-4-4
	$FirstDateFeature = $myBuilding->FirstDateFeature;

	$wArrangeType = $myBuilding->ArrangeType;
	$wFrameOverflow = $myBuilding->FrameOverflow;
	$wHansu = $myBuilding->Hansu;
	$wFloorReserveInfo = $myBuilding->FloorReserveInfo;
}

// ホーチキ工事: FrameOverflow（時間外枠数）を WEB 空き判定に使用する。
// 点検は従来どおり時間外のみ。余地（通常枠）は WEB 変更対象外。
$wFrameOverflow = intval($wFrameOverflow);

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
if($BuildingLoop < 1){
	$wBuildingName = '';
}

$WakuSuu =  count($WAKUPATTERN[$WakuPattern]['AMPM']);
for ($i = 0; $i < $WakuSuu; $i++) {
	$STimeList[] = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
	$ETimeList[] =  $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
}


$WakuArray = explode("-",$WakuRange);
foreach ($WakuArray as $value) {
    $sumt += (int)$value; // 各要素を整数に変換して合計に加算
}
$Waku = $sumt - 1;
$lowWaku = $Waku - 2;
$ReserveFrom = "120"; #固定で$mySetting->ReserveFrom;
$ReserveTo = "5"; #固定で $mySetting->ReserveTo;

$resolvedPeriod = resolveBuildingSenyuAndYoyaku($myBukken, $editBuildingCD ? $myBuilding : null, $editBuildingCD);
$SenyuStartDate = $resolvedPeriod['SenyuStartDate'];
$SenyuEndDate = $resolvedPeriod['SenyuEndDate'];
$YoyakuEndDate = $resolvedPeriod['YoyakuEndDate'];

$wHoliday = SPFWTools::decodePluralValue($Holiday);
sort($wHoliday);
$HolidayItems = parseHoliday1($Holiday);
$arrHoliday = [];
$arrFullDayHoliday = [];
foreach($HolidayItems as $aHolidayItem){
	array_push($arrHoliday, $aHolidayItem['date']);
	if ($aHolidayItem['period'] === 'ALL') {
		array_push($arrFullDayHoliday, $aHolidayItem['date']);
	}
}

$wReserveDay = SPFWTools::decodePluralValue($ReserveDay);
sort($wReserveDay);

$beforeReserveDay = [];
$afterReserveDay = [];
foreach($wReserveDay as $aReserveDay){
	$dateReserveDay = new DateTime($aReserveDay);
	$dateSenyuStartDate = new DateTime($SenyuStartDate);
	$dateSenyuEndDate = new DateTime($SenyuEndDate);

	if($dateReserveDay < $dateSenyuStartDate){
		array_push($beforeReserveDay, date('Y-m-d', strtotime($aReserveDay)));
	}else if($dateReserveDay > $dateSenyuEndDate){
		array_push($afterReserveDay, date('Y-m-d', strtotime($aReserveDay)));
	}
}


$SenyuStartDateConvert = $SenyuStartDate;
$SenyuEndDateConvert = $SenyuEndDate;

$CancelTo = "2"; #$mySetting->CancelTo;

$OpenTime = ['09:00','09:00','09:00','09:00','09:00','09:00','09:00'];
$CloseTime = ['18:00','18:00','18:00','18:00','18:00','18:00','18:00'];
$LunchTimeFrom = ['12:00','12:00','12:00','12:00','12:00','12:00','12:00'];
$LunchTimeTo = ['13:00','13:00','13:00','13:00','13:00','13:00','13:00'];


$Holiday = SPFWTools::decodePluralValue($mySetting->Holiday);
$FreeFlg = "2";#固定$mySetting->FreeFlg;

$MINUTEUNIT = $myBukken->MinuteTime;#時間単位だな

// $MINUTETYPE = $mySetting->getTimeArray($MINUTEUNIT);
$MINUTETYPE[0] = $MINUTEUNIT;# 0が20分これしか使わないとする。　本来なら　1が40分　２が60分とかになる。

$ReserveFromDate = $SenyuStartDate;
$MyOpenTime = $OpenTime[$TargetWeekdayNo];
$MyCloseTime = $CloseTime[$TargetWeekdayNo];
$MyLunchTimeFrom = $LunchTimeFrom[$TargetWeekdayNo];
$MyLunchTimeTo = $LunchTimeTo[$TargetWeekdayNo];
$MyEveningTimeFrom = $EveningTimeFrom[$TargetWeekdayNo];
$MyEveningTimeTo = $EveningTimeTo[$TargetWeekdayNo];
$MyHoliday = $Holiday[$TargetWeekdayNo];
$MyOpenTimeHour = intval(substr($MyOpenTime, 0, 2));
$MyOpenTimeMinute = intval(substr($MyOpenTime, 3, 2));
$MyCloseTimeHour = intval(substr($MyCloseTime, 0, 2));
$MyCloseTimeMinute = intval(substr($MyCloseTime, 3, 2));





####20150619　時間帯ごとの工事枠を取得して、枠越えの感知する。 →　工事枠という概念はなく、工事班に枠越え（班）があるだけ

$WakuSuu = count($WAKUPATTERN[$WakuPattern]['AMPM']);
$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][0];
$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$WakuSuu - 1];

$TimeLoop  = $WakuSuu;
for ($i = 0; $i < $TimeLoop; $i++) { #時間選択し
	$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
	$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
	$wTime[$i] = $sSTime . "～" . $sETime;

}

########################################################
# 入力チェック
########################################################
if ($rKey == NULL) {
	if($editBuildingCD)
		$URL = _MAIN_URL . 'login.php?editBuildingCD='.$editBuildingCD;
	else
		$URL = _MAIN_URL . 'login.php';
	header('Location: ' . $URL);
	exit;
}
########################################################
# 認証動作
########################################################
$myUser = new User($myDB);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

$IfConfirm = false;
$IfNotConfirm = true;
if ($myUser->UserCD == -1) {
	if($editBuildingCD)
		$URL = _MAIN_URL . 'login.php?editBuildingCD='.$editBuildingCD;
	else
		$URL = _MAIN_URL . 'login.php';

	header('Location: ' . $URL);
	exit;
}else {
	if($myUser->ConfirmFlg == '1'){
		$IfConfirm = true;
		$IfNotConfirm = false;
	}else{
		$IfConfirm = false;
		$IfNotConfirm = true;
	}
}

// 仮日程
$ReserveState = '仮';
if($myUser->ReplyFlg == '3'){
	$ReserveState = '辞';
	$IfConfirm = false;
	$IfNotConfirm = true;
}else if($myUser->ReplyFlg == '1' || $myUser->ReplyFlg == '2' || $myUser->ConfirmFlg == '1'){
	$ReserveState = '確';
}

$loginUserCD = $myUser->UserCD; //今ログインしているUserCD
$UserCDmantan = $loginUserCD  ;
$wID = $myUser->ID; //今ログインしているUserCD
$MyMenuCD = SPFWTools::decodePluralValue($myUser->MenuCD)[0]; //今ログインしているUserCDの工事内容 |11|
///お客様情報が入ってなかったらformへ戻す20170621
$wTEL = $myUser->TEL;
$wLastName = $myUser->LastName;

$Address3 = $myUser->Address3; #ユーザの棟記号
$TargetClientCD = $myUser->ClientCD;
if ($wLastName == "" or $wTEL == "") {
	if($editBuildingCD)
		$URL = './form.php?rKey=' . $rKey . '&editBukkenCD='.$editBukkenCD.'&editBuildingCD='.$editBuildingCD.'&wLang=' . $wLang . '&btnflg=1';
	else
		$URL = './form.php?rKey=' . $rKey . '&editBukkenCD='.$editBukkenCD.'&wLang=' . $wLang . '&btnflg=1';
	// $URL = _MAIN_URL . $wClientID . '/form.php?rKey=' . $rKey . '&wLang=' . $wLang . '&btnflg=1';
	header('Location: ' . $URL);
	exit;
}

// ########################################################
// # 多棟の場合 ユーザの属する棟の専有部期間に変換
// ########################################################
// if ($TatoFlg == "1" and $Address3 != NULL) {
// 	$ToData = getToData($myDB, $wClientCD);
// 	for ($x = 0; $x < count($ToData); $x++) {
// 		if ($Address3 == $ToData['ToName'][$x]) {
// 			$tatoSenyuStartDate = $ToData['SenyuStartDate'][$x];
// 			$tatoSenyuEndDate = $ToData['SenyuEndDate'][$x];

// 			//多棟の2次受付締切日を取得
// 			$wExtendedYoyakuEndDate = $ToData['ExtendedYoyakuEndDate'][$x];
			
// 			//wExtendedYoyakuEndDateに値があれば$IfExtendedYoyakuEndDateをfalseにする
// 			if ($wExtendedYoyakuEndDate != NULL) {
// 				$IfExtendedYoyakuEndDate = false;
// 			} else {
// 				$IfExtendedYoyakuEndDate = true;
// 			}

// 			// datepicker用
// 			$SenyuStartDateConvert = $tatoSenyuStartDate;
// 			$SenyuEndDateConvert = $tatoSenyuEndDate;
// 			break;
// 		}
// 	}
// }

########################################################
# 予約抽出
########################################################
#20150418　複数枠をもってこない。はじめのひとつの予約が選択されている。

$editReservationCD = SPFWParameter::getValues('editReservationCD');
$ct = SPFWParameter::getValues('ct');
$vRes = SPFWParameter::getValues('vRes');
$r = SPFWParameter::getValues('r');
$wDate = SPFWParameter::getValues('wDate');
$wLang = SPFWParameter::getValues('wLang');
$Select = SPFWParameter::getValues('Select');
$vCal = SPFWParameter::getValues('vCal');

$wGoMonth = SPFWParameter::getValues('wGoMonth');
$wGoYear = SPFWParameter::getValues('wGoYear');

$wUserMemo = SPFWParameter::getValues('wUserMemo');
$wSecondTimeFrom = SPFWParameter::getValues('wSecondTimeFrom');
$Syusei = SPFWParameter::getValues('Syusei');

if ($r > 0) {
	$editReservationCD = $r;
	SPFWTemplate::setValue('editReservationCD', $editReservationCD);
}

$myReservation = new Reservation($myDB);
if($editBuildingCD){
	if (!$myReservation->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '" . $editBuildingCD . "' AND UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE", "")) {
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	}
}else{
	if (!$myReservation->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL AND UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE", "")) {
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	}
}


$editReservationCD = $myReservation->ReservationCD;
$wSecondChoice = $myReservation->SecondChoice;
$wUserMemo = $myReservation->UserMemo; // 20220519edit 中西さんより MemoからUserMemoカラムに変更
$wTimeFrom = $myReservation->TimeFrom; #ここ間違っている可能性あり★
SPFWTemplate::setValue('editReservationCD', $editReservationCD);

if ($myReservation->RecCnt == 1 && !($editReservationCD > 0))
	$IfAlready = TRUE;

$IfYouCan = !$IfAlready;
/*
if ($vRes == NULL) {
	$myReservation = new Reservation($myDB);

	// if($editBuildingCD){
	// 	if (!$myReservation->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '" . $editBuildingCD . "' AND UserCD = '" . $myUser->UserCD . "' AND ReservationCD = " . intval($editReservationCD) . " AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", ""))
	// 		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	// }else{
	// 	if (!$myReservation->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL AND UserCD = '" . $myUser->UserCD . "' AND ReservationCD = " . intval($editReservationCD) . " AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", ""))
	// 		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	// }

	if($editBuildingCD){
		if (!$myReservation->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '" . $editBuildingCD . "' AND UserCD = '" . $myUser->UserCD . "' AND ReservationCD = " . intval($editReservationCD) . " AND Status = 1 AND MukouFlg = FALSE", ""))
			trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	}else{
		if (!$myReservation->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL AND UserCD = '" . $myUser->UserCD . "' AND ReservationCD = " . intval($editReservationCD) . " AND Status = 1 AND MukouFlg = FALSE", ""))
			trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	}

	if ($myReservation->RecCnt == 0)
		$IfNoReservation = TRUE;

	if ($myReservation->RecCnt == 1) { #ここはレコードは１つ。ReservationCDが１つを選択する
		$IfReservation = TRUE;

		$wStylistCD = $myReservation->StylistCD;
		$wTimeFrom = $myReservation->TimeFrom;
		$wMenuCD = SPFWTools::decodePluralValue($myReservation->MenuCD)[0]; #基本的には1つのはず　工事内容が2つならおかしくなる
		$wFreeFlg = $myReservation->FreeFlg;

		$wYear = substr($wTimeFrom, 0, 4);
		$wMonth = substr($wTimeFrom, 5, 2);
		$wDay = substr($wTimeFrom, 8, 2);
		$wDate = substr($wTimeFrom, 0, 10);


		$QUERY .= "&vRes=t&r=" . $editReservationCD;
		SPFWTemplate::setValue('vRes', 't');
		SPFWTemplate::setValue('wDate', $wDate);
		$wGoYear = $wYear;
		$wGoMonth = $wMonth;
	}
} else if ($editReservationCD > 0)
	$QUERY .= "&vRes=t&r=" . $editReservationCD;
*/
if ($editReservationCD > 0)
	$QUERY .= "&vRes=t&r=" . $editReservationCD;

$myReservation2 = new Reservation($myDB);

if($editBuildingCD){
	if (!$myReservation2->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '" . $editBuildingCD . "' AND UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE", ""))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
}else{
	if (!$myReservation2->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL AND UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE", ""))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
}

if ($myReservation2->RecCnt == 0) {
	$IfNoReservation = TRUE;
} else {
	$IfReservation = TRUE;
}

function getTimeFormat($time){
	$datetime = DateTime::createFromFormat('H:i', $time);
	if((int)$datetime->format('i') == 0)
		$formatted = (int)$datetime->format('G') . '時';
	else
		$formatted = (int)$datetime->format('G') . '時' . (int)$datetime->format('i') . '分';

	return $formatted;
}

if ($myReservation2->RecCnt >= 1) {
	$wTimeFrom = $myReservation2->TimeFrom;
	if ($wTimeFrom) $IfTimeFrom = TRUE;
	if (empty($wTimeFrom) && !empty($OpSenkouFlg)) {
		$IfTimeFrom = false;
		$IfKaraOpSenkou = true;
	}
	$WakuTime = getWakuTime2($WakuPattern, $wTimeFrom);
	if($WakuTime)
		$Reservationtime = getTimeFormat($WakuTime['STime']) . "～" . getTimeFormat($WakuTime['ETime']);
	else
		$Reservationtime = '';

	$DispReservationDate = date('n月j日', strtotime($wTimeFrom));

	$WeekList = array("日", "月", "火", "水", "木", "金", "土");
	$w1 = $WeekList[date('w', strtotime($wTimeFrom))];	

} #20 End


########################################################
# 初期日付設定
########################################################
if ($wDate != NULL) {
	$wYear = substr($wDate, 0, 4);
	$wMonth = substr($wDate, 5, 2);
	$wDay = substr($wDate, 8, 2);

	SPFWTemplate::dropValue('wYear');
	SPFWTemplate::dropValue('wMonth');
	SPFWTemplate::dropValue('wDay');
	SPFWTemplate::setValue('wYear', $wYear);
	SPFWTemplate::setValue('wMonth', $wMonth);
	SPFWTemplate::setValue('wDay', $wDay);

	$IfReserveOK = TRUE;
}

if ($wGoYearMonth != NULL) {
	$wGoYear = substr($wGoYearMonth, 0, 4);
	$wGoMonth = substr($wGoYearMonth, 4, 2);
}

if ($vCal == 't' && $wGoYear != NULL && $wGoMonth != NULL) {
	$vDate = sprintf("%04d/%02d/%02d", $wGoYear, $wGoMonth, 1);
} else if ($wDate != NULL) {
	$vDate = sprintf("%04d/%02d/%02d", $wYear, $wMonth, $wDay);
} else {
	$vDate = date('Y/m/d');

	// $SenyuEndDate = $myBukken->SenyuEndDate;

	if($tatoSenyuEndDate != NULL){
		$SenyuEndDate = $tatoSenyuEndDate;
	}
	$SenyuEndDateINI = date('Y/m/d', strtotime($SenyuEndDate));

	$vDate = $SenyuEndDateINI;
}

$WeekList = array("日", "月", "火", "水", "木", "金", "土");
///次の月や前の月、日付を選択した際に専有部の日程が表示されないので対応20170707

// $mySetting = new Setting($myDB);
// if (!$mySetting->executeSelect("ClientCD = " . $TargetClientCD . " AND MukouFlg = FALSE", "")) {
// 	$ErrorString = array();
// 	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
// 	$ErrorLoop = count($ErrorString);
// 	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
// 	unset($myTemplate);
// 	exit;
// }

// if(empty($TatoFlg) or $TatoFlg == 0){

	//専有部日時をわけて表示 英語分岐対応必要★
	// $wSenyuEndDate 	= $myBukken->SenyuEndDate;
	$wSenyuEndDate 	= $SenyuEndDate;

	$DispSenyuEndDate = date('Y年m月d日', strtotime($wSenyuEndDate));
	// $wSenyuStartDate = $myBukken->SenyuStartDate;
	$wSenyuStartDate = $SenyuStartDate;
	$DispSenyuStartDate = date('Y年m月d日', strtotime($wSenyuStartDate));
	
	$w4 = $WeekList[date('w', strtotime($wSenyuStartDate))];
	$w5 = $WeekList[date('w', strtotime($wSenyuEndDate))];
// }else { //多棟の場合は多棟の設定を引っ張ってくる
// 	$DispSenyuEndDate = date('Y年m月d日', strtotime($tatoSenyuEndDate));
// 	$DispSenyuStartDate = date('Y年m月d日', strtotime($tatoSenyuStartDate));

// 	$w4 = $WeekList[date('w', strtotime($tatoSenyuStartDate))];
// 	$w5 = $WeekList[date('w', strtotime($tatoSenyuEndDate))];
// }

// unset($mySetting);

// ########################################################
// # 休日設定リストを取得
// ########################################################

########################################################
# スタイリストリストを取得
########################################################

$myListObject = new SPFWListObject($myDB);
$sql = "SELECT ";
$sql .= "StylistCD, ";
$sql .= "ClientCD, ";
$sql .= "StylistName, ";
$sql .= "NumberOfLines, ";
$sql .= "NumberOfFreeLines, ";
$sql .= "FreeFlg, ";
$sql .= "OpenTime, ";
$sql .= "CloseTime";
$myListObject->SelectSQL = $sql;
$sql = " FROM tStylistM";
$sql .= " WHERE StylistCD > 0 AND MukouFlg = FALSE";
// if (is_array($whereSQL)) {
// 	for ($i = 0; $i < count($whereSQL); $i++)
// 		$sql .= " AND " . $whereSQL[$i];
// }
$sql .= " AND ClientCD = " . $TargetClientCD;

// $sql .= " AND WakugoeFlg = 0 ";
$myListObject->Condition = $sql;
$myListObject->Order = "StylistCD";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

unset($StylistCD);
unset($StylistName);
unset($StylistSelected);

if ($FreeFlg == 1) {
	$StylistCD[] = -1;
	$StylistName[] = "FREE";
	$StylistSelected[] = NULL;
}

$LinesLoop = $myListObject->Rows;
for ($i = 0; $i < $LinesLoop; $i++) {
	$StylistCD[] = $myListObject->GetValue($i, 0);
	#$StylistName[] = $myListObject->GetValue($i, 2);
	#$StylistSelected[] = (!$wFreeFlg && $wStylistCD > 0 && $myListObject->GetValue(0, 0) == $wStylistCD) ? ' selected' : NULL;
	$StylistLines[] = $myListObject->GetValue($i, 3);
}
$StylistLoop = 1; #count($StylistCD);

// スタイリストが複数いない場合は選択させず、固定値を入れる
if (($FreeFlg == 1 && $StylistLoop == 2) || ($FreeFlg != 1 && $StylistLoop == 1)) {
	$IfStylist = FALSE;
	SPFWTemplate::setValue("wStylistCD", $StylistCD[0]);
	SPFWTemplate::setValue("vStylist", 'f');
} else {
	$IfStylist = TRUE;
	SPFWTemplate::dropValue("wStylistCD");
}


########################################################
# メニュー抽出
########################################################

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "MenuCD, ";
$sql .= "ClientCD, ";
$sql .= "MenuName, ";
$sql .= "NumberOfLines, ";
$sql .= "MinuteType ";
$myListObject->SelectSQL = $sql;
$sql = " FROM tMenuM";
$sql .= " WHERE MenuCD > 0 AND MukouFlg = FALSE";
// if (is_array($whereSQL)) {
// 	for ($i = 0; $i < count($whereSQL); $i++)
// 		$sql .= " AND " . $whereSQL[$i];
// }
$sql .= " AND ClientCD = " . $TargetClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "MenuCD";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$MenuLoop = $myListObject->Rows;
for ($i = 0; $i < $MenuLoop; $i++) {
	$MenuCD[$i] = $myListObject->GetValue($i, 0);
	$MenuName[$i] = $myListObject->GetValue($i, 2);
	#$Lines[$i] = $myListObject->GetValue($i, 3);
	$MyMinuteType[$i] = $myListObject->GetValue($i, 4);
	$pMinuteType[$i] = $MINUTETYPE[$MyMinuteType[$i]];
	$MenuNameArray[$MenuCD[$i]] = $myListObject->GetValue($i, 2);

	if ($MenuCD[$i] == $MyMenuCD) { # $MyMenuCD は　|12|　など
		$MenuChecked[$i] =  ' checked';
		$wMyMinuteType = 	$MyMinuteType[$i];
	}
}
## 20110616 add 　YKKさんは1個目とは限らない　
#	$MenuChecked[0] = (is_array($wMenuCD) && array_search($MenuCD[0], $wMenuCD) !== FALSE) ? ' checked' : checked;


//空日程の現在選択中の日付が表示されないようにする
$IfNokara = FALSE;
// if (($editReservationCD > 0) or $wDate != NULL) {
if ($wDate != NULL) {
	$IfNokara = TRUE;
} else {
	$IfKara = true;
}



########################################################
# カレンダー作成1
########################################################
if($tatoSenyuEndDate != NULL){
	$SenyuEndDate = $tatoSenyuEndDate;
}
if($tatoSenyuStartDate != NULL){
	$SenyuStartDate = $tatoSenyuStartDate;
}

$Toweek = $WeekList[date('w', strtotime($SenyuEndDate))];
$Fromweek = $WeekList[date('w', strtotime($SenyuStartDate))];

$SenyuEndDate = date('Y/m/d', strtotime($SenyuEndDate));
$SenyuStartDate = date('Y/m/d', strtotime($SenyuStartDate));

if ($vDate != NULL) {
	$myDate = $vDate;

	$MyYear = substr($vDate, 0, 4);

	if (strstr($vDate, '/'))
		$MyMonth = intval(substr($vDate, 5, 2));
	else
		$MyMonth = intval(substr($vDate, 4, 2));

	if (strstr($vDate, '/'))
		$MyDay = intval(substr($vDate, 8, 2));
	else
		$MyDay = intval(substr($vDate, 6, 2));
}




if (!checkdate($MyMonth, $MyDay, $MyYear))
	showSorryPage(_ILLEGAL_ACCESS);

$firstDate = SPFWDate::getFirstOfThisMonth($myDate);
$endDate = SPFWDate::getEndOfThisMonth($myDate);
$days = SPFWDate::getDayCountOfTerm($firstDate, $endDate) + 1;

$FirstDate = SPFWDate::getFormattedTimestamp(SPFWDate::getStrippedTimestamp($firstDate), "/");
$EndDate = SPFWDate::getFormattedTimestamp(SPFWDate::getStrippedTimestamp($endDate), "/");
$ShowDate = sprintf('%04d%02d%02d', $MyYear, $MyMonth, $MyDay);


########################################################
# 週単位のテンプレートブロック抜き出し
########################################################
if ($wLang == 1) {
	$CNT_FILE = "reserve_form_eng.tpl";
} else {
	$CNT_FILE = "reserve_form_kakutei.tpl";
}
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

// リスト部分(Loopの中身)のエレメント確定
$LoopString2 = $myTemplate->getStringBetween('WeekdayLoop');
$LoopString2 = '__WeekdayLoop__' . $LoopString2 . '__WeekdayLoop__';

########################################################
# カレンダー用休日設定リストを取得
########################################################
$myListObject = new SPFWListObject($myDB);
$sql = "SELECT ";
$sql .= "Day ";

$myListObject->SelectSQL = $sql;
$sql = " FROM tCalendarF";
$sql .= " WHERE CalendarCD > 0 AND MukouFlg = FALSE";
if ($MYSQL)
	$sql .= " AND date_format(Day, '%Y/%m/%d') >= date_format('" . $FirstDate . "', '%Y/%m/%d')";
else
	$sql .= " AND Day::DATE >= '" . $FirstDate . "'";
if ($MYSQL)
	$sql .= " AND date_format(Day, '%Y/%m/%d') <= date_format('" . $EndDate . "', '%Y/%m/%d')";
else
	$sql .= " AND Day::DATE <= '" . $EndDate . "'";
$sql .= " AND StylistCD IS NULL";
$sql .= " AND TimeFrom IS NULL";
$sql .= " AND TimeTo IS NULL";
$sql .= " AND ClientCD = " . $TargetClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "CalendarCD";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Calendar List Failed.", E_USER_ERROR);

for ($i = 0; $i < $myListObject->Rows; $i++) {
	$TempDay = $myListObject->GetValue($i, 0);
	$TempDay = substr($TempDay, 0, 10);
	$SetteiHoliday[] = $TempDay; #JavaScript 次へボタンで使用
	$TempDay = str_replace('-', '/', $TempDay);
	$ShopHoliday[$TempDay] = TRUE;
}
unset($myListObject);

####tSettingMの週の休工日を取得
if (in_array('t', $Holiday)) {
	$day1 = strtotime($SenyuStartDateConvert);
	$day2 = strtotime($SenyuEndDateConvert);
	$senyu_loop = ($day2 - $day1) / (60 * 60 * 24) + 1;
	for ($i = 0; $i < $senyu_loop; $i++) { // 専有部日数分のループ
		$tmp_senyu_date = date("Y-m-d", strtotime("+" . $i . " day", strtotime($SenyuStartDateConvert)));
		$tmp_senyu_weekno = date('w', strtotime($tmp_senyu_date)); // 0～6

		$tmp_senyu_weekholiday = $Holiday[$tmp_senyu_weekno]; // tSettingMの休日 その週が休みの場合、tが入る
		if ($tmp_senyu_weekholiday == 't') {
			$SetteiHoliday[] = $tmp_senyu_date; #JavaScript 次へボタンで使用
		}
	}
	if (is_array($SetteiHoliday)) {
		sort($SetteiHoliday); // 配列を日付順に並び替え
		$ShopHolidayLoop = count($SetteiHoliday);
	} else {
		$ShopHolidayLoop = 0;
	}
}


###20110828予約日色付け
$DispmyDate = $wYear . $wMonth . $wDay;
if ($vDate != NULL and $DispmyDate == "") {
	$DispmyDate = str_replace("/", "", $myDate);
}
###予約日色付けEND


########################################################
# カレンダー作成2
########################################################
$FirstWeekDay = date('w', mktime(0, 0, 0, substr($FirstDate, 5, 2), substr($FirstDate, 8, 2), substr($FirstDate, 0, 4)));
$DayCount = substr($EndDate, 8, 2) + $FirstWeekDay;
$WeekLoop = ceil($DayCount / 7);
$FirstInCalendar = date('Ymd', mktime(0, 0, 0, substr($FirstDate, 5, 2), substr($FirstDate, 8, 2) - $FirstWeekDay, substr($FirstDate, 0, 4)));

for ($i = 0; $i < $WeekLoop; $i++) {
	$WeekdayLoop = 7;

	unset($FullDate);
	unset($ThisDay);
	unset($IfDateValue);
	unset($DayColor);
	unset($IfOK1);
	unset($IfOK2);
	unset($IfEmpty);

	for ($j = 0; $j < $WeekdayLoop; $j++) {
		$TempDate = SPFWDate::getDatePlus($FirstInCalendar, $i * 7 + $j);
		$Jokyo[$j] = "-";

		####予約が満タンなら  Start
		$Mantan == 0;

		$TempDate2 = date('Y-m-d', strtotime($TempDate));

		$link = mysqli_connect(_HOST_NAME, _USER_NAME, _PASSWD, _MAIN_DB);

		####休日判定Start
		$sql = "SELECT count(Day) Kojiyasumi FROM tCalendarF where substring(Day,1,10) = '$TempDate2' AND MukouFlg = FALSE AND ClientCD = '" . $wClientCD . "' ";
		$recordSet2 = mysqli_query($link, $sql) or die(mysqli_error());
		$data = mysqli_fetch_assoc($recordSet2);
		$Kojiyasumi = $data['Kojiyasumi'];

		####休日判定End




		if (intval(substr($TempDate, 4, 2)) == $MyMonth) {
			$FullDate[$j] = SPFWDate::getFormattedTimestamp($TempDate, "/");
			//$Month = substr($TempDate, 4, 2));
			$ThisMonth[$j] = intval(substr($TempDate, 4, 2));
			$ThisDay[$j] = intval(substr($TempDate, 6, 2));
			$ThisYear[$j] = intval(substr($TempDate, 0, 4));
			$IfDateValue[$j] = TRUE;

			#$IfOK1[$j] = $IfOK2[$j] = (!$IfDateValue[$j] || $ShopHoliday[$FullDate[$j]] || $Holiday[$j] == 't' || $TempDate < $SenyuEndDate || $TempDate > $SenyuStartDate) ? FALSE : TRUE;
			$IfOK1[$j] = $IfOK2[$j] = (!$IfDateValue[$j]
				|| $ShopHoliday[$FullDate[$j]]
				|| $Holiday[$j] == 't'
				|| strtotime($TempDate2) > strtotime($SenyuEndDate)
				|| strtotime($TempDate2) < strtotime($SenyuStartDate)
				|| in_array($TempDate2, $arrFullDayHoliday)) ? FALSE : TRUE;
			
			// 予備日
			if(in_array($TempDate2, $beforeReserveDay) || in_array($TempDate2, $afterReserveDay)){
				$IfOK1[$j] = $IfOK2[$j] = TRUE;
			}

			if ($IfOK1[$j]) {
				$DayColor[$j] = "#ffffe0";
				$Jokyo[$j] = "○";

				###工事が休み
				if ($Kojiyasumi == 1) {
					$DayColor[$j] = "#ffffe0";
					#$xxx = $TempDate ;
					$Jokyo[$j] = "休";
					$IfOK1[$j] = false;
				}
				###空き確認関数
				//$AkiData = getAkiWaku2($myDB, $TargetClientCD, $TempDate, $WakuRange);
				//if ($AkiData['akiUmu'] <> 1) {

				$ExcludePattern = 0;
				if($FirstDateFeature == 1){
					$date1 = date("Y/m/d", strtotime($TempDate2));
					$date2 = date("Y/m/d", strtotime($SenyuStartDate));
					if($date1 == $date2){
						$ExcludePattern = 1;
					}
				}else if($FirstDateFeature == 2){
					$date1 = date("Y/m/d", strtotime($TempDate2));
					$date2 = date("Y/m/d", strtotime($SenyuStartDate));
					if($date1 == $date2){
						$ExcludePattern = 2;
					}
				}

				$dayWakuRange = applyHolidayToWakuRange(
					$WakuRange,
					$WAKUPATTERN[$WakuPattern]['AMPM'],
					getHolidayPeriodsForDate($HolidayItems, $TempDate2)
				);
				$WakuZanSuu = getAkiWaku($myDB, $TargetClientCD,$editBukkenCD,$editBuildingCD, $TempDate2, $WakuSuu, $STimeList, $ETimeList, $dayWakuRange, $wArrangeType, $WAKUPATTERN, $wHansu, $wFloorReserveInfo, $WakuPattern, $UserCDmantan, $ExcludePattern, $wFrameOverflow);
				if ($WakuZanSuu <= 0) {
					$DayColor[$j] = "#ffffe0";
					$Jokyo[$j] = "×";
					$IfOK1[$j] = false;
				} elseif ($WakuZanSuu <= 3) { // 2枠以下なら
					$DayColor[$j] = "#ffffe0";
					$Jokyo[$j] = "△";
				}

				###20110828　予約日色付け
				if ($TempDate == $DispmyDate) {
					if ($IfNokara) {
						$DayColor[$j] = "#f9e8d9";
						$IfOK1[$j] = false;
					} else {
						$DayColor[$j] = "#ffffe0";
					}
				}
				###20110828 予約日色付けEND
			} else if (in_array($TempDate2, $arrFullDayHoliday)) {
				$Jokyo[$j] = "休";
				$IfOK1[$j] = false;
			} else if ($TempDate < $SenyuEndDate || $TempDate > $SenyuStartDate) {
				$DayColor[$j] = $OutOfTermColor;
			} else if ($ShopHoliday[$FullDate[$j]] || $Holiday[$j] == 't') {
				//$DayColor[$j] = "#ffffe0";
				$Jokyo[$j] = "休";
				$IfOK1[$j] = false;
			} else {
				$DayColor[$j] = $OutOfTermColor; //灰色
				$Jokyo[$j] = "-";
			}
		} else {
			$ThisDay[$j] = "&nbsp;";
			$Jokyo[$j] = "&nbsp;";
			$IfEmpty[$j] = TRUE;
			$DayColor[$j] = $OutOfTermColor;
		}

	}

	$myTemplate->Msg = $LoopString2;
	$myTemplate->Msg2 = $LoopString3;
	$myTemplate->convertTags();
	$WeekdayBlock[$i] = $myTemplate->Msg;
}


$myTimestamp = SPFWDate::getPiecesOfTimestamp(SPFWDate::getStrippedTimestamp(SPFWDate::getMonthPlus($myDate, -1)));
$Previous = $myTimestamp['Year'] . "年" . $myTimestamp['Month'] . "月";
$PreviousWork = SPFWDate::getStrippedTimestamp(SPFWDate::getMonthPlus($myDate, -1));
$myTimestamp = SPFWDate::getPiecesOfTimestamp(SPFWDate::getStrippedTimestamp(SPFWDate::getMonthPlus($myDate, 1)));
$Next = $myTimestamp['Year'] . "年" . $myTimestamp['Month'] . "月";
$NextWork = SPFWDate::getStrippedTimestamp(SPFWDate::getMonthPlus($myDate, 1));
if ($wThisMonth) { //読み込み時
	$ThisMonth = $wGoMonth;
	$ThisYear = $wGoYear;
} else {
	$ThisMonth = $ThisMonth[0];
	$ThisYear = $ThisYear[0];
}
$Yearplus = $ThisYear + 1;
$Monthplus = $ThisMonth + 1;
$Yearminu = $ThisYear - 1;
$Monthminu = $ThisMonth - 1;
if ($ThisMonth == 1) {
	$IfYearback = true;
} else if ($ThisMonth == 12) {
	$IfYearover = true;
} else {
	$IfNoover = true;
}

if ($ThisMonth == 1) {
	$eThisMonth = "January";
} else if ($ThisMonth == 2) {
	$eThisMonth = "February";
} else if ($ThisMonth == 3) {
	$eThisMonth = "March";
} else if ($ThisMonth == 4) {
	$eThisMonth = "April";
} else if ($ThisMonth == 5) {
	$eThisMonth = "May";
} else if ($ThisMonth == 6) {
	$eThisMonth = "June";
} else if ($ThisMonth == 7) {
	$eThisMonth = "July";
} else if ($ThisMonth == 8) {
	$eThisMonth = "August";
} else if ($ThisMonth == 9) {
	$eThisMonth = "September";
} else if ($ThisMonth == 10) {
	$eThisMonth = "October";
} else if ($ThisMonth == 11) {
	$eThisMonth = "November";
} else if ($ThisMonth == 12) {
	$eThisMonth = "December";
}

########################################################
# 表示関連
########################################################
$DefaultYear = (int)substr($SenyuEndDate, 0, 4);
$DefaultMonth = (int)substr($SenyuEndDate, 5, 2);
$DefaultDay = (int)substr($SenyuEndDate, 7, 2);

if ($ReserveTo != NULL && $ReserveFrom != NULL) {
	$TempMonth = -1;
	$TempYear = -1;
	for ($i = $ReserveTo; $i <= $ReserveFrom; $i++) {
		$CompareMonth = (int) date('m', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')));
		$CompareYear = (int) date('Y', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')));
		if ($TempMonth != $CompareMonth || $TempYear != $CompareYear) {
			$YearMonthValue[] = $CompareYear . $CompareMonth;
			if ($wLang == 1) {
				$YearMonthChoice[] = $CompareMonth . '/' . $CompareYear;
			} else {
				$YearMonthChoice[] = $CompareYear . '年' . $CompareMonth . '月';
			}
		}
		if ($TempMonth != $CompareMonth) {
			$MonthChoice[] = $CompareMonth;
			$TempMonth = $CompareMonth;
		}
		if ($TempYear != $CompareYear) {
			$YearChoice[] = $CompareYear;
			$TempYear = $CompareYear;
		}
	}
} else {
	$YearChoice[] = date('Y', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
	$YearChoice[] = date('Y', mktime(0, 0, 0, date('m'), date('d'), date('Y') + 1));

	for ($i = 1; $i <= 12; $i++)
		$MonthChoice[] = $i;
}

$YearLoop = count($YearChoice);
for ($i = 0; $i < $YearLoop; $i++) {
	$YearValue[$i] = $YearChoice[$i];
	$YearSelected[$i] = (($wGoYear > 0 && date('Y') + $i == $wGoYear) || ($wGoYear == NULL && $DefaultYear == $YearValue[$i])) ? " selected" : NULL;
}

$MonthLoop = count($MonthChoice);
for ($i = 0; $i < $MonthLoop; $i++) {
	$MonthValue[$i] = $MonthChoice[$i];
	###20110811 日付をおすとデフォルト月にもどってしまうバグ対応
	if ($vCal != "t")
		$wGoMonth = substr($wDate, 5, 2);
	#		$MonthSelected[$i] = (($wGoMonth > 0 && intval($MonthValue[$i]) == $wGoMonth) || ($wGoMonth == NULL && $DefaultMonth == $MonthValue[$i])) ? " selected" : NULL;
	$MonthSelected[$i] = (($wGoMonth > 0 && $MonthValue[$i] == $wGoMonth) || ($wGoMonth == NULL && $DefaultMonth == $MonthValue[$i])) ? " selected" : NULL;
	###　END

}



###20110811 日付をおすとデフォルト月にもどってしまうバグ対応 vCalはカレンダーを変更したらつく。
if ($vCal != "t")
	$wGoYearMonth = $DefaultYear . intval($wGoMonth);
$YearMonthLoop = count($YearMonthChoice);
for ($i = 0; $i < $YearMonthLoop; $i++) {
	$YearMonthSelected[$i] = ($wGoYearMonth == $YearMonthValue[$i]) ? " selected" : NULL;
}

$DayLoop = 31;
for ($i = 0; $i < $DayLoop; $i++) {
	$DayValue[$i] = $i + 1;
	$DaySelected[$i] = (($wDay > 0 && intval($DayValue[$i]) == $wDay) || ($wDay == NULL && $DefaultDay == $DayValue[$i])) ? " selected" : NULL;
}

$HoursLoop = $MyCloseTimeHour - $MyOpenTimeHour + 1;
for ($i = 0; $i < $HoursLoop; $i++) {
	$HoursValue[$i] = $MyOpenTimeHour + $i;
	$HoursSelected[$i] = (intval($HoursValue[$i]) == $wHour) ? " selected" : NULL;
}

$MinutesLoop = 60 / $MINUTEUNIT;
for ($i = 0; $i < $MinutesLoop; $i++) {
	$MinutesValue[$i] = $i * $MINUTEUNIT;
	$MinutesSelected[$i] = (intval($MinutesValue[$i]) == $wMinute) ? " selected" : NULL;
}

//曜日取得
$w = date('w', strtotime($DispmyDate));
$weekday = $WeekList[$w];

$wDate = str_replace("-", "/", $wDate);

if ($wDay) {
	$TargetDate = $wDate;


	###空き確認関数
	$ExcludePattern = 0;
	$dayWakuRange = applyHolidayToWakuRange(
		$WakuRange,
		$WAKUPATTERN[$WakuPattern]['AMPM'],
		getHolidayPeriodsForDate($HolidayItems, $TargetDate)
	);
	$AkiTime = getAkiWakuTime($myDB, $TargetClientCD, $editBukkenCD, $editBuildingCD,$TargetDate, $WakuSuu, $STimeList, $ETimeList, $dayWakuRange, $wArrangeType, $WAKUPATTERN, $wHansu, $wFloorReserveInfo, $WakuPattern, $UserCDmantan, $ExcludePattern, $wFrameOverflow);

	$wOKTimeName = array();
	$OKTimes = array();
	$OKTimeSelected = array();
	for ($i = 0; $i < count($AkiTime); $i++) {
		$slotNameForTime = '';
		for ($x = 0; $x < $WakuSuu; $x++) {
			#echo "<br>932行目AkiTime[$i]:". $AkiTime[$i]."---".$ETimeList[$x];
			if ($AkiTime[$i] == $STimeList[$x]) {
				$AkiEndTime = $ETimeList[$x];
				$slotNameForTime = $WAKUPATTERN[$WakuPattern]['AMPM'][$x];
			}
		}
		if ($slotNameForTime !== '' && isSlotHoliday($HolidayItems, $TargetDate, $slotNameForTime)) {
			continue;
		}

		$wOKTimeName[] = $AkiTime[$i] . "～" . $AkiEndTime;
		$OKTimes[] = $AkiTime[$i];
		$idx = count($OKTimes) - 1;
		if(isset($WakuTime['STime']) && $WakuTime['STime'] == $AkiTime[$i] && (date("Y-m-d", strtotime($wTimeFrom)) == date("Y-m-d", strtotime($TargetDate))))
			$OKTimeSelected[$idx] = "selected";
		else
			$OKTimeSelected[$idx] = "";
	}
	if (is_array($wOKTimeName)) {
		if($FirstDateFeature == 1){
			$date1 = date("Y/m/d", strtotime($TargetDate));
			$date2 = date("Y/m/d", strtotime($SenyuStartDate));
			if($date1 == $date2){
				array_shift($wOKTimeName);
				array_shift($OKTimes);
			}
		}else if($FirstDateFeature == 2){
			$date1 = date("Y/m/d", strtotime($TargetDate));
			$date2 = date("Y/m/d", strtotime($SenyuStartDate));
			if($date1 == $date2){
				if(count($wOKTimeName) > 2){
					array_splice($wOKTimeName, 0, 2);
					array_splice($OKTimes, 0, 2);
				}
			}
		}
		$OKTimesLoop = count($wOKTimeName);
	}
}

SPFWTemplate::dropValue("vCal");
SPFWTemplate::dropValue("wGoYear");
SPFWTemplate::dropValue("wGoMonth");
SPFWTemplate::dropValue("wGoYearMonth");
SPFWTemplate::dropValue("wHour");
SPFWTemplate::dropValue("wMinute");
SPFWTemplate::dropValue("wMenuCD");
SPFWTemplate::dropValue("wTime");
SPFWTemplate::dropValue("wSecondChoice");


########################################################
# もどったときの入力値保持
########################################################
$Syusei = SPFWParameter::getValues('Syusei');
if ($Syusei == 1) {

	$wID = SPFWParameter::getValues('wID');
	$wSecondChoice = SPFWParameter::getValues('wSecondChoice'); // 第二希望
	$wUserMemo = SPFWParameter::getValues('wUserMemo'); // ご要望欄
	$wYear = SPFWParameter::getValues('wYear');
	$wMonth = SPFWParameter::getValues('wMonth');
	$wDay = SPFWParameter::getValues('wDay');
	$wDate = SPFWParameter::getValues('wDate');
	$wTime = SPFWParameter::getValues('wTime');

	$Syusei == "";
}
#戻り部分 End

########################################################
# コンテンツ表示
########################################################
// if ($wLang == 1) {
// 	$CNT_FILE = "reserve_form_eng.tpl";
// } else {
	$CNT_FILE = "reserve_form_kakutei.tpl";
// }

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, FALSE, $MyClientCD);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->Msg = str_replace($LoopString2, '', $myTemplate->Msg);
$myTemplate->Msg = str_replace($LoopString3, '', $myTemplate->Msg);

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);

// echo "<br> ".__LINE__." END :" ;




/***
 * 日付を指定して空き枠残数を取得
 *
 *  @param	WakuSuu: ex) 3
 *  @param	STimeList: ex) array 各枠の開始時間 [0] => 09:00 [1] => 13:00 [2] => 15:00
 *  @param	ETimeList: ex) array 各枠の終了時間 [0] => 12:00 [1] => 15:00 [2] => 17:00
 *  @param	WakuRange: ex)7-5-5
 *  @return	WakuZanSuu: ex)15
 */
// 点検の場合は、ユーザー側で時間指定枠を考慮します。 $wFrameOverflow
function getAkiWaku($myDB, $TargetClientCD,$editBukkenCD,$editBuildingCD, $TargetDate, $WakuSuu, $STimeList, $ETimeList, $WakuRange, $wArrangeType, $WAKUPATTERN, $wHansu, $wFloorReserveInfo, $wWakuPattern, $loginUserCD = "", $ExcludePattern=0, $wFrameOverflow=0)
{
	// ホーチキ WEB申込: 時間外（FrameOverflow）枠のみを空きとして数える。
	// 余地（通常枠の空き）は WEB 変更対象外。点検も従来どおり時間外のみ。
	$wFrameOverflow = intval($wFrameOverflow);
	$wHansu = intval($wHansu);
	if ($wHansu < 1) {
		$wHansu = 1;
	}

	$useOverflowOnly = webReserveUsesOverflowSlotsOnly($wArrangeType, $wFrameOverflow);
	if (!$useOverflowOnly) {
		$myListObject = new SPFWListObjectNoCount($myDB); #GroupBYを無効にしているクラス

		$sql = "SELECT ";
		$sql .= "count(r.ReservationCD), ";
		$sql .= " (CASE ";
		for ($x = 0; $x < $WakuSuu; $x++) { //AM1,AM2,PM1なら$WakuSuuは3。9時13時15時スタートなら$STimeList[$x]は9,13,15が入る。
			$tmp = $x + 1;
			$sql .= " WHEN (DATE_FORMAT(r.TimeFrom,'%H:%i') >= '$STimeList[$x]') and (DATE_FORMAT(r.TimeFrom,'%H:%i') <= '$ETimeList[$x]' ) THEN '$tmp' ";
		}
		$sql .= " ELSE '99' ";
		$sql .= " END) AS orderTimeFrom "; //使ってないっぽい
		// echo "<br><br>".$sql;
	$sqltest1 = $sql;
		$myListObject->SelectSQL = $sql;
		$sql = " FROM tReservationF r , tUserM u";
		$sql .= " WHERE r.ReservationCD > 0 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
		$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
		$sql .= " AND r.Status = 1";
		$sql .= " AND u.ReplyFlg <> 3";
		$sql .= " AND r.ClientCD = " . $TargetClientCD;
		$sql .= " AND r.BukkenCD = " . $editBukkenCD;
		if($editBuildingCD){
			$sql .= " AND r.BuildingCD = " . $editBuildingCD;
		}else{
			$sql .= " AND r.BuildingCD IS NULL ";
		}

		// 【2026/06 不具合対応】満枠の日でも「自分の予約」を件数から除外すると、
		// 既に予約済みの本人にだけ 残数1=△ が見え、満枠日でも申込/変更を
		// 進められてしまう（残数0→×にならない）。実際の残数を正しく表示する
		// ため、カレンダー記号・時間帯候補では自分の予約も件数に含める。
		// （予約変更の最終確定 getAkiWakuAMPMTime() では従来どおり自分を除外し、
		//   空きのある日での予約変更は引き続き可能）
		// if ($loginUserCD) // 自分の予約は除く（同じ時間に修正できる）
		// 	$sql .= " AND r.UserCD != '$loginUserCD' ";
	$sqltest2 = $sql;
	#echo $sql;
		$myListObject->Condition = $sql;
		$myListObject->Group = "orderTimeFrom";
		$myListObject->Order = "orderTimeFrom";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

		$ReservationLoop = $myListObject->Rows;
		// 枠（バンド）ごとの予約数を band番号(1始まり) => 件数 で集計する。
		// orderTimeFrom: 1=AM, 2=PM(または PM1), 3=PM2 ... 99=枠の時間外
		$bookedByBand = array();
		for ($i = 0; $i < $ReservationLoop; $i++) {
			$band = $myListObject->GetValue($i, 1);
			$bookedByBand[$band] = (int)$myListObject->GetValue($i, 0);
		}
		$WakuRangeArray = explode("-", $WakuRange);

		// 初日特例（FirstDateFeature）で先頭枠を除外する場合は対象外にする。
		$startBand = 1;
		if($ExcludePattern == 1)
			$startBand = 2; // 先頭1枠を除外
		else if($ExcludePattern == 2)
			$startBand = 3; // 先頭2枠を除外

		// 【2026/06 不具合対応】枠越（オーバーフロー）を含まない実空きのみを
		// 枠単位で数える。ある枠が定員超過（枠越使用）でも、その超過分が他枠の
		// 空きを相殺しないよう、枠ごとに 0 未満を切り捨ててから合計する。
		// （例: 定員15-15で AM=18,PM=14 → AM=0(切捨), PM=1 → 残数1=△）
		$WakuZanSuu = 0;
		for ($i = 0; $i < count($WakuRangeArray); $i++) {
			$band = $i + 1;
			if ($band < $startBand)
				continue; // 初日特例で除外された枠
			$max_i = (int)$WakuRangeArray[$i];
			$booked_i = isset($bookedByBand[$band]) ? $bookedByBand[$band] : 0;
			$free_i = $max_i - $booked_i;
			if ($free_i > 0)
				$WakuZanSuu += $free_i; // 実空きのみ加算（枠越は含めない）
		}

	}else{
		if ($wFrameOverflow < 1) {
			return 0;
		}
		$MaxWaku = explode("-",$WakuRange);
		$wWakuAM = $MaxWaku[0];
		$wWakuPM = $MaxWaku[1];
		$wWakuPM1 = $MaxWaku[1];
		if(count($MaxWaku)>2){
			$wWakuPM2 = $MaxWaku[2];
		}

		$wFloorReserveInfo = html_entity_decode($wFloorReserveInfo, ENT_QUOTES, 'UTF-8');
		$arrFloorReserveInfo = json_decode($wFloorReserveInfo, true);

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
		$sql .= "u.LastName, "; #7
		$sql .= "u.TEL, "; #8
		$sql .= "r.Memo, "; #9
		$sql .= "r.TimeExact, "; #10
		$sql .= "r.TimeMeaning, "; #11
		$sql .= "u.ReplyFlg, "; #12
		$sql .= "u.ConfirmFlg, "; #13
		$sql .= "r.HanNo, "; #14
		$sql .= "r.ViewOrderNo, "; #15
		$sql .= "u.EMail, "; #16
		$sql .= "CASE ";
		$sql .= " WHEN u.ReplyFlg = 3 THEN '2'";
		$sql .= " ELSE '1'";
		$sql .= " END AS SubOrder"; #17


		$myListObject->SelectSQL = $sql;
		$sql  = " FROM tReservationF r, tUserM u ";
		$sql .= " WHERE r.Status = 1 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
		$sql .= " AND r.BukkenCD = " . $editBukkenCD;
		if($editBuildingCD){
			$sql .= " AND r.BuildingCD = " . $editBuildingCD;
		}else{
			$sql .= " AND r.BuildingCD IS NULL ";
		}
		$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";

		$myListObject->Condition = $sql;
		$myListObject->Order = "Date, AMPM, r.HanNo, SubOrder, r.TimeFrom, r.Updated, r.ReservationCD";
		$myListObject->Limit = "allpage";
		if (!($myListObject->GetList(1)))
			trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

		$ReservationLoop = $myListObject->Rows;

		for ($i = 0; $i < $ReservationLoop; $i++) {
			$AMPM = $myListObject->GetValue($i, 2);
			$ID[$i] = $myListObject->GetValue($i, 3);
			$Reserve[$AMPM][] = $ID[$i];
			$UserData['ReplyFlg'][$ID[$i]] 	= $myListObject->GetValue($i, 12);
			$UserData['ConfirmFlg'][$ID[$i]] 	= $myListObject->GetValue($i, 13);
			$arrHanNo[$ID[$i]] 	= $myListObject->GetValue($i, 14);
		}

		// 初期予約情報を取得します。
		$myListObjectInit = new SPFWListObject($myDB);
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

		$myListObjectInit->SelectSQL = $sql;
		$sql  = " FROM tReservationInitF r, tUserM u ";
		$sql .= " WHERE r.Status = 1 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
		$sql .= " AND r.BukkenCD = " . $editBukkenCD;
		if($editBuildingCD){
			$sql .= " AND r.BuildingCD = " . $editBuildingCD;
		}else{
			$sql .= " AND r.BuildingCD IS NULL ";
		}
		$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";

		$myListObjectInit->Condition = $sql;
		$myListObjectInit->Order = "Date, AMPM, r.HanNo, r.TimeFrom, r.Updated, r.ReservationCD";
		$myListObjectInit->Limit = "allpage";

		if (!($myListObjectInit->GetList(1)))
			trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

		$ReservationLoopInit = $myListObjectInit->Rows;
		for ($i = 0; $i < $ReservationLoopInit; $i++) {
			$AMPM = $myListObjectInit->GetValue($i, 2);
			$ID[$i] = $myListObjectInit->GetValue($i, 3);
			$HanNo = $myListObjectInit->GetValue($i, 7);
			$ReserveInit[$AMPM][$HanNo][] = $ID[$i];
		}

		$rowCountforDay = $wHansu;
		for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
			$tempRowCountforDay = ceil((${'wWaku' . $WakuName}+$wFrameOverflow * $wHansu) / 5);
			if($tempRowCountforDay > $rowCountforDay)
				$rowCountforDay = $tempRowCountforDay;

			${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $wHansu)+$wFrameOverflow;
			${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;

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

			${'wWaku' . $WakuName . 'col'} = ceil(${'wWaku' . $WakuName} / $rowCountforDay)+$wFrameOverflow;
			if($WakuName == 'PM')$wWakuPM1col = ${'wWaku' . $WakuName . 'Col'} ;
		}
		$number01 = 0;
		for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
			$ban_rooms = 1;
			$Overflows = 0;
			$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
			$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
			$x = 0;

			for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
				$dis_ban = floor($k / $limit_ban) + 1;
				if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
					if($Overflows < $wFrameOverflow){
						if(isset($Reserve[$WakuName][$x]) 
							&& (!$arrHanNo[$Reserve[$WakuName][$x]] || $arrHanNo[$Reserve[$WakuName][$x]] == $dis_ban)){
							// 辞退
							if(isset($UserData['ReplyFlg'][$Reserve[$WakuName][$x]]) && $UserData['ReplyFlg'][$Reserve[$WakuName][$x]] == '3'){
								$number01 ++;
								$x ++;
							}else{
								$x ++;
							}
						}else{
							$number01 ++;
						}
						$Overflows ++;
					}
				}elseif(isset($Reserve[$WakuName][$x]) 
					&& (!$arrHanNo[$Reserve[$WakuName][$x]] || $arrHanNo[$Reserve[$WakuName][$x]] == $dis_ban)){
					// 辞退
					if(isset($UserData['ReplyFlg'][$Reserve[$WakuName][$x]]) && $UserData['ReplyFlg'][$Reserve[$WakuName][$x]] == '3'){
						$x ++;
					}else{
						// 仮日程の場合は、チェックを行わずに表示します。
						if(empty($UserData['ReplyFlg'][$Reserve[$WakuName][$x]]) && empty($UserData['ConfirmFlg'][$Reserve[$WakuName][$x]])){
							$x ++;
						}else{
							$bReservedRooms = 0;
							// foreach($arrFloorReserveInfo as $floor => $FloorReserveInfo){
							// 	if(date("Y-m-d", strtotime($FloorReserveInfo["wFloorDay"])) == date("Y-m-d", strtotime($TargetDate)) && $FloorReserveInfo["wFloorWaku"] == $WakuName){
							// 		$bReservedRooms += intval($FloorReserveInfo["wFloorCols"]);
							// 	}
							// }
							if(isset($ReserveInit[$WakuName][$dis_ban]) && is_array($ReserveInit[$WakuName][$dis_ban])){
								$bReservedRooms = count($ReserveInit[$WakuName][$dis_ban]);
							}
							if($ban_rooms > $bReservedRooms){
							}else{
								$bCorrectFloor = false;
								// foreach($arrFloorReserveInfo as $floor => $FloorReserveInfo){
								// 	if(date("Y-m-d", strtotime($FloorReserveInfo["wFloorDay"])) == date("Y-m-d", strtotime($SenyuDate)) && $FloorReserveInfo["wFloorWaku"] == $WakuName){
								// 		if (preg_match('/^'.$floor.'\d{2}$/', $Reserve[$SenyuDate][$WakuName][$x])) {
								// 			$bCorrectFloor = true;
								// 			break;
								// 		}
								// 	}
								// }
								if(isset($ReserveInit[$WakuName][$dis_ban]) && is_array($ReserveInit[$WakuName][$dis_ban])){
									foreach($ReserveInit[$WakuName][$dis_ban] as $ReserveInitRoom){
										if($ReserveInitRoom == $Reserve[$WakuName][$x]){
											$bCorrectFloor = true;
											break;
										}
									}
								}
								$x ++;
							}
						}
					}
				}elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
				}else{
					if($Overflows < $wFrameOverflow){
						$number01 ++;
					}else{
					}
				}

				$ban_rooms ++;
				if($ban_rooms > $limit_ban){
					$ban_rooms = 1;
					$Overflows = 0;
				}
			}
		}
		$WakuZanSuu = $number01;
	}
	return $WakuZanSuu;
}



/* 日付を指定して空き時間を取得
 *
 *  @param	WakuSuu: ex) 3
 *  @param	STimeList: ex) array 各枠の開始時間 [0] => 09:00 [1] => 13:00 [2] => 15:00
 *  @param	ETimeList: ex) array 各枠の終了時間 [0] => 12:00 [1] => 15:00 [2] => 17:00
 *  @param	WakuRange: ex)7-5-5
 *  @return	AkiDataTimeFrom: ex) array 空き時間の開始時間 [0] => 09:00 [1] => 13:00 [2] => 15:00
 */

function getAkiWakuTime($myDB, $TargetClientCD,$editBukkenCD,$editBuildingCD, $TargetDate, $WakuSuu, $STimeList, $ETimeList, $WakuRange, $wArrangeType, $WAKUPATTERN, $wHansu, $wFloorReserveInfo, $wWakuPattern, $loginUserCD = "", $ExcludePattern=0, $wFrameOverflow=0)
{
	// ホーチキ WEB申込: 選択可能な時間帯も時間外枠のみ。
	$wFrameOverflow = intval($wFrameOverflow);
	$wHansu = intval($wHansu);
	if ($wHansu < 1) {
		$wHansu = 1;
	}

	$useOverflowOnly = webReserveUsesOverflowSlotsOnly($wArrangeType, $wFrameOverflow);
	if (!$useOverflowOnly) {
		$myListObject = new SPFWListObjectNoCount($myDB); #GroupBYを無効にしているクラス

		$sql = "SELECT ";
		$sql .= "count(r.ReservationCD), ";
		$sql .= " (CASE ";
		for ($x = 0; $x < $WakuSuu; $x++) {
			$tmp = $x + 1;
			$sql .= " WHEN (DATE_FORMAT(r.TimeFrom,'%H:%i') >= '$STimeList[$x]') and (DATE_FORMAT(r.TimeFrom,'%H:%i') <= '$ETimeList[$x]' ) THEN '$tmp' ";
		}
		$sql .= " ELSE '99' ";
		$sql .= " END) AS orderTimeFrom ";

		$myListObject->SelectSQL = $sql;
		$sql = " FROM tReservationF r , tUserM u";
		$sql .= " WHERE r.ReservationCD > 0 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
		$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
		$sql .= " AND r.Status = 1";
		$sql .= " AND u.ReplyFlg <> 3";
		$sql .= " AND r.ClientCD = " . $TargetClientCD;
		$sql .= " AND r.BukkenCD = " . $editBukkenCD ;

		if($editBuildingCD){
			$sql .= " AND r.BuildingCD = " . $editBuildingCD ;
		}else{
			$sql .= " AND r.BuildingCD IS NULL " ;
		}

		// 【2026/06 不具合対応】満枠の日でも「自分の予約」を件数から除外すると、
		// 既に予約済みの本人にだけ 残数1=△ が見え、満枠日でも申込/変更を
		// 進められてしまう（残数0→×にならない）。実際の残数を正しく表示する
		// ため、カレンダー記号・時間帯候補では自分の予約も件数に含める。
		// （予約変更の最終確定 getAkiWakuAMPMTime() では従来どおり自分を除外し、
		//   空きのある日での予約変更は引き続き可能）
		// if ($loginUserCD) // 自分の予約は除く（同じ時間に修正できる）
		// 	$sql .= " AND r.UserCD != '$loginUserCD' ";

		$myListObject->Condition = $sql;
		$myListObject->Group = "orderTimeFrom";
		$myListObject->Order = "orderTimeFrom";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting Reservation List Failed.", E_USER_ERROR);


		$ReservationLoop = $myListObject->Rows;
		for ($i = 0; $i < $ReservationLoop; $i++) {
			$ReserveCount[$i] = $myListObject->GetValue($i, 0); #枠ごとの予約数
			$orderTimeFrom[$i] = $myListObject->GetValue($i, 1); #枠定義No
		}

		$WakuRangeArray = explode("-", $WakuRange);
		$AkiDataTimeFrom = array();
		$j = 0;

		#for( $i=0; $i<$WakuSuu;$i++){ #WakuSuuとReservationLoopは同じではない。
		#	if( $ReserveCount[$i] < $WakuRangeArray[$i] ){#空きあり
		#		$AkiDataTimeFrom[] =  $STimeList[$i];#空きのある開始時間
		#	}
		#}

		$j = 0;
		for ($i = 0; $i < $WakuSuu; $i++) { #WakuSuuとReservationLoopは同じではない。

			//orderTimeFrom(枠定義No)が枠パターンの順番とマッチするかどうかをまず確認
			if ($orderTimeFrom[$j] == $i + 1) {
				if ($ReserveCount[$j] < $WakuRangeArray[$i]) { #空きあり
					$AkiDataTimeFrom[] = $STimeList[$i]; #空きのある開始時間
				}
				$j++;
			} else { //マッチしない = 予約がない = 空いている
				$AkiDataTimeFrom[] =  $STimeList[$i]; #空きのある開始時間
			}

			//旧プログラム 2023/10/30廃止済み
			// if ($orderTimeFrom[$j] != $i + 1) {
			// 	echo '通ってる' . $orderTimeFrom[$j] . ($i + 1) . '<br>';
			// 	$AkiDataTimeFrom[] =  $STimeList[$i]; #空きのある開始時間
			// } else { #一致したら
			// 	if ($ReserveCount[$j] < $WakuRangeArray[$i]) { #空きあり
			// 		$AkiDataTimeFrom[] =  $STimeList[$i]; #空きのある開始時間
			// 	} else {
			// 		$j++; #空きなし
			// 	}
			// }
		}
	}else{
		if ($wFrameOverflow < 1) {
			return array();
		}
		$MaxWaku = explode("-",$WakuRange);
		$wWakuAM = $MaxWaku[0];
		$wWakuPM = $MaxWaku[1];
		$wWakuPM1 = $MaxWaku[1];
		if(count($MaxWaku)>2){
			$wWakuPM2 = $MaxWaku[2];
		}

		$wFloorReserveInfo = html_entity_decode($wFloorReserveInfo, ENT_QUOTES, 'UTF-8');
		$arrFloorReserveInfo = json_decode($wFloorReserveInfo, true);

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
		$sql .= "u.LastName, "; #7
		$sql .= "u.TEL, "; #8
		$sql .= "r.Memo, "; #9
		$sql .= "r.TimeExact, "; #10
		$sql .= "r.TimeMeaning, "; #11
		$sql .= "u.ReplyFlg, "; #12
		$sql .= "u.ConfirmFlg, "; #13
		$sql .= "r.HanNo, "; #14
		$sql .= "r.ViewOrderNo, "; #15
		$sql .= "u.EMail, "; #16
		$sql .= "CASE ";
		$sql .= " WHEN u.ReplyFlg = 3 THEN '2'";
		$sql .= " ELSE '1'";
		$sql .= " END AS SubOrder"; #17


		$myListObject->SelectSQL = $sql;
		$sql  = " FROM tReservationF r, tUserM u ";
		$sql .= " WHERE r.Status = 1 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
		$sql .= " AND r.BukkenCD = " . $editBukkenCD;
		if($editBuildingCD){
			$sql .= " AND r.BuildingCD = " . $editBuildingCD;
		}else{
			$sql .= " AND r.BuildingCD IS NULL ";
		}
		$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";

		$myListObject->Condition = $sql;
		$myListObject->Order = "Date, AMPM, r.HanNo, SubOrder, r.TimeFrom, r.Updated, r.ReservationCD";
		$myListObject->Limit = "allpage";
		if (!($myListObject->GetList(1)))
			trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

		$ReservationLoop = $myListObject->Rows;

		for ($i = 0; $i < $ReservationLoop; $i++) {
			$AMPM = $myListObject->GetValue($i, 2);
			$ID[$i] = $myListObject->GetValue($i, 3);
			$Reserve[$AMPM][] = $ID[$i];
			$UserData['ReplyFlg'][$ID[$i]] 	= $myListObject->GetValue($i, 12);
			$UserData['ConfirmFlg'][$ID[$i]] 	= $myListObject->GetValue($i, 13);
			$arrHanNo[$ID[$i]] 	= $myListObject->GetValue($i, 14);
		}

		// 初期予約情報を取得します。
		$myListObjectInit = new SPFWListObject($myDB);
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

		$myListObjectInit->SelectSQL = $sql;
		$sql  = " FROM tReservationInitF r, tUserM u ";
		$sql .= " WHERE r.Status = 1 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
		$sql .= " AND r.BukkenCD = " . $editBukkenCD;
		if($editBuildingCD){
			$sql .= " AND r.BuildingCD = " . $editBuildingCD;
		}else{
			$sql .= " AND r.BuildingCD IS NULL ";
		}
		$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";

		$myListObjectInit->Condition = $sql;
		$myListObjectInit->Order = "Date, AMPM, r.HanNo, r.TimeFrom, r.Updated, r.ReservationCD";
		$myListObjectInit->Limit = "allpage";

		if (!($myListObjectInit->GetList(1)))
			trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

		$ReservationLoopInit = $myListObjectInit->Rows;
		for ($i = 0; $i < $ReservationLoopInit; $i++) {
			$AMPM = $myListObjectInit->GetValue($i, 2);
			$ID[$i] = $myListObjectInit->GetValue($i, 3);
			$HanNo = $myListObjectInit->GetValue($i, 7);
			$ReserveInit[$AMPM][$HanNo][] = $ID[$i];
		}

		$rowCountforDay = $wHansu;
		for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$i];
			$tempRowCountforDay = ceil((${'wWaku' . $WakuName}+$wFrameOverflow * $wHansu) / 5);
			if($tempRowCountforDay > $rowCountforDay)
				$rowCountforDay = $tempRowCountforDay;

			${'wWaku' . $WakuName . 'Col'} = ceil(${'wWaku' . $WakuName} / $wHansu)+$wFrameOverflow;
			${'wWaku' . $WakuName . 'ColSum'} = ${'wWaku' . $WakuName . 'Col'} * $wHansu;

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

			${'wWaku' . $WakuName . 'col'} = ceil(${'wWaku' . $WakuName} / $rowCountforDay)+$wFrameOverflow;
			if($WakuName == 'PM')$wWakuPM1col = ${'wWaku' . $WakuName . 'Col'} ;
		}
		$ReserveCount = [];
		for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
			$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
			$ban_rooms = 1;
			$Overflows = 0;
			$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
			$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
			$x = 0;
			$ReserveCount[$j] = 0;

			for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
				$dis_ban = floor($k / $limit_ban) + 1;
				if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
					if($Overflows < $wFrameOverflow){
						if(isset($Reserve[$WakuName][$x]) 
							&& (!$arrHanNo[$Reserve[$WakuName][$x]] || $arrHanNo[$Reserve[$WakuName][$x]] == $dis_ban)){
							// 辞退
							if(isset($UserData['ReplyFlg'][$Reserve[$WakuName][$x]]) && $UserData['ReplyFlg'][$Reserve[$WakuName][$x]] == '3'){
								$ReserveCount[$j] ++;
								$x ++;
							}else{
								$x ++;
							}
						}else{
							$ReserveCount[$j] ++;
						}
						$Overflows ++;
					}
				}elseif(isset($Reserve[$WakuName][$x]) 
					&& (!$arrHanNo[$Reserve[$WakuName][$x]] || $arrHanNo[$Reserve[$WakuName][$x]] == $dis_ban)){
					// 辞退
					if(isset($UserData['ReplyFlg'][$Reserve[$WakuName][$x]]) && $UserData['ReplyFlg'][$Reserve[$WakuName][$x]] == '3'){
						$x ++;
					}else{
						// 仮日程の場合は、チェックを行わずに表示します。
						if(empty($UserData['ReplyFlg'][$Reserve[$WakuName][$x]]) && empty($UserData['ConfirmFlg'][$Reserve[$WakuName][$x]])){
							$x ++;
						}else{
							$bReservedRooms = 0;
							// foreach($arrFloorReserveInfo as $floor => $FloorReserveInfo){
							// 	if(date("Y-m-d", strtotime($FloorReserveInfo["wFloorDay"])) == date("Y-m-d", strtotime($TargetDate)) && $FloorReserveInfo["wFloorWaku"] == $WakuName){
							// 		$bReservedRooms += intval($FloorReserveInfo["wFloorCols"]);
							// 	}
							// }
							if(isset($ReserveInit[$WakuName][$dis_ban]) && is_array($ReserveInit[$WakuName][$dis_ban])){
								$bReservedRooms = count($ReserveInit[$WakuName][$dis_ban]);
							}
							if($ban_rooms > $bReservedRooms){
							}else{
								$bCorrectFloor = false;
								// foreach($arrFloorReserveInfo as $floor => $FloorReserveInfo){
								// 	if(date("Y-m-d", strtotime($FloorReserveInfo["wFloorDay"])) == date("Y-m-d", strtotime($SenyuDate)) && $FloorReserveInfo["wFloorWaku"] == $WakuName){
								// 		if (preg_match('/^'.$floor.'\d{2}$/', $Reserve[$SenyuDate][$WakuName][$x])) {
								// 			$bCorrectFloor = true;
								// 			break;
								// 		}
								// 	}
								// }
								if(isset($ReserveInit[$WakuName][$dis_ban]) && is_array($ReserveInit[$WakuName][$dis_ban])){
									foreach($ReserveInit[$WakuName][$dis_ban] as $ReserveInitRoom){
										if($ReserveInitRoom == $Reserve[$WakuName][$x]){
											$bCorrectFloor = true;
											break;
										}
									}
								}
								$x ++;
							}
						}
					}
				}elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
				}else{
					if($Overflows < $wFrameOverflow){
						$ReserveCount[$j] ++;
					}else{
					}
				}

				$ban_rooms ++;
				if($ban_rooms > $limit_ban){
					$ban_rooms = 1;
					$Overflows = 0;
				}
			}
		}

		$AkiDataTimeFrom = array();
		for ($i = 0; $i < $WakuSuu; $i++) { #WakuSuuとReservationLoopは同じではない。
			if(isset($ReserveCount[$i]) && $ReserveCount[$i] > 0){
				$AkiDataTimeFrom[] = $STimeList[$i]; #空きのある開始時間
			}
		}
	}

	#print_r( $AkiDataTimeFrom );
	return $AkiDataTimeFrom; #配列　09:00　13:00など入ってる
}

#echo "<br> ".__LINE__." ここまでOK END:";
?>
