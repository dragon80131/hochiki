<?php
$ds          = DIRECTORY_SEPARATOR;  //1

$storeFolder = '../kojifileSeko';   //2

if (!empty($_FILES)) {

    $tempFile = $_FILES['file']['tmp_name'];//3

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

    $targetFile =  $targetPath. $_FILES['file']['name'];  //5

    move_uploaded_file($tempFile,$targetFile);  //6





	########################################################
	# 写真台帳を営業支援へ連携
	########################################################

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

	// 営業支援
	include_once _CLS_DIR . "sf_kotei/"."SPUSFile.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	#### SFBukkenCD　をもってくる。
	$mySetting = new Setting($myDB);

	if (!$mySetting->executeSelect(" MukouFlg = FALSE", "")){
		$ErrorString = array();
		$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}
	$SFBukkenCD = $mySetting->SFBukkenCD;
	unset($mySetting);

	if ($SFBukkenCD) {

		########################################################
		# 営業支援　データベース接続 テーブル　tFileF 
		########################################################
		$myDB = new SPFWDatabase("kojikotei", "192.168.98.241", _USER_NAME, _PASSWD, FALSE);



		$image_name = $_FILES['file']['name']; // ファイル名
#		$image_name = mb_convert_encoding($image_name, 'euc-jp', 'utf-8');
		$image_name_kakuchoshi = pathinfo($image_name, PATHINFO_EXTENSION); // 拡張子
		$image_name_kakuchoshi = strtolower($image_name_kakuchoshi);


		// ファイルの取得
		$image_path = $targetFile ;
		$img_file = file_get_contents( $image_path );


#$fp = fopen("aaa.txt", "a");
#fwrite($fp, "\nSFBukkenCD:".$SFBukkenCD);
#fwrite($fp, "\nimage_name:".$image_name);
#fwrite($fp, "\nimage_name_kakuchoshi:".$image_name_kakuchoshi);
#fwrite($fp, "\nimage_path:".$image_path);


		//バイナリーファイル取得が成功した場合
		if($img_file){

			//4から始まるpdfファイルかエクセルのみ
			if( ( substr( $image_name , 0,2) == "4." AND $image_name_kakuchoshi == "pdf" ) or 
			    ( substr( $image_name , 0,2) == "4." AND $image_name_kakuchoshi == "xls" ) or 
			    ( substr( $image_name , 0,2) == "4." AND $image_name_kakuchoshi == "xlsx" ) ) {

				$image_name = ltrim($image_name,"4."); // ファイル名の4.を取り除く

				//画像を保存するSQL文の実行
				$myFile = new File($myDB);

				$myFile->FileCD = -1;

				$myFile->BukkenCD = $SFBukkenCD;
				$myFile->SekoStatus = 3;                 #3:完成図書　１:物件情報の関連資料　２：折衝記録の資料　4:更新ファイル
				$myFile->Device = "";                    #未使用
				$myFile->P001 = $image_name;             #ファイル名
				$myFile->P002 = $image_name_kakuchoshi;  #拡張子
				$myFile->File = $img_file;
				$myFile->Created = 0;
				$myFile->Updated = 0;
#fwrite($fp, "\n".__LINE__);
				if (!$myFile->executeUpdate()){
					$ErrorString = array();
					$ErrorString[] = "情報の更新に失敗しました。";
					showAdminSorryPage($ErrorString);
				}
#fwrite($fp, "\nOKOK");
			}else{
				#ファイルなし？ファイルサイズ0？
				$IfNG = TRUE;
			}
		}
#fclose($fp);
	}

}


?> 
