<?php
include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
include_once _CLS_DIR . "SPUSReservation.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSClient.cls";
// include_once _CLS_DIR . "SPUSHearingContent.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";


include_once "./include/common_489.php";


// データベースコネクト
$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


########################################################
#　多言語化対応(Multilingual support)
########################################################
$lang = get_lang();
$WORD = get_word($myDB); //このあとにset_word()使う。commonplace参照
if ($lang == 'ja') $Ifjp = TRUE; #datapikckerの制御

#利用ワード
$form1 = $WORD[$lang]['form.1']; #取付機器情報
$form2 = $WORD[$lang]['form.2']; #■標準機器
$form3 = $WORD[$lang]['form.3']; #取付機器
$form4 = $WORD[$lang]['form.4']; #型番
$form5 = $WORD[$lang]['form.5']; #仕様書
$form6 = $WORD[$lang]['form.6']; #取り扱い説明書
$form7 = $WORD[$lang]['form.7']; #■オプション機器
$form8 = $WORD[$lang]['form.8']; #取付機器操作方法動画
$form9 = $WORD[$lang]['form.9']; #
$form10 = $WORD[$lang]['form.10']; #
$form11 = $WORD[$lang]['form.11']; #
$form12 = $WORD[$lang]['form.12']; #
$form13 = $WORD[$lang]['form.13']; #
$form14 = $WORD[$lang]['form.14']; #
$form15 = $WORD[$lang]['form.15']; #
$form16 = $WORD[$lang]['form.16']; #つぎへ
$form17 = $WORD[$lang]['form.17']; #もどる
$form18 = $WORD[$lang]['form.18']; #
$form19 = $WORD[$lang]['form.19']; #
$form20 = $WORD[$lang]['form.20']; #
$form21 = $WORD[$lang]['form.21']; #
$form22 = $WORD[$lang]['form.22']; #
$form23 = $WORD[$lang]['form.23']; #
$form24 = $WORD[$lang]['form.24']; #
$form25 = $WORD[$lang]['form.25']; #
$form26 = $WORD[$lang]['form.26']; #
$form27 = $WORD[$lang]['form.27']; #
$form28 = $WORD[$lang]['form.28']; #
$form29 = $WORD[$lang]['form.29']; #
$form30 = $WORD[$lang]['form.30']; #

$top1 = $WORD[$lang]['top.1']; #号室
$logout1 = $WORD[$lang]['logout.1']; #ログアウト
$finish2 = $WORD[$lang]['finish.2']; #予約システムTOPへ
$finish3 = $WORD[$lang]['finish.3']; #予約TOP
#$form16 = $WORD[$lang]['form.16'];#つぎへ
#$form17 = $WORD[$lang]['form.17'];#もどる

$steppng = 'step1.png';
if ($lang <> 'ja') $steppng = 'step1_en.png';
$stepoppng = 'step1-op.png';
if ($lang <> 'ja') $stepoppng = 'step1-op_en.png';


########################################################
# クライアント取得
########################################################

$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');

// #### マンション名　をもってくる。
$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD AND MukouFlg = FALSE", "")) {
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
// if ($lang <> 'ja') $MansionName = $myBukken->BukkenNameEn; #★Multilingual

$WakuPattern = $myBukken->WakuPattern;
if($editBuildingCD){
	$wBuildingName = $myBuilding->BuildingName;
	$WakuPattern = $myBuilding->WakuPattern;
}
$today = date("Y-m-d");

unset($myBukken);

$IfNoop = true;

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

// 修正ボタン
$shuuseibotan = SPFWParameter::getValues('shuuseibotan');

	#######################################################
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
	$wKubun = SPFWParameter::getValues('wKubun');
	// 備考・特記事項
	$wFreeMemo = SPFWParameter::getValues('wFreeMemo');

	// ヒアリング


########################################################
# レジストキー付きの場合には、メールアドレス抽出
########################################################

$myUser = new User($myDB);


// 空メール連携 かつ レジストキーなしは不正
if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS);

