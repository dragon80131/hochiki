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
include_once _CLS_DIR . "SPUSResultReport.cls";
include_once _CLS_DIR . "SPUSResultReport.cls";
include_once _CLS_DIR . "SPUSResidentsForm.cls";

// include_once "./include/common.php";

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

// 二か月後に点検がある物件達の進捗状況を0にリセットする。
$TargetMonth = date('n', strtotime('+2 month'));
// $TargetMonth = date('n', strtotime('+1 month'));

$Target_Year_Month = date('Y-m', strtotime('+2 month'));
// $Target_Year_Month = date('Y-m', strtotime('+1 month'));

try {
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	];
	$statement = 'UPDATE tBukkenM SET KikiStatus = 1 , ReportSubmitted = 0  WHERE KikiTenkenMonth = "' . $TargetMonth . '" OR SougouTenkenMonth = "' . $TargetMonth . '"';
	$stmt = $dbh->prepare($statement);
	$stmt->execute();
	$reg_array = $stmt->fetchAll();
} catch (Exception $ex) {
	var_dump($ex);
}

########################################################
# 点検報告formのリセット
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "BukkenCD ";
$myListObject->SelectSQL = $sql;

$sql = "FROM tBukkenM ";
$sql .= "WHERE KikiTenkenMonth = '" . $TargetMonth . "' OR SougouTenkenMonth = '" . $TargetMonth . "'";

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$TargetBukkenLoop = $myListObject->Rows;
for ($j = 0; $j < $TargetBukkenLoop; $j++) {

	$TargetBukkenCD[$j] = $myListObject->GetValue($j, 0);
	if ($TargetBukkenCD[$j]) {

		$myResultReport = new ResultReport($myDB);
		if (!$myResultReport->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $TargetBukkenCD[$j], "") || $myResultReport->RecCnt != 1) {
			// trigger_error("Getting Bukken Failed.", E_USER_ERROR);
		} else {
			$myResultReport->MukouFlg = 1;
			if (!$myResultReport->executeUpdate()) {
				trigger_error("executeUpdate(myResultReport) Failed.", E_USER_ERROR);
			}
			unset($myResultReport);
		}

		$statement = 'UPDATE tResidentsFormF SET Moved_at = "' . $Target_Year_Month . '" WHERE BukkenCD = "' . $TargetBukkenCD[$j] . '" AND Moved_at IS NULL';
		$stmt = $dbh->prepare($statement);
		$stmt->execute();
		$reg_array = $stmt->fetchAll();

		unset($myResidentsForm);
	}
}
unset($myListObject);

########################################################
# 施工会社情報取得してメール送信
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
$sql .= " AND IsSyoubou = 1 ";

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
	$GyosyaMail2[$i]	= $myListObject->GetValue($i, 3);

	$myListObject2 = new SPFWListObject($myDB);
	$sql = "SELECT ";
	$sql .= "BukkenName ";
	$myListObject2->SelectSQL = $sql;
	$sql = " FROM tBukkenM";
	$sql .= " WHERE MukouFlg = FALSE AND GyosyaCD =" . $GyosyaCD[$i];
	$sql .= " AND ( KikiStatus = 1 OR KikiStatus = 0 )"; //施工への依頼中状態と物件作成直後の状態
	$sql .= " AND ( KikiTenkenMonth = '" . $TargetMonth . "' OR SougouTenkenMonth = '" . $TargetMonth . "')";

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

	if ($GyosyaMail[$i] or $GyosyaMail2[$i]) {
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		// $to[$i] .= "matsugami-c@nespe.com,";

		if ($GyosyaMail[$i]) {
			$to[$i] .= $GyosyaMail[$i] . ",";
		}
		if ($GyosyaMail2[$i]) {
			$to[$i] .= $GyosyaMail2[$i];
		}

		$title = "【点検システム】" . $TargetMonth . "月点検分　実施日登録依頼";
		$content = "システムにログインし、" . $TargetMonth . "月点検予定の、実施日程登録をお願いいたします。\r\n";
		$content .= "URL:https://app5.489501.jp/kotei/login_form.php";
		$content .= "\r\n-------------------------------------------\r\n";
		$content .= "ダイア-点検進捗管理システム-\r\n";
		$content .= "-------------------------------------------\r\n";
		$headers = "From: info@nespe.com";
		mb_send_mail($to[$i], $title, $content, $headers);
	}
}
unset($myListObject);
