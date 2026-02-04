<?php
include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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


// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


$wLang = SPFWParameter::getValues('wLang');
$editReservationCD = SPFWParameter::getValues('editReservationCD');

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
if($editBuildingCD){
	$wBuildingName 	= $myBuilding->BuildingName;
	$WakuPattern 	= $myBuilding->WakuPattern;
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

$KetteiHaifuDate = $mySetting->KetteiHaifuDate;

########################################################
# パラメータ取得
########################################################
session_start();
$_SESSION['ticket'] = md5(uniqid() . mt_rand());
$ticket = htmlspecialchars($_SESSION['ticket'], ENT_QUOTES);
SPFWTemplate::setValue('ticket', $ticket);

$wID = SPFWParameter::getValues('wID'); #20171230追加
$wUserMemo = SPFWParameter::getValues('wUserMemo');
$wwUserMemo = nl2br(SPFWParameter::getValues('wUserMemo')); // 表示用
$wSecondChoice = SPFWParameter::getValues('wSecondChoice'); #20171230追加
$wYear = SPFWParameter::getValues('wYear'); #20171230追加
$wMonth = SPFWParameter::getValues('wMonth'); #20171230追加
$wDay = SPFWParameter::getValues('wDay'); #20171230追加
$wDate = SPFWParameter::getValues('wDate'); #20171230追加
$MyMenuCD = SPFWParameter::getValues('MyMenuCD'); #|12|など
/*
	$wDate1 = SPFWParameter::getValues('wDate1');
	$wTime1 = SPFWParameter::getValues('wTime1');
	$wDate2 = SPFWParameter::getValues('wDate2');
	$wTime2 = SPFWParameter::getValues('wTime2');
	$wDate3 = SPFWParameter::getValues('wDate3');
	$wTime3 = SPFWParameter::getValues('wTime3');

	if($wDate1 != ''){
		$wTime1StartArr = explode('～', $wTime1);
		$wTime = $wTime1StartArr[0];
	} else if($wDate2 != '') {
		$wTime2StartArr = explode('～', $wTime2);
		$wTime = $wTime2StartArr[0];
	} else if($wDate3 != '') {
		$wTime3StartArr = explode('～', $wTime3);
		$wTime = $wTime3StartArr[0];
	}

	$wSecondChoice = $wDate1 . ' ' . $wTime1 . ',' . $wDate2 . ' ' . $wTime2 . ',' . $wDate3 . ' ' . $wTime3;
*/
$wTime = SPFWParameter::getValues('wTime'); #20171230追加


if (!$wTime) {
	$IfErr = true;
	include_once("reserve_form.php");
	exit;
}

$datetime = new DateTime();
$datetime->setDate($wYear, $wMonth, $wDay);
$week = array("日", "月", "火", "水", "木", "金", "土");
$w = (int)$datetime->format('w');
$weekday = $week[$w];



/*
	$date1Str = ($wDate1 != '') ? date('Y年n月j日', strtotime($wDate1)) : '';
	$niChi1Str = ($wDate1 != '') ? '(' . $week[date('w', strtotime($wDate1))] . ')' : '';
	$date2Str = ($wDate2 != '') ? date('Y年n月j日', strtotime($wDate2)) : '';
	$niChi2Str = ($wDate2 != '') ? '(' . $week[date('w', strtotime($wDate2))] . ')' : '';
	$date3Str = ($wDate3 != '') ? date('Y年n月j日', strtotime($wDate3)) : '';
	$niChi3Str = ($wDate3 != '') ? '(' . $week[date('w', strtotime($wDate3))] . ')' : '';
*/
$myUser = new User($myDB);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

$IfConfirm = false;
$IfNotConfirm = true;

if ($myUser->UserCD == -1) {
	showSorryPage(_ILLEGAL_ACCESS);
}else {
	if($myUser->ConfirmFlg == '1'){
		$IfConfirm = true;
		$IfNotConfirm = false;
	}else{
		$IfConfirm = false;
		$IfNotConfirm = true;
	}
}
#$Address3 = $myUser->Address3; #多棟日程記号

//20170706reserve_finish後、ブラウザバックから来た時にエラーになるため、DBのReservationCDをとっている
$myReservation = new Reservation($myDB);

if($editBuildingCD){
	if (!$myReservation->executeSelect("BukkenCD = $editBukkenCD AND UserCD = '" . $myUser->UserCD . "' AND BuildingCD = '" . $editBuildingCD . "' AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", ""))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
}else{
	if (!$myReservation->executeSelect("BukkenCD = $editBukkenCD AND UserCD = '" . $myUser->UserCD . "' AND BuildingCD IS NULL AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", ""))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
}

$ReservationCD = $myReservation->ReservationCD;
if ($editReservationCD != $ReservationCD) {
	$editReservationCD = $ReservationCD;
	SPFWTemplate::dropValue('editReservationCD');

	SPFWTemplate::setValue('editReservationCD', $editReservationCD);
}

unset($myReservation);

for ($i = 0; $i < count($WAKUPATTERN[$WakuPattern]['AMPM']); $i++) {
	if (strtotime($WAKUPATTERN[$WakuPattern]['StartTime'][$i]) <= strtotime($wTime) and strtotime($wTime) <  strtotime($WAKUPATTERN[$WakuPattern]['EndTime'][$i])) {
		$wOKTimeName = $WAKUPATTERN[$WakuPattern]['StartTime'][$i] . "～" . $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
	}
}

$SecondChoice = str_replace("NULL", "", $SecondChoice);

SPFWTemplate::setValue("work", "1");

########################################################
# コンテンツ表示
########################################################

## 予約可能数を表示（確認）するとき使います。20110821
##echo "OKTimesLoop".$OKTimesLoop ;

if ($wLang == 1) {
	$CNT_FILE = "reserve_confirm_eng.tpl";
} else {
	$CNT_FILE = "reserve_confirm_kakutei.tpl";
}

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, FALSE, $MyClientCD);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
?>
