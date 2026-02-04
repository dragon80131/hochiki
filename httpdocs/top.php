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
include_once _CLS_DIR . "SPUSReservation.cls";
// include_once _CLS_DIR . "SPUSTato.cls";
include_once _CLS_DIR . "SPUSCalendar.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";

include_once "./include/common_489.php";

// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照

#利用ワード
$top1 = $WORD[$lang]['top.1']; #号室様
$top2 = $WORD[$lang]['top.2']; #お客様情報の登録
$top3 = $WORD[$lang]['top.3']; #お客様情報を入力し、「次へ」をクリックしてください。
$top4 = $WORD[$lang]['top.4']; #*は入力必須項目です。
$top5 = $WORD[$lang]['top.5']; #氏名
$top6 = $WORD[$lang]['top.6']; #インターネットからの受付は終了しました

$top7 = $WORD[$lang]['top.7']; #
$top8 = $WORD[$lang]['top.8']; #
$top9 = $WORD[$lang]['top.9']; #
$top10 = $WORD[$lang]['top.10']; #
$top11 = $WORD[$lang]['top.11']; #
$top12 = $WORD[$lang]['top.12']; #
$top13 = $WORD[$lang]['top.13']; #
$top14 = $WORD[$lang]['top.14']; #
$top15 = $WORD[$lang]['top.15']; #
$top16 = $WORD[$lang]['top.16']; #
$top17 = $WORD[$lang]['top.17']; #
$top18 = $WORD[$lang]['top.18']; #
$top19 = $WORD[$lang]['top.19']; #
$top20 = $WORD[$lang]['top.20']; #
$top21 = $WORD[$lang]['top.21']; #
$top22 = $WORD[$lang]['top.22']; #
$top23 = $WORD[$lang]['top.23']; #
$top24 = $WORD[$lang]['top.24']; #
$top25 = $WORD[$lang]['top.25']; #
$top26 = $WORD[$lang]['top.26']; #

$reserve_list2 = $WORD[$lang]['reserve_list.2']; #仮日程
$reserve_list5 = $WORD[$lang]['reserve_list.5']; #工事日

$logout1 = $WORD[$lang]['logout.1']; #ログアウト

if ($lang <> "ja") {
	$btn_manual = "manu_eng.png";
	$nittei_png = "nittei_en.png";
	$nittei1_png = "nittei1_en.png";
	$op_png = "op_en.png";
	$op1_png = "op1_en.png";
	$kojimovie_png = "kojimovie_en.png";
	$kiki_png = "kiki_en.png";
	$siryou_png = "siryo_en.png";
	$faq_png = "faq_en.png";
} else {
	$btn_manual = "manu.png";
	$nittei_png = "nittei.png";
	$nittei1_png = "nittei1.png";
	$op_png = "op.png";
	$op1_png = "op1.png";
	$kojimovie_png = "kojimovie.png";
	$kiki_png = "kiki.png";
	$siryou_png = "siryo.png";
	$faq_png = "faq.png";
}


########################################################
# 値取得
########################################################
$wLang = SPFWParameter::getValues('wLang');
$rKey = SPFWParameter::getValues('rKey');
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
$wClientCD = SPFWParameter::getValues('wClientCD');
// ########################################################
// # クライアント取得
// ########################################################
// // //$wClientID = getClientID();  // URLからClientID取得 common.php
// // //$myClient = new Client($myDB);

// // $wClientID = getClientID2();  // URLからClientID取得 common.php



// // $myClient = new Client($myDB);
// // if (!$myClient->executeSelect("ID = '" . $wClientID . "' AND MukouFlg = FALSE ", "") || $myClient->RecCnt != 1) {
// // 	trigger_error("Getting Client Failed.", E_USER_ERROR);
// // }
// // $wClientCD = $myClient->ClientCD;
// // $TargetClientCD = $wClientCD;

// // $ThisYear = substr($myClient->Created, 0, 4);
// // unset($myClient);

// // if ($rKey == "") {
// // 	$URL = _MAIN_URL . $wClientID . '/login_form.php';
// // 	header('Location: ' . $URL);
// // 	exit;
// // }


