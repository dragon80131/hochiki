<?php

$ds          = DIRECTORY_SEPARATOR;  //1

$storeFolder = '../kojifileKosin';   //2

if (!empty($_FILES)) {#①File存在

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
	include_once _CLS_DIR . "reload.cls";
	include_once _CLS_DIR . "SPUSSetting.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";

	// 営業支援
	include_once _CLS_DIR . "SPUSFile.cls";

	$SFBukkenCD 	= SPFWParameter::getValues('BukkenCD'); 	// 物件管理番号

	if ($SFBukkenCD) {#②SFBukkenCD存在

		########################################################
		# 営業支援　データベース接続 テーブル　tFileF 
		########################################################
		// データベースコネクト
		$myDB = new SPFWDatabase(_MAIN_DB_sf, _HOST_NAME_sf, _USER_NAME_sf, _PASSWD_sf, FALSE);
		if (!$myDB->Connection)
			trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


		$image_name = $_FILES['file']['name']; // ファイル名
//		$image_name = mb_convert_encoding($image_name, 'euc-jp', 'utf-8');
		$image_name_kakuchoshi = pathinfo($image_name, PATHINFO_EXTENSION); // 拡張子



		// 画像の取得
		$image_path = $targetFile ;
		$img_file = file_get_contents( $image_path );



		//バイナリーファイル取得が成功した場合
		if($img_file){#③画像ファイル存在

			//画像を保存するSQL文の実行
			$myFile = new File($myDB);

			$myFile->FileCD = -1;

			$myFile->BukkenCD = $SFBukkenCD;
			$myFile->SekoStatus = 4;                 #4:更新ファイル　3:完成図書　１:物件情報の関連資料　２：折衝記録の資料
			$myFile->Device = "";                    #未使用
			$myFile->P001 = $image_name;             #ファイル名
			$myFile->P002 = $image_name_kakuchoshi;  #拡張子

			$myFile->P004 = "yotei";  #予定案内(yotei)か決定案内(ketei)か

			$myFile->File = $img_file;
			$myFile->Created = 1;
			$myFile->Updated = 1;

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

#調査ミサイル 
#  $fh = fopen("aaa.txt", "a");
#  fwrite($fh,"\n -2-:".$image_path);
#  fclose($fh);
#調査ミサイルEnd
		}#③画像ファイル存在 End


//fclose($fp);

	}#②SFBukkenCD存在 End
}#①File存在 End


?> 
