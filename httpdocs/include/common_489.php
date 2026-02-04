<?php
#	$isAdminMode = TRUE;

include_once "setting.properties";

// URLからClientIDを取得
function getClientID()
{


	$wRequestURIArray = explode("/", $_SERVER["REQUEST_URI"]);
	$ClientID = $wRequestURIArray[1];
	#$ClientID = $wRequestURIArray[3];

	/*
	$CreatedYear = date('Y');
	// ClientID以前を取り除く

	$filepath = str_replace(_ROOT_URL, "", "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);  //ローカルIPなのでドメイン取得したらこれに切り替える
#	$filepath = str_replace(_ROOT_URL, "", "http://192.168.98.192".$_SERVER["REQUEST_URI"]);

	// ファイル名のみを取得する
	$filename = basename($_SERVER['PHP_SELF']);

	// GET渡しがあれば取得する
	if ($_SERVER['QUERY_STRING']) $filename .= "?".$_SERVER['QUERY_STRING'];

	// ClientIDを取得する
	$str = str_replace($filename,"",$filepath);

	// URLがおかしくて / が入っている場合は取り除く
	#$str = str_replace("/","",$str);
	// URLがおかしくてdk が入っている場合は取り除く
#https://www2.489501.jp/sk/2019/14906/top_seko.php?ID=5678&pass=2175
	$str = str_replace( _PART_DIR ,"",$str);




	$ClientID = str_replace($CreatedYear,"",$str);
	$ClientID = str_replace("upfile","",$ClientID);
	$ClientID = str_replace("/","",$ClientID);
*/

	if ($ClientID == 'n65826') $ClientID = 'n66458';


	return $ClientID;
}

