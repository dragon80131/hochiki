<?php

include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
include_once _CLS_DIR . "SPFWTools.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$SearchRoomNo = SPFWParameter::getValues('SearchRoomNo');
$SearchRoomNo2 = SPFWParameter::getValues('SearchRoomNo2');
$sort = SPFWParameter::getValues('sort');
########################################################
# 認証動作
########################################################
if ($rKey) {

	$myUser = new User($myDB);
	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS);

	$UserCD 		= $myUser->UserCD;
	$UserKbn 		= $myUser->UserKbn; //	1:幹事企業一般 2:管理者 3:協力業者CD
	$ClientCD 		= $myUser->ClientCD; #幹事企業CD

	unset($myUser);

	if ($ClientCD) {

		$isClientCD = true;
	}
	//幹事企業と管理者の場合メモ欄の表示
	if ($UserKbn == 1 or $UserKbn == 2) {
		$IfMemo_disp = true;
	}
}

########################################################
# 担当者リスト表示
########################################################

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "UserCD, ";
$sql .= "LastName ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tUserM";
// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
$sql .= " WHERE MukouFlg = FALSE AND ClientCD = " . $ClientCD;

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$UserLoop = $myListObject->Rows;
echo "<br> ".__LINE__." Hensu :".$UserLoop;
for ($i = 0; $i < $UserLoop; $i++) {
	$UserCDs[$i] 	= $myListObject->GetValue($i, 0);
	$LastName[$i]	= $myListObject->GetValue($i, 1);
	$LastNameArray[$UserCDs[$i]] = $LastName[$i];
	// echo $LastNameArray[$UserCDs[$i]];
}
unset($myListObject);

#######################################################
# 物件情報
#######################################################
$myBukken = new Bukken($myDB);
if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "")) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}
if ($myBukken->RecCnt != 1) {

	trigger_error("Getting myBukken List Failed.", E_USER_ERROR);
} else {

	$BukkenName = $myBukken->BukkenName;
	$Memo = $myBukken->Memo;

	$Date1 = $myBukken->Date1;
	$Date2 = $myBukken->Date2;
	$Date3 = $myBukken->Date3;
	$Date4 = $myBukken->Date4;
	$Date5 = $myBukken->Date5;

	$AllDates = array($Date1, $Date2, $Date3, $Date4, $Date5);
	// usort($AllDates, "date_sort");
	$AllDates = array_diff($AllDates, array(""));
	$AllDates = array_values($AllDates);
	$FirstDate = current($AllDates);
	$LastDate = end($AllDates);
	if ($LastDate) {

		if ($FirstDate != $LastDate) {
			$Formated_FirstDate = date('Y年n月j日', strtotime($FirstDate));
			$Formated_LastDate = date('Y年n月j日', strtotime($LastDate));
			// $IfExistLastDate = true;
			$isExistLastDate = true;
		} else {
			$Formated_FirstDate = date('Y年n月j日', strtotime($FirstDate));

			// $IfnotExistLastDate = true;
			$isnotExistLastDate = true;
		}
	} else {
		//通らないはず
		// $IfnotExistLastDate = true;
		$isnotExistLastDate = true;
	}
}
unset($myListObject);

########################################################
# 部屋情報取得
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT DISTINCT RoomNo ";
$sql .= "RoomNo ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tResidentsFormF";
$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
$sql .= " AND Moved_at IS NULL ";

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$RoomLoop = $myListObject->Rows;
//プルダウン用のルームNoを格納する配列
$RoomNoArray = array();
for ($i = 0; $i < $RoomLoop; $i++) {
	$Room[$i]	= $myListObject->GetValue($i, 0);
	//プルダウンのためにルームNoを配列に格納
	$RoomNoArray[$i] .= "<option value='" . $Room[$i];
	$RoomNoArray[$i] .= "'>" . $Room[$i] . "</option>";
}

unset($myListObject);
########################################################
# お客様問い合わせ一覧
########################################################

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "FormCD, ";
$sql .= "RoomNo, ";
$sql .= "Name, ";
$sql .= "TEL, ";
$sql .= "Contents, ";
$sql .= "Creator,";
$sql .= "Created,";
$sql .= "Updated,";
$sql .= "TaioLog, ";
$sql .= "IsInRoom, ";
$sql .= "DemandDate, ";
$sql .= "Dates, ";
$sql .= "AMPM ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tResidentsFormF";
// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
$sql .= " AND Moved_at IS NULL ";


if ($SearchRoomNo) $sql .= " AND RoomNo LIKE '%" . $SearchRoomNo . "%'";
if ($SearchRoomNo2) $sql .= " AND RoomNo LIKE '%" . $SearchRoomNo2 . "%'";

