<?php


#調査ミサイル
# $fh = fopen("aaa.txt", "a");
# fwrite($fh,"\n".basename(__FILE__)." ".__LINE__."行目 START:".$bbbb );
# fclose($fh);
#調査ミサイルEnd

if (!empty($_FILES)) {

	include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
	
	#	include_once _CLS_DIR . "SPUSPicture.cls";
	#	$ds = DIRECTORY_SEPARATOR; //1
	#	$storeFolder = '../template/'.$ClientCD ; //2

	$editBukkenCD = SPFWParameter::getValues("editBukkenCD");
	$ClientCD = SPFWParameter::getValues("ClientCD");
	$TenkenKind = SPFWParameter::getValues('TenkenKind');

	########################################################
	# サーバーに一時アップロード処理
	########################################################
	if ($editBukkenCD > 0) {

		$tempFile = $_FILES['file']['tmp_name']; //3

		// $targetPath = "E:/xampp8.2.4/kotei/httpdocs/template/".$ClientCD ; //4

		if($TenkenKind == "1"){
			$targetPath = "E:/xampp8.2.4/kotei/httpdocs/template/kiki";
		}else if($TenkenKind == "2"){
			$targetPath = "E:/xampp8.2.4/kotei/httpdocs/template/sougou";
		}else{
			$targetPath = "E:/xampp8.2.4/kotei/httpdocs/template/kiki";
		}
		
		// if(file_exists($targetPath)){
		// 	//存在したときの処理
		// 	echo "存在します";
		// }else{
		// 	//存在しないときの処理
		// 	$Command = 'mkdir ' . $targetPath ;
		// 	shell_exec($Command);
		// }
		
		$tempFile = $_FILES['file']['tmp_name'];  //3
		// $random_name = md5(uniqid(rand(), 1));
		// $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		#$targetFile = $targetPath . $editBukkenCD . "_" . date("YmdHis") . "_" . $random_name . "." . $extension; //5
		$targetFile = $targetPath ."/". $editBukkenCD . ".xlsx" ; //5
		move_uploaded_file($tempFile, $targetFile); //6

	}


}
