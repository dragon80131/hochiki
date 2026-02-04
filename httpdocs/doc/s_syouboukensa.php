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
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSBukkenMatrix.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


	########################################################
	# 認証動作
	########################################################
	$rKey = SPFWParameter::getValues('rKey');

	$myUser = new User($myDB);

	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS);

#	$Extra1 = $myUser->Extra1 ;#所属CD
#	$MyZokusei = $myUser->Extra3 ;		#管理ユーザ２一般ユーザ１

	unset($myUser);

	########################################################
	# 物件名表示
	########################################################

	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD > 0 AND MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD , ""))
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);

	$wBukkenName = $myBukken->BukkenName;

	unset($myBukken);


	$DateNameLoop = count( $DATENAME );
	for( $i=0; $i<$DateNameLoop; $i++){
		$DateNameCD[$i] = $i + 1;
	}


	########################################################
	# 工事情報抽出
	########################################################

	// 工事情報が登録されていないとすすめないようにする
	$myKoji = new Koji($myDB);

	if (!$myKoji->executeSelect(" BukkenCD = ".$editBukkenCD , "")) {
		trigger_error("Getting Koji Failed.", E_USER_ERROR);
	}

	if ($myKoji->RecCnt != 1) {

		#工事情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "工事情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	}

	$ShoboTokurei = $myKoji->ShoboTokurei;
	if($ShoboTokurei==1){
		$If170 =TRUE;
		$Juko220Disabled = 'disabled';
		$Kyoju220Disabled ='disabled';

	}elseif($ShoboTokurei==2){
		$If220Juko = TRUE;
		$Disabled170 ='disabled';
		$Kyoju220Disabled ='disabled';
		$Juko220Checked='checked';
	}elseif($ShoboTokurei==3){
		$If220Kyoju =TRUE;
		$Disabled170 ='disabled';
		$Juko220Disabled = 'disabled';
		$Kyoju220Checked='checked';
	}else{

	}
	unset($myKoji);



	########################################################
	# 部屋情報抽出
	########################################################
	$myBukkenMatrix = new BukkenMatrix($myDB);

	if(!$myBukkenMatrix->executeSelect("BukkenCD = ".$editBukkenCD ,"")){
		trigger_error("Getting myBukkenMatrix Failed.", E_USER_ERROR);
	}

	if ($myBukkenMatrix->RecCnt != 1) {

		#部屋情報登録がまだ
		$ErrorString = array();
		$ErrorString[] = "部屋情報を登録してください。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ERROR_TPL, $MyCarrier, TRUE);
		exit;

	}

	$KaiRoom = $myBukkenMatrix->KaiRoom;

	$KaiRoom2 = SPFWTools::decodePluralValue($KaiRoom);#配列に変換
	
	if($Kyoju220Checked=='checked')
		$KaiRoom3 = "<table border='1' id='KaiRoom5' style='display:table;'><tr>";
	else
		$KaiRoom3 = "<table border='1' id='KaiRoom5' style='display:none;'><tr>";

	for($i=0;$i<count($KaiRoom2);$i++){

		$KaiRoom3 .= "<td><input type='checkbox' name='KaiRoom4[]' id='KaiRoomID".$i."' value=".$KaiRoom2[$i].">".$KaiRoom2[$i]."</td>";

#		if(substr($KaiRoom2[$i],0,strlen($KaiRoom2[$i])-2 ) !==str_replace( substr($KaiRoom2[$i+1],-2),"",$KaiRoom2[$i+1]) ){
		if( substr($KaiRoom2[$i],0,strlen($KaiRoom2[$i])-2 ) !== substr($KaiRoom2[$i+1],0,strlen($KaiRoom2[$i+1])-2 ) ){
#$KaiRoomCurrent = substr($KaiRoom2[$i],0,strlen($KaiRoom2[$i])-2 );
#$KaiRoomNext =  substr($KaiRoom2[$i+1],0,strlen($KaiRoom2[$i+1])-2 );
#echo "<br>".__LINE__."行目:".$KaiRoom2[$i];	
#echo "<br>".__LINE__."行目:".$KaiRoomCurrent."   ".$KaiRoomNext ;

		$KaiRoom3 .= "</tr><tr>";
		}
	}

	$KaiRoom3 .= "</tr></table>";
	$KaiRoomSuu = count($KaiRoom2);



	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_syouboukensa.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
