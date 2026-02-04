<?php
	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "global.inc";
	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSSetting.cls";
	include_once _CLS_DIR . "SPUSUploadFile.cls";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$UploadFileID = SPFWParameter::getValues("UploadFileID");

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

    $uploadFile = new UploadFile($myDB);
    if (!$uploadFile->executeSelect(" UploadFileID = $UploadFileID", "")) {
        echo "ファイルの削除中にエラーが発生しました";
        exit;
    }
    $filePath = $uploadFile->FilePath;
    if($uploadFile->executeDelete("UploadFileID = $UploadFileID")){
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                echo "ファイルが正常に削除されました: " . htmlspecialchars($fileName);
            } else {
                echo "ファイルの削除中にエラーが発生しました";
            }
        } else {
            echo "ファイルが見つかりません: " . htmlspecialchars($fileName);
        }
    }

} else {
    echo "不正なリクエストです。";
}
?>
