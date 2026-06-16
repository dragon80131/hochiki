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
include_once _CLS_DIR . "SPUSResidentsForm.cls";

include_once _CLS_DIR . "SPUSSettingDates.cls";
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

$dsn = 'mysql:host=localhost;dbname=kojikotei;charset=utf8';
$user = 'root';
$password = '';
try {
	$dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
	echo 'データベースにアクセスできません！' . $e->getMessage();
	exit;
}

try {
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	];
} catch (Exception $ex) {
	var_dump($ex);
}


########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');

$RoomNo = SPFWParameter::getValues('RoomNo');
$Name = SPFWParameter::getValues('Name');
$TEL = SPFWParameter::getValues('TEL');
$Contents = SPFWParameter::getValues('Contents');
$IsInRoom = SPFWParameter::getValues('IsInRoom');
$DemandDate = SPFWParameter::getValues('DemandDate');
$AMPM = SPFWParameter::getValues('ampm');
$Dates = SPFWParameter::getValues('dates');
$MailAddress = SPFWParameter::getValues('MailAddress');

$ToName = SPFWParameter::getValues('ToName');

$DatesData = SPFWParameter::getValues('DatesData');
########################################################
# 認証動作
########################################################

// $myUser = new User($myDB);

// if ($rKey) {

// 	// if ($rKey == NULL)
// 	// 	showSorryPage(_ILLEGAL_ACCESS);

// 	if (!$myUser->doAuthenticationByRegistKey($rKey))
// 		trigger_error("doAuthentication Failed.", E_USER_ERROR);

// 	// if ($myUser->UserCD == -1)
// 	// 	showSorryPage(_ILLEGAL_ACCESS);
// 	$UserCD 		= $myUser->UserCD;
// } else {
// 	$UserCD = "";
// }

// unset($myUser);


########################################################
# 設定日登録
########################################################
if ($editBukkenCD) {

	$statement = ' DELETE FROM tSettingDatesM WHERE BukkenCD = "' . $editBukkenCD . '" LIMIT 10 ';
	$stmt = $dbh->prepare($statement);
	$stmt->execute();


	if ($DatesData) {

		foreach ($DatesData as $key => $value) {

			$mySettingDates = new SettingDates($myDB);
			$mySettingDates->BukkenCD = $editBukkenCD;
			$mySettingDates->Date = $value;



			if (!$mySettingDates->executeUpdate()) {
				trigger_error("executeUpdate(mySettingDates) Failed.", E_USER_ERROR);
			}
			unset($mySettingDates);
		}
	}
}


########################################################
# お問い合わせ登録
########################################################
// $myBukken = new Bukken($myDB);

// if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
// 	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
// }

// $TantoCD1 = $myBukken->TantoCD1;
// $TantoCD2 = $myBukken->TantoCD2;
// $BukkenName = $myBukken->BukkenName;
// unset($myBukken);

// if ($TantoCD1) {

// 	$myUser = new User($myDB);
// 	if (!$myUser->executeSelect("UserCD = " . $TantoCD1 . " AND MukouFlg = FALSE", "") || $myUser->RecCnt != 1) {
// 		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
// 	}
// 	$TantoCD1Address1 = $myUser->Address1;

// 	unset($myUser);
// }




########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_Set_Dates_Finish.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
