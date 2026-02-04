<?php

include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";
include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
include_once _CLS_DIR . "SPUSKojiNittei.cls";

include_once "./include/common.php";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
########################################################
# 認証動作
########################################################
if ($rKey) {

	$myUser = new User($myDB);
	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS);

	$UserCD 		= $myUser->UserCD;
	$ClientCD 		= $myUser->ClientCD; #幹事企業CD
	$EigyosyoCD 		= $myUser->EigyosyoCD; #幹事企業支店・営業所CD
	$UserKbn 		= $myUser->UserKbn; #1:幹事企業一般 2:管理者 3:協力業者CD
	$GyosyaCD	= $myUser->GyosyaCD; #協力業者CD
	unset($myUser);
}
########################################################
# 物件情報取得（取得）
########################################################
$myBukken = new Bukken($myDB);

if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}

$wKenmeiNo 				= $myBukken->KenmeiNo;
$wBukkenName 			= $myBukken->BukkenName;
$wBukkenName_Hurigana	= $myBukken->BukkenName_Hurigana;
$wAddress 				= $myBukken->Address;
$wBukkenMemo 			= $myBukken->BukkenMemo;
$WFormatType 			= $myBukken->FormatType;
$ClientCD 			    = $myBukken->ClientCD;
$LatestIraiKojiKind 	= $myBukken->LatestIraiKojiKind;
$FirstKojiDate 	= $myBukken->FirstKojiDate;
$LastKojiDate 	= $myBukken->LastKojiDate;
$Time 	= $myBukken->KojiTime;
$Kosu 			= $myBukken->Kosu;
$KikiTenkenMonth = $myBukken->KikiTenkenMonth;
$SougouTenkenMonth = $myBukken->SougouTenkenMonth;
$KikiAMKojiTime = $myBukken->KikiAMKojiTime;
$KikiPMKojiTime = $myBukken->KikiPMKojiTime;
$SougouAMKojiTime = $myBukken->SougouAMKojiTime;
$SougouPMKojiTime = $myBukken->SougouPMKojiTime;
$KikiTenkenKikan = $myBukken->KikiTenkenKikan;
$SougouTenkenKikan = $myBukken->SougouTenkenKikan;

//防災情報
$BousaiStartDate = $myBukken->BousaiStartDate;
$BousaiEndDate = $myBukken->BousaiEndDate;
$BousaiTenkenMonth = $myBukken->BousaiTenkenMonth;
$BousaiKojiTime = $myBukken->BousaiKojiTime;
$GyosyaBousaiCD = $myBukken->GyosyaBousaiCD;
$KanriCompanyBousaiCD = $myBukken->KanriCompanyBousaiCD;
$WorkPlace = $myBukken->WorkPlace;
$BoukaBikou = $myBukken->BoukaBikou;
$BousaiLastUpdated = $myBukken->BousaiLastUpdated;
$BousaiLastUpdater = $myBukken->BousaiLastUpdater;

if ($BousaiLastUpdater) {
	$myUser = new User($myDB);
	if (!$myUser->executeSelect("MukouFlg = FALSE AND UserCD = " . $BousaiLastUpdater, "") || $myUser->RecCnt != 1) {
		trigger_error("Getting User Failed.", E_USER_ERROR);
	}
	$name = $myUser->LastName;
	unset($myUser);
} else {
	$name = "";
}



if ($LatestIraiKojiKind == "1") {
	$IfKobetsu = "true";
} else if ($LatestIraiKojiKind == "2") {
	$IfNotKobetsu = "true";
} else {
	$IfKobetsu = "true";
}

$CurrentMonth = date('n');
$date = array($SougouTenkenMonth, $KikiTenkenMonth);
$num = 0;
while ($num < 13) {
	$check_date = $CurrentMonth + $num;
	if ($check_date > 12) {
		$check_date = $check_date - 12;
	}
	$index = array_search($check_date, $date);
	if ($index !== FALSE) {
		if ($SougouTenkenMonth == $check_date) {
			$NextTenkenKind = 2;
			$IfNextSougou = true;
		} else if ($KikiTenkenMonth == $check_date) {
			$NextTenkenKind = 1;
			$IfNextKiki = true;
		}
		$result_date[$i] = $date[$index];
		break;
	}
	$num++;
}

if ($IfNextKiki) {
	$NitteiLoop = $KikiTenkenKikan;
} else if ($IfNextSougou) {
	$NitteiLoop = $SougouTenkenKikan;
}
########################################################
# 施工業者担当者
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "gt.UserCD, ";		#0業者担当CD
$sql .= "gt.LastName, ";	#1業者担当名
$sql .= "gt.GyosyaCD, ";		#2業者CD
$sql .= "gt.TEL, ";			#3業者担当TEL
$sql .= "gt.Address3, ";	#4業者担当携帯
$sql .= "gt.EMail, ";		#5業者担当メールアドレス
$sql .= "gt.Address3, ";	#6  2こめのメールアドレス
$sql .= "gt.Notes, ";		#7備考
$sql .= "g.GyosyaName, ";	#8業者名
$sql .= "g.ShozokuCD ";		#9管轄支店　|3|4|5|となっている。
$myListObject->SelectSQL = $sql;
$sql = " FROM tUserM gt , tGyosyaM g ";
$sql .= " WHERE gt.GyosyaCD = g.GyosyaCD AND gt.MukouFlg = FALSE AND UserKbn = 3 ";

$myListObject->Condition	= $sql;
$myListObject->Order 		= "CAST( g.GyosyaNameKana as BINARY ) "; #表示順
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting User List Failed.", E_USER_ERROR);

$GyosyaTantoLoop = $myListObject->Rows;
for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
	$GyosyaTantoCD[$i] 		= $myListObject->GetValue($i, 0);
	$GyosyaTantoName[$i]	= $myListObject->GetValue($i, 1);
	$GyosyaName[$i] 		= $myListObject->GetValue($i, 8);
	$GyosyaKey[$GyosyaTantoCD[$i]] = $i;
}
unset($myListObject);


unset($myBukken);
########################################################
# フォーマットファイルがアップされているか確認
########################################################
$targetPath = "E:/xampp8.2.4/kotei/httpdocs/template/" . $ClientCD; //4
if (file_exists($targetPath)) {
	//存在したときの処理
	$IfUpSumi = true;
}
########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_date_bousai_test.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
########################################################
# 関数群
########################################################
function date_sort($a, $b)
{
	return strtotime($a) - strtotime($b);
}