// URLからClientIDを取得
function getClientCD($myDB)
{

	/*
#2019のフォルダ名は、作業実施時の年とする。年をまたがって作成作業はしない。
	$CreatedYear = date('Y');
	// ClientID以前を取り除く
	//$filepath = str_replace(_ROOT_URL, "", "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);//ドメイン取得したら切り替える
	$filepath = str_replace(_ROOT_URL, "", "http://192.168.98.192".$_SERVER["REQUEST_URI"]);


	// ファイル名のみを取得する
	$filename = basename($_SERVER['PHP_SELF']);

	// GET渡しがあれば取得する
	if ($_SERVER['QUERY_STRING']) $filename .= "?".$_SERVER['QUERY_STRING'];

	// ClientIDを取得する
	$str = str_replace($filename,"",$filepath);

	// URLがおかしくて / が入っている場合は取り除く
	$str = str_replace( _PART_DIR ,"",$str);
	$ClientID = str_replace( $CreatedYear,"",$str);
	$ClientID = str_replace("upfile","",$ClientID);
	$ClientID = str_replace("/","",$ClientID);
*/

	$wRequestURIArray = explode("/", $_SERVER["REQUEST_URI"]);
	$ClientID = $wRequestURIArray[1];
	#	$ClientID = $wRequestURIArray[3];

	$myClient = new Client($myDB);

	if (!$myClient->executeSelect("ID = '" . $ClientID . "' AND MukouFlg = FALSE", "")) {
		$ErrorString = array();
		$ErrorString[] = "myClient情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	$ClientCD = $myClient->ClientCD;

	if ($ClientID == 'n65826') $ClientCD = '5986';
	return $ClientCD;
}

// URLからClientIDを取得20191028addueda
function getClientCD2($myDB)
{


	$wRequestURIArray = explode("/", $_SERVER["REQUEST_URI"]);
	$ClientID = $wRequestURIArray[1];
	$myClient = new Client($myDB);

	//if (!$myClient->executeSelect("ID = " . $ClientID . " AND MukouFlg = FALSE", "")){
	if (!$myClient->executeSelect("ID = '" . $ClientID . "' AND MukouFlg = FALSE", "")) {

		$ErrorString = array();
		$ErrorString[] = "myClient情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}
	$ClientCD = $myClient->ClientCD;

	if ($ClientID == 'n65826') $ClientCD = '5986';
	return $ClientCD;
}

// URLからClientIDを取得
function getClientID2()
{

	$wRequestURIArray = explode("/", $_SERVER["REQUEST_URI"]);
	$ClientID = $wRequestURIArray[1];

	if ($ClientID == 'n65826') $ClientID = 'n66458';

	return $ClientID;
}

// 予約物件番号からSPADEの物件番号に変換
function convClientID_BukkenCD($val)
{
	if (substr($val, 0, 1) == "n") {
		$val = ltrim($val, "n");
	}
	return $val;
}

function getTaioList($myDB, $UserCD)
{

	include_once _CLS_DIR . "SPFWListObject.cls";

	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "TaioCD, ";
	$sql .= "Category, ";
	$sql .= "TaioStatus, ";
	$sql .= "TaioNotes, ";
	$sql .= "Updated, ";
	$sql .= "Updater ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tTaioF";
	$sql .= " WHERE TaioCD > 0 AND MukouFlg = FALSE AND UserCD = '" . $UserCD . "' ";

	$myListObject->Condition = $sql;
	$myListObject->Order = "Updated";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

	$TaioLoop = $myListObject->Rows;
	for ($x = 0; $x < $TaioLoop; $x++) {
		$TaioList['TaioCD'][$x] = $myListObject->GetValue($x, 0);
		$TaioList['Category'][$x] = $myListObject->GetValue($x, 1);
		$TaioList['TaioStatus'][$x] = $myListObject->GetValue($x, 2);
		$TaioList['TaioNotes'][$x] = $myListObject->GetValue($x, 3);
		$TaioList['Updated'][$x] = substr($myListObject->GetValue($x, 4), 5, 11);
		$TaioList['Updater'][$x] = $myListObject->GetValue($x, 5);
	}
	return $TaioList;
}


function getUserData($myDB, $UserCD)
{

	$myUser = new User($myDB);

	if (!$myUser->executeSelect("UserCD = " . $UserCD, "") || $myUser->RecCnt != 1) {
		$ErrorString = array();
		$ErrorString[] = "tUserF情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}

	$UserData['LastName'] = $myUser->LastName;
	$UserData['TEL'] = $myUser->TEL;
	$UserData['TEL2'] = $myUser->TEL2;
	$UserData['EMail'] = $myUser->EMail;
	$UserData['MenuCD'] = $myUser->MenuCD;

	return $UserData;
}



#工事時間をなげると　枠のStart時間とEnd時間、枠名称（AMとかPM1）を配列WakuTimeでかえす
# $WPはtSettingMのWakuPattern　$wTimeFrom は、2018-09-10 10:00:00
function getWakuTime($WakuPattern, $wTimeFrom)
{

	#$WAKUPATTERN[3]['Name'] = "3枠(9:00-12:00,13:00-15:00,15:00-17:00)";
	#$WAKUPATTERN[3]['StartTime'][0] = "09:00";
	#$WAKUPATTERN[3]['EndTime'][0] = "12:00";
	#$WAKUPATTERN[3]['AMPM'][0] = "AM";
	#$WAKUPATTERN[3]['StartTime'][1] = "13:00";
	#$WAKUPATTERN[3]['EndTime'][1] = "15:00";
	#$WAKUPATTERN[3]['AMPM'][2] = "PM1";
	#$WAKUPATTERN[3]['StartTime'][2] = "15:00";
	#$WAKUPATTERN[3]['EndTime'][2] = "17:00";
	#$WAKUPATTERN[3]['AMPM'][2] = "PM2";
	global $WAKUPATTERN; #関数の外でよばれたものをつかう。


	#$wTimeFrom　を　Stime　とETimeと比較する。
	$targetTime = substr($wTimeFrom, 11, 2); #分までは枠で設定されることはない。
	$WakuSuu = count($WAKUPATTERN[$WakuPattern]['StartTime']);

	for ($aa = 0; $aa < $WakuSuu; $aa++) {
		$STime[$aa] = $WAKUPATTERN[$WakuPattern]['StartTime'][$aa]; # ex 9:00
		$STimeHour[$aa] = substr($STime[$aa], 0, 2); # ex 9
		$ETime[$aa] = $WAKUPATTERN[$WakuPattern]['EndTime'][$aa]; # ex 17:00
		$ETimeHour[$aa] = substr($ETime[$aa], 0, 2); # ex 17
		if (substr($ETime[$aa], 3, 2) == '30') {
			$ETimeHour[$aa] = $ETimeHour[$aa] + 1; # ex 17
		}
		$WakuName[$aa] = $WAKUPATTERN[$WakuPattern]['AMPM'][$aa]; # ex PM1

		#echo "<br>".__LINE__."行目:".$targetTime." >= ".$STimeHour[$aa]." and ".$targetTime." < ".$ETimeHour[$aa];
		if ($targetTime >= $STimeHour[$aa] and $targetTime <= $ETimeHour[$aa]) {

			$WakuTime['STime'] = $STime[$aa];
			$WakuTime['ETime'] = $ETime[$aa];
			$WakuTime['AMPM'] = $WakuName[$aa];
			break;
		}
	}

	return $WakuTime;
}


function getWakuTime2($WakuPattern, $wTimeFrom)
{

	#$WAKUPATTERN[3]['Name'] = "3枠(9:00-12:00,13:00-15:00,15:00-17:00)";
	#$WAKUPATTERN[3]['StartTime'][0] = "09:00";
	#$WAKUPATTERN[3]['EndTime'][0] = "12:00";
	#$WAKUPATTERN[3]['AMPM'][0] = "AM";
	#$WAKUPATTERN[3]['StartTime'][1] = "13:00";
	#$WAKUPATTERN[3]['EndTime'][1] = "15:00";
	#$WAKUPATTERN[3]['AMPM'][2] = "PM1";
	#$WAKUPATTERN[3]['StartTime'][2] = "15:00";
	#$WAKUPATTERN[3]['EndTime'][2] = "17:00";
	#$WAKUPATTERN[3]['AMPM'][2] = "PM2";
	global $WAKUPATTERN; #関数の外でよばれたものをつかう。


	#$wTimeFrom　を　Stime　とETimeと比較する。
	$targetTime = substr($wTimeFrom, 11, 2); #分までは枠で設定されることはない。
	if (substr($wTimeFrom, 14, 2) == '30') {
		$targetTime = $targetTime + 0.5;
	} elseif (substr($wTimeFrom, 14, 2) == '15') {
		$targetTime = $targetTime + 0.25;
	} elseif (substr($wTimeFrom, 14, 2) == '45') {
		$targetTime = $targetTime + 0.75;
	}


	//5枠( AM1 09:00-10:30, AM2 10:30-12:00, PM1 13:00-14:20,PM2 14:20-15:40, PM3 15:40-17:00)
	//の場合、10，40、50分の指定がないとAM2がAM1で表示される為追加
	if ($WakuPattern == "30") {
		if (substr($wTimeFrom, 14, 2) == '10') {
			$targetTime = $targetTime + 0.2;
		} elseif (substr($wTimeFrom, 14, 2) == '40') {
			$targetTime = $targetTime + 0.6;
		} elseif (substr($wTimeFrom, 14, 2) == '50') {
			$targetTime = $targetTime + 0.8;
		}
	}

	$WakuSuu = count($WAKUPATTERN[$WakuPattern]['StartTime']);

	for ($aa = 0; $aa < $WakuSuu; $aa++) {
		$STime[$aa] = $WAKUPATTERN[$WakuPattern]['StartTime'][$aa]; # ex 9:00
		$STimeHour[$aa] = substr($STime[$aa], 0, 2); # ex 9
		$ETime[$aa] = $WAKUPATTERN[$WakuPattern]['EndTime'][$aa]; # ex 17:00
		$ETimeHour[$aa] = substr($ETime[$aa], 0, 2); # ex 17
		if (substr($ETime[$aa], 3, 2) == '30') {
			$ETimeHour[$aa] = $ETimeHour[$aa] + 0.5; # ex 17
		} else if (substr($ETime[$aa], 3, 2) == '45') {
			$ETimeHour[$aa] = $ETimeHour[$aa] + 0.75; # ex 17
		} else if (substr($ETime[$aa], 3, 2) == '15') {
			$ETimeHour[$aa] = $ETimeHour[$aa] + 0.25; # ex 17
		}
		$WakuName[$aa] = $WAKUPATTERN[$WakuPattern]['AMPM'][$aa]; # ex PM1

		#echo "<br>".__LINE__."行目:".$targetTime." >= ".$STimeHour[$aa]." and ".$targetTime." < ".$ETimeHour[$aa];
		if ($targetTime >= $STimeHour[$aa] and $targetTime < $ETimeHour[$aa]) {

			$WakuTime['STime'] = $STime[$aa];
			$WakuTime['ETime'] = $ETime[$aa];
			$WakuTime['AMPM'] = $WakuName[$aa];
			break;
		}
	}

	return $WakuTime;
}





function getToData($myDB, $ClientCD)
{

	include_once _CLS_DIR . "SPFWListObject.cls";

	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "ToName, ";
	$sql .= "KyoyoStartDate, ";
	$sql .= "KyoyoEndDate, ";
	$sql .= "SenyuStartDate, ";
	$sql .= "SenyuEndDate, ";
	$sql .= "KetteiHaifuDate, ";
	$sql .= "YoyakuEndDate, ";
	$sql .= "YoteiTekyoDate, ";
	$sql .= "PhotoTekyoDate, ";
	$sql .= "OpEndDate, ";
	$sql .= "KetteiTeikyoDate, ";
	$sql .= "Updater, ";
	$sql .= "ExtendedYoyakuEndDate ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tTatoM";
	$sql .= " WHERE MukouFlg = FALSE AND ClientCD = '" . $ClientCD . "' ";

	$myListObject->Condition = $sql;
	$myListObject->Order = "Updated";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Stylist List Failed.", E_USER_ERROR);

	$TaioLoop = $myListObject->Rows;
	for ($x = 0; $x < $TaioLoop; $x++) {
		$ToData['ToName'][$x] = $myListObject->GetValue($x, 0);
		$ToData['KyoyoStartDate'][$x] = $myListObject->GetValue($x, 1);
		$ToData['KyoyoEndDate'][$x] = $myListObject->GetValue($x, 2);
		$ToData['SenyuStartDate'][$x] = $myListObject->GetValue($x, 3);
		$ToData['SenyuEndDate'][$x] =  $myListObject->GetValue($x, 4);
		$ToData['KetteiHaifuDate'][$x] =  $myListObject->GetValue($x, 5);
		$ToData['YoyakuEndDate'][$x] =  $myListObject->GetValue($x, 6);
		$ToData['YoteiTekyoDate'][$x] =  $myListObject->GetValue($x, 7);
		$ToData['PhotoTekyoDate'][$x] =  $myListObject->GetValue($x, 8);
		$ToData['OpEndDate'][$x] =  $myListObject->GetValue($x, 9);
		$ToData['KetteiTeikyoDate'][$x] =  $myListObject->GetValue($x, 10);
		$ToData['Updater'][$x] = $myListObject->GetValue($x, 11);
		$ToData['ExtendedYoyakuEndDate'][$x] = $myListObject->GetValue($x, 12);
	}
	return $ToData;
}
//住民側の予約サイトの登録に使う関数
function get_lang()
{
	$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	$language = 'ja';
	return $language;
}

function get_word($myDB)
{
	include_once _CLS_DIR . "SPFWListObject.cls";

	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "WordHensu, ";
	$sql .= "ja, ";
	$sql .= "en,zh,es,ko ";

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tWordM";
	$sql .= " WHERE MukouFlg = FALSE";

	$myListObject->Condition = $sql;
	$myListObject->Order = "WordCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Word List Failed.", E_USER_ERROR);

	for ($i = 0; $i < $myListObject->Rows; $i++) {
		$WordHensu[$i] = $myListObject->GetValue($i, 0);
		$ja[$i] = $myListObject->GetValue($i, 1);
		$en[$i] = $myListObject->GetValue($i, 2);

		$WORD["ja"][$WordHensu[$i]] = $ja[$i];
		$WORD["en"][$WordHensu[$i]] = $en[$i];
		$WORD["zh"][$WordHensu[$i]] = $myListObject->GetValue($i, 3);
		$WORD["es"][$WordHensu[$i]] = $myListObject->GetValue($i, 4);
		$WORD["ko"][$WordHensu[$i]] = $myListObject->GetValue($i, 5);
	}
	unset($myListObject);

	return $WORD;
}

function set_word($WORD, $language, $setword)
{
	//var_dump($WORD);
	if (isset($WORD[$language][$setword])) {
		$word = $WORD[$language][$setword];
	} else {
		$word = $WORD["ja"][$setword];
	}
	return $word;
}

function getMihenjiList($myDB, $TargetClientCD, $WakuSuu, $WAKUPATTERN, $WakuPattern)
{
	########################################################
	# 未返事の部屋取得
	########################################################

	$myListObject = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "distinct( u.userCD ), ";	#0
	$sql .= "u.id, ";					#1
	$sql .= "r.TimeFrom, ";				#2
	$sql .= "r.TimeTo,"; 				#3
	$sql .= "r.Notes,"; 				#4

	$sql .= " (CASE ";
	for ($x = 0; $x < $WakuSuu; $x++) {
		$STime[$x] = str_replace(":", "", $WAKUPATTERN[$WakuPattern]['StartTime'][$x]);
		$ETime[$x] = str_replace(":", "", $WAKUPATTERN[$WakuPattern]['EndTime'][$x]);
		$sql .= "   WHEN (DATE_FORMAT(r.TimeFrom,'%H%i') >= $STime[$x]) and (DATE_FORMAT(r.TimeFrom,'%H%i') < $ETime[$x] ) THEN '$x+1' ";
	}
	$sql .= "   ELSE '99' ";
	$sql .= " END) AS orderTimeFrom ";	#5

	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF r , tUserM u";
	$sql .= " WHERE r.UserCD = u.UserCD and r.TimeFrom is not NULL and r.Status = 1";
	$sql .= " and u.ID not like 'dummy%' and u.MukouFlg = FALSE";
	$sql .= " and u.ClientCD = $TargetClientCD and r.ClientCD = $TargetClientCD";
	$sql .= " and u.ReplyFlg = '0'";
	$sql .= " and u.UserCD not like '1234' and u.UserCD not like 'aiphone' and u.UserCD not like '5678' and u.UserCD not like '%kanri%' and u.UserCD not like '%dummy%' ";

	$myListObject->Condition = $sql;
	// $myListObject->Order = "u.UserCD ASC";
	$myListObject->Order = "(u.UserCD + 0)";

	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Stylist List Failed.", E_USER_ERROR);


	for ($i = 0; $i < $myListObject->Rows; $i++) {
		$Mihenji_Heya[$i] = $myListObject->GetValue($i, 0);
		$ID[$i] = $myListObject->GetValue($i, 1);
		$ReserveDate[$i] = $myListObject->GetValue($i, 2);
		$ReserveDateDisp[$i] = substr($ReserveDate[$i], 0, 10);
		$timeto[$i] = $myListObject->GetValue($i, 3);
		$Notes[$i] = $myListObject->GetValue($i, 4);

		#時間セット
		if ($ReserveDate[$i]) {
			$timefromArray = explode(" ", $ReserveDate[$i]);
			$targetTime = substr($timefromArray[1], 0, 2);
			$WakuTime = getWakuTime2($WakuPattern, $ReserveDate[$i]);
			$timeFromTime[$i] = $WakuTime['AMPM'];
			$ListTimeFromDisp[$i] = $WakuTime["STime"];
			$ListTimeToDisp[$i] =  $WakuTime["ETime"];
		}

		$Mikoji[$ID[$i]]["Mikoujiheya"] = $Mihenji_Heya[$i];
		$Mikoji[$ID[$i]]["Kojidate"] = $ReserveDateDisp[$i];
		$Mikoji[$ID[$i]]["KojiStartTime"] = $ListTimeFromDisp[$i];
		$Mikoji[$ID[$i]]["KojiEndTime"] = $ListTimeToDisp[$i];
	}
	unset($myListObject);

	########################################################
	# 完了の部屋取得
	########################################################
	// Notesに日付が入っていれば完了
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "distinct( userCD ), ";	#0
	$sql .= "TimeFrom, ";			#1
	$sql .= "TimeTo,"; 				#2
	$sql .= "Notes ,";				#3
	$sql .= " (CASE ";
	for ($x = 0; $x < $WakuSuu; $x++) {
		$STime[$x] = str_replace(":", "", $WAKUPATTERN[$WakuPattern]['StartTime'][$x]);
		$ETime[$x] = str_replace(":", "", $WAKUPATTERN[$WakuPattern]['EndTime'][$x]);
		$sql .= "   WHEN (DATE_FORMAT(TimeFrom,'%H%i') >= $STime[$x]) and (DATE_FORMAT(TimeFrom,'%H%i') < $ETime[$x] ) THEN '$x+1' ";
	}
	$sql .= "   ELSE '99' ";
	$sql .= " END) AS orderTimeFrom ";	#5
	$myListObject->SelectSQL = $sql;
	$sql = "FROM tReservationF ";
	$sql .= " WHERE ClientCD = '" . $TargetClientCD . "' ";
	$sql .= " AND (Notes != '' or Notes is not null)"; #完了チェックの日が入ってる

	$myListObject->Condition = $sql;
	$myListObject->Order = "UserCD ASC"; // 部屋番号順
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting vReservationF List Failed.", E_USER_ERROR);

	for ($i = 0; $i < $myListObject->Rows; $i++) {
		$Notes_Kan_Heya[$i] = $myListObject->GetValue($i, 0); // 完了してる部屋
		$ID[$i] = $myListObject->GetValue($i, 0); // 完了してる部屋
		$ReserveDate[$i] = $myListObject->GetValue($i, 1);
		$ReserveDateDisp[$i] = substr($ReserveDate[$i], 0, 10);
		$time[$i] = $myListObject->GetValue($i, 5);

		#時間セット
		if ($ReserveDate[$i]) {
			$timefromArray = explode(" ", $ReserveDate[$i]);
			$targetTime = substr($timefromArray[1], 0, 2);
			$WakuTime = getWakuTime2($WakuPattern, $ReserveDate[$i]);
			$timeFromTime[$i] = $WakuTime['AMPM'];
			$ListTimeFromDisp[$i] = $WakuTime["STime"];
			$ListTimeToDisp[$i] =  $WakuTime["ETime"];
		}

		$Mikoji[$ID[$i]]["Mikoujiheya"] = $Notes_Kan_Heya[$i];
		$Mikoji[$ID[$i]]["Kojidate"] = $ReserveDateDisp[$i];
		$Mikoji[$ID[$i]]["KojiStartTime"] = $ListTimeFromDisp[$i];
		$Mikoji[$ID[$i]]["KojiEndTime"] = $ListTimeToDisp[$i];
	}


	unset($myListObject);


	// 親機　施工後の写真があれば完了
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "distinct( r.userCD ), ";	#0
	$sql .= "p.SekoStatus,";			#1
	$sql .= "p.Device, ";				#2
	$sql .= "p.Eda, ";					#3
	$sql .= "r.TimeFrom, ";				#4
	$sql .= "r.TimeTo,"; 				#5
	$sql .= "r.Notes,"; 				#6
	$sql .= "u.id, ";					#7

	$sql .= " (CASE ";
	for ($x = 0; $x < $WakuSuu; $x++) {
		$STime[$x] = str_replace(":", "", $WAKUPATTERN[$WakuPattern]['StartTime'][$x]);
		$ETime[$x] = str_replace(":", "", $WAKUPATTERN[$WakuPattern]['EndTime'][$x]);
		$sql .= "   WHEN (DATE_FORMAT(r.TimeFrom,'%H%i') >= $STime[$x]) and (DATE_FORMAT(r.TimeFrom,'%H%i') < $ETime[$x] ) THEN '$x+1' ";
	}
	$sql .= "   ELSE '99' ";
	$sql .= " END) AS orderTimeFrom ";	#8
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tReservationF r ,tPictureF p , tUserM u";
	$sql .= " WHERE r.ClientCD = p.ClientCD AND r.UserCD = p.ID ";
	$sql .= " and r.UserCD = u.UserCD and r.TimeFrom is not NULL and r.Status = 1";
	$sql .= " and r.ClientCD = $TargetClientCD and p.ClientCD = $TargetClientCD";
	$sql .= " and u.ReplyFlg = '0'";
	$sql .= " and u.UserCD not like '1234' and u.UserCD not like 'aiphone' and u.UserCD not like '5678' and u.UserCD not like '%kanri%' and u.UserCD not like '%dummy%' ";
	$sql .= " AND p.SekoStatus = 2 AND p.Device = 1 AND p.Eda = 1 AND p.MukouFlg = 0"; #室内親機の施工後の1枚目の写真があるか

	$myListObject->Condition = $sql;
	$myListObject->Order = "r.userCD";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting tReservationF List Failed.", E_USER_ERROR);

	for ($i = 0; $i < $myListObject->Rows; $i++) {
		$Pic_Kan_Heya[$i] = $myListObject->GetValue($i, 0); // 完了してる部屋
		$SekoStatus[$i] = $myListObject->GetValue($i, 1); // 完了してる部屋
		$Device[$i] = $myListObject->GetValue($i, 2); // 完了してる部屋
		$Eda[$i] = $myListObject->GetValue($i, 3); // 完了してる部屋
		$ReserveDate[$i] = $myListObject->GetValue($i, 4);
		$ReserveDateDisp[$i] = substr($ReserveDate[$i], 0, 10);
		$time[$i] = $myListObject->GetValue($i, 5);
		$ID[$i] = $myListObject->GetValue($i, 7);

		if ($SekoStatus[$i] = 2 and $Device[$i] = 1 and $Eda[$i] = 1) { // 室内親機の施工後の1枚目の写真がある場合
			$Pic_Kan_Heya[$j] = $ID[$i];
			$ReserveDateDisp2[$i] = substr($ReserveDate[$i], 0, 10);
			$WakuTime = getWakuTime2($WakuPattern, $ReserveDate[$i]);
			$ListTimeFromDisp2[$i] = $WakuTime["STime"];
			$ListTimeToDisp2[$i] =  $WakuTime["ETime"];

			$Mikoji[$ID[$i]]["Mikoujiheya"] = $Pic_Kan_Heya[$i];
			$Mikoji[$ID[$i]]["Kojidate"] = $ReserveDateDisp2[$i];
			$Mikoji[$ID[$i]]["KojiStartTime"] = $ListTimeFromDisp2[$i];
			$Mikoji[$ID[$i]]["KojiEndTime"] = $ListTimeToDisp2[$i];
		} else { // 工事済じゃないやつ
			// 表示する
			echo "<br>" . __LINE__ . "行目:写真なし";
		}
	}
	unset($myListObject);



	########################################################
	# 未返事の部屋から、完了の部屋を取り除く
	########################################################
	if ($Mihenji_Heya) {
		if ($Notes_Kan_Heya)	$Mihenji_Heya = array_diff($Mihenji_Heya, $Notes_Kan_Heya);
		if ($Pic_Kan_Heya) 		$Mihenji_Heya = array_diff($Mihenji_Heya, $Pic_Kan_Heya);

		$Mihenji_Heya = array_values($Mihenji_Heya);
		$Mihenji_Heya_Loop = count($Mihenji_Heya);

		for ($i = 0; $i < $Mihenji_Heya_Loop; $i++) {
			$MihenjiDate[$i] = $Mikoji[$Mihenji_Heya[$i]]["Kojidate"];
			$MihenjiTime[$i] = $Mikoji[$Mihenji_Heya[$i]]["KojiStartTime"] . '～' . $Mikoji[$Mihenji_Heya[$i]]["KojiEndTime"];
		}

		$data = array($Mihenji_Heya_Loop, $Mihenji_Heya, $MihenjiDate, $MihenjiTime);
	} else {
		$data = array(0, null, null, null);
	}



	return $data;
}

//住民側の予約サイトの登録に使う関数終わり


/**
 * 2段階認証のためのメール送信
 * @param string $toMail 送信先メールアドレス
 * @param string $code 2段階認証コード
 */
function sendTwoFactorAuthMail($toMail, $code, $Expiry, $url = null)
{
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	

	$ExpiryDate = date("Y年m月d日 H時i分", strtotime($Expiry));

	if (!$url) {
		// URLが指定されていない場合はデフォルトのURLを使用
		$url = _ROOT_URL . "login.php";
	}

	$title = "消防設備点検予約システムからのお知らせ";
	$content = "\r\n";
	$content .= "システムにログインするための確認コードをお知らせします。\r\n";
	$content .= "表示されている画面に下記のコードを入力してください。\r\n";
	$content .= "\r\n";
	$content .= "{$code}\r\n";
	$content .= "\r\n";
	$content .= "このコードは{$ExpiryDate}まで有効です。\r\n";
	$content .= "\r\n";
	$content .= "このメールは「消防設備点検予約システム」からお送りしています。\r\n";
	$content .= "登録に身に覚えのない場合はお手数おかけしますがこのメールは破棄くださいませ。\r\n";
	$content .= "\r\n";
	$content .= "-------------------------------------------\r\n";
	$content .= "消防設備点検予約システム\r\n";
	$content .= "-------------------------------------------\r\n";
	$content .= "URL:{$url}";
	$headers = "From: no-reply@489501.jp";

	// return mb_send_mail($toMail, $title, $content, $headers);
}
