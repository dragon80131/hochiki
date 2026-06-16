<?php
	include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";;
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSStylist.cls";
include_once _CLS_DIR . "SPUSSetting.cls";
include_once _CLS_DIR . "SPUSMenu.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSCalendar.cls";
#include_once _CLS_DIR . "SPUSTato.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSTaio.cls";

include_once "./include/common.php";
include_once "./include/bukken_alert.php";


// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

$wLang = SPFWParameter::getValues('wLang');


########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照

#利用ワード
$reserve_finish1 = $WORD[$lang]['reserve_finish.1']; #部屋番号
$reserve_finish2 = $WORD[$lang]['reserve_finish.2']; #ご予約内容の確認
$reserve_finish3 = $WORD[$lang]['reserve_finish.3']; #日程変更はまだ完了しておりません
$reserve_finish4 = $WORD[$lang]['reserve_finish.4']; #下記内容でよろしければ「予約を確定する」ボタンを押してください。
$reserve_finish5 = $WORD[$lang]['reserve_finish.5']; #修正する場合は「修正する」ボタンを押してください。
$reserve_finish6 = $WORD[$lang]['reserve_finish.6']; #ご予約内容
$reserve_finish7 = $WORD[$lang]['reserve_finish.7']; #■第１希望
$reserve_finish8 = $WORD[$lang]['reserve_finish.8']; #■第２希望
$reserve_finish9 = $WORD[$lang]['reserve_finish.9']; #■第３希望
$reserve_finish10 = $WORD[$lang]['reserve_finish.10']; #■ご要望
$reserve_finish11 = $WORD[$lang]['reserve_finish.11']; #予約を確定する
$reserve_finish12 = $WORD[$lang]['reserve_finish.12']; #修正する



$top1 = $WORD[$lang]['top.1']; #号室
$logout1 = $WORD[$lang]['logout.1']; #ログアウト
$finish2 = $WORD[$lang]['finish.2']; #予約システムTOPへ
$finish3 = $WORD[$lang]['finish.3']; #予約TOP

$steppng = 'step4.png';
if ($lang <> 'ja') $steppng = 'step4_en.png';

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
include_once _CLS_DIR . "SPUSSetting.cls";
$mySetting = new Setting($myDB);

if (!$mySetting->executeSelect(" ClientCD = $wClientCD AND MukouFlg = FALSE", "")) {
	trigger_error("Getting mySetting Failed.", E_USER_ERROR);
}
$MansionName = $mySetting->MansionName;
if ($lang <> 'ja') $MansionName = $mySetting->MansionNameEn; #★Multilingual
$KetteiHaifuDate = $mySetting->KetteiHaifuDate;
$WeekList = array("日", "月", "火", "水", "木", "金", "土");
$w3 = $WeekList[date('w', strtotime($KetteiHaifuDate))];

$MINUTEUNIT = $mySetting->MinuteUnit;
$MINUTETYPE = $mySetting->getTimeArray($MINUTEUNIT);
$WakuPattern = $mySetting->WakuPattern;

$wDate1 = SPFWParameter::getValues('wDate1'); #住人さん確認メール本文で使用
$wDate1 = date("Y-m-d", strtotime($wDate1));
$wDate2 = SPFWParameter::getValues('wDate2'); #住人さん確認メール本文で使用
$wDate2 = date("Y-m-d", strtotime($wDate2));
$wDate3 = SPFWParameter::getValues('wDate3'); #住人さん確認メール本文で使用
$wDate3 = date("Y-m-d", strtotime($wDate3));
$wTime1 = SPFWParameter::getValues('wTime1');
$wTime2 = SPFWParameter::getValues('wTime2');
$wTime3 = SPFWParameter::getValues('wTime3');

#第3希望の任意に、第一希望を枠越えにする
$IfDate3Ari = TRUE; #第3希望がありフラグ
if (!$wTime3 or !$wDate3) {
	$Date3Nashi = TRUE; //対応履歴と住人さん確認メール本文で、入力がないことを示すために使う
	$wDate3 = $wDate1;
	$wTime3 = $wTime1;
	$IfDate3Ari = ""; #第3希望がありフラグ
}


if ($wDate1 != '') { #第１希望
	$wDate = $wDate1;
	$wTime1StartArr = explode('～', $wTime1);
	$MailTime1 = $wTime1StartArr[0];
	$MailTimeTo1 = $wTime1StartArr[1];
}
if ($wDate2) { #第２希望
	$wDate = $wDate2;
	$wTime2StartArr = explode('～', $wTime2);
	$MailTime2 = $wTime2StartArr[0]; #住人さん確認メール本文で使用
	$MailTimeTo2 = $wTime2StartArr[1]; #住人さん確認メール本文で使用
}
if ($wDate3 != '') { #第３希望
	$wDate = $wDate3;
	$wTime3StartArr = explode('～', $wTime3);
	$MailTime3 = $wTime3StartArr[0]; #住人さん確認メール本文で使用
	$MailTimeTo3 = $wTime3StartArr[1]; #住人さん確認メール本文で使用
}

