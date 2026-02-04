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
include_once _CLS_DIR . "SPUSReservation.cls";
// include_once _CLS_DIR . "reload.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
// include_once _CLS_DIR . "SPUSTaio.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";


include_once "./include/common_489.php";
#######################################################################
# データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);
######################################################################


$wLang = SPFWParameter::getValues('wLang');


########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照
if ($lang == 'ja') $Ifjp = TRUE; #datapikckerの制御

#利用ワード
$finish1 = $WORD[$lang]['finish.1']; #
$finish2 = $WORD[$lang]['finish.2']; #
$finish3 = $WORD[$lang]['finish.3']; #
$finish4 = $WORD[$lang]['finish.4']; #
$finish5 = $WORD[$lang]['finish.5']; #
$finish6 = $WORD[$lang]['finish.6']; #
$finish7 = $WORD[$lang]['finish.7']; #
$finish8 = $WORD[$lang]['finish.8']; #
$finish9 = $WORD[$lang]['finish.9']; #

$top1 = $WORD[$lang]['top.1']; #号室
$logout1 = $WORD[$lang]['logout.1']; #ログアウト
#$finish2 = $WORD[$lang]['finish.2'];#予約システムTOPへ
#$finish3 = $WORD[$lang]['finish.3'];#予約TOP
#$form16 = $WORD[$lang]['form.16'];#つぎへ
#$form17 = $WORD[$lang]['form.17'];#もどる

$steppng = 'step1.png';
if ($lang <> 'ja') $steppng = 'step1_en.png';
$stepoppng = 'step1-op.png';
if ($lang <> 'ja') $stepoppng = 'step1-op_en.png';


// ########################################################
// # クライアント取得
// ########################################################
// // //$wClientID = getClientID();  // URLからClientID取得 common.php
// // //$wClientCD = getClientCD($myDB);

// // $wClientID = getClientID2();  // URLからClientID取得 common.php
// // $wClientCD = getClientCD2($myDB);

// // $myClient = new Client($myDB);
// // if (!$myClient->executeSelect("ID = '" . $wClientID . "' AND MukouFlg = FALSE ", "") || $myClient->RecCnt != 1) {
// // 	trigger_error("Getting Client Failed.", E_USER_ERROR);
// // }
// // $wClientCD = $myClient->ClientCD;
// // unset($myClient);

// // $TargetClientCD = $wClientCD;



$btnflg = SPFWParameter::getValues('btnflg');
$CustomerEdit = SPFWParameter::getValues('CustomerEdit');
$work = SPFWParameter::getValues('work');
$wLang = SPFWParameter::getValues('wLang');
$wEMail = SPFWParameter::getValues('wEMail');
$wLastName = SPFWParameter::getValues('wLastName');
$wPasswd = SPFWParameter::getValues('wPasswd');
$wTEL = SPFWParameter::getValues('wTEL');
// $wKubun = SPFWParameter::getValues('wKubun');
$wFreeMemo = SPFWParameter::getValues('wFreeMemo');
$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');


#### マンション名　をもってくる。
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


$wWEBReceptType = $myBukken->WEBReceptType;

$MansionName = $myBukken->BukkenName;
$wBuildingName = $myBukken->BuildingName;
if($editBuildingCD){
	$wBuildingName = $myBuilding->BuildingName;
}

$today = date("Y-m-d");

unset($myBukken);


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
				$wBuildingName = '棟'.numberToCircled($i+2);
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

$IfNoOp = "TRUE";


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

#TargetClientCDは、雑排のこのシステムでは　BukkenCDに近い。物件ごとに質問を変えれる。今は無効に
// $TargetClientCD = SPFWParameter::getValues('vC');



########################################################
# 正規アクセスチェック
########################################################

// 空メール連携 かつ レジストキーなしは不正
if (!$IfNoMail && $rKey == "")
	showSorryPage(_ILLEGAL_ACCESS);
if ($IfASP && $vC == NULL)
	showSorryPage(_ILLEGAL_ACCESS);



// ########################################################
// # 基本設問の読み込み
// ########################################################

