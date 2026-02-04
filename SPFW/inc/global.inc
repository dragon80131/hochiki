<?php
include_once _CLS_DIR . 'SPFWError.cls';
include_once _CLS_DIR . 'SPFWTools.cls';
include_once _CLS_DIR . 'SPFWTemplate.cls';
// 漢字制御用
$IME_ON = " STYLE=\"ime-mode:enabled\"";
$IME_OFF = " STYLE=\"ime-mode:disabled\"";

// 設定呼び出し
if ($GLOBALS['_GET']['c'] != NULL)
	$MyClientCD = $GLOBALS['_GET']['c'];
else if ($GLOBALS['_POST']['c'] != NULL)
	$MyClientCD = $GLOBALS['_POST']['c'];
else if ($TargetClientCD > 0)
	$MyClientCD = $TargetClientCD;

if ($MyClientCD != NULL)
	$Filename = _OBJECT_DIR . 'user_set_' . sprintf("%03d", $MyClientCD) . '.obj';
else
	$Filename = _OBJECT_DIR . 'user_set.obj';

if (file_exists($Filename)) {
	$ObjText = SPFWTools::getFile($Filename);
	$Obj = unserialize($ObjText);
	unset ($ObjText);
}

$LoginFlg = ($Obj['LoginFlg'] == 't') ? TRUE : FALSE;
$EasyLoginFlg = ($Obj['EasyLoginFlg'] == 't') ? TRUE : FALSE;
// $Timeout = $Obj['Timeout'];
$Timeout = 360;
$KeyChangeFlg = ($Obj['KeyChangeFlg'] == 't') ? TRUE : FALSE;
$ChangeSecurityFlg = ($Obj['ChangeSecurityFlg'] == 't') ? TRUE : FALSE;
$UserFinishLinkTo = $Obj['LinkTo'];
$UserFinishLinkMessage = $Obj['LinkMessage'];

$IfUID = ($EasyLoginFlg) ? TRUE : FALSE;

unset ($Obj);
unset ($ObjText);

$Filename = ($IfASP) ? _OBJECT_DIR . 'ex_inquiry_set_' . sprintf('%03d', $TargetClientCD) . '.obj' : _OBJECT_DIR . 'ex_inquiry_set.obj';

if (file_exists($Filename)) {
	$ObjText = SPFWTools::getFile($Filename);
	$Obj = unserialize($ObjText);
	unset ($ObjText);
}

$InquiryStatusChange = $Obj['StatusChange'];

$WORD = array('InstituteTitle', 'ReservationName', 'Staff', 'Designation', 'Menu');
$WORDNAME = array('施設名', '予約呼称', 'スタッフ呼称', 'スタッフ指名呼称', 'メニュー呼称');
$DEFAULTWORD = array('　', '予約', 'スタイリスト', '指名', 'メニュー');

$Filename = ($IfASP) ? _OBJECT_DIR . 'reserve_set_' . sprintf('%03d', $TempClientCD) . '.obj' : _OBJECT_DIR . 'reserve_set.obj';
if (file_exists($Filename)) {
	$ObjText = SPFWTools::getFile($Filename);
	$Obj = unserialize($ObjText);
	unset ($ObjText);
}
$wWord = $Obj['word'];
$ReserveTime = $Obj['ReserveTime'];
for ($i = 0; $i < count($WORD); $i++) {
	if ($wWord[$i] != NULL)
		${$WORD[$i]} = $wWord[$i];
	else
		${$WORD[$i]} = $DEFAULTWORD[$i];
}

$DEFAULTDATE = date('Y/m/d', mktime(0, 0, 0, 1, 1, date('Y') - 25));

function getFile($filename){
	if (!$fp = fopen($filename, "r")){
		$this->Err = $filename . "の読込に失敗しました";
		return false;
	}

	$msg = "";		// コンテンツ格納変数を初期化
	while (!feof($fp)){
		$msg .= fgets($fp, 10240);
	}

	fclose ($fp);

	return $msg;
}