$week = array("日", "月", "火", "水", "木", "金", "土");
$date1Str = ($wDate1 != '') ? date('Y年n月j日', strtotime($wDate1)) : '';
$niChi1Str = ($wDate1 != '') ? '(' . $week[date('w', strtotime($wDate1))] . ')' : '';
$date2Str = ($wDate2 != '') ? date('Y年n月j日', strtotime($wDate2)) : '';
$niChi2Str = ($wDate2 != '') ? '(' . $week[date('w', strtotime($wDate2))] . ')' : '';
$date3Str = ($wDate3 != '') ? date('Y年n月j日', strtotime($wDate3)) : '';
$niChi3Str = ($wDate3 != '') ? '(' . $week[date('w', strtotime($wDate3))] . ')' : '';

$TatoFlg = $mySetting->TatoFlg; #多棟FLG

$WakuRange = $mySetting->WakuRange;
$WakuRangeArr = explode("-", $WakuRange); #工事枠数
$WakuSuu =  count($WAKUPATTERN[$WakuPattern]['AMPM']);
for ($i = 0; $i < $WakuSuu; $i++) {
	$STimeList[] = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
	$ETimeList[] = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];

	if ($wTime1StartArr[0] == $WAKUPATTERN[$WakuPattern]['StartTime'][$i]) {
		$WakuRangeMaxSuu1 = $WakuRangeArr[$i]; #なんこめをいれる 7-5-5
		$wTimeAMPM1 = $WAKUPATTERN[$WakuPattern]['AMPM'][$i];
	}
	if ($wTime2StartArr[0] == $WAKUPATTERN[$WakuPattern]['StartTime'][$i]) {
		$WakuRangeMaxSuu2 = $WakuRangeArr[$i]; #なんこめをいれる 7-5-5
		$wTimeAMPM2 = $WAKUPATTERN[$WakuPattern]['AMPM'][$i];
	}
	if ($wTime3StartArr[0] == $WAKUPATTERN[$WakuPattern]['StartTime'][$i]) {
		$WakuRangeMaxSuu3 = $WakuRangeArr[$i]; #なんこめをいれる 7-5-5
		$wTimeAMPM3 = $WAKUPATTERN[$WakuPattern]['AMPM'][$i];
	}
}

unset($mySetting);


$editReservationCD = SPFWParameter::getValues('editReservationCD');
$wUserMemo = SPFWParameter::getValues('wUserMemo');
if ($wUserMemo)	$MemoAri = 1; #メモありでメール

$MyMenuCD = SPFWParameter::getValues('MyMenuCD'); #

$rKey = SPFWParameter::getValues('rKey');
$work = SPFWParameter::getValues('work');
$ticket = SPFWParameter::getValues('ticket');

SPFWTemplate::setValue('work', '');



###リロード防止策
session_start();
if ($ticket == "" || $ticket != $_SESSION['ticket']) {
	$URL = 'top.php?c=' . $wClientCD . '&rKey=' . $rKey . '&wLang=' . $wLang;
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

$wID = $myUser->ID;
$Lastname = $myUser->LastName;
$Lastname = str_replace("<font color=red >確認</font>", "", $Lastname);

$EMail = $myUser->EMail;
$UserCD = $myUser->UserCD;
//LINEIDの取得
$LineID = $myUser->LineID;
$loginUserCD = $UserCD;

$Address3 = $myUser->Address3; #棟記号
if (!$wMenuCD)
	$wMenuCD =  SPFWTools::decodePluralValue($myUser->MenuCD); #配列を返す

$myUser->ReplyFlg = "1";
$myUser->Updated = "NOW()";
$myUser->Updater = $wID;

if (!$myUser->executeUpdate("", $wClientCD)) {
	trigger_error("Updating User Failed.", E_USER_ERROR);
}


########################################################
# 多棟の場合 ユーザの属する棟の決定案内配布日に変換
########################################################
if ($TatoFlg == "1" and $Address3 != NULL) {
	$ToData = getToData($myDB, $wClientCD);

	for ($x = 0; $x < count($ToData['ToName']); $x++) {

		#echo "<font color=white>★".count($ToData['ToName'])."-".$Address3."-".$ToData['ToName'][$x]."</font>";
		if ($Address3 == $ToData['ToName'][$x]) {
			$KetteiHaifuDate = $ToData['KetteiHaifuDate'][$x];
			$KetteiHaifuDate = date('Y年m月d日', strtotime($KetteiHaifuDate));
			$WeekList = array("日", "月", "火", "水", "木", "金", "土");
			$w3 = $WeekList[date('w', strtotime($ToData['KetteiHaifuDate'][$x]))];
			break;
		}
	}
}
if ($lang <> "ja") {
	$KetteiHaifuDate = date('d/m/Y', strtotime($KetteiHaifuDate));
	$WeekList = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	$w3 = $WeekList[date('w', strtotime($KetteiHaifuDate))];
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
	$MenuCD[$i] = $myListObject->GetValue($i, 0);
	$MyMinuteType[$i] = $myListObject->GetValue($i, 1);

	#if( (is_array($wMenuCD) && array_search($MenuCD[$i], $wMenuCD) !== FALSE) ){
	if ($MenuCD[$i] == $MyMenuCD) {
		#$MenuChecked[$i] =  ' checked' ;
		$wMyMinuteType = 	$MyMinuteType[$i];
	}
}
$IfNoOp = "TRUE";

########################################################
# スタイリストリストを取得
########################################################

$myListObject = new SPFWListObject($myDB);
$sql = "SELECT ";
$sql .= "StylistCD, ";
$sql .= "WakugoeFlg, ";
$sql .= "NumberOfLines "; #ライン数(列数）
$myListObject->SelectSQL = $sql;
$sql = " FROM tStylistM";
$sql .= " WHERE StylistCD > 0 AND MukouFlg = FALSE ";
$sql .= " AND ClientCD = " . $TargetClientCD;

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
	if (!$myListObject->GetValue($i, 1)) {
		$StylistList[] = $myListObject->GetValue($i, 0);
		$LineSuu[$myListObject->GetValue($i, 0)] = $myListObject->GetValue($i, 2); #LineSuu[スタリストCD]　
	} else {
		$OverFrameStylistList[] = $myListObject->GetValue($i, 0); // 枠越え
		$OverFrameLineSuu[] = $myListObject->GetValue($i, 2); // 枠越えLine数
	}
}

