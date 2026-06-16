<?php
// include_once "/var/www/kawamoto_dia/SPFW/inc/setting.properties";
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
include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSReservation.cls";
#include_once _CLS_DIR . "reload.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSTaio.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";
include_once _CLS_DIR . "SPUSBranche.cls";

include_once "./include/common_489.php";



// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

$steppng = 'step4.png';
if ($lang <> 'ja') $steppng = 'step4_en.png';


########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照

#利用ワード
$kakutei1 = $WORD[$lang]['kakutei.1']; #
$kakutei2 = $WORD[$lang]['kakutei.2']; #
$kakutei3 = $WORD[$lang]['kakutei.3']; #
#$kakutei4 = $WORD[$lang]['kakutei.4'];#


$top1 = $WORD[$lang]['top.1']; #号室
$logout1 = $WORD[$lang]['logout.1']; #ログアウト
$finish2 = $WORD[$lang]['finish.2']; #予約システムTOPへ
$finish3 = $WORD[$lang]['finish.3']; #予約TOP


$steppng = 'step4.png';
if ($lang <> 'ja') $steppng = 'step4_en.png';



########################################################
# クライアント取得
########################################################

$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');

$flag = SPFWParameter::getValues('flag');

// #### マンション名　をもってくる。
$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
	unset($myTemplate);
	exit;
}

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "") || $myBuilding->RecCnt != 1) {
		trigger_error("Getting myBuilding Failed.", E_USER_ERROR);
	}
}


$MansionName = $myBukken->BukkenName;
$WakuPattern = $myBukken->WakuPattern;
$wBuildingName 	= $myBukken->BuildingName;

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

########################################################
# 認証動作
########################################################

$clsUser = new User($myDB);
if (!$clsUser->doAuthenticationByRegistKey($rKey, $wClientCD))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($clsUser->Completed == NULL) { #24
	$Identifier = 'new';
	$IfNew = TRUE;
} else { #24
	$Identifier = 'kakutei';
	$IfNew = FALSE;

	$Lastname = $clsUser->Lastname; #20171231追加
	$ID = $clsUser->ID; #20171231追加
	$EMail = $clsUser->EMail; #20171231追加

	$UserCD = $clsUser->UserCD;
} #24
$IfModify = !$IfNew;

if ($clsUser->UserCD != -1) {
	$IfNew = FALSE;
	$rKey = $clsUser->RegistKey;
	$EMail = $clsUser->EMail; ##20170619追加

	if ($IfASP)
		$MyClientCD = $clsUser->ClientCD;

	$QUERY = "?rKey=" . $rKey . "&c=" . $MyClientCD . "&editBukkenCD=" . $editBukkenCD . "&editBuildingCD=" . $editBuildingCD;

	$Filename = _OBJECT_DIR . 'reserve_set.obj';

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

} else {
	$IfNew = TRUE;
}

///部屋番号表示20170608
$myUser = new User($myDB);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

$wID = $myUser->ID; #20170608追加
$TargetClientCD = $myUser->ClientCD; #20170608追加

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


//メール送信内容追加20170620
$wLastName = $myUser->LastName;
$wLastName = str_replace("<font color=red >確認</font>", "", $wLastName);

$wEMail = $myUser->EMail;
//LINEIDの取得
$LineID = $myUser->LineID;

// ######################################################
// #reloadチェック
// ######################################################
// /*
// $myReload = new Reload();
// $isReload = FALSE;
// if ($myReload->isReload())
// 	$isReload = TRUE;
// unset($myReload);
// */

########################################################
# 予約抽出
########################################################

$myReservation = new Reservation($myDB);
if($editBuildingCD){
	$Condition = "  BukkenCD = $editBukkenCD AND BuildingCD = '" . $editBuildingCD . "' and UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE";
}else{
	$Condition = "  BukkenCD = $editBukkenCD AND BuildingCD IS NULL and UserCD = '" . $myUser->UserCD . "' AND Status = 1 AND MukouFlg = FALSE";
}

$Condition .= " AND ClientCD = '" . $TargetClientCD . "' ";

if (!$myReservation->executeSelect($Condition, "TimeFrom DESC"))
	trigger_error("Getting Reservation Failed.", E_USER_ERROR);

if ($myReservation->RecCnt == 0) {
	$IfNoReservation = TRUE;
} else {
	$IfReservation = TRUE;
}

$wTimeFrom = $myReservation->TimeFrom;
$WakuTime = getWakuTime2($WakuPattern, $wTimeFrom);
$Reservationtime = $WakuTime['STime'] . "～" . $WakuTime['ETime'];

$DispReservationDate = date('Y年m月d日', strtotime($wTimeFrom));
$WeekList = array("日", "月", "火", "水", "木", "金", "土");
if ($lang <> 'ja') {
	$DispReservationDate = date('d/m/Y', strtotime($wTimeFrom));
	$WeekList = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
}
$wUserMemo = $myReservation->UserMemo;
#echo "<br>42行目".$DispReservationDate."----".$Reservationtime;
//曜日を取得20170526

