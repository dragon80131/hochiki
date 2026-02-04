<?php
	include_once "setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";
	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWTools.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPUSUser.cls";

	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSDevice.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";

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
	$UserCD = $myUser->UserCD;

	$Extra5 = $myUser->Extra5;

	$IfKanrishaFlg = ( $myUser->Extra3 == "2" )? "TRUE":"" ;
	$IfNonKanrishaFlg = ( $myUser->Extra3 != "2" )? "TRUE":"" ;


	$wCategory = SPFWParameter::getValues('wCategory');


	$wRNsystem = SPFWParameter::getValues('wRNsystem');//nameに指定されている属性の値を取得
	$strRNsystem = SPFWTools::encodePluralValue($wRNsystem);//配列をパイプつなぎの文字列に変換
	$wDeviceName = SPFWParameter::getValues('wDeviceName');
	$wShortName = SPFWParameter::getValues('wShortName');
	$wKataban = SPFWParameter::getValues('wKataban');
	$wShiyoshoURL = SPFWParameter::getValues('wShiyoshoURL');
	$wManualURL = SPFWParameter::getValues('wManualURL');

	$editDeviceCD = $_POST["editDeviceCD"];
	$work = $_POST["work"];


	########################################################
	# 登録・更新等各種動作
	########################################################
	//postなら処理を実行する
	if($_SERVER['REQUEST_METHOD']==='POST'){
	#header('Location:http://localhost/keijiban.php');
	}

	if ($work == 1 & $Reload == ""){
		$ErrorString = array();
		if ( $wDeviceName == NULL) {
			$IfErrorDeviceName = TRUE;
			include_once("s_device_detail.php");
			exit;
		}
		if ( $wShortName == NULL) {
			$IfErrorShortName = TRUE;
			include_once("s_device_detail.php");
			exit;
		}

		$myDevice = new Device($myDB);

		if ($editDeviceCD > 0){
			if (!$myDevice->executeSelect("DeviceCD = " . $editDeviceCD, "") || $myDevice->RecCnt != 1){
				$ErrorString = array();
				$ErrorString[] = "tDeviceM情報の抽出に失敗しました。";
				showAdminSorryPage($ErrorString);
			}
			$myDevice->DeviceCD = $editDeviceCD;

		} else {
			$myDevice->DeviceCD = -1;
			$myDevice->Creator = $UserCD;
			if($wKataban != NULL){

				if (!$myDevice->executeSelect("Kataban = '".$wKataban."' and MukouFlg=false", "") || $myDevice->RecCnt != 0){
					$IfErrorKataban = TRUE ;

					include_once("s_device_detail.php");
					exit;
				}
			}
		}

		$myDevice->DeviceName = $wDeviceName;
		$myDevice->BasicSystem = $strRNsystem;//パイプ繋ぎの文字列をDBに登録
		$myDevice->ShortName = $wShortName;
		$myDevice->Kataban = $wKataban;
		$myDevice->Category = $wCategory;
		$myDevice->ShiyoshoURL = $wShiyoshoURL;
		$myDevice->ManualURL = $wManualURL;
		$myDevice->Updater = $UserCD;


		if (!$myDevice->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}
		else{
			$IfCreate = ($editDeviceCD == -1) ? TRUE : FALSE;
			$IfUpdate = ($editDeviceCD != -1) ? TRUE : FALSE;
		}
		SPFWTemplate::dropValue("editDeviceCD");
		//$myDevice->publishIt();

		$Reload = 1;
	}
	else if ($work == 2){
		$myDevice = new Device($myDB);

		$Condition = "DeviceCD = " . $editDeviceCD;

		if (!$myDevice->executeSelect($Condition, NULL) || $myDevice->RecCnt != 1){
			$ErrorString = array();
			$ErrorString[] = "メニューマスタ情報の抽出に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

		$myDevice->MukouFlg = TRUE;
		$myDevice->Updater = $UserCD;

		if ( !$myDevice->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

		$IfDelete = TRUE;
		SPFWTemplate::dropValue("editDeviceCD");
	}

	SPFWTemplate::dropValue('wDeviceCD');
	SPFWTemplate::dropValue('wRNsystem');// 配列
	SPFWTemplate::dropValue('wDeviceName');
	SPFWTemplate::dropValue('wShortName');
	SPFWTemplate::dropValue('wKataban');
	SPFWTemplate::dropValue('wCategory');
	SPFWTemplate::dropValue('wShiyoshoURL');
	SPFWTemplate::dropValue('wManualURL');


	########################################################
	# 機器種類用　Extra5　メニュー式
	########################################################

	$TextMode[1] = array(NULL, " istyle=\"1\"", " istyle=\"1\"", " mode=\"hiragana\"", " istyle=\"1\"");
	$TextMode[2] = array(NULL, " istyle=\"2\"", " istyle=\"2\"", " mode=\"katakana\"", " istyle=\"2\"");
	$TextMode[3] = array(NULL, " istyle=\"3\"", " istyle=\"3\"", " mode=\"alphabet\"", " istyle=\"3\"");
	$TextMode[4] = array(NULL, " istyle=\"4\"", " istyle=\"4\"", " mode=\"numeric\"", " istyle=\"4\"");
	$TextMode[5] = array(NULL, " istyle=\"4\"", " istyle=\"4\"", " mode=\"numeric\"", " istyle=\"4\"");

	$QuestionClient = ($TargetClientCD > 0) ? $TargetClientCD : 1;
	$QuestionObject = _OBJECT_DIR . 'question_' . sprintf("%03d", $QuestionClient) . '.obj';

	if (file_exists($QuestionObject)) {
		$ObjText = SPFWTools::getFile($QuestionObject);
		$Obj = unserialize($ObjText);

		$QuestionLoop = count($Obj['QuestionCD']);
		$QuestionCD = $Obj['QuestionCD'];
		$Question = $Obj['Question'];
		$Required = $Obj['Required'];
		$TypeOfQuestion = $Obj['TypeOfQuestion'];
		$Size = $Obj['Size'];
		$Cols = $Obj['Cols'];
		$Rows = $Obj['Rows'];
		$TextFormat = $Obj['TextFormat'];
		$Choices = $Obj['Choices'];
		$ColumnIndex = $Obj['ColumnIndex'];
		$InternalUse = $Obj['InternalUse'];

		for ($i = 0; $i < $QuestionLoop; $i++) {
			$No = $i + 1;
			if ($InternalUse[$i] == 't')
				continue;

			${'IfQuestion' . $No} = TRUE;
			${'IfQuestion' . $No . 'Required'} = $Required[$i];
			if ($TypeOfQuestion[$i] == 1 && $TextFormat[$i] == 5)
				${'IfDate' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 1)
				${'IfText' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 2)
				${'IfTextarea' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 3)
				${'IfRadio' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 4)#ここを利用
				${'IfSelect' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 5)
				${'IfCheckbox' . $No} = TRUE;

			${'Question' . $No} = $Question[$i];
			${'Size' . $No} = $Size[$i];
			${'Cols' . $No} = $Cols[$i];
			${'Rows' . $No} = $Rows[$i];

			$wColumnIndex = $ColumnIndex[$i];

			if ($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4 || $TypeOfQuestion[$i] == 5) {
				if ($vForm != 't') {
					if ($TypeOfQuestion[$i] == 5 && !is_array(${'wExtra' . $wColumnIndex}))
						${'wQuestion' . $No} = User::decodePluralValue(${'wExtra' . $wColumnIndex});
					else
						${'wQuestion' . $No} = ${'wExtra' . $wColumnIndex};
				}
				else
					${'wQuestion' . $No} = ${'wExtra' . $No};

				$regs = explode("\n", $Choices[$i]);
				${'Choice' . $No . 'Loop'} = count($regs);
				for ($j = 0; $j < count($regs); $j++) {
					${'Choice' . $No . 'Value'}[$j] = $j + 1;
					${'Choice' . $No . 'Name'}[$j] = $regs[$j];

					if ($TypeOfQuestion[$i] == 3)
						${'Choice' . $No . 'Checked'}[$j] = (${'wQuestion' . $No} == ${'Choice' . $No . 'Value'}[$j]) ? ' checked' : NULL;
					else if ($TypeOfQuestion[$i] == 4)#ここを利用
						${'Choice' . $No . 'Selected'}[$j] = (${'wQuestion' . $No} == ${'Choice' . $No . 'Value'}[$j]) ? ' selected' : NULL;
					else if ($TypeOfQuestion[$i] == 5)
						${'Choice' . $No . 'Checked'}[$j] = (is_array(${'wQuestion' . $No}) && array_search(${'Choice' . $No . 'Value'}[$j], ${'wQuestion' . $No}) !== FALSE) ? ' checked' : NULL;
				}
			}
			else {
				${'TextMode' . $No} = $TextMode[$TextFormat[$i]][$MyCarrier];
				if ($vForm != 't') {
					${'wQuestion' . $No} = ${'wExtra' . $wColumnIndex};
				}
				else
					${'wQuestion' . $No} = ${'wExtra' . $No};
			}
		}
	}


	########################################################
	# 機器一覧表示
	########################################################

	// ページクラスのインスタンス生成
	$myListObject = new SPFWListObject($myDB);

	// Select SQL を設定
	$sql = "SELECT ";
	$sql .= "DeviceCD, ";
	$sql .= "DeviceName, ";
	$sql .= "ShortName, ";
	$sql .= "Kataban, ";
	$sql .= "Category, ";
	$sql .= "BasicSystem, ";
	$sql .= "ShiyoshoURL, ";
	$sql .= "ManualURL ";

	$myListObject->SelectSQL = $sql;

	// WHERE Condition を設定
	$sql = " FROM tDeviceM";
	$sql .= " WHERE DeviceCD > 0 AND MukouFlg = FALSE";
	//$sql .= " AND Extra5 =".$Extra5;

	$myListObject->Condition = $sql;

	$myListObject->Order = "Category,Kataban";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);

	// データ表示
		$DeviceListLoop = $myListObject->Rows;

		for ($i = 0; $i < $DeviceListLoop; $i++) {
			$DeviceCD[$i] = $myListObject->GetValue($i, 0);
			$DeviceName[$i] = $myListObject->GetValue($i, 1);
			$ShortName[$i] = $myListObject->GetValue($i, 2);
			$Kataban[$i] = $myListObject->GetValue($i, 3);

			$Category[$i] =  $myListObject->GetValue($i, 4);
			$Category[$i] = $KIKICATEGORY[$Category[$i]];
			$BasicSystem[$i] =  $myListObject->GetValue($i, 5);
			//この時点では$BasicSystem[$i]は文字列が入ってる配列
			$BasicSystem[$i] = SPFWTools::decodePluralValue($BasicSystem[$i]);
			//文字列から配列に変換
			$BasicSystemLoop[$i] = count($BasicSystem[$i]);
		for ($j = 0; $j < $BasicSystemLoop[$i]; $j++) {
			$test[$i] = $BasicSystem[$i][$j];
		}

			if( $myListObject->GetValue($i, 6) != NULL )
			$ShiyoshoURL[$i] =  "<a href=".$myListObject->GetValue($i, 5).">○</a>";
			if( $myListObject->GetValue($i, 7) != NULL )
			$ManualURL[$i] = "<a href=".$myListObject->GetValue($i, 6).">○</a>";

		}

	// 	$myDevice = new Device($myDB);
		for ($i = 0; $i < $BasicSystemLoop; $i++) {
			if ($BasicSystemflg == true && $BasicSystem[$i] !== "") {
				$$BasicSystemDisp .= " ";
			}
			if ($BasicSystem[$i] !== "") {
				$BasicSystemDisp .= $RNSYSTEMNAME[$BasicSystem[$i]];
				$BasicSystemflg = true;
			} else {
		$BasicSystemDisp = '-';
	}
		if ($BasicSystem) {
						$BasicSystemDisp = "";
						$BasicSystemflg = false;
		}
					unset($myDevice);

	########################################################
	# コンテンツ表示
	########################################################

	SPFWTemplate::dropValue("work");
	SPFWTemplate::setValue("work", "");



	$CNT_FILE = "s_device_list_copy.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>