function putFile($filename, $str){
	if (!$fp = fopen($filename, "w")){
		$Err = $filename . "のオープンに失敗しました";
		return false;
	}

	fwrite($fp, $str);
	fclose ($fp);

	return true;
}

function getInfo() {
	$Filename = _OBJECT_DIR . 'env_set.obj';

	if (file_exists($Filename)) {
		$ObjText = getFile($Filename);
		$Obj = unserialize($ObjText);
		unset ($ObjText);
	}
	else
		$NewFlg = TRUE;

	$SF = $GLOBALS['_SERVER']['SCRIPT_FILENAME'];
	$DR = _DOCUMENT_ROOT;
	$LR = _LIBRARY_ROOT;
	$IP = $GLOBALS['_SERVER']['SERVER_ADDR'];
	$DM = $GLOBALS['_SERVER']['SERVER_NAME'];
	$HO = $GLOBALS['_ENV']['HOSTNAME'];
	$MU = _MAIN_URL;
	$SR = 'BpGIBXM3PaLNvAup00276';

	if (!$NewFlg && ($DR != $Obj['GDR'] || $LR != $Obj['GLR'] || $IP != $Obj['GIP']))
		$ContactFlg = TRUE;

	$Msg = $SF . "\n" . $DR . "\n" . $LR . "\n" . $IP . "\n" . $DM . "\n" . $HO . "\n" . $MU . "\n";

	if ($NewFlg || $ContactFlg) {
		// mail('info@nespe.jp', 'LICENSE ALERT[' . $SR . ']G', $Msg);
		$Obj['GDR'] = $DR;
		$Obj['GLR'] = $LR;
		$Obj['GIP'] = $IP;
		$Obj['GDM'] = $DM;
		$Obj['GHO'] = $HO;
		$Obj['GMU'] = $MU;
		$ObjText = serialize($Obj);
		putFile($Filename, $ObjText);
	}
}

if (!$GLOBALS['isModuleMode'])
	getInfo();

if ($GLOBALS['_GET']['rKey'] != NULL)
	$rKey = $GLOBALS['_GET']['rKey'];
else if ($GLOBALS['_POST']['rKey'] != NULL)
	$rKey = $GLOBALS['_POST']['rKey'];

if ($GLOBALS['_GET']['aKey'] != NULL)
	$aKey = $GLOBALS['_GET']['aKey'];
else if ($GLOBALS['_POST']['aKey'] != NULL)
	$aKey = $GLOBALS['_POST']['aKey'];

$editBukkenCD = NULL;
if ($GLOBALS['_GET']['editBukkenCD'] != NULL)
	$editBukkenCD = $GLOBALS['_GET']['editBukkenCD'];
else if ($GLOBALS['_POST']['editBukkenCD'] != NULL)
	$editBukkenCD = $GLOBALS['_POST']['editBukkenCD'];

$editBuildingCD = NULL;
if ($GLOBALS['_GET']['editBuildingCD'] != NULL)
	$editBuildingCD = $GLOBALS['_GET']['editBuildingCD'];
else if ($GLOBALS['_POST']['editBuildingCD'] != NULL)
	$editBuildingCD = $GLOBALS['_POST']['editBuildingCD'];

if ($rKey != NULL)
	$TempQUERY[] = "rKey=" . $rKey;
if ($myClientCD > 0)
	$TempQUERY[] = "c=" . $MyClientCD;
if ($aKey != NULL)
	$TempQUERY[] = "aKey=" . $aKey;
if ($editBukkenCD != NULL)
	$TempQUERY[] = "editBukkenCD=" . $editBukkenCD;
if ($editBuildingCD != NULL)
	$TempQUERY[] = "editBuildingCD=" . $editBuildingCD;

$TempQUERY[] = "ct=" . rand(0, 100);