// #$QuestionObject = ($TargetClientCD) ? _OBJECT_DIR . 'standard_' . sprintf('%03d', $TargetClientCD) . '.obj' : _OBJECT_DIR . 'standard.obj';
// $QuestionObject = _OBJECT_DIR . 'standard.obj';

// if (file_exists($QuestionObject)) {
// 	$ObjText = SPFWTools::getFile($QuestionObject);
// 	$Obj = unserialize($ObjText);
// }

// for ($i = 1; $i < count($PRESET_QUESTION_ID); $i++) {
// 	${'If' . $PRESET_QUESTION_ID[$i]} = $Obj['If' . $PRESET_QUESTION_ID[$i] . 'Use'];
// 	${'If' . $PRESET_QUESTION_ID[$i] . 'Required'} = $Obj['If' . $PRESET_QUESTION_ID[$i] . 'Required'];
// 	${$PRESET_QUESTION_ID[$i] . 'Name'} = $Obj[$PRESET_QUESTION_ID[$i] . 'Name'];
// }

// ########################################################
// # 拡張設問の読み込み
// ########################################################

// #$QuestionClient = ($TargetClientCD > 0) ? $TargetClientCD : 1;
// $QuestionClient = 1;

// $QuestionObject = _OBJECT_DIR . 'question_' . sprintf("%03d", $QuestionClient) . '.obj';

// if (file_exists($QuestionObject)) {
// 	$ObjText = SPFWTools::getFile($QuestionObject);
// 	$Obj = unserialize($ObjText);

// 	if ($Obj['QuestionCD']) $QuestionLoop = count($Obj['QuestionCD']);
// 	$QuestionCD = $Obj['QuestionCD'];
// 	$Question = $Obj['Question'];
// 	$Required = $Obj['Required'];
// 	$TypeOfQuestion = $Obj['TypeOfQuestion'];
// 	$Choices = $Obj['Choices'];
// 	$ColumnIndex = $Obj['ColumnIndex'];
// 	$TextFormat = $Obj['TextFormat'];
// 	$InternalUse = $Obj['InternalUse'];

// 	for ($i = 0; $i < $QuestionLoop; $i++) {
// 		$No = $i + 1;
// 		if ($InternalUse[$i] == 't')
// 			continue;

// 		${'IfQuestion' . $No} = TRUE;
// 		${'IfExtra' . $No} = TRUE;
// 		${'IfExtra' . $No . 'Required'} = ($Required[$i]) ? TRUE : FALSE;
// 		if ($TypeOfQuestion[$i] == 1 && $TextFormat[$i] == 5)
// 			${'IfDate' . $No} = TRUE;
// 		else if ($TypeOfQuestion[$i] == 1)
// 			${'IfText' . $No} = TRUE;
// 		else if ($TypeOfQuestion[$i] == 2)
// 			${'IfTextarea' . $No} = TRUE;
// 		else if ($TypeOfQuestion[$i] == 3)
// 			${'IfRadio' . $No} = TRUE;
// 		else if ($TypeOfQuestion[$i] == 4)
// 			${'IfSelect' . $No} = TRUE;
// 		else if ($TypeOfQuestion[$i] == 5)
// 			${'IfCheckbox' . $No} = TRUE;

// 		$wExtra = ${'wExtra' . $No};

// 		${'Question' . $No} = $Question[$i];

// 		if ($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4 || $TypeOfQuestion[$i] == 5) {
// 			$regs = explode("\n", $Choices[$i]);
// 			${'Choice' . $No . 'Loop'} = count($regs);
// 			for ($j = 0; $j < count($regs); $j++) {
// 				${'Choice' . $No . 'Value'}[$j] = $j + 1;
// 				${'Choice' . $No . 'Name'}[$j] = $regs[$j];
// 			}

// 			if (($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4) && is_array(${'Choice' . $No . 'Value'}))
// 				${'pQuestion' . $No} = $regs[array_search($wExtra, ${'Choice' . $No . 'Value'})];
// 			else if ($TypeOfQuestion[$i] == 5 && is_array(${'Choice' . $No . 'Value'})) {
// 				for ($j = 0; $j < count($wExtra); $j++) {
// 					${'pQuestion' . $No} .= $regs[array_search($wExtra[$j], ${'Choice' . $No . 'Value'})];
// 					if ($j != count($wExtra) - 1)
// 						${'pQuestion' . $No} .= "\n";
// 				}
// 			}
// 		} else
// 			${'pQuestion' . $No} = $wExtra;

