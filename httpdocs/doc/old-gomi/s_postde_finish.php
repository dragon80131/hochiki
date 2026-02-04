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

	include_once _CLS_DIR . "SPUSBukken.cls";
#	include_once _CLS_DIR . "SPUSTanto.cls";
	include_once _CLS_DIR . "SPUSSiten.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSIraiFile.cls";
	include_once _CLS_DIR . "SPUSPostde.cls";


	include_once _CLS_DIR . "SPFWParameter.cls";
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$rKey = SPFWParameter::getValues('rKey');


	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


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

	$wUserCD = $myUser->UserCD;
	$MyShozokuCD = $myUser->Extra1 ;	#所属支店CD
	$MyZokusei = $myUser->Extra3 ;		#管理ユーザ２一般ユーザ１
	$MyEigyoshoCD = $myUser->Extra4 ;	#営業所CD nespeユーザはNULLになってる

	########################################################
	# パラメータ取得
	########################################################
	
	echo "<a href=\"s_postde.php$QUERY&editBukkenCD=$editBukkenCD \">back</a><br><br>";

	
	$kojiwaku = SPFWParameter::getValues('kojiwaku');
	$kojijun = SPFWParameter::getValues('kojijun');
	$Notes = SPFWParameter::getValues('Notes');
	$filename = SPFWParameter::getValues('filename');
	$dataName = SPFWParameter::getValues('dataName');
	$extension = SPFWParameter::getValues('extension');


echo "<br>68行目".$filename;
/*	$myPostde = new Postde($myDB);
	
	$myPostde->BukkenCD=$editBukkenCD;
//	$myPostde->PostPic=$
	$myPostde->Kojiwaku=$kojiwaku;
	$myPostde->KojiPattern=$kojijun;
	$myPostde->Notes=$Note;

	if(!$myPostde->executeUpdate())
		trigger_error("Updating Postde Failed.", E_USER_ERROR);
*/
	$dir = "upload/";



	//画像ファイルの保存場所
	$image_path = $filename;  #縮尺用

	####
	//データベースに接続する変数名を宣言しておく_HOST_NAME, _USER_NAME, _PASSWD
	define( 'DB_HOST', _HOST_NAME );
	define( 'DB_USER', _USER_NAME );
	define( 'DB_PASS', _PASSWD );
	define( 'DB_NAME', _MAIN_DB );


	//データベースに接続する
	$db_link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	// 画像の取得
	$img_file = file_get_contents( $image_path );#縮小なし

	// 画像の取得
	#$img_file = file_get_contents( $img_url );

	//画像取得が成功した場合
	//画像取得が成功した場合
	if($img_file){

		//画像をバイナリに変換
		$img_binary = mysqli_real_escape_string( $db_link, $img_file ); // Pic用
		$Today = date('Y-m-d h:i:s');


		//画像を保存するSQL文の実行

#		$result = mysqli_query( $db_link,'INSERT INTO tPictureF(PostdeCD,BukkenCD,Kojiwaku,KojiPattern,PostPic,Notes,Created,Creator,Updated,Updater) VALUES  ("'.-1.'","'.$editBukkenCD.'","'.$kojiwaku.'","'.$kojijun.'","'.$img_binary2.'","'.$Notes.'" ,\'0\',"'.$Today.'",\'0\',"'.$Today.'",\'0\')');	
#		$result = mysqli_query( $db_link,"INSERT INTO tPostdeF(PostdeCD,BukkenCD,Kojiwaku,KojiPattern,PostPic,Notes,MukouFlg,Created,Creator,Updated,Updater) VALUES( -1 ,".$editBukkenCD." , \'". $kojiwaku ."\', ". $kojijun ." ,\'".$img_binary."\',\'".$Notes."\' ,0,".$Today.",0,".$Today.",0)");	
		$result = mysqli_query( $db_link,'INSERT INTO tPostdeF(BukkenCD,Kojiwaku,KojiPattern,PostPic,Notes,MukouFlg,Created,Creator,Updated,Updater) VALUES  ('.$editBukkenCD.',\''.$kojiwaku.'\','.$kojijun.',\''.$img_binary.'\',\''.$Notes.'\',0,\''.$Today.'\',\''.$wUserCD.'\',\''.$Today.'\',\''.$wUserCD.'\')');

		//結果の表示
		if($result){
			$IfOK2 = TRUE;
			echo "<FONT size=2><br>画像をデータベースに保存しました。</FONT>";
		}else{
			$IfNG2 = TRUE; 
			echo "<br>保存できませんでした。";
		}
	}else{
		$IfNG = TRUE; #echo("画像ファイルを選択してください。");
	}




############


/*		$result = mysqli_query( $db_link,
						'INSERT INTO tPostdeF( BukkenCD,Kojiwaku,KojiPattern,P002,PostPic,Notes,MukouFlg,Created,Creator,Updated,Updater
						) VALUES  (
						"'.$BukkenCD.'","'.$kojiwaku.'","'.$kojijun.'","'.$extension.'", "'.$img_binary.'" ,"'.$Note.'",\'0\',"'.$Today.'","'.$wUserCD.'","'.$Today.'","'.$wUserCD.'")');
	#					"'.$BukkenCD.'","'.$SekoStatus.'","'.$Device.'","'.$img_binary2.'","'.$extension.'", "'.$img_binary.'" ,"'.$wMemo.'",\'0\',"'.$Today.'",\'0\',"'.$Today.'",\'0\')');	W
			#			"'.$BukkenCD.'","'.$SekoStatus.'","'.$Device.'","'.$img_binary2.'", "'.$img_binary.'" ,\'0\',"'.$Today.'",\'0\',"'.$Today.'",\'0\')');	
*/
###########	
	########################################################
	# 依頼ファイルの削除
	########################################################
/*
	if ($work == "2" and $editIraiFileCD > 0 ) {

		$myIraiFile = new IraiFile($myDB);
		if (!$myIraiFile->executeSelect("IraiFileCD = $editIraiFileCD" )){
			trigger_error("Getting IraiFile Failed.", E_USER_ERROR);
		}

		$myIraiFile->MukouFlg = 1 ;
		if (!$myIraiFile->executeUpdate()){
			trigger_error("executeUpdate(IraiFile) Failed.", E_USER_ERROR);
		}
		SPFWTemplate::dropValue('work');

		unset($myIraiFile);
	}


*/
	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_postde_finish.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
