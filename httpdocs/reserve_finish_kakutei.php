<?php
$isAdminMode = TRUE;
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
include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSMenu.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSCalendar.cls";
include_once _CLS_DIR . "SPFWParameter.cls";

include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";
include_once _CLS_DIR . "SPUSBranche.cls";

include_once "./include/common_489.php";


// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

$wLang = SPFWParameter::getValues('wLang');


########################################################
#　多言語化対応(Multilingual support)
########################################################
$language = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照


########################################################
# マンション名取得
########################################################
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD 	= SPFWParameter::getValues('editBuildingCD');

SPFWTemplate::setValue('editBukkenCD', $editBukkenCD);
SPFWTemplate::setValue('editBuildingCD', $editBuildingCD);
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

$MansionName 	= $myBukken->BukkenName;
$wBuildingName 	= $myBukken->BuildingName;

// $OPEnd = $mySetting->OPEnd;
// #$MinuteUnit = $mySetting->MinuteUnit;
// $KetteiHaifuDate = date('Y年m月d日', strtotime($mySetting->KetteiHaifuDate));

$MINUTEUNIT = $myBukken->MinuteTime;#時間単位だな 20
// $MINUTETYPE = $mySetting->getTimeArray($MINUTEUNIT);
$MINUTETYPE[0] = $MINUTEUNIT;# 0が20分これしか使わないとする。　本来なら　1が40分　２が60分とかになる。

$MaxWakuSu = $myBukken->MaxWakuSu;#6-4-4
$WakuPattern = $myBukken->WakuPattern;
$wHansu = $myBukken->Hansu;
$wArrangeType = $myBukken->ArrangeType;
$wFrameOverflow = $myBukken->FrameOverflow;
$wFloorReserveInfo = $myBukken->FloorReserveInfo;
$wWakuPattern = $myBukken->WakuPattern;

if($editBuildingCD){
	$wBuildingName 	= $myBuilding->BuildingName;
	$MaxWakuSu = $myBuilding->MaxWakuSu;#6-4-4
	$WakuPattern = $myBuilding->WakuPattern;
	$wHansu = $myBuilding->Hansu;
	$wArrangeType = $myBuilding->ArrangeType;
	$wFrameOverflow = $myBuilding->FrameOverflow;
	$wFloorReserveInfo = $myBuilding->FloorReserveInfo;
	$wWakuPattern = $myBuilding->WakuPattern;
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
	$ETimeList[] = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
}

// $TatoFlg = $mySetting->TatoFlg; #多棟FLG
$WEBReceptType = $myBukken->WEBReceptType; #確定かどうか

// unset($mySetting);

$wDate = SPFWParameter::getValues('wDate'); #20171230追加
$vDate = $wDate;
$wTime = SPFWParameter::getValues('wTime'); #20171230追加
$wTimeFrom = $wDate . " " . $wTime; #20171230追加 メール用
$WakuTime = getWakuTime2($WakuPattern, $wTimeFrom);
$wTimeTo = $WakuTime['ETime']; // メール用
$editReservationCD = SPFWParameter::getValues('editReservationCD'); #20171230追加
$wUserMemo = SPFWParameter::getValues('wUserMemo'); #20171230追加
$wSecondChoice = SPFWParameter::getValues('wSecondChoice'); #20171230追加
$MyMenuCD = SPFWParameter::getValues('MyMenuCD'); #
$rKey = SPFWParameter::getValues('rKey'); #20171230追加
$work = SPFWParameter::getValues('work'); #20171230追加
$ticket = SPFWParameter::getValues('ticket'); #20171230追加
$flag = SPFWParameter::getValues('flag');

SPFWTemplate::setValue('work', '');

###リロード防止策
session_start();
#echo "<br>ticket".$ticket;
#echo "<br>_SESSION".$_SESSION['ticket'];
if ($ticket == "" || $ticket != $_SESSION['ticket']) {
	#echo "再読み込みしましたね。";
	#$URL = _MAIN_URL . 'top.php?rKey='.$rKey.'wLang='.$wLang;
	$URL = 'top.php?editBukkenCD='.$editBukkenCD.'&editBuildingCD='.$editBuildingCD.'&rKey=' . $rKey . '&wLang=' . $wLang;
	header('Location: ' . $URL);
	exit;
}
unset($_SESSION['ticket']);

########################################################
# 認証動作
########################################################
$myUser = new User($myDB);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1) {
	$URL = _MAIN_URL . 'login_form.php';
	header('Location: ' . $URL);
	exit;
}

if($flag == '3'){
	$IfDecline = true;
	$IfConfirm = false;
	$IfNotConfirm = false;
	
}else if($myUser->ConfirmFlg == '1'){
	$IfDecline = false;
	$IfConfirm = true;
	$IfNotConfirm = false;
}else{
	$IfDecline = false;
	$IfConfirm = false;
	$IfNotConfirm = true;
}

$Lastname = $myUser->LastName;
$TargetClientCD = $myUser->ClientCD;
if ($wUserMemo == "") { //20191107追加
	$Lastname = str_replace("<font color=red >確認</font>", "", $Lastname);
} else {
	$Lastname = str_replace("<font color=red >確認</font>", "", $Lastname);
	// $Lastname = "<font color=red >確認</font>" . $Lastname; //ここで$Lastnameが消えてしまって確認だけになってしまう。
	$myUser->LastName = $Lastname;
	$MemoAri = 1; #メモありでメール
}