$myListObject->Condition	= $sql;
//$sortがあるときはソートを実行
$Ifsort2 = true;
if ($sort == 1) {
	$myListObject->Order 		= "LENGTH(RoomNo),CAST(RoomNo AS SIGNED) asc";
} elseif ($sort == 2) {
	$myListObject->Order 		= "Created desc";
	$Ifsort2 = false;
	$Ifsort3 = true;
} elseif ($sort == 3) {
	$myListObject->Order 		= "Created asc";
	$Ifsort2 = true;
	$Ifsort3 = false;
} else {
	$myListObject->Order 		= "CASE DemandDate WHEN '不在' THEN 2 ELSE 1 END, Dates asc, AMPM asc, RoomNo asc ";
}
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$ResidentsFormLoop = $myListObject->Rows;
for ($i = 0; $i < $ResidentsFormLoop; $i++) {
	$FormCD[$i] 	= $myListObject->GetValue($i, 0);
	$RoomNo[$i]	= $myListObject->GetValue($i, 1);
	$Name[$i] = $myListObject->GetValue($i, 2);
	$TEL[$i] = $myListObject->GetValue($i, 3);
	$Contents[$i] = $myListObject->GetValue($i, 4);
	$Creator[$i] = $myListObject->GetValue($i, 5);
	$CreatorName[$i] = $LastNameArray[$Creator[$i]];
	$Created[$i] = $myListObject->GetValue($i, 6);
	$Updated[$i] = $myListObject->GetValue($i, 7);
	$TaioLog[$i] = $myListObject->GetValue($i, 8);
	$IsInRoom[$i] = $myListObject->GetValue($i, 9);
	if ($IsInRoom[$i] == "2") {
		$Contents[$i] = "在宅不可";
	}
	$DemandDate[$i] = $myListObject->GetValue($i, 10);

	$Dates[$i] = $myListObject->GetValue($i, 11);
	$AMPM[$i] = $myListObject->GetValue($i, 12);

	//日付が違うときを探す
	$MonthDate[$i] = mb_substr($DemandDate[$i], 5, 5);
	if ($MonthDate[$i] != $MonthDate[$i - 1]) {

		if ($DemandDate[$i] != "不在") {

			$ProcessedDates[$i] = date('n月j日', strtotime($Dates[$i]));

			// $MonthDate2[$i] = substr_replace($MonthDate[$i], "月",2,1);
			// $MonthDate2[$i] = $MonthDate2[$i]."日";

			$DiffMonthDate[$i] = "<tr><th></th><th class='monthdate'>" . $ProcessedDates[$i] . "</th><th class='display'></th><th class='display'></th><th class='display'></th><th class='display'></th><th class='display'></th></tr>";
		} else {
		}
	} else {
	}

	//AMPMが違う時を探す
	if ($DemandDate[$i] != $DemandDate[$i - 1]) {

		if ($DemandDate[$i] != "不在") {
			$AMPMInfo[$i] = mb_substr($DemandDate[$i], 10, 15);
		} else {
			$AMPMInfo[$i] = "不在";
		}

		$DiffDate[$i] = "<tr><th></th><th class='ampm'>" . $AMPM[$i] . $AMPMInfo[$i] . "</th><th class='display'></th><th class='display'></th><th class='display'></th><th class='display'></th><th class='display'></th></tr>";
	} else {
	}

	if ($DemandDate[$i] != "不在") {
		$DemandDate3[$i] = mb_substr($DemandDate[$i], 5, 20);
	} else {
		$DemandDate3[$i] = "不在";
	}
}

unset($myListObject);

########################################################
# お客様問い合わせ一覧 削除済
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "FormCD, ";
$sql .= "RoomNo, ";
$sql .= "Name, ";
$sql .= "TEL, ";
$sql .= "Contents, ";
$sql .= "Creator,";
$sql .= "Created,";
$sql .= "Updated, ";
$sql .= "TaioLog, ";
$sql .= "IsInRoom, ";
$sql .= "DemandDate ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tResidentsFormF";
// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
$sql .= " WHERE MukouFlg = TRUE AND BukkenCD = " . $editBukkenCD;
$sql .= " AND Moved_at IS NULL ";


if ($SearchRoomNo) $sql .= " AND RoomNo LIKE '%" . $SearchRoomNo . "%'";
if ($SearchRoomNo2) $sql .= " AND RoomNo LIKE '%" . $SearchRoomNo2 . "%'";

$myListObject->Condition	= $sql;
//$sortがあるときはソートを実行
if ($sort == 1) {
	$sql .= " ORDER BY LENGTH(RoomNo),CAST(RoomNo AS SIGNED) asc";
} elseif ($sort == 2) {
	$sql .= " ORDER BY Created desc";
} elseif ($sort == 3) {
	$sql .= " ORDER BY Created asc";
} else {
	$sql .= " ORDER BY CASE DemandDate WHEN '不在' THEN 2 ELSE 1 END, Dates asc, AMPM asc, RoomNo asc ";
}

// $myListObject->Order 		= "Created desc";
// $myListObject->Order 		= "CASE DemandDate WHEN '不在' THEN 2 ELSE 1 END, Dates asc, AMPM asc, RoomNo asc ";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$ResidentsFormLoop2 = $myListObject->Rows;
for ($i = 0; $i < $ResidentsFormLoop2; $i++) {

	$FormCD2[$i] 	= $myListObject->GetValue($i, 0);
	$RoomNo2[$i]	= $myListObject->GetValue($i, 1);
	$Name2[$i] = $myListObject->GetValue($i, 2);
	$TEL2[$i] = $myListObject->GetValue($i, 3);
	$Contents2[$i] = $myListObject->GetValue($i, 4);
	$Creator2[$i] = $myListObject->GetValue($i, 5);
	if ($Creator2[$i] == NULL) {
		$CreatorName2[$i] = "";
	} else {
		$CreatorName2[$i] = $LastNameArray[$Creator2[$i]];
	}
	$Created2[$i] = $myListObject->GetValue($i, 6);
	$Updated2[$i] = $myListObject->GetValue($i, 7);
	$TaioLog2[$i] = $myListObject->GetValue($i, 8);
	$IsInRoom2[$i] = $myListObject->GetValue($i, 9);
	if ($IsInRoom2[$i] == "2") {
		$Contents2[$i] = "在宅不可";
	}
	$DemandDate2[$i] = $myListObject->GetValue($i, 10);
}

unset($myListObject);


########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_Taio_List.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
