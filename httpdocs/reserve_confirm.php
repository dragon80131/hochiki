<?php
	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
#	include_once _CLS_DIR . "SPUSTato.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";

	include_once "./include/common.php";


	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


	$wLang = SPFWParameter::getValues('wLang');
	$editReservationCD = SPFWParameter::getValues('editReservationCD');

########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照
if($lang == 'ja')$Ifjp = TRUE;#datapikckerの制御

#利用ワード
$reserve_confirm1 = $WORD[$lang]['reserve_confirm.1'];#部屋番号
$reserve_confirm2 = $WORD[$lang]['reserve_confirm.2'];#ご予約内容の確認
$reserve_confirm3 = $WORD[$lang]['reserve_confirm.3'];#日程変更はまだ完了しておりません
$reserve_confirm4 = $WORD[$lang]['reserve_confirm.4'];#下記内容でよろしければ「予約を確定する」ボタンを押してください。
$reserve_confirm5 = $WORD[$lang]['reserve_confirm.5'];#修正する場合は「修正する」ボタンを押してください。
$reserve_confirm6 = $WORD[$lang]['reserve_confirm.6'];#ご予約内容
$reserve_confirm7 = $WORD[$lang]['reserve_confirm.7'];#■第１希望
$reserve_confirm8 = $WORD[$lang]['reserve_confirm.8'];#■第２希望
$reserve_confirm9 = $WORD[$lang]['reserve_confirm.9'];#■第３希望
$reserve_confirm10 = $WORD[$lang]['reserve_confirm.10'];#■ご要望
$reserve_confirm11 = $WORD[$lang]['reserve_confirm.11'];#予約を確定する
$reserve_confirm12 = $WORD[$lang]['reserve_confirm.12'];#修正する



$top1 = $WORD[$lang]['top.1'];#号室
$logout1 = $WORD[$lang]['logout.1'];#ログアウト
$finish2 = $WORD[$lang]['finish.2'];#予約システムTOPへ
$finish3 = $WORD[$lang]['finish.3'];#予約TOP


