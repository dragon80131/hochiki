<?php
$isAdminMode = TRUE;
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
include_once _CLS_DIR . "SPUSBuilding.cls";
include_once "./include/common.php";
include_once "./include/holiday_helpers.php";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################

$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$m 				= SPFWParameter::getValues('m');
$PurposeSinki 	= SPFWParameter::getValues('PurposeSinki');

$work 			= SPFWParameter::getValues('work');
$checkcopy		= SPFWParameter::getValues('checkcopy');

$wEditBukkenCD	= $editBukkenCD;
if($work == '1'){
	if($checkcopy != ''){
		$wEditBukkenCD = $checkcopy;
	}else{
		$wEditBukkenCD = '';
	}
}

if ($editBukkenCD) {
	$IfSinki = true;
}
// echo "<br> ".__LINE__." editBukkenCD :".$editBukkenCD;
########################################################
# 認証動作
########################################################

$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS2);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS2);

$UserCD 		= $myUser->UserCD;


$ClientCD 		= $myUser->ClientCD; #幹事企業CD
$EigyosyoCD 		= $myUser->EigyosyoCD; #幹事企業支店・営業所CD
$UserKbn 		= $myUser->UserKbn; #1:幹事企業一般 2:管理者 3:協力業者CD
$editGyosyaCD	= $myUser->GyosyaCD; #協力業者CD

$IfWorker		= $UserKbn == 3;
$IfDeveloper	= $UserKbn != 3;
$IfSP 			= $m == 1; // スマホ用
$IfPC 			= $m != 1;
$IfShowSchedule = false;
if($IfSP){
	if($IfDeveloper)
		$IfShowSchedule = true;
	$IfWorker		= true;
	$IfDeveloper	= false;
}


unset($myUser);

$IfRegist	= true;
$IfUpdate	= false;

