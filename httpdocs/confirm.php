<?php
include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";

include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
// include_once _CLS_DIR . "reload.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
// include_once _CLS_DIR . "SPUSHearingContent.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";

include_once "./include/common_489.php";


// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


$wLang = SPFWParameter::getValues('wLang');
########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照
if ($lang == 'ja') $Ifjp = TRUE; #datapikckerの制御

#利用ワード
$confirm1 = $WORD[$lang]['confirm.1']; #
$confirm2 = $WORD[$lang]['confirm.2']; #
$confirm3 = $WORD[$lang]['confirm.3']; #
$confirm4 = $WORD[$lang]['confirm.4']; #
$confirm5 = $WORD[$lang]['confirm.5']; #
$confirm6 = $WORD[$lang]['confirm.6']; #
$confirm7 = $WORD[$lang]['confirm.7']; #
$confirm8 = $WORD[$lang]['confirm.8']; #
$confirm9 = $WORD[$lang]['confirm.9']; #
$confirm10 = $WORD[$lang]['confirm.10']; #
$confirm11 = $WORD[$lang]['confirm.11']; #


$form11 = $WORD[$lang]['form.11']; #
$form12 = $WORD[$lang]['form.12']; #
$form13 = $WORD[$lang]['form.13']; #
$form15 = $WORD[$lang]['form.15']; #
$form22 = $WORD[$lang]['form.22']; #
$form23 = $WORD[$lang]['form.23']; #

$top1 = $WORD[$lang]['top.1']; #号室
$logout1 = $WORD[$lang]['logout.1']; #ログアウト
$finish2 = $WORD[$lang]['finish.2']; #予約システムTOPへ
$finish3 = $WORD[$lang]['finish.3']; #予約TOP
$form16 = $WORD[$lang]['form.16']; #つぎへ
$form17 = $WORD[$lang]['form.17']; #もどる

$steppng = 'step1.png';
if ($lang <> 'ja') $steppng = 'step1_en.png';
$stepoppng = 'step1-op.png';
if ($lang <> 'ja') $stepoppng = 'step1-op_en.png';




########################################################
# 物件情報取得
########################################################

$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');

// #### マンション名　をもってくる。
// include_once _CLS_DIR . "SPUSSetting.cls";
$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect(" BukkenCD = ".$editBukkenCD." AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
	unset($myTemplate);
	exit;
}

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "") || $myBuilding->RecCnt != 1) {
		$ErrorString = array();
		$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}
}

$MansionName = $myBukken->BukkenName;
$wBuildingName = $myBukken->BuildingName;
if($editBuildingCD){
	$wBuildingName = $myBuilding->BuildingName;
}

// 棟一覧
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "u.BuildingCD, ";
$sql .= "u.BuildingName ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tBuildingM u ";
$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$editBukkenCD."'";

$myListObject->Condition = $sql;
$myListObject->Order = "u.BuildingCD ASC";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting User List Failed.", E_USER_ERROR);

$BuildingCD = [];
$BuildingName = [];
$BuildingLoop = $myListObject->Rows;

for ($i = 0; $i < $BuildingLoop; $i++) {
	$BuildingCD[$i] = $myListObject->GetValue($i, 0);
	$BuildingName[$i] = $myListObject->GetValue($i, 1);
}
unset($myListObject);

function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}

// 棟名称が空の場合、例外処理
if(!$wBuildingName){
	if($editBuildingCD){
		$wBuildingName = '棟'.numberToCircled(2);
		for ($i = 0; $i < $BuildingLoop; $i++) {
			if($BuildingCD[$i] == $editBuildingCD){
				$wBuildingName = '棟'. numberToCircled($i+2);
			}
		}

	}else{
		if($BuildingLoop > 0){
			$wBuildingName = '棟'.numberToCircled(1);
		}
	}
}
if($BuildingLoop < 1){
	$wBuildingName = '';
}



$today = date("Y-m-d");

unset($myBukken);