$w2 = $WeekList[date('w', strtotime($wTimeFrom))];
$w2 = '('.$w2.')';
$ReserveQuery = $QUERY . "&r="  . $myReservation->ReservationCD;



//tUserMのReplyFlgを更新
/*
$myUser = new User($myDB);
if($editBuildingCD){
	if (!$myUser->executeSelect("BukkenCD = $editBukkenCD AND BuildingCD = $editBuildingCD AND ClientCD = $TargetClientCD AND ID = '" . $wID . "' MukouFlg = FALSE", "")) {
		$ErrorString = array();
		$ErrorString[] = "情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}
}else{
	if (!$myUser->executeSelect("BukkenCD = $editBukkenCD AND BuildingCD IS NULL AND ClientCD = $TargetClientCD AND ID = '" . $wID . "' MukouFlg = FALSE", "")) {
		$ErrorString = array();
		$ErrorString[] = "情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}
}
*/
if($flag == '3'){
	$myUser->ReplyFlg = 3;
	$myUser->ConfirmFlg = 1;
	$kakutei1 = "ご予約の辞退";
	$kakutei3 = '';
	$DispReservationDate = '辞退で受付いたしました。<br>次回のご協力よろしくお願いします。';
	$Reservationtime = '';
	$w2 = '';
}else{
	$myUser->ReplyFlg = 1;
	$myUser->ConfirmFlg = 1;
}
$myUser->Updated = "NOW()";
$myUser->Updater = $wID;

if (!$myUser->executeUpdate("", $TargetClientCD)) {
	trigger_error("Updating myUser Failed.", E_USER_ERROR);
}
unset($myUser);



// #対応記録
// $myTaio = new Taio($myDB);

// $myTaio->TaioCD = "-1";
// $myTaio->ClientCD = $TargetClientCD;
// $myTaio->UserCD = $wID;
// $myTaio->Category = "7"; #WEB受付
// $myTaio->TaioNotes = "[予約確定]変更無し"; // 20200708「 [WEB予約 」からはじめないで！RN支援報告書で使用しているため（[予約確定]はOK）
// $myTaio->Creator = $wID;
// $myTaio->Updater = $wID;

// if (!$myTaio->executeUpdate()) {
// 	trigger_error("Updating Taio Failed.", E_USER_ERROR);
// }
// unset($myTaio);
// #ヒアリングNoをカウントアップしない



##20170619メール送信
########################################################
# メール送信処理
########################################################