if ($wEditBukkenCD) {
	if($editBukkenCD){
		$IfRegist	= false;
		$IfUpdate	= true;
	}

	########################################################
	# 棟一覧
	########################################################
	$myListObject = new SPFWListObject($myDB);

	$sql = "SELECT ";
	$sql .= "u.BuildingCD, ";
	$sql .= "u.BuildingName, ";
	$sql .= "u.Kosu, ";
	$sql .= "u.Kaidaka, ";
	$sql .= "u.SenyuStartDate, ";
	$sql .= "u.SenyuEndDate, ";
	$sql .= "u.YoyakuEndDate, ";
	$sql .= "u.Holiday1, ";
	$sql .= "u.ReserveDay ";

	$myListObject->SelectSQL = $sql;

	$sql = " FROM tBuildingM u ";
	$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$wEditBukkenCD."'";

	$myListObject->Condition = $sql;
	$myListObject->Order = "u.BuildingCD ASC";
	$myListObject->Limit = "allpage";

	if (!($myListObject->GetList(1)))
		trigger_error("Getting User List Failed.", E_USER_ERROR);

	$BuildingCD = [];
	$BuildingName = [];
	$Kosu = [];
	$Kaidaka = [];
	$BuildingNo = [];
	$BuildingSenyuStartDate = [];
	$BuildingSenyuEndDate = [];
	$BuildingYoyakuEndDate = [];
	$BuildingSenyuDate = [];

	$BuildingHoliday1 = [];
	$BuildingHoliday2 = [];
	$BuildingHoliday3 = [];
	$BuildingHolidayPeriod1 = [];
	$BuildingHolidayPeriod2 = [];
	$BuildingHolidayPeriod3 = [];
	$BuildingHolidayPeriodSelect1 = [];
	$BuildingHolidayPeriodSelect2 = [];
	$BuildingHolidayPeriodSelect3 = [];
	$BuildingKyukoTable = [];
	$BuildingHolidayStr = [];

	$BuildingReserveDay1 = [];
	$BuildingReserveDay2 = [];
	$BuildingReserveDay3 = [];
	$BuildingReserveDayTable = [];
	$BuildingReserveDayStr = [];


	$LastBuildingNo = 1;

	$BuildingLoop = $myListObject->Rows;

	for ($i = 0; $i < $BuildingLoop; $i++) {
		$BuildingCD[$i] = $myListObject->GetValue($i, 0);
		$BuildingName[$i] = $myListObject->GetValue($i, 1);
		$Kosu[$i] = $myListObject->GetValue($i, 2);
		$Kaidaka[$i] = $myListObject->GetValue($i, 3);
		$BuildingNo[$i] = $i+2;
		$LastBuildingNo = $BuildingNo[$i];
		if ($IfUpdate) {
			$BuildingSenyuStartDate[$i] = $myListObject->GetValue($i, 4);
			$BuildingSenyuEndDate[$i] = $myListObject->GetValue($i, 5);
			$BuildingYoyakuEndDate[$i] = $myListObject->GetValue($i, 6);
			$Holiday1Data = $myListObject->GetValue($i, 7);
			$ReserveDay1Data = $myListObject->GetValue($i, 8);
			if($BuildingSenyuStartDate[$i] || $BuildingSenyuEndDate[$i]){
				$BuildingSenyuDate[$i] = $BuildingSenyuStartDate[$i] . ' ～ ' . $BuildingSenyuEndDate[$i];
			}
			$BuildingHoliday1[$i] ="";
			$BuildingHoliday2[$i] ="";
			$BuildingHoliday3[$i] ="";
			$BuildingHolidayPeriod1[$i] ="ALL";
			$BuildingHolidayPeriod2[$i] ="ALL";
			$BuildingHolidayPeriod3[$i] ="ALL";

			if ($Holiday1Data) {
				$HolidayItems = parseHoliday1($Holiday1Data);
				$BuildingHolidayStrParts = array();
				foreach ($HolidayItems as $hi) {
					$BuildingHolidayStrParts[] = formatHolidayDisplay($hi['token']);
				}
				$BuildingHolidayStr[$i] = implode(', ', $BuildingHolidayStrParts);

				for ($k = 1; $k <= count($HolidayItems); $k++) {
					${"BuildingHoliday" . $k}[$i] = $HolidayItems[$k - 1]['date'];
					${"BuildingHolidayPeriod" . $k}[$i] = $HolidayItems[$k - 1]['period'];
				}
				$maxNo 	= count($HolidayItems);
				$maxNo1 = ceil($maxNo / 3);
				$maxNo 	= $maxNo1 * 3 + 1;
				$j = 4;
				$holidayDateName = "editHoliday".$BuildingCD[$i]."[]";
				$holidayPeriodName = "editHolidayPeriod".$BuildingCD[$i]."[]";
				$holidayDateClass = "wHoliday".$LastBuildingNo;
				for ($k = 1; $k < $maxNo1; $k++) {
					$BuildingKyukoTable[$i] .= "<tr><td><span class='kyukobi-action'><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='images/icon_delete.png'></a></span></td>";
					$BuildingKyukoTable[$i] .= "<td>";
					$BuildingKyukoTable[$i] .= holidayDatePeriodInputHtml($holidayDateName, $holidayPeriodName, ${"BuildingHoliday" . $j}[$i], ${"BuildingHolidayPeriod" . $j}[$i], $holidayDateClass);
					$BuildingKyukoTable[$i] .= holidayDatePeriodInputHtml($holidayDateName, $holidayPeriodName, ${"BuildingHoliday" . ($j + 1)}[$i], ${"BuildingHolidayPeriod" . ($j + 1)}[$i], $holidayDateClass);
					$BuildingKyukoTable[$i] .= holidayDatePeriodInputHtml($holidayDateName, $holidayPeriodName, ${"BuildingHoliday" . ($j + 2)}[$i], ${"BuildingHolidayPeriod" . ($j + 2)}[$i], $holidayDateClass);
					$BuildingKyukoTable[$i] .= "</td></tr>";
					$j += 3;
				}
			}
			$BuildingHolidayPeriodSelect1[$i] = holidayPeriodSelectHtml("editHolidayPeriod".$BuildingCD[$i]."[]", $BuildingHolidayPeriod1[$i]);
			$BuildingHolidayPeriodSelect2[$i] = holidayPeriodSelectHtml("editHolidayPeriod".$BuildingCD[$i]."[]", $BuildingHolidayPeriod2[$i]);
			$BuildingHolidayPeriodSelect3[$i] = holidayPeriodSelectHtml("editHolidayPeriod".$BuildingCD[$i]."[]", $BuildingHolidayPeriod3[$i]);

			// 予備日
			$BuildingReserveDay1[$i] ="";
			$BuildingReserveDay2[$i] ="";
			$BuildingReserveDay3[$i] ="";

			if ($ReserveDay1Data) {
				$ReserveDay = SPFWTools::decodePluralValue($ReserveDay1Data);
				$BuildingReserveDayStr[$i] = implode(', ', $ReserveDay);

				for ($k = 1; $k <= count($ReserveDay); $k++) {
					${"BuildingReserveDay" . $k}[$i] = $ReserveDay[$k - 1];
				}
				$maxNo 	= count($ReserveDay);
				$maxNo1 = ceil($maxNo / 3);
				$maxNo 	= $maxNo1 * 3 + 1;
				$j = 4;
				for ($k = 1; $k < $maxNo1; $k++) {
					$BuildingReserveDayTable[$i] .= "<tr><td><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='images/icon_delete.png'></a></td>";
					$BuildingReserveDayTable[$i] .= "<td><input type='text' name='editReserveDay".$BuildingCD[$i]."[]' value='".${"BuildingReserveDay" . $j}[$i]."' class='wReserveDay".$LastBuildingNo."' style='width:120px' >";
					$BuildingReserveDayTable[$i] .= "　<input type='text' name='editReserveDay".$BuildingCD[$i]."[]' value='".${"BuildingReserveDay" . ($j + 1)}[$i]."' class='wReserveDay".$LastBuildingNo."' style='width:120px' >";
					$BuildingReserveDayTable[$i] .= "　<input type='text' name='editReserveDay".$BuildingCD[$i]."[]' value='".${"BuildingReserveDay" . ($j + 2)}[$i]."' class='wReserveDay".$LastBuildingNo."' style='width:120px' ></td></tr>";
					$j += 3;
				}
			}				
		}else{ // $IfRegist == true
			// $BuildingSenyuStartDate[$i] = $myListObject->GetValue($i, 4);
			// $BuildingSenyuEndDate[$i] = $myListObject->GetValue($i, 5);
			// $Holiday1Data = $myListObject->GetValue($i, 6);
			// $ReserveDay1Data = $myListObject->GetValue($i, 7);
			$BuildingSenyuStartDate[$i] = null;
			$BuildingSenyuEndDate[$i] = null;
			$BuildingYoyakuEndDate[$i] = null;
			$Holiday1Data = null;
			$ReserveDay1Data = null;

			if($BuildingSenyuStartDate[$i] || $BuildingSenyuEndDate[$i]){
				$BuildingSenyuDate[$i] = $BuildingSenyuStartDate[$i] . ' ～ ' . $BuildingSenyuEndDate[$i];
			}
			$BuildingHoliday1[$i] ="";
			$BuildingHoliday2[$i] ="";
			$BuildingHoliday3[$i] ="";
			$BuildingHolidayPeriod1[$i] ="ALL";
			$BuildingHolidayPeriod2[$i] ="ALL";
			$BuildingHolidayPeriod3[$i] ="ALL";
			$BuildingHolidayPeriodSelect1[$i] = holidayPeriodSelectHtml("newHolidayPeriod".$LastBuildingNo."[]", "ALL");
			$BuildingHolidayPeriodSelect2[$i] = holidayPeriodSelectHtml("newHolidayPeriod".$LastBuildingNo."[]", "ALL");
			$BuildingHolidayPeriodSelect3[$i] = holidayPeriodSelectHtml("newHolidayPeriod".$LastBuildingNo."[]", "ALL");

			if ($Holiday1Data) {
				$Holiday = SPFWTools::decodePluralValue($Holiday1Data);
				$BuildingHolidayStr[$i] = implode(', ', $Holiday);

				for ($k = 1; $k <= count($Holiday); $k++) {
					${"BuildingHoliday" . $k}[$i] = $Holiday[$k - 1];
				}
				$maxNo 	= count($Holiday);
				$maxNo1 = ceil($maxNo / 3);
				$maxNo 	= $maxNo1 * 3 + 1;
				$j = 4;
				for ($k = 1; $k < $maxNo1; $k++) {
					$BuildingKyukoTable[$i] .= "<tr><td><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='images/icon_delete.png'></a></td>";
					$BuildingKyukoTable[$i] .= "<td><input type='text' name='newHoliday".$LastBuildingNo."[]' value='".${"BuildingHoliday" . $j}[$i]."' class='wHoliday".$LastBuildingNo."' style='width:120px' >";
					$BuildingKyukoTable[$i] .= "　<input type='text' name='newHoliday".$LastBuildingNo."[]' value='".${"BuildingHoliday" . ($j + 1)}[$i]."' class='wHoliday".$LastBuildingNo."' style='width:120px' >";
					$BuildingKyukoTable[$i] .= "　<input type='text' name='newHoliday".$LastBuildingNo."[]' value='".${"BuildingHoliday" . ($j + 2)}[$i]."' class='wHoliday".$LastBuildingNo."' style='width:120px' ></td></tr>";
					$j += 3;
				}
			}

			// 予備日
			$BuildingReserveDay1[$i] ="";
			$BuildingReserveDay2[$i] ="";
			$BuildingReserveDay3[$i] ="";

			if ($ReserveDay1Data) {
				$ReserveDay = SPFWTools::decodePluralValue($ReserveDay1Data);
				$BuildingReserveDayStr[$i] = implode(', ', $ReserveDay);

				for ($k = 1; $k <= count($ReserveDay); $k++) {
					${"BuildingReserveDay" . $k}[$i] = $ReserveDay[$k - 1];
				}
				$maxNo 	= count($ReserveDay);
				$maxNo1 = ceil($maxNo / 3);
				$maxNo 	= $maxNo1 * 3 + 1;
				$j = 4;
				for ($k = 1; $k < $maxNo1; $k++) {
					$BuildingReserveDayTable[$i] .= "<tr><td><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='images/icon_delete.png'></a></td>";
					$BuildingReserveDayTable[$i] .= "<td><input type='text' name='newReserveDay".$LastBuildingNo."[]' value='".${"BuildingReserveDay" . $j}[$i]."' class='wReserveDay".$LastBuildingNo."' style='width:120px' >";
					$BuildingReserveDayTable[$i] .= "　<input type='text' name='newReserveDay".$LastBuildingNo."[]' value='".${"BuildingReserveDay" . ($j + 1)}[$i]."' class='wReserveDay".$LastBuildingNo."' style='width:120px' >";
					$BuildingReserveDayTable[$i] .= "　<input type='text' name='newReserveDay".$LastBuildingNo."[]' value='".${"BuildingReserveDay" . ($j + 2)}[$i]."' class='wReserveDay".$LastBuildingNo."' style='width:120px' ></td></tr>";
					$j += 3;
				}
			}				
		}
	}
	unset($myListObject);
}

