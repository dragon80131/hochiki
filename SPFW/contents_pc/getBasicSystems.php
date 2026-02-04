<?php
include_once "setting.properties";
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

include_once "./include/kenmei_connect.php";
include_once "./include/common.php";

// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


########################################################
# POSTデータ受け取り
########################################################

$BasicSystem = $_POST['BasicSystem'];


########################################################
# 機器明細 親機子機など選択メニュー
########################################################
#親機
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "DeviceName, Kataban ";
$myListObject->SelectSQL = $sql;
$sql = " FROM tDeviceM";
$sql .= " WHERE BasicSystem = '" . $BasicSystem . "' AND MukouFlg = FALSE";

$myListObject->Condition = $sql;
$myListObject->Order = "Kataban";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);
$OyaKatabanLoop = $myListObject->Rows;
for ($i = 0; $i < $OyaKatabanLoop; $i++) {
	$OyaDeviceName[$i] = $myListObject->GetValue($i, 0);
	$OyaDeviceKataban[$i] = $myListObject->GetValue($i, 1);

	$OyaDeviceList[$j] = "<option value='" . $OyaDeviceKataban[$j] . "'>".$OyaDeviceKataban[$j]." " . $OyaDeviceName[$j] . "</option>";
}
unset($myListObject);

#子機
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "DeviceName, Kataban ";
$myListObject->SelectSQL = $sql;
$sql = " FROM tDeviceM";
$sql .= " WHERE Category IN('2','3') AND MukouFlg = FALSE";

$myListObject->Condition = $sql;
$myListObject->Order = "Kataban";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);
$KokiKatabanLoop = $myListObject->Rows;
for ($i = 0; $i < $KokiKatabanLoop; $i++) {
	$KokiDeviceName[$i] = $myListObject->GetValue($i, 0);
	$KokiDeviceKataban[$i] = $myListObject->GetValue($i, 1);

	$KokiDeviceList[$j] = "<option value='" . $KokiDeviceKataban[$j] . "'>" .$KokiDeviceKataban[$j]." ". $KokiDeviceName[$j] . "</option>";
}
unset($myListObject);

header('Content-type:application/json; charset=utf8');
echo json_encode(array($SalesList, $DesignList, $ConstructList));