if ($rKey == "" and $editBukkenCD == "") {
	$URL = _MAIN_URL.'login.php';
	header('Location: ' . $URL);
	exit;
}

########################################################
# 設定情報取得
########################################################
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

#$Company = $myBukken->Company;
$wWEBReceptType = $myBukken->WEBReceptType;

$MansionName 	= $myBukken->BukkenName;
$wBuildingName 	= $myBukken->BuildingName;


$YoyakuEndDate  = $myBukken->YoyakuEndDate;
$wCloseDate 	= $YoyakuEndDate;

// $KetteiTeikyoDate  = $myBukken->KetteiTeikyoDate;
// $SenyuStartDate = $myBukken->SenyuStartDate;
// $SenyuEndDate  	= $myBukken->SenyuEndDate;

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

$YoyakuEndDate = $myBukken->YoyakuEndDate;
$DispYoyakuEndDate = date('Y年n月j日', strtotime($YoyakuEndDate));

// $KyoyuStartDate = $myBukken->KyoyuStartDate;
// $KyoyuEndDate	= $myBukken->KyoyuEndDate;
// $OpSenkouFlg	= $myBukken->OpSenkouFlg;
// $OpSenkouStartDate = $myBukken->OpSenkouStartDate;
// $OpSenkouTeikyoDate = $myBukken->OpSenkouTeikyoDate;
// $OpEndDate		= $myBukken->OpEndDate;
$GyosyaCD = $myBukken->GyosyaCD;
// $OPCloseDate 	= $OpEndDate;

$WakuPattern 	= $myBukken->WakuPattern;
$ThisYear		= date('Y', strtotime($myBukken->Created));
if($editBuildingCD){
	$wBuildingName 	= $myBuilding->BuildingName;
	$WakuPattern 	= $myBuilding->WakuPattern;
	$ThisYear		= date('Y', strtotime($myBuilding->Created));
}

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
if($BuildingLoop < 1){
	$wBuildingName = '';
}

// $Postponed		= $myBukken->Postponed;

$today = new DateTime();
$today = $today->format('Y-m-d');
// // $time1 = strtotime($KetteiTeikyoDate);
// // $time2 = strtotime($today);
// // $diff = ($time2 - $time1) / (60 * 60 * 24);

// // if ($Postponed == 0) {
$IfNotPostponed = true;
// // 	if ($diff >= 0) {
// // 		$IfAfterKetteiTeikyo = true;
// // 	} else {
// // 		$IfBeforeKetteiTeikyo = true;
// // 	}
// // }


if (strtotime($YoyakuEndDate) >= strtotime(date('Y-m-d'))) { #受付締切日 >= 今日
	$IfOPEN = "TRUE";
	$IfCLOSE = "";



} else {
	$IfCLOSE = "TRUE";
}

// // $TatoFlg = $myBukken->TatoFlg; #多棟FLG
// // $WebBiko = $myBukken->WebBiko; #お客様サイト用注意事項
// // $AppUsage = $myBukken->AppUsage; #0:インターホン、1:インターホン以外
// // if ($WebBiko != "") {
// // 	$IfWebBiko = true;
// // }

// // var_dump($TatoFlg);

// // $IfAppUsage = !empty($AppUsage) ? FALSE : TRUE; //インターホンの場合のみ表示

// // //中矢防災さん取付機器ボタン非表示　GyosyaCD＝277
// // $IfkikiShow = "TRUE";
// // if ($GyosyaCD == 277) {
// // 	$IfkikiShow = FALSE;
// // }

// // $QuestionFlg = $myBukken->QuestionFlg; #アンケート表示有無
// // $IfQuest = $QuestionFlg == "1" ? TRUE : FALSE;

unset($myBukken);
########################################################
# 認証動作
########################################################
$clsUser = new User($myDB);
if (!$clsUser->doAuthenticationByRegistKey($rKey, $wClientCD))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

$IfConfirm = false;
$IfNotConfirm = true;
$IfDecline = false;

