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
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
#	include_once _CLS_DIR . "SPUSKenmei.cls";


	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 認証動作
	########################################################
/*
	$myUser = new User($myDB);

	if ($rKey == NULL) 
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey)) 
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) 
		showSorryPage(_ILLEGAL_ACCESS);

	$UserCD = $myUser->UserCD;
	$ID = $myUser->ID;
	unset($myUser);



	$wKenmeiNo = $editKenmeiNo;
#echo "chkChangePartsFee:".$chkPCSupportFee[0];




$editKenmeiNo = htmlspecialchars( $_GET['editKenmeiNo'] );


$editMitumoriCD = SPFWParameter::getValues('editMitumoriCD');

$wPackCD = htmlspecialchars( $_POST['wPackCD'] );
$wkname = htmlspecialchars( $_POST['wkname'] );

	########################################################
	# 見積情報取得
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "MitumoriNo, ";
	$sql .= "Kenmei, ";
	$sql .= "MitusakiName, ";
	$sql .= "MitusakiTantoName, ";
	$sql .= "BusyoYubin, ";
	$sql .= "BusyoAddress, ";
	$sql .= "BumonName, ";
	$sql .= "BusyoName, ";
	$sql .= "BusyoTel, ";
	$sql .= "BusyoFax, ";
	$sql .= "MituCreated, ";
	$sql .= "MituYuko, ";
	$sql .= "MituAllKingaku, ";
	$sql .= "Noki, ";
	$sql .= "Torihiki, ";
	$sql .= "Biko, ";
	$sql .= "PackCD ";
	$myListObject->SelectSQL = $sql;

	$sql = " FROM tMitumoriF ";
	$sql .= "WHERE MukouFlg = FALSE AND MitumoriCD = $editMitumoriCD ";
	$myListObject->Condition = $sql;
	$myListObject->Order = "1";
	$myListObject->Limit = "allpage";
	
	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$MitumoriNo = $myListObject->GetValue(0, 0);
		$Kenmei = $myListObject->GetValue(0, 1);
//		$MitusakiName = $myListObject->GetValue(0, 2);
		$MitusakiTantoName = $myListObject->GetValue(0, 3);
		$BusyoYubin = $myListObject->GetValue(0, 4);
		$BusyoYubin1 = substr($BusyoYubin,0,3);
		$BusyoYubin2 = substr($BusyoYubin,3,6);
		$BusyoAddress = $myListObject->GetValue(0, 5);
		$BumonName = $myListObject->GetValue(0, 6);
		$BusyoName = $myListObject->GetValue(0, 7);
		$BusyoTel = $myListObject->GetValue(0, 8);
		$BusyoFax = $myListObject->GetValue(0, 9);
		$MituCreated = $myListObject->GetValue(0, 10);
		$MituCreatedY = substr($MituCreated,0,4);
		$MituCreatedM = substr($MituCreated,5,2);
		$MituCreatedD = substr($MituCreated,8,2);
		$MituYuko = $myListObject->GetValue(0, 11);
		$MituAllKingaku = $myListObject->GetValue(0, 12);
		if ($MituAllKingaku != "")
			$MituAllKingaku = number_format($MituAllKingaku);
		$Noki = $myListObject->GetValue(0, 13);
		$Torihiki = $myListObject->GetValue(0, 14);
		$Biko = $myListObject->GetValue(0, 15);
		$PackCD = $myListObject->GetValue(0, 16);

		$data = [
			"MitumoriNo" => $MitumoriNo,
			"Kenmei" => $Kenmei,
//			"MitusakiName" => $MitusakiName,
			"MitusakiTantoName" => $MitusakiTantoName,
			"BusyoYubin" => $BusyoYubin,
			"BusyoYubin1" => $BusyoYubin1,
			"BusyoYubin2" => $BusyoYubin2,
			"BusyoAddress" => $BusyoAddress,
			"BumonName" => $BumonName,
			"BusyoName" => $BusyoName,
			"BusyoTel" => $BusyoTel,
			"BusyoFax" => $BusyoFax,
			"MituCreated" => $MituCreated,
			"MituCreatedY" => $MituCreatedY,
			"MituCreatedM" => $MituCreatedM,
			"MituCreatedD" => $MituCreatedD,
			"MituYuko" => $MituYuko,
			"MituAllKingaku" => $MituAllKingaku,
			"Noki" => $Noki,
			"Torihiki" => $Torihiki,
			"Biko" => $Biko
		];
	}

	########################################################
	# パック名取得
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "PackName ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tPackM ";
	$sql .= "WHERE MukouFlg = FALSE AND PackCD =".$wPackCD;

	$myListObject->Condition = $sql;
	$myListObject->Order = "1";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$PackName = $myListObject->GetValue(0, 0);
	}
	unset($myListObject);

	########################################################
	# 病院名取得
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "BukkenName, ";
	$sql .= "TantoName ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tKenmeiF ";
	$sql .= "WHERE MukouFlg = FALSE AND KenmeiNo ='".$editKenmeiNo."%'";

	$myListObject->Condition = $sql;
	$myListObject->Order = "1";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$BukkenName = $myListObject->GetValue(0, 0);
		$TantoCD = $myListObject->GetValue(0, 1);
	}
	unset($myListObject);

	########################################################
	#  担当者名、部署取得
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "b.BSYO_MEI, ";
	$sql .= "s.SYIN_KNJ ";
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tBSYO b, tSHIN s ";
	$sql .= "WHERE s.SYIN_CD = '".$ID."' and s.BSYO_CD = b.BSYO_CD";

	$myListObject->Condition = $sql;
	$myListObject->Order = "1";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	if ($myListObject->Rows == 1) {
		$Bsyomei = $myListObject->GetValue(0, 0);
		$TantoName = $myListObject->GetValue(0, 1);

	}
	unset($myListObject);


	// 今日
	$Today = date('Y').'年'.date('m').'月'.date('d').'日';

*/







	//ライブラリ読み込み
	//require_once './PHPWord/PHPWord.php';
	require_once ('./PHPWord/PHPWord.php');



	$PHPWord = new PHPWord();

	//テンプレート読み込み
	$template_filepath = "./template/tmp_WORD_tetuduki.docx";
	$document = $PHPWord->loadTemplate($template_filepath);

	/*
	###文字化け対処Start
	$DispTaioNotes = $BukkenName ;
	$BK1 =    mb_convert_encoding($DispTaioNotes, 'sjis', 'euc-jp');
	$BK2 =    mb_convert_encoding($DispTaioNotes, 'sjis', 'utf-8');
	$BK3 =    mb_convert_encoding($DispTaioNotes, 'euc-jp', 'sjis');
	$BK4 =    mb_convert_encoding($DispTaioNotes, 'euc-jp', 'utf-8');
	$BK5 =    mb_convert_encoding($DispTaioNotes, 'utf-8', 'sjis');
	$BK6 =    mb_convert_encoding($DispTaioNotes, 'utf-8', 'euc-jp');
	###文字化け対処End
	*/
	$document->setValue('PackName', $PackName);//パック名
	$document->setValue('Today', $Today);//日付
	$document->setValue('MitusakiName', $wkname);//契約相手方名
	$document->setValue('Bsyomei', $Bsyomei);//部署名
	$document->setValue('TantoName', $TantoName);//担当者名

	/*
	$document->setValue('Value4', 'Earth');
	$document->setValue('Value5', $BukkenName );
	$document->setValue('Value6', $Today );
	*/
	/*20170721コメントアウト
	$document->setValue('Value1', $BukkenName);
	$document->setValue('Value2', $PackName);
	$document->setValue('Value3', 'システム');
	$document->setValue('Value4', number_format(100000));
	$document->setValue('Value5', number_format(108000));
	*/



	/********ファイル書き出し処理*********/
	//ファイルの名前
	$fname = '手続書.docx';
	$fname = mb_convert_encoding($fname, 'sjis-win', 'UTF-8');

	//ファイルのサーバでの保存先
	$fpath = './template/'.$fname;

	#$document->save($fpath);
	$document->save("./template/".$fname);

	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fname");



	ob_end_clean();
	readfile($fpath);

	//ファイル削除
	unlink($fpath);


	unset($document);
	unset($PHPWord);



?>
