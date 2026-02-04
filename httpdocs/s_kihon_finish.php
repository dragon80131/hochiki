<?php
$isAdminMode = TRUE;

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
include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPUSKojiNittei.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);
########################################################
# 認証動作
########################################################
$rKey  = SPFWParameter::getValues("rKey");

$myUser = new User($myDB);

if ($rKey == NULL)
	showSorryPage(_ILLEGAL_ACCESS2);

if (!$myUser->doAuthenticationByRegistKey($rKey))
	trigger_error("doAuthentication Failed.", E_USER_ERROR);

if ($myUser->UserCD == -1)
	showSorryPage(_ILLEGAL_ACCESS2);

$UserCD 		= $myUser->UserCD;
$ID 			= $myUser->ID;
$UserKbn = $myUser->UserKbn;
$ClientCD = $myUser->ClientCD;

unset($myUser);
########################################################
# 値取得
########################################################
$editBukkenCD  	= SPFWParameter::getValues("editBukkenCD");
$work  			= SPFWParameter::getValues("work");
########################################################
# 登録処理
########################################################

if ($work == 1) { #新規、修正
	// １．物件基本情報
	$wKenmeiNo 				= SPFWParameter::getValues("wKenmeiNo");
	$wBukkenName 			= SPFWParameter::getValues("wBukkenName");
	$wBukkenNameKana 			= SPFWParameter::getValues("wBukkenNameKana");
	$wSagyoName	= SPFWParameter::getValues("wSagyoName");
	$wAddress 				= SPFWParameter::getValues("wAddress");
	$wBukkenMemo 				= SPFWParameter::getValues("wBukkenMemo");
	$wKojiName 				= SPFWParameter::getValues("wKojiName");
	$wKosu 					= SPFWParameter::getValues("wKosu");
	$wBuilding 					= SPFWParameter::getValues("wBuilding");
	$wKaidaka 				= SPFWParameter::getValues("wKaidaka");

	$wBukkenMemo 			= SPFWParameter::getValues("wBukkenMemo");
	// $KikiTenkenMonth = SPFWParameter::getValues("KikiTenkenMonth");
	// $SougouTenkenMonth = SPFWParameter::getValues("SougouTenkenMonth");
	// $KikiTenkenKikan = SPFWParameter::getValues("KikiTenkenKikan");
	// $kobetsuAM = SPFWParameter::getValues("kobetsuAM");
	// $kobetsuPM = SPFWParameter::getValues("kobetsuPM");
	// $kobetsu = SPFWParameter::getValues("kobetsu");
	// $kobetsu_Sougou = SPFWParameter::getValues("kobetsu_Sougou");
	// $KikiAMKojiTime = SPFWParameter::getValues("KikiAMKojiTime");
	//$KikiPMKojiTime = SPFWParameter::getValues("KikiPMKojiTime");
	// $SougouAMKojiTime = SPFWParameter::getValues("SougouAMKojiTime");
	// $SougouPMKojiTime = SPFWParameter::getValues("SougouPMKojiTime");
	// $SougouTenkenKikan = SPFWParameter::getValues("SougouTenkenKikan");
	// $ExistTenkenKikan = SPFWParameter::getValues("ExistTenkenKikan");
	// $ExistTenkenKikan_Sougou = SPFWParameter::getValues("ExistTenkenKikan_Sougou");
	$GyosyaCompany = SPFWParameter::getValues("GyosyaCompany");
	$TantoCD1 = SPFWParameter::getValues("TantoCD1");
	$TantoCD2 = SPFWParameter::getValues("TantoCD2");
	$TantoCD3 = SPFWParameter::getValues("TantoCD3");
	$TantoCD4 = SPFWParameter::getValues("TantoCD4");
	$TantoCD5 = SPFWParameter::getValues("TantoCD5");
	$KanriCompany = SPFWParameter::getValues("KanriCompany");
	$BrancheCompany = SPFWParameter::getValues("BrancheCompany");

	// $TatoFlg = SPFWParameter::getValues("TatoFlg");

	// $Same_kiki_sougou_flg = SPFWParameter::getValues("Same_kiki_sougou_flg");

	// //防災情報
	// $BousaiFlg = SPFWParameter::getValues("BousaiFlg");
	// $BousaiTenkenMonth = SPFWParameter::getValues("BousaiTenkenMonth");
	// $BousaiKojiTime = SPFWParameter::getValues("BousaiKojiTime");
	// $GyosyaBousaiCD = SPFWParameter::getValues("GyosyaBousaiCD");
	// $KanriCompanyBousaiCD = SPFWParameter::getValues("KanriCompanyBousaiCD");
	// $WorkPlace = SPFWParameter::getValues("WorkPlace");
	// $BoukaBikou = SPFWParameter::getValues("BoukaBikou");

	//備考欄
	$Biko = SPFWParameter::getValues("Biko");
	// $WakuPattern = SPFWParameter::getValues("WakuPattern");
	$MinuteTime = SPFWParameter::getValues("MinuteTime");
	$SenyuStartDate = SPFWParameter::getValues("SenyuStartDate");
	$SenyuEndDate = SPFWParameter::getValues("SenyuEndDate");
	$KyoyobuStartDate = SPFWParameter::getValues("KyoyobuStartDate");
	$KyoyobuEndDate = SPFWParameter::getValues("KyoyobuEndDate");
	$SenyubuStartDate = SPFWParameter::getValues("SenyubuStartDate");
	$SenyubuEndDate = SPFWParameter::getValues("SenyubuEndDate");
	$SenyuStartDate1 = SPFWParameter::getValues("SenyuStartDate1");
	$SenyuEndDate1 = SPFWParameter::getValues("SenyuEndDate1");
	$YoyakuEndDate 			= SPFWParameter::getValues("YoyakuEndDate");
	$wHoliday 				= SPFWParameter::getValues("wHoliday"); // 配列
	$wReserveDay 				= SPFWParameter::getValues("wReserveDay"); // 配列
	$newBuilding 			= SPFWParameter::getValues("newBuilding"); // 配列
	$newKosu 				= SPFWParameter::getValues("newKosu"); // 配列
	$newKaidaka 			= SPFWParameter::getValues("newKaidaka"); // 配列
	$newSenyuStartDate 		= SPFWParameter::getValues("newSenyuStartDate"); // 配列
	$newSenyuEndDate 		= SPFWParameter::getValues("newSenyuEndDate"); // 配列
	$newBuilingNo 			= SPFWParameter::getValues("newBuilingNo"); // 配列

	########################################################
	# 物件情報（編集のみ）
	########################################################
	$myBukken = new Bukken($myDB);

	if ($editBukkenCD > 0) {
		if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD . " AND MukouFlg = FALSE", "") || $myBukken->RecCnt != 1) {
			trigger_error("Getting Bukken Failed.", E_USER_ERROR);
		}
	} else {
		$editBukkenCD = -1;
	}

	$wBukkenName = replaceStr($wBukkenName); // 環境依存文字自動変換
	$myBukken->KenmeiNo 			= trim($wKenmeiNo);
	$myBukken->BukkenName 			= $wBukkenName;
	$myBukken->BukkenNameKana 			= $wBukkenNameKana;
	$myBukken->SagyoName	= $wSagyoName;
	$myBukken->Address 				= $wAddress;
	$myBukken->BuildingName 				= $wBuilding;
	$myBukken->Kosu 				= mb_convert_kana($wKosu, "n"); #戸数　半角数字に
	$myBukken->Kaidaka 			= mb_convert_kana( $wKaidaka , "n" ) ;#階高　半角数字に
	$myBukken->KanriGaisya 			= $wKanriGaisya;
	$myBukken->BukkenMemo 			= $wBukkenMemo;
	// $myBukken->KikiTenkenMonth = $KikiTenkenMonth;
	// $myBukken->SougouTenkenMonth = $SougouTenkenMonth;
	// $myBukken->KikiTenkenKikan = $KikiTenkenKikan;
	// $myBukken->SougouTenkenKikan = $SougouTenkenKikan;
	//$myBukken->KikiAMKojiTime = $KikiAMKojiTime;
	// $myBukken->WakuPattern = $WakuPattern;
	//$myBukken->KikiPMKojiTime = $KikiPMKojiTime;
	// $myBukken->KikiTenkenKikan = $KikiTenkenKikan;
	// $myBukken->SougouAMKojiTime = $SougouAMKojiTime;
	// $myBukken->SougouPMKojiTime = $SougouPMKojiTime;
	// $myBukken->ExistTenkenKikan = $ExistTenkenKikan;
	// $myBukken->ExistTenkenKikan_Sougou = $ExistTenkenKikan_Sougou;
	$myBukken->TantoCD1 = $TantoCD1;
	$myBukken->TantoCD2 = $TantoCD2;
	$myBukken->TantoCD3 = $TantoCD3;
	$myBukken->TantoCD4 = $TantoCD4;
	$myBukken->TantoCD5 = $TantoCD5;
	$myBukken->Creator = $UserCD;
	$myBukken->ClientCD = $ClientCD;
	$myBukken->KanriCompanyCD = $KanriCompany;
	$myBukken->BrancheCD = $BrancheCompany;
	// $myBukken->TatoFlg = $TatoFlg;

	// 機器と総合の資料が同じ場合
	// $myBukken->Same_kiki_sougou_flg = $Same_kiki_sougou_flg;

	if ($UserKbn != "3") {
		$myBukken->GyosyaCD = $GyosyaCompany;
	}

	$myBukken->Updater 	= $UserCD;

	//防災情報
	// $myBukken->BousaiTenkenMonth = $BousaiTenkenMonth;
	// $myBukken->BousaiKojiTime = $BousaiKojiTime;
	// $myBukken->GyosyaBousaiCD = $GyosyaBousaiCD;
	// $myBukken->KanriCompanyBousaiCD = $KanriCompanyBousaiCD;
	// $myBukken->BousaiFlg = $BousaiFlg;
	// $myBukken->WorkPlace = $WorkPlace;
	// $myBukken->BoukaBikou = $BoukaBikou;

	//備考欄
	$myBukken->Biko = $Biko;
	$myBukken->MinuteTime = $MinuteTime;
	$myBukken->SenyuStartDate = $SenyuStartDate;
	$myBukken->SenyuEndDate = $SenyuEndDate;
	$myBukken->KyoyobuStartDate = $KyoyobuStartDate;
	$myBukken->KyoyobuEndDate = $KyoyobuEndDate;
	$myBukken->SenyubuStartDate = $SenyubuStartDate;
	$myBukken->SenyubuEndDate = $SenyubuEndDate;
	$myBukken->SenyuStartDate1 = $SenyuStartDate1;
	$myBukken->SenyuEndDate1 = $SenyuEndDate1;
	$myBukken->YoyakuEndDate = $YoyakuEndDate;

	$Holiday = array();
	if ($wUseAppOnly != '1') {
		if(is_array($wHoliday) && count($wHoliday) > 0){
			for ($i = 0; $i < count($wHoliday); $i++) {
				if ($wHoliday[$i]) {
					$Holiday[] = $wHoliday[$i];
				}
			}
		}
		$myBukken->Holiday1 = SPFWTools::encodePluralValue($Holiday); #パイプつなぎ
	}

	$ReserveDay = array();
	if ($wUseAppOnly != '1') {
		if(is_array($wReserveDay) && count($wReserveDay) > 0){
			for ($i = 0; $i < count($wReserveDay); $i++) {
				if ($wReserveDay[$i]) {
					$ReserveDay[] = $wReserveDay[$i];
				}
			}
		}
		$myBukken->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ
	}

	if (!$myBukken->executeUpdate()) {
		trigger_error("executeUpdate(myBukken) Failed.", E_USER_ERROR);
	}

	$newBukkenCD = $myBukken->BukkenCD;
	if ($newBukkenCD > 0) {
		$editBukkenCD = $newBukkenCD;
	}
	unset($myBukken);
	###############################################################
	# 工事日程登録(機器)
	###############################################################
	if ($editBukkenCD > 0) {
		if ($kobetsu) {
			$DBNAME = _MAIN_DB;
			$dsn = 'mysql:host=localhost;dbname=' . $DBNAME . ';charset=utf8';
			$user = 'root';
			$password = '';
			try {
				$dbh = new PDO($dsn, $user, $password);
			} catch (PDOException $e) {
				echo 'データベースにアクセスできません！' . $e->getMessage();
				exit;
			}
			try {
				$options = [
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				];
				$statement = " DELETE FROM tKojiNitteiF WHERE BukkenCD = '" . $editBukkenCD . "' " . "AND TenkenKind = 1 ";
				$stmt = $dbh->prepare($statement);
				$stmt->execute();
			} catch (Exception $ex) {
				var_dump($ex);
			}
		}
	}
	if ($kobetsu) {
		foreach ($kobetsu as $key => $value) {
			$myKojiNittei = new KojiNittei($myDB);
			$myKojiNittei->BukkenCD = $editBukkenCD;
			$myKojiNittei->RoomNumber = $value;
			$myKojiNittei->TenkenKind = 1; //機器

			if (!$myKojiNittei->executeUpdate()) {
				trigger_error("executeUpdate(myKojiNittei) Failed.", E_USER_ERROR);
			}
		}
	}
	// 棟登録
	if ($editBukkenCD > 0) {
		// edit
		$myListObject = new SPFWListObject($myDB);

		$sql = "SELECT ";
		$sql .= "u.BuildingCD ";

		$myListObject->SelectSQL = $sql;

		$sql = " FROM tBuildingM u ";
		$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$editBukkenCD."'";

		$myListObject->Condition = $sql;
		$myListObject->Order = "u.BuildingCD ASC";
		$myListObject->Limit = "allpage";

		if (!($myListObject->GetList(1)))
			trigger_error("Getting User List Failed.", E_USER_ERROR);

		$BuildingCD = [];
		$BuildingLoop = $myListObject->Rows;

		for ($i = 0; $i < $BuildingLoop; $i++) {
			$BuildingCD[$i] = $myListObject->GetValue($i, 0);
		}
		unset($myListObject);

		for ($i = 0; $i < count($BuildingCD); $i++) {
			$editKosu = SPFWParameter::getValues("editKosu".$BuildingCD[$i]);
			if($editKosu){ // edit
				$editBuildingName = SPFWParameter::getValues("editBuildingName".$BuildingCD[$i]);
				$editKaidaka = SPFWParameter::getValues("editKaidaka".$BuildingCD[$i]);
				$editSenyuStartDate = SPFWParameter::getValues("editSenyuStartDate".$BuildingCD[$i]);
				$editSenyuEndDate = SPFWParameter::getValues("editSenyuEndDate".$BuildingCD[$i]);
				$editHoliday = SPFWParameter::getValues("editHoliday".$BuildingCD[$i]);
				$editReserveDay = SPFWParameter::getValues("editReserveDay".$BuildingCD[$i]);
				$editBuildingData = new Building($myDB);
				if (!$editBuildingData->executeSelect("BuildingCD = " . $BuildingCD[$i] . " AND MukouFlg = FALSE", "") || $editBuildingData->RecCnt != 1) {
					trigger_error("Getting Building Failed.", E_USER_ERROR);
				}
				$editBuildingData->BukkenCD = $editBukkenCD;
				$editBuildingData->BuildingName = $editBuildingName;
				$editBuildingData->Kosu = $editKosu;
				$editBuildingData->Kaidaka = $editKaidaka;
				$editBuildingData->SenyuStartDate = $editSenyuStartDate;
				$editBuildingData->SenyuEndDate = $editSenyuEndDate;

				$Holiday = array();
				if ($wUseAppOnly != '1') {
					if(is_array($editHoliday) && count($editHoliday) > 0){
						for ($k = 0; $k < count($editHoliday); $k++) {
							if ($editHoliday[$k]) {
								$Holiday[] = $editHoliday[$k];
							}
						}
					}
					$editBuildingData->Holiday1 = SPFWTools::encodePluralValue($Holiday); #パイプつなぎ
				}

				$ReserveDay = array();
				if ($wUseAppOnly != '1') {
					if(is_array($editReserveDay) && count($editReserveDay) > 0){
						for ($k = 0; $k < count($editReserveDay); $k++) {
							if ($editReserveDay[$k]) {
								$ReserveDay[] = $editReserveDay[$k];
							}
						}
					}
					$editBuildingData->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ
				}

				if (!$editBuildingData->executeUpdate()) {
					trigger_error("executeUpdate(editBuildingData) Failed.", E_USER_ERROR);
				}
				unset($editBuildingData);

			}else{ // delete
				$deleteBuildingData = new Building($myDB);
				$Condition = "BuildingCD = " . $BuildingCD[$i];
				if (!$deleteBuildingData->executeSelect($Condition, NULL) || $deleteBuildingData->RecCnt != 1){
						trigger_error("Getting Building Failed.", E_USER_ERROR);
				}

				$deleteBuildingData->MukouFlg = TRUE;
				$deleteBuildingData->Updater = $UserCD;
				if (!$deleteBuildingData->executeUpdate()){
					trigger_error("Updating User Failed.", E_USER_ERROR);
				}
				unset($deleteBuildingData);

			}

		}


		// add
		if($newKosu && is_array($newKosu)){
			for ($i = 0; $i < count($newKosu); $i++) {
				if ($newKosu[$i]) {

					$newBuildingData = new Building($myDB);
					$newBuildingData->BukkenCD = $editBukkenCD;
					$newBuildingData->BuildingName = isset($newBuilding[$i])?$newBuilding[$i]:'';;
					$newBuildingData->Kosu = isset($newKosu[$i])?$newKosu[$i]:'';
					$newBuildingData->Kaidaka = isset($newKaidaka[$i])?$newKaidaka[$i]:'';
					$newBuildingData->SenyuStartDate = isset($newSenyuStartDate[$i])?$newSenyuStartDate[$i]:'';
					$newBuildingData->SenyuEndDate = isset($newSenyuEndDate[$i])?$newSenyuEndDate[$i]:'';

					$builingNo = $newBuilingNo[$i];
					$wHoliday = SPFWParameter::getValues("newHoliday".$builingNo); // 配列
					$Holiday = array();
					if ($wUseAppOnly != '1') {
						if(is_array($wHoliday) && count($wHoliday) > 0){
							for ($k = 0; $k < count($wHoliday); $k++) {
								if ($wHoliday[$k]) {
									$Holiday[] = $wHoliday[$k];
								}
							}
						}
						$newBuildingData->Holiday1 = SPFWTools::encodePluralValue($Holiday); #パイプつなぎ
					}

					$wReserveDay = SPFWParameter::getValues("newReserveDay".$builingNo); // 配列
					$ReserveDay = array();
					if ($wUseAppOnly != '1') {
						if(is_array($wReserveDay) && count($wReserveDay) > 0){
							for ($k = 0; $k < count($wReserveDay); $k++) {
								if ($wReserveDay[$k]) {
									$ReserveDay[] = $wReserveDay[$k];
								}
							}
						}
						$newBuildingData->ReserveDay = SPFWTools::encodePluralValue($ReserveDay); #パイプつなぎ
					}					

					if (!$newBuildingData->executeUpdate()) {
						trigger_error("executeUpdate(newBuildingData) Failed.", E_USER_ERROR);
					}


				}
			}
		}

	}

	###############################################################
	# 工事日程登録(総合)
	###############################################################
	if ($editBukkenCD > 0) {

		if ($kobetsu_Sougou) {
			$DBNAME = _MAIN_DB;
			$dsn = 'mysql:host=localhost;dbname=' . $DBNAME . ';charset=utf8';
			$user = 'root';
			$password = '';
			try {
				$dbh = new PDO($dsn, $user, $password);
			} catch (PDOException $e) {
				echo 'データベースにアクセスできません！' . $e->getMessage();
				exit;
			}
			try {
				$options = [
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				];
				$statement = " DELETE FROM tKojiNitteiF WHERE BukkenCD = '" . $editBukkenCD . "' " . "AND TenkenKind = 2 ";
				$stmt = $dbh->prepare($statement);
				$stmt->execute();
			} catch (Exception $ex) {
				var_dump($ex);
			}
		}
	}
	if ($kobetsu_Sougou) {

		foreach ($kobetsu_Sougou as $key => $value) {
			$myKojiNittei = new KojiNittei($myDB);
			$myKojiNittei->BukkenCD = $editBukkenCD;
			$myKojiNittei->RoomNumber = $value;
			$myKojiNittei->TenkenKind = 2; //総合

			if (!$myKojiNittei->executeUpdate()) {
				trigger_error("executeUpdate(myKojiNittei) Failed.", E_USER_ERROR);
			}
		}
	}

	SPFWTemplate::dropValue('work');
	// リダイレクト
	$URL = _MAIN_URL . "s_kihon_finish.php?rKey=" . $rKey . "&editBukkenCD=" . $newBukkenCD . "&status=end";
	header('Location: ' . $URL);
	// exit;
} else {
	// リダイレクトで飛んできた場合
	$status  = SPFWParameter::getValues("status");
	if ($status == "end") {

		$IfOK = TRUE;
		$myBukken = new Bukken($myDB);

		if (!$myBukken->executeSelect("BukkenCD = " . $editBukkenCD, "") || $myBukken->RecCnt != 1) {
			trigger_error("Getting myBukken Failed.", E_USER_ERROR);
		}

		$BukkenName = $myBukken->BukkenName;
		unset($myBukken);
	}
}


