<?php
$isAdminMode = TRUE;
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
	include_once _CLS_DIR . "SPUSUser.cls";
	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSShiryo.cls";
	include_once _CLS_DIR . "SPUSIraiRenkei.cls";
	include_once _CLS_DIR . "SPUSBukkenMatrix.cls";
	include_once _CLS_DIR . "SPUSKoji.cls";
	include_once _CLS_DIR . "SPUSBuilding.cls";
	include_once _CLS_DIR . "SPUSReservationTemp.cls";
	include_once dirname(__DIR__) . "/include/building_period_helpers.php";
include_once dirname(__DIR__) . "/include/holiday_helpers.php";

	function normalizeKoteihyouSlotLabel($label) {
		return normalizeSlotLabelToCurrent($label);
	}

	function normalizeKoteihyouSlotLabels($labels) {
		foreach ($labels as $i => $label) {
			$labels[$i] = normalizeSlotLabelToCurrent($label);
		}
		return $labels;
	}


	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

	########################################################
	# 認証動作
	########################################################
	$rKey = SPFWParameter::getValues('rKey');

	$myUser = new User($myDB);

	if ($rKey == NULL) 
		showSorryPage(_ILLEGAL_ACCESS2);

	if (!$myUser->doAuthenticationByRegistKey($rKey)) 
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) 
		showSorryPage(_ILLEGAL_ACCESS2);

	$UserCD = $myUser->UserCD;
	$ID = $myUser->ID;


	########################################################
	# 設定パラメータ取得取得
	########################################################

	foreach($_POST as $key => $value){
		// echo "<br>key:".$key;
		${"$key"} = SPFWParameter::getValues($key);
		
		//  echo " :".SPFWParameter::getValues($key);
	}

	$act = SPFWParameter::getValues('act');
	$editBukkenCD = SPFWParameter::getValues('editBukkenCD');
	$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
	$myReservationTemp = new ReservationTemp($myDB);
	if($editBuildingCD){
		if (!$myReservationTemp->executeSelect("  BukkenCD = '".$editBukkenCD."' AND BuildingCD = '".$editBuildingCD."'", "")) 
			trigger_error("Getting Temp Reservation Failed.", E_USER_ERROR);
	}else{
		if (!$myReservationTemp->executeSelect("  BukkenCD = '".$editBukkenCD."' AND BuildingCD IS NULL", "")) 
			trigger_error("Getting Temp Reservation Failed.", E_USER_ERROR);
	}
	$temp_show = SPFWParameter::getValues('temp_show');
	if($temp_show == '1' && $act != 'temp_save'){
		$tempReservationInfo = $myReservationTemp->ReservationInfo;
		$tempReservationInfo = html_entity_decode($tempReservationInfo, ENT_QUOTES, 'UTF-8');
		$arrtempReservationInfo = json_decode($tempReservationInfo, true);

		$editBukkenCD = $arrtempReservationInfo['editBukkenCD'] ?? $editBukkenCD;
		$editBuildingCD = $arrtempReservationInfo['editBuildingCD'] ?? $editBuildingCD;
		$wHansu = $arrtempReservationInfo['wHansu'] ?? '';
		$wWakuPattern = $arrtempReservationInfo['wWakuPattern'] ?? '';
		$wFrameOverflow = $arrtempReservationInfo['wFrameOverflow'] ?? '';
		$wFrameOverflow = intval($wFrameOverflow);		

		$wWakuAM = $arrtempReservationInfo['wWakuAM'] ?? '';
		$wWakuAM2 = $arrtempReservationInfo['wWakuAM2'] ?? '';
		$wWakuAM3 = $arrtempReservationInfo['wWakuAM3'] ?? '';
		$wWakuPM = $arrtempReservationInfo['wWakuPM'] ?? '';
		$wWakuPM1 = $arrtempReservationInfo['wWakuPM1'] ?? '';
		$wWakuPM2 = $arrtempReservationInfo['wWakuPM2'] ?? '';
		$wFirstDateFeature = $arrtempReservationInfo['wFirstDateFeature'] ?? '';
		$wKojijun = $arrtempReservationInfo['wKojijun'] ?? '';
		$wWakuAMcol = $arrtempReservationInfo['wWakuAMcol'] ?? '';
		$wWakuAM2col = $arrtempReservationInfo['wWakuAM2col'] ?? '';
		$wWakuAM3col = $arrtempReservationInfo['wWakuAM3col'] ?? '';
		$wWakuPM1col = $arrtempReservationInfo['wWakuPM1col'] ?? '';
		$wWakuPM2col = $arrtempReservationInfo['wWakuPM2col'] ?? '';
		
		$RowsLoop = $arrtempReservationInfo['RowsLoop'] ?? '';
		$wHoliday1 = $arrtempReservationInfo['wHoliday1'] ?? '';
		$wShukujitucolor = $arrtempReservationInfo['wShukujitucolor'] ?? '';
		$wKyukobi = $arrtempReservationInfo['wKyukobi'] ?? '';
		$wKaiRoom3 = $arrtempReservationInfo['wKaiRoom3'] ?? '';
		$wMaxWakuSu = $arrtempReservationInfo['wMaxWakuSu'] ?? '';
		$rowCountforDay = $arrtempReservationInfo['rowCountforDay'] ?? '';
		$wArrangeType = $arrtempReservationInfo['wArrangeType'] ?? '';
		$FloorReserveInfo = $wFloorReserveInfo = $arrtempReservationInfo['FloorReserveInfo'] ?? '';
		$FloorReserveInfo = htmlspecialchars($FloorReserveInfo, ENT_QUOTES, 'UTF-8');

		$wShukujitucolor = SPFWTools::decodePluralValue($wShukujitucolor);
		$wKyukobi = SPFWTools::decodePluralValue($wKyukobi);
		$wKaiRoom3 = SPFWTools::decodePluralValue($wKaiRoom3);

		$wKoteihyouEX = array();
		$wKoteihyouEX_count = $arrtempReservationInfo['wKoteihyouEX_count'] ?? $wKoteihyouEX_count;
		if(empty($wKoteihyouEX_count) || $wKoteihyouEX_count == ''){
			$wKoteihyouEX_count = 0;
		}
		if($wKoteihyouEX_count == 0){
			echo ('<script>
				alert("工程表編集中にエラーが発生いたしました。");
				location.href="../s_menu.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
			</script>');
			exit;		
		}
		for($i=0; $i<$wKoteihyouEX_count; $i++){
			$wKoteihyouEX_temp = $arrtempReservationInfo['wKoteihyouEX_'.$i] ?? '';
			if(empty($wKoteihyouEX_temp) || $wKoteihyouEX_temp == ''){
				echo ('<script>
					alert("工程表編集中にエラーが発生いたしました。");
					location.href="../s_menu.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
				</script>');
				exit;		
			}
			$wKoteihyouEX = array_merge($wKoteihyouEX, SPFWTools::decodePluralValue($wKoteihyouEX_temp));
		}
		$wKoteihyouEX = normalizeKoteihyouSlotLabels($wKoteihyouEX);
	}else{
		$ColsBlock = SPFWTools::decodePluralValue($wColsBlock);
		$RowsLoop = SPFWParameter::getValues('RowsLoop');


		$wKojijun = SPFWParameter::getValues('wKojijun');		# 工事順
		$wFirstDateFeature = SPFWParameter::getValues('wFirstDateFeature');#初日工事数考慮
		$wHoliday1 = SPFWParameter::getValues('wHoliday1');		#休日
		$wHoliday2 = SPFWParameter::getValues('wHoliday2');		
		$wHoliday3 = SPFWParameter::getValues('wHoliday3');
		$wHoliday4 = SPFWParameter::getValues('wHoliday4');
		$wReserveDay = SPFWParameter::getValues('wReserveDay');		#予備日

		$wWakuAMcol = SPFWParameter::getValues('wWakuAMcol');
		$wWakuPM1col = SPFWParameter::getValues('wWakuPM1col');
		$wWakuPM = SPFWParameter::getValues('wWakuPM'); 
		$wHansu = SPFWParameter::getValues('wHansu');
		$wArrangeType = SPFWParameter::getValues('wArrangeType');
		$FloorReserveInfo = $wFloorReserveInfo = SPFWParameter::getValues('FloorReserveInfo');
		$FloorReserveInfo = htmlspecialchars($FloorReserveInfo, ENT_QUOTES, 'UTF-8');
		$wFrameOverflow = SPFWParameter::getValues('wFrameOverflow');
		$wFrameOverflow = intval($wFrameOverflow);
		// $backFrameOverflow = $wFrameOverflow;
		// if($wArrangeType == '1'){
		// 	$wFrameOverflow = 0;
		// }

		// if($wWakuPM){
		// 	$wWakuPM1col = ceil($wWakuPM/$wHansu);
		// }
		
		$wWakuPM2col = SPFWParameter::getValues('wWakuPM2col');
		$holiday = SPFWParameter::getValues('holiday');		#休日
		$wShukujitucolor = SPFWParameter::getValues('wShukujitucolor');		#祝日色
		$wKyukobi = SPFWParameter::getValues('wKyukobi');		#休工日
		$work = SPFWParameter::getValues('work');		#休工日
		$wKaiRoom3 = SPFWParameter::getValues('wKaiRoom3');		#休工日
		$wShukujitucolor = SPFWTools::decodePluralValue($wShukujitucolor);
		$wKyukobi = SPFWTools::decodePluralValue($wKyukobi);
		$wKaiRoom3 = SPFWTools::decodePluralValue($wKaiRoom3);

		$wKoteihyouEX = array();
		$wKoteihyouEX_count = SPFWParameter::getValues('wKoteihyouEX_count');
		if(empty($wKoteihyouEX_count) || $wKoteihyouEX_count == ''){
			$wKoteihyouEX_count = 0;
		}
		if($wKoteihyouEX_count == 0){
			echo ('<script>
				alert("工程表編集中にエラーが発生いたしました。");
				location.href="../s_menu.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
			</script>');
			exit;		
		}
		for($i=0; $i<$wKoteihyouEX_count; $i++){
			$wKoteihyouEX_temp = SPFWParameter::getValues('wKoteihyouEX_'.$i);
			if(empty($wKoteihyouEX_temp) || $wKoteihyouEX_temp == ''){
				echo ('<script>
					alert("工程表編集中にエラーが発生いたしました。");
					location.href="../s_menu.php?rKey='.$rKey.'&editBukkenCD='.$editBukkenCD.'";
				</script>');
				exit;		
			}
			$wKoteihyouEX = array_merge($wKoteihyouEX, SPFWTools::decodePluralValue($wKoteihyouEX_temp));
		}
		$wKoteihyouEX = normalizeKoteihyouSlotLabels($wKoteihyouEX);

	}

	$tempSavedReservationCnt = $myReservationTemp->RecCnt;

	$IfShowConfirmTempShow = false;
	if($temp_show == '1'){
		$IfShowConfirmTempShow = false;
	}else{
		if($myReservationTemp->RecCnt > 0)
			$IfShowConfirmTempShow = true;
	}


	unset($myUser);

	$myBukken = new Bukken($myDB);

	if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "") ){
		$ErrorString = array();
		$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
		showAdminSorryPage($ErrorString);
	}

	$myBuilding = new Building($myDB);
	if($editBuildingCD){
		if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD . " AND MukouFlg = FALSE", "")) {
			$ErrorString = array();
			$ErrorString[] = "tKojiF情報の抽出に失敗しました。";
			showAdminSorryPage($ErrorString);
		}
	}

	// $KyoyoStartDate = $myBukken->KyoyoStartDate;
	// $KyoyoEndDate = $myBukken->KyoyoEndDate;
	$wBuildingName = $myBukken->BuildingName;
	// $SenyuStartDate = $myBukken->SenyuStartDate;
	// $SenyuEndDate = $myBukken->SenyuEndDate;

	$resolvedPeriod = resolveBuildingSenyuAndYoyaku($myBukken, $editBuildingCD ? $myBuilding : null, $editBuildingCD);
	$SenyuStartDate = $resolvedPeriod['SenyuStartDate'];
	$SenyuEndDate = $resolvedPeriod['SenyuEndDate'];
	$YoyakuEndDate = $resolvedPeriod['YoyakuEndDate'];

	$SenyuDateCnt = (( strtotime( $SenyuEndDate ) -  strtotime( $SenyuStartDate )) / 86400) + 1 ;#専有部日数
	// 休工日
	// $Holiday1 = $myBukken->Holiday1;
	if($editBuildingCD){
		$wBuildingName = $myBuilding->BuildingName;
		// $Holiday1 = $myBuilding->Holiday1;
	}

	// 予備日
	// $ReserveDay = $myBukken->ReserveDay;
	// if($editBuildingCD){
	// 	$ReserveDay = $myBuilding->ReserveDay;
	// }


	// 棟名称が空の場合、例外処理
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

	// 棟名称が空の場合、例外処理
