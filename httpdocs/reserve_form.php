<?php
	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWListObjectNoCount.cls"; #GroupBYを無効にしているクラス
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSStylist.cls";
include_once _CLS_DIR . "SPUSSetting.cls";
include_once _CLS_DIR . "SPUSMenu.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSCalendar.cls";
include_once _CLS_DIR . "SPFWParameter.cls";

include_once "./include/common.php";

$rKey = SPFWParameter::getValues('rKey');

// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照
if ($lang == 'ja') $Ifjp = TRUE; #datapikckerの制御

#利用ワード
$reserve_form1 = $WORD[$lang]['reserve_form.1']; #
$reserve_form2 = $WORD[$lang]['reserve_form.2']; #*は入力必須項目です。
$reserve_form3 = $WORD[$lang]['reserve_form.3']; #１.日付選択 *
$reserve_form4 = $WORD[$lang]['reserve_form.4']; #
$reserve_form5 = $WORD[$lang]['reserve_form.5']; #
$reserve_form6 = $WORD[$lang]['reserve_form.6']; #
$reserve_form7 = $WORD[$lang]['reserve_form.7']; #前の月
$reserve_form8 = $WORD[$lang]['reserve_form.8']; #
$reserve_form9 = $WORD[$lang]['reserve_form.9']; #
$reserve_form10 = $WORD[$lang]['reserve_form.10']; #
$reserve_form11 = $WORD[$lang]['reserve_form.11']; #
$reserve_form12 = $WORD[$lang]['reserve_form.12']; #休…休工日
$reserve_form13 = $WORD[$lang]['reserve_form.13']; #
$reserve_form14 = $WORD[$lang]['reserve_form.14']; #
$reserve_form15 = $WORD[$lang]['reserve_form.15']; #
$reserve_form16 = $WORD[$lang]['reserve_form.16']; #３.第二希望入力
$reserve_form17 = $WORD[$lang]['reserve_form.17']; #
$reserve_form18 = $WORD[$lang]['reserve_form.18']; #
$reserve_form19 = $WORD[$lang]['reserve_form.19']; #４.ご要望
$reserve_form20 = $WORD[$lang]['reserve_form.20']; #
$reserve_form21 = $WORD[$lang]['reserve_form.21']; #
$reserve_form22 = $WORD[$lang]['reserve_form.22']; #
$reserve_form23 = $WORD[$lang]['reserve_form.23']; #戻る
$reserve_form24 = $WORD[$lang]['reserve_form.24']; #戻る
$reserve_form25 = $WORD[$lang]['reserve_form.25']; #戻る
$reserve_form26 = $WORD[$lang]['reserve_form.26']; #戻る
$reserve_form27 = $WORD[$lang]['reserve_form.27']; #戻る


$top1 = $WORD[$lang]['top.1']; #号室
$logout1 = $WORD[$lang]['logout.1']; #ログアウト
$finish2 = $WORD[$lang]['finish.2']; #予約システムTOPへ
$finish3 = $WORD[$lang]['finish.3']; #予約TOP

$steppng = 'step2.png';
if ($lang <> 'ja') $steppng = 'step2_en.png';
$stepoppng = 'step2-op.png';
if ($lang <> 'ja') $stepoppng = 'step2-op_en.png';
########################################################
# クライアント取得
########################################################
$wClientID = getClientID2();  // URLからClientID取得 common.php
$wClientCD = getClientCD2($myDB);

$myClient = new Client($myDB);
if (!$myClient->executeSelect("ID = '" . $wClientID . "' AND MukouFlg = FALSE ", "") || $myClient->RecCnt != 1) {
	trigger_error("Getting Client Failed.", E_USER_ERROR);
}
$wClientCD = $myClient->ClientCD;
unset($myClient);

$TargetClientCD = $wClientCD;


