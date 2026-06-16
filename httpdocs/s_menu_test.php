<?php
include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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

include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSIraiRenkei.cls";
include_once _CLS_DIR . "SPUSRingi.cls";
include_once _CLS_DIR . "SPUSKoji.cls";

include_once _CLS_DIR . "SPUSGyosya.cls";
include_once _CLS_DIR . "SPUSClient.cls";

include_once "./include/kenmei_connect.php";


$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 入力チェック
########################################################
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$rKey = SPFWParameter::getValues('rKey');
if ($rKey == NULL or $editBukkenCD == NULL) {
	$URL = _MAIN_URL . 'login_form.php';
	header('Location: ' . $URL);
	exit;
}
########################################################
# 認証動作
########################################################
$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS);

$Extra1 = $myUser->Extra1; #所属CD
$MyZokusei = $myUser->Extra3;	#管理ユーザ２一般ユーザ１
$UserKbn = $myUser->UserKbn; //ユーザー区分1:幹事企業一般 2:管理者 3:協力業者CD	
$User_GyosyaCD = $myUser->GyosyaCD; //ユーザー区分1:幹事企業一般 2:管理者 3:協力業者CD
$UserID = $myUser->ID;

echo "UserID:" . $UserID . "<br>";
echo "UserKbn:" . $UserKbn . "<br>";

unset($myUser);

if ($UserKbn == 3) {
	$myGyosya = new Gyosya($myDB);
	if (!$myGyosya->executeSelect("MukouFlg = FALSE AND GyosyaCD = " . $User_GyosyaCD, "") || $myGyosya->RecCnt != 1)
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);

	$IfSyoubou = $myGyosya->IsSyoubou ? true : false;
	$IfBouka = $myGyosya->IsBouka ? true : false;
	$IfNotRKI = true;
} else {
	$IfSyoubou = true;
	$IfBouka = true;
	$IfNotRKI = false;

	// $UserIDにnespeが含まれていたらIfNotRKIをtrueにする
	if (strpos($UserID, 'nespe') !== false) {
		$IfNotRKI = true;
	}

}

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "u.UserCD, ";
$sql .= "u.LastName ";		#1 2 名前

$myListObject->SelectSQL = $sql;

$sql = " FROM tUserM u ";
$sql .= " WHERE u.MukouFlg = FALSE";

$myListObject->Condition = $sql;
$myListObject->Order = "";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

if ($myListObject->Rows != 0) {
	$TantoLoop = $myListObject->Rows;
	for ($i = 0; $i < $TantoLoop; $i++) {
		$TantoCD[$i] = $myListObject->GetValue($i, 0);
		$TantoName[$i] = $myListObject->GetValue($i, 1);
		$TantoNameArray[$TantoCD[$i]] = $TantoName[$i];
	}
}
unset($myListObject);

########################################################
# 物件情報取得
########################################################
$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1)
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);

$BukkenName = $myBukken->BukkenName;
$KenmeiNo = $myBukken->KenmeiNo;
$KanriGaisya = $myBukken->KanriGaisya;
$Kosu = $myBukken->Kosu;
$Address = $myBukken->Address;
$GyosyaCD = $myBukken->GyosyaCD;

$KikiStatus = $myBukken->KikiStatus;
$StatusInfo = JudgeStatus($KikiStatus, STATUS);

$BousaiFlg = $myBukken->BousaiFlg;

$TantoCD1 = $myBukken->TantoCD1;
$TantoCD2 = $myBukken->TantoCD2;
$TantoCD3 = $myBukken->TantoCD3;
$TantoCD4 = $myBukken->TantoCD4;
$TantoCD5 = $myBukken->TantoCD5;
$TantoCD1Name = $TantoNameArray[$TantoCD1];
$TantoCD2Name = $TantoNameArray[$TantoCD2];
$TantoCD3Name = $TantoNameArray[$TantoCD3];
$TantoCD4Name = $TantoNameArray[$TantoCD4];
$TantoCD5Name = $TantoNameArray[$TantoCD5];

$Biko = $myBukken->Biko;


$ClientCD = $myBukken->ClientCD;
$myClient = new Client($myDB);
if (!$myClient->executeSelect(" ClientCD =" . $ClientCD, ""))
	trigger_error('Getting Gyosya Failed.', E_USER_ERROR);

$ClientName = $myClient->ClientName;

unset($myBukken);
#######################################################
#関数
#######################################################
function JudgeStatus($KikiStatus, $STATUS)
{
	if ($KikiStatus == $STATUS['依頼済']) {
		$StatusInfo = "日程入力依頼済";
	} else if ($KikiStatus == $STATUS['日程入力済']) {
		$StatusInfo = "日程登録済";
	}
	return $StatusInfo;
}
########################################################
# コンテンツ表示
########################################################

$CNT_FILE = "s_menu_test.tpl";

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