// if ($UserKbn == 3) {
// 	$SHeaderKanri = "<div style='text-align:center;'>";
// 	$SHeaderKanri .= "<img class='logo'  src='./images/489work.png' alt='489作業者' width='600' height='73'>";
// 	$SHeaderKanri .= "</div>";
// }

########################################################
# 物件情報取得（編集）
########################################################


$wHoliday1 ="";
$wHoliday2 ="";
$wHoliday3 ="";

$wReserveDay1 ="";
$wReserveDay2 ="";
$wReserveDay3 ="";

$KyukoTable = "";
$ReserveDayTable = "";

// 新規登録でも棟1デフォルト休工3枠に全日/午前/午後を表示
$wHolidayPeriod1 = "ALL";
$wHolidayPeriod2 = "ALL";
$wHolidayPeriod3 = "ALL";
$wHolidayPeriodSelect1 = holidayPeriodSelectHtml('wHolidayPeriod[]', $wHolidayPeriod1);
$wHolidayPeriodSelect2 = holidayPeriodSelectHtml('wHolidayPeriod[]', $wHolidayPeriod2);
$wHolidayPeriodSelect3 = holidayPeriodSelectHtml('wHolidayPeriod[]', $wHolidayPeriod3);
for ($hp = 1; $hp <= 3; $hp++) {
	${"wHolidayPeriodSelectedALL" . $hp} = "selected";
	${"wHolidayPeriodSelectedAM" . $hp} = "";
	${"wHolidayPeriodSelectedPM" . $hp} = "";
}