#$TargetDateの曜日にStylistが休みなら休みのStylistは除いておく。
for ($zz = 0; $zz < count($StylistList); $zz++) {
	$wStylistList[] = $StylistList[$zz];
}


#20200713add
####第1希望～第3希望の予約数を取得し、空きがあれば空き枠を取得する
$ReservedCount = getReservedCount($myDB, $wDate1, $wTime1StartArr[0]); // 第1希望の予約数を取得
if ($ReservedCount >= $WakuRangeMaxSuu1) {
	#echo "<br>第1希望 予約いっぱい ".$ReservedCount."-".$WakuRangeMaxSuu1;

	$ReservedCount = getReservedCount($myDB, $wDate2, $wTime2StartArr[0]); // 第2希望の予約数を取得
	if ($ReservedCount >= $WakuRangeMaxSuu2) {
		#echo "<br>第2希望 予約いっぱい ".$ReservedCount."-".$WakuRangeMaxSuu2;

		$ReservedCount = getReservedCount($myDB, $wDate3, $wTime3StartArr[0]); // 第3希望の予約数を取得
		if ($ReservedCount >= $WakuRangeMaxSuu3) {

			$dummyCount = getdummyCount($myDB, $wDate3, $wTime3StartArr[0]);
			if ($dummyCount < $WakuRangeMaxSuu3) {
				#				$AkiWakuAMPMTime = getAkiWakuAMPMTime2($myDB, $wDate3, $wTime3StartArr[0], $OverFrameStylistList,$OverFrameLineSuu, 5); #枠越えが５つもないやろ
				#				if ($AkiWakuAMPMTime['AkiUmu'] == 1) { //対応履歴用
				#					$ChosenDate = '【③】';
				#第3希望までうまっていたら第一希望の枠越えにセット
				$AkiWakuAMPMTime = getAkiWakuAMPMTime2($myDB, $wDate1, $wTime1StartArr[0], $OverFrameStylistList, $OverFrameLineSuu, 5);
				if ($AkiWakuAMPMTime['AkiUmu'] == 1) { //対応履歴用
					$ChosenDate = '【①】';
				} else {
					#echo "<br>第3希望 予約いっぱい ".$ReservedCount."-".$WakuRangeMaxSuu3;
					$Dai3madeMax = "★★予約が表示されていない可能性あり（かなりレアケース）★★";
				}
			} else {
				$dummyCount = getdummyCount($myDB, $wDate2, $wTime2StartArr[0]);
				if ($dummyCount < $WakuRangeMaxSuu2) {
					$AkiWakuAMPMTime = getAkiWakuAMPMTime2($myDB, $wDate2, $wTime2StartArr[0], $OverFrameStylistList, $OverFrameLineSuu, 5); #枠越えが５つもないやろ
					if ($AkiWakuAMPMTime['AkiUmu'] == 1) { //対応履歴用
						$ChosenDate = '【②】';
					}
				} else {
					$AkiWakuAMPMTime = getAkiWakuAMPMTime2($myDB, $wDate1, $wTime1StartArr[0], $OverFrameStylistList, $OverFrameLineSuu, 5); #枠越えが５つもないやろ
					if ($AkiWakuAMPMTime['AkiUmu'] == 1) { //対応履歴用
						$ChosenDate = '【①】';
					}
				}
			}
			#echo "<pre>";
			#var_dump($AkiWakuAMPMTime);
			#echo "</pre>";

		} else {
			#echo "<br>第3希望 予約OK ".$ReservedCount;
			$AkiWakuAMPMTime = getAkiWakuAMPMTime2($myDB, $wDate3, $wTime3StartArr[0], $wStylistList, $LineSuu, $WakuRangeMaxSuu3);
			if ($AkiWakuAMPMTime['AkiUmu'] == 1) { //対応履歴用
				$ChosenDate = '【③】';
			}
			#echo "<pre>";
			#var_dump($AkiWakuAMPMTime);
			#echo "</pre>";
		}
	} else {
		#echo "<br>第2希望 予約OK ".$ReservedCount;
		$AkiWakuAMPMTime = getAkiWakuAMPMTime2($myDB, $wDate2, $wTime2StartArr[0], $wStylistList, $LineSuu, $WakuRangeMaxSuu2);
		if ($AkiWakuAMPMTime['AkiUmu'] == 1) { //対応履歴用
			$ChosenDate = '【②】';
		}
		#echo "<pre>";
		#var_dump($AkiWakuAMPMTime);
		#echo "</pre>";
	}
} else {
	#echo "<br>第1希望 予約OK ".$ReservedCount;
	$AkiWakuAMPMTime = getAkiWakuAMPMTime2($myDB, $wDate1, $wTime1StartArr[0], $wStylistList, $LineSuu, $WakuRangeMaxSuu1);
	if ($AkiWakuAMPMTime['AkiUmu'] == 1) { //対応履歴用
		$ChosenDate = '【①】';
	}
	#	echo "<pre>";
	#	var_dump($AkiWakuAMPMTime);
	#	echo "</pre>";
}