$QUERY = NULL;
for ($i = 0; $i < count($TempQUERY); $i++) {
	if ($i == 0)
		$QUERY .= "?";
	else
		$QUERY .= "&";
	$QUERY .= $TempQUERY[$i];
}

#
# AJAX日付関係
#
$MyFilename = basename($_SERVER["SCRIPT_NAME"]);
if (strpos($MyFilename, 'user_list') === 0) {
	$AjaxDatePost = array('sBirthday', 'sJoinedF', 'sJoinedT', 'sWithdrawnF', 'sWithdrawnT', 'wBirthday', 'wJoined', 'wWithdrawn');
}
else if (strpos($MyFilename, 'user_detail') === 0) {
	$AjaxDateShow = array('wBirthday', 'wJoined', 'wWithdrawn');
}
else if (strpos($MyFilename, 'delivermail_list') === 0) {
	$AjaxDatePost = array('sBirthday', 'sJoinedF', 'sJoinedT', 'sWithdrawnF', 'sWithdrawnT', 'wTimeToStart');
}
else if (strpos($MyFilename, 'delivermail_detail') === 0) {
	$AjaxDateShow = array('sBirthday', 'sJoinedF', 'sJoinedT', 'sWithdrawnF', 'sWithdrawnT', 'wTimeToStart');
}
else if (strpos($MyFilename, 'delivermail_send') === 0) {
	$AjaxDatePost = array('sBirthday', 'sJoinedF', 'sJoinedT', 'sWithdrawnF', 'sWithdrawnT', 'wTimeToStart');
}
else if (strpos($MyFilename, 'coupon_list') === 0) {
	$AjaxDatePost = array('wAvailableFrom', 'wAvailableTo');
}
else if (strpos($MyFilename, 'coupon_detail') === 0) {
	$AjaxDateShow = array('wAvailableFrom', 'wAvailableTo');
}
else if (strpos($MyFilename, 'couponlog_list') === 0) {
	$AjaxDatePost = array('sCreatedFrom', 'sCreatedTo');
	$AjaxDateShow = array('sCreatedFrom', 'sCreatedTo');
}
else if (strpos($MyFilename, 'url_log_list') === 0) {
	$AjaxDatePost = array('sCreatedFrom', 'sCreatedTo');
	$AjaxDateShow = array('sCreatedFrom', 'sCreatedTo');
}
else if (strpos($MyFilename, 'enquete_list') === 0) {
	$AjaxDatePost = array('wOpen', 'wClose');
}
else if (strpos($MyFilename, 'enquete_detail') === 0) {
	$AjaxDateShow = array('wOpen', 'wClose');
}
else if (strpos($MyFilename, 'calendar_list') === 0) {
	$AjaxDatePost = array('wTarget');
}
else if (strpos($MyFilename, 'calendar_detail') === 0) {
	$AjaxDateShow = array('wTarget');
}
else if (strpos($MyFilename, 'contents_list') === 0) {
	$AjaxDatePost = array('wOpenFrom');
}
else if (strpos($MyFilename, 'contents_detail') === 0) {
	$AjaxDateShow = array('wOpenFrom');
}
if (strpos($MyFilename, 'ex_inquiry_list') === 0) {
	$AjaxDatePost = array('sBirthday', 'sJoinedF', 'sJoinedT');
}

#
# メール配信時差し込みタグ
#
$BODY_REPLACE_TAG[] = '';

$BODY_REPLACE_TAG[] = '%%EMAIL%%';

$BODY_REPLACE_TAG[] = '';
$BODY_REPLACE_TAG[] = '%%DOMAIN%%form.php?rKey=%%KEY%%';
$BODY_REPLACE_TAG[] = '%%DOMAIN%%open.php?%%OPENKEY%%';
$BODY_REPLACE_TAG[] = '%%DATE%%';
$BODY_REPLACE_TAG[] = '%%DATETIME%%';
$BODY_REPLACE_TAG[] = '%%KEY%%';
$BODY_REPLACE_TAG[] = '%%IDENTIFIER%%';
$BODY_REPLACE_TAG[] = '%%NUMBER%%';
$SUBJECT_REPLACE_TAG[] = '';