function numberToCircled($number) {
	$map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}
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
	if($BuildingLoop > 0)
		$IfBuildingExist = true;
	else
		$IfBuildingExist = false;


	// $wHoliday = SPFWTools::decodePluralValue($Holiday1);
	if($temp_show == '1' && $act != 'temp_save'){
		$wHoliday = $arrtempReservationInfo['wHoliday'] ?? array();
		$wHolidayPeriod = $arrtempReservationInfo['wHolidayPeriod'] ?? array();
	}else{
		$wHoliday 				= SPFWParameter::getValues("wHoliday"); // 配列
		$wHolidayPeriod			= SPFWParameter::getValues("wHolidayPeriod"); // 配列
	}
	$Holiday = combineHolidayInputs($wHoliday, $wHolidayPeriod);
	$wHolidayHTML = '';
	foreach ($Holiday as $token) {
		$wHolidayHTML .= '<input type="hidden" name="wHoliday[]" value="'.htmlspecialchars($token, ENT_QUOTES, 'UTF-8').'">';
	}
	sort($Holiday);

	// $wReserveDay = SPFWTools::decodePluralValue($ReserveDay);
	$beforeReserveDay = [];
	$afterReserveDay = [];

	if($wArrangeType != '1'){	
		if($temp_show == '1' && $act != 'temp_save'){
			$wReserveDay = $arrtempReservationInfo['wReserveDay'] ?? array();
		}else{
			$wReserveDay 				= SPFWParameter::getValues("wReserveDay"); // 配列
		}

		$ReserveDay = array();
		$wReserveDayHTML = '';
		if(is_array($wReserveDay) && count($wReserveDay) > 0){
			for ($i = 0; $i < count($wReserveDay); $i++) {
				if ($wReserveDay[$i]) {
					$ReserveDay[] = $wReserveDay[$i];
					$wReserveDayHTML .= '<input type="hidden" name="wReserveDay[]" value="'.$wReserveDay[$i].'">';
				}
			}
		}
		sort($ReserveDay);
		foreach($ReserveDay as $aReserveDay){
			$dateReserveDay = new DateTime($aReserveDay);
			$dateSenyuStartDate = new DateTime($SenyuStartDate);
			$dateSenyuEndDate = new DateTime($SenyuEndDate);

			if($dateReserveDay < $dateSenyuStartDate){
				array_push($beforeReserveDay, $aReserveDay);
			}else if($dateReserveDay > $dateSenyuEndDate){
				array_push($afterReserveDay, $aReserveDay);
			}
		}
	}

	// 一時保存
	if($act == 'temp_save'){
		for ($i = 0; $i < count($WAKUPATTERN[$wWakuPattern]['AMPM']); $i++) {
			if ($i != 0)
				$MaxWakuSu .= "-";

			$MaxWakuSu .= ${'wWaku' . $WAKUPATTERN[$wWakuPattern]['AMPM'][$i]};
		}
		if($editBuildingCD){
			$myBuilding->Holiday1 = encodeHoliday1($Holiday); #パイプつなぎ
			$myBuilding->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ
			$myBuilding->Hansu = $wHansu;
			$myBuilding->WakuPattern = $wWakuPattern;
			$myBuilding->Kojijun = $wKojijun;
			$myBuilding->FirstDateFeature = $wFirstDateFeature;
			$myBuilding->MaxWakuSu = $MaxWakuSu;
			$myBuilding->FrameOverflow = $wFrameOverflow;
			$myBuilding->ArrangeType = $wArrangeType;
			$myBuilding->FloorReserveInfo = $wFloorReserveInfo;

			if (!$myBuilding->executeUpdate()) {
				trigger_error("executeUpdate(myBuilding) Failed.", E_USER_ERROR);
			}
		}else{
			$myBukken->Hansu = $wHansu;
			$myBukken->WakuPattern = $wWakuPattern;
			$myBukken->Kojijun = $wKojijun;
			$myBukken->FirstDateFeature = $wFirstDateFeature;
			$myBukken->MaxWakuSu = $MaxWakuSu;
			$myBukken->FrameOverflow = $wFrameOverflow;
			$myBukken->ArrangeType = $wArrangeType;
			$myBukken->FloorReserveInfo = $wFloorReserveInfo;


			$myBukken->Holiday1 = encodeHoliday1($Holiday); #パイプつなぎ
			$myBukken->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ

			if (!$myBukken->executeUpdate()) {
				trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
			}
		}

		$myReservationTemp->ClientCD = $myUser->ClientCD;
		$myReservationTemp->UserCD = $myUser->UserCD;
		$myReservationTemp->BukkenCD = $editBukkenCD;
		$myReservationTemp->BuildingCD = $editBuildingCD;
		$myReservationTemp->ReservationInfo = json_encode($_POST);
		$myReservationTemp->Creator = $MyUserCD;
		$myReservationTemp->Updater = $MyUserCD;
		if (!$myReservationTemp->executeUpdate()) {
			$ErrorString = [];
			$ErrorString[] = '予約情報一時保存時にエラーがおこりました。';
			showAdminSorryPage($ErrorString);
		}
		$tempSavedReservationCnt = 1;
		$temp_show = '1';
	}

	$beforeReserveDayDateCnt = count($beforeReserveDay);
	$afterReserveDayDateCnt = count($afterReserveDay);

	$rowCountforDay = $wHansu;

	$wWakuAMcol=ceil(($wWakuAM)/$wHansu)+$wFrameOverflow;#入力枠数を班数でわった切り上げた数
	$tempRowCountforDay = ceil(($wWakuAM + $wFrameOverflow*$wHansu) / 5);
	if($tempRowCountforDay > $rowCountforDay)
		$rowCountforDay = $tempRowCountforDay;	

	// echo "<br> ".__LINE__." ここまでOK :".$wWakuPM1."/".$wHansu;
	if($wWakuPattern > 2) {
		$wWakuPM1col=ceil(($wWakuPM1)/$wHansu)+$wFrameOverflow;
		$wWakuPM2col=ceil(($wWakuPM2)/$wHansu)+$wFrameOverflow;

		$tempRowCountforDay = ceil(($wWakuPM1+$wFrameOverflow*$wHansu) / 5);
		if($tempRowCountforDay > $rowCountforDay)
			$rowCountforDay = $tempRowCountforDay;	
	
		$tempRowCountforDay = ceil(($wWakuPM2+$wFrameOverflow*$wHansu) / 5);
		if($tempRowCountforDay > $rowCountforDay)
			$rowCountforDay = $tempRowCountforDay;
	}else{//2枠のパターンの時
		// $wWakuPM1col=ceil(($wWakuPM1)/$wHansu);
		$wWakuPM1col=ceil(($wWakuPM)/$wHansu)+$wFrameOverflow;

		$tempRowCountforDay = ceil(($wWakuPM+$wFrameOverflow*$wHansu) / 5);
		if($tempRowCountforDay > $rowCountforDay)
			$rowCountforDay = $tempRowCountforDay;
	}
	$rowCountforDay = ceil($rowCountforDay / $wHansu) * $wHansu;

	########################################################
	# 詳細工程表イメージ作成
	########################################################

	$tempRowCountforDay = ceil(($wWakuAM+$wFrameOverflow*$wHansu) / 5);
	if($tempRowCountforDay > $wHansu){
		$wWakuAMcol = 5;
	}else{
		$wWakuAMcol = ceil($wWakuAM / $rowCountforDay)+$wFrameOverflow;
	}

	if($wWakuPattern > 2) {
		$tempRowCountforDay = ceil(($wWakuPM1+$wFrameOverflow*$wHansu) / 5);
		if($tempRowCountforDay > $wHansu){
			$wWakuPM1col = 5;
		}else{
			$wWakuPM1col = ceil($wWakuPM1 / $rowCountforDay)+$wFrameOverflow;
		}
	
		$tempRowCountforDay = ceil(($wWakuPM2+$wFrameOverflow*$wHansu) / 5);
		if($tempRowCountforDay > $wHansu){
			$wWakuPM2col = 5;
		}else{
			$wWakuPM2col = ceil($wWakuPM2 / $rowCountforDay)+$wFrameOverflow;
		}

	}else{//2枠のパターンの時
		// $wWakuPM1col=ceil(($wWakuPM1)/$rowCountforDay);
		$tempRowCountforDay = ceil(($wWakuPM + $wFrameOverflow*$wHansu) / 5);
		if($tempRowCountforDay > $wHansu){
			$wWakuPM1col = 5;
		}else{
			$wWakuPM1col = ceil($wWakuPM / $rowCountforDay)+$wFrameOverflow;
		}
	}

	########################################################
	# 半日休工（午前/午後）の枠を「休工」に補正する
	# 工程表案が古い（休工日の登録前に作成・一時保存された）場合、休工の枠が
	# 「空き」「枠越」のまま残り、編集画面で日程を割り当てられてしまうため。
	# セルの並び順は下の「詳細工程表イメージ作成」と同一。全日休工日はセルを消費しない。
	########################################################
	$HolidayWakuNames = isset($WAKUPATTERN[$wWakuPattern]['AMPM']) ? $WAKUPATTERN[$wWakuPattern]['AMPM'] : array('AM', 'PM');
	$HolidayBlankLabels = slotBlankLabels();
	$HolidayWakuCols = array($wWakuAMcol, $wWakuPM1col);
	if($wWakuPattern > 2)
		$HolidayWakuCols[] = $wWakuPM2col;

	$HolidayFixDates = array();
	foreach($beforeReserveDay as $aReserveDay){
		$HolidayFixDates[] = date('Y-m-d', strtotime($aReserveDay));
	}
	$HolidayFixDate = new DateTime($SenyuStartDate);
	for($i=0; $i<$SenyuDateCnt; $i++){
		$HolidayFixDates[] = $HolidayFixDate->format('Y-m-d');
		$HolidayFixDate->modify('+1 days');
	}
	foreach($afterReserveDay as $aReserveDay){
		$HolidayFixDates[] = date('Y-m-d', strtotime($aReserveDay));
	}

	$HolidayFixIndex = 0;
	foreach($HolidayFixDates as $aHolidayFixDate){
		if(isFullDayHoliday($Holiday, $aHolidayFixDate))
			continue;#全日休工日は1行だけ表示するのでセルを持たない

		for($j=0; $j<$rowCountforDay; $j++){
			foreach($HolidayWakuCols as $aWakuIdx => $aWakuCols){
				$aWakuName = isset($HolidayWakuNames[$aWakuIdx]) ? $HolidayWakuNames[$aWakuIdx] : '';
				$IsHolidayWaku = ($aWakuName !== '' && isSlotHoliday($Holiday, $aHolidayFixDate, $aWakuName));
				for($k=0; $k<$aWakuCols; $k++){
					if($IsHolidayWaku){
						$aCurLabel = isset($wKoteihyouEX[$HolidayFixIndex]) ? $wKoteihyouEX[$HolidayFixIndex] : '';
						#部屋番号が入っている枠は消さずに残す（工程表案の作り直しで対応してもらう）
						if(in_array($aCurLabel, $HolidayBlankLabels, true))
							$wKoteihyouEX[$HolidayFixIndex] = '休工';
					}
					$HolidayFixIndex ++;
				}
			}
		}
	}

	$i = 0;
	$disWakuName = 'AM';
	$WakuPatternNames = $WAKUPATTERN[$wWakuPattern]['Name'];
	if (preg_match('/\((.*?)\)/', $WakuPatternNames, $matches)) {
		$WakuPatternNamesStr = $matches[1];
		$WakuPatternNamesArr = explode(",", $WakuPatternNamesStr);
		if(isset($WakuPatternNamesArr[$i]) && $WakuPatternNamesArr[$i] != '')
			$disWakuName = trim($WakuPatternNamesArr[$i]);
	}

	$Koteihyou="<table border='1' width='600px' cellspacing='5' style='text-align:center' cellpadding='7' class='ex_table'><tr><td width='130px'>日程</td><td class='ex_table2' width='60px'>曜日</td><td width='50px'>班</td><td colspan = ".$wWakuAMcol." style='background-color:#ff7f50;' class='ex_table2'>".$disWakuName."</td>";
	if($wWakuPattern <= 2){
		$i = 1;
		$disWakuName = 'PM';
		$WakuPatternNames = $WAKUPATTERN[$wWakuPattern]['Name'];
		if (preg_match('/\((.*?)\)/', $WakuPatternNames, $matches)) {
			$WakuPatternNamesStr = $matches[1];
			$WakuPatternNamesArr = explode(",", $WakuPatternNamesStr);
			if(isset($WakuPatternNamesArr[$i]) && $WakuPatternNamesArr[$i] != '')
				$disWakuName = trim($WakuPatternNamesArr[$i]);
		}

		$Koteihyou .="<td colspan = ".$wWakuPM1col." style='background-color:#87cefa'  class='ex_table2'>".$disWakuName."</td></tr>";
		$wWakuPM2col = 0;
	}else{
		$i = 1;
		$disWakuName1 = 'PM';
		$WakuPatternNames = $WAKUPATTERN[$wWakuPattern]['Name'];
		if (preg_match('/\((.*?)\)/', $WakuPatternNames, $matches)) {
			$WakuPatternNamesStr = $matches[1];
			$WakuPatternNamesArr = explode(",", $WakuPatternNamesStr);
			if(isset($WakuPatternNamesArr[$i]) && $WakuPatternNamesArr[$i] != '')
				$disWakuName1 = trim($WakuPatternNamesArr[$i]);
		}

		$i = 2;
		$disWakuName2 = 'PM';
		$WakuPatternNames = $WAKUPATTERN[$wWakuPattern]['Name'];
		if (preg_match('/\((.*?)\)/', $WakuPatternNames, $matches)) {
			$WakuPatternNamesStr = $matches[1];
			$WakuPatternNamesArr = explode(",", $WakuPatternNamesStr);
			if(isset($WakuPatternNamesArr[$i]) && $WakuPatternNamesArr[$i] != '')
				$disWakuName2 = trim($WakuPatternNamesArr[$i]);
		}

		$Koteihyou .="<td colspan = ".$wWakuPM1col." style='background-color:#87cefa'  class='ex_table2'>".$disWakuName1."</td><td colspan = ".$wWakuPM2col." style='background-color:#99FF66;' class='ex_table2'>".$disWakuName2."</td></tr>";
	}


	$week = ['日','月','火','水','木','金','土'];
	$m = 0 ;

	// 予備日
	foreach($beforeReserveDay as $key => $aReserveDay){
		$date = new DateTime($aReserveDay);
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate,$SHUKUJITULIST);
		$YoubiCD =  $date->format('w') ;
		$Youbi = $week[$YoubiCD];
		$week_str = $Youbi;
		if($week_str == '土')
			$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
		else if($week_str == '日')
			$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

		#★１休工日なら
		$KojiHoliday_ = false;
		if (count($Holiday) > 0) { //休工日
			$KojiHoliday_ = isFullDayHoliday($Holiday, $SenyuDate);
		}
	
		if($KojiHoliday_){
			$Koteihyou .="<tr class='trreserveday'><td rowspan=1 class='ex_table2'>".$SenyuDate."<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .="<td rowspan=1 class='ex_table2'>".$week_str."</td>";
			$Koteihyou .="<td rowspan=1 class='ex_table2'></td>";
			$Koteihyou .="<td colspan=";
			$Koteihyou .=$wWakuAMcol+$wWakuPM1col+$wWakuPM2col;#うまくつかって上部をつくる　あとで♪
			$Koteihyou .=" rowspan=1 style='text-align:center;' class='ex_table2'> ";
			$Koteihyou .="休工日";
			$Koteihyou .="</td></tr>";

		#★１休工日でない場合
		}else{
			$Koteihyou .="<tr class='trtop trreserveday'><td rowspan=".$rowCountforDay." class='ex_table2'>".$SenyuDate."<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .="<td rowspan=".$rowCountforDay." class='ex_table2'>".$week_str."</td>";
			
			#★２班数分くりかえす
			$banNo = 1;
			$curHansu = $banNo;
			$banRowspan = floor($rowCountforDay / $wHansu);			
			for($j=0 ; $j < $rowCountforDay ;$j++ ){
				if($j!==0)#班が１つめなら<tr>つける
					$Koteihyou .="<tr class='trreserveday'>";
				if($j % $banRowspan == 0){
					$Koteihyou .="<td rowspan=".$banRowspan." class='ex_table2'>".$banNo."</td>";
					$curHansu = $banNo;
					$banNo ++;
				}

				#★３AM枠数の班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuAMcol ;$k++ ){
					if(isSlotLabelAki($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5; color:#1f1f1f;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#ffefd5;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else{
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}
					$KoteihyouEX[] = $wKoteihyouEX[$m];
					$HansuEX[] = $curHansu;
					$m++;
				}#★３AM枠数を班数で割った数分繰り返すEnd

				#★３-２PM１枠数を班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuPM1col ;$k++ ){
					if(isSlotLabelAki($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d2e5ff;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden'  id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d2e5ff; color:#1f1f1f;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden'  id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#d2e5ff;'>";
						$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else{
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}
					$KoteihyouEX[] = $wKoteihyouEX[$m];
					$HansuEX[] = $curHansu;
					$m++;
				}
				#★３-２PM１枠数を班数で割った数分繰り返す　End

				#★３-３PM２枠数を班数で割った数分繰り返す
				if($wWakuPattern > 2){
					for($k=0 ; $k < $wWakuPM2col ;$k++){
						if(isSlotLabelAki($wKoteihyouEX[$m])){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d1f9b7'>";
							$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5; color:#1f1f1f;'>";
							$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#d1f9b7'>";
							$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else{
							$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3'>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}
						$KoteihyouEX[] = $wKoteihyouEX[$m];
						$HansuEX[] = $curHansu;
						$m++;
					}
				}#★３-３PM２枠数を班数で割った数分繰り返す

				if($j!==0)
					$Koteihyou .="</tr>";
			}#★２班数分繰り返すEnd

		}
		$Koteihyou .="</tr>";
	}

	$date = new DateTime($SenyuStartDate);
	for($i=0 ; $i< $SenyuDateCnt ; $i++){
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate,$SHUKUJITULIST);
		$YoubiCD =  $date->format('w') ;
		$Youbi = $week[$YoubiCD];
		$week_str = $Youbi;
		if($week_str == '土')
			$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
		else if($week_str == '日')
			$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';


		if($result!==false || $YoubiCD == 0 || $YoubiCD == 6){
			$holidayCD = $i;
		}
		#★１休工日なら
		$KojiHoliday[$i] = false;
		if (count($Holiday) > 0) { //休工日
			$KojiHoliday[$i] = isFullDayHoliday($Holiday, $SenyuDate);
		}
	
		if($KojiHoliday[$i]){
			$Koteihyou .="<tr class='trholiday'><td rowspan=1".$wShukujitucolor[$i]." class='ex_table2'>".$SenyuDate."</td>";
			$Koteihyou .="<td rowspan=1".$wShukujitucolor[$i]." class='ex_table2'>".$week_str."</td>";
			$Koteihyou .="<td rowspan=1".$wShukujitucolor[$i]." class='ex_table2'></td>";
			$Koteihyou .="<td colspan=";
			$Koteihyou .=$wWakuAMcol+$wWakuPM1col+$wWakuPM2col;#うまくつかって上部をつくる　あとで♪
			$Koteihyou .=" rowspan=1 style='text-align:center;' class='ex_table2'> ";
			$Koteihyou .="休工日";
			$Koteihyou .="</td></tr>";

		#★１休工日でない場合
		}else{
			$Koteihyou .="<tr class='trtop'><td rowspan=".$rowCountforDay.$wShukujitucolor[$i]." class='ex_table2'>".$SenyuDate."</td>";
			$Koteihyou .="<td rowspan=".$rowCountforDay.$wShukujitucolor[$i]." class='ex_table2'>".$week_str."</td>";
			
			#★２班数分くりかえす
			$banNo = 1;
			$curHansu = $banNo;
			$banRowspan = floor($rowCountforDay / $wHansu);			
			for($j=0 ; $j < $rowCountforDay ;$j++ ){
				if($j!==0)#班が１つめなら<tr>つける
					$Koteihyou .="<tr>";
				if($j % $banRowspan == 0){
					$Koteihyou .="<td rowspan=".$banRowspan." class='ex_table2'>".$banNo."</td>";
					$curHansu = $banNo;
					$banNo ++;
				}

				#★３AM枠数の班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuAMcol ;$k++ ){
					if(isSlotLabelAki($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5;'>";
						$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5; color:#1f1f1f;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#ffefd5;'>";
						$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else{
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3'>";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}
					$KoteihyouEX[] = $wKoteihyouEX[$m];
					$HansuEX[] = $curHansu;
					$m++;
				}#★３AM枠数を班数で割った数分繰り返すEnd

				#★３-２PM１枠数を班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuPM1col ;$k++ ){
					if(isSlotLabelAki($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d2e5ff;'>";
						$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden'  id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d2e5ff; color:#1f1f1f;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden'  id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#d2e5ff;'>";
						$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else{
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}
					$KoteihyouEX[] = $wKoteihyouEX[$m];
					$HansuEX[] = $curHansu;
					$m++;
				}
				#★３-２PM１枠数を班数で割った数分繰り返す　End

				#★３-３PM２枠数を班数で割った数分繰り返す
				if($wWakuPattern > 2){
					for($k=0 ; $k < $wWakuPM2col ;$k++){
						if(isSlotLabelAki($wKoteihyouEX[$m])){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d1f9b7'>";
							$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5; color:#1f1f1f;'>";
							$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#d1f9b7'>";
							$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else{
							$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3'>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}

						$KoteihyouEX[] = $wKoteihyouEX[$m];
						$HansuEX[] = $curHansu;
						$m++;
					}
				}#★３-３PM２枠数を班数で割った数分繰り返す

				if($j!==0)
					$Koteihyou .="</tr>";
			}#★２班数分繰り返すEnd

		}
		$date->modify('+1 days');
		$Koteihyou .="</tr>";
	}

	// 予備日
	foreach($afterReserveDay as $key => $aReserveDay){
		$date = new DateTime($aReserveDay);
		$SenyuDate = $date->format('Y-m-d');
		$result = array_search($SenyuDate,$SHUKUJITULIST);
		$YoubiCD =  $date->format('w') ;
		$Youbi = $week[$YoubiCD];
		$week_str = $Youbi;
		if($week_str == '土')
			$week_str = '<span style="color:#0070c0">'.$week_str.'</span>';
		else if($week_str == '日')
			$week_str = '<span style="color:#ff9999">'.$week_str.'</span>';

		#★１休工日なら
		$KojiHoliday_ = false;
		if (count($Holiday) > 0) { //休工日
			$KojiHoliday_ = isFullDayHoliday($Holiday, $SenyuDate);
		}
	
		if($KojiHoliday_){
			$Koteihyou .="<tr class='trreserveday'><td rowspan=1 class='ex_table2'>".$SenyuDate."<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .="<td rowspan=1 class='ex_table2'>".$week_str."</td>";
			$Koteihyou .="<td rowspan=1 class='ex_table2'></td>";
			$Koteihyou .="<td colspan=";
			$Koteihyou .=$wWakuAMcol+$wWakuPM1col+$wWakuPM2col;#うまくつかって上部をつくる　あとで♪
			$Koteihyou .=" rowspan=1 style='text-align:center;' class='ex_table2'> ";
			$Koteihyou .="休工日";
			$Koteihyou .="</td></tr>";

		#★１休工日でない場合
		}else{
			$Koteihyou .="<tr class='trtop trreserveday'><td rowspan=".$rowCountforDay." class='ex_table2'>".$SenyuDate."<div class='reserve_day'>予備日</div></td>";
			$Koteihyou .="<td rowspan=".$rowCountforDay." class='ex_table2'>".$week_str."</td>";
			
			#★２班数分くりかえす
			$banNo = 1;
			$curHansu = $banNo;
			$banRowspan = floor($rowCountforDay / $wHansu);			
			for($j=0 ; $j < $rowCountforDay ;$j++ ){
				if($j!==0)#班が１つめなら<tr>つける
					$Koteihyou .="<tr class='trreserveday'>";
				if($j % $banRowspan == 0){
					$Koteihyou .="<td rowspan=".$banRowspan." class='ex_table2'>".$banNo."</td>";
					$curHansu = $banNo;
					$banNo ++;
				}

				#★３AM枠数の班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuAMcol ;$k++ ){
					if(isSlotLabelAki($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5; color:#1f1f1f;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#ffefd5;'>";
						$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else{
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3'>";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}
					$KoteihyouEX[] = $wKoteihyouEX[$m];
					$HansuEX[] = $curHansu;
					$m++;
				}#★３AM枠数を班数で割った数分繰り返すEnd

				#★３-２PM１枠数を班数で割った数分繰り返す
				for($k=0 ; $k < $wWakuPM1col ;$k++ ){
					if(isSlotLabelAki($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d2e5ff;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden'  id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d2e5ff; color:#1f1f1f;'>";
						$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden'  id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
						$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#d2e5ff;'>";
						$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else{
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3'>";
						$Koteihyou .= "<input type='hidden' id='m".$m."'  name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}
					$KoteihyouEX[] = $wKoteihyouEX[$m];
					$HansuEX[] = $curHansu;
					$m++;
				}
				#★３-２PM１枠数を班数で割った数分繰り返す　End

				#★３-３PM２枠数を班数で割った数分繰り返す
				if($wWakuPattern > 2){
					for($k=0 ; $k < $wWakuPM2col ;$k++){
						if(isSlotLabelAki($wKoteihyouEX[$m])){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#d1f9b7'>";
							$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else if(isSlotLabelWakuover($wKoteihyouEX[$m])){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell_blank' style='background-color:#ffefd5; color:#1f1f1f;'>";
							$Koteihyou .= "<a href='#'>".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else if($wKoteihyouEX[$m]=="休工"){
						$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3;'>";
						$Koteihyou .= "休工";
						$Koteihyou .= "<input type='hidden' id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
						$Koteihyou .="</td>";
					}else if($wKoteihyouEX[$m] != ''){
							$Koteihyou .="<td id='link_cell_".$m."' class='link_cell' style='background-color:#d1f9b7'>";
							$Koteihyou .= "<a href='#' >".$wKoteihyouEX[$m]."</a>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}else{
							$Koteihyou .="<td id='link_cell_".$m."' style='background-color:#d3d3d3'>";
							$Koteihyou .= "<input type='hidden'  id='m".$m."' name='wwKoteihyouEX[]' value=".$wKoteihyouEX[$m]." >";
							$Koteihyou .="</td>";
						}
						$KoteihyouEX[] = $wKoteihyouEX[$m];
						$HansuEX[] = $curHansu;
						$m++;
					}
				}#★３-３PM２枠数を班数で割った数分繰り返す

				if($j!==0)
					$Koteihyou .="</tr>";
			}#★２班数分繰り返すEnd

		}
		$Koteihyou .="</tr>";
	}

	$Koteihyou .= "</table>";
	for($i=0;$i<count($wKaiRoom3);$i++){
		if(array_search($wKaiRoom3[$i] ,$wKoteihyouEX )===false){
			if($ShortageRoom==""){
				$ShortageRoom = '<span class="missing_cell"><a href="#">'.$wKaiRoom3[$i].'</a><input type="hidden" name="wwKoteihyouEX[]" value="'.$wKaiRoom3[$i].'"></span>';
			}else{
				$ShortageRoom .= '<span class="missing_cell"><a href="#">'.$wKaiRoom3[$i].'</a><input type="hidden" name="wwKoteihyouEX[]" value="'.$wKaiRoom3[$i].'"></span>';
			}
		}
	}
	if(!$ShortageRoom){
		$IfShortage=FALSE;
		$IfNotShortage=TRUE;
		$SakuseiDisabled="";
	}else{
		$IfShortage=TRUE;
		$IfNotShortage=FALSE;
		$SakuseiDisabled='disabled';
	}
	$wShukujitucolor = SPFWTools::encodePluralValue($wShukujitucolor);
	$wKaiRoom3 = SPFWTools::encodePluralValue($wKaiRoom3);
	$wKyukobi = SPFWTools::encodePluralValue($wKyukobi);


	$myListObject = new SPFWListObject($myDB);
	$myListObject->SelectSQL = 'SELECT ID';
	$sql  = " FROM tReservationF WHERE MukouFlg = FALSE AND BukkenCD = ".$editBukkenCD;
	if($editBuildingCD){
		$sql .= " AND BuildingCD = " . $editBuildingCD;
	}else{
		$sql .= " AND BuildingCD IS NULL ";
	}
	$myListObject->Condition = $sql;
	$myListObject->Order = "ID";
	$myListObject->Limit = "allpage";
	if (!($myListObject->GetList(1)))
		trigger_error("Getting Reservation List Failed.", E_USER_ERROR);
	$ReservationCount = $myListObject->Rows;

	$wKoteihyouEX = SPFWTools::encodePluralValue($KoteihyouEX);
	$wHansuEXHTML = "";
	$i = 0;
	foreach (array_chunk($HansuEX, 300) as $HansuEXChunk) {
		$wHansuEXHTML .= '<input type="hidden" name="wHansuEX_'.$i.'" value="' . SPFWTools::encodePluralValue($HansuEXChunk) . '">';
		$i++;
	}
	$wHansuEXHTML .= '<input type="hidden" name="wHansuEX_count" value="' . $i . '">';
	

	// $wFrameOverflow = $backFrameOverflow;

	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_make_kanryo_hensyu.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
?>
