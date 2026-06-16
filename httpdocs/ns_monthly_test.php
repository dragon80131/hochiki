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
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSGyosya.cls";
// include_once _CLS_DIR . "SPUSSchedule.cls";
// include_once _CLS_DIR . "SPUSGenba.cls";
include_once _CLS_DIR . "SPFWParameter.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# パラメータ
########################################################
$rKey = SPFWParameter::getValues('rKey');
$editYear = SPFWParameter::getValues('yearlist') ? SPFWParameter::getValues('yearlist') : date('Y');
$editMonth = SPFWParameter::getValues('monthlist') ? SPFWParameter::getValues('monthlist') : date('m');
########################################################
# 入力チェック
########################################################
if ($rKey == NULL) {
	$URL = _MAIN_URL . 'login_form.php';
	header('Location: ' . $URL);
	exit;
}
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

$loginID = $myUser->ID;
$Extra1 = $myUser->Extra1; #所属CD 支店CD
$Extra3 = $myUser->Extra3; #ユーザ種別　管理ユーザ:２　一般ユーザ:１
$wUserCD = $myUser->UserCD;
$ClientCD = $myUser->ClientCD;//幹事企業CD
$UserKbn = $myUser->UserKbn;

$wGyosyaCD = $myUser->GyosyaCD;

unset($myUser);

if ($Extra1 == "2") {
	$IfMenu = true;
} else {
	$IfMenu = false;
}
########################################################
# 日付取得
########################################################
// 祝日データ取得
$holidays = holiday_check();

//現在の年月
$now = time();
$yearnow = date("Y", $now);
$yearnow2 = date("Y", $now) - 1;
$monthnow = date("n", $now);

if ($editYear <> "") {
	$yearlist = $editYear;
	$monthlist = $editMonth;
}
$YearLoop = 3; // とりあえず3年分選択可能
for ($i = 0; $i < $YearLoop; $i++) {
	if ($i == 0) {
		$year[$i] = $yearnow2;
	} else {
		$year[$i] = $yearnow2 + $i;
	}
	if ($yearlist <> "") {
		$yearselected[$i] = ($year[$i] == $yearlist) ? "selected" : NULL;	// 選択された年を表示
	} else {
		$yearselected[$i] = ($year[$i] == $yearnow) ? "selected" : NULL;	// 現在の年を表示
	}
	if ($yearselected[$i] == "selected") {
		$tYear = $year[$i];	// 表示したい年
	}
}

$MonthLoop = 12; // 固定 12か月
for ($i = 0; $i < 12; $i++) {
	$month[$i] = $i + 1;
	$month[$i] = sprintf('%02d', $month[$i]);

	if ($monthlist <> "") {
		$monthselected[$i] = ($i + 1 == $monthlist) ? "selected" : NULL;	// 選択された月を表示
	} else {
		$monthselected[$i] = ($i + 1 == $monthnow) ? "selected" : NULL;	// 現在の月を表示
	}
	if ($monthselected[$i] == "selected") {
		$tMonth = $i + 1;	// 表示したい月
		$tMonth_nofill = $tMonth; // 祝日計算用で0詰めしない
		$tMonth = sprintf('%02d', $tMonth); // 0詰め
	}
}
// 表示する月の最終日を取得
$d = new DateTime('last day of' . $tYear . '-' . $tMonth);
$lastday = substr($d->format('Y-m-d'), -2);

