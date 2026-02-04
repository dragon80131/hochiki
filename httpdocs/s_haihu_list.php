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

$SougouTenkenMonth 	    = $myBukken->SougouTenkenMonth;
$KikiTenkenMonth 		= $myBukken->KikiTenkenMonth;

$BousaiFlg 			    = $myBukken->BousaiFlg ? $myBukken->BousaiFlg : 0;
$BousaiTenkenMonth 	    = $myBukken->BousaiTenkenMonth ? $myBukken->BousaiTenkenMonth : 0;

$Format["小規模個別日程なし長谷工コミュニティバージョン"] = 1;
$Format["大規模個別日程あり長谷工コミュニティバージョン"] = 2;
$Format["小規模個別日程なしセントラルライフバージョン"] = 3;

switch ($WFormatType) {
	case $Format["小規模個別日程なし長谷工コミュニティバージョン"];
		$IfNotKobetsu = "true";
		break;

	case $Format["大規模個別日程あり長谷工コミュニティバージョン"];
		$IfKobetsu = "true";
		break;

	case $Format["小規模個別日程なしセントラルライフバージョン"];
		$IfNotKobetsu = "true";
		break;
}


require './vendor/autoload.php';

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

$writer = new PngWriter();
$qrCode = QrCode::create('https://app5.489501.jp/kotei/s_Taio_Form.php?editBukkenCD=' . $editBukkenCD)
	->setEncoding(new Encoding('UTF-8'))
	->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
	->setSize(100)
	->setMargin(10)
	->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
	->setForegroundColor(new Color(0, 0, 0))
	->setBackgroundColor(new Color(255, 255, 255));

$result = $writer->write($qrCode);
$dataUri = $result->getDataUri();


########################################################
# お知らせファイル情報
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "FileCD, ";
$sql .= "ServerFileName, ";
$sql .= "RealFileName, ";
$sql .= "FileKind ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tFileF";
$sql .= " WHERE MukouFlg = FALSE ";
$sql .= " AND BukkenCD = " . $editBukkenCD;
$sql .= " AND FileKind = 3 ";
$myListObject->Condition	= $sql;
$myListObject->Order 		= " Created desc ";
$myListObject->Limit 		= "7";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$ReportFileLoop = $myListObject->Rows;
for ($i = 0; $i < $ReportFileLoop; $i++) {
	$Report_FileCD[$i] 	= $myListObject->GetValue($i, 0);
	$Report_ServerFileName[$i]	= $myListObject->GetValue($i, 1);
	$Report_RealFileName[$i]	= $myListObject->GetValue($i, 2);

	$Array[$i]['FileCD'] = $Report_FileCD[$i];
	$Array[$i]['ServerFileName'] = $Report_ServerFileName[$i];
	$Array[$i]['RealFileName'] = $Report_RealFileName[$i];
}
$ArrayInfo = json_encode($Array);

unset($myListObject);

###########################################################
# 
###########################################################
$CurrentMonth = date('n');
$date = array($SougouTenkenMonth, $KikiTenkenMonth);
$num = 0;
$result_date = "";
while ($num < 13) {
	$check_date = $CurrentMonth + $num;
	if ($check_date > 12) {
		$check_date = $check_date - 12;
	}

	if ($check_date == $SougouTenkenMonth) {
		$NextTenkenKind = "NextTenkenKind";
	} else if ($check_date == $KikiTenkenMonth) {
		$NextTenkenKind2 = "NextTenkenKind";
	}
	$index = array_search($check_date, $date);
	if ($index !== FALSE) {
		$result_date = $date[$index];
		break;
	}
	$num++;
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
$sql .= " WHERE gt.GyosyaCD = g.GyosyaCD AND gt.MukouFlg = FALSE AND UserKbn = 3 "; //

#if($MyZokusei == 1) { #一般ユーザは自分の所属のみ
#	$sql .= " AND g.ShozokuCD like '%".$Extra1."%'  ";
#}

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


########################################################
# 登録済日程
########################################################
$myKojiNittei = new KojiNittei($myDB);
if (!$myKojiNittei->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD, "") || $myKojiNittei->RecCnt != 1) {
	// trigger_error("Getting myKojiNittei Failed.", E_USER_ERROR);
}
$Date 			= $myKojiNittei->Date;
$Time 			= $myKojiNittei->Time;


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

$CNT_FILE = "s_haihu_list.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