$IfNoop = TRUE;



########################################################
# パラメータ取得
########################################################

for ($i = 1; $i < count($PRESET_QUESTION_ID); $i++) {
	$MyID = $PRESET_QUESTION_ID[$i];
	${'w' . $MyID} = SPFWParameter::getValues('w' . $MyID);
}
for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
	$Index = $i + 1;
	${'wExtra' . $Index} = SPFWParameter::getValues('wExtra' . $Index);
}


// 区分（所有/賃貸)
// $wKubun = SPFWParameter::getValues('wKubun');

// 備考・特記事項
$wPasswd = SPFWParameter::getValues('wPasswd');
$wFreeMemo = SPFWParameter::getValues('wFreeMemo');

// // ヒアリング

// 個人情報の取り扱いについて
$kojin = SPFWParameter::getValues('kojin');

if ($kojin <> 1) {
	$IfKojinError = TRUE;
	$IfError = TRUE;
} else {
	$Kojin = $confirm11; #個人情報の取り扱いに同意します
}
SPFWTemplate::dropValue('kojin');
SPFWTemplate::setValue('editBukkenCD',$editBukkenCD);
SPFWTemplate::setValue('editBuildingCD',$editBuildingCD);
// POSTデータをチェック
if (empty($_POST['wLastName'])) {
    // wLastNameが空の場合、form.phpにリダイレクト
    header('Location: form.php');
    exit; // リダイレクト後、このスクリプトの実行を停止
}



########################################################
# 正規アクセスチェック
########################################################

// 空メール連携 かつ レジストキーなしは不正
if (!$IfNoMail && $rKey == "")
	showSorryPage(_ILLEGAL_ACCESS);
if ($IfASP && $vC == NULL)
	showSorryPage(_ILLEGAL_ACCESS);

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

	if ($Obj['QuestionCD']) $QuestionLoop = count($Obj['QuestionCD']);
	$QuestionCD = $Obj['QuestionCD'];
	$Question = $Obj['Question'];
	$Required = $Obj['Required'];
	$TypeOfQuestion = $Obj['TypeOfQuestion'];
	$Choices = $Obj['Choices'];
	$TextFormat = $Obj['TextFormat'];
	$InternalUse = $Obj['InternalUse'];

	for ($i = 0; $i < $QuestionLoop; $i++) {
		$No = $i + 1;
		if ($InternalUse[$i] == 't')
			continue;

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

		$wExtra = ${'wExtra' . $No};

		${'Question' . $No} = $Question[$i];

		if ($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4 || $TypeOfQuestion[$i] == 5) {
			$regs = explode("\n", $Choices[$i]);
			${'Choice' . $No . 'Loop'} = count($regs);
			for ($j = 0; $j < count($regs); $j++) {
				${'Choice' . $No . 'Value'}[$j] = $j + 1;
				${'Choice' . $No . 'Name'}[$j] = $regs[$j];
			}

			if (($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4) && is_array(${'Choice' . $No . 'Value'}))
				${'pExtra' . $No} = $regs[array_search($wExtra, ${'Choice' . $No . 'Value'})];
			else if ($TypeOfQuestion[$i] == 5 && is_array(${'Choice' . $No . 'Value'})) {
				for ($j = 0; $j < count($wExtra); $j++) {
					${'pExtra' . $No} .= $regs[array_search($wExtra[$j], ${'Choice' . $No . 'Value'})];
					if ($j != count($wExtra) - 1)
						${'pExtra' . $No} .= '####BR####';
				}
			}
		} else
			${'pExtra' . $No} = $wExtra;

		if (${'pExtra' . $No} == NULL)
			${'IfNotQuestion' . $No} = TRUE;
	}
}

########################################################
# パラメータチェック/値加工
########################################################
$ErrorString = array();

