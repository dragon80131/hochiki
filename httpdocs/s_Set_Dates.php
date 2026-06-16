<?php

include_once "C:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
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
include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPUSClient.cls";
include_once _CLS_DIR . "SPUSSettingDates.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
########################################################
#region 認証動作
########################################################
$myUser = new User($myDB);

if ($rKey) {
	$IfrKey = true;
	$ExistrKey = true;
} else {
	$ExistrKey = false;
}

$mySettingDates = new SettingDates($myDB);
if (!$mySettingDates->executeSelect(" BukkenCD = '" . $editBukkenCD . "' AND MukouFlg = FALSE", "")) {
	// trigger_error("Getting Bukken Failed.", E_USER_ERROR);
} else {
}

$myBukken = new Bukken($myDB);
if (!$myBukken->executeSelect(" BukkenCD = '" . $editBukkenCD . "' AND MukouFlg = FALSE", "")) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}

$BukkenName = $myBukken->BukkenName;
$ClientCD = $myBukken->ClientCD;

$UketsukeShimekiriDate = $myBukken->UketsukeShimekiriDate;

$SougouTenkenMonth 	   = $myBukken->SougouTenkenMonth;
$KikiTenkenMonth 	   = $myBukken->KikiTenkenMonth;

$SougouAMKojiTime = $myBukken->SougouAMKojiTime;
$SougouPMKojiTime = $myBukken->SougouPMKojiTime;
$SougouTenkenKikan = $myBukken->SougouTenkenKikan;

$KikiAMKojiTime = $myBukken->KikiAMKojiTime;
$KikiPMKojiTime = $myBukken->KikiPMKojiTime;
$KikiTenkenKikan = $myBukken->KikiTenkenKikan;

$Date1 = $myBukken->Date1;
$Date2 = $myBukken->Date2;
$Date3 = $myBukken->Date3;
$Date4 = $myBukken->Date4;
$Date5 = $myBukken->Date5;
$Date6 = $myBukken->Date6;

$ExistTenkenKikan = $myBukken->ExistTenkenKikan; //点検種類(機器)
$ExistTenkenKikan_Sougou = $myBukken->ExistTenkenKikan_Sougou; //点検種類(総合)

$TatoFlg = $myBukken->TatoFlg;
if ($TatoFlg == "1") $IfTatoFlg = true;

$myClient = new Client($myDB);
if (!$myClient->executeSelect(" ClientCD = '" . $ClientCD . "' AND MukouFlg = FALSE", "")) {
	trigger_error("Getting Bukken Failed.", E_USER_ERROR);
}

$ClientName = $myClient->ClientName;
$TEL = $myClient->TEL;


$today = date("Y-m-d");
if (!$rKey) {

	if (strtotime($today) > strtotime($UketsukeShimekiriDate)) {
		$URL = _MAIN_URL . "s_Taio_Exceed_UketsukeDate.php";
		header('Location: ' . $URL);
		exit;
	} else {
	}
}


unset($myUser);

#endregion


######################################################
#region チェック処理
######################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "Date ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tSettingDatesM";
// if($UserKbn != 2){#ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
$sql .= " WHERE MukouFlg = FALSE AND BukkenCD = " . $editBukkenCD;
$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);
$SettingDatesLoop = $myListObject->Rows;
// if ($SettingDatesLoop > 0) {

for ($i = 0; $i < $SettingDatesLoop; $i++) {
	$SettingDates[$i] 	= $myListObject->GetValue($i, 0);
}
// }
if (!is_array($SettingDates)) {
	$SettingDates = array();
}

unset($myListObject);


#endregion

###########################################################
# 今回の点検が機器点検か総合点検を判断
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

		$Kikan = $SougouTenkenKikan;

		if ($SougouAMKojiTime) {

			$IfNextTenkenAM = true;
			$NextTenkenAM = $SougouAMKojiTime;
		}
		if ($SougouPMKojiTime) {

			$IfNextTenkenPM = true;
			$NextTenkenPM = $SougouPMKojiTime;
		}

		if ((!$SougouAMKojiTime or !$SougouPMKojiTime)) {

			$IfNextTenkenAM = false;
			$IfNextTenkenPM = false;

			$IfOnlyOne = true;
		}

		// 一部住戸かどうか判断
		if ($ExistTenkenKikan_Sougou == "1") {
			$IfItibuZyuko = true;
		}
	} else if ($check_date == $KikiTenkenMonth) {

		$Kikan = $KikiTenkenKikan;

		if ($KikiAMKojiTime) {

			$IfNextTenkenAM = true;
			$NextTenkenAM = $KikiAMKojiTime;
		}

		if ($KikiPMKojiTime) {

			$IfNextTenkenPM = true;
			$NextTenkenPM = $KikiPMKojiTime;
		}

		if (!$KikiAMKojiTime or !$KikiPMKojiTime) {

			$IfNextTenkenAM = false;
			$IfNextTenkenPM = false;

			$IfOnlyOne = true;
		}

		// 一部住戸かどうか判断
		if ($ExistTenkenKikan == "1") {
			$IfItibuZyuko = true;
		}
	}

	$index = array_search($check_date, $date);
	if ($index !== FALSE) {
		$result_date = $date[$index];
		break;
	}

	$num++;
}

if ($Kikan) {

	for ($i = 0; $i < $Kikan; $i++) {

		$k = $i + 1;

		if ($NextTenkenAM) {

			$Dates[] = ${"Date" . $k} . "　" . $NextTenkenAM;
			$AMPM[] = "AM";
			$DatesData[] = ${"Date" . $k};

			// 値の存在チェック1
			if (in_array(${"Date" . $k} . "　" . $NextTenkenAM, $SettingDates)) {
				$DatesChecked[] = "checked";
			} else {
				$DatesChecked[] = "";
			}
		}

		if ($NextTenkenPM) {

			$Dates[] = ${"Date" . $k} . "　" . $NextTenkenPM;
			$AMPM[] = "PM";
			$DatesData[] = ${"Date" . $k};

			// 値の存在チェック2
			if (in_array(${"Date" . $k} . "　" . $NextTenkenPM, $SettingDates)) {
				$DatesChecked[] = "checked";
			} else {
				$DatesChecked[] = "";
			}
		}
	}


	$DatesLoop = count($Dates);
}







########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_Set_Dates.tpl";

$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();

$myTemplate->convertTags();
$myTemplate->outputTemplate();

unset($myTemplate);
unset($myLog);
	########################################################
	# 関数群
	########################################################