########################################################
# マンション名取得
########################################################
$mySetting = new Setting($myDB);
if (!$mySetting->executeSelect(" ClientCD = $wClientCD AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
	unset($myTemplate);
	exit;
}
$MansionName = $mySetting->MansionName;

$WakuPattern = $mySetting->WakuPattern;

$WakuSuu =  count($WAKUPATTERN[$WakuPattern]['AMPM']);
for ($i = 0; $i < $WakuSuu; $i++) {
	$STimeList[] = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
	$ETimeList[] =  $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
}


$WakuRange = $mySetting->WakuRange; #6-4-4
$ReserveFrom = $mySetting->ReserveFrom;
$ReserveTo = $mySetting->ReserveTo;

$SenyuStartDate = $mySetting->SenyuStartDate;
$SenyuStartDateConvert = $DispSenyuStartDateConvert = $SenyuStartDate;

$wSenyuStartDate = $SenyuStartDate;
$SenyuEndDate = $mySetting->SenyuEndDate;
$SenyuEndDateConvert = $DispSenyuEndDateConvert = $SenyuEndDate;
$wSenyuEndDate = $SenyuEndDate;
$usedatepicker = 'use-date-picker';
$checkInput = '<button type="button" onclick="checkInput()" class="finish-btn"> __reserve_form22__ </button>';

if ($lang <> 'ja') {
	$MansionName = $mySetting->MansionNameEn; #★Multilingual
	$DispSenyuStartDateConvert = date('d-m-Y', strtotime($SenyuStartDate));
	$DispSenyuEndDateConvert = date('d-m-Y', strtotime($SenyuEndDate));
	$usedatepicker = 'use-date-picker-en';
	$checkInput = '<button type="button" onclick="checkInputen()" class="finish-btn"> __reserve_form22__ </button>';
	#	$DispSetteiHoliday = date('d/m/Y',$SetteiHoliday);;
}

$SenyuStartDateJS = json_encode($SenyuStartDate);

$KyoyuEndDate = $mySetting->KyoyuEndDate;

if($KyoyuEndDate == $SenyuStartDate){
	$IfSameDate = TRUE;
}

//$WakuRangeを配列にする
$WakuRangeArray = explode("-", $WakuRange);

###20110718add　ここどうするか☆
$Waku = $mySetting->Waku - 1;
$lowWaku = $Waku - 2;

$CancelTo = $mySetting->CancelTo;
$OpenTime = SPFWTools::decodePluralValue($mySetting->OpenTime);
$CloseTime = SPFWTools::decodePluralValue($mySetting->CloseTime);
$LunchTimeFrom = SPFWTools::decodePluralValue($mySetting->LunchTimeFrom);
$LunchTimeTo = SPFWTools::decodePluralValue($mySetting->LunchTimeTo);
$Holiday = SPFWTools::decodePluralValue($mySetting->Holiday);
$FreeFlg = $mySetting->FreeFlg;

$HolidayJS = json_encode($Holiday);

$MINUTEUNIT = $mySetting->MinuteUnit;
$MINUTETYPE = $mySetting->getTimeArray($MINUTEUNIT);

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

$TatoFlg = $mySetting->TatoFlg; #多棟FLG

unset($mySetting);

########################################################
# dummy取得　※専有部と共用部の日程がかぶってたときの対応
########################################################
if($IfSameDate){
	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "UserCD, ";
	$sql .= "TimeFrom, ";
	$sql .= "TimeTo ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF";
	$sql .= " WHERE ClientCD = " . $TargetClientCD . " AND UserCD = 'dummyXX' AND MukouFlg = FALSE";
	//あいまい検索
	$sql .= " AND TimeFrom LIKE '" . $KyoyuEndDate . "%'";

	$myListObject->Condition = $sql;
	$myListObject->Order = "UserCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

	$DummyLoop = $myListObject->Rows;

	for ($i = 0; $i < $DummyLoop; $i++) {
		$DummyUserCD[$i] = $myListObject->GetValue($i, 0);
		$DummyTimeFrom[$i] = $myListObject->GetValue($i, 1);
		$DummyTimeFrom[$i] = substr($DummyTimeFrom[$i], 10, 6);
		$DummyTimeFrom[$i] = str_replace(" ", "", $DummyTimeFrom[$i]);
		$DummyTimeTo[$i] = $myListObject->GetValue($i, 2);
		$DummyTimeTo[$i] = substr($DummyTimeTo[$i], 10, 6);
		$DummyTimeTo[$i] = str_replace(" ", "", $DummyTimeTo[$i]);
	}

	unset($myListObject);
}

$DummyCount = $DummyLoop;

####20150619　時間帯ごとの工事枠を取得して、枠越えの感知する。 →　工事枠という概念はなく、工事班に枠越え（班）があるだけ

$WakuSuu = count($WAKUPATTERN[$WakuPattern]['AMPM']);
$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][0];
$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$WakuSuu - 1];