########################################################
# 予約動作
########################################################

$myReservation = new Reservation($myDB);

if ($editReservationCD > 0) {
	if (!$myReservation->executeSelect("ClientCD = $TargetClientCD AND ReservationCD = " . $editReservationCD, "") || $myReservation->RecCnt != 1)
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);

	$Identifier = 'edit';
	//		$wMenuCD = SPFWTools::decodePluralValue($myReservation->MenuCD);#変更なら上書き

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

	// 前回の日付の開始時間を取得
	if ($LastTimeFrom) {
		$LastTimeFromDate = substr($LastTimeFrom, 0, 10); // 日付部分切取り→0000-00-00
		$LastTimeFromTime = substr($LastTimeFrom, 11, 5); // 時間部分切取り→00:00:00
		for ($i = 0; $i < count($WAKUPATTERN[$WakuPattern]['AMPM']); $i++) {
			if (strtotime($WAKUPATTERN[$WakuPattern]['StartTime'][$i]) <= strtotime($LastTimeFromTime) and strtotime($LastTimeFromTime) <  strtotime($WAKUPATTERN[$WakuPattern]['EndTime'][$i])) {
				$LastTimeFromStartTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
			}
		}
		$LastDate = $LastTimeFromDate . " " . $LastTimeFromStartTime;

		// 今回の日付の開始時間を取得
		$SelectTimeFromDate = substr($AkiWakuAMPMTime['AkiTimeFrom'], 0, 10);
		$SelectTimeFromTime = substr($AkiWakuAMPMTime['AkiTimeFrom'], 11, 5);
		for ($i = 0; $i < count($WAKUPATTERN[$WakuPattern]['AMPM']); $i++) {
			if (strtotime($WAKUPATTERN[$WakuPattern]['StartTime'][$i]) <= strtotime($SelectTimeFromTime) and strtotime($SelectTimeFromTime) <  strtotime($WAKUPATTERN[$WakuPattern]['EndTime'][$i])) {
				$SelectTimeFromStartTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
			}
		}
		$SelectDate = $SelectTimeFromDate . " " . $SelectTimeFromStartTime;

		// 前回登録した日付+時間と異なる場合 時間と指定を削除
		if ($LastDate != $SelectDate) {
			$myReservation->Jikan = NULL;
			$myReservation->Shitei = NULL;
		}
	}
} else {
	$myReservation->ReservationCD = -1;
	$myReservation->ClientCD = $TargetClientCD;
	$Identifier = 'new';
}

$myReservation->StylistCD = $AkiWakuAMPMTime['AkiStylistCD'];
$myReservation->ClientCD = $TargetClientCD;
$myReservation->UserCD = $myUser->UserCD;
$myReservation->TimeFrom = $AkiWakuAMPMTime['AkiTimeFrom'];
$myReservation->TimeTo 	=  $AkiWakuAMPMTime['AkiTimeTo'];
$myReservation->MenuCD = "|" . $MyMenuCD . "|";

if ($Identifier == 'new') {
	$myReservation->MenuCD =  $myUser->MenuCD;
} else {
	$myReservation->MenuCD = SPFWTools::encodePluralValue($wMenuCD);
}


