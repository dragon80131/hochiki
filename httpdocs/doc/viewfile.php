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

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

###りある写真用
define( 'DB_HOST', _HOST_NAME );
define( 'DB_USER', _USER_NAME );
define( 'DB_PASS', _PASSWD );
define( 'DB_NAME', _MAIN_DB );



	$BukkenCD = $_GET['BukkenCD'];
	$FileCD = $_GET['FileCD'];
#	$FileCD = "10631";

	// データベースに接続
#	$DB = mysqli_connect( DB_HOST, DB_USER, DB_PASS );
#	mysqli_select_db( $DB, DB_NAME );

	$mysqli = new mysqli(_HOST_NAME, _USER_NAME, _PASSWD, _MAIN_DB);

	// データの取得
	$sql = 'SELECT FileCD,File,P001,P002 FROM tFileF WHERE FileCD = '.$FileCD.' and MukouFlg = 0' ;

#	$result = mysql_query( $sql );
#	$row = mysql_fetch_array( $result);

	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();


	$fileName = $row["P001"]; // ファイル名
	$P002 = $row["P002"]; // 拡張子


	// ダウンロードするファイル名
	//$fileName = mb_convert_encoding($fileName, 'sjis-win', 'UTF-8');
	$fileName = mb_convert_encoding($fileName, "SJIS", "UTF-8");
	$fileName = str_replace(","," ",$fileName); //DL時エラーになるので置き換え

	// 拡張子が保存されていない場合
	if ($P002 == "") {
		$P002 = pathinfo($fileName, PATHINFO_EXTENSION);
	}
	$P002 = strtolower($P002); // 小文字に



	// ヘッダ
	// word,excel,パワポ,pdf,png,jpg,gifはDL確認済
	if ( ($P002 == "doc") or ($P002 == "docx") ) {
		header('Content-Type: application/msword');

	} else if ( ($P002 == "xls") or ($P002 == "xlsx") ) {
		header('Content-Type: application/vnd.ms-excel');

	} else if ( ($P002 == "ppt") or ($P002 == "pptx") ) {
		header('Content-Type: application/vnd.ms-powerpoint');

	} else if ( $P002 == "pdf" ) {
		header('Content-Type: application/pdf');

	} else if ( ($P002 == "jpg") or ($P002 == "jpeg") ) {
		header('Content-Type: image/jpeg');

	} else if ( $P002 == "png" ) {
		header('Content-Type: image/png');

	} else if ( $P002 == "gif" ) {
		header('Content-Type: image/gif');

	} else {
		header('Content-Type: application/octet-stream');

	}

	//header("Content-Disposition: inline; filename=".$fileName);
	header("Content-Disposition: attachment; filename='$fileName'");

	// 対象ファイルを出力する。
	echo $row["File"];


?>