$wID = $myUser->ID;
if($flag == '3'){
	$myUser->ReplyFlg = "3";
	$myUser->ConfirmFlg = "1";
}else{
	$myUser->ReplyFlg = "1";
	$myUser->ConfirmFlg = "1";
}
$myUser->Updated = "NOW()";
$myUser->Updater = $wID;

if (!$myUser->executeUpdate()) {
	trigger_error("Updating User Failed.", E_USER_ERROR);
}

$EMail = $myUser->EMail;
$UserCD = $myUser->UserCD;

$Address3 = $myUser->Address3; #棟記号
if (!$wMenuCD)
	$wMenuCD =  SPFWTools::decodePluralValue($myUser->MenuCD); #配列
########################################################
# 依頼会社情報取得　
########################################################
$myClient = new Client($myDB);

$ClientName = "";
$ClientTEL = "";
$BusinessHours = "";
$BusinessHoursNote = "";

if ($TargetClientCD > 0) {
	if (!$myClient->executeSelect("ClientCD = $TargetClientCD and MukouFlg = 0" , "") || $myClient->RecCnt != 1)
		trigger_error("Getting myClient Failed.", E_USER_ERROR);

	$ClientName = $myClient->ClientName;
	$ClientTEL = $myClient->TEL;
	$BusinessHours = $myClient->BusinessHours;
	$BusinessHoursNote = $myClient->BusinessHoursNote;

}


$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "MenuCD, ";
$sql .= "MinuteType ";

$myListObject->SelectSQL = $sql;
$sql = " FROM tMenuM";
$sql .= " WHERE MenuCD > 0 AND MukouFlg = 0 ";
if ($IfASP) {
	$sql .= " AND ClientCD = " . $TargetClientCD;
}

$myListObject->Condition = $sql;
$myListObject->Order = "MenuCD";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$MenuLoop = $myListObject->Rows;
for ($i = 0; $i < $MenuLoop; $i++) {
	$MyMenuCD = $myListObject->GetValue($i, 0);
	$MyMinuteType = $myListObject->GetValue($i, 1);
	

}
$IfNoOp = "TRUE";

########################################################
# スタイリストリストを取得
########################################################

$myListObject = new SPFWListObject($myDB);
$sql = "SELECT ";
$sql .= "StylistCD ";
$myListObject->SelectSQL = $sql;
$sql = " FROM tStylistM";
$sql .= " WHERE StylistCD > 0 AND MukouFlg = FALSE ";
// $sql .= " AND WakugoeFlg = 0 "; // 枠越は除く
if ($IfASP) {
	$sql .= " AND ClientCD = " . $TargetClientCD;
}
$myListObject->Condition = $sql;
$myListObject->Order = "StylistCD";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

unset($StylistCD);
unset($StylistName);
unset($StylistSelected);

$LinesLoop = $myListObject->Rows;
for ($i = 0; $i < $LinesLoop; $i++) {
	$StylistList[] = $myListObject->GetValue($i, 0);
}



$AkiWakuAMPMTime = getAkiWakuAMPMTime($myDB, $TargetClientCD, $editBukkenCD, $editBuildingCD, $MyMinuteType, $wDate, $wTime, $WakuSuu, $STimeList, $ETimeList, $MaxWakuSu, $UserCD);