if ($rKey != NULL) {
	// if (!$myUser->doAuthenticationByRegistKey($rKey))
	// 	trigger_error("doAuthentication Failed.", E_USER_ERROR);

    $Condition = "RegistKey = '" . $rKey . "' AND MukouFlg = FALSE";

    if (!$myUser->executeSelect($Condition, ""))
        trigger_error("Getting myUser Failed.", E_USER_ERROR);




    $wID = $myUser->ID; #UserCD
    #$sssUserCD = $myUser->UserCD; #UserCD

	$wLastName = $myUser->LastName;
	$wInitPasswd = $myUser->Passwd;
	$wPasswd = $myUser->Passwd2;
	$wTEL = $myUser->TEL;

	$mail = $myUser->EMail;
	$EmailVerified = $myUser->EmailVerified;
	if ($mail && $EmailVerified) { // メールアドレスが登録されていて、メール認証済みの場合
		$wEMail = $mail;
	}
	// メールが登録されていない場合、メール登録画面に
	else{
		$URL = "./mail_regist.php?rKey={$rKey}&userID={$wID}&editBukkenCD={$editBukkenCD}&editBuildingCD={$editBuildingCD}";
		header('Location: ' . $URL);
		exit;
	}


}

if ($shuuseibotan == 1) { // 確認画面から戻った場合
	$wLastName = SPFWParameter::getValues('wLastName');
	$wTEL = SPFWParameter::getValues('wTEL');
	$wEMail = SPFWParameter::getValues('wEMail');
	$wPasswd = SPFWParameter::getValues('wPasswd');
}

// ###20110731 add for  Display pre-reserve

$myReservation = new Reservation($myDB);

// if ($IfASP)
// 	$Condition = "BukkenCD = " . intval($editBukkenCD) . " AND ";
$Condition = "UserCD = '" . $myUser->UserCD . "' AND TimeFrom > NOW() AND Status = 1 AND MukouFlg = FALSE";

if (!$myReservation->executeSelect($Condition, "TimeFrom DESC"))
	trigger_error("Getting Reservation Failed.", E_USER_ERROR);

if ($myReservation->RecCnt == 0)
	$IfNoReservation = TRUE;

if ($myReservation->RecCnt == 1) {
	$IfReservation = TRUE;
	$ReservationLoop = $myReservation->RecCnt;

	for ($i = 0; $i < $myReservation->RecCnt; $i++) {
		#			$myReservation->getDataSet($i);

		$TimeFrom[$i] = $myReservation->TimeFrom;
		$WakuTime = getWakuTime($WakuPattern, $wTimeFrom);
		$STime[$i] = $WakuTime['STime'];
		$ETime[$i] = $WakuTime['ETime'];

		$ReservationDate[$i] = substr($TimeFrom[$i], 0, 10) . " " . $STime[$i] . "～" . $ETime[$i];
		$ReserveQuery[$i] = $QUERY . "&r=" . $myReservation->ReservationCD;
	}
}


// ####Add　END
// ########################################################
// # 基本設問の読み込み
// ########################################################


// ########################################################
// # 余計なパラメータの排除
// ########################################################


// 区分（所有/ 賃貸）
SPFWTemplate::dropValue("wKubun");
// 備考・特記事項
SPFWTemplate::dropValue("wFreeMemo");



// ########################################################
// # 動作制御
// ########################################################

$vPasswd = $myUser->Passwd;


if ($myUser->Completed != NULL)
	$IfNew = FALSE;
else
	$IfNew = TRUE;
$IfModify = !$IfNew;




########################################################
# 拡張設問の読み込み
########################################################

########################################################
# setting.properties定義のパラメータ関連
########################################################


###英語のGenderとExtra1の扱い

if ($wKubun == "1")
	$Kubun1Checked = "checked";
else if ($wKubun == "2")
	$Kubun2Checked = "checked";



//確認の消し忘れ対応20170621
$wLastName = str_replace("<font color=red >確認</font>", "", $wLastName);




########################################################
# コンテンツ表示
########################################################
if ($wLang == 1) {
	$CNT_FILE = "form_eng.tpl";
} else {
	$CNT_FILE = "form.tpl";
}
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);

// $myReload = new Reload();
// $myTemplate->setValue("_r_e_l_o_a_d_", $myReload->embedValue());
// if ($IfASP)
// 	$myTemplate->setValue("vC", $TargetClientCD);
if ($IfNew)
	$myTemplate->setValue("vN", 't');
else
	$myTemplate->setValue("vN", NULL);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();

$myDB->close();

unset($myTemplate);
unset($myLog);