if ($wEditBukkenCD) {
	$myBukken = new Bukken($myDB);

	// if (!$myBukken->executeSelect("MukouFlg = FALSE AND ClientCD = $ClientCD AND BukkenCD = " . $wEditBukkenCD, "") || $myBukken->RecCnt != 1) {
	if (!$myBukken->executeSelect("MukouFlg = FALSE AND BukkenCD = " . $wEditBukkenCD, "") || $myBukken->RecCnt != 1) {
		trigger_error("Getting Bukken Failed.", E_USER_ERROR);
	}

	// $wKenmeiNo 				= $myBukken->KenmeiNo;
	$wBukkenName 			= $myBukken->BukkenName;
	$wBukkenNameKana	= $myBukken->BukkenNameKana;
	$wSagyoName 			= $myBukken->SagyoName;
	$wAddress 				= $myBukken->Address;
	// $TatoFlg 				= $myBukken->TatoFlg;
	// ${"TatoFlg".$TatoFlg} = " checked='checked'";
// echo "<br> ".__LINE__." Hensu :".${"TatoFlg".$TatoFlg};
	$ClientCD 				= $myBukken->ClientCD;

	// $ShozokuCD 				= $myBukken->ShozokuCD;
	// $wKanriGaisya 			= $myBukken->KanriGaisya;
	// $wOwner_name 			= $myBukken->Owner_name;
	// $wKanriGaisyaTanto 		= $myBukken->KanriGaisyaTanto;
	// $wKanriGaisyaTEL 		= $myBukken->KanriGaisyaTEL;

	$wTantoCD1 = $myBukken->TantoCD1;
	$wTantoCD2 = $myBukken->TantoCD2;
	$wTantoCD3 = $myBukken->TantoCD3;
	$wTantoCD4 = $myBukken->TantoCD4;
	$wTantoCD5 = $myBukken->TantoCD5;

	$wGyosyaCD = $myBukken->GyosyaCD;
	$wBrancheCD = $myBukken->BrancheCD;
	
	// $KikiTenkenMonth = $myBukken->KikiTenkenMonth;

	// $SougouTenkenMonth = $myBukken->SougouTenkenMonth;

	// $KikiTenkenKikan = $myBukken->KikiTenkenKikan;#機器点検必要日数
############
// 	$KikiAMKojiTime = $myBukken->KikiAMKojiTime;
// echo "<br> ".__LINE__." ここまでOK :".$wBukkenName;
	$WakuPattern = $myBukken->WakuPattern;
	#$WAKUPATTERN[0]['Name'] = "2枠( AM 9:00-12:00, PM 13:00-17:00)";
	$WakuPatternLoop = count($WAKUPATTERN);
	for($i=0;$i<$WakuPatternLoop;$i++){
		$DispWakuPattern[$i] = $WAKUPATTERN[$i]['Name'];
		$WakuPatternCD[$i] = $i;
		$WakuPatternSelected[$i]="";
		if($i==$WakuPattern)$WakuPatternSelected[$i] = " Selected";
	}

	$maxNo = 0; // デフォルト
	$KyukoTable = "";
	$ReserveDayTable = "";

	// $KikiPMKojiTime = $myBukken->KikiPMKojiTime;
	$wBuilding = $myBukken->BuildingName;
	$wKosu = $myBukken->Kosu;
	$wKaidaka 				= $myBukken->Kaidaka;
	if ($editBukkenCD) {
		$SenyuStartDate1 = $myBukken->SenyuStartDate1;#作業開始日
		$SenyuEndDate1 = $myBukken->SenyuEndDate1;#作業終了日

		$SenyuStartDate = $myBukken->SenyuStartDate;#作業開始日
		$SenyuEndDate = $myBukken->SenyuEndDate;#作業終了日
		$KyoyobuStartDate = $myBukken->KyoyobuStartDate;#共用部作業開始日
		$KyoyobuEndDate = $myBukken->KyoyobuEndDate;#共用部作業終了日
		$YoyakuEndDate = $myBukken->YoyakuEndDate;#受付締切日（棟1）
		$wBukkenMemo = $myBukken->BukkenMemo;
		$MinuteTime = $myBukken->MinuteTime;#工事所要時間（ex 20分）
		$Biko = $myBukken->Biko;

		// 既存棟で受付締切が未設定の場合は物件（棟1）の値を初期表示
		if (isset($BuildingLoop) && $BuildingLoop > 0) {
			for ($bi = 0; $bi < $BuildingLoop; $bi++) {
				if (empty($BuildingYoyakuEndDate[$bi]) && $YoyakuEndDate) {
					$BuildingYoyakuEndDate[$bi] = $YoyakuEndDate;
				}
			}
		}

		// 休工日
		$wHolidayPeriod1 = "ALL";
		$wHolidayPeriod2 = "ALL";
		$wHolidayPeriod3 = "ALL";
		if ($myBukken->Holiday1) {
			$HolidayItems = parseHoliday1($myBukken->Holiday1);
			for ($i = 1; $i <= count($HolidayItems); $i++) {
				${"wHoliday" . $i} = $HolidayItems[$i - 1]['date'];
				${"wHolidayPeriod" . $i} = $HolidayItems[$i - 1]['period'];
			}
			$maxNo 	= count($HolidayItems);
			$maxNo1 = ceil($maxNo / 3);
			$maxNo 	= $maxNo1 * 3 + 1;
			$j = 4;
			for ($i = 1; $i < $maxNo1; $i++) {
				$KyukoTable .= "<tr><td><span class='kyukobi-action'><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='images/icon_delete.png'></a></span></td>";
				$KyukoTable .= "<td>";
				$KyukoTable .= holidayDatePeriodInputHtml('wHoliday[]', 'wHolidayPeriod[]', ${"wHoliday" . $j}, ${"wHolidayPeriod" . $j}, 'wHoliday');
				$KyukoTable .= holidayDatePeriodInputHtml('wHoliday[]', 'wHolidayPeriod[]', ${"wHoliday" . ($j + 1)}, ${"wHolidayPeriod" . ($j + 1)}, 'wHoliday');
				$KyukoTable .= holidayDatePeriodInputHtml('wHoliday[]', 'wHolidayPeriod[]', ${"wHoliday" . ($j + 2)}, ${"wHolidayPeriod" . ($j + 2)}, 'wHoliday');
				$KyukoTable .= "</td></tr>";
				$j += 3;
			}
		}
		$wHolidayPeriodSelect1 = holidayPeriodSelectHtml('wHolidayPeriod[]', $wHolidayPeriod1);
		$wHolidayPeriodSelect2 = holidayPeriodSelectHtml('wHolidayPeriod[]', $wHolidayPeriod2);
		$wHolidayPeriodSelect3 = holidayPeriodSelectHtml('wHolidayPeriod[]', $wHolidayPeriod3);
		for ($hp = 1; $hp <= 3; $hp++) {
			$period = ${"wHolidayPeriod" . $hp};
			${"wHolidayPeriodSelectedALL" . $hp} = ($period === "ALL") ? "selected" : "";
			${"wHolidayPeriodSelectedAM" . $hp} = ($period === "AM") ? "selected" : "";
			${"wHolidayPeriodSelectedPM" . $hp} = ($period === "PM") ? "selected" : "";
		}

		if ($myBukken->ReserveDay) {
			$ReserveDay = SPFWTools::decodePluralValue($myBukken->ReserveDay);
			for ($i = 1; $i <= count($ReserveDay); $i++) {
				${"wReserveDay" . $i} = $ReserveDay[$i - 1];
			}
			$maxNo 	= count($ReserveDay);
			$maxNo1 = ceil($maxNo / 3);
			$maxNo 	= $maxNo1 * 3 + 1;
			$j = 4;
			for ($i = 1; $i < $maxNo1; $i++) {
				$ReserveDayTable .= "<tr><td><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='images/icon_delete.png'></a></td>";
				$ReserveDayTable .= "<td><input type='text' name='wReserveDay[]' value='__wReserveDay" . $j . "__' class='wReserveDay' style='width:120px' >";
				$ReserveDayTable .= "　<input type='text' name='wReserveDay[]' value='__wReserveDay" . ($j + 1) . "__' class='wReserveDay' style='width:120px' >";
				$ReserveDayTable .= "　<input type='text' name='wReserveDay[]' value='__wReserveDay" . ($j + 2) . "__' class='wReserveDay' style='width:120px' ></td></tr>";
				$j += 3;
			}

			// $IfMainBuilding = false;
			// if($BuildingLoop > 0 || $myBukken->BuildingName){
			// 	$IfMainBuilding = true;
			// }

		}

	}


	// $SougouTenkenKikan = $myBukken->SougouTenkenKikan;

	// $SougouAMKojiTime = $myBukken->SougouAMKojiTime;
	// $SougouPMKojiTime = $myBukken->SougouPMKojiTime;

	// $ExistTenkenKikan = $myBukken->ExistTenkenKikan;

	// $ExistTenkenKikan_Sougou = $myBukken->ExistTenkenKikan_Sougou;
	$wKanriCompanyCD = $myBukken->KanriCompanyCD;

	// //防災情報
	// $BousaiFlg = $myBukken->BousaiFlg;
	// $BousaiTenkenMonth = $myBukken->BousaiTenkenMonth;
	// $BousaiKojiTime = $myBukken->BousaiKojiTime;
	// $GyosyaBousaiCD = $myBukken->GyosyaBousaiCD;
	// $KanriCompanyBousaiCD = $myBukken->KanriCompanyBousaiCD;
	// $WorkPlace = $myBukken->WorkPlace;

	// $BoukaBikou = $myBukken->BoukaBikou;

	//備考欄

	// 機器と総合の資料同一有無
	// $Same_kiki_sougou_flg = $myBukken->Same_kiki_sougou_flg;



	if($IfWorker){
		$wkSenyuDate1 = '';
		if($SenyuStartDate1 != '' || $SenyuEndDate1 != ''){
			$wkSenyuDate1 = $SenyuStartDate1 . ' ～ ' . $SenyuEndDate1;
		}
		$wkHoliday1Items = parseHoliday1($myBukken->Holiday1);
		$wkHoliday1Parts = array();
		foreach ($wkHoliday1Items as $hi) {
			$wkHoliday1Parts[] = formatHolidayDisplay($hi['token']);
		}
		$wkHoliday1 = implode(', ', $wkHoliday1Parts);

		$wkReserveDay1 = SPFWTools::decodePluralValue($myBukken->ReserveDay);
		$wkReserveDay1 = implode(', ', $wkReserveDay1);

		$TotalKosuCount = intval($wKosu);
		$TotalBuildingCount = 1;
		for ($i = 0; $i < $BuildingLoop; $i++) {
			$TotalKosuCount += intval($Kosu[$i]);
			$TotalBuildingCount ++;
		}

	}


	unset($myBukken);
	// ########################################################
	// # 工事日程(機器)
	// ########################################################
	// $myListObject = new SPFWListObject($myDB);

	// $sql = "SELECT ";
	// $sql .= "RoomNumber ";
	// $myListObject->SelectSQL = $sql;
	// $sql = " FROM tKojiNitteiF";
	// $sql .= " WHERE MukouFlg = FALSE";
	// $sql .= " AND BukkenCD = " . $wEditBukkenCD;
	// $sql .= " AND TenkenKind = 1 "; //機器

	// $myListObject->Condition = $sql;
	// $myListObject->Order = "";
	// $myListObject->Limit = "allpage";

	// if (!($myListObject->GetList(1)))
	// 	trigger_error("Getting KojiNittei List Failed.", E_USER_ERROR);
	// $KojiNitteiLoop = $myListObject->Rows;
	// for ($i = 0; $i < $KojiNitteiLoop; $i++) {
	// 	$RoomNumber[$i] = $myListObject->GetValue($i, 0);
	// }
	// if ($RoomNumber) {
	// 	$RoomNumber = str_replace('false', '', $RoomNumber);
	// 	$RoomNumber = implode(', ', $RoomNumber);
	// 	$JsonRoomNumber = json_encode($RoomNumber);
	// }
	// unset($myListObject);

}




