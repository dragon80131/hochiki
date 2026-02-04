<?php
// #調査ミサイル
// $fh = fopen("aaa.txt", "a");
// fwrite($fh,"\n Start:");
// fclose($fh);
// #調査ミサイルEnd

if (!empty($_FILES)) {

	include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
	include_once _INC_DIR . "global.inc";
	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPFWTools.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSSetting.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPUSUploadFile.cls";

	$tempEditBukkenCD = SPFWParameter::getValues("editBukkenCD");
	$tempEditBuildingCD = SPFWParameter::getValues("editBuildingCD");
	$ClientCD = SPFWParameter::getValues("ClientCD");
	$TenkenKind = SPFWParameter::getValues('TenkenKind');

	$rKey 			= SPFWParameter::getValues("rKey");

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 認証動作
	########################################################
	$myUser = new User($myDB);
	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS2);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS2);

	$UserCD 		= $myUser->UserCD;
	unset($myUser);


	########################################################
	# サーバーに一時アップロード処理
	########################################################
	if ($tempEditBukkenCD > 0) {

		$tempFile = $_FILES['file']['tmp_name']; //3


		$directoryPath = "D:/xampp/htdocs/hochiki/httpdocs/kojifile/".$tempEditBukkenCD;
		if($tempEditBuildingCD){
			$directoryPath = "D:/xampp/htdocs/hochiki/httpdocs/kojifile/".$tempEditBukkenCD.'-'.$tempEditBuildingCD;
		}

		// ディレクトリが存在しない場合に作成
		if (!file_exists($directoryPath)) {
			mkdir($directoryPath, 0755, true); // 再帰的にディレクトリを作成
			echo "ディレクトリを作成しました。";
		} else {
			echo "ディレクトリは既に存在します。";
		}

		$tempFile = $_FILES['file']['tmp_name'];
		$fileName = basename($_FILES["file"]["name"]);
		$targetFile = $directoryPath . "/" . $_FILES['file']['name'];
		$targetFileName = $fileName;

		// ファイルがすでに存在する場合は、名前を変更する
		$i = 1;
		while (file_exists($targetFile)) {
			$fileInfo = pathinfo($fileName);
			$newFileName = $fileInfo['filename'] . "_$i." . $fileInfo['extension'];
			$targetFileName = $newFileName;
			$targetFile = $directoryPath . "/" .$newFileName;
			$i++;
		}		

		move_uploaded_file($tempFile, $targetFile);

		$newUploadFile = new UploadFile($myDB);
		$newUploadFile->UploadFileID = -1;
		$newUploadFile->BukkenCD = $tempEditBukkenCD;
		$newUploadFile->BuildingCD = $tempEditBuildingCD;
		$newUploadFile->UserCD = $UserCD;
		$newUploadFile->FileName = $targetFileName;
		$newUploadFile->FilePath = $targetFile;
		$newUploadFile->Updater = $UserCD;
		if (!$newUploadFile->executeUpdate())
			trigger_error("Updating Reservation Failed.", E_USER_ERROR);

	}
}