// 基本設問必須チェック
for ($i = 1; $i < count($PRESET_QUESTION_ID); $i++) {
	$MyID = $PRESET_QUESTION_ID[$i];
	if (${'If' . $MyID . 'Required'})
		${'If' . $MyID . 'Empty'} = SPFWInputCheck::isEmpty(${'w' . $MyID}, $IfError);
}


//必須項目の制御20170602
if ($wLastName == "") {
	$IfError = $IfLastNameError = TRUE;
} else {
	$IfLastName = TRUE;
}
if ($wTEL == "") {
	$IfError = $IfTELEmpty = TRUE;
} else {
	$IfTEL = TRUE;
}
if ($wEMail == "") {
	$IfError = $IfEMailError = TRUE;
} else {
	$IfEMail = TRUE;
}



// 基本設問形式チェック
//	if ($wID != NULL)
//		$IfIDError = (!SPFWInputCheck::isAlphaNumeric($wID, &$IfError) || !SPFWInputCheck::checkLength($wID, 2, 12, &$IfError));
//	if ($wPasswd != NULL)
//		$IfPasswdError = (!SPFWInputCheck::isAlphaNumeric($wPasswd, &$IfError) || !SPFWInputCheck::checkLength($wPasswd, 2, 12, &$IfError));
//	if (!$IfNoMail && $wEMail != NULL)
//		$IfEMailError = !SPFWInputCheck::isRightEMail($wEMail, &$IfError);
//	if ($wLastName != NULL)
//		$IfLastNameError = !SPFWInputCheck::checkLength($wLastName, NULL, 16, &$IfError);

//	if ($wFirstName != NULL)
//		$IfFirstNameError = !SPFWInputCheck::checkLength($wFirstName, NULL, 16, &$IfError);
//	if ($wLastNameKana != NULL)
//		$IfLastNameKanaError = !SPFWInputCheck::checkLength($wLastNameKana, NULL, 20, &$IfError);
//	if ($wFirstNameKana != NULL)
//		$IfFirstNameKanaError = !SPFWInputCheck::checkLength($wFirstNameKana, NULL, 20, &$IfError);
//	if ($wZipCode != NULL)
//		$IfZipCodeError = (!SPFWInputCheck::isNumeric($wZipCode, &$IfError) || !SPFWInputCheck::checkLength($wZipCode, 7, 7, &$IfError));
if ($wTEL != NULL)
	$IfTELError = (!SPFWInputCheck::isNumeric($wTEL, $IfError) || !SPFWInputCheck::checkLength($wTEL, 9, 12, $IfError));


#		$IfTELError = (!SPFWInputCheck::isNumeric($wTEL, &$IfError) || !SPFWInputCheck::checkLength($wTEL, 9, 12, &$IfError));
//	if ($wBirthday != NULL){
//		$IfBirthdayError = !SPFWInputCheck::isNumeric($wBirthday, &$IfError);
//		if (!$IfBirthdayError) {
//			$mBirthday = SPFWDate::getFormattedTimestamp(SPFWDate::getStrippedTimestamp($wBirthday), "/");
//			$IfBirthdayError = (!SPFWInputCheck::checkLength($mBirthday, 10, 10, &$IfError) || !SPFWInputCheck::isRightDate($mBirthday, &$IfError));
//		}
//	}