// ########################################################
// # 施工業者担当者
// ########################################################

// $myListObject = new SPFWListObject($myDB);

// $sql = "SELECT ";
// $sql .= "gt.UserCD, ";		#0業者担当CD
// $sql .= "gt.LastName, ";	#1業者担当名
// $sql .= "gt.GyosyaCD, ";		#2業者CD
// $sql .= "gt.TEL, ";			#3業者担当TEL
// $sql .= "gt.Address3, ";	#4業者担当携帯
// $sql .= "gt.EMail, ";		#5業者担当メールアドレス
// $sql .= "gt.Address3, ";	#6  2こめのメールアドレス
// $sql .= "gt.Notes, ";		#7備考
// $sql .= "g.GyosyaName, ";	#8業者名
// $sql .= "g.ShozokuCD ";		#9管轄支店　|3|4|5|となっている。
// $myListObject->SelectSQL = $sql;
// $sql = " FROM tUserM gt , tGyosyaM g ";
// $sql .= " WHERE gt.GyosyaCD = g.GyosyaCD AND gt.MukouFlg = FALSE AND UserKbn = 3 "; //

// $myListObject->Condition	= $sql;
// $myListObject->Order 		= "CAST( g.GyosyaNameKana as BINARY ) "; #表示順
// $myListObject->Limit 		= "allpage";

