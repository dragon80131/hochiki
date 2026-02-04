<?php

$ds          = DIRECTORY_SEPARATOR;  //1

$storeFolder = '../kojifileKosin';   //2

if (!empty($_FILES)) {

    $tempFile = $_FILES['file']['tmp_name'];  //3

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

    $targetFile =  $targetPath. $_FILES['file']['name'];  //5

    move_uploaded_file($tempFile,$targetFile);  //6


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
	include_once _CLS_DIR . "SPFWParameter.cls";

	include_once _CLS_DIR . "SPUSFile.cls";


	$SFBukkenCD = SPFWParameter::getValues('BukkenCD'); // 物件管理番号
	$UserCD = SPFWParameter::getValues('UserCD'); // 登録者ユーザーCD

	if ($SFBukkenCD) {

		########################################################
		# 営業支援　データベース接続 テーブル　tFileF 
		########################################################
		// データベースコネクト
		$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
		if (!$myDB->Connection)
			trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


		$image_name = $_FILES['file']['name']; // ファイル名
		//$image_name = mb_convert_encoding($image_name, 'euc-jp', 'utf-8');
		$image_name_kakuchoshi = pathinfo($image_name, PATHINFO_EXTENSION); // 拡張子


		// 画像の取得
		$image_path = $targetFile ;
		$img_file = file_get_contents( $image_path );


		//バイナリーファイル取得が成功した場合
		if($img_file){

			//画像を保存するSQL文の実行
			$myFile = new File($myDB);

			$myFile->FileCD = -1;
			$myFile->BukkenCD = $SFBukkenCD;
			$myFile->SekoStatus = "5"; #5:自動登録または自分で登録したファイル　4:予約更新ファイル　3:完成図書　１:物件情報の関連資料　２：折衝記録の資料
			$myFile->P001 = $image_name;             #ファイル名
			$myFile->P002 = $image_name_kakuchoshi;  #拡張子

			$myFile->File = $img_file;
			$myFile->Creator = $UserCD;
			$myFile->Updater = $UserCD;

			if (!$myFile->executeUpdate()){
				$ErrorString = array();
				$ErrorString[] = "情報の更新に失敗しました。";
				showAdminSorryPage($ErrorString);
				//fwrite($fp, "\nINSERT NG");
			}
				//fwrite($fp, "\nINSERT OK");

		}else{
			#ファイルなし？ファイルサイズ0？
			$IfNG = TRUE;
			//fwrite($fp, "\nNO FILE? FILE SIZE 0?");
		}


		//fclose($fp);


	}

}


?> 