// 拡張設問必須チェック
for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
	$Index = $i + 1;

	if ($i <> 10 and $wLang <> 1) { #①　個人情報保護の質問をEnglishは飛ばす

		if (${'IfExtra' . $Index . 'Required'})
			${'IfQuestionEmpty' . $Index} = SPFWInputCheck::isEmpty(${'wExtra' . $Index}, $IfError);
		#${'IfQuestionEmpty' . $Index} = SPFWInputCheck::isEmpty(${'wExtra' . $Index}, &$IfError);
		if ($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4 || $TypeOfQuestion[$i] == 5) {
			if (!SPFWInputCheck::isNumeric(${'wExtra' . $Index}, $IfError))
				#if (!SPFWInputCheck::isNumeric(${'wExtra' . $Index}, &$IfError))
				showSorryPage(_ILLEGAL_ACCESS);
		}
		if (${'IfDate' . $Index} && ${'wExtra' . $Index} != NULL) {
			${'IfQuestionError' . $Index} = !SPFWInputCheck::isNumeric(${'wExtra' . $Index}, $IfError);
			#${'IfQuestionError' . $Index} = !SPFWInputCheck::isNumeric(${'wExtra' . $Index}, &$IfError);
			if (!${'IfQuestionError' . $Index}) {
				${'pExtra' . $Index} = SPFWDate::getFormattedTimestamp(SPFWDate::getStrippedTimestamp(${'wExtra' . $Index}), "/");
				${'IfQuestionError' . $Index} = (!SPFWInputCheck::checkLength(${'pExtra' . $Index}, 10, 10, $IfError) || !SPFWInputCheck::isRightDate(${'pExtra' . $Index}, $IfError));
				#${'IfQuestionError' . $Index} = (!SPFWInputCheck::checkLength(${'pExtra' . $Index}, 10, 10, &$IfError) || !SPFWInputCheck::isRightDate(${'pExtra' . $Index}, &$IfError));
			}
		}
	} #①

}


// ID既利用チェック
if ($wID != NULL) {
	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$myUser = new User($myDB);
	if ($rKey != NULL) {
		if (!$myUser->doAuthenticationByRegistKey($rKey))
			trigger_error("doAuthentication Failed.", E_USER_ERROR);

		$UserCD = $myUser->UserCD;
	}

	#if ($myUser->isExistUserByID($wID, NULL, $UserCD))#ここがNG
	#	$IfError = $IfIDUsed = TRUE;
}


if ($IfNoMail && $wEMail != NULL) {
	// データベースコネクト
	if (!$myDB->Connection)
		$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	$myUser = new User($myDB);
	if ($rKey != NULL) {
		if (!$myUser->doAuthenticationByRegistKey($rKey))
			trigger_error("doAuthentication Failed.", E_USER_ERROR);

		$UserCD = $myUser->UserCD;
	}

	//		if ($myUser->isExistUserByEMail($wEMail, NULL, $UserCD))
	//			$IfError = $IfEMailUsed = TRUE;
}

$IfIDWarn = $IfIDEmpty || $IfIDError || $IfIDUsed;
$IfPasswdWarn = $IfPasswdEmpty || $IfPasswdError;
$IfLastNameWarn = $IfLastNameEmpty || $IfLastNameError;
$IfFirstNameWarn = $IfFirstNameEmpty || $IfFirstNameError;
$IfLastNameKanaWarn = $IfLastNameKanaEmpty || $IfLastNameKanaError;
$IfFirstNameKanaWarn = $IfFirstNameKanaEmpty || $IfFirstNameKanaError;
$IfGenderWarn = $IfGenderEmpty || $IfGenderError;
//	$IfEMailWarn = $IfEMailEmpty || $IfEMailError || $IfEMailUsed;
$IfBirthdayWarn = $IfBirthdayEmpty || $IfBirthdayError;
$IfZipCodeWarn = $IfZipCodeEmpty || $IfZipCodeError;
$IfPrefectureWarn = $IfPrefectureEmpty || $IfPrefectureError;
$IfAddress1Warn = $IfAddress1Empty || $IfAddress1Error;
$IfAddress2Warn = $IfAddress2Empty || $IfAddress2Error;
$IfAddress3Warn = $IfAddress3Empty || $IfAddress3Error;
$IfAddressKanaWarn = $IfAddressKanaEmpty || $IfAddressKanaError;

$IfTELWarn = $IfTELEmpty || $IfTELError;
for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
	$Index = $i + 1;
	${'IfQuestionWarn' . $Index} = ${'IfQuestionEmpty' . $Index} || ${'IfQuestionError' . $Index};
}

if ($IfError) {
	include_once("form.php" );
	exit;
}
$mGender = $GENDER[$wGender];
$wwExtra1 = $wExtra1;