if (!isset($AkiWakuAMPMTime["akiTimeFrom"])) {
	echo "ご指定の時間は予約が空いていません。お手数ですが、再度ご予約ください。<br><br>";
	echo "<a href='top.php?rKey=".$rKey."&editBukkenCD=".$editBukkenCD."&editBuildingCD=".$editBuildingCD."'>予約システムTOPへ</a>";
	exit;
} else {

	########################################################
	# 予約動作
	########################################################

	$myReservation = new Reservation($myDB);

	if($editBuildingCD){
		$Condition = "  BukkenCD = $editBukkenCD AND BuildingCD = '" . $editBuildingCD . "' and UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE";
	}else{
		$Condition = "  BukkenCD = $editBukkenCD AND BuildingCD IS NULL and UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE";
	}
	if (!$myReservation->executeSelect($Condition, "TimeFrom DESC"))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);

	if ($myReservation->RecCnt == 0) {
		$myReservation->ReservationCD = -1;
		$myReservation->ClientCD = $TargetClientCD;
		$myReservation->BukkenCD = $editBukkenCD;
		$myReservation->UserCD = $myUser->UserCD;
		if($editBuildingCD){
			$myReservation->BuildingCD = $editBuildingCD;
		}
		$Identifier = 'new';

	} else {
		$Identifier = 'kakutei';
		$wMenuCD = SPFWTools::decodePluralValue($myReservation->MenuCD); #変更なら上書き

		#変更前・前回受付日程表示
		$LastTimeFrom = $myReservation->TimeFrom;
		$LastJikan = $myReservation->Jikan;
		$LastShitei = $myReservation->Shitei;
		$LastShitei = $SHITEI[$LastShitei];
		$LastMemo = $LastJikan . $LastShitei . " " . $myReservation->UserMemo;
		$TaioLastMemo = $LastTimeFrom . " " . $LastMemo;

		$myReservation->Last2TimeFrom = $myReservation->LastTimeFrom;  	#前回予約日時を前々回予約日時にセット
		$myReservation->LastTimeFrom = $myReservation->TimeFrom;  		#現在予約日時を前回予約日時にセット
		$myReservation->LastMemo .= "\n" . date("Y-m-d H:i:s") . " [WEBから変更]" . $myReservation->UserMemo;	#メモを追加
		$myReservation->LastUpdated = $myReservation->Updated;
		$myReservation->LastUpdater = $myReservation->Updater;
	}

	$myReservation->StylistCD = $AkiWakuAMPMTime['akiStylistCD'];
	$myReservation->ClientCD = $TargetClientCD;
	$myReservation->UserCD = $myUser->UserCD;

	$DispTimeFrom = $AkiWakuAMPMTime['akiTimeFrom'];
	$DispTimeTo = substr($AkiWakuAMPMTime['akiTimeTo'], -6);

	$myReservation->TimeFrom = $AkiWakuAMPMTime['akiTimeFrom'];
	$myReservation->TimeTo 	=  $AkiWakuAMPMTime['akiTimeTo'];

	$myReservation->Jikan = null;
	$myReservation->Shitei = null;

	if ($Identifier == 'new') {
		$myReservation->MenuCD =  $myUser->MenuCD;
	} else {
		$myReservation->MenuCD = SPFWTools::encodePluralValue($wMenuCD);
	}

	#if ($wMemo) {
	#	$wMemo = $wMemo . "　" . $wwMemo;
	#} else {
	$wMemo = $wUserMemo; // メールで使用
	#}
	$myReservation->Status = 1;
	#$myReservation->FreeFlg = 0;
	$myReservation->UserMemo = $wUserMemo;
	// $myReservation->SecondChoice = $wSecondChoice;
	#$myReservation->Creator = '0';
	$myReservation->Updater = $UserCD;

	#webのｔReservationFには、FreeFlgを１にする
	#$myReservation->FreeFlg = '1';

	// 班
	$reserveHansu = 0;
	$YoyakuWakuIndex = 0;
	$YoyakuStart = $wTime;
	$YoyakuEnd = $wTimeTo;

	$WakuRangeArray = explode("-",$WakuRange);
	for ($i = 0; $i < $WakuSuu; $i++) {
		if ($STimeList[$i] <= $wTime and $wTime < $ETimeList[$i]) {
			$YoyakuStart = $STimeList[$i];
			$YoyakuEnd = $ETimeList[$i];
			$YoyakuWakuIndex = $i;
			break;
		}
	}

	$WakuRange = $MaxWakuSu;	
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
	$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $wDate . "', '%Y/%m/%d')";
	$sql .= " AND DATE_FORMAT(r.TimeFrom,'%H:%i') >= '$YoyakuStart' and DATE_FORMAT(r.TimeFrom,'%H:%i') <= '$YoyakuEnd'";

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
		$arrHanNo[$ID[$i]] 	= $myListObject->GetValue($i, 14);
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
	// for ($j = 0; $j < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $j++) {
	$j = $YoyakuWakuIndex;
	if(isset($WAKUPATTERN[$wWakuPattern]['AMPM'][$j])){
		$WakuName = $WAKUPATTERN[$wWakuPattern]['AMPM'][$j];
		$ban_rooms = 1;
		$Overflows = 0;
		$max_ban = ceil(${'wWaku' . $WakuName} / $wHansu);
		$limit_ban = ceil(${'wWaku' . $WakuName . 'ColSum'} / $wHansu);
		$passed_rooms = 0;
		$x = 0;
		// $ReserveCount[$j] = 0;

		for ($k = 0; $k < ${'wWaku' . $WakuName . 'ColSum'}; $k++) {#10,8,8
			$dis_ban = floor($k / $limit_ban) + 1;
			if($ban_rooms > $max_ban && $ban_rooms <= $limit_ban){
				if($Overflows < $wFrameOverflow){
					if(isset($Reserve[$WakuName][$x]) 
						&& (!$arrHanNo[$Reserve[$WakuName][$x]] || $arrHanNo[$Reserve[$WakuName][$x]] == $dis_ban)){
						// 辞退
						if(isset($UserData['ReplyFlg'][$Reserve[$WakuName][$x]]) && $UserData['ReplyFlg'][$Reserve[$WakuName][$x]] == '3'){
							// $ReserveCount[$j] ++;
							if($wArrangeType == '1'){
								$reserveHansu = $dis_ban;
								break;
							}
							$passed_rooms ++;
							$x ++;
						}else{
							$passed_rooms ++;
							$x ++;
						}
					}else{
						if($wArrangeType == '1'){
							$reserveHansu = $dis_ban;
							break;
						}
						// $ReserveCount[$j] ++;
					}
					$Overflows ++;
				}
			}elseif(isset($Reserve[$WakuName][$x]) 
				&& (!$arrHanNo[$Reserve[$WakuName][$x]] || $arrHanNo[$Reserve[$WakuName][$x]] == $dis_ban)){
				// 辞退
				if(isset($UserData['ReplyFlg'][$Reserve[$WakuName][$x]]) && $UserData['ReplyFlg'][$Reserve[$WakuName][$x]] == '3'){
					if($wArrangeType != '1'){
						$reserveHansu = $dis_ban;
						break;
					}
					$passed_rooms ++;
					$x ++;
				}else{
					if($wArrangeType == '1'){
						$bReservedRooms = 0;
						foreach($arrFloorReserveInfo as $floor => $FloorReserveInfo){
							if(date("Y-m-d", strtotime($FloorReserveInfo["wFloorDay"])) == date("Y-m-d", strtotime($wDate)) && $FloorReserveInfo["wFloorWaku"] == $WakuName){
								$bReservedRooms += intval($FloorReserveInfo["wFloorCols"]);
							}
						}
						if(($dis_ban - 1) * $max_ban + $ban_rooms > $bReservedRooms){
							$passed_rooms ++;
						}else{
							$passed_rooms ++;
							$x ++;
						}
					}else{
						$passed_rooms ++;
						$x ++;
					}
				}
			}elseif (${'wWaku' . $WakuName} > $passed_rooms) { //残った最大工事枠数分は空き
				if($wArrangeType != '1'){
					$reserveHansu = $dis_ban;
					break;
				}
				$passed_rooms ++;
			}else{
				if($Overflows < $wFrameOverflow){
					if($wArrangeType == '1'){
						$reserveHansu = $dis_ban;
						break;
					}
					// $ReserveCount[$j] ++;
					$Overflows ++;
				}
			}	
			$ban_rooms ++;
			if($ban_rooms > $limit_ban){
				$ban_rooms = 1;
				$Overflows = 0;
			}
		}
	}
	if($reserveHansu){
		$myReservation->HanNo = $reserveHansu;
	}

	$myReservation->Updated = "NOW()";

	if (!$myReservation->executeUpdate())
		trigger_error("Updating Reservation Failed.", E_USER_ERROR);

	if ($wLang == 1) { #英語のときの　日付表示を　　31/03/2016 に変更
		$wTimeFrom = substr($myReservation->TimeFrom, 8, 2) . "/" . substr($myReservation->TimeFrom, 5, 2) . "/" . substr($myReservation->TimeFrom, 0, 4) . " " . substr($myReservation->TimeFrom, 10, 6);
	}

	$wTimeFrom = $myReservation->TimeFrom;
	$WakuTime = getWakuTime($WakuPattern, $wTimeFrom);
	$Reservationtime = $WakuTime['STime'] . "～" . $WakuTime['ETime'];

	if ($wLang == 1) {
		$DispReservationDate = date('Y年m月d日', strtotime($wTimeFrom));
	} else {
		$DispReservationDate = date('Y年m月d日', strtotime($wTimeFrom));
	}
	$WeekList = array("日", "月", "火", "水", "木", "金", "土");
	$w2 = $WeekList[date('w', strtotime($wTimeFrom))];

	$ReserveQuery = $QUERY . "&r="  . $myReservation->ReservationCD;



	if ($WEBReceptType == "2") { //確定予約
		$Identifier = 'kakutei';
	} else { //第三希望まで
		$Identifier = 'new';
	}


	// #対応記録
	// $myTaio = new Taio($myDB);

	// $myTaio->TaioCD = "-1";
	// $myTaio->ClientCD = $TargetClientCD;
	// $myTaio->UserCD = $UserCD;

	// if ($MemoAri == 1) { #メモがある場合のみ送信489にメール
	// 	$myTaio->Category = "|3|7|"; #WEB受付+備考欄あり→確認する
	// 	$myTaio->TaioNotes = "[WEB予約 備考記述あり]" . $wUserMemo;
	// } else { #メモがある場合のみ送信 End
	// 	$myTaio->Category = "|7|"; #WEB受付
	// 	$myTaio->TaioNotes = "[WEB予約]";
	// }

	// $myTaio->LastTimeNittei = $TaioLastMemo;
	// #$myTaio->HearingNo = $tHearingNo + 1 ; #ヒアリングNoをカウントアップしない
	// $myTaio->Creator = $UserCD;
	// $myTaio->Updater = $UserCD;

	// if (!$myTaio->executeUpdate()) {
	// 	trigger_error("Updating Taio Failed.", E_USER_ERROR);
	// }
	// unset($myTaio);
	// #ヒアリングNoをカウントアップしない

	// insertReserveUpdatedHistory($myDB, $TargetClientCD, $UserCD, 1, $LastTimeFrom, $wTimeFrom, $UserCD, $MemoAri);

	$branchInfo = '';
	if($myBukken->BrancheCD){
		$myBranch = new Branche($myDB);
		if (!$myBranch->executeSelect("MukouFlg = FALSE AND BrancheCD = " . $myBukken->BrancheCD, "") || $myBranch->RecCnt != 1) {
		}else{
			$branchInfo = '　'.$myBranch->BrancheName.'メンテナンスセンター'.$myBranch->BrancheTEL;
		}
	}


	// //20170706末次。リロード対策。メール送信処理はreserve_confirm.phpから来た時のwork=1の時のみ通る
	if ($flag == '1') { // 予約を確定する
		if ($EMail) {
			########################################################
			# メール送信処理
			########################################################
			$wFromAddress  = "no-reply@489501.jp";
			$ID = $wID;
			if($wBuildingName){
				$Subject = "日程確定のお知らせ(".$MansionName." ".$wBuildingName.")";
			}else{
				$Subject = "日程確定のお知らせ(".$MansionName.")";
			}

			$Message = "日程が確定しました。ご確認お願いします。";
			$Message .= "\n登録日：".date("Y年m月d日");
			$Message .= "\n日程：".$DispReservationDate." ".$Reservationtime;
			$Message .= "\n".$wUserMemo;


			$Message .= "\n\n ==============================================================================";
			$Message .= "\nこのメールアドレスはお客様へのお知らせ専用です。";
			$Message .= "\nこのメールアドレスへ返信としてご質問をお送りいただいても回答できません。ご了承ください。";
			$Message .= "\nご質問やご不明な点がございましたら、下記までお問い合わせお願い申し上げます。";

			$ContactInfo = "\n".$ClientName."　".$ClientTEL."　営業時間：".$BusinessHours.$BusinessHoursNote;
			// $Message .= "\nホーチキ株式会社".$branchInfo."　営業時間：平日 ９：００～１７：３０（１２：００～１３：００を除く）";
			$Message .= $ContactInfo;

			$Headers = "From: " . mb_encode_mimeheader("消防設備点検予約システム", "ISO-2022-JP", "Q") . " < ".$wFromAddress."  >\n";

			if (!mb_send_mail($EMail, $Subject, $Message, $Headers, "-f" . $wFromAddress))
				trigger_error("ending Mail Failed. Please Look Up maillog.", E_USER_ERROR);

		} #EMailありEnd
	}else if($flag == '2'){ // 予約を変更する
		if ($EMail) {
			########################################################
			# メール送信処理
			########################################################
			$wFromAddress  = "no-reply@489501.jp";
			$ID = $wID;
			if($wBuildingName){
				$Subject = "日程変更のお知らせ(".$MansionName." ".$wBuildingName.")";
			}else{
				$Subject = "日程確定のお知らせ(".$MansionName.")";
			}

			$Message = "日程が変更されました。ご確認お願いします。";
			$Message .= "\n登録日：".date("Y年m月d日");
			$Message .= "\n日程：".$DispReservationDate." ".$Reservationtime;
			$Message .= "\n".$wUserMemo;


			$Message .= "\n\n ==============================================================================";
			$Message .= "\nこのメールアドレスはお客様へのお知らせ専用です。";
			$Message .= "\nこのメールアドレスへ返信としてご質問をお送りいただいても回答できません。ご了承ください。";
			$Message .= "\nご質問やご不明な点がございましたら、下記までお問い合わせお願い申し上げます。";

			$ContactInfo = "\n".$ClientName."　".$ClientTEL."　営業時間：".$BusinessHours.$BusinessHoursNote;
			// $Message .= "\nホーチキ株式会社".$branchInfo."　営業時間：平日 ９：００～１７：３０（１２：００～１３：００を除く）";
			$Message .= $ContactInfo;

			$Headers = "From: " . mb_encode_mimeheader("消防設備点検予約システム", "ISO-2022-JP", "Q") . " < ".$wFromAddress."  >\n";

			// if (!mb_send_mail($EMail, $Subject, $Message, $Headers, "-f" . $wFromAddress))
				// trigger_error("ending Mail Failed. Please Look Up maillog.", E_USER_ERROR);

		} #EMailありEnd
	}else if($flag == '3'){
		if ($EMail) {
			########################################################
			# メール送信処理
			########################################################
			$wFromAddress  = "no-reply@489501.jp";
			$ID = $wID;
			if($wBuildingName){
				$Subject = "辞退のお知らせ(".$MansionName." ".$wBuildingName.")";
			}else{
				$Subject = "辞退のお知らせ(".$MansionName.")";
			}

			$Message = "辞退で受付しました。次回のご協力よろしくお願いします。";
			$Message .= "\n登録日：".date("Y年m月d日");
			$Message .= "\n".$wUserMemo;
			$Message .= "\n\n==============================================================================";
			$Message .= "\nこのメールアドレスはお客様へのお知らせ専用です。";
			$Message .= "\nこのメールアドレスへ返信としてご質問をお送りいただいても回答できません。ご了承ください。";
			$Message .= "\nご質問やご不明な点がございましたら、下記までお問い合わせお願い申し上げます。";

			$ContactInfo = "\n".$ClientName."　".$ClientTEL."　営業時間：".$BusinessHours.$BusinessHoursNote;
			// $Message .= "\nホーチキ株式会社".$branchInfo."　営業時間：平日 ９：００～１７：３０（１２：００～１３：００を除く）";
			$Message .= $ContactInfo;

			$Headers = "From: " . mb_encode_mimeheader("消防設備点検予約システム", "ISO-2022-JP", "Q") . " < ".$wFromAddress."  >\n";

			if (!mb_send_mail($EMail, $Subject, $Message, $Headers, "-f" . $wFromAddress))
				trigger_error("ending Mail Failed. Please Look Up maillog.", E_USER_ERROR);

		} #EMailありEnd

	}

}