// if ($EMail == NULL) {
// 	$EMail = 'koji489@nsp.ne.jp';
// }
if ($EMail != NULL) { #35

	if ($lang <> 'ja') { #36
		$Filename = _OBJECT_DIR . 'reserve_set_en.obj'; #英語用のメール本文のファイルは、GUIでできません。SSHで直で編集
		$Reservationtime = str_replace("～", "-", $Reservationtime);
		$editTimeFrom = date('d/m/Y', strtotime($wTimeFrom)) . " " . $Reservationtime;
	} else { #36
		$Filename = _OBJECT_DIR . 'reserve_set.obj';
		$editTimeFrom = date('Y年m月d日', strtotime($wTimeFrom)) . $w2 . $Reservationtime;
	} #36

	#$Filename = ($IfASP) ? _OBJECT_DIR . 'user_set_' . sprintf('%03d', $TargetClientCD) . '.obj' : _OBJECT_DIR . 'user_set.obj';
	// if (file_exists($Filename)) { #37
	// 	$ObjText = SPFWTools::getFile($Filename);
	// 	$Obj = unserialize($ObjText);
	// 	unset($ObjText);

	// 	$Identifier = 'kakutei';

	// 	$wSendFlg = $Obj[$Identifier]['SendFlg'];
	// 	$wFromAddress = $Obj[$Identifier]['FromAddress'];
	// 	$wFromName = $Obj[$Identifier]['FromName'];
	// 	$wBcc = $Obj[$Identifier]['Bcc'];

	// 	$wSubject4pc = $Obj[$Identifier]['Subject4pc'];
	// 	$wMessage4pc = $Obj[$Identifier]['Message4pc'];

	// 	$wSubject4pc = str_replace("%%", "__", $wSubject4pc);
	// 	$wMessage4pc = str_replace("%%", "__", $wMessage4pc);

	// 	if ($wSendFlg == 't') { #38
	// 		#$CNT_FILE = "finish.tpl";
	// 		if ($wLang == 1) { #39
	// 			$CNT_FILE = "kakutei_eng.tpl";
	// 		} else { #39
	// 			$CNT_FILE = "kakutei.tpl";
	// 		} #39

	// 		$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	// 		$Subject = $wSubject4pc;
	// 		$Message = $wMessage4pc;

	// 		// $Message = str_replace("__DOMAIN__", _MAIN_URL, $Message);
	// 		// $Message = str_replace("__KEY__", $clsUser->RegistKey, $Message);
	// 		// $Message = str_replace("__MAILADDRESS__", $EMail, $Message);
	// 		// $Message = str_replace("__DATE__", date('Y年m月d日', mktime(date('H') + 16, date('i'), date('s'), date('m'), date('d'), date('Y'))), $Message);
	// 		// $Message = str_replace("__DATETIME__", date('Y年m月d日 H時i分s秒', mktime(date('H') + 16, date('i'), date('s'), date('m'), date('d'), date('Y'))), $Message);

	// 		$myTemplate->Msg = $Subject;
	// 		$myTemplate->convertTags();
	// 		$Subject = $myTemplate->Msg;
	// 		$myTemplate->Msg = $Message;
	// 		$myTemplate->convertTags();
	// 		$Message = $myTemplate->Msg;

	// 		$Subject = mb_convert_kana($Subject, 'KV');
	// 		$Message = mb_convert_kana($Message, 'KV');

	// 		$Headers = "From: " . mb_encode_mimeheader($wFromName, "ISO-2022-JP", "Q") . " <" . $wFromAddress . ">\n";
	// 		if ($wBcc != NULL)
	// 			$Headers .= "Bcc: " . $wBcc . "\n";
	// 		#テスト物件はメール飛ばさない
	// 		#if( substr($MansionName,0,4) <> "test"){
	// 		if (!mb_send_mail($EMail, $Subject, $Message, $Headers, "-f" . $wFromAddress))
	// 			trigger_error("ending Mail Failed. Please Look Up maillog.", E_USER_ERROR);
	// 	} #38
	// } #37
	$branchInfo = '';
	if($myBukken->BrancheCD){
		$myBranch = new Branche($myDB);
		if (!$myBranch->executeSelect("MukouFlg = FALSE AND BrancheCD = " . $myBukken->BrancheCD, "") || $myBranch->RecCnt != 1) {
		}else{
			$branchInfo = '　'.$myBranch->BrancheName.'メンテナンスセンター'.$myBranch->BrancheTEL;
		}
	}

	########################################################
	# メール送信処理
	########################################################
	if($flag == '3'){
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


		$Message .= "\n\n ==============================================================================";
		$Message .= "\nこのメールアドレスはお客様へのお知らせ専用です。";
		$Message .= "\nこのメールアドレスへ返信としてご質問をお送りいただいても回答できません。ご了承ください。";
		$Message .= "\nご質問やご不明な点がございましたら、下記までお問い合わせお願い申し上げます。";

		$ContactInfo = "\n".$ClientName."　".$ClientTEL."　営業時間：".$BusinessHours.$BusinessHoursNote;
		// $Message .= "\nホーチキ株式会社".$branchInfo."　営業時間：平日 ９：００～１７：３０（１２：００～１３：００を除く）";
		$Message .= $ContactInfo;

		$Headers = "From: " . mb_encode_mimeheader("消防設備点検予約システム", "ISO-2022-JP", "Q") . " < ".$wFromAddress."  >\n";
	}else{
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
	}


	if (!mb_send_mail($EMail, $Subject, $Message, $Headers, "-f" . $wFromAddress))
		trigger_error("ending Mail Failed. Please Look Up maillog.", E_USER_ERROR);
}
########################################################
#LINE通知　LINEIDがある場合のみ通知
########################################################
if ($LineID) {
	$userId = $LineID;
	$LineMessage = "【作業予約登録のお知らせ】\n";
	$LineMessage .= "以下の内容で工事日のご希望を承りました。\n";
	$LineMessage .= "マンション名：" . $MansionName . "\n";
	$LineMessage .= "部屋番号：" .  $wID . "\n";
	$LineMessage .= "予約日時：" . $editTimeFrom . "\n";
	$LineMessage .= "\n" . "※日程はまだ確定しておりません。";
	$LineMessage .= "日程調整後、決定した作業日程を書面とこちらのLINEにてお知らせいたします。";
	// if ($OpFlg = true) {
	// 	$LineMessage .= "\n\n" . "オプション申し込み　なし";
	// }

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

###20110731 Commentout Add  comment out = not display
$IfModify = !$IfNew;
$IfLogin = ($IfNew && $LoginFlg) ? TRUE : FALSE;
$IfLogout = (!$IfNew && $LoginFlg) ? TRUE : FALSE;
#	$IfReminder = ((!$IfASP || ($IfASP && $IfASPSite)) && $IfNew) ? TRUE : FALSE;
$IfShowPoint = ($IfPoint && !$IfNew) ? TRUE : FALSE;
#	$IfNewForm = ($IfNoMail && $IfNew) ? TRUE : FALSE;
$IfEasyLogin = ($IfNew && $EasyLoginFlg && $MyCarrier != 1) ? TRUE : FALSE;
$IfEasyLoginRegist = (!$IfNew && $EasyLoginFlg && $MyCarrier != 1) ? TRUE : FALSE;

########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "kakutei.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();
unset($myBukken);
unset($myTemplate);
unset($myLog);

// echo "<br> ".__LINE__." ここまでOK END:";