// if (!($myListObject->GetList(1)))
// 	trigger_error("Getting User List Failed.", E_USER_ERROR);

// $GyosyaTantoLoop = $myListObject->Rows;
// for ($i = 0; $i < $GyosyaTantoLoop; $i++) {
// 	$GyosyaTantoCD[$i] 		= $myListObject->GetValue($i, 0);
// 	$GyosyaTantoName[$i]	= $myListObject->GetValue($i, 1);
// 	$GyosyaName[$i] 		= $myListObject->GetValue($i, 8);
// 	$GyosyaKey[$GyosyaTantoCD[$i]] = $i;
// }
// unset($myListObject);

########################################################
# 施工会社情報取得
########################################################

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "GyosyaCD, ";
$sql .= "GyosyaName ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tGyosyaM ";
$sql .= " WHERE MukouFlg = FALSE AND ClientCD = " . $ClientCD;

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$SekoCompanyLoop = $myListObject->Rows;
$GyosyaCompanySelName = '';
for ($i = 0; $i < $SekoCompanyLoop; $i++) {
	$GyosyaCD[$i] 	= $myListObject->GetValue($i, 0);
	$GyosyaName[$i]	= $myListObject->GetValue($i, 1);
	if($wGyosyaCD == $GyosyaCD[$i]){
		$GyosyaSelected[$i] = " selected";
		$GyosyaCompanySelName = $GyosyaName[$i];
	}
}
unset($myListObject);

