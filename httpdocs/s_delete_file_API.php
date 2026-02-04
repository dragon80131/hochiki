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
include_once _CLS_DIR . "SPUSFile.cls";


$TargetFileCD = $_POST['TargetFileCD'];
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
	if ($TargetFileCD) {
		$statement = ' SELECT ServerFileName FROM tFileF WHERE FileCD = "' . $TargetFileCD . '"';
		$stmt = $dbh->prepare($statement);
		$stmt->execute();
		$FileInfo = $stmt->fetchAll();
		$filepath = $FileInfo[0]['ServerFileName'];
		$statement = ' DELETE FROM tFileF WHERE FileCD = "' . $TargetFileCD . '" LIMIT 1 ';
		$stmt = $dbh->prepare($statement);
		$stmt->execute();

		if ($filepath) {
			unlink("E:/xampp8.2.4/kotei/httpdocs/kojifile/" . $filepath);
		}
	}
} catch (Exception $ex) {
	var_dump($ex);
}