#######################################################
# 環境依存文字変換関数
#引数：変換前文字
#戻り値：変換後文字
#参考URL：http://wataame.sumomo.ne.jp/archives/1586
#######################################################
function replaceStr($str)
{
	$ret 	= "";
	$str2 	= "";

	$search =  array('Ⅰ', 'Ⅱ', 'Ⅲ', 'Ⅳ', 'Ⅴ', 'Ⅵ', 'Ⅶ', 'Ⅷ', 'Ⅸ', 'Ⅹ', '①', '②', '③', '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '№', '㈲', '㈱');
	$replace = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', '(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)', '(10)', 'No.', '（有）', '（株）');

	// 文字列数ループする
	for ($i = 0; $i < mb_strlen($str); $i++) {

		//1文字ずつ検索し、置き換える
		$bit = mb_substr($str, $i, 1);
		$bit2 = str_replace($search, $replace, $bit);

		// 改行を除去する
		$bit2 = str_replace(array("\r\n", "\r", "\n"), "", $bit2);

		// 1文字ずつ確認しているのでくっつける
		$str2 .= $bit2;
	}
	$ret = $str2;
	return $ret;
}
########################################################
# コンテンツ表示
########################################################
$CNT_FILE = "s_kihon_finish.tpl";
$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
$HiddenValues = $myTemplate->getValuesToPass();
$myTemplate->convertTags();
$myTemplate->outputTemplate();
unset($myTemplate);
unset($myLog);
