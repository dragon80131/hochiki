<?php
	$isAdminMode = TRUE;

	include_once "setting.properties";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPFWMobile.cls";
	include_once _CLS_DIR . "SPUSUser.cls";
	if ($IfPoint)
		include_once _CLS_DIR . "SPUSPointHistory.cls";

	########################################################
	# データベース接続
	########################################################

	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);

	########################################################
	# 管理者認証
	########################################################

	include_once "authorize.inc";

	########################################################
	# ターゲットクライアント確定
	########################################################

	if ($IfASP) {
		if ($IfAdminSystem)
			$TargetClientCD = $sClientCD;
		else if ($IfAdminClient)
			$TargetClientCD = $MyClientCD;
	}

	########################################################
	# 開封確認モード判定
	########################################################

	if ($editDeliveryCD > 0 && ($work2 == 1 || $work2 == 2)) {
		$OpenLogFlg = TRUE;
		include_once _CLS_DIR . 'SPUSOpenLog.cls';
		include_once _CLS_DIR . 'SPUSMailDelivery.cls';

		// 後で使うのでインスタンスを作っておく
		$myOpenLog = new OpenLog($myDB);
	}

	########################################################
	# パラメータ取得
	########################################################

	$wID = SPFWParameter::getValues('wID');
	$wPasswd = SPFWParameter::getValues('wPasswd');
	$wLastName = SPFWParameter::getValues('wLastName');
	$wFirstName = SPFWParameter::getValues('wFirstName');
	$wLastNameKana = SPFWParameter::getValues('wLastNameKana');
	$wFirstNameKana = SPFWParameter::getValues('wFirstNameKana');
	$wGender = SPFWParameter::getValues('wGender');
	$wEMail = SPFWParameter::getValues('wEMail');
	$wBirthdayYear = SPFWParameter::getValues('wBirthdayYear');
	$wBirthdayMonth = SPFWParameter::getValues('wBirthdayMonth');
	$wBirthdayDay = SPFWParameter::getValues('wBirthdayDay');
	$wZipCode = SPFWParameter::getValues('wZipCode');
	$wPrefecture = SPFWParameter::getValues('wPrefecture');
	$wAddress1 = SPFWParameter::getValues('wAddress1');
	$wAddress2 = SPFWParameter::getValues('wAddress2');
	$wAddress3 = SPFWParameter::getValues('wAddress3');
	$wAddressKana = SPFWParameter::getValues('wAddressKana');
	$wTEL = SPFWParameter::getValues('wTEL');
	for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
		$Index = $i + 1;
		${'wQuestion' . $Index} = SPFWParameter::getValues('wQuestion' . $Index);
	}

	if ($IfAjax) {
		for ($i = 0; $i < count($AjaxDatePost); $i++)
			SPFWParameter::wrapDate($AjaxDatePost[$i]);
	}

	########################################################
	# オブジェクトファイルのデコード
	########################################################

	$Filename = ($IfASP) ? _OBJECT_DIR . 'system_set_' . sprintf('%03d', $TargetClientCD) . '.obj' : _OBJECT_DIR . 'system_set.obj';

	if (file_exists($Filename)) {
		$ObjText = SPFWTools::getFile($Filename);
		$Obj = unserialize($ObjText);
		unset ($ObjText);
	}

	$wQuestionID = $Obj['UserList']['QuestionID'];
	$wQuestionName = $Obj['UserList']['QuestionName'];

	########################################################
	# 基本設問の読み込み
	########################################################

	$QuestionObject = ($IfASP) ? _OBJECT_DIR . 'standard_' . sprintf('%03d', $TargetClientCD) . '.obj' : _OBJECT_DIR . 'standard.obj';

	if (file_exists($QuestionObject)) {
		$ObjText = SPFWTools::getFile($QuestionObject);
		$Obj = unserialize($ObjText);
	}

	for ($i = 1; $i < count($PRESET_QUESTION_ID); $i++) {
		${'If' . $PRESET_QUESTION_ID[$i]} = $Obj['If' . $PRESET_QUESTION_ID[$i] . 'Use'];
		${'If' . $PRESET_QUESTION_ID[$i] . 'Required'} = $Obj['If' . $PRESET_QUESTION_ID[$i] . 'Required'];
		${$PRESET_QUESTION_ID[$i] . 'Name'} = $Obj[$PRESET_QUESTION_ID[$i] . 'Name'];
	}

	########################################################
	# 拡張設問の読み込み
	########################################################

	$QuestionClient = ($TargetClientCD > 0) ? $TargetClientCD : 1;
	$QuestionObject = _OBJECT_DIR . 'question_' . sprintf("%03d", $QuestionClient) . '.obj';
	if (file_exists($QuestionObject)) {
		$ObjText = SPFWTools::getFile($QuestionObject);
		$Obj = unserialize($ObjText);

		$QuestionLoop = count($Obj['QuestionCD']);
		$QuestionCD = $Obj['QuestionCD'];
		$Question = $Obj['Question'];
		$TypeOfQuestion = $Obj['TypeOfQuestion'];
		$Choices = $Obj['Choices'];
		$ColumnIndex = $Obj['ColumnIndex'];
		$TextFormat = $Obj['TextFormat'];
		$Required = $Obj['Required'];

		for ($i = 0; $i < $QuestionLoop; $i++) {
			$No = $i + 1;
			${'IfQuestion' . $No} = TRUE;
			${'IfExtra' . $No} = TRUE;
			${'IfExtra' . $No . 'Required'} = ($Required[$i]) ? TRUE : FALSE;
			if ($TypeOfQuestion[$i] == 1 && $TextFormat[$i] == 5)
				${'IfDate' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 1)
				${'IfText' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 2)
				${'IfTextarea' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 3)
				${'IfRadio' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 4)
				${'IfSelect' . $No} = TRUE;
			else if ($TypeOfQuestion[$i] == 5)
				${'IfCheckbox' . $No} = TRUE;

			${'Question' . $No} = $Question[$i];

			$wColumnIndex = $ColumnIndex[$i];

			if ($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4 || $TypeOfQuestion[$i] == 5) {
				$regs = explode("\n", $Choices[$i]);
				${'Choice' . $No . 'Loop'} = count($regs);
				for ($j = 0; $j < count($regs); $j++) {
					${'Choice' . $No . 'Value'}[$j] = $j + 1;
					${'Choice' . $No . 'Name'}[$j] = $regs[$j];

					if ($TypeOfQuestion[$i] == 3)
						${'Choice' . $No . 'Checked'}[$j] = (${'wQuestion' . $No} == ${'Choice' . $No . 'Value'}[$j]) ? ' checked' : NULL;
					else if ($TypeOfQuestion[$i] == 4)
						${'Choice' . $No . 'Selected'}[$j] = (${'wQuestion' . $No} == ${'Choice' . $No . 'Value'}[$j]) ? ' selected' : NULL;
					else if ($TypeOfQuestion[$i] == 5 && is_array(${'wQuestion' . $No}))
						${'Choice' . $No . 'Checked'}[$j] = (array_search(${'Choice' . $No . 'Value'}[$j], ${'wQuestion' . $No}) !== FALSE) ? ' checked' : NULL;
				}

				if ($TypeOfQuestion[$i] == 5)
					${'wExtra' . $wColumnIndex} = User::encodePluralValue(${'wQuestion' . $No});
				else
					${'wExtra' . $wColumnIndex} =${'wQuestion' . $No};
			}
			else
				${'wExtra' . $wColumnIndex} =${'wQuestion' . $No};
		}
	}

	########################################################
	# 登録・更新等各種動作
	########################################################
	if ($work == 1){
		########################################################
		# パラメータチェック/値加工
		########################################################
		$ErrorString = array();

		$wBirthday = $wBirthdayYear . $wBirthdayMonth . $wBirthdayDay;

		// 必須項目
		if ($IfIDRequired && $wID == NULL) $ErrorString[] = $IDName . "は必須項目です。";
		if ($IfPasswdRequired && $wPasswd == NULL) $ErrorString[] = $PasswdName . "は必須項目です。";
		if ($IfLastNameRequired && $wLastName == NULL) $ErrorString[] = $LastNameName . "は必須項目です。";
		if ($IfFirstNameRequired && $wFirstName == NULL) $ErrorString[] = $FirstNameName . "は必須項目です。";
		if ($IfLastNameKanaRequired && $wLastNameKana == NULL) $ErrorString[] = $LastNameKanaName . "は必須項目です。";
		if ($IfFirstNameKanaRequired && $wFirstNameKana == NULL) $ErrorString[] = $FirstNameKanaName . "は必須項目です。";
		if ($IfGenderRequired && $wGender == NULL) $ErrorString[] = $GenderName . "は必須項目です。";
		if ($IfEMailRequired && $wEMail == NULL) $ErrorString[] = $EMailName . "は必須項目です。";
		if ($IfBirthdayRequired && $wBirthday == NULL) $ErrorString[] = $BirthdayName . "は必須項目です。";
		if ($IfZipCodeRequired && $wZipCode == NULL) $ErrorString[] = $ZipCodeName . "は必須項目です。";
		if ($IfPrefectureRequired && $wPrefecture == NULL) $ErrorString[] = $PrefectureName . "は必須項目です。";
		if ($IfAddress1Required && $wAddress1 == NULL) $ErrorString[] = $Address1Name . "は必須項目です。";
		if ($IfAddress2Required && $wAddress2 == NULL) $ErrorString[] = $Address2Name . "は必須項目です。";
		if ($IfAddress3Required && $wAddress3 == NULL) $ErrorString[] = $Address3Name . "は必須項目です。";
		if ($IfAddressKanaRequired && $wAddressKana == NULL) $ErrorString[] = $AddressKanaName . "は必須項目です。";
		if ($IfTELRequired && $wTEL == NULL) $ErrorString[] = $TELName . "は必須項目です。";
		for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
			$Index = $i + 1;
			if (${'IfExtra' . $Index . 'Required'} && ${'wQuestion' . $Index} == NULL) $ErrorString[] = "『" . ${'Question' . $Index} . "』は必須項目です。";
		}
		if ($IfPointsRequired && $wPoints == NULL) $ErrorString[] = $PointsName . "は必須項目です。";
		if ($IfMailMagaFlgRequired && $wMailMagaFlg == NULL) $ErrorString[] = $MailMagaFlgName . "は必須項目です。";


		/*if ($wID != "" && !SPFWInputCheck::isAlphaNumeric($wID))
			$ErrorString[] = "IDは半角英数字で入力して下さい";
		if ($wPasswd != "" && !SPFWInputCheck::isAlphaNumeric($wPasswd))
			$ErrorString[] = "パスワードは半角英数字で入力して下さい";*/
		if ($wEMail != "" && !SPFWInputCheck::isRightEMail($wEMail))
			$ErrorString[] = "メールアドレスは半角英数字で入力してください";
		/*if ($wTel != "" && !SPFWInputCheck::isRightTelephoneNumber($wTel))
			$ErrorString[] = "電話番号は半角数字またはハイフンの組み合わせで入力してください";*/
		if ($wZipCode != "" && !SPFWInputCheck::isRightTelephoneNumber($wZipCode))
			$ErrorString[] = "郵便番号は半角数字またはハイフンの組み合わせで入力してください";
		/*if ($wPoints != "" && !SPFWInputCheck::isNumeric($wPoints))
			$ErrorString[] = "ポイントは半角数字で入力してください";*/
		if ($wJoinedYear != "" && $wJoinedMonth != "" && $wJoinedDay != "" && $wJoinedHour != "" && $wJoinedMinute != "" && $wJoinedSecond != ""){
			// 後で効率よく判定をかけるためフラグをセット
			$wJoinedFlg = true;
			$wJoined = $wJoinedYear . "/" . $wJoinedMonth . "/" . $wJoinedDay;
			if (!SPFWInputCheck::isRightDate($wJoined))
				$ErrorString[] = "入会日が正しく入力されていないようです";

			$wJoined = $wJoinedYear . "/" . $wJoinedMonth . "/" . $wJoinedDay . " " . $wJoinedHour . ":" . $wJoinedMinute . ":" . $wJoinedSecond;
		}
		if ($wWithdrawnYear != "" && $wWithdrawnMonth != "" && $wWithdrawnDay != "" && $wWithdrawnHour != "" && $wWithdrawnMinute != "" && $wWithdrawnSecond != ""){
			// 後で効率よく判定をかけるためフラグをセット
			$wWithdrawnFlg = true;
			$wWithdrawn = $wWithdrawnYear . "/" . $wWithdrawnMonth . "/" . $wWithdrawnDay;
			if (!SPFWInputCheck::isRightDate($wWithdrawn))
				$ErrorString[] = "退会日が正しく入力されていないようです";

			$wWithdrawn = $wWithdrawnYear . "/" . $wWithdrawnMonth . "/" . $wWithdrawnDay . " " . $wWithdrawnHour . ":" . $wWithdrawnMinute . ":" . $wWithdrawnSecond;
		}
		if ($wBirthdayYear != "" && $wBirthdayMonth != "" && $wBirthdayDay != ""){
			// 後で効率よく判定をかけるためフラグをセット
			$wBirthdayFlg = true;
			$wBirthday = $wBirthdayYear . "/" . $wBirthdayMonth . "/" . $wBirthdayDay;
			if (!SPFWInputCheck::isRightDate($wBirthday))
				$ErrorString[] = "誕生日が正しく入力されていないようです";
		}

		if ($IfPoint) {
			if ($wPoints != NULL && !is_numeric($wPoints))
				$ErrorString[] = "ポイントは半角数字で入力してください。";
			if ($wPoints != NULL && $wClassification == NULL)
				$ErrorString[] = "ポイント区分を選択してください。";
			if ($wPoints == NULL && $wClassification > 0)
				$ErrorString[] = "増減ポイント数を入力してください。";
		}

		$myUser = new User($myDB);
		if ($wID != "" && $myUser->isExistUserByID($wID, "", $editUserCD)){
			$ErrorString[] = "ご希望のIDは既に他の方に登録されているようです。";
		}
		unset($myUser);

		if (count($ErrorString) > 0){
			showAdminSorryPage($ErrorString);
		}

		$myUser = new User($myDB);

		if ($editUserCD > 0){
			if (!$myUser->executeSelect("UserCD = " . $editUserCD, "") || $myUser->RecCnt != 1){
				$ErrorString = array();
				$ErrorString[] = "アカウント情報の抽出に失敗しました。";
				showAdminSorryPage($ErrorString);
			}
		}
		else {
			$myUser->RegistKey = $myUser->getNewKey();
			$myUser->IdentifyKey = $myUser->getNewKey('IdentifyKey');
			$myUser->UserAgent = "ADMINISTRATOR";
			$myUser->Carrier = ($wEMail != NULL) ? SPFWMobile::getCarrierCodeByEMail($wEMail) : 1;
			if ($IfASP && $IfAdminClient)
				$myUser->ClientCD = $MyClientCD;
		}

		if ($IfID) $myUser->ID = $wID;
		if ($IfPasswd) $myUser->Passwd = $wPasswd;
		if ($IfLastName) $myUser->LastName = $wLastName;
		if ($IfFirstName) $myUser->FirstName = $wFirstName;
		if ($IfLastNameKana) $myUser->LastNameKana = $wLastNameKana;
		if ($IfFirstNameKana) $myUser->FirstNameKana = $wFirstNameKana;
		if ($IfGender) $myUser->Gender = $wGender;
		$myUser->EMail = $wEMail;
		if ($IfBirthday) $myUser->Birthday = $wBirthday;
		if ($IfZipCode) $myUser->ZipCode = $wZipCode;
		if ($IfPrefecture) $myUser->Prefecture = $wPrefecture;
		if ($IfAddress1) $myUser->Address1 = $wAddress1;
		if ($IfAddress2) $myUser->Address2 = $wAddress2;
		if ($IfAddress3) $myUser->Address3 = $wAddress3;
		if ($IfAddressKana) $myUser->AddressKana = $wAddressKana;
		if ($IfTEL) $myUser->TEL = $wTEL;
		for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
			$Index = $i + 1;
			if (${'IfExtra' . $Index}) {
				if (is_array(${'wQuestion' . $Index}))
					$myUser->{'Extra' . $ColumnIndex[$i]} = User::encodePluralValue(${'wQuestion' . $Index});
				else
					$myUser->{'Extra' . $ColumnIndex[$i]} = ${'wQuestion' . $Index};
			}
		}
		if ($IfPoints) $myUser->Points = $wPoints;
		if ($IfMailMagaFlg) $myUser->MailMagaFlg = $wMailMagaFlg;

		$myUser->Joined = $wJoined;
		$myUser->Withdrawn = $wWithdrawn;
		$myUser->UserType = $wUserType;
		$myUser->Notes = $wNotes;
		if ($editAdminCD == -1){
			$myUser->Creator = $AdminCD;
		}
		$myUser->Updater = $AdminCD;

		if ($myUser->RegistKey == NULL)
			$myUser->RegistKey = $myUser->getNewKey();

		if (!$IfDemo && !$myUser->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "アカウント情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}
		else{
			$IfCreate = ($editAccountCD == -1) ? TRUE : FALSE;
			$IfUpdate = ($editAccountCD != -1) ? TRUE : FALSE;
		}

		if ($IfPoint && $wPoints != NULL && $wClassification != NULL) {
			$myPointHistory = new PointHistory($myDB);
			$myPointHistory->LogCD = -1;
			$myPointHistory->UserCD = $myUser->UserCD;
			$myPointHistory->AdminCD = $AdminCD;
			$myPointHistory->Points = $wPoints;
			$myPointHistory->Classification = $wClassification;
			$myPointHistory->Notes = $wPointNotes;

			if (!$myPointHistory->executeUpdate()){
				$ErrorString = array();
				$ErrorString[] = "ポイント情報の更新に失敗しました。";
				showAdminSorryPage($ErrorString);
			}

			if (!$myUser->updatePoints($myUser->UserCD, $wPoints)){
				$ErrorString = array();
				$ErrorString[] = "ポイント情報の更新に失敗しました。";
				showAdminSorryPage($ErrorString);
			}
		}
	}
	else if ($work == 2){
		$myUser = new User($myDB);

		if (!$myUser->executeSelect("UserCD = " . $editUserCD, "") || $myUser->RecCnt != 1){
			$ErrorString = array();
			$ErrorString[] = "アカウント情報の抽出に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

		$myUser->Withdrawn = 'NOW()';
		$myUser->Notes .= "管理者操作による退会(" . date('Y/m/d H:i:s') . ")\n";

		if (!$myUser->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "アカウント情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}
	}
	else if ($work == 3){
		$myUser = new User($myDB);

		if (!$myUser->executeSelect("UserCD = " . $editUserCD, "") || $myUser->RecCnt != 1){
			$ErrorString = array();
			$ErrorString[] = "アカウント情報の抽出に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

		$myUser->Withdrawn = NULL;
		$myUser->Notes .= "管理者操作による復活(" . date('Y/m/d H:i:s') . ")\n";

		if (!$myUser->executeUpdate()){
			$ErrorString = array();
			$ErrorString[] = "アカウント情報の更新に失敗しました。";
			showAdminSorryPage($ErrorString);
		}
	}

	SPFWTemplate::dropValue(wID);
	SPFWTemplate::dropValue(wPasswd);
	SPFWTemplate::dropValue(wLastName);
	SPFWTemplate::dropValue(wFirstName);
	SPFWTemplate::dropValue(wLastNameKana);
	SPFWTemplate::dropValue(wFirstNameKana);
	SPFWTemplate::dropValue(wGender);
	SPFWTemplate::dropValue(wEMail);
	SPFWTemplate::dropValue(wBirthdayYear);
	SPFWTemplate::dropValue(wBirthdayMonth);
	SPFWTemplate::dropValue(wBirthdayDay);
	SPFWTemplate::dropValue(wZipCode);
	SPFWTemplate::dropValue(wPrefecture);
	SPFWTemplate::dropValue(wAddress1);
	SPFWTemplate::dropValue(wAddress2);
	SPFWTemplate::dropValue(wAddress3);
	SPFWTemplate::dropValue(wAddressKana);
	SPFWTemplate::dropValue(wTEL);
	SPFWTemplate::dropValue(wPoints);
	SPFWTemplate::dropValue(wMailMagaFlg);
	SPFWTemplate::dropValue(wJoinedYear);
	SPFWTemplate::dropValue(wJoinedMonth);
	SPFWTemplate::dropValue(wJoinedDay);
	SPFWTemplate::dropValue(wJoinedHour);
	SPFWTemplate::dropValue(wJoinedMinute);
	SPFWTemplate::dropValue(wJoinedSecond);
	SPFWTemplate::dropValue(wWithdrawnYear);
	SPFWTemplate::dropValue(wWithdrawnMonth);
	SPFWTemplate::dropValue(wWithdrawnDay);
	SPFWTemplate::dropValue(wWithdrawnHour);
	SPFWTemplate::dropValue(wWithdrawnMinute);
	SPFWTemplate::dropValue(wWithdrawnSecond);
	SPFWTemplate::dropValue(wUserType);
	SPFWTemplate::dropValue(wNotes);

	SPFWTemplate::dropValue(wPoints);
	SPFWTemplate::dropValue(wClassification);
	SPFWTemplate::dropValue(wPointNotes);

	for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
		$Index = $i + 1;
		SPFWTemplate::dropValue('wExtra' . $Index);
		SPFWTemplate::dropValue('wQuestion' . $Index);
	}

	########################################################
	# 初期値設定
	########################################################

	// 現在ページ
	if (!isset($myPage) || $myPage == "" || $myPage < 1) $myPage = 1;

	// 1ページ当たり件数
	if (!isset($cRowsPerPage)) $cRowsPerPage = $ROWS_PER_PAGE[_ROWS_PER_PAGE_DEFAULT_INDEX];

	########################################################
	# ソートの定義
	########################################################

	if (!isset($SortBy)) $SortBy = 1;
	$SortByStr[1] = array("会員コード", "UserCD");
	$SortByStr[2] = array("氏名", "LastName || FirstName");
	$SortByStr[3] = array("メールアドレス", "EMail");
	$SortByStr[4] = array("入会日時", "Joined");
	$SortByStr[5] = array("ステータス", "Withdrawn is NULL");

	########################################################
	# パラメータチェック/値加工
	########################################################

	$ErrorString = array();

	if ($IfASP && $IfAdminSystem && !($sClientCD > 0))
		$ErrorString[] = "検索対象のクライアントは必ず選択してください。";

	if ($sAgeF != NULL && !SPFWInputCheck::isNumeric($sAgeF))
		$ErrorString[] = "年齢(左側)が正しく入力されていないようです";
	if ($sAgeT != NULL && !SPFWInputCheck::isNumeric($sAgeT))
		$ErrorString[] = "年齢(右側)が正しく入力されていないようです";
	if ($sTEL != NULL && !SPFWInputCheck::isRightTelephoneNumber($sTEL))
		$ErrorString[] = "電話番号が正しく入力されていないようです";
	if ($sZipCode != NULL && !SPFWInputCheck::isNumeric($sZipCode))
		$ErrorString[] = "郵便番号が正しく入力されていないようです";

	if ($sBirthdayYear != NULL && $sBirthdayMonth != NULL && $sBirthdayDay != NULL){
		$sBirthday = $sBirthdayYear . "/" . $sBirthdayMonth . "/" . $sBirthdayDay;
		if (!SPFWInputCheck::isRightDate($sBirthday))
			$ErrorString[] = "誕生日が正しく入力されていないようです";
	}
	if ($sJoinedFYear != NULL && $sJoinedFMonth != NULL && $sJoinedFDay != NULL){
		$sJoinedF = $sJoinedFYear . "/" . $sJoinedFMonth . "/" . $sJoinedFDay;
		if (!SPFWInputCheck::isRightDate($sJoinedF))
			$ErrorString[] = "入会日(左側)が正しく入力されていないようです";
	}
	if ($sJoinedTYear != NULL && $sJoinedTMonth != NULL && $sJoinedTDay != NULL){
		$sJoinedT = $sJoinedTYear . "/" . $sJoinedTMonth . "/" . $sJoinedTDay;
		if (!SPFWInputCheck::isRightDate($sJoinedT))
			$ErrorString[] = "入会日(右側)が正しく入力されていないようです";
	}
	if ($sWithdrawnFYear != NULL && $sWithdrawnFMonth != NULL && $sWithdrawnFDay != NULL){
		$sWithdrawnF = $sWithdrawnFYear . "/" . $sWithdrawnFMonth . "/" . $sWithdrawnFDay;
		if (!SPFWInputCheck::isRightDate($sWithdrawnF))
			$ErrorString[] = "退会日(左側)が正しく入力されていないようです";
	}
	if ($sWithdrawnTYear != NULL && $sWithdrawnTMonth != NULL && $sWithdrawnTDay != NULL){
		$sWithdrawnT = $sWithdrawnTYear . "/" . $sWithdrawnTMonth . "/" . $sWithdrawnTDay;
		if (!SPFWInputCheck::isRightDate($sWithdrawnT))
			$ErrorString[] = "退会日(右側)が正しく入力されていないようです";
	}

	if (count($ErrorString) > 0){
		showAdminSorryPage($ErrorString);
	}

	########################################################
	# 検索パラメータに応じた条件SQL生成
	########################################################

	if ($sEMail != "")
		$whereSQL[] = "EMail LIKE '%" .$myDB->escapeString($sEMail) . "%'";
	if ($sID != "")
		$whereSQL[] = "ID LIKE '%" .$myDB->escapeString($sID) . "%'";
	if ($sLastName != "")
		$whereSQL[] = "LastName LIKE '%" .$myDB->escapeString($sLastName) . "%'";
	if ($sFirstName != "")
		$whereSQL[] = "FirstName LIKE '%" .$myDB->escapeString($sFirstName) . "%'";
	if ($sLastNameKana != "")
		$whereSQL[] = "LastNameKana LIKE '%" .$myDB->escapeString($sLastNameKana) . "%'";
	if ($sFirstNameKana != "")
		$whereSQL[] = "FirstNameKana LIKE '%" .$myDB->escapeString($sFirstNameKana) . "%'";
	if ($sTEL != "")
		$whereSQL[] = "translate(TEL, '-' , '') LIKE '%" . str_replace("-", "", $sTEL) . "%'";
	if ($MYSQL) {
		if ($sName != "")
			$whereSQL[] = "concat(LastName, FirstName) like '%" . $sName . "%'";
		if ($sNameKana != "")
			$whereSQL[] = "concat(LastNameKana, FirstNameKana) like '%" . $sNameKana . "%'";
		if ($sAgeF != "")
			$whereSQL[] = "(YEAR(CURDATE()) - YEAR(Birthday)) - (RIGHT(CURDATE(),5) < RIGHT(Birthday,5)) >= " . $sAgeF;
		if ($sAgeT != "")
			$whereSQL[] = "(YEAR(CURDATE()) - YEAR(Birthday)) - (RIGHT(CURDATE(),5) < RIGHT(Birthday,5)) <= " . $sAgeT;
		if ($sBirthday != "")
			$whereSQL[] = "date_format(Birthday, '%Y/%m/%d') = date_format('" . $sBirthday . "', '%Y/%m/%d')";
		if ($sJoinedF != "")
			$whereSQL[] = "date_format(Joined, '%Y/%m/%d') >= date_format('" . $sJoinedF . "', '%Y/%m/%d')";
		if ($sJoinedT != "")
			$whereSQL[] = "date_format(Joined, '%Y/%m/%d') <= date_format('" . $sJoinedT . "', '%Y/%m/%d')";
		if ($sWithdrawnF != "")
			$whereSQL[] = "date_format(Withdrawn, '%Y/%m/%d') >= date_format('" . $sWithdrawnF . "', '%Y/%m/%d')";
		if ($sWithdrawnT != "")
			$whereSQL[] = "date_format(Withdrawn, '%Y/%m/%d') <= date_format('" . $sWithdrawnT . "', '%Y/%m/%d')";
	}
	else {
		if ($sName != "")
			$whereSQL[] = "LastName || FirstName LIKE '%" . $myDB->escapeString($sName) . "%'";
		if ($sNameKana != "")
			$whereSQL[] = "LastNameKana || FirstNameKana LIKE '%" . $myDB->escapeString($sNameKana) . "%'";
		if ($sAgeF != "")
			$whereSQL[] = "date_part('year', age(Birthday)::INTERVAL) >= " . $sAgeF;
		if ($sAgeT != "")
			$whereSQL[] = "date_part('year', age(Birthday)::INTERVAL) <= " . $sAgeT;
		if ($sBirthday != "")
			$whereSQL[] = "Birthday::DATE = '" . $myDB->escapeString($sBirthday) . "'";
		if ($sJoinedF != "")
			$whereSQL[] = "Joined::DATE >= '" . $myDB->escapeString($sJoinedF) . "'";
		if ($sJoinedT != "")
			$whereSQL[] = "Joined::DATE <= '" . $myDB->escapeString($sJoinedT) . "'";
		if ($sWithdrawnF != "")
			$whereSQL[] = "Withdrawn::DATE >= '" . $myDB->escapeString($sWithdrawnF) . "'";
		if ($sWithdrawnT != "")
			$whereSQL[] = "Withdrawn::DATE <= '" . $myDB->escapeString($sWithdrawnT) . "'";
	}
	if ($sPrefecture > 0)
		$whereSQL[] = "Prefecture = " . $sPrefecture;
	if ($sAddress != "")
		$whereSQL[] = "(Address1 LIKE '%" . $myDB->escapeString($sAddress) . "%' OR Address2 LIKE '%" . $myDB->escapeString($sAddress) . "%' OR Address3 LIKE '%" . $myDB->escapeString($sAddress) . "%')";
	if ($sZipCode != "")
		$whereSQL[] = "ZipCode LIKE '%" .$myDB->escapeString($sZipCode) . "%'";
	if ($sAddress1 != "")
		$whereSQL[] = "Address1 LIKE '%" .$myDB->escapeString($sAddress1) . "%'";
	if ($sAddress2 != "")
		$whereSQL[] = "Address2 LIKE '%" .$myDB->escapeString($sAddress2) . "%'";
	if ($sAddress3 != "")
		$whereSQL[] = "Address3 LIKE '%" .$myDB->escapeString($sAddress3) . "%'";
	if ($sMukouFlg != "")
		$whereSQL[] = "MukouFlg = " . $sMukouFlg;

	if (count($sGender) > 0){
		for ($i = 0; $i < count($sGender); $i++){
			if ($sGender[$i] > 0){
				if ($i == 0) $genderSQL = "(";
				if ($i > 0) $genderSQL .= " OR ";
				$genderSQL .= "Gender = " . $sGender[$i] . "";
				if ($i == count($sGender) - 1) $genderSQL .= ")";
			}
		}
		$whereSQL[] = $genderSQL;
	}

	if (count($sPrefectures) > 0){
		for ($i = 0; $i < count($sPrefectures); $i++){
			if ($sPrefectures[$i] > 0){
				if ($i == 0) $genderSQL = "(";
				if ($i > 0) $genderSQL .= " OR ";
				$genderSQL .= "Prefecture = " . $sPrefectures[$i] . "";
				if ($i == count($sPrefectures) - 1) $genderSQL .= ")";
			}
		}
		$whereSQL[] = $genderSQL;
	}

	if (count($sCarrier) > 0){
		for ($i = 0; $i < count($sCarrier); $i++){
			if ($i == 0) $carrierSQL = "(";
			if ($i > 0) $carrierSQL .= " OR ";
			$carrierSQL .= "Carrier = " . $sCarrier[$i] . "";
			if ($i == count($sCarrier) - 1) $carrierSQL .= ")";
		}
		$whereSQL[] = $carrierSQL;
	}

	if ($sStatus != ""){
		switch($sStatus){
			case 1:
				break;
			case 2:
				$whereSQL[] = "Withdrawn IS NULL";
				break;
			case 3:
				$whereSQL[] = "Withdrawn IS NOT NULL";
				break;
		}
	}

	if ($sAccountCD != NULL){
		$whereSQL[] = "AccountCD = " . $sAccountCD;
	}

	for ($i = 0; $i < $QuestionLoop; $i++) {
		$Index = $i + 1;
		$sQuestion = ${'sQuestion' . $Index};

		if (($TypeOfQuestion[$i] == 1 || $TypeOfQuestion[$i] == 2) && $sQuestion != NULL) {
			$whereSQL[] = "Extra" . $ColumnIndex[$i] . " LIKE '%" . $sQuestion . "%'";
		}
		else if ($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4) {
			for ($j = 0; $j < count($sQuestion); $j++) {
				if ($j == 0) $partSQL = "(";
				if ($j > 0) $partSQL .= " or ";
				$partSQL .=  "Extra" . $ColumnIndex[$i] . " = " . $sQuestion[$j];
				if ($j == count($sQuestion) - 1) $partSQL .= ")";
			}
			if ($partSQL != NULL)
				$whereSQL[] = $partSQL;
		}
		else if ($TypeOfQuestion[$i] == 5) {
			for ($j = 0; $j < count($sQuestion); $j++) {
				if ($j == 0) $partSQL = "(";
				if ($j > 0) $partSQL .= " or ";
				$partSQL .=  "Extra" . $ColumnIndex[$i] . " LIKE '%|" . $sQuestion[$j] . "|%'";
				if ($j == count($sQuestion) - 1) $partSQL .= ")";
			}
			if ($partSQL != NULL)
				$whereSQL[] = $partSQL;
		}
	}

	if ($OpenLogFlg) {
		$myMailDelivery = new MailDelivery($myDB);

		if (!$myMailDelivery->executeSelect("DeliveryCD = " . $editDeliveryCD, NULL)) {
			$ErrorString = array();
			$ErrorString[] = "配信リストの抽出に失敗しました。";
			showAdminSorryPage($ErrorString);
		}

		if ($work2 == 2)
			$whereSQL[] = "(" . str_replace("where ", NULL, $myMailDelivery->ConditionSQL) . ")";
		$whereSQL[] = "Joined <= '" . $myMailDelivery->StartTime . "'";
	}

	########################################################
	# リストを取得
	########################################################
	// ページクラスのインスタンス生成
	$myListObject = new SPFWListObject($myDB);

	// Select SQL を設定
	$sql = "SELECT ";
	$sql .= "UserCD, ";
	$sql .= "ID, ";
	$sql .= "Passwd, ";
	$sql .= "LastName, ";
	$sql .= "FirstName, ";
	$sql .= "LastNameKana, ";
	$sql .= "FirstNameKana, ";
	$sql .= "Gender, ";
	$sql .= "EMail, ";
	$sql .= "Birthday, ";
	$sql .= "ZipCode, ";
	$sql .= "Prefecture, ";
	$sql .= "Address1, ";
	$sql .= "Address2, ";
	$sql .= "Address3, ";
	$sql .= "AddressKana, ";
	$sql .= "TEL, ";
	$sql .= "Extra1, ";
	$sql .= "Extra2, ";
	$sql .= "Extra3, ";
	$sql .= "Extra4, ";
	$sql .= "Extra5, ";
	$sql .= "Extra6, ";
	$sql .= "Extra7, ";
	$sql .= "Extra8, ";
	$sql .= "Extra9, ";
	$sql .= "Extra10, ";
	$sql .= "Points, ";
	$sql .= "Joined, ";
	$sql .= "Completed, ";
	$sql .= "Withdrawn, ";
	$sql .= "LastLogin, ";
	$sql .= "RegistKey, ";
	$sql .= "IdentifyKey, ";
	$sql .= "Carrier, ";
	$sql .= "UserAgent, ";
	$sql .= "UID, ";
	$sql .= "Created, ";
	$sql .= "Updated, ";
	$sql .= "Withdrawn IS NULL ";
	$myListObject->SelectSQL = $sql;

	// WHERE Condition を設定
	$sql = " FROM tUserM";
	$sql .= " WHERE UserCD > 0";
	for ($i = 0; $i < count($whereSQL); $i++){
		$sql .= " AND " . $whereSQL[$i];
	}
	if ($IfDemo)
		$sql .= " AND (Notes != 'NOSHOW' OR Notes IS NULL)";
	if ($IfASP) {
		if ($IfAdminSystem)
			$sql .= " AND ClientCD = " . $sClientCD;
		if ($IfAdminClient)
			$sql .= " AND ClientCD = " . $MyClientCD;
	}

	if ($OpenLogFlg && $work2 == 1) 
		$sql .= " AND UserCD IN (SELECT UserCD FROM tOpenLogF WHERE DeliveryCD = " . $editDeliveryCD . ")";
	else if ($OpenLogFlg && $work2 == 2)
		$sql .= " AND UserCD NOT IN (SELECT UserCD FROM tOpenLogF WHERE DeliveryCD = " . $editDeliveryCD . ")";

	$myListObject->Condition = $sql;

	// 表示順を指定
#	$myListObject->Order .= $SortByStr[ceil($SortBy / 2)][1];
#	$myListObject->Order .= ($SortBy % 2 != 0) ? "" : " DESC";
	
	// 件数
	$myListObject->Limit = $cRowsPerPage;
	
	// 検索実行
	if (!($myListObject->GetList($myPage))) {
		$ErrorString = array();
		$ErrorString[] = "会員リストの抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}

	// データ表示
	if ($myListObject->Rows != 0) {
		$UserListLoop = $myListObject->Rows;
		for ($i = 0; $i < $UserListLoop; $i++) {
			$UserCD[$i] = $myListObject->GetValue($i, 0);
			$ID[$i] = $myListObject->GetValue($i, 1);
			$Passwd[$i] = $myListObject->GetValue($i, 2);
			$LastName[$i] = $myListObject->GetValue($i, 3);
			$FirstName[$i] = $myListObject->GetValue($i, 4);
			$LastNameKana[$i] = $myListObject->GetValue($i, 5);
			$FirstNameKana[$i] = $myListObject->GetValue($i, 6);
			$Gender[$i] = $myListObject->GetValue($i, 7);
			$EMail[$i] = $myListObject->GetValue($i, 8);
			$Birthday[$i] = substr($myListObject->GetValue($i, 9), 0, 10);
			$ZipCode[$i] = $myListObject->GetValue($i, 10);
			$Prefecture[$i] = $myListObject->GetValue($i, 11);
			$Address1[$i] = $myListObject->GetValue($i, 12);
			$Address2[$i] = $myListObject->GetValue($i, 13);
			$Address3[$i] = $myListObject->GetValue($i, 14);
			$AddressKana[$i] = $myListObject->GetValue($i, 15);
			$TEL[$i] = $myListObject->GetValue($i, 16);
			$Extra1[$i] = $myListObject->GetValue($i, 17);
			$Extra2[$i] = $myListObject->GetValue($i, 18);
			$Extra3[$i] = $myListObject->GetValue($i, 19);
			$Extra4[$i] = $myListObject->GetValue($i, 20);
			$Extra5[$i] = $myListObject->GetValue($i, 21);
			$Extra6[$i] = $myListObject->GetValue($i, 22);
			$Extra7[$i] = $myListObject->GetValue($i, 23);
			$Extra8[$i] = $myListObject->GetValue($i, 24);
			$Extra9[$i] = $myListObject->GetValue($i, 25);
			$Extra10[$i] = $myListObject->GetValue($i, 26);
			$Points[$i] = $myListObject->GetValue($i, 27);
			$Joined[$i] = substr($myListObject->GetValue($i, 28), 0, 19);
			$Completed[$i] = substr($myListObject->GetValue($i, 29), 0, 19);
			$Withdrawn[$i] = substr($myListObject->GetValue($i, 30), 0, 19);
			$LastLogin[$i] = substr($myListObject->GetValue($i, 31), 0, 19);
			$RegistKey[$i] = $myListObject->GetValue($i, 32);
			$IdentifyKey[$i] = $myListObject->GetValue($i, 33);
			$Carrier[$i] = $myListObject->GetValue($i, 34);
			$UserAgent[$i] = $myListObject->GetValue($i, 35);
			$UID[$i] = $myListObject->GetValue($i, 36);
			$Created[$i] = substr($myListObject->GetValue($i, 37), 0, 19);
			$Updated[$i] = substr($myListObject->GetValue($i, 38), 0, 19);
			$Status[$i] = ($myListObject->GetValue($i, 39) == 't' || $myListObject->GetValue($i, 39) == 1) ? "入会" : "退会";

			$Gender[$i] = $GENDER[$Gender[$i]];
			$Prefecture[$i] = $PREFECTURE[$Prefecture[$i]];

			$IfPointArr[$i] = $IfPoint;

			for ($j = 0; $j < $QuestionLoop; $j++) {
				$No = $j + 1;
				$wColumnIndex = $ColumnIndex[$j];

				if ($TypeOfQuestion[$j] == 3 || $TypeOfQuestion[$j] == 4 || $TypeOfQuestion[$j] == 5) {
					$pQuestion = NULL;

					if ($TypeOfQuestion[$j] == 5 && !is_array(${'Extra' . $wColumnIndex}[$i]))
						$wQuestion = User::decodePluralValue(${'Extra' . $wColumnIndex}[$i]);
					else
						$wQuestion = ${'Extra' . $wColumnIndex}[$i];

					if (($TypeOfQuestion[$j] == 3 || $TypeOfQuestion[$j] == 4) && $wQuestion > 0)
						$pQuestion = ${'Choice' . $No . 'Name'}[array_search($wQuestion, ${'Choice' . $No . 'Value'})];
					else if ($TypeOfQuestion[$j] == 5) {
						for ($k = 0; $k < count($wQuestion); $k++) {
							$pQuestion .= ${'Choice' . $No . 'Name'}[array_search($wQuestion[$k], ${'Choice' . $No . 'Value'})];
							if ($k != count($wQuestion) - 1)
								$pQuestion .= '<br>';
						}
					}

					${'Extra' . $wColumnIndex}[$i] = $pQuestion;
				}
			}

			if ($OpenLogFlg) {
				$myOpenLog->initialize();
				if (!$myOpenLog->executeSelect("UserCD = " . $UserCD[$i] . " AND DeliveryCD = " . $editDeliveryCD, "Created LIMIT 1 OFFSET 0")) {
					$ErrorString = array();
					$ErrorString[] = "会員リストの抽出に失敗しました。";
					showAdminSorryPage($ErrorString);
				}
				$Opened[$i] = $myOpenLog->Created;

				$IfOpen = TRUE;
				$IfOpenArr[$i] = TRUE;
			}

			$IfReserveArr1[$i] = ($vReserve == 't') ? TRUE : FALSE;
			$IfReserveArr2[$i] = (!$IfReserveArr1[$i] && $IfReserveSystem) ? TRUE : FALSE;
		}
	}

	// ページ遷移関連
	$AllPages = $myListObject->Pages;
	$PreviousPage = $myPage - 1;
	$NextPage = $myPage + 1;
	$LastPage = $AllPages;
	if ($myPage > 1){
		$IfToTop = true;
		$IfToPre = true;
	}
	if ($myPage < $AllPages){
		$IfToNext = true;
		$IfToLast = true;
	}

	// 結果を判断
	if ($myListObject->Rows == 0){
		$IfNoResults = TRUE;
		$myPage = 0;
	}
	else
		$IfResults = TRUE;

	$AllRows = $myListObject->Count;

	unset($myListObject);

	########################################################
	# 表示関連
	########################################################

	$RowsPerPageLoop = count($ROWS_PER_PAGE);
	for ($i = 0; $i < $RowsPerPageLoop; $i++){
		$RowsPerPage[$i] = $ROWS_PER_PAGE[$i];
		$RowsPerPageSelected[$i] = ($RowsPerPage[$i] == $cRowsPerPage) ? "selected" : "";
	}

	$UserCD = SPFWParameter::adjustForPrint($UserCD);
	$ID = SPFWParameter::adjustForPrint($ID);
	$Passwd = SPFWParameter::adjustForPrint($Passwd);
	$Name = SPFWParameter::adjustForPrint($Name);
	$NameKana = SPFWParameter::adjustForPrint($NameKana);
	$EMail = SPFWParameter::adjustForPrint($EMail);
	$Status = SPFWParameter::adjustForPrint($Status);
	$Joined = SPFWParameter::adjustForPrint($Joined);

	$IfNew = (!$IfASP || ($IfASP && $IfAdminClient)) ? TRUE : FALSE;

	$ItemListLoop = count($wQuestionName);
	$QuestionName = $wQuestionName;

	if ($OpenLogFlg)
		$ColSpan = $ItemListLoop + 3;
	else if ($vReserve == 't')
		$ColSpan = $ItemListLoop + 3;
	else
		$ColSpan = $ItemListLoop + 2;

	if ($OpenLogFlg && $work2 == 2)
		$IfNotOpenLog = TRUE;

	########################################################
	# コンテンツ表示
	########################################################

	SPFWTemplate::dropValue("work");
	SPFWTemplate::dropValue("myPage");
	SPFWTemplate::dropValue("cRowsPerPage");
	SPFWTemplate::setValue("work", "");
	SPFWTemplate::setValue("SortBy", $SortBy);
	SPFWTemplate::setValue("editUserCD", $SortBy);
	SPFWTemplate::setValue("vFrom", "user_list");
	$HiddenValues = SPFWTemplate::getValuesToPass();

	$IfReserve = ($vReserve == 't' || $IfReserveSystem) ? TRUE : FALSE;

	$CNT_FILE = "admin/" . basename($_SERVER["SCRIPT_NAME"], ".php") . ".tpl";
	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

	########################################################
	# テンプレート加工
	########################################################

	$Body = $myTemplate->Msg;
	// 独自タグ部分をマッチングして取り出し
	preg_match_all("/(__)(.*?)(__)/", $Body, $regs);
	$Counter = count($regs[0]);
	for ($i = 0; $i < $Counter; $i++) {
		$Tag = $regs[2][$i];
		if ($Tag == 'Item'){
			// リスト部分(ItemListの中身)のエレメント確定
			$LoopBlock = NULL;
			$LoopString = $myTemplate->getStringBetween($Tag);
			break;
		}
	}

	for ($i = 0; $i < count($wQuestionID); $i++) {
		$ConvertTo .= $LoopString;
		$ConvertTo = str_replace("__QuestionID__", "__" . $wQuestionID[$i] . "__", $ConvertTo);
	}

	$Body = str_replace("__Item__" . $LoopString . "__Item__", $ConvertTo, $Body);
	$myTemplate->Msg = $Body;

	$myTemplate->outputTemplate();
	unset($myTemplate);

	unset($myLog);
	unset($myDB);
?>