$steppng = 'step3.png';
if($lang <>'ja') $steppng = 'step3_en.png';

	########################################################
	# クライアント取得
	########################################################
	//$wClientID = getClientID();  // URLからClientID取得 common.php
	//$wClientCD = getClientCD($myDB);

	$wClientID = getClientID2();  // URLからClientID取得 common.php
	$wClientCD = getClientCD2($myDB);


	$myClient = new Client($myDB);
	if (!$myClient->executeSelect("ID = '".$wClientID."' AND MukouFlg = FALSE ", "") || $myClient->RecCnt != 1){
		trigger_error("Getting Client Failed.", E_USER_ERROR);
	}
	$wClientCD = $myClient->ClientCD;
	unset($myClient);

	$TargetClientCD = $wClientCD;




	#### マンション名　をもってくる。
	include_once _CLS_DIR . "SPUSSetting.cls";
	$mySetting = new Setting($myDB);

	if (!$mySetting->executeSelect(" ClientCD = $wClientCD AND MukouFlg = FALSE", "")){
		$ErrorString = array();
		$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}
	$MansionName = $mySetting->MansionName;
	if($lang <> 'ja' ) $MansionName = $mySetting->MansionNameEn;#★Multilingual
	$WakuPattern = $mySetting->WakuPattern;
	$KetteiHaifuDate = $mySetting->KetteiHaifuDate;

	########################################################
	# パラメータ取得
	########################################################
	session_start();
	$_SESSION['ticket'] = md5(uniqid().mt_rand());
	$ticket = htmlspecialchars( $_SESSION['ticket'] , ENT_QUOTES );
	SPFWTemplate::setValue('ticket',$ticket);

	$wID = SPFWParameter::getValues('wID');#20171230追加
	$wUserMemo = SPFWParameter::getValues('wUserMemo');
	$wwUserMemo = nl2br(SPFWParameter::getValues('wUserMemo'));// 表示用
	$MyMenuCD = SPFWParameter::getValues('MyMenuCD');#|12|など

	$wDate1 = SPFWParameter::getValues('wDate1');
	$wTime1 = SPFWParameter::getValues('wTime1');
	$wDate2 = SPFWParameter::getValues('wDate2');
	$wTime2 = SPFWParameter::getValues('wTime2');
	$wDate3 = SPFWParameter::getValues('wDate3');
	$wTime3 = SPFWParameter::getValues('wTime3');

	if(empty($wTime1)){
		$wTime1ok = SPFWParameter::getValues('wTime1ok');
		$wTime1 = $wTime1ok;
	}

	if(empty($wTime2)){
		$wTime2ok = SPFWParameter::getValues('wTime2ok');
		$wTime2 = $wTime2ok;
	}

	if(empty($wTime3)){
		$wTime3ok = SPFWParameter::getValues('wTime3ok');
		$wTime3 = $wTime3ok;
	}

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

	if(!$wTime){
		$IfErr = true;
		include_once("reserve_form.php");
		exit;
	}

	$week = array("日", "月", "火", "水", "木", "金", "土");

	$date1Str = ($wDate1 != '') ? date('Y年n月j日', strtotime($wDate1)) : '';
	$niChi1Str = ($wDate1 != '') ? '(' . $week[date('w', strtotime($wDate1))] . ')' : '';
	$date2Str = ($wDate2 != '') ? date('Y年n月j日', strtotime($wDate2)) : '';
	$niChi2Str = ($wDate2 != '') ? '(' . $week[date('w', strtotime($wDate2))] . ')' : '';
	$date3Str = ($wDate3 != '') ? date('Y年n月j日', strtotime($wDate3)) : '';
	$niChi3Str = ($wDate3 != '') ? '(' . $week[date('w', strtotime($wDate3))] . ')' : '';


	if($lang <> 'ja'){
		$week = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
		$date1Str = ($wDate1 != '') ? date('d/m/Y', strtotime($wDate1)) : '';
		$niChi1Str = ($wDate1 != '') ? '(' . $week[date('w', strtotime($wDate1))] . ')' : '';
		$date2Str = ($wDate2 != '') ? date('d/m/Y', strtotime($wDate2)) : '';
		$niChi2Str = ($wDate2 != '') ? '(' . $week[date('w', strtotime($wDate2))] . ')' : '';
		$date3Str = ($wDate3 != '') ? date('d/m/Y', strtotime($wDate3)) : '';
		$niChi3Str = ($wDate3 != '') ? '(' . $week[date('w', strtotime($wDate3))] . ')' : '';
	}



	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	#$Address3 = $myUser->Address3; #多棟日程記号

	//20170706reserve_finish後、ブラウザバックから来た時にエラーになるため、DBのReservationCDをとっている
	$myReservation = new Reservation($myDB);

	if (!$myReservation->executeSelect("ClientCD = $TargetClientCD AND UserCD = '" . $myUser->UserCD . "' AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", ""))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);

		$ReservationCD = $myReservation->ReservationCD;
		if($editReservationCD != $ReservationCD){
			$editReservationCD = $ReservationCD;
			SPFWTemplate::dropValue('editReservationCD');

			SPFWTemplate::setValue('editReservationCD',$editReservationCD);
		}

	unset($myReservation);

	for( $i=0; $i< count($WAKUPATTERN[$WakuPattern]['AMPM']); $i++ ){
		if( strtotime( $WAKUPATTERN[$WakuPattern]['StartTime'][$i] ) <= strtotime( $wTime ) and strtotime( $wTime ) <  strtotime( $WAKUPATTERN[$WakuPattern]['EndTime'][$i] ) ){
			$wOKTimeName = $WAKUPATTERN[$WakuPattern]['StartTime'][$i]."～".$WAKUPATTERN[$WakuPattern]['EndTime'][$i] ;
		}
	}

	$SecondChoice = str_replace("NULL","",$SecondChoice);

	SPFWTemplate::setValue("work","1");

	########################################################
	# コンテンツ表示
	########################################################

## 予約可能数を表示（確認）するとき使います。20110821
##echo "OKTimesLoop".$OKTimesLoop ;

	if ($wLang == 1) {
		$CNT_FILE = "reserve_confirm_eng.tpl";
	} else {
		$CNT_FILE = "reserve_confirm.tpl";
	}

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier, FALSE, $MyClientCD);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