if ($clsUser->UserCD == -1) { #8
	showSorryPage(_ILLEGAL_ACCESS);
} elseif ($clsUser->UserCD != -1) { #8
	$IfNew = FALSE;
	$rKey = $clsUser->RegistKey;
	// $Points = $clsUser->Points;
	// $Address3 = $clsUser->Address3; #多棟日程記号
	$wwID = $clsUser->ID; #UserCD
	$wTEL = $clsUser->TEL; #TEL
	if ($wTEL) {
		$IfJoho = true;
	}else{
		$IfNoJoho = true;
	}
	if($clsUser->ConfirmFlg == '1'){
		$IfConfirm = true;
		$IfNotConfirm = false;
		$IfDecline = false;
	}else{
		$IfConfirm = false;
		$IfNotConfirm = true;
		$IfDecline = false;
	}
	if($clsUser->ReplyFlg == '3'){
		$IfConfirm = false;
		$IfNotConfirm = false;
		$IfDecline = true;
	}

	#20120804スマホ用　ユーザ登録なしに予約変更できないように制御
	$IfUser = FALSE;
	$LastName = $clsUser->LastName;
	if ($LastName <> "") {
		$IfUser = TRUE;
	}

	if ($IfASP)
		$MyClientCD = $clsUser->ClientCD;

	$QUERY = "?rKey=" . $rKey . "&c=" . $MyClientCD . "&editBukkenCD=" . $editBukkenCD. "&editBuildingCD=" . $editBuildingCD;

	if ($MyClientCD != NULL)
		$Filename = _OBJECT_DIR . 'user_set_' . sprintf("%03d", $MyClientCD) . '.obj';
	else
		$Filename = _OBJECT_DIR . 'user_set.obj';

	if (file_exists($Filename)) {
		$ObjText = SPFWTools::getFile($Filename);
		$Obj = unserialize($ObjText);
		unset($ObjText);
	}


	$LoginFlg = ($Obj['LoginFlg'] == 't') ? TRUE : FALSE;
	$Timeout = $Obj['Timeout'];
	$KeyChangeFlg = ($Obj['KeyChangeFlg'] == 't') ? TRUE : FALSE;
	$ChangeSecurityFlg = ($Obj['ChangeSecurityFlg'] == 't') ? TRUE : FALSE;

	unset($Obj);
	unset($ObjText);

// 	if ($IfASP && $IfASPSite) {
// 		include_once _CLS_DIR . "SPUSClient.cls";

// 		$myClient = new Client($myDB);
// 		if (!$myClient->executeSelect("ClientCD = " . $MyClientCD . " AND MukouFlg = FALSE") && $myClient->RecCnt != 1)
// 			trigger_error("Getting Client Failed.", E_USER_ERROR);

// 		if ($myClient->ID != $MySiteID) {
// 			showSorryPage(_ILLEGAL_ACCESS);
// 			exit;
// 		}

// 		unset($myClient);
// 	}



// 	###20120628オプションありなし表示制御

// 	// var_dump($Address3);

// 	########################################################
// 	# 多棟の場合 ユーザの属する棟の専有部期間に変換
// 	########################################################
// 	// if ($TatoFlg == "1" and $Address3 != NULL) {
// 	// 	#echo "<span style='color:#FFF;'>多棟&UserM Address3 OK</span>";
// 	// 	$ToData = getToData($myDB, $wClientCD);

// 	// 	// var_dump($ToData);
// 	// 	for ($x = 0; $x < count($ToData); $x++) {
// 	// 		if ($Address3 == $ToData['ToName'][$x]) {

// 	// 			$SenyuStartDate = $ToData['SenyuStartDate'][$x];
// 	// 			$SenyuEndDate = $ToData['SenyuEndDate'][$x];
// 	// 			$wCloseDate = $ToData['YoyakuEndDate'][$x];
// 	// 			$ExtendedYoyakuEndDate  = $ToData['ExtendedYoyakuEndDate'][$x]; //第2受付締切日
// 	// 			// var_dump($ExtendedYoyakuEndDate);
// 	// 			if (!empty($ExtendedYoyakuEndDate)) {
// 	// 				$wCloseDate = $ExtendedYoyakuEndDate;
// 	// 				$wWEBReceptType = 2; //第2受付期間では確定で取る
// 	// 				// var_dump($wWEBReceptType);
// 	// 			} else {
// 	// 				$wWEBReceptType = 1;
// 	// 			}
// 	// 			$KyoyuStartDate = $ToData['KyoyoStartDate'][$x]; #きょうよーStartDateになってるので注意
// 	// 			$KyoyuEndDate = $ToData['KyoyoEndDate'][$x]; #きょうよーEndDateになってるので注意
// 	// 			$OPCloseDate = $ToData['OpEndDate'][$x];
// 	// 			break;
// 	// 		}
// 	// 	}
// 	// }



// 	//共用部がなければ表示しない
// 	if ($KyoyuStartDate != "" &&  $KyoyuStartDate != "0000-00-00")
// 		$IfKyoyuStartDate = "TRUE";


	$myReservation = new Reservation($myDB);

	#echo "<br><font color=white >".__LINE__."UserCD:".$wClientCD.$clsUser->UserCD."</font>" ;
	if($editBuildingCD){
		if (!$myReservation->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD = '" . $editBuildingCD . "' AND UserCD = '" . $clsUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE", ""))
			trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	}else{
		if (!$myReservation->executeSelect("BukkenCD = '" . $editBukkenCD . "' AND BuildingCD IS NULL AND UserCD = '" . $clsUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE", ""))
			trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	}

	if ($myReservation->RecCnt == 0) { #19
		$IfNoReservation = TRUE;
	} else { #19
		$IfReservation = TRUE;
	} #19
// 	#echo "<br>".__LINE__."行目:".$IfReservation ;

// 	#echo "<br>249行目:".$IfReservation ;#ここまで　OK

	function getTimeFormat($time){
		$datetime = DateTime::createFromFormat('H:i', $time);
		if((int)$datetime->format('i') == 0)
			$formatted = (int)$datetime->format('G') . '時';
		else
			$formatted = (int)$datetime->format('G') . '時' . (int)$datetime->format('i') . '分';

		return $formatted;
	}

	if ($myReservation->RecCnt >= 1) { #20 #20150420 修正
		$IfReservation = TRUE;
		#			$ReservationLoop = $myReservation->RecCnt;


		#			if ( $ReservationLoop > 1) #20150420 修正　１ローテに
		#			$ReservationLoop = 1 ;

		#			for ($i = 0; $i < $ReservationLoop ; $i++) { #21

		#				$myReservation->getDataSet($i);

		$wTimeFrom = $myReservation->TimeFrom;
		if ($wTimeFrom) $IfTimeFrom = TRUE;
		if (empty($wTimeFrom) && !empty($OpSenkouFlg)) {
			$IfTimeFrom = false;
			$IfKaraOpSenkou = true;
		}
		#echo "<span style='color:#FFF;'>wTimeFrom : " . $wTimeFrom . "</span>";
		$WakuTime = getWakuTime2($WakuPattern, $wTimeFrom);
		if($WakuTime)
			$ReservationDateT = getTimeFormat($WakuTime['STime']) . "～" . getTimeFormat($WakuTime['ETime']);
		else
			$ReservationDateT = '';

		$DispReservationDate = date('n月j日', strtotime($wTimeFrom));
		// if ($lang <> 'ja') $DispReservationDate = date('d/m/Y', strtotime($wTimeFrom));

		$ReserveQuery = $QUERY . "&r=" . $myReservation->ReservationCD;

		#			}#21 End

	} #20 End

}


// //共用部日時をわけて表示//専有部日時をわけて表示
// $DispKyoyuStartDate = date('Y年m月d日', strtotime($KyoyuStartDate));
// $DispKyoyuEndDate = date('Y年m月d日', strtotime($KyoyuEndDate));
$DispSenyuStartDate = date('Y年n月j日', strtotime($SenyuStartDate));
$DispSenyuEndDate = date('Y年n月j日', strtotime($SenyuEndDate));
// //曜日を取得20170526
$WeekList = array("日", "月", "火", "水", "木", "金", "土");
$w1 = $WeekList[date('w', strtotime($wTimeFrom))];
// $w2 = $WeekList[date('w', strtotime($KyoyuStartDate))];
// $w3 = $WeekList[date('w', strtotime($KyoyuEndDate))];
$w4 = $WeekList[date('w', strtotime($SenyuStartDate))];
$w5 = $WeekList[date('w', strtotime($SenyuEndDate))];

/*
if ($lang <> 'ja') {
	// $DispKyoyuStartDate = date('d/m/Y', strtotime($KyoyuStartDate));
	// $DispKyoyuEndDate = date('d/m/Y', strtotime($KyoyuEndDate));
	$DispSenyuStartDate = date('d/m/Y', strtotime($SenyuStartDate));
	$DispSenyuEndDate = date('d/m/Y', strtotime($SenyuEndDate));
	$WeekList = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
}
*/
##工事時間表示20170619
$Reservationtime = $ReservationDateT; # substr($wTimeFrom, 11, 13);

if ($lang <> 'ja') {
	#echo "<font color=white >".$wTimeFrom."</font>";
	$w1 = $WeekList[date('w', strtotime($wTimeFrom))];
	$w2 = $WeekList[date('w', strtotime($KyoyuStartDate))];
	$w3 = $WeekList[date('w', strtotime($KyoyuEndDate))];
	$w4 = $WeekList[date('w', strtotime($SenyuStartDate))];
	$w5 = $WeekList[date('w', strtotime($SenyuEndDate))];
}



###20110731 Commentout Add  comment out = not display
$IfModify = !$IfNew;
$IfLogin = ($IfNew && $LoginFlg) ? TRUE : FALSE;
$IfLogout = (!$IfNew && $LoginFlg) ? TRUE : FALSE;
#	$IfReminder = ((!$IfASP || ($IfASP && $IfASPSite)) && $IfNew) ? TRUE : FALSE;
$IfShowPoint = ($IfPoint && !$IfNew) ? TRUE : FALSE;
#	$IfNewForm = ($IfNoMail && $IfNew) ? TRUE : FALSE;
$IfEasyLogin = ($IfNew && $EasyLoginFlg && $MyCarrier != 1) ? TRUE : FALSE;
$IfEasyLoginRegist = (!$IfNew && $EasyLoginFlg && $MyCarrier != 1) ? TRUE : FALSE;

SPFWTemplate::setValue("IfReservation", $IfReservation);

// ####写真テーブルから登録写真をSelectする。

// //確認書表示

// ####確認書をSelectする。
// // $myListObject2 = new SPFWListObject($myDB);
// // $sql = "SELECT ";
// // $sql .= "ID "; #1 0
// // $myListObject2->SelectSQL = $sql;
// // $sql = " FROM tPictureKF ";
// // $sql .= " where ID = '" . $wwID . "' and MukouFlg = 0 ";
// // $myListObject2->Condition = $sql;
// // $myListObject2->Order = "1";
// // $myListObject2->Limit = "allpage";

// // if (!($myListObject2->GetList(1)))
// // 	trigger_error("Getting Stylist List Failed.", E_USER_ERROR);
// // $KakuLoop = $myListObject2->Rows;

// // $Kakunin = "<a href=kanview.php?ID=" . $wwID . "&rKey=" . $rKey . ">";
// // $Kakunin .= "<img src=kanview.php?ID=" . $wwID . "&rKey=" . $rKey . " width='200'></a>";

// // if ($KakuLoop >  0) {

// // 	$IfKakuopen = true;
// // }

// ##テスト用
// #	$IfOPEN="TRUE";




// ########################################################
// # コンテンツ表示
// ########################################################


// 			// $CNT_FILE = "top.tpl";
// 			// if ($wWEBReceptType == 2) { #１第3希望まで　2確定予約
				$CNT_FILE = "top_kakutei.tpl";
// 			// }



#echo "<font color='white'>" . $CNT_FILE . "</font>";
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();
unset($myTemplate);
unset($myLog);

?>