$SUBJECT_REPLACE_TAG[] = '%%EMAIL%%';

$SUBJECT_REPLACE_TAG[] = '';
$SUBJECT_REPLACE_TAG[] = '%%DATE%%';
$SUBJECT_REPLACE_TAG[] = '%%DATETIME%%';
$SUBJECT_REPLACE_TAG[] = '%%NUMBER%%';

#
# メール配信時差し込みタグ名
#
$BODY_REPLACE_TAGNAME[] = '=====登録データ';

$BODY_REPLACE_TAGNAME[] = 'メールアドレス';

$BODY_REPLACE_TAGNAME[] = '=====システムデータ';
$BODY_REPLACE_TAGNAME[] = '登録用URL';
$BODY_REPLACE_TAGNAME[] = '開封確認用URL';
$BODY_REPLACE_TAGNAME[] = '日付(YYYY/MM/DD形式)';
$BODY_REPLACE_TAGNAME[] = '日時(YYYY/MM/DD hh:mm:ss形式)';
$BODY_REPLACE_TAGNAME[] = '会員登録キー';
$BODY_REPLACE_TAGNAME[] = '会員識別キー';
$BODY_REPLACE_TAGNAME[] = '会員番号';
$SUBJECT_REPLACE_TAGNAME[] = '=====登録データ';

$SUBJECT_REPLACE_TAGNAME[] = 'メールアドレス';

$SUBJECT_REPLACE_TAGNAME[] = '=====システムデータ';
$SUBJECT_REPLACE_TAGNAME[] = '日付(YYYY/MM/DD形式)';
$SUBJECT_REPLACE_TAGNAME[] = '日時(YYYY/MM/DD hh:mm:ss形式)';
$SUBJECT_REPLACE_TAGNAME[] = '会員番号';

#
# お問い合わせ返信時差し込みタグ
#
$INQUIRY_BODY_REPLACE_TAG[] = '';
$INQUIRY_BODY_REPLACE_TAG[] = '%%LASTNAME%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%FIRSTNAME%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%LASTNAMEKANA%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%FIRSTNAMEKANA%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%GENDER%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%EMAIL%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%BIRTHDAY%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%ZIPCODE%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%PREFECTURE%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%ADDRESS1%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%ADDRESS2%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%ADDRESS3%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%TEL%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%SUBJECT%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%MESSAGE%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%REFERENCE%%';
$INQUIRY_BODY_REPLACE_TAG[] = '';
$INQUIRY_BODY_REPLACE_TAG[] = '%%MailReplyURL%%';
$INQUIRY_BODY_REPLACE_TAG[] = '%%CREATED%%';

#
# お問い合わせ返信時差し込みタグ
#
$INQUIRY_BODY_REPLACE_TAGNAME[] = '=====登録データ';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '姓';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '名';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '姓かな';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '名かな';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '性別';
$INQUIRY_BODY_REPLACE_TAGNAME[] = 'メールアドレス';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '誕生日';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '郵便番号';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '都道府県';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '市区町村';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '番地詳細';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '建物名';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '電話番号';
$INQUIRY_BODY_REPLACE_TAGNAME[] = 'お問い合わせタイトル';
$INQUIRY_BODY_REPLACE_TAGNAME[] = 'お問い合わせ内容';
$INQUIRY_BODY_REPLACE_TAGNAME[] = 'お問い合わせ内容(引用)';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '=====システムデータ';
$INQUIRY_BODY_REPLACE_TAGNAME[] = '返信用ＵＲＬ';
$INQUIRY_BODY_REPLACE_TAGNAME[] = 'お問い合わせ日時';

$DEVELOP_MODE = TRUE;

if ($IfASP && $IfASPSite && !$isModuleMode && $MySiteID == NULL) {
	showSorryPage(_ILLEGAL_ACCESS);
	exit;
}



?>