$wMemo = $wUserMemo; // メールで使用

$myReservation->Status = 1;
$myReservation->UserMemo = $wUserMemo;
$SecondChoice  = "①" . str_replace("-", "/", substr($wDate1, 5, 5)) . $wTimeAMPM1;
$SecondChoice .= "②" . str_replace("-", "/", substr($wDate2, 5, 5)) . $wTimeAMPM2;
if ($Date3Nashi) { //第三希望入力無し
	$SecondChoice .= "③入力無し";
} else { //第三希望入力あり

	$SecondChoice .= "③" . str_replace("-", "/", substr($wDate3, 5, 5)) . $wTimeAMPM3;
}
$myReservation->SecondChoice = $SecondChoice;
$myReservation->Updater = $UserCD;

if (!$myReservation->executeUpdate())
	trigger_error("Updating Reservation Failed.", E_USER_ERROR);

recordBukkenWebActivity($myDB, $myUser->BukkenCD, ($Identifier == 'edit') ? 2 : 1);

if ($Lang <> 'ja') {
	$wTimeFrom = substr($myReservation->TimeFrom, 8, 2) . "/" . substr($myReservation->TimeFrom, 5, 2) . "/" . substr($myReservation->TimeFrom, 0, 4) . " " . substr($myReservation->TimeFrom, 10, 6);
}



#対応記録
$myTaio = new Taio($myDB);

$myTaio->TaioCD = "-1";
$myTaio->ClientCD = $TargetClientCD;
$myTaio->UserCD = $UserCD;

if ($MemoAri == 1) { #メモがある場合のみ送信489にメール
	$myTaio->Category = "|3|7|"; #WEB受付+備考欄あり→確認する
	$myTaio->TaioNotes = "[WEB予約 備考記述あり]" . $ChosenDate . $SecondChoice . " 備考:" . $wUserMemo; // 20200708必ず「 [WEB予約 」からはじめてください。RN支援報告書で使用しているため
} else { #メモがある場合のみ送信 End
	$myTaio->Category = "|7|"; #WEB受付
	$myTaio->TaioNotes = "[WEB予約]" . $ChosenDate . $SecondChoice; // 20200708必ず「 [WEB予約 」からはじめてください。RN支援報告書で使用しているため
}

$myTaio->LastTimeNittei = $TaioLastMemo;
$myTaio->Creator = $UserCD;
$myTaio->Updater = $UserCD;

if (!$myTaio->executeUpdate()) {
	trigger_error("Updating Taio Failed.", E_USER_ERROR);
}
unset($myTaio);