$TimeLoop  = $WakuSuu;
for ($i = 0; $i < $TimeLoop; $i++) { #時間選択し
	$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
	$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
	$wTime[$i] = $sSTime . "～" . $sETime;

	for($j = 0; $j < $DummyLoop; $j++){
		if(($sSTime <= $DummyTimeFrom[$j]) && ($DummyTimeTo[$j] <= $sETime)){
			$WakuRangeArray[$i] = $WakuRangeArray[$i] - 1;
		}
	} 
	
}

//$WakuRangeArrayで要素が0じゃないもののkeyから初日の時間帯を取得する
foreach($WakuRangeArray as $key => $value){
	if($value != 0){
		$WakuKey = $key;
		$wTimeOK[] = $wTime[$WakuKey];
	}
}

// foreach($wTime as $key => $value){
// 	foreach($wTimeOK as $key2 => $value2){
// 		if($value == $value2){
// 			unset($wTime[$key]);
// 		}
// 	}
// }

// echo "<br>専有部初日（これが選ばれたらwTimeOKを使う<br>";
// var_dump($SenyuStartDate);
// echo "<br>";

// echo "<br>初日の申し込みできる時間帯<br>";
// var_dump($wTimeOK);
// echo "<br>";

$TimeOKLoop = count($wTimeOK);

########################################################
# 入力チェック
########################################################
if ($rKey == NULL) {
	$URL = _MAIN_URL . 'login_form.php';
	header('Location: ' . $URL);
	exit;
}
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

$loginUserCD = $myUser->UserCD; //今ログインしているUserCD
$wID = $myUser->ID; //今ログインしているUserCD
$MyMenuCD = SPFWTools::decodePluralValue($myUser->MenuCD)[0]; //今ログインしているUserCDの工事内容 |11|
///お客様情報が入ってなかったらformへ戻す20170621
$wTEL = $myUser->TEL;
$wLastName = $myUser->LastName;

$Address3 = $myUser->Address3; #ユーザの棟記号

if ($wLastName == "" or $wTEL == "") {
	$URL = _MAIN_URL . $wClientID . '/form.php?rKey=' . $rKey . '&wLang=' . $wLang . '&btnflg=1';
	header('Location: ' . $URL);
	exit;
}
########################################################
# 多棟の場合 ユーザの属する棟の専有部期間に変換
########################################################

if ($TatoFlg == "1" and $Address3 != NULL) {
	$ToData = getToData($myDB, $wClientCD);
	for ($x = 0; $x < count($ToData); $x++) {
		if ($Address3 == $ToData['ToName'][$x]) {
			$SenyuStartDate = $ToData['SenyuStartDate'][$x];
			$SenyuEndDate = $ToData['SenyuEndDate'][$x];
			$wSenyuStartDate = $ToData['SenyuStartDate'][$x];
			$wSenyuEndDate = $ToData['SenyuEndDate'][$x];

			// datepicker用
			$SenyuStartDateConvert = $SenyuStartDate;
			$SenyuEndDateConvert = $SenyuEndDate;
			break;
		}
	}
}


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
if (!$myReservation->executeSelect("ClientCD = '" . $wClientCD . "' AND UserCD = '" . $myUser->UserCD . "' AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", "")) {
	trigger_error("Getting Reservation Failed.", E_USER_ERROR);
} else {
	$editReservationCD = $myReservation->ReservationCD;
	$wSecondChoice = $myReservation->SecondChoice;
	$wUserMemo = $myReservation->UserMemo; // 20220519edit 中西さんより MemoからUserMemoカラムに変更
	$wTimeFrom = $myReservation->TimeFrom; #ここ間違っている可能性あり★
}

