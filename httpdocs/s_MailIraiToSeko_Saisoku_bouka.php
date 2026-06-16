<?php

include_once "E:/xampp8.2.4/kotei/SPFW/inc/C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
// include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSKoji.cls";

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

$TargetMonth = date('n', strtotime('+2 month'));
########################################################
# 施工会社情報取得
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "GyosyaCD, ";
$sql .= "GyosyaName, ";
$sql .= "GyosyaMail, ";
$sql .= "GyosyaMail2 ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tGyosyaM ";
$sql .= " WHERE MukouFlg = FALSE ";
$sql .= " AND IsBouka = 1 ";

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$SekoCompanyLoop = $myListObject->Rows;
for ($i = 0; $i < $SekoCompanyLoop; $i++) {
	$GyosyaCD[$i] 	= $myListObject->GetValue($i, 0);
	$GyosyaName[$i]	= $myListObject->GetValue($i, 1);
	$GyosyaMail[$i]	= $myListObject->GetValue($i, 2);
	$GyosyaMail2[$i] = $myListObject->GetValue($i, 3);

	$myListObject2 = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "BukkenName ";
	$myListObject2->SelectSQL = $sql;
	$sql = " FROM tBukkenM";
	$sql .= " WHERE MukouFlg = FALSE AND GyosyaCD =" . $GyosyaCD[$i];
	$sql .= " AND ( BousaiStatus = 1 )";
	$sql .= " AND BousaiTenkenMonth = " . $TargetMonth;

	$myListObject2->Condition = $sql;
	$myListObject2->Order = "";
	$myListObject2->Limit = "allpage";

	if (!($myListObject2->GetList(1)))
		trigger_error("Getting Bukken List Failed.", E_USER_ERROR);

	$BukkenLoop = $myListObject2->Rows;
	for ($j = 0; $j < $BukkenLoop; $j++) {
		$BukkenName[$i] .= $myListObject2->GetValue($j, 0) . "\r\n";
	}
	unset($myListObject2);

	if (($GyosyaMail[$i] or $GyosyaMail2[$i]) and $BukkenName[$i]) {

		mb_language("Japanese");
		mb_internal_encoding("UTF-8");

		// $to[$i] .= "matsugami-c@nespe.com,";
		if ($GyosyaMail[$i]) {
			$to[$i] .= $GyosyaMail[$i] . ",";
		}
		if ($GyosyaMail2[$i]) {
			$to[$i] .= $GyosyaMail2[$i];
		}

		// $to[$i] = $GyosyaMail[$i];

		$title[$i] = "再通知【点検システム】" . $TargetMonth . "月点検分　実施日登録依頼";
		$content[$i] = $TargetMonth . "月点検予定の実施日登録が確認できません。\r\n";
		$content[$i] .= "システムにログインし、登録をお願いいたします。\r\n";
		$content[$i] .= "(未登録物件)\r\n";
		$content[$i] .= $BukkenName[$i] . "\r\n";
		$content[$i] .= "-------------------------------------------\r\n";
		$content[$i] .= "ダイア-点検進捗管理システム-\r\n";
		$content[$i] .= "-------------------------------------------\r\n";
		$content[$i] .= "URL:https://app5.489501.jp/kotei/login_form.php";
		$headers[$i] = "From: info@nespe.com";

		mb_send_mail($to[$i], $title[$i], $content[$i], $headers[$i]);
	}
}
unset($myListObject);