$DayLoop = $lastday;
$daycount = $lastday;
$WeekLoop = $lastday;
for ($i = 0; $i < $WeekLoop; $i++) {
	$Day = $i + 1;
	// 日付の表示
	$dd[$i] = sprintf("%02d", $Day);
	// 曜日の表示
	$datetime = new DateTime();
	$datetime->setDate($tYear, $tMonth, $Day);
	$weekarray = array("日", "月", "火", "水", "木", "金", "土");
	$w = $datetime->format('w');
	$dw[$i] = $weekarray[$w];
	$dc[$i] = "white"; // 先に全部白色に塗る（透明対策）
	// 土日
		if (($dw[$i] == "土") or ($dw[$i] == "日")) {
		$dc[$i] = "lightpink";
		$Count[$i] = "×";
	}
	// 祝日
	$strDay = $tYear . "/" . $tMonth_nofill . "/" . $Day; // 0詰めしていないv
	if (in_array($strDay, $holidays)) {
		$dc[$i] = "lightpink";
		$Count[$i] = "×";
	}
}
// 28日までは必ず存在するので、それ以降の表示・・・
if ($lastday == "31") {
	$Ifday29 = true;
	$Ifday30 = true;
	$Ifday31 = true;
} else if ($lastday == "30") {
	$Ifday29 = true;
	$Ifday30 = true;
} else if ($lastday == "29") {
	$Ifday29 = true;
}
########################################################
# 業者名
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "GyosyaCD, ";
$sql .= "GyosyaName, ";
$sql .= "Color ";

$myListObject->SelectSQL = $sql;
$sql = " FROM tGyosyaM ";

$sql .= " WHERE MukouFlg = FALSE";
$sql .= " AND ClientCD = ".$ClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$GyosyaLoop = $myListObject->Rows;
for ($i = 0; $i < $GyosyaLoop; $i++) {

	$GyosyaCDArray[$i] 	= $myListObject->GetValue($i, 0);
	$GyosyaNameArray[$i] = $myListObject->GetValue($i, 1);
	$GyosyaInfoArray[$GyosyaCDArray[$i]] = $GyosyaNameArray[$i];
	
	$Color[$i] = $myListObject->GetValue($i, 2);
	$GyosyaColorArray[$GyosyaCDArray[$i]] = $Color[$i];

}
unset($myListObject);
########################################################
#region 担当者名
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "UserCD, ";
$sql .= "LastName ";

$myListObject->SelectSQL = $sql;
$sql = " FROM tUserM ";

$sql .= " WHERE MukouFlg = FALSE";
$sql .= " AND ClientCD = ".$ClientCD;

$myListObject->Condition = $sql;
$myListObject->Order = "";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$UserLoop = $myListObject->Rows;
for ($i = 0; $i < $UserLoop; $i++) {
	$UserCDArray[$i] 	= $myListObject->GetValue($i, 0);
	$LastNameArray[$i] = $myListObject->GetValue($i, 1);
	$UserInfoArray[$UserCDArray[$i]] = $LastNameArray[$i];
}
unset($myListObject);
#endregion
########################################################
#region 現場取得 選択した月に作業のある現場のみ取得
########################################################
$sqldate = "'" . $tYear . "-" . $tMonth . "'"; // SQL用検索日付

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "BukkenCD, ";
$sql .= "BukkenName, ";
$sql .= "Date1, ";
$sql .= "Date2, ";
$sql .= "Date3, ";
$sql .= "Date4, ";
$sql .= "Date5, ";
$sql .= "Kosu, ";
$sql .= "GyosyaCD, ";
$sql .= "Address, ";
$sql .= "KanriCompanyCD, ";
$sql .= "ReportSubmitted, ";
$sql .= "TantoCD1, ";
$sql .= "TantoCD2, ";
$sql .= "KikiStatus, ";
$sql .= "KikiTenkenMonth, ";
$sql .= "SougouTenkenMonth ";

$myListObject->SelectSQL = $sql;
$sql = " FROM tBukkenM ";

$sql .= " WHERE MukouFlg = FALSE";

if($UserKbn == 1){

	$sql .= " AND ClientCD = ".$ClientCD;

}else if($UserKbn == 3){

	$sql .= " AND GyosyaCD = ".$wGyosyaCD;

}

if($editMonth){
	//定期での登録月での検索
	$sql .= " AND ( KikiTenkenMonth = $editMonth OR SougouTenkenMonth = $editMonth )";

}