if ($myReservation->RecCnt == 1 && !($editReservationCD > 0))
	$IfAlready = TRUE;

$IfYouCan = !$IfAlready;
if ($vRes == NULL) {
	$myReservation = new Reservation($myDB);

	if (!$myReservation->executeSelect("ClientCD = '" . $wClientCD . "' AND UserCD = '" . $myUser->UserCD . "' AND ReservationCD = " . intval($editReservationCD) . " AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", ""))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);

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

		$DispwTimeFrom = date('Y年m月d日', strtotime($wTimeFrom));
		$QUERY .= "&vRes=t&r=" . $editReservationCD;
		SPFWTemplate::setValue('vRes', 't');
		SPFWTemplate::setValue('wDate', $wDate);
		$wGoYear = $wYear;
		$wGoMonth = $wMonth;
	}
} else if ($editReservationCD > 0)
	$QUERY .= "&vRes=t&r=" . $editReservationCD;


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

	$mySetting = new Setting($myDB);
	if (!$mySetting->executeSelect("ClientCD = " . $TargetClientCD . " AND MukouFlg = FALSE", "")) {
		$ErrorString = array();
		$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}

	if ($TatoFlg == "1" and $Address3 != NULL) {
		$SenyuEndDate = $wSenyuEndDate;
	} else {
		$SenyuEndDate = $mySetting->SenyuEndDate;
	}

	$SenyuEndDateINI = date('Y/m/d', strtotime($SenyuEndDate));

	$vDate = $SenyuEndDateINI;
}

$WeekList = array("日", "月", "火", "水", "木", "金", "土");
///次の月や前の月、日付を選択した際に専有部の日程が表示されないので対応20170707