$wExtra1 = $EXTRA1[$wExtra1];

$mPrefecture = $PREFECTURE[$wPrefecture];

$IfNotID = ($wID == NULL) ? TRUE : FALSE;
//	$IfNotPasswd = ($wPasswd == NULL) ? TRUE : FALSE;
$IfNotLastName = ($wLastName == NULL) ? TRUE : FALSE;
$IfNotFirstName = ($wFirstName == NULL) ? TRUE : FALSE;
$IfNotLastNameKana = ($wLastNameKana == NULL) ? TRUE : FALSE;
$IfNotFirstNameKana = ($wFirstNameKana == NULL) ? TRUE : FALSE;
$IfNotGender = ($wGender == NULL) ? TRUE : FALSE;
//	$IfNotEMail = ($wEMail == NULL) ? TRUE : FALSE;
$IfNotBirthday = ($wBirthday == NULL) ? TRUE : FALSE;
$IfNotZipCode = ($wZipCode == NULL) ? TRUE : FALSE;
$IfNotPrefecture = ($wPrefecture == NULL) ? TRUE : FALSE;
$IfNotAddress1 = ($wAddress1 == NULL) ? TRUE : FALSE;
$IfNotAddress2 = ($wAddress2 == NULL) ? TRUE : FALSE;
$IfNotAddress3 = ($wAddress3 == NULL) ? TRUE : FALSE;
$IfNotAddressKana = ($wAddressKana == NULL) ? TRUE : FALSE;
$IfNotTEL = ($wTEL == NULL) ? TRUE : FALSE;
for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
	$Index = $i + 1;
	${'IfNotExtra' . $Index} = (${'wExtra' . $Index} == NULL) ? TRUE : FALSE;
}
$IfNotPoints = ($wPoints == NULL) ? TRUE : FALSE;
$IfNotMailMagaFlg = ($wMailMagaFlg == NULL) ? TRUE : FALSE;

$wID = SPFWParameter::adjustForPrint($wID);
$wPasswd = SPFWParameter::adjustForPrint($wPasswd);
$wLastName = SPFWParameter::adjustForPrint($wLastName);
$wFirstName = SPFWParameter::adjustForPrint($wFirstName);
$wLastNameKana = SPFWParameter::adjustForPrint($wLastNameKana);
$wFirstNameKana = SPFWParameter::adjustForPrint($wFirstNameKana);
$wGender = SPFWParameter::adjustForPrint($wGender);
$wExtra1 = SPFWParameter::adjustForPrint($wExtra1);
$wEMail = SPFWParameter::adjustForPrint($wEMail);
$wBirthday = SPFWParameter::adjustForPrint($wBirthday);
$wZipCode = SPFWParameter::adjustForPrint($wZipCode);
$wPrefecture = SPFWParameter::adjustForPrint($wPrefecture);
$wAddress1 = SPFWParameter::adjustForPrint($wAddress1);
$wAddress2 = SPFWParameter::adjustForPrint($wAddress2);
$wAddress3 = SPFWParameter::adjustForPrint($wAddress3);
$wAddressKana = SPFWParameter::adjustForPrint($wAddressKana);
$wTEL = SPFWParameter::adjustForPrint($wTEL);

	for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
		$Index = $i + 1;
		${'wExtra' . $Index} = SPFWParameter::adjustForPrint(${'wExtra' . $Index});
		${'pExtra' . $Index} = SPFWParameter::adjustForPrint(${'pExtra' . $Index});
	}
$IfNew = ($vN == 't') ? TRUE : FALSE;
$IfModify = !$IfNew;


########################################################
# コンテンツ表示
########################################################
if ($wLang == 1) {
	$CNT_FILE = "confirm_eng.tpl";
} else {
	$CNT_FILE = "confirm.tpl";
}
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

// $myReload = new Reload();
// $myTemplate->setValue("_r_e_l_o_a_d_", $myReload->embedValue());
$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);