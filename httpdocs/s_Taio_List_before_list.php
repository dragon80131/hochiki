<?php

include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
	$ClientCD 		= $myUser->ClientCD; #幹事企業CD

	unset($myUser);

	if ($ClientCD) {

		$isClientCD = true;
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
for ($i = 0; $i < $UserLoop; $i++) {
	$UserCDs[$i] 	= $myListObject->GetValue($i, 0);
	$LastName[$i]	= $myListObject->GetValue($i, 1);
	$LastNameArray[$UserCDs[$i]] = $LastName[$i];
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
# お客様問い合わせ一覧
########################################################

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "MAX(Moved_at) ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tResidentsFormF";
$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
$sql .= " AND Moved_at IS NOT NULL ";

if ($SearchRoomNo) $sql .= " AND RoomNo LIKE '%" . $SearchRoomNo . "%'";

$sql .= " GROUP BY Moved_at";
$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$ResidentsFormLoop = $myListObject->Rows;
for ($i = 0; $i < $ResidentsFormLoop; $i++) {

	$Moved_at[$i] = $myListObject->GetValue($i, 0);
}

unset($myListObject);



########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_Taio_List_before_list.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