$mySetting = new Setting($myDB);
if (!$mySetting->executeSelect("ClientCD = " . $TargetClientCD . " AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
	unset($myTemplate);
	exit;
}

//専有部日時をわけて表示 英語分岐対応必要★
// $wSenyuEndDate 	= $mySetting->SenyuEndDate;
// $wSenyuStartDate = $mySetting->SenyuStartDate;

$DispSenyuStartDate = date('Y年m月d日', strtotime($wSenyuStartDate));
$DispSenyuEndDate = date('Y年m月d日', strtotime($wSenyuEndDate));
$DispWeek = '<th bgcolor="#ffc0cb">日</th><th bgcolor="#ffffff">月</th><th bgcolor="#ffffff">火</th><th bgcolor="#ffffff">水</th><th bgcolor="#ffffff">木</th><th bgcolor="#ffffff">金</th><th bgcolor="#bde0ff">土</th>';
if ($lang <> 'ja') { #★★★
	$DispwTimeFrom = date('d/m/Y', strtotime($wTimeFrom));
	$DispSenyuStartDate = date('d/m/Y', strtotime($wSenyuStartDate));
	$DispSenyuEndDate = date('d/m/Y', strtotime($wSenyuEndDate));
	$WeekList = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	$DispWeek = '<th bgcolor="#ffc0cb">Sun</th><th bgcolor="#ffffff">Mon</th><th bgcolor="#ffffff">Tue</th><th bgcolor="#ffffff">Wed</th><th bgcolor="#ffffff">Thu</th><th bgcolor="#ffffff">Fri</th><th bgcolor="#bde0ff">Sat</th>';
}




unset($mySetting);
$w4 = $WeekList[date('w', strtotime($wSenyuStartDate))];
$w5 = $WeekList[date('w', strtotime($wSenyuEndDate))];


########################################################
# 休日設定リストを取得
########################################################


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
$sql .= " AND WakugoeFlg = 0 ";
$sql .= " AND ClientCD = " . $TargetClientCD;

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
	#$StylistSelected[] = (!$wFreeFlg && $wStylistCD > 0 && $myListObject->GetValue($i, 0) == $wStylistCD) ? ' selected' : NULL;
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
$sql .= " WHERE ClientCD = " . $wClientCD . " AND MenuCD > 0 AND MukouFlg = FALSE";
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
if (($editReservationCD > 0) or $wYear != "") {
	$IfNokara = true;
} else {
	$IfKara = true;
}



########################################################
# カレンダー作成1
########################################################

$SenyuEndDate = date('Y/m/d', strtotime($SenyuEndDate));

$SenyuStartDate = date('Y/m/d', strtotime($SenyuStartDate));
$Toweek = $WeekList[date('w', $SenyuEndDate)];
$Fromweek = $WeekList[date('w', $SenyuStartDate)];

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
	$CNT_FILE = "reserve_form.tpl";
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

#20200721 第三希望方式に変更するにあたり、全期間の休工日を取得するため、以下の条件はコメントアウトする
#	if ($MYSQL)
#		$sql .= " AND date_format(Day, '%Y/%m/%d') >= date_format('" . $FirstDate . "', '%Y/%m/%d')";
#	else
#		$sql .= " AND Day::DATE >= '" . $FirstDate . "'";
#	if ($MYSQL)
#		$sql .= " AND date_format(Day, '%Y/%m/%d') <= date_format('" . $EndDate . "', '%Y/%m/%d')";
#	else
#		$sql .= " AND Day::DATE <= '" . $EndDate . "'";

$sql .= " AND StylistCD IS NULL";
$sql .= " AND TimeFrom IS NULL";
$sql .= " AND TimeTo IS NULL";
$sql .= " AND ClientCD = " . $TargetClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "CalendarCD";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Calendar List Failed.", E_USER_ERROR);

$ShopHolidayLoop = $myListObject->Rows;
for ($i = 0; $i < $ShopHolidayLoop; $i++) {
	$TempDay[$i] = $myListObject->GetValue($i, 0);
	$TempDay[$i] = substr($TempDay[$i], 0, 10);
	$SetteiHoliday[] = $TempDay[$i]; #JavaScript 次へボタンで使用
	$NoWorkDays[$i] = $TempDay[$i];
	$NoWorkDaysEn[$i] = date('d-m-Y', strtotime($TempDay[$i]));

	$TempDay[$i] = str_replace('-', '/', $TempDay[$i]);
	$ShopHoliday[$TempDay[$i]] = TRUE;
}
unset($myListObject);

$NoWorkDaysJS = json_encode($NoWorkDays);
$NoWorkDaysEnJS = json_encode($NoWorkDaysEn);
// var_dump($NoWorkDaysEn);

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
			$DispThisMonth = date('Y年m月', strtotime($TempDate));
			if ($lang <> 'ja') $DispThisMonth = date('m/Y', strtotime($TempDate)); #★★
			$IfDateValue[$j] = TRUE;

			#$IfOK1[$j] = $IfOK2[$j] = (!$IfDateValue[$j] || $ShopHoliday[$FullDate[$j]] || $Holiday[$j] == 't' || $TempDate < $SenyuEndDate || $TempDate > $SenyuStartDate) ? FALSE : TRUE;
			$IfOK1[$j] = $IfOK2[$j] = (!$IfDateValue[$j]
				|| $ShopHoliday[$FullDate[$j]]
				|| $Holiday[$j] == 't'
				|| strtotime($TempDate2) > strtotime($SenyuEndDate)
				|| strtotime($TempDate2) < strtotime($SenyuStartDate)) ? FALSE : TRUE;

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
				$AkiData = getAkiWaku2($myDB, $TargetClientCD, $TempDate, $WakuRange);
				if ($AkiData['akiUmu'] <> 1) {
					$DayColor[$j] = "#ffffe0";
					$Jokyo[$j] = "×";
					$IfOK1[$j] = false;
				}

				###20110828　予約日色付け
				if ($TempDate == $DispmyDate) {
					if ($IfNokara) {
						$DayColor[$j] = "#f9e8d9";
					} else {
						$DayColor[$j] = "#ffffe0";
					}
				}
				###20110828 予約日色付けEND

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

// 選択可能な月のリストを設定に従って作成


if ($ReserveTo != NULL && $ReserveFrom != NULL) {
	$TempMonth = -1;
	$TempYear = -1;
	for ($i = $ReserveTo; $i <= $ReserveFrom; $i++) {
		$CompareMonth = (int) date('m', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')));
		$CompareYear = (int) date('Y', mktime(0, 0, 0, date('m'), date('d') + $i, date('Y')));
		if ($TempMonth != $CompareMonth || $TempYear != $CompareYear) {
			$YearMonthValue[] = $CompareYear . $CompareMonth;
			$YearMonthChoice[] = $CompareYear . '年' . $CompareMonth . '月';
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

	/* 20200707未使用
		###空き確認関数
		$AkiTime = getAkiWakuTime($myDB, $TargetClientCD, $TargetDate, $WakuSuu, $STimeList, $ETimeList, $WakuRange, $loginUserCD);
		#echo "<br>".count( $AkiTime);
		for( $i=0; $i< count($WAKUPATTERN[$WakuPattern]['AMPM']); $i++ ){
			for( $j =0; $j < count($AkiTime ); $j++ ){
				if( strtotime( $WAKUPATTERN[$WakuPattern]['StartTime'][$i] ) <= strtotime( $AkiTime[$j] ) and strtotime( $AkiTime[$j] ) <  strtotime( $WAKUPATTERN[$WakuPattern]['EndTime'][$i] ) ){
					$wOKTimeName[] = $WAKUPATTERN[$WakuPattern]['StartTime'][$i]."～".$WAKUPATTERN[$WakuPattern]['EndTime'][$i] ;
					$OKTimes[] = $WAKUPATTERN[$WakuPattern]['StartTime'][$i] ;
					break;
				}
			}
		}
		$OKTimesLoop = count( $wOKTimeName ) ;
#echo "<br>846行目".$OKTimesLoop." ".$wOKTimeName[0];
*/
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
if ($Syusei) {
	$wUserMemo = SPFWParameter::getValues('wUserMemo');

	$wDate1 = SPFWParameter::getValues('wDate1');
	$wDate2 = SPFWParameter::getValues('wDate2');
	$wDate3 = SPFWParameter::getValues('wDate3');
	$wTime1 = SPFWParameter::getValues('wTime1');
	$wTime2 = SPFWParameter::getValues('wTime2');
	$wTime3 = SPFWParameter::getValues('wTime3');

	$wTime1ok = SPFWParameter::getValues('wTime1ok');
	$wTime2ok = SPFWParameter::getValues('wTime2ok');
	$wTime3ok = SPFWParameter::getValues('wTime3ok');

	for ($i = 0; $i < $TimeLoop; $i++) {
		if (($wTime[$i] == $wTime1) || ($wTime[$i] == $wTime1ok)) $wTime1Selected[$i] = " selected ";
		if (($wTime[$i] == $wTime2) || ($wTime[$i] == $wTime2ok)) $wTime2Selected[$i] = " selected ";
		if (($wTime[$i] == $wTime3) || ($wTime[$i] == $wTime3ok)) $wTime3Selected[$i] = " selected ";
	}
}

########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "reserve_form.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, FALSE, $MyClientCD);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->Msg = str_replace($LoopString2, NULL, $myTemplate->Msg);
$myTemplate->Msg = str_replace($LoopString3, NULL, $myTemplate->Msg);

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);



/*** 日付を指定して空き枠残数を取得*/
function getAkiWaku2($myDB, $TargetClientCD, $TargetDate, $WakuRange)
{

	########################################################
	# 予約リストを取得
	########################################################
	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "count( ReservationCD) ";	#0
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF ";
	$sql .= " WHERE ReservationCD > 0 AND MukouFlg = FALSE";
	$sql .= " AND date_format(TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
	$sql .= " AND Status = 1";
	$sql .= " AND ClientCD = " . $TargetClientCD;

	$myListObject->Condition = $sql;
	$myListObject->Order = "StylistCD,TimeFrom";
	$myListObject->Limit = "allpage";
	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

	$ReservationSuu = $myListObject->GetValue(0, 0); #予約数

	$WakuSuu = 0;
	$WakuRangeArr = explode("-", $WakuRange);
	for ($i = 0; $i < count($WakuRangeArr); $i++) {
		$WakuSuu = $WakuSuu + $WakuRangeArr[$i];
	}
	if ($WakuSuu <= $ReservationSuu) {
		$AkiData['akiUmu'] = 0;
	} else {
		$AkiData['akiUmu'] = 1;
	}

	return $AkiData;
}



/* 日付を指定して空き時間を取得
 *
 *  @param	WakuSuu: ex) 3
 *  @param	STimeList: ex) array 各枠の開始時間 [0] => 09:00 [1] => 13:00 [2] => 15:00
 *  @param	ETimeList: ex) array 各枠の終了時間 [0] => 12:00 [1] => 15:00 [2] => 17:00
 *  @param	WakuRange: ex)7-5-5
 *  @return	AkiDataTimeFrom: ex) array 空き時間の開始時間 [0] => 09:00 [1] => 13:00 [2] => 15:00
 */
/* 20200707未使用
function getAkiWakuTime($myDB, $TargetClientCD, $TargetDate, $WakuSuu, $STimeList, $ETimeList, $WakuRange, $loginUserCD = ""){

	$myListObject = new SPFWListObjectNoCount($myDB);#GroupBYを無効にしているクラス

	$sql = "SELECT ";
	$sql .= "count(r.ReservationCD), ";
	$sql .= " (CASE ";
	for( $x=0; $x<$WakuSuu; $x++){
		$tmp = $x+1;
		$sql .= " WHEN (DATE_FORMAT(r.TimeFrom,'%H:%i') >= '$STimeList[$x]') and (DATE_FORMAT(r.TimeFrom,'%H:%i') < '$ETimeList[$x]' ) THEN '$tmp' ";
	}
	$sql .= " ELSE '99' ";
	$sql .= " END) AS orderTimeFrom ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF r , tMenuM m";
	$sql .= " WHERE r.ReservationCD > 0 AND r.MukouFlg = FALSE AND m.MenuCD = replace( r.MenuCD,'|','')";
	$sql .= " AND date_format(r.TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
	$sql .= " AND r.Status = 1";
	$sql .= " AND r.ClientCD = " . $TargetClientCD;

	if ($loginUserCD) // 自分の予約は除く（同じ時間に修正できる）
		$sql .= " AND r.UserCD != '$loginUserCD' ";

	$myListObject->Condition = $sql;
	$myListObject->Group = "orderTimeFrom";
	$myListObject->Order = "orderTimeFrom";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);


	$ReservationLoop = $myListObject->Rows;
	for ($i = 0; $i < $ReservationLoop; $i++) {
		$ReserveCount[$i] = $myListObject->GetValue($i, 0); #枠ごとの予約数
		$orderTimeFrom[$i] = $myListObject->GetValue($i, 1);#枠定義No
	}

	$WakuRangeArray = explode( "-",$WakuRange );
	$AkiDataTimeFrom = array();
	#for( $i=0; $i<$WakuSuu;$i++){ #WakuSuuとReservationLoopは同じではない。
	#	if( $ReserveCount[$i] < $WakuRangeArray[$i] ){#空きあり
	#		$AkiDataTimeFrom[] =  $STimeList[$i];#空きのある開始時間
	#	}
	#}

	$j=0;
	for( $i=0; $i<$WakuSuu;$i++){ #WakuSuuとReservationLoopは同じではない。

		if( $orderTimeFrom[$j] != $i+1  ){
			$AkiDataTimeFrom[] =  $STimeList[$i];#空きのある開始時間
		}else{#一致したら
			if( $ReserveCount[$j] < $WakuRangeArray[$i] ){#空きあり
				$AkiDataTimeFrom[] =  $STimeList[$i];#空きのある開始時間
			}else{
				$j++;#空きなし
			}
		}
	}

	#print_r( $AkiDataTimeFrom );
	return $AkiDataTimeFrom; #配列　09:00　13:00など入ってる
}
*/
