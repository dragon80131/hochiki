<?php

include_once "E:/xampp8.2.4/kotei/SPFW/inc/C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
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
# 物件情報取得
########################################################
$myListObject2 = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "BukkenName, ";
$sql .= "TantoCD1, ";
$sql .= "TantoCD2 ";
$myListObject2->SelectSQL = $sql;
$sql = " FROM tBukkenM";
$sql .= " WHERE MukouFlg = FALSE ";
$sql .= " AND ( KikiStatus = 1 OR KikiStatus = 0 )";
$sql .= " AND ( KikiTenkenMonth = '" . $TargetMonth . "' OR SougouTenkenMonth = '" . $TargetMonth . "')";
//ClientCDが131の物件(福岡支店）は除外
$sql .= " AND ClientCD != 131";

$myListObject2->Condition = $sql;
$myListObject2->Order = "";
$myListObject2->Limit = "allpage";

if (!($myListObject2->GetList(1)))
	trigger_error("Getting Bukken List Failed.", E_USER_ERROR);

$BukkenLoop = $myListObject2->Rows;
for ($j = 0; $j < $BukkenLoop; $j++) {

	$BukkenName[$j] = $myListObject2->GetValue($j, 0);
	$TantoCD1[$j] = $myListObject2->GetValue($j, 1);
	$TantoCD2[$j] = $myListObject2->GetValue($j, 2);

	// if ($TantoCD1[$j]) {
	// 	$myUser = new User($myDB);
	// 	if (!$myUser->executeSelect("UserCD = " . $TantoCD1[$j] . " AND MukouFlg = FALSE", "") || $myUser->RecCnt != 1) {
	// 		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	// 	}
	// 	$TantoCD1Address1[$j] = $myUser->Address1;
	// 	unset($myUser);
	// }

	// if ($TantoCD2[$j]) {
	// 	$myUser = new User($myDB);
	// 	if (!$myUser->executeSelect("UserCD = " . $TantoCD2[$j] . " AND MukouFlg = FALSE", "") || $myUser->RecCnt != 1) {
	// 		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	// 	}
	// 	$TantoCD2Address1[$j] = $myUser->Address1;
	// 	unset($myUser);
	// }

	// if ($TantoCD1Address1[$j] or $TantoCD2Address1[$j]) {
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");

	// $to[$j] .= "matsugami-c@nespe.com,";


	// 金子さんを追加
	$to[$j] .= "t.kaneko@rki.co.jp,";

	// if ($TantoCD1Address1[$j]) {
	// 	$to[$j] .= $TantoCD1Address1[$j] . ",";
	// }
	// if ($TantoCD2Address1[$j]) {
	// 	$to[$j] .= $TantoCD2Address1[$j] . ",";
	// }

	$title = "【点検進捗管理】" . $BukkenName[$j] . "実施日未登録のお知らせ";
	$content = "";
	$content .= $BukkenName[$j] . "\r\n";
	$content .= "点検実施日登録依頼から16日経過しましたが実施日登録が確認できません。点検業者へ登録依頼をお願いいたします。\r\n";
	$content .= "-------------------------------------------\r\n";
	$content .= "ダイア-点検進捗管理システム-\r\n";
	$content .= "-------------------------------------------\r\n";
	$content .= "URL:https://app5.489501.jp/kotei/login_form.php";
	$headers = "From: info@nespe.com";
	mb_send_mail($to[$j], $title, $content, $headers);
	// }
}

unset($myListObject2);
