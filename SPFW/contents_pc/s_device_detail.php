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

	include_once _CLS_DIR . "SPUSDevice.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 入力チェック
	########################################################
	$editDeviceCD = $_POST["editDeviceCD"];

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

#	$Nickname = $myUser->Nickname;
#	$EMail = $myUser->EMail;


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
	# 新規でない場合:URL抽出処理
	########################################################

	if ($editDeviceCD > 0){
		$wTitle = "<<編集>>";

		$myDevice = new Device($myDB);

		if (!$myDevice->executeSelect("DeviceCD = " . $editDeviceCD, "") || $myDevice->RecCnt == 0){
			$ErrorString = array();
			$ErrorString[] = "メニューマスタ情報の抽出に失敗しました。";
			$ErrorLoop = count($ErrorString);
			$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
			unset($myTemplate);
			exit;
		}

		$wDeviceCD = $myDevice->DeviceCD;
		$wCategory = $myDevice->Category;
		$wBasicSystem = $myDevice->BasicSystem;
		$wDeviceName = $myDevice->DeviceName;
		$wShortName = $myDevice->ShortName;# |3|4|5|のようにデータがはいってる
		#$myReservation->MenuCD = SPFWTools::encodePluralValue($wMenuCD);
		$wKataban = $myDevice->Kataban;
		$wShiyoshoURL = $myDevice->ShiyoshoURL;
		$wManualURL = $myDevice->ManualURL;

		$Created = $myDevice->Created;
		$Creator = $myDevice->Creator;
		$Updated = $myDevice->Updated;
		$Updater = $myDevice->Updater;

		for ($i = 0; $i < $Choice5Loop; $i++) {
			$Choice5Selected[$i] = ($wCategory == $Choice5Value[$i]) ? ' selected' : NULL;
		}

	} else {
		$wTitle = "<<新規登録>>";
	}

		########################################################
	# 基本システム
	########################################################

	#リニューアルシステムリスト 489propertiesに定義
	for ($i = 0; $i < count($RNSYSTEMNAME); $i++){
		$RNSYSTEM_Value[$i] = $i;
	}




	########################################################
	# コンテンツ表示
	########################################################

	SPFWTemplate::setValue("work");


	$CNT_FILE = "s_device_detail.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>