########################################################
#管理会社情報取得
########################################################

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "KanriCompanyCD, ";
$sql .= "KanriCompanyName ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tKanriCompanyM ";
$sql .= " WHERE MukouFlg = FALSE AND ClientCD = " . $ClientCD;

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$KanriCompanyLoop = $myListObject->Rows;
$KanriCompanySelName = '';
for ($i = 0; $i < $KanriCompanyLoop; $i++) {
	$KanriCompanyCD[$i] 	= $myListObject->GetValue($i, 0);
	$KanriCompanyName[$i]	= $myListObject->GetValue($i, 1);
	if($wKanriCompanyCD == $KanriCompanyCD[$i]){
		$KanriCompanySelected[$i] = " selected";
		$KanriCompanySelName = $KanriCompanyName[$i];
	}
}
unset($myListObject);

########################################################
# 支店・支社情報取得
########################################################

$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "BrancheCD, ";
$sql .= "BrancheName ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tBrancheM ";
$sql .= " WHERE MukouFlg = FALSE";

$myListObject->Condition	= $sql;
$myListObject->Order 		= "";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$BrancheCompanyLoop = $myListObject->Rows;
$BrancheCompanySelName = '';
for ($i = 0; $i < $BrancheCompanyLoop; $i++) {
	$BrancheCD[$i] 	= $myListObject->GetValue($i, 0);
	$BrancheName[$i]	= $myListObject->GetValue($i, 1);
	if($wBrancheCD == $BrancheCD[$i]){
		$BrancheSelected[$i] = " selected";
		$BrancheCompanySelName = $BrancheName[$i];
	}
}
unset($myListObject);