//20170706末次。リロード対策。メール送信処理はreserve_confirm.phpから来た時のwork=1の時のみ通る
if ($work == 1) {

	########################################################
	# メール送信処理
	########################################################
	if ($lang <> "ja") {
		$wData1 = date('d/m/y', strtotime($wData1));
		$wData2 = date('d/m/y', strtotime($wData2));
		$wData3 = date('d/m/y', strtotime($wData3));
	}


	$ID = $wID; // メール用


	if ($lang <> 'ja') {
		$Filename =  _OBJECT_DIR . 'reserve_set_en.obj'; #英語版の内容変更は、sshでSPFW/obj　の中のファイルを入れ替えて内容変更します。
	} else {
		$Filename =  _OBJECT_DIR . 'reserve_set.obj';
	}


	###20111004　メールがからならセンターにおくる。
	if ($EMail == NULL or $EMail == "") {
		//		$EMail = 'koji489@nsp.ne.jp';
		//		$EMail = 'info@nespe.com';
	}

	if (file_exists($Filename)) {
		$ObjText = SPFWTools::getFile($Filename);
		$Obj = unserialize($ObjText);
		unset($ObjText);

		$wSendFlg = $Obj[$Identifier]['SendFlg'];
		$wFromAddress = $Obj[$Identifier]['FromAddress'];
		$wFromName = $Obj[$Identifier]['FromName'];
		$wBcc = $Obj[$Identifier]['Bcc'];
		$wSubject4pc = $Obj[$Identifier]['Subject4pc'];
		$wMessage4pc = $Obj[$Identifier]['Message4pc'];

		$wSubject4pc = str_replace("%%", "__", $wSubject4pc) . "([" . $wClientID . "]" . $MansionName . " " . $wID . ")";
		$wMessage4pc = str_replace("%%", "__", $wMessage4pc);

		if ($wSendFlg == 't') {
			$CNT_FILE = "reserve_finish.tpl";
			$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

			$Message = $wMessage4pc;
			$Subject = $wSubject4pc;

			$Message = str_replace("__DOMAIN__", _MAIN_URL, $Message);
			$Message = str_replace("__KEY__", $clsUser->RegistKey, $Message);
			$Message = str_replace("__MAILADDRESS__", $EMail, $Message);
			$Message = str_replace("__DAY__", date('m月d日', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))), $Message);
			$Message = str_replace("__DATE__", date('Y年m月d日', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))), $Message);
			$Message = str_replace("__DATETIME__", date('Y年m月d日 H時i分s秒', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'))), $Message);
			$Message = str_replace("__USERCD__", $myUser->UserCD, $Message);

			$myTemplate->Msg = $Subject;
			$myTemplate->convertTags();
			$Subject = $myTemplate->Msg;
			$myTemplate->Msg = $Message;
			$myTemplate->convertTags();
			$Message = $myTemplate->Msg;

			$Headers = "From: " . mb_encode_mimeheader($wFromName, "ISO-2022-JP", "Q") . " <" . $wFromAddress . ">\n";
			if ($wBcc != NULL)
				$Headers .= "Bcc: " . $wBcc . "\n";
			if (!mb_send_mail($EMail, $Subject, $Message, $Headers, "-f" . $wFromAddress))
				trigger_error("ending Mail Failed. Please Look Up maillog.", E_USER_ERROR);

			#第3希望まで枠いっぱいの場合通知
			if ($Dai3madeMax) {
				if (!mb_send_mail('koji489@nsp.ne.jp', $Dai3madeMax . $Subject, $Message, $wFromAddress)) {
					trigger_error("ending Mail Failed. Please Look Up maillog.", E_USER_ERROR);
				}
			}
		}
	}
	########################################################
	#LINE通知　LINEIDがある場合のみ通知
	########################################################
	if ($LineID) {
		$userId = $LineID;
		$LineMessage = "【工事予約登録のお知らせ】\n";
		$LineMessage .= "以下の内容で工事日のご希望を承りました。\n";
		$LineMessage .= "マンション名：" . $MansionName . "\n";
		$LineMessage .= "部屋番号：" .  $wID . "\n";
		$LineMessage .= "予約日時：\n";
		$LineMessage .= "第1希望：" . $wDate1 . " " . $wTime1 . "\n";
		$LineMessage .= "第2希望：" . $wDate2 . " " . $wTime2 . "\n";
		if ($Date3Nashi == true) {
			$LineMessage .= "第3希望：入力無し\n";
		} else {
			$LineMessage .= "第3希望：" . $wDate3 . " " . $wTime3 . "\n";
		}
		$LineMessage .= "ご要望：" . $wUserMemo . "\n";
		$LineMessage .= "\n" . "※日程はまだ確定しておりません。";
		$LineMessage .= "日程調整後、決定した工事日程を書面とこちらのLINEにてお知らせいたします。";

		$message = json_encode([
			'type' => 'text',
			'text' => $LineMessage
		]);


		$url = 'https://www.489501.jp/sk/line_message_sender.php';
		$data = [
			'userId' => $userId,
			'message' => $message
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			echo 'Curl error: ' . curl_error($ch);
		}
		curl_close($ch);
	}
}




########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "reserve_finish.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, FALSE, $MyClientCD);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);



// 予約済の数を調べる
function getReservedCount($myDB, $TargetDate, $TargetTime)
{

	global $TargetClientCD;
	global $STimeList, $ETimeList;
	global $WakuSuu;
	global $MINUTEUNIT;
	global $loginUserCD;

	for ($i = 0; $i < $WakuSuu; $i++) {
		$tTime = $STimeList[$i]; // まず枠の開始時間をセット
		if ($tTime == $TargetTime) {

			#20210127 この処理だとうまくいかないのでコメント
			#			while ($tTime != $ETimeList[$i]) {
			#				$ssTime[] = $tTime;
			#				$tTime = date('H:i', (strtotime($tTime) + $MINUTEUNIT * 60));
			#				$k++;
			#				if ($k > 20) break 2;
			#			}
			#			break;

			// とりあえず20ループしておく。終了時間を超えたらbreakする
			for ($k = 0; $k <= 20; $k++) {
				// 枠の終了時間と、SQLで検索する時間を比較
				if (strtotime($ETimeList[$i]) <= strtotime($tTime)) {
					break;
				}
				$ssTime[] = $tTime;
				$tTime = date('H:i', (strtotime($tTime) + $MINUTEUNIT * 60));
			}
			break;
		}
	}
	if (is_array($ssTime)) {
		$SearchTime = implode("','", $ssTime);
	}

	########################################################
	# 予約数を取得
	########################################################

	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "ReservationCD, ";	#0
	$sql .= "StylistCD, ";		#1
	$sql .= "UserCD, ";			#2
	$sql .= "TimeFrom, ";		#3
	$sql .= "TimeTo, ";			#4
	$sql .= "MenuCD ";			#5

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF ";
	$sql .= " WHERE ReservationCD > 0 AND MukouFlg = FALSE";
	$sql .= " AND date_format(TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
	$sql .= " AND date_format(TimeFrom, '%H:%i') in ('" . $SearchTime . "')";
	$sql .= " AND Status = 1";
	$sql .= " AND ClientCD = " . $TargetClientCD;

	if ($loginUserCD) // 自分の予約は除く（同じ時間に修正できる）
		$sql .= " AND UserCD != '$loginUserCD'";

	$myListObject->Condition = $sql;
	$myListObject->Order = "StylistCD,TimeFrom";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

	$ReservationLoop = $myListObject->Rows;
	#for ($i = 0; $i < $ReservationLoop; $i++) {
	#	$ReservationCD = $myListObject->GetValue($i, 0);
	#}
	unset($myListObject);

	return $ReservationLoop; // 予約済数を返す
}



