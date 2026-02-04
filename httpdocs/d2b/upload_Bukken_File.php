<?php

if (!empty($_FILES)) {

	include_once "setting.properties";
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
	include_once _CLS_DIR . "SPUSFile.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSUser.cls";

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);	
	
	$editBukkenCD = SPFWParameter::getValues("editBukkenCD");
	$ClientCD = SPFWParameter::getValues("ClientCD");
	$FileKind = SPFWParameter::getValues('FileKind');
	if($FileKind == 1){
		$FolderName = "Bukken";
	}else if($FileKind == 2){
		$FolderName = "Report";
	}else if($FileKind == 3){
		$FolderName = "Notice";
	}else{
		$FolderName = "Bukken";
	}

	########################################################
	# サーバーに一時アップロード処理
	########################################################
	if ($editBukkenCD > 0) {

		if($FileKind == "1"){
			$targetPath = "E:/xampp8.2.4/kotei/httpdocs/kojifile/Bukken";
		}else if($FileKind == "2"){
			$targetPath = "E:/xampp8.2.4/kotei/httpdocs/kojifile/Report";

			$myBukken = new Bukken($myDB);
			if (!$myBukken->executeSelect(" BukkenCD = '" . $editBukkenCD . "' AND MukouFlg = FALSE", "")) {
				trigger_error("Getting Bukken Failed.", E_USER_ERROR);
			}

			$myBukken->ReportSubmitted = 1;
			$TantoCD1 = $myBukken->TantoCD1; 
			$TantoCD2 = $myBukken->TantoCD2;
			$BukkenName = $myBukken->BukkenName;

			if($TantoCD1){
				$myUser = new User($myDB);
				if (!$myUser->executeSelect("UserCD = " . $TantoCD1." AND MukouFlg = FALSE", "") || $myUser->RecCnt != 1){
					trigger_error("Getting Bukken Failed.", E_USER_ERROR);
				}
				$TantoCD1Address1 = $myUser->Address1;
				unset($myUser);
			}
			if($TantoCD2){
				$myUser = new User($myDB);
				if (!$myUser->executeSelect("UserCD = " . $TantoCD2." AND MukouFlg = FALSE", "") || $myUser->RecCnt != 1){
					trigger_error("Getting Bukken Failed.", E_USER_ERROR);
				}
				$TantoCD2Address1 = $myUser->Address1;
				unset($myUser);
			}

			if($TantoCD1Address1 OR $TantoCD2Address1){
				mb_language("Japanese");
				mb_internal_encoding("UTF-8");
				if($TantoCD1Address1){
					$to .= $TantoCD1Address1.",";
				}
				if($TantoCD2Address1){
					$to .= $TantoCD2Address1;
				}
		
				$title = "【点検進捗管理】".$BukkenName."点検完了報告";
				$content = "\r\n";
				$content .= $BukkenName."\r\n";
				$content .= "点検完了報告書がアップロードされました。\r\n";
				$content .= "システムにログインし、物件情報をご確認ください。\r\n";
				$content .= "-------------------------------------------\r\n";
				$content .= "ダイア-点検進捗管理システム-\r\n";
				$content .= "-------------------------------------------\r\n";
				$content .= "URL:https://app5.489501.jp/kotei/login_form.php";
				$headers = "From: info@nespe.com";
				mb_send_mail($to, $title, $content,$headers);

			}


			if (!$myBukken->executeUpdate()){
				trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
			}			
			
		}else if($FileKind == "3"){
			$targetPath = "E:/xampp8.2.4/kotei/httpdocs/kojifile/Notice";
		}else{
			$targetPath = "E:/xampp8.2.4/kotei/httpdocs/kojifile/Bukken";
		}
				
		$tempFile = $_FILES['file']['tmp_name'];
		$RealFileName = $_FILES['file']['name'];
		$FileExtension = pathinfo($RealFileName, PATHINFO_EXTENSION);
		$FileName = pathinfo($RealFileName, PATHINFO_FILENAME);
		$randval = rand();

		$Now = date("YmdHis");
		$targetFile = $targetPath ."/".$randval."-".$Now . ".".$FileExtension ; 

		$myFile = new File($myDB);
		$myFile->ServerFileName = $FolderName."/".$randval."-".$Now.".".$FileExtension;
		$myFile->RealFileName = $RealFileName;
		$myFile->BukkenCD = $editBukkenCD;
		$myFile->FileKind = $FileKind;
		if (!$myFile->executeUpdate()){
			trigger_error("executeUpdate(myFile) Failed.", E_USER_ERROR);
		}
		unset($myFile);	

		// $random_name = md5(uniqid(rand(), 1));
		// $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		move_uploaded_file($tempFile, $targetFile); //6

	}


}