########################################################
# 担当者リスト表示
########################################################
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "UserCD, ";
$sql .= "LastName ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tUserM";
if ($UserKbn != 2) { #ユーザ区分が管理者でなければ　自分の幹事企業のみ表示
	$sql .= " WHERE MukouFlg = FALSE AND UserKbn < 4 and ClientCD = " . $ClientCD;
}
$myListObject->Condition	= $sql;
$myListObject->Order 		= "LastNameKana,LastName ";
$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$TantoLoop = $myListObject->Rows;
$TantoCD1Name = '';
$TantoCD2Name = '';
$TantoCD3Name = '';
$TantoCD4Name = '';
$TantoCD5Name = '';

for ($i = 0; $i < $TantoLoop; $i++) {
	$TantoCD[$i] 	= $myListObject->GetValue($i, 0);
	$TantoName[$i]	= $myListObject->GetValue($i, 1);
	$TantoSoeji[$TantoCD[$i]] = $i;
	if($wTantoCD1 == $TantoCD[$i]){
		$TantoCD1Selected[$i] = " selected";
		$TantoCD1Name = $TantoName[$i];
	}
	if($wTantoCD2 == $TantoCD[$i]){
		$TantoCD2Selected[$i] = " selected";
		$TantoCD2Name = $TantoName[$i];
	}
	if($wTantoCD3 == $TantoCD[$i]){
		$TantoCD3Selected[$i] = " selected";
		$TantoCD3Name = $TantoName[$i];
	}
	// if($wTantoCD4 == $TantoCD[$i])$TantoCD4Selected[$i] = " selected";
	// if($wTantoCD5 == $TantoCD[$i])$TantoCD5Selected[$i] = " selected";
}
unset($myListObject);

########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_kihon_form.tpl";
$myTemplate 	= new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues 	= $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();
unset($myTemplate);
unset($myLog);
	########################################################
	# 関数群
	########################################################