$myListObject->Condition = $sql;
$myListObject->Order = " Date1 asc";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$BukkenLoop = $myListObject->Rows;
for ($i = 0; $i < $BukkenLoop; $i++) {
	
	$BukkenCD[$i] = $myListObject->GetValue($i, 0);
	$BukkenName[$i] = $myListObject->GetValue($i, 1);

	$GyosyaCD[$i] = $myListObject->GetValue($i, 8);
	
	$Date1[$i] = $myListObject->GetValue($i, 2);
	if($Date1[$i]){
		$Year1 = substr($Date1[$i],0,4);
		$Month1 = substr($Date1[$i],5,2);
		if($Year1 == $editYear AND $Month1 == $editMonth){
			$Day1 = substr($Date1[$i],8,2);
			if($GyosyaColorArray[$GyosyaCD[$i]]){
				${"Bcolor" . $Day1}[$i] = $GyosyaColorArray[$GyosyaCD[$i]];
			}else {
				${"Bcolor" . $Day1}[$i] = "orange";
			}
		}
	}

	$Date2[$i] = $myListObject->GetValue($i, 3);
	if($Date2[$i]){
		$Month2 = substr($Date2[$i],5,2);
		$Year2 = substr($Date2[$i],0,4);
		if($Year2 == $editYear AND $Month2 == $editMonth){
			$Day2 = substr($Date2[$i],8,2);
			if($GyosyaColorArray[$GyosyaCD[$i]]){
				${"Bcolor" . $Day2}[$i] = $GyosyaColorArray[$GyosyaCD[$i]];
			}else {
				${"Bcolor" . $Day2}[$i] = "orange";
			}
		}
	}

	$Date3[$i] = $myListObject->GetValue($i, 4);
	if($Date3[$i]){
		$Month3 = substr($Date3[$i],5,2);
		$Year3 = substr($Date3[$i],0,4);
		if($Year3 == $editYear AND $Month3 == $editMonth){
			$Day3 = substr($Date3[$i],8,2);
			if($GyosyaColorArray[$GyosyaCD[$i]]){
				${"Bcolor" . $Day3}[$i] = $GyosyaColorArray[$GyosyaCD[$i]];
			}else {
				${"Bcolor" . $Day3}[$i] = "orange";
			}
		}
	}
	$Date4[$i] = $myListObject->GetValue($i, 5);
	if($Date4[$i]){
		$Month4 = substr($Date4[$i],5,2);
		$Year4 = substr($Date4[$i],0,4);
		if($Year4 == $editYear AND $Month4 == $editMonth){
			$Day4 = substr($Date4[$i],8,2);
			if($GyosyaColorArray[$GyosyaCD[$i]]){
				${"Bcolor" . $Day4}[$i] = $GyosyaColorArray[$GyosyaCD[$i]];
			}else {
				${"Bcolor" . $Day4}[$i] = "orange";
			}
		}
	}
	$Date5[$i] = $myListObject->GetValue($i, 6);
	if($Date5[$i]){
		$Month5 = substr($Date5[$i],5,2);
		$Year5 = substr($Date5[$i],0,4);
		if($Year5 == $editYear AND $Month5 == $editMonth){
			$Day5 = substr($Date5[$i],8,2);
			if($GyosyaColorArray[$GyosyaCD[$i]]){
				${"Bcolor" . $Day5}[$i] = $GyosyaColorArray[$GyosyaCD[$i]];
			}else {
				${"Bcolor" . $Day5}[$i] = "orange";
			}
		}

	}

	$Kosu[$i] = $myListObject->GetValue($i,7);

	$GyosyaName[$i] = $GyosyaInfoArray[$GyosyaCD[$i]];

	$Address[$i] = $myListObject->GetValue($i, 9);
	$KanriCompanyCD[$i] = $myListObject->GetValue($i, 10);
	$ReportSubmitted[$i] = $myListObject->GetValue($i, 11);
	if($ReportSubmitted[$i]){
		$Progress[$i] = "submited";
	}

	$TantoCD1[$i] = $myListObject->GetValue($i, 12);
	$TantoName1[$i] = $UserInfoArray[$TantoCD1[$i]];
	$TantoCD2[$i] = $myListObject->GetValue($i, 13);
	$TantoName2[$i] = $UserInfoArray[$TantoCD2[$i]];

	$Status[$i] = $myListObject->GetValue($i, 14);

	$KikiTenkenMonth[$i] = $myListObject->GetValue($i, 15);
	$SougouTenkenMonth[$i] = $myListObject->GetValue($i, 16);


}
unset($myListObject);
#endregion
##################################################
#
##################################################
if($BukkenCD){

	$BukkenCDString = implode(",",$BukkenCD);

	$myListObject = new SPFWListObject($myDB);
	
	$sql = "SELECT ";
	$sql .= "BukkenCD, ";
	$sql .= "BukkenName, ";
	$sql .= "Date1, ";
	$sql .= "Date2, ";
	$sql .= "Date3, ";
	$sql .= "Date4, ";
	$sql .= "Date5, ";
	$sql .= "Kosu, ";
	$sql .= "GyosyaCD, ";
	$sql .= "Address, ";
	$sql .= "KanriCompanyCD, ";
	$sql .= "ReportSubmitted, ";
	$sql .= "TantoCD1, ";
	$sql .= "TantoCD2, ";
	$sql .= "KikiStatus, ";
	$sql .= "KikiTenkenMonth, ";
	$sql .= "SougouTenkenMonth ";
	
	$myListObject->SelectSQL = $sql;
	$sql = " FROM tBukkenM ";
	
	$sql .= " WHERE MukouFlg = FALSE";
	$sql .= " AND BukkenCD NOT IN(".$BukkenCDString.")";
	
	if($UserKbn == 1){

		$sql .= " AND ClientCD = ".$ClientCD;

	}else if($UserKbn == 3){

		$sql .= " AND GyosyaCD = ".$wGyosyaCD;

	}
	
	if($editMonth){

		//登録日が該当月としてあるとき
		$sql .= " AND ( ( DATE_FORMAT(Date1, '%Y%m') = ".$editYear.$editMonth." )";
		$sql .= " OR ( DATE_FORMAT(Date2, '%Y%m') = ".$editYear.$editMonth." )";
		$sql .= " OR ( DATE_FORMAT(Date3, '%Y%m') = ".$editYear.$editMonth." )";
		$sql .= " OR ( DATE_FORMAT(Date4, '%Y%m') = ".$editYear.$editMonth." )";
		$sql .= " OR ( DATE_FORMAT(Date5, '%Y%m') = ".$editYear.$editMonth." ))";

	}
	
	$myListObject->Condition = $sql;
	$myListObject->Order = " Date1 asc";

	$myListObject->Limit = "allpage";
	
	if (!($myListObject->GetList(1)))
		trigger_error("Getting Menu List Failed.", E_USER_ERROR);
	
	$BukkenLoop2 = $myListObject->Rows;
	for ($i = 0; $i < $BukkenLoop2; $i++) {
	
		$j = $BukkenLoop + $i;
		
		$BukkenCD[$j] = $myListObject->GetValue($i, 0);
		$BukkenName[$j] = $myListObject->GetValue($i, 1);

		$Date1[$j] = $myListObject->GetValue($i, 2);
		$GyosyaCD[$j] = $myListObject->GetValue($i, 8);

		if($Date1[$j]){

			$Year1 = substr($Date1[$j],0,4);
			if($Year1 == $editYear){

				$Day1 = substr($Date1[$j],8,2);

				if($GyosyaColorArray[$GyosyaCD[$j]]){
					${"Bcolor" . $Day1}[$j] = $GyosyaColorArray[$GyosyaCD[$j]];
				}else {
					${"Bcolor" . $Day1}[$j] = "orange";
				}

			}
		}
	
		$Date2[$j] = $myListObject->GetValue($i, 3);
		if($Date2[$j]){
			$Year2 = substr($Date2[$j],0,4);
			if($Year2 == $editYear){
				$Day2 = substr($Date2[$j],8,2);
				if($GyosyaColorArray[$GyosyaCD[$j]]){
					${"Bcolor" . $Day2}[$j] = $GyosyaColorArray[$GyosyaCD[$j]];
				}else {
					${"Bcolor" . $Day2}[$j] = "orange";
				}
			}
		}
	
		$Date3[$j] = $myListObject->GetValue($i, 4);
		if($Date3[$j]){
			$Year3 = substr($Date3[$j],0,4);
			if($Year3 == $editYear){
				$Day3 = substr($Date3[$j],8,2);

				if($GyosyaColorArray[$GyosyaCD[$j]]){
					${"Bcolor" . $Day3}[$j] = $GyosyaColorArray[$GyosyaCD[$j]];
				}else {
					${"Bcolor" . $Day3}[$j] = "orange";
				}

			}
		}
	
		$Date4[$j] = $myListObject->GetValue($i, 5);
		if($Date4[$j]){
			$Year4 = substr($Date4[$j],0,4);
			if($Year4 == $editYear){
				$Day4 = substr($Date4[$j],8,2);
				
				if($GyosyaColorArray[$GyosyaCD[$j]]){
					${"Bcolor" . $Day4}[$j] = $GyosyaColorArray[$GyosyaCD[$j]];
				}else {
					${"Bcolor" . $Day4}[$j] = "orange";
				}
			}
		}
	
		$Date5[$j] = $myListObject->GetValue($i, 6);
		if($Date5[$j]){
			$Year5 = substr($Date5[$j],0,4);
			if($Year5 == $editYear){
				$Day5 = substr($Date5[$j],8,2);
			
				if($GyosyaColorArray[$GyosyaCD[$j]]){
					${"Bcolor" . $Day5}[$j] = $GyosyaColorArray[$GyosyaCD[$j]];
				}else {
					${"Bcolor" . $Day5}[$j] = "orange";
				}
			}
		}
	
		$Kosu[$j] = $myListObject->GetValue($i,7);
	
		$GyosyaName[$j] = $GyosyaInfoArray[$GyosyaCD[$j]];
	
		$Address[$j] = $myListObject->GetValue($i, 9);
		$KanriCompanyCD[$j] = $myListObject->GetValue($i, 10);
		$ReportSubmitted[$j] = $myListObject->GetValue($i, 11);
		if($ReportSubmitted[$j]){
			$Progress[$j] = "submited";
		}
	
		$TantoCD1[$j] = $myListObject->GetValue($i, 12);
		$TantoName1[$j] = $UserInfoArray[$TantoCD1[$j]];
		$TantoCD2[$j] = $myListObject->GetValue($i, 13);
		$TantoName2[$j] = $UserInfoArray[$TantoCD2[$j]];
	
		$Status[$j] = $myListObject->GetValue($i, 14);
	
		$KikiTenkenMonth[$j] = $myListObject->GetValue($i, 15);
		$SougouTenkenMonth[$j] = $myListObject->GetValue($i, 16);

	}
}

$BukkenLoops = $BukkenLoop + $BukkenLoop2;

function holiday_check()
{
	$holidays = array();
	$file_array = file("holiday.log");
	for ($i = 0; $i < count($file_array); $i++) {
		if ($file_array[$i]) {
			$file_array[$i] = str_replace(array("\r\n", "\r", "\n"), '', $file_array[$i]);
			$val = explode(',', $file_array[$i]);
			array_push($holidays, $val[1]);
		}
	}
	return $holidays;
}

########################################################
# コンテンツ表示
########################################################
SPFWTemplate::dropValue("editYear");
SPFWTemplate::dropValue("editMonth");
$CNT_FILE = "ns_monthly.tpl";
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();
unset($myTemplate);
unset($myLog);