#function getAkiWakuAMPMTime2($myDB, $TargetDate, $TargetTime, $StylistList, $WakuRangeMaxSuu)
function getAkiWakuAMPMTime2($myDB, $TargetDate, $TargetTime, $StylistList, $LineSuu, $WakuRangeMaxSuu)
{

	global $TargetClientCD;
	global $wMyMinuteType;
	global $STimeList, $ETimeList;
	global $WakuSuu;
	global $MINUTEUNIT;
	global $loginUserCD;

	if (!is_array($StylistList)) {
		echo "空き枠確認でエラーです。引数足りず";
		exit;
	}
	if (!$wMyMinuteType) {
		echo "工事時間タイプ指定なしでエラーです。引数足りず";
		exit;
	}
	for ($i = 0; $i < $WakuSuu; $i++) {
		$tTime = $STimeList[$i]; // まず枠の開始時間をセット
		if ($tTime == $TargetTime) {

			#20210127 この処理だとうまくいかないのでコメント
			#			while ($tTime != $ETimeList[$i]) {
			#				$ssTime[] = $tTime;
			#				$tTime = date('H:i', (strtotime($tTime) + $MINUTEUNIT * 60));
			#				$k++;
			#				if ($k > 20) break 2;
			#			}
			#			break;

			// とりあえず20ループしておく。終了時間を超えたらbreakする
			for ($k = 0; $k <= 20; $k++) {
				// 枠の終了時間と、SQLで検索する時間を比較
				if (strtotime($ETimeList[$i]) <= strtotime($tTime)) {
					break;
				}
				$ssTime[] = $tTime;
				$tTime = date('H:i', (strtotime($tTime) + $MINUTEUNIT * 60));
			}
			break;
		}
	}
	if (is_array($ssTime)) {
		$SearchTime = implode("','", $ssTime);
	} else {
		echo "空き枠確認でエラーです。時間枠取得できず";
		exit;
	}

	########################################################
	# 予約リストを取得
	########################################################

	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "ReservationCD, ";	#0
	$sql .= "StylistCD, ";		#1
	$sql .= "UserCD, ";			#2
	$sql .= "TimeFrom, ";		#3
	$sql .= "TimeTo, ";			#4
	$sql .= "MenuCD ";			#5
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF ";
	$sql .= " WHERE ReservationCD > 0 AND MukouFlg = FALSE";
	$sql .= " AND date_format(TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
	$sql .= " AND date_format(TimeFrom, '%H:%i') in ('" . $SearchTime . "')";
	$sql .= " AND Status = 1";
	$sql .= " AND ClientCD = " . $TargetClientCD;

	if ($loginUserCD) // 自分の予約は除く（同じ時間に修正できる）
		$sql .= " AND UserCD != '$loginUserCD'";

	$myListObject->Condition = $sql;
	$myListObject->Order = "StylistCD,TimeFrom";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

	$ReservationLoop = $myListObject->Rows;
	#echo "<br>".__LINE__."行目CellLoop 検索時間群:".$SearchTime."予約数:".$ReservationLoop ;

	for ($i = 0; $i < $ReservationLoop; $i++) {
		$ReservationCD = $myListObject->GetValue($i, 0);
		$StylistCD = $myListObject->GetValue($i, 1);
		$UserCD = $myListObject->GetValue($i, 2);
		$TimeFrom = $myListObject->GetValue($i, 3);
		$TimeTo = $myListObject->GetValue($i, 4);
		$MenuCD = SPFWTools::decodePluralValue($myListObject->GetValue($i, 5))[0]; #工事内容は１つとする　ここ条件
		$TimeS[$StylistCD][] = substr($TimeFrom, 11, 5); #予約数分はいってる
		$TimeE[$StylistCD][] = substr($TimeTo, 11, 5);
	}

	$TimesLoop = count($ssTime); // 20180912修正（AM希望で30分工事で、9-12であれば、TimesLoopは6となる）

	#連続して必要な時間が空いてたらOk
	#$wMyMinuteType（消費する単位時間数　Ex　90分で３） があいていたら AkiUmu = 1
	$RenzokuAki = 0;
	$AkiUmu = 0;
	$pTime = strtotime($TargetTime); #13:00など　検索開始時間

	$WakuUseSuu = 0; #消費枠数と最大枠可能数（７とか）と比較する
	foreach ($StylistList as $value) {
		$k = 0;
		$s = $value; // StylistCD
		for ($i = 0; $i < $TimesLoop; $i += $wMyMinuteType) {
			$wTime = date("H:i", ($pTime + $MINUTEUNIT * 60 * $i));

			if (isset($TimeS[$s])) {
				#★肝★　wTimeが、あるユーザの予約がなかったら　かつ　ｗTimeが終わり時間より大きくなかったら（小さかったら）　
				#				if (array_search($wTime, $TimeS[$s]) === FALSE && !((strtotime($wTime) + $MINUTEUNIT * $wMyMinuteType * 60) > strtotime($tTime))) {
				if (array_count_values($TimeS[$s])[$wTime] < $LineSuu[$s] && !((strtotime($wTime) + $MINUTEUNIT * $wMyMinuteType * 60) > strtotime($tTime))) {


					#					if ($WakuUseSuu < $WakuRangeMaxSuu) { #消費枠数　と最大枠可能数（７とか）と比較する
					$AkiUmu = 1; #★空きがある！
					break 2;
					#					} else { #最大枠可能数（７とか）を越えてる　この時間帯はつかえない。
					#						$AkiUmu = 0; #空き無し
					#					}
				} else { #予約時間があいてなかったら
					$WakuUseSuu++; #消費枠数が１つふえる。
					$AkiUmu = 0; #空き無し
				}
			} else { #　$TimeS[$s]がない＝その時間帯 $wTime そのスタイリストCD $s で誰も予約がなかった
				$i = $TimesLoop;
				$AkiUmu = 1; #★空きがあり！
				break 2;
			}
		} #TimeLoop For End
	} #StylistのLoop　For End


	$AkiData['AkiTimeTo'] = $TargetDate . " " . date("H:i", (strtotime($wTime)  + $MINUTEUNIT * $wMyMinuteType * 60));
	$AkiData['AkiTimeFrom'] = $TargetDate . " " . date("H:i", (strtotime($AkiData['AkiTimeTo'])  - $MINUTEUNIT * $wMyMinuteType * 60));
	$AkiData['AkiUmu']  = $AkiUmu;
	$AkiData['AkiStylistCD']  = $s;

	return $AkiData;
} #function　getAkiWakuAMPMTime2