// 		if (${'pQuestion' . $No} == NULL)
// 			${'IfNotQuestion' . $No} = TRUE;
// 	}
// }

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

// 基本設問形式チェック
if ($wID != NULL)
	$IfIDError = (!SPFWInputCheck::isAlphaNumeric($wID, $IfError) || !SPFWInputCheck::checkLength($wID, 2, 12, $IfError));
if ($wPasswd != NULL)
	$IfPasswdError = (!SPFWInputCheck::isAlphaNumeric($wPasswd, $IfError) || !SPFWInputCheck::checkLength($wPasswd, 2, 12, $IfError));
if (!$IfNoMail && $wEMail != NULL)
	$IfEMailError = !SPFWInputCheck::isRightEMail($wEMail, $IfError);
if ($wLastName != NULL)
	$IfLastNameError = !SPFWInputCheck::checkLength($wLastName, NULL, 16, $IfError);
if ($wTEL != NULL)
	$IfTELError = (!SPFWInputCheck::isNumeric($wTEL, $IfError) || !SPFWInputCheck::checkLength($wTEL, 9, 12, $IfError));


// 拡張設問必須チェック
for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
	$Index = $i + 1;

	if ($i <> 10 and $wLang <> 1) { #①　個人情報保護の質問をEnglishは飛ばす

		if (${'IfExtra' . $Index . 'Required'})
			${'IfQuestionEmpty' . $Index} = SPFWInputCheck::isEmpty(${'wExtra' . $Index}, $IfError);
		if ($TypeOfQuestion[$i] == 3 || $TypeOfQuestion[$i] == 4 || $TypeOfQuestion[$i] == 5) {
			if (!SPFWInputCheck::isNumeric(${'wExtra' . $Index}, $IfError))
				showSorryPage(_ILLEGAL_ACCESS);
		}
		if (${'IfDate' . $Index} && ${'wExtra' . $Index} != NULL) {
			${'IfQuestionError' . $Index} = !SPFWInputCheck::isNumeric(${'wExtra' . $Index}, $IfError);
			if (!${'IfQuestionError' . $Index}) { #17
				${'wExtra' . $Index} = ${'pExtra' . $Index} = ${'pQuestion' . $Index} = SPFWDate::getFormattedTimestamp(SPFWDate::getStrippedTimestamp(${'wExtra' . $Index}), "/");
				${'IfQuestionError' . $Index} = (!SPFWInputCheck::checkLength(${'pExtra' . $Index}, 10, 10, $IfError) || !SPFWInputCheck::isRightDate(${'pExtra' . $Index}, $IfError));
			}
		}
	} #①

}

if ($IfError) {
	include_once("form.php");
	exit;
}

$mGender = $GENDER[$wGender];
$mPrefecture = $PREFECTURE[$wPrefecture];

for ($i = 1; $i < count($PRESET_QUESTION_ID); $i++) {
	$MyID = $PRESET_QUESTION_ID[$i];
	${'IfNot' . $MyID} = (${'w' . $MyID} == NULL) ? TRUE : FALSE;
}
// for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
// 	$Index = $i + 1;
// 	${'IfNotExtra' . $Index} = (${'wExtra' . $Index} == NULL) ? TRUE : FALSE;
// }

########################################################
# 登録処理
########################################################

$clsUser = new User($myDB);

