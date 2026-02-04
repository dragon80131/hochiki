<?php
$isAdminMode = TRUE;

include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSGyosya.cls";
include_once _CLS_DIR . "SPUSNotice.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once  "./include/kenmei_connect.php";

// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 入力チェック
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$m 				= SPFWParameter::getValues('m');

if ($rKey == NULL) {
	$URL = _MAIN_URL . 'login_form.php';
	header('Location: ' . $URL);
	exit;
}

$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS2);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS2);

$wClientCD = $myUser->ClientCD;	
$PartnerGyosyaCD = $myUser->GyosyaCD;
$UserKbn = $myUser->UserKbn;

unset($myUser);

// 検索
$myListObject = new SPFWListObject($myDB);
// 1ページ当たり件数
$cRowsPerPage = 50;

########################################################
# 物件検索＆結果表示
########################################################
$sql = "SELECT ";
$sql .= "b.BukkenCD, ";	#0
$sql .= "b.BukkenName, ";	#1
$sql .= "b.GyosyaCD, "; #2
$sql .= "b.SenyuStartDate,"; #3
$sql .= "b.SenyuEndDate"; #4


$myListObject->SelectSQL = $sql;
$sql = " FROM tBukkenM AS b ";
$sql .= " WHERE b.MukouFlg = FALSE ";

if ($UserKbn == "1") {
	$sql .= "AND b.ClientCD = " . $wClientCD;
} else if ($UserKbn == "3") {
	$sql .= "AND ( b.GyosyaCD = " . $PartnerGyosyaCD . " OR b.GyosyaBousaiCD = " . $PartnerGyosyaCD . ")";
}

$wBukkenName = '';
$search = SPFWParameter::getValues("search");
$IfSearch = FALSE;
$IfList = TRUE;
if($search == 'yes'){
	$IfSearch = TRUE;
	$IfList = FALSE;
	$cRowsPerPage = 'allpage';
	$wBukkenName = SPFWParameter::getValues("wBukkenName");

	$sql .= " AND b.BukkenName LIKE '%".$wBukkenName."%'";
	$sql .= " AND DATE(b.Updated) >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)";
}

$myListObject->Condition = $sql;
$myListObject->Order = "b.BukkenCD desc";

$myListObject->Limit = $cRowsPerPage;
$myPage = 1;

if (!($myListObject->GetList($myPage)))
	trigger_error("Getting Bukken List Failed.", E_USER_ERROR);

$BukkenLoop = $myListObject->Rows;
$searchCount = $myListObject->Rows;
for ($i = 0; $i < $BukkenLoop; $i++) {

	$BukkenCD[$i] = $myListObject->GetValue($i, 0);
	$BukkenName[$i] = $myListObject->GetValue($i, 1);
	$GyosyaCD[$i] = $myListObject->GetValue($i, 2);

	$myGyosya = new Gyosya($myDB);
	if ($GyosyaCD[$i]) {
		if (!$myGyosya->executeSelect(" GyosyaCD =" . $GyosyaCD[$i], ""))
			trigger_error('Getting Gyosya Failed.', E_USER_ERROR);

		$GyosyaName[$i] = $myGyosya->GyosyaName;
	}

	$GyosyaDisp[$i] = $GyosyaName[$i];

	$SenyuStartDate[$i] = $myListObject->GetValue($i, 3);
	$SenyuEndDate[$i] = $myListObject->GetValue($i, 4);
	if ($SenyuEndDate[$i] == "") {
		$KojiDate[$i] = $SenyuStartDate[$i];
	} else {
		$KojiDate[$i] = $SenyuStartDate[$i] . "～" . $SenyuEndDate[$i];
	}

}

// 結果を判断
if ($myListObject->Rows == 0) {
	$IfNoResults = TRUE;
} else {
	$IfResults = TRUE;
}
unset($myListObject);


########################################################
# コンテンツ表示
########################################################
$HiddenValues = SPFWTemplate::getValuesToPass();

$CNT_FILE = "s_kihon_copy.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
########################################################
# 関数群
########################################################
function date_sort($a, $b)
{
	return strtotime($a) - strtotime($b);
}