function getdummyCount($myDB, $TargetDate, $TargetTime)
{
	global $TargetClientCD;
	global $STimeList, $ETimeList;
	global $WakuSuu;
	global $MINUTEUNIT;
	global $loginUserCD;


	for ($i = 0; $i < $WakuSuu; $i++) {
		$tTime = $STimeList[$i]; // まず枠の開始時間をセット
		if ($tTime == $TargetTime) {

			#20210127 この処理だとうまくいかないのでコメント
			#			while ($tTime != $ETimeList[$i]) {
			#				$ssTime[] = $tTime;
			#				$tTime = date('H:i', (strtotime($tTime) + $MINUTEUNIT * 60));
			#				$k++;
			#				if ($k > 20) break 2;
			#			}
			#			break;

			// とりあえず20ループしておく。終了時間を超えたらbreakする
			for ($k = 0; $k <= 20; $k++) {
				// 枠の終了時間と、SQLで検索する時間を比較
				if (strtotime($ETimeList[$i]) <= strtotime($tTime)) {
					break;
				}
				$ssTime[] = $tTime;
				$tTime = date('H:i', (strtotime($tTime) + $MINUTEUNIT * 60));
			}
			break;
		}
	}

	if (is_array($ssTime)) {
		$SearchTime = implode("','", $ssTime);
	}

	########################################################
	# 予約数を取得
	########################################################

	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "ReservationCD, ";	#0
	$sql .= "StylistCD, ";		#1
	$sql .= "UserCD, ";			#2
	$sql .= "TimeFrom, ";		#3
	$sql .= "TimeTo, ";			#4
	$sql .= "MenuCD ";			#5

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF ";
	$sql .= " WHERE ReservationCD > 0 AND MukouFlg = FALSE";
	$sql .= " AND date_format(TimeFrom, '%Y/%m/%d') = date_format('" . $TargetDate . "', '%Y/%m/%d')";
	$sql .= " AND date_format(TimeFrom, '%H:%i') in ('" . $SearchTime . "')";
	$sql .= " AND Status = 1";
	$sql .= " AND ClientCD = " . $TargetClientCD;
	$sql .= " AND UserCD in ('dummy')";

	$myListObject->Condition = $sql;
	$myListObject->Order = "StylistCD,TimeFrom";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);

	$ReservationLoop = $myListObject->Rows;
	#for ($i = 0; $i < $ReservationLoop; $i++) {
	#	$ReservationCD = $myListObject->GetValue($i, 0);
	#}
	unset($myListObject);

	return $ReservationLoop; // 予約済数を返す
}