########################################################
# コンテンツ表示
########################################################
if ($wLang == 1) {
	$CNT_FILE = "reserve_finish_eng.tpl";
} else {
	$CNT_FILE = "reserve_finish_kakutei.tpl";
}
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, FALSE, $MyClientCD);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);


/***
 * 日付と時間枠（開始時間）を指定して空き時間を取得
 *
 *  @param	TargetDate: ex) 2019-06-09
 *  @param	TargetTime: ex) 09:00
 *  @param	WakuSuu: ex) 3
 *  @param	STimeList: ex) array 各枠の開始時間 [0] => 09:00 [1] => 13:00 [2] => 15:00
 *  @param	ETimeList: ex) array 各枠の終了時間 [0] => 12:00 [1] => 15:00 [2] => 17:00
 *  @return	AkiData:
 */
function getAkiWakuAMPMTime($myDB, $TargetClientCD, $editBukkenCD, $editBuildingCD, $MyMinuteType, $TargetDate, $TargetTime, $WakuSuu, $STimeList, $ETimeList, $MaxWakuSu,$loginUserCD = "")
{


	global $MINUTEUNIT;

	$TargetDate = date("Y-m-d", strtotime($TargetDate));
	$TargetWeekdayNo = date('w', strtotime($TargetDate)); // 選択した日の曜日番号

	########################################################
	# 基本設定呼び出し
	########################################################
	// $mySetting = new Setting($myDB);
	// if (!$mySetting->executeSelect("ClientCD = " . $TargetClientCD . " AND MukouFlg = FALSE", "")) {
	// 	trigger_error("Getting Stylist List Failed.", E_USER_ERROR);
	// }
	// $OpenTime = SPFWTools::decodePluralValue($mySetting->OpenTime);
	// $CloseTime = SPFWTools::decodePluralValue($mySetting->CloseTime);
	// $LunchTimeFrom = SPFWTools::decodePluralValue($mySetting->LunchTimeFrom);
	// $LunchTimeTo = SPFWTools::decodePluralValue($mySetting->LunchTimeTo);

	$OpenTime = ['09:00','09:00','09:00','09:00','09:00','09:00','09:00'];
	$CloseTime = ['18:00','18:00','18:00','18:00','18:00','18:00','18:00'];
	$LunchTimeFrom = ['12:00','12:00','12:00','12:00','12:00','12:00','12:00'];
	$LunchTimeTo = ['13:00','13:00','13:00','13:00','13:00','13:00','13:00'];



	$MyOpenTime = $OpenTime[$TargetWeekdayNo];
	$MyCloseTime = $CloseTime[$TargetWeekdayNo];
	$MyLunchTimeFrom = $LunchTimeFrom[$TargetWeekdayNo];
	$MyLunchTimeTo = $LunchTimeTo[$TargetWeekdayNo];

	$MyOpenTimeHour = intval(substr($MyOpenTime, 0, 2));
	$MyOpenTimeMinute = intval(substr($MyOpenTime, 3, 2));
	$MyCloseTimeHour = intval(substr($MyCloseTime, 0, 2));
	$MyCloseTimeMinute = intval(substr($MyCloseTime, 3, 2));

	$MyLunchTimeFromHour = substr($MyLunchTimeFrom, 0, 2);
	$MyLunchTimeFromMinute = substr($MyLunchTimeFrom, 3, 2);
	$MyLunchTimeToHour = substr($MyLunchTimeTo, 0, 2);
	$MyLunchTimeToMinute = substr($MyLunchTimeTo, 3, 2);

	// $WakuRange = $mySetting->WakuRange;
	$WakuRange = $MaxWakuSu; #6-4-4
	$WakuRangeArray = explode("-",$WakuRange);
// echo "<br> ".__LINE__." WakuRange :".$WakuRange;
// print_r($WakuRangeArray);


	unset($mySetting);

	########################################################
	# スタイリストリストを取得
	########################################################
/*
	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "StylistCD, ";
	$sql .= "NumberOfLines ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tStylistM";
	$sql .= " WHERE StylistCD > 0 AND MukouFlg = FALSE ";
	// $sql .= " AND (WakugoeFlg is NULL OR WakugoeFlg = 0)"; // 枠越は除く
	$sql .= " AND ClientCD = " . $TargetClientCD;

	$myListObject->Condition = $sql;
	$myListObject->Order = "StylistCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

	$StylistLoop = $myListObject->Rows;
	for ($i = 0; $i < $StylistLoop; $i++) {
		$StylistCD[$i] = $myListObject->GetValue($i, 0);
		$NumberOfLines[$i] = $myListObject->GetValue($i, 1);


		$LinesLoop += $NumberOfLines[$i];

		for ($j = 0; $j < $NumberOfLines[$i]; $j++)
			$StylistCDs[] = $StylistCD[$i];
	}
	unset($myListObject);
*/
	########################################################
	# 時刻LOOP
	########################################################
	for ($i = $MyOpenTimeHour; $i <= $MyCloseTimeHour; $i++) {
		for ($j = 0; $j < 60; $j += $MINUTEUNIT) {
			if (($i == $MyCloseTimeHour && $MyCloseTimeMinute <= $j) || $i > $MyCloseTimeHour)
				break;

			// 昼休みは除外する
			$tmptime = sprintf("%02d%02d", $i, $j);
			if (($MyLunchTimeFromHour . $MyLunchTimeFromMinute <= $tmptime) and ($tmptime < $MyLunchTimeToHour . $MyLunchTimeToMinute))
				break;

			$TimesHour[] = $i;
			$TimesMinute[] = $j;
			$Times[] = sprintf("%02d:%02d", $i, $j);
		}
	}

	// 予約いれたい時間がAMかPM1かPM2か...を判断
	for ($i = 0; $i < $WakuSuu; $i++) {
		if ($STimeList[$i] <= $TargetTime and $TargetTime < $ETimeList[$i]) {
			#echo "<br>★".$STimeList[$i]."～".$ETimeList[$i]." ".$WakuRangeArray[$i]; // 09:00～12:00 7
			$YoyakuStart = $STimeList[$i]; // 予約いれたい時間枠の開始時間
			$YoyakuEnd = $ETimeList[$i]; // 予約いれたい時間枠の終了時間
			$YoyakuWakuMax = $WakuRangeArray[$i]; // 予約いれたい時間枠の最大枠数
		}
	}

// echo "<br> ".__LINE__." Hensu :".$YoyakuStart;
// echo "<br> ".__LINE__." Hensu :".$YoyakuEnd;
// echo "<br> ".__LINE__." Hensu :".$YoyakuWakuMax;
// echo "<br> ".__LINE__." ここ :";


	########################################################
	# 予約リストを取得
	########################################################

	// 予約いれたい日、同じ時間枠の予約を取得する
	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "r.ReservationCD, ";	#0
	$sql .= "r.StylistCD, ";		#1
	$sql .= "r.UserCD, ";			#2
	$sql .= "r.TimeFrom, ";		#3
	$sql .= "r.TimeTo ";			#4

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF r, tUserM u";
	$sql .= " WHERE r.ReservationCD > 0 AND r.MukouFlg = FALSE AND r.UserCD = u.UserCD";
	$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
	$sql .= " AND date_format(r.TimeFrom, '%H:%i') >= '" . $YoyakuStart . "' AND date_format(r.TimeFrom, '%H:%i') < '" . $YoyakuEnd . "' ";
	$sql .= " AND r.Status = 1";
	$sql .= " AND u.ReplyFlg <> 3";
	$sql .= " AND r.ClientCD = " . $TargetClientCD;
	$sql .= " AND r.BukkenCD = " . $editBukkenCD;

	if($editBuildingCD){
		$sql .= " AND r.BuildingCD = " . $editBuildingCD;
	}else{
		$sql .= " AND r.BuildingCD IS NULL ";
	}

	if ($loginUserCD) // 自分の予約は除く（同じ時間に修正できる）
		$sql .= " AND r.UserCD != '$loginUserCD'";

	$myListObject->Condition = $sql;
	$myListObject->Order = "r.TimeFrom";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

	$ReservationLoop = $myListObject->Rows;
	for ($i = 0; $i < $ReservationLoop; $i++) {
		$ReservationCD = $myListObject->GetValue($i, 0);
		$StylistCD = $myListObject->GetValue($i, 1);
		#$UserCD = $myListObject->GetValue($i, 2);
		#$wTimeFrom = substr($myListObject->GetValue($i, 3), 11, 5); // 09:00, 11:00...
		$TimeFrom = $myListObject->GetValue($i, 3);
		$TimeTo = $myListObject->GetValue($i, 4);

		// $TimeFromYear = substr($TimeFrom, 0, 4);
		// $TimeFromMonth = substr($TimeFrom, 5, 2);
		// $TimeFromDay = substr($TimeFrom, 8, 2);
		// $TimeFromHour = substr($TimeFrom, 11, 2);
		// $TimeFromMinute = substr($TimeFrom, 14, 2);
		// $TimeToYear = substr($TimeTo, 0, 4);
		// $TimeToMonth = substr($TimeTo, 5, 2);
		// $TimeToDay = substr($TimeTo, 8, 2);
		// $TimeToHour = substr($TimeTo, 11, 2);
		// $TimeToMinute = substr($TimeTo, 14, 2);

		$TimeFromYear = date("Y", strtotime($TimeFrom));
		$TimeFromMonth = date("n", strtotime($TimeFrom));
		$TimeFromDay = date("j", strtotime($TimeFrom));
		$TimeFromHour = date("H", strtotime($TimeFrom));
		$TimeFromMinute = date("i", strtotime($TimeFrom));
		$TimeToYear = date("Y", strtotime($TimeTo));
		$TimeToMonth = date("n", strtotime($TimeTo));
		$TimeToDay = date("j", strtotime($TimeTo));
		$TimeToHour = date("H", strtotime($TimeTo));
		$TimeToMinute = date("i", strtotime($TimeTo));

		#if ($ReserveTime > 0)
		#	$TimeRequired = $ReserveTime;
		#else
		// $TimeRequired = (mktime($TimeToHour, $TimeToMinute, 0, $TimeToMonth, $TimeToDay, $TimeToYear) - mktime($TimeFromHour, $TimeFromMinute, 0, $TimeFromMonth, $TimeFromDay, $TimeFromYear)) / 60;
		// $TimeUnits = $TimeRequired / $MINUTEUNIT;
		// #echo "<br>TimeRequired".$TimeRequired." TimeUnits:".$TimeUnits;

		// for ($j = 0; $j < $TimeUnits; $j++) {
		// 	$MyTime = date('H:i', mktime($TimeFromHour, $TimeFromMinute + $j * $MINUTEUNIT, 0, $TimeFromMonth, $TimeFromDay, $TimeFromYear));
		// 	// $Reservation[$MyTime][$StylistCD]["ReservationCD"][] = $ReservationCD;
		// 	// $Reservation[$MyTime]["ReservationCD"][] = $ReservationCD;
		// }

		#$Reservation[$TimeFrom][$StylistCD]["ReservationCD"][] = $ReservationCD;
		$MyTime = date('H:i', mktime($TimeFromHour, $TimeFromMinute + $MINUTEUNIT, 0, $TimeFromMonth, $TimeFromDay, $TimeFromYear));		
	}
	unset($myListObject);

	#echo "<pre>";
	#var_dump($Reservation);
	#echo "</pre>";

	$ReturnData = array();

	$YoyakuCntCD = array();
	#$YoyakuCnt = 0; // 予約済カウント用
	$TimesLoop = count($Times);
	for ($i = 0; $i < $TimesLoop; $i++) {
		if ($YoyakuStart <= $Times[$i] and $Times[$i] < $YoyakuEnd) { // 予約したい時間帯のみ
						if(!$MyTime){
							if(isset($Times[$i]))
								$MyTime = $Times[$i];
							else
								$MyTime = isset($STimeList[0])?$STimeList[0]:$TargetTime;
						}
						$YoyakuOKTimeFrom = $TargetDate . " " . $MyTime;
						$YoyakuOKTimeFrom = date("Y-m-d H:i", strtotime($YoyakuOKTimeFrom));
						$YoyakuDateTimeEnd = date("Y-m-d H:i", strtotime($TargetDate . " " . $YoyakuEnd));
						if($YoyakuOKTimeFrom > $YoyakuDateTimeEnd)
							$YoyakuOKTimeFrom = $YoyakuDateTimeEnd;

						$YoyakuOKStylistCD = 1;
						$TimeRequired = $MINUTEUNIT * $MyMinuteType;
						$YoyakuOKTimeTo = date("Y-m-d H:i", strtotime($YoyakuOKTimeFrom . "+" . $TimeRequired . " minute"));

						// echo "<br> ".__LINE__." MINUTEUNIT :".$MINUTEUNIT;
						// echo "<br> ".__LINE__." MyMinuteType :".$MyMinuteType;
						// echo "<br> ".__LINE__." TimeRequired :".$TimeRequired;

						$ReturnData["akiTimeFrom"] = $YoyakuOKTimeFrom;
						$ReturnData["akiTimeTo"] = $YoyakuOKTimeTo;
						$ReturnData["akiStylistCD"] = $YoyakuOKStylistCD;
						break; // ループを抜ける
		}
	}

	#echo "<pre>";
	#var_dump($ReturnData);
	#echo "</pre>";

	return $ReturnData;
}