if ($rKey != NULL) {
	if (!$clsUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($clsUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS);
	if ($IfASP && $clsUser->ClientCD != $TargetClientCD)
		showSorryPage(_ILLEGAL_ACCESS);

	#$EMail = $clsUser->EMail;
	$wwID = $clsUser->ID;
	$wUserCD = $clsUser->UserCD;

	// $Address3 = $clsUser->Address3;

	//LINEIDの取得
	$LineID = $clsUser->LineID;
}

if ($clsUser->Completed == NULL) {
	$Identifier = 'new';
	$IfNew = TRUE;
} else {
	$Identifier = 'edit';
	$IfNew = FALSE;
}
$IfModify = !$IfNew;


####20120711 新規/変更の表示フラグ制御
//空日程か変更・確定か判断
$myReservation = new Reservation($myDB);

if($editBuildingCD){
	if (!$myReservation->executeSelect("BukkenCD = $editBukkenCD and UserCD = '" . $clsUser->UserCD . "' AND BuildingCD = '" . $editBuildingCD . "' AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", ""))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	if ($myReservation->RecCnt == 0) {
		$IfNoReservation = TRUE;
	} else {
		$IfReservation = TRUE;
	}
}else{
	if (!$myReservation->executeSelect("BukkenCD = $editBukkenCD and UserCD = '" . $clsUser->UserCD . "' AND BuildingCD IS NULL AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE", ""))
		trigger_error("Getting Reservation Failed.", E_USER_ERROR);
	if ($myReservation->RecCnt == 0) {
		$IfNoReservation = TRUE;
	} else {
		$IfReservation = TRUE;
	}

}

####新規/変更の表示フラグ制御おわり


if ($work == 1) { //finishで更新などを押したときに再度アップデートされないように制御する



	########################################################
	# 入会動作
	########################################################
	###20120520
	$vPasswd = $clsUser->Passwd;

	for ($i = 1; $i < count($PRESET_QUESTION_ID); $i++) {
		$MyID = $PRESET_QUESTION_ID[$i];
		if (${'If' . $MyID}) {
			$clsUser->{$MyID} = ${'w' . $MyID};
		}
	}
// 	$clsUser->Actors = User::encodePluralValue($wActors);

// 	for ($i = 0; $i < _MAX_QUESTIONS; $i++) {
// 		$Index = $i + 1;
// 		if (${'IfExtra' . $Index}) {

// 			if (is_array(${'wExtra' . $Index}))
// 				$clsUser->{'Extra' . $ColumnIndex[$i]} = User::encodePluralValue(${'wExtra' . $Index});
// 			else
// 				$clsUser->{'Extra' . $ColumnIndex[$i]} = ${'wExtra' . $Index};
// 		}
// 	}

	$clsUser->EMail = $wEMail;

	$clsUser->LastName = $wLastName;
	$clsUser->Passwd2 = $wPasswd;
	$clsUser->TEL = $wTEL;
// 	#$clsUser->Kubun = $wKubun;

	if ($clsUser->Completed == NULL) {
		$clsUser->Completed = 'NOW()';
		$clsUser->LastLogin = 'NOW()';
		if ($IfNoMail) {
			$rKey = $clsUser->RegistKey = $clsUser->getNewKey();
			if ($IfIdentify)
				$clsUser->IdentifyKey = $clsUser->getNewKey('IdentifyKey');
		}
	}

	$clsUser->Carrier = $MyCarrier;
	$clsUser->UserAgent = $HTTP_USER_AGENT;


	if ($rKey == "")
		$clsUser->Creator = 0;
	$clsUser->Updater = $wwID;
	// $clsUser->ReplyFlg = 1;


	if (!$clsUser->executeUpdate("", $TargetClientCD))
		trigger_error("executeUpdate(clsUser) Failed.", E_USER_ERROR);

	$work = "";

	$rKey = $clsUser->RegistKey;
	if($editBuildingCD)
		$QUERY = "?rKey=" . $rKey . "&editBukkenCD=" . $editBukkenCD. "&editBuildingCD=" . $editBuildingCD;
	else
		$QUERY = "?rKey=" . $rKey . "&editBukkenCD=" . $editBukkenCD;

// 	// #対応記録
// 	// $myTaio = new Taio($myDB);

// 	// $myTaio->TaioCD = "-1";
// 	// $myTaio->ClientCD = $TargetClientCD;
// 	// $myTaio->UserCD = $wUserCD;
// 	// if ($wFreeMemo != "") { //備考・特記事項ある場合
// 	// 	$myTaio->Category = "|3|7|"; #WEB受付+備考欄あり
// 	// 	if ($HearingFlg == "1") { //ヒアリング内容がある場合
// 	// 		$myTaio->TaioNotes = "[お客様WEB登録 備考記述あり]名前:" . $wLastName . "TEL:" . $wTEL . "ヒアリング:" . $SaveDate . "備考・特記事項:" . $wFreeMemo;
// 	// 	} else {
// 	// 		$myTaio->TaioNotes = "[お客様WEB登録 備考記述あり]名前:" . $wLastName . "TEL:" . $wTEL . "備考・特記事項:" . $wFreeMemo;
// 	// 	}
// 	// } else { //備考・特記事項ない場合
// 	// 	$myTaio->Category = "7"; #WEB受付
// 	// 	if ($HearingFlg == "1") { //ヒアリング内容がある場合
// 	// 		$myTaio->TaioNotes = "[お客様WEB登録]名前:" . $wLastName . "TEL:" . $wTEL . "ヒアリング:" . $SaveDate;
// 	// 	} else {
// 	// 		$myTaio->TaioNotes = "[お客様WEB登録]名前:" . $wLastName . "TEL:" . $wTEL;
// 	// 	}
// 	// }
// 	// #$myTaio->HearingNo = $tHearingNo + 1 ; #ヒアリングNoをカウントアップしない
// 	// $myTaio->Creator = $wUserCD;
// 	// $myTaio->Updater = $wUserCD;

// 	// if (!$myTaio->executeUpdate()) {
// 	// 	trigger_error("Updating Taio Failed.", E_USER_ERROR);
// 	// }
// 	// unset($myTaio);
// 	// #ヒアリングNoをカウントアップしない

// 	#対応記録End

// 	#LINE通知　LINEIDがある場合のみ通知
// 	if ($LineID) {
// 		$userId = $LineID;
// 		$LineMessage = "【お客様情報の登録完了のお知らせ】\n";
// 		$LineMessage .= "マンション名：" . $MansionName . "\n";
// 		$LineMessage .= "部屋番号：" . $wwID . "\n";
// 		$LineMessage .= "\n※日程の予約は完了してません。";

// 		$message = json_encode([
// 			'type' => 'text',
// 			'text' => $LineMessage
// 		]);

// 		$url = 'https://www.489501.jp/sk/line_message_sender.php';
// 		$data = [
// 			'userId' => $userId,
// 			'message' => $message
// 		];

// 		$ch = curl_init();
// 		curl_setopt($ch, CURLOPT_URL, $url);
// 		curl_setopt($ch, CURLOPT_POST, true);
// 		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 		$response = curl_exec($ch);

// 		if (curl_errno($ch)) {
// 			echo 'Curl error: ' . curl_error($ch);
// 		}
// 		curl_close($ch);
// 	}
}#work == 1のEnd





	$IfNittei = TRUE;
	$IfOp = "";

if ($CustomerEdit == "1") {
	$IfCustomerEdit = true;
	$IfNOCustomerEdit = false;
} else {
	$IfCustomerEdit = false;
	$IfNOCustomerEdit = true;
}


########################################################
# 多棟の場合 ユーザの属する棟の専有部期間に変換
########################################################
// if ($TatoFlg == "1" and $Address3 != NULL) {

// 	$ToData = getToData($myDB, $wClientCD);

// 	for ($x = 0; $x < count($ToData); $x++) {
// 		if ($Address3 == $ToData['ToName'][$x]) {
// 			$ExtendedYoyakuEndDate  = $ToData['ExtendedYoyakuEndDate'][$x]; //第2受付締切日

// 			if (!empty($ExtendedYoyakuEndDate)) {
// 				$wWEBReceptType = 2; //第2受付期間では確定で取る
// 			}
// 			break;
// 		}
// 	}
// }

########################################################
# 表示関連
########################################################

if ($UserFinishLinkTo == NULL)
	$UserFinishLinkTo = 'top.php' . $QUERY;
if ($UserFinishLinkMessage == NULL)
	$UserFinishLinkMessage = 'トップへ';

########################################################
# コンテンツ表示
########################################################

if ($wLang == 1) {
	$CNT_FILE = "finish_eng.tpl";
} else {

	$CNT_FILE = "finish.tpl";
	if ($wWEBReceptType == 2) {
		$CNT_FILE = "finish_kakutei.tpl";
	}

}

$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

$HiddenValues = $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

$myDB->close();

unset($myTemplate);
unset($myLog);
