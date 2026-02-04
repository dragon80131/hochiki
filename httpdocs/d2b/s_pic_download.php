<?php
	include_once "setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";

	include_once _CLS_DIR . "SPFWParameter.cls";
#	include_once _CLS_DIR . "reload.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 入力チェック
	########################################################

	if ($rKey == NULL) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}


	########################################################
	# 認証動作
	########################################################

	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}



	$editBukkenCD = SPFWParameter::getValues("editBukkenCD");
	$chk = SPFWParameter::getValues("chk");
#渡ってきた選択した写真を
#print_r($chk);
for($i = 0; $i < count($chk) ; $i++){
$wPictureCD = $wPictureCD.",".$chk[$i] ; 
}


	########################################################
	# パラメータ取得
	########################################################

######写真をSelectして、ファイルをフォルダにいれて、圧縮する部分
####まず upfile/images_genの中の　削除する。
	function deleteData ( $dir ) {
	    if ( $dirHandle = opendir ( $dir )) {
	        while ( false !== ( $fileName = readdir ( $dirHandle ) ) ) {
	            if ( $fileName != "." && $fileName != ".." ) {
	                unlink ( $dir.$fileName );
	            }
	        }
	        closedir ( $dirHandle );
	    }
	}
	deleteData ( './upfile/images_gen/' );
	deleteData ( './upfile/dl/' );



#	define( 'DB_HOST', _HOST_NAME );
#	define( 'DB_USER', _USER_NAME );
#	define( 'DB_PASS', _PASSWD );
#	define( 'DB_NAME', _MAIN_DB );

	// データベースに接続
#	$DB = mysql_connect( DB_HOST, DB_USER, DB_PASS );
#	mysql_select_db( DB_NAME, $DB );


$link = mysqli_connect( _HOST_NAME, _USER_NAME, _PASSWD , _MAIN_DB );





###選択された写真を取得する。

	$sql = "SELECT ";
	$sql .= "Device, ";		#カテゴリCD
	$sql .= "Pic, ";		#ファイル自体
	$sql .= "P003 ";		#拡張子
	$sql .= "FROM tPictureF ";
	$sql .= "WHERE PictureCD in ( 0".$wPictureCD.") ";#０はダミーで必要　SQL文確認
	$sql .= "AND MukouFlg = 0" ;

	// データの取得
#	$result = mysql_query( $sql );


	#表示、値取得の場合
	$result = mysqli_query( $link , $sql) or die(mysqli_error()) ;


	$No = 1;
	while ($row = mysqli_fetch_array( $result)) {
	
		$data = $row[1];
		$extension = $row[2];

		#ファイル名をわかりやすくする。
		switch( $row[0] ){
		case 9:
			# $Kyoyobu = mb_convert_encoding("共用部", 'euc-jp', 'sjis');
			$row[0] = "Kyoyobu";
			break;
		case 10:
			$row[0] = "Senyubu";
			break;
		case 8:
			$row[0] = "Zumen";
			break;
		case 11:
			$row[0] = "Gencho";
			break;
		case 13:
			$row[0] = "Heya";
			break;
		}

//		$file = "./upfile/images_gen/".$row[0]."_".$No.".".$extension;
		$file = "./upfile/images_gen/".$row[0]."_".$No.".jpg";

		$fp = fopen( $file , 'wb');
		if ($fp){

			if (flock($fp, LOCK_EX)){
				#echo "-LockOK- ";

				if (fwrite($fp,  $data) === FALSE){
					print('ファイル書き込みに失敗しました<br>');
					$IfErr1 = TURE;
				}else{
					#print('ファイルに書き込みました<br>');
				}
				flock($fp, LOCK_UN);

			}else{
				$IfErr1 = TURE;
			}
			$flag = fclose($fp);

		}else{
			$IfErr2 = TRUE;
		}
	$No = $No + 1;
	} #WhileのEnd



	if( $IfErr1 == "" and $IfErr2 == "" and $IfErr3 == ""){
		exec ('zip -r ./upfile/dl/image.zip ./upfile/images_gen/*');
		$IfNoErr = TRUE ;
	}

	SPFWTemplate::dropValue("ID");
###★★###写真をSelectして、ファイルをフォルダにいれて、圧縮する部分　END



	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = basename($_SERVER["SCRIPT_NAME"], ".php") .".tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