function insertReserveUpdatedHistory($myDB, $ClientCD, $UserCD, $KojiCategory, $LastTimeFrom, $TimeFrom, $AdminCD, $MemoAri)
{
	//物件の受付締切日を取得(tSettingM)
	$mySetting = new Setting($myDB);
	if (!$mySetting->executeSelect("ClientCD = " . $ClientCD . " AND MukouFlg = FALSE", "")) {
		$ErrorString = array();
		$ErrorString[] = "tSettingM情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}

	$TatoFlg = $mySetting->TatoFlg;				//多棟フラグ
	$YoyakuEndDate = $mySetting->YoyakuEndDate; //受付締切日

	unset($mySetting);


	//多棟の場合、棟の受付締切日を参照する
	if ($TatoFlg) {
		//tUserMから棟記号を取得
		$myUser = new User($myDB);
		if (!$myUser->executeSelect("ClientCD = " . $ClientCD . " AND UserCD = '" . $UserCD . "' AND MukouFlg = FALSE", "")) {
			$ErrorString = array();
			$ErrorString[] = "tUserM情報の抽出に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

		$ToName = $myUser->Address3; //棟記号

		unset($myUser);


		//Tatoにアクセスして棟の受付締切日を取得
		//※reserve_detail.phpにて以下ファイルincludeを忘れずに。
		//include_once _CLS_DIR . "SPUSTato.cls";
		$myTato = new Tato($myDB);
		if (!$myTato->executeSelect("ClientCD = " . $ClientCD . " AND ToName = '" . $ToName . "' AND MukouFlg = FALSE", "")) {
			$ErrorString = array();
			$ErrorString[] = "tTatoM情報の抽出に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

		$YoyakuEndDate = $myTato->YoyakuEndDate; //棟の受付締切日

		unset($myTato);
	}

	//「今日」が受付締切日を超えている場合、tReserveUpdatedHistoryFにINSERT
	//※reserve_detail.phpにて以下ファイルincludeを忘れずに。
	//include_once _CLS_DIR . "SPUSReserveUpdatedHistory.cls";
	$Today = date('Y-m-d'); //$YoyakuEndDateがY-m-dなので、同じフォーマットで比較する
	$Today = new DateTime($Today);
	$YoyakuEndDate = new DateTime($YoyakuEndDate);

	if ($Today > $YoyakuEndDate) {
		$myReserveUpdatedHistory = new ReserveUpdatedHistory($myDB);
		$myReserveUpdatedHistory->ReserveUpdatedHistoryCD = "-1";
		$myReserveUpdatedHistory->ClientCD = $ClientCD;
		$myReserveUpdatedHistory->UserCD = $UserCD;
		$myReserveUpdatedHistory->KojiCategory = $KojiCategory;
		$myReserveUpdatedHistory->LastTimeFrom = $LastTimeFrom;
		$myReserveUpdatedHistory->TimeFrom = $TimeFrom;
		if ($MemoAri == 1) { #メモがある場合のみ送信489にメール
			$myReserveUpdatedHistory->Category = "|3|7|"; #WEB受付+備考欄あり→確認する
		} else { #メモがある場合のみ送信 End
			$myReserveUpdatedHistory->Category = "|7|"; #WEB受付
		}

		$myReserveUpdatedHistory->Creator = $AdminCD;
		$myReserveUpdatedHistory->Updater = $AdminCD;

		if (!$myReserveUpdatedHistory->executeUpdate()) {
			$ErrorString = array();
			$ErrorString[] = "受付締切後の変更履歴の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}
		unset($myReserveUpdatedHistory);
	}


	return;
}
